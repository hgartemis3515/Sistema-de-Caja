<?php
class PruebacoloresController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Prueba');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/anytime.min.js?i=v2")
         ->addJs($this->baseUri . "public/template/assets/js/pages/components_thumbnails.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/media/fancybox.min.js")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/pruebacolores.js?i=v2");

        $lista_disenios_login = Disenopersonalizadosistema::find(array("tipo  = 'login' and estado = 'activo'"));
        if(!$lista_disenios_login) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! Error en búsqueda';
            echo json_encode($resp);
            exit();

        }

        $lista_disenios_registro = Disenopersonalizadosistema::find(array("tipo  = 'registro' and estado = 'activo'"));
        if(!$lista_disenios_registro) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! Error en búsqueda';
            echo json_encode($resp);
            exit();

        }

        $this->view->lista_disenios_registro = $lista_disenios_registro;
        $this->view->lista_disenios_login = $lista_disenios_login;
    }
    public function getColorAction(){

        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
            $opciones_system = Opciones::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$opciones_system) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! No es posible encontrar tu diseño';
                echo json_encode($resp);
                exit();

            }
            $lista_disenios_login = Disenopersonalizadosistema::find(array("tipo  = 'login' and estado = 'activo'"));
            if(!$lista_disenios_login) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Error en búsqueda';
                echo json_encode($resp);
                exit();

            }

            $lista_disenios_registro = Disenopersonalizadosistema::find(array("tipo  = 'registro' and estado = 'activo'"));
            if(!$lista_disenios_registro) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Error en búsqueda';
                echo json_encode($resp);
                exit();

            }
            $resp['respuesta'] = 'ok';
            $resp['color'] = $opciones_system;
            $resp['lista_disenios_login'] = $lista_disenios_login;
            $resp['lista_disenios_registro'] = $lista_disenios_registro;
            echo json_encode($resp);
            exit();

        }
    }
    public function saveAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();
            $idopcion = !isset($datapost['idopcion'])?0:intval($datapost['idopcion']) + 0;
            $radio_color = !isset($datapost['radio_color'])?'':$datapost['radio_color'];
            $id_plantilla_login = !isset($datapost['id_plantilla_login'])?'':$datapost['id_plantilla_login'];
            $id_plantilla_registro = !isset($datapost['id_plantilla_registro'])?'':$datapost['id_plantilla_registro'];
           
            if($radio_color == 'color_solido'){
                $color_solido = !isset($datapost['color_solido'])?'':$datapost['color_solido'];
                if(empty($color_solido)){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error en Código';
                    $msj['mensaje'] = 'Lo sentimos! Debes generar elegir un color, no se permiten campos vacíos.';
                    echo json_encode($msj);
                    exit();
                }
                $num_color = strlen( $color_solido);
                if($num_color < 7 || $num_color > 7){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error en Cáracteres';
                    $msj['mensaje'] = 'Lo sentimos! Tenemos un problema en los caracteres de tu código, solo deben ser 7';
                    echo json_encode($msj);
                    exit();
                }
                
            }else{
                $color_degradado_1 = !isset($datapost['color_degradado_1'])?'':$datapost['color_degradado_1'];
                $color_degradado_2 = !isset($datapost['color_degradado_2'])?'':$datapost['color_degradado_2'];
                if(empty($color_degradado_1)){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error en Código';
                    $msj['mensaje'] = 'Lo sentimos! Debes generar elegir un color, no se permiten campos vacíos.';
                    echo json_encode($msj);
                    exit();
                }
                if(empty($color_degradado_2)){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error en Código';
                    $msj['mensaje'] = 'Lo sentimos! Debes generar elegir un color, no se permiten campos vacíos.';
                    echo json_encode($msj);
                    exit();
                }
                $num_color_1 = strlen( $color_degradado_1);
                $num_color_2 = strlen( $color_degradado_2);
                if($num_color_1 < 7 || $num_color_1 > 7){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error en Cáracteres';
                    $msj['mensaje'] = 'Lo sentimos! Tenemos un problema en los caracteres de tu código, solo deben ser 7';
                    echo json_encode($msj);
                    exit();
                }
                if($num_color_2 < 7 || $num_color_2 > 7){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error en Cáracteres';
                    $msj['mensaje'] = 'Lo sentimos! Tenemos un problema en los caracteres de tu código, solo deben ser 7';
                    echo json_encode($msj);
                    exit();
                }
            }
         
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
              
          
            $opciones_system = Opciones::findFirst(array("id_opcion = :id_opcion:", 'bind' => array('id_opcion' => $idopcion)));

            if(!$opciones_system) {
                $opciones_system = new Opciones();
                $opciones_system->id_contribuyente = $usuario->id_contribuyente;
            }

            $opciones_system->color_fondo_tipo = $radio_color;
            if($radio_color == 'color_solido'){
                $opciones_system->color_fondo_1_rgb = $color_solido; 
                $opciones_system->color_fondo_2_rgb = $color_solido;  

                
            }else{
                $opciones_system->color_fondo_1_rgb =  $color_degradado_1;
                $opciones_system->color_fondo_2_rgb = $color_degradado_2;  
            }
            $opciones_system->id_plantilla_login = $id_plantilla_login;  
            $opciones_system->id_plantilla_registro = $id_plantilla_registro;  

            try {
                if(!$opciones_system->save()) {
                    $msg = '';
                    foreach ($opciones_system->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = '¡El nuevo diseño del sistema se ha actualizado correctamente!!';
            echo json_encode($resp);
            exit();

           
            
            
        }
    }
    public function deshacerDisenoAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true){
            $datapost = $this->request->getPost();

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
            $opciones_system = Opciones::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

            if(!$opciones_system) {
                 $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Diseño';
                $resp['mensaje'] = 'Lo sentimos! No se ha encontrado que tienes asignado un diseño personalizado, actualiza la pantalla y continuar';
                echo json_encode($resp);
                exit();
            }

            $opciones_system->color_fondo_tipo = 'color_degradado';
            $opciones_system->color_fondo_1_rgb =  '#7880f0';
            $opciones_system->color_fondo_2_rgb = '#3f51b5';  
        
            try {
                if(!$opciones_system->save()) {
                    $msg = '';
                    foreach ($opciones_system->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = '¡El diseño del sistema se ha reestablecido correctamente!';
            echo json_encode($resp);
            exit();

           
            
            
        }
    }
}
?>