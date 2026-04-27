<?php
class  TerminoslegalesController extends ControllerBase {
	
    public function indexAction() {
        
        $this->setTitle('Terminos legales');
        $this->view->setTemplateAfter('vacio');
        
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css");
            
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/js/login.js?i=v2");
    
    }

    public function terminosDelServicioAction() {
        
        $this->setTitle('Terminos del Servicio');
            $this->view->setTemplateAfter('vacio');
        
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css");

        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2");

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => 1)));

        $dominio_base = 'arpsystem.com.pe';
        $nombre_sistema = 'arpsystem.com.pe';
        $representante_legal = '';

        $razon_social_empresa = $contribuyente->razon_social;
        $ruc_empresa = $contribuyente->ruc;
        $email = $contribuyente->email;
        $direccion_empresa = $contribuyente->direccion_fiscal;
        $telefono = $contribuyente->telefono;
        $logo_empresa = $contribuyente->logo_461;

        $this->view->dominio_base = $dominio_base;
        $this->view->razon_social_empresa = $razon_social_empresa;
        $this->view->ruc_empresa = $ruc_empresa;
        $this->view->email = $email;
        $this->view->direccion_empresa = $direccion_empresa;
        $this->view->telefono = $telefono;
        $this->view->representante_legal = $representante_legal;
        $this->view->nombre_sistema = $nombre_sistema;
        $this->view->logo_empresa = $logo_empresa;
    }
}
?>