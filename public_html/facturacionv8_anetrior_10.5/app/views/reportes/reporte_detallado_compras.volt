<style>
.btn-default{
	color: #333!important;
	background-color: #fff!important;
	background-image: none!important;
	border: 1px solid #ddd!important;
	font-weight: normal!important; 
	padding: 5px 12px;
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
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reporte detallado de Compras</span></h4>
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
<div class="content" id="contenido_reporte_detallado">
	<div class="row">
		<div class="col-md-12 col-md-12">
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_panel_usuario">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="text-uppercase">Filtros avanzados</span> 
						</legend>
					</fieldset>
					<form name="frm_reporte_detallado" id="frm_reporte_detallado" action="">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i>
										Usuario
									</label>
									<select name="select_vendedor" id="select_vendedor" class="multiselect" multiple="multiple">
										<option value="0" selected>Todos</option>
										<?php
										foreach($lista_usuarios as $usuario) {
											echo '<option value="'.$usuario->idusuario.'">'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
										}
										?>
									</select>
								</div>	
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-profile position-left"></i>
										Sucursal
									</label>
									<select name="select_sucursal" id="select_sucursal" class="multiselect" multiple="multiple">
										<option value="0" selected>Todos</option>
										<?php
										foreach($lista_sucursales as $sucursal) {
											echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
										}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-cash2 mr-2"></i>
										Moneda
									</label>
									<select class="multiselect" multiple="multiple" name="id_cod_moneda" id="id_cod_moneda">
										<option value="" selected>Todos</option>
										<option value="PEN">Soles (S/)</option>
										<option value="USD">Dólares Americanos ($)</option>
									</select>
								</div>	
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-question-circle mr-2"></i>
										Estado
									</label>
									<select class="multiselect" multiple="multiple" name="select_estado" id="select_estado">
										<option value="activo" selected>Activos</option>
										<option value="inactivo">Anulados</option>
									</select>
								</div>	
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-profile position-left"></i> 
										Tipo de comprobante
									</label>
									<select class="multiselect" multiple="multiple" name="select_tipo_comprobante" id="select_tipo_comprobante">
										<option value="" selected>Todos</option>
										<option value="03">BOLETAS</option>
										<option value="01">FACTURAS</option>
										<option value="07">NOTAS DE CRÉDITO</option>
										<option value="08">NOTAS DE DÉBITO</option>
										<option value="00">OTRO DOC.</option>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<label class="label-form" for="select_tipofecha_busqueda"><i class="icon-file-empty position-left"></i> Mostrar facturas por:</label>
								<div class="form-group has-feedback has-feedback-left">
									<select name="select_criteriobusqueda" id="select_criteriobusqueda" data-placeholder="Selecciona un Periodo..." class="select_criteriobusqueda select_minimizado">
										<option value="fecha_documento">Fecha del Documento</option>
										<option value="fecha_registro">Fecha de Registro</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha inicio
									</label>
									<input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_inicio" id="fecha_inicio">
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha fin
									</label>
									<input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_fin" id="fecha_fin">
								</div>
							</div>
							
							<div class="col-md-12" style="display: none;">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-shopping-bag mr-2"></i>
										Producto
									</label>
									<select class="select  select_producto" name="select_producto" id="select_producto">
										<option value="0" selected>Todos</option>
									</select>
								</div>	
							</div>
							<div class="float-right">
								<button class="btn bg-indigo legitRipple btn_generar_reporte_detallado" type="button">
									Generar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title">Reporte Detallado de Compras</h6>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a data-action="collapse"></a></li>
							<li><a data-action="reload"></a></li>
							<li><a data-action="close"></a></li>
						</ul>
					</div>
				</div>

				<div class="panel-body">
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-highlight">
							<li class="active"><a href="#left-icon-tab1" data-toggle="tab"><img src="https://arpsystem.com.pe/facturacionv8/public/img/icon_documento.png" style="width:32px;" /> Documentos de Compra</a></li>
							<li><a href="#left-icon-tab2" data-toggle="tab"><img src="https://arpsystem.com.pe/facturacionv8/public/img/icon_detalle_documento.png" style="width:32px;" /> Detalle de Doc. Compras</a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="left-icon-tab1">
								<div class="table-responsive">
									<table class="table datatable-basic" id="tbl_lista_documentos">
										<thead>
											<tr>
												<th>Fecha Registro</th>
												<th>Fecha Comprobante</th>

												<th>C.Documento</th>
												<th>Documento</th>
												<th>Serie</th>
												<th>Correlativo</th>
												<th>ID Proveedor</th>
												<th>RUC</th>
												<th>Razón Social</th>
												<th>Email</th>
												<th>Celular</th>
												
												<th>Moneda</th>
												<th>Tipo Cambio</th>

												<th>Gravado</th>
												<th>Inafecto</th>
												<th>Exonerado</th>
												<th>Gratuito</th>
												<th>Exportación</th>
												<th>Descuento</th>
												<th>Sub Total</th>
												<th>IGV</th>
												<th>ISC</th>
												<th>ICBPER</th>
												<th>OtrImp.</th>
												<th>total</th>
												
												<th>Nota</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane" id="left-icon-tab2">
								<div class="table-responsive">
									<table class="table datatable-basic" id="tbl_lista_documentos_detalle">
										<thead>
											<tr>
												<th>Fecha Registro</th>
												<th>Fecha Comprobante</th>

												<th>C.Documento</th>
												<th>Documento</th>
												<th>Serie</th>
												<th>Correlativo</th>
												<th>ID Proveedor</th>
												<th>RUC</th>
												<th>Razón Social</th>
												<th>Email</th>
												<th>Celular</th>
												
												<th>Moneda</th>
												<th>Tipo Cambio</th>

												<th>Gravado</th>
												<th>Inafecto</th>
												<th>Exonerado</th>
												<th>Gratuito</th>
												<th>Exportación</th>
												<th>Descuento</th>
												<th>Sub Total</th>
												<th>IGV</th>
												<th>ISC</th>
												<th>ICBPER</th>
												<th>OtrImp.</th>
												<th>total</th>
												<th>Nota</th>

												<th>IdProducto</th>
												<th>Codigo Producto</th>
												<th>Descripción</th>
												<th>Cantidad</th>
												<th>Unidad</th>
												<th>Precio</th>
												<th>Precio sin IGV</th>
												<th>Sub Total</th>
												<th>IGV</th>
												<th>Importe</th>
												<th>Codigo Afect.IGV</th>
												<th>Afectación IGV</th>
												<th>Tipo Unidad</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
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