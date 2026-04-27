<?php
class VideotutorialesController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Centro de entrenamiento');
        $this->view->setTemplateAfter('vacio');

        $this->assets
        ->addCss($this->baseUri . "public/extras/modal-video/css/YouTubePopUp.css")
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/extras/modal-video/js/YouTubePopUp.jquery.js?v=2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/components_popups.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/videotutoriales/index.js");
    }
}
?>