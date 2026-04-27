<?php

class GestionrestauranteController extends ControllerBase

{

    public function indexAction() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss("css/main-indigo.css")
        ->addCss("css/new_style.css");
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }

    public function dashboardAction() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('layout1');

        $this->assets
        ->addCss("css/main-indigo.css")
        ->addCss("css/new_style.css");
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }
    public function iniciarcuentaAction() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('layout1');

        $this->assets
        ->addCss("css/main-indigo.css")
        ->addCss("css/new_style.css");
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }
    public function cocinaAction() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('layout1');

        $this->assets
        ->addCss("css/main-indigo.css")
        ->addCss("css/new_style.css");
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }
    public function reservaAction() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('vacio');

      
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }
    public function reserva2Action() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('vacio');

      
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }
    public function gestionreservasAction() {
		$this->tag->setTitle('Pantallas');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss("css/main-indigo.css")
        ->addCss("css/new_style.css");
        $this->assets
        ->addJs("template_new/theme_1/js/app.js")
        ->addJs("template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs("template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs("template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs("template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs("js/general.js?j=".rand());
        
    }
}
?>