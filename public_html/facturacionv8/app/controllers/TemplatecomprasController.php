<?php
class TemplatecomprasController extends ControllerBase
{
    public function get_html_otro_doc_a4_1($cpe) {
        
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td align="center" class="text-center">'.$item['nro_item'].'</td>
				<td align="center" width="60px">'.$item['codigo'].'</td>
				<td align="center">'.($item['cantidad'] + 0).'</td>
				<td align="center">'.$item['unidad_medida'].'</td>
				<td class="text-left td_descripcion" style="width: 250px !important;">'.$item['descripcion'].'</td>
				<td class="textright-" width="100px">'.$item['simbolo_moneda'].' '.$item['precio'].'</td>
				<td class="textright-" width="100px">'.$item['simbolo_moneda'].' '.custom_money_format($item['sub_total']).'</</td>
			</tr>
			';
		}
		
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger text-center mt-3" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format(round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'
			</td>
		</tr>
		';
		
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 10px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			.tb_resumen_totales {
				width: 100%;
			}
			body{
				position: relative;
				font-size: 10px;
				font-family: arial, sans-serif;
				color: #000;
			}
			.codigo_qr p{
				font-size: 10px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 10px;
			}
			.table td, .table th {
				padding: .6rem;
				vertical-align: top;
				border: 0;
			
			}
			
			.table thead th {
				vertical-align: bottom;
				
			}
			table {
				font-size: 10px;
			}
			.table_detraccion, .table_percepcion{
				border: 1px solid #000;
			}
			.table_detraccion td, .table_percepcion td{
				border:none;
				padding: 5px;
				font-size: 10px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 10px;
				color: #000;
			}
			td, th {
				text-align: left;
				padding: 5px;
			}
			th {
				text-align: center;
			}
			.col-2 {
				width: 16.66666667%;
				float: left;
				padding: 0;
			}
			.col-3{
				width: 25%;
				float: left;
				padding: 0;
			}
			.col-4{
				width: 33.333333333333%;
				float: left;
				padding: 0;
			}
			.col-5 {
				width: 41.66666667%;
				float: left;
				padding: 0;
			}
			.col-6 {
				width: 50%;
				float: left;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}

