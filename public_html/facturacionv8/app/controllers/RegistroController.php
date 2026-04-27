<?php
class RegistroController extends ControllerBase {
	
    public function indexAction($id_plantilla = 0) {
        $this->setTitle('Registrarse');
        $this->view->setTemplateAfter('vacio');
        
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs("https://www.google.com/recaptcha/api.js?i=v2", false)
            ->addJs($this->baseUri . "public/js/login.js?i=v2");
        
        $auth = $this->getSessionUser();
        if(!is_null($auth)) {
            $this->redirect_user($auth);
        }
        if(!isset($_GET['accion'])) {
            $accion = 'login';
        } else {
            $accion = 'register';
        }
        
        $data_inicial = $this->get_parametros_iniciales();
        $patrocinador = Contribuyente::findFirst(array('id_contribuyente = :id_contribuyente:', 'bind' => array('id_contribuyente' => $data_inicial['id_patrocinador'])));
        
        if(empty($id_plantilla)) {
            $id_plantilla_registro = isset($data_inicial['custom_data_style']['id_plantilla_registro'])?intval($data_inicial['custom_data_style']['id_plantilla_registro']):14;
        } else {
            $id_plantilla_registro = $id_plantilla;
        }

        $this->view->accion = $accion;
        $this->view->lista_ubigeo = SunatCodigoubigeo::find();
        $this->view->id_plantilla_registro = $id_plantilla_registro;
        $this->view->patrocinador = $patrocinador;
    }
}
?>