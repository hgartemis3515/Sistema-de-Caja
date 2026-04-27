<?php
class ProfileController extends ControllerBase
{
	public function indexAction($tab = '') {
		$this->setTitle('Perfil');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
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
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/subir_imagen.js?i=v3")
        ->addJs($this->baseUri . "public/js/profile.js?i=v33");
        
		$auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst("idusuario = ".$idusuario)->toArray();
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
            echo json_encode($resp);
            exit();
        }
        
        
        if($tab != 'perfil' && $tab != 'seguridad' && $tab != 'facturacion') {
            $tab = 'perfil';
        }
        $this->view->tab = $tab;
        $this->view->usuario = $usuario; 
        
    }
    
    public function saveAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            
            $codigo = $datapost['codigo'];
            $nombre = $datapost['nombre'];
            $apellido = $datapost['apellido'];
            $celular = $datapost['celular'];
            $telefono = $datapost['telefono'];
            $url_image = $datapost['imagen_usuario'];
           
            //Validación de sesion
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente == 2043) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Esta es una cuenta demo para todos nuestros amigos, Usted no debería cambiar los datos de esta cuenta!';
                echo json_encode($resp);
                exit();
            }
            
            //Validacion de cambios
            if(empty($codigo)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! No se permiten campos vacíos, por favor, ingrese o genere un código';
                echo json_encode($resp);
                exit();
            }
            if(empty($nombre)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Nombre';
                $resp['mensaje'] = 'Lo sentimos! No se permiten campos vacíos, por favor, ingrese un nombre';
                echo json_encode($resp);
                exit();
            }
            if(empty($apellido)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Apellido';
                $resp['mensaje'] = 'Lo sentimos! No se permiten campos vacíos, por favor, ingrese un apellido';
                echo json_encode($resp);
                exit();
            }
            if(empty($telefono)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Teléfono';
                $resp['mensaje'] = 'Lo sentimos! No se permiten campos vacíos, por favor, ingrese un número de teléfono';
                echo json_encode($resp);
                exit();
            }
            if(empty($celular)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Celular';
                $resp['mensaje'] = 'Lo sentimos! No se permiten campos vacíos, por favor, ingrese un número de celular';
                echo json_encode($resp);
                exit();
            }
            if(empty($url_image)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Imágen';
                $resp['mensaje'] = 'Debes agregar tu imágen de perfil!';
                echo json_encode($resp);
                exit();
            }
            $user = Usuario::findFirst(array('idusuario = :idusuario:', 'bind' =>  array('idusuario' => $idusuario)));
            $user->codigo = $codigo;
            $user->nombre = $nombre;
            $user->apellido = $apellido;
            $user->telefono = $telefono;
            $user->celular = $celular;
            $user->url_image = $url_image;

            try {
                if(!$user->save()) {
                    $msg = '';
                    foreach ($user->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se han podido guardar los datos';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se han podido guardar los datos '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Enhorabuena!';
            $resp['mensaje'] = 'Todos los datos se han guardado exitosamente!';
            echo json_encode($resp);
            exit();
            
        }
    }
    public function saveEmailAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $email = $datapost['email'];
            
             if(empty($email)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! Es obligatorio que ingreses un correo eléctronico, puesto que con él es que accederás a nuestro sistema';
                echo json_encode($resp);
                exit();
            }

            //Verificando que si el email ingresado ya existe
           $sql_email = Usuario::findFirst(array('email = :email: and idusuario <> :idusuario:', 'bind' =>  array('email' => $email, 'idusuario' => $idusuario)));
            if($sql_email) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Email';
                $resp['mensaje'] = 'Lo sentimos! El email ingresado ya existe en nuestro sistema, por seguridad, te invitamos a ingresar otro!';
                echo json_encode($resp);
                exit();
            }

            $user = Usuario::findFirst(array('idusuario = :idusuario: ', 'bind' =>  array('idusuario' => $idusuario)));
            $user->email = $email;

            try {
                if(!$user->save()) {
                    $msg = '';
                    foreach ($user->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se han podido guardar los datos';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se han podido guardar los datos '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Enhorabuena!';
            $resp['mensaje'] = 'El nuevo email  se ha guardado exitosamente! Recuerda que para la próxima sesión debes ingresar con él.';
            echo json_encode($resp);
            exit();
        }
    }
    public function savePasswordAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];

            $password = $datapost['password'];
            $new_password = $datapost['new_password'];
            
             if(empty($password)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! Es obligatorio que ingreses la anterior contraseña';
                echo json_encode($resp);
                exit();
            }
            if(empty($new_password)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! Es obligatorio que ingreses una contraseña, puesto que con ella es que accederás a nuestro sistema';
                echo json_encode($resp);
                exit();
            }
            //Verificando que si la contraseña es correcta
            $sql_password = Usuario::findFirst(array('idusuario = :idusuario: and password = :password:', 'bind' =>  array('idusuario' => $idusuario, 'password' => $password)));
            if(!$sql_password) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Contraseña';
                $resp['mensaje'] = 'Lo sentimos! La contraseña ingresada es incorrecta, intenta nuevamente!';
                echo json_encode($resp);
                exit();
            }
            $user = Usuario::findFirst(array('idusuario = :idusuario: ', 'bind' =>  array('idusuario' => $idusuario)));
            $user->password = $new_password;

            try {
                if(!$user->save()) {
                    $msg = '';
                    foreach ($user->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se han podido guardar los datos';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se han podido guardar los datos '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $enviaremail = new EnviaremailController;
            $resp_email = $enviaremail->enviar_cambio_password($idusuario);

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Enhorabuena!';
            $resp['mensaje'] = 'La nueva contraseña se ha guardado exitosamente! Recuerda que para la próxima sesión debes ingresar con él.';
            echo json_encode($resp);
            exit();
        }
        
    }

    public function getListaSuscripcionAction(){
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
           
            $lista_suscripcion = Suscripcion::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'",  'bind' => array('id_contribuyente' => $usuario->id_contribuyente), "order" => "fecha_fin DESC"));
            $array_lista = array();
            $array_fechafin = array();
            foreach($lista_suscripcion as $item){
                $periodo =  'Desde  '.date("d-m-Y", strtotime($item->fecha_inicio)).' al '.date("d-m-Y", strtotime($item->fecha_fin));
                $plan_reseller = Planreseller::findFirst(array("id_planreseller = :id_planreseller:", 'bind' => array('id_planreseller' => $item->id_planreseller)));
                $array_lista[] = array($item->id_suscripcion, $plan_reseller->nombre,  $periodo, $item->limite_mes_doc, $item->total);
                $array_fechafin[] =  $item->fecha_fin;
            }
            //Notificación
            $fecha_actual = date('d-m-Y');
            $first = current($array_fechafin); 
            $fin_plan_5 = strtotime ('-5 day' , strtotime ($first));
            $fin_plan_5 = date ('d-m-Y',$fin_plan_5);

            if($fecha_actual == $fin_plan_5){
                $alert_plan = '<div class="alert bg-danger alert-styled-left" style="max-width: 1100px; margin: 0 auto;">
                <button type="button" class="close" data-dismiss="alert"><span>×</span>
                <span class="sr-only">Close</span></button>
                <span class="text-semibold">Alerta! Tu plan finaliza dentro de 5 días, exactamente el día: '.date("d-m-Y", strtotime($first)).'</span>.</div>';
              
            } else {
                $alert_plan = '';
            }
           
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            $resp['fin_plan'] = $alert_plan;
            echo json_encode($resp);
            exit();

        }
    }
}
?>