<?php

class AdministracionproductosController extends ControllerBase

{

    public function indexAction() {
		$this->setTitle('Administración de productos');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/administracionproductos.js?i=v3");

    }

    public function getListContribuyenteAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

			
			$get_list = Contribuyente::find();

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $get_list;
            echo json_encode($resp);
            exit();
		}
	}
}

?>