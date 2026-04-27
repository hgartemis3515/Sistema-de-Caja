<?php
class DocumentoelectronicoController extends ControllerBase
{
	public function indexAction($id_tipodoc_electronico = '01', $accion = 'nuevo', $serie_comprobante = '', $numero_comprobante = '') {
		$this->view->setTemplateAfter('template_new');
		
        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/pickadate/picker.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/pickadate/picker.date.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/pickadate/picker.time.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/pickadate/legacy.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/anytime.min.js?i=v2")
			->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
			->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
			->addJs($this->baseUri . "public/js/documentoelectronico/cliente_flexdatalist.js?i=".rand())
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
			->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
			->addJs($this->baseUri . "public/js/general.js?j=".rand())
			->addJs($this->baseUri . "public/js/numeros_a_letras.js?i=v2")
			->addJs($this->baseUri . "public/js/apisunat.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/input_number_format.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/interaccion_items_detalle.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/nota_credito_debito.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/cuotas_documento.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/docelectronico.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/documento_envio_factura.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/interaccion_modalidadpago.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/registrar_producto.js?i=".rand())
			->addJs($this->baseUri . "public/js/documentoelectronico/funcionalidad_anticipo.js?i=".rand())
			->addJs($this->baseUri . "public/js/producto/modal_ver_stock_varias_sucursales.js?i=".rand());
		
		$this->assets
			->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
			->addCss($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.min.css")
			->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css")
			->addCss($this->baseUri . "public/css/main-indigo.css?i=".rand())
            ->addCss($this->baseUri . "public/css/new_style.css?i=".rand());

		$auth = $this->session->get('authv8');
		$idusuario = isset($auth['idusuario']) ? $auth['idusuario'] : 0;
		
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		$html_suscripcion = $this->get_html_suscripcion($this->get_data_suscripcion($idusuario));
		if($html_suscripcion['suscripcion_activa'] == 'no') {
			$this->dispatcher->forward(array(
				'controller' => 'estadosuscripcion',
				'action'     => 'index'
			));
			return false;
		}
		
		$this->view->html_suscripcion = $html_suscripcion['html'];
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		$lista_condiciones_pago = Condiciondepago::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$this->view->lista_condiciones_pago = $lista_condiciones_pago;

		$accion = empty($accion)?'nuevo':$accion;
		$validar_parametros_index = $this->validar_parametros_index($usuario->id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $accion);
		if($validar_parametros_index['respuesta'] == 'error') {
			echo json_encode($validar_parametros_index);
			exit();
		}

		$gestiondeetiquetas = new GestiondeetiquetasController;
		$lista_etiquetas = $gestiondeetiquetas->get_lista_etiquetas($usuario->id_contribuyente)['lista'];

		$this->setTitle($validar_parametros_index['titulo_pagina']);

		$this->view->modalidad_envio_sunat = $contribuyente->modalidad_envio_sunat;
		if($accion == 'nuevo') {
			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->tipo_doc_modifica = '';
			$this->view->serie_comprobante_modifica = '';
			$this->view->numero_comprobante_modifica = '';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';

		} else if($accion == 'editar') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08') {
				$this->view->tipo_doc_modifica = $documento->id_tipo_comprobante_modifica;
				$this->view->serie_comprobante_modifica = $documento->serie_documento_modifica;
				$this->view->numero_comprobante_modifica = $documento->nro_documento_modifica;
			} else {
				$this->view->tipo_doc_modifica = '';
				$this->view->serie_comprobante_modifica = '';
				$this->view->numero_comprobante_modifica = '';
			}
			
			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->serie_comprobante = $serie_comprobante;
			$this->view->numero_comprobante = $numero_comprobante;

		} else if($accion == 'crear_nota_credito') {
			$documento_a_modificar = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			$this->view->tipo_doc = '07';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';
			$this->view->tipo_doc_modifica = isset($documento_a_modificar->id_tipodoc_electronico)?$documento_a_modificar->id_tipodoc_electronico:'';
			$this->view->serie_comprobante_modifica = isset($documento_a_modificar->serie_comprobante)?$documento_a_modificar->serie_comprobante:'';;
			$this->view->numero_comprobante_modifica = isset($documento_a_modificar->numero_comprobante)?$documento_a_modificar->numero_comprobante:'';
		} else if($accion == 'crear_nota_debito') {
			$documento_a_modificar = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			$this->view->tipo_doc = '08';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';
			$this->view->tipo_doc_modifica = isset($documento_a_modificar->id_tipodoc_electronico)?$documento_a_modificar->id_tipodoc_electronico:'';
			$this->view->serie_comprobante_modifica = isset($documento_a_modificar->serie_comprobante)?$documento_a_modificar->serie_comprobante:'';;
			$this->view->numero_comprobante_modifica = isset($documento_a_modificar->numero_comprobante)?$documento_a_modificar->numero_comprobante:'';
		} else if($accion == 'transformar_cotizacion') {
			$doc_no_oficial = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => '88', 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
			
			$id_tipodoc_guardado = '88';
			$numero_doc_guardado = $numero_comprobante;
			
			if(!$doc_no_oficial) {
				$id_tipodoc_guardado = '';
				$numero_doc_guardado = '';
			}

			$this->view->id_tipodoc_guardado = $id_tipodoc_guardado;
			$this->view->numero_doc_guardado = $numero_doc_guardado;

			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->tipo_doc_modifica = '';
			$this->view->serie_comprobante_modifica = '';
			$this->view->numero_comprobante_modifica = '';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';
		} else if($accion == 'transformar_nota_venta') {
			$doc_no_oficial = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => '77', 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
			
			$id_tipodoc_guardado = '77';
			$numero_doc_guardado = $numero_comprobante;
			
			if(!$doc_no_oficial) {
				$id_tipodoc_guardado = '';
				$numero_doc_guardado = '';
			}

			$this->view->id_tipodoc_guardado = $id_tipodoc_guardado;
			$this->view->numero_doc_guardado = $numero_doc_guardado;

			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->tipo_doc_modifica = '';
			$this->view->serie_comprobante_modifica = '';
			$this->view->numero_comprobante_modifica = '';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';
		} else if($accion == 'duplicar_boleta') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => '03', 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			
			$id_tipodoc_guardado = '03';
			$numero_doc_guardado = $numero_comprobante;
			$serie_doc_guardado = $serie_comprobante;
			
			if(!$documento) {
				$id_tipodoc_guardado = '';
				$numero_doc_guardado = '';
				$serie_doc_guardado = '';
			}

