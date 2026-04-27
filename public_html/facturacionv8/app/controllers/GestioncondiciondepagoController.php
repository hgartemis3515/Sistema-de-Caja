<?php

class GestioncondiciondepagoController extends ControllerBase 
{
    public function indexAction() {
		$this->setTitle('Gestion de condicion de pago');
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
        ->addJs($this->baseUri . "public/js/general.js?j=".rand())
        ->addJs($this->baseUri . "public/js/gestioncondiciondepago.js?i=v2");
    }

    public function saveAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_condicionpago = !isset($datapost['idcondicionpago'])?0:intval($datapost['idcondicionpago']) + 0;
            $tipo_pago = !isset($datapost['tipo_pago'])?'':$datapost['tipo_pago'];
            $condicion_pago = !isset($datapost['condicion_pago'])?'':$datapost['condicion_pago'];

            //Verificando sesion
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

            //Verificando campos vacíos
           if(empty($tipo_pago)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes elegir un tipo de pago, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }

            $array_tipos = array('contado', 'credito', 'tarjeta_credito', 'transferencia');
            if(!in_array($tipo_pago, $array_tipos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo seleccionado no es válido!';
                echo json_encode($resp);
                exit();
            }

            if(empty($condicion_pago)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar una condición de pago, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }

            $condiciondepago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $id_condicionpago)));
            if(!$condiciondepago) {
                $condiciondepago = new Condiciondepago();
                $condiciondepago->id_contribuyente = $usuario->id_contribuyente;
            }

            $condiciondepago->tipo = $tipo_pago;
            $condiciondepago->condicionpago = $condicion_pago;
            $condiciondepago->estado = 'activo';

            try {
                if(!$condiciondepago->save()) {
                    $msg = '';
                    foreach ($condiciondepago->getMessages() as $message) {
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
            $resp['mensaje'] = 'La condición de pago se ha guardado correctamente!';
            echo json_encode($resp);
            exit();

    
        }
    }
    public function getListaCondicionpagoAction()
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
            
            $lista_condicionpago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

            $array_lista = array();
            foreach($lista_condicionpago as $item){
                $opciones = '<ul class="icons-list text-center">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="edit_condicionpago('.$item->id_condicionpago.')" data-idcondicionpago="'.$item->id_condicionpago.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                    <li><a onclick="eliminar_condicionpago('.$item->id_condicionpago.')" data-idcondicionpago="'.$item->id_condicionpago.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                </ul>
                            </li>
                        </ul>';

                $array_lista[] = array($item->id_condicionpago, $item->condicionpago,  $item->tipo, $opciones);
            }
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();

        }
    }

    public function getDataCondicionpagoAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_condicionpago = !isset($datapost['idcondicionpago'])?0:intval($datapost['idcondicionpago']) + 0;

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

            $condiciondepago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));


            if(!$condiciondepago) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la condición de pago seleccionada, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['pago'] = $condiciondepago;
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarCondicionpagoAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_condicionpago = !isset($datapost['idcondicionpago'])?0:intval($datapost['idcondicionpago']) + 0;

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

            $condiciondepago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$condiciondepago) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la condición de pago seleccionada, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            $condiciondepago->estado = 'inactivo';

            try {
                if(!$condiciondepago->save()) {
                    $msg = '';
                    foreach ($condiciondepago->getMessages() as $message) {
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
            $resp['mensaje'] = 'La condición de pago se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();
        }
    }
}

?>