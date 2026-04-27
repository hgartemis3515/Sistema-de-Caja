<?php
class EstadosuscripcionController extends ControllerBase {
    public function indexAction() {
		$this->setTitle('Estado de tu Suscripción');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js");
		
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$html = $this->get_html_suscripcion($this->get_data_suscripcion($idusuario));
		$this->view->html_suscripcion = $html['html'];
	}
}