<?php
class NotadeventaController extends ControllerBase {
	public function procesar_notadeventa($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $cabecera_inicial['idsucursal'], 'id_contribuyente' => $id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La Sucursal no es válida!';
			return $resp;
		}

		$id_tipodoc_electronico = $cabecera_inicial['id_tipodoc_electronico'];

		$this->db->begin();
		$fecha_actual = date('Y-m-d H:i:s');
		$new_documento = new DocNoOficial();
		$herramientas = new HerramientasController;
		$numero_comprobante = $herramientas->get_num_docnooficial($contribuyente->id_contribuyente, $id_tipodoc_electronico, $contribuyente->tipo_envio_sunat);

		//DATOS PRINCIPALES COMO PRIMARY KEY PARA UNA FACTURA
		$new_documento->id_contribuyente = $id_contribuyente;
		$new_documento->id_tipodocumento = $id_tipodoc_electronico;
		$new_documento->modalidad = $contribuyente->tipo_envio_sunat;
		$new_documento->numero_comprobante = $herramientas->get_num_docnooficial($contribuyente->id_contribuyente, $id_tipodoc_electronico, $contribuyente->tipo_envio_sunat);
		
		$new_documento->fecha_comprobante = $cabecera_inicial['fecha_documento'];

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

		$new_documento->nro_otr_comprobante = !empty($datapost['nro_orden'])?$datapost['nro_orden']:null;
		$new_documento->transporte_nro_placa = !empty($datapost['nro_placa_vehiculo'])?$datapost['nro_placa_vehiculo']:null;

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
		$new_documento->porcentaje_igv = $factor_igv_sunat;
		$new_documento->total_igv = $total_igv;
		$new_documento->total = $total;
		$new_documento->total_letras = $datapost['txt_total_letras'];

		//la primera vez siempre se guardará con un estado pendiente
		$new_documento->idcliente = $cliente['idcliente'];
		$new_documento->id_vendedor = $idvendedor;
		$new_documento->id_sucursal = $sucursal->idsucursal;
		$new_documento->estado_documento = "activo";
		$new_documento->fecha_registro = $fecha_actual;

		//verificamos si se trata de un traslado
		if(isset($datapost['tipo'])) {
			if($datapost['tipo'] == 'traslado') {
				$new_documento->tipo = 'traslado';
			} else if($datapost['tipo'] == 'salida') {
				$new_documento->tipo = 'salida';
			} else if($datapost['tipo'] == 'entrada') {
				$new_documento->tipo = 'entrada';
			} else {
				$new_documento->tipo = 'venta';	
			}
		} else {
			$new_documento->tipo = 'venta';
		}

		if(isset($datapost['idsucursal_origen'])) {
			$new_documento->idsucursal_origen = $datapost['idsucursal_origen'];
		}

		if(isset($datapost['idsucursal_destino'])) {
			$new_documento->idsucursal_destino = $datapost['idsucursal_destino'];
		}

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
				$new_montocobrado->serie_comprobante = null;
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
					$resp['mensaje'] = 'Error al insertar el registro en la Base de Datos, intenta nuevamente por favor. '.$e->getMessage();
					return $resp;
				}
			}	
		} else {
			$new_documento->id_condicionpago = $id_condicionpago;
			$new_documento->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
			$new_documento->tipo_venta = 'contado';
		}
		$new_documento->cpago_fechadeposito = !isset($cabecera_inicial['fecha_deposito_transferencia'])?null:$cabecera_inicial['fecha_deposito_transferencia'];
		$new_documento->cpago_idbanco = !isset($cabecera_inicial['idcuenta_banco_deposito'])?null:$cabecera_inicial['idcuenta_banco_deposito'];
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
			$resp['mensaje'] = 'Error al insertar el registro en la Base de Datos, intenta nuevamente por favor.';
			return $resp;
		}

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
			$new_detalle->precio = $item["PRECIO_DET"];
			$new_detalle->sub_total = $item["IMPORTE_DET"]; //se puede eliminar
			$new_detalle->importe = $item["IMPORTE_DET"];
			$new_detalle->id_codigoprecio = $item["PRECIO_TIPO_CODIGO"];
			$new_detalle->igv = $item["IGV_DET"];
			$new_detalle->icbper = $item["ICBPER_DET"];
			$new_detalle->id_tipoafectacionigv = $item["COD_TIPO_OPERACION_DET"];
			$new_detalle->id_producto = $item["IDPRODUCTO_DET"];
			$new_detalle->codigo_producto = $item["CODIGO_PRODUCTO"];
			$new_detalle->descripcion = $item["DESCRIPCION_DET"];
			$new_detalle->precio_sin_igv = $item["PRECIO_SIN_IGV_DET"];

			$new_detalle->tipo_unidad = !isset($item["TIPO_UNIDAD"])?'UND':$item["TIPO_UNIDAD"];
    		$new_detalle->id_presentacion = !isset($item["ID_PRESENTACION"])?null:intval($item["ID_PRESENTACION"]);
			$new_detalle->factor_igv = $factor_igv_sunat;

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
				$resp['mensaje'] = 'Error al insertar el registro en la Base de Datos, intenta nuevamente por favor. '.$e->getMessage();
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
			$modificar_stock = $this->get_sucursal_opcion($new_documento->id_contribuyente, $new_documento->id_sucursal, 'notaventa_modifica_stock');
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
						$data_kardex['idusuario'] = $new_documento->id_vendedor;
						$data_kardex['tipo_envio_sunat'] = $new_documento->modalidad; //prueba, produccion
						$data_kardex['tipo_kardex'] = 'venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion, anulacion_venta	
						$data_kardex['estado_kardex'] = 'activo'; //activo, anulado
						$data_kardex['cantidad_salida'] = $cantidad_kardex;

						$detalle_kardex = !isset($datapost['observacion_documento'])?'Salida con Nota de Venta.-':$datapost['observacion_documento'];
						$data_kardex['detalle'] = empty($detalle_kardex)?'Salida con Nota de Venta.-':$detalle_kardex;
						
						$data_kardex['fecha_registro'] = $fecha_actual;
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
							$resp['mensaje'] = 'Error al insertar el registro en la Base de Datos, intenta nuevamente por favor. '.$e->getMessage();
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
		$doc_guardado['serie_comprobante'] = 'NOTA_VENTA';
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

		$this->db->commit();
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Nota de Venta Registrada!';
		//$resp['mensaje'] = 'La Nota de Venta N° '.$new_documento->numero_comprobante.', ha sido registrada correctamente.<br />'.$html_botones_impresion;
		$resp['mensaje'] = 'La Nota de Venta N° '.$new_documento->numero_comprobante.', ha sido registrada correctamente.';
		$resp['documento'] = $doc_guardado;
		$resp['url_whatsapp_api'] = $url_whatsapp_api;
		$resp['url_whatsapp_web'] = $url_whatsapp_web;
		$resp['url_whatsapp'] = $url_whatsapp;
		return $resp;
	}
	
}
?>