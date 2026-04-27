<?php
class TemplatecpeController extends ControllerBase
{
	public function get_html_plantilla_a4_1($cpe) {  //ID: 1, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-left td_descripcion"><div style="width: 250px !important;">'.$item['descripcion'].'</div></td>
				<td  class="text-center">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td width="140px">'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right" idth="140px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
	
	
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
			<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';
		


		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';
	
		$resumen = $resumen.'
		<tr>
			<td>Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';
		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		<tr>
        			<td>Total a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
        			</td>
        		</tr>
				<tr>
        			<td>IGV a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total_igv'] - $cpe['totales_anticipos']['total_igv'], 2)).'
        			</td>
        		</tr>
        		';
		    }
		}

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2 mb-3">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mb-1">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		$aviso_masthead_prueba = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger text-center mt-3 font-weight-bold" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. ¡No tiene Validez!
				</p>
			';
			
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-size: 12px;
				margin-left: 0;
			
			}
			body{
				position: relative;
				font-size: 11px!important;
			}
			
			.display-none{
				display: none;
			}
			
			.table-no-border td, .table-no-border th {
				border: none!important;
				
			}
			.table td, .table th {
				padding: .55rem;
				vertical-align: top;
				border-top: 1px solid #5f5f5f;
				
			}
			.nota_doc td{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			table {
				font-size: 11px!important;				
			}
			
			.table-main{
				border: 1px solid #5f5f5f;
				margin-bottom: 0;
			}
			.table-main th{
				text-transform: uppercase;
			}

			.table-main td {
				border: 0;
				padding: 5px 5px 1px 5px;
				
			}
			.table-main .td_descripcion{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
			}
			td, th {
				border: 1px solid #5f5f5f;
				text-align: left;
				padding: 8px;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-8{
				width: 66.666667%;
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
			.no-border{margin-bottom: 0; }
			.no-border td, .no-border th {
				border: 0;
				
			}
			.masthead {
				display: block;
				position:relative;
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 900px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 900px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.table-head{
				padding: 25px 5px;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #5f5f5f;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.table-head p{
				line-height: 2;
				font-size: 14px;
			}
			.tb_resumen_totales td {
				padding: 2px;
				border: none;
			}
			.resumen_totales {
				height: 200px;
			}
			.table-cuentas{
				margin: auto;
			}
			.table-cuentas td{
				padding: 3px 5px;
			}
			.table-1 th {
				font-size: 10px;
			}
			.table-1 td{
				text-align: center!important;
			}
			.footer-content{
				position: absolute;
				bottom: 0;
			}
			.pb-3{
				padding-bottom: 20px;
			}

		</style>
		<body>';
		//Logo cuadrado
		if(empty($cpe['emisor']['logo_rectangular'])) {
				$height = "style='height: 190px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-3">
						<img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
					</div>
					<div class="col-5 text-center" style="padding:10px 5px">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>
					</div>
					<div class="col-4 p-2" style="padding:10px 5px">
						<div class="table-head">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			} else {
				$height = "style='height: 200px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-7 text-center" style="padding:10px 5px">
						<img  src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px;">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>

					</div>
					<div class="col-5 p-2">
						<div class="table-head">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			}
			$html = $html.'
			<div class="text-center">'.$cpe['sucursal']['txt_pdf_a4_1'].'</div>
			<div>
				<div class="single-date-client mb-3">
					<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
					<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
					'.$direccion_fiscal.'
					
					'.$descripcion_nota.'
					'.$documento_relacionado.'
					'.$fecha_vencimiento.'
				</div>	
			</div>
			<table class="table table-1 mt-1 text-center">
				<thead>
					<tr class="text-uppercase">
						<th>Fecha de emisión</th>
						<th>Forma de Pago</th>
						<th>Tipo moneda</th>
						<th>Número de guía</th>
						<th>Orden de Compra</th>
						<th>Número de Placa</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<tr>
						<td>'.$cpe['cabecera']['fecha_emision'].'</td>
						<td>'.$cpe['cabecera']['forma_de_pago'].'</td>
						<td>'.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						<td>'.$cpe['cabecera']['guia_remision'].'</td>
						<td>'.$cpe['cabecera']['orden_compra'].'</td>
						<td>'.$cpe['cabecera']['nro_placa'].'</td>
					</tr>
				</tbody>
			</table>
				
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th>Cant.</th>
						<th>Descripción</th>
						<th>Precio</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th>Importe</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="6" style="border: 1px solid #5f5f5f!important" class="text-uppercase font-weight-bold text-left">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
					</tr>
				</tbody>
			</table>
			<footer class="footer-resume">
				<table class="table no-border nota_doc">
					<tbody>
						<tr>
							<td width="66.666667%" class="pr-4">
								';
								if(!empty($cpe['cabecera']['documento_nota'])) {
								$html = $html.'
								<p><strong>Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
								';
								}
								$html = $html.'
								<div class="mt-3">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-3 mb-3">
									<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$cpe['sucursal']['txt_pdf_a4_2'].'
								</div>
							</td>
							<td width="33.333333333333%">
								<span style="text-align: left !important; font-family: Arial Narrow, Arial, sans-serif; font-weight: bold;">RESUMEN:</span>
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</footer>
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_lista_cuotas.'
			'.$html_retencion_base.'
			'.$html_lista_anticipos.'
			'.$html_cuentas_corrientes;
			
			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					$tb_pin = 'float-left';
				}else{
					$tb_pin = '';
				}
				
				$html = $html.'
				
				
				<table class="'.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em;float:left">
					<tbody>
						<tr>
							<td> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="50px"> 
							</td>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr" class="pl-2" width="90px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';

			}
			if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					$tb_yape = 'float-right';
					
					
				}else{
					$tb_yape = '';
				}
				$html = $html.'	
				<table class="'.$tb_yape.' mb-3" style="width:49%;">
					<tbody>
						<tr>
							<td> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="50px">
							</td>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';
			}
			$html = $html.'
			'.$aviso_texto_pruebas.'
			<div class="text-center mt-2 mb-5">'.$cpe['sucursal']['txt_pdf_a4_3'].'</div>
			
		</body>
			
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}

	public function get_html_plantilla_a4_2($cpe) { //ID: 2, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		$sitio_web = '';
		if(!empty($cpe['emisor']['sitio_web'])) {
			$sitio_web = '<p>'.$cpe['emisor']['sitio_web'].'</p>';
		} else {
			$sitio_web = '';
		}

		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '	<td class="border-left text-center">
				<p class="font-weight-bold">Fecha de  Vencimiento</p>
				<p>'.$cpe['cabecera']['fecha_vencimiento'].'</p> 
			</td>';
		
		}
		//Orden de compra
		$colum_1 = '';
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '<td style="border-bottom: 1px solid #000"><span class="font-weight-bold">Orden de Compra:</span> '.$cpe['cabecera']['orden_compra'].'</td>';
		} else {
			$orden_compra = '';
			$colum_1 = 'colspan="2"';
		}

		//N° de placa
		$colum = '';
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '<td style="border-bottom: 1px solid #000">
			<p><span class="font-weight-bold">Placa N°: </span>'.$cpe['cabecera']['nro_placa'].'</p>
		</td>';

		} else {
			$nro_placa = '';
			$colum = 'colspan="2"';
		}
		
	
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="7" class="text-uppercase text-left"  style="border-top: 1px solid #000; border-radius: 0!important">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td align="center" class="text-center">'.$item['nro_item'].'</td>
				<td align="center">'.$item['codigo'].'</td>
				<td class="text-left td_descripcion" style="width: 250px !important;">'.$item['descripcion'].'</td>
				<td align="center">'.$item['unidad_medida'].'</td>
				<td align="center">'.($item['cantidad'] + 0).'</td>
				<td align="center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</</td>
			</tr>
			';
		}
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
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
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';

		if(isset($cpe['sum_anticipos']) && 1 == 2) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		<tr>
        			<td>Total a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
        			</td>
        		</tr>
				<tr>
        			<td>IGV a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total_igv'] - $cpe['totales_anticipos']['total_igv'], 2)).'
        			</td>
        		</tr>
        		';
		    }
		}
	
		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="text-uppercase font-weight-bold" style="font-size: 10px">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase" style="font-size: 9px;">Información de la retención: </p>
				<table class="table table-retencion" style="border: 1px solid #000; font-size: 9px;">
					<tbody>
						<tr class="text-uppercase" style="border-bottom: 1px solid #000; font-weight: normal">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-main text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-main text-white">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}


		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
				font-size: 11px;
				font-family: arial, sans-serif;
				color: #000;
			}
			.codigo_qr p{
				font-size: 11px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
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
				font-size: 11px;
			}
			.table_detraccion, .table_percepcion{
				border: 1px solid #000;
			}
			.table_detraccion td, .table_percepcion td{
				border:none;
				padding: 5px;
				font-size: 11px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 11px;
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
				height: 200px;
			}
			.head-pg{
				position:relative;
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			} 
			
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
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
				font-size: 9px;
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
				border: 1px solid #000;
				width: 100%;
			}

			/* === Tabla Bordes === */
			.border-left{
				border-left: 1px solid #000!important;
			}
			.table-head {
				border: 1px solid #000;
				text-align: center;
				width: 100%;
				font-size: 11px!important;
			}
			.table-head td{
				padding: .8em 1em;
				line-height: 2;
				font-size: 14px;
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
			
			.tb_resumen_totales {
				width: 190px;
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
		
		if(empty($cpe['emisor']['logo_rectangular'])) {
			$height = "style='height: 150px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
				<div class="col-3">
					<img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
				</div>
				<div class="col-5 text-center" style="padding:10px 5px">
					<h5 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h5>
					'.$direccion_empresa.'
					'.$ubigeo_empresa.'
					<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
					'.$sitio_web.'
				</div>
				<div class="col-4  mt-1" style="padding:10px 5px">
					<table class="table-head bordered" style="font-size: 15px!important;">
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
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
		} else {
			$height = "style='height: 230px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
				<div class="col-7 text-center mb-4" style="padding:10px 5px">
					<img src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px; padding:0!important; margin:0!important;">
					<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
					'.$direccion_empresa.'
					'.$ubigeo_empresa.'
					<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
					'.$sitio_web.'
				</div>
				<div class="col-5"  style="padding:10px 5px">
					<table class="table-head bordered">
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
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
		}
		
			
		$html = $html.'
		'.$text_pdf_1.'
		<table class="table-2 bordered mt-30 mb-3">
			<tbody class="text-center">
				<tr>
					<td class="text-center">
						<p class="font-weight-bold">Fecha de Emisión:</p>
						<p>'.$cpe['cabecera']['fecha_emision'].'</p> 
					</td>
					<td class="border-left text-center">
						<p class="font-weight-bold">Forma de Pago</p>
						<p>'.$cpe['cabecera']['forma_de_pago'].'</p> 
					</td>
				
					'.$fecha_vencimiento.'
					<td class="border-left text-center" style="border-right: 1px solid #000">
						<p class="font-weight-bold">Moneda</p>
						<p>'.$cpe['cabecera']['nombre_moneda_doc'].'</p> 
					</td>
					<td class="text-center">
						<p class="font-weight-bold">Guía de Remisión N°</p>
						<p>'.(!empty($cpe['cabecera']['guia_remision'])?$cpe['cabecera']['guia_remision']:'-').'</p> 
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table-client bordered mt-30 mb-3">
			<tbody>
				<tr style="border-bottom: 1px solid #000">
					<td class="spacing-lg" '.$colum_1.' style="border-bottom: 1px solid #000">
						<p>
							<span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].': </span> '.$cpe['cliente']['doc_identificacion_numero'].'
						</p>
					</td>
					'.$orden_compra.'
				</tr>
				<tr>
					<td class="spacing-lg" '.$colum.' style="border-bottom: 1px solid #000">
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span> '.ucwords($cpe['cliente']['nombre']).'</p>
					</td>
					'.$nro_placa.'
				</tr>
				<tr>
					<td class="spacing-lg" colspan="2">
						'.(!empty($cpe['cliente']['direccion_fiscal'])?'<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>':'-').'
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table-items-productos table-4 bordered">
			<tbody>
				<tr class="text-uppercase bg-main text-white">
					<th>Item</th>
					<th>Código</th>
					<th width="500px">Descripción</th>
					<th>Unid.</th>
					<th>Cantidad</th>
					<th>P.Unitario</th>
					<th>Total</th>
				</tr>
				<tr>
					'.$items_detalle_html.'
				</tr>
				<tr>
					<td colspan="7" class="text-uppercase text-left font-weight-bold"  style="border-top: 1px solid #000; border-radius: 0!important">'.$cpe['cabecera']['total_letras'].' '.strtoupper($cpe['cabecera']['nombre_moneda_doc']).'</td>
				</tr>
				'.$text_pdf_2.'
				
			</tbody>
		</table>
		<div class="resumen_totales">
			<div class="col-8 pt-3 pr-3 notas_doc">
				'.$nota_doc.'
				'.$text_pdf_3.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$aviso_texto_pruebas.'
			</div>
			<div class="col-4 float-right">
				<table class="tb_resumen_totales float-right">
					<tbody>
						'.$resumen.'
					</tbody>
				</table>
			</div>
		</div>
		'.$html_lista_anticipos.'
		'.$html_lista_cuotas.'
		'.$html_detraccion.'
		'.$html_percepcion.'
		'.$html_retencion_base.'
		<div class="footer-content w-100">';
			$total_cuentas = count($cpe['cuentas_banco']);
			$rows = 0;
			if($total_cuentas > 0 && $total_cuentas <= 3) {
				$html = $html.'
					<table  class="table-3 table table-cuentas mt-4">
					<tr>
						<td colspan="3" class="text-uppercase" style="border-bottom: 1px solid #000;color: 000"> <p>Usted puede hacer pagos directamente en nuestras cuentas en los siguientes Bancos: </p></td>
					</tr>
					<tr>';

					foreach($cpe['cuentas_banco'] as $cuenta) {
						$html = $html.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}

				$html = $html.'
					</tr>
				</table>';
			} else if($total_cuentas > 3) {
				$html = $html.'
				<table  class="table-3 table table-cuentas">
					<tr>
						<td colspan="3" class="text-uppercase" style="border-bottom: 1px solid #000;  color: 000"> <p>Usted puede hacer pagos directamente en nuestras cuentas corrientes</p></td>
					</tr>';
				
				$item_nf = '';
				$parte1 = '';
				$parte2 = '';
				$parte3 = '';
				$lista_cuentas = '';
				foreach($cpe['cuentas_banco'] as $cuenta) {
					$rows++;
					
					if($rows > 0 && $rows <= 3) {
						$parte1 = $parte1.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}
					
					if($rows > 3 && $rows <= 6) {
						$item_nf = $item_nf.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}
					if($rows > 6 && $rows <= 9) {
						$parte2 = $parte2.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}
					if($rows > 9 && $rows <= 12) {
						$parte3 = $parte3.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}

				}

				if($parte1 != '') {
					$html = $html.'<tr>'.$parte1.'</tr>';
				}

				if($item_nf != '') {
					$html = $html.'<tr>'.$item_nf.'</tr>';
				}
				if($parte2 != '') {
					$html = $html.'<tr>'.$parte2.'</tr>';
				}
				if($parte3 != '') {
					$html = $html.'<tr>'.$parte3.'</tr>';
				}
				$html = $html.'
				</table>';
			}
				$html= $html.'
				<table class="table-footer mt-3 p-0 mb-3">
					<tbody class="text-center">
						<tr>
							<td style="border-right: 0;" class="codigo_qr" vertical-align: top;> 
								<p>Autorizado mediante la resolución Nº 0640050002737/Sunat</p>
								<p>Representación impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
								<p class="mb-1">Para consultar el comprobante visita: '.$cpe['patrocinador']['url_consulta_cpe'].'
								<p>HASH:  '.$cpe['cabecera']['hash'].'</p>
								<p>Atendido Por: '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							</td>
							<td style="border-left: 0" vertical-align: top;><img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-right"></td>
						</tr>	
					</tbody>
				</table>	
			
			</div>';
			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					$tb_pin = 'float-left';
				}else{
					$tb_pin = '';
				}
				$html = $html.'
				<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';

			}
			if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					$tb_yape = 'float-right';
					
				}else{
					$tb_yape = '';
				}
				$html = $html.'	
				<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';
			}
			$html = $html.'
		
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}

	public function get_html_plantilla_a4_3($cpe) { //ID: 3, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
			
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		$sitio_web = '';
		if(!empty($cpe['emisor']['sitio_web'])) {
			$sitio_web = '<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>';
		} else {
			$sitio_web = '';
		}

		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}

		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p class="txt_pdf_a4_2">'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}


		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<h4 class="font-weight-bold text-uppercase">Observación:</h4>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center pr-2">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items td_descripcion" style="text-align: left;"><div style="width: 280px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="font-weight-bold text-center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}

		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-theme-default">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}
		
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

	

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

	

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span>'.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		<tr>
        			<td class="color-theme-main">Total a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
        			</td>
        		</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr>
					<td class="color-theme-main">Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span>'.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 20px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="table-cuentas pl-2" style="width: 100%">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-cuentas pl-2">
					<tbody>
						<tr class="bg-theme-default">
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="table-cuentas pl-2" style="margin:auto">
					<tbody>
						<tr class="bg-theme-default">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="table-cuentas pl-2" style="margin:auto">
					<tbody>
						<tr class="bg-theme-default">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger text-center mt-3" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. ¡No tiene Validez!
				</p>
			';
		}

		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<body>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0px;
				}
				body{
					position: relative;
					font-family: "Cairo", sans-serif;
					font-size: 11px!important;
				}
				i::before{
					font-size: 11px!important;
				}
				h1, h2, h3, h4, h5, h6, table {
					font-size: 11px!important;
				}
				.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
					font-size: 11px;
				}
				table{
					width: 100%
				}
				th{
					text-align: center;
				}
				.nota_doc td{
					/* dividir cadenas largas en lineas*/
					-ms-word-break: break-all;
					word-break: break-all;
					word-break: break-word;
					-ms-hyphens: auto;
					-moz-hyphens: auto;
					-webkit-hyphens: auto;
					hyphens: auto;
				}
				.table-head td, .table-head th {
					border: 1px solid #000;
					padding: 10px;
				}
				.table_detraccion, .table_percepcion, .table-cuentas {
					border: 1px solid #000;
					padding: 4px;
				}
				.table-cuentas {
					width: auto;
					margin: auto
				}
				.table_detraccion td, .table_percepcion  td, .table-cuentas td{
					border: 0;
					padding: 4px;
				}
				.table-main-head td {
					text-align: center;
				}
				.txt_pdf_a4_1, .txt_pdf_a4_3, .txt_pdf_a4_3{
					font-size: 11px;
				}
				p{
					margin: 0;
					padding: 0;
					line-height: 12px
				}
				.box-content{
					border: 1px solid #000;
					width: 100%;
					
				}
				.box-content-height{
					height: 165px;
				}
				.box-content-height-1{
					height: 155px;
				}
				.col-3{
					width: 25%;
					float: left;
				}
				.col-4{
					width: 33.333333333333%;
					float: left;
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
					float: left;
				}
				.col-12{
					width: 100%;
					float: left;
				}
				.header-title{
					width: 37%;
					float: left;
				}
				.header-title p{
					font-size: 11px;
				}
				
				.table-head{
					width: 100%;
				}
				.border-black{
					border: 1px solid #000;
				}
				.bg-theme-default{
					background: #a3251e; 
					color: #fff;
				}
				.color-theme-main{
					color: #a3251e; 
				}
				.masthead{
					position:relative;
				}
				
				';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			} 
			
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
			$html = $html.'
				/* Tablas */
				.table-main-head {
					border: 1px solid #000;
				}
				.table-main-head th {
					padding: 3px 10px;
				}
				.table-main-head td {
					padding: 4px 10px;
				}
				.tabla-head{
					width: 38%;
					float: left;
					height: 200px;
				}
				/*footer {
					position: absolute;
					bottom: 3%;
					width: 100%;
				}*/
				.footer-info p{
					font-size: 11px;
				}
				.table-footer{
					border: 1px solid #000;
				}
				.table-footer td, .table-footer th {
					padding: 0;
					text-align: center;

				}
				.table-footer td {
					padding: 0px 5px 0 0!important;
				}
				.table-datos-iniciales {
					border: 1px solid #000!important;
					padding: 10px;
				}
				.table-datos-iniciales td{
					padding: 10px;
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
			</style>';
		
			if(empty($cpe['emisor']['logo_rectangular'])) {
				$height_masthead = 'style="height: 200px; padding:10px 5px"';
				$html = $html.'
				<div class="masthead w-100" '.$height_masthead.'>
					<div class="float-left mr-5 header-logo">
					<img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px" class="mt-3">
					</div>
					<div class="header-title text-center" ">
						<h5 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h5>
						'.$direccion_empresa.'
						'.$ubigeo_empresa.'
						<p><i class="flaticon-call mr-2"></i>Telf: '.$cpe['emisor']['telefono'].' -  <i class="flaticon-envelope mr-2"></i>'.$cpe['emisor']['email'].'<p>
						'.$sitio_web.'
					</div>
					<div class="tabla-head ml-3">
						<table class="table-head table">
							<tr class="mt-2">
								<td  class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
							</tr>
							<tr class="bg-main">
								<td  class="text-center bg-theme-default font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'</td>
							</tr>
							<tr>
								<td  class="text-center">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
							</tr>
						</table>
					</div>
				</div>';
			} else {
				$height_masthead = 'style="height: 230px"';
				$html = $html.'
				<div class="masthead w-100" '.$height_masthead.'>
					<div class="col-7 text-center mb-4">
					<img  src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px;">
						<h5 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h5>
						'.$direccion_empresa.'
						'.$ubigeo_empresa.'
						<p><i class="flaticon-call mr-2"></i>Telf: '.$cpe['emisor']['telefono'].' -  <i class="flaticon-envelope mr-2"></i>'.$cpe['emisor']['email'].'<p>
						'.$sitio_web.'

					</div>
					<div class="col-5 p-2">
						<table class="table-head table">
							<tr class="mt-2">
								<td  class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
							</tr>
							<tr class="bg-main">
								<td  class="text-center bg-theme-default font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'</td>
							</tr>
							<tr>
								<td  class="text-center">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
							</tr>
						</table>
					</div>
				</div>';
			}

			$html = $html.'
			<div class="text-center">'.$cpe['sucursal']['txt_pdf_a4_1'].'</div>
			<table class="table-datos-iniciales pt-2">
				<tbody>
					<tr class="text-uppercase">
						<td width="50%" valign="top">
							<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
							'.$direccion_fiscal.'
							<p> <span class="font-weight-bold">Forma de pago: </span>   '.$cpe['cabecera']['forma_de_pago'].'</p>
							'.$fecha_vencimiento.'
						</td>
						<td  width="50%" valign="top">
							<p><span class="font-weight-bold">F. Emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
							<p><span class="font-weight-bold">Tipo de Moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
							<p><span class="font-weight-bold">Vendedor: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							'.$orden_compra.'
							'.$num_guia.'  '.$nro_placa.'
							
							'.$descripcion_nota.'
							'.$documento_relacionado.'
						</td>
					</tr>
				</tbody>
			</table>
		
			<div class="table-content">
				<table class="table-main-head mt-4 mb-4">
					<tbody>
						<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
							<th>Cant.</th>
							<th>Descripción</th>
							<th>Precio</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th>Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="6" class="text-uppercase text-center" style="border-top: 1px solid #000">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			'.$text_pdf_2.'
			<footer>
				<table class="nota_doc mb-2">
					<tbody>
						<tr>
							<td class="pr-4">
								'.$nota_doc.'
								<p class="font-weight-bold text-uppercase mt-2">Consulte su documento electrónico en:</p>
								<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="40px" class="float-left mr-3 mb-3">
								<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
								<p><span class="font-weight-bold">Hash:</span> '.$cpe['cabecera']['hash'];
								
								if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
								
									$html = $html.'
									<table class="mb-3" style="width:100%;">
										<tbody>
											<tr>
												<td width="90px"> 
													<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px">
													<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
												</td>
												<td> 
													<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
												</td>
												
											</tr>
										</tbody>
									</table>';
					
								}
								if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
									
									$html = $html.'	
									<table   class="mb-3" style="width:100%;">
										<tbody>
											<tr>
												<td width="90px"> 
													<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
													<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
												</td>
												<td> 
													<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
													<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
												</td>
												
											</tr>
										</tbody>
									</table>';
								}
								$html = $html.'
								<p>Representación impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
							</td>
							<td width="320px" style="vertical-align: top;">
								<table class="table-footer font-weight-bold text-uppercase">
									<tr>
										<td colspan="2" class="text-center font-weight-bold text-uppercase"></td>
									</tr>
									<tr class="bg-main">
										<td  colspan="2"  class="text-center bg-theme-default font-weight-bold">Resumen</td>
									</tr>
									'.$resumen.'
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</footer>
			
			'.$html_detraccion.'
			'.$html_percepcion.'
			<div class="mt-2">
				'.$html_lista_cuotas.'
				'.$html_lista_anticipos.'
				'.$html_retencion_base.'
				'.$html_cuentas_corrientes.'
				'.$aviso_texto_pruebas.'

			</div>
			<div class="text-center mt-2">'.$text_pdf_3.'</div>
			
		</body>
		</html>
		';
			
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	public function get_html_plantilla_a4_4($cpe) { //ID: 4, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
	

		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-primary ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-primary ">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-primary">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-primary">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-primary">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
				<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_1'].'</td>
			</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}


		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
		
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center pr-2">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items td_descripcion" style="text-align: left;"><div style="width: 280px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="font-weight-bold text-center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
			// Resumen de ventas
			$resumen = '
			<tr>
				<td class="text-primary text-right">Gravada:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
				</td>
			</tr>
			';

			if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
				$resumen = $resumen.'
				<tr>
					<td class="text-primary text-right">Inafecto:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
					</td>
				</tr>
				';
			}

				
			if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
				$resumen = $resumen.'
				<tr>
					<td class="text-primary text-right">Exonerado:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
					</td>
				</tr>
				';
			}
		

			if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
				$resumen = $resumen.'
				<tr>
					<td class="text-primary text-right">Gratuito:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
					</td>
				</tr>
				';
			}

			if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
				$resumen = $resumen.'
				<tr>
					<td class="text-primary text-right">Exportación:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
					</td>
				</tr>
				';
			}

			if(floatval($cpe['cabecera']['total_icbper']) > 0) {
				$resumen = $resumen.'
				<tr>
					<td class="text-primary text-right">ICBPER:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
					</td>
				</tr>
				';
			}

			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
				</td>
			</tr>
			';

			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">Descuento Total:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
				</td>
			</tr>
			';

			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">Total:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
				</td>
			</tr>
			';
			if(isset($cpe['sum_anticipos'])) {
				if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
					$resumen = $resumen.'
					<tr>
						<td class="text-primary text-right">Total Anticipos (-):</td>
						<td class="text-right  font-weight-bold">
							<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
						</td>
					</tr>
					<tr>
						<td class="text-primary text-right">Total a Pagar:</td>
						<td class="text-right  font-weight-bold">
							<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
						</td>
					</tr>
					';
				}
			}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 20px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="table-cuentas table-retencion pl-2" style="width: 100%">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}

	
		
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<div style="width:33.333333333333%; float:left; height: 100px">
					<p class="tabla_cuentas"><img src="'.$cuenta['url_logo_banco'].'"  width="40px" class="img-cuenta mr-2"> <span class="font-weight-bold">Titular:</span> '.$cuenta['titular'].'</p>
					<p><span class="font-weight-bold">Banco:</span> '.$cuenta['nombre_banco'].'</p>
					<p class="tabla_cuentas"><span class="font-weight-bold">N° cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'
					<p><span class="font-weight-bold">CCI:</span> '.$cuenta['cci'].'</p>
				</div>
			
			';
		}

		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<footer class="w-100 pt-2" style="border-top:  1px solid #dddddd;" style="height: 80px">
				<p class="text-primary font-weight-bold mb-1">Cuentas:</p>
				'.$html_cuentas_corrientes.'
				
			</footer>
			';
		}


		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}


		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-main-head" style="margin:auto; width: auto">
					<tbody>
						<tr class="bg-theme-default">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
					<tr class="bg-primary text-white text-uppercase">
							<th>N°</th>
							<th width="100px">Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. ¡No tiene Validez!
				</p>
			';
		}
	
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
			body{
				position: relative;
				font-size: 11px!important;
			}
			
			h1, h2, h3, h4, h5,  table {
				font-size: 11px;
			}

			.table-no-border td, .table-no-border th {
				border: none;
			}
			header {
				border-bottom: 1px solid #007bff;
				text-align: center;
				height: 30px;
				margin-bottom: 10px;
			}
			.notas_doc td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			p{
				margin: 0;
				padding: 0;
			}
			td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}
			th {
				text-align: center;
			}
			
			#columna1{
				margin-left: 0;
				padding-left: 0;
			}
			#ultima_columna{
				margin-right: 0;
				padding-right: 0;
				width: 210px;
			}
				#invoice-total {
				float: right;
			}
			.borde-primary {
				border-top: 1px solid #007bff;
			}
			.contenedor {
				margin: 10px auto;
				float: right;
				width: 100%;
				padding-top: 1em;
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
			.col-6 {
				width: 50%;
				float: left;
				padding: 0;
			}
			.col-7 {
				width: 55%;
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
				height: auto;
				position:relative;
			}
			
					
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			} 
			
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
			$html = $html.'
			.footer-table {
				float: left;
				height: 200px;
				margin: 10px;
				padding: 10px;
				width: 100px;
			}
			.footer-table p{ padding-bottom: 10px}
			.footer-table {
				text-align: right;
			}
			.single-date {
				float: left;
				height: 100px;
				padding: 0 10px;
				width: 33.333333333333%;
			}
			.table-main-head{
				width: 100%;
				border-collapse: collapse;
			}
			.tb_resumen_totales {
				width: 100%;
			}
			.tb_resumen_totales td {
				padding: 8px 10px;
				font-weight: 700;
				border-top: none;
				border-left: 1px solid #dddddd;
				border-right: 1px solid #dddddd;
				border-bottom: 1px solid #dddddd;
			}

			.resumen_totales {
				height: 200px;
			}
			/*footer {
				position: absolute;
				bottom: 0%;
				border-top: 1px solid #007bff;
				width: 100%;
			}*/
			.table_datos_main td{
				border: 0;
			}
			table td, table tr{
				padding: 5px!important;
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
			.table-retencion td{
				font-size: 11px;
				text-align: center;
			}
		</style>
		<body>
			<header>
				<h4 class="pt-2 float-left" style="font-size: 14px!important">
				'.$cpe['cabecera']['nombre_cpe'].'
				</h4>
				<h4 class="pt-2 float-right" style="font-size: 14px!important">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h4>
			</header>
			<div class="masthead">
				<div class="col-6  mb-4">
					<h4 class="text-primary text-uppercase font-weight-bold mb-2">'.$cpe['emisor']['nombre_comercial'].'</h4>
					'.$direccion_empresa.'
					'.$ubigeo_empresa.'
					<p><i class="flaticon-call text-primary mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
					<p><i class="flaticon-envelope mr-2 text-primary"></i>'.$cpe['emisor']['email'].'</p>';
					if(!empty($cpe['emisor']['sitio_web'])) {
						$html = $html.'
						<p><i class="flaticon-placeholder-1 mr-2 text-primary"></i>'.$cpe['emisor']['sitio_web'].'</p>';
					}
					$html = $html.'
					
				</div>

				<div class="col-6 pl-5 text-center">';
					if(empty($cpe['emisor']['logo_rectangular'])) {
						$html = $html.'
						<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
						
					}else{
						$html = $html.'
						<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
					}
					$html = $html.'
					<div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
					<h4 class="text-primary text-uppercase  mb-2 mt-4 text-center font-weight-bold" style="font-size: 14px!important">R.U.C.  '.$cpe['emisor']['ruc'].'</h4>
				</div>
			</div>
			<div id="servicios" class="contenedor borde-primary">
				<table class="w-100 table_datos_main">
					<tr>
						<td width="33.333333333333%" valign="top">
							<p><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
							<p><span class="text-primary font-weight-bold">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
							'.$orden_compra.'
							'.$fecha_vencimiento.'
						</td>
						<td width="33.333333333333%" valign="top">
							<p><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							'.$direccion_fiscal.'
							'.$num_guia.'
							
						</td>
						<td width="33.333333333333%" valign="top">
							<p><span class="text-primary font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
							<p><span class="text-primary font-weight-bold">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
							'.$nro_placa.'
							'.$descripcion_nota.'
							'.$documento_relacionado.'
						</td>
					</tr>
				</table>
			</div>
			<div class="text-center" style="margin: 10px 0">
				'.$text_pdf_1.'
			</div>
			<section>
				<table class="table-main-head">
					<tbody>
						<tr class="bg-primary text-white text-uppercase">
							<th>Cant.</th>
							<th width="400px">Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th>Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table-no-border">
				<tbody>
					<tr>
						<td style="vertical-align: top;">
							<table  class="table-no-border notas_doc">
								<tbody>
									<tr>
										<th style="width:130px;">
											<img style="width: 60px; margin-bottom: 11px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'"/>
										</th>
										<td class="texto_general pr-4">
											';
											if(!empty($cpe['cabecera']['documento_nota'])) {
											$html = $html.'
											<p><strong class="text-primary">Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
											';
											}
											$html = $html.'
											<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
											<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
											<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
											<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
											'.$text_pdf_3.'
										</td>
									</tr>
									<tr>
									 	<td colspan="2">'.$aviso_texto_pruebas.'</td>
									</tr>
									
								</tbody>
							</table>
						</td>
						<td style="width:250px; vertical-align: top; padding: 0!important">
							<table class="tb_resumen_totales">
								<tbody>
									'.$resumen.'
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			</table>
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_lista_cuotas.'
			'.$html_retencion_base.'
			'.$html_lista_anticipos.'
			'.$html_cuentas_corrientes;
		if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
			if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
				$tb_pin = 'float-left';
			}else{
				$tb_pin = '';
			}
			$html = $html.'
			<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
				<tbody>
					<tr>
						<td width="140px"> 
							<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
							<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
						</td>
						<td> 
							<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
							<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
						</td>
						
					</tr>
				</tbody>
			</table>';

		}
		if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				$tb_yape = 'float-right';
				
			}else{
				$tb_yape = '';
			}
			$html = $html.'	
			<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
				<tbody>
					<tr>
						<td width="140px"> 
							<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
							<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
						</td>
						<td> 
							<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
							<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
						</td>
						
					</tr>
				</tbody>
			</table>';
		}
			$html = $html.'
			
			<hr>
			
		</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	public function get_html_plantilla_a4_5($cpe) { //ID: 5, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
	

		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-primary ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-primary ">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
	
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<div class="single-date">
				<p class="text-primary font-weight-bold">N° de Guía:</p>
				<p>'.$cpe['cabecera']['guia_remision'].'</p>
			</div>';
		} else {
			$num_guia = '';
		}

		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<div class="single-date">
				<p class="text-primary font-weight-bold">Orden de compra:</p>
				<p>'.$cpe['cabecera']['orden_compra'].'</p>
			</div>	';
		} else {
			$orden_compra = '';
		}

	
		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<div class="single-date">
				<p class="text-primary font-weight-bold">N° de placa:</p>
				<p>'.$cpe['cabecera']['nro_placa'].'</p>
			</div>
			';
		} else {
			$nro_placa = '';
		}


		
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
				<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_1'].'</td>
			</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}


		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items td_descripcion" style="text-align: left;"><div style="width: 300px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="font-weight-bold text-center" width="90px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td  class="text-center">'.$item['unidad_medida'].'</td>
				<td  class="text-center">'.$item['texto_tipo_operacion'].'</td>
				<td class="text-right" width="90px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		
		
		// Resumen de ventas
		$resumen = '
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom">Gravada: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</span> </p>
		</div>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Inafecto: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</span> </p>
			</div>
			';
		}

	

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Exonerado: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</span> </p>
			</div>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Exportación: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</span> </p>
			</div>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">ICBPER: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total_icbper']).'</span> </p>
			</div>
			';
		}

		$resumen = $resumen.'
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total_igv']).'</span> </p>
		</div>
		';

		$resumen = $resumen.'
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom">Descuento: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).' </p>
		</div>
		';
		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
					<p class="border-invoice-bottom">Total: <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total']).'</span> </p>
				</div>
				<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
					<p class="border-invoice-bottom">Total de Anticipos (-): <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['sum_anticipos']).'</span> </p>
				</div>
        		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
					<p class="border-invoice-bottom"><span class="bg-primary text-white">Total a Pagar:</span> <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
				</div>';
		    }else{
				$resumen = $resumen.'
				<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
					<p class="border-invoice-bottom"><span class="bg-primary text-white">Total:</span> <span class="price ml-5">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total']).'</span> </p>
				</div>
		
				';
			}
		}

		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="text-primary font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
			
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="text-uppercase">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuentas 
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr>
					<td rowspan="2" class="text-center">'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['tipo'].' - '.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
				<tr>
					<td colspan="3">Titular: '.$cuenta['titular'].'</td>
				</tr>
			
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas" style="margin:auto">
					<tbody>
						<tr class="text-uppercase">
							<th>Banco</th>
							<th>Tipo cta</th>
							<th>N° de cuenta</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 20px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="table-cuentas pl-2" style="width: 100%">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}


		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-main-head" style="margin:auto; width: auto">
					<tbody>
						<tr class="bg-theme-default">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}

	
	


		$html = '	
		<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
				<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
			</head>
			<body>
				<style>
					@import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap");
					body {
						font-family: "Open Sans",Tahoma,Geneva,sans-serif;
						position: relative;
						font-size: 11px!important;
					}
					
					table {
						font-size: 11px!important;
					}
					.notas_doc td{
						-ms-word-break: break-all;
						word-break: break-all;
						word-break: break-word;
						-ms-hyphens: auto;
						-moz-hyphens: auto;
						-webkit-hyphens: auto;
						hyphens: auto;
					}
					.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
						font-size: 11px;
					}
					ul {
						list-style-type: circle;
						margin-right: 0!important;
						padding: 0 0 0 10px;
					}
					td, th {
						border-bottom: 1px solid #dddddd;
						padding: 5px 2px 5px 5px!important;
					}
					tr:nth-child(even) {
						background-color: #eee;
					}
					p{
						margin: 0;
						padding: 0;
					}
					.border-invoice-bottom{
						border-bottom: 1px solid #ddd;
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
						padding: 0;
					}
					.col-7 {
						width: 55%;
						float: left;
						padding: 0;
					}
					.col-8{
						width: 66.666667%;
						float: left;
						padding: 0;
					}
					.invoice-total {
						width: 100%;
						height: 200px;
					}
					.display-inline{
						display: inline;
					}
					/*footer {
						position: absolute;
						bottom: 0;
						width: 100%;
					}*/
					
					.footer-table {
						float: right;
						margin: 0;
						padding: 0 5px;
						width: 250px;
						text-align: left;
					}
					.footer-table p{ padding-bottom: 10px}
					.header-main{
						width: 100%;
						height: 260px;
					}
					.item-media {
						text-align: left;
						font-size: 12px;
					}
					.item-media i {
						float: left;
						padding: 9px 3px;
						border-radius: 20px;
						border: 1px solid #ddd;
						text-align: center;
						margin-right: 10px
					}
					[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
						margin: 0px;
						text-align: center;
						font-size: 15px;
						color: #007bff;
					}
					.letter-spacing-1{
						letter-spacing: 2;
					}
					.masthead {
						position: relative;
						border-top: 1px dashed #007bff;
						padding-top: 2em;
					}
					.table-main-head{
						background: #fff;
					}
					.header-main{
						position:relative;
					}
					
					';
					
					if($cpe['cabecera']['estado_documento'] == 'anulado') {
						$html = $html.'
						.header-main::before{
							content: " ";
							position: absolute;
							top: 0;
							left: 0;
							width: 100%;
							height: 800px;
							background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
							background-repeat: no-repeat;
							backgroud-position: center right;
							z-index: -1;
						} ';
					}  
					
					if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
						$html = $html.'
						.header-main::before{
							content: " ";
							position: absolute;
							top: 0;
							left: 0;
							width: 100%;
							height: 950px;
							background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
							z-index: -1;
						} ';
					} 
					
					if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
						$html = $html.'
						.header-main::before{
							content: " ";
							position: absolute;
							top: 0;
							left: 0;
							width: 100%;
							height: 950px;
							background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
							z-index: -1;
						} ';
					}
			
					$html = $html.'
					.masthead .img-header{
						position: absolute;
						top: 0;
						right: 0;
					}
					.single-header1, .single-header2, .single-date, .info-total, .date-price, .single-footer-wight, .social-media{
						float: left;
						/*margin: 10px;
						padding: 10px;*/
					}
					
					.single-date {
						padding: 0 10px;
						margin: 10px 10px 10px 0;
					}
					.single-date-client{
						border-top: 1px dashed #007bff;
    					padding-top: 1em;
					}
					.single-footer-wight.box-main{
						padding: 0px 10px 10px 0;
						margin: 0px 10px 10px 0;
					}
					.table-main-head{
						width: 100%;
						border-collapse: collapse;
					}
					.sub-footer {
						border-bottom: 1px dashed #007bff;
					}
					.position-initial {
						position: initial!important;
						width: 100px!important;
					}
					.price {
						text-align: right;
						float: right;
					}

					.info-total{
						height: 200px;
						width: 250px;
					} 
					.date-price {
						width: 600px;
						float: right;
					}
					.social-media {
						width: 200px;
						float: left;
						padding: 0;
						margin: 5px 0;
					}
					/* Tablas */
					.table-no-border td, .table-no-border th {
						border: none;
					}
					.table-cuentas {
						font-size: 12px;
					}
					.table-cuentas td, .table-cuentas th {
						padding: 5px;
					}
					.table_detraccion td, .table_percepcion td{
						border-bottom: 1px solid #ddd;
						padding: 5px;
					}
					.sub-footer table {
						margin: auto;
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
				</style>
				<div class="masthead">
					<img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p-2.png" class="img-header">
					<div class="header-main">
						<div class="col-4">';
							if(empty($cpe['emisor']['logo_rectangular'])) {
								$html = $html.'
								<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
								
							}else{
								$html = $html.'
								<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
							}
							$html = $html.'
							<h6 class="font-weight-light text-primary mt-3">'.$cpe['emisor']['nombre_comercial'].'</h6>
							<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
							'.$direccion_empresa.'
							'.$ubigeo_empresa.'
							<p><i class="flaticon-call text-primary mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
							<p><i class="flaticon-envelope mr-2 text-primary"></i>'.$cpe['emisor']['email'].'</p>';
							if(!empty($cpe['emisor']['sitio_web'])) {
								$html = $html.'
								<p><i class="flaticon-placeholder-1 mr-2 text-primary"></i>'.$cpe['emisor']['sitio_web'].'</p>';
							}
							$html = $html.'
						</div>			
						<div class="pl-1 col-8">
							<h6 class="pt-2 font-weight-light text-uppercase" style="line-height: 1.5">'.$cpe['cabecera']['nombre_cpe'].': <br> <span  class="bg-primary text-white mt-2">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</span></h6> 
							<div id="date_invoice">
								<div class="single-date">
									<p class="text-primary font-weight-bold">Fecha emisión:</p>
									<p> '.$cpe['cabecera']['fecha_emision'].'</p>
								</div>	
								<div class="single-date">
									<p class="text-primary font-weight-bold">Forma de pago:</p>
									<p>'.$cpe['cabecera']['forma_de_pago'].'</p>
								</div>
								
								<div class="single-date">
									<p class="text-primary font-weight-bold">Moneda:</p>
									<p>'.$cpe['cabecera']['nombre_moneda_doc'].'</p>
								</div>
								'.$num_guia.'
								'.$orden_compra.'
								'.$nro_placa.'
							</div>		
						</div>	
					</div>
				</div> 
				<div class="mb-3 mt-2">
					<table>';
						$width_tb_1 = 'width="100%"';
						$width_tb_2 = '';
						if($cpe['cabecera']['id_tipo_cpe'] == '07' || $cpe['cabecera']['id_tipo_cpe'] == '08'){
							$width_tb_1 = 'width="60%"';
							$width_tb_2 = 'width="40%"';
						}
						$html = $html.'
						<tr>
							<td '.$width_tb_1.' style="border: none">
								<p><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
								<p><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
								'.$direccion_fiscal.'
								'.$fecha_vencimiento.'
							</td>
							<td '.$width_tb_2.' style="border: none">
								
							'.$descripcion_nota.'	
							'.$documento_relacionado.'
							</td>
						</tr>
					</table>
				</div>	
				<div class="text-center">
					'.$text_pdf_1.'
				</div>
				<div class="table-invoice mt-2">
					<table class="table-main-head mt-2">
						<tbody>
							<tr class="bg-primary text-white">
								<th>Cant.</th>
								<th width="450px">Descripción</th>
								<th>Precio</th>
								<th>Unid/Med</th>
								<th>Afect. IGV</th>
								<th>Importe</th>
							</tr>
							
							'.$items_detalle_html.'
							
							<tr>
								<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
							</tr>
						</tbody>
					</table>
					<table  class="table-no-border notas_doc">
						<tbody>
							<tr>
								<td style="vertical-align: top;" width="80%" class="pr-4">
									
									'.$nota_doc.'
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mt-1 mr-3 mb-3">
									<p class="mb-1"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</p> 
									<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-primary font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-primary font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<br>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$text_pdf_2.'
									
									'.$aviso_texto_pruebas.'
									
								</td>
								<td style="width:300px; vertical-align: top;">
									<table style="width: 100%;" class="tb_resumen_totales">
										<tbody>
											'.$resumen.'
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<footer>
				'.$html_lista_anticipos.'
				'.$html_lista_cuotas.'
				'.$html_retencion_base.'
				'.$html_cuentas_corrientes.'
				
				<div class="text-center mt-2">
						'.$text_pdf_3.'
					</div>
				</footer>
		
				'.$html_detraccion.'
				'.$html_percepcion;
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
					}else{
						$tb_yape = '';
						
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
			
			</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	public function get_html_plantilla_a4_6($cpe) { //ID: 6, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
	
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-default ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-default ">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-default">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-default">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-default">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<h4 class="text-theme-default text-left font-weight-bold">Observación:</h4>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
			$margin_top = 'mt-5';
		} else {
			$nota_doc = '';
			$margin_top = '';
		}
			
		
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3 mb-3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
	

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-default">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-default">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-default">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-default">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items td_descripcion" style="text-align: left;"><div style="width: 330px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center" width="150px">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td class="text-center">'.$item['unidad_medida'].'</td>
				<td class="text-center">'.$item['texto_tipo_operacion'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-theme-default text-right">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-right">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-right">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-right">Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';
		
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-theme-default text-white text-center text-uppercase">
							<th>N°</th>
							<th width="80px">Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
        		<tr>
        			<td class="text-theme-default text-right">Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		<tr>
        			<td class="text-theme-default text-right">Total a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
        			</td>
        		</tr>
        		';
		    }
		}
		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 20px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="table-cuentas table-retencion pl-2" style="width: 100%">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
	
	
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr>
					<td rowspan="2" class="text-center">'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['tipo'].' - '.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
				<tr>
					<td colspan="3">Titular: '.$cuenta['titular'].'</td>
				</tr>
			';
		}

		
	
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas pl-2"  style="margin:auto">
					<tbody>
						<tr class="bg-theme-default text-white text-center text-uppercase">
							<th>Banco</th>
							<th>TIPO CTA</th>
							<th>N° DE CUENTA</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-main-head" style="margin:auto; width: auto">
					<tbody>
						<tr class="bg-theme-default text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
	

		$html = '
				
		<html lang="es">
   		 	<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
				<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
				<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
    		</head>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0px;
				}

				body{
					position: relative;
					font-size: 11px!important;
				}
				
				h1, h2, h3, h4, h5,  table {
					font-size: 11px!important;
				}
				.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
					font-size: 11px;
				}
				.notas_doc td{
					-ms-word-break: break-all;
					word-break: break-all;
					word-break: break-word;
					-ms-hyphens: auto;
					-moz-hyphens: auto;
					-webkit-hyphens: auto;
					hyphens: auto;
				}
				header {
					text-align: center;
					height: 40px;
				}
				p{
					margin: 0;
					padding: 0;
				}
				td, th {
					text-align: left;
					padding: 5px!important;
				}
				.table-main-head td, .table-main-head th {
					text-align: center;
					padding: 8px 0 8px 8px;
				}
				th {
					text-align: center;
				}
				#columna1{
					margin-left: 0;
					padding-left: 0;
				}
				#ultima_columna{
					margin-right: 0;
					padding-right: 0;
					width: 210px;
				}
					#invoice-total {
					float: right;
				}
				.borde-theme-default {
					border-bottom: 1px solid #2f80c1;
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
				.col-6 {
					width: 50%;
					float: left;
					padding: 0;
				}
				.contenedor {
					margin: 10px auto;
					float: right;
					width: 100%;
					padding-top: 1em;
				}
				.border-0 td{
					border: none;
				}
				.float-right{
					float:right;
				}
				.float-left{
					float:left;
				}
				.head-1 {
					width: 250px;
				}
				.masthead {
					display: block;
					height: 100px;
					position:relative;
				}
				
				';
				if($cpe['cabecera']['estado_documento'] == 'anulado') {
					$html = $html.'
					.masthead::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 800px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
						background-repeat: no-repeat;
						backgroud-position: center right;
						z-index: -1;
					} ';
				} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
					$html = $html.'
					.masthead::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 950px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
						z-index: -1;
					} ';
				}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
					$html = $html.'
					.masthead::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 950px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
						z-index: -1;
					} ';
				}
			
				$html = $html.'
		
				.footer-table {
					float: left;
					height: 200px;
					margin: 10px;
					padding: 10px;
					width: 100px;
				}
				.footer-table p{ padding-bottom: 10px}
				.footer-table {
					text-align: right;
				}
				.single-date {
					float: left;
					height: 90px;
					padding: 10px;
					width: 33.333333333333%;
				}
				.table-main-head{
					width: 100%;
					border-collapse: collapse;
				}
				/*	footer {
					position: absolute;
					bottom: 18%;
					width: 100%;
				}*/
				.text-theme-default{
					color: #2f80c1;
				}
				.title-main{
					font-family: "Dancing Script", cursive;
				}
				.bg-theme-default{
					background-color: #2f80c1;
				}
				/* Tablas */
				.table-no-border td, .table-no-border th {
					border: none;
				}
				.table-cuentas {
					font-size: 11px;
				}
				.table-cuentas td, .table-cuentas th {
					padding: 5px 10px;
				}
				.tb_resumen_totales td{
					padding: 3px 5px;
				}
				.table_detraccion td, .table_percepcion td{
					border:none;
					padding: 5px;
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
				.table-retencion td{
					font-size: 11px;
					text-align: center;
				}
			</style>
			<body>
				<header class="borde-theme-default">
					<h4 class="pt-2 float-left" style="font-size: 14px!important">
						'.$cpe['cabecera']['nombre_cpe'].'
					</h4>
					<h4 class="pt-2 float-right" style="font-size: 14px!important">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h4>
				</header>
				<div class="masthead">
					<div class="col-6  pl-2 pt-3 mb-4">
					<h4 class="text-theme-default font-weight-bold text-uppercase mb-2">'.$cpe['emisor']['nombre_comercial'].'</h4>
					<p>'.$cpe['emisor']['direccion'].'</p>
					<p>'.$cpe['emisor']['ubigeo'].'</p>
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.:  '.$cpe['emisor']['telefono'].'</p>
					<p><i class="flaticon-envelope mr-2 text-theme-default"></i>'.$cpe['emisor']['email'].'</p>';
					if(!empty($cpe['emisor']['sitio_web'])) {
						$html = $html.'
						<p><i class="flaticon-placeholder-1 mr-2 text-theme-default"></i>'.$cpe['emisor']['sitio_web'].'</p>';
					}
					$html = $html.'
					</div>
					<div class="col-6  pt-3 pl-5 text-center">';
						if(empty($cpe['emisor']['logo_rectangular'])) {
							$html = $html.'
							<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
							
						}else{
							$html = $html.'
							<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
						}
						$html = $html.'
						<div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
						<h4 class="text-theme-default text-uppercase  mb-2 mt-4 text-center  font-weight-bold" style="font-size: 14px!important">R.U.C.  '.$cpe['emisor']['ruc'].'</h4>
					</div>
				</div>
				<table class="table border-0">
					<tr>
						<td width="33,33333333333333">
							<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
							<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
							'.$orden_compra.'
							'.$nro_placa.'
							'.$fecha_vencimiento.'
						</td>
						<td width="33,33333333333333">
							<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							'.$direccion_fiscal.'
							'.$num_guia.'
						</td>
						<td width="33,33333333333333">
							<p><span class="text-theme-default font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
							<p><span class="text-theme-default font-weight-bold">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
							'.$descripcion_nota.'
							'.$documento_relacionado.'
						</td>
					</tr>
				</table>
				
				<div class="text-center">
					'.$text_pdf_1.'
				</div>
				<section>
					<table class="table-main-head">
						<tbody>
							<tr class="bg-theme-default  text-white text-uppercase">
								<th>Cant.</th>
								<th width="500px">Descripción</th>
								<th>Precio</th>
								<th>UNID/MED </th>
								<th>AFECT.IGV</th>
								<th>Importe</th>
							</tr>
							<tr>
							'.$items_detalle_html.'
							</tr>
							<tr>
								<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
							</tr>
							'.$text_pdf_2.'
						</tbody>
					</table>
					<table  class="table table-no-border">
						<tbody>
							<tr>
								<td style="vertical-align: top;" width="70%">
									<table  class="table-no-border notas_doc">
										<tbody>
											<tr>
												<td class="p-0">
													'.$nota_doc.'
													<div class="single-footer-wight box-main mb-2 pr-4">
														<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'"width="60px" class="float-left mr-3 mb-3">
														<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
														<p><span class="text-theme-default font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
														<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
														<br>
														<p class="mb-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
														'.$aviso_texto_pruebas.'
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td  style="vertical-align: top;" class="p-0">

									<div class="resumen_totales">
										<table class="table tb_resumen_totales float-right mb-4">
											<tbody class="font-weight-bold text-right">
												'.$resumen.'
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
		
				</section>
				<div class="w-100">
					'.$html_lista_anticipos.'
					'.$html_detraccion.'
					'.$html_percepcion.'
					'.$text_pdf_3.'
					'.$html_lista_cuotas.'
					'.$html_retencion_base.'
					'.$html_cuentas_corrientes.'
				</div>
				<footer class="footer_table">
					<div class="col-12">';
						
						if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
							if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
								$tb_pin = 'float-left';
							}else{
								$tb_pin = '';
							}
							$html = $html.'
							<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
								<tbody>
									<tr>
										<td width="140px"> 
											<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
											<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
										</td>
										<td> 
											<p class="font-weight-bold"><span class="text-theme-default">Titular:</span>  '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
											<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
										</td>
										
									</tr>
								</tbody>
							</table>';
			
						}
						if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
							if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
								$tb_yape = 'float-right';
							
								
							}else{
								$tb_yape = '';
							}
							$html = $html.'	
							<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
								<tbody>
									<tr>
										<td width="140px"> 
											<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
											<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
										</td>
										<td> 
											<p class="font-weight-bold"><span class="text-theme-default">Titular:</span> '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
											<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
										</td>
										
									</tr>
								</tbody>
							</table>';
						}
						$html = $html.'
					
					</div>
					
				</footer>
			</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	public function get_html_plantilla_a4_7($cpe) { //ID: 7, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
	
	
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-default ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-default ">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-default">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-default">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-default">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<h4 class="text-theme-default text-left font-weight-bold">Observación:</h4>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
			$margin_top = 'mt-5';
		} else {
			$nota_doc = '';
			$margin_top = '';
		}
			
		
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3 mb-3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
	

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-default">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-default">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-default">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-default">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items td_descripcion" style="text-align: left;"><div style="width: 200px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center" width="130px">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td class="text-center">'.$item['unidad_medida'].'</td>
				<td width="90px">'.$item['texto_tipo_operacion'].'</td>
				<td class="text-right" width="90px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-theme-default text-left">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-left">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-left">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-left">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-left">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-left">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-left">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-left">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-left">Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
        		<tr>
        			<td class="text-theme-default text-left">Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		<tr>
        			<td class="text-theme-default text-left">Total a Pagar:</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
        			</td>
        		</tr>
        		';
		    }
		}
	
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-theme-default text-white text-center text-uppercase">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 20px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="table-cuentas table-retencion pl-2" style="width: 100%">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
	
	
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr>
					<td rowspan="2" class="text-center">'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['tipo'].' - '.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
				<tr>
					<td colspan="3">Titular: '.$cuenta['titular'].'</td>
				</tr>
			';
		}

		
	
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas pl-2 mb-3"  style="margin:auto">
					<tbody>
						<tr class="bg-theme-default text-white text-center text-uppercase">
							<th>Banco</th>
							<th>TIPO CTA</th>
							<th>N° DE CUENTA</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-main-head" style="margin:auto; width: auto">
					<tbody>
						<tr class="bg-theme-default text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger text-center">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
	
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<body>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0;
				}
				body{
					font-family: "Montserrat", sans-serif;	
					font-size: 11px!important;
					position: relative;
				}
				
				h1, h2, h3, h4, h5,  table {
					font-size: 11px!important;
				}
	
				footer p{
					margin: 0;
					padding: 0;
					font-size: 11px;
				}
				table{
					width: 100%
				}
				.notas_doc td{
					-ms-word-break: break-all;
					word-break: break-all;
					word-break: break-word;
					-ms-hyphens: auto;
					-moz-hyphens: auto;
					-webkit-hyphens: auto;
					hyphens: auto;
				}
				.table-no-border td, .table-no-border th {
					border: none;
				}
				td, th {
					border-bottom: 1px solid #dddddd;
					text-align: left;
					padding: 5px!important;
				}
				.tb_resumen_totales td{
					border-bottom: 1px solid #dddddd;
				}
				.font-weight-bold{
					font-weight: bold;
				}
				p{
					margin:0;
					padding: 0;
				}
				tr:nth-child(even) {
					background-color: #f0f0f0;
				}
				#invoice-total {
					width: 260px;
					position: relative;
					font-weight: 700;
					float: right!important;
				}
				.border-bottom-black {
					border-bottom: 1px solid #000;
					position: absolute;
					bottom: em;
					right: 0;
					z-index: 2;
					width: 100%;
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
				.col-6 {
					width: 50%;
					float: left;
					padding: 0;
				}
				.footer-table {
					height: 200px;
					width: 100px;
				}
				.footer-table p{ padding-bottom: 10px}
				
				.img-header {
					padding-top: 2em;
				}
				.method-price, #invoice-total, .footer-table {
					float: left;
					margin: 10px;
					padding: 10px;
					position: relative;
				}
				.method-price{
					/*height: 200px;*/
    				width: 500px;
				}
				.method-price p{
					padding: 0;
					margin: 0;
				}
				.invoice-number{
					position: absolute;
					top: 1.3em;
					right: 1em;
				}
				.invoice-date{
					position: absolute;
					bottom: -1.3em;
					right: 1em;
				}
				.masthead {
					display: block;
					height: 50px;
					position:relative;
					margin-bottom: 2.5em;
				}
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 400px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-verde.jpg);
					background-repeat: no-repeat;
					z-index: -1;
				}
				
				';
				if($cpe['cabecera']['estado_documento'] == 'anulado') {
					$html = $html.'
				
					body::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 800px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
						background-repeat: no-repeat;
						backgroud-position: center right;
						z-index: -1;
					} ';
				}
				
				 if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
					$html = $html.'
					body::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 980px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
						z-index: -1;
					} ';
				}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
					$html = $html.'
					body::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 980px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
						z-index: -1;
					} ';
				}
			
			
				$html = $html.'
				.text-theme-default{
					color: #77af18;
				}
				.table-3 {
					width: auto;
				}
				.text-theme-secundary{
					color: #333333;
				}
				.bg-theme-default{
					background: #77af18;
				}
				.table-cuentas {
					font-size: 11px;
				}
				.table-cuentas td, .table-cuentas th {
					padding: 5px;
				}
				.empresa-info{
					height: 140px;
					margin-bottom: 1.5em;
				}
				.tb_resumen_totales {
					width: auto;
				}
				.tb_resumen_totales td {
					padding: 5px 8px 5px 20px;
					font-weight: 700;
				}
				.resumen_totales {
					height: 150px;
				}
				.table_detraccion td, .table_percepcion td{
					padding: 5px;
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
				.table-main-head, .tb_resumen_totales{
					background: #fff;
				}
				.table-retencion td{
					font-size: 11px;
					text-align: center;
				}
			</style>
			<div class="masthead">
				<div class="head-mad">';
					if(empty($cpe['emisor']['logo_rectangular'])) {
						$html = $html.'
						<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
						
					}else{
						$html = $html.'
						<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
					}
				$html = $html.'
				</div>
				<div class="invoice-number">
					<h4 class="text-white text-uppercase font-weight-bold" style="font-size:15px!important;">'.$cpe['cabecera']['nombre_cpe'].': <br> '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h4>
				</div>
			</div>
			<div class="empresa-info w-100">
				<div class="col-6">
					<h5 class="text-theme-default font-weight-bold text-uppercase mb-2 mt-3">'.$cpe['emisor']['nombre_comercial'].'</h5>
					<p><span class="text-theme-default font-weight-bold">R.U.C. </span>'.$cpe['emisor']['ruc'].'</p>  
					<p>'.$cpe['emisor']['direccion'].'</p>
					<p>'.$cpe['emisor']['ubigeo'].'</p>
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
					<p><i class="flaticon-envelope mr-2 text-theme-default"></i>'.$cpe['emisor']['email'].'</p>';
					if(!empty($cpe['emisor']['sitio_web'])) {
						$html = $html.'
						<p><i class="flaticon-placeholder-1 mr-2 text-theme-default"></i>'.$cpe['emisor']['sitio_web'].'</p>';
					}
					$html = $html.'
				</div>
				<div class="col-6">
					<div class="client-info">
						<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						 '.$direccion_fiscal.'
						'.$fecha_vencimiento.'
					</div>
					<hr>
					<p><span class="text-theme-default font-weight-bold">Fecha de Emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
					<p><span class="text-theme-default font-weight-bold">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'.</p>
					<p><span class="text-theme-default font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'.</p>
					'.$orden_compra.'
					'.$num_guia.'
					'.$nro_placa.'
					
					'.$descripcion_nota.'
					'.$documento_relacionado.'
				</div>
			</div>
			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<section class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="text-uppercase bg-theme-default  font-weight-bold text-white text-center">
							<th>Cant.</th>
							<th>Descripción</th>
							<th width="150px" class="text-center">Precio</th>
							<th>Unid/Med</th>
							<th class="text-center">Afect. IGV</th>
							<th class="text-right">Importe</th>
						</tr>
						'.$items_detalle_html.'
						<tr>
							<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table table-no-border notas_doc">
					<tbody>
						<tr>
							<td style="vertical-align: top;">
								'.$nota_doc.'
								<div class="single-footer-wight box-main">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="50px" class="float-left mr-2 mb-3">
									<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-theme-default font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$aviso_texto_pruebas.'
									'.$text_pdf_3.'
								</div>
							</td>
							<td style="width:270px; vertical-align: top;" class="p-0">
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				
			</section>
			<footer>
				'.$html_lista_anticipos.'
				'.$html_detraccion.'
				'.$html_percepcion.'
				'.$html_lista_cuotas.'
				'.$html_retencion_base.'
				'.$html_cuentas_corrientes.'
				<div class="col-12">';
					
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mt-2 mb-4" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="150px" style="border-top: 1px solid #dddddd;"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td style="border-top: 1px solid #dddddd;"> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span>  '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mt-2 mb-4" style="width:49%;">
						<tbody>
							<tr>
								<td width="150px" style="border-top: 1px solid #dddddd;"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td style="border-top: 1px solid #dddddd;"> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span> '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
					
				</div>
				
			</footer>
		</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	
	//Nuevos diseños
	public function get_html_plantilla_a4_9($cpe) {  //ID: 36, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor
		
		//Dirección
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-primary">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-primary">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-primary">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-primary">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-primary">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 2px; background:#f7921c"></div>
			<p class="mb-2">'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3 mb-3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
	

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items td_descripcion" style="text-align: left;"><div style="width: 330px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center" width="150px">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td class="text-center">'.$item['unidad_medida'].'</td>
				<td class="text-center">'.$item['texto_tipo_operacion'].'</td>
				<td width="150px" class="text-right">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		
		
		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
				<tr class="bg-theme-color font-weight-bold">
					<td>Total a Pagar:</td>
					<td class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
        		</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr class="bg-theme-color font-weight-bold">
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-theme-color text-white text-uppercase">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}


		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 20px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td colspan="4" class="text-uppercase font-weight-bold bg-theme-color  text-white" style="font-size: 10px">Detracción de Monedas en soles</td>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<td colspan="3" class="text-uppercase font-weight-bold bg-theme-color  text-white" style="font-size: 10px">Detracción de Monedas en soles</td>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
		
			<table class="table_percepcion table">
				<tr>
					<td colspan="3" class="text-uppercase font-weight-bold bg-theme-color  text-white" style="font-size: 10px">Comprobante de percepción</td>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table table-retencion" style="margin:auto">
					<tbody>
						<tr class="bg-theme-color text-white text-uppercase">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<div style="width:33.333333333333%; float:left; height: 100px">
					<p class="tabla_cuentas"><img src="'.$cuenta['url_logo_banco'].'"  width="40px" class="img-cuenta mr-2"> <span class="font-weight-bold">Titular:</span> '.$cuenta['titular'].'</p>
					<p><span class="font-weight-bold">Banco:</span> '.$cuenta['nombre_banco'].'</p>
					<p class="tabla_cuentas"><span class="font-weight-bold">N° cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'
					<p><span class="font-weight-bold">CCI:</span> '.$cuenta['cci'].'</p>
				</div>
			
			';
		}

		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<footer class="w-100 pt-2" style="border-top:  1px solid #dddddd;" style="height: 80px">
				<p class="text-primary font-weight-bold mb-1">Cuentas:</p>
				'.$html_cuentas_corrientes.'
				
			</footer>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-main-head" style="margin:auto; width: auto">
					<tbody>
						<tr class="bg-theme-color text-white text-uppercase">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger text-center">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
	
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 11px!important;
				font-family: "Lato", sans-serif;
			
			}
			
			.display-none{
				display: none;
			}
			.tb_nota{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			table {
				font-size: 11px!important;				
			}
		
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;

			}
			td, th {
				text-align: left;
				padding: 5px;
			}
			
			.box-content{
				width: 100%;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-8{
				width: 66.666667%;
				float: left;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.col-12 {
				width: 100%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			.text-theme-color{
				color: #f9c24e;
			}
			.bg-theme-color{
				background: #f9c24e!important;
			}
			/* Clases de la plantilla*/
			.box-content{
				width: 100%;
			}
			.icons_info{
				margin-top: -5em;
			}
			
			
			/*.line-width-head{
				width: 17%;
				height: 2px;
				background: #f9c24e;
				position: absolute;
				right: 16.5em;
				top: 4em;
			}
			.line-head-height{
				width: 2px;
				height: 150px;
				background: #f9c24e;
				position: absolute;
				right: 16.3em;
				top: 4em;
			}*/
		
			.masthead{
				background: #202332;
				height: 300px;
				padding: 2em 4em 0 4em;
				color: #fff;
			    position:relative;
			}
			';
				if($cpe['cabecera']['estado_documento'] == 'anulado') {
					$html = $html.'
					.masthead::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 800px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
						background-repeat: no-repeat;
						backgroud-position: center right;
						z-index: -1;
					} ';
				} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
					$html = $html.'
					.masthead::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 950px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
						z-index: -1;
					} ';
				}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
					$html = $html.'
					.masthead::before{
						content: " ";
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 950px;
						background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
						z-index: -1;
					} ';
				}
			
				$html = $html.'
			.table-main-head{
				background: #fff;
			}
			.social {
				padding-left: 0;
				list-style-type: none;
			}
			.social_media p{
				font-size: 11px!important;
			}
			.social_media p .icons {
				color: #202332;
				width: 30px;
				height: 30px;
				line-height: 80px;
				border-radius: 50%;
				background-color: #f9c24e;
				text-align: center;
				padding: 5px 5px;
				margin-right: 5px;
				display: inline-block;
			}
			.text-theme-primary{
				color: #f9c24e!important;
			}
			/* section 2 */
			.nombre_documento_titular {
				position: relative;
				top: -.8em;
				right: -1em;
				color: #202332;
			}
			.title_price {
				font-weight: 300;
				text-transform: uppercase;
				font-size: 20px;
				border-left: 2px solid #202332;
				padding-left: .5em; 
				display: inline;
				color: #202332;
			}
			.total_invoice{
				background: #f9c24e;
				padding: 10px;
				width: 100%;
				height: 45px;
				position: relative;
			}

			.bg-shape-header{
				position: absolute;
				right: 0;
				top: -2.9em;
			}
			/* Section 3 */
			.single-date {
				float: left;
				height: 90px;
				padding: 0 10px;
				width: 33.333333333333%;
			}
			td, th {
				border-bottom: 1px solid #dddddd;
				padding: 5px 2px 5px 5px!important;
			}
			tr:nth-child(even) {
				background-color: #eee;
			}
			.tb_resumen_totales {
				/* float: right; */
				width: 100%;
			}
			.tb_resumen_totales  tr:nth-child(even){
				background-color: #fff;
			}
			.table {
				width: 100%;
				margin-bottom: 0;
				color: #212529;
			}
			.table_datos_main td{
				border: 0;
			}
			.table_detraccion td, .table_percepcion td{
				padding: 5px;
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
			.table-retencion td{
				font-size: 11px;
				text-align: center;
			}
		</style>
		<body>
			<div class="masthead">
				<div class="box-content">
					<div class="col-12 position-relative mb-4">';
						if(empty($cpe['emisor']['logo_rectangular'])) {
							$html = $html.'
							<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
							
						}else{
							$html = $html.'
							<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
						}
						$html = $html.'
						<div class="line-width-head"></div>
						<div class="line-head-height"></div>
					</div>
					<div class="col-4">
						<h6 class="font-weight-bold text-uppercase text-theme-primary">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<h6 class="font-weight-bold">R.U.C. '.$cpe['emisor']['ruc'].'</h6> 
						'.$direccion_empresa.'
					</div>
					<div class="col-3">
						<h6 class="font-weight-bold text-uppercase text-theme-primary">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h6>
					</div>
					<div class="col-5 icons_info">
						<div class="social_media">
							<p class="pl-1 mb-2"><span class="icons"><img src="http://arpsystem.com.pe/facturacionv8/img/call.png" width="20px"/></span> '.$cpe['emisor']['telefono'].'</p>
							<p class="pl-1 mb-2"><span class="icons"><img src="http://arpsystem.com.pe/facturacionv8/img/sobre.png" width="20px"/> </span>'.$cpe['emisor']['email'].'</p>
							';
							if(!empty($cpe['emisor']['sitio_web'])) {
							$html = $html.'
							<p class="pl-1 mb-2"><span class="icons"><img src="http://arpsystem.com.pe/facturacionv8/img/web.png" width="20px"/></span>'.$cpe['emisor']['sitio_web'].'</p>
							';
							}
							$html = $html.'
							
						</div>
					</div>
				</div>
			</div>
			<div class="total_invoice">
				<div class="bg-shape-header">
					<img src="http://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape_pl_13.png" width="500px"/>
				</div>
				<div class="col-6">
					<h6 class="title_price"> Total: <span class="font-weight-bold">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total']).'</span></h6>
				</div>
				<div class="col-6 text-center">
					<h6 class="nombre_documento_titular text-uppercase font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</h6>
				</div>
			</div>
			<div class="box-content mt-4">
				<table class="w-100 table_datos_main">
					<tr>
						<td width="50%" valign="top">
						<p><span class="text-theme-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							<p><span class="text-theme-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
							'.$direccion_fiscal.'
							'.$orden_compra.'
							'.$fecha_vencimiento.'
							'.$descripcion_nota.'
							'.$documento_relacionado.'
						</td>
						<td width="50%" valign="top">
							<p><span class="text-theme-primary font-weight-bold">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
							<p><span class="text-theme-primary font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
							<p><span class="text-theme-primary font-weight-bold">Forma de Pago:</span> 	'.$cpe['cabecera']['forma_de_pago'].'</p>
						
							'.$nro_placa.'
							'.$num_guia.'
							
							
						</td>
					</tr>
				</table>
			</div>
			<div class="text-center mt-2">
				'.$text_pdf_1.'
			</div>
			<table class="table-main-head table mt-3">
				<tbody>
					<tr class="bg-theme-color text-white text-uppercase">
						<th>Cant.</th>
						<th width="300px">Descripción</th>
						<th>Precio Unitario</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th class="text-right">Importe</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
					</tr>
					'.$text_pdf_2.'
				</tbody>
			</table>
			<div class="table_resum">
				<table class="table">
					<tr>
						<td style="border:none" width="66.666667%">
							'.$nota_doc.'	
							<div class="float-left">
								<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="mr-2 mb-3" />
							</div>
							<p class="mb-1 "><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
							<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
							<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
							'.$text_pdf_3.'
							'.$aviso_texto_pruebas.'
						</td>
						<td style="border:none;margin:0;padding:0!important" width="33.333333333333%">
							<table class="tb_resumen_totales mb-4" >
								<tbody>
									'.$resumen.'
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_lista_cuotas.'
			'.$html_retencion_base.'
			'.$html_cuentas_corrientes;
			
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="140px" style="border-top: 1px solid #dddddd;"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td style="border-top: 1px solid #dddddd;"> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span>  '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="140px" style="border-top: 1px solid #dddddd;"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td style="border-top: 1px solid #dddddd;"> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span> '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
		
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_10($cpe) {  //ID: 39, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO	
		$this->view->disable();
		
		//Dirección
		
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<i class="flaticon-placeholder-1 text-theme-primary mr-2"></i>'.$cpe['emisor']['direccion'].'. ';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = $cpe['emisor']['ubigeo'];
		} else {
			$ubigeo_empresa = '';
		}
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-primary">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-primary">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-primary">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-primary">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-primary">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
	
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 2px; background:#f7921c"></div>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
	

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-left td_descripcion"><div style="width: 210px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right pr-2" >'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>

			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

	
	
		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
        			<td>Total:</td>
        			<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
        			</td>
        		</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		
				<tr>
					<td colspan="2">
						<div class="bg-theme-primary p-2 border-radius tb-total">
							<div class="col-6 pt-2">
								Total a pagar:
							</div>
							<div class="col-6 text-right pt-2">
								<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
							</div>
						</div>
					</td>
				</tr>
        		';
		    }else{
	
				$resumen = $resumen.'
				<tr>
					<td colspan="2">
						<div class="bg-theme-primary p-2 border-radius tb-total">
							<div class="col-6 pt-2">
								Total:
							</div>
							<div class="col-6 text-right pt-2">
								<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
							</div>
						</div>
					</td>
				</tr>
				';
			}
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
					<tr class="bg-theme-primary text-center text-white text-uppercase">
							<th>N°</th>
							<th width="100px">Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<td colspan="4" class="text-uppercase font-weight-bold bg-theme-primary text-white" style="font-size: 10px">Detracción de Monedas en soles</td>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<td colspan="3" class="text-uppercase font-weight-bold bg-theme-primary text-white" style="font-size: 10px">Detracción de Monedas en soles</td>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<table class="table_percepcion table mb-3">
				<tr>
					<td colspan="3" class="text-uppercase font-weight-bold bg-theme-primary text-white" style="font-size: 10px">Comprobante de percepción</td>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<table class="table-cuentas table-retencion pl-2" style="width: 100%">
					<tbody>
						<tr>
							<td colspan="3" class="text-uppercase font-weight-bold bg-theme-primary text-white text-left" style="font-size: 10px">Información de la retención:</td>
						</tr>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuentas 

		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr>
					<td rowspan="2" class="text-center">'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['tipo'].' - '.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
				<tr>
					<td colspan="3">Titular: '.$cuenta['titular'].'</td>
				</tr>
			
			';
		}

		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas" style="margin:auto">
					<tbody>
						<tr class="bg-theme-primary text-center text-white text-uppercase">
							<th>Banco</th>
							<th>Tipo cta</th>
							<th>N° de cuenta</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
		
	
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-main-head" style="margin:auto; width: auto">
					<tbody>
						<tr class="bg-theme-primary text-center text-white text-uppercase">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-center  mt-2 text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/facturacionv8/extras/fontawesome/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 11px!important;
				font-family: "Lato", sans-serif;

			}
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}
			$html = $html.'
			.display-none{
				display: none;
			}
			
			table {
				font-size: 11px!important;				
			
			}
			.notas_doc td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;

			}
			.content_cuentas table{
				margin: 10px auto;
	
			}
			td, th {
				text-align: left;
				padding: 5px;
			}
			
			.box-content{
				width: 100%;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.col-12 {
				width: 100%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			.text-theme-primary{
				color: #f7921c;
			}
			.bg-theme-primary{
				background: #f7921c!important;
			}
			/* Cabecera */
			
			.masthead{
				position: relative;
			}
			.masthead::before {
				content: " ";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 300px;
				background-image: url(http://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-naranja.jpg);
				background-repeat: no-repeat;
			
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.shape-masthead{
				position:absolute;
				left: 0;
				top: 0;
			}
			/* Datos cliente */
			.single_client{
				height: 150px;
			}
			/* Tabla productos */
			.table-main-head{
				margin-bottom: 0;
				background: #fff;
			}
			.table-main-head td, .table-main-head th, .table-cuentas td, .table-cuentas th{
				border-bottom: 1px solid #dddddd;
			}
			.table-main-head td, .table-main-head th{
				padding: 5px;
			}
			.table-main-head tr:nth-child(even), .table-cuentas tr:nth-child(even) {
				background-color: #eee;
			}
			.border-radius {
				border-radius: 20px;
			}
			.tb-total {
				width: 100%;
				font-weight: 700;
				text-transform: uppercase;
				height: 45px;
				padding: 1em;
			}
			.table td, .table th {
				padding: 5px;
				vertical-align: top;
				border-top: 1px solid #dee2e6;
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
			.table-retencion td{
				font-size: 11px;
				text-align: center;
			}
		</style>
			<body>
				<div class="masthead" style="margin-top:1em">
					<div class="col-12">';
					if(empty($cpe['emisor']['logo_rectangular'])) {
						$style_class ='pt-3 mt-5';
						$html = $html.'
						<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" '.$style_class.' />';
						
					}else{
						$style_class= 'class="pt-3 mt-5"';
						$html = $html.'
						<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" '.$style_class.' />';
					}
						$html = $html.'
					</div>
					<table>
						<tr>
							<td width="50%">
								<h5 class="font-weight-bold text-uppercase text-theme-primary mt-2">'.$cpe['emisor']['nombre_comercial'].'</h5>
								<p class="font-weight-bold">R.U.C. '.$cpe['emisor']['ruc'].'</p>       
								<p>'.$direccion_empresa.' '.$ubigeo_empresa.'</p>
								<p><i class="flaticon-call text-theme-primary mr-2"></i>'.$cpe['emisor']['telefono'].'</p>
								<p><i class="flaticon-envelope text-theme-primary mr-2"></i>'.$cpe['emisor']['email'].'<p>
								';
								if(!empty($cpe['emisor']['sitio_web'])) {
								$html = $html.'
								<p><i class="flaticon-globo text-theme-primary mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>
								';
								}
								$html = $html.'
							</td>
							<td width="50%">
							<h6 class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'</h6>
							<h6 class="font-weight-bold text-uppercase text-theme-primary">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h6>
							</td>
						</tr>
					</table>
					
				</div>
				<div style="padding-bottom: 1em; margin-bottom: 1em; border-bottom:  1px solid #ededed;"></div>
				<table class="w-100 table_datos_main">
					<tr>
						<td width="50%" valign="top">
							<p><span class="text-theme-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
							<p><span class="text-theme-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							'.$direccion_fiscal.'
							'.$orden_compra.'
							'.$num_guia.'
							
						</td>
						<td width="50%" valign="top">
						<p><span class="text-theme-primary font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
						<p><span class="text-theme-primary font-weight-bold">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
						<p><span class="text-theme-primary font-weight-bold">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
						'.$nro_placa.'
						'.$descripcion_nota.'
						'.$documento_relacionado.'
						'.$fecha_vencimiento.'
						</td>
						
					</tr>
				</table>
				
				<div class="text-center">
					'.$text_pdf_1.'
				</div>
				<section class="section-tables mt-3">
					<table class="table table-main-head">
						<tbody>
							<tr class="bg-theme-primary text-white text-uppercase">
								<th>Cant.</th>
								<th>Descripción</th>
								<th width="160px">Precio Unitario</th>
								<th width="100px">UNID/MED </th>
								<th>AFECT.IGV</th>
								<th width="160px">Importe</th>
							</tr>
							<tr>
								'.$items_detalle_html.'
							</tr>
							<tr>
								<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
							</tr>
							'.$text_pdf_2.'
						</tbody>
					</table>
					<table  class="table table-no-border notas_doc">
						<tbody>
							<tr>
								<td style="width:80px;">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-2 mb-5">
								</td>
								<td style="vertical-align: top;" class="pr-4">
									'.$nota_doc.'
									<p class="mb-1"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-theme-default font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$text_pdf_3.'
								</td>
								<td style="width:300px; vertical-align: top;" class="p-0">
									<table style="width: 100%;" class="tb_resumen_totales">
										<tbody>
											'.$resumen.'
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</section>
				<div class="col-12 pl-2">
					'.$html_detraccion.'
					'.$html_percepcion.'
				</div>
				<div class="col-12 content_cuentas mb-4">
					'.$html_lista_anticipos.'
					'.$html_lista_cuotas.'
					'.$html_retencion_base.'
					'.$html_cuentas_corrientes.'
					</div>
				<footer class="mt-1">';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span> '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span>  '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span>  '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
					<div class="col-12 content_cuentas mb-4">
						'.$aviso_texto_pruebas.'
					</div>
					
				</footer>
			</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_11($cpe) { //ID: 42, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<i class="flaticon-placeholder-1 text-theme-default mr-2"></i> <span class="text-theme-default font-weight-bold">Dirección:</span>'.$cpe['emisor']['direccion'].' '.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$direccion_empresa = '';
		}
	
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-default ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-default">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-default">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-default">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-default">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
		
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<div class="line_theme_note mt-2 mb-2" style="width: 50px;height: 1px;background:#136493;"></div>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}	
	
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr style="border-bottom: 1px solid #136493;border-top: 1px solid #136493;">
			<td colspan="6" class="text-uppercase text-left" >'.$cpe['sucursal']['txt_pdf_a4_2'].'</td></tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-default">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-default">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-default">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-default">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="td_descripcion cabecera_detalle_items" style="text-align: left;"><div style="width:230px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center" width="150px">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right" width="100px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>

			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

	
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
				<tr class="bg-theme-default text-white">
					<td>Total a Pagar:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr class="bg-theme-default text-white">
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}
		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<th colspan="4" class="text-uppercase font-weight-bold text-left" style="font-size: 10px">Detracción de Monedas en soles</th>
					</tr>
					<tr>

						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
			
				<table class="table_detraccion table">
					<tr>
						<th colspan="3" class="text-uppercase font-weight-bold text-left" style="font-size: 10px">Detracción de Monedas en soles</th>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
		
			<table class="table_percepcion table">
				<tr>
					<th colspan="3" class="text-uppercase font-weight-bold" style="font-size: 10px">Comprobante de percepción</th>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table-main-head table-retencion" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
	
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<div style="width:33.333333333333%; float:left; height: 100px">
					<p class="tabla_cuentas"><img src="'.$cuenta['url_logo_banco'].'"  width="40px" class="img-cuenta mr-2"> <span class="font-weight-bold">Titular:</span> '.$cuenta['titular'].'</p>
					<p><span class="font-weight-bold">Banco:</span> '.$cuenta['nombre_banco'].'</p>
					<p class="tabla_cuentas"><span class="font-weight-bold">N° cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'
					<p><span class="font-weight-bold">CCI:</span> '.$cuenta['cci'].'</p>
				</div>
			
			';
		}

		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<footer class="w-100 pt-2" style="border-top:  1px solid #dddddd;" style="height: 80px">
				<p class="text-primary font-weight-bold mb-1">Cuentas:</p>
				'.$html_cuentas_corrientes.'
			
			</footer>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="table-main-head pl-2" style="margin:auto; width: auto">

					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
			
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="table-main-head" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}

		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/facturacionv8/extras/fontawesome/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 10px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 11px!important;
				font-family: "Lato", sans-serif;
			}
		
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 3em;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				}';
			}
			$html = $html.'
			 h5,  table {
				font-size: 11px;
			}
			.table-no-border td, .table-no-border th {
				border: none;
			}
			.notas_doc td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			header {
				border-bottom: 1px solid #007bff;
				text-align: center;
				height: 40px;
				margin-bottom: 10px;
			}
			p{
				margin: 0;
				padding: 0;
			}
			td, th {
				text-align: left;
				padding: 8px;
			}
		
			#columna1{
				margin-left: 0;
				padding-left: 0;
			}
			#ultima_columna{
				margin-right: 0;
				padding-right: 0;
				width: 210px;
			}
				#invoice-total {
				float: right;
			}
			.borde-primary {
				border-top: 1px solid #136493;
			}
			.contenedor {
				margin: 10px auto;
				float: right;
				width: 100%;
				padding-top: 1em;
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
			.col-6 {
				width: 50%;
				float: left;
				padding: 0;
			}
			.col-7 {
				width: 55%;
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
			.invoice-number{
				position: absolute;
				top: 1.1em;
				right: 1em;
			}
			.masthead {
				display: block;
				height: 50px;
				position:relative;
				margin-bottom: 2.5em;
			}
			.masthead::before{
				content: " ";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 400px;
				background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-azul.jpg);
				background-repeat: no-repeat;
				z-index: -1;
			}
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.footer-table {
				float: left;
				height: 200px;
				margin: 10px;
				padding: 10px;
				width: 100px;
			}
			.footer-table p{ padding-bottom: 10px}
			.footer-table {
				text-align: right;
			}
			.single-date {
				float: left;
				height: 90px;
				padding: 0 10px;
				width: 33.333333333333%;
			}
			.table-main-head{
				width: 100%;
				border-collapse: collapse;
			}
			.table-main-head th{
				border-bottom: 1px solid #136493;
			}
			.table-main-head td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.tb_resumen_totales {
				width: 100%;
			}
			.tb_resumen_totales td {
				padding: 8px 10px;
				font-weight: 700;
			}

			.resumen_totales {
				height: 200px;
			}
			.text-theme-default{
				color: #136493;
			}
			.bg-theme-default{
				background: #136493;
			}
			.text-title-theme {
				font-size: 11px;
				font-weight: 400;
			}
			.head-date{
				height: 170px;
			}
			.table_detraccion td, .table_percepcion td, .table_detraccion th, .table_percepcion th {
				border-bottom: 1px solid #136493;
				padding: 5px;
			}
			.table_detraccion th, .table_percepcion th {
				border-top: 0;
				text-align: left;
				padding: 5px;
			}
			.table-main-head .td_descripcion{
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
		<body>
			<div class="masthead">
				<div class="logo-content">';
					if(empty($cpe['emisor']['logo_rectangular'])) {
						$html = $html.'
						<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" class="mb-3" />';
						
					}else{
						$html = $html.'
						<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" class="mb-3" />';
					}	
					$html = $html.'
				</div>
				<div class="invoice-number">
					<h5 class="text-white text-uppercase font-weight-bold" style="font-size:14px!important;">'.$cpe['cabecera']['nombre_cpe'].': <br>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h5>
				</div>
			</div>
			<div class="w-100 head-date mt-5">
				<div class="col-4">
					<p class="text-title-theme">Empresa que emite:</p>
					<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#136493"></div>
					<h6 class="text-theme-default font-weight-bold text-uppercase mb-2" style="font-size: 12px">'.$cpe['emisor']['nombre_comercial'].'</h6>
					<p class="mb-0"><span class="text-theme-default font-weight-bold">R.U.C. </span>'.$cpe['emisor']['ruc'].'</p>
					'.$direccion_empresa.' 
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
					<p><i class="flaticon-envelope mr-2 text-theme-default"></i>'.$cpe['emisor']['email'].'</p>';
					if(!empty($cpe['emisor']['sitio_web'])) {
						$html = $html.'
						<p><i class="flaticon-globo mr-2 text-theme-default"></i>'.$cpe['emisor']['sitio_web'].'</p>';
					}
					$html = $html.'
				</div>
				<div class="col-4">
					<p class="text-title-theme">Cliente:</p>
					<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#136493"></div>
					<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
					<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
					'.$direccion_fiscal.'
					'.$fecha_vencimiento.'
				</div>
				<div class="col-4">
					<p class="text-title-theme">Detalles:</p>
					<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#136493"></div>
					<p><span class="text-theme-default font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
					<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
					<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
					'.$orden_compra.'
					'.$nro_placa.'
					'.$descripcion_nota.'
					'.$documento_relacionado.'
					'.$num_guia.'
					
				</div>
			</div>

			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<section>
				<table class="table-main-head">
					<tbody>
						<tr class="text-uppercase">
							<th>Cant.</th>
							<th>Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th>Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr style="border-bottom: 1px solid #136493;border-top: 1px solid #136493;">
							<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table-no-border notas_doc">
					<tbody>
						<tr>
							<td style="vertical-align: top;">
								<table  class="table-no-border">
									<tbody>
										<tr>
											<th style="width:130px;">
												<img style="width: 60px; margin-bottom: 11px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" />
											</th>
											<td class="texto_general">
												';
												if(!empty($nota_doc)) {
												$html = $html.'
												'.$nota_doc.'
												';
												}
												$html = $html.'
												<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
												<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
												<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
												<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
												
												
											</td>
										</tr>
										<tr>
											<td colspan="2">'.$aviso_texto_pruebas.'</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td style="width:250px; vertical-align: top; padding: 0!important">
								<table class="tb_resumen_totales total_invoice">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			'.$text_pdf_3.'
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_lista_cuotas.'
			'.$html_retencion_base.'
			'.$html_cuentas_corrientes;
			
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span> '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
					}else{
						$tb_yape = '';
						
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-default">Titular:</span>  '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-theme-default">N° de Telf:</span>  '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	
	public function get_html_plantilla_a4_12($cpe) {  //ID: 45, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
	
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-color ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-color">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-color">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-color">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-color">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		
	
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="7" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td></tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-color">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-color">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-color">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-color">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr>
				<td align="center">'.$item['codigo'].'</td>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-left td_descripcion"><div style="width: 300px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right"><div style="width: 100px !important;">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</div></td>
			</tr>

			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		
		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right font-weight-bold">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
				<tr class="bg-theme-color text-white font-weight-bold">
					<td>Total a Pagar:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr class="bg-theme-color text-white font-weight-bold">
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<td colspan="4" class="text-uppercase font-weight-bold bg-theme-color text-white " style="font-size: 12px">Detracción de Monedas en soles</td>
					</tr>
					<tr>

						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
			
				<table class="table_detraccion table">
					<tr>
						<td colspan="3" class="text-uppercase font-weight-bold bg-theme-color text-white " style="font-size: 12px">Detracción de Monedas en soles</td>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción

		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
		
			<table class="table_percepcion table">
				<tr>
					<td colspan="3" class="text-uppercase font-weight-bold bg-theme-color text-white" style="font-size: 12px">Comprobante de percepción</td>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table-main-head table table-retencion">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas pl-2" style="margin: auto">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="table-3 table-cuentas pl-2" style="margin:auto">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}

		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/facturacionv8/extras/fontawesome/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 15px!important;
				font-family: "Lato", sans-serif;

			}
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.table-main-head{
				background: #fff;
			}
			.display-none{
				display: none;
			}
			
			table {
				font-size: 14px!important;				
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 14px;
			}
			.notas_doc td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;

			}
			td, th {
				text-align: left;
				border-top: 1px solid #dddddd;
				border-bottom: 1px solid #dddddd;
				border-right: 2px solid #fff;
				border-left: 2px solid #fff;
				padding: 10px;
			}
		
			tr:nth-child(even) {
				background-color: #eee;
			}
			.box-content{
				width: 100%;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.col-12 {
				width: 100%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			.text-theme-color{
				color: #5c3588;
			}
			.bg-theme-color{
				background: #5c3588!important;
			}
			/* Clases de la plantilla */
			.circle_head{
				border: 2px solid #fff;
				border-radius: 50%;
				width: 250px;
				height: 250px;
				box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
			}
			.masthead {
				display: block;
				height: 350px;
				position:relative;
				margin-bottom: 2.5em;
				padding: 1em;
			}
			.masthead::before{
				content: " ";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 350px;
				background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/bg_plantilla_13.jpg);
				background-repeat: no-repeat;
				z-index: -1;
			}

			.single-date {
				height: 90px;
			}
			.box-white-content{
				background: #fff;
				border-top-left-radius: 7%;
				border-bottom-left-radius: 7%;
				margin-top: -5em;
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
			.table-retencion td{
				text-align: center;
			}
		</style>
		<body>
			<div class="masthead">
				<div class="col-6">
				 	<div class="circle_head text-center">';
						if(empty($cpe['emisor']['logo_rectangular'])) {
							$html = $html.'
							<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" class="mb-2 mt-5"  />';
							
						}else{
							$html = $html.'
							<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" class="mb-2 mt-5"  />';
						}
						$html = $html.'
						<h5 class="mb-0 text-white "><span class="font-weight-bold">R.U.C. </span>'.$cpe['emisor']['ruc'].'</h5>
					 </div>
					
				</div>
				<div class="col-6 text-right text-white">
					<h5 class="text-uppercase font-weight-bold mt-5" style="font-size:18px!important;">'.$cpe['cabecera']['nombre_cpe'].': <br>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h5>
					
					<p class="text-white font-weight-bold text-uppercase mb-2" style="font-size: 18px">'.$cpe['emisor']['nombre_comercial'].'</p>
					'.$direccion_empresa.' 
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
					<p><i class="flaticon-envelope mr-2 text-theme-default"></i>'.$cpe['emisor']['email'].'</p>';
					if(!empty($cpe['emisor']['sitio_web'])) {
						$html = $html.'
						<p><i class="flaticon-globo mr-2 text-theme-default"></i>'.$cpe['emisor']['sitio_web'].'</p>';
					}
					$html = $html.'
				</div>
			</div>
			<div class="box-white-content">
				<table class="w-100 table_datos_main">
						<tr>
							<td width="33.333333333333%" valign="top">
								<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
								<p><span class="text-theme-color font-weight-bold">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
								'.$orden_compra.'
								'.$fecha_vencimiento.'
							</td>
							<td width="33.333333333333%" valign="top">
								<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
								'.$direccion_fiscal.'
								'.$num_guia.'
								
							</td>
							<td width="33.333333333333%" valign="top">
								<p><span class="text-theme-color font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
								<p><span class="text-theme-color font-weight-bold">Forma de Pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
								'.$nro_placa.'
								'.$descripcion_nota.'
								'.$documento_relacionado.'
							</td>
						</tr>
					</table>	
				</div>
				<div class="text-center mt-2">
					'.$text_pdf_1.'
				</div>
				<table class="table-main-head">
					<tbody>
						<tr class="bg-theme-color text-white text-uppercase">
							
							<th>Cant.</th>
							<th>Código</th>
							<th width="500px">Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th class="text-right">Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="7" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table table-no-border notas_doc">
					<tbody>
						<tr>
							<td style="vertical-align: top;" class="pr-4">
								'.$nota_doc.'
								<div class="single-footer-wight box-main">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="80px" class="float-left mr-2 mb-3">
									<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-theme-default font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$aviso_texto_pruebas.'
									'.$text_pdf_3.'
								</div>
							</td>
							<td style="width:300px; vertical-align: top;" class="p-0">
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<footer class="mt-1">
					'.$html_lista_anticipos.'
					'.$html_detraccion.'
					'.$html_percepcion.'

					<div class="col-12 mb-4">
						'.$html_lista_cuotas.'
						'.$html_retencion_base.'
						'.$html_cuentas_corrientes.'
					</div>
				</footer>';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="150px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
					}else{
						$tb_yape = '';
						
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="150px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
			
			</div>
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_13($cpe) {  //ID: 48, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 text-theme-color mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-color">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-color">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-color">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-color">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-color">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}


		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold text-uppercase">Observación:</p> 
			<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 2px; background:#5baa01"></div>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="7" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td></tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-color">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-color">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-color">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-color">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td align="center">'.$item['codigo'].'</td>
				<td class="text-left td_descripcion"><div style="width: 300px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right"><div style="width: 100px !important;">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</div></td>
			</tr>

			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

	
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right text-uppercase">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		
				<tr class="bg-theme-color text-white font-weight-bold">
					<td class="text-uppercase">Total a Pagar:</td>
					<td class="text-right text-uppercase">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr class="bg-theme-color text-white font-weight-bold">
					<td class="text-uppercase">Total:</td>
					<td class="text-right text-uppercase">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}
		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<th colspan="4" class="text-uppercase font-weight-bold text-left">Detracción de Monedas en soles</th>
					</tr>
					<tr>

						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
			
				<table class="table_detraccion table">
					<tr>
						<th colspan="4" class="text-uppercase font-weight-bold text-left">Detracción de Monedas en soles</th>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
		
			<table class="table_percepcion table">
				<tr>
					<th colspan="3" class="text-uppercase font-weight-bold">Comprobante de percepción</th>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table table-retencion">
					<tbody>
						<tr class="bg-theme-color text-white text-uppercase">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas w-100 pl-2" style="margin: auto">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
					<tr class=" bg-theme-color text-white">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="table-3 table-cuentas pl-2" style="margin:auto">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}

		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 15px!important;
				font-family: "Lato", sans-serif;
				position:relative;
				
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.display-none{
				display: none;
			}
			
			table {
				font-size: 14px!important;				
			}
		
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;

			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 14px;
			}
			.notas_doc td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			td, th {
				text-align: left;
				padding: 7px;
			}
			.table_retencion th{
				color: #000;
			}
			.box-content{
				width: 100%;
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
			.col-4-r{
				width: 33.333333333333%;
				float: right;
				padding: 0;
			}
			.col-6-r{
				width: 50%;
				float: right;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-7-r {
				width: 55%;
				float: right;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.col-12 {
				width: 100%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			.text-theme-color{
				color: #5baa01;
			}
			.bg-theme-color{
				background: #5baa01!important;
			}
			/* Clases de la plantilla */
			.masthead {
				height: 240px;
			}
			.single-date{
				height: 130px;
			}
			.single-date p{
				font-size: 13px;
			}
			.box-content{
				width: 100%;
			}
			.body_head{
				z-index: 10;
			}
			.total_invoice{
				width: 100%;
				height: 75px;
				z-index: -1;
				color: #fff;
				background: #5baa01;
				position: relative;
			}
			.total_invoice::before{
				content: " ";
				position: absolute;
				top: 2em;
				right: 0;
				width: 100%;
				height: 400px;
				background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-verde-2.jpg);
				background-repeat: no-repeat;
				z-index: -1;
			}
			.table-main-head{
				width: 100%;
				border-bottom: 1px solid #5baa01;
			}
			.table-main-head th{
				color: #5baa01;
			}
			.tb_resumen_totales {
				float: right;
			} 
			.tb_resumen_totales td {
				border: none;
			} 
			.box-resumen{
				height: 200px;
			}
			.table_detraccion td, .table_percepcion td, .table_detraccion th, .table_percepcion th {
				border-bottom: 1px solid #5baa01;
				padding: 5px;
			}
			.table_detraccion th, .table_percepcion th {
				border-top: 1px solid #5baa01;
				text-align: left;
				padding: 5px;
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
			.table-retencion td{
				text-align: center;
			}
		</style>
		<body class="body_head">
			<table width="100%">
				<tr>
					<td colspan="3" style="padding: 0">'; 
						if(empty($cpe['emisor']['logo_rectangular'])) {
							$html = $html.'
							<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
							
						}else{
							$html = $html.'
							<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
						}
						$html = $html.'
					</td>
				</tr>
				<tr>
					<td width="33.333333333333%" valign="top">
						<h4 class="text-theme-color font-weight-bold text-uppercase mb-2" style="font-size: 17px">'.$cpe['emisor']['nombre_comercial'].'</h4>
						<p class="mb-0"><span class="text-theme-color font-weight-bold">R.U.C. </span>'.$cpe['emisor']['ruc'].'</p>
						'.$direccion_empresa.' 
						<p><i class="flaticon-call text-theme-color mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].' </p>
						<p><i class="flaticon-envelope mr-2 text-theme-color"></i>'.$cpe['emisor']['email'].'</p>';
						if(!empty($cpe['emisor']['sitio_web'])) {
							$html = $html.'
							<p><i class="flaticon-globo mr-2 text-theme-color"></i>'.$cpe['emisor']['sitio_web'].'</p>';
						}
						$html = $html.'
					
					</td>
					<td width="33.333333333333%" valign="top">
						<p class="text-title-theme">Cliente:</p>
						<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#5baa01"></div>
						<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$direccion_fiscal.'
					</td>
					<td width="33.333333333333%" valign="top">
						<p class="text-title-theme">Detalles:</p>
						<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#5baa01"></div>
						<p><span class="text-theme-color font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<p><span class="font-weight-bold text-theme-color">Forma de Pago:</span> '.$cpe['cabecera']['forma_de_pago'].'
						<p class="th_moneda"><span class="text-theme-color font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
						'.$num_guia.'
						'.$orden_compra.'
						'.$nro_placa.'
						'.$fecha_vencimiento.'
						
						'.$descripcion_nota.'
						'.$documento_relacionado.'
					</td>
				</tr>
			</table>
			<div class="text-center mt-3">
				'.$text_pdf_1.'
			</div>
			<div class="total_invoice mt-3">
				<div class="col-6-r text-right pr-3 pt-1 mb-4">
					<p class="nombre_documento_titular text-uppercase font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].': <br> '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
					<h5 class="title_price"> Total: <span class="font-weight-bold">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total']).'</span></h5>
				</div>
			</div>

			<section class="pl-2">
				<table class="table-main-head mt-5">
					<tbody>
						<tr class="text-uppercase" style="border-bottom: 1px solid #5baa01;border-top: 1px solid #5baa01;">
							<th>Cant.</th>
							<th>Código</th>
							<th class="text-left">Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th>Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr style="border-bottom: 1.5px solid #5baa01!important;border-top: 1px solid #5baa01!important;" >
							<td colspan="7" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table table-no-border notas_doc">
					<tbody>
						<tr>
							<td style="vertical-align: top;">
								'.$nota_doc.'
								<div class="single-footer-wight box-main">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="80px" class="float-left mr-2 mb-3">
									<p class="mb-1 mt-3"><span class="text-theme-color font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-theme-color font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-theme-color font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$aviso_texto_pruebas.'
								</div>
							</td>
							<td style="width:300px; vertical-align: top;" class="p-0">
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				'.$html_lista_anticipos.'
				'.$html_detraccion.'
				'.$html_percepcion.'
				<div class="box-content mt-3">
					<div class="col-12">
						'.$text_pdf_3.'
						'.$html_lista_cuotas.'
						'.$html_retencion_base.'
						'.$html_cuentas_corrientes.'
					</div>
				</div>
				';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:48%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="160px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-color">Titular:</span> '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-theme-color">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="160px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-color">Titular:</span> '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-theme-color">N° de Telf:</span> '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'

		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_14($cpe) {  //ID: 51, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Emisor //Dirección
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}

		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1 mt-5">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p class="txt_pdf_a4_2">'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td>'.($item['codigo']).'</td>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-left td_descripcion"><div style="width: 300px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center"><div style="width: 100px !important;">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</div></td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right"><div style="width: 100px !important;">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</div></td>
			</tr>
			';
			
		}
		// Resumen de ventas
		$resumen = '
		<tr height="30px">
			<td  class="tb_resumen pb_resumen" width="130px" style="border-top:1px solid #000">Gravada:</td>
			<td class="text-right br_bottom pb_resumen" style="border-top:1px solid #000">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';


		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="tb_resumen pb_resumen" width="130px">Inafecto:</td>
				<td class="text-right br_bottom pb_resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="tb_resumen pb_resumen" width="130px">Exonerado:</td>
				<td class="text-right br_bottom pb_resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="tb_resumen pb_resumen" width="130px">Gratuito:</td>
				<td class="text-right br_bottom pb_resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="tb_resumen pb_resumen" width="130px">Exportación:</td>
				<td class="text-right br_bottom pb_resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="tb_resumen pb_resumen" width="130px">ICBPER:</td>
				<td class="text-right br_bottom pb_resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="tb_resumen pb_resumen" width="130px">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right br_bottom pb_resumen">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="tb_resumen pb_resumen" width="130px">Descuento Total:</td>
			<td class="text-right br_bottom pb_resumen">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td class="pb_resumen"  height="35px" width="130px" style="border-right: 1px solid #000!important;">Total:</td>
					<td class="text-right pb_resumen">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				<tr>
					<td class="pb_resumen"  height="35px" width="130px" style="border-right: 1px solid #000!important;">Total Anticipos (-):</td>
					<td class="text-right pb_resumen">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
					</td>
				</tr>
        		<tr>
					<td class="pb_resumen"  height="35px" width="130px" style="border-right: 1px solid #000!important;">Total a Pagar:</td>
					<td class="text-right pb_resumen">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        	
        		';
		    }else{
				
				$resumen = $resumen.'
				<tr>
					<td class="pb_resumen"  height="35px" width="130px" style="border-right: 1px solid #000!important;">Total:</td>
					<td class="text-right pb_resumen">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}
	
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<div class="text-left mt-2 mb-2">
				<p class="font-weight-bold">Observación:</p>
				<p>'.$cpe['cabecera']['documento_nota'].'</p>
			</div>
			';
		} else {
			$nota_doc = '';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table table-retencion" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/facturacionv8/extras/fontawesome/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			.h5, h5 {
				font-size: 16px;
			}
			body{
				position: relative;
				font-size: 12px!important;
			}
		
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1150px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height:1150px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.display-none{
				display: none;
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
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.table-no-border td, .table-no-border th {
				border: none!important;
			}
			.table td, .table th {
				padding: .55rem;
				vertical-align: top;
				border-top: 1px solid #000;
			}
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #000;
			}
			table {
				font-size: 11px!important;				
			}
			.table-main{
				border: 1px solid #000;
			}
			.table-main th{
				text-transform: uppercase;
			}
			.table-main td{
				border: 0;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
			}
			td, th {
				border: 1px solid #000;
				text-align: left;
				padding: 8px;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
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
			}
			.table-head{
				padding: 25px 5px;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #000;
			}
			
			.table-head p{
				line-height: 2;
				font-size: 16px;
			}
			.tb_resumen_totales td {
				padding: 5px 9px;
				border: none;
				text-aling: left;
				text-transform: uppercase;
				font-weight: bold;
				font-size: 11px;
			}

			.resumen_totales {
				height: 200px;
			}
			.table-cuentas{
				margin: auto;
			}
			.table-cuentas td{
				padding: 3px 5px;
			}
			.table-1 td{
				text-align: center!important;
			}
			.footer-content{
				position: absolute;
				bottom: 0;
			}
			.table_datos{
				font-size:14px;
				text-transform: uppercase;
			}

			.tb_resumen {
				border-right: 1px solid #000!important;
				border-bottom: 1px solid #000!important;
			}
			.br_bottom {
				border-bottom: 1px solid #000!important;
			}
			.pb_resumen{
				padding: 5px 5px!important;
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
			.table-retencion td{
				text-align: center;
			}
		</style>
		<body>';
		
		if(empty($cpe['emisor']['logo_rectangular'])) {
				$height = "style='height: 90px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-3">
						<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />
					</div>
					<div class="col-5 text-center" style="padding:10px 5px">
						<h5 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h5>
						'.$direccion_empresa.'
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$ubigeo_empresa.'</p>
						<p><i class="flaticon-call mr-2"></i>'.$cpe['emisor']['telefono'].'</p>
						<p><i class="flaticon-envelope mr-2"></i>'.$cpe['emisor']['email'].'<p>
						<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>
					</div>
					<div class="col-4 p-2" style="margin-top:2px">
						<div class="table-head">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			} else {
				$height = "style='height: 140px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-7 text-center mb-4">
						<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						'.$direccion_empresa.'
						<p>'.$ubigeo_empresa.'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p><p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>

					</div>
					<div class="col-5 p-2">
						<div class="table-head mt-4">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			}

			$html = $html.'
			
			<div class="box-content mb-2 mt-5">
				<div class="text-center">'.$text_pdf_1.'</div>
				<table class="table table_datos">
					<td width="50%">
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$direccion_fiscal.'
						
						'.$descripcion_nota.'
						'.$documento_relacionado.'
						'.$fecha_vencimiento.'
					</td>
				
					<td width="50%">
						<p><span class="font-weight-bold">Fecha de Emisión: </span>'.$cpe['cabecera']['fecha_emision'].'</p>
						<p><span class="font-weight-bold">Forma de pago: </span>'.$cpe['cabecera']['forma_de_pago'].'</p>
						'.$num_guia.'
						<p><span class="font-weight-bold">Tipo de moneda: </span>'.$cpe['cabecera']['nombre_moneda_doc'].'</p>
						'.$orden_compra.'
						'.$nro_placa.'
					</td>
				</table>
			</div>
			<section class="content_table_main">
				<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th>Código</th>
						<th width="200px">Cant.</th>
						<th width="500px">Descripción</th>
						<th>Precio</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th>Importe</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>

						<td colspan="4" style="border: 1px solid #000!important" class="text-center">
							<span  class="text-uppercase text-center font-weight-bold">
							'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'
							</span>
						</td>
						<td colspan="3" style="padding: 0!important;" rowspan="3">
							<table style="width: 100%;" class="tb_resumen_totales">
								<tbody>
									'.$resumen.'
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="border: 1px solid #000!important">
							<div class="text-left">
								<img style="width: 60px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" class="mb-2 mr-2 float-left" />
								<p class="mb-1 pt-3"><span class="font-weight-bold">Vendedor: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
								<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
								'.$text_pdf_2.'
							</div>
						</td>
						
					</tr>
					
				</tbody>
			</table>
			</section>
			<div class="text-left mt-2 nota_doc">
				';
				if(!empty($nota_doc)) {
				$html = $html.'
				'.$nota_doc.'
				';
				}
				$html = $html.'
			</div>
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_lista_cuotas.'
			'.$html_retencion_base.'
			'.$html_cuentas_corrientes;
			
			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					$tb_pin = 'float-left';
				}else{
					$tb_pin = '';
				}
				$html = $html.'
				<table class="table-footer '.$tb_pin.' mb-3 mt-2" style="width:49%;margin-right:1.3em">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular:'.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';

			}
			if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					$tb_yape = 'float-right';
					
					
				}else{
					$tb_yape = '';
				}
				$html = $html.'	
				<table class="table-footer '.$tb_yape.' mb-3 mt-2" style="width:49%;">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular:'.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';
			}
			$html = $html.'
			
			<div class="text-center mt-3">
				<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
				'.$text_pdf_3.'
				'.$aviso_texto_pruebas.'
			</div>
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_15($cpe) {  //ID: 55, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><span class="font-weight-bold text-uppercase">Dirección:</span>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-uppercase">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-uppercase">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-uppercase">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-uppercase">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}


		//Observación
		
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			
			<div class="text-left mt-2 mb-2">
				<p class="font-weight-bold text-uppercase mb-2" style="border-bottom: 1px solid  #2d86c0">Observación:</p>
				<p>'.$cpe['cabecera']['documento_nota'].'</p>
			</div>
			';
		} else {
			$nota_doc = '';
		}
		
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="6" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td></tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-uppercase">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-uppercase">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-uppercase">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-uppercase">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-left td_descripcion"><div style="width: 250px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center" width="160px">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right" width="160px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>

			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-right">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-right">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-right">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-right">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-right">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-right">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td class="text-right">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-right">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td class="text-right">Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        	
				<tr>
					<td class="text-right">Total a Pagar:</td>
					<td class="text-right bg-theme-primary text-white">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr>
					<td class="text-right">Total:</td>
					<td class="text-right bg-theme-primary text-white">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}
		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<td colspan="4" class="text-uppercase font-weight-bold bg-theme-primary  text-white" style="font-size: 10px">Detracción de Monedas en soles</td>
					</tr>
					<tr>

						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
			
				<table class="table_detraccion table">
					<tr>
						<td colspan="3" class="text-uppercase font-weight-bold bg-theme-primary  text-white" style="font-size: 10px">Detracción de Monedas en soles</td>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
		
			<table class="table_percepcion table">
				<tr>
					<td colspan="3" class="text-uppercase font-weight-bold bg-theme-primary  text-white" style="font-size: 10px">Comprobante de percepción</td>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table-main-head table table-retencion">
					<tbody>
						<tr style="border-bottom: 1px solid #2d86c0;border-top: 1px solid #2d86c0;">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-cuentas" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-theme-primary text-white text-uppercase">
							<th>N°</th>
							<th width="90px">Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas pl-2 w-100" style="margin: auto">
					<tbody>
						<tr class="bg-theme-primary text-white">
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="table-3 table-cuentas pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/facturacionv8/extras/fontawesome/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 11px!important;
			}
			
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.table-main-head{
				background: #fff;
			}
			.display-none{
				display: none;
			}
			.table-no-border td, .table-no-border th {
				border: none!important;
			}
			td, th {
				border: 0!important;
				text-align: left;
				padding: 8px;
			}
			.table td, .table th {
				padding: .55rem;
				vertical-align: top;
				border-top: 0!important;
			}
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			table {
				font-size: 11px!important;				
			}
			.table-main{
				border: 1px solid #000;
			}
			.table-main th{
				text-transform: uppercase;
			}
			.table-main td{
				border: 0;
			}
			.text-theme-primary{
				color: #1ea2ec;
			}
			.bg-theme-primary{
				background: #1ea2ec;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
			}
			td, th {
				border: 1px solid #5f5f5f;
				text-align: left;
				padding: 8px;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
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
			.table-head{
				padding: 25px 5px;
				text-align: center;
				text-transform: uppercase;
				border: 2px dashed #8db6e2;
				border-radius: 20px;
			}
			
			.table-head p{
				line-height: 2;
				font-size: 12px;
			}
			.tb_resumen_totales td {
				padding: 5px 9px;
				border: none;
				text-aling: left;
				text-transform: uppercase;
				font-weight: bold;
				font-size: 11px;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.resumen_totales {
				height: 200px;
			}
			
			.table-cuentas td{
				padding: 3px 5px;
			}
			.table-1 td{
				text-align: center!important;
			}
			.footer-content{
				position: absolute;
				bottom: 0;
			}
			.single-date{
				height: 90px;
				border: 1px solid #000;
				padding: 20px;
				margin:  10px 10px 10px 0;
			}
			.tb_resumen {
				border-right: 1px solid #000!important;
				border-bottom: 1px solid #000!important;
			}
			.br_bottom {
				border-bottom: 1px solid #000!important;
			}
			.pb_resumen{
				padding: 5px 5px!important;
			}
			.font-weight-bold{
				font-weight: bold;
			}
			.banner_color{
				margin-top: -1.5em;
			}
			.table_datos_main{
				margin-top: 3em;
			}
			.table_datos_main td{
				border: 0;
			}
			.text-uppercarse{
				text-transform: uppercase;
			}
			.table-main-head td, .table-main-head th{
				border-bottom: 1px solid #dddddd;
				padding: 5px;
			}
			.table-main-head tr:nth-child(even) {
				background-color: #eee;
			}
			.tb_resumen_totales td {
				border: .5px solid #1ea2ec!important;
			}
			.tb_resumen_totales {
				width: 100%;
			}
			.table-cuentas td{
				border-bottom: 1px solid #1ea2ec!important;
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
			.table-retencion td{
				text-align: center;
			}
		</style>
		<body>
			<header style="height: 200px; background-color: #f2f2f2; padding-top: 2em">
				<div class="w-100">
					<div class="col-7" style="padding-left: 3em">';
						if(empty($cpe['emisor']['logo_rectangular'])) {
							$html = $html.'
							<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
							
						}else{
							$html = $html.'
							<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
						}
						$html = $html.'
					</div>
					<div class="col-4 float-right mr-3">
						<div class="table-head">
							<p class="font-weight-bold">R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p class="font-weight-bold text-theme-primary">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>
			</header>
			<div class="banner_color">
				<div class="col-6">
					<img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-azul-1.jpg" width="330px">
				</div>
				<div class="col-6 text-right">
					<img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-azul-2.jpg" width="330px">
				</div>
			</div>
			<table class="w-100 table_datos_main">
				<tr>
					<td width="50%" valign="top">
						<h6 class="font-weight-bold text-uppercase" style="font-size: 15px">'.$cpe['emisor']['nombre_comercial'].'</h6>
						'.$direccion_empresa.' '.$ubigeo_empresa.'</p>
						<p><span class="font-weight-bold text-uppercarse">Teléfono: </span>'.$cpe['emisor']['telefono'].'</p>
						<p><span class="font-weight-bold text-uppercarse">E-Mail: </span>'.$cpe['emisor']['email'].'<p>
						<p><span class="font-weight-bold text-uppercarse">Sitio Web: </span>'.$cpe['emisor']['sitio_web'].'</p>
						<p><span class="font-weight-bold text-uppercarse">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
						<p><span class="font-weight-bold text-uppercarse">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
					</td>
					<td width="50%" valign="top">
						<p><span class="font-weight-bold text-uppercarse">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="font-weight-bold text-uppercarse">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$direccion_fiscal.'
						'.$orden_compra.'
						'.$num_guia.'
						
						<p><span class="font-weight-bold text-uppercarse">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
						<p><span class="font-weight-bold text-uppercarse">Fecha de Emisión: </span> '.$cpe['cabecera']['fecha_emision'].'</p>
						'.$nro_placa.'
						'.$descripcion_nota.'
						'.$documento_relacionado.'
						'.$fecha_vencimiento.'
					</td>
					
				</tr>
			</table>
			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<section class="section-tables mt-3">
				<table class="table table-main-head">
					<tbody>
						<tr class="bg-theme-primary text-white text-uppercase">
							<th>Cant.</th>
							<th>Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th class="text-right">Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table table-no-border">
					<tbody>
						<tr style="float:right;">
							<td style="width:200px; vertical-align: top;"  class="p-0">
								<table  class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</section>
			<footer class="mt-1">
				<div class="col-12 nota_doc">
					';
					if(!empty($nota_doc)) {
					$html = $html.'
					'.$nota_doc.'
					';
					}
					$html = $html.'
					<div class="text-left">
						<img style="width: 45px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" class="mb-2 mr-2 float-left" />
						<p class="mb-1 pt-1"><span class="font-weight-bold">Vendedor: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
						<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
						'.$text_pdf_3.'
						'.$aviso_texto_pruebas.'
					</div>
					'.$html_detraccion.'
					'.$html_percepcion.'
				</div>
				
				<div class="col-12">
					'.$html_lista_anticipos.'
					'.$html_lista_cuotas.'
					'.$html_retencion_base.'
					'.$html_cuentas_corrientes.'
					
				</div>';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:48%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="45px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="45px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Titular:'.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="45px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="45px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Titular:'.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
			</footer>
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}

	public function get_html_plantilla_a4_16($cpe) {  //ID: 59, TAMAÑO: A4, ==> FACTURAS
		$this->view->disable();
		
		
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.$item['codigo'].'</td>
				<td class="text-left td_descripcion"><div style="width: 250px !important;">'.$item['descripcion'].'</div></td>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td width="140px">'.$item['unidad_medida'].'</td>
				<td  class="text-center">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td  class="text-right" idth="140px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
	
	
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
			<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-600">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-600">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-600">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-600">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-600 text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table mb-3" style="border:.5px solid #000">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-600 text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-600 text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-600 text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table mb-3" style="border:.5px solid #000">
					<tr style="border:.5px solid #000">
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr style="border:.5px solid #000">
						<td colspan="2">
							<p><span class="font-weight-600 text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-600 text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-600 text-uppercase">Comprobante de percepción</p>
			<table class="table mb-3" style="border:.5px solid #000">
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-600">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center" style="border:.5px solid #000">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-600 text-uppercase">Información de la retención: </p>
				<table class="pl-2 table mb-3 table-retencion" style="margin:auto; ">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center" style="border:.5px solid #000">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-600  text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table table-cuotas" style="100%">
					<tbody>
						<tr class="font-weight-600">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
			<link rel="stylesheet" href="https://use.typekit.net/zfm2gqz.css">

		</head>
		<style>
			@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap");
			@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap");
			@import url("https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,700;0,900;1,400&display=swap");


			@page{
				margin: 5px;
				background: #000;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 12px!important;
				font-family: aileron, sans-serif;
				margin: 0;
				color: #000;
			}
			.display-none{
				display: none;
			}
			.table-no-border td {
				border: none!important;
			}
			.table td{
				padding: 0px 3px;
				border: 0;
				font-weight: 600;
				vertical-align: inherit;
				color: #000;
			}
			.table th{
				border-top: 0.5px solid #000!important;
				padding: 0px 3px;
				font-weight: 600;
				vertical-align: inherit;
				color: #000;
			}
			.table_retencion tr, .table_destraccion tr, .table_percepcion tr {
				border: 1px solid #000;
			}
			.table_productos{
				border: 0.5px solid #000!important;
				
			}
			.table_productos td{
				border-right: 0.5px solid #000!important;
				padding: 10px 3px 0 3px;
			}
			.table_productos tbody {
				vertical-align: top;
			}
			.table_height_auto{
				height: auto;
			}
			.table_height_pred{
				height: 450px;
			}
			
			.nota_doc td{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table thead th {
				vertical-align: bottom;
				border-bottom: 0.5px solid #000;
			}
			table {
				font-size: 11px!important;				
			}
			
			.table-main{
				border: 0.5px solid #000;
			}
			.table-main th{
				text-transform: uppercase;
			}

			.table-main td {
				border: 0;
				padding: 5px 5px 1px 5px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
			}
			td, th {
				border: 0.5px solid #000;
				text-align: left;
				padding: 8px;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-8{
				width: 66.666667%;
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
			.no-border td, .no-border th {
				border: 0;
			}
			.border-table-resumen td{
				text-align: right;
				padding: 4px 3px 0 3px;
			}
			.border-table-resumen td, .border-table-resumen th {
				border-left: 0.5px solid #000!important;
				border-right: 0.5px solid #000!important;
				border-bottom: 0.5px solid #000!important;
			}
			
			.table-head{
				padding: 27px 0;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #000;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.table-head p{
				line-height: 1.4;
				font-size: 23px;
				font-weight: 700;
			}
			.tb_resumen_totales td {
				padding: 2px;
				border: none;
			}
			
			.table-cuentas{
				margin: auto;
			}
			.table-cuentas td{
				padding: 3px 5px;
			}
			.table-1 th {
				font-size: 10px;
			}
			.table-1 td{
				text-align: center!important;
			}
			.footer-content{
				position: absolute;
				bottom: 0;
			}
			.pb-3{
				padding-bottom: 20px;
			}
			.no-padding td{
				padding: 0;
			}
			
			.table {
				margin-bottom: 0;
			}

			.font-weight-600{
				font-weight: 600;
			}
			.ml-50{
				margin-left: 6.3em;
			}
			.masthead {
				display: block;
				position:relative;
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.table_productos .td_descripcion {
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-retencion td, .table-cuotas td{
				font-size: 11px;
				text-align: center;
			}
		</style>
		<body class="masthead">
			<table class="w-100 no-border no-padding">
				<tr>
					<td width="58%">
						<h5 class="text-uppercase m-0" style="font-size: 21.4px; font-weight: 700;letter-spacing: 0;">'.$cpe['emisor']['nombre_comercial'].'</h5>
						<p class="text-uppercase" style="font-size: 13.2px;font-weight: 700;">'.$cpe['emisor']['direccion'].'</p>
						<p class="font-weight-bold" style="font-size: 13.2px;font-weight: 700;">'.$cpe['emisor']['telefono'].' - '.$cpe['emisor']['email'].'</p>
						<img  src="https://arpsystem.com.pe/facturacionv8/img/plantilas_personalizadas/mitsubishi/logo_mitsubishi.jpg" width="90%" class="mt-1">
						
					</td>
					<td  width="42%" style="padding: 0;vertical-align: top;padding:10px 0px"">
						<div class="table-head mt-2">
							<p>RUC: '.$cpe['emisor']['ruc'].'</p>                    
							<p>'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</td>
				</tr>
			</table>

			<div id="servicios" class="contenedor borde-primary mb-3 mt-2 ml-1">
				<table class="w-100 table_datos_main no-border text-uppercase no-padding" style="font-size:10.53!important">
					<tr>
						<td width="58%" valign="top" style="padding-right:1em">
							<p><span  class="font-weight-600" style="width:90px; display: inline-block;">'.$cpe['cliente']['doc_identificacion_titulo'].'</span>: '.$cpe['cliente']['nombre'].'</p>
							<p><span class="font-weight-600" style="width:90px; display: inline-block;">'.$cpe['cliente']['doc_identificacion_siglas'].' </span>:  '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							<p><span class="font-weight-600" style="width:90px; display: inline-block;">Dirección</span>:   '.$cpe['cliente']['direccion_fiscal'].'</p>
						</td>

						<td width="43%" valign="top">
							<p><span class="font-weight-600" style="width:130px; display: inline-block;">Fecha de Emisión </span>:  '.$cpe['cabecera']['fecha_emision'].'</p>
							<p><span class="font-weight-600" style="width:130px; display: inline-block;">Moneda</span>:  '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
							<p><span class="font-weight-600" style="width:130px; display: inline-block;">Guía remisión:</span>:  '.$cpe['cabecera']['guia_remision'].'</p>
							<p><span class="font-weight-600 mt-3" style="width:130px; display: inline-block;">Fecha Vencimiento </span>:  '.$cpe['cabecera']['fecha_vencimiento'].'</p>
							
						</td>
						
					</tr>
				</table>
				<div  class="text-uppercase" style="margin-top: 2em; font-size: 10.53px">
					<p><span class="font-weight-600" style="width:90px; display: inline-block;">ORDEN DE COMPRA</span>: '.$cpe['cabecera']['orden_compra'].'</p>
					<p><span class="font-weight-600">O SERVICIO </p>
					<p><span class="font-weight-600" style="width:90px; display: inline-block;">Condición de Pago</span>:  '.$cpe['cabecera']['forma_de_pago'].'</p>
					
				</div>		
			</div>
			<div class="content_table_productos table_height_auto">
				<table class="table m-0 table_productos">
					<thead>
						<tr class="text-uppercase">
							<th width="150px" class="no-border">Código</th>
							<th width="750px">Descripción</th>
							<th>Cant.</th>
							<th>UNID.<br>MED. </th>
							<th  width="300px">V. Unit</th>
							<th  width="300px">Importe</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							'.$items_detalle_html.'
						</tr>
					
					</tbody>
				</table>
			</div>
			
			<div class="resumen_total">
				<table class="table no-border nota_doc" class="p-0">
					<tbody>
						<tr>
							<td width="57%" class="pr-4" style="vertical-align:top">
								'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'];
								
								if(!empty($cpe['cabecera']['documento_nota'])) {
								$html = $html.'
								<p><strong>Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
								';
								}
							
								$html = $html.'
								<p class="mb-3" style="padding-top: 4em">FORMA DE PAGO: </p>
								<div class="ml-3" style="padding-left: 7em">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="90px" class="mr-3 mt-4 mb-3">
								</div>
							
							</td>
							<td width="42%" class="p-0" style="vertical-align:top">
								<table style="width: 100%;" class="table border-table-resumen text-uppercase text-right">
									<tbody style="font-size:10px">
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				'.$html_detraccion.'
				'.$html_percepcion.'
				'.$html_lista_cuotas.'
				'.$html_retencion_base.'
				'.$aviso_texto_pruebas.'
				<div style="font-size: 11px; font-weight: 600">
					<p class="mb-1">REPRESENTACIÓN IMPRESA DE FACTURA ELECTRÓNICA, consulte en www.sunat.gob.pe </p>
					<p>Autorizado mediante Resolución N° 034-005-0004782/SUNAT</p>
					<p>Obtenga copia de su documento en '.$cpe['patrocinador']['dominio'].' </p>
				</div>
			</div>
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	
	public function get_html_plantilla_a4_17($cpe) {  //ID: 64, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		// Numero de placa
		$num_placa_th = '';
		$num_placa = '';
		if(!empty($cpe['cabecera']['nro_placa'])){
			$num_placa_th = '<th>Número de Placa</th>';
			$num_placa = '<td>'.$cpe['cabecera']['nro_placa'].'</td>';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td width="140px" class="text-center">'.$item['unidad_medida'].'</td> 
				<td>'.$item['codigo'].'</td>
				<td class="text-left td_descripcion"><div style="width: 400px !important;">'.$item['descripcion'].'</div></td>
				<td  class="text-center"><div style="width: 80px !important;">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</div></td>
				<td  class="text-right" idth="140px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
	
	
		// Resumen de ventas
		$resumen = '
		<tr>
			<td> Op. Gravada:</td>
			<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
			<td class="text-right">
			 '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
				<td class="text-right">
				'.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
				<td class="text-right">
					'.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
				<td class="text-right">
					'.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
				<td class="text-right">
					'.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
				<td class="text-right">
					'.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		
		$resumen = $resumen.'
		<tr>
			<td>Dscto Global:</td>
			<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
			<td class="text-right">
				'.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
			<td class="text-right">
				'.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Importe Total:</td>
					<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
					<td class="text-right">
						'.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
					<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
        			<td class="text-right  font-weight-bold">
        				'.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        	
				<tr class="bg-theme">
					<td>Total a Pagar:</td>
					<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
					<td class="text-right">
						'.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr class="bg-theme">
					<td>Importe Total:</td>
						<td><span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span></td>
					<td class="text-right">
						'.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
				<table class="table_detraccion table text-uppercase">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
				<table class="table_detraccion table text-uppercase">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<table class="table_percepcion table text-uppercase">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table text-uppercase" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}

		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuentas
		$html_cuentas_corrientes = '';
		$cuenta_tipo = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			if($cuenta['tipo'] == 'cuenta_corriente'){
				$cuenta_tipo = 'Cuenta Corriente';
			}else if($cuenta['tipo'] == 'cuenta_detracciones'){
				$cuenta_tipo = 'Cuenta Detracción';
			} else if($cuenta['tipo'] == 'cuenta_ahorro') {
				$cuenta_tipo = 'Cuenta de Ahorro';
			}

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta_tipo.'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
		
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger text-center mt-2">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 15px;
				font-style: normal;
				margin-left: 0;
			}
			
			body{
				position: relative;
				font-size: 10px!important;
				
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.display-none{
				display: none;
			}
			.font-weight-bold{
				font-weight: bold;
			}
			.table-no-border td, .table-no-border th {
				border: none!important;
			}
			.table td, .table th {
				padding: .55rem;
				vertical-align: top;
				border-top: 1px solid #5f5f5f;
				
			}
			.nota_doc td{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			table {
				font-size: 10px!important;				
			}
			
			.table-main{
				border: 1px solid #5f5f5f;
			}
			.table-main th{
				text-transform: uppercase;
			}

			.table-main td {
				border: 0;
				padding: 5px 5px 1px 5px;
				
			}
			.table-main .td_descripcion{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
			}
			td, th {
				border: 1px solid #5f5f5f;
				text-align: left;
				padding: 8px;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-8{
				width: 66.666667%;
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
			.no-border td, .no-border th {
				border: 0;
			}
			.masthead {
				display: block;
			}
			.table-head{
				padding: 25px 5px;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #5f5f5f;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.table-head p{
				line-height: 2;
				font-size: 14px;
			}
			.tb_resumen_totales td {
				padding: 2px;
				border: none;
			}
			.resumen_totales {
				height: 200px;
			}
			.table-cuentas{
				margin: auto;
			}
			.table-cuentas td{
				padding: 3px 5px;
			}
			.table-1 th {
				font-size: 10px;
			}
			.table-1 td{
				text-align: center!important;
			}
			.footer-content{
				position: absolute;
				bottom: 0;
			}
			.pb-3{
				padding-bottom: 20px;
			}
			.text-uppercase{
				text-transform: uppercase;
			}
			.bg-theme{
				background: #007499;
				color: #fff;
			}
			.table-cuentas tr:nth-child(even) {
				background-color: #f5f5f5;
			}
			.tb_resumen_totales td{
				font-size: 11px!important;
			}
		</style>
		<body>';
		//Logo cuadrado
		if(empty($cpe['emisor']['logo_rectangular'])) {
				$height = "style='height: 150px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-3">
						<img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
					</div>
					<div class="col-5 text-center" style="padding:10px 5px">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p class="text-uppercase"><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p><i class="flaticon-call mr-2"></i>'.$cpe['emisor']['telefono'].'</p>
						<p><i class="flaticon-envelope mr-2"></i>'.$cpe['emisor']['email'].'<p>
						<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>
					</div>
					<div class="col-4 p-2">
						<div class="table-head" style="border-radius: 5px">
							<p style="font-weight: 600;">R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			} else {
				$height = "style='height: 205px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-7 text-center">
						<img  src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px;">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p class="text-uppercase"><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p><p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>

					</div>
					<div class="col-5 p-2">
						<div class="table-head" style="border-radius: 5px">
							<p style="font-weight: 600;">R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			}
			$html = $html.'
			<div class="text-center">'.$cpe['sucursal']['txt_pdf_a4_1'].'</div>
			<div class="text-uppercase">
				<div class="single-date-client mb-3">
					<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
					<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
					'.$direccion_fiscal.'
					
					'.$descripcion_nota.'
					'.$documento_relacionado.'
					'.$fecha_vencimiento.'
				</div>	
			</div>
			<table class="table table-1 mt-1 text-center text-uppercase">
				<thead>
					<tr class="text-uppercase">
						<th>Fecha de emisión</th>
						<th>Forma de Pago</th>
						<th>Tipo moneda</th>
						<th>Número de guía</th>
						<th>Orden de Compra</th>
						'.$num_placa_th.'

					</tr>
				</thead>
				<tbody class="text-center">
					<tr>
						<td>'.$cpe['cabecera']['fecha_emision'].'</td>
						<td>'.$cpe['cabecera']['forma_de_pago'].'</td>
						<td>'.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						<td>'.$cpe['cabecera']['guia_remision'].'</td>
						<td>'.$cpe['cabecera']['orden_compra'].'</td>
						'.$num_placa.'
					</tr>
				</tbody>
			</table>
				
			<table class="table table-main text-uppercase m-0">
				<tbody>
					<tr class="text-uppercase  bg-theme">
						<th>Cant.</th>
						<th>UNID/MED </th>
						<th>Código </th>
						<th>Descripción</th>
						<th>V. Unit</th>
						<th>Importe</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="6" style="border: 1px solid #5f5f5f!important" class="text-uppercase font-weight-bold text-left">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
					</tr>
				</tbody>
			</table>
			<footer class="mt-1">
				<table class="table no-border nota_doc">
					<tbody>
						<tr>
							<td width="66.666667%" class="pr-4">
								';
								if(!empty($cpe['cabecera']['documento_nota'])) {
								$html = $html.'
								<p><strong>Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
								';
								}
								$html = $html.'
								<div class="mt-1">
									<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									
								</div>
							</td>
							<td width="33.333333333333%">
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody class="text-uppercase font-weight-bold">
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</footer>
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_lista_cuotas.'
			'.$html_retencion_base.'
			'.$html_cuentas_corrientes.'
			
			<table class="mt-3 text-left" style="margin: auto">
				<tr>
					<td><img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px"></td>
					<td>
						<p>Representación Impresa de la Factura Electrónica '.$cpe['cabecera']['nombre_cpe'].'</p>
						<p class="mb-1">Consulte su documento electrónico en: '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
						<p>RESUMEN:   '.$cpe['cabecera']['hash'].'</p>
					</td>
				</tr>
			</table>
			<div class="text-center mt-2 mb-3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</div>';


			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					$tb_pin = 'float-left';
				}else{
					$tb_pin = '';
				}
				$html = $html.'
				
				<table class="table-footer '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular:'.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';

			}
			if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					$tb_yape = 'float-right';
					
				}else{
					$tb_yape = '';
				}
				$html = $html.'	
				<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Titular:'.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
								<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';
			}
			$html = $html.'
			
			
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_18($cpe) { //ID: 73, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
			
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		$sitio_web = '';
		if(!empty($cpe['emisor']['sitio_web'])) {
			$sitio_web = '<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>';
		} else {
			$sitio_web = '';
		}

		
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			
			$fecha_vencimiento =  '
			<tr valign="top">
				<td width="110px">
					<p class="font-weight-bold color-secundary">F. Vencimiento<span class="float-right">:</span></p>
				</td>
				<td>
					<p class="pl-2">'.$cpe['cabecera']['fecha_vencimiento'].'</p> 
				</td>
			</tr>';
		
		}

		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '

			<tr valign="top">
				<td width="110px">
					<p class="font-weight-bold color-secundary">O. Compra<span class="float-right">:</span></p>
				</td>
				<td>
					<p class="pl-2">'.$cpe['cabecera']['orden_compra'].'</p> 
				</td>
			</tr>';
			
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<tr valign="top">
				<td width="110px">
					<p class="font-weight-bold color-secundary">N° Guía<span class="float-right">:</span></p>
				</td>
				<td>
					<p class="pl-2">'.$cpe['cabecera']['guia_remision'].'</p> 
				</td>
			</tr>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<tr valign="top">
				<td width="110px">
					<p class="font-weight-bold color-secundary">N. Placa<span class="float-right">:</span></p>
				</td>
				<td>
					<p class="pl-2">'.$cpe['cabecera']['nro_placa'].'</p> 
				</td>
			</tr>';
		} else {
			$nro_placa = '';
		}

	
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td align="center" class="text-center">'.$item['nro_item'].'</td>
				<td align="center">'.($item['cantidad'] + 0).'</td>
				<td align="center"><div class="min_text" style="width: 100px !important;-ms-word-break: break-all;word-break: break-all word-break: break-word;-ms-hyphens: auto;-moz-hyphens: auto;-webkit-hyphens: auto;hyphens: auto;">'.$item['codigo'].'</div></td>
				<td align="center">'.$item['unidad_medida'].'</td>
				<td class="text-left td_descripcion" style="width: 250px !important;">'.$item['descripcion'].'</td>
				<td align="center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger mt-3" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				
				$descripcion_nota = '
				<tr valign="bottom">
					<td width="110px">
						<p class="font-weight-bold color-secundary">Motivo de Emisión:<span class="float-right">:</span></p>
					</td>
					<td>
						<p class="pl-2">'.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p> 
					</td>
				</tr>
				';
				$documento_relacionado = '
				<tr valign="bottom">
					<td width="110px">
						<p class="font-weight-bold color-secundary">Documento relacionado:<span class="float-right">:</span></p>
					</td>
					<td>
						<p class="pl-2">'.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p> 
					</td>
				</tr>
				';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '
				<tr valign="bottom">
					<td width="110px">
						<p class="font-weight-bold color-secundary">Motivo de Emisión:<span class="float-right">:</span></p>
					</td>
					<td>
						<p class="pl-2">'.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p> 
					</td>
				</tr>
				';
				$documento_relacionado = '
				<tr valign="bottom">
					<td width="110px">
						<p class="font-weight-bold color-secundary">Documento relacionado:<span class="float-right">:</span></p>
					</td>
					<td>
						<p class="pl-2">'.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p> 
					</td>
				</tr>
				';
			}
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="color-secundary" style="font-size: 12px">Sub total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="color-secundary" style="font-size: 12px">Inafecto:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="color-secundary" style="font-size: 12px">Exonerado:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="color-secundary" style="font-size: 12px">Gratuito:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="color-secundary" style="font-size: 12px">Exportación:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="color-secundary" style="font-size: 12px">ICBPER:</td>
				<td class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="color-secundary" style="font-size: 12px">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="color-secundary" style="font-size: 12px">Descuento Total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td class="color-secundary" style="font-size: 12px">Total:</td>
					<td class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				<tr>
					<td class="color-secundary" style="font-size: 12px">Total Anticipos (-):</td>
					<td class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
					</td>
				</tr>
        		<tr>
					<td class="color-secundary" style="font-size: 12px">Total a Pagar:</td>
					<td class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		
        		';
		    }else{
				$resumen = $resumen.'
				<tr>
					<td class="color-secundary" style="font-size: 12px">Total:</td>
					<td class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Detracción de Monedas en soles</p>
				<table class="table-cuentas bordered p-2" style="width: 65%!important;">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<div class="content-cuentas">
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Comprobante de percepción</p>
				<table class="table-cuentas bordered pl-2" style="width: 65%!important;">
					<tbody>
						<tr>
							<td class="color-secundary" width="50%">% Percepción: </td>
							<td class="font-weight-bold">'.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
						</tr>
						<tr>
							<td class="color-secundary">Monto Percepción: </td>
							<td class="font-weight-bold">'.$monto_percepcion.'</td>
						</tr>
						<tr>
							<td class="color-secundary">Monto Total: </td>
							<td class="font-weight-bold">'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr>
					<td class="color-secundary" width="50%">Base Imponible de la Retención: </td>
					<td class="font-weight-bold">'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
				</tr>
				<tr>
					<td class="color-secundary">Porcentaje de Retención: </td>
					<td class="font-weight-bold">'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
				</tr>
				<tr>
					<td class="color-secundary">Monto de la Retención: </td>
					<td class="font-weight-bold">'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas">
				<p class="font-weight-bold text-uppercase" style="font-size: 9px;">Información de la retención: </p>
				<table class="table-cuentas bordered pl-2" style="width: 65%!important;">
					<tbody>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			
			';
		}
		//Cuotas
		$html_lista_cuotas = '';
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="table-items-productos table-4 bordered mt-3" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-main">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="table-items-productos table-4 bordered mt-3" style="margin:auto; width: auto;">
					<tbody>
						<tr class="text-uppercase bg-main tr_principal_productos">
							<th>N°</th>
							<th width="80px">Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr>
					<td rowspan="2" class="font-weight-bold">
						'.$cuenta['nombre_banco'].'
					</td>
					<td class="color-secundary">Cuenta Soles: </td>
					<td class="color-secundary">'.$cuenta['numero'].'</td>
				</tr>
				<tr>
					<td class="color-secundary">Cuenta CCI: </td>
					<td class="color-secundary">'.$cuenta['cci'].'</td>
				</tr>
				
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="mt-2">
			<div class="content-cuentas">
				<table class="table-cuentas bordered pl-2" style="width: 65%!important;">
					<tbody>
						'.$html_cuentas_corrientes;
						if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
							$html_cuentas_corrientes = $html_cuentas_corrientes.'
							<tr>
								<td rowspan="2" class="font-weight-bold">
									Yape
								</td>
								<td class="color-secundary">Código QR: </td>
								<td class="color-secundary"><img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> </td>
							</tr>
							<tr>
								<td class="color-secundary">
									Datos:
								</td>
								<td class="color-secundary">
									<p>Titular: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p>N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
							</tr>
							';

						}	
					
						if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
							$html_cuentas_corrientes = $html_cuentas_corrientes.'
							<tr>
								<td rowspan="2" class="font-weight-bold">
									Plin
								</td>
								<td class="color-secundary">Código QR: </td>
								<td class="color-secundary"><img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr"  class="pl-2" width="60px"> </td>
							</tr>
							<tr>
								<td class="color-secundary">
									Datos:
								</td>
								<td class="color-secundary">
									<p>Titular: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p>N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
							</tr>
							';

						}	
						$html_cuentas_corrientes = $html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
		</div>';
		}
		
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
				font-size: 11px;
				font-family: "Trebuchet MS";
				color: #000;
			}
			.color-theme{
				color: #ffd966;
			}
			.codigo_qr p{
				font-size: 11px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
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
				font-size: 11px;
			}
			.table_detraccion, .table_percepcion{
				border: 1px solid #000;
			}
			.table_detraccion td, .table_percepcion td{
				border:none;
				padding: 5px;
				font-size: 11px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 11px;
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
				height: 150px;
			}
			.head-pg{
				position:relative;
			}
		
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
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
				border-top: solid #727272 1px;
				border-left: solid #727272 1px;
				border-right: solid #727272 1px;
				border-bottom: solid #727272 1px!important;
			}
			.table-cuentas th {
				margin-bottom: 0;
			}
		
			.table-cuentas td{
				padding: 3px;
			}
			.table-cuentas td p{
				font-size: 9px;
			}
			.table-footer p {
				font-size: 9px;
			}
			.table-items-productos {
				margin: 0;
				border-bottom-right-radius: 0px!important;
				border: 1px solid #767171;
			}
			.table-items-productos th {
				margin-bottom: 0;
				padding: 10px 5px!important;
				position: relative;
			}
			
			.table-items-productos th:first-child::before {
				width: 0;
			}
			.table-items-productos th::before {
				
				content: "";
				position: absolute;
				top: 5px;
				left: 0;
				width: 1px;
				height: 60%;
				background-color: rgba(135,135,135,1);

			}
			.table-items-productos td {
				border: none;
				text-align: center;
				padding: 2px 5px;
			}
			
			.table-items-productos tr:nth-child(even) {
				background-color: #f0f0f0;
			}
			.bg-main{
				background: #ffd966;
				color: #767171;
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
				border: 1px solid #000;
				width: 100%;
			}

			/* === Tabla Bordes === */
			.border-left{
				border-left: 1px solid #000!important;
			}
			.table-head {
				border: 2px solid #ffd966;
				text-align: center;
				width: 100%;
				font-size: 11px!important;
			}
			.table-head td{
				padding: .6em;
				line-height: 2;
				font-size: 14px;
			}
			
			.table-2 {
				border: 1px solid #727272;
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
				border-radius: 20px;
				width: 100%;

			}
			.bordered th {
				border-top: none;
			}
			.bordered td:first-child, .bordered th:first-child {
				border-left: none;
			}
			.bordered th:first-child {
				-moz-border-radius: 50px 0 0 0;
				-webkit-border-radius: 50px 0 0 0;
				border-radius: 50px 0 0 0;
			}
			.bordered th:last-child {
				-moz-border-radius: 0 50px 0 0;
				-webkit-border-radius: 0 50px 0 0;
				border-radius: 0 50px 0 0;
			}
			.bordered th:only-child{
				-moz-border-radius: 50px 50px 0 0;
				-webkit-border-radius: 50px 50px 0 0;
				border-radius: 50px 50px 0 0;
			}
			.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 50px;
				-webkit-border-radius: 0 0 0 50px;
				border-radius: 0 0 0 50px;
			}
			.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 50px 0;
				-webkit-border-radius: 0 0 50px 0;
				border-radius: 0 0 50px 0;
			
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
				border-bottom-right-radius: 6px!important;
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
			
			.tb_resumen_totales {
				width: 140px;
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
			/* Head */
			.bg-head-title {
				height: 53px;
				width: 100%;
				position: absolute;
				top: 32%;
				left: 0;
				-webkit-box-shadow: 4px 4px 27px -4px rgb(0 0 0 / 28%);
				-moz-box-shadow: 4px 4px 27px -4px rgba(0,0,0,0.28);
				box-shadow: 4px 4px 27px -4px rgb(0 0 0 / 28%);
				padding: 0.6em;
				line-height: 2.5;
				font-size: 14px;
				color: #fed866;
				font-weight: 700;
				text-align: center;
				text-transform: uppercase;
				background: rgba(135,135,135,1);
			}
			.color-secundary{
				color: #727272;
			}
			
			.line_right {
				border-right: 1px solid #727272;
				padding-right: 1em;
			}
			/* footer */
			.table-footer {
				background: #ffd966;
			}
			.text-underline{
				text-decoration: underline;
			}
			.single-date-table td{
				padding: 0;
			}
		</style>
		<body>
			<div class="head-pg masthead w-100">
				<div class="col-3" style="padding-top:10px">';
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				</div>
				<div class="col-5 text-center" style="padding: 10px; color: #4d575d!important;">
					<p class="font-weight-bold text-uppercase" style="font-size:12px">'.$cpe['emisor']['nombre_comercial'].'</p>
					<p>'.$cpe['emisor']['direccion'].'</p>
					<p>'.$cpe['emisor']['ubigeo'].'</p>
					<p>'.$cpe['emisor']['telefono'].'</p>
					<p>'.$cpe['emisor']['email'].'<p>
					<p>'.$cpe['emisor']['sitio_web'].'</p>
					
				</div>
				<div class="col-4  mt-1 position-relative" style="padding: 10px">
					<table class="table-head bordered" style="font-size: 15px!important;">
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase color-secundary" style="font-size: 16px">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
						</tr>
						<tr class="bg-main text-white text-uppercase">
							<td colspan="3" class="text-center font-weight-bold" style="background: rgba(135,135,135,1);color:#ffd966">'.$cpe['cabecera']['nombre_cpe'].'</td>
						</tr>
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
						</tr>
					</table>
				</div>
			</div>
		
			'.$text_pdf_1.'
			<table class="table-2 bordered">
				<tbody>
					<tr class="text-uppercase">
						<td width="50%" valign="top">
							<table class="w-100 single-date-table">
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">'.$cpe['cliente']['doc_identificacion_titulo'].' <span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['nombre'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">Dirección <span class="float-right">:</span> </p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['direccion_fiscal'].'</p>
									</td>
								</tr>
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">Vendedor<span class="float-right">:</span</p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									</td>
								</tr>
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">Moneda<span class="float-right">:</span</p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cabecera']['nombre_moneda_doc'].'
									</td>
								</tr>
								'.$orden_compra.'
								
							</table>
						</td>
						<td  width="50%" valign="top">
							<table class="w-100 single-date-table">
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">'.$cpe['cliente']['doc_identificacion_siglas'].' <span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['doc_identificacion_numero'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">Teléfono<span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['telefono'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">F. Emisión<span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cabecera']['fecha_emision'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">Forma de pago: <span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cabecera']['forma_de_pago'].'</p> 
									</td>
									'.$num_guia.'  '.$nro_placa.'
									'.$descripcion_nota.'
									'.$documento_relacionado.'
								</tr>
							</table>
						</td>	
					</tr>
					<tr>
						<td colspan="2" style="padding:0;margin:0">
							<p class="text-right pr-5">El detalle Incluye IGV</p>
						</td>
					</tr>
				</tbody>
			</table>
		
		
		<table class="table-items-productos table-4 bordered mt-3">
			<tbody>
				<tr class="text-uppercase bg-main tr_principal_productos">
					<th>Item</th>
					<th>Cantidad</th>
					<th>Código</th>
					<th width="90px">U. MEDIDA</th>
					<th  width="500px">Descripción</th>
					<th>P.Unitario</th>
					<th>Importe</th>
				</tr>
				<tr>
					'.$items_detalle_html.'
				</tr>
			</tbody>
		</table>
		'.$text_pdf_2.'
		<div class="resumen_totales">
			<div class="col-9 pt-3 pr-3 notas_doc">
			';
				if(!empty($cpe['cabecera']['documento_nota'])) {
				$html = $html.'
				<p><strong>Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
				';
				}
				$html = $html.'
				<p class="font-weight-bold">Condiciones de ventas:</p>
				<ol style="list-style-type: decimal-leading-zero; padding-left: 2em;">
					<li>Puesto en obra previa coordinación</li>
					<li>Los precios están sujetos a variación de costos de refineria.</li>
					<li>Facturación electrónica</li>
					<li>Garantizamos nuestros productos</li>
				</ul>
			</div>
		</div>
		<table class="nota_doc mb-2">
			<tbody>
				<tr style="border-bottom: 2px dashed #727272">
					<td width="500px" class="pr-4" style="vertical-align: bottom;">
						<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
					</td>
					<td  style="vertical-align: top;" width="300px">
						<table class="table-footer bordered font-weight-bold text-uppercase w-100 p-2">
							<tbody class="">
								'.$resumen.'
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<footer>
			<p class="text-underline">CONDICIONES COMERCIALES</p>
			<p>Forma de pago:   '.$cpe['cabecera']['forma_de_pago'].'</p>
			<p>Validez de la Oferta: Sujeto a Variacion de Precio</p>
		</footer>
		'.$text_pdf_3 .'
		'.$html_lista_anticipos.'
		'.$html_detraccion.'
		'.$html_percepcion.'
		'.$html_lista_cuotas.'
		'.$html_retencion_base.'
		'.$html_cuentas_corrientes.'		
		'.$aviso_texto_pruebas.'
		
		<div class="mt-2">
			<p>Importante:</p>
			<p>Una vez realizada el abono, agradeceremos enviarnos un correo o WhatsApp con la copia del voucher de abono o constancia de
			transferencia y estara reservado su pedido. </p>
			<p>"Agradecemos de antemano por la preferencia comprometiendonos a servirlo con rapidez. </p>
			<p class="color-secundary text-right"> Este Archivo fue generado por  '.$cpe['patrocinador']['dominio'].' </p>

		</div>
		
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	
	public function get_html_plantilla_a4_19($cpe) { //ID: 77, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-primary pl-3">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Emisor
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
			
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		$sitio_web = '';
		if(!empty($cpe['emisor']['sitio_web'])) {
			$sitio_web = '<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>';
		} else {
			$sitio_web = '';
		}

		
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento = '<p><span class="font-weight-bold text-primary pl-3">Dirección:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		
		}

		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '<p><span class="font-weight-bold text-primary pl-3">Dirección:</span> '.$cpe['cabecera']['orden_compra'].'</p>';
			
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '<p><span class="font-weight-bold text-primary pl-3">Dirección:</span> '.$cpe['cabecera']['guia_remision'].'</p>';
			
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '<p><span class="font-weight-bold text-primary pl-3">Dirección:</span> '.$cpe['cabecera']['nro_placa'].'</p>';
			
		} else {
			$nro_placa = '';
		}

	
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
	
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-left td_descripcion"><div style="width: 250px !important;">'.$item['descripcion'].'</div></td>
				<td  class="text-center" width="300px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td width="140px">'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right" idth="140px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger mt-3" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
			//Para Nota de debito y crédito
			$descripcion_nota = '';
			$documento_relacionado = '';
			
			if($cpe['cabecera']['id_tipo_cpe'] == '07'){
				if(!empty($cpe['cabecera']['data_nota_credito'])){
					$descripcion_nota = '<p><span class="font-weight-bold pl-3 text-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
					$documento_relacionado = '<p><span class="font-weight-bold pl-3 text-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
					
				}
			}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
				if(!empty($cpe['cabecera']['data_nota_debito'])){
					$descripcion_nota = '<p><span class="font-weight-bold pl-3 text-primary">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
					$documento_relacionado = '<p><span class="font-weight-bold pl-3 text-primary">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
					
				}
			}
	
			//Destracción
			$html_detraccion = '';
			if($cpe['cabecera']['aplica_detraccion'] == 'si') {
				if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
					$html_detraccion = '
					<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Detracción de Monedas en soles</p>
					<table class="table_detraccion table">
						<tr>
							<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
							<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
							<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
							<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
						</tr>
						<tr>
							<td colspan="4">
								<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
								<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
							</td>
						</tr>
					</table>';
				} else{
					$html_detraccion = '
					<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Detracción de Monedas en soles</p>
					<table class="table_detraccion table">
						<tr class="font-weight-bold text-uppercase">
							<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
							<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						</tr>
						<tr>
							<td colspan="2">
								<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
								<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
							</td>
						</tr>
					</table>';
				}
			}
	
			//Percepción
			
			$html_percepcion = '';
			if($cpe['cabecera']['aplica_percepcion'] == 'si') {
	
				$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
				
				$html_percepcion = '
				<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Comprobante de percepción</p>
				<table class="table_percepcion table">
					<tr class="text-uppercase font-weight-bold">
						<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
						<td>Monto Percepción: '.$monto_percepcion.'</td>
						<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
					</tr>
				</table>';
			}
	
			//Retención
			$html_retencion_base  ='';
			if($cpe['cabecera']['aplica_retencion'] == 'si') {
				$html_retencion_base = $html_retencion_base.'
					<tr class="text-center">
						<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
						<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
						<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
					</tr>
					';
			}
	
			if(!empty($html_retencion_base)) {
				$html_retencion_base = '
				<div class="content-cuentas mt-4 mb-4">
					<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Información de la retención: </p>
					<table class="pl-2 table table-retencion" style="margin:auto">
						<tbody>
							<tr class="bg-theme-default color-white text-uppercase">
								<th>Base Imponible de la Retención</th>
								<th>Porcentaje de Retención</th>
								<th>Monto de la Retención</th>
							</tr>
							'.$html_retencion_base.'
						</tbody>
					</table>
				</div>
				';
			}
		// Resumen de ventas
		$resumen = '
		<div class="resumen-item">
			<span class="color-secundary" style="font-size: 12px">Sub total:</span>
			<span class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</span>
		</div>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<div class="resumen-item">
				<span class="color-secundary" style="font-size: 12px">Inafecto:</span>
				<span class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</span>
			</div>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<div class="resumen-item">
				<span class="color-secundary" style="font-size: 12px">Exonerado:</span>
				<span class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</span>
			</div>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<div class="resumen-item">
				<span class="color-secundary" style="font-size: 12px">Gratuito:</span>
				<span class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</span>
			</div>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<div class="resumen-item">
				<span class="color-secundary" style="font-size: 12px">Exportación:</span>
				<span class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</span>
			</div>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<div class="resumen-item">
				<span class="color-secundary" style="font-size: 12px">ICBPER:</span>
				<span class="text-right  font-weight-bold">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</span>
			</div>
			';
		}

		$resumen = $resumen.'
		<div class="resumen-item">
			<span class="color-secundary" style="font-size: 12px">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</span>
			<span class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</span>
		</div>
		';

		$resumen = $resumen.'
		<div class="resumen-item">
			<span class="color-secundary" style="font-size: 12px">Descuento Total:</span>
			<span class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</span>
		</div>
		';

		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<div class="resumen-item">
					<span class="color-secundary" style="font-size: 12px">Total:</span>
					<span class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
					</span>
				</div>
        		
				<div class="resumen-item">
					<span class="color-secundary" style="font-size: 12px">Total Anticipos (-):</span>
					<span class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
					</span>
				</div>
        		
        		
				<div class="resumen-item total">
					<span class="color-secundary" style="font-size: 12px">Total a Pagar:</span>
					<span class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</span>
				</div>
        		';
		    }else{
				$resumen = $resumen.'
				<div class="resumen-item total">
					<span class="color-secundary" style="font-size: 12px">Total:</span>
					<span class="text-right  font-weight-bold">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
					</span>
				</div>
				';
			}
		}
	
		//Cuotas
		$html_lista_cuotas = '';
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
			<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Detalle de la Forma de Pago: Crédito </p>
				<table class="table-cuotas table-4 bordered mt-3" style="margin:auto; width: 100%;">
					<tbody>
						<tr class="bg-theme-default  text-white text-uppercase">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas">
				<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Anticipos: </p>
				<table class="pl-2 pl-2 table table-3">
					<tbody>
						<tr class="bg-theme-default  text-white text-uppercase">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Cuentas
		$html_cuentas_corrientes = '';
		$cuenta_tipo = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			if($cuenta['tipo'] == 'cuenta_corriente'){
				$cuenta_tipo = 'Cuenta Corriente';
			}else if($cuenta['tipo'] == 'cuenta_detracciones'){
				$cuenta_tipo = 'Cuenta Detracción';
			} else if($cuenta['tipo'] == 'cuenta_ahorro') {
				$cuenta_tipo = 'Cuenta de Ahorro';
			}

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta_tipo.'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<p class="font-weight-bold text-uppercase title-table" style="display: inline-block;margin-bottom: 10px;">Cuentas: </p>
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr class="bg-theme-default  text-white text-uppercase">
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
		
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
				font-size: 11px;
				font-family: "Trebuchet MS";
				color: #000;
			}
			.color-white{
				color: #fff;
			}
			.codigo_qr p{
				font-size: 11px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
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
				font-size: 11px;
			}
			
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 11px;
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
			
			
			.head-pg{
				position:relative;
			}
		
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
		
			.table-head p{
				line-height: 2;
			}
			.resumen-item {
				background: #eea908;
				padding: .5em .5em .5em 1em;
				border-top-left-radius: 20px;
				border-bottom-left-radius: 20px;
				text-transform: uppercase;
				font-weight: bold;
				color: #365be3;
				font-weight: 700;
				margin: 5px 0;
			}
			.resumen-item.total {
				background: #365be3;
				color: #fff;
			}
			/*.resumen_totales {
				height: 100px;
			}*/
			.spacing-lg {
				width: 60%;
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
			
			/* header */

			.masthead {
				background: #365be3;
				color: #fff;
				padding: 3em 0;
			}
			
			
			.table-info-head td{
				width: 50%	
			}
			.title-client, .title-table{
				background: #eea908;
				padding: 1em 8em 1em 1.5em;
				border-top-right-radius: 20px;
				border-bottom-right-radius: 20px;
				display: inherit;
				text-transform: uppercase;
				font-weight: bold;
				color: #365be3;
			}
			.title-table{ padding: 1em 2em 1em 1.5em; }
			/* Table de productos */
			.text-primary{
				color: #365be3!important;
			}
			.bg-theme-default{
				background: #365be3;
			}
			.table-main-head th, .table-cuotas th{
				padding: 1em 10px;
			}
			.table-main-head td, .table-3 td, .table-retencion td, .table_percepcion td, .table_detraccion td{ 
				text-align: center;
				border-bottom: 1px solid #e0dfe2;
				padding: 1em 10px;

			}
			.table_percepcion td, .table_detraccion td{ border-top: 1px solid #e0dfe2;}
		</style>
		<body>
			<div >
				<table class="table">
					<tbody>
						<tr>
							<td width="50%">';
						
								$html = $html.'
								
								<div class="text-center">
								<img src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 220px">
									<h6 class="font-weight-bold text-uppercase text-primary">'.$cpe['emisor']['nombre_comercial'].'</h6>
									<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
									<p>'.$cpe['emisor']['ubigeo'].'</p>
									<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
									<p>'.$cpe['emisor']['sitio_web'].'</p>
								</div>';
								
								
								$html = $html.'
							</td>
							<td width="50%" class="head-invoice pt-4  text-center">
								<div class="masthead color-white">
									<h6>R.U.C. '.$cpe['emisor']['ruc'].'</h6>                    
									<h5 style="font-weight: 600;">'.$cpe['cabecera']['nombre_cpe'].'</h5>
									<h6>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h6>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="datos-box">
				<table class="w-100 table-info-head">
					<tbody>
						<tr>
							<td valign="top">
								<p class="title-client">Cliente:</p>
								<p class="pl-3 mt-2"><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
								<p class="pl-3"><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
								'.$direccion_fiscal.'
								
							</td>
						
							<td valign="top">
								<p class="title-client">Detalles:</p>
								<p  class="pl-3 mt-2"><span class="text-primary font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
								'.$num_guia.'
								<p  class="th_moneda pl-3"><span class="text-primary font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
								<p class="th_moneda pl-3"><span class="text-primary font-weight-bold">Forma de pago:</span> 	'.$cpe['cabecera']['forma_de_pago'].'</p>
								'.$nro_placa.'
								'.$fecha_vencimiento.'
								
								'.$descripcion_nota.'
								'.$documento_relacionado.'
								'.$orden_compra.'
							</td>
							
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="text-center">
			'.$text_pdf_1.'
		</div>
		<section  style="margin-top: 1em">
			<table class="table-main-head">
				<tbody>
					<tr class="bg-theme-default  text-white text-uppercase">
						<th>Cant.</th>
						<th width="500px">Descripción</th>
						<th>Precio</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th>Importe</th>
					</tr>
					<tr>
					'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="6" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
					</tr>
					'.$text_pdf_2.'
				</tbody>
			</table>
			<table  class="table table-no-border">
				<tbody>
					<tr>
						<td style="vertical-align: top;" width="70%">
							<table  class="table-no-border notas_doc">
								<tbody>
									<tr>
										<td class="p-0">
											'.$nota_doc.'
											<div class="single-footer-wight box-main mb-2 pr-4">
												<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'"width="60px" class="float-left mr-3 mb-3">
												<p class="mb-1 mt-3"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
												<p><span class="text-primary font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
												<p><span class="text-primary font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
												<br>
												<p class="mb-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
												'.$aviso_texto_pruebas.'
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td  style="vertical-align: top;" class="p-0">

							<div class="resumen_totales">
								'.$resumen.'
							</div>
						</td>
					</tr>
				</tbody>
			</table>

		</section>

		<div class="col-12">
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
		</div>
		<footer class="footer_table">
			<div class="col-12">
				'.$text_pdf_3.'
				'.$html_lista_cuotas.'
				'.$html_retencion_base.'
				'.$html_cuentas_corrientes;
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-3  '.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="150px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td class="text-left"> 
									<p class="font-weight-bold"><span class="text-primary">Titular:</span> '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-primary">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
					
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-3  '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="150px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td class="text-left">  
									<p class="font-weight-bold"><span class="text-primary">Titular:</span> '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-primary">N° de Telf:</span> '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'
				
			</div>
			
		</footer>
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	//Personalizado
	public function get_html_personalizada_a4_70($cpe) { //ID: 70, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
			
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		$sitio_web = '';
		if(!empty($cpe['emisor']['sitio_web'])) {
			$sitio_web = '<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>';
		} else {
			$sitio_web = '';
		}

		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold color-secundary">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold color-secundary">Fecha Vencimiento </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}

		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}

		

		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '	<td class="border-left text-center">
				<p class="font-weight-bold">Fecha de  Vencimiento</p>
				<p>'.$cpe['cabecera']['fecha_vencimiento'].'</p> 
			</td>';
		
		}
		//Orden de compra
		$colum_1 = '';
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '<td style="border-bottom: 1px solid #000"><span class="font-weight-bold">Orden de Compra:</span> '.$cpe['cabecera']['orden_compra'].'</td>';
		} else {
			$orden_compra = '';
			$colum_1 = 'colspan="2"';
		}

		//N° de placa
		$colum = '';
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '<td style="border-bottom: 1px solid #000">
			<p><span class="font-weight-bold">Placa N°: </span>'.$cpe['cabecera']['nro_placa'].'</p>
		</td>';

		} else {
			$nro_placa = '';
			$colum = 'colspan="2"';
		}
		
	
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p class="txt_pdf_a4_1 mt-30">'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>
		</tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td align="center" class="text-center">'.$item['nro_item'].'</td>
				<td align="center">'.($item['cantidad'] + 0).'</td>
				<td align="center"><div class="min_text" style="width: 100px !important;-ms-word-break: break-all;word-break: break-all word-break: break-word;-ms-hyphens: auto;-moz-hyphens: auto;-webkit-hyphens: auto;hyphens: auto;">'.$item['codigo'].'</div></td>
				<td align="center">'.$item['unidad_medida'].'</td>
				<td class="text-left td_descripcion" style="width: 250px !important;">'.$item['descripcion'].'</td>
				<td align="center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger mt-3" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="color-secundary" style="font-size: 12px">Sub total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="color-secundary" style="font-size: 12px">IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';


		$resumen = $resumen.'
		<tr>
			<td class="color-secundary" style="font-size: 12px">Total:</td>
			<td class="text-right  font-weight-bold">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="text-uppercase font-weight-bold" style="font-size: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			<p class="text-uppercase font-weight-bold" style="font-size: 10px">Comprobante de percepción</p>
			<table class="table_percepcion table">
			<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase" style="font-size: 9px;">Información de la retención: </p>
				<table class="table table-retencion" style="border: 1px solid #000; font-size: 9px;">
					<tbody>
						<tr class="text-uppercase" style="border-bottom: 1px solid #000; font-weight: normal">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class="bg-main text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		
		
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
				font-size: 11px;
				font-family: "Trebuchet MS";
				color: #000;
			}
			.color-theme{
				color: #ffd966;
			}
			.codigo_qr p{
				font-size: 11px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
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
				font-size: 11px;
			}
			.table_detraccion, .table_percepcion{
				border: 1px solid #000;
			}
			.table_detraccion td, .table_percepcion td{
				border:none;
				padding: 5px;
				font-size: 11px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 11px;
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
				height: 150px;
			}
			.head-pg{
				position:relative;
			}
			
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 950px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
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
				border-top: solid #727272 1px;
				border-left: solid #727272 1px;
				border-right: solid #727272 1px;
				border-bottom: solid #727272 1px!important;
			}
			.table-cuentas th {
				margin-bottom: 0;
			}
		
			.table-cuentas td{
				padding: 3px;
			}
			.table-cuentas td p{
				font-size: 9px;
			}
			.table-footer p {
				font-size: 9px;
			}
			.table-items-productos {
				margin: 0;
				border-bottom-right-radius: 0px!important;
				border: 1px solid #767171;
			}
			.table-items-productos th {
				margin-bottom: 0;
				padding: 10px 5px!important;
				position: relative;
			}
			
			.table-items-productos th:first-child::before {
				width: 0;
			}
			.table-items-productos th::before {
				
				content: "";
				position: absolute;
				top: 5px;
				left: 0;
				width: 1px;
				height: 60%;
				background-color: rgba(135,135,135,1);

			}
			.table-items-productos td {
				border: none;
				text-align: center;
				padding: 2px 5px;
			}
			
			.table-items-productos tr:nth-child(even) {
				background-color: #f0f0f0;
			}
			.bg-main{
				background: #ffd966;
				color: #767171;
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
				border: 1px solid #000;
				width: 100%;
			}

			/* === Tabla Bordes === */
			.border-left{
				border-left: 1px solid #000!important;
			}
			.table-head {
				border: 2px solid #ffd966;
				text-align: center;
				width: 100%;
				font-size: 11px!important;
			}
			.table-head td{
				padding: .6em;
				line-height: 2;
				font-size: 14px;
			}
			
			.table-2 {
				border: 1px solid #727272;
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
				border-radius: 20px;
				width: 100%;

			}
			.bordered th {
				border-top: none;
			}
			.bordered td:first-child, .bordered th:first-child {
				border-left: none;
			}
			.bordered th:first-child {
				-moz-border-radius: 50px 0 0 0;
				-webkit-border-radius: 50px 0 0 0;
				border-radius: 50px 0 0 0;
			}
			.bordered th:last-child {
				-moz-border-radius: 0 50px 0 0;
				-webkit-border-radius: 0 50px 0 0;
				border-radius: 0 50px 0 0;
			}
			.bordered th:only-child{
				-moz-border-radius: 50px 50px 0 0;
				-webkit-border-radius: 50px 50px 0 0;
				border-radius: 50px 50px 0 0;
			}
			.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 50px;
				-webkit-border-radius: 0 0 0 50px;
				border-radius: 0 0 0 50px;
			}
			.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 50px 0;
				-webkit-border-radius: 0 0 50px 0;
				border-radius: 0 0 50px 0;
			
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
				border-bottom-right-radius: 6px!important;
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
			
			.tb_resumen_totales {
				width: 140px;
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
			/* Head */
			.bg-head-title {
				height: 53px;
				width: 100%;
				position: absolute;
				top: 32%;
				left: 0;
				-webkit-box-shadow: 4px 4px 27px -4px rgb(0 0 0 / 28%);
				-moz-box-shadow: 4px 4px 27px -4px rgba(0,0,0,0.28);
				box-shadow: 4px 4px 27px -4px rgb(0 0 0 / 28%);
				padding: 0.6em;
				line-height: 2.5;
				font-size: 14px;
				color: #fed866;
				font-weight: 700;
				text-align: center;
				text-transform: uppercase;
				background: rgba(135,135,135,1);
			}
			.color-secundary{
				color: #727272;
			}
			
			.line_right {
				border-right: 1px solid #727272;
				padding-right: 1em;
			}
			/* footer */
			.table-footer {
				background: #ffd966;
			}
			.text-underline{
				text-decoration: underline;
			}
			.single-date-table td{
				padding: 0;
			}
		</style>
		<body>
			<div class="head-pg masthead w-100">
				<div class="col-3">
					<img src="https://arpsystem.com.pe/facturacionv8/img/plantilas_personalizadas/02/logo_personalizado_02.png"  style="width: 140px;padding-top:10px">
				</div>
				<div class="col-5 text-center" style="padding: 10px; color: #4d575d!important;">
					<p class="font-weight-bold" style=" font-size: 11px">GAMEZ CORPORACION S.A.C. – GAMEZCOR S.A.C</p>
					<p>Anexo Deliciana Nro. Sn Cm Huayo La Libertad - Pataz - Huayo.  </p>
					<p>Cel.: 991174471 – 966000693</p>
					<p>E-MaiI: <span class="text-underline">ventas@sico.com.pe</span> </p>
					<p><span class="text-underline">http://www.sico.com.pe/</span></p>
					
				</div>
				<div class="col-4  mt-1 position-relative" style="padding:  10px">
				
					<table class="table-head bordered" style="font-size: 15px!important;">
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase color-secundary" style="font-size: 16px">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
						</tr>
						<tr class="bg-main text-white text-uppercase">
							<td colspan="3" class="text-center font-weight-bold" style="background: rgba(135,135,135,1);color:#ffd966">'.$cpe['cabecera']['nombre_cpe'].'</td>
						</tr>
						<tr>
							<td colspan="3" class="text-center font-weight-bold text-uppercase">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
						</tr>
					</table>
				</div>
			</div>
		
			'.$text_pdf_1.'
			<table class="table-2 bordered">
				
				<tbody>
					<tr class="text-uppercase">
						<td width="50%" valign="top">
							<table class="w-100 single-date-table">
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">SEÑOR(ES) <span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['nombre'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">Dirección <span class="float-right">:</span> </p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['direccion_fiscal'].'</p>
									</td>
								</tr>
								<tr valign="top">
									<td width="70px">
										<p class="font-weight-bold color-secundary">Vendedor<span class="float-right">:</span</p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')
									</td>
								</tr>
							</table>
						</td>
						<td  width="50%" valign="top">
							<table class="w-100 single-date-table">
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">'.$cpe['cliente']['doc_identificacion_siglas'].' <span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['doc_identificacion_numero'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">Teléfono<span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cliente']['telefono'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">F. Emisión<span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cabecera']['fecha_emision'].'</p> 
									</td>
								</tr>
								<tr valign="top">
									<td width="110px">
										<p class="font-weight-bold color-secundary">Forma de pago: <span class="float-right">:</span></p>
									</td>
									<td>
										<p class="pl-2">'.$cpe['cabecera']['forma_de_pago'].'</p> 
									</td>
								</tr>
							</table>
						</td>	
					</tr>
					<tr>
						<td colspan="2" style="padding:0;margin:0">
							<p class="text-right pr-5">El detalle Incluye IGV</p>
						</td>
					</tr>
				</tbody>
			</table>
		
		
		<table class="table-items-productos table-4 bordered mt-3">
			<tbody>
				<tr class="text-uppercase bg-main tr_principal_productos">
					<th>Item</th>
					<th>Cantidad</th>
					<th>Código</th>
					<th width="90px">U. MEDIDA</th>
					<th  width="500px">Descripción</th>
					<th>P.Unitario</th>
					<th>Importe</th>
				</tr>
				<tr>
					'.$items_detalle_html.'
				</tr>
			</tbody>
		</table>
		'.$text_pdf_2.'
		<div class="resumen_totales">
			<div class="col-9 pt-3 pr-3 notas_doc">
				<p class="font-weight-bold">Condiciones de ventas:</p>
				<ol style="list-style-type: decimal-leading-zero; padding-left: 2em;">
					<li>Condición</li>
					<li>Puesto en obra previa coordinación</li>
					<li>Los precios están sujetos a variación de costos de refineria.</li>
					<li>Facturación electrónica</li>
					<li>Garantizamos nuestros productos</li>
				</ul>
			</div>
		</div>
		<table class="nota_doc mb-2">
			<tbody>
				<tr style="border-bottom: 2px dashed #727272">
					<td width="500px" class="pr-4" style="vertical-align: bottom;">
						<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
					</td>
					<td  style="vertical-align: top;" width="200px">
						<table class="table-footer bordered font-weight-bold text-uppercase w-100 p-2">
							<tbody class="">
								'.$resumen.'
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<footer>
			<p class="text-underline">CONDICIONES COMERCIALES</p>
			<p>Forma de pago:   '.$cpe['cabecera']['forma_de_pago'].'</p>
			<p>Validez de la Oferta: Sujeto a Variacion de Precio</p>
		</footer>
		<div class="mt-2 col-8">
			<div class="content-cuentas">
				<table class="table-cuentas bordered pl-2">
					<tbody>
						<tr>
							<td rowspan="2" class="font-weight-bold"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/interbank.png" width="90px"></td>
							<td class="color-secundary">Cuenta SoleS: </td>
							<td class="color-secundary">6003002124936</td>
						</tr>
						<tr>
							<td class="color-secundary">Cuenta CCI: </td>
							<td class="color-secundary">00360000300212494345</td>
						</tr>
						<tr>
							<td rowspan="2" class="font-weight-bold"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banco-de-la-nacion.jpg" width="90px"></td>
							
							<td class="color-secundary">Cuenta SoleS: </td>
							<td class="color-secundary">00741401355</td>
						</tr>
						<tr>
							<td class="color-secundary">Cuenta CCI: </td>
							<td class="color-secundary">01874100074140135592</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="mt-2 col-8">
			<p>Importante:</p>
			<p>Una vez realizada el abono, agradeceremos enviarnos un correo o WhatsApp con la copia del voucher de abono o constancia de
			transferencia y estara reservado su pedido. </p>
			<p>"Agradecemos de antemano por la preferencia comprometiendonos a servirlo con rapidez. </p>
			<p class="color-secundary text-right"> Este Archivo fue generado por GAMEZCOR S.A.C. </p>
			'.$aviso_texto_pruebas.'
		</div>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_personalizada_a4_78($cpe) { //ID: 78, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		$num_guia = '';
		if(!empty($cpe['cabecera']['guia_remision'])){
			$num_guia =  '<p><span class="font-weight-bold">N° de guía:</span> '.$cpe['cabecera']['guia_remision'].'</p>';
		
		}
	
		$num_placa = '';
		if(!empty($cpe['cabecera']['nro_placa'])){
			$num_placa =  '<p><span class="font-weight-bold">N° de placa:</span> '.$cpe['cabecera']['nro_placa'].'</p>';
		
		}
		
					
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td align="center" class="text-center" style="font-size: 10px!important;">'.$item['nro_item'].'</td>
				<td  width="90px">'.($item['codigo']).'</td>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td class="text-left td_descripcion"><div style="width: 320px !important;">'.$item['descripcion'].'</div></td>
				<td  class="text-center" width="140px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td  class="text-right" width="140px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
	
	
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right border-resumen" style="border-top: 0!important;">
			<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right border-resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right border-resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right border-resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right border-resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right border-resumen">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right border-resumen">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';


		$resumen = $resumen.'
		<tr>
			<td>Total:</td>
			<td class="text-right border-resumen">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'
			</td>
		</tr>
		';
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr  class="bg-table">
							<td colspan="2" class="font-weight-bold text-uppercase">Detracción de Monedas en soles</td>
						</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<table class="table_percepcion table">
				<tr  class="bg-table">
					<td colspan="3" class="font-weight-bold text-uppercase">Comprobante de percepción</td>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas">
				<table class="pl-2 table" style="margin:auto">
					<tbody>
						<tr>
							<td colspan="3" class="font-weight-bold text-uppercase">Información de la retención: </td>
						</tr>
						<tr class="bg-table text-uppercase">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		


		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<table class="pl-2 border-0 table-cuotas" style="margin:auto">
				<tbody>
					<tr>
						<th>N°</th>
						<th>Fecha de Vencimiento</th>
						<th>Moneda</th>
						<th>Monto</th>
						<th>Estado</th>
					</tr>
					'.$html_lista_cuotas.'
				</tbody>
			</table>
			';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		$aviso_masthead_prueba = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger text-center mt-3 font-weight-bold" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. ¡No tiene Validez!
				</p>
			';
			
		}
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-size: 10px;
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 10px!important;
			}
			.border-resumen{
				border: 1px solid #5f5f5f!important;
				background: #cccccc;
				padding: 10px;
			}
			.display-none{
				display: none;
			}
			.table-no-border td, .table-no-border th {
				border: none!important;
			}
			.table td, .table th {
				padding: 0.35rem;
				vertical-align: top;
				border-top: 1px solid #5f5f5f;
				
			}
			.nota_doc td{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
				padding: 0;
			}
			.nota_doc {
				padding: 0;
				margin: 0;
			}
			
			
			.bg-table{
				background: #cccccc;
			}
		
			.table-main .td_descripcion{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1;
			}
			td, th {
				border: 1px solid #5f5f5f;
				text-align: left;
				padding: 8px;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-8{
				width: 66.666667%;
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
			.no-border td, .no-border th {
				border: 0;
			}
			.masthead {
				display: block;
				position:relative;
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 900px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 900px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			table {
				font-size: 10px!important;				
			}
			.table{
				margin-bottom: 0;
			}
			.table-cuentas td{
				padding: 2px 5px;
			}
			
			.table-cuentas{
				width: 100%;
			}
			.table-cuotas td{
				padding: 2px 5px!important;
			}
			.table-1 th {
				font-size: 10px;
			}
			.table-1 td{
				text-align: center!important;
			}
			.table-head{
				padding: 10px 0px;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #5f5f5f;
			}
			.table-head p{
				line-height: 1.5;
				font-size: 20px;
				font-weight: bold;
			}
			
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			.table-main{
				border: 1px solid #5f5f5f;
				margin: 0;
				font-size: 10px!important;
			}
			.table-main th{
				text-transform: uppercase;
				background: #cccccc;
			}
			.table-main td {
				border: 0;
				padding: 5px 5px 1px 5px;
				
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 10px;
			}
			
			.tb_resumen_totales td {
				padding: 5px;
				border: none;
				text-transform: uppercase;
				font-weight: bold;
			}
			.resumen_totales {
				height: 200px;
			}
			
			.footer-content{
				position: absolute;
				bottom: 0;
			}
			.pb-3{
				padding-bottom: 20px;
			}
			.border-0 td, .border-0 th{
				border: 0;
				padding: 0;
			}
			td > p{
				line-height:1.2;
			}
			.margin-top-head{
				margin-top: 4em;
			}
		</style>
		<body>';
		//Logo cuadrado
		if(empty($cpe['emisor']['logo_rectangular'])) {
				$height = "style='height: 190px;'";
				$html = $html.'
				<div class="masthead  w-100" '.$height.'>
					<div class="col-3">
						<img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
					</div>
					<div class="col-5 text-center" style="padding:10px 5px">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>
					</div>
					<div class="col-4 p-2" style="padding:10px 5px">
						<div class="table-head margin-top-head">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;background: #cccccc;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			} else {
				$height = "style='height: 210px;'";
				$html = $html.'
				<div class="masthead  w-100" '.$height.'>
					<div class="col-7 text-center" style="padding:10px 5px">
						<img  src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px;">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>

					</div>
					<div class="col-5 p-2">
						<div class="table-head margin-top-head">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="background: #cccccc;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			}
			$html = $html.'
			<div class="text-center">'.$cpe['sucursal']['txt_pdf_a4_1'].'</div>
			
			<table class="border-0 text-uppercase">
				<tr>
					<td width="65%" class="pr-2">
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$direccion_fiscal.'
						'.$fecha_vencimiento.'
					</td>
					<td width="45%" style="vertical-align:top">
						<p><span class="font-weight-bold">Fecha de emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
						<p><span class="font-weight-bold">Moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
						<p><span class="font-weight-bold">Forma de Pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
						'.$num_guia.'
						
					</td>
				</tr>
			</table>
				
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th style="font-size: 10px!important;">Item</th>
						<th>Código</th>
						<th>Cant.</th>
						<th>U.M </th>
						<th>Descripción</th>
						<th>P.U</th>
						<th>Total</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="7" style="border: 1px solid #5f5f5f!important" class="text-uppercase font-weight-bold text-left">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
					</tr>
				</tbody>
			</table>
			<footer class="mb-1">
				<table class="table no-border nota_doc">
					<tbody>
						<tr>
							<td width="66.666667%" class="pr-4">
								';
								if(!empty($cpe['cabecera']['documento_nota'])) {
								$html = $html.'
								<p><strong>Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
								';
								}
								$html = $html.'
								
							</td>
							<td width="33.333333333333%">
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				
			</footer>
			<table class="table">';
				if($cpe['cabecera']['id_tipo_cpe'] == '07' || $cpe['cabecera']['id_tipo_cpe'] == '8' || !empty($html_lista_cuotas)){
					$html = $html.'
					<tr>
						<td width="50%">
							<p>Vendedor: '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							<p>O.C: '.$cpe['cabecera']['orden_compra'].'</p>
						</td>
						<td  width="50%">
							<p class="text-uppercase">Detalle de crédito:</p>
							
							'.$descripcion_nota.'
							'.$documento_relacionado.'
							'.$html_lista_cuotas.'
						</td>
					</tr>';
				}else{
					$html = $html.'
					<tr>
						<td>
							<p>Vendedor: '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							<p>O.C: '.$cpe['cabecera']['orden_compra'].'</p>
						</td>
					</tr>';
				}
				$html = $html.'
			</table>
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_retencion_base.'
			<div class="w-100">';
			$total_cuentas = count($cpe['cuentas_banco']);
			$rows = 0;
			if($total_cuentas > 0 && $total_cuentas <= 2) {
				$html = $html.'
					<table  class="table-cuentas">
					<tr>
						<td colspan="2" class="text-uppercase bg-table font-weight-bold" style="border-bottom: 1px solid #5f5f5f;color: 000"> Cuentas bancarias:</td>
					</tr>
					<tr style="border-bottom: 1px solid #5f5f5f;">';

					foreach($cpe['cuentas_banco'] as $cuenta) {
						$html = $html.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}

				$html = $html.'
					</tr>
				</table>';
			} else if($total_cuentas > 2) {
				$html = $html.'
				<table  class="table-cuentas">
					<tr>
						<td colspan="2" class="text-uppercase bg-table font-weight-bold" style="border-bottom: 1px solid #5f5f5f;color: 000"> Cuentas bancarias:</td>
					</tr>';
				
				$item_nf = '';
				$parte1 = '';
				$parte2 = '';
				$parte3 = '';
				$c = 0;
				$lista_cuentas = '';
				foreach($cpe['cuentas_banco'] as $cuenta) {
					$rows++;
					
					if($rows > 0 && $rows <= 2) {
						$parte1 = $parte1.'
						<td>
							<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
							<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
							<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
							<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
						</td>
						';
					}
					
					if($rows > 2 && $rows <= 4) {
						if($rows == 2){
							$item_nf = $item_nf.'
							<td colspan="2">
								<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
								<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
							</td>
							';
							}else{
								$item_nf = $item_nf.'
								<td>
									<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
									<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
									<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
									<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
								</td>
								';
							}
					}
					if($rows > 4 && $rows <= 6) {
						
						if($rows == 5){
							$parte2 = $parte2.'
							<td colspan="2">
								<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
								<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
							</td>
							';
						}else{
							$parte2 = $parte2.'
							<td>
								<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
								<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
							</td>
							';
						}
					}
					if($rows > 6 && $rows <= 8) {
						if($rows == 7){
							$parte3 = $parte3.'
							<td colspan="2">
								<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
								<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
							</td>
							';
						}else{
							$parte3 = $parte3.'
							<td>
								<p class="text-uppercase">Banco: '.$cuenta['nombre_banco'].'</p> 
								<p class="text-uppercase">Titular: '.$cuenta['titular'].'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$cuenta['nombre_moneda'].'):</span> '.$cuenta['numero'].'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta['cci'].'</p>
							</td>
							';
						}
						
					}

				}

				if($parte1 != '') {
					$html = $html.'<tr>'.$parte1.'</tr>';
				}

				if($item_nf != '') {
					$html = $html.'<tr>'.$item_nf.'</tr>';
				}
				if($parte2 != '') {
					$html = $html.'<tr>'.$parte2.'</tr>';
				}
				if($parte3 != '') {
					$html = $html.'<tr>'.$parte3.'</tr>';
				}
				$html = $html.'
				</table>';
			}
				
				
				$html = $html.'
				<table class="table table-footer p-0">
					<tbody class="text-center">
						<tr>
							<td style="border-right: 0;" class="codigo_qr" vertical-align: top;> 
								<p>Autorizado mediante la resolución Nº 0640050002737/Sunat</p>
								<p>Representación impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
								<p class="mb-1">Para consultar el comprobante visita: '.$cpe['patrocinador']['url_consulta_cpe'].'
								<p>HASH:  '.$cpe['cabecera']['hash'].'</p>
								
							</td>
							<td style="border-left: 0" vertical-align: top;><img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-right"></td>
						</tr>	
					</tbody>
				</table>';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.'" style="width:50%;">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold text-uppercase">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.'" style="width:50%;">
						<tbody>
							<tr>
								<td width="140px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold text-uppercase">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold text-uppercase">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
			<div class="text-center mt-2 mb-5">'.$cpe['sucursal']['txt_pdf_a4_3'].'</div>
			'.$aviso_texto_pruebas.'
		</body>
			
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;			
	}
	//Tickets
	public function get_html_plantilla_ticket_1($cpe) { //ID: 10, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección: '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center pr-2">'.($item['cantidad'] + 0).'</td>
				<td class="td_descripcion cabecera_detalle_items" style="text-align: left;">'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
				<td  class="text-center">'.$item['precio_unitario'].'</td>
				<td class="text-right">'.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';

		}
	
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				<p>Total Anticipos (-): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'</p>
        		<p><span class="font-weight-bold">Total a Pagar:</span>  <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
        		';
		    }else{
				$resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				';
			}
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold mt-2">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				color: #000;
				position:relative;
				z-index: 1;
				line-height: 16px;
				font-size: 12px!important;

			}
			/* clases de bootstrap */
			hr {
				margin-top: 1rem;
				margin-bottom: 1rem;
				border: 0;
				border-top: 1px solid rgba(0,0,0,.1);
			}
			.mr-2{
				margin-right: 0.5rem!important;
			}
			.mt-2, .my-2 {
				margin-top: 0.5rem!important;
			}
			.mb-0{
				margin-bottom: 0;
			}
			.mb-2, .my-2 {
				margin-bottom: 0.5rem!important;
			}
			.mb-4, .my-4 {
				margin-bottom: 1.5rem!important;
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			
			.codigo_qr_ticket{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.line-title{
				display: inline-block;
				width: 80px;
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
				text-align: center;
				padding: 8px;
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
			}
			.table-cuotas th, .table td, .table th{
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
				font-weight: 200;
			}
			td{
				padding: 3px;
			}

			.tb_resumen_totales{
				width: 60%;
				margin:  5px;
				font-size: 11px!important;
			
			}
			.tb_resumen_totales td, .tb_resumen_totales th{
				border: none!important;
				padding: 0px;
			}
			.codigo_qr_ticket p{
				font-size: 11px;
			}
			.masthead p{
				font-size: 11px;
			}
			.table-main-head th{
				font-weight: 200;
			}
			/* tamaño de letra */
			.texto_12 {
				font-size: 11px !important;
			}

			.texto_14_bold {
				font-size: 11px !important;
				font-weight: bold !important;
			}

			.texto_11 {
				font-size: 11px !important;
			}

			.texto_10 {
				font-size: 10px !important;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 11px;
				font-style: normal;
				margin-left: 20px;
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
		
		</style>
		<body>
			<div class="masthead text-center">';
			
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				<h3 class="font-weight-bold text-uppercase mb-0">'.$cpe['emisor']['nombre_comercial'].'</h3>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>'.$cpe['emisor']['direccion'].'</p>
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" class="mr-2"/>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2 texto_10"></i>Email: '.$cpe['emisor']['email'].'</p>';
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p>Website:'.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			</div>
			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<div class="text-left">
				<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
				<p><span class="font-weight-bold">Fecha de Emisión:</span>  '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$direccion_fiscal.'
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
				
			</div>
			<div class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="mb-4">
							<th>Cant.</th>
							<th>Descripción</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
					</tbody>
				</table>
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_retencion_base.'
			'.$html_lista_cuotas.'
			<footer class="mt-2">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
				'.$text_pdf_2.'
				<p class="texto_12">Importe en Letras: '.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
				<div class="text-center texto_10 mt-2 codigo_qr_ticket">
					<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2"/>
					'.$nota_doc.'
					<p class="mt-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
					Consulte su Documento en:</p>
				
					<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
					<p>HASH:  '.$cpe['cabecera']['hash'].'</p>
					<p>VENDEDOR:  '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
					'.$text_pdf_3.'
					
				</div>';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0" />
					<p class="font-weight-bold">Cuentas:</p>
					<table class="table-footer '.$tb_pin.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
	
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
					<table class="table-footer '.$tb_yape.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
				<div class="text-center mt-2">
					'.$aviso_texto_pruebas.'
				</div>
			</footer>

		</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}

	public function get_html_plantilla_ticket_2($cpe) { //ID: 11, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center pr-2">'.($item['cantidad'] + 0).'</td>
				<td class="td_descripcion cabecera_detalle_items" style="text-align: left;">'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
				<td  class="text-center">'.$item['precio_unitario'].'</td>
				<td class="text-right">'.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';

		}
	
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				<p>Total Anticipos (-): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'</p>
        		<p><span class="font-weight-bold">Total a Pagar:</span>  <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
        		';
		    }else{
				$resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				';
			}
		}

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
				<p>Base Imponible de la Retención:  '.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</p>
				<p>Porcentaje de Retención: '.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' % </p>
				<p>Monto de la Retención: '.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].' </p>
				
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-cuotas" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>F. de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
				font-size: 12px;

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
			.mr-2{
				margin-right: 0.5rem!important;
			}
			.mt-2, .my-2 {
				margin-top: 0.5rem!important;
			}
			.mb-2, .my-2 {
				margin-bottom: 0.5rem!important;
			}
			.mb-4, .my-4 {
				margin-bottom: 1.5rem!important;
			}
			.mt-4{
				margin-top:1.5rem!important
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
				padding: 4px;
			}
			.border-table{
				border-bottom: 1px solid #000;
			}
			.padding-right-4{
				padding-right: 4em;
			}
			/*.table-main-head th{
				font-weight: 200;
			}*/
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
				padding: 5px 3px;
				font-size: 10px;
			}
			.table td, .table th {
				padding: 0.75rem;
				vertical-align: top;
				border-top: 1px solid #000;
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
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				<h6 class="text-uppercase font-weight-bold">'.$cpe['emisor']['nombre_comercial'].'</h6>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>Direc.: '.$cpe['emisor']['direccion'].'</p>
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><i class="flaticon-call mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$cpe['emisor']['email'];
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> Website: '.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				<div class="text-left">
					<hr style="border-top: 1px solid #000; margin-bottom:5px">
					<p class="font-weight-bold text-uppercase mt-2">'.$cpe['cabecera']['nombre_cpe'].'<p>
					<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
					<hr style="border-bottom: 1px solid #000;margin:5px 0">
				</div>
			</div>
			<div>
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$direccion_fiscal.'
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
				'.$text_pdf_1.'
			</div>
			<div class="table-content mt-1">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Cant</th>
							<th>Descripción</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-top: 1px solid #000;">
							<td colspan="6" class="text-uppercase text-center font-weight-bold">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
				
			</div>
			<footer class="mt-4 text-center nota_doc">
				'.$html_lista_anticipos.'
				'.$html_detraccion.'
				'.$html_percepcion.'
				'.$html_retencion_base.'
				'.$text_pdf_2.'
				<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2 mt-2"/>
				'.$nota_doc.'
				<p>Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].' <br />
				Consulte su Documento en:</p>
				<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p><span class="font-weight-bold">HASH:</span> '.$cpe['cabecera']['hash'].'</p>
				<p><span class="font-weight-bold">VENDEDOR:</span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
				<p class="font-weight-bold mb-4 text-uppercase">Gracias por su preferecia</p>
				'.$text_pdf_3.'
				
				'.$html_lista_cuotas;
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
				'.$aviso_texto_pruebas.'
				
			</footer>
			
		
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}

	public function get_html_plantilla_ticket_3($cpe) { //ID: 12, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
	
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr class="text-left">
				<td>'.($item['codigo']).'</td>
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td align="center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</</td>
			</tr>
			<tr>
				<td colspan="6" >'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
			</tr>
			';

		}
	
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				<p>Total Anticipos (-): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'</p>
        		<p><span class="font-weight-bold">Total a Pagar:</span>  <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
        		';
		    }else{
				$resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				';
			}
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
				<p>Base Imponible de la Retención:  '.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</p>
				<p>Porcentaje de Retención: '.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' % </p>
				<p>Monto de la Retención: '.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].' </p>
				
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-1 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-cuotas" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>F. de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold mt-2">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}

		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
			.mt-4 {
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				<h6 class="text-uppercase font-weight-bold">'.$cpe['emisor']['nombre_comercial'].'</h6>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>Direc.: '.$cpe['emisor']['direccion'].'
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><i class="flaticon-call mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$cpe['emisor']['email'];
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> Website: '.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				<p class="font-weight-bold text-uppercase mt-3">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
			</div>
			<div class="mt-1">
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				<p><span class="font-weight-bold">Direc.:</span> '.$direccion_fiscal.'</p>
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
				'.$text_pdf_1.'
			</div>
			<div class="table-content mt-1">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Código</th>
							<th>Cant.</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-top: 1px solid #000;" class="mt-1">
							<td colspan="6" class="text-uppercase text-center font-weight-bold">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>

					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
			<footer class="mt-4 text-center nota_doc">
				'.$html_lista_anticipos.'
				'.$html_detraccion.'
				'.$html_percepcion.'
				'.$html_retencion_base.'
				'.$text_pdf_2.'
				<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2 mt-2"/>
				'.$nota_doc.'
				<p>Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].' <br />
				Consulte su Documento en:</p>
				<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p><span class="font-weight-bold">HASH:</span> '.$cpe['cabecera']['hash'].'</p>
				<p><span class="font-weight-bold">VENDEDOR:</span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
				'.$text_pdf_3.'
				'.$html_lista_cuotas;
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
				'.$aviso_texto_pruebas.'
				
			</footer>
		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}

	public function get_html_plantilla_ticket_4($cpe) { //ID: 62, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		
		$this->view->disable();
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
				<tr class="text-left">
				<td>'.($item['codigo']).'</td>
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td align="center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</</td>
			</tr>
			<tr>
				<td colspan="6" >'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
			</tr>
		';
		}
		
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';
		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				<p>Total Anticipos (-): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'</p>
        		<p><span class="font-weight-bold">Total a Pagar:</span>  <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
        		';
		    }else{
				$resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				';
			}
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-2 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold mt-2">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 11px;
				position:relative;
				line-height: 16px;
				font-size: 11px;

			}
			/* clases de bootstrap */
			h2{
				margin: 8px 0;
				font-weight: 500;
				font-size: 16px;
			}
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
			.mb-3, .my-3 {
				margin-bottom: 1rem!important;
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			

			p{
				margin: 0;
				padding: 0;
				font-size: 11px;
			}
			table{
				width: 100%;
				font-size: 10px!important;
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
				font-weight: 200;
			}
		</style>
		<body>
			<div class="masthead text-center">
				<h2 class="text-uppercase font-weight-bold">'.$cpe['emisor']['nombre_comercial'].'</h2>
				<h2>R.U.C.:  '.$cpe['emisor']['ruc'].'</h2>
				<p>Direc.: '.$cpe['emisor']['direccion'].' '.$cpe['emisor']['ubigeo'].'</p>';
				$html = $html.'
				<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
			</div>
			<div class="text-left">
				<p><span class="font-weight-bold">Fecha de Emisión:</span>  '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				<p><span class="font-weight-bold">Direc.: </span>'.$cpe['cliente']['direccion_fiscal'].'</p>
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
				'.$text_pdf_1.'
			</div>
			<div class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Código</th>
							<th>Cant.</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-top: 1px solid #000;" class="mt-1">
							<td colspan="6" class="font-weight-bold">Importe en Letras: '.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>

					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
			<footer class="text-center nota_doc">
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_retencion_base.'
			'.$text_pdf_2.'
			<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
			<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-3 mt-2"/>
			'.$nota_doc.'
			<p>Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].' <br />
			Consulte su Documento en:</p>
			<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
			<p><span class="font-weight-bold">HASH:</span> '.$cpe['cabecera']['hash'].'</p>
			<p><span class="font-weight-bold">VENDEDOR:</span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
			'.$text_pdf_3;
			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					$tb_pin = 'float-left';
				}else{
					$tb_pin = '';
				}
				$html = $html.'
				<table class="table-footer '.$tb_pin.'" style="width:100%;">
					<tbody>
						<tr>
							<td width="90px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
								<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';

			}
			if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					$tb_yape = 'float-right';
					
				}else{
					$tb_yape = '';
				}
				$html = $html.'	
				<table class="table-footer '.$tb_yape.'" style="width:100%;">
					<tbody>
						<tr>
							<td width="90px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
							</td>
							<td> 
								<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
								<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
							</td>
							
						</tr>
					</tbody>
				</table>';
			}
			$html = $html.'	


			'.$aviso_texto_pruebas.'
		
			
			</footer>
		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}
	public function get_html_plantilla_ticket_5($cpe) { //ID: 69, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center pr-2">'.($item['cantidad'] + 0).'</td>
				<td class="td_descripcion cabecera_detalle_items" style="text-align: left;">'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
				<td  class="text-center">'.$item['precio_unitario'].'</td>
				<td class="text-right">'.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';

		}
	
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				<p>Total Anticipos (-): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'</p>
        		<p><span class="font-weight-bold">Total a Pagar:</span>  <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
        		';
		    }else{
				$resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				';
			}
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-2 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold mt-2">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				color: #000;
				font-size: 11px;
				position:relative;
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			
			.codigo_qr_ticket{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.line-title{
				display: inline-block;
				width: 80px;
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
				text-align: center;
				padding: 8px;
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
			}
			.table-cuotas th, .table td, .table th{
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
				font-weight: 200;
			}
			td{
				padding: 3px;
			}

			.tb_resumen_totales{
				width: 60%;
				margin:  5px;
				font-size: 11px!important;
			
			}
			.tb_resumen_totales td, .tb_resumen_totales th{
				border: none!important;
				padding: 0px;
			}
			.codigo_qr_ticket p{
				font-size: 11px;
			}
			.masthead p{
				font-size: 11px;
			}
			.table-main-head th{
				font-weight: 200;
			}
			/* tamaño de letra */
			.texto_12 {
				font-size: 11px !important;
			}

			.texto_14_bold {
				font-size: 11px !important;
				font-weight: bold !important;
			}

			.texto_11 {
				font-size: 11px !important;
			}

			.texto_10 {
				font-size: 10px !important;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 11px;
				font-style: normal;
				margin-left: 20px;
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
			.text-overline{
				text-decoration: overline;
			}
			.mt-3{
				margin-top: 1em;
			}
		</style>
		<body>
			<div class="masthead text-center">';
			
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				<p class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</p>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>'.$cpe['emisor']['direccion'].'</p>
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" class="mr-2"/>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2 texto_10"></i>Email: '.$cpe['emisor']['email'].'</p>';
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p>Website:'.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			</div>
			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<div class="text-left">
				<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
				<p><span class="font-weight-bold">Fecha de Emisión:</span>  '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$direccion_fiscal.'
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
				
			</div>
			<div class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="mb-4">
							<th>Cant.</th>
							<th>Descripción</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
					</tbody>
				</table>
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
			'.$html_lista_anticipos.'
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_retencion_base.'
			<footer class="mt-2">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
				'.$text_pdf_2.'
				<p class="texto_12">Importe en Letras: '.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
				<div class="text-center texto_10 mt-2 codigo_qr_ticket">
					<img width="55px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2"/>
					'.$nota_doc.'
					<p class="mt-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
					Consulte su Documento en:</p>
				
					<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
					<p>HASH:  '.$cpe['cabecera']['hash'].'</p>
					<p>VENDEDOR:  '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
					'.$text_pdf_3.'
					
				</div>
				
				'.$html_lista_cuotas.'
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px;" />
				<div class="text-center mt-2">
					'.$aviso_texto_pruebas.'
				</div>
			</footer>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
			<div class="text-center mt-3">
				<p class="font-weight-bold">DECLARACION JURADA DE CONOCIMIENTO DEL CLIENTE BAJO EL REGIMEN GENERAL</p>
				<p>CASA DE CAMBIO ALGUIS DE AYMA VELARDEYONY MARGOT</p>
				<p>10239255464</p>
			</div>
			<p class="font-weight-bold  text-uppercase">Ejecutante.</p>
			<p>Por el presenté documentó declaro bajo juramento lo siguiente:</p>
			<p>Nombre:	Raúl</p>
			<p>A. Paterno:	Peralta</p>
			<p>A. Materno: 	Carrión</p>
			<p>DNI: 	23827304</p>
			<p>País: 	Perú </p>
			<p>Ocupación: 	trabajador (A) independiente </p>
			<p>Función pública: 	no</p>

			<p class="font-weight-bold  text-uppercase mt-3">BENEFICIARIO.</p>
			<p>Operación a favor de:  	persona jurídica</p>
			<p>R. Social: 	Raúl Peralta Carrión </p>
			<p>Ruc: 	10238273043</p>
			<p>Objeto social: 	otros tipos de intermediación monetaria</p>
			<p>Dirección: 	Av. EL Sol 102 cusco</p>
			<p>Teléfono: 	969359916</p>

			<p class="mt-3">Representante legal</p>
			<p>Nombre: 	Raúl</p>
			<p>A. Paterno:	Peralta</p>
			<p>A. Materno: 	Carrión</p>
			<p>DNI: 	23827304</p>
			<p class="mt-3">Origen del dinero involucrado: otros</p>
			<p class="mt-3">Propósito de la relación compra moneda extranjera</p>
			<p>Cantidad: USD 		5,000.00</p>
			<p>Valor: PEN 		18,475.00</p>
			<p>T.C: PEN 		3.69500</p>
			<p>Afirmo y ratifico todo lo manifestado en la presente declaración jurada.</p>
			
			<div class="text-center mt-5">
			<hr style="border-top: 1px solid #000;height: 1px; margin: auto; width: 200px" />
			<p class="text-uppercase">Firma</p>
			</div>
			<div class="text-right mt-5">
			<p class="text-uppercase">Fecha: '.date("d/m/Y", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
			</div>
		</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}
	public function get_html_plantilla_ticket_6($cpe) { //ID: 96, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
	
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr class="text-left">
				<td>'.($item['codigo']).'</td>
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td align="center" width="150px">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td class="text-right" width="150px">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</</td>
			</tr>
			<tr>
				<td colspan="6" >'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
			</tr>
			';

		}
	
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				<p>Total Anticipos (-): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'</p>
        		<p><span class="font-weight-bold">Total a Pagar:</span>  <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'</p>
        		';
		    }else{
				$resumen = $resumen.'
				<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
				';
			}
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
				<p>Base Imponible de la Retención:  '.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</p>
				<p>Porcentaje de Retención: '.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' % </p>
				<p>Monto de la Retención: '.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].' </p>
				
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-1 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2 table-cuotas" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>F. de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold mt-2">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}

		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
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
			.mt-4 {
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				<h6 class="text-uppercase font-weight-bold">'.$cpe['emisor']['nombre_comercial'].'</h6>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>Direc.: '.$cpe['emisor']['direccion'].'
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><i class="flaticon-call mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$cpe['emisor']['email'];
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> Website: '.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				<p class="font-weight-bold text-uppercase mt-3">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
			</div>
			<div class="mt-1">
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$direccion_fiscal.'
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
			</div>
			<div class="table-content mt-1">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Código</th>
							<th>Cant.</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-top: 1px solid #000;" class="mt-1">
							<td colspan="6" class="text-uppercase text-center font-weight-bold">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>

					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<table class="table-footer" style="width:100%;">
						<tbody>
						<tr>
							<td>
								<img width="60px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2 mt-2"/>
							</td>
							<td>
							<div class="resumen_totales text-right mt-2">
							'.$resumen.'
						</div>
							</td> 
					</table>
					
				</div>
			</div>
			'.$html_lista_anticipos.'
			<footer class="mt-4 text-center nota_doc">
				
				
				'.$nota_doc.'
				<p>Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'<br />
				Consulte su Documento en:</p>
				<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>';
				
				
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
				'.$aviso_texto_pruebas.'
				
			</footer>
		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}
	public function get_html_personalizada_ticket_6($cpe) { //ID: 92, TAMAÑO: TICKET, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-uppercase">Orden de compra: </span> '.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}

		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}
		//Detalles de producto
		$items_detalle_html = '';
		$cantidad_total = 0;
		
		foreach($cpe['detalle'] as $item) {
			
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center pr-2">'.($item['cantidad'] + 0).'</td>
				<td class="td_descripcion cabecera_detalle_items" style="text-align: left;">'.nl2br($item['descripcion']).' - '.$item['unidad_medida'].'</td>
				<td  class="text-center">'.$cpe['cabecera']['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td class="text-right">'.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';

			$cantidad_total = $cantidad_total + $item['cantidad'];
		}
	
	
		// Resumen de ventas
		$resumen = '
		<p>Gravada: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'</p>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<p>Inafecto: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'</p>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<p>Exonerado: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<p>Gratuito: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<p>Exportación: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'</p>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<p>ICBPER: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'</p>
			';
		}
	
		$resumen = $resumen.'
		<p>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%): <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'</p>
		';

		$resumen = $resumen.'
		<p>Descuento Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'</p>
		';

		$resumen = $resumen.'
		<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total']).'</p>
		';

		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}

		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase" style="margin-top: 10px">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
				<p class="font-weight-bold text-uppercase">Detracción de Monedas en soles</p>
				<table class="table_detraccion table">
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}

		//Percepción
		
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
			
			<p class="font-weight-bold text-uppercase">Comprobante de percepción</p>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff;" margin:0;padding:0 />
			<p>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</p>
			<p>Monto Percepción: '.$monto_percepcion.'</p>
			<p><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</p>
			';
		}

		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}

		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table" style="margin:auto">
					<tbody>
						<tr>
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}


		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}

		//Cuotas
		$html_lista_cuotas = '';
	
		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}

	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr>
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="text-danger font-weight-bold mt-2">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				color: #000;
				position:relative;
				z-index: 1;
				line-height: 16px;
				font-size: 12px!important;

			}
			/* clases de bootstrap */
			hr {
				margin-top: 1rem;
				margin-bottom: 1rem;
				border: 0;
				border-top: 1px solid rgba(0,0,0,.1);
			}
			.mr-2{
				margin-right: 0.5rem!important;
			}
			.mt-2, .my-2 {
				margin-top: 0.5rem!important;
			}
			.mb-0{
				margin-bottom: 0;
			}
			.mb-2, .my-2 {
				margin-bottom: 0.5rem!important;
			}
			.mb-4, .my-4 {
				margin-bottom: 1.5rem!important;
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
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			}
			if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
			
			.codigo_qr_ticket{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.line-title{
				display: inline-block;
				width: 80px;
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
				text-align: center;
				padding: 8px;
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
			}
			.table-cuotas th, .table td, .table th{
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
				font-weight: 200;
			}
			td{
				padding: 3px;
			}

			.tb_resumen_totales{
				width: 60%;
				margin:  5px;
				font-size: 11px!important;
			
			}
			.tb_resumen_totales td, .tb_resumen_totales th{
				border: none!important;
				padding: 0px;
			}
			.codigo_qr_ticket p{
				font-size: 11px;
			}
			.masthead p{
				font-size: 11px;
			}
			.table-main-head th{
				font-weight: 200;
			}
			/* tamaño de letra */
			.texto_12 {
				font-size: 11px !important;
			}

			.texto_14_bold {
				font-size: 11px !important;
				font-weight: bold !important;
			}

			.texto_11 {
				font-size: 11px !important;
			}

			.texto_10 {
				font-size: 10px !important;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 11px;
				font-style: normal;
				margin-left: 20px;
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
			.single-box-personal{
				line-height: 20px;
			}
		</style>
		<body>
			<div class="masthead text-center">';
				$html = $html.'
				<h3 class="font-weight-bold text-uppercase mb-0">'.$cpe['emisor']['nombre_comercial'].'</h3>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>'.$cpe['emisor']['direccion'].'</p>
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" class="mr-2"/>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2 texto_10"></i>Email: '.$cpe['emisor']['email'].'</p>';
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p>Website:'.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			</div>
			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<div class="text-left">
				<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
				<p><span class="font-weight-bold">Fecha de Emisión:</span>  '.$cpe['cabecera']['fecha_emision'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].': </span>'.$cpe['cliente']['nombre'].'</p>
				<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].'</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$direccion_fiscal.'
				<p><span class="text-theme-default font-weight-bold">Forma de pago: </span> '.$cpe['cabecera']['forma_de_pago'].'</p>
				'.$nro_placa.'
				
				'.$descripcion_nota.'
				'.$documento_relacionado.'
				'.$fecha_vencimiento.'
				
			</div>
			<div class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="mb-4">
							<th>Cant.</th>
							<th>Descripción</th>
							<th width="120px">Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
					</tbody>
				</table>
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
			'.$html_detraccion.'
			'.$html_percepcion.'
			'.$html_retencion_base.'
			
			'.$html_lista_cuotas.'
			<footer class="mt-2">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
				'.$text_pdf_2.'
				<p class="texto_12">Importe en Letras: '.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
				<div class="text-center texto_10 mt-2 codigo_qr_ticket">
					<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2"/>
					
					<p class="mt-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
					Consulte su Documento en:</p>
				
					<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
					<p>HASH:  '.$cpe['cabecera']['hash'].'</p>
					<p>VENDEDOR:  '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
					'.$text_pdf_3.'
					
				</div>';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0" />
					<p class="font-weight-bold">Cuentas:</p>
					<table class="table-footer '.$tb_pin.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
	
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0px" />
					<table class="table-footer '.$tb_yape.'" style="width:100%;">
						<tbody>
							<tr>
								<td width="90px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="40px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="40px"> 
								</td>
								<td> 
									<p class="font-weight-bold">Nombre: '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold">N° de Telf: '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'	
				<div class="text-center mt-2">
					'.$aviso_texto_pruebas.'
				</div>
			</footer>';

			if($cantidad_total >= 5000) {
				$html = $html.'
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 0" />
				<div class="single-box-personal text-center" style="margin-top: 1em">
					<h3 class="font-weight-bold text-uppercase mb-0">'.$cpe['emisor']['nombre_comercial'].'</h3>
					<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
					<p class="font-weight-bold text-uppercase mt-2" style="line-height: 15px">DECLARACION JURADA DE CONOCIMIENTO DEL CLIENTE BAJO EL REGIMEN GENERAL</p>
				</div>
				<div class="single-box-personal" style="margin-top: 1em">
					<p class="font-weight-bold text-uppercase" style="text-decoration: underline;">EJECUTANTE</p>
					<p>Por el presente documento declaro bajo juramento lo siguiente:</p>';

					$html = $html.$cpe['cabecera']['documento_nota'];

					/*
					if($cpe['cliente']['doc_identificacion_id'] == 6){
						$html = $html.'	
						<p>Razon Social: '.$cpe['emisor']['nombre_comercial'].'</p>
						<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
						<p>Dirección: '.$cpe['emisor']['direccion'].'</p>';
					}else{
						$html = $html.'	
						<p>Nombre: </p>
						<p>D.N.I.: </p>
						<p>Dirección: </p>';
					}
					*/

					$html = $html.'	
					
				</div>
				<div class="single-box-personal" style="margin-top: 1em">
					<p class="font-weight-bold text-uppercase" style="text-decoration: underline;">BENEFICIARIO</p>';
					if($cpe['cliente']['doc_identificacion_id'] == 6){
						$html = $html.'	
						<p>Operación a favor de:
						<span>Persona Jurídica</span>';
					}else{
						$html = $html.'	
						<span>Persona Natural</span>';
					}
					$html = $html.'	
					</p>
					<p>Objeto social: </span> otros tipos de intermediación monetaria</p>
					<p>'.$cpe['cliente']['doc_identificacion_titulo'].': '.$cpe['cliente']['nombre'].'</p>
					<p>'.$cpe['cliente']['doc_identificacion_siglas'].' '.$cpe['cliente']['doc_identificacion_numero'].'</p>
					<p>Dirección: '.$cpe['cliente']['direccion_fiscal'].'</p>
					<p>Teléfono: '.$cpe['cliente']['telefono'].'</p>
				</div>
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 1em 0" />
			
				<p>Origen del dinero involucrado: otros</p>';
				if($cpe['cabecera']['codigomoneda'] == 'USD') {
					$html = $html.'	
						<p>Propósito de la relación compra moneda extranjera</p>';
				}
				$html = $html.'	
				<div class="single-box-personal">
					<table>
						<tr>
							<td width="80px">Cantidad</td>
							<td>: '.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total']).'</td>
						</tr>';
						if($cpe['cabecera']['codigomoneda'] == 'USD') {
							$html = $html.'	
							
							<tr>
								<td width="80px">T.C ('.$cpe['cabecera']['tipo_cambio_sunat'].'): </td>
								<td>: '.$cpe['cabecera']['simbolo_moneda'].'  '.custom_money_format( round($cpe['cabecera']['tipo_cambio_sunat']*$cpe['cabecera']['total'], 2)).'</td>
							</tr>';
						}
						$html = $html.'	
					</table>
					<p>Afirmo y ratifico todo lo manifestado en la presente declaración jurada</p>
					<div class="text-center" style="margin-top: 2em">
						<hr style="border-top: 1px solid  #000; color: #fff; background-color: #fff; height: 1px; width: 100px; margin:auto" />
						<p>FIRMA</p>
						<p>DNI: .........................</p>
						<p class="text-right">Fecha: '.date('d-m-Y').'</p>
					</div>
				</div>';
			}
			
		$html = $html.'
		</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
	}

	
	
	public function get_html_personalizada_a4_97($cpe) {  //ID: 97, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 text-theme-color mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		$ubigeo_empresa = '';
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-theme-color">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold text-theme-color">Fecha Vencimiento: </span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';

		}
		//Orden de compra
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra = '
			<p><span class="font-weight-bold text-theme-color">Orden de compra: </span>'.$cpe['cabecera']['orden_compra'].'</p> ';
		} else {
			$orden_compra = '';
		}
	
		//Num de guía
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = '
			<p><span class="font-weight-bold text-theme-color">N° Guía: </span> '.$cpe['cabecera']['guia_remision'].'</p>';
		} else {
			$num_guia = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = '
			<p><span class="font-weight-bold text-theme-color">N° de placa: </span>'.$cpe['cabecera']['nro_placa'].'</p> 
			';
		} else {
			$nro_placa = '';
		}


		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold text-uppercase">Observación:</p> 
			<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 2px; background:#5baa01"></div>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p class="txt_pdf_a4_1">'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<tr>
			<td colspan="7" class="text-uppercase text-left">'.$cpe['sucursal']['txt_pdf_a4_2'].'</td></tr>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p class="txt_pdf_a4_3">'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Para Nota de debito y crédito
		$descripcion_nota = '';
		$documento_relacionado = '';
		if($cpe['cabecera']['id_tipo_cpe'] == '07'){
			if(!empty($cpe['cabecera']['data_nota_credito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-color">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_credito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-color">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_credito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_credito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_credito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_credito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}else if ($cpe['cabecera']['id_tipo_cpe'] == '08'){
			if(!empty($cpe['cabecera']['data_nota_debito'])){
				$descripcion_nota = '<p><span class="font-weight-bold text-theme-color">Motivo de Emisión:</span> '.$cpe['cabecera']['data_nota_debito']['descripcion_motivo'].'.</p>';
				$documento_relacionado = '<p><span class="font-weight-bold text-theme-color">Documento relacionado:</span> '.$cpe['cabecera']['data_nota_debito']['nombre_documento_modificado'].' '.$cpe['cabecera']['data_nota_debito']['serie_documento_modificado'].' - '.$cpe['cabecera']['data_nota_debito']['correlativo_documento_modificado'].' | '.date("d-m-Y", strtotime($cpe['cabecera']['data_nota_debito']['fecha_emision_documento_modificado'])).'</p>';
				
			}
		}
			
		//Detalles de producto
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td align="center">'.$item['codigo'].'</td>
				<td class="text-left td_descripcion"><div style="width: 300px !important;">'.nl2br($item['descripcion']).'</div></td>
				<td  class="text-center">'.$item['simbolo_moneda'].'  '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td  class="text-right"><div style="width: 100px !important;">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</div></td>
			</tr>

			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}
	
		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}
	
		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		if(isset($cpe['sum_anticipos'])) {
		    if(floatval($cpe['sum_anticipos']) > 0 && 1 == 2) {
		        $resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right text-uppercase">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
        		<tr>
        			<td>Total Anticipos (-):</td>
        			<td class="text-right  font-weight-bold">
        				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( $cpe['sum_anticipos']).'
        			</td>
        		</tr>
        		
				<tr class="bg-theme-color text-white font-weight-bold">
					<td class="text-uppercase">Total a Pagar:</td>
					<td class="text-right text-uppercase">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format( round($cpe['cabecera']['total'] - $cpe['sum_anticipos'], 2)).'
					</td>
				</tr>
        		';
		    }else{
				$resumen = $resumen.'
				<tr class="bg-theme-color text-white font-weight-bold">
					<td class="text-uppercase">Total:</td>
					<td class="text-right text-uppercase">
						<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].' </span> '.custom_money_format( $cpe['cabecera']['total']).'
					</td>
				</tr>
				';
			}
		}
		$html_lista_anticipos = '';
		if(count($cpe['anticipos']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['anticipos'] as $anticipo) {
				
				$html_lista_anticipos = $html_lista_anticipos.'
				<tr class="text-center">
					<td>'.$anticipo['num_anticipo'].'</td>
					<td>'.$anticipo['id_tipodoc_electronico'].'</td>
					<td>'.$anticipo['serie_comprobante'].'</td>
					<td>'.$anticipo['numero_comprobante'].'</td>
					<td>'.$anticipo['monto_anticipo'].'</td>
				</tr>
				';
			}
		}
		
		if(!empty($html_lista_anticipos)) {
			$html_lista_anticipos = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Anticipos </p>
				<table class="pl-2 pl-2 table-client" style="margin:auto; width: auto;">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>N°</th>
							<th>Tipo Doc.</th>
							<th>Serie</th>
							<th>Correlativo</th>
							<th>Monto</th>
						</tr>
						'.$html_lista_anticipos.'
					</tbody>
				</table>
			</div>
			';
		}
		//Destracción
		$html_detraccion = '';
		if($cpe['cabecera']['aplica_detraccion'] == 'si') {
			if($cpe['cabecera']['nombre_moneda_doc'] == 'USD') {
				$html_detraccion = '
				
				<table class="table_detraccion table">
					<tr>
						<th colspan="4" class="text-uppercase font-weight-bold text-left">Detracción de Monedas en soles</th>
					</tr>
					<tr>

						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
						<td>T.C: '.$cpe['cabecera']['data_detraccion']['tipo_cambio_sunat_detr'].'</td>
						<td>USD: '.$cpe['cabecera']['data_detraccion']['monto_destraccion_usd'].'</td>
					</tr>
					<tr>
						<td colspan="4">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			} else{
				$html_detraccion = '
			
				<table class="table_detraccion table">
					<tr>
						<th colspan="4" class="text-uppercase font-weight-bold text-left">Detracción de Monedas en soles</th>
					</tr>
					<tr>
						<td>% Detracción: '.$cpe['cabecera']['data_detraccion']['detraccion_porcentaje'].'</td>
						<td>Monto Detracción: S/ '.$cpe['cabecera']['data_detraccion']['detraccion_monto'].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><span class="font-weight-bold text-uppercase">Descripción: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_texto'].'</p>
							<p><span class="font-weight-bold text-uppercase">Banco de la Nación: </span>'.$cpe['cabecera']['data_detraccion']['detraccion_cuenta'].'</p>
						</td>
					</tr>
				</table>';
			}
		}
	
		//Percepción
		$html_percepcion = '';
		if($cpe['cabecera']['aplica_percepcion'] == 'si') {

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format( $cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
			$html_percepcion = '
		
			<table class="table_percepcion table">
				<tr>
					<th colspan="3" class="text-uppercase font-weight-bold">Comprobante de percepción</th>
				</tr>
				<tr>
					<td>% Percepción: '.$cpe['cabecera']['data_percepcion']['percepcion_porcentaje'].'</td>
					<td>Monto Percepción: '.$monto_percepcion.'</td>
					<td><span class="font-weight-bold">Monto Total: </span>'.$cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.$cpe['cabecera']['data_percepcion']['percepcion_monto_base'] .'</td>
				</tr>
			</table>';
		}
	
		//Retención
		$html_retencion_base  ='';
		if($cpe['cabecera']['aplica_retencion'] == 'si') {
			$html_retencion_base = $html_retencion_base.'
				<tr class="text-center">
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_base'].'</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_porcentaje'].' %</td>
					<td>'.$cpe['cabecera']['data_retencion']['retencion_simbolo_moneda'].' '.$cpe['cabecera']['data_retencion']['retencion_monto'].'</td>
				</tr>
				';
		}
		if(!empty($html_retencion_base)) {
			$html_retencion_base = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase">Información de la retención: </p>
				<table class="pl-2 table table-retencion">
					<tbody>
						<tr class="bg-theme-color text-white text-uppercase">
							<th>Base Imponible de la Retención</th>
							<th>Porcentaje de Retención</th>
							<th>Monto de la Retención</th>
						</tr>
						'.$html_retencion_base.'
					</tbody>
				</table>
			</div>
			';
		}
		
		//Cuentas
		$html_cuentas_corrientes = '';
		foreach($cpe['cuentas_banco'] as $cuenta) {
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta['nombre_banco'].'</td>
					<td>'.$cuenta['titular'].'</td>
					<td>'.$cuenta['tipo'].'</td>
					<td>'.$cuenta['nombre_moneda'].'</td>
					<td>'.$cuenta['numero'].'</td>
					<td>'.$cuenta['cci'].'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas w-100 pl-2" style="margin: auto">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>BANCO</th>
							<th>TITULAR</th>
							<th>TIPO CTA</th>
							<th>MONEDA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
					</tbody>
				</table>
			</div>
			';
		}
		//Cuotas
		$html_lista_cuotas = '';

		if(count($cpe['cabecera']['cuotas']) > 0) {
			//si ingresa aquí significa que existen cuotas para el comprobante y por tanto se debe mostrar la tabla.
			
			//para recorrer el array se utilizaría:
			foreach($cpe['cabecera']['cuotas'] as $item_cuota) {
				
				$html_lista_cuotas = $html_lista_cuotas.'
				<tr class="text-center">
					<td>'.$item_cuota['num_cuota'].'</td>
					<td>'.$item_cuota['fecha_vencimiento'].'</td>
					<td>'.$item_cuota['tipo_moneda'].'</td>
					<td>'.$item_cuota['monto_cuota'].'</td>
					<td>'.$item_cuota['estado_cuota'].'</td>
				</tr>
				';
			}
		}
	
	
		if(!empty($html_lista_cuotas)) {
			$html_lista_cuotas = '
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center"> Detalle de la Forma de Pago: Crédito </p>
				<table class="table-3 table-cuentas pl-2" style="margin:auto">
					<tbody>
						<tr class=" bg-theme-color text-white">
							<th>N°</th>
							<th>Fecha de Vencimiento</th>
							<th>Moneda</th>
							<th>Monto</th>
							<th>Estado</th>
						</tr>
						'.$html_lista_cuotas.'
					</tbody>
				</table>
			</div>
			';
		}
	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}

		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 15px!important;
				font-family: "Lato", sans-serif;
				position:relative;
				
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'anulado') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			} if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
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
					background-repeat: initial;
				} ';
			}  if($cpe['cabecera']['estado_documento'] == 'anulado' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
		
			$html = $html.'
			.display-none{
				display: none;
			}
			
			table {
				font-size: 14px!important;				
			}
		
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;

			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 14px;
			}
			.notas_doc td{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			td, th {
				text-align: left;
				padding: 7px;
			}
			.table_retencion th{
				color: #000;
			}
			.box-content{
				width: 100%;
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
			.col-4-r{
				width: 33.333333333333%;
				float: right;
				padding: 0;
			}
			.col-6-r{
				width: 50%;
				float: right;
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
				padding: 0;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}
			.col-7-r {
				width: 55%;
				float: right;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.col-12 {
				width: 100%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			.text-theme-color{
				color: #5baa01;
			}
			.bg-theme-color{
				background: #5baa01!important;
			}
			/* Clases de la plantilla */
			.masthead {
				height: 240px;
			}
			.single-date{
				height: 130px;
			}
			.single-date p{
				font-size: 13px;
			}
			.box-content{
				width: 100%;
			}
			.body_head{
				z-index: 10;
			}
			.total_invoice{
				width: 100%;
				height: 75px;
				z-index: -1;
				color: #fff;
				background: #5baa01;
				position: relative;
			}
			.total_invoice::before{
				content: " ";
				position: absolute;
				top: 2em;
				right: 0;
				width: 100%;
				height: 400px;
				background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-verde-2.jpg);
				background-repeat: no-repeat;
				z-index: -1;
			}
			.table-main-head{
				width: 100%;
				border-bottom: 1px solid #5baa01;
			}
			.table-main-head th{
				color: #5baa01;
			}
			.tb_resumen_totales {
				float: right;
			} 
			.tb_resumen_totales td {
				border: none;
			} 
			.box-resumen{
				height: 200px;
			}
			.table_detraccion td, .table_percepcion td, .table_detraccion th, .table_percepcion th {
				border-bottom: 1px solid #5baa01;
				padding: 5px;
			}
			.table_detraccion th, .table_percepcion th {
				border-top: 1px solid #5baa01;
				text-align: left;
				padding: 5px;
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
			.table-retencion td{
				text-align: center;
			}
		</style>
		<body class="body_head">
			<table width="100%">
				<tr>
					<td colspan="3" style="padding: 0">'; 
						$html = $html.'
							<img style="width: 600px;"  src="https://arpsystem.com.pe/facturacionv8/public/img/facturalaya_aquaterra_drilling.png" />';
						$html = $html.'
					</td>
				</tr>
				<tr>
					<td width="33.333333333333%" valign="top">
						<h4 class="text-theme-color font-weight-bold text-uppercase mb-2" style="font-size: 17px">'.$cpe['emisor']['nombre_comercial'].'</h4>
						<p class="mb-0"><span class="text-theme-color font-weight-bold">R.U.C. </span>'.$cpe['emisor']['ruc'].'</p>
						'.$direccion_empresa.' 
						<p><i class="flaticon-call text-theme-color mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].' </p>
						<p><i class="flaticon-envelope mr-2 text-theme-color"></i>'.$cpe['emisor']['email'].'</p>';
						if(!empty($cpe['emisor']['sitio_web'])) {
							$html = $html.'
							<p><i class="flaticon-globo mr-2 text-theme-color"></i>'.$cpe['emisor']['sitio_web'].'</p>';
						}
						$html = $html.'
					
					</td>
					<td width="33.333333333333%" valign="top">
						<p class="text-title-theme">Cliente:</p>
						<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#5baa01"></div>
						<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$direccion_fiscal.'
					</td>
					<td width="33.333333333333%" valign="top">
						<p class="text-title-theme">Detalles:</p>
						<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#5baa01"></div>
						<p><span class="text-theme-color font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<p><span class="font-weight-bold text-theme-color">Forma de Pago:</span> '.$cpe['cabecera']['forma_de_pago'].'
						<p class="th_moneda"><span class="text-theme-color font-weight-bold">Tipo de moneda:</span> '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
						'.$num_guia.'
						'.$orden_compra.'
						'.$nro_placa.'
						'.$fecha_vencimiento.'
						
						'.$descripcion_nota.'
						'.$documento_relacionado.'
					</td>
				</tr>
			</table>
			<div class="text-center mt-3">
				'.$text_pdf_1.'
			</div>
			<div class="total_invoice mt-3">
				<div class="col-6-r text-right pr-3 pt-1 mb-4">
					<p class="nombre_documento_titular text-uppercase font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].': <br> '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
					<h5 class="title_price"> Total: <span class="font-weight-bold">'.$cpe['cabecera']['simbolo_moneda'].' '.custom_money_format( $cpe['cabecera']['total']).'</span></h5>
				</div>
			</div>

			<section class="pl-2">
				<table class="table-main-head mt-5">
					<tbody>
						<tr class="text-uppercase" style="border-bottom: 1px solid #5baa01;border-top: 1px solid #5baa01;">
							<th>Cant.</th>
							<th>Código</th>
							<th class="text-left">Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th>Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr style="border-bottom: 1.5px solid #5baa01!important;border-top: 1px solid #5baa01!important;" >
							<td colspan="7" class="text-uppercase text-center">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<table  class="table table-no-border notas_doc">
					<tbody>
						<tr>
							<td style="vertical-align: top;">
								'.$nota_doc.'
								<div class="single-footer-wight box-main">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="80px" class="float-left mr-2 mb-3">
									<p class="mb-1 mt-3"><span class="text-theme-color font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-theme-color font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-theme-color font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$aviso_texto_pruebas.'
								</div>
							</td>
							<td style="width:300px; vertical-align: top;" class="p-0">
								<table style="width: 100%;" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				'.$html_lista_anticipos.'
				'.$html_detraccion.'
				'.$html_percepcion.'
				<div class="box-content mt-3">
					<div class="col-12">
						'.$text_pdf_3.'
						'.$html_lista_cuotas.'
						'.$html_retencion_base.'
						'.$html_cuentas_corrientes.'
					</div>
				</div>
				';
				if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
					if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
						$tb_pin = 'float-left';
					}else{
						$tb_pin = '';
					}
					$html = $html.'
					<table class="table-footer '.$tb_pin.' mb-3" style="width:48%;margin-right:1.3em">
						<tbody>
							<tr>
								<td width="160px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr"  class="pl-2" width="60px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-color">Titular:</span> '.$cpe['cabecera']['data_yape']['titular_yape'].'</p>
									<p class="font-weight-bold"><span class="text-theme-color">N° de Telf:</span> '.$cpe['cabecera']['data_yape']['celular_yape'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
	
				}
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
						$tb_yape = 'float-right';
						
					}else{
						$tb_yape = '';
					}
					$html = $html.'	
					<table class="table-footer '.$tb_yape.' mb-3" style="width:49%;">
						<tbody>
							<tr>
								<td width="160px"> 
									<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="90px"> 
									<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_plin']['img_qr_plin'].'" alt="codigo-qr" class="pl-2" width="90px"> 
								</td>
								<td> 
									<p class="font-weight-bold"><span class="text-theme-color">Titular:</span> '.$cpe['cabecera']['data_plin']['titular_plin'].'</p>
									<p class="font-weight-bold"><span class="text-theme-color">N° de Telf:</span> '.$cpe['cabecera']['data_plin']['celular_plin'].'</p>
								</td>
								
							</tr>
						</tbody>
					</table>';
				}
				$html = $html.'

		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
}
?>