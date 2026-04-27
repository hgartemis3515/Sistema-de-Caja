<?php
class MiwebsiteController extends ControllerBase {
	
    public function indexAction() {
		$this->setTitle('Mi website');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/media/cropper.min.js?i=v2")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")

            ->addJs($this->baseUri . "public/js/miwebsite/subir_imagenes.js?i=".rand())
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/miwebsite/miwebsite.js?i=".rand());

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $msj['respuesta'] = 'error';
            $msj['titulo'] = 'Error en Código';
            $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
            echo json_encode($msj);
            exit();
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) { 
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
            echo json_encode($resp);
            exit();
        }

        $categorias = EpCategoria::find();
        $this->view->categorias = $categorias;
        $this->view->contribuyente = $contribuyente;
    }

    public function guardarDataAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($contribuyente->modulo_marketing != 'si') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted Aún no ha sido habilitado para utilizar su propio sitio web!';
                echo json_encode($resp);
                exit();
            }

            $dominio_personalizado = !isset($datapost['dominio_personalizado'])?'':$datapost['dominio_personalizado'];
            $txt_logo_461 = !isset($datapost['txt_logo_461'])?'':$datapost['txt_logo_461'];
            $txt_logo_291 = !isset($datapost['txt_logo_291'])?'':$datapost['txt_logo_291'];
            $txt_logo_56 = !isset($datapost['txt_logo_56'])?'':$datapost['txt_logo_56'];

            if(empty($dominio_personalizado)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar un dominio para tu cuenta!';
                echo json_encode($resp);
                exit();
            }

            if(empty($txt_logo_461)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes cargar la imágen de 461 pixeles de ancho!';
                echo json_encode($resp);
                exit();
            }

            if(empty($txt_logo_291)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes cargar la imágen de 291 pixeles de ancho!';
                echo json_encode($resp);
                exit();
            }

            if(empty($txt_logo_56)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes cargar la imágen de 56 pixeles de ancho!';
                echo json_encode($resp);
                exit();
            }

            $ip = gethostbyname($dominio_personalizado);
            if($ip != '158.106.137.158') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El dominio no existe o aún no se ha asignado el registro A!';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 4) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tienes permisos para realizar esta acción!';
                echo json_encode($resp);
                exit();
            }

            $contribuyente->dominio = $dominio_personalizado;
            $contribuyente->logo_461 = $txt_logo_461;
            $contribuyente->logo_291 = $txt_logo_291;
            $contribuyente->logo_56 = $txt_logo_56;

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
            $resp['titulo'] = 'Ok';
            $resp['mensaje'] = 'Se ha guardado correctamente la información';
            echo json_encode($resp);
            exit();
        }
    }

    public function crearNuevaPaginaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($contribuyente->modulo_marketing != 'si') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted Aún no ha sido habilitado para utilizar su propio sitio web!';
                echo json_encode($resp);
                exit();
            }

            $paginas = EpUserpage::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
            if(count($paginas) >= 10) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Solo se permiten hasta 10 páginas por cuenta';
                echo json_encode($resp);
                exit();
            }

            $idtemplate = !isset($datapost['id_plantilla'])?'':intval($datapost['id_plantilla']);
            $template = EpTemplate::findFirst(array("idtemplate = :idtemplate: and estado = 'activo'", 'bind' => array('idtemplate' => $idtemplate)));
            if(!$template) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No Existe el Template Seleccionado';
                echo json_encode($resp);
                exit();
            }

            $new_pagina = new EpUserpage();
            $new_pagina->id_contribuyente = $contribuyente->id_contribuyente;
            $new_pagina->idtemplate = $template->idtemplate;
            $new_pagina->htmlcode = $template->html;
            $new_pagina->mainheadercode = null;
            $new_pagina->fecharegistro = date('Y-m-d H:i:s');
            $new_pagina->imagepreview = $template->img_preview;
            $new_pagina->estado = 'activo';

            try {
                if(!$new_pagina->save()) {
                    $msg = '';
                    foreach ($new_pagina->getMessages() as $message) {
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
            $resp['titulo'] = 'Ok';
            $resp['mensaje'] = 'Se ha creado Correctamente tu nueva Página';
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarPaginaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 4) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tienes permitido eliminar esta página';
                echo json_encode($resp);
                exit();
            }

            $iduserpage = !isset($datapost['iduserpage'])?0:$datapost['iduserpage'];
            $pagina = EpUserpage::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and iduserpage = :iduserpage:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'iduserpage' => $iduserpage)));
            if(!$pagina) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la página a eliminar o ya fué eliminada!';
                echo json_encode($resp);
                exit();
            }

            $pagina->estado = 'inactivo';

            try {
                if(!$pagina->save()) {
                    $msg = '';
                    foreach ($pagina->getMessages() as $message) {
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
            $resp['titulo'] = 'Ok';
            $resp['mensaje'] = 'Se ha eliminado la página!';
            echo json_encode($resp);
            exit();
        }
    }

    public function cambiarHomeAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 4) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tienes permitido eliminar esta página';
                echo json_encode($resp);
                exit();
            }

            $this->db->begin();
            $iduserpage = !isset($datapost['iduserpage'])?0:$datapost['iduserpage'];
            $pagina = EpUserpage::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and iduserpage = :iduserpage:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'iduserpage' => $iduserpage)));
            if(!$pagina) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la página a eliminar o ya fué eliminada!';
                echo json_encode($resp);
                exit();
            }

            $total_paginas = EpUserpage::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            foreach($total_paginas as $item) {
                $pagina_guardada = EpUserpage::findFirst(array("id_contribuyente = :id_contribuyente: and iduserpage = :iduserpage:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'iduserpage' => $item->iduserpage)));
                if($pagina_guardada) {
                    $pagina_guardada->is_home = 'no';

                    try {
                        if(!$pagina_guardada->save()) {
                            $this->db->rollback();
                            $msg = '';
                            foreach ($pagina_guardada->getMessages() as $message) {
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
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = $e->getMessage();
                        echo json_encode($resp);
                        exit();
                    }
                }
            }

            $pagina->is_home = 'si';

            try {
                if(!$pagina->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($pagina->getMessages() as $message) {
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
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Ok';
            $resp['mensaje'] = 'Se ha cambiado el home!';
            echo json_encode($resp);
            exit();
        }
    }

    public function getPaginasAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            $paginas = EpUserpage::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
            $lista = array();
            foreach($paginas as $pagina) {
                $lista[] = array(
                    'idpagina' => $pagina->iduserpage,
                    'imagepreview' => $pagina->imagepreview,
                    'id_contribuyente' => $contribuyente->id_contribuyente,
                    'is_home' => $pagina->is_home
                );
            }
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://arpsystem.com.pe/website/secure/projects?id_contribuyente=$contribuyente->id_contribuyente&clave=789456",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{\"CODDNI\":\"44358964\"}",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: 21",
                "Content-Type: application/json;chartset=utf-8",
                "Host: facturalaya.com",
                "Postman-Token: 71bee68e-f4af-427a-8db9-95d10647da14,cc79cde1-7a80-4207-b39c-5b73172321e2",
                "RequestVerificationToken: LzyGbKsB2lHMuQnWOCSFy4XEmYf5Rha3rugr3fWeFgge9iWDcvzDi-FgY3Glg1Tsgn3lll-uSlUdnlGQJriIDZqd6rWSfcWBh9N28EH9wBs1:Bf04fr_GVOquTAer6KDAGXcEq_uRmfAt2ldiKgorcXh5ZlbYepLjjdXDcQI1vOrSfDZzabiwn3aTwvoZOvdn6eYV_fpsi4N49vL4y30oj6I1",
                "User-Agent: PostmanRuntime/7.20.1",
                "cache-control: no-cache"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $lista_paginas = array();
            if ($err) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No podemos extraer el listado de páginas';
                echo json_encode($resp);
                exit();
            } else {
                $lista_paginas = json_decode($response);
            }

            $resp['respuesta'] = 'ok';
            $resp['paginas'] = $lista;
            $resp['lista_paginas'] = $lista_paginas;
            echo json_encode($resp);
            exit();
        }
    }
    
    public function saveimageAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {   
            $datapost = $this->request->getPost();
			$imgbase64 = $datapost['dataimage'];
			$tipo = $datapost['imagetipo'];
            $image_nombre = 'imguser-'.uniqid().'-'.md5(time()).'.png';
            $destino = "files/upload_user/";
            $file = $destino.$image_nombre;

            $herramientas = new HerramientasController;
			$success = $herramientas->subirimagen_servidor($imgbase64, $file);
            if($success == true)
            {
                $resp['respuesta'] = 'ok';
				$resp['urlimagen'] = '/facturacionv8/herramientas/verimage/'.$image_nombre;
                echo json_encode($resp);
                exit();
            }
            else
            {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
                $resp['explicacion'] = "Error al Subir Imágen";
                echo json_encode($resp);
                exit();
            }
        }
    }
    
    public function getTemplatesAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            $idcategoria = intval($datapost['idcategoria']) + 0;
            if($idcategoria > 0) {
                $categoria = EpCategoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $idcategoria)));
                if(!$categoria) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No Existe la Categoría';
                    echo json_encode($resp);
                    exit();
                }

                if($usuario->id_contribuyente == 1) {
                    $lista_plantillas = EpTemplate::find(array("idcategoria = :idcategoria: and estado = 'activo'", 'bind' => array('idcategoria' => $idcategoria)));
                } else {
                    $lista_plantillas = EpTemplate::find(array("idcategoria = :idcategoria: and estado = 'activo' and modalidad = 'normal'", 'bind' => array('idcategoria' => $idcategoria)));
                }
            } else {
                if($usuario->id_contribuyente == 1) {
                    $lista_plantillas = EpTemplate::find("estado = 'activo'");
                } else {
                    $lista_plantillas = EpTemplate::find("estado = 'activo' and modalidad = 'normal'");
                }
            }

            $lista = array();
            foreach($lista_plantillas as $plantilla) {
                $lista[] = array(
                    'id'    => $plantilla->idtemplate,
                    'nombre' => $plantilla->nombre,
                    'imagen' => $plantilla->img_preview
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            echo json_encode($resp);
            exit();
        }
    }
}