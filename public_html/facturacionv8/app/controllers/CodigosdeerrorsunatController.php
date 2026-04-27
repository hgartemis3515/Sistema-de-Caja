<?php
class CodigosdeerrorsunatController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Códigos de error');
        $this->view->setTemplateAfter('vacio');

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
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand())
        ->addJs($this->baseUri . "public/js/codigosdeerrorsunat.js?j=".rand());

    }

    public function getListaErroresAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $lista =  SunatCodigoretorno::find();
          
            $array_lista = array();
            foreach($lista as $item) {
                $codigo = '<span class="label bg-success" style="font-size: 12px">'.$item->codigo.'</span>';
                $array_lista[] = array(
                    $codigo,
                    $item->descripcion, 
                    $item->nota
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();
        }
    }
}