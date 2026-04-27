<?php 
Class Themecotizacionpdf {
	public function get_html_cotizacion($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal) {
		
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

		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
		}
		 
		$img_logo = $ruta_base.$img_logo;
		setlocale(LC_MONETARY, 'es_PE');

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}
		
		$items_detalle_html = '';
		foreach($detalle as $item) {
			if (strpos($item['TEXT_TIPO_OPERACION_DET'], '[') !== false) {
				$text_afectacion = $this->get_string_between($item['TEXT_TIPO_OPERACION_DET'], '[', ']');
			} else {
				if (strpos($item['TEXT_TIPO_OPERACION_DET'], '-') !== false) {
					$text_afectacion = explode(' - ', $item['TEXT_TIPO_OPERACION_DET'])[0];
				} else {
					$text_afectacion = $item['TEXT_TIPO_OPERACION_DET'];
				}
			}

			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items" style="width: 50px;">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.nl2br($item['DESCRIPCION_DET']).'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td class="cabecera_detalle_items" style="width: 100px;">'.$text_afectacion.'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}

		$resumen = '
		<tr>
			<td class="texto_general" style="padding: 0px !important;">Gravada:</td>
			<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="texto_general" style="padding: 0px !important;">Inafecto:</td>
				<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="texto_general" style="padding: 0px !important;">Exonerado:</td>
				<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="texto_general" style="padding: 0px !important;">Gratuito:</td>
				<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="texto_general" style="padding: 0px !important;">Exportación:</td>
				<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="texto_general" style="padding: 0px !important;">ICBPER:</td>
				<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="texto_general" style="padding: 0px !important;">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="texto_general" style="padding: 0px !important;">Descuento Total:</td>
			<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="texto_general" style="padding: 0px !important;">Total:</td>
			<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';

		if($documento->id_codigomoneda == 'USD') {
			$resumen = $resumen.'
			<tr>
				<td class="texto_general" style="padding: 0px !important;">T.C. ('.$documento->tipo_cambio_sunat.'): </td>
				<td style="font-family: Arial Narrow, Arial, sans-serif; padding: 0px !important;" class="text-right">
					<span class="simbolo_moneda">S/. </span> '.round($documento->tipo_cambio_sunat*$documento->total, 2).'
				</td>
			</tr>
			';
		}

		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '';
		}

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':$cliente->direccion_fiscal;
		if(isset($documento->dir_destino) && !empty($documento->dir_destino)) {
			$direccion_fiscal = $documento->dir_destino.' '.$ubigeo_ubicacion;
		}
		
		$nota_documento = '';
		if(!empty($documento->nota)) {
			$nota_documento = '<br /><strong>Observación: </strong><br />'.nl2br($documento->nota).'<br />';
		}

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<tr>
				<td class="datos_cliente" style="width: 50px !important;">
					N° Placa
				</td>
				<td class="datos_cliente" colspan="3">: '.$documento->transporte_nro_placa.'</td>
			</tr>
			';
		} else {
			$nro_placa = '';
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

				.text-right {
					text-align: right;
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
							<p style="font-weight: 600;">COTIZACIÓN</p>
							<p>'.$this->zero_fill($documento->numero_comprobante, 6).'</p>
						</td>
					</tr>
				</tbody>
			</table>';

			if(!empty($sucursal->txt_pdf_a4_1)) {
			$html = $html.'
			<table>
				<tbody>
					<tr>
						<td>
							<p>'.$sucursal->txt_pdf_a4_1.'</p>
						</td>
					</tr>
				</tbody>
			</table>';
			}

			$html = $html.'

			<div style="text-align:center;">
				<table style="margin: auto; border-collapse: collapse; padding-bottom: 5px !important;  padding-top: 5px !important;">
					<tbody>
						<tr>
							<td class="datos_cliente" style="width: 50px !important;">
								Cliente
							</td>
							<td class="datos_cliente" colspan="3">: '.ucwords($cliente->razon_social).'</td>
						</tr>
						<tr>
							<td class="datos_cliente"  style="width: 50px !important;">
								Num.Doc.:
							</td>
							<td class="datos_cliente"  colspan="3">: '.$cliente->num_doc.'</td>
						</tr>
						<tr>
							<td class="datos_cliente" style="width: 50px !important;">
								Dirección
							</td>
							<td class="datos_cliente" colspan="3">: '.ucwords(strtolower($direccion_fiscal)).'</td>
						</tr>
						'.$nro_placa.'
					</tbody>
				</table>
			</div>

			<div style="text-align:center; margin-top: 10px;">
				<table style="margin: auto; border: 1px solid black; border-collapse: collapse;">
					<tbody>
						<tr>
							<td class="cabecera_detalle_1">FECHA DE EMISIÓN</td>
							<td class="cabecera_detalle_1">CONDICIÓN DE PAGO</td>
							<td class="cabecera_detalle_1">TIPO DE MONEDA</td>
							<td class="cabecera_detalle_1">NÚMERO DE GUÍA</td>
							<td class="cabecera_detalle_1">ORDEN DE COMPRA</td>
						</tr>
						<tr>
							<td class="cabecera_detalle_2">'.date("d-m-Y / H:i A", strtotime($documento->fecha_comprobante)).'</td>
							<td class="cabecera_detalle_2">'.$nombre_condicion_pago.'</td>
							<td class="cabecera_detalle_2">'.$sunatmoneda->nombre.'</td>
							<td class="cabecera_detalle_2"></td>
							<td class="cabecera_detalle_2"></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<table cellpadding="0" cellspacing="0"  style="margin-top: 10px; border: 1px solid black; border-collapse: collapse;">
				<tbody>
					<tr>
						<td class="cabecera_detalle" style="width: 50px;">CANT.</td>
						<td class="cabecera_detalle">DESCRIPCIÓN</td>
						<td class="cabecera_detalle" style="width: 80px;">PRECIO</td>
						<td class="cabecera_detalle" style="width: 50px;">UNID/MED</td>
						<td class="cabecera_detalle" style="width: 50px;">AFECT.IGV</td>
						<td class="cabecera_detalle" style="width: 80px;">IMPORTE</td>
					</tr>
					'.$items_detalle_html.'
					<tr style="border: none !important;">
						<td class="cabecera_detalle" style="text-align: left !important; font-size: 10px; " colspan="6">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
					</tr>';

					if(!empty($sucursal->txt_pdf_a4_2)) {
						$html = $html.'
						<tr style="border: none !important;">
							<td class="cabecera_detalle" style="text-align: left !important; font-size: 10px; " colspan="6">'.$sucursal->txt_pdf_a4_2.'</td>
						</tr>';
					}

				$html = $html.'
				</tbody>
			</table>
			
			<div class="tablageneral tablacostos" style="width: 100%; padding:3px;"> 
				<table class="table">
					<tbody>
						<tr>
							<td style="width:400px; vertical-align: top;">
								<table class="table">
									<tbody>
										<tr>
											<th style="width:130px;">
												<img style="width: 120px; margin-bottom: 11px;" src="'.$ruta_qr.'" />
											</th>
											<td style="width:250px; font-family: Arial Narrow, Arial, sans-serif;">
												<span style="font-size: 11px">VENDEDOR: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</span><br />
												<span style="font-size: 13px">'.$nota_documento.'</span>
											</td>
										</tr>
										'.$aviso_texto_pruebas.'
									</tbody>
								</table>
							</td>
							<td style="width:260px; vertical-align: top;">
								<span style="text-align: left !important; font-family: Arial Narrow, Arial, sans-serif; font-weight: bold;">RESUMEN:</span>
								<table style="width: 100%;">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>';

			if(!empty($sucursal->txt_pdf_a4_3)) {
			$html = $html.'
			<table>
				<tbody>
					<tr>
						<td>
							<p>'.$sucursal->txt_pdf_a4_3.'</p>
						</td>
					</tr>
				</tbody>
			</table>';
			}

			$html = $html.$html_cuentas_corrientes.'
		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	function get_html_cotizacion_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $data_patrocinador, $vendedor, $sucursal) {
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

			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr class="detalletable">
				<td class="texto_11" WIDTH="30">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td class="texto_11" WIDTH="70">'.$item['DESCRIPCION_DET'].' - '.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td class="texto_11" WIDTH="45">'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td class="texto_11" WIDTH="45">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
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
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '';
		}

		$nota_documento = '';
		if(!empty($documento->nota)) {
			$nota_documento = '<br /><strong>Observación: </strong><br />'.$documento->nota.'<br />';
		}

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<tr>
				<td align="center" class="texto_12">N° Placa: '.$documento->transporte_nro_placa.'<td>
			</tr>
			';
		} else {
			$nro_placa = '';
		}

		$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':ucwords(strtolower($cliente->direccion_fiscal));
		if(isset($documento->dir_destino) && !empty($documento->dir_destino)) {
			$direccion_fiscal = ucwords(strtolower($documento->dir_destino)).' '.$ubigeo_ubicacion;
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
						<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" /> Telf.: '.$sucursal->telefono.'<br />
						<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/email.svg" style="width: 12px;" /> Email: '.$sucursal->email;

						if(!empty($sucursal->sitio_web)) {
							$html = $html.'<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/website.svg" style="width: 12px;" /> Website: '.$sucursal->sitio_web;
						}
						$html = $html.'
					<td>
				</tr>';

				if(!empty($sucursal->txt_pdf_ticket_1)){
				$html = $html.'
				<tr>
					<td align="center" class="texto_12">'.html_entity_decode($sucursal->txt_pdf_ticket_1).'<td>
				</tr>';
				}

				$html = $html.'
				<tr>
					<td><hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px; font-family: Arial Narrow, Arial, sans-serif; font-size: 12px;" /></td>
				</tr>
				<tr>
					<td align="center"  class="texto_14_bold">COTIZACIÓN<td>
				</tr>
				<tr>
					<td align="center" class="texto_14_bold">'.$this->zero_fill($documento->numero_comprobante, 6).'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">Fecha de Emisión: '.date("d-m-Y / H:i A", strtotime($documento->fecha_comprobante)).'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">
					Señor (es): '.ucwords($cliente->razon_social).'
					<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">N° Doc.: '.$cliente->num_doc.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">Direc.: '.$direccion_fiscal.'<td>
				</tr>
				'.$nro_placa.'
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
				</tr>';

				if(!empty($sucursal->txt_pdf_ticket_2)){
				$html = $html.'
				<tr>
					<td align="center" class="texto_10">'.html_entity_decode($sucursal->txt_pdf_ticket_2).'<td>
				</tr>';
				}

				$html = $html.'
				<tr>
					<td align="center"><img style="width: 95px; margin: 10px !important;" src="'.$ruta_qr.'" /><td>
				</tr>
				<tr>
					<td align="center"  class="texto_10">
					Representación Impresa de una Cotización <td>
				</tr>
				<tr>
					<td align="center" class="texto_10">
						<span>VENDEDOR: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</span>
						<br />
						<span style="font-size: 13px">'.$nota_documento.'</span>
					</td>
				</tr>';

				if(!empty($sucursal->txt_pdf_ticket_3)){
				$html = $html.'
				<tr>
					<td align="center" class="texto_10">'.html_entity_decode($sucursal->txt_pdf_ticket_3).'<td>
				</tr>';
				}

				$html = $html.'
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