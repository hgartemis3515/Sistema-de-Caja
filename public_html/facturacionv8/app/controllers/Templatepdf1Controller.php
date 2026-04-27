<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/qrlib/vendor/autoload.php";
use Knp\Snappy\Pdf;

class Templatepdf1Controller extends ControllerBase
{


	public function get_html_plantilla_a4_1($data) {  //ID: 1, TAMAÑO: A4, ==> FACTURAS, BOLETAS, NOTAS DE CRÉDITO, DÉBITO
		$this->view->disable();
		$data_patrocinador = $this->get_parametros_iniciales();
		$id_contribuyente = intval($data['id_contribuyente']);
		$tipo_comprobante = $data['tipo_comprobante'];
		$serie_doc = $data['serie_doc'];
		$numero_doc = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];
		$herramientas = new HerramientasController;
		
		$ruta_base = '/home/humbertoguadalup/public_html';

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}
		
		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}
		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 320px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		$img_logo = $ruta_base.$img_logo;

		//Datos del documento 
		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_comprobante, 'serie_comprobante' => $serie_doc, 'numero_comprobante' => $numero_doc, 'tipo_envio_sunat' => $tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
        }
        //Numero de comprobante
        $zero_fill = new Themefacturapdf();
        
        //Tipo de documento
		$tipo_documento = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $tipo_comprobante)));
		if(!$tipo_documento) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = 'No se ha encontrado el tipo de documento';
			echo json_encode($resp);
			exit();
        }

		//Tipo de moneda
		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		//Extraer sucursal
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		$opcion_mostrar_items_igv = 'si';
		if($documento->id_tipodoc_electronico == '01') {
			$opcion_mostrar_items_igv = ($sucursal->factura_mostrar_items_igv == 'si')?'si':'no';
		} else if($documento->id_tipodoc_electronico == '03') {
			$opcion_mostrar_items_igv = ($sucursal->boleta_mostrar_items_igv == 'si')?'si':'no';
		} else if($documento->id_tipodoc_electronico == '07') {
			$opcion_mostrar_items_igv = ($sucursal->notacredito_mostrar_items_igv == 'si')?'si':'no';
		} else if($documento->id_tipodoc_electronico == '08') {
			$opcion_mostrar_items_igv = ($sucursal->notadebito_mostrar_items_igv == 'si')?'si':'no';
		}

		//Datos del cliente 
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

        $tipo_doc = 'Num.Doc.';
        $tipo_doc_cliente = 'Nombre: ';
        $descripcion_nota = '';
		$documento_relacionado = '';
		$nombre_documento_titular = '';
		$numero_doc_cliente = $cliente->num_doc;
        if($documento->id_tipodoc_electronico == '01'){
			$nombre_documento_titular = 'Factura de Venta Electrónica';
        } else if ($documento->id_tipodoc_electronico == '03'){
			$nombre_documento_titular = 'Boleta de Venta Electrónica';
        } else if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08'){
			$nombre_documento_titular = 'Nota de Crédito Electrónica';
            $descripcion_nota = '<p><span class="font-weight-bold">Motivo de Emisión:</span> '.$documento->descripcion_motivo_credito.'.</p>';
          
			$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $tipo_envio_sunat)));

			$motivo_modificacion = SunatTiponotacredito::findFirst(array("id_tiponotacredito = :id_tiponotacredito:", 'bind' => array('id_tiponotacredito' => $documento->id_cod_tipomotivo_credito)));

			$texto_motivo = $motivo_modificacion->id_tiponotacredito.'. '.$motivo_modificacion->descripcion;

			$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));
			
			$texto_doc_modificado = $tipo_doc_modificado->descripcion;

			$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$texto_doc_modificado.' '.$doc_modificado->serie_comprobante.' - '.$herramientas->zero_fill($doc_modificado->numero_comprobante, 6).' | '.date("d-m-Y", strtotime($doc_modificado->fecha_comprobante)).'</p>';
        }
		
		if(preg_match('#^sdi.*#s', trim($cliente->num_doc))){
			$numero_doc_cliente = '';
		}
		
		if($cliente->id_tipodocidentidad == '1') {
			//DNI
			$tipo_doc = 'D.N.I.: ';
		}

		if($cliente->id_tipodocidentidad == '6') {
			//RUC
			$tipo_doc = 'R.U.C.: ';
			$tipo_doc_cliente = 'Razón Social';
		}

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
		


		//Ubicación
		
		if(empty($documento->dir_destino) && empty($documento->id_ubigeo_destino)) {
			$direccion_fiscal = ($cliente->direccion_fiscal == '')?' ':$cliente->direccion_fiscal;
		} else {
			$ubicacion_dir_cliente = '';
			if(!empty($documento->id_ubigeo_destino)) {
				$ubigeo_dir_cliente = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_destino)));
				if($ubigeo_dir_cliente) {
					$ubicacion_dir_cliente = $ubigeo_dir_cliente->distrito.' - '.$ubigeo_dir_cliente->provincia.' - '.$ubigeo_dir_cliente->departamento;
				}
			}
			
			if(empty($ubicacion_dir_cliente)) {
				$direccion_fiscal = $documento->dir_destino;
			} else {
				$documento_dir_destino = preg_replace('/\s\s+/', ' ', $documento->dir_destino);
				$ubicacion_dir_cliente = preg_replace('/\s\s+/', ' ', $ubicacion_dir_cliente);
				if(strpos(strtoupper($documento_dir_destino), strtoupper($ubicacion_dir_cliente)) !== false){
					$direccion_fiscal = $documento_dir_destino;
				} else{
					$direccion_fiscal = $documento_dir_destino.' '.$ubicacion_dir_cliente;
				}
			}
		}
		$direccion_fiscal = '<p><span class="font-weight-bold">Dirección:</span> '.$direccion_fiscal.'</p>';
		
		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if(!$ubigeo) {
			$ubigeo_ubicacion = '';
		} else {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

	
		
		//Numero de guía
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

		if(!empty($lista_docs_referencia)) {
			$num_guia = $lista_docs_referencia;
		} else {
			$num_guia = '';
		}

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

		//Texto personalizado
		if(!empty($sucursal->txt_pdf_a4_1)) {
			$text_pdf_1 = '<p>'.$sucursal->txt_pdf_a4_1.'</p>';
		} else {
			$text_pdf_1 = '';
		}
		if(!empty($sucursal->txt_pdf_a4_2)) {
			$text_pdf_2 = '<p>'.$sucursal->txt_pdf_a4_2.'</p>';
		} else {
			$text_pdf_2 = '';
		}
		if(!empty($sucursal->txt_pdf_a4_3)) {
			$text_pdf_3 = '<p>'.$sucursal->txt_pdf_a4_3.'</p>';
		} else {
			$text_pdf_3 = '';
		}
		//Lista de items
		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($id_contribuyente, $tipo_comprobante, $serie_doc, $numero_doc);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}
		$detalle = $resp_detalle['detalle'];
		
		$items_detalle_html = '';

		foreach($detalle as $item) {
			if (strpos($item['TEXT_TIPO_OPERACION_DET'], '[') !== false) {
				$text_afectacion = $herramientas->get_string_between($item['TEXT_TIPO_OPERACION_DET'], '[', ']');
			} else {
				if (strpos($item['TEXT_TIPO_OPERACION_DET'], '-') !== false) {
					$text_afectacion = explode(' - ', $item['TEXT_TIPO_OPERACION_DET'])[0];
				} else {
					$text_afectacion = $item['TEXT_TIPO_OPERACION_DET'];
				}
			}

			$sub_total_con_igv = floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			if(intval($item['COD_TIPO_OPERACION_DET']) == 10) {
				if($opcion_mostrar_items_igv == 'si') {
					$precio_unitario_item = $item['PRECIO_DET'];
					$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
				} else {
					$precio_unitario_item = round($item['PRECIO_DET']/1.18, $num_decimales);
					$subtotal_item = floatval($item['IMPORTE_DET']);
				}
			} else {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			}
			
			$items_detalle_html = $items_detalle_html.'
			<tr>
				<td class="text-center">'.($item['CANTIDAD_DET'] + 0).'</td>
				<td class="text-left"><div style="width: 330px !important;">'.nl2br($item['DESCRIPCION_DET']).'</div></td>
				<td  class="text-center">'.$sunatmoneda->simbolo.' '.$precio_unitario_item.'</td>
				<td>'.$item['UNIDAD_MEDIDA_NOMBRE'].'</td>
				<td>'.$text_afectacion.'</td>
				<td  class="text-right">'.$sunatmoneda->simbolo.' '.custom_money_format($subtotal_item).'</td>
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
		$nota_doc = '';
		if(!empty($documento->nota)) {
			$nota_doc = '
			<p><strong>Observación:</strong> '.$documento->nota.'</p>
			';
		} else {
			$nota_doc = '';
		}
		//Texto de prueba 
		$aviso_texto_pruebas = '';
		if($documento->tipo_envio_sunat == 'prueba') {
			$aviso_texto_pruebas = '
				<h6 class="font-weight-bold text-danger">
					Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
				</h6>
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

			if($cuenta->id_codigomoneda == 'PEN'){
				$moneda = 'Soles';
			}else{
				$moneda = 'Dólares';
			}
			$html_cuentas_corrientes = $html_cuentas_corrientes.'
				<tr class="text-center">
					<td>'.$cuenta->nombre_banco.'</td>
					<td>'.$cuenta->nombre_titular.'</td>
					<td>'.$tipo_cuenta_banco.'</td>
					<td>'.$moneda.'</td>
					<td>'.$cuenta->nro_cuenta.'</td>
					<td>'.$cuenta->cci.'</td>
				</tr>
			';
		}
		
		if(!empty($html_cuentas_corrientes)) {
			$html_cuentas_corrientes = '
			<div class="content-cuentas">
				<table class="table-3 table-cuentas pl-2">
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
		
		//Dirección
		if(!empty($sucursal->direccion)) {
			$direccion_empresa = '<p><i class="flaticon-placeholder-1 mr-2"></i>'.$sucursal->direccion.'</p>';
		} else {
			$direccion_empresa = '';
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
			<title>'.$documento->serie_comprobante.' - '.$herramientas->zero_fill($documento->numero_comprobante, 6).'</title>
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
				border: 1px solid #000;
			}
			
			.table-head p{
				line-height: 2;
				font-size: 17px;
			}
			.tb_resumen_totales td {
				padding: 5px 10px;
				border: none;
				text-aling: left:
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
		</style>
		<body>';
			$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}
		if(empty($contribuyente->logo_350)) {
				$height = "style='height: 200px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-3">
						<img src="'.$img_logo.'" '.$style_logo.'>
					</div>
					<div class="col-5 text-center">
						<h5 class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</h5>
						'.$direccion_empresa.'
						<p><i class="flaticon-placeholder-1 mr-2"></i>'.$ubigeo_ubicacion.'</p>
						<p><i class="flaticon-call mr-2"></i>'.$sucursal->telefono.'</p>
						<p><i class="flaticon-envelope mr-2"></i>'.$sucursal->email.'<p>
						<p><i class="icon-sphere3 mr-2"></i>'.$sucursal->sitio_web.'</p>
					</div>
					<div class="col-4 p-2">
						<div class="table-head">
							<p>R.U.C. '.$contribuyente->ruc.'</p>                    
							<p style="font-weight: 600;">'.$nombre_documento_titular.'</p>
							<p>'.$documento->serie_comprobante.' - '.$herramientas->zero_fill($documento->numero_comprobante, 6).'</p>
						</div>
					</div>
				</div>';
			} else {
				$height = "style='height: 240px;'";
				$html = $html.'
				<div class="masthead w-100" '.$height.'>
					<div class="col-7 text-center mb-4">
						<img  src="'.$img_logo.'" '.$style_logo.'">
						<h6 class="font-weight-bold text-uppercase">'.$contribuyente->nombre_comercial.'</h6>
						'.$direccion_empresa.'
						<p>'.$ubigeo_ubicacion.'</p>
						<p>Telf: '.$sucursal->telefono.' - Email: '.$sucursal->email.'</p>
						<p><p>
						<p>'.$sucursal->sitio_web.'</p>
					</div>
					<div class="col-5 p-2">
						<div class="table-head">
							<p>R.U.C. '.$contribuyente->ruc.'</p>                    
							<p style="font-weight: 600;">'.$nombre_documento_titular.'</p>
							<p>'.$documento->serie_comprobante.' - '.$herramientas->zero_fill($documento->numero_comprobante, 6).'</p>
						</div>
					</div>
				</div>';
			}

			$html = $html.'
			'.$text_pdf_1.'
			<div class="single-date-client mb-3 mt-5">
				<p><span class="font-weight-bold">'.$tipo_doc_cliente.':</span> '.ucwords($cliente->razon_social).'</p>
				<p><span class="font-weight-bold">'.$tipo_doc.':</span> '.$cliente->num_doc.'</p>
				'.$direccion_fiscal.'
				'.$descripcion_nota.'
				'.$documento_relacionado.'
			</div>	
			<table class="table table-1 mt-1 text-center">
				<thead>
					<tr class="text-uppercase">
						<th>Fecha de emisión</th>
						<th>Condición de Pago</th>
						<th>Tipo moneda</th>
						<th>Número de guía</th>
						<th>Orden de Compra</th>
						<th>Número de Placa</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<tr>
						<td>'.date("d-m-Y / H:i A", strtotime($fecha_doc_show)).'</td>
						<td>'.$nombre_condicion_pago.'</td>
						<td>'.$sunatmoneda->nombre.'</td>
						<td> '.$num_guia.'</td>
						<td> '.$orden_compra.'</td>
						<td> '.$nro_placa.'</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-main">
				<tbody>
					<tr class="text-uppercase">
						<th>Cant.</th>
						<th width="500px">Descripción</th>
						<th width="200px">Precio</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th width="200px">Importe</th>
					</tr>
					<tr>
						'.$items_detalle_html.'
					</tr>
					<tr>
						<td colspan="6" style="border: 1px solid #000!important" class="text-uppercase text-center font-weight-bold">'.$documento->total_letras.' '.strtoupper($sunatmoneda->nombre).'</td>
					</tr>
				</tbody>
			</table>
			'.$text_pdf_2.'
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
											<p><span class="font-weight-bold">HASH: </span>  '.$documento->hash_cpe.'</p>
											<p><span class="font-weight-bold">VENDEDOR: </span> '.$vendedor->nombre.' '.$vendedor->apellido.' (cod: '.$vendedor->idusuario.')</p>
											<p class="mb-3">Representación Impresa del Documento Electrónico</p>
											'.$text_pdf_3.'
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
			'.$html_cuentas_corrientes.'
			
		</body>
		</html>
		';
		
		/*echo $html;*/
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
		header('Content-Type: application/pdf');
		echo $snappy->getOutputFromHtml($resp_html['html']);
	}
	
}

?>