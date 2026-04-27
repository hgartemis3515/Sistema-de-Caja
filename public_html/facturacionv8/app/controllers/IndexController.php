<?php
class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->setTitle('Sistema de Facturación Electrónica!');
        $this->view->setTemplateAfter('empty');
        $dominio = $_SERVER['HTTP_HOST'];
        $data_extra = DataExtra::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => intval($this->data_patrocinador['id_contribuyente']))));
        if(!$data_extra) {
            return $this->response->redirect('login');
        }

        if(empty($data_extra->html_sitioweb)) {
            return $this->response->redirect('login');
        }
        
        $this->view->html = $data_extra->html_sitioweb;
    }

    
}
?>