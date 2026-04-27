<?php
class NotadedebitoController extends ControllerBase {
    
	public function procesar_notadebito($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {

		$herramientas = new HerramientasController;
		$resp_modalidad_envio = $herramientas->get_modalidad_envio_sunat($emisor, $datapost);
		if($resp_modalidad_envio['respuesta'] == 'error') {
			return $resp_modalidad_envio;
		}
		
		$modalidad_envio_sunat = $resp_modalidad_envio['modalidad_envio_sunat'];

		//1.- Guardamos en la base de datos el documento electrónico, pero siempre con un estado PENDIENTE!
		$resp_guardado_bd = $this->guardar_notadebito_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
        if($resp_guardado_bd['respuesta'] == 'error') {
			return $resp_guardado_bd;
		}

		$dominio_principal = $this->get_parametros_iniciales()['url_domain'];

		//id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
		$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$resp_guardado_bd['id_tipodoc_electronico']."||".$resp_guardado_bd['serie_comprobante']."||".$resp_guardado_bd['numero_comprobante']."||".$resp_guardado_bd['tipo_envio_sunat']."||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$resp_guardado_bd['id_tipodoc_electronico']."||".$resp_guardado_bd['serie_comprobante']."||".$resp_guardado_bd['numero_comprobante']."||".$resp_guardado_bd['tipo_envio_sunat']."||ticket");

		$url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

		$url_whatsapp = '';
		$url_whatsapp_api = '';
		$url_whatsapp_web = '';
		
		if(!empty($cliente['data_cliente']['cliente_celular'])) {
			$mensaje_whatsapp = 'Gracias por confiar en nosotros, desde los siguientes enlaces puedes descargar documento electrónico: Formato A4: '.$url_a4.' y Formato Ticket: '.$url_ticket;
			$url_whatsapp_api = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp_web = 'https://web.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
		}

		$html_botones_impresion = '
		<span class="heading-btn-group">
			<a href="'.$url_a4.'" target="_blank" class="btn btn-link btn-float text-size-small has-text legitRipple"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 50px;"> <span> A4 - PDF</span></a>
			<a href="'.$url_ticket.'" target="_blank" class="btn btn-link btn-float text-size-small has-text legitRipple"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 50px;">  <span> Ticket - PDF</span></a>
		</span>
		';

		if($modalidad_envio_sunat == 'no_enviar') {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Completo';
			$resp['mensaje'] = 'Se guardó correctamente el documento: <strong>'.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><strong>RECUERDA:</strong> El documento no ha sido enviado a SUNAT, por tanto usted debe enviarlo utilizando la pantalla de reporte de documentos!';
			$resp['documento'] = $resp_guardado_bd;
			$resp['url_whatsapp'] = $url_whatsapp;
			$resp['url_whatsapp_api'] = $url_whatsapp_api;
			$resp['url_whatsapp_web'] = $url_whatsapp_web;
			return $resp;
		}

		if($datapost['tipo_docelectronico_modificar'] == '03') { //las notas de una boleta no se envían una a una!
			$modalidad_envio_sunat = 'solo_firma';
		}

		//2.- Enviamos hacia la api de notacredito.php
		$resp_respuesta_api = $this->enviar_notadebito_api($resp_guardado_bd, $modalidad_envio_sunat);
		if($resp_respuesta_api['respuesta'] == 'error') {
			$resp['respuesta'] = 'ok';
			$resp['save_doc'] = 'ok';
			$resp['send_api'] = 'error';
			$resp['documento'] = $resp_guardado_bd;
			$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
			$resp['mensaje'] = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /> <span class="text-danger">Sin embargo aún está "PENDIENTE DE ENVÍO A SUNAT".</span>';
			$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
			$resp['url_whatsapp'] = $url_whatsapp;
			$resp['url_whatsapp_api'] = $url_whatsapp_api;
			$resp['url_whatsapp_web'] = $url_whatsapp_web;
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['save_doc'] = 'ok';
		$resp['send_api'] = 'ok';
		$resp['documento'] = $resp_guardado_bd;
		$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
		$resp['mensaje'] = 'El Documento Electrónico: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'  se Guardó y Envió Correctamente a SUNAT.';
		$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
		$resp['url_whatsapp'] = $url_whatsapp;
		$resp['url_whatsapp_api'] = $url_whatsapp_api;
		$resp['url_whatsapp_web'] = $url_whatsapp_web;
		return $resp;
    }

    public function enviar_notadebito_api($data_doc_bd, $modalidad_envio_sunat) {
        $id_contribuyente = $data_doc_bd['id_contribuyente'];
		$tipo_doc_electronico = $data_doc_bd['id_tipodoc_electronico'];
		$serie_comprobante = $data_doc_bd['serie_comprobante'];
		$numero_comprobante = $data_doc_bd['numero_comprobante'];
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        
        $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_doc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			return $resp;
		}

		$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idsucursal' => $documento->id_sucursal)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'No encontramos la Sucursal a la que pertenece el documento, por favor avise a SOPORTE!';
			return $resp;
		}

		$id_tipo_operacion = !empty($documento->id_tipo_operacion)?$documento->id_tipo_operacion:'0101';
		$sunat_tipooperacion = SunatTipooperacion::findFirst(array("id_codigotipooperacion = :id_codigotipooperacion:", 'bind' => array('id_codigotipooperacion' => $id_tipo_operacion)));
		if(!$sunat_tipooperacion) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Tipo de Operación para el Documento Electrónico no Existe!, por favor consulte con SOPORTE!';
			return $resp;
		}

