<?php
class HerramientasController extends ControllerBase
{
	public function verimageAction($imagename = '')
	{
		$this->view->disable();
		if ($imagename == '') {
			exit();
		}
		try {
			$rutaImagen = $_SERVER["DOCUMENT_ROOT"] . '/facturacionv8/public/files/upload_user/' . $imagename; #Concatenar nombre con .jpg
			if (file_exists($rutaImagen)) {
				$type = pathinfo($rutaImagen, PATHINFO_EXTENSION);
				$data = file_get_contents($rutaImagen);
				$informacionImagen = getimagesize($rutaImagen);

				header("Content-type: {$informacionImagen['mime']}");
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				readfile($rutaImagen);
				exit;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function logoEmpresaV1Action()
	{
		$this->view->disable();
		try {
			$rutaImagen = 'https://' . $this->data_patrocinador['url_domain'] . $this->data_patrocinador['logo_img_291'];
			$type = pathinfo($rutaImagen, PATHINFO_EXTENSION);
			$data = file_get_contents($rutaImagen);
			$informacionImagen = getimagesize($rutaImagen);
			header("Content-type: {$informacionImagen['mime']}");
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			readfile($rutaImagen);
			exit;
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function logoEmpresaV2Action()
	{
		$this->view->disable();
		try {
			$rutaImagen = 'https://' . $this->data_patrocinador['url_domain'] . $this->data_patrocinador['logo_img_56'];
			$type = pathinfo($rutaImagen, PATHINFO_EXTENSION);
			$data = file_get_contents($rutaImagen);
			$informacionImagen = getimagesize($rutaImagen);
			header("Content-type: {$informacionImagen['mime']}");
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			readfile($rutaImagen);
			exit;
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function imageclieAction($id_contribuyente = '', $imagename = '')
	{
		$this->view->disable();
		if ($imagename == '' || $id_contribuyente == '') {
			exit();
		}
		try {
			$rutaImagen = $_SERVER["DOCUMENT_ROOT"] . '/facturacionv8/public/files/upload_user/img_clientes/' . $id_contribuyente . '/' . $imagename; #Concatenar nombre con .jpg
			if (file_exists($rutaImagen)) {
				$type = pathinfo($rutaImagen, PATHINFO_EXTENSION);
				$data = file_get_contents($rutaImagen);
				$informacionImagen = getimagesize($rutaImagen);

				header("Content-type: {$informacionImagen['mime']}");
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				readfile($rutaImagen);
				exit;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function verimageprodAction($numero_ruc = '', $anio = '', $mes = '', $image_nombre = '')
	{
		$this->view->disable();
		if ($numero_ruc == '' || $anio == '' || $mes == '' || $image_nombre == '') {
			exit();
		}
		try {
			$rutaImagen = $_SERVER["DOCUMENT_ROOT"] . '/facturacionv8/public/files/upload_user/img_productos/' . $numero_ruc . '/' . $anio . '/' . $mes . '/' . $image_nombre; #Concatenar nombre con .jpg
			if (file_exists($rutaImagen)) {
				$type = pathinfo($rutaImagen, PATHINFO_EXTENSION);
				$data = file_get_contents($rutaImagen);
				$informacionImagen = getimagesize($rutaImagen);

				header("Content-type: {$informacionImagen['mime']}");
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				readfile($rutaImagen);
				exit;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function verimagepersonalizacionAction($numero_ruc = '', $anio = '', $mes = '', $image_nombre = '')
	{
		$this->view->disable();
		if ($numero_ruc == '' || $anio == '' || $mes == '' || $image_nombre == '') {
			exit();
		}
		try {
			$rutaImagen = $_SERVER["DOCUMENT_ROOT"] . '/facturacionv8/public/files/upload_user/img_personalizacion/' . $numero_ruc . '/' . $anio . '/' . $mes . '/' . $image_nombre; #Concatenar nombre con .jpg
			if (file_exists($rutaImagen)) {
				$type = pathinfo($rutaImagen, PATHINFO_EXTENSION);
				$data = file_get_contents($rutaImagen);
				$informacionImagen = getimagesize($rutaImagen);

				header("Content-type: {$informacionImagen['mime']}");
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				readfile($rutaImagen);
				exit;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function get_modalidad_envio_sunat($emisor, $datapost)
	{
		if (empty($datapost['modalidad_envio_sunat'])) {
			if (empty($emisor['modalidad_envio_sunat'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error Modo Envío';
				$resp['mensaje'] = 'Debes seleccionar el modo de envío a sunat';
				return $resp;
			} else {
				$modalidad_envio_sunat = $emisor['modalidad_envio_sunat'];
			}
		} else {
			$modalidad_envio_sunat = $datapost['modalidad_envio_sunat'];
		}

		if (isset($this->modalidad_envio_sunat) && !empty($this->modalidad_envio_sunat)) {
			$modalidad_envio_sunat = $this->modalidad_envio_sunat;
		}

		$modalidades_aceptadas = array('inmediato', 'solo_firma', 'no_enviar');
		if (!in_array($modalidad_envio_sunat, $modalidades_aceptadas)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Modo Envío';
			$resp['mensaje'] = 'Debes seleccionar el modo de envío a sunat';
			return $resp;
		}

		if ($this->bloquear_sunat) {
			$resp['modalidad_envio_sunat'] = 'no_enviar'; //$modalidad_envio_sunat;
		} else {
			$resp['modalidad_envio_sunat'] = $modalidad_envio_sunat;
		}
		$resp['respuesta'] = 'ok';

		return $resp;
	}

	public function saveimageAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$imgbase64 = $datapost['dataimage'];
			$tipo = $datapost['imagetipo'];
			$image_nombre = 'imguser-' . uniqid() . '-' . md5(time()) . '.png';
			$destino = "files/upload_user/";
			$file = $destino . $image_nombre;
			$success = $this->subirimagen_servidor($imgbase64, $file);
			if ($success == true) {
				$resp['respuesta'] = 'ok';
				$resp['urlimagen'] = '/facturacionv8/herramientas/verimage/' . $image_nombre;
				$resp_save = $this->guardar_imagen_en_bd($tipo, $resp['urlimagen']);
				$resp['resp_save'] = $resp_save;
				echo json_encode($resp);
				exit();
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['explicacion'] = "Error al Subir Imágen";
				echo json_encode($resp);
				exit();
			}
		}
	}

	public function guardar_imagen_cliente($id_contribuyente, $base64)
	{

		$datapost = $this->request->getPost();
		$imgbase64 = $base64;
		$image_nombre = 'imgclie-' . uniqid() . '-' . md5(time()) . '.png';
		$destino = $this->ruta_base_files . "/../public_html/facturacionv8/public/files/upload_user/img_clientes/" . $id_contribuyente . "/";
		if (!file_exists($destino)) {
			mkdir($destino, 0777, true);
		}

		$file = $destino . $image_nombre;
		$success = $this->subirimagen_servidor($imgbase64, $file);
		if ($success == true) {
			$resp['respuesta'] = 'ok';
			$resp['urlimagen'] = '/facturacionv8/herramientas/imageclie/' . $id_contribuyente . '/' . $image_nombre;
			return $resp;
		}

		$resp['respuesta'] = 'error';
		return $resp;
	}

	public function guardarImagenProductoAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$imgbase64 = $datapost['dataimage'];
			$tipo = $datapost['imagetipo'];
			$image_nombre = 'imgprod-' . uniqid() . '-' . md5(time()) . '.png';

			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Usuario';
				$resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if (!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
				echo json_encode($resp);
				exit();
			}

			$numero_ruc = $contribuyente->ruc;
			$anio = date("Y");
			$mes = date("m");
			$destino = $numero_ruc . '/' . $anio . '/' . $mes;
			$destino = $this->ruta_base_files . "/../public_html/facturacionv8/public/files/upload_user/img_productos/" . $numero_ruc . '/' . $anio . '/' . $mes . '/';
			if (!file_exists($destino)) {
				mkdir($destino, 0777, true);
			}

			$file = $destino . $image_nombre;
			$success = $this->subirimagen_servidor($imgbase64, $file);
			if ($success == true) {
				$resp['respuesta'] = 'ok';
				$resp['urlimagen'] = '/facturacionv8/herramientas/verimageprod/' . $numero_ruc . '/' . $anio . '/' . $mes . '/' . $image_nombre;
				echo json_encode($resp);
				exit();
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['explicacion'] = "Error al Subir Imágen";
				echo json_encode($resp);
				exit();
			}
		}
	}

	public function guardar_imagen_en_bd($tipo, $url_img)
	{
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if (!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Lo siento no existe el usuario';
			return $resp;
		}

		$rangos_validos = array(1, 2, 3, 5);
		if (!in_array($usuario->id_rol, $rangos_validos)) { //si es diferente de contribuyente_admin entonces no permitir que editen los usuarios
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Usted no tiene permisos para editar el usuario!';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if (!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el valor para el contribuyente.';
			return $resp;
		}

		if ($tipo == 'logo350x167') {
			$contribuyente->logo_350 = $url_img;
			$resp['resp_save'] = $contribuyente->save();
		} else if ($tipo == 'img') {
			$contribuyente->img_logo = $url_img;
			$resp['resp_save'] = $contribuyente->save();
		} else if ($tipo == 'img_profile' || $tipo == 'profile') {
			$usuario->url_image = $url_img;
			$resp['resp_save'] = $usuario->save();
		} else if ($tipo == 'img_login_template') {
			$opciones_system = Opciones::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));

			if (!$opciones_system) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Error al guardar la imágen, no se encuentra el valor válido para opciones.';
				return $resp;
			}

			$opciones_system->bg_template_login = $url_img;
			$resp['resp_save'] = $opciones_system->save();
		} else if ($tipo == 'img_registro_template') {

			$opciones_system = Opciones::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
			if (!$opciones_system) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Error al guardar la imágen, no se encuentra el valor válido para opciones.';
				return $resp;
			}

			$opciones_system->bg_template_register = $url_img;
			$resp['resp_save'] = $opciones_system->save();
		}

		$resp['respuesta'] = 'ok';
		return $resp;
	}

	public function subirimagen_servidor($imgbase64, $file_destino)
	{
		if (empty($imgbase64)) {
			return false;
		}
		
		// Extraer el tipo MIME y el contenido base64
		if (preg_match('/^data:image\/(\w+);base64,/', $imgbase64, $tipo)) {
			$data = substr($imgbase64, strpos($imgbase64, ',') + 1);
			$tipo = strtolower($tipo[1]); // jpg, png, gif, etc.

			// Validar tipos de imagen permitidos
			if (!in_array($tipo, ['jpg', 'jpeg', 'gif', 'png'])) {
				return false;
			}
		} else {
			return false;
		}

		$data = str_replace(' ', '+', $data);

		if (!$this->check_base64_image($data)) {
			return false;
		}

		$data_decoded = base64_decode($data);

		if ($data_decoded === false) {
			return false;
		}

		$success = file_put_contents($file_destino, $data_decoded);
		return $success !== false;
	}

	public function check_base64_image($base64)
	{
		try {
			if (empty($base64)) {
				return false;
			}

			$data = base64_decode($base64);

			if ($data === false) {
				return false;
			}

			$info = getimagesizefromstring($data);

			if ($info === false) {
				return false;
			}

			if ($info[0] > 0 && $info[1] > 0 && isset($info['mime'])) {
				return true;
			}

			return false;
		} catch (Exception $e) {
			$this->saveLogger($e);
			return false;
		}
	}

	public function generartokenAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$resp['respuesta'] = 'ok';
			$resp['length'] = !isset($datapost['length']) ? 0 : intval($datapost['length']) + 0;
			if ($resp['length'] <= 0) {
				$resp['token'] = $this->gettoken();
			} else {
				$resp['token'] = $this->gettoken($resp['length']);
			}
			echo json_encode($resp);
			exit();
		}
	}
	public function gettoken($length = 25)
	{
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		//$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet .= "0123456789";
		$max = strlen($codeAlphabet); // edited

		for ($i = 0; $i < $length; $i++) {
			$token .= $codeAlphabet[random_int(0, $max - 1)];
		}

		return $token;
	}

	public function verificar_permisos_usuario($idusuario) {
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if (!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Lo siento no existe el usuario';
			return $resp;
		}

		$rangos_validos = array(1, 2, 3, 5);
		if (!in_array($usuario->id_rol, $rangos_validos)) { //si es diferente de contribuyente_admin entonces no permitir que editen los usuarios
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Usted no tiene permisos para editar el usuario!';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		return $resp;
	}

	public function getListaUsuariosAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$lista = Usuario::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			$lista_usuarios = array();
			foreach ($lista as $usuario) {
				$lista_usuarios[] = array(
					'idusuario' => $usuario->idusuario,
					'nombre_completo' => $usuario->nombre . ' ' . $usuario->apellido . ' - ' . $usuario->estado
				);
			}
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista_usuarios;
			echo json_encode($resp);
			exit();
		}
	}

	public function getListaSucursalesAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$lista = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			$resp['idsucursal_usuario'] = $usuario->idsucursal;
			echo json_encode($resp);
			exit();
		}
	}

	public function getDataClienteAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if (!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
				echo json_encode($resp);
				exit();
			}

			$id_tipodocidentidad = !isset($datapost['tipo_doc']) ? 0 : intval($datapost['tipo_doc']);
			$tipodocidentidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad)));
			if (!$tipodocidentidad) {
				$msj['respuesta'] = 'error';
				$msj['titulo'] = 'Error';
				$msj['mensaje'] = 'Lo siento, ese  tipo de Documento de Identidad no existe. Por favor selecciona uno Válido';
				echo json_encode($msj);
				exit();
			}

			$num_doc = !isset($datapost['num_doc']) ? '' : $datapost['num_doc'];
			if (empty($num_doc)) {
				$msj['respuesta'] = 'error';
				$msj['titulo'] = 'Error';
				$msj['mensaje'] = 'Lo sentimos! Debes escribir el Documento de Identidad, no se permiten campos vacíos.';
				echo json_encode($msj);
				exit();
			}

			$incluir_licencias = !isset($datapost['incluir_licencias']) ? 'no' : $datapost['incluir_licencias'];
			$incluir_licencias = ($incluir_licencias == 'si') ? 'si' : 'no';

			$licencias = array();
			$resp_licencias = array();
			if ($incluir_licencias == 'si') {
				$resp_licencias = $this->get_licencias_conducir($num_doc);
				/*
				if($resp_licencias['respuesta'] == 'ok') {
					
				}
				*/
			}

			$cliente = Cliente::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad: and id_contribuyente = :id_contribuyente: and num_doc = :num_doc:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad, 'id_contribuyente' => $usuario->id_contribuyente, 'num_doc' => $num_doc)));

			$lista_guias_remision = array();
			if (!$cliente) {
				if ($id_tipodocidentidad == 1) {
					if (strlen($num_doc) != 8) {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['mensaje'] = 'El número de DNI debe tener 8 caracteres!... Por favor ingresa un número de DNI correcto!';
						echo json_encode($msj);
						exit();
					}
					$resp = $this->get_data_api_busquedas('dni', $num_doc, $contribuyente->tipo_busqueda_doc);

					if ($resp['respuesta'] == 'error') {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['data'] = $resp;
						$msj['mensaje'] = 'No hemos encontrado los datos para el DNI ingresado, ingresa todos los datos manualmente por favor!';
						echo json_encode($msj);
						exit();
					}
				} elseif ($id_tipodocidentidad == 6) {
					if (strlen($num_doc) != 11) {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['mensaje'] = 'El número de RUC debe tener 11 caracteres!... Por favor ingresa un número de RUC correcto!';
						echo json_encode($msj);
						exit();
					}
					$resp = $this->get_data_api_busquedas('ruc', $num_doc);

					if ($resp['respuesta'] == 'error') {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['mensaje'] = 'No hemos encontrado los datos para el RUC ingresado, ingresa todos los datos manualmente por favor!';
						echo json_encode($msj);
						exit();
					}
				} else {
					$resp['respuesta'] = 'ok';
					$resp['encontrado'] = false;
				}

				$resp['data_licencia'] = $resp_licencias;
				echo json_encode($resp);
				exit();
			} else {
				$guiaderemision = new GuiaderemisionController;
				$lista_guias_remision = $guiaderemision->get_guias_remision_cliente($contribuyente->id_contribuyente, $cliente->idcliente, $contribuyente->tipo_envio_sunat);
			}


			$departamento = '';
			$provincia = '';
			$distrito = '';
			$texto_ubigeo = '';

			if (isset($cliente->id_cod_ubigeo) && !empty($cliente->id_cod_ubigeo)) {
				$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
				if ($ubigeo) {
					$departamento = $ubigeo->departamento;
					$provincia = $ubigeo->provincia;
					$distrito = $ubigeo->distrito;
					$texto_ubigeo = $departamento . ' - ' . $provincia . ' - ' . $distrito;
				}
			}

			$resp['data_licencia'] = $resp_licencias;

			$resp['respuesta'] = 'ok';
			$resp['encontrado'] = true;
			$resp['lista_guias'] = $lista_guias_remision;
			$resp['api'] = false;
			$resp['data'] = $cliente;
			$resp['texto_ubigeo'] = $texto_ubigeo;

			echo json_encode($resp);
			exit();
		}
	}

	public function get_licencias_conducir($num_dni)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://facturalahoy.com/api/humbertoguadalup/get_licencia_conducir',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
			"token": "' . $this->token_user . '",
			"num_dni": "' . $num_dni . '"
		}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Cookie: PHPSESSID=ef2d07d222cd0db6cad75e6a35891a11'
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = '1';
			$resp['mensaje'] = 'No se encuentran datos';
			return $resp;
		}

		$resp_api = json_decode($response, true);
		if ($resp_api === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = '2';
			$resp['mensaje'] = 'Error al buscar las licencias';
			return $resp;
		}

		if (!isset($resp_api['respuesta'])) {
			$resp['respuesta'] = 'error';
			$resp['codigo'] = '3';
			$resp['mensaje'] = 'Error en la búsqueda de licencias';
			return $resp;
		}

		if ($resp_api['respuesta'] == 'error') {
			return $resp_api;
		}

		return $resp_api;
	}

	public function getDataProveedorAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if (!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
				echo json_encode($resp);
				exit();
			}

			$id_tipodocidentidad = !isset($datapost['tipo_doc']) ? 0 : intval($datapost['tipo_doc']);
			$tipodocidentidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad)));
			if (!$tipodocidentidad) {
				$msj['respuesta'] = 'error';
				$msj['titulo'] = 'Error';
				$msj['mensaje'] = 'Lo siento, ese  tipo de Documento de Identidad no existe. Por favor selecciona uno Válido';
				echo json_encode($msj);
				exit();
			}

			$num_doc = !isset($datapost['num_doc']) ? '' : $datapost['num_doc'];
			if (empty($num_doc)) {
				$msj['respuesta'] = 'error';
				$msj['titulo'] = 'Error';
				$msj['mensaje'] = 'Lo sentimos! Debes escribir el Documento de Identidad, no se permiten campos vacíos.';
				echo json_encode($msj);
				exit();
			}

			$proveedor = Proveedor::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad: and id_contribuyente = :id_contribuyente: and num_doc = :num_doc:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad, 'id_contribuyente' => $usuario->id_contribuyente, 'num_doc' => $num_doc)));
			if (!$proveedor) {
				if ($id_tipodocidentidad == 1) {
					if (strlen($num_doc) != 8) {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['mensaje'] = 'El número de DNI debe tener 8 caracteres!... Por favor ingresa un número de DNI correcto!';
						echo json_encode($msj);
						exit();
					}
					$resp = $this->get_data_api_busquedas('dni', $num_doc, $contribuyente->tipo_busqueda_doc);

					if ($resp['respuesta'] == 'error') {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['data'] = $resp;
						$msj['mensaje'] = 'No hemos encontrado los datos para el DNI ingresado, ingresa todos los datos manualmente por favor!';
						echo json_encode($msj);
						exit();
					}
				} elseif ($id_tipodocidentidad == 6) {
					if (strlen($num_doc) != 11) {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['mensaje'] = 'El número de RUC debe tener 11 caracteres!... Por favor ingresa un número de RUC correcto!';
						echo json_encode($msj);
						exit();
					}

					$resp = $this->get_data_api_busquedas('ruc', $num_doc);

					if ($resp['respuesta'] == 'error') {
						$msj['respuesta'] = 'error';
						$msj['titulo'] = 'Error';
						$msj['mensaje'] = 'No hemos encontrado los datos para el RUC ingresado, ingresa todos los datos manualmente por favor!';
						echo json_encode($msj);
						exit();
					}
				} else {
					$resp['respuesta'] = 'ok';
					$resp['encontrado'] = false;
				}

				echo json_encode($resp);
				exit();
			}

			$resp['respuesta'] = 'ok';
			$resp['encontrado'] = true;
			$resp['api'] = false;
			$resp['data'] = $proveedor;
			echo json_encode($resp);
			exit();
		}
	}

	public function getDataSucursalAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$idsucursal = intval($datapost['idsucursal']) + 0;

			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$msj['respuesta'] = 'error';
				$msj['titulo'] = 'Error';
				$msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
				echo json_encode($msj);
				exit();
			}

			$sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if (!$sucursal) {
				$msj['respuesta'] = 'error';
				$msj['titulo'] = 'Error en Sucursal';
				$msj['mensaje'] = 'Lo sentimos! No existe la sucursal seleccionada.';
				echo json_encode($msj);
				exit();
			}

			$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));

			$resp['respuesta'] = 'ok';
			$resp['sucursal'] = $sucursal;
			$resp['total_sucurles'] = count($sucursales);
			$resp['ubigeo'] = $ubigeo;
			echo json_encode($resp);
			exit();
		}
	}

	public function extraer_numeros($cadena)
	{
		$cadena = trim($cadena);
		$cadena = preg_replace('[\s+]', "", $cadena);
		$cadena2 = '';
		for ($i = 0; $i < strlen($cadena); $i++) {
			if (is_numeric($cadena[$i])) {
				$cadena2 .= $cadena[$i];
			}
		}
		return $cadena2;
	}

	public function get_html_primerospaso($id_contribuyente)
	{
		$html_primeros_pasos = '';
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if (!$contribuyente) {
			return $html_primeros_pasos;
		}

		$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if (!$sucursal) {
			$html_primeros_pasos = $html_primeros_pasos . '
			<div class="alert bg-info alert-styled-left" style="max-width: 1100px; margin: 0 auto;">
				<button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Recuerda: Antes de poder emitir tu primer comprobante electrónico debes registrar tu primera sucursal, allí deberás registrar las series para tus comprobantes electrónicos!. <a href="/facturacionv8/branchoffice">Puedes Registrar Tu Primera Sucursal Aquí</a></span>.
			</div>
			';
		}

		$producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if (!$producto) {
			$html_primeros_pasos = $html_primeros_pasos . '
			<div class="alert bg-success alert-styled-left" style="max-width: 1100px; margin: 0 auto;">
				<button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Para poder emitir tu primer comprobante electrónico debes agregar al menos un producto!. <a href="/facturacionv8/producto">Puedes Registrar Tus productos haciendo click aquí!</a></span>.
			</div>
			';
		}

		if ($contribuyente->tipo_envio_sunat == 'prueba') {
			$html_primeros_pasos = $html_primeros_pasos . '
			<div class="alert bg-primary alert-styled-left" style="max-width: 1100px; margin: 0 auto;">
				<button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Te encuentras en un ambiente de pruebas</a></span>.
			</div>
			';
		} else {
			$html_primeros_pasos = $html_primeros_pasos . '
			<div class="alert bg-success alert-styled-left" style="max-width: 1100px; margin: 0 auto;">
				<button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Felicidades! Ahora todo documento generado será reportado y enviado a sunat!... </span>.
			</div>
			';
		}

		return $html_primeros_pasos;
	}

	public function validate_date($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}

	public function validate_date_spanish($date)
	{
		$d = DateTime::createFromFormat('d/m/Y', $date);
		return $d && $d->format('d/m/Y') === $date;
	}

	public function validar_fecha_formato($date, $formato = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($formato, $date);
		return $d && $d->format($formato) === $date;
	}

	public function validate_date_time($date) {
		// Intentar con formato 'Y-m-d H:i:s'
		$d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
		if ($d && $d->format('Y-m-d H:i:s') === $date) {
			return true;
		}

		// Intentar con formato 'Y-m-d G:i:s'
		$d = DateTime::createFromFormat('Y-m-d G:i:s', $date);
		if ($d && $d->format('Y-m-d G:i:s') === preg_replace('/(\d{4}-\d{2}-\d{2}) 0*(\d{1,2}:\d{2}:\d{2})/', '$1 $2', $date)) {
			return true;
		}

		return false;
	}

	public function get_data_api_busquedas($tipo, $num_doc, $tipo_busqueda = 'completa')
	{
		if ($this->bloquear_busquedas) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Tenemos Problemas en los Servidores de SUNAT y RENIEC, ingresa los datos manualmente por favor...';
			return $resp;
		}

		$tipo_busqueda = 'completa';
		$password = $this->token_user; //AQUÍ TU PASSWORD 
		if ($tipo == 'dni') {
			$ruta = "https://facturalahoy.com/api/persona/" . $num_doc . '/' . $password . '/' . $tipo_busqueda;
		} elseif ($tipo == 'ruc') {
			$ruta = "https://facturalahoy.com/api/empresa/" . $num_doc . '/' . $password . '/' . $tipo_busqueda;
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Tipo de Documento Desconocido';
			return $resp;
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $ruta,
			CURLOPT_USERAGENT => 'Consulta Datos',
			CURLOPT_CONNECTTIMEOUT => 0,
			CURLOPT_TIMEOUT => 400,
			CURLOPT_FAILONERROR => true
		));

		$data = curl_exec($curl);
		if (curl_error($curl)) {
			$error_msg = curl_error($curl);
		}

		curl_close($curl);

		if (isset($error_msg)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['data'] = $data;
			$resp['encontrado'] = false;
			$resp['mensaje'] = 'Error en Api de Búsqueda';
			$resp['errores_curl'] = $error_msg;
			return $resp;
		}

		$data_resp = json_decode($data);
		if (!isset($data_resp->respuesta) || $data_resp->respuesta == 'error') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['encontrado'] = false;
			$resp['data_resp'] = $data;
			return $resp;
		}

		$departamento = '';
		$provincia = '';
		$distrito = '';
		$texto_ubigeo = '';

		if (isset($data_resp->codigo_ubigeo) && !empty($data_resp->codigo_ubigeo)) {
			$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $data_resp->codigo_ubigeo)));
			if ($ubigeo) {
				$departamento = $ubigeo->departamento;
				$provincia = $ubigeo->provincia;
				$distrito = $ubigeo->distrito;
				$texto_ubigeo = $departamento . ' - ' . $provincia . ' - ' . $distrito;
			}
		}

		if ($tipo == 'dni') {
			if (isset($data_resp->api->result->depaDireccion) && isset($data_resp->api->result->provDireccion) && isset($data_resp->api->result->distDireccion)) {
				$ubigeo = SunatCodigoubigeo::findFirst(array("departamento = :departamento: and provincia = :provincia: and distrito = :distrito:", 'bind' => array('departamento' => $data_resp->api->result->depaDireccion, 'provincia' => $data_resp->api->result->provDireccion, 'distrito' => $data_resp->api->result->distDireccion)));
				if ($ubigeo) {
					$departamento = $ubigeo->departamento;
					$provincia = $ubigeo->provincia;
					$distrito = $ubigeo->distrito;
					$texto_ubigeo = $departamento . ' - ' . $provincia . ' - ' . $distrito;
					$resp['texto_ubigeo'] = $texto_ubigeo;
					$resp['codigo_ubigeo'] = $ubigeo->codigo_ubigeo;
				}
			}
		}


		$resp['respuesta'] = 'ok';
		$resp['encontrado'] = true;
		$resp['api'] = true;
		$resp['data'] = json_decode($data);
		$resp['texto_ubigeo'] = $texto_ubigeo;

		return $resp;
	}

	public function getSugerenciasClientesAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$clientes = Cliente::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$listaclientes = array();
			foreach ($clientes as $cliente) {
				if ($cliente->idcliente > 0) {
					$listaclientes[] = array('razon_social' => $cliente->razon_social, 'num_doc' => $cliente->num_doc);
				}
			}

			echo json_encode($listaclientes);
			exit();
		}
	}

	public function getSugerenciasClientesMejoradoAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if (!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Lo sentimos la empresa no existe!';
				echo json_encode($resp);
				exit();
			}

			$array_lista = array();
			$tipo_docidentidad = intval($datapost['tipo_docidentidad']) + 0;

			if ($tipo_docidentidad != 1 && $tipo_docidentidad != 6 && $tipo_docidentidad != 0 && $tipo_docidentidad != 4 && $tipo_docidentidad != 7) {
				echo json_encode($array_lista);
				exit();
			}

			$termino = trim($datapost['keyword']);
			if (strlen($termino) <= 2) {
				echo json_encode($array_lista);
				exit();
			}

			$termino = '%' . $termino . '%';
			$query = "SELECT razon_social, num_doc FROM `cliente` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodocidentidad = '$tipo_docidentidad' and (razon_social LIKE :termino or num_doc like :termino) LIMIT 20";

			try {
				$sentencia = $this->db->prepare($query);
				$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
				$sentencia->execute();
			} catch (Exception $e) {
                $this->saveLogger($e);
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				exit();
			}

			$n = 0;
			while ($fila = $sentencia->fetch()) {
				$fila = (object) $fila;
				$array_lista[] = array('razon_social' => $fila->razon_social, 'num_doc' => $fila->num_doc);
			}

			echo json_encode($array_lista);
			exit();
		}
	}

	public function getSugerenciasProveedorAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if (!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Lo sentimos la empresa no existe!';
				echo json_encode($resp);
				exit();
			}

			$array_lista = array();
			$tipo_docidentidad = intval($datapost['tipo_docidentidad']) + 0;
			if ($tipo_docidentidad != 1 && $tipo_docidentidad != 6 && $tipo_docidentidad != 0) {
				echo json_encode($array_lista);
				exit();
			}


			$termino = trim($datapost['keyword']);
			if (strlen($termino) <= 2) {
				echo json_encode($array_lista);
				exit();
			}

			$termino = '%' . $termino . '%';
			$query = "SELECT razon_social, num_doc FROM `proveedor` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodocidentidad = '$tipo_docidentidad' and (razon_social LIKE :termino or num_doc like :termino) LIMIT 20";

			try {
				$sentencia = $this->db->prepare($query);
				$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
				$sentencia->execute();
			} catch (Exception $e) {
                $this->saveLogger($e);
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				exit();
			}

			$n = 0;
			while ($fila = $sentencia->fetch()) {
				$fila = (object) $fila;
				$array_lista[] = array('razon_social' => $fila->razon_social, 'num_doc' => $fila->num_doc);
			}

			echo json_encode($array_lista);
			exit();
		}
	}

	public function getSugerenciasUbigeosAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$termino = str_replace('  ', ' ', trim($datapost['q']));
			$trozos = explode(" ", $termino);
			$numero_trozos = count($trozos);
			if ($numero_trozos <= 1) {
				$termino = '%' . $termino . '%';
				$query = "select * from sunat_codigoubigeo where (codigo_ubigeo like :termino or departamento like :termino or provincia like :termino or distrito like :termino)";
			} else {
				//https://desarrolloweb.com/articulos/2087.php
				$termino = "'" . $termino . "'";
				$query = "select * from sunat_codigoubigeo where MATCH (codigo_ubigeo, departamento, provincia, distrito) AGAINST (:termino)";
			}

			try {
				$sentencia = $this->db->prepare($query);
				$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
				//$sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
				$sentencia->execute();
			} catch (Exception $e) {
                $this->saveLogger($e);
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				exit();
			}

			$lista = array();
			while ($item = $sentencia->fetch()) {
				$fila = (object) $item;
				$lista[] = array('id' => $fila->codigo_ubigeo, 'text' => $fila->departamento . ' - ' . $fila->provincia . ' - ' . $fila->distrito);
			}

			$resp['items'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getSugerenciasProductoAction() {
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$inicio_proceso = $this->inicio_ejecucion();

			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$resp = $this->get_sugerencias_producto($datapost, $usuario->id_contribuyente);
			echo json_encode($resp);
			exit();
		}
	}

	public function get_sugerencias_producto($datapost, $id_contribuyente) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$termino_busqueda = isset($datapost['q']) ? $datapost['q'] : '';
		if (empty($termino_busqueda)) {
			return array(); // Respuesta vacía
		}

		$idsucursal = empty($datapost['idsucursal']) ? 0 : intval($datapost['idsucursal']);
		$termino = preg_replace('/\s+/', ' ', trim($termino_busqueda));

		$page = isset($datapost['page']) ? intval($datapost['page']) : 1;
		$limite_inferior = ($page - 1) * 30;
		$limite_superior = 30;

		$total_rows = 200;
		$incomplete_results = true;
		if ($limite_superior >= $total_rows) {
			$incomplete_results = false;
		}
		
		$trozos = preg_split('/\s+/', $termino);
    	$numero_trozos = count($trozos);

		$num_letras = strlen(preg_replace('/\s+/', '', $termino));
		$where_sucursal = '';

		if ($contribuyente) {
			if ($contribuyente->multi_almacen == 'si') {
				if ($idsucursal > 0) {
					$where_sucursal = ' and idsucursal = ' . $idsucursal;
				}
			}
		}
		
		if ($numero_trozos <= 1) {
			$termino = '%' . $termino . '%';
			$query = "select * from producto where id_contribuyente = :id_contribuyente and estado = 'activo' and (nombre LIKE :termino or codigo LIKE :termino or codigos_presentaciones like :termino) $where_sucursal ORDER BY codigo, nombre ASC limit $limite_inferior, $limite_superior";
		} else {
			// Escapar caracteres especiales en las palabras
			$escaped_words = array_map(function($word) {
				return preg_quote($word, '/');
			}, $trozos);
	
			// Unir las palabras con '.*' para crear el patrón
			$pattern = implode('.*', $escaped_words);
			$termino = $pattern;

			$query = "SELECT * FROM producto WHERE (nombre RLIKE :termino OR codigo RLIKE :termino OR codigos_presentaciones RLIKE :termino) AND id_contribuyente = :id_contribuyente and estado = 'activo' $where_sucursal  limit $limite_inferior, $limite_superior ";
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e, 'Término de consulta: {'.$termino.'}');
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error en la consulta.'; //$e->getMessage()
			return $resp;
		}


		$lista = array();
		while ($item = $sentencia->fetch()) {
			$fila = (object) $item;
			$texto_unidad_medida = '';
			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $fila->id_unidad_medida)));
			if(!$unidadmedida) {
				$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => 7)));
			}
			
			$moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $fila->id_cod_moneda)));
			$categoria = Categoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $fila->id_categoria)));
			if (!$categoria) {
				$nombre_categoria = 'Sin Categorizar';
			} else {
				$nombre_categoria = $categoria->nombre;
			}

			if ($unidadmedida) {
				if (isset($unidadmedida->nombre)) {
					$texto_unidad_medida = $unidadmedida->nombre . ' (' . $unidadmedida->simbolo . ')';
				}
			}

			if ($fila->id_tipoafectacionigv == 10 || $fila->id_tipoafectacionigv == 7152) {
				$precio_producto = $fila->valor_con_igv + 0;
			} else {
				$precio_producto = $fila->valor_sin_igv + 0;
			}

			$imagen_producto = '';
			if (!empty($fila->foto)) {
				$imagen_producto = $fila->foto;
			}

			$presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $fila->idproducto)));
			$unidades_presentacion = array();
			if(!empty($texto_unidad_medida)) {
				$unidades_presentacion[] = $unidadmedida->nombre;
			}
			foreach ($presentaciones as $presentacion) {
				$unidad_presentacion = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->idunidad)));
				$unidades_presentacion[] = $unidad_presentacion->nombre;
			}

			$texto_presentaciones = '';
			if (count($unidades_presentacion) > 0) {
				$texto_presentaciones = ' - PRESENTACIÓN: ' . implode(', ', array_unique($unidades_presentacion));
			}

			$codigo = '';
			if (isset($fila->codigo) && !empty($fila->codigo)) {
				$codigo = $fila->codigo;
			}

			$lista[] = array(
				'top' => isset($fila->top) ? $fila->top : 99,
				'id' => $fila->idproducto,
				'text' => $fila->nombre,
				'unidad' => $texto_unidad_medida,
				'precio' => $moneda->simbolo . ' ' . $precio_producto,
				'categoria' => $nombre_categoria,
				'codigo' => $codigo . ' ' . $texto_presentaciones,
				'imagen' => $imagen_producto,
				'stock'	=> ($fila->stock + 0),
				'id_unidad_medida' => $unidadmedida->idunidad
			);
		}

		$resp['total_count'] = $total_rows;
		$resp['incomplete_results'] = $incomplete_results;
		$resp['limite_inferior'] = $limite_inferior;
		$resp['limite_superior'] = $limite_superior;
		$resp['page'] = $page;
		$resp['items'] = $lista;
		return $resp;
	}

	public function calcular_total_rows_consulta_producto($termino, $contribuyente, $idsucursal = 0)
	{
		$trozos = explode(" ", $termino);
		$numero_trozos = count($trozos);
		$num_letras = strlen(preg_replace('/\s+/', '', $termino));

		$where_sucursal = '';
		if ($contribuyente) {
			if ($contribuyente->multi_almacen == 'si') {
				if ($idsucursal > 0) {
					$where_sucursal = ' and idsucursal = ' . $idsucursal;
				}
			}
		}

		if ($numero_trozos <= 1 || $num_letras <= 5) {
			$termino = '%' . $termino . '%';
			$query = "select * from producto where estado = 'activo' and (nombre like :termino or codigo like :termino) and estado = 'activo' and id_contribuyente = :id_contribuyente $where_sucursal ";
		} else {
			//https://desarrolloweb.com/articulos/2087.php
			//$query = "select * from producto where estado = 'activo' and MATCH (codigo, nombre) AGAINST (:termino) and id_contribuyente = :id_contribuyente ";
			//$query = "SELECT producto.* FROM `producto` where id_contribuyente = :id_contribuyente and MATCH (codigo, nombre) AGAINST (:termino) ";
			$query = "SELECT idproducto FROM (
				(SELECT 999 as top, producto.* FROM `producto` WHERE id_contribuyente = :id_contribuyente and estado = 'activo' and nombre = :termino $where_sucursal order by top )
				
				UNION 
				
				(SELECT 1000 as top, producto.* FROM `producto` WHERE id_contribuyente = :id_contribuyente and estado = 'activo' and codigo = :termino $where_sucursal order by top )
				
				UNION 
				 
				(
					SELECT MATCH (codigo, nombre) AGAINST (:termino) as top, producto.* FROM `producto` WHERE id_contribuyente = :id_contribuyente and estado = 'activo' and MATCH (codigo, nombre) AGAINST (:termino) $where_sucursal and 
					idproducto not in (select idproducto FROM `producto` WHERE id_contribuyente = :id_contribuyente and estado = 'activo' and nombre = :termino $where_sucursal) and 
					idproducto not in (SELECT idproducto FROM `producto` WHERE id_contribuyente = :id_contribuyente and estado = 'activo' and codigo = :termino $where_sucursal) order by top
				) 
			) as resultado
			 ORDER BY resultado.top DESC ";
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			exit();
		}

		return $sentencia->rowCount();
	}

	public function getSugerenciasProducto2Action()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$idsucursal = empty($datapost['idsucursal']) ? 0 : intval($datapost['idsucursal']);
			$termino = str_replace('  ', ' ', trim($datapost['q']));
			$trozos = explode(" ", $termino);
			$numero_trozos = count($trozos);
			if ($numero_trozos <= 1) {
				$termino = '%' . $termino . '%';
				$query = "select * from producto where estado = 'activo' and (nombre like :termino or codigo like :termino) and id_contribuyente = :id_contribuyente";
			} else {
				//https://desarrolloweb.com/articulos/2087.php
				$termino = "'" . $termino . "'";
				$query = "select * from producto where estado = 'activo' and MATCH (codigo, nombre) AGAINST (:termino) and id_contribuyente = :id_contribuyente";
			}

			if ($contribuyente) {
				if ($contribuyente->multi_almacen == 'si') {
					if ($idsucursal > 0) {
						$query = $query . ' and idsucursal = ' . $idsucursal;
					}
				}
			}

			try {
				$sentencia = $this->db->prepare($query);
				$sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
				$sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
				$sentencia->execute();
			} catch (Exception $e) {
                $this->saveLogger($e);
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				exit();
			}

			$lista = array();
			while ($item = $sentencia->fetch()) {
				$fila = (object) $item;
				$texto_unidad_medida = '';
				$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $fila->id_unidad_medida)));
				$moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $fila->id_cod_moneda)));

				if ($fila->id_tipoafectacionigv == 10 || $fila->id_tipoafectacionigv == 7152) {
					$precio_producto = $fila->valor_con_igv + 0;
				} else {
					$precio_producto = $fila->valor_sin_igv + 0;
				}

				if (isset($unidadmedida->nombre)) {
					$texto_unidad_medida = ' - ' . $unidadmedida->nombre . ' (' . $unidadmedida->simbolo . ') - Precio: ' . $moneda->simbolo . ' ' . $precio_producto;
				}
				$lista[] = array('id' => $fila->idproducto, 'text' => $fila->nombre . $texto_unidad_medida);
			}

			$resp['items'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getSugerenciasDocelectronicoAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			$listadocumentos = array();
			if (!$usuario) {
				$listadocumentos[] = array('id' => '', 'text' => '');
				$resp['items'] = $listadocumentos;
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$termino = str_replace('  ', ' ', trim($datapost['q']));
			$tipo_doc = $datapost['id_tipodoc'];
			if ($tipo_doc != '01' && $tipo_doc != '03') {
				$listadocumentos[] = array('id' => '', 'text' => '');
				$resp['items'] = $listadocumentos;
				echo json_encode($resp);
				exit();
			}

			$documentos = DocElectronico::find(array("CONCAT(serie_comprobante, '-', numero_comprobante) like :termino: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'id_tipodoc_electronico' => $tipo_doc, 'termino' => '%' . $termino . '%')));
			foreach ($documentos as $documento) {
				$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));
				$listadocumentos[] = array('id' => $documento->serie_comprobante . ',' . $documento->numero_comprobante, 'text' => $documento->serie_comprobante . '-' . $documento->numero_comprobante . ' (' . $tipo_doc_electronico->descripcion . ')');
			}

			if (count($listadocumentos) <= 0) {
				$listadocumentos[] = array('id' => '', 'text' => '');
			}

			$resp['items'] = $listadocumentos;
			echo json_encode($resp);
			exit();
		}
	}

	public function crear_carpeta($ruta_carpeta)
	{
		if (!file_exists($ruta_carpeta)) {
			if (!mkdir($ruta_carpeta, 0700, true)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No hemos logrado crear el directorio para guardar tu certificado electrónico.';
				return $resp;
			}
		}

		$resp['respuesta'] = 'ok';
		return $resp;
	}

	public function comparar_fechas($primera, $segunda)
	{
		$primera = date("Y-m-d", strtotime($primera));
		$segunda = date("Y-m-d", strtotime($segunda));
		$valoresPrimera = explode("-", $primera);
		$valoresSegunda = explode("-", $segunda);
		$diaPrimera    = intval($valoresPrimera[2]);
		$mesPrimera  = intval($valoresPrimera[1]);
		$anyoPrimera   = intval($valoresPrimera[0]);
		$diaSegunda   = intval($valoresSegunda[2]);
		$mesSegunda = intval($valoresSegunda[1]);
		$anyoSegunda  = intval($valoresSegunda[0]);
		$diasPrimeraJuliano = $this->gregoriantojd_custom($mesPrimera, $diaPrimera, $anyoPrimera);
		$diasSegundaJuliano = $this->gregoriantojd_custom($mesSegunda, $diaSegunda, $anyoSegunda);
		if (!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha: ' . $primera . ', no es válida.';
			return $resp;
		} elseif (!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha: ' . $segunda . ', no es válida.';
			return $resp;
		} else {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'ok';
			$resp['dias_primera_fecha'] = $diasPrimeraJuliano;
			$resp['dias_segunda_fecha'] = $diasSegundaJuliano;
			$resp['diferencia_primera_segunda'] = $diasPrimeraJuliano - $diasSegundaJuliano;
			return $resp;
		}
	}

	private function gregoriantojd_custom($mes, $dia, $anyo)
	{
		if ($mes > 2) {
			$Y = $anyo;
			$M = $mes;
		} else {
			$Y = $anyo - 1;
			$M = $mes + 12;
		}

		$A = floor($Y / 100);
		$B = 2 - $A + floor($A / 4);
		$JD = floor(365.25 * ($Y + 4716)) + floor(30.6001 * ($M + 1)) + $dia + $B - 1524;

		return $JD;
	}

	public function verificar_validez_email($email)
	{
		if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$^", $email)) {
			return false;
		}

		return true;
	}

	public function envio_api_sunat($data, $ruta, $token = '')
	{
		$data['ruc_proveedor'] = $this->ruc_proveedor;
		$data['token_cliente'] = $this->token_user;
		$data_json = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Authorization: Token token="' . $token . '"',
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);
		return $respuesta;
	}

	public function get_status_doc_sunat($data)
	{
		$data['ruc_proveedor'] = $this->ruc_proveedor;
		$data['token_cliente'] = $this->token_user;
		$token = $this->token_user;
		$ruta = 'https://facturalahoy.com/api/estadodocumento';
		$data_json = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Authorization: Token token="' . $token . '"',
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);
		return $respuesta;
	}

	public function saveUser($email_login,  $password_login, $ruc,  $nombre_comercial, $telefono, $ubigeo)
	{
		/*
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
			echo json_encode($resp);
			exit();
		}

		return $usuario;
		*/
		return false;
	}

	public function get_num_docnooficial($id_contribuyente, $id_tipodocumento, $modalidad)
	{

		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'modalidad' => $modalidad), "order" => "numero_comprobante DESC"));

		if (!$documento) {
			return 1;
		}

		$numero_doc = intval($documento->numero_comprobante) + 1;
		return $numero_doc;
	}

	public function enviarCpeEmailAction()
	{
		$this->view->disable();
		$request = $this->request;
		if ($request->isAjax() == true) {
			$datapost = $this->request->getPost();
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if (!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión.';
				echo json_encode($resp);
				exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$id_tipodoc_electronico = empty($datapost['tipo_doc']) ? '' : $datapost['tipo_doc'];
			$serie_comprobante = empty($datapost['serie_doc']) ? '' : $datapost['serie_doc'];
			$numero_comprobante = empty($datapost['numero_comprobante']) ? 0 : intval($datapost['numero_comprobante']);
			$email = empty($datapost['email']) ? '' : $datapost['email'];

			if (empty($email)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Ingresa un Email Válido';
				echo json_encode($resp);
				exit();
			}

			if ($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
				$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			} else if ($id_tipodoc_electronico == '77' || $id_tipodoc_electronico == '88') {
				$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error Documento';
				$resp['mensaje'] = 'El documento electrónico no se encuentra';
				return $resp;
			}

			if (!$documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El Documento no Existe';
				echo json_encode($resp);
				exit();
			}

			$enviar_email = new EnviaremailController;
			$resp_email = $enviar_email->enviar_email_documento($contribuyente->id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $contribuyente->tipo_envio_sunat, $email);
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Envío Correcto';
			$resp['mensaje'] = 'El email ha sido enviado correctamente, puede tomar hasta 5 minutos en llegar al destinatario!';
			echo json_encode($resp);
			exit();
		}
	}

	public function get_msg_guardar_doc($msg_color, $mensaje, $url_a4, $url_ticket, $url_whatsapp)
	{
		if (empty($url_a4)) {
			$url_a4 = 'javascript:void(0)';
		}

		if (empty($url_ticket)) {
			$url_ticket = 'javascript:void(0)';
		}

		if (empty($url_whatsapp)) {
			$url_whatsapp = 'href="javascript:void(0)"';
		} else {
			$url_whatsapp = 'href="' . $url_whatsapp . '" target="_blank"';
		}

		$onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF" . rand() . "'); w.print();";
		$onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF" . rand() . "'); w.print();";

		$html = $mensaje;
		/*
		$html = '
		<div class="alert alert-'.$msg_color.' alert-bordered">							
			'.$mensaje.'
		</div>
		<div class="content-group" style="width: 300px; text-align: center; max-width: 300px;margin: 0 auto;">
			<div class="row row-seamless btn-block-group">
				<div class="col-xs-6">
					<a onclick="'.$onclick_a4.'" href="javascript:void(0);" class="btn btn-default btn-block btn-float btn-float-lg legitRipple">
						<img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 32px;">
						<span>Imprimir A4</span>
					</a>

					<a onclick="'.$onclick_ticket.'" href="javascript:void(0);" class="btn btn-default btn-block btn-float btn-float-lg legitRipple">
						<img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 32px;">
						<span>Imprimir Ticket</span>
					</a>
				</div>

				<div class="col-xs-6">
					<a '.$url_whatsapp.' class="btn btn-default btn-block btn-float btn-float-lg legitRipple">
						<i class="fa fa-whatsapp text-success"></i>
						<span>Enviar a Whatsapp</span>
					</a>

					<a href="/facturacionv8/dashboard" class="btn btn-default btn-block btn-float btn-float-lg legitRipple">
						<img src="/facturacionv8/img/lista_documentos.svg" style="width: 32px;">
						<span>Lista Documentos</span>
					</a>
				</div>
			</div>
		</div>
		';
		*/
		return $html;
	}

	public function limpia_espacios($cadena)
	{
		$cadena = trim($cadena);
		$cadena = preg_replace('[\s+]', "", $cadena);
		$cadena2 = '';
		for ($i = 0; $i < strlen($cadena); $i++) {
			if (is_numeric($cadena[$i])) {
				$cadena2 .= $cadena[$i];
			}
		}
		return $cadena2;
	}

	public function encriptar($simple_string)
	{
		$ciphering = "AES-128-CTR";
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		$encryption_iv = '1234567891011121';
		$encryption_key = 'w0MIPD7bk6qVVB7';
		$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
		return $encryption;
	}

	public function desencriptar($encryption)
	{
		$ciphering = "AES-128-CTR";
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		$decryption_iv = '1234567891011121';
		$decryption_key = "w0MIPD7bk6qVVB7";
		$decryption = openssl_decrypt($encryption, $ciphering, $decryption_key, $options, $decryption_iv);
		return $decryption;
	}

	public function get_string_between($string, $start, $end)
	{
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	public function zero_fill($valor, $long = 0)
	{
		return str_pad($valor, $long, '0', STR_PAD_LEFT);
	}

	public function get_logo_banco($cuenta)
	{
		$id_entidadfinanciera = 99;
		if (!empty($cuenta->id_entidadfinanciera)) {
			$id_entidadfinanciera = $cuenta->id_entidadfinanciera;
		}

		$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/otro_banco.png';

		if ($id_entidadfinanciera == 99) {
			if (strpos(strtolower($cuenta->nombre_banco), 'interbank') !== false) {
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/interbank.jpg';
			}

			if (strpos(strtolower($cuenta->nombre_banco), 'banco de crédito') !== false || strpos(strtolower($cuenta->nombre_banco), 'BCP') !== false) {
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/logobcp.jpg';
			}

			if (strpos(strtolower($cuenta->nombre_banco), 'bbva') !== false || strpos(strtolower($cuenta->nombre_banco), 'continental') !== false) {
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/bbva.jpg';
			}

			if (strpos(strtolower($cuenta->nombre_banco), 'nación') !== false || strpos(strtolower($cuenta->nombre_banco), 'nacion') !== false) {
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/bc_nacion.jpg';
			}

			if (strpos(strtolower($cuenta->nombre_banco), 'scotiabank') !== false || strpos(strtolower($cuenta->nombre_banco), 'scotia bank') !== false) {
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/scotiabank.png';
			}
		} else {
			$sunat_entidadfinanciera = SunatCodigoentidadfinanciera::findFirst(array("id_entidadfinanciera = :id_entidadfinanciera:", 'bind' => array('id_entidadfinanciera' => $id_entidadfinanciera)));
			if (!$sunat_entidadfinanciera) {
				$sunat_entidadfinanciera = SunatCodigoentidadfinanciera::findFirst(array("id_entidadfinanciera = :id_entidadfinanciera:", 'bind' => array('id_entidadfinanciera' => 99)));
			}

			$logo_banco = $sunat_entidadfinanciera->logo;
		}

		return $logo_banco;
	}

	function eliminar_tildes($cadena)
	{

		//Codificamos la cadena en formato utf8 en caso de que nos de errores
		//$cadena = utf8_encode($cadena);

		//Ahora reemplazamos las letras
		$cadena = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$cadena
		);

		$cadena = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$cadena
		);

		$cadena = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$cadena
		);

		$cadena = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$cadena
		);

		$cadena = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$cadena
		);

		$cadena = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C'),
			$cadena
		);

		return $cadena;
	}

	public function get_abonos_cpe($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat)
	{
		$query_abonos = "SELECT * FROM `monto_cobrado` where id_contribuyente = :id_contribuyente and id_tipodoc_electronico = :id_tipodoc_electronico and serie_comprobante = :serie_comprobante and numero_comprobante = :numero_comprobante and tipo_envio_sunat = :tipo_envio_sunat and estado = 'activo'";
		
		try {
			$sentencia = $this->db->prepare($query_abonos);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
			$sentencia->bindParam(':serie_comprobante', $serie_comprobante, PDO::PARAM_STR);
			$sentencia->bindParam(':numero_comprobante', $numero_comprobante, PDO::PARAM_INT);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (get_abonos_cpe) ',  $e->getMessage(), "\n";
			exit();
		}

		$lista_abonos = array();
		$monto_total_abonos = 0;
		while ($fila_abono = $sentencia->fetch()) {
			$item_abono = (object) $fila_abono;
			$condiciondepago_abono = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", "bind" => array('id_condicionpago' => $item_abono->id_condicionpago)));
			$nombre_condicion_pago_abono = 'Contado';
			if ($condiciondepago_abono) {
				$nombre_condicion_pago_abono = $condiciondepago_abono->condicionpago;
			}

			if ($item_abono->tipo_abono == 'retencion') {
				$nombre_condicion_pago_abono = 'Retención/Depósito';
			}

			if ($item_abono->id_codigomoneda == 'PEN') {
				$simbolo_moneda_abono = 'S/. ';
			} else {
				$simbolo_moneda_abono = 'USD ';
			}

			$monto_total_abonos = $monto_total_abonos + $item_abono->total;
			$nombre_banco = '';
			if (!empty($item_abono->idbanco)) {
				$banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $item_abono->idbanco)));
				if ($banco) {
					$nombre_banco = $banco->nombre_banco;
				}
			}

			$lista_abonos[] = array(
				'condicion_pago'	=> $nombre_condicion_pago_abono,
				'monto'				=> $item_abono->total,
				'fecha_registro'	=> date("d-m-Y / H:i A", strtotime($item_abono->fecha_registro)),
				'moneda'			=> $simbolo_moneda_abono,
				'numero_operacion'	=> empty($item_abono->cpago_nrooperacion) ? '' : $item_abono->cpago_nrooperacion,
				'idbanco'			=> $item_abono->idbanco,
				'nombre_banco'		=> $nombre_banco,
				'fecha_deposito'	=> empty($item_abono->fechadeposito) ? '' : date("d-m-Y / H:i A", strtotime($item_abono->fechadeposito))
			);
		}

		return $lista_abonos;
	}

	public function valida_caracteres_especiales($string)
	{
		if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string)) {
			return true;
		}

		if ($string == trim($string) && strpos($string, ' ') !== false) {
			return true;
		}

		if (!preg_match('/[^A-Za-z0-9]/', $string)) { } else {
			return true;
		}

		return false;
	}

	function create_guid()
	{
		if (function_exists('com_create_guid') === true) {
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	public function separar_nombre_apellido($nombreCompleto, $apellido_primero = false)
	{
		$chunks = ($apellido_primero)
			? explode(" ", strtoupper($nombreCompleto))
			: array_reverse(explode(" ", strtoupper($nombreCompleto)));
		$exceptions = ["DE", "LA", "DEL", "LOS", "SAN", "SANTA", "DA", "LAS", "MAC", "MC", "VAN", "VON", "Y", "I"];

		$existen = array_intersect($chunks, $exceptions);
		$nombre = array("Materno" => "", "Paterno" => "", "Nombres" => "");
		$agregar_en = ($apellido_primero)
			? "paterno"
			: "materno";
		$primera_vez = true;
		if ($apellido_primero) {
			if (!empty($existen)) {
				foreach ($chunks as $chunk) {
					if ($primera_vez) {
						$nombre["Paterno"] = $nombre["Paterno"] . " " . $chunk;
						$primera_vez = false;
					} else {
						if (in_array($chunk, $exceptions)) {
							if ($agregar_en == "paterno")
								$nombre["Paterno"] = $nombre["Paterno"] . " " . $chunk;
							elseif ($agregar_en == "materno")
								$nombre["Materno"] = $nombre["Materno"] . " " . $chunk;
							else
								$nombre["Nombres"] = $nombre["Nombres"] . " " . $chunk;
						} else {
							if ($agregar_en == "paterno") {
								$nombre["Paterno"] = $nombre["Paterno"] . " " . $chunk;
								$agregar_en = "materno";
							} elseif ($agregar_en == "materno") {
								$nombre["Materno"] = $nombre["Materno"] . " " . $chunk;
								$agregar_en = "nombres";
							} else {
								$nombre["Nombres"] = $nombre["Nombres"] . " " . $chunk;
							}
						}
					}
				}
			} else {
				foreach ($chunks as $chunk) {
					if ($primera_vez) {
						$nombre["Paterno"] = $nombre["Paterno"] . " " . $chunk;
						$primera_vez = false;
					} else {
						if (in_array($chunk, $exceptions)) {
							if ($agregar_en == "paterno")
								$nombre["Paterno"] = $nombre["Paterno"] . " " . $chunk;
							elseif ($agregar_en == "materno")
								$nombre["Materno"] = $nombre["Materno"] . " " . $chunk;
							else
								$nombre["Nombres"] = $nombre["Nombres"] . " " . $chunk;
						} else {
							if ($agregar_en == "paterno") {
								$nombre["Materno"] = $nombre["Materno"] . " " . $chunk;
								$agregar_en = "materno";
							} elseif ($agregar_en == "materno") {
								$nombre["Nombres"] = $nombre["Nombres"] . " " . $chunk;
								$agregar_en = "nombres";
							} else {
								$nombre["Nombres"] = $nombre["Nombres"] . " " . $chunk;
							}
						}
					}
				}
			}
		} else {
			foreach ($chunks as $chunk) {
				if ($primera_vez) {
					$nombre["Materno"] = $chunk . " " . $nombre["Materno"];
					$primera_vez = false;
				} else {
					if (in_array($chunk, $exceptions)) {
						if ($agregar_en == "materno")
							$nombre["Materno"] = $chunk . " " . $nombre["Materno"];
						elseif ($agregar_en == "paterno")
							$nombre["Paterno"] = $chunk . " " . $nombre["Paterno"];
						else
							$nombre["Nombres"] = $chunk . " " . $nombre["Nombres"];
					} else {
						if ($agregar_en == "materno") {
							$agregar_en = "paterno";
							$nombre["Paterno"] = $chunk . " " . $nombre["Paterno"];
						} elseif ($agregar_en == "paterno") {
							$agregar_en = "nombres";
							$nombre["Nombres"] = $chunk . " " . $nombre["Nombres"];
						} else {
							$nombre["Nombres"] = $chunk . " " . $nombre["Nombres"];
						}
					}
				}
			}
		}
		// LIMPIEZA DE ESPACIOS
		$nombre["materno"] = trim($nombre["Materno"]);
		$nombre["paterno"] = trim($nombre["Paterno"]);
		$nombre["nombres"] = trim($nombre["Nombres"]);
		return $nombre;
	}

	public function get_porcentajes_ganancia($precio_compra = 0, $precio_venta = 0, $precio_venta_minimo = 0)
	{
		$porcentaje_maximo = 0;
		$porcentaje_minimo = 0;

		if ($precio_compra <= 0 || $precio_venta <= 0 || $precio_compra >= $precio_venta) {
			$resp['respuesta'] = 'ok';
			$resp['porcentaje_maximo'] = $porcentaje_maximo;
			$resp['porcentaje_minimo'] = $porcentaje_minimo;
			return $resp;
		}

		$porcentaje_maximo = round(((floatval($precio_venta) * 100) / floatval($precio_compra)) - 100, 4);

		if ($precio_venta_minimo > 0 && $precio_venta_minimo > $precio_compra) {
			$porcentaje_minimo = round(((floatval($precio_venta_minimo) * 100) / floatval($precio_compra)) - 100, 4);
		}

		$resp['respuesta'] = 'ok';
		$resp['porcentaje_maximo'] = $porcentaje_maximo;
		$resp['porcentaje_minimo'] = $porcentaje_minimo;
		return $resp;
	}

	public function esUrlAbsoluta($url) {
        $esquemas = ['http:', 'https:', 'ftp:', 'ftps:', 'mailto:', 'tel:'];
    
        foreach ($esquemas as $esquema) {
            if (strpos($url, $esquema) === 0) {
                return true;
            }
        }
    
        return false;
    }

	public function truncateText($text, $maxLength = 27) {
		if (mb_strlen($text) > $maxLength) {
			return mb_substr($text, 0, $maxLength) . '...';
		} else {
			return $text;
		}
	}

	public function formatWhatsAppDate($dateString) {
		$date = new DateTime($dateString);
		$now = new DateTime();
		
		// Configurar el formateador para el nombre del día en español
		$dayFormatter = new IntlDateFormatter(
			'es_ES',
			IntlDateFormatter::FULL,
			IntlDateFormatter::NONE,
			'Europe/Madrid',
			IntlDateFormatter::GREGORIAN,
			'EEEE'
		);
	
		// Hoy
		if ($date->format('Y-m-d') === $now->format('Y-m-d')) {
			return $date->format('g:i A'); // Formato de 12 horas con AM/PM
		}
	
		// Ayer
		$yesterday = (clone $now)->modify('-1 day');
		if ($date->format('Y-m-d') === $yesterday->format('Y-m-d')) {
			return 'Ayer';
		}
	
		// Antes de ayer (últimos 6 días)
		for ($i = 2; $i <= 7; $i++) {
			$compareDate = (clone $now)->modify("-$i days");
			if ($date->format('Y-m-d') === $compareDate->format('Y-m-d')) {
				return ucfirst($dayFormatter->format($date));
			}
		}
	
		// Cualquier fecha anterior
		return $date->format('d/m/Y'); // Formato de fecha
	}

	public function obtener_fecha_mes($mes, $anio) {
		// Fecha de inicio (primer día del mes)
		$fecha_inicio = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $anio));
	
		// Fecha de fin (último día del mes)
		$fecha_fin = date("Y-m-d", mktime(0, 0, 0, $mes + 1, 0, $anio));
	
		return array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin);
	}
}