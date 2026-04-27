<?php

class NotificacionController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Dashboard');
        $this->view->setTemplateAfter('main');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/notificacion.js?i=".rand());

            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
    
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

            $total = $this->get_numtotal_docs_pendientes($contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat);
            
            if($total == NULL) {
                $total = $total + 0;   
            }
            $this->view->total = $total;
          
    }

    public function getListaDocPendientesAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
                echo json_encode($resp);
                exit();

            }

            $resp = $this->get_lista_doc_pendientes($usuario);
            echo json_encode($resp);
            exit();
        }
    }
    
    public function get_lista_doc_pendientes($usuario)
    {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            echo json_encode($resp);
            exit();
		}
		
		$id_sucursal = empty($datapost['id_sucursal'])?0:intval($datapost['id_sucursal']);
        $id_vendedor = empty($datapost['id_vendedor'])?0:intval($datapost['id_vendedor']);

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $id_sucursal = $usuario->idsucursal;
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $id_sucursal = $usuario->idsucursal;
                        $id_vendedor = $usuario->idusuario;
                    }
                }
            }
        }
        
        $sql = "";
        if($id_sucursal > 0) {
            $sql = " and id_sucursal = $id_sucursal ";
        }
        
        if($id_vendedor > 0) {
            $sql = $sql." and id_vendedor = $id_vendedor ";
        }
        
        $list_pendientes = DocElectronico::find(array("id_contribuyente = :id_contribuyente: and estado_envio_sunat = 'pendiente' and tipo_envio_sunat = :tipo_envio_sunat: $sql", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
        
        $array_lista = array();
        foreach($list_pendientes as $item){
            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
            $array_lista[] = array($item->fecha_registro, $tipo_comprobante->descripcion.' '.$item->serie_comprobante.': '.$item->numero_comprobante, $cliente->razon_social, $item->total);
        }
        $resp['respuesta'] = 'ok';
        $resp['lista'] = $array_lista;
        $resp['total'] = count($array_lista);
        return $resp;
    }

    public function notificacionesAction(){

        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Usuario';
                    $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
                    echo json_encode($resp);
                    exit();

            }
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! No hemos encontrado su contribuyente, por favor, verifique sesion';
                echo json_encode($resp);
                exit();

            }
            $resp['respuesta'] = 'ok';
            $resp['docs_pendientes'] = $this->get_lista_doc_pendientes($usuario);
            $resp['docs_pendientes_cinco_dias'] = $this->get_fecha_tope($contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat);
            echo json_encode($resp);
            exit();
        }
        
    }

    public function get_numtotal_docs_pendientes($id_contribuyente, $tipo_envio_sunat) {
        $query = "SELECT count(numero_comprobante) as total_docs FROM `doc_electronico` where id_contribuyente = :id_contribuyente and estado_envio_sunat = 'pendiente' and tipo_envio_sunat = :tipo_envio_sunat";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            
            $sentencia->execute();
            $result = $sentencia->fetch();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        
        $total = 0;
        if(!empty($result['total_docs'])) {
            $total = $result['total_docs'] + 0;   
        }
        return $total;
    }
    public function get_fecha_tope() {
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
                echo json_encode($resp);
                exit();

        }
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! No hemos encontrado su contribuyente, por favor, verifique sesion';
            echo json_encode($resp);
            exit();

        }
        $fecha_actual = date("d-m-Y");
        
        $list_pendientes = DocElectronico::find(array("id_contribuyente = :id_contribuyente: and estado_envio_sunat = 'pendiente' and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
        $list_dias_contados = array();
        foreach($list_pendientes as $item){
            $fecha = $item->fecha_registro;
            $fecha_tope = strtotime ( '+5 day' , strtotime ( $fecha ) ) ;
            $fecha_tope = date ( 'd-m-Y' , $fecha_tope );

            if($fecha_tope == $fecha_actual){
               // echo "Ya han pasado 5 días desde que enviaste un comprobante".$item->serie_comprobante.'-'.$numero_comprobate;
               $list_dias_contados[] = $item;
            }
        }
      
    }

}
?>