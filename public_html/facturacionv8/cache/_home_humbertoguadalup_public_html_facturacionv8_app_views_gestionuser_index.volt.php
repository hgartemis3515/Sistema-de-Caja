<style>

.dataTables_filter > label:after {
    content: "";
}
@media (min-width: 800px){
	.dataTables_filter > label:after {
    content: "\e98e";
	}
}

.btn-default{
	color: #333!important;
	background-color: #fff!important;
	background-image: none!important;
	border: 1px solid #ddd!important;
	font-weight: normal!important; 
	padding: 6px 12px;
}

.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
	right: 0px;
}
.overflow-auto{
	overflow: auto;

}
/* LOADING */
.loading {
	/* position: absolute; */
	top: 50%;
	left: 50%;
}
.loading-bar {
	display: inline-block;
	width: 4px;
	height: 18px;
	border-radius: 4px;
	animation: loading 1s ease-in-out infinite;
}
.loading-bar:nth-child(1) {
	background-color: #3f51b5;
	animation-delay: 0;
}
.loading-bar:nth-child(2) {
	background-color: #2196f3;
	animation-delay: 0.09s;
}
.loading-bar:nth-child(3) {
	background-color: #4caf50;
	animation-delay: .18s;
}
.loading-bar:nth-child(4) {
	background-color: #00bcd4;
	animation-delay: .27s;
}
.multiselect-item label {
	display: block;
	margin: 0;
	height: 100%;
	cursor: pointer;
	padding: 8px 12px;
	padding-left: 42px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
@keyframes loading {
	0% {
	transform: scale(1);
	}
	20% {
	transform: scale(1, 2.2);
	}
	40% {
	transform: scale(1);
	}
}
/* /LOADING */
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de Usuarios</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/><span>Boleta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/factura.svg" style="width: 25px;"/><span>Factura</span></a>
				<a href="/facturacionv8/documentoelectronico/index/77/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/nota_venta.svg" style="width: 25px;"/><span>Nota de Venta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/88/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/cotizacion.svg" style="width: 25px;"/><span>Cotización</span></a>
				<a href="/facturacionv8/dashboard" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/dashboard.svg" style="width: 25px;"/><span>Dashboard</span></a>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-md-12 col-md-12">
			<div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body" id="content_panel_usuario">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="text-uppercase">Agregar Usuarios</span> 
						</legend>
					</fieldset>
					<form name="frm_gestionuser" id="frm_gestionuser" action="">
						<input type="hidden" id="idusuario" name="idusuario" value="" />
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i>
										Código Grupo
									</label>
									<div class="input-group">
										<input type="text" name="codigo" id="txt_codigo" class="form-control" placeholder="Código">
										<span class="input-group-btn">
											<button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
												<i class="icon-rotate-ccw3 mr-2"></i>
												Generar
											</button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-lock2 mr-2"></i>
										Contraseña
									</label>
									<div class="input-group">
										<input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
										<div class="input-group-btn">
											<span id="show-passwd" action="hide" class="btn btn-default legitRipple"><i class="icon-eye-blocked"></i></span>
											<button class="btn bg-indigo legitRipple btn_generar_password" type="button">
												<i class="icon-rotate-ccw3 mr-2"></i>
												Generar
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i> 
										Nombre
									</label>
									<input type="text" class="form-control form-control-sm" name="nombre" id="nombre" placeholder="Nombre">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i> 
										Apellido
									</label>
									<input type="text" class="form-control form-control-sm" name="apellido" id="apellido" placeholder="Apellido">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i>
										Rol
									</label>
									<select class="select inputMaterial select_rol_usuario" name="rol" id="rol">
										<option value="3">Administrador</option>
										<option value="4">Colaborador</option>
									</select>
								</div>	
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-envelop mr-2"></i>
										Email
									</label>
									<input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-iphone mr-2"></i> 
										Célular
									</label>
									<input type="text" class="form-control form-control-sm" name="celular" id="celular" placeholder="Célular">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-phone2 mr-2"></i> 
										Teléfono
									</label>
									<input type="text" class="form-control form-control-sm" name="telefono" id="telefono"  placeholder="Teléfono">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i>
										Selecciona la sucursal/almacén a donde será asignado el usuario:
									</label>
									<select class="select inputMaterial" name="idsucursal" id="idsucursal">
										
									</select>
								</div>	
							</div>
							<div class="col-md-12" id="contenido_permisos_personalizados" style="display: none;">
								<div class="form-group">
									<label class="label-form text-semibold"><i class="icon-key position-left"></i>Permisos Personalizados para el Usuario</label>
									<div class="row">
										<div class="col-lg-12">
											<div class="panel border-top-indigo">
												<div class="panel-body">
													<div class="tabbable">
														<ul class="nav nav-xs nav-tabs nav-tabs-solid nav-tabs-component">
															<li class="active"><a href="#tab_opciones_registro" data-toggle="tab">Registro, Edición y/o Actualización</a></li>
															<li><a href="#tab_opciones_reportes" data-toggle="tab">Acceso a Reportes</a></li>
															<li><a href="#tab_opciones_menu" data-toggle="tab">Personalización de Menús</a></li>
															<li><a href="#tab_opciones_otros" data-toggle="tab">Otros</a></li>
														</ul>
				
														<div class="tab-content">
															<div class="tab-pane active" id="tab_opciones_registro">
																<!-- opciones para los registros, actualizaciones y ediciones -->
																<div class="row">
																	<div class="col-lg-6 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Módulo de Ventas
																			</label>
																			<select class="select inputMaterial" name="opt_registro_ventas" id="opt_registro_ventas">
																				<option value="sucursal_asignada">Registrar Ventas en Sucursal Asignada</option>
																				<option value="todas_las_sucursales" selected>Registrar Ventas en Todas las Sucursales</option>
																				<option value="prohibir">Prohibir Registro de Ventas</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-6 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Módulo de Compras
																			</label>
																			<select class="select inputMaterial" name="opt_registro_compras" id="opt_registro_compras">
																				<option value="sucursal_asignada">Registrar Compras en Sucursal Asignada</option>
																				<option value="todas_las_sucursales" selected>Registrar Compras en Todas las Sucursales</option>
																				<option value="prohibir">Prohibir Registro de Compras</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-6 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Almacén: Productos y Servicios
																			</label>
																			<select class="select inputMaterial" name="opt_registro_productos" id="opt_registro_productos">
																				<option value="sucursal_asignada">Registro y Actualización en Sucursal Asignada</option>
																				<option value="todas_las_sucursales" selected>Registro y Actualización en Todas las Sucursales </option>
																				<option value="prohibir">Prohibir Registro y Actualización</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-6 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Módulo Cuentas por Cobrar
																			</label>
																			<select class="select inputMaterial" name="opt_registro_cuentascobrar" id="opt_registro_cuentascobrar">
																				<option value="sucursal_asignada">Registrar Abonos en Sucursal Asignada</option>
																				<option value="todas_las_sucursales" selected>Registrar Abonos en Todas las Sucursales</option>
																				<option value="prohibir">Prohibir el Registro de Abonos</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-6 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Documentos Electrónicos
																			</label>
																			<select class="" multiple="multiple" name="opt_registro_documentos" id="opt_registro_documentos">
																				<option value="boleta">Registro de Boletas</option>
																				<option value="factura">Registro de Facturas</option>
																				<option value="nota_venta">Registro de Notas de Venta</option>
																				<option value="cotizacion">Registro de Cotizaciones</option>
																				<option value="guias_remision">Registro de Guías de Remisión</option>
																				<option value="notas_credito">Registro de Notas de Crédito para Facturas y Boletas</option>
																				<option value="notas_debito">Registro de Notas de Débito para Facturas y Boletas</option>

																				<option value="anulacion_boleta">Anulación de Boletas</option>
																				<option value="anulacion_factura">Anulación de Facturas</option>
																				<option value="anulacion_nota_venta">Anulación de Notas de Venta</option>
																				<option value="anulacion_cotizacion">Anulación de Cotizaciones</option>
																				<option value="anulacion_guias_remision">Anulación de Guías de Remisión</option>
																				<option value="anulacion_notas_credito">Anulación de Notas de Crédito para Facturas y Boletas</option>
																				<option value="anulacion_notas_debito">Anulación de Notas de Débito para Facturas y Boletas </option>

																				<option value="editar_cotizacion">Edición de Cotizaciones</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-6 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Fecha de Emisión del Comprobante
																			</label>
																			<select class="select inputMaterial" name="opt_cambio_fechaemision" id="opt_cambio_fechaemision">
																				<option value="permitir" selected>Permitir que el Usuario Cambie la Fecha de Emisión</option>
																				<option value="prohibir">Prohibir el Cambio de la Fecha de Emisión</option>
																			</select>
																		</div>	
																	</div>
																</div>
																<!-- /opciones para los registros, actualizaciones y ediciones -->
															</div>
				
															<div class="tab-pane" id="tab_opciones_reportes">
																<!-- Opciones para los Reportes -->
																<div class="row">
																	<div class="col-lg-4 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Módulo de Ventas
																			</label>
																			<select class="select inputMaterial" name="opt_verreportes_ventas" id="opt_verreportes_ventas">
																				<option value="propias">Ver solo las ventas registradas por el usuario</option>
																				<option value="sucursal_asignada" selected>Ver ventas de toda la sucursal Asignada</option>
																				<option value="todas_las_sucursales">Ver Ventas de todas las sucursales</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-4 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Módulo de Contabilidad
																			</label>
																			<select class="select inputMaterial" name="opt_verreportes_contabilidad" id="opt_verreportes_contabilidad">
																				<option value="permitir" selected>Permitir Acceso al Módulo Contable</option>
																				<option value="denegar">Prohibir Acceso al Módulo Contable</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-4 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Módulo Cuentas por Cobrar
																			</label>
																			<select class="select inputMaterial" name="opt_verreportes_cuentascobrar" id="opt_verreportes_cuentascobrar">
																				<option value="propias">Ver solo las ventas registradas por el usuario</option>
																				<option value="sucursal_asignada">Ver ventas de toda la sucursal Asignada</option>
																				<option value="todas_las_sucursales" selected>Ver Ventas de todas las sucursales</option>
																			</select>
																		</div>	
																	</div>

																	<div class="col-lg-12 mb-10">
																		<div class="form-group">
																			<label class="label-form">
																				<i class="icon-users mr-2"></i> Reportes
																			</label>
																			<select  class="" multiple="multiple" name="opt_verreportes_reportes" id="opt_verreportes_reportes">
																				<option value="detalle_ventas">Ver Reporte Detallado de Ventas</option>
																				<option value="productos_mas_vendidos">Ver Reporte de Productos Más Vendidos</option>
																				<option value="kardex">Ver Kardex</option>
																				<option value="top_clientes">Top Clientes</option>
																				<option value="top_vendedores">Top Vendedores</option>
																				<option value="top_estadisticas_dashboard">Ver Estadísticas Dashboard</option>
																			</select>
																		</div>	
																	</div>

																</div>
																<!-- /Opciones para los Reportes -->
															</div>
				
															<div class="tab-pane" id="tab_opciones_menu">
																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_cajachica" name="opt_menu_cajachica" class="control-primary" checked>
																			Caja Chica
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Caja Chica.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_cuentasporcobrar" name="opt_menu_cuentasporcobrar" class="control-info" checked>
																			Cuentas por Cobrar
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Cuentas por Cobrar.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_gestioncompras" name="opt_menu_gestioncompras" class="control-success" checked>
																			Gestión de Compras
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Compras.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_proveedores" name="opt_menu_proveedores" class="control-danger" checked>
																			Gestión de Proveedores
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Proveedores.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_guiaremision" name="opt_menu_guiaremision" class="control-primary" checked>
																			Guías de Remisión
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Guías de Remisión.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_resumenboletas" name="opt_menu_resumenboletas" class="control-info" checked>
																			Resumen de Boletas
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Resúmens Diario de Boletas.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_clientes" name="opt_menu_clientes" class="control-success" checked>
																			Clientes
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Clientes.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_inventario" name="opt_menu_inventario" class="control-danger" checked>
																			Inventario
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Inventario.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_contabilidad" name="opt_menu_contabilidad" class="control-success" checked>
																			Contabilidad
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al módulo de Contabilidad.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_top_clientes" name="opt_menu_top_clientes" class="control-success" checked>
																			Top Clientes
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al reporte Top Clientes.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_menu_top_vendedores" name="opt_menu_top_vendedores" class="control-success" checked>
																			Top Colaboradores
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario tendrá acceso al reporte Top Colaboradores.</div>
																		</label>
																	</div>
																</div>
																
															</div>

															<div class="tab-pane" id="tab_opciones_otros">
																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="opt_otros_costocompra" name="opt_otros_costocompra" class="control-primary" checked>
																			Costo de Compra
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar esta casilla el usuario podrá visualizar los costos de compra.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="modificar_precio_en_pantalla_venta" name="modificar_precio_en_pantalla_venta" class="control-primary" checked>
																			Modificar Precio en Pantalla de Venta
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar esta casilla esta casilla el sistema permitirá que el usuario pueda modificar el precio de venta.</div>
																		</label>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="venta_debajo_precio_minimo" name="venta_debajo_precio_minimo" class="control-primary" checked>
																			Permitir Ventas Debajo del Precio Mínimo
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar esta casilla el sistema permitirá que el usuario pueda hacer ventas debajo del precio mínimo para cualquier producto o presentación</div>
																		</label>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="checkbox">
																		<label>
																			<input type="checkbox" id="ocultar_opciones_avanzadas_dashboard" name="ocultar_opciones_avanzadas_dashboard" class="control-primary">
																			Ocultar Opciones Avanzadas en el Dashboard
																			<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar esta casilla el sistema ocultará la opción de opciones avanzadas en el dashboard</div>
																		</label>
																	</div>
																</div>
																
																<div class="col-lg-4">
																	<div class="form-group">
																		<label class="label-form">
																			<i class="icon-users mr-2"></i> Mostrar Ventas en el Dashboard
																		</label>
																		<select class="select inputMaterial" name="opt_rango_mostrar_ventas" id="opt_rango_mostrar_ventas">
																			<option value="ultimos_30_dias">Últimos 30 Días</option>
																			<option value="mes_actual">Mes Actual</option>
																			<option value="dia_actual">Día Actual</option>
																		</select>
																	</div>	
																</div>

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6" style="display: none;">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="ver_ventas_totales" name="ver_ventas_totales" class="control-primary">
													Ver Ventas de Toda la Empresa
													<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> El Usuario podrá visualizar las ventas de toda la empresa en general, si la opción está desactivada entonces el usuario podrá ver las ventas y listado de documentos solo de la sucursal a la cuál fué asignado.</div>
												</label>
											</div>

											<div class="checkbox">
												<label>
													<input type="checkbox" id="modificacion_almacen" name="modificacion_almacen" class="control-info">
													Permitir Registro/Actualización de Productos en la Sucursal Asignada
													<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario podrá hacer modificaciones y/o actualizaciones de productos en la sucursal asignada.</div>
												</label>
											</div>

											<div class="checkbox">
												<label>
													<input type="checkbox" id="modificacion_multi_almacen" name="modificacion_multi_almacen" class="control-success">
													Permitir Registro/Actualización de Productos en Todas las Sucursales
													<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Al marcar la opción el usuario podrá hacer modificaciones y/o actualizaciones de productos Todas las Sucursales.</div>
												</label>
											</div>
										</div>

										<div class="col-md-6" style="display: none;">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="ventas_multisucursal" name="ventas_multisucursal" class="control-success">
													Permitir Ventas en Diferentes Sucursales
													<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Si activas la opción el usuario podrá emitir comprobantes electrónicos en cualquier sucursal. Caso contrario el usuario solo podrá emitir comprobantes para la sucursal asignada.</div>
												</label>
											</div>

											<div class="checkbox">
												<label>
													<input type="checkbox" id="restriccion_precio_venta_minimo" name="restriccion_precio_venta_minimo" class="control-danger">
													¿Permitir Ventas Debajo del Precio Mínimo?
													<div class="text-muted text-size-small"><i class="icon-info3 text-size-mini position-left"></i> Si acivas la opción el usuario podrá registrar ventas por debajo del precio mínimo definido en cada producto.</div>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="text-right">
								<button class="btn bg-indigo legitRipple btn_saveuser" type="button">
									<i class="icon-floppy-disk mr-2"></i>
									Guardar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div style="margin-top: 25px;"></div>
			<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body"  id="content_lista_usuarios">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Usuarios</span>
						</legend>
					</fieldset>
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
							<table class="table datatable-basic" id="tbl_lista_usuarios">
								<thead>
									<tr>
										<th>ID</th>
										<th>Cód.Grupal</th>
										<th>Rol</th>
										<th>Usuario</th>
										<th>Celular</th>
										<th>Telefono</th>
										<th>Email</th>
										<th>Fec.Registro.</th>
										<th>Estado</th>
										<th class="text-center">Acción</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer text-muted">
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>