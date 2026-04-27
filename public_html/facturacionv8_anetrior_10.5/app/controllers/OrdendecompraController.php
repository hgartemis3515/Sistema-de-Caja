<?php
class OrdendecompraController extends ControllerBase
{
    public function indexAction() {

    }

	public function get_data_orden_compra($orden_compra) {
		$id_contribuyente 		= $contribuyente['id_contribuyente'];
		$id_tipodoc_electronico = $orden_compra['id_tipodoc_electronico'];
		$serie_comprobante 		= $orden_compra['serie_comprobante'];
		$numero_comprobante 	= $orden_compra['numero_comprobante'];
		$tipo_envio_sunat 		= $orden_compra['tipo_envio_sunat'];

		$oden_compra = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
		if(!$orden_compra) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la Orden de Compra Seleccionada';
			echo json_encode($resp);
			exit();
		}

		$proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor:", 'bind' => array('id_proveedor' => $orden_compra->id_proveedor)));

		/*
		$data_orden_compra = array(
			public $id_contribuyente;
			public $id_tipodoc_electronico;
			public $serie_comprobante;
			public $numero_comprobante;
			public $tipo_envio_sunat;
			public $idsucursal;
			public $id_proveedor;
			public $fecha_registro;
			public $fecha_comprobante;
			public $estado;
			
			public $total_gravadas;
			public $total_inafecta;
			public $total_exoneradas;
			public $total_gratuitas;
			public $total_exportacion;
			public $total_icbper;
			public $total_descuento;
			public $porcentaje_descuento_total;
			public $sub_total;
			public $porcentaje_igv;
			public $total_igv;
			public $total_isc;
			public $total_otr_imp;
			public $total;


			public $nro_guia_remision;
			public $cod_guia_remision;
			public $nro_otr_comprobante;
			
			public $id_codigomoneda;
			public $tipo_cambio_sunat;
			public $id_usuario;
			public $nota;

			public $fecha_vto_comprobante;
			public $id_tipo_comprobante_modifica;
			public $serie_documento_modifica;
			public $nro_documento_modifica;
			public $id_cod_tipomotivo_credito;
			public $descripcion_motivo_credito;
			public $id_cod_tipomotivo_debito;
			public $descripcion_motivo_debito;
			public $fecha_comprobante_modifica;
			public $id_compra_modifica;
			
			public $id_condicionpago;
			public $monto_adeudado;
			public $monto_adeudado_inicial;
			public $fecha_pagopendiente;
			public $cpago_nrooperacion;
			public $cpago_fechadeposito;
			public $cpago_idbanco;
			public $tipo_compra;
			public $log_condicion_pago;
			public $log;

		);
		*/
	}

	public function anularOrdenCompraAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

			$id_contribuyente = $contribuyente->id_contribuyente;
			$id_tipodoc_electronico = $datapost['id_tipodoc_electronico'];
			$serie_comprobante = $datapost['serie_comprobante'];
			$numero_comprobante = $datapost['numero_comprobante'];
			$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

			$orden_compra = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			if(!$orden_compra) {
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la Orden de Compra Seleccionada';
                echo json_encode($resp);
                exit();
			}

			$confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Necesitamos tu Confirmación';
				$resp['mensaje'] = '¿Realmente deseas anular la orden de compra?';
				echo json_encode($resp);
				exit();
			}

			$orden_compra->estado = 'inactivo';
			try {
				if(!$orden_compra->save()) {
					$msg = '';
					foreach ($orden_compra->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
		
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $msg;
					echo json_encode($resp);
					exit();
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Error al anular la Orden de Compra: '.$e->getMessage();
				echo json_encode($resp);
				exit();
			}

			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Anulación Exitosa';
			$resp['mensaje'] = 'Se ha anulado la Orden de Compra correctamente!';
			echo json_encode($resp);
			exit();
		}
	}

    public function crear_orden_de_compra($contribuyente, $usuario, $id_proveedor, $datapost, $lista_detalle, $idsucursal) {
        $total = !isset($datapost['txt_total_comprobante'])?0:round(floatval($datapost['txt_total_comprobante']), 2);
		$total_igv = !isset($datapost['txt_igv_comprobante'])?0:round(floatval($datapost['txt_igv_comprobante']), 2);
		$sub_total = round($total - $total_igv, 2);

		$datapost['serie_comprobante'] = $this->get_serie_orden_compra($idsucursal);
		$datapost['numero_comprobante'] = $this->get_correlativo_orden_compra($contribuyente->id_contribuyente, $datapost['tipo_comprobante'], $datapost['serie_comprobante'], $contribuyente->tipo_envio_sunat, $idsucursal);

		$fecha_actual =  date('Y-m-d H:i:s');
		
		$new_orden_compra = new OrdenCompra();
		
		$new_orden_compra->id_contribuyente = $usuario->id_contribuyente;
		$new_orden_compra->id_tipodoc_electronico = $datapost['tipo_comprobante'];
		$new_orden_compra->serie_comprobante = strtoupper($datapost['serie_comprobante']);
		$new_orden_compra->numero_comprobante = $datapost['numero_comprobante'];
        $new_orden_compra->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

        $new_orden_compra->idsucursal = $idsucursal;
        $new_orden_compra->id_proveedor = $id_proveedor;
        $new_orden_compra->fecha_registro = $fecha_actual;
        $new_orden_compra->fecha_comprobante = date("Y-m-d", strtotime($datapost['fecha_comprobante']));
        $new_orden_compra->estado = 'activo';

		$new_orden_compra->total_gravadas = !isset($datapost['txt_gravada_comprobante'])?0:round(floatval($datapost['txt_gravada_comprobante']), 2); //id_tipoafectacionigv: 10
		$new_orden_compra->total_inafecta = !isset($datapost['txt_inafecta_comprobante'])?0:round(floatval($datapost['txt_inafecta_comprobante']), 2); //id_tipoafectacionigv: 30
		$new_orden_compra->total_exoneradas = !isset($datapost['txt_exonerada_comprobante'])?0:round(floatval($datapost['txt_exonerada_comprobante']), 2); //id_tipoafectacionigv: 20
		$new_orden_compra->total_gratuitas = !isset($datapost['txt_gratuita_comprobante'])?0:round(floatval($datapost['txt_gratuita_comprobante']), 2); //id_tipoafectacionigv: todas las demás
		$new_orden_compra->total_exportacion = !isset($datapost['txt_exportacion_comprobante'])?0:round(floatval($datapost['txt_exportacion_comprobante']), 2); //id_tipoafectacionigv: 40;
		$new_orden_compra->total_icbper = !isset($datapost['txt_icbper_comprobante'])?0:round(floatval($datapost['txt_icbper_comprobante']), 2); //id_tipoafectacionigv: 40;
		$new_orden_compra->total_descuento = !isset($datapost['txt_descuento_comprobante'])?0:round(floatval($datapost['txt_descuento_comprobante']), 2);
		$new_orden_compra->porcentaje_descuento_total = !isset($datapost['txt_descuento_porcentaje'])?0:round(floatval($datapost['txt_descuento_porcentaje']), 2);
		$new_orden_compra->sub_total = $sub_total;
		$new_orden_compra->porcentaje_igv = "18.00";
		$new_orden_compra->total_igv = $total_igv;
		$new_orden_compra->total_isc = '0';
		$new_orden_compra->total_otr_imp = '0';
		$new_orden_compra->total = $total;

		$new_orden_compra->nro_guia_remision = '';
		$new_orden_compra->cod_guia_remision = '';
		$new_orden_compra->nro_otr_comprobante = '';
		
		$new_orden_compra->id_codigomoneda = $datapost['codmoneda_comprobante'];
		$new_orden_compra->tipo_cambio_sunat = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);
		$new_orden_compra->id_usuario = $usuario->idusuario;
		$new_orden_compra->nota = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];

		//Tipo de venta y condición de pago
		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		$tipo_compra = !isset($datapost['opcion_tipo_venta'])?'contado':'credito';
		$tipo_compra = ($tipo_compra == 'credito')?'credito':'contado';
		$monto_adeudado = !isset($datapost['txt_monto_adeudado'])?0:floatval($datapost['txt_monto_adeudado']) + 0;
		if($tipo_compra == 'credito' && $monto_adeudado > 0) {
			$new_orden_compra->id_condicionpago = $datapost['id_venta_al_credito'];
			$new_orden_compra->tipo_compra = 'credito';
			$new_orden_compra->monto_adeudado = $monto_adeudado;
			$new_orden_compra->monto_adeudado_inicial = $monto_adeudado;
			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				
			} else {
				$new_orden_compra->fecha_pagopendiente = date("Y-m-d", strtotime($fecha_pagopendiente));
			}
		} else {
			$new_orden_compra->id_condicionpago = $id_condicionpago;
			$new_orden_compra->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
			$new_orden_compra->tipo_compra = 'contado';
			$new_orden_compra->cpago_fechadeposito = !isset($datapost['fecha_deposito_transferencia'])?null:$datapost['fecha_deposito_transferencia'];
			$new_orden_compra->cpago_idbanco = !isset($datapost['idcuenta_banco_deposito'])?null:$datapost['idcuenta_banco_deposito'];
		}
        
		try {
			if(!$new_orden_compra->save()) {
				$msg = '';
				foreach ($new_orden_compra->getMessages() as $message) {
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
			$resp['mensaje'] = 'Error al registrar la Orden de Compra: '.$e->getMessage();
			return $resp;
		}
        
		$n = 0;
		foreach($lista_detalle as $item) {
			$n++;
			$new_detalle = new OrdenCompraDetalle();
			$new_detalle->id_contribuyente = $new_orden_compra->id_contribuyente;
            $new_detalle->id_tipodoc_electronico = $new_orden_compra->id_tipodoc_electronico;
            $new_detalle->serie_comprobante = $new_orden_compra->serie_comprobante;
            $new_detalle->numero_comprobante = $new_orden_compra->numero_comprobante;
            $new_detalle->tipo_envio_sunat = $new_orden_compra->tipo_envio_sunat;
            
			$new_detalle->id_unidad_medida = $item["UNIDAD_MEDIDA_ID_DET"];
			$new_detalle->unidad_medida = $item["UNIDAD_MEDIDA_DET"];
			$new_detalle->cantidad = $item["CANTIDAD_DET"];
			$new_detalle->precio = $item["PRECIO_DET"];
			$new_detalle->sub_total = $item["IMPORTE_DET"]; //se puede eliminar
			$new_detalle->importe = $item["IMPORTE_DET"];
			$new_detalle->id_codigoprecio = $item["PRECIO_TIPO_CODIGO"];
			$new_detalle->igv = $item["IGV_DET"];
			$new_detalle->isc = $item["ISC_DET"];
			$new_detalle->icbper = $item["ICBPER_DET"];
			$new_detalle->id_tipoafectacionigv = $item["COD_TIPO_OPERACION_DET"];
			$new_detalle->id_producto = $item["IDPRODUCTO_DET"];
			$new_detalle->codigo_producto = $item["CODIGO_PRODUCTO"];
			$new_detalle->descripcion = $item["DESCRIPCION_DET"];
			$new_detalle->precio_sin_igv = $item["PRECIO_SIN_IGV_DET"];

			$new_detalle->tipo_unidad = !isset($item["TIPO_UNIDAD"])?'UND':$item["TIPO_UNIDAD"];
			$new_detalle->id_presentacion = !isset($item["ID_PRESENTACION"])?null:intval($item["ID_PRESENTACION"]);

			try {
				if(!$new_detalle->save()) {
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
				$resp['mensaje'] = 'Error al registrar el detalle de la Orden de Compra: '.$e->getMessage();
				return $resp;
			}
		}
        
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Registro Exitoso';
		$resp['mensaje'] = 'Se ha Registrado la Orden de Compra correctamente!';
		$resp['orden_compra'] = array(
            'id_contribuyente'          => $new_orden_compra->id_contribuyente,
            'id_tipodoc_electronico'    => $new_orden_compra->id_tipodoc_electronico,
            'serie_comprobante'         => $new_orden_compra->serie_comprobante,
            'numero_comprobante'        => $new_orden_compra->numero_comprobante,
            'tipo_envio_sunat'          => $new_orden_compra->tipo_envio_sunat
        );
        $resp['enlaces'] = $this->get_enlaces_orden_compra($resp['orden_compra']);
		return $resp;
    }

    public function get_enlaces_orden_compra($orden_compra) {
		$orden_compra = (object) $orden_compra;
        $herramientas = new HerramientasController;

        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        $string_encrypted_document_a4 = $herramientas->encriptar("$orden_compra->id_contribuyente||$orden_compra->id_tipodoc_electronico||$orden_compra->serie_comprobante||$orden_compra->numero_comprobante||$orden_compra->tipo_envio_sunat||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$orden_compra->id_contribuyente||$orden_compra->id_tipodoc_electronico||$orden_compra->serie_comprobante||$orden_compra->numero_comprobante||$orden_compra->tipo_envio_sunat||ticket");

        $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
        
        $enlaces = array(
            'a4' => $url_a4,
            'ticket' => $url_ticket
        );

        return $enlaces;
    }

    public function get_serie_orden_compra($idsucursal) {
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $idsucursal)));
			
        return $sucursal->orden_compra_serie;
    }

    public function get_correlativo_orden_compra($id_contribuyente, $tipo_doc_electronico, $serie_comprobante, $tipo_envio_sunat, $idsucursal) {

		//RECORDAR: las sucursales no deben tener la misma serie, en caso tengan la misma serie se creará un problema en la numeración, ya que la llave primaria de cada documento está compuesto de: id_contribuyente, id_tipodoc_electronico, serie_comprobante y tipo_envio_sunat, no interviene el id de la sucursal. Se hizo de esta manera para evitar huecos en la numeración!

		$orden_compra = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_doc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "numero_comprobante DESC"));

		if(!$orden_compra) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $idsucursal)));
			
            return $sucursal->orden_compra_numero;
		}
		
		$numero_doc = intval($orden_compra->numero_comprobante) + 1;
		return $numero_doc;
    }
}
?>