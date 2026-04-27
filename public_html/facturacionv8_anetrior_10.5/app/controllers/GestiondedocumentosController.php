<?php
class GestiondedocumentosController extends ControllerBase {
	public function indexAction() {
		$this->setTitle('Gestión de Documentos - Administración');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css")
            ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/loaders/progressbar.min.js")
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/gestiondedocumentos/txt_validacion_sunat.js?i=".rand())
			->addJs($this->baseUri . "public/js/gestiondedocumentos/index.js?i=".rand());
			
            $this->view->id_tipodoc = '01';
		$contribuyentes = Contribuyente::find(array("tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('tipo_envio_sunat' => 'produccion')));
		$this->view->contribuyentes = $contribuyentes;

        $this->view->id_contribuyente = isset($_GET['id_contribuyente']) ? $_GET['id_contribuyente']:'';
        $this->view->ruc_contribuyente = isset($_GET['ruc_contribuyente']) ? $_GET['ruc_contribuyente']:'';
        $this->view->tipodoc_electronico = isset($_GET['tipodoc_electronico']) ? $_GET['tipodoc_electronico']:'';
	}

    public function procesoTotalDocSunatAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

            $resp = $this->proceso_total_doc_sunat($datapost);
            echo json_encode($resp);
            exit();
        }
    }

