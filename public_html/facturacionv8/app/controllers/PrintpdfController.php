<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/libreriaqr/vendor/autoload.php";
use Knp\Snappy\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class PrintpdfController extends ControllerBase
{
	public function indexAction(){

		if(!isset($_GET['file'])) {
			exit();
		}

		$file = $_GET['file'];
		$this->print_pdf($file);
		exit();
	}

	public function pruebaAction() {
		$file = $_GET['file'];
		$id_plantilla = isset($_GET['id_plantillapdf'])?intval($_GET['id_plantillapdf']):0;
		$mostrar_html = !isset($_GET['mostrar_html'])?false:$_GET['mostrar_html'];
		$mostrar_html = ($mostrar_html=='si')?true:false;

		$herramientas = new HerramientasController;
		$cadena_decifrada = $herramientas->desencriptar($file);
		$array_data = explode('||', $cadena_decifrada);
		$id_contribuyente = !isset($array_data[0])?'':$array_data[0];
		$id_tipodoc_electronico = !isset($array_data[1])?'':$array_data[1];
		$serie_comprobante = !isset($array_data[2])?'':$array_data[2];
		$numero_comprobante = !isset($array_data[3])?'':$array_data[3];
		$tipo_envio_sunat = !isset($array_data[4])?'':$array_data[4];
		$tamanio = !isset($array_data[5])?'':$array_data[5];
		if($tamanio == 'ticket') {
			$tamanio = 'ticket';
		} else {
			$tamanio = 'a4';
		}

		$id_plantilla_pdf = $id_plantilla;

		//id_contribuyente||id_tipodoc_electronico||serie_documento||correlativo_documento||tipo_proceso||tamanio||plantilla
		if($id_plantilla > 0) {
			$cadena = "$id_contribuyente||$id_tipodoc_electronico||$serie_comprobante||$numero_comprobante||$tipo_envio_sunat||$tamanio||$id_plantilla_pdf";
		} else {
			$cadena = "$id_contribuyente||$id_tipodoc_electronico||$serie_comprobante||$numero_comprobante||$tipo_envio_sunat||$tamanio";
		}
		
		$id_sucursal = 0;
		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '31') {
			$documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '99') {
			$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->idsucursal;
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		}

		$resp_plantilla_pdf = $this->get_id_plantilla_pdf($id_sucursal, $documento->id_contribuyente, $id_tipodoc_electronico, $tamanio, $id_plantilla_pdf);

		$cadena_cifrada = $herramientas->encriptar($cadena);
		$this->print_pdf($cadena_cifrada, $mostrar_html);
		exit();
	}
	
	public function print_pdf($cadena_cifrada, $html = false) {
		$inicio_proceso = $this->inicio_ejecucion();
		//id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio||plantilla
		$herramientas = new HerramientasController;
		$cadena_decifrada = $herramientas->desencriptar($cadena_cifrada);
		//id_contribuyente||id_tipodoc_electronico||serie_comprobante||numero_comprobante||tipo_envio_sunat||tamanio||plantilla
		$array_data = explode('||', $cadena_decifrada);
		$id_contribuyente = !isset($array_data[0])?'':$array_data[0];
		$id_tipodoc_electronico = !isset($array_data[1])?'':$array_data[1];
		$serie_comprobante = !isset($array_data[2])?'':$array_data[2];
		$numero_comprobante = !isset($array_data[3])?'':$array_data[3];
		$tipo_envio_sunat = !isset($array_data[4])?'':$array_data[4];
		$tamanio = !isset($array_data[5])?'':$array_data[5];
		$id_plantilla_pdf = !isset($array_data[6])?'':$array_data[6];
		
		//1670||77|| ||1385||produccion||ticket
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}

		if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 320px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}
		//$img_logo = $ruta_base.$img_logo;
		
		if($tamanio != 'ticket' && $tamanio != 'a4') {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}

		$id_sucursal = 0;
		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '31') {
			$documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '99') {
			$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->idsucursal;
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		}
		
		if(!$documento) {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}
		
		$resp_plantilla_pdf = $this->get_id_plantilla_pdf($id_sucursal, $documento->id_contribuyente, $id_tipodoc_electronico, $tamanio, $id_plantilla_pdf);

		if($resp_plantilla_pdf['respuesta'] == 'error') {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}

		$id_plantilla_pdf = $resp_plantilla_pdf['id_plantilla_pdf'];

		$data['id_contribuyente'] = $id_contribuyente;
		$data['tipo_comprobante'] = $id_tipodoc_electronico;
		$data['serie_doc'] = $serie_comprobante;
		$data['numero_doc'] = $numero_comprobante;
		$data['tipo_envio_sunat'] = $tipo_envio_sunat;
		
		/*$this->print_pdf($cadena_cifrada, $mostrar_html);
		exit();*/

		$resp = $this->get_json_cpe($data);
		$data_cpe = $resp['cpe'];

		$guia_remision = new TemplateguiaremisionController;
		$doc_no_oficial = new TemplatedocnooficialController;
		$cpe = new TemplatecpeController;
		$guia_transportista = new TemplateguiatransportistaController;
		$theme_orden_compra = new TemplateordendecompraController;
		
		//FACTURAS, BOLETAS, NOTAS DE CRÉDITO, NOTAS DE DÉBITO
		if($id_plantilla_pdf == 1) {
			$data_cpe['id_plantilla'] = 1;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_1';
			$resp_html = $cpe->get_html_plantilla_a4_1($data_cpe);
		} else if($id_plantilla_pdf == 2) {
			$data_cpe['id_plantilla'] = 2;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_2';
			$resp_html = $cpe->get_html_plantilla_a4_2($data_cpe);

		} else if($id_plantilla_pdf == 3) {
			$data_cpe['id_plantilla'] = 3;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_3';
			$resp_html = $cpe->get_html_plantilla_a4_3($data_cpe);
			
		} else if($id_plantilla_pdf == 4) {
			$data_cpe['id_plantilla'] = 4;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_4';
			$resp_html = $cpe->get_html_plantilla_a4_4($data_cpe);
		} else if($id_plantilla_pdf == 5) {
			$data_cpe['id_plantilla'] = 5;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_5';
			$resp_html = $cpe->get_html_plantilla_a4_5($data_cpe);
		} else if($id_plantilla_pdf == 6) {
			$data_cpe['id_plantilla'] = 6;
			$data_cpe['function_name'] = '6';
			$data_cpe['function_name'] = 'get_html_plantilla_a4_6';
			$resp_html = $cpe->get_html_plantilla_a4_6($data_cpe);
		} else if($id_plantilla_pdf == 7) {
			$data_cpe['id_plantilla'] = 7;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_7';
			$resp_html = $cpe->get_html_plantilla_a4_7($data_cpe);
		} else if($id_plantilla_pdf == 10) {
			$data_cpe['id_plantilla'] = 10;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_1';
			$resp_html = $cpe->get_html_plantilla_ticket_1($data_cpe);
		} else if($id_plantilla_pdf == 11) {
			$data_cpe['id_plantilla'] = 11;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_2';
			$resp_html = $cpe->get_html_plantilla_ticket_2($data_cpe);
		} else if($id_plantilla_pdf == 12) {
			$data_cpe['id_plantilla'] = 12;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_3';
			$resp_html = $cpe->get_html_plantilla_ticket_3($data_cpe);
		} else if($id_plantilla_pdf == 62) {
			$data_cpe['id_plantilla'] = 62;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_4';
			$resp_html = $cpe->get_html_plantilla_ticket_4($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 95) {
			$data_cpe['id_plantilla'] = 95;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_95';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_95($data_cpe);
			
		} else if($id_plantilla_pdf == 69) {
			$data_cpe['id_plantilla'] = 69;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_5';
			$resp_html = $cpe->get_html_plantilla_ticket_5($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 92) {
			$data_cpe['id_plantilla'] = 92;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_6';
			$resp_html = $cpe->get_html_personalizada_ticket_6($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 96) {
			$data_cpe['id_plantilla'] = 96;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_6';
			$resp_html = $cpe->get_html_plantilla_ticket_6($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 36) {
			$data_cpe['id_plantilla'] = 36;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_9';
			$resp_html = $cpe->get_html_plantilla_a4_9($data_cpe);
		} else if($id_plantilla_pdf == 39) {
			$data_cpe['id_plantilla'] = 39;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_10';
			$resp_html = $cpe->get_html_plantilla_a4_10($data_cpe);
		} else if($id_plantilla_pdf == 42) {
			$data_cpe['id_plantilla'] = 42;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_11';
			$resp_html = $cpe->get_html_plantilla_a4_11($data_cpe);
		} else if($id_plantilla_pdf == 45) {
			$data_cpe['id_plantilla'] = 45;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_12';
			$resp_html = $cpe->get_html_plantilla_a4_12($data_cpe);
		} else if($id_plantilla_pdf == 48) {
			$data_cpe['id_plantilla'] = 48;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_13';
			$resp_html = $cpe->get_html_plantilla_a4_13($data_cpe);
		} else if($id_plantilla_pdf == 51) {
			$data_cpe['id_plantilla'] = 51;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_14';
			$resp_html = $cpe->get_html_plantilla_a4_14($data_cpe);
		} else if($id_plantilla_pdf == 55) {
			$data_cpe['id_plantilla'] = 55;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_15';
			$resp_html = $cpe->get_html_plantilla_a4_15($data_cpe);
		} else if($id_plantilla_pdf == 59) {
			$data_cpe['id_plantilla'] = 59;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_16';
			$resp_html = $cpe->get_html_plantilla_a4_16($data_cpe);
		} else if($id_plantilla_pdf == 64) {
			$data_cpe['id_plantilla'] = 64;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_17';
			$resp_html = $cpe->get_html_plantilla_a4_17($data_cpe);
		} else if($id_plantilla_pdf == 73) {
			$data_cpe['id_plantilla'] = 73;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_18';
			$resp_html = $cpe->get_html_plantilla_a4_18($data_cpe);
		} else if($id_plantilla_pdf == 77) {
			$data_cpe['id_plantilla'] = 77;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_19';
			$resp_html = $cpe->get_html_plantilla_a4_19($data_cpe);
		} else if($id_plantilla_pdf == 70) {
			$data_cpe['id_plantilla'] = 70;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_70';
			$resp_html = $cpe->get_html_personalizada_a4_70($data_cpe);

		} else if($id_plantilla_pdf == 78 || $id_plantilla_pdf == 81) {
			$data_cpe['id_plantilla'] = 78;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_78';
			$resp_html = $cpe->get_html_personalizada_a4_78($data_cpe);
		
		} else if($id_plantilla_pdf == 97) {
			$data_cpe['id_plantilla'] = 97;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_97';
			$resp_html = $cpe->get_html_personalizada_a4_97($data_cpe);

			
		

		//GUÍA DE REMISIÓN
		} else if($id_plantilla_pdf == 13) {
			$data_cpe['id_plantilla'] = 13;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_1';
			$resp_html = $guia_remision->get_html_plantilla_a4_1($data_cpe);
		} else if($id_plantilla_pdf == 14) {
			$data_cpe['id_plantilla'] = 14;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_2';
			$resp_html = $guia_remision->get_html_plantilla_a4_2($data_cpe);
		} else if($id_plantilla_pdf == 15) {
			$data_cpe['id_plantilla'] = 15;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_3';
			$resp_html = $guia_remision->get_html_plantilla_a4_3($data_cpe);
		} else if($id_plantilla_pdf == 16) {
			$data_cpe['id_plantilla'] = 16;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_4';
			$resp_html = $guia_remision->get_html_plantilla_a4_4($data_cpe);
		} else if($id_plantilla_pdf == 17) {
			$data_cpe['id_plantilla'] = 17;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_5';
			$resp_html = $guia_remision->get_html_plantilla_a4_5($data_cpe);
		} else if($id_plantilla_pdf == 18) {
			$data_cpe['id_plantilla'] = 18;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_6';
			$resp_html = $guia_remision->get_html_plantilla_a4_6($data_cpe);
		} else if($id_plantilla_pdf == 19) {
			$data_cpe['id_plantilla'] = 19;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_7';
			$resp_html = $guia_remision->get_html_plantilla_a4_7($data_cpe);
		} else if($id_plantilla_pdf == 38) {
			$data_cpe['id_plantilla'] = 38;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_9';
			$resp_html = $guia_remision->get_html_plantilla_a4_9($data_cpe);
		} else if($id_plantilla_pdf == 41) {
			$data_cpe['id_plantilla'] = 41;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_10';
			$resp_html = $guia_remision->get_html_plantilla_a4_10($data_cpe);
		} else if($id_plantilla_pdf == 44) {
			$data_cpe['id_plantilla'] = 44;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_11';
			$resp_html = $guia_remision->get_html_plantilla_a4_11($data_cpe);
		} else if($id_plantilla_pdf == 47) {
			$data_cpe['id_plantilla'] = 47;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_12';
			$resp_html = $guia_remision->get_html_plantilla_a4_12($data_cpe);
		} else if($id_plantilla_pdf == 50) {
			$data_cpe['id_plantilla'] = 50;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_13';
			$resp_html = $guia_remision->get_html_plantilla_a4_13($data_cpe);
		} else if($id_plantilla_pdf == 53) {
			$data_cpe['id_plantilla'] = 53;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_14';
			$resp_html = $guia_remision->get_html_plantilla_a4_14($data_cpe);
		} else if($id_plantilla_pdf == 57) {
			$data_cpe['id_plantilla'] = 57;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_15';
			$resp_html = $guia_remision->get_html_plantilla_a4_15($data_cpe);
		} else if($id_plantilla_pdf == 75) {
			$data_cpe['id_plantilla'] = 75;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_17';
			$resp_html = $guia_remision->get_html_plantilla_a4_17($data_cpe);
		} else if($id_plantilla_pdf == 72) {
			$data_cpe['id_plantilla'] = 72;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_72';
			$resp_html = $guia_remision->get_html_personalizada_a4_72($data_cpe);
		} else if($id_plantilla_pdf == 80 || $id_plantilla_pdf == 83) {
			$data_cpe['id_plantilla'] = 80;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_80';
			$resp_html = $guia_remision->get_html_personalizada_a4_80($data_cpe);
		} else if($id_plantilla_pdf == 99) {
			$data_cpe['id_plantilla'] = 99;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_16';
			$resp_html = $guia_remision->get_html_plantilla_a4_16($data_cpe);
			
		//NOTAS DE VENTA Y COTIZACIONES
		} else if($id_plantilla_pdf == 21) {
			$data_cpe['id_plantilla'] = 21;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_1';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_1($data_cpe);
		} else if($id_plantilla_pdf == 22) {
			$data_cpe['id_plantilla'] = 22;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_2';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_2($data_cpe);
		} else if($id_plantilla_pdf == 23) {
			$data_cpe['id_plantilla'] = 23;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_3';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_3($data_cpe);
		} else if($id_plantilla_pdf == 24) {
			$data_cpe['id_plantilla'] = 24;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_4';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_4($data_cpe);
		} else if($id_plantilla_pdf == 25) {
			$data_cpe['id_plantilla'] = 25;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_5';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_5($data_cpe);
		} else if($id_plantilla_pdf == 26) {
			$data_cpe['id_plantilla'] = 26;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_6';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_6($data_cpe);
		} else if($id_plantilla_pdf == 27) {
			$data_cpe['id_plantilla'] = 27;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_7';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_7($data_cpe);
		} else if($id_plantilla_pdf == 37) {
			$data_cpe['id_plantilla'] = 37;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_9';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_9($data_cpe);
		} else if($id_plantilla_pdf == 40) {
			$data_cpe['id_plantilla'] = 40;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_10';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_10($data_cpe);
		} else if($id_plantilla_pdf == 43) {
			$data_cpe['id_plantilla'] = 43;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_11';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_11($data_cpe);
		} else if($id_plantilla_pdf == 46) {
			$data_cpe['id_plantilla'] = 46;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_12';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_12($data_cpe);
		} else if($id_plantilla_pdf == 49) {
			$data_cpe['id_plantilla'] = 49;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_13';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_13($data_cpe);
		} else if($id_plantilla_pdf == 52) {
			$data_cpe['id_plantilla'] = 52;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_14';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_14($data_cpe);
		} else if($id_plantilla_pdf == 56) {
			$data_cpe['id_plantilla'] = 56;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_15';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_15($data_cpe);
		} else if($id_plantilla_pdf == 66) {
			$data_cpe['id_plantilla'] = 66;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_17';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_17($data_cpe);
		} else if($id_plantilla_pdf == 74) {
			$data_cpe['id_plantilla'] = 74;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_18';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_18($data_cpe);
		} else if($id_plantilla_pdf == 89) {
			$data_cpe['id_plantilla'] = 89;
			$data_cpe['function_name'] = 'get_html_plantilla_a4_89';
			$resp_html = $doc_no_oficial->get_html_plantilla_a4_89($data_cpe);
		} else if($id_plantilla_pdf == 30) {
			$data_cpe['id_plantilla'] = 30;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_1';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_1($data_cpe);
		} else if($id_plantilla_pdf == 31) {
			$data_cpe['id_plantilla'] = 31;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_2';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_2($data_cpe);
		} else if($id_plantilla_pdf == 32) {
			$data_cpe['id_plantilla'] = 32;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_3';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_3($data_cpe);
		} else if($id_plantilla_pdf == 63) {
			$data_cpe['id_plantilla'] = 63;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_4';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_4($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 98) {
			$data_cpe['id_plantilla'] = 98;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_6';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_6($data_cpe);
		} else if($id_plantilla_pdf == 76) {
			$data_cpe['id_plantilla'] = 76;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_5';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_5($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 90) {
			$data_cpe['id_plantilla'] = 90;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_90';
			$resp_html = $doc_no_oficial->get_html_plantilla_ticket_90($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 93) {
			$data_cpe['id_plantilla'] = 93;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_6';
			$resp_html = $doc_no_oficial->get_html_personalizada_ticket_6($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4
		} else if($id_plantilla_pdf == 94) {
			$data_cpe['id_plantilla'] = 94;
			$data_cpe['function_name'] = 'get_html_plantilla_personalizada_a4_94';
			$resp_html = $doc_no_oficial->get_html_plantilla_personalizada_a4_94($data_cpe); //anteriormente decia: get_html_plantilla_ticket_4

		} else if($id_plantilla_pdf == 67) {
			$data_cpe['id_plantilla'] = 67;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_67';
			$resp_html = $doc_no_oficial->get_html_personalizada_a4_67($data_cpe);
		} else if($id_plantilla_pdf == 68) {
			$data_cpe['id_plantilla'] = 68;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_68';
			$resp_html = $doc_no_oficial->get_html_personalizada_a4_68($data_cpe);
		} else if($id_plantilla_pdf == 71) {
			$data_cpe['id_plantilla'] = 1;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_71';
			$resp_html = $doc_no_oficial->get_html_personalizada_a4_71($data_cpe);
		} else if($id_plantilla_pdf == 79 || $id_plantilla_pdf == 82) {
			$data_cpe['id_plantilla'] = 79;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_79';
			$resp_html = $doc_no_oficial->get_html_personalizada_a4_79($data_cpe);
		} else if($id_plantilla_pdf == 100) {
			$data_cpe['id_plantilla'] = 100;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_100';
			$resp_html = $doc_no_oficial->get_html_personalizada_a4_100($data_cpe);

		//GUÍAS DE REMISIÓN - TICKET	
		} else if($id_plantilla_pdf == 33) {
			$data_cpe['id_plantilla'] = 33;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_1';
			$resp_html = $guia_remision->get_html_plantilla_ticket_1($data_cpe);
		} else if($id_plantilla_pdf == 34) {
			$data_cpe['id_plantilla'] = 34;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_2';
			$resp_html = $guia_remision->get_html_plantilla_ticket_2($data_cpe);
		} else if($id_plantilla_pdf == 35) {
			$data_cpe['id_plantilla'] = 35;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_3';
			$resp_html = $guia_remision->get_html_plantilla_ticket_3($data_cpe);


		//GUÍAS TRANSPORTISTA: A4
		} else if($id_plantilla_pdf == 84) {
			$data_cpe['id_plantilla'] = 84;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_84';
			$resp_html = $guia_transportista->get_html_personalizada_a4_84($data_cpe);
		//GUÍAS TRANSPORTISTA: TICKET
		} else if($id_plantilla_pdf == 85) {
			$data_cpe['id_plantilla'] = 85;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_85';
			$resp_html = $guia_transportista->get_html_plantilla_ticket_85($data_cpe);
			

		//ORDEN DE COMPRA
		} else if($id_plantilla_pdf == 87) {
			$data_cpe['id_plantilla'] = 87;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_87';
			$resp_html = $theme_orden_compra->get_html_personalizada_a4_87($data_cpe);
		} else if($id_plantilla_pdf == 91) {
			$data_cpe['id_plantilla'] = 91;
			$data_cpe['function_name'] = 'get_html_personalizada_a4_91';
			$resp_html = $theme_orden_compra->get_html_plantilla_a4_91($data_cpe);
		//GUÍAS TRANSPORTISTA: TICKET
		} else if($id_plantilla_pdf == 88) {
			$data_cpe['id_plantilla'] = 88;
			$data_cpe['function_name'] = 'get_html_plantilla_ticket_88';
			$resp_html = $theme_orden_compra->get_html_plantilla_ticket_88($data_cpe);
		}
		
		if($html) {
			echo $resp_html['html'];
			exit();
		}

		$url_logo_patrocinador = $data_cpe['patrocinador']['url_logo_patrocinador'];
		$dominio_patrocinador = $data_cpe['patrocinador']['dominio'];
		
		$nombre_archivo = $serie_comprobante.'-'.$numero_comprobante.'.pdf';
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		$snappy->setOption('enable-local-file-access', true);
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$nombre_archivo.'"');
		
		if($tamanio == 'ticket') {
			$snappy->setOption('margin-left', '1mm');
			$snappy->setOption('margin-right', '1mm');
			$snappy->setOption('margin-top', '2mm');
			$snappy->setOption('margin-bottom', '5mm');
			if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
				$snappy->setOption('zoom', $this->snappy_zoom);
			}
			
			$num_items = $this->get_num_items($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat);
			
			$factor_multiplicacion = 12;
			if($id_tipodoc_electronico == '09') {
				$factor_multiplicacion = 25;
			}
			
			$alto_total = 250 + $num_items*$factor_multiplicacion;
			
			$html_footer = '
			<div style="margin: 0; padding-left: 0; padding-right: 0; padding-top: 0; padding-bottom: 15px;">
				<div class="text-center">
					<img src="'.$url_logo_patrocinador.'" style="width: 140px !important;" class="margin-0"><p class="margin-0" style="font-style: italic; font-size:12px;">Emitido por: <span class="font-weight-bold">'.$dominio_patrocinador.'</span></p>
				</div>
			</div>
    		';
			
			echo $snappy->getOutputFromHtml($resp_html['html'].$html_footer, array('page-height' =>  $alto_total,'page-width' => 78));
		} else {
			
			$html_header = '
    		<!DOCTYPE html>
    		<html>
			<body style="margin: 0; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px;">
    				<div class="text-center" style="height: 50px">
    					<p>hola</p>
    				</div>
    			</body>
    		</html>
    		';
			
			$html_footer = '
    		<!DOCTYPE html>
    		<html>
    			<style>
    				.text-center{
    					text-align: center;
    				}
    				.font-weight-bold{
    					font-weight: bold;
    				}
    				.margin-0{
    					margin: 0;
    					padding-left: 0;
						padding-right: 0;
						padding-top: 0;
						padding-bottom: 0;
    				}
    			</style>
    			<body style="margin: 0; padding-left: 0; padding-right: 0; padding-top: 0; padding-bottom: 0px;">
    				<div class="text-center">
    					<img src="'.$url_logo_patrocinador.'" style="width: 140px !important;" class="margin-0"><p class="margin-0" style="font-style: italic; font-size:12px;">Emitido por: <span class="font-weight-bold">'.$dominio_patrocinador.'</span></p>
    				</div>
    			</body>
    		</html>
    		';
        		
			$snappy->setOption('header-html', $html_header);
			$snappy->setOption('footer-html', $html_footer);

			$snappy->setOption('margin-top', '2mm');
			$snappy->setOption('margin-left', '9mm');
			$snappy->setOption('margin-right', '9mm');
			if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
				$snappy->setOption('zoom', $this->snappy_zoom);
			}

			//$snappy->setOption('margin-top', '8mm');
			//$snappy->setOption('margin-bottom', '8mm');
			
			echo $snappy->getOutputFromHtml($resp_html['html']);
		}
		
		exit();
	}

	public function get_id_plantilla_pdf($id_sucursal, $id_contribuyente, $id_tipodoc_electronico, $tamanio, $id_plantilla_pdf = '') {
		if(empty($id_plantilla_pdf)) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Sucursal';
				$resp['mensaje'] = 'No existe la sucursal asignada al documento';
				return $resp;
			}

			if($tamanio == 'a4') {
				if($id_tipodoc_electronico == '01') { //factura
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_factura_a4)?1:$sucursal->id_plantillapdf_factura_a4;
				} else if($id_tipodoc_electronico == '03') { //boleta
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_boleta_a4)?1:$sucursal->id_plantillapdf_boleta_a4;
				} else if($id_tipodoc_electronico == '07') { //nota de crédito
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_notacredito_a4)?1:$sucursal->id_plantillapdf_notacredito_a4;
				} else if($id_tipodoc_electronico == '08') { //nota de débito
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_notadebito_a4)?1:$sucursal->id_plantillapdf_notadebito_a4;
				} else if($id_tipodoc_electronico == '09') { //guía de remisión
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_guiaremision_a4)?13:$sucursal->id_plantillapdf_guiaremision_a4;
				} else if($id_tipodoc_electronico == '77') { //Nota de Venta
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_notaventa_a4)?21:$sucursal->id_plantillapdf_notaventa_a4;
				} else if($id_tipodoc_electronico == '88') { //Cotización
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_cotizacion_a4)?21:$sucursal->id_plantillapdf_cotizacion_a4;
				} else if($id_tipodoc_electronico == '31') { //Guía Transportista
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_guiatransportista_a4)?84:$sucursal->id_plantillapdf_guiatransportista_a4;
				} else if($id_tipodoc_electronico == '99') { //Orden de Compra
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_ordencompra_a4)?87:$sucursal->id_plantillapdf_ordencompra_a4;
				}

				if(empty($id_plantilla_pdf)) {
					$id_plantilla_pdf = 1;
				}
			} else if($tamanio == 'ticket') {
				if($id_tipodoc_electronico == '01') { //factura
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_factura_ticket)?10:$sucursal->id_plantillapdf_factura_ticket;
				} else if($id_tipodoc_electronico == '03') { //boleta
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_boleta_ticket)?10:$sucursal->id_plantillapdf_boleta_ticket;
				} else if($id_tipodoc_electronico == '07') { //nota de crédito
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_notacredito_ticket)?10:$sucursal->id_plantillapdf_notacredito_ticket;
				} else if($id_tipodoc_electronico == '08') { //nota de débito
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_notadebito_ticket)?10:$sucursal->id_plantillapdf_notadebito_ticket;
				} else if($id_tipodoc_electronico == '09') { //guía de remisión
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_guiaremision_ticket)?33:$sucursal->id_plantillapdf_guiaremision_ticket;
				} else if($id_tipodoc_electronico == '77') { //Nota de Venta
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_notaventa_ticket)?30:$sucursal->id_plantillapdf_notaventa_ticket;
				} else if($id_tipodoc_electronico == '88') { //Cotización
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_cotizacion_ticket)?30:$sucursal->id_plantillapdf_cotizacion_ticket;
				} else if($id_tipodoc_electronico == '31') { //Guía Transportista
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_guiatransportista_ticket)?85:$sucursal->id_plantillapdf_guiatransportista_ticket;
				} else if($id_tipodoc_electronico == '99') { //Orden de Compra
					$id_plantilla_pdf = empty($sucursal->id_plantillapdf_ordencompra_ticket)?88:$sucursal->id_plantillapdf_ordencompra_ticket;
				}
			}
		}

		$array_docs_validos = array();
		$plantillapdf = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf: and tamanio = :tamanio:", 'bind' => array('id_plantillapdf' => $id_plantilla_pdf, 'tamanio' => $tamanio)));
		if(!$plantillapdf) {
			
		} else {
			$array_docs_validos = json_decode($plantillapdf->ids_tipodocelectronico);
		}

		if(!in_array($id_tipodoc_electronico, $array_docs_validos)) {
			if($tamanio == 'a4') {
				if($id_tipodoc_electronico == '01') { //factura
					$id_plantilla_pdf = 1;
				} else if($id_tipodoc_electronico == '03') { //boleta
					$id_plantilla_pdf = 1;
				} else if($id_tipodoc_electronico == '07') { //nota de crédito
					$id_plantilla_pdf = 1;
				} else if($id_tipodoc_electronico == '08') { //nota de débito
					$id_plantilla_pdf = 1;
				} else if($id_tipodoc_electronico == '09') { //guía de remisión
					$id_plantilla_pdf = 13;
				} else if($id_tipodoc_electronico == '77') { //Nota de Venta
					$id_plantilla_pdf = 21;
				} else if($id_tipodoc_electronico == '88') { //cotizacion
					$id_plantilla_pdf = 21;
				} else if($id_tipodoc_electronico == '31') { //Guía Transportista
					$id_plantilla_pdf = 84;
				} else if($id_tipodoc_electronico == '99') { //Orden de Compra
					$id_plantilla_pdf = 87;
				}
			} else if($tamanio == 'ticket') {
				if($id_tipodoc_electronico == '01') { //factura
					$id_plantilla_pdf = 10;
				} else if($id_tipodoc_electronico == '03') { //boleta
					$id_plantilla_pdf = 10;
				} else if($id_tipodoc_electronico == '07') { //nota de crédito
					$id_plantilla_pdf = 10;
				} else if($id_tipodoc_electronico == '08') { //nota de débito
					$id_plantilla_pdf = 10;
				} else if($id_tipodoc_electronico == '09') { //guía de remisión
					$id_plantilla_pdf = 33;
				} else if($id_tipodoc_electronico == '77') { //Nota de Venta
					$id_plantilla_pdf = 30;
				} else if($id_tipodoc_electronico == '88') { //cotizacion
					$id_plantilla_pdf = 30;
				} else if($id_tipodoc_electronico == '31') { //Guía Transportista
					$id_plantilla_pdf = 85;
				} else if($id_tipodoc_electronico == '99') { //Orden de Compra
					$id_plantilla_pdf = 88;
				}
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['id_plantilla_pdf'] = $id_plantilla_pdf;
		return $resp;
	}

	public function get_num_items($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat) {
		
		$array_docs_sunat = array('01', '03', '07', '08', '09');
		$array_docs_otros = array('77', '88');

		if(in_array($id_tipodoc_electronico, $array_docs_sunat)) {
			$documentoelectronico = new DocumentoelectronicoController;

			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

			$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($documento->id_contribuyente, $documento->id_tipodoc_electronico, $documento->serie_comprobante, $documento->numero_comprobante);
			$detalle = $resp_detalle['detalle'];
			$num_items = count($detalle);		
		} else if(in_array($id_tipodoc_electronico, $array_docs_otros)) {
			$documentoelectronico = new DocumentoelectronicoController;

			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico,'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));

			$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($documento->id_contribuyente, $documento->id_tipodocumento, $documento->numero_comprobante, $documento->modalidad);
			$detalle = $resp_detalle['detalle'];
			$num_items = count($detalle);
		} else {
			$num_items = 1;
		}

		if($num_items <= 0) {
			$num_items = 1;
		}

		return $num_items;
	}

	//función que retorna un array con los datos del emisor
	public function get_data_emisor($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$ruta_base = $this->ruta_base_public_html;
		

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$img_logo_cuadrado = empty($contribuyente->img_logo)?'':str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		$img_logo_rectangular = empty($contribuyente->logo_350)?'':str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);

		$img_logo_cuadrado = empty($img_logo_cuadrado)?'':$ruta_base.$img_logo_cuadrado;
		$img_logo_rectangular = empty($img_logo_rectangular)?'':$ruta_base.$img_logo_rectangular;

		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '31'){
			$documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '99'){
			$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->idsucursal;
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		}

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$ubigeo_ubicacion = '';
		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if($ubigeo) {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

		$emisor = array(
			'razon_social' 			=> $contribuyente->razon_social,
			'nombre_comercial' 		=> $contribuyente->nombre_comercial,
			'direccion' 			=> $sucursal->direccion,
			'ubigeo' 				=> $ubigeo_ubicacion, //ubicación
			'telefono' 				=> $sucursal->telefono,
			'email' 				=> $sucursal->email,
			'sitio_web' 			=> $sucursal->sitio_web,
			'ruc' 					=> $contribuyente->ruc,
			'logo_rectangular'		=> $img_logo_rectangular,
			'logo_cuadrado' 		=> $img_logo_cuadrado,
			'tipo_envio_sunat'		=> $tipo_envio_sunat,
			'ruta_base'				=> $ruta_base
		);
		
		$resp['respuesta'] = 'ok';
		$resp['emisor'] = $emisor;
		return $resp;
	}

	//función que retorna un array con los datos del cliente
	public function get_data_cliente($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$herramientas = new HerramientasController;

		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
		}

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));

		if($cliente->id_tipodocidentidad == '0') {
			$doc_identificacion_siglas = 'Otro.Doc.';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == '1') {
			$doc_identificacion_siglas = 'D.N.I.';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == '4') {
			$doc_identificacion_siglas = 'Carnet Ext.';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == '6') {
			$doc_identificacion_siglas = 'R.U.C.';
			$doc_identificacion_titulo = 'Razón Social';
		} else if($cliente->id_tipodocidentidad == '7') {
			$doc_identificacion_siglas = 'Pasaporte';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == 'A') {
			$doc_identificacion_siglas = 'Ced.Dipl.Ident.';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == 'B') {
			$doc_identificacion_siglas = 'Doc.Ident.Res.No.Domic.';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == 'C') {
			$doc_identificacion_siglas = 'T.I.N';
			$doc_identificacion_titulo = 'Nombre';
		} else if($cliente->id_tipodocidentidad == 'D') {
			$doc_identificacion_siglas = 'I.N.';
			$doc_identificacion_titulo = 'Nombre';
		} else {
			$doc_identificacion_siglas = 'Num.Doc.';
			$doc_identificacion_titulo = 'Nombre';
		} 

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
				$documento_dir_destino = str_replace("–", " ", $documento->dir_destino ?? '');
				$documento_dir_destino = preg_replace('/\s\s+/', ' ', $documento_dir_destino);
				$documento_dir_destino = preg_replace('/\s\s+/', ' ', $documento_dir_destino);
				
				$ubicacion_dir_cliente = str_replace("–", " ", $ubicacion_dir_cliente);
				$ubicacion_dir_cliente = preg_replace('/\s\s+/', ' ', $ubicacion_dir_cliente);

				if(strpos($herramientas->eliminar_tildes(strtoupper($documento_dir_destino)), $herramientas->eliminar_tildes(strtoupper($ubicacion_dir_cliente))) !== false) {
					$direccion_fiscal = $documento_dir_destino;
				} else {
					$direccion_fiscal = $documento_dir_destino.' '.$ubicacion_dir_cliente;
				}
			}
		}

		$num_documento = $cliente->num_doc;
		if (strpos($cliente->num_doc, 'sdi') !== false) {
			$num_documento = '';
		}
		
		$cliente = array(
			'nombre'              		=> ucwords($cliente->razon_social),
			'doc_identificacion_titulo' => $doc_identificacion_titulo,
			'doc_identificacion_id'		=> $cliente->id_tipodocidentidad,
			'doc_identificacion_siglas' => $doc_identificacion_siglas,
			'doc_identificacion_numero' => $num_documento,
			'direccion_fiscal'			=> $direccion_fiscal,
			'telefono'					=> $cliente->celular
		);

		$resp['respuesta'] = 'ok';
		$resp['cliente'] = $cliente;
		return $resp;
	}

	//función que retorna un array con los items del cpe (facturas, boletas, notas de crédito, débito, notas de venta)
	public function get_detalle_cpe($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$documentoelectronico = new DocumentoelectronicoController;
		$herramientas = new HerramientasController;

		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

			$resp_detalle = $documentoelectronico->get_detalle_documento_guardado($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante);

		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));

			$resp_detalle = $documentoelectronico->get_detalle_documento_no_oficial($id_contribuyente, $id_tipodoc_electronico, $numero_comprobante, $tipo_envio_sunat);
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

		$simbolo_moneda = '';
		if($sunatmoneda) {
			$simbolo_moneda = $sunatmoneda->simbolo;
		}

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}

		if($resp_detalle['respuesta'] == 'error') {
			
		}

		$factor_igv_sunat = floatval($documento->porcentaje_igv) + 0;
		$factor_igv_sunat = round($factor_igv_sunat/100, 3);
		$this->factor_igv_sunat = $factor_igv_sunat;

		$opcion_mostrar_items_igv = 'si';
		if($id_tipodoc_electronico == '01') {
			$opcion_mostrar_items_igv = ($sucursal->factura_mostrar_items_igv == 'si')?'si':'no';
		} else if($id_tipodoc_electronico == '03') {
			$opcion_mostrar_items_igv = ($sucursal->boleta_mostrar_items_igv == 'si')?'si':'no';
		} else if($id_tipodoc_electronico == '07') {
			$opcion_mostrar_items_igv = ($sucursal->notacredito_mostrar_items_igv == 'si')?'si':'no';
		} else if($id_tipodoc_electronico == '08') {
			$opcion_mostrar_items_igv = ($sucursal->notadebito_mostrar_items_igv == 'si')?'si':'no';
		} else if($id_tipodoc_electronico == '77') {
			$opcion_mostrar_items_igv = ($sucursal->notaventa_mostrar_items_igv == 'si')?'si':'no';
		} else if($id_tipodoc_electronico == '88') {
			$opcion_mostrar_items_igv = ($sucursal->cotizacion_mostrar_items_igv == 'si')?'si':'no';
		}

		$detalle = $resp_detalle['detalle'];
		$lista_item = array();
		foreach($detalle as $item) {


			if (strpos($item['TEXT_TIPO_OPERACION_DET'] ?? '', '[') !== false) {
				$text_afectacion = $herramientas->get_string_between($item['TEXT_TIPO_OPERACION_DET'], '[', ']');
			} else {
				if (strpos($item['TEXT_TIPO_OPERACION_DET']  ?? '', '-') !== false) {
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
					$precio_unitario_item = round($item['PRECIO_DET']/(1 + $factor_igv_sunat), $num_decimales);
					$subtotal_item = floatval($item['IMPORTE_DET']);
				}
			} else {
				$precio_unitario_item = $item['PRECIO_DET'];
				$subtotal_item =  floatval($item['IGV_DET']) + floatval($item['IMPORTE_DET']);
			}

			$lista_item[] = array(
				'nro_item'				=> $item['ITEM_DET'],
				'codigo' 				=> $item['CODIGO_PRODUCTO'],
				'descripcion'			=> $item['DESCRIPCION_DET'],
				'cantidad'				=> $item['CANTIDAD_DET'] + 0,
				'texto_tipo_operacion'	=> $text_afectacion,
				'precio_unitario'		=> $precio_unitario_item + 0,
				'subtotal_item'			=> $subtotal_item + 0,
				'id_tipoafectacionigv' 	=> $item['COD_TIPO_OPERACION_DET'],
				'unidad_medida' 		=> $item['UNIDAD_MEDIDA_NOMBRE'],
				'simbolo_moneda'		=> $simbolo_moneda,
				'peso_det' 				=> !isset($item['PESO_DET'])?'':$item['PESO_DET'],
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista_item;
		return $resp;
	}

	//función que retorna el listado de cuentas de una empresa
	public function get_cuentas_banco($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$herramientas = new HerramientasController;

		$cuentas = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$lista_de_cuentas = array();
		foreach($cuentas as $cuenta) {
			$tipo_cuenta_banco = '';
			//cuenta_corriente, cuenta_ahorro, cuenta_detracciones
			if($cuenta->tipo_cuenta == 'cuenta_corriente') {
				$tipo_cuenta_banco = 'Cuenta Corriente';
			} else if($cuenta->tipo_cuenta == 'cuenta_ahorro') {
				$tipo_cuenta_banco = 'Cuenta de Ahorro';
			} else if($cuenta->tipo_cuenta == 'cuenta_detracciones') {
				$tipo_cuenta_banco = 'Cuenta Detracción';
			}
			$nombre_moneda = ($cuenta->id_codigomoneda=='PEN')?'Soles':'Dólares';
			
			$url_logo_banco = $herramientas->get_logo_banco($cuenta);

			$lista_de_cuentas[] = array(
				'nombre_banco' 		=> $cuenta->nombre_banco,
				'tipo' 				=> $cuenta->tipo_cuenta,
				'titulo'			=> $tipo_cuenta_banco,
				'url_logo_banco'	=> $url_logo_banco,
				'cci'				=> empty($cuenta->cci)?'':$cuenta->cci,
				'numero'			=> empty($cuenta->nro_cuenta)?'':$cuenta->nro_cuenta,
				'nombre_moneda' 	=> $nombre_moneda,
				'titular'			=> $cuenta->nombre_titular
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['cuentas_banco'] = $lista_de_cuentas;
		return $resp;
	}

	//función que retorna los datos del usuario vendedor
	public function get_data_vendedor($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$documentoelectronico = new DocumentoelectronicoController;
		$herramientas = new HerramientasController;

		$id_vendedor = 0;
		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_vendedor = $documento->id_vendedor;
		} else if($id_tipodoc_electronico == '31') {
			$documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_vendedor = $documento->id_vendedor;
		} else if($id_tipodoc_electronico == '99') {
			$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_vendedor = $documento->id_usuario;
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
			$id_vendedor = $documento->id_vendedor;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$vendedor = Usuario::findFirst(array("idusuario = :idusuario:", "bind" => array('idusuario' => $id_vendedor)));

		$data_vendedor = array(
			'idusuario' => $vendedor->idusuario,
			'nombre'	=> $vendedor->nombre,
			'apellido'	=> $vendedor->apellido
		);

		$resp['respuesta'] = 'ok';
		$resp['vendedor'] = $data_vendedor;
		return $resp;
	}

	//función que retorna los datos del patrocinado
	public function get_data_patrocinador($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$documentoelectronico = new DocumentoelectronicoController;
		$herramientas = new HerramientasController;

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		
		$data_patrocinador = $this->get_parametros_iniciales();
		$ruta_consulta_documento = 'https://'.$this->dominio_principal.'/facturacionv8/consultas/index/'.$contribuyente->id_contribuyente;

		if($herramientas->esUrlAbsoluta($data_patrocinador['logo_img_461'])) {
            $logo_patrocinador = $data_patrocinador['logo_img_461'];
        } else {
            $logo_patrocinador = 'https://'.$data_patrocinador['url_domain'].$data_patrocinador['logo_img_461'];    
        }

		$data_patrocinador = array(
			'dominio'				=> $data_patrocinador['url_domain'],
			'url_logo_patrocinador'	=> $logo_patrocinador,
			'url_consulta_cpe'		=> $ruta_consulta_documento
		);

		$resp['respuesta'] = 'ok';
		$resp['patrocinador'] = $data_patrocinador;
		return $resp;
	}

	//función que retorna la cabecera del comprobante
	public function get_cabecera_cpe($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$estado_documento = 'aprobado';

		$id_sucursal = 0;
		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;

			if($documento->estado_envio_sunat == 'rechazado' || $documento->estado_envio_sunat == 'anulado') {
				$estado_documento = 'anulado';
			}

		} else if($id_tipodoc_electronico == '31') {
			$documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;

			if($documento->estado_envio_sunat == 'rechazado' || $documento->estado_envio_sunat == 'anulado') {
				$estado_documento = 'anulado';
			}

		} else if($id_tipodoc_electronico == '99') {
			$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->idsucursal;

			if($documento->estado != 'activo') {
				$estado_documento = 'anulado';
			}

		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;

			if($documento->estado_documento == 'inactivo') {
				$estado_documento = 'anulado';
			}
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));
		$simbolo_moneda = '';
		if($sunatmoneda) {
			$simbolo_moneda = $sunatmoneda->simbolo;
			$nombre_moneda_doc = $sunatmoneda->nombre;
		}
		if($id_tipodoc_electronico == '09') {
			$nombre_moneda_doc = '';
		}
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));

		$herramientas = new HerramientasController;
		$cuentasporcobrar = new CuentasporcobrarController;

		//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION | TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE | CODIGO HASH | VALOR DE LA FIRMA |
		$codigo_hash = empty($documento->hash_cpe)?'':$documento->hash_cpe;

		$texto_qr_documento = "$contribuyente->ruc|$id_tipodoc_electronico|$serie_comprobante|$numero_comprobante|$documento->total_igv|$documento->total|".date("d/m/Y",strtotime($documento->fecha_registro))."|$cliente->id_tipodocidentidad|$cliente->num_doc|$codigo_hash|";

		if (!file_exists($this->ruta_base_files.'/'.$contribuyente->ruc.'/')) {
			mkdir($this->ruta_base_files.'/'.$contribuyente->ruc.'/', 0777, true);
		}
		
		$ruta_qr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.uniqid().'_'.$id_tipodoc_electronico.'_'.$serie_comprobante.'_'.$numero_comprobante.'.png';
		
		$result = Builder::create()
			->writer(new PngWriter())   // Definir el formato del código QR (PNG)
			->data($texto_qr_documento) // Contenido del QR
			->size(300)                 // Tamaño del QR
			->margin(5)                // Margen alrededor del QR
			->build();

		// Guardar en un archivo
		$result->saveToFile($ruta_qr);

		$nombre_cpe = 'Comprobante Electrónico';
		if($id_tipodoc_electronico == '01') {
			$nombre_cpe = 'Factura Electrónica';
		} else if($id_tipodoc_electronico == '03') {
			$nombre_cpe = 'Boleta de Venta Electrónica';
		} else if($id_tipodoc_electronico == '07') {
			$nombre_cpe = 'Nota de Crédito';
		} else if($id_tipodoc_electronico == '08') {
			$nombre_cpe = 'Nota de Débito';
		} else if($id_tipodoc_electronico == '09') {
			$nombre_cpe = 'Guía de Remisión Remitente Electrónica';
		} else if($id_tipodoc_electronico == '77') {
			$nombre_cpe = 'Nota de Venta';
		} else if($id_tipodoc_electronico == '88') {
			$nombre_cpe = 'Cotización';
		}

		/*
		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_documento = date("d-m-Y / H:i A", strtotime($documento->fecha_registro));			
		} else {
			$fecha_documento = date("d-m-Y", strtotime($documento->fecha_comprobante));
		}
		*/

		if(date("Y-m-d", strtotime($documento->fecha_registro)) == date("Y-m-d", strtotime($documento->fecha_comprobante))) {
			$fecha_documento = $documento->fecha_registro;			
		} else {
			$fecha_documento = $documento->fecha_comprobante;
		}
		
		$array_lista_guias = array();
		$lista_docs_referencia ='';

		if(isset($documento->array_docs_referencia)) {
			$array_docs_referencia = json_decode($documento->array_docs_referencia);
			if(is_array($array_docs_referencia)) {
				foreach($array_docs_referencia as $doc_referencia) {
					$array_lista_guias[] = $lista_docs_referencia.$doc_referencia->serie_comprobante.'-'.$doc_referencia->numero_comprobante;
				}
			}
	
			if(count($array_lista_guias) > 0) {
				$lista_docs_referencia = implode(', ', $array_lista_guias);
			}
		}

		//validamos si tiene detracción
		$aplica_detracion = 'no';
		$data_detraccion = array();

		if(isset($documento->detraccion_iddetraccion) && !empty($documento->detraccion_iddetraccion)) {
			$aplica_detracion = 'si';

			$tipo_cambio_sunat_detr = empty($documento->tipo_cambio_sunat)?1:$documento->tipo_cambio_sunat;
			$monto_detraccion = round($documento->detraccion_monto, 2);

			$monto_destraccion_usd = '';
			if($documento->id_codigomoneda == 'USD') {
				$tipo_cambio_sunat_detr = empty($documento->tipo_cambio_sunat)?1:$documento->tipo_cambio_sunat;
				$monto_detraccion = round($tipo_cambio_sunat_detr*$documento->detraccion_monto, 2);
				$monto_destraccion_usd = $documento->detraccion_monto;
			}

			$redondear_detraccion_pdf = $this->get_contribuyente_opcion($id_contribuyente, 'redondear_detraccion_pdf');

			$data_detraccion = array(
				'detraccion_porcentaje'		=> $documento->detraccion_porcentaje,
				'detraccion_monto'			=> ($redondear_detraccion_pdf == 'si')?round($monto_detraccion, 0):$monto_detraccion,
				'detraccion_texto'			=> $documento->detraccion_texto,
				'detraccion_cuenta'			=> $documento->detraccion_cuenta,
				'detraccion_moneda'			=> 'PEN',
				'detraccion_simbolo_moneda'	=> 'S/',
				'tipo_cambio_sunat_detr'	=> $tipo_cambio_sunat_detr,
				'monto_destraccion_usd'		=> $monto_destraccion_usd
			);
		}

		//validamos si tiene retención
		$aplica_retencion = 'no';
		$data_retencion = array();
		
		if(isset($documento->retencion_aplica) && $documento->retencion_aplica == 'si') {
			$aplica_retencion = 'si';
			$data_retencion = array(
				'retencion_porcentaje'		=> $documento->retencion_factor*100,
				'retencion_simbolo_moneda'	=> $sunatmoneda->simbolo,
				'retencion_codigo_moneda'	=> $documento->id_codigomoneda,
				'retencion_base'       		=> $documento->retencion_base,
				'retencion_monto'			=> $documento->retencion_monto
			);
		}
		
		//validamos si tiene percepcion
		$aplica_percepcion = 'no';
		$data_percepcion = array();
		
		if(isset($documento->id_tipo_operacion) && $documento->id_tipo_operacion == '2001') {
			$aplica_percepcion = 'si';
			$data_percepcion = array(
				'percepcion_porcentaje'		=> ($documento->percepcion_porcentaje + 0),
				'percepcion_moneda_simbolo'	=> $sunatmoneda->simbolo,
				'percepcion_monto'			=> $documento->percepcion_monto,
				'percepcion_monto_base'		=> ($documento->percepcion_montobase + $documento->percepcion_monto)
			);
		}
		
		//validando si tiene cuotas
		$lista_cuotas = array();
		$total_adeudado = 0;
		$fecha_pago_deuda = '';
		if(in_array($id_tipodoc_electronico, array('01', '03', '07', '08'))) {
			$resp_cuotas = $cuentasporcobrar->get_lista_abonos($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat);
			$lista_cuotas = $resp_cuotas['lista_cuotas'];
			$total_adeudado = $resp_cuotas['total_adeudado'];
		} else {
			$total_adeudado = $documento->monto_adeudado;
			$fecha_pago_deuda = !empty($documento->fecha_pagopendiente) ? date("d-m-Y", strtotime($documento->fecha_pagopendiente)) : '';
		}

		//condición de pago o forma de pago
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
		$nombre_condicion_pago = 'Al Contado';
		if($condicion_pago) {
			$nombre_condicion_pago = $condicion_pago->condicionpago;
			if(isset($documento->retencion_aplica) && $documento->retencion_aplica == 'si') {
				if(isset($documento->monto_adeudado) && $documento->monto_adeudado <= 0) {
					$nombre_condicion_pago = 'Al Contado';
				}
			}
		} else {
			if(isset($documento->id_tipo_operacion) && ($documento->id_tipo_operacion == '1001' || $documento->id_tipo_operacion == '1002' || $documento->id_tipo_operacion == '1003' || $documento->id_tipo_operacion == '1004')) {
				if($id_tipodoc_electronico == '01') {
					$pago_parcial = MontoCobrado::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and (estado = 'activo') and tipo_abono = 'pago_parcial'", "bind" => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'numero_comprobante' => $numero_comprobante), "order" => "id_montocobrado ASC"));
					
					$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $pago_parcial->id_condicionpago)));
					if($condicion_pago) {
						$nombre_condicion_pago = $condicion_pago->condicionpago;
					}
				}
			}
		}

		$data_nota_credito = array();
		if($id_tipodoc_electronico == '07') {
			$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $tipo_envio_sunat)));

			$motivo_modificacion = SunatTiponotacredito::findFirst(array("id_tiponotacredito = :id_tiponotacredito:", 'bind' => array('id_tiponotacredito' => $documento->id_cod_tipomotivo_credito)));

			$descripcion_motivo = $motivo_modificacion->id_tiponotacredito.'. '.$motivo_modificacion->descripcion;

			$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));
			
			$texto_doc_modificado = $tipo_doc_modificado->descripcion;

			$documento_relacionado = '<p><span class="font-weight-bold">Documento relacionado:</span> '.$texto_doc_modificado.' '.$doc_modificado->serie_comprobante.' - '.$herramientas->zero_fill($doc_modificado->numero_comprobante, 6).' | '.date("d-m-Y", strtotime($doc_modificado->fecha_comprobante)).'</p>';

			$data_nota_credito = array(
				'descripcion_motivo'					=> $descripcion_motivo,
				'codigo_motivo'							=> $documento->id_cod_tipomotivo_credito,

				'nombre_documento_modificado'			=> $tipo_doc_modificado->descripcion, //FACTURA, BOLETA (nombre del documento modificado)
				'serie_documento_modificado'			=> $doc_modificado->serie_comprobante,
				'correlativo_documento_modificado'		=> $herramientas->zero_fill($doc_modificado->numero_comprobante, 6),
				'fecha_emision_documento_modificado'	=> $doc_modificado->fecha_comprobante
			);
		}

		$data_nota_debito = array();
		if($id_tipodoc_electronico == '08'){
			$doc_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $documento->id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $tipo_envio_sunat)));

			$motivo_modificacion = SunatTiponotadebito::findFirst(array("id_tiponotadebito = :id_tiponotadebito:", 'bind' => array('id_tiponotadebito' => $documento->id_cod_tipomotivo_debito)));

			$descripcion_motivo = $motivo_modificacion->id_tiponotadebito.'. '.$motivo_modificacion->descripcion;

			$tipo_doc_modificado = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica)));

			$data_nota_debito = array(
				'descripcion_motivo'					=> $descripcion_motivo,
				'codigo_motivo'							=> $documento->id_cod_tipomotivo_debito,

				'nombre_documento_modificado'			=> $tipo_doc_modificado->descripcion, //FACTURA, BOLETA (nombre del documento modificado)
				'serie_documento_modificado'			=> $doc_modificado->serie_comprobante,
				'correlativo_documento_modificado'		=> $herramientas->zero_fill($doc_modificado->numero_comprobante, 6),
				'fecha_emision_documento_modificado'	=> $doc_modificado->fecha_comprobante
			);
		}


		$ubigeo_ubicacion_partida = '';
		$ubigeo_ubicacion_llegada = '';

		if(isset($documento->id_ubigeo_partida)) {
			$ubigeo_partida = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_partida)));
			if($ubigeo_partida) {
				$ubigeo_ubicacion_partida = $ubigeo_partida->distrito.', '.$ubigeo_partida->provincia.', '.$ubigeo_partida->departamento;
			}
		}
		
		if(isset($documento->id_ubigeo_destino)) {
			$ubigeo_destino = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $documento->id_ubigeo_destino)));
			if($ubigeo_destino) {
				$ubigeo_ubicacion_llegada = $ubigeo_destino->distrito.', '.$ubigeo_destino->provincia.', '.$ubigeo_destino->departamento;
			}
		}

		$fecha_vto_comprobante = '';
		if(isset($documento->fecha_vto_comprobante) && !empty($documento->fecha_vto_comprobante)) {
			if(date("Y-m-d", strtotime($fecha_documento)) != date("Y-m-d", strtotime($documento->fecha_vto_comprobante))) {
				$fecha_vto_comprobante = date("d-m-Y", strtotime($documento->fecha_vto_comprobante));
			}
		}
		
		//Código QR Yape
		$mostrar_yape = 'no';
		$data_yape = array(
			'mostrar_yape' 	=> $mostrar_yape,
			'img_qr_yape' 	=> '',
			'titular_yape' 	=> '',
			'celular_yape' 	=> ''
		);

		if(!empty($sucursal->img_qr_yape) && !empty($sucursal->titular_yape) && !empty($sucursal->celular_yape)) {
			if($id_tipodoc_electronico == '01') {
				$mostrar_yape = ($sucursal->factura_mostrar_yape == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '03') {
				$mostrar_yape = ($sucursal->boleta_mostrar_yape == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '77') {
				$mostrar_yape = ($sucursal->notaventa_mostrar_yape == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '88') {
				$mostrar_yape = ($sucursal->cotizacion_mostrar_yape == 'si')?'si':'no';
			}

			$data_yape = array(
				'mostrar_yape' 	=> $mostrar_yape,
				'img_qr_yape' 	=> $sucursal->img_qr_yape,
				'titular_yape' 	=> $sucursal->titular_yape,
				'celular_yape' 	=> $sucursal->celular_yape
			);
		}

		//código QR Plin
		$mostrar_plin = 'no';
		$data_plin = array(
			'mostrar_plin' 	=> $mostrar_plin,
			'img_qr_plin' 	=> '',
			'titular_plin' 	=> '',
			'celular_plin' 	=> ''
		);

		if(!empty($sucursal->img_qr_plin) && !empty($sucursal->titular_plin) && !empty($sucursal->celular_plin)) {
			if($id_tipodoc_electronico == '01') {
				$mostrar_plin = ($sucursal->factura_mostrar_plin == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '03') {
				$mostrar_plin = ($sucursal->boleta_mostrar_plin == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '77') {
				$mostrar_plin = ($sucursal->notaventa_mostrar_plin == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '88') {
				$mostrar_plin = ($sucursal->cotizacion_mostrar_plin == 'si')?'si':'no';
			}

			$data_plin = array(
				'mostrar_plin' 	=> $mostrar_plin,
				'img_qr_plin' 	=> $sucursal->img_qr_plin,
				'titular_plin' 	=> $sucursal->titular_plin,
				'celular_plin' 	=> $sucursal->celular_plin
			);
		}
		
		$cabecera = array(
			'estado_documento'	=> $estado_documento, //aprobado - anulado

			'serie'				=> $serie_comprobante,
			'correlativo'		=> $herramientas->zero_fill($numero_comprobante, 6),
			'id_tipo_cpe'		=> $id_tipodoc_electronico,
			'nombre_cpe'		=> $nombre_cpe,
			'fecha_emision'		=> $fecha_documento,
			'fecha_vencimiento'	=> $fecha_vto_comprobante,
			'orden_compra'		=> empty($documento->nro_otr_comprobante)?'':$documento->nro_otr_comprobante,
			'nro_placa'			=> empty($documento->transporte_nro_placa)?'':$documento->transporte_nro_placa,
			'guia_remision'		=> $lista_docs_referencia,
			'documento_nota'	=> $documento->nota,
			'tipo_cambio_sunat'	=> $documento->tipo_cambio_sunat,

			//totales del documento
			'codigomoneda'		=> $documento->id_codigomoneda,
			'simbolo_moneda'	=> $simbolo_moneda,
			'nombre_moneda_doc'	=> $nombre_moneda_doc,
			'total_letras'		=> $documento->total_letras,
			'total_gravadas'	=> $documento->total_gravadas,
			'total_inafecta'	=> $documento->total_inafecta,
			'total_exoneradas'	=> $documento->total_exoneradas,
			'total_gratuitas'	=> $documento->total_gratuitas,
			'total_exportacion' => $documento->total_exportacion,
			'total_icbper'		=> $documento->total_icbper,
			'porcentaje_igv'	=> $documento->porcentaje_igv,
			'total_igv'			=> $documento->total_igv,
			'total_descuento'	=> $documento->total_descuento,
			'total'				=> $documento->total,

			//
			'guia_ruta_qr'		=> isset($documento->ruta_qr)?$documento->ruta_qr:'',
			'ruta_imagen_qr'	=> $ruta_qr,

			//detracción
			'aplica_detraccion'	=> $aplica_detracion,
			'data_detraccion'	=> $data_detraccion,

			//reteción
			'aplica_retencion'	=> $aplica_retencion,
			'data_retencion'	=> $data_retencion,

			//percepcion
			'aplica_percepcion'	=> $aplica_percepcion,
			'data_percepcion'	=> $data_percepcion,

			//cuotas
			'cuotas'			=> $lista_cuotas,
			'total_adeudado'	=> $total_adeudado,
			'fecha_pago_deuda'	=> $fecha_pago_deuda,

			//modalidad de pago
			'forma_de_pago'		=> $nombre_condicion_pago,

			//solo válido cuando se trata de nota de crédito y débito
			'data_nota_credito'	=> $data_nota_credito,
			'data_nota_debito'	=> $data_nota_debito,

			//datos para la guía de remisión
			'id_modalidadtraslado' => !isset($documento->id_modalidadtraslado)?'':$documento->id_modalidadtraslado,
			'fecha_traslado'			=> !isset($documento->fecha_traslado)?'':date("d-m-Y", strtotime($documento->fecha_traslado)),
			'motivo_traslado'			=> !isset($documento->motivo_traslado)?'':$documento->motivo_traslado,
			'modalidad_traslado'		=> !isset($documento->modalidad_traslado)?'':$documento->modalidad_traslado,
			'direccion_partida'			=> !isset($documento->dir_partida)?'':$documento->dir_partida,
			'ubigeo_partida'			=> !isset($ubigeo_ubicacion_partida)?'':$ubigeo_ubicacion_partida,
			'direccion_destino'			=> !isset($documento->dir_destino)?'':$documento->dir_destino,
			'ubigeo_destino'			=> !isset($ubigeo_ubicacion_llegada)?'':$ubigeo_ubicacion_llegada,
			'transporte_nro_placa'		=> !isset($documento->transporte_nro_placa)?'':$documento->transporte_nro_placa,
			'nro_documento_transporte'	=> !isset($documento->nro_documento_transporte)?'':$documento->nro_documento_transporte,
			'razon_social_transporte'	=> !isset($documento->razon_social_transporte)?'':$documento->razon_social_transporte,
			'peso_total'				=> !isset($documento->peso)?'':$documento->peso,
			'peso_total_tne'			=> !isset($documento->peso)?'':round($documento->peso/1000, 2),
			'numero_paquetes'			=> !isset($documento->numero_paquetes)?'':$documento->numero_paquetes,
			'nro_licencia_conducir'		=> !isset($documento->licencia_conductor)?'':$documento->licencia_conductor,

			'id_codigopuerto'			=> !isset($documento->id_codigopuerto)?'':$documento->id_codigopuerto,
			'numero_contenedor'			=> !isset($documento->numero_contenedor)?'':$documento->numero_contenedor,

			'hash'						=> !isset($documento->hash_cpe)?'':$documento->hash_cpe,

			'data_yape'					=> $data_yape,
			'data_plin'					=> $data_plin
		);
 
		$resp['respuesta'] = 'ok';
		$resp['cabecera'] = $cabecera;
		return $resp;
	}

	public function get_data_sucursal($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$id_sucursal = 0;
		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '31') {
			$documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		} else if($id_tipodoc_electronico == '99') {
			$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
			$id_sucursal = $documento->idsucursal;
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
			$id_sucursal = $documento->id_sucursal;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $documento->id_contribuyente)));
		
		$data_sucursal = array(
			'telefono'		=> $sucursal->telefono,
			'email'			=> $sucursal->email,
			'sitio_web' 	=> $sucursal->sitio_web,
			'direccion' 	=> $sucursal->direccion,
			'txt_pdf_a4_1' 	=> $sucursal->txt_pdf_a4_1,
			'txt_pdf_a4_2' 	=> $sucursal->txt_pdf_a4_2,
			'txt_pdf_a4_3' 	=> $sucursal->txt_pdf_a4_3
		);

		$resp['respuesta'] = 'ok';
		$resp['sucursal'] = $data_sucursal;
		return $resp;
	}

	//valido únicamente para facturas, boletas, notas de crédito, notas de débito, notas de venta y cotizaciones
	public function get_json_cpe($data) {
		if($data['tipo_comprobante'] == '31') {
			$resp = $this->get_json_guia_transporte($data);

			if($resp['respuesta'] == 'error') {
				echo json_encode($resp);
				exit();
			}

			return $resp;
		}

		if($data['tipo_comprobante'] == '99') {
			$resp = $this->get_json_orden_compra($data);
			return $resp;
		}

		$detalle = $this->get_detalle_cpe($data)['detalle'];
		$anticipos = $this->get_anticipos($data, count($detalle));
		if(count($anticipos['detalle_anticipos']) > 0) {
			$detalle = array_merge($detalle, $anticipos['detalle_anticipos']);
		}

		$cabecera = $this->get_cabecera_cpe($data)['cabecera'];
		if(count($anticipos['lista_anticipos']) > 0) {
			$cabecera['total_letras'] 		= $cabecera['total_letras'];
			$cabecera['total_gravadas'] 	= $cabecera['total_gravadas'] - $anticipos['totales']['total_gravadas'];
			$cabecera['total_inafecta'] 	= $cabecera['total_inafecta'] - $anticipos['totales']['total_inafecta'];
			$cabecera['total_exoneradas'] 	= $cabecera['total_exoneradas'] - $anticipos['totales']['total_exoneradas'];
			$cabecera['total_gratuitas'] 	= $cabecera['total_gratuitas'] - $anticipos['totales']['total_gratuitas'];
			$cabecera['total_exportacion'] 	= $cabecera['total_exportacion'] - $anticipos['totales']['total_exportacion'];
			$cabecera['total_icbper'] 		= $cabecera['total_icbper'] - $anticipos['totales']['total_icbper'];
			$cabecera['porcentaje_igv'] 	= $cabecera['porcentaje_igv'];
			$cabecera['total_igv'] 			= $cabecera['total_igv'] - $anticipos['totales']['total_igv'];
			//$cabecera['total_descuento'] 	= $cabecera['total_descuento'] - $anticipos['totales']['total_descuento'];
			$cabecera['total'] 				= $cabecera['total'] - $anticipos['totales']['total'];
		}

		$cpe = array(
			'emisor'			=> $this->get_data_emisor($data)['emisor'],
			'cliente'			=> $this->get_data_cliente($data)['cliente'],
			'cabecera'			=> $cabecera,
			'detalle'			=> $detalle,
			'cuentas_banco'		=> $this->get_cuentas_banco($data)['cuentas_banco'],
			'vendedor'			=> $this->get_data_vendedor($data)['vendedor'],
			'patrocinador'		=> $this->get_data_patrocinador($data)['patrocinador'],
			'sucursal'			=> $this->get_data_sucursal($data)['sucursal'],
			'anticipos'			=> $anticipos['lista_anticipos'],
			'sum_anticipos'		=> $anticipos['sum_anticipos'],
			'totales_anticipos' => $anticipos['totales'],
			'splitpayments'		=> $this->get_splitpayments($data)['splitpayments']
		);

		$resp['respuesta'] = 'ok';
		$resp['cpe'] = $cpe;
		return $resp;
	}

	public function get_splitpayments($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$documentoelectronico = new DocumentoelectronicoController;
		$herramientas = new HerramientasController;

		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
		} else {
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
		}

		$splitpayments = array();
		if(!$documento) {
			$resp['respuesta'] = 'ok';
			$resp['splitpayments'] = $splitpayments;
			return $resp;
		}
		
		$detalle_pagos = DocDetallepago::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
		$splitpayments = array();
		foreach($detalle_pagos as $pago) {
			$banco = Cuentabanco::findFirst(array("id_contribuyente = :id_contribuyente: and id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_cuentabanco' => $pago->idbanco)));
			if(!$banco) {
				$nombre_banco = '';
			} else {
				$nombre_banco = $banco->nombre_banco;
			}

			$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $pago->id_condicionpago)));
			
			if($condicion_pago) {
				$splitpayment = array(
					'fecha_registro'	=> date("d-m-Y", strtotime($pago->fecha_registro)),
					'fecha_deposito'	=> (!empty($pago->fechadeposito)) ? date("d-m-Y", strtotime($pago->fechadeposito)) : '',
					'banco'				=> $nombre_banco,
					'condicion_pago'	=> $condicion_pago->condicionpago,
					'total'				=> $pago->total,
					'nro_operacion'		=> $pago->cpago_nrooperacion, //solo mostrar si es diferente a vacío
					'detalle'			=> $pago->detalle //solo mostrar si es diferente a vacío
				);
	
				$splitpayments[] = $splitpayment;
			}
		}
		
		$resp['respuesta'] = 'ok';
		$resp['splitpayments'] = $splitpayments;
		return $resp;
	}

	public function get_data_cabecera_orden_compra($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$herramientas = new HerramientasController;

		$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

		$estado_documento = ($documento->estado == 'activo')?'aprobado':'anulado';

		$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));
		$simbolo_moneda = '';
		$nombre_moneda_doc = '';
		if($sunatmoneda) {
			$simbolo_moneda = $sunatmoneda->simbolo;
			$nombre_moneda_doc = $sunatmoneda->nombre;
		}

		$convertir_numero_letras = new NumeroALetras();
		$total_a_pagar_letras = ($documento->id_codigomoneda == 'USD')?$convertir_numero_letras->convert(floatval($documento->total) , 'dólares'):$convertir_numero_letras->convert(floatval($documento->total) , 'soles');

		if($documento->tipo_compra == 'credito') {
			$cuota = array(
				'num_cuota' 		=> 1,
				'fecha_vencimiento' => date("d-m-Y", strtotime($documento->fecha_pagopendiente)),
				'tipo_moneda'   	=> $nombre_moneda_doc,
				'monto_cuota' 		=> $documento->monto_adeudado,
				'estado_cuota' 		=> 'pendiente'
			);

			$lista_cuotas[] = $cuota;
		} else {
			$lista_cuotas = array();
		}

		//condición de pago o forma de pago
		$condicion_pago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $documento->id_condicionpago)));
		$nombre_condicion_pago = 'Al Contado';
		$nombre_condicion_pago = $condicion_pago->condicionpago;
		

		$cabecera = array(
			'estado_documento'	=> $estado_documento, //aprobado - anulado

			'serie'				=> $serie_comprobante,
			'correlativo'		=> $herramientas->zero_fill($numero_comprobante, 6),
			'id_tipo_cpe'		=> '99',
			'nombre_cpe'		=> 'Orden de Compra',
			'fecha_emision'		=> date("d-m-Y", strtotime($documento->fecha_comprobante)),
			'fecha_vencimiento'	=> !empty($documento->fecha_vto_comprobante) ? date("d-m-Y", strtotime($documento->fecha_vto_comprobante)) : '',
			'orden_compra'		=> empty($documento->nro_otr_comprobante)?'':$documento->nro_otr_comprobante,
			'nro_placa'			=> empty($documento->transporte_nro_placa)?'':$documento->transporte_nro_placa,
			'guia_remision'		=> array(),
			'documento_nota'	=> $documento->nota,
			'tipo_cambio_sunat'	=> $documento->tipo_cambio_sunat,

			//totales del documento
			'codigomoneda'		=> $documento->id_codigomoneda,
			'simbolo_moneda'	=> $simbolo_moneda,
			'nombre_moneda_doc'	=> $nombre_moneda_doc,

			'total_letras'		=> $total_a_pagar_letras,
			'total_gravadas'	=> $documento->total_gravadas,
			'total_inafecta'	=> $documento->total_inafecta,
			'total_exoneradas'	=> $documento->total_exoneradas,
			'total_gratuitas'	=> $documento->total_gratuitas,
			'total_exportacion' => $documento->total_exportacion,
			'total_icbper'		=> $documento->total_icbper,
			'porcentaje_igv'	=> $documento->porcentaje_igv,
			'total_igv'			=> $documento->total_igv,
			'total_descuento'	=> $documento->total_descuento,
			'total'				=> $documento->total,

			//cuotas
			'cuotas'			=> $lista_cuotas,
			'total_adeudado'	=> $documento->monto_adeudado,
			'fecha_pago_deuda'	=> !empty($documento->fecha_pagopendiente)?date("d-m-Y", strtotime($documento->fecha_pagopendiente)):'',

			//modalidad de pago
			'forma_de_pago'		=> $nombre_condicion_pago
		);

		$resp['respuesta'] = 'ok';
		$resp['cabecera'] = $cabecera;
		return $resp;
	}

	public function get_data_detalle_orden_compra($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];

		$herramientas = new HerramientasController;

		$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

		$detalle_orden_compra = OrdenCompraDetalle::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $documento->idsucursal, 'id_contribuyente' => $documento->id_contribuyente)));

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$num_decimales = 2;
		if(!empty($contribuyente->num_decimales)) {
			$num_decimales = $contribuyente->num_decimales;
		}

		$lista_item = array();
		$n = 0;
		foreach($detalle_orden_compra as $item) {
			$n++;

			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));

			$text_afectacion = '';
			if (strpos($tipoafectacionigv->descripcion, '[') !== false) {
				$text_afectacion = $herramientas->get_string_between($tipoafectacionigv->descripcion, '[', ']');
			} else {
				if (strpos($tipoafectacionigv->descripcion, '-') !== false) {
					$text_afectacion = explode(' - ', $tipoafectacionigv->descripcion)[0];
				} else {
					$text_afectacion = $tipoafectacionigv->descripcion;
				}
			}

			$factor_igv_sunat = floatval($documento->porcentaje_igv) + 0;
			$factor_igv_sunat = round($factor_igv_sunat/100, 3);
			$this->factor_igv_sunat = $factor_igv_sunat;

			$opcion_mostrar_items_igv = ($sucursal->ordencompra_mostrar_items_igv == 'si')?'si':'no';

			$sub_total_con_igv = floatval($item->igv) + floatval($item->sub_total);
			if(intval($item->id_tipoafectacionigv) == 10) {
				if($opcion_mostrar_items_igv == 'si') {
					$precio_unitario_item = $item->precio;
					$subtotal_item =  floatval($item->igv) + floatval($item->sub_total);
				} else {
					$precio_unitario_item = round($item->precio/(1 + $factor_igv_sunat), $num_decimales);
					$subtotal_item = floatval($item->sub_total);
				}
			} else {
				$precio_unitario_item = $item->precio;
				$subtotal_item =  floatval($item->igv) + floatval($item->sub_total);
			}

			$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));

			$sunatmoneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $documento->id_codigomoneda)));

			$simbolo_moneda = '';
			if($sunatmoneda) {
				$simbolo_moneda = $sunatmoneda->simbolo;
			}
			
			$lista_item[] = array(
				'nro_item'				=> $n,
				'codigo' 				=> $item->codigo_producto,
				'descripcion'			=> $item->descripcion,
				'cantidad'				=> $item->cantidad + 0,
				'texto_tipo_operacion'	=> $text_afectacion,
				'precio_unitario'		=> $precio_unitario_item + 0,
				'subtotal_item'			=> $subtotal_item + 0,
				'id_tipoafectacionigv' 	=> $item->id_tipoafectacionigv,
				'unidad_medida' 		=> $unidad_medida_sunat->nombre,
				'simbolo_moneda'		=> $simbolo_moneda
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista_item;
		return $resp;
	}

	public function get_data_proveedor($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['tipo_comprobante'];
		$serie_comprobante = $data['serie_doc'];
		$numero_comprobante = $data['numero_doc'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];
		
		$documento = OrdenCompra::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));

		$proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor:", 'bind' => array('id_proveedor' => $documento->id_proveedor)));

		$resp['respuesta'] = 'ok';
		$resp['proveedor'] = (array)$proveedor;
		return $resp;
	}

	public function get_json_orden_compra($data) {
		$cpe = array(
			'emisor'			=> $this->get_data_emisor($data)['emisor'],
			'cabecera'			=> $this->get_data_cabecera_orden_compra($data)['cabecera'],
			'detalle'			=> $this->get_data_detalle_orden_compra($data)['detalle'],
			'vendedor'			=> $this->get_data_vendedor($data)['vendedor'],
			'patrocinador'		=> $this->get_data_patrocinador($data)['patrocinador'],
			'sucursal'			=> $this->get_data_sucursal($data)['sucursal'],
			'proveedor'			=> $this->get_data_proveedor($data)['proveedor']
		);

		$resp['respuesta'] = 'ok';
		$resp['cpe'] = $cpe;
		return $resp;
	}

	public function get_json_guia_transporte($data) {

		$guia_transportista = new GuiatransportistaController;

		$data_guia['id_contribuyente'] 			= $data['id_contribuyente'];
        $data_guia['id_tipodoc_electronico'] 	= $data['tipo_comprobante'];
        $data_guia['serie_comprobante'] 		= $data['serie_doc'];
        $data_guia['numero_comprobante'] 		= $data['numero_doc'];
        $data_guia['tipo_envio_sunat'] 			= $data['tipo_envio_sunat'];

		$resp_json_guia = $guia_transportista->get_data_json($data_guia);
		if($resp_json_guia['respuesta'] == 'error') {
			return $resp_json_guia;
		}

		if(!empty($resp_json_guia['data_json']['cabecera']['guia_ruta_qr'])) {
			$enlace_sunat_cpe = $resp_json_guia['data_json']['cabecera']['guia_ruta_qr'];

			if (!file_exists($this->ruta_base_files.'/qr_images/'.$data['id_contribuyente'].'/')) {
				mkdir($this->ruta_base_files.'/qr_images/'.$data['id_contribuyente'].'/', 0777, true);
			}
			
			$ruta_qr = $this->ruta_base_files.'/qr_images/'.$data['id_contribuyente'].'/'.uniqid().'_'.$data_guia['id_tipodoc_electronico'].'_'.$data_guia['serie_comprobante'].'_'.$data_guia['numero_comprobante'].'.png';

			$result = Builder::create()
				->writer(new PngWriter())   // Definir el formato del código QR (PNG)
				->data($enlace_sunat_cpe) // Contenido del QR
				->size(300)                 // Tamaño del QR
				->margin(5)                // Margen alrededor del QR
				->build();

			// Guardar en un archivo
			$result->saveToFile($ruta_qr);
	
			$cabecera = (array)$resp_json_guia['data_json']['cabecera'];
			$cabecera['ruta_imagen_qr'] = $ruta_qr;
			$resp_json_guia['data_json']['cabecera'] = $cabecera;
		}
		
		$cpe = array(
			'emisor'			=> $this->get_data_emisor($data)['emisor'],
			'cabecera'			=> $resp_json_guia['data_json']['cabecera'],
			'detalle'			=> $resp_json_guia['data_json']['detalle'],
			'conductor'			=> $resp_json_guia['data_json']['conductor'],
			'destinatario'		=> $resp_json_guia['data_json']['destinatario'],
			'remitente'			=> $resp_json_guia['data_json']['remitente'],
			'vendedor'			=> $this->get_data_vendedor($data)['vendedor'],
			'patrocinador'		=> $this->get_data_patrocinador($data)['patrocinador'],
			'sucursal'			=> $this->get_data_sucursal($data)['sucursal']
		);

		$resp['respuesta'] = 'ok';
		$resp['cpe'] = $cpe;
		return $resp;
	}

	public function get_anticipos($data, $num_items) {
		$lista_anticipos = DocElectronicoAnticipo::find(array("
			cpe_id_contribuyente 		= :id_contribuyente: and 
			cpe_id_tipodoc_electronico 	= :id_tipodoc_electronico: and 
			cpe_serie_comprobante 		= :serie_comprobante: and 
			cpe_numero_comprobante 		= :numero_comprobante: and 
			cpe_tipo_envio_sunat 		= :tipo_envio_sunat:
		", 'bind' => array(
			'id_contribuyente' 			=> $data['id_contribuyente'], 
			'id_tipodoc_electronico' 	=> $data['tipo_comprobante'], 
			'serie_comprobante' 		=> $data['serie_doc'], 
			'numero_comprobante' 		=> $data['numero_doc'], 
			'tipo_envio_sunat' 			=> $data['tipo_envio_sunat']
		)));
		
		$doc_electronico = DocElectronico::findFirst(array("
			id_contribuyente 		= :id_contribuyente: and 
			id_tipodoc_electronico 	= :id_tipodoc_electronico: and 
			serie_comprobante 		= :serie_comprobante: and 
			numero_comprobante 		= :numero_comprobante: and 
			tipo_envio_sunat 		= :tipo_envio_sunat:
		", 'bind' => array(
			'id_contribuyente' 			=> $data['id_contribuyente'], 
			'id_tipodoc_electronico' 	=> $data['tipo_comprobante'], 
			'serie_comprobante' 		=> $data['serie_doc'], 
			'numero_comprobante' 		=> $data['numero_doc'], 
			'tipo_envio_sunat' 			=> $data['tipo_envio_sunat']
		)));

		$anticipos = array();
		$num_anticipo = 0;
		$sum_anticipos = 0;

		$total_gravadas = 0;
		$total_inafecta = 0;
		$total_exoneradas = 0;
		$total_gratuitas = 0;
		$total_exportacion = 0;
		$total_icbper = 0;
		$total_descuento = 0;
		$porcentaje_descuento_total = 0;
		$sub_total = 0;
		$porcentaje_igv = 0;
		$impuesto_icbper = 0;
		$total_igv = 0;
		$total_isc = 0;
		$total_otr_imp = 0;
		$total = 0;

		$detalle_anticipos = array();
		$nro_item = $num_items;

		if($doc_electronico) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $doc_electronico->id_sucursal, 'id_contribuyente' => $doc_electronico->id_contribuyente)));

			$id_tipodoc_electronico = $data['tipo_comprobante'];
			$opcion_mostrar_items_igv = 'si';
			if($id_tipodoc_electronico == '01') {
				$opcion_mostrar_items_igv = ($sucursal->factura_mostrar_items_igv == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '03') {
				$opcion_mostrar_items_igv = ($sucursal->boleta_mostrar_items_igv == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '07') {
				$opcion_mostrar_items_igv = ($sucursal->notacredito_mostrar_items_igv == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '08') {
				$opcion_mostrar_items_igv = ($sucursal->notadebito_mostrar_items_igv == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '77') {
				$opcion_mostrar_items_igv = ($sucursal->notaventa_mostrar_items_igv == 'si')?'si':'no';
			} else if($id_tipodoc_electronico == '88') {
				$opcion_mostrar_items_igv = ($sucursal->cotizacion_mostrar_items_igv == 'si')?'si':'no';
			}
			
			foreach($lista_anticipos as $item) {
				$num_anticipo++;
				$nro_item++;

				$anticipos[] = array(
					'num_anticipo' => $num_anticipo,
					'id_tipodoc_electronico' => $item->anticipo_id_tipodoc_electronico,
					'serie_comprobante' => $item->anticipo_serie_comprobante,
					'numero_comprobante' => $item->anticipo_numero_comprobante,
					'monto_anticipo' => $item->monto_anticipo
				);

				$SunatTipodocelectronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $item->anticipo_id_tipodoc_electronico)));
				$nombre_cpe = $SunatTipodocelectronico->descripcion;

				$doc_anticipo = DocElectronico::findFirst(array("
					id_contribuyente 		= :id_contribuyente: and 
					id_tipodoc_electronico 	= :id_tipodoc_electronico: and 
					serie_comprobante 		= :serie_comprobante: and 
					numero_comprobante 		= :numero_comprobante: and 
					tipo_envio_sunat 		= :tipo_envio_sunat:
				", 'bind' => array(
					'id_contribuyente' 			=> $data['id_contribuyente'], 
					'id_tipodoc_electronico' 	=> $item->anticipo_id_tipodoc_electronico, 
					'serie_comprobante' 		=> $item->anticipo_serie_comprobante, 
					'numero_comprobante' 		=> $item->anticipo_numero_comprobante, 
					'tipo_envio_sunat' 			=> $data['tipo_envio_sunat']
				)));

				$simbolo_moneda = ($doc_anticipo->id_codigomoneda == 'PEN')?'S/.':'$';
				
				if($doc_anticipo->total != $item->monto_anticipo) {
					$total_gravadas = $total_gravadas + round($item->monto_anticipo/1.18, 2);
					$total_inafecta = $total_inafecta + 0;
					$total_exoneradas = $total_exoneradas + 0;
					$total_gratuitas = $total_gratuitas + 0;
					$total_exportacion = $total_exportacion + 0;
					$total_icbper = $total_icbper + 0;
					$total_descuento = $total_descuento + 0;
					$porcentaje_descuento_total = $porcentaje_descuento_total + 0;
					$subototal_item = round($item->monto_anticipo/1.18, 2);
					$sub_total = $sub_total + $subototal_item;
					$porcentaje_igv = $porcentaje_igv + 0;
					$impuesto_icbper = $impuesto_icbper + 0;
					$total_igv_item = round($item->monto_anticipo - $subototal_item, 2);
					$total_igv = $total_igv + floatval($total_igv_item);
					$total_isc = $total_isc + 0;
					$total_otr_imp = $total_otr_imp + 0;
					$total = $total + floatval($item->monto_anticipo);
		
					$sum_anticipos = $sum_anticipos + floatval($item->monto_anticipo);

					if($opcion_mostrar_items_igv == 'si') {
						$precio_unitario_item = $item->monto_anticipo;
						$subtotal_item = $item->monto_anticipo;
					} else {
						$precio_unitario_item = round($item->monto_anticipo/(1 + $this->factor_igv_sunat), 2);
						$subtotal_item = $precio_unitario_item;
					}
				} else {
					$total_gravadas = $total_gravadas + floatval($doc_anticipo->total_gravadas);
					$total_inafecta = $total_inafecta + floatval($doc_anticipo->total_inafecta);
					$total_exoneradas = $total_exoneradas + floatval($doc_anticipo->total_exoneradas);
					$total_gratuitas = $total_gratuitas + floatval($doc_anticipo->total_gratuitas);
					$total_exportacion = $total_exportacion + floatval($doc_anticipo->total_exportacion);
					$total_icbper = $total_icbper + floatval($doc_anticipo->total_icbper);
					$total_descuento = $total_descuento + floatval($doc_anticipo->total_descuento);
					$porcentaje_descuento_total = $porcentaje_descuento_total + floatval($doc_anticipo->porcentaje_descuento_total);
					$sub_total = $sub_total + floatval($doc_anticipo->sub_total);
					$porcentaje_igv = $porcentaje_igv + floatval($doc_anticipo->porcentaje_igv);
					$impuesto_icbper = $impuesto_icbper + floatval($doc_anticipo->impuesto_icbper);
					$total_igv = $total_igv + floatval($doc_anticipo->total_igv);
					$total_isc = $total_isc + floatval($doc_anticipo->total_isc);
					$total_otr_imp = $total_otr_imp + floatval($doc_anticipo->total_otr_imp);
					$total = $total + floatval($doc_anticipo->total);
		
					$sum_anticipos = $sum_anticipos + floatval($item->monto_anticipo);
		
					if($opcion_mostrar_items_igv == 'si') {
						$precio_unitario_item = $doc_anticipo->total;
						$subtotal_item = $doc_anticipo->total;
					} else {
						$precio_unitario_item = round($doc_anticipo->total/(1 + $this->factor_igv_sunat), 2);
						$subtotal_item = $precio_unitario_item;
					}
				}

				$detalle_anticipos[] = array(
					'nro_item'				=> $nro_item,
					'codigo' 				=> 'ANTICIPO',
					'descripcion'			=> "ANTICIPO: $nombre_cpe ".$item->anticipo_serie_comprobante.'-'.$item->anticipo_numero_comprobante,
					'cantidad'				=> 1,
					'texto_tipo_operacion'	=> '',
					'precio_unitario'		=> (-1)*$subtotal_item,
					'subtotal_item'			=> (-1)*$subtotal_item,
					'id_tipoafectacionigv' 	=> '',
					'unidad_medida' 		=> '-',
					'simbolo_moneda'		=> $simbolo_moneda,
					'peso_det' 				=> '',
				);
			}
		}
		
		$resp['lista_anticipos'] = $anticipos;
		$resp['sum_anticipos'] = $sum_anticipos;
		$resp['totales']['total_gravadas'] = $total_gravadas;
		$resp['totales']['total_inafecta'] = $total_inafecta;
		$resp['totales']['total_exoneradas'] = $total_exoneradas;
		$resp['totales']['total_gratuitas'] = $total_gratuitas;
		$resp['totales']['total_exportacion'] = $total_exportacion;
		$resp['totales']['total_icbper'] = $total_icbper;
		$resp['totales']['total_descuento'] = $total_descuento;
		$resp['totales']['porcentaje_descuento_total'] = $porcentaje_descuento_total;
		$resp['totales']['sub_total'] = $sub_total;
		$resp['totales']['porcentaje_igv'] = $porcentaje_igv;
		$resp['totales']['impuesto_icbper'] = $impuesto_icbper;
		$resp['totales']['total_igv'] = $total_igv;
		$resp['totales']['total_isc'] = $total_isc;
		$resp['totales']['total_otr_imp'] = $total_otr_imp;
		$resp['totales']['total'] = $total;
		$resp['detalle_anticipos'] = $detalle_anticipos;
		return $resp;
	}
}
?>