<?php
class ApivalidacionesController extends ControllerBase
{
	public function validar_contribuyente($data) {
		if(!isset($data['contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente';
			return $resp;
		}

		$contribuyente = $data['contribuyente'];

		if(!isset($contribuyente['token_contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->token_contribuyente';
			return $resp;
		}
		
		if(!isset($contribuyente['id_usuario_vendedor'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->id_usuario_vendedor';
			return $resp;
		}

		if(!isset($contribuyente['tipo_proceso'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->tipo_proceso';
			return $resp;
		}
		
		if(empty($contribuyente['token_contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->token_contribuyente';
			return $resp;
		}

		if(!isset($contribuyente['tipo_envio'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->tipo_envio';
			return $resp;
		}

		if($contribuyente['tipo_proceso'] != 'prueba' && $contribuyente['tipo_proceso'] != 'produccion') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->tipo_proceso tiene un valor inválido';
			return $resp;
		}

		if($contribuyente['tipo_envio'] != 'solo_firma' && $contribuyente['tipo_envio'] != 'inmediato') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->tipo_envio tiene un valor inválido';
			return $resp;
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $contribuyente['token_contribuyente'])));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->token tiene un valor inválido';
			return $resp;
		}

		if(empty($contribuyente['id_usuario_vendedor']) || intval($contribuyente['id_usuario_vendedor']) <= 0) {
			$usuario_administrador = Usuario::findFirst(array("id_rol in (1, 2, 3, 4, 5) and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente)));
			if(!$usuario_administrador) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'contribuyente';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No Existe un Usuario Válido para la Cuenta';
				return $resp;
			}

			$contribuyente['id_usuario_vendedor'] = $usuario_administrador->idusuario;
		}

		$contribuyente['id_usuario_vendedor'] = intval($contribuyente['id_usuario_vendedor']);
		
		$usuario_bd = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $contribuyente['id_usuario_vendedor'], 'id_contribuyente' => $contribuyente_bd->id_contribuyente)));
		if(!$usuario_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->id_usuario_vendedor tiene un valor inválido';
			return $resp;
		}

		$html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($usuario_bd->idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Suscripción no es válida, debe renovar su suscripción...';
			return $resp;
		}

		$contribuyente['id_contribuyente'] = $contribuyente_bd->id_contribuyente;
		$data['contribuyente'] = $contribuyente;

		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_cliente($data) {
		if(!isset($data['cliente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente';
			return $resp;
		}

		$cliente = $data['cliente'];

		if(!isset($cliente['tipo_docidentidad'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente->tipo_docidentidad';
			return $resp;
		}

		if(!isset($cliente['numerodocumento'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente->tipo_docidentidad';
			return $resp;
		}

		if(!isset($cliente['nombre'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente->tipo_docidentidad';
			return $resp;
		}

		if(intval($cliente['tipo_docidentidad']) != 0 && intval($cliente['tipo_docidentidad']) != 1 && intval($cliente['tipo_docidentidad']) != 6) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad cliente->tipo_docidentidad tiene un valor inválido';
			return $resp;
		}

		$emiclientesor['tipo_docidentidad'] = intval($cliente['tipo_docidentidad']) + 0;

		if($cliente['tipo_docidentidad'] != 0) {
			if($cliente['tipo_docidentidad'] == 1) { //dni
				if(strlen($cliente['numerodocumento']) != 8) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->numerodocumento tiene un valor inválido';
					return $resp;
				}
			}

			if($cliente['tipo_docidentidad'] == 6) { //dni
				if(strlen($cliente['numerodocumento']) != 11) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->numerodocumento tiene un valor inválido';
					return $resp;
				}
			}
		}

		$herramientas = new HerramientasController;

		if(isset($cliente['email'])) {
			if($cliente['email'] != '') {
				if(!$herramientas->verificar_validez_email($cliente['email'])) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->email tiene un valor inválido';
					return $resp;
				}
			}
		}

		if(isset($cliente['fecha_nac'])) {
			if($cliente['fecha_nac'] != '') {
				if(!$herramientas->validate_date_spanish($cliente['fecha_nac'])) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->fecha_nac tiene un valor inválido';
					return $resp;
				}
			}
		}

		$cliente['fecha_nac'] = strtoupper($cliente['fecha_nac']);

		if(isset($cliente['sexo'])) {
			if($cliente['sexo'] != '') {
				if(strtolower($cliente['sexo']) != 'masculino' && strtolower($cliente['sexo']) != 'femenino') {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->sexo tiene un valor inválido';
					return $resp;
				}		
			}
		}

		$data['cliente'] = $cliente;
		
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_detalle($data, $id_contribuyente, $moneda) {

		$this->factor_igv_sunat = round($data['cabecera_comprobante']['porcentaje_igv']/100, 2);
		
		if(!isset($data['detalle'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'detalle';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad detalle';
			return $resp;
		}

		if(!is_array($data['detalle'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'detalle';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Propiedad detalle, debe ser un array';
			return $resp;
		}

		if(count($data['detalle']) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'detalle';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentran items en el detalle';
			return $resp;
		}

		$lista = array();
		$n = 0;
		foreach($data['detalle'] as $item) {
			$n++;
			/*
			if(!isset($item['idproducto']) || empty($item['idproducto'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/idproducto';
				return $resp;
			}
			*/

			if(!isset($item['afecto_icbper']) || empty($item['afecto_icbper'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/afecto_icbper';
				return $resp;
			}

			if(!isset($item['id_tipoafectacionigv']) || empty($item['id_tipoafectacionigv'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/id_tipoafectacionigv';
				return $resp;
			}

			if(!isset($item['descripcion']) || empty($item['descripcion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/descripcion';
				return $resp;
			}

			if(!isset($item['idunidadmedida']) || empty($item['idunidadmedida'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/idunidadmedida';
				return $resp;
			}

			if(!isset($item['precio_venta']) || empty($item['precio_venta'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/precio_venta';
				return $resp;
			}

			if(!isset($item['cantidad']) || empty($item['cantidad'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/cantidad';
				return $resp;
			}

			if(!isset($item['codigo']) || empty($item['codigo'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/codigo';
				return $resp;
			}

			$item['idproducto'] = intval($item['idproducto']) + 0;
			$item['idunidadmedida'] = intval($item['idunidadmedida']) + 0;
			$item['id_tipoafectacionigv'] = intval($item['id_tipoafectacionigv']) + 0;
			$item['afecto_icbper'] = ($item['afecto_icbper'] == 'si')?'si':'no';
			$item['cantidad'] = floatval($item['cantidad']) + 0;

			$anio_actual =  date("Y");
			$icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
			$impuesto_icbper = !$icbper?0.5:$icbper->monto;

			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item['idproducto'], 'id_contribuyente' => $id_contribuyente)));
			if(!$producto) {
				$producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('codigo' => $item['codigo'], 'id_contribuyente' => $id_contribuyente)));

				//quizás también se tenga que buscar validando que sea de la sucursal indicada.
				if(!$producto) {
					$producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => 'P_TIENDAVIRTUAL', 'id_contribuyente' => $id_contribuyente)));
					if(!$producto) {
						$resp_prod = $this->crear_producto_general($id_contribuyente, $data['cabecera_comprobante']['idsucursal']);
						if($resp_prod['respuesta'] == 'error') {
							$resp['respuesta'] = 'error';
							$resp['codigo'] = 'detalle_item';
							$resp['titulo'] = 'error';
							$resp['mensaje'] = 'El producto'.$item['descripcion'].', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
							return $resp;
						}
		
						$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $resp_prod['id_producto'], 'id_contribuyente' => $id_contribuyente)));
					}
				}

				$item['idproducto'] = $producto->idproducto;
			}
			
			$item['idunidadmedida'] = isset($item['idunidadmedida'])?($item['idunidadmedida']=='NIU'?'NIU':'ZZ'):'NIU';
			$item['idunidadmedida'] = $item['idunidadmedida']=='ZZ'?20:7;
			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item['idunidadmedida'])));
			if(!$unidadmedida) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la unidad seleccionada para el producto '.$item['descripcion'];
				return $resp;
			}

			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item['id_tipoafectacionigv'])));
			if(!$tipoafectacionigv) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto '.$item['descripcion'];
				return $resp;
			}

			if($item['cantidad'] <= 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La cantidad no debe ser igual o menor a cero para el producto '.$item['descripcion'];
				return $resp;
			}

			$subtotal_icbper = 0;
			if($item['afecto_icbper'] == 'si') {
				$subtotal_icbper = round($impuesto_icbper*$item['cantidad'], 5);
			}

			$igv = 0;
			$subtotal = 0;
			if($item['id_tipoafectacionigv'] == 10) { //operacion gravada (incl.igv)
				if($item['precio_venta'] > 0 && $item['cantidad'] > 0) {
					$subtotal = round(($item['cantidad']*$item['precio_venta'])/(1 + $this->factor_igv_sunat), 2);
					$igv = round($item['cantidad']*$item['precio_venta'] - $subtotal, 2);
				}
			} else {
				$subtotal = round($item['cantidad']*$item['precio_venta'], 2);
				$igv = 0;
			}

			$item_nuevo = array(
				"row_identificadador" => $n,
				"idarticulo" => $item['idproducto'],
				"afecto_icbper" => $item['afecto_icbper'],
				"subtotal_icbper" => $subtotal_icbper,
				"id_tipoafectacionigv" => $item['id_tipoafectacionigv'],
				"descripcion" => $item['descripcion'],
				"text_tipo_afecigv" => $tipoafectacionigv->descripcion,
				"idunidadmedida" => $item['idunidadmedida'],
				"unidadmedida" => $unidadmedida->nombre,
				"id_cod_moneda" => $moneda,
				"cantidad" => $item['cantidad'],
				"precio" => $item['precio_venta'],
				"subtotal" => $subtotal,
				"igv" => $igv,
				"importe" => $subtotal + $igv,
				"estado" => "V",
				"codigo" => $item['codigo'],
				"html_lista_precios" => "",
				"item_detraccion_codigo" => "",
				"item_detraccion_porcentaje" => "",
				'tipo_unidad' => "unidad",
				'factor_igv_sunat' => $this->factor_igv_sunat
			);

			$lista[] = $item_nuevo;
		}

		$data['detalle'] = $lista;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function crear_producto_general($id_contribuyente, $id_sucursal) {
		$product = new Producto();
		$product->fecha_registro =  date('Y-m-d H:i:s');
		$product->id_contribuyente = $id_contribuyente;
		$product->idsucursal = $id_sucursal;

		$product->stock = 0;
		$product->precio_compra = 1;

		$product->valor_sin_igv = 10; //$valor_sin_igv
		$product->valor_con_igv = 11.8;
		
		$product->fecha_vencimiento = null;
		$product->marca = null;
		$product->codigo = 'P_TIENDAVIRTUAL';
		$product->id_unidad_medida = 7;
		$product->id_cod_detraccion = null;
		$product->id_tipoafectacionigv = 10;
		$product->id_categoria = null;
		$product->nombre = 'PRODUCTO TIENDA VIRTUAL';
		$product->id_cod_moneda = 'PEN';
		$product->foto = null;
		$product->nota = null;
		$product->stock_minimo = 1;
		$product->tipo_cambio_sunat = 3.981; 
		$product->precio_venta_minimo = 5;
		$product->multi_precio = 'no';
		$product->afecto_icbper = 'no';
		$product->estado = 'activo';

		try {
			if(!$product->save()) {
				$resp['respuesta'] = 'error';
				return $resp;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error: '.$e->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['id_producto'] = $product->idproducto;
		return $resp;
	}

	public function calcular_totales($data) {
		
		$detalle = $data['detalle'];
		$total_gravado = 0;
		$total_exonerado = 0;
		$total_inafecto = 0;
		$total_gratuito = 0;
		$total_exportacion = 0;
		$total_icbper = 0;
		$total_igv = 0;
		$subtotal_ventas = 0;

		foreach($detalle as $item) {
			$item = (object)$item;
			if($item->id_tipoafectacionigv == 10) {
				$total_gravado = $total_gravado + $item->importe;
				$subtotal_ventas = $subtotal_ventas + $item->importe;
			} else if($item->id_tipoafectacionigv == 30) {
				$total_inafecto = $total_inafecto + $item->importe;
				$subtotal_ventas = $subtotal_ventas + $item->importe;
			} else if($item->id_tipoafectacionigv == 40) {
				$subtotal_ventas = $subtotal_ventas + $item->importe;
				$total_exportacion = $total_exportacion + $item->importe;
			} else if($item->id_tipoafectacionigv == 20) {
				$total_exonerado = $total_exonerado + $item->importe;
				$subtotal_ventas = $subtotal_ventas + $item->importe;
			} else {
				$total_gratuito = $total_gratuito + $item->importe;
			}

			if($item->afecto_icbper == 'si') {
				$total_icbper = $total_icbper + $item->subtotal_icbper;
			}
		}
		
		$desc_imponible_gravado = 0;
		$desc_imponible_exonerado = 0;
		$desc_imponible_inafecto = 0;
		$desc_imponible_gratuito = 0;
		$desc_imposible_exportacion = 0;
		$desc_total_imponible = 0;
		$nuevo_total_gravado_sin_igv = $total_gravado/($this->factor_igv_sunat + 1);
		$descuento_factor = $data['cabecera_comprobante']['descuento_porcentaje'] / 100;
		if($descuento_factor > 0) {
			$desc_imponible_gravado = $nuevo_total_gravado_sin_igv*$descuento_factor; //total descuento sin igv
			$desc_imponible_exonerado = $total_exonerado*$descuento_factor;
			$desc_imponible_inafecto = $total_inafecto*$descuento_factor;
			$desc_imposible_exportacion = $total_exportacion*$descuento_factor;
			$desc_total_imponible = $desc_imponible_gravado + $desc_imponible_exonerado + $desc_imponible_inafecto + $desc_imposible_exportacion;
		}

		$total_gravado = $nuevo_total_gravado_sin_igv - $desc_imponible_gravado;
		$total_exonerado = $total_exonerado - $desc_imponible_exonerado;
		$total_inafecto = $total_inafecto - $desc_imponible_inafecto;
		$total_exportacion = $total_exportacion - $desc_imposible_exportacion;

		$otros_cargos = 0;

		$total_igv = $total_gravado*$this->factor_igv_sunat;
		$total_a_pagar = $total_gravado + $total_exonerado + $total_inafecto + $total_exportacion + $total_igv + $otros_cargos + $total_icbper;
		
		$totales = array(
			"total_gravado" => round($total_gravado, 2),
			"total_exonerado" => round($total_exonerado, 2),
			"total_inafecto" => round($total_inafecto, 2),
			"total_gratuito" => round($total_gratuito, 2),
			"total_exportacion" => round($total_exportacion, 2),
			"total_icbper" => round($total_icbper, 2),
			"sub_total_ventas" => round($subtotal_ventas, 2),
			"total_igv" => round($total_igv, 2),
			"total_a_pagar" => round($total_a_pagar, 2),
			"desc_total_imponible" => round($desc_total_imponible, 2),
			"desc_total_porcentaje" => round($data['cabecera_comprobante']['descuento_porcentaje'], 2),
			"otros_cargos" => round($otros_cargos, 2)
		);

		$resp['respuesta'] = 'ok';
		$resp['totales'] = $totales;
		return $resp;
	}
}