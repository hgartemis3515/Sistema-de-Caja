<?php
//require_once $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CreargremasivoController extends ControllerBase {

    public function indexAction() {

    }

    public function plantillaCrearGreMasivoAction() {
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
            ->setTitle("Plantilla G.R.E.")
            ->setSubject("Plantilla para Creación Masiva de G.R.E.")
            ->setDescription(
                "Esta plantilla se utiliza para crear G.R.E. de forma masiva desde excel al sistema de facturación electrónica"
            )
            ->setKeywords("facturalaya.com, código fuente, plantilla importación, importar gre")
            ->setCategory("importacion gre");
            
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Plantilla G.R.E Masivo");

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:A2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("B1:B2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("C1:C2");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("D1:J1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("K1:P1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("Q1:R1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("S1:T1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("U1:Y1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("Z1:AB1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("AC1:AH1");
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A3:AH3");

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id Sucursal')
            ->setCellValue('B1', 'Fecha Documento')
            ->setCellValue('C1', 'N° GUIA')
            ->setCellValue('D1', 'DATOS DEL CLIENTE')
            ->setCellValue('K1', "DATOS DEL TRASLADO")
            ->setCellValue('Q1', 'PUNTO DE PARTIDA')
            ->setCellValue('S1', 'PUNTO DE LLEGADA')
            ->setCellValue('U1', "DATOS DEL TRANSPORTE PRIVADO")
            ->setCellValue('Z1', 'DATOS DEL TRANSPORTE PÚBLICO')
            ->setCellValue('AC1', 'ITEMS DE LA GUÍA DE REMISIÓN')
            
            //cabecera del documento
            ->setCellValue('D2', 'Tipo.Doc.Cliente')
            ->setCellValue('E2', 'Nro.Doc.Cliente')
            ->setCellValue('F2', 'Nombre o Razón Social del Cliente')
            ->setCellValue('G2', 'Email')
            ->setCellValue('H2', 'Dirección')
            ->setCellValue('I2', 'Sexo')
            ->setCellValue('J2', 'Celular')

            ->setCellValue('K2', 'Motivo Traslado')
            ->setCellValue('L2', 'Modalidad Traslado')
            ->setCellValue('M2', 'F.Inicio Traslado')
            ->setCellValue('N2', 'Peso Total')
            ->setCellValue('O2', 'Nro. Bultos')
            ->setCellValue('P2', 'Observación')

            ->setCellValue('Q2', 'Dirección')
            ->setCellValue('R2', 'Código Ubigeo')

            ->setCellValue('S2', 'Dirección')
            ->setCellValue('T2', 'Código Ubigeo')

            ->setCellValue('U2', 'Tipo.Doc.Conductor')
            ->setCellValue('V2', 'N° DNI Conductor')
            ->setCellValue('W2', 'Nombre Conductor')
            ->setCellValue('X2', 'N° Licencia Conducir')
            ->setCellValue('Y2', 'N° Placa Vehículo')

            ->setCellValue('Z2', 'Tipo.Doc.Transporte')
            ->setCellValue('AA2', 'RUC Emp. Transporte')
            ->setCellValue('AB2', 'Razón Social Emp. Transporte')
            ->setCellValue('AC2', 'ID Producto')
            ->setCellValue('AD2', 'Código Producto')
            ->setCellValue('AE2', 'Descriopción')
            ->setCellValue('AF2', 'Unidad Medida')
            ->setCellValue('AG2', 'Cantidad')
            ->setCellValue('AH2', 'Peso Unitario')
            ;

        $this->asignar_tipo_valor_celdas($spreadsheet, 'A', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'B', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'D', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'E', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'J', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'K', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'L', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'M', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'R', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'T', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'U', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'V', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'X', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'Y', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'Z', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'AA', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'AC', 1000, '@');
        $this->asignar_tipo_valor_celdas($spreadsheet, 'AD', 1000, '@');

        //se agregan los comentarios para las celdas
        $this->agregar_comentarios_para_celdas($spreadsheet);
        
        //se auto ajustan las celdas
        $this->auto_ajustar_celdas($spreadsheet);

        //Colorear y alinear celdas
        $this->colorear_y_alinear_celdas($spreadsheet);

        $this->agregar_lista_unidades_excel($spreadsheet, 1, 'Lista Unidades');
        $this->agregar_lista_sucursales_excel($spreadsheet, 2, 'Lista Sucursales', $usuario->id_contribuyente);

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Plantilla para Crear G.R.E. Masivo';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function asignar_tipo_valor_celdas(&$spreadsheet, $column, $rows, $type) {
        for ($i = 4; $i <= $rows; $i++) {
            $cellCoordinate = $column . $i;
            $spreadsheet->getActiveSheet()
                ->getStyle($cellCoordinate)
                ->getNumberFormat()
                ->setFormatCode($type);  // Aplica el formato de texto
        }
    }

    public function colorear_y_alinear_celdas(&$spreadsheet) {
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AH3')->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AH3')->getAlignment()->setVertical('center');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:AH3')->getFont()->setBold(true);

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

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A3:AH3')->applyFromArray($estilo_row_nota);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'NOTA: Solo se permiten crear guías de remisión electrónica (no transportista), y solo se permite crear un máximo de 500 registros por archivo.');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A3:AH3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD');

        $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:C2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF9999');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('D1:J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('K1:P2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6B8B7');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('Q1:R2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CCC0DA');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('S1:T2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE699');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('U1:Y2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('8EA9DB');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('Z1:AB2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('F4B084');
        $spreadsheet->setActiveSheetIndex(0)->getStyle('AC1:AH2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A9D08E');

        $spreadsheet->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(25, 'pt');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(28, 'pt');
    }

    public function auto_ajustar_celdas(&$spreadsheet) {
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
    }

    public function agregar_comentarios_para_celdas(&$spreadsheet) {
        //N° CPE
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('C1')
            ->getText()->createTextRun("Número de Guía de Remisión (Sirve para saber cuántas filas pertenecen al mismo comprobante)");
        $spreadsheet->setActiveSheetIndex(0)->getComment("C2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("C2")->setHeight("150px");
        
        //Tipo.Doc.Cliente
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('D2')
            ->getText()->createTextRun("
            1 => D.N.I.
            6 => R.U.C.");
        $spreadsheet->setActiveSheetIndex(0)->getComment("D2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("D2")->setHeight("60px");

        //Nro.Doc.Cliente
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('E2')
            ->getText()->createTextRun("Número del Documento, si es 1 entonces debería tener 8 dígitos sin espacios, y si es 6 debería tener 11 dígitos sin espacios.");
        $spreadsheet->setActiveSheetIndex(0)->getComment("E2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("E2")->setHeight("60px");

        //Motivo Traslado
        $motivos_traslado = SunatMotivotraslado::find();
        $comment_motivo_traslado = "";
        foreach($motivos_traslado as $motivo_traslado) {
            $comment_motivo_traslado .= $motivo_traslado->id_motivotraslado." (".$motivo_traslado->descripcion.")\n";
        }
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('K2')
            ->getText()->createTextRun($comment_motivo_traslado);
        $spreadsheet->setActiveSheetIndex(0)->getComment("K2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("K2")->setHeight("60px");

        //Modalidad Traslado
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('L2')
            ->getText()->createTextRun("
            01 (Transporte Público)
            02 (Transporte Privado)");
        $spreadsheet->setActiveSheetIndex(0)->getComment("L2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("L2")->setHeight("60px");

        //F.Inicio Traslado
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('M2')
            ->getText()->createTextRun("Fecha en la que se Inicia el Traslado.
            Formato de Fecha: Día/Mes/Año
            Ejemplo: 25/03/2022");
        $spreadsheet->setActiveSheetIndex(0)->getComment("M2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("M2")->setHeight("60px");

        //Peso Total (KGM)
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('N2')
            ->getText()->createTextRun("Peso Total en Kilogramos (KGM)");
        $spreadsheet->setActiveSheetIndex(0)->getComment("N2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("N2")->setHeight("60px");

        //Tipo.Doc.Conductor
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('U1')
            ->getText()->createTextRun("Campos Requeridos solo si el transporte es privado");
        $spreadsheet->setActiveSheetIndex(0)->getComment("U1")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("U1")->setHeight("60px");

        //Tipo.Doc.Conductor
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('Z1')
            ->getText()->createTextRun("Campos Requeridos solo si el transporte es Público");
        $spreadsheet->setActiveSheetIndex(0)->getComment("Z1")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("Z1")->setHeight("60px");

        //ID PRODUCTO
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('AC2')
            ->getText()->createTextRun("Puede dejarse Vacío");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AA2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AA2")->setHeight("60px");

        //Peso Total (KGM)
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('AD2')
            ->getText()->createTextRun("Puede dejarse vacío");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AB2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AB2")->setHeight("60px");

        //Peso Total (KGM)
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('AE2')
            ->getText()->createTextRun("Campo Requerido (máximo 250 caracteres)");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AC2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AC2")->setHeight("60px");

        //Peso Total (KGM)
        $spreadsheet->setActiveSheetIndex(0)
            ->getComment('AF2')
            ->getText()->createTextRun("Ver Lista de Códigos Válidos para las Unidades de Medida");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AD2")->setWidth("400px");
        $spreadsheet->setActiveSheetIndex(0)->getComment("AD2")->setHeight("60px");
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

    public function iniciarProcesoCreacionGreAction() {
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
    
            $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
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
            //$numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
            $numeroMayorDeFila = $this->obtenerUltimaFilaConDatos($hojaActual);

            if($contribuyente->tipo_envio_sunat == 'prueba') {
                if($numeroMayorDeFila >= 15) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Esta cuenta se encuentra en el ambiente de pruebas, por tanto tiene un límite máximo de 5 items a importar., y según verificamos, intentas importar '.$numeroMayorDeFila.' items.';
                    echo json_encode($resp);
                    exit();
                }
            } else {
                if($numeroMayorDeFila > 904) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Debes volver a revisar tu excel, encontramos que intentas crear más de 900 registros. Recuerda que se permite un máximo de 900 registros por vez.';
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
                    $resp['mensaje'] = 'Error al guardar el token del contribuyente';
                    echo json_encode($resp);
                    exit();
                }
            }

            $cpe = array();
            
            for ($indiceFila = 4; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
                $n = $indiceFila - 1;
                
                $data_guia = array();
                $data_guia['id_sucursal']                 = trim($hojaActual->getCell("A".$indiceFila)->getValue());
                $data_guia['fecha_documento']             = trim($hojaActual->getCell("B".$indiceFila)->getValue());
                $data_guia['numero_guia']                 = intval(trim($hojaActual->getCell("C".$indiceFila)->getValue()));

                $data_guia['tipo_doc_cliente']            = trim($hojaActual->getCell("D".$indiceFila)->getValue());
                $data_guia['numero_doc_cliente']          = trim($hojaActual->getCell("E".$indiceFila)->getValue());
                $data_guia['nombre_cliente']              = trim($hojaActual->getCell("F".$indiceFila)->getValue());
                $data_guia['email_cliente']               = trim($hojaActual->getCell("G".$indiceFila)->getValue());
                $data_guia['direccion_cliente']           = trim($hojaActual->getCell("H".$indiceFila)->getValue());
                $data_guia['sexo_cliente']                = trim($hojaActual->getCell("I".$indiceFila)->getValue());
                $data_guia['celular_cliente']             = trim($hojaActual->getCell("J".$indiceFila)->getValue());
                
                $data_guia['motivo_traslado']             = trim($hojaActual->getCell("K".$indiceFila)->getValue());
                $data_guia['motivo_traslado']             = '0'.intval($data_guia['motivo_traslado']);
                $data_guia['modalidad_traslado']          = trim($hojaActual->getCell("L".$indiceFila)->getValue());
                $data_guia['modalidad_traslado']          = '0'.intval($data_guia['modalidad_traslado']);
                $data_guia['fecha_inicio_traslado']       = trim($hojaActual->getCell("M".$indiceFila)->getValue());
                $data_guia['peso_total']                  = floatval($hojaActual->getCell("N".$indiceFila)->getValue());
                $data_guia['numero_bultos']               = floatval($hojaActual->getCell("O".$indiceFila)->getValue());
                $data_guia['observacion']                 = trim($hojaActual->getCell("P".$indiceFila)->getValue());

                $data_guia['direccion_partida']           = trim($hojaActual->getCell("Q".$indiceFila)->getValue());
                $data_guia['codigo_ubigeo_partida']       = trim($hojaActual->getCell("R".$indiceFila)->getValue());

                $data_guia['direccion_llegada']           = trim($hojaActual->getCell("S".$indiceFila)->getValue());
                $data_guia['codigo_ubigeo_llegada']       = trim($hojaActual->getCell("T".$indiceFila)->getValue());

                $data_guia['tipo_doc_conductor']          = trim($hojaActual->getCell("U".$indiceFila)->getValue());
                $data_guia['numero_doc_conductor']        = trim($hojaActual->getCell("V".$indiceFila)->getValue());
                $data_guia['nombre_conductor']            = trim($hojaActual->getCell("W".$indiceFila)->getValue());
                $data_guia['numero_licencia_conductor']   = trim($hojaActual->getCell("X".$indiceFila)->getValue());
                $data_guia['numero_placa_vehiculo']       = trim($hojaActual->getCell("Y".$indiceFila)->getValue());

                $data_guia['tipo_doc_transporte']         = trim($hojaActual->getCell("Z".$indiceFila)->getValue());
                $data_guia['ruc_transporte']              = trim($hojaActual->getCell("AA".$indiceFila)->getValue());
                $data_guia['razon_social_transporte']     = trim($hojaActual->getCell("AB".$indiceFila)->getValue());

                $data_guia['id_producto']                 = trim($hojaActual->getCell("AC".$indiceFila)->getValue());
                $data_guia['codigo_producto']             = trim($hojaActual->getCell("AD".$indiceFila)->getValue());
                $data_guia['descripcion_producto']        = trim($hojaActual->getCell("AE".$indiceFila)->getValue());
                $data_guia['unidad_medida_producto']      = trim($hojaActual->getCell("AF".$indiceFila)->getValue());
                $data_guia['cantidad_producto']           = floatval($hojaActual->getCell("AG".$indiceFila)->getValue());
                $data_guia['peso_unitario_producto']      = floatval($hojaActual->getCell("AH".$indiceFila)->getValue());
                
                $resp_validacion = $this->validar_data_guia($contribuyente, $usuario, $data_guia);
                if($resp_validacion['respuesta'] == 'error') {
                    echo json_encode($resp_validacion);
                    exit();
                }

                $data_guia = $resp_validacion['data_guia'];

                $resp_array_json = $this->crear_array_json($contribuyente, $usuario, $data_guia);
                if($resp_array_json['respuesta'] == 'error') {
                    echo json_encode($resp_array_json);
                    exit();
                }

                $documento_numero = $data_guia['numero_guia'];
                $cpe[$documento_numero]['contribuyente'] = $resp_array_json['contribuyente'];
                $cpe[$documento_numero]['cabecera_comprobante'] = $resp_array_json['cabecera_comprobante'];
                $cpe[$documento_numero]['cliente'] = $resp_array_json['cliente'];
                $cpe[$documento_numero]['transporte'] = $resp_array_json['transporte'];
                $cpe[$documento_numero]['direccion_partida'] = $resp_array_json['direccion_partida'];
                $cpe[$documento_numero]['direccion_llegada'] = $resp_array_json['direccion_llegada'];
                $cpe[$documento_numero]['detalle'][] = $resp_array_json['detalle'];
            }

            if(count($cpe) <= 0) {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Proceso Finalizado';
                $resp['mensaje'] = 'No se han encontrado documentos para importar en el archivo excel.';
                echo json_encode($resp);
                exit();
            }
            
            $api_guia_remision = new ApiguiaremisionController;
            $this->db->begin();

            $n_guias = 0;
            foreach($cpe as $guia_remision) {
                $resp_proceso_guia = $api_guia_remision->procesar_guia_remision($guia_remision, '');
                if($resp_proceso_guia['respuesta'] == 'error') {
                    $this->db->rollback();
                    echo json_encode($resp_proceso_guia);
                    exit();
                }

                $n_guias++;
            }

            $log = new Log();
            $log->tabla = "doc_electronico";
            $log->accion = "crear_guia_masivo";            

            $log->descripcion = "Se importaron un total de ".count($cpe)." guías de remisión";
            $log->fecha_registro = date('Y-m-d H:i:s');
            $log->idusuario = $usuario->idusuario;
            $log->id_contribuyente = $usuario->id_contribuyente;
            $log->num_total_importados = count($cpe);
            $log->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
            $resp_log = $log->save();

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Proceso Finalizado';
            $resp['mensaje'] = 'Se han procesado correctamente todas las guías de remisión. N° Guías: '.count($cpe);
            echo json_encode($resp);
            exit();
        }
    }

    public function obtenerUltimaFilaConDatos($hojaActual) {
        $filaMasAlta = $hojaActual->getHighestRow();
        for ($fila = $filaMasAlta; $fila >= 1; $fila--) {
            $filaConDatos = false;
            foreach ($hojaActual->getRowIterator($fila, $fila) as $filaObj) {
                $celdaIterador = $filaObj->getCellIterator();
                $celdaIterador->setIterateOnlyExistingCells(true);  // Esto recorrerá solo las celdas que están actualmente en la hoja de cálculo
                foreach ($celdaIterador as $celda) {
                    if ($celda->getValue() != null) {
                        $filaConDatos = true;
                    }
                }
            }
            if ($filaConDatos) {
                return $fila;
            }
        }
        return 0;
    }

    public function validar_data_guia($contribuyente, $usuario, $data_guia) {
        
        $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $data_guia['id_sucursal'], 'id_contribuyente' => $contribuyente->id_contribuyente)));

        if(!$sucursal) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No existe la sucursal con ID: '.$data_guia['id_sucursal'];
            return $resp;
        }

        $tipo_doc_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $data_guia['tipo_doc_cliente'])));
        if(!$tipo_doc_identidad) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No existe el tipo de documento de identidad con ID: '.$data_guia['tipo_doc_cliente'];
            return $resp;
        }

        $validaciones = new Validaciones();
        if(!empty($data_guia['email_cliente'])) {
            if(!$validaciones->validate_email($data_guia['email_cliente'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Email Inválido';
                $resp['mensaje'] = 'El Email para el cliente es inválido: '.$data_guia['email_cliente'];
                return $resp;
            }
        }

        if(!empty($data_guia['fecha_documento'])) {
            $resp_valida_fecha_doc = $this->validar_fecha_documento($data_guia['fecha_documento']);
            if($resp_valida_fecha_doc['respuesta'] == 'error') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Email Inválido';
                $resp['mensaje'] = 'La fecha para el documento es inválida: '.$data_guia['fecha_documento'];
                return $resp;
            }

            $data_guia['fecha_documento'] = date("d/m/Y", strtotime($resp_valida_fecha_doc['fecha']));
        } else {
            $data_guia['fecha_documento'] = date("d/m/Y");
        }
        
        if($data_guia['modalidad_traslado'] != '01' && $data_guia['modalidad_traslado'] != '02') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El campo Modalidad de Traslado es inválido: '.$data_guia['modalidad_traslado'];
            return $resp;
        }

        $motivo_traslado = SunatMotivotraslado::findFirst(array("id_motivotraslado = :id_motivotraslado:", 'bind' => array('id_motivotraslado' => $data_guia['motivo_traslado'])));
        if(!$motivo_traslado) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No existe el motivo de traslado con ID: '.$data_guia['motivo_traslado'];
            return $resp;
        }

        if(!empty($data_guia['fecha_inicio_traslado'])) {
            $resp_valida_fecha_doc = $this->validar_fecha_documento($data_guia['fecha_inicio_traslado']);
            if($resp_valida_fecha_doc['respuesta'] == 'error') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Email Inválido';
                $resp['mensaje'] = 'La fecha para el documento es inválida: '.$data_guia['fecha_inicio_traslado'];
                return $resp;
            }

            $data_guia['fecha_inicio_traslado'] = date("d/m/Y", strtotime($resp_valida_fecha_doc['fecha']));
        } else {
            $data_guia['fecha_inicio_traslado'] = date("d/m/Y");
        }

        if(floatval($data_guia['peso_total']) < 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El campo Peso Total es inválido: '.$data_guia['peso_total'];
            return $resp;
        }

        if(empty($data_guia['direccion_partida'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El campo Dirección de Partida es obligatorio';
            return $resp;
        }

        $ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $data_guia['codigo_ubigeo_partida'])));
        if(!$ubigeo_partida) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No existe el código de ubigeo con ID: '.$data_guia['codigo_ubigeo_partida'];
            return $resp;
        }

        if(empty($data_guia['direccion_llegada'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El campo Dirección de Llegada es obligatorio';
            return $resp;
        }

        $ubigeo_llegada = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $data_guia['codigo_ubigeo_llegada'])));
        if(!$ubigeo_llegada) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No existe el código de ubigeo con ID: '.$data_guia['codigo_ubigeo_llegada'];
            return $resp;
        }

        if($data_guia['modalidad_traslado'] == '02') {
            if(empty($data_guia['tipo_doc_conductor'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Tipo de Documento del Conductor es obligatorio';
                return $resp;
            }

            if(empty($data_guia['numero_doc_conductor'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Número de Documento del Conductor es obligatorio';
                return $resp;
            }

            if(empty($data_guia['nombre_conductor'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Nombre del Conductor es obligatorio';
                return $resp;
            }

            if(empty($data_guia['numero_licencia_conductor'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Número de Licencia del Conductor es obligatorio';
                return $resp;
            }

            if(empty($data_guia['numero_placa_vehiculo'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Número de Placa del Vehículo es obligatorio';
                return $resp;
            }
        } else {
            if(empty($data_guia['tipo_doc_transporte'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Tipo de Documento del Transporte es obligatorio';
                return $resp;
            }

            if(empty($data_guia['ruc_transporte'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo RUC del Transporte es obligatorio';
                return $resp;
            }

            if(empty($data_guia['razon_social_transporte'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El campo Razón Social del Transporte es obligatorio';
                return $resp;
            }
        }

        if(empty($data_guia['id_producto'])) {
            $data_guia['id_producto'] = 0;
        } else {
            $data_guia['id_producto'] = intval($data_guia['id_producto']);
        }

        $resp['respuesta'] = 'ok';
        $resp['data_guia'] = $data_guia;
        return $resp;
    }

    public function crear_array_json($contribuyente, $usuario, $data_guia) {
        
        $data_guia = (object)$data_guia;
        $data["contribuyente"] = array(
            "token_contribuyente"           => $contribuyente->token, //Token del contribuyente
            "id_usuario_vendedor"           => $usuario->idusuario, //Debes ingresar el ID de uno de tus vendedores (opcional)
            "tipo_proceso"                  => $contribuyente->tipo_envio_sunat, //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
            "tipo_envio"                    => "inmediato" //funcional en una siguiente versión. Aquí puedes definir si se enviará de inmediato a sunat
        );
        
        $data["cabecera_comprobante"] = array(
            "tipo_documento"                => "09",  //{"01": GUIA REMISIÓN}
            "idsucursal"                    => $data_guia->id_sucursal,  //{ID DE SUCURSAL}
            "fecha_comprobante"             => $data_guia->fecha_documento, //"10/05/2020",  //fecha_comprobante
            "motivo_traslado"               => $data_guia->motivo_traslado,  //Catálogo No. 20: Códigos – Motivos de traslado
            "modalidad_traslado"            => $data_guia->modalidad_traslado,  //{01: Transporte Público, 02: Transporte Privado} Catálogo No. 18: Códigos – Modalidad de traslado
            "fecha_traslado"                => $data_guia->fecha_inicio_traslado, //"10/05/2020",  //Fecha Inicio de Traslado
            "otro_motivo_traslado"          => "",  //Describir el motivo de traslado en caso se haya seleccionado OTRO en motivo traslado
            "pesobruto"                     => round(floatval($data_guia->peso_total), 2),  //Peso Total en KGM
            "numero_bultos"                 => $data_guia->numero_bultos,  //Número Total de Bultos
            "numero_contenedor"             => "",  //Indicar el número de contenedor en caso sea importación, caso contrario dejar vacio
            "codigo_puerto"                 => "",  //Indicar Código de Puerto en caso sea importación, caso contrario dejar vacio
            "tipo_doc_referencia"           => "",  //Si la guía está relacionada a una factura o boleta, indicar el tipo de documento {01: factura, 03: boleta}
            "serie_doc_referencia"          => "",  //Serie del documento de referencia
            "numero_doc_referencia"         => "",  //Número del Documento de Referencia
            "informacion_adicional_sunat"   => $data_guia->observacion,  //Observación, este texto será informado a SUNAT
            "enviar_a_sunat"                => "no"  //Si deseas enviar a SUNAT, colocar "si", caso contrario "no"
        );
        
        $data["cliente"] = array(
            "tipo_docidentidad"             => $data_guia->tipo_doc_cliente, //{0: SINDOC, 1: DNI, 6: RUC}
            "numerodocumento"               => $data_guia->numero_doc_cliente, //Es opcional solo cuando tipo_docidentidad es 0, caso contrario se debe ingresar el número de ruc
            "nombre"                        => $data_guia->nombre_cliente, //Es opcional solo cuando tipo_docidentidad es 1, caso contrario es obligatorio ingresar aquí la razón social
            "email"                         => $data_guia->email_cliente, //opcional: (si tiene correo se enviará automáticamente el email)
            "direccion"                     => $data_guia->direccion_cliente, //opcional: 
            "ubigeo"	                    => '',
            "sexo"                          => $data_guia->sexo_cliente, //opcional: masculino
            "fecha_nac"                     => '', //opcional: 
            "celular"                       => $data_guia->celular_cliente //opcional
        );

        $data["transporte"] = array(
            "tipo_docidentidad"	            => ($data_guia->modalidad_traslado == '02')?'1':'6', //{Si la modalidad de transporte es privado (02) entonces el tipo de documento debe ser DNI = 1, caso contrario debe ser RUC: 6}
            "numerodocumento"	            => ($data_guia->modalidad_traslado == '02')?$data_guia->numero_doc_conductor:$data_guia->ruc_transporte, //{Si la modalidad de transporte es privado (02) entonces aquí el número de dni del conductor, caso contrario el RUC de la empresa de transporte}
            "nombre"	                    => ($data_guia->modalidad_traslado == '02')?$data_guia->nombre_conductor:$data_guia->razon_social_transporte, //{Si la modalidad de transporte es privado (02) entonces aquí el nombre del conductor, caso contrario la razón social de la empresa de transporte}
            "num_placa"	                    => ($data_guia->modalidad_traslado == '02')?$data_guia->numero_placa_vehiculo:'', //{Llenar únicamente si el transporte es privado}
            "conductor_num_licencia"	    => ($data_guia->modalidad_traslado == '02')?$data_guia->numero_licencia_conductor:'', //{Llenar únicamente si el transporte es privado}
        );

        $data["direccion_partida"] = array(
            "direccion"                     => $data_guia->direccion_partida, //Dirección de partida máximo 100 caracteres
            "ubigeo"                        => $data_guia->codigo_ubigeo_partida //ubigeo
        );

        $data["direccion_llegada"] = array(
            "direccion"                     => $data_guia->direccion_llegada, //Dirección de llegada máximo 100 caracteres
            "ubigeo"                        => $data_guia->codigo_ubigeo_llegada //ubigeo
        );
        
        $detalle = array(
            "idproducto"                    => $data_guia->id_producto,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	                    => $data_guia->codigo_producto, //codigo del producto (requerido)
            "descripcion"                   => $data_guia->descripcion_producto,  //"descripcion":"Zapatos",
            "idunidadmedida"                => $data_guia->unidad_medida_producto,  //{NIU para unidades, ZZ para servicio}
            "peso"                          => $data_guia->peso_unitario_producto,  //Precio unitario de venta (inc. IGV),
            "cantidad"                      => $data_guia->cantidad_producto,  //"cantidad":"1"
        );

        $data['detalle'] = $detalle;

        $data['respuesta'] = 'ok';
        
        return $data;
    }

    public function validar_fecha_documento($fecha) {
        if(!empty($fecha)) {
            $array_fecha_documento = explode('/', $fecha);
            if(!isset($array_fecha_documento[2]) || !isset($array_fecha_documento[1]) || !isset($array_fecha_documento[0])) {
                $excel_date = floatval($fecha); //here is that value 41621 or 41631
                if($excel_date <= 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'la fecha del documento no es válida';
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
                $resp['mensaje'] = 'La fecha ('.$fecha.') del documento no es válida';
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
}
?>