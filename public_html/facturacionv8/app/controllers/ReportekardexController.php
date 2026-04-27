<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportekardexController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Reporte Kardex');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")

            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v4")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v4")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v4")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v4")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v4")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/apisunat.js?i=v2") 
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/kardexreporte.js?j=".rand());

  
          
    }

    public function kardexdeproductoAction($idproducto = 0) {
        $this->setTitle('Reporte Kardex');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
            ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v4")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v4")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v4")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v4")
			->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v4")
            
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/reportekardex/kardexdeproducto.js?j=".rand());

        $fecha_actual = date("Y-m-d H:i:s");
        $fecha_inicio_b = date("d/m/Y", strtotime("-365 days", strtotime($fecha_actual)));
        $fecha_inicio_2 = date("Y-m-d", strtotime("-365 days", strtotime($fecha_actual)));
        $fecha_fin_b = date("d/m/Y");
        $fecha_fin_b_2 = date("Y-m-d");

        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $msj['respuesta'] = 'error';
            $msj['titulo'] = 'Error en Usuario';
            $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
            echo json_encode($msj);
            exit();
        }

        $sucursal_seleccionada = 0;
        if($idproducto > 0) {
            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente,'idproducto' => $idproducto)));
            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El producto no existe!';
                echo json_encode($resp);
                exit();
            }

            $this->view->idproducto = $producto->idproducto;
            $this->view->nombreproducto = $producto->nombre;
            $sucursal_seleccionada = $producto->idsucursal;
            $fecha_inicio_b = date("d/m/Y", strtotime($producto->fecha_registro));
            $fecha_inicio_2 = date("Y-m-d", strtotime($producto->fecha_registro));
        } else {
            $this->view->idproducto = '';
            $this->view->nombreproducto = '';
        }

        $this->view->fecha_inicio = $fecha_inicio_b;
        $this->view->fecha_fin = $fecha_fin_b;
        $this->view->fecha_inicio_2 = $fecha_inicio_2;
        $this->view->fecha_fin_2 = $fecha_fin_b_2;

        $gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_reportes', 'kardex') == false) {
                    $this->response->redirect('dashboard');
                }
            }
        }

        $this->view->lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $this->view->sucursal_seleccionada = $sucursal_seleccionada;
        
    }

    public function getKardexProductoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Usuario';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
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
            
            $idproducto = empty($datapost['idproducto'])?0:$datapost['idproducto'];
            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente,'idproducto' => $idproducto)));
            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El producto no existe!';
                echo json_encode($resp);
                exit();
            }
            
            $idsucursal = empty($datapost['idsucursal'])?$usuario->idsucursal:$datapost['idsucursal'];
            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $idsucursal = $producto->idsucursal;
            }

            $fecha_inicio = date("Y-m-d", strtotime($datapost['fecha_inicio']));
            $fecha_fin = date("Y-m-d", strtotime($datapost['fecha_fin']));
            
            $kardex = new KardexController;
            $resp = $kardex->get_kardex_detallado($usuario->idusuario, $idproducto, $idsucursal, $fecha_inicio, $fecha_fin);
            $resp['producto'] = $producto;
            echo json_encode($resp);
            exit();
        }
    }

    public function getInvValorizadoSunatProdAction($idproducto = 0, $idsucursal = 0, $fecha_inicio = '', $fecha_fin = '') {
		$this->view->disable();
        
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $msj['respuesta'] = 'error';
            $msj['titulo'] = 'Error en Usuario';
            $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
            echo json_encode($msj);
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
        
        $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente,'idproducto' => $idproducto)));
        if(!$producto) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El producto no existe!';
            echo json_encode($resp);
            exit();
        }
        
        $idsucursal = $producto->idsucursal;
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
        
        $kardex = new KardexController;
        $resp = $kardex->get_kardex_detallado($usuario->idusuario, $idproducto, $idsucursal, $fecha_inicio, $fecha_fin);
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("FORMATO 13.1: REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO")
            ->setSubject("FORMATO 13.1: REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO")
            ->setDescription(
                "FORMATO 13.1: REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO"
            )
            ->setKeywords("facturalaya.com, kardex valorizado, inventario, inventario permanente, formato")
            ->setCategory("Contabilidad");



        $spreadsheet->setActiveSheetIndex(0)->setTitle("Form.13.1 Invent. Valorizado");

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:N1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A2:N2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A3:N3");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A4:N4");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A5:N5");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A6:N6");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A7:N7");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A8:N8");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A9:N9");

        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", "PERIODO: ")
                    ->setCellValue("A2", "RUC: ".$contribuyente->ruc)
                    ->setCellValue("A3", "RAZÓN SOCIAL: ".$contribuyente->razon_social)
                    ->setCellValue("A4", "ESTABLECIMIENTO: ".$sucursal->codigo)
                    ->setCellValue("A5", "CÓDIGO DE LA EXISTENCIA: ".$producto->codigo)
                    ->setCellValue("A6", "TIPO: 01")
                    ->setCellValue("A7", "DESCRIPCIÓN: ".$producto->nombre)
                    ->setCellValue("A8", "CÓDIGO UNIDAD MEDIDA: ".$producto->id_unidad_medida)
                    ->setCellValue("A9", "MÉTODO DE VALUACIÓN: Promedio Ponderado");

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A12:D13");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("E12:E14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("F12:F12");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("I12:K12");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("L12:N12");

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("F13:F14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("G13:G14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("H13:H14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("I13:I14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("J13:J14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("K13:K14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("L13:L14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("M13:M14");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("N13:N14");
        
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A12:N14')->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A12:N14')->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A12:N14')->getFont()->setBold(true);
        $estilo_row_nota = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF')
            )
        );
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A12:N14')->applyFromArray($estilo_row_nota);
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A12:N14')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');

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

        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A12", "Doc. Traslado, CPE, Documento Interno")
                    ->setCellValue("A14", "FECHA")
                    ->setCellValue("B14", "TIPO")
                    ->setCellValue("C14", "SERIE")
                    ->setCellValue("D14", "NÚMERO")

                    ->setCellValue("E12", "Tipo Operación")

                    ->setCellValue("F12", "ENTRADAS")
                    ->setCellValue("F13", "CANTIDAD")
                    ->setCellValue("G13", "COSTO UNITARIO")
                    ->setCellValue("H13", "COSTO TOTAL")

                    ->setCellValue("I12", "SALIDAS")
                    ->setCellValue("I13", "CANTIDAD")
                    ->setCellValue("J13", "COSTO UNITARIO")
                    ->setCellValue("K13", "COSTO TOTAL") //ruc

                    ->setCellValue("L12", "SALDO FINAL")
                    ->setCellValue("L13", "CANTIDAD")
                    ->setCellValue("M13", "COSTO UNITARIO")   //NO
                    ->setCellValue("N13", "COSTO TOTAL");

        $i = 13;
        foreach($resp['lista'] as $item) {
            $i++;
            $fila = (object)$item;
            
            $costo_unitario_entrada = floatval(str_replace('S/ ', '', $fila->costo_unitario_entrada));
            $costo_unitario_entrada = (empty($costo_unitario_entrada) || $costo_unitario_entrada == 0)?'0':$costo_unitario_entrada;
            
            $costo_total_entrada = floatval(str_replace('S/ ', '', $fila->costo_total_entrada));
            $costo_total_entrada = (empty($costo_total_entrada) || $costo_total_entrada == 0)?'0':$costo_total_entrada;

            $costo_unitario_salida = floatval(str_replace('S/ ', '', $fila->costo_unitario_salida));
            $costo_unitario_salida = (empty($costo_unitario_salida) || $costo_unitario_salida == 0)?'0':$costo_unitario_salida;

            $costo_total_salida = floatval(str_replace('S/ ', '', $fila->costo_total_salida));
            $costo_total_salida = (empty($costo_total_salida) || $costo_total_salida == 0)?'0':$costo_total_salida;

            $costo_promedio_unitario = floatval(str_replace('S/ ', '', $fila->costo_promedio_unitario));
            $costo_promedio_unitario = (empty($costo_promedio_unitario) || $costo_promedio_unitario == 0)?0:$costo_promedio_unitario;

            $stock_valorizado = floatval(str_replace('S/ ', '', $fila->stock_valorizado));
            $stock_valorizado = (empty($stock_valorizado) || $stock_valorizado == 0)?0:$stock_valorizado;

            $stock_valorizado = round($costo_promedio_unitario*$fila->stock_actual, 2);

            $spreadsheet->getActiveSheet()->fromArray(array(
				/*A*/$fila->fecha,
				/*B*/$fila->tipo_doc_electronico,
				/*C*/$fila->serie_documento,
				/*D*/$fila->correlativo_documento,
				/*E*/$fila->tipo_operacion,
				/*F*/$fila->cantidad_entrada,
				/*G*/$costo_unitario_entrada,
				/*H*/$costo_total_entrada,
				/*I*/$fila->cantidad_salida,
				/*J*/$costo_unitario_salida,
				/*K*/$costo_total_salida,
				/*L*/$fila->stock_actual,
				/*M*/$costo_promedio_unitario,
				/*N*/$stock_valorizado
			), null, 'A'.($i+1));
        }
        
        $nombre_archivo = 'Fomato 13_1 Inventario Permanente Valorizado';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
	}
}
?>