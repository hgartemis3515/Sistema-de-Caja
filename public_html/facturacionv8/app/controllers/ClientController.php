<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ClientController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Clientes');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")
        
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/client.js?i=".rand());

        $ubigeo = SunatCodigoubigeo::find();
        $this->view->ubigeo = $ubigeo;

        $tipo_identidad = SunatTipodocidentidad::find();
        $this->view->tipo_identidad = $tipo_identidad;
    }

    public function insertAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_tipodocidentidad = !isset($datapost['type_document'])?'':$datapost['type_document'];
            $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']) + 0;
            $codigo = $datapost['codigo'];
            $num_doc = !isset($datapost['doc_id'])?'':$datapost['doc_id'];
            $razon_social = !isset($datapost['razon_social'])?'':$datapost['razon_social'];
            $direccion_fiscal = !isset($datapost['direccionfiscal'])?'':$datapost['direccionfiscal'];
            $email = !isset($datapost['email'])?'':$datapost['email'];
            $telefono = !isset($datapost['telefono'])?'':$datapost['telefono'];
            $celular = !isset($datapost['celular'])?'':$datapost['celular'];
            $id_cod_ubigeo = !isset($datapost['ubigeo'])?'':$datapost['ubigeo'];
            $num_cuenta_detraccion = !isset($datapost['cuenta_detraccion'])?'':$datapost['cuenta_detraccion'];
            $detalle_adicional = !isset($datapost['detalle_adicional'])?'':$datapost['detalle_adicional'];

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

            $herramientas = new HerramientasController;
            
            //Verificando campos 
            if(empty($codigo)){
                $codigo = $herramientas->gettoken(10);
            }

            $tipodocidentidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad)));
            if(!$tipodocidentidad){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo siento, ese  tipo de Documento de Identidad no existe. Por favor selecciona uno Válido';
                echo json_encode($msj);
                exit();
            }

            if(empty($num_doc)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Documento de Identidad, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($razon_social)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Razón Social o Nombre Completo del cliente,  no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            } 

            //Consultas
            if($id_cod_ubigeo != '') {
                $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_cod_ubigeo)));
                if(!$sql_ubigeo){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Lo siento, ese  código de Ubicación Geográfica no existe. Por favor selecciona un ubigeo Válido';
                    echo json_encode($msj);
                    exit();
                }
            }
            $nuevo_registro = false;

            $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$cliente) {
                $cliente = Cliente::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('num_doc' => $num_doc, 'id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocidentidad' => $id_tipodocidentidad)));
                if(!$cliente) {
                    $nuevo_registro = true; 
                    $cliente = new Cliente();
                    $cliente->fecha_registro = date('Y-m-d H:i:s');
                    $cliente->id_contribuyente = $usuario->id_contribuyente;
                    $cliente->estado = 'activo';
                } else {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Ya existe un usuario con el tipo de documento y número de documento!, por favor revise el listado de usuarios!';
                    echo json_encode($msj);
                    exit();
                }
            }

            if($nuevo_registro) {
                $registro_codigo = Cliente::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'id_contribuyente' => $usuario->id_contribuyente)));
            } else {
                $registro_codigo = Cliente::findFirst(array("codigo = :codigo: and idcliente <> :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'idcliente' => $cliente->idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
            }

            if($registro_codigo) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El código ya existe, por favor debes generar o ingresar un nuevo código!.';
                echo json_encode($resp);
                exit();
            }

            $cliente->id_tipodocidentidad = $id_tipodocidentidad;
            $cliente->num_doc = $num_doc;
            $cliente->razon_social = $razon_social;
            $cliente->direccion_fiscal = $direccion_fiscal;
            $cliente->id_cod_ubigeo = $id_cod_ubigeo;
            $cliente->codigo = $codigo;
            $cliente->email = $email;
            $cliente->celular = $celular;
            $cliente->telefono = $telefono;
            $cliente->num_cuenta_detraccion = $num_cuenta_detraccion;
            $cliente->detalle_adicional = $detalle_adicional;

            try {
                if(!$cliente->save()) {
                    $msg = '';
                    foreach ($cliente->getMessages() as $message) {
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
                $resp['mensaje'] = 'Error al guardar los datos del cliente! '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'Se guardaron correctamente los datos del cliente!';
            $resp['cliente'] = $cliente;
            $resp['idcliente'] = $cliente->idcliente;
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaClientesAction() {
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

            $total_registros = count(Cliente::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente))));

            $columnas = array(
                0 => 'idcliente',
                1 => 'id_tipodocidentidad',
                2 => 'codigo',
                3 => 'num_doc',
                4 => 'razon_social',
                5 => 'direccion_fiscal',
                6 => 'id_cod_ubigeo',

                7 => 'sc.departamento',
                8 => 'sc.provincia',
                9 => 'sc.distrito',

                10 => 'email',
                11 => 'celular',
                12 => 'detalle_adicional',
                13 => 'fecha_registro',
                14 => 'estado',
                15 => 'foto',
                16 => 'sexo',
                17 => 'fecha_nac'
            );


            $query = "SELECT * FROM cliente c LEFT JOIN sunat_codigoubigeo sc ON c.id_cod_ubigeo = sc.codigo_ubigeo WHERE id_contribuyente = :id_contribuyente ";

            if(!empty($datapost['search']['value'])) {
                $query = $query." AND (c.codigo like :termino or c.num_doc like :termino or c.razon_social like :termino or c.direccion_fiscal like :termino or c.id_cod_ubigeo like :termino or c.email like :termino or c.celular like :termino or c.sexo like :termino or sc.departamento like :termino or sc.provincia like :termino or sc.distrito like :termino) ";
            }

            $total_filas_filtradas = $this->get_num_rows_filtered_clientes($query, $usuario->id_contribuyente, $datapost['search']['value']);

            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
                if(!empty($datapost['search']['value'])) {
                    $termino_busqueda = '%'.$datapost['search']['value'].'%';
                    $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
                }
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $array_lista = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $item = (object)$fila;
                if($item->estado == 'activo') {
                    $estado = '<span class="label label-success">Activo</span>';
                } else {
                    $estado = '<span class="label label-danger">Inactivo</span>';
                }

                $cliente_departamento = '';
				$cliente_provincia = ''; 
				$cliente_distrito = '';
				$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $item->id_cod_ubigeo)));
				if($ubigeo_cliente) {
					$cliente_departamento = $ubigeo_cliente->departamento;
					$cliente_provincia = $ubigeo_cliente->provincia; 
					$cliente_distrito = $ubigeo_cliente->distrito;
				}

                $documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $item->id_tipodocidentidad)));
                $opciones = '
                <ul class="icons-list text-center">
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a onclick="editar_cliente('.$item->idcliente.')" data-idcliente="'.$item->idcliente.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                            <li><a onclick="eliminar_cliente('.$item->idcliente.')" data-idcliente="'.$item->idcliente.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                        </ul>
                    </li>
                </ul>';
                
                $array_lista[] = array(
                    'idcliente'             => $item->idcliente,
                    'id_tipodocidentidad'   => $documento_identidad->nombre,
                    'codigo'                => $item->codigo,
                    'num_doc'               => $item->num_doc,
                    'razon_social'          => $item->razon_social,
                    'direccion_fiscal'      => $item->direccion_fiscal,
                    'id_cod_ubigeo'         => $item->id_cod_ubigeo,

                    'departamento'          => $cliente_departamento,
                    'provincia'             => $cliente_provincia,
                    'distrito'              => $cliente_distrito,

                    'email'                 => $item->email,
                    'celular'               => $item->celular,
                    'detalle_adicional'     => $item->detalle_adicional,
                    'fecha_registro'        => date("d-m-Y", strtotime($item->fecha_registro)), 
                    'estado'                => $estado,
                    'sexo'                  => $item->sexo,
                    'fecha_nac'             => (!empty($item->fecha_nac))?date("d-m-Y", strtotime($item->fecha_nac)):'',
                    'opciones'              => $opciones
                );

                $n++;
            }

            $json_data = array(
                "draw"            => intval($datapost['draw']),
                "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
                "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
                "data"            => $array_lista,   // total data array
                "numero_n"        => $n
            );

            echo json_encode($json_data);
            exit();
        }
    }

    public function get_num_rows_filtered_clientes($query, $id_contribuyente, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
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

    public function getDataClienteAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']) + 0;
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

            $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El cliente seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['cliente'] = $cliente;
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarClienteAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']) + 0;
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

            $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$cliente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El cliente seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            $documento_electronico = DocElectronico::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $idcliente)));
            if($documento_electronico) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No podemos desactivar el cliente, pues el cliente seleccionado ya ha realizado compras!';
                echo json_encode($resp);
                exit();
            }

            $cliente->estado = 'inactivo';
        
            try {
                if(!$cliente->save()) {
                    $msg = '';
                    foreach ($cliente->getMessages() as $message) {
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
                $resp['mensaje'] = 'Error al desactivar el cliente! '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'El cliente fué desactivado correctamente!';
            $resp['idcliente'] = $cliente->idcliente;
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaSugerenciasClientesAction() {
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
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}
			
			$termino = str_replace('  ', ' ', trim($datapost['q']));
			$trozos = explode(" ", $termino); 
            $numero_trozos = count($trozos); 

            if($numero_trozos <= 1) {
                $termino = '%'.$termino.'%';
                $query = "select * from cliente where (num_doc like :termino or razon_social like :termino) and id_contribuyente = ".$usuario->id_contribuyente;
            } else {
                //https://desarrolloweb.com/articulos/2087.php
                $termino = "'".$termino."'";
                $query = "select * from cliente where (MATCH (razon_social, num_doc) AGAINST (:termino)) and id_contribuyente = ".$usuario->id_contribuyente;
            }

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
            
            $lista = array();
            while ($item = $sentencia->fetch()) {
                $fila = (object)$item;
                $lista[] = array('id' => $fila->idcliente, 'text' => $fila->num_doc.' - '.$fila->razon_social);
            }

            if(count($lista) <= 0) {
                $lista[] = array('id' => '', 'text' => '');
            }
			
            $resp['items'] = $lista;
            echo json_encode($resp);
            exit();
		}
    }

    public function descargarPlantillaImportacionAction() {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Lista Clientes");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'TIPO DOCUMENTO')
            ->setCellValue('B1', 'CODIGO')
            ->setCellValue('C1', 'NUM. DOCUMENTO')
            ->setCellValue('D1', 'NOMBRE/RAZON_SOCIAL')
            ->setCellValue('E1', 'DIRECCION')
            ->setCellValue('F1', 'CODIGO UBIGEO')
            ->setCellValue('G1', 'EMAIL')
            ->setCellValue('H1', 'CELULAR')
            ->setCellValue('I1', 'TELEFONO')
            ->setCellValue('J1', 'NUM CUENTA DETRACCION')
            ->setCellValue('K1', 'DETALLE')
            ;

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

        $spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

        $this->agregar_tipo_doc_identidad($spreadsheet, 1, 'Tipo Documento Identidad');

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Plantilla de Importación Clientes';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function agregar_tipo_doc_identidad(&$spreadsheet, $num_hoja, $nombre_hoja) {

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'TIPO DOCUMENTO')
            ->setCellValue('B1', 'NOMBRE')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);

        $lista = SunatTipodocidentidad::find();
        $n = 1;
        foreach($lista as $tipodoc) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $tipodoc->id_tipodocidentidad, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $tipodoc->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
    }

    public function importarClientesAction() {
		ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 940);

		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $resp = $this->importar_clientes($this->request->getPost());
            echo json_encode($resp);
            exit();
        }
	}

    //sirve para actualizar e importar clientes
	public function importar_clientes($datapost) {
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Usuario';
			$resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) { 
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
			return $resp;
		}
		
		//Datos de Formulario
		$confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];

		if(!isset($_FILES['archivo'])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar un Archivo!';
			return $resp;
		}
		
		$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		if(!in_array($_FILES["archivo"]["type"], $allowedFileType)){
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No es un archivo válido!';
			return $resp;
		}

		if($confirmacion != 'si') {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Necesitamos tu Confirmación!';
			$resp['mensaje'] = '<strong>Necesitamos tu confirmación para proceder con la importación de todos los clientes ¿Deseas Continuar?, Recuerda que una vez importados ya no podrás eliminar los clientes!</strong>';
			return $resp;
		}
		
		$nombre_temporal = 'temp'.uniqid().$_FILES['archivo']['name'];
		$rutaArchivo = $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/temporales/".$nombre_temporal;
		move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo);
		
		$documento = IOFactory::load($rutaArchivo);
		$totalDeHojas = $documento->getSheetCount();

		$hojaActual = $documento->getSheet(0); //extraemos datos de la primera hoja solamente
		$numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
		$numeroMayorDeColumna = 21;

		if($numeroMayorDeFila >= 1701) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Solo se permite subir un máximo de 700 Clientes por archivo!';
			return $resp;
		}
		
        $herramientas = new HerramientasController;

		$array_code_prod = array();
		$array_clientes = array();
        
		for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
			$n = $indiceFila - 1;
			$tipo_doc_identidad = $hojaActual->getCell("A".$indiceFila)->getValue();
			$codigo = trim($hojaActual->getCell("B".$indiceFila)->getValue());
			$num_documento = !empty($hojaActual->getCell("C".$indiceFila)->getValue())?trim($hojaActual->getCell("C".$indiceFila)->getValue()):'';
			$nombre_cliente = $hojaActual->getCell("D".$indiceFila)->getValue();
			$direccion = !empty($hojaActual->getCell("E".$indiceFila)->getValue())?trim($hojaActual->getCell("E".$indiceFila)->getValue()):'';
			$codigo_ubigeo = !empty($hojaActual->getCell("F".$indiceFila)->getValue())?trim($hojaActual->getCell("F".$indiceFila)->getValue()):'';
			$email = !empty($hojaActual->getCell("G".$indiceFila)->getValue())?trim($hojaActual->getCell("G".$indiceFila)->getValue()):'';
			$celular = !empty($hojaActual->getCell("H".$indiceFila)->getValue())?trim($hojaActual->getCell("H".$indiceFila)->getValue()):'';
			$telefono = !empty($hojaActual->getCell("I".$indiceFila)->getValue())?trim($hojaActual->getCell("I".$indiceFila)->getValue()):'';
			$num_cuenta_detraccion = !empty($hojaActual->getCell("J".$indiceFila)->getValue())?trim($hojaActual->getCell("J".$indiceFila)->getValue()):'';
			$detalle = !empty($hojaActual->getCell("K".$indiceFila)->getValue())?trim($hojaActual->getCell("K".$indiceFila)->getValue()):'';
			

			if(empty($tipo_doc_identidad) && empty($codigo) && empty($num_documento) && empty($nombre_cliente) && empty($direccion) && empty($codigo_ubigeo) && empty($email) && empty($celular) && empty($telefono) && empty($num_cuenta_detraccion) && empty($detalle)) {
				break;
			}
            
            $tipo_documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $tipo_doc_identidad)));
            if(!$tipo_documento_identidad) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un Tipo de Documento de Identificación Inválido';
                return $resp;
            }
            
            $registro_codigo = Cliente::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'id_contribuyente' => $usuario->id_contribuyente)));

            //Consultas
            if($codigo_ubigeo != '') {
                $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $codigo_ubigeo)));
                if(!$sql_ubigeo){
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', se está utilizando un código de ubigeo inválido';
                    return $resp;
                }
            }

            $tipo = 'nuevo';
            $cliente_bd = Cliente::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad: and num_doc = :num_doc: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_tipodocidentidad' => $tipo_doc_identidad, 'num_doc' => $num_documento, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$cliente_bd) {
                if($registro_codigo) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', se está utilizando un código utilizado por otro cliente.';
                    return $resp;
                }

                if(empty($codigo)){
                    $codigo = $herramientas->gettoken(10);
                }
                
                $tipo = 'insertar';
            } else {
                $tipo = 'actualizar';

                if(empty($codigo)){
                    $codigo = $cliente_bd->codigo;
                }
            }

            $array_clientes[] = array(
                "id_tipodocidentidad"		=> $tipo_doc_identidad,
                "num_doc"			        => $num_documento,
                "razon_social"		        => $nombre_cliente,
                "direccion_fiscal"		    => $direccion,
                "id_cod_ubigeo"		        => $codigo_ubigeo,
                "codigo"		            => $codigo,
                "email"	                    => $email,
                "celular"			        => $celular,
                "telefono"				    => $telefono,
                "num_cuenta_detraccion"		=> $num_cuenta_detraccion,
                "detalle_adicional"			=> $detalle,
                "tipo"                      => $tipo
            );
		}
        
		$this->db->begin();
		$fecha_registro = date('Y-m-d H:i:s');
		$n = -1;
		$num_decimales = 2;
		if($contribuyente->num_decimales > 2) {
			$num_decimales = $contribuyente->num_decimales;
		}
		foreach($array_clientes as $item) {
			$item = (object)$item;
			$n++;

            $cliente = Cliente::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad: and num_doc = :num_doc: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_tipodocidentidad' => $item->id_tipodocidentidad, 'num_doc' => $item->num_doc, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$cliente) {
                $cliente = new Cliente();
                $cliente->id_tipodocidentidad = $item->id_tipodocidentidad;
                $cliente->num_doc = $item->num_doc;
                $cliente->fecha_registro = $fecha_registro;
                $cliente->id_contribuyente = $usuario->id_contribuyente;
            }
            
            $cliente->estado = 'activo';
            $cliente->razon_social = $item->razon_social;

            if(!empty($item->direccion_fiscal)) {
                $cliente->direccion_fiscal = $item->direccion_fiscal;
            }

            if(!empty($item->id_cod_ubigeo)) {
                $cliente->id_cod_ubigeo = $item->id_cod_ubigeo;
            }

            $cliente->codigo = $item->codigo;
            $cliente->email = $item->email;

            if(!empty($item->celular)) {
                $cliente->celular = $item->celular;
            }
            
            if(!empty($item->telefono)) {
                $cliente->telefono = $item->telefono;
            }

            if(!empty($item->num_cuenta_detraccion)) {
                $cliente->num_cuenta_detraccion = $item->num_cuenta_detraccion;
            }

            if(!empty($item->detalle_adicional)) {
                $cliente->detalle_adicional = $item->detalle_adicional;
            }
            
            try {
                if(!$cliente->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($cliente->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Error al guardar los datos del cliente! '.$e->getMessage();
                return $resp;
            }
		}

		unlink($rutaArchivo);
		$this->db->commit();
		//$this->db->rollback();
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Genial!';
		$resp['mensaje'] = 'Se importaron '.count($array_clientes).' clientes correctamente!';
		return $resp;
	}
}
?>