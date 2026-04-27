<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/firebasejwt/vendor/autoload.php";
use Firebase\JWT\JWT;
class ApiController extends ControllerBase
{

	public function indexAction() {
		$this->view->disable();
		exit();
	}
	 
	public function crear_token($id_usuario, $email) {
		$time = time();
		$time_expire = $time + (60*60*24); //Tiempo Expiración: 60 segundos x 60 minutos x 24 horas
		$data_token = array(
			'iat'	=> $time,
			'exp'	=> $time_expire, //Tiempo Expiración: 60 segundos x 60 minutos x 24 horas
			'data'	=> array(
				'id_usuario'	=> $id_usuario,
				//'email'			=> $email
			)
		);

		$resp['token'] = JWT::encode($data_token, $this->token_cookie, 'HS512');
		$resp['time_expire'] = $time_expire;
		return $resp;
	}

	public function user_loginAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}

		$email = isset($data['email'])?$data['email']:'';
		$password = isset($data['password'])?$data['password']:'';

		$usuario = Usuario::findFirst(array("email = :email: and password = :password: and estado = 'activo'", 'bind' => array('email' => $email, 'password' => $password)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No Existe el Usuario';
			echo json_encode($resp);
			exit();
		}

		$resp_token = $this->crear_token($usuario->idusuario, $usuario->email);
		$usuario->token = $resp_token['token'];
		$usuario->token_expire = $resp_token['time_expire']; 

		try {
			if(!$usuario->save()) {
				$msg = '';
				foreach ($usuario->getMessages() as $message) {
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
		$resp['id_token'] = $resp_token['token'];
		echo json_encode($resp);
		exit();
	}

	public function registrarContribuyenteAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}

		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$usuario_patrocinador = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and (id_rol = 5 or id_rol = 1)", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente)));
		if(!$usuario_patrocinador) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_socio_estrategico';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No tiene permisos para crear contribuyentes en el sistema';
			echo json_encode($resp);
			exit();
		}

		$id_patrocinador = $usuario_patrocinador->id_contribuyente;
		if($usuario_patrocinador->id_contribuyente != 1) {

		}

		if(!isset($data['nuevo_contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_socio_estrategico';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Ingresa los datos del nuevo contribuyente';
			echo json_encode($resp);
			exit();
		}

		$nuevo_contribuyente = $data['nuevo_contribuyente'];

		$password_login = isset($nuevo_contribuyente['password_login'])?$nuevo_contribuyente['password_login']:'';
		$resp_password = $this->check_password_secure($password_login);
		if($resp_password['respuesta'] == 'error') {
			echo json_encode($resp_password);
			exit();
		}

		$datapost = array(
			"opcion_tipo_proceso"       => 'on', //on: PRUEBA
            "ruta_logo"                 => isset($nuevo_contribuyente['ruta_logo'])?$nuevo_contribuyente['ruta_logo']:"https://arpsystem.com.pe/facturacionv8/public/img/subetulogo.jpg",
            "ruc"                       => isset($nuevo_contribuyente['ruc'])?$nuevo_contribuyente['ruc']:'', 
            "razon_social"              => isset($nuevo_contribuyente['razon_social'])?$nuevo_contribuyente['razon_social']:'', 
            "nombre_comercial"          => isset($nuevo_contribuyente['nombre_comercial'])?$nuevo_contribuyente['nombre_comercial']:'', 
            "telefono"	                => isset($nuevo_contribuyente['telefono'])?$nuevo_contribuyente['telefono']:'', 
            "ubigeo"                    => isset($nuevo_contribuyente['ubigeo'])?$nuevo_contribuyente['ubigeo']:'', 
            "urbanizacion"              => isset($nuevo_contribuyente['urbanizacion'])?$nuevo_contribuyente['urbanizacion']:'', 
            "direccionfiscal"           => isset($nuevo_contribuyente['direccionfiscal'])?$nuevo_contribuyente['direccionfiscal']:'', 

            "idpatrocinador"            => $id_patrocinador,
            "modalidad_envio_sunat"     => 'inmediato', 
            "email_login"               => isset($nuevo_contribuyente['email_login'])?$nuevo_contribuyente['email_login']:'', 
            "password_login"            => $password_login, 
		);

		$gestiondecontribuyentes = new GestiondecontribuyentesController;
		$resp = $gestiondecontribuyentes->guardar($usuario_patrocinador, $datapost);
		echo json_encode($resp);
		exit();
	}

	public function check_password_secure($pwd) {
		if (strlen($pwd) < 8) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_password';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El password del usuario debe tener más de 8 caracteres';
			return $resp;
		}
	
		if (!preg_match("#[0-9]+#", $pwd)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_password';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El password debe incluir al menos un número';
			return $resp;
		}
	
		if (!preg_match("#[a-zA-Z]+#", $pwd)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_password';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El password debe incluir al menos una letra';
			return $resp;
		}     
	
		$resp['respuesta'] = 'ok';
		return $resp;
	}

	public function procesarVentaAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}

		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}
		
		$resp_proceso_venta = $this->procesar_venta($data);
		echo json_encode($resp_proceso_venta);
		exit();
	}

	public function procesar_venta($data) {
		$resp_contribuyente = $this->validar_contribuyente($data);
		if(isset($resp_contribuyente['respuesta']) && $resp_contribuyente['respuesta'] == 'error') {
			return $resp_contribuyente;
		}
		$data = $resp_contribuyente['data'];
		
		$resp_cliente = $this->validar_cliente($data);
		if(isset($resp_cliente['respuesta']) && $resp_cliente['respuesta'] == 'error') {
			return $resp_cliente;
		}
		$data = $resp_cliente['data'];

		$resp_cabecera = $this->validar_cabecera($data);
		if(isset($resp_cabecera['respuesta']) && $resp_cabecera['respuesta'] == 'error') {
			return $resp_cabecera;
		}
		$data = $resp_cabecera['data'];
		$this->factor_igv_sunat = round($data['cabecera_comprobante']['porcentaje_igv']/100, 2);
		
		$resp_detalle = $this->validar_detalle($data, $data['contribuyente']['id_contribuyente'], $data['cabecera_comprobante']['moneda']);
		if(isset($resp_detalle['respuesta']) && $resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}
		$data = $resp_detalle['data']; 

		$resp_totales = $this->calcular_totales($data);
		if(isset($resp_totales['respuesta']) && $resp_totales['respuesta'] == 'error') {
			return $resp_totales;
		}

		$data['totales'] = $resp_totales['totales'];

		$datapost = $this->generar_datapost($data);
		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => intval($data['contribuyente']['id_usuario_vendedor']), 'id_contribuyente' => intval($data['contribuyente']['id_contribuyente']))));
		
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_proceso = $documentoelectronico->procesar_documento_electronico($usuario, $datapost);
		return $resp_proceso;
	}

	public function procesarNotacreditoAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}
		
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}
		
		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$nota_credito = new ApinotacreditoController;
		$resp = $nota_credito->procesar_nota_credito($data, $token);
		echo json_encode($resp);
		exit();
	}

	public function procesarNotadebitoAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}
		
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$nota_debito = new ApinotadebitoController;
		$resp = $nota_debito->procesar_nota_debito($data, $token);
		echo json_encode($resp);
		exit();
	}

	public function procesarGuiaRemisionAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}
		
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$guia_remision = new ApiguiaremisionController;
		$resp_procesar_guia = $guia_remision->procesar_guia_remision($data, $token);
		echo json_encode($resp_procesar_guia);
		exit();
	}

	public function procesarNotaVentaAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}
		
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$nota_de_venta = new ApinotadeventaController;
		$resp = $nota_de_venta->procesar_nota_venta($data, $token);
		echo json_encode($resp);
		exit();
	}

	public function procesarCotizacionAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}
		
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$cotizacion = new ApicotizacionController;
		$resp = $cotizacion->procesar_cotizacion($data, $token);
		echo json_encode($resp);
		exit();
	}

	public function enviarCpeEmailAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}

		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$id_contribuyente = $contribuyente_bd->id_contribuyente;
		$id_tipodoc_electronico = isset($data['tipo_documento'])?$data['tipo_documento']:'';
		$serie_comprobante = isset($data['serie_comprobante'])?$data['serie_comprobante']:'';
		$numero_comprobante = isset($data['numero_comprobante'])?$data['numero_comprobante']:'';
		$tipo_envio_sunat = isset($data['ambiente'])?$data['ambiente']:'';
		$email = isset($data['email'])?$data['email']:'';

		$herramientas = new HerramientasController;
		if(!$herramientas->verificar_validez_email($email)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'email';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Email Inválido!';
			echo json_encode($resp);
			exit();
		}

		if($id_tipodoc_electronico == '77') {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
		} else {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante , 'tipo_envio_sunat' => $tipo_envio_sunat)));
		}

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'documento';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El documento no existe!';
			echo json_encode($resp);
			exit();
		}
		
		$enviar_email = new EnviaremailController;
		$resp_email = $enviar_email->enviar_email_documento($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat, $email);

		$resp['respuesta'] = 'ok';
		$resp['codigo'] = 'email';
		$resp['titulo'] = 'ok';
		$resp['mensaje'] = 'Su Email ha sido enviado correctamente!';
		echo json_encode($resp);
		exit();
	}

	public function calcular_totales($data) {
		
		$apisunat = new ApisunatController;

		$detalle = $data['detalle'];
		$total_gravado = 0;
		$total_exonerado = 0;
		$total_inafecto = 0;
		$total_gratuito = 0;
		$total_exportacion = 0;
		$total_icbper = 0;
		$total_igv = 0;
		$subtotal_ventas = 0;

		foreach($detalle as $item) {
			$item = (object)$item;
			if($item->id_tipoafectacionigv == 10) {
				$total_gravado = $total_gravado + $item->importe;
				$subtotal_ventas = $subtotal_ventas + $item->importe;
			} else if($item->id_tipoafectacionigv == 30) {
				$total_inafecto = $total_inafecto + $item->importe;
				$subtotal_ventas = $subtotal_ventas + $item->importe;
			} else if($item->id_tipoafectacionigv == 40) {
				$subtotal_ventas = $subtotal_ventas + $item->importe;
				$total_exportacion = $total_exportacion + $item->importe;
			} else if($item->id_tipoafectacionigv == 20) {
				$total_exonerado = $total_exonerado + $item->importe;
				$subtotal_ventas = $subtotal_ventas + $item->importe;
			} else {
				$total_gratuito = $total_gratuito + $item->importe;
			}

			if($item->afecto_icbper == 'si') {
				$total_icbper = $total_icbper + $item->subtotal_icbper;
			}
		}
		
		$desc_imponible_gravado = 0;
		$desc_imponible_exonerado = 0;
		$desc_imponible_inafecto = 0;
		$desc_imponible_gratuito = 0;
		$desc_imposible_exportacion = 0;
		$desc_total_imponible = 0;
		$nuevo_total_gravado_sin_igv = $total_gravado/($this->factor_igv_sunat + 1);
		$descuento_factor = $data['cabecera_comprobante']['descuento_porcentaje'] / 100;
		if($descuento_factor > 0) {
			$desc_imponible_gravado = $nuevo_total_gravado_sin_igv*$descuento_factor; //total descuento sin igv
			$desc_imponible_exonerado = $total_exonerado*$descuento_factor;
			$desc_imponible_inafecto = $total_inafecto*$descuento_factor;
			$desc_imposible_exportacion = $total_exportacion*$descuento_factor;
			$desc_total_imponible = $desc_imponible_gravado + $desc_imponible_exonerado + $desc_imponible_inafecto + $desc_imposible_exportacion;
		}

		$total_gravado = $nuevo_total_gravado_sin_igv - $desc_imponible_gravado;
		$total_exonerado = $total_exonerado - $desc_imponible_exonerado;
		$total_inafecto = $total_inafecto - $desc_imponible_inafecto;
		$total_exportacion = $total_exportacion - $desc_imposible_exportacion;

		$otros_cargos = 0;

		$total_igv = $total_gravado*$this->factor_igv_sunat;
		$total_a_pagar = $total_gravado + $total_exonerado + $total_inafecto + $total_exportacion + $total_igv + $otros_cargos + $total_icbper;

		$cabecera = $data['cabecera_comprobante'];
		$monto_dolares_detraccion = 0;
		$monto_detraccion = 0;
		if($cabecera['aplica_detraccion'] == 'si') {
			$porcentaje_detraccion = floatval($cabecera['porcentaje_detraccion']) + 0;
			if($cabecera['moneda'] == 'PEN') {
				$simbolo_moneda = 'S/.';
				$monto_detraccion = round(($porcentaje_detraccion/100)*$total_a_pagar, 2);
				$total_menos_detraccion = $total_a_pagar - $monto_detraccion;
			} else {
				$simbolo_moneda = 'USD';
				$resp_tipo_cambio = $apisunat->get_tipo_cambio(date("Y-m-d"));
				$tipo_cambio = $resp_tipo_cambio['venta'];
				$monto_dolares_detraccion = round(($porcentaje_detraccion/100)*$total_a_pagar, 2);
				$monto_detraccion = round($monto_dolares_detraccion*$tipo_cambio, 2);
				$total_menos_detraccion = $total_a_pagar - $monto_dolares_detraccion;
			}

			if($cabecera['tipo_venta'] == 'credito') {
				//NOTAS IMPORTANTE: ERROR NUMEROS - ERROR COMPARACION NUMEROS
				//cuando ingresaban dos números iguales como 271.80, php devolvía que uno es mayor que el otro, y además también indicaba que son diferentes, la solución fué pasar a float ambos números y redondearlos a dos decimales.
				$monto_deuda_total = floatval($cabecera['monto_deuda_total']) + 0;
				if(round(floatval($monto_deuda_total), 2) > round(floatval($total_menos_detraccion), 2)) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'totales';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'El máximo monto que puede pasar como deuda es: '.$simbolo_moneda.' '.$total_menos_detraccion.', ya que el monto de la detracción no puede pasar como deuda.';
					return $resp;
				}
			}
		}
		
		$totales = array(
			"total_gravado" 			=> round($total_gravado, 2),
			"total_exonerado" 			=> round($total_exonerado, 2),
			"total_inafecto" 			=> round($total_inafecto, 2),
			"total_gratuito" 			=> round($total_gratuito, 2),
			"total_exportacion" 		=> round($total_exportacion, 2),
			"total_icbper" 				=> round($total_icbper, 2),
			"sub_total_ventas" 			=> round($subtotal_ventas, 2),
			"total_igv" 				=> round($total_igv, 2),
			"total_a_pagar" 			=> round($total_a_pagar, 2),
			"desc_total_imponible" 		=> round($desc_total_imponible, 2),
			"desc_total_porcentaje" 	=> round($data['cabecera_comprobante']['descuento_porcentaje'], 5),
			"otros_cargos" 				=> round($otros_cargos, 2),

			"monto_dolares_detraccion"	=> $monto_dolares_detraccion,
			"monto_detraccion"			=> $monto_detraccion
		);

		$resp['respuesta'] = 'ok';
		$resp['totales'] = $totales;
		return $resp;
	}

	public function validar_detalle($data, $id_contribuyente, $moneda) {
		if(!isset($data['detalle'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'detalle';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad detalle';
			return $resp;
		}

		if(!is_array($data['detalle'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'detalle';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Propiedad detalle, debe ser un array';
			return $resp;
		}

		if(count($data['detalle']) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'detalle';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentran items en el detalle';
			return $resp;
		}

		$lista = array();
		$n = 0;
		foreach($data['detalle'] as $item) {
			$n++;
			
			if(!isset($item['afecto_icbper']) || empty($item['afecto_icbper'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/afecto_icbper';
				return $resp;
			}

			if(!isset($item['id_tipoafectacionigv']) || empty($item['id_tipoafectacionigv'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/id_tipoafectacionigv';
				return $resp;
			}

			if(!isset($item['descripcion']) || empty($item['descripcion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/descripcion';
				return $resp;
			}

			if(!isset($item['precio_venta']) || empty($item['precio_venta'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/precio_venta';
				return $resp;
			}

			if(!isset($item['cantidad']) || empty($item['cantidad'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/cantidad';
				return $resp;
			}

			if(!isset($item['codigo']) || empty($item['codigo'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad detalle/codigo';
				return $resp;
			}

			$item['idproducto'] = isset($item['idproducto'])?$item['idproducto']:0;
			$item['idproducto'] = intval($item['idproducto']) + 0;
			$item['idunidadmedida'] = $item['idunidadmedida'];
			$item['id_tipoafectacionigv'] = intval($item['id_tipoafectacionigv']) + 0;
			$item['afecto_icbper'] = ($item['afecto_icbper'] == 'si')?'si':'no';
			$item['cantidad'] = floatval($item['cantidad']) + 0;

			$anio_actual =  date("Y");
			$icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
			$impuesto_icbper = !$icbper?0.5:$icbper->monto;

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

			if($contribuyente->multi_almacen == 'si') {
				$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $item['idproducto'], 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $data['cabecera_comprobante']['idsucursal'])));
			} else {
				$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item['idproducto'], 'id_contribuyente' => $id_contribuyente)));
			}

			if(!$producto) {
				if($contribuyente->multi_almacen == 'si') {
					$producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal: and codigo = :codigo:", 'bind' => array('codigo' => $item['codigo'], 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $data['cabecera_comprobante']['idsucursal'])));
				} else {
					$producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('codigo' => $item['codigo'], 'id_contribuyente' => $id_contribuyente)));
				}

				//quizás también se tenga que buscar validando que sea de la sucursal indicada.
				if(!$producto) {
					$producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('codigo' => 'P_TIENDAVIRTUAL', 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $data['cabecera_comprobante']['idsucursal'])));
					if(!$producto) {
						$resp_prod = $this->crear_producto_general($id_contribuyente, $data['cabecera_comprobante']['idsucursal']);
						if($resp_prod['respuesta'] == 'error') {
							$resp['respuesta'] = 'error';
							$resp['codigo'] = 'detalle_item';
							$resp['titulo'] = 'error';
							$resp['mensaje'] = 'El producto'.$item['descripcion'].', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
							return $resp;
						}
		
						$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $resp_prod['id_producto'], 'id_contribuyente' => $id_contribuyente)));
					}
				}

				$item['idproducto'] = $producto->idproducto;
			}
			
			if($item['idunidadmedida'] == 'ZZ' || $item['idunidadmedida'] == 'NIU') {
				$item['idunidadmedida'] = isset($item['idunidadmedida'])?($item['idunidadmedida']=='NIU'?'NIU':'ZZ'):'NIU';
				$item['idunidadmedida'] = $item['idunidadmedida']=='ZZ'?20:7;
			} else {
				if(intval($item['idunidadmedida']) > 0) {
					$item['idunidadmedida'] = intval($item['idunidadmedida']) + 0;
				} else {
					$unidadmedida = SunatUnidadmedida::findFirst(array("codigo = :codigo:", 'bind' => array('codigo' => $item['idunidadmedida'])));
					if(!$unidadmedida) {
						$item['idunidadmedida'] = $producto->id_unidad_medida;
					} else {
						$item['idunidadmedida'] = $unidadmedida->idunidad;
					}
				}
			}
			
			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item['idunidadmedida'])));
			if(!$unidadmedida) {
				$item['idunidadmedida'] = $producto->id_unidad_medida;
				/*
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la unidad seleccionada para el producto '.$item['descripcion'];
				return $resp;
				*/
			}

			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item['id_tipoafectacionigv'])));
			if(!$tipoafectacionigv) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto '.$item['descripcion'];
				return $resp;
			}

			if($item['cantidad'] <= 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'detalle_item';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La cantidad no debe ser igual o menor a cero para el producto '.$item['descripcion'];
				return $resp;
			}

			$subtotal_icbper = 0;
			if($item['afecto_icbper'] == 'si') {
				$subtotal_icbper = round($impuesto_icbper*$item['cantidad'], 5);
			}

			$igv = 0;
			$subtotal = 0;
			if($item['id_tipoafectacionigv'] == 10) { //operacion gravada (incl.igv)
				if($item['precio_venta'] > 0 && $item['cantidad'] > 0) {
					$subtotal = round(($item['cantidad']*$item['precio_venta'])/(1 + $this->factor_igv_sunat), 2);
					$igv = round($item['cantidad']*$item['precio_venta'] - $subtotal, 2);
				}
			} else {
				$subtotal = round($item['cantidad']*$item['precio_venta'], 2);
				$igv = 0;
			}

			$item_nuevo = array(
				"row_identificadador" 			=> $n,
				"idarticulo" 					=> $item['idproducto'],
				"afecto_icbper" 				=> $item['afecto_icbper'],
				"subtotal_icbper" 				=> $subtotal_icbper,
				"id_tipoafectacionigv" 			=> $item['id_tipoafectacionigv'],
				"descripcion" 					=> $item['descripcion'],
				"text_tipo_afecigv" 			=> $tipoafectacionigv->descripcion,
				"idunidadmedida" 				=> $item['idunidadmedida'],
				"unidadmedida" 					=> $unidadmedida->nombre,
				"id_cod_moneda" 				=> $moneda,
				"cantidad" 						=> $item['cantidad'],
				"precio" 						=> $item['precio_venta'],
				"subtotal" 						=> $subtotal,
				"igv" 							=> $igv,
				"importe" 						=> $subtotal + $igv,
				"estado" 						=> "V",
				"codigo" 						=> $item['codigo'],
				"html_lista_precios" 			=> "",
				"item_detraccion_codigo" 		=> "",
				"item_detraccion_porcentaje" 	=> "",
				'tipo_unidad' 					=> "unidad",
				'factor_igv_sunat' 				=> $this->factor_igv_sunat //se debe enviar en decimal, tal cuál se envía desde la pantalla de creación cpe
			);

			$lista[] = $item_nuevo;
		}

		$data['detalle'] = $lista;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function crear_producto_general($id_contribuyente, $id_sucursal) {
		$product = new Producto();
		$product->fecha_registro 		=  date('Y-m-d H:i:s');
		$product->id_contribuyente 		= $id_contribuyente;
		$product->idsucursal 			= $id_sucursal;

		$product->stock 				= 0;
		$product->precio_compra 		= 1;

		$product->valor_sin_igv 		= 10; //$valor_sin_igv
		$product->valor_con_igv 		= 11.8;
		
		$product->fecha_vencimiento 	= null;
		$product->marca 				= null;
		$product->codigo 				= 'P_TIENDAVIRTUAL';
		$product->id_unidad_medida 		= 7;
		$product->id_cod_detraccion 	= '022';
		$product->id_tipoafectacionigv 	= 10;
		$product->id_categoria 			= null;
		$product->nombre 				= 'PRODUCTO TIENDA VIRTUAL';
		$product->id_cod_moneda 		= 'PEN';
		$product->foto 					= null;
		$product->nota 					= null;
		$product->stock_minimo 			= 1;
		$product->tipo_cambio_sunat 	= 3.981; 
		$product->precio_venta_minimo 	= 5;
		$product->multi_precio 			= 'no';
		$product->afecto_icbper 		= 'no';
		$product->estado 				= 'activo';

		try {
			if(!$product->save()) {
				$resp['respuesta'] = 'error';
				return $resp;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['id_producto'] = $product->idproducto;
		return $resp;
	}

	public function validar_cabecera($data) {
		$herramientas = new HerramientasController;
		
		if(!isset($data['cabecera_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante';
			return $resp;
		}

		$cabecera = $data['cabecera_comprobante'];

		if(!isset($cabecera['tipo_documento'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->tipo_documento';
			return $resp;
		}

		if(!isset($cabecera['moneda'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->moneda';
			return $resp;
		}

		if(!isset($cabecera['idsucursal'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->idsucursal';
			return $resp;
		}

		$id_contribuyente = $data['contribuyente']['id_contribuyente'];
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		if(intval($cabecera['idsucursal']) > 0) {
			$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idsucursal' => intval($cabecera['idsucursal']), 'id_contribuyente' => $id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->idsucursal';
				return $resp;
			}
		} else {
			$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->idsucursal';
				return $resp;
			}

			$cabecera['idsucursal'] = $sucursal->idsucursal;
		}
		

		if(!isset($cabecera['id_condicionpago'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->id_condicionpago';
			return $resp;
		}

		if(!isset($cabecera['fecha_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->fecha_comprobante';
			return $resp;
		}

		$array_fecha_documento = explode('/',!isset($cabecera['fecha_comprobante'])?date('d/m/Y'):$cabecera['fecha_comprobante']);
		if(!isset($array_fecha_documento[2]) || !isset($array_fecha_documento[1]) || !isset($array_fecha_documento[0])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha asignada al documento no es válida!';
			return $resp;
		}

		$fecha_documento = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
		if (!(DateTime::createFromFormat('Y-m-d', $fecha_documento) !== FALSE)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La propiedad cabecera_comprobante->fecha_comprobante tiene un valor inválido: El formato para la fecha del documento debe ser: dia/mes/año';
			return $resp;
		}

		$fecha_actual = date('Y-m-d', strtotime('today'));
		$resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $fecha_documento);
		$diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];

		if($cabecera['tipo_documento'] == '01' || $cabecera['tipo_documento'] == '07' || $cabecera['tipo_documento'] == '08') {
			if($diferencia_dias > 2) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_factura';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La Fecha Asignada al Documento No Debe Ser Mayor a 2 Días atrás.';
				return $resp;
			}
		} else if($cabecera['tipo_documento'] == '03') {
			if($diferencia_dias > 30) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_factura';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La Fecha Asignada al Documento No Debe Ser Mayor a 30 Días atrás.';
				return $resp;
			}
		} else {
			if($diferencia_dias > 2) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_factura';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La Fecha Asignada al Documento No Debe Ser Mayor a 3 Días atrás.';
				return $resp;
			}
		}

		if(!isset($cabecera['nro_placa'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->nro_placa';
			return $resp;
		}

		if(!isset($cabecera['nro_orden'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->nro_orden';
			return $resp;
		}

		if(!isset($cabecera['guia_remision'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->guia_remision';
			return $resp;
		}

		if(!isset($cabecera['descuento_porcentaje'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->descuento_porcentaje';
			return $resp;
		}

		if(!isset($cabecera['observacion'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->observacion';
			return $resp;
		}

		if($cabecera['moneda'] != 'USD' && $cabecera['moneda'] != 'PEN') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->moneda';
			return $resp;
		}

		if($cabecera['tipo_documento'] != '03' && $cabecera['tipo_documento'] != '01') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->tipo_documento';
			return $resp;
		}

		if(empty($cabecera['descuento_porcentaje'])) {
			$cabecera['descuento_porcentaje'] = 0;
		}
		$cabecera['descuento_porcentaje'] = empty($cabecera['descuento_porcentaje'])?0:$cabecera['descuento_porcentaje'];
		$cabecera['descuento_porcentaje'] = round(floatval($cabecera['descuento_porcentaje']), 5);
		if($cabecera['descuento_porcentaje'] < 0 || $cabecera['descuento_porcentaje'] > 100) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->descuento_porcentaje';
			return $resp;
		}

		$cabecera['aplica_detraccion'] = isset($cabecera['aplica_detraccion'])?$cabecera['aplica_detraccion']:'no';
		$cabecera['aplica_detraccion'] = ($cabecera['aplica_detraccion'] == 'si')?'si':'no';
		
		if($cabecera['aplica_detraccion'] == 'si') {
			if(!isset($cabecera['tipo_operacion'])) { //1001
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->tipo_operacion, y si en caso es un comprobante de detracción entonces el valor debe ser: 1001';
				return $resp;
			}

			if($cabecera['tipo_operacion'] != '1001') {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'Encontramos que la propiedad "aplica_detraccion" tiene el valor: si, entonces el tipo_operacion debe ser igual a: 1001, que indica que el comprobante es un comprobante de detracción.';
				return $resp;
			}

			/*
			if(!isset($cabecera['detraccion_tipo_pago'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->detraccion_tipo_pago';
				return $resp;
			}

			if($cabecera['detraccion_tipo_pago'] != '001') {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'detraccion_tipo_pago debe ser igual a: 001';
				return $resp;
			}
			*/

			$cabecera['detraccion_tipo_pago'] = '001';
			
			if(!isset($cabecera['detraccion_id_numero_cuenta'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->detraccion_id_numero_cuenta, en el sistema debes crear la cuenta de detracción, y se debe indicar el ID de la cuenta.';
				return $resp;
			}

			$cuenta_banco_detraccion = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco: and id_contribuyente = :id_contribuyente: and id_codigomoneda = :id_codigomoneda: and tipo_cuenta = :tipo_cuenta: and id_entidadfinanciera = :id_entidadfinanciera: and estado = 'activo'", 'bind' => array('id_cuentabanco' => intval($cabecera['detraccion_id_numero_cuenta']), 'id_contribuyente' => $id_contribuyente, 'id_codigomoneda' => 'PEN', 'tipo_cuenta' => 'cuenta_detracciones', 'id_entidadfinanciera' => '18')));
			if(!$cuenta_banco_detraccion) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La propiedad cabecera_comprobante->detraccion_id_numero_cuenta tiene un valor inválido, recuerda que debes crear la cuenta de detracción en tu sistema de facturación, la cuenta de detracción debe ser en soles y del banco de la nación.';
				return $resp;
			}
			
			if(!isset($cabecera['detraccion_codigo_bien'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->detraccion_codigo_bien';
				return $resp;
			}

			$sunat_codigo_bien = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $cabecera['detraccion_codigo_bien'])));
			if(!$sunat_codigo_bien) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La propiedad cabecera_comprobante->detraccion_codigo_bien tiene un valor erróneo.';
				return $resp;
			}

			/*
			if(!isset($cabecera['porcentaje_detraccion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->porcentaje_detraccion';
				return $resp;
			}
			*/

			$cabecera['porcentaje_detraccion'] = floatval($sunat_codigo_bien->porcentaje) + 0;

			/*
			if(!isset($cabecera['texto_detraccion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->texto_detraccion';
				return $resp;
			}
			*/

			$cabecera['texto_detraccion'] = "OPERACION SUJETA AL SISTEMA DE PAGO OBLIGACIONES TRIBUTARIAS DEL BANCO DE LA NACION";
		}

		//validación de venta al crédito
		$cabecera['fecha_pago_comprobante'] = '';
		if(isset($cabecera['tipo_venta']) && $cabecera['tipo_venta'] == 'credito') {
			if(!isset($cabecera['monto_deuda_total']) || floatval($cabecera['monto_deuda_total']) <= 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No existe la propiedad cabecera_comprobante->monto_deuda_total';
				return $resp;
			}

			if(!isset($cabecera['lista_cuotas'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No existe la propiedad cabecera_comprobante->lista_cuotas';
				return $resp;
			}

			if(!is_array($cabecera['lista_cuotas'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La propiedad cabecera_comprobante->lista_cuotas debería ser un array';
				return $resp;
			}
	
			if(count($cabecera['lista_cuotas']) <= 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No existe la propiedad cabecera_comprobante->lista_cuotas';
				return $resp;
			}

			$suma_cuotas = 0;
			foreach($cabecera['lista_cuotas'] as $cuota) {
				if(!is_array($cuota)) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cabecera_comprobante';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cabecera_comprobante->lista_cotas->cuota debería ser un array';
					return $resp;
				}

				if(!isset($cuota['vencimiento_cuota']) || empty($cuota['vencimiento_cuota'])) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cabecera_comprobante';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cabecera_comprobante->lista_cotas->cuota->vencimiento_cuota no existe';
					return $resp;
				}

				if(!isset($cuota['monto_cuota']) || empty($cuota['monto_cuota']) || floatval($cuota['monto_cuota']) <= 0) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cabecera_comprobante';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cabecera_comprobante->lista_cotas->cuota->monto_cuota no existe';
					return $resp;
				}

				$array_fecha_cuota = explode('/', $cuota['vencimiento_cuota']); //25-11-2025
				if(!isset($array_fecha_cuota[2]) || !isset($array_fecha_cuota[1]) || !isset($array_fecha_cuota[0])) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cabecera_comprobante';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cabecera_comprobante->lista_cotas->cuota->vencimiento_cuota no es válida';
					return $resp;
				}

				$fecha_cuota = $array_fecha_cuota[2].'-'.$array_fecha_cuota[1].'-'.$array_fecha_cuota[0];
				if (!(DateTime::createFromFormat('Y-m-d', $fecha_cuota) !== FALSE)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La propiedad cabecera_comprobante->lista_cotas->cuota->vencimiento_cuota tiene un valor inválido';
					return $resp;
				}

				$ultima_fecha_vencimiento = $cuota['vencimiento_cuota'];
				$suma_cuotas = $suma_cuotas + floatval($cuota['monto_cuota']);
			} 

			if($suma_cuotas != floatval($cabecera['monto_deuda_total'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La propiedad cabecera_comprobante->monto_deuda_total no es igual a la suma de las cuotas.';
				return $resp;
			}

			$cabecera['fecha_pago_comprobante'] = $ultima_fecha_vencimiento;
			$cabecera['tipo_venta'] = 'credito';
		} else {
			$cabecera['tipo_venta'] = 'contado';
		}

		if(isset($cabecera['porcentaje_igv'])) {
			$cabecera['porcentaje_igv'] = intval($cabecera['porcentaje_igv']) + 0;
			if($cabecera['porcentaje_igv'] != 18 && $cabecera['porcentaje_igv'] != 10) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'Valor inválido para la propiedad cabecera_comprobante->porcentaje_igv, solo se aceptan los siguientes valores (18 y 10)';
				return $resp;
			}
		} else {
			//si no existe entonces se considera siempre el 18%
			$cabecera['porcentaje_igv'] = 18;
		}

		//RETENCIÓN

		if(isset($cabecera['aplica_retencion']) && $cabecera['aplica_retencion'] == 'si') {
			if(isset($cabecera['aplica_detraccion']) && $cabecera['aplica_detraccion'] == 'si') {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se permite la creación de una factura de detracción y retención en el mismo documento.';
				return $resp;
			}

			if(!isset($cabecera['data_retencion']['porcentaje_retencion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No la propiedad cabecera_comprobante->data_retencion->porcentaje_retencion, el porcentaje de la retención debe ser un valor mayor a cero y menor a 100';
				return $resp;
			}

			if(floatval($cabecera['data_retencion']['porcentaje_retencion']) < 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para cabecera_comprobante->data_retencion->porcentaje_retencion, el porcentaje debe ser un valor mayor a cero y menor a 100';
				return $resp;
			}

			if(!isset($cabecera['data_retencion']['base_imponible_retencion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No la propiedad cabecera_comprobante->data_retencion->base_imponible_retencion, debe ser el monto total del comprobante';
				return $resp;
			}

			if(floatval($cabecera['data_retencion']['base_imponible_retencion']) < 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para cabecera_comprobante->data_retencion->base_imponible_retencion, se debe ingresar exactamente el monto total del comprobante';
				return $resp;
			}

			if(!isset($cabecera['data_retencion']['monto_retencion'])) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No la propiedad cabecera_comprobante->data_retencion->monto_retencion. (con máximo dos decimales)';
				return $resp;
			}

			if(floatval($cabecera['data_retencion']['monto_retencion']) < 0) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_comprobante';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra un valor válido para cabecera_comprobante->data_retencion->monto_retencion, (porcentaje/100)*base_imponible_retencion) redondeado a dos decimales.';
				return $resp;
			}
		} else {
			$cabecera['aplica_retencion'] = 'no';
		}

		$data['cabecera_comprobante'] = $cabecera;
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_cliente($data) {
		if(!isset($data['cliente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente';
			return $resp;
		}

		$cliente = $data['cliente'];

		if(!isset($cliente['tipo_docidentidad'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente->tipo_docidentidad';
			return $resp;
		}

		if(!isset($cliente['numerodocumento'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente->tipo_docidentidad';
			return $resp;
		}

		if(!isset($cliente['nombre'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cliente->tipo_docidentidad';
			return $resp;
		}

		if(intval($cliente['tipo_docidentidad']) != 0 && intval($cliente['tipo_docidentidad']) != 1 && intval($cliente['tipo_docidentidad']) != 6 && intval($cliente['tipo_docidentidad']) != 4) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cliente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad cliente->tipo_docidentidad tiene un valor inválido';
			return $resp;
		}

		$emiclientesor['tipo_docidentidad'] = intval($cliente['tipo_docidentidad']) + 0;

		if($cliente['tipo_docidentidad'] != 0) {
			if($cliente['tipo_docidentidad'] == 1) { //dni
				if(strlen($cliente['numerodocumento']) != 8) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->numerodocumento tiene un valor inválido (valor enviado: '.$cliente['numerodocumento'].'), se indicó que el número de documento es un DNI, por tanto únicamente debe tener 8 dígitos';
					return $resp;
				}
			}

			if($cliente['tipo_docidentidad'] == 6) { //dni
				if(strlen($cliente['numerodocumento']) != 11) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->numerodocumento tiene un valor inválido';
					return $resp;
				}
			}

			if($cliente['tipo_docidentidad'] == 4) { //dni
				$cliente['numerodocumento'] = preg_replace('/\s+/', '', $cliente['numerodocumento']);
                $cliente['numerodocumento'] = preg_replace('/[^A-Za-z0-9]/', '', $cliente['numerodocumento']);
			}
		}

		$herramientas = new HerramientasController;

		if(isset($cliente['email'])) {
			if($cliente['email'] != '') {
				if(!$herramientas->verificar_validez_email($cliente['email'])) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->email tiene un valor inválido';
					return $resp;
				}
			}
		}

		if(isset($cliente['fecha_nac'])) {
			if($cliente['fecha_nac'] != '') {
				if(!$herramientas->validate_date_spanish($cliente['fecha_nac'])) {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->fecha_nac tiene un valor inválido';
					return $resp;
				}
			}
		}

		$cliente['fecha_nac'] = strtoupper($cliente['fecha_nac']);

		if(isset($cliente['sexo'])) {
			if($cliente['sexo'] != '') {
				if(strtolower($cliente['sexo']) != 'masculino' && strtolower($cliente['sexo']) != 'femenino') {
					$resp['respuesta'] = 'error';
					$resp['codigo'] = 'cliente';
					$resp['titulo'] = 'error';
					$resp['mensaje'] = 'La propiedad cliente->sexo tiene un valor inválido';
					return $resp;
				}		
			}
		}

		$data['cliente'] = $cliente;
		
		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function validar_contribuyente($data) {
		if(!isset($data['contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente..';
			$resp['data']	= $data;
			return $resp;
		}

		$contribuyente = $data['contribuyente'];

		if(!isset($contribuyente['token_contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->token_contribuyente';
			return $resp;
		}

		/*
		if(!isset($contribuyente['id_contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->id_contribuyente';
			return $resp;
		}
		*/

		/*
		if(!isset($contribuyente['id_usuario_vendedor'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->id_usuario_vendedor';
			return $resp;
		}
		*/

		if(!isset($contribuyente['tipo_proceso'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->tipo_proceso';
			return $resp;
		}

		/*
		if(intval($contribuyente['id_contribuyente']) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->id_contribuyente';
			return $resp;
		}
		*/
		
		//$contribuyente['id_contribuyente'] = intval($contribuyente['id_contribuyente']);
		
		if(empty($contribuyente['token_contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->token_contribuyente';
			return $resp;
		}

		if(!isset($contribuyente['tipo_envio'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->tipo_envio';
			return $resp;
		}

		if($contribuyente['tipo_proceso'] != 'prueba' && $contribuyente['tipo_proceso'] != 'produccion') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->tipo_proceso tiene un valor inválido';
			return $resp;
		}

		if($contribuyente['tipo_envio'] != 'solo_firma' && $contribuyente['tipo_envio'] != 'inmediato' && $contribuyente['tipo_envio'] != 'no_enviar') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->tipo_envio tiene un valor inválido (valor enviado: '.$contribuyente['tipo_envio'].')';
			return $resp;
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $contribuyente['token_contribuyente'])));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->token tiene un valor inválido';
			return $resp;
		}

		if(empty($contribuyente['id_usuario_vendedor']) || intval($contribuyente['id_usuario_vendedor']) <= 0) {
			$usuario_administrador = Usuario::findFirst(array("id_rol in (1, 2, 3, 4, 5) and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente)));
			if(!$usuario_administrador) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'contribuyente';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No Existe un Usuario Válido para la Cuenta';
				return $resp;
			}

			$contribuyente['id_usuario_vendedor'] = $usuario_administrador->idusuario;
		}

		$contribuyente['id_usuario_vendedor'] = intval($contribuyente['id_usuario_vendedor']);
		
		$usuario_bd = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $contribuyente['id_usuario_vendedor'], 'id_contribuyente' => $contribuyente_bd->id_contribuyente)));
		if(!$usuario_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La propiedad contribuyente->id_usuario_vendedor tiene un valor inválido';
			return $resp;
		}
		
		$html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($usuario_bd->idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'La Suscripción no es válida, debe renovar su suscripción...';
			return $resp;
		}

		$contribuyente['id_contribuyente'] = $contribuyente_bd->id_contribuyente;
		$data['contribuyente'] = $contribuyente;

		$resp['respuesta'] = 'ok';
		$resp['data'] = $data;
		return $resp;
	}

	public function generar_datapost($data) {
		$cabecera = (object)$data['cabecera_comprobante'];
		$cliente = (object)$data['cliente'];
		$contribuyente = (object)$data['contribuyente'];
		$totales = (object)$data['totales'];
		$detalle = $data['detalle'];

		$apisunat = new ApisunatController;
		$resp_tipo_cambio = $apisunat->get_tipo_cambio(date("Y-m-d"));
		$tipo_cambio = $resp_tipo_cambio['venta'];

		$anio_actual =  date("Y");
		$icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
		$impuesto_icbper = !$icbper?0.5:$icbper->monto;

		$convertir_numero_letras = new NumeroALetras();
		$total_a_pagar_letras = ($cabecera->moneda == 'USD')?$convertir_numero_letras->convert(floatval($totales->total_a_pagar) , 'dólares'):$convertir_numero_letras->convert(floatval($totales->total_a_pagar) , 'soles');

		$data_cuotas = array();
		$opcion_tipo_venta = 'contado';
		$txt_monto_adeudado = 0;
		if(isset($cabecera->tipo_venta) && $cabecera->tipo_venta == 'credito') {
			$data_cuotas = array(
				"num_cuotas"			=> count($cabecera->lista_cuotas),
				"monto_total_cuotas"	=> $cabecera->monto_deuda_total,
				"cuotas"				=> $cabecera->lista_cuotas
			);
			$opcion_tipo_venta = 'credito';
			$txt_monto_adeudado = $cabecera->monto_deuda_total;
		}

		$array_tipo_operacion_validos = array('0101', '0200', '0201', '1001');
		$tipo_operacion_docelectronico = "0101";
		if(isset($cabecera->tipo_operacion)) {
			if(in_array($cabecera->tipo_operacion, $array_tipo_operacion_validos)) {
				$tipo_operacion_docelectronico = $cabecera->tipo_operacion;
			}
		}

		$opcion_envio_email = 'no';
		if(isset($cliente->email) && !empty($cliente->email)) {
		    $opcion_envio_email = 'si';
			if($cliente->email == '') {
				$opcion_envio_email = 'no';
			}

			$validaciones = new Validaciones();
			if(!$validaciones->validate_email($cliente->email)) {
				$opcion_envio_email = 'no';
			}
		}
		
		$datapost = array(
			//cabecera del documento
			"tipo_operacion_docelectronico" 	=> $tipo_operacion_docelectronico,
			"select_tipo_doc_electronico" 		=> !isset($cabecera->tipo_documento)?'':$cabecera->tipo_documento, //{03" => "boleta, 01" => "factura}
			"codmoneda_comprobante" 			=> !isset($cabecera->moneda)?'':$cabecera->moneda, //{PEN, USD}
			"select_sucursal" 					=> !isset($cabecera->idsucursal)?'':$cabecera->idsucursal, //debe existir en la base de datos
			"serie_comprobante" 				=> "-",
			"numero_comprobante" 				=> "-",
			"fecha_comprobante" 				=> !isset($cabecera->fecha_comprobante)?'':$cabecera->fecha_comprobante, // formato" => "dia/mes/año
			"fecha_vence_comprobante" 			=> !isset($cabecera->fecha_comprobante)?'':$cabecera->fecha_comprobante,
			"tipo_cambio_comprobante" 			=> $tipo_cambio, //
			"nro_placa_vehiculo" 				=> !isset($cabecera->nro_placa)?'':$cabecera->nro_placa,
			"nro_orden" 						=> !isset($cabecera->nro_orden)?'':$cabecera->nro_orden,
			"guia_remision_manual" 				=> !isset($cabecera->guia_remision)?'':$cabecera->guia_remision,
			"select_usuario_vendedor" 			=> !isset($contribuyente->id_usuario_vendedor)?'':$contribuyente->id_usuario_vendedor,
			"modalidad_envio_sunat" 			=> !isset($contribuyente->tipo_envio)?'':$contribuyente->tipo_envio,
			"doc_impuesto_icbper" 				=> $impuesto_icbper,

			"enviar_a_sunat"					=> !isset($cabecera->enviar_a_sunat)?'si':$cabecera->enviar_a_sunat,

			//Data del Cliente
			"id_cliente_documento" 				=> '',
			"cliente_tipo_docidentidad" 		=> !isset($cliente->tipo_docidentidad)?'':$cliente->tipo_docidentidad,
			"cliente_numerodocumento" 			=> !isset($cliente->numerodocumento)?'':$cliente->numerodocumento,
			"cliente_nombre" 					=> !isset($cliente->nombre)?'':$cliente->nombre,
			"cliente_direccion" 				=> !isset($cliente->direccion)?'':$cliente->direccion,
			"numero_celular" 					=> !isset($cliente->celular)?'':$cliente->celular,
			"cliente_api_foto" 					=> !isset($cliente->foto)?'':$cliente->foto,
			"cliente_api_fecha_nac" 			=> !isset($cliente->fecha_nac)?'':$cliente->fecha_nac,
			"cliente_api_sexo" 					=> !isset($cliente->sexo)?'':$cliente->sexo,
			"cliente_email" 					=> !isset($cliente->email)?'':$cliente->email,
			"opcion_envio_email" 				=> $opcion_envio_email,

			//cabecera del documento datos debajo del detalle
			"opcion_tipo_venta" 				=> $opcion_tipo_venta,
			"confirmacion" 						=> 'si',
			"fecha_pago_comprobante" 			=> !isset($cabecera->fecha_pago_comprobante)?'':$cabecera->fecha_pago_comprobante,
			"txt_monto_adeudado" 				=> $txt_monto_adeudado,
			"data_cuotas" 						=> json_encode($data_cuotas),
			"txt_pago_parcial" 					=> "",
			"condicionpago_comprobante" 		=> !isset($cabecera->id_condicionpago)?'':$cabecera->id_condicionpago,
			"txt_numero_operacion" 				=> "",
			"observacion_documento" 			=> !isset($cabecera->observacion)?'':$cabecera->observacion,
			"tipo_docelectronico_modificar" 	=> "",
			"id_motivo_nota_credito" 			=> "",
			"id_motivo_nota_debito" 			=> "",
		
			//opción de descuento 
			"opcion_tipo_descuento" 			=> ($totales->desc_total_porcentaje > 0)?'on':'',
			"txt_descuento_total" 				=> !isset($totales->desc_total_imponible)?0:$totales->desc_total_imponible,
			"txt_descuento_porcentaje" 			=> !isset($totales->desc_total_porcentaje)?0:$totales->desc_total_porcentaje,

			//totales documento
			"txt_sub_total_ventas" 				=> !isset($totales->sub_total_ventas)?0:$totales->sub_total_ventas,
			"txt_gravada_comprobante" 			=> !isset($totales->total_gravado)?0:$totales->total_gravado,
			"txt_exonerada_comprobante" 		=> !isset($totales->total_exonerado)?0:$totales->total_exonerado,
			"txt_inafecta_comprobante" 			=> !isset($totales->total_inafecto)?0:$totales->total_inafecto,
			"txt_exportacion_comprobante" 		=> !isset($totales->total_exportacion)?0:$totales->total_exportacion,
			"txt_descuento_comprobante" 		=> !isset($totales->desc_total_imponible)?0:$totales->desc_total_imponible,
			"txt_igv_comprobante" 				=> !isset($totales->total_igv)?0:$totales->total_igv,
			"txt_gratuita_comprobante" 			=> !isset($totales->total_gratuito)?0:$totales->total_gratuito,
			"txt_icbper_comprobante" 			=> !isset($totales->total_icbper)?0:$totales->total_icbper,
			"txt_otros_cargos_comprobante" 		=> !isset($totales->otros_cargos)?0:$totales->otros_cargos,
			"txt_total_comprobante" 			=> !isset($totales->total_a_pagar)?0:$totales->total_a_pagar,
			"txt_total_letras" 					=> $total_a_pagar_letras,
			"detalle" 							=> json_encode($detalle),


			
			//data extra no utilizada para facturas y boletas
			"total_recibido" 						=> "",
			"txt_otros_cargos_comprobante_input" 	=> "0.0",
			"tipo_percepcion" 						=> "51",
			"monto_base_percepcion" 				=> "",
			"porcentaje_percepcion" 				=> "",
			"monto_percepcion" 						=> "",

			//Data de la detracción
			"detraccion_tipo_pago" 					=> !isset($cabecera->detraccion_tipo_pago)?'':$cabecera->detraccion_tipo_pago,
			"detraccion_id_numero_cuenta" 			=> !isset($cabecera->detraccion_id_numero_cuenta)?'':$cabecera->detraccion_id_numero_cuenta,
			"detraccion_codigo_bien" 				=> !isset($cabecera->detraccion_codigo_bien)?'':$cabecera->detraccion_codigo_bien,
			"porcentaje_detraccion" 				=> !isset($cabecera->porcentaje_detraccion)?'':$cabecera->porcentaje_detraccion,
			"monto_dolares_detraccion"				=> !isset($totales->monto_dolares_detraccion)?0:$totales->monto_dolares_detraccion,
			"monto_detraccion" 						=> !isset($totales->monto_detraccion)?0:$totales->monto_detraccion,
			"texto_detraccion" 						=> !isset($cabecera->texto_detraccion)?'':$cabecera->texto_detraccion,

			//data para la retención
			"select_aplica_retencion"				=> !isset($cabecera->aplica_retencion)?'no':$cabecera->aplica_retencion,
			"porcentaje_retencion"					=> !isset($cabecera->data_retencion['porcentaje_retencion'])?0:$cabecera->data_retencion['porcentaje_retencion'],
			"base_imponible_retencion"				=> !isset($cabecera->data_retencion['base_imponible_retencion'])?0:$cabecera->data_retencion['base_imponible_retencion'],
			"monto_retencion"						=> !isset($cabecera->data_retencion['monto_retencion'])?0:$cabecera->data_retencion['monto_retencion'],

			"doc_guardado_id_tipodoc_electronico" 	=> "",
			"doc_guardado_serie_comprobante"		=> "",
			"doc_guardado_numero_comprobante" 		=> "",
			"tipo_doc_guardado" 					=> "",
			"numero_doc_guardado" 					=> "",
			"serie_doc_guardado" 					=> "",
			"doc_modifica_id_tipodoc_electronico" 	=> "",
			"doc_modifica_serie_comprobante" 		=> "",
			"doc_modifica_numero_comprobante" 		=> "",
			"flexdatalist-cliente_numerodocumento" 	=> "",
			"flexdatalist-cliente_nombre" 			=> "",

			"select_igv_sunat"						=> isset($cabecera->porcentaje_igv)?$cabecera->porcentaje_igv:18,
			"origen_data"							=> isset($data['origen_data'])?$data['origen_data']:'api',
		);
		
		return $datapost;
	}

	public function buscarDataClienteAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		$this->view->disable();

		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);

		$token = isset($data['token'])?$data['token']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}

		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Su cuenta no está habilitada para búsquedas';
			echo json_encode($resp);
			exit();
		} 

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Su cuenta no está habilitada para búsquedas';
			$resp['token'] = $token; 
			$resp['data'] = $data;
			echo json_encode($resp);
			exit();
		}

		$tipo_doc = isset($data['tipo_doc'])?$data['tipo_doc']:'';
		$num_doc = isset($data['num_doc'])?$data['num_doc']:'';

		if(empty($tipo_doc) || empty($num_doc)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'datos inválidos';
			echo json_encode($resp);
			exit();
		}

		if($tipo_doc != 'dni' && $tipo_doc != 'ruc') {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'datos inválidos';
			echo json_encode($resp);
			exit();
		}

		//validar si tiene una suscripción activa!
		$herramientas = new HerramientasController;
		if($tipo_doc == 'ruc') {
			$resp = $herramientas->get_data_api_busquedas('ruc', $num_doc);
		} else {
			$resp = $herramientas->get_data_api_busquedas('dni', $num_doc, $contribuyente_bd->tipo_busqueda_doc);
		}
		
		echo json_encode($resp);
		exit();
	}
	
	public function getAuthorizationHeader(){
		$headers = null;
		if (isset($_SERVER['Authorization'])) {
			$headers = trim($_SERVER["Authorization"]);
		} 
		else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
			$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			//print_r($requestHeaders);
			if (isset($requestHeaders['Authorization'])) {
				$headers = trim($requestHeaders['Authorization']);
			}
		}
		return $headers;
	}
	
	public function getBearerToken() {
		$headers = $this->getAuthorizationHeader();
		// HEADER: Get the access token from the header
		//I would recommend to use the following RegEx to check, if it's a valid jwt-token:
		// ==> /Bearer\s((.*)\.(.*)\.(.*))/

		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		return null;
	}

	public function validarconfigpluginAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);

		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}

		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Su cuenta no está habilitada para búsquedas';
			$resp['token'] = $token;
			$resp['data'] = $data;
			echo json_encode($resp);
			exit();
		}

		$id_sucursal = isset($data['id_sucursal'])?intval($data['id_sucursal']):0;
		$id_vendedor = isset($data['id_vendedor'])?intval($data['id_vendedor']):0;
		$moneda = isset($data['moneda'])?$data['moneda']:'';
		$tipo_cambio = isset($data['id_sucursal'])?intval($data['id_sucursal']):0;
		$unidad_medida = isset($data['unidad_medida'])?$data['unidad_medida']:'';

	}

	public function getProductosAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		//$token = $this->getBearerToken();//isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}

		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$limite_inferior = !isset($data['limite_inferior'])?0:intval($data['limite_inferior']);
		$limite_superior = !isset($data['limite_superior'])?10:intval($data['limite_superior']);
		if($limite_superior < $limite_inferior) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'limites';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El límite superior siempre debe ser mayor al límite inferior';
			echo json_encode($resp);
			exit();
		}

		$diferencia = abs($limite_superior - $limite_inferior);
		if($diferencia > 500) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'limites';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se pueden extraer más de 500 productos';
			echo json_encode($resp);
			exit();
		}

		$sql = "SELECT idproducto FROM producto WHERE id_contribuyente = :id_contribuyente LIMIT $limite_inferior, $limite_superior";
		
		try {
			$sentencia = $this->db->prepare($sql);
			$sentencia->bindParam(':id_contribuyente', $contribuyente_bd->id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada (getProductosAction): ',  $e->getMessage(), "\n";
			exit();
		}

		$lista_productos = array();
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$lista_productos[] = $this->get_array_producto($contribuyente_bd->id_contribuyente, $fila->idproducto);
		}

		echo json_encode($lista_productos);
		exit();
	}

	public function buscarProductosAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}
		
		if(!isset($data['entidades']) || empty($data['entidades'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_array_productos';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentran términos de búsqueda';
			echo json_encode($resp);
			exit();
		}
		$entidades = isset($data['entidades'])?$data['entidades']:'';

		if(!is_array($entidades)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_array_entidades';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentran términos de búsqueda';
			echo json_encode($resp);
			exit();
		}

		if(count($entidades) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'error_array_entidades';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentran términos de búsqueda';
			echo json_encode($resp);
			exit();
		}

		$id_sucursal = isset($data['id_sucursal'])?intval($data['id_sucursal']):0;
		
		$lista_resultados = array();
		$data_busqueda = array();
		
		foreach($entidades as $termino_busqueda) {
			$data_busqueda['termino_busqueda'] = $termino_busqueda;
			$data_busqueda['idsucursal'] = $id_sucursal;
			$limite = 3;
			
			$resp_busqueda = $this->get_lista_productos_by_termino_busqueda($data_busqueda, $contribuyente_bd->id_contribuyente, $limite);
			$lista_resultados[$termino_busqueda] = $resp_busqueda['lista'];
		}
		
		$resp['respuesta'] = 'ok';
		$resp['lista_resultados'] = $lista_resultados;
		//$resp['data'] = $data;
		echo json_encode($resp);
		exit();
	}

	public function get_lista_productos_by_termino_busqueda($data, $id_contribuyente, $limite) {
		
		$termino_busqueda = !isset($data['termino_busqueda']) ? '' : $data['termino_busqueda'];
		if (empty($termino_busqueda)) {
			$resp['respuesta'] = 'ok';
			$resp['lista'] = array();
			return $resp;
		}

		$idsucursal = isset($data['idsucursal']) ? 0 : intval($data['idsucursal']);
		$termino = str_replace('  ', ' ', trim($termino_busqueda));

		$trozos = explode(" ", $termino);
		$numero_trozos = count($trozos);
		$num_letras = strlen(preg_replace('/\s+/', '', $termino));
		$where_sucursal = '';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		if ($contribuyente) {
			if ($contribuyente->multi_almacen == 'si') {
				if ($idsucursal > 0) {
					$where_sucursal = ' and idsucursal = ' . $idsucursal;
				}
			}
		}
		
		if ($numero_trozos <= 1) {
			$termino = '%' . $termino . '%';
			$query = "select * from producto where estado = 'activo' and id_contribuyente = :id_contribuyente and (nombre LIKE :termino or codigo LIKE :termino or codigos_presentaciones like :termino) $where_sucursal ORDER BY codigo, nombre ASC limit ".$limite;
		} else {
			$termino = str_replace(" ", "+.*", $termino);
			$termino = str_replace(' ', '', $termino);
			$termino = "($termino)";

			$query = "SELECT * FROM producto WHERE (nombre RLIKE :termino OR codigo RLIKE :termino OR codigos_presentaciones RLIKE :termino) AND id_contribuyente = :id_contribuyente and estado = 'activo' $where_sucursal  limit ".$limite;
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error en la consulta.'; //$e->getMessage()
			$resp['lista'] = array();
			return $resp;
		}
		
		$lista = array();
		while ($item = $sentencia->fetch()) {
			$fila = (object) $item;
			$texto_unidad_medida = '';
			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $fila->id_unidad_medida)));
			$moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $fila->id_cod_moneda)));
			$categoria = Categoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $fila->id_categoria)));
			if (!$categoria) {
				$nombre_categoria = 'Sin Categorizar';
			} else {
				$nombre_categoria = $categoria->nombre;
			}

			if ($unidadmedida) {
				if (isset($unidadmedida->nombre)) {
					$texto_unidad_medida = $unidadmedida->nombre;
				}
			}

			if ($fila->id_tipoafectacionigv == 10 || $fila->id_tipoafectacionigv == 7152) {
				$precio_producto = $fila->valor_con_igv + 0;
			} else {
				$precio_producto = $fila->valor_sin_igv + 0;
			}

			$codigo = '';
			if (isset($fila->codigo) && !empty($fila->codigo)) {
				$codigo = $fila->codigo;
			}

			$lista[] = array(
				'id' => $fila->idproducto,
				'nombre' => $fila->nombre,
				'unidad' => $texto_unidad_medida,
				'precio_producto' => $precio_producto,
				'moneda' => ($fila->id_cod_moneda == 'PEN')?'Soles':'Dolares',
				'simbolo_moneda' => ($fila->id_cod_moneda == 'PEN')?'S/.':'USD',
				'stock'	=> ($fila->stock + 0)
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $lista;
		return $resp;
	}

	public function getProductoAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}
		
		$id_producto = isset($data['id'])?intval($data['id']):0;
		$data_producto = $this->get_array_producto($contribuyente_bd->id_contribuyente, $id_producto);
		echo json_encode($data_producto);
		exit();
	}

	public function getNumProductosAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$productos_totales = Producto::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente), 'columns' => 'idproducto', "order" => "idproducto DESC"));

		$resp['respuesta'] = 'ok';
		$resp['total'] = count($productos_totales);

		echo json_encode($resp);
		
		exit();
	}

	public function get_array_producto($id_contribuyente, $id_producto) {
		$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $id_producto, 'id_contribuyente' => $id_contribuyente)));
		if(!$producto) {
			$codigo = $id_producto;
			$producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'id_contribuyente' => $id_contribuyente)));
			if(!$producto) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'producto';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'El producto no es válido';
				echo json_encode($resp);
				exit();
			}
		}

		if($producto->id_tipoafectacionigv == 10 || $producto->id_tipoafectacionigv == 7152) {
			$precio_producto = $producto->valor_con_igv;
		} else {
			$precio_producto = $producto->valor_sin_igv;
		}

		$lista_imagenes = array();
		if($producto->foto != '') {
			$imagenes_bd = json_decode($producto->foto);
			foreach($imagenes_bd->cuadradas as $img_cuadrada) {
				$lista_imagenes[] = $img_cuadrada;
			}

			foreach($imagenes_bd->verticales as $img_vertical) {
				$lista_imagenes[] = $img_vertical;
			}

			foreach($imagenes_bd->horizontales as $img_horizontal) {
				$lista_imagenes[] = $img_horizontal;
			}
		} else {
			$imagenes_bd = array();
		}

		$data_categoria = array();
		if(!empty($producto->id_categoria)) {
			$categoria = Categoria::findFirst(array("idcategoria = :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcategoria' => $producto->id_categoria, 'id_contribuyente' => $id_contribuyente)));
			if($categoria) {
				$data_categoria = array(
					"id"	=> $categoria->idcategoria,
					"name"	=> $categoria->nombre
				);
			}
		}

		$data_producto = array(
			"id"	=> $producto->idproducto,
			"sku"	=> $producto->codigo,
			"ean"	=> "",
			"titulo"	=> $producto->nombre,
			"shortDescription"	=> '',
			"longDescription"	=> '',
			"price"	=> array(
				"currency"	=> $producto->id_cod_moneda,
				"listPrice"	=> $precio_producto,
				"sellPrice"	=> $precio_producto
			),
			"stock"	=> floatval($producto->stock) + 0,
			"images"	=> $lista_imagenes,
			"images_details"	=> $imagenes_bd,
			"tags"	=> '',
			"brand"	=> array(),
			"category"	=> $data_categoria,
			"video"	=> '',
			"status"	=> ($producto->estado == 'activo')?'active':'inactive',
			'delivery'	=> array(),
			'variations'	=> array(),
		);

		return $data_producto;
	}

	public function comunicacionBajaAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'json';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
		}
		
		$token = isset($data['contribuyente']['token_contribuyente'])?$data['contribuyente']['token_contribuyente']:''; //$this->getBearerToken();
		if(empty($token)) {
			$token = $this->getBearerToken();
		}
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		if(!isset($data['contribuyente'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente';
			return $resp;
		}

		$contribuyente = $data['contribuyente'];

		if(!isset($contribuyente['tipo_proceso'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->tipo_proceso';
			return $resp;
		}

		if(!isset($contribuyente['id_usuario_vendedor'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad contribuyente->id_usuario_vendedor';
			return $resp;
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => intval($contribuyente['id_usuario_vendedor']), 'id_contribuyente' => intval($contribuyente_bd->id_contribuyente))));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'contribuyente';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra un valor válido para la propiedad contribuyente->id_usuario_vendedor';
			return $resp;
		}

		if(!isset($data['cabecera_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante';
			return $resp;
		}

		$cabecera = $data['cabecera_comprobante'];

		if(!isset($cabecera['tipo_documento'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->tipo_documento';
			return $resp;
		}

		if(!isset($cabecera['serie_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->serie_comprobante';
			return $resp;
		}

		if(!isset($cabecera['numero_comprobante'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->numero_comprobante';
			return $resp;
		}

		if(!isset($cabecera['motivo_anulacion'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'cabecera_comprobante';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra la propiedad cabecera_comprobante->motivo_anulacion';
			return $resp;
		}
		
		if(in_array($cabecera['tipo_documento'], array('01', '07', '08'))) {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente, 'id_tipodoc_electronico' => $cabecera['tipo_documento'], 'serie_comprobante' => $cabecera['serie_comprobante'], 'numero_comprobante' => $cabecera['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente['tipo_proceso'])));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en ApiRest';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

			if($documento->id_tipodoc_electronico == '01') { //facturas

				$data_doc_bd['id_contribuyente'] 		= $documento->id_contribuyente;
				$data_doc_bd['id_tipodoc_electronico'] 	= $documento->id_tipodoc_electronico;
				$data_doc_bd['serie_comprobante'] 		= $documento->serie_comprobante;
				$data_doc_bd['numero_comprobante'] 		= $documento->numero_comprobante;
				$motivo 								= !isset($cabecera['motivo_anulacion'])?'':$cabecera['motivo_anulacion'];
				$confirmacion 							= 'si';
	
				$data_doc_bd['tipo_envio_sunat'] = $documento->tipo_envio_sunat;
				$data_doc_bd['idusuario'] = $usuario->idusuario;
				$data_doc_bd['tipo_log'] = 'comunicacion_baja';
				$data_doc_bd['descripcion'] = 'El Usuario '.$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una comunicación de baja utilizando una conexión ApiRest';

				if($confirmacion == 'si') {
					$log = new LogsController;
					$resp_log = $log->log_documento($data_doc_bd);
				}
				
				$comunicaciondebaja = new ComunicaciondebajaController;
				$resp = $comunicaciondebaja->crear_comunicacion_baja($data_doc_bd, $motivo, $confirmacion, $usuario->idusuario);
				echo json_encode($resp);
				exit();

			} else { //nota de crédito o débito
				if($documento->id_tipo_comprobante_modifica == '01') { //si modifica una factura
					$data_doc_bd['id_contribuyente'] 		= $documento->id_contribuyente;
					$data_doc_bd['id_tipodoc_electronico'] 	= $documento->id_tipodoc_electronico;
					$data_doc_bd['serie_comprobante'] 		= $documento->serie_comprobante;
					$data_doc_bd['numero_comprobante'] 		= $documento->numero_comprobante;
					$motivo 								= !isset($cabecera['motivo_anulacion'])?'':$cabecera['motivo_anulacion'];
					$confirmacion 							= 'si';

					$data_doc_bd['tipo_envio_sunat'] = $documento->tipo_envio_sunat;
					$data_doc_bd['idusuario'] = $usuario->idusuario;
					$data_doc_bd['tipo_log'] = 'comunicacion_baja';
					$data_doc_bd['descripcion'] = 'El Usuario '.$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una comunicación de baja utilizando una conexión ApiRest';

					if($confirmacion == 'si') {
						$log = new LogsController;
						$resp_log = $log->log_documento($data_doc_bd);
					}
		
					$comunicaciondebaja = new ComunicaciondebajaController;
					$resp = $comunicaciondebaja->crear_comunicacion_baja($data_doc_bd, $motivo, $confirmacion, $usuario->idusuario);
					echo json_encode($resp);
					exit();

				} else if ($documento->id_tipo_comprobante_modifica == '03') { //si modifica una boleta

					$data_doc_bd['id_contribuyente'] 		= $documento->id_contribuyente;
					$data_doc_bd['id_tipodoc_electronico'] 	= $documento->id_tipodoc_electronico;
					$data_doc_bd['serie_comprobante'] 		= $documento->serie_comprobante;
					$data_doc_bd['numero_comprobante'] 		= $documento->numero_comprobante;
					$data_doc_bd['idusuario'] 				= $usuario->idusuario;

					$confirmacion 							= 'si';
					$accion 								= 3;

					if($confirmacion == 'si') {
						if($accion == 3) {
							$log = new LogsController;
							$data_doc_bd['tipo_log'] = 'comunicacion_baja';
							$data_doc_bd['descripcion'] = 'El Usuario '.$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una anulación utilizando una conexión ApiRest';
							$resp_log = $log->log_documento($data_doc_bd);
						}
					}
					
					$resumendeboletas = new ResumendeboletasController;
					$resp = $resumendeboletas->crear_resumen_individual($data_doc_bd, $accion, $confirmacion);
					echo json_encode($resp);
					exit();

				} else {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en ApiRest';
					$resp['mensaje'] = 'El documento electrónico que modifica no existe o no es válido!';
					echo json_encode($resp);
					exit();
				}
			}
		} else if(in_array($cabecera['tipo_documento'], array('03'))) {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente, 'id_tipodoc_electronico' => $cabecera['tipo_documento'], 'serie_comprobante' => $cabecera['serie_comprobante'], 'numero_comprobante' => $cabecera['numero_comprobante'], 'tipo_envio_sunat' => $contribuyente['tipo_proceso'])));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en ApiRest';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

			$data_doc_bd['id_contribuyente'] 		= $documento->id_contribuyente;
            $data_doc_bd['id_tipodoc_electronico'] 	= $documento->id_tipodoc_electronico;
            $data_doc_bd['serie_comprobante'] 		= $documento->serie_comprobante;
            $data_doc_bd['numero_comprobante'] 		= $documento->numero_comprobante;
			$data_doc_bd['idusuario'] 				= $usuario->idusuario;
			
            $confirmacion 							= 'si';
            $accion 								= 3;
			$resumendeboletas = new ResumendeboletasController;
            $resp = $resumendeboletas->crear_resumen_individual($data_doc_bd, $accion, $confirmacion);
            echo json_encode($resp);
            exit();
		} else if(in_array($cabecera['tipo_documento'], array('77', '88'))) {

			$doc_no_oficial = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente_bd->id_contribuyente, 'id_tipodocumento' => $cabecera['tipo_documento'], 'numero_comprobante' => $cabecera['numero_comprobante'], 'modalidad' => $contribuyente['tipo_proceso'])));

			if(!$doc_no_oficial) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en ApiRest';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

			$datapost['id_tipodocumento'] 		= $doc_no_oficial->id_tipodocumento;
            $datapost['numero_comprobante'] 	= $doc_no_oficial->numero_comprobante;
            $datapost['idsucursal'] 			= $doc_no_oficial->id_sucursal;
			$datapost['idusuario'] 				= $usuario->idusuario;
            $datapost['confirmacion'] 			= 'si';

			$reportedocumentos = new ReportedocumentosController;
			$resp = $reportedocumentos->anular_doc_no_oficial($datapost, $contribuyente_bd->id_contribuyente, $contribuyente['tipo_proceso'], $contribuyente['id_usuario_vendedor']);
			echo json_encode($resp);
			exit();
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
	}

	public function getDataCpeAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}
		
		$data_cpe['id_contribuyente'] = $contribuyente_bd->id_contribuyente;
		$data_cpe['serie_documento'] = isset($data['serie_documento'])?$data['serie_documento']:'';
		$data_cpe['correlativo_documento'] = isset($data['correlativo_documento'])?$data['correlativo_documento']:'';
		$data_cpe['tipo_documento'] = isset($data['tipo_documento'])?$data['tipo_documento']:'';
		$data_cpe['tipo_envio_sunat'] = $contribuyente_bd->tipo_envio_sunat;

		$resp = $this->get_data_cpe($data_cpe);
		echo json_encode($resp);
		exit();
	}

	public function get_data_cpe($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$serie_documento = $data['serie_documento'];
		$correlativo_documento = $data['correlativo_documento'];
		$tipo_documento = $data['tipo_documento'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		//echo "$id_contribuyente||$serie_documento||$correlativo_documento||$tipo_documento||$tipo_envio_sunat";
		//exit();

		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_documento, 'serie_comprobante' => $serie_documento, 'numero_comprobante' => $correlativo_documento , 'tipo_envio_sunat' => $tipo_envio_sunat)));
		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'get_data_cpe';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El documento no existe';
			return $resp;
		}

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));
		$codigo_regimen = 'M-RER';
        if($sunat_tiporegimen) {
			$codigo_regimen = $sunat_tiporegimen->codigo_libro;
		}

		if($documento->fecha_comprobante == $documento->fecha_vto_comprobante || empty($documento->fecha_vto_comprobante)) {
			$fecha_vencimiento = '';
		} else {
			$fecha_vencimiento = date("d/m/Y", strtotime($documento->fecha_vto_comprobante));
		}

		$cliente_id_tipodocidentidad = $cliente->id_tipodocidentidad;
		$cliente_razon_social = $cliente->razon_social;
		$cliente_num_doc_identidad = $cliente->num_doc;
		if($documento->estado_envio_sunat == 'anulado' || $documento->estado_envio_sunat == 'rechazado') {
			$cliente_id_tipodocidentidad = '';
			$cliente_num_doc_identidad = '';
			$cliente_razon_social = '';
		}

		$estado_sunat = 1;
		$fm = 1; //Factor de Multiplicación
		if($documento->estado_envio_sunat == 'anulado') {
			$fm = 0;
			$estado_sunat = 2;
		} else if ($documento->estado_envio_sunat == 'rechazado') {
			$fm = 2;
			$estado_sunat = 0;
		} else if ($documento->estado_envio_sunat == 'pendiente') {
			if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03') {
				$fm = 1;
			} else if ($documento->id_tipodoc_electronico == '07') {
				$fm = -1;
			} else if ($documento->id_tipodoc_electronico == '08') {
				$fm = 1;
			}
		} else if ($documento->estado_envio_sunat == 'aceptado') {
			if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03') {
				$fm = 1;
			} else if ($documento->id_tipodoc_electronico == '07') {
				$fm = -1;
			} else if ($documento->id_tipodoc_electronico == '08') {
				$fm = 1;
			}
		} else {
			$fm = 1;
		}

		$factor_tipo_cambio = 1;
		if($documento->tipo_cambio_sunat == '') {
			setlocale(LC_MONETARY, 'es_PE');
		} else {
			setlocale(LC_MONETARY, 'es_US');
			$factor_tipo_cambio = floatval($documento->tipo_cambio_sunat);
		}

		$fm = $fm*$factor_tipo_cambio;

		$fecha_documento_modificado = '';
		if(!empty($documento->id_tipo_comprobante_modifica) && !empty($documento->serie_documento_modifica) && !empty($documento->nro_documento_modifica)) {
			$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			if($documento_modificado) {
				$fecha_documento_modificado = date("d/m/Y", strtotime($documento_modificado->fecha_comprobante));
			}
		}

		$total_icbper = 0;
		if(!empty($documento->total_icbper)){
			$total_icbper = round($documento->total_icbper, 2);
		}
		
		$cpe = array(
			'periodo'				=> date("Ym", strtotime($documento->fecha_comprobante)).'00',
			'codUnico'				=> '',
			'regimen'				=> $codigo_regimen,
			'datos_comprobante'		=> array(
				'fecha_emision'		=> date("d/m/Y", strtotime($documento->fecha_comprobante)),
				'fecha_vencimiento' => $fecha_vencimiento,
				'tipo_documento'	=> $documento->id_tipodoc_electronico,
				'serie'				=> $documento->serie_comprobante,
				'numero'			=> $documento->numero_comprobante,
				'numMaqReg'			=> '',
			),
			'informacion_cliente' 	=> array(
				't_doc'				=> $cliente_id_tipodocidentidad,
				'numero'			=> $cliente_num_doc_identidad,
				'razon_social'		=> $cliente_razon_social
			),
			'importes_operacion'	=> array(
				'op_export'			=> round($fm*$documento->total_exportacion, 2),
				'op_gravada'		=> round($fm*$documento->total_gravadas, 2),
				'descuento'			=> 0,
				'igv'				=> round($fm*$documento->total_igv, 2),
				'desc_igv' 			=> 0,
				'op_exonerada'		=> round($fm*$documento->total_exoneradas, 2),
				'op_inafecta'		=> round($fm*$documento->total_inafecta, 2),
				'isc'				=> round($fm*$documento->total_isc, 2),
				'op_arroz_p'		=> 0,
				'imp_arroz_p'		=> 0,
				'icbper'			=> number_format((float)($fm*$total_icbper), 2, '.', ''),
				'otros_tributos'	=> round(($fm*$documento->total_otr_imp), 2),
				'total'				=> round($fm*$documento->total, 2),
				'moneda' 			=> 'PEN',
				'tc'				=> empty($documento->tipo_cambio)?'1.000':$documento->tipo_cambio,
			),
			'docmodificado' => array(
				'fecha_comp_modif'	=> $fecha_documento_modificado,
				'tipo_doc_modif'	=> !empty($documento->id_tipo_comprobante_modifica)?$documento->id_tipo_comprobante_modifica:'',
				'serie_doc_modif'	=> empty($documento->serie_documento_modifica)?'-':$documento->serie_documento_modifica,
				'num_doc_modif'		=> intval($documento->nro_documento_modifica) + 0,
			),
			'otros' => array(
				'id_contr' => '',
				'error_t_c'	=> '',
				'comp_m_p'	=> '',
				'estado'	=> $estado_sunat,
				'camp_lib'	=> '',
				'estado_comp'	=> strtoupper($documento->estado_envio_sunat),
			)
		);

		$resp['respuesta'] = 'ok';
		$resp['cpe'] = $cpe;
		return $resp;
	}

	public function getLibroEventasAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}

		$herramientas = new HerramientasController;
		$fecha_inicio = isset($data['fecha_inicio'])?$data['fecha_inicio']:''; //Y-m-d
		$fecha_fin = isset($data['fecha_fin'])?$data['fecha_fin']:''; //Y-m-d
		$idsucursal = isset($data['id_sucursal'])?intval($data['id_sucursal']):0;

		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(date("m-Y", strtotime($fecha_inicio)) != date("m-Y", strtotime($fecha_fin))) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'No Es Posible Generar el libro electrónico con fechas que incluyan más de un mes.!';
			echo json_encode($resp);
			exit();
		}

		$reportes = new ReportesController;
		$resp = $reportes->detalledocumentosemitidos($fecha_inicio, $fecha_fin, $contribuyente_bd->tipo_envio_sunat, $contribuyente_bd->id_contribuyente, $idsucursal, '-1');
		echo json_encode($resp);
		exit();
	}

	public function getDetalleCpeAction() {
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		
		$this->view->disable();
		
		//obtenemos la data de la solicitud9b64g688
		$bodyRequest = file_get_contents("php://input");
		$data = json_decode($bodyRequest, true);
		$token = $this->getBearerToken();
		
		if(empty($token)) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'Cuenta Errada!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente_bd = Contribuyente::findFirst(array("token = :token:", 'bind' => array('token' => $token)));
		if(!$contribuyente_bd) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'token';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El token no es válido';
			echo json_encode($resp);
			exit();
		}
		
		$data_cpe['id_contribuyente'] = $contribuyente_bd->id_contribuyente;
		$data_cpe['serie_documento'] = isset($data['serie_documento'])?$data['serie_documento']:'';
		$data_cpe['correlativo_documento'] = isset($data['correlativo_documento'])?$data['correlativo_documento']:'';
		$data_cpe['tipo_documento'] = isset($data['tipo_documento'])?$data['tipo_documento']:'';
		$data_cpe['tipo_envio_sunat'] = $contribuyente_bd->tipo_envio_sunat;

		$resp = $this->get_detalle_cpe($data_cpe);
		echo json_encode($resp);
		exit();
	}

	public function get_items_detalle_cpe($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$lista_items = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		
		foreach($lista_items as $item) {
			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));
			$producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto:", 'bind' => array('id_contribuyente' => $id_contribuyente,'idproducto' => $item->id_producto)));

			$nombre_categoria = '';
			$id_categoria = '';
			$codigo_categoria = '';
			if($producto) {
				if(!empty($producto->id_categoria)) {
					$categoria = Categoria::findFirst(array("id_contribuyente = :id_contribuyente: and idcategoria = :id_categoria:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_categoria' => $producto->id_categoria)));
					if($categoria) {
						$nombre_categoria = $categoria->nombre;
						$id_categoria = $categoria->idcategoria;
						$codigo_categoria = $categoria->codigo;
					}
				}
			}

			$texto_bien_detraccion = '';
			$codigo_bien_detraccion = '';
			$porcentaje_detraccion = '';
			if(!empty($producto->detraccion_iddetraccion)) {
				$SunatCodigodetraccion = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $producto->id_cod_detraccion)));
				if($SunatCodigodetraccion) {
					$texto_bien_detraccion = $SunatCodigodetraccion->descripcion;
					$codigo_bien_detraccion = $SunatCodigodetraccion->id_cod_detraccion;
					$porcentaje_detraccion = $SunatCodigodetraccion->porcentaje;
				}
			}

			$lista[] = array(
				"ITEM_DET"          	 => $item->item,
				"UNIDAD_MEDIDA_ID_DET"   => $item->id_unidad_medida,
				"UNIDAD_MEDIDA_DET"      => $item->unidad_medida,
				"UNIDAD_MEDIDA_NOMBRE"	 => $unidad_medida_sunat->nombre,
				"PRECIO_TIPO_CODIGO"     => $item->id_codigoprecio, //Precio unitario (incluye el IGV) - catálogo N° 16
				"COD_TIPO_OPERACION_DET" => $item->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
				"TEXT_TIPO_OPERACION_DET" => empty($tipoafectacionigv->descripcion)?null:$tipoafectacionigv->descripcion, //text_tipoafectacionigv - CATALOGO No. 07
				"CANTIDAD_DET"           => $item->cantidad,
				"PRECIO_DET"             => $item->precio + 0,
				"IGV_DET"                => $item->igv,
				"ISC_DET"				 => $item->isc,
				"ICBPER_DET"			 => $item->icbper,
				"IMPORTE_DET"            => $item->sub_total,
				"PRECIO_SIN_IGV_DET"  	 => $item->precio_sin_igv + 0,
				"CODIGO_DET"             => $item->id_producto,
				"CODIGO_PRODUCTO"		 => $item->codigo_producto,
				"DESCRIPCION_DET"   	 => $item->descripcion,
				"IDPRODUCTO_DET"		 => $item->id_producto,
				"PESO_DET"				 => $item->peso,
				"FACTOR_IGV"			 => $item->factor_igv,
				"ID_CATEGORIA"			 => $id_categoria,
				"NOMBRE_CATEGORIA"		 => $nombre_categoria,
				"CODIGO_CATEGORIA"		 => $codigo_categoria,

				"TIPO_UNIDAD"				=> !isset($item->tipo_unidad)?'UND':$item->tipo_unidad,
				"ID_PRESENTACION"			=> !isset($item->id_presentacion)?'UND':$item->id_presentacion,

				"AFECTO_DETRACCION"		 => empty($codigo_bien_detraccion)?false:true,
				"CODIGO_BIEN_DETRACCION" => $codigo_bien_detraccion,
				"TEXTO_BIEN_DETRACCION"	 => $texto_bien_detraccion,
				"PORCENTAJE_DETRACCION"	 => $porcentaje_detraccion,
				"NOTA"					 => $producto->nota
			);
		}

		if(!isset($lista)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Detalle';
			$resp['mensaje'] = 'El documento no tiene items';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista;
		return $resp;
	}

	public function get_detalle_cpe($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$serie_documento = $data['serie_documento'];
		$correlativo_documento = $data['correlativo_documento'];
		$tipo_documento = $data['tipo_documento'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		//echo "$id_contribuyente||$serie_documento||$correlativo_documento||$tipo_documento||$tipo_envio_sunat";
		//exit();

		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_documento, 'serie_comprobante' => $serie_documento, 'numero_comprobante' => $correlativo_documento , 'tipo_envio_sunat' => $tipo_envio_sunat)));
		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = 'get_data_cpe';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'El documento no existe';
			return $resp;
		}

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));
		$codigo_regimen = 'M-RER';
        if($sunat_tiporegimen) {
			$codigo_regimen = $sunat_tiporegimen->codigo_libro;
		}

		if($documento->fecha_comprobante == $documento->fecha_vto_comprobante || empty($documento->fecha_vto_comprobante)) {
			$fecha_vencimiento = '';
		} else {
			$fecha_vencimiento = date("d/m/Y", strtotime($documento->fecha_vto_comprobante));
		}

		$cliente_id_tipodocidentidad = $cliente->id_tipodocidentidad;
		$cliente_razon_social = $cliente->razon_social;
		$cliente_num_doc_identidad = $cliente->num_doc;
		$cliente_direccion = $cliente->direccion_fiscal;
		$cliente_id_ubigeo = $cliente->id_cod_ubigeo;
		$cliente_email = $cliente->email;
		$cliente_celular = $cliente->celular;
		
		if($documento->estado_envio_sunat == 'anulado' || $documento->estado_envio_sunat == 'rechazado') {
			$cliente_id_tipodocidentidad = '';
			$cliente_num_doc_identidad = '';
			$cliente_razon_social = '';
			
			$cliente_direccion = "";
    		$cliente_id_ubigeo = "";
    		$cliente_email = "";
    		$cliente_celular = "";
		}

		$estado_sunat = 1;
		$fm = 1; //Factor de Multiplicación
		if($documento->estado_envio_sunat == 'anulado') {
			$fm = 0;
			$estado_sunat = 2;
		} else if ($documento->estado_envio_sunat == 'rechazado') {
			$fm = 2;
			$estado_sunat = 0;
		} else if ($documento->estado_envio_sunat == 'pendiente') {
			if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03') {
				$fm = 1;
			} else if ($documento->id_tipodoc_electronico == '07') {
				$fm = -1;
			} else if ($documento->id_tipodoc_electronico == '08') {
				$fm = 1;
			}
		} else if ($documento->estado_envio_sunat == 'aceptado') {
			if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03') {
				$fm = 1;
			} else if ($documento->id_tipodoc_electronico == '07') {
				$fm = -1;
			} else if ($documento->id_tipodoc_electronico == '08') {
				$fm = 1;
			}
		} else {
			$fm = 1;
		}

		$factor_tipo_cambio = 1;
		if($documento->tipo_cambio_sunat == '') {
			setlocale(LC_MONETARY, 'es_PE');
		} else {
			setlocale(LC_MONETARY, 'es_US');
			$factor_tipo_cambio = floatval($documento->tipo_cambio_sunat);
		}

		$fm = $fm*$factor_tipo_cambio;

		$fecha_documento_modificado = '';
		if(!empty($documento->id_tipo_comprobante_modifica) && !empty($documento->serie_documento_modifica) && !empty($documento->nro_documento_modifica)) {
			$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			if($documento_modificado) {
				$fecha_documento_modificado = date("d/m/Y", strtotime($documento_modificado->fecha_comprobante));
			}
		}

		$total_icbper = 0;
		if(!empty($documento->total_icbper)){
			$total_icbper = round($documento->total_icbper, 2);
		}

		//$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $this->get_items_detalle_cpe($id_contribuyente, $tipo_documento, $serie_documento, $correlativo_documento);
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$detalle_documento = $resp_detalle['detalle'];
		
		//condición de pago o forma de pago
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
		$nombre_condicion_pago = 'Al Contado';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
		} else {
			if(isset($documento->id_tipo_operacion) && ($documento->id_tipo_operacion == '1001' || $documento->id_tipo_operacion == '1002' || $documento->id_tipo_operacion == '1003' || $documento->id_tipo_operacion == '1004')) {
				if($tipo_documento == '01') {
					$pago_parcial = MontoCobrado::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and (estado = 'activo') and tipo_abono = 'pago_parcial'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 'serie_comprobante' => $documento->serie_comprobante, 'tipo_envio_sunat' => $documento->tipo_envio_sunat, 'numero_comprobante' => $documento->numero_comprobante), "order" => "id_montocobrado ASC"));
					
					$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $pago_parcial->id_condicionpago)));
					if($condicion_pago) {
						$nombre_condicion_pago = $condicion_pago->condicionpago;
					}
				}
			}
		}
		
		$id_banco = empty($documento->cpago_idbanco)?0:intval($documento->cpago_idbanco);
		$banco_nombre = '';
		$banco_numero = '';
		$banco_moneda = '';
		$banco_titular = '';
		if(!empty($id_banco)) {
		    $cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($id_banco))));
		    $banco_nombre = $cuenta_banco_transferencia->nombre_banco;
    		$banco_numero = $cuenta_banco_transferencia->nro_cuenta;
    		$banco_moneda = $cuenta_banco_transferencia->id_codigomoneda;
    		$banco_titular = $cuenta_banco_transferencia->nombre_titular;
		}
		
		
		//validando si tiene cuotas
		$cuentasporcobrar = new CuentasporcobrarController;
		$lista_cuotas = array();
		$total_adeudado = 0;
		$fecha_pago_deuda = '';
		if(in_array($tipo_documento, array('01', '03', '07', '08'))) {
			$resp_cuotas = $cuentasporcobrar->get_lista_abonos($id_contribuyente, $tipo_documento, $serie_documento, $correlativo_documento, $tipo_envio_sunat);
			$lista_cuotas = $resp_cuotas['lista_cuotas'];
			$total_adeudado = $resp_cuotas['total_adeudado'];
		} else {
			$total_adeudado = empty($documento->monto_adeudado)?'':$documento->monto_adeudado;
			$fecha_pago_deuda = !empty($documento->fecha_pagopendiente)?date("d-m-Y", strtotime($documento->fecha_pagopendiente)):'';
		}

		$sunatTipoOperacion = SunatTipooperacion::findFirst(array("id_codigotipooperacion = :id_codigotipooperacion:", 'bind' => array('id_codigotipooperacion' => $documento->id_tipo_operacion)));

		$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $id_contribuyente)));

		$vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('idusuario' => $documento->id_vendedor, 'id_contribuyente' => $id_contribuyente)));

		$anticipos = DocElectronicoAnticipo::find(array("cpe_id_contribuyente = :cpe_id_contribuyente: and cpe_id_tipodoc_electronico = :cpe_id_tipodoc_electronico: and cpe_serie_comprobante  = :cpe_serie_comprobante: and cpe_numero_comprobante  = :cpe_numero_comprobante: and cpe_tipo_envio_sunat = :cpe_tipo_envio_sunat:", 'bind' => array('cpe_id_contribuyente' => $id_contribuyente, 'cpe_id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 'cpe_serie_comprobante' => $documento->serie_comprobante, 'cpe_numero_comprobante' => $documento->numero_comprobante, 'cpe_tipo_envio_sunat' => $documento->tipo_envio_sunat)));

		$array_lista_anticipos = array();
		foreach($anticipos as $anticipo) {
			$array_lista_anticipos = array(
				'id_contribuyente' 			=> $anticipo->anticipo_id_contribuyente,
				'id_tipodoc_electronico' 	=> $anticipo->anticipo_id_tipodoc_electronico,
				'serie_comprobante' 		=> $anticipo->anticipo_serie_comprobante,
				'numero_comprobante' 		=> $anticipo->anticipo_numero_comprobante,
				'monto' 					=> $anticipo->monto_anticipo
			);
		}

		$gestiondeetiquetas = new GestiondeetiquetasController;
		$resp_etiquetas = $gestiondeetiquetas->get_etiquetas_documento($id_contribuyente, $documento->id_tipodoc_electronico, $documento->serie_comprobante, $documento->numero_comprobante, $documento->tipo_envio_sunat);
		$lista_ids_etiquetas = $resp_etiquetas['etiquetas_activas'];

		$lista_etiquetas_activas = array();
		foreach($lista_ids_etiquetas as $id_etiqueta) {
			$etiqueta = Etiqueta::findFirst(array("id_contribuyente = :id_contribuyente: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_etiqueta' => $id_etiqueta)));
			if($etiqueta) {
				$lista_etiquetas_activas[] = array(
					'id_etiqueta'	=> $etiqueta->id_etiqueta,
					'nombre' => $etiqueta->nombre
				);
			}
		}

		$texto_bien_detraccion = '';
		if(!empty($documento->detraccion_iddetraccion)) {
			$SunatCodigodetraccion = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $documento->detraccion_iddetraccion)));
			if($SunatCodigodetraccion) {
				$texto_bien_detraccion = $SunatCodigodetraccion->descripcion;
			}
		}

		$descripcion_percepcion = '';
		if(!empty($documento->percepcion_idtipo)) {
			$SunatCodigotipopercepcion = SunatCodigotipopercepcion::findFirst(array("codigo = :codigo:", 'bind' => array('codigo' => $documento->percepcion_idtipo)));
			if($SunatCodigotipopercepcion) {
				$descripcion_percepcion = $SunatCodigotipopercepcion->descripcion;
			}
		}

		$cpe = array(
			'periodo'				=> date("Ym", strtotime($documento->fecha_comprobante)).'00',
			'codUnico'				=> '',
			'regimen'				=> $codigo_regimen,
			'datos_comprobante'		=> array(
			    'tipo_operacion'    => $documento->id_tipo_operacion,
				'nombre_operación'	=> $sunatTipoOperacion->descripcion,
				'fecha_emision'		=> date("d/m/Y", strtotime($documento->fecha_comprobante)),
				'fecha_vencimiento' => $fecha_vencimiento,
				'tipo_documento'	=> $documento->id_tipodoc_electronico,
				'serie'				=> $documento->serie_comprobante,
				'numero'			=> $documento->numero_comprobante,
				'id_codigomoneda'	=> $documento->id_codigomoneda,
				'numMaqReg'			=> '',
				'id_sucursal'		=> $documento->id_sucursal,
				'nombre_sucursal'	=> $sucursal->nombre,
				'id_usuario'		=> $documento->id_vendedor,
				'nombre_usuario'	=> $vendedor->nombre,
				'apellido_usuario'	=> $vendedor->apellido,
				'ruc_contribuyente' => $contribuyente->ruc,
				'nro_otr_comprobante' => $documento->nro_otr_comprobante,
				'aplica_retencion'	=> ($documento->retencion_aplica == 'si')?'true':'false',
				'aplica_anticipo'   => (count($array_lista_anticipos) > 0)?'true':'false',
				'lista_anticipos'	=> $array_lista_anticipos,
				'tiene_etiquetas'	=> (count($lista_ids_etiquetas) > 0)?'true':'false',
				'lista_etiquetas'	=> $lista_etiquetas_activas
			),
			'informacion_cliente' 	=> array(
				't_doc'				=> $cliente_id_tipodocidentidad,
				'numero'			=> $cliente_num_doc_identidad,
				'razon_social'		=> $cliente_razon_social,
				'cliente_direccion' => $cliente_direccion,
        		'cliente_id_ubigeo' => $cliente_id_ubigeo,
        		'cliente_email'     => $cliente_email,
        		'cliente_celular'   => $cliente_celular,
			),
			
			'condicion_pago'        => array(
			    'forma_de_pago'     => $nombre_condicion_pago,
			    'cuotas'			=> $lista_cuotas,
    			'total_adeudado'	=> $total_adeudado,
    			'fecha_pago_deuda'	=> $fecha_pago_deuda,
    			
    			'cpago_nrooperacion'    => empty($documento->cpago_nrooperacion)?'':$documento->cpago_nrooperacion,
    			'cpago_fechadeposito'   => empty($documento->cpago_fechadeposito)?'':$documento->cpago_fechadeposito, 
    			'cpago_idbanco'         => empty($documento->cpago_idbanco)?'':$documento->cpago_idbanco,
    			
    			'data_banco'        => array(
    			    'nombre'        => $banco_nombre,
    			    'nro_cuenta'    => $banco_numero,
    			    'moneda'        => $banco_moneda,
    			    'titular'       => $banco_titular
    			)
			),
			
			'importes_operacion'	=> array(
				'op_export'			=> round($fm*$documento->total_exportacion, 2),
				'op_gravada'		=> round($fm*$documento->total_gravadas, 2),
				'descuento'			=> round($fm*$documento->total_descuento, 2),
				'porcentaje_descuento' => $documento->porcentaje_descuento_total,
				'descuento'			=> 0,
				'igv'				=> round($fm*$documento->total_igv, 2),
				'desc_igv' 			=> 0,
				'op_exonerada'		=> round($fm*$documento->total_exoneradas, 2),
				'op_inafecta'		=> round($fm*$documento->total_inafecta, 2),
				'isc'				=> round($fm*$documento->total_isc, 2),
				'op_arroz_p'		=> 0,
				'imp_arroz_p'		=> 0,
				'icbper'			=> number_format((float)($fm*$total_icbper), 2, '.', ''),
				'otros_tributos'	=> round(($fm*$documento->total_otr_imp), 2),
				'total'				=> round($fm*$documento->total, 2),
				'moneda' 			=> 'PEN',
				'tc'				=> empty($documento->tipo_cambio)?'1.000':$documento->tipo_cambio,

				'base_imponible'    	=> $documento->retencion_base,
				'porcentaje_retencion' 	=> $documento->retencion_factor,
				'monto_retencion' 		=> $documento->retencion_monto,

				'detraccion_cuenta' 		=> $documento->detraccion_cuenta,
				'detraccion_banco' 			=> !empty($documento->detraccion_monto)?'Banco de la nación':null,
				'detraccion_cod_bien' 		=> $documento->detraccion_iddetraccion,
				'detraccion_nombre_bien' 	=> $texto_bien_detraccion,
				'detraccion_porcentaje' 	=>  $documento->detraccion_porcentaje,
				'detraccion_monto' 			=>  $documento->detraccion_monto,
				'detraccion_texto' 			=>  $documento->detraccion_texto,

				'cod_tip_percepcion' 		=> $documento->percepcion_idtipo,
                'nombre_tipo_percepcion' 	=> $descripcion_percepcion,
                'base' 						=> $documento->percepcion_montobase,
                'porcentaje_percepcion' 	=> $documento->percepcion_porcentaje,
                'monto_percepcion' 			=> $documento->percepcion_monto,
			),
			'docmodificado' => array(
				'fecha_comp_modif'	=> $fecha_documento_modificado,
				'tipo_doc_modif'	=> !empty($documento->id_tipo_comprobante_modifica)?$documento->id_tipo_comprobante_modifica:'',
				'serie_doc_modif'	=> empty($documento->serie_documento_modifica)?'-':$documento->serie_documento_modifica,
				'num_doc_modif'		=> intval($documento->nro_documento_modifica) + 0,
			),
			'otros' => array(
				'id_contr' => '',
				'error_t_c'	=> '',
				'comp_m_p'	=> '',
				'estado'	=> $estado_sunat,
				'camp_lib'	=> '',
				'estado_comp'	=> strtoupper($documento->estado_envio_sunat),
			),
			'detalle_documento' => array(
				'es_al_credito'	=> ($documento->tipo_venta == 'credito')?'si':'no',
				'monto_adeudado' => ($documento->tipo_venta == 'credito')?$documento->monto_adeudado_inicial:0,
				'monto_pagado'	=> ($documento->tipo_venta == 'credito')?$documento->total - $documento->monto_adeudado:0,
				'fecha_pago'	=> ($documento->tipo_venta == 'credito')?$documento->fecha_pagopendiente:'',
				'observacion'   => $documento->nota
			),
			'lista_productos'	=> $detalle_documento,
			
			'enlaces'           => $this->get_enlaces_cpe($documento),
		);

		$resp['respuesta'] = 'ok';
		$resp['cpe'] = $cpe;
		return $resp;
	}
	
	public function get_enlaces_cpe($documento) {
	    $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
	    $herramientas = new HerramientasController;
	    $string_encrypted_document_a4 = $herramientas->encriptar("$documento->id_contribuyente||$documento->id_tipodoc_electronico||$documento->serie_comprobante||$documento->numero_comprobante||$documento->tipo_envio_sunat||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$documento->id_contribuyente||$documento->id_tipodoc_electronico||$documento->serie_comprobante||$documento->numero_comprobante||$documento->tipo_envio_sunat||ticket");
		$url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
		return array(
		    'url_a4' => $url_a4,
		    'url_ticket'    => $url_ticket
		);
	}
}
?>