			.col-8{
				width: 66.666667%;
				float:left;
			}
			.col-9 {
				width: 75%;
				float: left;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			
			.masthead {
				display: block;
				height: 180px;
			}
			.head-pg{
				position:relative;
			}
			
			';
		
			$html = $html.'
		
			.table-head p{
				line-height: 2;
			}
			.tb_resumen_totales td {
				padding: 2px 5px;
				border: solid #5f5f5f 1px;
				border-top: none;
				text-align: left;
			}
			.resumen_totales {
				height: 100px;
			}
			.spacing-lg {
				width: 60%;
			}
			/* Tablas */

			.table-cuentas {
				border-collapse: separate !important;
				border-spacing: 0;
				border-top: solid #000 1px;
				border-left: solid #000 1px;
				border-right: solid #000 1px;
				border-bottom: solid #000 1px!important;
			}
			.table-cuentas th {
				margin-bottom: 0;
			}
		
			.table-cuentas td{
				padding: 5px;
			}
			.table-cuentas td p{
				font-size: 9px;
			}
			.table-footer p {
				font-size: 15px;
			}
			.table-items-productos {
				margin: 0;
				border: 1px solid #000;
				border-bottom-right-radius: 0px!important;
			}
			.table-items-productos th {
				margin-bottom: 0;
			}
			.table-items-productos td {
				border: none;
				text-align: center;
				
			}
			
			.bg-main{
				background: #006cae;
			}
			.notas_doc{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-footer {
				width: 100%;
			}

			/* === Tabla Bordes === */
			.border-left{
				border-left: 1px solid #000!important;
			}
			.table-head {
				border: 1px solid #000;
			}

			.table-head{
				text-align: center;
				width: 100%;
				font-size: 11px!important;
			}
			.table-head td{
				padding: .8em 1em;
			}	
			.table-2 {
				border: 1px solid #000;
			}
			.table-2 td{
				padding: 1em;
			}
			.table-client{ 
				border: 1px solid #000;
				padding-top: 1px solid #000;
			}
			
			.bordered {
				border-collapse: separate !important;
				border-spacing: 0;
				-moz-border-radius: 12px;
				-webkit-border-radius: 12px;
				border-radius: 12px;
				width: 100%;

			}
			.bordered th {
				border-top: none;
			}
			.bordered td:first-child, .bordered th:first-child {
				border-left: none;
			}
			.bordered th:first-child {
				-moz-border-radius: 12px 0 0 0;
				-webkit-border-radius: 12px 0 0 0;
				border-radius: 12px 0 0 0;
			}
			.bordered th:last-child {
				-moz-border-radius: 0 12px 0 0;
				-webkit-border-radius: 0 12px 0 0;
				border-radius: 0 12px 0 0;
			}
			.bordered th:only-child{
				-moz-border-radius: 12px 12px 0 0;
				-webkit-border-radius: 12px 12px 0 0;
				border-radius: 12px 12px 0 0;
			}
			.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 12px;
				-webkit-border-radius: 0 0 0 12px;
				border-radius: 0 0 0 12px;
			}
			.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 12px 0;
				-webkit-border-radius: 0 0 12px 0;
				border-radius: 0 0 12px 0;
			
			}
			/* === Tabla 4 === */
			.table-4.bordered {
				border-collapse: separate !important;
				border-spacing: 0;
				-moz-border-radius: 6px;
				-webkit-border-radius: 6px;
				/* border-radius: 6px; */
				border-top-left-radius: 6px;
				border-top-right-radius: 6px;
				border-bottom-right-radius: 0px!important;
				border-bottom-left-radius: 6px;
			}
			.table-4.bordered th:first-child {
				-moz-border-radius: 6px 0 0 0;
				-webkit-border-radius: 6px 0 0 0;
				border-radius: 6px 0 0 0;
			}
			.table-4.bordered th:last-child {
				-moz-border-radius: 0 6px 0 0;
				-webkit-border-radius: 0 6px 0 0;
				border-radius: 0 6px 0 0;
			}
			.table-4.bordered th:only-child{
				-moz-border-radius: 6px 6px 0 0;
				-webkit-border-radius: 6px 6px 0 0;
				border-radius: 6px 6px 0 0;
			}
			.table-4.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 6px;
				-webkit-border-radius: 0 0 0 6px;
				border-radius: 0 0 0 6px;
			}
			.table-4.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 0px 0;
				-webkit-border-radius: 0 0 6px 0;
				border-radius: 0 0 6px 0;
			} 
			.table-head td {
				line-height: 2;
				font-size: 14px;
			}
			.tb_resumen_totales {
				width: 180px;
			}
			
			.table-items-productos .td_descripcion{ 
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-retencion td{
				font-size: 11px;
				text-align: center;
			}
		</style>
		<body>';
		
		$html = $html.'
			<div class="head-pg masthead w-100">
				<div class="col-8 text-center">
					<h5 class="font-weight-bold text-uppercase">'.$cpe['proveedor']['nom_comercial'].'</h5>
					<p style="font-size: 15px!important;">Dirección: '.$cpe['proveedor']['direccion'].'</p>
					<p style="font-size: 13px!important;">'.$cpe['proveedor']['detalle'].'</p>
				</div>
				<div class="col-4  mt-1">
					<table class="table-head bordered" style="font-size: 15px!important;">
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase">'.$cpe['proveedor']['tipo_doc'].': '.$cpe['proveedor']['num_doc'].'</td>
						</tr>
						<tr class="bg-main text-white">
							<td colspan="3" class="text-center font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</td>
						</tr>
						<tr>
							<td colspan="3" class="text-center">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
						</tr>
					</table>
				</div>
			</div>';
		
			
		$html = $html.'
		'.$text_pdf_1.'
		<table class="table-2 bordered mt-30 mb-3">
			<tbody class="text-center">
				<tr>
					<td class="text-center">
						<p class="font-weight-bold">Fecha de Emisión:</p>
						<p>'.$cpe['cabecera']['fecha_registro'].'</p> 
					</td>
					<td class="border-left text-center">
						<p class="font-weight-bold">Forma de Pago</p>
						<p>'.$cpe['cabecera']['tipo_compra'].'</p> 
					</td>
					<td class="border-left text-center" style="border-right: 1px solid #000">
						<p class="font-weight-bold">Moneda</p>
						<p>'.$cpe['cabecera']['moneda'].'</p> 
					</td>
					<td class="text-center">
						<p class="font-weight-bold">Guía de Remisión N°</p>
						<p></p> 
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table-client bordered mt-30 mb-3">
			<tbody>
				<tr style="border-bottom: 1px solid #000">
					<td class="spacing-lg" colspan="2" style="border-bottom: 1px solid #000">
						<p>
							<span class="font-weight-bold">'.$cpe['cliente']['siglas_doc'].': </span> '.$cpe['cliente']['ruc'].'
						</p>
					</td>
				</tr>
				<tr>
					<td class="spacing-lg" colspan="2" style="border-bottom: 1px solid #000">
						<p><span class="font-weight-bold">Razón Social: </span> '.ucwords($cpe['cliente']['razon_social']).'</p>
					</td>
				</tr>
				<tr>
					<td class="spacing-lg" colspan="2">
						'.(!empty($cpe['cliente']['direccion'])?'<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion'].'</p>':'-').'
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table-items-productos table-4 bordered">
			<tbody>
			
				<tr class="text-uppercase bg-main text-white">
					<th>N°</th>
					<th>Código</th>
					<th>Cant.</th>
					<th>Unid.</th>
					<th width="500px">Descripción</th>
					<th>P.U.</th>
					<th>Total</th>
				</tr>
				<tr>
					'.$items_detalle_html.'
				</tr>
				
			</tbody>
		</table>
		<div class="resumen_totales">
			<div class="col-9 pt-3 pr-3 notas_doc">
				<p><strong>Observaciones: </strong> '.$cpe['cabecera']['nota'].'</p>
			</div>
			<div class="col-3 float-right">
				<table class="tb_resumen_totales float-right">
					<tbody>
						'.$resumen.'
					</tbody>
				</table>
			</div>
		</div>
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
    }

	public function get_html_otro_doc_ticket_1($cpe) {
		$this->view->disable();
		
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr class="text-left">
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td align="center" width="150px">'.$item['unidad_medida'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.$item['precio'].'</</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['sub_total']).'</</td>
			</tr>
			<tr>
				<td colspan="6" >'.nl2br($item['descripcion']).'<br />Código: '.$item['codigo'].'</td>
			</tr>
			';

		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger text-center mt-3" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format(round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		$resumen = $resumen.'
		<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'</p>
		';
		
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				position:relative;
				line-height: 16px;
				font-size: 11px;

			}
			/* clases de bootstrap */
			.h6, h6 {
				font-size: 1rem;
				margin: 10px 0;
			}
			hr {
				margin-top: 1rem;
				margin-bottom: 1rem;
				border: 0;
				border-top: 1px solid rgba(0,0,0,.1);
			}
			.mt-2, .my-2 {
				margin-top: 0.5rem!important;
			}
			.mb-2, .my-2 {
				margin-bottom: 0.5rem!important;
			}
			.mt-3, .my-3 {
				margin-top: 1rem!important;
			}
			.mb-4, .my-4 {
				margin-bottom: 1.5rem!important;
			}
			table {
				border-collapse: collapse;
			}
			
			.text-left{
				text-align: left;
			}
			.text-right{
				text-align: right;
			}
			.text-center{
				text-align: center;
			}
			.font-weight-bold {
				font-weight: bold;
			}
			.text-uppercase{
				text-transform: uppercase;
			}
			.text-danger {
				color: #dc3545!important;
			}
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 3em;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg_ticket.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				}';
			} else if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			} else if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/anulado_pg_ticket_prueba.png);
					z-index: -1;
				} ';
			}
			$html = $html.'
			
			.table td, .table th {
				padding: 0.75rem;
				vertical-align: top;
				border-top: 1px solid #000;
			}
			p{
				margin: 0;
				padding: 0;
				font-size: 11px;
			}
			table{
				width: 100%;
				font-size: 11px!important;
			}
			th{
				text-align: left;
				padding: 8px;
				border-top: 1px solid #000;
				border-bottom: 1px solid #000;
			}
			td{
				
				padding: 2px;
			}
			.border-table{
				border-bottom: 1px solid #000;
			}
			.padding-right-4{
				padding-right: 4em;
			} 
			.nota_doc{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-cuotas th{
				border-bottom: 1px solid #000;
				border-top: 1px solid #000;
				padding: 10px 3px;
				font-weight: bold;
			}
			.table-main-head .td_descripcion {
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 12px;
			}
		</style>
		<body>
			<div class="masthead text-center">';
				$html = $html.'
				<h6 class="text-uppercase font-weight-bold">Proveedor: '.$cpe['proveedor']['nom_comercial'].'</h6>
				<p class="text-uppercase font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].' Nro.: '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
			</div>
			<div class="mt-1">
				<p><br />'.$cpe['proveedor']['tipo_doc'].': '.$cpe['proveedor']['num_doc'].'</p>
				<p>Dirección: '.$cpe['proveedor']['direccion'].'</p>
				<p>'.$cpe['proveedor']['detalle'].'</p>
				<p>'.$cpe['proveedor']['detalle'].'</p>
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.$cpe['cabecera']['fecha_registro'].'</p>
				<p><span class="font-weight-bold">Forma de Pago: '.$cpe['cabecera']['tipo_compra'].'</p>
				<p>Moneda: '.$cpe['cabecera']['moneda'].'</p>
			</div>
			<div class="mt-1">
				<p><span class="font-weight-bold">Cliente: </span> '.ucwords($cpe['cliente']['razon_social']).'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['siglas_doc'].'</span> '.$cpe['cliente']['ruc'].'</p>
				<p><span class="font-weight-bold">Dirección: </span> '.(!empty($cpe['cliente']['direccion'])?'<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion'].'</p>':'-').'</p>
			</div>
			<div class="table-content mt-1">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Cantidad</th>
							<th>U.Medida</th>
							<th>Precio</th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
}
?>