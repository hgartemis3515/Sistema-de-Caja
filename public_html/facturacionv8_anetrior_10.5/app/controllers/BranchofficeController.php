<?php
class BranchofficeController extends ControllerBase
{
	public function indexAction($idsucursal = 0) {
		$this->setTitle('Gestión de Sucursales');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/pages/components_thumbnails.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/media/fancybox.min.js")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")

        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

        ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/subir_imagen.js?i=".rand())
        ->addJs($this->baseUri . "public/js/branchoffice/plantillaspdf.js?i=".rand())
        ->addJs($this->baseUri . "public/js/branchoffice/subir_qr_yape_plin.js?i=".rand())
        ->addJs($this->baseUri . "public/js/branchoffice/branchoffice.js?i=".rand());
        
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));

        if($idsucursal > 0) {
            if(!$sucursal) {
                $idsucursal = 0;
            }
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $ubigeo = SunatCodigoubigeo::find();

        $plantilla_por_defecto_a4 = 1;
        $plantilla_por_defecto_ticket = 10;

        $id_plantillapdf_factura_a4 = empty($sucursal->id_plantillapdf_factura_a4)?1:$sucursal->id_plantillapdf_factura_a4;
        $id_plantillapdf_factura_ticket = empty($sucursal->id_plantillapdf_factura_ticket)?10:$sucursal->id_plantillapdf_factura_ticket;
        $id_plantillapdf_boleta_a4 = empty($sucursal->id_plantillapdf_boleta_a4)?1:$sucursal->id_plantillapdf_boleta_a4;
        $id_plantillapdf_boleta_ticket = empty($sucursal->id_plantillapdf_boleta_ticket)?10:$sucursal->id_plantillapdf_boleta_ticket;
        $id_plantillapdf_notacredito_a4 = empty($sucursal->id_plantillapdf_notacredito_a4)?1:$sucursal->id_plantillapdf_notacredito_a4;
        $id_plantillapdf_notacredito_ticket = empty($sucursal->id_plantillapdf_notacredito_ticket)?10:$sucursal->id_plantillapdf_notacredito_ticket;
        $id_plantillapdf_notadebito_a4 = empty($sucursal->id_plantillapdf_notadebito_a4)?1:$sucursal->id_plantillapdf_notadebito_a4;
        $id_plantillapdf_notadebito_ticket = empty($sucursal->id_plantillapdf_notadebito_ticket)?10:$sucursal->id_plantillapdf_notadebito_ticket;
        $id_plantillapdf_guiaremision_a4 = empty($sucursal->id_plantillapdf_guiaremision_a4)?13:$sucursal->id_plantillapdf_guiaremision_a4;
        $id_plantillapdf_guiaremision_ticket = empty($sucursal->id_plantillapdf_guiaremision_ticket)?33:$sucursal->id_plantillapdf_guiaremision_ticket;
        $id_plantillapdf_notaventa_a4 = empty($sucursal->id_plantillapdf_notaventa_a4)?21:$sucursal->id_plantillapdf_notaventa_a4;
        $id_plantillapdf_notaventa_ticket = empty($sucursal->id_plantillapdf_notaventa_ticket)?30:$sucursal->id_plantillapdf_notaventa_ticket;
        $id_plantillapdf_cotizacion_a4 = empty($sucursal->id_plantillapdf_cotizacion_a4)?21:$sucursal->id_plantillapdf_cotizacion_a4;
        $id_plantillapdf_cotizacion_ticket = empty($sucursal->id_plantillapdf_cotizacion_ticket)?30:$sucursal->id_plantillapdf_cotizacion_ticket;

        $plantillapdf_factura_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_factura_a4)));
        if(!$plantillapdf_factura_a4) {
            $plantillapdf_factura_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 1)));
        }

        $plantillapdf_factura_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_factura_ticket)));
        if(!$plantillapdf_factura_ticket) {
            $plantillapdf_factura_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 10)));
        }

        $plantillapdf_boleta_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_boleta_a4)));
        if(!$plantillapdf_boleta_a4) {
            $plantillapdf_boleta_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 1)));
        }

        $plantillapdf_boleta_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_boleta_ticket)));
        if(!$plantillapdf_boleta_ticket) {
            $plantillapdf_boleta_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 10)));
        }

        $plantillapdf_notacredito_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_notacredito_a4)));
        if(!$plantillapdf_notacredito_a4) {
            $plantillapdf_notacredito_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 1)));
        }

        $plantillapdf_notacredito_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_notacredito_ticket)));
        if(!$plantillapdf_notacredito_ticket) {
            $plantillapdf_notacredito_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 10)));
        }

        $plantillapdf_notadebito_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_notadebito_a4)));
        if(!$plantillapdf_notadebito_a4) {
            $plantillapdf_notadebito_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 1)));
        }

        $plantillapdf_notadebito_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_notadebito_ticket)));
        if(!$plantillapdf_notadebito_ticket) {
            $plantillapdf_notadebito_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 10)));
        }

        $plantillapdf_guiaremision_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_guiaremision_a4)));
        if(!$plantillapdf_guiaremision_a4) {
            $plantillapdf_guiaremision_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 13)));
        }

        $plantillapdf_guiaremision_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_guiaremision_ticket)));
        if(!$plantillapdf_guiaremision_ticket) {
            $plantillapdf_guiaremision_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 33)));
        }

        $plantillapdf_notaventa_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_notaventa_a4)));
        if(!$plantillapdf_notaventa_a4) {
            $plantillapdf_notaventa_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 21)));
        }

        $plantillapdf_notaventa_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_notaventa_ticket)));
        if(!$plantillapdf_notaventa_ticket) {
            $plantillapdf_notaventa_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 30)));
        }

        $plantillapdf_cotizacion_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_cotizacion_a4)));
        if(!$plantillapdf_cotizacion_a4) {
            $plantillapdf_cotizacion_a4 = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 21)));
        }

        $plantillapdf_cotizacion_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf_cotizacion_ticket)));
        if(!$plantillapdf_cotizacion_ticket) {
            $plantillapdf_cotizacion_ticket = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => 30)));
        }
        
        $this->view->plantillapdf_boleta_a4 = $plantillapdf_boleta_a4;
        $this->view->plantillapdf_boleta_ticket = $plantillapdf_boleta_ticket;
        $this->view->plantillapdf_factura_a4 = $plantillapdf_factura_a4;
        $this->view->plantillapdf_factura_ticket = $plantillapdf_factura_ticket;
        $this->view->plantillapdf_notacredito_a4 = $plantillapdf_notacredito_a4;
        $this->view->plantillapdf_notacredito_ticket = $plantillapdf_notacredito_ticket;
        $this->view->plantillapdf_notadebito_a4 = $plantillapdf_notadebito_a4;
        $this->view->plantillapdf_notadebito_ticket = $plantillapdf_notadebito_ticket;
        $this->view->plantillapdf_cotizacion_a4 = $plantillapdf_cotizacion_a4;
        $this->view->plantillapdf_cotizacion_ticket = $plantillapdf_cotizacion_ticket;
        $this->view->plantillapdf_guiaremision_a4 = $plantillapdf_guiaremision_a4;
        $this->view->plantillapdf_guiaremision_ticket = $plantillapdf_guiaremision_ticket;
        $this->view->plantillapdf_notaventa_a4 = $plantillapdf_notaventa_a4;
        $this->view->plantillapdf_notaventa_ticket = $plantillapdf_notaventa_ticket;
        
        
        $this->view->ubigeo = $ubigeo;
        $this->view->idsucursal = $idsucursal;
        $this->view->sucursal = $sucursal;
        $this->view->contribuyente = $contribuyente;
        $this->view->patrocinador = $this->data_patrocinador;
        $this->view->usuario = $usuario;

        $this->view->boleta_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'boleta_modifica_stock');
        $this->view->factura_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'factura_modifica_stock');
        $this->view->notacredito_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'notacredito_modifica_stock');
        $this->view->notadebito_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'notadebito_modifica_stock');
        $this->view->cotizacion_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'cotizacion_modifica_stock');
        $this->view->guiaremision_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'guiaremision_modifica_stock');
        $this->view->notaventa_modifica_stock = $this->get_sucursal_opcion($usuario->id_contribuyente, $idsucursal, 'notaventa_modifica_stock');
    }

    public function guardarOpcionModificaStockAction() {
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

            $idsucursal = intval($datapost['idsucursal']) + 0;

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No Existe la sucursal seleccionada';
                echo json_encode($resp);
                exit();
            }

            if(!$datapost['nombre_opcion']) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La opción Seleccionada no Existe';
                echo json_encode($resp);
                exit();
            }

            if(!$datapost['valor_opcion']) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El valor asignado a la opción no es correcta';
                echo json_encode($resp);
                exit();
            }

            $array_opt_validos = array('boleta_modifica_stock', 'factura_modifica_stock', 'notacredito_modifica_stock', 'notadebito_modifica_stock', 'cotizacion_modifica_stock', 'guiaremision_modifica_stock', 'notaventa_modifica_stock');
            if(!in_array($datapost['nombre_opcion'], $array_opt_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La opción Seleccionada no Existe';
                echo json_encode($resp);
                exit();
            }

            $opcion_valor = ($datapost['valor_opcion'] == 'si')?'si':'no';

            $resp_opcion = $this->set_sucursal_opcion($usuario->id_contribuyente, $idsucursal, $datapost['nombre_opcion'], $opcion_valor);
            echo json_encode($resp_opcion);
            exit();
        }
    }

    public function guardarOpcionItemsAction() {
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

            $idsucursal = intval($datapost['idsucursal']) + 0;

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No Existe la sucursal seleccionada';
                echo json_encode($resp);
                exit();
            }
            
            if($datapost['nombre_opcion'] == 'boleta_mostrar_items_igv') {
                $sucursal->boleta_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else if($datapost['nombre_opcion'] == 'factura_mostrar_items_igv') {
                $sucursal->factura_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else if($datapost['nombre_opcion'] == 'notacredito_mostrar_items_igv') {
                $sucursal->notacredito_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else if($datapost['nombre_opcion'] == 'notadebito_mostrar_items_igv') {
                $sucursal->notadebito_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else if($datapost['nombre_opcion'] == 'guiaremision_mostrar_items_igv') {
                $sucursal->guiaremision_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else if($datapost['nombre_opcion'] == 'cotizacion_mostrar_items_igv') {
                $sucursal->cotizacion_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else if($datapost['nombre_opcion'] == 'notaventa_mostrar_items_igv') {
                $sucursal->notaventa_mostrar_items_igv = !isset($datapost['valor_opcion'])?'si':(($datapost['valor_opcion'] == 'si')?'si':'no');
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La opción Seleccionada no Existe';
                echo json_encode($resp);
                exit();
            }

            try {
                if(!$sucursal->save()) {
                    $msg = '';
                    foreach ($sucursal->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se pueden guardar los cambios!';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se pueden guardar los cambios! '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Ok';
            $resp['mensaje'] = 'Se guardó la opción correctamente';
            echo json_encode($resp);
            exit();
        }
    }

    public function insertAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $codigo = trim($datapost['codigo']); //Este campo sirve para consignar en los XML el Código asignado por SUNAT para su establecimiento anexo declarado en el RUC.
            if(empty($codigo)) {
                $resp['respuesta']  = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Ingresar el código del establecimiento Anexo';
                echo json_encode($resp);
                exit();
            }

            if(strlen($codigo) > 4) {
                $resp['respuesta']  = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Código del Establecimiento Anexo es Inválido, debe tener máximo 4 dígitos numéricos';
                echo json_encode($resp);
                exit();
            }

            if(!preg_match('/^[0-9]+$/', $codigo)) {
                $resp['respuesta']  = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Código del Establecimiento Anexo es Inválido';
                echo json_encode($resp);
                exit();
            }

            $nombre_sucursal = $datapost['nombre_sucursal'];
            $direccion = $datapost['direccion'];
            $ubigeo = $datapost['ubigeo'];
            $urbanizacion = $datapost['urbanizacion'];
            $telefono = $datapost['telefono'];
            $email = $datapost['email'];
            $sitioweb = $datapost['sitioweb'];
            $info_adicional = $datapost['info_adicional'];
            $leyenda_comprobantes = !isset($datapost['leyenda_comprobantes'])?'':$datapost['leyenda_comprobantes'];

            $txt_pdf_a4_1 = !isset($datapost['txt_pdf_a4_1'])?'':$datapost['txt_pdf_a4_1'];
            $txt_pdf_a4_2 = !isset($datapost['txt_pdf_a4_2'])?'':$datapost['txt_pdf_a4_2'];
            $txt_pdf_a4_3 = !isset($datapost['txt_pdf_a4_3'])?'':$datapost['txt_pdf_a4_3'];
            $txt_pdf_ticket_1 = !isset($datapost['txt_pdf_ticket_1'])?'':$datapost['txt_pdf_ticket_1'];
            $txt_pdf_ticket_2 = !isset($datapost['txt_pdf_ticket_2'])?'':$datapost['txt_pdf_ticket_2'];
            $txt_pdf_ticket_3 = !isset($datapost['txt_pdf_ticket_3'])?'':$datapost['txt_pdf_ticket_3'];

            $plantilla_pdf_a4 = !isset($datapost['modelo_plantilla_pdf_a4'])?1:((!in_array($datapost['modelo_plantilla_pdf_a4'], array('1', '2', '3', '4', '5', '6', '7', '8')))?1:intval($datapost['modelo_plantilla_pdf_a4']));
            $plantilla_pdf_ticket = !isset($datapost['modelo_plantilla_pdf_ticket'])?1:((!in_array($datapost['modelo_plantilla_pdf_ticket'], array('1', '2', '3', '4', '5', '6', '7', '8')))?1:intval($datapost['modelo_plantilla_pdf_ticket']));

            $idsucursal = !isset($datapost['idsucursal'])?0:intval($datapost['idsucursal']) + 0;

            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El usuario no existe, debes iniciar sesión!.';
                echo json_encode($resp);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_permisos = $herramientas->verificar_permisos_usuario($usuario->idusuario);
            if($resp_permisos['respuesta'] == 'error') {
                echo json_encode($resp_permisos);
                exit();
            }

            //factura
            $factura_serie                  = !isset($datapost['factura_serie'])?'F001':$datapost['factura_serie'];
            $factura_numero                 = !isset($datapost['factura_numero'])?1:intval($datapost['factura_numero']);
            $factura_formato                = !isset($datapost['factura_formato'])?'A4':$datapost['factura_formato'];

            //boleta
            $boleta_serie                   = !isset($datapost['boleta_serie'])?'B001':$datapost['boleta_serie'];
            $boleta_numero                  = !isset($datapost['boleta_numero'])?1:intval($datapost['boleta_numero']);
            $boleta_formato                 = !isset($datapost['boleta_formato'])?'A4':$datapost['boleta_formato'];

            //nota de crédito que modifica una factura
            $notacredito_factura_serie      = !isset($datapost['notacredito_factura_serie'])?'FC01':$datapost['notacredito_factura_serie'];
            $notacredito_factura_numero     = !isset($datapost['notacredito_factura_numero'])?1:intval($datapost['notacredito_factura_numero']);
            $notacredito_factura_formato    = !isset($datapost['notacredito_factura_formato'])?'A4':$datapost['notacredito_factura_formato'];

            //nota de débito que modifica una factura
            $notadebito_factura_serie       = !isset($datapost['notadebito_factura_serie'])?'FD01':$datapost['notadebito_factura_serie'];
            $notadebito_factura_numero      = !isset($datapost['notadebito_factura_numero'])?1:intval($datapost['notadebito_factura_numero']);
            $notadebito_factura_formato     = !isset($datapost['notadebito_factura_formato'])?'A4':$datapost['notadebito_factura_formato'];

            //nota de débito que modifica una boleta
            $notacredito_boleta_serie       = !isset($datapost['notacredito_boleta_serie'])?'BC01':$datapost['notacredito_boleta_serie'];
            $notacredito_boleta_numero      = !isset($datapost['notacredito_boleta_numero'])?1:intval($datapost['notacredito_boleta_numero']);
            $notacredito_boleta_formato     = !isset($datapost['notacredito_boleta_formato'])?'A4':$datapost['notacredito_boleta_formato'];

            //nota de crédito que modifica una boleta
            $notadebito_boleta_serie        = !isset($datapost['notadebito_boleta_serie'])?'BD01':$datapost['notadebito_boleta_serie'];
            $notadebito_boleta_numero       = !isset($datapost['notadebito_boleta_numero'])?1:intval($datapost['notadebito_boleta_numero']);
            $notadebito_boleta_formato      = !isset($datapost['notadebito_boleta_formato'])?'A4':$datapost['notadebito_boleta_formato'];

            //Guía de Remisión
            $guia_remision_serie            = !isset($datapost['guia_remision_serie'])?'T001':$datapost['guia_remision_serie'];
            $guia_remision_numero           = !isset($datapost['guia_remision_numero'])?1:intval($datapost['guia_remision_numero']);
            $guia_remision_formato          = !isset($datapost['guia_remision_formato'])?'A4':$datapost['guia_remision_formato'];

            //Guía Transportista
            $guia_transportista_serie       = !isset($datapost['guia_transportista_serie'])?'V001':$datapost['guia_transportista_serie'];
            $guia_transportista_numero      = !isset($datapost['guia_transportista_numero'])?1:intval($datapost['guia_transportista_numero']);
            $guia_transportista_formato     = !isset($datapost['guia_transportista_formato'])?'A4':$datapost['guia_transportista_formato'];

            //Orden de Compra
            $orden_compra_serie             = !isset($datapost['orden_compra_serie'])?'OC01':$datapost['orden_compra_serie'];
            $orden_compra_numero            = !isset($datapost['orden_compra_numero'])?1:intval($datapost['orden_compra_numero']);
            $orden_compra_formato           = !isset($datapost['orden_compra_formato'])?'A4':$datapost['orden_compra_formato'];

            //plantillas pdf
            $id_plantillapdf_boleta_a4              = !isset($datapost['id_plantillapdf_boleta_a4'])?0:intval($datapost['id_plantillapdf_boleta_a4']);
            $id_plantillapdf_boleta_ticket          = !isset($datapost['id_plantillapdf_boleta_ticket'])?0:intval($datapost['id_plantillapdf_boleta_ticket']);
            $id_plantillapdf_factura_a4             = !isset($datapost['id_plantillapdf_factura_a4'])?0:intval($datapost['id_plantillapdf_factura_a4']);
            $id_plantillapdf_factura_ticket         = !isset($datapost['id_plantillapdf_factura_ticket'])?0:intval($datapost['id_plantillapdf_factura_ticket']);
            $id_plantillapdf_notacredito_a4         = !isset($datapost['id_plantillapdf_notacredito_a4'])?0:intval($datapost['id_plantillapdf_notacredito_a4']);
            $id_plantillapdf_notacredito_ticket     = !isset($datapost['id_plantillapdf_notacredito_ticket'])?0:intval($datapost['id_plantillapdf_notacredito_ticket']);
            $id_plantillapdf_notadebito_a4          = !isset($datapost['id_plantillapdf_notadebito_a4'])?0:intval($datapost['id_plantillapdf_notadebito_a4']);
            $id_plantillapdf_notadebito_ticket      = !isset($datapost['id_plantillapdf_notadebito_ticket'])?0:intval($datapost['id_plantillapdf_notadebito_ticket']);
            $id_plantillapdf_cotizacion_a4          = !isset($datapost['id_plantillapdf_cotizacion_a4'])?0:intval($datapost['id_plantillapdf_cotizacion_a4']);
            $id_plantillapdf_cotizacion_ticket      = !isset($datapost['id_plantillapdf_cotizacion_ticket'])?0:intval($datapost['id_plantillapdf_cotizacion_ticket']);
            $id_plantillapdf_guiaremision_a4        = !isset($datapost['id_plantillapdf_guiaremision_a4'])?0:intval($datapost['id_plantillapdf_guiaremision_a4']);
            $id_plantillapdf_guiaremision_ticket    = !isset($datapost['id_plantillapdf_guiaremision_ticket'])?0:intval($datapost['id_plantillapdf_guiaremision_ticket']);
            $id_plantillapdf_notaventa_a4           = !isset($datapost['id_plantillapdf_notaventa_a4'])?0:intval($datapost['id_plantillapdf_notaventa_a4']);
            $id_plantillapdf_notaventa_ticket       = !isset($datapost['id_plantillapdf_notaventa_ticket'])?0:intval($datapost['id_plantillapdf_notaventa_ticket']);

            $boleta_mostrar_items_igv           = !isset($datapost['boleta_mostrar_items_igv'])?'no':(($datapost['boleta_mostrar_items_igv'] == 'on')?'si':'no');
            $factura_mostrar_items_igv          = !isset($datapost['factura_mostrar_items_igv'])?'no':(($datapost['factura_mostrar_items_igv'] == 'on')?'si':'no');
            $notacredito_mostrar_items_igv      = !isset($datapost['notacredito_mostrar_items_igv'])?'no':(($datapost['notacredito_mostrar_items_igv'] == 'on')?'si':'no');
            $notadebito_mostrar_items_igv       = !isset($datapost['notadebito_mostrar_items_igv'])?'no':(($datapost['notadebito_mostrar_items_igv'] == 'on')?'si':'no');
            $guiaremision_mostrar_items_igv     = !isset($datapost['guiaremision_mostrar_items_igv'])?'no':(($datapost['guiaremision_mostrar_items_igv'] == 'on')?'si':'no');
            $cotizacion_mostrar_items_igv       = !isset($datapost['cotizacion_mostrar_items_igv'])?'no':(($datapost['cotizacion_mostrar_items_igv'] == 'on')?'si':'no');
            $notaventa_mostrar_items_igv        = !isset($datapost['notaventa_mostrar_items_igv'])?'no':(($datapost['notaventa_mostrar_items_igv'] == 'on')?'si':'no');

            $boleta_mostrar_yape        = !isset($datapost['boleta_mostrar_yape'])?'no':(($datapost['boleta_mostrar_yape'] == 'on')?'si':'no');
            $factura_mostrar_yape       = !isset($datapost['factura_mostrar_yape'])?'no':(($datapost['factura_mostrar_yape'] == 'on')?'si':'no');
            $notaventa_mostrar_yape     = !isset($datapost['notaventa_mostrar_yape'])?'no':(($datapost['notaventa_mostrar_yape'] == 'on')?'si':'no');
            $boleta_mostrar_plin        = !isset($datapost['boleta_mostrar_plin'])?'no':(($datapost['boleta_mostrar_plin'] == 'on')?'si':'no');
            $factura_mostrar_plin       = !isset($datapost['factura_mostrar_plin'])?'no':(($datapost['factura_mostrar_plin'] == 'on')?'si':'no');
            $notaventa_mostrar_plin     = !isset($datapost['notaventa_mostrar_plin'])?'no':(($datapost['notaventa_mostrar_plin'] == 'on')?'si':'no');
            $cotizacion_mostrar_yape    = !isset($datapost['cotizacion_mostrar_yape'])?'no':(($datapost['cotizacion_mostrar_yape'] == 'on')?'si':'no');
            $cotizacion_mostrar_plin    = !isset($datapost['cotizacion_mostrar_plin'])?'no':(($datapost['cotizacion_mostrar_plin'] == 'on')?'si':'no');

            $img_qr_yape    = !isset($datapost['img_qr_yape'])?null:$datapost['img_qr_yape'];
            $titular_yape   = !isset($datapost['titular_yape'])?null:$datapost['titular_yape'];
            $celular_yape   = !isset($datapost['celular_yape'])?null:$datapost['celular_yape'];
            $img_qr_plin    = !isset($datapost['img_qr_plin'])?null:$datapost['img_qr_plin'];
            $titular_plin   = !isset($datapost['titular_plin'])?null:$datapost['titular_plin'];
            $celular_plin   = !isset($datapost['celular_plin'])?null:$datapost['celular_plin'];

            $boleta_modifica_stock          = !isset($datapost['boleta_modifica_stock'])?'no':(($datapost['boleta_modifica_stock'] == 'on')?'si':'no');
            $factura_modifica_stock         = !isset($datapost['factura_modifica_stock'])?'no':(($datapost['factura_modifica_stock'] == 'on')?'si':'no');
            $notacredito_modifica_stock     = !isset($datapost['notacredito_modifica_stock'])?'no':(($datapost['notacredito_modifica_stock'] == 'on')?'si':'no');
            $notadebito_modifica_stock      = !isset($datapost['notadebito_modifica_stock'])?'no':(($datapost['notadebito_modifica_stock'] == 'on')?'si':'no');
            $cotizacion_modifica_stock      = !isset($datapost['cotizacion_modifica_stock'])?'no':(($datapost['cotizacion_modifica_stock'] == 'on')?'si':'no');
            $guiaremision_modifica_stock    = !isset($datapost['guiaremision_modifica_stock'])?'no':(($datapost['guiaremision_modifica_stock'] == 'on')?'si':'no');
            $notaventa_modifica_stock       = !isset($datapost['notaventa_modifica_stock'])?'no':(($datapost['notaventa_modifica_stock'] == 'on')?'si':'no');

            $factor_igv = !isset($datapost['factor_igv_sucursal'])?18:intval($datapost['factor_igv_sucursal']);
            //igv_sunat_posible_cambio_futuro_alex_castaneda_facturalaya.com
            $factor_igv = ($factor_igv == 18)?18:10;

            $glosa_amazonia = !isset($datapost['glosa_amazonia'])?'no':$datapost['glosa_amazonia'];

            //Verificando campos 
            if(empty($codigo)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($nombre_sucursal)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir un Nombre de Sucursal, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($direccion)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir una dirección,  no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($urbanizacion)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir una Urbanización , no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($telefono)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir un Número de Teléfono, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($email)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir un E-mail/Correo Eléctronico, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            
            //Consultas
            $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $ubigeo)));
            if(!$sql_ubigeo){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo siento, ese  código de Ubicación Geográfica no existe. Por favor selecciona un código de ubigeo Válido';
                echo json_encode($msj);
                exit();
            }

            $array_formatos = array('A4', 'A5', 'ticket');

            //factura
            if(!preg_match("/^F[a-zA-Z0-9_]{3}$/", $factura_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Factura debe Empezar con "F", seguido por tres caracteres alfanuméricos, un ejemplo válido sería: F001';
                echo json_encode($msj);
                exit();
            }

            if($factura_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para la factura debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($factura_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido para la factura no es válido!';
                echo json_encode($msj);
                exit();
            }

            //boleta
            if(!preg_match("/^B[a-zA-Z0-9_]{3}$/", $boleta_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Boleta debe Empezar con "B", seguido por tres caracteres alfanuméricos, un ejemplo válido sería: B001';
                echo json_encode($msj);
                exit();
            }

            if($boleta_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para una boleta debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($boleta_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido para la boleta no es válido!';
                echo json_encode($msj);
                exit();
            }

            //nota de crédito que modifica una factura
            if(!preg_match("/^F[a-zA-Z0-9_]{3}$/", $notacredito_factura_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Nota de Crédito que modifica una Factura debe Empezar con "F", seguido por tres caracteres alfanuméricos, un ejemplo válido sería: F001';
                echo json_encode($msj);
                exit();
            }

            if($notacredito_factura_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para una Nota de Crédito que Modifica una Factura debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($notacredito_factura_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido para la Nota de Crédito que modifica una Factura no es válido!';
                echo json_encode($msj);
                exit();
            }

            //nota de débito que modifica una factura
            if(!preg_match("/^F[a-zA-Z0-9_]{3}$/", $notadebito_factura_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Nota de Débito que Modifica una Factura debe Empezar con "F", seguido por tres caracteres alfanuméricos, un ejemplo válido sería: F001';
                echo json_encode($msj);
                exit();
            }

            if($notadebito_factura_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para la Nota de Débito que Modifica una Factura debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($notadebito_factura_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido no es válido para la Nota de Débito que Modifica una Factura';
                echo json_encode($msj);
                exit();
            }

            //nota de débito que modifica una boleta
            if(!preg_match("/^B[a-zA-Z0-9_]{3}$/", $notacredito_boleta_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Nota de Débito que Modifica una Boleta debe Empezar con "B", seguido por tres caracteres alfanuméricos, un ejemplo válido sería: B001';
                echo json_encode($msj);
                exit();
            }

            if($notacredito_boleta_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($notacredito_boleta_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido no es válido!';
                echo json_encode($msj);
                exit();
            }

            //nota de crédito que modifica una boleta
            if(!preg_match("/^B[a-zA-Z0-9_]{3}$/", $notadebito_boleta_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Nota de Débito que modifica una Boleta debe Empezar con "B", seguido por tres caracteres alfa numérico, un ejemplo válido sería: B001';
                echo json_encode($msj);
                exit();
            }

            if($notadebito_boleta_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para la nota de débito que modifica una boleta debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($notadebito_boleta_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido para la nota de débito que modifica una boleta no es válido!';
                echo json_encode($msj);
                exit();
            }

            //Guía de Remisión
            if(!preg_match("/^T[a-zA-Z0-9_]{3}$/", $guia_remision_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Guía de Remisión debe Empezar con "T", seguido por tres caracteres alfa numérico, un ejemplo válido sería: T001';
                echo json_encode($msj);
                exit();
            }

            if($guia_remision_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para la Guía de Remisión debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($guia_remision_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido para la Guía de Remisión no es válido!';
                echo json_encode($msj);
                exit();
            }

            //Guía Transportista
            if(!preg_match("/^V[a-zA-Z0-9_]{3}$/", $guia_transportista_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que la Serie de la Guía Transportista debe Empezar con "V", seguido por tres caracteres alfa numérico, un ejemplo válido sería: V001';
                echo json_encode($msj);
                exit();
            }

            if($guia_transportista_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que el número válido para la Guía Transportista debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($guia_transportista_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El formato elegido para la Guía Transportista no es válido!';
                echo json_encode($msj);
                exit();
            }

            //Orden de Compra
            if(!preg_match("/^OC[a-zA-Z0-9_]{2}$/", $orden_compra_serie)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que Serie de la Orden de Compra debe Empezar con "OC", seguido por dos caracteres alfa numérico, un ejemplo válido sería: OC01';
                echo json_encode($msj);
                exit();
            }

            if($orden_compra_numero <= 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerde que el número válido para la Orden de Compra debe ser mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if(!in_array($orden_compra_formato, $array_formatos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error'; 
                $msj['mensaje'] = 'El formato elegido para la Orden de Compra no es válido!';
                echo json_encode($msj);
                exit();
            }

            //validación de id de plantillas PDF
            if(!$this->validar_id_plantillapdf($id_plantillapdf_boleta_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Boleta en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_boleta_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Boleta en Tamaño Ticket no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_factura_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Factura en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_factura_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Factura en Tamaño ticket no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_notacredito_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Nota de Crédito en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_notacredito_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Nota de Crédito en Tamaño Ticket no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_notadebito_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Nota de Débito en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_notadebito_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Nota de Débito en Tamaño Ticket no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_cotizacion_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Cotización en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_cotizacion_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Cotización en Tamaño Ticket no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_guiaremision_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Guía de Remisión en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_guiaremision_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Guía de Remisión en Tamaño Ticket no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_notaventa_a4)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Nota de Venta en Tamaño A4 no existe!';
                echo json_encode($msj);
                exit();
            }

            if(!$this->validar_id_plantillapdf($id_plantillapdf_notaventa_ticket)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'La plantilla para la Nota de Venta en Tamaño Ticket no existe!';
                echo json_encode($msj);
                exit();
            }
            
            //Validamos que las series de los comprobantes no se repitan en una misma sucursal
            $array_series = array();
            $array_series[] = $factura_serie;
            $array_series[] = $boleta_serie;
            $array_series[] = $notacredito_factura_serie;
            $array_series[] = $notadebito_factura_serie;
            $array_series[] = $notacredito_boleta_serie;
            $array_series[] = $notadebito_boleta_serie;
            $array_series[] = $guia_remision_serie;
            $array_series[] = $guia_transportista_serie;
            $array_series[] = $orden_compra_serie;

            if($this->existen_duplicados($array_series)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Recuerda que ninguna serie debe duplicarse en una misma sucursal, se debe utilizar series diferentes para cada documento electrónico';
                echo json_encode($msj);
                exit();
            }
            
            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            $nuevo_registro = false;
                
            $this->db->begin();

            if(!$sucursal) {
                $nuevo_registro = true;    
                $sucursal = new Sucursal();
                $sucursal->id_contribuyente = $usuario->id_contribuyente;
                $sucursal->fecha_registro = date('Y-m-d H:i:s');
                $sucursal->estado = 'activo';

                //Verificamos que cada serie asignada a la sucursal sea única entre todas las sucursales
                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'factura_serie', $factura_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$factura_serie.' para las Facturas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'boleta_serie', $boleta_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$boleta_serie.' para las Boletas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notacredito_factura_serie', $notacredito_factura_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notacredito_factura_serie.' para las Notas de Crédito de Facturas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notadebito_factura_serie', $notadebito_factura_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notadebito_factura_serie.' para las Notas de Débito de Facturas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notacredito_boleta_serie', $notacredito_boleta_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notacredito_boleta_serie.' para las Notas de Crédito de Boletas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notadebito_boleta_serie', $notadebito_boleta_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notadebito_boleta_serie.' para las Notas de Débito de Boletas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'guia_remision_serie', $guia_remision_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$guia_remision_serie.' para las Guías de Remisión, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'guia_transportista_serie', $guia_transportista_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$guia_transportista_serie.' para las Guías de Transportista, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'orden_compra_serie', $orden_compra_serie)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$orden_compra_serie.' para las Ordenes de Compra, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }
                
            } else {
                //*=========== VALIDACIÓN PARA FACTURA ================ */
                if($sucursal->factura_serie != $factura_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '01', $sucursal->factura_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->factura_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->factura_numero != $factura_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '01', $sucursal->factura_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->factura_serie.' y el número '.$sucursal->factura_numero.', ya han sido utilizados para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                //Verificamos que cada serie asignada a la sucursal sea única entre todas las sucursales
                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'factura_serie', $factura_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$factura_serie.' para las Facturas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales...';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA BOLETA ================ */

                if($sucursal->boleta_serie != $boleta_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '03', $sucursal->boleta_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->boleta_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->boleta_numero != $boleta_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '03', $sucursal->boleta_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->boleta_serie.' y el número '.$sucursal->boleta_numero.', ya han sido utilizados para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'boleta_serie', $boleta_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$boleta_serie.' para las Boletas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA NOTAS DE CRÉDITO DE FACTURAS ================ */

                if($sucursal->notacredito_factura_serie != $notacredito_factura_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '07', $sucursal->notacredito_factura_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notacredito_factura_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->notacredito_factura_numero != $notacredito_factura_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '07', $sucursal->notacredito_factura_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notacredito_factura_serie.' y el número '.$sucursal->notacredito_factura_numero.', ya han sido utilizados para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notacredito_factura_serie', $notacredito_factura_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notacredito_factura_serie.' para las Notas de Crédito de Facturas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA NOTAS DE DEBITO DE FACTURAS ================ */

                if($sucursal->notadebito_factura_serie != $notadebito_factura_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '08', $sucursal->notadebito_factura_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notadebito_factura_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->notadebito_factura_numero != $notadebito_factura_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '08', $sucursal->notadebito_factura_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notadebito_factura_serie.' y el número '.$sucursal->notadebito_factura_numero.', ya han sido utilizados para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notadebito_factura_serie', $notadebito_factura_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notadebito_factura_serie.' para las Notas de Débito de Facturas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA NOTAS DE CRÉDITO DE BOLETAS ================ */

                if($sucursal->notacredito_boleta_serie != $notacredito_boleta_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '07', $sucursal->notacredito_boleta_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notacredito_boleta_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->notacredito_boleta_numero != $notacredito_boleta_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '07', $sucursal->notacredito_boleta_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notacredito_boleta_serie.' y el número '.$sucursal->notacredito_boleta_numero.', ya han sido utilizados para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notacredito_boleta_serie', $notacredito_boleta_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notacredito_boleta_serie.' para las Notas de Crédito de Boletas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA NOTAS DE DEBITO DE BOLETAS ================ */

                if($sucursal->notadebito_boleta_serie != $notadebito_boleta_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '08', $sucursal->notadebito_boleta_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notadebito_boleta_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->notadebito_boleta_numero != $notadebito_boleta_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '08', $sucursal->notadebito_boleta_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->notadebito_boleta_serie.' y el número '.$sucursal->notadebito_boleta_numero.', ya han sido utilizados para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'notadebito_boleta_serie', $notadebito_boleta_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$notadebito_boleta_serie.' para las Notas de Débito de Boletas, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA GUÍAS DE REMISIÓN ================ */

                //Guía de remisión
                if($sucursal->guia_remision_serie != $guia_remision_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '09', $sucursal->guia_remision_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->guia_remision_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->guia_remision_numero != $guia_remision_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '09', $sucursal->guia_remision_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->guia_remision_serie.' y el número '.$sucursal->guia_remision_numero.', ya han sido utilizados para emitir una guía de remisión electrónica, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'guia_remision_serie', $guia_remision_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$guia_remision_serie.' para las guía de remisión, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA GUÍAS TRANSPORTISTA ================ */

                if($sucursal->guia_transportista_serie != $guia_transportista_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '31', $sucursal->guia_transportista_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->guia_transportista_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->guia_transportista_numero != $guia_transportista_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '31', $sucursal->guia_transportista_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->guia_transportista_serie.' y el número '.$sucursal->guia_transportista_numero.', ya han sido utilizados para emitir una guía de remisión electrónica, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'guia_remision_serie', $guia_transportista_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$guia_transportista_serie.' para las guía de remisión, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }

                //*=========== VALIDACIÓN PARA ORDEN DE COMPRA ================ */

                if($sucursal->orden_compra_serie != $orden_compra_serie) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '99', $sucursal->orden_compra_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->orden_compra_serie.', ya ha sido utilizada para emitir un comprobante electrónico, por lo tanto ya no tiene permitido cambiar las series de sus comprobantes.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($sucursal->orden_compra_numero != $orden_compra_numero) {
                    if($this->verificar_docs_emitidos($usuario->id_contribuyente, '99', $sucursal->orden_compra_serie)) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'La serie '.$sucursal->orden_compra_serie.' y el número '.$sucursal->orden_compra_numero.', ya han sido utilizados para emitir una guía de remisión electrónica, por lo tanto ya no tiene permitido cambiar la numeración inicial de sus comprobantes electrónicos.';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($this->verificar_si_serie_ya_esta_asignada($usuario->id_contribuyente, 'guia_remision_serie', $orden_compra_serie, $sucursal->idsucursal)) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La Serie '.$orden_compra_serie.' para las guía de remisión, ya fué asignada a otra sucursal, las series no se deben repetir entre sucursales.';
                    echo json_encode($msj);
                    exit();
                }
            }

            //Factura
            $sucursal->factura_serie = $factura_serie;
            $sucursal->factura_numero = $factura_numero;

            //boleta
            $sucursal->boleta_serie = $boleta_serie;
            $sucursal->boleta_numero = $boleta_numero;

            //nota de crédito que modifica una factura
            $sucursal->notacredito_factura_serie = $notacredito_factura_serie;
            $sucursal->notacredito_factura_numero = $notacredito_factura_numero;
        
            //nota de débito que modifica una factura
            $sucursal->notadebito_factura_serie = $notadebito_factura_serie;
            $sucursal->notadebito_factura_numero = $notadebito_factura_numero;
        
            //nota de débito que modifica una boleta
            $sucursal->notacredito_boleta_serie = $notacredito_boleta_serie;
            $sucursal->notacredito_boleta_numero = $notacredito_boleta_numero;
        
            //nota de crédito que modifica una boleta
            $sucursal->notadebito_boleta_serie = $notadebito_boleta_serie;
            $sucursal->notadebito_boleta_numero = $notadebito_boleta_numero;

            //Guía de Remisión
            $sucursal->guia_remision_serie = $guia_remision_serie;
            $sucursal->guia_remision_numero = $guia_remision_numero;

            //Guía Tranportista
            $sucursal->guia_transportista_serie = $guia_transportista_serie;
            $sucursal->guia_transportista_numero = $guia_transportista_numero;

            //Orden de Compra
            $sucursal->orden_compra_serie = $orden_compra_serie;
            $sucursal->orden_compra_numero = $orden_compra_numero;

            //factor igv
            $sucursal->factor_igv = $factor_igv;

            $sucursal->glosa_amazonia = $glosa_amazonia;

            $sucursal_codigo = false;
            /*
            if($codigo == '0000') {
                $sucursal_codigo = false;
            } else {
                if($nuevo_registro) {
                    $sucursal_codigo = Sucursal::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'id_contribuyente' => $usuario->id_contribuyente)));
                } else {
                    $sucursal_codigo = Sucursal::findFirst(array("codigo = :codigo: and idsucursal <> :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'idsucursal' => $sucursal->idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                }
            } 
            */
            
            if($sucursal_codigo) {
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El código ya existe, por favor debes generar o ingresar un nuevo código!.';
                echo json_encode($resp);
                exit();
            }

            $sucursal->factura_formato              = $factura_formato;
            $sucursal->boleta_formato               = $boleta_formato;
            $sucursal->notacredito_factura_formato  = $notacredito_factura_formato;
            $sucursal->notadebito_factura_formato   = $notadebito_factura_formato;
            $sucursal->notacredito_boleta_formato   = $notacredito_boleta_formato;
            $sucursal->notadebito_boleta_formato    = $notadebito_boleta_formato;
            $sucursal->guia_remision_formato        = $guia_remision_formato;

            $sucursal->codigo                   = $codigo;
            $sucursal->nombre                   = $nombre_sucursal;
            $sucursal->direccion                = $direccion;
            $sucursal->id_ubigeo                = $ubigeo;
            $sucursal->urbanizacion             = $urbanizacion;
            $sucursal->telefono                 = $telefono;
            $sucursal->email                    = $email;
            $sucursal->sitio_web                = $sitioweb;
            $sucursal->informacion_adicional    = $info_adicional;
            $sucursal->leyenda_comprobantes     = $leyenda_comprobantes;

            $sucursal->txt_pdf_a4_1     = $txt_pdf_a4_1;
            $sucursal->txt_pdf_a4_2     = $txt_pdf_a4_2;
            $sucursal->txt_pdf_a4_3     = $txt_pdf_a4_3;
            $sucursal->txt_pdf_ticket_1 = $txt_pdf_ticket_1;
            $sucursal->txt_pdf_ticket_2 = $txt_pdf_ticket_2;
            $sucursal->txt_pdf_ticket_3 = $txt_pdf_ticket_3;
            
            $sucursal->plantilla_pdf_a4     = intval($plantilla_pdf_a4);
            $sucursal->plantilla_pdf_ticket = $plantilla_pdf_ticket;

            $sucursal->id_plantillapdf_boleta_a4            = $id_plantillapdf_boleta_a4;
            $sucursal->id_plantillapdf_boleta_ticket        = $id_plantillapdf_boleta_ticket;
            $sucursal->id_plantillapdf_factura_a4           = $id_plantillapdf_factura_a4;
            $sucursal->id_plantillapdf_factura_ticket       = $id_plantillapdf_factura_ticket;
            $sucursal->id_plantillapdf_notacredito_a4       = $id_plantillapdf_notacredito_a4;
            $sucursal->id_plantillapdf_notacredito_ticket   = $id_plantillapdf_notacredito_ticket;
            $sucursal->id_plantillapdf_notadebito_a4        = $id_plantillapdf_notadebito_a4;
            $sucursal->id_plantillapdf_notadebito_ticket    = $id_plantillapdf_notadebito_ticket;
            $sucursal->id_plantillapdf_cotizacion_a4        = $id_plantillapdf_cotizacion_a4;
            $sucursal->id_plantillapdf_cotizacion_ticket    = $id_plantillapdf_cotizacion_ticket;
            $sucursal->id_plantillapdf_guiaremision_a4      = $id_plantillapdf_guiaremision_a4;
            $sucursal->id_plantillapdf_guiaremision_ticket  = $id_plantillapdf_guiaremision_ticket;
            $sucursal->id_plantillapdf_notaventa_a4         = $id_plantillapdf_notaventa_a4;
            $sucursal->id_plantillapdf_notaventa_ticket     = $id_plantillapdf_notaventa_ticket;

            $sucursal->boleta_mostrar_items_igv         = $boleta_mostrar_items_igv;
            $sucursal->factura_mostrar_items_igv        = $factura_mostrar_items_igv;
            $sucursal->notacredito_mostrar_items_igv    = $notacredito_mostrar_items_igv;
            $sucursal->notadebito_mostrar_items_igv     = $notadebito_mostrar_items_igv;
            $sucursal->guiaremision_mostrar_items_igv   = $guiaremision_mostrar_items_igv;
            $sucursal->cotizacion_mostrar_items_igv     = $cotizacion_mostrar_items_igv;
            $sucursal->notaventa_mostrar_items_igv      = $notaventa_mostrar_items_igv;


            $sucursal->boleta_mostrar_yape      = $boleta_mostrar_yape;
            $sucursal->factura_mostrar_yape     = $factura_mostrar_yape;
            $sucursal->notaventa_mostrar_yape   = $notaventa_mostrar_yape;
            $sucursal->boleta_mostrar_plin      = $boleta_mostrar_plin;
            $sucursal->factura_mostrar_plin     = $factura_mostrar_plin;
            $sucursal->notaventa_mostrar_plin   = $notaventa_mostrar_plin;
            $sucursal->cotizacion_mostrar_yape  = $cotizacion_mostrar_yape;
            $sucursal->cotizacion_mostrar_plin  = $cotizacion_mostrar_plin;

            $sucursal->img_qr_yape  = $img_qr_yape;
            $sucursal->titular_yape = $titular_yape;
            $sucursal->celular_yape = $celular_yape;
            $sucursal->img_qr_plin  = $img_qr_plin;
            $sucursal->titular_plin = $titular_plin;
            $sucursal->celular_plin = $celular_plin;

            try {
                if(!$sucursal->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($sucursal->getMessages() as $message) {
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
                $resp['mensaje'] = '=> '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $boleta_modifica_stock          = !isset($datapost['boleta_modifica_stock'])?'no':(($datapost['boleta_modifica_stock'] == 'on')?'si':'no');
            $factura_modifica_stock         = !isset($datapost['factura_modifica_stock'])?'no':(($datapost['factura_modifica_stock'] == 'on')?'si':'no');
            $notacredito_modifica_stock     = !isset($datapost['notacredito_modifica_stock'])?'no':(($datapost['notacredito_modifica_stock'] == 'on')?'si':'no');
            $notadebito_modifica_stock      = !isset($datapost['notadebito_modifica_stock'])?'no':(($datapost['notadebito_modifica_stock'] == 'on')?'si':'no');
            $cotizacion_modifica_stock      = !isset($datapost['cotizacion_modifica_stock'])?'no':(($datapost['cotizacion_modifica_stock'] == 'on')?'si':'no');
            $guiaremision_modifica_stock    = !isset($datapost['guiaremision_modifica_stock'])?'no':(($datapost['guiaremision_modifica_stock'] == 'on')?'si':'no');
            $notaventa_modifica_stock       = !isset($datapost['notaventa_modifica_stock'])?'no':(($datapost['notaventa_modifica_stock'] == 'on')?'si':'no');
            
            $resp_boleta_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'boleta_modifica_stock', $boleta_modifica_stock);
            if($resp_boleta_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_boleta_modifica_stock);
                exit();
            }

            $resp_factura_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'factura_modifica_stock', $factura_modifica_stock);
            if($resp_factura_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_factura_modifica_stock);
                exit();
            }

            $resp_notacredito_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'notacredito_modifica_stock', $notacredito_modifica_stock);
            if($resp_notacredito_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_notacredito_modifica_stock);
                exit();
            }

            $resp_notadebito_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'notadebito_modifica_stock', $notadebito_modifica_stock);
            if($resp_notadebito_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_notadebito_modifica_stock);
                exit();
            }

            $resp_cotizacion_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'cotizacion_modifica_stock', $cotizacion_modifica_stock);
            if($resp_cotizacion_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_cotizacion_modifica_stock);
                exit();
            }

            $resp_guiaremision_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'guiaremision_modifica_stock', $guiaremision_modifica_stock);
            if($resp_guiaremision_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_guiaremision_modifica_stock);
                exit();
            }

            $resp_notaventa_modifica_stock = $this->set_sucursal_opcion($usuario->id_contribuyente, $sucursal->idsucursal, 'notaventa_modifica_stock', $notaventa_modifica_stock);
            if($resp_notaventa_modifica_stock['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_notaventa_modifica_stock);
                exit();
            }

            $log = new LogsController;
            $data['id_contribuyente'] = $usuario->id_contribuyente;
            $data['idusuario'] = $usuario->idusuario;
            $data['descripcion'] = 'Modificó la configuración de la Sucursal con id = '.$sucursal->idsucursal;
            $data['tabla'] = 'sucursal';
            $data['columna'] = 'all';

            $resp_log = $log->log_configuracion_sistema($data);
            if($resp_log['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_log);
                exit();
            }
            
            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'Se guardó correctamente la sucursal con id = '.$sucursal->idsucursal;
            $resp['idsucursal'] = $sucursal->idsucursal;
            echo json_encode($resp);
            exit();
        }
    }

    public function validar_id_plantillapdf($id_plantillapdf) {
        $plantillapdf = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf)));
        if(!$plantillapdf) {
            return false;
        }
        return true;
    }

    public function verificar_si_serie_ya_esta_asignada($id_contribuyente, $nombre_campo, $serie_comprobante, $idsucursal = '') {
        if($idsucursal == '') {
            $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and [".$nombre_campo."] = :serie_comprobante:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'serie_comprobante' => $serie_comprobante)));
        } else {
            $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and [".$nombre_campo."] = :serie_comprobante: and idsucursal <> :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'serie_comprobante' => $serie_comprobante, 'idsucursal' => $idsucursal)));
        }
        
        if(!$sucursal) {
            return false;
        }

        return true;
    }

    public function existen_duplicados($array) {
        return count($array) !== count(array_unique($array));
    }

    public function verificar_docs_emitidos($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante) {
        $documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and tipo_envio_sunat = 'produccion'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante)));
        if(!$documento_modificado) {
            return false;
        }
        return true;
    }

    public function getSucursalAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idsucursal = intval($datapost['idsucursal']) + 0;

            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_permisos = $herramientas->verificar_permisos_usuario($usuario->idusuario);
            if($resp_permisos['respuesta'] == 'error') {
                echo json_encode($resp_permisos);
                exit();
            }

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));

            $resp['respuesta'] = 'ok';
            $resp['sucursal'] = $sucursal;
            echo json_encode($resp);
            exit();
        }
    }

    public function listaSucursalesAction() {
		$this->setTitle('Lista de Sucursales');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/branchoffice/listasucursales.js?i=v3");
    }

    public function getListaSucursalesAction() {
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
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $array_lista = array();
            foreach($lista_sucursales as $item) {
                if($item->estado == 'activo') {
                    $estado = '<span class="label label-success">Activo</span>';
                    $menu_estado = '<li><a href="javascript:void(0)" onclick="cambiar_estado_sucursal('.$item->idsucursal.', '."'desactivar'".')"><i class="icon-cancel-circle2"></i> Desactivar Sucursal</a></li>';
                } else {
                    $estado = '<span class="label label-danger">Inactivo</span>';
                    $menu_estado = '<li><a href="javascript:void(0)" onclick="cambiar_estado_sucursal('.$item->idsucursal.', '."'activar'".')"><i class="icon-checkmark4"></i> Activar Sucursal</a></li>';
                }

                

                $opciones_menu = '
                <ul class="icons-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a target="_blank" href="/facturacionv8/branchoffice/index/'.$item->idsucursal.'"><i class="icon-pencil4"></i> Editar</a></li>
                            '.$menu_estado.'
                        </ul> 
                    </li>
                </ul>
                ';

                $ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $item->id_ubigeo)));
                $ubigeo_texto = $ubigeo->departamento.' - '.$ubigeo->provincia.' '.$ubigeo->distrito;
                $comprobantes = '
                Factura Electrónica | '.$item->factura_serie.'-'.$item->factura_numero.'<br />
                Boleta | '.$item->boleta_serie.'-'.$item->boleta_numero.'<br />
                Nota de Crédito Factura | '.$item->notacredito_factura_serie.'-'.$item->notacredito_factura_numero.'<br />
                Nota de Débito Factura | '.$item->notadebito_factura_serie.'-'.$item->notadebito_factura_numero.'<br />
                Nota de Crédito Boleta | '.$item->notacredito_boleta_serie.'-'.$item->notacredito_boleta_numero.'<br />
                Nota de Débito Boleta: | '.$item->notadebito_boleta_serie.'-'.$item->notadebito_boleta_numero.'<br />
                Guía de Remisión: | '.$item->guia_remision_serie.'-'.$item->guia_remision_numero.'<br />
                ';

                $array_lista[] = array(
                    $item->idsucursal,
                    $item->codigo, 
                    $item->nombre, 
                    $item->direccion.'<br />'.$ubigeo_texto,
                    $comprobantes, 
                    $estado, 
                    $opciones_menu
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function cambiarEstadoSucursalAction() {
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
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_permisos = $herramientas->verificar_permisos_usuario($usuario->idusuario);
            if($resp_permisos['respuesta'] == 'error') {
                echo json_encode($resp_permisos);
                exit();
            }

            $idsucursal = !isset($datapost['idsucursal'])?0:intval($datapost['idsucursal']) + 0;
            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Sucursal';
                $msj['mensaje'] = 'La Sucursal que intenta eliminar no existe.';
                echo json_encode($msj);
                exit();
            }
            
            /*
            $documentos = DocElectronico::find(array("id_sucursal = :id_sucursal:", 'bind' => array('id_sucursal' => $sucursal->idsucursal)));
            if(count($documentos) > 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'No es posible eliminar la sucursal, pues la actual sucursal ya ha emitido documentos electrónicos!';
                echo json_encode($msj);
                exit();
            }
            */

            $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $idsucursal)));

            if($sucursal->estado == 'activo') {
                $sucursal_bd->estado = 'inactivo';
            } else {
                $sucursal_bd->estado = 'activo';
            }
            
            try {
                if(!$sucursal_bd->save()) {
                    $msg = '';
                    foreach ($sucursal_bd->getMessages() as $message) {
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
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'El estado ha sido cambiado correctamente!';
            echo json_encode($resp);
            exit();
        }
    }

    public function getPlantillasUserAction() {
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
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $id_documento = $datapost['tipo_documento'];
            $tipos_comprobantes_validos = array('03', '01', '07', '08', '77', '88', '09');
            if(!in_array($id_documento, $tipos_comprobantes_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se reconoce el tipo de documento';
                echo json_encode($resp);
                exit();
            }

            $tamanio = $datapost['tamanio'];
            if(!in_array($tamanio, array('ticket', 'a4'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Tamaño';
                $resp['mensaje'] = 'Error, no se reconoce el tamaño';
                echo json_encode($resp);
                exit();
            }

            $plantillas = Plantillapdf::find(array("ids_tipodocelectronico like '%$id_documento%' and estado = 'activo' and tipo = :tipo: and tamanio = :tamanio:", 'bind' => array('tipo' => 'normal', 'tamanio' => $tamanio)));
            $plantillas_personalizadas = Plantillapdf::find(array("ids_tipodocelectronico like '%$id_documento%' and estado = 'activo' and tipo = 'personalizado' and id_contribuyente = :id_contribuyente: and tamanio = :tamanio:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tamanio' => $tamanio)));
            $lista_plantillas_normales = array();
            $lista_plantillas_personalizadas = array();

            foreach($plantillas as $item) {
                $lista_plantillas_normales[] = array(
                    'nombre'    => $item->nombre,
                    'tamanio'   => $item->tamanio,
                    'preview'   => $item->preview,
                    'tipo'      => $item->tipo,
                    'id_plantillapdf'   => $item->id_plantillapdf
                );
            }

            foreach($plantillas_personalizadas as $item) {
                $lista_plantillas_personalizadas[] = array(
                    'nombre'    => $item->nombre,
                    'tamanio'   => $item->tamanio,
                    'preview'   => $item->preview,
                    'tipo'      => $item->tipo,
                    'id_plantillapdf'   => $item->id_plantillapdf
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['normales'] = $lista_plantillas_normales;
            $resp['personalizadas'] = $lista_plantillas_personalizadas;
            echo json_encode($resp);
            exit();
        }
    }
}
?>