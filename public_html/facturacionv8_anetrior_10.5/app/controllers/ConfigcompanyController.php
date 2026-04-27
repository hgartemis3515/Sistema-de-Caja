<?php
class ConfigcompanyController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Configuración de Empresa');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css"); 
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/libraries/jquery_ui/core.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
            //->addJs($this->baseUri . "public/template/assets/js/plugins/ui/ripple.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/editors/ace/ace.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/subir_imagen.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
            ->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_factura.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_boleta.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_cotizacion.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_guia_remision.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_nota_credito.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_nota_debito.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_docs_nota_venta.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_api_productos.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany/tab_api_comunicacion_baja.js?i=".rand())
            ->addJs($this->baseUri . "public/js/configcompany.js?i=".rand());
            
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

        $html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$this->dispatcher->forward(array(
				'controller' => 'estadosuscripcion',
				'action'     => 'index'
			));
			return false;
		}
		
		$this->view->html_suscripcion = $html_suscripcion['html'];

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

        if(!$contribuyente) { 
            echo "Usted no tiene permisos para editar esta área!!";
            exit();
        }
        
        $array_validos = array(1, 2, 3, 5);
        if(!in_array($usuario->id_rol, $array_validos)) {
            echo "Usted no tiene permisos para editar esta área!!";
            exit();
        }
        
        $this->view->modalidad_envio_sunat = $contribuyente->modalidad_envio_sunat;
        $this->view->id_contribuyente = $usuario->id_contribuyente;
        $this->view->contribuyente = $contribuyente;
        $this->view->sunat_regimen = SunatTiporegimen::find();
        $this->view->token_contribuyente = $contribuyente->token;
        $this->view->redondear_detraccion_pdf = $this->get_contribuyente_opcion($usuario->id_contribuyente, 'redondear_detraccion_pdf');
        $this->view->permitir_cambio_cuotas = $this->get_contribuyente_opcion($usuario->id_contribuyente, 'permitir_cambio_cuotas');
    }

    public function getDataContribuyenteAction(){
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

            //$id_contribuyente =!isset($datapost['id_contribuyente'])?0:(intval($datapost['id_contribuyente']) + 0);
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa que intenta modificar no existe!';
                echo json_encode($resp);
                exit();
            }
            
            $array_validos = array(1, 2, 3);
            if(!in_array($usuario->id_rol, $array_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene los permisos suficientes para configurar la empresa!.';
                echo json_encode($resp);
                exit();
            }

            $resp_contribuyente = (array)$contribuyente;
            $resp_contribuyente['clave_sol'] = '';
            $resp_contribuyente['pass_certificado'] = '';

            $resp['respuesta'] = 'ok';
            $resp['contribuyente'] = $resp_contribuyente;
            echo json_encode($resp);
            exit();
        }
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

            $herramientas = new HerramientasController;
            
            $ruc = $herramientas->extraer_numeros($datapost['ruc']);
            $nombre_comercial = !isset($datapost['nombre_comercial'])?'':$datapost['nombre_comercial'];
            $razon_social = !isset($datapost['razon_social'])?'':$datapost['razon_social'];
            $telefono = !isset($datapost['telefono'])?'':$datapost['telefono'];
            $idubigeo = !isset($datapost['ubigeo'])?'':$datapost['ubigeo'];
            $urbanizacion = !isset($datapost['urbanizacion'])?'':$datapost['urbanizacion'];
            $direccionfiscal = !isset($datapost['direccionfiscal'])?'':$datapost['direccionfiscal'];
            $img_logo = !isset($datapost['ruta_logo'])?'':$datapost['ruta_logo'];
            $email = !isset($datapost['email_empresa'])?'':$datapost['email_empresa'];
            
            $password_login = !isset($datapost['password_login'])?'':$datapost['password_login'];

            $usuario_sol = !isset($datapost['usuario_sol'])?'':$datapost['usuario_sol'];
            $password_sol = !isset($datapost['password_sol'])?'':$datapost['password_sol'];
            $certificado_file = '';
            $certificado_pass = !isset($datapost['password_certificado'])?'':$datapost['password_certificado'];
            $tipo_proceso = !isset($datapost['opcion_tipo_proceso'])?'':$datapost['opcion_tipo_proceso'];
            $id_contribuyente = !isset($datapost['id_contribuyente'])?'':$datapost['id_contribuyente'];

            $sunat_idregimen = !isset($datapost['sunat_idregimen'])?0:intval($datapost['sunat_idregimen']);
            $restriccion_stock = !isset($datapost['restriccion_stock'])?'no':$datapost['restriccion_stock'];
            $multi_almacen = !isset($datapost['multi_almacen'])?'si':$datapost['multi_almacen'];
            $precio_venta_minimo = !isset($datapost['precio_venta_minimo'])?'no':$datapost['precio_venta_minimo'];

            $ver_fecha_vencimiento = !isset($datapost['producto_fecha_vencimiento'])?'no':(($datapost['producto_fecha_vencimiento'] == 'si')?'si':'no');
            $ver_marca = !isset($datapost['producto_marca'])?'no':(($datapost['producto_marca'] == 'si')?'si':'no');

            $tipo_busqueda_doc = !isset($datapost['tipo_busqueda_doc'])?'completa':$datapost['tipo_busqueda_doc'];

            $cotizacion_con_igv = !isset($datapost['cotizacion_con_igv'])?'si':(($datapost['cotizacion_con_igv'] == 'si')?'si':'no');
            $mostrar_uprecio_clieprod = !isset($datapost['mostrar_uprecio_clieprod'])?'si':(($datapost['mostrar_uprecio_clieprod'] == 'si')?'si':'no');

            $informar_condpago_sunat = !isset($datapost['informar_condpago_sunat'])?'no':($datapost['informar_condpago_sunat'] == 'si'?'si':'no');
            $permitir_cambio_cuotas = !isset($datapost['permitir_cambio_cuotas'])?'no':($datapost['permitir_cambio_cuotas'] == 'si'?'si':'no');
            $redondear_detraccion_pdf = !isset($datapost['redondear_detraccion_pdf'])?'si':($datapost['redondear_detraccion_pdf'] == 'no'?'no':'si');

            $nombre_ose = !isset($datapost['nombre_ose'])?null:$datapost['nombre_ose'];
            
            $u_prueba_ose = !isset($datapost['u_prueba_ose'])?null:$datapost['u_prueba_ose'];
            $c_prueba_ose = !isset($datapost['c_prueba_ose'])?null:$datapost['c_prueba_ose'];
            $url_prueba_ose = !isset($datapost['url_prueba_ose'])?null:$datapost['url_prueba_ose'];
            
            $u_produccion_ose = !isset($datapost['u_produccion_ose'])?null:$datapost['u_produccion_ose'];
            $c_produccion_ose = !isset($datapost['c_produccion_ose'])?null:$datapost['c_produccion_ose'];
            $url_produccion_ose = !isset($datapost['url_produccion_ose'])?null:$datapost['url_produccion_ose'];

            $url_facebook = !isset($datapost['url_facebook'])?null:$datapost['url_facebook'];
            $url_twitter = !isset($datapost['url_twitter'])?null:$datapost['url_twitter'];
            $url_tiktok = !isset($datapost['url_tiktok'])?null:$datapost['url_tiktok'];
            $url_instagram = !isset($datapost['url_instagram'])?null:$datapost['url_instagram'];
            $url_youtube = !isset($datapost['url_youtube'])?null:$datapost['url_youtube'];

            $sunat_u_sol_principal = !isset($datapost['sunat_u_sol_principal'])?null:$datapost['sunat_u_sol_principal'];
            $sunat_p_sol_principal = !isset($datapost['sunat_p_sol_principal'])?null:$datapost['sunat_p_sol_principal'];
            $sunat_client_id = !isset($datapost['sunat_client_id'])?null:$datapost['sunat_client_id'];
            $sunat_client_secret = !isset($datapost['sunat_client_secret'])?null:$datapost['sunat_client_secret'];

            $user_sol_busq_cpe = !isset($datapost['user_sol_busq_cpe'])?null:$datapost['user_sol_busq_cpe'];
            $pass_sol_busq_cpe = !isset($datapost['pass_sol_busq_cpe'])?null:$datapost['pass_sol_busq_cpe'];

            $num_decimales = !isset($datapost['num_decimales'])?2:intval($datapost['num_decimales']);
            if($num_decimales < 2) {
                $num_decimales = 2;
            }

            if($num_decimales > 10) {
                $num_decimales = 10;
            }

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
                echo json_encode($resp);
                exit();
            }

            //$razon_social = !isset($resp_busqueda_ruc['data']->razon_social)?'':$resp_busqueda_ruc['data']->razon_social;
            
            if($razon_social == '') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos la Razón Social!';
                echo json_encode($resp);
                exit();
            }
            
            if(empty($nombre_comercial)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Ingresar un Nombre Comercial!';
                echo json_encode($resp);
                exit();
            }
            
            $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $idubigeo)));
            if(!$sql_ubigeo){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo siento, ese  código de Ubicación Geográfica no existe. Por favor selecciona un ubigeo Válido';
                echo json_encode($msj);
                exit();
            }
            
            if(empty($urbanizacion)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el nombre de la urbanización!';
                echo json_encode($resp);
                exit();
            }

            if(empty($direccionfiscal)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Ingresar la Dirección de la Empresa!';
                echo json_encode($resp);
                exit();
            }

            if(empty($email)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar un email válido!';
                echo json_encode($resp);
                exit();
            }

            $sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $sunat_idregimen)));
            if(!$sunat_tiporegimen) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Seleccionar el Régimen al que Perteneces!';
                echo json_encode($resp);
                exit();
            }

            $this->db->begin();

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

            if(!$contribuyente) { 
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
                echo json_encode($resp);
                exit();
            }

            if(!in_array($restriccion_stock, array('si', 'no'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debe seleccionar la restricción de STOCK';
                echo json_encode($resp);
                exit();
            }

            if(!in_array($multi_almacen, array('si', 'no'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debe seleccionar si deseas que el sistema sea multi-almacén o trabaje con un único almacén';
                echo json_encode($resp);
                exit();
            }

            if(!in_array($tipo_busqueda_doc, array('completa', 'basica'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debe seleccionar el tipo de búsqueda. (La búsqueda completa mostrará la foto, fecha de nacimiento, dirección, ubigeo y edad. La búsqueda básica mostrará tan solo nombres)';
                echo json_encode($resp);
                exit();
            }
 
            if($tipo_proceso != 'on') {
                $contribuyente->tipo_envio_sunat = 'produccion';
            } else {
                $contribuyente->tipo_envio_sunat = 'prueba';
            }
            
            $contribuyente->nombre_comercial = $nombre_comercial;
            $contribuyente->razon_social = $razon_social;
            $contribuyente->email = $email;
            $contribuyente->telefono = $telefono;
            $contribuyente->codigo_ubigeo = $idubigeo;
            $contribuyente->urbanizacion = $urbanizacion;
            $contribuyente->direccion_fiscal = $direccionfiscal;
            $contribuyente->img_logo = $img_logo;
            $contribuyente->modalidad_envio_sunat = $modalidad_envio_sunat;
            $contribuyente->sunat_idregimen = $sunat_idregimen;
            $contribuyente->restriccion_stock = $restriccion_stock;
            $contribuyente->multi_almacen = $multi_almacen;
            $contribuyente->precio_venta_minimo = $precio_venta_minimo;
            $contribuyente->ver_fecha_vencimiento = $ver_fecha_vencimiento;
            $contribuyente->ver_marca = $ver_marca;
            $contribuyente->cotizacion_con_igv = $cotizacion_con_igv;
            $contribuyente->mostrar_uprecio_clieprod = $mostrar_uprecio_clieprod;
            $contribuyente->informar_condpago_sunat = $informar_condpago_sunat;

            $contribuyente->nombre_ose = $nombre_ose;
            $contribuyente->u_prueba_ose = $u_prueba_ose;
            $contribuyente->c_prueba_ose = $c_prueba_ose;
            $contribuyente->url_prueba_ose = $url_prueba_ose;

            $contribuyente->u_produccion_ose = $u_produccion_ose;
            $contribuyente->c_produccion_ose = $c_produccion_ose;
            $contribuyente->url_produccion_ose = $url_produccion_ose;

            $contribuyente->num_decimales = $num_decimales;
            $contribuyente->tipo_busqueda_doc = $tipo_busqueda_doc;

            $contribuyente->url_facebook = $url_facebook;
            $contribuyente->url_twitter = $url_twitter;
            $contribuyente->url_tiktok = $url_tiktok;
            $contribuyente->url_instagram = $url_instagram;
            $contribuyente->url_youtube = $url_youtube;

            if(!empty($user_sol_busq_cpe)) {
                $contribuyente->user_sol_busq_cpe = $user_sol_busq_cpe;    
            }
            
            if(!empty($pass_sol_busq_cpe)) {
                $contribuyente->pass_sol_busq_cpe = $pass_sol_busq_cpe;
            }
            
            if(!empty($sunat_u_sol_principal)) {
                $contribuyente->sunat_u_sol_principal = $sunat_u_sol_principal;
            }

            if(!empty($sunat_p_sol_principal)) {
                $contribuyente->sunat_p_sol_principal = $sunat_p_sol_principal;
            }

            if(!empty($sunat_client_id)) {
                $contribuyente->sunat_client_id = $sunat_client_id;
            }

            if(!empty($sunat_client_secret)) {
                $contribuyente->sunat_client_secret = $sunat_client_secret;
            }

            $contribuyente->regimen_retencion = 0.03;

            if($tipo_proceso != 'on') { //si es diferente a ON, entonces ingresó a producción.
                
                if($contribuyente->tipo_certificado != 'pse' && $contribuyente->tipo_certificado != 'pse_facturalaya') {
                    $usuario_sol = empty($usuario_sol)?$contribuyente->usuario_sol:$usuario_sol;
                    $password_sol = empty($password_sol)?$contribuyente->clave_sol:$password_sol;
                    $certificado_pass = empty($certificado_pass)?$contribuyente->pass_certificado:$certificado_pass;
                } else {
                    $usuario_sol = empty($contribuyente->usuario_sol)?'-':$contribuyente->usuario_sol;
                    $password_sol = empty($contribuyente->clave_sol)?'-':$contribuyente->clave_sol;
                    $certificado_pass = empty($contribuyente->pass_certificado)?'-':$contribuyente->pass_certificado;
                }

                if(empty($usuario_sol)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Si ya vas a pasar a producción, entonces debes ingresar tu Usuario SOL - SUNAT';
                    echo json_encode($resp);
                    exit();
                }

                if(empty($contribuyente->clave_sol) && empty($password_sol)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Si ya vas a pasar a producción, entonces debes ingresar tu Clave Sol - SUNAT';
                    echo json_encode($resp);
                    exit();
                }

                if($contribuyente->tipo_certificado != 'pse' && $contribuyente->tipo_certificado != 'pse_facturalaya') {
                    $gestiondecontribuyentes = new GestiondecontribuyentesController;
                    $resp_valid_sol = $gestiondecontribuyentes->validar_credenciales_sol($ruc, $usuario_sol, $password_sol);
                    if($resp_valid_sol->respuesta == 'error') {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Su Clave SOl y Usuario SOL no son válidos!, por favor ingrese sus datos correctos y válidos!';
                        echo json_encode($resp);
                        exit();
                    }

                    if(!isset($_FILES['archivo']) && empty($contribuyente->ruta_certificado)) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Por favor debe subir su certificado electrónico para poder firmar sus documentos enviados a SUNAT';
                        echo json_encode($resp);
                        exit();
                    }

                    if(empty($contribuyente->pass_certificado) && empty($certificado_pass)) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Si ya vas a pasar a producción, entonces debes ingresar el password de tu certificado electrónico';
                        echo json_encode($resp);
                        exit();
                    }
                }

                if(isset($_FILES['archivo'])) {
                    $ruta_temporal_certificado = $_FILES['archivo']['tmp_name'];
                    //$certPassword = !isset($datapost['password_certificado'])?'':$datapost['password_certificado'];
                    $data = file_get_contents($ruta_temporal_certificado);
                    if(!openssl_pkcs12_read($data, $certs, $certificado_pass)) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Usted ha ingresado un password errado para su certificado electrónico!';
                        echo json_encode($resp);
                        exit();
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
                        echo json_encode($resp);
                        exit();
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

                if($contribuyente->tipo_empresa_sunat == 'prico') {

                    if(empty($u_produccion_ose)) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Ingresa el Usuario para el ambiente de producción de la OSE';
                        echo json_encode($resp);
                        exit();
                    }

                    if(empty($c_produccion_ose)) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Ingresa la clave del usuario para el ambiente de producción de la OSE';
                        echo json_encode($resp);
                        exit();
                    }

                    if(empty($url_produccion_ose)) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Ingresa la Ruta para el ambiente de producción de la OSE';
                        echo json_encode($resp);
                        exit();
                    }
                }

                $contribuyente->ruta_certificado = !isset($_FILES['archivo']['tmp_name'])?$contribuyente->ruta_certificado:"firma/".$_FILES['archivo']['name'];
                $contribuyente->tipo_envio_sunat = 'produccion';
            }

            if($contribuyente->tipo_empresa_sunat == 'prico') {
                if(empty($nombre_ose)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Selecciona el nombre de la OSE';
                    echo json_encode($resp);
                    exit();
                }

                if(empty($u_prueba_ose)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Ingresa el USUARIO de prueba el ambiente de pruebas de la OSE';
                    echo json_encode($resp);
                    exit();
                }

                if(empty($c_prueba_ose)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Ingresa la CLAVE para el ambiente de pruebas de la OSE';
                    echo json_encode($resp);
                    exit();
                }

                if(empty($url_prueba_ose)) {
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Ingresa la Ruta para el ambiente de pruebas de la OSE';
                    echo json_encode($resp);
                    exit();
                }
            }
            
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
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $this->set_contribuyente_opciones($contribuyente->id_contribuyente, 'redondear_detraccion_pdf', $redondear_detraccion_pdf);
            $this->set_contribuyente_opciones($contribuyente->id_contribuyente, 'permitir_cambio_cuotas', $permitir_cambio_cuotas);

            $log = new LogsController;
            $data_log['id_contribuyente'] = $contribuyente->id_contribuyente;
            $data_log['idusuario'] = $usuario->idusuario;
            $data_log['descripcion'] = 'Se Actualizó los Datos en la Pantalla de Configurar Empresa';
            $data_log['tabla'] = 'contribuyente';
            $data_log['columna'] = 'all';
            $resp_log = $log->log_configuracion_sistema($data_log);

            if($resp_log['respuesta'] == 'error') {
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se ha podido guardar el log de la operación';
                $resp['resp_log'] = $resp_log;
                echo json_encode($resp);
                exit();
            }

            $this->db->commit();

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Genial!';
            $resp['id_contribuyente'] = $contribuyente->id_contribuyente;
            $resp['mensaje'] = 'Los datos fueron guardados correctamente!';
            $resp['resp_log'] = $resp_log;
            echo json_encode($resp);
            exit();
        }
    }

    public function generarTokenContribuyenteAction() {
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

            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permisos para generar un nuevo token, contacte con su administrador.';
                echo json_encode($resp);
                exit();
            }
            
            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '¿Realmente Deseas Actualizar el TOKEN?';
				echo json_encode($resp);
				exit();
            }
            
            $herramientas = new HerramientasController;
            $token = $herramientas->gettoken(37);

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
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
            $resp['titulo'] = 'OK';
            $resp['token'] = $contribuyente->token;
            $resp['mensaje'] = 'Se han actualizado los datos correctamente!';
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarLogoAction() {
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

            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permisos para generar un nuevo token, contacte con su administrador.';
                echo json_encode($resp);
                exit();
            }
            
            $tipo_logo = isset($datapost['tipo_logo'])?(($datapost['tipo_logo'] == 'cuadrado'?'cuadrado':'rectangular')):'';
            if(empty($tipo_logo)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debe Seleccionar un tipo de Logo';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '¿Realmente Deseas Eliminar el Logo?';
				echo json_encode($resp);
				exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

            if($tipo_logo == 'cuadrado') {
                $contribuyente->img_logo = null;
            } else if($tipo_logo == 'rectangular') {
                $contribuyente->logo_350 = null;
            }

            try {
                if(!$contribuyente->save()) {
                    $msg = '';
                    foreach ($contribuyente->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se ha logrado guardar los cambios!'.$msg;
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
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'Hemos guardado correctamente los cambios';
            echo json_encode($resp);
            exit();
        }
    }
}
?>