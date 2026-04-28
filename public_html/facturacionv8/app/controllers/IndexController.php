<?php
class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->setTitle('Sistema de Facturación Electrónica!');
        $this->view->setTemplateAfter('empty');
        $data_extra = DataExtra::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => intval($this->data_patrocinador['id_contribuyente']))));
        if(!$data_extra) {
            return $this->response->redirect('login');
        }

        if(empty($data_extra->html_sitioweb)) {
            return $this->response->redirect('login');
        }

        $html = (string) $data_extra->html_sitioweb;
        $base = (string) $this->url->getBaseUri();
        $loginPath = (preg_match('#/$#', $base) ? $base : rtrim($base, '/') . '/') . 'login';
        $loginUrl = htmlspecialchars($loginPath, ENT_QUOTES, 'UTF-8');
        $html = preg_replace('/href\s*=\s*["\'](?:\/login|login)["\']/i', 'href="' . $loginUrl . '"', $html);
        $this->view->html = $html;
    }

    
}
?>