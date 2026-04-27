<?php
class TemplatenotapedidoController extends ListaplantillaspdfController
{

	public function get_html_plantilla_a4_1($data) { 
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
		}

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
            $tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
        } else if ($cliente->id_tipodocidentidad == '1'){
            $tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
        if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
        } else if ($tipo_documento->id_tipodoc_electronico == '88'){
            $nombre_documento_titular = 'Cotización';
        } 

		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = $documento->nro_otr_comprobante;
		} else {
			$orden_compra = '';
		}

	
		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = $documento->transporte_nro_placa;

		} else {
			$nro_placa = '';
		}
	


		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}

		// Resumen de ventas
		$resumen = '
		<tr>
			<td>Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td>ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td>IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td>Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';

		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p class="font-weight">Observación:</p>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		} 
	
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = ' ';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}

		
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta->nombre_banco.'</td>
					<td>'.$tipo_cuenta_banco.'</td>
					<td>'.$cuenta->nro_cuenta.'</td>
					<td>'.$cuenta->cci.'</td>
				</tr>
			';
		}
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</title>
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
				font-size: 14px!important;
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
			table {
				font-size: 14px!important;
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
			.table-head{
				border: solid #5f5f5f 1px;
				padding: 15px 5px;
			}
			.table-head {
				border: solid #5f5f5f 1px;
				padding: 2em 1em;
				text-align: center;
				text-transform: uppercase;
			}
			.table-head p{
				line-height: 2;
			}
			.tb_resumen_totales td {
				padding: 5px 10px;
				border: none;
				text-aling: left:
			}

			.resumen_totales {
				height: 230px;
			}
			.table-cuentas{
				margin: auto;
			}
			.table-cuentas td{
				padding: 3px 5px;
			}
			.table-main-products {
				border: 1px solid #000;
			}
			.table-main-products td{
				border: none!important;
			}
		</style>
		<body>
			<div class="masthead w-100">
				<div class="col-3">
					<img src="'.$img_logo.'" '.$style_logo.'>
				</div>
				<div class="col-5 text-center">
					<h5 class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</h5>
					<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.'</p>
					<p><i class="flaticon-placeholder-1 mr-2"></i>'.$ubigeo_ubicacion.'</p>
					<p><i class="flaticon-call mr-2"></i>'.$sucursal->telefono.'</p>
					<p><i class="flaticon-envelope mr-2"></i>'.$sucursal->email.'<p>
					<p><i class="icon-sphere3 mr-2"></i>'.$sucursal->sitio_web.'</p>
				</div>
				<div class="col-4 p-2">
					<div class="table-head">
						<p>R.U.C. '.$contribuyente->ruc.'</p>                    
						<p style="font-weight: 600;">'.$nombre_documento_titular.'</p>
						<p>Nro. '.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</p>
					</div>
				</div>
			</div>
			<div class="single-date-client mb-3 mt-5">
				<p><span class="font-weight-bold">'.$tipo_doc_cliente.':</span> '.ucwords($cliente->razon_social).'</p>
				<p><span class="font-weight-bold">'.$tipo_doc.':</span> '.$cliente->num_doc.'</p>
				'.$direccion_fiscal.'
			</div>	
			<table class="table mt-1 text-center">
				<thead>
					<tr class="text-uppercase">
						<th>Fecha de emisión</th>
						<th>Condición de Pago</th>
						<th>Tipo moneda</th>
						<th>Número de guía</th>
						<th>Orden de Compra</th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center"> 
						<td>'.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</td>
						<td>'.$nombre_condicion_pago.'</td>
						<td>'.$sunatmoneda->nombre.'</td>
						<td>'.$num_guia.'</td>
						<td>'.$orden_compra.'</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-main-products">
				<tbody>
					<tr class="text-uppercase">
						<th>Cant.</th>
						<th width="500px">Descripción</th>
						<th  width="200px">Precio</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th  width="200px">Importe</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="6" class="text-uppercase text-center font-weight-bold" style="border: 1px solid #000!important">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
					</tr>
				</tbody>
			</table>

			<table  class="table-no-border">
				<tbody>
					<tr>
						<td style="vertical-align: top;">
							<table  class="table-no-border">
								<tbody>
									<tr>
										<th style="width:130px;">
											<img style="width: 120px; margin-bottom: 11px;" src="'.$ruta_qr.'" />
										</th>
										<td class="texto_general">
											';
											if(!empty($nota_doc)) {
											$html = $html.'
											'.$nota_doc.'
											';
											}
											$html = $html.'
											<p class="mb-1 mt-2"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$ruta_consulta_documento.'</p>
											<p><span class="font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
											<p class="mb-3">Representación Impresa de la Factura Electrónica</p>
										</td>
									</tr>
									<tr>
									 	<td colspan="2">'.$aviso_texto_pruebas.'</td>
									</tr>
									
								</tbody>
							</table>
						</td>
						<td style="width:250px; vertical-align: top;">
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
			<div class="content-cuentas">
				<table class="table-3 table-cuentas pl-2">
					<tbody>
						<tr>
							<th>BANCO</th>
							<th>TIPO CTA</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						'.$html_cuentas_corrientes.'
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
	public function get_html_plantilla_a4_2($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
		}

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
            $tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
        } else if ($cliente->id_tipodocidentidad == '1'){
            $tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
        if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
        } else if ($tipo_documento->id_tipodoc_electronico == '88'){
            $nombre_documento_titular = 'Cotización';
        } 

		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = '<p><span class="font-weight-bold">Orden de Compra: </span> '.$documento->nro_otr_comprobante.'</p>';
		} else {
			$orden_compra = '';
		}

	
		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = $documento->transporte_nro_placa;

		} else {
			$nro_placa = '';
		}
	


		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}

		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="font-weight-bold">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="font-weight-bold">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="font-weight-bold">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="font-weight-bold">Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';

		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p class="font-weight-bold">Observación:</p>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-theme-default">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		} 
	
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = ' ';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}

		
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta->nombre_banco.'</td>
					<td>'.$tipo_cuenta_banco.'</td>
					<td>'.$cuenta->nro_cuenta.'</td>
					<td>'.$cuenta->cci.'</td>
				</tr>
			';
		}
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</title>
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
				font-size: 14px!important;
			}
			.table td, .table th {
				padding: .6rem;
				vertical-align: top;
				border-top: 1px solid #5f5f5f;
			}
			.table thead th {
				vertical-align: bottom;
				border-bottom: 1px solid #5f5f5f;
			}
			table {
				font-size: 14px!important;
			}
			
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 14px;
			}
			td, th {
				/*border: 1px solid #5f5f5f;*/
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
				height: 160px;
			}
		
			.table-head p{
				line-height: 2;
			}
			.tb_resumen_totales td {
				padding: 5px 10px;
				border: solid #5f5f5f 1px;
				border-top: none;
				text-aling: left:
			}

			.resumen_totales {
				height: 230px;
			}
			/* Tablas */
			.table-head {
				border: 1px solid #000;
			}
			.table-head td,  .table-head th{
				border: 1px solid #000;
			}
			.table-2 td {
				border: 1px solid #212529;
			}
			
			.table-3 {
				border: 1px solid #000;
			}
			.table-3 th {
				margin-bottom: 0;
				border: none;
			}
			.table-3 td {
				border: none;
			}
			.table-cuentas{
				margin: auto;
			}
			.table-cuentas td{
				padding: 5px;
			}
			.table-items-productos {
				border: 1px solid #000;
				margin: 0;
			}
			.table-items-productos th {
				margin-bottom: 0;
				border: none;
			}
			.table-items-productos td {
				border: none;
			}
			.table-client{ 	border: 1px solid #000; }
			.table-client th{ 	border: none; }
			.table-client td{ 	border-bottom: 1px solid #000; }
			.bg-main{
				background: #006cae;
			}
			.table-footer {
				border: 1px solid #000;
			}
			/* === Tabla Bordes === */
			.table-head{
				border: solid #5f5f5f 1px;
				padding: 2em 1em;
				text-align: center;
				text-transform: uppercase;
			
				
			}
				
		</style>
		<body>
			<div class="masthead w-100">
				<div class="col-3">
					<img src="'.$img_logo.'" '.$style_logo.'>
				</div>
				<div class="col-5 text-center">
					<h5 class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</h5>
					<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.' <br> '.$ubigeo_ubicacion.'<p>
					<p><i class="flaticon-call mr-2"></i>'.$sucursal->telefono.'</p>
					<p><i class="flaticon-envelope mr-2"></i>'.$sucursal->email.'<p>
					<p><i class="icon-sphere3 mr-2"></i>'.$sucursal->sitio_web.'</p>
				</div>
				<div class="col-4">
					<table class="table table-head bordered">
						<tr>
							<td colspan="3" class="text-center font-weight-bold">R.U.C.: '.$contribuyente->ruc.'</td>
						</tr>
						<tr class="bg-main text-white">
							<td colspan="3" class="text-center font-weight-bold">'.$nombre_documento_titular.'</td>
						</tr>
						<tr>
							<td colspan="3" class="text-center">Nro. '.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</td>
						</tr>
					</table>
				</div>
			</div>
		
			<table class="table table-2 bordered mt-30">
				<tbody class="text-center">
					<tr>
						<td class="text-center">
							<p class="font-weight-bold">Fecha de Emisión:</p>
							<p>'.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p> 
						</td>
						<td class="border-left text-center">
							<p class="font-weight-bold">Cond. Pago</p>
							<p>'.$nombre_condicion_pago.'</p> 
						</td>
						<td class="border-left text-center">
							<p class="font-weight-bold">Moneda</p>
							<p>'.$sunatmoneda->nombre.'</p> 
						</td>
						<td class="border-left text-center">
							<p class="font-weight-bold">Guía de Remisión N°</p>
							<p>'.(!empty($lista_docs_referencia)?$lista_docs_referencia:'-').'</p> 
						</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-client bordered mt-30">
				<tbody>
					<tr>
						<td class="spacing-lg">
							<p>
								<span class="font-weight-bold">'.$tipo_doc.': </span> '.$cliente->num_doc.'
							</p>
						</td>
						<td>
							'.$orden_compra.'
						</td>
					</tr>
					<tr>
						<td class="spacing-lg">
							<p><span class="font-weight-bold">'.$tipo_doc_cliente.': </span> '.ucwords($cliente->razon_social).'</p>
						</td>
						<td>
							<p><span class="font-weight-bold" style="display:none;">Placa N°: </span>'.$nro_placa.'</p>
						</td>
					</tr>
					<tr>
						<td class="spacing-lg" colspan="2">
							'.$direccion_fiscal.'
						</td>
						
					</tr>
				</tbody>
			</table>

			<table class="table table-items-productos">
				<tbody>
					<tr class="text-uppercase bg-main text-white">
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
						<td colspan="6" class="text-uppercase text-center font-weight-bold" style="border: 1px solid #000;">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
					</tr>
				</tbody>
			</table>
			<div class="resumen_totales">
				<div class="col-7 pt-3">
					'.$nota_doc.'
				</div>
				<div class="col-5 float-right">
					<table class="tb_resumen_totales float-right font-weight-bold">
						<tbody>
							'.$resumen.'
						</tbody>
					</table>
				</div>
			</div>
			<div class="footer-content w-100 mt-3">
				<div class="col-12">
					<table class="table table-footer">
						<tbody class="text-center">
							<tr>
								<td style="border-right: 0"> 
									<p class="mb-1"><span class="font-weight-bold">Consulte su documento electrónico en:</span> '.$ruta_consulta_documento.'</p>
									<p><span class="font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
									<p class="mb-1">Representación Impresa de la Factura Electrónica</p>
									
								</td>
								<td style="border-left: 0"><img src="'.$ruta_qr.'" width="90px" class="float-right"></td>
							</tr>	
							<tr>
								<td colspan="2">'.$aviso_texto_pruebas.'</td>
							</tr>
						</tbody>
					</table>	
				</div>
				<div class="col-12 pr-1 pl-1">
					<table class="table-3 table-cuentas">
						<tbody>
							<tr class="bg-main text-white">
								<th>BANCO</th>
								<th>TIPO CTA</th>
								<th>CTA CTE</th>
								<th>CCI</th>
							</tr>
							'.$html_cuentas_corrientes.'
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
	public function get_html_plantilla_a4_3($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 160px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

			//Tipo de documento
			$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
			if(!$tipo_documento) {
				$resp['respuesta'] = 'error';
				$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
				echo json_encode($resp);
				exit();
			}
	
			//Datos del documento 
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));
	
			if(!$documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
	
			//Numero de comprobante
			$zero_fill = new Themefacturapdf();
	
			//Fecha del documento
			if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
				$fecha_doc_show = $documento->fecha_registro;
			} else {
				$fecha_doc_show = $documento->fecha_comprobante;
			}
	
			//Condición de pago del documento
			$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
	
			$nombre_condicion_pago = '';
			if($condicion_pago) {
				
				$nombre_condicion_pago = '<p class="text-uppercase"><span class="font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
			}
	
			//Tipo de moneda
			$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));
	
			//Extraer sucursal
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
			
			//Datos del cliente 
			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
			if(!$cliente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
			if($cliente->id_tipodocidentidad == '6'){
				$tipo_doc = 'RUC';
				$tipo_doc_cliente = 'Razón social';
			} else if ($cliente->id_tipodocidentidad == '1'){
				$tipo_doc = 'DNI';
				$tipo_doc_cliente = 'Cliente';
			}
			//Titulo del doc
			$nombre_documento_titular = '';
			if($tipo_documento->id_tipodoc_electronico == '77'){
				$nombre_documento_titular = 'Nota de Venta';
			} else if ($tipo_documento->id_tipodoc_electronico == '88'){
				$nombre_documento_titular = 'Cotización';
			} 
			
			//Ubicación
			$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'
			<p><span class="font-weight-bold text-uppercase">Dirección:</span> <span class="text-uppercase">
			'.$cliente->direccion_fiscal.'</p></span>';
	
			$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
			if(!$ubigeo) {
				$ubigeo_ubicacion = '';
			} else {
				$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
			}
	
			//Num guía
			$num_guia = '';
			//Orden de compra
			if(!empty($documento->nro_otr_comprobante)) {
				$orden_compra = '
				<p><span class="font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
			} else {
				$orden_compra = '';
			}
	
		
			//N° de placa
	
			if(!empty($documento->transporte_nro_placa)) {
				$nro_placa = '
				<p><span class="font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
				';
			} else {
				$nro_placa = '';
			}
		
	
	
		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td class="font-weight-bold">'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-theme-default">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="color-theme-main">Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<h4 class="text-theme-default">Observación:</h4>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
			<tr>
				<td style="width:250px; font-family: Arial Narrow, Arial, sans-serif; font-size: 13px;" colspan="2">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</td>
			</tr>
			';
		}
	
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = '';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta de Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}
			
			$logo_cuenta_banco = $this->get_logo_banco($cuenta);
			$img_logo_banco = '<img src="'.$logo_cuenta_banco.'"  width="40px" class="img-cuenta mr-2">';
			$moneda_cuenta_banco = 'SOLES';
			if($cuenta->id_codigomoneda == 'USD') {
				$moneda_cuenta_banco = 'DÓLARES';
			}

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
			<div class="border-black mt-4 p-2">
				<p class="text-uppercase font-weight-bold">DEPÓSITO A '.$cuenta->nombre_titular.'</p>
				<p>'.$tipo_cuenta_banco.'</p>
				<p>'.$img_logo_banco.' '.$cuenta->nombre_banco.' - '.$moneda_cuenta_banco.': N° '.$cuenta->nro_cuenta.'. CCI: '.$cuenta->cci.'</p>
			</div>
		
			';
			
		}
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		//Dirección
		if(!empty($sucursal->direccion)) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.'</p>';
		} else {
			$direccion_empresa = '';
		}
	
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$this->zero_fill($documento->numero_comprobante, 6).'</title>
		</head>
		<body>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0px;
				}
				body{
					position: relative;
					font-family: "Cairo", sans-serif;
					font-size: 14px!important;
				}
				
				h1, h2, h3, h4, h5, h6, table {
					font-size: 14px!important;
				}
				
				/*body::before{
					content: " ";
					position: absolute;
					top: 25%;
					left: 10%;
					width: 80%;
					height: 700px;
					background-image: url('.$img_logo.');
					background-size: cover;
					background-repeat: no-repeat;
					background-position: center center;
					z-index: -1;
					margin: auto;
					opacity: .5;
				}*/
				table{
					width: 100%
				}
				th{
					text-align: center;
				}
				.table-head td, .table-head th {
					border: 1px solid #000;
					padding: 10px;
				}
				.table-main-head td {
					text-align: center;
				}
				p{
					margin: 0;
					padding: 0;
				}
				.box-content{
					border: 1px solid #000;
					width: 100%;
					height: 160px;
				}
				.col-3{
					width: 25%;
					float: left;
				}
				.col-4{
					width: 33.333333333333%;
					float: left;
				}
				.col-6 {
					width: 50%;
					float: left;
				}
				.col-8{
					width: 66.666667%;
					float: left;
				}
				.header-title{
					width: 37%;
					float: left;
				}
				.header-title p{
					font-size: 15px;
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
					height: 200px;
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
					font-size: 14px;
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
			</style>
			<div class="masthead w-100">
				<div class="float-left mr-5 header-logo">
					<img src="'.$img_logo.'" '.$style_logo.'>
				</div>
				<div class="header-title">
					<h5 class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</h5>
					'.$direccion_empresa.'
					<p><i class="flaticon-placeholder-1 mr-2"></i>'.$ubigeo_ubicacion.'</p>
					<p><i class="flaticon-call mr-2"></i>'.$sucursal->telefono.'</p>
					<p><i class="flaticon-envelope mr-2"></i>'.$sucursal->email.'<p>
					<p><i class="icon-sphere3 mr-2"></i>'.$sucursal->sitio_web.'</p>
				</div>
				<div class="tabla-head ml-3">
					<table class="table-head table">
						<tr class="mt-2">
							<td  class="text-center font-weight-bold text-uppercase">R.U.C.: '.$contribuyente->ruc.'</td>
						</tr>
						<tr class="bg-main">
							<td  class="text-center bg-theme-default font-weight-bold text-uppercase">'.$nombre_documento_titular.'</td>
						</tr>
						<tr>
							<td  class="text-center">Nro. '.$this->zero_fill($documento->numero_comprobante, 6).'</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="box-content pt-2 mt-3">
				<div class="col-6">
					<p><span class="font-weight-bold text-uppercase">'.$tipo_doc.':</span> <span class="text-uppercase">'.ucwords($cliente->num_doc).'</span></p>
					<p><span class="font-weight-bold text-uppercase">'.$tipo_doc_cliente.':</span> <span class="text-uppercase">'.$cliente->razon_social.'</span></p>
					'.$direccion_fiscal.'
					'.$nombre_condicion_pago.'
					<p><span class="font-weight-bold text-uppercase">Vendedor:</span> <span class="text-uppercase">'.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</span></p>
				</div>
				<div class="col-6">
					<p><span class="font-weight-bold text-uppercase">F. Emisión:</span> <span class="text-uppercase">'.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</span></p>
					<p><span class="font-weight-bold text-uppercase">Tipo de Moneda:</span> <span class="text-uppercase">'.$sunatmoneda->nombre.'</span></p>
					'.$orden_compra.'
					'.$num_guia.'
					'.$nro_placa.'
				</div>
			</div>
			<div class="table-content">
				<table class="table-main-head mt-4">
					<tbody>
						<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
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
							<td colspan="6" class="text-uppercase text-center">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
			</div>
			<footer class="mt-5">
				<div class="col-8 footer-info">
					<p class="font-weight-bold text-uppercase">Consulte su documento electrónico en:</p>
					<img src="'.$ruta_qr.'" width="40px" class="float-left mr-3 mb-3">
					<p>VENDEDOR: '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
					Representación impresa de la FACTURA ELECTRÓNICA</p>
					'.$html_cuentas_corrientes.'
				</div>
				<div class="col-4">
					<div class="mt-5 pt-2">
						<table class="table-footer font-weight-bold text-uppercase">
							<tr>
								<td colspan="2" class="text-center font-weight-bold text-uppercase"></td>
							</tr>
							<tr class="bg-main">
								<td  colspan="2"  class="text-center bg-theme-default font-weight-bold">Resumen</td>
							</tr>
							'.$resumen.'
						</table>
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
	public function get_html_plantilla_a4_4($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}
	
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 120px; height: 120px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 200px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			
			$nombre_condicion_pago = '<p><span class="text-primary font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
		}

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
			$tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
		} else if ($cliente->id_tipodocidentidad == '1'){
			$tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
		if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
		} else if ($tipo_documento->id_tipodoc_electronico == '88'){
			$nombre_documento_titular = 'Cotización';
		} 
		
		
		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="text-primary font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

	

		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = '
			<p><span class="text-primary font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
		} else {
			$orden_compra = '';
		}

	
		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<p><span class="text-primary font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
			';
		} else {
			$nro_placa = '';
		}
	


		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-primary text-right">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-primary text-right">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="text-primary text-right">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-primary text-right">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-primary text-right">Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';

		//Observación
		$nota_doc = '';
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p><strong class="text-primary">Observación:</strong> '.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-theme-default">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = '<p class="text-primary font-weight-bold mb-1 mt-1">Cuentas:</p>';

		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta de Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}

			$logo_cuenta_banco = $this->get_logo_banco($cuenta);
			$img_logo_banco = '<img src="'.$logo_cuenta_banco.'"  width="40px" class="img-cuenta mr-2">';


			$html_cuentas_corrientes = $html_cuentas_corrientes.'
			<div class="col-4 p-0 m-0" style="height: 70px">
				<p class="tabla_cuentas">'.$img_logo_banco.' <span class="font-weight-bold">Titular:</span> '.$cuenta->nombre_titular.'</p>
				<p class="tabla_cuentas"><span class="font-weight-bold">N° cuenta:</span> '.$cuenta->nro_cuenta.' - <span class="font-weight-bold">CCI:</span> '.$cuenta->cci.'</p>
			</div>
			';
		}
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = $documento->nro_otr_comprobante;
		} else {
			$orden_compra = '';
		}
		
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$this->zero_fill($documento->numero_comprobante, 6).'</title>
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
				font-size: 14px!important;
			}
			
			h1, h2, h3, h4, h5, h6, table {
				font-size: 14px!important;
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
			}
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
				height: 140px;
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
				height: 300px;
			}
			/*footer {
				position: absolute;
				bottom: 0%;
				border-top: 1px solid #007bff;
				width: 100%;
			}*/
		</style>
		<body>
			<header>
				<h4 class="pt-2 float-left" style="font-size: 20px!important">
                  '.$nombre_documento_titular.'
				</h4>
				<h4 class="pt-2 float-right" style="font-size: 20px!important">'.$this->zero_fill($documento->numero_comprobante, 6).'</h4>
			</header>
			<div class="masthead">
				<div class="col-6  mb-4">
					<h4 class="text-primary text-uppercase font-weight-bold mb-2">'.$contribuyente->nombre_comercial.'</h4>
					<p>'.$sucursal->direccion.'</p>
					<p>'.$ubigeo_ubicacion.'</p>
					<p><i class="flaticon-call text-primary mr-2"></i>Telf.: '.$sucursal->telefono.'</p>
					<p><i class="flaticon-envelope mr-2 text-primary"></i>'.$sucursal->email.'</p>';
					if(!empty($sucursal->sitio_web)) {
						$html = $html.'
						<p><i class="flaticon-placeholder-1 mr-2 text-primary"></i>'.$sucursal->sitio_web.'</p>';
					}
					$html = $html.'
					
				</div>
				<div class="col-6 pl-5 text-center">
					<img '.$style_logo.' src="'.$img_logo.'" />
					<div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
					<h4 class="text-primary text-uppercase  mb-2 mt-4 text-center font-weight-bold" style="font-size: 18px!important">R.U.C.  '.$contribuyente->ruc.'</h4>
				</div>
			</div>
			<div id="servicios" class="contenedor borde-primary">
				<div class="single-date" id="columna1">
				   <p><span class="text-primary font-weight-bold">'.$tipo_doc_cliente.':</span> '.ucwords($cliente->razon_social).'</p>
				   <p><span class="text-primary font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
				   '.$orden_compra.'
				</div>			
				<article class="single-date">
					<p><span class="text-primary font-weight-bold">'.$tipo_doc.':</span> '.$cliente->num_doc.'</p>
					'.$direccion_fiscal.'
                    '.$num_guia.'
				</article>			
				<article class="single-date text-left">
					<p><span class="text-primary font-weight-bold">Tipo de modena:</span> '.$sunatmoneda->nombre.'</p>
					'.$nombre_condicion_pago.'
                    '.$nro_placa.'
				</article>			
			</div>
			<section>
				<table class="table-main-head">
					<tbody>
						<tr class="bg-primary text-white text-uppercase">
							<th>Cant.</th>
							<th width="500px">Descripción</th>
							<th>Precio Unitario</th>
							<th>UNID/MED </th>
							<th>AFECT.IGV</th>
							<th>Importe</th>
						</tr>
						<tr>
							'.$items_detalle_html.'
						</tr>
						<tr>
							<td colspan="6" class="text-uppercase text-center">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				<div class="resumen_totales">
					<div class="col-7">
						<img src="'.$ruta_qr.'" width="100px" class="float-left mr-3 mt-3 mb-3">
						<p class="mb-1 mt-3"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</span> '.$ruta_consulta_documento.'</p>
						<p><span class="text-primary font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
						<p class="mb-3">Representación Impresa de la Factura Electrónica</p>
						'.$aviso_texto_pruebas.'
						<div class="w-100 mt-3 mb-2">
							'.$nota_doc.'
						</div>
					</div>
					<div class="col-45 float-right">
						<table class="tb_resumen_totales float-right">
							<tbody>
								'.$resumen.'
							</tbody>
						</table>
					</div>
				</div>
			</section>
			<footer class="w-100 pt-2" style="border-top:  1px solid #dddddd;">
				'.$html_cuentas_corrientes.'
			</footer>
		</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
	public function get_html_plantilla_a4_5($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 180px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = '<div class="single-date">
			<p class="text-primary font-weight-bold">Cond. de pago:</p>
			<p>'.$condicion_pago->condicionpago.'</p>
			</div>';
		}

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
			$tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
		} else if ($cliente->id_tipodocidentidad == '1'){
			$tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
		if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
		} else if ($tipo_documento->id_tipodoc_electronico == '88'){
			$nombre_documento_titular = 'Cotización';
		} 
		
		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="text-primary font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = '
			<p><span class="text-primary font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
		} else {
			$orden_compra = '';
		}

	
		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<p><span class="text-primary font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
			';
		} else {
			$nro_placa = '';
		}
	


		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom">Gravada: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_gravadas).'</span> </p>
		</div>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Inafecto: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_inafecta).'</span> </p>
			</div>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Exonerado: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_exoneradas).'</span> </p>
			</div>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Gratuito: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_gratuitas).'</span> </p>
			</div>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">Exportación: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_exportacion).'</span> </p>
			</div>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
				<p class="border-invoice-bottom">ICBPER: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_icbper).'</span> </p>
			</div>
			';
		}

		$resumen = $resumen.'
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom">IGV ('.$documento->porcentaje_igv.'%): <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_igv).'</span> </p>
		</div>
		';

		$resumen = $resumen.'
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom">Descuento: <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total_igv).'</span> </p>
		</div>
		';

		$resumen = $resumen.'
		<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
			<p class="border-invoice-bottom"><span class="bg-primary text-white">Total:</span> <span class="price ml-5">'.$sunatmoneda->simbolo.' '.custom_money_format($documento->total).'</span> </p>
		</div>

		';

		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<h4 class="bg-primary text-white">Observación:</h4>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-theme-default">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		} 
	
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = '';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}

			$logo_cuenta_banco = $this->get_logo_banco($cuenta);
			$img_logo_banco = '<img src="'.$logo_cuenta_banco.'"  width="40px" class="img-cuenta mr-2">';

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$img_logo_banco.'</td>
					<td>'.$tipo_cuenta_banco.'</td>
					<td>'.$cuenta->nro_cuenta.'</td>
					<td>'.$cuenta->cci.'</td>
				</tr>
			
			';
		}
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$html = '	
		<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
				<title>'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</title>
			</head>
			<body>
				<style>
					@import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap");
					body {
						font-family: "Open Sans",Tahoma,Geneva,sans-serif;
						position: relative;
						font-size: 14px!important;
					}
					
					table {
						font-size: 14px!important;
					}
					ul {
						list-style-type: circle;
						margin-right: 0!important;
						padding: 0 0 0 10px;
					}
					td, th {
						border-bottom: 1px solid #dddddd;
						padding: 10px 2px 10px 10px;
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
    					padding-top: 2em;
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
						height: 270px;
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
					.table-cuentas {
						float: right;
						font-size: 12px;
					}
					.table-cuentas td, .table-cuentas th {
						padding: 5px;
					}
				</style>
				<div class="masthead">
					<img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p-2.png" class="img-header">
					<div class="header-main">
						<div class="col-4">
							<img src="'.$img_logo.'" '.$style_logo.'>
							<h4 class="font-weight-light text-primary mt-3">'.$contribuyente->nombre_comercial.'</h4>
							<p>R.U.C.: '.$contribuyente->ruc.'</p>
							<p>'.$sucursal->direccion.'</p>
							<p>'.$ubigeo_ubicacion.'</p>
							<p><i class="flaticon-call text-primary mr-2"></i>Telf.: '.$sucursal->telefono.'</p>
							<p><i class="flaticon-envelope mr-2 text-primary"></i>'.$sucursal->email.'</p>';
							if(!empty($sucursal->sitio_web)) {
								$html = $html.'
								<p><i class="flaticon-placeholder-1 mr-2 text-primary"></i>'.$sucursal->sitio_web.'</p>';
							}
							$html = $html.'
						</div>			
						<div class="pl-1 col-8">
							<h4 class="pt-2 font-weight-light text-uppercase" style="line-height: 1.5">'.$nombre_documento_titular.': <br> <span  class="bg-primary text-white mt-2">'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</span></h4> 
							<div id="date_invoice">
								<div class="single-date">
									<p class="text-primary font-weight-bold">Fecha emisión:</p>
									<p> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
								</div>	
								'.$nombre_condicion_pago.'
								
								<div class="single-date">
									<p class="text-primary font-weight-bold">Moneda:</p>
									<p>'.$sunatmoneda->nombre.'</p>
								</div>
								'.$num_guia.'
								'.$orden_compra.'
								'.$nro_placa.'
							</div>		
						</div>	
					</div>
				</div> 
				<div class="single-date-client mb-3 mt-2" style="height: 100px">
					<p><span class="text-primary font-weight-bold">'.$tipo_doc_cliente.':</span> '.ucwords($cliente->razon_social).'</p>
					<p><span class="text-primary font-weight-bold">'.$tipo_doc.':</span> '.$cliente->num_doc.'</p>
					 '.$direccion_fiscal.'
				</div>	
				<div class="table-invoice mt-2 pt-2">
					<table class="table-main-head mt-3">
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
								<td colspan="6" class="text-uppercase text-center">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
							</tr>
						</tbody>
					</table>
					<div class="invoice-total">
						<div class="col-4 pt-2">
						'.$nota_doc.'
						</div>
						<div class="col-8">
						'.$resumen.'
						</div>
					</div>
				</div>
				
				<footer>
					<div class="sub-footer">
						<div class="col-6">
							<img src="'.$ruta_qr.'" width="80px" class="float-left mr-3 mb-3">
							<p><span class="text-primary font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
							<p class="mb-3">Representación Impresa de la Factura Electrónica</p>
							'.$aviso_texto_pruebas.'
						</div>
						<div class="col-6 pl-2">
							<table class="table-3 table-cuentas pl-2">
								<tbody>
									<tr>
										<th>BANCO</th>
										<th>TIPO CTA</th>
										<th>CTA CTE</th>
										<th>CCI</th>
									</tr>
									'.$html_cuentas_corrientes.'
								</tbody>
							</table>
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
	public function get_html_plantilla_a4_6($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 120px; height: 120px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 200px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			$nombre_condicion_pago = '<p><span class="text-theme-default font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.' </p>';
		}
		
		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
			$tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
		} else if ($cliente->id_tipodocidentidad == '1'){
			$tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
		if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
		} else if ($tipo_documento->id_tipodoc_electronico == '88'){
			$nombre_documento_titular = 'Cotización';
		} 
		
		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="text-theme-default font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';
		

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = '
			<p><span class="text-theme-default font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
		} else {
			$orden_compra = '';
		}

	
		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<p><span class="text-theme-default font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
			';
		} else {
			$nro_placa = '';
		}
	


		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td class="font-weight-bold">'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-theme-default text-right">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default text-right">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-right">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-right">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default text-right">Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<h4 class="text-theme-default font-weight-bold">Observación:</h4>
			<p>'.$documento->nota.'</p>
			';
			$margin_top = 'mt-5';
		} else {
			$nota_doc = '';
			$margin_top = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
				<p class="font-weight-bold text-theme-default">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</p>
			';
		}
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = '';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta de Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}

			$logo_cuenta_banco = $this->get_logo_banco($cuenta);
			$img_logo_banco = '<img src="'.$logo_cuenta_banco.'"  width="40px" class="img-cuenta mr-2">';

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$img_logo_banco.'</td>
					<td>'.$tipo_cuenta_banco.'</td>
					<td>'.$cuenta->nro_cuenta.'</td>
					<td>'.$cuenta->cci.'</td>
				</tr>
			
			';
		}
		//Datos del vendedor
		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		$html = '
				
		<html lang="es">
   		 	<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
				<title>'.$this->zero_fill($documento->numero_comprobante, 6).'</title>
				<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
    		</head>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0px;
				}

				body{
					position: relative;
					font-size: 14px!important;
				}
				
				h1, h2, h3, h4, h5, h6, table {
					font-size: 14px!important;
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
					height: 50px;
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
				.masthead::before{
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
				}
			
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
					padding: 10px;
					width: 33.333333333333%;
				}
				.table-main-head{
					width: 100%;
					border-collapse: collapse;
				}

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
					font-size: 12px;
					float: right;
				}
				.table-cuentas td, .table-cuentas th {
					padding: 5px;
				}
	
				.tb_resumen_totales td{
					padding: 3px 5px;
				}
			</style>
			<body>
				<header class="borde-theme-default">
					<h4 class="pt-2 float-left" style="font-size: 20px!important">
						'.$nombre_documento_titular.'
					</h4>
					<h4 class="pt-2 float-right" style="font-size: 20px!important">'.$this->zero_fill($documento->numero_comprobante, 6).'</h4>
				</header>
				<div class="masthead">
					<div class="col-6  pl-2 pt-3 mb-4">
					<h4 class="text-theme-default font-weight-bold text-uppercase mb-2">'.$contribuyente->nombre_comercial.'</h4>
					<p>'.$sucursal->direccion.'</p>
					<p>'.$ubigeo_ubicacion.'</p>
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: '.$sucursal->telefono.'</p>
					<p><i class="flaticon-envelope mr-2 text-theme-default"></i>'.$sucursal->email.'</p>';
					if(!empty($sucursal->sitio_web)) {
						$html = $html.'
						<p><i class="flaticon-placeholder-1 mr-2 text-theme-default"></i>'.$sucursal->sitio_web.'</p>';
					}
					$html = $html.'
					</div>
					<div class="col-6  pt-3 pl-5 text-center">
						<img '.$style_logo.' src="'.$img_logo.'" />
						<div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
						<h4 class="text-theme-default text-uppercase  mb-2 mt-4 text-center  font-weight-bold" style="font-size: 18px!important">R.U.C.  '.$contribuyente->ruc.'</h4>
					</div>
				</div>
				<div id="servicios" class="contenedor borde-primary">
					<div class="single-date" id="columna1">
						<p><span class="text-theme-default font-weight-bold">'.$tipo_doc_cliente.':</span> '.ucwords($cliente->razon_social).'</p>
						<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
						'.$orden_compra.'
					</div>			
					<article class="single-date">
						<p><span class="text-theme-default font-weight-bold">'.$tipo_doc.':</span> '.$cliente->num_doc.'</p>
						'.$nombre_condicion_pago.'
					</article>			
					<article class="single-date text-left">
						<p><span class="text-theme-default font-weight-bold">Tipo de modena:</span> '.$sunatmoneda->nombre.'</p>
						'.$num_guia.'
						'.$nro_placa.'
					</article>			
				</div>
				<section>
					<table class="table-main-head mt-4">
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
								<td colspan="6" class="text-uppercase text-center">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
							</tr>
						</tbody>
					</table>
					<div class="col-6" style="height:140px!important">
						'.$nota_doc.'
					</div>
					<div class="col-6 float-right">
						<div class="resumen_totales">
							<table class="tb_resumen_totales float-right mb-5">
								<tbody class="font-weight-bold text-right">
									'.$resumen.'
								</tbody>
							</table>
						</div>
					</div>
				</section>
				<footer class="'.$margin_top.'">
					<div class="col-6">
						<div class="single-footer-wight box-main mt-2 mb-2">
							<img src="'.$ruta_qr.'" width="80px" class="float-left mr-3  mb-3">
							<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
							<p class="mb-1">Representación Impresa de la Factura Electrónica</p>
							'.$aviso_texto_pruebas.'
						</div>
					</div>
					<div class="col-6">
						<table class="table-3 table-cuentas">
							<tbody>
								<tr class="bg-theme-default  text-white text-uppercase">
									<th>Banco</th>
									<th>Moneda</th>
									<th>CTA CTE</th>
									<th>CCI</th>
								</tr>
								'.$html_cuentas_corrientes.'
							</tbody>
						</table>
					</div>
				</footer>
			</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
	public function get_html_plantilla_a4_7($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 90px; height: 90px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 200px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			
			$nombre_condicion_pago = '<p><span class="text-theme-default font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
		}

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
			$tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
		} else if ($cliente->id_tipodocidentidad == '1'){
			$tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
		if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
		} else if ($tipo_documento->id_tipodoc_electronico == '88'){
			$nombre_documento_titular = 'Cotización';
		} 

		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="text-theme-default font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = '
			<p><span class="text-theme-default font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
		} else {
			$orden_compra = '';
		}


		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<p><span class="text-theme-default font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
			';
		} else {
			$nro_placa = '';
		}



		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];

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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td width="150px">'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="text-theme-default">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="text-theme-default">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="text-theme-default">Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<h4 class="text-theme-default font-weight-bold">Observación:</h4>
			<p>'.$documento->nota.'</p>
			<hr>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->modalidad == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="font-weight-bold text-theme-default">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
		
			';
		}
	
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = '';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}

			$logo_cuenta_banco = $this->get_logo_banco($cuenta);
			$img_logo_banco = '<img src="'.$logo_cuenta_banco.'"  width="40px" class="img-cuenta mr-2">';

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$img_logo_banco.'</td>
					<td>'.$tipo_cuenta_banco.'</td>
					<td>'.$cuenta->nro_cuenta.'</td>
					<td>'.$cuenta->cci.'</td>
				</tr>
			';
		}

		//Datos del vendedor
		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
			<title>'.$this->zero_fill($documento->numero_comprobante, 6).'</title>
		</head>
		<body>
			<style>
				[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
					margin-left: 0;
				}
				body{
					font-family: "Montserrat", sans-serif;	
					font-size: 14px!important;
				}
				
				h1, h2, h3, h4, h5, h6, table {
					font-size: 14px!important;
				}
	
				footer p{
					margin: 0;
					padding: 0;
					font-size: 14px;
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
				.text-theme-default{
					color: #77af18;
				}
				.text-theme-secundary{
					color: #333333;
				}
				.bg-theme-default{
					background: #77af18;
				}
				.table-cuentas {
					font-size: 10px;
				}
				.table-cuentas td, .table-cuentas th {
					padding: 5px;
				}
				.empresa-info{
					height: 250px;
					margin-bottom: 1.5em;
				}
				.tb_resumen_totales {
					width: auto;
				}
				.tb_resumen_totales td {
					padding: 8px 10px 8px 30px;
					font-weight: 700;
				}
				.resumen_totales {
					height: 150px;
				}
				
			</style>
			<div class="masthead">
				<div class="">
					<img src="'.$img_logo.'" class="mb-3" '.$style_logo.' alt="">
				</div>
				<div class="invoice-number">
					<h5 class="text-white text-uppercase font-weight-bold" style="font-size:18px!important;">'.$nombre_documento_titular.': '.$this->zero_fill($documento->numero_comprobante, 6).'</h5>
				</div>
			</div>
			<div class="empresa-info w-100">
				<div class="col-6">
					<h5 class="text-theme-default font-weight-bold text-uppercase mb-2 mt-3">'.$contribuyente->nombre_comercial.'</h5>
					<p>'.$sucursal->direccion.'</p>
					<p>'.$ubigeo_ubicacion.'</p>
					<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: '.$sucursal->telefono.'</p>
					<p><i class="flaticon-envelope mr-2 text-theme-default"></i>'.$sucursal->email.'</p>';
					if(!empty($sucursal->sitio_web)) {
						$html = $html.'
						<p><i class="flaticon-placeholder-1 mr-2 text-theme-default"></i>'.$sucursal->sitio_web.'</p>';
					}
					$html = $html.'
				</div>
				<div class="col-6">
					<div class="client-info">
						<p><span class="text-theme-default font-weight-bold">'.$tipo_doc_cliente.':</span>  '.ucwords($cliente->razon_social).'</p>
						<p><span class="text-theme-default font-weight-bold">'.$tipo_doc.':</span> '.$cliente->num_doc.'</p>
						'.$direccion_fiscal.'
					</div>
					<hr>
					<p><span class="text-theme-default font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
					'.$nombre_condicion_pago.'
					<p><span class="text-theme-default font-weight-bold">Tipo de modena:</span> '.$sunatmoneda->nombre.'.</p>
					'.$orden_compra.'
					'.$num_guia.'
					'.$nro_placa.'
				</div>
			</div>
		
			<section class="table-content">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="text-uppercase bg-theme-default  font-weight-bold text-white text-center">
							<th>Cant.</th>
							<th width="450px">Descripción</th>
							<th width="150px">Precio</th>
							<th>Unid/Med</th>
							<th>Afect. IGV</th>
							<th>Importe</th>
						</tr>
						
						'.$items_detalle_html.'
						
						<tr>
							<td colspan="6" class="text-uppercase text-center">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				<div class="invoice-price-footer">
					<div class="method-price">
						'.$nota_doc.'
					</div>
					<div class="resumen_totales">
						<table class="tb_resumen_totales float-right">
							<tbody class="text-right">
								'.$resumen.'
							</tbody>
						</table>
					</div>
				</div>
			</section>
			<footer class="mt-5">
				<div class="col-6">
					<div class="single-footer-wight box-main">
						<img src="'.$ruta_qr.'" width="75px" class="float-left mr-2  mb-3">
						<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> '.$ruta_consulta_documento.'</p>
						<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
						<p class="mb-3">Representación Impresa de la Factura Electrónica</p>
						'.$aviso_texto_pruebas.'
						<table class="table-3 table-cuentas mt-3">
							<tbody>
								<tr class="bg-theme-default  text-white text-uppercase">
									<th>Banco</th>
									<th>Moneda</th>
									<th>CTA CTE</th>
									<th>CCI</th>
								</tr>
								'.$html_cuentas_corrientes.'
							</tbody>
						</table>
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
	public function get_html_plantilla_a4_8($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 150px; height: 150px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 250px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
		}

		//Datos del documento 
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		//Numero de comprobante
		$zero_fill = new Themefacturapdf();

		//Fecha del documento
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_doc_show = $documento->fecha_registro;
		} else {
			$fecha_doc_show = $documento->fecha_comprobante;
		}

		//Condición de pago del documento
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));

		$nombre_condicion_pago = '';
		if($condicion_pago) {
			
			$nombre_condicion_pago = '<p><span class="font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
		}

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}
		if($cliente->id_tipodocidentidad == '6'){
			$tipo_doc = 'RUC';
			$tipo_doc_cliente = 'Razón social';
		} else if ($cliente->id_tipodocidentidad == '1'){
			$tipo_doc = 'DNI';
			$tipo_doc_cliente = 'Cliente';
		}
		//Titulo del doc
		$nombre_documento_titular = '';
		if($tipo_documento->id_tipodoc_electronico == '77'){
			$nombre_documento_titular = 'Nota de Venta';
		} else if ($tipo_documento->id_tipodoc_electronico == '88'){
			$nombre_documento_titular = 'Cotización';
		} 

		//Ubicación
		$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':'<p><span class="font-weight-bold">Dirección:</span> '.$cliente->direccion_fiscal.'</p>';

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		//Num guía
		$num_guia = '';
		//Orden de compra
		if(!empty($documento->nro_otr_comprobante)) {
			$orden_compra = '
			<p><span class="font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
		} else {
			$orden_compra = '';
		}


		//N° de placa

		if(!empty($documento->transporte_nro_placa)) {
			$nro_placa = '
			<p><span class="font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
			';
		} else {
			$nro_placa = '';
		}



		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];

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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
		<tr>
			<td class="font-weight-bold text-uppercase">Gravada:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</td>
		</tr>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold text-uppercase">Inafecto:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold text-uppercase">Exonerado:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold text-uppercase">Gratuito:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold text-uppercase">Exportación:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</td>
			</tr>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
			<tr>
				<td class="font-weight-bold text-uppercase">ICBPER:</td>
				<td class="text-right">
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</td>
			</tr>
			';
		}

		$resumen = $resumen.'
		<tr>
			<td class="font-weight-bold text-uppercase">IGV ('.$documento->porcentaje_igv.'%):</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="font-weight-bold text-uppercase">Descuento Total:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</td>
		</tr>
		';

		$resumen = $resumen.'
		<tr>
			<td class="font-weight-bold text-uppercase">Total a Pagar:</td>
			<td class="text-right">
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</td>
		</tr>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p class="text-theme-default font-weight-bold">Observación:</p>
			<p>'.$documento->nota.'</p>
			<hr>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '
			<p class="font-weight-bold text-theme-default">
				Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
		
			';
		}
	
		//Cuentas 
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html_cuentas_corrientes = '';
		foreach($lista_cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'CTA CRE';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'CTA AHR';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'CTA DETR';
			}

			//Icon de banco 
			$logo_cuenta_banco = $this->get_logo_banco($cuenta);
			$img_logo_banco = '<img src="'.$logo_cuenta_banco.'"  width="40px" class="img-cuenta mr-2">';

			$html_cuentas_corrientes = $html_cuentas_corrientes.'
			<div class="col-4 p-0">
				<div class="item-media">
					'.$img_logo_banco.'
					<p class="text-primary font-weight-bold">'.$tipo_cuenta_banco.'</p>
					<p><span class="text-primary font-weight-bold">N°: </span>'.$cuenta->nro_cuenta.'</p>
					<p><span class="text-primary font-weight-bold">CCI:</span> '.$cuenta->cci.'</p>
				</div>
			</div>
			';
		}
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

	
		$html = '
				
		<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
				<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
				<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
				<title>'.$this->zero_fill($documento->numero_comprobante, 6).'</title>
			</head>
			<body>
				<style>
					[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
						margin-left: 0px;
					}

					body{
						position: relative;
					}
					table{
						width: 100%
					}
					th{
						text-align: center;
					}
					td, th {
						border-bottom: 1px solid #dddddd;
						padding: 8px;
					}
					.table-main-head td, .table-main-head th{
						padding: 5px;
					}
					p{
						margin: 0;
						padding: 0;
					}
					#invoice-total {
						/*
						float: right;
						width: 550px;
						*/
						margin-top: 1em;
						border: 1px solid #e1e2e3;
						border-left: 3px solid #e1e2e3;
						border-right: 3px solid #e1e2e3;
						border-radius: 15px;
						
					}
					.border-top{
						border-top: 1px dashed #e1e2e3!important;
					}
					.box-content {
						border: 1px solid #e1e2e3;
						border-radius: 15px;
						padding: 5px 15px;
						border-left: 3px solid #e1e2e3;
						border-right: 3px solid #e1e2e3;
						width: 100%;
					}
					/*.single-cliente .box-content {
						height: 140px;
					}*/
					.bg-secondary {
						background-color: #e1e2e3!important;
					}
					.col-4{
						width: 25%;
						float: left;
					}
					.col-6 {
						width: 50%;
						float: left;
					}
					.col-6-r {
						width: 50%;
						float: right;
					}
					.col-8{
						width: 66.666667%;
						float: left;
					}
					.col-8-r{
						width: 66.666667%;
						float: right;
					}
					.condiciones-box{
						height: 140px;
					}
					.cuentas-media {
						height: 120px;
					}
					.data-client{
						width: 100%;
					}
					.data-empresa{
						width: 100%;
						height: 150px;
					}
					.data-empresa2{
						width: 100%;
						height: 160px;
					}
					.display-inline{
						display: inline;
					}
					.item-media {
						text-align: left;
						font-size: 12px;
					}
					.item-media i, .img-cuenta {
						float: left;
						padding: 9px 3px;
						margin-right: 10px
					}
					
					.single-client{
						width: 100%;
					}
					.head-1 {
						width: 350px;
						float: right;
					}
					.masthead {
						height: 150px
					}
					.single-date, .cotizacion {
						float: left;
						margin: 10px 0;
						padding: 10px 0;
					}
					.single-date{
						width: 500px;
					}
					.cotizacion{
						width: 390px;
						height: 150px;
						float: right;
					}
					/*footer {
						position: absolute;
						bottom: 0%;
						width: 100%;
					}*/
				</style>
				<div class="masthead">
					<div class="float-left">
						<img src="'.$img_logo.'"  '.$style_logo.' alt="">
					</div>
					<div class="head-1 text-right">
						<img src="https://www.brildor.com/blog//wp-content/uploads/2009/12/Marcas-300x118.png">
					</div>
				</div>
				<div class="data-empresa">
					<div class="single-date" style="padding-bottom:0">
						<p><span class="font-weight-bold">Empresa: </span><span class="text-uppercase">'.$contribuyente->nombre_comercial.'</span></p>
						<p><span class="font-weight-bold">Dirección: </span>'.$sucursal->direccion.' - '.$ubigeo_ubicacion.'</span></p>
						<p><span class="font-weight-bold"> Teléfonos: </span><span class="text-uppercase">'.$sucursal->telefono.'</span></p>
						<p><span class="font-weight-bold">E-mail:</span> '.$sucursal->email.'</p>';
						if(!empty($sucursal->sitio_web)) {
							$html = $html.'
							<p><span class="font-weight-bold">Website: </span>'.$sucursal->sitio_web.'</p>';
						}
						$html = $html.'
					</div>
					<div class="cotizacion">
						<h5 class="text-center bg-secondary rounded text-uppercase p-1 font-weight-bold">'.$nombre_documento_titular.'</h5>
						<div class="box-content">
							<p><span class="font-weight-bold">Cotización N°:</span> <span class="text-uppercase">'.$this->zero_fill($documento->numero_comprobante, 6).'</span></p>
							<p><span class="font-weight-bold">Fecha:</span> <span class="text-uppercase"> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</span></p>
							<p><span class="font-weight-bold">Moneda:</span> <span class="text-uppercase">'.$sunatmoneda->nombre.'</span></p>
							'.$nombre_condicion_pago.'
							'.$orden_compra.'
							'.$num_guia.'
							'.$nro_placa.'
						</div>
					</div>
				</div>
				<div class="data-empresa2" style="padding: 0">
					<div class="single-date" style="border-top: 1px solid #e1e2e3;">
						<p><span class="font-weight-bold">Asesor de ventas: </span><span class="text-uppercase">'.$vendedor->nombre.' '.$vendedor->apellido.'</span></p>
						<p><span class="font-weight-bold">Email: </span><span class="text-uppercase">'.$vendedor->email.'</span></p>
						<p><span class="font-weight-bold">Teléfonos: </span><span class="text-uppercase">'.$vendedor->telefono.'</span></p>
					</div>
					
				</div>
				<div class="data-client">
					<div class="single-cliente">
						<h5 class="text-left bg-secondary mb-5 rounded pl-5 pr-5 display-inline text-uppercase p-1 font-weight-bold">Datos cliente</h5>
						<div class="box-content mt-3">
								<p><span class="font-weight-bold">'.$tipo_doc_cliente.':</span> <span class="text-uppercase">'.ucwords($cliente->razon_social).'</span></p>
								<p><span class="font-weight-bold">'.$tipo_doc.':</span> <span class="text-uppercase">'.$cliente->num_doc.'</span></p>
								'.$direccion_fiscal.'
						</div>
					</div>
				</div>
				<div class="table-content">
					<table class="table-main-head mt-2">
						<tbody>
							<tr class="font-weight-bold rounded mb-4 text-uppercase bg-secondary text-center">
								<th>Cant.</th>
								<th width="500px">Descripción</th>
								<th>Precio Unitario</th>
								<th>UNID/MED </th>
								<th>AFECT.IGV</th>
								<th>Importe</th>
							</tr>
							'.$items_detalle_html.'
							<tr>
								<td colspan="6" class="text-uppercase text-center">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
							</tr>
						</tbody>
					</table>
					<div class="w-100 condiciones-box">
						<div class="col-6 pb-4 mt-4">
							'.$nota_doc.'
						</div>
						<div class="col-6-r">
							<div class="mb-1" id="invoice-total">
								<div class="resumen_totales">
									<table class="tb_resumen_totales float-right">
										<tbody>
											'.$resumen.'
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<footer class="mt-5 pt-5" style="margin-top: 3em">
					<div class="codigo-qr mb-2">
						<div class="single-footer-wight mt-3 box-main">
							<img src="'.$ruta_qr.'" width="50px" class="float-left mr-3 mb-3">
							<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
							<p class="mb-3">Representación Impresa de la Factura Electrónica</p>
							'.$aviso_texto_pruebas.'
						</div>
					</div>
					<div class="cuentas-media mt-3 pt-3 border-top w-100">
						'.$html_cuentas_corrientes.'
					</div>
				</footer>
			</body>
		</html>
		';
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}

	/*********** TICKET **********/
	public function get_html_plantilla_ticket_1($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 160px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

			//Tipo de documento
			$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
			if(!$tipo_documento) {
				$resp['respuesta'] = 'error';
				$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
				echo json_encode($resp);
				exit();
			}
	
			//Datos del documento 
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));
	
			if(!$documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
			
			//Numero de comprobante
			$zero_fill = new Themefacturapdf();
	
			//Fecha del documento
			if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
				$fecha_doc_show = $documento->fecha_registro;
			} else {
				$fecha_doc_show = $documento->fecha_comprobante;
			}
	
			//Condición de pago del documento
			$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
	
			$nombre_condicion_pago = '';
			if($condicion_pago) {
				
				$nombre_condicion_pago = '<p class="text-uppercase"><span class="font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
			}
	
			//Tipo de moneda
			$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));
	
			//Extraer sucursal
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
			
			//Datos del cliente 
			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
			if(!$cliente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
			if($cliente->id_tipodocidentidad == '6'){
				$tipo_doc = 'RUC';
				$tipo_doc_cliente = 'Razón social';
			} else if ($cliente->id_tipodocidentidad == '1'){
				$tipo_doc = 'DNI';
				$tipo_doc_cliente = 'Cliente';
			}
			//Titulo del doc
			$nombre_documento_titular = '';
			if($tipo_documento->id_tipodoc_electronico == '77'){
				$nombre_documento_titular = 'Nota de Venta';
			} else if ($tipo_documento->id_tipodoc_electronico == '88'){
				$nombre_documento_titular = 'Cotización';
			} 
	
			
			//Ubicación
			$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':$cliente->direccion_fiscal;
	
			$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
			if(!$ubigeo) {
				$ubigeo_ubicacion = '';
			} else {
				$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
			}
	
			//Num guía
			$num_guia = '';
			//Orden de compra
			if(!empty($documento->nro_otr_comprobante)) {
				$orden_compra = '
				<p><span class="font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
			} else {
				$orden_compra = '';
			}
	
		
			//N° de placa
	
			if(!empty($documento->transporte_nro_placa)) {
				$nro_placa = '
				<p><span class="font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
				';
			} else {
				$nro_placa = '';
			}
		
		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr style="border: none !important;">
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td style="text-align: left !important;">'.$item['DESCRIPCION_DET'].'</td>
				<td width="100px" style="text-align: right !important;">'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td style="text-align: right !important;" width="100px">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
			<p class="text-theme-default">Gravada: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</p>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Inafecto: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</p>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			 	<p class="text-theme-default">Exonerado: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</p>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Gratuito: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</p>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Exportación: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</p>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">ICBPER: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</p>
			';
		}

		$resumen = $resumen.'
			<p class="text-theme-default">IGV ('.$documento->porcentaje_igv.'%): 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</p>
		';

		$resumen = $resumen.'
			<p class="text-theme-default">Descuento Total: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</p>
		';

		$resumen = $resumen.'
			<p class="color-theme-main">Total a Pagar: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</p>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p class="text-theme-default font-weight-bold">Observación:</p>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($contribuyente->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '
			<p style="font-family: Arial Narrow, Arial, sans-serif; font-size: 13px;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		//Dirección
		if(!empty($sucursal->direccion)) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.'</p>';
		} else {
			$direccion_empresa = '';
		}
	
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
			}
			.line-title{
				display: inline-block;
				width: 80px;
			}
			p{
				margin: 0;
				padding: 0;
				font-size: 15px;
			}
			table{
				width: 100%;
				font-size: 14px!important;
			}
			th{
				text-align: center;
				padding: 8px;
				border-bottom: 1px dashed #000;
				border-top: 1px dashed #000;
			}
			td{
				padding: 4px;
			}
			.tb_resumen_totales{
				width: 60%;
				margin:  5px;
				font-size: 14px!important;
			
			}
			.tb_resumen_totales td, .tb_resumen_totales th{
				border: none!important;
				padding: 0px;
			}
		</style>
		<body>
			<div class="masthead text-center">
				<img class="text-center" width="220px" src="'.$img_logo.'" />
				<h6 class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</h6>
				<p><span class="font-weight-bold">R.U.C.: </span>'.$contribuyente->ruc.'</p>
				<hr class="line-title mb-2 mt-2" style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 4px;" />
				<p><span class="font-weight-bold">Direc.: </span>'.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br /> '.$ubigeo_ubicacion.'</p>
				<p><i class="flaticon-call mr-2"></i><span class="font-weight-bold">Telf.:</span> '.$sucursal->telefono.'</p>
				<p><i class="flaticon-envelope mr-2"></i><span class="font-weight-bold">Email:</span> '.$sucursal->email.'</p>';
				if(!empty($sucursal->sitio_web)) {
					$html = $html.'<p><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> <span class="font-weight-bold"> Website:</span> '.$sucursal->sitio_web.'</p>';
				}
				$html = $html.'
				
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 4px;" />
			</div>
			<div class="text-left">
				<p class="font-weight-bold text-uppercase">'.$nombre_documento_titular.'<p>
				<p class="font-weight-bold">'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</p>
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
				<p>Señor (es): '.ucwords($cliente->razon_social).'</p>
				<p><span class="font-weight-bold">RUC:</span> '.$cliente->num_doc.'</p>
				<p><span class="font-weight-bold">Direc.:</span> '.$direccion_fiscal.'</p>
				<p>'.$nro_placa.'</p>
				<p>'.$orden_compra.'</p>
				<p>'.$num_guia.'</p>
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
						<tr style="border-top: 1px dashed #000;">
							<td colspan="6" class="text-uppercase text-center font-weight-bold">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2"  style="font-size: 14px">
						'.$resumen.'
					</div>
				</div>
			</div>
			
			<footer class="mt-2 text-center">
				<hr style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 4px;" />
				<img width="95px" src="'.$ruta_qr.'" style="display: block;margin: auto" class="mb-3"/>
				<p>'.$nota_doc.'</p>
				<p>Representación Impresa de la '.$nombre_documento_titular.' </p>
				<p class="font-weight-bold mt-2">Consulte su Documento en:</p>';

				if(!empty($sucursal->txt_pdf_ticket_2)){
				$html = $html.'
				<p>'.html_entity_decode($sucursal->txt_pdf_ticket_2).'</p>';
				}

				$html = $html.'
				<p>'.$ruta_consulta_documento.'</p>
				<p><span class="font-weight-bold">VENDEDOR:</span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
				';

				if(!empty($sucursal->txt_pdf_ticket_3)){
				$html = $html.'
				<p>'.html_entity_decode($sucursal->txt_pdf_ticket_3).'</p>';
				}

				$html = $html.'
				<hr class="line-title mb-2 mt-2" style="border-top: 1px dashed #000; color: #fff; background-color: #fff; height: 4px;" />
				'.$aviso_texto_pruebas.'
			</footer>
		</body>
		</html>
		';

		/*
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		header('Content-Type: application/pdf');
		$snappy->setOption('margin-left', '0mm');
		$snappy->setOption('margin-right', '0mm');
		$snappy->setOption('margin-top', '0mm');
		echo $snappy->getOutputFromHtml($html, array('page-height' =>  340,'page-width' => 78));
		exit();
		*/
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
	public function get_html_plantilla_ticket_2($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 160px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

			//Tipo de documento
			$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
			if(!$tipo_documento) {
				$resp['respuesta'] = 'error';
				$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
				echo json_encode($resp);
				exit();
			}
	
			//Datos del documento 
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));
	
			if(!$documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
			
			//Numero de comprobante
			$zero_fill = new Themefacturapdf();
	
			//Fecha del documento
			if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
				$fecha_doc_show = $documento->fecha_registro;
			} else {
				$fecha_doc_show = $documento->fecha_comprobante;
			}
	
			//Condición de pago del documento
			$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
	
			$nombre_condicion_pago = '';
			if($condicion_pago) {
				
				$nombre_condicion_pago = '<p class="text-uppercase"><span class="font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
			}
	
			//Tipo de moneda
			$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));
	
			//Extraer sucursal
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
			
			//Datos del cliente 
			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
			if(!$cliente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}

			if($cliente->id_tipodocidentidad == '6'){
				$tipo_doc = 'RUC';
				$tipo_doc_cliente = 'Razón social';
			} else if ($cliente->id_tipodocidentidad == '1'){
				$tipo_doc = 'DNI';
				$tipo_doc_cliente = 'Cliente';
			}
			//Titulo del doc
			$nombre_documento_titular = '';
			if($tipo_documento->id_tipodoc_electronico == '77'){
				$nombre_documento_titular = 'Nota de Venta';
			} else if ($tipo_documento->id_tipodoc_electronico == '88'){
				$nombre_documento_titular = 'Cotización';
			} 
			
			//Ubicación
			$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':$cliente->direccion_fiscal;
	
			$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
			if(!$ubigeo) {
				$ubigeo_ubicacion = '';
			} else {
				$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
			}
	
			//Num guía
			$num_guia = '';
			//Orden de compra
			if(!empty($documento->nro_otr_comprobante)) {
				$orden_compra = '
				<p><span class="font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
			} else {
				$orden_compra = '';
			}
	
		
			//N° de placa
	
			if(!empty($documento->transporte_nro_placa)) {
				$nro_placa = '
				<p><span class="font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
				';
			} else {
				$nro_placa = '';
			}
		
		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td colspan="6">'.nl2br($item['DESCRIPCION_DET']).'</td>
			</tr>
			<tr>
				<td colspan="2" class="text-right pr-5 padding-right-4">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td class="text-right">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
			<p class="text-theme-default">Gravada: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</p>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Inafecto: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</p>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			 	<p class="text-theme-default">Exonerado: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</p>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Gratuito: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</p>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Exportación: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</p>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">ICBPER: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</p>
			';
		}

		$resumen = $resumen.'
			<p class="text-theme-default">IGV ('.$documento->porcentaje_igv.'%): 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</p>
		';

		$resumen = $resumen.'
			<p class="text-theme-default">Descuento Total: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</p>
		';

		$resumen = $resumen.'
			<p class="color-theme-main">Total a Pagar: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</p>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p class="text-theme-default font-weight-bold">Observación:</p>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($contribuyente->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '
			<p style="font-family: Arial Narrow, Arial, sans-serif; font-size: 13px;">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		//Dirección
		if(!empty($sucursal->direccion)) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.'</p>';
		} else {
			$direccion_empresa = '';
		}
	
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
			}

			p{
				margin: 0;
				padding: 0;
				font-size: 14px;
			}
			table{
				width: 100%;
				font-size: 13px!important;
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
		</style>
		<body>
			<div class="masthead text-center">
				<img class="text-center" width="220px" src="'.$img_logo.'" />
				<h6 class="text-uppercase font-weight-bold">'.$contribuyente->nombre_comercial.'</h6>
				<p>R.U.C.: '.$contribuyente->ruc.'</p>
				<p>Direc.: '.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br /> '.$ubigeo_ubicacion.'</p>
				<p><i class="flaticon-call mr-2"></i>Telf.: '.$sucursal->telefono.'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$sucursal->email;
				if(!empty($sucursal->sitio_web)) {
					$html = $html.'<p><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> Website: '.$sucursal->sitio_web.'</p>';
				}
				$html = $html.'
				<p class="font-weight-bold text-uppercase mt-3">'.$nombre_documento_titular.'<p>
				<p class="font-weight-bold">'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</p>
			</div>
			<div class="text-left mt-3">
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
				<p><span class="font-weight-bold">Razón Social: </span>'.ucwords($cliente->razon_social).'</p>
				<p><span class="font-weight-bold">RUC:</span> '.$cliente->num_doc.'</p>
				<p><span class="font-weight-bold">Direc.:</span> '.$direccion_fiscal.'</p>
				
			</div>
			<div class="table-content mt-3">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Producto</th>
							<th>Cant.</th>
							<th>Precio </th>
							<th>Importe</th>
						</tr>
						'.$items_detalle_html.'
						<tr style="border-top: 1px solid #000;">
							<td colspan="6" class="text-uppercase text-center font-weight-bold">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
				
			</div>
			<div class="single-box-extra">
				<p>'.$nro_placa.'</p>
				<p>'.$orden_compra.'</p>
				<p>'.$num_guia.'</p>
			</div>
			<footer class="mt-4 text-center">
				<img width="95px" src="'.$ruta_qr.'" style="display: block;margin: auto" class="mb-3"/>
				<p>'.$nota_doc.'</p>
				<p>Representación Impresa de la '.$nombre_documento_titular.' </p>
				<p class="mt-2"><span class="font-weight-bold">VENDEDOR:</span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
				';

				if(!empty($sucursal->txt_pdf_ticket_3)){
				$html = $html.'
				<p>'.html_entity_decode($sucursal->txt_pdf_ticket_3).'</p>';
				}

				$html = $html.'
				<p class="font-weight-bold mb-4 text-uppercase">Gracias por su preferecia</p>
				'.$aviso_texto_pruebas.'
			</footer>
		</body>
		</html>
		';
		/*
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		header('Content-Type: application/pdf');
		$snappy->setOption('margin-left', '0mm');
		$snappy->setOption('margin-right', '0mm');
		$snappy->setOption('margin-top', '0mm');
		echo $snappy->getOutputFromHtml($html, array('page-height' =>  340,'page-width' => 78));
		exit();
		*/
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
	public function get_html_plantilla_ticket_3($data) {
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 160px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

			//Tipo de documento
			$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
			if(!$tipo_documento) {
				$resp['respuesta'] = 'error';
				$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
				echo json_encode($resp);
				exit();
			}
	
			//Datos del documento 
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_comprobante,'numero_comprobante' => $numero_doc, 'modalidad' => $contribuyente->tipo_envio_sunat)));
	
			if(!$documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
			
			//Numero de comprobante
			$zero_fill = new Themefacturapdf();
	
			//Fecha del documento
			if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
				$fecha_doc_show = $documento->fecha_registro;
			} else {
				$fecha_doc_show = $documento->fecha_comprobante;
			}
	
			//Condición de pago del documento
			$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
	
			$nombre_condicion_pago = '';
			if($condicion_pago) {
				
				$nombre_condicion_pago = '<p class="text-uppercase"><span class="font-weight-bold">Condición de pago:</span> '.$condicion_pago->condicionpago.'</p>';
			}
	
			//Tipo de moneda
			$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));
	
			//Extraer sucursal
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
			
			//Datos del cliente 
			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
			if(!$cliente) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en ApiRest';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				echo json_encode($resp);
				exit();
			}
			if($cliente->id_tipodocidentidad == '6'){
				$tipo_doc = 'RUC';
				$tipo_doc_cliente = 'Razón social';
			} else if ($cliente->id_tipodocidentidad == '1'){
				$tipo_doc = 'DNI';
				$tipo_doc_cliente = 'Cliente';
			}
			//Titulo del doc
			$nombre_documento_titular = '';
			if($tipo_documento->id_tipodoc_electronico == '77'){
				$nombre_documento_titular = 'Nota de Venta';
			} else if ($tipo_documento->id_tipodoc_electronico == '88'){
				$nombre_documento_titular = 'Cotización';
			} 
			
			//Ubicación
			$direccion_fiscal = ($cliente->direccion_fiscal == '')?'-':$cliente->direccion_fiscal;
	
			$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
			if(!$ubigeo) {
				$ubigeo_ubicacion = '';
			} else {
				$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
			}
	
			//Num guía
			$num_guia = '';
			//Orden de compra
			if(!empty($documento->nro_otr_comprobante)) {
				$orden_compra = '
				<p><span class="font-weight-bold">Orden de compra: </span> '.$documento->nro_otr_comprobante.'</p>	';
			} else {
				$orden_compra = '';
			}
	
		
			//N° de placa
	
			if(!empty($documento->transporte_nro_placa)) {
				$nro_placa = '
				<p><span class="font-weight-bold">N° de Placa: </span>'.$documento->transporte_nro_placa.'</p>
				';
			} else {
				$nro_placa = '';
			}
		
		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_doc, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
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

			//$sub_total_con_igv = round(floatval($item['CANTIDAD_DET']), $contribuyente->num_decimales)*round(floatval($item['PRECIO_DET']), $contribuyente->num_decimales);
			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if($contribuyente->cotizacion_con_igv == 'si') {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			} else {
				$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
				$subtotal_item = floatval($item['IMPORTE_DET']);
			}

			$items_detalle_html = $items_detalle_html.'
			<tr class="text-left">
				<td>'.($item['CODIGO_PRODUCTO']).'</td>
				<td>'.($item['CANTIDAD_DET'] + 0).'</td>
				<td>'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td class="text-right">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
			</tr>
			<tr>
				<td colspan="6" >'.nl2br($item['DESCRIPCION_DET']).'</td>
			</tr>
			';
		}
		// Resumen de ventas
		$resumen = '
			<p class="text-theme-default">Gravada: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gravadas).'
			</p>
		';

		if(floatval($documento->total_inafecta) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Inafecto: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_inafecta).'
				</p>
			';
		}

		if(floatval($documento->total_exoneradas) > 0) {
			$resumen = $resumen.'
			 	<p class="text-theme-default">Exonerado: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exoneradas).'
				</p>
			';
		}

		if(floatval($documento->total_gratuitas) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Gratuito: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_gratuitas).'
				</p>
			';
		}

		if(floatval($documento->total_exportacion) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">Exportación: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_exportacion).'
				</p>
			';
		}

		if(floatval($documento->total_icbper) > 0) {
			$resumen = $resumen.'
				<p class="text-theme-default">ICBPER: 
					<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_icbper).'
				</p>
			';
		}

		$resumen = $resumen.'
			<p class="text-theme-default">IGV ('.$documento->porcentaje_igv.'%): 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total_igv).'
			</p>
		';

		$resumen = $resumen.'
			<p class="text-theme-default">Descuento Total: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format(round(floatval($documento->total_descuento), 2)).'
			</p>
		';

		$resumen = $resumen.'
			<p class="color-theme-main">Total a Pagar: 
				<span class="simbolo_moneda">'.$sunatmoneda->simbolo.'</span> '.custom_money_format($documento->total).'
			</p>
		';
		//Observación
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p class="text-theme-default font-weight-bold">Observación:</p>
			<p>'.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}

		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($contribuyente->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '
			<p style="font-family: Arial Narrow, Arial, sans-serif; font-size: 13px;" class="mt-4">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
			</p>
			';
		}
		
		//Datos del vendedor

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));
	
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$tipo_comprobante.'|SERIEDOC|'.$numero_doc.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$tipo_comprobante.'_'.$serie_doc.'_'.$numero_doc.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if(!isset($data_patrocinador['url_domain'])) {
			$ruta_consulta_documento = 'https://arpsystem.com.pe/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		} else {
			$ruta_consulta_documento = 'https://'.$data_patrocinador['url_domain'].'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;
		}

		//Dirección
		if(!empty($sucursal->direccion)) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.'</p>';
		} else {
			$direccion_empresa = '';
		}
	
		$html = '
				
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
			}

			p{
				margin: 0;
				padding: 0;
				font-size: 14px;
			}
			table{
				width: 100%;
				font-size: 13px!important;
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
		</style>
		<body>
			<div class="masthead text-center">
				<img class="text-center" width="220px" src="'.$img_logo.'" />
				<h6 class="text-uppercase font-weight-bold">'.$contribuyente->nombre_comercial.'</h6>
				<p>R.U.C.: '.$contribuyente->ruc.'</p>
				<p>Direc.: '.$sucursal->direccion.' '.$sucursal->urbanizacion.'<br /> '.$ubigeo_ubicacion.'</p>
				<p><i class="flaticon-call mr-2"></i>Telf.: '.$sucursal->telefono.'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$sucursal->email;
				if(!empty($sucursal->sitio_web)) {
					$html = $html.'<p><img src="'.$ruta_base.'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> Website: '.$sucursal->sitio_web.'</p>';
				}
				$html = $html.'
				<p class="font-weight-bold text-uppercase mt-3">'.$nombre_documento_titular.'<p>
				<p class="font-weight-bold">'.$zero_fill->zero_fill($documento->numero_comprobante, 6).'</p>
			</div>
			<div class="text-left mt-3">
				<p><span class="font-weight-bold">Fecha de Emisión:</span> '.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</p>
				<p><span class="font-weight-bold">Razón Social: </span>'.ucwords($cliente->razon_social).'</p>
				<p><span class="font-weight-bold">RUC:</span> '.$cliente->num_doc.'</p>
				<p><span class="font-weight-bold">Direc.:</span> '.$direccion_fiscal.'</p>
				
			</div>
			<div class="table-content mt-3">
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
							<td colspan="6" class="text-uppercase text-center font-weight-bold">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
						</tr>
					</tbody>
				</table>
				<div class="w-100 condiciones-box  mb-1">
					<div class="resumen_totales text-right mt-2">
						'.$resumen.'
					</div>
				</div>
			</div>
			<div class="single-box-extra mt-2">
				<p>'.$nro_placa.'</p>
				<p>'.$orden_compra.'</p>
				<p>'.$num_guia.'</p>
			</div>
			<footer class="mt-2 text-center mt-3">
				<img width="95px" src="'.$ruta_qr.'" style="display: block;margin: auto" class="mb-3"/>
				<p>'.$nota_doc.'</p>
				<p>Representación Impresa de la '.$nombre_documento_titular.' </p>
				<p><span class="font-weight-bold">VENDEDOR:</span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
				';

				if(!empty($sucursal->txt_pdf_ticket_3)){
				$html = $html.'
				<p>'.html_entity_decode($sucursal->txt_pdf_ticket_3).'</p>';
				}

				$html = $html.'
				'.$aviso_texto_pruebas.'
			</footer>
		</body>
		</html>';
		
		/*
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		header('Content-Type: application/pdf');
		$snappy->setOption('margin-left', '0mm');
		$snappy->setOption('margin-right', '0mm');
		$snappy->setOption('margin-top', '0mm');
		echo $snappy->getOutputFromHtml($html, array('page-height' =>  340,'page-width' => 78));
		exit();
		*/
		$resp['respuesta'] = 'ok';
		$resp['html'] = $html;
		return $resp;
	}
	
	public function zero_fill ($valor, $long = 0) {
		return str_pad($valor, $long, '0', STR_PAD_LEFT);
	}
	public function get_logo_banco($cuenta) {
		$id_entidadfinanciera = 99;
		if(!empty($cuenta->id_entidadfinanciera)) {
			$id_entidadfinanciera = $cuenta->id_entidadfinanciera;
		}

		$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/otro_banco.png';

		if($id_entidadfinanciera == 99) {
			if(strpos(strtolower($cuenta->nombre_banco), 'interbank') !== false) {
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/interbank.jpg';
			}

			if(strpos(strtolower($cuenta->nombre_banco), 'banco de crédito') !== false || strpos(strtolower($cuenta->nombre_banco), 'BCP') !== false){
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/logobcp.jpg';
			}

			if(strpos(strtolower($cuenta->nombre_banco), 'bbva') !== false || strpos(strtolower($cuenta->nombre_banco), 'continental') !== false){
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/bbva.jpg';
			}

			if(strpos(strtolower($cuenta->nombre_banco), 'nación') !== false || strpos(strtolower($cuenta->nombre_banco), 'nacion') !== false){
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/bc_nacion.jpg';
			}

			if(strpos(strtolower($cuenta->nombre_banco), 'scotiabank') !== false || strpos(strtolower($cuenta->nombre_banco), 'scotia bank') !== false){
				$logo_banco = 'https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/scotiabank.png';
			}
		} else {
			$sunat_entidadfinanciera = SunatCodigoentidadfinanciera::findFirst(array("id_entidadfinanciera = :id_entidadfinanciera:", 'bind' => array('id_entidadfinanciera' => $id_entidadfinanciera)));
			if(!$sunat_entidadfinanciera) {
				$sunat_entidadfinanciera = SunatCodigoentidadfinanciera::findFirst(array("id_entidadfinanciera = :id_entidadfinanciera:", 'bind' => array('id_entidadfinanciera' => 99)));
			}

			$logo_banco = $sunat_entidadfinanciera->logo;
		}
		
		return $logo_banco;
	}


}
?>