			$this->view->id_tipodoc_guardado = $id_tipodoc_guardado;
			$this->view->serie_doc_guardado = $serie_doc_guardado;
			$this->view->numero_doc_guardado = $numero_doc_guardado;

			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->tipo_doc_modifica = '';
			$this->view->serie_comprobante_modifica = '';
			$this->view->numero_comprobante_modifica = '';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';
		} else if($accion == 'transformar_09_a_01' || $accion == 'transformar_09_a_03' || $accion == 'transformar_09_a_77' || $accion == 'transformar_09_a_88') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => '09', 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			$id_tipodoc_guardado = '09';
			$numero_doc_guardado = $numero_comprobante;
			$serie_doc_guardado = $serie_comprobante;

			if(!$documento) {
				$id_tipodoc_guardado = '';
				$numero_doc_guardado = '';
				$serie_doc_guardado = '';
			}

			$this->view->id_tipodoc_guardado = $id_tipodoc_guardado;
			$this->view->serie_doc_guardado = $serie_doc_guardado;
			$this->view->numero_doc_guardado = $numero_doc_guardado;

			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->tipo_doc_modifica = '';
			$this->view->serie_comprobante_modifica = '';
			$this->view->numero_comprobante_modifica = '';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';

		} else if($accion == 'duplicar_factura') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => '01', 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
			
			$id_tipodoc_guardado = '01';
			$numero_doc_guardado = $numero_comprobante;
			$serie_doc_guardado = $serie_comprobante;
			
			if(!$documento) {
				$id_tipodoc_guardado = '';
				$numero_doc_guardado = '';
				$serie_doc_guardado = '';
			}

			$this->view->id_tipodoc_guardado = $id_tipodoc_guardado;
			$this->view->serie_doc_guardado = $serie_doc_guardado;
			$this->view->numero_doc_guardado = $numero_doc_guardado;

			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->tipo_doc_modifica = '';
			$this->view->serie_comprobante_modifica = '';
			$this->view->numero_comprobante_modifica = '';
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = '';
		} else if($accion == 'editar_cotizacion') {
			$doc_no_oficial = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => '88', 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));
			$id_tipodoc_guardado = '88';
			$numero_doc_guardado = $numero_comprobante;
			
			if(!$doc_no_oficial) {
				$id_tipodoc_guardado = '';
				$numero_doc_guardado = '';
			}

			$this->view->id_tipodoc_guardado = $id_tipodoc_guardado;
			$this->view->numero_doc_guardado = $numero_doc_guardado;
			
			$this->view->tipo_doc_modifica = ''; //nota debito o crédito
			$this->view->serie_comprobante_modifica = ''; //nota debito o crédito
			$this->view->numero_comprobante_modifica = ''; //nota debito o crédito

			$this->view->tipo_doc = $id_tipodoc_electronico;
			$this->view->serie_comprobante = '';
			$this->view->numero_comprobante = $numero_comprobante;

			$lista_etiquetas = $gestiondeetiquetas->get_lista_etiquetas($usuario->id_contribuyente, '88', '', $numero_comprobante, $contribuyente->tipo_envio_sunat)['lista'];
		}

		$origen_id_contribuyente = "";
		$origen_id_tipodoc_electronico = "";
		$origen_serie_comprobante = "";
		$origen_numero_comprobante = "";
		$origen_tipo_envio_sunat = "";

		if(in_array($accion, array('editar', 'crear_nota_credito', 'crear_nota_debito', 'transformar_cotizacion', 'transformar_nota_venta', 'duplicar_boleta', 'duplicar_factura', 'editar_cotizacion', 'transformar_09_a_01', 'transformar_09_a_03', 'transformar_09_a_77', 'transformar_09_a_88'))) {
			$origen_id_contribuyente = $contribuyente->id_contribuyente;
			$origen_id_tipodoc_electronico = $id_tipodoc_electronico;
			$origen_serie_comprobante = $serie_comprobante;
			$origen_numero_comprobante = $numero_comprobante;
			$origen_tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		}
		$this->view->origen_id_contribuyente = $origen_id_contribuyente;
		$this->view->origen_id_tipodoc_electronico = isset($id_tipodoc_guardado)?$id_tipodoc_guardado:'';
		$this->view->origen_serie_comprobante = $origen_serie_comprobante;
		$this->view->origen_numero_comprobante = $origen_numero_comprobante;
		$this->view->origen_tipo_envio_sunat = $origen_tipo_envio_sunat;

		
        $herramientas = new HerramientasController;
        $this->view->html_sugerencias = $herramientas->get_html_primerospaso($usuario->id_contribuyente);
		$this->view->num_decimales = $contribuyente->num_decimales;
		$this->view->precio_venta_minimo = $contribuyente->precio_venta_minimo;
		$this->view->regimen_retencion = $contribuyente->regimen_retencion;

		$anio_actual =  date("Y");
        $icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
        if(!$icbper) {
            $impuesto_icbper = '0.5';
        } else {
            $impuesto_icbper = $icbper->monto;
		}
        
		$this->view->impuesto_icbper = $impuesto_icbper;
		$this->view->usuario = $usuario;
		$this->view->vendedores = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

		$percepcion_percepcion = 'no';
		if($contribuyente->tiene_detracciones == 'si' || $contribuyente->tiene_percepciones == 'si') {
			$percepcion_percepcion = 'si';
		}

		$this->view->tipo_percepcion = SunatCodigotipopercepcion::find();
		$this->view->mediosdepago = SunatMediosdepago::find();
		$this->view->tipo_operacion = SunatTipooperacion::find(array("order" => "orden ASC"));
		$this->view->percepcion_percepcion = $percepcion_percepcion;
		$this->view->cuentas_detracciones = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta = 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$this->view->cuentas_banco = Cuentabanco::find(array("id_contribuyente = :id_contribuyente: and tipo_cuenta <> 'cuenta_detracciones' and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		$this->view->bienes_detracciones = SunatCodigodetraccion::find();

		if($this->verificar_permisos_creacion_documento($usuario, 'factura') == false && $this->verificar_permisos_creacion_documento($usuario, 'boleta') == false && $this->verificar_permisos_creacion_documento($usuario, 'notas_credito') == false && $this->verificar_permisos_creacion_documento($usuario, 'notas_debito') == false && $this->verificar_permisos_creacion_documento($usuario, 'nota_venta') == false && $this->verificar_permisos_creacion_documento($usuario, 'cotizacion') == false && $this->verificar_permisos_creacion_documento($usuario, 'editar_cotizacion') == false) {
			$this->response->redirect('dashboard');
		}

		$this->view->lista_etiquetas = $lista_etiquetas;
		$this->view->accion_cpe = $accion;
		$this->view->contribuyente = $contribuyente;

		//validar permisos usuario
		if($usuario->id_rol == 4) {
			$modificar_precio_en_pantalla_venta = $this->get_usuario_opcion($usuario->id_contribuyente, $usuario->idusuario, 'modificar_precio_en_pantalla_venta');
			$modificar_precio_en_pantalla_venta = ($modificar_precio_en_pantalla_venta == '')?'si':$modificar_precio_en_pantalla_venta;
			
        	$venta_debajo_precio_minimo = $this->get_usuario_opcion($usuario->id_contribuyente, $usuario->idusuario, 'venta_debajo_precio_minimo');
			$venta_debajo_precio_minimo = ($venta_debajo_precio_minimo == '')?'si':$venta_debajo_precio_minimo;
		} else {
			$modificar_precio_en_pantalla_venta = 'si';
			$venta_debajo_precio_minimo = 'si';
		}
		
		$this->view->modificar_precio_en_pantalla_venta = $modificar_precio_en_pantalla_venta;
		$this->view->venta_debajo_precio_minimo = $venta_debajo_precio_minimo;
	}

	public function validar_parametros_index($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $accion) {

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$this->view->disable();
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			echo json_encode($resp);
			exit();
		}

		$titulo = '';
		if($id_tipodoc_electronico == '01') {
			$titulo = 'Factura';
		} else if ($id_tipodoc_electronico == '03') {
			$titulo = 'Boleta';
		} else if ($id_tipodoc_electronico == '07') {
			$titulo = 'Nota de Crédito';
		} else if ($id_tipodoc_electronico == '08') {
			$titulo = 'Nota de Débito';
		} else if($id_tipodoc_electronico == '77') {
			$titulo = 'Nota de Venta';
		} else if($id_tipodoc_electronico == '88') {
			$titulo = 'Cotización';
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de documento no es válido!';
			return $resp;
		}

		//acciones aceptadas!
		//nuevo: Se utilizará cuando se creará una nueva factura, boleta, nota de crédito y débito!
		//editar: Se utilizará cuando se editará una factura, boleta, nota de crédito y débito (tener en cuenta que todo documento que tenga un cdr no podrá ser editado)
		//crear_nota_credito = se utilizará cuando se selecciona una factura/boleta y se debe crear su correspondiente nota de crédito (recordar que no se puede crear una nota de crédito de un documento que no tenga un cdr válido)
		//crear_nota_debito = se utilizará cuando se selecciona una factura/boleta y se debe crear su correspondiente nota de débito
		
		$array_acciones_validas = array('nuevo', 'editar', 'crear_nota_credito', 'crear_nota_debito', 'transformar_cotizacion', 'transformar_nota_venta', 'duplicar_boleta', 'duplicar_factura', 'editar_cotizacion', 'transformar_09_a_01', 'transformar_09_a_03', 'transformar_09_a_77', 'transformar_09_a_88');
		if(!in_array($accion, $array_acciones_validas)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra la acción solicitada!';
			return $resp;
		}

		if($accion == 'editar') {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$documento) {
				$this->view->disable();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Edición';
				$resp['mensaje'] = 'El documento electrónico no se encuentra o no es válido!';
				return $resp;
			}
			
			if($documento->estado_envio_sunat == 'pendiente') {
				//puede que el archivo esté firmado y pendiente de envío
				if($documento->tipo_envio_sunat == 'produccion') {
					$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
				} else {
					$ruta_base_cpe_cdr = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
				}
				
				//Si se ingresa a la opción editar, es importante eliminar el certificado previamente creado para no complicar el proceso
				if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03') {
					$ruta_base_cpe_cdr = $ruta_base_cpe_cdr.'facturas_boletas/';
					$ruta_zip_file_xml = $ruta_base_cpe_cdr.$documento->name_xml_zip;
					@unlink($ruta_zip_file_xml);
				} elseif ($id_tipodoc_electronico == '07') {
					$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

					if(!$documento_modificado) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El documento que modifica la presente nota de crédito no existe!!';
						return $resp;
					}

					$ruta_base_cpe_cdr = $ruta_base_cpe_cdr.'nota_credito/';
					$ruta_zip_file_xml = $ruta_base_cpe_cdr.$documento->name_xml_zip;
					@unlink($ruta_zip_file_xml);
				} elseif ($id_tipodoc_electronico == '08') {

					$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $documento->id_tipo_comprobante_modifica, 'serie_comprobante' => $documento->serie_documento_modifica, 'numero_comprobante' => $documento->nro_documento_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

					if(!$documento_modificado) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El documento que modifica la presente nota de débito no existe!!';
						return $resp;
					}

					$ruta_base_cpe_cdr = $ruta_base_cpe_cdr.'nota_debito/';
					$ruta_zip_file_xml = $ruta_base_cpe_cdr.$documento->name_xml_zip;
					unlink($ruta_zip_file_xml);
				}

			} else if($documento->estado_documento == 'aceptado') { //Recordar: Si ya tiene un cdr entonces no puede ser editado
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento seleccionado ya no se puede editar, pues ya fué enviado correctamente a SUNAT!';
				return $resp;
			} else if($documento->estado_documento == 'rechazado') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento seleccionado ya no se puede editar, pues ya fué rechazado por SUNAT!';
				return $resp;
			} else {				
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento seleccionado ya no se puede editar, pues no contiene un estado válido!';
				return $resp;
			}
		} else if($accion == 'crear_nota_credito') {
			//verificamos que no existe una nota con el mismo tipo que se intenta crear
			//hay que recordar que solamente se puede crear una nota de crédito por factura o boleta!
			/*
			$verify_previus_note = DocElectronico::findFirst(
				array("
				id_contribuyente = :id_contribuyente: and 
				id_tipodoc_electronico = :id_tipodoc_electronico: and 
				id_tipo_comprobante_modifica = :id_tipo_comprobante_modifica: and 
				serie_documento_modifica = :serie_documento_modifica: and 
				nro_documento_modifica = :nro_documento_modifica: and 
				tipo_envio_sunat = :tipo_envio_sunat: and estado_envio_sunat  in ('aceptado', 'ticket', 'pendiente')",  
				'bind' => array(
								'id_contribuyente' => $id_contribuyente,
								'id_tipodoc_electronico' => '07',
								'id_tipo_comprobante_modifica' => $id_tipodoc_electronico,
								'serie_documento_modifica' => $serie_comprobante, 
								'nro_documento_modifica' => $numero_comprobante,
								'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat
							)
						)
					);

			if($verify_previus_note) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Previamente ya se registró la siguiente nota de crédito: '.$verify_previus_note->serie_comprobante.'-'.$verify_previus_note->numero_comprobante.', para la presente factura.';
				return $resp;
			}
			*/

			$reportedocumentos = new ReportedocumentosController;
			$nota_credito_anulacion = $reportedocumentos->tiene_nota_credito_anulacion($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante);
			if($nota_credito_anulacion['respuesta'] == 'ok') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El comprobante ya fué anulado con la siguiente nota de crédito: '.$nota_credito_anulacion['nota_credito']->serie_comprobante.'-'.$nota_credito_anulacion['nota_credito']->numero_comprobante;
				return $resp;
			}

		} else if($accion == 'crear_nota_debito' ) {
			//verificamos que no existe una nota con el mismo tipo que se intenta crear
			//hay que recordar que solamente se puede crear una nota de crédito por factura o boleta!
			$verify_previus_note = DocElectronico::findFirst(
				array("
				id_contribuyente = :id_contribuyente: and 
				id_tipodoc_electronico = :id_tipodoc_electronico: and 
				id_tipo_comprobante_modifica = :id_tipo_comprobante_modifica: and 
				serie_documento_modifica = :serie_documento_modifica: and 
				nro_documento_modifica = :nro_documento_modifica: and 
				tipo_envio_sunat = :tipo_envio_sunat: and estado_envio_sunat  in ('aceptado', 'ticket', 'pendiente')", 
				'bind' => array(
								'id_contribuyente' => $id_contribuyente,
								'id_tipodoc_electronico' => '08',
								'id_tipo_comprobante_modifica' => $id_tipodoc_electronico,
								'serie_documento_modifica' => $serie_comprobante, 
								'nro_documento_modifica' => $numero_comprobante,
								'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat
							)
						)
					);
			if($verify_previus_note) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Previamente ya se registró la siguiente nota de Débito: '.$verify_previus_note->serie_comprobante.'-'.$verify_previus_note->numero_comprobante.', para la presente factura.';
				return $resp;
			}
		}
		
		$resp['respuesta'] = 'ok';
		$resp['titulo_pagina'] = $titulo;
		return $resp;

	}

	/*se utiliza para recepcionar la data de factura, boleta, 
	nota de crédito y débito, no se recomienda utilizar para los demás tipos de documentos electrónicos,
	ya que el array de detalle para los demás tipos de documentos se forma de una manera diferente.
	*/
	public function guardarDocumentoAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}
			
			$resp_proceso = $this->procesar_documento_electronico($usuario, $datapost);
			echo json_encode($resp_proceso);
			exit();
		}
	}

	public function is_admin($usuario) {
		if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3 || $usuario->id_rol == 5) {
			return true;
		} else {
			return false;
		}
	}

	public function procesar_documento_electronico($usuario, $datapost) {

		$herramientas = new HerramientasController;
		$gestiondeetiquetas = new GestiondeetiquetasController;

		$usuario_original = $usuario;
		if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3 || $usuario->id_rol == 5) {
			$idusuario = isset($datapost['select_usuario_vendedor'])?intval($datapost['select_usuario_vendedor']):0;
			$usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $usuario->id_contribuyente)));
			if($usuario_vendedor) {
				$usuario = $usuario_vendedor;
			}
		}

		//hasta aquí se demora: 2.029 segundos
		
		$resp_idcliente = $this->get_idcliente($usuario, $datapost);
		if($resp_idcliente['respuesta'] == 'error') {
			return $resp_idcliente;
		}

		//hasta aquí se demoró 22 segundos
		//colocando como indice al número de documento cambió el timpo de proceso a solo 1.96 segundos

		$idcliente = $resp_idcliente['idcliente'];
		$datapost['idcliente'] = $idcliente;
		$resp_validacion = $this->valida_datos_iniciales($usuario, $datapost);
		if($resp_validacion['respuesta'] == 'error') {
			return $resp_validacion;
		}
		$datapost = $resp_validacion['datapost'];
		
		$resp_detalle = $this->validar_data_detalle($usuario, $datapost, $datapost['select_tipo_doc_electronico'], $resp_validacion['cabecera']['idsucursal']);
		if($resp_detalle['respuesta'] == 'error') {
			return $resp_detalle;
		}

		$resp_data_emisor = $this->get_data_emisor($usuario->id_contribuyente);
		if($resp_data_emisor['respuesta'] == 'error') {
			return $resp_data_emisor;
		}

		$resp_data_cliente = $this->get_data_cliente($idcliente);
		if($resp_data_cliente['respuesta'] == 'error') {
			return $resp_data_cliente;
		}
		
		$cabecera_inicial = $resp_validacion['cabecera']; //no es la cabecera final que será enviado, pues son datos principales para luego armar la cabecera.
		$detalle = $resp_detalle['detalle'];
		$emisor = $resp_data_emisor['emisor'];
		$cliente = $resp_data_cliente;
		$id_contribuyente = $usuario->id_contribuyente;

		$resp_payment_splits = $this->validate_payments_splits($datapost, $cabecera_inicial, $usuario->id_contribuyente);
		if($resp_payment_splits['respuesta'] == 'error') {
			return $resp_payment_splits;
		}
		$datapost = $resp_payment_splits['datapost'];

		$array_etiquetas = isset($datapost['lista_etiquetas'])?explode(',', $datapost['lista_etiquetas']):array();
		$resp_etiquetas = $gestiondeetiquetas->validar_existencia_etiquetas($id_contribuyente, $array_etiquetas);
		if($resp_etiquetas['respuesta'] == 'error') {
			return $resp_etiquetas;
		}

		$resp_anticipos = $this->valida_si_tiene_anticipos($usuario, $datapost);
		if($resp_anticipos['respuesta'] == 'error') {
			return $resp_anticipos;
		}
		$datapost['lista_anticipos'] = $resp_anticipos['lista_anticipos'];
		$datapost['tiene_anticipos'] = $resp_anticipos['tiene_anticipos'];

		$confirmacion = empty($datapost['confirmacion'])?'':$datapost['confirmacion'];
		if($confirmacion != 'si') {
			$resp['respuesta'] = 'ok';
			$resp['mensaje'] = '';
			return $resp;
		}

		$opcion_envio_email = !isset($datapost['opcion_envio_email'])?'no':$datapost['opcion_envio_email'];

		$gestion_usuarios = new GestionuserController;
		if(!$this->is_admin($usuario_original)) {
			if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') != 'acceso_total') {
					if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') != 'todas_las_sucursales') {
						if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') == 'prohibir') {
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'Se ha restringido la creación de documentos electrónicos para su cuenta!';
							return $resp;
						}

						if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_ventas') == 'sucursal_asignada') {
							if($usuario->idsucursal != $cabecera_inicial['idsucursal']) {
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = 'No Tiene Permisos para Crear Ventas en la Sucursal con ID: '.$cabecera_inicial['idsucursal'];
								return $resp;
							}
						}
					}
				}
			}
		}

		$gestion_de_contribuyentes = new GestiondecontribuyentesController;
		
		if($datapost['select_tipo_doc_electronico'] == '01') {
			if($gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_emision_facturas') == 'no') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se puede emitir una factura, pues el contribuyente tiene habilitado la emisión de Facturas!';
				return $resp;
			}

			if(!$this->is_admin($usuario_original)) {
				if($this->verificar_permisos_creacion_documento($usuario, 'factura') == false) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No tiene permisos para registrar Facturas Electrónicas!';
					return $resp;
				}
			}

			$nombre_doc_electronico = 'FACTURA ELECTRONICA';
			$factura = new FacturaController;
			$resp_procesamiento = $factura->procesar_factura($id_contribuyente, $usuario->idusuario, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		} elseif ($datapost['select_tipo_doc_electronico'] == '03') {
			if($gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_emision_boletas') == 'no') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se puede emitir una factura, pues el contribuyente tiene habilitado la emisión de Boletas!';
				return $resp;
			}

			if(!$this->is_admin($usuario_original)) {
				if($this->verificar_permisos_creacion_documento($usuario, 'boleta') == false) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No tiene permisos para registrar Boletas Electrónicas!';
					return $resp;
				}
			}
			
			$nombre_doc_electronico = 'BOLETA ELECTRONICA';
			$boleta = new BoletaController;
			$resp_procesamiento = $boleta->procesar_boleta($id_contribuyente, $usuario->idusuario, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		} elseif ($datapost['select_tipo_doc_electronico'] == '07') {
			if($gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_emision_notas_credito') == 'no') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se puede emitir una factura, pues el contribuyente tiene habilitado la emisión de Notas de Crédito!';
				return $resp;
			}

			if(!$this->is_admin($usuario_original)) {
				if($this->verificar_permisos_creacion_documento($usuario, 'notas_credito') == false) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No tiene permisos para registrar Notas de Crédito!';
					return $resp;
				}
			}

			$nombre_doc_electronico = 'NOTA DE CREDITO';
			$notadecredito = new NotadecreditoController;
			$resp_procesamiento = $notadecredito->procesar_notacredito($id_contribuyente, $usuario->idusuario, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		} elseif ($datapost['select_tipo_doc_electronico'] == '08') {
			if($gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_emision_notas_debito') == 'no') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se puede emitir una factura, pues el contribuyente tiene habilitado la emisión de Notas de Débito!';
				return $resp;
			}

			if(!$this->is_admin($usuario_original)) {
				if($this->verificar_permisos_creacion_documento($usuario, 'notas_debito') == false) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No tiene permisos para registrar Notas de Débito!';
					return $resp;
				}
			}

			$nombre_doc_electronico = 'NOTA DE DEBITO';
			$notadedebito = new NotadedebitoController;
			$resp_procesamiento = $notadedebito->procesar_notadebito($id_contribuyente, $usuario->idusuario, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		} elseif ($datapost['select_tipo_doc_electronico'] == '77') {
			if($gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_emision_notas_venta') == 'no') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se puede emitir una factura, pues el contribuyente tiene habilitado la emisión de Notas de Venta!';
				return $resp;
			}

			if(!$this->is_admin($usuario_original)) {
				if($this->verificar_permisos_creacion_documento($usuario, 'nota_venta') == false) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No tiene permisos para registrar Notas de Venta!';
					return $resp;
				}
			}

			$nombre_doc_electronico = 'NOTA DE VENTA';
			$notadeventa = new NotadeventaController;
			$resp_procesamiento = $notadeventa->procesar_notadeventa($id_contribuyente, $usuario->idusuario, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		} elseif ($datapost['select_tipo_doc_electronico'] == '88') {
			if(!$this->is_admin($usuario_original)) {
				if($this->verificar_permisos_creacion_documento($usuario, 'cotizacion') == false) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No tiene permisos para registrar Cotizaciones!';
					return $resp;
				}
			}

			if($cabecera_inicial['modo_edicion'] == 'si') {
				if(!$this->is_admin($usuario_original)) {
					if($this->verificar_permisos_creacion_documento($usuario, 'editar_cotizacion') == false) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'No tiene permisos para editar Cotizaciones!';
						return $resp;
					}
				}
			}

			$nombre_doc_electronico = 'COTIZACIÓN';
			$cotizacion = new CotizacionController;
			$resp_procesamiento = $cotizacion->procesar_cotizacion($id_contribuyente, $usuario->idusuario, $cabecera_inicial, $detalle, $emisor, $cliente, $datapost);
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error Tipo Documento';
			$resp['mensaje'] = 'No se reconoce el tipo de documento!';
			return $resp;
		}
		
		if($opcion_envio_email != 'no') {
			if($resp_procesamiento['respuesta'] == 'ok') {
				if(!$this->bloquear_correos) {
					$enviar_email = new EnviaremailController;
					$resp_email = $enviar_email->enviar_email_documento($resp_procesamiento['documento']['id_contribuyente'], $resp_procesamiento['documento']['id_tipodoc_electronico'], $resp_procesamiento['documento']['serie_comprobante'], $resp_procesamiento['documento']['numero_comprobante'], $resp_procesamiento['documento']['tipo_envio_sunat']);
				}
			}
		}
		
		
		$dominio_principal = $this->get_parametros_iniciales()['url_domain'];

		$db_doc_id_contribuyente = isset($resp_procesamiento['documento']['id_contribuyente'])?$resp_procesamiento['documento']['id_contribuyente']:' ';
		$db_doc_id_tipodoc_electronico = isset($resp_procesamiento['documento']['id_tipodoc_electronico'])?$resp_procesamiento['documento']['id_tipodoc_electronico']:' ';
		$db_doc_serie_comprobante = isset($resp_procesamiento['documento']['serie_comprobante'])?$resp_procesamiento['documento']['serie_comprobante']:' ';
		$db_doc_numero_comprobante = isset($resp_procesamiento['documento']['numero_comprobante'])?$resp_procesamiento['documento']['numero_comprobante']:' ';
		$db_doc_tipo_envio_sunat = isset($resp_procesamiento['documento']['tipo_envio_sunat'])?$resp_procesamiento['documento']['tipo_envio_sunat']:' ';

		if(isset($array_etiquetas) && is_array($array_etiquetas)) {
			$resp_asignacion_etiquetas = $gestiondeetiquetas->asignar_grupo_etiquetas_documento($db_doc_id_contribuyente, $usuario->idusuario, $db_doc_id_tipodoc_electronico, $db_doc_serie_comprobante, $db_doc_numero_comprobante, $db_doc_tipo_envio_sunat, $array_etiquetas);
		}

		$string_encrypted_document_a4 = $herramientas->encriptar("$db_doc_id_contribuyente||$db_doc_id_tipodoc_electronico||$db_doc_serie_comprobante||$db_doc_numero_comprobante||$db_doc_tipo_envio_sunat||a4");
		$string_encrypted_document_ticket = $herramientas->encriptar("$db_doc_id_contribuyente||$db_doc_id_tipodoc_electronico||$db_doc_serie_comprobante||$db_doc_numero_comprobante||$db_doc_tipo_envio_sunat||ticket");

		$resp_procesamiento['cadena_a4'] = $string_encrypted_document_a4;
		$resp_procesamiento['cadena_ticket'] = $string_encrypted_document_ticket;
		$resp_procesamiento['url_absoluta_a4'] = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$resp_procesamiento['url_absoluta_ticket'] = 'https://'.$dominio_principal.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
		$resp_procesamiento['url_relativa_a4'] = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
		$resp_procesamiento['url_relativa_ticket'] = '/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);

		$resp_procesamiento['cadena_xml'] = "/$db_doc_id_contribuyente/$db_doc_id_tipodoc_electronico/$db_doc_serie_comprobante/$db_doc_numero_comprobante/xml_cpe_zip";
		$resp_procesamiento['url_relativa_xml'] = "/facturacionv8/download/downloadcpe/$db_doc_id_contribuyente/$db_doc_id_tipodoc_electronico/$db_doc_serie_comprobante/$db_doc_numero_comprobante/xml_cpe_zip";
		$resp_procesamiento['url_absoluta_xml'] = "https://$dominio_principal/facturacionv8/download/downloadcpe/$db_doc_id_contribuyente/$db_doc_id_tipodoc_electronico/$db_doc_serie_comprobante/$db_doc_numero_comprobante/xml_cpe_zip";

		$resp_procesamiento['cadena_xml_cdr'] = "/$db_doc_id_contribuyente/$db_doc_id_tipodoc_electronico/$db_doc_serie_comprobante/$db_doc_serie_comprobante/xml_cdr_zip";
		$resp_procesamiento['url_relativa_xml_cdr'] = "/facturacionv8/download/downloadcpe/$db_doc_id_contribuyente/$db_doc_id_tipodoc_electronico/$db_doc_serie_comprobante/$db_doc_numero_comprobante/xml_cdr_zip";
		$resp_procesamiento['url_absoluta_xml_cdr'] = "https://$dominio_principal/facturacionv8/download/downloadcpe/$db_doc_id_contribuyente/$db_doc_id_tipodoc_electronico/$db_doc_serie_comprobante/$db_doc_numero_comprobante/xml_cdr_zip";

		$documento_bd = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $db_doc_id_contribuyente, 'id_tipodoc_electronico' => $db_doc_id_tipodoc_electronico, 'serie_comprobante' => $db_doc_serie_comprobante, 'numero_comprobante' => $db_doc_numero_comprobante , 'tipo_envio_sunat' => $db_doc_tipo_envio_sunat)));
		if($documento_bd) {
			$resp_procesamiento['estado_envio_sunat'] = $documento_bd->estado_envio_sunat;
			$resp_procesamiento['hash_cpe'] = $documento_bd->hash_cpe;
			$resp_procesamiento['hash_cdr'] = $documento_bd->hash_cdr;
			$resp_procesamiento['cod_sunat'] = $documento_bd->cod_sunat;
		}
		
		return $resp_procesamiento;
	}

	public function valida_si_tiene_anticipos($usuario, $datapost) {
		$tiene_anticipos = isset($datapost['select_tiene_anticipos'])?$datapost['select_tiene_anticipos']:'no';
		$tiene_anticipos = $tiene_anticipos == 'si'?'si':'no';

		$lista_anticipos = array();
		if($tiene_anticipos == 'no') {
			$resp['respuesta'] = 'ok';
			$resp['lista_anticipos'] = $lista_anticipos;
			$resp['tiene_anticipos'] = 'no';
			return $resp;
		}

		$anticipos = isset($datapost['anticipos'])?$datapost['anticipos']:'';
		$anticipos = json_decode($anticipos, true);
		if(!isset($anticipos['items'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'No se encontraron datos de anticipos, por favor ingresa los datos de anticipos...';
            return $resp;
        }

		if(!is_array($anticipos['items'])) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'No se encontraron datos de anticipos, por favor ingresa los datos de anticipos...';
            return $resp;
        }

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));

		$item_anticipos = $anticipos['items'];
		$n = 0;
		foreach($item_anticipos as $item) {
			$n++;
			$item = (object)$item;
			$anticipo_tipo_documento = isset($item->tipo_documento_anticipo)?$item->tipo_documento_anticipo:'';
			$anticipo_serie_comprobante = isset($item->serie_comprobante)?$item->serie_comprobante:'';
			$anticipo_correlativo_comprobante = isset($item->correlativo_comprobante)?$item->correlativo_comprobante:'';
			$anticipo_monto = isset($item->monto_anticipo)?floatval($item->monto_anticipo):0;

			if(empty($anticipo_tipo_documento)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Usuario';
				$resp['mensaje'] = 'Revisa el Item N° '.$n.' del anticipo, falta el tipo de documento del comprobante!...';
				return $resp;
			}

			if(empty($anticipo_serie_comprobante)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Usuario';
				$resp['mensaje'] = 'Revisa el Item N° '.$n.' del anticipo, falta la serie del comprobante!...';
				return $resp;
			}

			if(empty($anticipo_correlativo_comprobante)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Usuario';
				$resp['mensaje'] = 'Revisa el Item N° '.$n.' del anticipo, falta el correlativo del comprobante!...';
				return $resp;
			}

			if($anticipo_monto <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Usuario';
				$resp['mensaje'] = 'Revisa el Item N° '.$n.' del anticipo, al parecer el monto es incorrecto!...';
				return $resp;
			}

			$anticipo_documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $anticipo_tipo_documento, 'serie_comprobante' => $anticipo_serie_comprobante, 'numero_comprobante' => $anticipo_correlativo_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$anticipo_documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Usuario';
				$resp['mensaje'] = 'Revisa el Item N° '.$n.' del anticipo, al parecer el documento no existe!...';
				return $resp;
			}

			$lista_anticipos[] = array(
				'id_tipodoc_electronico' => $anticipo_tipo_documento,
				'serie_comprobante' => $anticipo_serie_comprobante,
				'numero_comprobante' => $anticipo_correlativo_comprobante,
				'monto' => $anticipo_monto
			);
		}

		$resp['respuesta'] = 'ok';
		$resp['lista_anticipos'] = $lista_anticipos;
		$resp['tiene_anticipos'] = 'si';
		return $resp;
	}

	public function verificar_permisos_creacion_documento($usuario, $tipo_documento) {
		$gestion_usuarios = new GestionuserController;
		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos') == 'acceso_denegado') {
					return false;
				}
				return $gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', $tipo_documento);
			}
		}
		return true;
	}
	
	public function get_data_cliente($idcliente) {
		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El cliente seleccionado no existe!';
			return $resp;
		}

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
		
		$data_cliente = array(
			"cliente_numerodocumento"       => $cliente->num_doc,
			"cliente_email"					=> $cliente->email,
			"cliente_celular"				=> $cliente->celular,
			"cliente_nombre"                => $cliente->razon_social,
			"cliente_tipodocumento"         => $cliente->id_tipodocidentidad,
			"cliente_direccion"             => $cliente->direccion_fiscal,
			"cliente_pais"         			=> "PE",
			"cliente_ciudad"				=> "",
			"cliente_codigoubigeo"          => !$ubigeo?"":$ubigeo->codigo_ubigeo,
			"cliente_departamento"          => !$ubigeo?"":$ubigeo->departamento,
			"cliente_provincia"         	=> !$ubigeo?"":$ubigeo->provincia,
			"cliente_distrito"              => !$ubigeo?"":$ubigeo->distrito
		);

		$resp['respuesta'] = 'ok';
		$resp['idcliente'] = $idcliente;
		$resp['data_cliente'] = $data_cliente;
		return $resp;
	}

	public function get_data_emisor($id_contribuyente) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes iniciar sesión.';
			return $resp;
		}

		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $contribuyente->codigo_ubigeo)));
		if(!$ubigeo) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El código de ubigeo seleccionado para el contribuyente no es válido!';
			return $resp;
		}
 
		if($contribuyente->tipo_envio_sunat == 'produccion') {
			$emisor = array(
				"ruc"						=> $contribuyente->ruc,
				"tipo_doc" 					=> "6",
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
		} else {
			$ruc_emisor = '20100066603';
			if($contribuyente->tipo_empresa_sunat == 'prico') {
				$ruc_emisor = $contribuyente->ruc;
			}

			$emisor = array(
				"ruc"						=> $ruc_emisor,
				"tipo_doc" 					=> "6",
				"email"						=> 'facturalaya.srl@gmail.com',
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
		}

		$resp['respuesta'] = 'ok';
		$resp['emisor'] = $emisor;
		return $resp;
	}

	public function validar_data_detalle($usuario, $datapost, $id_tipodoc_electronico, $idsucursal) {
		$idusuario = $usuario->idusuario;
		$detalle_documento = json_decode($datapost['detalle']);
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) { 
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
			echo json_encode($resp);
			exit();
		}

		if(!is_array($detalle_documento)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
			return $resp;
		}

		if(count($detalle_documento) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
			return $resp;
		}

		$num_decimales = 2;
		if($contribuyente->num_decimales > 2) {
			$num_decimales = $contribuyente->num_decimales;
		}

		//validar permisos usuario
		if($usuario->id_rol == 4) {
        	$venta_debajo_precio_minimo = $this->get_usuario_opcion($usuario->id_contribuyente, $usuario->idusuario, 'venta_debajo_precio_minimo');
			$venta_debajo_precio_minimo = ($venta_debajo_precio_minimo == '')?'si':$venta_debajo_precio_minimo;
		} else {
			$venta_debajo_precio_minimo = 'si';
		}

		$lista = array();
		$n = 0;
		foreach($detalle_documento as $item) {
			$n++;
			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item->idarticulo, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$producto) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Producto';
				$resp['mensaje'] = 'El producto: '.$item->descripcion.', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
				return $resp;
			}

			$precio_minimo_producto = floatval($producto->precio_venta_minimo) + 0; //precio

			$cantidad_a_vender = $item->cantidad;
			$idunidad_medida = str_replace('UND-', '', $item->idunidadmedida);
			$idunidad_medida = intval($idunidad_medida);
			$tipo_unidad_medida = 'UND';
			$id_presentacion_producto = null;
			if (strpos($item->idunidadmedida, '-') !== false) {
				//existe un guión
				$array_unidad_medida = explode('-', $item->idunidadmedida);
				if(count($array_unidad_medida) > 1) {
					$tipo_unidad_medida = ($array_unidad_medida[0] == 'PRE')?'PRE':'UND'; //PRE: presentacion, UND: unidad_medida

					if($tipo_unidad_medida == 'PRE') {
						//validar si existe la presentación
						$id_presentacion_producto = $item->idpresentacion;
						$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion: and estado = 'activo'", 'bind' => array('id_presentacion' => $id_presentacion_producto)));
						if(!$presentacion) {
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = "En el item N° $n para el producto: $item->descripcion, usted ha seleccionado una unidad inválida!";
							return $resp;
						}

						$idunidad_medida = intval($presentacion->idunidad);
						$cantidad_und_base = floatval($presentacion->cantidad_und_base) + 0;
						$cantidad_a_vender = round($item->cantidad*$cantidad_und_base, 4);
						$precio_minimo_producto = floatval($presentacion->precio_minimo) + 0;
					} else {
						$idunidad_medida = intval($array_unidad_medida[1]);
					}
				}
			}

			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $idunidad_medida)));
			if(!$unidadmedida) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No existe la unidad de medida seleccionada: '.$item->descripcion.' (idunidad: '.$item->idunidadmedida.')';
				return $resp;
			}

			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			if(!$tipoafectacionigv) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto: '.$item->descripcion;
				return $resp;
			}

			if($contribuyente->precio_venta_minimo == 'si') {
				if($unidadmedida->codigo != 'ZZ') {
					if($usuario->id_rol == 4) {
						//if($usuario->validar_precio_minimo == 'si') {
							if($item->precio < $producto->precio_venta_minimo) {
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = 'El producto '.$producto->nombre.' ('.$unidadmedida->nombre.') no se puede vender con un precio menor a: '.$producto->precio_venta_minimo.'. <strong class="text-danger">Usted no tiene permiso para vender productos por debajo del precio mínimo';
								return $resp;
							}
						//}
					}
				}
			}
			
			if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '77') {
				if($contribuyente->restriccion_stock == 'si') {
					if($unidadmedida->codigo != 'ZZ') {
						if($cantidad_a_vender > $producto->stock) {
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'El producto '.$producto->nombre.' ('.$unidadmedida->nombre.') tiene un stock actual de '.$producto->stock.' '.$unidadmedida->simbolo.'. <strong class="text-danger">Usted activo la opción de restricción de stock </strong>, por tanto no puede vender más de '.$producto->stock.' '.$unidadmedida->simbolo;
							return $resp;
						}
					}
				}
			}

			if($contribuyente->multi_almacen == 'si') {
				if($producto->idsucursal != $idsucursal) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El producto '.$producto->nombre.' ('.$unidadmedida->nombre.'), no pertenece a la sucursal seleccionada en el documento electrónico, debe eliminar el producto para poder continuar. RECUERDA: un documento electrónico solo puede contener productos de una única sucursal.';
					return $resp;
				}
			}

			//hay que recordar que en las boletas de crédito y débito solo se admiten operaciones onerosas como gravadas, exoneradas, inafectas, exportación
			if($id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08') {
				//tipos de afectación de igv  no válidos
				$array_ids_igv_validos = array(10, 20, 30, 40);
				if(!in_array($tipoafectacionigv->id_tipoafectacionigv, $array_ids_igv_validos)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Recuerda que en las notas de crédito y débito solo se aceptan productos con operaciones oneraosas: Gravadas, Inafectas, Exoneradas, y Exportación';
					return $resp;
				}
			}

			if(!isset($item->factor_igv_sunat) || empty($item->factor_igv_sunat)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Recarga la Página y Vuelve a procesar el documento.';
				return $resp;
			}

			//validación para que no ingresen comprobantes donde el item tenga como precio cero
			if($id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '09') {
				if($tipoafectacionigv->id_tipoafectacionigv == 10) {
					$igv_precio = round(floatval($item->precio)*floatval($item->factor_igv_sunat), 2) + 0;
					if($igv_precio < 0) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El monto del IGV para el item N° '.$n.' tiene como valor cero, no podemos continuar porque una operación gravada no puede tener como IGV igual a Cero., Debes ingresar un precio mayor!';
						return $resp;		
					}
				}
			}
 
			$icbper_det = 0;
			if($tipoafectacionigv->id_tipoafectacionigv == 7152) {
				$icbper_det = $item->cantidad*0.10;
			}

			if(floatval($item->cantidad) <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La cantidad vendida para el item N° '.$n.' tiene como valor cero, no podemos continuar pues la cantidad en cada uno de los items del documento electrónico debe ser mayor a cero!';
				return $resp;	
			}

			if($id_tipodoc_electronico != '77') {
				//el precio para las notas de venta puede ser cero, porque se utilizarían para traslados entre locales
				if(floatval($item->precio) <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El precio para el item N° '.$n.' tiene como valor cero, no podemos continuar pues el precio en cada uno de los items del documento electrónico debe ser mayor a cero!';
					return $resp;	
				}

				if(floatval($item->subtotal) <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El subtotal para el item N° '.$n.' tiene como valor cero, no podemos continuar pues el subtotal en cada uno de los items del documento electrónico debe ser mayor a cero!';
					return $resp;	
				}
			}

			//validamos si la factura es de exportación, entonces hay que validar que sus items también sean de exportación.
			$id_codigotipooperacion = isset($datapost['tipo_operacion_docelectronico'])?$datapost['tipo_operacion_docelectronico']:'0101';
			if($id_tipodoc_electronico == '01') { //Está modificando una factura
				if($id_codigotipooperacion == '0200' || $id_codigotipooperacion == '0201') {
					if($tipoafectacionigv->id_tipoafectacionigv != '40') {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Si intentas hacer una factura de Exportación, entonces los items también deben tener la Afectación IGV: EXPORTACIÓN. Encontramos que el item N° '.$n.' tiene el código de Exportación '.$tipoafectacionigv->id_tipoafectacionigv.' ('.$tipoafectacionigv->descripcion.').';
						return $resp;
					}
				}
			}

			if($venta_debajo_precio_minimo == 'no') {
				$precio_ingresado_usuario = floatval($item->precio) + 0;
				if($precio_ingresado_usuario < $precio_minimo_producto) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El producto '.$producto->nombre.' ('.$unidadmedida->nombre.') no se puede vender con un precio menor a: '.$precio_minimo_producto.'. <strong class="text-danger">Usted no tiene permiso para vender productos por debajo del precio mínimo';
					return $resp;
				}
			}

			$lista[] = array(
				"ITEM_DET"          	 	=> $n,
				"UNIDAD_MEDIDA_ID_DET"   	=> $unidadmedida->idunidad,
				"UNIDAD_MEDIDA_DET"      	=> $unidadmedida->codigo,
				"PRECIO_TIPO_CODIGO"     	=> "01", //Precio unitario (incluye el IGV) - catálogo N° 16
				"COD_TIPO_OPERACION_DET" 	=> $tipoafectacionigv->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
				"TEXT_TIPO_OPERACION_DET"	=> $tipoafectacionigv->descripcion, //text_tipoafectacionigv - CATALOGO No. 07
				"CANTIDAD_DET"           	=> floatval($item->cantidad) + 0,
				"PRECIO_DET"             	=> floatval($item->precio) + 0,
				"IGV_DET"                	=> $item->igv,
				"FACTOR_IGV"				=> $item->factor_igv_sunat,
				"ISC_DET"				 	=> "0",
				//"ICBPER_DET"				=> $icbper_det,
				"IMPORTE_DET"            	=> $item->subtotal,
				"PRECIO_SIN_IGV_DET"  	 	=> round(floatval($item->subtotal)/floatval($item->cantidad), $num_decimales) + 0,
				"CODIGO_DET"             	=> $producto->idproducto,
				"CODIGO_PRODUCTO"		 	=> $producto->codigo,
				"DESCRIPCION_DET"   	 	=> $item->descripcion,
				"IDPRODUCTO_DET"		 	=> $producto->idproducto,
				"ICBPER_DET"             	=> floatval($item->subtotal_icbper),
				"TIPO_UNIDAD"				=> $tipo_unidad_medida,
				"ID_PRESENTACION"			=> $id_presentacion_producto
			);
		}
		
		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista;
		return $resp;
	}

	public function get_detalle_documento_guardado($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		$lista_items = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		
		foreach($lista_items as $item) {
			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));

			$lista[] = array(
				"ITEM_DET"          	 => $item->item,
				"UNIDAD_MEDIDA_ID_DET"   => $item->id_unidad_medida,
				"UNIDAD_MEDIDA_DET"      => $item->unidad_medida,
				"UNIDAD_MEDIDA_NOMBRE"	 => $unidad_medida_sunat->nombre,
				"PRECIO_TIPO_CODIGO"     => $item->id_codigoprecio, //Precio unitario (incluye el IGV) - catálogo N° 16
				"COD_TIPO_OPERACION_DET" => $item->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
				"TEXT_TIPO_OPERACION_DET" => empty($tipoafectacionigv->descripcion)?null:$tipoafectacionigv->descripcion, //text_tipoafectacionigv - CATALOGO No. 07
				"CANTIDAD_DET"           => $item->cantidad,
				"PRECIO_DET"             => $item->precio + 0,
				"IGV_DET"                => $item->igv,
				"ISC_DET"				 => $item->isc,
				"ICBPER_DET"			 => $item->icbper,
				"IMPORTE_DET"            => $item->sub_total,
				"PRECIO_SIN_IGV_DET"  	 => $item->precio_sin_igv + 0,
				"CODIGO_DET"             => $item->id_producto,
				"CODIGO_PRODUCTO"		 => $item->codigo_producto,
				"DESCRIPCION_DET"   	 => $item->descripcion,
				"IDPRODUCTO_DET"		 => $item->id_producto,
				"PESO_DET"				 => $item->peso,
				"FACTOR_IGV"			 => $item->factor_igv,

				"TIPO_UNIDAD"				=> !isset($item->tipo_unidad)?'UND':$item->tipo_unidad,
				"ID_PRESENTACION"			=> !isset($item->id_presentacion)?'UND':$item->id_presentacion
			);
		}

		if(!isset($lista)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Detalle';
			$resp['mensaje'] = 'El documento no tiene items';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista;
		return $resp;
	}

	public function get_detalle_documento_no_oficial($id_contribuyente, $id_tipodocumento, $numero_comprobante, $modalidad) {
		$lista_items = DetalleDocnooficial::find(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $modalidad)));
		
		foreach($lista_items as $item) {
			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));

			$lista[] = array(
				"ITEM_DET"          	 => $item->item,
				"UNIDAD_MEDIDA_ID_DET"   => $item->id_unidad_medida,
				"UNIDAD_MEDIDA_DET"      => $item->unidad_medida,
				"UNIDAD_MEDIDA_NOMBRE"	 => $unidad_medida_sunat->nombre,
				"PRECIO_TIPO_CODIGO"     => $item->id_codigoprecio, //Precio unitario (incluye el IGV) - catálogo N° 16
				"COD_TIPO_OPERACION_DET" => $item->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
				"TEXT_TIPO_OPERACION_DET" => $tipoafectacionigv->descripcion, //text_tipoafectacionigv - CATALOGO No. 07
				"CANTIDAD_DET"           => $item->cantidad,
				"PRECIO_DET"             => $item->precio + 0,
				"ICBPER_DET"			 => $item->icbper,
				"IGV_DET"                => $item->igv,
				"IMPORTE_DET"            => $item->sub_total,
				"PRECIO_SIN_IGV_DET"  	 => $item->precio_sin_igv + 0,
				"CODIGO_DET"             => $item->id_producto,
				"CODIGO_PRODUCTO"		 => $item->codigo_producto,
				"DESCRIPCION_DET"   	 => $item->descripcion,
				"IDPRODUCTO_DET"		 => $item->id_producto, 

				"TIPO_UNIDAD"				=> !isset($item->tipo_unidad)?'UND':$item->tipo_unidad,
				"ID_PRESENTACION"			=> !isset($item->id_presentacion)?'UND':$item->id_presentacion
			);
		}

		if(!isset($lista)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Detalle';
			$resp['mensaje'] = 'El documento no tiene items';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['detalle'] = $lista;
		return $resp;
	}


	public function valida_datos_iniciales($usuario, $datapost) {
		
		$idusuario = $usuario->idusuario;
		$herramientas = new HerramientasController;
		$gestion_usuarios = new GestionuserController;
		$apisunat = new ApisunatController;

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el contribuyente al que pertenece el usuario!';
			return $resp;
		}

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $datapost['idcliente'])));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El cliente seleccionado no existe!';
			return $resp;
		}

		$idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
		$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
		if(!$sucursal) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes seleccionar una sucursal válida';
			return $resp;
		}
		
		$factor_igv_sunat = isset($datapost['select_igv_sunat'])?intval($datapost['select_igv_sunat']):$this->factor_igv_sunat*100;
		if(intval($factor_igv_sunat) == 18) {
			$factor_igv_sunat = 0.18;
		} else if(intval($factor_igv_sunat) == 10) {
			$factor_igv_sunat = 0.10;
		} else {
			$factor_igv_sunat = 0.18;
		}
		$datapost['factor_igv_sunat'] = $factor_igv_sunat;
		$this->factor_igv_sunat = $factor_igv_sunat;

		$id_codigomoneda = $datapost['codmoneda_comprobante'];
		$moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $id_codigomoneda)));
		if(!$moneda) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes seleccionar el código de moneda válido!';
			return $resp;
		}

		if(!empty($data['nro_placa_vehiculo'])) {
            if (strlen($data['transporte_nro_placa']) > 8) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El número de Placa no puede tener más de 8 caracteres y debe contener solo letras y números!';
				return $resp;
			}

			if(!preg_match('/^([A-Za-z0-9]+-?[A-Za-z0-9]+)$/', $data['transporte_nro_placa'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El número de placa no debe contener caractéres especiales.';
				return $resp;
			}
        }

		$tipo_cambio_comprobante = !isset($datapost['tipo_cambio_comprobante'])?0:round(floatval($datapost['tipo_cambio_comprobante']), 3);
		if($id_codigomoneda == 'USD') {
			if($tipo_cambio_comprobante <= 1) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar el tipo de cambio (venta) a la fecha de la emisión de la factura. (El tipo de Cambio debe ser mayor a 1)';
				return $resp;		
			}
		}
		
		$id_codigotipooperacion = isset($datapost['tipo_operacion_docelectronico'])?$datapost['tipo_operacion_docelectronico']:'0101';
		$codigotipooperacion = SunatTipooperacion::findFirst(array("id_codigotipooperacion = :id_codigotipooperacion:", 'bind' => array('id_codigotipooperacion' => $id_codigotipooperacion)));
		if(!$codigotipooperacion) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El Tipo de Operación para el Documento Electrónico no Existe!, por favor consulte con SOPORTE!';
			return $resp;
		}
		
		$id_tipodoc_electronico = !isset($datapost['select_tipo_doc_electronico'])?'':$datapost['select_tipo_doc_electronico'];
		$documento_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $id_tipodoc_electronico)));
		if(!$documento_electronico) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes seleccionar un documento electrónico válido';
			return $resp;
		}

		$aplica_retencion = 'no';
		if(isset($datapost['select_aplica_retencion'])) {
			if(!empty($datapost['select_aplica_retencion'])) {
				if($datapost['select_aplica_retencion'] == 'si') {
					$aplica_retencion = 'si';
				}
			}
		}
		$datapost['select_aplica_retencion'] = $aplica_retencion;
		
		//validar que en pruebas no se creen más de 10 comprobantes por tipo de comprobante.
		if($contribuyente->tipo_envio_sunat == 'prueba') {
			$usuarios_sistema = Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
			$es_reseller = false;
			foreach($usuarios_sistema as $user_sistema) {
				if($user_sistema->id_rol == 5) {
					$es_reseller = true;
					break;
				}
			}

			if(!$es_reseller) {
				if($usuario->id_contribuyente != 1) {
					if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 5) {
						$num_docs_creados = DocElectronico::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

						if($id_tipodoc_electronico == '01') {
							$nombre_doc_creado = 'FACTURAS';
						} else if($id_tipodoc_electronico == '03') {
							$nombre_doc_creado = 'BOLETAS';
						} else if($id_tipodoc_electronico == '07') {
							$nombre_doc_creado = 'NOTAS DE CRÉDITO';
						} else if($id_tipodoc_electronico == '08') {
							$nombre_doc_creado = 'NOTAS DE DÉBITO';
						} else if($id_tipodoc_electronico == '77') {
							$nombre_doc_creado = 'NOTAS DE VENTA';
						} else if($id_tipodoc_electronico == '88') {
							$nombre_doc_creado = 'COTIZACIONES';
						} else {
							$nombre_doc_creado = 'DOCUMENTOS';
						}
			
						if(count($num_docs_creados) > 100) {
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'En el ambiente de pruebas solo se permiten crear hasta 10 '.$nombre_doc_creado;
							return $resp;
						}
					}
				}
			}
		}

		
		
		$dg_id_tipodoc_electronico = !in_array($datapost['doc_guardado_id_tipodoc_electronico'], array('01', '03', '07', '08', '88'))?'':$datapost['doc_guardado_id_tipodoc_electronico'];

		$nombre_cpe_editado = '';
		if($dg_id_tipodoc_electronico == '01') {
			$nombre_cpe_editado = 'FACTURA';
		} else if($dg_id_tipodoc_electronico == '03') {
			$nombre_cpe_editado = 'BOLETA';
		} else if($dg_id_tipodoc_electronico == '07') {
			$nombre_cpe_editado = 'NOTA DE CRÉDITO';
		} else if($dg_id_tipodoc_electronico == '08') {
			$nombre_cpe_editado = 'NOTA DE DÉBITO';
		} else if($dg_id_tipodoc_electronico == '88') {
			$nombre_cpe_editado = 'COTIZACIÓN';
		} else if($dg_id_tipodoc_electronico == '77') {
			$nombre_cpe_editado = 'NOTA DE VENTA';
		} else {
			$nombre_cpe_editado = 'DOCUMENTO DESCONOCIDO';
		}

		$nombre_cpe_destino = '';
		if($id_tipodoc_electronico == '01') {
			$nombre_cpe_destino = 'FACTURA';
		} else if($id_tipodoc_electronico == '03') {
			$nombre_cpe_destino = 'BOLETA';
		} else if($id_tipodoc_electronico == '07') {
			$nombre_cpe_destino = 'NOTA DE CRÉDITO';
		} else if($id_tipodoc_electronico == '08') {
			$nombre_cpe_destino = 'NOTA DE DÉBITO';
		} else if($id_tipodoc_electronico == '88') {
			$nombre_cpe_destino = 'COTIZACIÓN';
		} else if($id_tipodoc_electronico == '77') {
			$nombre_cpe_destino = 'NOTA DE VENTA';
		} else {
			$nombre_cpe_destino = 'DOCUMENTO DESCONOCIDO';
		}

		$dg_serie_comprobante =  (strlen($datapost['doc_guardado_serie_comprobante']) > 4)?'':$datapost['doc_guardado_serie_comprobante'];
		$dg_numero_comprobante = intval($datapost['doc_guardado_numero_comprobante']) + 0;
		if(in_array($dg_id_tipodoc_electronico, array('01', '03', '07', '08'))) {
			if($dg_id_tipodoc_electronico != '' && $dg_serie_comprobante != '' && $dg_numero_comprobante > 0) {
				$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $dg_id_tipodoc_electronico, 'serie_comprobante' => $dg_serie_comprobante, 'numero_comprobante' => $dg_numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
				if(!$documento) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El documento que intentas modificar no existe!';
					return $resp;
				}
				
				if($id_tipodoc_electronico != $dg_id_tipodoc_electronico) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = "El documento que estás editando originalmente es una $nombre_cpe_editado y tu estás intentando cambiar la $nombre_cpe_editado a una $nombre_cpe_destino, y esta acción no se permite, ya que haz seleccionado la opción de Editar!!";
					return $resp;
				}
	
				$id_tipodoc_electronico = $documento->id_tipodoc_electronico;
			}
		} else if(in_array($dg_id_tipodoc_electronico, array('88'))) {
			if($dg_id_tipodoc_electronico != '' && $dg_numero_comprobante > 0) {
				$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => $dg_id_tipodoc_electronico, 'numero_comprobante' => $dg_numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));

				if(!$documento) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La cotización que intentas modificar no existe!';
					return $resp;
				}

				if($id_tipodoc_electronico != $dg_id_tipodoc_electronico) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = "El documento que estás editando originalmente es una $nombre_cpe_editado y tu estás intentando cambiar la $nombre_cpe_editado a una $nombre_cpe_destino, y esta acción no se permite, ya que haz seleccionado la opción de Editar!!";
					return $resp;
				}
	
				$id_tipodoc_electronico = $documento->id_tipodocumento;
			}
		} else {

		}
		
		$monto_detraccion = round(floatval($datapost['monto_detraccion']), 2);
		if($id_codigomoneda == 'USD') {
			//el monto de la detracción está llegando en soles, se necesita que toda detracción se guarde en la base de datos en dólares.
			//luego se mostrará en soles utilizando el tipo de cambio y redondeo.
			$monto_detraccion = round(floatval($datapost['monto_detraccion'])/$tipo_cambio_comprobante, 2);
			$datapost['monto_detraccion'] = $monto_detraccion;
		}

		//validando si se ha elegido el documento electrónico de identidad correcto!
		if($id_tipodoc_electronico == '01') { //FACTURA
			if($id_codigotipooperacion == '0200' || $id_codigotipooperacion == '0201') {
				if($cliente->id_tipodocidentidad != '0') {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Tipo Documento de Identidad Seleccionado no es Válido para una '.$documento_electronico->descripcion.' de Exportación.';
					return $resp;
				}
			} else {
				if($cliente->id_tipodocidentidad != '6') {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El Tipo Doc.Ident. Seleccionado no es Válido para una '.$documento_electronico->descripcion;
					return $resp;
				}
			}
		} else if($id_tipodoc_electronico == '03') { //BOLETA
			//no se puede restringir el uso de RUC en boletas porque si debería poderse colocar RUC en boletas.
			
		} else if($id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08') { //NOTA DE CRÉDITO (07) y NOTA DE DEBITO (08)
			$id_tipodoc_electronico_modifica = !isset($datapost['tipo_docelectronico_modificar'])?'':$datapost['tipo_docelectronico_modificar'];
			if($id_tipodoc_electronico_modifica != '01' && $id_tipodoc_electronico_modifica != '03') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Recuerda que una nota de crédito puede modificar solamente una boleta o factura';
				return $resp;
			}

			if($id_tipodoc_electronico_modifica == '01') { //Está modificando una factura
				if($id_codigotipooperacion == '0200' || $id_codigotipooperacion == '0201') {
					if($cliente->id_tipodocidentidad != '6' && $cliente->id_tipodocidentidad != '0') {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El Documento Seleccionado no es Válido para una '.$documento_electronico->descripcion.'..';
						return $resp;
					}
				} else {
					if($cliente->id_tipodocidentidad != '6') {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El Documento Seleccionado no es Válido para una '.$documento_electronico->descripcion.'.';
						return $resp;
					}
				}
			}

			if ($id_tipodoc_electronico_modifica == '03') { //Está modificando una boleta
				//no se puede restringir el uso de RUC en boletas porque si debería poderse colocar RUC en boletas.
			}

			if($id_tipodoc_electronico == '07' && $id_tipodoc_electronico_modifica == '03') {
				//Cabe señalar que para el caso de boletas de venta, no es posible emitir Notas de crédito con
				//motivos 04 (descuento global) 05 (descuento por ítem) 08 (bonificación).
				//Fuente: https://goo.gl/rpgsUC => Página 40  
				if($datapost['id_motivo_nota_credito'] == '04' || $datapost['id_motivo_nota_credito'] == '05' || $datapost['id_motivo_nota_credito'] == '08' || $datapost['id_motivo_nota_credito'] == '10') {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El motivo seleccionado no es válido para emitir una nota de crédito para Boleta!';
					return $resp;
				}
			}

			$total_descuento = !isset($datapost['txt_descuento_comprobante'])?0:round(floatval($datapost['txt_descuento_comprobante']), 2);
			if($total_descuento > 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'SUNAT no permite la emisión de notas de crédito con descuento global!';
				return $resp;
			}
			
		} else if ($id_tipodoc_electronico == '77'){ 
			//Aquí podemos agregar alguna condición para la nota de venta

		} else if ($id_tipodoc_electronico == '88'){ 
			//Aquí podemos agregar alguna condición para la nota de venta

		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Documento electrónico aún no es procesable!...';
			return $resp;
		}
		
		if(in_array($dg_id_tipodoc_electronico, array('01', '03', '07', '08'))) {
			if($dg_id_tipodoc_electronico != '' && $dg_serie_comprobante != '' && $dg_numero_comprobante > 0) {
				$data['id_tipodoc_electronico'] = $documento->id_tipodoc_electronico;
				$data['idsucursal'] = $documento->id_sucursal;
				$data['idcliente'] = $datapost['idcliente'];
				$data['fecha_documento'] = $documento->fecha_registro;
				$data['fecha_vence_documento'] = $documento->fecha_vto_comprobante;
				$data['numero_comprobante'] = $documento->numero_comprobante;
				$data['serie_comprobante'] =  $documento->serie_comprobante;
				$data['modo_edicion'] = 'si';

				$resp['datapost'] = $datapost;
				$resp['respuesta'] = 'ok';
				$resp['cabecera'] = $data;
				return $resp;
			}
		} else if(in_array($dg_id_tipodoc_electronico, array('88'))) {
			if($dg_id_tipodoc_electronico != '' && $dg_numero_comprobante > 0) {
				$data['id_tipodoc_electronico'] = $documento->id_tipodocumento;
				$data['idsucursal'] = $documento->id_sucursal;
				$data['idcliente'] = $datapost['idcliente'];
				$data['fecha_documento'] = $documento->fecha_registro;
				$data['fecha_vence_documento'] = $documento->fecha_registro;
				$data['numero_comprobante'] = $documento->numero_comprobante;
				$data['serie_comprobante'] =  '';
				$data['modo_edicion'] = 'si';
	
				$resp['datapost'] = $datapost;
				$resp['respuesta'] = 'ok';
				$resp['cabecera'] = $data;
				return $resp;
			}
		} else {

		}

		$array_fecha_documento = explode('/',!isset($datapost['fecha_comprobante'])?date('d/m/Y'):$datapost['fecha_comprobante']);
		if(!isset($array_fecha_documento[2]) || !isset($array_fecha_documento[1]) || !isset($array_fecha_documento[0])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha asignada al documento no es válida!';
			return $resp;
		}
		
		$fecha_documento = $array_fecha_documento[2].'-'.$array_fecha_documento[1].'-'.$array_fecha_documento[0];
		if (!(DateTime::createFromFormat('Y-m-d', $fecha_documento) !== FALSE)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha asignada al documento no es válida!';
			return $resp;
		}
		
		$fecha_actual = date('Y-m-d', strtotime('today'));
		$resp_diferencia_dias = $herramientas->comparar_fechas($fecha_actual, $fecha_documento);
		$diferencia_dias = $resp_diferencia_dias['diferencia_primera_segunda'];
		
		if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08') {
			if($diferencia_dias > 3) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_factura';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La Fecha Asignada al Documento No Debe Ser Mayor a 3 Días atrás.';
				return $resp;
			}
		} else if($id_tipodoc_electronico == '03') {
			if($diferencia_dias > 6) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_factura';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La Fecha Asignada al Documento No Debe Ser Mayor a 6 Días atrás.';
				return $resp;
			}
		} else {
			if($diferencia_dias > 30) {
				$resp['respuesta'] = 'error';
				$resp['codigo'] = 'cabecera_factura';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'La Fecha Asignada al Documento No Debe Ser Mayor a 30 Días atrás.';
				return $resp;
			}
		}

		if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
			if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_cambio_fechaemision') != 'acceso_total') {
				if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_cambio_fechaemision') != 'permitir') {
					if($diferencia_dias != 0) {
						$resp['respuesta'] = 'error';
						$resp['codigo'] = 'cabecera_factura';
						$resp['titulo'] = 'error';
						$resp['mensaje'] = 'Usted no tiene permitido generar comprobantes con fecha anterior o posterior. ';
						return $resp;
					}
				}
			}
		}
		
		$array_fecha_venc_documento = explode('/',!isset($datapost['fecha_vence_comprobante'])?date('d/m/Y'):$datapost['fecha_vence_comprobante']);
		if(!isset($array_fecha_venc_documento[2]) || !isset($array_fecha_venc_documento[1]) || !isset($array_fecha_venc_documento[0])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha asignada al documento no es válida!';
			return $resp;
		}

		$fecha_vence_documento = $array_fecha_venc_documento[2].'-'.$array_fecha_venc_documento[1].'-'.$array_fecha_venc_documento[0];
		if (!(DateTime::createFromFormat('Y-m-d', $fecha_vence_documento) !== FALSE)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha de vencimiento asignada al documento no es válida!';
			return $resp;
		}
		
		$resp_fecha = $herramientas->comparar_fechas($fecha_vence_documento, $fecha_documento);
		if($resp_fecha['diferencia_primera_segunda'] < 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'La fecha del documento: '.$fecha_documento.' no debe ser mayor a la fecha de vencimiento: '.$fecha_vence_documento.'!';
			return $resp;
		}

		//data retención
		$factor_retencion = isset($datapost['porcentaje_retencion'])?(floatval($datapost['porcentaje_retencion'])/100):0;
		$base_imponible_retencion = isset($datapost['base_imponible_retencion'])?(floatval($datapost['base_imponible_retencion'])):0;
		$monto_retencion = isset($datapost['monto_retencion'])?(floatval($datapost['monto_retencion'])):0;
		
		//verificamos el tipo de pago y la condicion de pago
		$tipo_venta = !isset($datapost['opcion_tipo_venta'])?'contado':(($datapost['opcion_tipo_venta'] == 'contado')?'contado':'credito');
		
		$monto_adeudado = !isset($datapost['txt_monto_adeudado'])?0: floatval($datapost['txt_monto_adeudado']) + 0;
		$monto_pagado = !isset($datapost['txt_pago_parcial'])?0: floatval($datapost['txt_pago_parcial']) + 0;
		$total_comprobante = !isset($datapost['txt_total_comprobante'])?0: floatval($datapost['txt_total_comprobante']) + 0;
		$id_condicionpago = !isset($datapost['condicionpago_comprobante'])?0:intval($datapost['condicionpago_comprobante']) + 0;
		$id_venta_al_credito = 0;
		$id_condicionpago_montopagado = 0; //el id de la condición en caso haya un monto pagado junto a la venta al crédito.

		if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
			if($aplica_retencion == 'si') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se permite la creación de una factura de detracción y retención al mismo tiempo.';
				return $resp;
			}
		}
		
		if($tipo_venta == 'credito') {
			if($monto_adeudado <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Si el comprobante electrónico se pagará al crédito, entonces el monto adeudado no puede ser igual o menor a cero.';
				return $resp;
			}
			
			if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
				$total_comprobante_detraccion = round($total_comprobante - $monto_detraccion, 2);
				if(round($monto_adeudado, 2) > $total_comprobante_detraccion) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El monto adeudado no debería ser mayor al monto a pagar... RECORDAR: Que la detracción se debe pagar si o si en la cuenta de detracciones por tanto no se considera al crédito, sino se considerará como un monto pagado con transferencia.';
					return $resp;
				}
			} else if($aplica_retencion == 'si') {
				$total_comprobante_retencion = round($total_comprobante - $monto_retencion, 2);
				if(round($monto_adeudado, 2) > $total_comprobante_retencion) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El monto adeudado no debería ser mayor al monto a pagar... RECORDAR: Que la retención se debe pagar si o si , por tanto no se considera al crédito.';
					return $resp;
				}
			} else {
				if($monto_adeudado > $total_comprobante) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El monto adeudado no debería ser mayor al monto total del comprobante.';
					return $resp;
				}
			}
			
			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			if(!isset($array_fecha_pagopendiente[2]) || !isset($array_fecha_pagopendiente[1]) || !isset($array_fecha_pagopendiente[0])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!.';
				return $resp;
			}

			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
				return $resp;
			}

			/*
			$resp_fecha_2 = $herramientas->comparar_fechas($fecha_pagopendiente, date('Y-m-d'));
			if($resp_fecha_2['diferencia_primera_segunda'] < 1) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto adeudado debe ser mayor a la fecha actual, por favor verifique!';
				return $resp;
			}
			*/

			$condicion_venta_credito = Condiciondepago::findFirst(array("tipo = 'credito' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
			
			if(!$condicion_venta_credito) {
				$condicion_venta_credito = new Condiciondepago();
				$condicion_venta_credito->id_contribuyente = $contribuyente->id_contribuyente;
				$condicion_venta_credito->tipo = 'credito';
				$condicion_venta_credito->condicionpago = 'Venta al Crédito';
				$condicion_venta_credito->estado = 'activo';

				try {
					if(!$condicion_venta_credito->save()) {
						$msg = '';
						foreach ($condicion_venta_credito->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Problemas al guardar la condición al crédito';
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Problemas al guardar la condición al crédito. '.$e->getMessage();
					return $resp;
				}
			}
			
			$id_venta_al_credito = $condicion_venta_credito->id_condicionpago;

			if($total_comprobante - $monto_adeudado > 0) {
				if($id_condicionpago <= 0) {
					$condicion_pago_parcial = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and tipo = 'contado' and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
					if($condicion_pago_parcial) {
						$id_condicionpago = $condicion_pago_parcial->id_condicionpago;
					}
				}

				$condicion_pago_parcial = Condiciondepago::findFirst(array("id_condicionpago = :id_condicionpago: and id_contribuyente = :id_contribuyente: and tipo <> 'credito' and estado = 'activo'", 'bind' => array('id_condicionpago' => $id_condicionpago, 'id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
				if(!$condicion_pago_parcial) {
					$condicion_pago_parcial = new Condiciondepago();
					$condicion_pago_parcial->id_contribuyente = $contribuyente->id_contribuyente;
					$condicion_pago_parcial->tipo = 'contado';
					$condicion_pago_parcial->condicionpago = 'Pago en Efectivo';
					$condicion_pago_parcial->estado = 'activo';

					try {
						if(!$condicion_pago_parcial->save()) {
							$msg = '';
							foreach ($condicion_pago_parcial->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = 'No tienes permitido crear ventas al crédito!';
							return $resp;
						}
					} catch (Exception $e) {
                		$this->saveLogger($e);
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Problemas al crear la condición al crédito! '.$e->getMessage();
						return $resp;
					}
				}

				$id_condicionpago = $condicion_pago_parcial->id_condicionpago;
			}
			
			//validamos las cuotas
			//aquí la idea es que todo debe estar en el array data_cuotas, de tal manera que aunque sea una sola cuota, también se le ubique en el array.
			//si existe el array data_cuotas significa que tenemos dos o más cuotas.
			//si no existe, significa que únicamente tenemos una cuota.

			//*Ya no se debe comprobar la cuotas con la detracción, porque a continuación se validará que el monto adeudado sea igual a la 
			//sumatoria de los montos de cada cuota.

			if(isset($datapost['data_cuotas'])) {
				$resp_validacion_cuotas = $this->validar_cuotas($datapost, $monto_adeudado);
				if($resp_validacion_cuotas['respuesta'] == 'error') {
					return $resp_validacion_cuotas;
				}

				$datapost['data_cuotas'] = $resp_validacion_cuotas['data_cuotas'];
			} else {
				$datapost['data_cuotas'] = array(
					'num_cuotas'			=> 1,
					'monto_total_cuotas' 	=> $monto_adeudado,
					'cuotas'				=> array(
													array(
														'id_cuota'	=> 1,
														'monto_cuota'	=> $monto_adeudado,
														'vencimiento_cuota'	=> $fecha_pagopendiente
													)
					)
				);
			}		
		}
		
		$idcuenta_banco_deposito = null;
		$fecha_deposito_transferencia = null;
		if($id_condicionpago > 0) {
			$condiciondepago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago: and tipo <> 'credito'", "bind" => array('id_contribuyente' => $usuario->id_contribuyente, 'id_condicionpago' => $id_condicionpago)));
			if(!$condiciondepago) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['codigo'] = 'documentoelectronico_valida_datos_iniciales';
				$resp['mensaje'] = 'La condición de pago seleccionada no es válida! => '.$id_condicionpago;
				return $resp;
			}

			if($condiciondepago->tipo == 'transferencia') {
				if(intval($datapost['select_cuenta_banco_deposito']) > 0) {
					$cuenta_banco_transferencia = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => intval($datapost['select_cuenta_banco_deposito']))));
					if(!$cuenta_banco_transferencia) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'La cuenta de banco de transferencia no existe, debes seleccionar una cuenta de banco en donde se ha depositado el dinero!!';
						return $resp;
					}

					$idcuenta_banco_deposito = intval($datapost['select_cuenta_banco_deposito']);
				}

				$array_fecha_deposito = explode('/',!isset($datapost['fecha_deposito'])?date('d/m/Y'):$datapost['fecha_deposito']);
				$fecha_deposito_transferencia = $array_fecha_deposito[2].'-'.$array_fecha_deposito[1].'-'.$array_fecha_deposito[0];
				if (!(DateTime::createFromFormat('Y-m-d', $fecha_deposito_transferencia) !== FALSE)) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
					return $resp;
				}
			}
		}

		//Validamos que exista el documento de referencia electrónica
		$select_doc_referencia_bd = empty($datapost['id_guia_remision_electronica'])?'':$datapost['id_guia_remision_electronica'];
		if(!empty($select_doc_referencia_bd)) {
			$array_doc_referencia_clie = explode(',', $select_doc_referencia_bd);
			$id_tipodoc_referencia_clie = '09';
			$serie_doc_referencia_clie = $array_doc_referencia_clie[1];
			$num_doc_referencia_clie = $array_doc_referencia_clie[2];

			$documento_ref_clie = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and idcliente = :idcliente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_referencia_clie, 'serie_comprobante' => $serie_doc_referencia_clie, 'numero_comprobante' => $num_doc_referencia_clie,'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'idcliente' =>  $datapost['idcliente'])));
			if(!$documento_ref_clie) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento de referencia seleccionado no existe!';
				return $resp;
			}
		}

		//validamos que el documento de referencia (guía de remisión) no electrónica tenga el formato correcto.
		$guias_manuales = empty($datapost['guia_remision_manual'])?'':trim($datapost['guia_remision_manual']);
		if(!empty($guias_manuales)) {
			$lista_guias = array_map('trim', explode(',', $guias_manuales));
			foreach($lista_guias as $guia_remision_manual) {
				if(!empty($guia_remision_manual)) {
					$array_guia_remision_manual = explode('-', $guia_remision_manual);
					$serie_doc_manual = !isset($array_guia_remision_manual[0])?'':trim($array_guia_remision_manual[0]);
					$num_doc_manual = !isset($array_guia_remision_manual[1])?'':trim($array_guia_remision_manual[1]);
					if(strlen($serie_doc_manual) != 4) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'La guía de remisión MANUAL no es válida, recuerda que la serie debe tener 4 caracteres.!';
						return $resp;
					}
		
					if(intval($num_doc_manual) <= 0) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'El número de la guía de remisión manual no es válido!';
						return $resp;
					}
				}
			}
		}

		//validamos el monto del comprobante y retención
		if($aplica_retencion == 'si') {
			if($id_codigomoneda != 'USD') {
				if($total_comprobante < 10) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El monto es menor a S/. 10 soles, no aplica retención.';
					return $resp;
				}
			} else {
				if(floatval($tipo_cambio_comprobante*$total_comprobante) < 400) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = "El monto es menor a S/. 400 soles (USD $total_comprobante*$tipo_cambio_comprobante), no aplica retención.";
					return $resp;
				}
			}

			if($id_tipodoc_electronico != '01') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Sólamente las FACTURAS pueden estar afectas a retención.!';
				return $resp;
			}
			
			if($factor_retencion != $contribuyente->regimen_retencion) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'No se permite modificar el porcentaje de retención! '.$factor_retencion.' - '.$contribuyente->regimen_retencion;
				return $resp;
			}

			if(empty($base_imponible_retencion)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar una base imponible para la retención!';
				return $resp;
			}

			if($base_imponible_retencion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar una base imponible para la retención!';
				return $resp;
			}
			
			if(empty($monto_retencion)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar un monto para la retención!';
				return $resp;
			}

			if($monto_retencion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar un monto para la retención!';
				return $resp;
			}
		}
		
		//validamos si se trata de un documento de detracción
		$nro_cuenta_detraccion = '';
		if($id_codigotipooperacion == '1001' || $id_codigotipooperacion == '1002' || $id_codigotipooperacion == '1003' || $id_codigotipooperacion == '1004') {
			
			//validamos que la detracción únicamente se aplique a FACTURAS y sus NOTAS.
			if($id_tipodoc_electronico != '01' && $id_tipodoc_electronico != '07' && $id_tipodoc_electronico != '08') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Las Operaciones de Detracción únicamente son válida para FACTURAS';
				return $resp;
			} else {
				if($id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08') {
					if($id_tipodoc_electronico_modifica != '01') {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Las Operaciones de Detracción únicamente son válida para NOTAS DE CRÉDITO que modifican FACTURAS';
					return $resp;
					}
				}
			}

			if($id_codigomoneda != 'USD') {
				if($total_comprobante < 400) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El monto es menor a S/. 400 soles, no aplica detracción.';
					return $resp;
				}
			}

			$condicion_venta_transferencia = Condiciondepago::findFirst(array("tipo = 'transferencia' and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente), "order" => "id_condicionpago ASC"));
			
			if(!$condicion_venta_transferencia) {
				$condicion_venta_transferencia = new Condiciondepago();
				$condicion_venta_transferencia->id_contribuyente = $contribuyente->id_contribuyente;
				$condicion_venta_transferencia->tipo = 'transferencia';
				$condicion_venta_transferencia->condicionpago = 'Transferencia Bancaria';
				$condicion_venta_transferencia->estado = 'activo';

				try {
					if(!$condicion_venta_transferencia->save()) {
						$msg = '';
						foreach ($condicion_venta_transferencia->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = 'Antes de Continuar Debes Registrar la Condición de Pago de tipo Transferencia!';
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Antes de Continuar Debes Registrar la Condición de Pago de tipo Transferencia! '.$e->getMessage();
					return $resp;
				}
			}
			
			//Operacion Sujeta a Detracción: Debe existir al menos un artículo sujeto a detracción. Si existe más de uno, el facturador tomara el mayor porcentaje por una interpretación conservadora Resolución 183-204 SUNAT/15.08.2004.
			$detraccion_tipo_pago = $datapost['detraccion_tipo_pago'];
			$detraccion_id_cuenta_banco = $datapost['detraccion_id_numero_cuenta'];
			$detraccion_codigo_bien = $datapost['detraccion_codigo_bien'];
			$porcentaje_detraccion = floatval($datapost['porcentaje_detraccion']);
			
			$texto_detraccion = $datapost['texto_detraccion'];
			
			$sunat_mediopago = SunatMediosdepago::findFirst(array("id_mediopago = :id_mediopago:", 'bind' => array('id_mediopago' => $detraccion_tipo_pago)));
			if(!$sunat_mediopago) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El medio de pago para la detracción no es válido!';
				return $resp;
			}

			$cuenta_banco = Cuentabanco::findFirst(array("id_cuentabanco = :id_cuentabanco:", 'bind' => array('id_cuentabanco' => $detraccion_id_cuenta_banco)));
			if(!$cuenta_banco) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La cuenta de banco de detracción no existe, debes seleccionar una cuenta de detracción válida!!';
				return $resp;
			}

			if(empty($cuenta_banco->nro_cuenta)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El número de la cuenta seleccionada está vacia!, debes ingresar un número para la cuenta de detracción...';
				return $resp;
			}

			$nro_cuenta_detraccion = $cuenta_banco->nro_cuenta;

			$sunat_codigo_bien = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $detraccion_codigo_bien)));
			if(!$sunat_codigo_bien) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El código para la detracción no es válido, debes seleccionar un código correcto!';
				return $resp;
			}

			if($porcentaje_detraccion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El porcentaje para la detracción debe ser mayor a cero!';
				return $resp;
			}

			if($monto_detraccion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El monto para la detracción debe ser mayor a cero!';
				return $resp;
			}

			if(empty($texto_detraccion)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes ingresar un texto para la detracción!';
				return $resp;
			}

			$detalle_documento = json_decode($datapost['detalle']);
			$tiene_detraccion = 'no';
			foreach($detalle_documento as $item_detalle) {
				$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item_detalle->idarticulo, 'id_contribuyente' => $usuario->id_contribuyente)));
				if(!$producto) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Producto';
					$resp['mensaje'] = 'El producto: '.$item_detalle->descripcion.', no existe en su base datos, no puede agregar productos que no estén registrados previamente!';
					return $resp;
				}

				if(!empty($producto->id_cod_detraccion)) {
					$detraccion_item = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $producto->id_cod_detraccion)));
					if($sunat_codigo_bien) {
						$tiene_detraccion = 'si';
						break;
					}
				}
			}
			
			$origen_data = isset($datapost['origen_data'])?$datapost['origen_data']:'sistema';
			if($tiene_detraccion != 'si' && $origen_data == 'sistema') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Producto';
				$resp['mensaje'] = 'En el documento por lo menos debe existir un producto o servicio afecto a detracción...';
				return $resp;
			}
		}
		
		//validamos si se trata de un documento de percepción
		if($id_codigotipooperacion == '2001') {
			$id_tipopercepcion = $datapost['tipo_percepcion'];
			$monto_base_percepcion = floatval($datapost['monto_base_percepcion']);
			$porcentaje_percepcion = floatval($datapost['porcentaje_percepcion']);
			$monto_percepcion = floatval($datapost['monto_percepcion']);

			$percepcion = SunatCodigotipopercepcion::findFirst(array("codigo = :codigo:", 'bind' => array('codigo' => $id_tipopercepcion)));
			if(!$percepcion) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El Tipo de Percepción no es Válido!';
				return $resp;
			}
			
			if($monto_base_percepcion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El monto base de la percepción debe ser mayor a cero!';
				return $resp;
			}

			if($porcentaje_percepcion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El porcentaje de la percepción debe ser mayor a cero!';
				return $resp;
			}

			if($monto_percepcion <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El moneto de la percepción debe ser mayor a cero!';
				return $resp;
			}
		}
		
		$data['id_tipodoc_electronico'] = $id_tipodoc_electronico;
		$data['idsucursal'] = $sucursal->idsucursal;
		$data['idcliente'] = $datapost['idcliente'];
		$data['fecha_documento'] = $fecha_documento;
		$data['fecha_vence_documento'] = $fecha_vence_documento;
		$data['modo_edicion'] = 'no';
		$data['nro_cuenta_detraccion'] = $nro_cuenta_detraccion;
		$data['descripcion_tipo_operacion_doc'] = $codigotipooperacion->descripcion;
		$data['id_venta_al_credito'] = $id_venta_al_credito;
		$data['txt_monto_adeudado'] = $monto_adeudado;
		$data['condicion_pago_parcial'] = $id_condicionpago;
		$data['idcuenta_banco_deposito'] = $idcuenta_banco_deposito;
		$data['fecha_deposito_transferencia'] = $fecha_deposito_transferencia;
		
		$resp['respuesta'] = 'ok';
		$resp['cabecera'] = $data;
		$resp['datapost'] = $datapost;
		return $resp;
	}

	public function crear_condiciondepago($id_contribuyente, $tipo = 'contado') {
		$condiciondepago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and tipo = '$tipo' and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if($condiciondepago) {
			$resp['respuesta'] = 'ok';
			$resp['id_condicionpago'] = $condiciondepago->id_condicionpago;
			return $resp;
		}

		$nombre_condicion_pago = 'Pago en Efectivo';
		if($tipo == 'contado') {
			$nombre_condicion_pago = 'Pago en Efectivo';
		} else if($tipo == 'credito') {
			$nombre_condicion_pago = 'Venta al Crédito';
		} else if($tipo == 'tarjeta_credito') {
			$nombre_condicion_pago = 'Tarjeta de Crédito';
		} else if($tipo == 'transferencia') {
			$nombre_condicion_pago = 'Transferencia Bancaria';
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El tipo de condición de pago no es válido!';
			return $resp;
		}

		$condiciondepago = new Condiciondepago();
		$condiciondepago->id_contribuyente = $id_contribuyente;
		$condiciondepago->tipo = $tipo;
		$condiciondepago->condicionpago = $nombre_condicion_pago;
		$condiciondepago->estado = 'activo';

		try {
			if(!$condiciondepago->save()) {
				$msg = '';
				foreach ($condiciondepago->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Problemas al guardar la condición de pago';
				return $resp;
			}
		} catch (Exception $e) {
			$this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Problemas al guardar la condición de pago. '.$e->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['id_condicionpago'] = $condiciondepago->id_condicionpago;
		return $resp;
	}

	public function validate_payments_splits($datapost, $cabecera_inicial, $id_contribuyente) {
		if(!isset($datapost['payment_splits']) || !is_array($datapost['payment_splits'])) {
			//es posible que sea una venta desde una ventana donde no se divida el pago.
			//aquí tenemos que armar el array de pagos
			if(isset($datapost['condicionpago_comprobante']) && intval($datapost['condicionpago_comprobante'])) {
				$id_condicionpago = intval($datapost['condicionpago_comprobante']);
			} else {
				$resp_creacion_condicionpago = $this->crear_condiciondepago($id_contribuyente, 'contado');
				if($resp_creacion_condicionpago['respuesta'] == 'error') {
					return $resp_creacion_condicionpago;
				}

				$id_condicionpago = $resp_creacion_condicionpago['id_condicionpago'];
				$datapost['condicionpago_comprobante'] = $id_condicionpago;
			}

			$condiciondepago = Condiciondepago::findFirst(array("id_contribuyente = :id_contribuyente: and id_condicionpago = :id_condicionpago:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_condicionpago' => $id_condicionpago)));
			if(!$condiciondepago) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['code'] = 'documentoelectronico_validate_payments_splits';
				$resp['mensaje'] = 'La condición de pago seleccionada no es válida!';
				return $resp;
			}

			$total_a_pagar = floatval($datapost['txt_total_comprobante']);
			$monto_pagado = $total_a_pagar;
			if(isset($datapost['opcion_tipo_venta']) && ($datapost['opcion_tipo_venta'] == 'on' || $datapost['opcion_tipo_venta'] == 'credito')) {
				
				
				$pagado = floatval($datapost['txt_monto_adeudado']) + floatval($datapost['txt_pago_parcial']);
				/*
				if($pagado != $total_a_pagar) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'La Suma del Monto a Crédito y el Monto Pagado es diferente al total del comprobante, revisa por favor!';
					return $resp;
				}
				*/
				
				$monto_pagado = floatval($datapost['txt_pago_parcial']);
			}

			$select_cuenta_banco_deposito = isset($datapost['select_cuenta_banco_deposito']) ? intval($datapost['select_cuenta_banco_deposito']) : 0;
			$fecha_deposito = isset($datapost['fecha_deposito']) ? $datapost['fecha_deposito'] : '';
			if($condiciondepago->tipo == 'contado' || $condiciondepago->tipo == 'tarjeta_credito' || $condiciondepago->tipo == 'credito') {
				$select_cuenta_banco_deposito = '';
				$fecha_deposito = '';
			}

			$txt_numero_operacion = isset($datapost['txt_numero_operacion']) ? $datapost['txt_numero_operacion'] : '';
			if($condiciondepago->tipo == 'contado') {
				$txt_numero_operacion = '';
			}

			$datapost['payment_splits'] = array();
			if($monto_pagado > 0) {
				$datapost['payment_splits'][] = array(
					"id" 					=> 0,
					"tipo" 					=> $condiciondepago->tipo,
					"id_condicionpago" 		=> $condiciondepago->id_condicionpago,
					"total" 				=> $monto_pagado,
					"idbanco" 				=> $select_cuenta_banco_deposito,
					"cpago_nrooperacion" 	=> $txt_numero_operacion,
					"fechadeposito" 		=> !isset($cabecera_inicial['fecha_deposito_transferencia'])?null:$cabecera_inicial['fecha_deposito_transferencia']
				);
			}
		}
		
		$resp['respuesta'] = 'ok';
		$resp['datapost'] = $datapost;
		return $resp;
	}

	public function ordenar_array_por(&$arrIni, $col, $order = SORT_ASC) {
		$arrAux = array();
		foreach ($arrIni as $key=> $row)
		{
			$arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
			$arrAux[$key] = strtolower($arrAux[$key]);
		}
		array_multisort($arrAux, $order, $arrIni);
	}

	public function validar_cuotas($datapost, $total_adeudado) {
		$herramientas = new HerramientasController;
		if(!isset($datapost['data_cuotas'])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Cuotas';
			$resp['mensaje'] = 'Debes ingresar el detalle de todas las cuotas';
			return $resp;
		}

		$data_cuotas = json_decode($datapost['data_cuotas'], true);
		$array_lista_cuotas = array();

		if(!isset($data_cuotas['cuotas'])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Cuotas';
			$resp['mensaje'] = 'Debes ingresar el detalle de todas las cuotas';
			return $resp;
		}

		if(!is_array($data_cuotas['cuotas'])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Cuotas';
			$resp['mensaje'] = 'El listado de cuotas no es válido';
			return $resp;
		}

		//validamos en caso no exista cuotas
		if(count($data_cuotas['cuotas']) <= 0) {
			$array_fecha_pagopendiente = explode('/',!isset($datapost['fecha_pago_comprobante'])?date('d/m/Y'):$datapost['fecha_pago_comprobante']);
			if(!isset($array_fecha_pagopendiente[2]) || !isset($array_fecha_pagopendiente[1]) || !isset($array_fecha_pagopendiente[0])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
				return $resp;
			}

			$fecha_pagopendiente = $array_fecha_pagopendiente[2].'-'.$array_fecha_pagopendiente[1].'-'.$array_fecha_pagopendiente[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_pagopendiente) !== FALSE)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto pendiente no es válida!';
				return $resp;
			}

			$resp_fecha_2 = $herramientas->comparar_fechas($fecha_pagopendiente, date('Y-m-d'));
			if($resp_fecha_2['diferencia_primera_segunda'] < 1) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'La fecha de pago del monto adeudado debe ser mayor a la fecha actual, por favor verifique!';
				return $resp;
			}

			$datapost['data_cuotas'] = array(
				'num_cuotas'			=> 1,
				'monto_total_cuotas' 	=> $total_adeudado,
				'cuotas'				=> array(
												array(
													'id_cuota'	=> 1,
													'monto_cuota'	=> $total_adeudado,
													'vencimiento_cuota'	=> $fecha_pagopendiente
												)
				)
			);

			$array_lista_cuotas[] = array(
				'id_cuota'	=> 1,
				'monto_cuota'	=> $total_adeudado,
				'vencimiento_cuota'	=> $fecha_pagopendiente
			);

			$resp['respuesta'] = 'ok';
			$resp['num_cuotas'] = 1;
			$resp['total_cuotas'] = $total_adeudado;
			$resp['array_cuotas'] = $array_lista_cuotas;
			$resp['data_cuotas'] = array(
				'num_cuotas'			=> 1,
				'monto_total_cuotas' 	=> $total_adeudado,
				'cuotas'				=> $array_lista_cuotas
			);
			
			return $resp;
		}

		$this->ordenar_array_por($data_cuotas['cuotas'], 'id_cuota', SORT_ASC);
		$array_data_cuotas = $data_cuotas['cuotas'];
		
		$herramientas = new HerramientasController;
		$total_cuotas = 0;
		$n = 0;
		$fecha_anterior = '';
		foreach($array_data_cuotas as $cuota) {
			$n++;
			$monto_cuota = round(floatval($cuota['monto_cuota']), 2);

			if($monto_cuota <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'El monto de la cuota N° '.$n.' es inválido. (Debe ser mayor a cero y con máximo dos decimales)';
				return $resp;
			}

			$total_cuotas = $total_cuotas + $monto_cuota;
			$vencimiento_cuota = $cuota['vencimiento_cuota']; 
			
			$array_fecha_vence_cuota = explode('/',!isset($vencimiento_cuota)?date('d/m/Y'):$vencimiento_cuota);
			if(!isset($array_fecha_vence_cuota[2]) || !isset($array_fecha_vence_cuota[1]) || !isset($array_fecha_vence_cuota[0])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'La fecha de vencimiento asignada a la cuota N° '.$n.' es inválida.';
				return $resp;
			}
			
			$fecha_vence_cuota = $array_fecha_vence_cuota[2].'-'.$array_fecha_vence_cuota[1].'-'.$array_fecha_vence_cuota[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_vence_cuota) !== FALSE)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'La fecha de vencimiento asignada a la cuota N° '.$n.' es inválida.';
				return $resp;
			}

			//validación de fecha del comprobante vs la fecha de la cuota.
			if(!isset($datapost['fecha_comprobante'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'Debes seleccionar la fecha de emisión para el comprobante.';
				return $resp;
			}

			$array_fecha_comprobante = explode('/',$datapost['fecha_comprobante']);
			if(!isset($array_fecha_comprobante[2]) || !isset($array_fecha_comprobante[1]) || !isset($array_fecha_comprobante[0])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'Debes seleccionar la fecha de emisión para el comprobante.';
				return $resp;
			}
			
			$fecha_comprobante = $array_fecha_comprobante[2].'-'.$array_fecha_comprobante[1].'-'.$array_fecha_comprobante[0];
			if (!(DateTime::createFromFormat('Y-m-d', $fecha_comprobante) !== FALSE)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'La fecha de vencimiento asignada a la cuota N° '.$n.' es inválida.';
				return $resp;
			}

			$resp_fecha_cpe = $herramientas->comparar_fechas($fecha_vence_cuota, $fecha_comprobante);
			if($resp_fecha_cpe['diferencia_primera_segunda'] <= 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Cuotas';
				$resp['mensaje'] = 'La fecha ('.$vencimiento_cuota.') asignada a la Cuota N° '.$n.' debe ser mayor a la fecha del documento (Fecha del Documento: '.$datapost['fecha_comprobante'];
				return $resp;
			}
			//fin: validación de fecha del comprobante vs la fecha de la cuota.

			if($fecha_anterior == '') {
				$fecha_anterior = $fecha_vence_cuota;
			} else {
				$resp_fecha = $herramientas->comparar_fechas($fecha_vence_cuota, $fecha_anterior);
				if($resp_fecha['diferencia_primera_segunda'] <= 0) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en Cuotas';
					$resp['mensaje'] = 'La fecha ('.$vencimiento_cuota.') asignada a la Cuota N° '.$n.' debe ser mayor a la fecha de vencimiento de la cuota N° '.($n - 1);
					return $resp;
				}
			}

			$array_lista_cuotas[] = array(
				'id_cuota'	=> $n,
				'monto_cuota'	=> $monto_cuota,
				'vencimiento_cuota'	=> $fecha_vence_cuota
			);
		}

		$total_adeudado = floatval($total_adeudado);
		$total_cuotas = floatval($total_cuotas);

		if($total_cuotas != $total_adeudado) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Cuotas';
			$resp['mensaje'] = 'La Sumatoria de los montos adeudados de cada cuota, debe ser igual al monto total adeudado';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['num_cuotas'] = $n;
		$resp['total_cuotas'] = $total_cuotas;
		$resp['array_cuotas'] = $array_lista_cuotas;
		$resp['data_cuotas'] = array(
			'num_cuotas'			=> $n,
			'monto_total_cuotas' 	=> $total_adeudado,
			'cuotas'				=> $array_lista_cuotas
		);
		
		return $resp;
	}

	public function ordenamiento_descendente_cuotas($item1, $item2) {
		if ($item1['id_cuota'] == $item2['id_cuota']) return 0;
		return ($item1['id_cuota'] < $item2['id_cuota']) ? 1 : -1;
	}

	public function ordenamiento_ascendente_cuotas($item1, $item2) {
		if ($item1['id_cuota'] == $item2['id_cuota']) return 0;
		return ($item1['id_cuota'] > $item2['id_cuota']) ? 1 : -1;
	}

	public function get_idcliente($usuario, $datapost) {
		$idusuario = $usuario->idusuario;
		$herramientas = new HerramientasController;

		$opcion_envio_email = !isset($datapost['opcion_envio_email'])?'no':$datapost['opcion_envio_email'];
		$email_cliente = '';
		if($opcion_envio_email != 'no') {
			$email_cliente = !isset($datapost['cliente_email'])?'':strtolower(trim($datapost['cliente_email']));
			if($email_cliente == '') {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en Email';
				$resp['mensaje'] = 'Si deseas enviar el documento electrónico al cliente, debes ingresar un email válido!.';
				return $resp;
			}

			$validaciones = new Validaciones();
			if(!$validaciones->validate_email($email_cliente)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Email Inválido';
				$resp['mensaje'] = 'Si deseas enviar el documento electrónico al cliente, debes ingresar un email válido!.';
				return $resp;
			}
		}

		$numero_telefono =  !isset($datapost['numero_celular'])?'':$datapost['numero_celular'];

		$idcliente = !isset($datapost['id_cliente_documento'])?0:intval($datapost['id_cliente_documento']) + 0;
		if($idcliente > 0) {
			$cliente = Cliente::findFirst(array("idcliente = :idcliente: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcliente' => $idcliente, 'id_contribuyente' => $usuario->id_contribuyente)));
			if($cliente) {
				if($opcion_envio_email != 'no') { //si se ha agregado un email válido entonces procedemos a actualizar el usuario
					$cliente->email = $email_cliente;
					try {
						if(!$cliente->save()) {
							$msg = '';
							foreach ($cliente->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = $msg;
							return $resp;
						}
					} catch (Exception $e) {
                		$this->saveLogger($e);
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $e->getMessage();
						return $resp;
					}
				}

				if(!empty($numero_telefono)) {
					$cliente->celular = $numero_telefono;

					try {
						if(!$cliente->save()) {
							$msg = '';
							foreach ($cliente->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = $msg;
							return $resp;
						}
					} catch (Exception $e) {
                		$this->saveLogger($e);
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $e->getMessage();
						return $resp;
					}
				}

				$direccion = !isset($datapost['cliente_direccion'])?'':$datapost['cliente_direccion'];
				$codigo_ubigeo = !isset($datapost['select_codigoubigeo'])?'':$datapost['select_codigoubigeo'];
				$codigo_ubigeo = ($codigo_ubigeo == '-')?'':$codigo_ubigeo;
				if($codigo_ubigeo != '') {
					$codigo_ubigeo_bd = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $codigo_ubigeo)));
					if($codigo_ubigeo_bd) {
						$cliente->id_cod_ubigeo = $codigo_ubigeo;

						try {
							if(!$cliente->save()) {
								$msg = '';
								foreach ($cliente->getMessages() as $message) {
									$msg = $msg.$message."</br>\n";
								}
								$resp['respuesta'] = 'error';
								$resp['titulo'] = 'Error';
								$resp['mensaje'] = $msg;
								return $resp;
							}
						} catch (Exception $e) {
                			$this->saveLogger($e);
							$resp['respuesta'] = 'error';
							$resp['titulo'] = 'Error';
							$resp['mensaje'] = $e->getMessage();
							return $resp;
						}
					}
				}

				$resp['respuesta'] = 'ok';
				$resp['code'] = '1';
				$resp['idcliente'] = $cliente->idcliente;
				return $resp;
			}
		}
		
		$id_tipodocidentidad = !isset($datapost['cliente_tipo_docidentidad'])?'':$datapost['cliente_tipo_docidentidad'];
		$tipo_documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad)));
		if(!$tipo_documento_identidad) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes seleccionar un tipo de documento de identidad válido!..';
			return $resp;
		}
		
		if($tipo_documento_identidad->id_tipodocidentidad == "1") {
			if(!empty($datapost['cliente_nombre']) && empty($datapost['cliente_numerodocumento'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Haz Seleccionado DNI como tipo de Documento, y también estás ingresando el nombre del cliente, por tanto debes ingresar si o si un número de DNI válido.!..';
				return $resp;
			}

			if($datapost['cliente_numerodocumento'] == '*' || $datapost['cliente_numerodocumento'] == '' || $datapost['cliente_numerodocumento'] == '0' || $datapost['cliente_numerodocumento'] == '-') {
				$datapost['cliente_numerodocumento'] = '00000000';
				$datapost['cliente_nombre'] = '-';
				$datapost['cliente_direccion'] = '-';
				$datapost['select_codigoubigeo'] = '150101';
				$email_cliente = '';
			}
		}

		if($tipo_documento_identidad->id_tipodocidentidad == "0") {
			if($datapost['cliente_numerodocumento'] == '*' || $datapost['cliente_numerodocumento'] == '' || $datapost['cliente_numerodocumento'] == '0' || $datapost['cliente_numerodocumento'] == '-') {
				$datapost['cliente_numerodocumento'] = 'sdi'.uniqid();
				if(empty($datapost['cliente_nombre'])) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Debes Ingresar el Nombre de tu Cliente';
					return $resp;
				}

				$datapost['select_codigoubigeo'] = '150101';
				$email_cliente = '';
			}
		}
		
		$num_doc = !isset($datapost['cliente_numerodocumento'])?'':trim($datapost['cliente_numerodocumento']);
		if($num_doc == '') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes ingresar un número de documento de identidad válido!';
			return $resp;
		}

		if($herramientas->valida_caracteres_especiales($num_doc)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El número de documento tiene caractéres inválidos, no se permiten espacios, tildes o caracteres especiales.';
			return $resp;
		}
		
		if($tipo_documento_identidad->id_tipodocidentidad == "6") {
			if (ctype_digit($num_doc)) {
				if(strlen($num_doc) != 11) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Tu estás ingresando '.strlen($num_doc).' caracteres como tu número de RUC, y normalmente el número de RUC debe tener 11 números.';
					return $resp;
				}
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Si haz Seleccionado un RUC, Debes ingresar solo números en el cambo NÚMERO DE DOCUMENTO, encontramos que estás ingresando letras!';
				return $resp;
			}
		}

		if($tipo_documento_identidad->id_tipodocidentidad == "1") {
			if (ctype_digit($num_doc)) {
				if(strlen($num_doc) != 8) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'Tu estás ingresando '.strlen($num_doc).' caracteres como tu número de DNI, y normalmente el número de DNI debe tener 8 números.';
					return $resp;
				}
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Si haz Seleccionado un RUC, Debes ingresar solo números en el cambo NÚMERO DE DOCUMENTO, encontramos que estás ingresando letras!';
				return $resp;
			}
		}

		if($tipo_documento_identidad->id_tipodocidentidad == "4" || $tipo_documento_identidad->id_tipodocidentidad == "7" || $tipo_documento_identidad->id_tipodocidentidad == "0" || $tipo_documento_identidad->id_tipodocidentidad == "A" || $tipo_documento_identidad->id_tipodocidentidad == "B" || $tipo_documento_identidad->id_tipodocidentidad == "C" || $tipo_documento_identidad->id_tipodocidentidad == "D") {
			if(strlen($num_doc) > 20) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Tu estás ingresando '.strlen($num_doc).' caracteres para el número de documento, y Según las Validaciones de SUNAT, indica que se deben colocar un máximo de 15 caracteres, por favor verifica!';
				return $resp;
			}
		}

		$cliente = Cliente::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad: and num_doc = :num_doc:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad, 'num_doc' => $num_doc, 'id_contribuyente' => $usuario->id_contribuyente)));
		if($cliente) {

			if($opcion_envio_email != 'no') { //si se ha agregado un email válido entonces procedemos a actualizar el usuario
				$cliente->email = $email_cliente;

				try {
					if(!$cliente->save()) {
						$msg = '';
						foreach ($cliente->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}
			}

			if(!empty($numero_telefono)) {
    			$cliente->celular = $numero_telefono;
				try {
					if(!$cliente->save()) {
						$msg = '';
						foreach ($cliente->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error';
						$resp['mensaje'] = $msg;
						return $resp;
					}
				} catch (Exception $e) {
                	$this->saveLogger($e);
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $e->getMessage();
					return $resp;
				}
    		}
			
			$resp['respuesta'] = 'ok';
			$resp['code'] = '3';
			$resp['idcliente'] = $cliente->idcliente;
			return $resp;
		}

		$razon_social = !isset($datapost['cliente_nombre'])?'':$datapost['cliente_nombre'];
		if($razon_social == '') {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Ingresar un Nombre o Razón Social!.';
			return $resp;
		}

		$direccion = !isset($datapost['cliente_direccion'])?'':$datapost['cliente_direccion'];
		$codigo_ubigeo = !isset($datapost['select_codigoubigeo'])?'':$datapost['select_codigoubigeo'];
		$codigo_ubigeo = ($codigo_ubigeo == '-')?'':$codigo_ubigeo;
		if($codigo_ubigeo != '') {
			$codigo_ubigeo_bd = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $codigo_ubigeo)));
			if(!$codigo_ubigeo_bd) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Debes Seleccionar el código de ubigeo correcto!';
				return $resp;
			}
		}

		$new_cliente = new Cliente();
		if(isset($datapost['cliente_api_foto']) && $datapost['cliente_api_foto'] != '') {	
			$resp_save_img_clie = $herramientas->guardar_imagen_cliente($usuario->id_contribuyente, $datapost['cliente_api_foto']);
			if($resp_save_img_clie['respuesta'] == 'ok') {
				$new_cliente->foto = $resp_save_img_clie['urlimagen'];
			}
		}

		if(isset($datapost['cliente_api_fecha_nac']) && $datapost['cliente_api_fecha_nac'] != '') {
			$array_fecha_nacimiento = explode('/',!isset($datapost['cliente_api_fecha_nac'])?'':$datapost['cliente_api_fecha_nac']);
			$clie_dia_nacimiento = '';
			$clie_mes_nacimiento = '';
			$clie_anio_nacimiento = '';

			if(isset($array_fecha_nacimiento[0])) {
				$clie_dia_nacimiento = $array_fecha_nacimiento[0];
			}

			if(isset($array_fecha_nacimiento[1])) {
				$clie_mes_nacimiento = $array_fecha_nacimiento[1];
			}

			if(isset($array_fecha_nacimiento[2])) {
				$clie_anio_nacimiento = $array_fecha_nacimiento[2];
			}

			$clie_fecha_nacimiento = $clie_anio_nacimiento.'-'.$clie_mes_nacimiento.'-'.$clie_dia_nacimiento;
			if($herramientas->validate_date($clie_fecha_nacimiento)) {
				$new_cliente->fecha_nac = date("Y-m-d", strtotime($clie_fecha_nacimiento));
			}
		}

		if(isset($datapost['cliente_api_sexo']) && $datapost['cliente_api_sexo'] != '') {
			if($datapost['cliente_api_sexo'] == 'FEMENINO') {
				$new_cliente->sexo = 'femenino';
			} else if($datapost['cliente_api_sexo'] == 'MASCULINO') {
				$new_cliente->sexo = 'masculino';
			}
		}
		
		$new_cliente->codigo = uniqid('CLIE_',true);
		$new_cliente->id_contribuyente = $usuario->id_contribuyente;
		$new_cliente->fecha_registro = date('Y-m-d H:i:s');
		$new_cliente->estado = 'activo';
		$new_cliente->id_tipodocidentidad = $id_tipodocidentidad;
		$new_cliente->num_doc = $num_doc;
		$new_cliente->email = $email_cliente;
		$new_cliente->razon_social = $razon_social;
		$new_cliente->direccion_fiscal = $direccion;
		
		if(!empty($numero_telefono)) {
			$new_cliente->celular = $numero_telefono;
		}

		if($codigo_ubigeo != '') {
			$new_cliente->id_cod_ubigeo = $codigo_ubigeo;
		}

		try {
			if(!$new_cliente->save()) {
				$msg = '';
				foreach ($new_cliente->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}

				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $msg;
				return $resp;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['idcliente'] = $new_cliente->idcliente;
		return $resp;
	}


	public function getDocumentoElectronicoAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

			$serie_comprobante = $datapost['serie'];
			$numero_comprobante = $datapost['numero'];
			$id_tipodoc_electronico = $datapost['tipo_doc'];
			$id_contribuyente = $usuario->id_contribuyente;

			$id_tipo_doc_modifica = !isset($datapost['id_tipo_doc_modifica'])?'':$datapost['id_tipo_doc_modifica'];
			$serie_comprobante_modifica = !isset($datapost['serie_comprobante_modifica'])?'':$datapost['serie_comprobante_modifica'];
			$numero_comprobante_modifica = !isset($datapost['numero_comprobante_modifica'])?'':$datapost['numero_comprobante_modifica'];
			
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			if(!$documento) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El documento electrónico no es válido';
				echo json_encode($resp);
				exit();
			}

			$factura = new FacturaController;
			$data_anticipos = $factura->get_anticipos($documento);

			$items_detalle = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

			$estado_sunat = '';
			if($documento->estado_envio_sunat == 'aceptado') {
				$estado_sunat = '<span class="label bg-success heading-text">'.$documento->tipo_envio_sunat.': Enviado Correctamente a SUNAT</span>';
			} else if($documento->estado_envio_sunat == 'rechazado') {
				$estado_sunat = '<span class="label bg-danger heading-text">'.$documento->tipo_envio_sunat.': Fué Rechazado por SUNAT</span>';
			} else if($documento->estado_envio_sunat == 'pendiente') {
				$estado_sunat = '<span class="label bg-primary heading-text">'.$documento->tipo_envio_sunat.': Aún no fué enviado a SUNAT</span>';
			}

			$fecha_comprobante = date("d-m-Y", strtotime($documento->fecha_registro));

			$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodoc_electronico)));
			$titulo_comprobante = $tipo_doc_electronico->descripcion.': '.$documento->serie_comprobante.' - '.$documento->numero_comprobante;

			$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));

			$data_grid = array();
			foreach($items_detalle as $item) {
				$tipo_afectacion_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
				$array_tipoafectacionigv = ($tipo_afectacion_igv)?explode('-', $tipo_afectacion_igv->descripcion):array(0);

				$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));

				$bd_lista_presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $item->id_producto)));
				
				$array_lista_presentaciones = array();
				$array_lista_presentaciones[] = array(
					"val"				=> 'UND-'.$unidad_medida_sunat->idunidad,
					"text"				=> $unidad_medida_sunat->nombre,

					"tipo"				=> 'unidad',
					"idpresentacion"	=> "",
					"codigo"			=> $item->codigo_producto,
					"nombrepresentacion"	=> "",
					"idproducto"		=> $item->id_producto,
					"idunidadpresentacion"	=> "",
					"idunidadbase"		=> $unidad_medida_sunat->idunidad,
					"nombreunidadpresentacion"	=> '',
					"cantidad"			=> "",
					"nombreunidadbase"	=> $unidad_medida_sunat->nombre,
					"precioconigv"		=> $item->precio,
					"preciosinigv"		=> $item->precio_sin_igv
				);
				
				foreach($bd_lista_presentaciones as $item_presentacion) {
					$array_lista_presentaciones[] = array(
						"val"					=> 'PRE-'.$item_presentacion->id_presentacion,
						"text"					=> $item_presentacion->nombre,

						"tipo"					=> 'presentacion',
						"idpresentacion"		=> $item_presentacion->id_presentacion,
						"codigo"				=> $item_presentacion->codigo,
						"nombrepresentacion"	=> $item_presentacion->nombre,
						"idproducto"			=> $item_presentacion->idproducto,
						"idunidadpresentacion"	=> $item_presentacion->idunidad,
						"idunidadbase"			=> $item_presentacion->idunidad_base,
						"nombreunidadpresentacion"	=> $item_presentacion->nombre,
						"cantidad"				=> $item_presentacion->cantidad_und_base,
						"nombreunidadbase"		=> '',
						"precioconigv"			=> $item_presentacion->precio_con_igv,
						"preciosinigv"			=> $item_presentacion->precio_sin_igv
					);
				}
				
				$idunidadmedida = 'UND-'.$item->id_unidad_medida;
				$nombreunidadmedida = $unidad_medida_sunat->nombre;

				$tipo_unidad = 'UND';
				$idpresentacion = '';
				if(isset($item->tipo_unidad) && $item->tipo_unidad == 'PRE') {
					$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => $item->id_presentacion)));
					if($presentacion) {
						$tipo_unidad = 'PRE';
						$idpresentacion = $presentacion->id_presentacion;

						$idunidadmedida = 'PRE-'.$presentacion->id_presentacion;
						$nombreunidadmedida = $presentacion->nombre;
					}
				}
				
				$factor_igv = round($item->factor_igv/100, 2);

				$data_grid[] = array(
					"row_identificadador"   => $item->id_producto.'|.|.|'.str_replace(' ', '', strtolower($item->descripcion)),
					"idarticulo" 			=> $item->id_producto,
					"id_tipoafectacionigv" 	=> $item->id_tipoafectacionigv,
					"descripcion"			=> $item->descripcion,
					"text_tipo_afecigv" 	=> $array_tipoafectacionigv[0],
					"idunidadmedida" 		=> $idunidadmedida,
					"unidadmedida" 			=> $nombreunidadmedida,
					"precio" 				=> $item->precio + 0,
					"id_cod_moneda" 		=> $documento->id_codigomoneda,
					"cantidad" 				=> $item->cantidad + 0,
					"subtotal" 				=> $item->sub_total + 0,
					"igv" 					=> $item->igv + 0,
					"importe" 				=> round($item->cantidad*$item->precio, 2) + 0,
					"codigo"				=> $item->codigo_producto,
					"estado" 				=> 'V',

					"select_unidadmedida"	=> json_encode($array_lista_presentaciones),
					"tipo_unidad"			=> $tipo_unidad,
					"idpresentacion"		=> $idpresentacion,

					"p_unit_sin_igv"		=> round($item->precio/(1 + $factor_igv), 10),
					"factor_igv_sunat"		=> $factor_igv
				);
			}

			if($documento->id_tipodoc_electronico == '07' || $documento->id_tipodoc_electronico == '08') {
				$documento_modificado = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipo_doc_modifica, 'serie_comprobante' => $serie_comprobante_modifica, 'numero_comprobante' => $numero_comprobante_modifica, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

				if(!$documento_modificado) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'El documento que modifica la presente nota de crédito no existe!!';
					echo json_encode($resp);
					exit();
				}
				$estado_sunat = '<span class="label bg-success heading-text">'.$documento_modificado->estado_envio_sunat.': Enviado Correctamente a SUNAT</span>';
				$heading_elements = '<span class="heading-text"><i class="icon-history text-warning position-left"></i> '.$documento_modificado->fecha_comprobante.'</span> '.$estado_sunat;

				$resp['doc_modifica_id_tipodoc_electronico'] = $id_tipo_doc_modifica;
				$resp['doc_modifica_serie_comprobante'] = $serie_comprobante_modifica;
				$resp['doc_modifica_numero_comprobante'] = $numero_comprobante_modifica;
				$resp['cod_motivo_nota'] = ($documento->id_tipodoc_electronico == '07')?$documento->id_cod_tipomotivo_credito:$documento->id_cod_tipomotivo_debito;
			} else {
				$heading_elements = '<span class="heading-text"><i class="icon-history text-warning position-left"></i> '.$fecha_comprobante.'</span> '.$estado_sunat;

				$resp['doc_modifica_id_tipodoc_electronico'] = '';
				$resp['doc_modifica_serie_comprobante'] = '';
				$resp['doc_modifica_numero_comprobante'] = '';
				$resp['cod_motivo_nota'] = '';
			}

			$resp['respuesta'] = 'ok';
			$resp['comprobante'] = $documento;
			$resp['detalle'] = $items_detalle;
			$resp['fecha_comprobante'] = $fecha_comprobante;
			$resp['estado_comprobante'] = $estado_sunat;
			$resp['heading_elements'] = $heading_elements;
			$resp['titulo_comprobante'] = $titulo_comprobante;
			$resp['cliente'] = $cliente;
			$resp['data_grid'] = $data_grid;
			$resp['data_anticipos'] = $data_anticipos;

			echo json_encode($resp);
			exit();
		}
	}

	public function getDataDocAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

			$id_tipodocumento = $datapost['id_tipodocumento'];

			if($id_tipodocumento == '77' || $id_tipodocumento == '88') {
				$resp = $this->get_data_doc_no_oficial($datapost, $contribuyente, $usuario);
				echo json_encode($resp);
				exit();
			}

			if($id_tipodocumento == '01' || $id_tipodocumento == '03' || $id_tipodocumento == '09' ) {
				$resp = $this->get_data_doc_electronico($datapost, $contribuyente, $usuario);
				echo json_encode($resp);
				exit();
			}

			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el tipo de documento';
			echo json_encode($resp);
			exit();
		}
	}

	public function get_data_doc_electronico($datapost, $contribuyente, $usuario) {
		$serie_comprobante = $datapost['serie_comprobante'];
		$numero_comprobante = $datapost['numero_comprobante'];
		$id_tipodoc_electronico = $datapost['id_tipodocumento'];
		$herramientas = new HerramientasController;
		
		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El documento electrónico no es válido';
			echo json_encode($resp);
			exit();
		}

		if(empty($documento->porcentaje_igv)) {
			$documento = (array)$documento;
			$documento['porcentaje_igv'] = 18;
			$documento = (object)$documento;
		}

		if(empty($documento->id_codigomoneda)) {
			$documento = (array)$documento;
			$documento['id_codigomoneda'] = 'PEN';
			$documento = (object)$documento;
		}

		$items_detalle = DetalleDoc::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		$texto_ubigeo = '';
		if($cliente->id_cod_ubigeo != '') {
			$sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
			if($sql_ubigeo){
				$departamento = $sql_ubigeo->departamento;
				$provincia = $sql_ubigeo->provincia;
				$distrito = $sql_ubigeo->distrito;
				$texto_ubigeo = $departamento.' - '.$provincia.' - '.$distrito;
			}
		}

		$data_grid = array();
		foreach($items_detalle as $item) {
			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item->id_producto, 'id_contribuyente' => $item->id_contribuyente)));

			$id_tipoafectacionigv = $item->id_tipoafectacionigv;
			$tipo_afectacion_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			if(!$tipo_afectacion_igv) {
				if($producto) {
					$tipo_afectacion_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
					$id_tipoafectacionigv = $producto->id_tipoafectacionigv;
				}
			}

			$id_codigomoneda = $documento->id_codigomoneda;
			if(empty($id_codigomoneda)) {
				if($producto) {
					$id_codigomoneda = $producto->id_cod_moneda;
				}
			}

			$array_tipoafectacionigv = ($tipo_afectacion_igv)?explode('-', $tipo_afectacion_igv->descripcion):array(0);
			$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));
			
			$bd_lista_presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $item->id_producto)));
			
			$array_lista_presentaciones = array();
			$array_lista_presentaciones[] = array(
				"val"				=> 'UND-'.$unidad_medida_sunat->idunidad,
				"text"				=> $unidad_medida_sunat->nombre,

				"tipo"				=> 'unidad',
				"idpresentacion"	=> "",
				"codigo"			=> $item->codigo_producto,
				"nombrepresentacion"	=> "",
				"idproducto"		=> $item->id_producto,
				"idunidadpresentacion"	=> "",
				"idunidadbase"		=> $unidad_medida_sunat->idunidad,
				"nombreunidadpresentacion"	=> '',
				"cantidad"			=> "",
				"nombreunidadbase"	=> $unidad_medida_sunat->nombre,
				"precioconigv"		=> $item->precio,
				"preciosinigv"		=> $item->precio_sin_igv
			);
			
            foreach($bd_lista_presentaciones as $item_presentacion) {
                $array_lista_presentaciones[] = array(
					"val"					=> 'PRE-'.$item_presentacion->id_presentacion,
					"text"					=> $item_presentacion->nombre,

					"tipo"					=> 'presentacion',
					"idpresentacion"		=> $item_presentacion->id_presentacion,
					"codigo"				=> $item_presentacion->codigo,
					"nombrepresentacion"	=> $item_presentacion->nombre,
					"idproducto"			=> $item_presentacion->idproducto,
					"idunidadpresentacion"	=> $item_presentacion->idunidad,
					"idunidadbase"			=> $item_presentacion->idunidad_base,
					"nombreunidadpresentacion"	=> $item_presentacion->nombre,
					"cantidad"				=> $item_presentacion->cantidad_und_base,
					"nombreunidadbase"		=> '',
					"precioconigv"			=> $item_presentacion->precio_con_igv,
					"preciosinigv"			=> $item_presentacion->precio_sin_igv
				);
			}
			
			$idunidadmedida = 'UND-'.$item->id_unidad_medida;
			$nombreunidadmedida = $unidad_medida_sunat->nombre;

			$tipo_unidad = 'UND';
			$idpresentacion = '';
			if(isset($item->tipo_unidad) && $item->tipo_unidad == 'PRE') {
				$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => $item->id_presentacion)));
				if($presentacion) {
					$tipo_unidad = 'PRE';
					$idpresentacion = $presentacion->id_presentacion;

					$idunidadmedida = 'PRE-'.$presentacion->id_presentacion;
					$nombreunidadmedida = $presentacion->nombre;
				}
			}
			if(!empty($item->factor_igv)) {
				$factor_igv = round($item->factor_igv/100, 2);
			}  else {
				$factor_igv = 0.18;
			}

			$subtotal_icbper = floatval($item->icbper) + 0;

			if($subtotal_icbper > 0) {
				$subtotal_icbper = $item->icbper;
				$afecto_icbper = 'si';
			} else {
				$subtotal_icbper = 0;
				$afecto_icbper = 'no';
			}

			$lista_precios_array = array();
			$html_lista_precios = "";

			if($producto) {
				if($producto->id_cod_moneda == 'PEN') {
					$simbolo_moneda_multiprecio = 'S/';
				} else {
					$simbolo_moneda_multiprecio = '$';
				}

				$lista_precios = ProductoListaprecio::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $producto->idproducto)));
				foreach($lista_precios as $item_precio) {
					$lista_precios_array[] = array(
						'nombre' => $item_precio->nombre,
						'precio' => $item_precio->precio + 0,
						'moneda' => $producto->id_cod_moneda
					);

					$function_asignar_precio = "asignar_precio_de_lista($item_precio->precio,'$producto->id_cod_moneda')";
					$html_lista_precios = $html_lista_precios.'<li><a href="javascript:void(0);" data-precio="'.$item_precio->precio.'" onclick="'.$function_asignar_precio.'"><span class="badge badge-success pull-right">'.$simbolo_moneda_multiprecio.' '.$item_precio->precio.'</span> '.$item_precio->nombre.'</a></li>';
				}
				
				if($producto->id_tipoafectacionigv == "10" || $producto->id_tipoafectacionigv == "7152") {
					$precio_venta_original = $producto->valor_con_igv + 0;
				} else {
					$precio_venta_original = $producto->valor_sin_igv + 0;
				}

				$function_asignar_precio = "asignar_precio_de_lista($precio_venta_original,'$producto->id_cod_moneda')";
				$html_lista_precios = $html_lista_precios.'
				<li><a href="javascript:void(0);" data-precio="'.$precio_venta_original.'" onclick="'.$function_asignar_precio.'"><span class="badge badge-success pull-right">'.$simbolo_moneda_multiprecio.' '.$precio_venta_original.'</span> Precio de Venta</a></li>';

				$function_asignar_precio = "asignar_precio_de_lista(".round($producto->precio_venta_minimo ?? 0, 2).",'$producto->id_cod_moneda')";
				$html_lista_precios = $html_lista_precios.'
				<li><a href="javascript:void(0);" data-precio="'.round($producto->precio_venta_minimo ?? 0, 2).'" onclick="'.$function_asignar_precio.'"><span class="badge badge-success pull-right">'.$simbolo_moneda_multiprecio.' '.round($producto->precio_venta_minimo ?? 0, 2).'</span> Precio Mínimo</a></li>';
			}

			if(intval($id_tipoafectacionigv) == 10) {
				$p_unit_sin_igv = round($item->precio/(1 + $factor_igv), 10);
			} else {
				$p_unit_sin_igv = $item->precio;
			}

			$peso = isset($producto->peso)?$producto->peso:0;

			$data_grid[] = array(
				"row_identificadador"   => $herramientas->create_guid(),//$item->id_producto.'|.|.|'.str_replace(' ', '', strtolower($item->descripcion)),
				"idarticulo" 			=> $item->id_producto,
				"id_tipoafectacionigv" 	=> $id_tipoafectacionigv,
				"descripcion"			=> $item->descripcion,
				"text_tipo_afecigv" 	=> $array_tipoafectacionigv[0],
				"idunidadmedida" 		=> $idunidadmedida,
				"unidadmedida" 			=> $nombreunidadmedida,
				"precio" 				=> $item->precio + 0,
				"id_cod_moneda" 		=> $id_codigomoneda,
				"cantidad" 				=> $item->cantidad + 0,
				"subtotal" 				=> $item->sub_total + 0,
				"igv" 					=> $item->igv + 0,
				"importe" 				=> round($item->cantidad*$item->precio, 2) + 0,
				"codigo"				=> $item->codigo_producto,
				"estado" 				=> 'V',

				'subtotal_icbper'		=> $subtotal_icbper,
				'afecto_icbper'			=> $afecto_icbper,

				"select_unidadmedida"	=> json_encode($array_lista_presentaciones),
				"tipo_unidad"			=> $tipo_unidad,
				"idpresentacion"		=> $idpresentacion,

				"p_unit_sin_igv"		=> $p_unit_sin_igv,
				"factor_igv_sunat"		=> $factor_igv,

				"html_lista_precios"			=> $html_lista_precios,
				"item_detraccion_codigo" 		=> '',
				"item_detraccion_porcentaje" 	=> '',

				"stock_actual"					=> isset($producto->stock)?$producto->stock:0,
				"peso_unitario"			=> $peso,
				"peso"					=> round(floatval($peso)*floatval($item->cantidad), 2)
			);			
		}

		$resp['respuesta'] = 'ok';
		$resp['comprobante'] = $documento;
		$resp['detalle'] = $items_detalle;
		$resp['fecha_comprobante'] = $documento->fecha_comprobante;
		$resp['cliente'] = $cliente;
		$resp['data_grid'] = $data_grid;
		$resp['texto_ubigeo'] = $texto_ubigeo;

		echo json_encode($resp);
		exit();
	}

	public function get_data_doc_no_oficial($datapost, $contribuyente, $usuario) {
		$id_tipodocumento = $datapost['id_tipodocumento'];
		$numero_comprobante = intval($datapost['numero_comprobante']) + 0;
		$nombre_nuevo_doc = $datapost['nombre_nuevo_doc'];

		$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Está intentando extraer los datos de un documento inválido o no existente!';
			echo json_encode($resp);
			exit();
		}
		
		$items_detalle = DetalleDocnooficial::find(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $contribuyente->tipo_envio_sunat)));

		$data_grid = array();
		foreach($items_detalle as $item) {
			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item->id_producto, 'id_contribuyente' => $item->id_contribuyente)));

			$tipo_afectacion_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item->id_tipoafectacionigv)));
			$array_tipoafectacionigv = ($tipo_afectacion_igv)?explode('-', $tipo_afectacion_igv->descripcion):array(0);
			$unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->id_unidad_medida)));
			
			$bd_lista_presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $item->id_producto)));
			
			$array_lista_presentaciones = array();
			$array_lista_presentaciones[] = array(
				"val"				=> 'UND-'.$unidad_medida_sunat->idunidad,
				"text"				=> $unidad_medida_sunat->nombre,

				"tipo"				=> 'unidad',
				"idpresentacion"	=> "",
				"codigo"			=> "",
				"nombrepresentacion"	=> "",
				"idproducto"		=> $item->id_producto,
				"idunidadpresentacion"	=> "",
				"idunidadbase"		=> $unidad_medida_sunat->idunidad,
				"nombreunidadpresentacion"	=> '',
				"cantidad"			=> "",
				"nombreunidadbase"	=> $unidad_medida_sunat->nombre,
				"precioconigv"		=> $item->precio,
				"preciosinigv"		=> $item->precio_sin_igv
			);
			
            foreach($bd_lista_presentaciones as $item_presentacion) {
                $array_lista_presentaciones[] = array(
					"val"					=> 'PRE-'.$item_presentacion->id_presentacion,
					"text"					=> $item_presentacion->nombre,

					"tipo"					=> 'presentacion',
					"idpresentacion"		=> $item_presentacion->id_presentacion,
					"codigo"				=> $item_presentacion->codigo,
					"nombrepresentacion"	=> $item_presentacion->nombre,
					"idproducto"			=> $item_presentacion->idproducto,
					"idunidadpresentacion"	=> $item_presentacion->idunidad,
					"idunidadbase"			=> $item_presentacion->idunidad_base,
					"nombreunidadpresentacion"	=> $item_presentacion->nombre,
					"cantidad"				=> $item_presentacion->cantidad_und_base,
					"nombreunidadbase"		=> '',
					"precioconigv"			=> $item_presentacion->precio_con_igv,
					"preciosinigv"			=> $item_presentacion->precio_sin_igv
				);
			}
			
			$idunidadmedida = 'UND-'.$item->id_unidad_medida;
			$nombreunidadmedida = $unidad_medida_sunat->nombre;

			$tipo_unidad = 'UND';
			$idpresentacion = '';
			if(isset($item->tipo_unidad) && $item->tipo_unidad == 'PRE') {
				$presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => $item->id_presentacion)));
				if($presentacion) {
					$tipo_unidad = 'PRE';
					$idpresentacion = $presentacion->id_presentacion;

					$idunidadmedida = 'PRE-'.$presentacion->id_presentacion;
					$nombreunidadmedida = $presentacion->nombre;
				}
			}

			$factor_igv = round($item->factor_igv/100, 2);
			if(intval($item->id_tipoafectacionigv) == 10) {
				$p_unit_sin_igv = round($item->precio/(1 + $factor_igv), 10);
			} else {
				$p_unit_sin_igv = $item->precio;
			}

			$peso = isset($producto->peso)?$producto->peso:0;

			$data_grid[] = array(
				"row_identificadador"   => $item->id_producto.'|.|.|'.str_replace(' ', '', strtolower($item->descripcion)),
				"idarticulo" 			=> $item->id_producto,
				"id_tipoafectacionigv" 	=> $item->id_tipoafectacionigv,
				"descripcion"			=> $item->descripcion,
				"text_tipo_afecigv" 	=> $array_tipoafectacionigv[0],
				"idunidadmedida" 		=> $idunidadmedida,
				"unidadmedida" 			=> $nombreunidadmedida,
				"precio" 				=> $item->precio + 0,
				"id_cod_moneda" 		=> $documento->id_codigomoneda,
				"cantidad" 				=> $item->cantidad + 0,
				"subtotal" 				=> $item->sub_total + 0,
				"igv" 					=> $item->igv + 0,
				"importe" 				=> round($item->cantidad*$item->precio, 2) + 0,
				"codigo"				=> $item->codigo_producto,
				"estado" 				=> 'V',

				"select_unidadmedida"	=> json_encode($array_lista_presentaciones),
				"tipo_unidad"			=> $tipo_unidad,
				"idpresentacion"		=> $idpresentacion,

				"p_unit_sin_igv"		=> $p_unit_sin_igv,
				"factor_igv_sunat"		=> $factor_igv,
				"peso_unitario"			=> $peso,
				"peso"					=> round(floatval($peso)*floatval($item->cantidad), 2)
			);
		}

		$tipo_doc_electronico = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $documento->id_tipodocumento)));
		$titulo_comprobante = 'La '.$tipo_doc_electronico->descripcion.' con N° : '.$numero_comprobante.', se pasará a una '.$nombre_nuevo_doc;

		$cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento->idcliente)));
		$texto_ubigeo = '';
		if($cliente->id_cod_ubigeo != '') {
			$sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $cliente->id_cod_ubigeo)));
			if($sql_ubigeo){
				$departamento = $sql_ubigeo->departamento;
				$provincia = $sql_ubigeo->provincia;
				$distrito = $sql_ubigeo->distrito;
				$texto_ubigeo = $departamento.' - '.$provincia.' - '.$distrito;
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['comprobante'] = $documento;
		$resp['detalle'] = $items_detalle;
		$resp['fecha_comprobante'] = $documento->fecha_comprobante;
		$resp['titulo_comprobante'] = $titulo_comprobante;
		$resp['cliente'] = $cliente;
		$resp['data_grid'] = $data_grid;
		$resp['texto_ubigeo'] = $texto_ubigeo;

		return $resp;
	}

	public function get_secret_data($id_contribuyente) {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No existe el ID de contribuyente';
			return $resp;
		}
		
		$secret_data = array();
		if($contribuyente->tipo_envio_sunat == 'produccion') {

			if($contribuyente->tipo_certificado == 'pse') {
				$secret_data['ruta_certificado'] = '';
				$secret_data['file_certificado'] = '';
				$secret_data['tipo_certificado'] = 'pse';
				$secret_data['ruc_pse'] = ''; //ruc del PSE
				$secret_data['pass_certificado'] = '';
				$secret_data['tipo_proceso'] = 'produccion';
				$secret_data['usuariosol'] = '';
				$secret_data['clavesol'] = '';
			} else if($contribuyente->tipo_certificado == 'pse_facturalaya') {
				$secret_data['ruta_certificado'] = '';
				$secret_data['file_certificado'] = '';
				$secret_data['tipo_certificado'] = 'pse_facturalaya';
				$secret_data['ruc_pse'] = ''; //ruc del PSE
				$secret_data['pass_certificado'] = '';
				$secret_data['tipo_proceso'] = 'produccion';
				$secret_data['usuariosol'] = '';
				$secret_data['clavesol'] = '';
			} else {
				$secret_data['ruta_certificado'] = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_certificado;
				if (!is_file($secret_data['ruta_certificado']) && is_file($secret_data['ruta_certificado'])) {
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error RUTA Certificado';
					$resp['mensaje'] = 'No se encuentra la ruta del certificado de producción';
					return $resp;
				}
				$secret_data['file_certificado'] = base64_encode(file_get_contents($secret_data['ruta_certificado']));
				$secret_data['pass_certificado'] = $contribuyente->pass_certificado;
				$secret_data['tipo_proceso'] = 'produccion';
				$secret_data['usuariosol'] = $contribuyente->usuario_sol;
				$secret_data['clavesol'] = $contribuyente->clave_sol;

				$secret_data['auth_api_sunat_ruc'] = $contribuyente->ruc;
				$secret_data['auth_api_sunat_usol'] = $contribuyente->sunat_u_sol_principal;
				$secret_data['auth_api_sunat_csol'] = $contribuyente->sunat_p_sol_principal;
				$secret_data['auth_api_sunat_id'] = $contribuyente->sunat_client_id;
				$secret_data['auth_api_sunat_secret'] = $contribuyente->sunat_client_secret;

			}

			$secret_data['ruta_dir_xml'] = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
			if (!file_exists($secret_data['ruta_dir_xml'])) {
				mkdir($secret_data['ruta_dir_xml'], 0777, true);
			}
			
			if($contribuyente->tipo_empresa_sunat == 'prico') {
				$secret_data['tipo_certificado'] = 'pse_facturalaya_ose';
				$secret_data['nombre_ose'] = $contribuyente->nombre_ose;
	
				$secret_data['usuario_sol_ose'] = $contribuyente->u_produccion_ose;
				$secret_data['pass_user_sol_ose'] = $contribuyente->c_produccion_ose;
				$secret_data['url_ose'] = $contribuyente->url_produccion_ose;
			}

		} else {
			$secret_data['ruta_certificado'] = $this->ruta_base_files.'/firmabeta.pfx';
			if (!file_exists($secret_data['ruta_certificado'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error RUTA Certificado';
				$resp['mensaje'] = 'No se encuentra la ruta del certificado';
				return $resp;
			}

			$secret_data['file_certificado'] = base64_encode(file_get_contents($secret_data['ruta_certificado']));

			$secret_data['ruta_dir_xml'] = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_prueba.'/';
			if (!file_exists($secret_data['ruta_dir_xml'])) {
				mkdir($secret_data['ruta_dir_xml'], 0777, true);
			}

			$secret_data['pass_certificado'] = '123456';
			$secret_data['tipo_proceso'] = 'prueba';
			$secret_data['usuariosol'] = 'MODDATOS';
			$secret_data['clavesol'] = 'moddatos';
			$secret_data['tipo_certificado'] = 'propio';

			if($contribuyente->tipo_empresa_sunat == 'prico') {
				$secret_data['tipo_certificado'] = 'pse_facturalaya_ose';
	
				$secret_data['nombre_ose'] = $contribuyente->nombre_ose;
	
				$secret_data['usuario_sol_ose'] = $contribuyente->u_prueba_ose;
				$secret_data['pass_user_sol_ose'] = $contribuyente->c_prueba_ose;
				$secret_data['url_ose'] = $contribuyente->url_prueba_ose;
			}
		}

		

		$resp['respuesta'] = 'ok';
		$resp['secret_data'] = $secret_data;
		return $resp;
	}

	public function get_numero_doc_electronico($id_contribuyente, $tipo_doc_electronico, $serie_comprobante, $idsucursal, $id_doc_modifica = '') {
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		//RECORDAR: las sucursales no deben tener la misma serie, en caso tengan la misma serie se creará un problema en la numeración, ya que la llave primaria de cada documento está compuesto de: id_contribuyente, id_tipodoc_electronico, serie_comprobante y tipo_envio_sunat, no interviene el id de la sucursal. Se hizo de esta manera para evitar huecos en la numeración!

		$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_doc_electronico, 'serie_comprobante' => $serie_comprobante, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat), "order" => "numero_comprobante DESC"));

		if(!$documento) {
			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $idsucursal)));
			
            if($tipo_doc_electronico == '07') { //NOTA DE CRÉDITO
                if($id_doc_modifica == '01') { //modifica una factura
                    return $sucursal->notacredito_factura_numero;
                }

                if($id_doc_modifica == '03') { //modifica una boleta
                    return $sucursal->notacredito_boleta_numero;
                }
            } else if($tipo_doc_electronico == '08') { //NOTA DE DÉBITO
                if($id_doc_modifica == '01') { //modifica una factura
                    return $sucursal->notadebito_factura_numero;
                }

                if($id_doc_modifica == '03') { //modifica una boleta
                    return $sucursal->notadebito_boleta_numero;
                }
            } else if($tipo_doc_electronico == '01') { //FACTURA
				return $sucursal->factura_numero;
            } else if($tipo_doc_electronico == '03') { //BOLETA DE VENTA
				return $sucursal->boleta_numero;
            } else if($tipo_doc_electronico == '09') { //GUÍA DE REMISIÓN
				return $sucursal->guia_remision_numero;
            }
		}
		
		$numero_doc = intval($documento->numero_comprobante) + 1;
		return $numero_doc;
	}

	public function registrar_payment_splits($contribuyente, $sucursal, $id_vendedor, $new_documento, $datapost) {
		
		$payment_splits = $datapost['payment_splits'];

		if(isset($new_documento->id_tipodoc_electronico)) {
			$id_tipodoc_electronico = $new_documento->id_tipodoc_electronico;
			$serie_comprobante = isset($new_documento->serie_comprobante)?$new_documento->serie_comprobante:'';
			$tipo_envio_sunat = $new_documento->tipo_envio_sunat;
		} else if(isset($new_documento->id_tipodocumento)) {
			$id_tipodoc_electronico = $new_documento->id_tipodocumento;
			$tipo_envio_sunat = $new_documento->modalidad;
			if($new_documento->id_tipodocumento == '77') {
				$serie_comprobante = 'NVEN';
			} else if ($new_documento->id_tipodocumento == '88') {
				$serie_comprobante = 'COTI';
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra el tipo para el documento destino';
				return $resp;
			}
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra el tipo para el documento destino';
			return $resp;
		}
		
		foreach($payment_splits as $pago) {
			$detalle_pago = new DocDetallepago();
			$detalle_pago->id_contribuyente 		= $contribuyente->id_contribuyente;
			$detalle_pago->id_tipodoc_electronico 	= $id_tipodoc_electronico;
			$detalle_pago->serie_comprobante 		= $serie_comprobante;
			$detalle_pago->numero_comprobante 		= $new_documento->numero_comprobante;
			$detalle_pago->tipo_envio_sunat 		= $tipo_envio_sunat;
			$detalle_pago->id_vendedor 				= $id_vendedor;
			$detalle_pago->id_sucursal 				= $sucursal->idsucursal;
			$detalle_pago->id_condicionpago 		= $pago['id_condicionpago'];
			$detalle_pago->id_codigomoneda 			= $new_documento->id_codigomoneda;
			$detalle_pago->tipo_cambio_sunat 		= $new_documento->tipo_cambio_sunat;
			$detalle_pago->total 					= $pago['total'];
			$detalle_pago->cpago_nrooperacion 		= isset($pago['cpago_nrooperacion']) ? $pago['cpago_nrooperacion'] : null;
			$detalle_pago->fecha_registro 			= date('Y-m-d H:i:s');
			$detalle_pago->fechadeposito 			= isset($pago['fechadeposito']) ? $pago['fechadeposito'] : null;
			$detalle_pago->idbanco 					= isset($pago['idbanco']) ? $pago['idbanco'] : null;
			$detalle_pago->detalle 					= isset($pago['detalle']) ? $pago['detalle'] : null;
			$detalle_pago->estado 					= 'activo';

			try {
				if(!$detalle_pago->save()) {
					$msg = '';
					foreach ($detalle_pago->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}

					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = $msg;
					return $resp;
				}
			} catch (Exception $e) {
				$this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $e->getMessage();
				return $resp;
			}
		}

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Ok';
		$resp['mensaje'] = 'Se registró correctamente los pagos';
		return $resp;
	}

	public function registrar_relacion_documento($contribuyente, $sucursal, $id_vendedor, $new_documento, $datapost) {
		if(!isset($datapost['documento_tipo_accion'])) {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Ok';
			$resp['mensaje'] = 'No se encuentra documento_tipo_accion';
			return $resp;
		}

		$accion = isset($datapost['documento_tipo_accion'])?$datapost['documento_tipo_accion']:'';
		if(empty($accion)) {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Ok';
			$resp['mensaje'] = 'No se encuentra documento_tipo_accion';
			return $resp;
		}

		if(!in_array($accion, array('transformar_cotizacion', 'transformar_nota_venta', 'duplicar_boleta', 'duplicar_factura', 'transformar_09_a_01', 'transformar_09_a_03', 'transformar_09_a_77', 'transformar_09_a_88'))) {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Ok';
			$resp['mensaje'] = 'No se encuentra un valor válido para documento_tipo_accion válido';
			return $resp;
		}

		if(empty($datapost['origen_id_contribuyente']) || empty($datapost['origen_id_tipodoc_electronico']) || empty($datapost['origen_numero_comprobante']) || empty($datapost['origen_tipo_envio_sunat'])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra origen_id_contribuyente, origen_id_tipodoc_electronico, origen_numero_comprobante';
			return $resp;
		}

		if(!in_array($datapost['origen_id_tipodoc_electronico'], array('01', '03', '07', '08', '09', '31', '88', '77'))) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra un valor válido para origen_id_tipodoc_electronico';
			return $resp;
		}

		if(in_array($datapost['origen_id_tipodoc_electronico'], array('01', '03', '07', '08', '09'))) {
			if(empty($datapost['origen_serie_comprobante'])) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra origen_serie_comprobante';
				return $resp;
			}
			
			$documento_origen = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $datapost['origen_id_tipodoc_electronico'], 'serie_comprobante' => $datapost['origen_serie_comprobante'], 'numero_comprobante' => $datapost['origen_numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		} else if($datapost['origen_id_tipodoc_electronico'] == '31') {
			$documento_origen = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodoc_electronico' => $datapost['origen_id_tipodoc_electronico'], 'serie_comprobante' => $datapost['origen_serie_comprobante'], 'numero_comprobante' => $datapost['origen_numero_comprobante'], 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat)));
		} else {
			if($datapost['origen_id_tipodoc_electronico'] == '77') { //Nota de Venta
				$datapost['origen_serie_comprobante'] = "NVEN";
			}

			if($datapost['origen_id_tipodoc_electronico'] == '88') { //cotización
				$datapost['origen_serie_comprobante'] = "COTI";
			}

			$documento_origen = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'id_tipodocumento' => $datapost['origen_id_tipodoc_electronico'], 'numero_comprobante' => $datapost['origen_numero_comprobante'], 'modalidad' => $contribuyente->tipo_envio_sunat)));
		}

		if(!$documento_origen) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra el documento origen';
			return $resp;
		}

		if(isset($new_documento->id_tipodoc_electronico)) {
			$destino_id_tipodoc_electronico = $new_documento->id_tipodoc_electronico;
			$destino_serie_comprobante = isset($new_documento->serie_comprobante)?$new_documento->serie_comprobante:'';
			$destino_tipo_envio_sunat = $new_documento->tipo_envio_sunat;
		} else if(isset($new_documento->id_tipodocumento)) {
			$destino_id_tipodoc_electronico = $new_documento->id_tipodocumento;
			$destino_tipo_envio_sunat = $new_documento->modalidad;
			if($new_documento->id_tipodocumento == '77') {
				$destino_serie_comprobante = 'NVEN';
			} else if ($new_documento->id_tipodocumento == '88') {
				$destino_serie_comprobante = 'COTI';
			} else {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'error';
				$resp['mensaje'] = 'No se encuentra el tipo para el documento destino';
				return $resp;
			}
		} else {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'error';
			$resp['mensaje'] = 'No se encuentra el tipo para el documento destino';
			return $resp;
		}
		
		//editar, editar_cotizacion
		if(in_array($accion, array('transformar_cotizacion', 'transformar_nota_venta', 'duplicar_boleta', 'duplicar_factura', 'transformar_09_a_01', 'transformar_09_a_03', 'transformar_09_a_77', 'transformar_09_a_88'))) {
			$origen_id_tipodoc_electronico = $datapost['origen_id_tipodoc_electronico'];
			$origen_serie_comprobante = $datapost['origen_serie_comprobante'];
			$origen_numero_comprobante = $datapost['origen_numero_comprobante'];

			$relacion_documento = new DocRelacion();
			$relacion_documento->origen_id_contribuyente = $contribuyente->id_contribuyente;
			$relacion_documento->origen_id_tipodoc_electronico = $datapost['origen_id_tipodoc_electronico'];
			$relacion_documento->origen_serie_comprobante = $datapost['origen_serie_comprobante'];
			$relacion_documento->origen_numero_comprobante = $datapost['origen_numero_comprobante'];
			$relacion_documento->origen_tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
			$relacion_documento->fecha_registro = date('Y-m-d H:i:s');
			$relacion_documento->accion = $accion;
			$relacion_documento->id_vendedor = $id_vendedor;
			$relacion_documento->destino_id_contribuyente = $new_documento->id_contribuyente;
			$relacion_documento->destino_id_tipodoc_electronico = $destino_id_tipodoc_electronico;
			$relacion_documento->destino_serie_comprobante = $destino_serie_comprobante;
			$relacion_documento->destino_numero_comprobante = $new_documento->numero_comprobante;
			$relacion_documento->destino_tipo_envio_sunat = $destino_tipo_envio_sunat;

			$nombre_doc_origen = '';
			$nombre_doc_serie_number_origen = '';
			if($origen_id_tipodoc_electronico == '01') {
				$nombre_doc_origen = 'FACTURA';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '03') {
				$nombre_doc_origen = 'BOLETA';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '07') {
				$nombre_doc_origen = 'NOTA DE CRÉDITO';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '08') {
				$nombre_doc_origen = 'NOTA DE DÉBITO';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '09') {
				$nombre_doc_origen = 'GUÍA REMISIÓN';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '31') {
				$nombre_doc_origen = 'GUÍA TRANSPORTISTA';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '77') {
				$nombre_doc_origen = 'NOTA DE VENTA';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else if($origen_id_tipodoc_electronico == '88') {
				$nombre_doc_origen = 'COTIZACIÓN';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			} else {
				$nombre_doc_origen = 'DOCUMENTO';
				$nombre_doc_serie_number_origen = $nombre_doc_origen." $relacion_documento->origen_serie_comprobante-$relacion_documento->origen_numero_comprobante";
			}
			
			$nombre_doc_destino = '';
			$nombre_doc_serie_number_destino = '';
			if($destino_id_tipodoc_electronico == '01') {
				$nombre_doc_destino = 'FACTURA';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '03') {
				$nombre_doc_destino = 'BOLETA';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '07') {
				$nombre_doc_destino = 'NOTA DE CRÉDITO';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '08') {
				$nombre_doc_destino = 'NOTA DE DÉBITO';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '09') {
				$nombre_doc_destino = 'GUÍA REMISIÓN';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '31') {
				$nombre_doc_destino = 'GUÍA TRANSPORTISTA';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '77') {
				$nombre_doc_destino = 'NOTA DE VENTA';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else if($destino_id_tipodoc_electronico == '88') {
				$nombre_doc_destino = 'COTIZACIÓN';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			} else {
				$nombre_doc_destino = 'DOCUMENTO';
				$nombre_doc_serie_number_destino = $nombre_doc_destino." $relacion_documento->destino_serie_comprobante-$relacion_documento->destino_numero_comprobante";
			}

			$descripcion = "La $nombre_doc_serie_number_origen se ha tomado como base para crear la $nombre_doc_serie_number_destino";
			$relacion_documento->descripcion = $descripcion;

			try {
				if(!$relacion_documento->save()) {
					$msg = '';
					foreach ($relacion_documento->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'No se pueden guardar los cambios!';
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'Excepción capturada: '.$e->getMessage();
				return $resp;
			}

			if(isset($datapost['anular_doc_origen']) && $datapost['anular_doc_origen'] == 'si') {
				if($datapost['origen_id_tipodoc_electronico'] == '77' || $datapost['origen_id_tipodoc_electronico'] == '88') { //cotización
					$data_anulacion_doc['idsucursal'] = $documento_origen->id_sucursal;
					$data_anulacion_doc['id_tipodocumento'] = $datapost['origen_id_tipodoc_electronico'];
					$data_anulacion_doc['numero_comprobante'] = $datapost['origen_numero_comprobante'];
					$data_anulacion_doc['confirmacion'] = 'si';
	
					$reporte_documento = new ReportedocumentosController;
					$resp_anulacion = $reporte_documento->anular_doc_no_oficial($data_anulacion_doc, $contribuyente->id_contribuyente, $contribuyente->tipo_envio_sunat, $id_vendedor);
					if($resp_anulacion['respuesta'] == 'error') {
						return $resp_anulacion;
					}
				}
			}

			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Ok';
			$resp['mensaje'] = 'Se ha registrado la relación de documentos';
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Ok';
		$resp['mensaje'] = 'No se ha registrado la relación de documentos';
		return $resp;
	}
}
?>