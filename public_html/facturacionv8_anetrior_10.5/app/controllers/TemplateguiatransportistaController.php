<?php
class TemplateguiatransportistaController extends ControllerBase
{
    public function get_html_personalizada_a4_84($cpe) { //ID: 84, TAMAÑO: A4, ==> GUÍA TRANSPORTISTA
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
				<td class="text-center">'.$item['ITEM'].'</td>
				<td class="text-center">'.$item['CODIGO'].'</td>
				<td class="text-center">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td  class="text-left">'.$item['DESCRIPCION'].'</td>
				<td>'.$item['UNIDAD_MEDIDA'].'</td>
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
			<title>'.$cpe['cabecera']['serie_comprobante'].' - '.$cpe['cabecera']['correlativo'].'</title>
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
							<p>'.$cpe['cabecera']['serie_comprobante'].' - '.$cpe['cabecera']['correlativo'].'</p>
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
							<p>'.$cpe['cabecera']['serie_comprobante'].' - '.$cpe['cabecera']['correlativo'].'</p>
						</div>
					</div>
				</div>';
			}

			$html = $html.'
			<table class="table text-center mt-4">
				<tbody>
					<tr class="text-uppercase">
						<th>Fecha Emisión </th>
						<th>DESTINATARIO</th>
						<th>Remitente</th>
					</tr>
					<tr>
						<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_comprobante'])).'</td>
						<td>'.ucwords($cpe['destinatario']['nombre_completo']).' <br> Num.Doc.: '.$cpe['destinatario']['numero_documento'].'</td>
						<td>'.$cpe['remitente']['nombre_completo'].'<br /> R.U.C.: '.$cpe['remitente']['numero_documento'].'</td>
						
					</tr>
				</tbody>
			</table>
			<table class="table">
				<tbody>
					<tr class="text-uppercase">
						<th>N° de registro MTC</th>
						<th>TUC</th>
					</tr>
					<tr>
						<td width="50%">'.$cpe['cabecera']['num_registro_mtc'].'</td>
						<td  width="50%">'.$cpe['cabecera']['tuc_vehiculo_principal'].'</td>
					</tr>
				</tbody>
			</table>
			<table class="table">
				<tbody>
					<tr class="text-uppercase">
						<th>Fecha Traslado</th>
						<th>Conductor</th>
						<th>UNID de transporte</th>
					</tr>
					<tr>
						<td>'.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</td>
						<td>'.$cpe['conductor']['nombre_completo'].' <br> Num. de Licencia.: '.$cpe['conductor']['nro_licencia'].'</td>
						<td>'.$cpe['cabecera']['transporte_nro_placa'].'</td>
					</tr>
				</tbody>
			</table>
			<table class="table">
				<tbody>
					<tr class="text-uppercase">
						<th width="50%">Punto de partida</th>
						<th width="50%">Punto de llegada</th>
					</tr>
					<tr class="text-center">
						<td width="50%">'.$cpe['cabecera']['dir_partida'].'</td>
						<td width="50%">'.$cpe['cabecera']['dir_destino'].'</td>
					</tr>
				</tbody>
			</table>
			
			<p class="mt-4 font-weight-bold">REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE: </p>
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th>Item</th>
						<th>Código</th>
						<th>Cant.</th>
						<th width="400px">Descripción</th>
						<th>UNID/MED </th>
					</tr>
					'.$items_detalle_html.'
					<tr>
						<td colspan="5" class="text-left" style="border: 1px solid #5f5f5f!important"><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
					</tr>
					<tr>
						<td colspan="5"  class="text-left" style="border: 1px solid #5f5f5f!important"><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
					</tr>
					<tr>
					
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
	
    public function get_html_plantilla_ticket_85($cpe) { //ID: 85, TAMAÑO: A4, ==> GUÍA TRANSPORTISTA
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
			   <td class="text-center">'.$item['ITEM'].'</td>
			   <td  class="text-left">'.$item['DESCRIPCION'].'</td>
			   <td>'.$item['UNIDAD_MEDIDA'].'</td>
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
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="'.$cpe['id_plantilla'].'-'.$cpe['function_name'].'">
			
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie_comprobante'].' - '.$cpe['cabecera']['correlativo'].'</title>
			
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
				<p class="font-weight-bold text-uppercase">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie_comprobante'].' - '.$cpe['cabecera']['correlativo'].'</p>
				<p> <span class="font-weight-bold"></span> Fecha de Emisión: '.date("d-m-Y", strtotime($cpe['cabecera']['fecha_comprobante'])).'</p>
				<p><span class="font-weight-bold">Destinatario:</span>  '.ucwords($cpe['destinatario']['nombre_completo']).' <br> Num.Doc.: '.$cpe['destinatario']['numero_documento'].'</p>
				<p><span class="font-weight-bold"> Remitente:</span> '.$cpe['emisor']['razon_social'].'</p>
			</div>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			<div class="text-left">
				<p><span class="font-weight-bold">N° de registro MTC:</span> '.$cpe['cabecera']['num_registro_mtc'].'</p>
				<p><span class="font-weight-bold">TUC: </span> '.$cpe['cabecera']['tuc_vehiculo_principal'].'</p>
			</div>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			<div class="text-left">
				<p><span class="font-weight-bold">Fecha Traslado:</span>  '.date("d-m-Y", strtotime($cpe['cabecera']['fecha_traslado'])).'</p>
				<p><span class="font-weight-bold"> Conductor: </span>'.$cpe['conductor']['nombre_completo'].' <br> <span class="font-weight-bold">Num. de Licencia.:</span> '.$cpe['conductor']['nro_licencia'].'</p>
				<p><span class="font-weight-bold">Unid. de transporte:</span>  '.$cpe['cabecera']['transporte_nro_placa'].'</p>
			</div>
			<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 2px; margin: 3px" />
			<div class="text-left">
				<p><span class="font-weight-bold">Punto de partida:</span>  '.$cpe['cabecera']['dir_partida'].'</p>
				<p><span class="font-weight-bold">Punto de llegada:</span>  '.$cpe['cabecera']['dir_destino'].'</p>
			</div>
			<div class="text-left">
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th>Cant.</th>
						<th>Descripción</th>
						<th>UNID/MED </th>
					</tr>
					'.$items_detalle_html.'
					<tr>
						<td colspan="5" class="text-left" style=""><span class="font-weight-bold">Peso Bruto (KGM):</span> '.$cpe['cabecera']['peso_total'].'</td>
					</tr>
					<tr>
						<td colspan="5"  class="text-left" style=""><span class="font-weight-bold">Peso Bruto (TONELADAS):</span> '.$cpe['cabecera']['peso_total_tne'].'</td>
					</tr>
					<tr>
					
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
}
?>