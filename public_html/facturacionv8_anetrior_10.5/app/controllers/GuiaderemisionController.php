<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuiaderemisionController extends ControllerBase {
    public function indexAction($tipo_doc_guardado = '', $serie_doc_guardado = '', $numero_doc_guardado = '') {
        //tipo_doc_guardado, numero_doc_guardado, 'NOTA DE VENTA', light, serie_doc_guardado
		$this->setTitle('Guía de remisión');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
        ->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css")
        ->addCss($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.min.css")
        ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.date.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/picker.time.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/pickadate/legacy.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/daterangepicker.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/anytime.min.js?i=v2")

        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
        ->addJs($this->baseUri . "public/js/guiaremision_flexdatalist.js?i=".rand())
		->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/apisunat.js?i=".rand())
        ->addJs($this->baseUri . "public/js/guiaderemision.js?i=".rand());

        //Para sesion
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        
        $this->view->motivos_de_traslado = SunatMotivotraslado::find();
        $this->view->modalidades_de_traslado = SunatModalidadtraslado::find();
        $this->view->codigos_de_puerto = SunatCodigopuerto::find();
        //Para clientes
        $this->view->tipo_identidad = SunatTipodocidentidad::find();
        $this->view->ubigeo = SunatCodigoubigeo::find();
        
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
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
        
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
        }

        $this->view->tipo_doc_guardado = $tipo_doc_guardado;
        $this->view->serie_doc_guardado = $serie_doc_guardado;
        $this->view->numero_doc_guardado = $numero_doc_guardado;
        $this->view->modalidad_envio_sunat = $contribuyente->modalidad_envio_sunat;
    }

    public function sugerenciasDireccionesLlegadaAction($id_cliente = '') {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $array_lista = array();
            if(!$usuario) {
                echo json_encode($array_lista);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                echo json_encode($array_lista);
                exit();
            }

            $datapost = $this->request->getPost();
            $id_cliente = intval($datapost['id_cliente']) + 0;
            if($id_cliente <= 0) {
                echo json_encode($array_lista);
                exit();
            }

            $query = "SELECT DISTINCT dir_destino, id_ubigeo_destino, sunat_codigoubigeo.departamento, sunat_codigoubigeo.provincia, sunat_codigoubigeo.distrito  FROM `doc_electronico` INNER JOIN sunat_codigoubigeo on doc_electronico.id_ubigeo_destino = sunat_codigoubigeo.codigo_ubigeo where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '09' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat' and idcliente = $id_cliente";

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array('dir_destino' => $fila->dir_destino, 'codigo_ubigeo' => $fila->id_ubigeo_destino, 'ubicacion' => $fila->departamento.' - '.$fila->provincia.' - '.$fila->distrito);
            }

            echo json_encode($array_lista);
            exit();
        }
    }

    public function sugerenciasTransportistaAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            $array_lista = array();
            if(!$usuario) {
                echo json_encode($array_lista);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                echo json_encode($array_lista);
                exit();
            }

            $tipo_doc_transporte = intval($datapost['id_tipodoc']);

            if($tipo_doc_transporte != 1 && $tipo_doc_transporte != 6) {
                echo json_encode($array_lista);
                exit();
            }
            
            if($tipo_doc_transporte == 1) { //dni
                $query = "SELECT DISTINCT transporte_nro_placa, id_tipo_documento_transporte, nro_documento_transporte, razon_social_transporte, licencia_conductor FROM `doc_electronico` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '09' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat' and id_tipo_documento_transporte = '1'";
            }

            if($tipo_doc_transporte == 6) {
                $query = "SELECT DISTINCT nro_documento_transporte, id_tipo_documento_transporte, razon_social_transporte, licencia_conductor FROM `doc_electronico` where id_contribuyente = $contribuyente->id_contribuyente and id_tipodoc_electronico = '09' and tipo_envio_sunat = '$contribuyente->tipo_envio_sunat' and id_tipo_documento_transporte = '6'";
            }
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
            
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                if($tipo_doc_transporte == 1) { //dni
                    $array_lista[] = array('razon_social' => $fila->razon_social_transporte, 'placa' => $fila->transporte_nro_placa, 'num_doc' => $fila->nro_documento_transporte, 'licencia_conducir' => empty($fila->licencia_conductor)?'':$fila->licencia_conductor);
                }

                if($tipo_doc_transporte == 6) {
                    $array_lista[] = array('razon_social' => $fila->razon_social_transporte, 'placa' => '', 'num_doc' => $fila->nro_documento_transporte, 'licencia_conducir' => empty($fila->licencia_conductor)?'':$fila->licencia_conductor);
                }
            }

            echo json_encode($array_lista);
            exit();
        }
    }

    public function getEmpresastransporteAction() {
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
            $lista = EmpresaTransporte::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
    }

    public function getListaConductoresAutosAction() {
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

            $id_etransporte = intval($datapost['id_etransporte']) + 0;
            
            $empresa_transporte = EmpresaTransporte::findFirst(array("id_etransporte = :id_etransporte: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_etransporte' => $id_etransporte, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$empresa_transporte) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la empresa de transporte seleccionada';
                echo json_encode($resp);
                exit();
            }

            $conductores = Conductor::find(array("id_etransporte = :id_etransporte:", 'bind' => array('id_etransporte' => $id_etransporte)));
            $vehiculos = Vehiculo::find(array("id_etransporte = :id_etransporte:", 'bind' => array('id_etransporte' => $id_etransporte)));

            $resp['respuesta'] = 'ok';
            $resp['conductores'] = $conductores;
            $resp['vehiculos'] = $vehiculos;
            echo json_encode($resp);
            exit();
        }
    }

    public function getDocumentosClienteAction() {
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

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $this->view->disable();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
            }
            
            $idcliente = intval($datapost['idcliente']) + 0;
            $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));

            try {
                $documentos = DocElectronico::find(array("id_contribuyente = :id_contribuyente: and (id_tipodoc_electronico = '01' or id_tipodoc_electronico = '03') and tipo_envio_sunat = :tipo_envio_sunat: and idcliente = :idcliente: and estado_envio_sunat = 'aceptado'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'idcliente' => $idcliente)));
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $listadocumentos = array();
            foreach ($documentos as $documento) {
				$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));
                $listadocumentos[] = array('id' => $documento->id_tipodoc_electronico.','.$documento->serie_comprobante.','.$documento->numero_comprobante, 'text' => $tipo_doc_electronico->descripcion.': '.$documento->serie_comprobante.'-'.$documento->numero_comprobante.' [Fecha Registro: '.date("d-m-Y / H:i A", strtotime($documento->fecha_registro)).']');
            }

            $resp['respuesta'] = 'ok';
            $resp['listadocumentos'] = $listadocumentos;
            echo json_encode($resp);
            exit();
        }
    }

    public function get_guias_remision_cliente($id_contribuyente, $idcliente, $tipo_envio_sunat) {
        $idcliente = intval($idcliente) + 0;
        $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $id_contribuyente)));
    
        $listadocumentos = array();

        if(!$cliente) {
            return $listadocumentos;
        }

        try {
            $documentos = DocElectronico::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = '09' and tipo_envio_sunat = :tipo_envio_sunat: and idcliente = :idcliente:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'idcliente' => $idcliente)));
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }
        
        foreach ($documentos as $documento) {
            $tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));
            $listadocumentos[] = array('id' => $documento->id_tipodoc_electronico.','.$documento->serie_comprobante.','.$documento->numero_comprobante, 'text' => $tipo_doc_electronico->descripcion.': '.$documento->serie_comprobante.'-'.$documento->numero_comprobante.' [Fecha Registro: '.date("d-m-Y / H:i A", strtotime($documento->fecha_registro)).']');
        }

        return $listadocumentos;
    }

    public function guardarGuiaRemisionAction() {
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
            
            $resp = $this->validar_y_procesar_guia_remision($usuario, $datapost);
            echo json_encode($resp);
            exit();
        }
    }

    public function validar_y_procesar_guia_remision($usuario, $datapost) {
        $herramientas = new HerramientasController;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra el contribuyente al que pertenece el usuario!';
            echo json_encode($resp);
            exit();
        }

        $gestion_de_contribuyentes = new GestiondecontribuyentesController;
        if($gestion_de_contribuyentes->get_value_in_option_system($contribuyente->id_contribuyente, 'permitir_emision_guias_remision') == 'no') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Se ha restringido la emisión de guías de remisión para este contribuyente!';
            return $resp;
        }
        
        $idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
        $array_fecha_documento = explode('/',!isset($datapost['fecha_comprobante'])?date('d/m/Y'):$datapost['fecha_comprobante']);
        $fecha_documento = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
        if (!(DateTime::createFromFormat('Y-m-d', $fecha_documento) !== FALSE)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La fecha asignada al documento no es válida!';
            return $resp;
        }
        
        $idcliente = !isset($datapost['idcliente'])?'':intval($datapost['idcliente']);

        //Validando Sucursal
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
        if(!$sucursal) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La Sucursal no Existe!';
            return $resp;
        }

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') == 'prohibir') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Se ha restringido la creación de documentos electrónicos para su cuenta!';
                        return $resp;
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') == 'sucursal_asignada') {
                        if($usuario->idsucursal != $idsucursal) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'No Tiene Permisos para Crear Ventas en la Sucursal con ID: '.$idsucursal;
                            return $resp;
                        }
                    }
                }
            }
        }

        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos') == 'acceso_denegado') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Se ha restringido la creación de Guías de Remisión para su cuenta!';
                    return $resp;
                }

                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'guias_remision') == false) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Se ha restringido la creación de Guías de Remisión para su cuenta!';
                    return $resp;
                }
            }
        }

        //Validando Cliente
        $cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
        if(!$cliente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El cliente seleccionado no existe!';
            return $resp;
        }

        if(!empty($datapost['cliente_email'])) {
            if($herramientas->verificar_validez_email($datapost['cliente_email'])) {
                $cliente->email = $datapost['cliente_email'];
                $resp_save_cliente = $cliente->save();
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Email Ingresado es Inválido!';
                return $resp;
            }
        }
        
        //Datos del traslado
        $id_motivo_traslado = !isset($datapost['motivo_traslado'])?'':$datapost['motivo_traslado'];
        $id_modalidad_traslado = !isset($datapost['modalidad_traslado'])?'':$datapost['modalidad_traslado'];

        $array_fecha_traslado = explode('/',!isset($datapost['fecha_traslado'])?date('d/m/Y'):$datapost['fecha_traslado']);
        $fecha_traslado = $array_fecha_traslado[2].'-'.$array_fecha_traslado[1].'-'.$array_fecha_traslado[0];
        if (!(DateTime::createFromFormat('Y-m-d', $fecha_traslado) !== FALSE)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La fecha de traslado no es válida!';
            return $resp;
        }

        $otro_motivo_traslado = !isset($datapost['otro_motivo_traslado'])?'':$datapost['otro_motivo_traslado'];
        $pesobruto = empty($datapost['pesobruto'])?0:floatval($datapost['pesobruto']);
        $numero_bultos = empty($datapost['numero_bultos'])?0:intval($datapost['numero_bultos']);
        $numero_contenedor = empty($datapost['numero_contenedor'])?null:$datapost['numero_contenedor'];
        $id_codigopuerto = (empty($datapost['codigo_puerto']) || $datapost['codigo_puerto'] == '-')?null:$datapost['codigo_puerto'];

        //validando los datos del traslado
        $motivo_traslado = SunatMotivotraslado::findFirst(array("id_motivotraslado = :id_motivotraslado:", 'bind' => array('id_motivotraslado' => $id_motivo_traslado)));
        if(!$motivo_traslado) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El motivo de traslado no es válido!';
            return $resp;
        }

        if($id_motivo_traslado == '13') {
            if(empty($otro_motivo_traslado)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si usted ha seleccionado la opción OTROS en motivo de traslado, entonces debe ingresar via texto el motivo del traslado!';
                return $resp;
            }
        }

        $modalidad_traslado = SunatModalidadtraslado::findFirst(array("id_modalidadtraslado = :id_modalidadtraslado:", 'bind' => array('id_modalidadtraslado' => $id_modalidad_traslado)));
        if(!$modalidad_traslado) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La modalidad de traslado elegida no es válida!';
            return $resp;
        }

        if($pesobruto <= 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el peso total!';
            return $resp;
        }

        if($numero_bultos <= 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar el número de bultos!!';
            return $resp;
        }

        if(!empty($id_codigopuerto)) {
            $codigo_puerto_bd = SunatCodigopuerto::findFirst(array("id_codigopuerto = :id_codigopuerto:", 'bind' => array('id_codigopuerto' => $id_codigopuerto)));
            if(!$codigo_puerto_bd) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Código de Puerto Seleccionado no Existe!';
                return $resp;
            }
        }
        
        //datos empresa transporte o datos conductor
        $id_tipo_documento_transporte = !isset($datapost['transporte_tipo_docidentidad'])?'':$datapost['transporte_tipo_docidentidad'];
        $nro_documento_transporte = !isset($datapost['transporte_numerodocumento'])?'':trim($datapost['transporte_numerodocumento']);
        $razon_social_transporte = !isset($datapost['transporte_nombre'])?'':trim($datapost['transporte_nombre']);
        $transporte_nro_placa = !isset($datapost['transporte_num_placa'])?'':trim($datapost['transporte_num_placa']);
        $licencia_conductor = !isset($datapost['conductor_num_licencia'])?'':trim($datapost['conductor_num_licencia']);
        
        $tipo_documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipo_documento_transporte)));
        if(!$tipo_documento_identidad) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar un tipo de documento de identidad válido!..';
            return $resp;
        }

        if($id_modalidad_traslado == '01') {
            //Transporte Público
            if($id_tipo_documento_transporte != '6') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si vas a utilizar el transporte público, entonces debes ingresar el NÚMERO DE RUC de la empresa de transportes!';
                return $resp;
            }

            if(strlen($nro_documento_transporte) != 11 ) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Recuerda que el número de RUC debe tener 11 Caracteres!';
                return $resp;
            }

            if(empty($razon_social_transporte)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar la razón social de la empresa de transportes!';
                return $resp;
            }
        } else {
            //Transporte Privado
            if(!in_array($id_tipo_documento_transporte, array('0', '1', '4', '7', 'A', 'B', 'C', 'D'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si vas a utilizar un transporte privado debes seleccionar "DNI" como tipo de documento!';
                return $resp;
            }

            if($id_tipo_documento_transporte == '1') {
                if(strlen($nro_documento_transporte) != 8 ) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El número de DNI debe tener 8 caracteres!';
                    return $resp;
                }
            } else {
                if(empty($nro_documento_transporte)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Debes Ingresar un número de documento!';
                    return $resp;
                }
            }

            if(empty($razon_social_transporte)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Ingresar el Nombre del Conductor!';
                return $resp;
            }

            if(empty($transporte_nro_placa)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Si vas a utilizar un transporte privado, debes ingresa el número de placa!';
                return $resp;
            }

            if(empty($licencia_conductor)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Según Resolución RS-SUNAT 123-2022, se debe ingresar la licencia de conducir en transporte privado!';
                return $resp;
            }
        }
        
        //Punto de Partida
        $direccionpartida = !isset($datapost['direccionpartida'])?'':$datapost['direccionpartida'];
        $id_ubigeo_partida = !isset($datapost['ubigeo_partida'])?'':$datapost['ubigeo_partida'];

        //Punto de Llegada
        $direccionllegada = !isset($datapost['direccionllegada'])?'':$datapost['direccionllegada'];
        $id_ubigeo_llegada = !isset($datapost['ubigeo_llegada'])?'':$datapost['ubigeo_llegada'];

        if(empty($direccionpartida)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar la dirección de partida!';
            return $resp;
        }

        $ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_partida)));
        if(!$ubigeo_partida) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El código de ubigeo de partida seleccionado no es válido!';
            return $resp;
        }

        if(empty($direccionllegada)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingresar la dirección de llegada o destino!';
            return $resp;
        }

        $ubigeo_llegada = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_ubigeo_llegada)));
        if(!$ubigeo_llegada) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El código de ubigeo de llegada seleccionado no es válido!';
            return $resp;
        }

        //Documentos Relacionados
        $serie_numero_docref_clie = '';
        $select_doc_referencia_bd = empty($datapost['select_doc_referencia'])?'':$datapost['select_doc_referencia'];
        if(!empty($select_doc_referencia_bd)) {
            $array_doc_referencia_clie = explode(',', $select_doc_referencia_bd);
            $id_tipodoc_referencia_clie = $array_doc_referencia_clie[0];
            $serie_doc_referencia_clie = $array_doc_referencia_clie[1];
            $num_doc_referencia_clie = $array_doc_referencia_clie[2];

            $documento_ref_clie = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and idcliente = :idcliente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_referencia_clie, 'serie_comprobante' => $serie_doc_referencia_clie, 'numero_comprobante' => $num_doc_referencia_clie,'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'idcliente' => $idcliente)));
            if(!$documento_ref_clie) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento de referencia seleccionado no existe!';
                return $resp;
            }

            $serie_numero_docref_clie = $documento_ref_clie->serie_comprobante.'-'.$documento_ref_clie->numero_comprobante;
        }

        $select_tipo_doc_referencia = $datapost['select_tipo_doc_referencia'];
        if($select_tipo_doc_referencia != '01' && $select_tipo_doc_referencia != '03') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El Tipo de Documento de Referencia Seleccionado no es Válido!';
            return $resp;
        }

        $serie_doc_ref = $datapost['serie_doc_referencia'];
        $num_doc_ref = $datapost['numero_doc_referencia'];
        $serie_numero_doc_referencia = $serie_doc_ref.'-'.$num_doc_ref;

        $detalle_guia = json_decode($datapost['detalle_guia']);
        if(empty($detalle_guia)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes agregar al menos un producto al listado!';
            return $resp;
        }
        
        $lista_detalle = array();
        $n = 0;
        foreach($detalle_guia as $item) {
            $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item->idarticulo, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Producto';
                $resp['mensaje'] = 'El producto: '.$item->descripcion.', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
                return $resp;
            }
            
            //RECIBIR LA UNIDAD DE MEDIDA
            $idunidad_medida = $item->idunidadmedida;
            $idunidad_medida = str_replace('UND-', '', $idunidad_medida);
            $idunidad_medida = intval($idunidad_medida);
            $tipo_unidad_medida = 'UND';
            $id_presentacion_producto = null;
            if (strpos($item->idunidadmedida, '-') !== false) {
                //existe un guión
                $array_unidad_medida = explode('-', $item->idunidadmedida);
                if(count($array_unidad_medida) > 1) {
                    $tipo_unidad_medida = ($array_unidad_medida[0] == 'PRE')?'PRE':'UND'; //PRE: presentacion, UND: unidad_medida

                    if($tipo_unidad_medida == 'PRE') {
                        //validar si existe la presentación
                        $id_presentacion_producto = $array_unidad_medida[1];
                        $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion: and estado = 'activo'", 'bind' => array('id_presentacion' => $id_presentacion_producto)));
                        if(!$presentacion) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = "En el item N° $n para el producto: $item->descripcion, usted ha seleccionado una unidad inválida!";
                            return $resp;
                        }

                        $idunidad_medida = intval($presentacion->idunidad);
                    } else {
                        $idunidad_medida = intval($array_unidad_medida[1]);
                    }
                }
            }
            
            $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad_medida)));
            if(!$unidadmedida) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La Unidad de medida Seleccionada para el producto no es correcta: (Prod: '.$item->descripcion.' y Unidad Medida: '.$item->idunidadmedida.')';
                return $resp;
            }

            $n++;
            $lista_detalle[] = array(
                "ITEM_DET"          		=> $n,
                "PESO_DET"      				=> $item->peso,
                "CANTIDAD_DET"              => $item->cantidad,
                "DESCRIPCION_DET"   	    => $item->descripcion,
                "CODIGO_PRODUCTO"           => $producto->codigo,
                "UNIDAD_MEDIDA_ID_DET"      => $unidadmedida->idunidad,
                "UNIDAD_MEDIDA_DET"         => $unidadmedida->codigo,
                "CODIGO_DET"                => $producto->idproducto,
                "IDPRODUCTO_DET"		    => $producto->idproducto,
                "TIPO_UNIDAD"				=> $tipo_unidad_medida,
                "ID_PRESENTACION"			=> $id_presentacion_producto
            );
        }
        
        $modalidad_envio_sunat = $datapost['modalidad_envio_sunat'];
        if($modalidad_envio_sunat != 'solo_firma' && $modalidad_envio_sunat != 'inmediato' && $modalidad_envio_sunat != 'no_enviar') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Producto';
            $resp['mensaje'] = 'Debes seleccionar una modalidad de envío válida!';
            return $resp;
        }

        //Extrayendo número correlativo
        $documentoelectronico = new DocumentoelectronicoController;
        $numero_comprobante = $documentoelectronico->get_numero_doc_electronico($contribuyente->id_contribuyente, '09', $sucursal->guia_remision_serie, $sucursal->idsucursal);

        //$serie_numero_docref_clie = $documento_ref_clie->serie_comprobante.'-'.$documento_ref_clie->numero_comprobante;
        //$serie_doc_ref = $datapost['serie_doc_referencia'];
        //$num_doc_ref = $datapost['numero_doc_referencia'];
        //$serie_numero_doc_referencia = $serie_doc_ref.'-'.$num_doc_ref;
        
        //Documentos Relacionados
        $array_docs_referencia = array();
        if(!empty($serie_numero_docref_clie)) {
            $array_docs_referencia[] = array(
                "id_tipodoc_electronico" => $documento_ref_clie->id_tipodoc_electronico,
                "serie_comprobante" => $documento_ref_clie->serie_comprobante,
                "numero_comprobante" => $documento_ref_clie->numero_comprobante
            );
        }

        if(!empty($serie_doc_ref) && !empty($num_doc_ref)) {
            $array_docs_referencia[] = array(
                "id_tipodoc_electronico" => $select_tipo_doc_referencia,
                "serie_comprobante" => $serie_doc_ref,
                "numero_comprobante" => $num_doc_ref
            );
        }
        

        //Cabecera
        $cabecera_inicial['id_tipodoc_electronico'] = '09';
        $cabecera_inicial['idsucursal'] = $sucursal->idsucursal;
        $cabecera_inicial['idcliente'] = $cliente->idcliente;
        $cabecera_inicial['fecha_documento'] = $fecha_documento;
        $cabecera_inicial['numero_comprobante'] = $numero_comprobante;
        $cabecera_inicial['serie_comprobante'] =  $sucursal->guia_remision_serie;
        $cabecera_inicial['id_motivotraslado'] = $motivo_traslado->id_motivotraslado;
        $cabecera_inicial['motivo_traslado'] = ($motivo_traslado->id_motivotraslado != '13')?$motivo_traslado->descripcion:$otro_motivo_traslado;
        $cabecera_inicial['id_modalidadtraslado'] = $modalidad_traslado->id_modalidadtraslado;
        $cabecera_inicial['modalidad_traslado'] = $modalidad_traslado->descripcion;
        $cabecera_inicial['fecha_traslado'] = $fecha_traslado;

        //$cabecera_inicial['empresa_transporte'] = $empresa_transporte;
        //$cabecera_inicial['conductor'] = $conductor;
        //$cabecera_inicial['vehiculo'] = $vehiculo;

        $cabecera_inicial['id_tipo_documento_transporte'] = $id_tipo_documento_transporte;
        $cabecera_inicial['nro_documento_transporte'] = $nro_documento_transporte;
        $cabecera_inicial['razon_social_transporte'] = $razon_social_transporte;
        $cabecera_inicial['transporte_nro_placa'] = $transporte_nro_placa;
        $cabecera_inicial['licencia_conductor'] = $licencia_conductor;
        
        $cabecera_inicial['array_docs_referencia'] = $array_docs_referencia;


        //Extrayendo la data del emisor
        $documentoelectronico = new DocumentoelectronicoController;
        $resp_data_emisor = $documentoelectronico->get_data_emisor($usuario->id_contribuyente);
        if($resp_data_emisor['respuesta'] == 'error') {
            return $resp_data_emisor;
        }
        $emisor = $resp_data_emisor['emisor'];
        $confirmacion = !empty($datapost['confirmacion'])?$datapost['confirmacion']:'no';
        if($confirmacion == 'no') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Necesitamos tu Confirmación!';
            $resp['mensaje'] = '¿Realmente deseas crear esta guía de remisión? Recuerda que no podrás anular la guía de remisión.';
            return $resp;
        }
        
        $resp_procesamiento = $this->procesar_guia_remision($contribuyente->id_contribuyente, $usuario->idusuario, $cabecera_inicial, $lista_detalle, $emisor, $cliente, $datapost);
        if($resp_procesamiento['respuesta'] == 'ok') {
            if($herramientas->verificar_validez_email($datapost['cliente_email'])) {
                $enviar_email = new EnviaremailController;
                $resp_email = $enviar_email->enviar_email_documento($resp_procesamiento['documento']['id_contribuyente'], $resp_procesamiento['documento']['id_tipodoc_electronico'], $resp_procesamiento['documento']['serie_comprobante'], $resp_procesamiento['documento']['numero_comprobante'], $resp_procesamiento['documento']['tipo_envio_sunat']);
            }
        }
        return $resp_procesamiento;
    }

    public function procesar_guia_remision($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
        $herramientas = new HerramientasController;
		$resp_modalidad_envio = $herramientas->get_modalidad_envio_sunat($emisor, $datapost);
		if($resp_modalidad_envio['respuesta'] == 'error') {
			return $resp_modalidad_envio;
        }

        $modalidad_envio_sunat = $resp_modalidad_envio['modalidad_envio_sunat'];

        //1.- Guardamos en la base de datos el documento electrónico, pero siempre con un estado PENDIENTE!
		$resp_guardado_bd = $this->guardar_guiaremision_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		if($resp_guardado_bd['respuesta'] == 'error') {
			return $resp_guardado_bd;
        }

        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        //id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
		$string_encrypted_document_a4 = $herramientas->encriptar("$id_contribuyente||".$resp_guardado_bd['id_tipodoc_electronico']."||".$resp_guardado_bd['serie_comprobante']."||".$resp_guardado_bd['numero_comprobante']."||".$resp_guardado_bd['tipo_envio_sunat']."||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$id_contribuyente||".$resp_guardado_bd['id_tipodoc_electronico']."||".$resp_guardado_bd['serie_comprobante']."||".$resp_guardado_bd['numero_comprobante']."||".$resp_guardado_bd['tipo_envio_sunat']."||ticket");

		$url_a4 = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$url_ticket = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
        
        $html_botones_impresion = '
		<span class="heading-btn-group">
			<a href="'.$url_a4.'" target="_blank" class="btn btn-link btn-float text-size-small has-text legitRipple"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 50px;"> <span> A4 - PDF</span></a>
            <a href="'.$url_ticket.'" target="_blank" class="btn btn-link btn-float text-size-small has-text legitRipple"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 50px;">  <span> Ticket - PDF</span></a>
		</span>
        ';

        if(isset($datapost['enviar_a_sunat'])) {
            if($datapost['enviar_a_sunat'] == 'no') {
                $modalidad_envio_sunat = 'no_enviar';
            }
        }
        
        if($modalidad_envio_sunat == 'no_enviar') {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Completo';
			$resp['documento'] = $resp_guardado_bd;
			$resp['mensaje'] = 'Se guardó correctamente el documento: <strong>'.$resp_guardado_bd['serie_comprobante'].'-'.$resp_guardado_bd['numero_comprobante'].'</strong>.<br /><br /><strong>RECUERDA:</strong> El documento no ha sido enviado a SUNAT, por tanto usted debe enviarlo utilizando la pantalla de reporte de documentos!'.'<br />'.$html_botones_impresion;
			return $resp;
        }
        
        //2.- Enviamos hacia la api de factura.php
		$resp_respuesta_api = $this->enviar_guia_remision_api($resp_guardado_bd, $modalidad_envio_sunat);
		if($resp_respuesta_api['respuesta'] == 'error') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en API';
            $resp['documento'] = $resp_guardado_bd;
			$resp['mensaje'] = 'Se guardó correctamente en la base de datos, <span class="text-danger">sin embargo no se logró enviar correctamente a Sunat, el documento se guardó como pendiente de envío a SUNAT</span>';
            $resp['repuesta_api'] = $resp_respuesta_api['mensaje'];
			return $resp;
		}
        
        $resp_respuesta_api['documento'] = $resp_guardado_bd;
		$resp_respuesta_api['mensaje'] = $resp_respuesta_api['mensaje'].'<br />'.$html_botones_impresion;
		return $resp_respuesta_api;
    }

    public function enviar_guia_remision_api($data_doc_bd, $modalidad_envio_sunat) {
        $id_contribuyente = $data_doc_bd['id_contribuyente'];
		$tipo_doc_electronico = $data_doc_bd['id_tipodoc_electronico'];
		$serie_comprobante = $data_doc_bd['serie_comprobante'];
        $numero_comprobante = $data_doc_bd['numero_comprobante'];

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el contribuyente!';
			return $resp;
		}

		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_doc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			return $resp;
		}

		$sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idsucursal' => $documento->id_sucursal)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'No encontramos la Sucursal a la que pertenece el documento, por favor avise a SOPORTE!';
			return $resp;
        }
        


		$idcliente = $documento->idcliente;

		$documentoelectronico = new DocumentoelectronicoController;
		$resp_secret_data = $documentoelectronico->get_secret_data($id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
		}

		$resp_data_emisor = $documentoelectronico->get_data_emisor($id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
		}

		$resp_data_cliente = $documentoelectronico->get_data_cliente($idcliente);
		if($resp_data_cliente['respuesta'] == 'error') {
			return $resp_data_emisor;
		}

		$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($id_contribuyente, $tipo_doc_electronico, $serie_comprobante, $numero_comprobante);
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$emisor = $resp_data_emisor['emisor'];
		$cliente = $resp_data_cliente['data_cliente'];
        $detalle = $resp_detalle['detalle'];
        
        $data_guia_remision = array(
            //Cabecera del documento
            "serie_comprobante"             => $documento->serie_comprobante,
            "numero_comprobante"            => $documento->numero_comprobante,
            "fecha_comprobante"             => $documento->fecha_comprobante,
            "cod_tipo_documento"            => $documento->id_tipodoc_electronico,
            "cod_sucursal_sunat"			=> $sucursal->codigo,
            
            //Datos del Traslado
            "id_motivotraslado" 			=> $documento->id_motivotraslado,
            "motivo_traslado"				=> $documento->motivo_traslado,
            "peso"							=> $documento->peso,
            "numero_paquetes"				=> $documento->numero_paquetes,
            "id_codigopuerto"               => $documento->id_codigopuerto,
            "numero_contenedor"             => $documento->numero_contenedor,
            "id_modalidadtraslado"			=> $documento->id_modalidadtraslado, //01 Transporte público, 02 Transporte privado
            "fecha_traslado"                => $documento->fecha_traslado,

            //Transportista
            "id_tipo_documento_transporte"	=> $documento->id_tipo_documento_transporte, //6: indica RUC: Catálogo 06
            "nro_documento_transporte"		=> $documento->nro_documento_transporte,
            "razon_social_transporte"		=> $documento->razon_social_transporte,
            'transporte_nro_placa'          => $documento->transporte_nro_placa,
            'nro_licencia_conductor'        => $documento->licencia_conductor,

            /*
            'id_tipodoc_conductor'          => $documento->id_tipodoc_conductor,
            'num_doc_conductor'             => $documento->num_doc_conductor,
            'nombre_completo_conductor'     => $documento->nombre_completo_conductor,
            */

            //Punto Llegada
            "id_ubigeo_destino"				=> $documento->id_ubigeo_destino,
            "dir_destino"					=> $documento->dir_destino,

            //Punto Partida
            "id_ubigeo_partida"				=> $documento->id_ubigeo_partida,
            "dir_partida"					=> $documento->dir_partida,

            //documentos relacionados o de referencia
            "docs_referencia"               => json_decode($documento->array_docs_referencia),
            "nota"							=> $documento->nota
        );

        $data = array_merge($data_guia_remision, $cliente);
        $data['cabecera'] = $data_guia_remision;
        $data['emisor'] = $emisor;
        $data['detalle'] = $detalle;
        $data['cliente'] = $cliente;
		$data['tipo_proceso'] = $documento->tipo_envio_sunat;
		$data['modalidad_envio_sunat'] = $modalidad_envio_sunat;
		$data['secret_data'] =  $resp_secret_data['secret_data'];
        $data['hash_cpe'] = empty($documento->hash_cpe)?'':$documento->hash_cpe;

        $data['tipo_envio_sistema'] = isset($data_doc_bd['tipo_envio_sistema'])?$data_doc_bd['tipo_envio_sistema']:'';
        
        $ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'guias_remision/';
		if (!file_exists($ruta_base_cpe_cdr)) {
			mkdir($ruta_base_cpe_cdr, 0777, true);
        }
        
        if(!empty($documento->name_xml_zip)) {
			$ruta_zip_file_xml = $ruta_base_cpe_cdr.$documento->name_xml_zip;
			if (file_exists($ruta_zip_file_xml)) {
				$data['content_file_zip_cpe'] = base64_encode(file_get_contents($ruta_zip_file_xml));
			}
        }
        
        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/nuevaguiasunat';
        $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
        
        $resp_api = json_decode($resp_api_ws);
        $resp['resp_api'] = $resp_api;
		if(empty($resp_api)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar correctamente a la sunat, el error devuelto es el siguiente: '.$resp_api_ws;
			return $resp;
        }
        
        if($modalidad_envio_sunat == 'solo_firma') {
			if($resp_api->respuesta == 'error') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!<br /><br /> ERROR: '.$resp_api->mensaje;
				return $resp;
			}

			$saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
			if (($saved_file === false) || ($saved_file == -1)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!';
				return $resp;
			}
			
			//$documento->estado_envio_sunat = 'pendiente';
			$documento->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
			$documento->ruta_xml = $data['secret_data']['ruta_dir_xml'];
			$documento->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
			$documento->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;
			$documento->estado_documento = 'activo';

            try {
                if(!$documento->save()) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['error_code'] = 'envio_sunat';
                    $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se envió correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego!';
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['error_code'] = 'envio_sunat';
                $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se envió correctamente. <br /> Sin embargo no se logró crear el documento electrónico correctamente, puedes intentarlo luego! <br /> ERROR: '.$e->getMessage();
                return $resp;
            }

			$resp['respuesta'] = "ok";
			$resp['titulo'] = 'Proceso Completado';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se firmó correctamente. <br /> Sin embargo recuerda que aún no ha sido enviado a sunat.<br>Hash CPE: '.$documento->hash_cpe;
			return $resp;
        }
        
        //si pasa aquí se supone que la modalidad es de enviar directo a sunat
		if($resp_api->respuesta == 'error' && $resp_api->mensaje != '4000') {
			$codio_error_sunat = isset($resp_api->cod_sunat)?$resp_api->cod_sunat:'';
			if ($codio_error_sunat == 'soap-env:Client.1032') {
				$documento->fecha_envio_sunat = date('Y-m-d H:i:s');
				$documento->estado_envio_sunat = 'rechazado';
				$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
				$documento->estado_documento = 'activo';
				$documento->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
				$documento->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
				$documento->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
				$documento->msje_sunat = !isset($resp_api->mensaje)?'':$resp_api->mensaje;
				$mensaje_api_sunat = !isset($resp_api->mensaje)?'':$resp_api->mensaje;

                try {
                    if(!$documento->save()) {
                        $msg = '';
                        foreach ($documento->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
                        $resp['respuesta'] = 'ok';
                        $resp['titulo'] = 'Envío Correcto';
                        $resp['error_code'] = 'envio_sunat';
                        $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' ha sido Rechazado. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
                        return $resp;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Envío Correcto';
                    $resp['error_code'] = 'envio_sunat';
                    $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' ha sido Rechazado. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
                    return $resp;
                }
	
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Proceso Terminado';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué rechazado por SUNAT.<br /><br />Mensaje Error Sunat: '.$mensaje_api_sunat;
				return $resp;

			} else {
				$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
				$resp_save = $documento->save();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.', se guardó correctamente. <br /> Sin embargo no se logró enviar a SUNAT, nosotros recomendamos verificar el estado del documento!<br /><br /> ERROR: '.$resp_api->mensaje;
				return $resp;
			}
        }
        
        if($resp_api->cod_sunat == '0' || $resp_api->cod_sunat == '2223' || $resp_api->mensaje == '4000') {
			//2223: El archivo ya fue presentado anteriormente (se entiende que esta factura ya fué enviada en una solicitud anterior, por tanto se la registra como enviada)
			//0: El archivo fué aceptado
			$documento->fecha_envio_sunat = date('Y-m-d H:i:s');
			$documento->estado_envio_sunat = 'aceptado';
			$documento->intentos_envio_sunat = intval($documento->intentos_envio_sunat) + 1;
			$documento->estado_documento = 'activo';
			$documento->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
			$documento->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
			$documento->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
			$documento->msje_sunat = !isset($resp_api->mensaje)?'':$resp_api->mensaje;
            if(isset($resp_api->url_sunat_guia) && !empty($resp_api->url_sunat_guia)) {
                $documento->ruta_qr = isset($resp_api->url_sunat_guia)?$resp_api->url_sunat_guia:'';
            }

            try {
                if(!$documento->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Envío Correcto';
                    $resp['error_code'] = 'envio_sunat';
                    $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envío Correcto';
                $resp['error_code'] = 'envio_sunat';
                $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar los datos de respuesta en el servidor local.<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
                return $resp;
            }

			//Guardamos el archivo zip del xml: colocar el @ delante, captura también los warnings
			$saved_file_xml_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
			if (($saved_file_xml_zip === false) || ($saved_file_xml_zip == -1)) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar correctamente el archivo en el servidor local.';
				return $resp;
            }
            $documento->ruta_xml = $data['secret_data']['ruta_dir_xml'];
			$documento->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
			$documento->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;

            try {
                if(!$documento->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Envío Correcto';
                    $resp['error_code'] = 'envio_sunat';
                    $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envío Correcto';
                $resp['error_code'] = 'envio_sunat';
                $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
                return $resp;
            }
			
			//Guardamos el archivo zip del cdr
			$saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));
			if (($saved_file_cdr_zip === false) || ($saved_file_cdr_zip == -1)) {
				$resp['respuesta'] = 'ok';
				$resp['titulo'] = 'Envío Correcto';
				$resp['error_code'] = 'envio_sunat';
				$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar correctamente el CDR en el servidor local.';
				return $resp;
			}
			$documento->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
			$documento->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;

            try {
                if(!$documento->save()) {
                    $msg = '';
                    foreach ($documento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Envío Correcto';
                    $resp['error_code'] = 'envio_sunat';
                    $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta del CDR en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT.';
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envío Correcto';
                $resp['error_code'] = 'envio_sunat';
                $resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT. <br /> Sin embargo no se logró guardar la ruta del CDR en la base de datos<br /><br />RECOMENDACIÓN: Consultar el cdr en SUNAT. '.$e->getMessage();
                return $resp;
            }
			
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Proceso Terminado';
			$resp['mensaje'] = 'El Documento N°: '.$serie_comprobante.'-'.$numero_comprobante.' fué enviado correctamente a SUNAT.<br /><br />Código SUNAT: '.$resp_api->cod_sunat.'<br />Mensaje SUNAT: '.$resp_api->msj_sunat.'<br />Digest Value: '.$resp_api->hash_cpe;
			return $resp;

		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Código Error: '.$resp_api->cod_sunat;
			$resp['mensaje'] = isset($resp_api->msj_sunat)?$resp_api->msj_sunat:'';
			return $resp;
		}

		$resp['respuesta'] = 'error';
		$resp['titulo'] = 'Error';
		$resp['mensaje'] = $resp_api;
		return $resp;
    }

    public function guardar_guiaremision_en_bd($id_contribuyente, $idvendedor, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $cabecera_inicial['idsucursal'], 'id_contribuyente' => $id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La Sucursal no es válida!';
			return $resp;
        }
        
        $serie_comprobante = $cabecera_inicial['serie_comprobante'];
        $numero_comprobante = $cabecera_inicial['numero_comprobante'];

        $this->db->begin();

        $new_documento = new DocElectronico();
        $herramientas = new HerramientasController;

        //DATOS PRINCIPALES COMO PRIMARY KEY PARA UNA FACTURA
        $new_documento->id_contribuyente = $id_contribuyente;
        $new_documento->id_tipodoc_electronico = $cabecera_inicial['id_tipodoc_electronico'];
        $new_documento->serie_comprobante = $serie_comprobante;
        $new_documento->numero_comprobante = $numero_comprobante;
        $new_documento->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

        //FECHA
        $new_documento->fecha_comprobante = $cabecera_inicial['fecha_documento'];
        
        //la primera vez siempre se guardará con un estado pendiente
		$new_documento->idcliente = $cliente->idcliente;
		$new_documento->id_vendedor = $idvendedor;
		$new_documento->id_sucursal = $sucursal->idsucursal;
		$new_documento->estado_envio_sunat = "pendiente";
        $new_documento->fecha_registro = date('Y-m-d H:i:s');

        //Datos de Traslado
        $new_documento->id_motivotraslado = $cabecera_inicial['id_motivotraslado'];
        $new_documento->motivo_traslado = $cabecera_inicial['motivo_traslado'];
        $new_documento->peso = floatval($datapost['pesobruto']);
        $new_documento->numero_paquetes = intval($datapost['numero_bultos']);
        $new_documento->id_codigopuerto = (empty($datapost['codigo_puerto']) || $datapost['codigo_puerto'] == '-')?null:$datapost['codigo_puerto'];
        $new_documento->numero_contenedor = empty($datapost['numero_contenedor'])?null:preg_replace('/\s+/', '', $datapost['numero_contenedor']);
        $new_documento->id_modalidadtraslado = $cabecera_inicial['id_modalidadtraslado'];
        $new_documento->modalidad_traslado = $cabecera_inicial['modalidad_traslado'];
        $new_documento->fecha_traslado = $cabecera_inicial['fecha_traslado'];

        
        //Transportista
        $new_documento->id_tipo_documento_transporte = $cabecera_inicial['id_tipo_documento_transporte'];
        $new_documento->nro_documento_transporte = $cabecera_inicial['nro_documento_transporte'];
        $new_documento->razon_social_transporte = $cabecera_inicial['razon_social_transporte'];
        $new_documento->transporte_nro_placa = $cabecera_inicial['transporte_nro_placa'];
        $new_documento->licencia_conductor = $cabecera_inicial['licencia_conductor'];

        /*
        $cabecera_inicial['id_tipo_documento_transporte'] = $id_tipo_documento_transporte;
        $cabecera_inicial['nro_documento_transporte'] = $nro_documento_transporte;
        $cabecera_inicial['razon_social_transporte'] = $razon_social_transporte;
        $cabecera_inicial['transporte_nro_placa'] = $transporte_nro_placa;
        */

        /*
        $new_documento->idconductor = $cabecera_inicial['conductor']->idconductor;
        $new_documento->id_tipodoc_conductor = $cabecera_inicial['conductor']->id_tipodocidentidad;
        $new_documento->num_doc_conductor = $cabecera_inicial['conductor']->num_doc;
        $new_documento->nombre_completo_conductor = $cabecera_inicial['conductor']->nombre_completo;
        */

        //punto de llegada
        $new_documento->id_ubigeo_destino = $datapost['ubigeo_llegada'];
        $new_documento->dir_destino = $datapost['direccionllegada'];

        //punto de partida
        $new_documento->id_ubigeo_partida = $datapost['ubigeo_partida'];
        $new_documento->dir_partida = $datapost['direccionpartida'];

        //documentos de referencia
        $new_documento->array_docs_referencia = json_encode($cabecera_inicial['array_docs_referencia']);

        //notas
        $new_documento->nota = !isset($datapost['informacion_adicional_sunat'])?'':$datapost['informacion_adicional_sunat'];

        try {
            if(!$new_documento->save()) {
                $this->db->rollback();
                $msg = '';
                foreach ($new_documento->getMessages() as $message) {
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
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }

        $num_decimales = 2;
        if($contribuyente->num_decimales > 2) {
            $num_decimales = $contribuyente->num_decimales;
        }
        
        $n = 0;
		foreach($detalle as $item) {
			$n++;
			$new_detalle = new DetalleDoc();
			$new_detalle->id_contribuyente = $new_documento->id_contribuyente;
			$new_detalle->id_tipodoc_electronico = $new_documento->id_tipodoc_electronico;
			$new_detalle->serie_comprobante = $new_documento->serie_comprobante;
			$new_detalle->numero_comprobante = $new_documento->numero_comprobante;
			$new_detalle->tipo_envio_sunat = $new_documento->tipo_envio_sunat;
			$new_detalle->item = $n;
			$new_detalle->id_unidad_medida = $item["UNIDAD_MEDIDA_ID_DET"];
			$new_detalle->unidad_medida = $item["UNIDAD_MEDIDA_DET"];
			$new_detalle->cantidad = $item["CANTIDAD_DET"];
			$new_detalle->id_producto = $item["IDPRODUCTO_DET"];
			$new_detalle->codigo_producto = $item["CODIGO_PRODUCTO"];
            $new_detalle->descripcion = $item["DESCRIPCION_DET"];
            $new_detalle->peso = $item["PESO_DET"];

            $new_detalle->tipo_unidad = !isset($item["TIPO_UNIDAD"])?'UND':$item["TIPO_UNIDAD"];
    		$new_detalle->id_presentacion = !isset($item["ID_PRESENTACION"])?null:intval($item["ID_PRESENTACION"]);

            try {
                if(!$new_detalle->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($new_detalle->getMessages() as $message) {
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
                $resp['mensaje'] = $e->getMessage();
                return $resp;
            }

            //aquí descontamos el stock para cada uno de los productos en el detalle!
			$cantidad_kardex = $item["CANTIDAD_DET"];
			if(isset($item["ID_PRESENTACION"]) && intval($item["ID_PRESENTACION"]) > 0) {
				$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item["ID_PRESENTACION"]))));
				if($presentacion) {
					$cantidad_kardex = round($item["CANTIDAD_DET"]*$presentacion->cantidad_und_base, $num_decimales);
				}
			}

			$modificar_stock = 'no';
			$modificar_stock = $this->get_sucursal_opcion($new_documento->id_contribuyente, $new_documento->id_sucursal, 'guiaremision_modifica_stock');
			$modificar_stock = ($modificar_stock == '')?'no':$modificar_stock;
			$modificar_stock = ($modificar_stock == 'si')?'si':'no';

			$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item["IDPRODUCTO_DET"])));
			if($producto && $modificar_stock == 'si') {
				$unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
				if($unidad_medida) {
					if($unidad_medida->codigo != 'ZZ') {

						$data_kardex['id_contribuyente'] = $new_documento->id_contribuyente;
						$data_kardex['idsucursal'] = $new_documento->id_sucursal;
						$data_kardex['idproducto'] = $producto->idproducto;
						$data_kardex['idusuario'] = $idvendedor;
						$data_kardex['tipo_envio_sunat'] = $new_documento->tipo_envio_sunat; //prueba, produccion
						$data_kardex['tipo_kardex'] = 'venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion	
						$data_kardex['estado_kardex'] = 'activo'; //activo, anulado
						
						$data_kardex['cantidad_salida'] = $cantidad_kardex;

						$data_kardex['detalle'] = 'Venta';
						$data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
						$data_kardex['docref_id_contribuyente'] = $new_documento->id_contribuyente;
						$data_kardex['docref_id_tipodoc_electronico'] = $new_documento->id_tipodoc_electronico;
						$data_kardex['docref_serie_comprobante'] = $new_documento->serie_comprobante;
						$data_kardex['docref_numero_comprobante'] = $new_documento->numero_comprobante;
						$data_kardex['docref_tipo_envio_sunat'] = $new_documento->tipo_envio_sunat;

						$kardex = new KardexController;
						$resp_kardex = $kardex->registrar_en_kardex($data_kardex);
						if($resp_kardex['respuesta'] == 'error') {
							$this->db->rollback();
							return $resp_kardex;
						}

						$producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
						$nuevo_stock = $producto->stock - $cantidad_kardex;
						//if($nuevo_stock < 0) { $nuevo_stock = 0; }
						$producto->stock = $nuevo_stock;

                        try {
                            if(!$producto->save()) {
                                $this->db->rollback();
                                $msg = '';
                                foreach ($producto->getMessages() as $message) {
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
                            $resp['mensaje'] = $e->getMessage();
                            return $resp;
                        }
					}
				}
			}
			//-------------- fin descuento stock --------------
        }
        
        $this->db->commit();
        $resp['respuesta'] = 'ok';
		$resp['id_contribuyente'] = $new_documento->id_contribuyente;
		$resp['id_tipodoc_electronico'] = $new_documento->id_tipodoc_electronico;
		$resp['serie_comprobante'] = $new_documento->serie_comprobante;
		$resp['numero_comprobante'] = $new_documento->numero_comprobante;
		$resp['tipo_envio_sunat'] = $new_documento->tipo_envio_sunat;
		return $resp;
    }

    public function iniciarImportacionItemsAction() {
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
            
            $resp = $this->iniciar_importacion_items($usuario, $datapost);
            echo json_encode($resp);
            exit();
        }       
    }
    
    public function iniciar_importacion_items($usuario, $datapost) {
        $herramientas = new HerramientasController;
        $api = new ApiController;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra el contribuyente al que pertenece el usuario!';
            return $resp;
        }
        
        $idsucursal = isset($datapost['idsucursal'])?intval($datapost['idsucursal']):0;
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
        if(!$sucursal) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La Sucursal no Existe!';
            return $resp;
        }
        
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
        
        $nombre_temporal = 'temp'.uniqid().$_FILES['archivo']['name'];
        $rutaArchivo = $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/temporales/".$nombre_temporal;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo);
        
        $documento = IOFactory::load($rutaArchivo);
        $totalDeHojas = $documento->getSheetCount();

        $hojaActual = $documento->getSheet(0); //extraemos datos de la primera hoja solamente
        $numeroMayorDeFila = $hojaActual->getHighestRow();
        
        if($contribuyente->tipo_envio_sunat == 'prueba') {
            if($numeroMayorDeFila >= 15) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Esta cuenta se encuentra en el ambiente de pruebas, por tanto tiene un límite máximo de 5 items a importar.';
                return $resp;
            }
        } else {
            if($numeroMayorDeFila >= 200) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Únicamente se permiten importaciones con un máximo de 200 items.';
                return $resp;
            }
        }
        
        $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
        if($confirmacion != 'si') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Necesitamos tu Confirmación!';
            $resp['mensaje'] = '<strong>¿Realmente deseas importar Todos los Item del Excel?!</strong>';
            return $resp;
        }
        
        $array_detalle = array();
        for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {

            $id_producto     = trim($hojaActual->getCell("A".$indiceFila)->getValue() ?? '');
            $codigo_producto = trim($hojaActual->getCell("B".$indiceFila)->getValue());
            $nombre_producto = trim($hojaActual->getCell("C".$indiceFila)->getValue());
            $idunidad_medida = intval($hojaActual->getCell("D".$indiceFila)->getValue());
            $cantidad        = floatval($hojaActual->getCell("E".$indiceFila)->getValue());
            $peso            = floatval($hojaActual->getCell("F".$indiceFila)->getValue());
            $bultos          = floatval($hojaActual->getCell("G".$indiceFila)->getValue());
            $peso            = ($peso<=0)?0:$peso;
            $bultos          = ($bultos<=0)?0:$bultos;
            $tipo_unidad     = trim($hojaActual->getCell("H".$indiceFila)->getValue() ?? '');
            $tipo_unidad     = empty($tipo_unidad)?'UND':$tipo_unidad;
            $tipo_unidad     = ($tipo_unidad != 'PRE')?'UND':'PRE';
            $tipo_unidad_texto = ($tipo_unidad == 'UND')?'unidad':"presentacion";
            
            if(!empty($id_producto) && $id_producto > 0) {
                $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and estado = 'activo'", 'bind' => array('idproducto' => $id_producto, 'id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $idsucursal)));
                if(!$producto) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Producto';
                    $resp['mensaje'] = 'El producto: '.$nombre_producto.', con ID: '.$id_producto.' no existe en la sucursal seleccionada';
                    return $resp;
                }
            } else {
                $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and codigo = :codigo: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $idsucursal, 'codigo' => $codigo_producto)));
                if(!$producto) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Producto';
                    $resp['mensaje'] = 'El producto: '.$nombre_producto.', con código '.$codigo_producto.', no existe en la sucursal seleccionada';
                    return $resp;
                }
            }
            
            if(empty($nombre_producto)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Producto';
                $resp['mensaje'] = 'El producto en la fila número: '.$indiceFila.', tiene un nombre vacío, recuerda que todo producto debería tener un nombre';
                return $resp;
            }

            if(empty($cantidad) || $cantidad <= 0) {
                $resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La cantidad debe ser mayor a cero en la fila número: '.$indiceFila;
				return $resp;
            }
            
            if($tipo_unidad == 'UND') {
                if(!empty($idunidad_medida)) {
                    $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad_medida)));
                    if(!$unidadmedida) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'No existe la unidad de medida en la fila número: '.$indiceFila;
                        return $resp;
                    }
                } else {
                    $idunidad_medida = 7;
                    $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad_medida)));
                    if(!$unidadmedida) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'No existe la unidad de medida en la fila número: '.$indiceFila;
                        return $resp;
                    }
                }

                $id_unidad_medida = $unidadmedida->idunidad;
                $val_unidad_medida = 'UND-'.$unidadmedida->idunidad;
                $unidad_medida_nombre = $unidadmedida->nombre;
                if(empty($peso)) {
                    $peso = round($producto->peso, 2) + 0;
                }
            } else {
                $productopresentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion: and idproducto = :idproducto:", 'bind' => array('id_presentacion' => $idunidad_medida, 'idproducto' => $producto->idproducto)));
                if(!$productopresentacion) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No existe la presentación en la fila número: '.$indiceFila;
                    return $resp;
                }

                $id_unidad_medida = $productopresentacion->id_presentacion;
                $val_unidad_medida = 'PRE-'.$productopresentacion->id_presentacion;
                $unidad_medida_nombre = $productopresentacion->nombre;
                if(empty($peso)) {
                    $peso = round($producto->peso*$productopresentacion->cantidad_und_base, 2) + 0;
                }
            }

            $prod_unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));

            $array_lista_presentaciones = array();
			$array_lista_presentaciones[] = array(
				"val"				=> 'UND-'.$prod_unidadmedida->idunidad,
				"text"				=> $prod_unidadmedida->nombre,

				"tipo"				=> 'unidad',
				"idpresentacion"	=> "",
				"codigo"			=> $producto->codigo,
				"nombrepresentacion"	=> "",
				"idproducto"		=> $producto->idproducto,
				"idunidadpresentacion"	=> "",
				"idunidadbase"		=> $prod_unidadmedida->idunidad,
				"nombreunidadpresentacion"	=> '',
				"cantidad"			=> "",
				"nombreunidadbase"	=> $prod_unidadmedida->nombre,
				"precioconigv"		=> $producto->valor_con_igv,
				"preciosinigv"		=> $producto->valor_sin_igv,
                "idcodmoneda"           => $producto->id_cod_moneda
			);

            $presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $producto->idproducto)));
            foreach($presentaciones as $presentacion) {

                $unidad_presentacion = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->idunidad)));
                $unidad_base = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->idunidad_base)));

                $array_lista_presentaciones[] = array(
                    "val"				    => 'PRE-'.$presentacion->id_presentacion,
                    "text"				    => $presentacion->nombre,
                    "tipo"				    => 'presentacion',
                    "idpresentacion"	    => $presentacion->id_presentacion,
                    "codigo"			    => $presentacion->codigo,
                    "nombrepresentacion"	=> $presentacion->nombre,
                    "idproducto"		    => $producto->idproducto,
                    "idunidadpresentacion"	=> $unidad_presentacion->idunidad,
                    "idunidadbase"		    => $unidad_base->idunidad,
                    "nombreunidadpresentacion"	=> $unidad_presentacion->nombre,
                    "cantidad"			    => $presentacion->cantidad_und_base,
                    "nombreunidadbase"	    => $unidad_base->nombre,
                    "precioconigv"		    => $producto->valor_con_igv,
                    "preciosinigv"		    => $producto->valor_sin_igv,
                    "idcodmoneda"           => $producto->id_cod_moneda
                );
            }
            
            $tipo_afectacion_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
            
            $array_detalle[] = array(
				"row_identificadador"   => $herramientas->create_guid(),
				"idarticulo" 			=> $producto->idproducto,
				"id_tipoafectacionigv" 	=> $producto->id_tipoafectacionigv,
				"descripcion"			=> $nombre_producto,
				"text_tipo_afecigv" 	=> $tipo_afectacion_igv->descripcion,
				"idunidadmedida" 		=> $val_unidad_medida,
				"unidadmedida" 			=> $unidad_medida_nombre,
				"precio" 				=> 0,
				"id_cod_moneda" 		=> $producto->id_cod_moneda,
				"cantidad" 				=> (float)$cantidad + 0,
				"subtotal" 				=> 0,
				"igv" 					=> 0,
				"importe" 				=> 0,
				"codigo"				=> empty($codigo_producto)?$producto->codigo:$codigo_producto,
				"estado" 				=> 'V',

				'subtotal_icbper'		=> 0,
				'afecto_icbper'			=> 'no',

				"select_unidadmedida"	=> json_encode($array_lista_presentaciones),
				"tipo_unidad"			=> $tipo_unidad,
				"idpresentacion"		=> '',

				"p_unit_sin_igv"		=> 0,
				"factor_igv_sunat"		=> 18,

				"html_lista_precios"			=> '',
				"item_detraccion_codigo" 		=> '',
				"item_detraccion_porcentaje" 	=> '',

				"stock_actual"					=> 999,
				"peso_unitario"			        => $peso + 0,
				"peso"					        => (float) round(floatval($peso)*floatval($cantidad), 2)
			);
        }
        
        $resp['respuesta'] = 'ok';
        $resp['detalle'] = $array_detalle;
        $resp['mensaje'] = 'Se importaron todos los items';
        return $resp;
    }

    public function crear_producto_en_bd($id_contribuyente, $id_sucursal, $data) {
        
        if(isset($data['codigo_producto'])) {
            $product = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and codigo = :codigo:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idsucursal' => $id_sucursal, 'codigo' => $data['codigo_producto'])));
            if($product) {
                $resp['respuesta'] = 'ok';
        		$resp['id_producto'] = $product->idproducto;
        		return $resp;
            }
        }
       
        $codigo_producto = isset($data['codigo_producto'])?$data['codigo_producto']:uniqid();
        $codigo_producto = empty($codigo_producto)?uniqid():$codigo_producto;
        
        $product = new Producto();
		$product->fecha_registro        = date('Y-m-d H:i:s');
		$product->id_contribuyente      = $id_contribuyente;
		$product->idsucursal            = $id_sucursal;

		$product->stock                 = 0;
		$product->precio_compra         = 1;

		$product->valor_sin_igv         = 10; //$valor_sin_igv
		$product->valor_con_igv         = 11.8;
		
		$product->fecha_vencimiento     = null;
		$product->marca                 = null;
		$product->codigo                = $codigo_producto;
		$product->id_unidad_medida      = isset($data['idunidad_medida'])?$data['idunidad_medida']:7;
		$product->id_cod_detraccion     = null;
		$product->id_tipoafectacionigv  = 10;
		$product->id_categoria          = null;
		$product->nombre                = isset($data['nombre_producto'])?$data['nombre_producto']:'PRODUCTO';
		$product->id_cod_moneda         = 'PEN';
		$product->foto                  = null;
		$product->nota                  = null;
		$product->stock_minimo          = 1;
		$product->tipo_cambio_sunat     = 3.5; 
		$product->precio_venta_minimo   = 5;
		$product->multi_precio          = 'no';
		$product->afecto_icbper         = 'no';
		$product->estado                = 'activo';

        try {
            if(!$product->save()) {
                $resp['respuesta'] = 'error';
                return $resp;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            return $resp;
        }

		$resp['respuesta'] = 'ok';
		$resp['id_producto'] = $product->idproducto;
		return $resp;
    }
}