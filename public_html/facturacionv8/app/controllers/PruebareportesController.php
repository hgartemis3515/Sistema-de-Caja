<?php
class PruebareportesController extends ControllerBase {
	
    public function indexAction() {

        $this->setTitle('Prueba');
        $this->view->setTemplateAfter('main');
        
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/jgrowl.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/daterangepicker.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/anytime.min.js?i=v2")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/visualization/d3/d3.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/visualization/d3/d3_tooltip.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/ripple.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/visualization/echarts/echarts.js?i=v2")  
        
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?i=v2")
        ->addJs($this->baseUri . "public/js/pruebareportes.js?i=v2");
        
       
    }

    public function generar_reporteAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $fecha_inicio = !isset($datapost['fechainicio'])?'':$datapost['fechainicio'];
            $fecha_final = !isset($datapost['fechafinal'])?'':$datapost['fechafinal'];
            $rangofechas = !isset($datapost['rangofechas'])?'':$datapost['rangofechas'];
            $select_criterio_visualizacion = !isset($datapost['select_criterio_visualizacion'])?'':$datapost['select_criterio_visualizacion'];
            $id_sucursal = !isset($datapost['select_sucursal'])?'':$datapost['select_sucursal'];
            $id_vendedor = !isset($datapost['select_vendedores'])?'':$datapost['select_vendedores'];
        
           
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            
            $facturas = DocElectronico::find(array("id_contribuyente = :id_contribuyente: and 
            CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST(fecha_registro AS DATE) 
            <= CAST(:fecha_final: AS DATE) and estado_envio_sunat in ('aceptado', 'pendiente') and id_tipodoc_electronico in ('01', '03',  '08') and id_sucursal = :id_sucursal: and id_vendedor = :id_vendedor:", 'bind' => array('id_contribuyente' =>$usuario->id_contribuyente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final, 'id_sucursal' => $id_sucursal,'id_vendedor' => $id_vendedor)));
            $total_facturas = count($facturas);

            $agrup_sucursales = "SELECT id_vendedor, id_sucursal, count(*) as total_documentos FROM doc_electronico WHERE 
            CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(fecha_registro AS DATE) 
            <= CAST(:fecha_final AS DATE) AND id_tipodoc_electronico IN ('01', '03', '08') AND id_vendedor = :id_vendedor GROUP BY id_sucursal
            ";

            try {
                $sentencia = $this->db->prepare($agrup_sucursales);
                $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_final', $fecha_final, PDO::PARAM_STR);
                $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
                $sentencia->bindParam(':id_vendedor', $id_vendedor, PDO::PARAM_INT);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: (generar_reporteAction) ',  $e->getMessage(), "\n";
                exit();
            } 
            
            while($lista_sucursales = $sentencia->fetch()){
                $lista_sucursales = (object)$lista_sucursales;
                echo $lista_sucursales->total_documentos;
            }
        }
    }
}
?>