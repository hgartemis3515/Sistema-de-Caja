<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/dompdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/qrlib/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/libreriaqr/vendor/autoload.php";
use Knp\Snappy\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Dompdf\Dompdf;

class DownloadController extends ControllerBase
{
	public function verpdfAction($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $modo = 'pdf', $tamanio = 'a4', $plantilla = 0) {
		$this->view->disable();
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}
		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		$html_cuentas_corrientes = $this->get_html_cuentas($id_contribuyente);

		$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodocumento)));

		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $documento->id_tipodocumento, $numero_comprobante, $contribuyente->tipo_envio_sunat);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}

		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));

		$detalle = $resp_detalle['detalle'];

		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$id_tipodoc_electronico.'|SERIEDOC|'.$numero_comprobante.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$id_tipodoc_electronico.'_'.$serie_comprobante.'_'.$numero_comprobante.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if($id_tipodoc_electronico == '77') { //Nota de Venta
			$themenotapedido = new Themenotapedidopdf();
			if($tamanio == 'ticket') {
				$resp_theme_doc = $themenotapedido->get_html_notapedido_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
			} else {
				if($sucursal->plantilla_pdf_a4 == 1) {
					$resp_theme_doc = $themenotapedido->get_html_notapedido($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				} else {
					$templatepdf = new TemplatepdfController;
					$resp_theme_doc = $templatepdf->html_doc_no_oficial($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			}
		} else if($id_tipodoc_electronico == '88') { //Nota de Venta
			$themecotizacion = new Themecotizacionpdf();
			if($tamanio == 'ticket') {
				$resp_theme_doc = $themecotizacion->get_html_cotizacion_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
			} else {
				if($sucursal->plantilla_pdf_a4 == 1) {
					$resp_theme_doc = $themecotizacion->get_html_cotizacion($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				} else {
					$templatepdf = new TemplatepdfController;
					$resp_theme_doc = $templatepdf->html_doc_no_oficial($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			}
		}
		
		try {
			$dompdf = new Dompdf();
			$dompdf->loadHtml($resp_theme_doc['html']);

			// (Optional) Setup the paper size and orientation 
			if($tamanio == 'ticket') {
				//$dompdf->set_paper(array(0,0,$width,$height));
				$dompdf->setPaper(array(0,0,219.2,915));
			} else {
				$dompdf->setPaper('A4');
			}
			
			// Render the HTML as PDF
			$dompdf->render();
			unlink($ruta_qr);
			//$dompdf->stream($tipo_doc_electronico->descripcion.'_'.$documento->serie_comprobante.'-'.$documento->numero_comprobante);
			$dompdf->stream($documento->numero_comprobante.".pdf", array('Attachment' => 0));
			
			exit();

		} catch(Exception $e) {
			var_dump($e);
			exit();
		}
	}

	public function downloadpdfAction($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $modo = 'pdf', $tamanio = 'a4', $plantilla = 0) {
		$this->view->disable();
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en ApiRest';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		$html_cuentas_corrientes = $this->get_html_cuentas($id_contribuyente);

		$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));

		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		$documentoelectronico = new DocumentoelectronicoController;
		$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante);
		if($resp_detalle['respuesta'] == 'error') {
			echo json_encode($resp_detalle);
			exit();
		}

		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $documento->id_vendedor)));

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));

		$detalle = $resp_detalle['detalle'];
		
		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$texto_qr_documento = $contribuyente->ruc.'|'.$id_tipodoc_electronico.'|'.$serie_comprobante.'|'.$numero_comprobante.'|'.$documento->total_igv.'|'.$documento->total.'|'.date("d-m-Y", strtotime($documento->fecha_registro)).'|'.$cliente->id_tipodocidentidad.'|'.$cliente->num_doc.'|||';

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$id_tipodoc_electronico.'_'.$serie_comprobante.'_'.$numero_comprobante.'.png';
		\PHPQRCode\QRcode::png($texto_qr_documento, $ruta_qr, 'Q',15, 0);

		if($plantilla == 0) {
			$plantilla_pdf_a4 = $sucursal->plantilla_pdf_a4;
		} else {
			$plantilla_pdf_a4 = $plantilla;
		}
		
		if($plantilla_pdf_a4 == 1) {
			if($id_tipodoc_electronico == '01') { //factura
				$themefactura = new Themefacturapdf();
				if($tamanio == 'ticket') {
					$resp_theme_doc = $themefactura->get_html_factura_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $themefactura->get_html_factura($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '03') { //boleta
				$themefactura = new Themeboletapdf();
				if($tamanio == 'ticket') {
					$resp_theme_doc = $themefactura->get_html_boleta_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $themefactura->get_html_boleta($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}			
			} else if ($id_tipodoc_electronico == '07') { //Nota de crédito
				$theme_notacredito = new Themenotacreditopdf();
	
				$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
	
				$motivo_modificacion = SunatTiponotacredito::findFirst(array("id_tiponotacredito = :id_tiponotacredito:", 'bind' => array('id_tiponotacredito' => $documento->id_cod_tipomotivo_credito)));
	
				$texto_motivo = $motivo_modificacion->id_tiponotacredito.'. '.$motivo_modificacion->descripcion;
				$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));
				$texto_doc_modificado = $tipo_doc_modificado->descripcion;
				if($tamanio == 'ticket') {
					$resp_theme_doc = $theme_notacredito->get_html_notacredito_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $theme_notacredito->get_html_notacredito($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '08') { //Nota de débito
				$theme_notadebito = new Themenotadebitopdf();
				$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				$motivo_modificacion = SunatTiponotadebito::findFirst(array("id_tiponotadebito = :id_tiponotadebito:", 'bind' => array('id_tiponotadebito' => $documento->id_cod_tipomotivo_debito)));
				$texto_motivo = $motivo_modificacion->id_tiponotadebito.'. '.$motivo_modificacion->descripcion;
				$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));
				$texto_doc_modificado = $tipo_doc_modificado->descripcion;
				if($tamanio == 'ticket') {
					$resp_theme_doc = $theme_notadebito->get_html_notadebito_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $theme_notadebito->get_html_notadebito($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '09') {
				$themeguiaremision = new Themeguiaremisionpdf();
				$ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_partida)));
				$ubigeo_destino = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_destino)));
				if($tamanio == 'ticket') {
					$resp_theme_doc = $themeguiaremision->get_html_factura_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $ubigeo_partida, $ubigeo_destino, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $themeguiaremision->get_html_guiaremision($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $ubigeo_partida, $ubigeo_destino, $this->data_patrocinador, $vendedor, $sucursal);
				}
			} else {
				echo "Documento Inválido!";
				exit();
			}
		} else if(in_array($plantilla_pdf_a4, array(3, 4, 5, 6, 7, 8))) {
			$lista_template = new ListaplantillaspdfController;
			$data_print['id_contribuyente'] = $contribuyente->id_contribuyente;
			$data_print['tipo_comprobante'] = $id_tipodoc_electronico;
			$data_print['serie_doc'] = $serie_comprobante;
			$data_print['numero_doc'] = $numero_comprobante;
			$data_print['tipo'] = 'pdf';
			$data_print['tamanio'] = 'a4';
			$data_print['plantilla'] =  $plantilla_pdf_a4;
			$lista_template->generar_pdf_cpe($data_print);
			exit();
		} else {
			$templatepdf = new TemplatepdfController;
			
			if($id_tipodoc_electronico == '01') { //factura
				$themefactura = new Themefacturapdf();
				if($tamanio == 'ticket') {
					$resp_theme_doc = $themefactura->get_html_factura_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc['html'] = $templatepdf->html_factura_pdf_a4($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '03') { //boleta
				$themefactura = new Themeboletapdf();
				if($tamanio == 'ticket') {
					$resp_theme_doc = $themefactura->get_html_boleta_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc['html'] = $templatepdf->html_factura_pdf_a4($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '07') { //Nota de crédito
				$theme_notacredito = new Themenotacreditopdf();
	
				$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
	
				$motivo_modificacion = SunatTiponotacredito::findFirst(array("id_tiponotacredito = :id_tiponotacredito:", 'bind' => array('id_tiponotacredito' => $documento->id_cod_tipomotivo_credito)));
	
				$texto_motivo = $motivo_modificacion->id_tiponotacredito.'. '.$motivo_modificacion->descripcion;
				$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));
				$texto_doc_modificado = $tipo_doc_modificado->descripcion;
				if($tamanio == 'ticket') {
					$resp_theme_doc = $theme_notacredito->get_html_notacredito_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $theme_notacredito->get_html_notacredito($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '08') { //Nota de débito
				$theme_notadebito = new Themenotadebitopdf();
				$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				$motivo_modificacion = SunatTiponotadebito::findFirst(array("id_tiponotadebito = :id_tiponotadebito:", 'bind' => array('id_tiponotadebito' => $documento->id_cod_tipomotivo_debito)));
				$texto_motivo = $motivo_modificacion->id_tiponotadebito.'. '.$motivo_modificacion->descripcion;
				$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));
				$texto_doc_modificado = $tipo_doc_modificado->descripcion;
				if($tamanio == 'ticket') {
					$resp_theme_doc = $theme_notadebito->get_html_notadebito_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $theme_notadebito->get_html_notadebito($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $doc_modificado, $texto_motivo, $texto_doc_modificado, $modo, $ubigeo, $this->data_patrocinador, $html_cuentas_corrientes, $condicion_pago, $vendedor, $sucursal);
				}
			} else if ($id_tipodoc_electronico == '09') {
				$themeguiaremision = new Themeguiaremisionpdf();
				$ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_partida)));
				$ubigeo_destino = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_destino)));
				if($tamanio == 'ticket') {
					$resp_theme_doc = $themeguiaremision->get_html_factura_ticket($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $ubigeo_partida, $ubigeo_destino, $this->data_patrocinador, $vendedor, $sucursal);
				} else {
					$resp_theme_doc = $themeguiaremision->get_html_guiaremision($contribuyente, $cliente, $documento, $detalle, $sunatmoneda, $ruta_qr, $modo, $ubigeo, $ubigeo_partida, $ubigeo_destino, $this->data_patrocinador, $vendedor, $sucursal);
				}
			} else {
				echo "Documento Inválido!";
				exit();
			}
			
			
		}
		
		if($modo == '789') {
			echo $resp_theme_doc['html'];
			exit();
		}

		try {
			$dompdf = new Dompdf();
			$dompdf->loadHtml($resp_theme_doc['html']);

			// (Optional) Setup the paper size and orientation 
			if($tamanio == 'ticket') {
				//$dompdf->set_paper(array(0,0,$width,$height));
				$dompdf->setPaper(array(0,0,219.2,915));
			} else {
				$dompdf->setPaper('A4');
			}
			
			// Render the HTML as PDF
			$dompdf->render();
			unlink($ruta_qr);
			//$dompdf->stream($tipo_doc_electronico->descripcion.'_'.$documento->serie_comprobante.'-'.$documento->numero_comprobante);
			$dompdf->stream($documento->serie_comprobante.'-'.$documento->numero_comprobante.".pdf", array('Attachment' => 0));
			
			exit();

		} catch(Exception $e) {
			var_dump($e);
			exit();
		}
	}

	public function resumenAction($id_contribuyente, $codigo, $serie, $secuencia, $tipo) {
		$this->view->disable();
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Código';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		//Verificamos los permisos
		if($id_contribuyente != $usuario->id_contribuyente) {
			if($usuario->id_rol != 1 && $usuario->id_rol != 2) {
				if($contribuyente->id_patrocinador != $usuario->id_contribuyente) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Documento no existe o no tiene los permisos correspondientes.';
					echo json_encode($resp);
					exit();
				}
			}
		}

		$resumen = ResumenBoletas::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and serie = :serie: and secuencia = :secuencia: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'codigo' => $codigo, 'serie' => $serie, 'secuencia' => $secuencia, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$resumen) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		if($resumen->tipo_envio_sunat == 'produccion') {
			$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
		} else {
			$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
		}

		if($tipo == 'xml_cpe_zip') {
			$nombre_archivo = $resumen->name_xml_zip;
		} else if($tipo == 'xml_cdr_zip') {
			$nombre_archivo = $resumen->name_cdr_zip;
		} else {
			echo 'no se reconoce el documento';
			exit();
		}

		$ruta_final = $ruta_base_cpe_cdr.'resumenes/'.$nombre_archivo;

		if (!file_exists($ruta_final) || empty($nombre_archivo)) {
			echo "no existe el archivo seleccionado!";
			exit();
		}

		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=$nombre_archivo");
		header("Content-Length: " . filesize($ruta_final));

		readfile($ruta_final);
		exit;
	}

	public function downloadcpeAction($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo) {
		$this->view->disable();

		if($id_tipodoc_electronico == '31') {
			$downloadguiatransportista = new DownloadguiatransportistaController;
			$downloadguiatransportista->download_xml($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo);
			exit();
		}
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}
		
		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
			echo json_encode($resp);
			exit();
		}

		if($documento->tipo_envio_sunat == 'produccion') {
			$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
		} else {
			$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
		}

		if($tipo == 'xml_cpe_zip') {
			$nombre_archivo = $documento->name_xml_zip;
		} else if($tipo == 'xml_cdr_zip') {
			$nombre_archivo = $documento->name_cdr_zip;
		} else {
			echo 'no se reconoce el documento';
			exit();
		}

		/*
		if($id_contribuyente != 1) {
			if($documento->tipo_envio_sunat != 'produccion') {
				echo "Descarga Disponible Solo en Producción!";
				exit();
			}
		}
		*/
		
		if($documento->id_tipodoc_electronico == '01' || $documento->id_tipodoc_electronico == '03') {
			$ruta_final = $ruta_base_cpe_cdr.'facturas_boletas/'.$nombre_archivo;
		} else if($documento->id_tipodoc_electronico == '07') {
			$ruta_final = $ruta_base_cpe_cdr.'nota_credito/'.$nombre_archivo;
		} else if($documento->id_tipodoc_electronico == '08') {
			$ruta_final = $ruta_base_cpe_cdr.'nota_debito/'.$nombre_archivo;
		} else if($documento->id_tipodoc_electronico == '09') {
			$ruta_final = $ruta_base_cpe_cdr.'guias_remision/'.$nombre_archivo;
		} else {
			echo "documento desconocido!";
			exit();
		}

		if (!file_exists($ruta_final) || empty($nombre_archivo)) {
			echo "no existe el archivo seleccionado!";
			exit();
		}

		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=$nombre_archivo");
		header("Content-Length: " . filesize($ruta_final));

		readfile($ruta_final);
		exit;
	}

	public function get_html_cuentas($id_contribuyente) {
		$lista_cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$html = '';
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
			$html = $html.'
			<tr>
				<td class="tabla_cuentas">'.$cuenta->nombre_banco.' - '.$tipo_cuenta_banco.'</td>
				<td class="tabla_cuentas">'.$cuenta->id_codigomoneda.'</td>
				<td class="tabla_cuentas">'.$cuenta->nro_cuenta.'</td>
				<td class="tabla_cuentas">'.$cuenta->cci.'</td>
			</tr>
			';
		}

		if($html != '') {
			$html = '
			<div style="text-align:center;">
				<table style="margin: auto; width: 550px !important; border: 1px solid black; border-collapse: collapse;">
					<tbody>
						<tr>
							<td style="padding: 1px 0px 0px 3px !important; font-size: 11px;" colspan="4">CUENTAS:</td>
						</tr>
						<tr>
							<td class="tabla_cuentas">BANCO</td>
							<td class="tabla_cuentas">MONEDA</td>
							<td class="tabla_cuentas">CTA CTE</td>
							<td class="tabla_cuentas">CCI</td>
						</tr>
						'.$html.'
					</tbody>
				</table>
			</div>
			';
		}

		return $html;
	}

	public function ticketAbonoAction($id_montocobrado) {
		$theme_pdf = new TemplatepdfController;
		$resp = $theme_pdf->html_abono_pdf_ticket($id_montocobrado);
		if($resp['respuesta'] == 'error'){
			echo json_encode($resp);
			exit();
		}
		
		try {
			$dompdf = new Dompdf();

			$dompdf->loadHtml($resp['html']);

			// (Optional) Setup the paper size and orientation 
			$dompdf->setPaper(array(0,0,219.2,915));
			$dompdf->set_option('isRemoteEnabled', true);
			
			// Render the HTML as PDF
			$dompdf->render();
			
			//$dompdf->stream($tipo_doc_electronico->descripcion.'_'.$documento->serie_comprobante.'-'.$documento->numero_comprobante);
			$dompdf->stream("Comprobante_Abono.pdf", array('Attachment' => 0));
			
			exit();

		} catch(Exception $e) {
			var_dump($e);
			exit();
		}
	}

	public function downloadCotisiscompletoAction($id_prospecto) {
		$this->view->disable();

		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			echo "no tiene permisos";
		}

		if($usuario->id_contribuyente != 1) {
			echo "no tienes acceso";
			exit();
		}
		
		$prospecto = Prospecto::findFirst(array("id_prospecto = :id_prospecto:", 'bind' => array('id_prospecto' => $id_prospecto)));
		if(!$prospecto) {
			echo "no existe";
			exit();
		}

		$emails_array = explode(',', $prospecto->correos);
		$correo_individual = trim($emails_array[0]);
		$cliente = Usuario::findFirst(array("email = :email:", 'bind' => array('email' => $correo_individual)));
        if(!$cliente) {
			//buscamos si existe una cuenta para el número de RUC
			$contribuyente_prospecto = Contribuyente::findFirst(array("ruc = :ruc:", 'bind' => array('ruc' => $prospecto->ruc)));
			if($contribuyente_prospecto) {
				$cliente = Usuario::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente_prospecto->id_contribuyente)));
				if(!$cliente) {
					echo "no encontramos el usuario";
					exit();
				}
			} else {
				echo "No Existe la Empresa Asignada al Contribuyente";
				exit();
			}
		}
		
		$contribuyente_cliente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $cliente->id_contribuyente)));
		if(!$contribuyente_cliente) {
			echo "La empresa no está registrada";
			exit();
		}

		$theme = new Themecotisiscompleto();
		$html = $theme->get_html($contribuyente_cliente, $prospecto, $cliente);
		$nombre_archivo = $contribuyente_cliente->ruc."_SistemaCompleto_V8.pdf";
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
		$snappy->setOption('enable-local-file-access', true);
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$nombre_archivo.'"');

		echo $snappy->getOutputFromHtml($html);
		exit();
	}

	public function downloadCotiservfactAction($id_prospecto) {
		$this->view->disable();

		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			echo "no tiene permisos";
		}

		if($usuario->id_contribuyente != 1) {
			echo "no tienes acceso";
			exit();
		}
		
		$prospecto = Prospecto::findFirst(array("id_prospecto = :id_prospecto:", 'bind' => array('id_prospecto' => $id_prospecto)));
		if(!$prospecto) {
			echo "no existe";
			exit();
		}

		$emails_array = explode(',', $prospecto->correos);
		$correo_individual = trim($emails_array[0]);
		$cliente = Usuario::findFirst(array("email = :email:", 'bind' => array('email' => $correo_individual)));
        if(!$cliente) {
			//buscamos si existe una cuenta para el número de RUC
			$contribuyente_prospecto = Contribuyente::findFirst(array("ruc = :ruc:", 'bind' => array('ruc' => $prospecto->ruc)));
			if($contribuyente_prospecto) {
				$cliente = Usuario::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente_prospecto->id_contribuyente)));
				if(!$cliente) {
					echo "no encontramos el usuario";
					exit();
				}
			} else {
				echo "No Existe la Empresa Asignada al Contribuyente";
				exit();
			}
		}
		
		$contribuyente_cliente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $cliente->id_contribuyente)));
		if(!$contribuyente_cliente) {
			echo "La empresa no está registrada";
			exit();
		}

		$theme = new Themecotiservfact();
		$html = $theme->get_html($contribuyente_cliente, $prospecto, $cliente);
		$nombre_archivo = $contribuyente_cliente->ruc."_servicio_Facturacion.pdf";
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
		$snappy->setOption('enable-local-file-access', true);
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$nombre_archivo.'"');

		echo $snappy->getOutputFromHtml($html);
		exit();
	}
}
?>