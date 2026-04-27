<?php
class SireController extends ControllerBase
{
	public function indexAction() {
		
	}

	public function ventasAction() {
		$this->setTitle('SIRE - Ventas');
        $this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
			
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
			->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
			->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
			->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
			->addJs($this->baseUri . "public/js/sire/ventas.js?i=".rand());

		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Código';
			$resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

		$herramientas = new HerramientasController();
		$sunat_u_sol_principal = '';
		if(!empty($contribuyente->sunat_u_sol_principal)) {
			$sunat_u_sol_principal = 'Registrado!';
		}

		$sunat_p_sol_principal = '';
		if(!empty($contribuyente->sunat_p_sol_principal)) {
			$sunat_p_sol_principal = 'Registrado!';
		}

		$sunat_client_id = '';
		if(!empty($contribuyente->sunat_client_id)) {
			$sunat_client_id = 'Registrado!';
		}

		$sunat_client_secret = '';
		if(!empty($contribuyente->sunat_client_secret)) {
			$sunat_client_secret = 'Registrado!';
		}

		$this->view->usuario = $usuario;
		$this->view->sunat_u_sol_principal = $sunat_u_sol_principal;
		$this->view->sunat_p_sol_principal = $sunat_p_sol_principal;
		$this->view->sunat_client_id = $sunat_client_id;
		$this->view->sunat_client_secret = $sunat_client_secret;
    }

    public function comprasAction() {
		$this->setTitle('SIRE - Compras');
        $this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
			
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
			->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
			->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
			->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
			->addJs($this->baseUri . "public/js/sire/compras.js?i=".rand());

		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Código';
			$resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

		$sunat_u_sol_principal = '';
		if(!empty($contribuyente->sunat_u_sol_principal)) {
			$sunat_u_sol_principal = 'Registrado!';
		}

		$sunat_p_sol_principal = '';
		if(!empty($contribuyente->sunat_p_sol_principal)) {
			$sunat_p_sol_principal = 'Registrado!';
		}

		$sunat_client_id = '';
		if(!empty($contribuyente->sunat_client_id)) {
			$sunat_client_id = 'Registrado!';
		}

		$sunat_client_secret = '';
		if(!empty($contribuyente->sunat_client_secret)) {
			$sunat_client_secret = 'Registrado!';
		}

		$this->view->usuario = $usuario;
		$this->view->sunat_u_sol_principal = $sunat_u_sol_principal;
		$this->view->sunat_p_sol_principal = $sunat_p_sol_principal;
		$this->view->sunat_client_id = $sunat_client_id;
		$this->view->sunat_client_secret = $sunat_client_secret;
    }

	public function guardarDataAccesoSunatAction() {
		$this->view->disable();
		$request = $this->request;
		if($request->isAjax() == true) 
		{
			$auth = $this->session->get('authv8');
			$idusuario = $auth['idusuario'];
			$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
			if(!$usuario) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes iniciar sesión!';
				echo json_encode($resp);
				exit();
			}

			$datapost = $this->request->getPost();

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se pudo obtener el contribuyente!';
				echo json_encode($resp);
				exit();
			}

			$usuario_sol_principal = $datapost['usuario_sol_principal'];
			$password_usuario_sol = $datapost['password_usuario_sol'];
			$cliente_id = isset($datapost['cliente_id'])? $datapost['cliente_id'] : '';
			$client_secret = isset($datapost['client_secret']) ? $datapost['client_secret'] : '';

			//validación de Usuario Sol y Clave SOL
			if(empty($usuario_sol_principal) || empty($password_usuario_sol)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar el Usuario SOL y Clave SOL!';
				echo json_encode($resp);
				exit();
			}

