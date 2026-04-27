<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportacioncpeController extends ControllerBase {

    public function indexAction() {

    }

    public function plantillaImportacionCpeAction() {

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
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("facturalaya.com - Alex Castaneda")
            ->setLastModifiedBy("facturalaya.com - Alex Castaneda")
            ->setTitle("Plantilla Importación CPE")
            ->setSubject("Plantilla para la Importación de CPE")
            ->setDescription(
                "Esta plantilla es la base para importar documentos electrónicos como Facturas y Boletas."
            )
            ->setKeywords("facturalaya.com, código fuente, plantilla importación, importar cpe")
            ->setCategory("importacion cpe");
            
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Plantilla CPE");

        //$spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:A2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("B1:B2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("C1:I1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("J1:P1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("Q1:W1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A3:W3");

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tipo CPE')
            ->setCellValue('A2', 'FACTURA/BOLETA')
            ->setCellValue('B1', "N° CPE")

            //cabecera del documento
            ->setCellValue('C1', 'CABECERA DEL DOCUMENTO')

            ->setCellValue('C2', 'Cod.Moneda')
            ->setCellValue('D2', 'Id.Sucursal')
            ->setCellValue('E2', 'Id.Cond.Pago')
            ->setCellValue('F2', 'Fecha Documento')
            ->setCellValue('G2', 'Nro.Placa')
            ->setCellValue('H2', 'Nro.Orden')
            ->setCellValue('I2', 'Observación')

            //Datos del Cliente
            ->setCellValue('J1', 'DATOS DEL CLIENTE')

            ->setCellValue('J2', 'Tipo.Doc.Cliente')
            ->setCellValue('K2', 'Nro.Doc.Cliente')
            ->setCellValue('L2', 'Nombre o Razón Social Cliente')
            ->setCellValue('M2', 'Email')
            ->setCellValue('N2', 'Dirección')
            ->setCellValue('O2', 'Sexo')
            ->setCellValue('P2', 'Celular')

            //Detalle
            ->setCellValue('Q1', 'ITEM DEL DOCUMENTO')

            ->setCellValue('Q2', 'Id.Prod.Servicio')
            ->setCellValue('R2', 'Id.Afectación')
            ->setCellValue('S2', 'Nombre Producto/Servicio')
            ->setCellValue('T2', 'Id.Unidad.Medida')
            ->setCellValue('U2', 'Precio Venta')
            ->setCellValue('V2', 'Cantidad')
            ->setCellValue('W2', 'Subtotal')
            ;
            
        //Comentario para la celda moneda
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('C2')
            ->getText()->createTextRun("
            PEN => SOLES
            USD => DÓLARES");
        $spreadsheet->setActiveSheetIndex(0)->getComment("C2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("C2")->setHeight("60px");


        //Comentario para la celda SUCURSAL
        $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $comentario_sucursales = "Si es Vacio, el sistema tomará automáticamente la primera sucursal."."\r\n\r\n";
        foreach($lista_sucursales as $item) {
            $comentario_sucursales = $comentario_sucursales.$item->idsucursal." => ".$item->nombre."\r\n";
        }
        
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('D2')
            ->getText()->createTextRun($comentario_sucursales);

        $spreadsheet->setActiveSheetIndex(0)->getComment("D2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("D2")->setHeight("300px");


        //comentario para la condición de pago
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('E2')
            ->getText()->createTextRun("Si es vacío se considerará al contado.");
        
        $spreadsheet->setActiveSheetIndex(0)->getComment("E2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("E2")->setHeight("50px");

        //comentario para la Fecha del Documento
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('F2')
            ->getText()->createTextRun("Si el campo está vacío se asignará la fecha actual.
            Formato de Fecha: Día/Mes/Año
            Ejemplo: 25/03/2022
            ");
        
        $spreadsheet->setActiveSheetIndex(0)->getComment("F2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("F2")->setHeight("100px");

        //Comentario para el Tipo de Documento de Identidad
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('J2')
            ->getText()->createTextRun("
            1 => D.N.I.
            6 => R.U.C.");
        $spreadsheet->setActiveSheetIndex(0)->getComment("J2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("J2")->setHeight("60px");

        //Comentario para el ID del Producto
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('Q2')
            ->getText()->createTextRun("Se puede dejar vacio, el sistema registrará un producto general.");
        $spreadsheet->setActiveSheetIndex(0)->getComment("Q2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("Q2")->setHeight("50px");

        //Comentario para la columna Tipo Afectación
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('R2')
            ->getText()->createTextRun("Se puede dejar vacio, se considerará GRAVADO");
        $spreadsheet->setActiveSheetIndex(0)->getComment("R2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("R2")->setHeight("50px");

        //Comentario para la columna Unidad Medida
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('T2')
            ->getText()->createTextRun("
            7   => UNIDAD
            20  => SERVICIO");
        $spreadsheet->setActiveSheetIndex(0)->getComment("T2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("T2")->setHeight("60px");
        
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
        
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:W3')->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:W3')->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:W3')->getFont()->setBold(true);

        //https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#formatting-cells
        $estilo_row_nota = array(
            'font'  => array(
                'bold'  => true,
                'italic'=> true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 8,
                'name'  => 'Verdana'
            )
        );
        
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A3:W3')->applyFromArray($estilo_row_nota);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'NOTA: Solo se permite la importación de facturas y boletas, y solo se permite importar un máximo de 500 comprobantes por archivo, las facturas no deben ser de fechas anteriores, y las boletas deben tener una fecha anterior máxima de 30 días.');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A3:W3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');


        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('B1:B2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('C1:I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE699');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('J1:P2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('8EA9DB');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('Q1:W2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');

        $spreadsheet->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(25, 'pt');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(28, 'pt');

        $this->agregar_lista_unidades_excel($spreadsheet, 1, 'Lista Unidades');
        $this->agregar_lista_afectaciones_excel($spreadsheet, 2, 'Afectaciones IGV');
        $this->agregar_lista_sucursales_excel($spreadsheet, 3, 'Lista Sucursales', $usuario->id_contribuyente);
        $this->agregar_tipo_doc_identidad_excel($spreadsheet, 4, 'Tipo.Doc.Identidad');
        $this->agregar_condicion_pago_excel($spreadsheet, 5, 'Condición de Pago', $usuario->id_contribuyente);
        $this->agregar_lista_monedas_excel($spreadsheet, 6, 'Moneda');

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Plantilla de Importación CPE';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function agregar_lista_monedas_excel(&$spreadsheet, $num_hoja, $nombre_hoja) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'COD.MONEDA')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'SIMBOLO')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $lista = SunatMoneda::find();
        $n = 1;
        foreach($lista as $unidad) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $unidad->id_codigomoneda, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $unidad->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $unidad->simbolo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');
    }

    public function agregar_lista_unidades_excel(&$spreadsheet, $num_hoja, $nombre_hoja) {

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'ID_UNIDADMEDIDA')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'SIMBOLO')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $lista = SunatUnidadmedida::find();
        $n = 1;
        foreach($lista as $unidad) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $unidad->idunidad, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $unidad->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $unidad->simbolo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');

    }

    public function agregar_lista_afectaciones_excel(&$spreadsheet, $num_hoja, $nombre_hoja) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'ID AFECTACION IGV')
            ->setCellValue('B1', 'DESCRIPCION')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);

        $lista = SunatTipoafectacionigv::find();
        $n = 1;
        foreach($lista as $item) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $item->id_tipoafectacionigv, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');
    }

    public function agregar_lista_sucursales_excel(&$spreadsheet, $num_hoja, $nombre_hoja, $id_contribuyente) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'ID SUCURSAL')
            ->setCellValue('B1', 'COD.LOCAL.ANEXO')
            ->setCellValue('C1', 'NOMBRE SUCURSAL')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $lista_sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        
        $n = 1;
        foreach($lista_sucursales as $item) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $item->idsucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->codigo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $item->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');
    }

    public function agregar_tipo_doc_identidad_excel(&$spreadsheet, $num_hoja, $nombre_hoja) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'Tipo.Doc.Cliente')
            ->setCellValue('B1', 'Nombre')
            ->setCellValue('C1', 'Descripción')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $lista_doc_identidad = SunatTipodocidentidad::find();
        
        $n = 1;
        foreach($lista_doc_identidad as $item) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $item->id_tipodocidentidad, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $item->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');
    }

    public function agregar_condicion_pago_excel(&$spreadsheet, $num_hoja, $nombre_hoja, $id_contribuyente) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'Id.Cond.Pago')
            ->setCellValue('B1', 'Descripción')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);

        //solo se permite al contado, porque en el excel no existe opción para agregar información de la transacción
        $lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo = 'contado' ", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        
        $n = 1;
        foreach($lista_condiciones_pago as $item) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $item->id_condicionpago, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->condicionpago, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');
    }

    public function iniciarProcesoImportacionAction() {
        ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 940);

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

            $time_inicio_proceso = microtime(true);

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) { 
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
                echo json_encode($resp);
                exit();
            }

            //Datos de Formulario
		    $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
            if(!isset($_FILES['archivo'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Agregar un Archivo!';
                echo json_encode($resp);
                exit();
            }
            
            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            if(!in_array($_FILES["archivo"]["type"], $allowedFileType)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No es un archivo válido!';
                echo json_encode($resp);
                exit();
            }
    
            if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Necesitamos tu Confirmación!';
                $resp['mensaje'] = '<strong>Una vez iniciado el proceso de importación de documentos electrónicos no podrá deshacerse, tampoco se podrá eliminar los comprobantes electrónicos.!</strong>';
                echo json_encode($resp);
                exit();
            }
            
            $nombre_temporal = 'temp'.uniqid().$_FILES['archivo']['name'];
            $rutaArchivo = $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/temporales/".$nombre_temporal;
            move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo);

            $documento = IOFactory::load($rutaArchivo);
            $totalDeHojas = $documento->getSheetCount();

            $hojaActual = $documento->getSheet(0); //extraemos datos de la primera hoja solamente
            $numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
            $numeroMayorDeColumna = 21;

            if($contribuyente->tipo_envio_sunat == 'prueba') {
                if($numeroMayorDeFila >= 15) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Esta cuenta se encuentra en el ambiente de pruebas, por tanto tiene un límite máximo de 5 items a importar.';
                    echo json_encode($resp);
                    exit();
                }
            } else {
                if($numeroMayorDeFila > 310) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Debes volver a revisar tu excel, encontramos que intentas crear más de 300 comprobantes. Recuerda que se permite un máximo de 300 comprobantes por vez.';
                    echo json_encode($resp);
                    exit();
                }
            }
            
            $herramientas = new HerramientasController;
            $validaciones = new Validaciones();

            if(empty($contribuyente->token)) {
                $token = $herramientas->gettoken(37);
                $contribuyente->token = $token;

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
                    $resp['mensaje'] = $e->getMessage();
                    echo json_encode($resp);
                    exit();
                }
            }

            $data_contribuyente = array(
                "token_contribuyente"   => $contribuyente->token, //Token del contribuyente
                "id_usuario_vendedor"   => $usuario->idusuario, //Debes ingresar el ID de uno de tus vendedores (opcional)
                "tipo_proceso"          => $contribuyente->tipo_envio_sunat, //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
                "tipo_envio"            => $contribuyente->modalidad_envio_sunat //funcional en una siguiente versión. Aquí puedes definir si se enviará de inmediato a sunat
            );

            
            $cpe = array();
            
            for ($indiceFila = 4; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
                $n = $indiceFila - 1;

                $documento_tipo                 = trim($hojaActual->getCell("A".$indiceFila)->getValue() ?? '');
                $documento_numero               = intval(trim($hojaActual->getCell("B".$indiceFila)->getValue() ?? ''));
                $documento_moneda               = trim($hojaActual->getCell("C".$indiceFila)->getValue() ?? '');
                $documento_idsucursal           = intval(trim($hojaActual->getCell("D".$indiceFila)->getValue() ?? 0));
                $documento_id_condicionpago     = intval(trim($hojaActual->getCell("E".$indiceFila)->getValue() ?? 0));
                $documento_fecha_emision        = trim($hojaActual->getCell("F".$indiceFila)->getCalculatedValue() ?? '');
                $documento_nro_placa            = trim($hojaActual->getCell("G".$indiceFila)->getValue() ?? '');
                $documento_nro_orden            = trim($hojaActual->getCell("H".$indiceFila)->getValue() ?? '');
                $documento_observacion          = trim($hojaActual->getCell("I".$indiceFila)->getValue() ?? '');

                $cliente_tipo_docidentidad      = intval(trim($hojaActual->getCell("J".$indiceFila)->getValue() ?? ''));
                $cliente_nro_docidentidad       = trim($hojaActual->getCell("K".$indiceFila)->getValue() ?? '');
                $cliente_razon_social           = trim($hojaActual->getCell("L".$indiceFila)->getValue() ?? '');
                $cliente_email                  = trim($hojaActual->getCell("M".$indiceFila)->getValue() ?? '');
                $cliente_direccion              = trim($hojaActual->getCell("N".$indiceFila)->getValue() ?? '');
                $cliente_sexo                   = trim($hojaActual->getCell("O".$indiceFila)->getValue() ?? '');
                $cliente_celular                = trim($hojaActual->getCell("P".$indiceFila)->getValue() ?? '');

                $producto_id                    = intval(trim($hojaActual->getCell("Q".$indiceFila)->getValue() ?? '0'));
                $producto_id_afectacion         = intval(trim($hojaActual->getCell("R".$indiceFila)->getValue() ?? '0'));
                $producto_descripcion           = trim($hojaActual->getCell("S".$indiceFila)->getValue() ?? '');
                $producto_id_unidadmedida       = intval(trim($hojaActual->getCell("T".$indiceFila)->getValue() ?? '0'));
                $producto_precio_venta          = floatval(trim($hojaActual->getCell("U".$indiceFila)->getValue() ?? '0'));
                $producto_cantidad              = floatval(trim($hojaActual->getCell("V".$indiceFila)->getValue() ?? '0'));
                
                if($documento_tipo != 'BOLETA' && $documento_tipo != 'FACTURA') {
                    //si ingresa aquí indica que ya no existen más documentos.
                    break;

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna TIPO_CPE, se ha ingresado un valor inválido. Debería ser FACTURA o BOLETA.';
                    echo json_encode($resp);
                    exit();
                }

                if(empty($documento_numero) || $documento_numero == '' || $documento_numero <= 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna N° CPE, se ha ingresado un valor inválido. Debería ser un número mayor a cero.';
                    echo json_encode($resp);
                    exit();
                }

                if($documento_moneda != 'PEN' && $documento_moneda != 'USD') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna COD.MONEDA, se ha ingresado un valor inválido. Debería ser PEN o USD.';
                    echo json_encode($resp);
                    exit();
                }

                $resp_valida_sucursal = $this->validar_id_sucursal($documento_idsucursal, $usuario->id_contribuyente, $n);
                if($resp_valida_sucursal['respuesta'] == 'error') {
                    echo json_encode($resp_valida_sucursal);
                    exit();
                }
                $documento_idsucursal = $resp_valida_sucursal['idsucursal'];

                $resp_valida_condicionpago = $this->validar_id_condicionpago($documento_id_condicionpago, $usuario->id_contribuyente, $n);
                if($resp_valida_condicionpago['respuesta'] == 'error') {
                    echo json_encode($resp_valida_condicionpago);
                    exit();
                }
                $documento_id_condicionpago = $resp_valida_condicionpago['id_condicionpago'];
                
                $resp_valida_fecha_doc = $this->validar_fecha_documento($documento_fecha_emision, $n);
                if($resp_valida_fecha_doc['respuesta'] == 'error') {
                    echo json_encode($resp_valida_fecha_doc);
                    exit();
                }

                $documento_fecha_emision = date("d/m/Y", strtotime($resp_valida_fecha_doc['fecha']));

                if($cliente_tipo_docidentidad != 6 && $cliente_tipo_docidentidad != 1 && $cliente_tipo_docidentidad != 0 && $cliente_tipo_docidentidad != 4) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', el tipo de documento de identidad ('.$cliente_tipo_docidentidad.')no es válido, únicamente se permite el código 1 (DNI), 6 (RUC) y 4 (CARNET EXTRANJERIA).';
                    echo json_encode($resp);
                    exit();
                    
                    //esta condición ya no funciona porque inicialmente se pensó que debería considerarse todos los tipos para boletas, sin embargo mejor se considera solo DNI, RUC Y OTRO
                    if($documento_tipo == 'BOLETA') {
                        
                    } else {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', el tipo de documento de identidad ('.$cliente_tipo_docidentidad.')no es válido, únicamente se permite el código 1 (DNI) y 6 (RUC).';
                        echo json_encode($resp);
                        exit();
                    }
                }

                if($documento_tipo == 'FACTURA') {
                    if($cliente_tipo_docidentidad != 6) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', el tipo de documento de identidad ('.$cliente_tipo_docidentidad.') no es válido, únicamente se permite el código 6 (RUC). Ya que se está intentando registrar una FACTURA';
                        echo json_encode($resp);
                        exit();
                    }
                }
                
                if($cliente_tipo_docidentidad == 1 || $cliente_tipo_docidentidad == 6) {
                    $cliente_nro_docidentidad = $herramientas->extraer_numeros($cliente_nro_docidentidad);
                } else {
                    // Elimina espacios, saltos de línea, fin de línea y tabulaciones
                    $cliente_nro_docidentidad = preg_replace('/\s+/', '', $cliente_nro_docidentidad);
                    // Elimina caracteres especiales
                    $cliente_nro_docidentidad = preg_replace('/[^A-Za-z0-9]/', '', $cliente_nro_docidentidad);
                }
                
                if(!empty($cliente_email)) {
                    if(!$validaciones->validate_email($cliente_email)) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Email Inválido';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', el email es inválido';
                        echo json_encode($resp);
                        exit();
                    }
                }

                if(!empty($cliente_sexo)) {
                    if($cliente_tipo_docidentidad != 'F' && $cliente_tipo_docidentidad != 'M') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', la columna SEXO es inválida, debe contener F para femenino, o M para masculino';
                        echo json_encode($resp);
                        exit();
                    }
                }

                $resp_valida_idproducto = $this->validar_id_producto($usuario->id_contribuyente, $documento_idsucursal, $producto_id, $producto_descripcion, $n);
                if($resp_valida_idproducto['respuesta'] == 'error') {
                    echo json_encode($resp_valida_idproducto);
                    exit();
                }

                $producto_id = $resp_valida_idproducto['id_producto'];

                $resp_valida_id_afectacion = $this->validar_id_afectacion($producto_id_afectacion, $n);
                if($resp_valida_id_afectacion['respuesta'] == 'error') {
                    echo json_encode($resp_valida_id_afectacion);
                    exit();
                }

                $producto_id_afectacion = $resp_valida_id_afectacion['id_tipoafectacionigv'];

                $resp_validacion_idunidad = $this->validar_unidad_medida($producto_id_unidadmedida, $n);
                if($resp_validacion_idunidad['respuesta'] == 'error') {
                    echo json_encode($resp_validacion_idunidad);
                    exit();
                }

                $producto_id_unidadmedida = $resp_validacion_idunidad['id_unidad_medida'];
                $producto_codigo_unidadmedida = $resp_validacion_idunidad['codigo'];

                if($producto_precio_venta <= 0) {
                    $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Email Inválido';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', el precio de venta debe ser mayor a cero.';
                        echo json_encode($resp);
                        exit();
                }

                if($producto_cantidad <= 0) {
                    $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Email Inválido';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', la cantidad no es válida.';
                        echo json_encode($resp);
                        exit();
                }

                $tipo_documento = ($documento_tipo == 'FACTURA')?'01':'03';
                
                $resp_val_serie_correlativo = $this->validar_fecha_cpe_series_correlativos($tipo_documento, $usuario->id_contribuyente, $documento_idsucursal, $resp_valida_fecha_doc['fecha'], $n);
                if($resp_val_serie_correlativo['respuesta'] == 'error') {
                    echo json_encode($resp_val_serie_correlativo);
                    exit();
                }
                
                $cabecera_comprobante = array(
                    "tipo_documento"            => $tipo_documento,  //{"01": FACTURA, "03": BOLETA}
                    "moneda"                    => $documento_moneda,  //{"USD", "PEN"}
                    "idsucursal"                => $documento_idsucursal,  //{ID DE SUCURSAL}
                    "id_condicionpago"          => $documento_id_condicionpago,  //condicionpago_comprobante
                    "fecha_comprobante"         => $documento_fecha_emision,  //fecha_comprobante
                    "nro_placa"                 => $documento_nro_placa,  //nro_placa_vehiculo
                    "nro_orden"                 => $documento_nro_orden,  //nro_orden
                    "guia_remision"             => "",  //guia_remision_manual
                    "descuento_monto"           => 0,  // (máximo 2 decimales) (monto total del descuento)
                    "descuento_porcentaje"      => 0,  // (máximo 2 decimales) (porcentaje total del descuento)
                    "observacion"               => $documento_observacion,  //observacion_documento
                    "enviar_a_sunat"            => "no"
                );

                $cliente = array(
                    "tipo_docidentidad"         => $cliente_tipo_docidentidad, //{0: SINDOC, 1: DNI, 6: RUC, 4: CARNET EXTRANJERIA}
                    "numerodocumento"           => $cliente_nro_docidentidad, //Es opcional solo cuando tipo_docidentidad es 0, caso contrario se debe ingresar el número de ruc
                    "nombre"                    => $cliente_razon_social, //Es opcional solo cuando tipo_docidentidad es 1, caso contrario es obligatorio ingresar aquí la razón social
                    "email"                     => $cliente_email, //opcional: (si tiene correo se enviará automáticamente el email)
                    "direccion"                 => $cliente_direccion, //opcional: 
                    "ubigeo"	                => "",
                    "sexo"                      => !empty($cliente_sexo)?$cliente_sexo:'', //opcional: masculino
                    "fecha_nac"                 => "", //opcional: 
                    "celular"                   => $cliente_celular //opcional
                );

                $detalle = array(
                    "idproducto"            => $producto_id,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
                    "codigo"	            => "TV_CODIGOPROD", //codigo del producto (requerido)
                    "afecto_icbper"         => "no",  //"afecto_icbper":"no",
                    "id_tipoafectacionigv"  => $producto_id_afectacion,  //"id_tipoafectacionigv":"10",
                    "descripcion"           => $producto_descripcion,  //"descripcion":"Zapatos",
                    "idunidadmedida"        => $producto_codigo_unidadmedida,  //{NIU para unidades, ZZ para servicio}
                    "precio_venta"          => $producto_precio_venta,  //Precio unitario de venta (inc. IGV),
                    "cantidad"              => $producto_cantidad,  //"cantidad":"1"
                );

                $cpe[$documento_numero]['contribuyente'] = $data_contribuyente;
                $cpe[$documento_numero]['cabecera_comprobante'] = $cabecera_comprobante;
                $cpe[$documento_numero]['cliente'] = $cliente;
                $cpe[$documento_numero]['detalle'][] = $detalle;
            }

            if(count($cpe) <= 0) {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Proceso Finalizado';
                $resp['mensaje'] = 'No se han encontrado documentos para importar en el archivo excel.';
                echo json_encode($resp);
                exit();
            }

            $api = new ApiController;
            $this->db->begin();

            $n_facturas = 0;
            $n_boletas = 0;
            foreach($cpe as $documento) {
                $resp_proceso_venta = $api->procesar_venta($documento);
                if($resp_proceso_venta['respuesta'] == 'error') {
                    $this->db->rollback();
                    echo json_encode($resp_proceso_venta);
                    exit();
                }

                if($documento['cabecera_comprobante']['tipo_documento'] == '01') {
                    $n_facturas++;
                } else if($documento['cabecera_comprobante']['tipo_documento'] == '03') {
                    $n_boletas++;
                }
            }

            //echo "llega paso final 2 => FACTURAS: $n_facturas => BOLETAS: $n_boletas, tiempo empleado: ".(microtime(true) - $time_inicio_proceso);
            //exit();

            $log = new Log();
            $log->tabla = "doc_electronico";
            $log->accion = "importacion_cpe";
            if($n_boletas > 0 && $n_facturas > 0) {
                $log->descripcion = "Se importaron un total de ".count($cpe)." documentos electrónicos, de los cuáles $n_boletas fueron boletas, y $n_facturas fueron facturas.";
            } else if($n_boletas > 0 && $n_facturas <= 0) {
                if($n_boletas == 1) {
                    $log->descripcion = "Se ha importado una factura.";
                } else {
                    $log->descripcion = "Se ha importado un total de $n_boletas Boletas.";
                }
            } else if($n_boletas <= 0 && $n_facturas > 0) {
                if($n_facturas == 1) {
                    $log->descripcion = "Se ha importado una factura.";
                } else {
                    $log->descripcion = "Se ha importado un total de $n_facturas Facturas.";
                }
            } else {
                $log->descripcion = "Se importaron un total de ".count($cpe)." documentos electrónicos";
            }

            $log->descripcion = "Se importaron un total de ".count($cpe)." documentos electrónicos, de los cuáles $n_boletas fueron boletas, y $n_facturas fueron facturas.";
            $log->fecha_registro = date('Y-m-d H:i:s');
            $log->idusuario = $usuario->idusuario;
            $log->id_contribuyente = $usuario->id_contribuyente;
            $log->num_total_importados = count($cpe);
            $log->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
            $resp_log = $log->save();

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Proceso Finalizado';
            $resp['mensaje'] = 'Se han procesado correctamente todos los documentos, FACTURAS: '.$n_facturas.' || BOLETAS: '.$n_boletas;
            echo json_encode($resp);
            exit();

            //En modo prueba sólamente deben poder importar hasta 3 comprobantes con un máximo de 5 items.
            //Deben importar un máximo de 500 comprobantes.
        }
    }

    public function validar_fecha_cpe_series_correlativos($tipo_documento, $id_contribuyente, $idsucursal, $nueva_fecha_emision, $n) {
        // En el excel los clientes pueden subir una lista completa de comprobantes con diferentes fechas
        // por tanto debemos validar que no se creen documentos con fecha anterior al último documento emitido desde la misma sucursal
        // por ejemplo: Si en la Sucursal 1, ya se emitió una factura o boleta con fecha 05 de Mayo 2022,
        // ya no debería permitirse la creación de documentos anteriores a esa fecha.
        
        $herramientas = new HerramientasController;
    
        $contribuyente = Contribuyente::findFirst(array(
            "id_contribuyente = :id_contribuyente:",
            'bind' => array('id_contribuyente' => $id_contribuyente)
        ));
    
        $sucursal = Sucursal::findFirst(array(
            "idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:",
            'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)
        ));
    
        if ($tipo_documento == '01') {
            $serie_documento = $sucursal->factura_serie;
        } elseif ($tipo_documento == '03') {
            $serie_documento = $sucursal->boleta_serie;
        }
        
        $ultimo_documento_emitido = DocElectronico::findFirst(array(
            "id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:",
            'bind' => array(
                'id_contribuyente' => $id_contribuyente, 
                'id_tipodoc_electronico' => $tipo_documento, 
                'serie_comprobante' => $serie_documento, 
                'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat
            ),
            "order" => "numero_comprobante DESC"
        ));
    
        if ($ultimo_documento_emitido) {
            $documento_electronico = SunatTipodocelectronico::findFirst(array(
                "id_tipodoc_electronico = :id_tipodoc_electronico:",
                'bind' => array('id_tipodoc_electronico' => $ultimo_documento_emitido->id_tipodoc_electronico)
            ));
            
            $fecha_ultimo_cpe = date('Y-m-d', strtotime($ultimo_documento_emitido->fecha_comprobante));
            $nueva_fecha_emision = date('Y-m-d', strtotime($nueva_fecha_emision));
            
            // Compara la nueva fecha con la fecha del último comprobante emitido
            $fecha_actual_doc = date('Y-m-d', strtotime($fecha_ultimo_cpe));
            $resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual_doc, $nueva_fecha_emision);
            $diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
            
            if ($diferencia_dias > 1) {
                $resp['respuesta'] = 'error';
                $resp['codigo'] = 'cabecera_factura';
                $resp['titulo'] = 'error';
                $resp['mensaje'] = 'Verificamos que el último comprobante emitido es la ' . $documento_electronico->descripcion . ' ' . $ultimo_documento_emitido->serie_comprobante . '-' . $ultimo_documento_emitido->numero_comprobante . ' con fecha: ' . date('d-m-Y', strtotime($ultimo_documento_emitido->fecha_comprobante)) . ', <strong>Por tanto no debes importar ' . $documento_electronico->descripcion . 'S con fechas menores al ' . date('d-m-Y', strtotime($ultimo_documento_emitido->fecha_comprobante)) . '</strong> (error en línea: ' . $n . ')';
                return $resp;
            }
        }
    
        // Validación adicional: Si la fecha de emisión es futura, debe ser como máximo 3 días adelante a la fecha actual.
        $nueva_fecha_emision = date('Y-m-d', strtotime($nueva_fecha_emision));
        $fecha_actual = date('Y-m-d');
        if ($nueva_fecha_emision > $fecha_actual) {
            $diferencia_futuro = (strtotime($nueva_fecha_emision) - strtotime($fecha_actual)) / (60 * 60 * 24);
            if ($diferencia_futuro > 3) {
                $resp['respuesta'] = 'error';
                $resp['codigo'] = 'cabecera_factura';
                $resp['titulo'] = 'error';
                $resp['mensaje'] = 'La fecha de emisión no puede ser mayor a 3 días en el futuro.';
                return $resp;
            }
        }
    
        $resp['respuesta'] = 'ok';
        return $resp;
    }    

    public function validar_unidad_medida($id_unidad_medida, $n) {
        if($id_unidad_medida > 0) {
            $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
            if(!$unidadmedida) {
                $resp['respuesta'] = 'error';
                $resp['codigo'] = 'detalle_item';
                $resp['titulo'] = 'error';
                $resp['mensaje'] = 'En la línea N° '.($n + 1).', el id de unidad de medida es inválido.';
                return $resp;
            }
        } else {
            $id_unidad_medida = 7;
        }

        $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));

        $resp['respuesta'] = 'ok';
        $resp['id_unidad_medida'] = $id_unidad_medida;
        $resp['codigo'] = $unidadmedida->codigo;
        return $resp;        
    }

    public function validar_id_afectacion($id_tipoafectacionigv, $n) {
        if($id_tipoafectacionigv > 0) {
            $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $id_tipoafectacionigv)));
            if(!$tipoafectacionigv) {
                $resp['respuesta'] = 'error';
                $resp['codigo'] = 'detalle_item';
                $resp['titulo'] = 'error';
                $resp['mensaje'] = 'En la línea N° '.($n + 1).', el tipo de afectación es inválido';
                return $resp;
            }

            $resp['respuesta'] = 'ok';
            $resp['id_tipoafectacionigv'] = $id_tipoafectacionigv;
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['id_tipoafectacionigv'] = 10;
        return $resp;        
    }

    public function validar_id_producto($id_contribuyente, $idsucursal, $id_producto, $descripcion, $n) {
        $api = new ApiController;
        $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and estado = 'activo'", 'bind' => array('idproducto' => $id_producto, 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $idsucursal)));
        if(!$producto) {
            $producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('codigo' => $id_producto, 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $idsucursal)));

            if(!$producto) {

                if(!empty($id_producto)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error Producto';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', el id_producto ('.$id_producto.') no existe en la base de datos.';
                    return $resp;
                }

                $producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('codigo' => 'P_TIENDAVIRTUAL', 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $idsucursal)));
                if(!$producto) {
                    $resp_prod = $api->crear_producto_general($id_contribuyente, $idsucursal);
                    if($resp_prod['respuesta'] == 'error') {
                        $resp['respuesta'] = 'error';
                        $resp['codigo'] = 'detalle_item';
                        $resp['titulo'] = 'error';
                        $resp['mensaje'] = 'El producto '.$descripcion.', no existe en su base datos.';
                        return $resp;
                    }
    
                    $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $resp_prod['id_producto'], 'id_contribuyente' => $id_contribuyente)));
                }
            }

            $id_producto = $producto->idproducto;
        }

        $id_producto = $producto->idproducto;
        
        $resp['respuesta'] = 'ok';
        $resp['id_producto'] = $id_producto;
        return $resp;
    }

    public function validar_fecha_documento($fecha, $n) {
        if(!empty($fecha)) {
            $array_fecha_documento = explode('/', $fecha);
            if(!isset($array_fecha_documento[2]) || !isset($array_fecha_documento[1]) || !isset($array_fecha_documento[0])) {
                $excel_date = floatval($fecha); //here is that value 41621 or 41631
                if($excel_date <= 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', la fecha del documento no es válida';
                    return $resp;
                }

                $unix_date = ($excel_date - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;
                $resp['respuesta'] = 'ok';
                $resp['fecha'] = gmdate("Y-m-d", $unix_date);
                return $resp;
            }
            
            $fecha_documento = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
            if (!(DateTime::createFromFormat('Y-m-d', $fecha_documento) !== FALSE)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'En la línea N° '.($n + 1).', la fecha ('.$fecha.') del documento no es válida';
                return $resp;
            }

            $resp['respuesta'] = 'ok';
            $resp['fecha'] = date("Y-m-d", strtotime($fecha_documento));
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['fecha'] = date('Y-m-d', strtotime('today'));
        return $resp;
    }

    public function validar_id_condicionpago($id_condicionpago, $id_contribuyente, $n) {
        if($id_condicionpago > 0) {
            $condicion_pago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo = 'contado' ", 'bind' => array('id_condicionpago' => $id_condicionpago, 'id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));

            if(!$condicion_pago) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna ID CONDICION PAGO, se ha ingresado un valor inválido. La condición de pago con ID '.$id_condicionpago.' no es válida.';
                return $resp;
            }

            $resp['respuesta'] = 'ok';
            $resp['id_condicionpago'] = $id_condicionpago;
            return $resp;
        }

        $condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and tipo = 'contado'", 'bind' => array('id_contribuyente' => $id_contribuyente), "order" => "id_condicionpago ASC"));
        if(!$condicion_pago) {
            $condicion_pago = new Condiciondepago();
            $condicion_pago->id_contribuyente = $id_contribuyente;
            $condicion_pago->tipo = 'contado';
            $condicion_pago->condicionpago = 'Pago en Efectivo';
            $condicion_pago->estado = 'activo';

            try {
                if(!$condicion_pago->save()) {
                    $msg = '';
                    foreach ($condicion_pago->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No existe una condición de pago al contado, debes registrar la condición de pago antes de iniciar con la importación!';
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $e->getMessage();
                return $resp;
            }
        }
        
        $resp['respuesta'] = 'ok';
        $resp['id_condicionpago'] = $condicion_pago->id_condicionpago;
        return $resp;
    }

    public function validar_id_sucursal($idsucursal, $id_contribuyente, $n) {
        if($idsucursal > 0) {
            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
            if(!$sucursal) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna ID.SUCURSAL, se ha ingresado un valor inválido. La Sucursal con el ID '.$idsucursal.' no existe.';
                return $resp;
            }

            $resp['respuesta'] = 'ok';
            $resp['idsucursal'] = $idsucursal;
            return $resp;
        }

        $sucursal = Sucursal::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$sucursal) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Verificamos que no tienes sucursales registradas.';
            return $resp;
        }
        
        $resp['respuesta'] = 'ok';
        $resp['idsucursal'] = $sucursal->idsucursal;
        return $resp;
    }
}
?>