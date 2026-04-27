<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/dompdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/qrlib/vendor/autoload.php";
use Dompdf\Dompdf;

require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/qrlib/vendor/autoload.php";
use Knp\Snappy\Pdf;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CajachicaController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Caja chica');
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

            ->addJs($this->baseUri . "public/js/gestiondecompras/proveedor_flexdatalist.js?i=".rand())

			->addJs($this->baseUri . "public/js/general.js?i=v2")
            ->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
            ->addJs($this->baseUri . "public/js/cajachica.js?j=".rand());

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

    public function saveAction(){
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
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
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

            $sucursal_1 = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal_1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Primero debes crear una Sucursal';
                echo json_encode($resp);
                exit();
            }
            
            if(!isset($usuario->idsucursal) || empty($usuario->idsucursal)) {
                $id_sucursal_usuario = $sucursal_1->idsucursal;
            } else {
                $id_sucursal_usuario = $usuario->idsucursal;
            }
            
            $id_movimiento = !isset($datapost['id_movimiento'])?0:intval($datapost['id_movimiento']) + 0;
            $tipo_movimiento = !isset($datapost['tipo_movimiento'])?'':$datapost['tipo_movimiento'];
            $id_sucursal = !isset($datapost['select_sucursal'])?$id_sucursal_usuario:intval($datapost['select_sucursal']) + 0;
            $id_vendedor = !isset($datapost['select_vendedor'])?$usuario->idusuario:intval($datapost['select_vendedor']) + 0;
            $fecha_registro = date('Y-m-d H:i:s');
            $fecha_movimiento = !isset($datapost['control_fecha_movimiento_valor'])?'':$datapost['control_fecha_movimiento_valor'];
            $descripcion = !isset($datapost['descripcion'])?'':$datapost['descripcion'];
            $id_cod_moneda = !isset($datapost['id_cod_moneda'])?'':$datapost['id_cod_moneda'];
            $monto = !isset($datapost['monto'])?0:floatval($datapost['monto']);
            $detalle_movimiento = !isset($datapost['detalle_movimiento'])?'':$datapost['detalle_movimiento'];
            $num_doc_proveedor = !isset($datapost['proveedor_numerodocumento'])?'':$datapost['proveedor_numerodocumento'];
            $razon_social = !isset($datapost['proveedor_nombre'])?'':$datapost['proveedor_nombre'];
            $tipo_comprobante_compra = !isset($datapost['select_tipo_comprobante'])?'':$datapost['select_tipo_comprobante'];
            $serie_num_comprobante = !isset($datapost['num_comprobante'])?'':$datapost['num_comprobante'];
            $con_comprobante = !isset($datapost['comprobante'])?0:intval($datapost['comprobante']) + 0;

            $id_condicionpago = !isset($datapost['condicionpago_abono'])?0:intval($datapost['condicionpago_abono']) + 0;
            $numero_operacion = !isset($datapost['txt_numero_operacion'])?0:intval($datapost['txt_numero_operacion']) + 0;
            $idcuenta_banco_deposito = null;
		    $fecha_deposito_transferencia = null;
            
            if($usuario->id_rol == 4) {
                $id_vendedor = $usuario->idusuario;
                $id_sucursal = $id_sucursal_usuario;
            }
            
            if($usuario->idusuario != $id_vendedor) {
                $sql_vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :id_usuario: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_usuario' => $id_vendedor)));
                if(!$sql_vendedor){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El vendedor seleccionado no existe!';
                    echo json_encode($msj);
                    exit();
                }
            }
            
            if($usuario->idsucursal != $id_sucursal) {
                $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$sucursal) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'La sucursal seleccionada no es correcta!';
                    echo json_encode($msj);
                    exit();
                }
            }
            
            if(empty($fecha_movimiento)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Por favor, elija una fecha en la que se realizó Movimiento de caja';
                echo json_encode($msj);
                exit();
            }
            
            //$fecha_movimiento = date("Y-m-d H:i:s", strtotime($fecha_movimiento));
            $herramientas = new HerramientasController;
			if(!$herramientas->validate_date_time($fecha_movimiento)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha Movimiento';
				$resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
				echo json_encode($resp);
                exit();
            }
            $fecha_movimiento = date("Y-m-d H:i:s", strtotime($fecha_movimiento));
            
            if(empty($tipo_movimiento)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Selecciona el Tipo de Movimiento, si es una Entrada o Salida de Dinero';
                echo json_encode($msj);
                exit();
            }

            if($tipo_movimiento != 'ingreso' && $tipo_movimiento != 'egreso') {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El tipo de movimiento seleccionado no es válido';
                echo json_encode($msj);
                exit();
            }
            
            if(empty($descripcion)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Por favor, agregue una breve descripción';
                echo json_encode($msj);
                exit();
            }

            $sql_moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $id_cod_moneda)));
            if(!$sql_moneda){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo de Moneda Seleccionada No Existe. Por favor, selecciona un tipo de moneda Válido';
                echo json_encode($resp);
                exit();
            }

            if(empty($monto)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Por favor, escriba el monto para generar el movimiento de caja';
                echo json_encode($msj);
                exit();
            }

            if($monto <= 0 ) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Debes ingresar un monto mayor a cero';
                echo json_encode($msj);
                exit();
            }

            if($con_comprobante == 2) {
                if(empty($num_doc_proveedor)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Si la opción CON DOCUMENTO está seleccionada debes ingresar el número de documento del proveedor';
                    echo json_encode($msj);
                    exit();
                }

                if(empty($razon_social)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Si la opción CON DOCUMENTO está seleccionada debes ingresar la razón social del proveedor';
                    echo json_encode($msj);
                    exit();
                }

                if(empty($tipo_comprobante_compra)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Si la opción CON DOCUMENTO está seleccionada debes ingresar el tipo de comprobante';
                    echo json_encode($msj);
                    exit();
                }

                if($tipo_comprobante_compra != '01' && $tipo_comprobante_compra != '03' && $tipo_comprobante_compra != '77') {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El Tipo de Comprobante Seleccionado no es Válido';
                    echo json_encode($msj);
                    exit();
                }

                if(empty($serie_num_comprobante)) {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Si la opción CON DOCUMENTO está seleccionada debes ingresar el número del comprobante';
                    echo json_encode($msj);
                    exit();
                }
            }

            if($id_condicionpago > 0) {
                $condiciondepago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", "bind" => array('id_contribuyente' => $usuario->id_contribuyente, 'id_condicionpago' => $id_condicionpago)));
                if(!$condiciondepago) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La condición de pago seleccionada no es válida!';
                    echo json_encode($resp);
                    exit();
                }

                if($condiciondepago->tipo == 'transferencia') {
                    if(intval($datapost['cuenta_banco_deposito']) > 0) {
                        $cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($datapost['cuenta_banco_deposito']))));
                        if(!$cuenta_banco_transferencia) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'La cuenta de banco de transferencia no existe, debes seleccionar una cuenta de banco en donde se ha depositado el dinero!!';
                            echo json_encode($resp);
                            exit();
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

            $id_proveedor = null;
            if(!empty($datapost['proveedor_numerodocumento'])) {
                $resp_idproveedor = $this->get_id_proveedor($datapost);
                if($resp_idproveedor['respuesta'] == 'error') {
                    echo json_encode($resp_idproveedor);
                    exit();
                }

                $id_proveedor = $resp_idproveedor['id_proveedor'];
            }


            
            $nuevo_movimiento = Movimientocaja::findFirst(array("id_movimiento = :id_movimiento: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_movimiento' => $id_movimiento, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$nuevo_movimiento) {
                $nuevo_movimiento = new Movimientocaja();
                $nuevo_movimiento->fecha_registro =  $fecha_registro;
                $nuevo_movimiento->id_contribuyente = $usuario->id_contribuyente;
            } else {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Por Seguridad no se permite la edición de un ingreso o egreso. Debes eliminar el registro y crear un nuevo registro.';
                echo json_encode($msj);
                exit();
            }
            
            $nuevo_movimiento->tipo_movimiento = $tipo_movimiento; 
            $nuevo_movimiento->id_sucursal = $id_sucursal; 
            $nuevo_movimiento->id_vendedor = $id_vendedor;  
            $nuevo_movimiento->fecha_movimiento =  $fecha_movimiento; 
            $nuevo_movimiento->descripcion = $descripcion;  
            $nuevo_movimiento->moneda = $id_cod_moneda;  
            $nuevo_movimiento->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
            $nuevo_movimiento->monto = $monto;  
            $nuevo_movimiento->detalle = $detalle_movimiento; 
            $nuevo_movimiento->ruc_comprobante = empty($num_doc_proveedor)?null:$num_doc_proveedor;
            $nuevo_movimiento->razon_social = empty($razon_social)?null:$razon_social;
            $nuevo_movimiento->tipo_comprobante = empty($tipo_comprobante_compra)?null:$tipo_comprobante_compra;
            $nuevo_movimiento->serie_num_comprobante = empty($serie_num_comprobante)?null:$serie_num_comprobante;
            $nuevo_movimiento->estado = 'activo';
            $nuevo_movimiento->id_proveedor = $id_proveedor;

            if($id_condicionpago > 0) {
                $nuevo_movimiento->id_condicionpago = $id_condicionpago;
                $nuevo_movimiento->cpago_nrooperacion = $numero_operacion;
            } else {
                $nuevo_movimiento->id_condicionpago = null;
                $nuevo_movimiento->cpago_nrooperacion = null;
            }
            
            $nuevo_movimiento->fechadeposito = $fecha_deposito_transferencia;
            $nuevo_movimiento->idbanco = $idcuenta_banco_deposito;

            try {    
                if(!$nuevo_movimiento->save()) {
                    $msg = '';
                    foreach ($nuevo_movimiento->getMessages() as $message) {
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
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $texto_a_encriptar = "$nuevo_movimiento->id_movimiento||$nuevo_movimiento->id_contribuyente||$nuevo_movimiento->tipo_envio_sunat";
            $texto_encriptado = $herramientas->encriptar($texto_a_encriptar);
            $url_ticket_movimiento = '/facturacionv8/cajachica/imprimir_ticket_movimiento/?file='.urlencode($texto_encriptado);

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'Se guardaron correctamente los datos del Movimiento! <br> <a target="_blank" href="'.$url_ticket_movimiento.'"><img style="width: 45px;" src="/facturacionv8/img/svg/ticket_cpe.svg" /></a>';
            echo json_encode($resp);
            exit();
       

        }
       
    }
    public function getListaMovimientosAction() {
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

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

            $resp = $this->get_lista_entradas_salidas($datapost, $contribuyente, $usuario);
            echo json_encode($resp);
            exit();
        }
    }

    public function getReporteExelIngresosEgresosAction() {
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

        $resp = $this->get_lista_entradas_salidas($datapost, $contribuyente, $usuario);
        if($resp['respuesta'] == 'error') {
            echo json_encode($resp);
            exit();
        }

        if($datapost['num_reporte'] == '1') {
            $this->get_reporte_exel_ingresos_egresos_1($usuario, $resp);
            exit();
        }

        if($datapost['num_reporte'] == '2') {
            $this->get_reporte_exel_ingresos_egresos_2($usuario, $resp);
            exit();   
        }
    }

    public function get_reporte_exel_ingresos_egresos_1($usuario, $resp) {
        $lista = $resp['lista_ingresos_salidas'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Listas Ingresos y Egresos")
            ->setSubject("Listas Ingresos y Egresos")
            ->setDescription(
                "Listas Ingresos y Egresos."
            )
            ->setKeywords("facturalaya.com, ingresos, egresos, salidas, entradas")
            ->setCategory("ingreso y egresos");

        $spreadsheet->setActiveSheetIndex(0)->setTitle("Ingresos y Egresos");

        /*
        $resp['fecha_inicio_time'] = $fecha_inicio_time;
        $resp['fecha_fin_time'] = $fecha_fin_time;
        $resp['ids_vendedores'] = $ids_vendedores;
        $resp['ids_sucursales'] = $ids_sucursales;
        */

        $fecha_inicio = date("d-m-Y / H:i A", strtotime($resp['fecha_inicio_time']));
        $fecha_fin = date("d-m-Y / H:i A", strtotime($resp['fecha_fin_time']));

        if(count($resp['ids_vendedores']) > 0) {
            $texto_vendedores = '';
            foreach($resp['ids_vendedores'] as $id_vendedor) {
                $sql_vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :id_usuario: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_usuario' => $id_vendedor)));
                if($sql_vendedor) {
                    $texto_vendedores .= $sql_vendedor->nombres.' '.$sql_vendedor->apellidos.' (ID: '.$sql_vendedor->idusuario.')'.", ";
                }
            }
        } else {
            $texto_vendedores = 'Todos';
        }

        if(count($resp['ids_sucursales']) > 0) {
            $texto_sucursales = '';
            foreach($resp['ids_sucursales'] as $id_sucursal) {
                $sql_sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
                if($sql_sucursal) {
                    $texto_sucursales .= $sql_sucursal->nombre.' (ID: '.$sql_sucursal->idsucursal.')'.", ";
                }
            }
        } else {
            $texto_sucursales = 'Todas';
        }

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A2:R2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A3:R3");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A4:R4");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A5:R5");
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:R5')->getAlignment()->setVertical('left');
        $spreadsheet->getActiveSheet()->getStyle('A2:R5')->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A8:A9");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("B8:C8");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("D8:E8");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("K8:L8");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("M8:N8");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("O8:R8");
        
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE INGRESOS Y EGRESOS DESDE EL '.$fecha_inicio.' HASTA EL '.$fecha_fin)
			->setCellValue('A3', 'Reporte Generado por: '.$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.')')
            ->setCellValue('A4', "Vendedores/Usuario Seleccionados: ".$texto_vendedores)
            ->setCellValue('A5', "Sucursales Seleccionadas: ".$texto_sucursales)

            ->setCellValue('B8', 'SUCURSAL')
            ->setCellValue('D8', 'USUARIO')
            ->setCellValue('K8', 'PROVEEDOR')
            ->setCellValue('M8', 'COMPROBANTE')
            ->setCellValue('O8', 'MODALIDAD DE PAGO')

			->setCellValue('A9', 'ID')
            ->setCellValue('B9', 'ID SUCURSAL')
            ->setCellValue('C9', 'NOMBRE SUCURSAL')
            ->setCellValue('D9', 'ID USUARIO')
            ->setCellValue('E9', 'NOMBRE USUARIO')
            ->setCellValue('F9', 'FECHA')
            ->setCellValue('G9', 'DESCRIPCION')
            ->setCellValue('H9', 'DETALLE')
            ->setCellValue('I9', 'MONEDA')
            ->setCellValue('J9', 'MONTO')
            ->setCellValue('K9', 'DOC')
            ->setCellValue('L9', 'RAZÓN SOCIAL')
            ->setCellValue('M9', 'TIPO DOC')
            ->setCellValue('N9', 'SERIE-NUMERO')
            ->setCellValue('O9', 'NOMBRE')
            ->setCellValue('P9', 'NRO OPERACION')
            ->setCellValue('Q9', 'BANCO')
            ->setCellValue('R9', 'FECHA DEPÓSITO')
            ;

        $estilo_titular = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );

        $estilo_subtitular = array(
            'font'  => array(
                'bold'  => false,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );

        $estilo_subtitular_2 = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );

        $estilo_contenido_tabla = array(
            'font'  => array(
                'bold'  => false,
                //'italic'=> true,
                'color' => array('rgb' => '000000'),
                'size'  => 8,
                'name'  => 'Verdana'
            )
        );

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:R2')->applyFromArray($estilo_titular);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:R5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A3:R5')->applyFromArray($estilo_titular);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A6:R6");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A7:R7");

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A8:R8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A8:R8')->applyFromArray($estilo_subtitular);

        $spreadsheet->getActiveSheet()->getStyle('I8:I8')->getAlignment()->setWrapText(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:R8')->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:R2')->getAlignment()->setHorizontal('center');
        
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', 'LISTA EGRESOS');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A7:R7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FABF8F');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A7:R7')->applyFromArray($estilo_subtitular_2);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A7:R7")->getAlignment()->setHorizontal('center');

        $suma_egresos = 0;
        $n = 9;
        $num_item = 0;
		foreach($lista as $item) {
			$item = (object)$item;
            if($item->tipo == 'egreso' && $item->estado == 'activo') {
                $n++;
                $num_item++;

                $nombre_proveedor = '';
                $numero_proveedor = '';
                if(count($item->data_proveedor) > 0) {
                    //$nombre_proveedor = $item->data_proveedor['num_doc']."\n".$item->data_proveedor['razon_social'];
                    $nombre_proveedor = $item->data_proveedor['razon_social'];
                    $numero_proveedor = $item->data_proveedor['num_doc'];
                }
                
                $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $item->id_sucursal)));
                $nombre_sucursal = $sucursal->nombre;

                $condicionpago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_condicionpago' => $item->id_condicionpago)));
                $nombre_condicionpago = '';
                if($condicionpago) {
                    $nombre_condicionpago = $condicionpago->condicionpago;
                }

                $banco = Cuentabanco::findFirst(array("id_contribuyente = :id_contribuyente: and id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_cuentabanco' => $item->idbanco)));
                $nombre_banco = '';
                if($banco) {
                    $nombre_banco = $banco->nombre_banco;
                }
                
                $fecha_deposito_transferencia = '';
                if(!empty($item->fechadeposito)) {
                    $fecha_deposito_transferencia = date("d-m-Y", strtotime($item->fechadeposito));
                }

                $vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :id_usuario: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_usuario' => $item->id_vendedor)));
                $nombre_vendedor = $vendedor->nombre.' '.$vendedor->apellido;

                $tipo_docelectronicio = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->tipo_comprobante)));
                $nombre_tipodocelectronico = '';
                if($tipo_docelectronicio) {
                    $nombre_tipodocelectronico = $tipo_docelectronicio->descripcion;
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueExplicit('A'.$n, $item->id_movimiento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('B'.$n, $item->id_sucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('C'.$n, $nombre_sucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.$n, $item->id_vendedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) //BENEFICIARIO
                    ->setCellValueExplicit('E'.$n, $nombre_vendedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('F'.$n, date("d-m-Y / H:i A", strtotime($item->fecha_movimiento)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //CLIENTE
                    ->setCellValueExplicit('G'.$n, $item->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //FACTURA/COTIZACIÓN
                    ->setCellValueExplicit('H'.$n, $item->detalle, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('I'.$n, $item->moneda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('J'.$n, $item->monto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('K'.$n, $numero_proveedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('L'.$n, $nombre_proveedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('M'.$n, $nombre_tipodocelectronico, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('N'.$n, $item->serie_num_comprobante, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('O'.$n, $nombre_condicionpago, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('P'.$n, $item->cpago_nrooperacion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('Q'.$n, $nombre_banco, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('R'.$n, $fecha_deposito_transferencia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ;

                $spreadsheet->setActiveSheetIndex(0)->getStyle('J'.$n)->getNumberFormat()->setFormatCode('0.00');
                if($n % 2 === 0) {
                    $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:R$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
                }

                $suma_egresos = $suma_egresos + $item->monto;
            }
        }
        
        //se define el contenido de la tabla con una letra más pequeña
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A10:R$n")->applyFromArray($estilo_contenido_tabla);

        /*
        $n++;
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:I$n");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", "TOTALES DE EGRESOS");
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->applyFromArray($estilo_subtitular_2);
        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit("L$n", $suma_egresos, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('L'.$n)->getNumberFormat()->setFormatCode('0.00');
        $spreadsheet->getActiveSheet()->getRowDimension($n)->setRowHeight(25);  
        */      

        // Configurar el ancho fijo de la columna B y ajustar el texto automáticamente
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(5);

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('G9:G'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(40);
        $spreadsheet->getActiveSheet()->getStyle('H9:H'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(15);
        $spreadsheet->getActiveSheet()->getStyle('L9:L'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getStyle('C9:C'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
        
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A7:L'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setVertical('center');

        //DATA PARA SEGUNDA TABLA DE INGRESOS
        $n = $n + 4;

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:R$n");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", "LISTA INGRESOS");
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:R$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FABF8F');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:R$n")->applyFromArray($estilo_subtitular_2);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:R$n")->getAlignment()->setHorizontal('center');

        $n++;

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('B'.$n, 'SUCURSAL')
            ->setCellValue('D8'.$n, 'USUARIO')
            ->setCellValue('K8'.$n, 'PROVEEDOR')
            ->setCellValue('M8'.$n, 'COMPROBANTE')
            ->setCellValue('O8'.$n, 'MODALIDAD DE PAGO');
        
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$n.':R'.$n)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$n.':R'.$n)->applyFromArray($estilo_subtitular);

        $n++;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$n, 'ID')
            ->setCellValue('B'.$n, 'ID SUCURSAL')
            ->setCellValue('C'.$n, 'NOMBRE SUCURSAL')
            ->setCellValue('D'.$n, 'ID USUARIO')
            ->setCellValue('E'.$n, 'NOMBRE USUARIO')
            ->setCellValue('F'.$n, 'FECHA')
            ->setCellValue('G'.$n, 'DESCRIPCION')
            ->setCellValue('H'.$n, 'DETALLE')
            ->setCellValue('I'.$n, 'MONEDA')
            ->setCellValue('J'.$n, 'MONTO')
            ->setCellValue('K'.$n, 'DOC')
            ->setCellValue('L'.$n, 'RAZÓN SOCIAL')
            ->setCellValue('M'.$n, 'TIPO DOC')
            ->setCellValue('N'.$n, 'SERIE-NUMERO')
            ->setCellValue('O'.$n, 'NOMBRE')
            ->setCellValue('P'.$n, 'NRO OPERACION')
            ->setCellValue('Q'.$n, 'BANCO')
            ->setCellValue('R'.$n, 'FECHA DEPÓSITO')
            ;
            
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$n.':R'.$n)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$n.':R'.$n)->applyFromArray($estilo_subtitular);

        $suma_ingreso = 0;
        $inicio_contenido_tabla2 = $n + 1;
        $num_item = 0;
		foreach($lista as $item) {
			$item = (object)$item;
            if($item->tipo == 'ingreso' && $item->estado == 'activo') {
                $n++;
                $num_item++;

                $nombre_proveedor = '';
                $numero_proveedor = '';
                if(count($item->data_proveedor) > 0) {
                    //$nombre_proveedor = $item->data_proveedor['num_doc']."\n".$item->data_proveedor['razon_social'];
                    $nombre_proveedor = $item->data_proveedor['razon_social'];
                    $numero_proveedor = $item->data_proveedor['num_doc'];
                }
                
                $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $item->id_sucursal)));
                $nombre_sucursal = $sucursal->nombre;

                $condicionpago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_condicionpago' => $item->id_condicionpago)));
                $nombre_condicionpago = '';
                if($condicionpago) {
                    $nombre_condicionpago = $condicionpago->condicionpago;
                }

                $banco = Cuentabanco::findFirst(array("id_contribuyente = :id_contribuyente: and id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_cuentabanco' => $item->idbanco)));
                $nombre_banco = '';
                if($banco) {
                    $nombre_banco = $banco->nombre_banco;
                }
                
                $fecha_deposito_transferencia = '';
                if(!empty($item->fechadeposito)) {
                    $fecha_deposito_transferencia = date("d-m-Y", strtotime($item->fechadeposito));
                }

                $vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :id_usuario: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_usuario' => $item->id_vendedor)));
                $nombre_vendedor = $vendedor->nombre.' '.$vendedor->apellido;

                $tipo_docelectronicio = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->tipo_comprobante)));
                $nombre_tipodocelectronico = '';
                if($tipo_docelectronicio) {
                    $nombre_tipodocelectronico = $tipo_docelectronicio->descripcion;
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueExplicit('A'.$n, $item->id_movimiento, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('B'.$n, $item->id_sucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('C'.$n, $nombre_sucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.$n, $item->id_vendedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) //BENEFICIARIO
                    ->setCellValueExplicit('E'.$n, $nombre_vendedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('F'.$n, date("d-m-Y / H:i A", strtotime($item->fecha_movimiento)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //CLIENTE
                    ->setCellValueExplicit('G'.$n, $item->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //FACTURA/COTIZACIÓN
                    ->setCellValueExplicit('H'.$n, $item->detalle, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('I'.$n, $item->moneda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('J'.$n, $item->monto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('K'.$n, $numero_proveedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('L'.$n, $nombre_proveedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('M'.$n, $nombre_tipodocelectronico, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('N'.$n, $item->serie_num_comprobante, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('O'.$n, $nombre_condicionpago, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('P'.$n, $item->cpago_nrooperacion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('Q'.$n, $nombre_banco, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('R'.$n, $fecha_deposito_transferencia, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ;

                $spreadsheet->setActiveSheetIndex(0)->getStyle('J'.$n)->getNumberFormat()->setFormatCode('0.00');
                if($n % 2 === 0) {
                    $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:R$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
                }

                $suma_ingreso = $suma_ingreso + $item->monto;
            }
        }
        
        //se define el contenido de la tabla con una letra más pequeña
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$inicio_contenido_tabla2:L$n")->applyFromArray($estilo_contenido_tabla);

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Lista Ingreso y Egresos';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function get_reporte_exel_ingresos_egresos_2($usuario, $resp) {
        $lista = $resp['lista_ingresos_salidas'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Listas Ingresos y Egresos")
            ->setSubject("Listas Ingresos y Egresos")
            ->setDescription(
                "Listas Ingresos y Egresos."
            )
            ->setKeywords("facturalaya.com, ingresos, egresos, salidas, entradas")
            ->setCategory("ingreso y egresos");

        $spreadsheet->setActiveSheetIndex(0)->setTitle("Ingresos y Egresos");

        /*
        $resp['fecha_inicio_time'] = $fecha_inicio_time;
        $resp['fecha_fin_time'] = $fecha_fin_time;
        $resp['ids_vendedores'] = $ids_vendedores;
        $resp['ids_sucursales'] = $ids_sucursales;
        */

        $fecha_inicio = date("d-m-Y / H:i A", strtotime($resp['fecha_inicio_time']));
        $fecha_fin = date("d-m-Y / H:i A", strtotime($resp['fecha_fin_time']));

        if(count($resp['ids_vendedores']) > 0) {
            $texto_vendedores = '';
            foreach($resp['ids_vendedores'] as $id_vendedor) {
                $sql_vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :id_usuario: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_usuario' => $id_vendedor)));
                if($sql_vendedor) {
                    $texto_vendedores .= $sql_vendedor->nombres.' '.$sql_vendedor->apellidos.' (ID: '.$sql_vendedor->idusuario.')'.", ";
                }
            }
        } else {
            $texto_vendedores = 'Todos';
        }

        if(count($resp['ids_sucursales']) > 0) {
            $texto_sucursales = '';
            foreach($resp['ids_sucursales'] as $id_sucursal) {
                $sql_sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $id_sucursal)));
                if($sql_sucursal) {
                    $texto_sucursales .= $sql_sucursal->nombre.' (ID: '.$sql_sucursal->idsucursal.')'.", ";
                }
            }
        } else {
            $texto_sucursales = 'Todas';
        }

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A2:L2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A3:L3");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A4:L4");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A5:L5");
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:L5')->getAlignment()->setVertical('left');
        $spreadsheet->getActiveSheet()->getStyle('A2:L5')->getAlignment()->setWrapText(true); 


        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A6:L6");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A7:L7");

        
        
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE INGRESOS Y EGRESOS DESDE EL '.$fecha_inicio.' HASTA EL '.$fecha_fin)
			->setCellValue('A3', 'Reporte Generado por: '.$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.')')
            ->setCellValue('A4', "Vendedores/Usuario Seleccionados: ".$texto_vendedores)
            ->setCellValue('A5', "Sucursales Seleccionadas: ".$texto_sucursales)

			->setCellValue('A8', 'N°')
            ->setCellValue('B8', 'RECIBO')
            ->setCellValue('C8', 'FECHA')
            ->setCellValue('D8', 'BENEFICIARIO')
            ->setCellValue('E8', 'DETALLE')
            ->setCellValue('F8', 'CLIENTE')
            ->setCellValue('G8', 'FACTURA/COTIZACIÓN')
            ->setCellValue('H8', 'RESPONSABLE/PROVEEDOR')
            ->setCellValue('I8', "NumDoc.\nFACTURA-BOLETA")
            ->setCellValue('J8', 'SUBTOTAL')
            ->setCellValue('K8', 'IGV')
            ->setCellValue('L8', 'TOTAL PAGADO')
            ;

        $estilo_titular = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );

        $estilo_subtitular = array(
            'font'  => array(
                'bold'  => false,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );

        $estilo_subtitular_2 = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );

        $estilo_contenido_tabla = array(
            'font'  => array(
                'bold'  => false,
                //'italic'=> true,
                'color' => array('rgb' => '000000'),
                'size'  => 8,
                'name'  => 'Verdana'
            )
        );

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:L2')->applyFromArray($estilo_titular);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:L5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A3:L5')->applyFromArray($estilo_titular);

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A8:L8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A8:L8')->applyFromArray($estilo_subtitular);

        $spreadsheet->getActiveSheet()->getStyle('I8:I8')->getAlignment()->setWrapText(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:L8')->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2:A2')->getAlignment()->setHorizontal('center');
        
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', 'LISTA EGRESOS');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A7:L7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FABF8F');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A7:L7')->applyFromArray($estilo_subtitular_2);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A7:L7")->getAlignment()->setHorizontal('center');

        $suma_egresos = 0;
        $n = 8;
        $num_item = 0;
		foreach($lista as $item) {
			$item = (object)$item;
            if($item->tipo == 'egreso' && $item->estado == 'activo') {
                $n++;
                $num_item++;

                $nombre_proveedor = '';
                if(count($item->data_proveedor) > 0) {
                    $nombre_proveedor = $item->data_proveedor['num_doc']."\n".$item->data_proveedor['razon_social'];
                }

                $data_extra_detalle = $this->extraerDatosEntreCorchetes($item->detalle);
                /*
                $resultado = [
                    'beneficiario' => null,
                    'cliente' => null,
                    'cotizacion_factura' => null,
                ];
                */        
                
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueExplicit('A'.$n, $num_item, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('B'.$n, $this->insertarSaltoLinea($item->descripcion, 4), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('C'.$n, date("d-m-Y / H:i A", strtotime($item->fecha_movimiento)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.$n, $data_extra_detalle['beneficiario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //BENEFICIARIO
                    ->setCellValueExplicit('E'.$n, $item->detalle, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('F'.$n, $data_extra_detalle['cliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //CLIENTE
                    ->setCellValueExplicit('G'.$n, $data_extra_detalle['cotizacion_factura'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //FACTURA/COTIZACIÓN
                    ->setCellValueExplicit('H'.$n, $nombre_proveedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('I'.$n, $item->serie_num_comprobante, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('J'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('K'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('L'.$n, $item->monto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                ;

                $spreadsheet->setActiveSheetIndex(0)->getStyle('L'.$n)->getNumberFormat()->setFormatCode('0.00');
                if($n % 2 === 0) {
                    $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
                }

                $suma_egresos = $suma_egresos + $item->monto;
            }
        }
        
        //se define el contenido de la tabla con una letra más pequeña
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A9:L$n")->applyFromArray($estilo_contenido_tabla);

        $n++;
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:I$n");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", "TOTALES DE EGRESOS");
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->applyFromArray($estilo_subtitular_2);
        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit("L$n", $suma_egresos, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('L'.$n)->getNumberFormat()->setFormatCode('0.00');
        $spreadsheet->getActiveSheet()->getRowDimension($n)->setRowHeight(25);        

        // Configurar el ancho fijo de la columna B y ajustar el texto automáticamente
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(5);

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('B3:B'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getStyle('E3:E'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getStyle('H3:H'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(10);
        $spreadsheet->getActiveSheet()->getStyle('I3:I'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A7:L'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setVertical('center');


        //DATA PARA SEGUNDA TABLA DE INGRESOS
        $n = $n + 4;

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:L$n");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", "LISTA INGRESOS");
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FABF8F');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->applyFromArray($estilo_subtitular_2);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getAlignment()->setHorizontal('center');

        $n++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$n, 'N°')
            ->setCellValue('B'.$n, 'RECIBO')
            ->setCellValue('C'.$n, 'FECHA')
            ->setCellValue('D'.$n, 'BENEFICIARIO')
            ->setCellValue('E'.$n, 'DETALLE')
            ->setCellValue('F'.$n, 'CLIENTE')
            ->setCellValue('G'.$n, 'FACTURA/COTIZACIÓN')
            ->setCellValue('H'.$n, 'RESPONSABLE/PROVEEDOR')
            ->setCellValue('I'.$n, "NumDoc.\nFACTURA-BOLETA")
            ->setCellValue('J'.$n, 'SUBTOTAL')
            ->setCellValue('K'.$n, 'IGV')
            ->setCellValue('L'.$n, 'TOTAL PAGADO')
            ;

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$n.':L'.$n)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$n.':L'.$n)->applyFromArray($estilo_subtitular);

        $suma_ingreso = 0;
        $inicio_contenido_tabla2 = $n + 1;
        $num_item = 0;
		foreach($lista as $item) {
			$item = (object)$item;
            if($item->tipo == 'ingreso' && $item->estado == 'activo') {
                $n++;
                $num_item++;

                $nombre_proveedor = '';
                if(count($item->data_proveedor) > 0) {
                    $nombre_proveedor = $item->data_proveedor['num_doc']."\n".$item->data_proveedor['razon_social'];
                }
                
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueExplicit('A'.$n, $num_item, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit('B'.$n, $this->insertarSaltoLinea($item->descripcion, 4), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('C'.$n, date("d-m-Y / H:i A", strtotime($item->fecha_movimiento)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //BENEFICIARIO
                    ->setCellValueExplicit('E'.$n, $item->detalle, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('F'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //CLIENTE
                    ->setCellValueExplicit('G'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING) //FACTURA/COTIZACIÓN
                    ->setCellValueExplicit('H'.$n, $nombre_proveedor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('I'.$n, $item->serie_num_comprobante, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('J'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('K'.$n, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('L'.$n, $item->monto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                ;

                $spreadsheet->setActiveSheetIndex(0)->getStyle('L'.$n)->getNumberFormat()->setFormatCode('0.00');
                if($n % 2 === 0) {
                    $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
                }

                $suma_ingreso = $suma_ingreso + $item->monto;
            }
        }

        //se define el contenido de la tabla con una letra más pequeña
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A$inicio_contenido_tabla2:L$n")->applyFromArray($estilo_contenido_tabla);

        if($suma_ingreso > 0) {
            $n++;
            $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:I$n");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", "TOTALES DE INGRESOS");
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:L$n")->applyFromArray($estilo_subtitular_2);
            $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit("L$n", $suma_ingreso, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $spreadsheet->getActiveSheet()->getRowDimension($n)->setRowHeight(25);    
            $spreadsheet->setActiveSheetIndex(0)->getStyle('L'.$n)->getNumberFormat()->setFormatCode('0.00');
        }

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Lista Ingreso y Egresos';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function extraerDatosEntreCorchetes($texto) {
        $resultado = [
            'beneficiario' => null,
            'cliente' => null,
            'cotizacion_factura' => null,
        ];
    
        // Buscar el texto dentro de corchetes
        if (preg_match('/\[(.*?)\]/', $texto, $coincidencia)) {
            $contenidoCorchetes = $coincidencia[1];
    
            // Dividir el contenido en partes usando el delimitador '|'
            $partes = explode('||', $contenidoCorchetes);
    
            // Asignar valores a cada clave si existen
            if (isset($partes[0])) {
                $resultado['beneficiario'] = trim($partes[0]);
            }
            if (isset($partes[1])) {
                $resultado['cliente'] = trim($partes[1]);
            }
            if (isset($partes[2])) {
                $resultado['cotizacion_factura'] = trim($partes[2]);
            }
        }
    
        return $resultado;
    }

    public function insertarSaltoLinea($texto, $numPalabras = 4) {
        // Divide el texto en palabras
        $palabras = explode(' ', $texto);
    
        // Si el número de palabras es mayor que $numPalabras, inserta un salto de línea
        if (count($palabras) > $numPalabras) {
            // Inserta el salto de línea después de las primeras $numPalabras
            array_splice($palabras, $numPalabras, 0, "\n");
        }
    
        // Une las palabras nuevamente en un solo string
        return implode(' ', $palabras);
    }

    public function get_lista_entradas_salidas($datapost, $contribuyente, $usuario) {
        $herramientas = new HerramientasController;
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

        $where_extra = '';
        if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        $query = "SELECT * FROM movimientocaja where id_contribuyente = :id_contribuyente and (fecha_movimiento BETWEEN :fecha_inicio and :fecha_fin) and tipo_envio_sunat = :tipo_envio_sunat ".$where_extra;

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio_time, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin_time, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (getProductosAction): ',  $e->getMessage(), "\n";
            exit();
        }

        $lista = array();
        $lista_ingresos_salidas = array();
        while ($item = $sentencia->fetch()) {
            $item = (object)$item;
            if($item->estado == 'activo') {
                $estado = '<span class="label label-success">Activo</span>';
            } else {
                $estado = '<span class="label label-danger">Inactivo</span>';
            }

            $texto_a_encriptar = "$item->id_movimiento||$item->id_contribuyente||$item->tipo_envio_sunat";
            $texto_encriptado = $herramientas->encriptar($texto_a_encriptar);
            $url_ticket_movimiento = '/facturacionv8/cajachica/imprimir_ticket_movimiento/?file='.urlencode($texto_encriptado);

            $opciones = '
            <ul class="icons-list text-center">
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a target="_blank" href="'.$url_ticket_movimiento.'"><i class="icon-printer"></i> Imprimir Ticket</a></li>
                        <li><a onclick="editar_movimiento('.$item->id_movimiento.')" data-id_movimiento="'.$item->id_movimiento.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                        <li><a onclick="eliminar_movimiento('.$item->id_movimiento.')" data-id_movimiento="'.$item->id_movimiento.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                    </ul>
                </li>
            </ul>';
            if($item->tipo_movimiento == 'ingreso') {
                $tipo_movimiento = '<span class="label label-success">Ingreso</span>';
            } else {
                $tipo_movimiento = '<span class="label label-danger">Egreso</span>';
            }
            
            $html_nota = '';
            if(!empty($item->detalle)) {
                $html_nota = '<img data-popup="popover" data-placement="top" title="Detalle" data-content="'.htmlspecialchars($item->detalle).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 30px; cursor: pointer;" />';
            }
            
            if($item->moneda == 'PEN') {
                $moneda_simbolo = 'S/ ';
            } else {
                $moneda_simbolo = '$ ';
            }

            $estado_movimiento = '';
            $estilo_tachado = '';
            if($item->estado == 'inactivo') {
                $estado_movimiento = '<br /><span class="label label-danger">Eliminado</span>';
                $estilo_tachado = ' style="text-decoration:line-through;" ';
            }

            $data_proveedor = array();
            if(!empty($item->id_proveedor)) {
                $proveedor = Proveedor::findFirst(array("id_contribuyente = :id_contribuyente:  and id_proveedor = :id_proveedor:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_proveedor' => $item->id_proveedor)));
                if($proveedor) {
                    $data_proveedor = array(
                        'id_tipodocidentidad'   => $proveedor->id_tipodocidentidad,
                        'num_doc'               => $proveedor->num_doc,
                        'razon_social'          => $proveedor->razon_social
                    );
                }
            }

            $lista[] = array(
                date("d/m/Y H:i:s", strtotime($item->fecha_movimiento)).$estado_movimiento, 
                '<div '.$estilo_tachado.'>'.$item->descripcion.$html_nota.'</div>', 
                '<div '.$estilo_tachado.'>'.$tipo_movimiento.'</div>',  
                '<div '.$estilo_tachado.'>'.$moneda_simbolo.' '.$item->monto.'</div>',  
                $opciones
            );

            $lista_ingresos_salidas[] = array(
                'id_movimiento'     => $item->id_movimiento,
                'fecha_movimiento'  => $item->fecha_movimiento,
                'estado'            => $item->estado,
                'tipo'              => $item->tipo_movimiento,
                'moneda'            => $item->moneda,
                'simbolo'           => $moneda_simbolo,
                'monto'             => $item->monto,
                'id_sucursal'       => $item->id_sucursal,
                'id_vendedor'       => $item->id_vendedor,
                'fecha_registro'    => $item->fecha_registro,
                'descripcion'       => $item->descripcion,
                'detalle'           => $item->detalle,

                'tipo_docelectronico'   => $item->tipo_comprobante,
                'tipo_comprobante'   => $item->tipo_comprobante,
                'serie_num_comprobante' => $item->serie_num_comprobante,
                'data_proveedor'        => $data_proveedor,
                'id_proveedor'        => $item->id_proveedor,
                'idbanco' => $item->idbanco,
                'fechadeposito' => $item->fechadeposito,
                'id_condicionpago' => $item->id_condicionpago,
                'cpago_nrooperacion' => $item->cpago_nrooperacion,
            );
        }
        
        $btn_pdf = '
        <a class="btn btn-danger mb-2" href="/facturacionv8/cajachica/download_ticket/'.$fecha_inicio_time.'/'.$fecha_fin_time.'/'.$datapost['vendedores'].'/'.$datapost['sucursales'].'"><i class="icon-file-pdf mr-2"></i>Descargar PDF</a>';

        $url_hliquidacion_soles = '/facturacionv8/cajachica/get_hoja_liquidacion/PEN/'.$fecha_inicio_time.'/'.$fecha_fin_time.'/'.$datapost['vendedores'].'/'.$datapost['sucursales'];
        $url_hliquidacion_dolares = '/facturacionv8/cajachica/get_hoja_liquidacion/USD/'.$fecha_inicio_time.'/'.$fecha_fin_time.'/'.$datapost['vendedores'].'/'.$datapost['sucursales'];

        $resp['respuesta'] = 'ok';
        $resp['lista'] = $lista;
        $resp['btn_pdf'] = $btn_pdf;
        $resp['url_hliquidacion_soles'] = $url_hliquidacion_soles;
        $resp['url_hliquidacion_dolares'] = $url_hliquidacion_dolares;
        $resp['lista_ingresos_salidas'] = $lista_ingresos_salidas;
        $resp['fecha_inicio_time'] = $fecha_inicio_time;
        $resp['fecha_fin_time'] = $fecha_fin_time;
        $resp['ids_vendedores'] = $ids_vendedores;
        $resp['ids_sucursales'] = $ids_sucursales;
        return $resp;
    }

    public function getDataMovimientoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_movimiento = !isset($datapost['id_movimiento'])?0:intval($datapost['id_movimiento']) + 0;
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

            $sql_movimiento_caja = Movimientocaja::findFirst(array("id_movimiento = :id_movimiento: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_movimiento' => $id_movimiento, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sql_movimiento_caja) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El movimiento de caja seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            $fecha = $sql_movimiento_caja->fecha_movimiento;
            $date = date_create($fecha);
            $fecha_final =date_format($date, 'Y-m-d');


            $resp['respuesta'] = 'ok';
            $resp['movimientocaja'] = $sql_movimiento_caja;
            //DD/MM/YYYY H:mm:ss
            $resp['fecha_movimiento_show'] = date("d/m/Y H:i:s", strtotime($sql_movimiento_caja->fecha_movimiento));
            $resp['fecha']= $fecha_final;
            echo json_encode($resp);
            exit();
        }
    }
    public function deleteAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_movimiento = !isset($datapost['id_movimiento'])?0:intval($datapost['id_movimiento']) + 0;
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

            $delete_movimiento = Movimientocaja::findFirst(array("id_movimiento = :id_movimiento: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_movimiento' => $id_movimiento, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$delete_movimiento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El movimiento seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            $delete_movimiento->estado = 'inactivo';
            try {
                if(!$delete_movimiento->save()) {
                    $msg = '';
                    foreach ($delete_movimiento->getMessages() as $message) {
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
                $resp['mensaje'] = 'Error al eliminar el movimiento de caja: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'El movimiento de caja se ha eliminado correctamente!';
            $resp['id_movimiento'] = $delete_movimiento->id_movimiento;
            echo json_encode($resp);
            exit();
        }
    }

    public function getTotalesVentasAction() {
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
            
            $totales_ventas = $this->get_resumen_ventas($fecha_inicio_time, $fecha_fin_time, $usuario->id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat);
            $anticipos = $this->get_total_anticipos($fecha_inicio_time, $fecha_fin_time, $usuario->id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat);
            
            $resp['respuesta'] = 'ok';
            $resp['totales'] = $totales_ventas;
            $resp['totales']['anticipos'] = $anticipos['total_anticipos'];
            echo json_encode($resp);
            exit();
        }
    }

    public function get_resumen_ventas($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat) {
        //echo $fecha_inicio.' => '.$fecha_fin.' => '.$id_contribuyente.' => '.json_encode($ids_sucursales).' => '.json_encode($ids_vendedores).' => '.$tipo_envio_sunat;
        
		$boletas_soles = array();
		$boletas_soles['contado'] = 0;
		$boletas_soles['credito'] = 0;
		$boletas_soles['tarjeta_credito'] = 0;
        $boletas_soles['transferencia'] = 0;
        $boletas_soles['monto_adeudado'] = 0;
        $boletas_soles['monto_adeudado_inicial'] = 0;
        $boletas_soles['total_documentos'] = 0;
		$boletas_soles['total'] = 0;
		$boletas_dolares = $boletas_soles;

		$facturas_soles = array();
		$facturas_soles['contado'] = 0;
		$facturas_soles['credito'] = 0;
		$facturas_soles['tarjeta_credito'] = 0;
        $facturas_soles['transferencia'] = 0;
        $facturas_soles['monto_adeudado'] = 0;
        $facturas_soles['monto_adeudado_inicial'] = 0;
        $facturas_soles['total_documentos'] = 0;
		$facturas_soles['total'] = 0;
		$facturas_dolares = $facturas_soles;

		$notas_credito_soles = array();
		$notas_credito_soles['contado'] = 0;
		$notas_credito_soles['credito'] = 0;
		$notas_credito_soles['tarjeta_credito'] = 0;
        $notas_credito_soles['transferencia'] = 0;
        $notas_credito_soles['monto_adeudado'] = 0;
        $notas_credito_soles['monto_adeudado_inicial'] = 0;
        $notas_credito_soles['total_documentos'] = 0;
		$notas_credito_soles['total'] = 0;
		$notas_credito_dolares = $notas_credito_soles;

		$notas_debito_soles = array();
		$notas_debito_soles['contado'] = 0;
		$notas_debito_soles['credito'] = 0;
		$notas_debito_soles['tarjeta_credito'] = 0;
        $notas_debito_soles['transferencia'] = 0;
        $notas_debito_soles['monto_adeudado'] = 0;
        $notas_debito_soles['monto_adeudado_inicial'] = 0;
        $notas_debito_soles['total_documentos'] = 0;
		$notas_debito_soles['total'] = 0;
		$notas_debito_dolares = $notas_debito_soles;

		$notas_venta_soles = array();
		$notas_venta_soles['contado'] = 0;
		$notas_venta_soles['credito'] = 0;
		$notas_venta_soles['tarjeta_credito'] = 0;
        $notas_venta_soles['transferencia'] = 0;
        $notas_venta_soles['monto_adeudado'] = 0;
        $notas_venta_soles['monto_adeudado_inicial'] = 0;
        $notas_venta_soles['total_documentos'] = 0;
		$notas_venta_soles['total'] = 0;
        $notas_venta_dolares = $notas_venta_soles;
        
        $abono_soles = array();
		$abono_soles['contado'] = 0;
		$abono_soles['credito'] = 0;
		$abono_soles['tarjeta_credito'] = 0;
        $abono_soles['transferencia'] = 0;
        $abono_soles['monto_adeudado'] = 0;
        $abono_soles['monto_adeudado_inicial'] = 0;
        $abono_soles['total_documentos'] = 0;
		$abono_soles['total'] = 0;
        $abono_dolares = $abono_soles;

        $boletas_soles = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '03', 'PEN');
        $facturas_soles = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '01', 'PEN');
        $notas_credito_soles = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '07', 'PEN');
        $notas_debito_soles = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '08', 'PEN');
        $notas_venta_soles = $this->get_resumen_ventas_by_doc_no_oficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '77', 'PEN');
        $abono_soles = $this->get_totales_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'PEN');

        $boletas_soles['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '03', 'PEN');
        $facturas_soles['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '01', 'PEN');
        $notas_credito_soles['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '07', 'PEN');
        $notas_debito_soles['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '08', 'PEN');
        $notas_venta_soles['total_documentos'] = $this->get_numdocs_nooficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '77', 'PEN');
        $abono_soles['total_documentos'] = $this->get_num_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'PEN');

        $boletas_dolares = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '03', 'USD');
        $facturas_dolares = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '01', 'USD');
        $notas_credito_dolares = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '07', 'USD');
        $notas_debito_dolares = $this->get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '08', 'USD');
        $notas_venta_dolares = $this->get_resumen_ventas_by_doc_no_oficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '77', 'USD');
        $abono_dolares = $this->get_totales_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'USD');

        $boletas_dolares['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '03', 'USD');
        $facturas_dolares['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '01', 'USD');
        $notas_credito_dolares['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '07', 'USD');
        $notas_debito_dolares['total_documentos'] = $this->get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '08', 'USD');
        $notas_venta_dolares['total_documentos'] = $this->get_numdocs_nooficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, '77', 'USD');
        $abono_dolares['total_documentos'] = $this->get_num_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'USD');

        $monto_cobrado_soles = $this->get_monto_cobrado($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'PEN');
        $monto_cobrado_dolares = $this->get_monto_cobrado($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'USD');

        $total_egreso_soles = $this->get_monto_movimiento($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'PEN', 'egreso');
        $total_egreso_dolares = $this->get_monto_movimiento($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'USD', 'egreso');

        $total_ingreso_soles = $this->get_monto_movimiento($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'PEN', 'ingreso');
        $total_ingreso_dolares = $this->get_monto_movimiento($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, 'USD', 'ingreso');


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
        
        //verificamos si alguna venta fué realizada en dólares
        if($boletas_dolares['total'] > 0 || $facturas_dolares['total'] > 0 || $notas_credito_dolares['total'] > 0 || $notas_debito_dolares['total'] > 0 ||  $notas_venta_dolares['total'] > 0 ||  $abono_dolares['total'] > 0) {
            $tiene_dolares = 'si';
        } else {
            $tiene_dolares = 'no';
        }
        
		$totales['boletas_soles'] = $boletas_soles;
		$totales['facturas_soles'] = $facturas_soles;
		$totales['notas_credito_soles'] = $notas_credito_soles;
		$totales['notas_debito_soles'] = $notas_debito_soles;
        $totales['notas_venta_soles'] = $notas_venta_soles;
        $totales['abono_soles'] = $abono_soles;

		$totales['boletas_dolares'] = $boletas_dolares;
		$totales['facturas_dolares'] = $facturas_dolares;
		$totales['notas_credito_dolares'] = $notas_credito_dolares;
		$totales['notas_debito_dolares'] = $notas_debito_dolares;
        $totales['notas_venta_dolares'] = $notas_venta_dolares;
        $totales['abono_dolares'] = $abono_dolares;
         
        $totales['monto_cobrado_soles'] = $monto_cobrado_soles;
        $totales['monto_cobrado_dolares'] = $monto_cobrado_dolares;

        $totales['total_egreso_soles'] = $total_egreso_soles;
        $totales['total_egreso_dolares'] = $total_egreso_dolares;
        $totales['total_ingreso_soles'] = $total_ingreso_soles;
        $totales['total_ingreso_dolares'] = $total_ingreso_dolares;

        $totales['subtotales_soles'] = $subtotales_soles;
        $totales['subtotales_dolares'] = $subtotales_dolares;

        $totales['tiene_dolares'] = $tiene_dolares;

		return $totales;
    }

    public function get_num_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_codigomoneda) {
        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and m.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and m.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        $where_extra = $where_extra." 
        AND NOT EXISTS (
            SELECT 1
            FROM doc_electronicoxnota den
            WHERE m.id_contribuyente = den.cpe_id_contribuyente
                AND m.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                AND m.serie_comprobante = den.cpe_serie_comprobante
                AND m.numero_comprobante = den.cpe_numero_comprobante
                AND m.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                AND den.nota_id_cod_tipomotivo_credito = '01' 
                AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
        ) 
        ";

        $query = "SELECT * FROM `monto_cobrado` m where m.id_contribuyente = :id_contribuyente and (m.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and m.tipo_envio_sunat = :tipo_envio_sunat and m.id_codigomoneda = :id_codigomoneda and m.estado = 'activo' ".$where_extra." ";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_num_abonos): ',  $e->getMessage(), "\n";
            exit();
        }
        
        $n = 0;
        while ($fila = $sentencia->fetch()) {
			$n++;
        }
        
		return $n;
    }

    public function get_numdocs_nooficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_tipodoc_electronico, $id_codigomoneda) {
		$where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
        
		$query = "SELECT id_contribuyente, id_tipodocumento, numero_comprobante, modalidad FROM `doc_no_oficial` where id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and id_tipodocumento = :id_tipodocumento and modalidad = :tipo_envio_sunat and id_codigomoneda = :id_codigomoneda and estado_documento = 'activo' and tipo = 'venta' ".$where_extra." ";
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodocumento', $id_tipodoc_electronico, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_numdocs_nooficial): ',  $e->getMessage(), "\n";
            exit();
        }        
        
        $n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
        }
        
		return $n;
    }
    
    public function get_numdocs_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_tipodoc_electronico, $id_codigomoneda) {
        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if($id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '01') {
            $where_extra = $where_extra." 
            AND NOT EXISTS (
                SELECT 1
                FROM doc_electronicoxnota den
                WHERE de.id_contribuyente = den.cpe_id_contribuyente
                    AND de.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                    AND de.serie_comprobante = den.cpe_serie_comprobante
                    AND de.numero_comprobante = den.cpe_numero_comprobante
                    AND de.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                    AND den.nota_id_cod_tipomotivo_credito = '01' 
                    AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
            )
            ";
        }
		
        $criterio_nota_credito = '';
        if($id_tipodoc_electronico == '07') {
            $criterio_nota_credito = " and de.id_cod_tipomotivo_credito <> '01' ";
        }
		
        $query = "SELECT de.id_contribuyente, de.id_tipodoc_electronico, de.serie_comprobante, de.tipo_envio_sunat FROM `doc_electronico` de where de.id_contribuyente = :id_contribuyente and (de.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and de.id_tipodoc_electronico = :id_tipodoc_electronico and de.tipo_envio_sunat = :tipo_envio_sunat and de.id_codigomoneda = :id_codigomoneda and de.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') $criterio_nota_credito ".$where_extra." ";
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_numdocs_sunat): ',  $e->getMessage(), "\n";
            exit();
        }
        
        $n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
        }
        
		return $n;
    }
 
	//función optimizada para extraer el resumen de las ventas by doc sunat
    public function get_resumen_ventas_by_doc_sunat($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_tipodoc_electronico, $id_codigomoneda) {
		$where_extra = '';
        if (!empty($ids_vendedores)) {
            $where_extra .= " AND de.id_vendedor IN (" . implode(",", $ids_vendedores) . ") ";
        }
        if (!empty($ids_sucursales)) {
            $where_extra .= " AND de.id_sucursal IN (" . implode(",", $ids_sucursales) . ") ";
        }

        $criteria_nota_credito = '';
        $join_nota_credito = '';
        if ($id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '01') {
            $join_nota_credito = "
                LEFT JOIN (
                    SELECT DISTINCT
                        cpe_id_contribuyente,
                        cpe_id_tipodoc_electronico,
                        cpe_serie_comprobante,
                        cpe_numero_comprobante,
                        cpe_tipo_envio_sunat
                    FROM doc_electronicoxnota
                    WHERE nota_id_cod_tipomotivo_credito = '01'
                        AND nota_estado_envio_sunat NOT IN ('rechazado', 'anulado')
                ) den ON de.id_contribuyente = den.cpe_id_contribuyente
                    AND de.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                    AND de.serie_comprobante = den.cpe_serie_comprobante
                    AND de.numero_comprobante = den.cpe_numero_comprobante
                    AND de.tipo_envio_sunat = den.cpe_tipo_envio_sunat
            ";
            $criteria_nota_credito = " AND den.cpe_id_contribuyente IS NULL ";
        }

        if ($id_tipodoc_electronico == '07') {
            $criteria_nota_credito = " AND de.id_cod_tipomotivo_credito <> '01' ";
        }

        $query = "
            SELECT
                de.id_contribuyente,
                de.id_tipodoc_electronico,
                de.id_condicionpago,
                SUM(de.total_gravadas) AS gravado,
                SUM(de.total_inafecta) AS inafecto,
                SUM(de.total_exoneradas) AS exonerado,
                SUM(de.total_gratuitas) AS gratuito,
                SUM(de.total_exportacion) AS exportacion,
                SUM(de.total_icbper) AS icbper,
                SUM(de.total_descuento) AS descuento,
                SUM(de.sub_total) AS sub_total,
                SUM(de.total_igv) AS igv,
                SUM(de.monto_adeudado) AS monto_adeudado,
                SUM(de.monto_adeudado_inicial) AS monto_adeudado_inicial,
                SUM(de.total) AS total
            FROM doc_electronico de
            $join_nota_credito
            WHERE de.id_contribuyente = :id_contribuyente
                AND de.fecha_registro BETWEEN :fecha_inicio AND :fecha_fin
                AND de.id_tipodoc_electronico = :id_tipodoc_electronico
                AND de.tipo_envio_sunat = :tipo_envio_sunat
                AND de.id_codigomoneda = :id_codigomoneda
                AND de.estado_envio_sunat IN ('aceptado', 'pendiente', 'ticket')
                $criteria_nota_credito
                $where_extra
            GROUP BY
                de.id_contribuyente,
                de.id_tipodoc_electronico,
                de.id_condicionpago
        ";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_resumen_ventas_by_doc_sunat): ',  $e->getMessage(), "\n";
            exit();
        }
        

		$totales = array();
		$totales['contado'] = 0;
		$totales['credito'] = 0; //aparece en la tabla como PAGO PARCIAL
		$totales['tarjeta_credito'] = 0;
        $totales['transferencia'] = 0;
        $totales['monto_adeudado'] = 0;
        $totales['monto_adeudado_inicial'] = 0;
        $totales['total_documentos'] = 0;
        $totales['total'] = 0;
        

        $n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
			$fila = (object)$fila;
			if(empty($fila->id_condicionpago)) {
				$totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
			} else {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago)));
				if(!$condicion_pago) {
					$totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
				} else {
					if($condicion_pago->tipo == 'contado') {
						$totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
					}

					if($condicion_pago->tipo == 'credito') {
                        $totales['credito'] = round($totales['credito'] + (floatval($fila->total) - floatval($fila->monto_adeudado)), 2) + 0; //PAGO PARCIAL
                        $totales['monto_adeudado'] = round(floatval($totales['monto_adeudado']) + floatval($fila->monto_adeudado), 2) + 0;
                        $totales['monto_adeudado_inicial'] = round(floatval($totales['monto_adeudado_inicial']) + floatval($fila->monto_adeudado_inicial), 2) + 0;
					}

					if($condicion_pago->tipo == 'tarjeta_credito') {
						$totales['tarjeta_credito'] = round($totales['tarjeta_credito'] + floatval($fila->total), 2) + 0;
					}

					if($condicion_pago->tipo == 'transferencia') {
						$totales['transferencia'] = round($totales['transferencia'] + floatval($fila->total), 2) + 0;
					}
				}
			}
 
			$totales['total'] = round($totales['total'] + floatval($fila->total) - floatval($fila->monto_adeudado_inicial), 2) + 0;
		}
        
        $totales['total_documentos'] = $n;

		return $totales;
	}

    public function get_total_anticipos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_codigomoneda = '') {
        $query_anticipos = "
            SELECT 
                cpe.id_tipodoc_electronico, cpe.id_codigomoneda, sum(anticipo.monto_anticipo) as total_anticipo 
            FROM doc_electronico cpe INNER JOIN doc_electronico_anticipo anticipo ON (cpe.id_contribuyente = anticipo.cpe_id_contribuyente and cpe.id_tipodoc_electronico = anticipo.cpe_id_tipodoc_electronico and cpe.serie_comprobante = anticipo.cpe_serie_comprobante and cpe.numero_comprobante = anticipo.cpe_numero_comprobante and cpe.tipo_envio_sunat = anticipo.cpe_tipo_envio_sunat )
            WHERE 
                cpe.id_contribuyente = :id_contribuyente and 
                cpe.tipo_envio_sunat = :tipo_envio_sunat and 
                cpe.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                cpe.fecha_comprobante >= :fecha_inicio and cpe.fecha_comprobante <= :fecha_fin 
        ";
        
        $query_anticipos = $query_anticipos." and (cpe.id_tipodoc_electronico = '01' or cpe.id_tipodoc_electronico = '03' or cpe.id_tipodoc_electronico = '08' or cpe.id_tipodoc_electronico = '07') ";

        if(count($ids_vendedores) > 0){ $query_anticipos = $query_anticipos." and cpe.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query_anticipos = $query_anticipos." and cpe.id_sucursal in (".implode(",", $ids_sucursales).") "; }
        if($id_codigomoneda != ''){ $query_anticipos = $query_anticipos." and cpe.id_codigomoneda = :id_codigomoneda "; }

        $query_anticipos = $query_anticipos." group by cpe.id_tipodoc_electronico, cpe.id_codigomoneda";
        
        try {
            $sentencia_anticipos = $this->db->prepare($query_anticipos);
            $sentencia_anticipos->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_anticipos->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_anticipos->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_anticipos->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            if($id_codigomoneda != '') {
                $sentencia_anticipos->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            }

            $sentencia_anticipos->execute();

            $total_anticipos['facturas_soles'] = 0;
            $total_anticipos['boletas_soles'] = 0;
            $total_anticipos['notas_credito_soles'] = 0;
            $total_anticipos['notas_debito_soles'] = 0;
            $total_anticipos['total_soles'] = 0;

            $total_anticipos['facturas_dolares'] = 0;
            $total_anticipos['boletas_dolares'] = 0;
            $total_anticipos['notas_dolares'] = 0;
            $total_anticipos['notas_dolares'] = 0;
            $total_anticipos['total_dolares'] = 0;

            $total_anticipos['total'] = 0;

            while ($fila2 = $sentencia_anticipos->fetch()) {
                $fila2 = (object)$fila2;
                if($fila2->id_tipodoc_electronico == '01' && $fila2->id_codigomoneda == 'PEN') {
                    $total_anticipos['facturas_soles'] = $total_anticipos['facturas_soles'] + $fila2->total_anticipo;
                } else if($fila2->id_tipodoc_electronico == '03' && $fila2->id_codigomoneda == 'PEN') {
                    $total_anticipos['boletas_soles'] = $total_anticipos['boletas_soles'] + $fila2->total_anticipo;
                } else if($fila2->id_tipodoc_electronico == '08' && $fila2->id_codigomoneda == 'PEN') {
                    $total_anticipos['notas_credito_soles'] = $total_anticipos['notas_credito_soles'] + $fila2->total_anticipo;
                } else if($fila2->id_tipodoc_electronico == '07' && $fila2->id_codigomoneda == 'PEN') {
                    $total_anticipos['notas_debito_soles'] = $total_anticipos['notas_debito_soles'] + $fila2->total_anticipo;
                }

                if($fila2->id_codigomoneda == 'PEN') {
                    $total_anticipos['total_soles'] = $total_anticipos['total_soles'] + $fila2->total_anticipo;
                }

                if($fila2->id_tipodoc_electronico == '01' && $fila2->id_codigomoneda == 'USD') {
                    $total_anticipos['facturas_dolares'] = $total_anticipos['facturas_dolares'] + $fila2->total_anticipo;
                } else if($fila2->id_tipodoc_electronico == '03' && $fila2->id_codigomoneda == 'USD') {
                    $total_anticipos['boletas_dolares'] = $total_anticipos['boletas_dolares'] + $fila2->total_anticipo;
                } else if($fila2->id_tipodoc_electronico == '08' && $fila2->id_codigomoneda == 'USD') {
                    $total_anticipos['notas_credito_dolares'] = $total_anticipos['notas_credito_dolares'] + $fila2->total_anticipo;
                } else if($fila2->id_tipodoc_electronico == '07' && $fila2->id_codigomoneda == 'USD') {
                    $total_anticipos['notas_debito_dolares'] = $total_anticipos['notas_debito_dolares'] + $fila2->total_anticipo;
                }

                if($fila2->id_codigomoneda == 'USD') {
                    $total_anticipos['total_dolares'] = $total_anticipos['total_dolares'] + $fila2->total_anticipo;
                }

                $total_anticipos['total'] = $total_anticipos['total'] + $fila2->total_anticipo;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada get_total_anticipos: ',  $e->getMessage(), "\n";
            exit();
        }

        $resp['respuesta'] = 'ok';
        $resp['total_anticipos'] = $total_anticipos;
        return $resp;
    }
 
	public function get_resumen_ventas_by_doc_sunat_anterior($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_tipodoc_electronico, $id_codigomoneda) {
		$where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and de.id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and de.id_sucursal in (".implode(",", $ids_sucursales).") "; }
		//if(count($ids_cod_monedas) > 0){ $where_extra = $where_extra." and id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
		//if(count($estados_envio_sunat) > 0){ $where_extra = $where_extra." and estado_envio_sunat in ('".implode("','", $estados_envio_sunat)."') "; }

        if($id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '01') {
            $where_extra = $where_extra." 
            AND NOT EXISTS (
                SELECT 1
                FROM doc_electronicoxnota den
                WHERE de.id_contribuyente = den.cpe_id_contribuyente
                    AND de.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                    AND de.serie_comprobante = den.cpe_serie_comprobante
                    AND de.numero_comprobante = den.cpe_numero_comprobante
                    AND de.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                    AND den.nota_id_cod_tipomotivo_credito = '01' 
                    AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
            )
            ";
        }
		
        $criterio_nota_credito = '';
        if($id_tipodoc_electronico == '07') {
            $criterio_nota_credito = " and de.id_cod_tipomotivo_credito <> '01' ";
        }

        $query = "SELECT de.id_contribuyente, de.id_tipodoc_electronico, de.id_condicionpago, sum(de.total_gravadas) gravado, sum(de.total_inafecta) inafecto, sum(de.total_exoneradas) exonerado, sum(de.total_gratuitas) gratuito, sum(de.total_exportacion) exportacion, sum(de.total_icbper) icbper, sum(de.total_descuento) descuento, sum(de.sub_total) sub_total, sum(de.total_igv) igv, sum(de.monto_adeudado) as monto_adeudado, sum(de.monto_adeudado_inicial) as monto_adeudado_inicial, sum(de.total) total FROM doc_electronico de where de.id_contribuyente = :id_contribuyente and (de.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and de.id_tipodoc_electronico = :id_tipodoc_electronico and de.tipo_envio_sunat = :tipo_envio_sunat and de.id_codigomoneda = :id_codigomoneda and de.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') $criterio_nota_credito ".$where_extra." GROUP BY de.id_contribuyente, de.id_tipodoc_electronico, de.id_condicionpago ";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_resumen_venas_by_doc_sunat_anterior): ',  $e->getMessage(), "\n";
            exit();
        }

		$totales = array();
		$totales['contado'] = 0;
		$totales['credito'] = 0; //aparece en la tabla como PAGO PARCIAL
		$totales['tarjeta_credito'] = 0;
        $totales['transferencia'] = 0;
        $totales['monto_adeudado'] = 0;
        $totales['monto_adeudado_inicial'] = 0;
        $totales['total_documentos'] = 0;
        $totales['total'] = 0;
        

        $n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
			$fila = (object)$fila;
			if(empty($fila->id_condicionpago)) {
				$totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
			} else {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago)));
				if(!$condicion_pago) {
					$totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
				} else {
					if($condicion_pago->tipo == 'contado') {
						$totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
					}

					if($condicion_pago->tipo == 'credito') {
                        $totales['credito'] = round($totales['credito'] + (floatval($fila->total) - floatval($fila->monto_adeudado)), 2) + 0; //PAGO PARCIAL
                        $totales['monto_adeudado'] = round(floatval($totales['monto_adeudado']) + floatval($fila->monto_adeudado), 2) + 0;
                        $totales['monto_adeudado_inicial'] = round(floatval($totales['monto_adeudado_inicial']) + floatval($fila->monto_adeudado_inicial), 2) + 0;
					}

					if($condicion_pago->tipo == 'tarjeta_credito') {
						$totales['tarjeta_credito'] = round($totales['tarjeta_credito'] + floatval($fila->total), 2) + 0;
					}

					if($condicion_pago->tipo == 'transferencia') {
						$totales['transferencia'] = round($totales['transferencia'] + floatval($fila->total), 2) + 0;
					}
				}
			}
 
			$totales['total'] = round($totales['total'] + floatval($fila->total) - floatval($fila->monto_adeudado_inicial), 2) + 0;
		}
        
        $totales['total_documentos'] = $n;
		return $totales;
	}

	public function get_resumen_ventas_by_doc_no_oficial($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_tipodoc_electronico, $id_codigomoneda) {
		$where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
		if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
		//if(count($ids_cod_monedas) > 0){ $where_extra = $where_extra." and id_codigomoneda in ('".implode("','", $ids_cod_monedas)."') "; }
		//if(count($estados_envio_sunat) > 0){ $where_extra = $where_extra." and estado_documento in ('".implode("','", $estados_envio_sunat)."') "; }
		
		$query = "SELECT id_contribuyente, id_tipodocumento, id_condicionpago, sum(total_gravadas) gravado, sum(total_inafecta) inafecto, sum(total_exoneradas) exonerado, sum(total_gratuitas) gratuito, sum(total_exportacion) exportacion, sum(total_icbper) icbper, sum(total_descuento) descuento, sum(sub_total) sub_total, sum(total_igv) igv, sum(monto_adeudado) as monto_adeudado, sum(monto_adeudado_inicial) as monto_adeudado_inicial, sum(total) total FROM `doc_no_oficial` where id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and id_tipodocumento = :id_tipodocumento and modalidad = :tipo_envio_sunat and id_codigomoneda = :id_codigomoneda and estado_documento = 'activo' ".$where_extra." GROUP BY id_contribuyente, id_tipodocumento, id_condicionpago";

		//$query = "SELECT id_contribuyente, id_tipodocumento, id_condicionpago, sum(total_gravadas) gravado, sum(total_inafecta) inafecto, sum(total_exoneradas) exonerado, sum(total_gratuitas) gratuito, sum(total_exportacion) exportacion, sum(total_icbper) icbper, sum(total_descuento) descuento, sum(sub_total) sub_total, sum(total_igv) igv, sum(total) total FROM `doc_no_oficial` where id_contribuyente = $id_contribuyente and (fecha_registro BETWEEN CAST('$fecha_inicio' AS DATETIME) and CAST('$fecha_fin' AS DATETIME)) and id_tipodocumento = '$id_tipodoc_electronico' and modalidad = '$tipo_envio_sunat' ".$where_extra." GROUP BY id_contribuyente, id_tipodocumento, id_condicionpago";
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_tipodocumento', $id_tipodoc_electronico, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_resumen_ventas_by_doc_no_oficial): ',  $e->getMessage(), "\n";
            exit();
        }    
			
		$totales = array();
		$totales['contado'] = 0;
        $totales['credito'] = 0;
        $totales['monto_adeudado'] = 0;
        $totales['monto_adeudado_inicial'] = 0;
		$totales['tarjeta_credito'] = 0;
        $totales['transferencia'] = 0;
        $totales['total_documentos'] = 0;
		$totales['total'] = 0;

        $n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
			$fila = (object)$fila;
			if(empty($fila->id_condicionpago)) {
				$totales['contado'] = $totales['contado'] + $fila->total;
			} else {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago)));
				if(!$condicion_pago) {
					$totales['contado'] = $totales['contado'] + $fila->total;
				} else {
					if($condicion_pago->tipo == 'contado') {
						$totales['contado'] = $totales['contado'] + $fila->total;
					}

					if($condicion_pago->tipo == 'credito') {
                        $totales['credito'] = $totales['credito'] + (floatval($fila->total) - floatval($fila->monto_adeudado)); //PAGO PARCIAL
                        $totales['monto_adeudado'] = floatval($totales['monto_adeudado']) + floatval($fila->monto_adeudado);
                        $totales['monto_adeudado_inicial'] = floatval($totales['monto_adeudado_inicial']) + floatval($fila->monto_adeudado_inicial);
					} 

					if($condicion_pago->tipo == 'tarjeta_credito') {
						$totales['tarjeta_credito'] = round($totales['tarjeta_credito'] + $fila->total, 2);
					}

					if($condicion_pago->tipo == 'transferencia') {
						$totales['transferencia'] = round($totales['transferencia'] + $fila->total, 2);
					}
				}
			}

			$totales['total'] = round($totales['total'] + $fila->total - floatval($fila->monto_adeudado_inicial), 2);
		}
        
        $totales['total_documentos'] = $n;
		return $totales;
    }

    public function get_totales_abonos($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_codigomoneda) {
        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and m.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and m.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        //$query = "SELECT id_contribuyente, id_tipodoc_electronico, id_condicionpago, sum(total) total FROM `monto_cobrado` where id_contribuyente = :id_contribuyente and (fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and tipo_envio_sunat = :tipo_envio_sunat and id_codigomoneda = :id_codigomoneda and estado = 'activo' ".$where_extra." GROUP BY id_contribuyente, id_condicionpago";

        $where_extra = $where_extra." 
        AND NOT EXISTS (
            SELECT 1
            FROM doc_electronicoxnota den
            WHERE m.id_contribuyente = den.cpe_id_contribuyente
                AND m.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                AND m.serie_comprobante = den.cpe_serie_comprobante
                AND m.numero_comprobante = den.cpe_numero_comprobante
                AND m.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                AND den.nota_id_cod_tipomotivo_credito = '01' 
                AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
        ) 
        ";

        $query = "
        (SELECT m.id_contribuyente, m.id_condicionpago, sum(m.total) total FROM `monto_cobrado` m INNER JOIN doc_electronico d  ON (m.id_contribuyente = d.id_contribuyente and m.id_tipodoc_electronico = d.id_tipodoc_electronico and m.serie_comprobante = d.serie_comprobante and m.numero_comprobante = d.numero_comprobante and m.tipo_envio_sunat = d.tipo_envio_sunat) WHERE  m.id_tipodoc_electronico in ('01', '03', '07', '08') and m.estado = 'activo' and d.estado_envio_sunat not in ('rechazado', 'anulado') and m.id_contribuyente = :id_contribuyente and (m.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and m.tipo_envio_sunat = :tipo_envio_sunat and m.id_codigomoneda = :id_codigomoneda $where_extra GROUP BY m.id_contribuyente, m.id_condicionpago) 

        UNION

        (SELECT m.id_contribuyente, m.id_condicionpago, sum(m.total) total FROM `monto_cobrado` m INNER JOIN doc_no_oficial d  ON (m.id_contribuyente = d.id_contribuyente and m.id_tipodoc_electronico = d.id_tipodocumento and m.numero_comprobante = d.numero_comprobante and m.tipo_envio_sunat = d.modalidad) WHERE m.id_tipodoc_electronico = '77' and m.estado = 'activo' and d.estado_documento = 'activo' and m.id_contribuyente = :id_contribuyente and (m.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and m.tipo_envio_sunat = :tipo_envio_sunat and m.id_codigomoneda = :id_codigomoneda $where_extra GROUP BY m.id_contribuyente, m.id_condicionpago)
        ";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_totales_abonos): ',  $e->getMessage(), "\n";
            exit();
        }
        
        $totales = array();
		$totales['contado'] = 0;
        $totales['credito'] = 0;
        $totales['monto_adeudado'] = 0;
        $totales['monto_adeudado_inicial'] = 0;
		$totales['tarjeta_credito'] = 0;
        $totales['transferencia'] = 0;
        $totales['total_documentos'] = 0;
        $totales['total'] = 0;
        
        $n = 0;
        while ($fila = $sentencia->fetch()) {
			$n++;
			$fila = (object)$fila;
			if(empty($fila->id_condicionpago)) {
				$totales['contado'] = $totales['contado'] + $fila->total;
			} else {
				$condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->id_condicionpago)));
				if(!$condicion_pago) {
					$totales['contado'] = $totales['contado'] + $fila->total;
				} else {
					if($condicion_pago->tipo == 'contado') {
						$totales['contado'] = $totales['contado'] + $fila->total;
					}

					if($condicion_pago->tipo == 'credito') {
                        $totales['contado'] = $totales['contado'] + $fila->total;
					} 

					if($condicion_pago->tipo == 'tarjeta_credito') {
						$totales['tarjeta_credito'] = $totales['tarjeta_credito'] + $fila->total;
					}

					if($condicion_pago->tipo == 'transferencia') {
						$totales['transferencia'] = $totales['transferencia'] + $fila->total;
					}
				}
			}

			$totales['total'] = $totales['total'] + $fila->total;
		}
        
        $totales['total_documentos'] = $n;
		return $totales;
    }
    
    public function get_monto_cobrado($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_codigomoneda) {
        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and m.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and m.id_sucursal in (".implode(",", $ids_sucursales).") "; }
        
        $where_extra_nota_credito = " 
        AND NOT EXISTS (
            SELECT 1
            FROM doc_electronicoxnota den
            WHERE m.id_contribuyente = den.cpe_id_contribuyente
                AND m.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                AND m.serie_comprobante = den.cpe_serie_comprobante
                AND m.numero_comprobante = den.cpe_numero_comprobante
                AND m.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                AND den.nota_id_cod_tipomotivo_credito = '01' 
                AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
        ) 
        ";

        $query = "SELECT sum(m.total) total FROM monto_cobrado m INNER JOIN doc_electronico d ON (m.id_contribuyente = d.id_contribuyente and m.id_tipodoc_electronico = d.id_tipodoc_electronico and m.serie_comprobante = d.serie_comprobante and m.numero_comprobante = d.numero_comprobante and m.tipo_envio_sunat = d.tipo_envio_sunat) where m.id_tipodoc_electronico in ('01', '03', '07', '08') and  m.id_contribuyente = :id_contribuyente and (m.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and m.tipo_envio_sunat = :tipo_envio_sunat and m.id_codigomoneda = :id_codigomoneda and m.estado = 'activo' and d.estado_envio_sunat not in ('rechazado', 'anulado')  $where_extra_nota_credito ".$where_extra;

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_monto_cobrado): ',  $e->getMessage(), "\n";
            exit();
        }

        $fila = $sentencia->fetch();

        $total_cpe = floatval($fila['total']) + 0;

        $query2 = "SELECT sum(m.total) total FROM monto_cobrado m INNER JOIN doc_no_oficial d ON (m.id_contribuyente = d.id_contribuyente and m.id_tipodoc_electronico = d.id_tipodocumento and m.numero_comprobante = d.numero_comprobante and m.tipo_envio_sunat = d.modalidad) where m.id_tipodoc_electronico = '77' and  m.id_contribuyente = :id_contribuyente and (m.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and m.tipo_envio_sunat = :tipo_envio_sunat and m.id_codigomoneda = :id_codigomoneda and m.estado = 'activo' and d.estado_documento = 'activo' ".$where_extra;

        try {
            $sentencia2 = $this->db->prepare($query2);
            $sentencia2->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia2->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia2->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia2->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia2->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia2->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_monto_cobrado): ',  $e->getMessage(), "\n";
            exit();
        }

        $fila2 = $sentencia2->fetch();

        $total_notasventa = floatval($fila['total']) + 0;

        return round(floatval($total_cpe + $total_notasventa), 2);
    }

    public function get_monto_movimiento_by_condicionpago($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_codigomoneda, $tipo_movimiento) {
        /*
        $condicion_pago_efectivo = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and tipo = 'contado' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

        if(!$condicion_pago_efectivo) {
            $condicion_pago_efectivo = new Condiciondepago();
            $condicion_pago_efectivo->id_contribuyente = $id_contribuyente;
            $condicion_pago_efectivo->tipo = 'contado';
            $condicion_pago_efectivo->condicionpago = 'Pago en Efectivo';
            $condicion_pago_efectivo->estado = 'activo';

            if(!$condicion_pago_efectivo->save()) {
                $msg = '';
                foreach ($condicion_pago_efectivo->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tienes permitido crear ventas al crédito!';
                return $resp;
            }
        }
            */

        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and m.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and m.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        $query = "SELECT COALESCE(c.tipo, 'contado') as tipo, m.moneda, sum(m.monto) total FROM movimientocaja m LEFT JOIN condiciondepago c ON m.id_condicionpago = c.id_condicionpago WHERE m.id_contribuyente = :id_contribuyente and m.tipo_envio_sunat = :tipo_envio_sunat and m.moneda = :id_codigomoneda and (m.fecha_movimiento BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and m.tipo_movimiento = :tipo_movimiento and m.estado = 'activo' $where_extra GROUP BY COALESCE(c.tipo, 'contado'), m.moneda;";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_movimiento', $tipo_movimiento, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_monto_movimiento_by_condicionpago) ',  $e->getMessage(), "\n";
            exit();
        }

        $totales = array();
        $totales['contado'] = 0;
        $totales['tarjeta_credito'] = 0;
        $totales['transferencia'] = 0;

        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            if($fila->tipo == 'contado') {
                $totales['contado'] = round($totales['contado'] + floatval($fila->total), 2) + 0;
            }

            if($fila->tipo == 'tarjeta_credito') {
                $totales['tarjeta_credito'] = round($totales['tarjeta_credito'] + floatval($fila->total), 2) + 0;
            }

            if($fila->tipo == 'transferencia') {
                $totales['transferencia'] = round($totales['transferencia'] + floatval($fila->total), 2) + 0;
            }
        }
        
        $resp['respuesta'] = 'ok';
        $resp['totales'] = $totales;
        return $resp;
    }

    public function get_monto_movimiento($fecha_inicio, $fecha_fin, $id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat, $id_codigomoneda, $tipo_movimiento) {
        
        $where_extra = '';
		if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        //$query = "SELECT sum(monto) FROM `movimientocaja` where id_contribuyente = 1 and (fecha_movimiento BETWEEN CAST('2019-12-29 00:01:01' AS DATETIME) and CAST('2019-12-30 10:01:01' AS DATETIME)) and moneda = 'PEN' and tipo_envio_sunat = 'prueba'";
        $query = "SELECT sum(monto) total FROM `movimientocaja` where id_contribuyente = :id_contribuyente and (fecha_movimiento BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and moneda = :id_codigomoneda and tipo_envio_sunat = :tipo_envio_sunat and tipo_movimiento = :tipo_movimiento and estado = 'activo' ".$where_extra;

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->bindParam(':id_codigomoneda', $id_codigomoneda, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_movimiento', $tipo_movimiento, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_monto_movimiento): ',  $e->getMessage(), "\n";
            exit();
        }

        $fila = $sentencia->fetch();

        return floatval($fila['total']) + 0;
    }

    public function  downloadTicketAction($fecha_inicio_time, $fecha_fin_time, $ids_vendedores_inicial, $ids_sucursales_inicial){
        $this->view->disable();
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
                        $ids_sucursales_inicial = $usuario->idsucursal;
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $ids_sucursales_inicial = $usuario->idsucursal;
                        $ids_vendedores_inicial = $usuario->idusuario;
                    }
                }
            }
        }

        $ids_vendedores = empty($ids_vendedores_inicial)?array():array_map('intval', explode(',', $ids_vendedores_inicial));
        $ids_sucursales = empty($ids_sucursales_inicial)?array():array_map('intval', explode(',', $ids_sucursales_inicial));
       
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

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_time));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_time));

        $totales_ventas = $this->get_resumen_ventas($fecha_inicio_time, $fecha_fin_time, $usuario->id_contribuyente, $ids_sucursales, $ids_vendedores, $tipo_envio_sunat); 
     
        $html = $this->reporte_ticket($totales_ventas,  $contribuyente);
       // echo $html;
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper(array(0,0,219.2,915));
        $dompdf->render();
        $dompdf->stream("Reporte Caja Chica.pdf");
        exit();
    }

    public function reporte_ticket($totales_ventas, $contribuyente){
        $this->view->disable();

        $ruta_base = $this->ruta_base_public_html;
		
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 80px; height: 80px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
        $img_logo = $ruta_base.$img_logo;
        
    
        $ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $contribuyente->codigo_ubigeo)));

        if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
        }
        
        //Factura 

        $factura_dolares = '';
        $factura_soles = '';
        $totales_ventas['facturas_dolares'] = (object)$totales_ventas['facturas_dolares'];
        $totales_ventas['facturas_soles'] = (object)$totales_ventas['facturas_soles'];

        //Total de facturas
        $total_facturas_documentos = $totales_ventas['facturas_dolares']->total_documentos + $totales_ventas['facturas_soles']->total_documentos;

        
        $factura_dolares = $factura_dolares.'
            <td>'.$totales_ventas['facturas_dolares']->contado.'</td>
            <td>'.$totales_ventas['facturas_dolares']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['facturas_dolares']->transferencia.'</td>
            <td>'.$totales_ventas['facturas_dolares']->monto_adeudado.'</td>
            <td>'.$totales_ventas['facturas_dolares']->total.'</td>
       ';

        $factura_soles = $factura_soles.'
            <td>'.$totales_ventas['facturas_soles']->contado.'</td>
            <td>'.$totales_ventas['facturas_soles']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['facturas_soles']->transferencia.'</td>
            <td>'.$totales_ventas['facturas_soles']->monto_adeudado.'</td>
            <td>'.$totales_ventas['facturas_soles']->total.'</td>
        ';

        //Boleta
        
        $boleta_dolares = '';
        $boleta_soles = '';
        $totales_ventas['boletas_dolares'] = (object)$totales_ventas['boletas_dolares'];
        $totales_ventas['boletas_soles'] = (object)$totales_ventas['boletas_soles'];
        
        //Total de boletas
        $total_boletas_documentos = $totales_ventas['boletas_dolares']->total_documentos + $totales_ventas['boletas_soles']->total_documentos;

        $boleta_dolares = $boleta_dolares.'
            <td>'.$totales_ventas['boletas_dolares']->contado.'</td>
            <td>'.$totales_ventas['boletas_dolares']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['boletas_dolares']->transferencia.'</td>
            <td>'.$totales_ventas['boletas_dolares']->monto_adeudado.'</td>
            <td>'.$totales_ventas['boletas_dolares']->total.'</td>
        ';

        $boleta_soles = $boleta_soles.'
            <td>'.$totales_ventas['boletas_soles']->contado.'</td>
            <td>'.$totales_ventas['boletas_soles']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['boletas_soles']->transferencia.'</td>
            <td>'.$totales_ventas['boletas_soles']->monto_adeudado.'</td>
            <td>'.$totales_ventas['boletas_soles']->total.'</td>
        ';

        //Nota de débito
                
        $notas_credito_dolares = '';
        $notas_credito_soles = '';
        $totales_ventas['notas_credito_dolares'] = (object)$totales_ventas['notas_credito_dolares'];
        $totales_ventas['notas_credito_soles'] = (object)$totales_ventas['notas_credito_soles'];

        //Total de Notas de credito
        $total_notas_credito_documentos = $totales_ventas['notas_credito_dolares']->total_documentos + $totales_ventas['notas_credito_soles']->total_documentos;

        $notas_credito_dolares = $notas_credito_dolares.'
            <td>'.$totales_ventas['notas_credito_dolares']->contado.'</td>
            <td>'.$totales_ventas['notas_credito_dolares']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['notas_credito_dolares']->transferencia.'</td>
            <td>'.$totales_ventas['notas_credito_dolares']->monto_adeudado.'</td>
            <td>'.$totales_ventas['notas_credito_dolares']->total.'</td>
        ';

        $notas_credito_soles = $notas_credito_soles.'
            <td>'.$totales_ventas['notas_credito_soles']->contado.'</td>
            <td>'.$totales_ventas['notas_credito_soles']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['notas_credito_soles']->transferencia.'</td>
            <td>'.$totales_ventas['notas_credito_soles']->monto_adeudado.'</td>
            <td>'.$totales_ventas['notas_credito_soles']->total.'</td>
        ';  
        //Nota de débito
        
        $notas_debito_dolares = '';
        $notas_debito_soles = '';
        $totales_ventas['notas_debito_dolares'] = (object)$totales_ventas['notas_debito_dolares'];
        $totales_ventas['notas_debito_soles'] = (object)$totales_ventas['notas_debito_soles'];

         //Total de Notas de debito
         $total_notas_debito_documentos = $totales_ventas['notas_debito_dolares']->total_documentos + $totales_ventas['notas_debito_soles']->total_documentos;
        
        $notas_debito_dolares = $notas_debito_dolares.'
            <td>'.$totales_ventas['notas_debito_dolares']->contado.'</td>
            <td>'.$totales_ventas['notas_debito_dolares']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['notas_debito_dolares']->transferencia.'</td>
            <td>'.$totales_ventas['notas_debito_dolares']->monto_adeudado.'</td>
            <td>'.$totales_ventas['notas_debito_dolares']->total.'</td>
        ';

        $notas_debito_soles = $notas_debito_soles.'
            <td>'.$totales_ventas['notas_debito_soles']->contado.'</td>
            <td>'.$totales_ventas['notas_debito_soles']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['notas_debito_soles']->transferencia.'</td>
            <td>'.$totales_ventas['notas_debito_soles']->monto_adeudado.'</td>
            <td>'.$totales_ventas['notas_debito_soles']->total.'</td>
        ';  

        //Notas de venta
        
        $notas_ventas_dolares = '';
        $notas_ventas_soles = '';
        $totales_ventas['notas_venta_dolares'] = (object)$totales_ventas['notas_venta_dolares'];
        $totales_ventas['notas_venta_soles'] = (object)$totales_ventas['notas_venta_soles'];

        //Total de Notas de venta
        $total_notas_venta_documentos = $totales_ventas['notas_venta_dolares']->total_documentos + $totales_ventas['notas_venta_soles']->total_documentos;

        $notas_ventas_dolares = $notas_ventas_dolares.'
            <td>'.$totales_ventas['notas_venta_dolares']->contado.'</td>
            <td>'.$totales_ventas['notas_venta_dolares']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['notas_venta_dolares']->transferencia.'</td>
            <td>'.$totales_ventas['notas_venta_dolares']->monto_adeudado.'</td>
            <td>'.$totales_ventas['notas_venta_dolares']->total.'</td>
        ';

        $notas_ventas_soles = $notas_ventas_soles.'
            <td>'.$totales_ventas['notas_venta_soles']->contado.'</td>
            <td>'.$totales_ventas['notas_venta_soles']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['notas_venta_soles']->transferencia.'</td>
            <td>'.$totales_ventas['notas_venta_soles']->monto_adeudado.'</td>
            <td>'.$totales_ventas['notas_venta_soles']->total.'</td>
        ';  

        //Abonos
        
        $abono_dolares = '';
        $abono_soles = '';
        $totales_ventas['abono_dolares'] = (object)$totales_ventas['abono_dolares'];
        $totales_ventas['abono_soles'] = (object)$totales_ventas['abono_soles'];

        //Total de Abonos
        $total_abono_documentos = $totales_ventas['abono_dolares']->total_documentos + $totales_ventas['abono_soles']->total_documentos;

        $abono_dolares = $abono_dolares.'
            <td>'.$totales_ventas['abono_dolares']->contado.'</td>
            <td>'.$totales_ventas['abono_dolares']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['abono_dolares']->transferencia.'</td>
            <td>'.$totales_ventas['abono_dolares']->monto_adeudado.'</td>
            <td>'.$totales_ventas['abono_dolares']->total.'</td>
        ';

        $abono_soles = $abono_soles.'
            <td>'.$totales_ventas['abono_soles']->contado.'</td>
            <td>'.$totales_ventas['abono_soles']->tarjeta_credito.'</td>
            <td>'.$totales_ventas['abono_soles']->transferencia.'</td>
            <td>'.$totales_ventas['abono_soles']->monto_adeudado.'</td>
            <td>'.$totales_ventas['abono_soles']->total.'</td>
        ';  

         //Subtotales
        
         $subtotales_dolares = '';
         $subtotales_soles = '';
         $totales_ventas['subtotales_dolares'] = (object)$totales_ventas['subtotales_dolares'];
         $totales_ventas['subtotales_soles'] = (object)$totales_ventas['subtotales_soles'];
         
         //Sumatoria subtotales
         $total_subtotales_soles = ($totales_ventas['facturas_soles']->total + $totales_ventas['boletas_soles']->total - $totales_ventas['notas_credito_soles']->total + $totales_ventas['notas_debito_soles']->total + $totales_ventas['notas_venta_soles']->total + $totales_ventas['abono_soles']->total);

         $total_subtotales_dolares = ($totales_ventas['facturas_dolares']->total + $totales_ventas['boletas_dolares']->total - $totales_ventas['notas_credito_dolares']->total + $totales_ventas['notas_debito_dolares']->total + $totales_ventas['notas_venta_dolares']->total + $totales_ventas['abono_dolares']->total);

         $subtotales_dolares = $subtotales_dolares.'
             <td>'.$totales_ventas['subtotales_dolares']->contado.'</td>
             <td>'.$totales_ventas['subtotales_dolares']->tarjeta_credito.'</td>
             <td>'.$totales_ventas['subtotales_dolares']->transferencia.'</td>
             <td>'.$totales_ventas['subtotales_dolares']->monto_adeudado.'</td>
             <td>'.$total_subtotales_dolares.'</td>
         ';
 
         $subtotales_soles = $subtotales_soles.'
             <td>'.$totales_ventas['subtotales_soles']->contado.'</td>
             <td>'.$totales_ventas['subtotales_soles']->tarjeta_credito.'</td>
             <td>'.$totales_ventas['subtotales_soles']->transferencia.'</td>
             <td>'.$totales_ventas['subtotales_soles']->monto_adeudado.'</td>
             <td>'.$total_subtotales_soles.'</td>
         ';  

        //Totales
        $total_dolares = $total_subtotales_dolares + $totales_ventas['total_ingreso_dolares'] - $totales_ventas['total_egreso_dolares'];
        $total_soles = $total_subtotales_soles + $totales_ventas['total_ingreso_soles'] - $totales_ventas['total_egreso_soles'];

     
        if($totales_ventas['tiene_dolares'] == 'si'){
           $display_dolares = "display_initial";
           $display_soles = "display_initial";
        }else{
            $display_dolares = "display_none";
            $display_soles = "display_initial";
        }
        $data_patrocinador = $this->get_parametros_iniciales();
        $img_empresa = $ruta_base.$data_patrocinador['logo_img_461'];
       
       
        $html = '
        <style>
            @page { margin: 20px 10px !important; }
            .display_initial{
               
            }
            .display_none{
                display: none;
            }
            .font-weight{
                font-weight: bold;
            }
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                font-size: 10px;
                margin-bottom: 10px;
            }
            .table-header   td,  .table-header th{
                border:none;
                text-align: center;
            }
            th{ font-weight: bold;}
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
            }
            .tg-lqy6{text-align:right;vertical-align:top}

            .text-success {
                color: #43a047;
            }

            .text-danger {
                color: #d84315;
            }
            .text-indigo {
                color: #3f51b5!important;
            }
        </style>
        <table class="table-header" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <img '.$style_logo.' src="'.$img_logo.'" />
                <td>
            </tr>
            <tr>
                <td align="center" height="5">
                <td>
            </tr>
            <tr>
                <td align="center" class="font-weight-bold" style="text-transform: uppercase; font-weight: bold">'.$contribuyente->nombre_comercial.'<td>
            </tr>
            <tr>
                <td align="center" class="texto_12">R.U.C.:'.$contribuyente->ruc.'<td>
            </tr>
            <tr>
                <td align="center" class="texto_12">'.$contribuyente->direccion_fiscal.' '.$contribuyente->urbanizacion.'<br /> '.$ubigeo_ubicacion.'<td>
            </tr>
            <tr>
                <td align="center" class="texto_12">
                    <img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" /> Telf.: '.$contribuyente->telefono.'<br />
                    <img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/email.svg" style="width: 12px;" /> Email: '.$contribuyente->email.'
                <td>
            </tr>
            
        </table>

		<table class="tb_factura">
            <thead>
                <tr>
                    <th  colspan="6">Factura: '.$total_facturas_documentos.'</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_factura_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$factura_dolares.'
                </tr>
                <tr class="tr_factura_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$factura_soles.'
                </tr>
            </tbody>
        </table>

        <table class="tb_boleta">
            <thead>
                <tr>
                    <th  colspan="6">Boleta: '.$total_boletas_documentos.'</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_boleta_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$boleta_dolares.'
                </tr>
                <tr class="tr_boleta_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$boleta_soles.'
                </tr>
            </tbody>
        </table>
        
        <table class="tb_nota_credito">
            <thead>
                <tr>
                    <th  colspan="6">Nota de crédito: '.$total_notas_credito_documentos.'</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_notas_credito_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$notas_credito_dolares.'
                </tr>
                <tr class="tr_notas_credito_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$notas_credito_soles.'
                </tr>
            </tbody>
        </table>

        <table class="tb_nota_debito">
            <thead>
                <tr>
                    <th  colspan="6">Nota de Débito: '.$total_notas_debito_documentos.'</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_notas_debito_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$notas_debito_dolares.'
                </tr>
                <tr class="tr_notas_debito_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$notas_debito_soles.'
                </tr>
            </tbody>
        </table>

        <table class="tb_nota_venta">
            <thead>
                <tr>
                    <th  colspan="6">Nota de venta: '.$total_notas_venta_documentos.'</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_notas_ventas_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$notas_ventas_dolares.'
                </tr>
                <tr  class="tr_notas_ventas_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$notas_ventas_soles.'
                </tr>
            </tbody>
        </table>

        <table class="tb_abonos">
            <thead>
                <tr>
                    <th  colspan="6">Abonos: '.$total_abono_documentos.'</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_abono_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$abono_dolares.'
                </tr>
                <tr class="tr_abono_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$abono_soles.'
                </tr>
            </tbody>
        </table>

        <table class="tb_sub_totales font-weight-bold">
            <thead>
                <tr>
                    <th  colspan="6">Subtotales</th>
                </tr>
            </thead>
            <tbody style="font-weight: bold;">>
                <tr>
                    <td></td>
                    <td>Cash/Efect.</td>
                    <td>Deb/Crédito</td>
                    <td>Transf.</td>
                    <td>Por Cobrar</td>
                    <td>Total</td>
                </tr>
                <tr class="tr_subtotales_dolares '.$display_dolares.'">
                    <td>$</td>
                    '.$subtotales_dolares.'
                </tr>
                <tr class="tr_subtotales_soles '.$display_soles.'">
                    <td>S/.</td>
                    '.$subtotales_soles.'
                </tr>
            </tbody>
        </table>
     
        <table class="tb_totales">
            <tbody style="font-weight: bold;"> 
                <tr>
                    <th></th>
                    <th class="'.$display_dolares.'">$</th>
                    <th class="'.$display_soles.'">S/.</th>
                </tr>
                <tr class="text-success">
                    <td>Ingresos de caja:</td>
                    <td class="'.$display_dolares.'">'.$totales_ventas['total_ingreso_dolares'].'</td>
                    <td class="'.$display_soles.'">'.$totales_ventas['total_ingreso_soles'].'</td>
                </tr>
                <tr  class="text-danger">
                    <td>Egresos de caja:</td>
                    <td class="'.$display_dolares.'">'.$totales_ventas['total_egreso_dolares'].'</td>
                    <td class="'.$display_soles.'">'.$totales_ventas['total_egreso_soles'].'</td>
                </tr>
                <tr class="text-indigo">
                    <td>Total:</td>
                    <td class="'.$display_dolares.'">'.$total_dolares.'</td>
                    <td class="'.$display_soles.'">'.$total_soles.'</td>
                </tr>
            </tbody>
        </table>
        ';
        
        return $html;
    }

    public function detallecajachicaAction() {
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
            
            $resp = $this->detallecajachica($datapost, $contribuyente, $usuario);
            echo json_encode($resp);
            exit();
        }
    }

    public function detallecajachica($datapost, $contribuyente, $usuario) {
        $fecha_inicio = $datapost['fecha_inicio_valor'];
        $fecha_fin = $datapost['fecha_fin_valor'];
        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', explode(',', $datapost['vendedores']));
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', explode(',', $datapost['sucursales']));
        $id_tipodoc_electronico = isset($datapost['id_tipocpe'])?$datapost['id_tipocpe']:'';
        $id_codigomoneda = $datapost['id_codigomoneda'];
        $tipo_transaccion = $datapost['tipo_transaccion'];
        $tipo_venta = !isset($datapost['tipo_venta'])?'contado':(($datapost['tipo_venta'] == 'credito')?'credito':'contado');
        $tipo_reporte = !isset($datapost['tipo_reporte'])?'documentos':$datapost['tipo_reporte'];
        
        if($tipo_reporte == 'documentos') {
            $resp_lista_docs = $this->get_lista_docs_detalle_caja($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales, $ids_vendedores, $id_tipodoc_electronico, $id_codigomoneda, $tipo_transaccion, $tipo_venta);
            if($resp_lista_docs['respuesta'] == 'error') {
                return $resp_lista_docs;
            }

            $resp_lista_docs_detalle = $this->get_lista_docs_detallado_detalle_caja($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales, $ids_vendedores, $id_tipodoc_electronico, $id_codigomoneda, $tipo_transaccion, $tipo_venta);
            if($resp_lista_docs_detalle['respuesta'] == 'error') {
                return $resp_lista_docs_detalle;
            }
            
            $resp['respuesta'] = 'ok';
            $resp['lista_docs'] = $resp_lista_docs['lista'];
            $resp['lista_docs_detalle'] = $resp_lista_docs_detalle['lista'];
            $resp['data_extra'] = $resp_lista_docs['data_extra'];
            return $resp;
        } else if($tipo_reporte == 'abonos') {
            $resp = $this->lista_detalleabonos_detalle_caja($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales, $ids_vendedores, $id_codigomoneda, $tipo_transaccion, 'credito', $tipo_reporte);
            return $resp;
        } else if($tipo_reporte == 'todo') {
            $resp = $this->lista_detalleabonos_detalle_caja($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales, $ids_vendedores, $id_codigomoneda, $tipo_transaccion, 'todo', $tipo_reporte);
            return $resp;
        } else if($tipo_reporte == 'hoja_liquidacion') {
            $resp = $this->get_hoja_liquidacion($contribuyente->id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales, $ids_vendedores, $id_codigomoneda, '', 'todo');
            return $resp;
        }
        
        $resp['respuesta'] = 'error';
        $resp['titulo'] = 'error';
        $resp['mensaje'] = 'No se encuentran datos';
        return $resp;
    }

    /*
    $id_contribuyente
    $fecha_inicio
    $fecha_fin
    $ids_sucursales = lista ids separados por comas
    $ids_vendedores = lista ids separados por comas
    $id_tipodoc_electronico = 01: FACTURA, 03: BOLETA, 07: NOTA DE CRÉDITO, 08: NOTA DE DÉBITO, 77: NOTA DE VENTA, AB: ABONO
    $id_codigomoneda = USD: dólares, PEN: sóles
    $tipo_transaccion = contado, credito, tarjeta_credito, transferencia ===> efectivo, tarjeta_credito, transferencia, deuda, monto_total
    */
    public function get_lista_docs_detalle_caja($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales = array(), $ids_vendedores = array(), $id_tipodoc_electronico = '', $id_codigomoneda = '', $tipo_transaccion = '', $tipo_venta = 'contado') {

        $herramientas = new HerramientasController;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos la empresa';
            echo json_encode($resp);
            exit();
        }

        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

        $fecha_inicio_valor = empty($fecha_inicio)?'':$fecha_inicio;
        $fecha_fin_valor = empty($fecha_fin)?'':$fecha_fin;

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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

        
        $where_opcional = '';

        /*
        $ids_vendedores_string = trim($ids_vendedores_string);
        if(empty($ids_vendedores_string)) {
            $ids_vendedores = array();
        } else {
            $ids_vendedores = array_map('intval', explode(',', $ids_vendedores_string));
        }
        */
        //$ids_vendedores = empty($ids_vendedores_string)?array():array_map('intval', explode(',', $ids_vendedores_string));        
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
                if(!$vendedor) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Vendedor';
                    $resp['mensaje'] = 'Uno de los vendedores seleccionados no está activo o no existe!';
                    echo json_encode($resp);
                    exit();
                }
            }
        }

        /*
        $ids_sucursales_string = trim($ids_sucursales_string);
        if(empty($ids_sucursales_string)) {
            $ids_sucursales = array();
        } else {
            $ids_sucursales = array_map('intval', explode(',', $ids_sucursales_string));
        }
        $ids_sucursales = empty($ids_sucursales_string)?array():array_map('intval', explode(',', $ids_sucursales_string));
        */
        if (($key_sucursales = array_search(0, $ids_sucursales)) !== false) { unset($ids_sucursales[$key_sucursales]);}
        if(count($ids_sucursales) > 0) {
            foreach($ids_sucursales as $idsucursal) {
                $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
                if(!$sucursal_bd) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Sucursal';
                    $resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
                    echo json_encode($resp);
                    exit();
                }
            }
        }

        if(!empty($id_tipodoc_electronico)) {
            $array_id_tipodocs = array('01', '03', '07', '08', '77');
            if(!in_array($id_tipodoc_electronico, $array_id_tipodocs)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo de Documento Seleccionado no Existe!';
                echo json_encode($resp);
                exit();
            }
        }

        if(!empty($id_codigomoneda)) {
            $array_monedas = array('PEN', 'USD');
            if(!in_array($id_codigomoneda, $array_monedas)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de moneda seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }
        }

        if(!empty($tipo_transaccion)) {
            $array_tipos_transaccion = array('contado', 'credito', 'tarjeta_credito', 'transferencia');
            if(!in_array($tipo_transaccion, $array_tipos_transaccion)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de transacción seleccionado!';
                echo json_encode($resp);
                exit();
            }
        }
        
        if(count($ids_vendedores) > 0){ $where_opcional = $where_opcional." and doc.id_vendedor in (".implode(",", $ids_vendedores).") "; }

		if(count($ids_sucursales) > 0){ $where_opcional = $where_opcional." and doc.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($id_tipodoc_electronico)) { $where_opcional_doc = " and doc.id_tipodoc_electronico = '".$id_tipodoc_electronico."' "; }

        if(!empty($id_codigomoneda)) { $where_opcional = $where_opcional." and doc.id_codigomoneda = '".$id_codigomoneda."' "; }

        if(!empty($tipo_transaccion)) { 
            if($tipo_transaccion == 'contado') {
                $where_opcional = $where_opcional." and (cp.tipo is NULL or cp.tipo = 'contado')  "; 
            } else {
                $where_opcional = $where_opcional." and cp.tipo = '$tipo_transaccion'  ";
            }
        }

        if(empty($tipo_transaccion) && empty($id_codigomoneda)) {
            
        } else {
            if($tipo_venta == 'contado') {
                $where_opcional = $where_opcional." and IFNULL(doc.monto_adeudado_inicial, 0) <= 0 ";
            } else {
                $where_opcional = $where_opcional." and IFNULL(doc.monto_adeudado_inicial, 0) > 0 ";
            }
        }
        
        if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
            $query = "
            SELECT doc.id_tipodoc_electronico, doc.serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, doc.total_isc, doc.total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal

            FROM doc_electronico doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.tipo_envio_sunat = :tipo_envio_sunat and 
                doc.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                (doc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.cpe_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.cpe_serie_comprobante
                        AND doc.numero_comprobante = den.cpe_numero_comprobante
                        AND doc.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.nota_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.nota_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.nota_serie_comprobante
                        AND doc.numero_comprobante = den.nota_numero_comprobante
                        AND doc.tipo_envio_sunat = den.nota_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 
                 
                $where_opcional $where_opcional_doc 
            ";
            
        } else if($id_tipodoc_electronico == '77') {
            $query = "
            SELECT doc.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, 0 total_isc, 0 total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal

            FROM doc_no_oficial doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.modalidad = :tipo_envio_sunat and 
                (doc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and 
                doc.estado_documento = 'activo' and 
                doc.id_tipodocumento = '77' and 
                doc.tipo = 'venta' 
                $where_opcional 
            ";
            
        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El Tipo de Documento Seleccionado no Existe!';
            echo json_encode($resp);
            exit();
        }
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio_time, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin_time, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo '==> Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }
        
        $num_docs['num_facturas'] = 0;
        $num_docs['num_boletas'] = 0;
        $num_docs['num_notas_credito'] = 0;
        $num_docs['num_notas_debito'] = 0;
        $num_docs['num_notas_venta'] = 0;
        $totales_condicion_pago = array();

        $lista = array();
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;

            $nombre_banco = '';
            if(!empty($fila->cpago_idbanco)) {
                $banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $fila->cpago_idbanco)));
                if($banco) {
                    $nombre_banco = $banco->nombre_banco;
                }
            }
            
            $lista[] = array(
                'id_tipodoc'    => $fila->id_tipodoc_electronico,
                'serie_correlativo' => (!empty($fila->serie_comprobante))?$fila->serie_comprobante.'-'.$fila->numero_comprobante:$fila->numero_comprobante,
                'total_gravadas'    => $fila->total_gravadas,
                'total_inafecta'    => $fila->total_inafecta,
                'total_exoneradas'  => $fila->total_exoneradas,
                'total_gratuitas'   => $fila->total_gratuitas,
                'total_exportacion' => $fila->total_exportacion,
                'total_icbper'      => $fila->total_icbper,
                'total_descuento'   => $fila->total_descuento,
                'sub_total'         => $fila->sub_total,
                'total_igv'         => $fila->total_igv,
                'total_isc'         => $fila->total_isc,
                'total_otr_imp'     => $fila->total_otr_imp,
                'total'             => $fila->total,
                'deuda'             => $fila->monto_adeudado,
                'pagado'            => round($fila->total - $fila->monto_adeudado, 2),
                'moneda'            => ($fila->id_codigomoneda == 'PEN')?'SOLES':'DOLARES',
                'sucursal'          => $fila->nombresucursal,
                'cliente'           => $fila->cliente_num_doc.'<br>'.$fila->cliente_razon_social,
                'condicionpago'     => $fila->cpago_nombre
            );

            if(!empty($fila->cpago_nombre)) {
                if(!isset($totales_condicion_pago[$fila->cpago_nombre])) {
                    $totales_condicion_pago[$fila->cpago_nombre] = round($fila->total - $fila->monto_adeudado, 2);
                } else {
                    $totales_condicion_pago[$fila->cpago_nombre] = $totales_condicion_pago[$fila->cpago_nombre] + round($fila->total - $fila->monto_adeudado, 2);
                }
            }
            
            if($fila->id_tipodoc_electronico == '01') {
                $num_docs['num_facturas'] = $num_docs['num_facturas'] + 1;
            } else if($fila->id_tipodoc_electronico == '03') {
                $num_docs['num_boletas'] = $num_docs['num_boletas'] + 1;
            } else if($fila->id_tipodoc_electronico == '07') {
                $num_docs['num_notas_credito'] = $num_docs['num_notas_credito'] + 1;
            } else if($fila->id_tipodoc_electronico == '08') {
                $num_docs['num_notas_debito'] = $num_docs['num_notas_debito'] + 1;
            } else if($fila->id_tipodoc_electronico == '77') {
                $num_docs['num_notas_venta'] = $num_docs['num_notas_venta'] + 1;
            }
        }

        $resp['respuesta'] = 'ok';
        $resp['lista'] = $lista;
        $resp['data_extra'] = array(
            'totales_condicion_pago' => $totales_condicion_pago,
            'numd_docs'             => $num_docs
        );
        return $resp;
    }

    public function get_lista_docs_detallado_detalle_caja($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales = array(), $ids_vendedores = array(), $id_tipodoc_electronico = '', $id_codigomoneda = '', $tipo_transaccion = '', $tipo_venta = 'contado') {

        $herramientas = new HerramientasController;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos la empresa';
            echo json_encode($resp);
            exit();
        }

        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

        $fecha_inicio_valor = empty($fecha_inicio)?'':$fecha_inicio;
        $fecha_fin_valor = empty($fecha_fin)?'':$fecha_fin;

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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
        
        $where_opcional = '';
              
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
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
                $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
                if(!$sucursal_bd) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Sucursal';
                    $resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
                    echo json_encode($resp);
                    exit();
                }
            }
        }

        if(!empty($id_tipodoc_electronico)) {
            $array_id_tipodocs = array('01', '03', '07', '08', '77');
            if(!in_array($id_tipodoc_electronico, $array_id_tipodocs)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo de Documento Seleccionado no Existe!';
                echo json_encode($resp);
                exit();
            }
        }

        if(!empty($id_codigomoneda)) {
            $array_monedas = array('PEN', 'USD');
            if(!in_array($id_codigomoneda, $array_monedas)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de moneda seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }
        }

        if(!empty($tipo_transaccion)) {
            $array_tipos_transaccion = array('contado', 'credito', 'tarjeta_credito', 'transferencia');
            if(!in_array($tipo_transaccion, $array_tipos_transaccion)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de transacción seleccionado!';
                echo json_encode($resp);
                exit();
            }
        }
        
        if(count($ids_vendedores) > 0){ $where_opcional = $where_opcional." and doc.id_vendedor in (".implode(",", $ids_vendedores).") "; }

		if(count($ids_sucursales) > 0){ $where_opcional = $where_opcional." and doc.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($id_tipodoc_electronico)) { $where_opcional_doc = " and doc.id_tipodoc_electronico = '".$id_tipodoc_electronico."' "; }

        if(!empty($id_codigomoneda)) { $where_opcional = $where_opcional." and doc.id_codigomoneda = '".$id_codigomoneda."' "; }

        if(!empty($tipo_transaccion)) { 
            if($tipo_transaccion == 'contado') {
                $where_opcional = $where_opcional." and (cp.tipo is NULL or cp.tipo = 'contado')  "; 
            } else {
                $where_opcional = $where_opcional." and cp.tipo = '$tipo_transaccion'  ";
            }
        }

        if(empty($tipo_transaccion) && empty($id_codigomoneda)) {
            
        } else {
            if($tipo_venta == 'contado') {
                $where_opcional = $where_opcional." and IFNULL(doc.monto_adeudado_inicial, 0) <= 0 ";
            } else {
                $where_opcional = $where_opcional." and IFNULL(doc.monto_adeudado_inicial, 0) > 0 ";
            }
        }
        
        if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
            $query = "
            SELECT doc.id_tipodoc_electronico, doc.serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, doc.total_isc, doc.total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, 

            detalle.id_unidad_medida detalle_id_unidad_medida, detalle.unidad_medida detalle_unidad_medida, detalle.cantidad detalle_cantidad, detalle.precio detalle_precio, detalle.sub_total detalle_sub_total, detalle.igv detalle_igv, detalle.isc detalle_isc, detalle.icbper detalle_icbper, detalle.importe detalle_importe, detalle.id_tipoafectacionigv detalle_id_tipoafectacionigv, detalle.id_producto detalle_id_producto, detalle.codigo_producto detalle_codigo_producto, detalle.descripcion detalle_descripcion, detalle.tipo_unidad detalle_tipo_unidad, detalle.id_presentacion detalle_id_presentacion, detalle.precio_sin_igv detalle_precio_sin_igv 

            FROM doc_electronico doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            INNER JOIN detalle_doc detalle on (doc.id_contribuyente = detalle.id_contribuyente and doc.id_tipodoc_electronico = detalle.id_tipodoc_electronico and doc.serie_comprobante = detalle.serie_comprobante and doc.numero_comprobante = detalle.numero_comprobante and doc.tipo_envio_sunat = detalle.tipo_envio_sunat) 


            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.tipo_envio_sunat = :tipo_envio_sunat and 
                doc.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                (doc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.cpe_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.cpe_serie_comprobante
                        AND doc.numero_comprobante = den.cpe_numero_comprobante
                        AND doc.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.nota_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.nota_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.nota_serie_comprobante
                        AND doc.numero_comprobante = den.nota_numero_comprobante
                        AND doc.tipo_envio_sunat = den.nota_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 
                 
                $where_opcional $where_opcional_doc 
            ";
            
        } else if($id_tipodoc_electronico == '77') {
            $query = "
            SELECT doc.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, 0 total_isc, 0 total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, 

            detalle.id_unidad_medida detalle_id_unidad_medida, detalle.unidad_medida detalle_unidad_medida, detalle.cantidad detalle_cantidad, detalle.precio detalle_precio, detalle.sub_total detalle_sub_total, detalle.igv detalle_igv, 0 detalle_isc, detalle.icbper detalle_icbper, detalle.importe detalle_importe, detalle.id_tipoafectacionigv detalle_id_tipoafectacionigv, detalle.id_producto detalle_id_producto, detalle.codigo_producto detalle_codigo_producto, detalle.descripcion detalle_descripcion, detalle.tipo_unidad detalle_tipo_unidad, detalle.id_presentacion detalle_id_presentacion, detalle.precio_sin_igv detalle_precio_sin_igv 

            FROM doc_no_oficial doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            INNER JOIN detalle_docnooficial detalle on (doc.id_contribuyente = detalle.id_contribuyente and doc.id_tipodocumento = detalle.id_tipodocumento and doc.numero_comprobante = detalle.numero_comprobante and doc.modalidad = detalle.modalidad) 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.modalidad = :tipo_envio_sunat and 
                (doc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) and 
                doc.estado_documento = 'activo' and 
                doc.id_tipodocumento = '77' and 
                doc.tipo = 'venta' 
                $where_opcional 
            ";
            
        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El Tipo de Documento Seleccionado no Existe!';
            echo json_encode($resp);
            exit();
        }
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio_time, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin_time, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo '==> Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }
        
        $num_docs['num_facturas'] = 0;
        $num_docs['num_boletas'] = 0;
        $num_docs['num_notas_credito'] = 0;
        $num_docs['num_notas_debito'] = 0;
        $num_docs['num_notas_venta'] = 0;
        $totales_condicion_pago = array();

        $lista_detalle = array();
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            
            if(isset($fila->detalle_id_presentacion) && intval($fila->detalle_id_presentacion) > 0) {
                $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($fila->detalle_id_presentacion))));
                $id_unidad_medida = $presentacion->idunidad;
                $nombre_unidad_medida = ($presentacion->nombre == '-')?'':$presentacion->nombre;
            } else {
                $nombre_unidad_medida = '';
                $id_unidad_medida = $fila->detalle_id_unidad_medida;
            }

            if(empty($nombre_unidad_medida)) {
                $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
                if($unidadmedida) {
                    $nombre_unidad_medida = $unidadmedida->nombre;
                }
            }
            
            $lista_detalle[] = array(
                'id_tipodoc'    => $fila->id_tipodoc_electronico,
                'serie_correlativo' => (!empty($fila->serie_comprobante))?$fila->serie_comprobante.'-'.$fila->numero_comprobante:$fila->numero_comprobante,
                'total_gravadas'    => $fila->total_gravadas,
                'total_inafecta'    => $fila->total_inafecta,
                'total_exoneradas'  => $fila->total_exoneradas,
                'total_gratuitas'   => $fila->total_gratuitas,
                'total_exportacion' => $fila->total_exportacion,
                'total_icbper'      => $fila->total_icbper,
                'total_descuento'   => $fila->total_descuento,
                'sub_total'         => $fila->sub_total,
                'total_igv'         => $fila->total_igv,
                'total_isc'         => $fila->total_isc,
                'total_otr_imp'     => $fila->total_otr_imp,
                'total'             => $fila->total,
                'deuda'             => $fila->monto_adeudado,
                'pagado'            => round($fila->total - $fila->monto_adeudado, 2),
                'moneda'            => ($fila->id_codigomoneda == 'PEN')?'SOLES':'DOLARES',
                'sucursal'          => $fila->nombresucursal,
                'cliente'           => $fila->cliente_num_doc.'<br>'.$fila->cliente_razon_social,
                'condicionpago'     => $fila->cpago_nombre,

                'producto_codigo'   => $fila->detalle_codigo_producto, 
                'producto_nombre'   => $fila->detalle_descripcion,
                'cantidad'          => $fila->detalle_cantidad + 0,
                'unidad_medida'     => $nombre_unidad_medida, 
                'precio_sin_igv'    => $fila->detalle_precio_sin_igv + 0,
                'item_igv'          => $fila->detalle_igv + 0,
                'item_isc'          => $fila->detalle_isc + 0, 
                'item_icbper'       => $fila->detalle_icbper + 0, 
                'item_importe'      => round($fila->detalle_importe + $fila->detalle_igv, 2) + 0
            );

            if(!empty($fila->cpago_nombre)) {
                if(!isset($totales_condicion_pago[$fila->cpago_nombre])) {
                    $totales_condicion_pago[$fila->cpago_nombre] = round($fila->total - $fila->monto_adeudado, 2);
                } else {
                    $totales_condicion_pago[$fila->cpago_nombre] = $totales_condicion_pago[$fila->cpago_nombre] + round($fila->total - $fila->monto_adeudado, 2);
                }
            }

            if($fila->id_tipodoc_electronico == '01') {
                $num_docs['num_facturas'] = $num_docs['num_facturas'] + 1;
            } else if($fila->id_tipodoc_electronico == '03') {
                $num_docs['num_boletas'] = $num_docs['num_boletas'] + 1;
            } else if($fila->id_tipodoc_electronico == '07') {
                $num_docs['num_notas_credito'] = $num_docs['num_notas_credito'] + 1;
            } else if($fila->id_tipodoc_electronico == '08') {
                $num_docs['num_notas_debito'] = $num_docs['num_notas_debito'] + 1;
            } else if($fila->id_tipodoc_electronico == '77') {
                $num_docs['num_notas_venta'] = $num_docs['num_notas_venta'] + 1;
            }
        }

        $resp['respuesta'] = 'ok';
        $resp['lista'] = $lista_detalle;
        $resp['data_extra'] = array(
            'totales_condicion_pago' => $totales_condicion_pago,
            'numd_docs'             => $num_docs
        );
        return $resp;
    }

    public function lista_detalleabonos_detalle_caja($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales = array(), $ids_vendedores = array(), $id_codigomoneda = '', $tipo_transaccion = '', $tipo_venta = 'credito', $tipo_reporte = 'todo') {

        $herramientas = new HerramientasController;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos la empresa';
            echo json_encode($resp);
            exit();
        }

        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

        $fecha_inicio_valor = empty($fecha_inicio)?'':$fecha_inicio;
        $fecha_fin_valor = empty($fecha_fin)?'':$fecha_fin;

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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

        
        $where_opcional = '';

        //$ids_vendedores = empty($ids_vendedores_string)?array():array_map('intval', explode(',', $ids_vendedores_string));        
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
                if(!$vendedor) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Vendedor';
                    $resp['mensaje'] = 'Uno de los vendedores seleccionados no está activo o no existe!';
                    return $resp;
                }
            }
        }

        //$ids_sucursales = empty($ids_sucursales_string)?array():array_map('intval', explode(',', $ids_sucursales_string));
        if (($key_sucursales = array_search(0, $ids_sucursales)) !== false) { unset($ids_sucursales[$key_sucursales]);}
        if(count($ids_sucursales) > 0) {
            foreach($ids_sucursales as $idsucursal) {
                $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
                if(!$sucursal_bd) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Sucursal';
                    $resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
                    return $resp;
                }
            }
        }

        if(!empty($id_codigomoneda)) {
            $array_monedas = array('PEN', 'USD');
            if(!in_array($id_codigomoneda, $array_monedas)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de moneda seleccionado no existe!';
                return $resp;
            }
        }

        if(!empty($tipo_transaccion)) {
            $array_tipos_transaccion = array('contado', 'credito', 'tarjeta_credito', 'transferencia');
            if(!in_array($tipo_transaccion, $array_tipos_transaccion)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de transacción seleccionado!';
                return $resp;
            }
        }
        
        if($tipo_reporte == 'todo') {
            if(count($ids_vendedores) > 0){ $where_opcional = $where_opcional." and doc.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        } else {
            if(count($ids_vendedores) > 0){ $where_opcional = $where_opcional." and mc.id_vendedor in (".implode(",", $ids_vendedores).") "; }
        }
        

		if(count($ids_sucursales) > 0){ $where_opcional = $where_opcional." and doc.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($id_codigomoneda)) { $where_opcional = $where_opcional." and doc.id_codigomoneda = '".$id_codigomoneda."' "; }

        if(!empty($tipo_transaccion)) { 
            if($tipo_venta == 'credito') {
                if($tipo_transaccion == 'contado') {
                    $where_opcional = $where_opcional." and (mc_cp.tipo  is NULL or mc_cp.tipo = 'contado')  "; 
                } else {
                    $where_opcional = $where_opcional." and mc_cp.tipo = '$tipo_transaccion'  ";
                }
            } else {
                if($tipo_transaccion == 'contado') {
                    $where_opcional = $where_opcional." and ((cp.tipo is NULL or cp.tipo = 'contado') or (mc_cp.tipo  is NULL or mc_cp.tipo = 'contado')) "; 
                } else {
                    $where_opcional = $where_opcional." and (cp.tipo = '$tipo_transaccion' or mc_cp.tipo = '$tipo_transaccion')  ";
                }
            }
        }

        if($tipo_venta == 'credito') {
            $where_opcional = $where_opcional." and IFNULL(doc.monto_adeudado_inicial, 0) > 0 ";
        } else {
            
        }
        
        $query = "
        SELECT * FROM ( 
            
            (
            SELECT doc.id_tipodoc_electronico, doc.serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, doc.total_isc, doc.total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, mc.id_montocobrado mc_id_montocobrado, mc.id_vendedor mc_id_vendedor, mc.id_condicionpago mc_idcondicionpago, mc.id_codigomoneda mc_id_codigomoneda, mc.total mc_total, mc.cpago_nrooperacion mc_cpago_nrooperacion, mc.fecha_registro mc_fecha_registro, mc.fechadeposito mc_fechadeposito, mc.idbanco mc_idbanco, mc.detalle mc_detalle, mc_cp.tipo  

            FROM doc_electronico doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            LEFT JOIN monto_cobrado mc ON (doc.id_contribuyente = mc.id_contribuyente and doc.id_tipodoc_electronico = mc.id_tipodoc_electronico and doc.serie_comprobante = mc.serie_comprobante and doc.numero_comprobante = mc.numero_comprobante and doc.tipo_envio_sunat = mc.tipo_envio_sunat and mc.estado = 'activo')  

            LEFT OUTER JOIN condiciondepago mc_cp on (mc.id_condicionpago = mc_cp.id_condicionpago and mc_cp.estado = 'activo') 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.tipo_envio_sunat = :tipo_envio_sunat and 
                doc.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                ((doc.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) or (mc.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME))) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.cpe_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.cpe_serie_comprobante
                        AND doc.numero_comprobante = den.cpe_numero_comprobante
                        AND doc.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.nota_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.nota_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.nota_serie_comprobante
                        AND doc.numero_comprobante = den.nota_numero_comprobante
                        AND doc.tipo_envio_sunat = den.nota_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 
                
                $where_opcional 
            )
        
        UNION ALL 

            (
            SELECT doc.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, 0 total_isc, 0 total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, mc.id_montocobrado mc_id_montocobrado, mc.id_vendedor mc_id_vendedor, mc.id_condicionpago mc_idcondicionpago, mc.id_codigomoneda mc_id_codigomoneda, mc.total mc_total, mc.cpago_nrooperacion mc_cpago_nrooperacion, mc.fecha_registro mc_fecha_registro, mc.fechadeposito mc_fechadeposito, mc.idbanco mc_idbanco, mc.detalle mc_detalle, mc_cp.tipo 

            FROM doc_no_oficial doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            LEFT JOIN monto_cobrado mc ON (doc.id_contribuyente = mc.id_contribuyente and doc.id_tipodocumento = mc.id_tipodoc_electronico and doc.numero_comprobante = mc.numero_comprobante and doc.modalidad = mc.tipo_envio_sunat and mc.estado = 'activo') 

            LEFT OUTER JOIN condiciondepago mc_cp on (mc.id_condicionpago = mc_cp.id_condicionpago and mc_cp.estado = 'activo') 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.modalidad = :tipo_envio_sunat and 
                ((doc.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) or (mc.fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME))) and 
                doc.estado_documento = 'activo' and 
                doc.id_tipodocumento = '77' and 
                doc.tipo = 'venta' 
                $where_opcional 
            )

        ) tbl_unida  ";

        //si se trata de abonos, debemos volver a filtrar la data porque caso contrario traerá los comprobantes donde la fecha de registro del comprobante coincida con el rango de fechas de la búsqueda, y además traerá los abonos en la fecha de búsqueda. Para poder solucionar este problema, debemos hacer un nuevo filtro a la tabla generada con el rango de fechas, pero el filtro se aplica únicamente cuando se trata de abonos.
        if($tipo_venta == 'credito') {
            $query = $query." where mc_fecha_registro BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME) ";
        }
        
        /*
        $query = str_replace(':fecha_inicio', "'$fecha_inicio_time'", $query);
        $query = str_replace(':fecha_fin', "'$fecha_fin_time'", $query);
        $query = str_replace(':id_contribuyente', "$id_contribuyente", $query);
        $query = str_replace(':tipo_envio_sunat', "'$tipo_envio_sunat'", $query);
        echo $query;
        exit();
        */
        
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio_time, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin_time, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }
        
        $totales_condicion_pago = array();
        $lista = array();
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            
            if(floatval($fila->monto_adeudado_inicial) <= 0) {
                $nombre_banco = '';
                if(!empty($fila->cpago_idbanco)) {
                    $banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $fila->cpago_idbanco)));
                    if($banco) {
                        $nombre_banco = $banco->nombre_banco;
                    }
                }

                $abono_fecha = !empty($fila->mc_fecha_registro)?date("d-m-Y", strtotime($fila->mc_fecha_registro)):'';
                $abono_condicion_pago = $fila->cpago_nombre;
                $abono_total = $fila->total;
                $abono_nro_operacion = $fila->cpago_nrooperacion;
                $abono_banco = $nombre_banco;
                $abono_detalle = '';
            } else {
                $condicion_pago_nombre = '';
                if(!empty($fila->mc_idcondicionpago)) {
                    $condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->mc_idcondicionpago)));
                    if($condicion_pago) {
                        $condicion_pago_nombre = $condicion_pago->condicionpago;
                    }
                }

                $nombre_banco = '';
                if(!empty($fila->mc_idbanco)) {
                    $banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $fila->mc_idbanco)));
                    if($banco) {
                        $nombre_banco = $banco->nombre_banco;
                    }
                }
                
                $abono_fecha = !empty($fila->mc_fecha_registro)?date("d-m-Y", strtotime($fila->mc_fecha_registro)):'';
                $abono_condicion_pago = $condicion_pago_nombre;
                $abono_total = $fila->mc_total;
                $abono_nro_operacion = $fila->mc_cpago_nrooperacion;
                $abono_banco = $nombre_banco;
                $abono_detalle = $fila->mc_detalle;
            }

            if(!empty($abono_condicion_pago)) {
                if(isset($totales_condicion_pago[$abono_condicion_pago])) {
                    $totales_condicion_pago[$abono_condicion_pago] = $totales_condicion_pago[$abono_condicion_pago] + $abono_total;
                } else {
                    $totales_condicion_pago[$abono_condicion_pago] = array();
                    $totales_condicion_pago[$abono_condicion_pago] = $abono_total;
                }
            }            

            $lista[] = array(
                'id_tipodoc'    => $fila->id_tipodoc_electronico,
                'serie_correlativo' => (!empty($fila->serie_comprobante))?$fila->serie_comprobante.'-'.$fila->numero_comprobante:$fila->numero_comprobante,
                'total_gravadas'    => $fila->total_gravadas,
                'total_inafecta'    => $fila->total_inafecta,
                'total_exoneradas'  => $fila->total_exoneradas,
                'total_gratuitas'   => $fila->total_gratuitas,
                'total_exportacion' => $fila->total_exportacion,
                'total_icbper'      => $fila->total_icbper,
                'total_descuento'   => $fila->total_descuento,
                'sub_total'         => $fila->sub_total,
                'total_igv'         => $fila->total_igv,
                'total_isc'         => $fila->total_isc,
                'total_otr_imp'     => $fila->total_otr_imp,
                'total'             => $fila->total,
                'deuda'             => $fila->monto_adeudado,
                'pagado'            => round($fila->total - $fila->monto_adeudado, 2),
                'moneda'            => ($fila->id_codigomoneda == 'PEN')?'SOLES':'DOLARES',
                'sucursal'          => $fila->nombresucursal,
                'cliente'           => $fila->cliente_num_doc.'<br>'.$fila->cliente_razon_social,
                'condicionpago'     => $fila->cpago_nombre, 

                'abono_fecha'           => $abono_fecha,
                'abono_condicion_pago'  => $abono_condicion_pago,
                'abono_total'           => $abono_total,
                'abono_nro_operacion'   => $abono_nro_operacion,
                'abono_banco'           => $abono_banco,
                'abono_detalle'         => $abono_detalle
            );


        }

        $resp['respuesta'] = 'ok';
        $resp['totales_condicion_pago'] = $totales_condicion_pago;
        $resp['lista'] = $lista;
        return $resp;

    }

    public function getHojaLiquidacionAction($id_codigomoneda, $fecha_inicio, $fecha_fin, $ids_vendedores_string, $ids_sucursales_string) {
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
        
        
        $data = $this->get_hoja_liquidacion($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales_string, $ids_vendedores_string, $id_codigomoneda, '', 'todo');
        if($data['respuesta'] == 'error') {
            echo json_encode($data);
            exit();
        }

        $lista_movimientos = $this->get_lista_movimientos($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales_string, $ids_vendedores_string, $id_codigomoneda);
        if($lista_movimientos['respuesta'] == 'error') {
            echo json_encode($lista_movimientos);
            exit();
        }

        $data['lista_movimientos'] = $lista_movimientos;

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin));

        $ids_vendedores = empty($ids_vendedores_string)?array():array_map('intval', explode(',', $ids_vendedores_string));
        $ids_sucursales = empty($ids_sucursales_string)?array():array_map('intval', explode(',', $ids_sucursales_string));
        
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

        $total_egreso = $this->get_monto_movimiento($fecha_inicio_time, $fecha_fin_time, $id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat, $id_codigomoneda, 'egreso');

        $total_ingreso = $this->get_monto_movimiento($fecha_inicio_time, $fecha_fin_time, $id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat, $id_codigomoneda, 'ingreso');

        $data_egreso = $this->get_monto_movimiento_by_condicionpago($fecha_inicio_time, $fecha_fin_time, $id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat, $id_codigomoneda, 'egreso');
        if($data_egreso['respuesta'] != 'ok') {
            echo json_encode($data_egreso);
            exit();
        }

        $data_ingreso = $this->get_monto_movimiento_by_condicionpago($fecha_inicio_time, $fecha_fin_time, $id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat, $id_codigomoneda, 'ingreso');
        if($data_ingreso['respuesta'] != 'ok') {
            echo json_encode($data_egreso);
            exit();
        }

        $data['totales_ingreso'] = $data_ingreso['totales'];
        $data['totales_egreso'] = $data_egreso['totales'];

        $data['caja_total_ingreso'] = $total_ingreso;
        $data['caja_total_egreso'] = $total_egreso;
        $data['id_codigomoneda'] = $id_codigomoneda;

        $data['anticipos'] = $this->get_total_anticipos($fecha_inicio_time, $fecha_fin_time, $id_contribuyente, $ids_sucursales, $ids_vendedores, $contribuyente->tipo_envio_sunat, $id_codigomoneda)['total_anticipos'];
        
        $html = $this->get_html_hoja_liquidacion($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_vendedores, $ids_sucursales, $data);
        /*echo $html;*/
        $html_header = '
		<!DOCTYPE html>
		<html>
			<body>
				<div class="text-center" style="height: 25px">
					
				</div>
			</body>
		</html>
		';
        $data_patrocinador = $this->get_parametros_iniciales();
		$img_empresa = 'https://'.$data_patrocinador['url_domain'].$data_patrocinador['logo_img_461'];
	
		$html_footer = '
		<!DOCTYPE html>
		<html>
			<style>
				.text-center{
					text-align: center;
				}
				.font-weight-bold{
					font-weight: bold;
				}
				.margin-0{
					margin: 0;
					padding: 0;
				}	
				p{
					font-size: 14px;
				}
			</style>
			<body class="margin-0">
				<div class="text-center">
					<img src="'.$img_empresa.'" width="150px" class="margin-0"><p class="text-uppercase margin-0" style="font-style: italic">Emitido por: <span class="font-weight-bold">'.$data_patrocinador['url_domain'].'</span></p>
				</div>
			</body>
		</html>
		';
        $html_header = '
		<!DOCTYPE html>
		<html>
			<body>
				<div class="text-center" style="height: 25px">
					
				</div>
			</body>
		</html>
		';
        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        header('Content-Type: application/pdf');
        if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
        $snappy->setOption('margin-left', '5mm');
        $snappy->setOption('margin-right', '5mm');
        $snappy->setOption('enable-local-file-access', true);
        echo $snappy->getOutputFromHtml($html,  array('header-html' => $html_header,'footer-html' => $html_footer));
        exit();
    }

    public function get_hoja_liquidacion($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales_string = '', $ids_vendedores_string = '', $id_codigomoneda = '', $tipo_transaccion = '', $tipo_venta = 'credito') {

        $herramientas = new HerramientasController;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos la empresa';
            return $resp;
        }

        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

        $fecha_inicio_valor = empty($fecha_inicio)?'':$fecha_inicio;
        $fecha_fin_valor = empty($fecha_fin)?'':$fecha_fin;

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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

        
        $where_opcional = '';

        $ids_vendedores = empty($ids_vendedores_string)?array():array_map('intval', explode(',', $ids_vendedores_string));        
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
                if(!$vendedor) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Vendedor';
                    $resp['mensaje'] = 'Uno de los vendedores seleccionados no está activo o no existe!';
                    return $resp;
                }
            }
        }

        $ids_sucursales = empty($ids_sucursales_string)?array():array_map('intval', explode(',', $ids_sucursales_string));
        if (($key_sucursales = array_search(0, $ids_sucursales)) !== false) { unset($ids_sucursales[$key_sucursales]);}
        if(count($ids_sucursales) > 0) {
            foreach($ids_sucursales as $idsucursal) {
                $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
                if(!$sucursal_bd) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Sucursal';
                    $resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
                    return $resp;
                }
            }
        }

        /*
        if(!empty($id_tipodoc_electronico)) {
            $array_id_tipodocs = array('01', '03', '07', '08', '77');
            if(!in_array($id_tipodoc_electronico, $array_id_tipodocs)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo de Documento Seleccionado no Existe!';
                echo json_encode($resp);
                exit();
            }
        }
        */

        if(!empty($id_codigomoneda)) {
            $array_monedas = array('PEN', 'USD');
            if(!in_array($id_codigomoneda, $array_monedas)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de moneda seleccionado no existe!';
                return $resp;
            }
        }

        if(!empty($tipo_transaccion)) {
            $array_tipos_transaccion = array('contado', 'credito', 'tarjeta_credito', 'transferencia');
            if(!in_array($tipo_transaccion, $array_tipos_transaccion)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el tipo de transacción seleccionado!';
                return $resp;
            }
        }
        
        $where_opcional1 = '';
        $where_opcional2 = '';
        if(count($ids_vendedores) > 0){ 
            $where_opcional1 = $where_opcional1." and doc.id_vendedor in (".implode(",", $ids_vendedores).") "; 

            $where_opcional2 = $where_opcional2." and mc.id_vendedor in (".implode(",", $ids_vendedores).") "; 
        }

		if(count($ids_sucursales) > 0){ $where_opcional = $where_opcional." and doc.id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($id_codigomoneda)) { $where_opcional = $where_opcional." and doc.id_codigomoneda = '".$id_codigomoneda."' "; }

        if(!empty($tipo_transaccion)) { 
            if($tipo_venta == 'credito') {
                if($tipo_transaccion == 'contado') {
                    $where_opcional = $where_opcional." and (mc_cp.tipo  is NULL or mc_cp.tipo = 'contado')  "; 
                } else {
                    $where_opcional = $where_opcional." and mc_cp.tipo = '$tipo_transaccion'  ";
                }
            } else {
                if($tipo_transaccion == 'contado') {
                    $where_opcional = $where_opcional." and ((cp.tipo is NULL or cp.tipo = 'contado') or (mc_cp.tipo  is NULL or mc_cp.tipo = 'contado')) "; 
                } else {
                    $where_opcional = $where_opcional." and (cp.tipo = '$tipo_transaccion' or mc_cp.tipo = '$tipo_transaccion')  ";
                }
            }
        }

        if($tipo_venta == 'credito') {
            $where_opcional = $where_opcional." and IFNULL(doc.monto_adeudado_inicial, 0) > 0 ";
        } else {
            
        }
        
        
        //INICIALMENTE SOLO SE TENÍAN LAS DOS PRIMERAS CONSULTAS, LUEGO SE AGREGARON DOS MÁS, PORQUE NO APARECÍAN LOS BONOS REGISTRADOS POR OTRA PERSONA DIFERENTE AL VENDEDOR INICIAL.
        //POR ESO SE AGREGÓ $where_opcional1 Y $where_opcional2
        $query = "
        SELECT * FROM ( 
            
            (
            SELECT doc.id_tipodoc_electronico, doc.serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, doc.total_isc, doc.total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, mc.id_montocobrado mc_id_montocobrado, mc.id_vendedor mc_id_vendedor, mc.id_condicionpago mc_idcondicionpago, mc.id_codigomoneda mc_id_codigomoneda, mc.total mc_total, mc.cpago_nrooperacion mc_cpago_nrooperacion, mc.fecha_registro mc_fecha_registro, mc.fechadeposito mc_fechadeposito, mc.idbanco mc_idbanco, mc.detalle mc_detalle, mc_cp.tipo mc_cp_tipo 

            FROM doc_electronico doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            LEFT JOIN monto_cobrado mc ON (doc.id_contribuyente = mc.id_contribuyente and doc.id_tipodoc_electronico = mc.id_tipodoc_electronico and doc.serie_comprobante = mc.serie_comprobante and doc.numero_comprobante = mc.numero_comprobante and doc.tipo_envio_sunat = mc.tipo_envio_sunat and mc.estado = 'activo')  

            LEFT OUTER JOIN condiciondepago mc_cp on (mc.id_condicionpago = mc_cp.id_condicionpago and mc_cp.estado = 'activo') 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.tipo_envio_sunat = :tipo_envio_sunat and 
                doc.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                ((doc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) or (mc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin)) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.cpe_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.cpe_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.cpe_serie_comprobante
                        AND doc.numero_comprobante = den.cpe_numero_comprobante
                        AND doc.tipo_envio_sunat = den.cpe_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 

                AND NOT EXISTS (
                    SELECT 1
                    FROM doc_electronicoxnota den
                    WHERE doc.id_contribuyente = den.nota_id_contribuyente
                        AND doc.id_tipodoc_electronico = den.nota_id_tipodoc_electronico
                        AND doc.serie_comprobante = den.nota_serie_comprobante
                        AND doc.numero_comprobante = den.nota_numero_comprobante
                        AND doc.tipo_envio_sunat = den.nota_tipo_envio_sunat
                        AND den.nota_id_cod_tipomotivo_credito = '01' 
                        AND den.nota_estado_envio_sunat <> 'rechazado' AND den.nota_estado_envio_sunat <> 'anulado' 
                ) 
                
                $where_opcional1 $where_opcional 
            )
        
        UNION ALL 

            (
            SELECT doc.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, 0 total_isc, 0 total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, mc.id_montocobrado mc_id_montocobrado, mc.id_vendedor mc_id_vendedor, mc.id_condicionpago mc_idcondicionpago, mc.id_codigomoneda mc_id_codigomoneda, mc.total mc_total, mc.cpago_nrooperacion mc_cpago_nrooperacion, mc.fecha_registro mc_fecha_registro, mc.fechadeposito mc_fechadeposito, mc.idbanco mc_idbanco, mc.detalle mc_detalle, mc_cp.tipo mc_cp_tipo 

            FROM doc_no_oficial doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            LEFT JOIN monto_cobrado mc ON (doc.id_contribuyente = mc.id_contribuyente and doc.id_tipodocumento = mc.id_tipodoc_electronico and doc.numero_comprobante = mc.numero_comprobante and doc.modalidad = mc.tipo_envio_sunat) 

            LEFT OUTER JOIN condiciondepago mc_cp on (mc.id_condicionpago = mc_cp.id_condicionpago and mc_cp.estado = 'activo') 

            WHERE 
                doc.id_contribuyente = :id_contribuyente and 
                doc.modalidad = :tipo_envio_sunat and 
                ((doc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin) or (mc.fecha_registro BETWEEN :fecha_inicio and :fecha_fin)) and 
                doc.estado_documento = 'activo' and 
                doc.id_tipodocumento = '77' 
                $where_opcional1 $where_opcional
            )

        ) tbl_unida WHERE ((mc_fecha_registro BETWEEN :fecha_inicio and :fecha_fin) or mc_fecha_registro is null) 
        ";
        

        /*
        $query = str_replace(':fecha_inicio', "'$fecha_inicio_time'", $query);
        $query = str_replace(':fecha_fin', "'$fecha_fin_time'", $query);
        $query = str_replace(':id_contribuyente', "$id_contribuyente", $query);
        $query = str_replace(':tipo_envio_sunat', "'$tipo_envio_sunat'", $query);
        echo $query;
        exit();
        */
        

        /*
        $query = "
        SELECT * FROM ( 
            
            (
            SELECT doc.id_tipodoc_electronico, doc.serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, doc.total_isc, doc.total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, mc.id_montocobrado mc_id_montocobrado, mc.id_vendedor mc_id_vendedor, mc.id_condicionpago mc_idcondicionpago, mc.id_codigomoneda mc_id_codigomoneda, mc.total mc_total, mc.cpago_nrooperacion mc_cpago_nrooperacion, mc.fecha_registro mc_fecha_registro, mc.fechadeposito mc_fechadeposito, mc.idbanco mc_idbanco, mc.detalle mc_detalle, mc_cp.tipo mc_cp_tipo 

            FROM doc_electronico doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            LEFT JOIN monto_cobrado mc ON (doc.id_contribuyente = mc.id_contribuyente and doc.id_tipodoc_electronico = mc.id_tipodoc_electronico and doc.serie_comprobante = mc.serie_comprobante and doc.numero_comprobante = mc.numero_comprobante and doc.tipo_envio_sunat = mc.tipo_envio_sunat)  

            LEFT OUTER JOIN condiciondepago mc_cp on (mc.id_condicionpago = mc_cp.id_condicionpago and mc_cp.estado = 'activo') 

            WHERE 
                doc.id_contribuyente = $id_contribuyente and 
                doc.tipo_envio_sunat = '$tipo_envio_sunat' and 
                doc.estado_envio_sunat in ('aceptado', 'pendiente', 'ticket') and 
                (doc.fecha_registro BETWEEN CAST('$fecha_inicio_time' AS DATETIME) and CAST('$fecha_fin_time' AS DATETIME)) 
                
                $where_opcional 
            )
        
        UNION ALL 

            (
            SELECT doc.id_tipodocumento id_tipodoc_electronico, '' serie_comprobante, doc.numero_comprobante, doc.total_gravadas, doc.total_inafecta, doc.total_exoneradas, doc.total_gratuitas, doc.total_exportacion, doc.total_icbper, doc.total_descuento, doc.sub_total, doc.total_igv, 0 total_isc, 0 total_otr_imp, doc.total, doc.fecha_comprobante, doc.id_codigomoneda, doc.idcliente, doc.id_vendedor, doc.id_sucursal, doc.fecha_registro, IFNULL(doc.monto_adeudado_inicial, 0) monto_adeudado_inicial, IFNULL(doc.monto_adeudado, 0) monto_adeudado, doc.id_condicionpago, doc.cpago_nrooperacion, doc.cpago_fechadeposito, doc.cpago_idbanco, clie.id_tipodocidentidad, clie.num_doc cliente_num_doc, clie.razon_social cliente_razon_social,  IFNULL(cp.tipo, 'contado') cpago_tipo, IFNULL(cp.condicionpago, 'Contado') cpago_nombre, suc.codigo codigosucursal, suc.nombre nombresucursal, mc.id_montocobrado mc_id_montocobrado, mc.id_vendedor mc_id_vendedor, mc.id_condicionpago mc_idcondicionpago, mc.id_codigomoneda mc_id_codigomoneda, mc.total mc_total, mc.cpago_nrooperacion mc_cpago_nrooperacion, mc.fecha_registro mc_fecha_registro, mc.fechadeposito mc_fechadeposito, mc.idbanco mc_idbanco, mc.detalle mc_detalle, mc_cp.tipo mc_cp_tipo 

            FROM doc_no_oficial doc INNER JOIN cliente clie ON doc.idcliente = clie.idcliente LEFT JOIN condiciondepago cp ON doc.id_condicionpago = cp.id_condicionpago INNER JOIN sucursal suc ON doc.id_sucursal = suc.idsucursal 

            LEFT JOIN monto_cobrado mc ON (doc.id_contribuyente = mc.id_contribuyente and doc.id_tipodocumento = mc.id_tipodoc_electronico and doc.numero_comprobante = mc.numero_comprobante and doc.modalidad = mc.tipo_envio_sunat) 

            LEFT OUTER JOIN condiciondepago mc_cp on (mc.id_condicionpago = mc_cp.id_condicionpago and mc_cp.estado = 'activo') 

            WHERE 
                doc.id_contribuyente = $id_contribuyente and 
                doc.modalidad = '$tipo_envio_sunat' and 
                (doc.fecha_registro BETWEEN CAST('$fecha_inicio_time' AS DATETIME) and CAST('$fecha_fin_time' AS DATETIME)) and 
                doc.estado_documento = 'activo' and 
                doc.id_tipodocumento = '77' 
                $where_opcional 
            )

        ) tbl_unida
        ";

        echo $query;
        exit();
        */
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio_time, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin_time, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }

        //Monto documentos pagados al contado
        $cp_tipo_docs_pagados['contado'] = 0; 
        $cp_tipo_docs_pagados['transferencia'] = 0;
        $cp_tipo_docs_pagados['tarjeta_credito'] = 0; 

        $item_detalle_docs_pagados['contado'] = array();
        $item_detalle_docs_pagados['transferencia'] = array();
        $item_detalle_docs_pagados['tarjeta_credito'] = array();
        
         //Monto Pagado de documentos marcados como crédito
        $cp_tipo_docs_credito['contado'] = 0;
        $cp_tipo_docs_credito['transferencia'] = 0;
        $cp_tipo_docs_credito['tarjeta_credito'] = 0; 

        $item_detalle_docs_credito['contado'] = array();
        $item_detalle_docs_credito['transferencia'] = array();
        $item_detalle_docs_credito['tarjeta_credito'] = array();

        $totales_condicion_pago = array();

        $totales_cpago_docs_contado = array();
        $totales_cpago_abonos = array();
        
        $lista = array();
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;

            if(floatval($fila->monto_adeudado_inicial) <= 0) {
                $nombre_banco = '';
                if(!empty($fila->cpago_idbanco)) {
                    $banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $fila->cpago_idbanco)));
                    if($banco) {
                        $nombre_banco = $banco->nombre_banco;
                    }
                }

                $abono_fecha = !empty($fila->mc_fecha_registro)?date("d-m-Y", strtotime($fila->mc_fecha_registro)):'';
                $abono_condicion_pago = $fila->cpago_nombre;
                $abono_total = $fila->total;
                $abono_nro_operacion = $fila->cpago_nrooperacion;
                $abono_banco = $nombre_banco;
                $abono_detalle = '';

                if(!empty($abono_condicion_pago)) {
                    if(isset($totales_cpago_docs_contado[$abono_condicion_pago])) {
                        if($fila->id_tipodoc_electronico == '07') {
                            $totales_cpago_docs_contado[$abono_condicion_pago] = $totales_cpago_docs_contado[$abono_condicion_pago] - $abono_total;
                        } else {
                            $totales_cpago_docs_contado[$abono_condicion_pago] = $totales_cpago_docs_contado[$abono_condicion_pago] + $abono_total;
                        }
                    } else {
                        if($fila->id_tipodoc_electronico == '07') {
                            $totales_cpago_docs_contado[$abono_condicion_pago] = (-1)*$abono_total;
                        } else {
                            $totales_cpago_docs_contado[$abono_condicion_pago] = $abono_total;
                        }
                    }
                }
            } else {
                $condicion_pago_nombre = '';
                if(!empty($fila->mc_idcondicionpago)) {
                    $condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->mc_idcondicionpago)));
                    if($condicion_pago) {
                        $condicion_pago_nombre = $condicion_pago->condicionpago;
                    }
                }

                $nombre_banco = '';
                if(!empty($fila->mc_idbanco)) {
                    $banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $fila->mc_idbanco)));
                    if($banco) {
                        $nombre_banco = $banco->nombre_banco;
                    }
                }
                
                $abono_fecha = !empty($fila->mc_fecha_registro)?date("d-m-Y", strtotime($fila->mc_fecha_registro)):'';
                $abono_condicion_pago = $condicion_pago_nombre;
                $abono_total = $fila->mc_total;
                $abono_nro_operacion = $fila->mc_cpago_nrooperacion;
                $abono_banco = $nombre_banco;
                $abono_detalle = $fila->mc_detalle;

                if(!empty($abono_condicion_pago)) {
                    if(isset($totales_cpago_abonos[$abono_condicion_pago])) {
                        if($fila->id_tipodoc_electronico == '07') {
                            $totales_cpago_abonos[$abono_condicion_pago] = $totales_cpago_abonos[$abono_condicion_pago] - $abono_total;
                        } else {
                            $totales_cpago_abonos[$abono_condicion_pago] = $totales_cpago_abonos[$abono_condicion_pago] + $abono_total;
                        }
                    } else {
                        if($fila->id_tipodoc_electronico == '07') {
                            $totales_cpago_abonos[$abono_condicion_pago] = (-1)*$abono_total;
                        } else {
                            $totales_cpago_abonos[$abono_condicion_pago] = $abono_total;
                        }
                    }
                }
            }

            //LAS NOTAS DE CRÉDITO DISMINUYEN DE VALOR: AQUÍ SE DEBERÍA CONSIDERAR EL MOTIVO DE LA NOTA

            if(!empty($abono_condicion_pago)) {
                if(isset($totales_condicion_pago[$abono_condicion_pago])) {
                    if($fila->id_tipodoc_electronico == '07') {
                        $totales_condicion_pago[$abono_condicion_pago] = $totales_condicion_pago[$abono_condicion_pago] - $abono_total;
                    } else {
                        $totales_condicion_pago[$abono_condicion_pago] = $totales_condicion_pago[$abono_condicion_pago] + $abono_total;
                    }
                } else {
                    if($fila->id_tipodoc_electronico == '07') {
                        $totales_condicion_pago[$abono_condicion_pago] = (-1)*$abono_total;
                    } else {
                        $totales_condicion_pago[$abono_condicion_pago] = $abono_total;
                    }
                }
            }            

            $lista[] = array(
                'id_tipodoc'    => $fila->id_tipodoc_electronico,
                'serie_correlativo' => (!empty($fila->serie_comprobante))?$fila->serie_comprobante.'-'.$fila->numero_comprobante:$fila->numero_comprobante,
                'total_gravadas'    => $fila->total_gravadas,
                'total_inafecta'    => $fila->total_inafecta,
                'total_exoneradas'  => $fila->total_exoneradas,
                'total_gratuitas'   => $fila->total_gratuitas,
                'total_exportacion' => $fila->total_exportacion,
                'total_icbper'      => $fila->total_icbper,
                'total_descuento'   => $fila->total_descuento,
                'sub_total'         => $fila->sub_total,
                'total_igv'         => $fila->total_igv,
                'total_isc'         => $fila->total_isc,
                'total_otr_imp'     => $fila->total_otr_imp,
                'total'             => $fila->total,
                'deuda'             => $fila->monto_adeudado,
                'pagado'            => round($fila->total - $fila->monto_adeudado, 2),
                'moneda'            => ($fila->id_codigomoneda == 'PEN')?'SOLES':'DOLARES',
                'sucursal'          => $fila->nombresucursal,
                'cliente'           => $fila->cliente_num_doc.'<br>'.$fila->cliente_razon_social,
                'condicionpago'     => $fila->cpago_nombre, 

                'abono_fecha'           => $abono_fecha,
                'abono_condicion_pago'  => $abono_condicion_pago,
                'abono_total'           => $abono_total,
                'abono_nro_operacion'   => $abono_nro_operacion,
                'abono_banco'           => $abono_banco,
                'abono_detalle'         => $abono_detalle
            );
            
            //aquí ingresan todos los comprobantes que fueron pagados al contado
            //puede ser en efectivo, tarjeta_credito o transferencia.
            //EXTRACCIÓN DE LISTA DETALLE
            if(floatval($fila->monto_adeudado_inicial) <= 0) {
                //Monto documentos pagados al contado
                if($fila->id_tipodoc_electronico == '07') {
                    $cp_tipo_docs_pagados[$fila->cpago_tipo] = ($cp_tipo_docs_pagados[$fila->cpago_tipo] ?? 0) - $fila->total;
                } else {
                    $cp_tipo_docs_pagados[$fila->cpago_tipo] = ($cp_tipo_docs_pagados[$fila->cpago_tipo] ?? 0) + $fila->total;
                }
                
                if($fila->id_tipodoc_electronico == '77') {
                    $query_detalle = "SELECT detalle.id_presentacion, detalle.id_unidad_medida, detalle.importe, detalle.igv, detalle.codigo_producto, detalle.descripcion, detalle.cantidad, prod.stock FROM detalle_docnooficial detalle INNER JOIN producto prod ON detalle.id_producto = prod.idproducto WHERE detalle.id_contribuyente = $id_contribuyente and detalle.id_tipodocumento = '$fila->id_tipodoc_electronico' and detalle.numero_comprobante = $fila->numero_comprobante and detalle.modalidad = '$tipo_envio_sunat';";
                } else {
                    $query_detalle = "SELECT detalle.id_presentacion, detalle.id_unidad_medida, detalle.importe, detalle.igv, detalle.codigo_producto, detalle.descripcion, detalle.cantidad, prod.stock FROM detalle_doc detalle INNER JOIN producto prod ON detalle.id_producto = prod.idproducto WHERE detalle.id_contribuyente = $id_contribuyente and detalle.id_tipodoc_electronico = '$fila->id_tipodoc_electronico' and detalle.serie_comprobante = '$fila->serie_comprobante' and detalle.numero_comprobante = $fila->numero_comprobante and detalle.tipo_envio_sunat = '$tipo_envio_sunat';";
                }
                
                try {
                    $sentencia_detalle = $this->db->prepare($query_detalle);
                    $sentencia_detalle->execute();
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $e->getMessage();
                    return $resp;
                }

                while ($item_detalle = $sentencia_detalle->fetch()) {
                    $item_detalle = (object)$item_detalle;

                    if(isset($item_detalle->id_presentacion) && intval($item_detalle->id_presentacion) > 0) {
                        $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item_detalle->id_presentacion))));
                        $id_unidad_medida = $presentacion->idunidad;
                        $nombre_unidad_medida = ($presentacion->nombre == '-')?'':$presentacion->nombre;
                    } else {
                        $nombre_unidad_medida = '';
                        $id_unidad_medida = $item_detalle->id_unidad_medida;
                    }
        
                    if(empty($nombre_unidad_medida)) {
                        $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
                        if($unidadmedida) {
                            $nombre_unidad_medida = $unidadmedida->nombre;
                        }
                    }

                    $detalle_importe = round($item_detalle->importe + $item_detalle->igv, 2) + 0;

                    $item_detalle_docs_pagados[$fila->cpago_tipo][] = array(
                        'documento'         => (!empty($fila->serie_comprobante))?$fila->serie_comprobante.'-'.$fila->numero_comprobante:$fila->numero_comprobante,
                        'cliente'           => $fila->cliente_razon_social, 
                        'cod_producto'      => $item_detalle->codigo_producto,
                        'nombre_producto'   => $item_detalle->descripcion,
                        'unidad_medida'     => $nombre_unidad_medida,
                        'cantidad'          => $item_detalle->cantidad, 
                        'precio_unitario'   => round($detalle_importe/$item_detalle->cantidad, 2),
                        'importe'           => $detalle_importe,
                        'stock_producto'    => $item_detalle->stock + 0
                    );
                }
            }

            //Aquí ingresan todos los comprobantes que fueron marcados como al crédito en algún momento
            //El crédito puede ser parcial o total, si es parcial seguramente tienen algunos abonos.
            //EXTRACCIÓN DE LISTA DETALLE
            if(floatval($fila->monto_adeudado_inicial) > 0) {
                //Monto Pagado de documentos marcados como crédito

                $condicion_pago_nombre = 'contado';
                $condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and tipo = 'contado' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

                if(!empty($fila->mc_idcondicionpago)) {
                    $condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", 'bind' => array('id_condicionpago' => $fila->mc_idcondicionpago)));
                    if($condicion_pago) {
                        $condicion_pago_nombre = $condicion_pago->condicionpago;
                    }
                }

                $cp_tipo_docs_credito[$condicion_pago->tipo] = $cp_tipo_docs_credito[$condicion_pago->tipo] + $abono_total;
                
                if($abono_total > 0) {
                    $item_detalle_docs_credito[$condicion_pago->tipo][] = array(
                        'documento'         => (!empty($fila->serie_comprobante))?$fila->serie_comprobante.'-'.$fila->numero_comprobante:$fila->numero_comprobante,
                        'cliente'           => $fila->cliente_razon_social,
                        'importe'           => $abono_total
                    );
                }
            }  
        }

        $resp['respuesta'] = 'ok';
        $resp['lista'] = $lista;
        
        $resp['cp_tipo_docs_pagados'] = $cp_tipo_docs_pagados;
        $resp['item_detalle_docs_pagados'] = $item_detalle_docs_pagados;
        $resp['cp_tipo_docs_credito'] = $cp_tipo_docs_credito;
        $resp['item_detalle_docs_credito'] = $item_detalle_docs_credito;
        $resp['totales_condicion_pago'] = $totales_condicion_pago;
        $resp['totales_cpago_docs_contado'] = $totales_cpago_docs_contado;
        $resp['totales_cpago_abonos'] = $totales_cpago_abonos;
        return $resp;

    }

    public function get_lista_movimientos($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_sucursales_string = '', $ids_vendedores_string = '', $id_codigomoneda = '') {
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            return $resp;
        }

        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
        $ids_vendedores = empty($ids_vendedores_string)?array():array_map('intval', explode(',', $ids_vendedores_string));
        $ids_sucursales = empty($ids_sucursales_string)?array():array_map('intval', explode(',', $ids_sucursales_string));
        $fecha_inicio_valor = empty($fecha_inicio)?date("Y-m-d 00:00:00"):$fecha_inicio;
        $fecha_fin_valor = empty($fecha_fin)?date("Y-m-d H:i:s"):$fecha_fin;
        
        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
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
                $sucursal_bd = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
                if(!$sucursal_bd) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Sucursal';
                    $resp['mensaje'] = 'Una de las sucursales seleccionadas no existe!';
                    return $resp;
                }
            }
        }

        if(!empty($id_codigomoneda)) {
            $id_codigomoneda = ($id_codigomoneda == 'PEN')?'PEN':'USD';
        }

        $where_extra = '';
        if(count($ids_vendedores) > 0){ $where_extra = $where_extra." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $where_extra = $where_extra." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
        if(!empty($id_codigomoneda)) { $where_extra = $where_extra." and moneda = '$id_codigomoneda' "; }

        $query = "SELECT * FROM movimientocaja where id_contribuyente = :id_contribuyente and (fecha_movimiento BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) and tipo_envio_sunat = :tipo_envio_sunat and estado = 'activo' ".$where_extra;
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio_time, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin_time, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada (get_lista_movimientos): ',  $e->getMessage(), "\n";
            exit();
        }
        
        $lista = array();
        $lista_ingresos = array();
        $lista_egresos = array();
        while ($item = $sentencia->fetch()) {
            $item = (object)$item;

            $lista[] = array(
                'fecha_movimiento'  => date("d/m/Y H:i:s", strtotime($item->fecha_movimiento)),
                'descripcion'       => $item->descripcion,
                'tipo'              => $item->tipo_movimiento,
                'id_codigomoneda'   => $item->moneda,
                'monto'   => $item->monto
            );

            if($item->tipo_movimiento == 'ingreso') {
                $lista_ingresos[] = array(
                    'fecha_movimiento'  => date("d/m/Y H:i:s", strtotime($item->fecha_movimiento)),
                    'descripcion'       => $item->descripcion,
                    'tipo'              => $item->tipo_movimiento,
                    'id_codigomoneda'   => $item->moneda,
                    'monto'   => $item->monto
                );
            } else {
                $lista_egresos[] = array(
                    'fecha_movimiento'  => date("d/m/Y H:i:s", strtotime($item->fecha_movimiento)),
                    'descripcion'       => $item->descripcion,
                    'tipo'              => $item->tipo_movimiento,
                    'id_codigomoneda'   => $item->moneda,
                    'monto'   => $item->monto
                );
            }
        }
        
        $resp['respuesta'] = 'ok';
        $resp['lista'] = $lista;
        $resp['lista_ingresos'] = $lista_ingresos;
        $resp['lista_egresos'] = $lista_egresos;
        return $resp;
    }

    public function get_html_hoja_liquidacion($id_contribuyente, $fecha_inicio, $fecha_fin, $ids_vendedores, $ids_sucursales, $data) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No encontramos la empresa';
            echo json_encode($resp);
            exit();
        }
        $ruta_base = $this->ruta_base_public_html;

        if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 80px; height: 80px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
        $img_logo = $ruta_base.$img_logo;

        $simbolo_moneda = '';
        $nombre_moneda = '';
        if($data['id_codigomoneda'] == 'PEN') {
            $simbolo_moneda = 'S/ ';
            $nombre_moneda = 'SOLES';
        } else {
            $simbolo_moneda = 'USD ';
            $nombre_moneda = 'DÓLARES';
        }

        $fecha_inicio_time = date("Y-m-d H:i:s", strtotime($fecha_inicio));
        $fecha_fin_time = date("Y-m-d H:i:s", strtotime($fecha_fin));
        $texto_fecha = '';
        if($fecha_inicio_time == $fecha_fin_time) {
            $texto_fecha = date("d-m-Y / H:i A", strtotime($fecha_inicio_time));
        } else {
            $fecha_inicio_cast = date("Y-m-d", strtotime($fecha_inicio));
            $fecha_fin_cast = date("Y-m-d", strtotime($fecha_fin));

            if($fecha_inicio_cast == $fecha_fin_cast) {
                $texto_fecha = $fecha_inicio_cast." Desde ".date("H:i A", strtotime($fecha_inicio_time))." Hasta ".date("H:i A", strtotime($fecha_fin_time));
            } else {
                $texto_fecha = date("d-m-Y / H:i A", strtotime($fecha_inicio_time))." - ".date("d-m-Y / H:i A", strtotime($fecha_fin_time));
            }
        }

        $texto_vendedores = '';
        $lista_vendedores = array();
        //$ids_vendedores = empty($ids_vendedores)?array():array_map('intval', explode(',', $ids_vendedores));        
        if (($key_vendedores = array_search(0, $ids_vendedores)) !== false) { unset($ids_vendedores[$key_vendedores]); }
        if(count($ids_vendedores) > 0) {
            foreach($ids_vendedores as $id_vendedor) {
                $vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('idusuario' => $id_vendedor, 'id_contribuyente' => $id_contribuyente)));
                if($vendedor) {
                    $lista_vendedores[] = $vendedor->nombre.' '.$vendedor->apellido." (Id. $vendedor->idusuario)";
                }
            }

            if(count($lista_vendedores) == 1) {
                $texto_vendedores = 'VENDEDOR: '.$lista_vendedores[0];
            } else {
                $texto_vendedores = 'VENDEDORES: '.implode(", ", $lista_vendedores);
            }

        } else {
            $texto_vendedores = 'VENDEDORES: Todos';
        }

        $texto_sucursales = '';
        $lista_sucursales = array();
        //$ids_sucursales = empty($ids_sucursales)?array():array_map('intval', explode(',', $ids_sucursales));        
        if (($key_vendedores = array_search(0, $ids_sucursales)) !== false) { unset($ids_sucursales[$key_vendedores]); }
        if(count($ids_sucursales) > 0) {
            foreach($ids_sucursales as $id_sucursal) {
                $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $id_contribuyente)));
                if($sucursal) {
                    $lista_sucursales[] = $sucursal->nombre." (Id. $sucursal->idsucursal)";
                }
            }

            if(count($lista_sucursales) == 1) {
                $texto_sucursales = 'SUCURSAL: '.$lista_sucursales[0];
            } else {
                $texto_sucursales = 'SUCURSALES: '.implode(", ", $lista_sucursales);
            }

        } else {
            $texto_sucursales = 'SUCURSALES: Todas';
        }

        $n = 0;
        $subtotal_docs_contado = 0;
        $subtotal_docs_transferencia = 0;
        $subtotal_docs_tarjeta_credito = 0;
        $subtotal_docs_abonos = 0;
        $subtotal_ingresos_caja_chica = 0;
        $subtotal_egresos_caja_chica = 0;

        $html = '
        <html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://facturalaya.com/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>Hoja de Liquidación</title>
		</head>
		
        <body>
        <style>
            body{
                font-size: 12px;
                color: #788993;
                background: #f2f4f5;
                padding: 10px 2em;
            }
            .col-2 {
                width: 16.66666667%;
                float: left;
                padding: 0;
            }
            .col-3{
                width: 25%;
                float: left;
                padding: 0;
            }
            .col-4{
                width: 33.333333333333%;
                float: left;
                padding: 0;
            }
            .col-5 {
                width: 41.66666667%;
                float: left;
                padding: 0;
            }
            .col-6 {
                width: 50%;
                float: left;
                padding: 0;
            }
            .col-7 {
                width: 55%;
                float: left;
                padding: 0;
            }
            .col-12 {
                width: 100%;
                float: left;
                padding: 0;
            }
            /* Class body */
            .table-bordered td, .table-bordered th {
                border: none;
            }
            .box-head p{
               margin: 0;
            }
            .padding-top-40{
                padding-top: 40px!important;
            }
            .table{
                border: none;
                font-size: 12px;
                background: #fff;
            }
           
            .table thead th {
                vertical-align: middle;
                border-bottom: 2px solid #e5e5e5;
                text-align: center;

            }
            .table-bordered th {
                border-top: 1px solid #e5e5e5;
            }
            .table-bordered td {
                border-top: 1px solid #e5e5e5;
                padding: 1rem 0;
            }
            .bg-head-table{
                background: #e5e5e5;
                color: #6a7a8c;
            }
            .table-2 {
                width: 100%;
                font-size: 12px;
                border: 1px solid #e5e5e5!important;
            }
            .table-2 td {
                border-bottom: 1px solid #e5e5e5!important;
                padding: 5px;
            }
        </style>
            <div class="text-center">
                <img src="'.$img_logo.'" class="mb-3" '.$style_logo.' alt="">
            </div>
            <div class="w-100  main-header">
                <div class="col-6 box-head">
                    <p class="text-uppercase">'.$contribuyente->razon_social.'</p>
                    <p>'.$contribuyente->direccion_fiscal.'</p>
                </div>
                <div class="col-6 text-right">
                    <p>
                    Fecha: '.date('d-m-Y / H:i A').'<br>
                    Moneda: '.$nombre_moneda.'
                    </p>
                </div>
                <div class="col-12">
                    <p class="mt-4 text-center mb-0">LIQUIDACION '.$texto_fecha.'</p>
                </div>
                <div class="col-6">
                    '.$texto_vendedores.'
                </div>
                <div class="col-6  text-right">
                    '.$texto_sucursales.'
                </div>
            </div>
            <div class="content-table mt-5">
                <table class="table table-bordered">
                    <thead class="bg-head-table">
                        <tr class="text-uppercase">
                            <th width="60px">Item</th>
                            <th width="120px">Serie-Número</th>
                            <th width="200px">Cliente</th>
                            <th width="120px">Código</th>
                            <th width="200px">Descripción</th>
                            <th width="120px">Cantidad</th>
                            <th width="120px">Importe</th>
                            <th width="120px">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase pl-3">** INGRESO EFECTIVO:</td>
                        </tr>';
                        foreach($data['item_detalle_docs_pagados']['contado'] as $item_docs_efectivo) {
                            $item_docs_efectivo = (object)$item_docs_efectivo;
                            if($item_docs_efectivo->importe > 0) {
                                $n++;
                                $html = $html."
                                <tr>
                                    <td class='pl-3'>$n</td>
                                    <td>$item_docs_efectivo->documento</td>
                                    <td>$item_docs_efectivo->cliente</td>
                                    <td class='text-center'>$item_docs_efectivo->cod_producto</td>
                                    <td>$item_docs_efectivo->nombre_producto</td>
                                    <td class='text-center'>".($item_docs_efectivo->cantidad + 0)."</td>
                                    <td class='text-center'>$item_docs_efectivo->importe</td>
                                    <td class='text-center'>$item_docs_efectivo->stock_producto</td>
                                </tr>
                                ";
                                $subtotal_docs_contado = $subtotal_docs_contado + $item_docs_efectivo->importe;
                            }
                        }
                    $html = $html.'
                        <tr class="text-uppercase bg-head-table">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Sub-total</th>
                            <th></th>
                            <th>'.$simbolo_moneda.$data['cp_tipo_docs_pagados']['contado'].'</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase pl-3">** INGRESO Transferencias:</td>
                        </tr>';
                        foreach($data['item_detalle_docs_pagados']['transferencia'] as $item_docs_transferencia) {
                            $item_docs_transferencia = (object)$item_docs_transferencia;
                            if($item_docs_transferencia->importe > 0) {
                                $n++;
                                $html = $html."
                                <tr>
                                    <td class='pl-3'>$n</td>
                                    <td>$item_docs_transferencia->documento</td>
                                    <td>$item_docs_transferencia->cliente</td>
                                    <td  class='text-center'>$item_docs_transferencia->cod_producto</td>
                                    <td>$item_docs_transferencia->nombre_producto</td>
                                    <td  class='text-center'>".($item_docs_transferencia->cantidad + 0)."</td>
                                    <td class='text-center'>$item_docs_transferencia->importe</td>
                                    <td  class='text-center'>$item_docs_transferencia->stock_producto</td>
                                </tr>
                                ";
                                $subtotal_docs_transferencia = $subtotal_docs_transferencia + $item_docs_transferencia->importe;
                            }
                        }
                    
                    $html = $html.'
                        <tr class="text-uppercase bg-head-table">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Sub-total</th>
                            <th></th>
                            <th>'.$simbolo_moneda.$data['cp_tipo_docs_pagados']['transferencia'].'</th>
                            <th></th>
                        </tr>
                        <tr>
                        <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase pl-3">** INGRESO Tarjetas:</td>
                    </tr>';

                    foreach($data['item_detalle_docs_pagados']['tarjeta_credito'] as $item_docs_tarjeta_credito) {
                        $item_docs_tarjeta_credito = (object)$item_docs_tarjeta_credito;
                        if($item_docs_tarjeta_credito->importe > 0) {
                            $n++;
                            $html = $html."
                            <tr>
                                <td class='pl-3'>$n</td>
                                <td>$item_docs_tarjeta_credito->documento</td>
                                <td>$item_docs_tarjeta_credito->cliente</td>
                                <td class='text-center'>$item_docs_tarjeta_credito->cod_producto</td>
                                <td>$item_docs_tarjeta_credito->nombre_producto</td>
                                <td  class='text-center'>".($item_docs_tarjeta_credito->cantidad + 0)."</td>
                                <td  class='text-center'>$item_docs_tarjeta_credito->importe</td>
                                <td  class='text-center'>$item_docs_tarjeta_credito->stock_producto</td>
                            </tr>
                            ";
                            $subtotal_docs_tarjeta_credito = $subtotal_docs_tarjeta_credito + $item_docs_tarjeta_credito->importe;
                        }
                    }
                
                    $html = $html.'
                        <tr class="text-uppercase bg-head-table">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Sub-total</th>
                            <th></th>
                            <th>'.$simbolo_moneda.$data['cp_tipo_docs_pagados']['tarjeta_credito'].'</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase pl-3">** ABONOS PAGADOS AL CONTADO:</td>
                        </tr>';

                        foreach($data['item_detalle_docs_credito']['contado'] as $item_abonos_contado) {
                            $n++;
                            $item_abonos_contado = (object)$item_abonos_contado;
                            $html = $html."
                            <tr>
                                <td class='pl-3'>$n</td>
                                <td>$item_abonos_contado->documento</td>
                                <td colspan='3'>$item_abonos_contado->cliente</td>
                                <td> </td>
                                <td  class='text-center'>$item_abonos_contado->importe</td>
                                <td></td>
                            </tr>
                            ";
                            $subtotal_docs_abonos = $subtotal_docs_abonos + $item_abonos_contado->importe;
                        }

                        $html = $html.'
                            <tr class="text-uppercase bg-head-table">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Sub-total</th>
                                <th></th>
                                <th>'.$simbolo_moneda.$data['cp_tipo_docs_credito']['contado'].'</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase pl-3">** ABONOS PAGADOS CON TARJETA DE CRÉDITO/DÉBITO:</td>
                            </tr>';

                        foreach($data['item_detalle_docs_credito']['tarjeta_credito'] as $item_abonos_tarjeta_credito) {
                            $n++;
                            $item_abonos_tarjeta_credito = (object)$item_abonos_tarjeta_credito;
                            $html = $html."
                            <tr>
                                <td class='pl-3'>$n</td>
                                <td>$item_abonos_tarjeta_credito->documento</td>
                                <td colspan='3'>$item_abonos_tarjeta_credito->cliente</td>
                                <td> </td>
                                <td  class='text-center'>$item_abonos_tarjeta_credito->importe</td>
                                <td></td>
                            </tr>
                            ";
                            $subtotal_docs_abonos = $subtotal_docs_abonos + $item_abonos_tarjeta_credito->importe;
                        }

                        $html = $html.'
                            <tr class="text-uppercase bg-head-table">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Sub-total</th>
                                <th></th>
                                <th>'.$simbolo_moneda.$data['cp_tipo_docs_credito']['tarjeta_credito'].'</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase pl-3">** ABONOS PAGADOS CON TRANSFERENCIA:</td>
                            </tr>';
                        
                        foreach($data['item_detalle_docs_credito']['transferencia'] as $item_abonos_transferencia) {
                            $n++;
                            $item_abonos_transferencia = (object)$item_abonos_transferencia;
                            $html = $html."
                            <tr>
                                <td class='pl-3'>$n</td>
                                <td>$item_abonos_transferencia->documento</td>
                                <td colspan='3'>$item_abonos_transferencia->cliente</td>
                                <td> </td>
                                <td  class='text-center'>$item_abonos_transferencia->importe</td>
                                <td></td>
                            </tr>
                            ";
                            $subtotal_docs_abonos = $subtotal_docs_abonos + $item_abonos_transferencia->importe;
                        }

                        $html = $html.'
                            <tr class="text-uppercase bg-head-table">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Sub-total</th>
                                <th></th>
                                <th>'.$simbolo_moneda.$data['cp_tipo_docs_credito']['transferencia'].'</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="8" class="font-weight-bold pt-2 pb-2 pl-3 text-uppercase">** LISTA INGRESOS CAJA CHICA:</td>
                            </tr>';
                            
                        foreach($data['lista_movimientos']['lista_ingresos'] as $ingreso_caja_chica) {
                            $n++;
                            $ingreso_caja_chica = (object)$ingreso_caja_chica;
                            $html = $html."
                            <tr>
                                <td class='pl-3'>$n</td>
                                <td colspan='4'>$ingreso_caja_chica->descripcion</td>
                                <td> </td>
                                <td  class='text-center'>$ingreso_caja_chica->monto</td>
                                <td></td>
                            </tr>
                            ";
                            $subtotal_ingresos_caja_chica = $subtotal_ingresos_caja_chica + $ingreso_caja_chica->monto;
                        }
                        
                        $html = $html.'
                            <tr class="text-uppercase bg-head-table">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Sub-total</th>
                                <th></th>
                                <th>'.$simbolo_moneda.$subtotal_ingresos_caja_chica.'</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="8" class="font-weight-bold pt-2 pb-2 pl-3 text-uppercase">** LISTA EGRESOS CAJA CHICA:</td>
                            </tr>';

                        foreach($data['lista_movimientos']['lista_egresos'] as $egreso_caja_chica) {
                            $n++;
                            $egreso_caja_chica = (object)$egreso_caja_chica;
                            $html = $html."
                            <tr>
                                <td class='pl-3'>$n</td>
                                <td colspan='4'>$egreso_caja_chica->descripcion</td>
                                <td> </td>
                                <td  class='text-center'>$egreso_caja_chica->monto</td>
                                <td></td>
                            </tr>
                            ";
                            $subtotal_egresos_caja_chica = $subtotal_egresos_caja_chica + $egreso_caja_chica->monto;
                        }

                        $html = $html.'
                            <tr class="text-uppercase bg-head-table">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Sub-total</th>
                                <th></th>
                                <th>'.$simbolo_moneda.$subtotal_egresos_caja_chica.'</th>
                                <th></th>
                            </tr>';

                        $lista_cpago_contado = '';
                        foreach($data['totales_cpago_docs_contado'] as $cpago_nombre_docs_contado => $cpado_docs_contado) {
                            $lista_cpago_contado = $lista_cpago_contado.$cpago_nombre_docs_contado.': '.$cpado_docs_contado.' ';
                        }

                        $lista_cpago_contado = 'Totales por Condición de Pago de Documento Pagados en su Totalidad: <br>'.$lista_cpago_contado;

                        $lista_cpago_nombre_abonos = '';
                        foreach($data['totales_cpago_abonos'] as $cpago_nombre_abonos => $cpago_abonos) {
                            $lista_cpago_nombre_abonos = $lista_cpago_nombre_abonos.$cpago_nombre_abonos.': '.$cpago_abonos.' ';
                        }

                        $lista_cpago_nombre_abonos = 'Totales por Condición de Pago de Abonos: <br>'.$lista_cpago_nombre_abonos;

                        $html = $html.'
                        <tr>
                            <td colspan="8" class="pl-3">
                            '.$lista_cpago_contado.'
                            <br /><br><br />
                            '.$lista_cpago_nombre_abonos.'
                            <td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="3" class="p-0">
                                
                                <table class="table-2 text-uppercase" style="margin-top: 60px">
                                    <tr>
                                        <td width="200px">Total Ingresos Ventas</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.($data['cp_tipo_docs_pagados']['contado'] + $data['cp_tipo_docs_pagados']['transferencia'] + $data['cp_tipo_docs_pagados']['tarjeta_credito']).'</td>
                                    </tr>

                                    <tr>
                                        <td width="200px">Total Ingresos Abonos</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.($data['cp_tipo_docs_credito']['contado'] + $data['cp_tipo_docs_credito']['transferencia'] + $data['cp_tipo_docs_credito']['tarjeta_credito']).'</td>
                                    </tr>

                                    <tr>
                                        <td width="200px">Total Ingresos Caja</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.($data['caja_total_ingreso']).'</td>
                                    </tr>
                                </table>

                                <table class="table-2 mt-3 text-uppercase">
                                    <tr class="mt-2 mb-2">
                                        <td width="200px">Total Egresos Caja</td>
                                        <td></td>
                                        <td><strong class="text-danger">-</strong> '.$simbolo_moneda.($data['caja_total_egreso']).'</td>
                                    </tr>
                                </table>

                                <table class="table-2 mt-3 text-uppercase">
                                    <tr class="mt-2 mb-2">
                                        <td width="200px">Total Anticipos</td>
                                        <td></td>
                                        <td><strong class="text-danger">-</strong> '.$simbolo_moneda.($data['anticipos']['total']).'</td>
                                    </tr>
                                </table>

                                <table class="table-2 mt-3 text-uppercase">
                                    <tr class="bg-head-table mt-2 mb-2">
                                        <td width="200px">Neto</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.round($data['cp_tipo_docs_pagados']['contado'] + $data['cp_tipo_docs_pagados']['transferencia'] + $data['cp_tipo_docs_pagados']['tarjeta_credito'] + $data['cp_tipo_docs_credito']['contado'] + $data['cp_tipo_docs_credito']['transferencia'] + $data['cp_tipo_docs_credito']['tarjeta_credito'] + $data['caja_total_ingreso'] - $data['caja_total_egreso'] - $data['anticipos']['total'], 2).'</td>
                                    </tr>
                                </table>

                                <table class="table-2 mt-3 text-uppercase">
                                    <tr>
                                        <td width="200px">Efectivo</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.round($data['cp_tipo_docs_pagados']['contado'] + $data['cp_tipo_docs_credito']['contado'] + $data['totales_ingreso']['contado'] - floatval($data['totales_egreso']['contado']), 2).'</td>
                                    </tr>
                                    <tr>
                                        <td width="200px">Transferencia</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.round($data['cp_tipo_docs_pagados']['transferencia'] + $data['cp_tipo_docs_credito']['transferencia'] + $data['totales_ingreso']['transferencia'] - floatval($data['totales_egreso']['transferencia']), 2).'</td>
                                    </tr>

                                    <tr>
                                        <td width="200px">Tarjetas</td>
                                        <td></td>
                                        <td>'.$simbolo_moneda.round($data['cp_tipo_docs_pagados']['tarjeta_credito'] + $data['cp_tipo_docs_credito']['tarjeta_credito'] + $data['totales_ingreso']['tarjeta_credito'] - floatval($data['totales_egreso']['tarjeta_credito']), 2).'</td>
                                    </tr>
                                </table>
                            </td>
                            
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </body>
        </html>
        ';

        /*
        $data['totales_ingreso'] = $data_ingreso['totales'];
        $data['totales_egreso'] = $data_egreso['totales'];
        */

        return $html;
    }

    public function plantilla_cajachicaAction(){

        $this->view->disable();
        
        $html = '
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            <link rel="stylesheet" href="https://facturalaya.com/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
            <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
            <title>Hoja de Liquidación</title>
        </head>
        
        <body>
        <style>
            body{
                font-size: 14px;
                color: #000;
            }
            .col-2 {
                width: 16.66666667%;
                float: left;
                padding: 0;
            }
            .col-3{
                width: 25%;
                float: left;
                padding: 0;
            }
            .col-4{
                width: 33.333333333333%;
                float: left;
                padding: 0;
            }
            .col-5 {
                width: 41.66666667%;
                float: left;
                padding: 0;
            }
            .col-6 {
                width: 50%;
                float: left;
                padding: 0;
            }
            .col-7 {
                width: 55%;
                float: left;
                padding: 0;
            }
            .col-12 {
                width: 100%;
                float: left;
                padding: 0;
            }
            /* Class body */
            .box-head p{
               margin: 0;
            }
            .padding-top-40{
                padding-top: 40px!important;
            }
            .table{
                border: none;
                font-size: 14px;
            }
            .table td, .table th {
                padding: 5px;
            }
            .table thead th {
                vertical-align: middle;
                border-bottom: 2px solid #000;
                text-align: center;
        
            }
            .table-bordered th {
                border: 1px solid #000;
            }
            .table-bordered td {
                border: none;
            }
            .bg-head-table{
                background: yellow;
            }
            .table-2 {
                width: 100%;
                font-size: 14px;
            }
            .table-2 td {
                border: 1px solid #000!important;
            }
        </style>
            <div class="w-100 padding-top-40 main-header">
                <div class="col-6 box-head">
                    <p class="text-uppercase">CORPORACION CAM MOTORS SAC </p>
                    <p>Av. Pachacutec 3327 Villa Maria del Triunfo</p>
                </div>
                <div class="col-6 text-right">
                    <p>Fecha: 05/03/2021</p>
                </div>
                <div class="col-12">
                    <p class="mt-4 text-center mb-0">LIQUIDACION 25-Febrero 2021</p>
                    <p>Vendedor: Jessica Ochoa</p>
                </div>
            </div>
            <div class="content-table">
                <table class="table table-bordered">
                    <thead class="bg-head-table">
                        <tr class="text-uppercase">
                            <th width="60px">Item</th>
                            <th width="120px">Serie-Número</th>
                            <th width="200px">Cliente</th>
                            <th width="120px">Código</th>
                            <th width="200px">Descripción</th>
                            <th width="120px">Cantidad</th>
                            <th width="120px">Precio</th>
                            <th width="120px">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase">** INGRESO EFECTIVO:</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>B11-001</td>
                            <td>Juan Diego</td>
                            <td>9645236</td>
                            <td>Bomba de agua</td>
                            <td>1</td>
                            <td>150.00</td>
                            <td>120</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>B11-001</td>
                            <td>Juan Diego</td>
                            <td>9645236</td>
                            <td>Bomba de agua</td>
                            <td>1</td>
                            <td>150.00</td>
                            <td>120</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>B11-001</td>
                            <td>Juan Diego</td>
                            <td>9645236</td>
                            <td>Bomba de agua</td>
                            <td>1</td>
                            <td>150.00</td>
                            <td>120</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <thead>
                        <tr class="text-uppercase">
                        <th width="60px"></th>
                        <th width="120px"></th>
                        <th width="200px"></th>
                        <th width="120px"></th>
                        <th width="200px">Sub-total</th>
                        <th width="120px"></th>
                        <th width="120px">450.000</th>
                        <th width="120px"></th>
                        </tr>
                    </thead>
                </table>
        
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase">** INGRESO Transferencias:</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>B11-001</td>
                            <td>Juan Diego</td>
                            <td>9645236</td>
                            <td>Bomba de agua</td>
                            <td>1</td>
                            <td>150.00</td>
                            <td>120</td>
                        </tr>
                    </tbody>
                </table>
        
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-uppercase">
                        <th width="60px"></th>
                        <th width="120px"></th>
                        <th width="200px"></th>
                        <th width="120px"></th>
                        <th width="200px">Sub-total</th>
                        <th width="120px"></th>
                        <th width="120px">450.000</th>
                        <th width="120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase">** INGRESO Tarjetas:</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>B11-001</td>
                            <td>Juan Diego</td>
                            <td>9645236</td>
                            <td>Bomba de agua</td>
                            <td>1</td>
                            <td>150.00</td>
                            <td>120</td>
                        </tr>
                    </tbody>
                </table>
        
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-uppercase">
                        <th width="60px"></th>
                        <th width="120px"></th>
                        <th width="200px"></th>
                        <th width="120px"></th>
                        <th width="200px">Sub-total</th>
                        <th width="120px"></th>
                        <th width="120px">450.000</th>
                        <th width="120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="font-weight-bold pt-2 pb-2 text-uppercase">** INGRESO Egresos:</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>B11-001</td>
                            <td>Juan Diego</td>
                            <td>9645236</td>
                            <td>Bomba de agua</td>
                            <td>1</td>
                            <td>150.00</td>
                            <td>120</td>
                        </tr>
                    </tbody>
                </table>
        
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-uppercase">
                        <th width="60px"></th>
                        <th width="120px"></th>
                        <th width="200px"></th>
                        <th width="120px"></th>
                        <th width="200px">Sub-total</th>
                        <th width="120px"></th>
                        <th width="120px">98.000</th>
                        <th width="120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="3" class="p-0">
                                
                                <table class="table-2 text-uppercase" style="margin-top: 60px">
                                    <tr>
                                        <td width="200px">Total Ingresos</td>
                                        <td></td>
                                        <td>00.00</td>
                                    </tr>
                                   
                                    <tr>
                                        <td width="200px">Total Ingresos</td>
                                        <td></td>
                                        <td>00.00</td>
                                    </tr>
                                </table>
        
                                <table class="table-2 mt-3 text-uppercase">
                                    <tr class="bg-head-table mt-2 mb-2">
                                        <td width="200px">Neto</td>
                                        <td></td>
                                        <td>00.00</td>
                                    </tr>
                                </table>
        
                                <table class="table-2 mt-3 text-uppercase">
                                    <tr>
                                        <td width="200px">Efectivo</td>
                                        <td></td>
                                        <td>00.00</td>
                                    </tr>
                                    <tr>
                                        <td width="200px">Transferencia</td>
                                        <td></td>
                                        <td>00.00</td>
                                    </tr>
        
                                    <tr>
                                        <td width="200px">Tarjetas</td>
                                        <td></td>
                                        <td>00.00</td>
                                    </tr>
                                </table>
                            </td>
                            
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </body>
        </html>
        ';
        /* echo $html;*/
         $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        header('Content-Type: application/pdf');
        if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
        $snappy->setOption('margin-left', '5mm');
        $snappy->setOption('margin-right', '5mm');
        $snappy->setOption('enable-local-file-access', true);
        echo $snappy->getOutputFromHtml($html);
        exit();
    }

    public function get_id_proveedor($datapost) {
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			return $resp;
		}

		//no hace falta hacer una validación de tipos, porque anteriormente se debió utilizar la función: validaciones_iniciales
		$id_tipo_doc_proveedor = trim($datapost['proveedor_tipo_docidentidad']);

		$numero_doc_proveedor = $this->limpia_espacios($datapost['proveedor_numerodocumento']);

		$proveedor = Proveedor::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('num_doc' => $numero_doc_proveedor, 'id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocidentidad' => $id_tipo_doc_proveedor)));
		if($proveedor) {
			$resp['respuesta'] = 'ok';
			$resp['id_proveedor'] = $proveedor->id_proveedor;
			return $resp;
		}
		
		$id_proveedor = !isset($datapost['id_proveedor_documento'])?0:intval($datapost['id_proveedor_documento']) + 0;
		if($id_proveedor > 0) {
			$proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor: and num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_proveedor' => $id_proveedor, 'num_doc' => $numero_doc_proveedor, 'id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocidentidad' => $id_tipo_doc_proveedor)));
			if($proveedor) {
				$resp['respuesta'] = 'ok';
				$resp['id_proveedor'] = $proveedor->id_proveedor;
				return $resp;
			}	
		}
		
		$proveedor_nombre = trim($datapost['proveedor_nombre']);
		if(empty($proveedor_nombre)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Proveedor';
			$resp['mensaje'] = 'Debes Ingresar la Razón Social de tu Proveedor!';
			return $resp;
		}

		$proveedor_direccion = '';

		$codigo_ubigeo = !isset($datapost['select_codigoubigeo'])?'':$datapost['select_codigoubigeo'];
		if($codigo_ubigeo != '') {
			$tipo_documento_identidad = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $codigo_ubigeo)));
			if(!$codigo_ubigeo) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Usted ha seleccionado un código de ubigeo inválido!';
				return $resp;
			}
		}

		$proveedor = new Proveedor();
		$proveedor->id_contribuyente = $usuario->id_contribuyente;
		$proveedor->id_tipodocidentidad = $id_tipo_doc_proveedor;
		$proveedor->codigo = uniqid('PROV_'.$usuario->id_contribuyente,true);;
		$proveedor->num_doc = $numero_doc_proveedor;
		$proveedor->razon_social = $proveedor_nombre;
		$proveedor->direccion_fiscal = $proveedor_direccion;
		$proveedor->id_cod_ubigeo = $codigo_ubigeo;
		$proveedor->fecha_registro = date('Y-m-d H:i:s');
		$proveedor->estado = 'activo';

        try {
            if(!$proveedor->save()) {
                $msg = '';
                foreach ($proveedor->getMessages() as $message) {
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
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }

		$resp['respuesta'] = 'ok';
		$resp['id_proveedor'] = $proveedor->id_proveedor;
		return $resp;
	}

    public function limpia_espacios($cadena){
		$cadena = trim($cadena);
		$cadena = preg_replace('[\s+]',"", $cadena);
		$cadena2 = '';
		for ($i=0; $i<strlen($cadena); $i++) {
			if (is_numeric($cadena[$i])) {$cadena2.=$cadena[$i];}
		}
		return $cadena2;
	}

    public function imprimirTicketMovimientoAction() {
        $file = $_GET['file'];
        $herramientas = new HerramientasController;
		$cadena_decifrada = $herramientas->desencriptar($file);
        $array_data = explode('||', $cadena_decifrada);
        $id_movimiento = !isset($array_data[0])?'':$array_data[0];
		$id_contribuyente = !isset($array_data[1])?'':$array_data[1];
		$tipo_envio_sunat = !isset($array_data[2])?'':$array_data[2];

        $resp_html = $this->get_html_ticket_movimiento($id_movimiento, $id_contribuyente, $tipo_envio_sunat);
        $nombre_archivo = 'Recibo-de-Caja-'.$id_movimiento.'.pdf';
        if($resp_html['respuesta'] == 'error') {
            echo "no existe";
            exit();
        }
        
        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$nombre_archivo.'"');
        if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
        $snappy->setOption('margin-left', '1mm');
        $snappy->setOption('margin-right', '1mm');
        $snappy->setOption('margin-top', '2mm');
        $snappy->setOption('margin-bottom', '5mm');
        $snappy->setOption('enable-local-file-access', true);

        echo $snappy->getOutputFromHtml($resp_html['html'], array('page-height' =>  150,'page-width' => 78));
        exit();
    }

    public function get_html_ticket_movimiento($id_movimiento, $id_contribuyente, $tipo_envio_sunat) {
        
		$data_patrocinador = $this->get_parametros_iniciales();
        $movimientocaja = Movimientocaja::findFirst(array("id_movimiento = :id_movimiento: and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_movimiento' => $id_movimiento, 'id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if(!$movimientocaja) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No Existe el Movimiento de Caja';
			return $resp;
        }

        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $movimientocaja->id_sucursal, 'id_contribuyente' => $id_contribuyente)));
        
		$herramientas = new HerramientasController;
		$ruta_base = $this->ruta_base_public_html;

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No Existe el Contribuyente';
			return $resp;
		}

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 90px; height: 90px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 160px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;
        
        $nombre_condicion_pago = '';
        if(!empty($movimientocaja->id_condicionpago)) {
            $condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $movimientocaja->id_condicionpago)));
            $nombre_condicion_pago = '<p><span class="font-weight-bold">Tipo Transacción: </span> '.$condicion_pago->condicionpago.'</p>';
        }

		//Tipo de moneda
		if($movimientocaja->moneda == 'PEN') {
            $simbolo_moneda = 'S/';
        } else {
            $simbolo_moneda = 'USD';
        }

        $vendedor_nombre = '';
        $vendedor_id = '';
        $vendedor_email = '';
        //Usuario - Quien regista el movimiento
        $vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $movimientocaja->id_vendedor)));
        if($vendedor) {
            $vendedor_nombre = $vendedor->nombre.' '.$vendedor->apellido;
            $vendedor_id = $vendedor->idusuario;
            $vendedor_email = $vendedor->email;
        }
        
        //Proveedor || Responsable
        $nombre_responsable = '';
        $tipo_doc_responsable = '';
        $numero_documento = '';
        $html_responsable = '';

        if(!empty($movimientocaja->id_proveedor)) {
            $responsable = Proveedor::findFirst(array("id_proveedor = :id_proveedor:", 'bind' => array('id_proveedor' => $movimientocaja->id_proveedor)));
            $nombre_responsable = $responsable->razon_social;
            $numero_documento = $responsable->num_doc;
            if($responsable->id_tipodocidentidad == '1') {
                $tipo_doc_responsable = 'D.N.I.';
            } else if($responsable->id_tipodocidentidad == '6') {
                $tipo_doc_responsable = 'R.U.C.';
            } else {
                $tipo_doc_responsable = 'OtroDoc.';
            }

            $html_responsable = '<p><span class="font-weight-bold">Proveedor | Responsable: '.$tipo_doc_responsable.'</span> '.$numero_documento.' - '.$nombre_responsable.'</p>';
        }


        
        $documento_relacionado = '';
        
        if(!empty($movimientocaja->tipo_comprobante) && !empty($movimientocaja->serie_num_comprobante)) {
            $tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $movimientocaja->tipo_comprobante)));
            if($tipo_documento) {
                $documento_relacionado = $tipo_documento->descripcion.' '.$movimientocaja->serie_num_comprobante;
                $documento_relacionado = '<p><span class="font-weight-bold">Documento: </span>'.$documento_relacionado.'</p>';
            }
        }

        $detalle_documento = empty($movimientocaja->detalle)?'':'<p><span class="font-weight-bold">Detalle | Nota: </span>'.$movimientocaja->detalle.'</p>';

        $nombre_tipo_movimiento = ($movimientocaja->tipo_movimiento == 'ingreso')?'RECIBO DE CAJA - INGRESO':'RECIBO DE CAJA - EGRESO';
        
		$img_empresa = 'https://'.$data_patrocinador['url_domain'].$data_patrocinador['logo_img_461'];
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://facturalaya.com/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$nombre_tipo_movimiento.'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				color: #000;
				font-size: 11px;
			}
			.line-title{
				display: inline-block;
				width: 80px;
			}
			p{
				margin: 0;
				padding: 0;
				font-size: 11px;
			}
			table{
				width: 100%;
				font-size: 11px!important;
			}
			th{
				text-align: center;
				padding: 8px;
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
			}
			td{
				padding: 3px;
			}
			.tb_resumen_totales{
				width: 60%;
				margin:  5px;
				font-size: 11px!important;
			
			}
			.tb_resumen_totales td, .tb_resumen_totales th{
				border: none!important;
				padding: 0px;
			}
			.codigo_qr_ticket p{
				font-size: 11px;
			}
			.masthead p{
				font-size: 11px;
			}
			.table-main-head th{
				font-weight: 200;
			}
			/* tamaño de letra */
			.texto_12 {
				font-size: 11px !important;
			}

			.texto_14_bold {
				font-size: 11px !important;
				font-weight: bold !important;
			}

			.texto_11 {
				font-size: 11px !important;
			}

			.texto_10 {
				font-size: 10px !important;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 11px;
				font-style: normal;
				margin-left: 20px;
			}
		</style>
		<body>
			<div class="masthead text-center">
				<img class="text-center" '.$style_logo.' src="'.$img_logo.'" />
				<p class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</p>
				<p>R.U.C.: '.$contribuyente->ruc.'</p>
				<p>'.$sucursal->direccion.' '.$sucursal->urbanizacion.'</p>
				<p><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" class="mr-2"/>Telf.: '.$sucursal->telefono.'</p>
				<p><i class="flaticon-envelope mr-2 texto_10"></i>Email: '.$sucursal->email.'</p>';
				if(!empty($sucursal->sitio_web)) {
					$html = $html.'<p>Website:'.$sucursal->sitio_web.'</p>';
				}
				$html = $html.'
				
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			</div>

            <div class="text-center">
                <p class="font-weight-bold text-uppercase">'.$nombre_tipo_movimiento.'<p>
                <p class="font-weight-bold">ID: '.$herramientas->zero_fill($movimientocaja->id_movimiento, 10).'</p>
            </div>
			<div class="text-left">
                <br />
                <p><span class="font-weight-bold">Descripción/Concepto:</span>  '.$movimientocaja->descripcion.'</p>
                <p><span class="font-weight-bold">Fecha de Emisión:</span>  '.date("d-m-Y / H:i A", strtotime($movimientocaja->fecha_registro)).'</p>
				<p><span class="font-weight-bold">Usuario: </span>'.ucwords($vendedor_nombre).' - (ID: '.$vendedor_id.')</p>
                '.$html_responsable.$documento_relacionado.$detalle_documento.'
			</div>

            <div class="text-left">
				<p><span class="font-weight-bold">Monto: '.$simbolo_moneda.' '.round($movimientocaja->monto, $num_decimales).'</span> </p>
				'.$nombre_condicion_pago.'
			</div>
			
			<footer class="mt-2">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px;" />
				<div class="text-center mt-2">
					<img src="'.$img_empresa.'" width="120px" class="margin-0"><p class="argin-0" style="font-style: italic">Emitido por: <span class="font-weight-bold">'.$data_patrocinador['url_domain'].'</span></p>
				</div>
			</footer>
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}
}
?>