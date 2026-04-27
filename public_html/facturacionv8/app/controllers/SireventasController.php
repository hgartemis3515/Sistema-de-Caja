<?php
class SireventasController extends ControllerBase
{
	public function sireVentasConsultarYDescargarPropuestaAction() {
		$this->view->disable();
		$request = $this->request;
		if($request->isAjax() == true) 
		{
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

			$datapost = $this->request->getPost();

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se pudo obtener el contribuyente!';
				echo json_encode($resp);
				exit();
			}

			$num_ticket = !isset($datapost['num_ticket'])?'':intval($datapost['num_ticket']);
			if($num_ticket == '') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar un número de ticket!';
				echo json_encode($resp);
				exit();
			}

			$sire_rvie = SireRvie::findFirst(array("id_contribuyente = :id_contribuyente: AND num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'num_ticket' => $num_ticket)));
			if(!$sire_rvie) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El número de ticket no es válido!';
				echo json_encode($resp);
				exit();
			}

			$data_api = array(
				"token" 				=> $this->token_user,
				"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
				"accion" 				=> "consultar_estado_ticket_propuesta_ventas",
				"data_propuesta" 		=> array(
					"perIni" 		=> $sire_rvie->periodo_mes,
					"perFin" 		=> $sire_rvie->periodo_mes,
					"numTicket" 	=> $num_ticket,
					"page" 			=> 1,
					"perPage" 		=> 5000
				)
			);

			$url = "https://facturalahoy.com/api/apisire/propuesta_ventas";
			$resp_sire = $this->conexion_sire($url, $data_api);
			if($resp_sire->respuesta == 'error') {
				echo json_encode($resp_sire);
				exit();
			}

			$registros_sire = $resp_sire->estado_ticket->registros;
			$resp_descargar_propuesta_rvie = array();
			foreach($registros_sire as $registro) {
				if(!empty($registro->archivoReporte)) {
					$archivos_reporte = $registro->archivoReporte;
					$num_ticket_descarga = $registro->numTicket;
					$periodo_consulta = $registro->perTributario;
					$codProceso = $registro->codProceso;
					foreach($archivos_reporte as $reporte) {
						$data_api_descarga = array(
							"token" 				=> $this->token_user,
							"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
							"accion" 				=> "descargar_archivo_propuesta_ventas",
							"data_propuesta" 		=> array(
								"codTipoArchivoReporte"		=> isset($reporte->codTipoAchivoReporte) ? $reporte->codTipoAchivoReporte : (isset($reporte->codTipoArchivoReporte) ? $reporte->codTipoArchivoReporte : null),
								"nomArchivoReporte" 		=> $reporte->nomArchivoReporte,
								"codLibro" 					=> '140000',
								"nom_archivo_txt" 			=> $reporte->nomArchivoContenido,
								"periodo"					=> $periodo_consulta,
								"codProceso"				=> $codProceso,
								"data_consulta_ticket"	    => $registro,
								"numTicket"					=> $num_ticket_descarga
							)
						);
	
						$resp_descargar_propuesta_rvie[] = $this->descargar_propuesta_rvie($contribuyente, $sire_rvie, $data_api_descarga);
					}
				} else {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Ticket Aún Está en Proceso, Consultar más tarde!';
					echo json_encode($resp);
					exit();
				}
			}

			$mensaje = 'Operación correcta!';
			foreach($resp_descargar_propuesta_rvie as $resp_descarga) {
				if($resp_descarga->respuesta == 'error') {
					$mensaje = 'Uno de los archivos en la propuesta no se pudo descargar! '.$resp_descarga->mensaje;
					break;
				}
			}
			
			$resp_get_registros = $this->extraer_registros_rvie_bd($sire_rvie);
			if($resp_get_registros['respuesta'] == 'error') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $resp_get_registros['mensaje'];
				echo json_encode($resp);
				exit();
			}
			
