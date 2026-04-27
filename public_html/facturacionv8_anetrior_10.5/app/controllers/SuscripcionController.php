<?php
class SuscripcionController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Renovar Suscripción');
		$this->view->setTemplateAfter('vacio');
		
		$this->assets
		->addCss("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/plugins/bootstrap/css/bootstrap.min.css", false)
		->addCss("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/fonts/flaticon/flaticon.css", false)
		->addCss("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/fonts/fontawesome/css/all.css", false)
		->addCss("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/css/style.css", false)
		->addCss("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/css/responsive.css", false);
		$this->assets
			->addJs("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/js/jquery-3.2.1.slim.min.js", false)
			->addJs("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/js/popper.min.js", false)
			->addJs("https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/plugins/bootstrap/js/bootstrap.min.js", false)
			->addJs("https://checkout.culqi.com/js/v3", false)
			->addJs("https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js", false)
			->addJs($this->baseUri . "public/js/suscripcion.js?i=".rand());
			
		$this->view->culqi_public_key = $this->culqi_public_key_prueba;

		$this->view->id_plan_mensual_97 = 1;
		$this->view->monto_plan_mensual_97 = 97;
		$this->view->id_plan_anual_97 = 1;
		$this->view->monto_plan_anual_97 = 873;

		$this->view->id_plan_mensual_129 = 1;
		$this->view->monto_plan_mensual_129 = 129;
		$this->view->id_plan_anual_129 = 1;
		$this->view->monto_plan_anual_129 = 1161;

		$this->view->id_plan_mensual_139 = 1;
		$this->view->monto_plan_mensual_139 = 139;
		$this->view->id_plan_anual_139 = 1;
		$this->view->monto_plan_anual_139 = 1773;
	}
	
	public function validar_suscripcion($id_contribuyente) {
		$resp['tipo_acceso'] = 'libre'; //libre,
		$resp['tiene_suscripcion'] = 'no';
        $resp['fecha_expira_suscripcion'] = '';
		$resp['dias_restantes_suscripcion'] = 0;
		$resp['mensaje_expira'] = '';
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		if($contribuyente->id_contribuyente == 1) {
			return $resp;
		}

		//si el usuario está en modo prueba, entonces se le deja acceso libre
		if($contribuyente->tipo_envio_sunat == 'prueba') {
			//aquí también se debería verificar si algún usuario está utilizando el sistema para control interno solamente
			return $resp;
		}

		if($contribuyente->id_patrocinador == 1) {
			$resp['mensaje_expira'] = 'Debe Realizar su Depósito a la Siguiente Cuenta en el BCP: 245-9603-5269-0-47 a Nombre de FacturalaYa SRL, y luego enviar una captura del voucher via whatsapp al siguiente número: 956295282 incluyendo tu número de RUC.';
		} else {
			$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));
			$resp['mensaje_expira'] = 'Puedes contactar al siguiente número de celular: '.$patrocinador->telefono.', y/o al siguiente email: '.$patrocinador->email.'.';
		}

		//aquí verificamos si el contribuyente es un patrocinador o socio estratégico
		$usuario_patrocinador = Usuario::findFirst(array("id_rol = :id_rol: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_rol' => 5, 'id_contribuyente' => $contribuyente->id_contribuyente)));
		if($usuario_patrocinador && $contribuyente->tipo_empresa == 1) {
			return $resp;
		}

		//1.- verificamos si el contribuyente tiene alguna suscripción activa.
		$suscripcion = Suscripcion::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and pago_verificado = 'si'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "fecha_fin DESC"));
		if(!$suscripcion) {
			$resp['tipo_acceso'] = 'restringido';
			return $resp;
		}
		$herramientas = new HerramientasController;

		//Aquí ingresa si el usuario ya tiene suscripciones activas...
		$resp_fecha = $herramientas->comparar_fechas($suscripcion->fecha_fin, date('Y-m-d'));
		$resp['tipo_acceso'] = 'restringido';
		$resp['tiene_suscripcion'] = 'si';
        $resp['fecha_expira_suscripcion'] = date("d-m-Y / H:i A", strtotime($suscripcion->fecha_fin));
		$resp['dias_restantes_suscripcion'] = $resp_fecha['diferencia_primera_segunda'];

		return $resp;
	}

	public function get_html_suscripcion($data_suscripcion) {
        $html = '';
		$suscripcion_activa = 'si';
		
		if($data_suscripcion['mensaje_expira'] != '') {
			$data_suscripcion['mensaje_expira'] = ' <div class="alert alert-primary alert-styled-right" style="margin-top: 25px;"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>'.$data_suscripcion['mensaje_expira'].'</div>';
		}

		if($data_suscripcion['tipo_acceso'] == 'libre') {
			$resp['html'] = $html;
			$resp['suscripcion_activa'] = $suscripcion_activa;
			return $resp;
		}

		if($data_suscripcion['tiene_suscripcion'] == 'no') {
			$html = '
			<div class="alert alert-warning alert-styled-right" style="margin-top: 25px;">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				Actualmente no tienes una suscripción activa! por favor contacta con SOPORTE... 
			</div>
			';
			$resp['html'] = $html.$data_suscripcion['mensaje_expira'];
			$resp['suscripcion_activa'] = 'no';
			return $resp;
		}

		if($data_suscripcion['dias_restantes_suscripcion'] < 0) {
			$html = '
			<div class="alert alert-warning alert-styled-right" style="margin-top: 25px;">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				Su suscripción a caducado el día '.$data_suscripcion['fecha_expira_suscripcion'].', por favor comuníquese con SOPORTE para renovar su suscripción!... 
			</div>
			';
			$resp['html'] = $html.$data_suscripcion['mensaje_expira'];
			$resp['suscripcion_activa'] = 'no';
			return $resp;
		}
		
		if($data_suscripcion['dias_restantes_suscripcion'] < 7) {
			if($data_suscripcion['dias_restantes_suscripcion'] > 4) {
				//mensaje verde
				$html = '
				<div class="alert alert-success alert-styled-right" style="margin-top: 25px;">
					<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
					Tu suscripción caducará el día: '.$data_suscripcion['fecha_expira_suscripcion'].', recuerda renovar tu suscripción a tiempo!... 
				</div>
				'.$data_suscripcion['mensaje_expira'];
				$resp['suscripcion_activa'] = 'si';
				
			} else {
				if($data_suscripcion['dias_restantes_suscripcion'] > 2) {
					//mensaje amarillo
					$html = '
					<div class="alert alert-warning alert-styled-right" style="margin-top: 25px;">
						<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
						Tu suscripción caducará el día: '.$data_suscripcion['fecha_expira_suscripcion'].', ya solo faltan '.$data_suscripcion['dias_restantes_suscripcion'].' días, no olvides renovar tu suscripción!... 
					</div>
					'.$data_suscripcion['mensaje_expira'];
					$resp['suscripcion_activa'] = 'si';
				} else {
					//mensaje rojo
					$html = '
					<div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
						<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
						Tu suscripción finaliza en '.$data_suscripcion['dias_restantes_suscripcion'].' días, renueva tu suscripción a tiempo caso contrario tu cuenta será bloqueada... 
					</div>
					'.$data_suscripcion['mensaje_expira'];
					$resp['suscripcion_activa'] = 'si';
				}
			}
		}

        $resp['html'] = $html;
        return $resp;
    }

	public function validate_subscription($idusuario) {
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Usuario';
			$resp['mensaje'] = 'No Existe el Usuario';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Contribuyente';
			$resp['mensaje'] = 'No Existe el Contribuyente!';
			return $resp;
		}

		//1.- verificamos si el contribuyente tiene alguna suscripción activa.
		$suscripcion = Suscripcion::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "fecha_fin DESC"));

		//NOTAS:
		//Para la descarga de documentos xml se debería verificar si el usuario tiene suscripciones pagas activas
		//Para la asignación de suscripciones ya no se debería permitir asignar meses gratuitos si esa persona estuvo en producción sin pagar por más de 20 días.

		if($usuario->id_contribuyente == 1) {
			$resp['respuesta'] = 'activo';
			$resp['modo'] = 'usuario_gratuito_prueba';
			$resp['dias_transcurridos'] = 1;
			$resp['dias_faltantes_para_expiracion'] = 360;
			return $resp;
		}
		
		if($usuario->id_rol == 5) {
			$resp['respuesta'] = 'activo';
			$resp['modo'] = 'usuario_gratuito_prueba';
			$resp['dias_transcurridos'] = 1;
			$resp['dias_faltantes_para_expiracion'] = 360;
			return $resp;
		}
		
		if(!$suscripcion) {
			//si no tiene una suscripción activa es probable que se trate de un usuario recién registrado o un usuario que pasó a producción sin pagar
			if($contribuyente->tipo_envio_sunat == 'prueba') {
				$dias_transcurridos = $this->dias_transcurridos($contribuyente->fecha_registro, date('Y-m-d H:i:s'));
				if($dias_transcurridos >= 60) {
					//Si el usuario se encuentra en prueba y sin pagar nada por más de 60 días entonces debemos mostrarle el mensaje para que se suscriba
					$resp['respuesta'] = 'expirado';
					$resp['modo'] = 'usuario_gratuito_prueba';
					$resp['dias_transcurridos'] = $dias_transcurridos;
					return $resp;
				}

				$resp['respuesta'] = 'activo';
				$resp['modo'] = 'usuario_gratuito_prueba';
				$resp['dias_transcurridos'] = $dias_transcurridos;
				$resp['dias_faltantes_para_expiracion'] = 60 - $dias_transcurridos;
				return $resp;

			} elseif ($contribuyente->tipo_envio_sunat == 'produccion') {
					//Si el usuario ha ingresado a producción sin pagar absolutamente entonces dejamos que siga emitiendo por solo un mes.
					//1.- Verificamos la fecha del primer documento electrónico emitido en producción.
					$primer_documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => 'produccion')));

					//También deberíamos verificar las notas de venta y cotizaciones

					$dias_transcurridos = 0;
					if(!$primer_documento) {
						$dias_transcurridos = $this->dias_transcurridos($contribuyente->fecha_registro, date('Y-m-d H:i:s'));

						//Si el usuario pasó a producción pero aún no ha pagado y tampoco ha emitido ningún documento entonces verificamos que no esté registrado sin pagar por más de 90 días
						if($dias_transcurridos >= 90) {
							$resp['respuesta'] = 'expirado';
							$resp['modo'] = 'usuario_gratuito_produccion';
							$resp['dias_transcurridos'] = $dias_transcurridos;
							return $resp;
						}

						$resp['respuesta'] = 'activo';
						$resp['modo'] = 'usuario_gratuito_produccion';
						$resp['dias_transcurridos'] = $dias_transcurridos;
						$resp['dias_faltantes_para_expiracion'] = 90 - $dias_transcurridos;
						return $resp;
					}

					
					$dias_transcurridos = $this->dias_transcurridos($primer_documento->fecha_registro, date('Y-m-d H:i:s'));
					//Si ya emitió su primer documento en producción y aún no ha pagado nada entonces solamente le dejaremos libre los 30 primeros días!
					if($dias_transcurridos >= 30) {
						$resp['respuesta'] = 'expirado';
						$resp['modo'] = 'usuario_gratuito_produccion';
						$resp['dias_transcurridos'] = $dias_transcurridos;
						return $resp;
					}

					$resp['respuesta'] = 'activo';
					$resp['modo'] = 'usuario_gratuito_produccion';
					$resp['dias_transcurridos'] = $dias_transcurridos;
					return $resp;
					
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error Contribuyente';
				$resp['modo'] = 'desconocido';
				$resp['mensaje'] = 'No se reconoce el estado del contribuyente!';
				return $resp;
			}
		}
		
		//Aquí ingresa si el usuario ya tiene suscripciones activas... Quizás sea una suscripción de prueba o paga!
		$fecha_fin_suscripcion = date("Y-m-d H:i:s", strtotime($suscripcion->fecha_fin."+ 1 days"));
		$dias_transcurridos = $this->dias_transcurridos($fecha_fin_suscripcion, date('Y-m-d H:i:s'));

		if($dias_transcurridos >= 7) {
			$resp['respuesta'] = 'expirado';
			$resp['modo'] = 'usuario_pago';
			$resp['dias_sin_suscripcion'] = $dias_transcurridos;
			return $resp;
		}
		
		$resp['respuesta'] = 'activo';
		$resp['modo'] = 'usuario_pago';
		$resp['dias_sin_suscripcion'] = ($dias_transcurridos <= 0)?0:$dias_transcurridos;

		if($dias_transcurridos <= 0) {
			$resp['dias_faltantes_para_expiracion'] = $this->dias_transcurridos(date('Y-m-d H:i:s'), $fecha_fin_suscripcion);
		} else {
			$resp['dias_faltantes_para_expiracion'] = 7 - $dias_transcurridos;
		}
		
		return $resp;
	}

	public function dias_transcurridos($fecha_i, $fecha_f) {
        $dias = (strtotime($fecha_f) - strtotime($fecha_i))/86400;
		$dias = floor($dias);		
		return $dias;
	}

	public function validar_expiracion_certificado($id_contribuyente) {
		//Posibles Estados: Expirado, Activo, 7_dias, 15_dias
		$id_contribuyente = intval($id_contribuyente) + 0;
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Usuario';
			$resp['mensaje'] = 'No Existe el Contribuyente!';
			echo json_encode($resp);
			exit();
		}

		if($id_contribuyente == 1) {
			$resp['respuesta'] = 'ok';
			$resp['color'] = 'verde';
			$resp['codigo'] = 'activo';
			$resp['dias_restantes'] = 997;
			return $resp;
		}

		if($contribuyente->tipo_envio_sunat != 'produccion') {
			//no cortamos el acceso
			$resp['respuesta'] = 'ok';
			$resp['color'] = 'verde';
			$resp['codigo'] = 'activo';
			$resp['dias_restantes'] = 998;
			return $resp;
		}

		$fecha_expira_certificado = $contribuyente->fecha_expira_cert;
		if(empty($fecha_expira_certificado)) {
			//no cortamos el acceso
			$resp['respuesta'] = 'ok';
			$resp['color'] = 'verde';
			$resp['codigo'] = 'activo';
			$resp['dias_restantes'] = 999;
			return $resp;
		}

		//verificando si es reseller:
		$usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
		$es_reseller = false;
		foreach($usuarios as $usuario) {
			if($usuario->id_rol == 5) {
				$es_reseller = true;
				break;
			}
		}

		$herramientas = new HerramientasController;
		$resp_diferencia = $herramientas->comparar_fechas($fecha_expira_certificado, date('Y-m-d'));
		//Si $resp_diferencia['diferencia_primera_segunda'] es menor a cero es que la fecha actual superó a la suscripción y debe cortarse el acceso
		//Si $resp_diferencia['diferencia_primera_segunda'] es igual o mayor a cero entonces aún está activo
		if($resp_diferencia['diferencia_primera_segunda'] < 0) {
			$resp['respuesta'] = 'ok';
			$resp['codigo'] = 'inactivo';
			$resp['dias_restantes'] = $resp_diferencia['diferencia_primera_segunda'].' => Actual: '.date('Y-m-d').', expira: '.$fecha_expira_certificado;
			return $resp;
		} else {
			if($resp_diferencia['diferencia_primera_segunda'] >= 15) {
				$resp['respuesta'] = 'ok';
				$resp['color'] = 'verde';
				$resp['codigo'] = 'activo';
				$resp['dias_restantes'] = $resp_diferencia['diferencia_primera_segunda'];
				return $resp;
			} else {
				if($resp_diferencia['diferencia_primera_segunda'] >= 7) {
					$resp['respuesta'] = 'ok';
					$resp['codigo'] = 'activo';
					$resp['color'] = 'amarillo';
					$resp['dias_restantes'] = $resp_diferencia['diferencia_primera_segunda'];
					return $resp;
				} else {
					$resp['respuesta'] = 'ok';
					$resp['codigo'] = 'activo';
					$resp['color'] = 'rojo';
					$resp['dias_restantes'] = $resp_diferencia['diferencia_primera_segunda'];
					return $resp;
				}
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['codigo'] = 'inactivo';
		return $resp;
	}

	public function get_html_certificado($data, $id_contribuyente) {
		$html = '';
		if($data['respuesta'] == 'error') {
			$resp['html'] = ' <div class="alert alert-primary alert-styled-right" style="margin-top: 25px;"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button> '.$data['mensaje'].'</div>';
			$resp['codigo'] = 'inactivo';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['html'] = ' <div class="alert alert-primary alert-styled-right" style="margin-top: 25px;"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button> No Existe el Contribuyente</div>';
			$resp['codigo'] = 'inactivo';
			return $resp;
		}

		$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));
		if(!$patrocinador) {
			$resp['html'] = ' <div class="alert alert-primary alert-styled-right" style="margin-top: 25px;"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button> No encontramos los datos de la empresa principal</div>';
			$resp['codigo'] = 'inactivo';
			return $resp;
		}

		if($patrocinador->id_contribuyente == 1 || $patrocinador->id_contribuyente == 923 || $patrocinador->id_contribuyente == 2) {
			$mensaje_contacto = 'Email: facturalaya.srl@gmail.com, Teléfonos: <a target="_blank" href="https://web.whatsapp.com/send?phone=51956295282&text=Deseo%20renovar%20mi%20activación%20y/o%20suscripci%C3%B3n,%20mi%20n%C3%BAmero%20de%20RUC%20es:%20'.$contribuyente->ruc.'%20y%20mi%20ID%20en%20el%20sistema%20es:%20'.$contribuyente->id_contribuyente.'.%20Necesito%20me%20indique%20c%C3%B3mo%20proceder!">956295282</a>, <a target="_blank" href="https://web.whatsapp.com/send?phone=51949255289&text=Deseo%20renovar%20mi%20activación%20y/o%20suscripci%C3%B3n,%20mi%20n%C3%BAmero%20de%20RUC%20es:%20'.$contribuyente->ruc.'%20y%20mi%20ID%20en%20el%20sistema%20es:%20'.$contribuyente->id_contribuyente.'.%20Necesito%20me%20indique%20c%C3%B3mo%20proceder!">949255289</a>, <a target="_blank" href="https://web.whatsapp.com/send?phone=51966732860&text=Deseo%20renovar%20mi%20activación%20y/o%20suscripci%C3%B3n,%20mi%20n%C3%BAmero%20de%20RUC%20es:%20'.$contribuyente->ruc.'%20y%20mi%20ID%20en%20el%20sistema%20es:%20'.$contribuyente->id_contribuyente.'.%20Necesito%20me%20indique%20c%C3%B3mo%20proceder!">966732860</a>, <a target="_blank" href="https://web.whatsapp.com/send?phone=51956295282&text=Deseo%20renovar%20mi%20activación%20y/o%20suscripci%C3%B3n,%20mi%20n%C3%BAmero%20de%20RUC%20es:%20'.$contribuyente->ruc.'%20y%20mi%20ID%20en%20el%20sistema%20es:%20'.$contribuyente->id_contribuyente.'.%20Necesito%20me%20indique%20c%C3%B3mo%20proceder!">también puedes hacer click aquí para enviar un mensaje via whatsapp.</a>';
		} else {
			$mensaje_contacto = 'Email: '.$patrocinador->email.', Teléfono: <a target="_blank" href="https://web.whatsapp.com/send?phone=51'.$patrocinador->telefono.'&text=Deseo%20renovar%20mi%20activación%20y/o%20suscripci%C3%B3n,%20mi%20n%C3%BAmero%20de%20RUC%20es:%20'.$contribuyente->ruc.'%20y%20mi%20ID%20en%20el%20sistema%20es:%20'.$contribuyente->id_contribuyente.'.%20Necesito%20me%20indique%20c%C3%B3mo%20proceder!">'.$patrocinador->telefono.'</a>, <a target="_blank" href="https://web.whatsapp.com/send?phone=51'.$patrocinador->telefono.'&text=Deseo%20renovar%20mi%20activación%20y/o%20suscripci%C3%B3n,%20mi%20n%C3%BAmero%20de%20RUC%20es:%20'.$contribuyente->ruc.'%20y%20mi%20ID%20en%20el%20sistema%20es:%20'.$contribuyente->id_contribuyente.'.%20Necesito%20me%20indique%20c%C3%B3mo%20proceder!">también puedes hacer click aquí para enviar un mensaje via whatsapp.</a>';
		}
		

		if($data['codigo'] == 'inactivo') {
			$resp['html'] = '
			<div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				Su Activación anual por servicios premium ha vencido!, comuníquese inmediatamente con soporte... A los siguientes números de celular para proceder con su renovación... <br /> '.$mensaje_contacto.'
			</div>
			';
			$resp['codigo'] = 'inactivo';
			return $resp;
		} else {
			if($data['color'] == 'verde') {
				$resp['html'] = '';
				$resp['codigo'] = 'activo';
				return $resp;
			} elseif ($data['color'] == 'amarillo') {
				$resp['html'] = '
				<div class="alert alert-info alert-styled-right" style="margin-top: 25px;">
					<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
					Tu Activación anual por servicios Premiun vencerá en '.$data['dias_restantes'].' días, debes renovar tu activación anual lo más pronto posible, caso contrario ya no podrás enviar documentos electrónicos... <br /> '.$mensaje_contacto.'
				</div>
				';
				$resp['codigo'] = 'activo';
				return $resp;
			} else {
				$resp['html'] = '
				<div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
					<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
					Tu activación anual por servicios premium vencerá en '.$data['dias_restantes'].' días, debes renovar tu activación anual lo más pronto posible, caso contrario ya no podrás enviar documentos electrónicos... <br /> '.$mensaje_contacto.'
				</div>
				';
				$resp['codigo'] = 'activo';
				return $resp;
			}
		}

        return $resp;
    }
}