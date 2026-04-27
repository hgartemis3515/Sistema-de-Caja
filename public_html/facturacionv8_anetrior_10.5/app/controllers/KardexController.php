<?php
class KardexController extends ControllerBase {
	public function registrar_en_kardex($data) {
		
		if($data['tipo_kardex'] == 'inventario_inicial' || $data['tipo_kardex'] == 'compra' || $data['tipo_kardex'] == 'ingreso_sindoc' || $data['tipo_kardex'] == 'anulacion_venta') {
			$tipo_kardex = 'entrada';
		} elseif ($data['tipo_kardex'] == 'venta' || $data['tipo_kardex'] == 'salida_sindoc' || $data['tipo_kardex'] == 'devolucion' || $data['tipo_kardex'] == 'anulacion_compra' || $data['tipo_kardex'] == 'anulacion_nota_credito') {
			$tipo_kardex = 'salida';
		} else {
			$tipo_kardex = 'desconocido';
		}

		if($tipo_kardex == 'desconocido') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Kardex';
			$resp['mensaje'] = 'No se Reconoce el Tipo de Kardex';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $data['id_contribuyente'])));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			echo json_encode($resp);
			exit();
		}

		$stock = 0;
		$costo_unitario_promedio = 0;
		$costo_total = 0;
		$stock_valorizado = 0;

		if($contribuyente->multi_almacen == 'si') {
			$save_kardex = Kardex::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $data['idproducto'], 'id_contribuyente' => $data['id_contribuyente'], 'idsucursal' => $data['idsucursal']), "order" => "id_kardex DESC"));
		} else {
			$save_kardex = Kardex::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $data['idproducto'], 'id_contribuyente' => $data['id_contribuyente']), "order" => "id_kardex DESC"));
		}
		
		if(!$save_kardex) {
			if($tipo_kardex == 'entrada') {
				$stock = empty($data['cantidad_entrada'])?0:$data['cantidad_entrada'];
			} elseif ($tipo_kardex == 'salida') {
				$stock = empty($data['cantidad_salida'])?0:$data['cantidad_salida'];
				$stock = (-1)*$stock;
			}

			$costo_unitario = empty($data['costo_unitario'])?0:$data['costo_unitario']; //Siempre ingresaremos un costo unitario con IGV
			$costo_total = $stock*$costo_unitario;
			$stock_valorizado = $stock*$costo_unitario;
			//$costo_unitario_promedio = round($costo_total/$stock);
			if($stock == 0) {
				$costo_unitario_promedio = 0;
			} else {
				$costo_unitario_promedio = $costo_total/$stock;
			}
		} else {
			if($tipo_kardex == 'entrada') {
				$costo_unitario = empty($data['costo_unitario'])?0:$data['costo_unitario']; //Siempre ingresaremos un costo unitario con IGV
				$stock = $data['cantidad_entrada'];
				$stock = $stock + $save_kardex->stock;
				$costo_total = round($data['cantidad_entrada']*$costo_unitario, 2);
				$stock_valorizado = round($costo_total + $save_kardex->stock_valorizado, 2);
				if($stock == 0) {
					$costo_unitario_promedio = 0;
				} else {
					$costo_unitario_promedio = $stock_valorizado/$stock;
				}
			} else {
				$costo_unitario = empty($data['costo_unitario'])?$save_kardex->costo_unitario_promedio:$data['costo_unitario'];
				$stock = $save_kardex->stock - $data['cantidad_salida'];
				$costo_total = round($costo_unitario*$data['cantidad_salida'], 2);
				$stock_valorizado = round($save_kardex->stock_valorizado - $costo_total, 2);
				if($stock == 0) {
					$costo_unitario_promedio = 0;
				} else {
					$costo_unitario_promedio = $stock_valorizado/$stock;
				}
			}
		}

		$new_kardex = new Kardex();
		$new_kardex->id_contribuyente = $data['id_contribuyente'];
		$new_kardex->idsucursal = $data['idsucursal'];
		$new_kardex->idproducto = $data['idproducto'];
		$new_kardex->idusuario = $data['idusuario'];
		$new_kardex->tipo_envio_sunat = $data['tipo_envio_sunat'];
		$new_kardex->tipo_kardex = $data['tipo_kardex'];
		$new_kardex->estado_kardex = 'activo';
		$new_kardex->cantidad_entrada = empty($data['cantidad_entrada'])?0:$data['cantidad_entrada'];
		$new_kardex->cantidad_salida = empty($data['cantidad_salida'])?0:$data['cantidad_salida'];
		$new_kardex->stock = $stock;
		$new_kardex->costo_unitario = $costo_unitario;
		$new_kardex->costo_unitario_promedio = round($costo_unitario_promedio, 2);
		$new_kardex->stock_valorizado = $stock_valorizado;
		$new_kardex->costo_total = $costo_total;
		$new_kardex->detalle = empty($data['detalle'])?'':$data['detalle'];
		$new_kardex->fecha_registro = empty($data['fecha_registro'])?date('Y-m-d H:i:s'):$data['fecha_registro'];;
		$new_kardex->docref_id_contribuyente = empty($data['docref_id_contribuyente'])?null:$data['docref_id_contribuyente'];
		$new_kardex->docref_id_tipodoc_electronico = empty($data['docref_id_tipodoc_electronico'])?null:$data['docref_id_tipodoc_electronico'];
		$new_kardex->docref_serie_comprobante = empty($data['docref_serie_comprobante'])?null:$data['docref_serie_comprobante'];
		$new_kardex->docref_numero_comprobante = empty($data['docref_numero_comprobante'])?null:$data['docref_numero_comprobante'];
		$new_kardex->docref_tipo_envio_sunat = empty($data['docref_tipo_envio_sunat'])?null:$data['docref_tipo_envio_sunat'];
		$new_kardex->id_compra = empty($data['id_compra'])?null:$data['id_compra'];

		try {
			if(!$new_kardex->save()) {
				$msg = '';
				foreach ($new_kardex->getMessages() as $message) {
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

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Kardex Generado';
		$resp['mensaje'] = 'Se ha registrado correctamente el kardex';
		$resp['costo_unitario_promedio'] = round($costo_unitario_promedio, 2);
		$resp['stock'] = $stock;
		return $resp;
	}

	public function get_kardex_detallado($idusuario, $idproducto, $idsucursal, $fecha_inicio = '', $fecha_fin = '') {
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			return $resp;
		}
		
		$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $idproducto)));
		if(!$producto) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Producto No Existe!';
			return $resp;
		}

		$idsucursal = $producto->idsucursal;
		if($contribuyente->multi_almacen == 'no') {
			$idsucursal = 0;
		}
		
		/*
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
		if(!$sucursal) {
			$idsucursal = $producto->idsucursal;
		}
		*/

		if(empty($fecha_inicio)) {
			$fecha_actual = date("Y-m-d H:i:s");
			$fecha_inicio_b = date("Y-m-d", strtotime("-365 days", strtotime($fecha_actual)));
		} else {
			$fecha_inicio_b = $fecha_inicio;
		}
		
		if(empty($fecha_fin)) {
			$fecha_fin_b = date("Y-m-d");
		} else {
			$fecha_fin_b = $fecha_fin;
		}
		
		$herramientas = new HerramientasController;
		$fecha_inicio = !$herramientas->validate_date($fecha_inicio)?$fecha_inicio_b:$fecha_inicio;
		$fecha_fin = !$herramientas->validate_date($fecha_fin)?$fecha_fin_b:$fecha_fin;

		if($idsucursal <= 0) {
			$query = "idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST(fecha_registro AS DATE) <= CAST(:fecha_fin: AS DATE) and tipo_envio_sunat = :tipo_envio_sunat: ";
	
			$lista_kardex = Kardex::find(array($query, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'idproducto' => $idproducto, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro ASC"));
		} else {
			$query = "idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST(fecha_registro AS DATE) <= CAST(:fecha_fin: AS DATE) and tipo_envio_sunat = :tipo_envio_sunat: ";
	
			$lista_kardex = Kardex::find(array($query, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'idproducto' => $idproducto, 'idsucursal' => $idsucursal, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro ASC"));
		}

		$total_items = count($lista_kardex);

		$lista = array();
		$lista_items = array();
		$n = $total_items;
		foreach($lista_kardex as $item) {
			$tipo_referencia_detalle = $this->get_tipo_referencia_kardex($item);
			
			$tipo_operacion = '99';
			$docref_id_tipodoc_electronico = $item->docref_id_tipodoc_electronico;
			if($item->tipo_kardex == 'inventario_inicial') {
				$tipo_operacion = '16';
			} else {
				if(!empty($item->id_compra)) {
					//si no es vacio, entonces se trata de una compra.
					if($item->docref_id_tipodoc_electronico == '07') {
						//nota crédito
						$tipo_operacion = '06'; //Resta stock, sería DEVOLUCIÓN ENTREGADA.
					} else  if($item->docref_id_tipodoc_electronico == '08') {
						//nota debito
						$tipo_operacion = '03'; //suma stock, sería CONSIGNACIÓN RECIBIDA
					} else if($item->docref_id_tipodoc_electronico == '03') {
						//boleta
						$tipo_operacion = '02';
					} else if($item->docref_id_tipodoc_electronico == '01') {
						//factura
						$tipo_operacion = '02';
					} else if($item->docref_id_tipodoc_electronico == '00') {
						//nota compra
						$tipo_operacion = '99';
						$docref_id_tipodoc_electronico = '00';
					} else {
						$tipo_operacion = '99';
						$docref_id_tipodoc_electronico = '00';
					}
				} else {
					//caso contrario una venta (factura, boleta, nota de crédito o débito)
					if($item->docref_id_tipodoc_electronico == '07') {
						//nota crédito
						$tipo_operacion = '05';
					} else if($item->docref_id_tipodoc_electronico == '08') {
						//nota debito
						$tipo_operacion = '04';
					} else if($item->docref_id_tipodoc_electronico == '03') {
						//boleta
						$tipo_operacion = '01';
					} else if($item->docref_id_tipodoc_electronico == '88') { //nuevo
						//Cotización
						$tipo_operacion = '01';
					} else if($item->docref_id_tipodoc_electronico == '09') { //nuevo
						//Guía Remisión remitente
						$tipo_operacion = '01';
					} else if($item->docref_id_tipodoc_electronico == '01') {
						//factura
						$tipo_operacion = '01';
					} else if($item->docref_id_tipodoc_electronico == '77') {
						//nota venta
						$tipo_operacion = '99';
						$docref_id_tipodoc_electronico = '00';
					} else {
						$tipo_operacion = '99';
						$docref_id_tipodoc_electronico = '00';
					}
				}
			}

			$lista[] = array(
				'item' 						=> $n, 
				'fecha_registro' 			=> date("d-m-Y / H:i A", strtotime($item->fecha_registro)), 
				'fecha'						=> date("d-m-Y", strtotime($item->fecha_registro)),
				'html_detalle' 				=> $tipo_referencia_detalle['html_detalle'],

				'detalle'					=> $item->detalle,
				'tipo_doc_electronico'		=> $docref_id_tipodoc_electronico,
				'serie_documento'			=> $item->docref_serie_comprobante,
				'correlativo_documento'		=> $item->docref_numero_comprobante,
				'tipo_operacion'			=> $tipo_operacion,
				
				'cantidad_entrada' 			=> $item->cantidad_entrada,
				'costo_unitario_entrada' 	=> ($tipo_referencia_detalle['tipo_kardex'] == 'entrada')?'S/ '.custom_money_format($item->costo_unitario):'S/ '.custom_money_format(0), //
				'costo_total_entrada' 		=> ($tipo_referencia_detalle['tipo_kardex'] == 'entrada')?'S/ '.custom_money_format($item->costo_total):'S/ '.custom_money_format(0),
				'cantidad_salida' 			=> $item->cantidad_salida,
				'costo_unitario_salida' 	=> ($tipo_referencia_detalle['tipo_kardex'] == 'salida')?'S/ '.custom_money_format($item->costo_unitario):'S/ '.custom_money_format(0), //
				'costo_total_salida' 		=> ($tipo_referencia_detalle['tipo_kardex'] == 'salida')?'S/ '.custom_money_format($item->costo_total):'S/ '.custom_money_format(0),
				'stock_actual' 				=> $item->stock,
				'costo_promedio_unitario' 	=> 'S/ '.custom_money_format($item->costo_unitario_promedio),
				'stock_valorizado' 			=> 'S/ '.custom_money_format($item->stock_valorizado)
			);
			$n--;

			//$lista_items[] = $item;
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $lista;
		$resp['total_items'] = $total_items;
		//$resp['lista_items'] = $lista_items;
		return $resp;
	}

	public function generar_enlace_pdf($id_contribuyente, $id_tipodoc_electronico, $serie_documento, $numero_documento, $tipo_envio_sunat) {

		$dominio_principal = $this->get_parametros_iniciales()['url_domain'];

		$herramientas = new HerramientasController;

		$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$id_tipodoc_electronico."||".$serie_documento."||".$numero_documento."||".$tipo_envio_sunat."||a4");
        $string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$id_tipodoc_electronico."||".$serie_documento."||".$numero_documento."||".$tipo_envio_sunat."||ticket");

		$url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

		$data['url_a4'] = $url_a4;
		$data['url_ticket'] = $url_ticket;
		return $data;
	}

	public function get_tipo_referencia_kardex($item) {
		$documento_referencia = '';
		$detalle = '';
		$html_detalle = '';

		if($item->tipo_kardex == 'inventario_inicial' || $item->tipo_kardex == 'compra' || $item->tipo_kardex == 'ingreso_sindoc' || $item->tipo_kardex == 'anulacion_venta') {
			$tipo_kardex = 'entrada';
		} elseif ($item->tipo_kardex == 'venta' || $item->tipo_kardex == 'salida_sindoc' || $item->tipo_kardex == 'devolucion' || $item->tipo_kardex == 'anulacion_compra' || $item->tipo_kardex == 'anulacion_nota_credito') {
			$tipo_kardex = 'salida';
		} else {
			$tipo_kardex = 'desconocido';
		}
		
		//inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
		if($tipo_kardex == 'entrada') {
			if($item->tipo_kardex == 'inventario_inicial' || $item->tipo_kardex == 'ingreso_sindoc') {

				$texto_ingreso = ($item->tipo_kardex == 'inventario_inicial')?'inventario_inicial':'Ingreso sin Doc.';

				$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
				if(!empty($item->detalle)) {
					$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($item->detalle).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
				}
				
				$resp_referencia = $this->serie_numero_doc_relacionado($item);
				$documento_referencia = $resp_referencia['texto_doc_referencia'];

				$detalle = 'Reg.Prod.';
				$html_detalle = '
				<div style="padding-right: 12px;" class="media-left media-middle">
					<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
						<span class="letter-icon">S</span>
					</a>
				</div>

				<div class="media-body">
					<div class="media-heading">
						<a href="#" class="letter-icon-title">'.$documento_referencia.'</a>
					</div>

					<div class="text-muted text-size-small">'.$observacion_movimiento.' '.$texto_ingreso.'</div>
				</div>
				';
			} else if($item->tipo_kardex == 'compra') {

				$resp_referencia = $this->serie_numero_doc_relacionado($item);
				$documento_referencia = $resp_referencia['texto_doc_referencia'];

				if($item->docref_id_tipodoc_electronico == '00') { //Sin Documento Válido
					$detalle = 'Compra';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">D</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a href="#" class="letter-icon-title">'.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Compra</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '01') { //Factura
					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Factura - Compra';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">F</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a href="#" class="letter-icon-title">Factura: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Factura de Compra</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '03') { //Boleta
					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Boleta - Compra';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">F</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a href="#" class="letter-icon-title">Boleta: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Boleta de Compra</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '08') { //Nota de Débito
					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Nota de Débito - Compra';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a href="#" class="letter-icon-title">Nota Débito: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Nota Débito de Compra</div>
					</div>
					';
				}

			} else if($item->tipo_kardex == 'anulacion_venta') {

				if($item->docref_id_tipodoc_electronico == '77') { //Nota de Venta

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_numero_comprobante;
					$detalle = 'Anulación de Nota de Venta';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Nota Venta: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Anulación de Nota de Venta</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '01') { //Factura

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Anulación de Factura de Venta';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">F</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Factura: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Anulación de Factura</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '03') { //Boleta

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Anulación de Boleta de Venta';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">B</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Boleta: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Anulación de Boleta</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '08') { //Nota de Débito

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Anulación de Nota de Débito - Compra';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Boleta: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Anulación de Nota Débito</div>
					</div>
					';
				}

				if($item->docref_id_tipodoc_electronico == '07') { //Nota de Crédito

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Nota de Crédito';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Doc.Elect.: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i> Nota de Crédito</div>
					</div>
					';
				}
			}
		} elseif ($tipo_kardex == 'salida') {
			if($item->tipo_kardex == 'salida_sindoc') {

				$texto_ingreso = ($item->tipo_kardex == 'inventario_inicial')?'inventario_inicial':'Ingreso sin Doc.';

				$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
				if(!empty($item->detalle)) {
					$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($item->detalle).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
				}

				$resp_referencia = $this->serie_numero_doc_relacionado($item);
				$documento_referencia = $resp_referencia['texto_doc_referencia'];
				$detalle = 'Salida de Almacén';
				$html_detalle = '
				<div style="padding-right: 12px;" class="media-left media-middle">
					<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
						<span class="letter-icon">B</span>
					</a>
				</div>

				<div class="media-body">
					<div class="media-heading">
						<a href="#" class="letter-icon-title">'.$documento_referencia.'</a>
					</div>

					<div class="text-muted text-size-small">'.$observacion_movimiento.' Salida sin Documento</div>
				</div>
				';
			} else if($item->tipo_kardex == 'venta') {
				//Las ventas pueden ser por nota de venta, boleta, factura, nota de débito. (las notas de crédito están consideradas como una salida de almacen)
				if($item->docref_id_tipodoc_electronico == '77') { //Nota de Venta
					$resp_referencia = $this->serie_numero_doc_relacionado($item);
					$documento_referencia = $resp_referencia['texto_doc_referencia'];

					$documento_relacionado = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodocumento' => $item->docref_id_tipodoc_electronico, 'numero_comprobante' => $item->docref_numero_comprobante, 'modalidad' => $item->docref_tipo_envio_sunat)));
					
					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);
					
					$detalle = 'Venta con Nota de Venta';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">'.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Salida con Nota de Venta</div>
					</div>
					';
				} else if($item->docref_id_tipodoc_electronico == '01') { //Factura
					$documento_relacionado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodoc_electronico' => $item->docref_id_tipodoc_electronico, 'serie_comprobante' => $item->docref_serie_comprobante, 'numero_comprobante' => $item->docref_numero_comprobante, 'tipo_envio_sunat' => $item->docref_tipo_envio_sunat)));

					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Venta con Factura';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">F</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Factura: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Factura de Venta Electrónica</div>
					</div>
					';
				} else if($item->docref_id_tipodoc_electronico == '03') { //Boleta
					$documento_relacionado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodoc_electronico' => $item->docref_id_tipodoc_electronico, 'serie_comprobante' => $item->docref_serie_comprobante, 'numero_comprobante' => $item->docref_numero_comprobante, 'tipo_envio_sunat' => $item->docref_tipo_envio_sunat)));

					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Venta con Boleta';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">B</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Boleta: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Boleta de Venta Electrónica</div>
					</div>
					';
				} else if($item->docref_id_tipodoc_electronico == '08') { //Nota de Débito
					$documento_relacionado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodoc_electronico' => $item->docref_id_tipodoc_electronico, 'serie_comprobante' => $item->docref_serie_comprobante, 'numero_comprobante' => $item->docref_numero_comprobante, 'tipo_envio_sunat' => $item->docref_tipo_envio_sunat)));

					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Venta con Nota de Débito';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Nota Débito: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Nota de Débito Venta Electrónica</div>
					</div>
					';
				} else if($item->docref_id_tipodoc_electronico == '88') { //cotización
					$resp_referencia = $this->serie_numero_doc_relacionado($item);
					$documento_referencia = $resp_referencia['texto_doc_referencia'];

					$documento_relacionado = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodocumento' => $item->docref_id_tipodoc_electronico, 'numero_comprobante' => $item->docref_numero_comprobante, 'modalidad' => $item->docref_tipo_envio_sunat)));
					
					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);
					
					$detalle = 'Salida con Cotización';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">C</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">'.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Salida con Cotización</div>
					</div>
					';
				} else if($item->docref_id_tipodoc_electronico == '09') { //guía remisión remitente
					$documento_relacionado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodoc_electronico' => $item->docref_id_tipodoc_electronico, 'serie_comprobante' => $item->docref_serie_comprobante, 'numero_comprobante' => $item->docref_numero_comprobante, 'tipo_envio_sunat' => $item->docref_tipo_envio_sunat)));

					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$resp_enlaces = $this->generar_enlace_pdf($item->id_contribuyente, $item->docref_id_tipodoc_electronico, $item->docref_serie_comprobante, $item->docref_numero_comprobante, $item->docref_tipo_envio_sunat);

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Salida con Guía';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-primary-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">G</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a target="_blank" href="'.$resp_enlaces['url_a4'].'" class="letter-icon-title">Guía: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Salida con Guía Remisión</div>
					</div>
					';
				}

			} else if($item->tipo_kardex == 'devolucion') {
				//se entiende por devolución cuando la empresa compra un producto, luego lo devuelve, lo que provoca una salida de almacen.
				if($item->docref_id_tipodoc_electronico == '07') { //Nota de Débito

					$documento_relacionado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->docref_id_contribuyente, 'id_tipodoc_electronico' => $item->docref_id_tipodoc_electronico, 'serie_comprobante' => $item->docref_serie_comprobante, 'numero_comprobante' => $item->docref_numero_comprobante, 'tipo_envio_sunat' => $item->docref_tipo_envio_sunat)));

					$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
					if(!empty($documento_relacionado->nota)) {
						$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($documento_relacionado->nota).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
					}

					$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
					$detalle = 'Anulación de Nota de Crédito de Compra';
					$html_detalle = '
					<div style="padding-right: 12px;" class="media-left media-middle">
						<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
							<span class="letter-icon">N</span>
						</a>
					</div>

					<div class="media-body">
						<div class="media-heading">
							<a href="#" class="letter-icon-title">Nota de Crédito: '.$documento_referencia.'</a>
						</div>

						<div class="text-muted text-size-small">'.$observacion_movimiento.' Anulación de Nota de Crédito de Compra</div>
					</div>
					';
				}
			} else if($item->tipo_kardex == 'anulacion_compra') {
				$documento_referencia = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
				$detalle = 'Anulación Compra';
				$html_detalle = '
				<div style="padding-right: 12px;" class="media-left media-middle">
					<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
						<span class="letter-icon">D</span>
					</a>
				</div>

				<div class="media-body">
					<div class="media-heading">
						<a href="#" class="letter-icon-title">Documento: '.$documento_referencia.'</a>
					</div>

					<div class="text-muted text-size-small"><i class="icon-info22 text-size-mini position-left"></i>Anulación de Documento de Compra</div>
				</div>
				';
			} else if($item->tipo_kardex == 'anulacion_nota_credito') {
				$observacion_movimiento = '<i class="icon-info22 text-size-mini position-left"></i>';
				if(!empty($item->detalle)) {
					$observacion_movimiento = '<img data-popup="popover" data-placement="top" title="Observación" data-content="'.htmlspecialchars($item->detalle).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 20px; cursor: pointer;" />';
				}

				$resp_referencia = $this->serie_numero_doc_relacionado($item);
				$documento_referencia = $resp_referencia['texto_doc_referencia'];
				$detalle = 'Salida de Almacén';
				$html_detalle = '
				<div style="padding-right: 12px;" class="media-left media-middle">
					<a href="#" style="padding: 8px 9px 7px 10px;" class="btn bg-danger-400 btn-rounded btn-icon btn-xs legitRipple">
						<span class="letter-icon">B</span>
					</a>
				</div>

				<div class="media-body">
					<div class="media-heading">
						<a href="#" class="letter-icon-title">'.$documento_referencia.'</a>
					</div>

					<div class="text-muted text-size-small">'.$observacion_movimiento.' Anulación de Nota Crédito</div>
				</div>
				';
			}
		}

		/*
		if(empty($html_detalle)) {
			$html_detalle = $html_detalle.'||'.$tipo_kardex.'||'.$documento_referencia.'||'.$detalle.'||'.json_encode($item);
		}
		*/
		
		$resp['html_detalle'] = $html_detalle;
		$resp['tipo_kardex'] = $tipo_kardex;
		$resp['doc_referencia'] = $documento_referencia;
		$resp['detalle_proceso'] = $detalle;
		return $resp;
	}

	public function serie_numero_doc_relacionado($item) {
		$nombre_doc_referencia = 'Otro Doc.';
		if($item->docref_id_tipodoc_electronico == '00') {
			$nombre_doc_referencia = 'Otro Doc.';
		} else if($item->docref_id_tipodoc_electronico == '01') {
			$nombre_doc_referencia = 'Factura';
		} else if($item->docref_id_tipodoc_electronico == '03') {
			$nombre_doc_referencia = 'Boleta';
		} else if($item->docref_id_tipodoc_electronico == '07') {
			$nombre_doc_referencia = 'Nota de Crédito';
		} else if($item->docref_id_tipodoc_electronico == '08') {
			$nombre_doc_referencia = 'Nota de Débito';
		} else if($item->docref_id_tipodoc_electronico == '09') {
			$nombre_doc_referencia = 'Guía de Remisión';
		} else if($item->docref_id_tipodoc_electronico == '77') {
			$nombre_doc_referencia = 'Nota de Venta';
		} else if($item->docref_id_tipodoc_electronico == '88') {
			$nombre_doc_referencia = 'Cotización';
		} else {
			$nombre_doc_referencia = 'Otro Doc.';
		}


		$serie_numero = '';
		if(empty($item->docref_serie_comprobante) && empty($item->docref_numero_comprobante)) {

		} elseif (!empty($item->docref_serie_comprobante) && empty($item->docref_numero_comprobante)) {
			$serie_numero = $item->docref_serie_comprobante;
		} elseif (empty($item->docref_serie_comprobante) && !empty($item->docref_numero_comprobante)) { 
			$serie_numero = $item->docref_numero_comprobante;
		} elseif (!empty($item->docref_serie_comprobante) && !empty($item->docref_numero_comprobante)) {
			$serie_numero = $item->docref_serie_comprobante.'-'.$item->docref_numero_comprobante;
		}

		$texto_doc_referencia = '';
		if(!empty($serie_numero)) {
			$texto_doc_referencia = $nombre_doc_referencia.': '.$serie_numero;
		}

		$resp['nombre_doc_referencia'] = $nombre_doc_referencia;
		$resp['serie_numero'] = $serie_numero;
		$resp['texto_doc_referencia'] = $texto_doc_referencia;
		return $resp;
	}
}
?>