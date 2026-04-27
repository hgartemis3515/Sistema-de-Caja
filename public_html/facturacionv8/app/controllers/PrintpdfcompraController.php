<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/qrlib/vendor/autoload.php";
use Knp\Snappy\Pdf;
class PrintpdfcompraController extends ControllerBase
{
    public function indexAction(){
		$file = $_GET['file'];
		$this->print_pdf($file);
		exit();
	}
	
	public function print_pdf($cadena_cifrada, $html = false) {
		//$id_contribuyente||$id_compra||$tipo_envio_sunat||$id_tipodoc_electronico||a4
		//$id_contribuyente||$id_compra||$tipo_envio_sunat||$id_tipodoc_electronico||ticket
		
		$herramientas = new HerramientasController;
		$cadena_decifrada = $herramientas->desencriptar($cadena_cifrada);
		
		$array_data = explode('||', $cadena_decifrada);
		$id_contribuyente = !isset($array_data[0])?'':$array_data[0];
		$id_compra = !isset($array_data[1])?'':$array_data[1];
		$tipo_envio_sunat = !isset($array_data[2])?'':$array_data[2];
		$id_tipodoc_electronico = !isset($array_data[3])?'':$array_data[3];
		$tipo_pdf = !isset($array_data[4])?'':$array_data[4];
		
		if($id_tipodoc_electronico != '00') {
		    return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}
		
		//1670||77|| ||1385||produccion||ticket
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}

		$resp_emisor = $this->get_data_emisor($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra);
		if($resp_emisor['respuesta'] == 'error') {
		    echo json_encode($resp_emisor);
		    exit();
		}
		
		$resp_cabecera = $this->get_cabecera_compra($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra);
		if($resp_cabecera['respuesta'] == 'error') {
		    echo json_encode($resp_cabecera);
		    exit();
		}
		
		$resp_detalle = $this->get_detalle_compra($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra);
		if($resp_detalle['respuesta'] == 'error') {
		    echo json_encode($resp_detalle);
		    exit();
		}
		
		$resp_cliente = $this->get_cliente_compra($id_contribuyente);
		if($resp_cliente['respuesta'] == 'error') {
		    echo json_encode($resp_cliente);
		    exit();
		}
		
		$resp_proveedor = $this->get_proveedor($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra);
		if($resp_proveedor['respuesta'] == 'error') {
		    echo json_encode($resp_proveedor);
		    exit();
		}
		
		$resp_usuario = $this->get_usuario_vendedor($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra);
		if($resp_usuario['respuesta'] == 'error') {
		    echo json_encode($resp_usuario);
		    exit();
		}
		
		
		$data = array(
			'emisor'	=> $resp_emisor['emisor'],
		    'cabecera'  => $resp_cabecera['cabecera'],
		    'detalle'   => $resp_detalle['detalle'],
		    'cliente'   => $resp_cliente['cliente'],
		    'proveedor' => $resp_proveedor['proveedor'],
		    'vendedor'  => $resp_usuario['vendedor']
		);

		if($id_tipodoc_electronico == '00') {
		    $templatecompra = new TemplatecomprasController;
			if($tipo_pdf == 'ticket') {
				$resp_html = $templatecompra->get_html_otro_doc_ticket_1($data);
			} else {
				$resp_html = $templatecompra->get_html_otro_doc_a4_1($data);
			}
		}
		
		if($html) {
			echo $resp_html['html'];
			exit();
		}
		