		$idcliente = $documento->idcliente;

		$documentoelectronico = new DocumentoelectronicoController;
		$resp_secret_data = $documentoelectronico->get_secret_data($id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
		}

		$resp_data_emisor = $documentoelectronico->get_data_emisor($id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
		}

		$resp_data_cliente = $documentoelectronico->get_data_cliente($idcliente);
		if($resp_data_cliente['respuesta'] == 'error') {
			return $resp_data_emisor;
		}

		$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($id_contribuyente, $tipo_doc_electronico, $serie_comprobante, $numero_comprobante);
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$emisor = $resp_data_emisor['emisor'];
		$cliente = $resp_data_cliente;
		$detalle = $resp_detalle['detalle'];

		$cabecera = array(
			"tipo_operacion"				=> $id_tipo_operacion,
			"tipo_operacion_descripcion"	=> !isset($sunat_tipooperacion->descripcion)?'':$sunat_tipooperacion->descripcion,
			'total_gravadas'            	=> $documento->total_gravadas,
	        'total_inafecta'            	=> $documento->total_inafecta,
	        'total_exoneradas'          	=> $documento->total_exoneradas,
			'total_exportacion'         	=> $documento->total_exportacion,
			'porcentaje_igv'            	=> $documento->porcentaje_igv,
			'impuesto_icbper'               => $documento->impuesto_icbper,
			'total_igv'                 	=> $documento->total_igv,
			'total_isc'                 	=> $documento->total_isc,
			"total_icbper"              	=> $documento->total_icbper,
	        'total_otr_imp'             	=> $documento->total_otr_imp,
	        'total'                     	=> $documento->total,
            'tipo_comprobante_modifica' 	=> $documento->id_tipo_comprobante_modifica,
	        'nro_documento_modifica'    	=> $documento->serie_documento_modifica.'-'.$documento->nro_documento_modifica,
	        'cod_tipo_motivo'           	=> $documento->id_cod_tipomotivo_debito,
	        'descripcion_motivo' 			=> $documento->descripcion_motivo_debito,
            'serie_comprobante' 			=> $serie_comprobante,
            'numero_comprobante' 			=> $numero_comprobante,
			'fecha_comprobante' 			=> $documento->fecha_comprobante,
			'cod_tipo_documento' 			=> $tipo_doc_electronico,
            'cod_moneda' 					=> $documento->id_codigomoneda,
			"cod_sucursal_sunat"			=> $sucursal->codigo,
			"tipo_cambio_comprobante"		=> $documento->tipo_cambio_sunat,
			"glosa_amazonia"                => $sucursal->glosa_amazonia
		);
		
		//ordenamos los datos que serán enviados a la RestApi de facturación electrónica.
		$data = array_merge($cabecera, $cliente['data_cliente']);
		$data['emisor'] = $emisor;
		$data['detalle'] = $detalle;
		$data['tipo_proceso'] = $documento->tipo_envio_sunat;
		$data['modalidad_envio_sunat'] = $modalidad_envio_sunat;
		$data['secret_data'] =  $resp_secret_data['secret_data'];
		$data['hash_cpe'] = empty($documento->hash_cpe)?'':$documento->hash_cpe;

		$data['tipo_envio_sistema'] = isset($data_doc_bd['tipo_envio_sistema'])?$data_doc_bd['tipo_envio_sistema']:'';

		$ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'nota_debito/';
		if (!file_exists($ruta_base_cpe_cdr)) {
			mkdir($ruta_base_cpe_cdr, 0777, true);
		}

		if(!empty($documento->name_xml_zip)) {
			$ruta_zip_file_xml = $ruta_base_cpe_cdr.$documento->name_xml_zip;
			if (file_exists($ruta_zip_file_xml)) {
				$data['content_file_zip_cpe'] = base64_encode(file_get_contents($ruta_zip_file_xml));
			}
		}

