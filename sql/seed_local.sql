-- Datos mínimos para desarrollo local (tras schema_from_models.sql)
SET NAMES utf8mb4;

INSERT IGNORE INTO `rol_usuario` (`id_rol`, `nombre`, `alias`, `descripcion`) VALUES
(1, 'Administrador', 'admin', 'Rol local'),
(2, 'Vendedor', 'vendedor', 'Rol local'),
(3, 'Administrador empresa', 'admin_emp', 'Rol local');

INSERT INTO `contribuyente` (`id_contribuyente`, `ruc`, `razon_social`, `nombre_comercial`, `tipo_envio_sunat`, `estado`, `id_patrocinador`, `tipo_empresa`, `https`, `restriccion_stock`, `multi_almacen`, `num_decimales`, `precio_venta_minimo`, `modulo_marketing`, `envio_automatico_docs`, `mostrar_codprod_pdfs`, `cotizacion_con_igv`, `mostrar_uprecio_clieprod`, `tiene_detracciones`, `tiene_percepciones`, `tipo_busqueda_doc`, `ver_fecha_vencimiento`, `ver_marca`, `informar_condpago_sunat`, `mostrar_aviso`, `mype`)
VALUES (1, '00000000000', 'Empresa desarrollo local', 'Empresa local', 'prueba', 'activo', 0, 1, 'si', 'no', 'no', 2, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'completa', 'no', 'no', 'no', 'no', 'no')
ON DUPLICATE KEY UPDATE `razon_social` = VALUES(`razon_social`);

INSERT INTO `sucursal` (`idsucursal`, `id_contribuyente`, `codigo`, `nombre`, `factor_igv`, `direccion`, `id_ubigeo`, `telefono`, `email`, `fecha_registro`, `estado`)
VALUES (1, 1, '0000', 'Principal', 0.18, '-', '150101', '', '', NOW(), 'activo')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

INSERT INTO `usuario` (`idusuario`, `id_contribuyente`, `codigo`, `id_rol`, `nombre`, `apellido`, `email`, `password`, `fecha_registro`, `estado`, `idsucursal`, `ver_ventas_totales`, `modificacion_almacen`, `modificacion_multi_almacen`, `ventas_multisucursal`, `acceso_mod_compras`, `validar_precio_minimo`, `permisos`)
VALUES (1, 1, 'LOCALDEV000001', 1, 'Admin', 'Local', 'admin@local.test', 'Admin123', NOW(), 'activo', 1, 'si', 'si', 'si', 'si', 'si', 'no', '')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`), `password` = VALUES(`password`);

INSERT INTO `data_extra` (`id_dataextra`, `html_sitioweb`, `url_guiausuario`, `id_contribuyente`)
VALUES (1, '<div class="container py-5"><h1>Sistema de caja — entorno local</h1><p>Esquema generado desde modelos Phalcon. Inicia sesión en <a href="/login">Login</a> con <strong>admin@local.test</strong> / <strong>Admin123</strong>.</p></div>', '', 1)
ON DUPLICATE KEY UPDATE `html_sitioweb` = VALUES(`html_sitioweb`);
