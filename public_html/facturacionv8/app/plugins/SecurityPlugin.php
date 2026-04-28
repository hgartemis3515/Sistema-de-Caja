<?php
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Component;
use Phalcon\Acl\Enum as AclEnum;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Session\Manager as SessionManager;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Injectable {

	public function getAcl() {
		// Verifica si el ACL ya está almacenado en persistencia
		if (isset($this->persistent->acl)) {
			return $this->persistent->acl;
		}
	
		// Crear el objeto ACL
		$acl = new AclList();
		$acl->setDefaultAction(AclEnum::DENY);
		
		$roles = array(
			'super_admin' 				=> new Role('super_admin'),
			'super_soporte' 			=> new Role('super_soporte'),
			'patrocinador' 				=> new Role('patrocinador'),
			'contribuyente_admin' 		=> new Role('contribuyente_admin'),
			'contribuyente_vendedor' 	=> new Role('contribuyente_vendedor'),
			'guest'						=> new Role('guest')
		);

		//registramos todos los roles en el ACL
		foreach ($roles as $role) {
			$acl->addRole($role);
		}
		
		/* ÁREAS DEL SUPER ADMINISTRADOR */
		$Super_adminAreas = array(
			'areaadministracion'	=> array('index')
		);

		foreach ($Super_adminAreas as $resource => $actions) {
			$acl->addComponent(new Component($resource), $actions);
		}
		
		/* ÁREAS DEL SUPER SOPORTE */
		$Super_soporteAreas = array(
			'clientearea' 				=> array('index'),
			'dominiopersonalizado' 		=> array('index', 'guardar'),
			'gestiondecontribuyentes' => array('asignar_p_s', 'asignar_pse_facturalaya', 'index', 'guardar', 'get_data_api_busquedas', 'get_patrocinadores', 'get_data_contribuyente', 'configuracion', 'get_lista_contribuyentes', 'get_membresia', 'guardar_membresia', 'guardar_fecha_suscripcion', 'guardar_expira_certificado', 'convertir_en_jefe_grupo', 'cambiar_tipo_empresa_sunat', 'quitar_pse_facturalaya', 'ver_estadisticas_contribuyente', 'get_lista_modulos_sistema', 'guardar_system_option_modulo', 'videotutoriales'),
			'gestiondeplanbase'			=> array('index','save','get_lista_planbase','get_data_planbase','eliminar_plan'), //ya está modificado
			'gestiondeplantillas' 		=> array('index', 'save', 'get_list_plantilla', 'get_data_plantilla','eliminar_plantilla', 'preview_template'), //ya está modificado
			'suscripcion' 				=> array('index'),
			'dashboardadmin' 			=> array('index', 'get_lista_docs_sunat', 'verificar_estado_sunat', 'modificar_docelectronico'), //ya está modificado
			'gestiondedocumentos'		=> array('index', 'get_lista_docs_sunat', 'verificar_estado_sunat', 'modificar_docelectronico', 'enviar_documento_sunat', 'procesar_facturas_notas', 'crear_resumen_individual', 'procesar_boletas_notas', 'consultar_ticket', 'ignorar_documento', 'guardar_fecha_documento', 'proceso_total_doc_sunat', 'proceso_validacion_cpe'), //ya está modificado
			'recuperacion' 				=> array('index'),

			'gestionplantillaspdf' 		=> array('index', 'guardar_plantilla', 'get_lista_plantillaspdf','get_data_plantillapdf','eliminar_plantillapdf'), //ya está modificado
			'gestionpersonalizarsistema' => array('index', 'guardar_diseno', 'get_lista_diseno','get_data_diseno','eliminar_diseno'),
			'gestionunidades' 			=> array('index','guardar_unidades', 'get_lista_unidades', 'get_data_unidades'),
			'administracionproductos' 	=> array('index','get_list_contribuyente'), //ya está modificado
			'prueba'					=> array('copiar_productos', 'prueba_permisos_usuario', 'get_txt_validacion_lote_sunat') //ya está modificado
		);

		foreach ($Super_soporteAreas as $resource => $actions) {
			$acl->addComponent(new Component($resource), $actions);
		}

		/* ÁREAS DEL PATROCINADOR */
		$Contribuyente_patrocinadorAreas = array(
			'areaadministracion'		=> array('index'),
			'configcompany' 			=> array('index','get_data_contribuyente', 'guardar'), //ya está modificado
			'gestiondecontribuyentes' 	=> array('index', 'guardar', 'get_data_api_busquedas', 'get_patrocinadores', 'get_data_contribuyente', 'configuracion', 'get_lista_contribuyentes', 'get_membresia', 'guardar_membresia', 'get_planes_personalizados', 'get_info_plan_base', 'guardar_plan_reseller', 'get_data_planreseller', 'get_lista_planes_suscripcion', 'get_info_plan_suscripcion', 'agregar_suscripcion_empresa', 'get_lista_suscripciones', 'get_lista_inactivos', 'ver_estadisticas_contribuyente') //ya está modificado
		);

		foreach ($Contribuyente_patrocinadorAreas as $resource => $actions) {
			$acl->addComponent(new Component($resource), $actions);
		}

		/* ÁREAS DEL CONTRIBUYENTE ADMINISTRADOR */
		$Contribuyente_adminAreas = array(
			'registradocliente' 		=> array('index'), //ya está modificado
			'download' 					=> array('sysfacturacion', 'themescpe', 'themeadmin'), //ya está modificado
			'configcompany' 			=> array('index','get_data_contribuyente', 'guardar', 'generar_token_contribuyente', 'eliminar_logo'), //ya está modificado
			'reportes' 					=> array('index', 'crear_reporte', 'libro_ecompras', 'get_detallecompras', 'generar_txt_libcompras', 'libro_eventas', 'generar_txt_libventas','reporte_detallado', 'get_reportedetallado', 'reporte_detallado_compras', 'actualizar_tipo_cambio_ventas', 'actualizar_tipo_cambio_compras', 'get_reporte_guias', 'get_reporte_general_ventas'), //se repite en vendedor
			'branchoffice' 				=> array('index','insert', 'get_sucursal', 'lista_sucursales', 'cambiar_estado_sucursal', 'get_lista_sucursales', 'get_plantillas_user', 'guardar_opcion_items', 'guardar_opcion_modifica_stock'), //ya está modificado
			'gestionuser' 				=> array('index','insert', 'get_lista_usuarios', 'eliminar_usuario', 'get_data_usuario'), //ya está modificado
			'gestiondecontribuyentes' 	=> array('index', 'guardar', 'get_data_api_busquedas', 'get_patrocinadores', 'get_data_contribuyente', 'configuracion', 'get_lista_contribuyentes', 'get_membresia', 'guardar_membresia'),
			'listacontribuyentes' 		=> array('index'),
			'listbranchoffice' 			=> array('index'),
			'dashboard' 				=> array('index', 'get_datos_estadisticos', 'get_lista_doc_pendientes', 'verificar_estado_sunat', 'modificar_docelectronico', 'save_config_modalidad_pago', 'download_plantilla_cpe'), //ya está modificado
			'gestioncuentadebanco'		=> array('index','save','get_lista_cuenta','get_data_cuenta','eliminar_cuenta'), //ya está modificado
			'gestioncondiciondepago'	=> array('index','save','get_lista_condicionpago','get_data_condicionpago','eliminar_condicionpago'), //ya está modificado
			'miwebsite' 				=> array('index', 'saveimage', 'get_templates', 'guardar_data', 'get_paginas', 'crear_nueva_pagina', 'eliminar_pagina', 'cambiar_home'),
			'editorweb' 				=> array('index', 'view_edit_page', 'get_data_page', 'guardar_pagina'), //ya está modificado
			'producto'					=> array('export_to_excel', 'export_to_update', 'actualizar_productos', 'update_cell'), //ya está modificado
			'importacioncpe' 			=> array('plantilla_importacion_cpe', 'iniciar_proceso_importacion'), //ya está modificado
			'gestiondeetiquetas' 		=> array('guardar_etiqueta', 'eliminar_etiqueta', 'get_lista_etiquetas', 'asignar_etiquetaxdocumento'), //ya está modificado
			'personalizaciondesistema' 	=> array('index', 'save_color','deshacer_diseno','save_template_login','save_template_registro', 'get_data_personalizacion','save_mensaje_sub','guardar_logo_dominios', 'guardar_imagen_servidor', 'save_background_login', 'save_background_registro', 'guardar_dominio_logos', 'guardar_color_base_sistema', 'reiniciar_colores_base'), //ya está modificado
			'widgets'					=> array('index'),
		);

		foreach ($Contribuyente_adminAreas as $resource => $actions) {
			$acl->addComponent(new Component($resource), $actions);
		}

		/* ÁREAS DEL CONTRIBUYENTE VENDEDOR */
		$Contribuyente_vendedorAreas = array(
			'producto' 				=> array('index', 'listaproductos', 'insert', 'get_data_producto','eliminar_producto', 'get_lista_productos', 'get_lista_categorias', 'importar_productos', 'registrar_salida_producto', 'registrar_ingreso_producto', 'iniciar_traslado', 'imprimir_codigobarras', 'get_sugerencias_marcas', 'update_cell', 'descargar_plantilla_importacion', 'iniciar_proceso_transformacion', 'get_stock_varias_sucursales'), //ya está modificado
			'importacionproductos' 	=> array('importar_productos'), //ya está modificado
			'category' 				=> array('index','insert', 'get_lista_categorias', 'eliminar_categoria', 'get_data_categoria', 'get_sugerencias_categorias'), //ya está modificado
			'client' 				=> array('index','insert', 'get_lista_clientes', 'eliminar_cliente', 'get_data_cliente', 'get_lista_sugerencias_clientes', 'descargar_plantilla_importacion', 'importar_clientes'), //ya está modificado
			'reportedocumentos' 	=> array('index', 'get_lista_documentos', 'enviar_documento_sunat', 'get_notas_de_venta', 'get_cotizaciones', 'get_lista_guias_remision', 'anular_doc_no_oficial', 'get_lista_guias_transportista'), //ya está modificado
			'resumendeboletas' 		=> array('index', 'get_lista_facturas', 'crear_resumen_boletas', 'get_lista_resumenes', 'get_items_resumen_creado', 'consultar_ticket', 'crear_resumen_individual'), //ya está modificado
			'listvoided' 			=> array('index', 'index2'),
			'resetpassword' 		=> array('index'),
			'herramientas' 			=> array('generartoken', 'get_lista_sucursales', 'get_data_cliente', 'get_data_sucursal', 'get_sugerencias_producto', 'get_sugerencias_docelectronico', 'saveimage', 'save_user', 'get_sugerencias_clientes', 'get_sugerencias_clientes_mejorado', 'get_lista_usuarios', 'get_data_proveedor', 'enviar_cpe_email', 'get_sugerencias_ubigeos', 'verimageprod', 'guardar_imagen_producto', 'get_sugerencias_proveedor'), //ya está modificado
			'documentoelectronico' 	=> array('index', 'guardar_documento', 'get_documento_electronico', 'get_data_doc'), //ya está modificado
			'apisunat' 				=> array('get_tipo_cambio', 'get_lista_ubigeos', 'get_lista_monedas', 'get_unidadesmedida', 'get_lista_tipoafectacionigv', 'get_codigodetraccion', 'get_lista_tipo_doc_identidad', 'get_sunat_tiponotacredito', 'get_sunat_tiponotadebito', 'get_lista_codprod_segmentos', 'get_lista_codprod_codigoproducto', 'get_lista_sugerencias_ubigeo', 'get_tipo_cambio_by_date'), //ya está modificado
			'profile' 				=> array('index','save', 'save_email', 'save_password', 'get_lista_suscripcion'), //ya está modificado
			'comunicaciondebaja' 	=> array('index', 'get_info_documento', 'crear_comunicacion_baja'), //ya está modificado
			'guiaderemision' 		=> array('index', 'get_empresastransporte', 'get_lista_conductores_autos', 'get_documentos_cliente', 'guardar_guia_remision', 'sugerencias_transportista', 'sugerencias_direcciones_llegada', 'iniciar_importacion_items'), //ya está modificado
			'empresasdetransporte' 	=> array('index', 'get_data_empresa', 'get_lista_empresa', 'eliminar_empresa', 'save', 'eliminar_conductor','eliminar_vehiculo'), //ya está modificado
			'elegirplansuscripcion'  => array('index'),
			'reportes' 				=> array('index', 'crear_reporte', 'libro_ecompras', 'get_detallecompras', 'generar_txt_libcompras', 'libro_eventas', 'generar_txt_libventas','reporte_detallado', 'get_reportedetallado', 'reporte_productosvendidos', 'get_reporteproductosvendidos', 'top_vendedores', 'top_clientes', 'get_top_vendedores', 'get_top_clientes', 'get_detalle_docs_by_idcliente', 'get_detalle_docs_by_idvendedor', 'generar_excel_libventas', 'generar_excel_ventas_ejb', 'liquidacion_impuesto_mensual', 'get_liquidacion_mensual', 'get_formato_cm', 'get_asiento_contable', 'get_reporte_detallado_compras', 'reporte_detallado_compras', 'get_reporte_guias', 'get_reporte_general_ventas', 'consolidado_ventas_producto', 'get_reporte_consolidado_productos', 'get_formato_importacion_guiaremision'), //ya está modificado
			'dashboard' 			=> array('index', 'get_datos_estadisticos', 'guardar_condicion_pago'), //ya está modificado
			'centrodeentrenamiento' => array('index'),
			'gestiondecompras' 		=> array('index', 'registrarcompra', 'guardar_compra', 'get_lista_compras', 'anular_compra', 'get_datos_estadisticos', 'get_detalle_compra', 'get_data_cpe', 'get_top_proveedores', 'procesar_xml_cpe'), //ya está modificado
			'notificacion' 			=> array('index', 'get_lista_doc_pendientes', 'notificaciones'), //ya está modificado
			'gestiondeproveedores' 	=>  array('index','insert', 'get_lista_proveedores', 'eliminar_proveedores', 'get_data_proveedores', 'get_lista_sugerencias_proveedor'), //ya está modificado
			'reportekardex' 		=>  array('index', 'kardexdeproducto', 'get_kardex_producto', 'get_inv_valorizado_sunat_prod'), //ya está modificado
			'estadosuscripcion' 	=> array('index'),
			'cajachica' 			=> array('index','save','get_lista_movimientos', 'get_data_movimiento', 'delete', 'get_totales_ventas',  'download_ticket', 'detallecajachica', 'get_hoja_liquidacion', 'imprimir_ticket_movimiento', 'get_reporte_exel_ingresos_egresos'), //ya está modificado
			'gestiondeprospectos' 	=> array('index','prueba', 'save','get_data_prospecto','get_lista_prospectos', 'eliminar_prospecto', 'agregar_etiqueta', 'eliminar_etiqueta','agregar_telefono','agregar_correo','get_data_nota', 'guardar_nota'), //ya está modificado
			'cuentasporcobrar'		=> array('index', 'get_lista_cpe', 'get_lista_notaventa', 'get_lista_abonos', 'guardar_abono', 'eliminar_abono', 'resetear_cuota', 'get_reporte_excel_cpe', 'get_reporte_excel_nventas', 'get_totales_monto_por_cobrar'), //ya está modificado
			'cuentasporpagar'		=> array('index', 'get_lista_cpe', 'get_lista_abonos', 'crear_abono_pago'), //	ya está modificado
			'download'				=> array('ticket_abono', 'download_cotisiscompleto', 'download_cotiservfact'),
			'systempos' 			=> array('index', 'get_productos', 'destacar_producto', 'validacion', 'get_items_cliente', 'get_items_categoria', 'get_items_cuentabanco', 'get_items_sunatcodigoubigeo', 'get_total_cliente', 'get_total_categoria', 'get_total_cuentabanco', 'get_total_sunatcodigoubigeo', 'get_total_condiciondepago', 'get_items_condiciondepago', 'buscar_cliente', 'actualizar_cliente', 'get_total_sunat_tipoafectacionigv', 'get_items_sunat_unidadmedida', 'get_items_sunat_tipoafectacionigv', 'get_total_sunat_unidadmedida', 'get_total_usuario', 'get_items_usuario', 'procesar_venta_carrito'),
			'pruebacolores' 		=> array('index', 'save','get_color','deshacer_diseno'), //ya está modificado
			'gestiondeetiquetas' 	=> array('get_lista_etiquetas', 'asignar_etiquetaxdocumento'), //ya está modificado
			'productomovimientos' 	=> array('get_lista_movimientos', 'print_pdf'), //ya está modificado
			'gestionrestaurante'	=> array('index','dashboard','iniciarcuenta','cocina','reserva','gestioncocina', 'gestionreservas', 'reserva2'),
			'creargremasivo' 		=> array('index', 'plantilla_crear_gre_masivo', 'iniciar_proceso_creacion_gre'), //ya está modificado
			'guiatransportista'		=> array('index', 'sugerencias_destinatario', 'sugerencias_conductor', 'sugerencias_datos_transportista', 'guardar_guia_transportista', 'sugerencias_remitente'), //ya está modificado
			'ordendecompra'			=> array('anular_orden_compra'), //ya está modificado
			'sire'					=> array('index', 'ventas', 'compras', 'guardar_data_acceso_sunat'), //ya está modificado
			'sireventas'			=> array('index', 'sire_ventas_lista_periodos', 'sire_ventas_get_ticket', 'sire_ventas_consultar_y_descargar_propuesta', 'download_txt_rvie', 'txt_reemplazar_propuesta_rvie'), //ya está modificado
			'sirecompras'			=> array('index', 'sire_compras_lista_periodos', 'sire_compras_get_ticket', 'sire_compras_consultar_y_descargar_propuesta', 'download_txt_rce'), //ya está modificado
			'facturalayacontabilidad' => array('index', 'get_lista_comprobantes', 'guardar_deposito', 'get_lista_depositos', 'validar_deposito', 'anular_ingreso_contabilidad'), //ya está modificado
		);

		foreach ($Contribuyente_vendedorAreas as $resource => $actions) {
			$acl->addComponent(new Component($resource), $actions);
		}

		//ya está todo modificado.
		$GuestAreas = array(
			'herramientas'			=> array('verimage', 'imageclie', 'logo_empresa_v1', 'logo_empresa_v2', 'verimagepersonalizacion', 'verimageprod'), //	ya está modificado
			'index'					=> array('index'),
			'errors'				=> array('show401', 'show404', 'show500'),
			'login'					=> array('index','session', 'logout', 'end','resetpassword', 'recoverpassword','recovery','newpassword','change_password', 'registrar_usuario', 'cambiar_password', 'inicio_sesion_remoto', 'login_remoto'), //ya está modificado
			'consultas'				=> array('index', 'consultar'),
			'download' 				=> array('downloadpdf', 'downloadcpe', 'resumen', 'verpdf'),
			'registro'				=> array('index'),
			'prueba'				=> array('index', 'parametros'),
			'terminoslegales'		=> array('index', 'terminos_del_servicio'), //ya está modificado
			'reset_password'		=> array('index'),
			'registrarse' 			=> array('index'),
			'pruebaguion'			=> array('index'),
			'suscripcion' 			=> array('index'),
			'api'					=> array('index', 'procesar_venta', 'buscar_data_cliente', 'get_producto', 'get_productos', 'procesar_notacredito', 'procesar_notadebito', 'procesar_guia_remision', 'procesar_nota_venta', 'procesar_cotizacion', 'get_num_productos', 'enviar_cpe_email', 'comunicacion_baja', 'user_login', 'registrar_contribuyente', 'get_data_cpe', 'get_libro_eventas', 'get_detalle_cpe', 'buscar_productos'), //ya está modificado
			'printpdf'				=> array('index', 'prueba'),
			'printpdfcompra' 		=> array('index'),
			'codigosdeerrorsunat'	=> array('index', 'get_lista_errores'), //ya está modificado
			'apiapp'				=> array('index', 'login', 'refresh_token', 'get_user_info', 'get_product_list', 'get_data_base', 'procesar_venta', 'procesar_nota_venta', 'procesar_cotizacion', 'procesar_notacredito', 'procesar_notadebito', 'procesar_guia_remision', 'get_lista_cpe', 'buscar_data_cliente', 'get_stats_totals', 'get_totales_venta', 'get_entradas_salidas', 'get_detalle_caja_chica', 'get_tipo_cambio', 'get_version', 'get_lista_condicion_pago', 'get_monto_icbper', 'get_data_socio_estrategico', 'get_lista_vendedores', 'get_lista_sucursales', 'get_lista_etiquetas', 'get_cuentas_banco', 'get_cuenta_detraccion', 'get_sunat_tipo_operacion', 'get_sunat_tipo_doc_electronico', 'get_sunat_codigo_detraccion', 'get_sunat_tipo_doc_identidad', 'get_sunat_moneda', 'get_sunat_unidad_medida', 'get_sunat_tipo_nota_debito', 'get_sunat_tipo_nota_credito', 'get_sunat_tipo_afectacion_igv', 'get_sunat_motivo_traslado', 'get_sunat_codigo_ubigeo', 'get_sunat_codigo_puerto', 'get_sunat_tipo_cambio', 'get_sunat_codigo_entidad_financiera', 'get_sunat_codigo_precio', 'get_sunat_codigo_retorno', 'get_sunat_codigo_tipo_percepcion', 'get_sunat_icbper', 'get_sunat_medios_de_pago', 'get_sunat_modalidad_traslado', 'get_sunat_tipo_regimen'), //ya está modificado
			'apigooglecloudstorage' => array('index', 'prueba'),
			'videotutoriales' 		=> array('index')
		);

		foreach ($GuestAreas as $resource => $actions) {
			$acl->addComponent(new Component($resource), $actions);
		}

		//otorgando acceso a todos los usuarios a las areas para visitantes
		foreach($roles as $role){
		    foreach($GuestAreas as $resource => $actions){
		    	foreach ($actions as $action){
					$acl->allow($role->getName(), $resource, $action);
				}
		    }
		}
		
		//Damos Acceso a las áreas de Super_Admin solo a los super_admin
		foreach ($Super_adminAreas as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('super_admin', $resource, $action);
			}
		}

		//Damos Acceso a las áreas de Patrocinador tanto a los super_admin y a los super_soporte
		foreach ($Contribuyente_patrocinadorAreas as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('super_admin', $resource, $action);
				$acl->allow('super_soporte', $resource, $action);
				$acl->allow('patrocinador', $resource, $action);
			}
		}

		//Damos Acceso a las áreas de super_soporte tanto a los super_admin como a los super_soporte
		foreach ($Super_soporteAreas as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('super_admin', $resource, $action);
				$acl->allow('super_soporte', $resource, $action);
				$acl->allow('patrocinador', $resource, $action);
			}
		}

		//Damos Acceso a las áreas de usuarioregistrado a todos los usuarios registrados y también a los clientes y admin.
		foreach ($Contribuyente_adminAreas as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('super_admin', $resource, $action);
				$acl->allow('super_soporte', $resource, $action);
				$acl->allow('patrocinador', $resource, $action);
				$acl->allow('contribuyente_admin', $resource, $action);
			}
		}

		//Damos Acceso a las áreas de contribuyente_vendedor a todos los usuarios vendedores
		foreach ($Contribuyente_vendedorAreas as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('super_admin', $resource, $action);
				$acl->allow('super_soporte', $resource, $action);
				$acl->allow('patrocinador', $resource, $action);
				$acl->allow('contribuyente_admin', $resource, $action);
				$acl->allow('contribuyente_vendedor', $resource, $action);
			}
		}

		//Damos Acceso a las áreas de guest o visitantes a los siguientes roles: admin, cliente, registrado, guest
		foreach ($GuestAreas as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('super_admin', $resource, $action);
				$acl->allow('super_soporte', $resource, $action);
				$acl->allow('patrocinador', $resource, $action);
				$acl->allow('contribuyente_admin', $resource, $action);
				$acl->allow('contribuyente_vendedor', $resource, $action);
				$acl->allow('guest', $resource, $action);
			}
		}

		$this->persistent->acl = $acl;
		return $this->persistent->acl;
	}

	/**
	 * El ACL usa slugs fijos (contribuyente_admin, …). En rol_usuario suele guardarse nombre legible
	 * (“Administrador”) o el seed local usa alias distintos; hay que mapear antes de isAllowed().
	 */
	private function resolveAclRoleName(?array $auth): string {
		if (!is_array($auth) || $auth === []) {
			return 'guest';
		}
		$raw = isset($auth['rol']) && is_string($auth['rol']) ? trim($auth['rol']) : '';
		if ($raw === '') {
			return 'guest';
		}
		$known = [
			'super_admin', 'super_soporte', 'patrocinador',
			'contribuyente_admin', 'contribuyente_vendedor', 'guest',
		];
		if (in_array($raw, $known, true)) {
			return $raw;
		}
		$alias = isset($auth['rol_alias']) && is_string($auth['rol_alias']) ? strtolower(trim($auth['rol_alias'])) : '';
		$byAlias = [
			'admin' => 'contribuyente_admin',
			'vendedor' => 'contribuyente_vendedor',
			'admin_emp' => 'contribuyente_admin',
		];
		if ($alias !== '' && isset($byAlias[$alias])) {
			return $byAlias[$alias];
		}
		$byLowerName = [
			'administrador' => 'contribuyente_admin',
			'vendedor' => 'contribuyente_vendedor',
			'administrador empresa' => 'contribuyente_admin',
		];
		$key = strtolower($raw);
		if (isset($byLowerName[$key])) {
			return $byLowerName[$key];
		}
		if (isset($auth['id_rol'])) {
			$id = (int) $auth['id_rol'];
			if ($id === 1) {
				return 'contribuyente_admin';
			}
			if ($id === 2) {
				return 'contribuyente_vendedor';
			}
			if ($id === 3) {
				return 'contribuyente_admin';
			}
		}
		return 'guest';
	}

	//Para la versión anterior de phalcon se utilizaba: public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
		
		//creamos una instancia de logger
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		if ($controller === null || $controller === '' || $controller === false) {
			$dispatcher->setControllerName('index');
			$dispatcher->setActionName('index');
			$controller = 'index';
			$action = 'index';
		}
		if ($action === null || $action === '' || $action === false) {
			$dispatcher->setActionName('index');
			$action = 'index';
		}
		$controller = strtolower((string) $controller);
		$action = strtolower((string) $action);
		
		$auth = $this->session->get('authv8');
		if($controller == 'login' && in_array($action, array('logout', 'inicio_sesion_remoto', 'login_remoto'))) {
			
		} else {
			if(!$auth) {
				if ($this->cookies->has('cookieFacturalaYa_060300')) {
					$rememberMeCookie = $this->cookies->get('cookieFacturalaYa_060300');
					$texto_encriptado = $rememberMeCookie->getValue();
					$herramientas = new HerramientasController;
					$texto_desencriptado = $herramientas->desencriptar($texto_encriptado);
					$dia_actual = date('dmY');
					$array_datos = explode("||", $texto_desencriptado);
					$id_usuario = isset($array_datos[0])?$array_datos[0]:0;
					$dia_actual_cookie = isset($array_datos[1])?$array_datos[1]:'';
					//if($dia_actual == $dia_actual_cookie) {
						$user = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $id_usuario)));
						if($user) {
							$login = new LoginController;
							$login->crear_nueva_session($user, false);
						}
					//}
				}
	
				$auth = $this->session->get('authv8');
			}
		}
		
		if (!$auth) {
			$role = 'guest';
		} else {
			$role = $this->resolveAclRoleName($auth);
		}
		
		$acl = $this->getAcl();
		
		if (!$acl->isComponent($controller)) {
			
			$dispatcher->forward([
				'controller' => 'errors',
				'action'     => 'show404'
			]);
			return false;
		}
		
		$allowed = $acl->isAllowed($role, $controller, $action);
		
		if (!$allowed) {
			$dispatcher->forward(array(
				'controller' => 'errors',
				'action'     => 'show401'
			));
			return false;
		}
	}
}
?>