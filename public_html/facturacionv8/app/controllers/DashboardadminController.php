<?php

class DashboardadminController extends ControllerBase
{
    public function indexAction($id_contribuyente = '', $id_tipodoc = '', $serie = '', $num_doc = '') {
		$this->setTitle('Dashboard');
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

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/pnotify.min.js")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?j=".rand()) 
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/dashboardadmin/index.js?i=".rand());

			$this->view->id_tipodoc = $id_tipodoc;
			
			$contribuyentes = Contribuyente::find(array("tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('tipo_envio_sunat' => 'produccion')));
			$this->view->contribuyentes = $contribuyentes;
	}
	
	public function getListaDocsSunatAction() {
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

			if($contribuyente->id_contribuyente != 1) {
				$resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No tiene permiso';
                echo json_encode($resp);
                exit();
			}

			$id_empresa = intval($datapost['id_contribuyente']) + 0;
			if($id_empresa > 0) {
				$empresa = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_empresa)));
				if(!$empresa) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El contribuyente buscado no existe!';
					echo json_encode($resp);
					exit();
				}
			}

			$estado_documentos = $datapost['estado_documento'];
			if($estado_documentos != 'todos') {
				$array_estado_validos = array('aceptado', 'anulado', 'pendiente', 'rechazado', 'ticket', 'xml');
				if(!in_array($estado_documentos, $array_estado_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El estado seleccionado no es válido!';
					echo json_encode($resp);
					exit();
				}
            }

			$tipo_documentos = $datapost['tipo_documento'];
			if($tipo_documentos != 'todos') {
				$array_tipos_validos = array('01', '03', '07', '08', '09');
				if(!in_array($tipo_documentos, $array_tipos_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Tipo de Documento no Válido!';
					echo json_encode($resp);
					exit();
				}
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
			
            $tipo_envio_sunat = 'produccion';
            
			$query_1 = "fecha_registro >= CAST(:fecha_inicio: AS DATE) and fecha_registro <= CAST(:fecha_fin: AS DATE) and tipo_envio_sunat = :tipo_envio_sunat: ";
			
			if(intval($id_empresa) > 0) {
                $query_1 = $query_1.' and id_contribuyente = '.$id_empresa;
			}
            
            if($estado_documentos == 'xml') {
                $query_1 = $query_1.' and (name_xml_zip is NULL or name_xml_zip = "") and estado_envio_sunat <> "pendiente" ';
            } else {
                if($estado_documentos != 'todos') {
                    $query_1 = $query_1.' and estado_envio_sunat = "'.$estado_documentos.'"';
                }
            }
			
    
            if($tipo_documentos != 'todos') {
                $query_1 = $query_1." and id_tipodoc_electronico in ('".$tipo_documentos."')";
            } else {
				$query_1 = $query_1." and id_tipodoc_electronico in ('01', '03', '07', '08')";
			}
            
            $comprobantes = DocElectronico::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "fecha_registro DESC"));
            
            //Inicio de configuración Server Side.
            $total_registros = count($comprobantes);

            $columnas = array( 
                //indice de la columna en datatable => nombre de la columna
                0 => '`de`.`fecha_registro`',
                1 => 'doc_serie_numero',
                2 => 'ruc_nom_clie',
                3 => '`de`.`total`',
                4 => '`de`.`fecha_registro`',
                5 => '`de`.`fecha_registro`',
                6 => '`de`.`fecha_registro`',
                7 => '`de`.`fecha_registro`',
                8 => '`de`.`fecha_registro`'
            );

            $query = "SELECT de.id_contribuyente, de.id_tipodoc_electronico, de.serie_comprobante, de.numero_comprobante, de.tipo_envio_sunat, de.total, de.nro_otr_comprobante, de.fecha_comprobante, de.fecha_vto_comprobante, de.id_codigomoneda, de.idcliente, de.id_vendedor, de.id_sucursal, de.fecha_registro, de.id_tipo_comprobante_modifica, de.serie_documento_modifica, de.nro_documento_modifica, de.id_condicionpago, de.monto_adeudado, de.fecha_pagopendiente, de.fecha_envio_sunat, de.estado_envio_sunat, de.hash_cpe, de.hash_cdr, de.cod_sunat, de.msje_sunat, de.ruta_xml, de.name_xml, de.name_xml_zip, de.name_cdr, de.name_cdr_zip, de.estado_documento, de.rb_id_contribuyente, de.rb_codigo, de.rb_serie, de.rb_secuencia, de.rb_tipo_envio_sunat, contribuyente.ruc, CONCAT(de.serie_comprobante,'-',de.numero_comprobante) as doc_serie_numero, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, cpago.tipo, cpago.condicionpago FROM doc_electronico de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago INNER JOIN contribuyente on de.id_contribuyente = contribuyente.id_contribuyente where de.fecha_comprobante >= CAST(:fecha_inicio AS DATE) and de.fecha_comprobante <= CAST(:fecha_fin AS DATE) and contribuyente.tipo_envio_sunat = :tipo_envio_sunat and de.tipo_envio_sunat = :tipo_envio_sunat ";

			if(intval($id_empresa) > 0) {
                $query = $query.' and de.id_contribuyente = '.$id_empresa;
			}

            if($estado_documentos == 'xml') {
                $query = $query.' and (de.name_xml_zip is NULL or de.name_xml_zip = "") and de.estado_envio_sunat <> "pendiente" ';
            } else {
                if($estado_documentos != 'todos') {
                    $query = $query.' and de.estado_envio_sunat = "'.$estado_documentos.'"';
                }
            }
    
            if($tipo_documentos != 'todos') {
                $query = $query." and de.id_tipodoc_electronico in ('".$tipo_documentos."')";
            } else {
				$query = $query." and de.id_tipodoc_electronico in ('01', '03', '07', '08')";
			}

            if(!empty($datapost['search']['value'])) {
                $query = $query." AND (
                    CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                    CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or 
                    de.transporte_nro_placa like :termino or 
                    de.nro_otr_comprobante like :termino or 
                    de.id_codigomoneda like :termino or cpago.tipo like :termino or cpago.condicionpago like :termino or 
					contribuyente.ruc like :termino
                    ) ";
            }

            //de.transporte_nro_placa like :termino or de.nro_otr_comprobante like :termino or de.id_codigomoneda like :termino

            $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $datapost['search']['value']);

            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
            
            
            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
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
			
			$reportedocumentos = new ReportedocumentosController;
			
            $array_lista = array();
            $array_lista_ids = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
				$item = (object)$fila;
				$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $item->id_contribuyente)));

                $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
                $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
                $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));

                $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||a4");
                $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||ticket");

                $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		        $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

                //botón para ver e imprimir el pdf del documento electrónico
                $btn_pdf = '
                <a title="Formato A4" target="_blank" href="'.$url_a4.'"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="max-width: 30px;"></a>
                
                <a title="Formato Ticket" target="_blank" href="'.$url_ticket.'"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 30px;"></a>
                ';

                //botón para ver y descargar el xml_firmado del documento electrónico
                if($item->tipo_envio_sunat == 'produccion') {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
                } else {
                    $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
                }

                $html_subtitulo_doc = '';
                if($item->id_tipodoc_electronico == '01') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                    $resp_verificacion_notas = $reportedocumentos->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                    $html_subtitulo_doc = $resp_verificacion_notas['html_nota_credito'].$resp_verificacion_notas['html_nota_debito'];
                } else if($item->id_tipodoc_electronico == '03') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                    $resp_verificacion_notas = $reportedocumentos->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                    $html_subtitulo_doc = $resp_verificacion_notas['html_nota_credito'].$resp_verificacion_notas['html_nota_debito'];
                } else if($item->id_tipodoc_electronico == '07') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_credito/';
                    $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-blue position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
                } else if($item->id_tipodoc_electronico == '08') {
                    $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_debito/';
                    $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-danger position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
                }
                
                if(!empty($item->name_xml_zip)) {
                    if (file_exists($ruta_base_zip_cpe_cdr.$item->name_xml_zip)) {
                        //Existe el XML FIRMADO
                        $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/'.$item->id_contribuyente.'/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'/xml_cpe_zip"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="max-width: 30px;"></a>';
                    } else {
                        $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="max-width: 30px;"></a>';
                    }
                } else {
                    $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="max-width: 30px;"></a>';
                }

                $menu_opciones = $reportedocumentos->get_options_button($item);
                $btn_cdr = $reportedocumentos->get_cdr_button($ruta_base_zip_cpe_cdr, $item, $tipo_envio_sunat);
                $btn_sunat = $reportedocumentos->get_sunat_button($ruta_base_zip_cpe_cdr, $item, $tipo_envio_sunat, $tipo_comprobante->descripcion);

                if($item->estado_envio_sunat == 'aceptado') {
                    $menu_opciones = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                '.$menu_opciones.'
                                <li>
                                <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar CPE Via Email</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    ';
                } else {
                    $menu_opciones = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                <li>
                                <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar CPE Via Email</a>
                                </li>
                                <li><a href="javascript:void(0)" onclick=\'ver_estado_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><img src="/facturacionv8/img/sunat_logo.png" /> Verificar Estado en SUNAT</a></li>
                            </ul>
                        </li>
                    </ul>
                    ';
                }

                $texto_placa_vehiculo = '';
                $texto_num_orden = '';
                //$texto_moneda = '';

                if(!empty($item->transporte_nro_placa)) {
                    $texto_placa_vehiculo = '<span class="label bg-success-400">Nro. Placa: '.$item->transporte_nro_placa.'</span>';
                }
                
                if(!empty($item->nro_otr_comprobante)) {
                    $texto_num_orden = '<span style="margin-left: 4px;" class="label bg-primary-400">Nro Orden: '.$item->nro_otr_comprobante.'</span>';
                }

                if($texto_placa_vehiculo == '' && $texto_num_orden == '') {
                    $texto_num_orden_placa = '';
                } else {
                    $texto_num_orden_placa = '<br />'.$texto_placa_vehiculo.$texto_num_orden;
                }
                
                $html_condicion_pago = '';
                if(!empty($item->id_condicionpago)) {
                    $html_condicion_pago = $reportedocumentos->get_html_condicionpago($item->id_condicionpago, $item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante, $item->fecha_pagopendiente, $item->monto_adeudado, $item->id_codigomoneda);
                }

                $numero_ticket = '';
                if($item->id_tipodoc_electronico == '03') {
                    $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->rb_id_contribuyente, 'codigo' => $item->rb_codigo, 'serie' => $item->rb_serie, 'secuencia' => $item->rb_secuencia, 'tipo_envio_sunat' => $item->tipo_envio_sunat)));

                    if($resumen) {
                        $numero_ticket = $resumen->numero_ticket;
                    }
                }
                

                $n++;
                $array_lista[] = array(
                    'DT_RowId' => $item->id_contribuyente.'||'.$item->id_tipodoc_electronico.'||'.$item->serie_comprobante.'||'.$item->numero_comprobante.'||'.$item->tipo_envio_sunat.'||'.$item->estado_envio_sunat.'||'.$numero_ticket,
					'fecha_registro' => date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
					'comprobante' => '<span class="label bg-success-400">Contribuyente: '.$contribuyente->ruc.'</span><br />'.$tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante.$texto_num_orden_placa.'<br />'.$html_subtitulo_doc.$html_condicion_pago, 
                    'cliente' => $cliente->num_doc.'<br />'.$cliente->razon_social, 
                    'total' => $moneda->simbolo.' '.$item->total, 
                    'pdf' => $btn_pdf, 
                    'xml' => $btn_xml, 
                    'cdr' => $btn_cdr, 
                    'sunat' => $btn_sunat, 
                    'opciones' => $menu_opciones
                );

                //id_contribuyente || id_tipodoc_electronico || serie_comprobante || numero_comprobante || tipo_envio_sunat
                //$array_lista_ids[] = $item->id_contribuyente.'||'.$item->id_tipodoc_electronico.'||'.$item->serie_comprobante.'||'.$item->numero_comprobante.'||'.$item->tipo_envio_sunat.'||'.$item->estado_envio_sunat;
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

	public function get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $tipo_envio_sunat, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
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
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $n++;
        }

        return $n;
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

            $id_contribuyente = empty($datapost['id_contribuyente'])?0:$datapost['id_contribuyente'];
            $id_tipodoc_electronico = empty($datapost['id_tipodoc_electronico'])?'':$datapost['id_tipodoc_electronico'];
            $serie_comprobante = empty($datapost['serie_comprobante'])?'':$datapost['serie_comprobante'];
            $numero_comprobante = empty($datapost['numero_comprobante'])?0:$datapost['numero_comprobante'];

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el ID de contribuyente';
                echo json_encode($resp);
                exit();
			}
			
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
            
            $resp_api = $herramientas->get_status_doc_sunat($data);
			$resp_api_2 = $resp_api;
            $resp_api = json_decode($resp_api);
            $resp_api = @json_decode($resp_api);
            
            if(empty($resp_api) || !isset($resp_api->rpta) || $resp_api->rpta == 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'SUNAT no está respondiendo correctamente!!';
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

			$resp['estado_documento'] = $estado_envio_sunat;
			$resp['resp_api'] = $resp_api;
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

            if($usuario->id_rol != 1) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permitido crear estas operaciones, solo un administrador puede hacerlo!';
                echo json_encode($resp);
                exit();
			}
			
			$id_contribuyente = $datapost['id_contribuyente'];

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
                    $resp['titulo'] = 'error_sin_xml';
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
                $data_recover['usuario_sol'] = $contribuyente->usuario_sol;
                $data_recover['ruc_proveedor'] = $this->ruc_proveedor;
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
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Proceso Correcto';
            $resp['mensaje'] = 'Se ha modificado correctamente el documento!';
            echo json_encode($resp);
            exit();
        }
    }
}