<?php
class TemplateguiaremisionController extends ControllerBase
{
	public function get_html_plantilla_a4_1($cpe) { //ID: 13, TAMAÑO: A4, ==> GUIA REMISIÓN
		
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
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
		}
		//Fecha de vencimiento 
		$fecha_vencimiento = '';
		if(!empty($cpe['cabecera']['fecha_vencimiento'])){
			$fecha_vencimiento =  '<p><span class="font-weight-bold">Fecha Vencimiento:</span> '.$cpe['cabecera']['fecha_vencimiento'].'</p>';
		}
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		$with_direccion = '';
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
		}
		//Texto personalizado
		if(!empty($cpe['sucursal']['txt_pdf_a4_1'])) {
			$text_pdf_1 = '<p>'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p>'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) { 
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
			</tr>
			';
		}
		
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold mt-2">Observación:</p>
			<p class="mb-2">'.$cpe['cabecera']['documento_nota'].'</p>
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
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
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
				text-align: center;
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
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
			}
			td, th {
				border: 1px solid #5f5f5f;
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
		</style>
		<body>';
		
		if(empty($cpe['emisor']['logo_rectangular'])) {
			$height_masthead = 'style="height: 170px"';
				$html = $html.'
				<div class="masthead w-100" '.$height_masthead.'>
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
				$height_masthead = 'style="height: 205px"';
				$html = $html.'
				<div class="masthead w-100" '.$height_masthead.'>
					<div class="col-7 text-center" style="padding:10px 5px">
						<img  src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px;">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p><p>
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
			'.$text_pdf_1.'
		
			<table class="table">
				<tbody>
					<tr class="text-uppercase">
						<th>FECHA DE EMISIÓN</th>
						<th>FECHA DE TRASLADO</th>
						<th>DOCS. REFERENCIA</th>
						<th>MOTIVO TRASLADO</th>
						<th>MOD. TRANSPORTE</th>
					</tr>
					<tr>
						<td>'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</td>
						<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</td>
						<td>'.$cpe['cabecera']['guia_remision'].'</td>
						<td>'.$cpe['cabecera']['motivo_traslado'].'</td>
						<td>'.$cpe['cabecera']['modalidad_traslado'].'</td>
					</tr>
				</tbody>
			</table>
		
				
			<table class="table">
				<tbody>
					<tr class="text-uppercase">
						<th '.$with_direccion.'>Dirección de partida</th>
						<th '.$with_direccion.'>Dirección de llegada</th>
						'.$th_codigo_puerto.'
						'.$th_numero_contenedor.'
					</tr>
					<tr class="text-center">
						<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
						<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
						'.$codigo_puerto.'
						'.$numero_contenedor.'
					</tr>
				</tbody>
			</table>
				
			
			<table class="table text-center mt-4">
			';
			if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
				//transporte público
				$html = $html.'
						<tbody>
							<tr class="text-uppercase">
								<th>DESTINATARIO</th>
								<th>RUC TRANSPORTE</th>
								<th>RAZÓN SOCIAL TRANSP.</th>
							</tr>
							<tr>
								<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
							</tr>
						</tbody>
						';
			} else {
				//transporte privado
				$html = $html.'
				<tbody>
					<tr class="text-uppercase">
						<th>DESTINATARIO</th>
						<th>UNIDAD DE TRANSPORTE</th>
						<th>DATOS CONDUCTORES</th>
						<th>Licencia de Conducir</th>
					</tr>
					<tr>
						<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
						<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
					</tr>
				</tbody>
				';
			}
			$html = $html.'
			</table>
			'.$text_pdf_2.'
			<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table table-main">
					<tbody>
						<tr class="text-uppercase">
							<th>Cant.</th>
							<th width="400px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr>
							<td colspan="4" class="text-left" style="border: 1px solid #5f5f5f!important"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr>
							<td colspan="4"  class="text-left" style="border: 1px solid #5f5f5f!important"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4"  class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
						
					</tbody>
				</table>
			<div class="resumen_totales">
				<div class="col-12">
					'.$nota_doc.'
					<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-3 mb-3">
					<p class="mb-1 mt-3"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
					<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
					<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
					<p class="mb-3">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
					'.$aviso_texto_pruebas.'
					'.$text_pdf_3.'
					
				</div>
			</div>
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	public function get_html_plantilla_a4_2($cpe) { //ID: 14, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		$with_direccion = '';
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) { 
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td class="text-center">'.($item['codigo']).'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
			</tr>
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
		<style>
			@page{
				margin: 10px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
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
			table {
				border: 1px solid;
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
				color: #000;
			}
			td, th {
				text-align: center;
				padding: 5px;
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
			<table class="table table-2 bordered mt-30">
				<tbody class="text-center">
					<tr>
						<td class="text-center">
							<p class="font-weight-bold">Fecha de Emisión:</p>
							<p>'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p> 
						</td>
						<td class="border-left text-center">
							<p class="font-weight-bold">Fecha de Traslado:</p>
							<p>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p> 
						</td>
						<td class="border-left text-center">
							<p class="font-weight-bold">Docs. Referencia: </p>
							<p>'.$cpe['cabecera']['guia_remision'].'</p> 
						</td>
						<td class="border-left text-center">
							<p class="font-weight-bold">Motivo de traslado: </p>
							<p>'.$cpe['cabecera']['motivo_traslado'].'</p> 
						</td>
						<td class="border-left text-center" style="border-radius: 0.5px">
							<p class="font-weight-bold">Mod. Transporte: </p>
							<p>'.$cpe['cabecera']['modalidad_traslado'].'</p> 
						</td>
					</tr>
				</tbody>
			</table>
			<table class="table bordered table-3">
			<tbody>
				<tr class="text-uppercase bg-main text-white">
					<th '.$with_direccion.'>Dirección de partida</th>
					<th '.$with_direccion.'>Dirección de llegada</th>
					'.$th_codigo_puerto.'
					'.$th_numero_contenedor.'
				</tr>
				<tr class="text-center">
					<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
					<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
					'.$codigo_puerto.'
					'.$numero_contenedor.'
				</tr>
			</tbody>
		</table>
		<table class="table text-center mt-4 table-3 bordered">
		';
			if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
				//transporte público
				$html = $html.'
						<tbody>
							<tr class="text-uppercase bg-main text-white text-center">
								<th>DESTINATARIO</th>
								<th>RUC TRANSPORTE</th>
								<th>RAZÓN SOCIAL TRANSP.</th>
							</tr>
							<tr>
								<td width="300px">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
							</tr>
						</tbody>
						';
			} else {
			//transporte privado
			$html = $html.'
			<tbody>
					<tr class="text-uppercase bg-main text-white text-center">
						<th>DESTINATARIO</th>
						<th>UNIDAD DE TRANSPORTE</th>
						<th>DATOS CONDUCTORES</th>
						<th>Licencia de Conducir</th>
					</tr>
					<tr>
						<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
						<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
					</tr>
				</tbody>
			';
			}
			$html = $html.'
			</table>
			<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table  table-3 bordered">
					<tbody>
						<tr class="text-uppercase bg-main text-white">
							<th>Cant.</th>
							<th>Código</th>
							<th width="500px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr>
							<td colspan="5" class="text-left" style="border-top: 1px solid #000!important; border-bottom: 1px solid #000!important"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr>
							<td colspan="5" class="text-left" style="border-bottom: 1px solid #000!important"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="5"  class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<div class="resumen_totales">
					<div class="col-12">
						<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-3  mb-3">
						<p class="mb-1 mt-3"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
						<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
						<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
						<p class="mb-3">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
						'.$text_pdf_3.'
						'.$aviso_texto_pruebas.'
						<div class="w-100 mt-3 mb-2">
							'.$nota_doc.'
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
	public function get_html_plantilla_a4_3($cpe) { //ID: 15, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		$with_direccion = '';
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
			';
		}
		
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
			<h6 style="font-size: 14px;" class="mt-2 text-danger font-weight-bold">
			Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</h6>
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
			$height_masthead = 'style="height: 150px; padding:10px 5px"';
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
			$height_masthead = 'style="height: 160px"';
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
		'.$text_pdf_1.'
			<table class="table-datos-iniciales pt-2">
				<tbody>
					<tr class="text-uppercase">
						<td width="50%" valign="top">
							<p><span class="font-weight-bold text-uppercase">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> <span class="text-uppercase">'.ucwords($cpe['cliente']['doc_identificacion_numero']).'</span></p>
							<p><span class="font-weight-bold text-uppercase">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> <span class="text-uppercase">'.$cpe['cliente']['nombre'].'</span></p>
							<p><span class="font-weight-bold text-uppercase">Vendedor:</span> <span class="text-uppercase">'.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</span></p>
							<p><span class="font-weight-bold text-uppercase">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
						</td>
						<td  width="50%" valign="top">
							<p><span class="font-weight-bold text-uppercase">F. Emisión:</span> <span class="text-uppercase">'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</span></p>
							<p><span class="font-weight-bold text-uppercase">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
							<p><span class="font-weight-bold text-uppercase">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
							'.$cpe['cabecera']['guia_remision'].'
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class="table-content mt-3">
				<table class="table-main-head text-center">
					<tbody>
						<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
							'.$th_codigo_puerto.'
							'.$th_numero_contenedor.'
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
							'.$codigo_puerto.'
							'.$numero_contenedor.'
						</tr>
					</tbody>
				</table>
				<table class="table-main-head text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
									<th width="250px">DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td width="250px">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
							<th >DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td width="250px">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head mt-1">
					<tbody>
						<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
							<th>Cant.</th>
							<th width="500px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr>
							<td colspan="4" class="text-left" style="border-top:1px solid #000; border-bottom: 1px solid"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr>
							<td colspan="4" class="text-left" style="border-bottom: 1px solid #000!important"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4" class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
					</tbody>
					'.$text_pdf_2.'
				</table>
			</div>
			<footer class="mt-2">
				<div class="col-12 mb-3">
					'.$nota_doc.'
				</div>
				
				<div class="col-12 footer-info">
					<p class="font-weight-bold text-uppercase">Consulte su documento electrónico en:</p>
					<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="40px" class="float-left mr-3 mb-3">
					<p>'.$cpe['patrocinador']['url_consulta_cpe'].' </p>
					<p><span class="font-weight-bold">Hash:</span> '.$cpe['cabecera']['hash'].'
					<p><span class="font-weight-bold">Vendedor:</span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
					'.$text_pdf_3.'
					<p>Representación impresa de Guía de remisión</p>
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
	public function get_html_plantilla_a4_4($cpe) { //ID: 16, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		$with_direccion = '';
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
		}
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-primary ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		<style>
			@page{
				margin: 10px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
				font-size: 12px!important;
			}
			
			h1, h2, h3, h4, h5, table {
				font-size: 12px!important;
			}
			header {
				border-bottom: 1px solid #007bff;
				text-align: center;
				height: 30px;
				margin-bottom: 10px;
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
			.table-main-head td {
				text-align: center;
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
				height: 80px;
				padding: 0 10px;
				width: 33.333333333333%;
			}
			.table-main-head{
				width: 100%;
				border-collapse: collapse;
			}
			.tb_resumen_totales td {
				padding: 8px 10px;
				font-weight: 700;
				border-top: none;
			}

			.resumen_totales {
				height: 230px;
			}
			/*footer {
				position: absolute;
				bottom: 0%;
				border-top: 1px solid #007bff;
				width: 100%;
			}*/
			.table-2 td, .table-1 td{
				text-align: center;
			}
		</style>
		<body>
			<header>
				<h4 class="pt-2 float-left text-left text-uppercase" style="font-size: 14px; width: 500px">
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
					<h4 class="text-primary text-uppercase  mb-2 mt-4 text-center font-weight-bold" style="font-size: 18px!important">R.U.C.  '.$cpe['emisor']['ruc'].'</h4>
				</div>
			</div>
			<div id="servicios" class="contenedor borde-primary">
				<div class="single-date" id="columna1">
				   <p><span class="text-primary font-weight-bold">Razon social:</span> '.ucwords($cpe['cliente']['nombre']).'</p>
				   <p><span class="text-primary font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				   
				</div>			
				<article class="single-date">
					<p><span class="text-primary font-weight-bold">RUC:</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
					<p><span class="text-primary font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
					'.$cpe['cabecera']['guia_remision'].'
				</article>			
				<article class="single-date text-left">
					<p><span class="text-primary font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
					<p><span class="text-primary font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
				</article>			
			</div>
			<div class="col-12">
				'.$text_pdf_1.'
			</div>
			<section>
				<table class="table-main-head table-1 text-center">
					<tbody>
						<tr class="bg-primary text-white text-uppercase">
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
							'.$th_codigo_puerto.'
							'.$th_numero_contenedor.'
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
							'.$codigo_puerto.'
							'.$numero_contenedor.'
						</tr>
					</tbody>
				</table>
				<table class="table-main-head table-2 text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="bg-primary text-white text-uppercase">
									<th>DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="bg-primary text-white text-uppercase">
							<th>DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head">
					<tbody>
						<tr class="bg-primary text-white text-uppercase">
							<th>Cant.</th>
							<th width="500px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr>
							<td colspan="4" style="text-align: left!important"><span class="font-weight-bold" >Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr>
							<td colspan="5" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4"  style="text-align: left!important"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
				<div class="resumen_totales">
					';
					if(!empty($cpe['cabecera']['documento_nota'])) {
					$html = $html.'
					<p class="mt-2"><strong class="text-primary">Observación: </strong>'.$cpe['cabecera']['documento_nota'].'</p>
					';
					}
					$html = $html.'
					<div class="col-7">
						<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-3 mt-3 mb-3">
						<p class="mb-1 mt-3"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
						<p><span class="text-primary font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
						<p><span class="text-primary font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
						
						<p class="mb-3">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
						'.$aviso_texto_pruebas.'
						<div class="w-100 mt-3 mb-2">
							
							'.$text_pdf_3.'
						</div>
					</div>
				</div>
			</section>
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	public function get_html_plantilla_a4_5($cpe) { //ID: 17, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		$with_direccion = '';
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
		}
		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold text-primary ">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
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
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="text-primary font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}

		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
		<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
				<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title></title>
			</head>
			<body>
				<style>
					@import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap");
					body {
						font-family: "Open Sans",Tahoma,Geneva,sans-serif;
						position: relative;
						font-size: 12px!important;
					}
					
					table {
						font-size: 12px!important;
					}
					h1{
						font-size: 20px!important;
					}
					h2, h3, h4, h5, h6{
						font-size: 12px;
					}
					ul {
						list-style-type: circle;
						margin-right: 0!important;
						padding: 0 0 0 10px;
					}
					td, th {
						border-bottom: 1px solid #dddddd;
						padding: 5px 2px 5px 5px;
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
						width: 25%!important;
						float: left;
						padding: 0;
					}
					.col-4{
						width: 33.333333333333%!important;
						float: left;
						padding: 0;
					}
					.col-5 {
						width: 41.66666667%;
						float: left;
						padding: 0;
					}
					.col-6 {
						width: 50%!important;
						float: left;
						padding: 0;
					}
					.col-7 {
						width: 75%!important;
						float: left;
						padding: 0;
					}
					.col-w-8 {
						width: 80%!important;
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
					.invoice-total {
						width: 100%;
						height: 80px;
					}
					.display-inline{
						display: inline;
					}
					/*footer {
						position: absolute;
						bottom: 0;
						width: 100%;
					}*/
					.table-main-head td{
						text-align: center;
					}
					.footer-table {
						float: right;
						margin: 10px;
						padding: 0 10px;
						width: 250px;
						text-align: left;
					}
					.footer-table p{ padding-bottom: 10px}
					.header-main{
						width: 100%;
						height: 300px;
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
						margin: 00px;
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
					.masthead .img-header{
						position: absolute;
						top: 0;
						right: 0;
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
					.single-header1, .single-header2, .single-date, .info-total, .date-price, .single-footer-wight, .social-media{
						float: left;
						/*margin: 10px;
						padding: 10px;*/
					}
					
					.single-date {
						width: 33.333333333333%;
						padding: 0 10px 0 0;
						margin: 10px 10px 10px 0;
					}
					.single-date-client{
						border-top: 1px dashed #007bff;
    					padding-top: 2em;
					}
					.single-footer-wight{
						/*width: 200px;*/
					}
					.single-footer-wight.box-main{
						/*width: 600px;*/
						padding: 0px 10px 10px 0;
						margin: 0px 10px 10px 0;
					}
					.table-main-head{
						width: 100%;
						border-collapse: collapse;
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
						height: 50px;
						width: 100%;
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
					.table-cuentas {
						float: right;
						font-size: 12px;
					}
					.table-cuentas td, .table-cuentas th {
						padding: 0px 5px;
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
							<h4 class="font-weight-light text-primary mt-3" style="font-size: 18px">'.$cpe['emisor']['nombre_comercial'].'</h4>
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
						<div class="pl-3 col-8">
							<h2 class="pt-2 font-weight-light text-uppercase" style="line-height: 1.5; font-size: 16px">'.$cpe['cabecera']['nombre_cpe'].': <br> <span  class="bg-primary text-white mt-2">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</span></h2> 
							<div id="date_invoice">
								<div class="single-date">
									<p class="text-primary font-weight-bold">Fecha emisión:</p>
									<p> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
									<p class="text-primary font-weight-bold">Fecha de traslado: </p>
									<p>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
								</div>	
								<div class="single-date">
									<p><span class="text-primary font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
									<p><span class="text-primary font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
								</div>
								
							</div>		
						</div>	
					</div>
				</div> 
				<div class="single-date-client mt-2" style="height: 80px">
					<div class="col-12 p-0">
						<p><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
						<p><span class="text-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$cpe['cabecera']['guia_remision'].'
						'.$text_pdf_1.'
					</div>
					
				</div>	
				<div class="table-invoice mt-2 pt-2">
					<table class="table-main-head text-center">
						<tbody>
							<tr class="bg-primary text-white text-uppercase">
								<th '.$with_direccion.'>Dirección de partida</th>
								<th '.$with_direccion.'>Dirección de llegada</th>
								'.$th_codigo_puerto.'
								'.$th_numero_contenedor.'
							</tr>
							<tr>
								<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
								<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
								'.$codigo_puerto.'
								'.$numero_contenedor.'
							</tr>
						</tbody>
					</table>
					<table class="table-main-head text-center mt-4">
					';
					if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
						//transporte público
						$html = $html.'
								<tbody>
									<tr class="bg-primary text-white text-uppercase">
										<th>DESTINATARIO</th>
										<th>RUC TRANSPORTE</th>
										<th>RAZÓN SOCIAL TRANSP.</th>
									</tr>
									<tr>
										<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
										<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
										<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
									</tr>
								</tbody>
								';
					} else {
						//transporte privado
						$html = $html.'
						<tbody>
							<tr class="bg-primary text-white text-uppercase">
								<th>DESTINATARIO</th>
								<th>UNIDAD DE TRANSPORTE</th>
								<th>DATOS CONDUCTORES</th>
								<th>Licencia de Conducir</th>
							</tr>
							<tr>
								<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
								<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
							</tr>
						</tbody>
						';
					}
					$html = $html.'
					</table>
					'.$text_pdf_2.'
					<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
					<table class="table-main-head mt-1">
						<tbody>
							<tr class="bg-primary text-white">
								<th>Cant.</th>
								<th width="500px">Producto</th>
								<th>UNID/MED </th>
								<th>Peso</th>
							</tr>
							'.$items_detalle_html.'
							<tr>
								<td colspan="4" style="text-align: left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
							</tr>
							<tr>
								<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
							</tr>
						</tbody>
					</table>
					'.$nota_doc.'
					
				</div>
				<footer class="mt-2">
					<div class="sub-footer">
						<div class="col-12">
							<div class="pr-2">
								<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="50px" class="float-left mr-3">
								<p class="mb-1"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</p> 
								<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
								<p><span class="text-primary font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
								<p><span class="text-primary font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
								'.$text_pdf_3.'
								<p class="mb-3">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
								'.$aviso_texto_pruebas.'
							</div>
						</div>
					</div>
				</footer>
			</body>
		</html>
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	public function get_html_plantilla_a4_6($cpe) { //ID: 18, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';

		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
		}
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="text-theme-default text-left font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
			$margin_top = 'mt-1';
		} else {
			$nota_doc = '';
			$margin_top = '';
		}

		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;"><div style="width: 330px !important;">'.$item['descripcion'].'</div></td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
			';
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
				<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title></title>
				<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
    		</head>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0px;
				}

				body{
					position: relative;
					font-size: 12px!important;
				}
				
				h1, h2, h3, h4, h5,  table {
					font-size: 12px!important;
				}
				
				/*body::before{
					content: " ";
					position: absolute;
					bottom: 0;
					left: 0;
					width: 100%;
					height: 300px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p3-3.jpg);
					background-size: cover;
					background-repeat: no-repeat;
					z-index: -1;
				}*/
			
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
					padding: 8px 0 8px 8px;
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
				.col-12 {
					width: 100%;
					float: left;
					padding: 0;
				}
				.contenedor {
					margin: 10px auto;
					float: right;
					width: 100%;
					padding-top: 1em;
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
				/*.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p3-2.jpg);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				}*/
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
				.table-cuentas {
					float: right;
					font-size: 12px;
				}
				.table-cuentas td, .table-cuentas th {
					padding: 5px 10px;
				}
				/*.single-footer-wight p {
					font-size: 12px;
				}*/
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
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
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
						<h4 class="text-theme-default text-uppercase  mb-2 mt-4 text-center  font-weight-bold" style="font-size: 18px!important">R.U.C.  '.$cpe['emisor']['ruc'].'</h4>
					</div>
				</div>
				<div id="servicios" class="contenedor borde-primary">
					<div class="single-date" id="columna1">
						<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
						<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						
					</div>			
					<article class="single-date">
						<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						<p><span class="text-theme-default font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
						'.$cpe['cabecera']['guia_remision'].'
					</article>			
					<article class="single-date text-left">
						<p><span class="text-theme-default font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
						<p><span class="text-theme-default font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
					</article>			
				</div>
				<div class="col-12">
					'.$text_pdf_1.'
				</div>
				<section>
					<table class="table-main-head">
						<tbody>
							<tr class="bg-theme-default text-white text-uppercase">
								<th '.$with_direccion.'>Dirección de partida</th>
								<th '.$with_direccion.'>Dirección de llegada</th>
								'.$th_codigo_puerto.'
								'.$th_numero_contenedor.'
							</tr>
							<tr>
								<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
								<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
								'.$codigo_puerto.'
								'.$numero_contenedor.'
							</tr>
						</tbody>
					</table>
					<table class="table-main-head text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="bg-primary text-white text-uppercase">
									<th>DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="bg-theme-default text-white text-uppercase">
							<th>DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
					<table class="table-main-head mt-1">
						<tbody>
							<tr class="bg-theme-default  text-white text-uppercase">
								<th>Cant.</th>
								<th width="500px">Producto</th>
								<th>UNID/MED </th>
								<th>Peso</th>
							</tr>
							'.$items_detalle_html.'
							<tr>
								<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
							</tr>
							<tr>
								<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
							</tr>
							<tr>
								<td colspan="4"  class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
							</tr>
							'.$text_pdf_2.'
						</tbody>
					</table>
					'.$nota_doc.'
					
				</section>
				<footer class="'.$margin_top.'">
					<div class="col-12">
						<div class="single-footer-wight box-main mb-2">
						<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-3 mt-3 mb-3">
						<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
						<p><span class="text-theme-default font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
						<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
						'.$text_pdf_3.'
						<p class="mb-1">Representación Impresa del Documento Electrónico</p>
						'.$aviso_texto_pruebas.'
						</div>
					</div>
				</footer>
			</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	public function get_html_plantilla_a4_7($cpe) { //ID: 19, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="text-theme-default text-left font-weight-bold">Observación:</p>
			<p class="mb-1">'.$cpe['cabecera']['documento_nota'].'</p>
			';
			$margin_top = 'mt-1';
		} else {
			$nota_doc = '';
			$margin_top = '';
		}
			
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger text-center">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
		<body>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0;
				}
				body{
					font-family: "Montserrat", sans-serif;	
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
				h1, h2, h3, h4, h5, table {
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
				td, th {
					border-bottom: 1px solid #dddddd;
					text-align: left;
					padding: 10px 5px 10px 15px;
				}
				p{
					margin:0;
					padding: 0;
				}
				.font-weight-bold{
					font-weight: bold;
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
				.bg-theme-default{
					background: #77af18;
				}
				.border-bottom-black {
					border-bottom: 1px solid #000;
					position: absolute;
					bottom: em;
					right: 0;
					z-index: 2;
					width: 100%;
				}
				
				.empresa-info{
					height: 180px;
					margin-bottom: 1.5em;
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
					top: 0;
					right: 1em;
					width: 300px;
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
				.table-1 td, .table-2 td, .table-1 th, .table-2 th{
					text-align: center;
				}
				.table-cuentas {
					float: right;
					font-size: 10px;
				}
				.table-cuentas td, .table-cuentas th {
					padding: 5px;
				}
				.tb_resumen_totales {
					width: auto;
				}
				.tb_resumen_totales td {
					padding: 8px 10px 8px 30px;
					font-weight: 700;
				}
				.text-theme-default{
					color: #77af18;
				}
				.text-theme-secundary{
					color: #333333;
				}
				
			
				.resumen_totales {
					height: 150px;
				}
				
			</style>
			<div class="masthead">
				<div class="logo_img">';
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
					<h5 class="text-white text-uppercase font-weight-bold pt-1" style="font-size:18px!important;">'.$cpe['cabecera']['nombre_cpe'].': <br> '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h5>
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
						<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span>  '.ucwords($cpe['cliente']['nombre']).'</p>
						<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						<p><span class="text-theme-default font-weight-bold">Docs. Referencia: </span> '.$cpe['cabecera']['guia_remision'].'</p>
					</div>
					<hr>
					<p><span class="text-theme-default font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
					<p><span class="text-theme-default font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
					<p><span class="text-theme-default font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
					<p><span class="text-theme-default font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
				</div>
			</div>
			<div class="col-12">
				'.$text_pdf_1.'
			</div>
			<section class="table-content">
				<table class="table-main-head table-1 text-center">
					<tbody>
						<tr class="bg-theme-default  text-white text-uppercase">
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
							'.$th_codigo_puerto.'
							'.$th_numero_contenedor.'
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
							'.$codigo_puerto.'
							'.$numero_contenedor.'
						</tr>
					</tbody>
				</table>
				<table class="table-main-head table-2 text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="bg-theme-default text-white text-uppercase">
									<th>DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="bg-theme-default text-white text-uppercase">
							<th>DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="text-uppercase bg-theme-default  font-weight-bold text-white text-center">
						<th>Cant.</th>
						<th width="500px">Producto</th>
						<th>UNID/MED </th>
						<th>Peso</th>
					</tr>
					'.$items_detalle_html.'
					<tr>
						<td colspan="4"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
					</tr>
					<tr>
						<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
					</tr>
					<tr>
						<td colspan="4"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
					</tr>
					'.$text_pdf_2.'	
					</tbody>
				</table>
				
			</section>
			<footer class="mt-1">
				<div class="col-12">
					<div class="single-footer-wight box-main">
						'.$nota_doc.'
						<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-2 mb-3">
						<p class="mb-1 mt-1"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
						<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
						'.$text_pdf_3.'
						<p class="mb-3">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
						'.$aviso_texto_pruebas.'
					</div>
				</div>
			</footer>
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	
	public function get_html_plantilla_a4_9($cpe) {  //ID: 38, TAMAÑO: A4, ==> GUIA REMISIÓN
		$this->view->disable();
		
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
			
			table {
				font-size: 11px!important;				
			}
		
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;

			}
			.table td, .table th {
				padding: 5px;
				vertical-align: top;
				border-top: 1px solid #dee2e6;
			}
			td, th {
				text-align: left;
				padding: 5px;
			}
			th {
				text-align: center;
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
			.col-6-right {
				width: 50%;
				float: right;
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
				margin-top: -3em;
			}
			
			
			/*.line-width-head{
				width: 35%;
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
				padding: 4em 4em 0 4em;
				color: #fff;
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
			.social {
				padding-left: 0;
				list-style-type: none;
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
				color: #f9c24e;
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
				top: -2.8em;
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
				padding: 5px 2px 5px 5px;
			}
			tr:nth-child(even) {
				background-color: #eee;
			}
			.tb_resumen_totales {
				float: right;
			}
			.tb_resumen_totales  tr:nth-child(even) {
				background-color: #fff;
			}
			.table-main-head td{
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
					<div class="col-4">
						<h6 class="font-weight-bold text-uppercase text-theme-primary">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h6>
					</div>
					<div class="col-4 icons_info">
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
				<div class="col-6-right text-center">
					<h5 class="nombre_documento_titular text-uppercase font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</h4>
				</div>
			</div>
			<div class="box-content mt-4">
				<table class="table">
					<tr>
						<td width="33%">
							<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
							<p><span class="text-theme-color font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						</td>
						<td width="33%">
							<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							<p><span class="text-theme-color font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
							'.$cpe['cabecera']['guia_remision'].'
						</td>
						<td width="33%">
							<p><span class="text-theme-color font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
							<p><span class="text-theme-color font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-12">
				'.$text_pdf_1.'
			</div>
			<section class="table-content">
				<table class="table-main-head table text-center">
					<tbody>
						<tr class="bg-theme-color   text-uppercase">
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
							'.$th_codigo_puerto.'
							'.$th_numero_contenedor.'
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
							'.$codigo_puerto.'
							'.$numero_contenedor.'
						</tr>
					</tbody>
				</table>
				<table class="table-main-head table text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="bg-theme-color text-uppercase">
									<th>DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="bg-theme-color  text-uppercase">
							<th>DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head table mt-2">
					<tbody>
						<tr class="text-uppercase bg-theme-color  font-weight-bold  text-center">
						<th>Cant.</th>
						<th width="500px">Producto</th>
						<th>UNID/MED </th>
						<th>Peso</th>
					</tr>
					'.$items_detalle_html.'
					<tr>
						<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
					</tr>
					<tr>
						<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
					</tr>
					<tr>
						<td colspan="4" class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
					</tr>
					'.$text_pdf_2.'	
					</tbody>
				</table>
			</section>
			'.$nota_doc.'
			<div class="mt-1">
				<img style="width: 60px;" class="float-left mr-2" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" />
				<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
				<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
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
	public function get_html_plantilla_a4_10($cpe) {  //ID: 41, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th class="text-center">Código de puerto</th>';
			$codigo_puerto = '<td class="text-center">'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th class="text-center">Número de contenedor</th>';
			$numero_contenedor = '<td class="text-center">'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
					<div class="col-12" style="margin-top:4em">';
					if(empty($cpe['emisor']['logo_rectangular'])) {
						$style_class ='pt-3 mt-2';
						$html = $html.'
						<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" '.$style_class.' />';
						
					}else{
						
						$html = $html.'
						<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'"  />';
					}
						$html = $html.'
					</div>
					<table>
						<tr>
							<td width="50%" class="text-left">
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
							<td width="50%" class="text-left">
							<h6 class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'</h6>
							<h6 class="font-weight-bold text-uppercase text-theme-primary">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h6>
							</td>
						</tr>
					</table>
				</div>
				</div>
				<div style="padding-bottom: 1em; margin-bottom: 1em; border-bottom:  1px solid #ededed;"></div>
				<table class="w-100 table_datos_main">
					<tr>
						<td width="50%" valign="top" class="text-left">
							<p><span class="text-theme-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
							<p><span class="text-theme-primary font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						</td>
						<td width="50%" valign="top" class="text-left">
						<p><span class="text-theme-primary font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<p><span class="text-theme-primary font-weight-bold">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
						</td>
						
					</tr>
				</table>
				<div class="col-12">
					'.$text_pdf_1.'
				</div>
				<section class="table-content">
					<table class="table table-main-head table-1 text-center">
						<tbody>
							<tr class="bg-theme-primary  text-white text-uppercase">
								<th '.$with_direccion.'  class="text-center">Dirección de partida</th>
								<th '.$with_direccion.'  class="text-center">Dirección de llegada</th>
								'.$th_codigo_puerto.'
								'.$th_numero_contenedor.'
							</tr>
							<tr>
								<td  class="text-center">'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
								<td  class="text-center">'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
								'.$codigo_puerto.'
								'.$numero_contenedor.'
							</tr>
						</tbody>
					</table>
					<table class="table table-main-head table-2 text-center mt-4">
					';
					if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
						//transporte público
						$html = $html.'
								<tbody>
									<tr class="bg-theme-primary text-white text-uppercase">
										<th class="text-center">DESTINATARIO</th>
										<th class="text-center">RUC TRANSPORTE</th>
										<th class="text-center">RAZÓN SOCIAL TRANSP.</th>
									</tr>
									<tr>
										<td  class="text-center">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
										<td  class="text-center">'.$cpe['cabecera']['nro_documento_transporte'].'</td>
										<td  class="text-center">'.$cpe['cabecera']['razon_social_transporte'].'</td>
									</tr>
								</tbody>
								';
					} else {
						//transporte privado
						$html = $html.'
						<tbody>
							<tr class="bg-theme-primary text-white  text-uppercase">
								<th class="text-center">DESTINATARIO</th>
								<th class="text-center">UNIDAD DE TRANSPORTE</th>
								<th class="text-center">DATOS CONDUCTORES</th>
								<th class="text-center">Licencia de Conducir</th>
							</tr>
							<tr>
								<td  class="text-center">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td  class="text-center">'.$cpe['cabecera']['transporte_nro_placa'].'</td>
								<td  class="text-center">'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td  class="text-center">'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
							</tr>
						</tbody>
						';
					}
					$html = $html.'
					</table>
					
					<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
					<table class="table table-main-head mt-2">
						<tbody>
							<tr class="text-uppercase bg-theme-primary  font-weight-bold text-white text-center">
								<th>Cant.</th>
								<th width="500px">Producto</th>
								<th>UNID/MED </th>
								<th>Peso</th>
							</tr>
							'.$items_detalle_html.'
							<tr>
								<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
							</tr>
							<tr>
								<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
							</tr>
							<tr>
								<td colspan="4" class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
							</tr>
							'.$text_pdf_2.'	
						</tbody>
					</table>
					<div class="invoice-price-footer">
						<div class="method-price">
							'.$nota_doc.'
						</div>
					</div>
				</section>
				<footer class="mt-1">
					<div class="col-12">
						<div class="single-footer-wight box-main">
							<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-2  mb-3">
							<p class="mb-1 mt-3"><span class="text-theme-primary font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
							<p><span class="text-theme-primary font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							<p><span class="font-weight-bold text-theme-primary">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
							'.$text_pdf_3.'
							<p class="mb-3">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
							'.$aviso_texto_pruebas.'
						</div>
					</div>
				</footer>
			</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;		
	}
	public function get_html_plantilla_a4_11($cpe) {  //ID: 44, TAMAÑO: A4, ==> GUIA REMISIÓN
		$this->view->disable();
	
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
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
			
			 h5,  table {
				font-size: 11px;
			}
			.table-no-border td, .table-no-border th {
				border: none;
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
				font-size: 11px;
			}
			td, th {
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
				top: .3em;
				right: 1em;
				width: 300px;
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
			}';
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
				height: 120px;
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
				font-size: 12px;
				font-weight: 400;
			}
			.head-date{
				height: 180px;
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
					<h5 class="text-white text-uppercase font-weight-bold" style="font-size:18px!important;">'.$cpe['cabecera']['nombre_cpe'].': <br>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</h5>
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
					<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
					<p><span class="text-theme-default font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				</div>
				<div class="col-4">
					<p class="text-title-theme">Detalles:</p>
					<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#136493"></div>
					<p><span class="text-theme-default  font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
					<p><span class="text-theme-default font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
					<p><span class="text-theme-default font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
					'.$cpe['cabecera']['guia_remision'].'
				</div>
			</div>

			<div class="col-12">
				'.$text_pdf_1.'
			</div>
			<section>
				<table class="table-main-head text-center">
					<tbody>
						<tr class="text-uppercase">
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
							'.$th_codigo_puerto.'
							'.$th_numero_contenedor.'
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
							'.$codigo_puerto.'
							'.$numero_contenedor.'
						</tr>
					</tbody>
				</table>
				<table class="table-main-head  text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="text-uppercase">
									<th>DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td width="250px">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="text-uppercase">
							<th>DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head">
					<tbody>
						<tr class="text-uppercase">
							<th>Cant.</th>
							<th width="400px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr>
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4" class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
					</tbody>
				</table>
				'.$nota_doc.'
				<table  class="table-no-border">
				<tbody>
					<tr>
						<td style="vertical-align: top;">
							<table  class="table-no-border">
								<tbody>
									<tr>
										<th style="width:80px;">
											<img style="width: 60px; margin-bottom: 11px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" />
										</th>
										<td class="texto_general">
											
											<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
											<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
											<p><span class="font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
											<p class="mb-3">Representación Impresa de '.$cpe['cabecera']['nombre_cpe'].'</p>
											'.$text_pdf_2.'
											
										</td>
									</tr>
									<tr>
									 	<td colspan="2">'.$aviso_texto_pruebas.'</td>
									</tr>
									
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			'.$text_pdf_3.'
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;		
	}
	public function get_html_plantilla_a4_12($cpe) {  //ID: 47, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}

		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
			';

		}
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td align="center">'.$item['codigo'].'</td>
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
			<title> '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
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
				border-top: 1px solid #dddddd;
				border-bottom: 1px solid #dddddd;
				border-right: 2px solid #fff;
				border-left: 2px solid #fff;
				padding: 5px!important;
			}
			.table-no-boder td{
				border:0
			}
			th {
				text-align: center;
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
				width: 200px;
				height: 200px;
				box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
			}
			.masthead {
				display: block;
				height: 280px;
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
			.single-date {
				height: 80px;
			}
			.box-white-content{
				background: #fff;
				border-top-left-radius: 7%;
				border-bottom-left-radius: 7%;
				margin-top: -5em;
			}
			.table-main-head td{
				text-align: center;
			}
			.pl-5{ padding-left: 2.5em!important}
		</style>
		<body>
			<div class="masthead">
				<div class="col-6">
				 	<div class="circle_head text-center">';
					 if(empty($cpe['emisor']['logo_rectangular'])) {
						 $html = $html.'
						 <img style="width: 80px; height: 80px" src="'.$cpe['emisor']['logo_cuadrado'].'" class="mb-2 mt-5"  />';
						 
					 }else{
						 $html = $html.'
						 <img style="width: 150px;"  src="'.$cpe['emisor']['logo_rectangular'].'" class="mb-2 mt-5"  />';
					 }
					 $html = $html.'
						<h6 class="mb-0 text-white "><span class="font-weight-bold">R.U.C. </span>'.$cpe['emisor']['ruc'].'</h6>
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
				<table class="table-no-boder table">
					<tr>
						<td width="33%" class="pl-5">
							<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
							<p><span class="text-theme-color font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						</td>
						<td width="33%">
							<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
							<p><span class="text-theme-color  font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
						</td>
						<td width="33%">
							<p><span class="text-theme-color font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
							<p><span class="text-theme-color font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
							'.$cpe['cabecera']['guia_remision'].'
						</td>
					</tr>
				</table>
		
				<div class="col-12">
					'.$text_pdf_1.'
				</div>
				<section class="table-content">
					<table class="table-main-head table text-center">
						<tbody>
							<tr class="bg-theme-color  text-white text-uppercase">
								<th '.$with_direccion.'>Dirección de partida</th>
								<th '.$with_direccion.'>Dirección de llegada</th>
								'.$th_codigo_puerto.'
								'.$th_numero_contenedor.'
							</tr>
							<tr>
								<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
								<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
								'.$codigo_puerto.'
								'.$numero_contenedor.'
							</tr>
						</tbody>
					</table>
					<table class="table-main-head table text-center mt-4">
					';
					if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
						//transporte público
						$html = $html.'
								<tbody>
									<tr class="bg-theme-color text-white text-uppercase">
										<th>DESTINATARIO</th>
										<th>RUC TRANSPORTE</th>
										<th>RAZÓN SOCIAL TRANSP.</th>
									</tr>
									<tr>
										<td width="250px">'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
										<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
										<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
									</tr>
								</tbody>
								';
					} else {
						//transporte privado
						$html = $html.'
						<tbody>
							<tr class="bg-theme-color text-white text-uppercase">
								<th>DESTINATARIO</th>
								<th>UNIDAD DE TRANSPORTE</th>
								<th>DATOS CONDUCTORES</th>
								<th>Licencia de Conducir</th>
							</tr>
							<tr>
								<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
								<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
							</tr>
						</tbody>
						';
					}
					$html = $html.'
					</table>
					<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
					<table class="table table-main-head">
						<tbody>
							<tr class="bg-theme-color text-white text-uppercase">
								<th>Código</th>
								<th>Cant.</th>
								<th width="400px">Producto</th>
								<th>UNID/MED </th>
								<th>Peso</th>
							</tr>
							<tr>
								'.$items_detalle_html.'
							</tr>
							<tr>
								<td colspan="5" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
							</tr>
							<tr>
								<td colspan="5" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
							</tr>
							<tr>
								<td colspan="5" class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
							</tr>
							'.$text_pdf_2.'
						</tbody>
					</table>
					<table  class="table table-no-border">
						<tbody>
							<tr>
								<td style="vertical-align: top;">
									'.$nota_doc.'
									<div class="single-footer-wight box-main">
										<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-2 mb-3">
										<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
										<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
										<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
										<p class="mb-3">Representación Impresa del Documento Electrónico</p>
										'.$aviso_texto_pruebas.'
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</section>
				
			</div>
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;		
	}
	public function get_html_plantilla_a4_13($cpe) {  //ID: 50, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="cabecera_detalle_items" style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td align="center">'.$item['codigo'].'</td>
				<td class="cabecera_detalle_items text-center" style="width: 50px;">'.($item['cantidad'] + 0).'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['unidad_medida'].'</td>
				<td class="cabecera_detalle_items" style="width: 50px;">'.$item['peso_det'].'</td>
			</tr>
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
		<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			.table td, .table th {
				padding: 5px;
			}
			body{
				position: relative;
				font-size: 12px!important;
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
				font-size: 12px!important;				
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
			th {
				text-align: center;
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
				font-size: 12px;
			}
			.box-content{
				width: 100%;
			}
			.body_head{
				z-index: 10;
			}
			.total_invoice{
				width: 100%;
				height: 79px;
				z-index: -1;
				color: #fff;
				background: #5baa01;
				position: relative;
				font-size: 15px;
			}
			.total_invoice::before{
				content: " ";
				position: absolute;
				top: 2.1em;
				right: 6em;
				width: 100%;
				height: 300px;
				background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-verde-2.jpg);
				background-repeat: no-repeat;
				z-index: -1;
			}
			.table-main-head{
				width: 100%;
				border-bottom: 1px solid #5baa01;
			}
			.table-main-head th {
				color: #fff;
				border-bottom: 1px solid #5baa01;
				border-top: 1px solid #5baa01;
				background: #5baa01;
			}
			.table-main-head td{ text-align: center}
			.tb_resumen_totales {
				float: right;
			} 
			.tb_resumen_totales td {
				border: none;
			} 
			.box-resumen{
				height: 200px;
			}
			
		</style>
		<body class="body_head">
			<div class="masthead">
				<div class="box-content  pl-3">
					<div class="col-12">';
						if(empty($cpe['emisor']['logo_rectangular'])) {
							$html = $html.'
							<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
							
						}else{
							$html = $html.'
							<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
						}
						$html = $html.'
					</div>
					<div class="single-date col-4 text-left">
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
						
					</div>
					
					<div class="single-date col-4 pr-2">
						<p class="text-title-theme">Cliente:</p>
						<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#5baa01"></div>
						<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
						<p><span class="text-theme-color font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						
					</div>			
					<div class="single-date col-4 pr-1">
						<p class="text-title-theme">Detalles:</p>
						<div class="line_theme_note mt-2 mb-2" style="width: 50px; height: 1px; background:#5baa01"></div>
						<p><span class="text-theme-color font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<p><span class="text-theme-color  font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
						<p><span class="text-theme-color font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
						<p><span class="text-theme-color font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
						'.$cpe['cabecera']['guia_remision'].'
					</div>			
				</div>
			</div>
			<div class="total_invoice mt-3">
				<div class="col-6-r text-right pr-3 pt-2 mb-4" style="width: 300px">
					<p class="nombre_documento_titular text-uppercase font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].': <br> '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
				</div>
			</div>

			<div class="col-12 mt-1">
				'.$text_pdf_1.'
			</div>
			<section class="pl-2 mt-3">
				<table class="table-main-head table text-center">
					<tbody>
						<tr class="text-uppercase">
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
							'.$th_codigo_puerto.'
							'.$th_numero_contenedor.'
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
							'.$codigo_puerto.'
							'.$numero_contenedor.'

						</tr>
					</tbody>
				</table>
				<table class="table-main-head table text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr class="text-uppercase">
									<th>DESTINATARIO</th>
									<th>RUC TRANSPORTE</th>
									<th>RAZÓN SOCIAL TRANSP.</th>
								</tr>
								<tr>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr class="text-uppercase">
							<th>DESTINATARIO</th>
							<th>UNIDAD DE TRANSPORTE</th>
							<th>DATOS CONDUCTORES</th>
							<th>Licencia de Conducir</th>
						</tr>
						<tr>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head">
					<tbody>
						<tr class="text-uppercase">
							<th width="900px">Producto</th>
							<th>Código</th>
							<th>Cant.</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr style="border-bottom: 1px solid #5baa01;border-top: 1px solid #5baa01;">
							<td colspan="5" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr style="border-bottom: 1px solid #5baa01;border-top: 1px solid #5baa01;">
							<td colspan="5" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr style="border-bottom: 1px solid #5baa01;border-top: 1px solid #5baa01;">
							<td colspan="5" class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
					</tbody>
				</table>
				<table  class="table table-no-border">
					<tbody>
						<tr>
							<td style="vertical-align: top;">
								'.$nota_doc.'
								<div class="single-footer-wight box-main">
									<img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-left mr-2 mb-3">
									<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
									<p><span class="text-theme-default font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
									<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
									<p class="mb-3">Representación Impresa del Documento Electrónico</p>
									'.$aviso_texto_pruebas.'
									'.$text_pdf_3.'
								</div>
							</td>
						
						</tr>
					</tbody>
				</table>
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;		
	}
	public function get_html_plantilla_a4_14($cpe) {  //ID: 53, TAMAÑO: A4, ==> GUIA REMISIÓN
		$this->view->disable();
		//Emisor 
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

		//Dirección Cliente
		$direccion_fiscal = '';
		if(!empty($cpe['cliente']['direccion_fiscal'])){
			$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$cpe['cliente']['direccion_fiscal'].'</p>';
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
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
		}
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td class="text-center">'.$item['unidad_medida'].'</td>
				<td class="text-center">'.$item['peso_det'].'</td>
			</tr>
			';
		}
		//Observación
		$nota_doc = '';
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold text-uppercase">Observación:</p> 
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
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
			<style>
			@page{
				margin: 100px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			.h5, h5 {
				font-size: 12px;
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
					height: 900px;
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
					height:900px;
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
				font-size: 13px;
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
			.table-retencion td, .table-directions td{
				text-align: center;
			}
		</style>
		<body>';
			
		if(empty($cpe['emisor']['logo_rectangular'])) {
				$height = "style='height: 160px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-3">
						<img src="'.$cpe['emisor']['logo_cuadrado'].'"  style="width: 100px; height: 100px">
					</div>
					<div class="col-5 text-center" style="padding:10px 5px">
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						'.$direccion_empresa.'
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$ubigeo_empresa.'</p>
						<p><i class="flaticon-call mr-2"></i>'.$cpe['emisor']['telefono'].'</p>
						<p><i class="flaticon-envelope mr-2"></i>'.$cpe['emisor']['email'].'<p>
						<p><i class="icon-sphere3 mr-2"></i>'.$cpe['emisor']['sitio_web'].'</p>
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
				$height = "style='height: 220px;'";
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
			'.$text_pdf_1.'
			<div class="box-content mb-2 mt-2">
			<table class="table table_datos">
					<td width="50%">
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						<p><span class="font-weight-bold">Fecha de Emisión: </span>'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<p><span class="text-theme-primary font-weight-bold">Forma de pago:</span> '.$cpe['cabecera']['forma_de_pago'].'</p>
						<p><span class="text-theme-primary font-weight-bold">Destinarario:</span> '.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</p>
					</td>
				
					<td width="50%">
						<p><span class="text-theme-color  font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
						<p><span class="text-theme-primary font-weight-bold">Uni. de transporte:</span> '.$cpe['cabecera']['transporte_nro_placa'].'</p>
						<p><span class="text-theme-primary font-weight-bold">DATOS CONDUCTORES:</span> '.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</p>';
						if($cpe['cabecera']['id_modalidadtraslado'] == '02') {
						$html = $html.'
						<p><span class="text-theme-primary font-weight-bold">Licencia:</span> '.$cpe['cabecera']['nro_licencia_conducir'].'</p>';
						}
						$html = $html.'
						<p><span class="text-theme-color font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
						<p><span class="text-theme-color font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
						'.$cpe['cabecera']['guia_remision'].'
						</td>
					</table>
			</div>
			<table class="table table-directions">
				<tbody>
					<tr class="text-uppercase">
						<th '.$with_direccion.'>Dirección de partida</th>
						<th '.$with_direccion.'>Dirección de llegada</th>
						'.$th_codigo_puerto.'
						'.$th_numero_contenedor.'
					</tr>
					<tr class="text-center">
						<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
						<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
						'.$codigo_puerto.'
						'.$numero_contenedor.'
					</tr>
				</tbody>
			</table>
			<section class="content_table_main">
				<table class="table table-main">
					<tbody>
						<tr class="text-uppercase">
							<th>Cant.</th>
							<th width="400px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="1" style="border: 1px solid #000!important" width="200px">
								<p style="font-size: 12px"><span class="font-weight-bold">Vendedor: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
							</td>
							<td colspan="1" style="border: 1px solid #000!important" class="text-center" width="100px">
								<span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'
							</td>
							
							<td colspan="1" style="border: 1px solid #000!important" width="200px" class="text-center"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						
							<td colspan="1" style="border: 1px solid #000!important;" class="text-center" width="200px">
								<span class="font-weight-bold text-center">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'
							</td>
						</tr>
						<tr>
							<td colspan="4" style="border: 1px solid #000!important">
								<div class="text-center">
									<img style="width: 70px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" class="mt-2 mb-2" />
									<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
								</div>
							</td>
						</tr>
						
					</tbody>
				</table>
			</section>
			
			'.$nota_doc.'
			'.$text_pdf_2.'
			<div class="mt-1">
				<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p class="mb-3">Representación Impresa del Documento Electrónico</p>
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
	public function get_html_plantilla_a4_15($cpe) {  //ID: 57, TAMAÑO: A4, ==> GUIA REMISIÓN
		$this->view->disable();
		
		//Emisor Datos
		$direccion_empresa = '';
		if(!empty($cpe['emisor']['direccion'])) {
			$direccion_empresa = '<p><span class="font-weight-bold text-uppercase">Dirección: </span>'.$cpe['emisor']['direccion'].'</p>';
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
	
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$codigo_puerto = '<p><span class="font-weight-bold text-uppercase">Código de puerto:</span> '.$cpe['cabecera']['id_codigopuerto'].'</p>';
		}
		

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$numero_contenedor = '<p><span class="font-weight-bold text-uppercase">N° de contenedor:</span> '.$cpe['cabecera']['numero_contenedor'].'</p>';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
			</tr>
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
			.table-date td, .table-main-head td{
				text-align: center;
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
				color: #0172ce;
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
				height: 120px;
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
						'.$direccion_empresa.' '.$ubigeo_empresa.'
						<p><span class="font-weight-bold text-uppercarse">Teléfono: </span>'.$cpe['emisor']['telefono'].'</p>
						<p><span class="font-weight-bold text-uppercarse">E-Mail: </span>'.$cpe['emisor']['email'].'<p>
						<p><span class="font-weight-bold text-uppercarse">Sitio Web: </span>'.$cpe['emisor']['sitio_web'].'</p>
						<p><span class="font-weight-bold text-uppercarse">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<p><span class="font-weight-bold text-uppercarse">Fecha de Traslado: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
						<hr style="margin:5px;">
						<p><span class="font-weight-bold text-uppercarse">Dirección de partida: </span> '.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</p>
						<p><span class="font-weight-bold text-uppercarse">Dirección de llegada: </span> '.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</p>
					</td>
					<td width="50%" valign="top">
						<p><span class="font-weight-bold text-uppercarse">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.ucwords($cpe['cliente']['nombre']).'</p>
						<p><span class="font-weight-bold text-uppercarse">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						<p><span class="font-weight-bold text-uppercarse">DOCS. REFERENCIA: </span> '.$cpe['cabecera']['guia_remision'].'</p>
						<p><span class="font-weight-bold text-uppercarse">MOTIVO TRASLADO: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
						<p><span class="font-weight-bold text-uppercarse">MOD. TRANSPORTE: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
						'.$codigo_puerto.'
						'.$numero_contenedor.'
					</td>
					
				</tr>
			</table>
			'.$text_pdf_1.'
			<table class="table table-date text-center mt-4">
			';
			if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
				//transporte público
				$html = $html.'
						<tbody>
							<tr class="bg-theme-primary text-white text-uppercase">
								<th>DESTINATARIO</th>
								<th>RUC TRANSPORTE</th>
								<th>RAZÓN SOCIAL TRANSP.</th>
							</tr>
							<tr>
								<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
							</tr>
						</tbody>
						';
			} else {
				//transporte privado
				$html = $html.'
				<tbody>
					<tr class="bg-theme-primary text-white text-uppercase">
						<th>DESTINATARIO</th>
						<th>UNIDAD DE TRANSPORTE</th>
						<th>DATOS CONDUCTORES</th>
						<th>Licencia de Conducir</th>
					</tr>
					<tr>
						<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
						<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
					</tr>
				</tbody>
				';
			}
			$html = $html.'
			</table>
			'.$text_pdf_2.'
			<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
			<section class="section-tables mt-1">
				<table class="table table-main-head">
					<tbody>
						<tr class="bg-theme-primary text-white text-uppercase">
							<th>Cant.</th>
							<th width="400px">Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="4" style="text-align: left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr>
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align: left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
						'.$text_pdf_2.'
					</tbody>
				</table>
			</section>
			<footer class="mt-1">
				<div class="col-12">
					'.$nota_doc.'
					<div class="text-left">
						<img style="width: 40px;" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" class="mb-2 mr-2 float-left" />
						<p class="mb-1"><span class="font-weight-bold">Vendedor: </span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
						<p><span class="font-weight-bold">HASH: </span>  '.$cpe['cabecera']['hash'].'</p>
					</div>
					
				</div>
			
			</footer>
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	
	public function get_html_plantilla_a4_17($cpe) { //ID: 75, TAMAÑO: A4, ==> GUIA REMISIÓN
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
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
			<title>'.$cpe['cabecera']['correlativo'].'</title>
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
				.head-pg::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/pg_anulado.jpg);
					background-repeat: no-repeat;
					backgroud-position: center right;
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
										<p class="pl-2">'.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
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
		
			<table class="table table-items-productos table-4 bordered mb-4 mt-4">
			<tbody>
				<tr class="text-uppercase bg-main bordered">
					<th>FECHA DE EMISIÓN</th>
					<th>FECHA DE TRASLADO</th>
					<th>DOCS. REFERENCIA</th>
					<th>MOTIVO TRASLADO</th>
					<th>MOD. TRANSPORTE</th>
				</tr>
				<tr>
					<td>'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</td>
					<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</td>
					<td>'.$cpe['cabecera']['guia_remision'].'</td>
					<td>'.$cpe['cabecera']['motivo_traslado'].'</td>
					<td>'.$cpe['cabecera']['modalidad_traslado'].'</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-items-productos table-4 bordered">
			<tbody>
			<tr class="text-uppercase bg-main bordered">
					<th '.$with_direccion.'>Dirección de partida</th>
					<th '.$with_direccion.'>Dirección de llegada</th>
					'.$th_codigo_puerto.' 
					'.$th_numero_contenedor.'
				</tr>
				<tr class="text-center">
					<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
					<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
					'.$codigo_puerto.' 
					'.$numero_contenedor.'
				</tr>
			</tbody>
		</table>
		<table class="table table-items-productos table-4 bordered text-center mt-4">
		';
		if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
			//transporte público
			$html = $html.'
					<tbody>
					<tr class="text-uppercase bg-main bordered">
							<th width="50%">RUC TRANSPORTE</th>
							<th width="50%">RAZÓN SOCIAL TRANSP.</th>
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
						</tr>
					</tbody>
					';
		} else {
			//transporte privado
			$html = $html.'
			<tbody>
				<tr class="text-uppercase bg-main bordered">
					<th width="150px">DESTINATARIO</th>
					<th>UNIDAD DE TRANSPORTE</th>
					<th>DATOS CONDUCTORES</th>
					<th>Licencia de Conducir</th>
				</tr>
				<tr>
					<td>'.ucwords($cpe['cliente']['nombre']).' <br> Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
					<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
					<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
					<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
				</tr>
			</tbody>
			';
		}
		$html = $html.'
		</table>
		<table class="table-items-productos table-4 bordered mt-3">
			<tbody>
				<tr class="text-uppercase bg-main tr_principal_productos">
					<th>Cant.</th>
					<th width="400px">Producto</th>
					<th>UNID/MED </th>
					<th>Peso</th>
				</tr>
				<tr>
					'.$items_detalle_html.'
				</tr>
				<tr>
					<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
				</tr>
				<tr>
					<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
				</tr>
				<tr>
					<td colspan="4"  class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
				</tr>
			</tbody>
		</table>
		'.$text_pdf_2.'
		<div class="pt-3 pr-3 notas_doc">
			<p class="font-weight-bold">Condiciones de ventas:</p>
			<ol style="list-style-type: decimal-leading-zero; padding-left: 2em;">
				<li>Condición</li>
				<li>Puesto en obra previa coordinación</li>
				<li>Los precios están sujetos a variación de costos de refineria.</li>
				<li>Facturación electrónica</li>
				<li>Garantizamos nuestros productos</li>
			</ul>
		</div>
		
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
							<td rowspan="2" class="font-weight-bold"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/interbank.png" width="120px"></td>
							<td class="color-secundary">Cuenta SoleS: </td>
							<td class="color-secundary">6003002124936</td>
						</tr>
						<tr>
							<td class="color-secundary">Cuenta CCI: </td>
							<td class="color-secundary">00360000300212494345</td>
						</tr>
						<tr>
							<td rowspan="2" class="font-weight-bold"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banco-de-la-nacion.jpg" width="120px"></td>
							
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

		</div>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_plantilla_a4_16($cpe) { //ID: 99, TAMAÑO: A4, ==> GUIA REMISIÓN
		
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
		//codigo de puerto
		$th_codigo_puerto = '';
		$codigo_puerto = '';
		
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$th_codigo_puerto = '<th>Código de puerto</th>';
			$codigo_puerto = '<td>'.$cpe['cabecera']['id_codigopuerto'].'</td>';
		}
		//Numero_contenedor
		$th_numero_contenedor = '';
		$numero_contenedor = '';

		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$th_numero_contenedor = '<th>Número de contenedor</th>';
			$numero_contenedor = '<td>'.$cpe['cabecera']['numero_contenedor'].'</td>';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
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
			$text_pdf_1 = '<p>'.$cpe['sucursal']['txt_pdf_a4_1'].'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_2'])) {
			$text_pdf_2 = '<p>'.$cpe['sucursal']['txt_pdf_a4_2'].'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($cpe['sucursal']['txt_pdf_a4_3'])) {
			$text_pdf_3 = '<p>'.$cpe['sucursal']['txt_pdf_a4_3'].'</p>';
		} else {
			$text_pdf_3 = '';
		}
		
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) { 
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td class="text-center">'.$item['nro_item'].'</td>
				<td class="text-center">'.$item['codigo'].'</td>
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td></td>
				<td>'.$item['peso_det'].'</td>
			</tr>
			';
		}
		
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="mb-2">'.$cpe['cabecera']['documento_nota'].'</p>
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
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
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
			table td{
				padding: .30rem;
			}
			.table td, .table th {
				padding: .30rem;
				vertical-align: top;
				border-top: 1px solid #5f5f5f;
			}

			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			table {
				font-size: 10px!important;				
				margin-bottom: 5px!important;
				color: #212529;
			}
			
			.table-main th{
				text-transform: uppercase;
				border: 0;
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
				padding-top: .5em;
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
				padding: 10px 5px;
				text-align: center;
				text-transform: uppercase;
				border: 1px solid #5f5f5f;
				border-radius:10px;
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
			.table-content-row {
				width: 100%;
			}
			.table-content-row td{
				border: 0;
				padding: 5px;
				padding-left: 0;
			}
			.table-border-solid td{
				border: 1px solid #5f5f5f!important;
				padding: 5px
			}
			.table-data-doc{
				width: 100%;
				border: 1px solid #5f5f5f;
			}
			.table-data-doc td{
				text-align: left;
				padding: 5px;
				border: 0;
			}
		</style>
		<body>
			<div class="masthead w-100">
				<table class="table-content-row">
					<td>
						<img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
					</td>
					<td>
						<h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$cpe['emisor']['direccion'].'</p>
						<p>'.$cpe['emisor']['ubigeo'].'</p>
						<p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
						<p>'.$cpe['emisor']['sitio_web'].'</p>
					</td>
					<td>
						<div class="table-head mt-2">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;">GUÍA REMISIÓN - REMITENTE</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</td>
				</table>
			</div>
			<table>
				<tbody>
					<tr class="text-uppercase">
						<th>Fecha</th>
						<th>Fecha Ini. Tras</th>
					</tr>
					<tr>
						<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_emision'])).'</td>
						<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</td>
					</tr>
				</tbody>
			</table>
			<table class="table-data-doc">
				<tr>
					<td style="border-bottom:0"><span class="font-weight-bold">Destinatario:</span> '.$cpe['cliente']['nombre'].'</td>
				</tr>	
				<tr>
					<td><span class="font-weight-bold">RUC N°:</span> '.$cpe['cliente']['doc_identificacion_numero'].'</td>
				</tr>
			</table>
			<table class="table table-content-row">
				<tbody>
					<tr class="text-center">
						<td width="50%  pl-0">
							<table class="table table-border-solid">
								<tbody>
									<tr class="text-uppercase">
										<th>Dirección de partida</th>
									</tr>
									<tr>
										<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
									</tr>
								</tbody>
							</table>
							<table class="table table-border-solid">
								<tbody>
									<tr class="text-uppercase">
										<th width="50%">Doc. de Referencia</th>
										<th width="50%">No. Comprobante</th>
									</tr>
									<tr>
										<td>'.$cpe['cabecera']['guia_remision'].'</td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td width="50%" style="padding-right:0">
							<table class="table table-border-solid">
								<tbody>
									<tr class="text-uppercase">
										<th>Dirección de llegada</th>
									</tr>
									<tr>
										<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
									</tr>
								</tbody>
							</table>
							<table class="table table-border-solid">
								<tbody>
									<tr class="text-uppercase">
									<th width="50%">No. Pedido</th>
									<th width="50%">Motivo de traslado</th>
										
									</tr>
									<tr>
										<td></td>
										<td>'.$cpe['cabecera']['motivo_traslado'].'</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase" style="border: 1px solid #5f5f5f">
						<th>Item.</th>
						<th>Código</th>
						<th>Cant.</th>
						<th>Unidad</th>
						<th width="350px" class="text-left">Descripción</th>
						<th>Marca</th>
						<th>Peso Aprox.</th>
					</tr>
					'.$items_detalle_html.'
					<tr>
						<td colspan="7" class="text-right" style="border: 1px solid #5f5f5f!important"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
					</tr>
					
				</tbody>
			</table>
			<table class="table">
				<tbody>
				<tr>
					<th width="50%">Observaciones</th>
					<th width="50%">Referencias</th>
				</tr>
					<tr>
						<td>
						'.$nota_doc.'
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<p class="font-weight-bold text-uppercase">MODALIDAD DE TRANSPORTE: '.$cpe['cabecera']['modalidad_traslado'].'</p>
			<table class="table table-content-row">
				<tbody>
					<tr class="text-left">
						<td width="50%  pl-0">
							<table class="table table-data-doc">
								<tbody>
									<tr class="text-uppercase">
										<th colspan="2">Empresa de transporte y conductor</th>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Transportista:</span>  </td>
										<td>'.$cpe['cliente']['nombre'].'</td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Ruc N°:</span> </td>
										<td>'.$cpe['cliente']['doc_identificacion_numero'].'</td>
									</tr>
									<tr style="border-top:1px solid #5f5f5f">
										<td><span class="font-weight-bold">Conductor:</span> </td>
										<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">DNI:</span> </td>
										<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">N° Brevete:</span> </td>
										<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
									</tr>
								</tbody>
							</table>
							<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" class="mb-2"/>
							<p>Representación gráfica de la GRE</p>
						</td>
						<td width="50%" style="padding-right:0">
							<table class="table table-data-doc">
								<tbody>
									<tr class="text-uppercase">
										<th colspan="2">Unidad de transporte  <span class="text-right">Ranfla/Carreta</span></th>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Placa:</span> </td>
										<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Marca:</span> </td>
										<td></td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">C. Inscrip:</span> </td>
										<td></td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Config. Vehic:</span> </td>
										<td></td>
									</tr>
								</tbody>
							</table>
							<table class="table table-data-doc">
								<tbody>
									<tr class="text-uppercase">
										<th colspan="2" class="text-center">Conformidad</th>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Firma:</span> </td>
										<td></td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">Nombre:</span> </td>
										<td></td>
									</tr>
									<tr>
										<td><span class="font-weight-bold">DNI:</span> </td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			
		</body>
		</html>
		';
		
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		
		return $resp;
	}
	public function get_html_personalizada_a4_72($cpe) { //ID: 72, TAMAÑO: A4, ==> GUIA REMISIÓN
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
				<td class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  class="text-left">'.$item['descripcion'].'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
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
			<title>'.$cpe['cabecera']['correlativo'].'</title>
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
				.head-pg::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/pg_anulado.jpg);
					background-repeat: no-repeat;
					backgroud-position: center right;
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
					<img src="https://arpsystem.com.pe/facturacionv8/img/plantilas_personalizadas/02/logo_personalizado_02.png"  style="width: 180px;padding-top:10px">
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
										<p class="pl-2">'.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
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
		
			<table class="table table-items-productos table-4 bordered mb-4 mt-4">
			<tbody>
				<tr class="text-uppercase bg-main bordered">
					<th>FECHA DE EMISIÓN</th>
					<th>FECHA DE TRASLADO</th>
					<th>DOCS. REFERENCIA</th>
					<th>MOTIVO TRASLADO</th>
					<th>MOD. TRANSPORTE</th>
				</tr>
				<tr>
					<td>'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</td>
					<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</td>
					<td>'.$cpe['cabecera']['guia_remision'].'</td>
					<td>'.$cpe['cabecera']['motivo_traslado'].'</td>
					<td>'.$cpe['cabecera']['modalidad_traslado'].'</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-items-productos table-4 bordered">
			<tbody>
			<tr class="text-uppercase bg-main bordered">
					<th '.$with_direccion.'>Dirección de partida</th>
					<th '.$with_direccion.'>Dirección de llegada</th>
				</tr>
				<tr class="text-center">
					<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
					<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-items-productos table-4 bordered text-center mt-4">
		';
		if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
			//transporte público
			$html = $html.'
					<tbody>
					<tr class="text-uppercase bg-main bordered">
							<th width="50%">RUC TRANSPORTE</th>
							<th width="50%">RAZÓN SOCIAL TRANSP.</th>
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
						</tr>
					</tbody>
					';
		} else {
			//transporte privado
			$html = $html.'
			<tbody>
				<tr class="text-uppercase bg-main bordered">
					<th width="50%">UNIDAD DE TRANSPORTE</th>
					<th width="50%">DATOS CONDUCTORES</th>
				</tr>
				<tr>
					<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
					<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
				</tr>
			</tbody>
			';
		}
		$html = $html.'
		</table>
		<table class="table-items-productos table-4 bordered mt-3">
			<tbody>
				<tr class="text-uppercase bg-main tr_principal_productos">
					<th>Cant.</th>
					<th width="400px">Producto</th>
					<th>UNID/MED </th>
					<th>Peso</th>
				</tr>
				<tr>
					'.$items_detalle_html.'
				</tr>
				<tr>
					<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
				</tr>
				<tr>
					<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
				</tr>
				<tr>
					<td colspan="4"  class="text-left"><span class="font-weight-bold">Numero de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
				</tr>
			</tbody>
		</table>
		'.$text_pdf_2.'
		<div class="pt-3 pr-3 notas_doc">
			<p class="font-weight-bold">Condiciones de ventas:</p>
			<ol style="list-style-type: decimal-leading-zero; padding-left: 2em;">
				<li>Condición:</li>
				<li>Puesto en obra previa coordinación</li>
				<li>Los precios están sujetos a variación de costos de refineria.</li>
				<li>Facturación electrónica</li>
				<li>Garantizamos nuestros productos</li>
			</ul>
		</div>
		
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
							<td rowspan="2" class="font-weight-bold"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/interbank.png" width="120px"></td>
							<td class="color-secundary">Cuenta SoleS: </td>
							<td class="color-secundary">6003002124936</td>
						</tr>
						<tr>
							<td class="color-secundary">Cuenta CCI: </td>
							<td class="color-secundary">00360000300212494345</td>
						</tr>
						<tr>
							<td rowspan="2" class="font-weight-bold"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banco-de-la-nacion.jpg" width="120px"></td>
							
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

		</div>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;		
	}
	public function get_html_personalizada_a4_80($cpe) { //ID: 80, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		$with_direccion = '';
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
		$orden_compra = '';
		if(!empty($cpe['cabecera']['orden_compra'])){
			$orden_compra =  '<p><span class="font-weight-bold">Contacto:</span> '.$cpe['cabecera']['orden_compra'].'</p>';
		
		}
		$num_placa = '';
		if(!empty($cpe['cabecera']['nro_placa'])){
			$num_placa =  '<p><span class="font-weight-bold">N° de placa:</span> '.$cpe['cabecera']['nro_placa'].'</p>';
		
		}
		
					
		//Detalles de producto
		$items_detalle_html = '';
		$total_cant=0;
		foreach($cpe['detalle'] as $item) {
			$total_cant+=$item['cantidad'];
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td>'.($item['codigo']).'</td>
				<td>'.$item['unidad_medida'].'</td>
				<td class="text-left td_descripcion">'.$item['descripcion'].'</td>
				<td class="text-right"><div style="width: 45px !important;">'.custom_money_format($item['cantidad'] + 0).'</div></td>
			</tr>
			';
		}
	

		$resumen = '
		<tr>
			<td>Total cantidad:</td>
			<td class="text-right border-resumen">
				<div style="width: 45px !important;">'.custom_money_format($total_cant + 0).'</div>
			</td>
		</tr>
		';
		
		

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
			<div class="content-cuentas mt-4 mb-4">
				<p class="font-weight-bold text-uppercase text-center">Detalle de la Forma de Pago: Crédito </p>
				<table class="pl-2" style="margin:auto">
					<tbody>
						<tr class="bg-table text-uppercase">
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
		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p>Observación: '.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
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
				margin-top: 2em;
			}
			.table-main th{
				text-align: center;
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
						<div class="table-head margin-top-head">
							<p>R.U.C. '.$cpe['emisor']['ruc'].'</p>                    
							<p style="font-weight: 600;background: #cccccc;">'.$cpe['cabecera']['nombre_cpe'].'</p>
							<p>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			}
			$html = $html.'
			<div class="text-center">'.$cpe['sucursal']['txt_pdf_a4_1'].'</div>
			
			<table class="table text-uppercase">
				<tr>
					<td>
						<p><span class="font-weight-bold">Fecha de emisión:</span> '.$cpe['cabecera']['fecha_emision'].'</p>
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_titulo'].':</span> '.$cpe['cliente']['nombre'].'</p>
						<p><span class="font-weight-bold">'.$cpe['cliente']['doc_identificacion_siglas'].':</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
						'.$direccion_fiscal.'
						'.$orden_compra.'
					</td>
				</tr>
			</table>
		
			<table class="table">
				<tbody>
					<tr class="text-uppercase bg-table">
						<th '.$with_direccion.'>Dirección de partida</th>
						<th '.$with_direccion.'>Dirección de llegada</th>
					</tr>
					<tr class="text-center">
						<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
						<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
					</tr>
				</tbody>
			</table>
			<table class="table text-center">
			';
			if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
				//transporte público
				$html = $html.'
						<tbody>
							<tr class="text-uppercase bg-table">
								<th>DESTINATARIO</th>
								<th>RUC TRANSPORTE</th>
								<th>RAZÓN SOCIAL TRANSP.</th>
							</tr>
							<tr>
								<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
							</tr>
						</tbody>
						';
			} else {
				//transporte privado
				$html = $html.'
				<tbody>
					<tr class="text-uppercase bg-table">
						<th>DATOS DEL TRANSPORTISTA</th>
						<th>Licencia de Conducir</th>
						<th>PLACA</th>
						
					</tr>
					<tr>
						<td class="text-center">'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						<td class="text-center">'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						<td class="text-center">'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						
					</tr>
				</tbody>
				';
			}
			$html = $html.'
			</table>
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th>Código</th>
						<th>U.M </th>
						<th>Descripción</th>
						<th>Cant.</th>
						
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr style="border-top: 1px solid #5f5f5f;">
						<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
					</tr>
						
				</tbody>
			</table>
			<footer class="mb-1">
				<table class="table no-border nota_doc">
					<tbody>
						<tr>
							<td>
								<table style="width: 34%;float:right" class="tb_resumen_totales">
									<tbody>
										'.$resumen.'
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				
			</footer>
			<table class="table text-uppercase">
				<tr>
					<td width="50%">
				
						<p>Motivo de traslado: '.$cpe['cabecera']['motivo_traslado'].'</p>
						<p>Peso Bruto: '.$cpe['cabecera']['peso_total_tne'].'</p>
						<p>Bultos: '.$cpe['cabecera']['numero_paquetes'].'</p>
						<p>Modalidad de transporte: '.$cpe['cabecera']['modalidad_traslado'].'</p>
					</td>
					<td  width="50%">
					<p>N° de Factura: '.$cpe['cabecera']['guia_remision'].'</p>
					<p>Orden de compra: '.$cpe['cabecera']['orden_compra'].'</p>
					<p>Usuario:  '.$cpe['vendedor']['apellido'].' </p>
					'.$nota_doc.'
					</td>
				</tr>
			</table>
			'.$html_lista_cuotas.'
			
			<div class="w-100">
				<table class="table table-footer  p-0">
					<tbody class="text-center">
						<tr>
							<td style="border-right: 0;" class="codigo_qr" vertical-align: top;> 
								<p>Autorizado mediante la resolución Nº 0640050002737/Sunat</p>
								<p>Representación impresa de la '.$cpe['cabecera']['nombre_cpe'].'</p>
								<p class="mb-1">Para consultar el comprobante visita: '.$cpe['patrocinador']['url_consulta_cpe'].'
								
							</td>
							<td style="border-left: 0" vertical-align: top;><img src="'.$cpe['cabecera']['ruta_imagen_qr'].'" width="60px" class="float-right"></td>
						</tr>	
					</tbody>
				</table>	
			'.$aviso_texto_pruebas.'

			<div class="text-center mt-2 mb-5">'.$cpe['sucursal']['txt_pdf_a4_3'].'</div>
			
		</body>
			
		';
	
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;			
	}
	public function get_html_plantilla_ticket_1($cpe) { //ID: 33, TAMAÑO: TICKET, ==> GUIA REMISIÓN
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

		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		//codigo de puerto

		$codigo_puerto = '';
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$codigo_puerto = '<p><span  class="font-weight-bold">Código de puerto:</span> '.$cpe['cabecera']['id_codigopuerto'].'</p>';
		}
		//Numero_contenedor
		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$numero_contenedor = '<p><span  class="font-weight-bold">N° de contenedor:</span> '.$cpe['cabecera']['numero_contenedor'].'</p>';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td  style="font-size: 12px">'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
			</tr>
			';
		}

		$with_direccion = '';
		if(!empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = '';
		}else if(empty($cpe['cabecera']['id_codigopuerto']) && !empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else if(!empty($cpe['cabecera']['id_codigopuerto']) && empty($cpe['cabecera']['numero_contenedor'])) {
			$with_direccion = 'width="33%"';
		}else{
			$with_direccion = 'width="50%"';
		}

		$codigo_puerto = '';
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$codigo_puerto = '<p><span  class="font-weight-bold">Código de puerto:</span> '.$cpe['cabecera']['id_codigopuerto'].'</p>';
		}

		$numero_contenedor = '';
		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$numero_contenedor = '<p><span  class="font-weight-bold">N° de contenedor:</span> '.$cpe['cabecera']['numero_contenedor'].'</p>';
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
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				color: #000;
				font-size: 11px;
				line-height: 16px;

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
			.table-2 td {
				border-top: 1px dashed #000;
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
			<div class="text-left">
				<p class="font-weight-bold text-uppercase" >Guía de remisión<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
				<p>Fecha de Emisión: '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				<p>Señor (es): '.ucwords($cpe['cliente']['nombre']).'</p>
				<p>RUC:'.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$text_pdf_1.'
			</div>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			<div class="text-left">
				<p><span class="font-weight-bold">Fecha de Emisión: </span>'.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				<p><span class="font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
				<p><span class="font-weight-bold">Motivo de traslado: </span>'.$cpe['cabecera']['motivo_traslado'].'</p>
				<p><span class="font-weight-bold">Docs. Referencia: </span>'.$cpe['cabecera']['guia_remision'].'</p>
				<p><span class="font-weight-bold">Mod. Transporte: </span>'.$cpe['cabecera']['modalidad_traslado'].'</p>
				'.$codigo_puerto.'
				'.$numero_contenedor.'
			</div>
			<div class="table-content mt-2">
				<table class="table-main-head">
					<tbody>
						<tr>
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
						</tr>
					</tbody>
				</table>
				<table class="table-2 text-center mt-1">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr>
									<td>Destinatario</td>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								</tr>
								<tr>
									<td>Ruc transporte</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								</tr>
								<tr>
									<td>Razón social transp</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr>
							<td class="font-weight-bold">Destinatario</td>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Unid. de transporte</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Datos conductores</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Licencia de conducir</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				
				<p class="mt-4 font-weight-bold" style="font-size: 11px">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="mb-4">
							<th>Cant.</th>
							<th>Producto</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-bottom: 1px dashed #000;border-top: 1px dashed #000;">
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr style="border-bottom: 1px dashed #000;border-top: 1px dashed #000;">
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4"  class="text-left"><span class="font-weight-bold">Número de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<footer class="mt-2">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
				'.$text_pdf_2.'
				<div class="text-center texto_10 mt-2 codigo_qr_ticket">
					<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2"/>
					'.$nota_doc.'
					<p class="mt-1">Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
					Consulte su Documento en:</p>';

					$html = $html.'
					<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
					<p>HASH:  '.$cpe['cabecera']['hash'].'</p>
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
	public function get_html_plantilla_ticket_2($cpe) { //ID: 34, TAMAÑO: TICKET, ==> GUIA REMISIÓN
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

		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$codigo_puerto = '<p><span  class="font-weight-bold">Código de puerto:</span> '.$cpe['cabecera']['id_codigopuerto'].'</p>';
		}
		//Numero_contenedor
		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$numero_contenedor = '<p><span  class="font-weight-bold">N° de contenedor:</span> '.$cpe['cabecera']['numero_contenedor'].'</p>';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td  style="font-size: 12px">'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
			</tr>
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
		<style>
			@page { margin: 20px !important; }
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 11px;
				font-style: normal;
				margin-left: 20px;
			}
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
			}
			/* clases de bootstrap */
			p {
				margin:   3px 0!important;
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
			hr {
				margin-top: 0;
				margin-bottom: 0;
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
				padding: 4px;
			}
			.border-table{
				border-bottom: 1px solid #000;
			}
			.padding-right-4{
				padding-right: 1em;
			}

			.table-2 td{
				border-bottom: 1px solid #000;
				border-top: 1px solid #000;
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
				<hr  class="mt-2" style="border-top: 1px solid #000;">
				<p class="font-weight-bold text-uppercase mt-1">'.$cpe['cabecera']['nombre_cpe'].'</p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
				<hr style="border-bottom: 1px solid #000;">
			</div>
			<div class="text-left mt-1">
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				<p><span class="font-weight-bold">Razón Social: </span>'.ucwords($cpe['cliente']['nombre']).'</p>
				<p><span class="font-weight-bold">RUC:</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$text_pdf_1.'
			</div>
			<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff; height: 2px;" />
			<div class="text-left">
				<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				<p><span class="text-theme-default font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
				<p><span class="text-theme-default font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
				<p><span class="text-theme-default font-weight-bold">Docs. Referencia: </span> '.$cpe['cabecera']['guia_remision'].'</p>
				<p><span class="text-theme-default font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
				'.$codigo_puerto.'
				'.$numero_contenedor.'
			</div>
			<div class="table-content mt-2">
				<table class="table-main-head">
					<tbody>
						<tr>
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
						</tr>
					</tbody>
				</table>
				<table class="table-2 text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr>
									<td class="font-weight-bold">Destinatario</td>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Ruc transporte</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Razón social transp</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>
							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr>
							<td class="font-weight-bold">Destinatario</td>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Unid. de transporte</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Datos conductores</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Licencia de conducir</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
				
			<p class="mt-4 font-weight-bold" style="font-size: 11px">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
			<div class="table-content mt-3">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold mb-4">
							<th>Producto</th>
							<th>Cant.</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-bottom: 1px solid #000;border-top: 1px solid #000;">
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr style="border-bottom: 1px solid #000;border-top: 1px dashed #000;">
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4"  class="text-left"><span class="font-weight-bold">Número de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
					</tbody>
				</table>
			</div>
			<footer class="mt-2 text-center">
				'.$text_pdf_2.'
				<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-3"/>
				<p>'.$nota_doc.'</p>
				<p>Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
				Consulte su Documento en:</p>
				<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p><span class="font-weight-bold">HASH:</span> '.$cpe['cabecera']['hash'].'</p>
				<p><span class="font-weight-bold">VENDEDOR:</span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>

				'.$text_pdf_3.'
				<p class="font-weight-bold mb-4 text-uppercase">Gracias por su preferecia</p>

				'.$aviso_texto_pruebas.'
				
			</footer>

		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	public function get_html_plantilla_ticket_3($cpe) { //ID: 35, TAMAÑO: TICKET, ==> GUIA REMISIÓN
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

		//Observación
		if(!empty($cpe['cabecera']['documento_nota'])) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$cpe['cabecera']['documento_nota'].'</p>
			';
		} else {
			$nota_doc = '';
		}
		if(!empty($cpe['cabecera']['id_codigopuerto'])) {
			$codigo_puerto = '<p><span  class="font-weight-bold">Código de puerto:</span> '.$cpe['cabecera']['id_codigopuerto'].'</p>';
		}
		//Numero_contenedor
		if(!empty($cpe['cabecera']['numero_contenedor'])) {
			$numero_contenedor = '<p><span  class="font-weight-bold">N° de contenedor:</span> '.$cpe['cabecera']['numero_contenedor'].'</p>';
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
		$items_detalle_html = '';
		foreach($cpe['detalle'] as $item) {
			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td  class="text-center">'.($item['cantidad'] + 0).'</td>
				<td  style="text-align: left !important;">'.$item['descripcion'].'</td>
				<td  style="font-size: 12px">'.$item['unidad_medida'].'</td>
				<td>'.$item['peso_det'].'</td>
			</tr>
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
		<style>
			@page { margin: 20px !important; }
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 11px;
				font-style: normal;
				margin-left: 20px;
			}
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
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

			.table-2 td{
				border-bottom: 1px solid #000;
				border-top: 1px solid #000;
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
				<p class="font-weight-bold  mb-2">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
			</div>
			<div class="main-cabecera">
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				<p><span class="font-weight-bold">Razón Social: </span>'.ucwords($cpe['cliente']['nombre']).'</p>
				<p><span class="font-weight-bold">RUC:</span> '.$cpe['cliente']['doc_identificacion_numero'].'</p>
				'.$text_pdf_1.'
			</div>
			<hr style="border-top: 1px solid #000; color: #fff; background-color: #fff; height: 2px;margin: 2px 0" />
			<div class="date-box">
				<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($cpe['cabecera']['fecha_emision'])).'</p>
				<p><span class="text-theme-default font-weight-bold">Fecha de traslado: </span>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
				<p><span class="text-theme-default font-weight-bold">Motivo de traslado: </span> '.$cpe['cabecera']['motivo_traslado'].'</p>
				<p><span class="text-theme-default font-weight-bold">Docs. Referencia: </span> '.$cpe['cabecera']['guia_remision'].'</p>
				<p><span class="text-theme-default font-weight-bold">Mod. Transporte: </span> '.$cpe['cabecera']['modalidad_traslado'].'</p>
				'.$codigo_puerto.'
				'.$numero_contenedor.'
			</div>
			<div class="table-content mt-2">
				<table class="table-main-head">
					<tbody>
						<tr>
							<th '.$with_direccion.'>Dirección de partida</th>
							<th '.$with_direccion.'>Dirección de llegada</th>
						</tr>
						<tr>
							<td>'.$cpe['cabecera']['direccion_partida'].'<br />'.$cpe['cabecera']['ubigeo_partida'].'</td>
							<td>'.$cpe['cabecera']['direccion_destino'].'<br />'.$cpe['cabecera']['ubigeo_destino'].'</td>
						</tr>
					</tbody>
				</table>
				<table class="table-2 text-center mt-4">
				';
				if($cpe['cabecera']['id_modalidadtraslado'] == '01') {
					//transporte público
					$html = $html.'
							<tbody>
								<tr>
									<td class="font-weight-bold">Destinatario</td>
									<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Ruc transporte</td>
									<td>'.$cpe['cabecera']['nro_documento_transporte'].'</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Razón social transp</td>
									<td>'.$cpe['cabecera']['razon_social_transporte'].'</td>
								</tr>

							</tbody>
							';
				} else {
					//transporte privado
					$html = $html.'
					<tbody>
						<tr>
							<td class="font-weight-bold">Destinatario</td>
							<td>'.ucwords($cpe['cliente']['nombre']).' - Num.Doc.: '.$cpe['cliente']['doc_identificacion_numero'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Unid. de transporte</td>
							<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Datos conductores</td>
							<td>'.$cpe['cabecera']['razon_social_transporte'].' DNI: '.$cpe['cabecera']['nro_documento_transporte'].'</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Licencia de conducir</td>
							<td>'.$cpe['cabecera']['nro_licencia_conducir'].'</td>
						</tr>
					</tbody>
					';
				}
				$html = $html.'
				</table>
			'.$text_pdf_2.'
			<p class="mt-2 mb-2 font-weight-bold" style="font-size: 11px">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
			<div class="table-content mt-3">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold mb-4">
							<th>Código</th>
							<th>Cant</th>
							<th>UNID/MED </th>
							<th>Peso</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-bottom: 1px solid #000;border-top: 1px solid #000;">
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
						</tr>
						<tr style="border-bottom: 1px solid #000;border-top: 1px dashed #000;">
							<td colspan="4" class="text-left"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
						</tr>
						<tr>
							<td colspan="4"  class="text-left"><span class="font-weight-bold">Número de Bulltos o Pallets:</span> '.$cpe['cabecera']['numero_paquetes'].'</td>
						</tr>
					</tbody>
				</table>
			</div>
			<footer class="mt-2 text-center">
				<img width="80px" src="'.$cpe['cabecera']['ruta_imagen_qr'].'" style="display: block;margin: auto" class="mb-2"/>
				<p>'.$nota_doc.'</p>
				<p>Representación Impresa de la '.$cpe['cabecera']['nombre_cpe'].' <br />
				Consulte su Documento en:</p>
				<p>'.$cpe['patrocinador']['url_consulta_cpe'].'</p>
				<p><span class="font-weight-bold">HASH:</span> '.$cpe['cabecera']['hash'].'</p>
				<p><span class="font-weight-bold">VENDEDOR:</span> '.$cpe['vendedor']['nombre'].' '.$cpe['vendedor']['apellido'].' (cod: '.$cpe['vendedor']['idusuario'].')</p>
				'.$text_pdf_3.'
				<p class="font-weight-bold mb-4 text-uppercase">Gracias por su preferecia</p>
				'.$aviso_texto_pruebas.'
			
			</footer>
			
		
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
}
?>