<?php
class ApiguiaremisionController extends ControllerBase
{
	public function procesar_guia_remision($data, $token) {
		$validaciones = new ApivalidacionesController;

		$resp_contribuyente = $validaciones->validar_contribuyente($data);
		if(isset($resp_contribuyente['respuesta']) && $resp_contribuyente['respuesta'] == 'error') {
			return $resp_contribuyente;
		}
		$data = $resp_contribuyente['data'];

		$resp_cabecera = $this->validar_cabecera_guiaremision($data);
		if(isset($resp_cabecera['respuesta']) && $resp_cabecera['respuesta'] == 'error') {
			return $resp_cabecera;
		}
		$data = $resp_cabecera['data'];

		$resp_cliente = $this->validar_cliente($data['contribuyente']['id_contribuyente'], $data);
		if(isset($resp_cliente['respuesta']) && $resp_cliente['respuesta'] == 'error') {
			return $resp_cliente;
		}
		$data = $resp_cliente['data'];

		$resp_detalle = $this->validar_detalle($data, $data['contribuyente']['id_contribuyente']);
		if(isset($resp_detalle['respuesta']) && $resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}
		$data = $resp_detalle['data']; 

		$resp_transporte = $this->validar_transporte($data);
		if(isset($resp_transporte['respuesta']) && $resp_transporte['respuesta'] == 'error') {
			return $resp_transporte;
		}
		$data = $resp_transporte['data'];

		$resp_direccion_partida = $this->validar_direccion_partida($data);
		if(isset($resp_direccion_partida['respuesta']) && $resp_direccion_partida['respuesta'] == 'error') {
			return $resp_direccion_partida;
		}
		$data = $resp_direccion_partida['data'];

		$resp_direccion_llegada = $this->validar_direccion_llegada($data);
		if(isset($resp_direccion_llegada['respuesta']) && $resp_direccion_llegada['respuesta'] == 'error') {
			return $resp_direccion_llegada;
		}
		$data = $resp_direccion_llegada['data'];

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => intval($data['contribuyente']['id_usuario_vendedor']), 'id_contribuyente' => intval($data['contribuyente']['id_contribuyente']))));
		$datapost = $this->generar_datapost($data);
		
		$guiaremision = new GuiaderemisionController;
		$resp_proceso = $guiaremision->validar_y_procesar_guia_remision($usuario, $datapost);
		return $resp_proceso;
	}

	public function validar_cabecera_guiaremision($data) {
		if(!isset($data['cabecera_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante';
			return $resp;
		}

		$cabecera = $data['cabecera_comprobante'];

		if(!isset($cabecera['tipo_documento'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->tipo_documento';
			return $resp;
		}

		if(!isset($cabecera['idsucursal'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->idsucursal';
			return $resp;
		}

		if(intval($cabecera['idsucursal']) > 0) {
			$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idsucursal' => intval($cabecera['idsucursal']), 'id_contribuyente' => $data['contribuyente']['id_contribuyente'])));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->idsucursal';
				return $resp;
			}
		} else {
			$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $data['contribuyente']['id_contribuyente'])));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->idsucursal';
				return $resp;
			}

			$cabecera['idsucursal'] = $sucursal->idsucursal;
		}

		if(!isset($cabecera['fecha_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->fecha_comprobante';
			return $resp;
		}

		if(!isset($cabecera['motivo_traslado'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->motivo_traslado';
			return $resp;
		}

		if(!isset($cabecera['modalidad_traslado'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->modalidad_traslado';
			return $resp;
		}

		if(!isset($cabecera['fecha_traslado'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->fecha_traslado';
			return $resp;
		}

		if(!isset($cabecera['otro_motivo_traslado'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->otro_motivo_traslado';
			return $resp;
		}

		if(!isset($cabecera['pesobruto'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->pesobruto';
			return $resp;
		}

		if(!isset($cabecera['numero_bultos'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->numero_bultos';
			return $resp;
		}

		if(!isset($cabecera['numero_contenedor'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->numero_contenedor';
			return $resp;
		}

		if(!isset($cabecera['codigo_puerto'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->codigo_puerto';
			return $resp;
		}
		
		if(!isset($cabecera['tipo_doc_referencia'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->tipo_doc_referencia';
			return $resp;
		}

		if(!isset($cabecera['serie_doc_referencia'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->serie_doc_referencia';
			return $resp;
		}

		if(!isset($cabecera['numero_doc_referencia'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->numero_doc_referencia';
			return $resp;
		}

		if(!isset($cabecera['informacion_adicional_sunat'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->informacion_adicional_sunat';
			return $resp;
		}

		$data['cabecera_comprobante'] = $cabecera;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_detalle($data, $id_contribuyente) {
		$validaciones = new ApivalidacionesController;

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

		$guia_de_remision = new GuiaderemisionController;

		$lista = array();
		$n = 0;
		foreach($data['detalle'] as $item) {
			$n++;

			if(!isset($item['codigo']) || empty($item['codigo'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/codigo';
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

			if(!isset($item['peso']) || empty($item['peso'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/peso';
				return $resp;
			}

			if(!isset($item['cantidad']) || empty($item['cantidad'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/cantidad';
				return $resp;
			}

			$item['idproducto'] = isset($item['idproducto'])?$item['idproducto']:0;

			$item['idproducto'] = intval($item['idproducto']) + 0;
			$item['idunidadmedida'] = intval($item['idunidadmedida']) + 0;
			$item['cantidad'] = floatval($item['cantidad']) + 0;
			$item['peso'] = floatval($item['peso']) + 0;

			if(isset($item['idunidadmedida']) && $item['idunidadmedida'] == 'NIU') {
			    $item['idunidadmedida'] = 7;
			} else if(isset($item['idunidadmedida']) && $item['idunidadmedida'] == 'ZZ') {
			    $item['idunidadmedida'] = 20;
			} else {
			    $item['idunidadmedida'] = intval($item['idunidadmedida']);
			}

			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item['idproducto'], 'id_contribuyente' => $id_contribuyente)));
			if(!$producto) {
				$producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('codigo' => $item['codigo'], 'id_contribuyente' => $id_contribuyente)));

				//quizás también se tenga que buscar validando que sea de la sucursal indicada.
				if(!$producto) {
					$data_new_product['codigo_producto'] = $item['codigo'];
					$data_new_product['nombre_producto'] = $item['descripcion'];
					$data_new_product['idunidad_medida'] = $item['idunidadmedida'];
					$data_new_product['peso'] = $item['peso'];
					
					$resp_prod = $guia_de_remision->crear_producto_en_bd($id_contribuyente, $data['cabecera_comprobante']['idsucursal'], $data_new_product);
					if($resp_prod['respuesta'] == 'error') {
						$resp['respuesta'] = 'error';
						$resp['codigo'] = 'detalle_item';
						$resp['titulo'] = 'error';
						$resp['mensaje'] = 'El producto'.$item['descripcion'].', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
						return $resp;
					}
	
					$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $resp_prod['id_producto'], 'id_contribuyente' => $id_contribuyente)));
				}

				$item['idproducto'] = $producto->idproducto;
			}
			
			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item['idunidadmedida'])));
			if(!$unidadmedida) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la unidad seleccionada para el producto '.$item['descripcion'];
				return $resp;
			}

			if($item['cantidad'] <= 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La cantidad no debe ser igual o menor a cero para el producto '.$item['descripcion'];
				return $resp;
			}

			if($item['peso'] <= 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La cantidad no debe ser igual o menor a cero para el producto '.$item['descripcion'];
				return $resp;
			}

			$lista[] = array(
				"idarticulo"		=> $item['idproducto'],
				"codigo"			=> $item['codigo'],
				"descripcion"		=> $item['descripcion'],
				"idunidadmedida"	=> $item['idunidadmedida'],
				"unidadmedida"		=> $unidadmedida->nombre,
				"cantidad"			=> $item['cantidad'],
				"peso"				=> $item['peso']
			);
		}

		$data['detalle'] = $lista;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_transporte($data) {
		if(!isset($data['transporte'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'transporte';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad transporte';
			return $resp;
		}

		if(!is_array($data['transporte'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'transporte';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Propiedad transporte, debe ser un array';
			return $resp;
		}

		$transporte = $data['transporte'];

		if(!isset($transporte['tipo_docidentidad'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'transporte';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad transporte->tipo_docidentidad';
			return $resp;
		}

		if(!isset($transporte['numerodocumento'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'transporte';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad transporte->numerodocumento';
			return $resp;
		}

		if(!isset($transporte['nombre'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'transporte';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad transporte->nombre';
			return $resp;
		}

		if(!isset($transporte['num_placa'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'transporte';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad transporte->num_placa';
			return $resp;
		}

		$data['transporte']	= $transporte;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_direccion_partida($data) {
		if(!isset($data['direccion_partida'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_partida';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad direccion_partida';
			return $resp;
		}

		if(!is_array($data['direccion_partida'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_partida';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Propiedad direccion_partida, debe ser un array';
			return $resp;
		}

		$direccion_partida = $data['direccion_partida'];

		if(!isset($direccion_partida['direccion'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_partida';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad direccion_partida->direccion';
			return $resp;
		}

		if(!isset($direccion_partida['ubigeo'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_partida';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad direccion_partida->ubigeo';
			return $resp;
		}

		$data['direccion_partida'] = $direccion_partida;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_direccion_llegada($data) {
		if(!isset($data['direccion_llegada'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_llegada';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad direccion_llegada';
			return $resp;
		}

		if(!is_array($data['direccion_llegada'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_llegada';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Propiedad direccion_llegada, debe ser un array';
			return $resp;
		}

		$direccion_llegada = $data['direccion_llegada'];

		if(!isset($direccion_llegada['direccion'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_llegada';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad direccion_llegada->direccion';
			return $resp;
		}

		if(!isset($direccion_llegada['ubigeo'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'direccion_llegada';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad direccion_llegada->ubigeo';
			return $resp;
		}

		$data['direccion_llegada'] = $direccion_llegada;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_cliente($id_contribuyente, $data) {
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

		$cliente_bd = Cliente::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad: and num_doc = :num_doc: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_tipodocidentidad' => $cliente['tipo_docidentidad'], 'num_doc' => $cliente['numerodocumento'], 'id_contribuyente' => $id_contribuyente)));
		$cliente['idcliente'] = '';
		if(!$cliente_bd) {
			$new_cliente = new Cliente();
			$new_cliente->codigo = uniqid('CLIE_',true);
			$new_cliente->id_contribuyente = $id_contribuyente;
			$new_cliente->fecha_registro = date('Y-m-d H:i:s');
			$new_cliente->estado = 'activo';
			$new_cliente->id_tipodocidentidad = $cliente['tipo_docidentidad'];
			$new_cliente->num_doc = $cliente['numerodocumento'];
			$new_cliente->email = isset($cliente['email'])?$cliente['email']:'';
			$new_cliente->razon_social = $cliente['nombre'];
			$new_cliente->direccion_fiscal = null;

			try {
				if(!$new_cliente->save()) {
					$msg = '';
					foreach ($new_cliente->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
		
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'El cliente no ha sido guardado correctamente';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cliente';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'El cliente no ha sido guardado correctamente: '.$e->getMessage();
				return $resp;
			}

			$cliente['idcliente'] = $new_cliente->idcliente;
		} else {
			$cliente['idcliente'] = $cliente_bd->idcliente;
		}

		$data['cliente'] = $cliente;
		
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function generar_datapost($data) {
		$cabecera = (object)$data['cabecera_comprobante'];
		$cliente = (object)$data['cliente'];
		$contribuyente = (object)$data['contribuyente'];
		$transporte = (object)$data['transporte'];
		$direccion_partida = (object)$data['direccion_partida'];
		$direccion_llegada = (object)$data['direccion_llegada'];
		$detalle = $data['detalle'];

		$conductor_num_licencia = isset($transporte->conductor_num_licencia)?$transporte->conductor_num_licencia:'';
		if(empty($conductor_num_licencia)) {
			$conductor_num_licencia = isset($cabecera->conductor_num_licencia)?$cabecera->conductor_num_licencia:'';
		}

		$datapost = array(
			"select_tipo_doc_electronico" 			=> '09',
			"select_sucursal" 						=> isset($cabecera->idsucursal)?$cabecera->idsucursal:'',
			"fecha_comprobante" 					=> isset($cabecera->fecha_comprobante)?$cabecera->fecha_comprobante:'',
			"idcliente" 							=> isset($cliente->idcliente)?$cliente->idcliente:'',
			"cliente_email" 						=> isset($cliente->email)?$cliente->email:'',
			"motivo_traslado"						=> isset($cabecera->motivo_traslado)?$cabecera->motivo_traslado:'',
			"modalidad_traslado" 					=> isset($cabecera->modalidad_traslado)?$cabecera->modalidad_traslado:'',
			"fecha_traslado" 						=> isset($cabecera->fecha_traslado)?$cabecera->fecha_traslado:'',
			"otro_motivo_traslado" 					=> isset($cabecera->otro_motivo_traslado)?$cabecera->otro_motivo_traslado:'',
			"pesobruto" 							=> isset($cabecera->pesobruto)?$cabecera->pesobruto:'',
			"numero_bultos" 						=> isset($cabecera->numero_bultos)?$cabecera->numero_bultos:'',
			"numero_contenedor" 					=> isset($cabecera->numero_contenedor)?$cabecera->numero_contenedor:'',
			"codigo_puerto" 						=> isset($cabecera->codigo_puerto)?$cabecera->codigo_puerto:'',

			"transporte_tipo_docidentidad" 			=> isset($transporte->tipo_docidentidad)?$transporte->tipo_docidentidad:'',
			"transporte_numerodocumento" 			=> isset($transporte->numerodocumento)?$transporte->numerodocumento:'',
			"transporte_nombre" 					=> isset($transporte->nombre)?$transporte->nombre:'',
			"transporte_num_placa" 					=> isset($transporte->num_placa)?$transporte->num_placa:'',
			"conductor_num_licencia"				=> $conductor_num_licencia,

			"direccionpartida" 						=> isset($direccion_partida->direccion)?$direccion_partida->direccion:'',
			"ubigeo_partida" 						=> isset($direccion_partida->ubigeo)?$direccion_partida->ubigeo:'',
			"direccionllegada" 						=> isset($direccion_llegada->direccion)?$direccion_llegada->direccion:'',
			"ubigeo_llegada" 						=> isset($direccion_llegada->ubigeo)?$direccion_llegada->ubigeo:'',

			"select_doc_referencia" 				=> "", //siempre vacio

			"select_tipo_doc_referencia" 			=> !empty($cabecera->tipo_doc_referencia)?$cabecera->tipo_doc_referencia:'01',
			"serie_doc_referencia" 					=> isset($cabecera->serie_doc_referencia)?$cabecera->serie_doc_referencia:'',
			"numero_doc_referencia" 				=> isset($cabecera->numero_doc_referencia)?$cabecera->numero_doc_referencia:'',
			"informacion_adicional_sunat" 			=> isset($cabecera->informacion_adicional_sunat)?$cabecera->informacion_adicional_sunat:'',

			"modalidad_envio_sunat" 				=> !isset($contribuyente->tipo_envio)?'':$contribuyente->tipo_envio,
			"detalle_guia"							=> json_encode($detalle),

			"confirmacion" 							=> 'si',
			"enviar_a_sunat"                		=> "no"  //Si deseas enviar a SUNAT, colocar "si", caso contrario "no"
		);

		return $datapost;
	}
}
?>