			$this->db->begin();
			$gestiondecontribuyentes = new GestiondecontribuyentesController;
			if($usuario_sol_principal == '' && $password_usuario_sol == '') {
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar el Usuario SOL y Clave SOL!';
				echo json_encode($resp);
				exit();
			} else if($usuario_sol_principal == 'Registrado!' && $password_usuario_sol != 'Registrado!') {
				$resp_valid_sol = $gestiondecontribuyentes->validar_credenciales_sol($contribuyente->ruc, $contribuyente->sunat_u_sol_principal, $password_usuario_sol);
				$contribuyente->sunat_p_sol_principal = $password_usuario_sol;
			} else if($usuario_sol_principal != 'Registrado!' && $password_usuario_sol == 'Registrado!') {
				$resp_valid_sol = $gestiondecontribuyentes->validar_credenciales_sol($contribuyente->ruc, $usuario_sol_principal, $contribuyente->sunat_p_sol_principal);
				$contribuyente->sunat_u_sol_principal = $usuario_sol_principal;
			} else if($usuario_sol_principal != 'Registrado!' && $password_usuario_sol != 'Registrado!') {
				$resp_valid_sol = $gestiondecontribuyentes->validar_credenciales_sol($contribuyente->ruc, $usuario_sol_principal, $password_usuario_sol);
				$contribuyente->sunat_p_sol_principal = $password_usuario_sol;
				$contribuyente->sunat_u_sol_principal = $usuario_sol_principal;
			}
			
			if(isset($resp_valid_sol)) {
				if(!$resp_valid_sol) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Su Clave SOl y Usuario SOL no son válidos!, por favor ingrese sus datos correctos y válidos!';
					echo json_encode($resp);
					exit();
				} else {
					try {
						if(!$contribuyente->save()) {
							$msg = '';
							foreach ($contribuyente->getMessages() as $message) {
								$msg .= $message . "<br>";
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
						$resp['mensaje'] = $e->getMessage();
						echo json_encode($resp);
						exit();
					}
				}
			}

			//validación de Cliente ID y Client Secret
			if(isset($cliente_id) && isset($client_secret) && !empty($cliente_id) && !empty($client_secret)) {
			
				if($cliente_id == 'Registrado!' && $client_secret != 'Registrado!') {
					$resp_valid_sol = $this->validar_sunat_client_id_secret($contribuyente, $contribuyente->sunat_client_id, $client_secret);
					$contribuyente->sunat_client_secret = $client_secret;
				} else if($cliente_id != 'Registrado!' && $client_secret == 'Registrado!') {
					$resp_valid_sol = $this->validar_sunat_client_id_secret($contribuyente, $cliente_id, $contribuyente->sunat_client_secret);
					$contribuyente->sunat_client_id = $cliente_id;
				} else if($cliente_id != 'Registrado!' && $client_secret != 'Registrado!') {
					$resp_valid_sol = $this->validar_sunat_client_id_secret($contribuyente, $cliente_id, $client_secret);
					$contribuyente->sunat_client_id = $cliente_id;
					$contribuyente->sunat_client_secret = $client_secret;
				}
				
				if(isset($resp_valid_sol)) {
					if($resp_valid_sol) {
						if($resp_valid_sol['respuesta'] == 'error') {
							$this->db->rollback();
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'Su Clave SOl y Usuario SOL no son válidos!, por favor ingrese sus datos correctos y válidos!';
							echo json_encode($resp);
							exit();
						} else {
							try {
								if(!$contribuyente->save()) {
									$msg = '';
									foreach ($contribuyente->getMessages() as $message) {
										$msg .= $message . "<br>";
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
								$resp['mensaje'] = $e->getMessage();
								echo json_encode($resp);
								exit();
							}
						}
					} else {
						$this->db->rollback();
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Su Cliente ID y Client Secret no son válidos!, por favor ingrese sus datos correctos y válidos!';
						echo json_encode($resp);
						exit();
					}				
				}
			}

			$this->db->commit();
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Éxito';
			$resp['mensaje'] = 'Se guardó correctamente!';
			echo json_encode($resp);
			exit();
		}
	}

	public function validar_sunat_client_id_secret($contribuyente, $cliente_id, $client_secret) {
		$data_api = array(
			"token" 				=> $this->token_user,
			"credenciales_sunat" 	=> array(
				"ruc" 			=> $contribuyente->ruc,
				"username" 		=> $contribuyente->sunat_u_sol_principal,
				"password" 		=> $contribuyente->sunat_p_sol_principal,
				"client_id" 	=> $cliente_id,
				"client_secret" => $client_secret
			),
			"accion" 				=> "consultar_lista_periodos_sire_ventas"
		);

		$sire_ventas = new SireventasController;

		$url = "https://facturalahoy.com/api/apisire/propuesta_ventas";
		$resp_sire = $sire_ventas->conexion_sire($url, $data_api);
		if($resp_sire->respuesta == 'error') {
			return false;
		}

		return true;
	}
}
?>