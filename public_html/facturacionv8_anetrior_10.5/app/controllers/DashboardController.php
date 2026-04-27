<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DashboardController extends ControllerBase
{
    public function indexAction($id_contribuyente = '', $id_tipodoc = '', $serie = '', $num_doc = '') {
		$this->setTitle('Dashboard');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css")
            ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")

            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

            ->addJs($this->baseUri . "public/templatev4/assets/js/vendor/visualization/echarts/echarts.min.js")
            
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/dashboard.js?i=".rand())
            ->addJs($this->baseUri . "public/js/importacioncpe.js?i=".rand())
            ->addJs($this->baseUri . "public/js/dashboard/crear_gre_masivo.js?i=".rand())
            ->addJs($this->baseUri . "public/js/modal_aviso.js?i=".rand())
            ->addJs($this->baseUri . "public/js/cuentasporcobrar/lista_abonos.js?i=".rand())
            ->addJs($this->baseUri . "public/js/dashboard/asignar_etiquetas.js?i=".rand());

            $this->view->id_tipodoc = $id_tipodoc;

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $this->view->disable();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes iniciar sesión.';
            echo json_encode($resp);
            exit();
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

        $html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$this->dispatcher->forward(array(
				'controller' => 'estadosuscripcion',
				'action'     => 'index'
			));
			return false;
		}
		
		$this->view->html_suscripcion = $html_suscripcion['html'];

        $lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo in ('contado', 'tarjeta_credito', 'transferencia')", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->lista_condiciones_pago = $lista_condiciones_pago;
        
        $this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        
        $this->view->contribuyente = $contribuyente;
        if($usuario->id_rol == 3 || $usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {
            $this->view->rol = 'admin';
        } else {
            $this->view->rol = 'vendedor';
        }

        $this->view->mostrar_graficos = $this->verificar_permiso_estadisticas_dashboard($usuario);

        $select_sucursal = 0;
        $select_vendedor = 0;

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $select_sucursal = $usuario->idsucursal;
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $select_sucursal = $usuario->idsucursal;
                        $select_vendedor = $usuario->idusuario;
                    }
                }
            }
        }

        if($select_sucursal == 0) {
            $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        } else {
            $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $select_sucursal)));
        }

        if($select_vendedor == 0) {
            $lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        } else {
            $lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idusuario' => $select_vendedor)));
        }

        $lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo in ('contado', 'tarjeta_credito', 'transferencia')", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->lista_condiciones_pago = $lista_condiciones_pago;
        
        $this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        
        $ocultar_opciones_avanzadas_dashboard = $this->get_usuario_opcion($usuario->id_contribuyente, $usuario->idusuario, 'ocultar_opciones_avanzadas_dashboard');
        $opt_rango_mostrar_ventas = $this->get_usuario_opcion($usuario->id_contribuyente, $usuario->idusuario, 'opt_rango_mostrar_ventas');
        

        $this->view->lista_sucursales = $lista_sucursales;
        $this->view->lista_usuarios = $lista_usuarios;
        $this->view->usuario = $usuario;
        $this->view->select_sucursal = $select_sucursal;
        $this->view->select_vendedor = $select_vendedor;
        $this->view->ocultar_opciones_avanzadas_dashboard = $ocultar_opciones_avanzadas_dashboard;
        $this->view->opt_rango_mostrar_ventas = $opt_rango_mostrar_ventas;
        $this->view->rango_fechas_dashboard = $this->get_rango_fechas_dashboard($opt_rango_mostrar_ventas);
    }

    public function get_rango_fechas_dashboard($opt_rango_mostrar_ventas) {
        // Ejemplo de valor de la variable:
        // $opt_rango_mostrar_ventas = 'ultimos_30_dias';
        // $opt_rango_mostrar_ventas = 'mes_actual';
        // $opt_rango_mostrar_ventas = 'dia_actual';
        $opt_rango_mostrar_ventas = ($opt_rango_mostrar_ventas == '')?'ultimos_30_dias':$opt_rango_mostrar_ventas;

        // Variables para las fechas de inicio y fin (texto para el input visible y valor para el input hidden)
        $fecha_inicio_txt   = '';
        $fecha_inicio_valor = '';
        $fecha_fin_txt      = '';
        $fecha_fin_valor    = '';

        // Ajustar fechas de inicio/fin según la opción
        switch ($opt_rango_mostrar_ventas) {
            case 'ultimos_30_dias':
                // Fecha inicio: hoy - 30 días
                $fecha_inicio_txt   = date('d-m-Y', strtotime('-30 days'));
                $fecha_inicio_valor = date('Y-m-d 00:00:00', strtotime('-30 days'));

                // Fecha fin: día actual a las 23:59:59
                $fecha_fin_txt   = date('d-m-Y 23:59:59');
                $fecha_fin_valor = date('Y-m-d 23:59:59');
                break;

            case 'mes_actual':
                // Fecha inicio: primer día del mes actual
                $fecha_inicio_txt   = date('01-m-Y');
                $fecha_inicio_valor = date('Y-m-01 00:00:00');

                // Fecha fin: último día del mes actual a las 23:59:59
                // (Usamos 't' para obtener el número total de días del mes actual)
                $fecha_fin_txt   = date('t-m-Y 23:59:59');
                $fecha_fin_valor = date('Y-m-t 23:59:59');
                break;

            case 'dia_actual':
                // Fecha inicio: día actual a las 00:00:00
                $fecha_inicio_txt   = date('d-m-Y');
                $fecha_inicio_valor = date('Y-m-d 00:00:00');

                // Fecha fin: día actual a las 23:59:59
                $fecha_fin_txt   = date('d-m-Y 23:59:59');
                $fecha_fin_valor = date('Y-m-d 23:59:59');
                break;

            default:
                // Caso por defecto, suponemos que es como 'ultimos_30_dias'
                $fecha_inicio_txt   = date('d-m-Y', strtotime('-30 days'));
                $fecha_inicio_valor = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $fecha_fin_txt   = date('d-m-Y 23:59:59');
                $fecha_fin_valor = date('Y-m-d 23:59:59');
                break;
        }

        return array(
            'fecha_inicio_txt'   => $fecha_inicio_txt,
            'fecha_inicio_valor' => $fecha_inicio_valor,
            'fecha_fin_txt'      => $fecha_fin_txt,
            'fecha_fin_valor'    => $fecha_fin_valor
        );
    }

    public function dashboard_2Action($id_contribuyente = '', $id_tipodoc = '', $serie = '', $num_doc = '') {
        $this->setTitle('Dashboard');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/main-indigo.css")
            ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")

            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

            ->addJs($this->baseUri . "public/templatev4/assets/js/vendor/visualization/echarts/echarts.min.js")
            
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/dashboard_2.js?i=".rand())
            ->addJs($this->baseUri . "public/js/importacioncpe.js?i=".rand())
            ->addJs($this->baseUri . "public/js/modal_aviso.js?i=".rand())
            ->addJs($this->baseUri . "public/js/cuentasporcobrar/lista_abonos.js?i=".rand())
            ->addJs($this->baseUri . "public/js/dashboard/asignar_etiquetas.js?i=".rand());

            $this->view->id_tipodoc = $id_tipodoc;

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $this->view->disable();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes iniciar sesión.';
            echo json_encode($resp);
            exit();
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

        $html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$this->dispatcher->forward(array(
				'controller' => 'estadosuscripcion',
				'action'     => 'index'
			));
			return false;
		}
		
		$this->view->html_suscripcion = $html_suscripcion['html'];

        $lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo in ('contado', 'tarjeta_credito', 'transferencia')", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->lista_condiciones_pago = $lista_condiciones_pago;
        
        $this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        
        $this->view->contribuyente = $contribuyente;
        if($usuario->id_rol == 3 || $usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {
            $this->view->rol = 'admin';
        } else {
            $this->view->rol = 'vendedor';
        }

        $this->view->mostrar_graficos = $this->verificar_permiso_estadisticas_dashboard($usuario);

        $select_sucursal = 0;
        $select_vendedor = 0;

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $select_sucursal = $usuario->idsucursal;
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $select_sucursal = $usuario->idsucursal;
                        $select_vendedor = $usuario->idusuario;
                    }
                }
            }
        }

        if($select_sucursal == 0) {
            $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        } else {
            $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $select_sucursal)));
        }

        if($select_vendedor == 0) {
            $lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        } else {
            $lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idusuario' => $select_vendedor)));
        }

        $lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo in ('contado', 'tarjeta_credito', 'transferencia')", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->lista_condiciones_pago = $lista_condiciones_pago;
        
        $this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        
        
        $this->view->lista_sucursales = $lista_sucursales;
        $this->view->lista_usuarios = $lista_usuarios;
        $this->view->usuario = $usuario;
        $this->view->select_sucursal = $select_sucursal;
        $this->view->select_vendedor = $select_vendedor;
    }

    public function verificar_permiso_estadisticas_dashboard($usuario) {
        if(!isset($usuario->permisos) || empty($usuario->permisos)) {
            return true;
        }

		$permisos = json_decode($usuario->permisos, true);
        if($permisos === false || is_null($permisos)){
            return true;
        }

		if(!isset($permisos['permisos_para_reportes']['opt_verreportes_reportes']['top_estadisticas_dashboard'])) {
			return true;
		}

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes', 'top_estadisticas_dashboard') == false) {
					return false;
				}
			}
		}
		return true;
	}

    public function getDatosEstadisticosDashboard2Action() {
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
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
			}

			$id_contribuyente = $usuario->id_contribuyente;
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}
            $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

            $gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                            $datapost['sucursales'] = $usuario->idsucursal;
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                            $datapost['sucursales'] = $usuario->idsucursal;
                            $datapost['vendedores'] = $usuario->idusuario;
                        }
                    }
                }
            }
            
			$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
			$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
			$fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
            $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];
            
			$fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
            $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
            
            if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
			if(count($ids_vendedores) > 0) {
				foreach($ids_vendedores as $id_vendedor) {
					$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
					if(!$vendedor) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error en Vendedor';
						$resp['mensaje'] = 'Uno de los vendedores seleccionados no está activo o no existe!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			if (($key_sucursales = array_search(0, $ids_sucursales)) !== false) { unset($ids_sucursales[$key_sucursales]);}
			if(count($ids_sucursales) > 0) {
				foreach($ids_sucursales as $idsucursal) {
					$sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
					if(!$sucursal_bd) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error en Sucursal';
						$resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
						echo json_encode($resp);
						exit();
					}
				}
            }
            
            $caja_chica = new CajachicaController;
            $totales_ventas = $caja_chica->get_resumen_ventas($fecha_inicio_time, $fecha_fin_time, $usuario->id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat);
            $resp['respuesta'] = 'ok';
            $resp['totales'] = $totales_ventas;


            //Series para gráfico
            $periodos_validos = array('dia', 'mes', 'anio');
            $periodo = !in_array($datapost['periodo'], $periodos_validos)?'dia':$datapost['periodo'];

            $array_serie_facturas_soles = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '01', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'PEN');
            $array_serie_boletas_soles = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '03', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'PEN');
            $array_serie_notas_credito_soles = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '07', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'PEN');
            $array_serie_notas_debito_soles = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '08', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'PEN');
            $array_serie_notas_venta_soles = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '77', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'PEN');
            $array_serie_total_soles = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, 'TOTAL', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'PEN');

            $array_serie_facturas_dolares = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '01', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'USD');
            $array_serie_boletas_dolares = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '03', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'USD');
            $array_serie_notas_credito_dolares = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '07', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'USD');
            $array_serie_notas_debito_dolares = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '08', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'USD');
            $array_serie_notas_venta_dolares = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, '77', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'USD');
            $array_serie_total_dolares = $this->get_serie_documento_dashboard_2($contribuyente->id_contribuyente, $fecha_inicio_time, $fecha_fin_time, 'TOTAL', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo, 'USD');

            $array_periodo_array = $this->get_array_periodo($fecha_inicio_time, $fecha_fin_time, $periodo);

            $serie_facturas_soles = array_values($array_serie_facturas_soles);
            $serie_boletas_soles = array_values($array_serie_boletas_soles);
            $serie_notas_credito_soles = array_values($array_serie_notas_credito_soles);
            $serie_notas_debito_soles = array_values($array_serie_notas_debito_soles);
            $serie_notas_venta_soles = array_values($array_serie_notas_venta_soles);
            $serie_total_soles = array_values($array_serie_total_soles);

            $serie_facturas_dolares = array_values($array_serie_facturas_dolares);
            $serie_boletas_dolares = array_values($array_serie_boletas_dolares);
            $serie_notas_credito_dolares = array_values($array_serie_notas_credito_dolares);
            $serie_notas_debito_dolares = array_values($array_serie_notas_debito_dolares);
            $serie_notas_venta_dolares = array_values($array_serie_notas_venta_dolares);
            $serie_total_dolares = array_values($array_serie_total_dolares);

            //periodo
            $resp['periodo_array'] = array_keys($array_periodo_array);

            $series_soles['Facturas'] = $serie_facturas_soles;
            $series_soles['Boletas'] = $serie_boletas_soles;
            $series_soles['Notas Crédito'] = $serie_notas_credito_soles;
            $series_soles['Notas Débito'] = $serie_notas_debito_soles;
            $series_soles['Notas Venta'] = $serie_notas_venta_soles;
            $series_soles['Total'] = $serie_total_soles;

            $series_dolares['Facturas'] = $serie_facturas_dolares;
            $series_dolares['Boletas'] = $serie_boletas_dolares;
            $series_dolares['Notas Crédito'] = $serie_notas_credito_dolares;
            $series_dolares['Notas Débito'] = $serie_notas_debito_dolares;
            $series_dolares['Notas Venta'] = $serie_notas_venta_dolares;
            $series_dolares['Total'] = $serie_total_dolares;

            $resp['series_soles'] = $series_soles;
            $resp['series_dolares'] = $series_dolares;

            $resp['prueba_Data'] = $array_serie_facturas_dolares;

            $resp['respuesta'] = 'ok';
            echo json_encode($resp);
            exit();
        }
    }

    public function getDatosEstadisticosAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            setlocale(LC_MONETARY, 'es_PE');
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
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

            $resp = $this->get_datos_estadisticos($datapost, $contribuyente, $usuario);
            echo json_encode($resp);
            exit();
        }
    }

    public function get_datos_estadisticos($datapost, $contribuyente, $usuario) {
        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $datapost['sucursales'] = $usuario->idsucursal;
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $datapost['sucursales'] = $usuario->idsucursal;
                        $datapost['vendedores'] = $usuario->idusuario;
                    }
                }
            }
        }
        
        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];
        
        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        $fecha_inicio = $fecha_inicio_time;
        $fecha_fin = $fecha_fin_time;
        
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$vendedor) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Vendedor';
                    $resp['mensaje'] = 'Uno de los vendedores seleccionados no está activo o no existe!';
                    return $resp;
                }
            }
        }

        if (($key_sucursales = array_search(0, $ids_sucursales)) !== false) { unset($ids_sucursales[$key_sucursales]);}
        if(count($ids_sucursales) > 0) {
            foreach($ids_sucursales as $idsucursal) {
                $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$sucursal_bd) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Sucursal';
                    $resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
                    return $resp;
                }
            }
        }
        

        $periodos_validos = array('dia', 'mes', 'anio');
        $periodo = !in_array($datapost['periodo'], $periodos_validos)?'dia':$datapost['periodo'];

        $resp['respuesta'] = 'ok';
        
        //Totales
        $total_facturas = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '01', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores);
        $total_boletas = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '03', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores);
        $total_notas_credito = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '07', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores);
        $total_notas_debito = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '08', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores);
        $total_notas_venta = $this->get_total_notasdeventa($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '77', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores);

        //
        $array_serie_facturas = $this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '01', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo);
        $array_serie_boletas = $this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '03', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo);
        $array_serie_notas_credito = $this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '07', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo);
        $array_serie_notas_debito = $this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '08', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo);
        $array_serie_notas_venta = $this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '77', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo);
        $array_serie_suma_total = $this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, 'TODO', $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores, $periodo);

        //Series
        $serie_facturas = array_values($array_serie_facturas);
        $serie_boletas = array_values($array_serie_boletas);
        $serie_notas_credito = array_values($array_serie_notas_credito);
        $serie_notas_debito = array_values($array_serie_notas_debito);
        $serie_notas_venta = array_values($array_serie_notas_venta);
        $serie_suma_total = array_values($array_serie_suma_total);

        //array_periodo
        $array_perido = $this->get_array_periodo($fecha_inicio, $fecha_fin, $periodo);

        $resp['respuesta'] = 'ok';

        // Totales
        $resp['total_facturas'] = $this->formatCurrency($total_facturas);
        $resp['total_boletas'] = $this->formatCurrency($total_boletas);
        $resp['total_notas_credito'] = $this->formatCurrency($total_notas_credito);
        $resp['total_notas_debito'] = $this->formatCurrency($total_notas_debito);
        $resp['total_notas_venta'] = $this->formatCurrency($total_notas_venta);
        $resp['total_neto'] = $this->formatCurrency($total_facturas + $total_boletas + $total_notas_debito + $total_notas_venta - $total_notas_credito);

        //periodo
        $resp['periodo_array'] = array_keys($array_perido);

        //Series
        $series['Facturas'] = $serie_facturas;
        $series['Boletas'] = $serie_boletas;
        $series['Notas Crédito'] = $serie_notas_credito;
        $series['Notas Débito'] = $serie_notas_debito;
        $series['Notas Venta'] = $serie_notas_venta;
        $series['Total'] = $serie_suma_total;

        //serie_unido_fechas
        $resp['dataFlChart'] = array(
            'spots' => array(
                'facturas' => $array_serie_facturas,
                'boletas'  => $array_serie_boletas,
                'notas_credito' => $array_serie_notas_credito,
                'notas_debito' => $array_serie_notas_debito,
                'notas_venta' => $array_serie_notas_venta,
                'total' => $array_serie_suma_total
            ),
            'titles' => array_keys($array_perido)
        );
        
        //gráfico circular
        $resp['condiciones_pago'] = $this->get_totales_condicionpago($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $contribuyente->tipo_envio_sunat, $ids_sucursales, $ids_vendedores);

        //Total documentos pendientes de envío
        $resp['docs_pendientes'] = $this->get_numtotal_docs_pendientes($contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat);

        /*
        //totales_desde_caja_chica
        $caja_chica = new CajachicaController;
        $resp['totales'] = $caja_chica->get_resumen_ventas($fecha_inicio, $fecha_fin, $contribuyente->id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat);
        */

        $caja_chica = new CajachicaController;
        $id_contribuyente = $contribuyente->id_contribuyente;
        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
        $boletas_soles = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '03', 'PEN');
        $facturas_soles = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '01', 'PEN');
        $notas_credito_soles = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '07', 'PEN');
        $notas_debito_soles = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '08', 'PEN');
        $notas_venta_soles = $caja_chica->get_resumen_ventas_by_doc_no_oficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '77', 'PEN');
        $abono_soles = $caja_chica->get_totales_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'PEN');

        $boletas_dolares = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '03', 'USD');
        $facturas_dolares = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '01', 'USD');
        $notas_credito_dolares = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '07', 'USD');
        $notas_debito_dolares = $caja_chica->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '08', 'USD');
        $notas_venta_dolares = $caja_chica->get_resumen_ventas_by_doc_no_oficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '77', 'USD');
        $abono_dolares = $caja_chica->get_totales_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'USD');

        $subtotales_soles['contado'] = round($boletas_soles['contado'] + $facturas_soles['contado'] - $notas_credito_soles['contado'] + $notas_debito_soles['contado'] + $notas_venta_soles['contado'] + $abono_soles['contado'], 2) + 0;
        $subtotales_soles['credito'] = round($boletas_soles['credito'] + $facturas_soles['credito'] - $notas_credito_soles['credito'] + $notas_debito_soles['credito'] + $notas_venta_soles['credito'] + $abono_soles['credito'], 2) + 0;
        $subtotales_soles['tarjeta_credito'] = round($boletas_soles['tarjeta_credito'] + $facturas_soles['tarjeta_credito'] - $notas_credito_soles['tarjeta_credito'] + $notas_debito_soles['tarjeta_credito'] + $notas_venta_soles['tarjeta_credito'] + $abono_soles['tarjeta_credito'], 2) + 0;
        $subtotales_soles['transferencia'] = round($boletas_soles['transferencia'] + $facturas_soles['transferencia'] - $notas_credito_soles['transferencia'] + $notas_debito_soles['transferencia'] + $notas_venta_soles['transferencia'] + $abono_soles['transferencia'], 2) + 0;
        $subtotales_soles['monto_adeudado'] = round($boletas_soles['monto_adeudado'] + $facturas_soles['monto_adeudado'] + $notas_credito_soles['monto_adeudado'] + $notas_debito_soles['monto_adeudado'] + $notas_venta_soles['monto_adeudado'], 2) + 0;

        $subtotales_dolares['contado'] = round($boletas_dolares['contado'] + $facturas_dolares['contado'] - $notas_credito_dolares['contado'] + $notas_debito_dolares['contado'] + $notas_venta_dolares['contado'] + $abono_dolares['contado'], 2) + 0;
        $subtotales_dolares['credito'] = round($boletas_dolares['credito'] + $facturas_dolares['credito'] - $notas_credito_dolares['credito'] + $notas_debito_dolares['credito'] + $notas_venta_dolares['credito'] + $abono_dolares['credito'], 2) + 0;
        $subtotales_dolares['tarjeta_credito'] = round($boletas_dolares['tarjeta_credito'] + $facturas_dolares['tarjeta_credito'] - $notas_credito_dolares['tarjeta_credito'] + $notas_debito_dolares['tarjeta_credito'] + $notas_venta_dolares['tarjeta_credito'] + $abono_dolares['tarjeta_credito'], 2) + 0;
        $subtotales_dolares['transferencia'] = round($boletas_dolares['transferencia'] + $facturas_dolares['transferencia'] - $notas_credito_dolares['transferencia'] + $notas_debito_dolares['transferencia'] + $notas_venta_dolares['transferencia'] + $abono_dolares['transferencia'], 2) + 0;
        $subtotales_dolares['monto_adeudado'] = round($boletas_dolares['monto_adeudado'] + $facturas_dolares['monto_adeudado'] + $notas_credito_dolares['monto_adeudado'] + $notas_debito_dolares['monto_adeudado'] + $notas_venta_dolares['monto_adeudado'], 2) + 0;

        $resp['totales']['subtotales_soles'] = $subtotales_soles;
        $resp['totales']['subtotales_dolares'] = $subtotales_dolares;

        $resp['series'] = $series;
        return $resp;
    }

    public function formatCurrency($amount, $currency = 'S/') {
        // Formatea el número con separador de miles y 2 decimales
        return $currency . ' ' . number_format($amount, 2, '.', ',');
    }

    public function getDatosEstadisticosBackupAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            setlocale(LC_MONETARY, 'es_PE');
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
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

            $fecha_actual = date("Y-m-d H:i:s");
            $fecha_inicio_b = date("Y-m-d", strtotime("-29 days", strtotime($fecha_actual)));
            $fecha_fin_b = date("Y-m-d");
            
            $fecha_inicio = ($datapost['fecha_inicio'] == '')?$fecha_inicio_b:$datapost['fecha_inicio'];
            $fecha_fin = ($datapost['fecha_fin'] == '')?$fecha_fin_b:$datapost['fecha_fin'];

            $herramientas = new HerramientasController;
            if(!$herramientas->validate_date($fecha_inicio)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
                echo json_encode($resp);
                exit();
            }

            if(!$herramientas->validate_date($fecha_fin)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Fecha';
                $resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
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

            $periodos_validos = array('dia', 'mes', 'anio');
            $periodo = !in_array($datapost['periodo'], $periodos_validos)?'dia':$datapost['periodo'];

            $resp['respuesta'] = 'ok';
            $resp['fecha_inicio'] = $fecha_inicio;
            $resp['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
            $resp['fecha_fin'] = $fecha_fin;
            $resp['id_sucursal'] = $id_sucursal;
            $resp['id_vendedor'] = $id_vendedor;
            $resp['periodo'] = $periodo;
            $resp['id_contribuyente'] = $contribuyente->id_contribuyente;
            
            //Totales
            $total_facturas = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '01', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);
            $total_boletas = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '03', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);
            $total_notas_credito = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '07', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);
            $total_notas_debito = $this->get_totales_docelectronico($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '08', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);
            $total_notas_venta = $this->get_total_notasdeventa($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '77', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);

            //Series
            $serie_facturas = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '01', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor, $periodo));
            $serie_boletas = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '03', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor, $periodo));
            $serie_notas_credito = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '07', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor, $periodo));
            $serie_notas_debito = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '08', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor, $periodo));
            $serie_notas_venta = array_values($this->get_serie_documento($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, '77', $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor, $periodo));

            $resp['respuesta'] = 'ok';

            //Totales
            $resp['total_facturas'] = 'S/ '.custom_money_format($total_facturas);
            $resp['total_boletas'] = 'S/ '.custom_money_format($total_boletas);
            $resp['total_notas_credito'] = 'S/ '.custom_money_format($total_notas_credito);
            $resp['total_notas_debito'] = 'S/ '.custom_money_format($total_notas_debito);
            $resp['total_notas_venta'] = 'S/ '.custom_money_format($total_notas_venta);
            $resp['total_neto'] = 'S/ '.custom_money_format($total_facturas + $total_boletas + $total_notas_debito + $total_notas_venta - $total_notas_credito);

            //periodo
            $resp['periodo_array'] = array_keys($this->get_array_periodo($fecha_inicio, $fecha_fin, $periodo));

            //Series
            $series['Facturas'] = $serie_facturas;
            $series['Boletas'] = $serie_boletas;
            $series['Notas Crédito'] = $serie_notas_credito;
            $series['Notas Débito'] = $serie_notas_debito;
            $series['Notas Venta'] = $serie_notas_venta;

            //gráfico circular
            $resp['condiciones_pago'] = $this->get_totales_condicionpago($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $contribuyente->tipo_envio_sunat, $id_sucursal, $id_vendedor);

            //Total documentos pendientes de envío
            $resp['docs_pendientes'] = $this->get_numtotal_docs_pendientes($contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat);

            //totales_desde_caja_chica
            $caja_chica = new CajachicaController;

            if($id_sucursal > 0) {
                $ids_sucursales[1] = empty($datapost['id_sucursal'])?0:intval($datapost['id_sucursal']);
            } else {
                $ids_sucursales = array();
            }

            if($id_vendedor > 0) {
                $ids_vendedores[1] = empty($datapost['id_vendedor'])?0:intval($datapost['id_vendedor']);
            } else {
                $ids_vendedores = array();
            }
            
            $resp['cajachica'] = $caja_chica->get_resumen_ventas($fecha_inicio.' 00:00:00', $fecha_fin.' 23:59:59', $contribuyente->id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat);

            $resp['series'] = $series;
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

    public function get_totales_docelectronico($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $tipo_envio_sunat, $ids_sucursales = array(), $ids_vendedores = array()) {
        $query = "SELECT sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM `doc_electronico` where id_contribuyente = :id_contribuyente and fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and tipo_envio_sunat = :tipo_envio_sunat and estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and id_tipodoc_electronico = :id_tipodoc_electronico";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            
            $sentencia->execute();
            $result = $sentencia->fetch();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $total = 0;
        if(!empty($result['total_soles'])) {
            $total = $result['total_soles'] + 0;   
        }

        $query_anticipos = "SELECT sum(round(if(cpe.id_codigomoneda = 'PEN', anticipo.monto_anticipo, anticipo.monto_anticipo*(if(cpe.tipo_cambio_sunat = 0, 1, cpe.tipo_cambio_sunat))), 2)) as total_anticipo FROM doc_electronico cpe INNER JOIN doc_electronico_anticipo anticipo ON (cpe.id_contribuyente = anticipo.cpe_id_contribuyente and cpe.id_tipodoc_electronico = anticipo.cpe_id_tipodoc_electronico and cpe.serie_comprobante = anticipo.cpe_serie_comprobante and cpe.numero_comprobante = anticipo.cpe_numero_comprobante and cpe.tipo_envio_sunat = anticipo.cpe_tipo_envio_sunat ) where cpe.id_contribuyente = :id_contribuyente and cpe.fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and cpe.fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and cpe.tipo_envio_sunat = :tipo_envio_sunat and cpe.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and cpe.id_tipodoc_electronico = :id_tipodoc_electronico";

        if(count($ids_vendedores) > 0){ $query_anticipos = $query_anticipos." and cpe.id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $query_anticipos = $query_anticipos." and cpe.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        try {
            $sentencia_anticipos = $this->db->prepare($query_anticipos);
            $sentencia_anticipos->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_anticipos->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_anticipos->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_anticipos->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_anticipos->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            
            $sentencia_anticipos->execute();
            $result_anticipos = $sentencia_anticipos->fetch();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $total_anticipo = 0;
        if(!empty($result_anticipos['total_anticipo'])) {
            $total_anticipo = $result_anticipos['total_anticipo'] + 0;   
        }
        
        return $total - $total_anticipo;
    }

    public function get_total_notasdeventa($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $tipo_envio_sunat, $ids_sucursales = array(), $ids_vendedores = array()) {
        $query = "SELECT sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM doc_no_oficial where id_contribuyente = :id_contribuyente and fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and modalidad = :modalidad and estado_documento = 'activo' and id_tipodocumento = :id_tipodoc_electronico ";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':modalidad', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            
            $sentencia->execute();
            $result = $sentencia->fetch();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        
        $total = 0;
        if(!empty($result['total_soles'])) {
            $total = $result['total_soles'] + 0;   
        }

        return $total;
    }

    public function get_serie_documento($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $tipo_envio_sunat, $ids_sucursales = array(), $ids_vendedores = array(), $periodo = 'dia') {
        if($periodo == 'mes') { 
            $query_time = '%Y-%m'; 
        } else if ($periodo == 'anio') { 
            $query_time = '%Y'; 
        } else { 
            $query_time = '%Y-%m-%d'; 
        }

        $query_anticipos = "";

        if($id_tipodoc_electronico == '77') {
            $query = "SELECT DATE_FORMAT(fecha_comprobante, '".$query_time."') as fecha, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM `doc_no_oficial` where id_contribuyente = :id_contribuyente and modalidad = :tipo_envio_sunat and estado_documento = 'activo' and fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and id_tipodocumento = :id_tipodoc_electronico ";

            if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
		    if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

            $query = $query." group by DATE_FORMAT(fecha_comprobante, '".$query_time."') ORDER BY fecha ASC";

        } else if($id_tipodoc_electronico == 'TODO'){
            $query_notas_venta = "
                SELECT 
                    DATE_FORMAT(fecha_comprobante, '".$query_time."') as fecha, 
                    sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles 
                FROM `doc_no_oficial` 
                WHERE 
                    id_contribuyente = :id_contribuyente and 
                    modalidad = :tipo_envio_sunat and 
                    estado_documento = 'activo' and 
                    fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and 
                    id_tipodocumento = '77' ";

            $query_cpe = "
                SELECT 
                    DATE_FORMAT(fecha_comprobante, '".$query_time."') as fecha, sum(round(if(id_codigomoneda = 'PEN', 
                    if(id_tipodoc_electronico = '07', -1*total, total), (if(id_tipodoc_electronico = '07', -1, 1))*total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles 
                FROM `doc_electronico` 
                WHERE 
                    id_contribuyente = :id_contribuyente and 
                    tipo_envio_sunat = :tipo_envio_sunat and 
                    estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and 
                    (id_tipodoc_electronico = '01' or id_tipodoc_electronico = '03' or id_tipodoc_electronico = '08' or id_tipodoc_electronico = '07')";

            if(count($ids_vendedores) > 0){ 
                $query_cpe = $query_cpe." and id_vendedor in (".implode(",", $ids_vendedores).") ";
                $query_notas_venta = $query_notas_venta." and id_vendedor in (".implode(",", $ids_vendedores).") "; 
            }

		    if(count($ids_sucursales) > 0){ 
                $query_cpe = $query_cpe." and id_sucursal in (".implode(",", $ids_sucursales).") ";
                $query_notas_venta = $query_notas_venta." and id_sucursal in (".implode(",", $ids_sucursales).") "; 
            }

            $query_cpe = $query_cpe." group by DATE_FORMAT(fecha_comprobante, '".$query_time."') ORDER BY date(fecha_comprobante) ASC";
            $query_notas_venta = $query_notas_venta." group by DATE_FORMAT(fecha_comprobante, '".$query_time."') ORDER BY fecha_comprobante ASC";

            $query = "SELECT fecha, sum(total_soles) total_soles FROM (($query_cpe) UNION ALL ($query_notas_venta)) as tabla_cpe_nv group by fecha ORDER BY fecha ASC";

        } else {
            $query = "SELECT DATE_FORMAT(fecha_comprobante, '".$query_time."') as fecha, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM `doc_electronico` WHERE id_contribuyente = :id_contribuyente and tipo_envio_sunat = :tipo_envio_sunat and estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and id_tipodoc_electronico = :id_tipodoc_electronico ";

            if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
		    if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

            $query = $query." group by DATE_FORMAT(fecha_comprobante, '".$query_time."') ORDER BY fecha ASC";
        }
        
    
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            if($id_tipodoc_electronico != 'TODO') {
                $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            }

            $sentencia->execute();

            $array_periodo = $this->get_array_periodo($fecha_inicio, $fecha_fin, $periodo);
            while ($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_periodo[$fila->fecha] = $fila->total_soles;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada 001: ',  $e->getMessage(), "\n" . $query;
            exit();
        }

        if($id_tipodoc_electronico != '77') {

            $query_anticipos = "
                SELECT 
                    DATE_FORMAT(cpe.fecha_comprobante, '".$query_time."') as fecha, sum(round(if(cpe.id_codigomoneda = 'PEN', if(cpe.id_tipodoc_electronico = '07', -1*anticipo.monto_anticipo, anticipo.monto_anticipo), (if(cpe.id_tipodoc_electronico = '07', -1, 1))*anticipo.monto_anticipo*(if(cpe.tipo_cambio_sunat = 0, 1, cpe.tipo_cambio_sunat))), 2)) as total_anticipo 
                FROM doc_electronico cpe INNER JOIN doc_electronico_anticipo anticipo ON (cpe.id_contribuyente = anticipo.cpe_id_contribuyente and cpe.id_tipodoc_electronico = anticipo.cpe_id_tipodoc_electronico and cpe.serie_comprobante = anticipo.cpe_serie_comprobante and cpe.numero_comprobante = anticipo.cpe_numero_comprobante and cpe.tipo_envio_sunat = anticipo.cpe_tipo_envio_sunat )
                WHERE 
                    cpe.id_contribuyente = :id_contribuyente and 
                    cpe.tipo_envio_sunat = :tipo_envio_sunat and 
                    cpe.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                    cpe.fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and cpe.fecha_comprobante <= CAST(:fecha_fin AS DATETIME) 
            ";

            if($id_tipodoc_electronico == 'TODO') {
                $query_anticipos = $query_anticipos." and (cpe.id_tipodoc_electronico = '01' or cpe.id_tipodoc_electronico = '03' or cpe.id_tipodoc_electronico = '08' or cpe.id_tipodoc_electronico = '07') ";
            } else {
                $query_anticipos = $query_anticipos." and cpe.id_tipodoc_electronico = :id_tipodoc_electronico ";
            }

            if(count($ids_vendedores) > 0){ $query_anticipos = $query_anticipos." and cpe.id_vendedor in (".implode(",", $ids_vendedores).") "; }
		    if(count($ids_sucursales) > 0){ $query_anticipos = $query_anticipos." and cpe.id_sucursal in (".implode(",", $ids_sucursales).") "; }

            $query_anticipos = $query_anticipos." group by DATE_FORMAT(cpe.fecha_comprobante, '".$query_time."') ORDER BY fecha ASC";

            try {
                $sentencia_anticipos = $this->db->prepare($query_anticipos);
                $sentencia_anticipos->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
                $sentencia_anticipos->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $sentencia_anticipos->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia_anticipos->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
                if($id_tipodoc_electronico != 'TODO') {
                    $sentencia_anticipos->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
                }
    
                $sentencia_anticipos->execute();
    
                while ($fila2 = $sentencia_anticipos->fetch()) {
                    $fila2 = (object)$fila2;
                    if(!empty($fila2->total_anticipo)) {
                        if(isset($array_periodo[$fila2->fecha])) {
                            $array_periodo[$fila2->fecha] = $array_periodo[$fila2->fecha] - $fila2->total_anticipo;
                        }
                    }
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada 002: ',  $e->getMessage(), "\n";
                exit();
            }
        }

        return $array_periodo;
    }

    public function get_serie_documento_dashboard_2($id_contribuyente, $fecha_inicio, $fecha_fin, $id_tipodoc_electronico, $tipo_envio_sunat, $ids_sucursales = array(), $ids_vendedores = array(), $periodo = 'dia', $id_codigomoneda = 'PEN') {
        if($periodo == 'mes') { 
            $query_time = '%Y-%m'; 
        } else if ($periodo == 'anio') { 
            $query_time = '%Y'; 
        } else { 
            $query_time = '%Y-%m-%d'; 
        }

        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if($id_tipodoc_electronico == '77') {
            $query = "SELECT DATE_FORMAT(fecha_registro, '".$query_time."') as fecha, sum(total) as total FROM `doc_no_oficial` where id_contribuyente = :id_contribuyente and modalidad = :tipo_envio_sunat and estado_documento = 'activo' and fecha_registro >= CAST(:fecha_inicio AS DATETIME) and fecha_registro <= CAST(:fecha_fin AS DATETIME) and id_tipodocumento = :id_tipodoc_electronico and id_codigomoneda = :id_codigomoneda ".$where_extra;
            $query = $query." group by DATE_FORMAT(fecha_registro, '".$query_time."') ORDER BY date(fecha_registro) ASC";
        } else if($id_tipodoc_electronico == 'TOTAL') {
            $query = "select fecha, sum(total) total from 
            (
            (SELECT DATE_FORMAT(fecha_registro, '".$query_time."') as fecha, sum(round(if(id_tipodoc_electronico = '07', total*(-1), total), 2)) as total FROM `doc_electronico` where id_contribuyente = :id_contribuyente and tipo_envio_sunat = :tipo_envio_sunat and estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and fecha_registro >= CAST(:fecha_inicio AS DATETIME) and fecha_registro <= CAST(:fecha_fin AS DATETIME) and id_tipodoc_electronico in ('01', '03', '08', '07') and id_codigomoneda = :id_codigomoneda $where_extra group by DATE_FORMAT(fecha_registro, '".$query_time."') ORDER BY date(fecha_registro) ASC)
            
            UNION ALL
            
            (SELECT DATE_FORMAT(fecha_registro, '".$query_time."') as fecha, sum(total) as total FROM `doc_no_oficial` where id_contribuyente = :id_contribuyente and modalidad = :tipo_envio_sunat and estado_documento = 'activo' and fecha_registro >= CAST(:fecha_inicio AS DATETIME) and fecha_registro <= CAST(:fecha_fin AS DATETIME) and id_tipodocumento = '77' and id_codigomoneda = :id_codigomoneda $where_extra group by DATE_FORMAT(fecha_registro, '".$query_time."') ORDER BY date(fecha_registro) ASC)
            ) as table_union 
            group by fecha ORDER BY date(fecha) ASC";

        } else {
            $query = "SELECT DATE_FORMAT(fecha_registro, '".$query_time."') as fecha, sum(total) as total FROM `doc_electronico` where id_contribuyente = :id_contribuyente and tipo_envio_sunat = :tipo_envio_sunat and estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and fecha_registro >= CAST(:fecha_inicio AS DATETIME) and fecha_registro <= CAST(:fecha_fin AS DATETIME) and id_tipodoc_electronico = :id_tipodoc_electronico and id_codigomoneda = :id_codigomoneda ".$where_extra;

            $query = $query." group by DATE_FORMAT(fecha_registro, '".$query_time."') ORDER BY date(fecha_registro) ASC";
        }
        
        /*
        $query = str_replace(':id_contribuyente', $id_contribuyente, $query);
        $query = str_replace(':fecha_inicio', '"'.$fecha_inicio.'"', $query);
        $query = str_replace(':fecha_fin', '"'.$fecha_fin.'"', $query);
        $query = str_replace(':tipo_envio_sunat', '"'.$tipo_envio_sunat.'"', $query);
        if($id_tipodoc_electronico != 'TOTAL') {
            $query = str_replace(':id_tipodoc_electronico', '"'.$id_tipodoc_electronico.'"', $query);
        }
        $query = str_replace(':id_codigomoneda', '"'.$id_codigomoneda.'"', $query);
        echo $query;
        exit();
        */
        

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            if($id_tipodoc_electronico != 'TOTAL') {
                $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            }
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);

            $sentencia->execute();

            $array_periodo = $this->get_array_periodo($fecha_inicio, $fecha_fin, $periodo);
            while ($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_periodo[$fila->fecha] = $fila->total;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada 003: ',  $e->getMessage(), "\n";
            exit();
        }

        return $array_periodo;
    }

    public function get_totales_condicionpago($id_contribuyente, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $ids_sucursales = array(), $ids_vendedores = array()) {
        //$query = "SELECT doc_electronico.id_condicionpago id_condicionpago, condiciondepago.tipo tipo_condicionpago, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM doc_electronico LEFT JOIN condiciondepago on doc_electronico.id_condicionpago = condiciondepago.id_condicionpago where doc_electronico.id_contribuyente = $id_contribuyente and id_tipodoc_electronico in ('01', '03', '08') and tipo_envio_sunat = '$tipo_envio_sunat' and CAST(doc_electronico.fecha_comprobante AS DATE) >= CAST('$fecha_inicio' AS DATE) and CAST(doc_electronico.fecha_comprobante AS DATE) <= CAST('$fecha_fin' AS DATE) and estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') ";

        $query = "SELECT doc_electronico.id_condicionpago id_condicionpago, condiciondepago.tipo tipo_condicionpago, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM doc_electronico LEFT JOIN condiciondepago on doc_electronico.id_condicionpago = condiciondepago.id_condicionpago where doc_electronico.id_contribuyente = :id_contribuyente and id_tipodoc_electronico in ('01', '03', '08') and tipo_envio_sunat = :tipo_envio_sunat and doc_electronico.fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and doc_electronico.fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') ";

        $query_notas_ventas = "SELECT doc_no_oficial.id_condicionpago id_condicionpago, condiciondepago.tipo tipo_condicionpago, sum(round(if(id_codigomoneda = 'PEN', total, total*(if(tipo_cambio_sunat = 0, 1, tipo_cambio_sunat))), 2)) as total_soles FROM doc_no_oficial LEFT JOIN condiciondepago on doc_no_oficial.id_condicionpago = condiciondepago.id_condicionpago where doc_no_oficial.id_contribuyente = :id_contribuyente and id_tipodocumento in ('77') and modalidad = :tipo_envio_sunat and doc_no_oficial.fecha_comprobante >= CAST(:fecha_inicio AS DATETIME) and doc_no_oficial.fecha_comprobante <= CAST(:fecha_fin AS DATETIME) and estado_documento = 'activo' ";

        if(count($ids_vendedores) > 0){ 
            $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; 
            $query_notas_ventas = $query_notas_ventas." and id_vendedor in (".implode(",", $ids_vendedores).") "; 
        }

		if(count($ids_sucursales) > 0){ 
            $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; 
            $query_notas_ventas = $query_notas_ventas." and id_sucursal in (".implode(",", $ids_sucursales).") "; 
        }

        $query = $query."  GROUP BY doc_electronico.id_condicionpago";
        $query_notas_ventas = $query_notas_ventas."  GROUP BY doc_no_oficial.id_condicionpago";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada 004: ',  $e->getMessage(), "\n";
            exit();
        }

        try {
            $sentencia2 = $this->db->prepare($query_notas_ventas);
            $sentencia2->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia2->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia2->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia2->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            
            $sentencia2->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada 005: ',  $e->getMessage(), "\n";
            exit();
        }
        
        $tipo_condicion = array();
        //contado, credito, tarjeta_credito, transferencia	
        $tipo_condicion['contado'] = 0;
        $tipo_condicion['credito'] = 0;
        $tipo_condicion['tarjeta_credito'] = 0;
        $tipo_condicion['transferencia'] = 0;
        $total = 0;

        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            if(empty($fila->tipo_condicionpago)) {
                $tipo_condicion['contado'] = $tipo_condicion['contado'] + $fila->total_soles;
            } else {
                $tipo_condicion[$fila->tipo_condicionpago] = $tipo_condicion[$fila->tipo_condicionpago] + $fila->total_soles;
            }
            $total = $total + $fila->total_soles;
        }

        while ($fila = $sentencia2->fetch()) {
            $fila = (object)$fila;
            if(empty($fila->tipo_condicionpago)) {
                $tipo_condicion['contado'] = $tipo_condicion['contado'] + $fila->total_soles;
            } else {
                $tipo_condicion[$fila->tipo_condicionpago] = $tipo_condicion[$fila->tipo_condicionpago] + $fila->total_soles;
            }
            $total = $total + $fila->total_soles;
        }

        $lista_condiciones = array();
        foreach($tipo_condicion as $key => $valor) {
            if($valor > 0) {
                $lista_condiciones[] = array(
                    'name' => $key,
                    'value' => $valor
                );
            }
        }

        return $lista_condiciones;
    }

    public function get_array_periodo($fecha_inicio, $fecha_fin, $periodo) {
        if($periodo == 'mes') {
            $interval_time = 'P1M';
            $format_time = "Y-m";
            $query_time = '%Y-%m';
        } else if ($periodo == 'anio') {
            $interval_time = 'P1Y';
            $format_time = "Y";
            $query_time = '%Y';
        } else {
            $interval_time = 'P1D';
            $format_time = "Y-m-d";
            $query_time = '%Y-%m-%d';
        }

        $periodo_fecha = new DatePeriod(
            new DateTime($fecha_inicio),
            new DateInterval($interval_time),
            new DateTime($fecha_fin)
        );

        $array_date = array();
        foreach ($periodo_fecha as $item) {
            $item = (array)$item;
            $array_date[date($format_time, strtotime($item['date']))] = 0;
        }

        $array_date[date($format_time, strtotime($fecha_fin))] = 0;

        return $array_date;
    }

    public function verificarEstadoSunatAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($contribuyente->tipo_envio_sunat != 'produccion') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Esta Función solo está disponible en producción!';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
                echo json_encode($resp);
                exit();
            }

            if($cliente->num_doc == '00000000') {
                $data['id_doc_cliente'] = '';
                $data['num_doc_cliente'] = '';
            } else {
                $data['id_doc_cliente'] = $cliente->id_tipodocidentidad;
                $data['num_doc_cliente'] = $cliente->num_doc;
            }

            $herramientas = new HerramientasController;
            $data['num_ruc'] = $contribuyente->ruc;
            $data['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
            $data['documento_serie'] = $serie_comprobante;
            $data['documento_numero'] = $numero_comprobante;
           
            $data['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
            $data['documento_total'] = $documento->total;
            
            $response = $herramientas->get_status_doc_sunat($data);
            $resp_api = @json_decode($response);
            $resp_api = @json_decode($resp_api);
            $resp['resp_api'] = $response;
            
            if(empty($resp_api)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['response'] = $resp_api;
                $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!.';
                echo json_encode($resp);
                exit();
            }

            if(!isset($resp_api->rpta)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['response'] = $resp_api;
                $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!..';
                echo json_encode($resp);
                exit();
            }

            if($resp_api->rpta == 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['response'] = $resp_api;
                $resp['mensaje'] = isset($resp_api->mensaje)?$resp_api->mensaje:'SUNAT no está respondiendo correctamente!!...';
                echo json_encode($resp);
                exit();
            }

            if($resp_api->data->estadoCp == '1') {
                $estado_documento_sunat = '<a href="#" class="list-group-item"><i class="icon-file-text2"></i> <span class="label bg-success heading-text">APROBADO</span> Estado del Comprobante a la Fecha de Consulta: </a>';
            } elseif ($resp_api->data->estadoCp == '2') {
                $estado_documento_sunat = '<a href="#" class="list-group-item"><i class="icon-file-text2"></i> <span class="label bg-danger heading-text">ANULADO</span> Estado del Comprobante a la Fecha de Consulta: </a>';
            } else {
                $estado_documento_sunat = '<a href="#" class="list-group-item"><i class="icon-file-text2"></i> <span class="label bg-danger heading-text">-</span> Estado del Comprobante a la Fecha de Consulta: </a>';
            }

            if($resp_api->data->estadoRuc == '00') {
                $estado_ruc_sunat = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-success heading-text">ACTIVO</span> Estado del contribuyente a la fecha de emisión: </a>';
            } else {
                $estado_ruc_sunat = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-danger heading-text">-</span> Estado del contribuyente a la fecha de emisión: </a>';
            }

            if($resp_api->data->condDomiRuc == '00') {
                $estado_domicilio = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-success heading-text">HABIDO</span> Condición de Domicilio a la Fecha de Emisión: </a>';
            } else {
                $estado_domicilio = ' <a href="#" class="list-group-item"><i class="icon-office"></i> <span class="label bg-danger heading-text">-</span> Condición de Domicilio a la Fecha de Emisión: </a>';
            }

            $html_respuesta = '
            <div class="list-group no-border no-padding-top">
                <a href="#" class="list-group-item"><i class="icon-calendar3"></i> <span class="label bg-success heading-text">'.date("d/m/Y").'</span> Fecha de Consulta: </a>
                '.$estado_documento_sunat.$estado_domicilio.$estado_ruc_sunat.'
                <a href="#" class="list-group-item"><i class="icon-database"></i> <span class="label bg-success heading-text">'.$documento->estado_envio_sunat.'</span> Estado en el Sistema: </a>
            </div>
            ';

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Resputa Obtenida';
            $resp['html'] = $html_respuesta;
            if($documento->estado_envio_sunat == 'aceptado') {
                if($documento->name_cdr != null && $documento->name_cdr_zip != null) {
                    $estado_envio_sunat = 'aceptado';
                } else {
                    $estado_envio_sunat = 'sin_cdr';
                }
            } else {
                $estado_envio_sunat = $documento->estado_envio_sunat;
            }

            //logs documento
            $lista_log = LogDocumento::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            $resp['log_documento'] = '';
            $n_l = 0;
            foreach($lista_log as $log) {
                $fecha_log = new DateTime($log->fecha_registro);
                $fecha_log_formateada = $fecha_log->format('d-m-Y h:i a');

                $n_l++;
                if($n_l == 1) {
                    $resp['log_documento'] = $fecha_log_formateada.'<br />'.$log->descripcion.'<hr>';
                } else {
                    $resp['log_documento'] = $resp['log_documento'].'<br />'.$fecha_log_formateada.' '.$log->descripcion.'<hr>';
                } 
            }

            $resp['estado_documento'] = $estado_envio_sunat;

            echo json_encode($resp);
            exit();
        }
    }

    public function guardarCondicionPagoAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            //aquí se debería verificar los permisos

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = empty($datapost['idcontribuyente'])?0:$datapost['idcontribuyente'];
            $id_tipodoc_electronico = empty($datapost['tipo_doc'])?'':$datapost['tipo_doc'];
            $serie_comprobante = empty($datapost['serie_doc'])?'':$datapost['serie_doc'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
            $monto_a_pagar = empty($datapost['monto_pagado'])?0:floatval($datapost['monto_pagado']);
            $fecha_proximo_pago = empty($datapost['fecha_proximo_pago'])?'':$datapost['fecha_proximo_pago'];
            $id_condicionpago = !isset($datapost['condicionpago_abono'])?0:intval($datapost['condicionpago_abono']) + 0;
            $numero_operacion = !isset($datapost['txt_numero_operacion'])?0:intval($datapost['txt_numero_operacion']) + 0;
            $txt_observacion_abono = !isset($datapost['txt_observacion_abono'])?'':$datapost['txt_observacion_abono'];
            $confirmacion = empty($datapost['confirmacion'])?'no':$datapost['confirmacion'];
            
            if($id_tipodoc_electronico == '77') {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => '77', 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
            } else {
                $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            }

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $monto_adeudado = $documento->monto_adeudado;
            if($monto_adeudado <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Actualmente no existe una deuda pendiente para este documento!';
                echo json_encode($resp);
                exit();
            }

            $simbolo_moneda = 'S/ ';
            if($documento->id_codigomoneda == 'USD') {
                $simbolo_moneda = 'USD $ ';
            }

            //monto_adeudado - monto_a_pagar < 0 : mostrar vuelto
            //monto_adeudado - monto_a_pagar == 0 : pagado
            //monto_adeudado - monto_a_pagar > 0 : seleccionar nueva fecha
            $vuelto = 0;
            $dinero_entregado = $monto_a_pagar;
            $log_condicion_pago = '';
            if(($monto_adeudado - $monto_a_pagar) < 0) { //mostrar vuelto
                $monto_a_pagar = $monto_adeudado;
                $vuelto = abs($monto_adeudado - $monto_a_pagar);
                $resp['mensaje'] = '¿Estás seguro que deseas anular la deuda total del comprobante electrónico?';
                $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), anuló la deuda todal del comprobante el día: '.date('d-m-Y / H:i A').'.';
            } else if (($monto_adeudado - $monto_a_pagar) == 0) { //pagado
                $resp['mensaje'] = '¿Estás seguro que deseas anular la deuda total del comprobante electrónico?';
                $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), anuló la deuda todal del comprobante el día: '.date('d-m-Y / H:i A').'.';
            } else if (($monto_adeudado - $monto_a_pagar) > 0) { //seleccionar nueva fecha
                $array_fecha_prox_pago = explode('/',!isset($fecha_proximo_pago)?date('d/m/Y'):$fecha_proximo_pago);
                $fecha_prox_pago = $array_fecha_prox_pago[2].'-'.$array_fecha_prox_pago[1].'-'.$array_fecha_prox_pago[0];
                if (!(DateTime::createFromFormat('Y-m-d', $fecha_prox_pago) !== FALSE)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La Fecha Seleccionada para el próximo pago no es válida!';
                    echo json_encode($resp);
                    exit();
                }

                $herramientas = new HerramientasController;
                $resp_fecha_2 = $herramientas->comparar_fechas($fecha_prox_pago, date('Y-m-d'));
				if($resp_fecha_2['diferencia_primera_segunda'] <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La fecha de pago del monto adeudado debe ser mayor a la fecha actual, por favor verifique!';
					echo json_encode($resp);
                    exit();
				}
                
                $resp['mensaje'] = 'Quedará una deuda pendiente de: '.$simbolo_moneda.($monto_adeudado - $monto_a_pagar).', la cuál debe ser pagada el día '.date("d-m-Y", strtotime($fecha_prox_pago)).' ¿Deseas continuar?';
                $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), agregó un pago de: '.$simbolo_moneda.$monto_a_pagar.', quedando una nueva deuda de: '.$simbolo_moneda.($monto_adeudado - $monto_a_pagar).'. Se registró esta operación el día: '.date('d-m-Y / H:i A').'.';
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Uno de los montos enviados no es correcto!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
				echo json_encode($resp);
				exit();
            }
            
            $this->db->begin();

            $monto_abono = 0;
            if(($monto_adeudado - $monto_a_pagar) < 0) { //mostrar vuelto
                $documento->monto_adeudado = 0;
                //$documento->id_condicionpago = null;
                $documento->log_condicion_pago = $log_condicion_pago;
                $monto_abono = $monto_adeudado;
            } else if (($monto_adeudado - $monto_a_pagar) == 0) {
                $documento->monto_adeudado = 0;
                //$documento->id_condicionpago = null;
                $documento->log_condicion_pago = $log_condicion_pago;
                $monto_abono = $monto_adeudado;
            } else if (($monto_adeudado - $monto_a_pagar) > 0) { //seleccionar nueva fecha
                $documento->monto_adeudado = round($monto_adeudado - $monto_a_pagar, 2);
                $documento->log_condicion_pago = $log_condicion_pago;
                $documento->fecha_pagopendiente = date("Y-m-d", strtotime($fecha_prox_pago));
                $monto_abono = $monto_a_pagar;
            }
            
            try {
                if(!$documento->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Error al guardar el documento electrónico: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $idcuenta_banco_deposito = null;
		    $fecha_deposito_transferencia = null;
            if($monto_abono > 0) {
                $monto_cobrado = new MontoCobrado();

                if($id_condicionpago > 0) {
                    $condiciondepago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", "bind" => array('id_contribuyente' => $usuario->id_contribuyente, 'id_condicionpago' => $id_condicionpago)));
                    if(!$condiciondepago) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La condición de pago seleccionada no es válida!';
                        return $resp;
                    }

                    if($condiciondepago->tipo == 'transferencia') {
                        if(intval($datapost['cuenta_banco_deposito']) > 0) {
                            $cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($datapost['cuenta_banco_deposito']))));
                            if(!$cuenta_banco_transferencia) {
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'La cuenta de banco de transferencia no existe, debes seleccionar una cuenta de banco en donde se ha depositado el dinero!!';
                                return $resp;
                            }
        
                            $idcuenta_banco_deposito = intval($datapost['cuenta_banco_deposito']);
                        }
        
                        $array_fecha_deposito = explode('/',!isset($datapost['fecha_deposito'])?date('d/m/Y'):$datapost['fecha_deposito']);
                        $fecha_deposito_transferencia = $array_fecha_deposito[2].'-'.$array_fecha_deposito[1].'-'.$array_fecha_deposito[0];
                        if (!(DateTime::createFromFormat('Y-m-d', $fecha_deposito_transferencia) !== FALSE)) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
                            return $resp;
                        }
                    }
                }
                
                $monto_cobrado->id_contribuyente = $id_contribuyente;
                $monto_cobrado->id_tipodoc_electronico = $id_tipodoc_electronico;
                $monto_cobrado->serie_comprobante = $serie_comprobante;
                $monto_cobrado->numero_comprobante = $numero_comprobante;
                $monto_cobrado->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
                $monto_cobrado->id_vendedor = $usuario->idusuario;
                $monto_cobrado->id_sucursal = $documento->id_sucursal;
                $monto_cobrado->id_codigomoneda = $documento->id_codigomoneda;
                $monto_cobrado->detalle = $txt_observacion_abono;
                $monto_cobrado->fechadeposito = $fecha_deposito_transferencia;
                $monto_cobrado->idbanco = $idcuenta_banco_deposito;

                if($id_condicionpago > 0) {
                    $monto_cobrado->id_condicionpago = $id_condicionpago;
                    $monto_cobrado->cpago_nrooperacion = $numero_operacion;
                } else {
                    $monto_cobrado->id_condicionpago = null;
                    $monto_cobrado->cpago_nrooperacion = null;
                }
                $monto_cobrado->fecha_registro = date("Y-m-d H:i:s");
                $monto_cobrado->estado = 'activo';
                $monto_cobrado->total = $monto_abono;

                try {
                    if(!$monto_cobrado->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($monto_cobrado->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = $msg;
                        echo json_encode($resp);
                        exit();
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Error al guardar el monto cobrado: '.$e->getMessage();
                    echo json_encode($resp);
                    exit();
                }
            }

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'Hemos guardado correctamente los datos!';
            $resp['id_montocobrado'] = ($monto_cobrado)?$monto_cobrado->id_montocobrado:0;
            echo json_encode($resp);
            exit();
        }
    }

    public function modificarDocelectronicoAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            /*
            if($usuario->id_rol == 4) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permitido crear estas operaciones, solo un administrador puede hacerlo!';
                echo json_encode($resp);
                exit();
			}
            */
			
            $herramientas = new HerramientasController;
			$id_contribuyente = $usuario->id_contribuyente;

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            $id_contribuyente = $contribuyente->id_contribuyente; //empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
            $accion = empty($datapost['accion'])?'':$datapost['accion'];
            $confirmacion = empty($datapost['confirmacion'])?'no':$datapost['confirmacion'];

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => 'produccion')));
            
            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos los datos del receptor del documento electrónico!';
                echo json_encode($resp);
                exit();
			}
			
            if($accion == 'actualizar_ticket') {
                if($documento->id_tipodoc_electronico == '01') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Las Facturas Electrónicas no se manejan con ticket!';
                    echo json_encode($resp);
                    exit();
                }

                $num_ticket = empty($datapost['num_ticket'])?'':$datapost['num_ticket'];
                if(empty($num_ticket)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Debes ingresar un número de ticket!';
                    echo json_encode($resp);
                    exit();
                }

                if($documento->estado_envio_sunat != 'ticket') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Al parecer no ha quedado pendiente !';
                    echo json_encode($resp);
                    exit();
                }
                
                $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->rb_id_contribuyente, 'codigo' => $documento->rb_codigo, 'serie' => $documento->rb_serie, 'secuencia' => $documento->rb_secuencia, 'tipo_envio_sunat' => $documento->rb_tipo_envio_sunat)));

                if(!$resumen) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se encuentra un resumen válido para el comprobante seleccionado!';
                    echo json_encode($resp);
                    exit();
                }

                if($confirmacion != 'si') {
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'ok';
                    $resp['mensaje'] = '¿Estas Seguro que Deseas Actualizar el Número de Ticket?';
                    echo json_encode($resp);
                    exit();
                }

                $resumen->numero_ticket = $num_ticket;

                try {
                    if(!$resumen->save()) {
                        $msg = '';
                        foreach ($resumen->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
        
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = $msg;
                        return $resp;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Error al guardar el número de ticket: '.$e->getMessage();
                    echo json_encode($resp);
                    exit();
                }
    
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'OK';
                $resp['mensaje'] = 'Se guardó correctamente el número de ticket';
                echo json_encode($resp);
                exit();
            } else if ($accion == 'resetear') {

                if($confirmacion != 'si') {
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'ok';
                    $resp['mensaje'] = '¿Estás Seguro de Resetear el Documento?';
                    echo json_encode($resp);
                    exit();
                }

                //aquí validar si se trata de una boleta, si es una boleta entonces pedir que se apruebe manualmente, ya que no se debe resetear
                $documento->estado_envio_sunat = 'pendiente';
                $documento->fecha_envio_sunat = null;
                $documento->hash_cpe = null;
                $documento->hash_cdr = null;
                $documento->cod_sunat = null;
                $documento->msje_sunat = null;
                $documento->ruta_xml = null;
                $documento->name_xml = null;
                $documento->name_xml_zip = null;
                $documento->name_cdr = null;
                $documento->name_cdr_zip = null;
                $documento->rb_id_contribuyente = null;
                $documento->rb_codigo = null;
                $documento->rb_serie = null;
                $documento->rb_secuencia = null;
                $documento->rb_tipo_envio_sunat = null;

            } else if ($accion == 'aprobar_manualmente') {

                if($cliente->num_doc == '00000000') {
                    $data_consulta['id_doc_cliente'] = '';
                    $data_consulta['num_doc_cliente'] = '';
                } else {
                    $data_consulta['id_doc_cliente'] = $cliente->id_tipodocidentidad;
                    $data_consulta['num_doc_cliente'] = $cliente->num_doc;
                }

                $data_consulta['num_ruc'] = $contribuyente->ruc;
                $data_consulta['id_tipo_doc_electronico'] = $id_tipodoc_electronico;
                $data_consulta['documento_serie'] = $serie_comprobante;
                $data_consulta['documento_numero'] = $numero_comprobante;
                $data_consulta['documento_fecha'] = date("d/m/Y", strtotime($documento->fecha_comprobante));
                $data_consulta['documento_total'] = floatval($documento->total) + 0;
                
                $resp_api_consulta = $herramientas->get_status_doc_sunat($data_consulta);
                
                $resp_api_consulta = @json_decode($resp_api_consulta);
                $resp_api_consulta = @json_decode($resp_api_consulta);
                
                if(empty($resp_api_consulta) || !isset($resp_api_consulta->rpta) || $resp_api_consulta->rpta == 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['resp_api'] = $resp_api_consulta;
                    $resp['mensaje'] = 'No podemos verificar es estado del comprobante en SUNAT, por tanto no se puede aprobar manualmente!!';
                    echo json_encode($resp);
                    exit();
                }

                if($resp_api_consulta->data->estadoCp == '1') {
                    
                } elseif ($resp_api_consulta->data->estadoCp == '2') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El Comprobante se encuentra en estado ANULADO, por tanto no se debe aprobar manualmente. Recomendamos Resetear el comprobante e intentar volver a enviar, una vez realizado el proceso, el sistema detectará la anulación y procederá a cambiar el estado del comprobante a ANULADO.';
                    echo json_encode($resp);
                    exit();
                } else {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El comprobante está PENDIENTE EN SUNAT, no podemos proceder con una APROBACIÓN MANUAL, debe usted reiniciar el comprobante y volver a enviar.';
                    echo json_encode($resp);
                    exit();
                }

                if($confirmacion != 'si') {
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'ok';
                    $resp['mensaje'] = '¿Estás Seguro de Aprobar Manualmente el Documento?';
                    echo json_encode($resp);
                    exit();
                }
                
                $documento->estado_envio_sunat = 'aceptado';
                $documento->msje_sunat = '-';
                $documento->name_cdr = '-';
                $documento->name_cdr_zip = '-';
                
                if($documento->id_tipodoc_electronico == '03') {
                    $documento->rb_id_contribuyente = $documento->id_contribuyente;
                    $documento->rb_codigo = 'RC';
                    $documento->rb_serie = '-';
                    $documento->rb_secuencia = 1;
                    $documento->rb_tipo_envio_sunat = $documento->tipo_envio_sunat;
                }
				
            } elseif ($accion == 'recover_cdr'){
                if($contribuyente->tipo_envio_sunat != 'produccion') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La recuperación del CDR es válida solo en producción!';
                    echo json_encode($resp);
                    exit();
                }

                if(empty($documento->ruta_xml)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se encuentra en la base de datos la ruta del xml, primero debes generar el xml firmado!';
                    echo json_encode($resp);
                    exit();
                }

                if($confirmacion != 'si') {
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'ok';
                    $resp['mensaje'] = '¿Estás seguro de que deseas Actualizar el Documento Electrónico?';
                    echo json_encode($resp);
                    exit();
                }

                $docelectronico = new DocumentoelectronicoController;
                $data_recover = array();
                $data_recover['ruc'] = $contribuyente->ruc;
                $data_recover['emisor']['ruc'] = $contribuyente->ruc;
                $data_recover['ruc_proveedor'] = $this->ruc_proveedor;
                $data_recover['usuario_sol'] = $contribuyente->usuario_sol;
                $data_recover['pass_sol'] = $contribuyente->clave_sol;
                $data_recover['serie_comprobante'] = $serie_comprobante;
                $data_recover['numero_comprobante'] = $numero_comprobante;
                $data_recover['tipo_comprobante'] = $id_tipodoc_electronico;
                $data_recover['tipo_certificado'] = $contribuyente->tipo_certificado;
                $data_recover['tipo_proceso'] = 'produccion';
                $resp_secret_data = $docelectronico->get_secret_data($contribuyente->id_contribuyente);
                $data_recover['secret_data'] =  $resp_secret_data['secret_data'];
                
                $herramientas = new HerramientasController;
                $ruta = 'https://facturalahoy.com/api/recovercdr';
                $resp_api_ws = $herramientas->envio_api_sunat($data_recover, $ruta);
                $resp['api'] = $resp_api_ws;
                $respuesta_cdr = json_decode($resp_api_ws);
                if($respuesta_cdr->respuesta == 'ok') {
                    if($respuesta_cdr->cod_sunat == '0') {
                        $ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/'.'facturas_boletas/';
                        $ruta_dir_xml = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
    
                        if (!file_exists($ruta_base_cpe_cdr)) {
                            mkdir($ruta_base_cpe_cdr, 0777, true);
                        }
                        
                        //Guardamos el archivo zip del cdr
                        $saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$respuesta_cdr->name_file_zip_cdr, base64_decode($respuesta_cdr->file_cdr_zip));
                        if (($saved_file_cdr_zip === false) || ($saved_file_cdr_zip == -1)) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['error_code'] = 'envio_sunat';
                            $resp['mensaje'] = 'Existe el CDR pero no se logró guardar en el servidor local!';
                            return $resp;
                        }

                        $documento->estado_envio_sunat = 'aceptado';
                        $documento->cod_sunat = !isset($respuesta_cdr->cod_sunat)?'':$respuesta_cdr->cod_sunat;
                        $documento->msje_sunat = !isset($respuesta_cdr->mensaje)?'':$respuesta_cdr->mensaje;
                        $documento->hash_cdr = !isset($respuesta_cdr->hash_cdr)?'':$respuesta_cdr->hash_cdr;
                        $documento->name_cdr = !isset($respuesta_cdr->name_file_xml_cdr)?'':$respuesta_cdr->name_file_xml_cdr;
                        $documento->name_cdr_zip = !isset($respuesta_cdr->name_file_zip_cdr)?'':$respuesta_cdr->name_file_zip_cdr;
                        
                        try {
                            if(!$documento->save()) {
                                $msg = '';
                                foreach ($documento->getMessages() as $message) {
                                    $msg = $msg.$message."</br>\n";
                                }
                
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = $msg;
                                return $resp;
                            }
                        } catch (Exception $e) {
                            $this->saveLogger($e);
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] =  $e->getMessage();
                            echo json_encode($resp);
                            exit();
                        }
                        
            
                        $resp['respuesta'] = 'ok';
                        $resp['titulo'] = 'Proceso Correcto';
                        $resp['mensaje'] = 'se ha recuperado el CDR!';
                        echo json_encode($resp);
                        exit();
                    }  

                } else {
                    echo $resp_api_ws;
                    exit();
                }
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se Reconoce la Acción!';
                echo json_encode($resp);
                exit();
            }

            try {
                if(!$documento->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
    
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada 006: ',  $e->getMessage(), "\n";
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Proceso Correcto';
            $resp['mensaje'] = 'Se ha modificado correctamente el documento!';
            echo json_encode($resp);
            exit();
        }
    }

    public function saveConfigModalidadPagoAction() {
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
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'Debes Iniciar Sessión.';
                echo json_encode($resp);
                exit();
            }

            
            if($usuario->id_rol == 3 || $usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 5) {
                
			} else {
			    $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permitido hacer este cambio, solo un administrador puede hacerlo!';
                echo json_encode($resp);
                exit();
			}

            $informar_condpago_sunat = !isset($datapost['informar_condpago_sunat'])?'no':($datapost['informar_condpago_sunat'] == 'si'?'si':'no');
            $opt_mostrar_modal_aviso = !isset($datapost['opt_mostrar_modal_aviso'])?'si':($datapost['opt_mostrar_modal_aviso'] == 'on'?'no':'si');

            $confirmacion = isset($datapost['confirmacion'])?$datapost['confirmacion']:'no';
            if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'ok';
                if($informar_condpago_sunat == 'si') {
                    $resp['mensaje'] = '¿Realmente Deseas Informar a SUNAT sobre tus ventas al Crédito?';
                } else {
                    $resp['mensaje'] = 'Todas tus ventas serán informadas como pagadas al contado... ¿Estás de Acuerdo?';
                }
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
            }

            $contribuyente->informar_condpago_sunat = $informar_condpago_sunat;
            $contribuyente->mostrar_aviso = $opt_mostrar_modal_aviso;
            
            try {
                if(!$contribuyente->save()) {
                    $msg = '';
                    foreach ($contribuyente->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Error al guardar la configuración: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'ok';
            $resp['mensaje'] = 'Se ha guardado su configuración correctamente!';
            echo json_encode($resp);
            exit();
        }
    }
}
?>