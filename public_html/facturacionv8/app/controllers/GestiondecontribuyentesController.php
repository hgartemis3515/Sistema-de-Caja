<?php
class GestiondecontribuyentesController extends ControllerBase
{
	public function indexAction() {
        $this->setTitle('Contribuyentes');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
            ->addJs($this->baseUri . "public/js/gestiondecontribuyentes/gestiondecontribuyentes.js?i=".rand());

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        $this->view->usuario = $usuario;
        $this->view->id_contribuyente = $usuario->id_contribuyente;
    }

    function get_lista_videos_admin() {
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://facturalahoy.com/api/facturalaya/get_videotutoriales_admin',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '{"token": "'.$this->token_user.'"}',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);
    
        $response = curl_exec($curl);
        curl_close($curl);
    
        // Decodificar la respuesta JSON a un array asociativo de PHP
        $data = json_decode($response, true);
    
        // Verificar si la respuesta es válida y contiene el array de videos
        if (isset($data['respuesta']) && $data['respuesta'] === 'ok' && isset($data['videos'])) {
            return $data['videos'];
        }
    
        // Si no se cumplen las condiciones, retornar un array vacío o manejar el error apropiadamente
        return [];
    }

    public function videotutorialesAction() {
        $this->setTitle('Videotutoriales Super Admin');
        $this->view->setTemplateAfter('vacio');

        $this->view->lista_videos = $this->get_lista_videos_admin();
    }

