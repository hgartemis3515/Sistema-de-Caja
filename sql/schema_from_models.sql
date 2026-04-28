SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` INT(11) NOT NULL AUTO_INCREMENT,
  `codigo` TEXT,
  `nombre` TEXT,
  `descripcion` MEDIUMTEXT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_codigoproducto` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  `codigo_cuenta_contable` TEXT,
  `codigo_centro_costo` TEXT,
  `codigo_presupuesto` TEXT,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cliente` (
  `idcliente` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodocidentidad` TEXT,
  `codigo` TEXT,
  `num_doc` TEXT,
  `razon_social` TEXT,
  `nombre_comercial` TEXT,
  `direccion_fiscal` TEXT,
  `id_cod_ubigeo` TEXT,
  `email` TEXT,
  `celular` TEXT,
  `telefono` TEXT,
  `num_cuenta_detraccion` TEXT,
  `detalle_adicional` MEDIUMTEXT,
  `fecha_registro` TEXT,
  `fecha_nac` TEXT,
  `sexo` TEXT,
  `foto` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`idcliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `compra` (
  `id_compra` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idsucursal` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_proveedor` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `fecha_comprobante` TEXT,
  `estado` TEXT,
  `total_gravadas` DECIMAL(18,6) DEFAULT NULL,
  `total_inafecta` DECIMAL(18,6) DEFAULT NULL,
  `total_exoneradas` DECIMAL(18,6) DEFAULT NULL,
  `total_gratuitas` DECIMAL(18,6) DEFAULT NULL,
  `total_exportacion` DECIMAL(18,6) DEFAULT NULL,
  `total_descuento` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_descuento_total` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_igv` DECIMAL(18,6) DEFAULT NULL,
  `total_igv` DECIMAL(18,6) DEFAULT NULL,
  `total_isc` DECIMAL(18,6) DEFAULT NULL,
  `total_icbper` DECIMAL(18,6) DEFAULT NULL,
  `total_otr_imp` DECIMAL(18,6) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `nro_guia_remision` INT(11) DEFAULT NULL,
  `cod_guia_remision` TEXT,
  `nro_otr_comprobante` TEXT,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `id_usuario` INT(11) DEFAULT NULL,
  `nota` TEXT,
  `fecha_vto_comprobante` TEXT,
  `id_tipo_comprobante_modifica` TEXT,
  `serie_documento_modifica` TEXT,
  `nro_documento_modifica` INT(11) DEFAULT NULL,
  `id_cod_tipomotivo_credito` TEXT,
  `descripcion_motivo_credito` MEDIUMTEXT,
  `id_cod_tipomotivo_debito` TEXT,
  `descripcion_motivo_debito` MEDIUMTEXT,
  `fecha_comprobante_modifica` TEXT,
  `id_compra_modifica` INT(11) DEFAULT NULL,
  `tipo` TEXT,
  `idsucursal_origen` INT(11) DEFAULT NULL,
  `idsucursal_destino` INT(11) DEFAULT NULL,
  `id_contribuyente_origen` INT(11) DEFAULT NULL,
  `id_tipodocumento_origen` TEXT,
  `numero_comprobante_origen` INT(11) DEFAULT NULL,
  `modalidad_origen` TEXT,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `monto_adeudado` DECIMAL(18,6) DEFAULT NULL,
  `monto_adeudado_inicial` DECIMAL(18,6) DEFAULT NULL,
  `fecha_pagopendiente` TEXT,
  `cpago_nrooperacion` TEXT,
  `cpago_fechadeposito` TEXT,
  `cpago_idbanco` INT(11) DEFAULT NULL,
  `tipo_compra` TEXT,
  `log_condicion_pago` TEXT,
  `log` TEXT,
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comunicacion_baja` (
  `id_contribuyente` INT(11) NOT NULL,
  `codigo` TEXT,
  `serie` TEXT,
  `secuencia` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `fecha_documentos` TEXT,
  `fecha_envio_sunat` TEXT,
  `estado_envio_sunat` TEXT,
  `hash_cpe` MEDIUMTEXT,
  `hash_cdr` MEDIUMTEXT,
  `numero_ticket` TEXT,
  `cod_sunat` TEXT,
  `msje_sunat` TEXT,
  `ruta_xml` MEDIUMTEXT,
  `name_xml` MEDIUMTEXT,
  `name_xml_zip` MEDIUMTEXT,
  `name_cdr` TEXT,
  `name_cdr_zip` TEXT,
  `intentos_envio_sunat` INT(11) DEFAULT NULL,
  `estado_documento` TEXT,
  `detalle` MEDIUMTEXT,
  `motivo` TEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `condiciondepago` (
  `id_condicionpago` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `tipo` TEXT,
  `condicionpago` TEXT,
  `dias` INT(11) DEFAULT NULL,
  `estado` TEXT,
  PRIMARY KEY (`id_condicionpago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conductor` (
  `idconductor` INT(11) NOT NULL AUTO_INCREMENT,
  `id_etransporte` INT(11) DEFAULT NULL,
  `id_tipodocidentidad` TEXT,
  `num_doc` TEXT,
  `nombre_completo` TEXT,
  `licencia_conducir` TEXT,
  `tipo_licencia` TEXT,
  `telefono` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`idconductor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contribuyente` (
  `id_contribuyente` INT(11) NOT NULL AUTO_INCREMENT,
  `ruc` TEXT,
  `razon_social` TEXT,
  `nombre_comercial` TEXT,
  `email` TEXT,
  `telefono` TEXT,
  `codigo_ubigeo` TEXT,
  `urbanizacion` TEXT,
  `direccion_fiscal` TEXT,
  `usuario_sol` TEXT,
  `clave_sol` TEXT,
  `ruta_certificado` MEDIUMTEXT,
  `pass_certificado` TEXT,
  `tipo_certificado` TEXT,
  `ruta_xml_prueba` MEDIUMTEXT,
  `ruta_xml_produccion` MEDIUMTEXT,
  `tipo_envio_sunat` TEXT,
  `id_patrocinador` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `img_logo` TEXT,
  `modalidad_envio_sunat` TEXT,
  `dominio` TEXT,
  `https` TEXT,
  `logo_461` MEDIUMTEXT,
  `logo_56` MEDIUMTEXT,
  `logo_291` MEDIUMTEXT,
  `logo_350` MEDIUMTEXT,
  `fecha_expiracion` TEXT,
  `fecha_expira_cert` TEXT,
  `correlativo_rd_boletas` INT(11) DEFAULT NULL,
  `correlativo_comunicacion_bajas` INT(11) DEFAULT NULL,
  `sunat_idregimen` INT(11) DEFAULT NULL,
  `restriccion_stock` TEXT,
  `multi_almacen` TEXT,
  `num_decimales` INT(11) DEFAULT NULL,
  `prodduplicados_detalle` MEDIUMTEXT,
  `precio_venta_minimo` TEXT,
  `modulo_marketing` TEXT,
  `envio_automatico_docs` TEXT,
  `mostrar_codprod_pdfs` TEXT,
  `cotizacion_con_igv` TEXT,
  `mostrar_uprecio_clieprod` TEXT,
  `tiene_detracciones` TEXT,
  `tiene_percepciones` TEXT,
  `estado` TEXT,
  `tipo_busqueda_doc` TEXT,
  `customer_id_culqi` TEXT,
  `token` MEDIUMTEXT,
  `ver_fecha_vencimiento` TEXT,
  `ver_marca` TEXT,
  `captcha_key_public` TEXT,
  `captcha_key_private` TEXT,
  `url_soporte` TEXT,
  `url_politica_privacidad` TEXT,
  `url_terminos_condiciones` TEXT,
  `url_empresa` TEXT,
  `informar_condpago_sunat` TEXT,
  `mostrar_aviso` TEXT,
  `tipo_empresa` INT(11) DEFAULT NULL,
  `renta_3ra_porcentaje` DECIMAL(18,6) DEFAULT NULL,
  `renta_3ra_coeficiente` DECIMAL(18,6) DEFAULT NULL,
  `mype` TEXT,
  `tipo_empresa_sunat` TEXT,
  `nombre_ose` TEXT,
  `u_prueba_ose` TEXT,
  `c_prueba_ose` TEXT,
  `url_prueba_ose` TEXT,
  `u_produccion_ose` TEXT,
  `c_produccion_ose` TEXT,
  `url_produccion_ose` TEXT,
  `regimen_retencion` DECIMAL(18,6) DEFAULT NULL,
  `url_facebook` TEXT,
  `url_youtube` TEXT,
  `url_tiktok` TEXT,
  `url_instagram` TEXT,
  `url_twitter` TEXT,
  `sunat_u_sol_principal` TEXT,
  `sunat_p_sol_principal` TEXT,
  `sunat_client_id` TEXT,
  `sunat_client_secret` TEXT,
  `user_sol_busq_cpe` TEXT,
  `pass_sol_busq_cpe` TEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contribuyente_opciones` (
  `id_contribuyente` INT(11) NOT NULL,
  `opcion_nombre` VARCHAR(191) NOT NULL,
  `opcion_valor` TEXT,
  PRIMARY KEY (`id_contribuyente`, `opcion_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cuentabanco` (
  `id_cuentabanco` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_codigomoneda` TEXT,
  `tipo_cuenta` TEXT,
  `nombre_banco` TEXT,
  `nombre_titular` TEXT,
  `nro_cuenta` TEXT,
  `cci` TEXT,
  `descripcion` MEDIUMTEXT,
  `estado` TEXT,
  `id_entidadfinanciera` TEXT,
  PRIMARY KEY (`id_cuentabanco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `data_extra` (
  `id_dataextra` INT(11) NOT NULL AUTO_INCREMENT,
  `html_sitioweb` MEDIUMTEXT,
  `url_guiausuario` TEXT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_dataextra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `detalle_compra` (
  `id_detalle` INT(11) DEFAULT NULL,
  `id_compra` INT(11) NOT NULL AUTO_INCREMENT,
  `id_unidad_medida` TEXT,
  `unidad_medida` TEXT,
  `cantidad` DECIMAL(18,6) DEFAULT NULL,
  `precio` DECIMAL(18,6) DEFAULT NULL,
  `precio_sin_igv` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `importe` DECIMAL(18,6) DEFAULT NULL,
  `id_codigoprecio` TEXT,
  `igv` DECIMAL(18,6) DEFAULT NULL,
  `isc` DECIMAL(18,6) DEFAULT NULL,
  `icbper` DECIMAL(18,6) DEFAULT NULL,
  `id_tipoafectacionigv` TEXT,
  `id_producto` INT(11) DEFAULT NULL,
  `codigo_producto` TEXT,
  `descripcion` MEDIUMTEXT,
  `tipo_unidad` TEXT,
  `id_presentacion` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `detalle_doc` (
  `iddetalle` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `item` INT(11) DEFAULT NULL,
  `id_unidad_medida` INT(11) DEFAULT NULL,
  `unidad_medida` TEXT,
  `cantidad` DECIMAL(18,6) DEFAULT NULL,
  `precio` DECIMAL(18,6) DEFAULT NULL,
  `precio_sin_igv` DECIMAL(18,6) DEFAULT NULL,
  `id_tipoafectacionigv` TEXT,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `id_codigoprecio` TEXT,
  `igv` DECIMAL(18,6) DEFAULT NULL,
  `isc` DECIMAL(18,6) DEFAULT NULL,
  `icbper` DECIMAL(18,6) DEFAULT NULL,
  `importe` DECIMAL(18,6) DEFAULT NULL,
  `id_producto` INT(11) DEFAULT NULL,
  `codigo_producto` TEXT,
  `descripcion` MEDIUMTEXT,
  `peso` DECIMAL(18,6) DEFAULT NULL,
  `tipo_unidad` TEXT,
  `id_presentacion` INT(11) DEFAULT NULL,
  `factor_igv` DECIMAL(18,6) DEFAULT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `detalle_docnooficial` (
  `iddetalle` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodocumento` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `modalidad` TEXT,
  `item` INT(11) DEFAULT NULL,
  `id_unidad_medida` INT(11) DEFAULT NULL,
  `unidad_medida` TEXT,
  `cantidad` DECIMAL(18,6) DEFAULT NULL,
  `precio` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `id_codigoprecio` TEXT,
  `igv` DECIMAL(18,6) DEFAULT NULL,
  `icbper` DECIMAL(18,6) DEFAULT NULL,
  `importe` DECIMAL(18,6) DEFAULT NULL,
  `id_tipoafectacionigv` TEXT,
  `id_producto` INT(11) DEFAULT NULL,
  `codigo_producto` TEXT,
  `descripcion` MEDIUMTEXT,
  `precio_sin_igv` DECIMAL(18,6) DEFAULT NULL,
  `peso` DECIMAL(18,6) DEFAULT NULL,
  `tipo_unidad` TEXT,
  `id_presentacion` INT(11) DEFAULT NULL,
  `factor_igv` DECIMAL(18,6) DEFAULT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `disenopersonalizadosistema` (
  `id_diseno` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `modulo` TEXT,
  `categoria` TEXT,
  `enlace` TEXT,
  `preview` TEXT,
  `estado` TEXT,
  `tipo` TEXT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_diseno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `doc_detallepago` (
  `id_detallepago` MEDIUMTEXT,
  `id_contribuyente` TEXT,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` TEXT,
  `tipo_envio_sunat` TEXT,
  `id_vendedor` TEXT,
  `id_sucursal` TEXT,
  `id_condicionpago` TEXT,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` TEXT,
  `total` TEXT,
  `cpago_nrooperacion` TEXT,
  `fecha_registro` TEXT,
  `fechadeposito` TEXT,
  `idbanco` TEXT,
  `detalle` MEDIUMTEXT,
  `estado` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `doc_electronico` (
  `id_contribuyente` INT(11) NOT NULL,
  `id_tipodoc_electronico` VARCHAR(191) NOT NULL,
  `serie_comprobante` VARCHAR(191) NOT NULL,
  `numero_comprobante` INT(11) NOT NULL,
  `tipo_envio_sunat` TEXT,
  `id_tipo_operacion` TEXT,
  `tipo_venta` TEXT,
  `total_gravadas` DECIMAL(18,6) DEFAULT NULL,
  `total_inafecta` DECIMAL(18,6) DEFAULT NULL,
  `total_exoneradas` DECIMAL(18,6) DEFAULT NULL,
  `total_gratuitas` DECIMAL(18,6) DEFAULT NULL,
  `total_exportacion` DECIMAL(18,6) DEFAULT NULL,
  `total_icbper` DECIMAL(18,6) DEFAULT NULL,
  `total_descuento` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_descuento_total` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_igv` DECIMAL(18,6) DEFAULT NULL,
  `impuesto_icbper` DECIMAL(18,6) DEFAULT NULL,
  `total_igv` DECIMAL(18,6) DEFAULT NULL,
  `total_isc` DECIMAL(18,6) DEFAULT NULL,
  `total_otr_imp` DECIMAL(18,6) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `total_letras` TEXT,
  `nro_guia_remision` INT(11) DEFAULT NULL,
  `cod_guia_remision` TEXT,
  `nro_otr_comprobante` TEXT,
  `fecha_comprobante` TEXT,
  `fecha_vto_comprobante` TEXT,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `idcliente` INT(11) DEFAULT NULL,
  `id_vendedor` INT(11) DEFAULT NULL,
  `id_sucursal` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `nota` TEXT,
  `id_motivotraslado` TEXT,
  `motivo_traslado` TEXT,
  `peso` DECIMAL(18,6) DEFAULT NULL,
  `numero_paquetes` INT(11) DEFAULT NULL,
  `id_codigopuerto` TEXT,
  `numero_contenedor` TEXT,
  `id_modalidadtraslado` TEXT,
  `modalidad_traslado` TEXT,
  `fecha_traslado` TEXT,
  `id_vehiculo` INT(11) DEFAULT NULL,
  `transporte_nro_placa` TEXT,
  `idconductor` INT(11) DEFAULT NULL,
  `id_tipodoc_conductor` TEXT,
  `num_doc_conductor` TEXT,
  `nombre_completo_conductor` TEXT,
  `licencia_conductor` TEXT,
  `id_etransporte` INT(11) DEFAULT NULL,
  `id_tipo_documento_transporte` TEXT,
  `nro_documento_transporte` TEXT,
  `razon_social_transporte` TEXT,
  `id_ubigeo_destino` TEXT,
  `dir_destino` TEXT,
  `id_ubigeo_partida` TEXT,
  `dir_partida` TEXT,
  `array_docs_referencia` TEXT,
  `id_tipo_comprobante_modifica` TEXT,
  `serie_documento_modifica` TEXT,
  `nro_documento_modifica` INT(11) DEFAULT NULL,
  `id_cod_tipomotivo_credito` TEXT,
  `descripcion_motivo_credito` MEDIUMTEXT,
  `id_cod_tipomotivo_debito` TEXT,
  `descripcion_motivo_debito` MEDIUMTEXT,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `monto_adeudado` DECIMAL(18,6) DEFAULT NULL,
  `monto_adeudado_inicial` DECIMAL(18,6) DEFAULT NULL,
  `log_condicion_pago` TEXT,
  `fecha_pagopendiente` TEXT,
  `cpago_nrooperacion` TEXT,
  `cpago_fechadeposito` TEXT,
  `cpago_idbanco` INT(11) DEFAULT NULL,
  `percepcion_idtipo` TEXT,
  `percepcion_montobase` DECIMAL(18,6) DEFAULT NULL,
  `percepcion_porcentaje` DECIMAL(18,6) DEFAULT NULL,
  `percepcion_monto` DECIMAL(18,6) DEFAULT NULL,
  `detraccion_id_mediopago` TEXT,
  `detraccion_cuenta` TEXT,
  `detraccion_iddetraccion` TEXT,
  `detraccion_porcentaje` DECIMAL(18,6) DEFAULT NULL,
  `detraccion_monto` DECIMAL(18,6) DEFAULT NULL,
  `detraccion_texto` TEXT,
  `fecha_envio_sunat` TEXT,
  `estado_envio_sunat` TEXT,
  `hash_cpe` MEDIUMTEXT,
  `hash_cdr` MEDIUMTEXT,
  `cod_sunat` TEXT,
  `msje_sunat` TEXT,
  `ruta_xml` MEDIUMTEXT,
  `name_xml` MEDIUMTEXT,
  `name_xml_zip` MEDIUMTEXT,
  `name_cdr` TEXT,
  `name_cdr_zip` TEXT,
  `intentos_envio_sunat` INT(11) DEFAULT NULL,
  `estado_documento` TEXT,
  `rb_id_contribuyente` INT(11) DEFAULT NULL,
  `rb_codigo` TEXT,
  `rb_serie` TEXT,
  `rb_secuencia` INT(11) DEFAULT NULL,
  `rb_tipo_envio_sunat` TEXT,
  `ignorar_documento` TEXT,
  `credito_sunat` TEXT,
  `retencion_aplica` TEXT,
  `retencion_factor` DECIMAL(18,6) DEFAULT NULL,
  `retencion_monto` DECIMAL(18,6) DEFAULT NULL,
  `retencion_base` DECIMAL(18,6) DEFAULT NULL,
  `ruta_qr` MEDIUMTEXT,
  `indicador_envio_sunat` TEXT,
  `tipo_doc_transporte_mercancias` TEXT,
  `indicador_traslado_total_dam_ds` TEXT,
  PRIMARY KEY (`id_contribuyente`, `id_tipodoc_electronico`, `serie_comprobante`, `numero_comprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `doc_electronico_anticipo` (
  `id_anticipo` INT(11) NOT NULL AUTO_INCREMENT,
  `cpe_id_contribuyente` INT(11) DEFAULT NULL,
  `cpe_id_tipodoc_electronico` TEXT,
  `cpe_serie_comprobante` TEXT,
  `cpe_numero_comprobante` INT(11) DEFAULT NULL,
  `cpe_tipo_envio_sunat` TEXT,
  `anticipo_id_contribuyente` INT(11) DEFAULT NULL,
  `anticipo_id_tipodoc_electronico` TEXT,
  `anticipo_serie_comprobante` TEXT,
  `anticipo_numero_comprobante` INT(11) DEFAULT NULL,
  `anticipo_tipo_envio_sunat` TEXT,
  `monto_anticipo` DECIMAL(18,6) DEFAULT NULL,
  PRIMARY KEY (`id_anticipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `doc_electronicoxnota` (
  `id_doc_electronicoxnota` INT(11) NOT NULL AUTO_INCREMENT,
  `cpe_id_contribuyente` INT(11) DEFAULT NULL,
  `cpe_id_tipodoc_electronico` TEXT,
  `cpe_serie_comprobante` TEXT,
  `cpe_numero_comprobante` INT(11) DEFAULT NULL,
  `cpe_tipo_envio_sunat` TEXT,
  `fecha_registro` TEXT,
  `nota_id_contribuyente` INT(11) DEFAULT NULL,
  `nota_id_tipodoc_electronico` TEXT,
  `nota_serie_comprobante` TEXT,
  `nota_numero_comprobante` INT(11) DEFAULT NULL,
  `nota_tipo_envio_sunat` TEXT,
  `nota_estado_envio_sunat` TEXT,
  `nota_id_cod_tipomotivo_credito` TEXT,
  `nota_id_cod_tipomotivo_debito` TEXT,
  PRIMARY KEY (`id_doc_electronicoxnota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `doc_no_oficial` (
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodocumento` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `modalidad` TEXT,
  `total_gravadas` DECIMAL(18,6) DEFAULT NULL,
  `total_inafecta` DECIMAL(18,6) DEFAULT NULL,
  `total_exoneradas` DECIMAL(18,6) DEFAULT NULL,
  `total_gratuitas` DECIMAL(18,6) DEFAULT NULL,
  `total_exportacion` DECIMAL(18,6) DEFAULT NULL,
  `total_icbper` DECIMAL(18,6) DEFAULT NULL,
  `total_descuento` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_descuento_total` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_igv` DECIMAL(18,6) DEFAULT NULL,
  `impuesto_icbper` DECIMAL(18,6) DEFAULT NULL,
  `total_igv` DECIMAL(18,6) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `total_letras` TEXT,
  `fecha_comprobante` TEXT,
  `fecha_vto_comprobante` TEXT,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `idcliente` INT(11) NOT NULL AUTO_INCREMENT,
  `id_vendedor` INT(11) DEFAULT NULL,
  `id_sucursal` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `nota` TEXT,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `monto_adeudado` DECIMAL(18,6) DEFAULT NULL,
  `monto_adeudado_inicial` DECIMAL(18,6) DEFAULT NULL,
  `log_condicion_pago` TEXT,
  `fecha_pagopendiente` TEXT,
  `cpago_nrooperacion` TEXT,
  `cpago_fechadeposito` TEXT,
  `cpago_idbanco` INT(11) DEFAULT NULL,
  `estado_documento` TEXT,
  `transporte_nro_placa` TEXT,
  `nro_otr_comprobante` TEXT,
  `tipo` TEXT,
  `idsucursal_origen` INT(11) DEFAULT NULL,
  `idsucursal_destino` INT(11) DEFAULT NULL,
  `id_ubigeo_destino` TEXT,
  `dir_destino` TEXT,
  PRIMARY KEY (`idcliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `doc_relacion` (
  `id` INT(11) DEFAULT NULL,
  `origen_id_contribuyente` INT(11) DEFAULT NULL,
  `origen_id_tipodoc_electronico` TEXT,
  `origen_serie_comprobante` TEXT,
  `origen_numero_comprobante` INT(11) DEFAULT NULL,
  `origen_tipo_envio_sunat` TEXT,
  `fecha_registro` TEXT,
  `descripcion` MEDIUMTEXT,
  `accion` TEXT,
  `destino_id_contribuyente` INT(11) DEFAULT NULL,
  `destino_id_tipodoc_electronico` TEXT,
  `destino_serie_comprobante` TEXT,
  `destino_numero_comprobante` INT(11) DEFAULT NULL,
  `destino_tipo_envio_sunat` TEXT,
  `id_vendedor` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_vendedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `empresa_transporte` (
  `id_etransporte` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodocidentidad` TEXT,
  `num_doc` TEXT,
  `razon_social` TEXT,
  `codigo_ubigeo` TEXT,
  `direccion` TEXT,
  `telefono` TEXT,
  `nota` TEXT,
  `estado` TEXT,
  `tipo_traslado` TEXT,
  PRIMARY KEY (`id_etransporte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ep_categoria` (
  `idcategoria` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ep_template` (
  `idtemplate` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `html` MEDIUMTEXT,
  `fecharegistro` TEXT,
  `idcategoria` INT(11) DEFAULT NULL,
  `ruta_assets` MEDIUMTEXT,
  `img_preview` TEXT,
  `estado` TEXT,
  `modalidad` TEXT,
  PRIMARY KEY (`idtemplate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ep_userpage` (
  `iduserpage` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idtemplate` INT(11) DEFAULT NULL,
  `htmlcode` MEDIUMTEXT,
  `mainheadercode` TEXT,
  `fecharegistro` TEXT,
  `imagepreview` TEXT,
  `estado` TEXT,
  `is_home` TEXT,
  PRIMARY KEY (`iduserpage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ep_userpageversiones` (
  `idversion` INT(11) NOT NULL AUTO_INCREMENT,
  `iduserpage` INT(11) DEFAULT NULL,
  `idtemplate` INT(11) DEFAULT NULL,
  `htmlcode` MEDIUMTEXT,
  `fecharegistro` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`idversion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `etiqueta` (
  `id_etiqueta` INT(11) DEFAULT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  `color` TEXT,
  `codigo` TEXT,
  `detalle` MEDIUMTEXT,
  `tipo` TEXT,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `etiquetaprospecto` (
  `id_etiqueta` INT(11) NOT NULL AUTO_INCREMENT,
  `id_prospecto` INT(11) DEFAULT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `nombre` TEXT,
  `color` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_etiqueta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `etiquetaxdocumento` (
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_etiqueta` INT(11) DEFAULT NULL,
  `estado` TEXT,
  `fecha_registro` TEXT,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha_inactivo` TEXT,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `facturalaya_deposito` (
  `id_deposito` INT(11) DEFAULT NULL,
  `id_user_registro` INT(11) DEFAULT NULL,
  `id_user_validacion` INT(11) DEFAULT NULL,
  `fecha_deposito` TEXT,
  `fecha_registro` TEXT,
  `fecha_validacion` TEXT,
  `id_cuentabanco` INT(11) DEFAULT NULL,
  `num_operacion` TEXT,
  `monto` DECIMAL(18,6) DEFAULT NULL,
  `id_servicio` INT(11) DEFAULT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `estado_validacion` TEXT,
  `estado_registro` TEXT,
  `nuevo_cliente` TEXT,
  `mes_contabilidad` INT(11) DEFAULT NULL,
  `anio_contabilidad` INT(11) DEFAULT NULL,
  `ingresa_reparticion` TEXT,
  `detalle` MEDIUMTEXT,
  `nombre_servicio` TEXT,
  `idcliente` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idcliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `guia_transportista` (
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` INT(11) NOT NULL AUTO_INCREMENT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `fecha_registro` TEXT,
  `fecha_comprobante` TEXT,
  `nota` TEXT,
  `fecha_traslado` TEXT,
  `id_unidad_medida` INT(11) DEFAULT NULL,
  `unidad_medida` TEXT,
  `peso_total` DECIMAL(18,6) DEFAULT NULL,
  `transporte_nro_placa` TEXT,
  `num_registro_mtc` TEXT,
  `tuc_vehiculo_principal` TEXT,
  `conductor_tipo_documento` TEXT,
  `conductor_nro_documento` TEXT,
  `conductor_nombre_completo` TEXT,
  `conductor_nro_licencia` TEXT,
  `dest_tipo_documento` TEXT,
  `dest_numero_documento` TEXT,
  `dest_nombre_completo` TEXT,
  `id_ubigeo_partida` TEXT,
  `dir_partida` TEXT,
  `codigo_local_anexo` TEXT,
  `id_ubigeo_destino` TEXT,
  `dir_destino` TEXT,
  `fecha_envio_sunat` TEXT,
  `estado_envio_sunat` TEXT,
  `hash_cpe` MEDIUMTEXT,
  `hash_cdr` MEDIUMTEXT,
  `cod_sunat` TEXT,
  `msje_sunat` TEXT,
  `ruta_xml` MEDIUMTEXT,
  `name_xml_zip` MEDIUMTEXT,
  `name_cdr` TEXT,
  `name_cdr_zip` TEXT,
  `intentos_envio_sunat` INT(11) DEFAULT NULL,
  `estado_documento` TEXT,
  `id_vendedor` INT(11) DEFAULT NULL,
  `id_sucursal` INT(11) DEFAULT NULL,
  `numero_paquetes` INT(11) DEFAULT NULL,
  `num_ticket` TEXT,
  `fec_recepcion_ticket` TEXT,
  `cod_respuesta` TEXT,
  `num_error` TEXT,
  `desc_error` TEXT,
  `desc_success` TEXT,
  `ruta_qr` MEDIUMTEXT,
  `observaciones` MEDIUMTEXT,
  `codigo_local_partida` TEXT,
  `codigo_local_llegada` TEXT,
  `remitente_tipo_documento` TEXT,
  `remitente_numero_documento` TEXT,
  `remitente_nombre_completo` TEXT,
  PRIMARY KEY (`id_tipodoc_electronico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `guia_transportista_detalle` (
  `iddetalle` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `item` INT(11) DEFAULT NULL,
  `id_unidad_medida` INT(11) DEFAULT NULL,
  `unidad_medida` TEXT,
  `cantidad` DECIMAL(18,6) DEFAULT NULL,
  `codigo` TEXT,
  `descripcion` MEDIUMTEXT,
  `idproducto` INT(11) DEFAULT NULL,
  `peso` DECIMAL(18,6) DEFAULT NULL,
  `id_presentacion` INT(11) DEFAULT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `kardex` (
  `id_kardex` INT(11) DEFAULT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idsucursal` INT(11) DEFAULT NULL,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `idproducto` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `tipo_kardex` TEXT,
  `estado_kardex` TEXT,
  `cantidad_entrada` DECIMAL(18,6) DEFAULT NULL,
  `cantidad_salida` DECIMAL(18,6) DEFAULT NULL,
  `stock` DECIMAL(18,6) DEFAULT NULL,
  `id_cod_moneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `costo_unitario` DECIMAL(18,6) DEFAULT NULL,
  `costo_unitario_promedio` DECIMAL(18,6) DEFAULT NULL,
  `costo_total` DECIMAL(18,6) DEFAULT NULL,
  `stock_valorizado` DECIMAL(18,6) DEFAULT NULL,
  `detalle` MEDIUMTEXT,
  `fecha_registro` TEXT,
  `docref_id_contribuyente` INT(11) DEFAULT NULL,
  `docref_id_tipodoc_electronico` TEXT,
  `docref_serie_comprobante` TEXT,
  `docref_numero_comprobante` INT(11) DEFAULT NULL,
  `docref_tipo_envio_sunat` TEXT,
  `id_compra` INT(11) DEFAULT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `log` (
  `id` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `tabla` TEXT,
  `accion` TEXT,
  `descripcion` MEDIUMTEXT,
  `fecha_registro` TEXT,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `num_total_importados` INT(11) DEFAULT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `log_compra` (
  `id` INT(11) DEFAULT NULL,
  `id_compra` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idsucursal` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_proveedor` INT(11) DEFAULT NULL,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `tipo` TEXT,
  `descripcion` MEDIUMTEXT,
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `log_config_sistema` (
  `id` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` MEDIUMTEXT,
  `tabla` TEXT,
  `columna` TEXT,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `log_documento` (
  `id` INT(11) DEFAULT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` MEDIUMTEXT,
  `fecha_registro` TEXT,
  `tipo` TEXT,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `monto_cobrado` (
  `id_montocobrado` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_vendedor` INT(11) DEFAULT NULL,
  `id_sucursal` INT(11) DEFAULT NULL,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `cpago_nrooperacion` TEXT,
  `fecha_registro` TEXT,
  `fecha_cuota` TEXT,
  `fechadeposito` TEXT,
  `idbanco` INT(11) DEFAULT NULL,
  `detalle` MEDIUMTEXT,
  `estado` TEXT,
  `tipo_abono` TEXT,
  PRIMARY KEY (`id_montocobrado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `monto_pagado` (
  `id_montopagado` INT(11) DEFAULT NULL,
  `id_compra` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_usuario` INT(11) DEFAULT NULL,
  `id_sucursal` INT(11) DEFAULT NULL,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `cpago_nrooperacion` TEXT,
  `fecha_registro` TEXT,
  `fechadeposito` TEXT,
  `idbanco` INT(11) DEFAULT NULL,
  `detalle` MEDIUMTEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `movimientocaja` (
  `id_movimiento` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_sucursal` INT(11) DEFAULT NULL,
  `id_vendedor` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `fecha_movimiento` TEXT,
  `descripcion` MEDIUMTEXT,
  `moneda` TEXT,
  `monto` DECIMAL(18,6) DEFAULT NULL,
  `tipo_movimiento` TEXT,
  `detalle` MEDIUMTEXT,
  `ruc_comprobante` TEXT,
  `tipo_comprobante` TEXT,
  `serie_num_comprobante` TEXT,
  `estado` TEXT,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `cpago_nrooperacion` TEXT,
  `fechadeposito` TEXT,
  `idbanco` INT(11) DEFAULT NULL,
  `id_proveedor` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `opciones` (
  `id_opcion` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `color_fondo_tipo` TEXT,
  `color_fondo_1_rgb` TEXT,
  `color_fondo_2_rgb` TEXT,
  `id_plantilla_login` INT(11) DEFAULT NULL,
  `img_background_login` TEXT,
  `id_plantilla_registro` INT(11) DEFAULT NULL,
  `img_background_register` TEXT,
  `msj_expira_suscripcion` TEXT,
  PRIMARY KEY (`id_opcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orden_compra` (
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `idsucursal` INT(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `fecha_comprobante` TEXT,
  `estado` TEXT,
  `total_gravadas` DECIMAL(18,6) DEFAULT NULL,
  `total_inafecta` DECIMAL(18,6) DEFAULT NULL,
  `total_exoneradas` DECIMAL(18,6) DEFAULT NULL,
  `total_gratuitas` DECIMAL(18,6) DEFAULT NULL,
  `total_exportacion` DECIMAL(18,6) DEFAULT NULL,
  `total_descuento` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_descuento_total` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_igv` DECIMAL(18,6) DEFAULT NULL,
  `total_igv` DECIMAL(18,6) DEFAULT NULL,
  `total_isc` DECIMAL(18,6) DEFAULT NULL,
  `total_icbper` DECIMAL(18,6) DEFAULT NULL,
  `total_otr_imp` DECIMAL(18,6) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `nro_guia_remision` INT(11) DEFAULT NULL,
  `cod_guia_remision` TEXT,
  `nro_otr_comprobante` TEXT,
  `id_codigomoneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `id_usuario` INT(11) DEFAULT NULL,
  `nota` TEXT,
  `fecha_vto_comprobante` TEXT,
  `id_condicionpago` INT(11) DEFAULT NULL,
  `monto_adeudado` DECIMAL(18,6) DEFAULT NULL,
  `monto_adeudado_inicial` DECIMAL(18,6) DEFAULT NULL,
  `fecha_pagopendiente` TEXT,
  `cpago_nrooperacion` TEXT,
  `cpago_fechadeposito` TEXT,
  `cpago_idbanco` INT(11) DEFAULT NULL,
  `tipo_compra` TEXT,
  `log_condicion_pago` TEXT,
  `log` TEXT,
  PRIMARY KEY (`idsucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orden_compra_detalle` (
  `id_detalle` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodoc_electronico` TEXT,
  `serie_comprobante` TEXT,
  `numero_comprobante` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `id_unidad_medida` TEXT,
  `unidad_medida` TEXT,
  `cantidad` DECIMAL(18,6) DEFAULT NULL,
  `precio` DECIMAL(18,6) DEFAULT NULL,
  `precio_sin_igv` DECIMAL(18,6) DEFAULT NULL,
  `sub_total` DECIMAL(18,6) DEFAULT NULL,
  `importe` DECIMAL(18,6) DEFAULT NULL,
  `id_codigoprecio` TEXT,
  `igv` DECIMAL(18,6) DEFAULT NULL,
  `isc` DECIMAL(18,6) DEFAULT NULL,
  `icbper` DECIMAL(18,6) DEFAULT NULL,
  `id_tipoafectacionigv` TEXT,
  `id_producto` INT(11) DEFAULT NULL,
  `codigo_producto` TEXT,
  `descripcion` MEDIUMTEXT,
  `tipo_unidad` TEXT,
  `id_presentacion` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plantillapdf` (
  `id_plantillapdf` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `tamanio` TEXT,
  `tipo` TEXT,
  `ids_tipodocelectronico` TEXT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `preview` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_plantillapdf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `producto` (
  `idproducto` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idsucursal` INT(11) DEFAULT NULL,
  `codigo` TEXT,
  `id_unidad_medida` INT(11) DEFAULT NULL,
  `id_cod_detraccion` TEXT,
  `id_tipoafectacionigv` TEXT,
  `id_categoria` INT(11) DEFAULT NULL,
  `nombre` TEXT,
  `id_cod_moneda` TEXT,
  `tipo_cambio_sunat` DECIMAL(18,6) DEFAULT NULL,
  `precio_compra` DECIMAL(18,6) DEFAULT NULL,
  `valor_sin_igv` DECIMAL(18,6) DEFAULT NULL,
  `valor_con_igv` DECIMAL(18,6) DEFAULT NULL,
  `precio_venta_minimo` DECIMAL(18,6) DEFAULT NULL,
  `nota` TEXT,
  `foto` TEXT,
  `stock` DECIMAL(18,6) DEFAULT NULL,
  `stock_minimo` DECIMAL(18,6) DEFAULT NULL,
  `fecha_registro` TEXT,
  `estado` TEXT,
  `afecto_icbper` TEXT,
  `multi_precio` TEXT,
  `fecha_vencimiento` TEXT,
  `marca` TEXT,
  `codigos_presentaciones` TEXT,
  `porcentaje_pventa` DECIMAL(18,6) DEFAULT NULL,
  `porcentaje_pminimo` DECIMAL(18,6) DEFAULT NULL,
  `costo_promedio` DECIMAL(18,6) DEFAULT NULL,
  `factor_igv` DECIMAL(18,6) DEFAULT NULL,
  `peso` DECIMAL(18,6) DEFAULT NULL,
  `destacado` TEXT,
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `producto_listaprecio` (
  `idprecio` INT(11) NOT NULL AUTO_INCREMENT,
  `idproducto` INT(11) DEFAULT NULL,
  `nombre` TEXT,
  `precio` DECIMAL(18,6) DEFAULT NULL,
  `estado` TEXT,
  PRIMARY KEY (`idprecio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `producto_movimiento` (
  `id_contribuyente` INT(11) NOT NULL,
  `id_tipo_movimiento` VARCHAR(191) NOT NULL,
  `serie` VARCHAR(191) NOT NULL,
  `correlativo` INT(11) NOT NULL,
  `tipo_envio_sunat` VARCHAR(191) NOT NULL,
  `tipo` TEXT,
  `id_sucursal` INT(11) DEFAULT NULL,
  `fecha_movimiento` TEXT,
  `id_usuario` INT(11) DEFAULT NULL,
  `nota` TEXT,
  `estado` TEXT,
  `destino_id_sucursal` INT(11) DEFAULT NULL,
  `destino_id_contribuyente` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_contribuyente`, `id_tipo_movimiento`, `serie`, `correlativo`, `tipo_envio_sunat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `producto_movimiento_det` (
  `id_movimiento_det` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipo_movimiento` TEXT,
  `serie` TEXT,
  `correlativo` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `item` INT(11) DEFAULT NULL,
  `cantidad` DECIMAL(18,6) DEFAULT NULL,
  `costo_unitario` DECIMAL(18,6) DEFAULT NULL,
  `id_codigomoneda` TEXT,
  `o_id_u_medida` INT(11) DEFAULT NULL,
  `o_u_medida` TEXT,
  `o_precio` DECIMAL(18,6) DEFAULT NULL,
  `o_id_afectigv` TEXT,
  `o_tipo_unidad` TEXT,
  `o_id_presentacion` INT(11) DEFAULT NULL,
  `o_cod_prod` TEXT,
  `o_id_prod` INT(11) DEFAULT NULL,
  `o_nom_prod` TEXT,
  `d_id_u_medida` INT(11) DEFAULT NULL,
  `d_u_medida` TEXT,
  `d_precio` DECIMAL(18,6) DEFAULT NULL,
  `d_id_afectigv` TEXT,
  `d_tipo_unidad` TEXT,
  `d_id_presentacion` INT(11) DEFAULT NULL,
  `d_cod_prod` TEXT,
  `d_id_prod` INT(11) DEFAULT NULL,
  `d_nom_prod` TEXT,
  PRIMARY KEY (`id_movimiento_det`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `producto_presentacion` (
  `id_presentacion` INT(11) DEFAULT NULL,
  `idproducto` INT(11) NOT NULL AUTO_INCREMENT,
  `idunidad` INT(11) DEFAULT NULL,
  `codigo` TEXT,
  `nombre` TEXT,
  `idunidad_base` INT(11) DEFAULT NULL,
  `precio_con_igv` DECIMAL(18,6) DEFAULT NULL,
  `precio_sin_igv` DECIMAL(18,6) DEFAULT NULL,
  `cantidad_und_base` DECIMAL(18,6) DEFAULT NULL,
  `estado` TEXT,
  `fecha_registro` TEXT,
  `precio_minimo` DECIMAL(18,6) DEFAULT NULL,
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `prospecto` (
  `id_prospecto` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_sucursal` INT(11) DEFAULT NULL,
  `iduserpage` INT(11) DEFAULT NULL,
  `ruc` TEXT,
  `razon_social` TEXT,
  `nombre_contacto` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  `etiquetas` TEXT,
  `correos` TEXT,
  `fecha_expira_oferta` TEXT,
  `telefonos` TEXT,
  `nota` TEXT,
  PRIMARY KEY (`id_prospecto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_tipodocidentidad` TEXT,
  `codigo` TEXT,
  `num_doc` TEXT,
  `razon_social` TEXT,
  `nombre_comercial` TEXT,
  `direccion_fiscal` TEXT,
  `id_cod_ubigeo` TEXT,
  `email` TEXT,
  `celular` TEXT,
  `telefono` TEXT,
  `num_cuenta_detraccion` TEXT,
  `detalle_adicional` MEDIUMTEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `resumen_boletas` (
  `id_contribuyente` INT(11) NOT NULL,
  `codigo` TEXT,
  `serie` TEXT,
  `secuencia` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `fecha_documentos` TEXT,
  `fecha_envio_sunat` TEXT,
  `estado_envio_sunat` TEXT,
  `hash_cpe` MEDIUMTEXT,
  `hash_cdr` MEDIUMTEXT,
  `numero_ticket` TEXT,
  `cod_sunat` TEXT,
  `msje_sunat` TEXT,
  `ruta_xml` MEDIUMTEXT,
  `name_xml` MEDIUMTEXT,
  `name_xml_zip` MEDIUMTEXT,
  `name_cdr` TEXT,
  `name_cdr_zip` TEXT,
  `intentos_envio_sunat` INT(11) DEFAULT NULL,
  `estado_documento` TEXT,
  `accion_sunat` INT(11) DEFAULT NULL,
  `detalle` MEDIUMTEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `rol_usuario` (
  `id_rol` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `alias` TEXT,
  `descripcion` MEDIUMTEXT,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sire_periodo` (
  `id_contribuyente` INT(11) NOT NULL,
  `tipo_envio_sunat` TEXT,
  `mes_anio` TEXT,
  `tipo_sire` TEXT,
  `fecha_registro` TEXT,
  `response` TEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sire_rce` (
  `id_contribuyente` INT(11) NOT NULL,
  `tipo_envio_sunat` TEXT,
  `periodo_anio` TEXT,
  `periodo_mes` TEXT,
  `num_ticket` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sire_rce_item` (
  `id` INT(11) NOT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `periodo_anio` TEXT,
  `periodo_mes` TEXT,
  `num_ticket` TEXT,
  `ruc` TEXT,
  `razon_social` TEXT,
  `periodo` TEXT,
  `car_sunat` TEXT,
  `fecha_emision` TEXT,
  `fecha_vcto_pago` TEXT,
  `tipo_cp_doc` TEXT,
  `serie_cdp` TEXT,
  `anio` TEXT,
  `nro_cp_doc_inicial` TEXT,
  `nro_final_rango` TEXT,
  `tipo_doc_identidad` TEXT,
  `nro_doc_identidad` TEXT,
  `apellidos_nombres_razon_social` TEXT,
  `bi_gravado_dg` DECIMAL(18,6) DEFAULT NULL,
  `igv_ipm_dg` DECIMAL(18,6) DEFAULT NULL,
  `bi_gravado_dgng` DECIMAL(18,6) DEFAULT NULL,
  `igv_ipm_dgng` DECIMAL(18,6) DEFAULT NULL,
  `bi_gravado_dng` DECIMAL(18,6) DEFAULT NULL,
  `igv_ipm_dng` DECIMAL(18,6) DEFAULT NULL,
  `valor_adq_ng` DECIMAL(18,6) DEFAULT NULL,
  `isc` DECIMAL(18,6) DEFAULT NULL,
  `icbper` DECIMAL(18,6) DEFAULT NULL,
  `otros_trib_cargos` DECIMAL(18,6) DEFAULT NULL,
  `total_cp` DECIMAL(18,6) DEFAULT NULL,
  `moneda` TEXT,
  `tipo_cambio` DECIMAL(18,6) DEFAULT NULL,
  `fecha_emision_doc_modificado` TEXT,
  `tipo_cp_modificado` TEXT,
  `serie_cp_modificado` TEXT,
  `cod_dam_dsi` TEXT,
  `nro_cp_modificado` TEXT,
  `clasif_bss_sss` TEXT,
  `id_proyecto_operadores` TEXT,
  `porcpart` DECIMAL(18,6) DEFAULT NULL,
  `imb` DECIMAL(18,6) DEFAULT NULL,
  `car_orig_ind_e_o_i` TEXT,
  `detraccion` DECIMAL(18,6) DEFAULT NULL,
  `tipo_de_nota` TEXT,
  `est_comp` TEXT,
  `clu1` TEXT,
  `clu2` TEXT,
  `clu3` TEXT,
  `clu4` TEXT,
  `clu5` TEXT,
  `clu6` TEXT,
  `clu7` TEXT,
  `clu8` TEXT,
  `clu9` TEXT,
  `clu10` TEXT,
  `clu11` TEXT,
  `clu12` TEXT,
  `clu13` TEXT,
  `clu14` TEXT,
  `clu15` TEXT,
  `clu16` TEXT,
  `clu17` TEXT,
  `clu18` TEXT,
  `clu19` TEXT,
  `clu20` TEXT,
  `clu21` TEXT,
  `clu22` TEXT,
  `clu23` TEXT,
  `clu24` TEXT,
  `clu25` TEXT,
  `clu26` TEXT,
  `clu27` TEXT,
  `clu28` TEXT,
  `clu29` TEXT,
  `clu30` TEXT,
  `clu31` TEXT,
  `clu32` TEXT,
  `clu33` TEXT,
  `clu34` TEXT,
  `clu35` TEXT,
  `clu36` TEXT,
  `clu37` TEXT,
  `clu38` TEXT,
  `clu39` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sire_rvie` (
  `id_contribuyente` INT(11) NOT NULL,
  `tipo_envio_sunat` TEXT,
  `periodo_anio` TEXT,
  `periodo_mes` TEXT,
  `num_ticket` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sire_rvie_item` (
  `id` INT(11) NOT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `tipo_envio_sunat` TEXT,
  `periodo_anio` TEXT,
  `periodo_mes` TEXT,
  `num_ticket` TEXT,
  `ruc` TEXT,
  `razon_social` TEXT,
  `periodo` TEXT,
  `car_sunat` TEXT,
  `fecha_emision` TEXT,
  `fecha_vcto_pago` TEXT,
  `tipo_cp_doc` TEXT,
  `serie_cdp` TEXT,
  `nro_cp_doc_inicial` TEXT,
  `nro_cp_doc_final` TEXT,
  `tipo_doc_identidad` TEXT,
  `nro_doc_identidad` TEXT,
  `apellidos_nombres_razon_social` TEXT,
  `valor_facturado_exportacion` DECIMAL(18,6) DEFAULT NULL,
  `bi_gravada` DECIMAL(18,6) DEFAULT NULL,
  `dscto_bi` DECIMAL(18,6) DEFAULT NULL,
  `igv_ipm` DECIMAL(18,6) DEFAULT NULL,
  `dscto_igv_ipm` DECIMAL(18,6) DEFAULT NULL,
  `mto_exonerado` DECIMAL(18,6) DEFAULT NULL,
  `mto_inafecto` DECIMAL(18,6) DEFAULT NULL,
  `isc` DECIMAL(18,6) DEFAULT NULL,
  `bi_grav_ivap` DECIMAL(18,6) DEFAULT NULL,
  `ivap` DECIMAL(18,6) DEFAULT NULL,
  `icbper` DECIMAL(18,6) DEFAULT NULL,
  `otros_tributos` DECIMAL(18,6) DEFAULT NULL,
  `total_cp` DECIMAL(18,6) DEFAULT NULL,
  `moneda` TEXT,
  `tipo_cambio` DECIMAL(18,6) DEFAULT NULL,
  `fecha_emision_doc_modificado` TEXT,
  `tipo_cp_modificado` TEXT,
  `serie_cp_modificado` TEXT,
  `nro_cp_modificado` TEXT,
  `id_proyecto_operadores_atribucion` TEXT,
  `tipo_de_nota` TEXT,
  `est_comp` TEXT,
  `valor_fob_embarcado` DECIMAL(18,6) DEFAULT NULL,
  `valor_op_gratuitas` DECIMAL(18,6) DEFAULT NULL,
  `tipo_operacion` TEXT,
  `dam_cp` TEXT,
  `clu` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sucursal` (
  `idsucursal` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `codigo` TEXT,
  `nombre` TEXT,
  `factor_igv` DECIMAL(18,6) DEFAULT NULL,
  `direccion` TEXT,
  `id_ubigeo` TEXT,
  `urbanizacion` TEXT,
  `telefono` TEXT,
  `email` TEXT,
  `sitio_web` TEXT,
  `informacion_adicional` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  `leyenda_comprobantes` TEXT,
  `factura_serie` TEXT,
  `factura_numero` INT(11) DEFAULT NULL,
  `factura_formato` TEXT,
  `boleta_serie` TEXT,
  `boleta_numero` INT(11) DEFAULT NULL,
  `boleta_formato` TEXT,
  `notacredito_factura_serie` TEXT,
  `notacredito_factura_numero` INT(11) DEFAULT NULL,
  `notacredito_factura_formato` TEXT,
  `notadebito_factura_serie` TEXT,
  `notadebito_factura_numero` INT(11) DEFAULT NULL,
  `notadebito_factura_formato` TEXT,
  `notacredito_boleta_serie` TEXT,
  `notacredito_boleta_numero` INT(11) DEFAULT NULL,
  `notacredito_boleta_formato` TEXT,
  `notadebito_boleta_serie` TEXT,
  `notadebito_boleta_numero` INT(11) DEFAULT NULL,
  `notadebito_boleta_formato` TEXT,
  `guia_remision_serie` TEXT,
  `guia_remision_numero` INT(11) DEFAULT NULL,
  `guia_remision_formato` TEXT,
  `guia_transportista_serie` TEXT,
  `guia_transportista_numero` INT(11) DEFAULT NULL,
  `guia_transportista_formato` TEXT,
  `orden_compra_serie` TEXT,
  `orden_compra_numero` INT(11) DEFAULT NULL,
  `orden_compra_formato` TEXT,
  `txt_pdf_a4_1` TEXT,
  `txt_pdf_a4_2` TEXT,
  `txt_pdf_a4_3` TEXT,
  `txt_pdf_ticket_1` TEXT,
  `txt_pdf_ticket_2` TEXT,
  `txt_pdf_ticket_3` TEXT,
  `plantilla_pdf_a4` INT(11) DEFAULT NULL,
  `plantilla_pdf_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_factura_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_factura_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_boleta_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_boleta_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_notacredito_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_notacredito_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_notadebito_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_notadebito_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_guiaremision_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_guiaremision_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_notaventa_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_notaventa_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_cotizacion_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_cotizacion_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_guiatransportista_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_guiatransportista_ticket` INT(11) DEFAULT NULL,
  `id_plantillapdf_ordencompra_a4` INT(11) DEFAULT NULL,
  `id_plantillapdf_ordencompra_ticket` INT(11) DEFAULT NULL,
  `boleta_mostrar_items_igv` TEXT,
  `factura_mostrar_items_igv` TEXT,
  `notacredito_mostrar_items_igv` TEXT,
  `notadebito_mostrar_items_igv` TEXT,
  `guiaremision_mostrar_items_igv` TEXT,
  `cotizacion_mostrar_items_igv` TEXT,
  `notaventa_mostrar_items_igv` TEXT,
  `ordencompra_mostrar_items_igv` TEXT,
  `img_qr_yape` TEXT,
  `titular_yape` TEXT,
  `celular_yape` TEXT,
  `img_qr_plin` TEXT,
  `titular_plin` TEXT,
  `celular_plin` TEXT,
  `boleta_mostrar_yape` TEXT,
  `factura_mostrar_yape` TEXT,
  `notaventa_mostrar_yape` TEXT,
  `boleta_mostrar_plin` TEXT,
  `factura_mostrar_plin` TEXT,
  `notaventa_mostrar_plin` TEXT,
  `cotizacion_mostrar_yape` TEXT,
  `cotizacion_mostrar_plin` TEXT,
  `glosa_amazonia` TEXT,
  PRIMARY KEY (`idsucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sucursal_opcion` (
  `id_contribuyente` INT(11) NOT NULL,
  `id_sucursal` INT(11) NOT NULL,
  `opcion_nombre` VARCHAR(191) NOT NULL,
  `opcion_valor` TEXT,
  PRIMARY KEY (`id_contribuyente`, `id_sucursal`, `opcion_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_bienesdetracciones` (
  `id_biendetraccion` TEXT,
  `descripcion` MEDIUMTEXT,
  `porcentaje` DECIMAL(18,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigodetraccion` (
  `id_cod_detraccion` TEXT,
  `descripcion` MEDIUMTEXT,
  `porcentaje` DECIMAL(18,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigoentidadfinanciera` (
  `id_entidadfinanciera` TEXT,
  `descripcion` MEDIUMTEXT,
  `estado` TEXT,
  `logo` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigoprecio` (
  `id_codigoprecio` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigoproducto` (
  `id_codigoproducto` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigopuerto` (
  `id_codigopuerto` TEXT,
  `descripcion` MEDIUMTEXT,
  `codigo` TEXT,
  `ubigeo` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigoretorno` (
  `codigo` TEXT,
  `descripcion` MEDIUMTEXT,
  `nota` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigotipopercepcion` (
  `codigo` TEXT,
  `descripcion` MEDIUMTEXT,
  `porcentaje` DECIMAL(18,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codigoubigeo` (
  `codigo_ubigeo` VARCHAR(191) NOT NULL,
  `departamento` TEXT,
  `provincia` TEXT,
  `distrito` TEXT,
  PRIMARY KEY (`codigo_ubigeo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codprodclase` (
  `idclase` INT(11) NOT NULL AUTO_INCREMENT,
  `idfamilia` INT(11) DEFAULT NULL,
  `descripcion` MEDIUMTEXT,
  PRIMARY KEY (`idclase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codprodfamilia` (
  `idfamilia` INT(11) NOT NULL AUTO_INCREMENT,
  `idsegmento` INT(11) DEFAULT NULL,
  `descripcion` MEDIUMTEXT,
  PRIMARY KEY (`idfamilia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codprodsegmento` (
  `idsegmento` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` MEDIUMTEXT,
  PRIMARY KEY (`idsegmento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_codproducto` (
  `codigoproducto` TEXT,
  `idclase` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` MEDIUMTEXT,
  `cod_alternativo` TEXT,
  PRIMARY KEY (`idclase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_icbper` (
  `anio` INT(11) NOT NULL,
  `monto` DECIMAL(18,6) DEFAULT NULL,
  PRIMARY KEY (`anio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_mediosdepago` (
  `id_mediopago` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_modalidadtraslado` (
  `id_modalidadtraslado` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_moneda` (
  `id_codigomoneda` TEXT,
  `simbolo` TEXT,
  `nombre` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_motivotraslado` (
  `id_motivotraslado` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tipoafectacionigv` (
  `id_tipoafectacionigv` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tipodecambio` (
  `fecha` TEXT,
  `compra` DECIMAL(18,6) DEFAULT NULL,
  `venta` DECIMAL(18,6) DEFAULT NULL,
  `moneda` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tipodocelectronico` (
  `id_tipodoc_electronico` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tipodocidentidad` (
  `id_tipodocidentidad` TEXT,
  `nombre` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tiponotacredito` (
  `id_tiponotacredito` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tiponotadebito` (
  `id_tiponotadebito` TEXT,
  `descripcion` MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tipooperacion` (
  `id_codigotipooperacion` TEXT,
  `descripcion` MEDIUMTEXT,
  `orden` INT(11) NOT NULL,
  PRIMARY KEY (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_tiporegimen` (
  `idregimen` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT,
  `codigo_libro` TEXT,
  PRIMARY KEY (`idregimen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sunat_unidadmedida` (
  `idunidad` INT(11) NOT NULL AUTO_INCREMENT,
  `codigo` TEXT,
  `nombre` TEXT,
  `simbolo` TEXT,
  PRIMARY KEY (`idunidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `suscripcion` (
  `id_suscripcion` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_planreseller` INT(11) DEFAULT NULL,
  `fecha_registro` TEXT,
  `fecha_inicio` TEXT,
  `fecha_fin` TEXT,
  `limite_mes_doc` INT(11) DEFAULT NULL,
  `total` DECIMAL(18,6) DEFAULT NULL,
  `condicion_pago` TEXT,
  `tipo_condicionpago` TEXT,
  `num_transaccion` TEXT,
  `nota` TEXT,
  `estado` TEXT,
  `pago_verificado` TEXT,
  `nota_pago_verificado` TEXT,
  `id_suscripcion_culqi` INT(11) DEFAULT NULL,
  `id_charge_culqi` TEXT,
  PRIMARY KEY (`id_suscripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `suscripcion_culqi` (
  `id_suscripcion_culqi` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `id_planreseller` INT(11) DEFAULT NULL,
  `id_suscripcion` TEXT,
  `fecha_registro` TEXT,
  `fecha_modificacion` TEXT,
  `id_usuario_registro` INT(11) DEFAULT NULL,
  `id_usuario_modifica` INT(11) DEFAULT NULL,
  `monto` DECIMAL(18,6) DEFAULT NULL,
  `periodo_prueba` INT(11) DEFAULT NULL,
  `fecha_pago` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_suscripcion_culqi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `system_option` (
  `id_contribuyente` INT(11) NOT NULL,
  `option_name` TEXT,
  `option_value` TEXT,
  PRIMARY KEY (`id_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_token` (
  `idtoken` INT(11) DEFAULT NULL,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `token` MEDIUMTEXT,
  `expire_time` INT(11) DEFAULT NULL,
  `device` TEXT,
  `expire_at` TEXT,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contribuyente` INT(11) DEFAULT NULL,
  `codigo` TEXT,
  `id_rol` INT(11) DEFAULT NULL,
  `nombre` TEXT,
  `apellido` TEXT,
  `celular` TEXT,
  `telefono` TEXT,
  `email` TEXT,
  `password` TEXT,
  `url_image` TEXT,
  `fecha_registro` TEXT,
  `estado` TEXT,
  `idsucursal` INT(11) DEFAULT NULL,
  `ver_ventas_totales` TEXT,
  `modificacion_almacen` TEXT,
  `modificacion_multi_almacen` TEXT,
  `ventas_multisucursal` TEXT,
  `acceso_mod_compras` TEXT,
  `validar_precio_minimo` TEXT,
  `permisos` TEXT,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `usuario_opcion` (
  `id_contribuyente` INT(11) NOT NULL,
  `idusuario` INT(11) NOT NULL,
  `opcion_nombre` VARCHAR(191) NOT NULL,
  `opcion_valor` TEXT,
  PRIMARY KEY (`id_contribuyente`, `idusuario`, `opcion_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vehiculo` (
  `id_vehiculo` INT(11) NOT NULL AUTO_INCREMENT,
  `id_etransporte` INT(11) DEFAULT NULL,
  `nro_placa` TEXT,
  `marca` TEXT,
  `const_inscripcion` TEXT,
  `capacidad` DECIMAL(18,6) DEFAULT NULL,
  `dgh` TEXT,
  `estado` TEXT,
  PRIMARY KEY (`id_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
