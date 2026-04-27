<?php
class GestiondecomprasController extends ControllerBase
{

    public function indexAction() {
		$this->setTitle('Gestión de Compras');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
			->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
			->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/gestiondecompras/index.js?i=".rand());
    }

    public function registrarcompraAction($id_tipodoc_electronico = '01', $ruc_proveedor = '', $serie_doc = '', $correlativo_doc = '') {
		$this->setTitle('Registro o Edición de Compras');
        $this->view->setTemplateAfter('template_new');

        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.date.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.time.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/legacy.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/daterangepicker.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/anytime.min.js?i=v2")
			->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
			->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
			//->addJs($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.js?i=v2")
			->addJs($this->baseUri . "public/js/general.js?j=".rand())
			->addJs($this->baseUri . "public/js/numeros_a_letras.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondecompras/top_menu_tipo_document.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondecompras/proveedor_flexdatalist.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondecompras/registrarcompras.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondecompras/popup_agregar_item.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondecompras/interaccion_modalidadpago.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondecompras/registrar_producto.js?i=".rand()); 
		
		$this->assets
			->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
			->addCss($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.min.css")
			->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css")
			->addCss($this->baseUri . "public/css/new_style.css");

		$this->view->tipo_doc = $id_tipodoc_electronico;

		$auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
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

		$anio_actual =  date("Y");
        $icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
        if(!$icbper) {
            $impuesto_icbper = '0.5';
        } else {
            $impuesto_icbper = $icbper->monto;
		}

		$lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$this->view->lista_condiciones_pago = $lista_condiciones_pago;
        
		$this->view->impuesto_icbper = $impuesto_icbper;
		//$this->view->opciones_select = $opciones_select;
		$this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->numero_decimales = $contribuyente->num_decimales;
        $this->view->precio_venta_minimo = $contribuyente->precio_venta_minimo;
        $this->view->bienes_detracciones = SunatCodigodetraccion::find();

		

		//datos para buscar facturas de compra:
		$sunat_u_sol_principal = '';
		if(!empty($contribuyente->sunat_u_sol_principal)) {
			$sunat_u_sol_principal = 'Registrado!';
		}

		$sunat_p_sol_principal = '';
		if(!empty($contribuyente->sunat_p_sol_principal)) {
			$sunat_p_sol_principal = 'Registrado!';
		}

		$sunat_client_id = '';
		if(!empty($contribuyente->sunat_client_id)) {
			$sunat_client_id = 'Registrado!';
		}

		$sunat_client_secret = '';
		if(!empty($contribuyente->sunat_client_secret)) {
			$sunat_client_secret = 'Registrado!';
		}

		$this->view->usuario = $usuario;
		$this->view->sunat_u_sol_principal = $sunat_u_sol_principal;
		$this->view->sunat_p_sol_principal = $sunat_p_sol_principal;
		$this->view->sunat_client_id = $sunat_client_id;
		$this->view->sunat_client_secret = $sunat_client_secret;
		$this->view->ruc_proveedor = $ruc_proveedor;
		$this->view->serie_doc = $serie_doc;	
		$this->view->correlativo_doc = $correlativo_doc;
	}

	public function getTopProveedoresAction() {
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
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa no existe!';
                echo json_encode($resp);
                exit();
			}

			$query = "SELECT c.id_proveedor, COUNT(c.id_proveedor) total_compras, p.num_doc, p.razon_social FROM compra c INNER JOIN proveedor p on c.id_proveedor = p.id_proveedor WHERE c.id_proveedor <> $contribuyente->id_contribuyente and c.id_contribuyente = $contribuyente->id_contribuyente GROUP BY c.id_proveedor ORDER BY COUNT(c.id_proveedor) DESC limit 10";

			try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: getTopProveedores ',  $e->getMessage(), "\n";
                exit();
            }