    public function getDataSuscripcionAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo Sentimos No tienes Permisos para Efectur esta Operación!';
                echo json_encode($resp);
                exit();
            }

            $id_suscripcion = empty($datapost['id_suscripcion'])?0:intval($datapost['id_suscripcion']);
            $suscripcion = Suscripcion::findFirst(array("id_suscripcion = :id_suscripcion:", 'bind' => array('id_suscripcion' => $id_suscripcion)));
            if(!$suscripcion) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'La suscripción que intenta validar o confirmar no existe!';
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['suscripcion'] = $suscripcion;
            echo json_encode($resp);
            exit();
        }
    }

    public function convertirEnSocioEstrategicoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo Sentimos No tienes Permisos para Efectur esta Operación!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Existe el Contribuyente!';
                echo json_encode($resp);
                exit();
            }

            if($contribuyente->id_contribuyente == 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No se Puede Cambiar los datos del Administrador General!';
                echo json_encode($resp);
                exit();
            }

            $usuario_admin = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and id_rol = 3", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
            if(!$usuario_admin) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No encontramos un usuario administrador para el contribuyente seleccionado!';
                echo json_encode($resp);
                exit();
            }

            $usuario_admin->id_rol = 5;
            if(!$usuario_admin->save()) {
                $msg = '';
                foreach ($usuario_admin->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'El contribuyente fué asignado como socio estratégico!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function convertirEnJefeGrupoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo Sentimos No tienes Permisos para Efectur esta Operación!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Existe el Contribuyente!';
                echo json_encode($resp);
                exit();
            }

            if($contribuyente->id_contribuyente == 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No se Puede Cambiar los datos del Administrador General!';
                echo json_encode($resp);
                exit();
            }

            $contribuyente->tipo_empresa = 2; //Si es 1: Es una Empresa Normal, si es 2: Es una empresa tipo reseller, pero se le denomina jefe de grupo, porque todas las empresas pueden pertenecer al mismo dueño, esta opción se utiliza para que en el sistema aparezca una opción superior donde los clientes puedan cambiar entre empresas.
            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'El contribuyente fué asignado como socio estratégico!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function validarVerificarPagoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo Sentimos No tienes Permisos para Efectur esta Operación!';
                echo json_encode($resp);
                exit();
            }

            $id_suscripcion = empty($datapost['id_suscripcion'])?0:intval($datapost['id_suscripcion']);
            $suscripcion = Suscripcion::findFirst(array("id_suscripcion = :id_suscripcion:", 'bind' => array('id_suscripcion' => $id_suscripcion)));
            if(!$suscripcion) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'La suscripción que intenta validar o confirmar no existe!';
                echo json_encode($resp);
                exit();
            }

            $nota = empty($datapost['nota'])?'':$datapost['nota'];

            if($nota == '') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Ingresa una Nota!';
				$resp['mensaje'] = 'Debes Ingresar una Nota para validar la operación!';
				echo json_encode($resp);
				exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
				$resp['mensaje'] = 'Realmente deseas validar esta operación!';
				echo json_encode($resp);
				exit();
            }
            
            $suscripcion->pago_verificado = 'si';
            $suscripcion->nota_pago_verificado = $nota;
            if(!$suscripcion->save()) {
                $msg = '';
                foreach ($suscripcion->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'La operación ha sido validada!!';
            echo json_encode($resp);
            exit();
        }
    }


    public function get_num_rows_query($query, $fecha_inicio, $fecha_fin, $id_contribuyente, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if($id_contribuyente != 1) {
                $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            }
            if(!empty($termino_busqueda)) {
                $termino_busqueda = '%'.$termino_busqueda.'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $n++;
        }

        return $n;
    }
    
    public function getDataContribuyenteAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }  

            $id_contribuyente =!isset($datapost['id_contribuyente'])?0:(intval($datapost['id_contribuyente']) + 0);
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }
            
            $resp_valid = $this->verificar_permisos($usuario->idusuario, $contribuyente->id_contribuyente);
            if($resp_valid['respuesta'] == 'error') {
                echo json_encode($resp_valid);
                exit();
            }
            
            $resp_contribuyente = (array)$contribuyente;
            $resp_contribuyente['clave_sol'] = '';
            $resp_contribuyente['pass_certificado'] = '';
            $resp_contribuyente['sunat_p_sol_principal'] = '';
            $resp_contribuyente['sunat_client_secret'] = '';

            $resp['respuesta'] = 'ok';
            $resp['contribuyente'] = $resp_contribuyente;
            echo json_encode($resp);
            exit();
        }
    }

    public function verificar_permisos($idusuario, $id_contribuyente) {
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El usuario no existe';
            return $resp;
        }  
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
            return $resp;
        }

        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Usted no tiene los permisos suficientes para agregar un nuevo contribuyente!.';
            return $resp;
        }

        if($usuario->id_rol == 3) {
            if($usuario->id_contribuyente != $contribuyente->id_contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Solo un administrador puede modificar los datos de tu empresa!.';
                return $resp;
            }
        }

        if($usuario->id_rol == 5) {
            if($usuario->id_contribuyente != $contribuyente->id_contribuyente) {
                if($usuario->id_contribuyente != $contribuyente->id_patrocinador) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Solo un administrador puede modificar los datos de tu empresa!.';
                    return $resp;
                }
            }
        }
        
        $resp['respuesta'] = 'ok';
        return $resp;
    }
    
    public function guardarAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            $resp = $this->guardar($usuario, $datapost);
            echo json_encode($resp);
            exit();
        }
    }

    public function guardar($usuario, $datapost) {
        $herramientas = new HerramientasController;
            
        $ruc = $herramientas->extraer_numeros($datapost['ruc']);
        $nombre_comercial = !isset($datapost['nombre_comercial'])?'':$datapost['nombre_comercial'];
        $razon_social = !isset($datapost['razon_social'])?'':$datapost['razon_social'];
        $telefono = !isset($datapost['telefono'])?'':$datapost['telefono'];
        $idubigeo = !isset($datapost['ubigeo'])?'':$datapost['ubigeo'];
        $urbanizacion = !isset($datapost['urbanizacion'])?'':$datapost['urbanizacion'];
        $direccionfiscal = !isset($datapost['direccionfiscal'])?'':$datapost['direccionfiscal'];
        $img_logo = !isset($datapost['ruta_logo'])?'':$datapost['ruta_logo'];

        $email_login = !isset($datapost['email_login'])?'':$datapost['email_login'];
        $password_login = !isset($datapost['password_login'])?'':$datapost['password_login'];

        $usuario_sol = !isset($datapost['usuario_sol'])?'':$datapost['usuario_sol'];
        $password_sol = !isset($datapost['password_sol'])?'':$datapost['password_sol'];
        $certificado_file = '';
        $certificado_pass = !isset($datapost['password_certificado'])?'':$datapost['password_certificado'];
        $tipo_proceso = !isset($datapost['opcion_tipo_proceso'])?'':$datapost['opcion_tipo_proceso'];
        $id_contribuyente = !isset($datapost['id_contribuyente'])?'':$datapost['id_contribuyente'];

        $modalidad_envio_sunat = $datapost['modalidad_envio_sunat'];
        $modalidades_aceptadas = array('inmediato', 'solo_firma', 'no_enviar');
        if(!in_array($modalidad_envio_sunat, $modalidades_aceptadas)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error Modo Envío';
            $resp['mensaje'] = 'Debes seleccionar el modo de envío a sunat';
            return $resp;
        }

        $resp_busqueda_ruc = $herramientas->get_data_api_busquedas('ruc', $ruc);
        if($resp_busqueda_ruc['respuesta'] == 'error') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El número de R.U.C. no es válido!';
            return $resp;
        }

        //$razon_social = !isset($resp_busqueda_ruc['data']->razon_social)?'':$resp_busqueda_ruc['data']->razon_social;
        if($razon_social == '') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos la Razón Social!';
            return $resp;
        }
        
        if(empty($nombre_comercial)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes Ingresar un Nombre Comercial!';
            return $resp;
        }

        $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $idubigeo)));
        if(!$sql_ubigeo){
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Lo siento, el  código de Ubicación Geográfica no existe. Por favor selecciona un ubigeo Válido';
            return $resp;
        }
        
        if(empty($urbanizacion)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el nombre de la urbanización!';
            return $resp;
        }

        if(empty($direccionfiscal)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes Ingresar la Dirección de la Empresa!';
            return $resp;
        }

        $this->db->begin();

        $idpatrocinador = 1;
        
        $contribuyente_patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $idpatrocinador)));
        if(!$contribuyente_patrocinador) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El patrocinador seleccionado no existe!, por favor verifica!';
            return $resp;
        }
        
        $id_contribuyente = !isset($datapost['id_contribuyente'])?0:intval($datapost['id_contribuyente']) + 0;
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => 1)));

        $nuevo_contribuyente = false;
        if(!$contribuyente) {
            
            //aquí debemos verificar si el usuario tiene permisos de patrocinador(5) o super_admin(1), super_soporte(2)
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 5) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes para agregar un nuevo contribuyente!.';
                return $resp;
            }

            //$idpatrocinador = 1;

            $verify_contribuyente = Contribuyente::findFirst(array("ruc = :ruc:", 'bind' => array('ruc' => $ruc)));
            if($verify_contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Contribuyente con RUC N°: '.$ruc.', ya fué registrado!';
                return $resp;
            }

            if(empty($email_login)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Ingresar un Email, pues utilizaremos dicho email para crear el usuario administrador!';
                return $resp;
            }

            //Solamente si se está agregando un contribuyente se registrará un usuario administrador!
            $contribuyente = new Contribuyente();
            $contribuyente->ruc = $ruc;
            $contribuyente->fecha_registro = date('Y-m-d H:i:s');
            $contribuyente->ruta_xml_prueba = 'prueba'; //nombre del directorio donde se guardarán los xmls de pruebas
            $contribuyente->ruta_xml_produccion = 'produccion'; //nombre del directorio donde se guardarán los xmls de producción
            $contribuyente->email = $email_login;
            $contribuyente->dominio = $patrocinador->dominio;
            $contribuyente->https = 'si';
            $contribuyente->logo_461 = '/facturacionv8/public/img/logo_facturalaya_461.png';
            $contribuyente->logo_291 = '/facturacionv8/img/logo_facturalaya_56_only.png';
            $contribuyente->logo_56 = '/facturacionv8/img/logo_facturalaya_291_verificado.png';

            $contribuyente->correlativo_rd_boletas = 1;
            $contribuyente->correlativo_comunicacion_bajas = 1;
            $contribuyente->sunat_idregimen = 1;
            $contribuyente->restriccion_stock = 'no';
            $contribuyente->multi_almacen = 'si';
            $contribuyente->num_decimales = 2;
            $contribuyente->prodduplicados_detalle = 'no';
            $contribuyente->precio_venta_minimo = 'no';

            $nuevo_contribuyente = true;
        } else {
            $resp_valid = $this->verificar_permisos($usuario->idusuario, $contribuyente->id_contribuyente);
            if($resp_valid['respuesta'] == 'error') {
                return $resp_valid;
            }


            if($usuario->id_rol == 1) {
                if($contribuyente->ruc != $ruc) {
                    if($contribuyente->tipo_envio_sunat == 'prueba') {
                        if(!$this->verificar_si_tiene_docs_en_produccion($id_contribuyente)) {
                            $contribuyente->ruc = $ruc;
                            $num_rucs = $this->get_cantidad_contribuyentes_mismo_ruc($ruc);
                            if($num_rucs <= 0) {
                                $contribuyente->correlativo_rd_boletas = 1;
                                $contribuyente->correlativo_comunicacion_bajas = 1;
                            } else {
                                $contribuyente->correlativo_rd_boletas = $num_rucs*10000;
                                $contribuyente->correlativo_comunicacion_bajas = $num_rucs*10000;
                            }
                        }
                    }
                }
            }
        }

        if($tipo_proceso != 'on') {
            $contribuyente->tipo_envio_sunat = 'produccion';
        } else {
            $contribuyente->tipo_envio_sunat = 'prueba';
        }
        
        $contribuyente->nombre_comercial = $nombre_comercial;
        $contribuyente->razon_social = $razon_social;
        $contribuyente->telefono = $telefono;
        $contribuyente->codigo_ubigeo = $idubigeo;
        $contribuyente->urbanizacion = $urbanizacion;
        $contribuyente->direccion_fiscal = $direccionfiscal;
        $contribuyente->img_logo = $img_logo;
        $contribuyente->modalidad_envio_sunat = $modalidad_envio_sunat;
        $contribuyente->id_patrocinador = $idpatrocinador;

        $contribuyente->regimen_retencion = 0.03;

        if(!$contribuyente->save()) {
            $msg = '';
            foreach ($contribuyente->getMessages() as $message) {
                $msg = $msg.$message."</br>\n";
            }
            $this->db->rollback();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error 00';
            $resp['mensaje'] = $msg;
            return $resp;
        }

        if($tipo_proceso != 'on') { //si es diferente a ON, entonces ingresó a producción.

            if($contribuyente->tipo_certificado != 'pse' && $contribuyente->tipo_certificado != 'pse_facturalaya') {
                $usuario_sol = empty($usuario_sol)?$contribuyente->usuario_sol:$usuario_sol;
                $password_sol = empty($password_sol)?$contribuyente->clave_sol:$password_sol;
                $certificado_pass = empty($certificado_pass)?$contribuyente->pass_certificado:$certificado_pass;
            } else {
                $usuario_sol = $contribuyente->usuario_sol;
                $password_sol = $contribuyente->clave_sol;
                $certificado_pass = $contribuyente->pass_certificado;
            }

            if(empty($usuario_sol)) {
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si ya vas a pasar a producción, entonces debes ingresar tu Usuario SOL - SUNAT';
                return $resp;
            }

            if(empty($contribuyente->clave_sol) && empty($password_sol)) {
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si ya vas a pasar a producción, entonces debes ingresar tu Clave Sol - SUNAT';
                return $resp;
            }

            if(empty($contribuyente->pass_certificado) && empty($certificado_pass)) {
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si ya vas a pasar a producción, entonces debes ingresar el password de tu certificado electrónico';
                return $resp;
            }

            if($contribuyente->tipo_certificado != 'pse' && $contribuyente->tipo_certificado != 'pse_facturalaya') {
                $resp_valid_sol = $this->validar_credenciales_sol($ruc, $usuario_sol, $password_sol);
                if($resp_valid_sol->respuesta == 'error') {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Su Clave SOl y Usuario SOL no son válidos!, por favor ingrese sus datos correctos y válidos!';
                    return $resp;
                }
            }

            if(!isset($_FILES['archivo']) && empty($contribuyente->ruta_certificado)) {
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Por favor debe subir su certificado electrónico para poder firmar sus documentos enviados a SUNAT';
                return $resp;
            }

            if(isset($_FILES['archivo'])) {
                $ruta_temporal_certificado = $_FILES['archivo']['tmp_name'];
                $certPassword = !isset($datapost['password_certificado'])?'':$datapost['password_certificado'];
                $data = file_get_contents($ruta_temporal_certificado);
                if(!openssl_pkcs12_read($data, $certs, $certPassword)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Usted ha ingresado un password errado para su certificado electrónico!';
                    return $resp;
                }

                $ruta_save_certificado = $this->ruta_base_files."/".$ruc."/firma/";
                if (!file_exists($ruta_save_certificado)) {
                    mkdir($ruta_save_certificado, 0777, true);
                }

                $ruta_save_certificado = $ruta_save_certificado.$_FILES['archivo']['name'];
                if(!move_uploaded_file($ruta_temporal_certificado, $ruta_save_certificado)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No hemos logrado guardar su certificado correctamente, por favor intenta nuevamente!';
                    return $resp;
                }

                $ruta_xmls_produccion = $this->ruta_base_files."/".$ruc.'/xmls_produccion/';
                if (!file_exists($ruta_xmls_produccion)) {
                    mkdir($ruta_xmls_produccion, 0777, true);
                }
            }

            if($contribuyente->tipo_certificado != 'pse' && $contribuyente->tipo_certificado != 'pse_facturalaya') {
                $contribuyente->usuario_sol = empty($usuario_sol)?$contribuyente->usuario_sol:$usuario_sol;
                $contribuyente->clave_sol = empty($password_sol)?$contribuyente->clave_sol:$password_sol;
                $contribuyente->pass_certificado = empty($certificado_pass)?$contribuyente->pass_certificado:$certificado_pass;
            }

            $contribuyente->ruta_certificado = !isset($_FILES['archivo']['tmp_name'])?$contribuyente->ruta_certificado:"firma/".$_FILES['archivo']['name'];
            $contribuyente->tipo_envio_sunat = 'produccion';
        }

        if(!$contribuyente->save()) {
            $msg = '';
            foreach ($contribuyente->getMessages() as $message) {
                $msg = $msg.$message."</br>\n";
            }
            $this->db->rollback();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error 28';
            $resp['mensaje'] = $msg;
            return $resp;
        }

        if($nuevo_contribuyente) {
            $verify_usuario = Usuario::findFirst(array("email = :email:", 'bind' => array('email' => $email_login)));
            if($verify_usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error 29';
                $resp['mensaje'] = 'El Email ingresado en sus datos de acceso, ya está ocupado por otro usuario, por favor ingresa un email diferente!!';
                return $resp;
            }

            //solamente se crea un nuevo usuario cuando se registra un nuevo contribuyente
            $new_usuario = new Usuario();
            $new_usuario->email = $email_login;
            $new_usuario->password = $password_login;
            $new_usuario->telefono = $telefono;
            $new_usuario->celular = $telefono;
            $new_usuario->id_rol = 3;
            $new_usuario->nombre = 'Usuario';
            $new_usuario->apellido = "Administrador";
            $new_usuario->id_contribuyente = $contribuyente->id_contribuyente;
            $new_usuario->codigo = $contribuyente->id_contribuyente.'ADM-'.$herramientas->gettoken(6);
            $new_usuario->fecha_registro = date('Y-m-d H:i:s');
            $new_usuario->estado = 'activo';

            if(!$new_usuario->save()) {
                $msg = '';
                foreach ($new_usuario->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error 30';
                $resp['mensaje'] = $msg;
                return $resp;
            }
        }

        $this->db->commit();
        $resp['respuesta'] = 'ok';
        $resp['titulo'] = 'Genial!';
        $resp['id_contribuyente'] = $contribuyente->id_contribuyente;
        $resp['mensaje'] = 'Los datos fueron guardados correctamente!';
        return $resp;
    }

    public function verificar_si_tiene_docs_en_produccion($id_contribuyente) {
        $documento_produccion = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = 'produccion'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$documento_produccion) {
            return false;
        }
        return true;
    }

    public function get_cantidad_contribuyentes_mismo_ruc($ruc) {
        $query = "SELECT count(*) total FROM contribuyente WHERE ruc = :ruc";
        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':ruc', $ruc, PDO::PARAM_STR);

        $sentencia->execute();
        $fila = $sentencia->fetch();
        return intval($fila['total']) + 0;
    }

    public function getPatrocinadoresAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 5) {
                $query = "select * from contribuyente where id_contribuyente = ".$usuario->id_contribuyente;
            } else if($usuario->id_rol == 1 || $usuario->id_rol == 2) {
                $query = "select * from contribuyente where id_contribuyente in (select distinct id_contribuyente from usuario where id_rol in (1, 2, 5))";
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permisos para acceder a esta área.';
                echo json_encode($resp);
                exit();
            }
            
            $sentencia = $this->db->prepare($query);
            $sentencia->execute();

            $lista = array();
            while ($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $lista[] = array("id_contribuyente" => $fila->id_contribuyente, "value" => $fila->razon_social.' - '.$fila->ruc);
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function validar_credenciales_sol($ruc, $usuario_sol, $pass_sol) {
        $data['ruc'] = $ruc;
        $data['usuario_sol'] = $usuario_sol;
        $data['pass_sol'] = $pass_sol;
        $data['ruc_proveedor'] = $this->ruc_proveedor;
        $data['token_cliente'] = $this->token_user;
        $ruta = 'https://facturalahoy.com/api/apiempresa/validar_usuario';
        $token = '';

        $data_json = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Token token="'.$token.'"',
			'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
        curl_close($ch);
        
		return json_decode($respuesta);
    }

    public function getDataApiBusquedasAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $herramientas = new HerramientasController;
            $num_doc = $herramientas->extraer_numeros($datapost['num_doc']);
            $resp = $herramientas->get_data_api_busquedas('ruc', $num_doc);
            echo json_encode($resp);
            exit();
		}
    }
    

    //ACCIÓN: Lista_Contribuyentes
    public function configuracionAction($id_contribuyente = 0) {
        $this->setTitle('Gestión de Contribuyentes');
        $this->view->setTemplateAfter('template_new');
        
        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/libraries/jquery_ui/core.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/subir_imagen.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
            ->addJs($this->baseUri . "public/js/gestiondecontribuyentes/configuracion.js?i=v4");

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
            echo json_encode($resp);
            exit();
        }
        
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 5) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Usted no tiene los permisos suficientes para agregar un nuevo contribuyente!.';
            echo json_encode($resp);
            exit();
        }

        if(intval($id_contribuyente) > 0) {
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No Existe el Patrocinador!.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 5) {
                if($usuario->id_contribuyente != $contribuyente->id_patrocinador) {
                    //si el id_contribuyente del usuario no es igual al id_patrocinador, significa que la empresa no es patrocinadora de la empresa actual.
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Usted no tiene los permisos suficientes para editar este contribuyente!.';
                    echo json_encode($resp);
                    exit();
                }
            }
        }
        
        $this->view->usuario = $usuario;
        $this->view->id_contribuyente = $id_contribuyente;
        $this->view->modalidad_envio_sunat = 'inmediato';
    }

    public function getListaContribuyentesAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes para agregar un nuevo contribuyente!.';
                echo json_encode($resp);
                exit();
            }

            $lista = array();
            if($usuario->id_rol == 1 || $usuario->id_rol == 2) {
                $lista_contribuyentes = Contribuyente::find(array('columns' => 'id_contribuyente', "order" => "id_contribuyente DESC"));
            }
            
            //Inicio de configuración Server Side.
            $total_registros = count($lista_contribuyentes);

            $columnas = array( 
                //indice de la columna en datatable => nombre de la columna
                0 => 'cont.id_contribuyente',
                1 => 'cont.razon_social',
                2 => 'cont.ruc',
                3 => 'pat.razon_social',
                4 => 'pat.ruc'
            );

            $query = "SELECT cont.id_contribuyente, cont.fecha_expiracion, cont.ruc, cont.tipo_empresa, cont.tipo_empresa_sunat, cont.razon_social, cont.fecha_expira_cert, cont.tipo_certificado, cont.tipo_envio_sunat, cont.fecha_registro, cont.id_patrocinador, pat.ruc ruc_patrocinador, pat.razon_social razon_social_patrocinador FROM contribuyente cont INNER JOIN contribuyente pat on cont.id_patrocinador = pat.id_contribuyente Where ";

            if($usuario->id_rol == 1 || $usuario->id_rol == 2) {
                $query = $query." 1 = 1 ";
            }

            if(!empty($datapost['search']['value'])) {
                if($datapost['search']['value'] == 'produccion') {
                    $query = $query." AND (cont.tipo_envio_sunat like :termino) ";
                } else if($datapost['search']['value'] == 'pse_anterior') {
                    $datapost['search']['value'] = '';
                    $query = $query." AND (cont.tipo_certificado = 'pse') ";
                } else if($datapost['search']['value'] == 'produccion, 20604209987') {
                    $datapost['search']['value'] = '20604209987';
                    $query = $query." AND (cont.tipo_envio_sunat = 'produccion' and pat.ruc like :termino) ";
                } else {
                    $query = $query." AND (cont.id_contribuyente like :termino or cont.ruc like :termino or cont.razon_social like :termino or pat.razon_social like :termino or pat.ruc like :termino or cont.tipo_envio_sunat like :termino or cont.tipo_certificado like :termino) ";
                }
            }

            $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $datapost['search']['value']);

            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

            try {
                $sentencia = $this->db->prepare($query);
                if(!empty($datapost['search']['value'])) {
                    $termino_busqueda = '%'.$datapost['search']['value'].'%';
                    $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
                }
                $sentencia->execute();
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
            
            $herramientas = new HerramientasController;
            $array_lista = array();
            $n = 0;
            while($item = $sentencia->fetch()) {
                $item = (object)$item;

                if($item->tipo_envio_sunat == 'produccion') {
                    $estado_envio = '<span class="label label-primary">Producción</span>';
                } else {
                    $estado_envio = '<span class="label label-success">Prueba</span>';
                }

                $tipo_certificado = '';
                if($item->tipo_certificado == 'pse') {
                    $tipo_certificado = '<span class="label label-danger">PSE</span>';
                } else if($item->tipo_certificado == 'pse_facturalaya') {
                    $tipo_certificado = '<span style="margin-left: 5px;" class="label label-danger">PSE-FACTURAlAYA</span>';
                }

                $tipo_empresa_html = '';
                if($item->tipo_empresa == 2) {
                    $tipo_empresa_html = '<span style="margin-left: 5px;" class="label label-info">JEFE GRUPO</span>';
                }

                $tipo_empresa_sunat_html = '';
                if($item->tipo_empresa_sunat == 'prico') {
                    $tipo_empresa_sunat_html = '<span style="margin-left: 5px;" class="label label-primary">PRICO</span>';
                }

                $resp_totales = $this->get_total_documentos($item->id_contribuyente, $item->tipo_envio_sunat);

                $menu_admin = '';
                $link_lista_documentos = '';
                if($usuario->id_rol == 1 || $usuario->id_rol == 2) {
                    if($item->tipo_envio_sunat == 'produccion') {
                        $link_lista_documentos = '<span class="label_razonsocial text-success"><i class="fa fa-caret-right position-left color-indigo"></i>
                        <a target="_blank" href="/facturacionv8/gestiondedocumentos?id_contribuyente='.$item->id_contribuyente.'">Ver Lista Documentos</a></span>';
                    }
                }
                
                $user_admin = Usuario::findFirst(array("id_rol in (1, 2, 3, 5) and id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $item->id_contribuyente)));
                
                $datos_admin = '';
                if(!$user_admin) {
                    $datos_admin = '
                    <span class="label_razonsocial"> - </span>
                    <span class="label_razonsocial"> - </span>
                    ';
                } else {
                    $cadena_encriptada = $herramientas->encriptar('FACTURALAYAAAAA||'.$user_admin->idusuario);
                    $datos_admin = '
                    <span class="label_razonsocial"><i class="fa fa-caret-right position-left color-indigo"></i>'.$user_admin->email.'</span>
                    <span class="label_razonsocial"><i class="fa fa-caret-right position-left color-indigo"></i>'.$user_admin->password.'</span>
                    <span class="label_razonsocial text-success"><i class="fa fa-caret-right position-left color-indigo"></i>
                        <a target="_blank" href="/facturacionv8/login/inicio_sesion_remoto?user='.$cadena_encriptada.'">Click para Ingresar</a></span>
                    '.$link_lista_documentos;
                }

                $fecha_expira_suscripcion = '';
                $fecha_expira_certificado = '';
                $fecha_expira = empty($item->fecha_expiracion)?'':date("d-m-Y", strtotime($item->fecha_expiracion));
                $fecha_expira_suscripcion = '
                <small onclick="editar_fecha_expira('.$item->id_contribuyente.', '."'".$fecha_expira."'".')" class="label_razonsocial"><i class="fa fa-caret-right position-left color-indigo"></i> <span style="cursor: pointer; text-decoration-color: #EA215A; text-decoration-thickness: .125em; text-underline-offset: 1.5px; border-bottom: #2196f3 0.130em dashed;">Fin Suscripción: '.$fecha_expira.'</span></small>';
                
                if(!empty($item->fecha_expira_cert)) {
                    $fecha_expira_certificado = '<small onclick="cambiar_tipo_certificado('.$item->id_contribuyente.', '."'".date("d/m/Y", strtotime($item->fecha_expira_cert))."'".')" class="label_razonsocial"><i class="fa fa-caret-right position-left color-indigo"></i> <span style="cursor: pointer; text-decoration-color: #EA215A; text-decoration-thickness: .125em; text-underline-offset: 1.5px; border-bottom: #2196f3 0.130em dashed;">Expira Cert. '.date("d/m/Y", strtotime($item->fecha_expira_cert)).'</span></small>';
                } else {
                    $fecha_expira_certificado = '<small onclick="cambiar_tipo_certificado('.$item->id_contribuyente.')" class="label_razonsocial"><i class="fa fa-caret-right position-left color-indigo"></i> <span style="cursor: pointer; text-decoration-color: #EA215A; text-decoration-thickness: .125em; text-underline-offset: 1.5px; border-bottom: #2196f3 0.130em dashed;">Expira Cert. - </span></small>';
                }

                $primer_doc_electronico = DocElectronico::findFirst(array("tipo_envio_sunat = :tipo_envio_sunat: and id_contribuyente = :id_contribuyente:", 'bind' => array('tipo_envio_sunat' => 'produccion', 'id_contribuyente' => $item->id_contribuyente), "order" => "fecha_registro ASC"));

                $fecha_primer_documento = '<small class="text-sucess"><i class="fa fa-caret-right position-left color-indigo"></i> Doc. En Prueba</small>';
                if($primer_doc_electronico) {
                    $fecha_primer_documento = '<small class="text-sucess"><i class="fa fa-caret-right position-left color-indigo"></i>Fecha 1° Doc: '.date("d-m-Y", strtotime($primer_doc_electronico->fecha_registro)).'</small>';
                }

                $ultimo_doc_electronico = DocElectronico::findFirst(array("tipo_envio_sunat = :tipo_envio_sunat: and id_contribuyente = :id_contribuyente:", 'bind' => array('tipo_envio_sunat' => 'produccion', 'id_contribuyente' => $item->id_contribuyente), "order" => "fecha_registro DESC"));

                $fecha_ultimo_documento = '<br /><small class="text-sucess"><i class="fa fa-caret-right position-left color-indigo"></i> Sin Doc. Prod.</small>';
                if($ultimo_doc_electronico) {
                    $fecha_ultimo_documento = '<br /><small class="text-sucess"><i class="fa fa-caret-right position-left color-indigo"></i>Fecha Ult. Doc: '.date("d-m-Y", strtotime($ultimo_doc_electronico->fecha_registro)).'</small>';
                }

                $menu_opciones = $this->get_menu_contribuyente($item->id_contribuyente, $usuario->id_rol);

                $n++;
                $array_lista[] = array(
                    $item->id_contribuyente, 
                    $this->recortar_texto($item->razon_social, 50).'<br />'.$item->ruc.'<br />'.$estado_envio.$tipo_certificado.$tipo_empresa_sunat_html.$tipo_empresa_html.$resp_totales['html_totales'],
                    '<small class="text-sucess"><i class="fa fa-caret-right position-left color-indigo"></i> Registro: '.date("d-m-Y / H:i A", strtotime($item->fecha_registro)).'</small>'.$fecha_expira_suscripcion.$fecha_expira_certificado.$fecha_primer_documento.$fecha_ultimo_documento,
                    $datos_admin,
                    $menu_opciones
                );
            }

            $json_data = array(
                "draw"            => intval($datapost['draw']),
                "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
                "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
                "data"            => $array_lista,   // total data array
                "numero_n"        => $n
            );

            echo json_encode($json_data);
            exit();
        }
    }

    public function recortar_texto($texto, $limite=100){
        $texto = str_replace("'", " ", $texto);
        $texto = str_replace('"', " ", $texto);
        $texto = trim($texto);
        $texto = strip_tags($texto);
        $tamano = strlen($texto);
        $resultado = '';
        if($tamano <= $limite){
            return $texto;
        }else{
            $texto = substr($texto, 0, $limite);
            $palabras = explode(' ', $texto);
            $resultado = implode(' ', $palabras);
            $resultado .= '...';
        }
        return $resultado;
    }

    public function get_num_rows_filtered_doc_sunat($query, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
            if(!empty($termino_busqueda)) {
                $termino_busqueda = '%'.$termino_busqueda.'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $n++;
        }

        return $n;
    }

    public function getPlanesBaseAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $id_contribuyente = isset($datapost['id_contribuyente'])?intval($datapost['id_contribuyente']):0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }

            $lista_planes = array();
            $n = 0;
            $html_checkbox = '';
            $lista_planes_base = Planbase::find();
            $lista_colores_checkboxs = array('control-danger', 'control-success', 'control-warning', 'control-info', 'control-custom');
            foreach($lista_planes_base as $planbase) {
                $resellerxpbase = Resellerxpbase::findFirst(array("id_contribuyente = :id_contribuyente: and idplanbase = :idplanbase:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idplanbase' => $planbase->idplanbase)));
                if(!$resellerxpbase) {
                    $estado = 'inactivo';
                } else {
                    $estado = $resellerxpbase->estado;
                }
                $planbase_2 = (array)$planbase;
                $planbase_2['estado'] = $estado;
                $lista_planes[] = $planbase_2;
                if($estado == 'activo') {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                shuffle($lista_colores_checkboxs);
                $html_checkbox = $html_checkbox.'
                <div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox_'.$n.'" data-idplanbase="'.$planbase->idplanbase.'" data-idcontribuyente="'.$id_contribuyente.'" class="item_plan_base '.$lista_colores_checkboxs[0].'" '.$checked.'>
						'.$planbase->nombre.'
					</label>
                </div>
                ';
                $n++;
            }

            $resp['respuesta'] = 'ok';
            $resp['lista_planes'] = $lista_planes;
            $resp['razon_social'] = $contribuyente->razon_social.' - '.$contribuyente->ruc;
            $resp['ruc'] = $contribuyente->ruc;
            $resp['html'] = $html_checkbox;
            echo json_encode($resp);
            exit();
        }
    }

    public function asignarPlanBaseAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $id_contribuyente = isset($datapost['id_contribuyente'])?intval($datapost['id_contribuyente']):0;
            $idplanbase = isset($datapost['idplanbase'])?intval($datapost['idplanbase']):0;
            $estado = isset($datapost['estado'])?$datapost['estado']:"false";
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }

            $planbase = Planbase::findFirst(array("idplanbase = :idplanbase:", 'bind' => array('idplanbase' => $idplanbase)));
            if(!$planbase) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El plan base seleccionado no existe';
                echo json_encode($resp);
                exit();
            }

            $resellerxpbase = Resellerxpbase::findFirst(array("id_contribuyente = :id_contribuyente: and idplanbase = :idplanbase:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idplanbase' => $idplanbase)));
            if(!$resellerxpbase) {
                $resellerxpbase = new Resellerxpbase();
                $resellerxpbase->fecha_registro = date('Y-m-d H:i:s');
                $resellerxpbase->id_contribuyente = $id_contribuyente;
                $resellerxpbase->idplanbase = $idplanbase;
            }

            if($estado == 'true') {
                $resellerxpbase->estado = 'activo';
            } else {
                $resellerxpbase->estado = 'inactivo';
            }

            if(!$resellerxpbase->save()) {
                $msg = '';
                foreach ($resellerxpbase->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
    
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            echo json_encode($resp);
            exit();
        }
    }

    public function asignarPSAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = isset($datapost['id_contribuyente'])?intval($datapost['id_contribuyente']):0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
				$resp['mensaje'] = 'Realmente deseas validar esta operación!';
				echo json_encode($resp);
				exit();
            }

            $contribuyente->tipo_certificado = 'pse';
            $contribuyente->usuario_sol = 'CERT2019';
            $contribuyente->clave_sol = 'cERT062019';
            $contribuyente->pass_certificado = 'D7JBbNGCVzg8u3LS';
            $contribuyente->ruta_certificado = '/home/wilson/.pse/20505675593.pfx';
            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'La Operación se Realizó Correctamente!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function quitarPseFacturalayaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = isset($datapost['id_contribuyente'])?intval($datapost['id_contribuyente']):0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
				$resp['mensaje'] = 'Realmente deseas validar esta operación!';
				echo json_encode($resp);
				exit();
            }

            $contribuyente->tipo_certificado = 'propio';
            $contribuyente->usuario_sol = '';
            $contribuyente->clave_sol = '';
            $contribuyente->pass_certificado = '';
            $contribuyente->ruta_certificado = '';
            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'La Operación se Realizó Correctamente!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function asignarPseFacturalayaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = isset($datapost['id_contribuyente'])?intval($datapost['id_contribuyente']):0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
				$resp['mensaje'] = 'Realmente deseas validar esta operación!';
				echo json_encode($resp);
				exit();
            }

            $contribuyente->tipo_certificado = 'pse_facturalaya';
            $contribuyente->usuario_sol = '-';
            $contribuyente->clave_sol = '-';
            $contribuyente->pass_certificado = '-';
            $contribuyente->ruta_certificado = '/home/wilson/.pse/-.pfx';
            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'La Operación se Realizó Correctamente!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function cambiarTipoEmpresaSunatAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = isset($datapost['id_contribuyente'])?intval($datapost['id_contribuyente']):0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }

            $tipo_empresa_sunat = $datapost['tipo_empresa_sunat'];
            if($tipo_empresa_sunat != 'prico' && $tipo_empresa_sunat != 'normal') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo Empresa No Existe!';
                echo json_encode($resp);
                exit();
            }
            
            $contribuyente->tipo_empresa_sunat = ($tipo_empresa_sunat == 'prico')?'prico':'normal';
            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'Se ha realizado el cambio correctamente!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function get_menu_contribuyente($id_contribuyente, $id_rol_user) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        if($contribuyente->tipo_empresa_sunat == 'prico') {
            $nuevo_tipo_empresa = "'normal'";
        } else {
            $nuevo_tipo_empresa = "'prico'";
        }

        if($id_rol_user == 1 || $id_rol_user == 2) {
            $menu = '';

            if(empty($contribuyente->tipo_certificado) || $contribuyente->tipo_certificado == 'propio') {
                $menu = $menu.'<li><a href="javascript:void(0)" onclick="asignar_cert_pse_facturalaya('.$id_contribuyente.')"><img src="/facturacionv8/public/img/favicons/apple-icon-57x57.png"> Asignar PSE FacturalaYa</a></li>';
            } else {
                $menu = $menu.'<li><a href="javascript:void(0)" onclick="quitar_pse_facturalaya('.$id_contribuyente.')"><img src="/facturacionv8/public/img/favicons/apple-icon-57x57.png"> Quitar PSE FacturalaYa</a></li>';
            }

            $menu = $menu.'
            ';
        } else if ($id_rol_user == 5) {
            $menu = '';
        } else {
            $menu = '';
        }

        $menu = '
        <ul class="icons-list text-center">
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right" style="min-width: 270px;">
                    <li>
                        <a target="_blank" href="/facturacionv8/gestiondecontribuyentes/configuracion/'.$id_contribuyente.'"><i class="icon-pencil4"></i> Editar</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="asignar_modulos_sistema('.$id_contribuyente.')"><i class="icon-database-check"></i> Asignación de Módulos</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="ver_estadisticas_contribuyente('.$id_contribuyente.')"><i class="icon-stats-growth"></i> Ver Estadísticas</a>
                    </li>
                    '.$menu.'
                </ul>
            </li>
        </ul>
        ';

        return $menu;
    }

    public function get_total_documentos($id_contribuyente, $tipo_envio_sunat) {

        $query = "SELECT estado_envio_sunat, count(id_contribuyente) as total FROM `doc_electronico` where id_contribuyente = :id_contribuyente and tipo_envio_sunat = :tipo_envio_sunat GROUP BY estado_envio_sunat";
        $sentencia = $this->db->prepare($query);
        $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
        $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
        $sentencia->execute();

        $resp['total_aceptados'] = 0;
        $resp['total_rechazados'] = 0;
        $resp['total_pendientes'] = 0;
        $resp['total_anulados'] = 0;
        $resp['total_tickets'] = 0;
        $resp['estado_desconocido'] = 0;
        $resp['total'] = 0;

        while($item = $sentencia->fetch()) {
            $item = (object)$item;
            if($item->estado_envio_sunat == 'aceptado') {
                $resp['total_aceptados'] = $item->total;
            } else if ($item->estado_envio_sunat == 'pendiente') {
                $resp['total_pendientes'] = $item->total;
            } else if ($item->estado_envio_sunat == 'rechazado') {
                $resp['total_rechazados'] = $item->total;
            } else if ($item->estado_envio_sunat == 'anulado') {
                $resp['total_anulados'] = $item->total;
            } else if ($item->estado_envio_sunat == 'ticket') {
                $resp['total_tickets'] = $item->total;
            } else {
                $resp['estado_desconocido'] = $item->total;
            }
            $resp['total'] = $resp['total'] + $item->total;
        }

        $resp['html_totales'] = '
        <div class="btn-group">
            <a href="javascript:void(0);" class="label border-left-primary label-striped dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> '.$resp['total'].' Documentos<span class="caret"></span></a>

            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="javascript:void(0);"><span class="status-mark position-left border-success"></span>'.$resp['total_aceptados'].' Aprobados</a></li>
                <li><a href="javascript:void(0);"><span class="status-mark position-left border-danger"></span>'.$resp['total_rechazados'].'  Rechazados</a></li>
                <li><a href="javascript:void(0);"><span class="status-mark position-left border-info"></span>'.$resp['total_pendientes'].'  Pendientes</a></li>
                <li><a href="javascript:void(0);"><span class="status-mark position-left border-danger"></span>'.$resp['total_anulados'].'  Anulados</a></li>
                <li><a href="javascript:void(0);"><span class="status-mark position-left border-primary"></span>'.$resp['total_tickets'].' Pendiente por Consulta Ticket</a></li>
                <li><a href="javascript:void(0);"><span class="status-mark position-left border-danger"></span>'.$resp['estado_desconocido'].'  con Estado Desconocido</a></li>
            </ul>
        </div>
        ';

        return $resp;
    }

    public function guardarFechaSuscripcionAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            if($usuario->id_contribuyente != 1) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'No tiene permisos para realizar esta acción.';
                echo json_encode($msj);
                exit();
            }

            $id_contribuyente = empty($datapost['id_contribuyente'])?0:intval($datapost['id_contribuyente']);
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

            $array_fecha_expiracion = explode('/',!isset($datapost['fecha_expiracion'])?'':$datapost['fecha_expiracion']);
            $fecha_expiracion = $array_fecha_expiracion[2].'-'.$array_fecha_expiracion[1].'-'.$array_fecha_expiracion[0];
            if (!(DateTime::createFromFormat('Y-m-d', $fecha_expiracion) !== FALSE)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La fecha de expiración no es válida!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = '¿Deseas Hacer el Cambio?';
				$resp['mensaje'] = '¿Realmente Deseas Actualizar los Datos?';
				echo json_encode($resp);
				exit();
            }
            
            $contribuyente->fecha_expiracion = $fecha_expiracion;
            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
				exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Confirmado!';
            $resp['mensaje'] = 'Se ha cambiado la fecha de suscripción!!';
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaInactivosAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = array();
            echo json_encode($resp);
            exit();
        }
    }

    public function guardarExpiraCertificadoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes para agregar un nuevo contribuyente!.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes para agregar un nuevo contribuyente!.';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = intval($datapost['id_contribuyente']);
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

            $array_fecha_expiracion = explode('/',!isset($datapost['fecha_expiracion'])?'':$datapost['fecha_expiracion']);
            $fecha_expiracion = $array_fecha_expiracion[2].'-'.$array_fecha_expiracion[1].'-'.$array_fecha_expiracion[0];
            if (!(DateTime::createFromFormat('Y-m-d', $fecha_expiracion) !== FALSE)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La fecha de expiración no es válida!';
                echo json_encode($resp);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_fecha = $herramientas->comparar_fechas($fecha_expiracion, date('Y-m-d'));
            if($resp_fecha['diferencia_primera_segunda'] <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La fecha de expiración debe ser mayor a la fecha actual!';
                echo json_encode($resp);
                exit();
            }
            
            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '¿Realmente Deseas Actualizar los Datos?';
				echo json_encode($resp);
				exit();
			}

            $contribuyente->fecha_expira_cert = date("Y-m-d", strtotime($fecha_expiracion));

            if(!$contribuyente->save()) {
                $msg = '';
                foreach ($contribuyente->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'Se han actualizado los datos correctamente!';
            echo json_encode($resp);
            exit();
        }
    }

    public function verEstadisticasContribuyenteAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el usuario.';
                echo json_encode($resp);
                exit();
            }

            $econtribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$econtribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la Empresa.';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la Empresa en consulta';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                if($contribuyente->id_patrocinador != $econtribuyente->id_contribuyente) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No tiene permiso para ver estos datos';
                    echo json_encode($resp);
                    exit();
                }
            }
            
            try {
                $resp['respuesta'] = 'ok';
                $resp['numdocs_emitidos'] = $this->get_cantidad_docs_emitidos($id_contribuyente);
                $resp['montodocs_emitidos'] = $this->get_montos_por_documentos($id_contribuyente);
                $resp['cantidad_productos'] = $this->get_cantidad_productos($id_contribuyente);
                $resp['cantidad_sucursales'] = $this->get_cantidad_sucursales($id_contribuyente);
                $resp['cantidad_clientes'] = $this->get_cantidad_clientes($id_contribuyente);
                $resp['cantidad_usuarios'] = $this->get_cantidad_usuarios($id_contribuyente);
                $resp['ultimo_plan_suscripcion'] = '';
                $resp['lista_planesbase_html'] = array();

                echo json_encode($resp);
                exit();
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
        }
    }

    public function get_cantidad_docs_emitidos($id_contribuyente) {
        setlocale(LC_TIME, "es_ES");

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            return $resp;
        }

        $monthFormatter = new IntlDateFormatter(
            'es_ES',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Madrid',
            IntlDateFormatter::GREGORIAN,
            'MMMM'
        );

        $fechaActual = new DateTime();

        // Último día del mes anterior
        $ultimo_dia_mes_anterior = (clone $fechaActual)->modify('last day of previous month')->format('Y-m-d');
        $fecha_actual = $fechaActual->format('Y-m-d');
        
        for ($i = 1; $i <= 7; $i++) {
            $fechaBase = (clone $fechaActual)->modify("-" . ($i - 1) . " month");
            
            ${"fecha_inicio_$i"} = $fechaBase->modify('first day of this month')->format('Y-m-d');
            ${"fecha_fin_$i"} = $fechaBase->modify('last day of this month')->format('Y-m-d');
            ${"mes_$i"} = ucfirst($monthFormatter->format($fechaBase));
            ${"anio_$i"} = $fechaBase->format('Y');
        }
        
        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_1,
            'fecha_fin'             => $fecha_fin_1,
            'mes'                   => $mes_1,
            'anio'                  => $anio_1,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '', $contribuyente->tipo_envio_sunat, 'otros')
        );
        
        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_2,
            'fecha_fin'             => $fecha_fin_2,
            'mes'                   => $mes_2,
            'anio'                  => $anio_2,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '', $contribuyente->tipo_envio_sunat, 'otros')
        );

        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_3,
            'fecha_fin'             => $fecha_fin_3,
            'mes'                   => $mes_3,
            'anio'                  => $anio_3,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '', $contribuyente->tipo_envio_sunat, 'otros')
        );

        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_4,
            'fecha_fin'             => $fecha_fin_4,
            'mes'                   => $mes_4,
            'anio'                  => $anio_4,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_4, $fecha_fin_4, '', $contribuyente->tipo_envio_sunat, 'otros')
        );

        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_5,
            'fecha_fin'             => $fecha_fin_5,
            'mes'                   => $mes_5,
            'anio'                  => $anio_5,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_5, $fecha_fin_5, '', $contribuyente->tipo_envio_sunat, 'otros')
        );

        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_6,
            'fecha_fin'             => $fecha_fin_6,
            'mes'                   => $mes_6,
            'anio'                  => $anio_6,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_6, $fecha_fin_6, '', $contribuyente->tipo_envio_sunat, 'otros')
        );

        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_7,
            'fecha_fin'             => $fecha_fin_7,
            'mes'                   => $mes_7,
            'anio'                  => $anio_7,
            'total_facturas'        => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '01', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_boletas'         => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '03', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_credito'   => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '07', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_debito'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '08', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_guias_remision'  => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '09', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_notas_venta'     => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '77', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cotizaciones'    => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '88', $contribuyente->tipo_envio_sunat, 'otros'),
            'total_cpe'             => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '', $contribuyente->tipo_envio_sunat, 'cpe'),
            'total_otros'           => $this->cantidad_documentos($id_contribuyente, $fecha_inicio_7, $fecha_fin_7, '', $contribuyente->tipo_envio_sunat, 'otros')
        );

        return $data;
    }

    public function cantidad_documentos($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $tipo_envio_sunat, $tipo_docs) {
        if($tipo_docs == 'cpe') {
            if(empty($id_tipodoc_electronico)) {
                $query = "SELECT count(*) total FROM doc_electronico WHERE id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and tipo_envio_sunat = :tipo_envio_sunat";
            } else {
                $query = "SELECT count(*) total FROM doc_electronico WHERE id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and id_tipodoc_electronico = :id_tipodoc_electronico and tipo_envio_sunat = :tipo_envio_sunat";
            }
        } else {
            if(empty($id_tipodoc_electronico)) {
                $query = "SELECT count(*) total FROM doc_no_oficial WHERE id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and modalidad = :tipo_envio_sunat";
            } else {
                $query = "SELECT count(*) total FROM doc_no_oficial WHERE id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and id_tipodocumento = :id_tipodoc_electronico and modalidad = :tipo_envio_sunat";
            }
        }

        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
		$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        if(!empty($id_tipodoc_electronico)) {
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
        }
		$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
		$sentencia->execute();

        $fila = $sentencia->fetch();

        return intval($fila['total']) + 0;
    }

    public function get_cantidad_productos($id_contribuyente) {
        $query = "SELECT count(idproducto) as total FROM `producto` where id_contribuyente = :id_contribuyente and estado = 'activo'";

        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->execute();

        $fila = $sentencia->fetch();

        return intval($fila['total']) + 0;
    }

    public function get_cantidad_sucursales($id_contribuyente) {
        $query = "SELECT count(idsucursal) as total FROM `sucursal` where id_contribuyente = :id_contribuyente and estado = 'activo'";

        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->execute();

        $fila = $sentencia->fetch();

        return intval($fila['total']) + 0;
    }

    public function get_cantidad_clientes($id_contribuyente) {
        $query = "SELECT count(idcliente) as total FROM cliente WHERE id_contribuyente = :id_contribuyente and estado = 'activo'";

        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->execute();

        $fila = $sentencia->fetch();

        return intval($fila['total']) + 0;
    }

    public function get_cantidad_usuarios($id_contribuyente) {
        $query = "SELECT count(idusuario) as total FROM `usuario` WHERE id_contribuyente = :id_contribuyente and estado = 'activo'";

        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->execute();

        $fila = $sentencia->fetch();

        return intval($fila['total']) + 0;
    }

    public function get_montos_por_documentos($id_contribuyente) {
        // Configurar el formateador para el nombre del mes en español
        $monthFormatter = new IntlDateFormatter(
            'es_ES',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Madrid',
            IntlDateFormatter::GREGORIAN,
            'MMMM'
        );

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            return $resp;
        }

        $fechaActual = new DateTime();

        // Último día del mes anterior
        $ultimo_dia_mes_anterior = (clone $fechaActual)->modify('last day of previous month')->format('Y-m-d');
        $fecha_actual = $fechaActual->format('Y-m-d');

        // Generar fechas para los últimos 7 meses
        for ($i = 1; $i <= 7; $i++) {
            $fechaBase = (clone $fechaActual)->modify("-" . ($i - 1) . " month");
            
            ${"fecha_inicio_$i"} = $fechaBase->modify('first day of this month')->format('Y-m-d');
            ${"fecha_fin_$i"} = $fechaBase->modify('last day of this month')->format('Y-m-d');
            ${"mes_$i"} = ucfirst($monthFormatter->format($fechaBase));
            ${"anio_$i"} = $fechaBase->format('Y');
        }
        
        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_1,
            'fecha_fin'             => $fecha_fin_1,
            'mes'                   => $mes_1,
            'anio'                  => $anio_1,
            'monto_total_facturas'        => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '01', $contribuyente->tipo_envio_sunat),
            'monto_total_boletas'         => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '03', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_credito'   => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '07', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_debito'    => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '08', $contribuyente->tipo_envio_sunat),
            'monto_total_guias_remision'  => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '09', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_venta'     => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '77', $contribuyente->tipo_envio_sunat),
            'monto_total_cotizaciones'    => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '88', $contribuyente->tipo_envio_sunat),
            'monto_total_cpe'             => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '', $contribuyente->tipo_envio_sunat),
            'monto_total_otros'           => $this->get_monto_documento($id_contribuyente, $fecha_inicio_1, $fecha_fin_1, '', $contribuyente->tipo_envio_sunat)
        );

        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_2,
            'fecha_fin'             => $fecha_fin_2,
            'mes'                   => $mes_2,
            'anio'                  => $anio_2,
            'monto_total_facturas'        => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '01', $contribuyente->tipo_envio_sunat),
            'monto_total_boletas'         => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '03', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_credito'   => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '07', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_debito'    => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '08', $contribuyente->tipo_envio_sunat),
            'monto_total_guias_remision'  => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '09', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_venta'     => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '77', $contribuyente->tipo_envio_sunat),
            'monto_total_cotizaciones'    => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '88', $contribuyente->tipo_envio_sunat),
            'monto_total_cpe'             => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '', $contribuyente->tipo_envio_sunat),
            'monto_total_otros'           => $this->get_monto_documento($id_contribuyente, $fecha_inicio_2, $fecha_fin_2, '', $contribuyente->tipo_envio_sunat)
        );


        $data[] = array(
            'fecha_inicio'          => $fecha_inicio_3,
            'fecha_fin'             => $fecha_fin_3,
            'mes'                   => $mes_3,
            'anio'                  => $anio_3,
            'monto_total_facturas'        => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '01', $contribuyente->tipo_envio_sunat),
            'monto_total_boletas'         => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '03', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_credito'   => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '07', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_debito'    => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '08', $contribuyente->tipo_envio_sunat),
            'monto_total_guias_remision'  => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '09', $contribuyente->tipo_envio_sunat),
            'monto_total_notas_venta'     => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '77', $contribuyente->tipo_envio_sunat),
            'monto_total_cotizaciones'    => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '88', $contribuyente->tipo_envio_sunat),
            'monto_total_cpe'             => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '', $contribuyente->tipo_envio_sunat),
            'monto_total_otros'           => $this->get_monto_documento($id_contribuyente, $fecha_inicio_3, $fecha_fin_3, '', $contribuyente->tipo_envio_sunat)
        );

        return $data;
    }

    public function get_monto_documento($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $tipo_envio_sunat) {
        setlocale(LC_TIME, "es_ES");

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            return $resp;
        }
        
        if(in_array($id_tipodoc_electronico, array('77', '88'))) {
            $query = "SELECT sum(total) as total FROM doc_no_oficial WHERE id_contribuyente = :id_contribuyente and id_tipodocumento = :id_tipodoc_electronico and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and modalidad = :tipo_envio_sunat";
        } else {
            $query = "SELECT sum(total) as total FROM doc_electronico WHERE id_contribuyente = :id_contribuyente and id_tipodoc_electronico = :id_tipodoc_electronico and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and tipo_envio_sunat = :tipo_envio_sunat";
        }

        $sentencia = $this->db->prepare($query);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
        $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
		$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
		$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
		$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
		$sentencia->execute();

        $fila = $sentencia->fetch();

        return round(floatval($fila['total']), 2) + 0;
    }

    public function getListaModulosSistemaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el usuario.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 5) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Tiene Permisos para Hacer el Cambio';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Existe el Contribuyente!';
                echo json_encode($resp);
                exit();
            }
            
            $array_modulos_value = array(
                'permitir_emision_facturas'             => 'si',
                'permitir_emision_boletas'              => 'si',
                'permitir_emision_notas_credito'        => 'si',
                'permitir_emision_notas_debito'         => 'si',
                'permitir_emision_notas_venta'          => 'si',
                'permitir_emision_guias_remision'       => 'si',
                'permitir_emision_guias_transportista'  => 'si',
                'permitir_emision_ordenes_compra'       => 'si',
                'permitir_acceso_modulo_contabilidad'   => 'si',
                'permitir_acceso_modulo_sire'           => 'si',
                'permitir_acceso_cuentas_cobrar'        => 'si',
                'permitir_acceso_cuentas_pagar'         => 'si',
                'permitir_acceso_compras'               => 'si',
                'permitir_acceso_top_clientes'          => 'si',
                'permitir_acceso_top_vendedores'        => 'si',
                'permitir_acceso_modulo_pos'            => 'si',
            );

            $array_modulos_title = array(
                'permitir_emision_facturas'             => '¿Permitir la Emisión de Facturas?',
                'permitir_emision_boletas'              => '¿Permitir la Emisión de Boletas?',
                'permitir_emision_notas_credito'        => '¿Permitir la Emisión de Notas de Crédito?',
                'permitir_emision_notas_debito'         => '¿Permitir la Emisión de Notas de Débito?',
                'permitir_emision_notas_venta'          => '¿Permitir la Emisión de Notas de Venta?',
                'permitir_emision_guias_remision'       => '¿Permitir la Emisión de Guías de Remisión?',
                'permitir_emision_guias_transportista'  => '¿Permitir la Emisión de Guías de Transportista?',
                'permitir_emision_ordenes_compra'       => '¿Permitir la Emisión de Ordenes de Compra?',
                'permitir_acceso_modulo_contabilidad'   => '¿Permitir el Acceso al Módulo de Contabilidad?',
                'permitir_acceso_modulo_sire'           => '¿Permitir el Acceso al Módulo de Sire?',
                'permitir_acceso_cuentas_cobrar'        => '¿Permitir el Acceso a Cuentas por Cobrar?',
                'permitir_acceso_cuentas_pagar'         => '¿Permitir el Acceso a Cuentas por Pagar?',
                'permitir_acceso_compras'               => '¿Permitir el Acceso a Compras?',
                'permitir_acceso_top_clientes'          => '¿Permitir el Acceso al Top de Clientes?',
                'permitir_acceso_top_vendedores'        => '¿Permitir el Acceso al Top de Vendedores?',
                'permitir_acceso_modulo_pos'            => '¿Permitir el Acceso al Módulo POS?',
            );

            //$lista_colores_checkboxs = array('control-danger', 'control-success', 'control-warning', 'control-info', 'control-custom');
            $array_modulos_color = array(
                'permitir_emision_facturas'             => 'systemoption_control-success',
                'permitir_emision_boletas'              => 'systemoption_control-success',
                'permitir_emision_notas_credito'        => 'systemoption_control-success',
                'permitir_emision_notas_debito'         => 'systemoption_control-success',
                'permitir_emision_notas_venta'          => 'systemoption_control-success',
                'permitir_emision_guias_remision'       => 'systemoption_control-success',
                'permitir_emision_guias_transportista'  => 'systemoption_control-success',
                'permitir_emision_ordenes_compra'       => 'systemoption_control-danger',
                'permitir_acceso_modulo_contabilidad'   => 'systemoption_control-warning',
                'permitir_acceso_modulo_sire'           => 'systemoption_control-info',
                'permitir_acceso_cuentas_cobrar'        => 'systemoption_control-custom',
                'permitir_acceso_cuentas_pagar'         => 'systemoption_control-danger',
                'permitir_acceso_compras'               => 'systemoption_control-warning',
                'permitir_acceso_top_clientes'          => 'systemoption_control-info',
                'permitir_acceso_top_vendedores'        => 'systemoption_control-success',
                'permitir_acceso_modulo_pos'            => 'systemoption_control-primary',
            );
            
            foreach($array_modulos_value as $option_name => $option_value) {
                $array_modulos_value[$option_name] = ($this->get_value_in_option_system($id_contribuyente, $option_name) == 'la_opcion_no_existe') ? 'si' : $this->get_value_in_option_system($id_contribuyente, $option_name);
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Modulos del Sistema';
            $resp['mensaje'] = 'Se obtuvo los modulos del sistema.';
            $resp['modulos'] = $array_modulos_value;
            $resp['modulos_title'] = $array_modulos_title;
            $resp['modulos_color'] = $array_modulos_color;
            $resp['nombre_empresa'] = $contribuyente->razon_social.' - '.$contribuyente->ruc.' ('.$contribuyente->id_contribuyente.')';
            echo json_encode($resp);
            exit();
        }
    }

    public function get_value_in_option_system($id_contribuyente, $option_name) {
        $system_option = SystemOption::findFirst(array("id_contribuyente = :id_contribuyente: and option_name = :option_name:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'option_name' => $option_name)));
        if(!$system_option) {
            return 'la_opcion_no_existe';
        }

        return $system_option->option_value;
    }

    public function guardarSystemOptionModuloAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el usuario.';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 5) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Tiene Permisos para Hacer el Cambio';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Existe el Contribuyente!';
                echo json_encode($resp);
                exit();
            }

            if(!isset($datapost['option_name'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Existe el Modulo!';
                echo json_encode($resp);
                exit();
            }

            $option_name = $datapost['option_name'];
            $option_value = isset($datapost['option_value'])? $datapost['option_value'] : 'si';
            $option_value = ($option_value == 'si')? 'si' : 'no';

            $array_modulos_value = array(
                'permitir_emision_facturas',
                'permitir_emision_boletas',
                'permitir_emision_notas_credito',
                'permitir_emision_notas_debito',
                'permitir_emision_notas_venta',
                'permitir_emision_guias_remision',
                'permitir_emision_guias_transportista',
                'permitir_emision_ordenes_compra',
                'permitir_acceso_modulo_contabilidad',
                'permitir_acceso_modulo_sire',
                'permitir_acceso_cuentas_cobrar',
                'permitir_acceso_cuentas_pagar',
                'permitir_acceso_compras',
                'permitir_acceso_top_clientes',
                'permitir_acceso_top_vendedores',
                'permitir_acceso_modulo_pos',
            );

            if(!in_array($option_name, $array_modulos_value)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'No Existe el Modulo!';
                echo json_encode($resp);
                exit();
            }
            
            $system_option = SystemOption::findFirst(array("id_contribuyente = :id_contribuyente: and option_name = :option_name:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'option_name' => $option_name)));
            if(!$system_option) {
                $system_option = new SystemOption();
                $system_option->id_contribuyente = $id_contribuyente;
                $system_option->option_name = $option_name;
            }

            $system_option->option_value = $option_value;

            try {
                if(!$system_option->save()) {
                    $msg = '';
                    foreach ($system_option->getMessages() as $message) {
                        $msg .= $message;
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Usuario';
                    $resp['mensaje'] = $msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $e->getMessage();
                return (object)$resp;
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Modulos del Sistema';
            $resp['mensaje'] = 'Se guardo correctamente la nueva opción.';
            echo json_encode($resp);
            exit();
        }
    }
}