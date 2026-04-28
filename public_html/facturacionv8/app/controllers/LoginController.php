<?php
class LoginController extends ControllerBase {
	
    public function indexAction($id_plantilla = 0) {
        $this->setTitle('Login');
        $this->view->setTemplateAfter('vacio');
        
        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("https://www.google.com/recaptcha/api.js?i=v3", false)
        ->addJs($this->baseUri . "public/js/login.js?i=".rand());
        
        $auth = $this->getSessionUser();
        if(!is_null($auth)) {
            $idusuario = $auth['idusuario'];
            $user = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $this->redirect_user($auth);
        }

        if(!isset($_GET['accion']) || !$_GET['accion']) {
            $accion = 'login';
        } else {
            $accion = 'register';
        }

        // HTTPS + sin www (producción). En 127.0.0.1 / localhost no redirigir: php -S no tiene TLS ni ruta /facturacionv8/.
        $hostClean = str_replace('www.', '', (string) ($_SERVER['HTTP_HOST'] ?? ''));
        $isDevLocal = (bool) preg_match('/^(127\.0\.0\.1|localhost|::1)(\:\d+)?$/i', $hostClean);
        if (!$isDevLocal) {
            $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
            if (strpos($host, 'www.') !== false) {
                $dominio = str_replace('www.', '', $host);
                header("Location: https://{$dominio}/facturacionv8/login");
                exit;
            }
            $httpsOn = isset($_SERVER['HTTPS'])
                && (strtolower((string) $_SERVER['HTTPS']) === 'on' || (string) $_SERVER['HTTPS'] === '1');
            if (!$httpsOn) {
                $dominio = str_replace('www.', '', $host);
                header("Location: https://{$dominio}/facturacionv8/login");
                exit;
            }
        }
        
        //login personalizado
        $data_inicial = $this->get_parametros_iniciales();
        $patrocinador = Contribuyente::findFirst(array('id_contribuyente = :id_contribuyente:', 'bind' => array('id_contribuyente' => $data_inicial['id_patrocinador'])));
        
        if(empty($id_plantilla)) {
            $id_plantilla_login = isset($data_inicial['custom_data_style']['id_plantilla_login'])?intval($data_inicial['custom_data_style']['id_plantilla_login']):1;
        } else {
            $id_plantilla_login = $id_plantilla;
        }

        $this->view->accion = $accion;
        $this->view->lista_ubigeo = SunatCodigoubigeo::find();
        $this->view->id_plantilla_login = $id_plantilla_login;
        $this->view->patrocinador = $patrocinador;
    }
    
    public function recoverpasswordAction() {
        
        $this->setTitle('Recuperar Contraseña');
        $this->view->setTemplateAfter('vacio');
        
        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("https://www.google.com/recaptcha/api.js?i=v2", false)
        ->addJs($this->baseUri . "public/js/login.js?i=v2");
        
        $auth = $this->getSessionUser();
        if(!is_null($auth)) {
            $this->redirect_user($auth);
		}
		
		$accion = 'recover';
		if(!empty($_GET['email'])) {
			$herramientas = new HerramientasController;
			if($herramientas->verificar_validez_email($_GET['email'])) {

				$secret_key = $this->get_parametros_iniciales()['captcha_key_private'];
				$captcha = $_GET['g-recaptcha-response'];
				$ip_user = $_SERVER["REMOTE_ADDR"];

				$result_captcha = $this->url_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha&remoteip=$ip_user");
				$result_captcha = json_decode($result_captcha);
				if($result_captcha->success != true) {
					$accion = 'recover';
				} else {
					$usuario = Usuario::findFirst(array("email = :email: and estado = 'activo'", 'bind' => array('email' => $_GET['email'])));
					if(!$usuario) {
						$accion = 'recover';
					} else {
						$enviaremail = new EnviaremailController;
						$resp = $enviaremail->enviar_correo_recoverpass($usuario->idusuario);
						$accion = 'email_enviado';
					}
				}
			}
		}
	
        $this->view->accion = $accion;
    }

