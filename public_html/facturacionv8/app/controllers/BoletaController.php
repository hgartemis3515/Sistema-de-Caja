<?php
class BoletaController extends ControllerBase {

	public function procesar_boleta($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
		$herramientas = new HerramientasController;
		$resp_modalidad_envio = $herramientas->get_modalidad_envio_sunat($emisor, $datapost);
		if($resp_modalidad_envio['respuesta'] == 'error') {
			return $resp_modalidad_envio;
		}

		$modalidad_envio_sunat = $resp_modalidad_envio['modalidad_envio_sunat'];
		if(isset($datapost['enviar_a_sunat']) && $datapost['enviar_a_sunat'] == 'no') {
			$modalidad_envio_sunat = 'no_enviar';
		}
		
		$cliente_bd = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $cliente['idcliente'])));
		if(round(floatval($datapost['txt_total_comprobante']), 2) >= 700) {
			if($cliente_bd->id_tipodocidentidad == '1') {
				if($cliente_bd->num_doc == '00000000') {
					$resp_verify['respuesta'] = 'error';
					$resp_verify['titulo'] = 'Error en Cliente';
					$resp_verify['mensaje'] = 'Para Montos Superiores a S/. 700 soles, se debe consignar el nombre y documento electrónico del cliente! ¡Es Obligatorio!';
					return $resp_verify;
				}
			} else {
				/*
				$resp_verify['respuesta'] = 'error';
				$resp_verify['titulo'] = 'Error en Cliente';
				$resp_verify['mensaje'] = 'Para Montos Superiores a S/. 700 soles, se debe consignar el nombre y documento electrónico del cliente! ¡Es Obligatorio!';
				return $resp_verify;
				*/
			}
		}

		$factura = new FacturaController;

		//1.- Guardamos en la base de datos el documento electrónico, pero siempre con un estado PENDIENTE!
		$resp_guardado_bd = $factura->guardar_factura_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		if($resp_guardado_bd['respuesta'] == 'error') {
			return $resp_guardado_bd;
		}
		
		$dominio_principal = $this->get_parametros_iniciales()['url_domain'];
		
		//id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
		$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$resp_guardado_bd['id_tipodoc_electronico']."||".$resp_guardado_bd['serie_comprobante']."||".$resp_guardado_bd['numero_comprobante']."||".$resp_guardado_bd['tipo_envio_sunat']."||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$resp_guardado_bd['id_tipodoc_electronico']."||".$resp_guardado_bd['serie_comprobante']."||".$resp_guardado_bd['numero_comprobante']."||".$resp_guardado_bd['tipo_envio_sunat']."||ticket");

		$url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

		$mensaje_whatsapp = '';
		$url_whatsapp_api = '';
		$url_whatsapp_web = '';
		$url_whatsapp = '';
		if(!empty($cliente['data_cliente']['cliente_celular'])) {
			$mensaje_whatsapp = 'Gracias por confiar en nosotros, desde los siguientes enlaces puedes descargar documento electrónico: Formato A4: '.$url_a4.' y Formato Ticket: '.$url_ticket;
			$url_whatsapp_api = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp_web = 'https://web.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
		}
		
		if($modalidad_envio_sunat == 'no_enviar') {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Completo';
			$mensaje_resp = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><br /><strong>RECUERDA:</strong> El documento no ha sido enviado a SUNAT, por tanto usted debe enviarlo utilizando la pantalla de reporte de documentos!';
			$resp['mensaje'] = $herramientas->get_msg_guardar_doc('primary', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
			$resp['documento'] = $resp_guardado_bd;
			$resp['url_whatsapp_api'] = $url_whatsapp_api;
			$resp['url_whatsapp_web'] = $url_whatsapp_web;
			$resp['url_whatsapp'] = $url_whatsapp;
			return $resp;
		}
        
		//1.- Enviamos hacia la api de boleta.php: Hay que recordar que la boleta solo se debe firmar, no se envía una a una.
		//$modalidad_envio_sunat = 'solo_firma';
		if($modalidad_envio_sunat == 'solo_firma') {
			$resp_respuesta_api = $factura->enviar_factura_api($resp_guardado_bd, $modalidad_envio_sunat, $idvendedor);
			if($resp_respuesta_api['respuesta'] == 'error') {

				$resp['respuesta'] = 'ok';
				$resp['save_doc'] = 'ok';
				$resp['send_api'] = 'error';
				$resp['documento'] = $resp_guardado_bd;
				$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
				$mensaje_resp = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><br /> Sin embargo tuvimos problemas al tratar de firmar el comprobante.';
				$resp['mensaje'] = $herramientas->get_msg_guardar_doc('primary', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
				$resp['url_whatsapp'] = $url_whatsapp;
				$resp['url_whatsapp_api'] = $url_whatsapp_api;
				$resp['url_whatsapp_web'] = $url_whatsapp_web;
				$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
				return $resp;
			}

			$resp['respuesta'] = 'ok';
			$resp['save_doc'] = 'ok';
			$resp['send_api'] = 'ok';
			$resp['documento'] = $resp_guardado_bd;
			$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
			$mensaje_resp = 'El Documento Electrónico : '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].' se Guardó y Firmó Correctamente.<br/><br/>';
			$resp['mensaje'] = $herramientas->get_msg_guardar_doc('success', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
			$resp['url_whatsapp'] = $url_whatsapp;
			$resp['url_whatsapp_api'] = $url_whatsapp_api;
			$resp['url_whatsapp_web'] = $url_whatsapp_web;
			$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
			return $resp;

		} else if($modalidad_envio_sunat == 'inmediato') {

			$resp_respuesta_api = $factura->enviar_factura_api($resp_guardado_bd, 'solo_firma', $idvendedor);
			if($resp_respuesta_api['respuesta'] == 'error') {
				/*
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en API';
				$resp['mensaje'] = 'El documento no pudo ser enviado, pues la firma no se logró realizar correctamente.';
				$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
				return $resp;
				*/
				$resp['respuesta'] = 'ok';
				$resp['save_doc'] = 'ok';
				$resp['send_api'] = 'error';
				$resp['documento'] = $resp_guardado_bd;
				$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
				$mensaje_resp = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><br />Sin embargo tuvimos problemas al tratar de firmar el comprobante.';
				$resp['mensaje'] = $herramientas->get_msg_guardar_doc('primary', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
				$resp['url_whatsapp'] = $url_whatsapp;
				$resp['url_whatsapp_api'] = $url_whatsapp_api;
				$resp['url_whatsapp_web'] = $url_whatsapp_web;
				$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
				return $resp;
			}

			$resumendocumentos = new ResumendeboletasController;
            $confirmacion = 'si';
			$accion = 1;
			$resp_resumen = $resumendocumentos->crear_resumen_individual($resp_guardado_bd, $accion, $confirmacion);
			if($resp_resumen['respuesta'] == 'error') {
				$resp['respuesta'] = 'ok';
				$resp['save_doc'] = 'ok';
				$resp['send_api'] = 'ok';
				$resp['documento'] = $resp_guardado_bd;
				$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
				$mensaje_resp = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><br /> Sin embargo aún está "PENDIENTE DE ENVÍO A SUNAT".';
				$resp['mensaje'] = $herramientas->get_msg_guardar_doc('primary', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
				$resp['url_whatsapp'] = $url_whatsapp;
				$resp['url_whatsapp_api'] = $url_whatsapp_api;
				$resp['url_whatsapp_web'] = $url_whatsapp_web;
				$resp['repuesta_api'] = $resp_resumen['mensaje'];
				return $resp;
			}

			$resp['respuesta'] = 'ok';
			$resp['save_doc'] = 'ok';
			$resp['send_api'] = 'ok';
			$resp['documento'] = $resp_guardado_bd;
			$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
			$mensaje_resp = 'Se guardó y envió a SUNAT correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.';
			$resp['mensaje'] = $herramientas->get_msg_guardar_doc('success', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
			$resp['url_whatsapp'] = $url_whatsapp;
			$resp['url_whatsapp_api'] = $url_whatsapp_api;
			$resp['url_whatsapp_web'] = $url_whatsapp_web;
			$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
			return $resp;
		}
	}
}