			echo json_encode($resp_get_registros);
			exit();
		}
	}

	public function buscar_item_en_rvie($sire_rvie, $item_doc_electronico) {
		$id_contribuyente = $sire_rvie->id_contribuyente;
		$tipo_envio_sunat = $sire_rvie->tipo_envio_sunat;
		$periodo_anio = $sire_rvie->periodo_anio;
		$periodo_mes = $sire_rvie->periodo_mes;
		$num_ticket = $sire_rvie->num_ticket;
		$existe_sunat = '';

		$id_tipodoc_electronico = $item_doc_electronico->id_tipodoc_electronico;
		$serie_comprobante = $item_doc_electronico->serie_comprobante;
		$numero_comprobante = $item_doc_electronico->numero_comprobante;

		$sire_rvie_item = SireRvieItem::findFirst(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: AND num_ticket = :num_ticket: and tipo_cp_doc = :tipo_cp_doc: and serie_cdp = :serie_cdp: and nro_cp_doc_inicial = :nro_cp_doc_inicial:", 
		'bind' => array(
			'id_contribuyente' => $id_contribuyente, 
			'tipo_envio_sunat' => $tipo_envio_sunat, 
			'periodo_anio' => $periodo_anio, 
			'periodo_mes' => $periodo_mes, 
			'num_ticket' => $num_ticket,
			'tipo_cp_doc' => $id_tipodoc_electronico,
			'serie_cdp' => $serie_comprobante,
			'nro_cp_doc_inicial' => $numero_comprobante
		)));
		
		if(!$sire_rvie_item) {
			$resp['existe_sunat'] = 'no';
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encontró el registro en la base de datos!';
			$resp['item_doc_electronico'] = $item_doc_electronico;
			return $resp;
		}

		$existe_sunat = 'si';
		$montos_iguales = 'si';
		if($item_doc_electronico->total != $sire_rvie_item->total_cp) {
			$estado_revision = 'existe_sunat_pero_no_coincide_total';
			$montos_iguales = 'no';
		}

		$tiene_mismo_estado = 'si';
		if($item_doc_electronico->estado_comprobante == 'aceptado' && $sire_rvie_item->est_comp == 1) {
			$tiene_mismo_estado = 'si';
		} else {
			$tiene_mismo_estado = 'no';
		}

		$resp['respuesta'] = 'ok';
		$resp['montos_iguales'] = $montos_iguales;
		$resp['tiene_mismo_estado'] = $tiene_mismo_estado;
		$resp['item_sire'] = $sire_rvie_item;
		$resp['existe_sunat'] = 'si';
		return $resp;
	}
	
	public function downloadTxtRvieAction($num_ticket = '') {
		
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
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se pudo obtener el contribuyente!';
			echo json_encode($resp);
			exit();
		}

		$num_ticket = $_GET['num_ticket'];
		
		if($num_ticket == '') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes ingresar un número de ticket!';
			echo json_encode($resp);
			exit();
		}

		$sire_rvie = SireRvie::findFirst(array("id_contribuyente = :id_contribuyente: AND num_ticket = :num_ticket: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'num_ticket' => $num_ticket, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		if(!$sire_rvie) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El número de ticket no es válido!';
			echo json_encode($resp);
			exit();
		}

		$resp_get_registros = $this->extraer_registros_rvie_bd($sire_rvie);
		if($resp_get_registros['respuesta'] == 'error') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $resp_get_registros['mensaje'];
			echo json_encode($resp);
			exit();
		}

		$filename = "LE".$contribuyente->ruc.$sire_rvie->periodo_mes.'00'.'140400'.'02'.'1'.'1'.'1'.'2'.'.txt';
		
		header("Content-Type: text/plain");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		foreach($resp_get_registros['registros'] as $item) {
			$item = (object)$item;
			$item_txt_propuesta_sire = array(
				$contribuyente->ruc,
				$contribuyente->razon_social,
				$sire_rvie->periodo_mes,
				$item->car_sunat,
				$item->fecha_emision,
				$item->fecha_vcto_pago,
				$item->tipo_cp_doc,
				$item->serie_cdp,
				$item->nro_cp_doc_inicial,
				$item->nro_cp_doc_final,
				$item->tipo_doc_identidad,
				$item->nro_doc_identidad,
				$item->apellidos_nombres_razon_social,
				$item->valor_facturado_exportacion,
				$item->bi_gravada,
				$item->dscto_bi,
				$item->igv_ipm,
				$item->dscto_igv_ipm,
				$item->mto_exonerado,
				$item->mto_inafecto,
				$item->isc,
				$item->bi_grav_ivap,
				$item->ivap,
				$item->icbper,
				$item->otros_tributos,
				$item->total_cp,
				$item->moneda,
				$item->tipo_cambio,
				$item->fecha_emision_doc_modificado,
				$item->tipo_cp_modificado,
				$item->serie_cp_modificado,
				$item->nro_cp_modificado,
				$item->id_proyecto_operadores_atribucion,
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				''
			);

			echo implode("|",$item_txt_propuesta_sire)."\r\n";
		}

		exit();
	}

	public function txtReemplazarPropuestaRvieAction($num_ticket = '') {
		
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
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se pudo obtener el contribuyente!';
			echo json_encode($resp);
			exit();
		}

		$num_ticket = $_GET['num_ticket'];
		
		if($num_ticket == '') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes ingresar un número de ticket!';
			echo json_encode($resp);
			exit();
		}

		$sire_rvie = SireRvie::findFirst(array("id_contribuyente = :id_contribuyente: AND num_ticket = :num_ticket: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'num_ticket' => $num_ticket, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		if(!$sire_rvie) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El número de ticket no es válido!';
			echo json_encode($resp);
			exit();
		}

		$resp_get_registros = $this->extraer_registros_rvie_bd($sire_rvie);
		if($resp_get_registros['respuesta'] == 'error') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $resp_get_registros['mensaje'];
			echo json_encode($resp);
			exit();
		}

		//$filename = "LE".$contribuyente->ruc.$sire_rvie->periodo_mes.'00'.'140400'.'02'.'OIM2'.'.txt';
		$filename = "LE".$contribuyente->ruc.$sire_rvie->periodo_mes."00"."140400021112.txt";
		
		header("Content-Type: text/plain");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		foreach($resp_get_registros['registros'] as $item) {
			$item = (object)$item;

			$nro_cp_doc_final = ($item->tipo_cp_doc == '03') ? $item->nro_cp_doc_final : '';

			$item_txt_propuesta_sire = array(
				$contribuyente->ruc, //1
				$contribuyente->razon_social, //2
				$sire_rvie->periodo_mes, //3
				'', //$item->car_sunat, //si se reemplaza la propuesta debe ir vacío //4
				$item->fecha_emision, //5
				$item->fecha_vcto_pago, //6
				$item->tipo_cp_doc, //7
				$item->serie_cdp, //8
				$item->nro_cp_doc_inicial, //9
				$nro_cp_doc_final, //10
				$item->tipo_doc_identidad, //11
				$item->nro_doc_identidad, //12
				$item->apellidos_nombres_razon_social, //13
				$item->valor_facturado_exportacion, //14
				$item->bi_gravada, //15
				$item->dscto_bi, //16
				$item->igv_ipm, //17
				$item->dscto_igv_ipm, //18
				$item->mto_exonerado, //19
				$item->mto_inafecto, //20
				$item->isc, //21
				$item->bi_grav_ivap, //22
				$item->ivap, //23
				$item->icbper, //24
				$item->otros_tributos, //25
				$item->total_cp,	//26
				$item->moneda, //27
				(floatval($item->tipo_cambio) <= 1)?'':$item->tipo_cambio, 		//28
				$item->fecha_emision_doc_modificado, //29
				$item->tipo_cp_modificado, //30
				($item->serie_cp_modificado == '-' || $item->serie_cp_modificado == '')?'':$item->serie_cp_modificado, //31
				($item->nro_cp_modificado == '0' || intval($item->nro_cp_modificado) <= 0)?'':$item->nro_cp_modificado, //32
				$item->id_proyecto_operadores_atribucion, //33
				'',
				'',
				'',
				'',
				''
			);

			echo implode("|",$item_txt_propuesta_sire)."\r\n";
		}

		exit();
	}
	
	public function extraer_registros_rvie_bd($sire_rvie) {
		$id_contribuyente = $sire_rvie->id_contribuyente;
		$tipo_envio_sunat = $sire_rvie->tipo_envio_sunat;
		$periodo_anio = $sire_rvie->periodo_anio;
		$periodo_mes = $sire_rvie->periodo_mes;
		$num_ticket = $sire_rvie->num_ticket;

		$herramientas = new HerramientasController;
		$anio_libro_e_ventas = $sire_rvie->periodo_anio;
		$mes_libro_e_ventas = substr($sire_rvie->periodo_mes, 4); 
		$fecha_inicio = $herramientas->obtener_fecha_mes($mes_libro_e_ventas, $anio_libro_e_ventas)['fecha_inicio'];
		$fecha_fin = $herramientas->obtener_fecha_mes($mes_libro_e_ventas, $anio_libro_e_ventas)['fecha_fin'];
		$reportes = new ReportesController;
		$libro_e_ventas = $reportes->detalledocumentosemitidos($fecha_inicio, $fecha_fin, $tipo_envio_sunat, $id_contribuyente, 0, '-1');
		
		$registros = array();
		$total_cp = 0;
		$total_mto_exonerado = 0;
		$total_mto_inafecto = 0;
		$total_igv_ipm = 0;
		$total_dscto_igv_ipm = 0;
		$total_bi_gravada = 0;
		$total_dscto_bi = 0;
		$total_isc = 0;
		$total_bi_grav_ivap = 0;
		$total_ivap = 0;
		$total_icbper = 0;
		$total_otros_tributos = 0;
		$total_valor_facturado_exportacion = 0;
		$total_valor_fob_embarcado = 0;
		$total_valor_op_gratuitas = 0;

		$total_facturas = 0;
		$total_boletas = 0;
		$total_notas_credito = 0;
		$total_notas_debito = 0;

		$estados_en_libro_e_ventas = array();
		$totales_en_libro_e_ventas = array();
		foreach($libro_e_ventas as $item_libro_e_ventas) {
			$item_libro_e_ventas = (object)$item_libro_e_ventas;

			$estados_en_libro_e_ventas[$item_libro_e_ventas->id_tipodoc_electronico.'-'.$item_libro_e_ventas->serie_comprobante.'-'.$item_libro_e_ventas->numero_comprobante] = $item_libro_e_ventas->estado_sire;
			$totales_en_libro_e_ventas[$item_libro_e_ventas->id_tipodoc_electronico.'-'.$item_libro_e_ventas->serie_comprobante.'-'.$item_libro_e_ventas->numero_comprobante] = $item_libro_e_ventas->total;

			$resp_busqueda = $this->buscar_item_en_rvie($sire_rvie, $item_libro_e_ventas);
			if($resp_busqueda['respuesta'] == 'error') {
				$total_cp = $total_cp + $item_libro_e_ventas->total;
				$total_mto_exonerado = $total_mto_exonerado + $item_libro_e_ventas->total_exoneradas;
				$total_mto_inafecto = $total_mto_inafecto + $item_libro_e_ventas->total_inafecta;
				$total_igv_ipm = $total_igv_ipm + $item_libro_e_ventas->total_igv;
				$total_dscto_igv_ipm = $total_dscto_igv_ipm + $item_libro_e_ventas->descuento_igv;
				$total_bi_gravada = $total_bi_gravada + $item_libro_e_ventas->total_gravadas;
				$total_dscto_bi = $total_dscto_bi + $item_libro_e_ventas->descuento;
				$total_isc = $total_isc + $item_libro_e_ventas->total_isc;
				$total_bi_grav_ivap = $total_bi_grav_ivap + 0;
				$total_ivap = $total_ivap + 0;
				$total_icbper = $total_icbper + $item_libro_e_ventas->icbper;
				$total_otros_tributos = $total_otros_tributos + $item_libro_e_ventas->otros_tributos;
				$total_valor_facturado_exportacion = $total_valor_facturado_exportacion + $item_libro_e_ventas->total_exportacion;
				$total_valor_fob_embarcado = $total_valor_fob_embarcado + 0;
				$total_valor_op_gratuitas = $total_valor_op_gratuitas + $item_libro_e_ventas->total_gratuitas;

				if($item_libro_e_ventas->id_tipodoc_electronico == '01') {
					$total_facturas = $total_facturas + $item_libro_e_ventas->total;
				} else if($item_libro_e_ventas->id_tipodoc_electronico == '03') {
					$total_boletas = $total_boletas + $item_libro_e_ventas->total;
				} else if($item_libro_e_ventas->id_tipodoc_electronico == '07') {
					$total_notas_credito = $total_notas_credito + $item_libro_e_ventas->total;
				} else if($item_libro_e_ventas->id_tipodoc_electronico == '08') {
					$total_notas_debito = $total_notas_debito + $item_libro_e_ventas->total;
				}
				
				$analisis_html = '
				<img src="/facturacionv8/img/no_existe_sunat.png" style="width: 25px;"/>
				';
				
				//implica que no existe el item, debemos agregarlo
				$registros[] = array(
					'analisis_ntml'						=> $analisis_html,
					'fecha_emision' 					=> $item_libro_e_ventas->fecha_comprobante,
					'fecha_vcto_pago' 					=> $item_libro_e_ventas->fecha_vencimiento,
					'tipo_cp_doc' 						=> $item_libro_e_ventas->id_tipodoc_electronico,
					'serie_cdp' 						=> $item_libro_e_ventas->serie_comprobante,
					'nro_cp_doc_inicial' 				=> $item_libro_e_ventas->numero_comprobante,
					'nro_cp_doc_final' 					=> $item_libro_e_ventas->numero_comprobante,

					'tipo_doc_identidad' 				=> $item_libro_e_ventas->cliente_id_tipodocidentidad,
					'nro_doc_identidad' 				=> $item_libro_e_ventas->cliente_num_doc_identidad,
					'apellidos_nombres_razon_social' 	=> $item_libro_e_ventas->cliente_razon_social,


					'valor_facturado_exportacion' 		=> $item_libro_e_ventas->total_exportacion,
					'bi_gravada' 						=> $item_libro_e_ventas->total_gravadas,
					'dscto_bi' 							=> $item_libro_e_ventas->descuento,
					'igv_ipm' 							=> $item_libro_e_ventas->total_igv,
					'dscto_igv_ipm' 					=> $item_libro_e_ventas->descuento_igv,
					'mto_exonerado' 					=> $item_libro_e_ventas->total_exoneradas,
					'mto_inafecto' 						=> $item_libro_e_ventas->total_inafecta,
					'isc' 								=> $item_libro_e_ventas->total_isc,
					'bi_grav_ivap' 						=> 0,
					'ivap' 								=> 0,
					'icbper' 							=> $item_libro_e_ventas->icbper,
					'otros_tributos' 					=> $item_libro_e_ventas->otros_tributos,
					'total_cp' 							=> $item_libro_e_ventas->total,
					'moneda' 							=> $item_libro_e_ventas->moneda,
					'tipo_cambio' 						=> $item_libro_e_ventas->tipo_cambio,

					'fecha_emision_doc_modificado' 		=> $item_libro_e_ventas->fecha_comp_modif,
					'tipo_cp_modificado' 				=> $item_libro_e_ventas->tipo_comp_modif,
					'serie_cp_modificado' 				=> $item_libro_e_ventas->serie_comp_modif,
					'nro_cp_modificado' 				=> $item_libro_e_ventas->numero_comp_modif,

					'id_proyecto_operadores_atribucion' => '',
					'tipo_de_nota' 						=> $item_libro_e_ventas->id_motivo_nota,
					'est_comp' 							=> $item_libro_e_ventas->estado_comprobante,
					'valor_fob_embarcado' 				=> '',
					'valor_op_gratuitas' 				=> $item_libro_e_ventas->total_gratuitas,
					'tipo_operacion' 					=> $item_libro_e_ventas->tipo_operacion,
					'dam_cp' 							=> '',
					'clu' 								=> '',
					'car_sunat' 						=> $item_libro_e_ventas->car_sunat,
					'existe_en_sire'					=> 'no',
					'coincide_estado'					=> 'no',
					'coincide_total'					=> 'no',
					'existe_sunat'						=> 'no'
				);
			}
		}
		
		$sire_rvie_items = SireRvieItem::find(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: AND num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodo_mes, 'num_ticket' => $num_ticket)));
		
		foreach($sire_rvie_items as $item) {
			
			$total_cp = $total_cp + $item->total_cp;
			$total_mto_exonerado = $total_mto_exonerado + $item->mto_exonerado;
			$total_mto_inafecto = $total_mto_inafecto + $item->mto_inafecto;
			$total_igv_ipm = $total_igv_ipm + $item->igv_ipm;
			$total_dscto_igv_ipm = $total_dscto_igv_ipm + $item->dscto_igv_ipm;
			$total_bi_gravada = $total_bi_gravada + $item->bi_gravada;
			$total_dscto_bi = $total_dscto_bi + $item->dscto_bi;
			$total_isc = $total_isc + $item->isc;
			$total_bi_grav_ivap = $total_bi_grav_ivap + $item->bi_grav_ivap;
			$total_ivap = $total_ivap + $item->ivap;
			$total_icbper = $total_icbper + $item->icbper;
			$total_otros_tributos = $total_otros_tributos + $item->otros_tributos;
			$total_valor_facturado_exportacion = $total_valor_facturado_exportacion + $item->valor_facturado_exportacion;
			$total_valor_fob_embarcado = $total_valor_fob_embarcado + $item->valor_fob_embarcado;
			$total_valor_op_gratuitas = $total_valor_op_gratuitas + $item->valor_op_gratuitas;

			if($item->tipo_cp_doc == '01') {
				$total_facturas = $total_facturas + $item->total_cp;
			} else if($item->tipo_cp_doc == '03') {
				$total_boletas = $total_boletas + $item->total_cp;
			} else if($item->tipo_cp_doc == '07') {
				$total_notas_credito = $total_notas_credito + $item->total_cp;
			} else if($item->tipo_cp_doc == '08') {
				$total_notas_debito = $total_notas_debito + $item->total_cp;
			}

			$coincide_estado = 'si';
			if(isset($estados_en_libro_e_ventas[$item->tipo_cp_doc.'-'.$item->serie_cdp.'-'.$item->nro_cp_doc_inicial])) {
				if($estados_en_libro_e_ventas[$item->tipo_cp_doc.'-'.$item->serie_cdp.'-'.$item->nro_cp_doc_inicial] == $item->est_comp) {
					$coincide_estado = 'si';
				} else {
					$coincide_estado = $estados_en_libro_e_ventas[$item->tipo_cp_doc.'-'.$item->serie_cdp.'-'.$item->nro_cp_doc_inicial];
				}
			}
				
			$coincide_total = 'si';
			if(isset($totales_en_libro_e_ventas[$item->tipo_cp_doc.'-'.$item->serie_cdp.'-'.$item->nro_cp_doc_inicial])) {
				if($totales_en_libro_e_ventas[$item->tipo_cp_doc.'-'.$item->serie_cdp.'-'.$item->nro_cp_doc_inicial] == $item->total_cp) {
					$coincide_total = 'si';
				} else {
					$coincide_total = 'no';
				}
			}

			
			if($coincide_estado == 'si' && $coincide_total == 'si') {
				$analisis_html = '<img src="/facturacionv8/img/check_sire.png" style="width: 25px;"/>';
			} else if($coincide_estado == 'si' && $coincide_total == 'no') {
				$analisis_html = '<img src="/facturacionv8/img/no_coincide_monto.png" style="width: 25px;"/>';
			} else if($coincide_estado == 'no' && $coincide_total == 'si') {
				$analisis_html = '<img src="/facturacionv8/img/no_coincide_status.png" style="width: 25px;"/>';
			} else if($coincide_estado == 'no' && $coincide_total == 'no') {
				$analisis_html = '
				<img src="/facturacionv8/img/no_coincide_status.png" style="width: 25px;"/>
				<img src="/facturacionv8/img/no_coincide_monto.png" style="width: 25px;"/>
				';
			}

			$registros[] = array(
				'analisis_ntml'						=> $analisis_html,
				'fecha_emision' 					=> $this->formatearFechaDMY($item->fecha_emision),
				'fecha_vcto_pago' 					=> $this->formatearFechaDMY($item->fecha_vcto_pago),
				'tipo_cp_doc' 						=> $item->tipo_cp_doc,
				'serie_cdp' 						=> $item->serie_cdp,
				'nro_cp_doc_inicial' 				=> $item->nro_cp_doc_inicial,
				'nro_cp_doc_final' 					=> $item->nro_cp_doc_final,
				'tipo_doc_identidad' 				=> $item->tipo_doc_identidad,
				'nro_doc_identidad' 				=> $item->nro_doc_identidad,
				'apellidos_nombres_razon_social' 	=> $item->apellidos_nombres_razon_social,
				'valor_facturado_exportacion' 		=> $item->valor_facturado_exportacion,
				'bi_gravada' 						=> $item->bi_gravada,
				'dscto_bi' 							=> $item->dscto_bi,
				'igv_ipm' 							=> $item->igv_ipm,
				'dscto_igv_ipm' 					=> $item->dscto_igv_ipm,
				'mto_exonerado' 					=> $item->mto_exonerado,
				'mto_inafecto' 						=> $item->mto_inafecto,
				'isc' 								=> $item->isc,
				'bi_grav_ivap' 						=> $item->bi_grav_ivap,
				'ivap' 								=> $item->ivap,
				'icbper' 							=> $item->icbper,
				'otros_tributos' 					=> $item->otros_tributos,
				'total_cp' 							=> $item->total_cp,
				'moneda' 							=> $item->moneda,
				'tipo_cambio' 						=> $item->tipo_cambio,
				'fecha_emision_doc_modificado' 		=> $this->formatearFechaDMY($item->fecha_emision_doc_modificado),
				'tipo_cp_modificado' 				=> $item->tipo_cp_modificado,
				'serie_cp_modificado' 				=> $item->serie_cp_modificado,
				'nro_cp_modificado' 				=> $item->nro_cp_modificado,
				'id_proyecto_operadores_atribucion' => $item->id_proyecto_operadores_atribucion,
				'tipo_de_nota' 						=> $item->tipo_de_nota,
				'est_comp' 							=> $item->est_comp,
				'valor_fob_embarcado' 				=> $item->valor_fob_embarcado,
				'valor_op_gratuitas' 				=> $item->valor_op_gratuitas,
				'tipo_operacion' 					=> $item->tipo_operacion,
				'dam_cp' 							=> $item->dam_cp,
				'clu' 								=> $item->clu,
				'car_sunat' 						=> $item->car_sunat,
				'existe_en_sire'					=> 'si',
				'coincide_estado'					=> ($coincide_estado == 'no') ? 'No Coincide Estado' : 'Si Coincide Estado',
				'coincide_total'					=> ($coincide_total == 'no') ? 'No Coincide Monto' : 'Si Coincide Monto',
				'existe_sunat'						=> 'si'
			);
		}
		
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Éxito';
		$resp['mensaje'] = 'Se extrajeron los registros de la base de datos correctamente!';
		$resp['registros'] = $registros;
		$resp['totales'] = array(
			'total_cp' => round($total_cp, 2),
			'total_mto_exonerado' => round($total_mto_exonerado, 2),
			'total_mto_inafecto' => round($total_mto_inafecto, 2),
			'total_igv_ipm' => round($total_igv_ipm, 2),
			'total_dscto_igv_ipm' => round($total_dscto_igv_ipm, 2),
			'total_bi_gravada' => round($total_bi_gravada, 2),
			'total_dscto_bi' => round($total_dscto_bi, 2),
			'total_isc' => round($total_isc, 2),
			'total_bi_grav_ivap' => round($total_bi_grav_ivap, 2),
			'total_ivap' => round($total_ivap, 2),
			'total_icbper' => round($total_icbper, 2),
			'total_otros_tributos' => round($total_otros_tributos, 2),
			'total_valor_facturado_exportacion' => round($total_valor_facturado_exportacion, 2),
			'total_valor_fob_embarcado' => round($total_valor_fob_embarcado, 2),
			'total_valor_op_gratuitas' => round($total_valor_op_gratuitas, 2),
			'total_facturas' => round($total_facturas, 2),
			'total_boletas' => round($total_boletas, 2),
			'total_notas_credito' => round($total_notas_credito, 2),
			'total_notas_debito' => round($total_notas_debito, 2)
		);
		$resp['items_sire_ventas'] = $sire_rvie_items;
		return $resp;
	}

	public function verificar_si_existe_en_array($item, $lista) {
		foreach ($lista as $registro) {
			if ($item->tipo_cp_doc == $registro['id_tipodoc_electronico'] &&
				$item->serie_cdp == $registro['serie_comprobante'] &&
				$item->nro_cp_doc_inicial == $registro['numero_comprobante']) {
				return true; // El registro está en el array
			}
		}
		return false; // El registro no está en el array
	}

	public function formatearFechaDMY($fecha) {
		if (empty($fecha)) {
			return null;
		}
	
		try {
			$objFecha = new DateTime($fecha);
			return $objFecha->format('d/m/Y');
		} catch (Exception $e) {
            $this->saveLogger($e);
			// En caso de que la fecha no sea válida
			return null;
		}
	}

	public function descargar_propuesta_rvie($contribuyente, $sire_rvie, $data) {
		$url = "https://facturalahoy.com/api/apisire/propuesta_ventas";
		$resp_sire = $this->conexion_sire($url, $data);
		
		if($resp_sire->respuesta == 'error' || !isset($resp_sire->zip_base64)) {
			// Manejar error: respuesta de error de la API o falta de contenido ZIP
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error en la respuesta de la API o falta de contenido ZIP.';
			return (object)$resp;
		}
	
		$zipContent = base64_decode($resp_sire->zip_base64);
		if (!$zipContent) {
			// Manejar error: falla en la decodificación de base64
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al decodificar el contenido del ZIP.';
			return (object)$resp;
		}
	
		$tempZip = tempnam(sys_get_temp_dir(), 'zip');
		if (file_put_contents($tempZip, $zipContent) === false) {
			// Manejar error: falla al escribir el archivo temporal
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al escribir el archivo ZIP temporal.';
			return (object)$resp;
		}
	
		$zip = new ZipArchive;
		if ($zip->open($tempZip) !== TRUE) {
			// Manejar error: falla al abrir el archivo ZIP
			unlink($tempZip);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error al abrir el archivo ZIP.';
			return (object)$resp;
		}
	
		$txtFound = false;
		$lista_resp_process_contenido = array();
		for($i = 0; $i < $zip->numFiles; $i++) {
			$filename = $zip->getNameIndex($i);
			if (pathinfo($filename, PATHINFO_EXTENSION) == 'txt') {
				$txtFound = true;
				$fileContent = $zip->getFromIndex($i);
				// Hacer algo con el contenido del .txt, como imprimirlo
				$this->db->begin();
				$resp_process_contenido = $this->procesar_contenido_propuesta($contribuyente, $sire_rvie, $fileContent);
				$lista_resp_process_contenido[] = $resp_process_contenido;
				if($resp_process_contenido->respuesta == 'error') {
					$this->db->rollback();
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['fileContent'] = $fileContent;
					$resp['mensaje'] = $resp_process_contenido->mensaje;
					return (object)$resp;
				}
				$this->db->commit();
			}
		}
		$zip->close();
		unlink($tempZip);
	
		if (!$txtFound) {
			// Manejar error: no se encontraron archivos .txt en el ZIP
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encontraron archivos .txt en el ZIP.';
			return (object)$resp;
		}
	
		// Manejar éxito: se encontró al menos un archivo .txt en el ZIP
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Éxito';
		$resp['mensaje'] = 'Se descargó la propuesta correctamente!';
		$resp['lista_resp_process_contenido'] = $lista_resp_process_contenido;
		return (object)$resp;
	}

	public function procesar_contenido_propuesta($contribuyente, $sire_rvie, $contenido_propuesta) {
		
		$lineas = explode("\n", $contenido_propuesta);
		$primeraLinea = array_shift($lineas);
		$primeraLinea = str_replace("\xEF\xBB\xBF", '', $primeraLinea);
		$encabezados = explode('|', $primeraLinea);

		$encabezadosEsperados = [
			'Fecha de emisión', 
			'Fecha Vcto/Pago', 
			'Tipo CP/Doc.', 
			'Serie del CDP', 
			'Nro CP o Doc. Nro Inicial (Rango)',
			'Nro Final (Rango)', 
			'Tipo Doc Identidad', 
			'Nro Doc Identidad', 
			'Apellidos Nombres/ Razón Social', 
			'Valor Facturado Exportación',
			'BI Gravada', 
			'Dscto BI', 
			'IGV / IPM', 
			'Dscto IGV / IPM', 
			'Mto Exonerado', 
			'Mto Inafecto', 
			'ISC', 
			'BI Grav IVAP',
			'IVAP', 
			'ICBPER', 
			'Otros Tributos', 
			'Total CP', 
			'Moneda', 
			'Tipo Cambio', 
			'Fecha Emisión Doc Modificado', 
			'Tipo CP Modificado',
			'Serie CP Modificado', 
			'Nro CP Modificado', 
			'ID Proyecto Operadores Atribución', 
			'Tipo de Nota', 
			'Est. Comp', 
			'Valor FOB Embarcado',
			'Valor OP Gratuitas', 
			'Tipo Operación', 
			'DAM / CP', 'CLU', 
			'CAR SUNAT'
		];

		if ($encabezados == $encabezadosEsperados) {
			$resp_proceso = $this->procesar_contenido_propuesta_modelo_encabezado_1($contribuyente, $sire_rvie, $contenido_propuesta);
			return $resp_proceso;
		}

		$encabezadosEsperados = [
			'Ruc', 
			'Razon Social', 
			'Periodo', 
			'CAR SUNAT', 
			'Fecha de emisión', 
			'Fecha Vcto/Pago', 
			'Tipo CP/Doc.', 
			'Serie del CDP', 
			'Nro CP o Doc. Nro Inicial (Rango)', 
			'Nro Final (Rango)', 
			'Tipo Doc Identidad', 
			'Nro Doc Identidad', 
			'Apellidos Nombres/ Razón Social', 
			'Valor Facturado Exportación', 
			'BI Gravada', 
			'Dscto BI', 
			'IGV / IPM', 
			'Dscto IGV / IPM', 
			'Mto Exonerado', 
			'Mto Inafecto', 
			'ISC', 
			'BI Grav IVAP', 
			'IVAP', 
			'ICBPER', 
			'Otros Tributos', 
			'Total CP', 
			'Moneda', 
			'Tipo Cambio', 
			'Fecha Emisión Doc Modificado', 
			'Tipo CP Modificado', 
			'Serie CP Modificado', 
			'Nro CP Modificado', 
			'ID Proyecto Operadores Atribución', 
			'Tipo de Nota', 
			'Est. Comp', 
			'Valor FOB Embarcado', 
			'Valor OP Gratuitas', 
			'Tipo Operación', 
			'DAM / CP', 
			'CLU'
		];

		if ($encabezados == $encabezadosEsperados) {
			$resp_proceso = $this->procesar_contenido_propuesta_modelo_encabezado_2($contribuyente, $sire_rvie, $contenido_propuesta);
			return $resp_proceso;
		}

		//
		$resp['respuesta'] = 'error';
		$resp['titulo'] = 'Error';
		$resp['mensaje'] = 'No se pudo procesar el contenido de la propuesta.';
		$resp['contenido_propuesta'] = $contenido_propuesta;
		return (object)$resp;		
	}

	public function procesar_contenido_propuesta_modelo_encabezado_2($contribuyente, $sire_rvie, $contenido_propuesta) {
		$id_contribuyente = $sire_rvie->id_contribuyente;
		$tipo_envio_sunat = $sire_rvie->tipo_envio_sunat;
		$periodo_anio = $sire_rvie->periodo_anio;
		$periodo_mes = $sire_rvie->periodo_mes;
		$num_ticket = $sire_rvie->num_ticket;
		
		$lineas = explode("\n", $contenido_propuesta);
		$primeraLinea = array_shift($lineas);
		$primeraLinea = str_replace("\xEF\xBB\xBF", '', $primeraLinea);
		$encabezados = explode('|', $primeraLinea);

		$resp['contenido_propuesta'] = $contenido_propuesta;

		$encabezadosEsperados = [
			'Ruc', 'Razon Social', 'Periodo', 'CAR SUNAT', 'Fecha de emisión', 'Fecha Vcto/Pago', 'Tipo CP/Doc.', 'Serie del CDP', 
			'Nro CP o Doc. Nro Inicial (Rango)', 'Nro Final (Rango)', 'Tipo Doc Identidad', 'Nro Doc Identidad', 
			'Apellidos Nombres/ Razón Social', 'Valor Facturado Exportación', 'BI Gravada', 'Dscto BI', 'IGV / IPM', 
			'Dscto IGV / IPM', 'Mto Exonerado', 'Mto Inafecto', 'ISC', 'BI Grav IVAP', 'IVAP', 'ICBPER', 'Otros Tributos', 
			'Total CP', 'Moneda', 'Tipo Cambio', 'Fecha Emisión Doc Modificado', 'Tipo CP Modificado', 'Serie CP Modificado', 
			'Nro CP Modificado', 'ID Proyecto Operadores Atribución', 'Tipo de Nota', 'Est. Comp', 'Valor FOB Embarcado', 
			'Valor OP Gratuitas', 'Tipo Operación', 'DAM / CP', 'CLU'
		];

		// Verificar que los encabezados coincidan
		if ($encabezados !== $encabezadosEsperados) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Model 2: Los encabezados del archivo no coinciden con los esperados.';
			$resp['encabezados'] = $encabezados;
			$resp['encabezadosEsperados'] = $encabezadosEsperados;
			return (object)$resp;
		}

		// Procesar cada línea del archivo
		foreach ($lineas as $linea) {
			$datos = explode('|', $linea);

			// Verificar que la línea tiene el número correcto de datos
			if (count($datos) < 40) {
				// Manejar el error, como registrar un mensaje o saltar esta línea
				continue;
			}

			if($datos[0] == '' || $datos[1] == '' || $datos[2] == '' || $datos[3] == '') {
				continue;
			}

			$ruc = $datos[0];
			$razon_social = $datos[1];
			$periodo = $datos[2];
			$car_sunat = $datos[3];
			$fecha_emision = $datos[4];
			$fecha_vcto_pago = $datos[5];
			$tipo_cp_doc = $datos[6];
			$serie_cdp = $datos[7];
			$nro_cp_doc_inicial = $datos[8];
			$nro_cp_doc_final = $datos[9];
			$tipo_doc_identidad = $datos[10];
			$nro_doc_identidad = $datos[11];
			$apellidos_nombres_razon_social = $datos[12];
			$valor_facturado_exportacion = $datos[13];
			$bi_gravada = $datos[14];
			$dscto_bi = $datos[15];
			$igv_ipm = $datos[16];
			$dscto_igv_ipm = $datos[17];
			$mto_exonerado = $datos[18];
			$mto_inafecto = $datos[19];
			$isc = $datos[20];
			$bi_grav_ivap = $datos[21];
			$ivap = $datos[22];
			$icbper = $datos[23];
			$otros_tributos = $datos[24];
			$total_cp = $datos[25];
			$moneda = $datos[26];
			$tipo_cambio = $datos[27];
			$fecha_emision_doc_modificado = $datos[28];
			$tipo_cp_modificado = $datos[29];
			$serie_cp_modificado = $datos[30];
			$nro_cp_modificado = $datos[31];
			$id_proyecto_operadores_atribucion = $datos[32];
			$tipo_de_nota = $datos[33];
			$est_comp = $datos[34];
			$valor_fob_embarcado = $datos[35];
			$valor_op_gratuitas = $datos[36];
			$tipo_operacion = $datos[37];
			$dam_cp = $datos[38];
			$clu = $datos[39];
			
			//validar si existe un registro en SireRvieItem
			$sire_rvie_item = SireRvieItem::findFirst(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: AND num_ticket = :num_ticket: AND tipo_cp_doc = :tipo_cp_doc: AND serie_cdp = :serie_cdp: AND nro_cp_doc_inicial = :nro_cp_doc_inicial: AND nro_cp_doc_final = :nro_cp_doc_final: AND tipo_doc_identidad = :tipo_doc_identidad: AND nro_doc_identidad = :nro_doc_identidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodo_mes, 'num_ticket' => $num_ticket, 'tipo_cp_doc' => $tipo_cp_doc, 'serie_cdp' => $serie_cdp, 'nro_cp_doc_inicial' => $nro_cp_doc_inicial, 'nro_cp_doc_final' => $nro_cp_doc_final, 'tipo_doc_identidad' => $tipo_doc_identidad, 'nro_doc_identidad' => $nro_doc_identidad)));
			if(!$sire_rvie_item) {
				// Crear y configurar el objeto SireRvieItem
				$sire_rvie_item = new SireRvieItem();
				$sire_rvie_item->id_contribuyente = $id_contribuyente;
				$sire_rvie_item->tipo_envio_sunat = $tipo_envio_sunat;
				$sire_rvie_item->periodo_anio = $periodo_anio;
				$sire_rvie_item->periodo_mes = $periodo_mes;
				$sire_rvie_item->car_sunat = $car_sunat;
				$sire_rvie_item->num_ticket = $num_ticket;
				$sire_rvie_item->ruc = $ruc;
				$sire_rvie_item->razon_social = $razon_social;
				$sire_rvie_item->periodo = $periodo;
				$sire_rvie_item->fecha_emision = $this->dia_mes_anio_to_date($fecha_emision);
				$sire_rvie_item->fecha_vcto_pago = $this->dia_mes_anio_to_date($fecha_vcto_pago);
				$sire_rvie_item->tipo_cp_doc = $tipo_cp_doc;
				$sire_rvie_item->serie_cdp = $serie_cdp;
				$sire_rvie_item->nro_cp_doc_inicial = $nro_cp_doc_inicial;
				$sire_rvie_item->nro_cp_doc_final = $nro_cp_doc_final;
				$sire_rvie_item->tipo_doc_identidad = $tipo_doc_identidad;
				$sire_rvie_item->nro_doc_identidad = $nro_doc_identidad;
				$sire_rvie_item->apellidos_nombres_razon_social = $apellidos_nombres_razon_social;
				$sire_rvie_item->valor_facturado_exportacion = $this->convertirTextoADecimal($valor_facturado_exportacion);
				$sire_rvie_item->bi_gravada = $this->convertirTextoADecimal($bi_gravada);
				$sire_rvie_item->dscto_bi = $this->convertirTextoADecimal($dscto_bi);
				$sire_rvie_item->igv_ipm = $this->convertirTextoADecimal($igv_ipm);
				$sire_rvie_item->dscto_igv_ipm = $this->convertirTextoADecimal($dscto_igv_ipm);
				$sire_rvie_item->mto_exonerado = $this->convertirTextoADecimal($mto_exonerado);
				$sire_rvie_item->mto_inafecto = $this->convertirTextoADecimal($mto_inafecto);
				$sire_rvie_item->isc = $this->convertirTextoADecimal($isc);
				$sire_rvie_item->bi_grav_ivap = $this->convertirTextoADecimal($bi_grav_ivap);
				$sire_rvie_item->ivap = $this->convertirTextoADecimal($ivap);
				$sire_rvie_item->icbper = $this->convertirTextoADecimal($icbper);
				$sire_rvie_item->otros_tributos = $this->convertirTextoADecimal($otros_tributos);
				$sire_rvie_item->total_cp = $this->convertirTextoADecimal($total_cp);
				$sire_rvie_item->moneda = $moneda;
				$sire_rvie_item->tipo_cambio = $this->convertirTextoADecimal($tipo_cambio);
				$sire_rvie_item->fecha_emision_doc_modificado = $this->dia_mes_anio_to_date($fecha_emision_doc_modificado);
				$sire_rvie_item->tipo_cp_modificado = $tipo_cp_modificado;
				$sire_rvie_item->serie_cp_modificado = $serie_cp_modificado;
				$sire_rvie_item->nro_cp_modificado = $nro_cp_modificado;
				$sire_rvie_item->id_proyecto_operadores_atribucion = $id_proyecto_operadores_atribucion;
				$sire_rvie_item->tipo_de_nota = $tipo_de_nota;
				$sire_rvie_item->est_comp = $est_comp;
				$sire_rvie_item->valor_fob_embarcado = $this->convertirTextoADecimal($valor_fob_embarcado);
				$sire_rvie_item->valor_op_gratuitas = $this->convertirTextoADecimal($valor_op_gratuitas);
				$sire_rvie_item->tipo_operacion = $tipo_operacion;
				$sire_rvie_item->dam_cp = $dam_cp;
				$sire_rvie_item->clu = $clu;

				try {
					if(!$sire_rvie_item->save()) {
						$msg = '';
						foreach ($sire_rvie_item->getMessages() as $message) {
							$msg .= $message . "<br>";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return (object)$resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return (object)$resp;
				}
			}
			
		}

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Éxito';
		$resp['mensaje'] = 'Se procesó el contenido de la propuesta correctamente!';
		return (object)$resp;
	}

	public function procesar_contenido_propuesta_modelo_encabezado_1($contribuyente, $sire_rvie, $contenido_propuesta) {
		$id_contribuyente = $sire_rvie->id_contribuyente;
		$tipo_envio_sunat = $sire_rvie->tipo_envio_sunat;
		$periodo_anio = $sire_rvie->periodo_anio;
		$periodo_mes = $sire_rvie->periodo_mes;
		$num_ticket = $sire_rvie->num_ticket;

		$lineas = explode("\n", $contenido_propuesta);
		$primeraLinea = array_shift($lineas);
		$primeraLinea = str_replace("\xEF\xBB\xBF", '', $primeraLinea);
		$encabezados = explode('|', $primeraLinea);

		$resp['contenido_propuesta'] = $contenido_propuesta;

		$encabezadosEsperados = [
			'Fecha de emisión', 'Fecha Vcto/Pago', 'Tipo CP/Doc.', 'Serie del CDP', 'Nro CP o Doc. Nro Inicial (Rango)',
			'Nro Final (Rango)', 'Tipo Doc Identidad', 'Nro Doc Identidad', 'Apellidos Nombres/ Razón Social', 'Valor Facturado Exportación',
			'BI Gravada', 'Dscto BI', 'IGV / IPM', 'Dscto IGV / IPM', 'Mto Exonerado', 'Mto Inafecto', 'ISC', 'BI Grav IVAP',
			'IVAP', 'ICBPER', 'Otros Tributos', 'Total CP', 'Moneda', 'Tipo Cambio', 'Fecha Emisión Doc Modificado', 'Tipo CP Modificado',
			'Serie CP Modificado', 'Nro CP Modificado', 'ID Proyecto Operadores Atribución', 'Tipo de Nota', 'Est. Comp', 'Valor FOB Embarcado',
			'Valor OP Gratuitas', 'Tipo Operación', 'DAM / CP', 'CLU', 'CAR SUNAT'
		];

		$resp['contenido_propuesta_txt'] = $contenido_propuesta;

		// Verificar que los encabezados coincidan
		if ($encabezados !== $encabezadosEsperados) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Model 1: Los encabezados del archivo no coinciden con los esperados.';
			$resp['encabezados'] = $encabezados;
			$resp['encabezadosEsperados'] = $encabezadosEsperados;
			return (object)$resp;
		}

		// Procesar cada línea del archivo
		foreach ($lineas as $linea) {
			$datos = explode('|', $linea);

			// Verificar que la línea tiene el número correcto de datos
			if (count($datos) < 37) {
				// Manejar el error, como registrar un mensaje o saltar esta línea
				continue;
			}

			if($datos[0] == '' || $datos[1] == '' || $datos[2] == '' || $datos[3] == '') {
				continue;
			}
			
			$fecha_emision 		= $datos[0];
			$fecha_vcto_pago 	= $datos[1];
			$tipo_cp_doc 		= $datos[2];
			$serie_cdp 			= $datos[3];
			$nro_cp_doc_inicial = $datos[4];
			$nro_cp_doc_final 	= $datos[5];
			$tipo_doc_identidad = $datos[6];
			$nro_doc_identidad 	= $datos[7];
			$apellidos_nombres_razon_social = $datos[8];
			$valor_facturado_exportacion = $datos[9];
			$bi_gravada 		= $datos[10];
			$dscto_bi 			= $datos[11];
			$igv_ipm 			= $datos[12];
			$dscto_igv_ipm 		= $datos[13];
			$mto_exonerado 		= $datos[14];
			$mto_inafecto 		= $datos[15];
			$isc 				= $datos[16];
			$bi_grav_ivap 		= $datos[17];
			$ivap 				= $datos[18];
			$icbper 			= $datos[19];
			$otros_tributos 	= $datos[20];
			$total_cp 			= $datos[21];
			$moneda 			= $datos[22];
			$tipo_cambio 		= $datos[23];
			$fecha_emision_doc_modificado = $datos[24];
			$tipo_cp_modificado = $datos[25];
			$serie_cp_modificado = $datos[26];
			$nro_cp_modificado 	= $datos[27];
			$id_proyecto_operadores_atribucion = $datos[28];
			$tipo_de_nota 		= $datos[29];
			$est_comp 			= $datos[30];
			$valor_fob_embarcado 	= $datos[31];
			$valor_op_gratuitas 	= $datos[32];
			$tipo_operacion 		= $datos[33];
			$dam_cp 				= $datos[34];
			$clu 					= $datos[35];
			$car_sunat 				= $datos[36];
			
			//validar si existe un registro en SireRvieItem
			$sire_rvie_item = SireRvieItem::findFirst(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: AND num_ticket = :num_ticket: AND tipo_cp_doc = :tipo_cp_doc: AND serie_cdp = :serie_cdp: AND nro_cp_doc_inicial = :nro_cp_doc_inicial: AND nro_cp_doc_final = :nro_cp_doc_final: AND tipo_doc_identidad = :tipo_doc_identidad: AND nro_doc_identidad = :nro_doc_identidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodo_mes, 'num_ticket' => $num_ticket, 'tipo_cp_doc' => $tipo_cp_doc, 'serie_cdp' => $serie_cdp, 'nro_cp_doc_inicial' => $nro_cp_doc_inicial, 'nro_cp_doc_final' => $nro_cp_doc_final, 'tipo_doc_identidad' => $tipo_doc_identidad, 'nro_doc_identidad' => $nro_doc_identidad)));
			if(!$sire_rvie_item) {
				$sire_rvie_item = new SireRvieItem();
				$sire_rvie_item->id_contribuyente 		= $id_contribuyente;
				$sire_rvie_item->tipo_envio_sunat 		= $tipo_envio_sunat;
				$sire_rvie_item->periodo_anio 			= $periodo_anio;
				$sire_rvie_item->periodo_mes 			= $periodo_mes;
				$sire_rvie_item->num_ticket 			= $num_ticket;
				$sire_rvie_item->fecha_emision 			= $this->dia_mes_anio_to_date($fecha_emision);
				$sire_rvie_item->fecha_vcto_pago 		= $this->dia_mes_anio_to_date($fecha_vcto_pago);
				$sire_rvie_item->tipo_cp_doc			= $tipo_cp_doc;
				$sire_rvie_item->serie_cdp 				= $serie_cdp;
				$sire_rvie_item->nro_cp_doc_inicial 	= $nro_cp_doc_inicial;
				$sire_rvie_item->nro_cp_doc_final 		= $nro_cp_doc_final;
				$sire_rvie_item->tipo_doc_identidad 	= $tipo_doc_identidad;
				$sire_rvie_item->nro_doc_identidad 		= $nro_doc_identidad;
				$sire_rvie_item->apellidos_nombres_razon_social = $apellidos_nombres_razon_social;
				$sire_rvie_item->valor_facturado_exportacion = $this->convertirTextoADecimal($valor_facturado_exportacion);
				$sire_rvie_item->bi_gravada 			= $this->convertirTextoADecimal($bi_gravada);
				$sire_rvie_item->dscto_bi 				= $this->convertirTextoADecimal($dscto_bi);
				$sire_rvie_item->igv_ipm 				= $this->convertirTextoADecimal($igv_ipm);
				$sire_rvie_item->dscto_igv_ipm 			= $this->convertirTextoADecimal($dscto_igv_ipm);
				$sire_rvie_item->mto_exonerado 			= $this->convertirTextoADecimal($mto_exonerado);
				$sire_rvie_item->mto_inafecto 			= $this->convertirTextoADecimal($mto_inafecto);
				$sire_rvie_item->isc 					= $this->convertirTextoADecimal($isc);
				$sire_rvie_item->bi_grav_ivap 			= $this->convertirTextoADecimal($bi_grav_ivap);
				$sire_rvie_item->ivap 					= $this->convertirTextoADecimal($ivap);
				$sire_rvie_item->icbper 				= $this->convertirTextoADecimal($icbper);
				$sire_rvie_item->otros_tributos 		= $this->convertirTextoADecimal($otros_tributos);
				$sire_rvie_item->total_cp 				= $this->convertirTextoADecimal($total_cp);
				$sire_rvie_item->moneda 				= $moneda;
				$sire_rvie_item->tipo_cambio 			= $this->convertirTextoADecimal($tipo_cambio);
				$sire_rvie_item->fecha_emision_doc_modificado = $this->dia_mes_anio_to_date($fecha_emision_doc_modificado);
				$sire_rvie_item->tipo_cp_modificado 	= $tipo_cp_modificado;
				$sire_rvie_item->serie_cp_modificado 	= $serie_cp_modificado;
				$sire_rvie_item->nro_cp_modificado 		= $nro_cp_modificado;
				$sire_rvie_item->id_proyecto_operadores_atribucion = $id_proyecto_operadores_atribucion;
				$sire_rvie_item->tipo_de_nota 			= $tipo_de_nota;
				$sire_rvie_item->est_comp 				= $est_comp;
				$sire_rvie_item->valor_fob_embarcado 	= $this->convertirTextoADecimal($valor_fob_embarcado);
				$sire_rvie_item->valor_op_gratuitas 	= $this->convertirTextoADecimal($valor_op_gratuitas);
				$sire_rvie_item->tipo_operacion 		= $tipo_operacion;
				$sire_rvie_item->dam_cp 				= $dam_cp;
				$sire_rvie_item->clu 					= $clu;
				$sire_rvie_item->car_sunat 				= $car_sunat;

				try {
					if(!$sire_rvie_item->save()) {
						$msg = '';
						foreach ($sire_rvie_item->getMessages() as $message) {
							$msg .= $message . "<br>";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return (object)$resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return (object)$resp;
				}
			}
			
		}

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Éxito';
		$resp['mensaje'] = 'Se procesó el contenido de la propuesta correctamente!';
		return (object)$resp;
	}

	public function sireVentasGetTicketAction() {
		$this->view->disable();
		$request = $this->request;
		if($request->isAjax() == true) 
		{
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

			$datapost = $this->request->getPost();

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se pudo obtener el contribuyente!';
				echo json_encode($resp);
				exit();
			}

			$periodo_anio = !isset($datapost['num_ejercicio'])?'':intval($datapost['num_ejercicio']);
			if($periodo_anio == '') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debe seleccionar un ejercicio!';
				echo json_encode($resp);
				exit();
			}

			if($periodo_anio < 2023) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se puede obtener el ticket de un ejercicio anterior al 2019!';
				echo json_encode($resp);
				exit();
			}

			$periodotributario = !isset($datapost['periodo_tributario'])?'':$datapost['periodo_tributario'];
			if($periodotributario == '') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debe seleccionar un periodo tributario!';
				echo json_encode($resp);
				exit();
			}

			if(!$this->validarPeriodoTributario($periodo_anio, $periodotributario)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El periodo tributario no es válido!';
				echo json_encode($resp);
				exit();
			}

			/*
			$sire_rvie = SireRvie::findFirst(array("id_contribuyente = :id_contribuyente: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodotributario, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "fecha_registro DESC"));

			if($sire_rvie) {
				$fecha_registro_ultimo_ticket = $sire_rvie->fecha_registro;
				if(!$this->verificarFechaRegistro($fecha_registro_ultimo_ticket)) {
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Éxito';
					$resp['mensaje'] = 'Se obtuvo el ticket correctamente!';
					$resp['num_ticket'] = $sire_rvie->num_ticket;
					echo json_encode($resp);
					exit();
				}
			}
			*/
			
			$data_api = array(
				"token" 				=> $this->token_user,
				"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
				"accion" 				=> "get_ticket_to_descargar_propuesta_ventas",
				"periodotributario" 	=> $periodotributario
			);

			$url = "https://facturalahoy.com/api/apisire/propuesta_ventas";
			$resp_sire = $this->conexion_sire($url, $data_api);
			if($resp_sire->respuesta == 'error') {
				echo json_encode($resp_sire);
				exit();
			}

			$num_ticket = $resp_sire->ticket->numTicket;
			if($num_ticket == '') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se pudo obtener el ticket!';
				echo json_encode($resp);
				exit();
			}

			$sire_rvie = SireRvie::findFirst(array("id_contribuyente = :id_contribuyente: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: and num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodotributario, 'num_ticket' => $num_ticket), "order" => "fecha_registro DESC"));
			if(!$sire_rvie) {
				$sire_rvie = new SireRvie();
				$sire_rvie->id_contribuyente = $usuario->id_contribuyente;
				$sire_rvie->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$sire_rvie->periodo_anio = $periodo_anio;
				$sire_rvie->periodo_mes = $periodotributario;
				$sire_rvie->num_ticket = $num_ticket;
				$sire_rvie->fecha_registro = date('Y-m-d H:i:s');

				try {
					if(!$sire_rvie->save()) {
						$msg = '';
						foreach ($sire_rvie->getMessages() as $message) {
							$msg .= $message . "<br>";
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

			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Éxito';
			$resp['mensaje'] = 'Se obtuvo el ticket correctamente!';
			$resp['num_ticket'] = $num_ticket;
			echo json_encode($resp);
			exit();
		}
	}

	public function verificarFechaRegistro($fechaRegistro) {
		// Convertir la fecha de registro en objeto DateTime
		$fechaRegistro = new DateTime($fechaRegistro);
	
		// Obtener la fecha y hora actual
		$fechaActual = new DateTime();
	
		// Obtener la fecha de ayer
		$ayer = new DateTime('yesterday');
	
		// Comprobar si la fecha de registro es de ayer
		if ($fechaRegistro->format('Y-m-d') === $ayer->format('Y-m-d')) {
			return true;
		}
	
		// Comprobar si la fecha de registro es de hoy pero hace más de una hora
		if ($fechaRegistro->format('Y-m-d') === $fechaActual->format('Y-m-d')) {
			// Diferencia en horas
			$diferenciaHoras = $fechaActual->diff($fechaRegistro)->h;
			if ($diferenciaHoras >= 1) {
				return true;
			}
		}
	
		// En otros casos, retorna false
		return false;
	}

    public function sireVentasListaPeriodosAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
			
			$fechaActual = new DateTime();
			$anoActual = $fechaActual->format("Y");
			$mesActual = $fechaActual->format("m");
			$id_contribuyente = $contribuyente->id_contribuyente;
			$tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
			$mes_anio = $mesActual.$anoActual;
			$tipo_sire = 'ventas';

			$sire_periodo = SirePeriodo::findFirst(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND mes_anio = :mes_anio: and tipo_sire = 'ventas'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'mes_anio' => $mes_anio)));
			if($sire_periodo) {
				echo $sire_periodo->response;
				exit();
			}

			$data_api = array(
				"token" 				=> $this->token_user,
				"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
				"accion" 				=> "consultar_lista_periodos_sire_ventas"
			);

			$url = "https://facturalahoy.com/api/apisire/propuesta_ventas";
			$resp_sire = $this->conexion_sire($url, $data_api);
			if($resp_sire->respuesta == 'error') {
				echo json_encode($resp_sire);
				exit();
			}

			try {
				$sire_periodo = new SirePeriodo();
				$sire_periodo->id_contribuyente = $id_contribuyente;
				$sire_periodo->tipo_envio_sunat = $tipo_envio_sunat;
				$sire_periodo->mes_anio = $mes_anio;
				$sire_periodo->tipo_sire = 'ventas';
				$sire_periodo->response = json_encode($resp_sire);
				$sire_periodo->fecha_registro = date('Y-m-d H:i:s');
				
				if(!$sire_periodo->save()) {
					$msg = '';
					foreach ($sire_periodo->getMessages() as $message) {
						$msg .= $message . "<br>";
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
			
			echo $sire_periodo->response;
			exit();
		}
	}

	public function get_credenciales_sunat($id_contribuyente) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$credenciales_sunat = array(
			"ruc" 			=> $contribuyente->ruc,
			"username" 		=> $contribuyente->sunat_u_sol_principal,
			"password" 		=> $contribuyente->sunat_p_sol_principal,
			"client_id" 	=> $contribuyente->sunat_client_id,
			"client_secret" => $contribuyente->sunat_client_secret
		);

		return $credenciales_sunat;
	}

	function conexion_sire($url, $data) {
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			)
		));
	
		$response = curl_exec($curl);
	
		// Verificar si ocurrió un error en la solicitud cURL
		if (curl_errno($curl)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error en la solicitud cURL: ' . curl_error($curl);
			curl_close($curl);
			return $resp;
		}
	
		// Obtener el código de respuesta HTTP
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
	
		// Verificar si el código de respuesta indica un error
		if ($http_code >= 400) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Error en la respuesta HTTP: Código de estado ' . $http_code;
			return $resp;
		}
	
		// Verificar si la respuesta es un JSON válido
		$json = json_decode($response);
		if (json_last_error() === JSON_ERROR_NONE) {
			return $json; // Retornar el array asociativo si es un JSON válido
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La respuesta no es un JSON válido: ' . $response;
			return $resp;
		}
	}

	public function validarPeriodoTributario($numEjercicio, $periodoTributario) {
		// Verificar longitud
		if (strlen($periodoTributario) != 6) {
			return false;
		}
	
		// Extraer año y mes
		$anio = substr($periodoTributario, 0, 4);
		$mes = substr($periodoTributario, 4, 2);
	
		// Verificar si el año y el mes son numéricos
		if (!is_numeric($anio) || !is_numeric($mes)) {
			return false;
		}
	
		// Convertir a enteros
		$anio = (int)$anio;
		$mes = (int)$mes;
	
		// Verificar rango del mes
		if ($mes < 1 || $mes > 12) {
			return false;
		}

		if($anio != $numEjercicio) {
			return false;
		}
	
		// La función retorna true si todas las validaciones son correctas
		return true;
	}

	public function formatearFechaParaMySQL($fecha) {
		if (empty($fecha)) {
			return null;
		}
	
		$partes = explode('/', $fecha);
		if (count($partes) == 3) {
			list($anio, $mes, $dia) = $partes;
			if (checkdate($mes, $dia, $anio)) {
				return "$anio-$mes-$dia";
			}
		}
		return null;
	}

	public function dia_mes_anio_to_date($fecha) {
		if (empty($fecha)) {
			return null;
		}
	
		$partes = explode('/', $fecha);
		if (count($partes) == 3) {
			// Asumiendo que la fecha está en el formato día/mes/año
			list($dia, $mes, $anio) = $partes;
			if (checkdate($mes, $dia, $anio)) {
				// Formato de MySQL: año-mes-día
				return "$anio-$mes-$dia";
			}
		}
		return null;
	}

	public function convertirTextoADecimal($texto) {
		if (empty($texto)) {
			return null;
		}
	
		// Primero, eliminamos todas las comas excepto la que precede inmediatamente al punto decimal
		$partes = explode('.', $texto);
		if (count($partes) > 1) {
			$parteEntera = str_replace(',', '', implode('.', array_slice($partes, 0, -1)));
			$parteDecimal = end($partes);
			$textoFormateado = $parteEntera . '.' . $parteDecimal;
		} else {
			$textoFormateado = str_replace(',', '', $texto);
		}
	
		return is_numeric($textoFormateado) ? (float)$textoFormateado : null;
	}
}
?>