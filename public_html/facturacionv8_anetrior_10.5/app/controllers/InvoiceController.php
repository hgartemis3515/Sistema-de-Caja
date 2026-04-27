<?php
class InvoiceController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Comprobantes de pago');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-blue.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/login.js?i=v2")
            ->addJs($this->baseUri . "public/js/global.js?i=v2");
    }
}
?>
