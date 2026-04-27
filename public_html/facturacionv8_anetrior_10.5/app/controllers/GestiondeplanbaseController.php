<?php

class GestiondeplanbaseController extends ControllerBase

{

    public function indexAction() {
		$this->setTitle('Gestion de plan base');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/gestionplanbase.js?i=v2");
    }

    public function saveAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idplanbase = !isset($datapost['idplanbase'])?0:intval($datapost['idplanbase']) + 0;
            $nombre_plan = !isset($datapost['nombre_plan'])?'':$datapost['nombre_plan'];
            $num_dias = !isset($datapost['num_dias'])?'':$datapost['num_dias'];
            $limite_doc = !isset($datapost['limite_doc'])?'':$datapost['limite_doc'];
            $tipo_plan = !isset($datapost['tipo_plan'])?'':$datapost['tipo_plan'];
            $precio_base = !isset($datapost['precio_base'])?'':$datapost['precio_base'];
            $porcentaje = !isset($datapost['porcentaje'])?'':$datapost['porcentaje'];
            $nota = !isset($datapost['nota'])?'':$datapost['nota'];

            //Verificando campos vacíos
            if(empty($nombre_plan)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un nombre del plan, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($num_dias)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un número de días, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($limite_doc)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un límite de documentos, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($tipo_plan)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un tipo de plan, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($precio_base)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un precio base, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            if(empty($porcentaje)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un porcentaje, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            $planbase = Planbase::findFirst(array("idplanbase = :idplanbase:", 'bind' => array('idplanbase' => $idplanbase)));

            if(!$planbase) {
                $planbase = new Planbase();
                $planbase->fecha_registro =  date('Y-m-d H:i:s');
            }

            $planbase->nombre = $nombre_plan;
            $planbase->num_dias = $num_dias;
            $planbase->limite_mes_doc = $limite_doc;
            $planbase->tipo = $tipo_plan;
            $planbase->precio_base = $precio_base;
            $planbase->porcentaje = $porcentaje;
            $planbase->nota = $nota;
            $planbase->estado = 'activo';

            try {
                if(!$planbase->save()) {
                    $msg = '';
                    foreach ($planbase->getMessages() as $message) {
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
            $resp['mensaje'] = 'El plan  se ha guardado correctamente!';
            echo json_encode($resp);
            exit();

           
        }
    }
    public function getListaPlanbaseAction()
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
            $lista_planbase = Planbase::find(array("estado = 'activo'"));
            $array_lista = array();
            foreach($lista_planbase as $item){
                $opciones = '<ul class="icons-list text-center">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="edit_planbase('.$item->idplanbase.')" data-idplanbase="'.$item->idplanbase.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                    <li><a onclick="eliminar_planbase('.$item->idplanbase.')" data-idplanbase="'.$item->idplanbase.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                </ul>
                            </li>
                        </ul>';

                $array_lista[] = array($item->idplanbase, $item->nombre,  $item->num_dias, $item->limite_mes_doc, $item->tipo, $item->precio_base, $item->porcentaje, $opciones);
            }
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();

        }
    }

    public function getDataPlanbaseAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idplanbase = !isset($datapost['idplanbase'])?0:intval($datapost['idplanbase']) + 0;

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

            $planbase = Planbase::findFirst(array("idplanbase = :idplanbase:", 'bind' => array('idplanbase' => $idplanbase)));
            if(!$planbase) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido el plan, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['plan'] = $planbase;
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarPlanAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idplanbase = !isset($datapost['idplanbase'])?0:intval($datapost['idplanbase']) + 0;

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

            $planbase = Planbase::findFirst(array("idplanbase = :idplanbase:", 'bind' => array('idplanbase' => $idplanbase)));
            if(!$planbase) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido el plan, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            $planbase->estado = 'inactivo';

            try {
                if(!$planbase->save()) {
                    $msg = '';
                    foreach ($planbase->getMessages() as $message) {
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
            $resp['mensaje'] = 'El plan  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();


        }
    }
}

?>