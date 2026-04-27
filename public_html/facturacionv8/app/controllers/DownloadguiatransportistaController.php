<?php
class DownloadguiatransportistaController extends ControllerBase
{
    public function download_xml($id_contribuyente = 0, $id_tipodoc_electronico = '', $serie_comprobante = '', $numero_comprobante = 0, $tipo = '') {

        $this->view->disable();
        
        if($tipo != 'xml_cpe_zip' && $tipo != 'xml_cdr_zip') {
            echo "no existe el archivo xml de la guía de remisión";
            exit();
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes iniciar sesión.';
            echo json_encode($resp);
            exit();
        }

        $guia_transportista = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

        if(!$guia_transportista) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encontró la guía de remisión transportista.';
            echo json_encode($resp);
            exit();
        }


        if($tipo == 'xml_cpe_zip') {
            if(empty($guia_transportista->name_xml_zip) || empty($guia_transportista->ruta_xml)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encontró el archivo XML de la guía de remisión transportista.';
                echo json_encode($resp);
                exit();
            }
    
            $ruta_archivo = $guia_transportista->ruta_xml.$guia_transportista->name_xml_zip;
        } else if($tipo == 'xml_cdr_zip') {
            if(empty($guia_transportista->name_cdr) || empty($guia_transportista->ruta_xml)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encontró el archivo XML de la guía de remisión transportista.';
                echo json_encode($resp);
                exit();
            }
    
            $ruta_archivo = $guia_transportista->ruta_xml.$guia_transportista->name_cdr;
        } else {
            echo "no existe el archivo CDR de la guía de remisión";
            exit();
        }

        if (!file_exists($ruta_archivo)) {
            echo "no existe el archivo CDR de la guía de remisión";
            exit();
        }

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$nombre_archivo");
        header("Content-Length: " . filesize($ruta_archivo));

        readfile($ruta_archivo);
        exit();
    }
}
?>