    public function resetpasswordAction($idusuario = '', $token = '') {
        
        $this->setTitle('Recuperar Contraseña');
        $this->view->setTemplateAfter('vacio');
        
        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
		->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
		->addJs("https://www.google.com/recaptcha/api.js?i=v2", false)
        ->addJs($this->baseUri . "public/js/login.js?i=v3");
		
		$idusuario = intval($idusuario);
		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and estado = 'activo'", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$this->view->disable();
			echo "Página no Existe!";
			exit();
		}

		$cadena_recuperacion = sha1($usuario->id_contribuyente.','.$usuario->idusuario.','.$usuario->password);
		if($cadena_recuperacion != $token) {
			$this->view->disable();
			echo "El enlace a expirado!";
			exit();
		}
		
		$this->view->token = $token;
        $this->view->usuario = $usuario;
	}
	
	public function cambiarPasswordAction() {
		$this->view->disable();
        $request = $this->request;
        if($request -> isAjax() == true){
			$datapost = $this->request->getPost();
			$token = $datapost['token'];


			$secret_key = $this->get_parametros_iniciales()['captcha_key_private'];
			$captcha = $datapost['g-recaptcha-response'];
			$ip_user = $_SERVER["REMOTE_ADDR"];

			$result_captcha = $this->url_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha&remoteip=$ip_user");
			$result_captcha = json_decode($result_captcha);
			if($result_captcha->success != true) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes Activar el Captcha';
				echo json_encode($resp);
				exit();
			}
			
			$password = trim($datapost['password']);
			$password2 = trim($datapost['password2']);

			if($password != $password2) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes confirmar tu password correctamente, asegúrate de ingresar el mismo password en ambas casillas!';
				echo json_encode($resp);
				exit();
			}

			$idusuario = intval($datapost['idusuario']);
			$usuario = Usuario::findFirst(array("idusuario = :idusuario: and estado = 'activo'", 'bind' => array('idusuario' => $idusuario)));
			if(!$usuario) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El usuario no existe';
				echo json_encode($resp);
				exit();
			}

			$cadena_recuperacion = sha1($usuario->id_contribuyente.','.$usuario->idusuario.','.$usuario->password);
			if($cadena_recuperacion != $token) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No Es posible cambiar su password porque su enlace a expirado, debe generar otro enlace.';
				echo json_encode($resp);
				exit();
			}

			$usuario->password = trim($password);
            try {
                if(!$usuario->save()) {
                    $msg = '';
                    foreach ($usuario->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Inténtelo más tarde, no hemos podido cambiar su password';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Inténtelo más tarde, no hemos podido cambiar su password. Error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $enviaremail = new EnviaremailController;
            $resp_email = $enviaremail->enviar_cambio_password($usuario->idusuario);
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Cambio Correcto!';
			$resp['mensaje'] = 'Hemos cambiado correctamente su password, hemos enviado sus datos de acceso a su correo electrónico. Para iniciar sección haga click en el siguiente enlace: <a href="/facturacionv8/login">Haz Click para Ingresar</a>';
			echo json_encode($resp);
			exit();
		}
	}

	public function sessionAction() {
		if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = Usuario::findFirst(array("email = :email: and password = :password: and estado = 'activo'", 'bind' => array('email' => $email, 'password' => $password)));
            
            $secret_key = $this->get_parametros_iniciales()['captcha_key_private'];
			$captcha = $this->request->getPost('g-recaptcha-response');
            $ip_user = $_SERVER["REMOTE_ADDR"];
            
            $result_captcha = $this->url_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha&remoteip=$ip_user");
			$result_captcha = json_decode($result_captcha);
			if($result_captcha->success != true) {
				$this->flashSession->error('Debes Activar el Recaptcha');
                return $this->dispatcher->forward(array(
                    "controller" => "login",
                    "action" => "index"
                ));
            }
            
            if (!$user) {
                $this->flashSession->error('Error Email/Password');
                return $this->dispatcher->forward(array(
                    "controller" => "login",
                    "action" => "index"
                ));
            }
            
            $this->crear_nueva_session($user);
        }
    }

    public function crear_nueva_session($user, $redirect = true) {
        $rol = RolUsuario::findFirst(array("id_rol = :idrol:", 'bind' => array('idrol' => $user->id_rol)));
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $user->id_contribuyente)));
        
        $usuario = array(
            'idusuario'         => $user->idusuario,
            'codigo'            => $user->codigo,
            'nombre'            => $user->nombre,
            'apellido'          => $user->apellido,
            'celular'           => $user->celular,
            'telefono'          => $user->telefono,
            'id_rol'            => $user->id_rol,
            'rol'               => $rol->nombre,
            'rol_alias'         => $rol->alias,
            'email'             => $user->email,
            'url_image'         => $user->url_image,
            'ruc'               => $contribuyente->ruc,
            'razon_social'      => $contribuyente->razon_social
        );
        
        $this->registrar_sesion($usuario, $redirect);
    }

    public function get_idpatrocinador() {
        $data_patrocinador = $this->get_parametros_iniciales();
        if($data_patrocinador['id_contribuyente'] == 1) {
            if (!$this->cookies->has('registro_negocio')) {
                return 2;
            }
        } else {
            //si la data del patrocinador indica que es un contribuyente con id diferente a 1, entonces lo más
            //probable es que sea un reseller autorizado.
            //ya que por ahora solo los reseller tienen dominio personalizado
            //return $data_patrocinador['id_contribuyente'];
            return $data_patrocinador['id_contribuyente'];
        }

        return 1;
    }

    public function registrarUsuarioAction() {
        $this->view->disable();
        $request = $this->request;
        if($request -> isAjax() == true){
            $herramientas = new HerramientasController;

            $datapost = $this->request->getPost();
            $email = !isset($datapost['email_login'])?'':$datapost['email_login'];
            $password = !isset($datapost['password_login'])?'':$datapost['password_login'];
            $ruc = $herramientas->extraer_numeros(!isset($datapost['ruc'])?'':$datapost['ruc']);
            $nombre_usuario = !isset($datapost['nombre_usuario'])?'':$datapost['nombre_usuario'];
            $telefono = !isset($datapost['telefono'])?'':$datapost['telefono'];
            $codigo_ubigeo = !isset($datapost['ubigeo'])?'':$datapost['ubigeo'];
            $idpatrocinador = !isset($datapost['idpatrocinador'])?1:$datapost['idpatrocinador'];

            if(!isset($datapost['g-recaptcha-response']) || $datapost['g-recaptcha-response'] == '') {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Debes verificar que no eres un robot!';
                echo json_encode($msj);
                exit();
            }

            $secret_key = $this->get_parametros_iniciales()['captcha_key_private'];
            $captcha = $datapost['g-recaptcha-response'];
            $ip_user = $_SERVER["REMOTE_ADDR"];

            $result_captcha = $this->url_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha&remoteip=$ip_user");
            $result_captcha = json_decode($result_captcha);
            if($result_captcha->success != true) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Debes demostrar que no eres un robot!';
                echo json_encode($msj);
                exit();
            }

            //$idpatrocinador = 2;
            $idpatrocinador = $this->get_idpatrocinador();

            $contribuyente = Contribuyente::findFirst(array("ruc = :ruc:", 'bind' => array('ruc' => $ruc)));
            if($contribuyente) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Ya existe una empresa registrada con el presente ruc!';
                echo json_encode($msj);
                exit();
            }
            
            if(!$herramientas->verificar_validez_email($email)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Email';
                $resp['mensaje'] = 'EL email ingresado no tiene el formato correcto!';
                echo json_encode($resp);
                exit();
            }

            $verify_usuario = Usuario::findFirst(array("email = :email:", 'bind' => array('email' => $email)));
            if($verify_usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Email ingresado, ya está ocupado por otro usuario, por favor ingresa un email diferente!!';
                echo json_encode($resp);
                exit();
            }

            if(empty($password) || strlen($password) < 6) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Password';
                $resp['mensaje'] = 'El password ingresado debe tener6 o más caracteres!';
                echo json_encode($resp);
                exit();
            }

            $resp_busqueda_ruc = $herramientas->get_data_api_busquedas('ruc', $ruc);
            if($resp_busqueda_ruc['respuesta'] == 'error') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El número de R.U.C. no es válido!';
                $resp['busqueda']   = $resp_busqueda_ruc;
                echo json_encode($resp);
                exit();
            }

            $razon_social = !isset($resp_busqueda_ruc['data']->razon_social)?'Razón Social Empresa':$resp_busqueda_ruc['data']->razon_social;
            if($razon_social == '') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos la Razón Social!';
                echo json_encode($resp);
                exit();
            }

            if(empty($nombre_usuario)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Por favor ingresa tu nombre para saber a quien dirigirnos :)!';
                echo json_encode($resp);
                exit();
            }

            if(empty($telefono)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar un número de celular válido!';
                echo json_encode($resp);
                exit();
            }

            $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $codigo_ubigeo)));
            if(!$sql_ubigeo){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Por favor, selecciona una ubicación válida!';
                echo json_encode($msj);
                exit();
            }

            $this->db->begin();
            $contribuyente = new Contribuyente();
            $contribuyente->ruc = $ruc;
            $contribuyente->razon_social = $razon_social;
            $contribuyente->fecha_registro = date('Y-m-d H:i:s');
            $contribuyente->id_patrocinador = $idpatrocinador;
            $contribuyente->ruta_xml_prueba = 'prueba'; //nombre del directorio donde se guardarán los xmls de pruebas
            $contribuyente->ruta_xml_produccion = 'produccion'; //nombre del directorio donde se guardarán los xmls de producción
            $contribuyente->email = $email;
            $contribuyente->telefono = $telefono;
            $contribuyente->codigo_ubigeo = $codigo_ubigeo;
            $contribuyente->tipo_envio_sunat = 'prueba';
            $contribuyente->modalidad_envio_sunat ='no_enviar';
            $contribuyente->direccion_fiscal = '-';
            
            $contribuyente->correlativo_rd_boletas = 1;
            $contribuyente->correlativo_comunicacion_bajas = 1;
            $contribuyente->sunat_idregimen = 1;
            $contribuyente->restriccion_stock = 'no';
            $contribuyente->multi_almacen = 'si';
            $contribuyente->num_decimales = 2;
            $contribuyente->prodduplicados_detalle = 'no';
            $contribuyente->precio_venta_minimo = 'no';

            $contribuyente->regimen_retencion = 0.03;

            try {
                if(!$contribuyente->save()) {
                    $msg = '';
                    foreach ($contribuyente->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $this->db->rollback();
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
                $resp['mensaje'] = 'Inténtelo más tarde, no hemos podido registrar su empresa. Error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            //Se pre-registra una primera sucursal
            $new_sucursal = new Sucursal();
            $new_sucursal->id_contribuyente = $contribuyente->id_contribuyente;
            $new_sucursal->codigo = '0000';
            $new_sucursal->nombre = $razon_social;
            $new_sucursal->id_ubigeo = $codigo_ubigeo;
            $new_sucursal->telefono = $telefono;
            $new_sucursal->email = $email;
            $new_sucursal->direccion = '-';
            $new_sucursal->fecha_registro = date('Y-m-d H:i:s');
            $new_sucursal->estado = 'activo';

            try {
                if(!$new_sucursal->save()) {
                    $msg = '';
                    foreach ($new_sucursal->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $this->db->rollback();
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
                $resp['mensaje'] = 'Inténtelo más tarde, no hemos podido registrar su empresa. Error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            //solamente se crea un nuevo usuario cuando se registra un nuevo contribuyente
            $new_usuario = new Usuario();
            $new_usuario->email = $email;
            $new_usuario->password = $password;
            $new_usuario->telefono = $telefono;
            $new_usuario->celular = $telefono;
            $new_usuario->id_rol = 3;
            $new_usuario->nombre = $nombre_usuario;
            $new_usuario->apellido = "Administrador";
            $new_usuario->id_contribuyente = $contribuyente->id_contribuyente;
            $new_usuario->codigo = $herramientas->gettoken(14);
            $new_usuario->fecha_registro = date('Y-m-d H:i:s');
            $new_usuario->estado = 'activo';

            try {
                if(!$new_usuario->save()) {
                    $msg = '';
                    foreach ($new_usuario->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $this->db->rollback();
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
                $resp['mensaje'] = 'Inténtelo más tarde, no hemos podido registrar su empresa. Error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $this->db->commit();

            $rol = RolUsuario::findFirst(array("id_rol = :idrol:", 'bind' => array('idrol' => $new_usuario->id_rol)));
            $usuario = array(
                'idusuario'         => $new_usuario->idusuario,
                'codigo'            => $new_usuario->codigo,
                'nombre'            => $new_usuario->nombre,
                'apellido'          => $new_usuario->apellido,
                'celular'           => $new_usuario->celular,
                'telefono'          => $new_usuario->telefono,
                'id_rol'            => $new_usuario->id_rol,
                'rol'               => $rol->nombre,
                'rol_alias'         => $rol->alias,
                'email'             => $new_usuario->email,
                'ruc'               => $contribuyente->ruc,
                'razon_social'      => $razon_social
            );

            $auth = $this->session->set('authv8', $usuario);

            $enviaremail = new EnviaremailController;
            $resp_email = $enviaremail->enviar_datos_de_acceso($new_usuario->idusuario);
            $resp_notificacion = $enviaremail->patrocinador_new_user($contribuyente, $new_usuario);

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Registro Correcto';
            $resp['mensaje'] = 'La Cuenta se ha creado correctamente, haga click en aceptar para ingresar automáticamente al sistema!, <strong class="text-success">También hemos enviado tus datos de acceso a tu correo electrónico</strong>';
            echo json_encode($resp);
            exit();
        }
    }

    public function url_get_contents ($Url) {
        if (!function_exists('curl_init')){ 
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    public function logoutAction() {
        if ($this->cookies->has($this->token_cookie)){
            $rememberMe = $this->cookies->get($this->token_cookie);
            $rememberMe->delete();
        }
        $this->session->remove('authv8');
        $this->session->destroy();
        return $this->response->redirect('login');
    }

    public function inicioSesionRemotoAction() {
        if ($this->cookies->has($this->token_cookie)){
            $rememberMe = $this->cookies->get($this->token_cookie);
            $rememberMe->delete();
        }
        $this->session->remove('authv8');
        $this->session->destroy();
        $cadena_encriptada = $_GET['user'];
        return $this->response->redirect('login/login_remoto?user='.$cadena_encriptada);
    }
    
    public function loginRemotoAction() {
        $this->view->disable();
        $cadena_encriptada = urldecode($_GET['user']);

        $herramientas = new HerramientasController;
		$cadena_decifrada = $herramientas->desencriptar($cadena_encriptada);

        $array_data = explode('||', $cadena_decifrada);
		$iduser = !isset($array_data[1])?'':$array_data[1];

        $idusuario = intval($iduser);
        $user = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if (!$user) {
            echo "no se encuentra el usuario";
            exit();
        }
        
        $this->crear_nueva_session($user);
    }
}
?>