<?php
class FacturaController extends ControllerBase {

	public function procesar_factura($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {

		$herramientas = new HerramientasController;
		$resp_modalidad_envio = $herramientas->get_modalidad_envio_sunat($emisor, $datapost);
		if($resp_modalidad_envio['respuesta'] == 'error') {
			return $resp_modalidad_envio;
		}

		$modalidad_envio_sunat = $resp_modalidad_envio['modalidad_envio_sunat'];
		if(isset($datapost['enviar_a_sunat']) && $datapost['enviar_a_sunat'] == 'no') {
			$modalidad_envio_sunat = 'no_enviar';
		}
		
		//1.- Guardamos en la base de datos el documento electrónico, pero siempre con un estado PENDIENTE!
		$resp_guardado_bd = $this->guardar_factura_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
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
		
		if($modalidad_envio_sunat == 'no_enviar') {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Completo';
			$resp['documento'] = $resp_guardado_bd;
			$mensaje_resp = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><br /><strong>RECUERDA:</strong> El documento no ha sido enviado a SUNAT, por tanto usted debe enviarlo utilizando la pantalla de reporte de documentos!';
			$resp['mensaje'] = $herramientas->get_msg_guardar_doc('primary', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
			$resp['url_whatsapp'] = $url_whatsapp;
			$resp['url_whatsapp_api'] = $url_whatsapp_api;
			$resp['url_whatsapp_web'] = $url_whatsapp_web;
			return $resp;
		}

		//2.- Enviamos hacia la api de factura.php
		$resp_respuesta_api = $this->enviar_factura_api($resp_guardado_bd, $modalidad_envio_sunat, $idvendedor);
		if($resp_guardado_bd['respuesta'] == 'ok' && $resp_guardado_bd['respuesta'] == 'error') {
			$resp['respuesta'] = 'ok';
			$resp['save_doc'] = 'ok';
			$resp['send_api'] = 'error';
			$resp['documento'] = $resp_guardado_bd;
			$resp['titulo'] = 'El Documento se ha Guardado Correctamente!';
			$mensaje_resp = 'Se guardó correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.<br /><br /> Sin embargo aún está "PENDIENTE DE ENVÍO A SUNAT"';
			$resp['mensaje'] = $herramientas->get_msg_guardar_doc('primary', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
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
		$mensaje_resp = 'Se guardó y envió a SUNAT correctamente el documento: '.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'.';
		$resp['mensaje'] = $herramientas->get_msg_guardar_doc('success', $mensaje_resp, $url_a4, $url_ticket, $url_whatsapp);
		$resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
		$resp['url_whatsapp'] = $url_whatsapp;
		$resp['url_whatsapp_api'] = $url_whatsapp_api;
		$resp['url_whatsapp_web'] = $url_whatsapp_web;
		return $resp;
	}

	public function enviar_factura_api($data_doc_bd, $modalidad_envio_sunat, $idvendedor = '') {
		
		$id_contribuyente = $data_doc_bd['id_contribuyente'];
		$tipo_doc_electronico = $data_doc_bd['id_tipodoc_electronico'];
		$serie_comprobante = $data_doc_bd['serie_comprobante'];
		$numero_comprobante = $data_doc_bd['numero_comprobante'];

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el contribuyente!';
			return $resp;
		}

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

		$sunat_tipooperacion = SunatTipooperacion::findFirst(array("id_codigotipooperacion = :id_codigotipooperacion:", 'bind' => array('id_codigotipooperacion' => $documento->id_tipo_operacion)));
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
			return $resp_data_cliente;
		}

		$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($id_contribuyente, $tipo_doc_electronico, $serie_comprobante, $numero_comprobante);
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$emisor = $resp_data_emisor['emisor'];
		$cliente = $resp_data_cliente;
		$detalle = $resp_detalle['detalle'];
		

		$forma_pago['forma_de_pago']			= 'contado'; //contado, credito
		$forma_pago['monto_deuda_total']		= 0; //Monto total adeudado sin condierar detracciones o retenciones
		$forma_pago['detalle_cuotas']			= array();

		if($contribuyente->informar_condpago_sunat == 'si') {
			if($documento->tipo_venta == 'credito') {
				if($documento->monto_adeudado > 0) {

					$cuentasporcobrar = new CuentasporcobrarController;
					$resp_cuotas = $cuentasporcobrar->get_lista_abonos($id_contribuyente, $tipo_doc_electronico, $serie_comprobante, $numero_comprobante, $contribuyente->tipo_envio_sunat);
					if(count($resp_cuotas['lista_cuotas']) > 0) {
						$forma_pago['forma_de_pago']			= 'credito'; //contado, credito
						$forma_pago['monto_deuda_total']		= $documento->monto_adeudado; //Monto total adeudado sin condierar detracciones o retenciones

						foreach($resp_cuotas['lista_cuotas'] as $item_cuota) {
							$forma_pago['detalle_cuotas'][]			= array(
								'fecha_vencimiento'	=> date("Y-m-d", strtotime($item_cuota['fecha_vencimiento'])),
								'monto_cuota'		=> $item_cuota['monto_cuota']
							);
						}
					}
				}
			}
		}

		$retencion['aplica_retencion'] = 'no';
		$retencion['data_retencion'] = array();
		if($documento->id_tipodoc_electronico == '01') {
			$retencion['aplica_retencion'] = isset($documento->retencion_aplica)?$documento->retencion_aplica:'no';
			$retencion['aplica_retencion'] = ($retencion['aplica_retencion'] == 'si')?'si':'no';
			if($retencion['aplica_retencion'] == 'si') {
				$retencion['data_retencion'] = array(
					'base_imponible' => $documento->retencion_base,
					'factor' => $documento->retencion_factor,
					'monto' => $documento->retencion_monto //La moneda debe ser la misma en todo el documento. Salvo las percepciones que sólo son en moneda nacional
				);
			}
		}

		$monto_detraccion = $documento->detraccion_monto;

		$data_factura = array(
			"tipo_operacion"				=> $documento->id_tipo_operacion,
			"tipo_operacion_descripcion"	=> !isset($sunat_tipooperacion->descripcion)?'':$sunat_tipooperacion->descripcion,
			"total_gravadas"               	=> $documento->total_gravadas,
			"total_inafecta"                => $documento->total_inafecta,
			"total_exoneradas"				=> $documento->total_exoneradas,
			"total_gratuitas"			    => $documento->total_gratuitas,
			"total_exportacion"		    	=> $documento->total_exportacion,
			"total_descuento"	    		=> $documento->total_descuento,
			"sub_total"              		=> $documento->sub_total,
			"porcentaje_igv"                => $documento->porcentaje_igv,
			'impuesto_icbper'               => $documento->impuesto_icbper,
			"total_igv"                     => $documento->total_igv,
			"total_isc"                   	=> $documento->total_isc,
			"total_icbper"                  => $documento->total_icbper,
			"total_otr_imp"                 => $documento->total_otr_imp,
			"total"                  		=> $documento->total,
			"total_letras"              	=> $documento->total_letras,
			"nro_guia_remision"             => !empty($documento->nro_guia_remision)?$documento->nro_guia_remision:'',
			"cod_guia_remision"             => !empty($documento->cod_guia_remision)?$documento->cod_guia_remision:'',
			"nro_otr_comprobante"           => !empty($documento->nro_otr_comprobante)?$documento->nro_otr_comprobante:'',
			"transporte_nro_placa"			=> !empty($documento->transporte_nro_placa)?$documento->transporte_nro_placa:'',
			"docs_referencia"               => json_decode($documento->array_docs_referencia),
			"serie_comprobante"             => $serie_comprobante, //Para Facturas la serie debe comenzar por la letra F, seguido de tres dígitos
			"numero_comprobante"            => $numero_comprobante,
			"fecha_comprobante"             => $documento->fecha_comprobante,
			"fecha_vto_comprobante"         => $documento->fecha_vto_comprobante,
			"cod_tipo_documento"            => $tipo_doc_electronico,
			"cod_moneda"                    => $documento->id_codigomoneda,
			"cod_sucursal_sunat"			=> $sucursal->codigo,
			"tipo_cambio_comprobante"		=> $documento->tipo_cambio_sunat,
			"glosa_amazonia"                => $sucursal->glosa_amazonia,
			

			//detracción
			"detraccion_id_mediopago" 		=> $documento->detraccion_id_mediopago,
			"detraccion_cuenta" 			=> $documento->detraccion_cuenta,
			"detraccion_iddetraccion" 		=> $documento->detraccion_iddetraccion,
			"detraccion_porcentaje" 		=> $documento->detraccion_porcentaje,
			"detraccion_monto" 				=> $monto_detraccion, //el monto de la detracción siempre debe ser en soles
			"detraccion_texto" 				=> $documento->detraccion_texto,

			//percepción
			"percepcion_idtipo" => $documento->percepcion_idtipo,
			"percepcion_montobase" => $documento->percepcion_montobase,
			"percepcion_porcentaje" => $documento->percepcion_porcentaje,
			"percepcion_monto" => $documento->percepcion_monto
		);

		$data = array_merge($data_factura, $cliente['data_cliente']);
		$data = array_merge($data, $forma_pago);
		$data = array_merge($data, $retencion);

		$resp_anticipos = $this->get_anticipos($documento);
		$data['lista_anticipos'] = $resp_anticipos['lista_anticipos'];
		$data['total_anticipos'] = $resp_anticipos['sum_anticipos'];
		
		$data['emisor'] = $emisor;
		$data['detalle'] = $detalle;
		$data['tipo_proceso'] = $documento->tipo_envio_sunat;
		$data['modalidad_envio_sunat'] = $modalidad_envio_sunat;
		$data['secret_data'] =  $resp_secret_data['secret_data'];
		$data['hash_cpe'] = empty($documento->hash_cpe)?'':$documento->hash_cpe;

		$data['tipo_envio_sistema'] = isset($data_doc_bd['tipo_envio_sistema'])?$data_doc_bd['tipo_envio_sistema']:'';

		$ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'facturas_boletas/';
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
		$ruta = 'https://facturalahoy.com/api/factura';

		$resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);

		
		$resp_api = json_decode($resp_api_ws);
		$resp['data_envida'] = $data;
		$resp['ruta_envida'] = $ruta;
		$resp['respuesta_api'] = $resp_api;
		if(empty($resp_api)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = '<strong class="text-success">El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. </strong><br /> Sin embargo no se logró enviar correctamente a la sunat, el error devuelto es el siguiente: '.$resp_api_ws;
			return $resp;
		}

		if($modalidad_envio_sunat == 'solo_firma') {
			if($resp_api->respuesta == 'error') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin Embargo no se ha logrado crear el XML!<br /><br /> ERROR: '.$resp_api->mensaje;
				return $resp;
			}

			$saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
			if (($saved_file === false) || ($saved_file == -1)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
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
					$resp['error_code'] = 'envio_sunat';
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego! '.$e->getMessage();
				return $resp;
			}

			$resp['respuesta'] = "ok";
			$resp['titulo'] = 'Proceso Completado';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se firmó correctamente. <br /> Sin embargo recuerda que aún no ha sido enviado a sunat.<br>Hash CPE: '.$documento->hash_cpe;
			return $resp;
		}

		//si pasa aquí se supone que la modalidad es de enviar directo a sunat

		//si la modalidad es directo a firmar, entonces definitivamente es posible que hasta aquí se haya creado el xml y se haya firmado
		$resp['xml_cpe_save'] = 'false';
		if(!empty($resp_api->name_file_zip_cpe) && !empty($resp_api->file_cpe_zip)) {
			//Guardamos el archivo zip del xml: colocar el @ delante, captura también los warnings
			$saved_file_xml_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
			if (($saved_file_xml_zip === false) || ($saved_file_xml_zip == -1)) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo la respuesta no se logró guardar en el servidor local.';
				return $resp;
			}
			$documento->ruta_xml = $data['secret_data']['ruta_dir_xml'];
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
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta xml en el servidor<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta xml en el servidor<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
				return $resp;
			}

