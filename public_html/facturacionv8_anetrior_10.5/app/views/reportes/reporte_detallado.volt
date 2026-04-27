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
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reporte detallado</span></h4>
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
									<select class="multiselect" multiple="multiple" name="id_cod_moneda" id="id_cod_moneda">
										<option value="" selected>Todos</option>
										<option value="PEN">Soles (S/)</option>
										<option value="USD">Dólares Americanos ($)</option>
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
										<option value="88">COTIZACIONES</option>
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
										<option value="" selected>Todos</option>
										<option value="aceptado">Aceptado</option>
										<option value="rechazado">Rechazado</option>
										<option value="pendiente">Pendiente</option>
										<option value="ticket">Ticket</option>
										<option value="activo">Activo</option>
										<option value="anulado">Anulado</option>
									</select>
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
					<h6 class="panel-title">Reporte Detallado de Ventas</h6>
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
							<li class="active"><a href="#left-icon-tab1" data-toggle="tab"><img src="https://arpsystem.com.pe/facturacionv8/public/img/icon_documento.png" style="width:32px;" /> Documentos de Venta</a></li>
							<li><a href="#left-icon-tab2" data-toggle="tab"><img src="https://arpsystem.com.pe/facturacionv8/public/img/icon_detalle_documento.png" style="width:32px;" /> Detalle de Doc. Ventas</a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="left-icon-tab1">
								<div class="table-responsive">
									<table class="table table-bordered datatable-highlight" id="tbl_lista_reporte_general">
										<thead>
											<tr>
												<th class="item-sucursal">Sucursal</th> <!-- 0 -->
												<th>Vendedor</th> <!-- 1 -->
												<!-- Documento electronico -->
												<th>Tipo_documento</th> <!-- 2 -->
												<th>Fecha documento</th> <!-- 3 -->

												<th>Moneda</th> <!-- 4 -->
												<th>Tipo de cambio</th> <!-- 5 -->

												<th>Cond.Pago Tipo</th> <!-- 4 -->
												<th>Cond.Pago Nombre</th> <!-- 5 -->
												<th>Cond.Pago NumOperación</th> <!-- 6 -->
												<th>Cond.Pago FechaDepósito</th> <!-- 7 -->
												<th>Cond.Pago IdBanco</th> <!-- 8 -->
												<th>Cond.Pago Banco</th> <!-- 9 -->

												<th>Placa</th> <!-- 10 -->

												<th>Serie-Núm.</th> <!-- 11 -->
												<!-- Documento electronico -->
												<th>Estado Doc.</th> <!-- 12 -->

												<th>Nro Orden</th>
												<th>Docs. Relacinados</th>
												
												<th>TipoDoc.Cliente</th> <!-- 13 -->
												<th>Num.Doc.Cliente</th> <!-- 14 -->
												<th>Nombre Cliente</th> <!-- 15 -->

												<th>Dirección</th> <!-- 16 -->
												<th>Cod.Ubigeo</th> <!-- 17 -->
												<th>Ubigeo.Cliente</th> <!-- 18 -->
												<!-- Detalles del reporte -->

												<!-- Totales -->
												<th>Tot.Export.</th> <!-- 19 -->
												<th>Tot.Gravado</th> <!-- 20 -->
												<th>Tot.Exonerdo</th> <!-- 21 -->
												<th>Tot.Inafecto</th> <!-- 22 -->
												<th>Tot.Gratuito</th> <!-- 23 -->
												<th>Tot.ICBPER</th> <!-- 24 -->
												<th>Tot.Descuento</th> <!-- 25 -->
												<th>Sub Total</th> <!-- 26 -->
												<th>% IGV</th> <!-- 27 -->
												<th>Total IGV</th> <!-- 28 -->
												<th>Total ISC</th> <!-- 29 -->
												<th>Otro Imp.</th> <!-- 30 -->
												<th>Total</th> <!-- 31 -->
												<!-- /Totales -->

												<th>Tipo.Doc.Modif</th> <!-- 32 -->
												<th>Serie.Doc.Modif</th> <!-- 33 -->
												<th>Num.Doc.Modif</th> <!-- 34 -->
												<th>Observación</th> <!-- 35 -->
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane" id="left-icon-tab2">
								<div class="table-responsive">
									<table class="table table-bordered datatable-highlight" id="tbl_lista_reporte">
										<thead>
											<tr>
												<th class="item-sucursal">Sucursal</th> <!-- 0 -->
												<th>Vendedor</th> <!-- 1 -->
												<!-- Documento electronico -->
												<th>Tipo_documento</th> <!-- 2 -->
												<th>Fecha documento</th> <!-- 3 -->

												<th>Moneda</th> <!-- 4 -->
												<th>Tipo de cambio</th> <!-- 5 -->

												<th>Cond.Pago Tipo</th> <!-- 4 -->
												<th>Cond.Pago Nombre</th> <!-- 5 -->
												<th>Cond.Pago NumOperación</th> <!-- 6 -->
												<th>Cond.Pago FechaDepósito</th> <!-- 7 -->
												<th>Cond.Pago IdBanco</th> <!-- 8 -->
												<th>Cond.Pago Banco</th> <!-- 9 -->

												<th>Placa</th> <!-- 10 -->

												<th>Serie-Núm.</th> <!-- 11 -->
												<!-- Documento electronico -->
												<th>Estado Doc.</th> <!-- 12 -->
												
												<th>TipoDoc.Cliente</th> <!-- 13 -->
												<th>Num.Doc.Cliente</th> <!-- 14 -->
												<th>Nombre Cliente</th> <!-- 15 -->

												<th>Dirección</th> <!-- 16 -->
												<th>Cod.Ubigeo</th> <!-- 17 -->
												<th>Ubigeo.Cliente</th> <!-- 18 -->
												<!-- Detalles del reporte -->

												<!-- Totales -->
												<th>Tot.Export.</th> <!-- 19 -->
												<th>Tot.Gravado</th> <!-- 20 -->
												<th>Tot.Exonerdo</th> <!-- 21 -->
												<th>Tot.Inafecto</th> <!-- 22 -->
												<th>Tot.Gratuito</th> <!-- 23 -->
												<th>Tot.ICBPER</th> <!-- 24 -->
												<th>Tot.Descuento</th> <!-- 25 -->
												<th>Sub Total</th> <!-- 26 -->
												<th>% IGV</th> <!-- 27 -->
												<th>Total IGV</th> <!-- 28 -->
												<th>Total ISC</th> <!-- 29 -->
												<th>Otro Imp.</th> <!-- 30 -->
												<th>Total</th> <!-- 31 -->
												<!-- /Totales -->

												<th>Tipo.Doc.Modif</th> <!-- 32 -->
												<th>Serie.Doc.Modif</th> <!-- 33 -->
												<th>Num.Doc.Modif</th> <!-- 34 -->
												<th>Observación</th> <!-- 35 -->

												<th>N° Item</th> <!-- 36 -->
												<th>IdProducto</th> <!-- 37 -->
												<th>Cod.Producto</th> <!-- 38 -->
												<th>Nombre Prod. BD</th> <!-- 39 -->
												<th>Producto/Servicio</th> <!-- 39 -->
												<th>MarcaProducto</th> <!-- 40 -->
												<th>Categoría</th> <!-- 41 -->
												<th>Cantidad</th> <!-- 42 -->
												<th>Unid.Medida</th> <!-- 43 -->
												<th>Precio_sin_igv</th> <!-- 44 -->
												<th>IGV</th> <!-- 45 -->
												<th>ISC</th> <!-- 46 -->
												<th>ICBPER</th> <!-- 47 -->
												<th>Importe</th> <!-- 48 -->

												<th>Costo.Unit.</th> <!-- 49 -->
												<th>CostoTotal</th> <!-- 50 -->
												<th>Utilidad</th> <!-- 51 -->

												<th>Stock</th> <!-- 52 -->
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