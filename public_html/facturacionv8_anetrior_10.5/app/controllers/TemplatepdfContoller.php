<?php
class TemplatepdfController extends ControllerBase
{
	public function html_factura_pdf_a4($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal) {

		if(empty($contribuyente->logo_350)) {
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		$ubigeo_ubicacion = '';
		if($ubigeo) {
			$ubigeo_ubicacion = substr($ubigeo->distrito, 2).', '.substr($ubigeo->provincia, 2).', '.substr($ubigeo->departamento, 2);
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

		$html = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
					line-height: 5px;
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
					font-size: 9px;
					font-weight: 700;
					width: 90%;
				}
				.box-table-5 p{
					font-size: 9px;
					font-weight: 700;
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
					font-weight: 700;
					width:auto;
				}
				.table-3.table-cuentas {
					border-collapse: separate !important;
					border-spacing: 0;
					border-top: solid #000 .5px;
					border-left: solid #000 .5px;
					border-right: solid #000 .5px;
					border-bottom: solid #000 0px!important;
				}
				.table-3.table-cuentas td, .table-3.table-cuentas th {
					border-left: .5px solid #000;
					padding: 5px!important;
				}
			</style>

			<body>
				<table class="table-main-head">
					<tbody>
						<tr>
							<td class="text-head spacing-lg">
								<img >
								<p>JR.COYLLUR 280 -ZARATE</p>
								<p>Telf.:999667832</p>
								<p>www.grupovisualcont.com</p>
							</td>
							<td>
								<table class="table-head bordered">
									<tr>
										<td colspan="3" class="text-center font-weight-bold">R.U.C.: 20000000001</td>
									</tr>
									<tr class="bg-main">
										<td colspan="3" class="text-center font-weight-bold">Facturación Electrónica</td>
									</tr>
									<tr>
										<td colspan="3" class="text-center">Nro. F001-0000004</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
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
								<p>'.$lista_docs_referencia.'</p> 
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table-3 bordered mt-30">
					<tbody>
						<tr>
							<td class="spacing-lg">
								<p>
									<span class="font-weight-bold">RUC: </span> '.$cliente->num_doc.'
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
								<p><span class="font-weight-bold">Placa N°: </span> </p>
								<p>'.$nro_placa.'</p>
							</td>
						</tr>
						<tr>
							<td class="spacing-lg">
								<p><span class="font-weight-bold">Dirección: </span> '.ucwords(strtolower($cliente->direccion_fiscal)).'</p>
							</td>
							<td>
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
							<th>Total</th>
						</tr>
						<tr class="border-left">
							<td>1</td>
							<td>3i47yMdQ3Y</td>
							<td>prueba detraccion</td>
							<td>UND</td>
							<td>1.00</td>
							<td>50.00</td>
							<td>50.00</td>
						</tr>
						<tr class="border-left">
							<td>2</td>
							<td>3i47yMdQ3Y</td>
							<td>prueba detraccion</td>
							<td>UND</td>
							<td>1.00</td>
							<td>50.00</td>
							<td>50.00</td>
						</tr>
						<tr class="border-left">
							<td>3</td>
							<td>3i47yMdQ3Y</td>
							<td>prueba detraccion</td>
							<td>UND</td>
							<td>1.00</td>
							<td>50.00</td>
							<td>50.00</td>
						</tr>
						<tr>
							<td colspan="7" class="text-uppercase" style="border-top: solid #000 .5px;">SON: CINCUENTA Y NUEVE CON 00/100 SOLES</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0"  class="table-foot">
					<tr>
						<td rowspan="3" valign="middle" align="left" class="box-table-5">
							<p>Detracción en Moneda Soles</p>
							<table class="table-5">
								<tbody>
									<tr>
										<td>
											<p>% Detracción: 10.00</p>
										</td>
										<td>
											<p>Monto Detracción: 5.90</p>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<p><span class="font-weight-bold">Descripción:</span> OPERACION SUJETA AL SISTEMA DE PAGO OBLIGACIONES TRIBUTARIAS DEL BANCO DE LA NACION 212313515152222
											</p>
										</td>
									</tr>
								</tbody>
							</table>  
						</td>
						<td class="sub-total">Sub Total </td>
						<td class="sub-total">S/ 50.00</td>
					</tr>
					<tr>
						<td class="sub-total">IGV 18 % </td>
						<td class="sub-total">S/ 9.00</td>
					</tr>
					<tr>
						<td class="sub-total">Total </td>
						<td class="sub-total">S/ 59.00</td>
					</tr>
				</table>
				<table style="margin-top: 5px">
					<tbody>
						<tr>
							<td class="spacing-xm">
								<p style=" font-size: 11px;font-weight: 700;margin: 0; padding: 0;">CUENTAS CORRIENTES</p>
								<table class="table-3 table-cuentas">
									<tbody>
										<tr>
											<th>Banco</th>
											<th>Moneda</th>
											<th>CTA CTE</th>
											<th>CCI</th>
										</tr>
										<tr class="text-center">
											<td>Banco de crédito </td>
											<td>Soles</td>
											<td>CTA CORRIENTE</td>
											<td>4</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td  rowspan="4" valign="middle" align="right" class="text-center codigo-qr">
								<!-- Codigo qr-->
								<img src="" width="100px" alt="">
								<p>Representación impresa de la
									Factura Electrónica, Autorizado
									mendiante la Resolución N° 0340050007579
									para consultar el documento visita
										http://grupovisualcont.com/</p>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
					</tbody>
				</table>
			</body>
			</html>
		';

		return $html;
	}
	
}
?>