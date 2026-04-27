<?php
class CuentasporpagarController extends ControllerBase {
	public function indexAction() {
		$this->setTitle('Cuentas por Pagar');
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

			->addJs($this->baseUri . "public/js/general.js?i=v2")
            ->addJs($this->baseUri . "public/js/cuentasporpagar.js?j=".rand());

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
			
			$id_proveedor = empty($datapost['select_id_proveedor'])?'':intval($datapost['select_id_proveedor']) + 0;
            $fecha_inicio_valor = empty($datapost['fecha_inicio_valor'])?date("Y-m-d"):$datapost['fecha_inicio_valor'];
            $fecha_fin_valor = empty($datapost['fecha_fin_valor'])?date("Y-m-d"):$datapost['fecha_fin_valor'];
            
			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio_valor)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha Movimiento';
				$resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
				echo json_encode($resp);
                exit();
			}
			
			if(!$herramientas->validate_date($fecha_fin_valor)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha Movimiento';
				$resp['mensaje'] = 'El formato de la fecha ingresada para el movimiento no es válido!';
				echo json_encode($resp);
                exit();
            }
            
			$fecha_inicio = date("Y-m-d", strtotime($fecha_inicio_valor));
            $fecha_fin = date("Y-m-d", strtotime($fecha_fin_valor));
            $fecha_actual = date("Y-m-d");
            
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
						echo json_encode($resp);
						exit();
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
            
            if(count($tipos_comprobantes) > 0){ 
                $sql_reporte = $sql_reporte." and id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; 
                $sql_reporte_2 = $sql_reporte_2." and de.id_tipodoc_electronico in ('".implode("','", $tipos_comprobantes)."') "; 
            }

            if($id_proveedor > 0) { 
                $sql_reporte = $sql_reporte." and id_proveedor = ".$id_proveedor." "; 
                $sql_reporte_2 = $sql_reporte_2." and de.id_proveedor = ".$id_proveedor." "; 
            }

            //(monto_adeudado is null or monto_adeudado <= 0)
            $query_1 = " CAST(fecha_registro AS DATE) >= CAST(:fecha_inicio: AS DATE) and CAST(fecha_registro AS DATE) <= CAST(:fecha_fin: AS DATE) and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: ".$sql_reporte;
        
			$comprobantes = Compra::find(array($query_1, 'bind' => array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_contribuyente' => $usuario->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro DESC"));

			//Inicio de configuración Server Side.
            $total_registros = count($comprobantes);
            
			$columnas = array( 
                //indice de la columna en datatable => nombre de la columna
                0 => '`de`.`fecha_registro`',
                1 => 'doc_serie_numero',
                2 => 'ruc_nom_prov',
				3 => '`de`.`total`',
                4 => 'deuda_total',
				5 => '`de`.`fecha_pagopendiente`',
                6 => '`de`.`fecha_registro`'
			);
            
            $query = "SELECT de.*, CONCAT(de.serie_comprobante,'-',de.numero_comprobante) as doc_serie_numero, CONCAT(prov.num_doc, ' ', prov.razon_social) as ruc_nom_prov, cpago.tipo, cpago.condicionpago, (de.total - de.monto_adeudado) as deuda_total FROM compra de INNER JOIN proveedor prov ON de.id_proveedor = prov.id_proveedor INNER JOIN sunat_moneda m on de.id_codigomoneda = m.id_codigomoneda LEFT JOIN condiciondepago cpago on de.id_condicionpago = cpago.id_condicionpago where CAST(de.fecha_registro AS DATE) >= CAST(:fecha_inicio AS DATE) and CAST(de.fecha_registro AS DATE) <= CAST(:fecha_fin AS DATE) and de.id_contribuyente = :id_contribuyente and de.tipo_envio_sunat = :tipo_envio_sunat and de.estado = 'activo' ".$sql_reporte_2;
            
			if(!empty($datapost['search']['value'])) {
                $query = $query." AND (
                    CONCAT(prov.num_doc, ' ', prov.razon_social) like :termino or 
                    CONCAT(de.serie_comprobante,'-',de.numero_comprobante) like :termino or 
                    de.nro_otr_comprobante like :termino or 
                    de.id_codigomoneda like :termino or cpago.tipo like :termino or cpago.condicionpago like :termino
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
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
			
			$array_lista = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $item = (object)$fila;
                
                $proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor:", 'bind' => array('id_proveedor' => $item->id_proveedor)));
                $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $item->id_codigomoneda)));
                $tipo_comprobante = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->id_tipodoc_electronico)));
				if($tipo_comprobante) {
					$nombre_comprobante = $tipo_comprobante->descripcion;
				} else {
					$nombre_comprobante = 'Otr.Doc. ';
				}

                $fecha_de_pago = '';
                $monto_adeudado = 0;
                $class_fecha_pago = 'text-success';
                $dias_restantes_credito = 0;
                $texto_dias_restantes = 'Fecha Límite';
                $menu_crear_abono = '';
                $menu_crear_abono_function = '';
                $menu_lista_abonos = "get_lista_abonos($item->id_compra, $item->id_contribuyente, '$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante)";
                if(!empty($item->monto_adeudado) && floatval($item->monto_adeudado) > 0) {
                    $monto_adeudado = $item->monto_adeudado;
                    $menu_crear_abono_function = "crear_abono_deuda($item->id_compra, $item->id_contribuyente, '$item->id_tipodoc_electronico', '$item->serie_comprobante', $item->numero_comprobante, '".date("Y-m-d", strtotime($item->fecha_pagopendiente))."', 'credito', $item->monto_adeudado, '$item->id_codigomoneda')";
                    $menu_crear_abono = '<li>
                                            <a href="javascript:void(0)" onclick="'.$menu_crear_abono_function.'"><img src="/facturacionv8/public/img/svg/abonos.svg" style="width: 20px;"> Crear Abono</a>
                                        </li>
                                        ';

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
  
                $menu_crear_abono = $menu_crear_abono.'
                                    <li>
                                        <a href="javascript:void(0)" onclick="'.$menu_lista_abonos.'"><img src="/facturacionv8/public/img/svg/ver_abonos.svg" style="width: 20px;"> Ver Abonos</a>
                                    </li>
                ';
                
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

				$array_lista[] = array(
                    'fecha_registro'        => date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
                    'doc_serie_numero'      => $nombre_comprobante.': '.$item->serie_comprobante.'-'.$item->numero_comprobante, 
                    'ruc_nom_prov'             => $proveedor->num_doc.'<br />'.$proveedor->razon_social,
                    'deuda_total'           => $moneda->simbolo.' '.$monto_adeudado,
                    'abonos_total'          => $moneda->simbolo.' '.round($item->total - $monto_adeudado, 2),
                    'fecha_vencimiento'     => '
                    <div class="content-group" style="margin: 0px !important;" style="cursor: pointer;" onclick="'.$menu_crear_abono_function.'">
                        <h7 class="no-margin '.$class_fecha_pago.'"><i class="icon-clipboard3 position-left '.$class_fecha_pago.'"></i> '.$fecha_de_pago.'</h7><br />
                        <span class="text-muted text-size-small">'.$texto_dias_restantes.'</span>
                    </div>
                    ',
                    'menu'                  => $menu
                );
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
			
            $id_compra = empty($datapost['id_compra'])?0:intval($datapost['id_compra']);
            
            $documento = Compra::findFirst(array("id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat: and id_compra = :id_compra:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'id_compra' => $id_compra)));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No encontramos el documento electrónico';
                echo json_encode($resp);
                exit();
            }

            $query = "SELECT * FROM `monto_pagado` where id_compra = :id_compra and id_contribuyente = :id_contribuyente and tipo_envio_sunat = :tipo_envio_sunat and estado = 'activo'";   

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':id_contribuyente', $contribuyente->id_contribuyente, PDO::PARAM_INT);
                $sentencia->bindParam(':id_compra', $id_compra, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: (getListaAbonos)',  $e->getMessage(), "\n";
                exit();
            }

            $lista = array();
            while($fila = $sentencia->fetch()) {
                $item = (object)$fila;
                if($item->id_codigomoneda == 'PEN') {
                    $simbolo_moneda = 'S/. ';
                } else {
                    $simbolo_moneda = 'USD ';
                }
                $html_nota = '';
                if(!empty($item->detalle)) {
                    $html_nota = '<img data-popup="popover" data-placement="top" title="Detalle" data-content="'.htmlspecialchars($item->detalle).'" src="/facturacionv8/img/svg/note2_fy.svg" style="width: 30px; cursor: pointer;" />';
                }

                $condiciondepago = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago:", "bind" => array('id_condicionpago' => $item->id_condicionpago)));
                $condicion_pago_html = '';
                if($condiciondepago) {
                    if($condiciondepago->tipo == 'credito') {
                        $condicion_pago_html = '<br /><span class="label bg-danger">Crédito</span>';
                    } else if($condiciondepago->tipo == 'tarjeta_credito') {
                        $condicion_pago_html = '<br /><span class="label bg-info">Tarjeta Crédito/Débito</span>';
                    } else if($condiciondepago->tipo == 'transferencia') {
                        $condicion_pago_html = '<br /><span class="label bg-primary">Transferencia</span>';
                    } else {
                        //$condicion_pago_html = '<br /><span class="label bg-success">Contado</span>';
                    }
                }

                $menu = '
                    <ul class="icons-list text-center">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="width: 260px;">
                                <li>
                                    <a style="display:none;" href="/facturacionv8/download/ticket_abono/'.$item->id_montopagado.'" target="_blank"><i class="icon-ticket"></i> Imprimir Abono</a>
                                    <a style="display:none;" href="javascript(0)" onclick="eliminar_abono('.$item->id_montopagado.')"><i class="icon-minus-circle2"></i> Eliminar</a>
                                </li>
                            </ul>
                        </li>
                    </ul>';

                $lista[] = array(
                    date("d-m-Y / H:i A", strtotime($item->fecha_registro)),
                    $simbolo_moneda.$item->total.$html_nota.$condicion_pago_html,
                    $menu
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
                $html_nota_lista = 'El monto total del documento electrónico es de '.$simbolo_moneda_doc.' '.$documento->total.', y se han registrado abonos por un total de '.$simbolo_moneda_doc.' '.($documento->total - $monto_adeudado).'. Aún queda una deuda de '.$simbolo_moneda_doc.' '.$monto_adeudado.'.';
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

	public function crearAbonoPagoAction() {
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

			$id_compra = empty($datapost['id_compra'])?0:intval($datapost['id_compra']);
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
            
            $documento = Compra::findFirst(array("id_compra = :id_compra: and id_contribuyente = :id_contribuyente: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'id_compra' => $id_compra)));

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
                echo json_encode($resp);
                exit();
            }

            $monto_adeudado = $documento->monto_adeudado;
            if($monto_adeudado <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Actualmente no existe una deuda pendiente para este documento!';
                echo json_encode($resp);
                exit();
            }

            $simbolo_moneda = 'S/ ';
            if($documento->id_codigomoneda == 'USD') {
                $simbolo_moneda = 'USD $ ';
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
                $array_fecha_prox_pago = explode('/',!isset($fecha_proximo_pago)?date('d/m/Y'):$fecha_proximo_pago);
                $fecha_prox_pago = $array_fecha_prox_pago[2].'-'.$array_fecha_prox_pago[1].'-'.$array_fecha_prox_pago[0];
                if (!(DateTime::createFromFormat('Y-m-d', $fecha_prox_pago) !== FALSE)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'La Fecha Seleccionada para el próximo pago no es válida!';
                    echo json_encode($resp);
                    exit();
                }

                $herramientas = new HerramientasController;
                $resp_fecha_2 = $herramientas->comparar_fechas($fecha_prox_pago, date('Y-m-d'));
				if($resp_fecha_2['diferencia_primera_segunda'] <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La fecha de pago del monto adeudado debe ser mayor a la fecha actual, por favor verifique!';
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
            
            $this->db->begin();

            $monto_abono = 0;
            if(($monto_adeudado - $monto_a_pagar) < 0) { //mostrar vuelto
                $documento->monto_adeudado = 0;
                //$documento->id_condicionpago = null;
                $documento->log_condicion_pago = $log_condicion_pago;
                $monto_abono = $monto_adeudado;
            } else if (($monto_adeudado - $monto_a_pagar) == 0) {
                $documento->monto_adeudado = 0;
                //$documento->id_condicionpago = null;
                $documento->log_condicion_pago = $log_condicion_pago;
                $monto_abono = $monto_adeudado;
            } else if (($monto_adeudado - $monto_a_pagar) > 0) { //seleccionar nueva fecha
                $documento->monto_adeudado = round($monto_adeudado - $monto_a_pagar, 2);
                $documento->log_condicion_pago = $log_condicion_pago;
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
                $resp['mensaje'] = 'Error al guardar los datos: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $idcuenta_banco_deposito = null;
		    $fecha_deposito_transferencia = null;
            if($monto_abono > 0) {
                $monto_pagado = new MontoPagado();

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
                
                $monto_pagado->id_compra = $id_compra;
                $monto_pagado->id_contribuyente = $id_contribuyente;
                $monto_pagado->id_tipodoc_electronico = $id_tipodoc_electronico;
                $monto_pagado->serie_comprobante = $serie_comprobante;
                $monto_pagado->numero_comprobante = $numero_comprobante;
                $monto_pagado->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
                $monto_pagado->id_usuario = $usuario->idusuario;
                $monto_pagado->id_sucursal = $documento->idsucursal;
                $monto_pagado->id_codigomoneda = $documento->id_codigomoneda;
                $monto_pagado->detalle = $txt_observacion_abono;
                $monto_pagado->fechadeposito = $fecha_deposito_transferencia;
                $monto_pagado->idbanco = $idcuenta_banco_deposito;

                if($id_condicionpago > 0) {
                    $monto_pagado->id_condicionpago = $id_condicionpago;
                    $monto_pagado->cpago_nrooperacion = $numero_operacion;
                } else {
                    $monto_pagado->id_condicionpago = null;
                    $monto_pagado->cpago_nrooperacion = null;
                }
                $monto_pagado->fecha_registro = date("Y-m-d H:i:s");
                $monto_pagado->estado = 'activo';
                $monto_pagado->total = $monto_abono;

                try {
                    if(!$monto_pagado->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($monto_pagado->getMessages() as $message) {
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
                    $resp['mensaje'] = 'Error al guardar los datos: '.$e->getMessage();
                    echo json_encode($resp);
                    exit();
                }
            }

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'Hemos guardado correctamente los datos!';
            $resp['id_montocobrado'] = ($monto_pagado)?$monto_pagado->id_montopagado:0;
            echo json_encode($resp);
            exit();
        }
    }
}