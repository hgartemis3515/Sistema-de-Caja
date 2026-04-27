<?php
class CotizacionController extends ControllerBase {
	public function procesar_cotizacion($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $cabecera_inicial['idsucursal'], 'id_contribuyente' => $id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La Sucursal no es válida!';
			return $resp;
		}
		$herramientas = new HerramientasController;
		$id_tipodoc_electronico = $cabecera_inicial['id_tipodoc_electronico'];

		$this->db->begin();

		if($cabecera_inicial['modo_edicion'] == 'si') {
			$new_documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $cabecera_inicial['id_tipodoc_electronico'], 'numero_comprobante' => $cabecera_inicial['numero_comprobante'], 'modalidad' => $contribuyente->tipo_envio_sunat)));

			if(!$new_documento) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La cotización que intentas modificar no existe!';
				return $resp;
			}

			//2.- Eliminar todos los registros del detalle!
			$items_detalle_actual = DetalleDocnooficial::find(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $cabecera_inicial['id_tipodoc_electronico'], 'numero_comprobante' => $cabecera_inicial['numero_comprobante'], 'modalidad' => $contribuyente->tipo_envio_sunat)));
			
			if(!$items_detalle_actual->delete()) {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento no se puede editar, inténtalo en un momento!!';
				return $resp;
			}
		} else {
			$new_documento = new DocNoOficial();
			//DATOS PRINCIPALES COMO PRIMARY KEY PARA UNA FACTURA
			$new_documento->id_contribuyente = $id_contribuyente;
			$new_documento->id_tipodocumento = $id_tipodoc_electronico;
			$new_documento->modalidad = $contribuyente->tipo_envio_sunat;
			$new_documento->numero_comprobante = $herramientas->get_num_docnooficial($contribuyente->id_contribuyente, $id_tipodoc_electronico, $contribuyente->tipo_envio_sunat);
			$new_documento->fecha_comprobante = $cabecera_inicial['fecha_documento'];
			$new_documento->fecha_vto_comprobante = isset($cabecera_inicial['fecha_vence_documento'])?$cabecera_inicial['fecha_vence_documento']:null;
		}

		//TIPO Y CAMBIO DE MONEDA
		$new_documento->id_codigomoneda = $datapost['codmoneda_comprobante'];
		$new_documento->tipo_cambio_sunat = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);

		//TOTALES X TIPO DE AFECTACIÓN DEL IGV : sunat_tipoafectacionigv : Catálogo No. 07: Códigos de Tipo de Afectación del IGV
		$new_documento->total_gravadas = !isset($datapost['txt_gravada_comprobante'])?0:round(floatval($datapost['txt_gravada_comprobante']), 2); //id_tipoafectacionigv: 10
		$new_documento->total_exoneradas = !isset($datapost['txt_exonerada_comprobante'])?0:round(floatval($datapost['txt_exonerada_comprobante']), 2); //id_tipoafectacionigv: 20
		$new_documento->total_inafecta = !isset($datapost['txt_inafecta_comprobante'])?0:round(floatval($datapost['txt_inafecta_comprobante']), 2); //id_tipoafectacionigv: 30
		$new_documento->total_exportacion = !isset($datapost['txt_exportacion_comprobante'])?0:round(floatval($datapost['txt_exportacion_comprobante']), 2); //id_tipoafectacionigv: 40;
		$new_documento->total_gratuitas = !isset($datapost['txt_gratuita_comprobante'])?0:round(floatval($datapost['txt_gratuita_comprobante']), 2); //id_tipoafectacionigv: todas las demás
		
		$new_documento->total_icbper = !isset($datapost['txt_icbper_comprobante'])?0:round(floatval($datapost['txt_icbper_comprobante']), 2);
		$new_documento->impuesto_icbper = !isset($datapost['doc_impuesto_icbper'])?0:round(floatval($datapost['doc_impuesto_icbper']), 2);

		$new_documento->nro_otr_comprobante = !empty($datapost['nro_orden'])?$datapost['nro_orden']:null;
		$new_documento->transporte_nro_placa = !empty($datapost['nro_placa_vehiculo'])?$datapost['nro_placa_vehiculo']:null;
		
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
		$new_documento->porcentaje_igv = $factor_igv_sunat;
		$new_documento->total_igv = $total_igv;
		$new_documento->total = $total;
		$new_documento->total_letras = $datapost['txt_total_letras'];

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

		//la primera vez siempre se guardará con un estado pendiente
		$new_documento->idcliente = $cliente['idcliente'];
		$new_documento->id_vendedor = $idvendedor;
		$new_documento->id_sucursal = $sucursal->idsucursal;
		$new_documento->estado_documento = "activo";
		$new_documento->fecha_registro = date('Y-m-d H:i:s');

		$new_documento->nota = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];

		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		if($id_condicionpago > 0) {
			$new_documento->id_condicionpago = $id_condicionpago;
			$new_documento->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
		}

		$tipo_venta = !isset($datapost['opcion_tipo_venta'])?'contado':'credito';
		if($tipo_venta == 'credito') {
			$condicion_venta_credito = Condiciondepago::findFirst(array("tipo = 'credito' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
			if(!$condicion_venta_credito) {
				$condicion_venta_credito = new Condiciondepago();
				$condicion_venta_credito->id_contribuyente = $contribuyente->id_contribuyente;
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
						$resp['mensaje'] = 'problemas al crear la condición venta al crédito!';
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'problemas al crear la condición venta al crédito! '.$e->getMessage();
					return $resp;
				}
			}
			$new_documento->id_condicionpago = $condicion_venta_credito->id_condicionpago;

			$new_documento->monto_adeudado = $total;
			$new_documento->monto_adeudado_inicial = $total;
			if(!empty($datapost['fecha_pago_comprobante'])) {
				$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
				$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
				if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
					
				} else {
					$new_documento->fecha_pagopendiente = date("Y-m-d", strtotime($fecha_pagopendiente));
				}
			}
		}

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
			$resp['mensaje'] = 'problemas al guardar la cotización! '.$e->getMessage();
			return $resp;
		}

		$herramientas = new HerramientasController;
		$n = 0;
		foreach($detalle as $item) {
			$n++;
			$new_detalle = new DetalleDocnooficial();
			$new_detalle->id_contribuyente = $new_documento->id_contribuyente;
			$new_detalle->id_tipodocumento = $new_documento->id_tipodocumento;
			$new_detalle->numero_comprobante = $new_documento->numero_comprobante;
			$new_detalle->modalidad = $new_documento->modalidad;
			$new_detalle->item = $n;
			$new_detalle->id_unidad_medida = $item["UNIDAD_MEDIDA_ID_DET"];
			$new_detalle->unidad_medida = $item["UNIDAD_MEDIDA_DET"];
			$new_detalle->cantidad = $item["CANTIDAD_DET"];
			$new_detalle->precio = $item["PRECIO_DET"] + 0;
			$new_detalle->sub_total = $item["IMPORTE_DET"] + 0; //se puede eliminar
			$new_detalle->importe = $item["IMPORTE_DET"] + 0;
			$new_detalle->id_codigoprecio = $item["PRECIO_TIPO_CODIGO"];
			$new_detalle->igv = $item["IGV_DET"];
			$new_detalle->icbper = $item["ICBPER_DET"];
			$new_detalle->id_tipoafectacionigv = $item["COD_TIPO_OPERACION_DET"];
			$new_detalle->id_producto = $item["IDPRODUCTO_DET"];
			$new_detalle->codigo_producto = $item["CODIGO_PRODUCTO"];
			$new_detalle->descripcion = $item["DESCRIPCION_DET"];
			$new_detalle->precio_sin_igv = $item["PRECIO_SIN_IGV_DET"] + 0;

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
				$resp['mensaje'] = 'problemas al guardar el detalle de la cotización! '.$e->getMessage();
				return $resp;
			}

			$num_decimales = 2;
			if($contribuyente->num_decimales > 2) {
				$num_decimales = $contribuyente->num_decimales;
			}

			//aquí descontamos el stock para cada uno de los productos en el detalle!
			$cantidad_kardex = $item["CANTIDAD_DET"];
			if(isset($item["ID_PRESENTACION"]) && intval($item["ID_PRESENTACION"]) > 0) {
				$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item["ID_PRESENTACION"]))));
				if($presentacion) {
					$cantidad_kardex = round($item["CANTIDAD_DET"]*$presentacion->cantidad_und_base, $num_decimales);
				}
			}

			$modificar_stock = 'no';
			$modificar_stock = $this->get_sucursal_opcion($new_documento->id_contribuyente, $new_documento->id_sucursal, 'cotizacion_modifica_stock');
			$modificar_stock = ($modificar_stock == '')?'no':$modificar_stock;
			$modificar_stock = ($modificar_stock == 'si')?'si':'no';
			$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item["IDPRODUCTO_DET"])));
			if($producto && $modificar_stock == 'si') {
				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
				if($unidad_medida) {
					if($unidad_medida->codigo != 'ZZ') {

						$data_kardex['id_contribuyente'] = $new_documento->id_contribuyente;
						$data_kardex['idsucursal'] = $new_documento->id_sucursal;
						$data_kardex['idproducto'] = $producto->idproducto;
						$data_kardex['idusuario'] = $new_documento->id_vendedor;
						$data_kardex['tipo_envio_sunat'] = $new_documento->modalidad; //prueba, produccion
						$data_kardex['tipo_kardex'] = 'venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion, anulacion_venta	
						$data_kardex['estado_kardex'] = 'activo'; //activo, anulado
						$data_kardex['cantidad_salida'] = $cantidad_kardex;

						$detalle_kardex = !isset($datapost['observacion_documento'])?'Salida con Nota de Venta.-':$datapost['observacion_documento'];
						$data_kardex['detalle'] = empty($detalle_kardex)?'Salida con Nota de Venta.-':$detalle_kardex;
						
						$data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
						$data_kardex['docref_id_contribuyente'] = $new_documento->id_contribuyente;
						$data_kardex['docref_id_tipodoc_electronico'] = $new_documento->id_tipodocumento;
						$data_kardex['docref_numero_comprobante'] = $new_documento->numero_comprobante;
						$data_kardex['docref_tipo_envio_sunat'] = $new_documento->modalidad;
						
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
							$resp['mensaje'] = 'problemas al guardar el stock del producto!: '.$e->getMessage();
							return $resp;
						}
					}
				}
			}
		}

		$dominio_principal = $this->get_parametros_iniciales()['url_domain'];

		//id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
		$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$new_documento->id_tipodocumento."|| ||".$new_documento->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$new_documento->id_tipodocumento."|| ||".$new_documento->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||ticket");

		$url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

		$html_botones_impresion = '
		<span class="heading-btn-group">
			<a href="'.$url_a4.'" target="_blank" class="btn btn-link btn-float text-size-small has-text legitRipple"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 50px;"> <span> A4 - PDF</span></a>
			<a href="'.$url_ticket.'" target="_blank" class="btn btn-link btn-float text-size-small has-text legitRipple"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 50px;">  <span> Ticket - PDF</span></a>
		</span>
		';

		$doc_guardado['id_contribuyente'] = $new_documento->id_contribuyente;
		$doc_guardado['id_tipodoc_electronico'] = $new_documento->id_tipodocumento;
		$doc_guardado['serie_comprobante'] = 'COTIZACION';
		$doc_guardado['numero_comprobante'] = $new_documento->numero_comprobante;
		$doc_guardado['tipo_envio_sunat'] = $new_documento->modalidad;
		$doc_guardado['idusuario'] = $idvendedor;

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
		
		$url_whatsapp = '';
		$url_whatsapp_api = '';
		$url_whatsapp_web = '';
		if(!empty($cliente['data_cliente']['cliente_celular'])) {
			$mensaje_whatsapp = 'Gracias por confiar en nosotros, desde los siguientes enlaces puedes descargar documento electrónico: Formato A4: '.$url_a4.' y Formato Ticket: '.$url_ticket;
			$url_whatsapp_api = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp_web = 'https://web.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
		}

		if($cabecera_inicial['modo_edicion'] == 'si') {
			$vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'idusuario' => $idvendedor)));
			$log = new LogsController;
			$data_log_doc['id_contribuyente'] = $new_documento->id_contribuyente;
			$data_log_doc['id_tipodoc_electronico'] = $new_documento->id_tipodocumento;
			$data_log_doc['serie_comprobante'] = 'COTI';
			$data_log_doc['numero_comprobante'] = $new_documento->numero_comprobante;
			$data_log_doc['tipo_envio_sunat'] = $new_documento->modalidad;
			$data_log_doc['idusuario'] = $idvendedor;
			$data_log_doc['tipo_log'] = 'editar';
			$data_log_doc['descripcion'] = 'El Usuario '.$vendedor->nombre.' '.$vendedor->apellido.' (ID: '.$vendedor->idusuario.') ha realizado una modificación o edición';
			
			$resp_log = $log->log_documento($data_log_doc);
		}

		$this->db->commit();
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Cotización Registrada!';
		//$resp['mensaje'] = 'La Cotización N° '.$new_documento->numero_comprobante.', ha sido registrada correctamente.<br />'.$html_botones_impresion;
		$resp['mensaje'] = 'La Cotización N° '.$new_documento->numero_comprobante.', ha sido registrada correctamente.';
		$resp['documento'] = $doc_guardado;
		$resp['url_whatsapp_api'] = $url_whatsapp_api;
		$resp['url_whatsapp_web'] = $url_whatsapp_web;
		$resp['url_whatsapp'] = $url_whatsapp;
		return $resp;
	}
	
}
?>