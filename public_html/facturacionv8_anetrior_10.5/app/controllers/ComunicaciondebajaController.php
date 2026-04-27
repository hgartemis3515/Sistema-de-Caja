<?php
class ComunicaciondebajaController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Configuración de Empresa');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css"); 
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/libraries/jquery_ui/core.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/ripple.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
			->addJs($this->baseUri . "public/js/comunicaciondebaja.js?i=v2");
			
    }
    
    public function getInfoDocumentoAction() {
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

            $data_doc_bd['id_contribuyente'] = $usuario->id_contribuyente;
            $data_doc_bd['id_tipodoc_electronico'] = $datapost['tipodoc'];
            $data_doc_bd['serie_comprobante'] = $datapost['serie'];
            $data_doc_bd['numero_comprobante'] = $datapost['numero'];

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el contribuyente!';
                return $resp;
            }
            
            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $data_doc_bd['id_contribuyente'], 'id_tipodoc_electronico' => $data_doc_bd['id_tipodoc_electronico'], 'serie_comprobante' => $data_doc_bd['serie_comprobante'], 'numero_comprobante' => $data_doc_bd['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el documento!';
                return $resp;
            }

            if($documento->estado_envio_sunat != 'aceptado') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No es posible dar de baja a un documento que no fué aceptado o aún no ha sido enviado a sunat!';
                return $resp;
            }

            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));

            $resp['respuesta'] = 'ok';
            $resp['documento'] = $documento;
            $resp['nombre'] = $tipo_comprobante->descripcion;
            echo json_encode($resp);
            exit();
        }
    }

	public function crearComunicacionBajaAction() {
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

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el contribuyente!';
                return $resp;
            }
            
            $data_doc_bd['id_contribuyente'] = $usuario->id_contribuyente;
            $data_doc_bd['id_tipodoc_electronico'] = $datapost['tipodoc'];
            $data_doc_bd['serie_comprobante'] = $datapost['serie'];
            $data_doc_bd['numero_comprobante'] = $datapost['numero'];
            $motivo = !isset($datapost['motivo'])?'':$datapost['motivo'];
            $confirmacion = !isset($datapost['confirmacion'])?'':$datapost['confirmacion'];
            
            $data_doc_bd['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
            $data_doc_bd['idusuario'] = $usuario->idusuario;
            $data_doc_bd['tipo_log'] = 'comunicacion_baja';

            $gestion_usuarios = new GestionuserController;
            if($data_doc_bd['id_tipodoc_electronico'] == '01') {
                if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_factura')) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Usted no tiene permisos para anular una Factura!';
                    return $resp;
                }
            }

            if($data_doc_bd['id_tipodoc_electronico'] == '07') {
                if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_notas_credito')) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Usted no tiene permisos para anular una Nota de Crédito!';
                    return $resp;
                }
            }

            if($data_doc_bd['id_tipodoc_electronico'] == '08') {
                if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_notas_debito')) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Usted no tiene permisos para anular una Nota de Débito!';
                    return $resp;
                }
            }
            
            if($confirmacion == 'si') {
                $log = new LogsController;
                $resp_log = $log->log_documento($data_doc_bd);
            }

            $resp = $this->crear_comunicacion_baja($data_doc_bd, $motivo, $confirmacion, $usuario->idusuario);
            echo json_encode($resp);
            exit();
        }
	}

	public function crear_comunicacion_baja($data_doc_bd, $descripcion, $confirmacion, $idusuario) {
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
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el documento!';
			return $resp;
		}

		if($documento->estado_envio_sunat != 'aceptado') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No es posible dar de baja a un documento que no fué aceptado o aún no ha sido enviado a sunat!';
			return $resp;
		}

		if(empty($descripcion)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Deberías agregar un motivo para dar de baja el presente documento!!';
			return $resp;
		}

		$documentoelectronico = new DocumentoelectronicoController;
        $resp_data_emisor = $documentoelectronico->get_data_emisor($id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
        }
        
        $resp_secret_data = $documentoelectronico->get_secret_data($id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
		}
		
		$codigo = "RA";
        $serie = date('Ymd');
        //$secuencia = intval(date('Gis')); //$this->get_secuencia_resumen($id_contribuyente, $codigo, $serie, $contribuyente->tipo_envio_sunat);

        $fecha1 = new DateTime(date("Y-m-d 00:00:00")); //fecha inicio del día
        $fecha2 = new DateTime(); //fecha y hora actual
        $intervalo = $fecha1->diff($fecha2);
		$secuencia = intval($intervalo->h.$intervalo->i) + intval($intervalo->s);

		$fecha_documentos = date("Y-m-d", strtotime($documento->fecha_comprobante));
		$cabecera = array(
            //Cabecera del documento
            "codigo"						=> $codigo,
            "serie"							=> $serie,
            "secuencia"             		=> $secuencia,
            "fecha_referencia"             	=> $fecha_documentos, //Fecha de emisión de los documentos (yyyy-mm-dd)
            "fecha_baja"          			=> date('Y-m-d') //Fecha de generación del resumen (yyyy-mm-dd)
		);
		
		$detalle[] = array (
			"ITEM_DET"          	=> 1,
			"TIPO_COMPROBANTE"  	=> $documento->id_tipodoc_electronico,
			"SERIE"           		=> $documento->serie_comprobante,
			"NUMERO"            	=> $documento->numero_comprobante,
			"MOTIVO"          		=> $descripcion
		);

		$data = $cabecera;
		$data['emisor'] = $resp_data_emisor['emisor'];
        $data['ruc_proveedor'] = $this->ruc_proveedor;
        $data['detalle'] = $detalle;
		$data['secret_data'] =  $resp_secret_data['secret_data'];
		
		$ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'comunicacionesbajas/';
		if (!file_exists($ruta_base_cpe_cdr)) {
			mkdir($ruta_base_cpe_cdr, 0777, true);
		}
		
		if($confirmacion != 'si') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Debes confirmar esta acción!';
            $resp['mensaje'] = '<string class="text-danger">¿Realmente deseas dar de baja el presente documento?</strong> No podrás revertir esta acción!...';
            return $resp;
		}
		
		$herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/comunicacionbaja';
		$resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);

		$resp_api = json_decode($resp_api_ws);
		if(empty($resp_api)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = 'Debes intentarlo luego. Las webservices de la SUNAT no respondieron correctamente, obtenemos el siguiente error: '.$resp_api_ws;
			return $resp;
        }
        
        $resp['response2'] = $resp_api_ws;
        $resp['resp_api'] = $resp_api;
		if($resp_api->respuesta == 'error') {
            //Si ingresa aquí debemos verificar si es un error en el envío o un error en la recepción del cdr
            if($resp_api->codigo_error_api == 'error_envio_baja') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error al Enviar a Sunat';
                $resp['mensaje'] = $resp_api->msj_sunat;

                $documento->estado_envio_sunat = 'pendiente';
                $msg = '';
                try {
                    if(!$documento->save()) {
                        $msg = '';
                        foreach ($documento->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $msg = $e->getMessage();
                }

                $resp['mensaje'] = $resp['mensaje'].' '.$msg;
                
                return $resp;
            } else if($resp_api->codigo_error_api == 'error_consulta_ticket') {

                $this->db->begin();
                $new_comunicacionbaja = new ComunicacionBaja();
                $new_comunicacionbaja->id_contribuyente = $id_contribuyente;
                $new_comunicacionbaja->codigo = $data['codigo'];
                $new_comunicacionbaja->serie = $data['serie'];
                $new_comunicacionbaja->secuencia = $data['secuencia'];
                $new_comunicacionbaja->fecha_documentos = $fecha_documentos;
                $new_comunicacionbaja->fecha_envio_sunat = date('Y-m-d H:i:s');
                $new_comunicacionbaja->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
                $new_comunicacionbaja->numero_ticket = $resp_api->codigo_ticket;
                $new_comunicacionbaja->estado_envio_sunat = 'ticket';
                $new_comunicacionbaja->ruta_xml = $data['secret_data']['ruta_dir_xml'];
                $new_comunicacionbaja->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
                $new_comunicacionbaja->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;
                $new_comunicacionbaja->estado_documento = 'activo';
                $new_comunicacionbaja->intentos_envio_sunat = 1;
                $new_comunicacionbaja->tipo_envio_sunat = $data['secret_data']['tipo_proceso'];
                $new_comunicacionbaja->detalle = json_encode($documento);
                $new_comunicacionbaja->motivo = $descripcion;

                try {
                    if(!$new_comunicacionbaja->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($new_comunicacionbaja->getMessages() as $message) {
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
                    $msg = $e->getMessage();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    return $resp;
                }

                $resp_asignar_baja = $this->registrar_idbaja_en_documento($documento, $data, $resp_api, $idusuario);
                if($resp_asignar_baja['respuesta'] == 'error') {
                    $this->db->rollback();
                    return $resp_asignar_baja;
                }

                $saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));

                $this->db->commit();
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envio Correcto!';
                $resp['mensaje'] = 'La comunicación de Baja '.$data['codigo'].'-'.$data['serie'].'-'.$data['secuencia'].' fué enviada correctamente, sin embargo aún no se pudo recepcionar el CDR, el número de ticket es el siguiente: '.$resp_api->codigo_ticket;
                return $resp;

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Desconocido!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                return $resp;
            }
		} else if($resp_api->respuesta == 'ok') {
            if($resp_api->cod_sunat == '0' || $resp_api->cod_sunat == '2324' || $resp_api->cod_sunat == '2323' || $resp_api->cod_sunat == 'soap-env:Client.0402') {
                //2223: El archivo ya fue presentado anteriormente (se entiende que esta factura ya fué enviada en una solicitud anterior, por tanto se la registra como enviada)
                //0: El archivo fué aceptado

                $this->db->begin();
                $new_comunicacionbaja = new ComunicacionBaja();
                $new_comunicacionbaja->id_contribuyente = $id_contribuyente;
                $new_comunicacionbaja->codigo = $data['codigo'];
                $new_comunicacionbaja->serie = $data['serie'];
                $new_comunicacionbaja->secuencia = $data['secuencia'];
                $new_comunicacionbaja->fecha_documentos = $fecha_documentos;
                $new_comunicacionbaja->fecha_envio_sunat = date('Y-m-d H:i:s');
                $new_comunicacionbaja->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
                $new_comunicacionbaja->numero_ticket = $resp_api->codigo_ticket;
                $new_comunicacionbaja->estado_envio_sunat = 'aceptado';
                $new_comunicacionbaja->ruta_xml = $data['secret_data']['ruta_dir_xml'];
                $new_comunicacionbaja->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
                $new_comunicacionbaja->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;
                $new_comunicacionbaja->estado_documento = 'activo';
                $new_comunicacionbaja->intentos_envio_sunat = 1;
                $new_comunicacionbaja->tipo_envio_sunat = $data['secret_data']['tipo_proceso'];
                $new_comunicacionbaja->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
                $new_comunicacionbaja->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
                $new_comunicacionbaja->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                $new_comunicacionbaja->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
                $new_comunicacionbaja->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
                $new_comunicacionbaja->detalle = json_encode($documento);
                $new_comunicacionbaja->motivo = $descripcion;
                
                $saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
                $saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));

                try {
                    if(!$new_comunicacionbaja->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($new_comunicacionbaja->getMessages() as $message) {
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
                    $msg = $e->getMessage();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    return $resp;
                }

                $resp_asignar_baja = $this->registrar_idbaja_en_documento($documento, $data, $resp_api, $idusuario);
                if($resp_asignar_baja['respuesta'] == 'error') {
                    $this->db->rollback();
                    return $resp_asignar_baja;
                }

                $this->db->commit();
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envio Correcto!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                return $resp;

            } else {
                //aquí podemos trabajar con las posibles errores de rechazo!
                //http://cpe.sunat.gob.pe/boleta-de-venta-desde-los-sistemas-del-contribuyente (sección preguntas frecuentes)
                //indica que en caso  haya errores se debe enviar un nuevo resumen solucionando o reparando los errores indicados!
                
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envio Correcto!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                return $resp;
            }
        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error Desconocido!';
            $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
            return $resp;
        }
	}

	public function registrar_idbaja_en_documento($documento, $data, $resp_api, $idusuario) {
		$documento->rb_id_contribuyente = $documento->id_contribuyente;
		$documento->rb_codigo = $data['codigo'];
		$documento->rb_serie = $data['serie'];
		$documento->rb_secuencia = $data['secuencia'];
		$documento->rb_tipo_envio_sunat = $documento->tipo_envio_sunat;
		$documento->estado_envio_sunat = 'anulado';
		$documento->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
		$documento->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
		$documento->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
		$documento->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
		$documento->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
		
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
            $msg = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = $msg;
            return $resp;
        }
        
        $detalle_documento = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 'serie_comprobante' => $documento->serie_comprobante, 'numero_comprobante' => $documento->numero_comprobante, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

        foreach($detalle_documento as $item) {
            $producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto)));
            $cantidad_kardex = $item->cantidad;
            if(isset($item->id_presentacion) && intval($item->id_presentacion) > 0) {
                $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item->id_presentacion))));
                if($presentacion) {
                    $cantidad_kardex = round($item->cantidad*$presentacion->cantidad_und_base, 4);
                }
            }

            if(isset($lista_productos_doc[$item->id_producto])) {
                $lista_productos_doc[$item->id_producto] = $lista_productos_doc[$item->id_producto] + $cantidad_kardex;
            } else {
                $lista_productos_doc[$item->id_producto] = $cantidad_kardex;
            }
        }

        foreach($detalle_documento as $item) {
            $producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto)));
            if($producto) {
                /*
                $cantidad_kardex = $item->cantidad;
                if(isset($item->id_presentacion) && intval($item->id_presentacion) > 0) {
                    $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item->id_presentacion))));
                    if($presentacion) {
                        $cantidad_kardex = round($item->cantidad*$presentacion->cantidad_und_base, 4);
                    }
                }
                */
                $cantidad_kardex = $lista_productos_doc[$item->id_producto];

				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
				if($unidad_medida) {
					if($unidad_medida->codigo != 'ZZ') {
                        if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03' || $documento->id_tipodoc_electronico == '08') {
                            //primero verificamos que no exista un registro de venta eliminada, para evitar que se creen registros duplicados
                            $kardex_doc_anulado = Kardex::findFirst(array("
                                docref_id_contribuyente         = :docref_id_contribuyente: and 
                                docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                docref_serie_comprobante        = :docref_serie_comprobante: and 
                                docref_numero_comprobante       = :docref_numero_comprobante: and 
                                docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                idproducto                      = :idproducto: and 
                                id_compra                       is null and 
                                tipo_kardex                     = 'anulacion_venta'
                            ", 'bind' => array(
                                'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                'docref_id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 
                                'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                'idproducto'                    => $producto->idproducto
                            ), "order" => "id_kardex DESC"));

                            if(!$kardex_doc_anulado) {
                                //Una factura, boleta y nota de débito anulada representa un ingreso, ya que el producto vuelve a ingresar a almacén.
                                $kardex_guardado = Kardex::findFirst(array("
                                    docref_id_contribuyente         = :docref_id_contribuyente: and 
                                    docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                    docref_serie_comprobante        = :docref_serie_comprobante: and 
                                    docref_numero_comprobante       = :docref_numero_comprobante: and 
                                    docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                    idproducto                      = :idproducto: and 
                                    id_compra                       is null
                                ", 'bind' => array(
                                    'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                    'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                    'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                    'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                    'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                    'idproducto'                    => $producto->idproducto
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
                                    $msg = $e->getMessage();
                                    $resp['respuesta'] = 'error';
                                    $resp['titulo'] = 'Error';
                                    $resp['mensaje'] = $msg;
                                    return $resp;
                                }
                            }
                        }
            
                        if($documento->id_tipodoc_electronico == '07') {
                            $kardex_doc_anulado = Kardex::findFirst(array("
                                    docref_id_contribuyente         = :docref_id_contribuyente: and 
                                    docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                    docref_serie_comprobante        = :docref_serie_comprobante: and 
                                    docref_numero_comprobante       = :docref_numero_comprobante: and 
                                    docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                    idproducto                      = :idproducto: and 
                                    id_compra                       is null and 
                                    tipo_kardex                     = 'anulacion_nota_credito'
                                ", 'bind' => array(
                                    'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                    'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                    'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                    'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                    'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                    'idproducto'                    => $producto->idproducto
                                ), "order" => "id_kardex DESC"));

                            if(!$kardex_doc_anulado && ($documento->id_cod_tipomotivo_credito == '01')) {
                                //aquí básicamente debemos verificar si existe alguna modificacion en el kardex que se deba revertir.
                                $kardex_guardado = Kardex::findFirst(array("
                                        docref_id_contribuyente         = :docref_id_contribuyente: and 
                                        docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                        docref_serie_comprobante        = :docref_serie_comprobante: and 
                                        docref_numero_comprobante       = :docref_numero_comprobante: and 
                                        docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                        idproducto                      = :idproducto: and 
                                        id_compra                       is null
                                    ", 'bind' => array(
                                        'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                        'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                        'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                        'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                        'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                        'idproducto'                    => $producto->idproducto
                                    ), "order" => "id_kardex DESC"));

                                if($kardex_guardado) {
                                    $data_kardex['id_contribuyente'] = $documento->id_contribuyente;
                                    $data_kardex['idsucursal'] = $documento->id_sucursal;
                                    $data_kardex['idproducto'] = $item->id_producto;
                                    $data_kardex['idusuario'] = $idusuario;
                                    $data_kardex['tipo_envio_sunat'] = $documento->tipo_envio_sunat; //prueba, produccion
                                    $data_kardex['tipo_kardex'] = 'anulacion_nota_credito'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
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
                                        $msg = $e->getMessage();
                                        $resp['respuesta'] = 'error';
                                        $resp['titulo'] = 'Error';
                                        $resp['mensaje'] = $msg;
                                        return $resp;
                                    }
                                }
                            }
                        }
                    }
                }
            }
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

        if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08') {
            $nota_credito_controller = new NotadecreditoController;
            $data_doc_electronicoxnota['nota_estado_envio_sunat'] = $documento->estado_envio_sunat;
            $resp_doc_electronicoxnota = $nota_credito_controller->guardar_registro_doc_electronicoxnota($data_doc_electronicoxnota);
            if($resp_doc_electronicoxnota['respuesta'] == 'error') {
                //return $resp_doc_electronicoxnota;
            }
        }

		$resp['respuesta'] = 'ok';
		return $resp;
	}

	public function get_secuencia_resumen($id_contribuyente, $codigo, $serie, $tipo_envio_sunat) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        
        //Se hizo la modificación al algoritmo para extraer la secuencia porque existen empresas que comparten el mismo RUC
        $lista_contribuyentes = Contribuyente::find(array("ruc = :ruc:", 'bind' => array('ruc' => $contribuyente->ruc)));

        if(count($lista_contribuyentes) <= 1) {
            $baja = ComunicacionBaja::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'codigo' => $codigo, 'serie' => $serie, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "secuencia DESC"));
        } else {
            $lista_ids = array();
            foreach($lista_contribuyentes as $item) {
                $lista_ids[] = $item->id_contribuyente;
            }
            
            $sql = "id_contribuyente in (".implode(',', $lista_ids).") and codigo = :codigo: and serie = :serie: and tipo_envio_sunat = :tipo_envio_sunat:";
            $baja = ComunicacionBaja::findFirst(array($sql, 'bind' => array('codigo' => $codigo, 'serie' => $serie, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "secuencia DESC"));
        }
        
        if(!$baja) {
            if($contribuyente->correlativo_comunicacion_bajas > 1) {
                return $contribuyente->correlativo_comunicacion_bajas;
            }
            return 1;
        }
        
        return $baja->secuencia + 1;
    }
}