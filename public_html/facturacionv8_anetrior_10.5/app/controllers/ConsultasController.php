<?php
class ConsultasController extends ControllerBase {
	
    public function indexAction($id_contribuyente = 0) {
        $id_contribuyente = intval($id_contribuyente) + 0;
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $this->view->disable();
            echo "";
            exit();
        }

        $this->setTitle('Consulta de Documentos');
        $this->view->setTemplateAfter('vacio');
        
        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js")
		->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js")
        ->addJs($this->baseUri . "public/js/consultas.js?i=".rand());

        $this->view->id_contribuyente = $id_contribuyente;
    }
    
    public function consultarAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_contribuyente = $datapost['id_contribuyente'];
            $id_tipodoc_electronico = $datapost['tipo_documento'];
            $serie_numero_documento = $datapost['serie_numero_documento'];
            $fecha_emision = $datapost['fecha_documento'];
            $monto_total = $datapost['monto_total'];
            $resp = $this->verificar_comprobante($id_contribuyente, $id_tipodoc_electronico, $serie_numero_documento, $fecha_emision, $monto_total);
            echo json_encode($resp);
            exit();
        }
    }
    public function verificar_comprobante($id_contribuyente, $id_tipodoc_electronico, $serie_numero_documento, $fecha_emision, $monto_total){
        $id_contribuyente = intval($id_contribuyente) + 0;
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo']='error';
            $resp['mensaje'] = 'No Encontramos el Documento Solicitado!';
            return $resp;
        }
         
        $tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $id_tipodoc_electronico)));
		if(!$tipo_doc_electronico) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No es un documento válido!';
			return $resp;
        }

        if(strlen($serie_numero_documento) <= 5) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No es un documento válido!';
			return $resp;
        }

        $array_serie_numero = explode("-", $serie_numero_documento);
        $serie_documento = isset($array_serie_numero[0])?$array_serie_numero[0]:'';
        $numero_documento = isset($array_serie_numero[1])?$array_serie_numero[1]:'';
        
        if(strlen($serie_documento) > 4) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No es un documento válido!';
			return $resp;
        }
        
        $documento_electronico = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and total = :total:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_documento, 'numero_comprobante' => $numero_documento, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'total' => $monto_total)));

        if(!$documento_electronico) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'No Existe';
            $resp['mensaje'] = 'El documento no existe!';
            return $resp;
        }

        if($documento_electronico->estado_envio_sunat == 'pendiente') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Pendiente';
            $resp['mensaje'] = 'La '.$tipo_doc_electronico->descripcion.' con serie '.$serie_numero_documento." Existe!, sin embargo aún no ha sido informada a SUNAT!";
            return $resp;
        } else if($documento_electronico->estado_envio_sunat == 'aceptado') {
            $herramientas = new HerramientasController;
            $string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$id_tipodoc_electronico."||".$serie_documento."||".$numero_documento."||".$contribuyente->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$id_tipodoc_electronico."||".$serie_documento."||".$numero_documento."||".$contribuyente->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		    $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
            
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Aceptado';
            $resp['mensaje'] = 'La '.$tipo_doc_electronico->descripcion.' con serie '.$serie_numero_documento." Existe!, y fué informada y aceptada por SUNAT el ".$documento_electronico->fecha_comprobante.'<br /><br /><div class="text-center"><a title="Formato A4" target="_blank" href="'.$url_a4.'"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 30px;"></a>
            <a title="Formato Ticket" target="_blank" href="'.$url_ticket.'"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 30px;"></a>
            </a>
            <a title="Versión en XML" target="_blank" href="/facturacionv8/download/downloadcpe/'.$id_contribuyente.'/'.$id_tipodoc_electronico.'/'.$serie_documento.'/'.$numero_documento.'/xml_cpe_zip"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="max-width: 30px;"></a></div>
            ';
            return $resp;
        } else if($documento_electronico->estado_envio_sunat == 'rechazado') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Rechazado';
            $resp['mensaje'] = "El documento existe, sin embargo ha sido rechazado!";
            return $resp;
        } else {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Desconocido';
            $resp['mensaje'] = "No se Reconoce el Estado del Documento!";
            return $resp;
        }
    }
}
?>