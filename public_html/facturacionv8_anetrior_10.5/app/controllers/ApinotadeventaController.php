<?php
class ApinotadeventaController extends ControllerBase
{
	public function procesar_nota_venta($data, $token) {
		$validaciones = new ApivalidacionesController;

		$resp_contribuyente = $validaciones->validar_contribuyente($data);
		if(isset($resp_contribuyente['respuesta']) && $resp_contribuyente['respuesta'] == 'error') {
			return $resp_contribuyente;
		}
		$data = $resp_contribuyente['data'];

		$resp_cabecera = $this->validar_cabecera_nota_venta($data);
		if(isset($resp_cabecera['respuesta']) && $resp_cabecera['respuesta'] == 'error') {
			return $resp_cabecera;
		}
		$data = $resp_cabecera['data'];
		$this->factor_igv_sunat = round($data['cabecera_comprobante']['porcentaje_igv']/100, 2);

		$resp_cliente = $validaciones->validar_cliente($data);
		if(isset($resp_cliente['respuesta']) && $resp_cliente['respuesta'] == 'error') {
			return $resp_cliente;
		}
		$data = $resp_cliente['data'];

		$resp_detalle = $validaciones->validar_detalle($data, $data['contribuyente']['id_contribuyente'], $data['cabecera_comprobante']['moneda']);
		if(isset($resp_detalle['respuesta']) && $resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}
		$data = $resp_detalle['data']; 

		$resp_totales = $validaciones->calcular_totales($data);
		if(isset($resp_totales['respuesta']) && $resp_totales['respuesta'] == 'error') {
			return $resp_totales;
		}
		$data['totales'] = $resp_totales['totales'];

		$datapost = $this->generar_datapost_nota_venta($data);
		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => intval($data['contribuyente']['id_usuario_vendedor']), 'id_contribuyente' => intval($data['contribuyente']['id_contribuyente']))));
		
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_proceso = $documentoelectronico->procesar_documento_electronico($usuario, $datapost);
		return $resp_proceso;
	}

	public function validar_cabecera_nota_venta($data) {
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

		if(!isset($cabecera['moneda'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->moneda';
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
		

		if(!isset($cabecera['id_condicionpago'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->id_condicionpago';
			return $resp;
		}

		if(!isset($cabecera['fecha_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->fecha_comprobante';
			return $resp;
		}

		if(!isset($cabecera['nro_placa'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->nro_placa';
			return $resp;
		}

		if(!isset($cabecera['nro_orden'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->nro_orden';
			return $resp;
		}

		if(!isset($cabecera['guia_remision'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->guia_remision';
			return $resp;
		}

		if(!isset($cabecera['descuento_porcentaje'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->descuento_porcentaje';
			return $resp;
		}

		if(!isset($cabecera['observacion'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->observacion';
			return $resp;
		}

		if($cabecera['moneda'] != 'USD' && $cabecera['moneda'] != 'PEN') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->moneda';
			return $resp;
		}

		if($cabecera['tipo_documento'] != '77') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->tipo_documento';
			return $resp;
		}

		if(empty($cabecera['descuento_porcentaje'])) {
			$cabecera['descuento_porcentaje'] = 0;
		}
		$cabecera['descuento_porcentaje'] = empty($cabecera['descuento_porcentaje'])?0:$cabecera['descuento_porcentaje'];
		$cabecera['descuento_porcentaje'] = round(floatval($cabecera['descuento_porcentaje']), 2);
		if($cabecera['descuento_porcentaje'] < 0 || $cabecera['descuento_porcentaje'] > 100) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->descuento_porcentaje';
			return $resp;
		}

		if(isset($cabecera['porcentaje_igv'])) {
			$cabecera['porcentaje_igv'] = intval($cabecera['porcentaje_igv']) + 0;
			if($cabecera['porcentaje_igv'] != 18 && $cabecera['porcentaje_igv'] != 10) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->porcentaje_igv, solo se aceptan los siguientes valores (18 y 10)';
				return $resp;
			}
		} else {
			//si no existe entonces se considera siempre el 18%
			$cabecera['porcentaje_igv'] = 18;
		}

		$data['cabecera_comprobante'] = $cabecera;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function generar_datapost_nota_venta($data) {
		$cabecera = (object)$data['cabecera_comprobante'];
		$cliente = (object)$data['cliente'];
		$contribuyente = (object)$data['contribuyente'];
		$totales = (object)$data['totales'];
		$detalle = $data['detalle'];

		$apisunat = new ApisunatController;
		$resp_tipo_cambio = $apisunat->get_tipo_cambio(date("Y-m-d"));
		$tipo_cambio = $resp_tipo_cambio['venta'];

		$anio_actual =  date("Y");
		$icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
		$impuesto_icbper = !$icbper?0.5:$icbper->monto;

		$convertir_numero_letras = new NumeroALetras();
		$total_a_pagar_letras = ($cabecera->moneda == 'USD')?$convertir_numero_letras->convert(floatval($totales->total_a_pagar) , 'dólares'):$convertir_numero_letras->convert(floatval($totales->total_a_pagar) , 'soles');

		$datapost = array(
			//cabecera del documento
			"tipo_operacion_docelectronico" => "0101",
			"select_tipo_doc_electronico" => !isset($cabecera->tipo_documento)?'':$cabecera->tipo_documento, //{03" => "boleta, 01" => "factura}
			"codmoneda_comprobante" => !isset($cabecera->moneda)?'':$cabecera->moneda, //{PEN, USD}
			"select_sucursal" => !isset($cabecera->idsucursal)?'':$cabecera->idsucursal, //debe existir en la base de datos
			"serie_comprobante" => "-",
			"numero_comprobante" => "-",
			"fecha_comprobante" => !isset($cabecera->fecha_comprobante)?'':$cabecera->fecha_comprobante, // formato" => "dia/mes/año
			"fecha_vence_comprobante" => !isset($cabecera->fecha_comprobante)?'':$cabecera->fecha_comprobante,
			"tipo_cambio_comprobante" => $tipo_cambio, //
			"nro_placa_vehiculo" => !isset($cabecera->nro_placa)?'':$cabecera->nro_placa,
			"nro_orden" => !isset($cabecera->nro_orden)?'':$cabecera->nro_orden,
			"guia_remision_manual" => !isset($cabecera->guia_remision)?'':$cabecera->guia_remision,
			"select_usuario_vendedor" => !isset($contribuyente->id_usuario_vendedor)?'':$contribuyente->id_usuario_vendedor,
			"modalidad_envio_sunat" => !isset($contribuyente->tipo_envio)?'':$contribuyente->tipo_envio,
			"doc_impuesto_icbper" => $impuesto_icbper,

			//Data del Cliente
			"id_cliente_documento" => '',
			"cliente_tipo_docidentidad" => !isset($cliente->tipo_docidentidad)?'':$cliente->tipo_docidentidad,
			"cliente_numerodocumento" => !isset($cliente->numerodocumento)?'':$cliente->numerodocumento,
			"cliente_nombre" => !isset($cliente->nombre)?'':$cliente->nombre,
			"cliente_direccion" => !isset($cliente->direccion)?'':$cliente->direccion,
			"numero_celular" => !isset($cliente->celular)?'':$cliente->celular,
			"cliente_api_foto" => !isset($cliente->foto)?'':$cliente->foto,
			"cliente_api_fecha_nac" => !isset($cliente->fecha_nac)?'':$cliente->fecha_nac,
			"cliente_api_sexo" => !isset($cliente->sexo)?'':$cliente->sexo,
			"cliente_email" => !isset($cliente->email)?'':$cliente->email,

			//cabecera del documento datos debajo del detalle
			"opcion_tipo_venta" => "contado",
			"confirmacion" => 'si',
			"fecha_pago_comprobante" => "",
			"txt_monto_adeudado" => "",
			"txt_pago_parcial" => "",
			"condicionpago_comprobante" => "",
			"txt_numero_operacion" => "",
			"observacion_documento" 			=> !isset($cabecera->observacion)?'':$cabecera->observacion,
			"tipo_docelectronico_modificar" => "",
			"id_motivo_nota_credito" => "",
			"id_motivo_nota_debito" => "",
		
			//opción de descuento 
			"opcion_tipo_descuento" => ($totales->desc_total_porcentaje > 0)?'on':'',
			"txt_descuento_total" => !isset($totales->desc_total_imponible)?0:$totales->desc_total_imponible,
			"txt_descuento_porcentaje" => !isset($totales->desc_total_porcentaje)?0:$totales->desc_total_porcentaje,

			//totales documento
			"txt_sub_total_ventas" => !isset($totales->sub_total_ventas)?0:$totales->sub_total_ventas,
			"txt_gravada_comprobante" => !isset($totales->total_gravado)?0:$totales->total_gravado,
			"txt_exonerada_comprobante" =>!isset($totales->total_exonerado)?0:$totales->total_exonerado,
			"txt_inafecta_comprobante" => !isset($totales->total_inafecto)?0:$totales->total_inafecto,
			"txt_exportacion_comprobante" => !isset($totales->total_exportacion)?0:$totales->total_exportacion,
			"txt_descuento_comprobante" => !isset($totales->desc_total_imponible)?0:$totales->desc_total_imponible,
			"txt_igv_comprobante" => !isset($totales->total_igv)?0:$totales->total_igv,
			"txt_gratuita_comprobante" => !isset($totales->total_gratuito)?0:$totales->total_gratuito,
			"txt_icbper_comprobante" => !isset($totales->total_icbper)?0:$totales->total_icbper,
			"txt_otros_cargos_comprobante" => !isset($totales->otros_cargos)?0:$totales->otros_cargos,
			"txt_total_comprobante" => !isset($totales->total_a_pagar)?0:$totales->total_a_pagar,
			"txt_total_letras" => $total_a_pagar_letras,
			"detalle" => json_encode($detalle),
			
			//data extra no utilizada para facturas y boletas
			"total_recibido" => "",
			"txt_otros_cargos_comprobante_input" => "0.0",
			"tipo_percepcion" => "51",
			"monto_base_percepcion" => "",
			"porcentaje_percepcion" => "",
			"monto_percepcion" => "",
			"detraccion_tipo_pago" => "001",
			"detraccion_id_numero_cuenta" => "0",
			"detraccion_codigo_bien" => "001",
			"porcentaje_detraccion" => "",
			"monto_detraccion" => "",
			"texto_detraccion" => "",
			"doc_guardado_id_tipodoc_electronico" => "03",
			"doc_guardado_serie_comprobante" => "",
			"doc_guardado_numero_comprobante" => "",
			"tipo_doc_guardado" => "",
			"numero_doc_guardado" => "",
			"serie_doc_guardado" => "",
			"doc_modifica_id_tipodoc_electronico" => "",
			"doc_modifica_serie_comprobante" => "",
			"doc_modifica_numero_comprobante" => "",
			"flexdatalist-cliente_numerodocumento" => "",
			"flexdatalist-cliente_nombre" => "",

			"select_igv_sunat"	=> isset($cabecera->porcentaje_igv)?$cabecera->porcentaje_igv:18
		);

		return $datapost;
	}
}
?>