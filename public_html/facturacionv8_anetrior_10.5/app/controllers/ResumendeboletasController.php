<?php
class ResumendeboletasController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Resúmenes de Boletas');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
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
			->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
			->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switch.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand())
            ->addJs($this->baseUri . "public/js/resumendeboletas.js?i=v2");
    }

    public function getListaFacturasAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $fecha_registro = !isset($datapost['fecha_registro_boletas'])?'':$datapost['fecha_registro_boletas'];

            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            if($fecha_registro == '') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Selecciona una fecha válida!!';
                echo json_encode($resp);
                exit();
            }

            $array_fecha_documento = explode('/',!isset($fecha_registro)?date('d/m/Y'):$fecha_registro);
            $fecha = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
            if (!(DateTime::createFromFormat('Y-m-d', $fecha) !== FALSE)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes seleccionar una fecha válida!';
                echo json_encode($resp);
                exit();
            }
            
            $lista = $this->get_lista_boletas($usuario->id_contribuyente, $fecha);

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function get_lista_boletas($id_contribuyente, $fecha) {
        //Extraemos solamente los documentos que sean boletas, notas de crédito y débito
        //en este sentido hay que tener en cuenta que las notas de crédito y débito también pueden ser para boletas
        //entonces es por eso que también filtramos solo las que empiecen con B, pues ya sabemos que
        //boletas, notas de crédito de boletas, notas de débito de boletas todas empezarán con B.
        //Aquí hay que tener cuidado en caso algún nuevo documento que sea para boletas empiece con otra leta.
        //aunque otra opción aunque más lenta hubiese sido comparar que tipo de documento modifica, y si el documento que modifica
        //es una boleta entonces lo extraemos, como por ahora no tenemos documentos complicados lo dejaremos la consulta tal cual para agilizar
        //las búsquedas.

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $query = "SELECT * FROM doc_electronico where tipo_envio_sunat = :tipo_envio_sunat and id_contribuyente = :id_contribuyente and id_tipodoc_electronico in ('03', '07', '08') and serie_comprobante regexp '^b|B.' and CAST(fecha_comprobante AS DATE) = CAST(:fecha AS DATE) and (rb_codigo IS NULL or rb_serie IS NULL or rb_secuencia IS NULL)";
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: (get_lista_boletas) ',  $e->getMessage(), "\n";
            exit();
        }


        $lista = array();
        while ($item = $sentencia->fetch()) {
            $item = (object)$item;
            $tipo_doc = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
            $lista[] = array(date("d-m-Y", strtotime($item->fecha_comprobante)), strtoupper($tipo_doc->descripcion), $item->serie_comprobante, $item->numero_comprobante, $item->total);
        }

        return $lista;
    }

    public function crearResumenBoletasAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $fecha_registro = !isset($datapost['fecha_registro_boletas'])?'':$datapost['fecha_registro_boletas'];

            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            if($fecha_registro == '') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Selecciona una fecha válida!!';
                echo json_encode($resp);
                exit();
            }

            $array_fecha_documento = explode('/',!isset($fecha_registro)?date('d/m/Y'):$fecha_registro);
            $fecha = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
            if (!(DateTime::createFromFormat('Y-m-d', $fecha) !== FALSE)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes seleccionar una fecha válida!';
                echo json_encode($resp);
                exit();
            }

            $confirmado = !isset($datapost['confirmado'])?'':$datapost['confirmado'];
            
            $resp_envio_resumen = $this->crear_resumen_agrupado($usuario->id_contribuyente, $fecha, $confirmado, $usuario->idusuario);
            echo json_encode($resp_envio_resumen);
            exit();
        }
    }

    public function crearResumenIndividualAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            if(!isset($datapost['id_contribuyente']) || empty($datapost['id_contribuyente'])) {
                $id_contribuyente = $usuario->id_contribuyente;
            } else {
                $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_contribuyente != $contribuyente->id_contribuyente) {
                if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El Documento no existe o no tiene los permisos correspondientes.';
                    echo json_encode($resp);
                    exit();
                }
            }
            
            $data_doc_bd['id_contribuyente'] = $contribuyente->id_contribuyente;
            $data_doc_bd['id_tipodoc_electronico'] = isset($datapost['tipodoc'])?$datapost['tipodoc']:'';
            $data_doc_bd['serie_comprobante'] = isset($datapost['serie'])?$datapost['serie']:'';
            $data_doc_bd['numero_comprobante'] = isset($datapost['numero'])?$datapost['numero']:'';
            $confirmacion = !isset($datapost['confirmacion'])?'':$datapost['confirmacion'];
            $accion = isset($datapost['accion'])?$datapost['accion']:'';

            $data_doc_bd['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
            $data_doc_bd['idusuario'] = $usuario->idusuario;

            if($accion == 3) {
                $gestion_usuarios = new GestionuserController;
                if($data_doc_bd['id_tipodoc_electronico'] == '03') {
                    if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_boleta')) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Usted no tiene permisos para anular una Boleta!';
                        return $resp;
                    }
                }

                if($data_doc_bd['id_tipodoc_electronico'] == '07') {
                    if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_notas_credito')) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Usted no tiene permisos para anular una Nota de Crédito!';
                        return $resp;
                    }
                }

                if($data_doc_bd['id_tipodoc_electronico'] == '08') {
                    if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_notas_debito')) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Usted no tiene permisos para anular una Nota de Débito!';
                        return $resp;
                    }
                }
            }
            
            if($confirmacion == 'si') {
                if($accion == 3) {
                    $log = new LogsController;
                    $data_doc_bd['tipo_log'] = 'comunicacion_baja';
                    $data_doc_bd['descripcion'] = 'El Usuario '.$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una anulación';
                    $resp_log = $log->log_documento($data_doc_bd);
                }
            }
            
            $resp = $this->crear_resumen_individual($data_doc_bd, $accion, $confirmacion);
            echo json_encode($resp);
            exit();
        }
    }

    public function get_secuencia_resumen($id_contribuyente, $codigo, $serie) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        //Se hizo la modificación al algoritmo para extraer la secuencia porque existen empresas que comparten el mismo RUC
        $lista_contribuyentes = Contribuyente::find(array("ruc = :ruc:", 'bind' => array('ruc' => $contribuyente->ruc)));
        if(count($lista_contribuyentes) <= 1) {
            $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'codigo' => $codigo, 'serie' => $serie, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "secuencia DESC"));
        } else {
            $lista_ids = array();
            foreach($lista_contribuyentes as $item) {
                $lista_ids[] = $item->id_contribuyente;
            }
            
            $sql = "id_contribuyente in (".implode(',', $lista_ids).") and codigo = :codigo: and serie = :serie: and tipo_envio_sunat = :tipo_envio_sunat:";
            $resumen = ResumenBoletas::findFirst(array($sql, 'bind' => array('codigo' => $codigo, 'serie' => $serie, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "secuencia DESC"));
        }

        if(!$resumen) {
            if($contribuyente->correlativo_rd_boletas > 1) {
                return $contribuyente->correlativo_rd_boletas;
            }
            return 1;
        }

        if($contribuyente->correlativo_rd_boletas > $resumen->secuencia + 1) {
            return $contribuyente->correlativo_rd_boletas;
        }

        return $resumen->secuencia + 1;
    }

    public function crear_resumen_agrupado($id_contribuyente, $fecha_documentos, $confirmado = 'si', $idusuario = '') {

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $accion = 1; //ESTE RESUMEN AGRUPADO SIEMPRE, SIEMPRE SE UTILIZARÁ PARA ADICIONAR ES DECIR CON 1
        //TODO RESUMEN QUE SEA PARA ANULAR O MODIFICAR SERÁ ENVIADO DE FORMA INDIVIDUAL
        //Esto ayudará para tener un orden en las consultas, por ejemplo:
        //Si consultamos el resumen_boletas número 01, entonces este resumen tendrá un campo que indice: 1, 2, 3 (1:adicionar)
        //eso indicará que todo el contenido de ese resumen se ha manejado con el número 1, es decir, se adicionó.
        $documentoelectronico = new DocumentoelectronicoController;
        $resp_data_emisor = $documentoelectronico->get_data_emisor($id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
        }
        
        $resp_secret_data = $documentoelectronico->get_secret_data($id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
        }
        
        $codigo = "RC";
        $serie = date('Ymd');

        $secuencia = $this->get_secuencia_resumen($id_contribuyente, $codigo, $serie);

        $cabecera = array(
            //Cabecera del documento
            "codigo"						=> $codigo,
            "serie"							=> $serie,
            "secuencia"             		=> $secuencia,
            "fecha_referencia"             	=> date("Y-m-d", strtotime($fecha_documentos)), //Fecha de emisión de los documentos (yyyy-mm-dd)
            "fecha_documento"          		=> date('Y-m-d') //Fecha de generación del resumen (yyyy-mm-dd)
        );

        $query = "SELECT * FROM doc_electronico where tipo_envio_sunat = :tipo_envio_sunat and id_contribuyente = :id_contribuyente and id_tipodoc_electronico in ('03', '07', '08') and serie_comprobante regexp '^b|B.' and CAST(fecha_comprobante AS DATE) = CAST(:fecha AS DATE) and (rb_codigo IS NULL or rb_serie IS NULL or rb_secuencia IS NULL)";
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':fecha', $fecha_documentos, PDO::PARAM_STR);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: (crear_resumen_agrupado) ',  $e->getMessage(), "\n";
            exit();
        }

        $detalle = array();
        $lista_items_afectados = array();
        $i = 0;
        while ($item = $sentencia->fetch()) {
            $item = (object)$item;
            $lista_items_afectados[] = $item;

            if(empty($item->name_xml)) {
                $factura = new FacturaController;
                $resp_guardado_bd['id_contribuyente'] = $item->id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $item->id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $item->serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $item->numero_comprobante;
                $resp = $factura->enviar_factura_api($resp_guardado_bd, 'solo_firma', $item->id_vendedor);
                if($resp['respuesta'] == 'error') {
                    return $resp;                    
                }
            }

            $tipo_doc = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));

            if($item->id_tipodoc_electronico == '07' || $item->id_tipodoc_electronico == '08') {
                $numero_comprobante_ref = $item->serie_documento_modifica.'-'.$item->nro_documento_modifica;
                $id_tipo_comprobante_modifica = $item->id_tipo_comprobante_modifica;

                if($item->id_tipodoc_electronico == '07') {
                    if($item->id_cod_tipomotivo_credito == '10') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Para Notras de Crédito que modifican boletas, no se debe seleccionar el tipo 10 - Otros Conceptos.';
                        return $resp;
                    }
                } else {
                    if($item->id_cod_tipomotivo_credito == '03') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Para Notras de Débito que modifican boletas, no se debe seleccionar el 03: Penalidad/Otros conceptos';
                        return $resp;
                    }
                }
            } else {
                $numero_comprobante_ref = "";
                $id_tipo_comprobante_modifica = "";
            }

            /*
            - 1: Adicionar: Se usará para nuevos documentos que no han sido informados
            - 2: Modificar: Debe utilizarse en el supuesto de documentos ya informados sobre los que se modificará algún dato. No es modificable el tipo, serie y número del documento informado.
            - 3: Anulado: Aplicable a documentos informados que no ha sido otorgado al adquiriente o usuario, de lo contrario la anulación de la operación deberá sustentarse mediante Nota de Crédito
            */
            $i++;
            $detalle[] = array(
                "ITEM_DET"				=>	$i,
                "TIPO_COMPROBANTE"		=>	$item->id_tipodoc_electronico,
                "NRO_COMPROBANTE"		=>	$item->serie_comprobante."-".$item->numero_comprobante,
                "NRO_DOCUMENTO"			=>	$cliente->num_doc,
                "TIPO_DOCUMENTO"		=>	$cliente->id_tipodocidentidad,
                "NRO_COMPROBANTE_REF"	=>	$numero_comprobante_ref,
                "TIPO_COMPROBANTE_REF"	=>	$id_tipo_comprobante_modifica,
                "STATUS"				=>	$accion, //1: Adicionar, 2: Modificar, 3: Anulado
                "COD_MONEDA"			=>	$item->id_codigomoneda,
                "TOTAL"					=>	$item->total,
                "GRAVADA"				=>	$item->total_gravadas,
                "EXONERADO"				=>	$item->total_exoneradas,
                "ICBPER"                =>  $item->total_icbper,
                "INAFECTO"				=>	$item->total_inafecta,
                "EXPORTACION"			=>	$item->total_exportacion,
                "GRATUITAS"				=>	$item->total_gratuitas,
                "MONTO_CARGO_X_ASIG"	=>	"0",
                "CARGO_X_ASIGNACION"	=>	"0",
                "ISC"					=>	$item->total_isc,
                "IGV"					=>	$item->total_igv,
                "OTROS"					=>	$item->total_otr_imp,
                "FACTOR_IGV"            =>  empty($item->porcentaje_igv)?($this->factor_igv_sunat*100):$item->porcentaje_igv,
            );
        }

        if(count($detalle) <= 0) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentran boletas pendiente de envío en la fecha seleccionada!';
			return $resp;
        }

        $data = $cabecera;
        $data['emisor'] = $resp_data_emisor['emisor'];
        $data['detalle'] = $detalle;
        $data['secret_data'] =  $resp_secret_data['secret_data'];

        $ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'resumenes/';
		if (!file_exists($ruta_base_cpe_cdr)) {
			mkdir($ruta_base_cpe_cdr, 0777, true);
        }
        
        if($confirmado != 'si') {
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'error';
            $resp['mensaje'] = 'Se creará el siguiente resumen: '.$data['codigo'].'-'.$data['serie'].'-'.$data['secuencia'].', conteniendo '.count($lista_items_afectados).' documentos, está seguro de crear el resumen? los cambios no se podrán revertir!';
            return $resp;
        }
        
        //enviamos el resumen hacia la api
        $resp_api = $this->enviar_resumen_api($data, $id_contribuyente, $fecha_documentos, $accion, $lista_items_afectados, $ruta_base_cpe_cdr, $idusuario);
        return $resp_api;
    }

    public function registrar_idresumen_en_documentos($lista_documentos, $codigo, $serie, $secuencia, $estado, $resp_api, $accion, $idusuario = '') {
        foreach($lista_documentos as $item) {
            $id_contribuyente = $item->id_contribuyente;
            $id_tipodoc_electronico = $item->id_tipodoc_electronico;
            $serie_comprobante = $item->serie_comprobante;
            $numero_comprobante = $item->numero_comprobante;
            $tipo_envio_sunat = $item->tipo_envio_sunat;

            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

            $documento->rb_id_contribuyente = $id_contribuyente;
            $documento->rb_codigo = $codigo;
            $documento->rb_serie = $serie;
            $documento->rb_secuencia = $secuencia;
            $documento->rb_tipo_envio_sunat = $tipo_envio_sunat;
            
            if($accion == 3) {
                $documento->estado_envio_sunat = $estado; //'anulado';
            } else if($accion == 2) {
                $documento->estado_envio_sunat = $estado;
            } else {
                $documento->estado_envio_sunat = $estado;
            }

            $documento->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
            $documento->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
            $documento->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
            $documento->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
            $documento->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
            
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
                $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                return $resp;
            }

            if($item->id_tipodoc_electronico == '07' || $item->id_tipodoc_electronico == '08') {
                $nota_credito_controller = new NotadecreditoController;
                $data_doc_electronicoxnota['cpe_id_contribuyente'] 			= $documento->id_contribuyente;
                $data_doc_electronicoxnota['cpe_id_tipodoc_electronico'] 	= $documento->id_tipo_comprobante_modifica;
                $data_doc_electronicoxnota['cpe_serie_comprobante'] 		= $documento->serie_documento_modifica;
                $data_doc_electronicoxnota['cpe_numero_comprobante'] 		= $documento->nro_documento_modifica;
                $data_doc_electronicoxnota['cpe_tipo_envio_sunat'] 			= $documento->tipo_envio_sunat;
                $data_doc_electronicoxnota['fecha_registro'] 				= date('Y-m-d H:i:s');
                $data_doc_electronicoxnota['nota_id_contribuyente']			= $documento->id_contribuyente;
                $data_doc_electronicoxnota['nota_id_tipodoc_electronico']	= $documento->id_tipodoc_electronico;
                $data_doc_electronicoxnota['nota_serie_comprobante'] 		= $documento->serie_comprobante;
                $data_doc_electronicoxnota['nota_numero_comprobante'] 		= $documento->numero_comprobante;
                $data_doc_electronicoxnota['nota_tipo_envio_sunat'] 		= $documento->tipo_envio_sunat;
                $data_doc_electronicoxnota['nota_estado_envio_sunat'] 		= $documento->estado_envio_sunat;
                $data_doc_electronicoxnota['nota_id_cod_tipomotivo_credito'] 	= $documento->id_cod_tipomotivo_credito;
                $data_doc_electronicoxnota['nota_id_cod_tipomotivo_debito'] 	= $documento->id_cod_tipomotivo_debito;
                
                $resp_doc_electronicoxnota = $nota_credito_controller->guardar_registro_doc_electronicoxnota($data_doc_electronicoxnota);
                if($resp_doc_electronicoxnota['respuesta'] == 'error') {
                    //return $resp_doc_electronicoxnota;
                }
            }

            if(empty($idusuario)) {
                $auth = $this->session->get('authv8');
                $idusuario = $auth['idusuario'];
            }

            //para el kardex solo validamos la acción 3, que es cuando ANULAN, ya que la creación del kardex es al guardado del comprobante en los ingresos
            if($accion == 3) {
                $detalle_documento = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipodoc_electronico, 'serie_comprobante' => $documento->serie_comprobante, 'numero_comprobante' => $documento->numero_comprobante, 'tipo_envio_sunat' => $documento->tipo_envio_sunat)));

                foreach($detalle_documento as $item) {
                    $producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto)));
                    if($producto) {

                        $cantidad_kardex = $item->cantidad;
                        if(isset($item->id_presentacion) && intval($item->id_presentacion) > 0) {
                            $presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => intval($item->id_presentacion))));
                            if($presentacion) {
                                $cantidad_kardex = round($item->cantidad*$presentacion->cantidad_und_base, 4);
                            }
                        }

                        $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
                        if($unidad_medida) {
                            if($unidad_medida->codigo != 'ZZ') {
                                if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03' || $documento->id_tipodoc_electronico == '08') {

                                    //Primero debemos verificar que aún no existe un registro en el kardex con esta anulación.
                                    $kardex_anulado = Kardex::findFirst(array("
                                        docref_id_contribuyente         = :docref_id_contribuyente: and 
                                        docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                        docref_serie_comprobante        = :docref_serie_comprobante: and 
                                        docref_numero_comprobante       = :docref_numero_comprobante: and 
                                        docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                        idproducto                      = :idproducto: and 
                                        id_compra                       is null and 
                                        tipo_kardex                     = 'anulacion_venta'
                                    ", 'bind' => array(
                                        'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                        'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                        'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                        'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                        'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                        'idproducto'                    => $producto->idproducto
                                    ), "order" => "id_kardex DESC"));

                                    if(!$kardex_anulado) {
                                        //Una factura, boleta y nota de débito anulada representa un ingreso, ya que el producto vuelve a ingresar a almacén.
                                        //para eso extraemos los datos del último kardex ingresado para extraer el preceio y poder seguir haciendo el cálculo
                                        $kardex_guardado = Kardex::findFirst(array("
                                            docref_id_contribuyente         = :docref_id_contribuyente: and 
                                            docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                            docref_serie_comprobante        = :docref_serie_comprobante: and 
                                            docref_numero_comprobante       = :docref_numero_comprobante: and 
                                            docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                            idproducto                      = :idproducto: and 
                                            id_compra                       is null
                                        ", 'bind' => array(
                                            'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                            'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                            'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                            'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                            'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                            'idproducto'                    => $producto->idproducto
                                        ), "order" => "id_kardex DESC"));

                                        if($kardex_guardado) {
                                            $data_kardex['id_contribuyente'] = $documento->id_contribuyente;
                                            $data_kardex['idsucursal'] = $documento->id_sucursal;
                                            $data_kardex['idproducto'] = $item->id_producto;
                                            $data_kardex['idusuario'] = $idusuario;
                                            $data_kardex['tipo_envio_sunat'] = $documento->tipo_envio_sunat; //prueba, produccion
                                            $data_kardex['tipo_kardex'] = 'anulacion_venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
                                            $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                                            $data_kardex['cantidad_entrada'] = $cantidad_kardex;
                                            $data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario_promedio;;
                        
                                            $data_kardex['detalle'] = 'Anulación de Comprobante de Venta';
                                            $data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
                        
                                            $data_kardex['docref_id_contribuyente'] = $documento->id_contribuyente;
                                            $data_kardex['docref_id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
                                            $data_kardex['docref_serie_comprobante'] = $documento->serie_comprobante;
                                            $data_kardex['docref_numero_comprobante'] = $documento->numero_comprobante;
                                            $data_kardex['docref_tipo_envio_sunat'] = $documento->tipo_envio_sunat;
                                            
                                            $kardex = new KardexController;
                                            $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                                            if($resp_kardex['respuesta'] == 'error') {
                                                return $resp_kardex;
                                            }

                                            $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
                                        }
                        
                                        
                                        $producto->stock = $producto->stock + $cantidad_kardex;
                                        
                                        try {
                                            if(!$producto->save()) {
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
                                            $resp['respuesta'] = 'error';
                                            $resp['titulo'] = 'Error';
                                            $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                                            return $resp;
                                        }
                                    }
                                }
                    
                                if($documento->id_tipodoc_electronico == '07') {
                                    $kardex_anulado = Kardex::findFirst(array("
                                    docref_id_contribuyente         = :docref_id_contribuyente: and 
                                    docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                    docref_serie_comprobante        = :docref_serie_comprobante: and 
                                    docref_numero_comprobante       = :docref_numero_comprobante: and 
                                    docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                    idproducto                      = :idproducto: and 
                                    id_compra                       is null and 
                                    tipo_kardex                     = 'anulacion_venta'
                                ", 'bind' => array(
                                    'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                    'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                    'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                    'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                    'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                    'idproducto'                    => $producto->idproducto
                                ), "order" => "id_kardex DESC"));

                                    //verificamos que no exista un item del kardex con el estado anulado, esto para evitar que se vuelva a registrar un registro cuando la boleta se anula
                                    if(!$kardex_anulado) {
                                        //Una Nota de Crédito Anulada indica que la mercadería sigue siendo válida para una factura o boleta, por lo tanto una nota de crédito anulada representará una salida.
                                        $kardex_guardado = Kardex::findFirst(array("
                                            docref_id_contribuyente         = :docref_id_contribuyente: and 
                                            docref_id_tipodoc_electronico   = :docref_id_tipodoc_electronico: and 
                                            docref_serie_comprobante        = :docref_serie_comprobante: and 
                                            docref_numero_comprobante       = :docref_numero_comprobante: and 
                                            docref_tipo_envio_sunat         = :docref_tipo_envio_sunat: and 
                                            idproducto                      = :idproducto: and 
                                            id_compra                       is null
                                        ", 'bind' => array(
                                            'docref_id_contribuyente'       => $documento->id_contribuyente, 
                                            'docref_id_tipodoc_electronico' =>  $documento->id_tipodoc_electronico, 
                                            'docref_serie_comprobante'      => $documento->serie_comprobante, 
                                            'docref_numero_comprobante'     => $documento->numero_comprobante, 
                                            'docref_tipo_envio_sunat'       => $documento->tipo_envio_sunat, 
                                            'idproducto'                    => $producto->idproducto
                                        ), "order" => "id_kardex DESC"));
                        
                                        if($kardex_guardado) {
                                            $data_kardex['id_contribuyente'] = $documento->id_contribuyente;
                                            $data_kardex['idsucursal'] = $documento->id_sucursal;
                                            $data_kardex['idproducto'] = $item->id_producto;
                                            $data_kardex['idusuario'] = $idusuario;
                                            $data_kardex['tipo_envio_sunat'] = $documento->tipo_envio_sunat; //prueba, produccion
                                            $data_kardex['tipo_kardex'] = 'venta'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion (cuando se devuelve la mercadería y sale del almacen), anulacion_venta	
                                            $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                                            $data_kardex['cantidad_salida'] = $cantidad_kardex;
                                            $data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario;
        
                                            $data_kardex['detalle'] = 'Anulación de Nota de Crédito de Venta';
                                            $data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
                            
                                            $data_kardex['docref_id_contribuyente'] = $documento->id_contribuyente;
                                            $data_kardex['docref_id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
                                            $data_kardex['docref_serie_comprobante'] = $documento->serie_comprobante;
                                            $data_kardex['docref_numero_comprobante'] = $documento->numero_comprobante;
                                            $data_kardex['docref_tipo_envio_sunat'] = $documento->tipo_envio_sunat;
                                            
                                            $kardex = new KardexController;
                                            $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                                            if($resp_kardex['respuesta'] == 'error') {
                                                return $resp_kardex;
                                            }
                                            
                                            $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
                                            $producto->stock = $producto->stock + $cantidad_kardex;

                                            try {
                                                if(!$producto->save()) {
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
                                                $resp['respuesta'] = 'error';
                                                $resp['titulo'] = 'Error';
                                                $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                                                return $resp;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function getListaResumenesAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }
 
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

            $resumenes = ResumenBoletas::find(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), 'limit' => 100));
            
            $lista = array();
            foreach($resumenes as $item) {

                //botón para ver y descargar el xml_firmado del documento electrónico
                if($item->tipo_envio_sunat == 'produccion') {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
                } else {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
                }
                $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'resumenes/';
                
                $boton_ver_items = '
                <button onclick="ver_lista_items_resumen('.$item->id_contribuyente.', \''.$item->codigo.'\', \''.$item->serie.'\', '.$item->secuencia.')" type="button" class="btn btn-primary"><i class="icon-eye position-left"></i> Ver Boletas</button>
                ';

                $btn_pdf = '<a title="Haz Click para Descargar el PDF" target="_blank" href="/facturacionv8/download/downloadpdf/resumen/'.$item->id_contribuyente.'/'.$item->codigo.'/'.$item->serie.'/'.$item->secuencia.'"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 30px;"></a>';

                $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/resumen/'.$item->id_contribuyente.'/'.$item->codigo.'/'.$item->serie.'/'.$item->secuencia.'/xml_cpe_zip"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="max-width: 30px;"></a>';

                if (file_exists($ruta_base_zip_cpe_cdr.$item->name_cdr_zip)) {
                    $btn_cdr = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/resumen/'.$item->id_contribuyente.'/'.$item->codigo.'/'.$item->serie.'/'.$item->secuencia.'/xml_cdr_zip"><img src="/facturacionv8/img/svg/xml_cdr.svg" style="max-width: 30px;"></a>';
                } else {
                    $btn_cdr = '<a title="Haz Click para Enviar a SUNAT" onclick="descargar_cdr_resumen(\''.$item->numero_ticket.', '.$item->id_contribuyente.'\')" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="max-width: 30px;"></a>';
                }

                $btn_sunat = '
                <div class="btn-group">
                    <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i style="font-size: 21px;" class="icon-checkmark font-weight-bold text-success position-left"></i> &nbsp;<span class="caret text-success"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                        <li><a href="#">Resumen Diario: <span class="label label-primary pull-right">'.$item->codigo.' - '.$item->serie.'-'.$item->secuencia.'</span></a></li>
                        <li><a href="#"><i class="icon-checkmark font-weight-bold text-success"></i> Enviado a Sunat</li>
                        <li><a href="#">Ticket: <span class="label label-success pull-right">'.$item->numero_ticket.'</span></a></li>
                        <li><a href="#">CÓDIGO: <span class="label label-success pull-right">'.$item->cod_sunat.'</span></a></li>
                        <li><a href="#">FECHA DOCUMENTOS: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_documentos)).'</span></a></li>
                        <li><a href="#">FECHA REGISTRO: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_envio_sunat)).'</span></a></li>
                        <li><a href="#">Observación: '.$item->msje_sunat.'</a></li>
                    </ul>
                </div>';

                $lista[] = array(
                    date("d-m-Y", strtotime($item->fecha_envio_sunat)), 
                    date("d-m-Y", strtotime($item->fecha_documentos)),
                    $boton_ver_items,
                    $item->numero_ticket,
                    $btn_pdf,
                    $btn_xml,
                    $btn_cdr,
                    $btn_sunat
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsResumenCreadoAction() {
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
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;

            $codigo = $datapost['codigo'];
            $serie = $datapost['serie'];
            $secuencia = $datapost['secuencia'];

            $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $codigo, 'serie' => $serie, 'secuencia' => $secuencia, 'tipo_envio_sunat' => $tipo_envio_sunat)));

            if(!$resumen) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el resumen seleccionado!';
                echo json_encode($resp);
                exit();
            }

            $documentos = DocElectronico::find(array("rb_id_contribuyente = :rb_id_contribuyente: and rb_codigo = :rb_codigo: and rb_serie = :rb_serie: and rb_secuencia = :rb_secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('rb_id_contribuyente' => $usuario->id_contribuyente, 'rb_codigo' => $codigo, 'rb_serie' => $serie, 'rb_secuencia' => $secuencia, 'tipo_envio_sunat' => $tipo_envio_sunat)));

            $lista = array();
            foreach($documentos as $item) {
                $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
                $tipo_doc = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
                if($resumen->accion_sunat == 1) {
                    $accion_sunat = 'Adicionar';
                } else if ($resumen->accion_sunat == 2) {
                    $accion_sunat = 'Modificar';
                } else if ($resumen->accion_sunat == 3) {
                    $accion_sunat = 'Anular';
                } else {
                    $accion_sunat = 'Desconocido';
                }
                $lista[] = array(
                    date("d-m-Y", strtotime($item->fecha_comprobante)),
                    $tipo_doc->descripcion, 
                    $item->serie_comprobante.'-'.$item->numero_comprobante, 
                    $cliente->num_doc.' '.$cliente->razon_social, 
                    $item->total, 
                    $accion_sunat);
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function crear_resumen_individual($data_doc_bd, $accion, $confirmacion) {
        $id_contribuyente = $data_doc_bd['id_contribuyente'];
		$tipo_doc_electronico = $data_doc_bd['id_tipodoc_electronico'];
		$serie_comprobante = $data_doc_bd['serie_comprobante'];
        $numero_comprobante = $data_doc_bd['numero_comprobante'];
        $idusuario = $data_doc_bd['idusuario'];

        $accion = intval($accion) + 0;
        
        $acciones_validas = array(1, 2, 3);
        if(!in_array($accion, $acciones_validas)) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La acción no está permitida!';
			return $resp;
        }

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
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el documento!';
			return $resp;
        }
        
        $resp_validacion = $this->validar_documento_para_envio_sunat($documento, $accion);
        if($resp_validacion['respuesta'] == 'error') {
            return $resp_validacion;
        }

        $tipo_doc = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));

        $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));

        if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08') {
            $numero_comprobante_ref = $documento->serie_documento_modifica.'-'.$documento->nro_documento_modifica;
            $id_tipo_comprobante_modifica = $documento->id_tipo_comprobante_modifica;

            if($documento->id_tipodoc_electronico == '07') {
                if($documento->id_cod_tipomotivo_credito == '10') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Para Notas de Crédito que modifican boletas, no se debe seleccionar el tipo 10 - Otros Conceptos.';
                    return $resp;
                }
            } else {
                if($documento->id_cod_tipomotivo_credito == '03') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Para Notas de Débito que modifican boletas, no se debe seleccionar el 03: Penalidad/Otros conceptos';
                    return $resp;
                }
            }
        } else {
            $numero_comprobante_ref = "";
            $id_tipo_comprobante_modifica = "";
        }
        
        $detalle[] = array(
            "ITEM_DET"				=>	1,
            "TIPO_COMPROBANTE"		=>	$documento->id_tipodoc_electronico,
            "NRO_COMPROBANTE"		=>	$documento->serie_comprobante."-".$documento->numero_comprobante,
            "NRO_DOCUMENTO"			=>	$cliente->num_doc,
            "TIPO_DOCUMENTO"		=>	$cliente->id_tipodocidentidad,
            "NRO_COMPROBANTE_REF"	=>	$numero_comprobante_ref,
            "TIPO_COMPROBANTE_REF"	=>	$id_tipo_comprobante_modifica,
            "STATUS"				=>	$accion, //1: Adicionar, 2: Modificar, 3: Anulado
            "COD_MONEDA"			=>	$documento->id_codigomoneda,
            "TOTAL"					=>	$documento->total,
            "GRAVADA"				=>	$documento->total_gravadas,
            "EXONERADO"				=>	$documento->total_exoneradas,
            "INAFECTO"				=>	$documento->total_inafecta,
            "EXPORTACION"			=>	$documento->total_exportacion,
            "ICBPER"                =>  $documento->total_icbper,
            "GRATUITAS"				=>	$documento->total_gratuitas,
            "MONTO_CARGO_X_ASIG"	=>	"0",
            "CARGO_X_ASIGNACION"	=>	"0",
            "ISC"					=>	$documento->total_isc,
            "IGV"					=>	$documento->total_igv,
            "OTROS"					=>	$documento->total_otr_imp,
            "FACTOR_IGV"            =>  $documento->porcentaje_igv
        );
        $lista_items_afectados[] = $documento;
        $documentoelectronico = new DocumentoelectronicoController;
        $resp_data_emisor = $documentoelectronico->get_data_emisor($id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
        }
        
        $resp_secret_data = $documentoelectronico->get_secret_data($id_contribuyente);
		if($resp_secret_data['respuesta'] == 'error') {
			return $resp_secret_data;
        }
        
        $codigo = "RC";
        $serie = date('Ymd');

        $secuencia = $this->get_secuencia_resumen($id_contribuyente, $codigo, $serie);

        $cabecera = array(
            //Cabecera del documento
            "codigo"						=> $codigo,
            "serie"							=> $serie,
            "secuencia"             		=> $secuencia,
            "fecha_referencia"             	=> date("Y-m-d", strtotime($documento->fecha_comprobante)), //Fecha de emisión de los documentos (yyyy-mm-dd)
            "fecha_documento"          		=> date('Y-m-d') //Fecha de generación del resumen (yyyy-mm-dd)
        );

        $data = $cabecera;
        $data['emisor'] = $resp_data_emisor['emisor'];
        $data['detalle'] = $detalle;
        $data['secret_data'] =  $resp_secret_data['secret_data'];

        $ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'resumenes/';
		if (!file_exists($ruta_base_cpe_cdr)) {
			mkdir($ruta_base_cpe_cdr, 0777, true);
        }

        if($confirmacion != 'si') {

            if($accion == 3) {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = '¿Realmente Deseas Anular el Documento?';
                $resp['mensaje'] = '<strong class="text-danger">Se anulará el documento: '.$detalle[0]['NRO_COMPROBANTE'].'</strong>, y se creará el siguiente resumen individual: '.$data['codigo'].'-'.$data['serie'].'-'.$data['secuencia'].', estás seguro de anular el documento? los cambios no se podrán revertir!';
                return $resp;
            }
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Necesitamos de tu Confirmación!';
            $resp['mensaje'] = 'Se creará el siguiente resumen: '.$data['codigo'].'-'.$data['serie'].'-'.$data['secuencia'].', conteniendo '.count($lista_items_afectados).' documento, está seguro de crear el resumen? los cambios no se podrán revertir!';
            return $resp;
        }
        
        //enviamos el resumen hacia la api
        $time_inicio = microtime(true);
        $resp_api = $this->enviar_resumen_api($data, $id_contribuyente, $documento->fecha_comprobante, $accion, $lista_items_afectados, $ruta_base_cpe_cdr, $idusuario);
        $time_fin = microtime(true);
        $resp_api['tiempo_enviar_resumen_api'] = $time_fin - $time_inicio;
        return $resp_api;   
    }


    public function enviar_resumen_api($data, $id_contribuyente, $fecha_documentos, $accion, $lista_items_afectados, $ruta_base_cpe_cdr, $idusuario = '') {
        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/resumenboletas';

        $time_inicio = microtime(true);
        $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
        $time_fin = microtime(true);
        $resp['tiempo_envio_api_sunat_facturalahoy'] = $time_fin - $time_inicio;

        $resp_api = json_decode($resp_api_ws);
        $resp['response'] = $resp_api;
		if(empty($resp_api)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['error_code'] = 'envio_sunat';
			$resp['mensaje'] = 'El Documento N°: '.$data['serie'].'-'.$data['secuencia'].', se guardó correctamente. <br /> Sin embargo no se logró enviar correctamente a la sunat, el error devuelto es el siguiente: '.$resp_api_ws;
			return $resp;
        }
        
		if($resp_api->respuesta == 'error') {
            //Si ingresa aquí debemos verificar si es un error en el envío o un error en la recepción del cdr
            if(isset($resp_api->codigo_error_api) && $resp_api->codigo_error_api == 'error_envio_resumen') {
                $codigo_error_sunat = isset($resp_api->cod_sunat)?$resp_api->cod_sunat:'';
                $httpcode_error_sunat = isset($resp_api->httpcode)?$resp_api->httpcode:'';

                if ($codigo_error_sunat == 'soap-env:Client.0402') {
                    //La numeracion o nombre del documento ya ha sido enviado anteriormente
                    //Esto indica que si la numeración ya fué enviada se está generando un mismo número de resumen ya enviado
                    //por ello debemos crear un resumen anulado para que la numeración siga adelante.
                    $resp_guardar_resumen_anulado = $this->crear_resumen_anulado_para_continuar_numeracion($id_contribuyente, $fecha_documentos, $data, $accion);
                    if($resp_guardar_resumen_anulado['respuesta'] == 'error') {
                        return $resp_guardar_resumen_anulado;
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error al Enviar a Sunat..';
                    $resp['codigo_error_sunat'] = $codigo_error_sunat;
                    $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                    return $resp;
                } else if ($codigo_error_sunat == 'soap-env:Client.0402'){
                    //No tiene el perfil para enviar comprobantes electronicos - Detalle: Rejected by policy.
                    $resp['respuesta'] = 'error';
                    $resp['codigo_error_sunat'] = $codigo_error_sunat;
                    $resp['titulo'] = 'Error al Enviar a Sunat...';
                    $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                    return $resp;

                } else {
                    if($httpcode_error_sunat == '502') {
                        $resp['respuesta'] = 'error';
                        $resp['codigo_error_sunat'] = $codigo_error_sunat;
                        $resp['titulo'] = 'Error al Enviar a Sunat....';
                        $resp['mensaje'] = 'Al parecer el WebService de SUNAT no está Respondiendo Correctamente, por favor inténtalo en unos minutos!';
                        return $resp;
                    }
                }

                $mensaje_resp = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                $mensaje_resp = empty($mensaje_resp)?(!isset($resp_api->mensaje)?'':$resp_api->mensaje):$mensaje_resp;

                if (strpos($mensaje_resp, "2223") !== false) {
                    $resp_guardar_resumen_anulado = $this->crear_resumen_anulado_para_continuar_numeracion($id_contribuyente, $fecha_documentos, $data, $accion);
                    if($resp_guardar_resumen_anulado['respuesta'] == 'error') {
                        return $resp_guardar_resumen_anulado;
                    }
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error al Enviar a Sunat.....*';
                $resp['codigo_error_sunat'] = $codigo_error_sunat;
                $resp['mensaje'] = $mensaje_resp;
                return $resp;
            } else if(isset($resp_api->codigo_error_api) && $resp_api->codigo_error_api == 'error_consulta_ticket') {
                
                $this->db->begin();
                $estado_envio_sunat = 'ticket';
                if($resp_api->cod_sunat == '2987') {
                    $estado_envio_sunat = 'anulado';
                }
                $new_resumen_boletas = new ResumenBoletas();
                $new_resumen_boletas->id_contribuyente = $id_contribuyente;
                $new_resumen_boletas->codigo = $data['codigo'];
                $new_resumen_boletas->serie = $data['serie'];
                $new_resumen_boletas->secuencia = $data['secuencia'];
                $new_resumen_boletas->fecha_documentos = date("Y-m-d", strtotime($fecha_documentos));
                $new_resumen_boletas->fecha_envio_sunat = date('Y-m-d H:i:s');
                $new_resumen_boletas->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
                $new_resumen_boletas->numero_ticket = $resp_api->codigo_ticket;
                $new_resumen_boletas->estado_envio_sunat = $estado_envio_sunat;
                $new_resumen_boletas->ruta_xml = $data['secret_data']['ruta_dir_xml'];
                $new_resumen_boletas->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
                $new_resumen_boletas->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;
                $new_resumen_boletas->estado_documento = 'activo';
                $new_resumen_boletas->accion_sunat = $accion;
                $new_resumen_boletas->intentos_envio_sunat = 1;
                $new_resumen_boletas->tipo_envio_sunat = $data['secret_data']['tipo_proceso'];
                $new_resumen_boletas->detalle = json_encode($lista_items_afectados);

                try {
                    if(!$new_resumen_boletas->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($new_resumen_boletas->getMessages() as $message) {
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
                    $resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
                    return $resp;
                }

                $resp_asignar_resumen = $this->registrar_idresumen_en_documentos($lista_items_afectados, $data['codigo'], $data['serie'], $data['secuencia'], $estado_envio_sunat, $resp_api, $accion, $idusuario);
                if($resp_asignar_resumen['respuesta'] == 'error') {
                    $this->db->rollback();
                    return $resp_asignar_resumen;
                }

                $saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));

                $this->db->commit();
                $mensaje_ticket = !isset($resp_api->msj_sunat)?'':(' Error: <strong class="text-danger">'.$resp_api->msj_sunat.'</strong>');
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envio Correcto!';
                $resp['mensaje'] = 'El Resúmen Diario '.$data['codigo'].'-'.$data['serie'].'-'.$data['secuencia'].' fué enviado correctamente, sin embargo aún no se pudo recepcionar el CDR, el número de ticket es el siguiente: '.$resp_api->codigo_ticket.' '.$mensaje_ticket;
                return $resp;

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Desconocido!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?(isset($resp_api->mensaje)?$resp_api->mensaje:''):$resp_api->msj_sunat;
                return $resp;
            }
		} else if($resp_api->respuesta == 'ok') {
            if($resp_api->cod_sunat == '0' || $resp_api->cod_sunat == '2282') {
                //0: El archivo fué aceptado

                $this->db->begin();
                $new_resumen_boletas = new ResumenBoletas();
                $new_resumen_boletas->id_contribuyente = $id_contribuyente;
                $new_resumen_boletas->codigo = $data['codigo'];
                $new_resumen_boletas->serie = $data['serie'];
                $new_resumen_boletas->secuencia = $data['secuencia'];
                $new_resumen_boletas->fecha_documentos = date("Y-m-d", strtotime($fecha_documentos));
                $new_resumen_boletas->fecha_envio_sunat = date('Y-m-d H:i:s');
                $new_resumen_boletas->hash_cpe = !isset($resp_api->hash_cpe)?'':$resp_api->hash_cpe;
                $new_resumen_boletas->numero_ticket = $resp_api->codigo_ticket;
                if($accion == 3) {
                    $estado_envio_sunat = 'anulado';
                } else {
                    $estado_envio_sunat = 'aceptado';
                }
                $new_resumen_boletas->estado_envio_sunat = $estado_envio_sunat;
                $new_resumen_boletas->ruta_xml = $data['secret_data']['ruta_dir_xml'];
                $new_resumen_boletas->name_xml = !isset($resp_api->name_file_xml_cpe)?'':$resp_api->name_file_xml_cpe;
                $new_resumen_boletas->name_xml_zip = !isset($resp_api->name_file_zip_cpe)?'':$resp_api->name_file_zip_cpe;
                $new_resumen_boletas->estado_documento = 'activo';
                $new_resumen_boletas->accion_sunat = $accion;
                $new_resumen_boletas->intentos_envio_sunat = 1;
                $new_resumen_boletas->tipo_envio_sunat = $data['secret_data']['tipo_proceso'];
                $new_resumen_boletas->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
                $new_resumen_boletas->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
                $new_resumen_boletas->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                $new_resumen_boletas->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
                $new_resumen_boletas->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
                $new_resumen_boletas->detalle = json_encode($lista_items_afectados);
                
                $saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
                $saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));

                try {
                    if(!$new_resumen_boletas->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($new_resumen_boletas->getMessages() as $message) {
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
                    $resp['mensaje'] = 'Error al insertar el registro en la Base de Datos, intenta nuevamente por favor.';
                    return $resp;
                }

                $resp_asignar_resumen = $this->registrar_idresumen_en_documentos($lista_items_afectados, $data['codigo'], $data['serie'], $data['secuencia'], $estado_envio_sunat, $resp_api, $accion, $idusuario);
                if($resp_asignar_resumen['respuesta'] == 'error') {
                    $this->db->rollback();
                    return $resp_asignar_resumen;
                }

                $this->db->commit();
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Envio Correcto!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                return $resp;

            } else if ($resp_api->cod_sunat == '2223') {
                //2223: Aparentemente el mismo resumen ya fué presentado, aquí tenemos dos problemas, no sabemos si el resumen en cuestión tiene las mismas boletas, es un problema pq al día siguiente habrán algunas boletas que no se puedan enviar porque ya habrán sido enviadas en un resumen anterior!.
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                echo json_encode($resp);
                exit();
            } else {
                //aquí podemos trabajar con las posibles errores de rechazo!
                //http://cpe.sunat.gob.pe/boleta-de-venta-desde-los-sistemas-del-contribuyente (sección preguntas frecuentes)
                //indica que en caso  haya errores se debe enviar un nuevo resumen solucionando o reparando los errores indicados!
                
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Respuesta Desconocida!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                return $resp;
            }
        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error Desconocido!';
            $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
            return $resp;
        }
    }

    public function crear_resumen_anulado_para_continuar_numeracion($id_contribuyente, $fecha_documentos, $data, $accion) {
        $new_resumen_boletas = new ResumenBoletas();
        $new_resumen_boletas->id_contribuyente = $id_contribuyente;
        $new_resumen_boletas->codigo = $data['codigo'];
        $new_resumen_boletas->serie = $data['serie'];
        $new_resumen_boletas->secuencia = $data['secuencia'];
        $new_resumen_boletas->fecha_documentos = date("Y-m-d", strtotime($fecha_documentos));
        $new_resumen_boletas->fecha_envio_sunat = date('Y-m-d H:i:s');
        $new_resumen_boletas->hash_cpe = null;
        $new_resumen_boletas->numero_ticket = null;
        $new_resumen_boletas->estado_envio_sunat = 'rechazado';
        $new_resumen_boletas->ruta_xml =  null;
        $new_resumen_boletas->name_xml =  null;
        $new_resumen_boletas->name_xml_zip =  null;
        $new_resumen_boletas->estado_documento = 'activo';
        $new_resumen_boletas->accion_sunat = $accion;
        $new_resumen_boletas->intentos_envio_sunat = 1;
        $new_resumen_boletas->tipo_envio_sunat = $data['secret_data']['tipo_proceso'];
        $new_resumen_boletas->detalle =  null;

        try {
            if(!$new_resumen_boletas->save()) {
                $msg = '';
                foreach ($new_resumen_boletas->getMessages() as $message) {
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
            $resp['mensaje'] = 'Error al insertar el registro en la Base de Datos, intenta nuevamente por favor.';
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['titulo'] = 'guardado';
        $resp['mensaje'] = 'El resumen fué guardado correctamente!';
        return $resp;
    }

    public function validar_documento_para_envio_sunat($documento, $accion) {

        //escenarios
        /*
        1.- Si la acción es (1: adicionar)
            Si es BOLETA: Verificar que no pertenezca a ningún resúmen diario, si el resumen diario tiene un número de ticket o un cdr se supone que ya fué enviado. 
            Si es Nota Crédito o Débito: 
                Verificar que el documento no pertenezca a ningún resumen diario.

                Verificar que la boleta a quien se intenta modificar ya tenga un cdr válido y aceptado, o mejor dicho que ya pertenezca a un resúmen diario que sea válido y aceptado.
        2.- Si la acción es (3: anular)
            Verificar si ya tiene un cdr válido, y si el estado de respuesta de la sunat es aceptado

        */

        if($accion == 1) {
            if($documento->estado_envio_sunat != 'pendiente') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento '.$documento->serie_comprobante.'-'.$documento->numero_comprobante.', ya fué enviado previamente, no puede ser enviado nuevamente!';
                return $resp;
            }
        }

        if($accion == 3) {
            if($documento->estado_envio_sunat != 'aceptado') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento '.$documento->serie_comprobante.'-'.$documento->numero_comprobante.', debe estar aceptado antes de ser anulado!';
                return $resp;
            }
        }

        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function consultarTicketAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $inicio_consultas_internas = microtime(true);
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión!';
                echo json_encode($resp);
                exit();
            }

            $numero_ticket = !isset($datapost['numero_ticket'])?'':$datapost['numero_ticket'];
            $idcontribuyente = !isset($datapost['idcontribuyente'])?'':intval($datapost['idcontribuyente']);

            if($idcontribuyente > 0) {
                if($usuario->id_contribuyente != $idcontribuyente) {
                    if($usuario->id_rol == 1) {

                    } else {
                        $idcontribuyente = $usuario->id_contribuyente;
                    }
                }
            } else {
                $idcontribuyente = $usuario->id_contribuyente;
            }
            
            

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $idcontribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la cuenta!';
                echo json_encode($resp);
                exit();
            }
            
            $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: and numero_ticket = :numero_ticket:", 'bind' => array('numero_ticket' => $numero_ticket, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'id_contribuyente' => $contribuyente->id_contribuyente)));

            if(!$resumen) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El ticket número: '.$numero_ticket.', no se encuentra asignado a ningún resumen, por favor comuníquese con soporte';
                echo json_encode($resp);
                exit();
            }

            $resp['time_consultas_resumenboletas'] = microtime(true) - $inicio_consultas_internas;

            $inicio_proceso_data_emisor = microtime(true);
            $documentoelectronico = new DocumentoelectronicoController;
            $resp_data_emisor = $documentoelectronico->get_data_emisor($contribuyente->id_contribuyente);
            if($resp_data_emisor['respuesta'] == 'error') {
                echo json_encode($resp_data_emisor);
                exit();
            }

            $resp['time_consultas_dataemisor'] = microtime(true) - $inicio_proceso_data_emisor;
            
            $inicio_time_secret_data = microtime(true);
            $resp_secret_data = $documentoelectronico->get_secret_data($contribuyente->id_contribuyente);
            if($resp_secret_data['respuesta'] == 'error') {
                echo json_encode($resp_secret_data);
                exit();
            }
            $resp['time_consultas_secretdata'] = microtime(true) - $inicio_time_secret_data;

            $data['ruc'] = $resp_data_emisor['emisor']['ruc'];
            $data['secret_data'] = $resp_secret_data['secret_data'];
            $data['cod_ticket'] = $numero_ticket;
            $data['codigo'] = $resumen->codigo;
            $data['serie'] = $resumen->serie;
            $data['secuencia'] = $resumen->secuencia;

            $ruta_base_cpe_cdr = $data['secret_data']['ruta_dir_xml'].'resumenes/';
            if (!file_exists($ruta_base_cpe_cdr)) {
                mkdir($ruta_base_cpe_cdr, 0777, true);
            }

            $resp['time_consultas_internas'] = microtime(true) - $inicio_consultas_internas;

            $inicio_consulta_ticket_api = microtime(true);
            $herramientas = new HerramientasController;
            $ruta = 'https://facturalahoy.com/api/resumenboletas/get_cdr';
            $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
            $resp_api = json_decode($resp_api_ws);
            $resp['response'] = $resp_api_ws;
            $resp['resp_api_ws'] = $resp_api;
            $resp['time_consulta_ticket_api'] = microtime(true) - $inicio_consulta_ticket_api;
            $resp['time_consultas_internas_y_api'] = microtime(true) - $inicio_consultas_internas;

            if(empty($resp_api)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['error_code'] = 'envio_sunat';
                $resp['mensaje'] = 'El CDR Aún no se encuentra disponible, por favor intente más tarde!';
                //$resp['mensaje'] = 'No hemos podido recuperar el CDR, obtenemos el siguiente error: '.$resp_api_ws;
                echo json_encode($resp);
                exit();
            }

            if($resp_api->respuesta == 'error') {
                //Si ingresa aquí debemos verificar si es un error en el envío o un error en la recepción del cdr
                if($resp_api->codigo_error_api == 'error_consulta_ticket') {

                    if($resp_api->cod_sunat == '2987') {
                        $this->db->begin();
    
                        $resumen->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
                        $resumen->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
                        $resumen->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                        $resumen->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
                        $resumen->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
                        $resumen->estado_envio_sunat = 'anulado';
    
                        $saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));
                        
                        try {
                            if(!$resumen->save()) {
                                $this->db->rollback();
                                $msg = '';
                                foreach ($resumen->getMessages() as $message) {
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
    
                        $lista_documentos = DocElectronico::find(array("rb_id_contribuyente = :rb_id_contribuyente: and rb_codigo = :rb_codigo: and rb_serie = :rb_serie: and rb_secuencia = :rb_secuencia:", 'bind' => array('rb_id_contribuyente' => $resumen->id_contribuyente, 'rb_codigo' => $resumen->codigo, 'rb_serie' => $resumen->serie, 'rb_secuencia' => $resumen->secuencia)));
    
                        $resp_asignar_resumen = $this->registrar_idresumen_en_documentos($lista_documentos, $resumen->codigo, $resumen->serie, $resumen->secuencia, 'anulado', $resp_api, $resumen->accion_sunat, $usuario->idusuario);
                        if($resp_asignar_resumen['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_asignar_resumen);
                            exit();
                        }
    
                        $saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));
    
                        $this->db->commit();
                        $resp['respuesta'] = 'ok';
                        $resp['titulo'] = 'Envio Correcto!';
                        $resp['mensaje'] = 'Se recuperó correctamente el CDR del resúmen:  '.$resumen->codigo.'-'.$resumen->serie.'-'.$resumen->secuencia.', la respuesta fué: '.$resp_api->msj_sunat;
                        echo json_encode($resp);
                        exit();
                    }
                    
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se logró obtener el cdr, se obtiene el siguiente error: '.$resp_api->msj_sunat;
                    echo json_encode($resp);
                    exit();
                } else {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error Desconocido';
                    $resp['mensaje'] = 'No se logró obtener el cdr, se obtiene el siguiente error: '.$resp_api->msj_sunat;
                    echo json_encode($resp);
                    exit();
                }
            } else if($resp_api->respuesta == 'ok') {
                if($resp_api->cod_sunat == '0' || $resp_api->cod_sunat == '2282') {
                    //0: El archivo fué aceptado

                    $this->db->begin();

                    $resumen->hash_cdr = !isset($resp_api->hash_cdr)?'':$resp_api->hash_cdr;
                    $resumen->cod_sunat = !isset($resp_api->cod_sunat)?'':$resp_api->cod_sunat;
                    $resumen->msje_sunat = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                    $resumen->name_cdr = !isset($resp_api->name_file_xml_cdr)?'':$resp_api->name_file_xml_cdr;
                    $resumen->name_cdr_zip = !isset($resp_api->name_file_zip_cdr)?'':$resp_api->name_file_zip_cdr;
                    $resumen->estado_envio_sunat = 'aceptado';

                    $saved_file_cdr_zip = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cdr, base64_decode($resp_api->file_cdr_zip));

                    try {
                        if(!$resumen->save()) {
                            $this->db->rollback();
                            $msg = '';
                            foreach ($resumen->getMessages() as $message) {
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

                    $lista_documentos = DocElectronico::find(array("rb_id_contribuyente = :rb_id_contribuyente: and rb_codigo = :rb_codigo: and rb_serie = :rb_serie: and rb_secuencia = :rb_secuencia:", 'bind' => array('rb_id_contribuyente' => $resumen->id_contribuyente, 'rb_codigo' => $resumen->codigo, 'rb_serie' => $resumen->serie, 'rb_secuencia' => $resumen->secuencia)));

                    $resp_asignar_resumen = $this->registrar_idresumen_en_documentos($lista_documentos, $resumen->codigo, $resumen->serie, $resumen->secuencia, 'aceptado', $resp_api, $resumen->accion_sunat, $usuario->idusuario);
                    if($resp_asignar_resumen['respuesta'] == 'error') {
                        $this->db->rollback();
                        echo json_encode($resp_asignar_resumen);
                        exit();
                    }

                    $saved_file = @file_put_contents($ruta_base_cpe_cdr.$resp_api->name_file_zip_cpe, base64_decode($resp_api->file_cpe_zip));

                    $this->db->commit();
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Envio Correcto!';
                    $resp['mensaje'] = 'Se recuperó correctamente el CDR del resúmen:  '.$resumen->codigo.'-'.$resumen->serie.'-'.$resumen->secuencia.', la respuesta fué: '.$resp_api->msj_sunat;
                    $resp['tiempo_total'] = microtime(true) - $inicio_consultas_internas;
                    echo json_encode($resp);
                    exit();

                } else if ($resp_api->cod_sunat == '2223') {
                    //2223: Aparentemente el mismo resumen ya fué presentado, aquí tenemos dos problemas, no sabemos si el resumen en cuestión tiene las mismas boletas, es un problema pq al día siguiente habrán algunas boletas que no se puedan enviar porque ya habrán sido enviadas en un resumen anterior!.

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                    echo json_encode($resp);
                    exit();
                } else {
                    //aquí podemos trabajar con las posibles errores de rechazo!
                    //http://cpe.sunat.gob.pe/boleta-de-venta-desde-los-sistemas-del-contribuyente (sección preguntas frecuentes)
                    //indica que en caso  haya errores se debe enviar un nuevo resumen solucionando o reparando los errores indicados!
                    
                    $resp['respuesta'] = 'ok';
                    $resp['titulo'] = 'Envio Correcto!';
                    $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                    echo json_encode($resp);
                    exit();
                }
                
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Desconocido!';
                $resp['mensaje'] = !isset($resp_api->msj_sunat)?'':$resp_api->msj_sunat;
                echo json_encode($resp);
                exit();
            }
        }
    }
}
?>