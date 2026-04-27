<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportesController extends ControllerBase
{ 
	public function indexAction() {
		
		$this->response->redirect('reportes/libro_eventas');
	}

	public function libroEcomprasAction() {
		$this->setTitle('Libro Electrónico de Compras');
        $this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
			
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
			->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
			->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
			->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
			->addJs($this->baseUri . "public/js/libro_ecompras.js?i=".rand());

		$fecha_actual = date('Y-m-d');
		$nuevafecha = strtotime('-12 month', strtotime($fecha_actual));
		$nuevafecha = date ('Y-m-d', $nuevafecha);
		$start    = new DateTime($nuevafecha);
		$start->modify('first day of this month');
		$end      = new DateTime(date('Y-m-d', strtotime('today')));
		$end->modify('first day of next month');
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($start, $interval, $end);
		$fecha_inicio_actual = date('Y-m-d',strtotime('first day of this month',strtotime('today')));

		setlocale(LC_TIME, 'spanish');

		$options_select = "";
		foreach ($period as $dt) {
			$item_periodo = $dt->format("Y-m-d");
			$fecha_inicio = date('Y-m-d',strtotime('first day of this month',strtotime($item_periodo)));
			$anio = date('Y',strtotime('last day of this month',strtotime($item_periodo)));
			$mes = date('m',strtotime('last day of this month',strtotime($item_periodo)));
			//$nombre_mes = ucwords(strftime("%B",mktime(0, 0, 0, $mes, 1, $anio)));

			$nombre_mes = ucwords((new IntlDateFormatter(
				'es_ES', 
				IntlDateFormatter::FULL, 
				IntlDateFormatter::NONE,
				'Europe/Madrid',
				IntlDateFormatter::GREGORIAN,
				'MMMM'
			))->format(mktime(0, 0, 0, $mes, 1, $anio)));

			if($fecha_inicio == $fecha_inicio_actual) {
                $options_select = $options_select.'<option selected="true" value="'.$fecha_inicio.'">'.$nombre_mes.' del '.$anio.'</option>';
            } else {
                $options_select = $options_select.'<option value="'.$fecha_inicio.'">'.$nombre_mes.' del '.$anio.'</option>';
            }
		}
		
		$this->view->options_select = $options_select;
		
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

		$this->view->regimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_contabilidad') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_contabilidad') != 'permitir') {
					$this->response->redirect('dashboard');
				}
			}
		}
	}

	public function libroEventasAction() {
		$this->setTitle('Libro Electrónico de Ventas');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");

		$this->assets
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
			
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
			->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
			->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
			->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/libro_eventas.js?i=".rand());
			
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

		$this->view->regimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_contabilidad') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_contabilidad') != 'permitir') {
					$this->response->redirect('dashboard');
				}
			}
		}
	}

	public function reporteDetalladoAction() {
		$this->setTitle('Reporte detallado');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
		
		$this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
			->addJs($this->baseUri . "public/js/reporte_detallado.js?j=".rand());

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
		
		$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		
		$this->view->lista_sucursales = $lista_sucursales;
		$this->view->lista_usuarios = $lista_usuarios;

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_verreportes_reportes', 'detalle_ventas') == false) {
					$this->response->redirect('dashboard');
				}
			}
		}
	}

	public function reporteProductosvendidosAction() {
		$this->setTitle('Reporte de Productos');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
		
		$this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
			->addJs($this->baseUri . "public/js/reportes/reporte_productosvendidos.js?j=".rand());

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
		
		$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		
		$this->view->lista_sucursales = $lista_sucursales;
		$this->view->lista_usuarios = $lista_usuarios;

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_verreportes_reportes', 'productos_mas_vendidos') == false) {
					$this->response->redirect('dashboard');
				}
			}
		}
	}

	public function topVendedoresAction() {
		$this->setTitle('Top Vendedores');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
		
		$this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
			->addJs($this->baseUri . "public/js/reportes/top_vendedores.js?j=".rand());

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

			$gestion_usuarios = new GestionuserController;
			if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
					if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes', 'top_vendedores') == false) {
						$this->response->redirect('dashboard');
					}
				}
			}

			$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			
			$this->view->lista_sucursales = $lista_sucursales;
			$this->view->lista_usuarios = $lista_usuarios;

		
	}

	public function topClientesAction() {
		$this->setTitle('Top Clientes');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
		
		$this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
			->addJs($this->baseUri . "public/js/reportes/top_clientes.js?j=".rand());

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

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes', 'top_clientes') == false) {
					$this->response->redirect('dashboard');
				}
			}
		}

		$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

		//VERIFICANDO PERMISOS DEL USUARIO!
		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
					if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
						$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal: ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $usuario->idsucursal)));
					}

					if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
						$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal: ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $usuario->idsucursal)));
						$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idusuario' => $usuario->idusuario)));
					}
				}
			}
		}
		
		$this->view->lista_sucursales = $lista_sucursales;
		$this->view->lista_usuarios = $lista_usuarios;
	}

	public function getReporteproductosvendidosAction() {
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
			
			$datapost = $this->request->getPost();
			$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
			$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
			$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
			$id_cod_moneda = empty($datapost['tipos_monedas'])?'PEN':$datapost['tipos_monedas'];
			$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
			$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);

			$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);

			$tipos_validos_monedas = array('PEN', 'USD');
			$tipos_comprobantes_validos = array('03', '01', '07', '08', '77');
			$estados_enviosunat_validos = array('aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado');
			
			if(!in_array($id_cod_moneda, $tipos_validos_monedas)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error Código Moneda';
				$resp['mensaje'] = 'El código de Moneda Seleccionado es Inválido!';
				echo json_encode($resp);
				exit();
			}

			$tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			$estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
			if(count($ids_vendedores) > 0) {
				foreach($ids_vendedores as $id_vendedor) {
					$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $usuario->id_contribuyente)));
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
			
			$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$sql_ids_vendedores_c1 = ' ';
			$sql_ids_vendedores_c2 = ' ';
			$sql_ids_vendedores_c3 = ' ';
			if(count($ids_vendedores) > 0) { 
				$sql_ids_vendedores_c1 = " and doc_electronico.id_vendedor in (".implode(",", $ids_vendedores).") "; 
				$sql_ids_vendedores_c2 = " and doc_electronico.id_vendedor in (".implode(",", $ids_vendedores).") "; 
				$sql_ids_vendedores_c3 = " and doc_no_oficial.id_vendedor in (".implode(",", $ids_vendedores).") "; 
			}

			$sql_ids_sucursales_c1 = ' ';
			$sql_ids_sucursales_c2 = ' ';
			$sql_ids_sucursales_c3 = ' ';
			$sql_ids_sucursales_c4 = ' ';
			if(count($ids_sucursales) > 0) { 
				$sql_ids_sucursales_c1 = " and doc_electronico.id_sucursal in (".implode(",", $ids_sucursales).") "; 
				$sql_ids_sucursales_c2 = " and doc_electronico.id_sucursal in (".implode(",", $ids_sucursales).") "; 
				$sql_ids_sucursales_c3 = " and doc_no_oficial.id_sucursal in (".implode(",", $ids_sucursales).") "; 
				$sql_ids_sucursales_c4 = " and producto.idsucursal in (".implode(",", $ids_sucursales).") "; 
			} else {

			}

			$sql_cod_moneda_c1 = " and doc_electronico.id_codigomoneda = '".$id_cod_moneda."' ";
			$sql_cod_moneda_c2 = " and doc_electronico.id_codigomoneda = '".$id_cod_moneda."' ";
			$sql_cod_moneda_c3 = " and doc_no_oficial.id_codigomoneda = '".$id_cod_moneda."' ";
			
			$sql_estados_envio_c1 = ' ';
			$sql_estados_envio_c2 = ' ';
			$sql_estados_envio_c3 = ' ';
			if(count($estados_envio_sunat) > 0) { 
				$sql_estados_envio_c1 = " and doc_electronico.estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') "; 
				$sql_estados_envio_c2 = " and doc_electronico.estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') "; 

				if(in_array('aceptado', $estados_envio_sunat)) {
					$sql_estados_envio_c3 = " and doc_no_oficial.estado_documento = 'activo' "; 
				} else if (in_array('anulado', $estados_envio_sunat)) {
					$sql_estados_envio_c3 = " and doc_no_oficial.estado_documento = 'inactivo' "; 
				} else {
					$sql_estados_envio_c3 = " "; 
				}
				
			} else {

			}

			$sql_restriccion_fecha_c1 = " and doc_electronico.fecha_comprobante >= CAST('".$fecha_inicio."' AS DATE) and doc_electronico.fecha_comprobante <= CAST('".$fecha_fin."' AS DATE) ";
			$sql_restriccion_fecha_c2 = " and doc_electronico.fecha_comprobante >= CAST('".$fecha_inicio."' AS DATE) and doc_electronico.fecha_comprobante <= CAST('".$fecha_fin."' AS DATE)  ";
			$sql_restriccion_fecha_c3 = " and doc_no_oficial.fecha_comprobante >= CAST('".$fecha_inicio."' AS DATE) and doc_no_oficial.fecha_comprobante <= CAST('".$fecha_fin."' AS DATE)  ";

			/*
			CONSULTA ORIGINAL QUE AYUDARÁ PARA DAR SOPORTE Y ENTENDER LA EXTRACCIÓN DE LA DATA:

			SELECT producto.idproducto, producto.nombre, productos_vendidos.id_producto, productos_vendidos.id_unidad_medida, productos_vendidos.cantidad, productos_vendidos.igv, productos_vendidos.subtotal, productos_vendidos.total FROM producto LEFT JOIN 

				(SELECT id_producto, id_unidad_medida, sum(cantidad) cantidad, sum(igv) igv, sum(subtotal) subtotal, sum(total) total FROM 
					( 
					Lista de productos que fueron vendidos en una boleta, factura y nota de débito
					CONSULTA C1
					SELECT detalle_doc.id_producto id_producto, detalle_doc.id_unidad_medida id_unidad_medida, sum(detalle_doc.cantidad) cantidad, sum(detalle_doc.igv) igv, sum(detalle_doc.importe) as subtotal, sum(detalle_doc.igv + detalle_doc.importe) total FROM detalle_doc INNER JOIN doc_electronico ON (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat)  where detalle_doc.id_contribuyente = @id_contribuyente and detalle_doc.id_tipodoc_electronico in ('01', '03', '08') and detalle_doc.tipo_envio_sunat = 'prueba' and doc_electronico.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') GROUP BY detalle_doc.id_producto, detalle_doc.id_unidad_medida

					UNION

					Lista de productos que fueron devueltos en una nota de crédito, quizás sea necesario mejorar la consulta utilizado el motivo de la nota de crédito, porque puede que no sea una devolución total
					CONSULTA C2
					SELECT detalle_doc.id_producto id_producto, detalle_doc.id_unidad_medida id_unidad_medida, -1*sum(detalle_doc.cantidad) cantidad, -1*sum(detalle_doc.igv) igv, -1*sum(detalle_doc.importe) as subtotal, -1*sum(detalle_doc.igv + detalle_doc.importe) total FROM detalle_doc INNER JOIN doc_electronico ON (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat)  where detalle_doc.id_contribuyente = @id_contribuyente and detalle_doc.id_tipodoc_electronico = '07' and detalle_doc.tipo_envio_sunat = 'prueba' and doc_electronico.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') GROUP BY detalle_doc.id_producto, detalle_doc.id_unidad_medida

					UNION

					lista de productos que fueron vendidos en una nota de venta 
					CONSULTA C3
					SELECT detalle_docnooficial.id_producto id_producto, detalle_docnooficial.id_unidad_medida id_unidad_medida, sum(detalle_docnooficial.cantidad) cantidad, sum(detalle_docnooficial.igv) igv, sum(detalle_docnooficial.importe) as subtotal, sum(detalle_docnooficial.igv + detalle_docnooficial.importe) total from detalle_docnooficial INNER JOIN doc_no_oficial ON (detalle_docnooficial.id_contribuyente = doc_no_oficial.id_contribuyente and detalle_docnooficial.id_tipodocumento = doc_no_oficial.id_tipodocumento and detalle_docnooficial.numero_comprobante = doc_no_oficial.numero_comprobante) where detalle_docnooficial.id_contribuyente = @id_contribuyente and detalle_docnooficial.id_tipodocumento = '77' and doc_no_oficial.estado_documento = 'activo' GROUP BY detalle_docnooficial.id_producto, detalle_docnooficial.id_unidad_medida
					
					) tbl_top_productos GROUP BY id_producto, id_unidad_medida ORDER BY sum(total) DESC
				) productos_vendidos 

			ON producto.idproducto = productos_vendidos.id_producto where producto.id_contribuyente = @id_contribuyente ORDER BY productos_vendidos.total DESC
			*/

			$query = "
			SELECT producto.idproducto idproducto, producto.estado estado, producto.nombre nombre, productos_vendidos.id_producto id_producto, productos_vendidos.id_unidad_medida id_unidad_medida, productos_vendidos.cantidad cantidad, productos_vendidos.igv igv, productos_vendidos.subtotal subtotal, productos_vendidos.total total FROM producto LEFT JOIN 

				(SELECT id_producto, id_unidad_medida, sum(cantidad) cantidad, sum(igv) igv, sum(subtotal) subtotal, sum(total) total FROM 
					("; 

					if(count($tipos_comprobantes) > 0) {
						$query_c1 = '';
						$query_c2 = '';
						$query_c3 = '';

						if(in_array('01', $tipos_comprobantes) || in_array('03', $tipos_comprobantes) || in_array('08', $tipos_comprobantes)) {
							//se ha seleccionado una factura, boleta o nota de débito
							$tipos_comprobantes_c1 = array();
							if(in_array('01', $tipos_comprobantes)) {
								$tipos_comprobantes_c1[] = '01';
							}

							if(in_array('03', $tipos_comprobantes)) {
								$tipos_comprobantes_c1[] = '03';
							}

							if(in_array('08', $tipos_comprobantes)) {
								$tipos_comprobantes_c1[] = '08';
							}

							$sql_tipos_comprobantes_c1 = " ('".implode("','", $tipos_comprobantes_c1)."') "; 

							$query_c1 = "
							/*Lista de productos que fueron vendidos en una boleta, factura y nota de débito */
							/*CONSULTA C1*/
							SELECT detalle_doc.id_producto id_producto, detalle_doc.id_unidad_medida id_unidad_medida, sum(detalle_doc.cantidad) cantidad, sum(detalle_doc.igv) igv, sum(detalle_doc.importe) as subtotal, sum(detalle_doc.igv + detalle_doc.importe) total FROM detalle_doc INNER JOIN doc_electronico ON (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat)  where detalle_doc.id_contribuyente = ".$id_contribuyente." and detalle_doc.id_tipodoc_electronico in ".$sql_tipos_comprobantes_c1." and detalle_doc.tipo_envio_sunat = '".$tipo_envio_sunat."' ".$sql_estados_envio_c1.$sql_restriccion_fecha_c1.$sql_ids_vendedores_c1.$sql_ids_sucursales_c1.$sql_cod_moneda_c1." GROUP BY detalle_doc.id_producto, detalle_doc.id_unidad_medida
							
							";
						}

						if(in_array('07', $tipos_comprobantes)) {
							$query_c2 = "
							/*Lista de productos que fueron devueltos en una nota de crédito, quizás sea necesario mejorar la consulta utilizado el motivo de la nota de crédito, porque puede que no sea una devolución total*/
							/*CONSULTA C2*/
							SELECT detalle_doc.id_producto id_producto, detalle_doc.id_unidad_medida id_unidad_medida, -1*sum(detalle_doc.cantidad) cantidad, -1*sum(detalle_doc.igv) igv, -1*sum(detalle_doc.importe) as subtotal, -1*sum(detalle_doc.igv + detalle_doc.importe) total FROM detalle_doc INNER JOIN doc_electronico ON (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat)  where detalle_doc.id_contribuyente = ".$id_contribuyente." and detalle_doc.id_tipodoc_electronico = '07' and detalle_doc.tipo_envio_sunat = '".$tipo_envio_sunat."' ".$sql_estados_envio_c2.$sql_restriccion_fecha_c2.$sql_ids_vendedores_c2.$sql_ids_sucursales_c2.$sql_cod_moneda_c2." GROUP BY detalle_doc.id_producto, detalle_doc.id_unidad_medida
							
							";
						}

						if(in_array('77', $tipos_comprobantes)) {
							$query_c3 = "
							/*lista de productos que fueron vendidos en una nota de venta */
							/*CONSULTA C3*/
							SELECT detalle_docnooficial.id_producto id_producto, detalle_docnooficial.id_unidad_medida id_unidad_medida, sum(detalle_docnooficial.cantidad) cantidad, sum(detalle_docnooficial.igv) igv, sum(detalle_docnooficial.importe) as subtotal, sum(detalle_docnooficial.igv + detalle_docnooficial.importe) total from detalle_docnooficial INNER JOIN doc_no_oficial ON (detalle_docnooficial.id_contribuyente = doc_no_oficial.id_contribuyente and detalle_docnooficial.id_tipodocumento = doc_no_oficial.id_tipodocumento and detalle_docnooficial.numero_comprobante = doc_no_oficial.numero_comprobante) where detalle_docnooficial.id_contribuyente = ".$id_contribuyente." and detalle_docnooficial.modalidad = '".$tipo_envio_sunat."' and detalle_docnooficial.id_tipodocumento = '77' ".$sql_estados_envio_c3.$sql_restriccion_fecha_c3.$sql_ids_vendedores_c3.$sql_ids_sucursales_c3.$sql_cod_moneda_c3." GROUP BY detalle_docnooficial.id_producto, detalle_docnooficial.id_unidad_medida 
							";
						}

						if($query_c1 != '' && $query_c2 == '' && $query_c3 == '') {
							$query = $query.$query_c1;
						}

						if($query_c1 == '' && $query_c2 != '' && $query_c3 == '') {
							$query = $query.$query_c2;
						}

						if($query_c1 == '' && $query_c2 == '' && $query_c3 != '') {
							$query = $query.$query_c3;
						}

						if($query_c1 != '' && $query_c2 != '' && $query_c3 == '') {
							$query = $query.$query_c1.' UNION ALL '.$query_c2;
						}

						if($query_c1 != '' && $query_c2 == '' && $query_c3 != '') {
							$query = $query.$query_c1.' UNION ALL '.$query_c3;
						}

						if($query_c1 == '' && $query_c2 != '' && $query_c3 != '') {
							$query = $query.$query_c1.' UNION ALL '.$query_c2.' UNION ALL '.$query_c3;
						}

					} else {
						$query = $query."
						/*Lista de productos que fueron vendidos en una boleta, factura y nota de débito */
						/*CONSULTA C1*/
						SELECT detalle_doc.id_producto id_producto, detalle_doc.id_unidad_medida id_unidad_medida, sum(detalle_doc.cantidad) cantidad, sum(detalle_doc.igv) igv, sum(detalle_doc.importe) as subtotal, sum(detalle_doc.igv + detalle_doc.importe) total FROM detalle_doc INNER JOIN doc_electronico ON (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat)  where detalle_doc.id_contribuyente = ".$id_contribuyente." and detalle_doc.id_tipodoc_electronico in ('01', '03', '08') and detalle_doc.tipo_envio_sunat = '".$tipo_envio_sunat."' ".$sql_estados_envio_c1.$sql_restriccion_fecha_c1.$sql_ids_vendedores_c1.$sql_ids_sucursales_c1.$sql_cod_moneda_c1." GROUP BY detalle_doc.id_producto, detalle_doc.id_unidad_medida

						UNION ALL

						/*Lista de productos que fueron devueltos en una nota de crédito, quizás sea necesario mejorar la consulta utilizado el motivo de la nota de crédito, porque puede que no sea una devolución total*/
						/*CONSULTA C2*/
						SELECT detalle_doc.id_producto id_producto, detalle_doc.id_unidad_medida id_unidad_medida, -1*sum(detalle_doc.cantidad) cantidad, -1*sum(detalle_doc.igv) igv, -1*sum(detalle_doc.importe) as subtotal, -1*sum(detalle_doc.igv + detalle_doc.importe) total FROM detalle_doc INNER JOIN doc_electronico ON (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat)  where detalle_doc.id_contribuyente = ".$id_contribuyente." and detalle_doc.id_tipodoc_electronico = '07' and detalle_doc.tipo_envio_sunat = '".$tipo_envio_sunat."' ".$sql_estados_envio_c2.$sql_restriccion_fecha_c2.$sql_ids_vendedores_c2.$sql_ids_sucursales_c2.$sql_cod_moneda_c2." GROUP BY detalle_doc.id_producto, detalle_doc.id_unidad_medida

						UNION ALL

						/*lista de productos que fueron vendidos en una nota de venta */
						/*CONSULTA C3*/
						SELECT detalle_docnooficial.id_producto id_producto, detalle_docnooficial.id_unidad_medida id_unidad_medida, sum(detalle_docnooficial.cantidad) cantidad, sum(detalle_docnooficial.igv) igv, sum(detalle_docnooficial.importe) as subtotal, sum(detalle_docnooficial.igv + detalle_docnooficial.importe) total from detalle_docnooficial INNER JOIN doc_no_oficial ON (detalle_docnooficial.id_contribuyente = doc_no_oficial.id_contribuyente and detalle_docnooficial.id_tipodocumento = doc_no_oficial.id_tipodocumento and detalle_docnooficial.numero_comprobante = doc_no_oficial.numero_comprobante) where detalle_docnooficial.id_contribuyente = ".$id_contribuyente." and detalle_docnooficial.modalidad = '".$tipo_envio_sunat."' and detalle_docnooficial.id_tipodocumento = '77' ".$sql_estados_envio_c3.$sql_restriccion_fecha_c3.$sql_ids_vendedores_c3.$sql_ids_sucursales_c3.$sql_cod_moneda_c3." GROUP BY detalle_docnooficial.id_producto, detalle_docnooficial.id_unidad_medida
						";
					}
				
					
			$query = $query."
					) tbl_top_productos GROUP BY id_producto, id_unidad_medida ORDER BY sum(total) DESC
				) productos_vendidos 
			ON producto.idproducto = productos_vendidos.id_producto where producto.id_contribuyente = ".$id_contribuyente." ".$sql_ids_sucursales_c4." ORDER BY productos_vendidos.total DESC
			";
			
			$sentencia = $this->db->prepare($query);
			
			$lista = array();
			$suma_total = 0;
			$venta_total = 0;
			$costo_total = 0;
			$utilidad_total = 0;

			try {
				$sentencia->execute();
				while ($fila = $sentencia->fetch()) {
					$fila = (object)$fila;

					if(!empty($fila->id_producto)) {
						//Lista de todos los productos que si tienen ventas
						$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $fila->idproducto)));
						$stock_actual = isset($producto->stock)?$producto->stock:0;
						$save_kardex = Kardex::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $fila->idproducto), "order" => "id_kardex DESC"));
						$costo_promedio = $save_kardex?$save_kardex->costo_unitario_promedio:$producto->precio_compra;
						$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
						$nombre_unidad = $unidad_medida?$unidad_medida->nombre:'';
						$num_decimales = empty($contribuyente->num_decimales)?2:$contribuyente->num_decimales;
						$cantidad_vendida = empty($fila->cantidad)?0:round(floatval($fila->cantidad), 2);
						if($cantidad_vendida > 0) {
							$precio_venta_promedio = round($fila->total/$cantidad_vendida, 4);
						} else {
							$precio_venta_promedio = 0;
						}
						
						$costo_total_item = round($costo_promedio*$fila->cantidad, $num_decimales) + 0;
						$costo_total_producto = round($producto->precio_compra*$fila->cantidad, $num_decimales) + 0;
						$utilidad = round($fila->total - $costo_total_producto, $num_decimales) + 0;
						$nombre_cageoria = '';
						$categoria = Categoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $producto->id_categoria)));
						if($categoria){
							$nombre_cageoria = $categoria->nombre;
						}
						$lista[] = array(
							'idproducto' 				=> $fila->idproducto,
							'codigo' 					=> $producto->codigo,
							'nombre' 					=> $producto->nombre,
							'nombre_unidad' 			=> $nombre_unidad,
							'marca' 					=> $producto->marca,
							'nombre_cageoria' 			=> $nombre_cageoria,
							'stock_actual' 				=> $stock_actual + 0,
							'cantidad_vendida' 			=> $cantidad_vendida + 0,
							'id_cod_moneda' 			=> $id_cod_moneda,
							'precio_venta_promedio' 	=> $precio_venta_promedio + 0,
							'igv' 						=> $fila->igv + 0,
							'subtotal' 					=> $fila->subtotal + 0,
							'total' 					=> $fila->total + 0,
							'precio_compra' 			=> $producto->precio_compra + 0,
							'costo_total_item' 			=> $costo_total_producto + 0,
							'utilidad' 					=> $utilidad + 0
						);

						$suma_total = $suma_total + $fila->total;

						$venta_total = $venta_total + $fila->total;
						//$costo_total = $costo_total + $costo_total_item;
						$costo_total = $costo_total + $costo_total_producto;
						$utilidad_por_precio_compra = round($fila->total - $costo_total_producto, $num_decimales) + 0;
						$utilidad_total = $utilidad_total + $utilidad_por_precio_compra;
						
					} else {
						//lista de productos que no tienen ventas, aquí solo mostrar productos activos.
						if($fila->estado == 'activo') {
							$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $fila->idproducto)));
							$stock_actual = isset($producto->stock)?$producto->stock:0;
							//$save_kardex = Kardex::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $fila->idproducto), "order" => "id_kardex DESC"));
							//$costo_promedio = $save_kardex?$save_kardex->costo_unitario_promedio:$producto->precio_compra;
							$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
							$nombre_unidad = $unidad_medida?$unidad_medida->nombre:'';
							//$num_decimales = empty($contribuyente->num_decimales)?2:$contribuyente->num_decimales;
							//$cantidad_vendida = empty($fila->cantidad)?0:$fila->cantidad;
							/*if($cantidad_vendida > 0) {
								$precio_venta_promedio = round($fila->total/$cantidad_vendida, $num_decimales) + 0;
							} else {
								$precio_venta_promedio = 0;
							}*/
							//$utilidad = 0;
							$nombre_cageoria = '';
							$categoria = Categoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $producto->id_categoria)));
							if($categoria){
								$nombre_cageoria = $categoria->nombre;
							}
							
							$lista[] = array(
								'idproducto' 				=> $fila->idproducto,
								'codigo' 					=> $producto->codigo,
								'nombre' 					=> $producto->nombre,
								'nombre_unidad' 			=> $nombre_unidad,
								'marca' 					=> $producto->marca,
								'nombre_cageoria' 			=> $nombre_cageoria,
								'stock_actual' 				=> $stock_actual + 0,
								'cantidad_vendida' 			=> 0,
								'id_cod_moneda' 			=> $id_cod_moneda,
								'precio_venta_promedio' 	=> 0,
								'igv' 						=> 0,
								'subtotal' 					=> 0,
								'total' 					=> 0,
								'precio_compra' 			=> 0,
								'costo_total_item' 			=> 0,
								'utilidad' 					=> 0
							);
						}
					}
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $e->getMessage();
				echo json_encode($resp);
				exit();
			}

			if($id_cod_moneda == 'PEN') {
				$simbolo_moneda = 'S/.';
			} else {
				$simbolo_moneda = '$';
			}

			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			$resp['suma_total'] = $suma_total;
			$resp['venta_total'] = $simbolo_moneda.' '.number_format($venta_total, 3, '.', ',');
			$resp['costo_total'] = $simbolo_moneda.' '.number_format($costo_total, 3, '.', ',');
			$resp['utilidad_total'] = $simbolo_moneda.' '.number_format($utilidad_total, 3, '.', ',');
			echo json_encode($resp);
			exit();
		}
	}

	public function getFormatoImportacionGuiaremisionAction() {
		$this->view->disable();
        $request = $this->request;
		$datapost = $this->request->get();
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
		
		$datapost = (array)$datapost;
		$datapost['draw'] = 1;
		$datapost['order'][0]['column'] = 0;
		$datapost['order'][0]['dir'] = 'desc';
		$datapost['start'] = 0;
		$datapost['length'] = 1000000;
		$datapost['search']['value'] = '';
		$datapost['search']['regex'] = false;
		
		$resp_reporte = $this->get_reporte_consolidado_productos($datapost, $usuario);
		if($resp_reporte['respuesta'] == 'error') {
			echo json_encode($resp_reporte);
			exit();
		}
		
		$lista_items = $resp_reporte['json_data']['data'];
		
		$spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Plantilla Items Guía Remisión")
            ->setSubject("Plantilla Items Guía Remisión")
            ->setDescription(
                "Plantila de importación de items para crear guía de remisión electrónica"
            )
            ->setKeywords("facturalaya.com, reporte de ventas")
            ->setCategory("reportes");
            
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Plantilla Items Guía Remisión");
		$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID PRODUCTO')
            ->setCellValue('B1', 'CODIGO')
            ->setCellValue('C1', 'NOMBRE PRODUCTO')
            ->setCellValue('D1', 'ID UNIDAD')
            ->setCellValue('E1', 'CANTIDAD')
            ->setCellValue('F1', 'PESO')
            ->setCellValue('G1', 'BULTOS')
            ->setCellValue('H1', 'TIPO UNIDAD')
            ;
		
		
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getFont()->setBold(true);

		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');	

		$num_hoja = 0;

		$lista = array();
		$n = 1;
		foreach($lista_items as $item) {
			$n++;
			if($item['tipo_unidad'] == 'PRE') {
				$id_unidad_medida = $item['id_presentacion'];
			} else {
				$id_unidad_medida = $item['id_unidad_medida'];
			}

			$spreadsheet->setActiveSheetIndex($num_hoja)
				->setCellValueExplicit('A'.$n, $item['id_producto'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('B'.$n, $item['producto_codigo'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('C'.$n, $item['producto_nombre'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('D'.$n, $id_unidad_medida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('E'.$n, $item['cantidad_total'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('F'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('G'.$n, $item['cantidad_total'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('H'.$n, $item['tipo_unidad'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
			;
		}

		$spreadsheet->setActiveSheetIndex(0);
		
        $nombre_archivo = 'items_importancion_guia_remision';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function getFormatoCmAction() {
		$this->view->disable();
        $request = $this->request;
		$datapost = $this->request->get();
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

		$resp_detalle = $this->get_reportedetallado($usuario, $datapost);

		$spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Reporte C.M.")
            ->setSubject("Reporte C.M.")
            ->setDescription(
                "Reporte Creado Exclusivamente para el CIRCULO MILITAR DE SUPERVISORES TECNICOS Y SUB OFICIALES"
            )
            ->setKeywords("facturalaya.com, reporte de ventas")
            ->setCategory("reportes");
            
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Reporte C.M.");
		$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SEDE')
            ->setCellValue('B1', 'N° CP')
            ->setCellValue('C1', 'FECHA CP')
            ->setCellValue('D1', 'MONTO')
            ->setCellValue('E1', 'RAZON SOCIAL')
            ->setCellValue('F1', 'NUM.DOC.')
            ->setCellValue('G1', 'DETALLE')
            ->setCellValue('H1', 'OBSERVACIÓN')
            ;
		
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
        
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getFont()->setBold(true);

		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');
		$num_hoja = 0;

		$lista = array();
		$n = 1;
		foreach($resp_detalle['lista'] as $item) {
			if($item['item'] == 1) {
				$n++;
				$spreadsheet->setActiveSheetIndex($num_hoja)
					->setCellValueExplicit('A'.$n, $item['nombre_sucursal_text'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
					->setCellValueExplicit('B'.$n, $item['serie_documento'].'-'.$item['correlativo_documento'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
					->setCellValueExplicit('C'.$n, $item['fecha_comprobante'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
					->setCellValueExplicit('D'.$n, floatval($item['total_number']), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
					->setCellValueExplicit('E'.$n, $item['nombre_cliente_text'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
					->setCellValueExplicit('F'.$n, $item['numero_doc_cliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
					->setCellValueExplicit('G'.$n, $item['nombre_producto'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
					->setCellValueExplicit('H'.$n, $item['nota'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				;
			}
		}

		$spreadsheet->setActiveSheetIndex(0);
		
        $nombre_archivo = 'Reporte C.M.';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function getReportedetalladoAction() {
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

			$resp_reporte = $this->get_reportedetallado($usuario, $datapost);
			echo json_encode($resp_reporte);
			exit();
		}
	}

	public function getReporteGeneralVentasAction() {
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
			
			$resp_reporte = $this->get_reporte_general_ventas($usuario, $datapost);
			echo json_encode($resp_reporte);
			exit();
		}
	}

	public function get_reporte_general_ventas($usuario, $datapost) {
		$gestion_usuarios = new GestionuserController;
		$id_contribuyente = $usuario->id_contribuyente;
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			return $resp;
		}

		if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3) {
            $resp_permiso_costo_prod = true;
        } else {
            $resp_permiso_costo_prod = $gestion_usuarios->verificar_permisos($usuario, 'permisos_otros', 'opt_otros_costocompra');
        }

		$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
		$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
		$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
		$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
		$ids_cod_monedas = empty($datapost['tipos_monedas'])?array():explode(',', $datapost['tipos_monedas']);
		$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
		$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
		//$idproducto = !isset($datapost['select_producto'])?array(0):$datapost['select_producto'];

		$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
		$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);

		$tipos_validos_monedas = array('PEN', 'USD');
		$tipos_comprobantes_validos = array('03', '01', '07', '08', '77', '88');
		$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado');
		
		$ids_cod_monedas = array_filter($ids_cod_monedas);
		if(count($ids_cod_monedas) > 0) {
			foreach($ids_cod_monedas as $idcodmoneda) {
				if(!in_array($idcodmoneda, $tipos_validos_monedas)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Código Moneda';
					$resp['mensaje'] = 'El código de Moneda Seleccionado es Inválido!';
					return $resp;
				}
			}
		}

		$tipos_comprobantes = array_filter($tipos_comprobantes);
		if(count($tipos_comprobantes) > 0) {
			foreach($tipos_comprobantes as $id_tipo_doc) {
				if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

		$estados_envio_sunat = array_filter($estados_envio_sunat);
		if(count($estados_envio_sunat) > 0) {
			foreach($estados_envio_sunat as $tipo_env_sunat) {
				if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

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
		
		$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
		$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			return $resp;
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			return $resp;
		}

		$lista = array();

		$sql_reporte = "SELECT de.id_contribuyente, de.fecha_comprobante, de.id_sucursal, de.id_vendedor, de.id_condicionpago, de.idcliente, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.tipo_envio_sunat, de.estado_envio_sunat, de.cpago_nrooperacion, de.cpago_fechadeposito, de.cpago_idbanco, de.transporte_nro_placa, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_gratuitas, de.total_exportacion, de.total_icbper, de.total_descuento, de.sub_total, de.porcentaje_igv, de.total_igv, de.total_isc, de.total_otr_imp, de.total, de.nota, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, de.id_codigomoneda, de.dir_destino, de.id_ubigeo_destino, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.nro_otr_comprobante, de.array_docs_referencia FROM doc_electronico de where de.tipo_envio_sunat = :tipo_envio_sunat and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) and de.id_contribuyente =  :id_contribuyente ";

		if(count($ids_vendedores) > 0){ $sql_reporte = $sql_reporte." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $sql_reporte = $sql_reporte." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }
		if(count($ids_cod_monedas) > 0){ $sql_reporte = $sql_reporte." and de.id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
		if(count($tipos_comprobantes) > 0){ $sql_reporte = $sql_reporte." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; }
		if(count($estados_envio_sunat) > 0){ $sql_reporte = $sql_reporte." and de.estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') "; }

		try {
			$sentencia = $this->db->prepare($sql_reporte);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->execute();

			while ($fila = $sentencia->fetch()) {
				$fila = (object)$fila;
				if($fila->tipo_cambio == '') {
					setlocale(LC_MONETARY, 'es_PE');
				} else {
					setlocale(LC_MONETARY, 'es_US');
				}
				
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				$nombre_sucursal = '';
				$nombre_sucursal_text = '';
				if($sucursal) {
					$nombre_sucursal_text = $sucursal->nombre;
					$nombre_sucursal = '
					<div>'.$sucursal->nombre.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-office text-size-mini position-left"></i> '.$sucursal->direccion.'</div>
					';
				}

				$nombre_tipo_documento = '';
				if($fila->id_tipodoc_electronico == '03') {
					$nombre_tipo_documento = 'BOLETA';
				} elseif($fila->id_tipodoc_electronico == '01') {
					$nombre_tipo_documento = 'FACTURA';
				} elseif($fila->id_tipodoc_electronico == '07') {
					$nombre_tipo_documento = 'NOTA DE CRÉDITO';
				} elseif($fila->id_tipodoc_electronico == '08') {
					$nombre_tipo_documento = 'NOTA DE DÉBITO';
				} elseif($fila->id_tipodoc_electronico == '09') {
					$nombre_tipo_documento = 'GUÍA REMISIÓN';
				}

				$condicion_pago_tipo = 'CONTADO';
				$condicion_pago_nombre = 'Contado';
				if(!empty($fila->id_condicionpago)) {
					$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));
					if($condicion_pago) {
						$condicion_pago_tipo = strtoupper($condicion_pago->tipo);
						$condicion_pago_nombre = $condicion_pago->condicionpago;
					}
				}

				$nombre_cliente = '';
				$nombre_cliente_text = '';
				$direccion_cliente = '';
				$id_ubigeo_cliente = '';
				$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($cliente) {
					$tipo_doc_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $cliente->id_tipodocidentidad)));
					$nombre_cliente = '
					<div>'.$tipo_doc_identidad->nombre.': '.$cliente->num_doc.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.ucwords($cliente->razon_social).'</div>
					';
					$nombre_cliente_text = $cliente->razon_social;

					if(!empty($fila->dir_destino)) {
						$direccion_cliente = $fila->dir_destino;
					} else {
						$direccion_cliente = empty($cliente->direccion_fiscal)?'':$cliente->direccion_fiscal;
					}

					if(!empty($fila->id_ubigeo_destino)) {
						$id_ubigeo_cliente = $fila->id_ubigeo_destino;
					} else {
						$id_ubigeo_cliente = empty($cliente->id_cod_ubigeo)?'':$cliente->id_cod_ubigeo;
					}
				}

				$ubigeo_dir_cliente = '';
				if(!empty($id_ubigeo_cliente)) {
					$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_cliente)));
					if($ubigeo_cliente) {
						$ubigeo_dir_cliente =  $ubigeo_cliente->departamento.' - '.$ubigeo_cliente->provincia.' '.$ubigeo_cliente->distrito;
					}
				}

				$nombre_vendedor = '';
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($vendedor) {
					$nombre_vendedor = '
					<div>'.$vendedor->email.' - (ID: '.$vendedor->idusuario.')</div>
					<div class="text-primary-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.$vendedor->nombre.' '.$vendedor->apellido.'</div>
					';
				}
				
				$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
				$cpago_fechadeposito = '';
				$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
				$nombre_banco_deposito = '';

				if(intval($cpago_idbanco) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
					if($cuenta_banco_transferencia) {
						$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
					}
				}

				if(!empty($fila->cpago_fechadeposito)) {
					$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
				}
				
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $fila->tipo_envio_sunat)));
				

				if(count($abonos) > 0) {
					$lista_nrooperacion = array();
					$lista_fechadeposito = array();
					$lista_idbanco = array();
					$lista_nombre_banco = array();

					foreach($abonos as $abono) {
						if(!empty($abono->cpago_nrooperacion)) {
							$lista_nrooperacion[] = $abono->cpago_nrooperacion;
						}

						if(!empty($abono->fechadeposito)) {
							$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
						}
						
						if(intval($abono->idbanco) > 0 ) {
							$lista_idbanco[] = $abono->idbanco;
							$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
							if($cuenta_banco_transferencia) {
								$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
							}
						}
					}


					$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
					$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
					$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
					$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
				}

				$string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$fila->tipo_envio_sunat."||a4");
				$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$fila->tipo_envio_sunat."||ticket");

				$url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
				$url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

				$enlace_pdf = $url_a4;

				if($fila->estado_envio_sunat == 'anulado') {
					$fm = 0;
				} else if ($fila->estado_envio_sunat == 'rechazado') {
					$fm = 0;
				} else {
					$fm = 1;
				}

				$docs_relacionados = '';

				if (!empty($fila->array_docs_referencia)) {
					// Decodifica el JSON en un array asociativo
					$temp = json_decode($fila->array_docs_referencia, true); 

					// Verifica si la decodificación fue exitosa
					if (json_last_error() == JSON_ERROR_NONE && is_array($temp)) {
						$result = [];

						// Recorre cada elemento del array (cada objeto dentro del JSON)
						foreach ($temp as $doc) {
							// Verifica que cada objeto tiene las claves necesarias
							if (isset($doc['id_tipodoc_electronico'], $doc['serie_comprobante'], $doc['numero_comprobante'])) {
								// Concatena los valores del objeto y añade al resultado
								$result[] = implode('-', [
									$doc['id_tipodoc_electronico'], 
									$doc['serie_comprobante'], 
									$doc['numero_comprobante']
								]);
							}
						}

						// Une todos los elementos de resultado en una cadena, separados por punto y coma
						$docs_relacionados = implode('; ', $result);
					} else {
						// Si hay un error en la decodificación o $temp no es un array, manten $docs_relacionados como vacío
						$docs_relacionados = '';
					}
				}

				$anticipo_total_gravadas = 0;
				$anticipo_total_inafecta = 0;
				$anticipo_total_exoneradas = 0;
				$anticipo_total_gratuitas = 0;
				$anticipo_total_exportacion = 0;
				$anticipo_total_icbper = 0;
				$anticipo_sub_total = 0;
				$anticipo_impuesto_icbper = 0;
				$anticipo_total_igv = 0;
				$anticipo_total_isc = 0;
				$anticipo_total_otr_imp = 0;
				$anticipo_total = 0;
				if($fila->id_tipodoc_electronico == '01') {
					$anticipos = $this->get_anticipos(
						array(
							'id_contribuyente' 	=> $id_contribuyente, 
							'tipo_comprobante' 	=> $fila->id_tipodoc_electronico, 
							'serie_doc' 		=> $fila->serie_comprobante, 
							'numero_doc' 		=> $fila->numero_comprobante, 
							'tipo_envio_sunat' 	=> $tipo_envio_sunat
						)
					);

					$anticipo_total_gravadas 		= $anticipos['totales']['total_gravadas'];
					$anticipo_total_inafecta 		= $anticipos['totales']['total_inafecta'];
					$anticipo_total_exoneradas 		= $anticipos['totales']['total_exoneradas'];
					$anticipo_total_gratuitas 		= $anticipos['totales']['total_gratuitas'];
					$anticipo_total_exportacion 	= $anticipos['totales']['total_exportacion'];
					$anticipo_total_icbper 			= $anticipos['totales']['total_icbper'];
					$anticipo_sub_total 			= $anticipos['totales']['sub_total'];
					$anticipo_impuesto_icbper 		= $anticipos['totales']['impuesto_icbper'];
					$anticipo_total_igv 			= $anticipos['totales']['total_igv'];
					$anticipo_total_isc 			= $anticipos['totales']['total_isc'];
					$anticipo_total_otr_imp 		= $anticipos['totales']['total_otr_imp'];
					$anticipo_total 				= $anticipos['totales']['total'];
				}

				$cpe_total_exportacion 	= $fila->total_exportacion - $anticipo_total_exportacion;
				$cpe_total_gravadas 	= $fila->total_gravadas - $anticipo_total_gravadas;
				$cpe_total_exoneradas 	= $fila->total_exoneradas - $anticipo_total_exoneradas;
				$cpe_total_inafecta 	= $fila->total_inafecta - $anticipo_total_inafecta;
				$cpe_total_gratuitas 	= $fila->total_gratuitas - $anticipo_total_gratuitas;
				$cpe_total_icbper 		= $fila->total_icbper - $anticipo_total_icbper;
				$cpe_total_descuento 	= $fila->total_descuento;
				$cpe_sub_total 			= $fila->sub_total - $anticipo_sub_total;
				$cpe_porcentaje_igv 	= $fila->porcentaje_igv;
				$cpe_total_igv 			= $fila->total_igv - $anticipo_total_igv;
				$cpe_total_isc 			= $fila->total_isc - $anticipo_total_isc;
				$cpe_total_otr_imp 		= $fila->total_otr_imp - $anticipo_total_otr_imp;
				$cpe_total 				= $fila->total - $anticipo_total;
				$cpe_total 				= $fila->total - $anticipo_total;

				$lista[] = array(
					'nombre_sucursal' 				=> $nombre_sucursal, //0
					'nombre_sucursal_text'			=> $nombre_sucursal_text,
					'nombre_vendedor' 				=> $nombre_vendedor, //
					'nombre_tipo_documento' 		=> $nombre_tipo_documento, //
					'fecha_comprobante' 			=> date("d-m-Y", strtotime($fila->fecha_comprobante)), //
					'id_codigomoneda'				=> $fila->id_codigomoneda,
					'tipo_cambio'					=> $fila->tipo_cambio,
					
					'condicion_pago_tipo' 			=> $condicion_pago_tipo, //
					'condicion_pago_nombre' 		=> $condicion_pago_nombre, //
					'cpago_nrooperacion' 			=> $cpago_nrooperacion, //5
					'cpago_fechadeposito' 			=> $cpago_fechadeposito, //6
					'cpago_idbanco' 				=> $cpago_idbanco, //7
					'nombre_banco_deposito' 		=> $nombre_banco_deposito, //8
					'transporte_nro_placa' 			=> $fila->transporte_nro_placa,
					'serie_documento' 				=> $fila->serie_comprobante,
					'correlativo_documento'			=> $fila->numero_comprobante,

					'nro_orden'						=> $fila->nro_otr_comprobante,
					'docs_relacionados'				=> $docs_relacionados,

					'serie_correlativo' 			=> '<a title="Click Para Ver PDF" target="_blank" href="'.$enlace_pdf.'">'.$fila->serie_comprobante.'-'.$fila->numero_comprobante.'<a/>', //9
					'estado_envio_sunat' 			=> strtoupper($fila->estado_envio_sunat),

					'id_tipodoc_cliente'			=> $cliente->id_tipodocidentidad,
					'numero_doc_cliente'			=> $cliente->num_doc,
					'nombre_cliente' 				=> $nombre_cliente, //10
					'nombre_cliente_text'			=> $nombre_cliente_text,
					
					'direccion_cliente'				=> $direccion_cliente,
					'id_ubigeo_cliente'				=> $id_ubigeo_cliente,
					'ubigeo_dir_cliente'			=> $ubigeo_dir_cliente,

					'total_exportacion' 			=> custom_money_format($cpe_total_exportacion), //
					'total_gravadas' 				=> custom_money_format($cpe_total_gravadas), //
					'total_exoneradas' 				=> custom_money_format($cpe_total_exoneradas), //
					'total_inafecta' 				=> custom_money_format($cpe_total_inafecta), //
					'total_gratuitas' 				=> custom_money_format($cpe_total_gratuitas), //15
					'total_icbper' 					=> custom_money_format($cpe_total_icbper), //
					'total_descuento' 				=> custom_money_format($cpe_total_descuento), //
					'sub_total' 					=> custom_money_format($cpe_sub_total), //
					'porcentaje_igv' 				=> $cpe_porcentaje_igv, //
					'total_igv' 					=> custom_money_format($cpe_total_igv), //
					'total_isc' 					=> custom_money_format($cpe_total_isc), //
					'total_otr_imp' 				=> custom_money_format($cpe_total_otr_imp), //
					'total' 						=> custom_money_format($cpe_total), //25
					'total_number'					=> $cpe_total,
					
					'id_tipo_comprobante_modifica' 	=> $fila->id_tipo_comprobante_modifica, //24
					'serie_documento_modifica' 		=> empty($fila->serie_documento_modifica)?'-':$fila->serie_documento_modifica, //25
					'nro_documento_modifica' 		=> intval($fila->nro_documento_modifica) + 0, //linea 26
					'nota' 							=> $fila->nota
				);
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo '==> Excepción capturada: ',  $e->getMessage(), "\n";
			exit();
		}

		try {

			$sql_notas_cotizac = "SELECT de.id_contribuyente, de.fecha_comprobante, de.estado_documento, de.id_sucursal, de.id_vendedor, de.id_condicionpago, de.cpago_nrooperacion, de.cpago_fechadeposito, de.cpago_idbanco, de.transporte_nro_placa, de.idcliente, de.id_tipodocumento, de.numero_comprobante, de.modalidad, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_gratuitas, de.total_exportacion, de.total_icbper, de.total_descuento, de.sub_total, de.porcentaje_igv, de.total_igv, de.total, de.nota, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.nro_otr_comprobante FROM doc_no_oficial de where de.modalidad = :modalidad and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente ";

			if(count($ids_vendedores) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }
			if(count($ids_sucursales) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }
			if(count($ids_cod_monedas) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
			if(count($tipos_comprobantes) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_tipodocumento in ('".implode("','", $tipos_comprobantes)."') "; }
			if(count($estados_envio_sunat) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.estado_documento in ('".implode("','", $estados_envio_sunat)."') "; }

			$sentencia = $this->db->prepare($sql_notas_cotizac);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':modalidad', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->execute();

			while ($fila = $sentencia->fetch()) {
				$fila = (object)$fila;
				if($fila->tipo_cambio == '') {
					setlocale(LC_MONETARY, 'es_PE');
				} else {
					setlocale(LC_MONETARY, 'es_US');
				}

				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				$nombre_sucursal = '';
				$nombre_sucursal_text = '';
				if($sucursal) {
					$nombre_sucursal_text = $sucursal->nombre;
					$nombre_sucursal = '
					<div>'.$sucursal->nombre.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-office text-size-mini position-left"></i> '.$sucursal->direccion.'</div>
					';
				}

				$nombre_tipo_documento = '';
				if($fila->id_tipodocumento == '77') {
					$nombre_tipo_documento = 'NOTA DE VENTA';
				} elseif($fila->id_tipodocumento == '88') {
					$nombre_tipo_documento = 'COTIZACIÓN';
				}

				$condicion_pago_tipo = 'CONTADO';
				$condicion_pago_nombre = 'Contado';
				if(!empty($fila->id_condicionpago)) {
					$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));
					if($condicion_pago) {
						$condicion_pago_tipo = strtoupper($condicion_pago->tipo);
						$condicion_pago_nombre = $condicion_pago->condicionpago;
					}
				}

				$nombre_cliente = '';
				$nombre_cliente_text = '';
				$direccion_cliente = '';
				$id_ubigeo_cliente = '';
				$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($cliente) {
					$tipo_doc_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $cliente->id_tipodocidentidad)));
					$nombre_cliente = '
					<div>'.$tipo_doc_identidad->nombre.': '.$cliente->num_doc.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.ucwords($cliente->razon_social).'</div>
					';
					$nombre_cliente_text = $cliente->razon_social;

					if(!empty($fila->dir_destino)) {
						$direccion_cliente = $fila->dir_destino;
					} else {
						$direccion_cliente = empty($cliente->direccion_fiscal)?'':$cliente->direccion_fiscal;
					}

					if(!empty($fila->id_ubigeo_destino)) {
						$id_ubigeo_cliente = $fila->id_ubigeo_destino;
					} else {
						$id_ubigeo_cliente = empty($cliente->id_cod_ubigeo)?'':$cliente->id_cod_ubigeo;
					}
				}

				$ubigeo_dir_cliente = '';
				if(!empty($id_ubigeo_cliente)) {
					$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_cliente)));
					if($ubigeo_cliente) {
						$ubigeo_dir_cliente =  $ubigeo_cliente->departamento.' - '.$ubigeo_cliente->provincia.' '.$ubigeo_cliente->distrito;
					}
				}

				$nombre_vendedor = '';
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($vendedor) {
					$nombre_vendedor = '
					<div>'.$vendedor->email.' - (ID: '.$vendedor->idusuario.')</div>
					<div class="text-primary-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.$vendedor->nombre.' '.$vendedor->apellido.'</div>
					';
				}

				$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
				$cpago_fechadeposito = '';
				$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
				$nombre_banco_deposito = '';

				if(intval($cpago_idbanco) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
					if($cuenta_banco_transferencia) {
						$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
					}
				}

				if(!empty($cpago_fechadeposito)) {
					$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
				}
				
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodocumento, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $fila->modalidad)));
				

				if(count($abonos) > 0) {
					$lista_nrooperacion = array();
					$lista_fechadeposito = array();
					$lista_idbanco = array();
					$lista_nombre_banco = array();

					foreach($abonos as $abono) {
						if(!empty($abono->cpago_nrooperacion)) {
							$lista_nrooperacion[] = $abono->cpago_nrooperacion;
						}

						if(!empty($abono->fechadeposito)) {
							$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
						}
						
						if(intval($abono->idbanco) > 0 ) {
							$lista_idbanco[] = $abono->idbanco;
							$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
							if($cuenta_banco_transferencia) {
								$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
							}
						}
					}


					$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
					$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
					$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
					$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
				}

				$string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$fila->id_tipodocumento."|| ||".$fila->numero_comprobante."||".$fila->modalidad."||a4");
				$string_encrypted_document_ticket = $herramientas->encriptar("$usuario->id_contribuyente||".$fila->id_tipodocumento."|| ||".$fila->numero_comprobante."||".$fila->modalidad."||ticket");

				$url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
				$url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
				
				$enlace_pdf = $url_a4;

				if($fila->estado_documento != 'activo') {
					$fm = 0;
				} else {
					$fm = 1;
				}
				
				$lista[] = array(
					'nombre_sucursal' 					=> $nombre_sucursal, //0
					'nombre_sucursal_text'				=> $nombre_sucursal_text,
					'nombre_vendedor' 					=> $nombre_vendedor, //
					'nombre_tipo_documento' 			=> $nombre_tipo_documento, //
					'fecha_comprobante' 				=> date("d-m-Y", strtotime($fila->fecha_comprobante)), //
					'id_codigomoneda'					=> $fila->id_codigomoneda,
					'tipo_cambio'						=> $fila->tipo_cambio,
					
					'condicion_pago_tipo' 				=> $condicion_pago_tipo, //
					'condicion_pago_nombre' 			=> $condicion_pago_nombre, //
					'cpago_nrooperacion' 				=> $cpago_nrooperacion, //5
					'cpago_fechadeposito' 				=> $cpago_fechadeposito, //6
					'cpago_idbanco' 					=> $cpago_idbanco, //7
					'nombre_banco_deposito' 			=> $nombre_banco_deposito, //8
					'transporte_nro_placa' 				=> $fila->transporte_nro_placa,

					'nro_orden'							=> $fila->nro_otr_comprobante,
					'docs_relacionados'					=> '',

					'serie_documento' 					=> 'NV',
					'correlativo_documento'				=> $fila->numero_comprobante,

					'serie_correlativo' 				=> '<a title="Click Para Ver PDF" target="_blank" href="'.$enlace_pdf.'">'.$fila->numero_comprobante.'<a/>', //
					'estado_envio_sunat' 				=> ($fila->estado_documento == 'activo')?'ACEPTADO':'ANULADO',
					
					'id_tipodoc_cliente'			=> $cliente->id_tipodocidentidad,
					'numero_doc_cliente'			=> $cliente->num_doc,
					'nombre_cliente' 				=> $nombre_cliente, //10
					'nombre_cliente_text'			=> $nombre_cliente_text,

					'direccion_cliente'				=> $direccion_cliente,
					'id_ubigeo_cliente'				=> $id_ubigeo_cliente,
					'ubigeo_dir_cliente'			=> $ubigeo_dir_cliente,

					'total_exportacion' 				=> custom_money_format($fila->total_exportacion), //11
					'total_gravadas' 					=> custom_money_format($fila->total_gravadas), //12
					'total_exoneradas' 					=> custom_money_format($fila->total_exoneradas), //13
					'total_inafecta' 					=> custom_money_format($fila->total_inafecta), //14
					'total_gratuitas' 					=> custom_money_format($fila->total_gratuitas), //15
					'total_icbper' 						=> custom_money_format($fila->total_icbper), //16
					'total_descuento' 					=> custom_money_format($fila->total_descuento), //
					'sub_total' 						=> custom_money_format($fila->sub_total), //18
					'porcentaje_igv' 					=> custom_money_format($fila->porcentaje_igv), //19
					'total_igv' 						=> custom_money_format($fila->total_igv), //
					'total_isc' 						=> custom_money_format(0), //
					'total_otr_imp' 					=> custom_money_format(0), //
					'total' 							=> custom_money_format($fila->total), //
					'total_number'						=> $fila->total,
					
					'id_tipo_comprobante_modifica' 		=> '', //24
					'serie_documento_modifica' 			=> '', //
					'nro_documento_modifica' 			=> '', //
					'nota' 								=> $fila->nota
				);
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo '==> Excepción capturada: ',  $e->getMessage(), "\n";
			exit();
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $lista;
		return $resp;
	}

	public function get_reportedetallado($usuario, $datapost) {
		$gestion_usuarios = new GestionuserController;
		$id_contribuyente = $usuario->id_contribuyente;
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			return $resp;
		}

		if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3) {
            $resp_permiso_costo_prod = true;
        } else {
            $resp_permiso_costo_prod = $gestion_usuarios->verificar_permisos($usuario, 'permisos_otros', 'opt_otros_costocompra');
        }

		$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
		$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
		$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
		$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
		$ids_cod_monedas = empty($datapost['tipos_monedas'])?array():explode(',', $datapost['tipos_monedas']);
		$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
		$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
		//$idproducto = !isset($datapost['select_producto'])?array(0):$datapost['select_producto'];

		$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
		$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);

		$tipos_validos_monedas = array('PEN', 'USD');
		$tipos_comprobantes_validos = array('03', '01', '07', '08', '77', '88');
		$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado');
		
		$ids_cod_monedas = array_filter($ids_cod_monedas);
		if(count($ids_cod_monedas) > 0) {
			foreach($ids_cod_monedas as $idcodmoneda) {
				if(!in_array($idcodmoneda, $tipos_validos_monedas)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Código Moneda';
					$resp['mensaje'] = 'El código de Moneda Seleccionado es Inválido!';
					return $resp;
				}
			}
		}

		$tipos_comprobantes = array_filter($tipos_comprobantes);
		if(count($tipos_comprobantes) > 0) {
			foreach($tipos_comprobantes as $id_tipo_doc) {
				if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

		$estados_envio_sunat = array_filter($estados_envio_sunat);
		if(count($estados_envio_sunat) > 0) {
			foreach($estados_envio_sunat as $tipo_env_sunat) {
				if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

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
		
		$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
		$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			return $resp;
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			return $resp;
		}

		$lista = array();
		 
		$sql_reporte = "SELECT de.id_contribuyente, de.fecha_comprobante, de.id_sucursal, de.id_vendedor, de.id_condicionpago, de.idcliente, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.tipo_envio_sunat, de.estado_envio_sunat, de.cpago_nrooperacion, de.cpago_fechadeposito, de.cpago_idbanco, de.transporte_nro_placa, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_gratuitas, de.total_exportacion, de.total_icbper, de.total_descuento, de.sub_total, de.porcentaje_igv, de.total_igv, de.total_isc, de.total_otr_imp, de.total, de.nota, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, de.id_codigomoneda, de.dir_destino, de.id_ubigeo_destino, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, detalle.item, detalle.id_producto, detalle.id_unidad_medida, detalle.unidad_medida, detalle.cantidad, detalle.precio, detalle.sub_total det_sub_total, detalle.igv, detalle.isc, detalle.icbper, detalle.importe, detalle.precio_sin_igv, detalle.descripcion FROM doc_electronico de INNER JOIN detalle_doc detalle on de.id_contribuyente = detalle.id_contribuyente and de.id_tipodoc_electronico = detalle.id_tipodoc_electronico and de.serie_comprobante = detalle.serie_comprobante and de.numero_comprobante = detalle.numero_comprobante and de.tipo_envio_sunat = detalle.tipo_envio_sunat where de.tipo_envio_sunat = :tipo_envio_sunat and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) and de.id_contribuyente =  :id_contribuyente ";
		
		if(count($ids_vendedores) > 0){ $sql_reporte = $sql_reporte." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $sql_reporte = $sql_reporte." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }
		if(count($ids_cod_monedas) > 0){ $sql_reporte = $sql_reporte." and de.id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
		if(count($tipos_comprobantes) > 0){ $sql_reporte = $sql_reporte." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; }
		if(count($estados_envio_sunat) > 0){ $sql_reporte = $sql_reporte." and de.estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') "; }
		
		$sentencia = $this->db->prepare($sql_reporte);
		$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
		$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
		
		try {
		
			$sentencia->execute();
			while ($fila = $sentencia->fetch()) {
				$fila = (object)$fila;
				if($fila->tipo_cambio == '') {
					setlocale(LC_MONETARY, 'es_PE');
				} else {
					setlocale(LC_MONETARY, 'es_US');
				}
				
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				$nombre_sucursal = '';
				$nombre_sucursal_text = '';
				if($sucursal) {
					$nombre_sucursal_text = $sucursal->nombre;
					$nombre_sucursal = '
					<div>'.$sucursal->nombre.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-office text-size-mini position-left"></i> '.$sucursal->direccion.'</div>
					';
				}

				$nombre_tipo_documento = '';
				if($fila->id_tipodoc_electronico == '03') {
					$nombre_tipo_documento = 'BOLETA';
				} elseif($fila->id_tipodoc_electronico == '01') {
					$nombre_tipo_documento = 'FACTURA';
				} elseif($fila->id_tipodoc_electronico == '07') {
					$nombre_tipo_documento = 'NOTA DE CRÉDITO';
				} elseif($fila->id_tipodoc_electronico == '08') {
					$nombre_tipo_documento = 'NOTA DE DÉBITO';
				} elseif($fila->id_tipodoc_electronico == '09') {
					$nombre_tipo_documento = 'GUÍA REMISIÓN';
				}

				$condicion_pago_tipo = 'CONTADO';
				$condicion_pago_nombre = 'Contado';
				if(!empty($fila->id_condicionpago)) {
					$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));
					if($condicion_pago) {
						$condicion_pago_tipo = strtoupper($condicion_pago->tipo);
						$condicion_pago_nombre = $condicion_pago->condicionpago;
					}
				}
				
				$nombre_cliente = '';
				$nombre_cliente_text = '';
				$direccion_cliente = '';
				$id_ubigeo_cliente = '';
				$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($cliente) {
					$tipo_doc_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $cliente->id_tipodocidentidad)));
					$nombre_cliente = '
					<div>'.$tipo_doc_identidad->nombre.': '.$cliente->num_doc.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.ucwords($cliente->razon_social).'</div>
					';
					$nombre_cliente_text = $cliente->razon_social;

					if(!empty($fila->dir_destino)) {
						$direccion_cliente = $fila->dir_destino;
					} else {
						$direccion_cliente = empty($cliente->direccion_fiscal)?'':$cliente->direccion_fiscal;
					}

					if(!empty($fila->id_ubigeo_destino)) {
						$id_ubigeo_cliente = $fila->id_ubigeo_destino;
					} else {
						$id_ubigeo_cliente = empty($cliente->id_cod_ubigeo)?'':$cliente->id_cod_ubigeo;
					}
				}

				$ubigeo_dir_cliente = '';
				if(!empty($id_ubigeo_cliente)) {
					$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_cliente)));
					if($ubigeo_cliente) {
						$ubigeo_dir_cliente =  $ubigeo_cliente->departamento.' - '.$ubigeo_cliente->provincia.' '.$ubigeo_cliente->distrito;
					}
				}

				$nombre_vendedor = '';
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($vendedor) {
					$nombre_vendedor = '
					<div>'.$vendedor->email.' - (ID: '.$vendedor->idusuario.')</div>
					<div class="text-primary-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.$vendedor->nombre.' '.$vendedor->apellido.'</div>
					';
				}

				$nombre_producto = '';
				$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $fila->id_producto, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($producto) {
					$nombre_producto = $producto->nombre;
				}

				$nombre_cageoria = '';
				$categoria = Categoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $producto->id_categoria)));
				if($categoria){
					$nombre_cageoria = $categoria->nombre;
				} 

				$nombre_unidad = '';
				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $fila->id_unidad_medida)));
				if($unidad_medida) {
					$nombre_unidad = $unidad_medida->simbolo;
				}
				
				$costo_unitario = $this->get_costo_producto($producto);
				if($costo_unitario < 0) {
					$costo_unitario = 0;
				}
				
				$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
				$cpago_fechadeposito = '';
				$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
				$nombre_banco_deposito = '';

				if(intval($cpago_idbanco) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
					if($cuenta_banco_transferencia) {
						$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
					}
				}

				if(!empty($fila->cpago_fechadeposito)) {
					$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
				}
				
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $fila->tipo_envio_sunat)));
				

				if(count($abonos) > 0) {
					$lista_nrooperacion = array();
					$lista_fechadeposito = array();
					$lista_idbanco = array();
					$lista_nombre_banco = array();

					foreach($abonos as $abono) {
						if(!empty($abono->cpago_nrooperacion)) {
							$lista_nrooperacion[] = $abono->cpago_nrooperacion;
						}

						if(!empty($abono->fechadeposito)) {
							$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
						}
						
						if(intval($abono->idbanco) > 0 ) {
							$lista_idbanco[] = $abono->idbanco;
							$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
							if($cuenta_banco_transferencia) {
								$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
							}
						}
					}


					$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
					$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
					$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
					$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
				}

				$string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$fila->tipo_envio_sunat."||a4");
				$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$fila->tipo_envio_sunat."||ticket");

				$url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
				$url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

				$enlace_pdf = $url_a4;

				if($fila->estado_envio_sunat == 'anulado') {
					$fm = 0;
				} else if ($fila->estado_envio_sunat == 'rechazado') {
					$fm = 0;
				} else {
					$fm = 1;
				}

				if($resp_permiso_costo_prod === true) {
					$utilidad = round($fila->importe + $fila->igv - $costo_unitario*$fila->cantidad, 2);
				} else {
					$costo_unitario = 0;
					$utilidad = 0;
				}

				$anticipo_total_gravadas = 0;
				$anticipo_total_inafecta = 0;
				$anticipo_total_exoneradas = 0;
				$anticipo_total_gratuitas = 0;
				$anticipo_total_exportacion = 0;
				$anticipo_total_icbper = 0;
				$anticipo_sub_total = 0;
				$anticipo_impuesto_icbper = 0;
				$anticipo_total_igv = 0;
				$anticipo_total_isc = 0;
				$anticipo_total_otr_imp = 0;
				$anticipo_total = 0;
				if($fila->id_tipodoc_electronico == '01') {
					$anticipos = $this->get_anticipos(
						array(
							'id_contribuyente' 	=> $id_contribuyente, 
							'tipo_comprobante' 	=> $fila->id_tipodoc_electronico, 
							'serie_doc' 		=> $fila->serie_comprobante, 
							'numero_doc' 		=> $fila->numero_comprobante, 
							'tipo_envio_sunat' 	=> $fila->tipo_envio_sunat
						)
					);

					$anticipo_total_gravadas 		= $anticipos['totales']['total_gravadas'];
					$anticipo_total_inafecta 		= $anticipos['totales']['total_inafecta'];
					$anticipo_total_exoneradas 		= $anticipos['totales']['total_exoneradas'];
					$anticipo_total_gratuitas 		= $anticipos['totales']['total_gratuitas'];
					$anticipo_total_exportacion 	= $anticipos['totales']['total_exportacion'];
					$anticipo_total_icbper 			= $anticipos['totales']['total_icbper'];
					$anticipo_sub_total 			= $anticipos['totales']['sub_total'];
					$anticipo_impuesto_icbper 		= $anticipos['totales']['impuesto_icbper'];
					$anticipo_total_igv 			= $anticipos['totales']['total_igv'];
					$anticipo_total_isc 			= $anticipos['totales']['total_isc'];
					$anticipo_total_otr_imp 		= $anticipos['totales']['total_otr_imp'];
					$anticipo_total 				= $anticipos['totales']['total'];
				}

				$cpe_total_exportacion 	= $fila->total_exportacion - $anticipo_total_exportacion;
				$cpe_total_gravadas 	= $fila->total_gravadas - $anticipo_total_gravadas;
				$cpe_total_exoneradas 	= $fila->total_exoneradas - $anticipo_total_exoneradas;
				$cpe_total_inafecta 	= $fila->total_inafecta - $anticipo_total_inafecta;
				$cpe_total_gratuitas 	= $fila->total_gratuitas - $anticipo_total_gratuitas;
				$cpe_total_icbper 		= $fila->total_icbper - $anticipo_total_icbper;
				$cpe_total_descuento 	= $fila->total_descuento;
				$cpe_sub_total 			= $fila->sub_total - $anticipo_sub_total;
				$cpe_porcentaje_igv 	= $fila->porcentaje_igv;
				$cpe_total_igv 			= $fila->total_igv - $anticipo_total_igv;
				$cpe_total_isc 			= $fila->total_isc - $anticipo_total_isc;
				$cpe_total_otr_imp 		= $fila->total_otr_imp - $anticipo_total_otr_imp;
				$cpe_total 				= $fila->total - $anticipo_total;
				$cpe_total 				= $fila->total - $anticipo_total;

				$lista[] = array(
					'nombre_sucursal' 				=> $nombre_sucursal, //0
					'nombre_sucursal_text'			=> $nombre_sucursal_text,
					'nombre_vendedor' 				=> $nombre_vendedor, //
					'nombre_tipo_documento' 		=> $nombre_tipo_documento, //
					'fecha_comprobante' 			=> date("d-m-Y", strtotime($fila->fecha_comprobante)), //
					'id_codigomoneda'					=> $fila->id_codigomoneda,
					'tipo_cambio'						=> $fila->tipo_cambio,
					
					'condicion_pago_tipo' 			=> $condicion_pago_tipo, //
					'condicion_pago_nombre' 		=> $condicion_pago_nombre, //
					'cpago_nrooperacion' 			=> $cpago_nrooperacion, //5
					'cpago_fechadeposito' 			=> $cpago_fechadeposito, //6
					'cpago_idbanco' 				=> $cpago_idbanco, //7
					'nombre_banco_deposito' 		=> $nombre_banco_deposito, //8
					'transporte_nro_placa' 			=> $fila->transporte_nro_placa,
					'serie_documento' 				=> $fila->serie_comprobante,
					'correlativo_documento'			=> $fila->numero_comprobante,

					'serie_correlativo' 			=> '<a title="Click Para Ver PDF" target="_blank" href="'.$enlace_pdf.'">'.$fila->serie_comprobante.'-'.$fila->numero_comprobante.'<a/>', //9
					'estado_envio_sunat' 			=> strtoupper($fila->estado_envio_sunat),

					'id_tipodoc_cliente'			=> $cliente->id_tipodocidentidad,
					'numero_doc_cliente'			=> $cliente->num_doc,
					'nombre_cliente' 				=> $nombre_cliente, //10
					'nombre_cliente_text'			=> $nombre_cliente_text,
					
					'direccion_cliente'				=> $direccion_cliente,
					'id_ubigeo_cliente'				=> $id_ubigeo_cliente,
					'ubigeo_dir_cliente'			=> $ubigeo_dir_cliente,

					'total_exportacion' 			=> custom_money_format($cpe_total_exportacion), //
					'total_gravadas' 				=> custom_money_format($cpe_total_gravadas), //
					'total_exoneradas' 				=> custom_money_format($cpe_total_exoneradas), //
					'total_inafecta' 				=> custom_money_format($cpe_total_inafecta), //
					'total_gratuitas' 				=> custom_money_format($cpe_total_gratuitas), //15
					'total_icbper' 					=> custom_money_format($cpe_total_icbper), //
					'total_descuento' 				=> custom_money_format($cpe_total_descuento), //
					'sub_total' 					=> custom_money_format($cpe_sub_total), //
					'porcentaje_igv' 				=> $cpe_porcentaje_igv, //
					'total_igv' 					=> custom_money_format($cpe_total_igv), //
					'total_isc' 					=> custom_money_format($cpe_total_isc), //
					'total_otr_imp' 				=> custom_money_format($cpe_total_otr_imp), //
					'total' 						=> custom_money_format($cpe_total), //25
					'total_number'					=> $cpe_total,
					
					'id_tipo_comprobante_modifica' 	=> $fila->id_tipo_comprobante_modifica, //24
					'serie_documento_modifica' 		=> empty($fila->serie_documento_modifica)?'-':$fila->serie_documento_modifica, //25
					'nro_documento_modifica' 		=> intval($fila->nro_documento_modifica) + 0, //linea 26
					'nota' 							=> $fila->nota, //linea 27

					'item' 							=> $fila->item, //
					'idproducto'					=> $producto->idproducto,
					'codigo' 						=> $producto->codigo,
					'nombre_producto_original'		=> $nombre_producto, //
					'nombre_producto'				=> $nombre_producto, //
					'producto_servicio'				=> $fila->descripcion, //30
					'marca' 						=> $producto->marca,
					'nombre_categoria' 				=> $nombre_cageoria, //34
					'cantidad' 						=> $fila->cantidad + 0, //
					'nombre_unidad' 				=> $nombre_unidad, //
					'precio_sin_igv' 				=> custom_money_format($fila->precio_sin_igv), //32
					'igv' 							=> custom_money_format($fila->igv), //
					'isc' 							=> custom_money_format($fila->isc), //
					'icbper' 						=> custom_money_format($fila->icbper), //
					'subtotal' 						=> custom_money_format($fila->importe + $fila->igv), //36

					'costo_unitario' 				=> custom_money_format($costo_unitario), //37
					'costoxcantidad' 				=> custom_money_format(round($costo_unitario*$fila->cantidad, 2)), //
					'utilidad' 						=> custom_money_format($utilidad), //39
					'stock' 						=> $producto->stock
				);
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo '==> Excepción capturada: ',  $e->getMessage(), "\n";
			exit();
		}

		try {
			$sql_notas_cotizac = "SELECT de.id_contribuyente, de.fecha_comprobante, de.estado_documento, de.id_sucursal, de.id_vendedor, de.id_condicionpago, de.cpago_nrooperacion, de.cpago_fechadeposito, de.cpago_idbanco, de.transporte_nro_placa, de.idcliente, de.id_tipodocumento, de.numero_comprobante, de.modalidad, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_gratuitas, de.total_exportacion, de.total_icbper, de.total_descuento, de.sub_total, de.porcentaje_igv, de.total_igv, de.total, de.nota, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, detalle.item, detalle.id_producto, detalle.id_unidad_medida, detalle.unidad_medida, detalle.cantidad, detalle.precio, detalle.sub_total det_sub_total, detalle.igv, detalle.importe, detalle.precio_sin_igv, detalle.descripcion FROM doc_no_oficial de INNER JOIN  detalle_docnooficial detalle on de.id_contribuyente = detalle.id_contribuyente and de.id_tipodocumento = detalle.id_tipodocumento and de.numero_comprobante = detalle.numero_comprobante and de.modalidad = detalle.modalidad where de.modalidad = :modalidad and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente ";

			if(count($ids_vendedores) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }
			if(count($ids_sucursales) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }
			if(count($ids_cod_monedas) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
			if(count($tipos_comprobantes) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.id_tipodocumento in ('".implode("','", $tipos_comprobantes)."') "; }
			if(count($estados_envio_sunat) > 0){ $sql_notas_cotizac = $sql_notas_cotizac." and de.estado_documento in ('".implode("','", $estados_envio_sunat)."') "; }

			$sentencia = $this->db->prepare($sql_notas_cotizac);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':modalidad', $tipo_envio_sunat, PDO::PARAM_STR);
			
			$sentencia->execute();
			while ($fila = $sentencia->fetch()) {
				$fila = (object)$fila;
				if($fila->tipo_cambio == '') {
					setlocale(LC_MONETARY, 'es_PE');
				} else {
					setlocale(LC_MONETARY, 'es_US');
				}

				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				$nombre_sucursal = '';
				$nombre_sucursal_text = '';
				if($sucursal) {
					$nombre_sucursal_text = $sucursal->nombre;
					$nombre_sucursal = '
					<div>'.$sucursal->nombre.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-office text-size-mini position-left"></i> '.$sucursal->direccion.'</div>
					';
				}

				$nombre_tipo_documento = '';
				if($fila->id_tipodocumento == '77') {
					$nombre_tipo_documento = 'NOTA DE VENTA';
				} elseif($fila->id_tipodocumento == '88') {
					$nombre_tipo_documento = 'COTIZACIÓN';
				}

				$condicion_pago_tipo = 'CONTADO';
				$condicion_pago_nombre = 'Contado';
				if(!empty($fila->id_condicionpago)) {
					$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));
					if($condicion_pago) {
						$condicion_pago_tipo = strtoupper($condicion_pago->tipo);
						$condicion_pago_nombre = $condicion_pago->condicionpago;
					}
				}

				$nombre_cliente = '';
				$nombre_cliente_text = '';
				$direccion_cliente = '';
				$id_ubigeo_cliente = '';
				$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($cliente) {
					$tipo_doc_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $cliente->id_tipodocidentidad)));
					$nombre_cliente = '
					<div>'.$tipo_doc_identidad->nombre.': '.$cliente->num_doc.'</div>
					<div class="text-success-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.ucwords($cliente->razon_social).'</div>
					';
					$nombre_cliente_text = $cliente->razon_social;

					if(!empty($fila->dir_destino)) {
						$direccion_cliente = $fila->dir_destino;
					} else {
						$direccion_cliente = empty($cliente->direccion_fiscal)?'':$cliente->direccion_fiscal;
					}

					if(!empty($fila->id_ubigeo_destino)) {
						$id_ubigeo_cliente = $fila->id_ubigeo_destino;
					} else {
						$id_ubigeo_cliente = empty($cliente->id_cod_ubigeo)?'':$cliente->id_cod_ubigeo;
					}
				}

				$ubigeo_dir_cliente = '';
				if(!empty($id_ubigeo_cliente)) {
					$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_cliente)));
					if($ubigeo_cliente) {
						$ubigeo_dir_cliente =  $ubigeo_cliente->departamento.' - '.$ubigeo_cliente->provincia.' '.$ubigeo_cliente->distrito;
					}
				}

				$nombre_vendedor = '';
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($vendedor) {
					$nombre_vendedor = '
					<div>'.$vendedor->email.' - (ID: '.$vendedor->idusuario.')</div>
					<div class="text-primary-600 text-size-small"><i class="icon-user text-size-mini position-left"></i> '.$vendedor->nombre.' '.$vendedor->apellido.'</div>
					';
				}

				$nombre_producto = '';
				$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $fila->id_producto, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($producto) {
					$nombre_producto = $producto->nombre;
				}

				$nombre_cageoria = '';
				$categoria = Categoria::findFirst(array("idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $producto->id_categoria)));
				if($categoria){
					$nombre_cageoria = $categoria->nombre;
				}

				$nombre_unidad = '';
				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $fila->id_unidad_medida)));
				if($unidad_medida) {
					$nombre_unidad = $unidad_medida->simbolo;
				}
				
				$costo_unitario = $this->get_costo_producto($producto);
				if($costo_unitario < 0) {
					$costo_unitario = 0;
				}

				$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
				$cpago_fechadeposito = '';
				$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
				$nombre_banco_deposito = '';

				if(intval($cpago_idbanco) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
					if($cuenta_banco_transferencia) {
						$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
					}
				}

				if(!empty($cpago_fechadeposito)) {
					$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
				}
				
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodocumento, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $fila->modalidad)));
				

				if(count($abonos) > 0) {
					$lista_nrooperacion = array();
					$lista_fechadeposito = array();
					$lista_idbanco = array();
					$lista_nombre_banco = array();

					foreach($abonos as $abono) {
						if(!empty($abono->cpago_nrooperacion)) {
							$lista_nrooperacion[] = $abono->cpago_nrooperacion;
						}

						if(!empty($abono->fechadeposito)) {
							$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
						}
						
						if(intval($abono->idbanco) > 0 ) {
							$lista_idbanco[] = $abono->idbanco;
							$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
							if($cuenta_banco_transferencia) {
								$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
							}
						}
					}


					$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
					$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
					$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
					$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
				}

				$string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$fila->id_tipodocumento."|| ||".$fila->numero_comprobante."||".$fila->modalidad."||a4");
				$string_encrypted_document_ticket = $herramientas->encriptar("$usuario->id_contribuyente||".$fila->id_tipodocumento."|| ||".$fila->numero_comprobante."||".$fila->modalidad."||ticket");

				$url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
				$url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
				
				$enlace_pdf = $url_a4;

				if($fila->estado_documento != 'activo') {
					$fm = 0;
				} else {
					$fm = 1;
				}

				if($resp_permiso_costo_prod === true) {
					$utilidad = round($fila->importe + $fila->igv - $costo_unitario*$fila->cantidad, 2);
				} else {
					$costo_unitario = 0;
					$utilidad = 0;
				}
				
				$lista[] = array(
					'nombre_sucursal' 					=> $nombre_sucursal, //0
					'nombre_sucursal_text'				=> $nombre_sucursal_text,
					'nombre_vendedor' 					=> $nombre_vendedor, //
					'nombre_tipo_documento' 			=> $nombre_tipo_documento, //
					'fecha_comprobante' 				=> date("d-m-Y", strtotime($fila->fecha_comprobante)), //
					'id_codigomoneda'					=> $fila->id_codigomoneda,
					'tipo_cambio'						=> $fila->tipo_cambio,
					
					'condicion_pago_tipo' 				=> $condicion_pago_tipo, //
					'condicion_pago_nombre' 			=> $condicion_pago_nombre, //
					'cpago_nrooperacion' 				=> $cpago_nrooperacion, //5
					'cpago_fechadeposito' 				=> $cpago_fechadeposito, //6
					'cpago_idbanco' 					=> $cpago_idbanco, //7
					'nombre_banco_deposito' 			=> $nombre_banco_deposito, //8
					'transporte_nro_placa' 				=> $fila->transporte_nro_placa,

					'serie_documento' 					=> 'NV',
					'correlativo_documento'				=> $fila->numero_comprobante,

					'serie_correlativo' 				=> '<a title="Click Para Ver PDF" target="_blank" href="'.$enlace_pdf.'">'.$fila->numero_comprobante.'<a/>', //
					'estado_envio_sunat' 				=> ($fila->estado_documento == 'activo')?'ACEPTADO':'ANULADO',
					
					'id_tipodoc_cliente'			=> $cliente->id_tipodocidentidad,
					'numero_doc_cliente'			=> $cliente->num_doc,
					'nombre_cliente' 				=> $nombre_cliente, //10
					'nombre_cliente_text'			=> $nombre_cliente_text,

					'direccion_cliente'				=> $direccion_cliente,
					'id_ubigeo_cliente'				=> $id_ubigeo_cliente,
					'ubigeo_dir_cliente'			=> $ubigeo_dir_cliente,

					'total_exportacion' 				=> custom_money_format($fila->total_exportacion), //11
					'total_gravadas' 					=> custom_money_format($fila->total_gravadas), //12
					'total_exoneradas' 					=> custom_money_format($fila->total_exoneradas), //13
					'total_inafecta' 					=> custom_money_format($fila->total_inafecta), //14
					'total_gratuitas' 					=> custom_money_format($fila->total_gratuitas), //15
					'total_icbper' 						=> custom_money_format($fila->total_icbper), //16
					'total_descuento' 					=> custom_money_format($fila->total_descuento), //
					'sub_total' 						=> custom_money_format($fila->sub_total), //18
					'porcentaje_igv' 					=> custom_money_format($fila->porcentaje_igv), //19
					'total_igv' 						=> custom_money_format($fila->total_igv), //
					'total_isc' 						=> custom_money_format(0), //
					'total_otr_imp' 					=> custom_money_format(0), //
					'total' 							=> custom_money_format($fila->total), //
					'total_number'						=> $fila->total,
					
					'id_tipo_comprobante_modifica' 		=> '', //24
					'serie_documento_modifica' 			=> '', //
					'nro_documento_modifica' 			=> '', //
					'nota' 								=> $fila->nota, //

					'item' 								=> $fila->item, //28
					'idproducto'						=> $producto->idproducto,
					'codigo' 							=> $producto->codigo,
					'nombre_producto_original'			=> $nombre_producto, //
					'nombre_producto'					=> $nombre_producto, //
					'producto_servicio'					=> $fila->descripcion, //30
					'marca' 							=> $producto->marca,
					'nombre_categoria' 					=> $nombre_cageoria,
					'cantidad' 							=> $fila->cantidad + 0, //
					'nombre_unidad' 					=> $nombre_unidad, //
					'precio_sin_igv' 					=> custom_money_format($fila->precio_sin_igv), //
					'igv' 								=> custom_money_format($fila->igv), //
					'isc' 								=> custom_money_format(0), //
					'icbper' 							=> custom_money_format(0), //
					'subtotal' 							=> custom_money_format($fila->importe + $fila->igv), //

					'costo_unitario' 					=> custom_money_format($costo_unitario), //37
					'costoxcantidad' 					=> custom_money_format(round($costo_unitario*$fila->cantidad, 2)), //38
					'utilidad' 							=> custom_money_format($utilidad), //39
					'stock' 							=> $producto->stock
				);
			}

		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: ==> ',  $e->getMessage(), "\n";
			exit();
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $lista;
		return $resp;
	}

	//   FUNCIONA: select_vendedor=0&select_sucursal=0&fecha_inicio=01%2F02%2F2022&fecha_fin=08%2F03%2F2022&id_cod_moneda=&select_tipo_comprobante=&select_estado=&select_producto=0&sucursales=0&vendedores=0&tipos_documentos=&estados_documentos=&tipos_monedas=



	//NO FUNCIONA: select_vendedor=0&select_sucursal=0&fecha_inicio=01%2F02%2F2022&fecha_fin=08%2F03%2F2022&id_cod_moneda=&select_tipo_comprobante=&select_estado=&select_producto=0&sucursales=0&vendedores=0&tipos_documentos=&estados_documentos=&tipos_monedas=

	public function get_costo_producto($producto) {
		return $producto->precio_compra;
		/*
		$save_kardex = Kardex::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $producto->idproducto), "order" => "id_kardex DESC"));
		if(!$save_kardex) {
			return $producto->precio_compra;
		}

		//creo que aquí la lógica sería: Si existe una salida, entonces verificar el costo promedio justo en dicha salida, ya sea una boleta, factura o nota de crédito, de tal manera que en un determinado tiempo la ganancia será diferente dependiente del costo promedio calculado en este momento.

		return $save_kardex->costo_unitario_promedio;
		*/
	}

	public function generarTxtLibcomprasAction($fecha_inicio, $fecha_fin, $idsucursal = 0, $tipo_comprobante = '-1', $periodo_seleccionado = '') {
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
		
		$herramientas = new HerramientasController;
		
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($periodo_seleccionado)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Periodo';
			$resp['mensaje'] = 'El periodo seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}

		$tipos_validos_docs = array('-1', '01', '03', '07', '08');
		if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

		$idsucursal = empty($idsucursal)?0:intval($idsucursal) + 0;
		if($idsucursal > 0) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
				echo json_encode($resp);
				exit();
			}
		}

		$filename = 'LE'.$contribuyente->ruc.date("Ym", strtotime($fecha_inicio)).'00'.'0801'.'0000'.'1111'.'.txt';

		header("Content-Type: text/plain");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		
		$items_libro = $this->get_detallecompras($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $periodo_seleccionado, $idsucursal, $tipo_comprobante);
		foreach($items_libro as $key => $item) {
			echo implode("|",$item)."\r\n";
		}
		exit();
	}

	public function actualizarTipoCambioComprasAction() {
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

			$fecha_inicio = $datapost['fecha_inicio'];
			$fecha_fin = $datapost['fecha_fin'];
			$periodo_seleccionado = $datapost['periodo'];
			$tipo_fecha = !isset($datapost['tipo_fecha'])?'fecha_emision':(($datapost['tipo_fecha'] == 'fecha_emision')?'fecha_emision':'fecha_registro');
			
			$herramientas = new HerramientasController;
			
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($periodo_seleccionado)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Periodo';
				$resp['mensaje'] = 'El periodo seleccionado no es válido!';
				echo json_encode($resp);
                exit();
			}

			$tipos_validos_docs = array('-1', '01', '03', '07', '08');
			$tipo_comprobante = empty($datapost['tipo_documento'])?'-1':$datapost['tipo_documento'];
			if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
				echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$idsucursal = empty($datapost['idsucursal'])?0:intval($datapost['idsucursal']) + 0;
			if($idsucursal > 0) {
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				if(!$sucursal) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
					echo json_encode($resp);
					exit();
				}
			}
			
			if($tipo_comprobante == '-1') {
				$array_tipos_docs = " ('01', '03', '07', '08') ";
			} else {
				$array_tipos_docs = " ('".$tipo_comprobante."') ";
			}

			$confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Necesitamos tu Confirmación!';
				$resp['mensaje'] = '¿Realmente Deseas Actualizar el factor tipo de cambio en todos tus documentos emitidos en Dólares?, la actualización no podrá revertirse.';
				echo json_encode($resp);
				exit();
			}

			$resp = $this->actualizar_tipo_cambio_compras($usuario, $contribuyente, $idsucursal, $fecha_inicio, $fecha_fin, $tipo_fecha, $array_tipos_docs);

			echo json_encode($resp);
			exit();
		}
	}

	public function actualizar_tipo_cambio_compras($usuario, $contribuyente, $idsucursal, $fecha_inicio, $fecha_fin, $tipo_fecha, $array_tipos_docs) {

		$campo_fecha = 'de.fecha_comprobante';
		if($tipo_fecha == 'fecha_registro') {
			$campo_fecha = 'de.fecha_registro';
		}

		$query = "SELECT de.id_compra, de.fecha_comprobante, de.total_isc, de.total_icbper, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, proveedor.id_tipodocidentidad, proveedor.num_doc, proveedor.razon_social, de.total_gravadas, de.total_igv, de.total_otr_imp, de.total, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv,  de.total_otr_imp, de.total, de.total_gratuitas, de.fecha_comprobante_modifica, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica from compra de INNER JOIN proveedor on de.id_proveedor = proveedor.id_proveedor where de.estado = 'activo' and  de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and ".$campo_fecha." >= CAST(:fecha_inicio AS DATE) and ".$campo_fecha." <= CAST(:fecha_fin AS DATE) ";

		if($idsucursal > 0) {
			$query = $query.' and idsucursal = '.$idsucursal.' ';
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (actualizar_tipo_cambio_compras) ',  $e->getMessage(), "\n";
			exit();
		}
		
		$this->db->begin();
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			try {
				$documento_compra = Compra::findFirst(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: and id_compra = :id_compra:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'id_compra' => $fila->id_compra)));

				if($documento_compra) {
					$tipo_cambiosunat = SunatTipodecambio::findFirst(array("fecha = CAST(:fecha: AS DATE)", 'bind' => array('fecha' => $documento_compra->fecha_comprobante)));
					
					if($tipo_cambiosunat) {
						if(round(floatval($tipo_cambiosunat->venta), 3) != round(floatval($documento_compra->tipo_cambio_sunat), 3)) {

							$tipo_de_cambio_anterior = $documento_compra->tipo_cambio_sunat;

							if(empty($documento_compra->log)){ 
								$log = array();
							} else {
								$log = json_decode($documento_compra->log, true);
							}

							$log[] = array(
								'tipo' => 'tipo_cambio',
								'descripcion' => "El día: ".date('Y-m-d H:i:s').", el usuario $usuario->nombre $usuario->apellido (ID: $usuario->idusuario) realizó un cambio en al factor T.C. Anterior: $tipo_de_cambio_anterior, Actual: $tipo_cambiosunat->venta "
							);
							$documento_compra->log = json_encode($log);

							$documento_compra->tipo_cambio_sunat = $tipo_cambiosunat->venta;

							try {
								if(!$documento_compra->save()) {
									$this->db->rollback();
									$msg = '';
									foreach ($documento_compra->getMessages() as $message) {
										$msg = $msg.$message."</br>\n";
									}
									$resp['respuesta'] = 'error';
									$resp['titulo'] = 'Error';
									$resp['mensaje'] = 'No se logró actualizar el tipo de cambio!';
									return $resp;
								}
							} catch (Exception $e) {
                				$this->saveLogger($e);
								$this->db->rollback();
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = 'No se logró actualizar el tipo de cambio: '.$e->getMessage();
								return $resp;
							}

							$n++;
						}
					}
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se logró actualizar el tipo de cambio: '.$e->getMessage();
				return $resp;
			}
		}

		$this->db->commit();
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Actualización Completa';
		$resp['mensaje'] = 'El proceso de actualización del tipo de cambio ha finalizado correctamente, se actualizaron '.$n.' documentos de compra.';
		return $resp;
	}

	public function getDetallecomprasAction() {
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

			$fecha_inicio = $datapost['fecha_inicio'];
			$fecha_fin = $datapost['fecha_fin'];
			$periodo_seleccionado = $datapost['periodo'];
			$tipo_fecha = !isset($datapost['tipo_fecha'])?'fecha_emision':(($datapost['tipo_fecha'] == 'fecha_emision')?'fecha_emision':'fecha_registro');
			
			$herramientas = new HerramientasController;
			
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($periodo_seleccionado)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Periodo';
				$resp['mensaje'] = 'El periodo seleccionado no es válido!';
				echo json_encode($resp);
                exit();
			}

			$tipos_validos_docs = array('-1', '01', '03', '07', '08');
			$tipo_comprobante = empty($datapost['tipo_documento'])?'-1':$datapost['tipo_documento'];
			if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
				echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

			$idsucursal = empty($datapost['idsucursal'])?0:intval($datapost['idsucursal']) + 0;
			if($idsucursal > 0) {
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				if(!$sucursal) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
					echo json_encode($resp);
					exit();
				}
			}
			
			$resp['respuesta'] = 'ok';
			$resp['detalle_comprobantes'] = $this->get_detallecompras($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $periodo_seleccionado, $idsucursal, $tipo_comprobante, $tipo_fecha);
			echo json_encode($resp);
			exit();
		}
	}

	public function get_detallecompras($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $periodo_seleccionado, $idsucursal = 0, $tipo_comprobante = '-1', $tipo_fecha = 'fecha_emision') {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));
		$codigo_regimen = 'M-RER';
        if($sunat_tiporegimen) {
			$codigo_regimen = $sunat_tiporegimen->codigo_libro;
		}

		if($tipo_comprobante == '-1') {
			$array_tipos_docs = " ('01', '03', '07', '08') ";
		} else {
			$array_tipos_docs = " ('".$tipo_comprobante."') ";
		}

		$campo_fecha = 'de.fecha_comprobante';
		if($tipo_fecha == 'fecha_registro') {
			$campo_fecha = 'de.fecha_registro';
		}

		$query = "SELECT de.id_compra, de.fecha_comprobante, de.total_isc, de.total_icbper, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, proveedor.id_tipodocidentidad, proveedor.num_doc, proveedor.razon_social, de.total_gravadas, de.total_igv, de.total_otr_imp, de.total, de.id_codigomoneda, 
		if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv,  de.total_otr_imp, de.total, de.total_gratuitas, de.fecha_comprobante_modifica, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica from compra de INNER JOIN proveedor on de.id_proveedor = proveedor.id_proveedor where de.estado = 'activo' and  de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and ".$campo_fecha." >= CAST(:fecha_inicio AS DATE) and ".$campo_fecha." <= CAST(:fecha_fin AS DATE) ";

		if($idsucursal > 0) {
			$query = $query.' and idsucursal = '.$idsucursal.' ';
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (get_detallecompras) ',  $e->getMessage(), "\n";
			exit();
		}

		$lista = array();
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$n++;

			if(empty($fila->fecha_vto_comprobante)) {
				$fecha_vencimiento = date("d-m-Y", strtotime($fila->fecha_comprobante));
			} else {
				$fecha_vencimiento = date("d-m-Y", strtotime($fila->fecha_vto_comprobante));
			}

			if($fecha_vencimiento == date("d-m-Y", strtotime($fila->fecha_comprobante))) {
				$fecha_vencimiento = '';
			}

			$fm = 1;
			if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
				$fm = 1;
			} else if ($fila->id_tipodoc_electronico == '07') {
				$fm = 1;
			} else if ($fila->id_tipodoc_electronico == '08') {
				$fm = 1;
			}
			
			$factor_tipo_cambio = 1;
			if($fila->tipo_cambio == '') {
				setlocale(LC_MONETARY, 'es_PE');
			} else {
				setlocale(LC_MONETARY, 'es_US');
				$factor_tipo_cambio = floatval($fila->tipo_cambio);
			}

			$fm = $fm*$factor_tipo_cambio;

			$total_icbper = 0;
			if(!empty($fila->total_icbper)){
				$total_icbper = round($fila->total_icbper, 2);
			}


			$mes_periodo_actual = intval(date('m', strtotime($periodo_seleccionado))) + 0;
			$anio_periodo_actual = intval(date('Y', strtotime($periodo_seleccionado))) + 0;

			$mes_periodo_declaracion = $mes_periodo_actual;
			$anio_periodo_declaracion = $anio_periodo_actual;

			$mes_fecha_cpe = intval(date('m', strtotime($fila->fecha_comprobante))) + 0;
			$anio_fecha_cpe = intval(date('Y', strtotime($fila->fecha_comprobante))) + 0;

			$mismo_periodo = 'si';
			if($anio_fecha_cpe == $anio_periodo_declaracion) {
				//nos indica que el CPE se emitió en el mismo año del periodo.
				if($mes_periodo_declaracion == $mes_fecha_cpe) {
					//Aquí nos indica que el CPE se emitió en el Mismo mes del periodo.
					//Sería el estado = 1, ya que el mes es el mismo.
					$mismo_periodo = 'si';
				} else {
					/*
					if($mes_periodo_declaracion - $mes_periodo_declaracion == 1) {
						//Esto indicaría que el comprobante es del mes anterior.

					}
					*/
					//Si el mes es diferente, entonces se debe entender que la fecha es anterior.
					$mismo_periodo = 'no';
				}
			} else {
				//si el año es diferente, entonces se entiende que el mes es diferente, y por tanto debe ser de un mes pasado.
				$mismo_periodo = 'no';
				/*
				if($anio_periodo_declaracion - $anio_fecha_cpe == 1) {
					//esto indicaría que el comprobante es del año pasado.

				}
				*/
				$diferencia_meses = $this->fecha_diferencia_meses($fila->fecha_comprobante, $periodo_seleccionado);
				if($diferencia_meses >= 12) {
					$mismo_periodo = 'sin_derecho_a_credito_fiscal';
				}
			}
			
			$estado_comprobante_sunat = 1;
			if($mismo_periodo == 'sin_derecho_a_credito_fiscal') {
				//si ingresa aquí, es porque el comprobante ha sido emitido hace más de 12 meses, por lo tanto así sea factura o boletas no tiene derecho a crédito fiscal, por tanto el estado debe ser 7.
				$estado_comprobante_sunat = 7;
			} else {
				if($fila->id_tipodoc_electronico == '01') {
					if($mismo_periodo == 'si') {
						$estado_comprobante_sunat = 1;
					} else {
						$estado_comprobante_sunat = 6;
					}
				} else if($fila->id_tipodoc_electronico == '03') {
					if($mismo_periodo == 'si') {
						$estado_comprobante_sunat = 0;
					} else {
						$estado_comprobante_sunat = 7;
					}
				} else if($fila->id_tipodoc_electronico == '07' || $fila->id_tipodoc_electronico == '08') {
					if($fila->id_tipo_comprobante_modifica == '01') {
						//notas que modifican una factura
						if($mismo_periodo == 'si') {
							$estado_comprobante_sunat = 1;
						} else {
							$estado_comprobante_sunat = 6;
						}
					} else {
						//notas que modifican una boleta
						if($mismo_periodo == 'si') {
							$estado_comprobante_sunat = 0;
						} else {
							$estado_comprobante_sunat = 7;
						}
					}
				}
			}

			$total_gravadas = round($fm*$fila->total_gravadas, 2);
			$total_igv = round($fm*$fila->total_igv, 2);
			$total_no_gravados = round($fm*$fila->total_inafecta + $fm*$fila->total_exoneradas + $fm*$fila->total_gratuitas + $fm*$fila->total_exportacion, 2);
			$total_isc = round($fm*$fila->total_isc, 2);
			
			$total_icbper = number_format((float)($fm*$total_icbper), 2, '.', '');
			$total_otr_imp = round(($fm*$fila->total_otr_imp), 2);
			$total = round($fm*$fila->total, 2);

			if($estado_comprobante_sunat == 7 || $estado_comprobante_sunat == 0) {
				//si ingresa aquí hablamos de un comprobante que no da derecho a crédito fiscal, por tanto sus subtotales todos deben ser cero
				$total_gravadas = 0;
				$total_igv = 0;
				$total_no_gravados = round($fm*$fila->total, 2);
				$total_isc = 0;
				
				$total_icbper = 0;
				$total_otr_imp = 0;
				$total = round($fm*$fila->total, 2);
			}
			
			//tablas actualizadas: Estructuras PLE: https://emprender.sunat.gob.pe/estructurasple

			$lista[] = array(
				'periodo' 					=> date("Ym", strtotime($periodo_seleccionado)).'00', //1
				'codigo_unico' 				=> $n, //date("Ymd", strtotime($fila->fecha_comprobante)).'-'.$fila->id_compra, //2
				'regimen' 					=> $codigo_regimen,  //3

				//Datos del Comprobante
				'fecha_comprobante' 		=> date("d/m/Y", strtotime($fila->fecha_comprobante)),  //4
				'fecha_vencimiento' 		=> $fecha_vencimiento,  //5
				'id_tipodoc_electronico' 	=> $fila->id_tipodoc_electronico,  //6
				'serie_comprobante' 		=> $fila->serie_comprobante,  //7
				'dua_dsi' 					=> '', //8
				'numero_comprobante' 		=> $fila->numero_comprobante, //9
				'odncf'						=> '', //10

				//Datos del proveedor
				'id_tipodocidentidad' 		=> $fila->id_tipodocidentidad,  //11
				'num_doc' 					=> $fila->num_doc,  //12
				'razon_social' 				=> $fila->razon_social, //13

				'total_gravadas' 			=> $total_gravadas, //14
				'total_igv' 				=> $total_igv,  //15
				'saldo_exportacion' 		=> 0, //16
				'imp_prom_mun' 				=> 0, //17
				'op_grav_no_cf' 			=> 0, //18
				'mont_imp_prom_mun' 		=> 0, //19
				'total_no_gravados' 		=> $total_no_gravados, //20
				'total_isc' 				=> $total_isc, //21
				
				'total_icbper' 				=> $total_icbper, //22
				'total_otr_imp' 			=> $total_otr_imp,  //23
				'total' 					=> $total, //24
				'moneda' 					=> 'PEN', //empty($fila->tipo_cambio)?'':$fila->id_codigomoneda, //25
				'tipo_cambio' 				=> empty($fila->tipo_cambio)?'1.000':$fila->tipo_cambio, //26
				
				'fecha_comprobante_modifica' => empty($fila->fecha_comprobante_modifica)?'':date("d/m/Y", strtotime($fila->fecha_comprobante_modifica)), //27
				'id_tipo_comprobante_modifica' => $fila->id_tipo_comprobante_modifica, //28
				'serie_documento_modifica' 	=> empty($fila->serie_documento_modifica)?'-':$fila->serie_documento_modifica, //29
				'dua_doc_modifica' 			=> '', //30
				'nro_documento_modifica' 	=> (intval($fila->nro_documento_modifica) + 0) == 0?'':(intval($fila->nro_documento_modifica) + 0), //31
				
				'fecha_detraccion' 			=> '', //32
				'numero_detraccion' 		=> '', //33
				'marc_comp_retenc' 			=> '', //34
				'clasif_bienes_serv' 		=> '', //35

				'id_contr' 					=> '', //36
				'error_tc' 					=> '', //37
				'error_prov_n_h' 			=> '', //38
				'error_prov_ex' 			=> '', //39
				'error_dni' 				=> '', //40
				'comp_m_p' 					=> '', //41: 1. Consignar "1" si el comprobante de pago fue cancelado con algún medio de pago establecido en la tabla 1; en caso contrario no consignar nada.
				'estado' 					=> $estado_comprobante_sunat, //42: 1. Obligatorio.  2. Registrar '0' cuando el comprobante de pago o documento no da derecho al crédito fiscal. 3. Registrar '1' cuando se anota el comprobante de pago o documento en el periodo que se emitió o que se pagó el impuesto, según  corresponda, y da derecho al crédito fiscal.  4. Registrar '6' cuando la fecha de emisión del comprobante de pago o de pago del impuesto, por operaciones que dan derecho a crédito fiscal, es anterior al periodo de anotación y esta se produce dentro de los doce meses siguientes a la emisión o  pago del impuesto, según corresponda. 5. Registrar '7' cuando la fecha de emisión del comprobante de pago o pago del impuesto, por operaciones que no dan derecho a crédito fiscal, es anterior al periodo de anotación y esta se produce luego de los doce meses siguientes a la emisión o pago del impuesto, según corresponda. 6. Registrar '9' cuando se realice un ajuste o rectificación en la anotación de la información de una operación registrada en un periodo anterior.
				'campo_libre_1' 				=> '', //43
			);

			//tablas actualizadas: Estructuras PLE: https://emprender.sunat.gob.pe/estructurasple
		}

		return $lista;
	}

	public function fecha_diferencia_meses($desde, $hasta) { //formato variables: YYYY-m-d
		$desde = date_create($desde);
		$hasta = date_create($hasta);
		
		$diferencia = date_diff($desde, $hasta);
		return array(
			'anios'	=> $diferencia->format('%y'),
			'meses'	=> $diferencia->format('%m'),
			'dias'	=> $diferencia->format('%d')
		);
	}

	public function detalledocumentosemitidos($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $idsucursal = 0, $tipo_comprobante = '-1') {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));
		$codigo_regimen = 'M-RER';
        if($sunat_tiporegimen) {
			$codigo_regimen = $sunat_tiporegimen->codigo_libro;
		}

		if($tipo_comprobante == '-1') {
			$array_tipos_docs = " ('01', '03', '07', '08') ";
		} else {
			$array_tipos_docs = " ('".$tipo_comprobante."') ";
		}

		//Extraer Totales
		$query = "SELECT de.fecha_comprobante, de.id_tipo_operacion, de.id_cod_tipomotivo_credito, de.id_cod_tipomotivo_debito, de.total_isc, de.total_icbper, de.total_otr_imp, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) ";

		//$query = 'SELECT de.fecha_comprobante, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = '.$id_contribuyente.' and tipo_envio_sunat = '.$tipo_envio_sunat.' and CAST(de.fecha_comprobante AS DATE) >= CAST('.$fecha_inicio.' AS DATE) and CAST(de.fecha_comprobante AS DATE) <= CAST('.$fecha_fin.' AS DATE);';
		//echo $query;
		//exit();

		if($idsucursal > 0) {
			$query = $query.' and de.id_sucursal = '.$idsucursal.' ';
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (detalledocumentosemitidos) ',  $e->getMessage(), "\n";
			exit();
		}

		$lista = array();
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$n++;
			if($fila->fecha_comprobante == $fila->fecha_vto_comprobante || empty($fila->fecha_vto_comprobante)) {
				$fecha_vencimiento = '';
			} else {
				$fecha_vencimiento = date("d/m/Y", strtotime($fila->fecha_vto_comprobante));
			}

			$estado_sunat = 1;
			$estado_sire = 1;
			$fm = 1; //Factor de Multiplicación
			if($fila->estado_envio_sunat == 'anulado') {
				$fm = 0;
				$estado_sunat = 2;
				$estado_sire = 2;
			} else if ($fila->estado_envio_sunat == 'rechazado') {
				$fm = 2;
				$estado_sunat = 0;
				$estado_sire = 2;
			} else if ($fila->estado_envio_sunat == 'pendiente') {
				if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
					$fm = 1;
				} else if ($fila->id_tipodoc_electronico == '07') {
					$fm = -1;
				} else if ($fila->id_tipodoc_electronico == '08') {
					$fm = 1;
				}
				$estado_sire = 2;
			} else if ($fila->estado_envio_sunat == 'aceptado') {
				if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
					$fm = 1;
				} else if ($fila->id_tipodoc_electronico == '07') {
					$fm = -1;
				} else if ($fila->id_tipodoc_electronico == '08') {
					$fm = 1;
				}
				$estado_sire = 1;
			} else {
				$fm = 1;
				$estado_sire = 2;
			}
			
			//$tipo_cambio = '-';
			$factor_tipo_cambio = 1;
			if($fila->tipo_cambio == '') {
				setlocale(LC_MONETARY, 'es_PE');
			} else {
				setlocale(LC_MONETARY, 'es_US');
				$factor_tipo_cambio = floatval($fila->tipo_cambio);
			}

			$fm = $fm*$factor_tipo_cambio;

			$fecha_documento_modificado = '';
			if(!empty($fila->id_tipo_comprobante_modifica) && !empty($fila->serie_documento_modifica) && !empty($fila->nro_documento_modifica)) {
				$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipo_comprobante_modifica, 'serie_comprobante' => $fila->serie_documento_modifica, 'numero_comprobante' => $fila->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				if($documento_modificado) {
					$fecha_documento_modificado = date("d/m/Y", strtotime($documento_modificado->fecha_comprobante));
				}
			}

			$cliente_num_doc_identidad = $fila->num_doc;
			if($fila->id_tipodocidentidad == '0') {
				if(strpos($fila->num_doc, 'sdi') !== false){
					$cliente_num_doc_identidad = '0';
				}
			}
			
			//ESTE CÓDIGO SE UTILIZARÁ EN CASO EL CLIENTE UTILICE GRATUITAS Y QUIERA UN DESGLOSE
			//REVISAR: E:\Dropbox\CARPETAPRIVADA\FacturalaYa SRL\Equipo facturalaya.com\REGISTRO TRANSFERENCIAS GRATUITAS EN REGISTRO DE VENTAS.xlsx
			/*
			$item_total_gravadas = 0;
			$item_total_gravadas_igv = 0;
			$item_total_inafecta = 0;
			$array_gratuita_afectaigv = array('11', '12', '13', '14', '15', '16');
			$array_gratuita_inafecta = array('31', '32', '33', '34', '35', '36');
			if($fila->total_gratuitas > 0) {
				$lista_items_detalle = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				
				foreach($lista_items_detalle as $itemdetalle) {
					if(in_array($itemdetalle->id_tipoafectacionigv, $array_gratuita_afectaigv)) {
						$item_total_gravadas = $item_total_gravadas + round($itemdetalle->importe/1.18, 2);
						$item_total_gravadas_igv = $item_total_gravadas_igv + round($itemdetalle->importe - $itemdetalle->importe/1.18, 2);
					} else if(in_array($itemdetalle->id_tipoafectacionigv, $array_gratuita_inafecta)) {
						$item_total_inafecta = $item_total_inafecta + $itemdetalle->importe;
					}
				}
			}
			*/

			$total_icbper = 0;
			if(!empty($fila->total_icbper)){
				$total_icbper = round($fila->total_icbper, 2);
			}

			$cliente_id_tipodocidentidad = $fila->id_tipodocidentidad;
			$cliente_razon_social = $fila->razon_social;
			if($fila->estado_envio_sunat == 'anulado' || $fila->estado_envio_sunat == 'rechazado') {
				$cliente_id_tipodocidentidad = '';
				$cliente_num_doc_identidad = '';
				$cliente_razon_social = '';
			}

			$id_motivo_nota = '';
			if($fila->id_tipodoc_electronico == '07') {
				$id_motivo_nota = $fila->id_cod_tipomotivo_credito;
			} else if($fila->id_tipodoc_electronico == '08') {
				$id_motivo_nota = $fila->id_cod_tipomotivo_debito;
			}

			$anticipo_total_gravadas = 0;
			$anticipo_total_inafecta = 0;
			$anticipo_total_exoneradas = 0;
			$anticipo_total_gratuitas = 0;
			$anticipo_total_exportacion = 0;
			$anticipo_total_icbper = 0;
			$anticipo_sub_total = 0;
			$anticipo_impuesto_icbper = 0;
			$anticipo_total_igv = 0;
			$anticipo_total_isc = 0;
			$anticipo_total_otr_imp = 0;
			$anticipo_total = 0;

			if($fila->id_tipodoc_electronico == '01') {
				$anticipos = $this->get_anticipos(
					array(
					'id_contribuyente' 	=> $id_contribuyente, 
					'tipo_comprobante' 	=> $fila->id_tipodoc_electronico, 
					'serie_doc' 		=> $fila->serie_comprobante, 
					'numero_doc' 		=> $fila->numero_comprobante, 
					'tipo_envio_sunat' 	=> $contribuyente->tipo_envio_sunat)
				);

				$anticipo_total_gravadas 		= $anticipos['totales']['total_gravadas'];
				$anticipo_total_inafecta 		= $anticipos['totales']['total_inafecta'];
				$anticipo_total_exoneradas 		= $anticipos['totales']['total_exoneradas'];
				$anticipo_total_gratuitas 		= $anticipos['totales']['total_gratuitas'];
				$anticipo_total_exportacion 	= $anticipos['totales']['total_exportacion'];
				$anticipo_total_icbper 			= $anticipos['totales']['total_icbper'];
				$anticipo_sub_total 			= $anticipos['totales']['sub_total'];
				$anticipo_impuesto_icbper 		= $anticipos['totales']['impuesto_icbper'];
				$anticipo_total_igv 			= $anticipos['totales']['total_igv'];
				$anticipo_total_isc 			= $anticipos['totales']['total_isc'];
				$anticipo_total_otr_imp 		= $anticipos['totales']['total_otr_imp'];
				$anticipo_total 				= $anticipos['totales']['total'];
			}
			
			$lista[] = array(
				'fecha' 			=> date("Ym", strtotime($fila->fecha_comprobante)).'00', //1
				'cod_unico' 					=> $n, //date("Ym", strtotime($fila->fecha_comprobante)).$fila->id_tipodoc_electronico.$fila->serie_comprobante.$fila->numero_comprobante, //2
				'regimen' 						=> $codigo_regimen,  //3

				//Datos del Comprobante
				'fecha_comprobante' 			=> date("d/m/Y", strtotime($fila->fecha_comprobante)),  //4
				'fecha_vencimiento' 			=> $fecha_vencimiento,  //5
				'id_tipodoc_electronico' 		=> $fila->id_tipodoc_electronico,  //6
				'serie_comprobante' 			=> $fila->serie_comprobante,  //7
				'numero_comprobante' 			=> $fila->numero_comprobante, //8
				'num_maq_reg' 					=> '', //9

				//Datos del Cliente
				'cliente_id_tipodocidentidad' 	=> $cliente_id_tipodocidentidad,  //10
				'cliente_num_doc_identidad' 	=> $cliente_num_doc_identidad,  //11
				'cliente_razon_social' 			=> $cliente_razon_social, //12
				
				'total_exportacion' 			=> round($fm*$fila->total_exportacion - $fm*$anticipo_total_exportacion, 2), //13
				'total_gravadas' 				=> round($fm*$fila->total_gravadas - $fm*$anticipo_total_gravadas , 2), //14
				'descuento' 					=> 0, //15
				'total_igv' 					=> round($fm*$fila->total_igv - $fm*$anticipo_total_igv, 2), //16
				'descuento_igv' 				=> 0, //17
				'total_exoneradas' 				=> round($fm*$fila->total_exoneradas - $fm*$anticipo_total_exoneradas, 2), //18
				'total_inafecta' 				=> round($fm*$fila->total_inafecta - $fm*$anticipo_total_inafecta + $fm*$fila->total_gratuitas, 2), //19
				'total_isc' 					=> round($fm*$fila->total_isc - $fm*$anticipo_total_isc, 2), //20
				'op_arroz_pilado' 				=> 0, //21
				'imp_arroz_pilado' 				=> 0, //22
				'icbper' 						=> number_format((float)($fm*$total_icbper) - $fm*$anticipo_total_icbper, 2, '.', ''), //23 
				'otros_tributos' 				=> round(($fm*$fila->total_otr_imp) - $fm*$anticipo_total_otr_imp, 2), //24
				'total' 						=> round($fm*$fila->total - $fm*$anticipo_total, 2), //25
				'moneda' 						=> 'PEN',//empty($fila->tipo_cambio)?'':$fila->id_codigomoneda, //26
				'tipo_cambio' 					=> empty($fila->tipo_cambio)?'1.000':$fila->tipo_cambio, //27
				'fecha_comp_modif' 				=> $fecha_documento_modificado, //28
				'tipo_comp_modif' 				=> $fila->id_tipo_comprobante_modifica, //29
				'serie_comp_modif' 				=> empty($fila->serie_documento_modifica)?'-':$fila->serie_documento_modifica, //30
				'numero_comp_modif' 			=> intval($fila->nro_documento_modifica) + 0, //31

				'id_contr' 						=> '', //32
				'error_tc' 						=> '', //33
				'comp_m_p' 						=> '', //34
				'estado' 						=> $estado_sunat, //35
				'camp_libre_1' 					=> '', //36
				'estado_comprobante' 			=> strtoupper($fila->estado_envio_sunat), //37

				'id_motivo_nota'				=> $id_motivo_nota,
				'tipo_operacion'				=> $fila->id_tipo_operacion,
				'car_sunat'						=> $contribuyente->ruc.$fila->id_tipodoc_electronico.$fila->serie_comprobante.$fila->numero_comprobante,
				'total_gratuitas'				=> $fila->total_gratuitas - $fm*$anticipo_total_gratuitas,
				'estado_sire'					=> $estado_sire
			);
		}
		
		return $lista;
	}

	public function get_anticipos($data) {
		$lista_anticipos = DocElectronicoAnticipo::find(array("
			cpe_id_contribuyente 		= :id_contribuyente: and 
			cpe_id_tipodoc_electronico 	= :id_tipodoc_electronico: and 
			cpe_serie_comprobante 		= :serie_comprobante: and 
			cpe_numero_comprobante 		= :numero_comprobante: and 
			cpe_tipo_envio_sunat 		= :tipo_envio_sunat:
		", 'bind' => array(
			'id_contribuyente' 			=> $data['id_contribuyente'], 
			'id_tipodoc_electronico' 	=> $data['tipo_comprobante'], 
			'serie_comprobante' 		=> $data['serie_doc'], 
			'numero_comprobante' 		=> $data['numero_doc'], 
			'tipo_envio_sunat' 			=> $data['tipo_envio_sunat']
		)));

		$anticipos = array();
		$num_anticipo = 0;
		$sum_anticipos = 0;

		$total_gravadas = 0;
		$total_inafecta = 0;
		$total_exoneradas = 0;
		$total_gratuitas = 0;
		$total_exportacion = 0;
		$total_icbper = 0;
		$total_descuento = 0;
		$porcentaje_descuento_total = 0;
		$sub_total = 0;
		$porcentaje_igv = 0;
		$impuesto_icbper = 0;
		$total_igv = 0;
		$total_isc = 0;
		$total_otr_imp = 0;
		$total = 0;

		foreach($lista_anticipos as $item) {
			$num_anticipo++;
			$anticipos[] = array(
				'num_anticipo' => $num_anticipo,
				'id_tipodoc_electronico' => $item->anticipo_id_tipodoc_electronico,
				'serie_comprobante' => $item->anticipo_serie_comprobante,
				'numero_comprobante' => $item->anticipo_numero_comprobante,
				'monto_anticipo' => $item->monto_anticipo
			);
			
			$doc_anticipo = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $data['id_contribuyente'], 'id_tipodoc_electronico' => $item->anticipo_id_tipodoc_electronico, 'serie_comprobante' => $item->anticipo_serie_comprobante, 'numero_comprobante' => $item->anticipo_numero_comprobante, 'tipo_envio_sunat' => $data['tipo_envio_sunat'])));

			if($doc_anticipo->total != $item->monto_anticipo) {
				$total_gravadas = $total_gravadas + round($item->monto_anticipo/1.18, 2);
				$total_inafecta = $total_inafecta + 0;
				$total_exoneradas = $total_exoneradas + 0;
				$total_gratuitas = $total_gratuitas + 0;
				$total_exportacion = $total_exportacion + 0;
				$total_icbper = $total_icbper + 0;
				$total_descuento = $total_descuento + 0;
				$porcentaje_descuento_total = $porcentaje_descuento_total + 0;
				$subototal_item = round($item->monto_anticipo/1.18, 2);
				$sub_total = $sub_total + $subototal_item;
				$porcentaje_igv = $porcentaje_igv + 0;
				$impuesto_icbper = $impuesto_icbper + 0;
				$total_igv_item = round($item->monto_anticipo - $subototal_item, 2);
				$total_igv = $total_igv + floatval($total_igv_item);
				$total_isc = $total_isc + 0;
				$total_otr_imp = $total_otr_imp + 0;
				$total = $total + floatval($item->monto_anticipo);
	
				$sum_anticipos = $sum_anticipos + floatval($item->monto_anticipo);
			} else {

				$total_gravadas = $total_gravadas + floatval($doc_anticipo->total_gravadas);
				$total_inafecta = $total_inafecta + floatval($doc_anticipo->total_inafecta);
				$total_exoneradas = $total_exoneradas + floatval($doc_anticipo->total_exoneradas);
				$total_gratuitas = $total_gratuitas + floatval($doc_anticipo->total_gratuitas);
				$total_exportacion = $total_exportacion + floatval($doc_anticipo->total_exportacion);
				$total_icbper = $total_icbper + floatval($doc_anticipo->total_icbper);
				$total_descuento = $total_descuento + floatval($doc_anticipo->total_descuento);
				$porcentaje_descuento_total = $porcentaje_descuento_total + floatval($doc_anticipo->porcentaje_descuento_total);
				$sub_total = $sub_total + floatval($doc_anticipo->sub_total);
				$porcentaje_igv = $porcentaje_igv + floatval($doc_anticipo->porcentaje_igv);
				$impuesto_icbper = $impuesto_icbper + floatval($doc_anticipo->impuesto_icbper);
				$total_igv = $total_igv + floatval($doc_anticipo->total_igv);
				$total_isc = $total_isc + floatval($doc_anticipo->total_isc);
				$total_otr_imp = $total_otr_imp + floatval($doc_anticipo->total_otr_imp);
				$total = $total + floatval($doc_anticipo->total);
	
				$sum_anticipos = $sum_anticipos + floatval($item->monto_anticipo);
			}
		}
		
		$resp['lista_anticipos'] = $anticipos;
		$resp['sum_anticipos'] = $sum_anticipos;
		$resp['totales']['total_gravadas'] = $total_gravadas;
		$resp['totales']['total_inafecta'] = $total_inafecta;
		$resp['totales']['total_exoneradas'] = $total_exoneradas;
		$resp['totales']['total_gratuitas'] = $total_gratuitas;
		$resp['totales']['total_exportacion'] = $total_exportacion;
		$resp['totales']['total_icbper'] = $total_icbper;
		$resp['totales']['total_descuento'] = $total_descuento;
		$resp['totales']['porcentaje_descuento_total'] = $porcentaje_descuento_total;
		$resp['totales']['sub_total'] = $sub_total;
		$resp['totales']['porcentaje_igv'] = $porcentaje_igv;
		$resp['totales']['impuesto_icbper'] = $impuesto_icbper;
		$resp['totales']['total_igv'] = $total_igv;
		$resp['totales']['total_isc'] = $total_isc;
		$resp['totales']['total_otr_imp'] = $total_otr_imp;
		$resp['totales']['total'] = $total;
		return $resp;
	}

	public function generarTxtLibventasAction($fecha_inicio, $fecha_fin, $idsucursal = 0, $tipo_comprobante = '-1') {
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
		
		$herramientas = new HerramientasController;
		
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		/*
		if(!$herramientas->validate_date($periodo_seleccionado)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Periodo';
			$resp['mensaje'] = 'El Periodo Seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}
		*/

		
		if(date("m-Y", strtotime($fecha_inicio)) != date("m-Y", strtotime($fecha_fin))) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'No Es Posible Generar el TXT del libro electrónico con fechas que incluyan más de un mes.!';
			echo json_encode($resp);
			exit();
		}

		$tipos_validos_docs = array('-1', '01', '03', '07', '08');
		if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

		$idsucursal = empty($idsucursal)?0:intval($idsucursal) + 0;
		if($idsucursal > 0) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
				echo json_encode($resp);
				exit();
			}
		}
		
		$filename = 'LE'.$contribuyente->ruc.date("Ym", strtotime($fecha_inicio)).'00'.'1401'.'0000'.'1111'.'.txt';

		header("Content-Type: text/plain");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		
		$items_libro = $this->detalledocumentosemitidos($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $idsucursal, $tipo_comprobante);
		foreach($items_libro as $key => $item) {
			echo implode("|",$item)."\r\n";
		}
		exit();
	}

	public function getAsientoContableAction($fecha_inicio, $fecha_fin, $idsucursal = 0, $tipo_comprobante = '-1') {
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
		
		$herramientas = new HerramientasController;
		
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}
		
		if(date("m-Y", strtotime($fecha_inicio)) != date("m-Y", strtotime($fecha_fin))) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'No Es Posible Generar el TXT del libro electrónico con fechas que incluyan más de un mes.!';
			echo json_encode($resp);
			exit();
		}

		$tipos_validos_docs = array('-1', '01', '03', '07', '08');
		if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

		$idsucursal = empty($idsucursal)?0:intval($idsucursal) + 0;
		if($idsucursal > 0) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
				echo json_encode($resp);
				exit();
			}
		}
		
		$resp_asiento_contable_mes = $this->get_asientos_contables_mes($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $idsucursal, $tipo_comprobante);

		$spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Asientos Contables")
            ->setSubject("Asientos Contables")
            ->setDescription(
                "Asientos Contables - Versión BETA"
            )
            ->setKeywords("facturalaya.com, código fuente, plantilla importación, importar cpe")
            ->setCategory("Contabilidad");
            
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Asiento Contables Mensual");
		$spreadsheet->setActiveSheetIndex(0)
					->setCellValue("A1", "Origen")
					->setCellValue("B1", "Num.Voucher")
					->setCellValue("C1", "Fecha")
					->setCellValue("D1", "Cuenta")
					->setCellValue("E1", "Monto Debe")
					->setCellValue("F1", "Monto Haber")
					->setCellValue("G1", "Moneda S/D")
					->setCellValue("H1", "T.Cambio")
					->setCellValue("I1", "Doc")
					->setCellValue("J1", "Num.Doc")
					->setCellValue("K1", "Fec.Doc")
					->setCellValue("L1", "Fec.Ven")
					->setCellValue("M1", "Cod.Prov.Clie") //ruc
					->setCellValue("N1", "C.Costo")
					->setCellValue("O1", "Presupuesto")
					->setCellValue("P1", "F.Efectivo")   //NO
					->setCellValue("Q1", "Glosa")
					->setCellValue("R1", "Libro C/V/R") //Siempre V
					->setCellValue("S1", "Mto.Neto1")    //Neto sin IGV
					->setCellValue("T1", "Mto.Neto2")    //Venta exportacion
					->setCellValue("U1", "Mto.Neto3")    //NO
					->setCellValue("V1", "Mto.Neto4")    //Base imponible venta inafecta
					->setCellValue("W1", "Mto.Neto5")    //NO
					->setCellValue("X1", "Mto.Neto6")    //Base imponible venta exonerada
					->setCellValue("Y1", "Mto.Neto7")    //Otros tributos
					->setCellValue("Z1", "Mto.Neto8")    //ISC
					->setCellValue("AA1", "Mto.Neto9")    //ISC
					->setCellValue("AB1", "Mto.IGV")     //IGV
					->setCellValue("AC1", "Ref.Doc")
					->setCellValue("AD1", "Ref.Num.Doc")
					->setCellValue("AE1", "Ref.Fecha")
					->setCellValue("AF1", "D.Numero")    //NO
					->setCellValue("AG1", "D.Fecha")     //NO
					->setCellValue("AH1", "RUC")
					->setCellValue("AI1", "R.Social")
					->setCellValue("AJ1", "Tipo")
					->setCellValue("AK1", "Tip.Doc.Iden")
					->setCellValue("AL1", "Medio de Pago")
					->setCellValue("AM1", "Apellido 1")
					->setCellValue("AN1", "Apellido 2")
					->setCellValue("AO1", "Nombre")
					->setCellValue("AP1", "T.Bien");

		$asiento_contable = $resp_asiento_contable_mes['asiento_contable'];
		for($i=1; $i<=count($asiento_contable); $i++) {
			$fila = (object)$asiento_contable['n'.$i];
			
			$spreadsheet->getActiveSheet()->fromArray(array(
				/*A*/$fila->origen,
				/*B*/$fila->num_voucher,
				/*C*/$fila->fecha,
				/*D*/$fila->cuenta,
				/*E*/floatval($fila->monto_debe),
				/*F*/floatval($fila->monto_haber),
				/*G*/$fila->moneda_s_d,
				/*H*/floatval($fila->tipo_cambio),
				/*I*/$fila->doc,
				/*J*/$fila->num_doc,
				/*K*/$fila->fec_doc,
				/*L*/$fila->fec_ven,
				/*M*/$fila->cod_prov_clie,
				/*N*/$fila->c_costo,
				/*O*/$fila->presupuesto,
				/*P*/$fila->f_efectivo,
				/*Q*/$fila->glosa,
				/*R*/$fila->libro_c_v_r,
				/*S*/floatval($fila->mto_neto1),
				/*T*/floatval($fila->mto_neto2),
				/*U*/floatval($fila->mto_neto3),
				/*V*/floatval($fila->mto_neto4),
				/*W*/floatval($fila->mto_neto5),
				/*X*/floatval($fila->mto_neto6),
				/*Y*/floatval($fila->mto_neto7),
				/*Z*/floatval($fila->mto_neto8),
				/*AA*/floatval($fila->mto_neto9),
				/*AB*/floatval($fila->mto_igv),
				/*AC*/$fila->ref_doc,
				/*AD*/$fila->ref_num_doc,
				/*AE*/$fila->ref_fecha,
				/*AF*/$fila->d_numero,
				/*AG*/$fila->d_fecha,
				/*AH*/$fila->ruc,
				/*AI*/$fila->r_social,
				/*AJ*/$fila->tipo,
				/*AK*/$fila->tipo_doc_iden,
				/*AL*/$fila->medio_pago,
				/*AM*/$fila->apellido_1,
				/*AN*/$fila->apellido_2,
				/*AO*/$fila->nombre,
				/*AP*/$fila->t_bien,
			), null, 'A'.($i+1));

			/*
			//NO SE DEBE UTILIZAR ESTE CÓDIGO PORQUE SE CUELGA CUANDO SE TRABAJA CON MUCHAS FILAS
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.($i+1), $fila->origen, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('B'.($i+1), $fila->num_voucher, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('C'.($i+1), $fila->fecha, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('D'.($i+1), $fila->cuenta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('E'.($i+1), floatval($fila->monto_debe), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('F'.($i+1), floatval($fila->monto_haber), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('G'.($i+1), $fila->moneda_s_d, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('H'.($i+1), floatval($fila->tipo_cambio), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('I'.($i+1), $fila->doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('J'.($i+1), $fila->num_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('K'.($i+1), $fila->fec_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('L'.($i+1), $fila->fec_ven, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('M'.($i+1), $fila->cod_prov_clie, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('N'.($i+1), $fila->c_costo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('O'.($i+1), $fila->presupuesto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('P'.($i+1), $fila->f_efectivo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Q'.($i+1), $fila->glosa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('R'.($i+1), $fila->libro_c_v_r, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('S'.($i+1), floatval($fila->mto_neto1), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('T'.($i+1), floatval($fila->mto_neto2), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('U'.($i+1), floatval($fila->mto_neto3), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('V'.($i+1), floatval($fila->mto_neto4), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('W'.($i+1), floatval($fila->mto_neto5), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('X'.($i+1), floatval($fila->mto_neto6), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('Y'.($i+1), floatval($fila->mto_neto7), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('Z'.($i+1), floatval($fila->mto_neto8), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('AA'.($i+1), floatval($fila->mto_neto9), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('AB'.($i+1), floatval($fila->mto_igv), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
				->setCellValueExplicit('AC'.($i+1), $fila->ref_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AD'.($i+1), $fila->ref_num_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AE'.($i+1), $fila->ref_fecha, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AF'.($i+1), $fila->d_numero, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AG'.($i+1), $fila->d_fecha, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AH'.($i+1), $fila->ruc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AI'.($i+1), $fila->r_social, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AJ'.($i+1), $fila->tipo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AK'.($i+1), $fila->tipo_doc_iden, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AL'.($i+1), $fila->medio_pago, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AM'.($i+1), $fila->apellido_1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AN'.($i+1), $fila->apellido_2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AO'.($i+1), $fila->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AP'.($i+1), $fila->t_bien, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            	;

			*/
		}
		
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AE')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AF')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AG')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AH')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AI')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AJ')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AK')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AL')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AM')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AN')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AO')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AP')->setAutoSize(true);
		
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AP1')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AP1')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AP1')->getFont()->setBold(true);

		//$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AP1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF87CEFA');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AP1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8CCE4');
		$spreadsheet->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(25, 'pt');
		
		$nombre_archivo = 'Asiento Contable';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function get_asientos_contables_mes($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $idsucursal = 0, $tipo_comprobante = '-1') {

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));
		$codigo_regimen = 'M-RER';
        if($sunat_tiporegimen) {
			$codigo_regimen = $sunat_tiporegimen->codigo_libro;
		}

		if($tipo_comprobante == '-1') {
			$array_tipos_docs = " ('01', '03', '07', '08') ";
		} else {
			$array_tipos_docs = " ('".$tipo_comprobante."') ";
		}

		$herramientas = new HerramientasController;

		//$query = "SELECT de.fecha_comprobante, de.total_isc, de.total_icbper, de.total_otr_imp, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio_sunat, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.porcentaje_igv, de.total_descuento, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and CAST(de.fecha_comprobante AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_comprobante AS DATE) <= CAST(:fecha_fin AS DATE) ";

		$query = "SELECT de.fecha_comprobante, de.total_isc, de.total_icbper, de.total_otr_imp, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, de.tipo_cambio_sunat, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.porcentaje_igv, de.total_descuento, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) ";

		if($idsucursal > 0) {
			$query = $query.' and de.id_sucursal = '.$idsucursal.' ';
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (get_asientos_contables_mes) ',  $e->getMessage(), "\n";
			exit();
		}

		$nro_orden = 0;
		$vounum = 0;
		$ctaini = "12121";
        $ctacuarenta = "40111";
		$asientos_contables_mes = array();
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			
			$vouorigen = '02'; //código interno, siempre será '02'
			$vounum++; //código único por cada iteración del bucle.

			$fec_emi = date("d/m/Y", strtotime($fila->fecha_comprobante));
			$fec_vto = date("d/m/Y", strtotime($fila->fecha_vto_comprobante));
			$cli_numdoc = $fila->num_doc;
			$cli_tipodoc = $fila->id_tipodocidentidad;
			$cli_razsoc = $fila->razon_social;

			if ($cli_tipodoc == 1) {
				$resp_separacion = $herramientas->separar_nombre_apellido($cli_razsoc);
				$cli_apellido1 = $resp_separacion['paterno'];
				$cli_apellido2 = $resp_separacion['materno'];
				$cli_nombres = $resp_separacion['nombres'];
			} else {
				$cli_apellido1 = "";
				$cli_apellido2 = "";
				$cli_nombres = "";
			}

			$fecha_documento_modificado = '';
			if(!empty($fila->id_tipo_comprobante_modifica) && !empty($fila->serie_documento_modifica) && !empty($fila->nro_documento_modifica)) {
				$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipo_comprobante_modifica, 'serie_comprobante' => $fila->serie_documento_modifica, 'numero_comprobante' => $fila->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				if($documento_modificado) {
					$fecha_documento_modificado = date("d/m/Y", strtotime($documento_modificado->fecha_comprobante));
				}
			}

			$fecheref = "";
			if ($fila->id_tipodoc_electronico == '07') {
				$debeini = '';
				$haberini = $fila->total;
				$debecuarenta = $fila->total_igv;
				$habercuarenta = '';
				$fec_vto = $fec_emi;
				
				$fecheref = $fecha_documento_modificado;
				//bolsas
				$debebolsas = $fila->total_icbper;
				$haberbolsas = '';
			} else {
				if ($fila->id_tipodoc_electronico == '08') {
					$fecheref = $fecha_documento_modificado;
				}

				$debeini = $fila->total;
				$haberini = '';
				$debecuarenta = '';
				$habercuarenta = $fila->total_igv;
				//bolsas
				$debebolsas = '';
				$haberbolsas = $fila->total_icbper;
			}
			
			$moneda = 'S';
			$ctaxcobrar = '12121';

			if($fila->id_codigomoneda == 'USD') {
				$moneda = 'D';
				$ctaxcobrar = '12122';
			}
			
			//primer registro necesario para determinar los totales.
			$primer_registro = array();
			if($fila->estado_envio_sunat != 'anulado') {
				$tipoai = 2; //codigo 2: cliente (cuando el comprobante esté anulado será 0)
				$primer_registro = array(
					'origen'			=> $vouorigen,				//1
					'num_voucher'		=> $vounum,					//2
					'fecha'				=> $fec_emi, //Fecha Emisión	//3
					'cuenta'			=> $ctaxcobrar,					//4
					'monto_debe'		=> $debeini,					//5
					'monto_haber'		=> $haberini,					//6
					'moneda_s_d'		=> $moneda,						//7
					'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
					'doc'				=> $fila->id_tipodoc_electronico,	//9
					'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
					'fec_doc'			=> $fec_emi,	//11
					'fec_ven'			=> $fec_vto,	//12
					'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
					'c_costo'			=> '',						//14
					'presupuesto'		=> '',						//15
					'f_efectivo'		=> '',	//NO				//16
					'glosa'				=> 'Ingresos',				//17
					'libro_c_v_r'		=> 'V',	//Siempre V			//18
					'mto_neto1'			=> '',	//Neto sin IGV		//19
					'mto_neto2'			=> '',	//Venta exportacion		//20
					'mto_neto3'			=> '',	//NO					//21
					'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
					'mto_neto5'			=> '',	//NO								//23
					'mto_neto6'			=> '',	//Base imponible venta exonerada	//24
					'mto_neto7'			=> '',	//Otros tributos					//25
					'mto_neto8'			=> '',	//ISC								//26
					'mto_neto9'			=> '',	//ISC								//27
					'mto_igv'			=> '',	//IGV								//28
					'ref_doc'			=> '',										//29
					'ref_num_doc'		=> '',										//30
					'ref_fecha'			=> '',										//31
					'd_numero'			=> '',	//NO								//32
					'd_fecha'			=> '',	//NO								//33
					'ruc'				=> $cli_numdoc,								//34
					'r_social'			=> $cli_razsoc,								//35
					'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
					'tipo_doc_iden'		=> $cli_tipodoc,							//37
					'medio_pago'		=> '',										//38
					'apellido_1'		=> $cli_apellido1,							//39
					'apellido_2'		=> $cli_apellido2,							//40
					'nombre'			=> $cli_nombres,							//41
					't_bien'			=> '',										//42
				);

				$nro_orden++;
				$asientos_contables_mes['n'.$nro_orden] = $primer_registro;
			}

			$total_gravadas = $fila->total_gravadas;
			$total_exportacion = $fila->total_exportacion;
			$total_inafecta = $fila->total_inafecta;
			$total_exoneradas = $fila->total_exoneradas;
			$total_otr_imp = $fila->total_icbper;
			$total_isc = $fila->total_isc;
			$total_igv = $fila->total_igv;

			if($fila->estado_envio_sunat == 'anulado') {
				$cli_numdoc = '00000000000';
				$habercuarenta = '0';
				$cli_razsoc = "DOCUMENTO ANULADO";

				$tipoai = '0';
				$cli_tipodoc = '0';

				$cli_apellido1 = '';
				$cli_apellido2 = '';
				$cli_nombres = '';

				$total_gravadas = '0';
				$total_exportacion = '0';
				$total_inafecta = '0';
				$total_exoneradas = '0';
				$total_otr_imp = '0';
				$total_isc = '0';
				$total_igv = '0';
			}

			//Segundo registro solo si no existen operaciones gratuitas
			$segundo_registro = array();
			if(empty($fila->total_gratuitas) || $fila->total_gratuitas == 0) {
				$tipoai = 2; //codigo 2: cliente (cuando el comprobante esté anulado será 0)
				$segundo_registro = array(
					'origen'			=> $vouorigen,				//1
					'num_voucher'		=> $vounum,					//2
					'fecha'				=> $fec_emi, //Fecha Emisión	//3
					'cuenta'			=> $ctacuarenta,					//4
					'monto_debe'		=> $debecuarenta,					//5
					'monto_haber'		=> $habercuarenta,					//6
					'moneda_s_d'		=> $moneda,						//7
					'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
					'doc'				=> $fila->id_tipodoc_electronico,	//9
					'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
					'fec_doc'			=> $fec_emi,	//11
					'fec_ven'			=> $fec_vto,	//12
					'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
					'c_costo'			=> '',						//14
					'presupuesto'		=> '',						//15
					'f_efectivo'		=> '',	//NO				//16
					'glosa'				=> 'Ingresos',				//17
					'libro_c_v_r'		=> 'V',	//Siempre V			//18
					'mto_neto1'			=> $total_gravadas,	//Neto sin IGV		//19
					'mto_neto2'			=> $total_exportacion,	//Venta exportacion		//20
					'mto_neto3'			=> '',	//NO					//21
					'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
					'mto_neto5'			=> $total_exoneradas,	//NO				//23
					'mto_neto6'			=> $total_inafecta,	//Base imponible venta exonerada	//24
					'mto_neto7'			=> '',	//Otros tributos					//25
					'mto_neto8'			=> $total_isc,	//ISC								//26
					'mto_neto9'			=> $total_otr_imp,	//ISC								//27
					'mto_igv'			=> $total_igv,	//IGV								//28
					'ref_doc'			=> $fila->id_tipo_comprobante_modifica,										//29
					'ref_num_doc'		=> $fila->serie_documento_modifica . "-" . $fila->nro_documento_modifica,										//30
					'ref_fecha'			=> $fecheref,										//31
					'd_numero'			=> '',	//NO								//32
					'd_fecha'			=> '',	//NO								//33
					'ruc'				=> $cli_numdoc,								//34
					'r_social'			=> $cli_razsoc,								//35
					'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
					'tipo_doc_iden'		=> $cli_tipodoc,							//37
					'medio_pago'		=> '',										//38
					'apellido_1'		=> $cli_apellido1,							//39
					'apellido_2'		=> $cli_apellido2,							//40
					'nombre'			=> $cli_nombres,							//41
					't_bien'			=> '',										//42
				);

				$nro_orden++;
				$asientos_contables_mes['n'.$nro_orden] = $segundo_registro;
			}

			//bolsas
			//Tercer registro, se crea únicamente si existen otros impuestos como el de las bolsas.
			$tercer_registro = array();
			if ($total_otr_imp > 0) {
				$tipoai = 2; //codigo 2: cliente (cuando el comprobante esté anulado será 0)
				$tercer_registro = array(
					'origen'			=> $vouorigen,				//1
					'num_voucher'		=> $vounum,					//2
					'fecha'				=> $fec_emi, //Fecha Emisión	//3
					'cuenta'			=> '40189',					//4
					'monto_debe'		=> $debebolsas,					//5
					'monto_haber'		=> $haberbolsas,					//6
					'moneda_s_d'		=> $moneda,						//7
					'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
					'doc'				=> $fila->id_tipodoc_electronico,	//9
					'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
					'fec_doc'			=> $fec_emi,	//11
					'fec_ven'			=> $fec_vto,	//12
					'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
					'c_costo'			=> '',						//14
					'presupuesto'		=> '',						//15
					'f_efectivo'		=> '',	//NO				//16
					'glosa'				=> 'Ingresos',				//17
					'libro_c_v_r'		=> 'V',	//Siempre V			//18
					'mto_neto1'			=> '',	//Neto sin IGV		//19
					'mto_neto2'			=> '',	//Venta exportacion		//20
					'mto_neto3'			=> '',	//NO					//21
					'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
					'mto_neto5'			=> '',	//NO				//23
					'mto_neto6'			=> '',	//Base imponible venta exonerada	//24
					'mto_neto7'			=> '',	//Otros tributos					//25
					'mto_neto8'			=> '',	//ISC								//26
					'mto_neto9'			=> '',	//ISC								//27
					'mto_igv'			=> '',	//IGV								//28
					'ref_doc'			=> '',										//29
					'ref_num_doc'		=> '',										//30
					'ref_fecha'			=> '',										//31
					'd_numero'			=> '',	//NO								//32
					'd_fecha'			=> '',	//NO								//33
					'ruc'				=> $cli_numdoc,								//34
					'r_social'			=> $cli_razsoc,								//35
					'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
					'tipo_doc_iden'		=> $cli_tipodoc,							//37
					'medio_pago'		=> '',										//38
					'apellido_1'		=> $cli_apellido1,							//39
					'apellido_2'		=> $cli_apellido2,							//40
					'nombre'			=> $cli_nombres,							//41
					't_bien'			=> '',										//42
				);

				$nro_orden++;
				$asientos_contables_mes['n'.$nro_orden] = $tercer_registro;
			}

			//manejo para montos gratuitos
			$igvpct = $fila->porcentaje_igv;
			$igvcte = $igvpct / 100;
			$igvval = $igvcte + 1;

			$gratis_gravado  = array("11", "12", "13", "14", "15", "16");
			$gratis_inafecto = array("31", "32", "33", "34", "35", "36");
			$venta_afecta = array("10", "20", "30", "40");
			
			//$documentox = "s" . $fila->id_tipodoc_electronico . $fila->serie_comprobante . "_" . $fila->numero_comprobante;
			//$documentoc = "c" . $fila->id_tipodoc_electronico . $fila->serie_comprobante . "_" . $fila->numero_comprobante;
			//$documentoci = "ci" . $fila->id_tipodoc_electronico . $fila->serie_comprobante . "_" . $fila->numero_comprobante;

			if($fila->estado_envio_sunat != 'anulado') {

				$lista_items_detalle = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				
				//inicio foreach
				foreach($lista_items_detalle as $item) {
					$cta_contable = '';
					$cta_centrocto = '';
					$cta_presupuesto = '';

					$producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto, 'id_contribuyente' => $item->id_contribuyente)));
					$categoria = Categoria::findFirst(array("id_contribuyente = :id_contribuyente: and idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $producto->id_categoria, 'id_contribuyente' => $item->id_contribuyente)));

					if($categoria) {
						$cta_contable = $categoria->codigo_cuenta_contable;
						$cta_centrocto = $categoria->codigo_centro_costo;
						$cta_presupuesto = $categoria->codigo_presupuesto;
					}

					//operaciones gratuitas
					if (in_array($item->id_tipoafectacionigv, $gratis_gravado)) {
						$valsesenta = ($item->importe / $igvval) * $igvcte;
						$valsesenta = round($valsesenta, 2);
						//$$documentox += $valsesenta;
						$valcuarenta = $item->importe / $igvval;
						$valcuarenta = round($valcuarenta, 2);
						//$$documentoc += $valcuarenta;
					}

					//Gratuitas inafecto
					if (in_array($item->id_tipoafectacionigv, $gratis_inafecto)) {
						//$$documentoci += $rowdet->importeval;
					}

					if ($item->id_tipodoc_electronico == '07') {
						$debedet = $item->sub_total;
						$haberdet = '';
					} else {
						$debedet = '';
						$haberdet = $item->sub_total;
					}
					
					if (in_array($item->id_tipoafectacionigv, $venta_afecta)) { 

					} else {
						$haberdet = 0;
					}
					
					$registro_detalle = array();
					$registro_detalle = array(
						'origen'			=> $vouorigen,				//1
						'num_voucher'		=> $vounum,					//2
						'fecha'				=> $fec_emi, //Fecha Emisión	//3
						'cuenta'			=> $cta_contable,					//4
						'monto_debe'		=> $debedet,					//5
						'monto_haber'		=> $haberdet,					//6
						'moneda_s_d'		=> $moneda,						//7
						'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
						'doc'				=> $fila->id_tipodoc_electronico,	//9
						'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
						'fec_doc'			=> $fec_emi,	//11
						'fec_ven'			=> $fec_vto,	//12
						'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
						'c_costo'			=> $cta_centrocto,						//14
						'presupuesto'		=> $cta_presupuesto,						//15
						'f_efectivo'		=> '',	//NO				//16
						'glosa'				=> 'Ingresos',				//17
						'libro_c_v_r'		=> 'V',	//Siempre V			//18
						'mto_neto1'			=> '',	//Neto sin IGV		//19
						'mto_neto2'			=> '',	//Venta exportacion		//20
						'mto_neto3'			=> '',	//NO					//21
						'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
						'mto_neto5'			=> '',	//NO				//23
						'mto_neto6'			=> '',	//Base imponible venta exonerada	//24
						'mto_neto7'			=> '',	//Otros tributos					//25
						'mto_neto8'			=> '',	//ISC								//26
						'mto_neto9'			=> '',	//ISC								//27
						'mto_igv'			=> '',	//IGV								//28
						'ref_doc'			=> '',										//29
						'ref_num_doc'		=> '',										//30
						'ref_fecha'			=> '',										//31
						'd_numero'			=> '',	//NO								//32
						'd_fecha'			=> '',	//NO								//33
						'ruc'				=> $cli_numdoc,								//34
						'r_social'			=> $cli_razsoc,								//35
						'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
						'tipo_doc_iden'		=> $cli_tipodoc,							//37
						'medio_pago'		=> '',										//38
						'apellido_1'		=> $cli_apellido1,							//39
						'apellido_2'		=> $cli_apellido2,							//40
						'nombre'			=> $cli_nombres,							//41
						't_bien'			=> '',										//42
					);

					/*
					if($fila->serie_comprobante == 'F004' && $fila->numero_comprobante == 458) {
						echo json_encode($registro_detalle);
						exit();
					}
					*/
	
					$nro_orden++;
					$asientos_contables_mes['n'.$nro_orden] = $registro_detalle;
				}
				//fin foreach

				if ($fila->total_descuento > 0) {
					if ($fila->id_tipodoc_electronico == '07') {
						$debedcto = '';
						$haberdcto = $fila->total_descuento;
					} else {
						$debedcto = $fila->total_descuento;
						$haberdcto = '';
					}
					
					$registro_descuento = array(
						'origen'			=> $vouorigen,				//1
						'num_voucher'		=> $vounum,					//2
						'fecha'				=> $fec_emi, //Fecha Emisión	//3
						'cuenta'			=> "7411",					//4
						'monto_debe'		=> $debedcto,					//5
						'monto_haber'		=> $haberdcto,					//6
						'moneda_s_d'		=> $moneda,						//7
						'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
						'doc'				=> $fila->id_tipodoc_electronico,	//9
						'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
						'fec_doc'			=> $fec_emi,	//11
						'fec_ven'			=> $fec_vto,	//12
						'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
						'c_costo'			=> '', //PREGUNTAR: $cta_centrocto,						//14
						'presupuesto'		=> '', //PREGUNTAR: $cta_presupuesto,						//15
						'f_efectivo'		=> '',	//NO				//16
						'glosa'				=> 'Ingresos',				//17
						'libro_c_v_r'		=> 'V',	//Siempre V			//18
						'mto_neto1'			=> '',	//Neto sin IGV		//19
						'mto_neto2'			=> '',	//Venta exportacion		//20
						'mto_neto3'			=> '',	//NO					//21
						'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
						'mto_neto5'			=> '',	//NO				//23
						'mto_neto6'			=> '',	//Base imponible venta exonerada	//24
						'mto_neto7'			=> '',	//Otros tributos					//25
						'mto_neto8'			=> '',	//ISC								//26
						'mto_neto9'			=> '',	//ISC								//27
						'mto_igv'			=> '',	//IGV								//28
						'ref_doc'			=> '',										//29
						'ref_num_doc'		=> '',										//30
						'ref_fecha'			=> '',										//31
						'd_numero'			=> '',	//NO								//32
						'd_fecha'			=> '',	//NO								//33
						'ruc'				=> $cli_numdoc,								//34
						'r_social'			=> $cli_razsoc,								//35
						'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
						'tipo_doc_iden'		=> $cli_tipodoc,							//37
						'medio_pago'		=> '',										//38
						'apellido_1'		=> $cli_apellido1,							//39
						'apellido_2'		=> $cli_apellido2,							//40
						'nombre'			=> $cli_nombres,							//41
						't_bien'			=> '',										//42
					);
	
					$nro_orden++;
					$asientos_contables_mes['n'.$nro_orden] = $registro_descuento;
				}

				/*
				if($fila->total_gratuitas > 0){
					$debesesenta = "s".$fila->id_tipodoc_electronico.$fila->serie_comprobante."_".$fila->numero_comprobante;
					$registro_gratuitas = array(
						'origen'			=> $vouorigen,				//1
						'num_voucher'		=> $vounum,					//2
						'fecha'				=> $fec_emi, //Fecha Emisión	//3
						'cuenta'			=> "6411",					//4
						'monto_debe'		=> $$debesesenta,					//5
						'monto_haber'		=> 0,					//6
						'moneda_s_d'		=> $moneda,						//7
						'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
						'doc'				=> $fila->id_tipodoc_electronico,	//9
						'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
						'fec_doc'			=> $fec_emi,	//11
						'fec_ven'			=> $fec_vto,	//12
						'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
						'c_costo'			=> '', //PREGUNTAR: $cta_centrocto,						//14
						'presupuesto'		=> '', //PREGUNTAR: $cta_presupuesto,						//15
						'f_efectivo'		=> '',	//NO				//16
						'glosa'				=> 'Ingresos',				//17
						'libro_c_v_r'		=> 'V',	//Siempre V			//18
						'mto_neto1'			=> 0,	//Neto sin IGV		//19
						'mto_neto2'			=> $total_exportacion,	//Venta exportacion		//20
						'mto_neto3'			=> '',	//NO					//21
						'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
						'mto_neto5'			=> $total_exoneradas,	//NO				//23
						'mto_neto6'			=> $total_inafecta,	//Base imponible venta exonerada	//24
						'mto_neto7'			=> '',	//Otros tributos					//25
						'mto_neto8'			=> $total_isc,	//ISC								//26
						'mto_neto9'			=> $total_otr_imp,	//								//27
						'mto_igv'			=> 0,	//IGV								//28
						'ref_doc'			=> $fila->id_tipo_comprobante_modifica,										//29
						'ref_num_doc'		=> $fila->serie_documento_modifica."-".$fila->nro_documento_modifica,										//30
						'ref_fecha'			=> $fecheref,										//31
						'd_numero'			=> '',	//NO								//32
						'd_fecha'			=> '',	//NO								//33
						'ruc'				=> $cli_numdoc,								//34
						'r_social'			=> $cli_razsoc,								//35
						'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
						'tipo_doc_iden'		=> $cli_tipodoc,							//37
						'medio_pago'		=> '',										//38
						'apellido_1'		=> $cli_apellido1,							//39
						'apellido_2'		=> $cli_apellido2,							//40
						'nombre'			=> $cli_nombres,							//41
						't_bien'			=> '',										//42
					);
	
					$nro_orden++;
					$asientos_contables_mes['n'.$nro_orden] = $registro_gratuitas;

					$habercuarenta = $habercuarenta+$$debesesenta;    
                    $gravadac = "c".$fila->id_tipodoc_electronico.$fila->serie_comprobante."_".$fila->numero_comprobante;
                    $total_gravadas = $total_gravadas + $$gravadac;
                    $inafectaci = "ci".$fila->id_tipodoc_electronico.$fila->serie_comprobante."_".$fila->numero_comprobante;
                    $total_inafecta = $$inafectaci;

					$registro_gratuitas = array(
						'origen'			=> $vouorigen,				//1
						'num_voucher'		=> $vounum,					//2
						'fecha'				=> $fec_emi, //Fecha Emisión	//3
						'cuenta'			=> $ctacuarenta,					//4
						'monto_debe'		=> $debecuarenta,					//5
						'monto_haber'		=> $habercuarenta,					//6
						'moneda_s_d'		=> $moneda,						//7
						'tipo_cambio'		=> $fila->tipo_cambio_sunat,	//8
						'doc'				=> $fila->id_tipodoc_electronico,	//9
						'num_doc'			=> $fila->serie_comprobante.'-'.$fila->numero_comprobante,	//10
						'fec_doc'			=> $fec_emi,	//11
						'fec_ven'			=> $fec_vto,	//12
						'cod_prov_clie'		=> $cli_numdoc, //ruc		//13
						'c_costo'			=> '', //PREGUNTAR: $cta_centrocto,						//14
						'presupuesto'		=> '', //PREGUNTAR: $cta_presupuesto,						//15
						'f_efectivo'		=> '',	//NO				//16
						'glosa'				=> 'Ingresos',				//17
						'libro_c_v_r'		=> 'V',	//Siempre V			//18
						'mto_neto1'			=> $total_gravadas,	//Neto sin IGV		//19
						'mto_neto2'			=> $total_exportacion,	//Venta exportacion		//20
						'mto_neto3'			=> '',	//NO					//21
						'mto_neto4'			=> '',	//Base imponible venta inafecta		//22
						'mto_neto5'			=> $total_exoneradas,	//NO				//23
						'mto_neto6'			=> $total_inafecta,	//Base imponible venta exonerada	//24
						'mto_neto7'			=> '',	//Otros tributos					//25
						'mto_neto8'			=> $total_isc,	//ISC								//26
						'mto_neto9'			=> $total_otr_imp,	//								//27
						'mto_igv'			=> $habercuarenta,	//IGV								//28
						'ref_doc'			=> $fila->id_tipo_comprobante_modifica,										//29
						'ref_num_doc'		=> $fila->serie_documento_modifica."-".$fila->nro_documento_modifica,										//30
						'ref_fecha'			=> $fecheref,										//31
						'd_numero'			=> '',	//NO								//32
						'd_fecha'			=> '',	//NO								//33
						'ruc'				=> $cli_numdoc,								//34
						'r_social'			=> $cli_razsoc,								//35
						'tipo'				=> $tipoai,									//36 //codigo 2: cliente (cuando el comprobante esté anulado será 0)
						'tipo_doc_iden'		=> $cli_tipodoc,							//37
						'medio_pago'		=> '',										//38
						'apellido_1'		=> $cli_apellido1,							//39
						'apellido_2'		=> $cli_apellido2,							//40
						'nombre'			=> $cli_nombres,							//41
						't_bien'			=> '',										//42
					);
	
					$nro_orden++;
					$asientos_contables_mes['n'.$nro_orden] = $registro_gratuitas;
				}
				*/
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['asiento_contable'] = $asientos_contables_mes;
		return $resp;
	}

	public function generarExcelLibventasAction($fecha_inicio, $fecha_fin, $idsucursal = 0, $tipo_comprobante = '-1') {
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
		
		$herramientas = new HerramientasController;
		
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}
		
		if(date("m-Y", strtotime($fecha_inicio)) != date("m-Y", strtotime($fecha_fin))) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'No Es Posible Generar el TXT del libro electrónico con fechas que incluyan más de un mes.!';
			echo json_encode($resp);
			exit();
		}

		$tipos_validos_docs = array('-1', '01', '03', '07', '08');
		if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

		$idsucursal = empty($idsucursal)?0:intval($idsucursal) + 0;
		if($idsucursal > 0) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
				echo json_encode($resp);
				exit();
			}
		}
		
		$spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle("Lista Productos");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'PERIODO')
            ->setCellValue('B1', 'COD.UNIC.')
            ->setCellValue('C1', 'REGIMEN')
            ->setCellValue('D1', 'F.EMISIÓN')
            ->setCellValue('E1', 'F.VENCIMIENTO')
            ->setCellValue('F1', 'TIPO DOC')
            ->setCellValue('G1', 'SERIE')
            ->setCellValue('H1', 'NUMERO')
            ->setCellValue('I1', 'NUM.MAQ.REG.')
            ->setCellValue('J1', 'T.DOC.')
            ->setCellValue('K1', 'NUMERO')
            ->setCellValue('L1', 'RAZÓN SOCIAL')
			->setCellValue('M1', 'OP.EXPORT.')
			->setCellValue('N1', 'OP.GRAVADA')
			->setCellValue('O1', 'DESCUENT.')
			->setCellValue('P1', 'IGV')
			->setCellValue('Q1', 'DESC.IGV')
			->setCellValue('R1', 'OP.EXONERADA')
			->setCellValue('S1', 'OP.INAFECTA')
			->setCellValue('T1', 'ISC')
			->setCellValue('U1', 'OP.ARROZ.P.')
			->setCellValue('V1', 'IMP.ARROZ.P.')
			->setCellValue('W1', 'ICBPER')
			->setCellValue('X1', 'OTRO.TRIBUTOS.')
			->setCellValue('Y1', 'TOTAL')
			->setCellValue('Z1', 'MONEDA')
			->setCellValue('AA1', 'T.C.')
			->setCellValue('AB1', 'FEC.COMP.MODIF.')
			->setCellValue('AC1', 'TIPO.DOC.MODIF.')
			->setCellValue('AD1', 'SERIE.DOC.MODIF.')
			->setCellValue('AE1', 'NUM.DOC.MODIF.')
			->setCellValue('AF1', 'ID.CONTR.')
			->setCellValue('AG1', 'ERR.T.C.')
			->setCellValue('AH1', 'COMP.M.P.')
			->setCellValue('AI1', 'ESTADO')
			->setCellValue('AJ1', 'CAMP.LIB.')
			->setCellValue('AK1', 'ESTADO COMP.')
            ;

		$n = 1;
		$items_libro = $this->detalledocumentosemitidos($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $idsucursal, $tipo_comprobante);
		foreach($items_libro as $item) {
			$n++;
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$n, $item['fecha'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('B'.$n, $item['cod_unico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('C'.$n, $item['regimen'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('D'.$n, $item['fecha_comprobante'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('E'.$n, $item['fecha_vencimiento'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('F'.$n, $item['id_tipodoc_electronico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('G'.$n, $item['serie_comprobante'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('H'.$n, $item['numero_comprobante'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('I'.$n, $item['num_maq_reg'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('J'.$n, $item['cliente_id_tipodocidentidad'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('K'.$n, $item['cliente_num_doc_identidad'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('L'.$n, $item['cliente_razon_social'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('M'.$n, $item['total_exportacion'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('N'.$n, $item['total_gravadas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('O'.$n, $item['descuento'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('P'.$n, $item['total_igv'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Q'.$n, $item['descuento_igv'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('R'.$n, $item['total_exoneradas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('S'.$n, $item['total_inafecta'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('T'.$n, $item['total_isc'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('U'.$n, $item['op_arroz_pilado'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('V'.$n, $item['imp_arroz_pilado'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('W'.$n, $item['icbper'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('X'.$n, $item['otros_tributos'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Y'.$n, $item['total'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Z'.$n, $item['moneda'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AA'.$n, $item['tipo_cambio'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AB'.$n, $item['fecha_comp_modif'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AC'.$n, $item['tipo_comp_modif'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AD'.$n, $item['serie_comp_modif'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AE'.$n, $item['numero_comp_modif'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AF'.$n, $item['id_contr'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AG'.$n, $item['error_tc'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AH'.$n, $item['comp_m_p'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AI'.$n, $item['estado'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AJ'.$n, $item['camp_libre_1'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AK'.$n, $item['estado_comprobante'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
		}

		$nombre_archivo = $contribuyente->ruc.'_LibroVentas_'.date("m_Y", strtotime($fecha_inicio));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function get_repote_ventas_ejb($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $idsucursal = 0, $tipo_comprobante = '-1') {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$sunat_tiporegimen = SunatTiporegimen::findFirst(array("idregimen = :idregimen:", 'bind' => array('idregimen' => $contribuyente->sunat_idregimen)));
		$codigo_regimen = 'M-RER';
        if($sunat_tiporegimen) {
			$codigo_regimen = $sunat_tiporegimen->codigo_libro;
		}

		if($tipo_comprobante == '-1') {
			$array_tipos_docs = " ('01', '03', '07', '08') ";
		} else {
			$array_tipos_docs = " ('".$tipo_comprobante."') ";
		}

		//Extraer Totales
		$query = "SELECT de.fecha_comprobante, de.total_isc, de.total_icbper, de.total_otr_imp, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) ";

		//$query = 'SELECT de.fecha_comprobante, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = '.$id_contribuyente.' and tipo_envio_sunat = '.$tipo_envio_sunat.' and CAST(de.fecha_comprobante AS DATE) >= CAST('.$fecha_inicio.' AS DATE) and CAST(de.fecha_comprobante AS DATE) <= CAST('.$fecha_fin.' AS DATE);';
		//echo $query;
		//exit();

		if($idsucursal > 0) {
			$query = $query.' and de.id_sucursal = '.$idsucursal.' ';
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (get_repote_ventas_ejb) ',  $e->getMessage(), "\n";
			exit();
		}

		$lista = array();
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$n++;
			if($fila->fecha_comprobante == $fila->fecha_vto_comprobante || empty($fila->fecha_vto_comprobante)) {
				$fecha_vencimiento = date("d/m/Y", strtotime($fila->fecha_comprobante));
			} else {
				$fecha_vencimiento = date("d/m/Y", strtotime($fila->fecha_vto_comprobante));
			}

			$estado_sunat = 1;
			$fm = 1; //Factor de Multiplicación
			if($fila->estado_envio_sunat == 'anulado') {
				$fm = 0;
				$estado_sunat = 0;
			} else if ($fila->estado_envio_sunat == 'rechazado') {
				$fm = 0;
				$estado_sunat = 0;
			} else if ($fila->estado_envio_sunat == 'pendiente') {
				if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
					$fm = 1;
				} else if ($fila->id_tipodoc_electronico == '07') {
					$fm = -1;
				} else if ($fila->id_tipodoc_electronico == '08') {
					$fm = 1;
				}
			} else if ($fila->estado_envio_sunat == 'aceptado') {
				if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
					$fm = 1;
				} else if ($fila->id_tipodoc_electronico == '07') {
					$fm = -1;
				} else if ($fila->id_tipodoc_electronico == '08') {
					$fm = 1;
				}
			} else {
				$fm = 1;
			}
			
			//$tipo_cambio = '-';
			$factor_tipo_cambio = 1;
			if($fila->tipo_cambio == '') {
				setlocale(LC_MONETARY, 'es_PE');
			} else {
				setlocale(LC_MONETARY, 'es_US');
				$factor_tipo_cambio = floatval($fila->tipo_cambio);
			}

			$fm = $fm*$factor_tipo_cambio;

			$fecha_documento_modificado = '';
			if(!empty($fila->id_tipo_comprobante_modifica) && !empty($fila->serie_documento_modifica) && !empty($fila->nro_documento_modifica)) {
				$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipo_comprobante_modifica, 'serie_comprobante' => $fila->serie_documento_modifica, 'numero_comprobante' => $fila->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				if($documento_modificado) {
					$fecha_documento_modificado = date("d/m/Y", strtotime($documento_modificado->fecha_comprobante));
				}
			}

			$cliente_num_doc_identidad = $fila->num_doc;
			if($fila->id_tipodocidentidad == '0') {
				if(strpos($fila->num_doc, 'sdi') !== false){
					$cliente_num_doc_identidad = '0';
				}
			}

			$nombre_moneda_ejb = '';
			if($fila->id_codigomoneda == 'PEN') {
				$nombre_moneda_ejb = 'MN';
			} else {
				$nombre_moneda_ejb = 'US';
			}

			$codigo_documento = '';
			if($fila->id_tipodoc_electronico == '01') {
				$codigo_documento = 'FT';
			} else if($fila->id_tipodoc_electronico == '03') {
				$codigo_documento = 'BV';
			} else if($fila->id_tipodoc_electronico == '07') {

			} else if($fila->id_tipodoc_electronico == '08') {

			}
			
			$lista[] = array(
				$cliente_num_doc_identidad,
				$codigo_documento, 
				$fila->serie_comprobante,
				$fila->numero_comprobante,
				date("d/m/Y", strtotime($fila->fecha_comprobante)),
				$fecha_vencimiento,
				$nombre_moneda_ejb,
				round($fm*$fila->total_igv, 2),
				round($fm*$fila->total_gravadas + $fm*$fila->total_igv, 2),
				round($fm*$fila->total_exportacion + $fm*$fila->total_exoneradas + $fm*$fila->total_inafecta, 2),
				round($fm*$fila->total_isc, 2),
				round(($fm*$fila->total_otr_imp + $fm*$fila->total_icbper), 2),
				'701211',
				$fecha_documento_modificado,
				$fila->id_tipo_comprobante_modifica,
				empty($fila->serie_documento_modifica)?'':$fila->serie_documento_modifica,
				empty($fila->nro_documento_modifica)?'':$fila->nro_documento_modifica,
				'',
				'05',
				'121201',
				'VENTA '.$fila->serie_comprobante.'-'.$fila->numero_comprobante,
				''
			);
		}

		return $lista;
	}

	public function generarExcelVentasEjbAction($fecha_inicio, $fecha_fin, $idsucursal = 0, $tipo_comprobante = '-1') {
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
		
		$herramientas = new HerramientasController;
		
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}
		
		if(date("m-Y", strtotime($fecha_inicio)) != date("m-Y", strtotime($fecha_fin))) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'No Es Posible Generar el TXT del libro electrónico con fechas que incluyan más de un mes.!';
			echo json_encode($resp);
			exit();
		}

		$tipos_validos_docs = array('-1', '01', '03', '07', '08');
		if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
			echo json_encode($resp);
			exit();
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

		$idsucursal = empty($idsucursal)?0:intval($idsucursal) + 0;
		if($idsucursal > 0) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
				echo json_encode($resp);
				exit();
			}
		}
		
		$spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle("Lista Productos");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'RUC')
            ->setCellValue('B1', 'Tipo Documento')
            ->setCellValue('C1', 'Serie Documento')
            ->setCellValue('D1', 'Numero Documento')
            ->setCellValue('E1', 'Fecha Documento')
            ->setCellValue('F1', 'Fecha Vencimiento')
            ->setCellValue('G1', 'Moneda')
            ->setCellValue('H1', 'Importe IGV')
			->setCellValue('I1', 'Importe Documento')
			->setCellValue('J1', 'Importe Inafecto')
            ->setCellValue('K1', 'Importe ISC')
            ->setCellValue('L1', 'Otros')
            ->setCellValue('M1', 'Cuenta de Venta')
			->setCellValue('N1', 'Fecha Documento Ref.')
			->setCellValue('O1', 'Tipo Documento Ref.')
			->setCellValue('P1', 'Serie Documento Ref.')
			->setCellValue('Q1', 'Número Documento Ref.')
			->setCellValue('R1', 'Centro Costo')
			->setCellValue('S1', 'Subsidiario')
			->setCellValue('T1', 'Cuenta por Cobrar')
			->setCellValue('U1', 'Glosa de Comprobante')
			->setCellValue('V1', 'Anexo de Venta')
            ;

		$n = 1;
		$items_libro = $this->get_repote_ventas_ejb($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $idsucursal, $tipo_comprobante);
		foreach($items_libro as $item) {
			$n++;
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$n, $item[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('B'.$n, $item[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('C'.$n, $item[2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('D'.$n, $item[3], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('E'.$n, $item[4], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('F'.$n, $item[5], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('G'.$n, $item[6], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('H'.$n, $item[7], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('I'.$n, $item[8], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('J'.$n, $item[9], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('K'.$n, $item[10], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('L'.$n, $item[11], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('M'.$n, $item[12], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('N'.$n, $item[13], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('O'.$n, $item[14], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('P'.$n, $item[15], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Q'.$n, $item[16], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('R'.$n, $item[17], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('S'.$n, $item[18], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('T'.$n, $item[19], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('U'.$n, $item[20], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('V'.$n, $item[21], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
		}

		$nombre_archivo = $contribuyente->ruc.'_fomartoEJB_ventas_'.date("m_Y", strtotime($fecha_inicio));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function actualizarTipoCambioVentasAction() {
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

			$fecha_inicio = $datapost['fecha_inicio'];
			$fecha_fin = $datapost['fecha_fin'];
			
			$herramientas = new HerramientasController;
			
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$tipos_validos_docs = array('-1', '01', '03', '07', '08');
			$tipo_comprobante = empty($datapost['tipo_documento'])?'-1':$datapost['tipo_documento'];
			if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
				echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

			$idsucursal = empty($datapost['idsucursal'])?0:intval($datapost['idsucursal']) + 0;
			if($idsucursal > 0) {
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				if(!$sucursal) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
					echo json_encode($resp);
					exit();
				}
			}

			if($tipo_comprobante == '-1') {
				$array_tipos_docs = " ('01', '03', '07', '08') ";
			} else {
				$array_tipos_docs = " ('".$tipo_comprobante."') ";
			}

			$confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Necesitamos tu Confirmación!';
				$resp['mensaje'] = '¿Realmente Deseas Actualizar el factor tipo de cambio en todos tus documentos emitidos en Dólares?, la actualización no podrá revertirse.';
				echo json_encode($resp);
				exit();
			}
			
			$resp = $this->actualizar_tipo_cambio_ventas($usuario, $contribuyente, $array_tipos_docs, $idsucursal, $fecha_inicio, $fecha_fin);
			echo json_encode($resp);
			exit();
		}
	}

	public function actualizar_tipo_cambio_ventas($usuario, $contribuyente, $array_tipos_docs, $idsucursal, $fecha_inicio, $fecha_fin) {
		//Extraer Totales
		$query = "SELECT de.fecha_comprobante, de.total_isc, de.total_icbper, de.total_otr_imp, de.fecha_vto_comprobante, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, clie.idcliente, clie.id_tipodocidentidad, clie.num_doc, clie.razon_social, de.id_codigomoneda, if(de.id_codigomoneda = 'PEN', '', de.tipo_cambio_sunat) tipo_cambio, de.total_gravadas, de.total_inafecta, de.total_exoneradas, de.total_exportacion, de.total_igv, de.total_otr_imp, de.total, de.total_gratuitas, de.estado_envio_sunat FROM doc_electronico de INNER JOIN cliente clie on de.idcliente = clie.idcliente WHERE de.id_contribuyente = :id_contribuyente and de.id_tipodoc_electronico in ".$array_tipos_docs." and tipo_envio_sunat = :tipo_envio_sunat and de.id_codigomoneda = 'USD' and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) ";

		if($idsucursal > 0) {
			$query = $query.' and de.id_sucursal = '.$idsucursal.' ';
		}

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (actualizar_tipo_cambio_ventas) ',  $e->getMessage(), "\n";
			exit();
		}

		$this->db->begin();
		
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			if($documento) {
				$tipo_cambiosunat = SunatTipodecambio::findFirst(array("fecha = CAST(:fecha: AS DATE)", 'bind' => array('fecha' => $documento->fecha_comprobante)));
				
				if($tipo_cambiosunat) {
					if(round(floatval($tipo_cambiosunat->venta), 3) != round(floatval($documento->tipo_cambio_sunat), 3)) {
						$tipo_de_cambio_anterior = $documento->tipo_cambio_sunat;
						$documento->tipo_cambio_sunat = $tipo_cambiosunat->venta;

						try {
							if(!$documento->save()) {
								$this->db->rollback();
								$msg = '';
								foreach ($documento->getMessages() as $message) {
									$msg = $msg.$message."</br>\n";
								}
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = 'No se logró actualizar el tipo de cambio!';
								return $resp;
							}
						} catch (Exception $e) {
                			$this->saveLogger($e);
							$this->db->rollback();
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'No se logró actualizar el tipo de cambio! '.$e->getMessage();
							return $resp;
						}

						$log = new LogDocumento();
						$log->id_contribuyente = $contribuyente->id_contribuyente;
						$log->id_tipodoc_electronico = $fila->id_tipodoc_electronico;
						$log->serie_comprobante = $fila->serie_comprobante;
						$log->numero_comprobante = $fila->numero_comprobante;
						$log->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
						$log->idusuario = $usuario->idusuario;
						$log->fecha_registro = date('Y-m-d H:i:s');
						$log->tipo = 'tipo_cambio';
						$log->descripcion = "El Usuario $usuario->nombre $usuario->apellido (ID: $usuario->idusuario) ha realizado una actualización del T.C., T.C. Anterior: $tipo_de_cambio_anterior, nuevo T.C.: $tipo_cambiosunat->venta ";
						
						try {
							if(!$log->save()) {
								$this->db->rollback();
								$msg = '';
								foreach ($log->getMessages() as $message) {
									$msg = $msg.$message."</br>\n";
								}
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = 'No se logró actualizar el log de cambios!';
								return $resp;
							}
						} catch (Exception $e) {
                			$this->saveLogger($e);
							$this->db->rollback();
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'No se logró actualizar el log de cambios! '.$e->getMessage();
							return $resp;
						}

						$n++;
					}
				}
			}
		}

		$this->db->commit();
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Actualización Completa';
		$resp['mensaje'] = 'El proceso de actualización del tipo de cambio ha finalizado correctamente. Se actualizaron '.$n.' documentos de venta.';
		return $resp;
	}

	public function crearReporteAction() {
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

			$fecha_inicio = $datapost['fecha_inicio'];
			$fecha_fin = $datapost['fecha_fin'];
			
			$herramientas = new HerramientasController;
			
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$tipos_validos_docs = array('-1', '01', '03', '07', '08');
			$tipo_comprobante = empty($datapost['tipo_documento'])?'-1':$datapost['tipo_documento'];
			if(!in_array($tipo_comprobante, $tipos_validos_docs)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de documento seleccionado no es válido!';
				echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			$estado_envio_sunat = $contribuyente->tipo_envio_sunat;

			$idsucursal = empty($datapost['idsucursal'])?0:intval($datapost['idsucursal']) + 0;
			if($idsucursal > 0) {
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
				if(!$sucursal) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
					echo json_encode($resp);
					exit();
				}
			}
			
			$resp['respuesta'] = 'ok';
			$resp['detalle_comprobantes'] = $this->detalledocumentosemitidos($fecha_inicio, $fecha_fin, $estado_envio_sunat, $usuario->id_contribuyente, $idsucursal, $tipo_comprobante);
			echo json_encode($resp);
			exit();
		}
	}

	public function getTopClientesAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
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

			$datapost = $this->request->getPost();
			$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
			$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
			$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
			$id_codigomoneda = empty($datapost['tipos_monedas'])?'PEN':(($datapost['tipos_monedas'] == 'PEN')?'PEN':'USD');
			$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
			$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
			$numero_registros = intval($datapost['numero_registros']) + 0;
			if($numero_registros > 1000 || $numero_registros <= 0) {
				$numero_registros = 100;
			}
			$idcliente = intval($datapost['idcliente']) + 0;
			if($idcliente > 0) {
				$cliente = $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $id_contribuyente)));
				if(!$cliente) {
					$idcliente = 0;
				}
			} else {
				$idcliente = 0;
			}

			//VERIFICANDO PERMISOS DEL USUARIO!
			$gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                            $ids_sucursales = array($usuario->idsucursal);
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                            $ids_sucursales = array($usuario->idsucursal);
                            $ids_vendedores = array($usuario->idusuario);
                        }
                    }
                }
            }

			$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);
			
			$tipos_comprobantes_validos = array('03', '01', '07', '08', '77'); //las notas de crédito siempre descontarán en el total, (se sabe que algunos tipos de notas de crédito no necesariamente descuentan o eliminan una factura, sin embargo para agilizar el proceso, todas las notas se consideran que descuentan el stock)
			//$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado'); //consideraciones, no se debe aceptar anulados, rechazados
			$estados_enviosunat_validos = array('activo', 'aceptado', 'pendiente', 'ticket'); //consideraciones, no se debe aceptar anulados, rechazados

			$tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			$estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
			if(count($ids_vendedores) > 0) {
				foreach($ids_vendedores as $id_vendedor) {
					$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $usuario->id_contribuyente)));
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

			$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$lista = array();

			$sql_ids_vendedores = "";
			if(count($ids_vendedores) > 0) { 
				$sql_ids_vendedores = " (".implode(",", $ids_vendedores).") "; 
			}

			$sql_ids_sucursales = "";
			if(count($ids_sucursales) > 0) { 
				$sql_ids_sucursales = " (".implode(",", $ids_sucursales).") ";
			}

			$sql_estados_envio_docelectronico = "";
			$sql_estados_envio_docno_oficial = "";
			if(count($estados_envio_sunat) > 0) { 
				$sql_estados_envio_docelectronico = " ('".implode("','", $estados_envio_sunat)."') "; 

				if(in_array('aceptado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'activo' "; 
				} else if (in_array('anulado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'inactivo' "; 
				}
			}

			$sql_ids_docs_electronicos = "";
			$sql_ids_docs_no_oficiales = "";
			if(count($tipos_comprobantes) > 0) {
				if(in_array('01', $tipos_comprobantes) || in_array('03', $tipos_comprobantes) || in_array('08', $tipos_comprobantes) || in_array('07', $tipos_comprobantes)) {
					$array_ids_docs_electronicos = array();
					if(in_array('01', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '01';
					}

					if(in_array('03', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '03';
					}

					if(in_array('08', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '08';
					}

					if(in_array('07', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '07';
					}

					$sql_ids_docs_electronicos = " ('".implode("','", $array_ids_docs_electronicos)."') "; 
				}

				if(in_array('77', $tipos_comprobantes)) {
					$sql_ids_docs_no_oficiales = '77';
				}
			}
			
			$resp = $this->get_top_clientes($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $numero_registros, $fecha_inicio, $fecha_fin, $idcliente);

			echo json_encode($resp);
			exit();
		}
	}

	public function get_top_clientes($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $numero_registros, $fecha_inicio, $fecha_fin, $idcliente) {

		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		//SE CONSIDERA EN LOS TOTALES LA RESTA DE LAS NOTAS DE CRÉDITO
		$query_docs_electronicos = "SELECT doc_electronico.idcliente idcliente, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_gravadas, doc_electronico.total_gravadas)) total_gravadas, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_inafecta, doc_electronico.total_inafecta)) total_inafecta, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_exoneradas, doc_electronico.total_exoneradas)) total_exoneradas, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_gratuitas, doc_electronico.total_gratuitas)) total_gratuitas, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_exportacion, doc_electronico.total_exportacion)) total_exportacion, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_icbper, doc_electronico.total_icbper)) total_icbper, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.sub_total, doc_electronico.sub_total)) sub_total, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_igv, doc_electronico.total_igv)) total_igv, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_isc, doc_electronico.total_isc)) total_isc, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_otr_imp, doc_electronico.total_otr_imp)) total_otr_imp, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total, doc_electronico.total)) total FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE)  ";

		if($idcliente > 0) {
			$query_docs_electronicos = $query_docs_electronicos." and doc_electronico.idcliente = $idcliente ";
		}
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_electronicos = $query_docs_electronicos." GROUP BY doc_electronico.idcliente ORDER BY sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total, doc_electronico.total)) DESC";

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT doc_no_oficial.idcliente idcliente, sum(doc_no_oficial.total_gravadas) total_gravadas, sum(doc_no_oficial.total_inafecta) total_inafecta, sum(doc_no_oficial.total_exoneradas) total_exoneradas, sum(doc_no_oficial.total_gratuitas) total_gratuitas, sum(doc_no_oficial.total_exportacion) total_exportacion, sum(doc_no_oficial.total_icbper) total_icbper, sum(doc_no_oficial.sub_total) sub_total, sum(doc_no_oficial.total_igv) total_igv, 0 isc, 0 total_otr_imp, sum(doc_no_oficial.total) total FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) "; 

		if($idcliente > 0) {
			$query_docs_no_oficiales = $query_docs_no_oficiales." and doc_no_oficial.idcliente = $idcliente ";
		}
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}
		
		$query_docs_no_oficiales = $query_docs_no_oficiales."GROUP BY doc_no_oficial.idcliente ORDER BY sum(doc_no_oficial.total) DESC";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "
			select idcliente, sum(total_gravadas) total_gravadas, sum(total_inafecta) total_inafecta, sum(total_exoneradas) total_exoneradas, sum(total_gratuitas) total_gratuitas, sum(total_exportacion) total_exportacion, sum(total_icbper) total_icbper, sum(sub_total) sub_total, sum(total_igv) total_igv, sum(total_isc) total_isc, sum(total_otr_imp) total_otr_imp, sum(total) total FROM  
			
				(($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales))  tbl_todo 
				
			GROUP BY idcliente ORDER BY sum(total) DESC limit $numero_registros";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales." limit $numero_registros";
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos." limit $numero_registros";
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$herramientas = new HerramientasController;
		$diferencia_fechas = $herramientas->comparar_fechas($fecha_inicio, $fecha_fin);
		$dias_diferencia = abs($diferencia_fechas['diferencia_primera_segunda']) + 1;

		$array_lista = array();
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$cliente = $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $id_contribuyente)));
			if($cliente) {
				$resp_detalle_compra = $this->get_detalle_compras_by_cliente($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $fila->idcliente);
				if($resp_detalle_compra['respuesta'] == 'ok') {
					$detalle_documentos = $resp_detalle_compra['data'];

					if($dias_diferencia <= 0) {
						$promedio_num_docs = $detalle_documentos['total_docs'];
						$promedio_total = $fila->total;
					} else {
						$promedio_num_docs = round($detalle_documentos['total_docs']/$dias_diferencia, 2);
						$promedio_total = round($fila->total/$dias_diferencia, 2);
					}

					$array_lista[] = array(
						'cliente' 			=> $cliente->razon_social.'<br />'.$cliente->num_doc, 
						'total_factura' 	=> $detalle_documentos['total_factura'],
						'total_boleta' 		=> $detalle_documentos['total_boleta'],
						'total_nota_venta' 	=> $detalle_documentos['total_nota_venta'],
						'total_nota_debito' 	=> $detalle_documentos['total_nota_debito'],
						'total_nota_credito' => $detalle_documentos['total_nota_credito'],
						'total' 			=> $fila->total,
						'total_docs' 		=> $detalle_documentos['total_docs'],
						'promedio_num_docs' => $promedio_num_docs,
						'promedio_total' 	=> $promedio_total,
						'idcliente'			=> $fila->idcliente
					);
				}
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $array_lista;
		echo json_encode($resp);		
		exit();
	}

	public function get_detalle_compras_by_cliente($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $idcliente) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		$query_docs_electronicos = "SELECT doc_electronico.idcliente idcliente, doc_electronico.id_tipodoc_electronico id_tipodoc_electronico, count(id_tipodoc_electronico) totaldocumentos, sum(doc_electronico.total_gravadas) total_gravadas, sum(doc_electronico.total_inafecta) total_inafecta, sum(doc_electronico.total_exoneradas) total_exoneradas, sum(doc_electronico.total_gratuitas) total_gratuitas, sum(doc_electronico.total_exportacion) total_exportacion, sum(doc_electronico.total_icbper) total_icbper, sum(doc_electronico.sub_total) sub_total, sum(doc_electronico.total_igv) total_igv, sum(doc_electronico.total_isc) total_isc, sum(doc_electronico.total_otr_imp) total_otr_imp, sum(doc_electronico.total) total FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_electronico.idcliente = $idcliente ";
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_electronicos = $query_docs_electronicos." GROUP BY doc_electronico.idcliente, doc_electronico.id_tipodoc_electronico ORDER BY sum(doc_electronico.total) DESC";

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT doc_no_oficial.idcliente idcliente, doc_no_oficial.id_tipodocumento id_tipodoc_electronico, count(id_tipodocumento) totaldocumentos, sum(doc_no_oficial.total_gravadas) total_gravadas, sum(doc_no_oficial.total_inafecta) total_inafecta, sum(doc_no_oficial.total_exoneradas) total_exoneradas, sum(doc_no_oficial.total_gratuitas) total_gratuitas, sum(doc_no_oficial.total_exportacion) total_exportacion, sum(doc_no_oficial.total_icbper) total_icbper, sum(doc_no_oficial.sub_total) sub_total, sum(doc_no_oficial.total_igv) total_igv, 0 isc, 0 total_otr_imp, sum(doc_no_oficial.total) total FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_no_oficial.idcliente = $idcliente "; 
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}
		
		$query_docs_no_oficiales = $query_docs_no_oficiales."GROUP BY doc_no_oficial.idcliente, doc_no_oficial.id_tipodocumento ORDER BY sum(doc_no_oficial.total) DESC";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "
			select idcliente, id_tipodoc_electronico, sum(totaldocumentos) totaldocumentos, sum(total_gravadas) total_gravadas, sum(total_inafecta) total_inafecta, sum(total_exoneradas) total_exoneradas, sum(total_gratuitas) total_gratuitas, sum(total_exportacion) total_exportacion, sum(total_icbper) total_icbper, sum(sub_total) sub_total, sum(total_igv) total_igv, sum(total_isc) total_isc, sum(total_otr_imp) total_otr_imp, sum(total) total FROM  
			
				(($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales))  tbl_todo 
			
			WHERE idcliente = $idcliente 
			GROUP BY idcliente, id_tipodoc_electronico ORDER BY sum(total) DESC";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales;
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos;
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$array_datos = array();
		$array_datos['total_factura'] = 0;
		$array_datos['total_boleta'] = 0;
		$array_datos['total_nota_credito'] = 0;
		$array_datos['total_nota_debito'] = 0;
		$array_datos['total_nota_venta'] = 0;

		$array_datos['num_factura'] = 0;
		$array_datos['num_boleta'] = 0;
		$array_datos['num_nota_credito'] = 0;
		$array_datos['num_nota_debito'] = 0;
		$array_datos['num_nota_venta'] = 0;

		$array_datos['total_docs'] = 0;
		
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			if($fila->id_tipodoc_electronico == '01') {
				$array_datos['total_factura'] = $array_datos['total_factura'] + $fila->total;
				$array_datos['num_factura'] = $array_datos['num_factura'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '03') {
				$array_datos['total_boleta'] = $array_datos['total_boleta'] + $fila->total;
				$array_datos['num_boleta'] = $array_datos['num_boleta'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '07') {
				$array_datos['total_nota_credito'] = $array_datos['total_nota_credito'] + $fila->total;
				$array_datos['num_nota_credito'] = $array_datos['num_nota_credito'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '08') {
				$array_datos['total_nota_debito'] = $array_datos['total_nota_debito'] + $fila->total;
				$array_datos['num_nota_debito'] = $array_datos['num_nota_debito'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '77') {
				$array_datos['total_nota_venta'] = $array_datos['total_nota_venta'] + $fila->total;
				$array_datos['num_nota_venta'] = $array_datos['num_nota_venta'] + $fila->totaldocumentos;
			}

			$array_datos['total_docs'] = $array_datos['total_docs'] + $fila->totaldocumentos;
		}

		$resp['respuesta'] = 'ok';
		$resp['data'] = $array_datos;
		return $resp;
	}

	public function getDetalleDocsByIdclienteAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
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

			$datapost = $this->request->getPost();
			$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
			$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
			$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
			$id_codigomoneda = empty($datapost['tipos_monedas'])?'PEN':(($datapost['tipos_monedas'] == 'PEN')?'PEN':'USD');
			$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
			$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
			$numero_registros = intval($datapost['numero_registros']) + 0;
			$idcliente = intval($datapost['idcliente']) + 0;
			$tipo_reporte = ($datapost['tiporeporte'] == 'documentos')?'documentos':'detallado';
			if($numero_registros > 1000 || $numero_registros <= 0) {
				$numero_registros = 100;
			}

			//VERIFICANDO PERMISOS DEL USUARIO!
			$gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                            $ids_sucursales = array($usuario->idsucursal);
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                            $ids_sucursales = array($usuario->idsucursal);
                            $ids_vendedores = array($usuario->idusuario);
                        }
                    }
                }
            }

			$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);
			
			$tipos_comprobantes_validos = array('03', '01', '07', '08', '77'); //las notas de crédito siempre descontarán en el total, (se sabe que algunos tipos de notas de crédito no necesariamente descuentan o eliminan una factura, sin embargo para agilizar el proceso, todas las notas se consideran que descuentan el stock)
			//$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado'); //consideraciones, no se debe aceptar anulados, rechazados
			$estados_enviosunat_validos = array('activo', 'aceptado', 'pendiente', 'ticket'); //consideraciones, no se debe aceptar anulados, rechazados

			$tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			$estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
			if(count($ids_vendedores) > 0) {
				foreach($ids_vendedores as $id_vendedor) {
					$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $usuario->id_contribuyente)));
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

			$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$lista = array();

			$sql_ids_vendedores = "";
			if(count($ids_vendedores) > 0) { 
				$sql_ids_vendedores = " (".implode(",", $ids_vendedores).") "; 
			}

			$sql_ids_sucursales = "";
			if(count($ids_sucursales) > 0) { 
				$sql_ids_sucursales = " (".implode(",", $ids_sucursales).") ";
			}

			$sql_estados_envio_docelectronico = "";
			$sql_estados_envio_docno_oficial = "";
			if(count($estados_envio_sunat) > 0) { 
				$sql_estados_envio_docelectronico = " ('".implode("','", $estados_envio_sunat)."') "; 

				if(in_array('aceptado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'activo' "; 
				} else if (in_array('anulado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'inactivo' "; 
				}
			}

			$sql_ids_docs_electronicos = "";
			$sql_ids_docs_no_oficiales = "";
			if(count($tipos_comprobantes) > 0) {
				if(in_array('01', $tipos_comprobantes) || in_array('03', $tipos_comprobantes) || in_array('08', $tipos_comprobantes) || in_array('07', $tipos_comprobantes)) {
					$array_ids_docs_electronicos = array();
					if(in_array('01', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '01';
					}

					if(in_array('03', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '03';
					}

					if(in_array('08', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '08';
					}

					if(in_array('07', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '07';
					}

					$sql_ids_docs_electronicos = " ('".implode("','", $array_ids_docs_electronicos)."') "; 
				}

				if(in_array('77', $tipos_comprobantes)) {
					$sql_ids_docs_no_oficiales = '77';
				}
			}

			$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $id_contribuyente)));
			if(!$cliente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El ID del cliente no es válido!';
				echo json_encode($resp);
                exit();
			}
			
			if($tipo_reporte == 'documentos') {
				$reporte_docs = $this->get_lista_docs_by_clie_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $idcliente, $cliente);
			} else {
				$reporte_docs = $this->get_lista_docs_detallado_by_clie_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $idcliente, $cliente);
			}

			if($reporte_docs['respuesta'] == 'error') {
				echo json_encode($reporte_docs);
				exit();
			}
			
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $reporte_docs['lista'];
			$resp['cliente'] = $cliente;
			echo json_encode($resp);
			exit();
		}
	}

	public function get_lista_docs_by_clie_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $idcliente, $cliente) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		$query_docs_electronicos = "SELECT doc_electronico.idcliente idcliente, doc_electronico.id_tipodoc_electronico id_tipodoc_electronico, doc_electronico.serie_comprobante serie_comprobante, doc_electronico.numero_comprobante numero_comprobante, doc_electronico.id_condicionpago id_condicionpago,  doc_electronico.cpago_nrooperacion cpago_nrooperacion,  doc_electronico.cpago_idbanco cpago_idbanco, doc_electronico.cpago_fechadeposito cpago_fechadeposito, doc_electronico.total_gravadas total_gravadas, doc_electronico.total_inafecta total_inafecta, doc_electronico.total_exoneradas total_exoneradas, doc_electronico.total_gratuitas total_gratuitas, doc_electronico.total_icbper total_icbper, doc_electronico.total_igv total_igv, doc_electronico.total_isc total_isc, doc_electronico.total_otr_imp total_otr_imp, doc_electronico.sub_total sub_total, doc_electronico.total total, doc_electronico.fecha_comprobante fecha_comprobante, doc_electronico.fecha_registro fecha_registro, doc_electronico.fecha_vto_comprobante fecha_vto_comprobante, doc_electronico.id_sucursal id_sucursal, doc_electronico.id_vendedor id_vendedor FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_electronico.idcliente = $idcliente ";
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor in ".$sql_ids_vendedores." ";
		}
		
		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT doc_no_oficial.idcliente idcliente, doc_no_oficial.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc_no_oficial.numero_comprobante numero_comprobante, doc_no_oficial.id_condicionpago id_condicionpago,  doc_no_oficial.cpago_nrooperacion cpago_nrooperacion,  doc_no_oficial.cpago_idbanco cpago_idbanco, doc_no_oficial.cpago_fechadeposito cpago_fechadeposito, doc_no_oficial.total_gravadas total_gravadas, doc_no_oficial.total_inafecta total_inafecta, doc_no_oficial.total_exoneradas total_exoneradas, doc_no_oficial.total_gratuitas total_gratuitas, doc_no_oficial.total_icbper total_icbper, doc_no_oficial.total_igv total_igv, 0 total_isc, 0 total_otr_imp, doc_no_oficial.sub_total sub_total, doc_no_oficial.total total, doc_no_oficial.fecha_comprobante fecha_comprobante, doc_no_oficial.fecha_registro fecha_registro, doc_no_oficial.fecha_comprobante fecha_vto_comprobante, doc_no_oficial.id_sucursal id_sucursal, doc_no_oficial.id_vendedor id_vendedor FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_no_oficial.idcliente = $idcliente "; 
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales)";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales;
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos;
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$herramientas = new HerramientasController;
		try {
			$array_lista = array();
			$n = 0;
			while($fila = $sentencia->fetch()) {
				$fila = (object)$fila;
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $id_contribuyente)));
				$datos_vendedor = '';
				if($vendedor) {
					$datos_vendedor = $vendedor->nombre.' '.$vendedor->apellido.'<br >'.$vendedor->email;
				}
				
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $id_contribuyente)));
				$datos_sucursal = '';
				if($sucursal) {
					$datos_sucursal = $sucursal->nombre.'<br />'.$sucursal->direccion;
				}

				$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||a4");
                $string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||ticket");

                $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		        $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

				$ruta_pdf = 'javascript:void(0)';
				$serie_comprobante = $fila->serie_comprobante;
				if ($fila->id_tipodoc_electronico == '01') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '03') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '07') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '08') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '77') {
					$ruta_pdf = $url_a4;
					$serie_comprobante = 'NV';
				} else {
					$ruta_pdf = $url_a4;
				}

				$condicion_pago_nombre = 'CONTADO';
				if(!empty($fila->id_condicionpago)) {
					$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $id_contribuyente)));
					if($condicion_pago) {
						$condicion_pago_nombre = strtoupper($condicion_pago->tipo);
					}
				}
				
				$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
				$cpago_fechadeposito = '';
				$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
				$nombre_banco_deposito = '';

				if(intval($cpago_idbanco) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
					if($cuenta_banco_transferencia) {
						$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
					}
				}

				if(!empty($fila->cpago_fechadeposito)) {
					$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
				}

				if($fila->id_tipodoc_electronico == '77') {
					$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
				} else {
					$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
				}

				if(count($abonos) > 0) {
					$lista_nrooperacion = array();
					$lista_fechadeposito = array();
					$lista_idbanco = array();
					$lista_nombre_banco = array();

					foreach($abonos as $abono) {
						if(!empty($abono->cpago_nrooperacion)) {
							$lista_nrooperacion[] = $abono->cpago_nrooperacion;
						}

						if(!empty($abono->fechadeposito)) {
							$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
						}
						
						if(intval($abono->idbanco) > 0 ) {
							$lista_idbanco[] = $abono->idbanco;
							$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
							if($cuenta_banco_transferencia) {
								$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
							}
						}
					}


					$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
					$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
					$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
					$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
				}

				$array_lista[] = array(
					'fecha_registro'			=> date("d-m-Y / H:i A", strtotime($fila->fecha_registro)),
					'fecha_comprobante'			=> date("d-m-Y", strtotime($fila->fecha_comprobante)),
					'id_tipodoc_electronico' 	=> $fila->id_tipodoc_electronico,
					'serie_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$serie_comprobante."</a>",
					'numero_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$fila->numero_comprobante."</a>",
					
					'condicion_pago'			=> $condicion_pago_nombre, //
					'cpago_nrooperacion'		=> $cpago_nrooperacion,
					'cpago_fechadeposito'		=> $cpago_fechadeposito,
					'cpago_idbanco'				=> $cpago_idbanco,
					'nombre_banco_deposito'		=> $nombre_banco_deposito,

					
					'total_gravadas'			=> $fila->total_gravadas,
					'total_inafecta'			=> $fila->total_inafecta,
					'total_exoneradas'			=> $fila->total_exoneradas,
					'total_gratuitas'			=> $fila->total_gratuitas,
					'total_icbper'				=> $fila->total_icbper,
					'total_igv'					=> $fila->total_igv,
					'sub_total'					=> $fila->sub_total,
					'total'						=> $fila->total,
					'fecha_vto_comprobante'		=> !empty($fila->fecha_vto_comprobante) ? date("d-m-Y", strtotime($fila->fecha_vto_comprobante)) : '',
					'id_sucursal'				=> $fila->id_sucursal,
					'id_vendedor'				=> $fila->id_vendedor,
					'datos_vendedor'			=> $datos_vendedor,
					'datos_sucursal'			=> $datos_sucursal,
					'id_codigomoneda'			=> $id_codigomoneda
				);
			}

		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			echo json_encode($resp);
			exit();
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $array_lista;
		return $resp;
	}

	public function get_lista_docs_detallado_by_clie_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $idcliente, $cliente) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		$query_docs_electronicos = "SELECT doc_electronico.idcliente idcliente, doc_electronico.id_tipodoc_electronico id_tipodoc_electronico, doc_electronico.serie_comprobante serie_comprobante, doc_electronico.numero_comprobante numero_comprobante, doc_electronico.id_condicionpago id_condicionpago,  doc_electronico.cpago_nrooperacion cpago_nrooperacion,  doc_electronico.cpago_idbanco cpago_idbanco, doc_electronico.cpago_fechadeposito cpago_fechadeposito, doc_electronico.total_gravadas total_gravadas, doc_electronico.total_inafecta total_inafecta, doc_electronico.total_exoneradas total_exoneradas, doc_electronico.total_gratuitas total_gratuitas, doc_electronico.total_icbper total_icbper, doc_electronico.total_igv total_igv, doc_electronico.total_isc total_isc, doc_electronico.total_otr_imp total_otr_imp, doc_electronico.sub_total doc_sub_total, doc_electronico.total total, doc_electronico.fecha_comprobante fecha_comprobante, doc_electronico.fecha_registro fecha_registro, doc_electronico.fecha_vto_comprobante fecha_vto_comprobante, doc_electronico.id_sucursal id_sucursal, doc_electronico.id_vendedor id_vendedor, detalle_doc.id_unidad_medida, detalle_doc.unidad_medida, detalle_doc.cantidad, detalle_doc.precio, detalle_doc.sub_total, detalle_doc.id_codigoprecio, detalle_doc.igv, detalle_doc.isc, detalle_doc.icbper, detalle_doc.importe, detalle_doc.id_tipoafectacionigv, detalle_doc.id_producto, detalle_doc.codigo_producto, detalle_doc.descripcion, detalle_doc.precio_sin_igv, detalle_doc.peso FROM
		`doc_electronico` INNER JOIN detalle_doc ON (doc_electronico.id_contribuyente = detalle_doc.id_contribuyente and doc_electronico.id_tipodoc_electronico = detalle_doc.id_tipodoc_electronico and doc_electronico.serie_comprobante = detalle_doc.serie_comprobante and doc_electronico.numero_comprobante = detalle_doc.numero_comprobante and doc_electronico.tipo_envio_sunat = detalle_doc.tipo_envio_sunat) where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_electronico.idcliente = $idcliente ";
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_electronicos = $query_docs_electronicos." ORDER BY doc_electronico.id_contribuyente, doc_electronico.id_tipodoc_electronico, doc_electronico.serie_comprobante, doc_electronico.numero_comprobante, doc_electronico.tipo_envio_sunat ";

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT
		doc_no_oficial.idcliente idcliente, doc_no_oficial.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc_no_oficial.numero_comprobante numero_comprobante, doc_no_oficial.id_condicionpago id_condicionpago,  doc_no_oficial.cpago_nrooperacion cpago_nrooperacion,  doc_no_oficial.cpago_idbanco cpago_idbanco, doc_no_oficial.cpago_fechadeposito cpago_fechadeposito,  doc_no_oficial.total_gravadas total_gravadas, doc_no_oficial.total_inafecta total_inafecta, doc_no_oficial.total_exoneradas total_exoneradas, doc_no_oficial.total_gratuitas total_gratuitas, doc_no_oficial.total_icbper total_icbper, doc_no_oficial.total_igv total_igv, 0 total_isc, 0 total_otr_imp, doc_no_oficial.sub_total doc_sub_total, doc_no_oficial.total total, doc_no_oficial.fecha_comprobante fecha_comprobante, doc_no_oficial.fecha_registro fecha_registro, doc_no_oficial.fecha_comprobante fecha_vto_comprobante, doc_no_oficial.id_sucursal id_sucursal, doc_no_oficial.id_vendedor id_vendedor, detalle_docnooficial.id_unidad_medida, detalle_docnooficial.unidad_medida, detalle_docnooficial.cantidad, detalle_docnooficial.precio, detalle_docnooficial.sub_total, detalle_docnooficial.id_codigoprecio, detalle_docnooficial.igv, 0 isc, detalle_docnooficial.icbper, detalle_docnooficial.importe, detalle_docnooficial.id_tipoafectacionigv, detalle_docnooficial.id_producto, detalle_docnooficial.codigo_producto, detalle_docnooficial.descripcion, detalle_docnooficial.precio_sin_igv, detalle_docnooficial.peso FROM `doc_no_oficial` INNER JOIN detalle_docnooficial ON (doc_no_oficial.id_contribuyente = detalle_docnooficial.id_contribuyente and doc_no_oficial.id_tipodocumento = detalle_docnooficial.id_tipodocumento and doc_no_oficial.numero_comprobante = detalle_docnooficial.numero_comprobante and doc_no_oficial.modalidad = detalle_docnooficial.modalidad) where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_no_oficial.idcliente = $idcliente "; 
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}

		$query_docs_no_oficiales = $query_docs_no_oficiales." ORDER BY doc_no_oficial.id_contribuyente, doc_no_oficial.id_tipodocumento, doc_no_oficial.numero_comprobante, doc_no_oficial.modalidad ";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales)";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales;
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos;
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$array_lista = array();
		$n = 0;
		$herramientas = new HerramientasController;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $id_contribuyente)));
			$datos_vendedor = '';
			if($vendedor) {
				$datos_vendedor = $vendedor->nombre.' '.$vendedor->apellido.'<br >'.$vendedor->email;
			}

			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $id_contribuyente)));
			$datos_sucursal = '';
			if($sucursal) {
				$datos_sucursal = $sucursal->nombre.'<br />'.$sucursal->direccion;
			}

			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $fila->id_producto, 'id_contribuyente' => $id_contribuyente)));

			$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||a4");
			$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||ticket");

			$url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
			$url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

			$ruta_pdf = 'javascript:void(0)';
			$serie_comprobante = $fila->serie_comprobante;
			if ($fila->id_tipodoc_electronico == '01') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '03') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '07') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '08') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '77') {
				$ruta_pdf = $url_a4;
				$serie_comprobante = 'NV';
			} else {
				$ruta_pdf = $url_a4;
			}

			$condicion_pago_nombre = 'CONTADO';
			if(!empty($fila->id_condicionpago)) {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $id_contribuyente)));
				if($condicion_pago) {
					$condicion_pago_nombre = strtoupper($condicion_pago->tipo);
				}
			}
			
			$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
			$cpago_fechadeposito = '';
			$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
			$nombre_banco_deposito = '';

			if(intval($cpago_idbanco) > 0) {
				$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
				if($cuenta_banco_transferencia) {
					$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
				}
			}

			if(!empty($fila->cpago_fechadeposito)) {
				$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
			}

			if($fila->id_tipodoc_electronico == '77') {
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			} else {
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			}

			if(count($abonos) > 0) {
				$lista_nrooperacion = array();
				$lista_fechadeposito = array();
				$lista_idbanco = array();
				$lista_nombre_banco = array();

				foreach($abonos as $abono) {
					if(!empty($abono->cpago_nrooperacion)) {
						$lista_nrooperacion[] = $abono->cpago_nrooperacion;
					}

					if(!empty($abono->fechadeposito)) {
						$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
					}
					
					if(intval($abono->idbanco) > 0 ) {
						$lista_idbanco[] = $abono->idbanco;
						$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
						if($cuenta_banco_transferencia) {
							$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
						}
					}
				}


				$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
				$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
				$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
				$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
			}

			$array_lista[] = array(
				'fecha_registro'			=> date("d-m-Y / H:i A", strtotime($fila->fecha_registro)),
				'fecha_comprobante'			=> date("d-m-Y", strtotime($fila->fecha_comprobante)),
				'id_tipodoc_electronico' 	=> $fila->id_tipodoc_electronico,
				'serie_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$serie_comprobante."</a>",
				'numero_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$fila->numero_comprobante."</a>",

				'condicion_pago'			=> $condicion_pago_nombre, //
				'cpago_nrooperacion'		=> $cpago_nrooperacion,
				'cpago_fechadeposito'		=> $cpago_fechadeposito,
				'cpago_idbanco'				=> $cpago_idbanco,
				'nombre_banco_deposito'		=> $nombre_banco_deposito,
				
				'total_gravadas'			=> $fila->total_gravadas,
				'total_inafecta'			=> $fila->total_inafecta,
				'total_exoneradas'			=> $fila->total_exoneradas,
				'total_gratuitas'			=> $fila->total_gratuitas,
				'total_icbper'				=> $fila->total_icbper,
				'total_igv'					=> $fila->total_igv,
				'doc_sub_total'				=> $fila->doc_sub_total,
				'total'						=> $fila->total,
				'fecha_vto_comprobante'		=> !empty($fila->fecha_vto_comprobante) ? date("d-m-Y", strtotime($fila->fecha_vto_comprobante)) : '',
				'id_sucursal'				=> $fila->id_sucursal,
				'id_vendedor'				=> $fila->id_vendedor,
				'datos_vendedor'			=> $datos_vendedor,
				'datos_sucursal'			=> $datos_sucursal,
				'id_codigomoneda'			=> $id_codigomoneda,
				
				//detalle
				'codigo_producto'	=> $fila->codigo_producto,
				'descripcion'	=> $fila->descripcion,
				'id_unidad_medida'	=> $fila->id_unidad_medida,
				'unidad_medida'	=> $fila->unidad_medida,
				'id_tipoafectacionigv' => $fila->id_tipoafectacionigv,
				'cantidad'	=> $fila->cantidad + 0,
				'precio'	=> $fila->precio + 0,
				'sub_total'	=> $fila->sub_total + 0,
				'igv'	=> $fila->igv,
				'icbper'	=> $fila->icbper,
				'importe'	=> round($fila->sub_total + $fila->igv, 2)
			);
		}

		
		
		$resp['respuesta'] = 'ok';
		$resp['lista'] = $array_lista;
		return $resp;
	}

	public function getTopVendedoresAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
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

			$datapost = $this->request->getPost();
			$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
			$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
			$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
			$id_codigomoneda = empty($datapost['tipos_monedas'])?'PEN':(($datapost['tipos_monedas'] == 'PEN')?'PEN':'USD');
			$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
			$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
			$numero_registros = intval($datapost['numero_registros']) + 0;
			if($numero_registros > 1000 || $numero_registros <= 0) {
				$numero_registros = 100;
			}

			//VERIFICANDO PERMISOS DEL USUARIO!
			$gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                            $ids_sucursales = array($usuario->idsucursal);
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                            $ids_sucursales = array($usuario->idsucursal);
                            $ids_vendedores = array($usuario->idusuario);
                        }
                    }
                }
            }

			$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);
			
			$tipos_comprobantes_validos = array('03', '01', '07', '08', '77'); //las notas de crédito siempre descontarán en el total, (se sabe que algunos tipos de notas de crédito no necesariamente descuentan o eliminan una factura, sin embargo para agilizar el proceso, todas las notas se consideran que descuentan el stock)
			//$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado'); //consideraciones, no se debe aceptar anulados, rechazados
			$estados_enviosunat_validos = array('activo', 'aceptado', 'pendiente', 'ticket'); //consideraciones, no se debe aceptar anulados, rechazados

			$tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			$estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

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

			$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$lista = array();

			$sql_ids_vendedores = "";
			if(count($ids_vendedores) > 0) { 
				$sql_ids_vendedores = " (".implode(",", $ids_vendedores).") "; 
			}

			$sql_ids_sucursales = "";
			if(count($ids_sucursales) > 0) { 
				$sql_ids_sucursales = " (".implode(",", $ids_sucursales).") ";
			}

			$sql_estados_envio_docelectronico = "";
			$sql_estados_envio_docno_oficial = "";
			if(count($estados_envio_sunat) > 0) { 
				$sql_estados_envio_docelectronico = " ('".implode("','", $estados_envio_sunat)."') "; 

				if(in_array('aceptado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'activo' "; 
				} else if (in_array('anulado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'inactivo' "; 
				}
			}

			$sql_ids_docs_electronicos = "";
			$sql_ids_docs_no_oficiales = "";
			if(count($tipos_comprobantes) > 0) {
				if(in_array('01', $tipos_comprobantes) || in_array('03', $tipos_comprobantes) || in_array('08', $tipos_comprobantes) || in_array('07', $tipos_comprobantes)) {
					$array_ids_docs_electronicos = array();
					if(in_array('01', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '01';
					}

					if(in_array('03', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '03';
					}

					if(in_array('08', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '08';
					}

					if(in_array('07', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '07';
					}

					$sql_ids_docs_electronicos = " ('".implode("','", $array_ids_docs_electronicos)."') "; 
				}

				if(in_array('77', $tipos_comprobantes)) {
					$sql_ids_docs_no_oficiales = '77';
				}
			}
			
			$resp = $this->get_top_vendedores($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $numero_registros, $fecha_inicio, $fecha_fin);

			echo json_encode($resp);
			exit();
		}
	}

	public function get_top_vendedores($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $numero_registros, $fecha_inicio, $fecha_fin) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		//SE CONSIDERA EN LOS TOTALES LA RESTA DE LAS NOTAS DE CRÉDITO
		$query_docs_electronicos = "SELECT doc_electronico.id_vendedor id_vendedor, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_gravadas, doc_electronico.total_gravadas)) total_gravadas, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_inafecta, doc_electronico.total_inafecta)) total_inafecta, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_exoneradas, doc_electronico.total_exoneradas)) total_exoneradas, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_gratuitas, doc_electronico.total_gratuitas)) total_gratuitas, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_exportacion, doc_electronico.total_exportacion)) total_exportacion, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_icbper, doc_electronico.total_icbper)) total_icbper, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.sub_total, doc_electronico.sub_total)) sub_total, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_igv, doc_electronico.total_igv)) total_igv, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_isc, doc_electronico.total_isc)) total_isc, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total_otr_imp, doc_electronico.total_otr_imp)) total_otr_imp, sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total, doc_electronico.total)) total FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE)  ";
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_electronicos = $query_docs_electronicos." GROUP BY doc_electronico.id_vendedor ORDER BY sum(IF(id_tipodoc_electronico='07', -1*doc_electronico.total, doc_electronico.total)) DESC";

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT doc_no_oficial.id_vendedor id_vendedor, sum(doc_no_oficial.total_gravadas) total_gravadas, sum(doc_no_oficial.total_inafecta) total_inafecta, sum(doc_no_oficial.total_exoneradas) total_exoneradas, sum(doc_no_oficial.total_gratuitas) total_gratuitas, sum(doc_no_oficial.total_exportacion) total_exportacion, sum(doc_no_oficial.total_icbper) total_icbper, sum(doc_no_oficial.sub_total) sub_total, sum(doc_no_oficial.total_igv) total_igv, 0 isc, 0 total_otr_imp, sum(doc_no_oficial.total) total FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) "; 
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}
		
		$query_docs_no_oficiales = $query_docs_no_oficiales."GROUP BY doc_no_oficial.id_vendedor ORDER BY sum(doc_no_oficial.total) DESC";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "
			select id_vendedor, sum(total_gravadas) total_gravadas, sum(total_inafecta) total_inafecta, sum(total_exoneradas) total_exoneradas, sum(total_gratuitas) total_gratuitas, sum(total_exportacion) total_exportacion, sum(total_icbper) total_icbper, sum(sub_total) sub_total, sum(total_igv) total_igv, sum(total_isc) total_isc, sum(total_otr_imp) total_otr_imp, sum(total) total FROM  
			
				(($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales))  tbl_todo 
				
			GROUP BY id_vendedor ORDER BY sum(total) DESC limit $numero_registros";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales." limit $numero_registros";
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos." limit $numero_registros";
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$herramientas = new HerramientasController;
		$diferencia_fechas = $herramientas->comparar_fechas($fecha_inicio, $fecha_fin);
		$dias_diferencia = abs($diferencia_fechas['diferencia_primera_segunda']) + 1;

		$array_lista = array();
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$vendedor = $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $id_contribuyente)));
			if($vendedor) {
				$resp_detalle_compra = $this->get_detalle_compras_by_vendedor($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $fila->id_vendedor);
				$num_clientes_diferentes = $this->get_total_clientes_diferentes($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $fila->id_vendedor);
				
				if($resp_detalle_compra['respuesta'] == 'ok') {
					$detalle_documentos = $resp_detalle_compra['data'];

					if($dias_diferencia <= 0) {
						$promedio_num_clie = $num_clientes_diferentes;
						$promedio_total = $fila->total;
					} else {
						$promedio_num_clie = round($num_clientes_diferentes/$dias_diferencia, 2);
						$promedio_total = round($fila->total/$dias_diferencia, 2);
					}

					$array_lista[] = array(
						'vendedor' 			=> $vendedor->nombre.'<br />'.$vendedor->apellido, 
						'total_factura' 	=> $detalle_documentos['total_factura'],
						'total_boleta' 		=> $detalle_documentos['total_boleta'],
						'total_nota_venta' 	=> $detalle_documentos['total_nota_venta'],
						'total_nota_debito' 	=> $detalle_documentos['total_nota_debito'],
						'total_nota_credito' => $detalle_documentos['total_nota_credito'],
						'total' 			=> $fila->total,
						'total_clientes' 		=>$num_clientes_diferentes,
						'promedio_num_clie' => $promedio_num_clie,
						'promedio_total' 	=> $promedio_total,
						'idvendedor'			=> $fila->id_vendedor,
						'num_dias'			=> $dias_diferencia
					);
				}
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $array_lista;
		echo json_encode($resp);		
		exit();
	}

	public function get_total_clientes_diferentes($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $id_vendedor) {
		$query_docs_electronicos = "SELECT DISTINCT idcliente FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE)  ";

		if(!empty($id_vendedor)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor = ".$id_vendedor." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_no_oficiales = "SELECT DISTINCT idcliente FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) "; 
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor = ".$id_vendedor." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}
		
		$query_docs_no_oficiales = $query_docs_no_oficiales."GROUP BY doc_no_oficial.id_vendedor ORDER BY sum(doc_no_oficial.total) DESC";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "
			select count(DISTINCT idcliente) total FROM
				(($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales))  tbl_todo ";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = "SELECT count(DISTINCT idcliente) total FROM ($query_docs_no_oficiales) tbl_toto ";
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = "SELECT count(DISTINCT idcliente) total FROM ($query_docs_electronicos) tbl_toto ";
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$total = 0;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$total = $total + $fila->total;
		}

		return $total;
	}

	public function get_detalle_compras_by_vendedor($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_vendedores, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $id_vendedor) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		$query_docs_electronicos = "SELECT doc_electronico.id_vendedor id_vendedor, doc_electronico.id_tipodoc_electronico id_tipodoc_electronico, count(id_tipodoc_electronico) totaldocumentos, sum(doc_electronico.total_gravadas) total_gravadas, sum(doc_electronico.total_inafecta) total_inafecta, sum(doc_electronico.total_exoneradas) total_exoneradas, sum(doc_electronico.total_gratuitas) total_gratuitas, sum(doc_electronico.total_exportacion) total_exportacion, sum(doc_electronico.total_icbper) total_icbper, sum(doc_electronico.sub_total) sub_total, sum(doc_electronico.total_igv) total_igv, sum(doc_electronico.total_isc) total_isc, sum(doc_electronico.total_otr_imp) total_otr_imp, sum(doc_electronico.total) total FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_electronico.id_vendedor = $id_vendedor ";
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_electronicos = $query_docs_electronicos." GROUP BY doc_electronico.id_vendedor, doc_electronico.id_tipodoc_electronico ORDER BY sum(doc_electronico.total) DESC";

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT doc_no_oficial.id_vendedor id_vendedor, doc_no_oficial.id_tipodocumento id_tipodoc_electronico, count(id_tipodocumento) totaldocumentos, sum(doc_no_oficial.total_gravadas) total_gravadas, sum(doc_no_oficial.total_inafecta) total_inafecta, sum(doc_no_oficial.total_exoneradas) total_exoneradas, sum(doc_no_oficial.total_gratuitas) total_gratuitas, sum(doc_no_oficial.total_exportacion) total_exportacion, sum(doc_no_oficial.total_icbper) total_icbper, sum(doc_no_oficial.sub_total) sub_total, sum(doc_no_oficial.total_igv) total_igv, 0 isc, 0 total_otr_imp, sum(doc_no_oficial.total) total FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_no_oficial.id_vendedor = $id_vendedor "; 
		
		if(!empty($sql_ids_vendedores)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_vendedor in ".$sql_ids_vendedores." ";
		}

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}
		
		$query_docs_no_oficiales = $query_docs_no_oficiales."GROUP BY doc_no_oficial.id_vendedor, doc_no_oficial.id_tipodocumento ORDER BY sum(doc_no_oficial.total) DESC";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "
			select id_vendedor, id_tipodoc_electronico, sum(totaldocumentos) totaldocumentos, sum(total_gravadas) total_gravadas, sum(total_inafecta) total_inafecta, sum(total_exoneradas) total_exoneradas, sum(total_gratuitas) total_gratuitas, sum(total_exportacion) total_exportacion, sum(total_icbper) total_icbper, sum(sub_total) sub_total, sum(total_igv) total_igv, sum(total_isc) total_isc, sum(total_otr_imp) total_otr_imp, sum(total) total FROM  
			
				(($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales))  tbl_todo 
			
			WHERE id_vendedor = $id_vendedor 
			GROUP BY id_vendedor, id_tipodoc_electronico ORDER BY sum(total) DESC";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales;
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos;
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$array_datos = array();
		$array_datos['total_factura'] = 0;
		$array_datos['total_boleta'] = 0;
		$array_datos['total_nota_credito'] = 0;
		$array_datos['total_nota_debito'] = 0;
		$array_datos['total_nota_venta'] = 0;

		$array_datos['num_factura'] = 0;
		$array_datos['num_boleta'] = 0;
		$array_datos['num_nota_credito'] = 0;
		$array_datos['num_nota_debito'] = 0;
		$array_datos['num_nota_venta'] = 0;

		$array_datos['total_docs'] = 0;
		
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			if($fila->id_tipodoc_electronico == '01') {
				$array_datos['total_factura'] = $array_datos['total_factura'] + $fila->total;
				$array_datos['num_factura'] = $array_datos['num_factura'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '03') {
				$array_datos['total_boleta'] = $array_datos['total_boleta'] + $fila->total;
				$array_datos['num_boleta'] = $array_datos['num_boleta'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '07') {
				$array_datos['total_nota_credito'] = $array_datos['total_nota_credito'] + $fila->total;
				$array_datos['num_nota_credito'] = $array_datos['num_nota_credito'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '08') {
				$array_datos['total_nota_debito'] = $array_datos['total_nota_debito'] + $fila->total;
				$array_datos['num_nota_debito'] = $array_datos['num_nota_debito'] + $fila->totaldocumentos;
			}

			if($fila->id_tipodoc_electronico == '77') {
				$array_datos['total_nota_venta'] = $array_datos['total_nota_venta'] + $fila->total;
				$array_datos['num_nota_venta'] = $array_datos['num_nota_venta'] + $fila->totaldocumentos;
			}

			$array_datos['total_docs'] = $array_datos['total_docs'] + $fila->totaldocumentos;
		}

		$resp['respuesta'] = 'ok';
		$resp['data'] = $array_datos;
		return $resp;
	}

	public function getDetalleDocsByIdvendedorAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
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

			$datapost = $this->request->getPost();
			$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
			$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
			$id_codigomoneda = empty($datapost['tipos_monedas'])?'PEN':(($datapost['tipos_monedas'] == 'PEN')?'PEN':'USD');
			$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
			$estados_envio_sunat = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
			$id_vendedor = intval($datapost['id_vendedor']) + 0;
			$tipo_reporte = ($datapost['tiporeporte'] == 'documentos')?'documentos':'detallado';

			$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);
			
			$tipos_comprobantes_validos = array('03', '01', '07', '08', '77'); //las notas de crédito siempre descontarán en el total, (se sabe que algunos tipos de notas de crédito no necesariamente descuentan o eliminan una factura, sin embargo para agilizar el proceso, todas las notas se consideran que descuentan el stock)
			//$estados_enviosunat_validos = array('activo', 'aceptado', 'rechazado', 'pendiente', 'ticket', 'anulado'); //consideraciones, no se debe aceptar anulados, rechazados
			$estados_enviosunat_validos = array('activo', 'aceptado', 'pendiente', 'ticket'); //consideraciones, no se debe aceptar anulados, rechazados

			$tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

			$estados_envio_sunat = array_filter($estados_envio_sunat);
			if(count($estados_envio_sunat) > 0) {
				foreach($estados_envio_sunat as $tipo_env_sunat) {
					if(!in_array($tipo_env_sunat, $estados_enviosunat_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
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

			$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$lista = array();

			$sql_ids_sucursales = "";
			if(count($ids_sucursales) > 0) { 
				$sql_ids_sucursales = " (".implode(",", $ids_sucursales).") ";
			}

			$sql_estados_envio_docelectronico = "";
			$sql_estados_envio_docno_oficial = "";
			if(count($estados_envio_sunat) > 0) { 
				$sql_estados_envio_docelectronico = " ('".implode("','", $estados_envio_sunat)."') "; 

				if(in_array('aceptado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'activo' "; 
				} else if (in_array('anulado', $estados_envio_sunat)) {
					$sql_estados_envio_docno_oficial = " doc_no_oficial.estado_documento = 'inactivo' "; 
				}
			}

			$sql_ids_docs_electronicos = "";
			$sql_ids_docs_no_oficiales = "";
			if(count($tipos_comprobantes) > 0) {
				if(in_array('01', $tipos_comprobantes) || in_array('03', $tipos_comprobantes) || in_array('08', $tipos_comprobantes) || in_array('07', $tipos_comprobantes)) {
					$array_ids_docs_electronicos = array();
					if(in_array('01', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '01';
					}

					if(in_array('03', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '03';
					}

					if(in_array('08', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '08';
					}

					if(in_array('07', $tipos_comprobantes)) {
						$array_ids_docs_electronicos[] = '07';
					}

					$sql_ids_docs_electronicos = " ('".implode("','", $array_ids_docs_electronicos)."') "; 
				}

				if(in_array('77', $tipos_comprobantes)) {
					$sql_ids_docs_no_oficiales = '77';
				}
			}

			$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
			if(!$vendedor) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El ID del vendedor no es válido!';
				echo json_encode($resp);
                exit();
			}
			
			if($tipo_reporte == 'documentos') {
				$reporte_docs = $this->get_lista_docs_by_vendedor_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $id_vendedor);
			} else {
				$reporte_docs = $this->get_lista_docs_detallado_by_vendedor_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $id_vendedor);
			}

			if($reporte_docs['respuesta'] == 'error') {
				echo json_encode($reporte_docs);
				exit();
			}
			
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $reporte_docs['lista'];
			$resp['nombre_vendedor'] = $vendedor->nombre.' '.$vendedor->apellido;
			$resp['email_vendedor'] = $vendedor->email;
			$resp['celular_vendedor'] = $vendedor->celular;
			echo json_encode($resp);
			exit();
		}
	}

	public function get_lista_docs_by_vendedor_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $id_vendedor) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		$query_docs_electronicos = "SELECT doc_electronico.idcliente idcliente, doc_electronico.id_tipodoc_electronico id_tipodoc_electronico, doc_electronico.serie_comprobante serie_comprobante, doc_electronico.numero_comprobante numero_comprobante, doc_electronico.id_condicionpago id_condicionpago,  doc_electronico.cpago_nrooperacion cpago_nrooperacion,  doc_electronico.cpago_idbanco cpago_idbanco, doc_electronico.cpago_fechadeposito cpago_fechadeposito, doc_electronico.total_gravadas total_gravadas, doc_electronico.total_inafecta total_inafecta, doc_electronico.total_exoneradas total_exoneradas, doc_electronico.total_gratuitas total_gratuitas, doc_electronico.total_icbper total_icbper, doc_electronico.total_igv total_igv, doc_electronico.total_isc total_isc, doc_electronico.total_otr_imp total_otr_imp, doc_electronico.sub_total sub_total, doc_electronico.total total, doc_electronico.fecha_comprobante fecha_comprobante, doc_electronico.fecha_registro fecha_registro, doc_electronico.fecha_vto_comprobante fecha_vto_comprobante, doc_electronico.id_sucursal id_sucursal, doc_electronico.id_vendedor id_vendedor FROM `doc_electronico` where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_electronico.id_vendedor = $id_vendedor ";
		
		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT doc_no_oficial.idcliente idcliente, doc_no_oficial.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc_no_oficial.numero_comprobante numero_comprobante, doc_no_oficial.id_condicionpago id_condicionpago,  doc_no_oficial.cpago_nrooperacion cpago_nrooperacion,  doc_no_oficial.cpago_idbanco cpago_idbanco, doc_no_oficial.cpago_fechadeposito cpago_fechadeposito, doc_no_oficial.total_gravadas total_gravadas, doc_no_oficial.total_inafecta total_inafecta, doc_no_oficial.total_exoneradas total_exoneradas, doc_no_oficial.total_gratuitas total_gratuitas, doc_no_oficial.total_icbper total_icbper, doc_no_oficial.total_igv total_igv, 0 total_isc, 0 total_otr_imp, doc_no_oficial.sub_total sub_total, doc_no_oficial.total total, doc_no_oficial.fecha_comprobante fecha_comprobante, doc_no_oficial.fecha_registro fecha_registro, doc_no_oficial.fecha_comprobante fecha_vto_comprobante, doc_no_oficial.id_sucursal id_sucursal, doc_no_oficial.id_vendedor id_vendedor FROM `doc_no_oficial` where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_no_oficial.id_vendedor = $id_vendedor ";

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales)";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales;
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos;
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$herramientas = new HerramientasController;
		try {
			$array_lista = array();
			$n = 0;
			while($fila = $sentencia->fetch()) {
				$fila = (object)$fila;
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $id_contribuyente)));
				$datos_vendedor = '';
				if($vendedor) {
					$datos_vendedor = $vendedor->nombre.' '.$vendedor->apellido.'<br >'.$vendedor->email;
				}

				$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $id_contribuyente)));
				$datos_cliente = '';
				if($cliente) {
					$datos_cliente = $cliente->razon_social.' - '.$cliente->num_doc;
				}
				
				$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $id_contribuyente)));
				$datos_sucursal = '';
				if($sucursal) {
					$datos_sucursal = $sucursal->nombre.'<br />'.$sucursal->direccion;
				}

				$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||a4");
                $string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||ticket");

                $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		        $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

				$ruta_pdf = 'javascript:void(0)';
				$serie_comprobante = $fila->serie_comprobante;
				if ($fila->id_tipodoc_electronico == '01') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '03') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '07') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '08') {
					$ruta_pdf = $url_a4;
				} else if($fila->id_tipodoc_electronico == '77') {
					$ruta_pdf = $url_a4;
					$serie_comprobante = 'NV';
				} else {
					$ruta_pdf = $url_a4;
				}

				$condicion_pago_nombre = 'CONTADO';
				if(!empty($fila->id_condicionpago)) {
					$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $id_contribuyente)));
					if($condicion_pago) {
						$condicion_pago_nombre = strtoupper($condicion_pago->tipo);
					}
				}
				
				$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
				$cpago_fechadeposito = '';
				$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
				$nombre_banco_deposito = '';

				if(intval($cpago_idbanco) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
					if($cuenta_banco_transferencia) {
						$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
					}
				}

				if(!empty($fila->cpago_fechadeposito)) {
					$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
				}

				if($fila->id_tipodoc_electronico == '77') {
					$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
				} else {
					$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
				}

				if(count($abonos) > 0) {
					$lista_nrooperacion = array();
					$lista_fechadeposito = array();
					$lista_idbanco = array();
					$lista_nombre_banco = array();

					foreach($abonos as $abono) {
						if(!empty($abono->cpago_nrooperacion)) {
							$lista_nrooperacion[] = $abono->cpago_nrooperacion;
						}

						if(!empty($abono->fechadeposito)) {
							$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
						}
						
						if(intval($abono->idbanco) > 0 ) {
							$lista_idbanco[] = $abono->idbanco;
							$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
							if($cuenta_banco_transferencia) {
								$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
							}
						}
					}


					$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
					$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
					$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
					$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
				}

				$array_lista[] = array(
					'fecha_registro'			=> date("d-m-Y / H:i A", strtotime($fila->fecha_registro)),
					'fecha_comprobante'			=> date("d-m-Y", strtotime($fila->fecha_comprobante)),
					'id_tipodoc_electronico' 	=> $fila->id_tipodoc_electronico,
					'serie_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$serie_comprobante."</a>",
					'numero_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$fila->numero_comprobante."</a>",

					'cliente'					=> $datos_cliente,
					
					'condicion_pago'			=> $condicion_pago_nombre, //
					'cpago_nrooperacion'		=> $cpago_nrooperacion,
					'cpago_fechadeposito'		=> $cpago_fechadeposito,
					'cpago_idbanco'				=> $cpago_idbanco,
					'nombre_banco_deposito'		=> $nombre_banco_deposito,

					
					'total_gravadas'			=> $fila->total_gravadas,
					'total_inafecta'			=> $fila->total_inafecta,
					'total_exoneradas'			=> $fila->total_exoneradas,
					'total_gratuitas'			=> $fila->total_gratuitas,
					'total_icbper'				=> $fila->total_icbper,
					'total_igv'					=> $fila->total_igv,
					'sub_total'					=> $fila->sub_total,
					'total'						=> $fila->total,
					'fecha_vto_comprobante'		=> date("d-m-Y", strtotime($fila->fecha_vto_comprobante)),
					'id_sucursal'				=> $fila->id_sucursal,
					'id_vendedor'				=> $fila->id_vendedor,
					'datos_vendedor'			=> $datos_vendedor,
					'datos_sucursal'			=> $datos_sucursal,
					'id_codigomoneda'			=> $id_codigomoneda
				);
			}

		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			echo json_encode($resp);
			exit();
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $array_lista;
		return $resp;
	}

	public function get_lista_docs_detallado_by_vendedor_iddoc($id_contribuyente, $tipo_envio_sunat, $id_codigomoneda, $sql_ids_sucursales, $sql_estados_envio_docelectronico, $sql_estados_envio_docno_oficial, $sql_ids_docs_electronicos, $sql_ids_docs_no_oficiales, $fecha_inicio, $fecha_fin, $id_vendedor) {
		//CONDICIONES PARA FORMAR EL STRING SQL PARA LA TABLA DOC_ELECTRONICO
		$query_docs_electronicos = "SELECT doc_electronico.idcliente idcliente, doc_electronico.id_tipodoc_electronico id_tipodoc_electronico, doc_electronico.serie_comprobante serie_comprobante, doc_electronico.numero_comprobante numero_comprobante, doc_electronico.id_condicionpago id_condicionpago,  doc_electronico.cpago_nrooperacion cpago_nrooperacion,  doc_electronico.cpago_idbanco cpago_idbanco, doc_electronico.cpago_fechadeposito cpago_fechadeposito, doc_electronico.total_gravadas total_gravadas, doc_electronico.total_inafecta total_inafecta, doc_electronico.total_exoneradas total_exoneradas, doc_electronico.total_gratuitas total_gratuitas, doc_electronico.total_icbper total_icbper, doc_electronico.total_igv total_igv, doc_electronico.total_isc total_isc, doc_electronico.total_otr_imp total_otr_imp, doc_electronico.sub_total doc_sub_total, doc_electronico.total total, doc_electronico.fecha_comprobante fecha_comprobante, doc_electronico.fecha_registro fecha_registro, doc_electronico.fecha_vto_comprobante fecha_vto_comprobante, doc_electronico.id_sucursal id_sucursal, doc_electronico.id_vendedor id_vendedor, detalle_doc.id_unidad_medida, detalle_doc.unidad_medida, detalle_doc.cantidad, detalle_doc.precio, detalle_doc.sub_total, detalle_doc.id_codigoprecio, detalle_doc.igv, detalle_doc.isc, detalle_doc.icbper, detalle_doc.importe, detalle_doc.id_tipoafectacionigv, detalle_doc.id_producto, detalle_doc.codigo_producto, detalle_doc.descripcion, detalle_doc.precio_sin_igv, detalle_doc.peso FROM
		`doc_electronico` INNER JOIN detalle_doc ON (doc_electronico.id_contribuyente = detalle_doc.id_contribuyente and doc_electronico.id_tipodoc_electronico = detalle_doc.id_tipodoc_electronico and doc_electronico.serie_comprobante = detalle_doc.serie_comprobante and doc_electronico.numero_comprobante = detalle_doc.numero_comprobante and doc_electronico.tipo_envio_sunat = detalle_doc.tipo_envio_sunat) where doc_electronico.tipo_envio_sunat = '$tipo_envio_sunat' and doc_electronico.id_contribuyente = $id_contribuyente and doc_electronico.id_tipodoc_electronico <> '09' and doc_electronico.id_codigomoneda = '$id_codigomoneda' and doc_electronico.total > 0 and doc_electronico.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_electronico.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_electronico.id_vendedor = $id_vendedor ";
		
		if(!empty($sql_ids_sucursales)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_electronicos)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.id_tipodoc_electronico in ".$sql_ids_docs_electronicos." ";
		}

		if(!empty($sql_estados_envio_docelectronico)) {
			$query_docs_electronicos = $query_docs_electronicos."  and doc_electronico.estado_envio_sunat in ".$sql_estados_envio_docelectronico." ";
		}

		$query_docs_electronicos = $query_docs_electronicos." ORDER BY doc_electronico.id_contribuyente, doc_electronico.id_tipodoc_electronico, doc_electronico.serie_comprobante, doc_electronico.numero_comprobante, doc_electronico.tipo_envio_sunat ";

		//CONDICIONES PARA FORMAR EL STRING SQL EN LA TABLA DOC_NO_OFICINALES
		$query_docs_no_oficiales = "SELECT
		doc_no_oficial.idcliente idcliente, doc_no_oficial.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc_no_oficial.numero_comprobante numero_comprobante, doc_no_oficial.id_condicionpago id_condicionpago,  doc_no_oficial.cpago_nrooperacion cpago_nrooperacion,  doc_no_oficial.cpago_idbanco cpago_idbanco, doc_no_oficial.cpago_fechadeposito cpago_fechadeposito,  doc_no_oficial.total_gravadas total_gravadas, doc_no_oficial.total_inafecta total_inafecta, doc_no_oficial.total_exoneradas total_exoneradas, doc_no_oficial.total_gratuitas total_gratuitas, doc_no_oficial.total_icbper total_icbper, doc_no_oficial.total_igv total_igv, 0 total_isc, 0 total_otr_imp, doc_no_oficial.sub_total doc_sub_total, doc_no_oficial.total total, doc_no_oficial.fecha_comprobante fecha_comprobante, doc_no_oficial.fecha_registro fecha_registro, doc_no_oficial.fecha_comprobante fecha_vto_comprobante, doc_no_oficial.id_sucursal id_sucursal, doc_no_oficial.id_vendedor id_vendedor, detalle_docnooficial.id_unidad_medida, detalle_docnooficial.unidad_medida, detalle_docnooficial.cantidad, detalle_docnooficial.precio, detalle_docnooficial.sub_total, detalle_docnooficial.id_codigoprecio, detalle_docnooficial.igv, 0 isc, detalle_docnooficial.icbper, detalle_docnooficial.importe, detalle_docnooficial.id_tipoafectacionigv, detalle_docnooficial.id_producto, detalle_docnooficial.codigo_producto, detalle_docnooficial.descripcion, detalle_docnooficial.precio_sin_igv, detalle_docnooficial.peso FROM `doc_no_oficial` INNER JOIN detalle_docnooficial ON (doc_no_oficial.id_contribuyente = detalle_docnooficial.id_contribuyente and doc_no_oficial.id_tipodocumento = detalle_docnooficial.id_tipodocumento and doc_no_oficial.numero_comprobante = detalle_docnooficial.numero_comprobante and doc_no_oficial.modalidad = detalle_docnooficial.modalidad) where doc_no_oficial.modalidad = '$tipo_envio_sunat' and doc_no_oficial.id_contribuyente = $id_contribuyente and doc_no_oficial.id_codigomoneda = '$id_codigomoneda' and doc_no_oficial.id_tipodocumento <> '88' and doc_no_oficial.total > 0 and doc_no_oficial.fecha_registro >= CAST('$fecha_inicio' AS DATE) and doc_no_oficial.fecha_registro <= CAST('$fecha_fin' AS DATE) and doc_no_oficial.id_vendedor = $id_vendedor ";

		if(!empty($sql_ids_sucursales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_sucursal in ".$sql_ids_sucursales." ";
		}

		if(!empty($sql_ids_docs_no_oficiales)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and doc_no_oficial.id_tipodocumento = '77' ";
		}

		if(!empty($sql_estados_envio_docno_oficial)) {
			$query_docs_no_oficiales = $query_docs_no_oficiales."  and ".$sql_estados_envio_docno_oficial." ";
		}

		$query_docs_no_oficiales = $query_docs_no_oficiales." ORDER BY doc_no_oficial.id_contribuyente, doc_no_oficial.id_tipodocumento, doc_no_oficial.numero_comprobante, doc_no_oficial.modalidad ";

		if((!empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) || (empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales))) {
			$query = "($query_docs_electronicos) UNION ALL ($query_docs_no_oficiales)";
		} else if(empty($sql_ids_docs_electronicos) && !empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_no_oficiales;
		} else if(!empty($sql_ids_docs_electronicos) && empty($sql_ids_docs_no_oficiales)) {
			$query = $query_docs_electronicos;
		}
		
		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al generar el reporte';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$array_lista = array();
		$n = 0;
		$herramientas = new HerramientasController;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_vendedor, 'id_contribuyente' => $id_contribuyente)));
			$datos_vendedor = '';
			if($vendedor) {
				$datos_vendedor = $vendedor->nombre.' '.$vendedor->apellido.'<br >'.$vendedor->email;
			}

			$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $id_contribuyente)));
			$datos_cliente = '';
			if($cliente) {
				$datos_cliente = $cliente->razon_social.' - '.$cliente->num_doc;
			}

			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->id_sucursal, 'id_contribuyente' => $id_contribuyente)));
			$datos_sucursal = '';
			if($sucursal) {
				$datos_sucursal = $sucursal->nombre.'<br />'.$sucursal->direccion;
			}

			$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||a4");
			$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$fila->id_tipodoc_electronico."||".$fila->serie_comprobante."||".$fila->numero_comprobante."||".$tipo_envio_sunat."||ticket");

			$url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
			$url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $fila->id_producto, 'id_contribuyente' => $id_contribuyente)));

			$ruta_pdf = 'javascript:void(0)';
			$serie_comprobante = $fila->serie_comprobante;
			if ($fila->id_tipodoc_electronico == '01') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '03') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '07') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '08') {
				$ruta_pdf = $url_a4;
			} else if($fila->id_tipodoc_electronico == '77') {
				$ruta_pdf = $url_a4;
				$serie_comprobante = 'NV';
			} else {
				$ruta_pdf = $url_a4;
			}

			$condicion_pago_nombre = 'CONTADO';
			if(!empty($fila->id_condicionpago)) {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $id_contribuyente)));
				if($condicion_pago) {
					$condicion_pago_nombre = strtoupper($condicion_pago->tipo);
				}
			}
			
			$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
			$cpago_fechadeposito = '';
			$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
			$nombre_banco_deposito = '';

			if(intval($cpago_idbanco) > 0) {
				$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
				if($cuenta_banco_transferencia) {
					$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
				}
			}

			if(!empty($fila->cpago_fechadeposito)) {
				$cpago_fechadeposito = date("d-m-Y", strtotime($fila->cpago_fechadeposito));
			}

			if($fila->id_tipodoc_electronico == '77') {
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			} else {
				$abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			}

			if(count($abonos) > 0) {
				$lista_nrooperacion = array();
				$lista_fechadeposito = array();
				$lista_idbanco = array();
				$lista_nombre_banco = array();

				foreach($abonos as $abono) {
					if(!empty($abono->cpago_nrooperacion)) {
						$lista_nrooperacion[] = $abono->cpago_nrooperacion;
					}

					if(!empty($abono->fechadeposito)) {
						$lista_fechadeposito[] = date("d-m-Y", strtotime($abono->fechadeposito));
					}
					
					if(intval($abono->idbanco) > 0 ) {
						$lista_idbanco[] = $abono->idbanco;
						$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($abono->idbanco))));
						if($cuenta_banco_transferencia) {
							$lista_nombre_banco[] = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
						}
					}
				}


				$cpago_nrooperacion = (count($lista_nrooperacion) > 0)?implode(",", $lista_nrooperacion):'';
				$cpago_fechadeposito = (count($lista_fechadeposito) > 0)?implode(",", $lista_fechadeposito):'';
				$cpago_idbanco = (count($lista_idbanco) > 0)?implode(",", $lista_idbanco):'';
				$nombre_banco_deposito = (count($lista_nombre_banco) > 0)?implode(",", $lista_nombre_banco):'';
			}

			$array_lista[] = array(
				'fecha_registro'			=> date("d-m-Y / H:i A", strtotime($fila->fecha_registro)),
				'fecha_comprobante'			=> date("d-m-Y", strtotime($fila->fecha_comprobante)),
				'id_tipodoc_electronico' 	=> $fila->id_tipodoc_electronico,
				'serie_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$serie_comprobante."</a>",
				'numero_comprobante' 		=> "<a href='$ruta_pdf' target='_blank'>".$fila->numero_comprobante."</a>",

				'cliente'					=> $datos_cliente,

				'condicion_pago'			=> $condicion_pago_nombre, //
				'cpago_nrooperacion'		=> $cpago_nrooperacion,
				'cpago_fechadeposito'		=> $cpago_fechadeposito,
				'cpago_idbanco'				=> $cpago_idbanco,
				'nombre_banco_deposito'		=> $nombre_banco_deposito,
				
				'total_gravadas'			=> $fila->total_gravadas,
				'total_inafecta'			=> $fila->total_inafecta,
				'total_exoneradas'			=> $fila->total_exoneradas,
				'total_gratuitas'			=> $fila->total_gratuitas,
				'total_icbper'				=> $fila->total_icbper,
				'total_igv'					=> $fila->total_igv,
				'doc_sub_total'				=> $fila->doc_sub_total,
				'total'						=> $fila->total,
				'fecha_vto_comprobante'		=> date("d-m-Y", strtotime($fila->fecha_vto_comprobante)),
				'id_sucursal'				=> $fila->id_sucursal,
				'id_vendedor'				=> $fila->id_vendedor,
				'datos_vendedor'			=> $datos_vendedor,
				'datos_sucursal'			=> $datos_sucursal,
				'id_codigomoneda'			=> $id_codigomoneda,
				
				//detalle
				'codigo_producto'			=> $fila->codigo_producto,
				'descripcion'				=> $fila->descripcion,
				'id_unidad_medida'			=> $fila->id_unidad_medida,
				'unidad_medida'				=> $fila->unidad_medida,
				'id_tipoafectacionigv' 		=> $fila->id_tipoafectacionigv,
				'cantidad'					=> $fila->cantidad + 0,
				'precio'					=> $fila->precio + 0,
				'sub_total'					=> $fila->sub_total + 0,
				'igv'						=> $fila->igv,
				'icbper'					=> $fila->icbper,
				'importe'					=> round($fila->sub_total + $fila->igv, 2)
			);
		}

		
		
		$resp['respuesta'] = 'ok';
		$resp['lista'] = $array_lista;
		return $resp;
	}

	public function liquidacionImpuestoMensualAction() {
		$this->setTitle('Liquidación de Impuesto Mensual');
        $this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");

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

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")

			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
			->addJs($this->baseUri . "public/js/reportes/liquidacion_impuesto_mensual.js?i=".rand());

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

		$this->view->contribuyente = $contribuyente;
	}

	public function getLiquidacionMensualAction() {
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

			$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
			$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);

			$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
			$periodo_seleccionado = $fecha_inicio;
			$resp = $this->informe_igv_renta($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $periodo_seleccionado);
			echo json_encode($resp);
			exit();
		}
	}

	public function informe_igv_renta($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $periodo_seleccionado, $idsucursal = 0, $tipo_comprobante = '-1') {

		$lista_ventas = $this->detalledocumentosemitidos($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente);

		$lista_compras = $this->get_detallecompras($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, $periodo_seleccionado);

		$total_ventas_afectas = 0;
		$total_descuentos = 0;

		$total_compras_nacionales = 0;

		$compras_importadas = 0; //Compras importadas, se refiere a las importaciones, en este tipo de compras se contabilizan con DUA (NO con facturas) en la practica es casi imposible que algun cliente necesita esta opcion
		$total_no_gravadas_internas = 0; //NO gravadas internas, son compras que NO estan afectos al IGV, como son las compras con Boletas, comisiones de bancos (ahora dan facturas), compras en zonas que estan exoneradas del IGV
		$total_no_gravadas_importadas = 0; //No Gravadas Importadas, son importaciones que ingresan sin estar afecto al IGV, es casi nulo igual que las compras importadas

		$total_saldo_mes = 0;
		$total_saldo_a_favor = 0;
		$total_percepciones_mes = 0;
		$total_retenciones_mes = 0;
		$total_retenciones_periodo = 0;
		$tributo_a_pagar_igv = 0;

		foreach($lista_ventas as $venta) {
			$venta = (object)$venta;
			if($venta->id_tipodoc_electronico == '01' || $venta->id_tipodoc_electronico == '03' || $venta->id_tipodoc_electronico == '08') {
				$total_ventas_afectas = $total_ventas_afectas + $venta->total_gravadas;
			}

			if($venta->id_tipodoc_electronico == '07') {
				$total_descuentos = $total_descuentos + $venta->total_gravadas;
			}
		}

		foreach($lista_compras as $compra) {
			$compra = (object)$compra;
			if($compra->id_tipodoc_electronico == '01') {
				$total_compras_nacionales = $total_compras_nacionales + $compra->total_gravadas;
			}
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$mype = $contribuyente->mype;
		$renta_3ra_coeficiente = $contribuyente->renta_3ra_coeficiente;
		$renta_3ra_porcentaje = $contribuyente->renta_3ra_porcentaje;

		$resp['respuesta'] = 'ok';
		$resp['total_ventas_afectas'] = $total_ventas_afectas;
		$resp['total_descuentos'] = $total_descuentos;
		$resp['total_compras_nacionales'] = $total_compras_nacionales;
		$resp['compras_importadas'] = $compras_importadas;
		$resp['total_no_gravadas_internas'] = $total_no_gravadas_internas;
		$resp['total_no_gravadas_importadas'] = $total_no_gravadas_importadas;
		$resp['total_saldo_mes'] = $total_saldo_mes;
		$resp['total_saldo_a_favor'] = $total_saldo_a_favor;
		$resp['total_percepciones_mes'] = $total_percepciones_mes;
		$resp['total_retenciones_mes'] = $total_retenciones_mes;
		$resp['total_retenciones_periodo'] = $total_retenciones_periodo;
		$resp['tributo_a_pagar_igv'] = $tributo_a_pagar_igv;
		$resp['lista_ventas'] = $lista_ventas;
		$resp['lista_compras'] = $lista_compras;
		return $resp;
	}

	public function reporteDetalladoComprasAction() {
		$this->setTitle('Compras - Reporte detallado');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
		
		$this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
			->addJs($this->baseUri . "public/js/reportes/reporte_detallado_compras.js?j=".rand());

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
		
		$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		
		$this->view->lista_sucursales = $lista_sucursales;
		$this->view->lista_usuarios = $lista_usuarios;

		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_verreportes_reportes', 'detalle_ventas') == false) {
					$this->response->redirect('dashboard');
				}
			}
		}
	}

	public function getReporteDetalladoComprasAction() {
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

			$resp_detalle = $this->get_reportedetallado_compras($usuario, $datapost);
			echo json_encode($resp_detalle);
			exit();
		}
	}

	public function get_reportedetallado_compras($usuario, $datapost) {
		$id_contribuyente = $usuario->id_contribuyente;
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			return $resp;
		}
		
		$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
		$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
		$fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
		$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];
		$ids_cod_monedas = empty($datapost['tipos_monedas'])?array():explode(',', $datapost['tipos_monedas']);
		$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
		$estados_documentos = empty($datapost['estados_documentos'])?array():explode(',', $datapost['estados_documentos']);
		$criteriobusqueda = empty($datapost['select_criteriobusqueda'])?'fecha_documento':$datapost['select_criteriobusqueda'];
		
		$array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
		$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);

		$tipos_validos_monedas = array('PEN', 'USD');
		$tipos_comprobantes_validos = array('03', '01', '07', '08', '00');
		$estados_documentos_validos = array('activo', 'inactivo');
		
		$ids_cod_monedas = array_filter($ids_cod_monedas);
		if(count($ids_cod_monedas) > 0) {
			foreach($ids_cod_monedas as $idcodmoneda) {
				if(!in_array($idcodmoneda, $tipos_validos_monedas)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Código Moneda';
					$resp['mensaje'] = 'El código de Moneda Seleccionado es Inválido!';
					return $resp;
				}
			}
		}

		$tipos_comprobantes = array_filter($tipos_comprobantes);
		if(count($tipos_comprobantes) > 0) {
			foreach($tipos_comprobantes as $id_tipo_doc) {
				if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

		$estados_documentos = array_filter($estados_documentos);
		if(count($estados_documentos) > 0) {
			foreach($estados_documentos as $tipo_env_sunat) {
				if(!in_array($tipo_env_sunat, $estados_documentos_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

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
		
		$fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
		$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			return $resp;
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			return $resp;
		}

		$campo_busqueda_fecha = 'fecha_comprobante';
		if($criteriobusqueda == 'fecha_registro') {
			$campo_busqueda_fecha = 'fecha_registro';
		}
	
		$sql_reporte = "SELECT c.id_compra, c.id_contribuyente, c.idsucursal, c.id_tipodoc_electronico, c.serie_comprobante, c.numero_comprobante, c.tipo_envio_sunat, c.id_proveedor, c.fecha_registro, c.fecha_comprobante, c.estado, c.total_gravadas, c.total_inafecta, c.total_exoneradas, c.total_gratuitas, c.total_exportacion, c.total_descuento, c.porcentaje_descuento_total, c.sub_total, c.porcentaje_igv, c.total_igv, c.total_isc, c.total_icbper, c.total_otr_imp, c.total, c.id_codigomoneda, c.tipo_cambio_sunat, c.id_usuario, c.nota, c.id_condicionpago, c.monto_adeudado, c.monto_adeudado_inicial, c.fecha_pagopendiente, c.cpago_nrooperacion, c.cpago_fechadeposito, c.cpago_idbanco, c.tipo_compra, 
		
		s.codigo, s.nombre, 

		p.num_doc proveedor_num_ruc, p.razon_social proveedor_razon_social, p.email proveedor_email, p.celular proveedor_celular, 

		sunat_tipodoc.descripcion nombre_doc_electronico, 

		sunat_moneda.simbolo moneda_simbolo, sunat_moneda.nombre moneda_nombre 
		
		FROM compra c 
			INNER JOIN sucursal s ON c.idsucursal = s.idsucursal 
			INNER JOIN proveedor p ON c.id_proveedor = p.id_proveedor 
			INNER JOIN sunat_tipodocelectronico sunat_tipodoc ON c.id_tipodoc_electronico = sunat_tipodoc.id_tipodoc_electronico 
			INNER JOIN sunat_moneda ON c.id_codigomoneda = sunat_moneda.id_codigomoneda 
		
		WHERE 
		c.id_contribuyente =  :id_contribuyente and c.tipo_envio_sunat = :tipo_envio_sunat and c.$campo_busqueda_fecha >= CAST(:fecha_inicio AS DATE) and c.$campo_busqueda_fecha <= CAST(:fecha_fin AS DATE)  
		";

		if(count($ids_vendedores) > 0){ $sql_reporte = $sql_reporte." and c.id_usuario in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $sql_reporte = $sql_reporte." and c.idsucursal in (".implode(",", $ids_sucursales).") "; }
		if(count($ids_cod_monedas) > 0){ $sql_reporte = $sql_reporte." and c.id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
		if(count($tipos_comprobantes) > 0){ $sql_reporte = $sql_reporte." and c.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; }
		if(count($estados_documentos) > 0){ $sql_reporte = $sql_reporte." and c.estado in ('".implode("','", $estados_documentos)."') "; }

		/*
		$sql_reporte = str_replace(':id_contribuyente', $id_contribuyente, $sql_reporte);
		$sql_reporte = str_replace(':fecha_inicio', "'$fecha_inicio'", $sql_reporte);
		$sql_reporte = str_replace(':fecha_fin', "'$fecha_fin'", $sql_reporte);
		$sql_reporte = str_replace(':tipo_envio_sunat', "'$tipo_envio_sunat'", $sql_reporte);
		echo $sql_reporte;
		exit();
		*/

		try {
			$sentencia = $this->db->prepare($sql_reporte);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] =  $e->getMessage();
			return $resp;
		}

		$lista_documentos = array();
		$detalle_documentos = array();
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$n++;

			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $fila->idsucursal, 'id_contribuyente' => $id_contribuyente)));

			$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $fila->id_usuario, 'id_contribuyente' => $id_contribuyente)));
			
			$condicion_pago_tipo = 'CONTADO';
			$condicion_pago_nombre = 'Contado';
			if(!empty($fila->id_condicionpago)) {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago, 'id_contribuyente' => $usuario->id_contribuyente)));
				if($condicion_pago) {
					$condicion_pago_tipo = strtoupper($condicion_pago->tipo);
					$condicion_pago_nombre = $condicion_pago->condicionpago;
				}
			}

			$cpago_nrooperacion = empty($fila->cpago_nrooperacion)?'':$fila->cpago_nrooperacion;
			$cpago_fechadeposito = !empty($fila->cpago_fechadeposito)?date("d-m-Y", strtotime($fila->cpago_fechadeposito)):'';
			$cpago_idbanco = empty($fila->cpago_idbanco)?'':$fila->cpago_idbanco;
			$nombre_banco_deposito = '';
			if(intval($cpago_idbanco) > 0) {
				$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($fila->cpago_idbanco))));
				if($cuenta_banco_transferencia) {
					$nombre_banco_deposito = $cuenta_banco_transferencia->nombre_banco.' - '.$cuenta_banco_transferencia->nro_cuenta;
				}
			}

			$lista_documentos[] = array(

				'sucursal_idsucursal'		=> $fila->idsucursal,
				'sucursal_nombre'			=> $sucursal->nombre,
				'vendedor_idusuario'		=> $fila->id_usuario,
				'vendedor_nombre_completo'	=> $vendedor->nombre.' '.$vendedor->apellido,
				'condicion_pago_tipo'		=> $condicion_pago_tipo,
				'condicion_pago_nombre'		=> $condicion_pago_nombre,
				'cpago_nrooperacion'		=> $cpago_nrooperacion,
				'cpago_fechadeposito'		=> $cpago_fechadeposito,
				'cpago_idbanco'				=> $cpago_idbanco,
				'nombre_banco_deposito'		=> $nombre_banco_deposito,

				'id_tipodoc_electronico'	=> $fila->id_tipodoc_electronico,
				'nombre_doc_electronico'	=> $fila->nombre_doc_electronico,
				'serie_comprobante'			=> $fila->serie_comprobante,
				'numero_comprobante'		=> $fila->numero_comprobante,
				'id_proveedor'				=> $fila->id_proveedor,
				'proveedor_num_ruc'			=> $fila->proveedor_num_ruc,
				'proveedor_razon_social'	=> $fila->proveedor_razon_social,
				'proveedor_email'			=> $fila->proveedor_email,
				'proveedor_celular'			=> $fila->proveedor_celular,
				'fecha_registro'			=> $fila->fecha_registro,
				'fecha_comprobante'			=> $fila->fecha_comprobante,
				'total_gravadas'			=> $fila->total_gravadas,
				'total_inafecta'			=> $fila->total_inafecta,
				'total_exoneradas'			=> $fila->total_exoneradas,
				'total_gratuitas'			=> $fila->total_gratuitas,
				'total_exportacion'			=> $fila->total_exportacion,
				'total_descuento'			=> $fila->total_descuento,
				'sub_total'					=> $fila->sub_total,
				'total_igv'					=> $fila->total_igv,
				'total_isc'					=> $fila->total_isc,
				'total_icbper'				=> $fila->total_icbper,
				'total_otr_imp'				=> $fila->total_otr_imp,
				'total'						=> $fila->total,
				'moneda_nombre'				=> $fila->moneda_nombre,
				'tipo_cambio_sunat'			=> $fila->tipo_cambio_sunat,
				'nota'						=> $fila->nota
			);
			
			$sql_detalle_compra = "SELECT d.id_unidad_medida, d.cantidad, d.precio, d.sub_total, d.id_codigoprecio,  d.igv, d.isc, d.importe, d.id_tipoafectacionigv, d.id_producto, d.codigo_producto, d.descripcion, d.precio_sin_igv, d.tipo_unidad, d.id_presentacion, 

			s_tafectacion.descripcion afectacionigv_nombre
			
			FROM detalle_compra d 
				INNER JOIN sunat_tipoafectacionigv s_tafectacion ON d.id_tipoafectacionigv = s_tafectacion.id_tipoafectacionigv 
			
			WHERE d.id_compra = $fila->id_compra ";

			try {
				$sentencia2 = $this->db->prepare($sql_detalle_compra);
				$sentencia2->execute();
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] =  '=>'.$e->getMessage();
				return $resp;
			}

			while($item = $sentencia2->fetch()) {
				$item = (object)$item;

				$idunidad_medida = intval($item->id_unidad_medida);
				$cantidad_presentacion = 1;
				if($item->tipo_unidad == 'PRE') {
					$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion: and estado = 'activo'", 'bind' => array('id_presentacion' => $item->id_presentacion)));
					if($presentacion) {
						$idunidad_medida = intval($presentacion->idunidad);
						$cantidad_presentacion = round($item->cantidad*$presentacion->cantidad_und_base, 4);
					}
				}

				$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad_medida)));
				if(!$unidadmedida) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No se encuentra la unidad seleccionada para el producto: '.$item->descripcion.' ..';
					return $resp;
				}

				$detalle_documentos[] = array(
					'sucursal_idsucursal'		=> $fila->idsucursal,
					'sucursal_nombre'			=> $sucursal->nombre,
					'vendedor_idusuario'		=> $fila->id_usuario,
					'vendedor_nombre_completo'	=> $vendedor->nombre.' '.$vendedor->apellido,
					'condicion_pago_tipo'		=> $condicion_pago_tipo,
					'condicion_pago_nombre'		=> $condicion_pago_nombre,
					'cpago_nrooperacion'		=> $cpago_nrooperacion,
					'cpago_fechadeposito'		=> $cpago_fechadeposito,
					'cpago_idbanco'				=> $cpago_idbanco,
					'nombre_banco_deposito'		=> $nombre_banco_deposito,
					
					'id_tipodoc_electronico'	=> $fila->id_tipodoc_electronico,
					'nombre_doc_electronico'	=> $fila->nombre_doc_electronico,
					'serie_comprobante'			=> $fila->serie_comprobante,
					'numero_comprobante'		=> $fila->numero_comprobante,
					'id_proveedor'				=> $fila->id_proveedor,
					'proveedor_num_ruc'			=> $fila->proveedor_num_ruc,
					'proveedor_razon_social'	=> $fila->proveedor_razon_social,
					'proveedor_email'			=> $fila->proveedor_email,
					'proveedor_celular'			=> $fila->proveedor_celular,
					'fecha_registro'			=> $fila->fecha_registro,
					'fecha_comprobante'			=> $fila->fecha_comprobante,
					'total_gravadas'			=> $fila->total_gravadas,
					'total_inafecta'			=> $fila->total_inafecta,
					'total_exoneradas'			=> $fila->total_exoneradas,
					'total_gratuitas'			=> $fila->total_gratuitas,
					'total_exportacion'			=> $fila->total_exportacion,
					'total_descuento'			=> $fila->total_descuento,
					'sub_total'					=> $fila->sub_total,
					'total_igv'					=> $fila->total_igv,
					'total_isc'					=> $fila->total_isc,
					'total_icbper'				=> $fila->total_icbper,
					'total_otr_imp'				=> $fila->total_otr_imp,
					'total'						=> $fila->total,
					'moneda_nombre'				=> $fila->moneda_nombre,
					'tipo_cambio_sunat'			=> $fila->tipo_cambio_sunat,
					'nota'						=> $fila->nota,

					'id_producto'				=> $item->id_producto,
					'codigo_producto'			=> $item->codigo_producto,
					'producto_descripcion'		=> $item->descripcion,
					'cantidad'					=> $item->cantidad,
					'precio'					=> $item->precio,
					'precio_sin_igv'			=> $item->precio_sin_igv,
					'sub_total'					=> $item->sub_total,
					'igv'						=> $item->igv,
					'importe'					=> $item->importe,
					'id_tipoafectacionigv'		=> $item->id_tipoafectacionigv,
					'afectacionigv_nombre'		=> $item->afectacionigv_nombre,
					'unidad_medida'				=> $unidadmedida->nombre,
					'tipo_unidad'				=> ($item->tipo_unidad == 'PRE')?'Presentacion':'normal'
				);
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['lista_documentos'] = $lista_documentos;
		$resp['detalle_documentos'] = $detalle_documentos;

		return $resp;
	}

	public function get_num_rows_consulta($query, $data) {
        try {
			$id_contribuyente = $data['id_contribuyente'];
			$fecha_inicio = $data[''];
			$fecha_fin = $data['fecha_fin'];
			$tipo_envio_sunat = $data['tipo_envio_sunat'];
			$termino_busqueda = isset($data['termino_busqueda'])?$data['termino_busqueda']:'';

			$sentencia = $this->db->prepare($query);
			$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
			$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
			$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
			$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            if(!empty($termino_busqueda)) {
                $termino_busqueda = '%'.$termino_busqueda.'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
           	return 0;
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $n++;
        }

        return $n;
    }

	public function getReporteGuiasAction() {
		$this->view->disable();

		$datapost = $this->request->get();
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

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			echo json_encode($resp);
			exit();
		}

		$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
		$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));

		$tipo_reporte = !isset($datapost['tipo_reporte'])?'':$datapost['tipo_reporte'];
		if($tipo_reporte != 'lista' && $tipo_reporte != 'lista_detalle') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de reporte no se reconoce.';
			echo json_encode($resp);
			exit();
		}
		
		$fecha_inicio = !isset($datapost['fecha_inicio'])?'':$datapost['fecha_inicio'];
		$fecha_fin = !isset($datapost['fecha_fin'])?'':$datapost['fecha_fin'];

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
		
		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date($fecha_inicio)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Inicio';
			$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		if(!$herramientas->validate_date($fecha_fin)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en la Fecha de Término';
			$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
			echo json_encode($resp);
			exit();
		}

		$data = array();
		$data['id_contribuyente'] = $id_contribuyente;
		$data['tipo_envio_sunat'] = $tipo_envio_sunat;
		$data['vendedores'] = $ids_vendedores; //array ids, si es array vacio entonces todos.
		$data['sucursales'] = $ids_sucursales; //array ids, si es vacio se considera todos.
		$data['fecha_inicio'] = $fecha_inicio; //en formato: Y-m-d
		$data['fecha_fin'] = $fecha_fin; //en formato: Y-m-d
		$data['id_tipodoc_electronico'] = '09';
		
		if($tipo_reporte == 'lista') {
			$resp = $this->get_lista_guias($data);
		} else {
			$resp = $this->get_lista_guias_detalle($data);
		}

		if($resp['respuesta'] == 'error') {
			echo json_encode($resp);
			exit();
		}

		if($tipo_reporte == 'lista') {
			$this->get_lista_guias_excel($resp['lista']);
			exit();
		} else {
			$this->get_lista_guias_detalle_excel($resp['lista']);
			exit();
		}
	}

	public function get_lista_guias($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];
		$ids_vendedores = $data['vendedores']; //array ids, si es array vacio entonces todos.
		$ids_sucursales = $data['sucursales']; //array ids, si es vacio se considera todos.
		$fecha_inicio = $data['fecha_inicio']; //en formato: Y-m-d
		$fecha_fin = $data['fecha_fin']; //en formato: Y-m-d
		$id_tipodoc_electronico = '09';

		$sql = "SELECT * FROM doc_electronico de WHERE de.tipo_envio_sunat = :tipo_envio_sunat and de.id_contribuyente =  :id_contribuyente and de.fecha_registro >= CAST(:fecha_inicio AS DATE) and de.fecha_registro <= CAST(:fecha_fin AS DATE) and de.id_tipodoc_electronico = :id_tipodoc_electronico ";

		if(count($ids_vendedores) > 0){ $sql = $sql." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }

		if(count($ids_sucursales) > 0){ $sql = $sql." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }

		$sentencia = $this->db->prepare($sql);
		$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
		$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
		$sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);

		try {
			$lista = array();
			$sentencia->execute();
			while ($fila = $sentencia->fetch()) {
				$fila = (object)$fila;

				$origen_departamento = '';
				$origen_provincia = ''; 
				$origen_distrito = '';
				$ubigeo_origen = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $fila->id_ubigeo_partida)));
				if($ubigeo_origen) {
					$origen_departamento = $ubigeo_origen->departamento;
					$origen_provincia = $ubigeo_origen->provincia; 
					$origen_distrito = $ubigeo_origen->distrito;
				}

				$destino_departamento = '';
				$destino_provincia = ''; 
				$destino_distrito = '';
				$ubigeo_destino = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $fila->id_ubigeo_destino)));
				if($ubigeo_destino) {
					$destino_departamento = $ubigeo_destino->departamento;
					$destino_provincia = $ubigeo_destino->provincia; 
					$destino_distrito = $ubigeo_destino->distrito;
				}

				$cliente_departamento = '';
				$cliente_provincia = '';
				$cliente_distrito = '';
				$cliente_nombre = '';
				$cliente_id_doc = '';
				$cliente_num_doc = '';
				$cliente_direccion = '';
				$cliente_email = '';
				if(!empty($fila->idcliente)) {
					$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $fila->id_contribuyente)));
					if($cliente) {
						$cliente_nombre = $cliente->razon_social;
						$cliente_id_doc = $cliente->id_tipodocidentidad;
						$cliente_num_doc = $cliente->num_doc;
						$cliente_direccion = $cliente->direccion_fiscal;
						$cliente_email = $cliente->email;

						if(!empty($cliente->id_cod_ubigeo)) {
							$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
							if($ubigeo_destino) {
								$cliente_departamento = $ubigeo_cliente->departamento;
								$cliente_provincia = $ubigeo_cliente->provincia; 
								$cliente_distrito = $ubigeo_cliente->distrito;
							}
						}
					}
				}

				$lista[] = array(
					'fecha_registro'			=> $fila->fecha_registro,
					'tipo_doc'					=> $fila->id_tipodoc_electronico,
					'serie'						=> $fila->serie_comprobante,
					'correlativo'				=> $fila->numero_comprobante,
					'peso_total'				=> $fila->peso,
					'id_motivotraslado'			=> $fila->id_motivotraslado,
					'motivo_traslado'			=> $fila->motivo_traslado,
					'fecha_traslado'			=> $fila->fecha_traslado,
					'nota'						=> $fila->nota,
					'numero_paquetes'			=> $fila->numero_paquetes,
					'id_codigopuerto'			=> $fila->id_codigopuerto,
					'numero_contenedor'			=> $fila->numero_contenedor,
					'id_modalidadtraslado'		=> $fila->id_modalidadtraslado,
					'modalidad_traslado'		=> $fila->modalidad_traslado,

					//falta agregar los documentos de referencia

					//modalidad de transporte privado
					'transporte_nro_placa'		=> ($fila->id_modalidadtraslado == 2)?$fila->transporte_nro_placa:'',
					'id_tipo_doc_conductor'		=> ($fila->id_modalidadtraslado == 2)?$fila->id_tipo_documento_transporte:'',
					'nro_doc_conductor'			=> ($fila->id_modalidadtraslado == 2)?$fila->nro_documento_transporte:'',
					'nombre_conductor'			=> ($fila->id_modalidadtraslado == 2)?$fila->razon_social_transporte:'',

					//modalidad de transporte público
					'id_tipo_doc_transporte'	=> ($fila->id_modalidadtraslado == 1)?$fila->id_tipo_documento_transporte:'',
					'num_doc_e_transporte'		=> ($fila->id_modalidadtraslado == 1)?$fila->nro_documento_transporte:'',
					'razon_social_e_transporte'	=> ($fila->id_modalidadtraslado == 1)?$fila->razon_social_transporte:'',
					
					//datos del lugar de partida
					'id_ubigeo_partida'			=> $fila->id_ubigeo_partida,
					'dir_partida'				=> $fila->dir_partida,
					'origen_departamento' 		=> $origen_departamento,
					'origen_provincia' 			=> $origen_provincia,
					'origen_distrito' 			=> $origen_distrito,

					//datos del lugar de llegada
					'id_ubigeo_destino'			=> $fila->id_ubigeo_destino,
					'dir_destino'				=> $fila->dir_destino,
					'destino_departamento' 		=> $destino_departamento,
					'destino_provincia' 		=> $destino_provincia,
					'destino_distrito' 			=> $destino_distrito,

					//DATOS DEL CLIENTE
					'cliente_id_doc' 			=> $cliente_id_doc,
					'cliente_num_doc' 			=> $cliente_num_doc,
					'cliente_nombre' 			=> $cliente_nombre,
					'cliente_email' 			=> $cliente_email,
					'cliente_direccion' 		=> $cliente_direccion,
					'cliente_departamento' 		=> $cliente_departamento,
					'cliente_provincia' 		=> $cliente_provincia,
					'cliente_distrito' 			=> $cliente_distrito,
				);
			}

		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $lista;
		return $resp;
	}

	public function get_lista_guias_detalle($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];
		$ids_vendedores = $data['vendedores']; //array ids, si es array vacio entonces todos.
		$ids_sucursales = $data['sucursales']; //array ids, si es vacio se considera todos.
		$fecha_inicio = $data['fecha_inicio']; //en formato: Y-m-d
		$fecha_fin = $data['fecha_fin']; //en formato: Y-m-d
		$id_tipodoc_electronico = '09';
		
		$sql = "SELECT de.fecha_registro, de.id_tipodoc_electronico, de.id_contribuyente, de.idcliente, de.serie_comprobante, de.numero_comprobante, de.peso, de.id_motivotraslado, de.motivo_traslado, de.fecha_traslado, de.nota, de.numero_paquetes, de.id_codigopuerto, de.numero_contenedor, de.id_modalidadtraslado, de.modalidad_traslado, de.transporte_nro_placa, de.id_tipo_documento_transporte, de.nro_documento_transporte, de.razon_social_transporte, de.id_ubigeo_partida, de.dir_partida, de.id_ubigeo_destino, de.dir_destino, detalle.item, detalle.id_producto, detalle.codigo_producto, detalle.id_unidad_medida, detalle.unidad_medida, detalle.cantidad, detalle.peso detalle_peso, detalle.descripcion FROM doc_electronico de INNER JOIN detalle_doc detalle on de.id_contribuyente = detalle.id_contribuyente and de.id_tipodoc_electronico = detalle.id_tipodoc_electronico and de.serie_comprobante = detalle.serie_comprobante and de.numero_comprobante = detalle.numero_comprobante and de.tipo_envio_sunat = detalle.tipo_envio_sunat where de.id_contribuyente =  :id_contribuyente and de.tipo_envio_sunat = :tipo_envio_sunat and de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) and de.id_tipodoc_electronico = :id_tipodoc_electronico ";

		if(count($ids_vendedores) > 0){ $sql = $sql." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }

		if(count($ids_sucursales) > 0){ $sql = $sql." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }

		$sentencia = $this->db->prepare($sql);
		$sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
		$sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
		$sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
		$sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
		$sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);

		try {
			$lista = array();
			$sentencia->execute();
			while ($fila = $sentencia->fetch()) {
				$fila = (object)$fila;

				$origen_departamento = '';
				$origen_provincia = ''; 
				$origen_distrito = '';
				$ubigeo_origen = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $fila->id_ubigeo_partida)));
				if($ubigeo_origen) {
					$origen_departamento = $ubigeo_origen->departamento;
					$origen_provincia = $ubigeo_origen->provincia; 
					$origen_distrito = $ubigeo_origen->distrito;
				}

				$destino_departamento = '';
				$destino_provincia = ''; 
				$destino_distrito = '';
				$ubigeo_destino = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $fila->id_ubigeo_destino)));
				if($ubigeo_destino) {
					$destino_departamento = $ubigeo_destino->departamento;
					$destino_provincia = $ubigeo_destino->provincia; 
					$destino_distrito = $ubigeo_destino->distrito;
				}

				$cliente_departamento = '';
				$cliente_provincia = '';
				$cliente_distrito = '';
				$cliente_nombre = '';
				$cliente_id_doc = '';
				$cliente_num_doc = '';
				$cliente_direccion = '';
				$cliente_email = '';
				if(!empty($fila->idcliente)) {
					$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $fila->idcliente, 'id_contribuyente' => $fila->id_contribuyente)));
					if($cliente) {
						$cliente_nombre = $cliente->razon_social;
						$cliente_id_doc = $cliente->id_tipodocidentidad;
						$cliente_num_doc = $cliente->num_doc;
						$cliente_direccion = $cliente->direccion_fiscal;
						$cliente_email = $cliente->email;

						if(!empty($cliente->id_cod_ubigeo)) {
							$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
							if($ubigeo_destino) {
								$cliente_departamento = $ubigeo_cliente->departamento;
								$cliente_provincia = $ubigeo_cliente->provincia; 
								$cliente_distrito = $ubigeo_cliente->distrito;
							}
						}
					}
				}

				$lista[] = array(
					'fecha_registro'			=> $fila->fecha_registro,
					'tipo_doc'					=> $fila->id_tipodoc_electronico,
					'serie'						=> $fila->serie_comprobante,
					'correlativo'				=> $fila->numero_comprobante,
					'peso_total'				=> $fila->peso,
					'id_motivotraslado'			=> $fila->id_motivotraslado,
					'motivo_traslado'			=> $fila->motivo_traslado,
					'fecha_traslado'			=> $fila->fecha_traslado,
					'nota'						=> $fila->nota,
					'numero_paquetes'			=> $fila->numero_paquetes,
					'id_codigopuerto'			=> $fila->id_codigopuerto,
					'numero_contenedor'			=> $fila->numero_contenedor,
					'id_modalidadtraslado'		=> $fila->id_modalidadtraslado,
					'modalidad_traslado'		=> $fila->modalidad_traslado,

					//falta agregar los documentos de referencia

					//modalidad de transporte privado
					'transporte_nro_placa'		=> ($fila->id_modalidadtraslado == 2)?$fila->transporte_nro_placa:'',
					'id_tipo_doc_conductor'		=> ($fila->id_modalidadtraslado == 2)?$fila->id_tipo_documento_transporte:'',
					'nro_doc_conductor'			=> ($fila->id_modalidadtraslado == 2)?$fila->nro_documento_transporte:'',
					'nombre_conductor'			=> ($fila->id_modalidadtraslado == 2)?$fila->razon_social_transporte:'',

					//modalidad de transporte público
					'id_tipo_doc_transporte'	=> ($fila->id_modalidadtraslado == 1)?$fila->id_tipo_documento_transporte:'',
					'num_doc_e_transporte'		=> ($fila->id_modalidadtraslado == 1)?$fila->nro_documento_transporte:'',
					'razon_social_e_transporte'	=> ($fila->id_modalidadtraslado == 1)?$fila->razon_social_transporte:'',
					
					//datos del lugar de partida
					'id_ubigeo_partida'			=> $fila->id_ubigeo_partida,
					'dir_partida'				=> $fila->dir_partida,
					'origen_departamento' 		=> $origen_departamento,
					'origen_provincia' 			=> $origen_provincia,
					'origen_distrito' 			=> $origen_distrito,

					//datos del lugar de llegada
					'id_ubigeo_destino'			=> $fila->id_ubigeo_destino,
					'dir_destino'				=> $fila->dir_destino,
					'destino_departamento' 		=> $destino_departamento,
					'destino_provincia' 		=> $destino_provincia,
					'destino_distrito' 			=> $destino_distrito,

					//DATOS DEL DETALLE
					'nro_item'					=> $fila->item,
					'codigo_producto'			=> $fila->codigo_producto,
					'id_producto'				=> $fila->id_producto,
					'descripcion'				=> $fila->descripcion,
					'id_unidad_medida'			=> $fila->id_unidad_medida,
					'unidad_medida'				=> $fila->unidad_medida,
					'cantidad'					=> $fila->cantidad,
					'peso'						=> $fila->detalle_peso,

					//DATOS DEL CLIENTE
					'cliente_id_doc' 			=> $cliente_id_doc,
					'cliente_num_doc' 			=> $cliente_num_doc,
					'cliente_nombre' 			=> $cliente_nombre,
					'cliente_email' 			=> $cliente_email,
					'cliente_direccion' 		=> $cliente_direccion,
					'cliente_departamento' 		=> $cliente_departamento,
					'cliente_provincia' 		=> $cliente_provincia,
					'cliente_distrito' 			=> $cliente_distrito,
				);
			}

		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['lista'] = $lista;
		return $resp;
	}

	public function get_lista_guias_excel($lista) {
		$spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Listas Guías de Remisión")
            ->setSubject("Listas Guías de Remisión")
            ->setDescription(
                "Listado de guías de remisión."
            )
            ->setKeywords("facturalaya.com, código fuente, plantilla importación, importar cpe")
            ->setCategory("importacion cpe");

		$spreadsheet->setActiveSheetIndex(0)->setTitle("Lista");

		$spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:N1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("O1:V1"); //cliente
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("W1:Z1"); 
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AA1:AC1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AD1:AH1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AI1:AM1");

		$spreadsheet->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(28, 'pt');
        //$spreadsheet->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(28, 'pt');

		$estilo_cabecera_texto = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 8,
                'name'  => 'Verdana'
            )
        );

		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1F497D');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('76933C');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E26B0A');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('009242');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('31869B');

		$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'DATOS GENERALES DE LA GUÍA')
			->setCellValue('O1', 'DATOS DEL CLIENTE')
            ->setCellValue('W1', 'TRANSPORTE PRIVADO')
            ->setCellValue('AA1', 'TRANSPORTE PÚBLICO')
			->setCellValue('AD1', 'DATOS LUGAR ORIGEN')
			->setCellValue('AI1', 'DATOS LUGAR DESTINO')

            ->setCellValue('A2', 'Fecha Registro')
			->setCellValue('B2', 'Tipo Doc.')
			->setCellValue('C2', 'Serie')
			->setCellValue('D2', 'Correlativo')
			->setCellValue('E2', 'Peso (KGM)')
			->setCellValue('F2', 'IdMot.Trasl.')
			->setCellValue('G2', 'Motivo Traslado')
			->setCellValue('H2', 'fecha_traslado')
			->setCellValue('I2', 'Nota')
			->setCellValue('J2', 'Num.Paquetes')
			->setCellValue('K2', 'Codigo Puerto')
			->setCellValue('L2', 'Num.Contenedor')
			->setCellValue('M2', 'IdMod.Traslado')
			->setCellValue('N2', 'Modalidad Traslado')

			//DATOS DEL CLIENTE
			->setCellValue('O2', 'Id.Doc.Cliente')
			->setCellValue('P2', 'Num.Doc.')
			->setCellValue('Q2', 'Nombre')
			->setCellValue('R2', 'Email')
			->setCellValue('S2', 'Dirección')
			->setCellValue('T2', 'Departamento')
			->setCellValue('U2', 'Provincia')
			->setCellValue('V2', 'Distrito')
			
			//TRANSPORTE PRIVADO
			->setCellValue('W2', 'Nro. Placa')
			->setCellValue('X2', 'Id.Doc.Conductor')
			->setCellValue('Y2', 'Nro.Doc.Conductor')
			->setCellValue('Z2', 'Nombre Conductor')

			//TRANSPORTE PUBLICO
			->setCellValue('AA2', 'Id.Doc.Transporte')
			->setCellValue('AB2', 'Num.Doc.Transporte')
			->setCellValue('AC2', 'Razón Social')

			//DATOS LUGAR ORIGEN
			->setCellValue('AD2', 'Ubigeo')
			->setCellValue('AE2', 'Dirección')
			->setCellValue('AF2', 'Departamento')
			->setCellValue('AG2', 'Provincia')
            ->setCellValue('AH2', 'Distrito')

			//DATOS LUGAR DESTINO
			->setCellValue('AI2', 'Ubigeo')
			->setCellValue('AJ2', 'Dirección')
			->setCellValue('AK2', 'Departamento')
			->setCellValue('AL2', 'Provincia')
			->setCellValue('AM2', 'Distrito')
            ;
		
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(false);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(false);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AE')->setAutoSize(false);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AF')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AG')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AH')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AI')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AJ')->setAutoSize(false);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AK')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AL')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AM')->setAutoSize(true);

		$n = 2;
		foreach($lista as $item) {
			$item = (object)$item;
			$n++;
			$spreadsheet->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$n, date("d-m-Y", strtotime($item->fecha_registro)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->tipo_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('C'.$n, $item->serie, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$n, $item->correlativo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('E'.$n, $item->peso_total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('F'.$n, $item->id_motivotraslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('G'.$n, $item->motivo_traslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('H'.$n, date("d-m-Y", strtotime($item->fecha_traslado)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('I'.$n, $item->nota, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('J'.$n, $item->numero_paquetes, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('K'.$n, $item->id_codigopuerto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('L'.$n, $item->numero_contenedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('M'.$n, $item->id_modalidadtraslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('N'.$n, $item->modalidad_traslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//DATOS DEL CLIENTE
				->setCellValueExplicit('O'.$n, $item->cliente_id_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('P'.$n, $item->cliente_num_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Q'.$n, $item->cliente_nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('R'.$n, $item->cliente_email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('S'.$n, $item->cliente_direccion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('T'.$n, $item->cliente_departamento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('U'.$n, $item->cliente_provincia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('V'.$n, $item->cliente_distrito, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				
				//TRANSPORTE PRIVADO
				->setCellValueExplicit('W'.$n, $item->transporte_nro_placa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('X'.$n, $item->id_tipo_doc_conductor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Y'.$n, $item->nro_doc_conductor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('Z'.$n, $item->nombre_conductor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//TRANSPORTE PUBLICO
				->setCellValueExplicit('AA'.$n, $item->id_tipo_doc_transporte, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('AB'.$n, $item->num_doc_e_transporte, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AC'.$n, $item->razon_social_e_transporte, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//DATOS LUGAR ORIGEN
                ->setCellValueExplicit('AD'.$n, $item->id_ubigeo_partida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AE'.$n, $item->dir_partida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('AF'.$n, $item->origen_departamento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AG'.$n, $item->origen_provincia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('AH'.$n, $item->origen_distrito, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//DATOS LUGAR DESTINO
				->setCellValueExplicit('AI'.$n, $item->id_ubigeo_destino, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AJ'.$n, $item->dir_destino, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AK'.$n, $item->destino_departamento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AL'.$n, $item->destino_provincia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AM'.$n, $item->destino_distrito, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
		}

		$spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Lista Guías';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function get_lista_guias_detalle_excel($lista) {

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Listas Guías de Remisión")
            ->setSubject("Listas Guías de Remisión")
            ->setDescription(
                "Listado de guías de remisión."
            )
            ->setKeywords("facturalaya.com, código fuente, plantilla importación, importar cpe")
            ->setCategory("importacion cpe");

		$spreadsheet->setActiveSheetIndex(0)->setTitle("Lista");

		$spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:N1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("O1:V1"); //cliente
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("W1:Z1"); 
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AA1:AC1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AD1:AH1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AI1:AM1");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("AN1:AT1");

		$spreadsheet->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(28, 'pt');
        //$spreadsheet->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(28, 'pt');

		$estilo_cabecera_texto = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 8,
                'name'  => 'Verdana'
            )
        );

		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1F497D');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('O1:V2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('76933C');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('W1:Z2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E26B0A');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AA1:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AD1:AH2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('009242');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AI1:AM2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('31869B');

		$spreadsheet->setActiveSheetIndex(0)->getStyle('AN1:AU2')->getAlignment()->setHorizontal('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AN1:AU2')->getAlignment()->setVertical('center');
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AN1:AU2')->applyFromArray($estilo_cabecera_texto);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('AN1:AU2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('336600');

		$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'DATOS GENERALES DE LA GUÍA')
			->setCellValue('O1', 'DATOS DEL CLIENTE')
            ->setCellValue('W1', 'TRANSPORTE PRIVADO')
            ->setCellValue('AA1', 'TRANSPORTE PÚBLICO')
			->setCellValue('AD1', 'DATOS LUGAR ORIGEN')
			->setCellValue('AI1', 'DATOS LUGAR DESTINO')
			->setCellValue('AN1', 'ITEMS DEL DE LA GUÍA')

            ->setCellValue('A2', 'Fecha Registro')
			->setCellValue('B2', 'Tipo Doc.')
			->setCellValue('C2', 'Serie')
			->setCellValue('D2', 'Correlativo')
			->setCellValue('E2', 'Peso (KGM)')
			->setCellValue('F2', 'IdMot.Trasl.')
			->setCellValue('G2', 'Motivo Traslado')
			->setCellValue('H2', 'fecha_traslado')
			->setCellValue('I2', 'Nota')
			->setCellValue('J2', 'Num.Paquetes')
			->setCellValue('K2', 'Codigo Puerto')
			->setCellValue('L2', 'Num.Contenedor')
			->setCellValue('M2', 'IdMod.Traslado')
			->setCellValue('N2', 'Modalidad Traslado')

			//DATOS DEL CLIENTE
			->setCellValue('O2', 'Id.Doc.Cliente')
			->setCellValue('P2', 'Num.Doc.')
			->setCellValue('Q2', 'Nombre')
			->setCellValue('R2', 'Email')
			->setCellValue('S2', 'Dirección')
			->setCellValue('T2', 'Departamento')
			->setCellValue('U2', 'Provincia')
			->setCellValue('V2', 'Distrito')
			
			//TRANSPORTE PRIVADO
			->setCellValue('W2', 'Nro. Placa')
			->setCellValue('X2', 'Id.Doc.Conductor')
			->setCellValue('Y2', 'Nro.Doc.Conductor')
			->setCellValue('Z2', 'Nombre Conductor')

			//TRANSPORTE PUBLICO
			->setCellValue('AA2', 'Id.Doc.Transporte')
			->setCellValue('AB2', 'Num.Doc.Transporte')
			->setCellValue('AC2', 'Razón Social')

			//DATOS LUGAR ORIGEN
			->setCellValue('AD2', 'Ubigeo')
			->setCellValue('AE2', 'Dirección')
			->setCellValue('AF2', 'Departamento')
			->setCellValue('AG2', 'Provincia')
            ->setCellValue('AH2', 'Distrito')

			//DATOS LUGAR DESTINO
			->setCellValue('AI2', 'Ubigeo')
			->setCellValue('AJ2', 'Dirección')
			->setCellValue('AK2', 'Departamento')
			->setCellValue('AL2', 'Provincia')
			->setCellValue('AM2', 'Distrito')

			//ITEMS
			->setCellValue('AN2', 'Nro.Item')
			->setCellValue('AO2', 'Id Producto')
			->setCellValue('AP2', 'Cod. Producto')
			->setCellValue('AQ2', 'Descripción')
			->setCellValue('AR2', 'id_Unidad_Medida')
			->setCellValue('AS2', 'Unidad Medida')
			->setCellValue('AT2', 'Cantidad')
			->setCellValue('AU2', 'Peso Unit.')
            ;
		
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(false);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(false);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AE')->setAutoSize(false);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AF')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AG')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AH')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AI')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AJ')->setAutoSize(false);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AK')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AL')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AM')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AN')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AO')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AP')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AQ')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AR')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AS')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AT')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AU')->setAutoSize(true);


		$n = 2;
		foreach($lista as $item) {
			$item = (object)$item;
			$n++;
			$spreadsheet->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$n, date("d-m-Y", strtotime($item->fecha_registro)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->tipo_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('C'.$n, $item->serie, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$n, $item->correlativo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('E'.$n, $item->peso_total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('F'.$n, $item->id_motivotraslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('G'.$n, $item->motivo_traslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('H'.$n, date("d-m-Y", strtotime($item->fecha_traslado)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('I'.$n, $item->nota, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('J'.$n, $item->numero_paquetes, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('K'.$n, $item->id_codigopuerto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('L'.$n, $item->numero_contenedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('M'.$n, $item->id_modalidadtraslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('N'.$n, $item->modalidad_traslado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//DATOS DEL CLIENTE
				->setCellValueExplicit('O'.$n, $item->cliente_id_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('P'.$n, $item->cliente_num_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Q'.$n, $item->cliente_nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('R'.$n, $item->cliente_email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('S'.$n, $item->cliente_direccion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('T'.$n, $item->cliente_departamento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('U'.$n, $item->cliente_provincia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('V'.$n, $item->cliente_distrito, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				
				//TRANSPORTE PRIVADO
				->setCellValueExplicit('W'.$n, $item->transporte_nro_placa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('X'.$n, $item->id_tipo_doc_conductor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('Y'.$n, $item->nro_doc_conductor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('Z'.$n, $item->nombre_conductor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//TRANSPORTE PUBLICO
				->setCellValueExplicit('AA'.$n, $item->id_tipo_doc_transporte, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('AB'.$n, $item->num_doc_e_transporte, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AC'.$n, $item->razon_social_e_transporte, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//DATOS LUGAR ORIGEN
                ->setCellValueExplicit('AD'.$n, $item->id_ubigeo_partida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AE'.$n, $item->dir_partida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('AF'.$n, $item->origen_departamento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AG'.$n, $item->origen_provincia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('AH'.$n, $item->origen_distrito, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//DATOS LUGAR DESTINO
				->setCellValueExplicit('AI'.$n, $item->id_ubigeo_destino, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AJ'.$n, $item->dir_destino, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AK'.$n, $item->destino_departamento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AL'.$n, $item->destino_provincia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AM'.$n, $item->destino_distrito, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

				//ITEMS
				->setCellValueExplicit('AN'.$n, $item->nro_item, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AO'.$n, $item->id_producto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AP'.$n, $item->codigo_producto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AQ'.$n, $item->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AR'.$n, $item->id_unidad_medida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AS'.$n, $item->unidad_medida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AT'.$n, (string)($item->cantidad + 0), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
				->setCellValueExplicit('AU'.$n, (string)($item->peso + 0), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
		}

		$spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Lista Guías';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}

	public function consolidadoVentasProductoAction() {
		$this->setTitle('Consolidado de Venas por Producto');
		$this->view->setTemplateAfter('template_new');

        $this->assets
		->addCss($this->baseUri . "public/css/new_style.css");
		
		$this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
			->addJs($this->baseUri . "public/js/reportes/consolidado_ventas_producto.js?j=".rand());

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
		
		$lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$lista_usuarios = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		
		$this->view->lista_sucursales = $lista_sucursales;
		$this->view->lista_usuarios = $lista_usuarios;
	}

	public function getReporteConsolidadoProductosAction() {
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
			
			$resp_reporte = $this->get_reporte_consolidado_productos($datapost, $usuario);
			if($resp_reporte['respuesta'] == 'error') {
				echo json_encode($resp_reporte);
				exit();
			}

			echo json_encode($resp_reporte['json_data']);
			exit();
		}
	}

	public function get_reporte_consolidado_productos($datapost, $usuario) {
		$id_contribuyente = $usuario->id_contribuyente;
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
			return $resp;
		}
		$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', $datapost['vendedores']);
		$ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', $datapost['sucursales']);
		$id_cod_moneda = empty($datapost['tipos_monedas'])?'PEN':$datapost['tipos_monedas'];
		$tipos_comprobantes = empty($datapost['tipos_documentos'])?array():$datapost['tipos_documentos'];
		$estado_documento = !isset($datapost['estado_documento'])?'activo':$datapost['estado_documento'];

		$fecha_inicio_valor = empty($datapost['fecha_inicio'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio'];
		$fecha_fin_valor = empty($datapost['fecha_fin'])?date("Y-m-d H:i:s"):$datapost['fecha_fin'];
		
		$fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
		$fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));

		$tipos_validos_monedas = array('PEN', 'USD');
		$tipos_comprobantes_validos = array('03', '01', '77', '88');
		$estados_enviosunat_validos = array('activo', 'inactivo');
		
		if(!in_array($id_cod_moneda, $tipos_validos_monedas)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Código Moneda';
			$resp['mensaje'] = 'El código de Moneda Seleccionado es Inválido!';
			return $resp;
		}

		$tipos_comprobantes = array_filter($tipos_comprobantes);
		if(count($tipos_comprobantes) > 0) {
			foreach($tipos_comprobantes as $id_tipo_doc) {
				if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error Tipo Doc.';
					$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
					return $resp;
				}
			}
		}

		if(!in_array($estado_documento, $estados_enviosunat_validos)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Tipo Doc.';
			$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
			return $resp;
		}

		if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
		if(count($ids_vendedores) > 0) {
			foreach($ids_vendedores as $id_vendedor) {
				$vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $usuario->id_contribuyente)));
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
		
		$herramientas = new HerramientasController;
		if(!$herramientas->validate_date_time($fecha_inicio_time)) { //Y-m-d H:i:s
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha de inicio no es válida';
			return $resp;
		}

		if(!$herramientas->validate_date_time($fecha_fin_time)) { //Y-m-d H:i:s
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha de fin no es válida';
			return $resp;
		}
		
		if(count($tipos_comprobantes) <= 1) {
			$where_id_tipodoc_electronico = " = '".$tipos_comprobantes[0]."' ";
		} else {
			$where_id_tipodoc_electronico = " in ('".implode("','", $tipos_comprobantes)."') ";
		}

		if($estado_documento == 'activo') {
			$sql_estado_doc_no_oficial = " AND doc.estado_documento = 'activo' ";
			$sql_estado_doc_electronico = " AND (doc.estado_envio_sunat <> 'rechazado' AND doc.estado_envio_sunat <> 'anulado') ";
		} else {
			$sql_estado_doc_no_oficial = " AND doc.estado_documento = 'inactivo' ";
			$sql_estado_doc_electronico = " AND (doc.estado_envio_sunat = 'rechazado' or doc.estado_envio_sunat = 'anulado') ";
		}

		$where_id_vendedor = "";
		if(count($ids_vendedores) == 1) {
			$id_vendedor = isset($ids_vendedores[0])?$ids_vendedores[0]:(isset($ids_vendedores[1])?$ids_vendedores[1]:0);
			if($id_vendedor > 0) {
				$where_id_vendedor = " AND doc.id_vendedor = ".$id_vendedor." ";
			}
		} else if(count($ids_vendedores) > 1) {
			$where_id_vendedor = " AND doc.id_vendedor in (".implode(",", $ids_vendedores).") ";
		} else {
			$where_id_vendedor = "";
		}

		$where_id_sucursal = "";
		if(count($ids_sucursales) == 1) {
			$id_sucursal = isset($ids_sucursales[0])?$ids_sucursales[0]:(isset($ids_sucursales[1])?$ids_sucursales[1]:0);
			if($id_sucursal > 0) {
				$where_id_sucursal = " AND doc.id_sucursal = ".$id_sucursal." ";
			}
		} else if(count($ids_sucursales) > 1) {
			$where_id_sucursal = " AND doc.id_sucursal in (".implode(",", $ids_sucursales).") ";
		} else {
			$where_id_sucursal = "";
		}
		
		$sql_doc_no_oficial = "";
		if(in_array('77', $tipos_comprobantes) || in_array('88', $tipos_comprobantes)) {
			$sql_doc_no_oficial = "
			SELECT doc.id_contribuyente id_contribuyente,
				doc.id_tipodocumento 	id_tipodoc_electronico,
				'SERIE' 				serie_comprobante,
				doc.numero_comprobante 	numero_comprobante,
				doc.fecha_registro 		fecha_registro,
				det.id_producto 		id_producto,
				det.tipo_unidad 		tipo_unidad,
				det.id_unidad_medida 	id_unidad_medida,
				det.id_presentacion 	id_presentacion,
				sum(det.cantidad) 		cantidad_total,
				presentacion.nombre 	nom_presentacion,
				presentacion.idunidad_base 		id_unidad_base,
				presentacion.idunidad 			id_unidad_presentacion,
				presentacion.cantidad_und_base 	cantidad_und_base,
				producto.nombre 				producto_nombre,
				producto.codigo					producto_codigo,
				producto.id_unidad_medida 		prod_id_unidad_medida
			FROM   doc_no_oficial doc
				INNER JOIN detalle_docnooficial det
						ON ( doc.id_contribuyente = det.id_contribuyente
								AND doc.id_tipodocumento = det.id_tipodocumento
								AND doc.numero_comprobante = det.numero_comprobante
								AND doc.modalidad = det.modalidad )
				LEFT JOIN producto_presentacion presentacion 
							ON (det.id_presentacion = presentacion.id_presentacion) 
				INNER JOIN producto 
							ON (det.id_producto = producto.idproducto)
			WHERE  doc.id_contribuyente = $id_contribuyente
				AND doc.id_tipodocumento $where_id_tipodoc_electronico
				$sql_estado_doc_no_oficial
				AND doc.modalidad = '$tipo_envio_sunat' 
				AND doc.id_codigomoneda = '$id_cod_moneda' 
				$where_id_vendedor 
				$where_id_sucursal 
				AND ( doc.fecha_registro BETWEEN Cast('$fecha_inicio_time' AS DATETIME) AND Cast('$fecha_fin_time' AS DATETIME)) 

			GROUP BY 
				det.id_producto,
				det.id_unidad_medida,
				det.id_presentacion
			ORDER BY det.id_producto, det.id_unidad_medida, det.id_presentacion
			";
		}
		
		$sql_doc_electronico = "";
		if(in_array('01', $tipos_comprobantes) || in_array('03', $tipos_comprobantes)) {
			$sql_doc_electronico = "
			SELECT doc.id_contribuyente 		id_contribuyente,
				doc.id_tipodoc_electronico 		id_tipodoc_electronico,
				doc.serie_comprobante 			serie_comprobante,
				doc.numero_comprobante 			numero_comprobante,
				doc.fecha_registro 				fecha_registro,
				det.id_producto 				id_producto,
				det.tipo_unidad 				tipo_unidad,
				det.id_unidad_medida 			id_unidad_medida,
				det.id_presentacion 			id_presentacion,
				Sum(det.cantidad)              	cantidad_total,
				presentacion.nombre            	nom_presentacion,
				presentacion.idunidad_base     	id_unidad_base,
				presentacion.idunidad          	id_unidad_presentacion,
				presentacion.cantidad_und_base 	cantidad_und_base,
				producto.nombre 				producto_nombre,
				producto.codigo					producto_codigo,
				producto.id_unidad_medida      	prod_id_unidad_medida
			FROM   doc_electronico doc
				INNER JOIN detalle_doc det
						ON ( doc.id_contribuyente = det.id_contribuyente
								AND doc.id_tipodoc_electronico = det.id_tipodoc_electronico
								AND doc.serie_comprobante = det.serie_comprobante
								AND doc.numero_comprobante = det.numero_comprobante
								AND doc.tipo_envio_sunat = det.tipo_envio_sunat )
				LEFT JOIN producto_presentacion presentacion
						ON ( det.id_presentacion = presentacion.id_presentacion )
				INNER JOIN producto
						ON ( det.id_producto = producto.idproducto )
			WHERE  doc.id_contribuyente = $id_contribuyente 
				AND doc.id_tipodoc_electronico $where_id_tipodoc_electronico
				$sql_estado_doc_electronico 
				AND doc.tipo_envio_sunat = '$tipo_envio_sunat' 
				AND doc.id_codigomoneda = '$id_cod_moneda' 
				$where_id_vendedor 
				$where_id_sucursal 
				AND (doc.fecha_registro BETWEEN Cast('$fecha_inicio_time' AS DATETIME) AND Cast('$fecha_fin_time' AS DATETIME))
			GROUP BY det.id_producto,
					det.id_unidad_medida,
					det.id_presentacion
			ORDER BY det.id_producto, det.id_unidad_medida, det.id_presentacion
			";
		}

		$query = "";
		if($sql_doc_no_oficial == '' && $sql_doc_electronico != '') {
			$query = $sql_doc_electronico;
		} else if($sql_doc_no_oficial != '' && $sql_doc_electronico == '') {
			$query = $sql_doc_no_oficial;
		} else if($sql_doc_no_oficial != '' && $sql_doc_electronico != '') {
			$query = "SELECT * FROM (($sql_doc_no_oficial) UNION ALL ($sql_doc_electronico)) WHERE 1=1 ";
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encontraron datos para el reporte';
			return $resp;
		}
		
		$sql_count = "";
		if ($sql_doc_no_oficial == '' && $sql_doc_electronico != '') {
			$sql_count = "SELECT COUNT(*) AS total FROM ($sql_doc_electronico) tbl_total";
		} else if ($sql_doc_no_oficial != '' && $sql_doc_electronico == '') {
			$sql_count = "SELECT COUNT(*) AS total FROM ($sql_doc_no_oficial) tbl_total";
		} else if ($sql_doc_no_oficial != '' && $sql_doc_electronico != '') {
			$sql_count = "SELECT COUNT(*) AS total FROM (($sql_doc_no_oficial) UNION ALL ($sql_doc_electronico)) tbl_total";
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encontraron datos para el reporte';
			return $resp;
		}
		
		try {
			$sentencia = $this->db->prepare($sql_count);				
			$sentencia->execute();
			$result = $sentencia->fetch();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}
		
		$total_registros = $result['total'];
		
		$columnas = array(
			0 => 'id_tipodoc_electronico',
			1 => 'id_tipodoc_electronico',
			2 => 'serie_comprobante',
			3 => 'numero_comprobante',
			4 => 'fecha_registro',
			5 => 'id_producto',
			6 => 'producto_codigo',
			7 => 'producto_nombre',
			8 => 'id_unidad_medida',
			9 => 'id_unidad_medida',

			10 => 'cantidad_total',
			11 => 'prod_id_unidad_medida',
			12 => 'prod_id_unidad_medida'
		);

		$termino_busqueda = isset($datapost['search']['value'])?$datapost['search']['value']:'';

		if(!empty($termino_busqueda)) {
			$query = str_replace("GROUP BY", " AND (producto_codigo like :termino or producto_nombre like :termino) GROUP BY ", $query);
		}

		$total_filas_filtradas = $this->get_num_rows_filtered_consolidado_productos($query, $termino_busqueda);

		//$query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
		$query = $query." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

		try {
			$sentencia = $this->db->prepare($query);
			if(!empty($termino_busqueda)) {
				$termino_busqueda = '%'.$termino_busqueda.'%';
				$sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
			}
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$array_lista = array();
		$n = 0;
		while($fila = $sentencia->fetch()) {
			$n++;
			$item = (object)$fila;

			$sunat_tipodocelectronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
			if($item->tipo_unidad == 'PRE') {
				$id_unidad_medida = $item->id_unidad_presentacion;
				$nombre_unidad_medida = $item->nom_presentacion;
			} else {
				$id_unidad_medida = $item->prod_id_unidad_medida;

				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
				if(!$unidad_medida) {
					$nombre_unidad_medida = 'DESCONOCIDO';
				} else {
					$nombre_unidad_medida = $unidad_medida->nombre;
				}
			}
			
			$array_lista[] = array(
				'fecha_registro' 			=> date("d-m-Y H:i:s", strtotime($item->fecha_registro)),
				'id_contribuyente' 			=> $item->id_contribuyente,
				'id_tipodoc_electronico' 	=> $item->id_tipodoc_electronico,
				'nombre_documento'			=> $sunat_tipodocelectronico->descripcion,
				'serie_comprobante' 		=> $item->serie_comprobante,
				'numero_comprobante' 		=> $item->numero_comprobante,
				
				'id_producto' 				=> $item->id_producto,
				'producto_codigo' 			=> $item->producto_codigo,
				'producto_nombre' 			=> $item->producto_nombre,

				'cantidad_total' 			=> floatval($item->cantidad_total) + 0,

				'tipo_unidad'				=> $item->tipo_unidad,
				'id_unidad_medida' 			=> $id_unidad_medida,
				'unidad_medida' 			=> $nombre_unidad_medida,
				'id_presentacion'			=> $item->id_presentacion
			);
		}

		$json_data = array(
			"draw"            => intval($datapost['draw']),
			"recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
			"recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
			"data"            => $array_lista,   // total data array
			"numero_n"        => $n
		);

		$resp['respuesta'] = 'ok';
		$resp['json_data'] = $json_data;
		return $resp;
	}

	public function get_num_rows_filtered_consolidado_productos($query, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare("SELECT COUNT(*) total FROM ($query) AS tbl1");
            if(!empty($termino_busqueda)) {
                $termino_busqueda = '%'.$termino_busqueda.'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            return $fila->total;
        }

        return $n;
	}
}