    public function proceso_total_doc_sunat($data) {
        //sleep(1.5);

        $id_contribuyente       = $data['id_contribuyente'];
        $id_tipodoc_electronico = $data['id_tipodoc_electronico'];
        $serie_comprobante      = $data['serie_comprobante'];
        $numero_comprobante     = $data['numero_comprobante'];
        $id_row = $data['id_row'];

        $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));
        if(!$documento) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Posiblmente el documento no exista, o se encuentre en una versión de prueba';
            $resp['step_code'] = '1';
            return $resp;
        }

        if($documento->estado_envio_sunat == 'aceptado') {
            if(empty($documento->name_xml_zip) && empty($documento->name_xml_zip)) {
                $factura = new FacturaController;
                $data_generar_xml['id_contribuyente'] = $id_contribuyente;
                $data_generar_xml['id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
                $data_generar_xml['serie_comprobante'] = $serie_comprobante;
                $data_generar_xml['numero_comprobante'] = $numero_comprobante;
                $data_generar_xml['tipo_envio_sistema'] = 'soporte';

                $resp = $factura->enviar_factura_api($data_generar_xml, 'solo_firma', $documento->id_vendedor);
                return $resp;
            }
            
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Ya se encuentra en estado ACEPTADO';
            $resp['step_code'] = '2';
            return $resp;
        }

        if($documento->estado_envio_sunat == 'rechazado') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Ya se encuentra en estado RECHAZADO';
            $resp['step_code'] = '3';
            return $resp;
        }

        if($documento->estado_envio_sunat == 'anulado') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Ya se encuentra en estado ANULADO';
            $resp['step_code'] = '4';
            return $resp;
        }

        $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
        if(!$cliente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
            $resp['step_code'] = '5';
            return $resp;
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            $resp['step_code'] = '6';
            return $resp;
        }

        //validamos el estado del documento en SUNAT:
        $herramientas = new HerramientasController;
        
        if($cliente->num_doc == '00000000') {
            $data_validacion['id_doc_cliente'] = '';
            $data_validacion['num_doc_cliente'] = '';
        } else {
            $data_validacion['id_doc_cliente'] = $cliente->id_tipodocidentidad;
            $data_validacion['num_doc_cliente'] = $cliente->num_doc;
        }

        $data_validacion['num_ruc'] = $contribuyente->ruc;
        $data_validacion['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
        $data_validacion['documento_serie'] = $serie_comprobante;
        $data_validacion['documento_numero'] = $numero_comprobante;
        $data_validacion['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
        $data_validacion['documento_total'] = $documento->total;
        $data_validacion['id_row'] = $id_row;
        
        if($id_tipodoc_electronico != '09') {
            $ruta = 'https://facturalahoy.com/api/estadodocumento';
            $resp_api = $herramientas->envio_api_sunat($data_validacion, $ruta);
    
            $resp_api_2 = $resp_api;
            $resp_api = json_decode($resp_api);
            $resp_api = json_decode($resp_api);
    
            $resp_validacion_sunat = '';
            if(empty($resp_api) || !isset($resp_api->rpta) || $resp_api->rpta == 0) {
                $resp_validacion_sunat = 'error_validacion_sunat';
            } else {
                if($resp_api->data->estadoCp == '1') {
                    $resp_validacion_sunat = 'aprobado';
                } elseif ($resp_api->data->estadoCp == '2') {
                    $resp_validacion_sunat = 'anulado';
                } else {
                    $resp_validacion_sunat = 'pendiente';
                }
            }
        }

        if($id_tipodoc_electronico == '09') {
            $resp_validacion_sunat = 'pendiente';
        }

        if($resp_validacion_sunat == 'anulado') {
            //anulación_manual
            $resp = $this->modificar_comprobante($contribuyente, $documento, 'anular_manualmente', 'si', '');
            $resp['step_code'] = '7';
            $resp['data_documento'] = $data_validacion;
            $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
            return $resp;
        }

        if($resp_validacion_sunat == 'aprobado') {
            if($documento->id_tipodoc_electronico == '01' || ($documento->id_tipodoc_electronico == '07' && $documento->id_tipo_comprobante_modifica == '01') || ($documento->id_tipodoc_electronico == '08' && $documento->id_tipo_comprobante_modifica == '01')) {
                $resp = $this->modificar_comprobante($contribuyente, $documento, 'recover_cdr', 'si', '');
                $resp['step_code'] = '8';
                $resp['data_documento'] = $data_validacion;
                $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                return $resp;
            } else if($documento->id_tipodoc_electronico == '03' || ($documento->id_tipodoc_electronico == '07' && $documento->id_tipo_comprobante_modifica == '03') || ($documento->id_tipodoc_electronico == '08' && $documento->id_tipo_comprobante_modifica == '03')) {
                $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->rb_id_contribuyente, 'codigo' => $documento->rb_codigo, 'serie' => $documento->rb_serie, 'secuencia' => $documento->rb_secuencia, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

                $numero_ticket = '';
                if($resumen) {
                    $numero_ticket = $resumen->numero_ticket;
                }

                if($numero_ticket == '') {
                    $resp = $this->modificar_comprobante($contribuyente, $documento, 'aprobar_manualmente', 'si', '');
                    $resp['step_code'] = '9';
                    $resp['data_documento'] = $data_validacion;
                    $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                    return $resp;
                } else {
                    $resp = $this->consultar_ticket($id_contribuyente, $numero_ticket, $documento->id_vendedor);
                    if($resp['respuesta'] == 'ok') {
                        $resp['step_code'] = '10';
                        $resp['data_documento'] = $data_validacion;
                        $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                        return $resp;
                    }

                    $resp = $this->modificar_comprobante($contribuyente, $documento, 'aprobar_manualmente', 'si', '');
                    $resp['step_code'] = '11';
                    $resp['data_documento'] = $data_validacion;
                    $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                    return $resp;
                }
            } else if($documento->id_tipodoc_electronico == '09') {
                
            }

            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El DOCUMENTO se encuentra en estado APROBADO en sunat, pero no se pudo modificar en la BD';
            $resp['step_code'] = '12';
            $resp['data_documento'] = $data_validacion;
            $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
            return $resp;
        }

        //Enviamos los comprobantes como corresponda.
        if($documento->id_tipodoc_electronico == '01') {
            $factura = new FacturaController;
            $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
            $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
            $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
            $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
            $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';

            $resp = $factura->enviar_factura_api($resp_guardado_bd, 'inmediato', $documento->id_vendedor);
            $resp['step_code'] = '13';
            $resp['data_documento'] = $data_validacion;
            $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
            return $resp;
        } else if($documento->id_tipodoc_electronico == '03') {
            //puede estar en estado ticket o puede estar en pendiente.
            $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->rb_id_contribuyente, 'codigo' => $documento->rb_codigo, 'serie' => $documento->rb_serie, 'secuencia' => $documento->rb_secuencia, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

            $numero_ticket = '';
            if($resumen) {
                $numero_ticket = $resumen->numero_ticket;
            }

            if($numero_ticket == '') {
                $data_doc_bd['id_contribuyente'] = $id_contribuyente;
                $data_doc_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $data_doc_bd['serie_comprobante'] = $serie_comprobante;
                $data_doc_bd['numero_comprobante'] = $numero_comprobante;
                $data_doc_bd['idusuario'] = $documento->id_vendedor;
                $accion = 1;
                $resumenboletas = new ResumendeboletasController;
                $resp = $resumenboletas->crear_resumen_individual($data_doc_bd, $accion, 'si');
                $resp['step_code'] = '14';
                $resp['data_documento'] = $data_validacion;
                $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                return $resp;
            } else {
                $resp_consultar_ticket = $this->consultar_ticket($id_contribuyente, $numero_ticket, $documento->id_vendedor);
                $resp_consultar_ticket['step_code'] = '15';
                $resp_consultar_ticket['data_documento'] = $data_validacion;
                if($resp_consultar_ticket['respuesta'] == 'ok') {
                    return $resp_consultar_ticket;
                }

                $documento->estado_envio_sunat = 'pendiente';
                
                try {
                    if(!$documento->save()) {
                        $msg = '';
                        foreach ($documento->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
        
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                        return $resp;    
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                    return $resp;
                }
                
                $data_doc_bd['id_contribuyente'] = $id_contribuyente;
                $data_doc_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $data_doc_bd['serie_comprobante'] = $serie_comprobante;
                $data_doc_bd['numero_comprobante'] = $numero_comprobante;
                $data_doc_bd['idusuario'] = $documento->id_vendedor;
                $accion = 1;
                $resumenboletas = new ResumendeboletasController;
                $resp = $resumenboletas->crear_resumen_individual($data_doc_bd, $accion, 'si');
                $resp['step_code'] = '16';
                $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                $resp['resp_consultar_ticket'] = $resp_consultar_ticket;
                return $resp;
            }
        } else if($documento->id_tipodoc_electronico == '09') {
            $guia_remision = new GuiaderemisionController;
            $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
            $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
            $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
            $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;

            $resp_guia_remision = $guia_remision->enviar_guia_remision_api($resp_guardado_bd, 'inmediato');
            echo json_encode($resp_guia_remision);
            exit();
        } else if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08') {
            if($documento->id_tipo_comprobante_modifica == '01') {
                if($documento->id_tipodoc_electronico == '07') {
                    $nota_credito = new NotadecreditoController;
                    $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                    $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                    $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                    $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
    
                    $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';
    
                    $resp = $nota_credito->enviar_notacredito_api($resp_guardado_bd, 'inmediato');
                    $resp['step_code'] = '17';
                    $resp['data_documento'] = $data_validacion;
                    $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                    return $resp;
                } else if($documento->id_tipodoc_electronico == '08') {
                    $nota_debito = new NotadedebitoController;
                    $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                    $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                    $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                    $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
    
                    $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';
    
                    $resp = $nota_debito->enviar_notadebito_api($resp_guardado_bd, 'inmediato');
                    $resp['step_code'] = '18';
                    $resp['data_documento'] = $data_validacion;
                    $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                    return $resp;
                }
            } else {
                //Notas de crédito y débito de boletas.

                //puede estar en estado ticket o puede estar en pendiente.
                $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->rb_id_contribuyente, 'codigo' => $documento->rb_codigo, 'serie' => $documento->rb_serie, 'secuencia' => $documento->rb_secuencia, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

                $numero_ticket = '';
                if($resumen) {
                    $numero_ticket = $resumen->numero_ticket;
                }

                if($numero_ticket == '') {
                    $data_doc_bd['id_contribuyente'] = $id_contribuyente;
                    $data_doc_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                    $data_doc_bd['serie_comprobante'] = $serie_comprobante;
                    $data_doc_bd['numero_comprobante'] = $numero_comprobante;
                    $data_doc_bd['idusuario'] = $documento->id_vendedor;
                    $accion = 1;
                    $resumenboletas = new ResumendeboletasController;
                    $resp = $resumenboletas->crear_resumen_individual($data_doc_bd, $accion, 'si');
                    $resp['step_code'] = '18.1';
                    $resp['data_documento'] = $data_validacion;
                    $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                    return $resp;
                } else {
                    $resp = $this->consultar_ticket($id_contribuyente, $numero_ticket, $documento->id_vendedor);
                    $resp['step_code'] = '18.2';
                    $resp['data_documento'] = $data_validacion;
                    if($resp['respuesta'] == 'ok') {
                        return $resp;
                    }
                    
                    $data_doc_bd['id_contribuyente'] = $id_contribuyente;
                    $data_doc_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                    $data_doc_bd['serie_comprobante'] = $serie_comprobante;
                    $data_doc_bd['numero_comprobante'] = $numero_comprobante;
                    $data_doc_bd['idusuario'] = $documento->id_vendedor;
                    $accion = 1;
                    $resumenboletas = new ResumendeboletasController;
                    $resp = $resumenboletas->crear_resumen_individual($data_doc_bd, $accion, 'si');
                    $resp['step_code'] = '18.3';
                    $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
                    return $resp;
                }
                
            }
        }

        $resp['respuesta'] = 'error';
        $resp['titulo'] = 'Error';
        $resp['mensaje'] = 'no se realizó ninguna acción';
        $resp['step_code'] = '19';
        $resp['data_documento'] = $data_validacion;
        $resp['resp_validacion_sunat'] = $resp_validacion_sunat;
        return $resp;
    }

    public function getListaDocsSunatAction() {        
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $inicio_proceso = microtime(true);
            
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

			if($contribuyente->id_contribuyente != 1) {
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tiene permiso';
                echo json_encode($resp);
                exit();
			}

            $tipos_comprobantes_validos = array('03', '01', '07', '08', '09', '31');
			$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado', 'xml');
            
            $tipos_comprobantes = empty($datapost['tipo_documento'])?array():$datapost['tipo_documento'];
            $estados_envio_sunat = empty($datapost['estado_documento'])?array():$datapost['estado_documento'];
            $ids_contribuyentes = empty($datapost['id_contribuyente'])?array():array_map('intval', $datapost['id_contribuyente']);
            $ids_contribuyentes_excluidos = empty($datapost['id_contribuyente_excluir'])?array():array_map('intval', $datapost['id_contribuyente_excluir']);
            $ids_contribuyentes_excluidos[] = 6236;
            $ids_contribuyentes_excluidos[] = 7890;
            $ids_contribuyentes_excluidos[] = 4090;
            $ids_contribuyentes_excluidos[] = 6394;
            $ids_contribuyentes_excluidos[] = 4350;
            $ids_contribuyentes_excluidos[] = 6312;
            
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

            $estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

            $fecha_actual = date("Y-m-d H:i:s");
            $fecha_inicio_b = date("Y-m-d", strtotime("-29 days", strtotime($fecha_actual)));
            $fecha_fin_b = date("Y-m-d");
            
            $fecha_inicio = ($datapost['fecha_inicio'] == '')?$fecha_inicio_b:$datapost['fecha_inicio'];
			$fecha_fin = ($datapost['fecha_fin'] == '')?$fecha_fin_b:$datapost['fecha_fin'];
			
            if(!$this->validate_date($fecha_inicio)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }
            if(!$this->validate_date($fecha_fin)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
                echo json_encode($resp);
                exit();
			}

            $fecha_inicio_date_time = $fecha_inicio.' 00:00:00';
            $fecha_fin_date_time = $fecha_fin.' 23:59:59';
			
            $tipo_envio_sunat = 'produccion';

            $query_1 = " (fecha_registro BETWEEN '$fecha_inicio_date_time' AND '$fecha_fin_date_time') and tipo_envio_sunat = 'produccion' ";
			
            if(count($ids_contribuyentes) > 0){ 
                $query_1 = $query_1." and id_contribuyente in (".implode(",", $ids_contribuyentes).") "; 
            } else {
                $query_1 = $query_1." and ignorar_documento <> 'si' "; 
            }

            if(count($ids_contribuyentes_excluidos) > 0){ 
                $query_1 = $query_1." and id_contribuyente not in (".implode(",", $ids_contribuyentes_excluidos).") "; 
            }

            if(count($estados_envio_sunat) > 0){ 
                if(in_array('xml', $estados_envio_sunat)) {
                    $query_1 = $query_1.' and (name_xml_zip is NULL or name_xml_zip = "") and estado_envio_sunat <> "pendiente" ';
                } else {
                    $query_1 = $query_1." and estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') ";
                }
            }
            
            if(count($tipos_comprobantes) > 0) {
                $query_1 = $query_1." and id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') ";
            } else {
				$query_1 = $query_1." and id_tipodoc_electronico <> '09'";
			}

            $total_filas_extraidas = intval($datapost['length']) - intval($datapost['start']);
            
            if($total_filas_extraidas <= 500) {
                $query_1 = "SELECT count(*) total FROM `doc_electronico` where $query_1";

                try {
                    $sentencia = $this->db->prepare($query_1);
                    $sentencia->execute();
                    while ($totales = $sentencia->fetch()) {
                        $totales = (object)$totales;
                        $total_registros = $totales->total;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                    exit();
                }
            } else {
                $total_registros = 23000;
            }

            $columnas = array( 
                //indice de la columna en datatable => nombre de la columna
                0 => '`de`.`fecha_registro`',
                1 => '`de`.`fecha_comprobante`',
                2 => 'ruc_nom_clie',
                3 => '`de`.`total`',
                4 => '`de`.`fecha_registro`',
                5 => '`de`.`fecha_registro`',
                6 => '`de`.`fecha_registro`',
                7 => '`de`.`fecha_registro`',
                8 => '`de`.`fecha_registro`'
            );

            $query = "SELECT 
            de.id_contribuyente, 
            de.id_tipo_operacion, 
            de.id_tipodoc_electronico, 
            de.serie_comprobante, 
            de.numero_comprobante, 
            de.tipo_envio_sunat, 
            de.total, 
            de.nro_otr_comprobante, 
            de.fecha_comprobante, 
            de.fecha_vto_comprobante, 
            de.id_codigomoneda, 
            de.idcliente, 
            de.id_vendedor, 
            de.id_sucursal, 
            de.fecha_registro, 
            de.id_tipo_comprobante_modifica, 
            de.serie_documento_modifica, 
            de.nro_documento_modifica, 
            de.id_condicionpago, 
            de.monto_adeudado, 
            de.fecha_pagopendiente, 
            de.fecha_envio_sunat, 
            de.estado_envio_sunat, 
            de.hash_cpe, 
            de.hash_cdr, 
            de.cod_sunat, 
            de.msje_sunat, 
            de.ruta_xml, 
            de.name_xml, 
            de.name_xml_zip, 
            de.name_cdr, 
            de.name_cdr_zip, 
            de.estado_documento, 
            de.rb_id_contribuyente, 
            de.rb_codigo, 
            de.rb_serie, 
            de.rb_secuencia, 
            de.rb_tipo_envio_sunat,
            clie.num_doc num_doc_cliente,
            clie.razon_social razon_social_cliente,
            clie.email email_cliente,
            CONCAT(
              de.serie_comprobante, '-', de.numero_comprobante
            ) as doc_serie_numero, 
            CONCAT(
              clie.num_doc, ' ', clie.razon_social
            ) as ruc_nom_clie
          FROM 
            doc_electronico de 
            INNER JOIN cliente clie ON de.idcliente = clie.idcliente 
          where (de.fecha_comprobante BETWEEN :fecha_inicio AND :fecha_fin)
            and de.tipo_envio_sunat = 'produccion' 
          ";

            if(count($ids_contribuyentes) > 0){ 
                $query = $query." and de.id_contribuyente in (".implode(",", $ids_contribuyentes).") "; 
            } else {
                $query = $query."  and de.ignorar_documento <> 'si' "; 
            }

            if(count($ids_contribuyentes_excluidos) > 0){ 
                $query = $query." and de.id_contribuyente not in (".implode(",", $ids_contribuyentes_excluidos).") "; 
            }

            if(count($estados_envio_sunat) > 0){ 
                if(in_array('xml', $estados_envio_sunat)) {
                    $query = $query.' and (de.name_xml_zip is NULL or de.name_xml_zip = "") and de.estado_envio_sunat <> "pendiente" ';
                } else {
                    $query = $query." and de.estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') ";
                }
            }
            
            if(count($tipos_comprobantes) > 0) {
                $query = $query." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') ";
            } else {
				$query = $query." and de.id_tipodoc_electronico <> '09'";
			}

            if(!empty($datapost['search']['value'])) {
                $query = $query." AND (
                    CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                    CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or 
                    de.transporte_nro_placa like :termino or 
                    de.nro_otr_comprobante like :termino or 
                    de.id_codigomoneda like :termino
                    ) ";
            }
            
            //de.transporte_nro_placa like :termino or de.nro_otr_comprobante like :termino or de.id_codigomoneda like :termino
            if($total_filas_extraidas <= 500) {
                $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $datapost['search']['value']);
            } else {
                $total_filas_filtradas = $total_filas_extraidas*3;
            }

            //$query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
            $query_ordenar_por = " ORDER BY de.total DESC LIMIT ".intval($datapost['start']).",".intval($datapost['length'])." ";
            if(isset($datapost['order'][0]['column'])) {
                if($datapost['order'][0]['column'] == 1) {
                    $query_ordenar_por = " ORDER BY de.fecha_comprobante ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])." ";
                }
            }
            
            $query = $query.$query_ordenar_por;

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                //$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
                if(!empty($datapost['search']['value'])) {
                    $termino_busqueda = '%'.$datapost['search']['value'].'%';
                    $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
                }
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $reportedocumentos = new ReportedocumentosController;
			$herramientas = new HerramientasController;
            

            $array_lista = array();
            $array_lista_ids = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
				$item = (object)$fila;
				$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $item->id_contribuyente)));

                $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
                
                $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||a4");
                $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||ticket");

                $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		        $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

                //botón para ver e imprimir el pdf del documento electrónico
                $btn_pdf = '
                <a title="Formato A4" target="_blank" href="'.$url_a4.'"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px;"></a>
                
                <a title="Formato Ticket" target="_blank" href="'.$url_ticket.'"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px;"></a>
                ';

                //botón para ver y descargar el xml_firmado del documento electrónico
                if($item->tipo_envio_sunat == 'produccion') {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
                } else {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
                }

                $html_subtitulo_doc = '';
                if($item->id_tipodoc_electronico == '01') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                } else if($item->id_tipodoc_electronico == '03') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                } else if($item->id_tipodoc_electronico == '07') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_credito/';
                    $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-blue position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
                } else if($item->id_tipodoc_electronico == '08') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_debito/';
                    $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-danger position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
                }
                
                if(!empty($item->name_xml_zip)) {
                    if (file_exists($ruta_base_zip_cpe_cdr.$item->name_xml_zip)) {
                        //Existe el XML FIRMADO
                        $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/'.$item->id_contribuyente.'/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'/xml_cpe_zip"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="width: 30px;"></a>';
                    } else {
                        $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                    }
                } else {
                    $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                }

                if($total_filas_extraidas <= 500) {
                    $menu_opciones = $reportedocumentos->get_options_button($item);
                    $btn_cdr = $reportedocumentos->get_cdr_button($ruta_base_zip_cpe_cdr, $item, $tipo_envio_sunat);
                    $btn_sunat = $reportedocumentos->get_sunat_button($ruta_base_zip_cpe_cdr, $item, $tipo_envio_sunat, $tipo_comprobante->descripcion);
                } else {
                    $menu_opciones = '';
                    $btn_cdr = '';
                    $btn_sunat = '';
                }

                if($item->estado_envio_sunat == 'aceptado') {
                    $menu_opciones = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                '.$menu_opciones.'
                                <li>
                                <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$item->email_cliente."'".')"><i class="icon-envelop2 icon-success"></i> Enviar CPE Via Email</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    ';
                } else {
                    $menu_opciones = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                <li>
                                    <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$item->email_cliente."'".')"><i class="icon-envelop2 icon-success"></i> Enviar CPE Via Email</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick=\'ver_estado_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><img src="/facturacionv8/img/sunat_logo.png" /> Verificar Estado en SUNAT</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="ignorar_documento('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.')"><i class="icon-eye-blocked2"></i> Ignorar Documento para Siempre</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    ';
                }

                $texto_placa_vehiculo = '';
                $texto_num_orden = '';
                //$texto_moneda = '';

                if(!empty($item->transporte_nro_placa)) {
                    $texto_placa_vehiculo = '<span class="label bg-success-400">Nro. Placa: '.$item->transporte_nro_placa.'</span>';
                }
                
                if(!empty($item->nro_otr_comprobante)) {
                    $texto_num_orden = '<span style="margin-left: 4px;" class="label bg-primary-400">Nro Orden: '.$item->nro_otr_comprobante.'</span>';
                }

                if($texto_placa_vehiculo == '' && $texto_num_orden == '') {
                    $texto_num_orden_placa = '';
                } else {
                    $texto_num_orden_placa = $texto_placa_vehiculo.$texto_num_orden.'<br/>';
                }
                
                $numero_ticket = '';
                
                $datos_contribuyente = '<a target="_blank" href="/facturacionv8/gestiondedocumentos?id_contribuyente='.$contribuyente->id_contribuyente.'&ruc_contribuyente='.$contribuyente->ruc.'&tipodoc_electronico='.$item->id_tipodoc_electronico.'"> => </a><span class="label bg-success-400">Contribuyente: '.$contribuyente->ruc.'</span>';
                $user_admin = Usuario::findFirst(array("id_rol in (1, 2, 3, 5) and id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
                if($user_admin) {
                    $cadena_encriptada = $herramientas->encriptar('FACTURALAYAAAAA||'.$user_admin->idusuario);
                } else {
                    $cadena_encriptada = $herramientas->encriptar('FACTURALAYAAAAA||89888888888');
                }
                
                $parametros_fecha = "$item->id_contribuyente, '$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante, '$item->tipo_envio_sunat'";

                $fechas_documento = '
                <span><i class="fa fa-caret-right position-left color-indigo"></i>F.Reg.: '.date("d-m-Y / H:i A", strtotime($item->fecha_registro)).'</span>
                <br />
                ';

                if($item->id_tipodoc_electronico == '01' || $item->id_tipodoc_electronico == '07' || $item->id_tipodoc_electronico == '08') {
                    $fechas_documento = $fechas_documento.'<span onclick="editar_fecha_comprobante('."'".date("d/m/Y", strtotime($item->fecha_comprobante))."'".', '.$parametros_fecha.')" style="cursor: pointer; text-decoration-color: #EA215A; text-decoration-thickness: .125em; text-underline-offset: 1.5px; border-bottom: #2196f3 0.130em dashed;"><i class="fa fa-caret-right position-left color-indigo"></i>F.Documento: '.date("d-m-Y", strtotime($item->fecha_comprobante)).'</span>';
                } else {
                    $fechas_documento = $fechas_documento.'<span><i class="fa fa-caret-right position-left color-indigo"></i>F.Documento: '.date("d-m-Y", strtotime($item->fecha_comprobante)).'</span>';
                }

                $sunat_tipooperacion = SunatTipooperacion::findFirst(array("id_codigotipooperacion = :id_codigotipooperacion:", "bind" => array("id_codigotipooperacion" => $item->id_tipo_operacion)));
                $tipo_operacion_doc = "";
                if($sunat_tipooperacion) {
                    $tipo_operacion_doc = '<br><span><i class="fa fa-caret-right position-left color-indigo"></i>
                    '.$sunat_tipooperacion->descripcion.'</span>';
                }

                $n++;
                $dt_row_id = $item->id_contribuyente.'||'.$item->id_tipodoc_electronico.'||'.$item->serie_comprobante.'||'.$item->numero_comprobante.'||'.$item->tipo_envio_sunat.'||'.$item->estado_envio_sunat.'||'.$numero_ticket;
                $dt_row_id_guion = str_replace('||', '_', $dt_row_id);
                $array_lista[] = array(
                    'DT_RowId' => $dt_row_id,
					'fecha_registro' => $fechas_documento.$tipo_operacion_doc."<div id='fecha_$dt_row_id_guion'></div>", 
					'comprobante' => $datos_contribuyente.'<br />'.$tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante.'<br /><span class="label_razonsocial text-success"><i class="fa fa-caret-right position-left color-indigo"></i> <a target="_blank" href="/facturacionv8/login/inicio_sesion_remoto?user='.$cadena_encriptada.'">Click para Ingresar</a></span>'."<div id='doc_$dt_row_id_guion'></div>", 
                    'cliente' => $item->num_doc_cliente.'<br />'.$item->razon_social_cliente."<div id='clie_$dt_row_id_guion'></div>", 
                    'total' => $item->id_codigomoneda.' '.$item->total, 
                    'pdf' => $btn_pdf, 
                    'xml' => $btn_xml, 
                    'cdr' => $btn_cdr, 
                    'sunat' => $btn_sunat, 
                    'opciones' => $menu_opciones
                );
            }
            
            $json_data = array(
                "draw"            => intval($datapost['draw']),
                "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
                "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
                "data"            => $array_lista,   // total data array
                "numero_n"        => $n,
                "time_proceso"    => microtime(true) - $inicio_proceso
            );

            echo json_encode($json_data);
            exit();
        }
    }

	public function get_lista_docs_sunat2222Action() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $inicio_proceso = microtime(true);
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

			if($contribuyente->id_contribuyente != 1) {
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tiene permiso';
                echo json_encode($resp);
                exit();
			}

            $tipos_comprobantes_validos = array('03', '01', '07', '08', '77', '88');
			$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado', 'xml');
            
            $tipos_comprobantes = empty($datapost['tipo_documento'])?array():$datapost['tipo_documento'];
            $estados_envio_sunat = empty($datapost['estado_documento'])?array():$datapost['estado_documento'];
            $ids_contribuyentes = empty($datapost['id_contribuyente'])?array():array_map('intval', $datapost['id_contribuyente']);
            $ids_contribuyentes_excluidos = empty($datapost['id_contribuyente_excluir'])?array():array_map('intval', $datapost['id_contribuyente_excluir']);
            
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

            $estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

            $fecha_actual = date("Y-m-d H:i:s");
            $fecha_inicio_b = date("Y-m-d", strtotime("-29 days", strtotime($fecha_actual)));
            $fecha_fin_b = date("Y-m-d");
            
            $fecha_inicio = ($datapost['fecha_inicio'] == '')?$fecha_inicio_b:$datapost['fecha_inicio'];
			$fecha_fin = ($datapost['fecha_fin'] == '')?$fecha_fin_b:$datapost['fecha_fin'];
			
            if(!$this->validate_date($fecha_inicio)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }
            if(!$this->validate_date($fecha_fin)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
                echo json_encode($resp);
                exit();
			}
			
            $tipo_envio_sunat = 'produccion';


            
			$query_1 = "CAST(fecha_registro AS DATE) >= CAST('$fecha_inicio' AS DATE) and CAST(fecha_registro AS DATE) <= CAST('$fecha_fin' AS DATE) and tipo_envio_sunat = 'produccion' and ignorar_documento <> 'si' ";
			
            if(count($ids_contribuyentes) > 0){ 
                $query_1 = $query_1." and id_contribuyente in (".implode(",", $ids_contribuyentes).") "; 
            }

            if(count($ids_contribuyentes_excluidos) > 0){ 
                $query_1 = $query_1." and id_contribuyente not in (".implode(",", $ids_contribuyentes_excluidos).") "; 
            }

            if(count($estados_envio_sunat) > 0){ 
                if(in_array('xml', $estados_envio_sunat)) {
                    $query_1 = $query_1.' and (name_xml_zip is NULL or name_xml_zip = "") and estado_envio_sunat <> "pendiente" ';
                } else {
                    $query_1 = $query_1." and estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') ";
                }
            }
            
            if(count($tipos_comprobantes) > 0) {
                $query_1 = $query_1." and id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') ";
            } else {
				$query_1 = $query_1." and id_tipodoc_electronico <> '09'";
			}
            
            $query_1 = "SELECT count(*) total FROM `doc_electronico` where $query_1";

            try {
                $sentencia = $this->db->prepare($query_1);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada (get_lista_docs_sunat2222Action): ',  $e->getMessage(), "\n";
                exit();
            }

            while ($totales = $sentencia->fetch()) {
                $totales = (object)$totales;
                $total_registros = $totales->total;
            }

            $columnas = array( 
                //indice de la columna en datatable => nombre de la columna
                0 => '`de`.`fecha_registro`',
                1 => 'doc_serie_numero',
                2 => 'ruc_nom_clie',
                3 => '`de`.`total`',
                4 => '`de`.`fecha_registro`',
                5 => '`de`.`fecha_registro`',
                6 => '`de`.`fecha_registro`',
                7 => '`de`.`fecha_registro`',
                8 => '`de`.`fecha_registro`'
            );

            $query = "SELECT de.id_contribuyente, de.id_tipo_operacion, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.tipo_envio_sunat, de.total, de.nro_otr_comprobante, de.fecha_comprobante, de.fecha_vto_comprobante, de.id_codigomoneda, de.idcliente, de.id_vendedor, de.id_sucursal, de.fecha_registro, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, de.id_condicionpago, de.monto_adeudado, de.fecha_pagopendiente, de.fecha_envio_sunat, de.estado_envio_sunat, de.hash_cpe, de.hash_cdr, de.cod_sunat, de.msje_sunat, de.ruta_xml, de.name_xml, de.name_xml_zip, de.name_cdr, de.name_cdr_zip, de.estado_documento, de.rb_id_contribuyente, de.rb_codigo, de.rb_serie, de.rb_secuencia, de.rb_tipo_envio_sunat, contribuyente.ruc, CONCAT(de.serie_comprobante,'-',de.numero_comprobante) as doc_serie_numero, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, cpago.tipo, cpago.condicionpago FROM doc_electronico de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago INNER JOIN contribuyente on de.id_contribuyente = contribuyente.id_contribuyente where CAST(de.fecha_comprobante AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_comprobante AS DATE) <= CAST(:fecha_fin AS DATE) and contribuyente.tipo_envio_sunat = 'produccion' and de.tipo_envio_sunat = 'produccion' and de.ignorar_documento <> 'si' ";

            if(count($ids_contribuyentes) > 0){ 
                $query = $query." and de.id_contribuyente in (".implode(",", $ids_contribuyentes).") "; 
            }

            if(count($ids_contribuyentes_excluidos) > 0){ 
                $query = $query." and de.id_contribuyente not in (".implode(",", $ids_contribuyentes_excluidos).") "; 
            }

            if(count($estados_envio_sunat) > 0){ 
                if(in_array('xml', $estados_envio_sunat)) {
                    $query = $query.' and (de.name_xml_zip is NULL or de.name_xml_zip = "") and de.estado_envio_sunat <> "pendiente" ';
                } else {
                    $query = $query." and de.estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') ";
                }
            }
            
            if(count($tipos_comprobantes) > 0) {
                $query = $query." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') ";
            } else {
				$query = $query." and de.id_tipodoc_electronico <> '09'";
			}

            if(!empty($datapost['search']['value'])) {
                $query = $query." AND (
                    CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                    CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or 
                    de.transporte_nro_placa like :termino or 
                    de.nro_otr_comprobante like :termino or 
                    de.id_codigomoneda like :termino or cpago.tipo like :termino or cpago.condicionpago like :termino or 
					contribuyente.ruc like :termino
                    ) ";
            }

            $query = str_replace(':fecha_inicio', "'".$fecha_inicio."'", $query);
            $query = str_replace(':fecha_fin', "'".$fecha_fin."'", $query);

            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $query = str_replace(':termino', "'".$termino_busqueda."'", $query);
            }
            
            echo $query;
            exit();
            
            //de.transporte_nro_placa like :termino or de.nro_otr_comprobante like :termino or de.id_codigomoneda like :termino
            $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $datapost['search']['value']);

            //$query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
            $query = $query." ORDER BY de.total DESC LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";



            echo $query;
            exit();
            
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                //$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
                if(!empty($datapost['search']['value'])) {
                    $termino_busqueda = '%'.$datapost['search']['value'].'%';
                    $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
                }
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
			
			$reportedocumentos = new ReportedocumentosController;
			$herramientas = new HerramientasController;

            $array_lista = array();
            $array_lista_ids = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
				$item = (object)$fila;
				$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $item->id_contribuyente)));

                $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
                $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
                $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));

                $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||a4");
                $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||ticket");

                $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		        $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

                //botón para ver e imprimir el pdf del documento electrónico
                $btn_pdf = '
                <a title="Formato A4" target="_blank" href="'.$url_a4.'"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px;"></a>
                
                <a title="Formato Ticket" target="_blank" href="'.$url_ticket.'"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px;"></a>
                ';

                //botón para ver y descargar el xml_firmado del documento electrónico
                if($item->tipo_envio_sunat == 'produccion') {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
                } else {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
                }

                $html_subtitulo_doc = '';
                if($item->id_tipodoc_electronico == '01') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                    $resp_verificacion_notas = $reportedocumentos->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                    $html_subtitulo_doc = $resp_verificacion_notas['html_nota_credito'].$resp_verificacion_notas['html_nota_debito'];
                } else if($item->id_tipodoc_electronico == '03') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                    $resp_verificacion_notas = $reportedocumentos->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                    $html_subtitulo_doc = $resp_verificacion_notas['html_nota_credito'].$resp_verificacion_notas['html_nota_debito'];
                } else if($item->id_tipodoc_electronico == '07') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_credito/';
                    $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-blue position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
                } else if($item->id_tipodoc_electronico == '08') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_debito/';
                    $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-danger position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
                }
                
                if(!empty($item->name_xml_zip)) {
                    if (file_exists($ruta_base_zip_cpe_cdr.$item->name_xml_zip)) {
                        //Existe el XML FIRMADO
                        $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/'.$item->id_contribuyente.'/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'/xml_cpe_zip"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="width: 30px;"></a>';
                    } else {
                        $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                    }
                } else {
                    $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                }

                $menu_opciones = $reportedocumentos->get_options_button($item);
                $btn_cdr = $reportedocumentos->get_cdr_button($ruta_base_zip_cpe_cdr, $item, $tipo_envio_sunat);
                $btn_sunat = $reportedocumentos->get_sunat_button($ruta_base_zip_cpe_cdr, $item, $tipo_envio_sunat, $tipo_comprobante->descripcion);

                if($item->estado_envio_sunat == 'aceptado') {
                    $menu_opciones = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                '.$menu_opciones.'
                                <li>
                                <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar CPE Via Email</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    ';
                } else {
                    $menu_opciones = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                <li>
                                    <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar CPE Via Email</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick=\'ver_estado_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><img src="/facturacionv8/img/sunat_logo.png" /> Verificar Estado en SUNAT</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="ignorar_documento('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.')"><i class="icon-eye-blocked2"></i> Ignorar Documento para Siempre</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    ';
                }

                $texto_placa_vehiculo = '';
                $texto_num_orden = '';
                //$texto_moneda = '';

                if(!empty($item->transporte_nro_placa)) {
                    $texto_placa_vehiculo = '<span class="label bg-success-400">Nro. Placa: '.$item->transporte_nro_placa.'</span>';
                }
                
                if(!empty($item->nro_otr_comprobante)) {
                    $texto_num_orden = '<span style="margin-left: 4px;" class="label bg-primary-400">Nro Orden: '.$item->nro_otr_comprobante.'</span>';
                }

                if($texto_placa_vehiculo == '' && $texto_num_orden == '') {
                    $texto_num_orden_placa = '';
                } else {
                    $texto_num_orden_placa = $texto_placa_vehiculo.$texto_num_orden.'<br/>';
                }
                
                $html_condicion_pago = '';
                if(!empty($item->id_condicionpago)) {
                    $html_condicion_pago = $reportedocumentos->get_html_condicionpago($item->id_condicionpago, $item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante, $item->fecha_pagopendiente, $item->monto_adeudado).'<br />';
                }

                $numero_ticket = '';
                if($item->id_tipodoc_electronico == '03') {
                    $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->rb_id_contribuyente, 'codigo' => $item->rb_codigo, 'serie' => $item->rb_serie, 'secuencia' => $item->rb_secuencia, 'tipo_envio_sunat' => $item->tipo_envio_sunat)));

                    if($resumen) {
                        $numero_ticket = $resumen->numero_ticket;
                    }
                }
                
                $datos_contribuyente = '<a target="_blank" href="/facturacionv8/gestiondedocumentos?id_contribuyente='.$contribuyente->id_contribuyente.'&ruc_contribuyente='.$contribuyente->ruc.'&tipodoc_electronico='.$item->id_tipodoc_electronico.'"> => </a><span class="label bg-success-400">Contribuyente: '.$contribuyente->ruc.'</span>';
                $user_admin = Usuario::findFirst(array("id_rol in (1, 2, 3, 5) and id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
                if($user_admin) {
                    $cadena_encriptada = $herramientas->encriptar('FACTURALAYAAAAA||'.$user_admin->idusuario);
                } else {
                    $cadena_encriptada = $herramientas->encriptar('FACTURALAYAAAAA||89888888888');
                }
                
                $parametros_fecha = "$item->id_contribuyente, '$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante, '$item->tipo_envio_sunat'";

                $fechas_documento = '
                <span><i class="fa fa-caret-right position-left color-indigo"></i>F.Reg.: '.date("d-m-Y / H:i A", strtotime($item->fecha_registro)).'</span>
                <br />
                ';

                if($item->id_tipodoc_electronico == '01' || $item->id_tipodoc_electronico == '07' || $item->id_tipodoc_electronico == '08') {
                    $fechas_documento = $fechas_documento.'<span onclick="editar_fecha_comprobante('."'".date("d/m/Y", strtotime($item->fecha_comprobante))."'".', '.$parametros_fecha.')" style="cursor: pointer; text-decoration-color: #EA215A; text-decoration-thickness: .125em; text-underline-offset: 1.5px; border-bottom: #2196f3 0.130em dashed;"><i class="fa fa-caret-right position-left color-indigo"></i>F.Documento: '.date("d-m-Y", strtotime($item->fecha_comprobante)).'</span>';
                } else {
                    $fechas_documento = $fechas_documento.'<span><i class="fa fa-caret-right position-left color-indigo"></i>F.Documento: '.date("d-m-Y", strtotime($item->fecha_comprobante)).'</span>';
                }

                $sunat_tipooperacion = SunatTipooperacion::findFirst(array("id_codigotipooperacion = :id_codigotipooperacion:", "bind" => array("id_codigotipooperacion" => $item->id_tipo_operacion)));
                $tipo_operacion_doc = "";
                if($sunat_tipooperacion) {
                    $tipo_operacion_doc = '<br><span><i class="fa fa-caret-right position-left color-indigo"></i>
                    '.$sunat_tipooperacion->descripcion.'</span>';
                }

                $n++;
                $array_lista[] = array(
                    'DT_RowId' => $item->id_contribuyente.'||'.$item->id_tipodoc_electronico.'||'.$item->serie_comprobante.'||'.$item->numero_comprobante.'||'.$item->tipo_envio_sunat.'||'.$item->estado_envio_sunat.'||'.$numero_ticket,
					'fecha_registro' => $fechas_documento.$tipo_operacion_doc,
					'comprobante' => $datos_contribuyente.'<br />'.$tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante.'<br /><span class="label_razonsocial text-success"><i class="fa fa-caret-right position-left color-indigo"></i>
                    <a target="_blank" href="/facturacionv8/login/inicio_sesion_remoto?user='.$cadena_encriptada.'">Click para Ingresar</a></span>', 
                    'cliente' => $cliente->num_doc.'<br />'.$cliente->razon_social, 
                    'total' => $moneda->simbolo.' '.$item->total, 
                    'pdf' => $btn_pdf, 
                    'xml' => $btn_xml, 
                    'cdr' => $btn_cdr, 
                    'sunat' => $btn_sunat, 
                    'opciones' => $menu_opciones
                );

                //id_contribuyente || id_tipodoc_electronico || serie_comprobante || numero_comprobante || tipo_envio_sunat
                //$array_lista_ids[] = $item->id_contribuyente.'||'.$item->id_tipodoc_electronico.'||'.$item->serie_comprobante.'||'.$item->numero_comprobante.'||'.$item->tipo_envio_sunat.'||'.$item->estado_envio_sunat;
            }
            
            $json_data = array(
                "draw"            => intval($datapost['draw']),
                "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
                "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
                "data"            => $array_lista,   // total data array
                "numero_n"        => $n,
                "time_proceso"    => microtime(true) - $inicio_proceso
            );

            echo json_encode($json_data);
            exit();
        }
	}

	public function get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare("SELECT COUNT(*) total FROM ($query) AS tbl1");
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            //$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
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
            $fila = (object)$fila;
            return $fila->total;
        }

        return $n;
	}

	public function validate_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
	}

    public function ignorarDocumentoAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permitido crear estas operaciones, solo un administrador puede hacerlo!';
                echo json_encode($resp);
                exit();
			}
			
			$id_contribuyente = $datapost['id_contribuyente'];

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
			}

            $id_contribuyente = $contribuyente->id_contribuyente; //empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
			$confirmacion = empty($datapost['confirmacion'])?'no':$datapost['confirmacion'];

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = '¿Esta Acción no Podrá Revertirse!';
                $resp['mensaje'] = '¿Realmente Deseas Ignorar este documento?, ya no volverá a aparecer en esta pantalla... <strong class="text-danger">y no se podrá revertir la acción!</strong>';
                echo json_encode($resp);
                exit();
            }

            $documento->ignorar_documento = 'si';
            
            try {
                if(!$documento->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
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
                echo json_encode($resp);
                exit();
            }

			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'OK';
			$resp['mensaje'] = 'El documento ya no volverá aparecer en esta pantalla';
            echo json_encode($resp);
            exit();
        }
    }

	public function modificarDocelectronicoAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permitido crear estas operaciones, solo un administrador puede hacerlo!';
                echo json_encode($resp);
                exit();
			}
			
			$id_contribuyente = $datapost['id_contribuyente'];

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
			}
			
			$id_contribuyente = $contribuyente->id_contribuyente; //empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
            $accion = empty($datapost['accion'])?'':$datapost['accion'];
			$confirmacion = empty($datapost['confirmacion'])?'no':$datapost['confirmacion'];
			$num_ticket = empty($datapost['num_ticket'])?'':$datapost['num_ticket'];

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));
            
            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

			$resp = $this->modificar_comprobante($contribuyente, $documento, $accion, $confirmacion, $num_ticket);
			echo json_encode($resp);
			exit();
        }
	}

	public function modificar_comprobante($contribuyente, $documento, $accion, $confirmacion = 'si', $num_ticket = '') {
		$id_contribuyente = $contribuyente->id_contribuyente;
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
			return $resp;
		}
		
		if($accion == 'actualizar_ticket') {
			if($documento->id_tipodoc_electronico == '01') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Las Facturas Electrónicas no se manejan con ticket!';
				return $resp;
			}
			
			if(empty($num_ticket)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar un número de ticket!';
				return $resp;
			}

			if($documento->estado_envio_sunat != 'ticket') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento Electrónico no se encuentra en estado TICKET, por tanto no es necesario cambiar el número de ticket!';
				return $resp;
			}
			
			$resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->rb_id_contribuyente, 'codigo' => $documento->rb_codigo, 'serie' => $documento->rb_serie, 'secuencia' => $documento->rb_secuencia, 'tipo_envio_sunat' => $documento->rb_tipo_envio_sunat)));

			if(!$resumen) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se encuentra un resumen válido para el comprobante seleccionado!';
				return $resp;
			}

			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'ok';
				$resp['mensaje'] = '¿Estas Seguro que Deseas Actualizar el Número de Ticket?';
				return $resp;
			}

			$resumen->numero_ticket = $num_ticket;
            try {
                if(!$resumen->save()) {
                    $msg = '';
                    foreach ($resumen->getMessages() as $message) {
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
			$resp['titulo'] = 'OK';
			$resp['mensaje'] = 'Se guardó correctamente el número de ticket';
			return $resp;
		} else if ($accion == 'resetear') {

			//aquí validar si se trata de una boleta, si es una boleta entonces pedir que se apruebe manualmente, ya que no se debe resetear
			$documento->estado_envio_sunat = 'pendiente';
			$documento->fecha_envio_sunat = null;
			$documento->hash_cpe = null;
			$documento->hash_cdr = null;
			$documento->cod_sunat = null;
			$documento->msje_sunat = null;
			$documento->ruta_xml = null;
			$documento->name_xml = null;
			$documento->name_xml_zip = null;
			$documento->name_cdr = null;
			$documento->name_cdr_zip = null;
			$documento->rb_id_contribuyente = null;
			$documento->rb_codigo = null;
			$documento->rb_serie = null;
			$documento->rb_secuencia = null;
			$documento->rb_tipo_envio_sunat = null;

		} else if ($accion == 'aprobar_manualmente') {
			
			$documento->estado_envio_sunat = 'aceptado';
			$documento->msje_sunat = '-';
			$documento->name_cdr = '-';
            $documento->name_cdr_zip = '-';
            
            if($documento->id_tipodoc_electronico == '03') {
                $documento->rb_id_contribuyente = $documento->id_contribuyente;
                $documento->rb_codigo = 'RC';
                $documento->rb_serie = '-';
                $documento->rb_secuencia = 1;
                $documento->rb_tipo_envio_sunat = $documento->tipo_envio_sunat;
            }
		} else if($accion == 'anular_manualmente') {

            $data_doc_bd['id_contribuyente'] = $documento->id_contribuyente;
            $data_doc_bd['id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
            $data_doc_bd['serie_comprobante'] = $documento->serie_comprobante;
            $data_doc_bd['numero_comprobante'] = $documento->numero_comprobante;

            $motivo = 'Error en el Comprobante';
            $confirmacion = 'si';
            
            $data_doc_bd['tipo_envio_sunat'] = $documento->tipo_envio_sunat;
            $data_doc_bd['idusuario'] = $documento->id_vendedor;
            $data_doc_bd['tipo_log'] = 'comunicacion_baja';


            $data_de_baja['codigo'] = 'RC';
		    $data_de_baja['serie'] = '-';
		    $data_de_baja['secuencia'] = 1;

            $comunicacion_baja = new ComunicaciondebajaController;
            $resp_asignar_baja = $comunicacion_baja->registrar_idbaja_en_documento($documento, $data_de_baja, array(), 1);
            if($resp_asignar_baja['respuesta'] == 'error') {
                echo json_encode($resp_asignar_baja);
                exit();
            }

            /*
            $documento->estado_envio_sunat = 'anulado';
			$documento->msje_sunat = '-';
			$documento->name_cdr = '-';
            $documento->name_cdr_zip = '-';
            
            if($documento->id_tipodoc_electronico == '03') {
                $documento->rb_id_contribuyente = $documento->id_contribuyente;
                $documento->rb_codigo = 'RC';
                $documento->rb_serie = '-';
                $documento->rb_secuencia = 1;
                $documento->rb_tipo_envio_sunat = $documento->tipo_envio_sunat;
            }
            */
		} elseif ($accion == 'recover_cdr'){
			if($contribuyente->tipo_envio_sunat != 'produccion') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La recuperación del CDR es válida solo en producción!';
				return $resp;
			}

            /*
			if(empty($documento->ruta_xml)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'error_sin_xml';
				$resp['mensaje'] = 'No se encuentra en la base de datos la ruta del xml, primero debes generar el xml firmado!';
				return $resp;
			}
            */

			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'ok';
				$resp['mensaje'] = '¿Estás seguro de que deseas Actualizar el Documento Electrónico?';
				return $resp;
			}

            //DATOS PARA ACTUALIZAR LA TABLA DE DOC_ELECTRONICOXNOTA
            $data_doc_electronicoxnota['cpe_id_contribuyente'] 			= $documento->id_contribuyente;
            $data_doc_electronicoxnota['cpe_id_tipodoc_electronico'] 	= $documento->id_tipo_comprobante_modifica;
            $data_doc_electronicoxnota['cpe_serie_comprobante'] 		= $documento->serie_documento_modifica;
            $data_doc_electronicoxnota['cpe_numero_comprobante'] 		= $documento->nro_documento_modifica;
            $data_doc_electronicoxnota['cpe_tipo_envio_sunat'] 			= $documento->tipo_envio_sunat;
            $data_doc_electronicoxnota['fecha_registro'] 				= date('Y-m-d H:i:s');
            $data_doc_electronicoxnota['nota_id_contribuyente']			= $documento->id_contribuyente;
            $data_doc_electronicoxnota['nota_id_tipodoc_electronico']	= $documento->id_tipodoc_electronico;
            $data_doc_electronicoxnota['nota_serie_comprobante'] 		= $documento->serie_comprobante;
            $data_doc_electronicoxnota['nota_numero_comprobante'] 		= $documento->numero_comprobante;
            $data_doc_electronicoxnota['nota_tipo_envio_sunat'] 		= $documento->tipo_envio_sunat;
            $data_doc_electronicoxnota['nota_estado_envio_sunat'] 		= $documento->estado_envio_sunat;
            $data_doc_electronicoxnota['nota_id_cod_tipomotivo_credito'] 	= $documento->id_cod_tipomotivo_credito;
            $data_doc_electronicoxnota['nota_id_cod_tipomotivo_debito'] 	= $documento->id_cod_tipomotivo_debito;
            //FIN DATOS PARA ACTUALIZAR LA TABLA DE DOC_ELECTRONICOXNOTA

			$docelectronico = new DocumentoelectronicoController;
			$data_recover = array();
			$data_recover['ruc'] = $contribuyente->ruc;
			$data_recover['usuario_sol'] = $contribuyente->usuario_sol;
			$data_recover['pass_sol'] = $contribuyente->clave_sol;
			$data_recover['serie_comprobante'] = $documento->serie_comprobante;
			$data_recover['numero_comprobante'] = $documento->numero_comprobante;
			$data_recover['tipo_comprobante'] = $documento->id_tipodoc_electronico;
			$data_recover['tipo_certificado'] = $contribuyente->tipo_certificado;
			$data_recover['tipo_proceso'] = 'produccion';
			$resp_secret_data = $docelectronico->get_secret_data($contribuyente->id_contribuyente);
			$data_recover['secret_data'] =  $resp_secret_data['secret_data'];
            $data_recover['ruc_proveedor'] = $this->ruc_proveedor;
			
            if($contribuyente->tipo_empresa_sunat == 'prico') {
				$data_recover['secret_data']['tipo_certificado'] = 'pse_facturalaya_ose';
				$data_recover['secret_data']['nombre_ose'] = $contribuyente->nombre_ose;
	
				$data_recover['secret_data']['usuario_sol_ose'] = $contribuyente->u_produccion_ose;
				$data_recover['secret_data']['pass_user_sol_ose'] = $contribuyente->c_produccion_ose;
				$data_recover['secret_data']['url_ose'] = $contribuyente->url_produccion_ose;
			}


			$herramientas = new HerramientasController;
			$ruta = 'https://facturalahoy.com/api/recovercdr';
			$resp_api_ws = $herramientas->envio_api_sunat($data_recover, $ruta);
			$resp['api'] = $resp_api_ws;
			$respuesta_cdr = json_decode($resp_api_ws);
			if($respuesta_cdr->respuesta == 'ok') {
				if($respuesta_cdr->cod_sunat == '0') {
					$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/'.'facturas_boletas/';
					$ruta_dir_xml = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';

					if (!file_exists($ruta_base_cpe_cdr)) {
						mkdir($ruta_base_cpe_cdr, 0777, true);
					}
					
					//Guardamos el archivo zip del cdr
					$saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$respuesta_cdr->name_file_zip_cdr, base64_decode($respuesta_cdr->file_cdr_zip));
					if (($saved_file_cdr_zip === false) || ($saved_file_cdr_zip == -1)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['error_code'] = 'envio_sunat';
						$resp['mensaje'] = 'Existe el CDR pero no se logró guardar en el servidor local!';
						return $resp;
					}

					$documento->estado_envio_sunat = 'aceptado';
					$documento->cod_sunat = !isset($respuesta_cdr->cod_sunat)?'':$respuesta_cdr->cod_sunat;
					$documento->msje_sunat = !isset($respuesta_cdr->mensaje)?'':$respuesta_cdr->mensaje;
					$documento->hash_cdr = !isset($respuesta_cdr->hash_cdr)?'':$respuesta_cdr->hash_cdr;
					$documento->name_cdr = !isset($respuesta_cdr->name_file_xml_cdr)?'':$respuesta_cdr->name_file_xml_cdr;
					$documento->name_cdr_zip = !isset($respuesta_cdr->name_file_zip_cdr)?'':$respuesta_cdr->name_file_zip_cdr;
					
					try {
						if(!$documento->save()) {
							$msg = '';
							foreach ($documento->getMessages() as $message) {
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
						$resp['mensaje'] =  $e->getMessage();
						return $resp;
					}

                    if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08') {
                        $nota_credito_controller = new NotadecreditoController;
                        $data_doc_electronicoxnota['nota_estado_envio_sunat'] = $documento->estado_envio_sunat;
                        $resp_doc_electronicoxnota = $nota_credito_controller->guardar_registro_doc_electronicoxnota($data_doc_electronicoxnota);
                        if($resp_doc_electronicoxnota['respuesta'] == 'error') {
                            //return $resp_doc_electronicoxnota;
                        }
                    }
		
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Proceso Correcto';
					$resp['mensaje'] = 'se ha recuperado el CDR!';
					return $resp;
				}  

			} else {
                $resp['respuesta'] = 'error';
                $resp['tituto'] = 'error';
                $resp['mensaje'] = $resp_api_ws;
                $resp['resp_api'] = $resp_api_ws;
				return $resp;
			}
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se Reconoce la Acción!';
			return $resp;
		}

		try {
			if(!$documento->save()) {
				$msg = '';
				foreach ($documento->getMessages() as $message) {
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
		$resp['titulo'] = 'Proceso Correcto';
		$resp['mensaje'] = 'Se ha modificado correctamente el documento!';
		return $resp;
	}
	
	public function verificarEstadoSunatAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
			}
			
            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
                echo json_encode($resp);
                exit();
            }

            if($cliente->num_doc == '00000000') {
                $data['id_doc_cliente'] = '';
                $data['num_doc_cliente'] = '';
            } else {
                $data['id_doc_cliente'] = $cliente->id_tipodocidentidad;
                $data['num_doc_cliente'] = $cliente->num_doc;
            }

            $herramientas = new HerramientasController;
            $data['num_ruc'] = $contribuyente->ruc;
            $data['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
            $data['documento_serie'] = $serie_comprobante;
            $data['documento_numero'] = $numero_comprobante;
           
            $data['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
            $data['documento_total'] = $documento->total;
			
			$ruta = 'https://facturalahoy.com/api/estadodocumento';
            $resp_api = $herramientas->envio_api_sunat($data, $ruta);
            
			$resp_api_2 = $resp_api;
            $resp_api = json_decode($resp_api);
            $resp_api = json_decode($resp_api);
            
            if(empty($resp_api) || !isset($resp_api->rpta) || $resp_api->rpta == 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = isset($resp_api->mensaje)?$resp_api->mensaje:'SUNAT no está respondiendo correctamente!!...';
                $resp['resp_api'] = $resp_api;
                echo json_encode($resp);
                exit();
            }

            if($resp_api->data->estadoCp == '1') {
                $estado_documento_sunat = '<a href="#" class="list-group-item"><i class="icon-file-text2"></i> <span class="label bg-success heading-text">APROBADO</span> Estado del Comprobante a la Fecha de Consulta: </a>';
            } elseif ($resp_api->data->estadoCp == '2') {
                $estado_documento_sunat = '<a href="#" class="list-group-item"><i class="icon-file-text2"></i> <span class="label bg-danger heading-text">ANULADO</span> Estado del Comprobante a la Fecha de Consulta: </a>';
            } else {
                $estado_documento_sunat = '<a href="#" class="list-group-item"><i class="icon-file-text2"></i> <span class="label bg-danger heading-text">-</span> Estado del Comprobante a la Fecha de Consulta: </a>';
            }

            if($resp_api->data->estadoRuc == '00') {
                $estado_ruc_sunat = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-success heading-text">ACTIVO</span> Estado del contribuyente a la fecha de emisión: </a>';
            } else {
                $estado_ruc_sunat = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-danger heading-text">-</span> Estado del contribuyente a la fecha de emisión: </a>';
            }

            if($resp_api->data->condDomiRuc == '00') {
                $estado_domicilio = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-success heading-text">HABIDO</span> Condición de Domicilio a la Fecha de Emisión: </a>';
            } else {
                $estado_domicilio = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-danger heading-text">-</span> Condición de Domicilio a la Fecha de Emisión: </a>';
            }

            $html_respuesta = '
            <div class="list-group no-border no-padding-top">
                <a href="#" class="list-group-item"><i class="icon-calendar3"></i> <span class="label bg-success heading-text">'.date("d/m/Y").'</span> Fecha de Consulta: </a>
                '.$estado_documento_sunat.$estado_domicilio.$estado_ruc_sunat.'
                <a href="#" class="list-group-item"><i class="icon-database"></i> <span class="label bg-success heading-text">'.$documento->estado_envio_sunat.'</span> Estado en el Sistema: </a>
            </div>
            ';

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Resputa Obtenida';
            $resp['html'] = $html_respuesta;
            if($documento->estado_envio_sunat == 'aceptado') {
                if($documento->name_cdr != null && $documento->name_cdr_zip != null) {
                    $estado_envio_sunat = 'aceptado';
                } else {
                    $estado_envio_sunat = 'sin_cdr';
                }
            } else {
                $estado_envio_sunat = $documento->estado_envio_sunat;
            }

			$resp['estado_documento'] = $estado_envio_sunat;
			$resp['resp_api'] = $resp_api;
            echo json_encode($resp);
            exit();
        }
	}

	public function enviarDocumentoSunatAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }
            
            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $id_tipodoc_electronico = $datapost['id_tipodoc_electronico'];
            $serie_comprobante = $datapost['serie_comprobante'];
            $numero_comprobante = intval($datapost['numero_comprobante']) + 0;
            $modo = $datapost['modo'];

            if($modo != 'inmediato' && $modo != 'solo_firma') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'El modo seleccionado no es válido!';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != $contribuyente->id_contribuyente) {
                if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El Documento no existe o no tiene los permisos correspondientes.';
                    echo json_encode($resp);
                    exit();
                }
            }

            $array_ids_docs_validos = array('01', '03', '07', '08', '09');
            if(!in_array($id_tipodoc_electronico, $array_ids_docs_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de documento seleccionado no se reconoce';
                echo json_encode($resp);
                exit();
            }

            if(strlen($serie_comprobante) > 4) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La serie no existe!';
                echo json_encode($resp);
                exit();
            }

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en ApiRest';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
            if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Debes Confirmar!';
                if($modo == 'inmediato') {
                    $resp['mensaje'] = '¿Realmente Deseas Enviar el Documento a SUNAT? Lo enviaremos inmediatamente!..';
                } else {
                    $resp['mensaje'] = '¿Seguro que deseas firmar el documento? Solo se firmará el documento y no se enviará el documento a SUNAT.';
                }
                echo json_encode($resp);
                exit();
            }

            if($id_tipodoc_electronico == '01') { //FACTURA
                $factura = new FacturaController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;

                $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';

                $resp_factura = $factura->enviar_factura_api($resp_guardado_bd, $modo, $idusuario);
                echo json_encode($resp_factura);
                exit();
            } else if($id_tipodoc_electronico == '03') { //BOLETA
                if($modo == 'inmediato') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Recurda que las boletas no se permiten enviarse de una a una, se deben enviar en un resúmen diario de boletas';
                    echo json_encode($resp);
                    exit();
                }

                $factura = new FacturaController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;

                $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';

                $resp = $factura->enviar_factura_api($resp_guardado_bd, 'solo_firma', $idusuario);
                echo json_encode($resp);
                exit();
            } else if($id_tipodoc_electronico == '07') {
                if ($documento->id_tipo_comprobante_modifica == '03') { //modifica una boleta
                    if($modo == 'inmediato') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Recurda que las notas de crédito de boletas no se permiten enviarse de una a una, se deben enviar en un resúmen diario de boletas';
                        echo json_encode($resp);
                        exit();
                    }
                }
                $nota_credito = new NotadecreditoController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;

                $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';

                $resp = $nota_credito->enviar_notacredito_api($resp_guardado_bd, $modo);
                echo json_encode($resp);
                exit();

            } else if($id_tipodoc_electronico == '08') {
                if ($documento->id_tipo_comprobante_modifica == '03') { //modifica una boleta
                    if($modo == 'inmediato') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Recurda que las notas de débito de boletas no se permiten enviarse de una a una, se deben enviar en un resúmen diario de boletas';
                        echo json_encode($resp);
                        exit();
                    }
                }
                
                $nota_debito = new NotadedebitoController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;

                $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';

                $resp = $nota_debito->enviar_notadebito_api($resp_guardado_bd, $modo);
                echo json_encode($resp);
                exit();
            } else if($id_tipodoc_electronico == '09') { //GUIA DE REMISION
                $guia_remision = new GuiaderemisionController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;

                $resp_guardado_bd['tipo_envio_sistema'] = 'soporte';

                $resp_guia_remision = $guia_remision->enviar_guia_remision_api($resp_guardado_bd, $modo);
                echo json_encode($resp_guia_remision);
                exit();
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de documento seleccionado no se reconoce';
                echo json_encode($resp);
                exit();
            }

        }
	}
	
	public function crearResumenIndividualAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            if(!isset($datapost['id_contribuyente']) || empty($datapost['id_contribuyente'])) {
                $id_contribuyente = $usuario->id_contribuyente;
            } else {
                $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != $contribuyente->id_contribuyente) {
                if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El Documento no existe o no tiene los permisos correspondientes.';
                    echo json_encode($resp);
                    exit();
                }
            }
            
            $data_doc_bd['id_contribuyente'] = $contribuyente->id_contribuyente;
            $data_doc_bd['id_tipodoc_electronico'] = $datapost['tipodoc'];
            $data_doc_bd['serie_comprobante'] = $datapost['serie'];
            $data_doc_bd['numero_comprobante'] = $datapost['numero'];
            $data_doc_bd['idusuario'] = $usuario->idusuario;

            $confirmacion = !isset($datapost['confirmacion'])?'':$datapost['confirmacion'];
			$accion = $datapost['accion'];
			$resumenboletas = new ResumendeboletasController;
            $resp = $resumenboletas->crear_resumen_individual($data_doc_bd, $accion, $confirmacion);
            echo json_encode($resp);
            exit();
        }
	}
	
	public function procesarFacturasNotasAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
			}

			if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Acceso válido solo a SUPER USUARIOS.';
				echo json_encode($resp);
				exit();
			}

			$id_contribuyente = empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
			$numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
			$confirmacion = !isset($datapost['confirmacion'])?'':$datapost['confirmacion'];
            $modalidad_envio_cpe = !isset($datapost['modalidad_envio_cpe'])?'':$datapost['modalidad_envio_cpe'];
			
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
			}

			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }
			
			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
                echo json_encode($resp);
                exit();
            }

            if($cliente->num_doc == '00000000') {
                $data['id_doc_cliente'] = '';
                $data['num_doc_cliente'] = '';
            } else {
                $data['id_doc_cliente'] = $cliente->id_tipodocidentidad;
                $data['num_doc_cliente'] = $cliente->num_doc;
			}
			
			$herramientas = new HerramientasController;
            $data['num_ruc'] = $contribuyente->ruc;
            $data['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
            $data['documento_serie'] = $serie_comprobante;
            $data['documento_numero'] = $numero_comprobante;
           
            $data['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
            $data['documento_total'] = $documento->total;
            
            $resp_api = $herramientas->get_status_doc_sunat($data);
			$resp_api_2 = $resp_api;
            $resp_api = json_decode($resp_api);
            $resp_api = json_decode($resp_api);
            
            if(empty($resp_api) || !isset($resp_api->rpta) || $resp_api->rpta == 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!';
                echo json_encode($resp);
                exit();
			}
			
			if($resp_api->data->estadoCp == '2') {
				$estado_en_sunat = 'ACEPTADO';
				if($resp_api->data->estadoCp == '2') {
					$estado_en_sunat = 'ANULADO';
				}
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = "El Comprobante $id_contribuyente - $id_tipodoc_electronico - $serie_comprobante - $numero_comprobante, ha sido procesado en SUNAT... Su Estado es: $estado_en_sunat";
                echo json_encode($resp);
                exit();
			}

			if($resp_api->data->estadoCp == '1') {
				if($documento->estado_envio_sunat == 'pendiente') {
					//Esto significa que en SUNAT ha sido procesado y está aceptado, sin embargo en el sistema se encuentra como pendiente... La acción que debemos tomar en este caso es RECUPERAR EL CDR.
					$resp = $this->modificar_comprobante($contribuyente, $documento, 'recover_cdr', 'si', '');
					echo json_encode($resp);
					exit();
				}
			}
			
			$modo = 'inmediato';
			$idusuario = $usuario->idusuario;
			if($id_tipodoc_electronico == '01') { //FACTURA

                if($modalidad_envio_cpe == 'envio_en_lote') {
                    $fecha_actual = date('Y-m-d', strtotime('today'));
                    $resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $documento->fecha_comprobante);
                    $diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
                    if($diferencia_dias >= 7) {
                        $resp['respuesta'] = 'error';
                        $resp['codigo'] = 'cabecera_factura';
                        $resp['titulo'] = 'error';
                        $resp['mensaje'] = 'La Factura Supera la Fecha Límite!';
                        echo json_encode($resp);
                        exit();
                    }
                }

                $factura = new FacturaController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp_factura = $factura->enviar_factura_api($resp_guardado_bd, $modo, $idusuario);
                echo json_encode($resp_factura);
                exit();
            } else if($id_tipodoc_electronico == '07') {

                if($modalidad_envio_cpe == 'envio_en_lote') {
                    $fecha_actual = date('Y-m-d', strtotime('today'));
                    $resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $documento->fecha_comprobante);
                    $diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
                    if($diferencia_dias >= 7) {
                        $resp['respuesta'] = 'error';
                        $resp['codigo'] = 'cabecera_factura';
                        $resp['titulo'] = 'error';
                        $resp['mensaje'] = 'La Factura Supera la Fecha Límite!';
                        echo json_encode($resp);
                        exit();
                    }
                }

                $nota_credito = new NotadecreditoController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp = $nota_credito->enviar_notacredito_api($resp_guardado_bd, $modo);
                echo json_encode($resp);
                exit();
            } else if($id_tipodoc_electronico == '08') {
                if($modalidad_envio_cpe == 'envio_en_lote') {
                    $fecha_actual = date('Y-m-d', strtotime('today'));
                    $resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $documento->fecha_comprobante);
                    $diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
                    if($diferencia_dias >= 7) {
                        $resp['respuesta'] = 'error';
                        $resp['codigo'] = 'cabecera_factura';
                        $resp['titulo'] = 'error';
                        $resp['mensaje'] = 'La Factura Supera la Fecha Límite!';
                        echo json_encode($resp);
                        exit();
                    }
                }

                $nota_debito = new NotadedebitoController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp = $nota_debito->enviar_notadebito_api($resp_guardado_bd, $modo);
                echo json_encode($resp);
                exit();
            } else if($id_tipodoc_electronico == '09') { //GUIA DE REMISION
                $factura = new GuiaderemisionController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp_factura = $factura->enviar_guia_remision_api($resp_guardado_bd, $modo);
                echo json_encode($resp_factura);
                exit();
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de documento seleccionado no se reconoce';
                echo json_encode($resp);
                exit();
            }
		}
	}

	public function procesarBoletasNotasAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
			}

			if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Acceso válido solo a SUPER USUARIOS.';
				echo json_encode($resp);
				exit();
			}

			$id_contribuyente = empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
			$numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
			$confirmacion = !isset($datapost['confirmacion'])?'':$datapost['confirmacion'];
			
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
			}

			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }
			
			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
                echo json_encode($resp);
                exit();
            }

            if($cliente->num_doc == '00000000') {
                $data['id_doc_cliente'] = '';
                $data['num_doc_cliente'] = '';
            } else {
                $data['id_doc_cliente'] = $cliente->id_tipodocidentidad;
                $data['num_doc_cliente'] = $cliente->num_doc;
			}
			
			$herramientas = new HerramientasController;
            $data['num_ruc'] = $contribuyente->ruc;
            $data['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
            $data['documento_serie'] = $serie_comprobante;
            $data['documento_numero'] = $numero_comprobante;
           
            $data['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
            $data['documento_total'] = $documento->total;
            
            $resp_api = $herramientas->get_status_doc_sunat($data);
			$resp_api_2 = $resp_api;
            $resp_api = @json_decode($resp_api);
            $resp_api = @json_decode($resp_api);
            
            if(empty($resp_api) || !isset($resp_api->rpta) || $resp_api->rpta == 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!';
                echo json_encode($resp);
                exit();
			}
			
			if($resp_api->data->estadoCp == '2') {
				$estado_en_sunat = 'ACEPTADO';
				if($resp_api->data->estadoCp == '2') {
					$estado_en_sunat = 'ANULADO';
				}
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = "El Comprobante $id_contribuyente - $id_tipodoc_electronico - $serie_comprobante - $numero_comprobante, ha sido procesado en SUNAT... Su Estado es: $estado_en_sunat";
                echo json_encode($resp);
                exit();
			}

			if($resp_api->data->estadoCp == '1') {
				if($documento->estado_envio_sunat == 'pendiente' || $documento->estado_envio_sunat == 'ticket') {
					//Esto significa que el estao de la boleta está en pendiente o ticket, sin embargo en sunat ya está aceptado.
					$resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->rb_id_contribuyente, 'codigo' => $documento->rb_codigo, 'serie' => $documento->rb_serie, 'secuencia' => $documento->rb_secuencia, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));
					$numero_ticket = '';
                    if(!$resumen) {
						//Proceder con una aprobación manual, ya que se encuentra activo en sunat y no tiene resumen.
						$resp = $this->modificar_comprobante($contribuyente, $documento, 'aprobar_manualmente', $confirmacion = 'si', $numero_ticket);
						echo json_encode($resp);
						exit();
					}

					//Si encuentra el resumen, intentaremos con una consulta de ticket
					$numero_ticket = $resumen->numero_ticket;
					if(!empty($numero_ticket)) {
						$resp = $this->consultar_ticket($contribuyente->id_contribuyente, $numero_ticket, $idusuario);
						if($resp['respuesta'] == 'error') {
							//Al consultar el ticket obtener un error
							//Procedemos con una aprobación manual.
							$resp = $this->modificar_comprobante($contribuyente, $documento, 'aprobar_manualmente', $confirmacion = 'si', $numero_ticket);
							echo json_encode($resp);
							exit();
						}
						echo json_encode($resp);
						exit();
					} else {
						//no tiene ticket pero se encuentra enviado en SUNAT
						//procedemos con una aprobación manual
						$resp = $this->modificar_comprobante($contribuyente, $documento, 'aprobar_manualmente', $confirmacion = 'si', $numero_ticket);
						echo json_encode($resp);
						exit();
					}
				} else {
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'enviado';
					$resp['mensaje'] = 'El Comprobante ya fué enviado y se encuentra aceptado';
					echo json_encode($resp);
					exit();
				}
			} else {
				//Ingresa aquí porque no se encuentra enviado....
				//Entonces debemos resetear y volver a enviar!.
				$resp_reseteo = $this->modificar_comprobante($contribuyente, $documento, 'resetear', $confirmacion = 'si', '');
				$data_doc_bd['id_contribuyente'] = $documento->id_contribuyente;
				$data_doc_bd['id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
				$data_doc_bd['serie_comprobante'] = $documento->serie_comprobante;
				$data_doc_bd['numero_comprobante'] = $documento->numero_comprobante;
                $data_doc_bd['idusuario'] = $usuario->idusuario;

				$confirmacion = 'si';
				$accion = 1;
				$resumenboletas = new ResumendeboletasController;
				$resp = $resumenboletas->crear_resumen_individual($data_doc_bd, $accion, $confirmacion);
				echo json_encode($resp);
				exit();
			}
		}
	}

	public function consultarTicketAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            $numero_ticket = !isset($datapost['numero_ticket'])?'':$datapost['numero_ticket'];
			$idcontribuyente = !isset($datapost['idcontribuyente'])?'':intval($datapost['idcontribuyente']);
			$resp = $this->consultar_ticket($idcontribuyente, $numero_ticket, $idusuario);
			echo json_encode($resp);
			exit();
		}
	}

	public function consultar_ticket($idcontribuyente, $numero_ticket) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $idcontribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la cuenta!';
			echo json_encode($resp);
			exit();
		}
		
		$resumen = ResumenBoletas::findFirst(array("numero_ticket = :numero_ticket: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('numero_ticket' => $numero_ticket, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$resumen) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El ticket número: '.$numero_ticket.', no se encuentra asignado a ningún resumen, por favor comuníquese con soporte';
			return $resp;
		}

		$documentoelectronico = new DocumentoelectronicoController;
		$resp_data_emisor = $documentoelectronico->get_data_emisor($contribuyente->id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
		}
		
		$resp_secret_data = $documentoelectronico->get_secret_data($contribuyente->id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
		}

		$data['ruc'] = $resp_data_emisor['emisor']['ruc'];
		$data['secret_data'] = $resp_secret_data['secret_data'];
		$data['cod_ticket'] = $numero_ticket;
		$data['codigo'] = $resumen->codigo;
		$data['serie'] = $resumen->serie;
		$data['secuencia'] = $resumen->secuencia;

		$ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'resumenes/';
		if (!file_exists($ruta_base_cpe_cdr)) {
			mkdir($ruta_base_cpe_cdr, 0777, true);
		}

		$herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/resumenboletas/get_cdr';
		$resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
		$resp_api = json_decode($resp_api_ws);
		$resp['response'] = $resp_api_ws;
		$resp['resp_api_ws'] = $resp_api;
		if(empty($resp_api)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = 'El CDR Aún no se encuentra disponible, por favor intente más tarde!';
            $resp['step'] = 'consulta_ticket_00004';
			//$resp['mensaje'] = 'No hemos podido recuperar el CDR, obtenemos el siguiente error: '.$resp_api_ws;
			return $resp;
		}

		if($resp_api->respuesta == 'error') {
			//Si ingresa aquí debemos verificar si es un error en el envío o un error en la recepción del cdr
			if($resp_api->codigo_error_api == 'error_consulta_ticket') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se logró obtener el cdr, se obtiene el siguiente error: '.$resp_api->msj_sunat;
                $resp['step'] = 'consulta_ticket_0003';
                $resp['cod_sunat'] = isset($resp_api->cod_sunat)?$resp_api->cod_sunat:'';
				return $resp;
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error Desconocido';
				$resp['mensaje'] = 'No se logró obtener el cdr, se obtiene el siguiente error: '.$resp_api->msj_sunat;
                $resp['step'] = 'consulta_ticket_002';
                $resp['cod_sunat'] = isset($resp_api->cod_sunat)?$resp_api->cod_sunat:'';
				return $resp;
			}
		} else if($resp_api->respuesta == 'ok') {
			if($resp_api->cod_sunat == '0' || $resp_api->cod_sunat == '2282') {
				//0: El archivo fué aceptado

				$this->db->begin();

				$resumen->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
				$resumen->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
				$resumen->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
				$resumen->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
				$resumen->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
				$resumen->estado_envio_sunat = 'aceptado';

				$saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));

                try {
                    if(!$resumen->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($resumen->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
        
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = $msg;
                        $resp['step'] = 'consulta_ticket_001';
                        return $resp;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $e->getMessage();
                    $resp['step'] = 'consulta_ticket_002';
                    return $resp;
                }

				$lista_documentos = DocElectronico::find(array("rb_id_contribuyente = :rb_id_contribuyente: and rb_codigo = :rb_codigo: and rb_serie = :rb_serie: and rb_secuencia = :rb_secuencia:", 'bind' => array('rb_id_contribuyente' => $resumen->id_contribuyente, 'rb_codigo' => $resumen->codigo, 'rb_serie' => $resumen->serie, 'rb_secuencia' => $resumen->secuencia)));
				$resumenboletas = new ResumendeboletasController;
				$resp_asignar_resumen = $resumenboletas->registrar_idresumen_en_documentos($lista_documentos, $resumen->codigo, $resumen->serie, $resumen->secuencia, 'aceptado', $resp_api, $resumen->accion_sunat);
				if($resp_asignar_resumen['respuesta'] == 'error') {
					$this->db->rollback();
                    $resp['step'] = 'consulta_ticket_0';
					return $resp;
				}

				$saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));

				$this->db->commit();
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envio Correcto!';
				$resp['mensaje'] = 'Se recuperó correctamente el CDR del resúmen:  '.$resumen->codigo.'-'.$resumen->serie.'-'.$resumen->secuencia.', la respuesta fué: '.$resp_api->msj_sunat;
				$resp['step'] = 'consulta_ticket_1';
                return $resp;

			} else if ($resp_api->cod_sunat == '2223') {
				//2223: Aparentemente el mismo resumen ya fué presentado, aquí tenemos dos problemas, no sabemos si el resumen en cuestión tiene las mismas boletas, es un problema pq al día siguiente habrán algunas boletas que no se puedan enviar porque ya habrán sido enviadas en un resumen anterior!.

				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
                $resp['step'] = 'consulta_ticket_2';
				$resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
				return $resp;
			} else {
				//aquí podemos trabajar con las posibles errores de rechazo!
				//http://cpe.sunat.gob.pe/boleta-de-venta-desde-los-sistemas-del-contribuyente (sección preguntas frecuentes)
				//indica que en caso  haya errores se debe enviar un nuevo resumen solucionando o reparando los errores indicados!
				
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envio Correcto!';
                $resp['step'] = 'consulta_ticket_3';
				$resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
				return $resp;
			}
			
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Desconocido!';
            $resp['step'] = 'consulta_ticket_4';
			$resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
			return $resp;
		}
    }

    public function guardarFechaDocumentoAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }
            //data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, tipo_envio_sunat: tipo_envio_sunat},

            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $id_tipodoc_electronico = $datapost['id_tipodoc_electronico'];
            $serie_comprobante = $datapost['serie_comprobante'];
            $numero_comprobante = intval($datapost['numero_comprobante']) + 0;
            $tipo_envio_sunat = $datapost['tipo_envio_sunat'];

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Documento no existe o no tiene los permisos correspondientes.';
                echo json_encode($resp);
                exit();
            }

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en ApiRest';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }
            
            if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08') { //FACTURA
                $herramientas = new HerramientasController;
                
                $array_fecha_documento = explode('/',!isset($datapost['fecha_comprobante'])?date('d/m/Y'):$datapost['fecha_comprobante']);
                if(!isset($array_fecha_documento[2]) || !isset($array_fecha_documento[1]) || !isset($array_fecha_documento[0])) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La fecha asignada al documento no es válida!';
                    echo json_encode($resp);
                    exit();
                }

                $fecha_documento = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
                if (!(DateTime::createFromFormat('Y-m-d', $fecha_documento) !== FALSE)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La fecha asignada al documento no es válida!';
                    echo json_encode($resp);
                    exit();
                }
                
                $fecha_actual = date('Y-m-d', strtotime('today'));
                $resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $documento->fecha_comprobante);
                $diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
                if($diferencia_dias <= -3) {
                    $resp['respuesta'] = 'error';
                    $resp['codigo'] = 'cabecera_factura';
                    $resp['titulo'] = 'error';
                    $resp['mensaje'] = 'La Fecha no debe ser cambiada pues aún está dentro del periodo de envío válido!';
                    echo json_encode($resp);
                    exit();
                } else {
                    $resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $fecha_documento);
                    $diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
                    if($diferencia_dias > 6) {
                        $resp['respuesta'] = 'error';
                        $resp['codigo'] = 'cabecera_factura';
                        $resp['titulo'] = 'error';
                        $resp['mensaje'] = 'La diferencia de días de la fecha asignada con relación a la fecha actual no debe superar los 6 días!';
                        echo json_encode($resp);
                        exit();
                    }

                    if($diferencia_dias < 0) {
                        $resp['respuesta'] = 'error';
                        $resp['codigo'] = 'cabecera_factura';
                        $resp['titulo'] = 'error';
                        $resp['mensaje'] = 'La nueva fecha debe ser la fecha actual o una fecha anterior, no puede ser una fecha futura!';
                        echo json_encode($resp);
                        exit();
                    }

                    $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
                    if($confirmacion != 'si') {
                        $resp['respuesta'] = 'ok';
                        $resp['titulo'] = 'Debes Confirmar!';
                        /*
                        if($modo == 'inmediato') {
                            $resp['mensaje'] = '¿Realmente Deseas Cambiar la Fecha del Comprobante?';
                        } else {
                            $resp['mensaje'] = 'El Cambio Será Permanente';
                        }
                        */
                        $resp['mensaje'] = '¿Realmente Deseas Cambiar la Fecha del Comprobante?';
                        echo json_encode($resp);
                        exit();
                    }

                    //Si la fecha es cambiada, se debe resetear el comprobante
                    $documento->estado_envio_sunat = 'pendiente';
                    $documento->fecha_envio_sunat = null;
                    $documento->hash_cpe = null;
                    $documento->hash_cdr = null;
                    $documento->cod_sunat = null;
                    $documento->msje_sunat = null;
                    $documento->ruta_xml = null;
                    $documento->name_xml = null;
                    $documento->name_xml_zip = null;
                    $documento->name_cdr = null;
                    $documento->name_cdr_zip = null;
                    $documento->rb_id_contribuyente = null;
                    $documento->rb_codigo = null;
                    $documento->rb_serie = null;
                    $documento->rb_secuencia = null;
                    $documento->rb_tipo_envio_sunat = null;

                    $documento->fecha_comprobante = date('Y-m-d', strtotime($fecha_documento));
                    $documento->fecha_vto_comprobante = date('Y-m-d', strtotime($fecha_documento));
                    
                    try {
                        if(!$documento->save()) {
                            $msg = '';
                            foreach ($documento->getMessages() as $message) {
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
                    $resp['titulo'] = 'OK';
                    $resp['mensaje'] = 'Se ha cambiado la fecha del comprobante correctamente!';
                    echo json_encode($resp);
                    exit();
                }
            }
            
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El cambio solo es válido para FACTURAS';
            echo json_encode($resp);
            exit();
        }
    }

    public function procesoValidacionCpeAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
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

            $resp = $this->validacion_cpe_sunat($datapost);
            echo json_encode($resp);
            exit();
        }      
    }

    public function validacion_cpe_sunat($data) {
        sleep(2);

        $id_contribuyente       = $data['id_contribuyente'];
        $id_tipodoc_electronico = $data['id_tipodoc_electronico'];
        $serie_comprobante      = $data['serie_comprobante'];
        $numero_comprobante     = $data['numero_comprobante'];
        $id_row = $data['id_row'];

        $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));
        if(!$documento) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Posiblmente el documento no exista, o se encuentre en una versión de prueba';
            $resp['step_code'] = '1';
            return $resp;
        }

        $emisor = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$emisor) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            $resp['step_code'] = '6';
            return $resp;
        }

        /*
        if($documento->estado_envio_sunat != 'aceptado') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Se Debe enviar el comprobante primero';
            $resp['step_code'] = '6';
            return $resp;
        }
        */

        $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
        if(!$cliente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
            $resp['step_code'] = '5';
            return $resp;
        }
        
        //validamos el estado del documento en SUNAT:
        $herramientas = new HerramientasController;
        
        if($cliente->num_doc == '00000000') {
            $data_validacion['id_doc_cliente'] = '';
            $data_validacion['num_doc_cliente'] = '';
        } else {
            $data_validacion['id_doc_cliente'] = $cliente->id_tipodocidentidad;
            $data_validacion['num_doc_cliente'] = $cliente->num_doc;
        }

        $data_validacion['num_ruc'] = $emisor->ruc;
        $data_validacion['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
        $data_validacion['documento_serie'] = $serie_comprobante;
        $data_validacion['documento_numero'] = $numero_comprobante;
        $data_validacion['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
        $data_validacion['documento_total'] = $documento->total;
        $data_validacion['id_row'] = $id_row;
        
        $ruta = 'https://facturalahoy.com/api/validacioncpe';
        $response = $herramientas->envio_api_sunat($data_validacion, $ruta);

        $resp_api = json_decode($response);
		if ($resp_api === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!';
            $resp['response'] = $response;
			return $resp;
		}

        if(!isset($resp_api->respuesta)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!';
            $resp['response'] = $response;
			return $resp;
        }

        if(!isset($resp_api->data)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!';
            $resp['response'] = $response;
			return $resp;
        }

        if($resp_api->respuesta == 'error') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Error en la conexión con SUNAT';
            $resp['response'] = $response;
            return $resp;
        }

        if($resp_api->data->estadoCp == '1') {
            $resp['respuesta'] = 'ok';
            $resp['estado'] = 'aprobado';
            $resp['titulo'] = 'Aprobado';
            $resp['mensaje'] = 'El comprobante se encuentra Aprobado en SUNAT';
            $resp['txt_cpe'] = "";
            $resp['response'] = $response;
            return $resp;
        }
        
        if ($resp_api->data->estadoCp == '2') {
            $resp['respuesta'] = 'ok';
            $resp['estado'] = 'anulado';
            $resp['titulo'] = 'Anulado';
            $resp['mensaje'] = 'El comprobante se encuentra Anulado en SUNAT';
            $resp['txt_cpe'] = "";
            $resp['response'] = $response;
            return $resp;
        }
        

        $data_validacion['num_ruc'] = $emisor->ruc;
        $data_validacion['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
        $data_validacion['documento_serie'] = $serie_comprobante;
        $data_validacion['documento_numero'] = $numero_comprobante;
        $data_validacion['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
        $data_validacion['documento_total'] = $documento->total;
        $data_validacion['id_row'] = $id_row;

        
        $resp['respuesta'] = 'informe_sunat';
        $resp['estado'] = 'pendiente';
        $resp['titulo'] = 'Pendiente';
        
        $nombre_cpe = "cpe";
        if($id_tipodoc_electronico == '01') {
            $nombre_cpe = "FACTURA";
        } else if($id_tipodoc_electronico == '03') {
            $nombre_cpe = "BOLETA";
        } else if($id_tipodoc_electronico == '07') {
            $nombre_cpe = "NOTA DE CRÉDITO";
        } else if($id_tipodoc_electronico == '08') {
            $nombre_cpe = "NOTA DE DÉBITO";
        }

        $resp['cpe'] = array(
            'ruc_emisor'                => $emisor->ruc,
            'id_tipo_doc_electronico'   => $id_tipodoc_electronico,
            'nombre_cpe'                => $nombre_cpe,
            'serie_comprobante'         => $serie_comprobante,
            'numero_comprobante'        => $numero_comprobante,
            'fecha_comprobante'         => date("d/m/Y", strtotime($documento->fecha_comprobante)),
            'total'                     => $documento->total
        );

        $resp['txt_cpe'] = "$emisor->ruc|$id_tipodoc_electronico|$serie_comprobante|$numero_comprobante|".date("d/m/Y", strtotime($documento->fecha_comprobante))."|$documento->total";
        $resp['data_cpe'] = "
            RUC CONTRIBUYENTE: $emisor->ruc \n
            TIPO DOCUMENTO: $id_tipodoc_electronico \n
            SERIE: $serie_comprobante \n
            CORRELATIVO: $numero_comprobante \n
            FECHA_EMISION: ".date("d/m/Y", strtotime($documento->fecha_comprobante))." \n
            TOTAL: $documento->total \n\n
        ";
        $resp['mensaje'] = 'El comprobante se encuentra Pendiente en SUNAT';
        $resp['response'] = $response;
        return $resp;
    }
}
?>