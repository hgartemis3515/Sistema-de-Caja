<?php

class EjemplosdepdfController extends ControllerBase

{

    public function indexAction() {
		$this->setTitle('Ejemplos de Pdf');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/ejemplosdepdf.js?i=v2");

    }
}
?>