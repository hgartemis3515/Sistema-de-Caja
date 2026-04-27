<?php

class PersonalizaciondesistemaController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Personalizar Sistema');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        //->addCss($this->baseUri . "public/extras/deby_radiobuttons/css/deby-styles.css")
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
        ->addJs("https://unpkg.com/default-passive-events", false)
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
        
        ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")

        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/bootbox.min.js")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/sweet_alert.min.js")

        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/pnotify.min.js")

        ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
        
        ->addJs($this->baseUri . "public/js/general.js?j=".rand())
        ->addJs($this->baseUri . "public/js/personalizaciondesistema/update_image.js?i=".rand())
        ->addJs($this->baseUri . "public/js/personalizaciondesistema/personalizaciondesistema.js?i=".rand())
        ;

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));

        $id_contribuyente = $usuario->id_contribuyente;
        
        $array_validos = array(1, 2, 3, 5);
        if(!in_array($usuario->id_rol, $array_validos)) {
            echo "Usted no tiene permisos para editar esta área!!";
            exit();
        }
       
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        
        $lista_disenios_login = Disenopersonalizadosistema::find(array("(modulo = 'login' and estado = 'activo' and tipo = 'normal') or (modulo = 'login' and estado = 'activo' and tipo = 'personalizada' and id_contribuyente = $id_contribuyente)"));
        $lista_disenios_registro = Disenopersonalizadosistema::find(array("(modulo = 'registro' and estado = 'activo' and tipo = 'normal') or (modulo = 'registro' and estado = 'activo' and tipo = 'personalizada' and id_contribuyente = $id_contribuyente)"));

        $contribuyente_opciones = $this->get_contribuyente_opciones($id_contribuyente);
        
        $data = array();
        $data['id_plantilla_login'] = isset($contribuyente_opciones['id_plantilla_login'])?intval($contribuyente_opciones['id_plantilla_login']) + 0:0;
        $data['id_plantilla_registro'] = isset($contribuyente_opciones['id_plantilla_registro'])?intval($contribuyente_opciones['id_plantilla_registro']) + 0:0;
        $data['img_background_login'] = isset($contribuyente_opciones['img_background_login'])?$contribuyente_opciones['img_background_login']:'https://arpsystem.com.pe/facturacionv8/img/38.jpg';
        $data['img_background_register'] = isset($contribuyente_opciones['img_background_register'])?$contribuyente_opciones['img_background_register']:'https://arpsystem.com.pe/facturacionv8/img/hero-6.jpg';

        //INICIO DE VALIDACIONES PARA PODER EXTRAER EL VALOR CORRECTO PARA LOS COLORES
        //Es muy importante que tenga valores correctos porque puede ocasionar un problema muy grande en el sistema
        $color_base_sistema = isset($contribuyente_opciones['color_base_sistema'])?$contribuyente_opciones['color_base_sistema']:'{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}';
        $color_base_sistema = @json_decode($color_base_sistema);
        if($color_base_sistema === null) {
            $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
        }

        if(!isset($color_base_sistema->tipo_color)) {
            $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
        }

        if($color_base_sistema->tipo_color != 'color_degradado' && $color_base_sistema->tipo_color != 'color_solido') {
            $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
        } else {
            if($color_base_sistema->tipo_color == 'color_degradado') {
                if(!isset($color_base_sistema->color_1) || !isset($color_base_sistema->color_2)) {
                    $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                } else {
                    if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_base_sistema->color_1) || !preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_base_sistema->color_2)) {
                        $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                    }
                }
            } else {
                if(!isset($color_base_sistema->color_solido)) {
                    $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                } else {
                    if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_base_sistema->color_solido)) {
                        $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                    }
                }
            }
        }
        //FIN validación para colores base de sistema

        $data['color_base_sistema'] = $color_base_sistema;
        
        $this->view->data = $data;
        $this->view->lista_disenios_registro = $lista_disenios_registro;
        $this->view->lista_disenios_login = $lista_disenios_login;
        $this->view->contribuyente = $contribuyente;
    }

    //testeado
    public function guardarImagenServidorAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
			$imgbase64 = $datapost['dataimage'];
			$tipo = $datapost['imagetipo'];
			$image_nombre = 'imgprod-'.uniqid().'-'.md5(time()).'.png';
			
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

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
				echo json_encode($resp);
				exit();
			}
            
            $numero_ruc = $contribuyente->ruc;
			$anio = date("Y");
			$mes = date("m");
			$destino = $numero_ruc.'/'.$anio.'/'.$mes;
			$destino = $this->ruta_base_files."/../public_html/facturacionv8/public/files/upload_user/img_personalizacion/".$numero_ruc.'/'.$anio.'/'.$mes.'/';
			if (!file_exists($destino)) {
				mkdir($destino, 0777, true);
			}
			
            $file = $destino.$image_nombre;
            $herramientas = new HerramientasController;
			$success = $herramientas->subirimagen_servidor($imgbase64, $file);
            if($success == true) {
                $resp['respuesta'] = 'ok';
				$resp['urlimagen'] = '/facturacionv8/herramientas/verimagepersonalizacion/'.$numero_ruc.'/'.$anio.'/'.$mes.'/'.$image_nombre;
                echo json_encode($resp);
                exit();
            }
            
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = "Error al Subir Imágen";
            echo json_encode($resp);
            exit();
        }
    }
    
    //testeado
    public function saveTemplateLoginAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }

            $id_plantilla_login = intval($datapost['id_plantilla_login']) + 0;

            $resp_valor = $this->set_contribuyente_opciones($usuario->id_contribuyente, 'id_plantilla_login', $id_plantilla_login);
            if($resp_valor['respuesta'] == 'error') {
                echo json_encode($resp_valor);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Operación Exitosa';
            $resp['mensaje'] = 'La Nueva Plantilla para Ingresar al Sistema se ha guardado';
            echo json_encode($resp);
            exit();
        }
    }

    //testeado
    public function saveTemplateRegistroAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
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

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }

            $id_plantilla_registro = intval($datapost['id_plantilla_registro']) + 0;

            $resp_valor = $this->set_contribuyente_opciones($usuario->id_contribuyente, 'id_plantilla_registro', $id_plantilla_registro);
            if($resp_valor['respuesta'] == 'error') {
                echo json_encode($resp_valor);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Operación Exitosa';
            $resp['mensaje'] = 'La Nueva Plantilla de Registro de Usuarios se ha guardado';
            echo json_encode($resp);
            exit();
        }
    }

    //testeado
    public function saveBackgroundLoginAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
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

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }

            $img_background_login = trim($datapost['img_background_login']);

            if(empty($img_background_login)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes cargar una imágen o ingresar la ruta de la imágen';
                echo json_encode($resp);
                exit();
            }

            $resp_valor = $this->set_contribuyente_opciones($usuario->id_contribuyente, 'img_background_login', $img_background_login);
            if($resp_valor['respuesta'] == 'error') {
                echo json_encode($resp_valor);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Operación Exitosa';
            $resp['mensaje'] = 'La imágen para el background de la página de login se ha guardado.';
            echo json_encode($resp);
            exit();
        }
    }

    //testeado
    public function saveBackgroundRegistroAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }

            $img_background_register = trim($datapost['img_background_register']);

            if(empty($img_background_register)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes cargar una imágen o ingresar la ruta de la imágen';
                echo json_encode($resp);
                exit();
            }

            $resp_valor = $this->set_contribuyente_opciones($usuario->id_contribuyente, 'img_background_register', $img_background_register);
            if($resp_valor['respuesta'] == 'error') {
                echo json_encode($resp_valor);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Operación Exitosa';
            $resp['mensaje'] = 'La imágen para el background de la página de registro se ha guardado.';
            echo json_encode($resp);
            exit();
        }
    }

    //testeado
    public function guardarDominioLogosAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
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

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }
            
            $dominio_personalizado = $datapost['dominio_personalizado'];
            $img_logo_461 = isset($datapost['img_logo_461'])?$datapost['img_logo_461']:'';
            $img_logo_291 = isset($datapost['img_logo_291'])?$datapost['img_logo_291']:'';
            $img_logo_56 = isset($datapost['img_logo_56'])?$datapost['img_logo_56']:'';

            if(empty($dominio_personalizado)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el nombre de tu dominio.';
                echo json_encode($resp);
                exit();
            }

            if(empty($img_logo_461)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el primer logo.';
                echo json_encode($resp);
                exit();
            }

            if(empty($img_logo_291)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el segundo logo.';
                echo json_encode($resp);
                exit();
            }

            if(empty($img_logo_56)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el tercer logo.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $contribuyente->dominio = $dominio_personalizado;
            $contribuyente->logo_461 = $img_logo_461;
            $contribuyente->logo_291 = $img_logo_291;
            $contribuyente->logo_56 = $img_logo_56;

            try {
                if(!$contribuyente->save()) {
                    $msg = '';
                    foreach ($contribuyente->getMessages() as $message) {
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
            $resp['mensaje'] = '¡El nuevo dominio y logos han sido guardados correctamente!';
            echo json_encode($resp);
            exit();
        }
    }

    //testeado
    public function guardarColorBaseSistemaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
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

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }

            $tipo_color = isset($datapost['tipo_color'])?$datapost['tipo_color']:'';
            $color_fondo_base_1_rgb = isset($datapost['color_fondo_base_1_rgb'])?$datapost['color_fondo_base_1_rgb']:'';
            $color_fondo_base_2_rgb = isset($datapost['color_fondo_base_2_rgb'])?$datapost['color_fondo_base_2_rgb']:'';
            $color_fondo_base_solido_rgb = isset($datapost['color_fondo_base_solido_rgb'])?$datapost['color_fondo_base_solido_rgb']:'';

            if($tipo_color != 'color_solido' && $tipo_color != 'color_degradado') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Primero debes seleccionar el tipo de color (degradado o sólido)';
                echo json_encode($resp);
                exit();
            }

            if($tipo_color == 'color_solido') {
                if(empty($color_fondo_base_solido_rgb)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Valor no válido para el color sólido';
                    echo json_encode($resp);
                    exit();
                }

                if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_fondo_base_solido_rgb)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El color ingresado debe ser en código RGB (ejem. #295a7f)';
                    echo json_encode($msj);
                    exit();
                }

                $color_base_sistema = array(
                    'tipo_color'    => $tipo_color,
                    'color_solido'  => $color_fondo_base_solido_rgb
                );

            } else if($tipo_color == 'color_degradado') {
                if(empty($color_fondo_base_1_rgb)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Valor no válido para el color degradado 1';
                    echo json_encode($resp);
                    exit();
                }

                if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_fondo_base_1_rgb)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El color ingresado debe ser en código RGB (ejem. #295a7f)';
                    echo json_encode($msj);
                    exit();
                }

                if(empty($color_fondo_base_2_rgb)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Valor no válido para el color degradado 2';
                    echo json_encode($resp);
                    exit();
                }

                if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_fondo_base_2_rgb)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El color ingresado debe ser en código RGB (ejem. #295a7f)';
                    echo json_encode($msj);
                    exit();
                }

                $color_base_sistema = array(
                    'tipo_color'    => $tipo_color,
                    'color_1'       => $color_fondo_base_1_rgb,
                    'color_2'       => $color_fondo_base_2_rgb
                );
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Primero debes seleccionar el tipo de color (degradado o sólido)..';
                echo json_encode($resp);
                exit();
            }
            
            $resp_valor = $this->set_contribuyente_opciones($usuario->id_contribuyente, 'color_base_sistema', json_encode($color_base_sistema));
            if($resp_valor['respuesta'] == 'error') {
                echo json_encode($resp_valor);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Operación Exitosa';
            $resp['mensaje'] = 'Los nuevos colores han sido guardados correctamente';
            echo json_encode($resp);
            exit();
        }
    }

    //testeado
    public function reiniciarColoresBaseAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes iniciar sesión para poder continuar.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Permisos Infuficientes';
                echo json_encode($resp);
                exit();
            }
            
            $resp_valor = $this->set_contribuyente_opciones($usuario->id_contribuyente, 'color_base_sistema', '{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
            if($resp_valor['respuesta'] == 'error') {
                echo json_encode($resp_valor);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Operación Exitosa';
            $resp['mensaje'] = 'Los Colores Fueron Reiniciados!';
            echo json_encode($resp);
            exit();
        }
    }
}
?>