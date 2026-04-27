<?php
class TemplateordendecompraController extends ControllerBase
{
    public function get_html_personalizada_a4_87($cpe) { //ID: 87, TAMAÑO: A4, ==> ORDEN DE COMPRA
        $this->view->disable();
		
		//Dirección Emisor
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>';
		} else {
			$direccion_empresa = '';
		}
		//Ubigeo Emisor
		if(!empty($cpe['emisor']['ubigeo'])) {
			$ubigeo_empresa = '<p>'.$cpe['emisor']['ubigeo'].'</p>';
		} else {
			$ubigeo_empresa = '';
		}
	
		$total_adeudado = '';
		if(!empty($cpe['cabecera']['total_adeudado'])){
			$total_adeudado =  '<p><span class="font-weight-bold">Total Adeudado:</span>  '.$cpe['cabecera']['simbolo_moneda'].' '.$cpe['cabecera']['total_adeudado'].' </p><p>  <span class="font-weight-bold">Fecha de pago:</span> '.$cpe['cabecera']['fecha_pago_deuda'].'</p>';
		
		}
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha de Vencimiento:</span>  '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		
		}
		
		//Num guía
		$num_guia = '';
		if(!empty($cpe['cabecera']['guia_remision'])) {
			$num_guia = $cpe['cabecera']['guia_remision'];

		} else {
			$num_guia = '';
		}
		//Orden de compra
		$orden_compra ='';
		$orden_compra_th = '';
		if(!empty($cpe['cabecera']['orden_compra'])) {
			$orden_compra_th = '<th>Orden de compra</th>';
			$orden_compra = '<td> '.$cpe['cabecera']['orden_compra'].'</td>';
		} else {
			$orden_compra = '';
			$orden_compra_th = '';
		}

		//N° de placa
		if(!empty($cpe['cabecera']['nro_placa'])) {
			$nro_placa = $cpe['cabecera']['nro_placa'];

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

		//Detalles de producto
		$items_detalle_html = '';
		
		foreach($cpe['detalle'] as $item) {
				
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td style="text-align: left !important;" width="280px">'.$item['descripcion'].'</td>
				<td class="text-center">'.$item['simbolo_moneda'].' '.$item['precio_unitario'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['texto_tipo_operacion'].'</td>
				<td style="text-align: right !important;">'.$item['simbolo_moneda'].' '.custom_money_format($item['subtotal_item']).'</td>
			</tr>
			';
		}
		
		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format(round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		
		
		if($cpe['cabecera']['codigomoneda'] == 'USD') {
			$resumen = $resumen.'
			<tr>
				<td>Total:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'
				</td>
			</tr>
			<tr>
				<td>T.C ('.$cpe['cabecera']['tipo_cambio_sunat'].'): </td>
				<td class="text-right">S/. '.custom_money_format(round($cpe['cabecera']['tipo_cambio_sunat']*$cpe['cabecera']['total'], 2)).'</td>
			</tr>
			';
		}else{
			$resumen = $resumen.'
				<tr>
					<td>Total:</td>
					<td class="text-right">
						<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'
					</td>
				</tr>';
		}

	
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="text-danger text-center mt-3 font-weight-bold" style="font-size: 12px; font-style: italic!important;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. ¡No tiene Validez!
				</p>
			';
		}

		$canjeo = '';
        if($cpe['cabecera']['id_tipo_cpe'] == '77'){
			$canjeo = '<p class="font-weight-bold text-uppercase">Canjear este tipo de documento por un documento electrónico (Factura o Boleta)</p>';
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
				z-index: 1;
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
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			table {
				font-size: 11px!important;				
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
			.nota_doc{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.no-border td, .no-border th {
				border: 0;
			}
			.table-head{
				padding: 25px 5px;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #5f5f5f;
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
			.table-main .td_descripcion {
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
            .font-weight-bold{
                font-weight: bold;
            }
		</style>

		<body>';
			
		 
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
				<div class="col-4 p-2">
					<div class="table-head">
						<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
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
					'.$direccion_empresa.'
					<p>'.$ubigeo_empresa.'</p>
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
			<div class="text-center">'.$text_pdf_1.'</div>
			<div>
				<div class="single-date-client mb-3">
                    <p class="font-weight-bold text-uppercase">Datos del proveedor:</p>
					<p><span class="font-weight-bold">Razón Social:</span> '.$cpe['proveedor']['razon_social'].'</p>
					<p><span class="font-weight-bold">R.U.C:</span> '.$cpe['proveedor']['num_doc'].'</p>
                    <p><span class="font-weight-bold">Dirección Fiscal:</span> '.$cpe['proveedor']['direccion_fiscal'].'</p>
                   
				</div>	
			</div>
			<table class="table table-1 mt-1 text-center">
				<thead>
					<tr class="text-uppercase">
						<th>Fecha de emisión</th>
						<th>Forma de Pago</th>
						<th class="th_moneda">Tipo moneda</th>
						
					</tr>
				</thead>
				<tbody class="text-center">
					<tr>
						<td>'.$cpe['cabecera']['fecha_emision'].'</td>
						<td>'.$cpe['cabecera']['forma_de_pago'].'</td>
						<td  class="td_moneda">'.$cpe['cabecera']['nombre_moneda_doc'].'</td>
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
						<td colspan="6" style="border: 1px solid #5f5f5f!important" class="text-uppercase text-left font-weight-bold">'.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</td>
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
								<div class="mt-3">
									<p><span class="font-weight-bold">COMPRADOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p>Representación impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
									'.$text_pdf_2.'
									
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
			
			<div class="text-center mt-2 mb-5">'.$text_pdf_3.'</div>
			<div class="text-center mt-2">
				'.$aviso_texto_pruebas.'
			</div>
		</body>
		</html>
		';
        $resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;	
    }
	public function get_html_plantilla_a4_91($cpe) {  //ID: 1, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
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
			<span class="simbolo_moneda">'. $cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gravadas']).'
			</td>
		</tr>
		';

		if(floatval($cpe['cabecera']['total_inafecta']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_inafecta']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exoneradas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exoneradas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_gratuitas']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_gratuitas']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_exportacion']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_exportacion']).'
				</td>
			</tr>
			';
		}

		if(floatval($cpe['cabecera']['total_icbper']) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_icbper']).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$cpe['cabecera']['porcentaje_igv'].'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total_igv']).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format(round(floatval($cpe['cabecera']['total_descuento']), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'
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

			$monto_percepcion = $cpe['cabecera']['data_percepcion']['percepcion_moneda_simbolo'].' '.custom_money_format($cpe['cabecera']['data_percepcion']['percepcion_monto']);
			
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
				$height = "style='height: 230px;'";
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
								<div class="mt-3">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-3 mb-3">
									<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa del Documento Electrónico</p>
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
			'.$html_cuentas_corrientes;
			
			if($cpe['cabecera']['data_yape']['mostrar_yape'] == 'si'){
				if($cpe['cabecera']['data_plin']['mostrar_plin'] == 'si'){
					$tb_pin = 'float-left';
				}else{
					$tb_pin = '';
				}
				
				$html = $html.'
				
				
				<table class="'.$tb_pin.' mb-3" style="width:49%;margin-right:1.3em">
					<tbody>
						<tr>
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/yape_logo.png" alt="yape-logo"  width="60px"> 
								<img src="https://arpsystem.com.pe'.$cpe['cabecera']['data_yape']['img_qr_yape'].'" alt="codigo-qr" class="pl-2" width="60px"> 
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
							<td width="140px"> 
								<img src="https://arpsystem.com.pe/facturacionv8/img/plin_logo.png" alt="yape-logo"  width="60px"> 
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
    public function get_html_plantilla_ticket_88($cpe) { //ID: 88, TAMAÑO: A4, ==> ORDEN DE COMPRA
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
			<p><span class="font-weight-bold">N° Guía:</span> '.$cpe['cabecera']['guia_remision'].'</p>';
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

		
		
		if($cpe['cabecera']['codigomoneda'] == 'USD') {
			$resumen = $resumen.'
			<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'</p>
			<p>T.C ('.$cpe['cabecera']['tipo_cambio_sunat'].'): S/. '.custom_money_format(round($cpe['cabecera']['tipo_cambio_sunat']*$cpe['cabecera']['total'], 2)).'</p>
		
			';
		}else{
			$resumen = $resumen.'
			<p>Total: <span class="simbolo_moneda">'.$cpe['cabecera']['simbolo_moneda'].'</span> '.custom_money_format($cpe['cabecera']['total']).'</p>
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
		
		$canjeo = '';
        if($cpe['cabecera']['id_tipo_cpe'] == '77'){
			$canjeo = '<p class="font-weight-bold text-uppercase">Canjear este tipo de documento por un documento electrónico (Factura o Boleta)</p>';
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
				font-size: 11px;
				position: relative;
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
			.codigo_qr_ticket{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
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
				<h3 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h3>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>'.$cpe['emisor']['direccion'].'</p>
				<p>'.$cpe['emisor']['ubigeo'].'</p>			
				<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 14px;" class="mr-2"/>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$cpe['emisor']['email'].'</p>';
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p>Website:'.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			</div>
			<div class="text-center">
				'.$text_pdf_1.'
			</div>
			<div class="date-client">
                <p style="font-weight: 600; text-align:center;">'.$cpe['cabecera']['nombre_cpe'].'</p>
				<p class="text-center">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
                <hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
                <p class="font-weight-bold text-uppercase">Datos del proveedor:</p>
                <p><span class="font-weight-bold">Razón Social:</span> '.$cpe['proveedor']['razon_social'].'</p>
                <p><span class="font-weight-bold">R.U.C:</span> '.$cpe['proveedor']['num_doc'].'</p>
                <p><span class="font-weight-bold">Dirección Fiscal:</span> '.$cpe['proveedor']['direccion_fiscal'].'</p>
			</div>
			<div class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold mb-4">
							<th>Cant.</th>
							<th>Descripción</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
					</tbody>
				</table>
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2"  style="font-size: 14px">
						'.$resumen.'
					</div>
				</div>
			</div>
			'.$html_lista_cuotas.'
			<footer class="mt-2">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
				'.$text_pdf_2.'
				<p class="texto_12">Importe en Letras: '.$cpe['cabecera']['total_letras'].' '.$cpe['cabecera']['nombre_moneda_doc'].'</p>
				<div class="text-center texto_10 mt-2 codigo_qr_ticket">
					
					'.$nota_doc.'
					<p class="mt-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
					Consulte su Documento en:</p>';

				
					$html = $html.'
				
					<p>VENDEDOR:  '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
					'.$text_pdf_3.'

					'.$aviso_texto_pruebas.'
					
				</div>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			
		</footer>
		</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
    }
}