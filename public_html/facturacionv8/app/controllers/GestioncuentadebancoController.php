<?php

class GestioncuentadebancoController extends ControllerBase

{

    public function indexAction() {
		$this->setTitle('Gestion de cuentas');
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
        ->addJs($this->baseUri . "public/js/gestioncuentabanco.js?i=v3");

        $moneda = SunatMoneda::find();
        $this->view->moneda = $moneda;

        $entidad_finciera = SunatCodigoentidadfinanciera::find("estado = 'activo'");
        $this->view->entidad_financiera = $entidad_finciera;
    }

    public function saveAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_cuentabanco = !isset($datapost['idcuentabanco'])?0:intval($datapost['idcuentabanco']) + 0;
            $id_codigomoneda = !isset($datapost['moneda'])?'':$datapost['moneda'];
            $tipo_cuenta = !isset($datapost['tipo_cuenta'])?'':$datapost['tipo_cuenta'];
            $nombre_banco = !isset($datapost['nombre_banco'])?'':$datapost['nombre_banco'];
            $nombre_titular = !isset($datapost['nombre_titular'])?'':$datapost['nombre_titular'];
            $nro_cuenta = !isset($datapost['num_cuenta'])?'':$datapost['num_cuenta'];
            $cci = !isset($datapost['cci'])?'':$datapost['cci'];
            $descripcion = !isset($datapost['descripcion'])?'':$datapost['descripcion'];
            $id_entidadfinanciera = !isset($datapost['entidad_financiera'])?'':$datapost['entidad_financiera'];

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
            if(empty($id_codigomoneda)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes elegir una moneda, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }

            $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $id_codigomoneda)));
            if(!$moneda) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes seleccionar el código de moneda válido!';
                echo json_encode($resp);
                exit();
            }

            $entidad_financiera = SunatCodigoentidadfinanciera::findFirst(array("id_entidadfinanciera = :id_entidadfinanciera:", 'bind' => array('id_entidadfinanciera' => $id_entidadfinanciera)));
            if(!$entidad_financiera) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes seleccionar una entidad financiera válida!';
                echo json_encode($resp);
                exit();
            }

            if(empty($tipo_cuenta)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes elegir un tipo de cuenta, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }
            if(empty($nombre_banco)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar el nombre del banco, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }
            if(empty($nombre_titular)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar el titular de la cuenta, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }
            if(empty($nro_cuenta)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar el número de cuenta, intenta nuevamente';
                echo json_encode($resp);
                exit();
            }

           $cuentabanco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $id_cuentabanco)));
            if(!$cuentabanco) {
                $cuentabanco = new Cuentabanco();
                $cuentabanco->id_contribuyente = $usuario->id_contribuyente;
            }

            $cuentabanco->id_codigomoneda = $id_codigomoneda;
            $cuentabanco->tipo_cuenta = $tipo_cuenta;
            $cuentabanco->nombre_banco = $nombre_banco;
            $cuentabanco->nombre_titular = $nombre_titular;
            $cuentabanco->nro_cuenta = $nro_cuenta;
            $cuentabanco->cci = $cci;
            $cuentabanco->descripcion = $descripcion;
            $cuentabanco->estado = 'activo';
            $cuentabanco->id_entidadfinanciera = $entidad_financiera->id_entidadfinanciera;

            try {
                if(!$cuentabanco->save()) {
                    $msg = '';
                    foreach ($cuentabanco->getMessages() as $message) {
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
            $resp['mensaje'] = 'La cuenta de banco se ha guardado correctamente!';
            echo json_encode($resp);
            exit();

           
        }
    }
    public function getListaCuentaAction()
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
            $lista_cuentabanco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $array_lista = array();
            foreach($lista_cuentabanco as $item){
                $opciones = '<ul class="icons-list text-center">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="edit_cuentabanco('.$item->id_cuentabanco.')" data-idcuentabanco="'.$item->id_cuentabanco.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                    <li><a onclick="eliminar_cuentabanco('.$item->id_cuentabanco.')" data-idcuentabanco="'.$item->id_cuentabanco.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                </ul>
                            </li>
                        </ul>';

                $array_lista[] = array($item->id_cuentabanco, $item->id_codigomoneda, $item->nombre_banco,  $item->nombre_titular, $item->nro_cuenta, $opciones);
            }
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();

        }
    }

    public function getDataCuentaAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_cuentabanco = !isset($datapost['idcuentabanco'])?0:intval($datapost['idcuentabanco']) + 0;

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

            $cuentabanco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $id_cuentabanco)));
            if(!$cuentabanco) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la cuenta de banco, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['cuenta'] = $cuentabanco;
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarCuentaAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_cuentabanco = !isset($datapost['idcuentabanco'])?0:intval($datapost['idcuentabanco']) + 0;

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

            $cuentabanco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $id_cuentabanco)));
            if(!$cuentabanco) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la cuenta de banco, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            $cuentabanco->estado = 'inactivo';
            if(!$cuentabanco->save()) {
                $msg = '';
                foreach ($cuentabanco->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'La cuenta  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();


        }
    }
}

?>