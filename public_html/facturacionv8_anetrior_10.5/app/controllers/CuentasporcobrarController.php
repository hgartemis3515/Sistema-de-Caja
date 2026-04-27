<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class CuentasporcobrarController extends ControllerBase {
	public function indexAction() {
		$this->setTitle('Cuentas por Cobrar');
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

            ->addJs("https://unpkg.com/axios/dist/axios.min.js", false)

			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")

			->addJs($this->baseUri . "public/js/general.js?i=v2")
            ->addJs($this->baseUri . "public/js/cuentasporcobrar/cuentasporcobrar.js?j=".rand())
            ->addJs($this->baseUri . "public/js/cuentasporcobrar/lista_abonos.js?j=".rand());

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

        $lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo in ('contado', 'tarjeta_credito', 'transferencia')", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->lista_condiciones_pago = $lista_condiciones_pago;
        $this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
	}

	public function getListaCpeAction() {
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
			
			$resp = $this->get_lista_cpe($contribuyente, $usuario, $datapost);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();
		} 
	}

    public function getTotalesMontoPorCobrarAction() {
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
            
            $resp_totales_cpe = $this->get_total_deuda_by_doc_cpe($contribuyente, $usuario, $datapost);
            if($resp_totales_cpe['respuesta'] == 'error') {
                echo json_encode($resp_totales_cpe);
                exit();
            }

            $resp_totales_nota_venta = $this->get_total_deuda_by_nota_venta($contribuyente, $usuario, $datapost);
            if($resp_totales_nota_venta['respuesta'] == 'error') {
                echo json_encode($resp_totales_nota_venta);
                exit();
            }

            $resp['respuesta'] = 'ok';

            $formato_soles = new NumberFormatter('es_PE', NumberFormatter::CURRENCY);
            $resp['boletas_soles'] = $formato_soles->formatCurrency(round(floatval($resp_totales_cpe['boletas_soles']), 2), 'PEN');
            $resp['facturas_soles'] = $formato_soles->formatCurrency(round(floatval($resp_totales_cpe['facturas_soles']), 2), 'PEN');
            $resp['notas_venta_soles'] = $formato_soles->formatCurrency(round(floatval($resp_totales_nota_venta['notas_venta_soles']), 2), 'PEN');
            $resp['total_soles'] = $formato_soles->formatCurrency(round(floatval($resp_totales_cpe['boletas_soles']) + floatval($resp_totales_cpe['facturas_soles']) + floatval($resp_totales_nota_venta['notas_venta_soles']), 2), 'PEN');
            
            $formato_dolares = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
            $resp['boletas_dolares'] = $formato_dolares->formatCurrency(round(floatval($resp_totales_cpe['boletas_dolares']), 2), 'USD');
            $resp['facturas_dolares'] = $formato_dolares->formatCurrency(round(floatval($resp_totales_cpe['facturas_dolares']), 2), 'USD');
            $resp['notas_venta_dolares'] = $formato_dolares->formatCurrency(round(floatval($resp_totales_nota_venta['notas_venta_dolares']), 2), 'USD');
            $resp['total_dolares'] = $formato_dolares->formatCurrency(round(floatval($resp_totales_cpe['boletas_dolares']) + floatval($resp_totales_cpe['facturas_dolares']) + floatval($resp_totales_nota_venta['notas_venta_dolares']), 2), 'USD');

            echo json_encode($resp);
            exit();
		} 
    }

    public function getReporteExcelNventasAction() {
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

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes iniciar sesión.';
            echo json_encode($resp);
            exit();
        }

        $tipo_reporte = isset($datapost['tipo_reporte'])?$datapost['tipo_reporte']:'lista';


        $resp = $this->get_lista_notaventa($contribuyente, $usuario, $datapost, $tipo_reporte);
        if($resp['respuesta'] == 'error') {
            echo json_encode($resp);
            exit();
        }

        $this->crear_excel_cuentas_por_cobrar($resp);
        exit();
    }

    public function getReporteExcelCpeAction() {
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

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes iniciar sesión.';
            echo json_encode($resp);
            exit();
        }

        $tipo_reporte = isset($datapost['tipo_reporte'])?$datapost['tipo_reporte']:'lista';


        $resp = $this->get_lista_cpe($contribuyente, $usuario, $datapost, $tipo_reporte);
        if($resp['respuesta'] == 'error') {
            echo json_encode($resp);
            exit();
        }

        $this->crear_excel_cuentas_por_cobrar($resp);
        exit();
    }

    public function crear_excel_cuentas_por_cobrar($data) {
        $lista = $data['data']['data'];
        $sum_total_pagado_soles = $data['sum_total_pagado_soles'];
        $sum_total_adeudado_soles = $data['sum_total_adeudado_soles'];
        $sum_total_pagado_dolares = $data['sum_total_pagado_dolares'];
        $sum_total_adeudado_dolares = $data['sum_total_adeudado_dolares'];
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Reporte Cuentas por Cobrar")
            ->setSubject("Reporte Cuentas por Cobrar")
            ->setDescription(
                "Reporte Cuentas por Cobrar."
            )
            ->setKeywords("facturalaya.com, código fuente, plantilla importación, importar cpe")
            ->setCategory("importacion Abonos");

        $spreadsheet->setActiveSheetIndex(0)->setTitle("Lista Abonos");

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A2:I2");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("A3:I3");

		$spreadsheet->setActiveSheetIndex(0)->mergeCells("A5:C5"); 
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("D5:E5");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("F5:F6");
		$spreadsheet->setActiveSheetIndex(0)->mergeCells("G5:G6");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("H5:H6");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("I5:I6");

        $estilo_cabecera_texto = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 11,
                'name'  => 'Verdana'
            )
        );

        $estilo_titular = array(
            'font'  => array(
                'bold'  => true,
                //'italic'=> true,
                'color' => array('rgb' => '3F51B5'),
                'size'  => 14,
                'name'  => 'Verdana'
            )
        );


        $spreadsheet->setActiveSheetIndex(0)->getStyle("A5:I6")->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A5:I6")->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A5:I6")->applyFromArray($estilo_cabecera_texto);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A5:I6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3F51B5');

        $spreadsheet->setActiveSheetIndex(0)->getStyle("A2:I3")->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A2:I3")->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A2:I3")->applyFromArray($estilo_titular);
        
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE CUENTAS POR COBRAR')
			->setCellValue('A3', "Del $fecha_inicio al $fecha_fin")

            ->setCellValue('A5', 'DOCUMENTO')
            ->setCellValue('A6', 'FEC. DOC')
            ->setCellValue('B6', 'SERIE')
            ->setCellValue('C6', 'CORRELATIVO')
            ->setCellValue('D5', 'CLIENTE')
            ->setCellValue('D6', 'NUM DOC')
            ->setCellValue('E6', 'RAZON SOCIAL')
			->setCellValue('F5', 'FEC. VENC.')
			->setCellValue('G5', 'MONEDA')
            ->setCellValue('H5', 'DEUDA TOTAL')
            ->setCellValue('I5', 'TOTAL ABONOS')
            ;

        $n = 6;

        if($sum_total_pagado_soles > 0 || $sum_total_adeudado_soles > 0) {
            foreach($lista as $item) {
                $item = (object)$item;
                if($item->moneda == 'PEN') {
                    $n++;
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValueExplicit('A'.$n, $item->fecha_registro_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('B'.$n, $item->serie, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('C'.$n, $item->numero_comprobante, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('D'.$n, $item->cliente_num_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('E'.$n, $item->cliente_razon_social, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('F'.$n, $item->fecha_vencimiento_deuda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('G'.$n, $item->moneda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('H'.$n, $item->monto_deuda_total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                        ->setCellValueExplicit('I'.$n, $item->monto_total_abonos, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ;
                }
            }

            $n++;
            $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:G$n");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", 'TOTAL SOLES');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->getAlignment()->setHorizontal('center');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->getAlignment()->setVertical('center');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->applyFromArray($estilo_cabecera_texto);
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3F51B5');

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("H$n", $sum_total_adeudado_soles)
                ->setCellValue("I$n", $sum_total_pagado_soles);
        }

        if($sum_total_pagado_dolares > 0 || $sum_total_adeudado_dolares > 0) {
            foreach($lista as $item) {
                $item = (object)$item;
                if($item->moneda == 'USD') {
                    $n++;
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValueExplicit('A'.$n, $item->fecha_registro_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('B'.$n, $item->serie, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('C'.$n, $item->numero_comprobante, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('D'.$n, $item->cliente_num_doc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('E'.$n, $item->cliente_razon_social, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('F'.$n, $item->fecha_vencimiento_deuda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('G'.$n, $item->moneda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                        ->setCellValueExplicit('H'.$n, $item->monto_deuda_total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                        ->setCellValueExplicit('I'.$n, $item->monto_total_abonos, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    ;
                }
            }

            $n++;
            $spreadsheet->setActiveSheetIndex(0)->mergeCells("A$n:G$n");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$n", 'TOTAL DÓLARES');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->getAlignment()->setHorizontal('center');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->getAlignment()->setVertical('center');
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->applyFromArray($estilo_cabecera_texto);
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A$n:I$n")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3F51B5');

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("H$n", $sum_total_adeudado_dolares)
                ->setCellValue("I$n", $sum_total_pagado_dolares);
        }


        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(9);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(18);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(14);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(11);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(18);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(19);

        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(5)->setRowHeight(28);

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Lista Guías';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function get_total_deuda_by_doc_cpe($contribuyente, $usuario, $datapost) {
        $gestion_usuarios = new GestionuserController;
        $permiso_usuario = $gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_cuentascobrar');
        
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d"):$datapost['fecha_fin_valor'];
        
        $herramientas = new HerramientasController;
        if(!$herramientas->validate_date($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        if(!$herramientas->validate_date($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d", strtotime($fecha_fin_valor));
        $fecha_actual = date("Y-m-d");
        
        $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']) + 0;

        $tipo_venta = !isset($datapost['tipo_venta'])?'todos':$datapost['tipo_venta'];
        $tipos_comprobantes = array('03', '01');
        
        $criterios_where = '';
        if($tipo_venta == 'todos') {
            
        } else if($tipo_venta == 'pagado') {
            $criterios_where = $criterios_where." and (de.monto_adeudado is null or de.monto_adeudado <= 0) ";
        } else if($tipo_venta == 'pendiente') {
            $criterios_where = $criterios_where." and (de.monto_adeudado > 0) ";
        } else if($tipo_venta == 'vencidas') {
            $criterios_where = $criterios_where." and (de.monto_adeudado > 0 and CAST(de.fecha_pagopendiente AS DATE) < CAST('$fecha_actual' AS DATE)) ";
        }
        
        if(count($tipos_comprobantes) > 0){ 
            $criterios_where = $criterios_where." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; 
        } else {
            $criterios_where = $criterios_where." and de.id_tipodoc_electronico in ('01', '03') "; 
        }

        if($idcliente > 0) { 
            $criterios_where = $criterios_where." and de.idcliente = ".$idcliente." "; 
        }

        if($permiso_usuario == 'sucursal_asignada') {
            $id_sucursal_asignada = $usuario->idsucursal;
            if(!empty($id_sucursal_asignada)) {
                $criterios_where = $criterios_where." and de.id_sucursal = $id_sucursal_asignada "; 
            }
        } else if($permiso_usuario == 'propias') {
            $criterios_where = $criterios_where." and de.id_vendedor = $usuario->idusuario "; 
        }

        $query = "SELECT de.id_tipodoc_electronico id_tipodoc_electronico, de.id_codigomoneda id_codigomoneda, sum(de.monto_adeudado) total_monto_adeudado FROM doc_electronico de 
                    WHERE CAST(de.fecha_registro AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_registro AS DATE) <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente and de.tipo_envio_sunat = :tipo_envio_sunat and de.estado_envio_sunat not in ('rechazado', 'anulado') ".$criterios_where."
        
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

                    GROUP BY de.id_tipodoc_electronico, de.id_codigomoneda
        ";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Excepción capturada: (get_total_deuda_by_doc_cpe) '.$e->getMessage();
            return $resp;
        }

        $resp['boletas_soles'] = 0;
        $resp['facturas_soles'] = 0;
        $resp['boletas_dolares'] = 0;
        $resp['facturas_dolares'] = 0;
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;

            if($fila->id_tipodoc_electronico == '03') {
                if($fila->id_codigomoneda == 'PEN') {
                    $resp['boletas_soles'] = $fila->total_monto_adeudado; 
                } else if($fila->id_codigomoneda == 'USD') {
                    $resp['boletas_dolares'] = $fila->total_monto_adeudado; 
                }
            } else if($fila->id_tipodoc_electronico == '01') {
                if($fila->id_codigomoneda == 'PEN') {
                    $resp['facturas_soles'] = $fila->total_monto_adeudado; 
                } else if($fila->id_codigomoneda == 'USD') {
                    $resp['facturas_dolares'] = $fila->total_monto_adeudado; 
                }
            }

        }

        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function get_lista_cpe($contribuyente, $usuario, $datapost, $tipo_reporte = '') {

        $gestion_usuarios = new GestionuserController;
        $permiso_usuario = $gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_cuentascobrar');
        
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d"):$datapost['fecha_fin_valor'];
        
        $herramientas = new HerramientasController;
        if(!$herramientas->validate_date($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        if(!$herramientas->validate_date($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d", strtotime($fecha_fin_valor));
        $fecha_actual = date("Y-m-d");
        
        $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']);
        $tipo_venta = !isset($datapost['tipo_venta'])?'todos':$datapost['tipo_venta'];
        $tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
        $tipos_comprobantes_validos = array('03', '01', '07', '08', '77');

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

        $sql_reporte = '';
        $sql_reporte_2 = '';
        if($tipo_venta == 'todos') {
            
        } else if($tipo_venta == 'pagado') {
            $sql_reporte = $sql_reporte." and (monto_adeudado is null or monto_adeudado <= 0) ";
            $sql_reporte_2 = $sql_reporte_2." and (de.monto_adeudado is null or de.monto_adeudado <= 0) ";
        } else if($tipo_venta == 'pendiente') {
            $sql_reporte = $sql_reporte." and (monto_adeudado > 0) ";
            $sql_reporte_2 = $sql_reporte_2." and (de.monto_adeudado > 0) ";
        } else if($tipo_venta == 'vencidas') {
            $sql_reporte = $sql_reporte." and (monto_adeudado > 0 and CAST(fecha_pagopendiente AS DATE) < CAST('$fecha_actual' AS DATE)) ";
            $sql_reporte_2 = $sql_reporte_2." and (de.monto_adeudado > 0 and CAST(de.fecha_pagopendiente AS DATE) < CAST('$fecha_actual' AS DATE)) ";
        }
        
        if(count($tipos_comprobantes) > 0){ 
            $sql_reporte = $sql_reporte." and id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; 
            $sql_reporte_2 = $sql_reporte_2." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; 
        } else { 
            $sql_reporte = $sql_reporte." and id_tipodoc_electronico in ('01', '03') "; 
            $sql_reporte_2 = $sql_reporte_2." and de.id_tipodoc_electronico in ('01', '03') "; 
        }

        if($idcliente > 0) { 
            $sql_reporte = $sql_reporte." and idcliente = ".$idcliente." "; 
            $sql_reporte_2 = $sql_reporte_2." and de.idcliente = ".$idcliente." "; 
        }

        if($permiso_usuario == 'sucursal_asignada') {
            $id_sucursal_asignada = $usuario->idsucursal;
            if(!empty($id_sucursal_asignada)) {
                $sql_reporte = $sql_reporte." and id_sucursal = $id_sucursal_asignada "; 
                $sql_reporte_2 = $sql_reporte_2." and de.id_sucursal = $id_sucursal_asignada "; 
            }
        } else if($permiso_usuario == 'propias') {
            $sql_reporte = $sql_reporte." and id_vendedor = $usuario->idusuario "; 
            $sql_reporte_2 = $sql_reporte_2." and de.id_vendedor = $usuario->idusuario "; 
        }

        //(monto_adeudado is null or monto_adeudado <= 0)
        $query_1 = " estado_envio_sunat not in ('rechazado', 'anulado') and CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST(fecha_registro AS DATE) <= CAST(:fecha_fin: AS DATE) and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: ".$sql_reporte;
        
        $comprobantes = DocElectronico::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro DESC"));

        //Inicio de configuración Server Side.
        $total_registros = count($comprobantes);
        
        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => '`de`.`fecha_registro`',
            1 => 'doc_serie_numero',
            2 => 'doc_serie_numero',
            3 => 'ruc_nom_clie',
            4 => '`de`.`total`',
            5 => 'deuda_total',
            6 => '`de`.`fecha_pagopendiente`',
            7 => '`de`.`fecha_registro`'
        );
        
        $query = "SELECT de.*, CONCAT(de.serie_comprobante,'-',de.numero_comprobante) as doc_serie_numero, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, cpago.tipo, cpago.condicionpago, (de.total - de.monto_adeudado) as deuda_total FROM doc_electronico de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago where CAST(de.fecha_registro AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_registro AS DATE) <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente and de.tipo_envio_sunat = :tipo_envio_sunat and de.estado_envio_sunat not in ('rechazado', 'anulado') ".$sql_reporte_2;
        
        $search_value = isset($datapost['search']['value'])?$datapost['search']['value']:'';
        if(!empty($search_value)) {
            $query = $query." AND (
                CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or 
                de.transporte_nro_placa like :termino or 
                de.nro_otr_comprobante like :termino or 
                de.id_codigomoneda like :termino or cpago.tipo like :termino or cpago.condicionpago like :termino
                ) ";
        }

        $query = $query."
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
        
        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $search_value);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        /*
        if($tipo_reporte == '') {
            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        } else {
            //si el reporte es para un excel entonces eliminamos la posibilidad de limitar el reporte.
            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." ";
        }
        */
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
            return $resp;
        }
        
        $array_lista = array();

        $sum_total_pagado_soles = 0;
        $sum_total_adeudado_soles = 0;
        $sum_total_pagado_dolares = 0;
        $sum_total_adeudado_dolares = 0;

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
            $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));
            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));

            $opt_agregar_cuotas = "si";
            if($item->id_tipodoc_electronico == '01') { 
                if($item->credito_sunat == 'si') {
                    $permitir_cambio_cuotas = $this->get_contribuyente_opcion($usuario->id_contribuyente, 'permitir_cambio_cuotas');
                    if($permitir_cambio_cuotas == 'no' || $permitir_cambio_cuotas == '') {
                        $opt_agregar_cuotas = "no";
                    }
                }
            }

            //revisar ACTUALIZACIÓN A PHP8.3
            $menu_crear_abono_function = "ver_crear_abonos($item->id_contribuyente, '$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante, '".date("Y-m-d", strtotime($item->fecha_pagopendiente ?? ''))."', 'credito', $item->monto_adeudado, '$item->id_codigomoneda', '$opt_agregar_cuotas')";
            $menu_crear_abono = '<li>
                                    <a href="javascript:void(0)" onclick="'.$menu_crear_abono_function.'"><img src="/facturacionv8/public/img/svg/abonos.svg" style="width: 20px;"> Ver/Crear Abonos</a>
                                </li>
                                ';

            $fecha_de_pago = '';
            $monto_adeudado = 0;
            $class_fecha_pago = 'text-success';
            $menu_lista_abonos = "get_lista_abonos($item->id_contribuyente, '$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante)";
            $dias_restantes_credito = 0;
            $texto_dias_restantes = 'Fecha Límite';
            if(!empty($item->monto_adeudado) && floatval($item->monto_adeudado) > 0 && !empty($item->fecha_pagopendiente)) {
                $monto_adeudado = $item->monto_adeudado;
                $resp_comparar_fechas = $herramientas->comparar_fechas($item->fecha_pagopendiente, date('Y-m-d'));
                $dias_restantes_credito = $resp_comparar_fechas['diferencia_primera_segunda'];
                if($dias_restantes_credito <= 15) {
                    if($dias_restantes_credito >= 0) {
                        if($dias_restantes_credito == 0) {
                            $class_fecha_pago = 'text-danger';
                            $texto_dias_restantes = 'Hoy se vence el crédito';
                        } else if($dias_restantes_credito > 0 && $dias_restantes_credito <= 5) {
                            $class_fecha_pago = 'text-danger';
                            $texto_dias_restantes = 'Ya solo faltan '.abs($dias_restantes_credito).' días';
                        } else {
                            $class_fecha_pago = 'text-primary';
                            $texto_dias_restantes = 'Faltan solo '.abs($dias_restantes_credito).' días';
                        }
                    } else {
                        $texto_dias_restantes = 'Se venció hace '.abs($dias_restantes_credito).' días';
                        $class_fecha_pago = 'text-info';
                    }
                } else {
                    $class_fecha_pago = 'text-success';
                    $texto_dias_restantes = 'Vence en '.abs($dias_restantes_credito).' días';
                }

                $fecha_de_pago = date("d-m-Y", strtotime($item->fecha_pagopendiente));
            }

            $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_a4 = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 30px; cursor: pointer;">';

            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="max-width: 30px; cursor: pointer;">';
            $btn_pdf = $btn_pdf_a4.$btn_pdf_ticket;
            
            $menu = '';
            if(!empty($menu_crear_abono)) {
                $menu = $menu.'
                <ul class="icons-list text-center">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                            '.$menu_crear_abono.'
                        </ul>
                    </li>
                </ul>';
            }

            if($item->id_codigomoneda == 'PEN') {
                $sum_total_pagado_soles = $sum_total_pagado_soles + round($item->total - $monto_adeudado, 2);
                $sum_total_adeudado_soles = $sum_total_adeudado_soles + $monto_adeudado;
            } else {
                $sum_total_pagado_dolares = $sum_total_pagado_dolares + round($item->total - $monto_adeudado, 2);
                $sum_total_adeudado_dolares = $sum_total_adeudado_dolares + $monto_adeudado;
            }

            $array_lista[] = array(
                'fecha_registro_doc'    => date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
                'serie'             => $item->serie_comprobante,
                'numero_comprobante'   => $item->numero_comprobante,
                'cliente_razon_social'  => $cliente->razon_social,
                'cliente_num_doc'       => $cliente->num_doc,
                'simbolo_moneda'                => $moneda->simbolo,
                'fecha_vencimiento_deuda'     => $fecha_de_pago,
                'monto_deuda_total'           => $monto_adeudado,
                'monto_total_abonos'       => round($item->total - $monto_adeudado, 2),
                'moneda'    => $item->id_codigomoneda,



                'fecha_registro' => date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
                'doc_serie_numero' => $tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante, 
                'doc_serie_numero_pdf' => $btn_pdf,
                'ruc_nom_clie' => $cliente->num_doc.'<br />'.$cliente->razon_social,
                'deuda_total' => $moneda->simbolo.' '.$monto_adeudado,
                'abonos_total' => $moneda->simbolo.' '.round($item->total - $monto_adeudado, 2),
                'fecha_vencimiento' => '
                <div class="content-group" style="margin: 0px !important;" style="cursor: pointer;" onclick="'.$menu_crear_abono_function.'">
                    <h7 class="no-margin '.$class_fecha_pago.'"><i class="icon-clipboard3 position-left '.$class_fecha_pago.'"></i> '.$fecha_de_pago.'</h7><br />
                    <span class="text-muted text-size-small">'.$texto_dias_restantes.'</span>
                </div>
                ',
                'menu' => $menu
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
        $resp['sum_total_pagado_soles'] = $sum_total_pagado_soles;
        $resp['sum_total_adeudado_soles'] = $sum_total_adeudado_soles;
        $resp['sum_total_pagado_dolares'] = $sum_total_pagado_dolares;
        $resp['sum_total_adeudado_dolares'] = $sum_total_adeudado_dolares;
        $resp['fecha_inicio'] = date("d-m-Y", strtotime($fecha_inicio_valor));
        $resp['fecha_fin'] = date("d-m-Y", strtotime($fecha_fin_valor));
        $resp['data'] = $json_data;
        return $resp;
    }

	public function getListaNotaventaAction() {
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

			$resp = $this->get_lista_notaventa($contribuyente, $usuario, $datapost);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();	
		}
	}

    public function get_total_deuda_by_nota_venta($contribuyente, $usuario, $datapost) {
        $gestion_usuarios = new GestionuserController;
        $permiso_usuario = $gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_cuentascobrar');

        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d"):$datapost['fecha_fin_valor'];
        
        $herramientas = new HerramientasController;
        if(!$herramientas->validate_date($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        if(!$herramientas->validate_date($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d", strtotime($fecha_fin_valor));
        $fecha_actual = date("Y-m-d");
        
        $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']);
        $tipo_venta = !isset($datapost['tipo_venta'])?'todos':$datapost['tipo_venta'];
        $tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
        $tipos_comprobantes_validos = array('03', '01', '07', '08', '77');

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

        $criterios_where = '';
        if($tipo_venta == 'todos') {
            
        } else if($tipo_venta == 'pagado') {
            $criterios_where = $criterios_where." and (de.monto_adeudado is null or de.monto_adeudado <= 0) ";
        } else if($tipo_venta == 'pendiente') {
            $criterios_where = $criterios_where." and (de.monto_adeudado > 0)";
        } else if($tipo_venta == 'vencidas') {
            $criterios_where = $criterios_where." and (de.monto_adeudado > 0 and CAST(de.fecha_pagopendiente AS DATE) < CAST('$fecha_actual' AS DATE))";
        }
        
        $criterios_where = $criterios_where." and de.id_tipodocumento = '77' ";

        if($idcliente > 0) { 
            $criterios_where = $criterios_where." and de.idcliente = ".$idcliente." "; 
        }

        if($permiso_usuario == 'sucursal_asignada') {
            $id_sucursal_asignada = $usuario->idsucursal;
            if(!empty($id_sucursal_asignada)) {
                $criterios_where = $criterios_where." and de.id_sucursal = $id_sucursal_asignada "; 
            }
        } else if($permiso_usuario == 'propias') {
            $criterios_where = $criterios_where." and de.id_vendedor = $usuario->idusuario "; 
        }
        
        $query = "SELECT de.id_tipodocumento id_tipodoc_electronico, de.id_codigomoneda, sum(de.monto_adeudado) total_monto_adeudado FROM doc_no_oficial de WHERE CAST(de.fecha_registro AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_registro AS DATE) <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente and de.modalidad = :tipo_envio_sunat and  de.estado_documento = 'activo' ".$criterios_where." GROUP BY de.id_tipodocumento, de.id_codigomoneda";
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Excepción capturada: (get_total_deuda_by_nota_venta) '.$e->getMessage();
            return $resp;
        }

        $resp['notas_venta_soles'] = 0;
        $resp['notas_venta_dolares'] = 0;
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            if($fila->id_codigomoneda == 'PEN') {
                $resp['notas_venta_soles'] = $fila->total_monto_adeudado;
            } else if($fila->id_codigomoneda == 'USD') {
                $resp['notas_venta_dolares'] = $fila->total_monto_adeudado;
            }
            
        }

        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function get_lista_notaventa($contribuyente, $usuario, $datapost, $tipo_reporte = '') {

        $gestion_usuarios = new GestionuserController;
        $permiso_usuario = $gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_cuentascobrar');

        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d"):$datapost['fecha_fin_valor'];
        
        $herramientas = new HerramientasController;
        if(!$herramientas->validate_date($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        if(!$herramientas->validate_date($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en la Fecha Movimiento';
            $resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
            return $resp;
        }
        
        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d", strtotime($fecha_fin_valor));
        $fecha_actual = date("Y-m-d");
        
        $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']);
        $tipo_venta = !isset($datapost['tipo_venta'])?'todos':$datapost['tipo_venta'];
        $tipos_comprobantes = empty($datapost['tipos_documentos'])?array():explode(',', $datapost['tipos_documentos']);
        $tipos_comprobantes_validos = array('03', '01', '07', '08', '77');

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

        $sql_reporte = '';
        $sql_reporte_2 = '';
        if($tipo_venta == 'todos') {
            
        } else if($tipo_venta == 'pagado') {
            $sql_reporte = $sql_reporte." and (monto_adeudado is null or monto_adeudado <= 0) ";
            $sql_reporte_2 = $sql_reporte_2." and (de.monto_adeudado is null or de.monto_adeudado <= 0) ";
        } else if($tipo_venta == 'pendiente') {
            $sql_reporte = $sql_reporte." and (monto_adeudado > 0)";
            $sql_reporte_2 = $sql_reporte_2." and (de.monto_adeudado > 0)";
        } else if($tipo_venta == 'vencidas') {
            $sql_reporte = $sql_reporte." and (monto_adeudado > 0 and CAST(fecha_pagopendiente AS DATE) < CAST('$fecha_actual' AS DATE))";
            $sql_reporte_2 = $sql_reporte_2." and (de.monto_adeudado > 0 and CAST(de.fecha_pagopendiente AS DATE) < CAST('$fecha_actual' AS DATE))";
        }
        
        $sql_reporte = $sql_reporte." and id_tipodocumento = '77' "; 
        $sql_reporte_2 = $sql_reporte_2." and de.id_tipodocumento = '77' ";

        if($idcliente > 0) { 
            $sql_reporte = $sql_reporte." and idcliente = ".$idcliente." "; 
            $sql_reporte_2 = $sql_reporte_2." and de.idcliente = ".$idcliente." "; 
        }

        if($permiso_usuario == 'sucursal_asignada') {
            $id_sucursal_asignada = $usuario->idsucursal;
            if(!empty($id_sucursal_asignada)) {
                $sql_reporte = $sql_reporte." and id_sucursal = $id_sucursal_asignada "; 
                $sql_reporte_2 = $sql_reporte_2." and de.id_sucursal = $id_sucursal_asignada "; 
            }
        } else if($permiso_usuario == 'propias') {
            $sql_reporte = $sql_reporte." and id_vendedor = $usuario->idusuario "; 
            $sql_reporte_2 = $sql_reporte_2." and de.id_vendedor = $usuario->idusuario "; 
        }

        //(monto_adeudado is null or monto_adeudado <= 0)
        $query_1 = " estado_documento = 'activo' and CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST(fecha_registro AS DATE) <= CAST(:fecha_fin: AS DATE) and id_contribuyente = :id_contribuyente: and modalidad = :tipo_envio_sunat: ".$sql_reporte;
        
        $comprobantes = DocNoOficial::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro DESC"));

        //Inicio de configuración Server Side.
        $total_registros = count($comprobantes);
        
        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => '`de`.`fecha_registro`',
            1 => 'doc_serie_numero',
            2 => 'doc_serie_numero',
            3 => 'ruc_nom_clie',
            4 => '`de`.`total`',
            5 => 'deuda_total',
            6 => '`de`.`fecha_pagopendiente`',
            7 => '`de`.`fecha_registro`'
        );
        
        $query = "SELECT de.*, CONCAT('',de.numero_comprobante) as doc_serie_numero, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, cpago.tipo, cpago.condicionpago, (de.total - de.monto_adeudado) as deuda_total FROM doc_no_oficial de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago where CAST(de.fecha_registro AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_registro AS DATE) <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente and de.modalidad = :tipo_envio_sunat and  de.estado_documento = 'activo' ".$sql_reporte_2;
        
        $search_value = isset($datapost['search']['value'])?$datapost['search']['value']:'';
        if(!empty($search_value)) {
            $query = $query." AND (
                CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                CONCAT('',de.numero_comprobante) like :termino or 
                de.transporte_nro_placa like :termino or 
                de.nro_otr_comprobante like :termino or 
                de.id_codigomoneda like :termino or cpago.tipo like :termino or cpago.condicionpago like :termino
                ) ";
        }
        
        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $search_value);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        /*
        if($tipo_reporte == '') {
            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        } else {
            //si el reporte es para un excel entonces eliminamos la posibilidad de limitar el reporte.
            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." ";
        }
        */
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
            return $resp;
        }
        
        $array_lista = array();

        $sum_total_pagado_soles = 0;
        $sum_total_adeudado_soles = 0;
        $sum_total_pagado_dolares = 0;
        $sum_total_adeudado_dolares = 0;

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
            $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));
            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodocumento)));

            $fecha_de_pago = '';
            $monto_adeudado = 0;
            $class_fecha_pago = 'text-success';
            $menu_crear_abono = '';
            $menu_lista_abonos = "get_lista_abonos($item->id_contribuyente, '$item->id_tipodocumento', '', $item->numero_comprobante)";
            $dias_restantes_credito = 0;
            $texto_dias_restantes = 'Fecha Límite';
            $fecha_pagopendiente = empty($item->fecha_pagopendiente)?date('Y-m-d', strtotime('+1 day')):$item->fecha_pagopendiente;
            if(!empty($item->monto_adeudado) && floatval($item->monto_adeudado) > 0) {
                $monto_adeudado = $item->monto_adeudado;
                $resp_comparar_fechas = $herramientas->comparar_fechas($fecha_pagopendiente, date('Y-m-d'));
                $dias_restantes_credito = $resp_comparar_fechas['diferencia_primera_segunda'];
                if($dias_restantes_credito <= 15) {
                    if($dias_restantes_credito >= 0) {
                        if($dias_restantes_credito == 0) {
                            $class_fecha_pago = 'text-danger';
                            $texto_dias_restantes = 'Hoy se vence el crédito';
                        } else if($dias_restantes_credito > 0 && $dias_restantes_credito <= 5) {
                            $class_fecha_pago = 'text-danger';
                            $texto_dias_restantes = 'Ya solo faltan '.abs($dias_restantes_credito).' días';
                        } else {
                            $class_fecha_pago = 'text-primary';
                            $texto_dias_restantes = 'Faltan solo '.abs($dias_restantes_credito).' días';
                        }
                    } else {
                        $texto_dias_restantes = 'Se venció hace '.abs($dias_restantes_credito).' días';
                        $class_fecha_pago = 'text-info';
                    }
                } else {
                    $class_fecha_pago = 'text-success';
                    $texto_dias_restantes = 'Vence en '.abs($dias_restantes_credito).' días';
                }

                $fecha_de_pago = date("d-m-Y", strtotime($fecha_pagopendiente));
            }
            
            $menu_crear_abono_function = "ver_crear_abonos($item->id_contribuyente, '77', '', $item->numero_comprobante, '".date("Y-m-d", strtotime($fecha_pagopendiente))."', 'credito', $item->monto_adeudado, '$item->id_codigomoneda')";
            $menu_crear_abono = '<li>
                                    <a href="javascript:void(0)" onclick="'.$menu_crear_abono_function.'"><img src="/facturacionv8/public/img/svg/abonos.svg" style="width: 20px;"> Ver/Crear Abono</a>
                                </li>
                                ';

            //id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
            $string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$item->id_tipodocumento."|| ||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$usuario->id_contribuyente||".$item->id_tipodocumento."|| ||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_a4 = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 30px; cursor: pointer;">';

            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="max-width: 30px; cursor: pointer;">';
            
            $menu = '';
            if(!empty($menu_crear_abono)) {
                $menu = $menu.'
                <ul class="icons-list text-center">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                            '.$menu_crear_abono.'
                        </ul>
                    </li>
                </ul>';
            }

            if($item->id_codigomoneda == 'PEN') {
                $sum_total_pagado_soles = $sum_total_pagado_soles + round($item->total - $monto_adeudado, 2);
                $sum_total_adeudado_soles = $sum_total_adeudado_soles + $monto_adeudado;
            } else {
                $sum_total_pagado_dolares = $sum_total_pagado_dolares + round($item->total - $monto_adeudado, 2);
                $sum_total_adeudado_dolares = $sum_total_adeudado_dolares + $monto_adeudado;
            }

            $array_lista[] = array(
                'fecha_registro_doc'        => date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
                'serie'                     => 'NVENTA',
                'numero_comprobante'        => $item->numero_comprobante,
                'cliente_razon_social'      => $cliente->razon_social,
                'cliente_num_doc'           => $cliente->num_doc,
                'simbolo_moneda'            => $moneda->simbolo,
                'fecha_vencimiento_deuda'   => $fecha_de_pago,
                'monto_deuda_total'         => $monto_adeudado,
                'monto_total_abonos'        => round($item->total - $monto_adeudado, 2),
                'moneda'                    => $item->id_codigomoneda,


                'fecha_registro'            => date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
                'doc_serie_numero'          => $tipo_comprobante->descripcion.': '.$item->doc_serie_numero, 
                'doc_serie_numero_pdf'      => $btn_pdf_a4.$btn_pdf_ticket,
                'ruc_nom_clie'              => $cliente->num_doc.'<br />'.$cliente->razon_social,
                'deuda_total'               => $moneda->simbolo.' '.$monto_adeudado,
                'abonos_total'              => $moneda->simbolo.' '.round($item->total - $monto_adeudado, 2),
                'fecha_vencimiento'         => '
                <div class="content-group" style="margin: 0px !important;" style="cursor: pointer;" onclick="'.$menu_crear_abono_function.'">
                    <h7 class="no-margin '.$class_fecha_pago.'"><i class="icon-clipboard3 position-left '.$class_fecha_pago.'"></i> '.$fecha_de_pago.'</h7><br />
                    <span class="text-muted text-size-small">'.$texto_dias_restantes.'</span>
                </div>
                ',
                'menu'                      => $menu
            );
        }
        
        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
            "data"            => $array_lista,   // total data array
            "numero_n"        => $n
        );

        $resp['respuesta']                  = 'ok';
        $resp['sum_total_pagado_soles']     = $sum_total_pagado_soles;
        $resp['sum_total_adeudado_soles']   = $sum_total_adeudado_soles;
        $resp['sum_total_pagado_dolares']   = $sum_total_pagado_dolares;
        $resp['sum_total_adeudado_dolares'] = $sum_total_adeudado_dolares;
        $resp['fecha_inicio']               = date("d-m-Y", strtotime($fecha_inicio_valor));
        $resp['fecha_fin']                  = date("d-m-Y", strtotime($fecha_fin_valor));
        $resp['data']                       = $json_data;
        return $resp;
    }

	public function get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $id_contribuyente, $tipo_envio_sunat, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
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
            $n++;
        }

        return $n;
    }

    //Función importante que se utiliza también para extraer las cuotas y enviar a sunat
    public function get_lista_abonos($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat) {
        $array_cpe = array('01', '03', '07', '08');
        $array_docs_nooficial = array('77');

        if(in_array($id_tipodoc_electronico, $array_cpe)) {
            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
        } else {
            $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
        }

        if($documento->tipo_venta != 'credito') {
            $resp['respuesta'] = 'ok';
            $resp['lista_cuotas'] = array();
            $resp['total_adeudado'] = 0;
            return $resp;
        }

        //en estas consultas incluso deberíamos limitar la consulta a tipo_abono = "cuota", ya que es el único tipo en el sistema que se considera una cuota y tiene fecha de vencimiento.
        //tenemos pago parcial, que se utiliza para combinar con detracción o retención.
        if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
            $montos_cobrados = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and (estado = 'activo' or estado = 'indefinido')", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'numero_comprobante' => $numero_comprobante), "order" => "id_montocobrado ASC"));
        } else {
            $montos_cobrados = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and (estado = 'activo' or estado = 'indefinido')", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "id_montocobrado ASC"));
        }
        
        //Ingresará aquí cuando el documento tenga una única cuota.
        //Cuando el documento no tenga más de una cuota entonces se considera cuota única
        if(count($montos_cobrados) <= 0) {
            $moneda_nombre = '';
            if($documento->id_codigomoneda == 'PEN') {
                $moneda_nombre = 'SOLES';
            } else {
                $moneda_nombre = 'DÓLARES';
            }
            
            $cuota = array(
                'num_cuota' => 1,
                'fecha_vencimiento' => date("d-m-Y", strtotime($documento->fecha_pagopendiente)),
                'tipo_moneda'   => $moneda_nombre,
                'monto_cuota' => $documento->monto_adeudado,
                'estado_cuota' => 'pendiente'
            );

            $resp['respuesta'] = 'ok';
            $resp['total_adeudado'] = $documento->monto_adeudado;
            $resp['lista_cuotas'][] = $cuota;
            return $resp;
        }

        //pasa aquí cuando al momento de crear el comprobante, se ha seleccionado AGREGAR CUOTAS, y el comprobante tiene dos o más cuotas.
        $lista = array();
        $suma_cuotas = 0;
        $n = 0;
        foreach($montos_cobrados as $item) {
            $moneda_nombre = '';
            if($item->id_codigomoneda == 'PEN') {
                $moneda_nombre = 'SOLES';
            } else {
                $moneda_nombre = 'DÓLARES';
            }
            
            if(!empty($item->fecha_cuota)) {
                $n++;
                $estado_cuota = $item->estado;
                if($estado_cuota == 'activo') {
                    $estado_cuota = 'Pagado';
                } else if($estado_cuota == 'indefinido') {
                    $estado_cuota = 'Pendiente';
                }

                $cuota = array(
                    'num_cuota'         => $n,
                    'fecha_vencimiento' => date("d-m-Y", strtotime($item->fecha_cuota)),
                    'tipo_moneda'       => $moneda_nombre,
                    'monto_cuota'       => $item->total,
                    'estado_cuota'      => $estado_cuota
                );

                $lista[] = $cuota;
                $suma_cuotas = $suma_cuotas + $item->total;
            }
        }
        
        $resp['respuesta'] = 'ok';
        $resp['lista_cuotas'] = $lista;
        $resp['total_adeudado'] = $suma_cuotas;
        return $resp;
    }

    public function getListaAbonosAction() {
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

            $id_contribuyente = empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];
            
            $array_cpe = array('01', '03', '07', '08');
            $array_docs_nooficial = array('77');

            if(in_array($id_tipodoc_electronico, $array_cpe)) {
                $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            } else {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
            }

            $credito_informado_sunat = 'no';
            if($id_tipodoc_electronico == '01') {
                if($documento->credito_sunat == 'si') {
                    $credito_informado_sunat = 'si';
                }
            }

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos el documento electrónico';
                echo json_encode($resp);
                exit();
            }

            $resp_validacion_cuotas = $this->validar_si_tiene_cuotas_registradas($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $contribuyente, $documento);

            if(in_array($id_tipodoc_electronico, $array_cpe)) {
                $query = "SELECT * FROM `monto_cobrado` where id_contribuyente = :id_contribuyente and id_tipodoc_electronico = :id_tipodoc_electronico and serie_comprobante = :serie_comprobante and numero_comprobante = :numero_comprobante and tipo_envio_sunat = :tipo_envio_sunat and (estado = 'activo' or estado = 'indefinido') ORDER BY id_montocobrado ASC";    
            } else if(in_array($id_tipodoc_electronico, $array_docs_nooficial)) {
                $query = "SELECT * FROM `monto_cobrado` where id_contribuyente = :id_contribuyente and id_tipodoc_electronico = :id_tipodoc_electronico and numero_comprobante = :numero_comprobante and tipo_envio_sunat = :tipo_envio_sunat and (estado = 'activo' or estado = 'indefinido') ORDER BY id_montocobrado ASC";
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No es un documento válido';
                echo json_encode($resp);
                exit();
            }

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
                $sentencia->bindParam(':id_tipodoc_electronico', $id_tipodoc_electronico, PDO::PARAM_STR);
                if(in_array($id_tipodoc_electronico, $array_cpe)) {
                    $sentencia->bindParam(':serie_comprobante', $serie_comprobante, PDO::PARAM_STR);
                }
                $sentencia->bindParam(':numero_comprobante', $numero_comprobante, PDO::PARAM_INT);
                $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $lista = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $item = (object)$fila;
                if($item->id_codigomoneda == 'PEN') {
                    $simbolo_moneda = 'S/. ';
                    $tipo_moneda = 'SOLES';
                } else {
                    $simbolo_moneda = 'USD ';
                    $tipo_moneda = 'DÓLARES';
                }
                $html_nota = '';
                if(!empty($item->detalle)) {
                    $html_nota = '<img data-popup="popover" data-placement="top" title="Detalle" data-content="'.htmlspecialchars($item->detalle).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 30px; cursor: pointer;" />';
                }

                $condiciondepago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", "bind" => array('id_condicionpago' => $item->id_condicionpago)));
                $condicion_pago_html = '';

                if($item->estado == 'activo') {
                    if($condiciondepago) {
                        if($condiciondepago->tipo == 'credito') {
                            $condicion_pago_html = '<span class="label bg-danger">Crédito</span><br /> <br />';
                        } else if($condiciondepago->tipo == 'tarjeta_credito') {
                            $condicion_pago_html = '<span class="label bg-info">Tarjeta Crédito/Débito</span><br /> <br />';
                        } else if($condiciondepago->tipo == 'transferencia') {
                            $condicion_pago_html = '<span class="label bg-primary">Transferencia</span><br /> <br />';
                        } else {
                            $condicion_pago_html = '<span class="label bg-success">Contado</span><br /> <br />';
                        }
                    }
                }
                
                $fecha_cuota = $item->fecha_registro;
                $fecha_registro_cuota = '<small>F.Registro:<br /> '.date("d-m-Y", strtotime($fecha_cuota)).'</small>';
                if(!empty($item->fecha_cuota)) {
                    if(date("d-m-Y", strtotime($item->fecha_cuota)) != date("d-m-Y", strtotime($item->fecha_registro))) {
                        $fecha_cuota = $item->fecha_cuota;
                        $fecha_registro_cuota = '
                        <small>Fecha Vencimiento Cuota:<br /> '.date("d-m-Y", strtotime($fecha_cuota)).'</small>
                        ';
                    }
                }

                $estado_cuota = '';
                $img_url_ticket = '';
                
                $monto_total = $simbolo_moneda.$item->total;
                if($item->estado == 'activo') {
                    $estado_cuota = '<span class="label bg-success">Pagado</span>';
                    $img_url_ticket = '<br /><a href="/facturacionv8/download/ticket_abono/'.$item->id_montocobrado.'" target="_blank"><img src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="max-width: 30px; margin-top: 10px;"></a>';
                } else if($item->estado == 'inactivo') {
                    $estado_cuota = '<span class="label bg-danger">Eliminado</span>';
                } else if($item->estado == 'indefinido') {
                    $estado_cuota = '<span class="label bg-info">Pendiente</span>';
                    $monto_total = '<span class="text-danger">'.$simbolo_moneda.$item->total.'</span>';
                }

                $funcion_editar = "editar_abono($id_contribuyente, '$id_tipodoc_electronico', '$serie_comprobante', $numero_comprobante, $item->id_montocobrado, $item->total, '".date("d-m-Y", strtotime($fecha_cuota))."', '$credito_informado_sunat')";
                $funcion_resetear = "resetear_abono($id_contribuyente, '$id_tipodoc_electronico', '$serie_comprobante', $numero_comprobante, $item->id_montocobrado, $item->total, '".date("d-m-Y", strtotime($fecha_cuota))."', '$credito_informado_sunat')";
                $funcion_eliminar = "eliminar_abono($id_contribuyente, '$id_tipodoc_electronico', '$serie_comprobante', $numero_comprobante, $item->id_montocobrado, $item->total, '".date("d-m-Y", strtotime($fecha_cuota))."', '$credito_informado_sunat')";

                $btn_opciones = '
                <div class="btn-group">';

                if($item->estado == 'activo') {
                    //if(!empty($item->fecha_cuota)) {
                        $btn_opciones = $btn_opciones.'<button onclick="'.$funcion_eliminar.'" type="button" class="btn btn-danger legitRipple btn-xs"><i class="icon-trash"></i></button>';
                    //}
                } else if($item->estado == 'indefinido') {
                    $btn_opciones = $btn_opciones.'
                    <button onclick="'.$funcion_eliminar.'" type="button" class="btn btn-danger legitRipple btn-xs"><i class="icon-trash"></i></button>
                    <button onclick="'.$funcion_editar.'" type="button" class="btn btn-primary legitRipple btn-xs"><i class="icon-pencil3"></i></button>
                    ';
                } else {

                }

                $btn_opciones = $btn_opciones.'</div>';

                $permitir_cambio_cuotas = $this->get_contribuyente_opcion($usuario->id_contribuyente, 'permitir_cambio_cuotas');
                
                if(in_array($id_tipodoc_electronico, $array_cpe) && $credito_informado_sunat == 'si') {
                    if($permitir_cambio_cuotas == 'no' || $permitir_cambio_cuotas == '') {
                        $btn_opciones = '
                        <div class="btn-group">';
    
                            if($item->estado == 'activo') {
                                if(!empty($item->fecha_cuota)) {
                                    $btn_opciones = $btn_opciones.'<button title="Resetear Cuota: Regresa la Cuota a su Estado Original de Pendiente de Pago" onclick="'.$funcion_resetear.'" type="button" class="btn btn-primary legitRipple btn-xs"><i class="icon-loop3"></i></button>';
                                }
                            } else {
                                $btn_opciones = $btn_opciones.'<button onclick="'.$funcion_editar.'" type="button" class="btn btn-primary legitRipple btn-xs"><i class="icon-pencil3"></i></button>';
                            }
                        
                        $btn_opciones = $btn_opciones.'
                        </div>';
                    } else {
                        $btn_opciones = '
                        <div class="btn-group">';
    
                            if($item->estado == 'activo') {
                                if(!empty($item->fecha_cuota)) {
                                    $btn_opciones = $btn_opciones.'
                                    <button title="Resetear Cuota: Regresa la Cuota a su Estado Original de Pendiente de Pago" onclick="'.$funcion_resetear.'" type="button" class="btn btn-primary legitRipple btn-xs"><i class="icon-loop3"></i></button>';
                                }
                            } else {
                                $btn_opciones = $btn_opciones.'
                                <button onclick="'.$funcion_eliminar.'" type="button" class="btn btn-danger legitRipple btn-xs"><i class="icon-trash"></i></button>
                                <button onclick="'.$funcion_editar.'" type="button" class="btn btn-primary legitRipple btn-xs"><i class="icon-pencil3"></i></button>';
                            }
                        
                        $btn_opciones = $btn_opciones.'
                        </div>';
                    }
                }
                
                
                
                $n++;
                $lista[] = array(
                    $n,
                    $fecha_registro_cuota,
                    $tipo_moneda,
                    $condicion_pago_html.
                    $monto_total.$html_nota,
                    '<div class="text-center">'.$estado_cuota.$img_url_ticket.'</div>',
                    $btn_opciones
                );
            }

            $monto_adeudado = 0;
            if(!empty($documento->monto_adeudado) && floatval($documento->monto_adeudado) > 0) {
                $monto_adeudado = $documento->monto_adeudado;
            }

            $simbolo_moneda_doc = '';
            if($documento->id_codigomoneda == 'PEN') {
                $simbolo_moneda_doc = 'S/. ';
            } else {
                $simbolo_moneda_doc = 'USD ';
            }

            $html_nota_lista = '';
            if($monto_adeudado > 0) {
                $serie_documento_mensaje = empty($serie_comprobante)?'N° '.$numero_comprobante:$serie_comprobante.'-'.$numero_comprobante;
                $html_nota_lista = 'El monto total del documento electrónico <strong>'.$serie_documento_mensaje.'</strong> es de '.$simbolo_moneda_doc.' '.$documento->total.', y se han registrado abonos por un total de '.$simbolo_moneda_doc.' '.($documento->total - $monto_adeudado).'. Aún queda una deuda de '.$simbolo_moneda_doc.' '.$monto_adeudado.'.';
            } else {
                $html_nota_lista = 'El documento electrónico ha sido pagado en su totalidad.';
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            $resp['total_doc'] = $documento->total;
            $resp['total_abonado'] = $documento->total - $monto_adeudado;
            $resp['monto_adeudado'] = $monto_adeudado;
            $resp['html_nota_lista'] = $html_nota_lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function validar_si_tiene_cuotas_registradas($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $contribuyente, $documento) {
        $array_cpe = array('01', '03', '07', '08');
        $array_docs_nooficial = array('77');

        if(in_array($id_tipodoc_electronico, $array_cpe)) {
            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
        } else {
            $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
        }

        if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
            $montos_cobrados = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'numero_comprobante' => $numero_comprobante)));
        } else {
            $montos_cobrados = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
        }

        if(in_array($id_tipodoc_electronico, $array_cpe)) {
            if(count($montos_cobrados) <= 0) {
                if($contribuyente->informar_condpago_sunat == 'si') {
                    $cuota_credito = new MontoCobrado();
                    $cuota_credito->id_contribuyente  = $id_contribuyente;
                    $cuota_credito->id_tipodoc_electronico = $id_tipodoc_electronico;
                    $cuota_credito->serie_comprobante = $serie_comprobante;
                    $cuota_credito->numero_comprobante = $numero_comprobante;
                    $cuota_credito->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
                    $cuota_credito->id_vendedor = $documento->id_vendedor;
                    $cuota_credito->id_sucursal = $documento->id_sucursal;
                    $cuota_credito->id_codigomoneda =  $documento->id_codigomoneda;

                    
                    $cuota_credito->total = floatval($documento->monto_adeudado);
                    $cuota_credito->fecha_registro = date('Y-m-d H:i:s');
                    $cuota_credito->fecha_cuota = date("Y-m-d", strtotime($documento->fecha_pagopendiente));
                    $cuota_credito->estado = 'indefinido';
                    $resp_save_credito = $cuota_credito->save();
                }
            }
        }

        return true;
    }

    public function resetearCuotaAction() {
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

            $id_abono = empty($datapost['id_abono'])?0:$datapost['id_abono'];
            $id_contribuyente = empty($datapost['idcontribuyente'])?0:$datapost['idcontribuyente'];
            $id_tipodoc_electronico = empty($datapost['tipo_doc'])?'':$datapost['tipo_doc'];
            $serie_comprobante = empty($datapost['serie_doc'])?'':$datapost['serie_doc'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];

            if((in_array($id_tipodoc_electronico, array('01', '03', '07', '08')))) {
                $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            } else {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => '77', 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
            }

            if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
                $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'numero_comprobante' => $numero_comprobante)));
            } else {
                $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            }

            if(!$monto_cobrado) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El abono que intentas resetear no existe.';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
                $resp['mensaje'] = 'El estado de la cuota cambiará a: PENDIENTE DE PAGO ¿Deseas Continuar?';
				echo json_encode($resp);
				exit();
            }

            $this->db->begin();

            if($id_tipodoc_electronico == '01') {
                if($documento->credito_sunat == 'si') {
                    if(empty($monto_cobrado->fecha_cuota)) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Este abono se ha considerado como pago al contado al momento de crear la FACTURA, por tanto no se permite eliminar o resetear!';
                        echo json_encode($resp);
                        exit();
                    }

                    $monto_cobrado->estado = 'indefinido';
                    
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
                        $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                        echo json_encode($resp);
                        exit();
                    }

                    $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), reseteó la cuota con ID: '.$monto_cobrado->id_montocobrado.' en la fecha: '.date('d-m-Y / H:i A').'.';
                    $documento->monto_adeudado = round($documento->monto_adeudado + $monto_cobrado->total, 2);
                    $documento->log_condicion_pago = (empty($documento->log_condicion_pago))?$log_condicion_pago:$documento->log_condicion_pago.'<br'.$log_condicion_pago;

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
                        $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                        echo json_encode($resp);
                        exit();
                    }

                    $this->db->commit();
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Cuota Reseteada';
                    $resp['mensaje'] = 'Se ha cambiado el estado a PENDIENTE DE PAGO';
                    echo json_encode($resp);
                    exit();
                }
            }

            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El abono no se puede resetear, por favor elimine el abono!';
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarAbonoAction() {
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

            $id_abono = empty($datapost['id_abono'])?0:$datapost['id_abono'];
            $id_contribuyente = empty($datapost['idcontribuyente'])?0:$datapost['idcontribuyente'];
            $id_tipodoc_electronico = empty($datapost['tipo_doc'])?'':$datapost['tipo_doc'];
            $serie_comprobante = empty($datapost['serie_doc'])?'':$datapost['serie_doc'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];

            if((in_array($id_tipodoc_electronico, array('01', '03', '07', '08')))) {
                $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            } else {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => '77', 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
            }

            if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
                $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'numero_comprobante' => $numero_comprobante)));
            } else {
                $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            }

            if(!$monto_cobrado) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El abono que intentas modificar no existe.';
                echo json_encode($resp);
                exit();
            }

            if($id_tipodoc_electronico == '01') {
                if($documento->credito_sunat == 'si') {
                    $permitir_cambio_cuotas = $this->get_contribuyente_opcion($usuario->id_contribuyente, 'permitir_cambio_cuotas');
                    if($permitir_cambio_cuotas == 'no' || $permitir_cambio_cuotas == '') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Se informó el crédito a SUNAT con sus respectivas cuotas y fechas de vencimiento, por tanto no se permite eliminar una cuota.';
                        echo json_encode($resp);
                        exit();
                    }
                }
            }

            if($monto_cobrado->estado == 'inactivo') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El abono ya se encuentra eliminado.';
                echo json_encode($resp);
                exit();
            }

            /*
            if(empty($monto_cobrado->fecha_cuota)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se permite eliminar este abono.';
                echo json_encode($resp);
                exit();
            }
            */

            $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
			if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
                $resp['mensaje'] = '¿Realmente Deseas Eliminar Esta Cuota?, no se podrá deshacer esta acción.';
				echo json_encode($resp);
				exit();
            }

            $this->db->begin();

            $monto_cobrado->estado = 'inactivo';
            
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
                $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), eliminó la cuota con ID: '.$monto_cobrado->id_montocobrado.' en la fecha: '.date('d-m-Y / H:i A').'.';
            $monto_total_cobrado = $this->get_total_pagado($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $contribuyente->tipo_envio_sunat);
            $documento->monto_adeudado = $documento->total - $monto_total_cobrado;
            $documento->log_condicion_pago = (empty($documento->log_condicion_pago))?$log_condicion_pago:$documento->log_condicion_pago.'<br'.$log_condicion_pago;

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
                $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['monto_total_cobrado'] = $monto_total_cobrado;
            $resp['titulo'] = 'Cuota Eliminada';
            $resp['mensaje'] = 'Se ha eliminado correctamente la cuota elimina';
            echo json_encode($resp);
            exit();
        }
    }

    public function get_total_pagado($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat) {
        if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
            $montos_cobrados = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'activo'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'numero_comprobante' => $numero_comprobante)));
        } else {
            $montos_cobrados = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'activo'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
        }

        $total_pagado = 0;

        foreach($montos_cobrados as $monto) {
            if($monto->estado == 'activo') {
                $total_pagado = $total_pagado + $monto->total;
            }
        }

        return $total_pagado;
    }

    public function guardarAbonoAction() {
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

            $herramientas = new HerramientasController;

            //aquí se debería verificar los permisos

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            $id_abono = empty($datapost['id_abono'])?0:intval($datapost['id_abono']);
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

            $gestion_usuarios = new GestionuserController;
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_cuentascobrar') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_cuentascobrar') == 'prohibir') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No tienes permisos para registrar abonos!';
                    echo json_encode($resp);
                    exit();
                }

                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_cuentascobrar') == 'sucursal_asignada') {
                    if($usuario->idsucursal != $documento->id_sucursal) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'No tienes permisos para registrar abonos en este documento!';
                        echo json_encode($resp);
                        exit();
                    }
                }
            }

            $monto_adeudado = $documento->monto_adeudado;
            if($monto_adeudado <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Actualmente no existe una deuda pendiente para este documento!';
                echo json_encode($resp);
                exit();
            }
            
            $idcuenta_banco_deposito = null;
		    $fecha_deposito_transferencia = null;

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

            $simbolo_moneda = 'S/ ';
            if($documento->id_codigomoneda == 'USD') {
                $simbolo_moneda = 'USD $ ';
            }

            $this->db->begin();

            //aquí verificamos si se trata de una factura
            //si es una factura verificamos si se ha informado el crédito a sunat
            //si se ha informado el crédito a sunat, entonces únicamente permitimos la edición del abono
            if($id_tipodoc_electronico == '01') {
                if($documento->credito_sunat == 'si') {
                    $permitir_cambio_cuotas = $this->get_contribuyente_opcion($usuario->id_contribuyente, 'permitir_cambio_cuotas');
                    if($permitir_cambio_cuotas == 'no' || $permitir_cambio_cuotas == '') {
                        if($id_abono <= 0) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'La forma de pago al crédito ha sido informada a SUNAT, por tanto no se deben agregar nuevas cuotas y tampoco se deben eliminar las cuotas!';
                            echo json_encode($resp);
                            exit();
                        }
    
                        //si el documento ha sido informado a sunat, entonces debería existir un registro en la tabla monto_cobrado
                        $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
    
                        if(!$monto_cobrado) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'No existe la cuota que intenga asignar como pagada.';
                            echo json_encode($resp);
                            exit();
                        }
    
                        if($monto_cobrado->estado == 'activo') {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'La cuota que intenta editar, ya tiene un estado PAGADO, primero debe resetear la cuota.';
                            echo json_encode($resp);
                            exit();
                        }
    
                        $monto_cobrado_anterior = MontoCobrado::findFirst(array("id_montocobrado < :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
    
                        if($monto_cobrado_anterior) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Existe una cuota anterior pendiente de pago, las cuotas se deben cancelar de forma ordenada!.';
                            echo json_encode($resp);
                            exit();
                        }
    
                        $confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
                        if($confirmacion != 'si') {
                            $this->db->rollback();
                            $resp['respuesta'] = 'ok';
                            $resp['mensaje'] = 'El estado de la cuota pasará a PAGADA. ¿Deseas Continuar?';
                            $resp['titulo'] = 'Necesitamos tu Confirmación!';
                            echo json_encode($resp);
                            exit();
                        }
                        
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
                            $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                            echo json_encode($resp);
                            exit();
                        }
    
                        $monto_a_pagar = $monto_cobrado->total;
                        $fecha_prox_pago = null;
                        if(($monto_adeudado - $monto_a_pagar) < 0) { //mostrar vuelto
                            $nuevo_monto_adeudado = 0;
                        } else if (($monto_adeudado - $monto_a_pagar) == 0) {
                            $nuevo_monto_adeudado = 0;
                        } else if (($monto_adeudado - $monto_a_pagar) > 0) { //seleccionar nueva fecha
                            $nuevo_monto_adeudado = round($monto_adeudado - $monto_a_pagar, 2);
                            //extraer la fecha del próximo pago (primera próxima cuota)
                            $siguiente_abono_pendiente = MontoCobrado::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_cuota ASC"));
    
                            $fecha_prox_pago = date("Y-m-d", strtotime($siguiente_abono_pendiente->fecha_cuota));
                        }
    
                        if(empty($fecha_prox_pago)) {
                            $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), agregó un pago de: '.$simbolo_moneda.$monto_a_pagar.', quedando una nueva deuda de: '.$simbolo_moneda.($monto_adeudado - $monto_a_pagar).'. Se registró esta operación el día: '.date('d-m-Y / H:i A');
                        } else {
                            $log_condicion_pago = 'El Usuario '.$usuario->nombre.' (ID: '.$usuario->idusuario.'), agregó un pago de: '.$simbolo_moneda.$monto_a_pagar.', quedando una nueva deuda de: '.$simbolo_moneda.($monto_adeudado - $monto_a_pagar).'. Se registró esta operación el día: '.date('d-m-Y / H:i A').', Fecha próxima cuota: '.date('d-m-Y', strtotime($fecha_prox_pago)).'.';
                        }
                        
                        
                        $documento->monto_adeudado = $nuevo_monto_adeudado;
                        $documento->fecha_pagopendiente = $fecha_prox_pago;
                        $documento->log_condicion_pago = (empty($documento->log_condicion_pago))?$log_condicion_pago:$documento->log_condicion_pago.'<br>'.$log_condicion_pago;
                        
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
                            $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                            echo json_encode($resp);
                            exit();
                        }
    
                        $this->db->commit();
                        $resp['respuesta'] = 'ok';
                        $resp['titulo'] = 'OK';
                        $resp['mensaje'] = 'Hemos guardado correctamente los datos!...';
                        $resp['id_montocobrado'] = ($monto_cobrado)?$monto_cobrado->id_montocobrado:0;
                        echo json_encode($resp);
                        exit();   
                    }
                }
            }
                
            //Si llega aquí, quiere decir que se trata de un comprobante donde el crédito no se ha informado a SUNAT
            //puede ser una FACTURA, BOLETA, NOTA DE VENTA U OTRO.

            //1.- Es importante verificar que el total de las cuotas registradas no supere el monto adeudado.
            if($id_abono > 0) {
                //si el documento ha sido informado a sunat, entonces debería existir un registro en la tabla monto_cobrado
                if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
                    $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
                } else {
                    $monto_cobrado = MontoCobrado::findFirst(array("id_montocobrado = :id_montocobrado: and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", "bind" => array('id_montocobrado' => $id_abono, 'id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
                }

                if(!$monto_cobrado) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El abono que intentas modificar no existe.';
                    echo json_encode($resp);
                    exit();
                }

                if($monto_cobrado->estado == 'activo') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La cuota que intenta editar, ya tiene un estado PAGADO, elimina la cuota para agregar una nueva.';
                    echo json_encode($resp);
                    exit();
                }

                $monto_a_pagar = $monto_cobrado->total;

                //verificando los totales de todos los abonos
                if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
                    $montos_cobrados_pendientes = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
                } else {
                    $montos_cobrados_pendientes = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
                }

                $suma_pagados_y_pendientes = 0;
                $num_cuotas_pendientes = 0;
                $num_cuotas_estado_pendiente = 0;
                $num_cuotas_estado_pagado = 0;
                foreach($montos_cobrados_pendientes as $item_pagado_pendiente) {
                    $suma_pagados_y_pendientes = $suma_pagados_y_pendientes + $item_pagado_pendiente->total;
                    $num_cuotas_pendientes++;

                    if($item_pagado_pendiente->estado == 'activo') {
                        $num_cuotas_estado_pagado++;
                    }

                    if($item_pagado_pendiente->estado == 'indefinido') {
                        $num_cuotas_estado_pendiente++;
                    }
                }

                if($suma_pagados_y_pendientes > $documento->monto_adeudado) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La Suma de las Cuotas está superando el monto adeudado... el monto total pendiente de pago es: '.$simbolo_moneda.' '.$documento->monto_adeudado.' y la suma de todas las cuotas pagadas y pendientes es de: '.$simbolo_moneda.' '.$suma_pagados_y_pendientes.'.';

                    if($num_cuotas_estado_pagado <= 0 && $num_cuotas_estado_pendiente <= 0) {
                        
                    } else if($num_cuotas_estado_pagado <= 0 && $num_cuotas_estado_pendiente > 0) {
                        $resp['mensaje'] = "Verificamos que tienes $num_cuotas_estado_pendiente cuotas registradas y pendientes de pago, y que en total suman $simbolo_moneda $suma_pagados_y_pendientes, no podemos agregar esta cuota porque estaría superando el monto pendiente de pago que es de: $simbolo_moneda $documento->monto_adeudado.";
                    } else if($num_cuotas_estado_pagado > 0 && $num_cuotas_estado_pendiente <= 0) {
                        $resp['mensaje'] = "Verificamos que tienes $num_cuotas_estado_pagado cuotas registradas y pagadas, y que en total suman $simbolo_moneda $suma_pagados_y_pendientes, no podemos agregar esta cuota porque estaría superando el monto pendiente de pago que es de: $simbolo_moneda $documento->monto_adeudado.";
                    } else if($num_cuotas_estado_pagado > 0 && $num_cuotas_estado_pendiente > 0) {
                        $resp['mensaje'] = "Verificamos que tienes $num_cuotas_estado_pagado cuotas pagadas y $num_cuotas_estado_pendiente cuotas pendientes de pago, y que en total suman $simbolo_moneda $suma_pagados_y_pendientes, no podemos agregar esta cuota porque estaría superando el monto pendiente de pago que es de: $simbolo_moneda $documento->monto_adeudado.";
                    } else {
                        
                    }
                    
                    echo json_encode($resp);
                    exit();
                }
            } else {
                //verificando los totales de todos los abonos
                if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
                    $montos_cobrados_pendientes = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
                } else {
                    $montos_cobrados_pendientes = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
                }

                $suma_pagados_y_pendientes = 0;
                $num_cuotas_pendientes = 0;
                $num_cuotas_estado_pendiente = 0;
                $num_cuotas_estado_pagado = 0;
                foreach($montos_cobrados_pendientes as $item_pagado_pendiente) {
                    $suma_pagados_y_pendientes = $suma_pagados_y_pendientes + $item_pagado_pendiente->total;
                    $num_cuotas_pendientes++;

                    if($item_pagado_pendiente->estado == 'activo') {
                        $num_cuotas_estado_pagado++;
                    }

                    if($item_pagado_pendiente->estado == 'indefinido') {
                        $num_cuotas_estado_pendiente++;
                    }
                }

                $suma_pagados_y_pendientes = $suma_pagados_y_pendientes + $monto_a_pagar;
                if($suma_pagados_y_pendientes > $documento->monto_adeudado) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La Suma de las Cuotas está superando el monto adeudado... el monto total pendiente de pago es: '.$simbolo_moneda.' '.$documento->monto_adeudado.' y la suma de todas las cuotas pagadas y pendientes es de: '.$simbolo_moneda.' '.$suma_pagados_y_pendientes.'.';

                    if($num_cuotas_estado_pagado <= 0 && $num_cuotas_estado_pendiente <= 0) {
                        
                    } else if($num_cuotas_estado_pagado <= 0 && $num_cuotas_estado_pendiente > 0) {
                        $resp['mensaje'] = "Verificamos que tienes $num_cuotas_estado_pendiente cuotas registradas y pendientes de pago, y que en total suman $simbolo_moneda ".($suma_pagados_y_pendientes - $monto_a_pagar)." más esta nueva cuota de $simbolo_moneda $monto_a_pagar sería en total $suma_pagados_y_pendientes, y superaría el monto adeudado de $simbolo_moneda $documento->monto_adeudado, por tanto no podemos continuar con el registro de la cuota actual.";
                    } else if($num_cuotas_estado_pagado > 0 && $num_cuotas_estado_pendiente <= 0) {
                        //no pasará (porque solo se filtra indefinidos)
                        $resp['mensaje'] = "Verificamos que tienes $num_cuotas_estado_pagado cuotas registradas y pagadas, y que en total suman $simbolo_moneda ".($suma_pagados_y_pendientes - $monto_a_pagar)." más esta nueva cuota de $simbolo_moneda $monto_a_pagar sería en total $suma_pagados_y_pendientes, y superaría el monto adeudado de $simbolo_moneda $documento->monto_adeudado, por tanto no podemos continuar con el registro de la cuota actual.";
                    } else if($num_cuotas_estado_pagado > 0 && $num_cuotas_estado_pendiente > 0) {
                        //no pasará (porque solo se filtra indefinidos)
                        $resp['mensaje'] = $resp['mensaje'] = "Verificamos que tienes $num_cuotas_estado_pendiente cuotas pendientes de pago y $num_cuotas_estado_pagado cuotas pagadas, y que en total suman $simbolo_moneda ".($suma_pagados_y_pendientes - $monto_a_pagar)." más esta nueva cuota de $simbolo_moneda $monto_a_pagar sería en total $suma_pagados_y_pendientes, y superaría el monto adeudado de $simbolo_moneda $documento->monto_adeudado, por tanto no podemos continuar con el registro de la cuota actual.";
                    } else {
                        
                    }
                    
                    echo json_encode($resp);
                    exit();
                }
                
                $monto_cobrado = new MontoCobrado();
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

                if($id_abono > 0) {
                    $sql_siguiente_abono_pendiente = "id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido' and id_montocobrado <> $id_abono";
                } else {
                    $sql_siguiente_abono_pendiente = "id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = 'indefinido'";
                }
                
                $siguiente_abono_pendiente = MontoCobrado::findFirst(array($sql_siguiente_abono_pendiente, "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_cuota ASC"));

                if($siguiente_abono_pendiente) {
                    $fecha_prox_pago = date("Y-m-d", strtotime($siguiente_abono_pendiente->fecha_cuota));
                } else {
                    
                    $array_fecha_prox_pago = explode('/',!isset($fecha_proximo_pago)?date('d/m/Y'):$fecha_proximo_pago);
                    $fecha_prox_pago = $array_fecha_prox_pago[2].'-'.$array_fecha_prox_pago[1].'-'.$array_fecha_prox_pago[0];
                    if (!(DateTime::createFromFormat('Y-m-d', $fecha_prox_pago) !== FALSE)) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La Fecha Seleccionada para el próximo pago no es válida!';
                        echo json_encode($resp);
                        exit();
                    }

                    //$fecha_prox_pago = date("d-m-Y",strtotime(date("d-m-Y")."+ 7 days"));
                }
                
                $resp_fecha_2 = $herramientas->comparar_fechas($fecha_prox_pago, date('Y-m-d'));
				if($resp_fecha_2['diferencia_primera_segunda'] <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La próxima fecha ('.$fecha_prox_pago.') de pago del monto adeudado debe ser mayor a la fecha actual, por favor verifique!';
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
            
            $monto_abono = 0;
            if(($monto_adeudado - $monto_a_pagar) < 0) { //mostrar vuelto
                $documento->monto_adeudado = 0;
                //$documento->id_condicionpago = null;
                $documento->log_condicion_pago = (empty($documento->log_condicion_pago))?$log_condicion_pago:$documento->log_condicion_pago.'<br'.$log_condicion_pago;
                $monto_abono = $monto_adeudado;
            } else if (($monto_adeudado - $monto_a_pagar) == 0) {
                $documento->monto_adeudado = 0;
                //$documento->id_condicionpago = null;
                $documento->log_condicion_pago = (empty($documento->log_condicion_pago))?$log_condicion_pago:$documento->log_condicion_pago.'<br'.$log_condicion_pago;
                $monto_abono = $monto_adeudado;
            } else if (($monto_adeudado - $monto_a_pagar) > 0) { //seleccionar nueva fecha
                $documento->monto_adeudado = round($monto_adeudado - $monto_a_pagar, 2);
                $documento->log_condicion_pago = (empty($documento->log_condicion_pago))?$log_condicion_pago:$documento->log_condicion_pago.'<br'.$log_condicion_pago;
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
                $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }
            
            if($monto_abono > 0) {
                if($id_abono > 0) {
                    if($id_condicionpago > 0) {
                        $monto_cobrado->id_condicionpago = $id_condicionpago;
                    }

                    if(!empty($numero_operacion)) {
                        $monto_cobrado->cpago_nrooperacion = $numero_operacion;
                    }

                    if(!empty($txt_observacion_abono)) {
                        $monto_cobrado->detalle = $txt_observacion_abono;
                    }
                    
                    if(!empty($fecha_deposito_transferencia)) {
                        $monto_cobrado->fechadeposito = $fecha_deposito_transferencia;
                    }
                    
                    if(!empty($idcuenta_banco_deposito)) {
                        $monto_cobrado->idbanco = $idcuenta_banco_deposito;
                    }
                    
                    $monto_cobrado->id_vendedor = $usuario->idusuario; //se debe asignar el abono a quien registra el abono.
                    $monto_cobrado->fecha_registro = date("Y-m-d H:i:s");
                    $monto_cobrado->estado = 'activo';
                    
                } else {
                    $monto_cobrado = new MontoCobrado();
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
                }
                
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
                    $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                    echo json_encode($resp);
                    exit();
                }
            }

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'Hemos guardado correctamente los datos.!';
            $resp['id_montocobrado'] = ($monto_cobrado)?$monto_cobrado->id_montocobrado:0;
            echo json_encode($resp);
            exit();
        }
    }
}