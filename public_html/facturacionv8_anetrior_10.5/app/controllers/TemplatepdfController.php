<?php
class TemplatepdfController extends ControllerBase
{
	public function html_factura_pdf_a4($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal) {

		$ruta_base = '/home/humbertoguadalup/public_html';
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="max-width: 170px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		$ubigeo_ubicacion = '';
		if($ubigeo) {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
		}

		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		$array_docs_referencia = json_decode($documento->array_docs_referencia);
		$array_lista = array();
		$lista_docs_referencia ='';
		if(is_array($array_docs_referencia)) {
			foreach($array_docs_referencia as $doc_referencia) {
				$array_lista[] = $lista_docs_referencia.$doc_referencia->serie_comprobante.'-'.$doc_referencia->numero_comprobante;
			}
		}

		if(count($array_lista) > 0) {
			$lista_docs_referencia = implode(', ', $array_lista);
		}
		
		setlocale(LC_MONETARY, 'es_PE');

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':$cliente->direccion_fiscal;
		$nro_placa = $documento->transporte_nro_placa;

		$numero_doc_cliente = $cliente->num_doc;
		if(preg_match('#^sdi.*#s', trim($cliente->num_doc))){
			$numero_doc_cliente = '';
		}

		$texto_tipo_doc = 'Num.Doc: ';
		$texto_dipo_doc_nombre = 'Nombre: ';
		if($cliente->id_tipodocidentidad == '1') {
			//DNI
			$texto_tipo_doc = 'D.N.I.: ';
			$texto_dipo_doc_nombre = 'Nombre: ';
		}

		if($cliente->id_tipodocidentidad == '6') {
			//RUC
			$texto_tipo_doc = 'R.U.C.: ';
			$texto_dipo_doc_nombre = 'Razón Social: ';
		}

		$nombre_comprobante = 'Comprobante Electrónico';
		if($documento->id_tipodoc_electronico == '01') {
			$nombre_comprobante = 'Factura de Venta Electrónica';
		} else if ($documento->id_tipodoc_electronico == '03') {
			$nombre_comprobante = 'Boleta de Venta Electrónica';
		} else if ($documento->id_tipodoc_electronico == '') {

		} else if ($documento->id_tipodoc_electronico == '') {

		}

		$estado_documento = '';
		if($documento->estado_envio_sunat == 'anulado' || $documento->estado_envio_sunat == 'rechazado') {
			$estado_documento = '<br /><p style="color: #f44336; font-size: 18px; font-weight: bold;">COMPROBANTE ANULADO</p><br />';
		}

		$aviso_texto_pruebas = '';
		if($documento->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '<br /><p style="color: #4caf50; font-size: 14px; font-style: oblique;">Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!</p><br />';
		}

		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
		$total_cuentas = count($lista_cuentas);

		$html = '
			<html lang="es-ES" prefix="og: http://ogp.me/ns#" xmlns="https://www.w3.org/1999/xhtml">
			<head>   
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
			</head>
			
				<style>
				body{
					margin: 0;
					padding: 0px;
					font-family: arial, sans-serif;
				}
				table{
					width: 100%;
				
				}
				.box-1{
					margin: 20px 0;
				}
				.box-1 p{
					margin: 0px;
					padding: 0px;
					font-size: 10px;
				}
				/* ===  Clases generales === */
				.bg-main{
					background: #006cae;
					color: #fff;
				}
				.border-bottom{
					border-bottom: solid 1px #000;
				}
				.border-left{
					border-left: solid 1px #000;
				}
				.font-weight-bold{
					font-weight: 700;
				}
				.mt-30{
					margin-top: 20px;
				}
				.p-0{
					padding: 0!important;
				}
				.text-center{
					text-align:center;
				}
				.text-uppercase{
					text-transform: uppercase;
				}
				/* === Tabla cabecera === */
				.table-head   {
					font-size: 17px;
				}
				.table-head td, .table-head th {
					padding: 12px;
					text-align: center;
				}
				.table-main-head, .table-head   {
					border-collapse: collapse;
					width: 100%;
				}
				.table-main-head td, .table-main-head th {
					text-align: center;
				}
				.text-head p{
					line-height: 12px;
					font-size: 12px;
				}
				.spacing-lg {
					width: 60%;
				}
				.spacing-xm {
					width: 77%;
				}
				.table-2 p, .table-3 p, .table-4 p {
				padding: 0px!important;
				margin: 0px!important;
				}
				/* === Tabla Bordes === */
				.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border: solid #000 .5px;
					-moz-border-radius: 12px;
					-webkit-border-radius: 12px;
					border-radius: 12px;
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
				/* === Tabla 2 === */
				.table-2{
					font-size: 13px;
				}
				.table-2 td, .table-2 th {
					text-align: center;
					padding: 10px 0;
				}
				.table-2.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border: solid #000 .5px;
					-moz-border-radius: 6px;
					-webkit-border-radius: 6px;
					border-radius: 6px;
				}
				.table-2.bordered th:first-child {
					-moz-border-radius: 6px 0 0 0;
					-webkit-border-radius: 6px 0 0 0;
					border-radius: 6px 0 0 0;
				}
				.table-2.bordered th:last-child {
					-moz-border-radius: 0 6px 0 0;
					-webkit-border-radius: 0 6px 0 0;
					border-radius: 0 6px 0 0;
				}
				.table-2.bordered th:only-child{
					-moz-border-radius: 6px 6px 0 0;
					-webkit-border-radius: 6px 6px 0 0;
					border-radius: 6px 6px 0 0;
				}
				.table-2.bordered tr:last-child td:first-child {
					-moz-border-radius: 0 0 0 6px;
					-webkit-border-radius: 0 0 0 6px;
					border-radius: 0 0 0 6px;
				}
				.table-2.bordered tr:last-child td:last-child {
					-moz-border-radius: 0 0 6px 0;
					-webkit-border-radius: 0 0 6px 0;
					border-radius: 0 0 6px 0;
				} 
				/* === Tabla 3 === */
				.table-3{
					font-size: 12px;
				}
				.table-3 td, .table-3 th {
					border-bottom: 1px solid #000;
					padding: 7px;
				}
				.table-3.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 0px!important;
					-moz-border-radius: 6px;
					-webkit-border-radius: 6px;
					border-radius: 6px;
				}
				.table-3.bordered th:first-child {
					-moz-border-radius: 6px 0 0 0;
					-webkit-border-radius: 6px 0 0 0;
					border-radius: 6px 0 0 0;
				}
				.table-3.bordered th:last-child {
					-moz-border-radius: 0 6px 0 0;
					-webkit-border-radius: 0 6px 0 0;
					border-radius: 0 6px 0 0;
				}
				.table-3.bordered th:only-child{
					-moz-border-radius: 6px 6px 0 0;
					-webkit-border-radius: 6px 6px 0 0;
					border-radius: 6px 6px 0 0;
				}
				.table-3.bordered tr:last-child td:first-child {
					-moz-border-radius: 0 0 0 6px;
					-webkit-border-radius: 0 0 0 6px;
					border-radius: 0 0 0 6px;
				}
				.table-3.bordered tr:last-child td:last-child {
					-moz-border-radius: 0 0 6px 0;
					-webkit-border-radius: 0 0 6px 0;
					border-radius: 0 0 6px 0;
				} 
				/* === Tabla 4 === */
				.table-4{
					font-size: 10px;
				}
				.table-4 th {
					text-transform: uppercase;
					border-top: .5px solid #000;
					background: #006cae;
					color: #fff;
					text-align: center;
				}
				.table-4 td, .table-4 th {
					padding: 6px;
				}
				.table-4.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 .5px;
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
					border-bottom: solid #000 0px!important;
				} 
				
				/* === Tabla 5 === */
				.table-5 {
					border: solid #000 0.5px;
					font-size: 10px;
					width: 90%;
					padding: 5px;
				}
				.table-5 p{ margin: 0px;}
				.box-table-5{
					text-align: center;
					padding: 5px;
				}
				.box-table-5 p{
					font-size: 11px;
					margin: 0;
					padding: 0;
					display: inline-block;
				}
				/* === Tabla 6 === */
				.border{
					border-left: 1px solid #000;
					border-right: 1px solid #000;
					border-bottom: 1px solid #000;
				}
				.table-6{
					font-size: 11px;
				}
					.table td{
					margin: -1em;
				}
				.table-foot{
					border-top: solid #000 0px;
					border-left: solid #000 0px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 0px;
					border-top-left-radius: 0px;
					border-top-right-radius: 0px;
					border-bottom-right-radius: 6px!important;
					border-bottom-left-radius: 6px;
					margin: 0px;
				}
				.sub-total{
					border-bottom: .5px solid #000;
					border-left: .5px solid #000;
					font-size: 10px;
					padding: 5px 10px;
					font-weight: 700;
				}
				/* == Tabla cuentas ==*/
				.codigo-qr{
					font-size: 10px;
				}
				.table-cuentas{   
					text-transform: uppercase;
					font-size: 10px;
					width:100%;
					margin: 20px 0px;
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 .5px!important;
				}
				.table-cuentas td {
					margin: 5px;
					padding: 5px;
				}
				.table-cuentas p{
					margin: 0px;
					padding: 0px;
				}
				.footer-text{
					border: 1px solid #000;
					width: 100%;		
				}
				.footer-text p{
					padding: 1px;
				}

				*{
					margin-top: 7px;
					margin-bottom: 0;
				}
				</style>

			<body>
				
				<table class="table-main-head">
					<tbody>
						<tr>';

							$sitioweb_html = '';
							if(!empty($sucursal->sitio_web)) {
								$sitioweb_html = "<p>Website: $sucursal->sitio_web</p>";
							}

							if(!empty($contribuyente->logo_350)) {
								$html = $html.'
								<td class="text-head spacing-lg">
									<p><img style="width:260px; margin:0px;padding:0px" src="'.$img_logo.'" /><br />
									<span class="font-weight-bold" style="font-size: 12px;">'.strtoupper($contribuyente->nombre_comercial).'</span>
									</p>
									<p>'.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br />'.$ubigeo_ubicacion.'</p>
									<p>Telf: '.$sucursal->telefono.' - Email: '.$sucursal->email.'</p>
									'.$sitioweb_html.'
								</td>
								';
							} else {
								$html = $html.'
								<td width="100px"><img style="width:100px;" src="'.$img_logo.'" /></td>
								<td class="text-head spacing-lg">
									<p class="font-weight-bold" style="font-size: 12px;">'.strtoupper($contribuyente->nombre_comercial).'</p>
									<p>'.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br />'.$ubigeo_ubicacion.'</p>
									<p>Telf: '.$sucursal->telefono.'</p>
									<p>Email: '.$sucursal->email.'</p>
									'.$sitioweb_html.'
								</td>
								';
							}
							
							$html = $html.'
							<td>
								<table class="table-head bordered">
									<tr>
										<td colspan="3" class="text-center font-weight-bold">R.U.C.: '.$contribuyente->ruc.'</td>
									</tr>
									<tr class="bg-main">
										<td colspan="3" class="text-center font-weight-bold">'.$nombre_comprobante.'</td>
									</tr>
									<tr>
										<td colspan="3" class="text-center">Nro. '.$documento->serie_comprobante.' - '.$this->zero_fill($documento->numero_comprobante, 6).'</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>';

				if(!empty($sucursal->txt_pdf_a4_1)) {
					$html = $html.'
					<table class="table-main-head">
						<tbody>
							<tr>
								<td class="text-head spacing-lg">
									<div>'.$sucursal->txt_pdf_a4_1.'</div>
								</td>
							</tr>
						</tbody>
					</table>
					';
				}

				$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));

				$ubicacion_cliente = '';
				if($ubigeo_cliente) {
					$ubicacion_cliente = $ubigeo_cliente->distrito.' - '.$ubigeo_cliente->provincia.' - '.$ubigeo_cliente->departamento;
				}

				if ($documento->id_tipodoc_electronico == '03') {
					$ubicacion_cliente = '';
				}

				$html = $html.'
				<table class="table-2 bordered mt-30">
					<tbody>
						<tr>
							<td>
								<p class="font-weight-bold">Fecha de Emisión:</p>
								<p>'.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p> 
							</td>
							<td class="border-left">
								<p class="font-weight-bold">Cond. Pago</p>
								<p>'.$nombre_condicion_pago.'</p> 
							</td>
							<td class="border-left">
								<p class="font-weight-bold">Moneda</p>
								<p>'.$sunatmoneda->nombre.'</p> 
							</td>
							<td class="border-left">
								<p class="font-weight-bold">Guía de Remisión N°</p>
								<p>'.(!empty($lista_docs_referencia)?$lista_docs_referencia:'-').'</p> 
							</td>
						</tr>
					</tbody>
				</table>

				<table class="table-3 bordered mt-30">
					<tbody>
						<tr>
							<td class="spacing-lg">
								<p>
									<span class="font-weight-bold">'.$texto_tipo_doc.' </span> '.$cliente->num_doc.'
								</p>
							</td>
							<td>
								<p><span class="font-weight-bold">Orden de Compra: </span> '.$documento->nro_otr_comprobante.'</p>
							</td>
						</tr>
						<tr>
							<td class="spacing-lg">
								<p><span class="font-weight-bold">'.$texto_dipo_doc_nombre.' </span> '.ucwords($cliente->razon_social).'</p>
							</td>
							<td>
								<p><span class="font-weight-bold">Placa N°: </span>'.$nro_placa.'</p>
							</td>
						</tr>
						<tr>
							<td class="spacing-lg" colspan="2">
								<p><span class="font-weight-bold">Dirección: </span> '.ucwords(strtolower($cliente->direccion_fiscal)).' '.$ubicacion_cliente.'</p>
							</td>
							<td style="display:none;">
								<p style="display:none;"><span class="font-weight-bold">Cond. de Pago: </span> CONTADO</p>
							</td>
						</tr>
					</tbody>
				</table>
				
				<table class="table-4 bordered mt-30">
					<tbody>   
						<tr>
							<th>Item</th>
							<th>Código</th>
							<th width="300px">Descripción</th>
							<th>Unid.</th>
							<th>Cantidad</th>
							<th>P.Unitario</th>
							<th width="80px">Total</th>
						</tr>';

						$items_detalle_html = '';
						$nro_item = 0;
						foreach($detalle as $item) {
							$nro_item++;
							$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
							$items_detalle_html = $items_detalle_html.'
							<tr class="border-left">
								<td align="center">'.$nro_item.'</td>
								<td align="center">'.$item['CODIGO_PRODUCTO'].'</td>
								<td>'.$item['DESCRIPCION_DET'].'</td>
								<td align="center">'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
								<td align="center">'.($item['CANTIDAD_DET'] + 0).'</td>
								<td align="center">'.$sunatmoneda->simbolo.' '.$item['PRECIO_DET'].'</td>
								<td align="right">'.$sunatmoneda->simbolo.' '.custom_money_format($sub_total_con_igv).'</td>
							</tr>
							';
						}
						
						$html = $html.$items_detalle_html.'
						<tr>
							<td colspan="7" class="text-uppercase" style="border-top: solid #000 .5px;"> '.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				
				';

				
				$rows_subtotales = '';
				$num_items_subtotales = 1;
				if(floatval($documento->total_inafecta) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Inafecto </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_inafecta).'</td>
					</tr>
					';
				}

				if(floatval($documento->total_exoneradas) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Exonerado </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_exoneradas).'</td>
					</tr>
					';
				}

				if(floatval($documento->total_exportacion) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Exportación </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_exportacion).'</td>
					</tr>
					';
				}

				if(floatval($documento->total_icbper) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">ICBPER </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_icbper).'</td>
					</tr>
					';
				}

				$num_items_subtotales++;
				$rows_subtotales = $rows_subtotales.'
				<tr>
					<td class="sub-total">IGV ('.$documento->porcentaje_igv.'%): </td>
					<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_igv).'</td>
				</tr>
				';

				if(floatval($documento->total_gratuitas) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Gratuito </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_gratuitas).'</td>
					</tr>
					';
				}

				$num_items_subtotales++;
				$rows_subtotales = $rows_subtotales.'
				<tr>
					<td class="sub-total">Descuento Total: </td>
					<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format(round(floatval($documento->total_descuento), 2)).'</td>
				</tr>
				';

				$num_items_subtotales++;
				$rows_subtotales = $rows_subtotales.'
				<tr>
					<td class="sub-total">Total: </td>
					<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format(round(floatval($documento->total), 2)).'</td>
				</tr>
				';

				$html = $html.'
				<table width="100%" cellpadding="0" cellspacing="0"  class="table-foot">
					<tr>
						<td rowspan="'.$num_items_subtotales.'" valign="middle" align="left" class="box-table-5" width="500px">
							<div>';
								if(!empty($sucursal->txt_pdf_a4_2)) {
									$html = $html.'<p>'.$sucursal->txt_pdf_a4_2.'</p><br>';
								}

								$nota_documento = '';
								if(!empty($documento->nota)) {
									$nota_documento = '<p style="margin-top: 5px; text-align: left;"> <span class="font-weight-bold" style="color: #006cae;">Observación:</span> <br />'.nl2br($documento->nota).'</p>';
								}

								$html = $html.$nota_documento.$estado_documento.$aviso_texto_pruebas.'
							</div>
						</td>
						<td class="sub-total">Gravado </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_gravadas).'</td>
					</tr>
					
					'.$rows_subtotales.'
				</table>';
				
				
				if(!empty($documento->detraccion_iddetraccion)) {
					$html = $html.'
					<p style="font-size: 10px;font-weight: bold; margin: 0" class="text-uppercase">Detracción en Moneda Soles</p>
					<table class="table-5">
						<tbody>
							<tr>
								<td>
									<p>% Detracción: '.$documento->detraccion_porcentaje.'</p>
								</td>
								<td>
									<p>Monto Detracción: '.$documento->detraccion_monto.'</p>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p><span class="font-weight-bold">Descripción:</span> '.$documento->detraccion_texto.'</p>
									<p><span class="font-weight-bold">Banco Nación:</span> '.$documento->detraccion_cuenta.'</p>
								</td>
							</tr>
						</tbody>
					</table>';
				}

				if($documento->id_tipo_operacion == '2001') {
					$html = $html.'
					<p style="font-size: 10px;font-weight: bold; margin: 0" class="text-uppercase">Comprobante de Percepción</p>
					<table class="table-5">
						<tbody>
							<tr>
								<td>
									<p>% Percepción: '.($documento->percepcion_porcentaje + 0).'</p>
								</td>
								<td>
									<p>Monto Percepción: '.$sunatmoneda->simbolo.' '.custom_money_format($documento->percepcion_monto).'</p>
								</td>
								<td>
									<p><span class="font-weight-bold">Monto Total:</span> '.$sunatmoneda->simbolo.' '.custom_money_format($documento->percepcion_montobase + $documento->percepcion_monto).'</p>
								</td>
							</tr>
						</tbody>
					</table>';
				}
				

				if($total_cuentas > 0 && $total_cuentas <= 3) {
					$html = $html.'
						<table  class="table-cuentas">
						<tr>
							<td colspan="3" style="border-bottom: 1px solid #000;"> <p>Usted puede hacer pagos directamente en nuestras cuentas en los siguientes Bancos: </p></td>
						</tr>
						<tr>';

						foreach($lista_cuentas as $cuenta) {
							$nombre_moneda = ($cuenta->id_codigomoneda=='PEN')?'Soles':'Dólares';
							$html = $html.'
							<td>
								<p class="text-uppercase">Banco: '.$cuenta->nombre_banco.'</p> 
								<p class="text-uppercase">Titular: '.$cuenta->nombre_titular.'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$nombre_moneda.'):</span> '.$cuenta->nro_cuenta.'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta->cci.'</p>
							</td>
							';
						}

					$html = $html.'
						</tr>
					</table>';
				}

				$rows = 0;
				if($total_cuentas > 3) {
					$html = $html.'
					<table  class="table-cuentas">
						<tr>
							<td colspan="3" style="border-bottom: 1px solid #000;"> <p>Usted puede hacer pagos directamente en nuestras cuentas corrientes</p></td>
						</tr>';
					
					$item_nf = '';
					$parte1 = '';
					foreach($lista_cuentas as $cuenta) {
						$rows++;
						$nombre_moneda = ($cuenta->id_codigomoneda=='PEN')?'Soles':'Dólares';
						if($rows > 0 && $rows <= 3) {
							$parte1 = $parte1.'
							<td>
								<p class="text-uppercase">'.$cuenta->nombre_banco.'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$nombre_moneda.'):</span> '.$cuenta->nro_cuenta.'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta->cci.'</p>
							</td>
							';
						}
						
						if($rows > 3 && $rows <= 6) {
							$item_nf = $item_nf.'
							<td>
								<p class="text-uppercase">'.$cuenta->nombre_banco.'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$nombre_moneda.'):</span> '.$cuenta->nro_cuenta.'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta->cci.'</p>
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

					$html = $html.'
					</table>';
				}

				$html = $html.'
				<div class="box-1 footer-text">
					<table style="margin:0;">
						<tr>
							<td>
								<p>Autorizado mediante la resolución Nº 0640050002737/Sunat</p>
								<p>Representación impresa de la '.$nombre_comprobante.'</p>
								<p>Para consultar el comprobante visita '.$ruta_consulta_documento.'</p>
								<p>HASH: '.$documento->hash_cpe.'</p>
								<p>Atendido Por: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
							</td>
							<td style="text-align: right;">
								<img src="'.$ruta_qr.'"  width="70px" style="margin: 5px" alt="">
							</td>
						</tr>
					</table>
				</div>';

				if(!empty($sucursal->txt_pdf_a4_3)) {
					$html = $html.'
					<div class="box-1 footer-text">
						<table style="margin:0;">
							<tr>
								<td>
									<p>'.$sucursal->txt_pdf_a4_3.'</p>
								</td>
							</tr>
						</table>
					</div>
					';
				}
				
			$html = $html.'
			</body>
			</html>
		';

		return $html;
	}

	public function html_doc_no_oficial($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal) {

		$ruta_base = '/home/humbertoguadalup/public_html';
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="max-width: 170px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		$ubigeo_ubicacion = '';
		if($ubigeo) {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
		}

		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}
		
		$array_lista = array();
		$lista_docs_referencia ='';
		
		setlocale(LC_MONETARY, 'es_PE');

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':$cliente->direccion_fiscal;
		$nro_placa = $documento->transporte_nro_placa;

		$numero_doc_cliente = $cliente->num_doc;
		if(preg_match('#^sdi.*#s', trim($cliente->num_doc))){
			$numero_doc_cliente = '';
		}

		$texto_tipo_doc = 'Num.Doc: ';
		$texto_dipo_doc_nombre = 'Nombre: ';
		if($cliente->id_tipodocidentidad == '1') {
			//DNI
			$texto_tipo_doc = 'D.N.I.: ';
			$texto_dipo_doc_nombre = 'Nombre: ';
		}

		if($cliente->id_tipodocidentidad == '6') {
			//RUC
			$texto_tipo_doc = 'R.U.C.: ';
			$texto_dipo_doc_nombre = 'Razón Social: ';
		}

		$nombre_comprobante = 'Comprobante Electrónico';
		$serie_documento = 'DOC';
		if($documento->id_tipodocumento == '77') {
			$nombre_comprobante = 'Nota de Venta';
			$serie_documento = "";
		} else if ($documento->id_tipodocumento == '88') {
			$nombre_comprobante = 'Cotización';
			$serie_documento = "";
		} else if ($documento->id_tipodocumento == '') {

		} else if ($documento->id_tipodocumento == '') {

		}

		$estado_documento = '';
		if($documento->estado_documento == 'inactivo') {
			$estado_documento = '<br /><p style="color: #f44336; font-size: 18px; font-weight: bold;">COMPROBANTE ANULADO</p><br />';
		}

		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '<br /><p style="color: #4caf50; font-size: 14px; font-style: oblique;">Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!</p><br />';
		}

		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
		$total_cuentas = count($lista_cuentas);

		$html = '
			<html lang="es-ES" prefix="og: http://ogp.me/ns#" xmlns="https://www.w3.org/1999/xhtml">
			<head>   
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
			</head>
			
				<style>
				body{
					margin: 0;
					padding: 0px;
					font-family: arial, sans-serif;
				}
				table{
					width: 100%;
				
				}
				.box-1{
					margin: 20px 0;
				}
				.box-1 p{
					margin: 0px;
					padding: 0px;
					font-size: 10px;
				}
				/* ===  Clases generales === */
				.bg-main{
					background: #006cae;
					color: #fff;
				}
				.border-bottom{
					border-bottom: solid 1px #000;
				}
				.border-left{
					border-left: solid 1px #000;
				}
				.font-weight-bold{
					font-weight: 700;
				}
				.mt-30{
					margin-top: 20px;
				}
				.p-0{
					padding: 0!important;
				}
				.text-center{
					text-align:center;
				}
				.text-uppercase{
					text-transform: uppercase;
				}
				/* === Tabla cabecera === */
				.table-head   {
					font-size: 17px;
				}
				.table-head td, .table-head th {
					padding: 12px;
					text-align: center;
				}
				.table-main-head, .table-head   {
					border-collapse: collapse;
					width: 100%;
				}
				.table-main-head td, .table-main-head th {
					text-align: center;
				}
				.text-head p{
					line-height: 12px;
					font-size: 12px;
				}
				.spacing-lg {
					width: 60%;
				}
				.spacing-xm {
					width: 77%;
				}
				.table-2 p, .table-3 p, .table-4 p {
				padding: 0px!important;
				margin: 0px!important;
				}
				/* === Tabla Bordes === */
				.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border: solid #000 .5px;
					-moz-border-radius: 12px;
					-webkit-border-radius: 12px;
					border-radius: 12px;
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
				/* === Tabla 2 === */
				.table-2{
					font-size: 13px;
				}
				.table-2 td, .table-2 th {
					text-align: center;
					padding: 10px 0;
				}
				.table-2.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border: solid #000 .5px;
					-moz-border-radius: 6px;
					-webkit-border-radius: 6px;
					border-radius: 6px;
				}
				.table-2.bordered th:first-child {
					-moz-border-radius: 6px 0 0 0;
					-webkit-border-radius: 6px 0 0 0;
					border-radius: 6px 0 0 0;
				}
				.table-2.bordered th:last-child {
					-moz-border-radius: 0 6px 0 0;
					-webkit-border-radius: 0 6px 0 0;
					border-radius: 0 6px 0 0;
				}
				.table-2.bordered th:only-child{
					-moz-border-radius: 6px 6px 0 0;
					-webkit-border-radius: 6px 6px 0 0;
					border-radius: 6px 6px 0 0;
				}
				.table-2.bordered tr:last-child td:first-child {
					-moz-border-radius: 0 0 0 6px;
					-webkit-border-radius: 0 0 0 6px;
					border-radius: 0 0 0 6px;
				}
				.table-2.bordered tr:last-child td:last-child {
					-moz-border-radius: 0 0 6px 0;
					-webkit-border-radius: 0 0 6px 0;
					border-radius: 0 0 6px 0;
				} 
				/* === Tabla 3 === */
				.table-3{
					font-size: 12px;
				}
				.table-3 td, .table-3 th {
					border-bottom: 1px solid #000;
					padding: 7px;
				}
				.table-3.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 0px!important;
					-moz-border-radius: 6px;
					-webkit-border-radius: 6px;
					border-radius: 6px;
				}
				.table-3.bordered th:first-child {
					-moz-border-radius: 6px 0 0 0;
					-webkit-border-radius: 6px 0 0 0;
					border-radius: 6px 0 0 0;
				}
				.table-3.bordered th:last-child {
					-moz-border-radius: 0 6px 0 0;
					-webkit-border-radius: 0 6px 0 0;
					border-radius: 0 6px 0 0;
				}
				.table-3.bordered th:only-child{
					-moz-border-radius: 6px 6px 0 0;
					-webkit-border-radius: 6px 6px 0 0;
					border-radius: 6px 6px 0 0;
				}
				.table-3.bordered tr:last-child td:first-child {
					-moz-border-radius: 0 0 0 6px;
					-webkit-border-radius: 0 0 0 6px;
					border-radius: 0 0 0 6px;
				}
				.table-3.bordered tr:last-child td:last-child {
					-moz-border-radius: 0 0 6px 0;
					-webkit-border-radius: 0 0 6px 0;
					border-radius: 0 0 6px 0;
				} 
				/* === Tabla 4 === */
				.table-4{
					font-size: 10px;
				}
				.table-4 th {
					text-transform: uppercase;
					border-top: .5px solid #000;
					background: #006cae;
					color: #fff;
					text-align: center;
				}
				.table-4 td, .table-4 th {
					padding: 6px;
				}
				.table-4.bordered {
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 .5px;
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
					border-bottom: solid #000 0px!important;
				} 
				
				/* === Tabla 5 === */
				.table-5 {
					border: solid #000 0.5px;
					font-size: 10px;
					width: 90%;
					padding: 5px;
				}
				.table-5 p{ margin: 0px;}
				.box-table-5{
					text-align: center;
					padding: 5px;
				}
				.box-table-5 p{
					font-size: 11px;
					margin: 0;
					padding: 0;
					display: inline-block;
				}
				/* === Tabla 6 === */
				.border{
					border-left: 1px solid #000;
					border-right: 1px solid #000;
					border-bottom: 1px solid #000;
				}
				.table-6{
					font-size: 11px;
				}
					.table td{
					margin: -1em;
				}
				.table-foot{
					border-top: solid #000 0px;
					border-left: solid #000 0px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 0px;
					border-top-left-radius: 0px;
					border-top-right-radius: 0px;
					border-bottom-right-radius: 6px!important;
					border-bottom-left-radius: 6px;
					margin: 0px;
				}
				.sub-total{
					border-bottom: .5px solid #000;
					border-left: .5px solid #000;
					font-size: 10px;
					padding: 5px 10px;
					font-weight: 700;
				}
				/* == Tabla cuentas ==*/
				.codigo-qr{
					font-size: 10px;
				}
				.table-cuentas{   
					text-transform: uppercase;
					font-size: 10px;
					width:100%;
					margin: 20px 0px;
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 .5px!important;
				}
				.table-cuentas td {
					margin: 5px;
					padding: 5px;
				}
				.table-cuentas p{
					margin: 0px;
					padding: 0px;
				}
				.footer-text{
					border: 1px solid #000;
					width: 100%;		
				}
				.footer-text p{
					padding: 1px;
				}

				*{
					margin-top: 7px;
					margin-bottom: 0;
				}
				</style>

			<body>
				
				<table class="table-main-head">
					<tbody>
						<tr>';

							if(!empty($contribuyente->logo_350)) {
								$html = $html.'
								<td class="text-head spacing-lg">
									<p><img style="width:260px; margin:0px;padding:0px" src="'.$img_logo.'" /><br />
									<span class="font-weight-bold" style="font-size: 12px;">'.strtoupper($contribuyente->nombre_comercial).'</span>
									</p>
									<p>'.$sucursal->direccion.' '.$sucursal->urbanizacion.' '.$ubigeo_ubicacion.'</p>
									<p>Telf: '.$sucursal->telefono.' - Email: '.$sucursal->email.'</p>
								</td>
								';
							} else {
								$html = $html.'
								<td width="100px"><img style="width:100px;" src="'.$img_logo.'" /></td>
								<td class="text-head spacing-lg">
									<p class="font-weight-bold" style="font-size: 12px;">'.strtoupper($contribuyente->nombre_comercial).'</p>
									<p>'.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br />'.$ubigeo_ubicacion.'</p>
									<p>Telf: '.$sucursal->telefono.'</p>
									<p>Email: '.$sucursal->email.'</p>
								</td>
								';
							}
							
							$html = $html.'
							<td>
								<table class="table-head bordered">
									<tr>
										<td colspan="3" class="text-center font-weight-bold">R.U.C.: '.$contribuyente->ruc.'</td>
									</tr>
									<tr class="bg-main">
										<td colspan="3" class="text-center font-weight-bold">'.$nombre_comprobante.'</td>
									</tr>
									<tr>
										<td colspan="3" class="text-center">Nro. '.$this->zero_fill($documento->numero_comprobante, 6).'</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>';

				if(!empty($sucursal->txt_pdf_a4_1)) {
					$html = $html.'
					<table class="table-main-head">
						<tbody>
							<tr>
								<td class="text-head spacing-lg">
									<div>'.$sucursal->txt_pdf_a4_1.'</div>
								</td>
							</tr>
						</tbody>
					</table>
					';
				}

				$ubigeo_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
				$ubicacion_cliente = '';
				if($ubigeo_cliente) {
					$ubicacion_cliente = $ubigeo_cliente->distrito.' - '.$ubigeo_cliente->provincia.' - '.$ubigeo_cliente->departamento;
				}

				$html = $html.'
				<table class="table-2 bordered mt-30">
					<tbody>
						<tr>
							<td>
								<p class="font-weight-bold">Fecha de Emisión:</p>
								<p>'.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p> 
							</td>
							<td class="border-left">
								<p class="font-weight-bold">Cond. Pago</p>
								<p>'.$nombre_condicion_pago.'</p> 
							</td>
							<td class="border-left">
								<p class="font-weight-bold">Moneda</p>
								<p>'.$sunatmoneda->nombre.'</p> 
							</td>
							<td class="border-left">
								<p class="font-weight-bold">Guía de Remisión N°</p>
								<p>'.(!empty($lista_docs_referencia)?$lista_docs_referencia:'-').'</p> 
							</td>
						</tr>
					</tbody>
				</table>

				<table class="table-3 bordered mt-30">
					<tbody>
						<tr>
							<td class="spacing-lg">
								<p>
									<span class="font-weight-bold">'.$texto_tipo_doc.' </span> '.$cliente->num_doc.'
								</p>
							</td>
							<td>
								<p><span class="font-weight-bold">Orden de Compra: </span> '.$documento->nro_otr_comprobante.'</p>
							</td>
						</tr>
						<tr>
							<td class="spacing-lg">
								<p><span class="font-weight-bold">'.$texto_dipo_doc_nombre.' </span> '.ucwords($cliente->razon_social).'</p>
							</td>
							<td>
								<p><span class="font-weight-bold" style="display:none;">Placa N°: </span>'.$nro_placa.'</p>
							</td>
						</tr>
						<tr>
							<td class="spacing-lg" colspan="2">
								<p><span class="font-weight-bold">Dirección: </span> '.ucwords(strtolower($cliente->direccion_fiscal)).' '.$ubicacion_cliente.'</p>
							</td>
							<td style="display:none;">
								<p style="display:none;"><span class="font-weight-bold">Cond. de Pago: </span> CONTADO</p>
							</td>
						</tr>
					</tbody>
				</table>
				
				<table class="table-4 bordered mt-30">
					<tbody>   
						<tr>
							<th>Item</th>
							<th>Código</th>
							<th width="330px">Descripción</th>
							<th>Unid.</th>
							<th>Cantidad</th>
							<th>P.Unitario</th>
							<th width="80px">Total</th>
						</tr>';

						$items_detalle_html = '';
						$nro_item = 0;
						foreach($detalle as $item) {
							$nro_item++;
							$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
							$descripcion_producto = $item['DESCRIPCION_DET'];
							$producto_item = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item['IDPRODUCTO_DET'], 'id_contribuyente' => $contribuyente->id_contribuyente)));

							if($producto_item) {
								if(!empty($producto_item->marca)) {
									$descripcion_producto = $descripcion_producto.' - '.$producto_item->marca;
								}
	
								if(!empty($producto_item->laboratorio)) {
									$descripcion_producto = $descripcion_producto.' - '.$producto_item->laboratorio;
								}
							}

							$items_detalle_html = $items_detalle_html.'
							<tr class="border-left">
								<td align="center">'.$nro_item.'</td>
								<td align="center">'.$item['CODIGO_PRODUCTO'].'</td>
								<td>'.$descripcion_producto.'</td>
								<td align="center">'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
								<td align="center">'.($item['CANTIDAD_DET'] + 0).'</td>
								<td align="center">'.$sunatmoneda->simbolo.' '.$item['PRECIO_DET'].'</td>
								<td align="right">'.$sunatmoneda->simbolo.' '.custom_money_format($sub_total_con_igv).'</td>
							</tr>
							';
						}
						
						$html = $html.$items_detalle_html.'
						<tr>
							<td colspan="7" class="text-uppercase" style="border-top: solid #000 .5px;">SON: '.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				
				';

				
				$rows_subtotales = '';
				$num_items_subtotales = 1;
				if(floatval($documento->total_inafecta) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Inafecto </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_inafecta).'</td>
					</tr>
					';
				}

				if(floatval($documento->total_exoneradas) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Exonerado </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_exoneradas).'</td>
					</tr>
					';
				}

				if(floatval($documento->total_exportacion) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Exportación </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_exportacion).'</td>
					</tr>
					';
				}

				if(floatval($documento->total_icbper) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">ICBPER </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_icbper).'</td>
					</tr>
					';
				}

				$num_items_subtotales++;
				$rows_subtotales = $rows_subtotales.'
				<tr>
					<td class="sub-total">IGV ('.$documento->porcentaje_igv.'%): </td>
					<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_igv).'</td>
				</tr>
				';

				if(floatval($documento->total_gratuitas) > 0) {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Gratuito </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_gratuitas).'</td>
					</tr>
					';
				}

				$num_items_subtotales++;
				$rows_subtotales = $rows_subtotales.'
				<tr>
					<td class="sub-total">Descuento Total: </td>
					<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format(round(floatval($documento->total_descuento), 2)).'</td>
				</tr>
				';

				$num_items_subtotales++;
				$rows_subtotales = $rows_subtotales.'
				<tr>
					<td class="sub-total">Total: </td>
					<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format(round(floatval($documento->total), 2)).'</td>
				</tr>
				';

				if($documento->id_codigomoneda == 'USD') {
					$num_items_subtotales++;
					$rows_subtotales = $rows_subtotales.'
					<tr>
						<td class="sub-total">Total (T.C '.$documento->tipo_cambio_sunat.'): </td>
						<td align="right" class="sub-total">S/. '.custom_money_format(round($documento->tipo_cambio_sunat*$documento->total, 2)).'</td>
					</tr>
					';
				}

				$html = $html.'
				<table width="100%" cellpadding="0" cellspacing="0"  class="table-foot">
					<tr>
						<td rowspan="'.$num_items_subtotales.'" valign="middle" align="left" class="box-table-5" width="500px">
							<div>';
								if(!empty($sucursal->txt_pdf_a4_2)) {
									$html = $html.'<p>'.$sucursal->txt_pdf_a4_2.'</p><br>';
								}

								$nota_documento = '';
								if(!empty($documento->nota)) {
									$nota_documento = '<p style="margin-top: 5px"> <span class="font-weight-bold" style="color: #006cae;">Observación:</span> '.$documento->nota.'</p>';
								}

								$html = $html.$nota_documento.$estado_documento.$aviso_texto_pruebas.'
							</div>
						</td>
						<td class="sub-total">Gravado </td>
						<td align="right" class="sub-total">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_gravadas).'</td>
					</tr>
					
					'.$rows_subtotales.'
				</table>';
				
				
				if(!empty($documento->detraccion_iddetraccion)) {
					$html = $html.'
					<p style="font-size: 10px;font-weight: bold; margin: 0" class="text-uppercase">Detracción en Moneda Soles</p>
					<table class="table-5">
						<tbody>
							<tr>
								<td>
									<p>% Detracción: '.$documento->detraccion_porcentaje.'</p>
								</td>
								<td>
									<p>Monto Detracción: '.$documento->detraccion_monto.'</p>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p><span class="font-weight-bold">Descripción:</span> '.$documento->detraccion_texto.'</p>
									<p><span class="font-weight-bold">Banco Nación:</span> '.$documento->detraccion_cuenta.'</p>
								</td>
							</tr>
						</tbody>
					</table>';
				}
				

				if($total_cuentas > 0 && $total_cuentas <= 3) {
					$html = $html.'
						<table  class="table-cuentas">
						<tr>
							<td colspan="3" style="border-bottom: 1px solid #000;"> <p>Usted puede hacer pagos directamente en nuestras cuentas en los siguientes Bancos: </p></td>
						</tr>
						<tr>';

						foreach($lista_cuentas as $cuenta) {
							$nombre_moneda = ($cuenta->id_codigomoneda=='PEN')?'Soles':'Dólares';
							$html = $html.'
							<td>
								<p class="text-uppercase">Banco: '.$cuenta->nombre_banco.'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$nombre_moneda.'):</span> '.$cuenta->nro_cuenta.'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta->cci.'</p>
							</td>
							';
						}

					$html = $html.'
						</tr>
					</table>';
				}

				$rows = 0;
				if($total_cuentas > 3) {
					$html = $html.'
					<table  class="table-cuentas">
						<tr>
							<td colspan="3" style="border-bottom: 1px solid #000;"> <p>Usted puede hacer pagos directamente en nuestras cuentas corrientes</p></td>
						</tr>';
					
					$item_nf = '';
					$parte1 = '';
					foreach($lista_cuentas as $cuenta) {
						$rows++;
						$nombre_moneda = ($cuenta->id_codigomoneda=='PEN')?'Soles':'Dólares';
						if($rows > 0 && $rows <= 3) {
							$parte1 = $parte1.'
							<td>
								<p class="text-uppercase">'.$cuenta->nombre_banco.'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$nombre_moneda.'):</span> '.$cuenta->nro_cuenta.'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta->cci.'</p>
							</td>
							';
						}
						
						if($rows > 3 && $rows <= 6) {
							$item_nf = $item_nf.'
							<td>
								<p class="text-uppercase">'.$cuenta->nombre_banco.'</p> 
								<p><span class="text-uppercase">Nro Cuenta ('.$nombre_moneda.'):</span> '.$cuenta->nro_cuenta.'</p>
								<p><span class="text-uppercase">CCI:</span> '.$cuenta->cci.'</p>
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

					$html = $html.'
					</table>';
				}

				$html = $html.'
				<div class="box-1 footer-text">
					<table style="margin:0;">
						<tr>
							<td>
								<p>Autorizado mediante la resolución Nº 0640050002737/Sunat</p>
								<p>Representación impresa de la '.$nombre_comprobante.'</p>
								<p>Para consultar el comprobante visita '.$ruta_consulta_documento.'</p>
								<p>Atendido Por: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
							</td>
							<td style="text-align: right;">
								<img src="'.$ruta_qr.'"  width="70px" style="margin: 5px" alt="">
							</td>
						</tr>
					</table>
				</div>';

				if(!empty($sucursal->txt_pdf_a4_3)) {
					$html = $html.'
					<div class="box-1 footer-text">
						<table style="margin:0;">
							<tr>
								<td>
									<p>'.$sucursal->txt_pdf_a4_3.'</p>
								</td>
							</tr>
						</table>
					</div>
					';
				}
				
			$html = $html.'
			</body>
			</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	

	public function zero_fill ($valor, $long = 0) {
		return str_pad($valor, $long, '0', STR_PAD_LEFT);
	}
	
	public function html_abono_pdf_ticket($id_montocobrado) {
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Usuario';
			$resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			return $resp;
		}

		$abono = MontoCobrado::findFirst(array("id_contribuyente = :id_contribuyente: and id_montocobrado = :id_montocobrado:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_montocobrado' => $id_montocobrado)));
		if(!$abono) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No Existe el Abono.';
			return $resp;
		}
		
		setlocale(LC_MONETARY, 'es_PE');
		$array_cpe = array('01', '03', '07', '08');
		$array_doc_nooficinal = array('77');
		$documentoelectronico = new DocumentoelectronicoController;

		if(in_array($abono->id_tipodoc_electronico, $array_cpe)) {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $abono->id_tipodoc_electronico, 'serie_comprobante' => $abono->serie_comprobante, 'numero_comprobante' => $abono->numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			$id_tipo_doc_electronico = $documento->id_tipodoc_electronico;
			$lista_abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and (estado = 'activo' or estado = 'indefinido')", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $abono->id_tipodoc_electronico, 'serie_comprobante' => $abono->serie_comprobante, 'numero_comprobante' => $abono->numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodocumento' => $abono->id_tipodoc_electronico, 'numero_comprobante' => $abono->numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
			$id_tipo_doc_electronico = $documento->id_tipodocumento;
			$lista_abonos = MontoCobrado::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and (estado = 'activo' or estado = 'indefinido')", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $abono->id_tipodoc_electronico, 'numero_comprobante' => $abono->numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		}
		
		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Documento al que hace referencia el abono no existe!.';
			return $resp;
		}

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No encontramos los datos del cliente!';
			return $resp;
		}

		$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $id_tipo_doc_electronico)));
		if(in_array($abono->id_tipodoc_electronico, $array_cpe)) {
			$nombre_doc_referencia = $tipo_doc_electronico->descripcion.' '.$documento->serie_comprobante.'-'.$this->zero_fill($documento->numero_comprobante, 6);
		} else {
			$nombre_doc_referencia = $tipo_doc_electronico->descripcion.' N° '.$this->zero_fill($documento->numero_comprobante, 4);
		}
		

		$orden = 0;
		$orden_abono = 1;
		foreach($lista_abonos as $item_abono) {
			$orden++;
			if($item_abono->id_montocobrado == $id_montocobrado) {
				$orden_abono = $orden;
			}
		}

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));

		if(in_array($abono->id_tipodoc_electronico, $array_cpe)) {
			$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($documento->id_contribuyente, $documento->id_tipodoc_electronico, $documento->serie_comprobante, $documento->numero_comprobante);
		} else {
			$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($documento->id_contribuyente, $documento->id_tipodocumento, $documento->numero_comprobante, $contribuyente->tipo_envio_sunat);
		}
		
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$detalle = $resp_detalle['detalle'];

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $abono->id_vendedor)));
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		$ruta_base = 'https://'.$this->get_parametros_iniciales()['url_domain'];

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="max-width: 170px;"';
			//$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
			$img_logo = $contribuyente->img_logo;
		} else {
			$style_logo = 'style="width: 180px;"';
			//$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
			$img_logo = $contribuyente->logo_350;
		}

		$img_logo = $ruta_base.$img_logo;

		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		$monto_adeudado = 0;
		if(!empty($documento->monto_adeudado) && floatval($documento->monto_adeudado) > 0) {
			$monto_adeudado = $documento->monto_adeudado;
		}

		$nombre_doc_identidad_clie = 'Num.Doc.:';
		if($cliente->id_tipodocidentidad == '6') {
			$nombre_doc_identidad_clie = 'R.U.C.:';
		} else if($cliente->id_tipodocidentidad == '1') {
			$nombre_doc_identidad_clie = 'D.N.I.:';
		} else {

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
			<tr>
				<td class="cabecera_detalle_items" style="height: 10px;"></td>
			</tr>
			';
		}

		$resumen = '
		<tr>
			<td align="right" class="texto_12">
				Total Doc. Elect.: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		<tr>
			<td align="right" class="texto_12">
				Monto Abonado: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($abono->total).'
			</td>
		</tr>
		<tr>
			<td align="right" class="texto_12">
				Total Abonos: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round($documento->total - $monto_adeudado, 2)).'
			</td>
		</tr>
		<tr>
			<td align="right" class="texto_12">
				Total Adeudado: <span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($monto_adeudado).'
			</td>
		</tr>
		';
		

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
					<td align="center">
						<img '.$style_logo.' src="'.$img_logo.'" />
					<td>
				</tr>
				<tr>
					<td align="center" height="5">
					<td>
				</tr>
				<tr>
					<td align="center" class="texto_14_bold">'.$contribuyente->nombre_comercial.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">R.U.C.:'.$contribuyente->ruc.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">'.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br /> '.$ubigeo_ubicacion.'<td>
				</tr>
				<tr>
					<td align="center" class="texto_12">
						<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/telephone.png" style="width: 12px;" /> Telf.: '.$sucursal->telefono.'<br />
						<img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/email.svg" style="width: 12px;" /> Email: '.$sucursal->email.'
					<td>
				</tr>
				<tr>
					<td><hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px; font-family: Arial Narrow, Arial, sans-serif; font-size: 12px;" /></td>
				</tr>
				<tr>
					<td align="center"  class="texto_14_bold">Registro Abono<td>
				</tr>
				<tr>
					<td align="center" class="texto_14_bold">N° '.$this->zero_fill($orden_abono, 3).'<td>
				</tr>
				<tr>
					<td align="left" class="texto_12">Fecha de Registro: '.date("d-m-Y / H:i A", strtotime($abono->fecha_registro)).'<td>
				</tr>
				<tr>
					<td align="left" class="texto_12">
					Señor (es): '.ucwords($cliente->razon_social).'
					<td>
				</tr>
				<tr>
					<td align="left" class="texto_12">'.$nombre_doc_identidad_clie.' '.$cliente->num_doc.'<td>
				</tr>
				<tr>
					<td align="left" class="texto_12">Direc.: '.ucwords(strtolower($cliente->direccion_fiscal)).'<td>
				</tr>
				<tr>
					<td align="left" class="texto_12">Doc. Referencia: '.$nombre_doc_referencia.'<td>
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
					<td align="left" class="texto_12"> 
						<span>Observación: '.$abono->detalle.'</span>
					</td>
				</tr>
			</table>
			<p><hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" /></p>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="center" class="texto_10"> 
						<span>Atendido Por: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</span>
					</td>
				</tr>
			</table>
			<hr style="border-top: 1px dashed #030303; color: #fff; background-color: #fff; height: 4px;" />
		</body>
		</html>
		';

		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
}
?>