			$resp['xml_cpe_save'] = 'true';
		}

		if(!isset($resp_api->cod_sunat)) {
			$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
			$resp_save = $documento->save();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$mensaje_error = '';
			if(isset($resp_api->mensaje)) {{
				$mensaje_error = $resp_api->mensaje;
			}}
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar a SUNAT, nosotros recomendamos verificar el estado del documento!<br /><br /> ERROR: '.$mensaje_error;
			$resp['response_api'] = !empty($resp_api)?$resp_api:'';
			return $resp;
		}

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
					$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' ha sido Anulado. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
					return $resp;
				}

				$resp = $this->devolver_stock_almacen($documento->id_contribuyente, $documento->id_tipodoc_electronico, $documento->serie_comprobante, $documento->numero_comprobante, $documento->tipo_envio_sunat, $idvendedor);
	
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Proceso Terminado';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué Anulado por SUNAT.<br /><br />Mensaje Error Sunat: '.$mensaje_api_sunat;
				return $resp;

			}
			/* else {
				$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
				$resp_save = $documento->save();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar a SUNAT, nosotros recomendamos verificar el estado del documento!<br /><br /> ERROR: '.$resp_api->mensaje;
				return $resp;
			}*/
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
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
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
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta del CDR en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
				return $resp;
			}
			
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Terminado';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT.<br /><br />Código SUNAT: '.$resp_api->cod_sunat.'<br />Mensaje SUNAT: '.$resp_api->msj_sunat.'<br />Digest Value: '.$resp_api->hash_cpe;
			return $resp;
		}

		$mensaje_respuesta_sunat = '';
		$codigo_error_sunat = '';
		if($resp_api->respuesta == 'error') {
			$mensaje_respuesta_sunat = $resp_api->mensaje;
			$codigo_error_sunat = $resp_api->cod_sunat;
		} else {
			$mensaje_respuesta_sunat = $resp_api->msj_sunat;
			$codigo_error_sunat = $resp_api->cod_sunat;
		}

		$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
		$resp_save = $documento->save();
		$resp['respuesta'] = 'error';
		$resp['titulo'] = 'Error - '.$codigo_error_sunat;
		$resp['error_code'] = 'envio_sunat';
		$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar a SUNAT, nosotros recomendamos verificar el estado del documento!<br /><br /> ERROR: '.$mensaje_respuesta_sunat;
		return $resp;
	}

	public function get_anticipos($documento) {
		$lista_anticipos = DocElectronicoAnticipo::find(array("cpe_id_contribuyente = :id_contribuyente: and cpe_id_tipodoc_electronico = :id_tipodoc_electronico: and cpe_serie_comprobante = :serie_comprobante: and cpe_numero_comprobante = :numero_comprobante: and cpe_tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 'serie_comprobante' => $documento->serie_comprobante, 'numero_comprobante' => $documento->numero_comprobante, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

		$anticipos = array();
		$num_anticipo = 0;
		$sum_anticipos = 0;
		foreach($lista_anticipos as $item) {
			$num_anticipo++;
			$anticipos[] = array(
				'num_anticipo' => $num_anticipo,
				'id_tipodoc_electronico' => $item->anticipo_id_tipodoc_electronico,
				'serie_comprobante' => $item->anticipo_serie_comprobante,
				'numero_comprobante' => $item->anticipo_numero_comprobante,
				'monto_anticipo' => $item->monto_anticipo
			);
			$sum_anticipos = $sum_anticipos + floatval($item->monto_anticipo);
		}

		$resp['lista_anticipos'] = $anticipos;
		$resp['sum_anticipos'] = $sum_anticipos;
		return $resp;
	}

	public function devolver_stock_almacen($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat, $idusuario) {
		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el documento!';
			return $resp;
		}

		$detalle_documento = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 'serie_comprobante' => $documento->serie_comprobante, 'numero_comprobante' => $documento->numero_comprobante, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

		foreach($detalle_documento as $item) {
            $producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto)));
            if($producto) {
				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
				if($unidad_medida) {
					if($unidad_medida->codigo != 'ZZ') {

						$cantidad_kardex = $item->cantidad;
						if(isset($item->id_presentacion) && intval($item->id_presentacion) > 0) {
							$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item->id_presentacion))));
							if($presentacion) {
								$cantidad_kardex = round($item->cantidad*$presentacion->cantidad_und_base, 4);
							}
						}
						
                        if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03' || $documento->id_tipodoc_electronico == '08') {
                            //primero verificamos que no exista un registro de venta eliminada, para evitar que se creen registros duplicados
                            $kardex_doc_anulado = Kardex::findFirst(array("
									docref_id_contribuyente 		= :docref_id_contribuyente: and 
									docref_id_tipodoc_electronico 	= :docref_id_tipodoc_electronico: and 
									docref_serie_comprobante 		= :docref_serie_comprobante: and 
									docref_numero_comprobante 		= :docref_numero_comprobante: and 
									docref_tipo_envio_sunat 		= :docref_tipo_envio_sunat: and 
									idproducto 						= :idproducto: and 
									id_compra 						is null and 
									tipo_kardex 					= 'anulacion_venta'
								", 'bind' => array(
									'docref_id_contribuyente' 		=> $documento->id_contribuyente, 
									'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
									'docref_serie_comprobante' 		=> $documento->serie_comprobante, 
									'docref_numero_comprobante' 	=> $documento->numero_comprobante, 
									'docref_tipo_envio_sunat' 		=> $documento->tipo_envio_sunat, 
									'idproducto' 					=> $producto->idproducto
								), "order" => "id_kardex DESC"));

                            if(!$kardex_doc_anulado) {
                                //Una factura, boleta y nota de débito anulada representa un ingreso, ya que el producto vuelve a ingresar a almacén.
                                $kardex_guardado = Kardex::findFirst(array("
										docref_id_contribuyente 		= :docref_id_contribuyente: and 
										docref_id_tipodoc_electronico 	= :docref_id_tipodoc_electronico: and 
										docref_serie_comprobante 		= :docref_serie_comprobante: and 
										docref_numero_comprobante 		= :docref_numero_comprobante: and 
										docref_tipo_envio_sunat 		= :docref_tipo_envio_sunat: and 
										idproducto 						= :idproducto: and 
										id_compra 						is null
									", 'bind' => array(
										'docref_id_contribuyente' 		=> $documento->id_contribuyente, 
										'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
										'docref_serie_comprobante' 		=> $documento->serie_comprobante, 
										'docref_numero_comprobante' 	=> $documento->numero_comprobante, 
										'docref_tipo_envio_sunat' 		=> $documento->tipo_envio_sunat, 
										'idproducto' 					=> $producto->idproducto
									), "order" => "id_kardex DESC"));
                                                            
                                if($kardex_guardado) {
                                    $data_kardex['id_contribuyente'] = $documento->id_contribuyente;
                                    $data_kardex['idsucursal'] = $documento->id_sucursal;
                                    $data_kardex['idproducto'] = $item->id_producto;
                                    $data_kardex['idusuario'] = $idusuario;
                                    $data_kardex['tipo_envio_sunat'] = $documento->tipo_envio_sunat; //prueba, produccion
                                    $data_kardex['tipo_kardex'] = 'anulacion_venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
                                    $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                                    $data_kardex['cantidad_entrada'] = $cantidad_kardex;
                                    $data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario_promedio;;

                                    $data_kardex['detalle'] = 'Anulación de Comprobante de Venta';
                                    $data_kardex['fecha_registro'] = date('Y-m-d H:i:s');

                                    $data_kardex['docref_id_contribuyente'] = $documento->id_contribuyente;
                                    $data_kardex['docref_id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
                                    $data_kardex['docref_serie_comprobante'] = $documento->serie_comprobante;
                                    $data_kardex['docref_numero_comprobante'] = $documento->numero_comprobante;
                                    $data_kardex['docref_tipo_envio_sunat'] = $documento->tipo_envio_sunat;
                                    
                                    $kardex = new KardexController;
                                    $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                                    if($resp_kardex['respuesta'] == 'error') {
                                        return $resp_kardex;
                                    }

									$producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
                                }


                                $producto->stock = $producto->stock + $cantidad_kardex;

								try {
									if(!$producto->save()) {
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
									$resp['respuesta'] = 'error';
									$resp['titulo'] = 'Error';
									$resp['mensaje'] = $e->getMessage();
									return $resp;
								}
                            }
                        }
            
                        if($documento->id_tipodoc_electronico == '07') {
                            $kardex_doc_anulado = Kardex::findFirst(array("
									docref_id_contribuyente 		= :docref_id_contribuyente: and 
									docref_id_tipodoc_electronico 	= :docref_id_tipodoc_electronico: and 
									docref_serie_comprobante 		= :docref_serie_comprobante: and 
									docref_numero_comprobante 		= :docref_numero_comprobante: and 
									docref_tipo_envio_sunat 		= :docref_tipo_envio_sunat: and 
									idproducto 						= :idproducto: and 
									id_compra 						is null and 
									tipo_kardex 					= 'anulacion_venta'
								", 'bind' => array(
									'docref_id_contribuyente' 		=> $documento->id_contribuyente, 
									'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
									'docref_serie_comprobante' 		=> $documento->serie_comprobante, 
									'docref_numero_comprobante' 	=> $documento->numero_comprobante, 
									'docref_tipo_envio_sunat' 		=> $documento->tipo_envio_sunat, 
									'idproducto' 					=> $producto->idproducto
								), "order" => "id_kardex DESC"));

                            if(!$kardex_doc_anulado) {
                                //Una Nota de Crédito Anulada indica que la mercadería sigue siendo válida para una factura o boleta, por lo tanto una nota de crédito anulada representará una salida.
                                $kardex_guardado = Kardex::findFirst(array("docref_id_contribuyente = :docref_id_contribuyente: and docref_id_tipodoc_electronico = :docref_id_tipodoc_electronico: and docref_serie_comprobante = :docref_serie_comprobante: and docref_numero_comprobante = :docref_numero_comprobante: and docref_tipo_envio_sunat = :docref_tipo_envio_sunat: and idproducto = :idproducto: and id_compra is null", 'bind' => array('docref_id_contribuyente' => $documento->id_contribuyente, 'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 'docref_serie_comprobante' => $documento->serie_comprobante, 'docref_numero_comprobante' => $documento->numero_comprobante, 'docref_tipo_envio_sunat' => $documento->tipo_envio_sunat, 'idproducto' => $producto->idproducto), "order" => "id_kardex DESC"));

                                if($kardex_guardado) {
                                    $data_kardex['id_contribuyente'] = $documento->id_contribuyente;
                                    $data_kardex['idsucursal'] = $documento->id_sucursal;
                                    $data_kardex['idproducto'] = $item->id_producto;
                                    $data_kardex['idusuario'] = $idusuario;
                                    $data_kardex['tipo_envio_sunat'] = $documento->tipo_envio_sunat; //prueba, produccion
                                    $data_kardex['tipo_kardex'] = 'venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
                                    $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                                    $data_kardex['cantidad_salida'] = $cantidad_kardex;
                                    $data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario;

                                    $data_kardex['detalle'] = 'Anulación de Nota de Crédito de Venta';
                                    $data_kardex['fecha_registro'] = date('Y-m-d H:i:s');

                                    $data_kardex['docref_id_contribuyente'] = $documento->id_contribuyente;
                                    $data_kardex['docref_id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
                                    $data_kardex['docref_serie_comprobante'] = $documento->serie_comprobante;
                                    $data_kardex['docref_numero_comprobante'] = $documento->numero_comprobante;
                                    $data_kardex['docref_tipo_envio_sunat'] = $documento->tipo_envio_sunat;
                                    
                                    $kardex = new KardexController;
                                    $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                                    if($resp_kardex['respuesta'] == 'error') {
                                        return $resp_kardex;
                                    }
                                    
									$producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];

                                    $nuevo_stock = $producto->stock - $cantidad_kardex;
                                    //if($nuevo_stock < 0) { $nuevo_stock = 0; }
                                    $producto->stock = $nuevo_stock;
                                    
									try {
										if(!$producto->save()) {
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
            }
		}
		
		$resp['respuesta'] = 'ok';
		return $resp;
	}
	
	public function guardar_factura_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $cabecera_inicial['idsucursal'], 'id_contribuyente' => $id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La Sucursal no es válida!';
			return $resp;
		}
		
		$id_tipodoc_electronico = $cabecera_inicial['id_tipodoc_electronico'];
		if($id_tipodoc_electronico == '01') {
			$serie_comprobante = $sucursal->factura_serie;
		} else {
			$serie_comprobante = $sucursal->boleta_serie;
		}

		$this->db->begin();
		$fecha_actual = date('Y-m-d H:i:s');

		if($cabecera_inicial['modo_edicion'] == 'si') {
			$new_documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $cabecera_inicial['id_tipodoc_electronico'], 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $cabecera_inicial['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$new_documento) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				return $resp;
			}

			$numero_comprobante = $new_documento->numero_comprobante;

			//Aquí debemos eliminar el detalle del documento actual (si todo el detalle!)
			//1.- Primero debemos guardar el detalle en un garbage collector para poder tener un registro de cambios


			//2.- Eliminar todos los registros del detalle!
			$items_detalle_actual = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $cabecera_inicial['id_tipodoc_electronico'], 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $cabecera_inicial['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$items_detalle_actual->delete()) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento no se puede editar, inténtalo en un momento!!';
				return $resp;
			}

		} else {
			$documentoelectronico = new DocumentoelectronicoController;
			$numero_comprobante = $documentoelectronico->get_numero_doc_electronico($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $sucursal->idsucursal);
			$new_documento = new DocElectronico();

			//DATOS PRINCIPALES COMO PRIMARY KEY PARA UNA FACTURA
			$new_documento->id_contribuyente = $id_contribuyente;
			$new_documento->id_tipodoc_electronico = $id_tipodoc_electronico;
			$new_documento->serie_comprobante = $serie_comprobante;
			$new_documento->numero_comprobante = $numero_comprobante;
			$new_documento->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

			//FECHA
			$new_documento->fecha_comprobante = $cabecera_inicial['fecha_documento'];
			$new_documento->fecha_vto_comprobante = $cabecera_inicial['fecha_vence_documento'];
		}
		
		//TIPO DE MONEDA
		$new_documento->id_codigomoneda = $datapost['codmoneda_comprobante'];
		$new_documento->tipo_cambio_sunat = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);

		//Catálogo N°51
		//$new_documento->id_tipo_operacion = '0101';
		$id_codigotipooperacion = isset($datapost['tipo_operacion_docelectronico'])?$datapost['tipo_operacion_docelectronico']:'0101';
		$new_documento->id_tipo_operacion = $id_codigotipooperacion;

		//retención
		$aplica_retencion = isset($datapost['select_aplica_retencion'])?$datapost['select_aplica_retencion']:'no';
		$aplica_retencion = ($aplica_retencion == 'si')?'si':'no';

		if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
			//para detracciones
			$new_documento->detraccion_id_mediopago = !isset($datapost['detraccion_tipo_pago'])?null:$datapost['detraccion_tipo_pago'];
			$new_documento->detraccion_cuenta = !isset($cabecera_inicial['nro_cuenta_detraccion'])?null:$cabecera_inicial['nro_cuenta_detraccion'];
			$new_documento->detraccion_iddetraccion = !isset($datapost['detraccion_codigo_bien'])?null:$datapost['detraccion_codigo_bien'];
			$new_documento->detraccion_porcentaje = !isset($datapost['porcentaje_detraccion'])?null:floatval($datapost['porcentaje_detraccion']);
			$new_documento->detraccion_monto = !isset($datapost['monto_detraccion'])?null:round(floatval($datapost['monto_detraccion']), 2);
			$new_documento->detraccion_texto = !isset($datapost['texto_detraccion'])?null:$datapost['texto_detraccion'];
		}

		if($aplica_retencion == 'si') {
			$new_documento->retencion_aplica = 'si';
			$new_documento->retencion_factor = isset($datapost['porcentaje_retencion'])?(round(floatval($datapost['porcentaje_retencion'])/100, 5)):0;
			$new_documento->retencion_monto = !isset($datapost['monto_retencion'])?null:round(floatval($datapost['monto_retencion']), 5);
			$new_documento->retencion_base = isset($datapost['base_imponible_retencion'])?(round(floatval($datapost['base_imponible_retencion']), 2)):0;
		}

		if($id_codigotipooperacion == '2001') {
			//para percepciones
			$new_documento->percepcion_idtipo = !isset($datapost['tipo_percepcion'])?null:$datapost['tipo_percepcion'];
			$new_documento->percepcion_montobase = !isset($datapost['monto_base_percepcion'])?null:floatval($datapost['monto_base_percepcion']);
			$new_documento->percepcion_porcentaje = !isset($datapost['porcentaje_percepcion'])?null:floatval($datapost['porcentaje_percepcion']);
			$new_documento->percepcion_monto = !isset($datapost['monto_percepcion'])?null:floatval($datapost['monto_percepcion']);
		}

		//TOTALES X TIPO DE AFECTACIÓN DEL IGV : sunat_tipoafectacionigv : Catálogo No. 07: Códigos de Tipo de Afectación del IGV
		$new_documento->total_gravadas = !isset($datapost['txt_gravada_comprobante'])?0:round(floatval($datapost['txt_gravada_comprobante']), 2); //id_tipoafectacionigv: 10
		$new_documento->total_exoneradas = !isset($datapost['txt_exonerada_comprobante'])?0:round(floatval($datapost['txt_exonerada_comprobante']), 2); //id_tipoafectacionigv: 20
		$new_documento->total_inafecta = !isset($datapost['txt_inafecta_comprobante'])?0:round(floatval($datapost['txt_inafecta_comprobante']), 2); //id_tipoafectacionigv: 30
		$new_documento->total_exportacion = !isset($datapost['txt_exportacion_comprobante'])?0:round(floatval($datapost['txt_exportacion_comprobante']), 2); //id_tipoafectacionigv: 40;
		$new_documento->total_gratuitas = !isset($datapost['txt_gratuita_comprobante'])?0:round(floatval($datapost['txt_gratuita_comprobante']), 2); //id_tipoafectacionigv: todas las demás
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

		if(!isset($datapost['factor_igv_sunat'])) {
			$this->db->rollback();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes ingresar el valor para: Factor IGV SUNAT!!';
			return $resp;
		}

		$factor_igv_sunat = round(floatval($datapost['factor_igv_sunat'])*100, 3);
		$valores_validos_factor_igv = $this->get_valores_validos_factor_igv();
		if(!in_array($factor_igv_sunat, $valores_validos_factor_igv)) {
			$this->db->rollback();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = "El valor ingresado ($factor_igv_sunat) para el Factor IGV SUNAT no es válido, valores permitidos son: ".implode(', ', $valores_validos_factor_igv);
			return $resp;
		}

		//TOTALES DOCUMENTO ELECTRÓNICO
		$total = !isset($datapost['txt_total_comprobante'])?0:round(floatval($datapost['txt_total_comprobante']), 2);
		$total_igv = !isset($datapost['txt_igv_comprobante'])?0:round(floatval($datapost['txt_igv_comprobante']), 2);
		$sub_total = round($total - $total_igv, 2);
		$new_documento->total_descuento = !isset($datapost['txt_descuento_comprobante'])?0:round(floatval($datapost['txt_descuento_comprobante']), 2);
		$new_documento->porcentaje_descuento_total = !isset($datapost['txt_descuento_porcentaje'])?0:round(floatval($datapost['txt_descuento_porcentaje']), 2);
		$new_documento->sub_total = $sub_total;
		$new_documento->porcentaje_igv = $factor_igv_sunat; //guarda el porcentaje igv utilizado (10% se debe ingresa el número 10, para el 18% se debe ingresar el número 18)
		$new_documento->total_igv = $total_igv;
		$new_documento->total_isc = '0';
		$new_documento->total_otr_imp = '0';
		$new_documento->total = $total;
		$new_documento->total_letras = $datapost['txt_total_letras'];

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

		//la primera vez siempre se guardará con un estado pendiente
		$new_documento->idcliente = $cliente['idcliente'];
		$new_documento->id_vendedor = $idvendedor;
		$new_documento->id_sucursal = $sucursal->idsucursal;
		$new_documento->estado_envio_sunat = "pendiente";
		$new_documento->fecha_registro = $fecha_actual;

		$new_documento->nota = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];
		
		//Tipo de venta y condición de pago
		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		$tipo_venta = !isset($datapost['opcion_tipo_venta'])?'contado':$datapost['opcion_tipo_venta'];
		$tipo_venta = ($tipo_venta == 'contado')?'contado':'credito';

		$monto_adeudado = !isset($datapost['txt_monto_adeudado'])?0:floatval($datapost['txt_monto_adeudado']) + 0;

		//para detracciones vamos a necesitar la condición de venta al contado y también la condición de venta como transferencia
		if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
			$condicion_venta_transferencia = Condiciondepago::findFirst(array("tipo = 'transferencia' and id_contribuyente = :id_contribuyente: and estado = 'activo' and condicionpago = 'Cuenta Detracción'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
			
			//si no existe una condición de venta del tipo transferencia, entonces la creamos automáticamente
			if(!$condicion_venta_transferencia) {
				$condicion_venta_transferencia = new Condiciondepago();
				$condicion_venta_transferencia->id_contribuyente = $contribuyente->id_contribuyente;
				$condicion_venta_transferencia->tipo = 'transferencia';
				$condicion_venta_transferencia->condicionpago = 'Cuenta Detracción';
				$condicion_venta_transferencia->estado = 'activo';

				try {
					if(!$condicion_venta_transferencia->save()) {
						$msg = '';
						foreach ($condicion_venta_transferencia->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Antes de Continuar Debes Registrar la Condición de Pago de tipo Transferencia!';
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}				
			}

			$condicion_venta_contado = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
			
			//si no existe una condición de venta del tipo transferencia, entonces la creamos automáticamente
			if(!$condicion_venta_contado) {
				$condicion_venta_contado = new Condiciondepago();
				$condicion_venta_contado->id_contribuyente = $contribuyente->id_contribuyente;
				$condicion_venta_contado->tipo = 'contado';
				$condicion_venta_contado->condicionpago = 'Transferencia Bancaria';
				$condicion_venta_contado->estado = 'activo';

				try {
					if(!$condicion_venta_contado->save()) {
						$msg = '';
						foreach ($condicion_venta_contado->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Antes de Continuar Debes Registrar la Condición de Pago de tipo Contado!';
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}
			}
		}

		if($tipo_venta == 'credito' && $monto_adeudado > 0) {
			$new_documento->id_condicionpago = $cabecera_inicial['id_venta_al_credito'];
			$new_documento->tipo_venta = 'credito';
			$new_documento->monto_adeudado = $monto_adeudado;
			$new_documento->monto_adeudado_inicial = $total;

			$new_documento->credito_sunat = ($contribuyente->informar_condpago_sunat == 'si')?'si':'no';

			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				
			} else {
				$new_documento->fecha_pagopendiente = date("Y-m-d", strtotime($fecha_pagopendiente));
			}
			
			if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
				$detraccion_monto = !isset($datapost['monto_detraccion'])?null:round(floatval($datapost['monto_detraccion']), 2);
				$condicion_venta_transferencia = Condiciondepago::findFirst(array("tipo = 'transferencia' and id_contribuyente = :id_contribuyente: and estado = 'activo' and condicionpago = 'Cuenta Detracción'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

				//registramos la detracción
				//registramos un abono con el monto de la detracción
				$new_montocobrado_detraccion = new MontoCobrado();
				$new_montocobrado_detraccion->id_contribuyente  = $id_contribuyente;
				$new_montocobrado_detraccion->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado_detraccion->serie_comprobante = $serie_comprobante;
				$new_montocobrado_detraccion->numero_comprobante = $numero_comprobante;
				$new_montocobrado_detraccion->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado_detraccion->id_vendedor = $idvendedor;
				$new_montocobrado_detraccion->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado_detraccion->id_condicionpago = $condicion_venta_transferencia->id_condicionpago;
				$new_montocobrado_detraccion->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado_detraccion->total = $detraccion_monto;
				$new_montocobrado_detraccion->cpago_nrooperacion = '';
				$new_montocobrado_detraccion->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado_detraccion->detalle = 'Cuenta Detracción';
				$new_montocobrado_detraccion->estado = 'activo';
				$new_montocobrado_detraccion->fechadeposito = $cabecera_inicial['fecha_documento'];
				$new_montocobrado_detraccion->idbanco = $datapost['detraccion_id_numero_cuenta'];
				$new_montocobrado_detraccion->tipo_cambio_sunat = round(floatval($datapost['tipo_cambio_comprobante']), 3);
				$new_montocobrado_detraccion->tipo_abono = 'detraccion';
				
				try {
					if(!$new_montocobrado_detraccion->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado_detraccion->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}

				//FACTURA CON DETRACCIÓN, AL CRÉDITO Y MONTO PARCIAL PAGADO.
				//existe un monto parcial pagado en efectivo, tarjeta o transferencia (es decir es una factura al crédito con detracción, con un monto parcial pagado.)
				if($total - $monto_adeudado - $detraccion_monto > 0) {
					$monto_cobrado = round($total - $monto_adeudado - $detraccion_monto, 2);
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
					$new_montocobrado->tipo_abono = 'pago_parcial';
					
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
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $e->getMessage();
						return $resp;
					}
				}
			} else if($aplica_retencion == 'si') {
				$monto_retencion = !isset($datapost['monto_retencion'])?null:round(floatval($datapost['monto_retencion']), 5);
				//buscamos un ID de pago al contado
				$condicion_venta_contado = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

				//registramos un abono con el monto de la detracción
				$new_montocobrado_retencion = new MontoCobrado();
				$new_montocobrado_retencion->id_contribuyente  = $id_contribuyente;
				$new_montocobrado_retencion->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado_retencion->serie_comprobante = $serie_comprobante;
				$new_montocobrado_retencion->numero_comprobante = $numero_comprobante;
				$new_montocobrado_retencion->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado_retencion->id_vendedor = $idvendedor;
				$new_montocobrado_retencion->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado_retencion->id_condicionpago = $condicion_venta_contado->id_condicionpago;
				$new_montocobrado_retencion->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado_retencion->total = $monto_retencion;
				$new_montocobrado_retencion->cpago_nrooperacion = '';
				$new_montocobrado_retencion->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado_retencion->detalle = 'Retención';
				$new_montocobrado_retencion->estado = 'activo';
				$new_montocobrado_retencion->fechadeposito = null;
				$new_montocobrado_retencion->idbanco = null;
				$new_montocobrado_retencion->tipo_cambio_sunat = round(floatval($datapost['tipo_cambio_comprobante']), 3);
				$new_montocobrado_retencion->tipo_abono = 'retencion'; //detraccion, retencion, adelanto,
				
				try {
					if(!$new_montocobrado_retencion->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado_retencion->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}

				if($total - $monto_adeudado - $monto_retencion > 0) {
					$monto_cobrado = round($total - $monto_adeudado - $monto_retencion, 2);
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
					$new_montocobrado->tipo_abono = 'pago_parcial';
					
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
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $e->getMessage();
						return $resp;
					}
				}

			} else {
				//cuando no existe detracción no hay problema, todo se trabaja igual
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
					$new_montocobrado->tipo_abono = 'pago_parcial';
					
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
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $e->getMessage();
						return $resp;
					}
				}	
			}

			//verificamos nuevamente el array cuotas
			//anteriormente ya no se debe hacer absolutamente nada, pues ya se hicieron todas las validaciones con detracciones y registros de pagos al contado.
			//*********** VALIDACIÓN Y GUARDADO DE CUOTAS **********/
			if(!isset($datapost['data_cuotas'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Si el comprobante es al crédito al menos debe ingresar una cuota, con su respectiva fecha de vencimiento y monto para el documento.';
				return $resp;
			}

			if(!isset($datapost['data_cuotas']['cuotas'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Si el comprobante es al crédito al menos debe ingresar una cuota, con su respectiva fecha de vencimiento y monto para el documento.';
				return $resp;
			}

			if(!is_array($datapost['data_cuotas']['cuotas'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'El listado de cuotas no es válido';
				return $resp;
			}

			$sumatoria_cuotas = 0;
			$num_cuota = 0;
			foreach($datapost['data_cuotas']['cuotas'] as $item_cuota) {
				$num_cuota++;
				if(!isset($item_cuota['id_cuota'])) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Cuotas';
					$resp['mensaje'] = 'No se encuentra el ID para la cuota número '.$num_cuota;
					return $resp;
				}

				if(!isset($item_cuota['monto_cuota'])) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Cuotas';
					$resp['mensaje'] = 'No se encuentra el Monto para la cuota número '.$num_cuota;
					return $resp;
				}

				if(!isset($item_cuota['vencimiento_cuota'])) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Cuotas';
					$resp['mensaje'] = 'No se encuentra la Fecha de Vencimiento para la cuota número '.$num_cuota;
					return $resp;
				}
				$sumatoria_cuotas = $sumatoria_cuotas + floatval($item_cuota['monto_cuota']);

				$cuota_credito = new MontoCobrado();
				$cuota_credito->id_contribuyente  = $id_contribuyente;
				$cuota_credito->id_tipodoc_electronico = $id_tipodoc_electronico;
				$cuota_credito->serie_comprobante = $serie_comprobante;
				$cuota_credito->numero_comprobante = $numero_comprobante;
				$cuota_credito->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$cuota_credito->id_vendedor = $idvendedor;
				$cuota_credito->id_sucursal = $sucursal->idsucursal;
				$cuota_credito->id_codigomoneda =  $datapost['codmoneda_comprobante'];

				
				$cuota_credito->total = floatval($item_cuota['monto_cuota']);
				$cuota_credito->fecha_registro = date('Y-m-d H:i:s');
				$cuota_credito->fecha_cuota = date("Y-m-d", strtotime($item_cuota['vencimiento_cuota']));
				$cuota_credito->estado = 'indefinido';
				$cuota_credito->tipo_abono = 'cuota';
				
				try {
					if(!$cuota_credito->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($cuota_credito->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}
			}

			if($datapost['data_cuotas']['cuotas'][0]) {
				$primera_cuota = $datapost['data_cuotas']['cuotas'][0];
				$primera_cuota_fecha = date("Y-m-d", strtotime($primera_cuota['vencimiento_cuota']));
				$new_documento->fecha_vencimiento = $primera_cuota_fecha;
				$new_documento->fecha_pagopendiente = $primera_cuota_fecha;
			}
			
		} else {
			//validamos si existe detracción
			if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
				$detraccion_monto = !isset($datapost['monto_detraccion'])?null:round(floatval($datapost['monto_detraccion']), 2);

				$condicion_venta_transferencia = Condiciondepago::findFirst(array("tipo = 'transferencia' and id_contribuyente = :id_contribuyente: and estado = 'activo' and condicionpago = 'Cuenta Detracción'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
				//ya no validamos que exista, porque líneas arriba ya creamos la condición en caso no exista

				//registramos un abono con el monto de la detracción
				$new_montocobrado_detraccion = new MontoCobrado();
				$new_montocobrado_detraccion->id_contribuyente  = $id_contribuyente;
				$new_montocobrado_detraccion->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado_detraccion->serie_comprobante = $serie_comprobante;
				$new_montocobrado_detraccion->numero_comprobante = $numero_comprobante;
				$new_montocobrado_detraccion->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado_detraccion->id_vendedor = $idvendedor;
				$new_montocobrado_detraccion->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado_detraccion->id_condicionpago = $condicion_venta_transferencia->id_condicionpago;
				$new_montocobrado_detraccion->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado_detraccion->total = $detraccion_monto;
				$new_montocobrado_detraccion->cpago_nrooperacion = '';
				$new_montocobrado_detraccion->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado_detraccion->detalle = 'Cuenta Detracción';
				$new_montocobrado_detraccion->estado = 'activo';
				$new_montocobrado_detraccion->fechadeposito = $cabecera_inicial['fecha_documento'];
				$new_montocobrado_detraccion->idbanco = $datapost['detraccion_id_numero_cuenta'];
				$new_montocobrado_detraccion->tipo_cambio_sunat = round(floatval($datapost['tipo_cambio_comprobante']), 3);
				$new_montocobrado_detraccion->tipo_abono = 'detraccion';
				
				try {
					if(!$new_montocobrado_detraccion->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado_detraccion->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}
				
				if(intval($cabecera_inicial['condicion_pago_parcial']) > 0) {
					$condicion_pago_parcial = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente: and tipo <> 'credito' and estado = 'activo'", 'bind' => array('id_condicionpago' => $cabecera_inicial['condicion_pago_parcial'], 'id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

					if(!$condicion_pago_parcial) {
						//buscamos un ID de pago al contado
						$condicion_pago_parcial = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
					}
				} else {
					//buscamos un ID de pago al contado
					$condicion_pago_parcial = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
				}
				
				//ya no validamos que exista, porque líneas arriba ya creamos la condición en caso no exista

				//Registramos el segundo abono, que sería la diferencia del total y la detracción
				//registramos un abono con el monto cobrado
				$new_montocobrado_contado = new MontoCobrado();
				$new_montocobrado_contado->id_contribuyente  = $id_contribuyente;
				$new_montocobrado_contado->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado_contado->serie_comprobante = $serie_comprobante;
				$new_montocobrado_contado->numero_comprobante = $numero_comprobante;
				$new_montocobrado_contado->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado_contado->id_vendedor = $idvendedor;
				$new_montocobrado_contado->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado_contado->id_condicionpago = $condicion_pago_parcial->id_condicionpago;
				$new_montocobrado_contado->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado_contado->total = round($total - $detraccion_monto, 2);
				$new_montocobrado_contado->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
				$new_montocobrado_contado->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado_contado->detalle = 'Monto Cobrado';
				$new_montocobrado_contado->estado = 'activo';
				$new_montocobrado_contado->fechadeposito = (isset($cabecera_inicial['fecha_deposito_transferencia']))?$cabecera_inicial['fecha_deposito_transferencia']:null;
				$new_montocobrado_contado->idbanco = (isset($cabecera_inicial['idcuenta_banco_deposito']))?$cabecera_inicial['idcuenta_banco_deposito']:null;
				$new_montocobrado_contado->tipo_cambio_sunat = round(floatval($datapost['tipo_cambio_comprobante']), 3);
				$new_montocobrado_contado->tipo_abono = 'pago_parcial';
				
				try {
					if(!$new_montocobrado_contado->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado_contado->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}

				//es importante que aunque no sea una venta al crédito, marquemos al comprobante como crédito para que los reportes puedan ofreceer información correcta, ya que aunque no sea al crédito, la detracción se registrará como una cuota y el total restante como una segunda cuota.
				$new_documento->tipo_venta = 'credito';
				$new_documento->monto_adeudado = 0;
				$new_documento->monto_adeudado_inicial = $total;
				$new_documento->id_condicionpago = $cabecera_inicial['id_venta_al_credito'];


			} else if($aplica_retencion == 'si') { //validamos si aplica retención
				$monto_retencion = !isset($datapost['monto_retencion'])?null:round(floatval($datapost['monto_retencion']), 5);
				//buscamos un ID de pago al contado
				$condicion_venta_contado = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

				//registramos un abono con el monto de la detracción
				$new_montocobrado_retencion = new MontoCobrado();
				$new_montocobrado_retencion->id_contribuyente  = $id_contribuyente;
				$new_montocobrado_retencion->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado_retencion->serie_comprobante = $serie_comprobante;
				$new_montocobrado_retencion->numero_comprobante = $numero_comprobante;
				$new_montocobrado_retencion->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado_retencion->id_vendedor = $idvendedor;
				$new_montocobrado_retencion->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado_retencion->id_condicionpago = $condicion_venta_contado->id_condicionpago;
				$new_montocobrado_retencion->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado_retencion->total = $monto_retencion;
				$new_montocobrado_retencion->cpago_nrooperacion = '';
				$new_montocobrado_retencion->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado_retencion->detalle = 'Retención';
				$new_montocobrado_retencion->estado = 'activo';
				$new_montocobrado_retencion->fechadeposito = null;
				$new_montocobrado_retencion->idbanco = null;
				$new_montocobrado_retencion->tipo_cambio_sunat = round(floatval($datapost['tipo_cambio_comprobante']), 3);
				$new_montocobrado_retencion->tipo_abono = 'retencion'; //detraccion, retencion, adelanto,
				
				try {
					if(!$new_montocobrado_retencion->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado_retencion->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}

				if(intval($cabecera_inicial['condicion_pago_parcial']) > 0) {
					$condicion_pago_parcial = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente: and tipo <> 'credito' and estado = 'activo'", 'bind' => array('id_condicionpago' => $cabecera_inicial['condicion_pago_parcial'], 'id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

					if(!$condicion_pago_parcial) {
						//buscamos un ID de pago al contado
						$condicion_pago_parcial = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
					}
				} else {
					//buscamos un ID de pago al contado
					$condicion_pago_parcial = Condiciondepago::findFirst(array("tipo = 'contado' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
				}

				//registramos un abono con el monto de la detracción
				$new_montocobrado_contado = new MontoCobrado();
				$new_montocobrado_contado->id_contribuyente  = $id_contribuyente;
				$new_montocobrado_contado->id_tipodoc_electronico = $id_tipodoc_electronico;
				$new_montocobrado_contado->serie_comprobante = $serie_comprobante;
				$new_montocobrado_contado->numero_comprobante = $numero_comprobante;
				$new_montocobrado_contado->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montocobrado_contado->id_vendedor = $idvendedor;
				$new_montocobrado_contado->id_sucursal = $sucursal->idsucursal;
				$new_montocobrado_contado->id_condicionpago = $condicion_pago_parcial->id_condicionpago;
				$new_montocobrado_contado->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montocobrado_contado->total = round($total - $monto_retencion, 2);
				$new_montocobrado_contado->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
				$new_montocobrado_contado->fecha_registro = date('Y-m-d H:i:s');
				$new_montocobrado_contado->detalle = 'Monto Cobrado';
				$new_montocobrado_contado->estado = 'activo';
				$new_montocobrado_contado->fechadeposito = (isset($cabecera_inicial['fecha_deposito_transferencia']))?$cabecera_inicial['fecha_deposito_transferencia']:null;
				$new_montocobrado_contado->idbanco = (isset($cabecera_inicial['idcuenta_banco_deposito']))?$cabecera_inicial['idcuenta_banco_deposito']:null;
				$new_montocobrado_contado->tipo_cambio_sunat = round(floatval($datapost['tipo_cambio_comprobante']), 3);
				$new_montocobrado_contado->tipo_abono = 'pago_parcial';
				
				try {
					if(!$new_montocobrado_contado->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($new_montocobrado_contado->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
			
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}

				//validamos si existe la condición de venta al crédito
				//se necesita la condición de venta al crédito para el registeo del pago pacial
				$condicion_venta_credito = Condiciondepago::findFirst(array("tipo = 'credito' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
			
				if(!$condicion_venta_credito) {
					$condicion_venta_credito = new Condiciondepago();
					$condicion_venta_credito->id_contribuyente = $id_contribuyente;
					$condicion_venta_credito->tipo = 'credito';
					$condicion_venta_credito->condicionpago = 'Venta al Crédito';
					$condicion_venta_credito->estado = 'activo';

					try {
						if(!$condicion_venta_credito->save()) {
							$msg = '';
							foreach ($condicion_venta_credito->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'No tienes permitido crear ventas al crédito!';
							return $resp;
						}
					} catch (Exception $e) {
                		$this->saveLogger($e);
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $e->getMessage();
						return $resp;
					}
				}
				
				$id_venta_al_credito = $condicion_venta_credito->id_condicionpago;

				//es importante que aunque no sea una venta al crédito, marquemos al comprobante como crédito para que los reportes puedan ofreceer información correcta, ya que aunque no sea al crédito, la retención se registrará como una cuota y el total restante como una segunda cuota.
				$new_documento->tipo_venta = 'credito';
				$new_documento->monto_adeudado = 0;
				$new_documento->monto_adeudado_inicial = $total;
				$new_documento->id_condicionpago = $id_venta_al_credito;
			} else {
				//no existe detracción y tampoco retención
				$new_documento->id_condicionpago = $id_condicionpago;
				$new_documento->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
				$new_documento->tipo_venta = 'contado';
				$new_documento->cpago_fechadeposito = !isset($cabecera_inicial['fecha_deposito_transferencia'])?null:$cabecera_inicial['fecha_deposito_transferencia'];
				$new_documento->cpago_idbanco = !isset($cabecera_inicial['idcuenta_banco_deposito'])?null:$cabecera_inicial['idcuenta_banco_deposito'];
			}
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
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

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
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $e->getMessage();
				return $resp;
			}

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

			//aquí descontamos el stock para cada uno de los productos en el detalle!
			$modificar_stock = 'si';
			if($new_documento->id_tipodoc_electronico == '01') {
				$modificar_stock = $this->get_sucursal_opcion($new_documento->id_contribuyente, $new_documento->id_sucursal, 'factura_modifica_stock');
			} else if($new_documento->id_tipodoc_electronico == '03') {
				$modificar_stock = $this->get_sucursal_opcion($new_documento->id_contribuyente, $new_documento->id_sucursal, 'boleta_modifica_stock');
			}

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
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = $e->getMessage();
							return $resp;
						}
					}
				}
			}
			//-------------- fin descuento stock --------------
		}

		//aquí guardamos los anticipos en caso tenga
		if(isset($datapost['tiene_anticipos']) && $datapost['tiene_anticipos'] == 'si') {
			if(isset($datapost['lista_anticipos']) && count($datapost['lista_anticipos']) > 0) {
				foreach($datapost['lista_anticipos'] as $anticipo) {
					$anticipo = (object) $anticipo;
					$new_anticipo = new DocElectronicoAnticipo();

					$new_anticipo->cpe_id_contribuyente 			= $new_documento->id_contribuyente;
					$new_anticipo->cpe_id_tipodoc_electronico 		= $new_documento->id_tipodoc_electronico;
					$new_anticipo->cpe_serie_comprobante 			= $new_documento->serie_comprobante;
					$new_anticipo->cpe_numero_comprobante 			= $new_documento->numero_comprobante;
					$new_anticipo->cpe_tipo_envio_sunat 			= $new_documento->tipo_envio_sunat;
					$new_anticipo->anticipo_id_contribuyente 		= $new_documento->id_contribuyente;
					$new_anticipo->anticipo_id_tipodoc_electronico 	= $anticipo->id_tipodoc_electronico;
					$new_anticipo->anticipo_serie_comprobante 		= $anticipo->serie_comprobante;
					$new_anticipo->anticipo_numero_comprobante 		= $anticipo->numero_comprobante;
					$new_anticipo->anticipo_tipo_envio_sunat 		= $new_documento->tipo_envio_sunat;
					$new_anticipo->monto_anticipo 					= $anticipo->monto;

					try {
						if(!$new_anticipo->save()) {
							$msg = '';
							foreach ($new_anticipo->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							$this->db->rollback();
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'No se pudo guardar el anticipo, inténtalo en un momento!!';
							echo json_encode($resp);
							exit();
						}
					} catch (Exception $e) {
                		$this->saveLogger($e);
						$this->db->rollback();
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'No se pudo guardar el anticipo';//$e->getMessage();
						echo json_encode($resp);
						exit();
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

		try {
			$resp_reg_payment_splits = $documento_electronico->registrar_payment_splits($contribuyente, $sucursal, $idvendedor, $new_documento, $datapost);
			if($resp_reg_payment_splits['respuesta'] == 'error') {
				$this->db->rollback();
				return $resp_reg_payment_splits;
			}
		} catch (Exception $e) {
			$this->saveLogger($e);
			$this->db->rollback();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$this->db->commit();
		return $resp;
	}
}