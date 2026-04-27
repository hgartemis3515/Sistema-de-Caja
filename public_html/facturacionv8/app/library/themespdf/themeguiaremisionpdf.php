<?php 
Class Themeguiaremisionpdf {
	public function get_html_guiaremision($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $ubigeo_partida, $ubigeo_destino, $data_patrocinador, $vendedor, $sucursal) {
		$ruta_base = '/home/humbertoguadalup/public_html';

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 80px; height: 80px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}

		$img_logo = $ruta_base.$img_logo;
		setlocale(LC_MONETARY, 'es_PE');

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}

		$items_detalle_html = '
			
			';
			

		$resumen = '
		<tr>
			<th style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;">Gravada:</th>
			<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
				<span class="simbolo_moneda">USD </span> 879
			</td>
		</tr>
		';

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$array_docs_referencia = json_decode($documento->array_docs_referencia);
		$lista_docs_referencia ='';
		foreach($array_docs_referencia as $doc_referencia) {
			$lista_docs_referencia = $lista_docs_referencia.$doc_referencia->serie_comprobante.'-'.$doc_referencia->numero_comprobante.' ';
		}

		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		if(!$ubigeo_partida) {
			$ubigeo_ubicacion_partida = '';
		} else {
			$ubigeo_ubicacion_partida = $ubigeo_partida->distrito.', '.$ubigeo_partida->provincia.', '.$ubigeo_partida->departamento;
		}

		if(!$ubigeo_destino) {
			$ubigeo_ubicacion_llegada = '';
		} else {
			$ubigeo_ubicacion_llegada = $ubigeo_destino->distrito.', '.$ubigeo_destino->provincia.', '.$ubigeo_destino->departamento;
		}

		$items_detalle_html = '';
		foreach($detalle as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items" style="width: 50px;">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['PESO_DET'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;"></td>
			</tr>
			';
		}
		
		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html lang="es-ES" prefix="og: http://ogp.me/ns#" xmlns="https://www.w3.org/1999/xhtml">
		<head>   
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
			<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet" type="text/css">
			<link href="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/font-awesome-4.6.1/css/font-awesome.css" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">  
			<link href="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/css/estilo.css" rel="stylesheet">
			<style>
				.cabecera_detalle {
					text-align: center; 
					font-family: Arial Narrow, Arial, sans-serif;
					font-size: 11px; 
					border: 1px solid black; 
					border-collapse: collapse; 
					font-weight: bold;
				}

				.cabecera_documento {
					font-family: Arial Narrow, Arial, sans-serif;
					padding: 0px !important;
					width: 190px !important; 
					border: solid 1px black; 
					font-size: 15px !important; 
					line-height: 25px !important; 
					margin: 5px 0 !important;
				}

				.cabecera_detalle_items {
					text-align: center; 
					padding: 5px 0px 5px 3px !important; 
					font-family: Arial Narrow, Arial, sans-serif;
					font-size: 11px; 
					border: 1px solid black; 
					border-collapse: collapse;
					border: none !important;
				}

				.cabecera_detalle_1 {
					text-align: center; 
					font-family: Arial Narrow, Arial, sans-serif; 
					padding: 1px 0px 0px 3px !important; 
					width: 20% !important; 
					font-size: 10px; 
					border: 1px solid black; 
					border-collapse: collapse; 
					font-weight: bold;
				}

				.cabecera_detalle_2 {
					text-align: center; 
					font-family: Arial Narrow, Arial, sans-serif; 
					padding: 13px 0px 13px 3px !important; 
					width: 20% !important; 
					font-size: 10px; border: 1px solid black; 
					border-collapse: collapse; 
					font-weight: bold;
				}

				.tabla_cuentas {
					padding: 1px 0px 0px 3px !important;
					width: 25% !important;
					font-size: 10px;
					border: 1px solid black;
					border-collapse: collapse;
				}

				.datos_cliente {
					font-family: Arial Narrow, Arial, sans-serif; 
					padding: 1px 0px 0px 3px !important; 
					font-size: 14px;
				}
			</style>
		</head>
		<body>    
			<table class="tablareceipt">
				<tbody>
					<tr>
						<td style="padding: 0px !important; width: 181px !important;">
							<img '.$style_logo.' src="'.$img_logo.'" />
						</td>
						<td style="width: 300px !important;  line-height: 22px !important;">
							<p style="font-family: Arial Narrow, Arial, sans-serif; font-size: 17px;">'.$contribuyente->nombre_comercial.'</p>
							<p style="font-family: Arial Narrow, Arial, sans-serif; text-transform: none !important;">'.$sucursal->direccion.'<br />'.$ubigeo_ubicacion.'</p>
							<p style="font-family: Arial Narrow, Arial, sans-serif; text-transform: none !important;"><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" /> Telf.: '.$sucursal->telefono.'</p>
							<p style="font-family: Arial Narrow, Arial, sans-serif; text-transform: none !important;"><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/email.svg" style="width: 12px;" /> '.$sucursal->email.'</p>';
							if(!empty($sucursal->sitio_web)) {
								$html = $html.'<p style="font-family: Arial Narrow, Arial, sans-serif; text-transform: none !important;"><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/website.svg" style="width: 12px;" /> '.$sucursal->sitio_web.'</p>';
							}
							$html = $html.'
						</td>
						<td class="cabecera_documento">
							<p>R.U.C. '.$contribuyente->ruc.'</p>                    
							<p style="font-weight: 600;">GUÍA DE REMISIÓN ELECTRÓNICA</p>
							<p>'.$documento->serie_comprobante.' - '.$this->zero_fill($documento->numero_comprobante, 6).'</p>
						</td>
					</tr>
				</tbody>
			</table>

			<div style="text-align:center; margin-top: 10px;">
				<table style="margin: auto; border: 1px solid black; border-collapse: collapse;">
					<tbody>
						<tr>
							<td class="cabecera_detalle_1">FECHA DE EMISIÓN</td>
							<td class="cabecera_detalle_1">FECHA DE TRASLADO</td>
							<td class="cabecera_detalle_1">DOCS. REFERENCIA</td>
							<td class="cabecera_detalle_1">MOTIVO TRASLADO</td>
							<td class="cabecera_detalle_1">MOD. TRANSPORTE</td>
						</tr>
						<tr>
							<td class="cabecera_detalle_2">'.date("d-m-Y / H:i A", strtotime($documento->fecha_registro)).'</td>
							<td class="cabecera_detalle_2">'.date("d-m-Y", strtotime($documento->fecha_traslado)).'</td>
							<td class="cabecera_detalle_2">'.$lista_docs_referencia.'</td>
							<td class="cabecera_detalle_2">'.$documento->motivo_traslado.'</td>
							<td class="cabecera_detalle_2">'.$documento->modalidad_traslado.'</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div style="text-align:center; margin-top: 10px;">
				<table style="margin: auto; border: 1px solid black; border-collapse: collapse;">
					<tbody>
						<tr>
							<td class="cabecera_detalle_1">DIRECCIÓN DE PARTIDA</td>
							<td class="cabecera_detalle_1">DIRECCIÓN DE LLEGADA</td>
						</tr>
						<tr>
							<td class="cabecera_detalle_2">'.$documento->dir_partida.'<br />'.$ubigeo_ubicacion_partida.'</td>
							<td class="cabecera_detalle_2">'.$documento->dir_destino.'<br />'.$ubigeo_ubicacion_llegada.'</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div style="text-align:center; margin-top: 10px;">
				<table style="margin: auto; border: 1px solid black; border-collapse: collapse;">';

		if($documento->id_modalidadtraslado == '01') {
			//transporte público
			$html = $html.'
					<tbody>
						<tr>
							<td class="cabecera_detalle_1">DESTINATARIO</td>
							<td class="cabecera_detalle_1">RUC TRANSPORTE</td>
							<td class="cabecera_detalle_1">RAZÓN SOCIAL TRANSP.</td>
						</tr>
						<tr>
							<td class="cabecera_detalle_2">'.ucwords($cliente->razon_social).' - Num.Doc.: '.$cliente->num_doc.'</td>
							<td class="cabecera_detalle_2">'.$documento->nro_documento_transporte.'</td>
							<td class="cabecera_detalle_2">'.$documento->razon_social_transporte.'</td>
						</tr>
					</tbody>
					';
		} else {
			//transporte privado
			$html = $html.'
			<tbody>
				<tr>
					<td class="cabecera_detalle_1">DESTINATARIO</td>
					<td class="cabecera_detalle_1">UNIDAD DE TRANSPORTE</td>
					<td class="cabecera_detalle_1">DATOS CONDUCTORES</td>
				</tr>
				<tr>
					<td class="cabecera_detalle_2">'.ucwords($cliente->razon_social).' - Num.Doc.: '.$cliente->num_doc.'</td>
					<td class="cabecera_detalle_2">'.$documento->transporte_nro_placa.'</td>
					<td class="cabecera_detalle_2">'.$documento->razon_social_transporte.' DNI: '.$documento->nro_documento_transporte.'</td>
				</tr>
			</tbody>
			';
		}
		$html = $html.'
				</table>
			</div>
			
			<div>
			<strong>REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </strong>
				<table cellpadding="0" cellspacing="0"  style="margin-top: 10px; border: 1px solid black; border-collapse: collapse;">
					<tbody>
						<tr>
							<td class="cabecera_detalle" style="width: 50px;">CANT.</td>
							<td class="cabecera_detalle">PRODUCTO</td>
							<td class="cabecera_detalle" style="width: 50px;">UNID/MED</td>
							<td class="cabecera_detalle" style="width: 50px;">PESO</td>
							<td class="cabecera_detalle" style="width: 80px;">COST.MIN.TRASL.</td>
						</tr>
						'.$items_detalle_html.'
						<tr style="border: none !important;">
							<td class="cabecera_detalle" style="text-align: left !important; font-size: 10px; " colspan="5">Peso Bruto (KGM): '.$documento->peso.'</td>
						</tr>
						<tr style="border: none !important;">
							<td class="cabecera_detalle" style="text-align: left !important; font-size: 10px; " colspan="5">Numero de Bulltos o Pallets: '.$documento->numero_paquetes.'</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div style="text-align:center; margin-top: 10px;">
				<table style="margin: none; border: 0px !important;">
					<tbody>
						<tr>
							<td class="cabecera_detalle_2"  style="margin: none; border: 0px !important;">
								<img style="width: 90px; margin-bottom: 11px;" src="'.$ruta_qr.'" />
							</td>
							<td style="width:250px; font-family: Arial Narrow, Arial, sans-serif;">
								'.$documento->nota.'
								Consulte su documento electrónico en:
								<span style="font-size: 10px">'.$ruta_consulta_documento.'</span>
								<br>
								<span style="font-size: 11px">HASH: '.$documento->hash_cpe.'</span>
								<br />
								<span style="font-size: 11px">VENDEDOR: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	function get_html_factura_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $data_patrocinador, $vendedor, $sucursal) {
		if($modo != 'prueba') {
			$ruta_base = '/home/humbertoguadalup/public_html';
		} else {
			$ruta_base = 'https://facturalahoy.com';
		}
		
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 80px; height: 80px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		setlocale(LC_MONETARY, 'es_PE');

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}

		$items_detalle_html = '';
		foreach($detalle as $item) {

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);

			$items_detalle_html = $items_detalle_html.'
			<tr class="detalletable">
				<td class="texto_11" WIDTH="30">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td class="texto_11" WIDTH="70">'.$item['DESCRIPCION_DET'].' - '.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td class="texto_11" WIDTH="45">'.$sunatmoneda->simbolo.' '.$item['PRECIO_DET'].'</td>
				<td class="texto_11" WIDTH="45">'.$sunatmoneda->simbolo.' '.custom_money_format($sub_total_con_igv).'</td>
			</tr>
			';
		}

		$resumen = '
		<tr>
			<td align="right" class="texto_12">
				Gravada: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td align="right" class="texto_12">
					Inafecto: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td align="right" class="texto_12">
					Exonerado: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td align="right" class="texto_12">
				 Gratuito: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td align="right" class="texto_12">
				Exportación: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td align="right" class="texto_12">
				ICBPER: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td align="right" class="texto_12">
				IGV ('.$documento->porcentaje_igv.'%): <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td align="right" class="texto_12">
			 	Descuento Total: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td align="right" class="texto_12">
				Total: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$aviso_texto_pruebas = '';
		if($documento->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '
			<tr>
				<td style="width:250px; margin-top: 15px !important; font-size: 18px; color: red; font-weight: bold;" colspan="2" class="texto_12">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</td>
			</tr>
			';
		}


		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html lang="es-ES" prefix="og: http://ogp.me/ns#" xmlns="https://www.w3.org/1999/xhtml">
		<head>   
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>
		<style>
			@page { margin: 10px !important; }
			.texto_12 {
				font-family: Arial Narrow, Arial, sans-serif !important; 
				font-size: 12px !important;
			}

			.texto_14_bold {
				font-family: Arial Narrow, Arial, sans-serif !important; 
				font-size: 14px !important;
				font-weight: bold !important;
			}

			.texto_11 {
				font-family: Arial Narrow, Arial, sans-serif !important; 
				font-size: 11px !important;
			}

			.texto_10 {
				font-family: Arial Narrow, Arial, sans-serif !important; 
				font-size: 10px !important;
			}

			.text-right {
				text-align: right;
			}
		</style>
		<body> 
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="center"><img '.$style_logo.' src="'.$img_logo.'" /><td>
				</tr>
				<tr>
					<td align="center" class="texto_14_bold">'.$contribuyente->nombre_comercial.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">R.U.C.:'.$contribuyente->ruc.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">'.$sucursal->direccion.'<br /> '.$ubigeo_ubicacion.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">
						<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" /> Telf.: '.$contribuyente->telefono.'<br />
						<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/email.svg" style="width: 12px;" /> Email: '.$sucursal->email;

						if(!empty($sucursal->sitio_web)) {
							$html = $html.'<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/website.svg" style="width: 12px;" /> Website: '.$sucursal->sitio_web;
						}
						$html = $html.'
					<td>
				</tr>
				<tr>
					<td><hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px; font-family: Arial Narrow, Arial, sans-serif; font-size: 12px;" /></td>
				</tr>
				<tr>
					<td align="center"  class="texto_14_bold">FACTURA ELECTRÓNICA<td>
				</tr>
				<tr>
					<td align="center" class="texto_14_bold">'.$documento->serie_comprobante.' - '.$this->zero_fill($documento->numero_comprobante, 6).'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">Fecha de Emisión: '.date("d-m-Y / H:i A", strtotime($documento->fecha_registro)).'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">
					Señor (es): '.ucwords($cliente->razon_social).'
					<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">RUC N°: '.$cliente->num_doc.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">Direc.: '.ucwords(strtolower($cliente->direccion_fiscal)).'<td>
				</tr>
			</table>
			<hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" />
			<table cellpadding="0" cellspacing="1">
				<tbody>
					<tr class="texto_12">
						<td WIDTH="40">Cant.</td>
						<td WIDTH="70">Descripción</td>
						<td WIDTH="45">Precio</td>
						<td WIDTH="45">Importe</td>
					</tr>
					<tr>
						<td colspan="4">
							<hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" />
						</td>
					</tr>
					'.$items_detalle_html.'
				</tbody>
			</table>
			<hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" />
			<table width="100%"  cellpadding="0" cellspacing="0" style="margin-right: 10px;">
					'.$resumen.'
			</table>
			<p><hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" /></p>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="center"  class="texto_10">Importe en Letras: '.$documento->total_letras.'<td>
				</tr>
				<tr>
					<td align="center"><img style="width: 95px; margin: 10px !important;" src="'.$ruta_qr.'" /><td>
				</tr>
				<tr>
					<td align="center"  class="texto_10">
					Representación Impresa de la Factura Electrónica <br />
					Consulte su Documento en:<td>
				</tr>
				<tr>
					<td align="center" class="texto_10"> 
						'.$ruta_consulta_documento.'
						<br>
						HASH: '.$documento->hash_cpe.'
						<br />
						<span>VENDEDOR: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</span>
					</td>
				</tr>
				'.$aviso_texto_pruebas.'
			</table>
			<hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" />
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	public function zero_fill ($valor, $long = 0) {
		return str_pad($valor, $long, '0', STR_PAD_LEFT);
	}
}
?>