			$n = 0;
			$array_lista = array();
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array('num_doc' => $fila->num_doc, 'razon_social' => $fila->razon_social, 'compras' => $fila->total_compras, 'texto' => $fila->num_doc.' - '.$fila->razon_social.' (N°Comp.: '.$fila->total_compras.')');
            }

            echo json_encode($array_lista);
            exit();
			
		}
	}

	public function guardarCompraAction() {
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
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa no existe!';
                echo json_encode($resp);
                exit();
            }

			$idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;

			$gestion_usuarios = new GestionuserController;
			if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_compras') != 'acceso_total') {
					if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_compras') != 'todas_las_sucursales') {
						if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_compras') == 'prohibir') {
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'Se ha bloqueado el registro de compras para su cuenta!';
							echo json_encode($resp);
							exit();
						}

						if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_compras') == 'sucursal_asignada') {
							if($usuario->idsucursal != $idsucursal) {
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = 'No Tiene Permisos para Registrar Compras en la Sucursal con ID: '.$idsucursal;
								echo json_encode($resp);
								exit();
							}
						}
					}
				}
			}

			$this->db->begin();

			$resp_validacion = $this->validaciones_iniciales($datapost);
			if($resp_validacion['respuesta'] == 'error') {
				$this->db->rollback();
				$resp_validacion['function'] = 'validaciones_iniciales';
				echo json_encode($resp_validacion);
				exit();
			}
			$datapost = $resp_validacion['datapost'];

			$resp_idproveedor = $this->get_id_proveedor($datapost);
			if($resp_idproveedor['respuesta'] == 'error') {
				$this->db->rollback();
				$resp_validacion['function'] = 'get_id_proveedor';
				echo json_encode($resp_idproveedor);
				exit();
			}

			$id_proveedor = $resp_idproveedor['id_proveedor'];
			$datapost['id_proveedor_documento'] = $resp_idproveedor['id_proveedor'];
			
			if($datapost['tipo_comprobante'] != '99') {

				$datapost['serie_comprobante'] = trim($datapost['serie_comprobante']);
				$datapost['numero_comprobante'] = preg_replace('/^0+/', '', trim($datapost['numero_comprobante']));

				$compra_guardada = Compra::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and id_proveedor = :id_proveedor: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $datapost['tipo_comprobante'], 'serie_comprobante' => strtoupper($datapost['serie_comprobante']), 'numero_comprobante' => $datapost['numero_comprobante'], 'id_proveedor' => $resp_idproveedor['id_proveedor'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat))); 
				if($compra_guardada) {
					$this->db->rollback();
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Comprobante: '.strtoupper($datapost['serie_comprobante']).'-'.$datapost['numero_comprobante'].', ya fué registrado previamente!';
					echo json_encode($resp);
					exit();
				}
	
				$resp_verify_doc_modifica = $this->verificar_doc_modificado($id_proveedor, $datapost, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat);
				if($resp_verify_doc_modifica['respuesta'] == 'error') {
					$this->db->rollback();
					$resp_verify_doc_modifica['function'] = 'verificar_doc_modificado';
					echo json_encode($resp_verify_doc_modifica);
					exit();
				}
	
				$datapost['id_compra_modificada'] = isset($resp_verify_doc_modifica['id_compra_modificada'])?$resp_verify_doc_modifica['id_compra_modificada']:'';
			}
			
			$confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$this->db->rollback();
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '';
				echo json_encode($resp);
				exit();
			}

			$resp_proceso_compra = $this->procesar_compra($contribuyente, $usuario, $id_proveedor, $datapost);
			if($resp_proceso_compra['respuesta'] == 'error') {
				$this->db->rollback();
				echo json_encode($resp_proceso_compra);
				exit();
			}

			$this->db->commit();
			echo json_encode($resp_proceso_compra);
			exit();
		}
	}

	public function procesar_compra($contribuyente, $usuario, $id_proveedor, $datapost) {
		$herramientas = new HerramientasController;

		$idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
		$detalle_documento = json_decode($datapost['detalle']);
		$resp_detalle = $this->validar_data_detalle($detalle_documento, $contribuyente->id_contribuyente, $idsucursal);
		if($resp_detalle['respuesta'] == 'error') {
			$resp_detalle['function'] = 'validar_data_detalle';
			return $resp_detalle;
		}

		$lista_detalle = $resp_detalle['detalle'];
	
		if($datapost['tipo_comprobante'] == '99') {

			$gestion_de_contribuyentes = new GestiondecontribuyentesController;
			if($gestion_de_contribuyentes->get_value_in_option_system($contribuyente->id_contribuyente, 'permitir_emision_ordenes_compra') == 'no') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'no tiene permitido emitir órdenes de compra!';
				return $resp;
			}
			
			$orden_compra = new OrdendecompraController;
			$resp_orden_compra = $orden_compra->crear_orden_de_compra($contribuyente, $usuario, $id_proveedor, $datapost, $lista_detalle, $idsucursal);
			return $resp_orden_compra;
		}

		$total = !isset($datapost['txt_total_comprobante'])?0:round(floatval($datapost['txt_total_comprobante']), 2);
		$total_igv = !isset($datapost['txt_igv_comprobante'])?0:round(floatval($datapost['txt_igv_comprobante']), 2);
		$sub_total = round($total - $total_igv, 2);

		$datapost['serie_comprobante'] = trim($datapost['serie_comprobante']);
		$datapost['numero_comprobante'] = preg_replace('/^0+/', '', trim($datapost['numero_comprobante']));

		$fecha_actual =  date('Y-m-d H:i:s');
		
		$new_compra = new Compra();
		
		$new_compra->id_contribuyente = $usuario->id_contribuyente;
		$new_compra->id_tipodoc_electronico = $datapost['tipo_comprobante'];
		$new_compra->idsucursal = $idsucursal;
		$new_compra->serie_comprobante = strtoupper($datapost['serie_comprobante']);
		$new_compra->numero_comprobante = $datapost['numero_comprobante'];
		$new_compra->total_gravadas = !isset($datapost['txt_gravada_comprobante'])?0:round(floatval($datapost['txt_gravada_comprobante']), 2); //id_tipoafectacionigv: 10
		$new_compra->total_inafecta = !isset($datapost['txt_inafecta_comprobante'])?0:round(floatval($datapost['txt_inafecta_comprobante']), 2); //id_tipoafectacionigv: 30
		$new_compra->total_exoneradas = !isset($datapost['txt_exonerada_comprobante'])?0:round(floatval($datapost['txt_exonerada_comprobante']), 2); //id_tipoafectacionigv: 20
		$new_compra->total_gratuitas = !isset($datapost['txt_gratuita_comprobante'])?0:round(floatval($datapost['txt_gratuita_comprobante']), 2); //id_tipoafectacionigv: todas las demás
		$new_compra->total_exportacion = !isset($datapost['txt_exportacion_comprobante'])?0:round(floatval($datapost['txt_exportacion_comprobante']), 2); //id_tipoafectacionigv: 40;
		$new_compra->total_icbper = !isset($datapost['txt_icbper_comprobante'])?0:round(floatval($datapost['txt_icbper_comprobante']), 2); //id_tipoafectacionigv: 40;
		$new_compra->total_descuento = !isset($datapost['txt_descuento_comprobante'])?0:round(floatval($datapost['txt_descuento_comprobante']), 2);
		$new_compra->porcentaje_descuento_total = !isset($datapost['txt_descuento_porcentaje'])?0:round(floatval($datapost['txt_descuento_porcentaje']), 2);
		$new_compra->sub_total = $sub_total;
		$new_compra->porcentaje_igv = "18.00";
		$new_compra->total_igv = $total_igv;
		$new_compra->total_isc = '0';
		$new_compra->total_otr_imp = '0';
		$new_compra->total = $total;
		$new_compra->nro_guia_remision = '';
		$new_compra->cod_guia_remision = '';
		$new_compra->nro_otr_comprobante = '';
		$new_compra->fecha_comprobante = date("Y-m-d", strtotime($datapost['fecha_comprobante']));
		$new_compra->id_codigomoneda = $datapost['codmoneda_comprobante'];
		$new_compra->tipo_cambio_sunat = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);
		$new_compra->id_proveedor = $id_proveedor;
		$new_compra->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$new_compra->id_usuario = $usuario->idusuario;
		$new_compra->fecha_registro = $fecha_actual;
		$new_compra->nota = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];
		$new_compra->estado = 'activo';

		//en caso la compra sea creada para un traslado:
		$new_compra->tipo = isset($datapost['tipo'])?$datapost['tipo']:null;
		$new_compra->idsucursal_origen = isset($datapost['idsucursal_origen'])?$datapost['idsucursal_origen']:null;
		$new_compra->idsucursal_destino = isset($datapost['idsucursal_destino'])?$datapost['idsucursal_destino']:null;
		$new_compra->id_contribuyente_origen = isset($datapost['id_contribuyente_origen'])?$datapost['id_contribuyente_origen']:null;
		$new_compra->id_tipodocumento_origen = isset($datapost['id_tipodocumento_origen'])?$datapost['id_tipodocumento_origen']:null;
		$new_compra->numero_comprobante_origen = isset($datapost['numero_comprobante_origen'])?$datapost['numero_comprobante_origen']:null;
		$new_compra->modalidad_origen = isset($datapost['modalidad_origen'])?$datapost['modalidad_origen']:null;

		//Tipo de venta y condición de pago
		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		$tipo_compra = !isset($datapost['opcion_tipo_venta'])?'contado':'credito';
		$tipo_compra = ($tipo_compra == 'credito')?'credito':'contado';
		$monto_adeudado = !isset($datapost['txt_monto_adeudado'])?0:floatval($datapost['txt_monto_adeudado']) + 0;
		if($tipo_compra == 'credito' && $monto_adeudado > 0) {
			$new_compra->id_condicionpago = $datapost['id_venta_al_credito'];
			$new_compra->tipo_compra = 'credito';
			$new_compra->monto_adeudado = $monto_adeudado;
			$new_compra->monto_adeudado_inicial = $monto_adeudado;
			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				
			} else {
				$new_compra->fecha_pagopendiente = date("Y-m-d", strtotime($fecha_pagopendiente));
			}
		} else {
			$new_compra->id_condicionpago = $id_condicionpago;
			$new_compra->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
			$new_compra->tipo_compra = 'contado';
			$new_compra->cpago_fechadeposito = !isset($datapost['fecha_deposito_transferencia'])?null:$datapost['fecha_deposito_transferencia'];
			$new_compra->cpago_idbanco = !isset($datapost['idcuenta_banco_deposito'])?null:$datapost['idcuenta_banco_deposito'];
		}
		//fin condicion de pago y tipo de venta

		//datos del documento modificado en caso sea una nota de crédito o débito
		if($datapost['tipo_comprobante'] == '07' || $datapost['tipo_comprobante'] == '08') {
			$id_motivo_nota_credito = null;
			$id_motivo_nota_debito = null;
			$motivo_nota_credito = null;
			$motivo_nota_debito = null;

			if($datapost['tipo_comprobante'] == '07') {
				$id_motivo_nota_credito = empty($datapost['id_motivo_nota_credito'])?'':$datapost['id_motivo_nota_credito'];

				$motivo_nota = SunatTiponotacredito::findFirst(array("id_tiponotacredito = :id_tiponotacredito:", 'bind' => array('id_tiponotacredito' => $id_motivo_nota_credito)));
				$motivo_nota_credito =$motivo_nota->descripcion;
			} else {
				$id_motivo_nota_debito = empty($datapost['id_motivo_nota_debito'])?'':$datapost['id_motivo_nota_debito'];
				$motivo_nota = SunatTiponotadebito::findFirst(array("id_tiponotadebito = :id_tiponotadebito:", 'bind' => array('id_tiponotadebito' => $id_motivo_nota_debito)));
				$motivo_nota_debito = $motivo_nota->descripcion;
			}

			$new_compra->id_tipo_comprobante_modifica = $datapost['tipo_doc_modifica'];
			$new_compra->serie_documento_modifica = $datapost['serie_doc_modifica'];
			$new_compra->nro_documento_modifica = $datapost['numero_doc_modifica'];
			$new_compra->id_cod_tipomotivo_credito = $id_motivo_nota_credito;
			$new_compra->descripcion_motivo_credito = $motivo_nota_credito;
			$new_compra->id_cod_tipomotivo_debito = $id_motivo_nota_debito;;
			$new_compra->descripcion_motivo_debito = $motivo_nota_debito;
			$new_compra->fecha_comprobante_modifica = date("Y-m-d", strtotime($datapost['fecha_doc_modifica']));
			$new_compra->id_compra_modifica = $datapost['id_compra_modificada'];
		}

		try {
			if(!$new_compra->save()) {
				$msg = '';
				foreach ($new_compra->getMessages() as $message) {
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
			$resp['mensaje'] = 'Error al guardar la compra: '.$e->getMessage();
			return $resp;
		}

		$array_motivos_modifican_stocks = array('01', '02', '06', '07', '10');
		$n = 0;
		foreach($lista_detalle as $item) {
			$n++;
			$new_detalle = new DetalleCompra();
			$new_detalle->id_compra = $new_compra->id_compra;
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
				$resp['mensaje'] = 'Error al guardar el detalle de la compra: '.$e->getMessage();
				return $resp;
			}

			if($datapost['tipo_comprobante'] == '07') {
				if(!in_array($id_motivo_nota_credito, $array_motivos_modifican_stocks)) {
					continue;
				}
			}

			$num_decimales = 2;
			if($contribuyente->num_decimales > 2) {
				$num_decimales = $contribuyente->num_decimales;
			}

			$cantidad_kardex = $item["CANTIDAD_DET"];
			$precio_item = $item["PRECIO_DET"];

			if(isset($item["ID_PRESENTACION"]) && intval($item["ID_PRESENTACION"]) > 0) {
				$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item["ID_PRESENTACION"]))));
				if($presentacion) {
					$cantidad_kardex = round($item["CANTIDAD_DET"]*$presentacion->cantidad_und_base, $num_decimales);
					if($presentacion->cantidad_und_base > 0) {
						$precio_item = round($precio_item/$presentacion->cantidad_und_base, $num_decimales);
					}
				}
			}

			//aquí descontamos el stock para cada uno de los productos en el detalle!
			$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item["IDPRODUCTO_DET"])));
			if($producto) {
				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
				if($unidad_medida) {
					if($unidad_medida->codigo != 'ZZ') {

						/* DATA PARA EL KARDEX */
						$data_kardex['id_contribuyente'] = $new_compra->id_contribuyente;
						$data_kardex['idsucursal'] = $new_compra->idsucursal;
						$data_kardex['idproducto'] = $producto->idproducto;
						$data_kardex['idusuario'] = $usuario->idusuario;
						$data_kardex['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat; //prueba, produccion
						$data_kardex['fecha_registro'] = $fecha_actual;

						/* datos del documento de referencia que afecta al kardex */
						$data_kardex['docref_id_contribuyente'] = $new_compra->id_contribuyente;
						$data_kardex['docref_id_tipodoc_electronico'] = $new_compra->id_tipodoc_electronico;
						$data_kardex['docref_serie_comprobante'] = $new_compra->serie_comprobante;
						$data_kardex['docref_numero_comprobante'] = $new_compra->numero_comprobante;
						$data_kardex['docref_tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
						$data_kardex['id_compra'] = $new_compra->id_compra;
						
						$data_kardex['estado_kardex'] = 'activo';
						
						if($datapost['tipo_comprobante'] == '01' || $datapost['tipo_comprobante'] == '03' || $datapost['tipo_comprobante'] == '08') {
							$data_kardex['costo_unitario'] = ($new_compra->id_codigomoneda=='USD')?(round($new_compra->tipo_cambio_sunat*$precio_item, 2)):$precio_item;
							$data_kardex['tipo_kardex'] = 'compra'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
							$data_kardex['detalle'] = 'Registro de Productos';
							$data_kardex['cantidad_entrada'] = $cantidad_kardex;
						} else if($datapost['tipo_comprobante'] == '00') {
							$data_kardex['costo_unitario'] = ($new_compra->id_codigomoneda=='USD')?(round($new_compra->tipo_cambio_sunat*$precio_item, 2)):$precio_item;
							$data_kardex['tipo_kardex'] = 'ingreso_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
							$data_kardex['detalle'] = 'Registro de Productos Sin Doc.Oficial';
							$data_kardex['cantidad_entrada'] = $cantidad_kardex;
						} else if($datapost['tipo_comprobante'] == '07') {
							//Si se registra una nota de crédito quiere decir que de alguna manera se está anulando una factura registrada previamente, en ese sentido es una SALIDA
							$data_kardex['tipo_kardex'] = 'devolucion'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
							$data_kardex['detalle'] = 'Registro de Productos';
							$data_kardex['cantidad_salida'] = $cantidad_kardex;
						}
						
						$kardex = new KardexController;
						$resp_kardex = $kardex->registrar_en_kardex($data_kardex);
						if($resp_kardex['respuesta'] == 'error') {
							$resp_kardex['function'] = 'registrar_en_kardex';
							return $resp_kardex;
						}

						$producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
						
						if($datapost['tipo_comprobante'] == '00' || $datapost['tipo_comprobante'] == '01' || $datapost['tipo_comprobante'] == '03' || $datapost['tipo_comprobante'] == '08') {
							$producto->stock = $producto->stock + $cantidad_kardex;
						} else if($datapost['tipo_comprobante'] == '07') {
							$nuevo_stock = $producto->stock - $cantidad_kardex;
							//if($nuevo_stock < 0) { $nuevo_stock = 0; }
							$producto->stock = $nuevo_stock;
						} else {
							$producto->stock = $producto->stock;
						}

						//modificando el precio de compra:
						if($producto->id_cod_moneda == 'USD') {
							if($new_compra->id_codigomoneda == 'USD') {
								$producto->precio_compra = $precio_item;
							} else {
								//la compra es en soles.
								$producto->precio_compra = round($precio_item/$new_compra->tipo_cambio_sunat, 2);
							}
						} else {
							//el producto en la base de datos está en soles.
							if($new_compra->id_codigomoneda == 'USD') {
								//la compra es en dólares.
								$producto->precio_compra = round($new_compra->tipo_cambio_sunat*$precio_item, 2);
							} else {
								//la compra es en soles
								$producto->precio_compra = $precio_item;
							}
						}

						if($item['modificar_precio_venta'] == 'si') {
							$pventa_minimo_nuevo = floatval($item['pventa_minimo_nuevo']);
							$pventa_nuevo = floatval($item['pventa_nuevo']);

							$resp_porcentajes = $herramientas->get_porcentajes_ganancia(floatval($precio_item), $pventa_nuevo, $pventa_minimo_nuevo);
							$producto->porcentaje_pventa = $resp_porcentajes['porcentaje_maximo'];
							$producto->porcentaje_pminimo = $resp_porcentajes['porcentaje_minimo'];
							$producto->precio_venta_minimo = $pventa_minimo_nuevo;


							if($item["COD_TIPO_OPERACION_DET"] == 10 || $item["COD_TIPO_OPERACION_DET"] == 7152) {
								$producto->valor_sin_igv = round($pventa_nuevo/1.18, $num_decimales); //$valor_sin_igv
								$producto->valor_con_igv = $pventa_nuevo;
							} else {
								$producto->valor_sin_igv = $pventa_nuevo; //$valor_sin_igv
								$producto->valor_con_igv = round($pventa_nuevo*1.18, $num_decimales);
							}
						}
						
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
							$resp['mensaje'] = 'Error al guardar el producto: '.$e->getMessage();
							return $resp;
						}
					}
				}
			}
		}

		if($tipo_compra == 'credito' && $monto_adeudado > 0) {
			if($total - $monto_adeudado > 0) {
				$monto_pagado = $total - $monto_adeudado;
				$new_montopagado = new MontoPagado();
				$new_montopagado->id_compra = $new_compra->id_compra;
				$new_montopagado->id_contribuyente  = $usuario->id_contribuyente;
				$new_montopagado->id_tipodoc_electronico = $datapost['tipo_comprobante'];
				$new_montopagado->serie_comprobante = strtoupper($datapost['serie_comprobante']);
				$new_montopagado->numero_comprobante = $datapost['numero_comprobante'];
				$new_montopagado->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$new_montopagado->id_usuario = $usuario->idusuario;
				$new_montopagado->id_sucursal = $idsucursal;
				$new_montopagado->id_condicionpago = $datapost['condicion_pago_parcial'];
				$new_montopagado->id_codigomoneda =  $datapost['codmoneda_comprobante'];
				$new_montopagado->total = $monto_pagado;
				$new_montopagado->cpago_nrooperacion = !isset($datapost['txt_numero_operacion'])?'':$datapost['txt_numero_operacion'];
				$new_montopagado->fecha_registro = date('Y-m-d H:i:s');
				$new_montopagado->detalle = !isset($datapost['observacion_documento'])?'':$datapost['observacion_documento'];
				$new_montopagado->estado = 'activo';
				$new_montopagado->fechadeposito = !isset($datapost['fecha_deposito_transferencia'])?null:$datapost['fecha_deposito_transferencia'];
				$new_montopagado->idbanco = !isset($datapost['idcuenta_banco_deposito'])?null:$datapost['idcuenta_banco_deposito'];

				try {
					if(!$new_montopagado->save()) {
						$msg = '';
						foreach ($new_montopagado->getMessages() as $message) {
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
					$resp['mensaje'] = 'Error al guardar el monto pagado: '.$e->getMessage();
					return $resp;
				}
			}	
		}
		
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Registro Exitoso';
		$resp['mensaje'] = 'Se ha procesado la compra correctamente!';
		$resp['id_compra'] = $new_compra->id_compra;
		return $resp;
	}

	public function verificar_doc_modificado($id_proveedor, $datapost, $id_contribuyente, $tipo_envio_sunat) {
		$tipo_comprobante_compra = $datapost['tipo_comprobante'];
		if($tipo_comprobante_compra == '01' || $tipo_comprobante_compra == '03' || $tipo_comprobante_compra == '00') {
			$resp['respuesta'] = 'ok';
			$resp['id_compra_modificada'] = null;
			return $resp;
		}

		$compra_a_modificar = Compra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_proveedor = :id_proveedor:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $datapost['tipo_doc_modifica'], 'serie_comprobante' => trim($datapost['serie_doc_modifica']), 'numero_comprobante' => trim($datapost['numero_doc_modifica']), 'tipo_envio_sunat' => $tipo_envio_sunat, 'id_proveedor' => $id_proveedor)));

		if(!$compra_a_modificar) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Documento que se modificará aún no ha sido registrado en la base de datos!';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['id_compra_modificada'] = $compra_a_modificar->id_compra;
		return $resp;
	}

	public function validar_data_detalle($detalle_documento, $id_contribuyente, $id_sucursal) {
		if(!is_array($detalle_documento)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
			return $resp;
		}

		if(count($detalle_documento) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
			return $resp;
		}

		$lista = array();
		$n = 0;
		foreach($detalle_documento as $item) {
			$n++;

			$producto_idproducto = intval($item->idarticulo) + 0;
			$producto_codigo = $n.'GET_CPE';
			if($producto_idproducto > 0) {
				//se debe validar el código del producto por sucursal, ya que caso contrario ocasionará que se creen registros en el kardex con el id del producto y un idde sucursal a la que no pertenece
				$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $producto_idproducto, 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $id_sucursal)));
				if(!$producto) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = "El item N° $n con nombre: <strong>$item->descripcion</strong> no existe en la sucursal seleccionada!";
					return $resp;
				}

				$producto_idproducto = $item->idarticulo;
				$producto_codigo = $producto->codigo;
			} else {
				$producto_idproducto = 0;
				$producto_codigo = $n.'GET_CPE';	
			}

			$idunidad_medida = str_replace('UND-', '', $item->idunidadmedida);
			$idunidad_medida = intval($idunidad_medida);
			$tipo_unidad_medida = 'UND';
			$id_presentacion_producto = null;
			if (strpos($item->idunidadmedida, '-') !== false) {
				//existe un guión
				$array_unidad_medida = explode('-', $item->idunidadmedida);
				if(count($array_unidad_medida) > 1) {
					$tipo_unidad_medida = ($array_unidad_medida[0] == 'PRE')?'PRE':'UND'; //PRE: presentacion, UND: unidad_medida

					if($tipo_unidad_medida == 'PRE') {
						//validar si existe la presentación
						$id_presentacion_producto = $item->idpresentacion;
						$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion: and estado = 'activo'", 'bind' => array('id_presentacion' => $id_presentacion_producto)));
						if(!$presentacion) {
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = "En el item N° $n para el producto: $item->descripcion, usted ha seleccionado una unidad inválida!";
							return $resp;
						}

						$idunidad_medida = intval($presentacion->idunidad);
					} else {
						$idunidad_medida = intval($array_unidad_medida[1]);
					}
				}
			}

			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad_medida)));
			if(!$unidadmedida) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La unidad seleccionada para el producto: '.$item->descripcion.' no es correcta. (IdUnidad: '.$item->idunidadmedida.')';
				return $resp;
			}

			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			if(!$tipoafectacionigv) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto: '.$item->descripcion;
				return $resp;
			}

			if($item->cantidad <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La cantidad para el Item N° '.$n.' debe ser diferente de cero.';
				return $resp;
			}

			$icbper_det = 0;
			if($tipoafectacionigv->id_tipoafectacionigv == 7152) {
				$icbper_det = $item->cantidad*0.10;
			}

			$modificar_precio_venta = isset($item->modificar_precio_venta)?$item->modificar_precio_venta:'no';
			$pventa_nuevo = 0;
			$pventa_minimo_nuevo = 0;
			if($modificar_precio_venta == 'si') {
				$pventa_nuevo = floatval($item->pventa_nuevo) + 0;
				$pventa_minimo_nuevo = floatval($item->pventa_minimo_nuevo) + 0;

				if($pventa_nuevo <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Usted ha seleccionado la opción de modificar el precio de venta para el item N° '.$n.', por tanto el nuevo precio de venta debería ser mayor a cero.';
					return $resp;
				}

				if($pventa_minimo_nuevo <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Usted ha seleccionado la opción de modificar el precio de venta para el item N° '.$n.', por tanto el nuevo precio mínimo debería ser mayor a cero.';
					return $resp;
				}

				if($pventa_nuevo <= $pventa_minimo_nuevo) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Usted ha seleccionado la opción de modificar el precio de venta para el item N° '.$n.', por tanto el precio de venta nuevo NO PUEDE ser mayor al precio mínimo.';
					return $resp;
				}
			}
			
			//NOTA PARA EL DESARROLLADOR:
			//Recordar que la función de procesar venta también se utiliza para registrar traslados.
			$lista[] = array(
				"ITEM_DET"          	 	=> $n,
				"UNIDAD_MEDIDA_ID_DET"   	=> $unidadmedida->idunidad,
				"UNIDAD_MEDIDA_DET"      	=> $unidadmedida->codigo,
				"PRECIO_TIPO_CODIGO"     	=> "01", //Precio unitario (incluye el IGV) - catálogo N° 16
				"COD_TIPO_OPERACION_DET" 	=> $tipoafectacionigv->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
				"TEXT_TIPO_OPERACION_DET" 	=> $tipoafectacionigv->descripcion, //text_tipoafectacionigv - CATALOGO No. 07
				"CANTIDAD_DET"           	=> $item->cantidad,
				"PRECIO_DET"             	=> $item->precio,
				"IGV_DET"                	=> $item->igv,
				"ICBPER_DET"			 	=> isset($item->subtotal_icbper)?$item->subtotal_icbper:0,
				"ISC_DET"					=> "0",
				"IMPORTE_DET"            	=> $item->subtotal,
				"PRECIO_SIN_IGV_DET"  	 	=> round(round($item->subtotal, 2)/round($item->cantidad, 2), 2),
				"CODIGO_DET"             	=> $producto_idproducto,
				"CODIGO_PRODUCTO"		 	=> $producto_codigo,
				"DESCRIPCION_DET"   	 	=> trim($item->descripcion),
				"IDPRODUCTO_DET"		 	=> $producto_idproducto,

				"TIPO_UNIDAD"				=> $tipo_unidad_medida,
				"ID_PRESENTACION"			=> $id_presentacion_producto,

				"modificar_precio_venta" 	=> $modificar_precio_venta,
				"pventa_nuevo"				=> $pventa_nuevo,
				"pventa_minimo_nuevo"		=> $pventa_minimo_nuevo,
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista;
		return $resp;
	}

	public function validaciones_iniciales($datapost) {
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
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
			$resp['mensaje'] = 'No se encuentra el contribuyente al que pertenece el usuario!';
			return $resp;
		}

		$herramientas = new HerramientasController;

		$idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
			return $resp;
		}

		$tipo_comprobante_compra = $datapost['tipo_comprobante'];
		$tipos_validos_comprobante = array('01', '03', '07', '08', '00', '99');
		if(!in_array($tipo_comprobante_compra, $tipos_validos_comprobante)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Comprobante';
			$resp['mensaje'] = 'El Tipo de Comprobante Seleccionado no es Válido';
			return $resp;
		}
		
		$cod_moneda = trim($datapost['codmoneda_comprobante']);
		$tipo_cambio = floatval($datapost['tipo_cambio_comprobante']);

		if($tipo_comprobante_compra != '99') {

			$serie_comprobante = trim($datapost['serie_comprobante']);
			$numero_comprobante = trim($datapost['numero_comprobante']);

			if(empty($serie_comprobante)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes Ingresar una Serie Válida';
				return $resp;
			}
	
			if(empty($numero_comprobante)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes Ingresar un Número Válido';
				return $resp;
			}
		}
	
		$id_codigomoneda = $datapost['codmoneda_comprobante'];
		$moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $id_codigomoneda)));
		if(!$moneda) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes seleccionar el código de moneda válido!';
			return $resp;
		}

		if($id_codigomoneda == 'USD') {
			$tipo_cambio_sunat = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);
			if($tipo_cambio_sunat <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El Tipo de Cambio no puede ser Cero o Menor que Cero!';
				return $resp;
			}
		}

		$array_fecha_documento = explode('/',!isset($datapost['fecha_comprobante'])?date('d/m/Y'):$datapost['fecha_comprobante']);
		$fecha_comprobante = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
		if (!(DateTime::createFromFormat('Y-m-d', $fecha_comprobante) !== FALSE)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha asignada al documento no es válida!';
			return $resp;
		}
		
		$fecha_comprobante_2 = new DateTime($fecha_comprobante);
		$fecha_actual = new DateTime();
		
		if($fecha_comprobante_2 > $fecha_actual) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La Fecha Seleccionada no es Válida!. Recuerda seleccionar la fecha del documento de compra, no es posible seleccionar una fecha futura!';
			return $resp;
		}
		
		$datapost['fecha_comprobante'] = $fecha_comprobante;
		
		$id_tipo_doc_proveedor = trim($datapost['proveedor_tipo_docidentidad']);
		$array_tipodoc_proveedor_validos = array('0', '1', '6');
		if(!in_array($id_tipo_doc_proveedor, $array_tipodoc_proveedor_validos)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Proveedor';
			$resp['mensaje'] = 'El Tipo de Documento de Identidad Seleccionado para el Proveedor no es Válido!';
			return $resp;
		}

		$numero_doc_proveedor = $this->limpia_espacios($datapost['proveedor_numerodocumento']);

		if($tipo_comprobante_compra == '01' || $tipo_comprobante_compra == '03' || $tipo_comprobante_compra == '07' || $tipo_comprobante_compra == '08') {
			//si es boleta o factura entonces el proveedor debe tener un RUC
			if($id_tipo_doc_proveedor != '6') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Proveedor';
				$resp['mensaje'] = 'Usted Debe Seleccionar la Opción de RUC y Luego Ingresar un RUC Válido!';
				return $resp;
			}

			if(strlen($numero_doc_proveedor) != 11) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Proveedor';
				$resp['mensaje'] = 'Debes Ingresar un Número de RUC Válido!';
				return $resp;
			}
		} else {
			//es otro documento, difente a factura o boleta, entonces puede que el proveedor tenga ruc, dni, u otro documento.
			if($id_tipo_doc_proveedor == '6') {
				if(strlen($numero_doc_proveedor) != 11) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Proveedor';
					$resp['mensaje'] = 'Debes Ingresar un Número de RUC Válido para el Proveedor!';
					return $resp;
				}
			}

			if($id_tipo_doc_proveedor == '1') {
				if(strlen($numero_doc_proveedor) != 8) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Proveedor';
					$resp['mensaje'] = 'Debes Ingresar un Número de DNI Válido para el Proveedor!';
					return $resp;
				}
			}

			if($id_tipo_doc_proveedor == '0') {
				if(strlen($numero_doc_proveedor) < 2) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Proveedor';
					$resp['mensaje'] = 'El Número de Documento de Identidad para el Proveedor debe ser Mayor a 2 Dígitos!';
					return $resp;
				}
			}
		} 

		//Si se trata de una nota de crédito, debemos validar que antes se haya registrado una boleta o factura que intenta modificar
		$text_motivo_nota = '';
		if($tipo_comprobante_compra == '07' || $tipo_comprobante_compra == '08') {
			$tipo_doc_modifica = empty($datapost['tipo_doc_modifica'])?'':$datapost['tipo_doc_modifica'];
			$serie_doc_modifica = empty($datapost['serie_doc_modifica'])?'':$datapost['serie_doc_modifica'];
			$numero_doc_modifica = empty($datapost['numero_doc_modifica'])?'':$datapost['numero_doc_modifica'];
			$id_motivo_nota_credito = empty($datapost['id_motivo_nota_credito'])?'':$datapost['id_motivo_nota_credito'];
			$id_motivo_nota_debito = empty($datapost['id_motivo_nota_debito'])?'':$datapost['id_motivo_nota_debito'];
			$fecha_doc_modifica = empty($datapost['fecha_doc_modifica'])?'':$datapost['fecha_doc_modifica'];

			$tipos_validos_comprobante_a_modific = array('01', '03');
			if(!in_array($tipo_doc_modifica, $tipos_validos_comprobante_a_modific)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Comprobante';
				$resp['mensaje'] = 'Si va a crear una nota de crédito debe selecionar una factura o boleta para modificar!.';
				return $resp;
			}

			if($tipo_comprobante_compra == '07') {
				$motivo_nota_credito = SunatTiponotacredito::findFirst(array("id_tiponotacredito = :id_tiponotacredito:", 'bind' => array('id_tiponotacredito' => $id_motivo_nota_credito)));
				if(!$motivo_nota_credito) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Motivo Seleccionado para la Nota de Crédito no es Válido';
					return $resp;
				}
				$text_motivo_nota = $motivo_nota_credito->descripcion;
			} else {
				$motivo_nota_debito = SunatTiponotadebito::findFirst(array("id_tiponotadebito = :id_tiponotadebito:", 'bind' => array('id_tiponotadebito' => $id_motivo_nota_debito)));
				if(!$motivo_nota_debito) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Motivo Seleccionado para la Nota de Débito no es Válido';
					return $resp;
				}

				$text_motivo_nota = $motivo_nota_debito->descripcion;
			}
			

			$array_fecha_documento_modifica = explode('/',!isset($datapost['fecha_doc_modifica'])?'31-31-31':$datapost['fecha_doc_modifica']);
			$fecha_doc_modifica = $array_fecha_documento_modifica[2].'-'.$array_fecha_documento_modifica[1].'-'.$array_fecha_documento_modifica[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_doc_modifica) !== FALSE)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar la fecha en que fué generado el documento que modificará la presente Nota de Crédito!';
				return $resp;
			}
			$datapost['fecha_doc_modifica'] = $fecha_doc_modifica;
			
			/*
			if($id_motivo_nota_credito == '04' || $id_motivo_nota_credito == '05' || $id_motivo_nota_credito == '08') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El motivo seleccionado no es válido para emitir una nota de Boleta!';
				return $resp;
			}
			*/

			if(empty($serie_doc_modifica)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes Ingresar la Serie del Documento que está modificando la presente Nota de Crédito!';
				return $resp;
			}

			if(empty($numero_doc_modifica)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes Ingresar el Numero del documento que está modificando la presente nota de crédito!';
				return $resp;
			}
		}
		
		$detalle_documento = json_decode($datapost['detalle']);
		if(!is_array($detalle_documento)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
			return $resp;
		}
		
		if(count($detalle_documento) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
			return $resp;
		}

		$porcentaje_descuento = floatval($datapost['txt_descuento_porcentaje']);
		if($porcentaje_descuento < 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Porcentaje de Descuento no es Válido!';
			return $resp;
		}

		//VERIFICACIÓN DE CONDICIÓN DE PAGO
		//verificamos el tipo de pago y la condicion de pago
		$tipo_compra = !isset($datapost['opcion_tipo_venta'])?'contado':(($datapost['opcion_tipo_venta'] == 'contado')?'contado':'credito');
		$monto_adeudado = !isset($datapost['txt_monto_adeudado'])?0: floatval($datapost['txt_monto_adeudado']) + 0;
		$monto_pagado = !isset($datapost['txt_pago_parcial'])?0: floatval($datapost['txt_pago_parcial']) + 0;
		$total_comprobante = !isset($datapost['txt_total_comprobante'])?0: floatval($datapost['txt_total_comprobante']) + 0;
		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		$id_venta_al_credito = 0;
		$id_condicionpago_montopagado = 0; //el id de la condición en caso haya un monto pagado junto a la venta al crédito.

		if($tipo_compra == 'credito') {
			if($monto_adeudado <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Si el comprobante electrónico se pagará al crédito, entonces el monto adeudado no puede ser igual o menor a cero.';
				return $resp;
			}

			if($monto_adeudado > $total_comprobante) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El monto adeudado no debería ser mayor al monto total del comprobante.';
				return $resp;
			}

			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
				return $resp;
			}

			$resp_fecha_2 = $herramientas->comparar_fechas($fecha_pagopendiente, date('Y-m-d'));
			if($resp_fecha_2['diferencia_primera_segunda'] < 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto adeudado debe ser igual o mayor a la fecha actual, por favor verifique!';
				return $resp;
			}

			$condicion_venta_credito = Condiciondepago::findFirst(array("tipo = 'credito' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
			
			if(!$condicion_venta_credito) {
				$condicion_venta_credito = new Condiciondepago();
				$condicion_venta_credito->id_contribuyente = $contribuyente->id_contribuyente;
				$condicion_venta_credito->tipo = 'credito';
				$condicion_venta_credito->condicionpago = 'Venta al Crédito';
				$condicion_venta_credito->estado = 'activo';

				try{
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
					$resp['mensaje'] = 'No tienes permitido crear ventas al crédito!';
					return $resp;
				}
			}
			
			$id_venta_al_credito = $condicion_venta_credito->id_condicionpago;

			if($total_comprobante - $monto_adeudado > 0) { //si el total del comprobante menos lo adeudado es mayor a cero, entonces existe un pago parcial
				if($id_condicionpago <= 0) {
					$condicion_pago_parcial = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and tipo = 'contado' and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
					if($condicion_pago_parcial) {
						$id_condicionpago = $condicion_pago_parcial->id_condicionpago;
					}
				}

				$condicion_pago_parcial = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente: and tipo <> 'credito' and estado = 'activo'", 'bind' => array('id_condicionpago' => $id_condicionpago, 'id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
				if(!$condicion_pago_parcial) {
					$condicion_pago_parcial = new Condiciondepago();
					$condicion_pago_parcial->id_contribuyente = $contribuyente->id_contribuyente;
					$condicion_pago_parcial->tipo = 'contado';
					$condicion_pago_parcial->condicionpago = 'Pago en Efectivo';
					$condicion_pago_parcial->estado = 'activo';

					try {
						if(!$condicion_pago_parcial->save()) {
							$msg = '';
							foreach ($condicion_pago_parcial->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'problemas al registrar la condición de pago al crédito!';
							return $resp;
						}
					} catch (Exception $e) {
                		$this->saveLogger($e);
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'problemas al registrar la condición de pago al crédito! '.$e->getMessage();
						return $resp;
					}
				}

				$id_condicionpago = $condicion_pago_parcial->id_condicionpago;
			}
		}
		
		$idcuenta_banco_deposito = null;
		$fecha_deposito_transferencia = null;
		if($id_condicionpago > 0) {
			$condiciondepago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago: and tipo <> 'credito'", "bind" => array('id_contribuyente' => $usuario->id_contribuyente, 'id_condicionpago' => $id_condicionpago)));
			if(!$condiciondepago) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La condición de pago seleccionada no es válida!';
				return $resp;
			}

			if($condiciondepago->tipo == 'transferencia') {
				if(intval($datapost['select_cuenta_banco_deposito']) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($datapost['select_cuenta_banco_deposito']))));
					if(!$cuenta_banco_transferencia) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'La cuenta de banco de transferencia no existe, debes seleccionar una cuenta de banco en donde se ha depositado el dinero!!';
						return $resp;
					}

					$idcuenta_banco_deposito = intval($datapost['select_cuenta_banco_deposito']);
				}

				$array_fecha_deposito = explode('/',!isset($datapost['fecha_deposito'])?date('d/m/Y'):$datapost['fecha_deposito']);
				$fecha_deposito_transferencia = $array_fecha_deposito[2].'-'.$array_fecha_deposito[1].'-'.$array_fecha_deposito[0];
				if (!(DateTime::createFromFormat('Y-m-d', $fecha_deposito_transferencia) !== FALSE)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
					return $resp;
				}
			}
		}
		//FIN CONDICIÓN DE PAGO
		
		$datapost['id_venta_al_credito'] = $id_venta_al_credito;
		$datapost['txt_monto_adeudado'] = $monto_adeudado;
		$datapost['condicion_pago_parcial'] = $id_condicionpago;
		$datapost['idcuenta_banco_deposito'] = $idcuenta_banco_deposito;
		$datapost['fecha_deposito_transferencia'] = $fecha_deposito_transferencia;

		$resp['respuesta'] = 'ok';
		$resp['datapost'] = $datapost;
		return $resp;
	}

	public function get_id_proveedor($datapost) {
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			return $resp;
		}

		//no hace falta hacer una validación de tipos, porque anteriormente se debió utilizar la función: validaciones iniciales
		$tipo_comprobante_compra = $datapost['tipo_comprobante'];
		$id_tipo_doc_proveedor = trim($datapost['proveedor_tipo_docidentidad']);

		$numero_doc_proveedor = $this->limpia_espacios($datapost['proveedor_numerodocumento']);

		if($tipo_comprobante_compra == '01' || $tipo_comprobante_compra == '03' || $tipo_comprobante_compra == '07' || $tipo_comprobante_compra == '08') {
			//si es boleta o factura entonces el proveedor debe tener un RUC
			if($id_tipo_doc_proveedor != '6') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Proveedor';
				$resp['mensaje'] = 'Usted Debe Seleccionar la Opción de RUC y Luego Ingresar un RUC Válido!';
				return $resp;
			}

			if(strlen($numero_doc_proveedor) != 11) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Proveedor';
				$resp['mensaje'] = 'Debes Ingresar un Número de RUC Válido!';
				return $resp;
			}
		} else {
			//es otro documento, difente a factura o boleta, entonces puede que el proveedor tenga ruc, dni, u otro documento.
			if($id_tipo_doc_proveedor == '6') {
				if(strlen($numero_doc_proveedor) != 11) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Proveedor';
					$resp['mensaje'] = 'Debes Ingresar un Número de RUC Válido para el Proveedor!';
					return $resp;
				}
			}

			if($id_tipo_doc_proveedor == '1') {
				if(strlen($numero_doc_proveedor) != 8) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Proveedor';
					$resp['mensaje'] = 'Debes Ingresar un Número de DNI Válido para el Proveedor!';
					return $resp;
				}
			}

			if($id_tipo_doc_proveedor == '0') {
				if(strlen($numero_doc_proveedor) < 2) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Proveedor';
					$resp['mensaje'] = 'El Número de Documento de Identidad para el Proveedor debe ser Mayor a 2 Dígitos!';
					return $resp;
				}
			}
		}

		$proveedor = Proveedor::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('num_doc' => $numero_doc_proveedor, 'id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocidentidad' => $id_tipo_doc_proveedor)));
		if($proveedor) {
			$resp['respuesta'] = 'ok';
			$resp['id_proveedor'] = $proveedor->id_proveedor;
			return $resp;
		}
		
		$id_proveedor = !isset($datapost['id_proveedor_documento'])?0:intval($datapost['id_proveedor_documento']) + 0;
		if($id_proveedor > 0) {
			$proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor: and num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_proveedor' => $id_proveedor, 'num_doc' => $numero_doc_proveedor, 'id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocidentidad' => $id_tipo_doc_proveedor)));
			if($proveedor) {
				$resp['respuesta'] = 'ok';
				$resp['id_proveedor'] = $proveedor->id_proveedor;
				return $resp;
			}	
		}
		
		$proveedor_nombre = trim($datapost['proveedor_nombre']);
		if(empty($proveedor_nombre)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Proveedor';
			$resp['mensaje'] = 'Debes Ingresar la Razón Social de tu Proveedor!';
			return $resp;
		}

		$proveedor_direccion = trim($datapost['proveedor_direccion']);

		$codigo_ubigeo = !isset($datapost['select_codigoubigeo'])?'':$datapost['select_codigoubigeo'];
		if($codigo_ubigeo != '') {
			$tipo_documento_identidad = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $codigo_ubigeo)));
			if(!$codigo_ubigeo) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Usted ha seleccionado un código de ubigeo inválido!';
				return $resp;
			}
		}

		$proveedor = new Proveedor();
		$proveedor->id_contribuyente = $usuario->id_contribuyente;
		$proveedor->id_tipodocidentidad = $id_tipo_doc_proveedor;
		$proveedor->codigo = uniqid('PROV_'.$usuario->id_contribuyente,true);;
		$proveedor->num_doc = $numero_doc_proveedor;
		$proveedor->razon_social = $proveedor_nombre;
		$proveedor->direccion_fiscal = $proveedor_direccion;
		$proveedor->id_cod_ubigeo = $codigo_ubigeo;
		$proveedor->fecha_registro = date('Y-m-d H:i:s');
		$proveedor->estado = 'activo';

		try {
			if(!$proveedor->save()) {
				$msg = '';
				foreach ($proveedor->getMessages() as $message) {
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
			$resp['mensaje'] = 'No se pudo registrar al proveedor, por favor intente nuevamente!';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['id_proveedor'] = $proveedor->id_proveedor;
		return $resp;
	}

	public function limpia_espacios($cadena){
		$cadena = trim($cadena);
		$cadena = preg_replace('[\s+]',"", $cadena);
		$cadena2 = '';
		for ($i=0; $i<strlen($cadena); $i++) {
			if (is_numeric($cadena[$i])) {$cadena2.=$cadena[$i];}
		}
		return $cadena2;
	}

	public function getListaComprasAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
			}
			
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            $fecha_actual = date("Y-m-d H:i:s");
			$fecha_inicio_b = date("Y-m-d", strtotime("-290 days", strtotime($fecha_actual)));
            $fecha_fin_b = date("Y-m-d");
            $fecha_inicio = empty($datapost['fecha_inicio'])?$fecha_inicio_b:date("Y-m-d", strtotime($datapost['fecha_inicio']));
            $fecha_fin = empty($datapost['fecha_fin'])?$fecha_fin_b:date("Y-m-d", strtotime($datapost['fecha_fin']));
            $id_sucursal = empty($datapost['id_sucursal'])?0:intval($datapost['id_sucursal']);
			$id_vendedor = empty($datapost['id_vendedor'])?0:intval($datapost['id_vendedor']);
			$tipo_compras = empty($datapost['tipo_compras'])?'docs_compra':$datapost['tipo_compras'];
			$tipos_comprobantes = empty($datapost['tipo_comprobantes'])?array():$datapost['tipo_comprobantes'];
			$tipos_comprobantes_validos = array('03', '01', '07', '08', '00', '99');
			$select_tipofecha_busqueda = empty($datapost['select_tipofecha_busqueda'])?'fecha_documento':$datapost['select_tipofecha_busqueda'];

			if(!is_array($tipos_comprobantes)) {
				$tipos_comprobantes = array();
			}

			if(in_array('-', $tipos_comprobantes)) {
				$tipos_comprobantes = array();
			}

			$tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

            $herramientas = new HerramientasController;
            if(!$herramientas->validate_date($fecha_inicio)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }
            if(!$herramientas->validate_date($fecha_fin)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }
            
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			
			$tipos_documentos_sql = '';
			$tipo_filtro_fecha = 'fecha_comprobante';
			if($select_tipofecha_busqueda == 'fecha_registro') {
				$tipo_filtro_fecha = 'fecha_registro';
			}
			
			if($tipo_compras == 'ordenes_compra') {
				if(count($tipos_comprobantes) > 0) {
					if(!in_array('99', $tipos_comprobantes)) {
						$json_data = array(
							"draw"            => 0,
							"recordsTotal"    => 0,  //Número total de registros que cumplen con los criterios iniciales
							"recordsFiltered" => 0, //Total registros filtrados
							"data"            => array(),   // total data array
							"numero_n"        => 0
						);
			
						echo json_encode($json_data);
						exit();
					}
				}

				$data_consulta['id_contribuyente'] = $contribuyente->id_contribuyente;
				$data_consulta['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
				$data_consulta['fecha_inicio'] = $fecha_inicio;
				$data_consulta['fecha_fin'] = $fecha_fin;
				$data_consulta['id_sucursal'] = $id_sucursal;
				$data_consulta['tipo_filtro_fecha'] = $tipo_filtro_fecha;
				$data_consulta['id_vendedor'] = $id_vendedor;

				$resp_reporte_orden_compra = $this->get_lista_orden_compra($data_consulta, $datapost);
				echo json_encode($resp_reporte_orden_compra);
				exit();
			}
			
			if($tipo_compras == 'docs_compra') {
				if(count($tipos_comprobantes) <= 1) {
					if(in_array('00', $tipos_comprobantes)) {
						$json_data = array(
							"draw"            => 0,
							"recordsTotal"    => 0,  //Número total de registros que cumplen con los criterios iniciales
							"recordsFiltered" => 0, //Total registros filtrados
							"data"            => array(),   // total data array
							"numero_n"        => 0
						);
			
						echo json_encode($json_data);
						exit();
					}
				}

				if(count($tipos_comprobantes) > 0){ $tipos_documentos_sql = " and id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; }

				$query_1 = "estado = 'activo' and CAST($tipo_filtro_fecha AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST($tipo_filtro_fecha AS DATE) <= CAST(:fecha_fin: AS DATE) and id_contribuyente = :id_contribuyente: $tipos_documentos_sql and tipo_envio_sunat = '".$contribuyente->tipo_envio_sunat."' ";
			} else {
				if(count($tipos_comprobantes) > 0) {
					if(!in_array('00', $tipos_comprobantes)) {
						$json_data = array(
							"draw"            => 0,
							"recordsTotal"    => 0,  //Número total de registros que cumplen con los criterios iniciales
							"recordsFiltered" => 0, //Total registros filtrados
							"data"            => array(),   // total data array
							"numero_n"        => 0
						);
			
						echo json_encode($json_data);
						exit();
					}
				}

				$query_1 = "estado = 'activo' and CAST($tipo_filtro_fecha AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST($tipo_filtro_fecha AS DATE) <= CAST(:fecha_fin: AS DATE) and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico in ('00') and tipo_envio_sunat = '".$contribuyente->tipo_envio_sunat."' and tipo = 'compra' ";
			}
            
            if(intval($id_sucursal) > 0) {
                $query_1 = $query_1.' and idsucursal = '.$id_sucursal;
            }
    
            if(intval($id_vendedor) > 0) {
                $query_1 = $query_1.' and id_usuario = '.$id_vendedor;
            }

			$comprobantes = Compra::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente), "order" => "$tipo_filtro_fecha DESC"));
            
            //Inicio de configuración Server Side.
            $total_registros = count($comprobantes);

            $columnas = array( 
                //indice de la columna en datatable => nombre de la columna
                0 => $tipo_filtro_fecha,
                1 => $tipo_filtro_fecha,
                2 => 'ruc_razon_social',
                3 => 'serie_num_comprobante',
                4 => 'total',
                5 => $tipo_filtro_fecha
			);

			if($tipo_compras == 'docs_compra') {
				$query = "SELECT  compra.*, proveedor.id_tipodocidentidad, proveedor.num_doc, proveedor.razon_social, CONCAT(proveedor.num_doc, ' ', proveedor.razon_social) ruc_razon_social, CONCAT(compra.serie_comprobante,'-',compra.numero_comprobante) serie_num_comprobante FROM compra INNER JOIN proveedor on compra.id_proveedor = proveedor.id_proveedor where compra.estado = 'activo' and  CAST(compra.$tipo_filtro_fecha AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(compra.$tipo_filtro_fecha AS DATE) <= CAST(:fecha_fin AS DATE) and compra.id_contribuyente = :id_contribuyente and compra.id_tipodoc_electronico in ('01', '03', '07', '08') and compra.tipo_envio_sunat = '".$contribuyente->tipo_envio_sunat."' ";
			} else {
				$query = "SELECT  compra.*, proveedor.id_tipodocidentidad, proveedor.num_doc, proveedor.razon_social, CONCAT(proveedor.num_doc, ' ', proveedor.razon_social) ruc_razon_social, CONCAT(compra.serie_comprobante,'-',compra.numero_comprobante) serie_num_comprobante FROM compra INNER JOIN proveedor on compra.id_proveedor = proveedor.id_proveedor where compra.estado = 'activo' and  CAST(compra.$tipo_filtro_fecha AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(compra.$tipo_filtro_fecha AS DATE) <= CAST(:fecha_fin AS DATE) and compra.id_contribuyente = :id_contribuyente and compra.id_tipodoc_electronico in ('00') and compra.tipo = 'compra' and compra.tipo_envio_sunat = '".$contribuyente->tipo_envio_sunat."' ";
			}
			
            if(intval($id_sucursal) > 0) {
                $query = $query.' and idsucursal = '.$id_sucursal.' ';
            }
    
            if(intval($id_vendedor) > 0) {
                $query = $query.' and id_usuario = '.$id_vendedor.' ';
            }

			$termino = '';
            if(!empty($datapost['search']['value'])) {
				$termino = str_replace('  ', ' ', trim($datapost['search']['value']));
				$trozos = explode(" ", $termino); 
				$numero_trozos = count($trozos);
				$num_letras = strlen(preg_replace('/\s+/', '', $termino));
				
				if($numero_trozos <= 3) {
					$termino = '%'.$termino.'%';
					$query = $query." AND (proveedor.num_doc like :termino or CONCAT(compra.serie_comprobante,'-',compra.numero_comprobante) like :termino or proveedor.razon_social like :termino)";
				} else {
					$termino = str_replace(" ", "+.*", $termino);
					$termino = str_replace(' ', '', $termino);
					$termino = "($termino)";
					$query = $query." AND (proveedor.num_doc RLIKE :termino or CONCAT(compra.serie_comprobante,'-',compra.numero_comprobante) RLIKE :termino or proveedor.razon_social RLIKE :termino)";
				}
            }

            $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $datapost['search']['value']);

			$query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
			
			try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
                if(!empty($termino)) {
                    $sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
                }
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
			}
			
			$array_lista = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
				$item = (object)$fila;
				$n++;


				if($item->id_codigomoneda == 'PEN') {
					$simbolo_moneda = 'S/ ';
				} else {
					$simbolo_moneda = 'USD ';
				}

				$total_documento = $simbolo_moneda.$item->total;

				$totales_x_tipoafectacion = '';
				if($item->total_gravadas > 0) {
					$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_gravadas.'</span> Total Gravado: </a></li>
					';
				}

				if($item->total_inafecta > 0) {
					$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_inafecta.'</span> Total Inafecto: </a></li>
					';
				}

				if($item->total_exoneradas > 0) {
					$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_exoneradas.'</span> Total Exonerado: </a></li>
					';
				}

				if($item->total_gratuitas > 0) {
					$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_gratuitas.'</span> Total Gratuito: </a></li>
					';
				}

				if($item->total_exportacion > 0) {
					$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_exportacion.'</span> Total Exportación: </a></li>
					';
				}

				if($item->total_descuento > 0) {
					$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-danger pull-right">- '.$simbolo_moneda.' '.$item->total_descuento.'</span> Total Descuento: </a></li>
					';
				}

				$detalle_totales = '
				<div class="btn-group">
					<a href="javascript:void(0);" class="label border-left-primary label-striped dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> '.$total_documento.'<span class="caret"></span></a>

					<ul class="dropdown-menu">
					'.$totales_x_tipoafectacion.'
						<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_igv.'</span> Total IGV: </a></li>
						<li class="divider"></li>
						<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total.'</span> Total: </a></li>
					</ul>
				</div>
				';

				$comprobante_compra = '';
				if($item->id_tipodoc_electronico == '01') {
					$comprobante_compra = '
					<div class="media-body">
						<a href="javascript:void(0);" onclick="get_detalle_compra('.$item->id_compra.')" data-toggle="modal" data-target="#detalle_compra" class="display-inline-block text-default text-semibold letter-icon-title">'.$item->serie_comprobante.'-'.$item->numero_comprobante.'</a>
						<div class="text-muted text-size-small"><span class="status-mark border-success position-left"></span> Factura Compra</div>
					</div>
					';
				} elseif ($item->id_tipodoc_electronico == '03') {
					$comprobante_compra = '
					<div class="media-body">
						<a href="javascript:void(0);" onclick="get_detalle_compra('.$item->id_compra.')" data-toggle="modal" data-target="#detalle_compra" class="display-inline-block text-default text-semibold letter-icon-title">'.$item->serie_comprobante.'-'.$item->numero_comprobante.'</a>
						<div class="text-muted text-size-small"><span class="status-mark border-primary position-left"></span> Boleta Compra</div>
					</div>
					';
				}  elseif ($item->id_tipodoc_electronico == '07') {
					$comprobante_compra = '
					<div class="media-body">
						<a href="javascript:void(0);" onclick="get_detalle_compra('.$item->id_compra.')" data-toggle="modal" data-target="#detalle_compra" class="display-inline-block text-default text-semibold letter-icon-title">'.$item->serie_comprobante.'-'.$item->numero_comprobante.'</a>
						<div class="text-muted text-size-small"><span class="status-mark border-danger position-left"></span> Nota de Crédito</div>
					</div>
					';
				}  elseif ($item->id_tipodoc_electronico == '08') {
					$comprobante_compra = '
					<div class="media-body">
						<a href="javascript:void(0);" onclick="get_detalle_compra('.$item->id_compra.')" data-toggle="modal" data-target="#detalle_compra" class="display-inline-block text-default text-semibold letter-icon-title">'.$item->serie_comprobante.'-'.$item->numero_comprobante.'</a>
						<div class="text-muted text-size-small"><span class="status-mark border-info position-left"></span> Nota de Débito</div>
					</div>
					';
				} else {
					$serie_desconocida = empty($item->serie_comprobante)?'0000':$item->serie_comprobante;
					$numero_desconocido = empty($item->numero_comprobante)?'0000':$item->numero_comprobante;
					$comprobante_compra = '
					<div class="media-body">
						<a href="javascript:void(0);" onclick="get_detalle_compra('.$item->id_compra.')" data-toggle="modal" data-target="#detalle_compra" class="display-inline-block text-default text-semibold letter-icon-title">'.$serie_desconocida.'-'.$numero_desconocido.'</a>
						<div class="text-muted text-size-small"><span class="status-mark border-indigo position-left"></span> Otr.Compr.Compra</div>
					</div>
					';
				}

				$menu_opciones = '
				<ul class="icons-list text-center">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<i class="icon-menu9"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">';


						if($tipo_compras == 'otros_docs') {
							$enlace_pdf_a4 = $this->get_enlaces_pdf_compras($contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat, $item->id_compra, $item->id_tipodoc_electronico)['enlaces']['url_a4'];
							$enlace_pdf_ticket = $this->get_enlaces_pdf_compras($contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat, $item->id_compra, $item->id_tipodoc_electronico)['enlaces']['url_ticket'];
							$menu_opciones = $menu_opciones.'<li><a href="'.$enlace_pdf_a4.'" target="_blank"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"> Ver A4</a></li>';
							$menu_opciones = $menu_opciones.'<li><a href="'.$enlace_pdf_ticket.'" target="_blank"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"> Ver Ticket</a></li>';
					
						}



				$menu_opciones = $menu_opciones.'
							<li><a href="javascript:void(0);" onclick="anular_compra('.$item->id_compra.')"><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>
							<li><a href="javascript:void(0);"  onclick="get_detalle_compra('.$item->id_compra.')" data-toggle="modal" data-target="#detalle_compra"><i class="fa fa-eye mr-2"></i>Ver Detalle</a></li>
						</ul>
					</li>
				</ul>
				';

				$array_lista[] = array(
					$n,
					date("d-m-Y", strtotime($item->$tipo_filtro_fecha)),
					$item->num_doc.'<br />'.$item->razon_social,
					$comprobante_compra,
					$detalle_totales,
					$menu_opciones
				);
			}

			$json_data = array(
                "draw"            => intval($datapost['draw']),
                "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
                "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
                "data"            => $array_lista,   // total data array
                "numero_n"        => $n
            );

            echo json_encode($json_data);
            exit();
		}
	}

	public function get_lista_orden_compra($data_consulta, $datapost) {
		$id_contribuyente = $data_consulta['id_contribuyente'];
		$tipo_envio_sunat = $data_consulta['tipo_envio_sunat'];
		$fecha_inicio = $data_consulta['fecha_inicio'];
		$fecha_fin = $data_consulta['fecha_fin'];
		$id_sucursal = $data_consulta['id_sucursal'];
		$tipo_filtro_fecha = $data_consulta['tipo_filtro_fecha'];
		$id_vendedor = $data_consulta['id_vendedor'];

		$query_1 = "CAST($tipo_filtro_fecha AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST($tipo_filtro_fecha AS DATE) <= CAST(:fecha_fin: AS DATE) and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = '99' and tipo_envio_sunat = '".$tipo_envio_sunat."' ";

		if(intval($id_sucursal) > 0) {
			$query_1 = $query_1.' and idsucursal = '.$id_sucursal;
		}

		if(intval($id_vendedor) > 0) {
			$query_1 = $query_1.' and id_usuario = '.$id_vendedor;
		}

		$comprobantes = OrdenCompra::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $id_contribuyente), "order" => "$tipo_filtro_fecha DESC"));

		$total_registros = count($comprobantes);

		$columnas = array( 
			//indice de la columna en datatable => nombre de la columna
			0 => $tipo_filtro_fecha,
			1 => $tipo_filtro_fecha,
			2 => 'ruc_razon_social',
			3 => 'serie_num_comprobante',
			4 => 'total',
			5 => $tipo_filtro_fecha
		);

		$query = "SELECT  ordencompra.*, proveedor.id_tipodocidentidad, proveedor.num_doc, proveedor.razon_social, CONCAT(proveedor.num_doc, ' ', proveedor.razon_social) ruc_razon_social, CONCAT(ordencompra.serie_comprobante,'-',ordencompra.numero_comprobante) serie_num_comprobante FROM orden_compra ordencompra INNER JOIN proveedor on ordencompra.id_proveedor = proveedor.id_proveedor where CAST(ordencompra.$tipo_filtro_fecha AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(ordencompra.$tipo_filtro_fecha AS DATE) <= CAST(:fecha_fin AS DATE) and ordencompra.id_contribuyente = :id_contribuyente and ordencompra.id_tipodoc_electronico = '99' and ordencompra.tipo_envio_sunat = '".$tipo_envio_sunat."' ";

		if(intval($id_sucursal) > 0) {
			$query = $query.' and idsucursal = '.$id_sucursal.' ';
		}

		if(intval($id_vendedor) > 0) {
			$query = $query.' and id_usuario = '.$id_vendedor.' ';
		}

		$termino = '';
		if(!empty($datapost['search']['value'])) {
			$termino = str_replace('  ', ' ', trim($datapost['search']['value']));
			$trozos = explode(" ", $termino); 
			$numero_trozos = count($trozos);
			$num_letras = strlen(preg_replace('/\s+/', '', $termino));
			
			if($numero_trozos <= 3) {
				$termino = '%'.$termino.'%';
				$query = $query." AND (proveedor.num_doc like :termino or CONCAT(ordencompra.serie_comprobante,'-',ordencompra.numero_comprobante) like :termino or proveedor.razon_social like :termino)";
			} else {
				$termino = str_replace(" ", "+.*", $termino);
				$termino = str_replace(' ', '', $termino);
				$termino = "($termino)";
				$query = $query." AND (proveedor.num_doc RLIKE :termino or CONCAT(ordencompra.serie_comprobante,'-',ordencompra.numero_comprobante) RLIKE :termino or proveedor.razon_social RLIKE :termino)";
			}
		}

		$total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $id_contribuyente, $datapost['search']['value']);

		$query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			if(!empty($termino)) {
				$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
			}
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			exit();
		}

		$array_lista = array();
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$item = (object)$fila;
			$n++;


			if($item->id_codigomoneda == 'PEN') {
				$simbolo_moneda = 'S/ ';
			} else {
				$simbolo_moneda = 'USD ';
			}

			$total_documento = $simbolo_moneda.$item->total;

			$totales_x_tipoafectacion = '';
			if($item->total_gravadas > 0) {
				$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
				<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_gravadas.'</span> Total Gravado: </a></li>
				';
			}

			if($item->total_inafecta > 0) {
				$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
				<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_inafecta.'</span> Total Inafecto: </a></li>
				';
			}

			if($item->total_exoneradas > 0) {
				$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
				<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_exoneradas.'</span> Total Exonerado: </a></li>
				';
			}

			if($item->total_gratuitas > 0) {
				$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
				<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_gratuitas.'</span> Total Gratuito: </a></li>
				';
			}

			if($item->total_exportacion > 0) {
				$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
				<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_exportacion.'</span> Total Exportación: </a></li>
				';
			}

			if($item->total_descuento > 0) {
				$totales_x_tipoafectacion = $totales_x_tipoafectacion.'
				<li><a href="#"><span class="badge badge-danger pull-right">- '.$simbolo_moneda.' '.$item->total_descuento.'</span> Total Descuento: </a></li>
				';
			}

			$detalle_totales = '
			<div class="btn-group">
				<a href="javascript:void(0);" class="label border-left-primary label-striped dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> '.$total_documento.'<span class="caret"></span></a>

				<ul class="dropdown-menu">
				'.$totales_x_tipoafectacion.'
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total_igv.'</span> Total IGV: </a></li>
					<li class="divider"></li>
					<li><a href="#"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$item->total.'</span> Total: </a></li>
				</ul>
			</div>
			';

			$comprobante_compra = '
			<div class="media-body">
				'.$item->serie_comprobante.'-'.$item->numero_comprobante.'
				<div class="text-muted text-size-small"><span class="status-mark border-success position-left"></span> Orden de Compra</div>
			</div>
			';

			$parametros_funcion_jquery = "('$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante, '$item->tipo_envio_sunat')";

			$etiqueta_anulacion = '';
			if($item->estado != 'activo') {
                $etiqueta_anulacion = '<br /><span class="label bg-danger">ANULADO</span><br/>';
            }

			$menu_opciones = '';
			if($item->estado == 'activo') {
				$menu_opciones = '
				<ul class="icons-list text-center">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<i class="icon-menu9"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
							<li><a href="javascript:void(0);" onclick="anular_orden_compra'.$parametros_funcion_jquery.'"><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>
						</ul>
					</li>
				</ul>
				';
			}

			$data_enlace = array(
				'id_contribuyente'          => $item->id_contribuyente,
				'id_tipodoc_electronico'    => $item->id_tipodoc_electronico,
				'serie_comprobante'         => $item->serie_comprobante,
				'numero_comprobante'        => $item->numero_comprobante,
				'tipo_envio_sunat'          => $item->tipo_envio_sunat
			);

			$orden_compra = new OrdendecompraController;
			$enlaces_orden_compra = $orden_compra->get_enlaces_orden_compra($data_enlace);
			$url_a4 = $enlaces_orden_compra['a4'];
            $url_ticket = $enlaces_orden_compra['ticket'];

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_a4 = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';

            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';
            $btn_pdf = $btn_pdf_a4.$btn_pdf_ticket;

			$array_lista[] = array(
				$btn_pdf,
				date("d-m-Y", strtotime($item->$tipo_filtro_fecha)),
				$item->num_doc.'<br />'.$item->razon_social,
				$comprobante_compra.$etiqueta_anulacion,
				$detalle_totales,
				$menu_opciones
			);
		}

		$json_data = array(
			"draw"            => intval($datapost['draw']),
			"recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
			"recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
			"data"            => $array_lista,   // total data array
			"numero_n"        => $n
		);

		return $json_data;
	}

	public function anularCompraAction() {
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

			$id_compra = intval($datapost['id_compra']) + 0;
			$compra = Compra::findFirst(array("id_compra = :id_compra: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_compra' => $id_compra, 'id_contribuyente' => $usuario->id_contribuyente)));

			if(!$compra) {
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Registro de Compra no Existe!';
                echo json_encode($resp);
                exit();
			}

			if($compra->estado == 'inactivo' || $compra->estado == 'anulado') {
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento de compra ya se encuentra anulado!';
                echo json_encode($resp);
                exit();
			}
			
			$confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Necesitamos Tu Confirmación!';
				$resp['mensaje'] = '<strong class="text-danger">¿Realmente Deseas Eliminar la Compra '.$compra->serie_comprobante.'-'.$compra->numero_comprobante.'?</strong>, una vez eliminada la compra, ya no se podrá recuperar!';
				echo json_encode($resp);
				exit();
			}

			$this->db->begin();

			$compra->estado = 'inactivo';

			try {
				if(!$compra->save()) {
					$this->db->rollback();
					$msg = '';
					foreach ($compra->getMessages() as $message) {
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
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Error al intentar anular la compra!';
				echo json_encode($resp);
				exit();
			}

			$detalle_compra = DetalleCompra::find(array("id_compra = :id_compra:", 'bind' => array('id_compra' => $id_compra)));
			foreach($detalle_compra as $item) {
				
				$cantidad_kardex = $item->cantidad;
                if(isset($item->id_presentacion) && intval($item->id_presentacion) > 0) {
                    $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item->id_presentacion))));
                    if($presentacion) {
                        $cantidad_kardex = round($item->cantidad*$presentacion->cantidad_und_base, 4);
                    }
                }

				$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto)));
				if($producto) {
					$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
					if($unidad_medida) {
						if($unidad_medida->codigo != 'ZZ') {
							if($compra->id_tipodoc_electronico == '00' || $compra->id_tipodoc_electronico == '01' || $compra->id_tipodoc_electronico == '03' || $compra->id_tipodoc_electronico == '08') {
								//si se anula un documento no oficial, una boleta, una factura, una nota de débito entonces significa que el supuesto ingreso es inválido, entonces debe ser una SALIDA.
								$kardex_guardado = Kardex::findFirst(array("id_compra = :id_compra:", 'bind' => array('id_compra' => $compra->id_compra), "order" => "id_kardex DESC"));
								if($kardex_guardado) {
									$data_kardex['id_contribuyente'] = $compra->id_contribuyente;
									$data_kardex['idsucursal'] = $compra->idsucursal;
									$data_kardex['idproducto'] = $item->id_producto;
									$data_kardex['idusuario'] = $idusuario;
									$data_kardex['tipo_envio_sunat'] = $compra->tipo_envio_sunat; //prueba, produccion
									$data_kardex['tipo_kardex'] = 'anulacion_compra'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
									$data_kardex['estado_kardex'] = 'activo'; //activo, anulado
									$data_kardex['cantidad_salida'] = $cantidad_kardex;
									$data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario;
	
									$data_kardex['detalle'] = 'Anulación de Compra';
									$data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
									$data_kardex['docref_id_contribuyente'] = $compra->id_contribuyente;
									$data_kardex['docref_id_tipodoc_electronico'] = $compra->id_tipodoc_electronico;
									$data_kardex['docref_serie_comprobante'] = $compra->serie_comprobante;
									$data_kardex['docref_numero_comprobante'] = $compra->numero_comprobante;
									$data_kardex['docref_tipo_envio_sunat'] = $compra->tipo_envio_sunat;
									$data_kardex['id_compra'] = $compra->id_compra;
	
									$kardex = new KardexController;
									$resp_kardex = $kardex->registrar_en_kardex($data_kardex);
									if($resp_kardex['respuesta'] == 'error') {
										$this->db->rollback();
										echo json_encode($resp_kardex);
										exit();
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
											$this->db->rollback();
											echo json_encode($resp);
											exit();
										}
									} catch (Exception $e) {
                						$this->saveLogger($e);
										$this->db->rollback();
										$resp['respuesta'] = 'error';
										$resp['titulo'] = 'Error';
										$resp['mensaje'] = 'Error al intentar anular la compra!';
										echo json_encode($resp);
										exit();
									}
								}
							}

							if($compra->id_tipodoc_electronico == '07') {
								//Si se está registrando la anulación de una nota de crédito
								//entonces, recordar que cuando se registra una nota de crédito sale la mercadería de almacen porque fué devuelta al proveedor
								//si esta nota de crédito es anulada, entonces representa un ingreso por anulación de nota de crédito.
								$kardex_guardado = Kardex::findFirst(array("id_compra = :id_compra:", 'bind' => array('id_compra' => $compra->id_compra), "order" => "id_kardex DESC"));
								if($kardex_guardado) {
									$data_kardex['id_contribuyente'] = $compra->id_contribuyente;
									$data_kardex['idsucursal'] = $compra->idsucursal;
									$data_kardex['idproducto'] = $item->id_producto;
									$data_kardex['idusuario'] = $idusuario;
									$data_kardex['tipo_envio_sunat'] = $compra->tipo_envio_sunat; //prueba, produccion
									$data_kardex['tipo_kardex'] = 'compra'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
									$data_kardex['estado_kardex'] = 'activo'; //activo, anulado


									$data_kardex['cantidad_entrada'] = $cantidad_kardex;
									$data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario_promedio;

									$data_kardex['detalle'] = 'Anulación de Nota de Crédito';
									$data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
									$data_kardex['docref_id_contribuyente'] = $compra->id_contribuyente;
									$data_kardex['docref_id_tipodoc_electronico'] = $compra->id_tipodoc_electronico;
									$data_kardex['docref_serie_comprobante'] = $compra->serie_comprobante;
									$data_kardex['docref_numero_comprobante'] = $compra->numero_comprobante;
									$data_kardex['docref_tipo_envio_sunat'] = $compra->tipo_envio_sunat;
									$data_kardex['id_compra'] = $compra->id_compra;

									$kardex = new KardexController;
									$resp_kardex = $kardex->registrar_en_kardex($data_kardex);
									if($resp_kardex['respuesta'] == 'error') {
										$this->db->rollback();
										echo json_encode($resp_kardex);
										exit();
									}
									
									$producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
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
											$this->db->rollback();
											echo json_encode($resp);
											exit();
										}
									} catch (Exception $e) {
                						$this->saveLogger($e);
										$this->db->rollback();
										$resp['respuesta'] = 'error';
										$resp['titulo'] = 'Error';
										$resp['mensaje'] = 'Error al intentar anular la compra!';
										echo json_encode($resp);
										exit();
									}
								}
							}
						}
					}
				}
			}

			$data['id_compra'] = $compra->id_compra;
			$data['tipo_compra'] = $compra->tipo_compra;
			$data['id_contribuyente'] = $compra->id_contribuyente;
			$data['idsucursal'] = $compra->idsucursal;
			$data['id_tipodoc_electronico'] = $compra->id_tipodoc_electronico;
			$data['serie_comprobante'] = $compra->serie_comprobante;
			$data['numero_comprobante'] = $compra->numero_comprobante;
			$data['tipo_envio_sunat'] = $compra->tipo_envio_sunat;
			$data['id_usuario'] = $idusuario;
			$data['tipo_log'] = 'anular_compra';
			$data['id_proveedor'] = $compra->id_proveedor;
			$data['descripcion'] = '';

			$log_compra = new LogsController;
			$resp_log_compra = $log_compra->log_compra($data);
			
			$this->db->commit();
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Correcto';
			$resp['mensaje'] = 'La Compra ha Sido Eliminada Correctamente';
			$resp['resp_log_compra'] = $resp_log_compra;
			echo json_encode($resp);
			exit();
		}
	}

	public function getDatosEstadisticosAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            setlocale(LC_MONETARY, 'es_PE');
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

            $fecha_actual = date("Y-m-d H:i:s");
            $fecha_inicio_b = date("Y-m-d", strtotime("-29 days", strtotime($fecha_actual)));
            $fecha_fin_b = date("Y-m-d");
            
            $fecha_inicio = ($datapost['fecha_inicio'] == '')?$fecha_inicio_b:$datapost['fecha_inicio'];
            $fecha_fin = ($datapost['fecha_fin'] == '')?$fecha_fin_b:$datapost['fecha_fin'];

            $herramientas = new HerramientasController;
            if(!$herramientas->validate_date($fecha_inicio)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }

            if(!$herramientas->validate_date($fecha_fin)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }

            $id_sucursal = empty($datapost['id_sucursal'])?0:intval($datapost['id_sucursal']);
            $id_vendedor = empty($datapost['id_vendedor'])?0:intval($datapost['id_vendedor']);
            $periodos_validos = array('dia', 'mes', 'anio');
            $periodo = !in_array($datapost['periodo'], $periodos_validos)?'dia':$datapost['periodo'];

            $resp['respuesta'] = 'ok';
            $resp['fecha_inicio'] = $fecha_inicio;
            $resp['fecha_fin'] = $fecha_fin;
            $resp['id_sucursal'] = $id_sucursal;
            $resp['id_vendedor'] = $id_vendedor;
            $resp['periodo'] = $periodo;
			$resp['id_contribuyente'] = $contribuyente->id_contribuyente;
			
			//Series
			$serie_facturas = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '01', $id_sucursal, $id_vendedor, $periodo, $contribuyente->tipo_envio_sunat));
			$serie_boletas = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '03', $id_sucursal, $id_vendedor, $periodo, $contribuyente->tipo_envio_sunat));
			$serie_otros = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '00', $id_sucursal, $id_vendedor, $periodo, $contribuyente->tipo_envio_sunat));
			
            //periodo
            $resp['periodo_array'] = array_keys($this->get_array_periodo($fecha_inicio, $fecha_fin, $periodo));

            //Series
            $series['Facturas'] = $serie_facturas;
            $series['Boletas'] = $serie_boletas;
            $series['Otros'] = $serie_otros;

            //gráfico circular
			$resp['totales_compra'] = $this->get_totales_compras($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);
			
            $resp['series'] = $series;
            echo json_encode($resp);
            exit();            
        }
	}

	public function get_totales_compras($id_contribuyente, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_sucursal = 0, $id_vendedor = 0) {

		$query = "select id_tipodoc_electronico, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles from compra where id_contribuyente = :id_contribuyente and id_tipodoc_electronico in ('03', '01', '00') and estado = 'activo' and CAST(fecha_comprobante AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(fecha_comprobante AS DATE) <= CAST(:fecha_fin AS DATE) and tipo_envio_sunat = '".$tipo_envio_sunat."' ";
		
        if(intval($id_sucursal) > 0) { $query = $query.' and idsucursal = :id_sucursal '; }
        if(intval($id_vendedor) > 0) { $query = $query.' and id_usuario = :id_vendedor '; }

		$query = $query."  GROUP BY id_tipodoc_electronico";
		
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(intval($id_sucursal) > 0) { $sentencia->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT); }
            if(intval($id_vendedor) > 0) { $sentencia->bindParam(':id_vendedor', $id_vendedor, PDO::PARAM_INT); }
            
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: get_totales_compras ',  $e->getMessage(), "\n";
            exit();
        }
        
        $tipos_documentos = array();
        //contado, credito, tarjeta_credito, transferencia	
        $tipos_documentos['Boletas de Compra'] = 0;
        $tipos_documentos['Facturas de Compra'] = 0;
		$tipos_documentos['Otros Documentos'] = 0;
		
        $total = 0;

        while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			if($fila->id_tipodoc_electronico == '03') {
				$tipos_documentos['Boletas de Compra'] = $tipos_documentos['Boletas de Compra'] + $fila->total_soles;
			} else if ($fila->id_tipodoc_electronico == '01') {
				$tipos_documentos['Facturas de Compra'] = $tipos_documentos['Facturas de Compra'] + $fila->total_soles;
			} else {
				$tipos_documentos['Otros Documentos'] = $tipos_documentos['Otros Documentos'] + $fila->total_soles;
			}
			
            $total = $total + $fila->total_soles;
		}
		

        $lista_condiciones = array();
        foreach($tipos_documentos as $key => $valor) {
            if($valor > 0) {
                $lista_condiciones[] = array(
                    'name' => $key,
                    'value' => $valor
                );
            }
        }

        return $lista_condiciones;
    }

	public function get_serie_documento($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $id_sucursal = 0, $id_vendedor = 0, $periodo = 'dia', $tipo_envio = 'prueba') {
        if($periodo == 'mes') { 
            $query_time = '%Y-%m'; 
        } else if ($periodo == 'anio') { 
            $query_time = '%Y'; 
        } else { 
            $query_time = '%Y-%m-%d'; 
		}
		
		$query = "SELECT DATE_FORMAT(fecha_comprobante, '".$query_time."') as fecha, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM compra where id_contribuyente = :id_contribuyente and estado = 'activo' and CAST(fecha_comprobante AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(fecha_comprobante AS DATE) <= CAST(:fecha_fin AS DATE) and id_tipodoc_electronico = :id_tipodoc_electronico and tipo_envio_sunat = '".$tipo_envio."' ";
        
        if(intval($id_sucursal) > 0) { $query = $query.' and idsucursal = :id_sucursal '; }
        if(intval($id_vendedor) > 0) { $query = $query.' and id_usuario = :id_vendedor '; }
        $query = $query." group by DATE_FORMAT(fecha_comprobante, '".$query_time."') ORDER BY date(fecha_comprobante) ASC";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            if(intval($id_sucursal) > 0) { $sentencia->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT); }
            if(intval($id_vendedor) > 0) { $sentencia->bindParam(':id_vendedor', $id_vendedor, PDO::PARAM_INT); }

            $sentencia->execute();

            $array_periodo = $this->get_array_periodo($fecha_inicio, $fecha_fin, $periodo);
            while ($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_periodo[$fila->fecha] = $fila->total_soles;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: get_serie_documento ',  $e->getMessage(), "\n";
            exit();
        }

        return $array_periodo;
    }

	public function get_array_periodo($fecha_inicio, $fecha_fin, $periodo) {
        if($periodo == 'mes') {
            $interval_time = 'P1M';
            $format_time = "Y-m";
            $query_time = '%Y-%m';
        } else if ($periodo == 'anio') {
            $interval_time = 'P1Y';
            $format_time = "Y";
            $query_time = '%Y';
        } else {
            $interval_time = 'P1D';
            $format_time = "Y-m-d";
            $query_time = '%Y-%m-%d';
        }

        $periodo_fecha = new DatePeriod(
            new DateTime($fecha_inicio),
            new DateInterval($interval_time),
            new DateTime($fecha_fin)
        );

        $array_date = array();
        foreach ($periodo_fecha as $item) {
            $item = (array)$item;
            $array_date[date($format_time, strtotime($item['date']))] = 0;
        }

        $array_date[date($format_time, strtotime($fecha_fin))] = 0;

        return $array_date;
    }

	public function get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $id_contribuyente, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            if(!empty($termino_busqueda)) {
                $termino_busqueda = '%'.$termino_busqueda.'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $n++;
        }

        return $n;
	}
	
	public function registrar_entrada_sin_documento($idproducto, $id_usuario, $id_contribuyente, $idsucursal, $cantidad, $fecha, $nota = '', $data_origen = array(), $costo_compra_producto = 0) {

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $idproducto)));

		$num_doc = $contribuyente->ruc;
		$proveedor = Proveedor::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('num_doc' => $num_doc, 'id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodocidentidad' => '6')));

		if(!$proveedor) {
			$proveedor = new Proveedor();
			$proveedor->id_contribuyente = $contribuyente->id_contribuyente;
			$proveedor->id_tipodocidentidad = '6';
			$proveedor->codigo = uniqid('PROV_'.$contribuyente->id_contribuyente,true);;
			$proveedor->num_doc = $num_doc;
			$proveedor->razon_social = $contribuyente->razon_social;
			$proveedor->direccion_fiscal = $contribuyente->direccion_fiscal;
			$proveedor->id_cod_ubigeo = $contribuyente->codigo_ubigeo;
			$proveedor->fecha_registro = date('Y-m-d H:i:s', strtotime($fecha));
			$proveedor->estado = 'activo';

			try {
				if(!$proveedor->save()) {
					$msg = '';
					foreach ($proveedor->getMessages() as $message) {
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
				$resp['mensaje'] = 'Error al intentar registrar el proveedor!';
				return $resp;
			}
		}

		$tipo_cambio = 1;
		if($producto->id_cod_moneda == 'USD') {
			$apisunat = new ApisunatController;
			$resp_tipo_cambio = $apisunat->get_tipo_cambio(date("Y-m-d", strtotime($fecha)));
			$tipo_cambio = $resp_tipo_cambio['compra'];
			$tipo_cambio = $producto->tipo_cambio_sunat;
		}
		
		if($costo_compra_producto <= 0) {
			$costo_compra_producto = $producto->precio_compra;
		}
		
		$precio_compra_total_con_igv = round($cantidad*$costo_compra_producto, 2);
		$precio_compra_total_sin_igv = round($cantidad*$costo_compra_producto/1.18, 2);
		$total_igv = round($precio_compra_total_con_igv - $precio_compra_total_sin_igv, 2);

		$total_gravadas = 0;
		$total_inafecta = 0;
		$total_exoneradas = 0;
		$total_exportacion = 0;
		$total_gratuitas = 0;

		if($producto->id_tipoafectacionigv == '10' || $producto->id_tipoafectacionigv == 7152) {
			$total_gravadas = $precio_compra_total_sin_igv;
		} elseif ($producto->id_tipoafectacionigv == '20') {
			$total_exoneradas = $precio_compra_total_sin_igv;
		} elseif ($producto->id_tipoafectacionigv == '30') {
			$total_inafecta = $precio_compra_total_sin_igv;
		} elseif ($producto->id_tipoafectacionigv == '40') {
			$total_exportacion = $precio_compra_total_sin_igv;
		} else {
			$total_gratuitas = $precio_compra_total_sin_igv;
		}

		$total_icbper = 0;
		if($producto->id_tipoafectacionigv == 7152) {
			$total_icbper = $cantidad*0.10;
		}
		
		$new_compra = new Compra();
		$new_compra->id_contribuyente = $contribuyente->id_contribuyente;
		$new_compra->id_tipodoc_electronico = '00';
		$new_compra->idsucursal = $idsucursal;
		$new_compra->serie_comprobante = null;
		$new_compra->numero_comprobante = null;
		$new_compra->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$new_compra->total_gravadas = $total_gravadas;
		$new_compra->total_exoneradas = $total_exoneradas;
		$new_compra->total_inafecta = $total_inafecta;
		$new_compra->total_exportacion = $total_exportacion;
		$new_compra->total_gratuitas = $total_gratuitas;
		$new_compra->total_icbper = $total_icbper;		
		$new_compra->total_descuento = 0;
		$new_compra->porcentaje_descuento_total = 0;
		$new_compra->sub_total = $precio_compra_total_sin_igv;
		$new_compra->porcentaje_igv = "18.00";
		$new_compra->total_igv = $total_igv;
		$new_compra->total_isc = '0';
		$new_compra->total_otr_imp = '0';
		$new_compra->total = $precio_compra_total_con_igv;
		$new_compra->nro_guia_remision = '';
		$new_compra->cod_guia_remision = '';
		$new_compra->nro_otr_comprobante = '';
		$new_compra->fecha_comprobante = date("Y-m-d", strtotime($fecha));
		$new_compra->id_codigomoneda = $producto->id_cod_moneda;
		$new_compra->tipo_cambio_sunat = $tipo_cambio;
		$new_compra->id_proveedor = $proveedor->id_proveedor;
		$new_compra->id_usuario = $id_usuario;
		$new_compra->fecha_registro = date('Y-m-d H:i:s', strtotime($fecha));
		$new_compra->nota = $nota;
		$new_compra->estado = 'activo';

		if(isset($data_origen['tipo'])) {
			$new_compra->tipo = $data_origen['tipo'];
		}

		if(isset($data_origen['idsucursal_origen'])) {
			$new_compra->idsucursal_origen = $data_origen['idsucursal_origen'];
		}

		if(isset($data_origen['idsucursal_destino'])) {
			$new_compra->idsucursal_destino = $data_origen['idsucursal_destino'];
		}

		if(isset($data_origen['id_contribuyente_origen'])) {
			$new_compra->id_contribuyente_origen = $data_origen['id_contribuyente_origen'];
		}

		if(isset($data_origen['id_tipodocumento_origen'])) {
			$new_compra->id_tipodocumento_origen = $data_origen['id_tipodocumento_origen'];
		}

		if(isset($data_origen['numero_comprobante_origen'])) {
			$new_compra->numero_comprobante_origen = $data_origen['numero_comprobante_origen'];
		}

		if(isset($data_origen['modalidad_origen'])) {
			$new_compra->modalidad_origen = $data_origen['modalidad_origen'];
		}

		try {
			if(!$new_compra->save()) {
				$msg = '';
				foreach ($new_compra->getMessages() as $message) {
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
			$resp['mensaje'] = 'Error al intentar registrar la compra! '.$e->getMessage();
			return $resp;
		}

		$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));

		$new_detalle = new DetalleCompra();
		$new_detalle->id_compra = $new_compra->id_compra;
		$new_detalle->id_unidad_medida = $producto->id_unidad_medida;
		$new_detalle->unidad_medida = $unidadmedida->codigo;
		$new_detalle->cantidad = $cantidad;
		$new_detalle->precio = round($costo_compra_producto, 2);
		$new_detalle->sub_total = round(($costo_compra_producto*$cantidad)/1.18, 2);
		$new_detalle->importe = round(($costo_compra_producto*$cantidad)/1.18, 2);
		$new_detalle->id_codigoprecio = '01';
		$new_detalle->igv = round($costo_compra_producto*$cantidad, 2) - round(($costo_compra_producto*$cantidad)/1.18, 2);
		$new_detalle->isc = 0;
		$new_detalle->id_tipoafectacionigv = $producto->id_tipoafectacionigv;
		$new_detalle->id_producto = $producto->idproducto;
		$new_detalle->codigo_producto = $producto->codigo;
		$new_detalle->icbper = $total_icbper;
		$new_detalle->descripcion = $producto->nombre;
		$new_detalle->precio_sin_igv = round($costo_compra_producto/1.18, 2);

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
			$resp['mensaje'] = 'Error al intentar registrar el detalle de la compra! '.$e->getMessage();
			return $resp;
		}
		
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Registro Exitoso';
		$resp['mensaje'] = 'La compra se ha registrado correctamente!';
		$resp['id_compra'] = $new_compra->id_compra;
		return $resp;
	}
	public function get_header_doccompra($idcompra, $id_contribuyente) {

		$encabezado = "SELECT c.*, p.*, t.* FROM compra c INNER JOIN proveedor p ON c.id_proveedor = p.id_proveedor LEFT JOIN sunat_tipodocelectronico t ON  c.id_tipodoc_electronico = t.id_tipodoc_electronico  WHERE
		 c.id_contribuyente = :id_contribuyente and c.id_compra = :id_compra";
		 try {
			$sentencia = $this->db->prepare($encabezado);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':id_compra', $idcompra, PDO::PARAM_INT);
			$sentencia->execute();
	
			$result = $sentencia->fetch();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }
		
		return $result;
	}
	public function get_detalle_doccompra($idcompra, $id_contribuyente){

		$lista_compra = Compra::find(array("id_contribuyente = :id_contribuyente: 
		 and id_compra = :id_compra:", 'bind' => array('id_contribuyente' => $id_contribuyente,'id_compra' => $idcompra)));
		 $detalle_compra = DetalleCompra::find(array("id_compra = :id_compra:", 'bind' => array('id_compra' => $idcompra)));
		 $array_lista = array();

		foreach($detalle_compra as $detalle_compra){
			$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $detalle_compra->id_unidad_medida)));
			$tipo_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $detalle_compra->id_tipoafectacionigv)));
			$array_lista[] = array(($detalle_compra->cantidad + 0), $detalle_compra->descripcion, $tipo_igv->descripcion,  $unidad_medida->nombre, $detalle_compra->precio, $detalle_compra->sub_total, $detalle_compra->igv, round($detalle_compra->sub_total + $detalle_compra->igv, 2));
		}
		return $array_lista;
	}
	public function getDetalleCompraAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
			$datapost = $this->request->getPost();
			$idcompra = !isset($datapost['idcompra'])?0:intval($datapost['idcompra']) + 0;
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
			}
			
			$header_detalle_compra = $this->get_header_doccompra($idcompra, $usuario->id_contribuyente);
			if(!$header_detalle_compra) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! No se han podido cargar los datos de la compra';
                echo json_encode($msj);
                exit();
			}
			$result_detalle_compra = $this->get_detalle_doccompra($idcompra, $usuario->id_contribuyente);
			if(!$result_detalle_compra) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! No se han podido cargar los datos';
                echo json_encode($msj);
                exit();
			}
			$resp['respuesta'] = 'ok';
			$resp['encabezado']  = $header_detalle_compra;
			$resp['lista']  = $result_detalle_compra;
			echo json_encode($resp);
            exit();  
			

		}
	}

	public function procesarXmlCpeAction() {
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
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }
			
			if(!isset($_FILES["xmlFile"])) {
                $resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Solo se permiten archivos XML';
				echo json_encode($resp);
				exit();
            }
            
            if(!isset($_FILES["xmlFile"]["tmp_name"])) {
                $resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Solo se permiten archivos XML';
				echo json_encode($resp);
				exit();
            }
            
            if(!isset($_FILES["xmlFile"]["type"])) {
                $resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Solo se permiten archivos XML';
				echo json_encode($resp);
				exit();
            }
			
			if ($_FILES["xmlFile"]["type"] != "text/xml") {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Solo se permiten archivos XML';
				echo json_encode($resp);
				exit();
			}

			$data['tipo_proceso'] = 'read_xml';
			
			$fileContent = file_get_contents($_FILES["xmlFile"]["tmp_name"]);
			$encodedFile = base64_encode($fileContent);

			$data['base64_xml'] = $encodedFile;
			
			$unidadmedida = SunatUnidadmedida::find();

			$data['token_cliente'] = $this->token_user; //KEY para que puedas consumir nuestra api
			$data['ruc_proveedor'] = $this->ruc_proveedor; //Tu número de RUC, el cuál será responsable por los datos enviados en todos los json

			$data['secret_data'] = array(
				"tipo_certificado"		=> $contribuyente->tipo_certificado, //no cambiar
				"tipo_proceso" 			=> "produccion", //prueba, produccion: aquí si deseas enviar en prueba o producción
			);

			//Datos del Receptor del comprobante de pago
			$data['receptor'] = array(
				'ruc' 						=> $contribuyente->ruc, //RUC del contribuyente, de tu cliente
				'tipo_doc' 					=> '6', //no cambiar (6: RUC)
			);

			$ruc_emisor = '20604209987';
			$serie_doc = 'F001';
			$correlativo_doc = 1;

			$id_tipo_cpe = '01';
			$id_tipodoc_proveedor = '6';
			
			$data['token_cliente'] = $this->token_user;
			$data['ruc_emisor'] = $ruc_emisor;
			$data['serie'] = $serie_doc;
			$data['correlativo'] = $correlativo_doc;
			$data['sol_ruc'] = $contribuyente->ruc;
			$data['sol_user'] = '';
			$data['sol_password'] = '';

			$herramientas = new HerramientasController;
			$ruta = 'https://facturalahoy.com/api/facturalaya/recuperar_cpe';
			$response = $herramientas->envio_api_sunat($data, $ruta);
			
			$respuesta_doc = json_decode($response);
			if (isset($respuesta_doc->success) && $respuesta_doc->success == true) {
				if($usuario->id_contribuyente != 1) {
					if($respuesta_doc->result->receptor->num_doc != $contribuyente->ruc) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El comprobante EXISTE, sin embargo no podemos mostrar los datos porque usted no es el receptor.';
						echo json_encode($resp);
						exit();
					}
				}

				$resp['respuesta'] = 'ok';
				$resp['cpe'] = $respuesta_doc->result;
				$resp['unidades_medida'] = $unidadmedida;
				$resp['response'] = $response;
				echo json_encode($resp);
				exit();
			}

			if (isset($respuesta_doc->success) && $respuesta_doc->success == false) {
				if (isset($respuesta_doc->message)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['response'] = $response;
					$resp['mensaje'] = $respuesta_doc->success;
					echo json_encode($resp);
					exit();
				}

				if (isset($respuesta_doc->mensaje)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['response'] = $response;
					$resp['mensaje'] = $respuesta_doc->mensaje;
					echo json_encode($resp);
					exit();
				}
			}
			
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['response'] = $response;
			$resp['mensaje'] = 'No hemos logrado leer el XML, ingresa los datos manualmente...';
			
			echo json_encode($resp);
			exit();
		}
	}

	public function getDataCpeAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
			$datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

			$unidadmedida = SunatUnidadmedida::find();

			$data['token_cliente'] = $this->token_user; //KEY para que puedas consumir nuestra api
			$data['ruc_proveedor'] = $this->ruc_proveedor; //Tu número de RUC, el cuál será responsable por los datos enviados en todos los json

			$data['secret_data'] = array(
				"tipo_certificado"		=> $contribuyente->tipo_certificado, //no cambiar
				"tipo_proceso" 			=> "produccion", //prueba, produccion: aquí si deseas enviar en prueba o producción
			);

			//Datos del Receptor del comprobante de pago
			$data['receptor'] = array(
				'ruc' 						=> $contribuyente->ruc, //RUC del contribuyente, de tu cliente
				'tipo_doc' 					=> '6', //no cambiar (6: RUC)
			);

			$ruc_emisor = !isset($datapost['ruc_emisor'])?'':trim($datapost['ruc_emisor']);
			$serie_doc = !isset($datapost['serie_doc'])?'':trim($datapost['serie_doc']);
			$correlativo_doc = !isset($datapost['correlativo_doc'])?'':trim($datapost['correlativo_doc']);
			$correlativo_doc = preg_replace('/^0+/', '', $correlativo_doc);
			$id_tipo_cpe = !isset($datapost['id_tipo_cpe'])?'':$datapost['id_tipo_cpe'];
			$id_tipodoc_proveedor = !isset($datapost['id_tipodoc_proveedor'])?'':$datapost['id_tipodoc_proveedor'];
			
			$data['ruc_emisor'] = $ruc_emisor;
			$data['serie'] = $serie_doc;
			$data['correlativo'] = $correlativo_doc;
			$data['sol_ruc'] = $contribuyente->ruc;
			$data['sol_user'] = '';
			$data['sol_password'] = '';

			if(isset($contribuyente->user_sol_busq_cpe) && !empty($contribuyente->user_sol_busq_cpe)) {
				$data['sol_user'] = $contribuyente->user_sol_busq_cpe;
			} else {
				if(isset($contribuyente->sunat_u_sol_principal) && !empty($contribuyente->sunat_u_sol_principal)) {
					$data['sol_user'] = $contribuyente->sunat_u_sol_principal;
				} else {
					$data['sol_user'] = '';
				}
			}

			if(isset($contribuyente->pass_sol_busq_cpe) && !empty($contribuyente->pass_sol_busq_cpe)) {
				$data['sol_password'] = $contribuyente->pass_sol_busq_cpe;
			} else {
				if(isset($contribuyente->sunat_p_sol_principal) && !empty($contribuyente->sunat_p_sol_principal)) {
					$data['sol_password'] = $contribuyente->sunat_p_sol_principal;
				} else {
					$data['sol_password'] = '';
				}
			}

			$herramientas = new HerramientasController;
			$ruta = 'https://facturalahoy.com/api/facturalaya/recuperar_cpe';
			$response = $herramientas->envio_api_sunat($data, $ruta);
			
			$respuesta_doc = json_decode($response);
			if (isset($respuesta_doc->success) && $respuesta_doc->success == true) {
				if($usuario->id_contribuyente != 1) {
					if($respuesta_doc->result->receptor->num_doc != $contribuyente->ruc) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El comprobante EXISTE, sin embargo no podemos mostrar los datos porque usted no es el receptor.';
						echo json_encode($resp);
						exit();
					}
				}

				$resp['respuesta'] = 'ok';
				$resp['cpe'] = $respuesta_doc->result;
				$resp['unidades_medida'] = $unidadmedida;
				$resp['response'] = $response;
				echo json_encode($resp);
				exit();
			}

			
			if (isset($respuesta_doc->success) && $respuesta_doc->success == false) {
				if (isset($respuesta_doc->message)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['response'] = $response;
					$resp['mensaje'] = $respuesta_doc->success;
					echo json_encode($resp);
					exit();
				}

				if (isset($respuesta_doc->mensaje)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['response'] = $response;
					$resp['mensaje'] = $respuesta_doc->mensaje;
					echo json_encode($resp);
					exit();
				}
			}
			
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['response'] = $response;
			$resp['mensaje'] = 'No hemos logrado encontrar el comprobante en SUNAT, ingrese manualmente los datos...';
			
			echo json_encode($resp);
			exit();
		}
	}

	public function get_enlaces_pdf_compras($id_contribuyente, $tipo_envio_sunat, $id_compra, $id_tipodoc_electronico) {
	    $herramientas = new HerramientasController;
	       
	    $string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||$id_compra||$tipo_envio_sunat||$id_tipodoc_electronico||a4");
        $string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||$id_compra||$tipo_envio_sunat||$id_tipodoc_electronico||ticket");
        
        $url_a4 = '/facturacionv8/printpdfcompra/?file='.urlencode($string_encrypted_document_a4);
        $url_ticket = '/facturacionv8/printpdfcompra/?file='.urlencode($string_encrypted_document_ticket);
        
        $resp['respuesta'] = 'ok';
        $resp['enlaces'] = array(
            'url_a4'           => $url_a4,
            'url_ticket'    => $url_ticket,
        );

        return $resp;
	}
}