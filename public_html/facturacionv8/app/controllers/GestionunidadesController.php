<?php

class GestionunidadesController extends ControllerBase

{

    public function indexAction() {
		$this->setTitle('Gestion de Unidades');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
        ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/gestionunidades.js?i=v3");

      
    }

    public function guardarUnidadesAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idunidad = !isset($datapost['idunidad'])?0:intval($datapost['idunidad']) + 0;
            $codigo = !isset($datapost['codigo'])?'':$datapost['codigo'];
            $nombre_unidad = !isset($datapost['nombre_unidad'])?'':$datapost['nombre_unidad'];
            $simbolo_unidad = !isset($datapost['simbolo_unidad'])?'':$datapost['simbolo_unidad'];
           
            //Verificando campos vacíos
            if(empty($codigo)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un código de unidad, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($nombre_unidad)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar el nombre de la unidad, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($simbolo_unidad)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar el símbolo de la unidad, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
           
            $unidad_medidas = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad)));

            if(!$unidad_medidas) {
                $unidad_medidas = new SunatUnidadmedida();
            }

            $unidad_medidas->codigo = $codigo;
            $unidad_medidas->nombre = $nombre_unidad;
            $unidad_medidas->simbolo = $simbolo_unidad;
        
            try {
                if(!$unidad_medidas->save()) {
                    $msg = '';
                    foreach ($unidad_medidas->getMessages() as $message) {
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
            $resp['mensaje'] = 'La unidad  se ha guardado correctamente!';
            echo json_encode($resp);
            exit();

           
        }
    }
    public function getListaUnidadesAction()
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
            $lista_unidades = SunatUnidadmedida::find();
            $array_lista = array();
            foreach($lista_unidades as $item){
                $opciones = '<ul class="icons-list text-center">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="edit_unidades('.$item->idunidad.')" data-idunidad="'.$item->idunidad.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                </ul>
                            </li>
                        </ul>';

                $array_lista[] = array($item->idunidad, $item->codigo,  $item->nombre, $item->simbolo, $opciones);
            }
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();

        }
    }

    public function getDataUnidadesAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idunidad = !isset($datapost['idunidad'])?0:intval($datapost['idunidad']) + 0;

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

            $unidad_medidas = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad)));
            if(!$unidad_medidas) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la Unidad que deseas, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['unidad_medidas'] = $unidad_medidas;
            echo json_encode($resp);
            exit();

        }
    }

}

?>