		$nombre_archivo = $id_tipodoc_electronico.'-'.$id_compra.'.pdf';
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
		$snappy->setOption('enable-local-file-access', true);
		if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$nombre_archivo.'"');
		
		
		if($tipo_pdf == 'ticket') {
			$snappy->setOption('margin-left', '1mm');
			$snappy->setOption('margin-right', '1mm');
			$snappy->setOption('margin-top', '2mm');
			$snappy->setOption('margin-bottom', '5mm');
			$num_items = 4;
			
			$factor_multiplicacion = 12;
			if($id_tipodoc_electronico == '09') {
				$factor_multiplicacion = 25;
			}
			
			$alto_total = 250 + $num_items*$factor_multiplicacion;
			
			echo $snappy->getOutputFromHtml($resp_html['html'], array('page-height' =>  $alto_total,'page-width' => 78));
			//echo $snappy->getOutputFromHtml($resp_html['html'], array('page-height' =>  140,'page-width' => 78, 'header-html' => $resp_html['html_header'], 'footer-html' => $resp_html['html_footer']));
		} else {
			//echo $snappy->getOutputFromHtml($resp_html['html'], array('header-html' => $resp_html['html_header'], 'footer-html' => $resp_html['html_footer']));
			$snappy->setOption('margin-left', '9mm');
			$snappy->setOption('margin-right', '9mm');
			$snappy->setOption('margin-top', '8mm');
			$snappy->setOption('margin-bottom', '8mm');
			
			echo $snappy->getOutputFromHtml($resp_html['html']);
		}
		
		exit();
	}

	public function get_data_emisor($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra) {

		$ruta_base = $this->ruta_base_public_html;
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		$img_logo_cuadrado = empty($contribuyente->img_logo)?'':str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		$img_logo_rectangular = empty($contribuyente->logo_350)?'':str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);

		$img_logo_cuadrado = empty($img_logo_cuadrado)?'':$ruta_base.$img_logo_cuadrado;
		$img_logo_rectangular = empty($img_logo_rectangular)?'':$ruta_base.$img_logo_rectangular;
		
		$documento = Compra::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and id_compra = :id_compra: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'id_compra' => $id_compra, 'tipo_envio_sunat' => $tipo_envio_sunat))); 
		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Comprobante no existe';
			return $resp;
		}
		$id_sucursal = $documento->idsucursal;

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
	
	public function get_cabecera_compra($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra) {
	    
	    $compra = Compra::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and id_compra = :id_compra: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'id_compra' => $id_compra, 'tipo_envio_sunat' => $tipo_envio_sunat))); 
		if(!$compra) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Comprobante no existe';
			return $resp;
		}
	    
        $cabecera = array(
            'nombre_cpe'                    => 'Otro Doc.',
            'serie'                         => $compra->serie_comprobante,
            'correlativo'                   => $compra->numero_comprobante,
	        'fecha_registro'                => $compra->fecha_registro,
            'fecha_emision'             => $compra->fecha_comprobante,
            'total_gravadas'                => $compra->total_gravadas,
            'total_inafecta'                => $compra->total_inafecta,
            'total_exoneradas'              => $compra->total_exoneradas,
            'total_gratuitas'               => $compra->total_gratuitas,
            'total_exportacion'             => $compra->total_exportacion,
            'total_descuento'               => $compra->total_descuento,
            'porcentaje_descuento_total'    => $compra->porcentaje_descuento_total,
            'sub_total'                     => $compra->sub_total,
            'porcentaje_igv'                => $compra->porcentaje_igv,
            'total_igv'                     => $compra->total_igv,
            'total_icbper'                  => $compra->total_icbper,
            'total_isc'                     => $compra->total_isc,
            'total_otr_imp'                 => $compra->total_otr_imp,
            'total'                         => $compra->total,
            'nro_guia_remision'             => $compra->nro_guia_remision,
            'cod_guia_remision'             => $compra->cod_guia_remision,
            'nro_otr_comprobante'           => $compra->nro_otr_comprobante,
            'id_codigomoneda'               => $compra->id_codigomoneda,
            'tipo_compra'                   => $compra->tipo_compra,
            'moneda'                        => ($compra->id_codigomoneda == 'PEN')?'SOLES':'DÓLARES',
            'simbolo_moneda'                => ($compra->id_codigomoneda == 'PEN')?'S/.':'USD',
            'nota'                          => $compra->nota,
            'monto_adeudado'                => $compra->monto_adeudado,
            'monto_adeudado_inicial'        => $compra->monto_adeudado_inicial,
            'fecha_pagopendiente'           => $compra->fecha_pagopendiente,
            'cpago_nrooperacion'            => $compra->cpago_nrooperacion,
            'cpago_fechadeposito'           => $compra->cpago_fechadeposito,
            'cpago_idbanco'                 => $compra->cpago_idbanco
        );
        
        $resp['respuesta'] = 'ok';
        $resp['cabecera'] = $cabecera;
        return $resp;
	}
	
	public function get_detalle_compra($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra) {
	    $compra = Compra::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and id_compra = :id_compra: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'id_compra' => $id_compra, 'tipo_envio_sunat' => $tipo_envio_sunat))); 
		if(!$compra) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Comprobante no existe';
			return $resp;
		}
		
	    $detalle_compra = DetalleCompra::find(array("id_compra = :id_compra:", 'bind' => array('id_compra' => $id_compra)));
	    $lista = array();
	    
	    $n = 0;
	    foreach($detalle_compra as $item) {
	        $n++;
	        $producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->id_producto)));
	        $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));
	        
	        $lista[] = array(
	            'nro_item' => $n,
	            'cantidad' => $item->cantidad,
	            'precio_sin_igv'    => $item->precio_sin_igv,
	            'precio'            => $item->precio,
	            'sub_total'         => round($item->precio_sin_igv*$item->cantidad, 2),
	            'total'             => round($item->precio*$item->cantidad, 2),
	            'descripcion'       => $item->descripcion,
	            'unidad_medida'     => $unidad_medida->nombre,
	            'codigo'            => $producto->codigo,
	            'icbper'            => $item->icbper,
	            'id_tipoafectacionigv' => $item->id_tipoafectacionigv,
	            'moneda'                        => ($compra->id_codigomoneda == 'PEN')?'SOLES':'DÓLARES',
                'simbolo_moneda'                => ($compra->id_codigomoneda == 'PEN')?'S/.':'USD',
	        );
	    }
	    
	    $resp['respuesta'] = 'ok';
	    $resp['detalle'] = $lista;
	    return $resp;
	}
	
	public function get_cliente_compra($id_contribuyente) {
	    //el cliente en este caso es el contribuyente
	    $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
	    $ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $contribuyente->codigo_ubigeo)));
	    
	    $cliente = array(
            "ruc"						=> $contribuyente->ruc,
			"tipo_doc" 					=> "6",
			"siglas_doc"                => 'R.U.C.',
			"email"						=> $contribuyente->email,
			"nom_comercial" 			=> $contribuyente->nombre_comercial,
			"razon_social" 				=> $contribuyente->razon_social,
			"codigo_ubigeo" 			=> $ubigeo->codigo_ubigeo,
			"direccion"					=> $contribuyente->direccion_fiscal,
			"modalidad_envio_sunat"		=> $contribuyente->modalidad_envio_sunat,
			"direccion_departamento" 	=> $ubigeo->departamento,
			"direccion_provincia" 		=> $ubigeo->provincia,
			"direccion_distrito" 		=> $ubigeo->distrito,
			"direccion_codigopais" 		=> "PE"
	    );
	    
	    $resp['respuesta'] = 'ok';
	    $resp['cliente'] = $cliente;
	    return $resp;
	}
	
	public function get_proveedor($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra) {
	    $compra = Compra::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and id_compra = :id_compra: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'id_compra' => $id_compra, 'tipo_envio_sunat' => $tipo_envio_sunat))); 
		if(!$compra) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Comprobante no existe...';
			return $resp;
		}
		
		$proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor:", 'bind' => array('id_proveedor' => $compra->id_proveedor)));
		if(!$proveedor) {
		    $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El proveedor no existe...';
			return $resp;
		}
		
		$codigo_ubigeo = '';
		$direccion_departamento = '';
		$direccion_provincia = '';
		$direccion_distrito = '';
		
		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $proveedor->id_cod_ubigeo)));
		if($ubigeo) {
		    $codigo_ubigeo = $ubigeo->codigo_ubigeo;
    		$direccion_departamento = $ubigeo->departamento;
    		$direccion_provincia = $ubigeo->provincia;
    		$direccion_distrito = $ubigeo->distrito;
		}
		
		$tipo_doc = 'Doc.Ident.';
		if($proveedor->id_tipodocidentidad == '1') {
		    $tipo_doc = 'DNI';
		} else if($proveedor->id_tipodocidentidad == '1') {
		    $tipo_doc = 'RUC';
		}
		
		$data_proveedor = array(
            "num_doc"					=> $proveedor->num_doc,
			"tipo_doc" 					=> $tipo_doc,
			"email"						=> $proveedor->email,
			"nom_comercial" 			=> $proveedor->razon_social,
			"codigo_ubigeo" 			=> $codigo_ubigeo,
			"direccion"					=> $proveedor->direccion_fiscal,
			"modalidad_envio_sunat"		=> $tipo_envio_sunat,
			"direccion_departamento" 	=> $direccion_departamento,
			"direccion_provincia" 		=> $direccion_provincia,
			"direccion_distrito" 		=> $direccion_distrito,
			"direccion_codigopais" 		=> "PE",
			"telefono"                  => $proveedor->telefono,
			"detalle"                   => $proveedor->detalle_adicional
	    );
	    
	    $resp['respuesta'] = 'ok';
	    $resp['proveedor'] = $data_proveedor;
	    return $resp;
	}
	
	public function get_usuario_vendedor($id_contribuyente, $id_tipodoc_electronico, $tipo_envio_sunat, $id_compra) {
	    $compra = Compra::findFirst(array("estado = 'activo' and id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and id_compra = :id_compra: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'id_compra' => $id_compra, 'tipo_envio_sunat' => $tipo_envio_sunat))); 
		if(!$compra) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Comprobante no existe...';
			return $resp;
		}
		
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $compra->id_usuario)));
		$data_vendedor = array(
		    'nombre_completo' => $usuario->nombre.' '.$usuario->apellido,
		    'id_usuario'    => $usuario->idusuario
		);
		
		$resp['respuesta'] = 'ok';
		$resp['vendedor'] = $data_vendedor;
		return $resp;
	}
}