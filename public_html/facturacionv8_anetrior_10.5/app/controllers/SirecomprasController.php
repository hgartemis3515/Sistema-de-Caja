<?php
class SirecomprasController extends ControllerBase
{
    public function sireComprasConsultarYDescargarPropuestaAction() {
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

			$sire_rce = SireRce::findFirst(array("id_contribuyente = :id_contribuyente: AND num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'num_ticket' => $num_ticket)));
			if(!$sire_rce) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El número de ticket no es válido!';
				echo json_encode($resp);
				exit();
			}

			$data_api = array(
				"token" 				=> $this->token_user,
				"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
				"accion" 				=> "consultar_estado_ticket_propuesta_compras",
				"data_propuesta" 		=> array(
					"perIni" 		=> $sire_rce->periodo_mes,
					"perFin" 		=> $sire_rce->periodo_mes,
					"numTicket" 	=> $num_ticket,
					"page" 			=> 1,
					"perPage" 		=> 5000
				)
			);

			$url = "https://facturalahoy.com/api/apisire/propuesta_compras";
			$resp_sire = $this->conexion_sire($url, $data_api);
            
			if($resp_sire->respuesta == 'error') {
				echo json_encode($resp_sire);
				exit();
			}

			$registros_sire = $resp_sire->estado_ticket->registros;
			$resp_descargar_propuesta_rce = array();
			foreach($registros_sire as $registro) {
				$archivos_reporte = $registro->archivoReporte;
				try {
					foreach($archivos_reporte as $reporte) {
						$data_api_descarga = array(
							"token" 				=> $this->token_user,
							"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
							"accion" 				=> "descargar_archivo_propuesta_compras",
							"data_propuesta" 		=> array(
								"nomArchivoReporte" 		=> $reporte->nomArchivoReporte,
								"nom_archivo_txt" 			=> $reporte->nomArchivoContenido,
								"codTipoArchivoReporte"     => "01",
								"perIni" 					=> $sire_rce->periodo_mes,
								"perFin" 					=> $sire_rce->periodo_mes,
								"numTicket" 				=> $num_ticket,
							)
						);
	
						$resp_descargar_propuesta_rce[] = $this->descargar_propuesta_rce($contribuyente, $sire_rce, $data_api_descarga);
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['registros_sire'] = $registros_sire;
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No se encontraron archivos en la propuesta, al parecer el ticket sigue en proceso!';
					$resp['e'] = $e;
					echo json_encode($resp);
					exit();
				}
			}

			$mensaje = 'Operación correcta!';
			foreach($resp_descargar_propuesta_rce as $resp_descarga) {
				if($resp_descarga->respuesta == 'error') {
					$mensaje = 'Uno de los archivos en la propuesta no se pudo descargar! '.$resp_descarga->mensaje;
					break;
				}
			}

			$resp_get_registros = $this->extraer_registros_rce_bd($sire_rce);
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

    public function extraer_registros_rce_bd($sire_rce) {
		$id_contribuyente = $sire_rce->id_contribuyente;
		$tipo_envio_sunat = $sire_rce->tipo_envio_sunat;
		$periodo_anio = $sire_rce->periodo_anio;
		$periodo_mes = $sire_rce->periodo_mes;
		$num_ticket = $sire_rce->num_ticket;
		
		$sire_rce_items = SireRceItem::find(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: AND num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodo_mes, 'num_ticket' => $num_ticket)));

		$registros = array();
		$total_facturas = 0;
		$total_boletas = 0;
		$total_notas_credito = 0;
		$total_notas_debito = 0;
		$total_cp = 0;

		foreach($sire_rce_items as $item) {

			if($item->tipo_cp_doc == '01') {
				$total_facturas = $total_facturas + $item->total_cp;
			} else if($item->tipo_cp_doc == '03') {
				$total_boletas = $total_boletas + $item->total_cp;
			} else if($item->tipo_cp_doc == '07') {
				$total_notas_credito = $total_notas_credito + $item->total_cp;
			} else if($item->tipo_cp_doc == '08') {
				$total_notas_debito = $total_notas_debito + $item->total_cp;
			}

			$total_cp = $total_cp + $item->total_cp;

			$registros[] = array(
				'url_search_sunat' => "<a href='/facturacionv8/gestiondecompras/registrarcompra/01/$item->nro_doc_identidad/$item->serie_cdp/$item->nro_cp_doc_inicial' target='_blank'><img src='/facturacionv8/img/search_in_sunat.png' style='width: 25px;'/></a>",
                'ruc' => $item->ruc,
				'razon_social' => $item->razon_social,
				'periodo' => $item->periodo,
				'car_sunat' => $item->car_sunat,
				'fecha_emision' => $this->formatearFechaDMY($item->fecha_emision),
				'fecha_vcto_pago' => $this->formatearFechaDMY($item->fecha_vcto_pago),
				'tipo_cp_doc' => $item->tipo_cp_doc,
				'serie_cdp' => $item->serie_cdp,
				'anio' => $item->anio,
				'nro_cp_doc_inicial' => $item->nro_cp_doc_inicial,
				'nro_final_rango' => $item->nro_final_rango,
				'tipo_doc_identidad' => $item->tipo_doc_identidad,
				'nro_doc_identidad' => $item->nro_doc_identidad,
				'apellidos_nombres_razon_social' => $item->apellidos_nombres_razon_social,
				'bi_gravado_dg' => $item->bi_gravado_dg,
				'igv_ipm_dg' => $item->igv_ipm_dg,
				'bi_gravado_dgng' => $item->bi_gravado_dgng,
				'igv_ipm_dgng' => $item->igv_ipm_dgng,
				'bi_gravado_dng' => $item->bi_gravado_dng,
				'igv_ipm_dng' => $item->igv_ipm_dng,
				'valor_adq_ng' => $item->valor_adq_ng,
				'isc' => $item->isc,
				'icbper' => $item->icbper,
				'otros_trib_cargos' => $item->otros_trib_cargos,
				'total_cp' => $item->total_cp,
				'moneda' => $item->moneda,
				'tipo_cambio' => $item->tipo_cambio,
				'fecha_emision_doc_modificado' => $this->formatearFechaDMY($item->fecha_emision_doc_modificado),
				'tipo_cp_modificado' => $item->tipo_cp_modificado,
				'serie_cp_modificado' => $item->serie_cp_modificado,
				'cod_dam_dsi' => $item->cod_dam_dsi,
				'nro_cp_modificado' => $item->nro_cp_modificado,
				'clasif_bss_sss' => $item->clasif_bss_sss,
				'id_proyecto_operadores' => $item->id_proyecto_operadores,
				'porcpart' => $item->porcpart,
				'imb' => $item->imb,
				'car_orig_ind_e_o_i' => $item->car_orig_ind_e_o_i,
				'detraccion' => $item->detraccion,
				'tipo_de_nota' => $item->tipo_de_nota,
				'est_comp' => $item->est_comp,
				'clu1' => $item->clu1 ?? null,
				'clu2' => $item->clu2 ?? null,
				'clu3' => $item->clu3 ?? null,
				'clu4' => $item->clu4 ?? null,
				'clu5' => $item->clu5 ?? null,
				'clu6' => $item->clu6 ?? null,
				'clu7' => $item->clu7 ?? null,
				'clu8' => $item->clu8 ?? null,
				'clu9' => $item->clu9 ?? null,
				'clu10' => $item->clu10 ?? null,
				'clu11' => $item->clu11 ?? null,
				'clu12' => $item->clu12 ?? null,
				'clu13' => $item->clu13 ?? null,
				'clu14' => $item->clu14 ?? null,
				'clu15' => $item->clu15 ?? null,
				'clu16' => $item->clu16 ?? null,
				'clu17' => $item->clu17 ?? null,
				'clu18' => $item->clu18 ?? null,
				'clu19' => $item->clu19 ?? null,
				'clu20' => $item->clu20 ?? null,
				'clu21' => $item->clu21 ?? null,
				'clu22' => $item->clu22 ?? null,
				'clu23' => $item->clu23 ?? null,
				'clu24' => $item->clu24 ?? null,
				'clu25' => $item->clu25 ?? null,
				'clu26' => $item->clu26 ?? null,
				'clu27' => $item->clu27 ?? null,
				'clu28' => $item->clu28 ?? null,
				'clu29' => $item->clu29 ?? null,
				'clu30' => $item->clu30 ?? null,
				'clu31' => $item->clu31 ?? null,
				'clu32' => $item->clu32 ?? null,
				'clu33' => $item->clu33 ?? null,
				'clu34' => $item->clu34 ?? null,
				'clu35' => $item->clu35 ?? null,
				'clu36' => $item->clu36 ?? null,
				'clu37' => $item->clu37 ?? null,
				'clu38' => $item->clu38 ?? null,
				'clu39' => $item->clu39 ?? null,
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Éxito';
		$resp['mensaje'] = 'Se extrajeron los registros de la base de datos correctamente!';
		$resp['total_facturas'] = $total_facturas;
		$resp['total_boletas'] = $total_boletas;
		$resp['total_notas_credito'] = $total_notas_credito;
		$resp['total_notas_debito'] = $total_notas_debito;
		$resp['total_cp'] = $total_cp;
		$resp['registros'] = $registros;
		return $resp;
	}

	public function downloadTxtRceAction() {
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

		$num_ticket = $_GET['num_ticket'];
		if($num_ticket == '') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes ingresar un número de ticket!';
			echo json_encode($resp);
			exit();
		}

		$sire_rce = SireRce::findFirst(array("id_contribuyente = :id_contribuyente: AND num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'num_ticket' => $num_ticket)));
		if(!$sire_rce) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El número de ticket no es válido!';
			echo json_encode($resp);
			exit();
		}

		$resp_get_registros = $this->extraer_registros_rce_bd($sire_rce);
		if($resp_get_registros['respuesta'] == 'error') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $resp_get_registros['mensaje'];
			echo json_encode($resp);
			exit();
		}

		$filename = $contribuyente->ruc.'-'.'CP'.'-'.$sire_rce->periodo_mes.'-'.'1'.'.txt';

		header("Content-Type: text/plain");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		foreach($resp_get_registros['registros'] as $item) {
			$item = (object)$item;
			$item_txt_propuesta_sire = array(
				$contribuyente->ruc,
				$contribuyente->razon_social,
				$sire_rce->periodo_mes,
				$item->car_sunat,
				$item->fecha_emision,
				$item->fecha_vcto_pago,
				$item->tipo_cp_doc,
				$item->serie_cdp,
				$item->anio,
				$item->nro_cp_doc_inicial,
				$item->nro_final_rango,
				$item->tipo_doc_identidad,
				$item->nro_doc_identidad,
				$item->apellidos_nombres_razon_social,
				$item->bi_gravado_dg,
				$item->igv_ipm_dg,
				$item->bi_gravado_dgng,
				$item->igv_ipm_dgng,
				$item->bi_gravado_dng,
				$item->igv_ipm_dng,
				$item->valor_adq_ng,
				$item->isc,
				$item->icbper,
				$item->otros_trib_cargos,
				$item->total_cp,
				$item->moneda,
				$item->tipo_cambio,
				$item->fecha_emision_doc_modificado,
				$item->tipo_cp_modificado,
				$item->serie_cp_modificado,
				$item->cod_dam_dsi,
				$item->nro_cp_modificado,
				$item->clasif_bss_sss,
				$item->id_proyecto_operadores,
				$item->porcpart,
				$item->imb,
				$item->car_orig_ind_e_o_i,
				$item->detraccion,
				$item->tipo_de_nota,
				$item->est_comp,
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
			);

			echo implode("|",$item_txt_propuesta_sire)."\r\n";
		}

		exit();
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

    public function descargar_propuesta_rce($contribuyente, $sire_rce, $data) {
		$url = "https://facturalahoy.com/api/apisire/propuesta_compras";
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
				$resp_process_contenido = $this->procesar_contenido_propuesta($contribuyente, $sire_rce, $fileContent);
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

	public function procesar_contenido_propuesta($contribuyente, $sire_rce, $contenido_propuesta) {
		$id_contribuyente = $sire_rce->id_contribuyente;
        $tipo_envio_sunat = $sire_rce->tipo_envio_sunat;
        $periodo_anio = $sire_rce->periodo_anio;
        $periodo_mes = $sire_rce->periodo_mes;
        $num_ticket = $sire_rce->num_ticket;

        $lineas = explode("\n", $contenido_propuesta);
        $encabezados = explode('|', array_shift($lineas));

        $resp['contenido_propuesta'] = $contenido_propuesta;

		$encabezadosEsperados = [
            'RUC', 'Apellidos y Nombres o Razón social', 'Periodo', 'CAR SUNAT', 'Fecha de emisión', 
            'Fecha Vcto/Pago', 'Tipo CP/Doc.', 'Serie del CDP', 'Año', 'Nro CP o Doc. Nro Inicial (Rango)', 
            'Nro Final (Rango)', 'Tipo Doc Identidad', 'Nro Doc Identidad', 'Apellidos Nombres/ Razón  Social', 
            'BI Gravado DG', 'IGV / IPM DG', 'BI Gravado DGNG', 'IGV / IPM DGNG', 'BI Gravado DNG', 
            'IGV / IPM DNG', 'Valor Adq. NG', 'ISC', 'ICBPER', 'Otros Trib/ Cargos', 'Total CP', 'Moneda', 
            'Tipo de Cambio', 'Fecha Emisión Doc Modificado', 'Tipo CP Modificado', 'Serie CP Modificado', 
            'COD. DAM O DSI', 'Nro CP Modificado', 'Clasif de Bss y Sss', 'ID Proyecto Operadores', 'PorcPart', 
            'IMB', 'CAR Orig/ Ind E o I', 'Detracción', 'Tipo de Nota', 'Est. Comp.', 'Incal', 'CLU1', 'CLU2', 
            'CLU3', 'CLU4', 'CLU5', 'CLU6', 'CLU7', 'CLU8', 'CLU9', 'CLU10', 'CLU11', 'CLU12', 'CLU13', 
            'CLU14', 'CLU15', 'CLU16', 'CLU17', 'CLU18', 'CLU19', 'CLU20', 'CLU21', 'CLU22', 'CLU23', 'CLU24', 
            'CLU25', 'CLU26', 'CLU27', 'CLU28', 'CLU29', 'CLU30', 'CLU31', 'CLU32', 'CLU33', 'CLU34', 'CLU35', 
            'CLU36', 'CLU37', 'CLU38', 'CLU39'
        ];

		// Verificar que los encabezados coincidan
		if ($encabezados != $encabezadosEsperados) {
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
            $anio = $datos[8];
            $nro_cp_doc_inicial = $datos[9];
            $nro_final_rango = $datos[10];
            $tipo_doc_identidad = $datos[11];
            $nro_doc_identidad = $datos[12];
            $apellidos_nombres_razon_social = $datos[13];
            $bi_gravado_dg = $datos[14];
            $igv_ipm_dg = $datos[15];
            $bi_gravado_dgng = $datos[16];
            $igv_ipm_dgng = $datos[17];
            $bi_gravado_dng = $datos[18];
            $igv_ipm_dng = $datos[19];
            $valor_adq_ng = $datos[20];
            $isc = $datos[21];
            $icbper = $datos[22];
            $otros_trib_cargos = $datos[23];
            $total_cp = $datos[24];
            $moneda = $datos[25];
            $tipo_cambio = $datos[26];
            $fecha_emision_doc_modificado = $datos[27];
            $tipo_cp_modificado = $datos[28];
            $serie_cp_modificado = $datos[29];
            $cod_dam_dsi = $datos[30];
            $nro_cp_modificado = $datos[31];
            $clasif_bss_sss = $datos[32];
            $id_proyecto_operadores = $datos[33];
            $porcpart = $datos[34];
            $imb = $datos[35];
            $car_orig_ind_e_o_i = $datos[36];
            $detraccion = $datos[37];
            $tipo_de_nota = $datos[38];
            $est_comp = $datos[39];
            $clu1 = $datos[40] ?? null;
            $clu2 = $datos[41] ?? null;
            $clu3 = $datos[42] ?? null;
            $clu4 = $datos[43] ?? null;
            $clu5 = $datos[44] ?? null;
            $clu6 = $datos[45] ?? null;
            $clu7 = $datos[46] ?? null;
            $clu8 = $datos[47] ?? null;
            $clu9 = $datos[48] ?? null;
            $clu10 = $datos[49] ?? null;
            $clu11 = $datos[50] ?? null;
            $clu12 = $datos[51] ?? null;
            $clu13 = $datos[52] ?? null;
            $clu14 = $datos[53] ?? null;
            $clu15 = $datos[54] ?? null;
            $clu16 = $datos[55] ?? null;
            $clu17 = $datos[56] ?? null;
            $clu18 = $datos[57] ?? null;
            $clu19 = $datos[58] ?? null;
            $clu20 = $datos[59] ?? null;
            $clu21 = $datos[60] ?? null;
            $clu22 = $datos[61] ?? null;
            $clu23 = $datos[62] ?? null;
            $clu24 = $datos[63] ?? null;
            $clu25 = $datos[64] ?? null;
            $clu26 = $datos[65] ?? null;
            $clu27 = $datos[66] ?? null;
            $clu28 = $datos[67] ?? null;
            $clu29 = $datos[68] ?? null;
            $clu30 = $datos[69] ?? null;
            $clu31 = $datos[70] ?? null;
            $clu32 = $datos[71] ?? null;
            $clu33 = $datos[72] ?? null;
            $clu34 = $datos[73] ?? null;
            $clu35 = $datos[74] ?? null;
            $clu36 = $datos[75] ?? null;
            $clu37 = $datos[76] ?? null;
            $clu38 = $datos[77] ?? null;
            $clu39 = $datos[78] ?? null;
			
			//validar si existe un registro en SireRceItem
            //ids de la tabla sireRceItem: id_contribuyente, tipo_envio_sunat, periodo_anio, periodo_mes, num_ticket, tipo_cp_doc, serie_cdp, nro_cp_doc_inicial, nro_cp_doc_final, tipo_doc_identidad, nro_doc_identidad
            $sire_rce_item = SireRceItem::findFirst(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: AND num_ticket = :num_ticket: AND tipo_cp_doc = :tipo_cp_doc: AND serie_cdp = :serie_cdp: AND nro_cp_doc_inicial = :nro_cp_doc_inicial: AND nro_final_rango = :nro_final_rango: AND tipo_doc_identidad = :tipo_doc_identidad: AND nro_doc_identidad = :nro_doc_identidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodo_mes, 'num_ticket' => $num_ticket, 'tipo_cp_doc' => $tipo_cp_doc, 'serie_cdp' => $serie_cdp, 'nro_cp_doc_inicial' => $nro_cp_doc_inicial, 'nro_final_rango' => $nro_final_rango, 'tipo_doc_identidad' => $tipo_doc_identidad, 'nro_doc_identidad' => $nro_doc_identidad)));
			
			if(!$sire_rce_item) {
				// Crear y configurar el objeto SireRceItem
				$sire_rce_item = new SireRceItem();
                $sire_rce_item->id_contribuyente = $id_contribuyente;
                $sire_rce_item->tipo_envio_sunat = $tipo_envio_sunat;
                $sire_rce_item->periodo_anio = $periodo_anio;
                $sire_rce_item->periodo_mes = $periodo_mes;
                $sire_rce_item->num_ticket = $num_ticket;

                $sire_rce_item->ruc = $ruc;
                $sire_rce_item->razon_social = $razon_social;
                $sire_rce_item->periodo = $periodo;
                $sire_rce_item->car_sunat = $car_sunat;
                $sire_rce_item->fecha_emision = $this->dia_mes_anio_to_date($fecha_emision);
                $sire_rce_item->fecha_vcto_pago = $this->dia_mes_anio_to_date($fecha_vcto_pago);
                $sire_rce_item->tipo_cp_doc = $tipo_cp_doc;
                $sire_rce_item->serie_cdp = $serie_cdp;
                $sire_rce_item->anio = $anio;
                $sire_rce_item->nro_cp_doc_inicial = $nro_cp_doc_inicial;
                $sire_rce_item->nro_final_rango = $nro_final_rango;
                $sire_rce_item->tipo_doc_identidad = $tipo_doc_identidad;
                $sire_rce_item->nro_doc_identidad = $nro_doc_identidad;
                $sire_rce_item->apellidos_nombres_razon_social = $apellidos_nombres_razon_social;
                $sire_rce_item->bi_gravado_dg = $this->convertirTextoADecimal($bi_gravado_dg);
                $sire_rce_item->igv_ipm_dg = $this->convertirTextoADecimal($igv_ipm_dg);
                $sire_rce_item->bi_gravado_dgng = $this->convertirTextoADecimal($bi_gravado_dgng);
                $sire_rce_item->igv_ipm_dgng = $this->convertirTextoADecimal($igv_ipm_dgng);
                $sire_rce_item->bi_gravado_dng = $this->convertirTextoADecimal($bi_gravado_dng);
                $sire_rce_item->igv_ipm_dng = $this->convertirTextoADecimal($igv_ipm_dng);
                $sire_rce_item->valor_adq_ng = $this->convertirTextoADecimal($valor_adq_ng);
                $sire_rce_item->isc = $this->convertirTextoADecimal($isc);
                $sire_rce_item->icbper = $this->convertirTextoADecimal($icbper);
                $sire_rce_item->otros_trib_cargos = $this->convertirTextoADecimal($otros_trib_cargos);
                $sire_rce_item->total_cp = $this->convertirTextoADecimal($total_cp);
                $sire_rce_item->moneda = $moneda;
                $sire_rce_item->tipo_cambio = $tipo_cambio;
                $sire_rce_item->fecha_emision_doc_modificado = $this->dia_mes_anio_to_date($fecha_emision_doc_modificado);
                $sire_rce_item->tipo_cp_modificado = $tipo_cp_modificado;
                $sire_rce_item->serie_cp_modificado = $serie_cp_modificado;
                $sire_rce_item->cod_dam_dsi = $cod_dam_dsi;
                $sire_rce_item->nro_cp_modificado = $nro_cp_modificado;
                $sire_rce_item->clasif_bss_sss = $clasif_bss_sss;
                $sire_rce_item->id_proyecto_operadores = $id_proyecto_operadores;
                $sire_rce_item->porcpart = $porcpart;
                $sire_rce_item->imb = $imb;
                $sire_rce_item->car_orig_ind_e_o_i = $car_orig_ind_e_o_i;
                $sire_rce_item->detraccion = $detraccion;
                $sire_rce_item->tipo_de_nota = $tipo_de_nota;
                $sire_rce_item->est_comp = $est_comp;
                $sire_rce_item->clu1 = $clu1 ?? null;
                $sire_rce_item->clu2 = $clu2 ?? null;
                $sire_rce_item->clu3 = $clu3 ?? null;
                $sire_rce_item->clu4 = $clu4 ?? null;
                $sire_rce_item->clu5 = $clu5 ?? null;
                $sire_rce_item->clu6 = $clu6 ?? null;
                $sire_rce_item->clu7 = $clu7 ?? null;
                $sire_rce_item->clu8 = $clu8 ?? null;
                $sire_rce_item->clu9 = $clu9 ?? null;
                $sire_rce_item->clu10 = $clu10 ?? null;
                $sire_rce_item->clu11 = $clu11 ?? null;
                $sire_rce_item->clu12 = $clu12 ?? null;
                $sire_rce_item->clu13 = $clu13 ?? null;
                $sire_rce_item->clu14 = $clu14 ?? null;
                $sire_rce_item->clu15 = $clu15 ?? null;
                $sire_rce_item->clu16 = $clu16 ?? null;
                $sire_rce_item->clu17 = $clu17 ?? null;
                $sire_rce_item->clu18 = $clu18 ?? null;
                $sire_rce_item->clu19 = $clu19 ?? null;
                $sire_rce_item->clu20 = $clu20 ?? null;
                $sire_rce_item->clu21 = $clu21 ?? null;
                $sire_rce_item->clu22 = $clu22 ?? null;
                $sire_rce_item->clu23 = $clu23 ?? null;
                $sire_rce_item->clu24 = $clu24 ?? null;
                $sire_rce_item->clu25 = $clu25 ?? null;
                $sire_rce_item->clu26 = $clu26 ?? null;
                $sire_rce_item->clu27 = $clu27 ?? null;
                $sire_rce_item->clu28 = $clu28 ?? null;
                $sire_rce_item->clu29 = $clu29 ?? null;
                $sire_rce_item->clu30 = $clu30 ?? null;
                $sire_rce_item->clu31 = $clu31 ?? null;
                $sire_rce_item->clu32 = $clu32 ?? null;
                $sire_rce_item->clu33 = $clu33 ?? null;
                $sire_rce_item->clu34 = $clu34 ?? null;
                $sire_rce_item->clu35 = $clu35 ?? null;
                $sire_rce_item->clu36 = $clu36 ?? null;
                $sire_rce_item->clu37 = $clu37 ?? null;
                $sire_rce_item->clu38 = $clu38 ?? null;
                $sire_rce_item->clu39 = $clu39 ?? null;

				try {
					if(!$sire_rce_item->save()) {
						$msg = '';
						foreach ($sire_rce_item->getMessages() as $message) {
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
    
    public function sireComprasListaPeriodosAction() {
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
			$tipo_sire = 'compras';

			$sire_periodo = SirePeriodo::findFirst(array("id_contribuyente = :id_contribuyente: AND tipo_envio_sunat = :tipo_envio_sunat: AND mes_anio = :mes_anio: and tipo_sire = 'compras'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'tipo_envio_sunat' => $tipo_envio_sunat, 'mes_anio' => $mes_anio)));
			if($sire_periodo) {
				echo $sire_periodo->response;
				exit();
			}

			$data_api = array(
				"token" 				=> $this->token_user,
				"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
				"accion" 				=> "consultar_lista_periodos_sire_compras"
			);

			$url = "https://facturalahoy.com/api/apisire/propuesta_compras";
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
				$sire_periodo->tipo_sire = 'compras';
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

    public function sireComprasGetTicketAction() {
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

			$sire_rce = SireRce::findFirst(array("id_contribuyente = :id_contribuyente: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodotributario), "order" => "fecha_registro DESC"));

			if($sire_rce) {
				$fecha_registro_ultimo_ticket = $sire_rce->fecha_registro;
				if(!$this->verificarFechaRegistro($fecha_registro_ultimo_ticket)) {
					$resp['respuesta'] = 'ok';
					$resp['titulo'] = 'Éxito';
					$resp['mensaje'] = 'Se obtuvo el ticket correctamente!';
					$resp['num_ticket'] = $sire_rce->num_ticket;
					echo json_encode($resp);
					exit();
				}
			}
			
			$data_api = array(
				"token" 				=> $this->token_user,
				"credenciales_sunat" 	=> $this->get_credenciales_sunat($usuario->id_contribuyente),
				"accion" 				=> "get_ticket_to_descargar_propuesta_compras",
				"periodotributario" 	=> $periodotributario
			);

			$url = "https://facturalahoy.com/api/apisire/propuesta_compras";
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

			$sire_rce = SireRce::findFirst(array("id_contribuyente = :id_contribuyente: AND periodo_anio = :periodo_anio: AND periodo_mes = :periodo_mes: and num_ticket = :num_ticket:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'periodo_anio' => $periodo_anio, 'periodo_mes' => $periodotributario, 'num_ticket' => $num_ticket)));
			if(!$sire_rce) {
				$sire_rce = new SireRce();
				$sire_rce->id_contribuyente = $usuario->id_contribuyente;
				$sire_rce->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
				$sire_rce->periodo_anio = $periodo_anio;
				$sire_rce->periodo_mes = $periodotributario;
				$sire_rce->num_ticket = $num_ticket;
				$sire_rce->fecha_registro = date('Y-m-d H:i:s');

				try {
					if(!$sire_rce->save()) {
						$msg = '';
						foreach ($sire_rce->getMessages() as $message) {
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