		$herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/notadebito';
		$resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
		$resp_api = json_decode($resp_api_ws);
		$resp['response'] = $resp_api;
		if(empty($resp_api)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar correctamente a la sunat, el error devuelto es el siguiente: '.$resp_api_ws;
			return $resp;
		}

		if($modalidad_envio_sunat == 'solo_firma') {
			if($resp_api->respuesta == 'error') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat 02';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!<br /><br /> ERROR: '.$resp_api->mensaje;
				return $resp;
			}

			$saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
			if (($saved_file === false) || ($saved_file == -1)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat 03';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!';
				return $resp;
			}

			//$documento->estado_envio_sunat = 'pendiente';
			$documento->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
			$documento->ruta_xml = $data['secret_data']['ruta_dir_xml'];
			$documento->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
			$documento->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;
			$documento->estado_documento = 'activo';

			try {
				if(!$documento->save()) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['error_code'] = 'envio_sunat 05';
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat 06';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego! <br /> ERROR: '.$e->getMessage();
				return $resp;
			}

			$resp['respuesta'] = "ok";
			$resp['titulo'] = 'Proceso Completado';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se firmó correctamente. <br /> Sin embargo recuerda que aún no ha sido enviado a sunat.<br>Hash CPE: '.$documento->hash_cpe;
			return $resp;
		}

		//si pasa aquí se supone que la modalidad es de enviar directo a sunat
		if($resp_api->respuesta == 'error') {

			$codio_error_sunat = isset($resp_api->cod_sunat)?$resp_api->cod_sunat:'';
			if ($codio_error_sunat == 'soap-env:Client.1032') {
				$documento->fecha_envio_sunat = date('Y-m-d H:i:s');
				$documento->estado_envio_sunat = 'anulado';
				$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
				$documento->estado_documento = 'activo';
				$documento->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
				$documento->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
				$documento->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
				$documento->msje_sunat = !isset($resp_api->mensaje)?'':$resp_api->mensaje;
				$mensaje_api_sunat = !isset($resp_api->mensaje)?'':$resp_api->mensaje;

				try {
					if(!$documento->save()) {
						$msg = '';
						foreach ($documento->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'ok';
						$resp['titulo'] = 'Envío Correcto';
						$resp['error_code'] = 'envio_sunat';
						$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' ha sido Anulado. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Envío Correcto';
					$resp['error_code'] = 'envio_sunat';
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' ha sido Anulado. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. <br /> ERROR: '.$e->getMessage();
					return $resp;
				}
	
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Proceso Terminado';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué Anulado por SUNAT.<br /><br />Mensaje Error Sunat: '.$mensaje_api_sunat;
				return $resp;

			}

			$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
			$resp_save = $documento->save();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar a SUNAT, nosotros recomendamos verificar el estado del documento!<br /><br /> ERROR: '.$resp_api->mensaje;
			return $resp;
		}

		if($resp_api->cod_sunat == '0' || $resp_api->cod_sunat == '2223') {
			//2223: El archivo ya fue presentado anteriormente (se entiende que esta factura ya fué enviada en una solicitud anterior, por tanto se la registra como enviada)
			//0: El archivo fué aceptado

			$documento->fecha_envio_sunat = date('Y-m-d H:i:s');
			$documento->estado_envio_sunat = 'aceptado';
			$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
			$documento->estado_documento = 'activo';
			$documento->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
			$documento->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
			$documento->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
			$documento->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
			
			try {
				if(!$documento->save()) {
					$msg = '';
					foreach ($documento->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Envío Correcto';
					$resp['error_code'] = 'envio_sunat';
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. <br /> ERROR: '.$e->getMessage();
				return $resp;
			}

			//Guardamos el archivo zip del xml: colocar el @ delante, captura también los warnings
			$saved_file_xml_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
			if (($saved_file_xml_zip === false) || ($saved_file_xml_zip == -1)) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar correctamente el archivo en el servidor local.';
				return $resp;
			}
			$documento->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
			$documento->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;

			try {
				if(!$documento->save()) {
					$msg = '';
					foreach ($documento->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Envío Correcto';
					$resp['error_code'] = 'envio_sunat';
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. <br /> ERROR: '.$e->getMessage();
				return $resp;
			}
			
			//Guardamos el archivo zip del cdr
			$saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));
			if (($saved_file_cdr_zip === false) || ($saved_file_cdr_zip == -1)) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar correctamente el CDR en el servidor local.';
				return $resp;
			}
			$documento->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
			$documento->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;

			try {
				if(!$documento->save()) {
					$msg = '';
					foreach ($documento->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Envío Correcto';
					$resp['error_code'] = 'envio_sunat';
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta del CDR en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta del CDR en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. <br /> ERROR: '.$e->getMessage();
				return $resp;
			}
			
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Terminado';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT.<br /><br />Código SUNAT: '.$resp_api->cod_sunat.'<br />Mensaje SUNAT: '.$resp_api->msj_sunat.'<br />Digest Value: '.$resp_api->hash_cpe;
			return $resp;

		}
		
		$resp['respuesta'] = 'error';
		$resp['titulo'] = 'Error';
		$resp['mensaje'] = $resp_api->error_mensaje;
		return $resp;
	}
    
    public function guardar_notadebito_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $cabecera_inicial['idsucursal'], 'id_contribuyente' => $id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La Sucursal no es válida!';
			return $resp;
        }
		
		//Verificando los códigos de motivo
        $motivo_nota = SunatTiponotadebito::findFirst(array("id_tiponotadebito = :id_tiponotadebito:", 'bind' => array('id_tiponotadebito' => $datapost['id_motivo_nota_debito'])));
        if(!$motivo_nota) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El motivo o tipo seleccionado para la presente nota de débito no es válido!';
            return $resp;
		}
		
		$this->db->begin();
		$fecha_actual = date('Y-m-d H:i:s');

		if(!isset($datapost['factor_igv_sunat'])) {
			$this->db->rollback();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes ingresar el valor para: Factor IGV SUNAT!!';
			return $resp;
		}

		$factor_igv_sunat = round(floatval($datapost['factor_igv_sunat'])*100, 2);
		if($factor_igv_sunat != 18 && $factor_igv_sunat != 10) {
			$this->db->rollback();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = "El valor ingresa ($factor_igv_sunat) para el Factor IGV SUNAT no es válido";
			return $resp;
		}
		
		if($cabecera_inicial['modo_edicion'] == 'si') {
			$new_documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $cabecera_inicial['id_tipodoc_electronico'], 'serie_comprobante' => $cabecera_inicial['serie_comprobante'], 'numero_comprobante' => $cabecera_inicial['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$new_documento) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				return $resp;
			}

			//Aquí debemos eliminar el detalle del documento actual (si todo el detalle!)
			//1.- Primero debemos guardar el detalle en un garbage collector para poder tener un registro de cambios


			//2.- Eliminar todos los registros del detalle!
			$items_detalle_actual = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $cabecera_inicial['id_tipodoc_electronico'], 'serie_comprobante' => $cabecera_inicial['serie_comprobante'], 'numero_comprobante' => $cabecera_inicial['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$items_detalle_actual->delete()) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento no se puede editar, inténtalo en un momento!!';
				return $resp;
			}
		} else {
			$id_tipodoc_electronico_modifica = $datapost['tipo_docelectronico_modificar'];
			$documento_modifica_array = explode(',', $datapost['serie_numero_doc_modificar']);
			$serie_documento_modifica = $documento_modifica_array[0];
			$numero_documento_modifica = $documento_modifica_array[1];
			
			//verificamos que no existe una nota con el mismo tipo que se intenta crear
			//hay que recordar que solamente se puede crear una nota de débito
			/*
			$verify_previus_note = DocElectronico::findFirst(
				array("
				id_contribuyente = :id_contribuyente: and 
				id_tipodoc_electronico = :id_tipodoc_electronico: and 
				id_tipo_comprobante_modifica = :id_tipo_comprobante_modifica: and 
				serie_documento_modifica = :serie_documento_modifica: and 
				nro_documento_modifica = :nro_documento_modifica: and 
				tipo_envio_sunat = :tipo_envio_sunat: and estado_envio_sunat <> 'anulado'", 
				'bind' => array(
								'id_contribuyente' => $id_contribuyente,
								'id_tipodoc_electronico' => $cabecera_inicial['id_tipodoc_electronico'],
								'id_tipo_comprobante_modifica' => $id_tipodoc_electronico_modifica,
								'serie_documento_modifica' => $serie_documento_modifica, 
								'nro_documento_modifica' => $numero_documento_modifica,
								'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat
							)
						)
					);
			if($verify_previus_note) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Previamente ya se registró la siguiente nota de débito: '.$verify_previus_note->serie_comprobante.'-'.$verify_previus_note->numero_comprobante.', para la presente factura.';
				return $resp;
			}
			*/

			//Verificamos que el documento que se intenta modificar se encuentre en la base de datos.
			$documento_para_modificar = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico_modifica, 'serie_comprobante' => $serie_documento_modifica, 'numero_comprobante' => $numero_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			if(!$documento_para_modificar) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El Documenteo que intenta modificar no existe!';
				return $resp;
			}

			if($documento_para_modificar->porcentaje_igv != $factor_igv_sunat) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = "El factor IGV utilizando en el documento: $serie_documento_modifica-$numero_documento_modifica es $documento_para_modificar->porcentaje_igv por tanto debe ser el mismo factor en la nota de débito.";
				return $resp;
			}

			//Verificamos que el documento que se intenta modificar tenga un CDR válido
			//Referencia: https://goo.gl/Bm65PE
			if($documento_para_modificar->estado_envio_sunat != 'aceptado') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El Documento que intenta modificar no tiene un CDR válido, recuerda que se debe emitir una nota de crédito o débito sobre un documento que haya sido aceptado en sunat y que tenga un CDR válido!';
				return $resp;
			}

			if($id_tipodoc_electronico_modifica == '01') { //modifica una factura
				$serie_comprobante = $sucursal->notadebito_factura_serie;
			} else if ($id_tipodoc_electronico_modifica == '03') { //modifica una boleta
				$serie_comprobante = $sucursal->notadebito_boleta_serie;
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de documento a modificar no es válido!';
				return $resp;
			}

			$id_tipodoc_electronico = $cabecera_inicial['id_tipodoc_electronico'];
			$documentoelectronico = new DocumentoelectronicoController;
			$numero_comprobante = $documentoelectronico->get_numero_doc_electronico($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $sucursal->idsucursal, $id_tipodoc_electronico_modifica);
			
			if($numero_comprobante == 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No hemos logrado extraer la numeración, por favor consulte con soporte!';
				return $resp;
			}

			$new_documento = new DocElectronico();
        
			//DATOS PRINCIPALES COMO PRIMARY KEY PARA UNA NOTA DE DEBITO
			$new_documento->id_contribuyente = $id_contribuyente;
			$new_documento->id_tipodoc_electronico = $id_tipodoc_electronico;
			$new_documento->serie_comprobante = $serie_comprobante;
			$new_documento->numero_comprobante = $numero_comprobante;
			$new_documento->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

			//DATOS DEL COMPROBANTE QUE MODIFICA
			$new_documento->id_tipo_comprobante_modifica = $id_tipodoc_electronico_modifica;
			$new_documento->serie_documento_modifica = $serie_documento_modifica;
			$new_documento->nro_documento_modifica = $numero_documento_modifica;
		}
		
        $id_motivo = $motivo_nota->id_tiponotadebito;
        $descripcion = $motivo_nota->descripcion;

        //MOTIVO DE MODIFICACIÓN PARA LA NOTA DE CRÉDITO
        $new_documento->id_cod_tipomotivo_debito = $id_motivo;
		$new_documento->descripcion_motivo_debito = $descripcion;
		
		//DATA EXTRA
		$array_docs_referencia = array();
		if(!empty($datapost['id_guia_remision_electronica'])) {
			$array_doc_referencia_clie = explode(',', $datapost['id_guia_remision_electronica']);
			$array_docs_referencia[] = array(
				"id_tipodoc_electronico" => '09',
				"serie_comprobante" => $array_doc_referencia_clie[1],
				"numero_comprobante" => $array_doc_referencia_clie[2]
			);
		}
		
		$guias_manuales = empty($datapost['guia_remision_manual'])?'':trim($datapost['guia_remision_manual']);
		if(!empty($guias_manuales)) {
			$lista_guias = array_map('trim', explode(',', $guias_manuales));
			foreach($lista_guias as $guia_remision_manual) {
				if(!empty($guia_remision_manual)) {
					$array_guia_remision_manual = explode('-', $guia_remision_manual);
					$serie_doc_manual = !isset($array_guia_remision_manual[0])?'':trim($array_guia_remision_manual[0]);
					$num_doc_manual = !isset($array_guia_remision_manual[1])?'':trim($array_guia_remision_manual[1]);
					$array_docs_referencia[] = array(
						"id_tipodoc_electronico" => '09',
						"serie_comprobante" => $serie_doc_manual,
						"numero_comprobante" => $num_doc_manual
					);
				}
			}
		}
		
		if(count($array_docs_referencia) > 0) {
			$new_documento->nro_guia_remision = !empty($array_docs_referencia[0]['numero_comprobante'])?$array_docs_referencia[0]['numero_comprobante']:'';
			$new_documento->cod_guia_remision = !empty($array_docs_referencia[0]['serie_comprobante'])?$array_docs_referencia[0]['serie_comprobante']:'';
		} else {
			$new_documento->nro_guia_remision = '';
			$new_documento->cod_guia_remision = '';
		}
		
		$new_documento->nro_otr_comprobante = !empty($datapost['nro_orden'])?$datapost['nro_orden']:'';
		$new_documento->array_docs_referencia = json_encode($array_docs_referencia);
		$new_documento->transporte_nro_placa = !empty($datapost['nro_placa_vehiculo'])?$datapost['nro_placa_vehiculo']:null;
        
        //FECHA Y TIPO DE MONEDA
		$new_documento->fecha_comprobante = $cabecera_inicial['fecha_documento'];
		$new_documento->id_codigomoneda = $datapost['codmoneda_comprobante'];
		$new_documento->tipo_cambio_sunat = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);
        
		//Catálogo N°51
		//$new_documento->id_tipo_operacion = '0101';
		$id_codigotipooperacion = isset($datapost['tipo_operacion_docelectronico'])?$datapost['tipo_operacion_docelectronico']:'0101';
		$new_documento->id_tipo_operacion = $id_codigotipooperacion;

        //TOTALES X TIPO DE AFECTACIÓN DEL IGV : sunat_tipoafectacionigv : Catálogo No. 07: Códigos de Tipo de Afectación del IGV
		$new_documento->total_gravadas = !isset($datapost['txt_gravada_comprobante'])?0:round(floatval($datapost['txt_gravada_comprobante']), 2); //id_tipoafectacionigv: 10
		$new_documento->total_exoneradas = !isset($datapost['txt_exonerada_comprobante'])?0:round(floatval($datapost['txt_exonerada_comprobante']), 2); //id_tipoafectacionigv: 20
		$new_documento->total_inafecta = !isset($datapost['txt_inafecta_comprobante'])?0:round(floatval($datapost['txt_inafecta_comprobante']), 2); //id_tipoafectacionigv: 30
		$new_documento->total_exportacion = !isset($datapost['txt_exportacion_comprobante'])?0:round(floatval($datapost['txt_exportacion_comprobante']), 2); //id_tipoafectacionigv: 40;
		//$new_documento->total_icbper = !isset($datapost['txt_icbper_comprobante'])?0:round(floatval($datapost['txt_icbper_comprobante']), 2); //id_tipoafectacionigv: 7152
		$new_documento->total_icbper = !isset($datapost['txt_icbper_comprobante'])?0:round(floatval($datapost['txt_icbper_comprobante']), 2);
		$new_documento->impuesto_icbper = !isset($datapost['doc_impuesto_icbper'])?0:round(floatval($datapost['doc_impuesto_icbper']), 2);

		//Direfente dirección para cada comprobante:
		$dir_destino = !isset($datapost['cliente_direccion'])?null:$datapost['cliente_direccion'];
		$id_ubigeo_destino = !isset($datapost['select_codigoubigeo'])?null:$datapost['select_codigoubigeo'];

		if(!empty($id_ubigeo_destino)) {
			$codigo_ubigeo_bd = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_destino)));
			if(!$codigo_ubigeo_bd) {
				$id_ubigeo_destino = null;
			}
		}
		$new_documento->dir_destino = empty($dir_destino)?null:$dir_destino;
		$new_documento->id_ubigeo_destino = empty($id_ubigeo_destino)?null:$id_ubigeo_destino;
        
        //TOTALES DOCUMENTO ELECTRÓNICO
		$total = !isset($datapost['txt_total_comprobante'])?0:round(floatval($datapost['txt_total_comprobante']), 2);
		$total_igv = !isset($datapost['txt_igv_comprobante'])?0:round(floatval($datapost['txt_igv_comprobante']), 2);
		$sub_total = round($total - $total_igv, 2);
		$new_documento->sub_total = $sub_total;
		$new_documento->porcentaje_igv = $factor_igv_sunat;
		$new_documento->total_igv = $total_igv;
		$new_documento->total_isc = '0';
		$new_documento->total_otr_imp = '0';
		$new_documento->total = $total;
        $new_documento->total_letras = $datapost['txt_total_letras'];

        //la primera vez siempre se guardará con un estado pendiente
		$new_documento->idcliente = $cliente['idcliente'];
		$new_documento->id_vendedor = $idvendedor;
		$new_documento->id_sucursal = $sucursal->idsucursal;
		$new_documento->estado_envio_sunat = "pendiente";
		$new_documento->fecha_registro = $fecha_actual;
		
		$new_documento->nota = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];

		//Tipo de venta y condición de pago
		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		$tipo_venta = !isset($datapost['opcion_tipo_venta'])?'contado':'credito';
		$monto_adeudado = !isset($datapost['txt_monto_adeudado'])?0:floatval($datapost['txt_monto_adeudado']) + 0;
		if($tipo_venta == 'credito' && $monto_adeudado > 0) {
			$new_documento->id_condicionpago = $cabecera_inicial['id_venta_al_credito'];
			$new_documento->tipo_venta = 'credito';
			$new_documento->monto_adeudado = $monto_adeudado;
			$new_documento->monto_adeudado_inicial = $total;
			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				
			} else {
				$new_documento->fecha_pagopendiente = date("Y-m-d", strtotime($fecha_pagopendiente));
			}

			if($total - $monto_adeudado > 0) {
				$monto_cobrado = $total - $monto_adeudado;
				$new_montocobrado = new MontoCobrado();
				$new_montocobrado->id_contribuyente  = $id_contribuyente;
				$new_montocobrado->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado->serie_comprobante = $serie_comprobante;
				$new_montocobrado->numero_comprobante = $numero_comprobante;
				$new_montocobrado->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado->id_vendedor = $idvendedor;
				$new_montocobrado->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado->id_condicionpago = $cabecera_inicial['condicion_pago_parcial'];
				$new_montocobrado->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado->total = $monto_cobrado;
				$new_montocobrado->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
				$new_montocobrado->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado->detalle = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];
				$new_montocobrado->estado = 'activo';
				$new_montocobrado->fechadeposito = !isset($cabecera_inicial['fecha_deposito_transferencia'])?null:$cabecera_inicial['fecha_deposito_transferencia'];
				$new_montocobrado->idbanco = !isset($cabecera_inicial['idcuenta_banco_deposito'])?null:$cabecera_inicial['idcuenta_banco_deposito'];

				try {
					if(!$new_montocobrado->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$this->db->rollback();
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}
			}	
		} else {
			$new_documento->id_condicionpago = $id_condicionpago;
			$new_documento->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
			$new_documento->tipo_venta = 'contado';
			$new_documento->cpago_fechadeposito = !isset($cabecera_inicial['fecha_deposito_transferencia'])?null:$cabecera_inicial['fecha_deposito_transferencia'];
			$new_documento->cpago_idbanco = !isset($cabecera_inicial['idcuenta_banco_deposito'])?null:$cabecera_inicial['idcuenta_banco_deposito'];
		}
		
		//fin condicion de pago y tipo de venta
        try {
			if(!$new_documento->save()) {
				$this->db->rollback();
				$msg = '';
				foreach ($new_documento->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}

				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $msg;
				return $resp;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			$this->db->rollback();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}
        
		$array_motivos_modifican_stocks = array();
		//01: Interés por Mora 
		//02: Aumento de Valor
		//03: Penalidades/Otros Conceptos
		
        $n = 0;
		foreach($detalle as $item) {
			$n++;
			$new_detalle = new DetalleDoc();
			$new_detalle->id_contribuyente = $new_documento->id_contribuyente;
			$new_detalle->id_tipodoc_electronico = $new_documento->id_tipodoc_electronico;
			$new_detalle->serie_comprobante = $new_documento->serie_comprobante;
			$new_detalle->numero_comprobante = $new_documento->numero_comprobante;
			$new_detalle->tipo_envio_sunat = $new_documento->tipo_envio_sunat;
			$new_detalle->item = $n;
			$new_detalle->id_unidad_medida = $item["UNIDAD_MEDIDA_ID_DET"];
			$new_detalle->unidad_medida = $item["UNIDAD_MEDIDA_DET"];
			$new_detalle->cantidad = $item["CANTIDAD_DET"];
			$new_detalle->precio = $item["PRECIO_DET"] + 0;
			$new_detalle->sub_total = $item["IMPORTE_DET"] + 0; //se puede eliminar
			$new_detalle->importe = $item["IMPORTE_DET"] + 0;
			$new_detalle->id_codigoprecio = $item["PRECIO_TIPO_CODIGO"];
			$new_detalle->igv = $item["IGV_DET"];
			$new_detalle->isc = $item["ISC_DET"];
			$new_detalle->icbper = $item["ICBPER_DET"];
			$new_detalle->id_tipoafectacionigv = $item["COD_TIPO_OPERACION_DET"];
			$new_detalle->id_producto = $item["IDPRODUCTO_DET"];
			$new_detalle->codigo_producto = $item["CODIGO_PRODUCTO"];
			$new_detalle->descripcion = $item["DESCRIPCION_DET"];
			$new_detalle->precio_sin_igv = $item["PRECIO_SIN_IGV_DET"] + 0;
			$new_detalle->factor_igv = $factor_igv_sunat;

			$new_detalle->tipo_unidad = !isset($item["TIPO_UNIDAD"])?'UND':$item["TIPO_UNIDAD"];
    		$new_detalle->id_presentacion = !isset($item["ID_PRESENTACION"])?null:intval($item["ID_PRESENTACION"]);

			try {
				if(!$new_detalle->save()) {
					$this->db->rollback();
					$msg = '';
					foreach ($new_detalle->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
		
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $msg;
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $e->getMessage();
				return $resp;
			}

			if(in_array($id_motivo, $array_motivos_modifican_stocks)) {
				$num_decimales = 2;
				if($contribuyente->num_decimales > 2) {
					$num_decimales = $contribuyente->num_decimales;
				}
	
				$cantidad_kardex = $item["CANTIDAD_DET"];
				if(isset($item["ID_PRESENTACION"]) && intval($item["ID_PRESENTACION"]) > 0) {
					$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item["ID_PRESENTACION"]))));
					if($presentacion) {
						$cantidad_kardex = round($item["CANTIDAD_DET"]*$presentacion->cantidad_und_base, $num_decimales);
					}
				}
	
				//aquí disminuimos el stock para cada uno de los productos en el detalle!
				$modificar_stock = 'si';
				$modificar_stock = $this->get_sucursal_opcion($new_documento->id_contribuyente, $new_documento->id_sucursal, 'notadebito_modifica_stock');
				$modificar_stock = ($modificar_stock == '')?'si':$modificar_stock;
				$modificar_stock = ($modificar_stock == 'si')?'si':'no';

				$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item["IDPRODUCTO_DET"])));
				if($producto && $modificar_stock == 'si') {
					$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
					if($unidad_medida) {
						if($unidad_medida->codigo != 'ZZ') {
	
							$data_kardex['id_contribuyente'] = $new_documento->id_contribuyente;
							$data_kardex['idsucursal'] = $new_documento->id_sucursal;
							$data_kardex['idproducto'] = $producto->idproducto;
							$data_kardex['idusuario'] = $idvendedor;
							$data_kardex['tipo_envio_sunat'] = $new_documento->tipo_envio_sunat; //prueba, produccion
							$data_kardex['tipo_kardex'] = 'venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion	
							$data_kardex['estado_kardex'] = 'activo'; //activo, anulado
							
							$data_kardex['cantidad_salida'] = $cantidad_kardex;
	
							$data_kardex['detalle'] = 'Venta';
							$data_kardex['fecha_registro'] = $fecha_actual;
							$data_kardex['docref_id_contribuyente'] = $new_documento->id_contribuyente;
							$data_kardex['docref_id_tipodoc_electronico'] = $new_documento->id_tipodoc_electronico;
							$data_kardex['docref_serie_comprobante'] = $new_documento->serie_comprobante;
							$data_kardex['docref_numero_comprobante'] = $new_documento->numero_comprobante;
							$data_kardex['docref_tipo_envio_sunat'] = $new_documento->tipo_envio_sunat;
	
							$kardex = new KardexController;
							$resp_kardex = $kardex->registrar_en_kardex($data_kardex);
							if($resp_kardex['respuesta'] == 'error') {
								$this->db->rollback();
								return $resp_kardex;
							}
							
							$producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
							$nuevo_stock = $producto->stock - $cantidad_kardex;
							//if($nuevo_stock < 0) { $nuevo_stock = 0; }
							$producto->stock = $nuevo_stock;

							try {
								if(!$producto->save()) {
									$this->db->rollback();
									$msg = '';
									foreach ($producto->getMessages() as $message) {
										$msg = $msg.$message."</br>\n";
									}
						
									$resp['respuesta'] = 'error';
									$resp['titulo'] = 'Error';
									$resp['mensaje'] = $msg;
									return $resp;
								}
							} catch (Exception $e) {
                				$this->saveLogger($e);
								$this->db->rollback();
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = $e->getMessage();
								return $resp;
							}
						}
					}
				}
			}
		}
		
		
		$resp['respuesta'] = 'ok';
		$resp['id_contribuyente'] = $new_documento->id_contribuyente;
		$resp['id_tipodoc_electronico'] = $new_documento->id_tipodoc_electronico;
		$resp['serie_comprobante'] = $new_documento->serie_comprobante;
		$resp['numero_comprobante'] = $new_documento->numero_comprobante;
		$resp['tipo_envio_sunat'] = $new_documento->tipo_envio_sunat;
		$resp['idusuario'] = $idvendedor;

		$documento_electronico = new DocumentoElectronicoController;
		$resp_reg_relacion = $documento_electronico->registrar_relacion_documento($contribuyente, $sucursal, $idvendedor, $new_documento, $datapost);
		if($resp_reg_relacion['respuesta'] == 'error') {
			$this->db->rollback();
			return $resp_reg_relacion;
		}

		$this->db->commit();
		return $resp;
	}
	
}