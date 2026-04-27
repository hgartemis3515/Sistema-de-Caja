<?php
class GuiatransportistaController extends ControllerBase {
    public function indexAction($tipo_doc_guardado = '', $serie_doc_guardado = '', $numero_doc_guardado = '') {
        $this->setTitle('Guía Transportista');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
        ->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css")
        ->addCss($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.min.css")
        ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.date.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.time.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/legacy.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/daterangepicker.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/anytime.min.js?i=v2")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
		->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/apisunat.js?i=".rand())
        ->addJs($this->baseUri . "public/js/guiatransportista/guiatransportista_flexdatalist.js?i=".rand())
        ->addJs($this->baseUri . "public/js/guiatransportista/tabla_items_detalle.js?i=".rand())
        ->addJs($this->baseUri . "public/js/guiatransportista/index.js?i=".rand());

        //Para sesion
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];

        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

        $html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$this->dispatcher->forward(array(
				'controller' => 'estadosuscripcion',
				'action'     => 'index'
			));
			return false;
		}
		
		$this->view->html_suscripcion = $html_suscripcion['html'];

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
        }

        $this->view->tipo_doc_guardado = $tipo_doc_guardado;
        $this->view->serie_doc_guardado = $serie_doc_guardado;
        $this->view->numero_doc_guardado = $numero_doc_guardado;
        $this->view->modalidad_envio_sunat = $contribuyente->modalidad_envio_sunat;
        $this->view->contribuyente = $contribuyente;
    }

    public function sugerenciasDatosTransportistaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $array_lista = array();
            if(!$usuario) {
                echo json_encode($array_lista);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                echo json_encode($array_lista);
                exit();
            }

            $query = "SELECT DISTINCT transporte_nro_placa, num_registro_mtc, tuc_vehiculo_principal FROM `guia_transportista` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '31' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat'";
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                //exit();
                echo json_encode($array_lista);
                exit();
            }
            
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array(
                    'transporte_nro_placa'      => $fila->transporte_nro_placa, 
                    'num_registro_mtc'          => $fila->num_registro_mtc, 
                    'tuc_vehiculo_principal'    => $fila->tuc_vehiculo_principal
                );
            }

            echo json_encode($array_lista);
            exit();
        }
    }

    public function sugerenciasConductorAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $array_lista = array();
            if(!$usuario) {
                echo json_encode($array_lista);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                echo json_encode($array_lista);
                exit();
            }

            $tipo_documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $datapost['id_tipo_doc_conductor'])));
            if(!$tipo_documento_identidad) {
                echo json_encode($array_lista);
                exit();
            }

            $query = "SELECT DISTINCT conductor_tipo_documento, conductor_nro_documento, conductor_nombre_completo, conductor_nro_licencia FROM `guia_transportista` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '31' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat' and conductor_tipo_documento = $tipo_documento_identidad->id_tipodocidentidad";
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                //exit();
                echo json_encode($array_lista);
                exit();
            }
            
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array(
                    'conductor_tipo_documento'  => $fila->conductor_tipo_documento, 
                    'conductor_nro_documento'   => $fila->conductor_nro_documento, 
                    'conductor_nombre_completo' => $fila->conductor_nombre_completo,
                    'conductor_nro_licencia'    => $fila->conductor_nro_licencia
                );
            }

            echo json_encode($array_lista);
            exit();
        }
    }

    public function sugerenciasRemitenteAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $array_lista = array();
            if(!$usuario) {
                echo json_encode($array_lista);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                echo json_encode($array_lista);
                exit();
            }

            $tipo_documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $datapost['id_tipodoc_destinatario'])));
            if(!$tipo_documento_identidad) {
                echo json_encode($array_lista);
                exit();
            }

            $query = "SELECT DISTINCT remitente_tipo_documento, remitente_numero_documento, remitente_nombre_completo FROM `guia_transportista` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '31' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat' and remitente_tipo_documento = $tipo_documento_identidad->id_tipodocidentidad";
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                //exit();
                echo json_encode($array_lista);
                exit();
            }
            
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array(
                    'remitente_tipo_documento'   => $fila->remitente_tipo_documento, 
                    'remitente_numero_documento' => $fila->remitente_numero_documento, 
                    'remitente_nombre_completo'  => $fila->remitente_nombre_completo
                );
            }

            echo json_encode($array_lista);
            exit();
        }
    }

    public function sugerenciasDestinatarioAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $array_lista = array();
            if(!$usuario) {
                echo json_encode($array_lista);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                echo json_encode($array_lista);
                exit();
            }

            $tipo_documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $datapost['id_tipodoc_destinatario'])));
            if(!$tipo_documento_identidad) {
                echo json_encode($array_lista);
                exit();
            }

            $query = "SELECT DISTINCT dest_tipo_documento, dest_numero_documento, dest_nombre_completo FROM `guia_transportista` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '31' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat' and dest_tipo_documento = $tipo_documento_identidad->id_tipodocidentidad";
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                //exit();
                echo json_encode($array_lista);
                exit();
            }
            
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array(
                    'dest_tipo_documento'   => $fila->dest_tipo_documento, 
                    'dest_numero_documento' => $fila->dest_numero_documento, 
                    'dest_nombre_completo'  => $fila->dest_nombre_completo
                );
            }

            echo json_encode($array_lista);
            exit();
        }
    }

    public function guardarGuiaTransportistaAction() {
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
            
            $resp = $this->procesar_guia_transportista($usuario, $datapost);
            echo json_encode($resp);
            exit();
        }
    }

    public function procesar_guia_transportista($usuario, $datapost) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra el contribuyente al que pertenece el usuario!';
            return $resp;
        }

        $gestion_de_contribuyentes = new GestiondecontribuyentesController;
        if($gestion_de_contribuyentes->get_value_in_option_system($contribuyente->id_contribuyente, 'permitir_emision_guias_transportista') == 'no') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No tienes permitido emitir Guías de Transportista.';
            return $resp;
        }
        
        $data['idsucursal'] = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
        $data['fecha_comprobante'] = !isset($datapost['fecha_comprobante'])?date('d/m/Y'):$datapost['fecha_comprobante'];
        $data['fecha_traslado'] = !isset($datapost['fecha_traslado'])?date('d/m/Y'):$datapost['fecha_traslado'];
        $data['pesobruto'] = empty($datapost['pesobruto'])?0:floatval($datapost['pesobruto']);

        //datos del remitente
        $data['remitente_tipo_documento'] = !isset($datapost['tipo_documento_remitente'])?0:intval($datapost['tipo_documento_remitente']);
        $data['remitente_numero_documento'] = !isset($datapost['nro_documento_remitente'])?'':$datapost['nro_documento_remitente'];
        $data['remitente_nombre_completo'] = !isset($datapost['nombre_remitente'])?'':$datapost['nombre_remitente'];

        //Datos del Transportista
        $data['transporte_nro_placa'] = !isset($datapost['transporte_nro_placa'])?'':$datapost['transporte_nro_placa'];
        $data['num_registro_mtc'] = !isset($datapost['num_registro_mtc'])?'':$datapost['num_registro_mtc'];
        $data['tuc_vehiculo_principal'] = !isset($datapost['tuc_vehiculo_principal'])?'':$datapost['tuc_vehiculo_principal'];
        
        //Datos del Conductor
        $data['conductor_tipo_documento'] = !isset($datapost['tipo_documento_conductor'])?0:intval($datapost['tipo_documento_conductor']);
        $data['conductor_nro_documento'] = !isset($datapost['nro_documento_conductor'])?'':$datapost['nro_documento_conductor'];
        $data['conductor_nombre_completo'] = !isset($datapost['nombre_conductor'])?'':$datapost['nombre_conductor'];
        $data['conductor_nro_licencia'] = !isset($datapost['conductor_num_licencia'])?'':$datapost['conductor_num_licencia'];
        
        //Datos del Destinatario
        $data['dest_tipo_documento'] = !isset($datapost['tipo_documento_destinatario'])?0:intval($datapost['tipo_documento_destinatario']);
        $data['dest_numero_documento'] = !isset($datapost['nro_documento_destinatario'])?'':$datapost['nro_documento_destinatario'];
        $data['dest_nombre_completo'] = !isset($datapost['nombre_destinatario'])?'':$datapost['nombre_destinatario'];

        //Punto de Partida
        $data['direccionpartida'] = !isset($datapost['direccionpartida'])?'':$datapost['direccionpartida'];
        $data['id_ubigeo_partida'] = !isset($datapost['ubigeo_partida'])?'':$datapost['ubigeo_partida'];
        $data['codigo_local_partida'] = !isset($datapost['codigo_local_partida'])?'':$datapost['codigo_local_partida'];

        //Punto de Llegada
        $data['direccionllegada'] = !isset($datapost['direccionllegada'])?'':$datapost['direccionllegada'];
        $data['id_ubigeo_llegada'] = !isset($datapost['ubigeo_llegada'])?'':$datapost['ubigeo_llegada'];
        $data['codigo_local_llegada'] = !isset($datapost['codigo_local_llegada'])?'':$datapost['codigo_local_llegada'];

        $data['detalle_guia'] = $datapost['detalle_guia'];

        $data['nota'] = !isset($datapost['informacion_adicional_sunat'])?'':$datapost['informacion_adicional_sunat'];

        $resp_validacion = $this->validar_datapost_guia_transportista($usuario, $data);
        if($resp_validacion['respuesta'] == 'error') {
            return $resp_validacion;
        }

        $data_guia_transportista = $resp_validacion['data'];

        $confirmacion = !empty($datapost['confirmacion'])?$datapost['confirmacion']:'no';
        if($confirmacion == 'no') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Necesitamos tu Confirmación!';
            $resp['mensaje'] = '¿Realmente deseas crear esta guía de remisión? Recuerda que no podrás anular la guía de remisión.';
            return $resp;
        }
        
        $resp_guardar_bd = $this->guardar_guia_transportista_bd($contribuyente, $usuario, $data_guia_transportista);
        if($resp_guardar_bd['respuesta'] == 'error') {
            return $resp_guardar_bd;
        }

        $resp_envio_sunat = $this->enviar_guiatransportista_sunat($resp_guardar_bd['data_guia_transportista']);
        $resp['mensaje'] = 'Se guardó correctamente la Guía Tranpostista y también se envió a SUNAT.';
        $resp['titulo'] = 'Éxito';
        if($resp_envio_sunat['respuesta'] == 'error') {
            $resp['titulo'] = 'Guardado correctamente en el Sistema';
            $resp['mensaje'] = 'Se guardó correctamente la Guía de Transportista, <strong class="text-danger">pero no se pudo enviar a SUNAT. Error SUNAT: '.$resp_envio_sunat['mensaje'].'</strong>';
        }
        
        $resp['respuesta'] = 'ok';
        
        $resp['data_guia'] = $resp_guardar_bd['data_guia_transportista'];
        $resp['enlaces'] = $this->get_enlaces_guia_transportista($resp_guardar_bd['data_guia_transportista'])['enlaces'];

        return $resp;
    }

    public function get_enlaces_guia_transportista($data_guia_transportista) {
        $herramientas = new HerramientasController;

        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        $id_contribuyente       = $data_guia_transportista['id_contribuyente'];
        $id_tipodoc_electronico = $data_guia_transportista['id_tipodoc_electronico'];
        $serie_comprobante      = $data_guia_transportista['serie_comprobante'];
        $numero_comprobante     = $data_guia_transportista['numero_comprobante'];
        $tipo_envio_sunat       = $data_guia_transportista['tipo_envio_sunat'];
        $id_usuario             = $data_guia_transportista['id_usuario'];

        $guia_transportista = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if(!$guia_transportista) {
            $resp['respuesta'] = 'ok';
            $resp['enlaces'] = array();
            return $resp;
        }

        $string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||$id_tipodoc_electronico||$serie_comprobante||$numero_comprobante||$tipo_envio_sunat||a4");
        $string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||$id_tipodoc_electronico||$serie_comprobante||$numero_comprobante||$tipo_envio_sunat||ticket");

        $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
        $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

        $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
        $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
        
        $btn_pdf = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';
        $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';
        
        
        $ruta_base_cpe = $guia_transportista->ruta_xml;
        $ruta_xml_zip = $guia_transportista->ruta_xml.$guia_transportista->name_xml_zip;
        $ruta_xml_cdr = $guia_transportista->ruta_xml.$guia_transportista->name_cdr_zip;

        $ruta_xml = '';
        $btn_xml = '';
        if (file_exists($ruta_xml_zip)) {
            //Existe el XML FIRMADO
            $ruta_xml = "/facturacionv8/download/downloadcpe/$guia_transportista->id_contribuyente/$guia_transportista->id_tipodoc_electronico/$guia_transportista->serie_comprobante/$guia_transportista->numero_comprobante/xml_cpe_zip";
            $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="'.$ruta_xml.'"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="width: 30px;"></a>';
        } else {
            $btn_xml = '<a onclick=\'enviar_documento_sunat('.$guia_transportista->id_contribuyente.', "'.$guia_transportista->id_tipodoc_electronico.'", "'.$guia_transportista->serie_comprobante.'", '.$guia_transportista->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
        }

        $ruta_cdr = '';
        if(file_exists($ruta_xml_cdr)) {
            $ruta_cdr = "/facturacionv8/download/downloadcpe/$guia_transportista->id_contribuyente/$guia_transportista->id_tipodoc_electronico/$guia_transportista->serie_comprobante/$guia_transportista->numero_comprobante/xml_cdr_zip";
        }

        $btn_envio_whatsapp = '';
        $btn_var_whatsapp = "'$guia_transportista->id_tipodoc_electronico', '$guia_transportista->serie_comprobante', '$guia_transportista->numero_comprobante', '$dominio_principal', '$url_a4', '$url_ticket', '/facturacionv8/download/downloadcpe/$guia_transportista->id_contribuyente/$guia_transportista->id_tipodoc_electronico/$guia_transportista->serie_comprobante/$guia_transportista->numero_comprobante/xml_cpe_zip', 'numero_celular', '$guia_transportista->dest_nombre_completo'";
        $btn_envio_whatsapp = '<li><a href="javascript:void(0)" onclick="enviar_mensaje_whatsapp('.$btn_var_whatsapp.')"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 20px;"> Enviar WhatsApp</a></li>';

        /*
        $url_whatsapp = '';
		$url_whatsapp_api = '';
		$url_whatsapp_web = '';

        if(!empty($cliente['data_cliente']['cliente_celular'])) {
			$mensaje_whatsapp = 'Gracias por confiar en nosotros, desde los siguientes enlaces puedes descargar documento electrónico: Formato A4: '.$url_a4.' y Formato Ticket: '.$url_ticket;
			$url_whatsapp_api = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp_web = 'https://web.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
			$url_whatsapp = 'https://api.whatsapp.com/send?phone=51'.$cliente['data_cliente']['cliente_celular'].'&text='.urlencode($mensaje_whatsapp);
		}
        */

        $resp['respuesta'] = 'ok';
        $resp['enlaces'] = array(
            'btn_pdf'           => $btn_pdf,
            'btn_pdf_ticket'    => $btn_pdf_ticket,
            'url_a4'            => $url_a4,
            'url_ticket'        => $url_ticket,
            'btn_xml'           => $btn_xml,
            'btn_envio_whatsapp' => $btn_envio_whatsapp,
            'ruta_cdr'          => $ruta_cdr
        );

        return $resp;
    }

    public function get_data_json($data) {
        $documentoelectronico = new DocumentoelectronicoController;
        $herramientas = new HerramientasController;

        $id_contribuyente       = $data['id_contribuyente'];
        $id_tipodoc_electronico = $data['id_tipodoc_electronico'];
        $serie_comprobante      = $data['serie_comprobante'];
        $numero_comprobante     = $data['numero_comprobante'];
        $tipo_envio_sunat       = $data['tipo_envio_sunat'];

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $guia_transportista = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if(!$guia_transportista) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la Guía de Transportista.';
            return $resp;
        }

		$resp_secret_data = $documentoelectronico->get_secret_data($id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
		}

        $resp_data_emisor = $documentoelectronico->get_data_emisor($id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
		}

        $resp_detalle = $this->get_detalle_guia_transportista($data);
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$emisor = $resp_data_emisor['emisor'];
        $detalle = $resp_detalle['detalle'];

        $ubigeo_ubicacion_partida = '';
		$ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $guia_transportista->id_ubigeo_partida)));
		if($ubigeo_partida) {
			$ubigeo_ubicacion_partida = $ubigeo_partida->distrito.', '.$ubigeo_partida->provincia.', '.$ubigeo_partida->departamento;
		}

        $ubigeo_ubicacion_destino = '';
		$ubigeo_destino = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $guia_transportista->id_ubigeo_destino)));
		if($ubigeo_destino) {
			$ubigeo_ubicacion_destino = $ubigeo_destino->distrito.', '.$ubigeo_destino->provincia.', '.$ubigeo_destino->departamento;
		}

        $cabecera = array(
            'cod_tipo_documento'    => $id_tipodoc_electronico,
            'serie_comprobante'     => $serie_comprobante,
            'numero_comprobante'    => $numero_comprobante,
            'fecha_comprobante'     => $guia_transportista->fecha_comprobante,
            'hora_comprobante'      => date('H:i:s', strtotime($guia_transportista->fecha_registro)),
            'nota'                  => $guia_transportista->nota,
            'documento_nota'        => $guia_transportista->nota,
            
            'peso_total'            => $guia_transportista->peso_total,
            'peso_total_tne'        => !isset($guia_transportista->peso_total)?'':round($guia_transportista->peso_total/1000, 2),
            'umedida_peso_total'    => 'KGM',
            
            'transporte_nro_placa'  => $guia_transportista->transporte_nro_placa,
            'num_registro_mtc'      => $guia_transportista->num_registro_mtc, //Alfanumérico hasta 20 caracteres (es opcional)
            'tuc_vehiculo_principal'=> $guia_transportista->tuc_vehiculo_principal,
            
            //Datos del punto de partida
            'id_ubigeo_partida'     => $guia_transportista->id_ubigeo_partida,
            'dir_partida'           => $guia_transportista->dir_partida.' '.$ubigeo_ubicacion_partida,
            'codigo_local_anexo'    => $guia_transportista->codigo_local_anexo,
        
            //datos del punto de llegada
            'fecha_traslado'        => $guia_transportista->fecha_traslado,
            'id_ubigeo_destino'     => $guia_transportista->id_ubigeo_destino,
            'dir_destino'           => $guia_transportista->dir_destino.' '.$ubigeo_ubicacion_destino,

            'ruta_imagen_qr'	    => $guia_transportista->ruta_qr,
            'guia_ruta_qr'          => $guia_transportista->ruta_qr,
            "nombre_cpe"            => "Guía de Remisión Electrónica Transportista",
            "correlativo"           => $herramientas->zero_fill($numero_comprobante, 6),
            "estado_documento"      => $guia_transportista->estado_envio_sunat,
            "hash"                  => ""
        );

        //remitente
        $remitente = array(
            'tipo_documento'  => empty($guia_transportista->remitente_tipo_documento)?'6':$guia_transportista->remitente_tipo_documento,
            'numero_documento'=> empty($guia_transportista->remitente_numero_documento)?$contribuyente->ruc:$guia_transportista->remitente_numero_documento,
            'nombre_completo' => empty($guia_transportista->remitente_nombre_completo)?$contribuyente->razon_social:$guia_transportista->remitente_nombre_completo
        );

        //Datos del destinatario
        $destinatario = array(
            'tipo_documento'    => $guia_transportista->dest_tipo_documento,
            'numero_documento'  => $guia_transportista->dest_numero_documento,
            'nombre_completo'   => $guia_transportista->dest_nombre_completo
        );

        $conductor = array(
            'tipo_documento'    => $guia_transportista->conductor_tipo_documento, //tipo de documento de identidad del conductor
            'nro_documento'     => $guia_transportista->conductor_nro_documento, //número de documento del conductor
            'nombres'           => '', //nombres del conductor
            'apellidos'         => '', //apellidos del conductor
            'nombre_completo'   => $guia_transportista->conductor_nombre_completo, //nombres y apellidos del conductor
            'nro_licencia'      => $guia_transportista->conductor_nro_licencia, //Número de licencia de conducir
        );

        $cliente = array(
            'tipo_documento'    => $guia_transportista->dest_tipo_documento,
            'numero_documento'  => $guia_transportista->dest_numero_documento,
            'nombre_completo'   => $guia_transportista->dest_nombre_completo
        );

        $lista_array = array();
        foreach($detalle as $item) {
            $lista_array[] = array(
                "ITEM"          => $item['ITEM_DET'], //correlativo 2 para el segundo item
                "UNIDAD_MEDIDA" => $item['UNIDAD_MEDIDA_DET'], //Catálogo No. 03 (NIU para unidades), (ZZ para servicios)
                "CANTIDAD_DET"  => $item['CANTIDAD_DET'], //Cantidad vendida
                "CODIGO"        => $item['CODIGO_PRODUCTO'],  //Código interno del producto, entre 1 y 30 caracteres
                "DESCRIPCION"   => $item['DESCRIPCION_DET'], //Nombre del producto hasta 300 caracteres
                "PESO"          => $item['PESO_DET'], //Peso aproximado en KGM
                'UNIDAD_MEDIDA_ID_DET'      => $item['UNIDAD_MEDIDA_ID_DET'],
                'IDPRODUCTO_DET'		    => $item['IDPRODUCTO_DET'],
                'TIPO_UNIDAD'				=> $item['TIPO_UNIDAD'],
                'ID_PRESENTACION'			=> $item['ID_PRESENTACION'],
            );
        }

        $data_json = array(
            'cabecera'      => $cabecera,
            'emisor'        => $emisor,
            'destinatario'  => $destinatario,
            'remitente'     => $remitente,
            'conductor'     => $conductor,
            'cliente'       => $cliente,
            'detalle'       => $lista_array,
            'secret_data'   => $resp_secret_data['secret_data']
        );

        $resp['respuesta'] = 'ok';
        $resp['data_json'] = $data_json;
        return $resp;
    }

    public function get_detalle_guia_transportista($data) {
        $id_contribuyente       = $data['id_contribuyente'];
        $id_tipodoc_electronico = $data['id_tipodoc_electronico'];
        $serie_comprobante      = $data['serie_comprobante'];
        $numero_comprobante     = $data['numero_comprobante'];
        $tipo_envio_sunat       = $data['tipo_envio_sunat'];

        $guia_transportista = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if(!$guia_transportista) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la Guía de Transportista.';
            return $resp;
        }

        $items_detalle = GuiaTransportistaDetalle::find(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
        
        $lista_array = array();
        foreach($items_detalle as $item) {

            $unidadmedida = SunatUnidadmedida::findFirst(array("codigo = :codigo:", 'bind' => array('codigo' => $item->id_unidad_medida)));
            $descripcion_unidad = $item->unidad_medida; 
            if($unidadmedida) {
                $descripcion_unidad = $unidadmedida->nombre;
            }

            $lista_array[] = array(
                'ITEM_DET'          		=> $item->item,
                'PESO_DET'      			=> $item->peso,
                'CANTIDAD_DET'              => $item->cantidad,
                'DESCRIPCION_DET'   	    => $item->descripcion,
                'CODIGO_PRODUCTO'           => $item->codigo,
                'UNIDAD_MEDIDA_ID_DET'      => $item->id_unidad_medida,
                'UNIDAD_MEDIDA_DET'         => $descripcion_unidad,
                'IDPRODUCTO_DET'		    => $item->idproducto,
                'TIPO_UNIDAD'				=> 'UND',
                'ID_PRESENTACION'			=> $item->id_presentacion
            );
        }

        $resp['respuesta'] = 'ok';
        $resp['detalle'] = $lista_array;
        return $resp;
    }

    public function get_numero_guia_transportista($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $tipo_envio_sunat, $idsucursal) {
        $guia_transportista = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "numero_comprobante DESC"));

        if(!$guia_transportista) {
            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $idsucursal)));

            return $sucursal->guia_transportista_numero;
        }

        $numero_doc = intval($guia_transportista->numero_comprobante) + 1;
		return $numero_doc;
	}

    public function guardar_guia_transportista_bd($contribuyente, $usuario, $data) {
        $numero_comprobante = $this->get_numero_guia_transportista($contribuyente->id_contribuyente, '31', $data['serie_comprobante'], $contribuyente->tipo_envio_sunat, $data['idsucursal']);

        $this->db->begin();

        $guia_transportista = new Guiatransportista();
        $guia_transportista->id_contribuyente           = $contribuyente->id_contribuyente;
        $guia_transportista->id_tipodoc_electronico     = '31';
        $guia_transportista->serie_comprobante          = $data['serie_comprobante'];
        $guia_transportista->numero_comprobante         = $numero_comprobante; 
        $guia_transportista->tipo_envio_sunat           = $contribuyente->tipo_envio_sunat;
        $guia_transportista->fecha_registro             = date('Y-m-d H:i:s');
        $guia_transportista->fecha_comprobante          = $data['fecha_documento'];
        $guia_transportista->nota                       = $data['nota'];
        $guia_transportista->fecha_traslado             = $data['fecha_traslado'];
        $guia_transportista->id_unidad_medida           = 1;
        $guia_transportista->unidad_medida              = 'KGM';
        $guia_transportista->peso_total                 = $data['pesobruto'];

        $guia_transportista->remitente_tipo_documento    = $data['remitente_tipo_documento'];
        $guia_transportista->remitente_numero_documento  = $data['remitente_numero_documento'];
        $guia_transportista->remitente_nombre_completo   = $data['remitente_nombre_completo'];

        $guia_transportista->transporte_nro_placa       = $data['transporte_nro_placa'];
        $guia_transportista->num_registro_mtc           = $data['num_registro_mtc'];
        $guia_transportista->tuc_vehiculo_principal     = $data['tuc_vehiculo_principal'];

        $guia_transportista->conductor_tipo_documento   = $data['conductor_tipo_documento'];
        $guia_transportista->conductor_nro_documento    = $data['conductor_nro_documento'];
        $guia_transportista->conductor_nombre_completo  = $data['conductor_nombre_completo'];
        $guia_transportista->conductor_nro_licencia     = $data['conductor_nro_licencia'];

        $guia_transportista->dest_tipo_documento        = $data['dest_tipo_documento'];
        $guia_transportista->dest_numero_documento      = $data['dest_numero_documento'];
        $guia_transportista->dest_nombre_completo       = $data['dest_nombre_completo'];

        $guia_transportista->id_ubigeo_partida          = $data['id_ubigeo_partida'];
        $guia_transportista->dir_partida                = $data['direccionpartida'];
        $guia_transportista->codigo_local_anexo         = !isset($data['codigo_local_partida'])?$data['codigo_local_anexo']:$data['codigo_local_partida'];
        $guia_transportista->codigo_local_partida       = !isset($data['codigo_local_partida'])?$data['codigo_local_anexo']:$data['codigo_local_partida'];

        $guia_transportista->id_ubigeo_destino          = $data['id_ubigeo_llegada'];
        $guia_transportista->dir_destino                = $data['direccionllegada'];
        $guia_transportista->codigo_local_llegada       = !isset($data['codigo_local_llegada'])?'0000':$data['codigo_local_llegada'];

        $guia_transportista->fecha_envio_sunat          = null;
        $guia_transportista->estado_envio_sunat         = 'pendiente';
        $guia_transportista->hash_cpe                   = '';
        $guia_transportista->hash_cdr                   = '';
        $guia_transportista->cod_sunat                  = '';
        $guia_transportista->msje_sunat                 = '';
        $guia_transportista->ruta_xml                   = '';
        $guia_transportista->name_xml                   = '';
        $guia_transportista->name_xml_zip               = '';
        $guia_transportista->name_cdr                   = '';
        $guia_transportista->name_cdr_zip               = '';
        $guia_transportista->intentos_envio_sunat       = '';
        $guia_transportista->estado_documento           = 'activo';
        $guia_transportista->id_vendedor                = $usuario->idusuario;
        $guia_transportista->id_sucursal                = $data['idsucursal'];
        $guia_transportista->numero_paquetes            = 1;

        try {
            if(!$guia_transportista->save()) {
                $msg = '';
                foreach ($guia_transportista->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Error al guardar la Guía de Transportista: '.$msg;
                return $resp;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            $this->db->rollback();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Error al guardar la Guía de Transportista: '.$e->getMessage();
            return $resp;
        }

        $n = 0;
        foreach($data['lista_detalle'] as $item) {
            $n++;
            $guia_transportista_detalle = new GuiaTransportistaDetalle();
            $guia_transportista_detalle->id_contribuyente       = $guia_transportista->id_contribuyente;
            $guia_transportista_detalle->id_tipodoc_electronico = $guia_transportista->id_tipodoc_electronico;
            $guia_transportista_detalle->serie_comprobante      = $guia_transportista->serie_comprobante;
            $guia_transportista_detalle->numero_comprobante     = $guia_transportista->numero_comprobante;
            $guia_transportista_detalle->tipo_envio_sunat       = $guia_transportista->tipo_envio_sunat;

            $guia_transportista_detalle->item                   = $n;
            $guia_transportista_detalle->id_unidad_medida       = $item['UNIDAD_MEDIDA_ID_DET'];
            $guia_transportista_detalle->unidad_medida          = $item['UNIDAD_MEDIDA_DET'];
            $guia_transportista_detalle->peso                   = $item['PESO_DET'];
            $guia_transportista_detalle->cantidad               = $item['CANTIDAD_DET'];
            $guia_transportista_detalle->idproducto             = $item['IDPRODUCTO_DET'];
            $guia_transportista_detalle->descripcion            = $item['DESCRIPCION_DET'];
            $guia_transportista_detalle->id_presentacion        = $item['ID_PRESENTACION'];
            $guia_transportista_detalle->codigo                 = $item['CODIGO_PRODUCTO'];
            
            try {
                if(!$guia_transportista_detalle->save()) {
                    $msg = '';
                    foreach ($guia_transportista_detalle->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Error al guardar el detalle: '.$msg;
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Error al guardar el detalle: '.$e->getMessage();
                return $resp;
            }
        }

        $this->db->commit();
        $resp['respuesta'] = 'ok';
        $resp['titulo'] = 'Proceso Correcto';
        $resp['mensaje'] = 'Se guardó correctamente la guía transportista';
        $resp['data_guia_transportista'] = array(
            'id_contribuyente'          => $guia_transportista->id_contribuyente,
            'id_tipodoc_electronico'    => $guia_transportista->id_tipodoc_electronico,
            'serie_comprobante'         => $guia_transportista->serie_comprobante,
            'numero_comprobante'        => $guia_transportista->numero_comprobante,
            'tipo_envio_sunat'          => $guia_transportista->tipo_envio_sunat,
            'id_usuario'                => $guia_transportista->id_vendedor
        );

        return $resp;
    }

    public function validar_datapost_guia_transportista($usuario, $data) {
        $array_fecha_documento = explode('/',$data['fecha_comprobante']);
        $fecha_documento = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
        if (!(DateTime::createFromFormat('Y-m-d', $fecha_documento) !== FALSE)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La fecha asignada al documento no es válida!';
            return $resp;
        }
        $data['fecha_documento'] = $fecha_documento;

        $array_fecha_traslado = explode('/', $data['fecha_traslado']);
        $fecha_traslado = $array_fecha_traslado[2].'-'.$array_fecha_traslado[1].'-'.$array_fecha_traslado[0];
        if (!(DateTime::createFromFormat('Y-m-d', $fecha_traslado) !== FALSE)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La fecha de traslado no es válida!';
            return $resp;
        }
        $data['fecha_traslado'] = $fecha_traslado;

        if(floatval($data['pesobruto']) <= 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el peso total!';
            return $resp;
        }
        $data['pesobruto'] = floatval($data['pesobruto']);

        //Validando Sucursal
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $data['idsucursal'], 'id_contribuyente' => $usuario->id_contribuyente)));
        if(!$sucursal) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La Sucursal no Existe!';
            return $resp;
        }
        $data['serie_comprobante'] = $sucursal->guia_transportista_serie;
        $data['codigo_local_anexo'] = $sucursal->codigo;

        //validando remitente
        if(empty($data['remitente_tipo_documento'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar el tipo de documento para el remitente!';
            return $resp;
        }

        if(empty($data['remitente_numero_documento'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el número de documento del remitente!';
            return $resp;
        }
        
        if(empty($data['remitente_nombre_completo'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el nombre completo del remitente!';
            return $resp;
        }

        $tipo_docidentidad_remitente= SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $data['remitente_tipo_documento'])));
        if(!$tipo_docidentidad_remitente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar el tipo de documento para el remitente!';
            return $resp;
        }

        if (strlen($data['remitente_numero_documento']) != 11) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El número de RUC del remitente es inválido!';
            return $resp;
        }

        if(!empty($data['num_registro_mtc'])) {
            // valor alfanumérico hasta 20 caracteres
            if (strlen($data['num_registro_mtc']) > 20 || !preg_match('/^[a-z0-9]+$/i', $data['num_registro_mtc'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El número de registro MTC no puede tener más de 20 caracteres y debe contener solo letras y números!';
                return $resp;
            }
        }

        if(empty($data['transporte_nro_placa'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el número de placa!';
            return $resp;
        }

        if (strlen($data['transporte_nro_placa']) > 8) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El número de Placa no puede tener más de 8 caracteres y debe contener solo letras y números!';
            return $resp;
        }
        
        if(!preg_match('/^[a-z0-9]+$/i', $data['transporte_nro_placa'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El número de placa del transporte no puede tener guiones o espacios';
            return $resp;
        }
        
        if(empty($data['conductor_tipo_documento'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar el tipo de documento para el conductor!';
            return $resp;
        }

        $tipo_docidentidad_conductor= SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $data['conductor_tipo_documento'])));
        if(!$tipo_docidentidad_conductor) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar el tipo de documento para el conductor!';
            return $resp;
        }

        if($data['conductor_tipo_documento'] != '1') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El tipo de documento seleccionado para el conductor no es válido!';
            return $resp;
        }

        if(empty($data['conductor_nro_documento'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el número de documento del conductor!';
            return $resp;
        }

        if(empty($data['conductor_nombre_completo'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el nombre completo del conductor!';
            return $resp;
        }

        if(empty($data['conductor_nro_licencia'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el número de licencia del conductor!';
            return $resp;
        }

        if (strlen($data['conductor_nro_licencia']) > 10 || !preg_match('/^[a-z0-9]+$/i', $data['conductor_nro_licencia'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La Licencia de Conducir no puede tener más de 10 caracteres y debe contener solo letras y números!';
            return $resp;
        }
        
        if(empty($data['dest_tipo_documento'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar el tipo de documento para el destinatario!';
            return $resp;
        }

        $tipo_docidentidad_destinatario = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $data['dest_tipo_documento'])));
        if(!$tipo_docidentidad_destinatario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar el tipo de documento para el destinatario!';
            return $resp;
        }

        if(empty($data['dest_numero_documento'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el número de documento del destinatario!';
            return $resp;
        }

        if(empty($data['dest_nombre_completo'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el nombre completo del destinatario!';
            return $resp;
        }

        $detalle_guia = json_decode($data['detalle_guia']);
        if(empty($detalle_guia)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes agregar al menos un producto al listado!';
            return $resp;
        }

        if(empty($data['direccionpartida'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar la dirección de partida!';
            return $resp;
        }

        $ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $data['id_ubigeo_partida'])));
        if(!$ubigeo_partida) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El código de ubigeo de partida seleccionado no es válido!';
            return $resp;
        }

        if(empty($data['direccionllegada'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar la dirección de llegada o destino!';
            return $resp;
        }

        $ubigeo_llegada = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $data['id_ubigeo_llegada'])));
        if(!$ubigeo_llegada) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El código de ubigeo de llegada seleccionado no es válido!';
            return $resp;
        }
        
        $lista_detalle = array();
        $n = 0;
        foreach($detalle_guia as $item) {
            $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item->idarticulo, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Producto';
                $resp['mensaje'] = 'El producto: '.$item->descripcion.', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
                return $resp;
            }
            
            //RECIBIR LA UNIDAD DE MEDIDA
            $idunidad_medida = $item->idunidadmedida;
            $idunidad_medida = str_replace('UND-', '', $idunidad_medida);
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
                        $id_presentacion_producto = $array_unidad_medida[1];
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
                $resp['mensaje'] = 'La Unidad de medida Seleccionada para el producto no es correcta: (Prod: '.$item->descripcion.' y Unidad Medida: '.$item->idunidadmedida.')';
                return $resp;
            }

            $n++;
            $lista_detalle[] = array(
                "ITEM_DET"          		=> $n,
                "PESO_DET"      			=> $item->peso,
                "CANTIDAD_DET"              => $item->cantidad,
                "DESCRIPCION_DET"   	    => $item->descripcion,
                "CODIGO_PRODUCTO"           => $producto->codigo,
                "UNIDAD_MEDIDA_ID_DET"      => $unidadmedida->idunidad,
                "UNIDAD_MEDIDA_DET"         => $unidadmedida->codigo,
                "CODIGO_DET"                => $producto->idproducto,
                "IDPRODUCTO_DET"		    => $producto->idproducto,
                "TIPO_UNIDAD"				=> $tipo_unidad_medida,
                "ID_PRESENTACION"			=> $id_presentacion_producto
            );
        }

        $resp['respuesta'] = 'ok';
        $data['lista_detalle'] = $lista_detalle;
        $resp['data'] = $data;
        return $resp;
    }

    public function enviar_guiatransportista_sunat($data) {

        $id_contribuyente       = $data['id_contribuyente'];
        $id_tipodoc_electronico = $data['id_tipodoc_electronico'];
        $serie_comprobante      = $data['serie_comprobante'];
        $numero_comprobante     = $data['numero_comprobante'];
        $tipo_envio_sunat       = $data['tipo_envio_sunat'];
        $id_usuario             = $data['id_usuario'];

        $guia_transportista = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if(!$guia_transportista) {
            $resp['respuesta'] = 'error';
            $resp['cod_p'] = 'P0001';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No Existe la Guía Transportista: '.$data['serie_comprobante'].'-'.$data['numero_comprobante'];
            return $resp;
        }

        if($guia_transportista->estado_envio_sunat == 'aceptado') {
            $resp['respuesta'] = 'error';
            $resp['cod_p'] = 'P0001.1';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La Guía Transportista: '.$data['serie_comprobante'].'-'.$data['numero_comprobante'].' ya fue aceptada por SUNAT';
            return $resp;
        }

        $resp_data_json = $this->get_data_json($data);
        if($resp_data_json['respuesta'] == 'error') {
            $resp_data_json['cod_p'] = 'P0002';
            return $resp_data_json;
        }

        $data_json = $resp_data_json['data_json'];
        
        //validar la creación de la ruta base xml
        if(empty($guia_transportista->ruta_xml)) {
            $ruta_base_xml = $data_json['secret_data']['ruta_dir_xml'].'guias_transportista/';
            if (!file_exists($ruta_base_xml)) {
                mkdir($ruta_base_xml, 0777, true);
            }
        } else {
            $ruta_base_xml = $guia_transportista->ruta_xml;
            if (!file_exists($ruta_base_xml)) {
                mkdir($ruta_base_xml, 0777, true);
            }
        }

        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/guiaremisiontransportista/procesar_guia_transportista';
        $resp_api_ws = $herramientas->envio_api_sunat($data_json, $ruta);

        $resp_api = json_decode($resp_api_ws);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $resp['respuesta']  = 'error';
            $resp['titulo']     = 'Error';
            $resp['mensaje']    = 'Error al codificar el json de la respuesta: ' . json_last_error_msg();
            $resp['cod_p']      = 'P0003';
            $resp['response']   = $resp_api_ws;
            return $resp;
        }

        if(!isset($resp_api->respuesta)) {
            $resp['respuesta']  = 'error';
            $resp['titulo']     = 'Error';
            $resp['mensaje']    = 'Error al obtener la respuesta del servidor.';
            $resp['cod_p']      = 'P0004';
            $resp['response']   = $resp_api_ws;
            return $resp;
        }

        if(!isset($resp_api->sunat)) {
            $resp['respuesta']  = 'error';
            $resp['titulo']     = 'Error';
            $resp['cod_p']      = 'P0005';
            $resp['mensaje']    = isset($resp_api->mensaje)?$resp_api->mensaje:'Error al obtener la respuesta del servidor..';
            $resp['response']   = $resp_api;
            return $resp;
        }

        //---------------------------------------------------------------------------------------
        $guia_transportista->fecha_envio_sunat = date('Y-m-d H:i:s');

        if(isset($resp_api->sunat->num_ticket) && !empty($resp_api->sunat->num_ticket)) {
            if(empty($guia_transportista->num_ticket)) {
                if($resp_api->sunat->cod_consulta_ticket == '0') {
                    $guia_transportista->fecha_envio_sunat = date('Y-m-d');
                    $guia_transportista->estado_envio_sunat = 'pendiente';
                    $guia_transportista->fec_recepcion_ticket = date('Y-m-d H:i:s');
                    $guia_transportista->num_ticket = $resp_api->sunat->num_ticket;
                    
                    try {
                        if(!$guia_transportista->save()) {
                            $msg = '';
                            foreach ($sucursal->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
            
                            $resp_log = $this->guardar_log_guia_transportista($data, $id_usuario, $msg, 'error_guardar_ticket');
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Error al guardar el ticket de la guía de transportista: '.$e->getMessage();
                        return $resp;
                    }
                }
            }
        }

        $guia_transportista->ruta_xml = $ruta_base_xml;

        $resp_guardado_xml = '';
        if(isset($resp_api->sunat->base_64_xml_zip) && !empty($resp_api->sunat->base_64_xml_zip)) {
            //Guardamos el XML en el sistema
            $saved_file_xml_zip = @file_put_contents($ruta_base_xml.$resp_api->sunat->name_xml_zip, base64_decode($resp_api->sunat->base_64_xml_zip));
            if (($saved_file_xml_zip === false) || ($saved_file_xml_zip == -1)) {
                $resp_guardado_xml = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar correctamente el archivo XML.';
            } else {
                $resp_guardado_xml = 'El XML del Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' se guardó correctamente.';
            }

            $guia_transportista->hash_cpe = isset($resp_api->sunat->hash_cpe)?$resp_api->sunat->hash_cpe:'';
            $guia_transportista->name_xml = isset($resp_api->sunat->name_xml)?$resp_api->sunat->name_xml:'';
            $guia_transportista->name_xml_zip = isset($resp_api->sunat->name_xml_zip)?$resp_api->sunat->name_xml_zip:'';
        }

        try {
            if(!$guia_transportista->save()) {
                $msg = '';
                foreach ($guia_transportista->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp_log = $this->guardar_log_guia_transportista($data, $id_usuario, $msg, 'error_guardar_xml');
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Error al guardar el XML de la guía de transportista: '.$e->getMessage();
            return $resp;
        }

        $resp_guardado_xml_cdr = '';
        if(isset($resp_api->sunat->base_64_xml_cdr_zip) && !empty($resp_api->sunat->base_64_xml_cdr_zip)) {
            //Guardamos el CDR en el sistema
            $saved_file_cdr_zip = @file_put_contents($ruta_base_xml.$resp_api->sunat->name_xml_cdr_zip, base64_decode($resp_api->sunat->base_64_xml_cdr_zip));
            if (($saved_file_cdr_zip === false) || ($saved_file_cdr_zip == -1)) {
                $resp_guardado_xml_cdr = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar correctamente el archivo CDR.';
            } else {
                $resp_guardado_xml_cdr = 'El CDR del Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' se guardó correctamente.';
            }

            $guia_transportista->name_cdr = isset($resp_api->sunat->name_cdr)?$resp_api->sunat->name_cdr:'';
            $guia_transportista->name_cdr_zip = isset($resp_api->sunat->name_xml_cdr_zip)?$resp_api->sunat->name_xml_cdr_zip:'';

            try {
                if(!$guia_transportista->save()) {
                    $msg = '';
                    foreach ($guia_transportista->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
        
                    $resp_log = $this->guardar_log_guia_transportista($data, $id_usuario, $msg, 'error_guardar_cdr');
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Error al guardar el CDR de la guía de transportista: '.$e->getMessage();
                return $resp;
            }
        }

        if(isset($resp_api->sunat->cod_sunat) && $resp_api->sunat->cod_sunat != '') {
            if($resp_api->sunat->cod_sunat == '0') {

                $guia_transportista->estado_envio_sunat = 'aceptado';
                $guia_transportista->hash_cdr = isset($resp_api->sunat->hash_cdr)?$resp_api->sunat->hash_cdr:'';
                $guia_transportista->cod_sunat = isset($resp_api->sunat->cod_sunat)?$resp_api->sunat->cod_sunat:'';
                $guia_transportista->msje_sunat = isset($resp_api->sunat->msg_sunat)?$resp_api->sunat->msg_sunat:'';
                $guia_transportista->name_cdr = isset($resp_api->sunat->name_cdr)?$resp_api->sunat->name_cdr:'';
                $guia_transportista->name_cdr_zip = isset($resp_api->sunat->name_cdr_zip)?$resp_api->sunat->name_cdr_zip:'';
                $guia_transportista->intentos_envio_sunat = intval($guia_transportista->intentos_envio_sunat) + 1;
                $guia_transportista->cod_respuesta = isset($resp_api->sunat->cod_sunat)?$resp_api->sunat->cod_sunat:'';
                if(isset($resp_api->sunat->url_sunat_guia) && !empty($resp_api->sunat->url_sunat_guia)) {
                    $guia_transportista->ruta_qr = isset($resp_api->sunat->url_sunat_guia)?$resp_api->sunat->url_sunat_guia:'';
                }
                $observaciones = isset($resp_api->sunat->observaciones)?$resp_api->sunat->observaciones:array();
                $observaciones = is_array($observaciones)?json_encode($observaciones):null;
                $guia_transportista->observaciones = $observaciones;

                try {
                    if(!$guia_transportista->save()) {
                        $msg = '';
                        foreach ($sucursal->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
        
                        $resp_log = $this->guardar_log_guia_transportista($data, $id_usuario, $msg, 'error_guardar_cdr');
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Error al guardar el CDR de la guía de transportista: '.$e->getMessage();
                    return $resp;
                }

                $resp['respuesta'] = 'ok';
                $resp['cod_p']      = 'P0006';
                $resp['titulo'] = 'Proceso Correcto';
                $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br />'.$resp_guardado_xml.'<br />'.$resp_guardado_xml_cdr;
                return $resp;

            } else {
                $resp['respuesta']  = 'error';
                $resp['titulo']     = 'Error';
                $resp['cod_p']      = 'P0007';
                $resp['mensaje']    = 'Código de Respuesta: '.$resp_api->sunat->cod_respuesta.' - '.$resp_api->sunat->msg_sunat;
                $resp['response']   = $resp_api;
                return $resp;
            }
        }

        if(isset($resp_api->respuesta) && $resp_api->respuesta == 'error') {
            if(isset($resp_api->sunat)) {
                $resp['respuesta']  = 'error';
                $resp['titulo']     = 'Error';
                $resp['cod_p']      = 'P0009';
                $resp['mensaje']    = 'Error '.$resp_api->sunat->cod_respuesta.':'.$resp_api->sunat->msg_sunat;
                $resp['response']   = $resp_api;
                return $resp;
            }
        }
        
        $resp['respuesta']  = 'error';
        $resp['titulo']     = 'Error';
        $resp['cod_p']      = 'P0008';
        $resp['mensaje']    = 'Error al obtener la respuesta del servidor...';
        $resp['response']   = $resp_api;
        return $resp;
    }

    public function guardar_log_guia_transportista($data, $id_usuario, $descripcion, $tipo) {
        $id_contribuyente       = $data['id_contribuyente'];
        $id_tipodoc_electronico = $data['id_tipodoc_electronico'];
        $serie_comprobante      = $data['serie_comprobante'];
        $numero_comprobante     = $data['numero_comprobante'];
        $tipo_envio_sunat       = $data['tipo_envio_sunat'];

        $new_log = new LogDocumento();
        $new_log->id_contribuyente = $id_contribuyente;
        $new_log->id_tipodoc_electronico = $id_tipodoc_electronico;
        $new_log->serie_comprobante = $serie_comprobante;
        $new_log->numero_comprobante = $numero_comprobante;
        $new_log->tipo_envio_sunat = $tipo_envio_sunat;
        $new_log->id_usuario = $id_usuario;
        $new_log->descripcion = $descripcion;
        $new_log->tipo = $tipo;
        $new_log->fecha_registro = date('Y-m-d H:i:s');
        $resp_save = $new_log->save();

        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function enviar_api_sunat_guia($data, $ruta, $token = '') {
        $data['token'] = $this->token_user;
        $data_json = json_encode($data);

        // Verificar si la conversión a json fue exitosa
        if ($data_json === false) {
            $resp['respuesta']  = 'error';
            $resp['titulo']     = 'Error';
            $resp['mensaje']    = 'Error en la conversión a JSON de los datos enviados a SUNAT';
            $resp['data_json']  = $data;
            return $resp;
        }

        $ch = curl_init();

        if ($ch === false) {
            $resp['respuesta']  = 'error';
            $resp['titulo']     = 'Error';
            $resp['mensaje']    = 'No podemos iniciar la conexión con el servidor';
            return $resp;
        }

        $headers = [
            'Authorization: Token token="'.$token.'"',
            'Content-Type: application/json',
        ];

        $options = [
            CURLOPT_URL => $ruta,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_RETURNTRANSFER => true,
        ];

        curl_setopt_array($ch, $options);
        
        $response  = curl_exec($ch);

        // Verificar si hubo un error en cURL
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            $resp['respuesta']  = 'error';
            $resp['titulo']     = 'Error';
            $resp['mensaje']    = 'Error en la solicitud cURL: ' . $error;
            return $resp;
        }

        curl_close($ch);

        $resp['respuesta'] = 'ok';
        $resp['response'] = $response;
        return $resp;
    }

}