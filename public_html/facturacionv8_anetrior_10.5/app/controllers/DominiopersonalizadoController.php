<?php
class DominiopersonalizadoController extends ControllerBase {

	public function indexAction($id_contribuyente = 0) {
		$this->setTitle('Dominio personalizado');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/subir_imagen.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/dominiopersonalizado.js?i=v3");

		$auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst("idusuario = ".$idusuario);

        $id_contribuyente = intval($id_contribuyente) + 0;
        if(!isset($id_contribuyente) || $id_contribuyente == 0) {
            $id_contribuyente = $usuario->id_contribuyente;
        }
        
        $array_validos = array(1, 2, 3, 5);
        if(!in_array($usuario->id_rol, $array_validos)) {
            echo "Usted no tiene permisos para editar esta área!!";
            exit();
        }

        if($usuario->id_rol == 3) {
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        } else if($usuario->id_rol == 5) {
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente: and id_patrocinador = :id_patrocinador:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_patrocinador' => $usuario->id_contribuyente)));
        } else {
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        }
        
        if(!$contribuyente) {
            echo "no existe el contribuyente!";
            exit();
        }

        $this->view->contribuyente = $contribuyente;
        $this->view->usuario = $usuario;
    }

    public function guardarAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            //Datos de sesion
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

            $datapost = $this->request->getPost();
            $dominio = isset($datapost['dominio'])?$datapost['dominio']:'';
            $logo461x95 = isset($datapost['logo461x95'])?$datapost['logo461x95']:'';
            $logo291x60 = isset($datapost['logo291x60'])?$datapost['logo291x60']:'';
            $logo56x56 = isset($datapost['logo56x56'])?$datapost['logo56x56']:'';
            $captcha_key_private = isset($datapost['captcha_key_private'])?$datapost['captcha_key_private']:'';
            $captcha_key_public = isset($datapost['captcha_key_public'])?$datapost['captcha_key_public']:'';
            $id_contribuyente = isset($datapost['id_contribuyente'])?$datapost['id_contribuyente']:'';

            $array_validos = array(1, 2, 3, 5);
            if(!in_array($usuario->id_rol, $array_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Permisos';
                $resp['mensaje'] = 'Usted no tiene permiso para realizar esta operación!';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol == 3) {
                $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            } else if($usuario->id_rol == 5) {
                $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente: and id_patrocinador = :id_patrocinador:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_patrocinador' => $usuario->id_contribuyente)));
            } else {
                $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            }

            
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el contribuyente!';
                echo json_encode($resp);
                exit();
            }

            if(empty($logo461x95) || empty($logo291x60) || empty($logo56x56)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar todas la imágenes para poder personalizar el sistema con tu logo!!';
                echo json_encode($resp);
                exit();
            }

            $urlData = parse_url('http://'.$dominio);
            if($urlData === false) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El dominio es incorrecto!';
                echo json_encode($resp);
                exit();
            }

            if($dominio != $urlData['host']) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El dominio es incorrecto!';
                echo json_encode($resp);
                exit();
            }
            
            if($usuario->id_rol == 1 || $usuario->id_rol == 2) {
                $contribuyente_igual_dominio = Contribuyente::findFirst(array("id_contribuyente <> :id_contribuyente: and dominio = :dominio:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'dominio' => $dominio)));
            } else {
                $contribuyente_igual_dominio = Contribuyente::findFirst(array("id_contribuyente <> :id_contribuyente: and dominio = :dominio:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'dominio' => $dominio)));
            }

            if($contribuyente_igual_dominio) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Usuario: '.$contribuyente_igual_dominio->razon_social.', ya tiene asignado el dominio: '.$dominio.', debes ingresar un dominio diferente!';
                echo json_encode($resp);
                exit();
            }

            if($contribuyente->dominio != $dominio) {
                //indica que es la primera vez que guarda el dominio
                $contribuyente->dominio = $dominio;
                $contribuyente->https = 'no';
            }

            $contribuyente->logo_461 = $logo461x95;
            $contribuyente->logo_291 = $logo291x60;
            $contribuyente->logo_56 = $logo56x56;
            $contribuyente->captcha_key_private = $captcha_key_private;
            $contribuyente->captcha_key_public = $captcha_key_public;

            try {
                if(!$contribuyente->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se logró guardar correctamente los datos!';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se logró guardar correctamente los datos!';
                echo json_encode($resp);
                exit();
            }
            
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'Recuerda seguir el procedimiento que se encuentra al final de esta pantalla, caso contrario no funcionará!';
            echo json_encode($resp);
            exit();
        }
    }
}


?>