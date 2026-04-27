<?php
class ReportedocumentosController extends ControllerBase
{
	public function indexAction($id_contribuyente = '', $id_tipodoc = '', $serie = '', $num_doc = '') {
		$this->setTitle('Comprobantes de pago');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/jgrowl.min.js?i=v2")
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js?i=v2")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js?i=v2")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/ui/moment/moment.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/pickers/daterangepicker.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
            ->addJs($this->baseUri . "public/js/reportedocumentos.js?i=".rand());
           // ->addJs($this->baseUri . "public/js/helptour/reportedocumentos_tour_1.js?i=v2");

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
        
        $this->response->redirect('dashboard');
    }

    public function getListaDocumentosAction() {
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

            $resp = $this->get_lista_documentos($datapost, $contribuyente, $usuario);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();
        }
    }

    public function get_lista_documentos($datapost, $contribuyente, $usuario) {

        $herramientas = new HerramientasController;
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                        $datapost['vendedores'] = array($usuario->idusuario);
                    }
                }
            }
        }
        
        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', $datapost['vendedores']);
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', $datapost['sucursales']);
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];

        if(!$herramientas->validate_date_time($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Inicio';
            $resp['mensaje'] = 'La fecha de inicio no es válida! '.$fecha_inicio_valor;
            return $resp;
        }

        if(!$herramientas->validate_date_time($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Fin';
            $resp['mensaje'] = 'La fecha de fin no es válida!';
            return $resp;
        }
        
        $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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
        
        

        $estado_envio_sunat = $contribuyente->tipo_envio_sunat;
        
        $query_1 = " fecha_registro >= :fecha_inicio: and fecha_registro <= :fecha_fin: and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: and id_tipodoc_electronico in ('01', '03', '07', '08') ";

        if(count($ids_vendedores) > 0){ $query_1 = $query_1." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query_1 = $query_1." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        $comprobantes = DocElectronico::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), 'columns' => 'id_contribuyente, id_tipodoc_electronico, serie_comprobante, tipo_envio_sunat', "order" => "fecha_registro DESC"));
        
        //Inicio de configuración Server Side.
        $total_registros = count($comprobantes);

        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => '`de`.`fecha_registro`',
            1 => 'doc_serie_numero',
            2 => 'ruc_nom_clie',
            3 => '`de`.`total`',
            4 => '`de`.`total`',
            5 => '`de`.`fecha_registro`',
            6 => '`de`.`fecha_registro`',
            7 => '`de`.`fecha_registro`',
            8 => '`de`.`fecha_registro`',
            9 => '`de`.`fecha_registro`',
            10 => '`de`.`fecha_registro`'
        );

        $query = "SELECT de.*, CONCAT(de.serie_comprobante,'-',de.numero_comprobante) as doc_serie_numero, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, clie.celular cliente_celular, clie.razon_social cliente_nombre, cpago.tipo, cpago.condicionpago FROM doc_electronico de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago where de.fecha_registro >= :fecha_inicio and de.fecha_registro <= :fecha_fin and de.id_contribuyente = :id_contribuyente and de.tipo_envio_sunat = :tipo_envio_sunat and de.id_tipodoc_electronico in ('01', '03', '07', '08') ";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($datapost['search']['value'])) {
            $query = $query." AND (
                CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or 
                de.transporte_nro_placa like :termino or 
                de.nro_otr_comprobante like :termino or 
                de.id_codigomoneda like :termino or cpago.tipo like :termino or cpago.condicionpago like :termino
                ) ";
        }

        //de.transporte_nro_placa like :termino or de.nro_otr_comprobante like :termino or de.id_codigomoneda like :termino

        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $datapost['search']['value']);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        
        /*
        $query = str_replace(':fecha_inicio', "'".$fecha_inicio."'", $query);
        $query = str_replace(':fecha_fin', "'".$fecha_fin."'", $query);
        $query = str_replace(':id_contribuyente', $usuario->id_contribuyente, $query);
        $query = str_replace(':tipo_envio_sunat', "'".$contribuyente->tipo_envio_sunat."'", $query);

        if(!empty($datapost['search']['value'])) {
            $termino_busqueda = '%'.$datapost['search']['value'].'%';
            $query = str_replace(':termino', "'".$termino_busqueda."'", $query);
        }

        echo $query;
        exit();
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
            $resp['titulo'] = 'Error en Sucursal';
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }
        
        $array_lista = array();
        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
            $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));

            $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_a4 = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';

            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';
            $btn_pdf = $btn_pdf_a4.$btn_pdf_ticket;

            //botón para ver y descargar el xml_firmado del documento electrónico
            if($item->tipo_envio_sunat == 'produccion') {
                $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
            } else {
                $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
            }

            $html_subtitulo_doc = '';
            if($item->id_tipodoc_electronico == '01') {
                $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                $resp_verificacion_notas = $this->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                $html_subtitulo_doc = $resp_verificacion_notas['html_nota_credito'].$resp_verificacion_notas['html_nota_debito'];
            } else if($item->id_tipodoc_electronico == '03') {
                $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'facturas_boletas/';
                $resp_verificacion_notas = $this->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                $html_subtitulo_doc = $resp_verificacion_notas['html_nota_credito'].$resp_verificacion_notas['html_nota_debito'];
            } else if($item->id_tipodoc_electronico == '07') {
                $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_credito/';
                $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-blue position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
            } else if($item->id_tipodoc_electronico == '08') {
                $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'nota_debito/';
                $html_subtitulo_doc = '<div class="text-muted text-size-small"> <span class="status-mark border-danger position-left"></span> Modifica: '.$item->serie_documento_modifica.'-'.$item->nro_documento_modifica.' </div>';
            }
            
            $ruta_xml = '';
            if(!empty($item->name_xml_zip)) {
                if (file_exists($ruta_base_zip_cpe_cdr.$item->name_xml_zip)) {
                    //Existe el XML FIRMADO
                    $ruta_xml = "/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cpe_zip";
                    $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="'.$ruta_xml.'"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="width: 30px;"></a>';
                } else {
                    $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                }
            } else {
                $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
            }

            $ruta_cdr = '';
            if(!empty($item->name_cdr_zip)) {
                if(file_exists($ruta_base_zip_cpe_cdr.$item->name_cdr_zip)) {
                    $ruta_cdr = "/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cdr_zip";
                }
            }
            
            $menu_opciones = $this->get_options_button($item);
            $btn_cdr = $this->get_cdr_button($ruta_base_zip_cpe_cdr, $item, $estado_envio_sunat);
            $btn_sunat = $this->get_sunat_button($ruta_base_zip_cpe_cdr, $item, $estado_envio_sunat, $tipo_comprobante->descripcion);

            $menu_duplicar_comprobantes = '';
            if($item->id_tipodoc_electronico == '01') {
                $menu_duplicar_comprobantes = $menu_duplicar_comprobantes.'<li><a href="/facturacionv8/documentoelectronico/index/03/duplicar_factura/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/boleta.svg" style="width: 20px;"> Crear Boleta</a></li>';
                $menu_duplicar_comprobantes = $menu_duplicar_comprobantes.'<li><a href="/facturacionv8/documentoelectronico/index/01/duplicar_factura/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/factura.svg" style="width: 20px;"> Duplicar Factura</a></li>';
            }
            
            if ($item->id_tipodoc_electronico == '03') {
                $menu_duplicar_comprobantes = $menu_duplicar_comprobantes.'<li><a href="/facturacionv8/documentoelectronico/index/03/duplicar_boleta/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/boleta.svg" style="width: 20px;"> Duplicar Boleta</a></li>';
                $menu_duplicar_comprobantes = $menu_duplicar_comprobantes.'<li><a href="/facturacionv8/documentoelectronico/index/01/duplicar_boleta/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/factura.svg" style="width: 20px;"> Crear Factura</a></li>';
            }
            
            $btn_var_whatsapp = "'$item->id_tipodoc_electronico', '$item->serie_comprobante', '$item->numero_comprobante', '$dominio_principal', '$url_a4', '$url_ticket', '/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cpe_zip', '$item->cliente_celular', '$item->cliente_nombre'";
            $btn_envio_whatsapp = '<li><a href="javascript:void(0)" onclick="enviar_mensaje_whatsapp('.$btn_var_whatsapp.')"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 20px;"> Enviar WhatsApp</a></li>';

            $tipo_de_operacion = $this->get_tipo_operacion($item->id_tipo_operacion);

            //-----INICIO ETIQUETAS-------------------
            $gestiondeetiquetas = new GestiondeetiquetasController;
            $resp_etiquetas = $gestiondeetiquetas->get_etiquetas_documento($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante, $estado_envio_sunat);
            $etiquetas_html = $resp_etiquetas['etiquetas_html'];
            $menu_etiqueta = $resp_etiquetas['menu_etiqueta'];
            //----------FIN ETIQUETAS-------------------

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
                            </li>'.$btn_envio_whatsapp.$menu_duplicar_comprobantes.$menu_etiqueta.'
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
                            </li>'.$btn_envio_whatsapp.'
                            <li><a href="javascript:void(0)" onclick=\'ver_estado_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><img src="/facturacionv8/img/sunat_logo.png" /> Verificar Estado en SUNAT</a></li>'.$menu_duplicar_comprobantes.$menu_etiqueta.'
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
                $html_condicion_pago = $this->get_html_condicionpago($item->id_condicionpago, $item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante, $item->fecha_pagopendiente, $item->monto_adeudado, $item->id_codigomoneda);
            }

            if(isset($datapost['mostrar_opt_menu']) && $datapost['mostrar_opt_menu'] == 'no') {
                $menu_opciones = '';
            }

            $logs_html = '';
            if($item->estado_documento != 'activo') {
                $etiqueta_anulacion = '<span class="label bg-danger">ANULADO</span><br/>';
                $html_condicion_pago = '';

                $log_documento = LogDocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'id_tipodoc_electronico' => $item->id_tipodoc_electronico, 'numero_comprobante' => $item->numero_comprobante, 'serie_comprobante' => $item->serie_comprobante, 'tipo_envio_sunat' => $item->tipo_envio_sunat)));

                if($log_documento) {
                    $logs_html = $log_documento->descripcion;
                }
            }

            $lista_destino_docs = DocRelacion::find(array("
                    destino_id_contribuyente        = :destino_id_contribuyente: and 
                    destino_id_tipodoc_electronico  = :destino_id_tipodoc_electronico: and 
                    destino_serie_comprobante       = :destino_serie_comprobante: and 
                    destino_numero_comprobante      = :destino_numero_comprobante: and 
                    destino_tipo_envio_sunat        = :destino_tipo_envio_sunat:
                ", 'bind' => array(
                    'destino_id_contribuyente'          => $item->id_contribuyente, 
                    'destino_id_tipodoc_electronico'    => $item->id_tipodoc_electronico, 
                    'destino_serie_comprobante'         => $item->serie_comprobante, 
                    'destino_numero_comprobante'        => $item->numero_comprobante, 
                    'destino_tipo_envio_sunat'          => $item->tipo_envio_sunat
                )));

            foreach($lista_destino_docs as $destino_doc) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $destino_doc->id_vendedor)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$destino_doc->descripcion.'<br /> 
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($destino_doc->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }
            
            $lista_origen_docs = DocRelacion::find(array("
                origen_id_contribuyente         = :origen_id_contribuyente: and 
                origen_id_tipodoc_electronico   = :origen_id_tipodoc_electronico: and 
                origen_serie_comprobante        = :origen_serie_comprobante: and 
                origen_numero_comprobante       = :origen_numero_comprobante: and 
                origen_tipo_envio_sunat         = :origen_tipo_envio_sunat:
            ", 'bind' => array(
                'origen_id_contribuyente'       => $item->id_contribuyente, 
                'origen_id_tipodoc_electronico' => $item->id_tipodoc_electronico, 
                'origen_serie_comprobante'      => $item->serie_comprobante,
                'origen_numero_comprobante'     => $item->numero_comprobante, 
                'origen_tipo_envio_sunat'       => $item->tipo_envio_sunat
            )));

            foreach($lista_origen_docs as $origen_doc) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $origen_doc->id_vendedor)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$origen_doc->descripcion.'<br />
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($origen_doc->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }

            $user_creador_doc = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'idusuario' => $item->id_vendedor)));
            $nombre_user_creador_doc = '';
            if($user_creador_doc) {
                $nombre_user_creador_doc = $user_creador_doc->nombre.' '.$user_creador_doc->apellido." (ID: $user_creador_doc->idusuario)";
            }

            $n++;
            $array_lista[] = array(
                'fecha_cpe'         => date("d-m-Y / H:i A", strtotime($item->fecha_registro)), 
                'datetime'          => $item->fecha_registro,
                'documento'         => $tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante.$texto_num_orden_placa.'<br />'.$html_subtitulo_doc.$html_condicion_pago, 
                
                'serie_comprobante'     => $item->serie_comprobante,
                'numero_comprobante'    => $item->numero_comprobante,
                'nombre_cpe'            => $tipo_comprobante->descripcion,
                'url_a4'                => $url_a4,
                'url_ticket'            => $url_ticket,
                'ruta_xml'              => $ruta_xml,
                'ruta_cdr'              => $ruta_cdr,
                'cliente_tipo_doc'      => $cliente->id_tipodocidentidad,
                'cliente_num_doc'       => $cliente->num_doc,
                'cliente_nombre'        => $cliente->razon_social,

                'cliente'               => $cliente->num_doc.'<br />'.$cliente->razon_social, 
                'total'                 => $moneda->simbolo.' '.$item->total, 
                'total_monto'           => $item->total,
                'simbolo_moneda'        => $moneda->simbolo,
                'nota'                  => $item->nota,
                
                'pdf'               => $btn_pdf, 
                'xml'               => $btn_xml, 
                'cdr'               => $btn_cdr, 
                'sunat'             => $btn_sunat, 
                'opciones'          => $menu_opciones,
                'etiquetas'         => $etiquetas_html,
                'etiquetas_activas' => isset($resp_etiquetas['etiquetas_activas'])?$resp_etiquetas['etiquetas_activas']:'',
                'tipo_operacion'    => $tipo_de_operacion,
                'retencion'         => ($item->retencion_aplica == 'si')?'Si':'No',
                'logs_html'         => $logs_html,
                'nombre_user_creador_doc' => $nombre_user_creador_doc
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
        $resp['data'] = $json_data;
        return $resp;
    }
    
    public function get_tipo_operacion($id_tipooperacion) {
        if($id_tipooperacion == '0101') {
            return 'Venta Interna';
        } else if($id_tipooperacion == '0200') {
            return 'Exportación de Bienes';
        } else if($id_tipooperacion == '0201') {
            return 'Exportación de Servicios';
        } else if($id_tipooperacion == '1001') {
            return 'Operación Sujeta a Detracción';
        } else if($id_tipooperacion == '2001') {
            return 'Operación Sujeta a Percepción';
        } else {
            return 'Venta Interna';
        }
    }

    public function get_html_condicionpago($id_condicionpago, $id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $fecha_pagopendiente, $monto_adeudado, $moneda = 'PEN') {
        $herramientas = new HerramientasController;
        $condiciondepago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", "bind" => array('id_condicionpago' => $id_condicionpago)));

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $array_cpe = array('01', '03', '07', '08');

        if(in_array($id_tipodoc_electronico, $array_cpe)) {
            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
        } else {
            $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
        }

        $html_condicion_pago = '';
        if($condiciondepago) {
            if($condiciondepago->tipo == 'credito') {
                $opt_agregar_cuotas = "si";
                if($id_tipodoc_electronico == '01') { 
                    if($documento->credito_sunat == 'si') {
                        $opt_agregar_cuotas = "no";
                    }
                }

                $funcion_ver_crear_abonos = "";

                if(!empty($fecha_pagopendiente) && $monto_adeudado > 0) {
                    $funcion_ver_crear_abonos = "ver_crear_abonos($id_contribuyente, '$id_tipodoc_electronico', '$serie_comprobante', $numero_comprobante, '".date('Y-m-d', strtotime($fecha_pagopendiente))."', 'credito', $monto_adeudado, '$moneda', '$opt_agregar_cuotas')";

                    $resp_fecha_2 = $herramientas->comparar_fechas($fecha_pagopendiente, date('Y-m-d'));
                    if($resp_fecha_2['diferencia_primera_segunda'] <= 15) {
                        if($resp_fecha_2['diferencia_primera_segunda'] <= 5) {
                            //ver_crear_abonos(1, '01', 'F001', 638, '2021-12-08', 'credito', 80.00, 'PEN', 'no')
                            $html_condicion_pago = '<a href="javascript:void(0)" style="cursor: pointer;" onclick="'.$funcion_ver_crear_abonos.'" class="label bg-danger">Crédito al: '.date("d-m-Y", strtotime($fecha_pagopendiente)).'</a>';
                        } else {
                            $html_condicion_pago = '<a href="javascript:void(0)" style="cursor: pointer;" onclick="'.$funcion_ver_crear_abonos.'" class="label bg-primary">Crédito al: '.date("d-m-Y", strtotime($fecha_pagopendiente)).'</a>';
                        }
                    } else {
                        $html_condicion_pago = '<a href="javascript:void(0)" style="cursor: pointer;" onclick="'.$funcion_ver_crear_abonos.'" class="label bg-success">Crédito al: '.date("d-m-Y", strtotime($fecha_pagopendiente)).'</a>';
                    }
                } else {
                    if($monto_adeudado > 0) {
                        $html_condicion_pago = '<a href="javascript:void(0)" style="cursor: pointer;" onclick="'.$funcion_ver_crear_abonos.'" class="label bg-success">Crédito</a>';
                    }
                }
            } else if ($condiciondepago->tipo == 'tarjeta_credito') {
                $html_condicion_pago = '<span class="label bg-info">Tarjeta</span>';
            } else if ($condiciondepago->tipo == 'transferencia') {
                $html_condicion_pago = '<span class="label bg-info">Transferencia</span>';
            } else {
                $html_condicion_pago = '';
            }
            $html_condicion_pago = empty($html_condicion_pago)?$html_condicion_pago:'<br />'.$html_condicion_pago;
        }

        return $html_condicion_pago;
    }

    public function getListaGuiasRemisionAction() {
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

            $resp = $this->get_lista_guias_remision($datapost, $contribuyente, $usuario);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();
        }
    }

    public function get_lista_guias_remision($datapost, $contribuyente, $usuario) {
        
        $herramientas = new HerramientasController;
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                        $datapost['vendedores'] = array($usuario->idusuario);
                    }
                }
            }
        }
        
        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', $datapost['vendedores']);
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', $datapost['sucursales']);
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];
        
        if(!$herramientas->validate_date_time($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Inicio';
            $resp['mensaje'] = 'La fecha de inicio no es válida!';
            return $resp;
        }

        if(!$herramientas->validate_date_time($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Fin';
            $resp['mensaje'] = 'La fecha de fin no es válida!';
            return $resp;
        }

        $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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

        $estado_envio_sunat = $contribuyente->tipo_envio_sunat;

        $query_1 = "fecha_registro >= :fecha_inicio: and fecha_registro <= :fecha_fin: and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: and id_tipodoc_electronico  = '09' ";

        if(count($ids_vendedores) > 0){ $query_1 = $query_1." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query_1 = $query_1." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
        
        $comprobantes = DocElectronico::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro DESC"));
        
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

        $query = "SELECT de.*, CONCAT(de.serie_comprobante,'-',de.numero_comprobante) as doc_serie_numero, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, clie.celular cliente_celular, clie.razon_social cliente_nombre FROM doc_electronico de INNER JOIN cliente clie ON de.idcliente = clie.idcliente where de.fecha_registro >= :fecha_inicio and de.fecha_registro <= :fecha_fin and de.id_contribuyente = :id_contribuyente and de.tipo_envio_sunat = :tipo_envio_sunat and de.id_tipodoc_electronico = '09' ";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($datapost['search']['value'])) {
            $query = $query." AND (CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or de.transporte_nro_placa like :termino or de.nro_otr_comprobante like :termino or de.id_codigomoneda like :termino) ";
        }

        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $datapost['search']['value']);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        
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
            $resp['titulo'] = 'Error en Sucursal';
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }
        
        $array_lista = array();
        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));

            $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            
            $btn_pdf = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';

            //botón para ver y descargar el xml_firmado del documento electrónico
            if($item->tipo_envio_sunat == 'produccion') {
                $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
            } else {
                $ruta_base_zip_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
            }

            $html_subtitulo_doc = '';
            $ruta_base_zip_cpe_cdr = $ruta_base_zip_cpe_cdr.'guias_remision/';

            $ruta_xml = '';
            if(!empty($item->name_xml_zip)) {
                if (file_exists($ruta_base_zip_cpe_cdr.$item->name_xml_zip)) {
                    //Existe el XML FIRMADO
                    $ruta_xml = "/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cpe_zip";
                    $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="'.$ruta_xml.'"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="width: 30px;"></a>';
                } else {
                    $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                }
            } else {
                $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
            }

            $ruta_cdr = '';
            if(!empty($item->name_cdr_zip)) {
                if(file_exists($ruta_base_zip_cpe_cdr.$item->name_cdr_zip)) {
                    $ruta_cdr = "/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cdr_zip";
                }
            }

            $btn_envio_whatsapp = '';
            $btn_var_whatsapp = "'$item->id_tipodoc_electronico', '$item->serie_comprobante', '$item->numero_comprobante', '$dominio_principal', '$url_a4', '$url_ticket', '/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cpe_zip', '$item->cliente_celular', '$item->cliente_nombre'";
            $btn_envio_whatsapp = '<li><a href="javascript:void(0)" onclick="enviar_mensaje_whatsapp('.$btn_var_whatsapp.')"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 20px;"> Enviar WhatsApp</a></li>';

            //-----INICIO ETIQUETAS-------------------
            $gestiondeetiquetas = new GestiondeetiquetasController;
            $resp_etiquetas = $gestiondeetiquetas->get_etiquetas_documento($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante, $estado_envio_sunat);
            $etiquetas_html = $resp_etiquetas['etiquetas_html'];
            $menu_etiqueta = $resp_etiquetas['menu_etiqueta'];
            //----------FIN ETIQUETAS-------------------

            $menu_opciones = $this->get_options_button($item);
            $btn_cdr = $this->get_cdr_button($ruta_base_zip_cpe_cdr, $item, $estado_envio_sunat);
            $btn_sunat = $this->get_sunat_button($ruta_base_zip_cpe_cdr, $item, $estado_envio_sunat, $tipo_comprobante->descripcion);

            $menu_opciones = '
            <ul class="icons-list text-center">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                        '.$menu_opciones.'
                        <li>
                        <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar al Correo Electrónico</a>
                        </li>
                        '.$btn_envio_whatsapp.$menu_etiqueta.'
                        <li><a href="/facturacionv8/documentoelectronico/index/01/transformar_09_a_01/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/factura.svg" style="width: 20px;"> Crear Factura</a></li>
                        <li><a href="/facturacionv8/documentoelectronico/index/03/transformar_09_a_03/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/boleta.svg" style="width: 20px;"> Crear Boleta</a></li>
                        <li><a href="/facturacionv8/documentoelectronico/index/77/transformar_09_a_77/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_venta.svg" style="width: 20px;"> Crear Nota de Venta</a></li>
                        <li><a href="/facturacionv8/documentoelectronico/index/88/transformar_09_a_88/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/cotizacion.svg" style="width: 20px;"> Crear Cotización</a></li>
                    </ul>
                </li>
            </ul>
            ';
            /*
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
                            <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar al Correo Electrónico</a>
                            </li>
                            '.$btn_envio_whatsapp.$menu_etiqueta.'
                            <li><a href="/facturacionv8/documentoelectronico/index/01/transformar_09_a_01/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/factura.svg" style="width: 20px;"> Crear Factura</a></li>
                            <li><a href="/facturacionv8/documentoelectronico/index/03/transformar_09_a_03/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/boleta.svg" style="width: 20px;"> Crear Boleta</a></li>
                            <li><a href="/facturacionv8/documentoelectronico/index/77/transformar_09_a_77/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_venta.svg" style="width: 20px;"> Crear Nota de Venta</a></li>
                            <li><a href="/facturacionv8/documentoelectronico/index/88/transformar_09_a_88/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/cotizacion.svg" style="width: 20px;"> Crear Cotización</a></li>
                        </ul>
                    </li>
                </ul>
                ';
            } else {
                $menu_opciones = '';
            }
            */
            
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

            $user_creador_doc = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'idusuario' => $item->id_vendedor)));
            $nombre_user_creador_doc = '';
            if($user_creador_doc) {
                $nombre_user_creador_doc = $user_creador_doc->nombre.' '.$user_creador_doc->apellido." (ID: $user_creador_doc->idusuario)";
            }

            $n++;
            $array_lista[] = array(
                'fecha_cpe' => date("d-m-Y / H:i A", strtotime($item->fecha_registro)), 
                'datetime'  => $item->fecha_registro,
                'documento' => $tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante.$texto_num_orden_placa.'<br />'.$html_subtitulo_doc, 
                'cliente'   => $cliente->num_doc.'<br />'.$cliente->razon_social, 
                'peso'      => $item->peso, 
                'pdf'       => $btn_pdf.' '.$btn_pdf_ticket, 
                'xml'       => $btn_xml, 
                'cdr'       => $btn_cdr, 
                'sunat'     => $btn_sunat, 
                'opciones'  => $menu_opciones,
                'etiquetas' => $etiquetas_html,
                'nota'      => $item->nota,
                'etiquetas_activas' => isset($resp_etiquetas['etiquetas_activas'])?$resp_etiquetas['etiquetas_activas']:'',


                'serie_comprobante'     => $item->serie_comprobante,
                'numero_comprobante'    => $item->numero_comprobante,
                'nombre_cpe'            => $tipo_comprobante->descripcion,
                'url_a4'                => $url_a4,
                'url_ticket'            => $url_ticket,
                'ruta_xml'              => $ruta_xml,
                'ruta_cdr'              => $ruta_cdr,
                'cliente_tipo_doc'      => $cliente->id_tipodocidentidad,
                'cliente_num_doc'       => $cliente->num_doc,
                'cliente_nombre'        => $cliente->razon_social,

                'nombre_user_creador_doc' => $nombre_user_creador_doc
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
        $resp['data'] = $json_data;
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

    public function get_cdr_button($ruta_base_zip_cpe_cdr, $item, $estado_envio_sunat) {
        if($item->id_tipodoc_electronico == '01' || $item->id_tipodoc_electronico == '09' || $item->id_tipodoc_electronico == '31') {
            $btn_cdr = $this->html_button_cdr($ruta_base_zip_cpe_cdr, $item, 'facturasynotas', $estado_envio_sunat);
        } else if($item->id_tipodoc_electronico == '03') {
            $btn_cdr = $this->html_button_cdr($ruta_base_zip_cpe_cdr, $item, 'boletasynotas', $estado_envio_sunat);
        } else if($item->id_tipodoc_electronico == '07') {
            if($item->id_tipo_comprobante_modifica == '01') {
                $btn_cdr = $this->html_button_cdr($ruta_base_zip_cpe_cdr, $item, 'facturasynotas', $estado_envio_sunat);
            } else if($item->id_tipo_comprobante_modifica == '03') {
                $btn_cdr = $this->html_button_cdr($ruta_base_zip_cpe_cdr, $item, 'boletasynotas', $estado_envio_sunat);
            } else {
                $btn_cdr = '';
            }
        } else if($item->id_tipodoc_electronico == '08') {
            if($item->id_tipo_comprobante_modifica == '01') {
                $btn_cdr = $this->html_button_cdr($ruta_base_zip_cpe_cdr, $item, 'facturasynotas', $estado_envio_sunat);
            } else if($item->id_tipo_comprobante_modifica == '03') {
                $btn_cdr = $this->html_button_cdr($ruta_base_zip_cpe_cdr, $item, 'boletasynotas', $estado_envio_sunat);
            } else {
                $btn_cdr = '';
            }
        } else {
            $btn_cdr = '';
        }

        return $btn_cdr;
    } 

    public function get_sunat_button($ruta_base_zip_cpe_cdr, $item, $estado_envio_sunat, $tipodoc_descripcion) {
        if($item->id_tipodoc_electronico == '01' || $item->id_tipodoc_electronico == '09' || $item->id_tipodoc_electronico == '31') {
            $btn_sunat = $this->html_button_sunat($ruta_base_zip_cpe_cdr, $item, 'facturasynotas', $estado_envio_sunat, $tipodoc_descripcion);
        } else if($item->id_tipodoc_electronico == '03') {
            $btn_sunat = $this->html_button_sunat($ruta_base_zip_cpe_cdr, $item, 'boletasynotas', $estado_envio_sunat, $tipodoc_descripcion);
        } else if($item->id_tipodoc_electronico == '07') {
            if($item->id_tipo_comprobante_modifica == '01') {
                $btn_sunat = $this->html_button_sunat($ruta_base_zip_cpe_cdr, $item, 'facturasynotas', $estado_envio_sunat, $tipodoc_descripcion);
            } else if($item->id_tipo_comprobante_modifica == '03') {
                $btn_sunat = $this->html_button_sunat($ruta_base_zip_cpe_cdr, $item, 'boletasynotas', $estado_envio_sunat, $tipodoc_descripcion);
            } else {
                $btn_sunat = '';
            }
        } else if($item->id_tipodoc_electronico == '08') {
            if($item->id_tipo_comprobante_modifica == '01') {
                $btn_sunat = $this->html_button_sunat($ruta_base_zip_cpe_cdr, $item, 'facturasynotas', $estado_envio_sunat, $tipodoc_descripcion);
            } else if($item->id_tipo_comprobante_modifica == '03') {
                $btn_sunat = $this->html_button_sunat($ruta_base_zip_cpe_cdr, $item, 'boletasynotas', $estado_envio_sunat, $tipodoc_descripcion);
            } else {
                $btn_sunat = '';
            }
        } else {
            $btn_sunat = '';
        }

        return $btn_sunat;
    }

    public function get_options_button($item) {
        if($item->id_tipodoc_electronico == '01') {
            $btn_opciones = $this->html_button_opciones($item, 'facturasynotas');
            return $btn_opciones;
        } else if($item->id_tipodoc_electronico == '03') {
            $btn_opciones = $this->html_button_opciones($item, 'boletasynotas');
            return $btn_opciones;
        } else if($item->id_tipodoc_electronico == '07') {
            if($item->id_tipo_comprobante_modifica == '01') {
                $btn_opciones = $this->html_button_opciones($item, 'facturasynotas');
                return $btn_opciones;
            } else if($item->id_tipo_comprobante_modifica == '03') {
                $btn_opciones = $this->html_button_opciones($item, 'boletasynotas');
                return $btn_opciones;
            } else {
                $btn_opciones = '';
            }
        } else if($item->id_tipodoc_electronico == '08') {
            if($item->id_tipo_comprobante_modifica == '01') {
                $btn_opciones = $this->html_button_opciones($item, 'facturasynotas');
                return $btn_opciones;
            } else if($item->id_tipo_comprobante_modifica == '03') {
                $btn_opciones = $this->html_button_opciones($item, 'boletasynotas');
                return $btn_opciones;
            } else {
                $btn_opciones = '';
            }
        } else if($item->id_tipodoc_electronico == '09') {
            $btn_opciones = $this->html_button_opciones($item, 'guia_remision');
        } else if($item->id_tipodoc_electronico == '31') {
            $btn_opciones = $this->html_button_opciones($item, 'guia_transportista');
        } else {
            $btn_opciones = '';
        }

        return $btn_opciones;
    }

    public function html_button_opciones($item, $tipo) {
        $menu_opciones = '';

        if($tipo == 'guia_remision') {
            $menu_opciones = $menu_opciones.'';
            $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/guiaderemision/index/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><i class="icon-make-group"></i> Copiar Guía de Remisión</a></li>';
        } else if($tipo == 'guia_transportista') {
            $menu_opciones = $menu_opciones.'';
            $menu_opciones = $menu_opciones.'<li style="display:none;"><a href="/facturacionv8/guiatransportista/index/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><i class="icon-make-group"></i> Copiar Guía Transportista</a></li>';
        } else if($tipo == 'facturasynotas') {
            if(!empty($item->name_cdr_zip)) {
                $verificacion_notas = $this->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);

                //si ya fué enviado entonces se puede crear una nota de crédito o débito
                if($item->id_tipodoc_electronico == '01') {
                    $nota_credito_anulacion = $this->tiene_nota_credito_anulacion($item->id_contribuyente, '07', $item->serie_comprobante, $item->numero_comprobante);

                    if($nota_credito_anulacion['respuesta'] != 'ok') {
                        $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/documentoelectronico/index/'.$item->id_tipodoc_electronico.'/crear_nota_credito/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_credito.svg" style="width: 20px;"> Crear Nota Crédito</a></li>';
                    }
    
                    if(!$verificacion_notas['nota_debito']) {
                        $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/documentoelectronico/index/'.$item->id_tipodoc_electronico.'/crear_nota_debito/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_debito.svg" style="width: 20px;"> Crear Nota Débito</a></li>';
                    }

                    if(!$verificacion_notas['nota_credito'] && !$verificacion_notas['nota_debito']) {
                        $menu_opciones = $menu_opciones.'<li><a href="javascript:void(0)" onclick=\'open_modal_comunicacionbaja( "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>';
                    }
                } else {
                    $menu_opciones = $menu_opciones.'<li><a href="javascript:void(0)" onclick=\'open_modal_comunicacionbaja( "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>';
                }

            } else {
                //si está vacio es porque aún no se envió a SUNAT
                //Entonces se puede editar
                $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/documentoelectronico/index/'.$item->id_tipodoc_electronico.'/editar/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><i class="icon-pencil4"></i> Editar Documento</a></li>';
            }

            $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/guiaderemision/index/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><i class="icon-make-group"></i> Crear Guía de Remisión</a></li>';
        } else if($tipo == 'boletasynotas') {
            if(empty($item->rb_id_contribuyente) || empty($item->rb_codigo) || empty($item->rb_serie) || empty($item->rb_secuencia)) {
                //no pertenece a ningún resumen
                //Entonces se puede editar
                $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/documentoelectronico/index/'.$item->id_tipodoc_electronico.'/editar/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><i class="icon-pencil4"></i> Editar Documento</a></li>';
            } else {

                $verificacion_notas = $this->verificar_si_tiene_nota_credito_debito($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante);
                
                //si ya fué enviado entonces se puede crear una nota de crédito o débito
                if($item->id_tipodoc_electronico == '03') {
                    if(!$verificacion_notas['nota_credito']) {
                        $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/documentoelectronico/index/'.$item->id_tipodoc_electronico.'/crear_nota_credito/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_credito.svg" style="width: 20px;"> Crear Nota Crédito</a></li>';
                    }
    
                    if(!$verificacion_notas['nota_debito']) {
                        $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/documentoelectronico/index/'.$item->id_tipodoc_electronico.'/crear_nota_debito/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_debito.svg" style="width: 20px;"> Crear Nota Débito</a></li>';
                    }
                }
                
                $menu_opciones = $menu_opciones.'<li><a href="javascript:void(0)" onclick=\'enviar_resumen_individual_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', 3)\'><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>';
            }

            $menu_opciones = $menu_opciones.'<li><a href="/facturacionv8/guiaderemision/index/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'"><i class="icon-make-group"></i> Crear Guía de Remisión</a></li>';
        } else {
            $menu_opciones = '';
        }

        if($tipo != 'guia_remision' && $tipo != 'guia_transportista') {
            $menu_opciones = $menu_opciones.'
            <li><a href="javascript:void(0)" onclick=\'ver_estado_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'><img src="/facturacionv8/img/sunat_logo.png" /> Verificar Estado en SUNAT</a></li>
            ';
        }

        return $menu_opciones;
    }

    public function html_button_sunat($ruta_base_zip_cpe_cdr, $item, $tipo, $estado_envio_sunat, $tipodoc_descripcion) {
        if($tipo == 'facturasynotas') {
            //if (file_exists($ruta_base_zip_cpe_cdr.$item->name_cdr_zip) && !empty($item->name_cdr_zip)) {

                $item_fecha_envio_sunat = empty($item->fecha_envio_sunat)?'':date("d-m-Y", strtotime($item->fecha_envio_sunat));
                if($item->estado_envio_sunat == 'aceptado') {
                    $btn_sunat = '
                    <div class="btn-group">
                        <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i style="font-size: 21px;" class="icon-checkmark font-weight-bold text-success position-left"></i> &nbsp;<span class="caret text-success"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                            <li><a href="javascript:void(0)">'.$tipodoc_descripcion.' <span class="label label-primary pull-right">'.$item->serie_comprobante.' - '.$item->numero_comprobante.'</span></a></li>
                            <li><a href="javascript:void(0)"><i class="icon-checkmark font-weight-bold text-success"></i> Enviado a Sunat</li>
                            <li><a href="javascript:void(0)">ESTADO: <span class="label label-success pull-right">Aceptado</span></a></li>
                            <li><a href="javascript:void(0)">CÓDIGO: <span class="label label-success pull-right">'.$item->cod_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA ENVÍO: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_registro)).'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA REGISTRO: <span class="label label-success pull-right">'.$item_fecha_envio_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">Observación: '.$item->msje_sunat.'</a></li>
                        </ul>
                    </div>';
                } else if($item->estado_envio_sunat == 'pendiente') {
                    $btn_sunat = '<a title="Aún no ha sido enviado, click para enviar a sunat" onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "inmediato")\' href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
                } else if($item->estado_envio_sunat == 'rechazado') {
                    $btn_sunat = '
                    <div class="btn-group">
                        <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i style="font-size: 21px;" class="icon-cancel-circle2 font-weight-bold text-danger position-left"></i> &nbsp;<span class="caret text-success"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                            <li><a href="javascript:void(0)">'.$tipodoc_descripcion.' <span class="label label-primary pull-right">'.$item->serie_comprobante.' - '.$item->numero_comprobante.'</span></a></li>
                            <li><a href="javascript:void(0)"><i class="icon-checkmark font-weight-bold text-success"></i> Enviado a Sunat</li>
                            <li><a href="javascript:void(0)">ESTADO: <span class="label label-danger pull-right">Rechazado</span></a></li>
                            <li><a href="javascript:void(0)">CÓDIGO: <span class="label label-danger pull-right">'.$item->cod_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA ENVÍO: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_registro)).'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA REGISTRO: <span class="label label-success pull-right">'.$item_fecha_envio_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">Observación: '.$item->msje_sunat.'</a></li>
                        </ul>
                    </div>';

                    /*$btn_sunat = '<a title="El Documento ha sido rechazado" onclick=\'mostrar_mensaje_rechazo('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "inmediato")\' target="_blank" href="javascript:void(0)"><img src="/facturacionv8/img/svg/estado_rechazado_sunat.svg" style="width: 30px;"></a>';*/
                } else if($item->estado_envio_sunat == 'anulado') {
                    $btn_sunat = '
                    <div class="btn-group">
                        <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i style="font-size: 21px;" class="icon-cancel-square2 font-weight-bold text-danger position-left"></i> &nbsp;<span class="caret text-success"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                            <li><a href="javascript:void(0)">'.$tipodoc_descripcion.' <span class="label label-primary pull-right">'.$item->serie_comprobante.' - '.$item->numero_comprobante.'</span></a></li>
                            <li><a href="javascript:void(0)"><i class="icon-checkmark font-weight-bold text-success"></i> Enviado a Sunat</li>
                            <li><a href="javascript:void(0)">ESTADO: <span class="label label-danger pull-right">Rechazado</span></a></li>
                            <li><a href="javascript:void(0)">CÓDIGO: <span class="label label-danger pull-right">'.$item->cod_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA ENVÍO: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_registro)).'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA REGISTRO: <span class="label label-success pull-right">'.$item_fecha_envio_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">Observación: '.$item->msje_sunat.'</a></li>
                        </ul>
                    </div>';

                } else {
                    $btn_sunat = 'ESTADO DESCONOCIDO';
                }
            /*} else {
                $btn_sunat = '<a title="Aún no ha sido enviado, click para enviar a sunat" onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "inmediato")\' target="_blank" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
            }*/
        } else if($tipo == 'boletasynotas') {
            $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->rb_id_contribuyente, 'codigo' => $item->rb_codigo, 'serie' => $item->rb_serie, 'secuencia' => $item->rb_secuencia, 'tipo_envio_sunat' => $item->tipo_envio_sunat)));

            $numero_ticket = '';
            if($resumen) {
                $numero_ticket = $resumen->numero_ticket;
            }

            if(!empty($item->rb_id_contribuyente) && !empty($item->tipo_envio_sunat) && !empty($item->rb_serie)) {
                $resumen = true;
            }

            if(!$resumen) {
                $btn_sunat = '<a title="Haz Click para Enviar a SUNAT" onclick=\'enviar_resumen_individual_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', 1)\'  href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
            } else {
                $fecha_envio_boleta = isset($resumen->fecha_envio_sunat)?date("d-m-Y", strtotime($resumen->fecha_envio_sunat)):date("d-m-Y", strtotime($item->fecha_registro));
                if($item->estado_envio_sunat == 'ticket') {
                    $btn_sunat = '<a title="Haz Click para Enviar a SUNAT" onclick="descargar_cdr_resumen(\''.$numero_ticket.'\', \''.$item->id_contribuyente.'\')" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';

                } else if($item->estado_envio_sunat == 'aceptado') {
                    $btn_sunat = '
                    <div class="btn-group">
                        <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i style="font-size: 21px;" class="icon-checkmark font-weight-bold text-success position-left"></i> &nbsp;<span class="caret text-success"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                            <li><a href="javascript:void(0)">'.$tipodoc_descripcion.' <span class="label label-primary pull-right">'.$item->serie_comprobante.' - '.$item->numero_comprobante.'</span></a></li>
                            <li><a href="javascript:void(0)"><i class="icon-checkmark font-weight-bold text-success"></i> Enviado a Sunat</li>
                            <li><a href="javascript:void(0)">ESTADO: <span class="label label-success pull-right">Aceptado</span></a></li>
                            <li><a href="javascript:void(0)">CÓDIGO: <span class="label label-success pull-right">'.$item->cod_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA ENVÍO: <span class="label label-success pull-right">'.$fecha_envio_boleta.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA REGISTRO: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_registro)).'</span></a></li>
                            <li><a href="javascript:void(0)">Observación: '.$item->msje_sunat.'</a></li>
                        </ul>
                    </div>';
                } else if($item->estado_envio_sunat == 'rechazado') {
                    $btn_sunat = '
                    <div class="btn-group">
                        <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i style="font-size: 21px;" class="icon-cancel-circle2 font-weight-bold text-danger position-left"></i> &nbsp;<span class="caret text-success"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" style="width: 300px;">
                            <li><a href="javascript:void(0)">'.$tipodoc_descripcion.' <span class="label label-primary pull-right">'.$item->serie_comprobante.' - '.$item->numero_comprobante.'</span></a></li>
                            <li><a href="javascript:void(0)"><i class="icon-checkmark font-weight-bold text-success"></i> Enviado a Sunat</li>
                            <li><a href="javascript:void(0)">ESTADO: <span class="label label-danger pull-right">Rechazado</span></a></li>
                            <li><a href="javascript:void(0)">CÓDIGO: <span class="label label-danger pull-right">'.$item->cod_sunat.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA ENVÍO: <span class="label label-success pull-right">'.$fecha_envio_boleta.'</span></a></li>
                            <li><a href="javascript:void(0)">FECHA REGISTRO: <span class="label label-success pull-right">'.date("d-m-Y", strtotime($item->fecha_registro)).'</span></a></li>
                            <li><a href="javascript:void(0)">Observación: '.$item->msje_sunat.'</a></li>
                        </ul>
                    </div>';
                } else if($item->estado_envio_sunat == 'anulado') {
                    $btn_sunat = '
                    <div class="btn-group">
                        <button type="button" class="btn border-warning text-warning-600 btn-flat btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i style="font-size: 21px;" class="icon-cancel-square2 font-weight-bold text-danger position-left"></i> &nbsp;<span class="caret text-success"></span>
                        </button>
                    </div>';
                    
                } else {
                    $btn_sunat = 'Estado Desconocido!';
                }
            }
        } else {
            $btn_sunat = '';
        }

        return $btn_sunat;
    }

    public function html_button_cdr($ruta_base_zip_cpe_cdr, $item, $tipo, $estado_envio_sunat) {
        if($tipo == 'facturasynotas') {
            if($item->estado_envio_sunat == 'pendiente') {
                $btn_cdr = '<a title="Haz Click para Enviar a SUNAT" onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "inmediato")\'  href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
            } else if ($item->estado_envio_sunat == 'aceptado') {
                $btn_cdr = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/'.$item->id_contribuyente.'/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'/xml_cdr_zip"><img src="/facturacionv8/img/svg/xml_cdr.svg" style="width: 30px;"></a>';
            } else if ($item->estado_envio_sunat == 'rechazado') {
                $btn_cdr = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/downloadcpe/'.$item->id_contribuyente.'/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'/xml_cdr_zip"><img src="/facturacionv8/img/svg/xml_cdr.svg" style="width: 30px;"></a>';
            } else if ($item->estado_envio_sunat == 'anulado') {
                $btn_cdr = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/comunicacionbaja/'.$item->id_contribuyente.'/'.$item->id_tipodoc_electronico.'/'.$item->serie_comprobante.'/'.$item->numero_comprobante.'/xml_cdr_zip"><img src="/facturacionv8/img/svg/xml_cdr.svg" style="width: 30px;"></a>';
            } else {
                $btn_cdr = 'Estado desconocido!';
            }
            
            return $btn_cdr;
        } else if($tipo == 'boletasynotas') {
            if(empty($item->rb_id_contribuyente) || empty($item->rb_codigo) || empty($item->rb_serie) || empty($item->rb_secuencia)) {
                //Indica que aún no pertenece a ningún resumen creado, por lo que podemos enviarlo como un resumen individual.
                $btn_cdr = '<a title="Haz Click para Enviar a SUNAT" onclick=\'enviar_resumen_individual_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', 1)\'  href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';

                return $btn_cdr;
            } else {
                //indica que es posible que pertenezca a un resumen
                $resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->rb_id_contribuyente, 'codigo' => $item->rb_codigo, 'serie' => $item->rb_serie, 'secuencia' => $item->rb_secuencia, 'tipo_envio_sunat' => $item->tipo_envio_sunat)));
                if(!$resumen) {
                    //indica que no pertenece a ningún resumen por tanto podemos enviarlo nuevamente
                    $btn_cdr = '<a title="Haz Click para Enviar a SUNAT" onclick=\'enviar_resumen_individual_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.')\'  href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';

                    return $btn_cdr;
                } else {
                    if($resumen->estado_envio_sunat == 'ticket') {
                        //indica que aún no existe un cdr por tanto debemos extraer el cdr
                        $btn_cdr = '<a title="Haz Click para descargar el CDR desde SUNAT" onclick="descargar_cdr_resumen(\''.$resumen->numero_ticket.'\', \''.$item->id_contribuyente.'\')" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';

                        return $btn_cdr;
                    } else {
                        //indica que ya existe un cdr, por tanto se debe mostrar la respuesta
                        $btn_cdr = '<a title="Haz Click para Descargar el XML" target="_blank" href="/facturacionv8/download/resumen/'.$resumen->id_contribuyente.'/'.$resumen->codigo.'/'.$resumen->serie.'/'.$resumen->secuencia.'/xml_cdr_zip"><img src="/facturacionv8/img/svg/xml_cdr.svg" style="width: 30px;"></a>';

                        return $btn_cdr;
                    }
                }
            }
        } else {
            return '';
        }
    }

    public function verificar_si_tiene_nota_credito_debito($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante) {

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $notas = DocElectronico::find(
            array("
            id_contribuyente = :id_contribuyente: and 
            id_tipo_comprobante_modifica = :id_tipo_comprobante_modifica: and 
            serie_documento_modifica = :serie_documento_modifica: and 
            nro_documento_modifica = :nro_documento_modifica: and 
            tipo_envio_sunat = :tipo_envio_sunat: and 
            (estado_envio_sunat <> 'anulado' and estado_envio_sunat <> 'rechazado')", 
            'bind' => array(
                        'id_contribuyente' => $id_contribuyente,
                        'id_tipo_comprobante_modifica' => $id_tipodoc_electronico,
                        'serie_documento_modifica' => $serie_comprobante, 
                        'nro_documento_modifica' => $numero_comprobante,
                        'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat
                    )
                )
            );
        
        $html_nota_credito = '';
        $html_nota_debito = '';
        $nota_credito = false;
        $nota_debito = false;

        foreach($notas as $item) {
            if($item->id_tipodoc_electronico == '07') {
                $nota_credito = true;
                $html_nota_credito = $html_nota_credito.'<div class="text-muted text-size-small"> <span class="status-mark border-blue position-left"></span>Modificado por Nota Crédito: '.$item->serie_comprobante.'-'.$item->numero_comprobante.' </div>';
            }

            if($item->id_tipodoc_electronico == '08') {
                $nota_debito = true;
                $html_nota_debito = $html_nota_debito.'<div class="text-muted text-size-small"> <span class="status-mark border-danger position-left"></span>Modificado por Nota Débito: '.$item->serie_comprobante.'-'.$item->numero_comprobante.' </div>';
            }
        }

        $resp['respuesta'] = 'ok';
        $resp['html_nota_credito'] = $html_nota_credito;
        $resp['html_nota_debito'] = $html_nota_debito;
        $resp['nota_credito'] = $nota_credito;
        $resp['nota_debito'] = $nota_debito;
        return $resp;
    }

    public function tiene_nota_credito_anulacion($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $nota_credito = DocElectronico::findFirst(
            array("
            id_contribuyente = :id_contribuyente: and 
            id_tipo_comprobante_modifica = :id_tipo_comprobante_modifica: and 
            serie_documento_modifica = :serie_documento_modifica: and 
            nro_documento_modifica = :nro_documento_modifica: and 
            tipo_envio_sunat = :tipo_envio_sunat: and 
            id_tipodoc_electronico = :id_tipodoc_electronico: and 
            (estado_envio_sunat <> 'anulado' and estado_envio_sunat <> 'rechazado') and id_cod_tipomotivo_credito = :id_cod_tipomotivo_credito:", 
            'bind' => array(
                        'id_contribuyente' => $id_contribuyente,
                        'id_tipo_comprobante_modifica' => $id_tipodoc_electronico,
                        'serie_documento_modifica' => $serie_comprobante, 
                        'nro_documento_modifica' => $numero_comprobante,
                        'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat,
                        'id_cod_tipomotivo_credito' => '01',
                        'id_tipodoc_electronico' => '07'
                    )
                )
            );

        if(!$nota_credito) {
            $resp['respuesta'] = 'error';
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['nota_credito'] = $nota_credito;
        return $resp;
    }

    public function enviarDocumentoSunatAction() {
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
            
            $id_contribuyente = intval($datapost['id_contribuyente']) + 0;
            $id_tipodoc_electronico = $datapost['id_tipodoc_electronico'];
            $serie_comprobante = $datapost['serie_comprobante'];
            $numero_comprobante = intval($datapost['numero_comprobante']) + 0;
            $modo = $datapost['modo'];

            if($modo != 'inmediato' && $modo != 'solo_firma') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Usuario';
                $resp['mensaje'] = 'El modo seleccionado no es válido!';
                echo json_encode($resp);
                exit();
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

            $array_ids_docs_validos = array('01', '03', '07', '08', '09', '31');
            if(!in_array($id_tipodoc_electronico, $array_ids_docs_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de documento seleccionado no se reconoce';
                echo json_encode($resp);
                exit();
            }

            if(strlen($serie_comprobante) > 4) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La serie no existe!';
                echo json_encode($resp);
                exit();
            }

            if($id_tipodoc_electronico == '31') {
                $documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: AND id_tipodoc_electronico = :id_tipodoc_electronico: AND serie_comprobante = :serie_comprobante: AND numero_comprobante = :numero_comprobante: AND tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            } else {
                $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
            }

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en ApiRest';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
            if($confirmacion != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Debes Confirmar!';
                if($modo == 'inmediato') {
                    $resp['mensaje'] = '¿Realmente Deseas Enviar el Documento a SUNAT? Lo enviaremos inmediatamente!..';
                } else {
                    $resp['mensaje'] = '¿Seguro que deseas firmar el documento? Solo se firmará el documento y no se enviará el documento a SUNAT.';
                }
                echo json_encode($resp);
                exit();
            }

            if($id_tipodoc_electronico == '01') { //FACTURA
                $factura = new FacturaController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp_factura = $factura->enviar_factura_api($resp_guardado_bd, $modo, $idusuario);
                echo json_encode($resp_factura);
                exit();
            } else if($id_tipodoc_electronico == '03') { //BOLETA
                if($modo == 'inmediato') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Recurda que las boletas no se permiten enviarse de una a una, se deben enviar en un resúmen diario de boletas';
                    echo json_encode($resp);
                    exit();
                }

                $factura = new FacturaController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp = $factura->enviar_factura_api($resp_guardado_bd, 'solo_firma', $idusuario);
                echo json_encode($resp);
                exit();
            } else if($id_tipodoc_electronico == '07') {
                if ($documento->id_tipo_comprobante_modifica == '03') { //modifica una boleta
                    if($modo == 'inmediato') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Recurda que las notas de crédito de boletas no se permiten enviarse de una a una, se deben enviar en un resúmen diario de boletas';
                        echo json_encode($resp);
                        exit();
                    }
                }
                $nota_credito = new NotadecreditoController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp = $nota_credito->enviar_notacredito_api($resp_guardado_bd, $modo);
                echo json_encode($resp);
                exit();

            } else if($id_tipodoc_electronico == '08') {
                if ($documento->id_tipo_comprobante_modifica == '03') { //modifica una boleta
                    if($modo == 'inmediato') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Recurda que las notas de débito de boletas no se permiten enviarse de una a una, se deben enviar en un resúmen diario de boletas';
                        echo json_encode($resp);
                        exit();
                    }
                }
                
                $nota_debito = new NotadedebitoController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp = $nota_debito->enviar_notadebito_api($resp_guardado_bd, $modo);
                echo json_encode($resp);
                exit();
            } else if($id_tipodoc_electronico == '09') { //GUIA DE REMISION
                $guia_remision = new GuiaderemisionController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp_guia_remision = $guia_remision->enviar_guia_remision_api($resp_guardado_bd, $modo);
                echo json_encode($resp_guia_remision);
                exit();
            } else if($id_tipodoc_electronico == '31') {
                $guia_transportista = new GuiatransportistaController;
                $resp_guardado_bd['id_contribuyente'] = $id_contribuyente;
                $resp_guardado_bd['id_tipodoc_electronico'] = $id_tipodoc_electronico;
                $resp_guardado_bd['serie_comprobante'] = $serie_comprobante;
                $resp_guardado_bd['numero_comprobante'] = $numero_comprobante;
                $resp_guardado_bd['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
                $resp_guardado_bd['id_usuario'] = $idusuario;
                
                $resp_guia_transportista = $guia_transportista->enviar_guiatransportista_sunat($resp_guardado_bd);
                echo json_encode($resp_guia_transportista);
                exit();

            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de documento seleccionado no se reconoce';
                echo json_encode($resp);
                exit();
            }

        }
    }

    public function getNotasDeVentaAction() {
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

            $resp = $this->get_notas_de_venta($datapost, $contribuyente, $usuario);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();
        }
    }

    public function get_notas_de_venta($datapost, $contribuyente, $usuario) {
        $herramientas = new HerramientasController;
            
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                        $datapost['vendedores'] = array($usuario->idusuario);
                    }
                }
            }
        }
        
        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', $datapost['vendedores']);
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', $datapost['sucursales']);
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];
        
        if(!$herramientas->validate_date_time($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Inicio';
            $resp['mensaje'] = 'La fecha de inicio no es válida!';
            return $resp;
        }

        if(!$herramientas->validate_date_time($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Fin';
            $resp['mensaje'] = 'La fecha de fin no es válida!';
            return $resp;
        }

        $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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

        $estado_envio_sunat = $contribuyente->tipo_envio_sunat;


        $query_1 = "fecha_registro >= :fecha_inicio: and fecha_registro <= :fecha_fin: and id_contribuyente = :id_contribuyente: and modalidad = :modalidad: and id_tipodocumento = :id_tipodocumento: and tipo = 'venta' ";

        if(count($ids_vendedores) > 0){ $query_1 = $query_1." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query_1 = $query_1." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
        
        $comprobantes = DocNoOficial::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $contribuyente->id_contribuyente, 'modalidad' => $contribuyente->tipo_envio_sunat, 'id_tipodocumento' => '77'), "order" => "fecha_registro DESC"));

        //Inicio de configuración Server Side.
        $total_registros = count($comprobantes);

        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => '`de`.`numero_comprobante`',
            1 => '`de`.`fecha_registro`',
            2 => 'ruc_nom_clie',
            3 => '`de`.`total`',
            4 => '`de`.`fecha_registro`',
            5 => '`de`.`fecha_registro`',
            6 => '`de`.`fecha_registro`',
            7 => '`de`.`fecha_registro`',
            8 => '`de`.`fecha_registro`',
            9 => '`de`.`fecha_registro`'
        );

        $query = "SELECT de.*, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, clie.celular cliente_celular, clie.razon_social cliente_nombre, cpago.tipo, cpago.condicionpago FROM doc_no_oficial de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago WHERE de.fecha_registro >= :fecha_inicio and de.fecha_registro <= :fecha_fin and de.id_contribuyente = :id_contribuyente and de.modalidad = :tipo_envio_sunat and de.id_tipodocumento = '77' and de.tipo = 'venta' ";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($datapost['search']['value'])) {
            $query = $query." AND (
                CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or 
                de.numero_comprobante like :termino or 
                de.transporte_nro_placa like :termino or 
                de.nro_otr_comprobante like :termino or 
                de.id_codigomoneda like :termino or 
                cpago.tipo like :termino or cpago.condicionpago like :termino
                ) ";
        }

        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $datapost['search']['value']);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

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
            $resp['titulo'] = 'Error en Sucursal';
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }

        $array_lista = array();
        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
            $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));

            //id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
            $string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$item->id_tipodocumento."|| ||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$usuario->id_contribuyente||".$item->id_tipodocumento."|| ||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_a4 = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';

            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';

            //$btn_pdf = $btn_pdf.$btn_pdf_ticket;

            $btn_envio_whatsapp = '';
            $menu_anulacion = '';
            $etiqueta_anulacion = '';
            if($item->estado_documento == 'activo') {
                $menu_anulacion = '<li><a href="javascript:void(0)" onclick=\'anular_doc_no_oficial( "'.$item->id_tipodocumento.'", '.$item->numero_comprobante.', '.$item->id_sucursal.')\'><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>';

                $btn_var_whatsapp = "'$item->id_tipodocumento', '', '$item->numero_comprobante', '$dominio_principal', '$url_a4', '$url_ticket', '', '$item->cliente_celular', '$item->cliente_nombre'";
                $btn_envio_whatsapp = '<li><a href="javascript:void(0)" onclick="enviar_mensaje_whatsapp('.$btn_var_whatsapp.')"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 20px;"> Enviar WhatsApp</a></li>';
            }

            //-----INICIO ETIQUETAS-------------------
            $gestiondeetiquetas = new GestiondeetiquetasController;
            $resp_etiquetas = $gestiondeetiquetas->get_etiquetas_documento($item->id_contribuyente, $item->id_tipodocumento, 'NV01', $item->numero_comprobante, $contribuyente->tipo_envio_sunat);
            $etiquetas_html = $resp_etiquetas['etiquetas_html'];
            $menu_etiqueta = $resp_etiquetas['menu_etiqueta'];
            //----------FIN ETIQUETAS-------------------
            
            $menu_opciones = '
            <ul class="icons-list text-center">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                        '.$menu_anulacion.'

                        <li><a href="/facturacionv8/documentoelectronico/index/03/transformar_nota_venta/N/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/boleta.svg" style="width: 20px;"> Crear Boleta</a></li>

                        <li><a href="/facturacionv8/documentoelectronico/index/01/transformar_nota_venta/N/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/factura.svg" style="width: 20px;"> Crear Factura</a></li>

                        <li><a href="/facturacionv8/documentoelectronico/index/77/transformar_nota_venta/N/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_venta.svg" style="width: 20px;"> Duplicar Nota de Venta</a></li>

                        <li><a href="/facturacionv8/guiaderemision/index/77/NOTA/'.$item->numero_comprobante.'"><i class="icon-make-group"></i> Crear Guía Remisión</a></li>

                        <li>
                        <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodocumento."'".', '."'".""."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar via Email</a>
                        </li>
                        '.$btn_envio_whatsapp.$menu_etiqueta.'
                    </ul>
                </li>
            </ul>
            ';

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
                $html_condicion_pago = $this->get_html_condicionpago($item->id_condicionpago, $item->id_contribuyente, '77', 'NV', $item->numero_comprobante, $item->fecha_pagopendiente, $item->monto_adeudado, $item->id_codigomoneda);
            }

            $logs_html = '';
            if($item->estado_documento != 'activo') {
                $etiqueta_anulacion = '<span class="label bg-danger">ANULADO</span><br/>';
                $html_condicion_pago = '';

                $log_documento = LogDocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'id_tipodoc_electronico' => $item->id_tipodocumento, 'numero_comprobante' => $item->numero_comprobante, 'tipo_envio_sunat' => $item->modalidad)));

                if($log_documento) {
                    $logs_html = $log_documento->descripcion;
                }
            }

            $lista_destino_docs = DocRelacion::find(array("
                destino_id_contribuyente        = :destino_id_contribuyente: and 
                destino_id_tipodoc_electronico  = :destino_id_tipodoc_electronico: and 
                destino_numero_comprobante      = :destino_numero_comprobante: and 
                destino_tipo_envio_sunat        = :destino_tipo_envio_sunat:
            ", 'bind' => array(
                'destino_id_contribuyente'          => $item->id_contribuyente, 
                'destino_id_tipodoc_electronico'    => $item->id_tipodocumento, 
                'destino_numero_comprobante'        => $item->numero_comprobante, 
                'destino_tipo_envio_sunat'          => $item->modalidad
            )));

            foreach($lista_destino_docs as $destino_doc) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $destino_doc->id_vendedor)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$destino_doc->descripcion.'<br /> 
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($destino_doc->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }
            
            $lista_origen_docs = DocRelacion::find(array("
                origen_id_contribuyente         = :origen_id_contribuyente: and 
                origen_id_tipodoc_electronico   = :origen_id_tipodoc_electronico: and 
                origen_numero_comprobante       = :origen_numero_comprobante: and 
                origen_tipo_envio_sunat         = :origen_tipo_envio_sunat:
            ", 'bind' => array(
                'origen_id_contribuyente'       => $item->id_contribuyente, 
                'origen_id_tipodoc_electronico' => $item->id_tipodocumento, 
                'origen_numero_comprobante'     => $item->numero_comprobante, 
                'origen_tipo_envio_sunat'       => $item->modalidad
            )));

            foreach($lista_origen_docs as $origen_doc) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $origen_doc->id_vendedor)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$origen_doc->descripcion.'<br />
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($origen_doc->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }

            $user_creador_doc = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'idusuario' => $item->id_vendedor)));
            $nombre_user_creador_doc = '';
            if($user_creador_doc) {
                $nombre_user_creador_doc = $user_creador_doc->nombre.' '.$user_creador_doc->apellido." (ID: $user_creador_doc->idusuario)";
            }

            $array_lista[] = array(
                'documento'     => $etiqueta_anulacion.'NOTA VENTA N° '.$item->numero_comprobante.$texto_num_orden_placa.$html_condicion_pago, 
                'fecha_cpe'     => date("d-m-Y / H:i A", strtotime($item->fecha_registro)), 
                'datetime'      => $item->fecha_registro,
                'cliente'       => $cliente->num_doc.'<br />'.$cliente->razon_social, 
                'total'         => $moneda->simbolo.' '.$item->total, 
                'total_monto'   => $item->total,
                'simbolo_moneda'=> $moneda->simbolo,
                'pdf_a4'        => $btn_pdf_a4, 
                'pdf_ticket'    => $btn_pdf_ticket, 
                'opciones'      => $menu_opciones,
                'etiquetas'     => $etiquetas_html,
                'nota'          => $item->nota,
                'etiquetas_activas' => isset($resp_etiquetas['etiquetas_activas'])?$resp_etiquetas['etiquetas_activas']:'',

                'serie_comprobante'     => '',
                'numero_comprobante'    => $item->numero_comprobante,
                'nombre_cpe'            => 'NOTA DE VENTA',
                'url_a4'                => $url_a4,
                'url_ticket'            => $url_ticket,
                'ruta_xml'              => '',
                'cliente_tipo_doc'      => $cliente->id_tipodocidentidad,
                'cliente_num_doc'       => $cliente->num_doc,
                'cliente_nombre'        => $cliente->razon_social,
                'logs_html'             => $logs_html,
                'nombre_user_creador_doc' => $nombre_user_creador_doc
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
        $resp['data'] = $json_data;
        return $resp;
    }

    public function anularDocNoOficialAction() {
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
                $resp['mensaje'] = 'No se encuentra una sessión activa!';
                echo json_encode($resp);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra el contribuyente!';
                echo json_encode($resp);
                exit();
            }

            $resp = $this->anular_doc_no_oficial($datapost, $contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat, $usuario->idusuario);
            echo json_encode($resp);
            exit();
        }
    }

    public function anular_doc_no_oficial($datapost, $id_contribuyente, $tipo_envio_sunat, $idusuario) {
        $array_tipos_validos = array('77', '88');
        $id_tipodocumento = empty($datapost['id_tipodocumento'])?'':$datapost['id_tipodocumento'];
        if(!in_array($id_tipodocumento, $array_tipos_validos)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se reconoce el documento que intenta Eliminar!';
            return $resp;
        }
        
        $numero_comprobante = empty($datapost['numero_comprobante'])?'':intval($datapost['numero_comprobante']);
        $idsucursal = empty($datapost['idsucursal'])?0:intval($datapost['idsucursal']);
        $confirmacion = empty($datapost['confirmacion'])?'no':$datapost['confirmacion'];
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $id_contribuyente)));
        if(!$sucursal) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes seleccionar una sucursal válida';
            return $resp;
        }
        
        $doc_no_oficial = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and id_sucursal = :idsucursal: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat, 'idsucursal' => $idsucursal)));

        if(!$doc_no_oficial) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra el documento que desea anular!';
            return $resp;
        }

        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        $gestion_usuarios = new GestionuserController;

        if($id_tipodocumento == '77') {
            if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_nota_venta')) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permisos para anular una Nota de Venta!';
                return $resp;
            }
        }
        
        if ($id_tipodocumento == '88') {
            if(!$gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_cotizacion')) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted no tiene permisos para anular una Cotización!';
                return $resp;
            }
        }
        
        if($confirmacion != 'si') {
            if($id_tipodocumento == '77') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = '¿Realmente Deseas Anular la Nota de Venta?';
                $resp['mensaje'] = '<strong class="text-danger">Se anulará la Nota de Venta N°: '.$numero_comprobante.'</strong>, y los cambios no se podrán revertir!!';
            } else if ($id_tipodocumento == '88') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = '¿Realmente Deseas Anular la Cotización?';
                $resp['mensaje'] = '<strong class="text-danger">Se anulará la Cotización N°: '.$numero_comprobante.'</strong>, y los cambios no se podrán revertir!!';
            } else {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = '¿Realmente Deseas Anular el Documento?';
                $resp['mensaje'] = '<strong class="text-danger">Se anulará el documento N°: '.$numero_comprobante.'</strong>, y los cambios no se podrán revertir!!';
            }

            return $resp;
        }

        $data_log['id_contribuyente'] = $doc_no_oficial->id_contribuyente;
        $data_log['id_tipodoc_electronico'] = $doc_no_oficial->id_tipodocumento;
        $data_log['serie_comprobante'] = '';
        $data_log['numero_comprobante'] = $doc_no_oficial->numero_comprobante;
        $data_log['tipo_envio_sunat'] = $doc_no_oficial->modalidad;
        $data_log['idusuario'] = $idusuario;
        $data_log['tipo_log'] = 'anulacion';
        
        if($confirmacion == 'si') {
            $log = new LogsController;
            $resp_log = $log->log_documento($data_log);
        }        

        $this->db->begin();

        $doc_no_oficial->estado_documento = 'inactivo';
        try {
            if(!$doc_no_oficial->save()) {
                $msg = '';
                foreach ($doc_no_oficial->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $this->db->rollback();
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

        if($id_tipodocumento == '77') {
            $detalle_nota_venta = DetalleDocnooficial::find(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));

            foreach($detalle_nota_venta as $item) {
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
                            $kardex_guardado = Kardex::findFirst(array("docref_id_contribuyente = :docref_id_contribuyente: and docref_id_tipodoc_electronico = :docref_id_tipodoc_electronico: and docref_numero_comprobante = :docref_numero_comprobante: and docref_tipo_envio_sunat = :docref_tipo_envio_sunat: and idproducto = :idproducto:", 'bind' => array('docref_id_contribuyente' => $id_contribuyente, 'docref_id_tipodoc_electronico' =>  $id_tipodocumento, 'docref_numero_comprobante' => $numero_comprobante, 'docref_tipo_envio_sunat' => $tipo_envio_sunat, 'idproducto' => $item->id_producto), "order" => "id_kardex DESC"));
                            if($kardex_guardado) {
                                $data_kardex['id_contribuyente'] = $doc_no_oficial->id_contribuyente;
                                $data_kardex['idsucursal'] = $doc_no_oficial->id_sucursal;
                                $data_kardex['idproducto'] = $item->id_producto;
                                $data_kardex['idusuario'] = $idusuario;
                                $data_kardex['tipo_envio_sunat'] = $tipo_envio_sunat; //prueba, produccion
                                $data_kardex['tipo_kardex'] = 'anulacion_venta'; //anulacion_venta, inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc, devolucion	
                                $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                                $data_kardex['cantidad_entrada'] = $cantidad_kardex;
                                $data_kardex['costo_unitario'] = $kardex_guardado->costo_unitario_promedio;
            
                                $data_kardex['detalle'] = 'Anulación de Nota de Venta';
                                $data_kardex['fecha_registro'] = date('Y-m-d H:i:s');
                                $data_kardex['docref_id_contribuyente'] = $doc_no_oficial->id_contribuyente;
                                $data_kardex['docref_id_tipodoc_electronico'] = $doc_no_oficial->id_tipodocumento;
                                $data_kardex['docref_numero_comprobante'] = $doc_no_oficial->numero_comprobante;
                                $data_kardex['docref_tipo_envio_sunat'] = $doc_no_oficial->modalidad;
            
                                $kardex = new KardexController;
                                $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                                if($resp_kardex['respuesta'] == 'error') {
                                    $this->db->rollback();
                                    return $resp_kardex;
                                }
                                
                                $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
                            }

                            $producto->stock = $producto->stock + $cantidad_kardex;
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
            }
        }
        
        $this->db->commit();
        $resp['respuesta'] = 'ok';
        $resp['titulo'] = 'Exito';
        $resp['mensaje'] = 'El documento Se ha eliminado correctamente!';
        return $resp;
    }

    public function getCotizacionesAction() {
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

            $resp = $this->get_cotizaciones($datapost, $contribuyente, $usuario);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();
        }
    }

    public function get_cotizaciones($datapost, $contribuyente, $usuario) {
        $herramientas = new HerramientasController;
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                        $datapost['vendedores'] = array($usuario->idusuario);
                    }
                }
            }
        }
        
        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', $datapost['vendedores']);
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', $datapost['sucursales']);
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];
        
        if(!$herramientas->validate_date_time($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Inicio';
            $resp['mensaje'] = 'La fecha de inicio no es válida!';
            return $resp;
        }

        if(!$herramientas->validate_date_time($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Fin';
            $resp['mensaje'] = 'La fecha de fin no es válida!';
            return $resp;
        }

        $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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
        
        $estado_envio_sunat = $contribuyente->tipo_envio_sunat;

        $query_1 = "fecha_registro >= :fecha_inicio: and fecha_registro <= :fecha_fin: and id_contribuyente = :id_contribuyente: and modalidad = :modalidad: and id_tipodocumento = :id_tipodocumento: and estado_documento = 'activo' ";

        if(count($ids_vendedores) > 0){ $query_1 = $query_1." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query_1 = $query_1." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
        
        $comprobantes = DocNoOficial::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $contribuyente->id_contribuyente, 'modalidad' => $contribuyente->tipo_envio_sunat, 'id_tipodocumento' => '88'), "order" => "fecha_registro DESC"));

        //Inicio de configuración Server Side.
        $total_registros = count($comprobantes);

        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => '`de`.`numero_comprobante`',
            1 => '`de`.`fecha_registro`',
            2 => 'ruc_nom_clie',
            3 => '`de`.`total`',
            4 => '`de`.`fecha_registro`',
            5 => '`de`.`fecha_registro`',
            6 => '`de`.`fecha_registro`',
            7 => '`de`.`fecha_registro`',
            8 => '`de`.`fecha_registro`',
            9 => '`de`.`fecha_registro`'
        );

        $query = "SELECT de.*, CONCAT(clie.num_doc, ' ', clie.razon_social) as ruc_nom_clie, clie.celular cliente_celular, clie.razon_social cliente_nombre FROM doc_no_oficial de INNER JOIN cliente clie ON de.idcliente = clie.idcliente INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda WHERE 
        de.fecha_registro >= :fecha_inicio and 
        de.fecha_registro <= :fecha_fin and 
        de.id_contribuyente = :id_contribuyente and 
        de.modalidad = :tipo_envio_sunat and de.estado_documento = 'activo' and 
        de.id_tipodocumento = '88' ";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($datapost['search']['value'])) {
            $query = $query." AND (CONCAT(clie.num_doc, ' ', clie.razon_social) like :termino or de.numero_comprobante like :termino or de.transporte_nro_placa like :termino or de.nro_otr_comprobante like :termino or de.id_codigomoneda like :termino) ";
        }

        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $datapost['search']['value']);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

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
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }

        $array_lista = array();
        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $item->idcliente)));
            $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));

            //id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio
            $string_encrypted_document_a4 = $herramientas->encriptar("$usuario->id_contribuyente||".$item->id_tipodocumento."|| ||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$usuario->id_contribuyente||".$item->id_tipodocumento."|| ||".$item->numero_comprobante."||".$contribuyente->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_a4 = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';

            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';

            $btn_var_whatsapp = "'$item->id_tipodocumento', '', '$item->numero_comprobante', '$dominio_principal', '$url_a4', '$url_ticket', '', '$item->cliente_celular', '$item->cliente_nombre'";
            $btn_envio_whatsapp = '<li><a href="javascript:void(0)" onclick="enviar_mensaje_whatsapp('.$btn_var_whatsapp.')"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 20px;"> Enviar WhatsApp</a></li>';

            //-----INICIO ETIQUETAS-------------------
            $gestiondeetiquetas = new GestiondeetiquetasController;
            $resp_etiquetas = $gestiondeetiquetas->get_etiquetas_documento($item->id_contribuyente, $item->id_tipodocumento, 'C001', $item->numero_comprobante, $contribuyente->tipo_envio_sunat);
            $etiquetas_html = $resp_etiquetas['etiquetas_html'];
            $menu_etiqueta = $resp_etiquetas['menu_etiqueta'];
            //----------FIN ETIQUETAS-------------------

            $menu_opciones = '
            <ul class="icons-list text-center">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                        <li><a href="javascript:void(0)" onclick=\'anular_doc_no_oficial( "'.$item->id_tipodocumento.'", '.$item->numero_comprobante.', '.$item->id_sucursal.')\'><i class="icon-cancel-square2 text-danger"></i> Anular Comprobante</a></li>


                        <li><a href="/facturacionv8/documentoelectronico/index/03/transformar_cotizacion/C/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/boleta.svg" style="width: 20px;"> Crear Boleta</a></li>

                        <li><a href="/facturacionv8/documentoelectronico/index/01/transformar_cotizacion/C/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/factura.svg" style="width: 20px;"> Crear Factura</a></li>

                        <li><a href="/facturacionv8/documentoelectronico/index/77/transformar_cotizacion/C/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_venta.svg" style="width: 20px;"> Crear Nota de Venta</a></li>

                        <li><a href="/facturacionv8/documentoelectronico/index/88/transformar_cotizacion/C/'.$item->numero_comprobante.'"><img src="/facturacionv8/img/nota_venta.svg" style="width: 20px;"> Duplicar Cotización</a></li>
                        
                        <li><a href="/facturacionv8/guiaderemision/index/88/COTI/'.$item->numero_comprobante.'"><i class="icon-make-group"></i> Crear Guía Remisión</a></li>

                        <li><a href="/facturacionv8/documentoelectronico/index/88/editar_cotizacion/C/'.$item->numero_comprobante.'"><i class="icon-pencil"></i> Editar Cotización</a></li>

                        <li><a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodocumento."'".', '."'".""."'".', '.$item->numero_comprobante.', '."'".$cliente->email."'".')"><i class="icon-envelop2 icon-success"></i> Enviar via Email</a></li>

                        '.$btn_envio_whatsapp.$menu_etiqueta.'
                    </ul>
                </li>
            </ul>
            ';
            ///facturacionv8/documentoelectronico/index/01/transformar_cotizacion/C/4
            
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

            if($item->estado_documento != 'activo') {
                $etiqueta_anulacion = '<span class="label bg-danger">ANULADO</span><br/>';
                $html_condicion_pago = '';
            }

            
            $logs_documento = LogDocumento::find(array("
                id_contribuyente        = :id_contribuyente: and 
                id_tipodoc_electronico  = :id_tipodoc_electronico: and 
                numero_comprobante      = :numero_comprobante: and 
                tipo_envio_sunat        = :tipo_envio_sunat:
            ", 'bind' => array(
                'id_contribuyente'          => $item->id_contribuyente, 
                'id_tipodoc_electronico'    => $item->id_tipodocumento, 
                'numero_comprobante'        => $item->numero_comprobante, 
                'tipo_envio_sunat'          => $item->modalidad
            )));

            $logs_html = '';
            foreach($logs_documento as $log_documento) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $log_documento->idusuario)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$log_documento->descripcion.'<br /> 
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($log_documento->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }

            $lista_destino_docs = DocRelacion::find(array("
                destino_id_contribuyente        = :destino_id_contribuyente: and 
                destino_id_tipodoc_electronico  = :destino_id_tipodoc_electronico: and 
                destino_numero_comprobante      = :destino_numero_comprobante: and 
                destino_tipo_envio_sunat        = :destino_tipo_envio_sunat:
            ", 'bind' => array(
                'destino_id_contribuyente'          => $item->id_contribuyente, 
                'destino_id_tipodoc_electronico'    => $item->id_tipodocumento, 
                'destino_numero_comprobante'        => $item->numero_comprobante, 
                'destino_tipo_envio_sunat'          => $item->modalidad
            )));

            foreach($lista_destino_docs as $destino_doc) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $destino_doc->id_vendedor)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$destino_doc->descripcion.'<br /> 
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($destino_doc->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }
            
            $lista_origen_docs = DocRelacion::find(array("
                origen_id_contribuyente         = :origen_id_contribuyente: and 
                origen_id_tipodoc_electronico   = :origen_id_tipodoc_electronico: and 
                origen_numero_comprobante       = :origen_numero_comprobante: and 
                origen_tipo_envio_sunat         = :origen_tipo_envio_sunat:
            ", 'bind' => array(
                'origen_id_contribuyente'       => $item->id_contribuyente, 
                'origen_id_tipodoc_electronico' => $item->id_tipodocumento, 
                'origen_numero_comprobante'     => $item->numero_comprobante, 
                'origen_tipo_envio_sunat'       => $item->modalidad
            )));

            foreach($lista_origen_docs as $origen_doc) {
                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $origen_doc->id_vendedor)));
                $logs_html = $logs_html.'<br />
                <div class="text-muted text-size-small">
                    <i class="icon-checkmark3 text-size-mini position-left"></i> '.$origen_doc->descripcion.'<br />
                    <i class="icon-calendar text-size-mini position-left"></i> '.date("d-m-Y / H:i A", strtotime($origen_doc->fecha_registro)).'<br />
                    <i class="icon-user-check text-size-mini position-left"></i> '.$usuario_vendedor->nombre.' '.$usuario_vendedor->apellido.' (Id. '.$usuario_vendedor->idusuario.')
                </div>';
            }

            $user_creador_doc = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'idusuario' => $item->id_vendedor)));
            $nombre_user_creador_doc = '';
            if($user_creador_doc) {
                $nombre_user_creador_doc = $user_creador_doc->nombre.' '.$user_creador_doc->apellido." (ID: $user_creador_doc->idusuario)";
            }

            $array_lista[] = array(
                'documento'     => 'COTIZACIÓN N° '.$item->numero_comprobante.$texto_num_orden_placa, 
                'fecha_cpe'     => date("d-m-Y / H:i A", strtotime($item->fecha_registro)), 
                'datetime'      => $item->fecha_registro,
                'cliente'       => $cliente->num_doc.'<br />'.$cliente->razon_social, 
                'total'         => $moneda->simbolo.' '.$item->total, 
                'total_monto'   => $item->total,
                'simbolo_moneda'=> $moneda->simbolo,
                'pdf_a4'        => $btn_pdf_a4, 
                'pdf_ticket'    => $btn_pdf_ticket, 
                'opciones'      => $menu_opciones,
                'etiquetas'     => $etiquetas_html,
                'nota'          => $item->nota,
                'etiquetas_activas' => isset($resp_etiquetas['etiquetas_activas'])?$resp_etiquetas['etiquetas_activas']:'',

                'serie_comprobante'     => '',
                'numero_comprobante'    => $item->numero_comprobante,
                'nombre_cpe'            => 'COTIZACIÓN',
                'url_a4'                => $url_a4,
                'url_ticket'            => $url_ticket,
                'ruta_xml'              => '',
                'cliente_tipo_doc'      => $cliente->id_tipodocidentidad,
                'cliente_num_doc'       => $cliente->num_doc,
                'cliente_nombre'        => $cliente->razon_social,
                'logs_html'             => $logs_html,
                'nombre_user_creador_doc' => $nombre_user_creador_doc
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
        $resp['data'] = $json_data;
        return $resp;
    }

    public function getListaGuiasTransportistaAction() {
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

            $resp = $this->get_lista_guias_transportista($datapost, $contribuyente, $usuario);
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }

            echo json_encode($resp['data']);
            exit();
        }
    }

    public function get_lista_guias_transportista($datapost, $contribuyente, $usuario) {
        
        $herramientas = new HerramientasController;
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];

        $gestion_usuarios = new GestionuserController;
        if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'acceso_total') {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') != 'todas_las_sucursales') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'sucursal_asignada') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                    }

                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_reportes', 'opt_verreportes_ventas') == 'propias') {
                        $datapost['sucursales'] = array($usuario->idsucursal);
                        $datapost['vendedores'] = array($usuario->idusuario);
                    }
                }
            }
        }

        $ids_vendedores = empty($datapost['vendedores'])?array():array_map('intval', $datapost['vendedores']);
        $ids_sucursales = empty($datapost['sucursales'])?array():array_map('intval', $datapost['sucursales']);
        $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d 00:00:00"):$datapost['fecha_inicio_valor'];
        $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d H:i:s"):$datapost['fecha_fin_valor'];
        
        if(!$herramientas->validate_date_time($fecha_inicio_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Inicio';
            $resp['mensaje'] = 'La fecha de inicio no es válida!';
            return $resp;
        }

        if(!$herramientas->validate_date_time($fecha_fin_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Fecha Fin';
            $resp['mensaje'] = 'La fecha de fin no es válida!';
            return $resp;
        }

        $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio_valor));
        $fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_fin_valor));
        
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

        $estado_envio_sunat = $contribuyente->tipo_envio_sunat;

        $query_1 = "fecha_registro >= :fecha_inicio: and fecha_registro <= :fecha_fin: and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: and id_tipodoc_electronico  = '31' ";

        if(count($ids_vendedores) > 0){ $query_1 = $query_1." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query_1 = $query_1." and id_sucursal in (".implode(",", $ids_sucursales).") "; }
        
        $comprobantes = GuiaTransportista::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro DESC"));
        
        //Inicio de configuración Server Side.
        $total_registros = count($comprobantes);

        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => '`guia`.`fecha_registro`',
            1 => 'doc_serie_numero',
            2 => '`guia`.`dest_nombre_completo`',
            3 => '`guia`.`total`',
            4 => '`guia`.`fecha_registro`',
            5 => '`guia`.`fecha_registro`',
            6 => '`guia`.`fecha_registro`',
            7 => '`guia`.`fecha_registro`',
            8 => '`guia`.`fecha_registro`'
        );

        $query = "SELECT guia.*, CONCAT(guia.serie_comprobante, '-', guia.numero_comprobante) as doc_serie_numero FROM guia_transportista guia where guia.fecha_registro >= :fecha_inicio and guia.fecha_registro <= :fecha_fin and guia.id_contribuyente = :id_contribuyente and guia.tipo_envio_sunat = :tipo_envio_sunat and guia.id_tipodoc_electronico = '31' ";

        if(count($ids_vendedores) > 0){ $query = $query." and id_vendedor in (".implode(",", $ids_vendedores).") "; }
        if(count($ids_sucursales) > 0){ $query = $query." and id_sucursal in (".implode(",", $ids_sucursales).") "; }

        if(!empty($datapost['search']['value'])) {
            $query = $query." AND (guia.dest_nombre_completo like :termino or CONCAT(guia.serie_comprobante, '-', guia.numero_comprobante) like :termino or guia.transporte_nro_placa like :termino or guia.conductor_nro_documento like :termino or guia.dest_numero_documento like :termino) ";
        }

        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $fecha_inicio, $fecha_fin, $usuario->id_contribuyente, $contribuyente->tipo_envio_sunat, $datapost['search']['value']);

        $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

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
            $resp['titulo'] = 'Error en Sucursal';
            $resp['mensaje'] = $e->getMessage();
            return $resp;
        }

        $array_lista = array();
        $n = 0;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));

            $string_encrypted_document_a4 = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$item->id_contribuyente||".$item->id_tipodoc_electronico."||".$item->serie_comprobante."||".$item->numero_comprobante."||".$item->tipo_envio_sunat."||ticket");

            $url_a4 = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $url_ticket = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

            $onclick_a4 = "var w = window.open('$url_a4','Imprimir_PDF".rand()."'); w.print();";
            $onclick_ticket = "var w = window.open('$url_ticket','Imprimir_PDF".rand()."'); w.print();";
            
            $btn_pdf = '<img onclick="'.$onclick_a4.'" title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;">';
            $btn_pdf_ticket = '<img onclick="'.$onclick_ticket.'" src="/facturacionv8/img/svg/ticket_cpe.svg" title="Formato Ticket" style="width: 30px; cursor: pointer;">';
            
            $ruta_base_cpe = $item->ruta_xml;
            $ruta_xml_zip = $item->ruta_xml.$item->name_xml_zip;
            $ruta_xml_cdr = $item->ruta_xml.$item->name_cdr_zip;

            $ruta_xml = '';
            $btn_xml = '';
            if (file_exists($ruta_xml_zip)) {
                //Existe el XML FIRMADO
                $ruta_xml = "/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cpe_zip";
                $btn_xml = '<a title="Haz Click para Descargar el XML" target="_blank" href="'.$ruta_xml.'"><img src="/facturacionv8/img/svg/xml_cpe.svg" style="width: 30px;"></a>';
            } else {
                $btn_xml = '<a onclick=\'enviar_documento_sunat('.$item->id_contribuyente.', "'.$item->id_tipodoc_electronico.'", "'.$item->serie_comprobante.'", '.$item->numero_comprobante.', "solo_firma")\' title="Haz Click para volver a firmar el XML" href="javascript:void(0)"><img src="/facturacionv8/img/svg/get_cdr.svg" style="width: 30px;"></a>';
            }

            $ruta_cdr = '';
            if(file_exists($ruta_xml_cdr)) {
                $ruta_cdr = "/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cdr_zip";
            }

            $btn_envio_whatsapp = '';
            $btn_var_whatsapp = "'$item->id_tipodoc_electronico', '$item->serie_comprobante', '$item->numero_comprobante', '$dominio_principal', '$url_a4', '$url_ticket', '/facturacionv8/download/downloadcpe/$item->id_contribuyente/$item->id_tipodoc_electronico/$item->serie_comprobante/$item->numero_comprobante/xml_cpe_zip', 'numero_celular', '$item->dest_nombre_completo'";
            $btn_envio_whatsapp = '<li><a href="javascript:void(0)" onclick="enviar_mensaje_whatsapp('.$btn_var_whatsapp.')"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 20px;"> Enviar WhatsApp</a></li>';

            //-----INICIO ETIQUETAS-------------------
            $gestiondeetiquetas = new GestiondeetiquetasController;
            $resp_etiquetas = $gestiondeetiquetas->get_etiquetas_documento($item->id_contribuyente, $item->id_tipodoc_electronico, $item->serie_comprobante, $item->numero_comprobante, $estado_envio_sunat);
            $etiquetas_html = $resp_etiquetas['etiquetas_html'];
            $menu_etiqueta = $resp_etiquetas['menu_etiqueta'];
            //----------FIN ETIQUETAS-------------------

            $menu_opciones = $this->get_options_button($item);
            $btn_cdr = $this->get_cdr_button('ruta_base', $item, $estado_envio_sunat);
            $btn_sunat = $this->get_sunat_button('ruta_base', $item, $estado_envio_sunat, $tipo_comprobante->descripcion);

            $menu_opciones = '
            <ul class="icons-list text-center">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                        '.$menu_opciones.'
                        <li>
                        <a href="javascript:void(0)" onclick="enviar_email_cpe('.$item->id_contribuyente.', '."'".$item->id_tipodoc_electronico."'".', '."'".$item->serie_comprobante."'".', '.$item->numero_comprobante.', '."'".$item->dest_nombre_completo."'".')"><i class="icon-envelop2 icon-success"></i> Enviar al Correo Electrónico</a>
                        </li>
                        '.$btn_envio_whatsapp.$menu_etiqueta.'
                    </ul>
                </li>
            </ul>
            ';
            
            $texto_placa_vehiculo = '';
            if(!empty($item->transporte_nro_placa)) {
                $texto_placa_vehiculo = '<br /><span class="label bg-success-400">Nro. Placa: '.$item->transporte_nro_placa.'</span>';
            }

            $user_creador_doc = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario:", 'bind' => array('id_contribuyente' => $item->id_contribuyente, 'idusuario' => $item->id_vendedor)));
            $nombre_user_creador_doc = '';
            if($user_creador_doc) {
                $nombre_user_creador_doc = $user_creador_doc->nombre.' '.$user_creador_doc->apellido." (ID: $user_creador_doc->idusuario)";
            }

            $n++;
            $array_lista[] = array(
                'fecha_cpe' => date("d-m-Y / H:i A", strtotime($item->fecha_registro)), 
                'datetime'  => $item->fecha_registro,
                'documento' => $tipo_comprobante->descripcion.': '.$item->serie_comprobante.'-'.$item->numero_comprobante.$texto_placa_vehiculo, 
                'destinatario'   => $item->dest_numero_documento.'<br />'.$item->dest_nombre_completo, 
                'peso'      => $item->peso_total, 
                'pdf'       => $btn_pdf.' '.$btn_pdf_ticket, 
                'xml'       => $btn_xml, 
                'cdr'       => $btn_cdr, 
                'sunat'     => $btn_sunat, 
                'opciones'  => $menu_opciones,
                'etiquetas' => $etiquetas_html,
                'nota'      => $item->nota,
                'etiquetas_activas' => isset($resp_etiquetas['etiquetas_activas'])?$resp_etiquetas['etiquetas_activas']:'',


                'serie_comprobante'     => $item->serie_comprobante,
                'numero_comprobante'    => $item->numero_comprobante,
                'nombre_cpe'            => $tipo_comprobante->descripcion,
                'url_a4'                => $url_a4,
                'url_ticket'            => $url_ticket,
                'ruta_xml'              => $ruta_xml,
                'ruta_cdr'              => $ruta_cdr,
                'dest_tipo_documento'   => $item->dest_tipo_documento,
                'dest_numero_documento' => $item->dest_numero_documento,
                'dest_nombre_completo'  => $item->dest_nombre_completo,
                'nombre_user_creador_doc' => $nombre_user_creador_doc
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
        $resp['data'] = $json_data;
        return $resp;
    }
}
?>