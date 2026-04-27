<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/firebasejwt/vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class ApiappController extends ControllerBase
{
	
	public function indexAction() {
		$this->view->disable();
	}

	private function setHttpResponseHeaders() {
		$this->response->setHeader('Access-Control-Allow-Origin', '*');
		$this->response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
		$this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	
		if ($this->request->getMethod() == 'OPTIONS') {
			$this->response->setStatusCode(200, 'OK');
			return $this->response;
		}
	}
    
    public function crear_token($usuario) {
		$time = time();
		$time_valid_token = (60*60*24*7); //Tiempo Expiración: 60 segundos x 60 minutos x 24 horas * 7 días (válido por 7 días)
		$time_expire = $time + $time_valid_token;

		$data_token = array(
			'iat'	=> $time,
			'exp'	=> $time_expire, //Tiempo Expiración: 60 segundos x 60 minutos x 24 horas * 7 días
			'data'	=> array(
				'idusuario'			=> (int)$usuario->idusuario,
				'id_contribuyente' 	=> (int)$usuario->id_contribuyente,
				'email'				=> $usuario->email
			)
		);

		$resp['token'] = JWT::encode($data_token, $this->token_cookie, 'HS512');
		$resp['expire_time'] = $time_expire;
		$resp['expirein'] = $time_valid_token;
		$resp['expire_at'] = date("Y-m-d H:i:s", $time_expire);

		return $resp;
	}

	public function validate_token($id_contribuyente, $idusuario, $token) {
		try {
			$usertoken = UserToken::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario: and token = :token:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idusuario' => $idusuario, 'token' => $token)));
			if(!$usertoken) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['code'] = 'error_token';
				$resp['mensaje'] = 'Usuario No Existe';
				return $resp;
			}

			//$expires_at_time = $usertoken->expire_time;
			$expires_at_time = strtotime($usertoken->expire_at);
			$time_now = time();

			if($time_now > $expires_at_time) {
				//token expirado
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['code'] = 'expired_token';
				$resp['mensaje'] = 'Token Expirado';
				return $resp;
			}

			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Ok';
			$resp['code'] = 'valid_token';
			$resp['mensaje'] = 'El Token es Válido';
			return $resp;

		} catch (Exception $e) {
            $this->saveLogger($e);
			
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['code'] = 'error_token';
			$resp['mensaje'] = 'Error en el Token';
			return $resp;
		}
	}

	public function getVersionAction() {
		$this->setHttpResponseHeaders();
		$this->view->disable();

		$major = 1;
		$minor = 2;
		$patch = 3;
		$tipo = "beta";

		// Construye la cadena de versión basada en los componentes individuales
		$versionString = "{$major}.{$minor}.{$patch}-{$tipo}";

		$versionData = array(
			"major" 	=> $major,
			"minor" 	=> $minor,
			"patch" 	=> $patch,
			"tipo" 		=> $tipo,
			"version" 	=> $versionString
		);
		
		return $this->response->setJsonContent($versionData);
	}
	
	public function getUserInfoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
            return $this->response->setJsonContent($resp_validacion);
		}
		
		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Usuario no Existe';
			return $this->response->setJsonContent($resp);
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Usuario no Existe';
			return $this->response->setJsonContent($resp);
		}

		$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));

		$dominio_patrocinador = 'facturalaya.com';
		if(!empty($patrocinador->dominio)) {
			$dominio_patrocinador = $patrocinador->dominio;
		}

		$resp_user_data = $this->get_userdata($usuario);

		$this->response->setStatusCode(200, 'OK');
		$resp['respuesta'] = 'ok';
		$resp['userdata'] = $resp_user_data['userdata'];
		$resp['patrocinador'] = $resp_user_data['patrocinador'];
		return $this->response->setJsonContent($resp);
	}

    public function refreshTokenAction() {
        $this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

        $usertoken = UserToken::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario: and token = :token:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idusuario' => $idusuario, 'token' => $token)));

        if(!$usertoken) {
            $this->response->setStatusCode(401, 'Unauthorized');
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
            $resp['mensaje'] = 'No Existe El Token';
            return $this->response->setJsonContent($resp);
        }

        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $this->response->setStatusCode(401, 'Unauthorized');
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No Existe El Usuario';
            return $this->response->setJsonContent($resp);
        }

        $resp_token = $this->crear_token($usuario);
        
        $usertoken->id_contribuyente = $usuario->id_contribuyente;
        $usertoken->idusuario = $usuario->idusuario;
        $usertoken->token = $resp_token['token'];
        $usertoken->expire_time = $resp_token['expire_time'];
		$usertoken->expire_at = $resp_token['expire_at'];

        try {
			if(!$usertoken->save()) {
				$msg = '';
				foreach ($usertoken->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}
                $this->response->setStatusCode(401, 'Unauthorized');
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $msg;
				return $this->response->setJsonContent($resp);
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
            $this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $this->response->setJsonContent($resp);
		}

        $this->response->setStatusCode(200, 'OK');
		$resp['respuesta'] = 'ok';
		$resp['token'] = $resp_token['token'];
        $resp['expirein'] = $resp_token['expirein'];
		$resp['expireat'] = $resp_token['expire_at'];
        $resp['idusuario'] = (int)$usuario->idusuario;
		$resp['id_contribuyente'] = (int)$usuario->id_contribuyente;
        $resp['email'] = $usuario->email;
		$resp_user_data = $this->get_userdata($usuario);
		$resp['userdata'] = $resp_user_data['userdata'];
		return $this->response->setJsonContent($resp);
    }

    public function loginAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se recibieron datos!';
			return $this->response->setJsonContent($resp);
		}

		$email = $data['email'] ?? '';
		$password = $data['password'] ?? '';
		$device = $data['device'] ?? null;
        
		$usuario = Usuario::findFirst(array("email = :email: and password = :password: and estado = 'activo'", 'bind' => array('email' => $email, 'password' => $password)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No Existe el Usuario';
			return $this->response->setJsonContent($resp);
		}
		
		$resp_token = $this->crear_token($usuario);

        $usertoken = new UserToken();
        $usertoken->id_contribuyente = $usuario->id_contribuyente;
        $usertoken->idusuario = $usuario->idusuario;
        $usertoken->token = $resp_token['token'];
        $usertoken->expire_time = $resp_token['expire_time'];
		$usertoken->expire_at = $resp_token['expire_at'];
		$usertoken->device = $device;

		try {
			if(!$usertoken->save()) {
				$msg = '';
				foreach ($usertoken->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}
				
				$this->response->setStatusCode(401, 'Unauthorized');
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $msg;
				return $this->response->setJsonContent($resp);
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $this->response->setJsonContent($resp);
		}

		$this->response->setStatusCode(200, 'OK');
		$resp['respuesta'] = 'ok';
		$resp['token'] = $resp_token['token'];
        $resp['expirein'] = $resp_token['expirein'];
		$resp['expireat'] = $resp_token['expire_at'];
        $resp['idusuario'] = (int)$usuario->idusuario;
		$resp['id_contribuyente'] = (int)$usuario->id_contribuyente;
        $resp['email'] = $usuario->email;
		$resp_user_data = $this->get_userdata($usuario);
		$resp['userdata'] = $resp_user_data['userdata'];
		
		return $this->response->setJsonContent($resp);
	}

	public function get_userdata($usuario) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$rol = RolUsuario::findFirst(array("id_rol = :idrol:", 'bind' => array('idrol' => $usuario->id_rol)));

		$id_patrocinador = 1;

		$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_patrocinador)));

		$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

		$logo_img_461 = $patrocinador->logo_461;
		$logo_img_291 = $patrocinador->logo_291;
		$logo_img_56 = $patrocinador->logo_56;
		$url_domain = $patrocinador->dominio;
		$url_soporte = $patrocinador->url_soporte;
		$url_politica_privacidad = $patrocinador->url_politica_privacidad;
		$url_terminos_condiciones = $patrocinador->url_terminos_condiciones;
		$url_empresa = $patrocinador->url_empresa;
		$ruc_patrocinador = $patrocinador->ruc;
		$razon_social_patrocinador = $patrocinador->razon_social;
		$email_patrocinador = $patrocinador->email;
		$telefono_patrocinador = $patrocinador->telefono;

		$array_patrocinador = array(
			"id_patrocinador" 			=> (int)$patrocinador->id_contribuyente,
			"logo_img_461" 				=> !empty($patrocinador->logo_461)?$patrocinador->logo_461:'',
			"logo_img_291" 				=> !empty($patrocinador->logo_291)?$patrocinador->logo_291:'',
			"logo_img_56" 				=> !empty($patrocinador->logo_56)?$patrocinador->logo_56:'',
			"url_domain" 				=> !empty($patrocinador->dominio)?$patrocinador->dominio:'',
			"url_soporte" 				=> !empty($patrocinador->url_soporte)?$patrocinador->url_soporte:'',
			"url_politica_privacidad" 	=> !empty($patrocinador->url_politica_privacidad)?$patrocinador->url_politica_privacidad:'',
			"url_terminos_condiciones" 	=> !empty($patrocinador->url_terminos_condiciones)?$patrocinador->url_terminos_condiciones:'',
			"url_empresa" 				=> !empty($patrocinador->url_empresa)?$patrocinador->url_empresa:'',
			"ruc_patrocinador" 			=> !empty($patrocinador->ruc)?$patrocinador->ruc:'',
			"razon_social_patrocinador" => !empty($patrocinador->razon_social)?$patrocinador->razon_social:'',
			"email_patrocinador" 		=> !empty($patrocinador->email)?$patrocinador->email:'',
			"telefono_patrocinador" 	=> !empty($patrocinador->telefono)?$patrocinador->telefono:'',
		);
		
		$resp['userdata'] = array(
			'idusuario'         => (int)$usuario->idusuario,
            'codigo'            => $usuario->codigo,
            'nombre'            => $usuario->nombre,
            'apellido'          => $usuario->apellido,
            'celular'           => $usuario->celular,
            'telefono'          => $usuario->telefono,
			'idsucursal_asignada' => (empty($usuario->idsucursal)) ? '' : $usuario->idsucursal,
			'id_primera_sucursal' => (empty($sucursal->idsucursal)) ? '' : $sucursal->idsucursal,
            'id_rol'            => (int)$usuario->id_rol,
            'rol'               => $rol->nombre,
            'rol_alias'         => $rol->alias,
            'email'             => $usuario->email,
            'url_image'         => $usuario->url_image,
            'ruc'               => $contribuyente->ruc,
            'razon_social'      => $contribuyente->razon_social,
			'id_contribuyente'	=> (int)$contribuyente->id_contribuyente,
			'id_patrocinador'	=> (int)$patrocinador->id_patrocinador,
			'modalidad_envio_sunat' => $contribuyente->modalidad_envio_sunat,
			'patrocinador'		=> $array_patrocinador
		);
		
		$resp['respuesta'] = 'ok';
		
		return $resp;
	}

	public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        //I would recommend to use the following RegEx to check, if it's a valid jwt-token:
        // ==> /Bearer\s((.)\.(.)\.(.*))/

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function getAuthorizationHeader(){
        $headers = null;

		// Revisa si el encabezado Authorization está en REDIRECT_HTTP_AUTHORIZATION
		if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
			$headers = trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
		} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			// Algunas veces el encabezado puede estar en HTTP_AUTHORIZATION
			$headers = trim($_SERVER['HTTP_AUTHORIZATION']);
		}
		
		return $headers;
    }

	public function getProductListAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp_validacion);
		}

		$start = isset($data['start'])?intval($data['start']):1;
		$length = isset($data['length'])?intval($data['length']):10;
		$search_value = isset($data['search_value'])?$data['search_value']:'';
		$idsucursal = isset($data['idsucursal'])?intval($data['idsucursal']):0;
		$tipo_productos = isset($data['tipo_productos'])?intval($data['tipo_productos']):0;
		$draw = isset($data['draw'])?intval($data['draw']):1;
		$order_colum = isset($data['order_colum'])?intval($data['order_colum']):0;
		$order_dir = isset($data['order_dir'])?$data['order_dir']:'asc';
		
		/*
		{
			"start": 1,
			"length": 3,
			"search_value": "",
			"idsucursal": "",
			"tipo_productos": 0,
			"draw": 1,
			"order_colum": 0,
			"order_dir": "asc"
		}
		*/

		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));

		if($idsucursal > 0) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$this->response->setStatusCode(400, 'Bad Request');
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['codigo'] = 'params_error';
				$resp['mensaje'] = 'No existe el la sucursal seleccionada!';
				return $this->response->setJsonContent($resp);
			}
		}

		if($start < 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad start debe ser mayor o igual a cero';
			return $this->response->setJsonContent($resp);
		}

		if($length <= 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad length debe ser mayor a cero';
			return $this->response->setJsonContent($resp);
		}

		if($tipo_productos != 0 && $tipo_productos != 1 && $tipo_productos != 2) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad tipo_productos debe tener valor 0, 1, 2. (0: Todos, 1: Solo Productos, 2: Solo Servicios)';
			return $this->response->setJsonContent($resp);
		}

		if($draw <= 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad draw debe ser mayor a cero';
			return $this->response->setJsonContent($resp);
		}

		if($order_colum < 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad order_colum debe ser mayor a cero';
			return $this->response->setJsonContent($resp);
		}

		if($order_dir != 'asc' && $order_dir != 'desc') {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad order_dir debe tener los siguientes valores: asc o desc. (asc: orden ascendente, desc: orden descendente)';
			return $this->response->setJsonContent($resp);
		}

		$datapost['start'] = $start;
		$datapost['length'] = $length;
		$datapost['search']['value'] = $search_value;
		$datapost['idsucursal'] = $idsucursal;
		$datapost['select_tipo_item'] = $tipo_productos; //1: productos, 2: servicios, 0: todos
		$datapost['draw'] = $draw;
		$datapost['order'][0]['column'] = $order_colum;
		$datapost['order'][0]['dir'] = $order_dir;
		$datapost['mostrar_opt_menu'] = 'no';

		$this->response->setStatusCode(200, 'OK');
		$prodcontroller = new ProductoController;
		$resp_productos = $prodcontroller->get_lista_productos($usuario, $datapost, $idsucursal, $datapost['select_tipo_item']);
		return $this->response->setJsonContent($resp_productos);
	}

	public function getDataBaseAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		$resp = $this->get_data_base($id_contribuyente, $idusuario);
		return $this->response->setJsonContent($resp);
	}

	public function get_data_base($id_contribuyente, $idusuario) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente), 'columns' => 'id_contribuyente, ruc, razon_social, nombre_comercial, email, telefono, codigo_ubigeo, urbanizacion, direccion_fiscal, tipo_certificado, id_patrocinador, fecha_registro, img_logo, logo_461, logo_56, logo_291, logo_350, estado, tipo_empresa, tipo_empresa_sunat, regimen_retencion'));

		$gestiondeetiquetas = new GestiondeetiquetasController;
		$apisunat = new ApisunatController;
		$lista_etiquetas = $gestiondeetiquetas->get_lista_etiquetas($id_contribuyente)['lista'];
		$fecha_actual = date('Y-m-d');

		$anio_actual =  date("Y");
		$icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
		$impuesto_icbper = !$icbper?0.5:$icbper->monto;

		$data_patrocinador = array(
			'dominio' 		=> 'facturalaya.com',
			'logo_461'		=> 'https://arpsystem.com.pe/sys_fe/public/img/logo_facturalaya_461.png',
			'logo_291'		=> 'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_291_verificado.png',
			'logo_56'		=> 'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_56_only.png',
			'url_soporte' 	=> 'https://arpsystem.com.pe/soporte'
		);

		if($contribuyente->id_contribuyente > 1) {
			$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));
			if($patrocinador) {
				if(!empty($patrocinador->dominio)) {
					$data_patrocinador = array(
						'dominio' 		=> $patrocinador->dominio,
						'logo_461'		=> !empty($patrocinador->logo_461)?$patrocinador->logo_461:'https://arpsystem.com.pe/sys_fe/public/img/logo_facturalaya_461.png',
						'logo_291'		=> !empty($patrocinador->logo_291)?$patrocinador->logo_291:'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_291_verificado.png',
						'logo_56'		=> !empty($patrocinador->logo_56)?$patrocinador->logo_56:'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_56_only.png',
						'url_soporte' 	=> 'https://arpsystem.com.pe/soporte'
					);
				}
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['dataBase']['sunatTipooperacion'] 		= SunatTipooperacion::find(array("order" => "orden ASC"));
		$resp['dataBase']['sunatTipodocelectronico'] 	= SunatTipodocelectronico::find();
		$resp['dataBase']['sunatCodigodetraccion'] 		= SunatCodigodetraccion::find();
		$resp['dataBase']['sunatTipodocidentidad'] 		= SunatTipodocidentidad::find();
		$resp['dataBase']['sunatMoneda'] 				= SunatMoneda::find();
		$resp['dataBase']['sunatUnidadmedida'] 			= SunatUnidadmedida::find();
		$resp['dataBase']['sunatTiponotadebito'] 		= SunatTiponotadebito::find();
		$resp['dataBase']['sunatTiponotacredito'] 		= SunatTiponotacredito::find();
		$resp['dataBase']['sunatTipoafectacionigv'] 	= SunatTipoafectacionigv::find("id_tipoafectacionigv <> 7152");
		$resp['dataBase']['sunatCodigoubigeo'] 			= SunatCodigoubigeo::find();
		$resp['dataBase']['sunatMotivoTraslado']		= SunatMotivotraslado::find();
		$resp['dataBase']['listaCodigoPuerto'] 			= SunatCodigopuerto::find();
		
		$resp['dataBase']['cuentaDetraccion'] 			= Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta = 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$resp['dataBase']['cuentasBanco'] 				= Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$resp['dataBase']['listaEtiquetas'] 			= $lista_etiquetas;
		$resp['dataBase']['tipoCambio'] 				= $apisunat->get_tipo_cambio($fecha_actual);
		
		$resp['dataBase']['listaSucursales'] 			= Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$resp['dataBase']['dataContribuyente']			= $contribuyente;
		
		$resp['dataBase']['listaVendedores']			= $this->get_lista_vendedores($id_contribuyente);
		
		$resp['dataBase']['dataPatrocinador']			= $data_patrocinador;
		$resp['dataBase']['sunatData']					= array(
			'montoIcbper'		=> (float)$impuesto_icbper,
			'regimenRetencion'	=> (float)$contribuyente->regimen_retencion
		);
		$resp['dataBase']['condicionPago'] = $this->get_lista_condicion_pago($id_contribuyente);
		return $resp;
	}

	public function getListaCondicionPagoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		$resp = $this->get_lista_condicion_pago($id_contribuyente);
		return $this->response->setJsonContent($resp);
	}

	public function getMontoIcbperAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		$anio_actual =  date("Y");
		$icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
		$impuesto_icbper = !$icbper?0.5:$icbper->monto;
		$resp['respuesta'] = 'ok';
		$resp['monto_icbper'] = (float)$impuesto_icbper;
		return $this->response->setJsonContent($resp);
	}

	public function getDataSocioEstrategicoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		
		$data_patrocinador = array(
			'dominio' 		=> 'facturalaya.com',
			'logo_461'		=> 'https://arpsystem.com.pe/sys_fe/public/img/logo_facturalaya_461.png',
			'logo_291'		=> 'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_291_verificado.png',
			'logo_56'		=> 'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_56_only.png',
			'url_soporte' 	=> 'https://arpsystem.com.pe/soporte'
		);

		if($contribuyente->id_contribuyente > 1) {
			$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));
			if($patrocinador) {
				if(!empty($patrocinador->dominio)) {
					$data_patrocinador = array(
						'dominio' 		=> $patrocinador->dominio,
						'logo_461'		=> !empty($patrocinador->logo_461)?$patrocinador->logo_461:'https://arpsystem.com.pe/sys_fe/public/img/logo_facturalaya_461.png',
						'logo_291'		=> !empty($patrocinador->logo_291)?$patrocinador->logo_291:'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_291_verificado.png',
						'logo_56'		=> !empty($patrocinador->logo_56)?$patrocinador->logo_56:'https://arpsystem.com.pe/sys_fe/img/logo_facturalaya_56_only.png',
						'url_soporte' 	=> 'https://arpsystem.com.pe/soporte'
					);
				}
			}
		}

		return $this->response->setJsonContent($data_patrocinador);
	}

	public function getListaVendedoresAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		$resp = $this->get_lista_vendedores($id_contribuyente);
		return $this->response->setJsonContent($resp);
	}

	public function getListaSucursalesAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		$resp = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		return $this->response->setJsonContent($resp);
	}

	public function getListaEtiquetasAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$this->response->setStatusCode(200, 'OK');
		$gestiondeetiquetas = new GestiondeetiquetasController;
		$resp = $gestiondeetiquetas->get_lista_etiquetas($id_contribuyente);
		return $this->response->setJsonContent($resp);
	}

	public function getCuentasBancoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		return $this->response->setJsonContent($resp);
	}

	public function getCuentaDetraccionAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta = 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoOperacionAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTipooperacion::find(array("order" => "orden ASC"));
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoDocElectronicoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTipodocelectronico::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoDetraccionAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigodetraccion::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoDocIdentidadAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTipodocidentidad::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatMonedaAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatMoneda::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatUnidadMedidaAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatUnidadmedida::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoNotaDebitoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTiponotadebito::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoNotaCreditoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTiponotacredito::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoAfectacionIgvAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTipoafectacionigv::find("id_tipoafectacionigv <> 7152");
		return $this->response->setJsonContent($resp);
	}

	public function getSunatMotivoTrasladoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatMotivotraslado::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoUbigeoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigoubigeo::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoPuertoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigopuerto::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoCambioAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$fecha_actual = date('Y-m-d');
		$apisunat = new ApisunatController;
		$resp = $apisunat->get_tipo_cambio($fecha_actual);
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoEntidadFinancieraAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigoentidadfinanciera::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoPrecioAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigoprecio::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoRetornoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigoretorno::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatCodigoTipoPercepcionAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatCodigotipopercepcion::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatIcbperAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatIcbper::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatMediosDePagoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatMediosdepago::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatModalidadTrasladoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatModalidadtraslado::find();
		return $this->response->setJsonContent($resp);
	}

	public function getSunatTipoRegimenAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$resp = SunatTiporegimen::find();
		return $this->response->setJsonContent($resp);
	}

	public function get_lista_condicion_pago($id_contribuyente) {
		$condicion_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo <> 'credito'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$lista_condicion_pago = array();
		foreach($condicion_pago as $condicion) {
			$lista_condicion_pago[] = array(
				'id_condicionpago' 	=> $condicion->id_condicionpago,
				'tipo'				=> $condicion->tipo,
				'nombre'			=> $condicion->condicionpago,
				'dias'				=> $condicion->dias
			);
		}

		$condicion_pago_credito = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo = 'credito'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if($condicion_pago_credito) {
			$acepta_credito = 'si';
			$id_condicionpago_credito = $condicion_pago_credito->id_condicionpago;
		} else {
			$acepta_credito = 'no';
			$id_condicionpago_credito = 0;
		}

		$condiciones = array(
			'id_condicionpago_credito' 	=> (int)$id_condicionpago_credito,
			'acepta_credito'			=> $acepta_credito,
			'lista_condicion'			=> $lista_condicion_pago
		);

		return $condiciones;
	}

	public function get_lista_vendedores($id_contribuyente) {
		$usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $id_contribuyente), 'columns' => 'idusuario, codigo, id_rol, nombre, apellido, celular, telefono, email, url_image, permisos', "order" => "idusuario DESC"));
		
		$lista_usuarios = array();
		foreach($usuarios as $usuario) {
			$usuario = (array) $usuario;
			$usuario['permisos'] = json_decode($usuario['permisos']);
			$usuario['user_options'] = UsuarioOpcion::find(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario: ", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idusuario' => $usuario['idusuario']), 'columns' => 'opcion_nombre, opcion_valor', "order" => "opcion_nombre DESC"));
			$lista_usuarios[] = $usuario;
		}

		return $lista_usuarios;
	}

	public function getTipoCambioAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$apisunat = new ApisunatController;
		$fecha_actual = date('Y-m-d');
		$resp_tipo_cambio = $apisunat->get_tipo_cambio($fecha_actual);

		if($resp_tipo_cambio['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'tipo_cambio';
			$resp['mensaje'] = 'No existe el tipo de cambio';
			return $this->response->setJsonContent($resp);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp_tipo_cambio);
	}

	public function procesarVentaAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$data['contribuyente'] = array(
			"token_contribuyente"	=> $this->get_token_contribuyente($id_contribuyente),
			"id_usuario_vendedor"	=> $usuario->idusuario,
			"tipo_proceso"			=> $contribuyente->tipo_envio_sunat,
			"tipo_envio"			=> "inmediato"
		);

		$data['origen_data'] = 'conexion_api_apiappcontroller';
		
		$api = new ApiController;
		$resp_proceso_venta = $api->procesar_venta($data);
		if($resp_proceso_venta['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_proceso_venta);
		}

		$this->response->setStatusCode(200, 'OK');
		$resp = array(
			'respuesta' 				=> 'ok',
			'serie_comprobante'			=> $resp_proceso_venta['documento']['serie_comprobante'],
			'numero_comprobante'		=> $resp_proceso_venta['documento']['numero_comprobante'],
			'id_tipodoc_electronico' 	=> $resp_proceso_venta['documento']['id_tipodoc_electronico'],
			'mensaje'					=> $resp_proceso_venta['mensaje'],
			'mensaje_sunat'				=> isset($resp_proceso_venta['repuesta_api']) ? $resp_proceso_venta['repuesta_api'] : '',
			'url_relativa_a4' 			=> $resp_proceso_venta['url_relativa_a4'],
			'url_relativa_ticket' 		=> $resp_proceso_venta['url_relativa_ticket'],
			'url_relativa_xml' 			=> $resp_proceso_venta['url_relativa_xml'],
			'url_relativa_xml_cdr' 		=> $resp_proceso_venta['url_relativa_xml_cdr'],
		);

		return $this->response->setJsonContent($resp);
	}

	public function procesarNotaVentaAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$data['contribuyente'] = array(
			"token_contribuyente"	=> $this->get_token_contribuyente($id_contribuyente),
			"id_usuario_vendedor"	=> $usuario->idusuario,
			"tipo_proceso"			=> $contribuyente->tipo_envio_sunat,
			"tipo_envio"			=> "inmediato"
		);

		$nota_de_venta = new ApinotadeventaController;
		$resp = $nota_de_venta->procesar_nota_venta($data, $contribuyente->token);
		if($resp['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp);
	}

	public function procesarCotizacionAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
            return $this->response->setJsonContent($resp_validacion);
		}

		$data['contribuyente'] = array(
			"token_contribuyente"	=> $this->get_token_contribuyente($id_contribuyente),
			"id_usuario_vendedor"	=> $usuario->idusuario,
			"tipo_proceso"			=> $contribuyente->tipo_envio_sunat,
			"tipo_envio"			=> "inmediato"
		);

		$nota_cotizacion = new ApicotizacionController;
		$resp = $nota_cotizacion->procesar_cotizacion($data, $contribuyente->token);
		if($resp['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp);
	}

	public function procesarNotacreditoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$data['contribuyente'] = array(
			"token_contribuyente"	=> $this->get_token_contribuyente($id_contribuyente),
			"id_usuario_vendedor"	=> $usuario->idusuario,
			"tipo_proceso"			=> $contribuyente->tipo_envio_sunat,
			"tipo_envio"			=> "inmediato"
		);

		$nota_credito = new ApinotacreditoController;
		$resp = $nota_credito->procesar_nota_credito($data, $contribuyente->token);
		if($resp['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp);
	}

	public function procesarNotadebitoAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$data['contribuyente'] = array(
			"token_contribuyente"	=> $this->get_token_contribuyente($id_contribuyente),
			"id_usuario_vendedor"	=> $usuario->idusuario,
			"tipo_proceso"			=> $contribuyente->tipo_envio_sunat,
			"tipo_envio"			=> "inmediato"
		);
		
		$nota_debito = new ApinotadebitoController;
		$resp = $nota_debito->procesar_nota_debito($data, $contribuyente->token);
		if($resp['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp);
	}

	public function procesarGuiaRemisionAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$data['contribuyente'] = array(
			"token_contribuyente"	=> $this->get_token_contribuyente($id_contribuyente),
			"id_usuario_vendedor"	=> $usuario->idusuario,
			"tipo_proceso"			=> $contribuyente->tipo_envio_sunat,
			"tipo_envio"			=> "inmediato"
		);
		
		$guia_remision = new ApiguiaremisionController;
		$resp_procesar_guia = $guia_remision->procesar_guia_remision($data, $token);
		if($resp_procesar_guia['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp_procesar_guia);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp_procesar_guia);
	}

	public function buscarDataClienteAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		$tipo_doc = isset($data['tipo_doc'])?$data['tipo_doc']:'';
		$num_doc = isset($data['num_doc'])?$data['num_doc']:'';

		if(empty($tipo_doc) || empty($num_doc)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'data';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'datos inválidos';
			return $this->response->setJsonContent($resp);
		}

		if($tipo_doc != 'dni' && $tipo_doc != 'ruc') {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'data';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'datos inválidos';
			return $this->response->setJsonContent($resp);
		}

		//validar si tiene una suscripción activa!
		$herramientas = new HerramientasController;
		if($tipo_doc == 'ruc') {
			$resp = $herramientas->get_data_api_busquedas('ruc', $num_doc);
		} else {
			$resp = $herramientas->get_data_api_busquedas('dni', $num_doc);
		}

		if($resp['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp);
		}
		
		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp);
	}

	public function getListaCpeAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}
		
		$start 					= isset($data['start'])?intval($data['start']):1;
		$length 				= isset($data['length'])?intval($data['length']):10;
		$search_value 			= isset($data['search_value'])?$data['search_value']:'';
		$draw 					= isset($data['draw'])?intval($data['draw']):1;
		$order_colum 			= isset($data['order_colum'])?intval($data['order_colum']):0;
		$order_dir 				= isset($data['order_dir'])?$data['order_dir']:'asc';
		
		$fecha_inicio 			= isset($data['fecha_inicio'])?$data['fecha_inicio']:'';
		$fecha_fin 				= isset($data['fecha_fin'])?$data['fecha_fin']:'';
		$sucursales 			= isset($data['sucursales'])?$data['sucursales']:array();
		$vendedores 			= isset($data['vendedores'])?$data['vendedores']:array();
		$cate_busqueda_docs		= isset($data['cate_busqueda_docs'])?$data['cate_busqueda_docs']:'';

		if($start < 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad start debe ser mayor o igual a cero';
			return $this->response->setJsonContent($resp);
		}

		if($length <= 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad length debe ser mayor a cero';
			return $this->response->setJsonContent($resp);
		}

		if($draw <= 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad draw debe ser mayor a cero';
			return $this->response->setJsonContent($resp);
		}

		if($order_colum < 0) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad order_colum debe ser mayor a cero';
			return $this->response->setJsonContent($resp);
		}

		if($order_dir != 'asc' && $order_dir != 'desc') {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad order_dir debe tener los siguientes valores: asc o desc. (asc: orden ascendente, desc: orden descendente)';
			return $this->response->setJsonContent($resp);
		}

		$herramientas = new HerramientasController;
		if(!$herramientas->validar_fecha_formato($fecha_inicio, 'd-m-Y H:i:s')) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_inicio no es válido, el formato correcto debe ser: "d-m-Y H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(!$herramientas->validar_fecha_formato($fecha_fin, 'd-m-Y H:i:s')) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_fin no es válido, el formato correcto debe ser: "d-m-Y H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(empty($cate_busqueda_docs)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad cate_busqueda_docs tiene un valor inválido. (Valores permitidos: b_f_nc_nd, nv, coti, gre)';
			return $this->response->setJsonContent($resp);
		}

		if(!in_array($cate_busqueda_docs, array('b_f_nc_nd', 'nv', 'coti', 'gre'))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad cate_busqueda_docs tiene un valor inválido. (Valores permitidos: b_f_nc_nd, nv, coti, gre)';
			return $this->response->setJsonContent($resp);
		}

		$fecha_inicio_obj = DateTime::createFromFormat('d-m-Y H:i:s', $fecha_inicio);
		$fecha_inicio_ingles = $fecha_inicio_obj->format('Y-m-d H:i:s');

		$fecha_fin_obj = DateTime::createFromFormat('d-m-Y H:i:s', $fecha_fin);
		$fecha_fin_ingles = $fecha_fin_obj->format('Y-m-d H:i:s');

		$datapost['start'] 					= $start;
		$datapost['length'] 				= $length;
		$datapost['search']['value'] 		= $search_value;
		$datapost['draw'] 					= $draw;
		$datapost['order'][0]['column'] 	= $order_colum;
		$datapost['order'][0]['dir'] 		= $order_dir;
		$datapost['mostrar_opt_menu'] 		= 'no';

		$datapost['fecha_inicio_valor'] 	= $fecha_inicio_ingles;
		$datapost['fecha_fin_valor'] 		= $fecha_fin_ingles;
		$datapost['sucursales'] 			= $sucursales;
		$datapost['vendedores'] 			= $vendedores;
		$datapost['cate_busqueda_docs'] 	= $cate_busqueda_docs;

		
		
		$reportedocumentos = new ReportedocumentosController;

		if($cate_busqueda_docs == 'b_f_nc_nd') {
			$resp_lista = $reportedocumentos->get_lista_documentos($datapost, $contribuyente, $usuario);
		} else if($cate_busqueda_docs == 'nv') {
			$resp_lista = $reportedocumentos->get_notas_de_venta($datapost, $contribuyente, $usuario);
		} else if($cate_busqueda_docs == 'coti') {
			$resp_lista = $reportedocumentos->get_cotizaciones($datapost, $contribuyente, $usuario);
		} else if($cate_busqueda_docs == 'gre') {
			$resp_lista = $reportedocumentos->get_lista_guias_remision($datapost, $contribuyente, $usuario);
		}

		if($resp_lista['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp_lista);
		}

		$lista = array();
		foreach($resp_lista['data']['data'] as $item) {
			$item = (object)$item;
			$lista[] = array(
				'datetime'			=> $item->datetime,
				'fecha_registro'	=> date("d-m-Y", strtotime($item->datetime)),
				'hora'				=> date("H:i A", strtotime($item->datetime)),
				'serie'				=> isset($item->serie_comprobante)?$item->serie_comprobante:'',
				'correlativo'		=> isset($item->numero_comprobante)?$item->numero_comprobante:'',
				'nombre'			=> isset($item->nombre_cpe)?$item->nombre_cpe:'',
				'url_pdf_a4'		=> isset($item->url_a4)?$item->url_a4:'',
				'url_pdf_ticket'	=> isset($item->url_ticket)?$item->url_ticket:'',
				'url_xml'			=> isset($item->ruta_xml)?$item->ruta_xml:'',
				'url_cdr'			=> isset($item->ruta_cdr)?$item->ruta_cdr:'',
				'monto_total'		=> isset($item->total_monto)?$item->total_monto:0,
				'simbolo_moneda'	=> isset($item->simbolo_moneda)?$item->simbolo_moneda:'',
				'peso_total'		=> isset($item->peso)?$item->peso:0,
				'etiquetas_activas'	=> isset($item->etiquetas_activas)?$item->etiquetas_activas:array(),
				'tipo_operacion'	=> isset($item->tipo_operacion)?$item->tipo_operacion:'',
				'retencion'			=> isset($item->retencion)?$item->retencion:'',

				'cliente'			=> array(
					'tipo_doc'		=> $item->cliente_tipo_doc,
					'razon_social'	=> $item->cliente_nombre,
					'num_doc'		=> $item->cliente_num_doc
				)
			);
		}

		$resp_lista['data']['data'] = $lista;

		$this->response->setStatusCode(200, 'OK');
		$resp['respuesta'] = 'ok';
		$resp['lista'] = $resp_lista['data'];

		return $this->response->setJsonContent($resp);
	}

	public function getStatsTotalsAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}
		
		$fecha_inicio = isset($data['fecha_inicio'])?date("Y-m-d H:i:s", strtotime($data['fecha_inicio'])):'';
		$fecha_fin = isset($data['fecha_fin'])?date("Y-m-d H:i:s", strtotime($data['fecha_fin'])):'';
		$sucursales = isset($data['sucursales'])?$data['sucursales']:array();
		$vendedores = isset($data['vendedores'])?$data['vendedores']:array();
		$periodo = isset($data['periodo'])?$data['periodo']:'';

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date_time($fecha_inicio)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_inicio no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(!$herramientas->validate_date_time($fecha_fin)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_fin no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(count($sucursales)) {
			foreach($sucursales as $id_sucursal) {
				$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
				if(!$sucursal) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'La sucursal seleccionada no existe.';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(count($vendedores)) {
			foreach($vendedores as $id_vendedor) {
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$vendedor) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'El colaborador seleccionado no existe!';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(!in_array($periodo, array('dia', 'mes', 'anio'))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El periodo debe ser dia, mes o anio.';
			return $this->response->setJsonContent($resp);
		}

		$datapost['fecha_inicio'] = date("d-m-Y H:i:s", strtotime($fecha_inicio));
		$datapost['fecha_inicio_valor'] = $fecha_inicio;
		$datapost['fecha_fin'] = date("d-m-Y H:i:s", strtotime($fecha_fin));
		$datapost['fecha_fin_valor'] = $fecha_fin;
		$datapost['sucursales'] = implode(',', $sucursales);
		$datapost['vendedores'] = implode(',', $vendedores);
		$datapost['periodo'] = $periodo;
		
		$dashboard = new DashboardController;
		$resp_dashboard = $dashboard->get_datos_estadisticos($datapost, $contribuyente, $usuario);
		if($resp_dashboard['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp_dashboard);
		}

		$resp['respuesta'] = 'ok';
		$resp['totales'] = array(
			'total_facturas' => $resp_dashboard['total_facturas'],
			'total_boletas' => $resp_dashboard['total_boletas'],
			'total_notas_credito' => $resp_dashboard['total_notas_credito'],
			'total_notas_debito' => $resp_dashboard['total_notas_debito'],
			'total_notas_venta' => $resp_dashboard['total_notas_venta'],
			'total_neto' => $resp_dashboard['total_neto'],
		);
		$resp['dataFlChart'] = $resp_dashboard['dataFlChart'];
		$resp['condiciones_pago'] = $resp_dashboard['condiciones_pago'];

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp);
	}

	public function getTotalesVentaAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		/*
		{
			"fecha_inicio": "09-11-2022 0:00:00",
			"fecha_fin": "09-12-2022 23:59:59",
			"sucursales": [],
			"vendedores": [],
		}
		*/

		$fecha_inicio = isset($data['fecha_inicio'])?date("Y-m-d H:i:s", strtotime($data['fecha_inicio'])):'';
		$fecha_fin = isset($data['fecha_fin'])?date("Y-m-d H:i:s", strtotime($data['fecha_fin'])):'';
		$sucursales = isset($data['sucursales'])?$data['sucursales']:array();
		$vendedores = isset($data['vendedores'])?$data['vendedores']:array();
		$periodo = isset($data['periodo'])?$data['periodo']:'';

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date_time($fecha_inicio)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_inicio no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(!$herramientas->validate_date_time($fecha_fin)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_fin no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(count($sucursales)) {
			foreach($sucursales as $id_sucursal) {
				$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
				if(!$sucursal) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'La sucursal seleccionada no existe.';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(count($vendedores)) {
			foreach($vendedores as $id_vendedor) {
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$vendedor) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'El colaborador seleccionado no existe!';
					return $this->response->setJsonContent($resp);
				}
			}
		}
		
		$caja_chica = new CajachicaController;
		$resp_caja_chica = $caja_chica->get_resumen_ventas($fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $sucursales, $vendedores, $contribuyente->tipo_envio_sunat);

		$this->response->setStatusCode(200, 'OK');
		$resp['respuesta'] = 'ok';
		$resp['totales'] = $resp_caja_chica;
		return $this->response->setJsonContent($resp);
	}

	public function getEntradasSalidasAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		/*
		{
			"fecha_inicio": "09-11-2022 0:00:00",
			"fecha_fin": "09-12-2022 23:59:59",
			"sucursales": [],
			"vendedores": []
		}
		*/

		$fecha_inicio = isset($data['fecha_inicio'])?date("Y-m-d H:i:s", strtotime($data['fecha_inicio'])):'';
		$fecha_fin = isset($data['fecha_fin'])?date("Y-m-d H:i:s", strtotime($data['fecha_fin'])):'';
		$sucursales = isset($data['sucursales'])?$data['sucursales']:array();
		$vendedores = isset($data['vendedores'])?$data['vendedores']:array();

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date_time($fecha_inicio)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_inicio no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(!$herramientas->validate_date_time($fecha_fin)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_fin no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(count($sucursales)) {
			foreach($sucursales as $id_sucursal) {
				$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
				if(!$sucursal) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'La sucursal seleccionada no existe.';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(count($vendedores)) {
			foreach($vendedores as $id_vendedor) {
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$vendedor) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'El colaborador seleccionado no existe!';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		$datapost['fecha_inicio'] = date("d-m-Y H:i:s", strtotime($fecha_inicio));
		$datapost['fecha_inicio_valor'] = $fecha_inicio;
		$datapost['fecha_fin'] = date("d-m-Y H:i:s", strtotime($fecha_fin));
		$datapost['fecha_fin_valor'] = $fecha_fin;
		$datapost['sucursales'] = implode(',', $sucursales);
		$datapost['vendedores'] = implode(',', $vendedores);
		
		$caja_chica = new CajachicaController;
		$resp_caja_chica = $caja_chica->get_lista_entradas_salidas($datapost, $contribuyente, $usuario);
		if($resp_caja_chica['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp_caja_chica);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp_caja_chica);
	}

	public function getDetalleCajaChicaAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}

		/*
		{
			"fecha_inicio": "2022-12-09 00:00:00",
			"fecha_fin": "2022-12-09 21:28:12",
			"sucursales": [],
			"vendedores": [],

			id_tipocpe: "", (01, 03, 07, 08, 77, vacio)
			id_codigomoneda: "PEN", //PEN - USD
			tipo_transaccion: "", //contado, tarjeta_credito, transferencia, credito
			tipo_venta: "contado", //contado, credito
			tipo_reporte: "documentos" //documentos, todo, abonos
		}
		*/

		$fecha_inicio = isset($data['fecha_inicio'])?date("Y-m-d H:i:s", strtotime($data['fecha_inicio'])):'';
		$fecha_fin = isset($data['fecha_fin'])?date("Y-m-d H:i:s", strtotime($data['fecha_fin'])):'';
		$sucursales = isset($data['sucursales'])?$data['sucursales']:array();
		$vendedores = isset($data['vendedores'])?$data['vendedores']:array();
		$id_tipocpe = isset($data['id_tipocpe'])?$data['id_tipocpe']:'';
		$id_codigomoneda = isset($data['id_codigomoneda'])?$data['id_codigomoneda']:'';
		$tipo_transaccion = isset($data['tipo_transaccion'])?$data['tipo_transaccion']:'';
		$tipo_venta = isset($data['tipo_venta'])?$data['tipo_venta']:'';
		$tipo_reporte = isset($data['tipo_reporte'])?$data['tipo_reporte']:'';
		

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date_time($fecha_inicio)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_inicio no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(!$herramientas->validate_date_time($fecha_fin)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_fin no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(count($sucursales)) {
			foreach($sucursales as $id_sucursal) {
				$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
				if(!$sucursal) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'La sucursal seleccionada no existe.';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(count($vendedores)) {
			foreach($vendedores as $id_vendedor) {
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$vendedor) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'El colaborador seleccionado no existe!';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(!in_array($id_tipocpe, array('', '01', '03', '07', '08', '77'))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad id_tipocpe no tiene un valor válido. (Valores válidos: 01, 03, 07, 08, 77 y vacío.)';
			return $this->response->setJsonContent($resp);
		}

		if(!in_array($id_codigomoneda, array('', 'PEN', 'USD'))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad id_codigomoneda no tiene un valor válido. (Valores válidos: PEN, USD)';
			return $this->response->setJsonContent($resp);
		}

		if(!in_array($tipo_transaccion, array("contado", "tarjeta_credito", "transferencia", "credito", ""))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad tipo_transaccion no tiene un valor válido. (Valores válidos: contado, tarjeta_credito, transferencia, credito y vacio)';
			return $this->response->setJsonContent($resp);
		}

		if(!in_array($tipo_venta, array("contado", "credito", ""))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad tipo_venta no tiene un valor válido. (Valores válidos: contado, credito)';
			return $this->response->setJsonContent($resp);
		}

		if(!in_array($tipo_reporte, array("documentos", "todo", "abonos"))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'La propiedad tipo_reporte no tiene un valor válido. (Valores válidos: documentos, todo, abonos)';
			return $this->response->setJsonContent($resp);
		}

		$datapost['fecha_inicio'] 		= date("d-m-Y H:i:s", strtotime($fecha_inicio));
		$datapost['fecha_inicio_valor'] = $fecha_inicio;
		$datapost['fecha_fin'] 			= date("d-m-Y H:i:s", strtotime($fecha_fin));
		$datapost['fecha_fin_valor'] 	= $fecha_fin;
		$datapost['sucursales'] 		= implode(',', $sucursales);
		$datapost['vendedores'] 		= implode(',', $vendedores);
		$datapost['id_tipocpe'] 		= $id_tipocpe;
		$datapost['id_codigomoneda'] 	= $id_codigomoneda;
		$datapost['tipo_transaccion'] 	= $tipo_transaccion;
		$datapost['tipo_venta'] 		= $tipo_venta;
		$datapost['tipo_reporte'] 		= $tipo_reporte;
		
		$caja_chica = new CajachicaController;
		$resp_caja_chica = $caja_chica->detallecajachica($datapost, $contribuyente, $usuario);
		if($resp_caja_chica['respuesta'] == 'error') {
			$this->response->setStatusCode(400, 'Bad Request');
			return $this->response->setJsonContent($resp_caja_chica);
		}

		$this->response->setStatusCode(200, 'OK');
		return $this->response->setJsonContent($resp_caja_chica);
	}

	public function getDataDashboardAction() {
		$this->setHttpResponseHeaders();
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");

        $data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			return $this->response->setJsonContent($resp);
		}

		$token = $this->getBearerToken();
		if(empty($token)) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Token';
			return $this->response->setJsonContent($resp);
		}

		$id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:'';
		$idusuario = isset($data['idusuario'])?$data['idusuario']:'';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No Existe El Usuario';
			return $this->response->setJsonContent($resp);
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
		if(!$usuario) {
			$this->response->setStatusCode(401, 'Unauthorized');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'token';
			$resp['mensaje'] = 'No existe el usuario';
			return $this->response->setJsonContent($resp);
		}

		$resp_validacion = $this->validate_token($id_contribuyente, $idusuario, $token);
		if($resp_validacion['respuesta'] == 'error') {
			$this->response->setStatusCode(401, 'Unauthorized');
			return $this->response->setJsonContent($resp_validacion);
		}
		
		$fecha_inicio = isset($data['fecha_inicio'])?date("Y-m-d H:i:s", strtotime($data['fecha_inicio'])):'';
		$fecha_fin = isset($data['fecha_fin'])?date("Y-m-d H:i:s", strtotime($data['fecha_fin'])):'';
		$sucursales = isset($data['sucursales'])?$data['sucursales']:array();
		$vendedores = isset($data['vendedores'])?$data['vendedores']:array();
		$periodo = isset($data['periodo'])?$data['periodo']:'';

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date_time($fecha_inicio)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_inicio no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(!$herramientas->validate_date_time($fecha_fin)) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El formado de la propiedad: fecha_fin no es válido, el formato correcto debe ser: "Y-m-d H:i:s"';
			return $this->response->setJsonContent($resp);
		}

		if(count($sucursales)) {
			foreach($sucursales as $id_sucursal) {
				$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
				if(!$sucursal) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'La sucursal seleccionada no existe.';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(count($vendedores)) {
			foreach($vendedores as $id_vendedor) {
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$vendedor) {
					$this->response->setStatusCode(400, 'Bad Request');
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['codigo'] = 'params_error';
					$resp['mensaje'] = 'El colaborador seleccionado no existe!';
					return $this->response->setJsonContent($resp);
				}
			}
		}

		if(!in_array($periodo, array('dia', 'mes', 'anio'))) {
			$this->response->setStatusCode(400, 'Bad Request');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['codigo'] = 'params_error';
			$resp['mensaje'] = 'El periodo debe ser dia, mes o anio.';
			return $this->response->setJsonContent($resp);
		}

		$datapost['fecha_inicio'] = date("d-m-Y H:i:s", strtotime($fecha_inicio));
		$datapost['fecha_inicio_valor'] = $fecha_inicio;
		$datapost['fecha_fin'] = date("d-m-Y H:i:s", strtotime($fecha_fin));
		$datapost['fecha_fin_valor'] = $fecha_fin;
		$datapost['sucursales'] = implode(',', $sucursales);
		$datapost['vendedores'] = implode(',', $vendedores);
		$datapost['periodo'] = $periodo;
	}

	public function get_token_contribuyente($id_contribuyente) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			return '';
		}

		if(empty($contribuyente->token)) {
			$herramientas = new HerramientasController;
            $token = $herramientas->gettoken(37);
			$contribuyente->token = $token;
			try {
				if(!$contribuyente->save()) {
					$msg = '';
					foreach ($contribuyente->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}

					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $msg;
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $e->getMessage();
				return $resp;
			}

			return $token;
		}

		return $contribuyente->token;
	}
}