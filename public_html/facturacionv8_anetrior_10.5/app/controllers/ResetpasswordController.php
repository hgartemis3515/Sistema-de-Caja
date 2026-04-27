<?php
class ResetpasswordController extends ControllerBase {
	
    public function indexAction() {
        $this->setTitle('Recuperar Contraseña');
        $this->view->setTemplateAfter('vacio');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/login.js?i=v2")
        ->addJs($this->baseUri . "public/js/global.js?i=v2");

	}
}
?>