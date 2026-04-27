<?php

class GestiondeplantillasController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Gestión de plantillas');
        $this->view->setTemplateAfter('main');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css");

        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/libraries/jquery_ui/core.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/subir_imagen.js?i=v3")
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/gestiondeplantillas.js?i=".rand());     
            
            $categorias = EpCategoria::find();
            $this->view->categorias = $categorias;
    }

    public function previewTemplateAction($idtemplate) {
        $this->setTitle('Gestión de plantillas');
        $this->view->setTemplateAfter('main');
        $this->view->disable();

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

        $plantilla = EpTemplate::findFirst(array("idtemplate = :idtemplate:", 'bind' => array('idtemplate' => $idtemplate)));
        if(!$plantilla) {
            exit();
        }

        echo $plantilla->html;
        exit();
    }

    public function saveAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idtemplate = !isset($datapost['idplantilla'])?0:intval($datapost['idplantilla']) + 0;
            $nombre = !isset($datapost['nombre_plantilla'])?'':$datapost['nombre_plantilla'];
            $idcategoria = !isset($datapost['categoria_plantilla'])?0:intval($datapost['categoria_plantilla']);
            $assets = !isset($datapost['assets_plantilla'])?'':$datapost['assets_plantilla'];
            $html = !isset($datapost['html_plantilla'])?'':$datapost['html_plantilla'];
            $url_image = !isset($datapost['preview_imagen'])?'':$datapost['preview_imagen'];

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

            //Verificando campos vacíos
            if(empty($nombre)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un nombre para la plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }

            $categoria = EpCategoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $idcategoria)));
            if(!$categoria) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La categoría seleccionada no existe!';
                echo json_encode($resp);
                exit();
            }

            if(empty($assets)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar los assets de la plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }
            if(empty($html)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar el HTML  de la plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }
            if(empty($url_image)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes subir seleccionar una imagen para la vista previa de la plantilla';
                echo json_encode($resp);
                exit();
            }

            $plantilla = EpTemplate::findFirst(array("idtemplate = :idtemplate:", 'bind' => array('idtemplate' => $idtemplate)));
            if(!$plantilla) {
                $plantilla = new EpTemplate();
            }

            $plantilla->idtemplate = $idtemplate;
            $plantilla->nombre = $nombre;
            $plantilla->idcategoria = $idcategoria;
            $plantilla->ruta_assets = $assets;
            $plantilla->img_preview = $url_image;
            $plantilla->html = $html;
            $plantilla->fecharegistro = date('Y-m-d H:i:s');
            $plantilla->estado = 'activo';

            try {
                if(!$plantilla->save()) {
                    $msg = '';
                    foreach ($plantilla->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'La plantilla se ha guardado correctamente!';
            echo json_encode($resp);
            exit();


        }

    }
    public function getListPlantillaAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
            if($usuario->id_contribuyente == 1) {
                $lista_template = EpTemplate::find("estado = 'activo'");
            } else {
                $lista_template = EpTemplate::find("estado = 'activo' and modalidad = 'normal'");
            }
            
            $array_lista = array();
            foreach($lista_template as $item){
                $opciones = '<ul class="icons-list text-center">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="edit_template('.$item->idtemplate.')" data-idtemplate="'.$item->idtemplate.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                    <li><a onclick="eliminar_template('.$item->idtemplate.')" data-idtemplate="'.$item->idtemplate.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                    <li><a target="_blank" href="/facturacionv8/gestiondeplantillas/preview_template/'.$item->idtemplate.'"><i class="icon-user-cancel"></i> Preview</a></li>
                                </ul>
                            </li>
                        </ul>';

                $nombre_categoria = '';
                $categoria = EpCategoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $item->idcategoria)));
                if($categoria) {
                    $nombre_categoria = $categoria->nombre;
                }
                $img_preview = ' <img class="img-preview-pag"  src="'.$item->img_preview.'" alt="preview_template" width="120px">';
                $array_lista[] = array($item->idtemplate, $img_preview, $item->nombre,  $nombre_categoria, $item->ruta_assets, $opciones);
            }
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();

        }
    }

    public function getDataPlantillaAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idtemplate = !isset($datapost['idplantilla'])?0:intval($datapost['idplantilla']) + 0;
        
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
            $template = EpTemplate::findFirst(array("idtemplate = :idtemplate:", 'bind' => array('idtemplate' => $idtemplate)));
            if(!$template) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguidola plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['plantilla'] = $template;
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarPlantillaAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idtemplate = !isset($datapost['idplantilla'])?0:intval($datapost['idplantilla']) + 0;

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

            $template = EpTemplate::findFirst(array("idtemplate = :idtemplate:", 'bind' => array('idtemplate' => $idtemplate)));
            if(!$template) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguidola plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            $template->estado = 'inactivo';
            try {
                if(!$template->save()) {
                    $msg = '';
                    foreach ($template->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'La plantilla  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();


        }
    }
}
?>