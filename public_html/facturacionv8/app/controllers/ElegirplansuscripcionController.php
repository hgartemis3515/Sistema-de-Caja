<?php
class ElegirplansuscripcionController extends ControllerBase {
    public function indexAction() {
		$this->setTitle('Plan Suscripción');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css");
        
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js")
		->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js")
        ->addJs($this->baseUri . "public/js/apisunat.js")
        ->addJs($this->baseUri . "public/js/general.js");

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $suscripcion = new SuscripcionController;
        $resp_validacion = $suscripcion->validate_subscription($idusuario);
        if($resp_validacion['modo'] == 'usuario_gratuito_prueba') {
            if($resp_validacion['respuesta'] == 'activo') {
                $mensaje = 'Usted está utilizando el sistema de forma gratuita por '.$resp_validacion['dias_transcurridos'].' días... El periodo de prueba termina en: '.$resp_validacion['dias_faltantes_para_expiracion'];
            } else if ($resp_validacion['respuesta'] == 'expirado') {
                $mensaje = 'Su suscripción ha caducado!... Debes elegir uno de nuestros planes para poder continuar utilizando tu cuenta!';
            }
        } else if ($resp_validacion['modo'] == 'usuario_gratuito_produccion') {
            if($resp_validacion['respuesta'] == 'activo') {
                $mensaje = 'Usted está utilizando el sistema de forma gratuita por '.$resp_validacion['dias_transcurridos'].' días... El periodo de prueba termina en: '.$resp_validacion['dias_faltantes_para_expiracion'];
            } else if ($resp_validacion['respuesta'] == 'expirado') {
                $mensaje = 'Su suscripción ha caducado!... Debes elegir uno de nuestros planes para poder continuar utilizando tu cuenta!';
            }
        } else if ($resp_validacion['modo'] == 'usuario_pago') {
            if($resp_validacion['respuesta'] == 'activo') {
                if($resp_validacion['dias_sin_suscripcion'] > 0) {
                    $mensaje = 'Su suscripción finalizó hace '.$resp_validacion['dias_sin_suscripcion'].' días. Le recomendamos renovar su suscripción caso contrario su cuenta se bloqueará en '.$resp_validacion['dias_faltantes_para_expiracion'].' días.';
                } else {
                    $mensaje = 'Su suscripción se encuentra activa!';
                }
            } else if ($resp_validacion['respuesta'] == 'expirado') {
                $mensaje = 'Su suscripción ha finalizado, usted debe renovar su suscripción para seguir utilizando su cuenta!';
            }
        } else {
            $mensaje = '';
        }
        
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        $this->view->planes = $this->get_planes_reseller($usuario->id_contribuyente);
        $this->view->usuario = $usuario;
        $this->view->mensaje_suscripcion = $mensaje;
        $this->view->resp_validacion = $resp_validacion;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $id_patrocinador = $contribuyente->id_patrocinador;

        $this->view->id_contribuyente = $id_patrocinador;
    }

    public function get_planes_reseller($id_contribuyente) {

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        $id_patrocinador = $contribuyente->id_patrocinador;

        $planes = Planreseller::find(array("id_contribuyente = :id_contribuyente: and rxpbase_idplanbase <> 8", 'bind' => array('id_contribuyente' => $id_patrocinador), "order" => "precio ASC", "limit" => 3));
        $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_patrocinador)));

        if(count($planes) < 3) {
            $id_patrocinador = 1;
            $planes = Planreseller::find(array("id_contribuyente = :id_contribuyente: and rxpbase_idplanbase <> 8", 'bind' => array('id_contribuyente' => $id_patrocinador), "order" => "precio ASC", "limit" => 3));
            $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_patrocinador)));
        }

        $lista = array();
        $n = 0;
        foreach($planes as $plan) {
            $lista[$n]['nombre_plan'] = $plan->nombre;
            $lista[$n]['num_dias']  = $plan->num_dias;
            $lista[$n]['precio'] = $plan->precio;
            $lista[$n]['num_telefono'] = $contribuyente->telefono;
            $lista[$n]['num_ruc'] = $contribuyente->ruc;
            $lista[$n]['limite_mes_doc'] = $plan->limite_mes_doc;
            $n++;
        }

        return $lista;
    }
}