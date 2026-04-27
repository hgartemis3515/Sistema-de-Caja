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
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reporte Productos Vendidos</span></h4>
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

		<div class="col-md-12">
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
										Vendedor
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
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-cash2 mr-2"></i>
										Moneda
									</label>
									<select name="id_cod_moneda" id="id_cod_moneda" data-placeholder="Selecciona una Moneda..." class="select_minimizado select_criterio_visualizacion id_cod_moneda">
									<option value="PEN" selected>Soles</option>
									<option value="USD">Dólares</option>
								</select>
								</div>	
							</div>
							<div class="col-md-4">
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
										<option value="77">NOTAS DE VENTAS</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-question-circle mr-2"></i>
										Estado
									</label>
									<select class="multiselect" multiple="multiple" name="select_estado" id="select_estado">
										<option value="">Todos</option>
										<option value="aceptado" selected>Aceptado</option>
										<option value="rechazado">Rechazado</option>
										<option value="pendiente" selected>Pendiente</option>
										<option value="ticket" selected>Ticket</option>
										<option value="anulado">Anulado</option>
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

		<div class="col-md-4">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/icons/ventas_totales_64.png" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="ventas_totales"></h5>
						<div>Total en Ventas</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/icons/costo_total_64.png" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="costo_promedio_total"></h5>
						<div>Costo Prom. Total</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/icons/utilidad_total_64.png" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="utilidad_total"></h5>
						<div>Utilidad</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>
		
		<div class="col-md-12">
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_lista_reporte">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Reporte</span>
						</legend>
					</fieldset>
					<div class="row content-table">
						<div class="col-lg-12">
							<div class="table-responsive-2 overflow-auto">
								<table class="table table-bordered datatable-highlight" id="tbl_lista_reporte">
									<thead>
										<tr>
											<th>ID Producto</th> <!-- 0 -->
											<th>Código</th> <!-- 1 -->
											<th>Nombre</th> <!-- 2 -->
											<th>Unidad Medida</th> <!-- 3 -->
											<th>Marca</th>
											<th>Categoría</th>
											<th>Stock Actual</th> <!-- 6 -->
											<th>Cantidad Vendida</th> <!-- 7 -->
											<th>Moneda</th> <!-- 8 -->
											<th>P.Venta Prom. Unit.</th> <!-- 9 -->
											<th>igv</th><!-- 10 -->
											<th>SubTotal</th> <!-- 11 -->
											<th>Total</th> <!-- 12 -->
											<th>Costo Unitario</th>
											<th>Costo Unitario</th> <!-- 14 -->
											<th>Costo Total</th> <!-- 15 -->
											<th>Utilidad</th> <!-- 16 -->
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="10"></th>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tfoot>
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