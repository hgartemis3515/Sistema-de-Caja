<style>
.btn-default{
	color: #333!important;
	background-color: #fff!important;
	background-image: none!important;
	border: 1px solid #ddd!important;
	font-weight: normal!important; 
	padding: 7px 12px;
}

.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
	right: 0px;
}
.overflow-auto{
	overflow: auto;

}
.dropdown-menu>li>a {
	display: block;
	padding: 3px 20px;
	/* clear: both; */
	font-weight: 400;
	line-height: 1.5384616;
	color: #333;
	white-space: initial;
}

.mb-3{
	margin-bottom: 30px;
}
.nav-tabs li.active {
	font-weight: 700;
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
.mt-5{
	margin-top: 20px;
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
.checkbox, .radio{
	left: 30%;
}
.sin-padding{
	padding: 0!important;
}
.align-self-end {
	-ms-flex-item-align: end!important;
	align-self: flex-end!important;
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

.datatable-scroll-wrap {
	width: 100%;
	min-height: .01%;
	overflow-x: visible;
}
.tooltip.fade.top.in{
	position: absolute;
	top: -29px;
	left: 1216.12px;
}
.tooltip_small {
	width: 150px;
	color: #fff;
	text-align: center;
	background-color: #333;
	border-radius: 3px;
	position: absolute;
	top: -50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
.tooltip_small .tooltip-inner {
	padding: 7px 12px;
}

.tooltip_small .tooltip-arrow {
	position: absolute;
	width: 0;
	height: 0;
	border-color: transparent;
	border-style: solid;
}

.tooltip_small .tooltip-arrow {
	bottom: -4px;
	left: 50%;
	margin-left: -4px;
	border-width: 4px 4px 0;
	border-top-color: #333;
}
.tool_tip{
	position: relative;
}
.tool_tip:hover{
	cursor: grab;
}
/* tab style */
.nav_new_style {
	text-align: center;
}
.nav-tabs.nav_new_style.nav-tabs-solid>.active>a, .nav-tabs.nav_new_style.nav-tabs-solid>.active>a:focus, .nav-tabs.nav_new_style.nav-tabs-solid>.active>a:hover {
	margin: 0 10px;
	border-radius: 5rem!important;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
}
.nav-tabs.nav_new_style.nav-tabs-solid>li >a, .nav-tabs.nav_new_style.nav-tabs-solid>li >a:focus, .nav-tabs.nav_new_style.nav-tabs-solid> li >a:hover {
	background-color: #fff;
	border: 1px solid #ddd!important;
	border-radius: 5rem!important;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
}
.modal-header-bg{
	position: relative;
}
.modal-header-bg::after {
	content: ' ';
	position: absolute;
	top: -7em;
	left: 0;
	width: 100%;
	height: 400px;
	background-image: url(/facturacionv8/img/10.png);
	background-repeat: no-repeat;
}
.nav-tabs.nav-tabs-solid.nav_new_style {
	background-color: transparent;
}
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Caja chica</span></h4>
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
			<div class="panel panel-flat">
				<div class="panel-body">
					<div class="col-md-12">
						<form id="frm_filtros">
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha inicio
									</label>
									<input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha_inicio" name="fecha_inicio" id="fecha_inicio">
									<input type="hidden" name="fecha_inicio_valor" value="<?php echo date('Y-m-d 00:00:00'); ?>" id="fecha_inicio_valor" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha fin
									</label>
									<input type="text" value="<?php echo date('d/m/Y H:i:s'); ?>" class="form-control form-control-sm control_fecha_fin" name="fecha_fin" id="fecha_fin">
									<input type="hidden" name="fecha_fin_valor" value="<?php echo date('Y-m-d H:i:s'); ?>" id="fecha_fin_valor" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-profile position-left"></i>
										Sucursal
									</label>
									<select name="select_sucursal_filtro" id="select_sucursal_filtro" class="multiselect select_filtro_cajachica" multiple="multiple">
										<?php
										if($select_sucursal == 0) {
											echo '<option value="0" selected>Todos</option>';
										}
										foreach($lista_sucursales as $sucursal) {
											if($select_sucursal == $sucursal->idsucursal) {
												echo '<option value="'.$sucursal->idsucursal.'" selected>'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
											} else {
												echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-users mr-2"></i>
										Vendedor
									</label>
									<select name="select_vendedor_filtro" id="select_vendedor_filtro" class="multiselect select_filtro_cajachica" multiple="multiple">
										<?php
										if($select_vendedor == 0) {
											echo '<option value="0" selected>Todos</option>';
										}
										foreach($lista_usuarios as $usuario) {
											if($usuario->idusuario == $select_vendedor) {
												echo '<option value="'.$usuario->idusuario.'" selected>'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
											} else {
												echo '<option value="'.$usuario->idusuario.'">'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
											}
										}
										?>
									</select>
								</div>	
							</div>
							
						</form>
					</div>
						
					<div class="tabbable" id="tab_resumen_ig">
						<ul class="nav nav-tabs nav-tabs-highlight">
							<li class="active"><a href="#badges-tab1" data-toggle="tab" class="tab_opcion"><i class="icon-stats-growth"></i> Caja Chica</a></li>
							<li><a href="#badges-tab2" class="tab_opcion" data-toggle="tab"><i class="icon-transmission mr-2"></i>Ingresos / Egresos</a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="badges-tab1">
								<div class="col-md-12">
									<div class="text-right btn-cajachica">
										
									</div>
									{{ partial('cajachica/resumen_ventas') }}
								</div>
							</div>

							<div class="tab-pane" id="badges-tab2">
								<div class="col-md-12">
									{{ partial('cajachica/gastos_caja') }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="info_detalle_caja">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header modal-header-bg">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="body_info_detalle_caja">
				<div class="tabbable margin-top-20">
					<ul class="nav nav-tabs nav-tabs-solid border-0 nav_new_style">
						<li class="nav-item active"><a href="#lista_doc" class="nav-link active" data-toggle="tab">Lista de Documentos</a></li>
						<li class="nav-item"><a href="#detalle_doc" class="nav-link active" data-toggle="tab">Detalle Doc.</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="lista_doc">
							<div class="overflow-auto">
								<table id="tbl_lista_doc" class="table">
									<thead>
										<tr class="bg-indigo">
										<td>Documento</td>
										<td>Cliente</td>
										<td>Sucursal</td>
										<td>Moneda</td>
										<td>Gravado</td>
										<td>Inafecto</td>
										<td>Exonerado</td>
										<td>Gratuito</td>
										<td>Exportación</td>
										<td>ICBPER</td>
										<td>Descuento</td>
										<td>Sub Total</td>
										<td>IGV</td>
										<td>ISC</td>
										<td>OtrImp.</td>
										<td>TotalDoc.</td>
										<td>Deuda</td>
										<td>Pagado</td>
										<td>Mod.Pago</td>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>

						<div class="tab-pane" id="detalle_doc">
							<div class="overflow-auto">
								<table id="tbl_detalle_doc" class="table">
									<thead>
										<tr>
											<td>Documento</td>
											<td>Cliente</td>
											<td>Sucursal</td>
											<td>Moneda</td>
											<td>Gravado</td>
											<td>Inafecto</td>
											<td>Exonerado</td>
											<td>Gratuito</td>
											<td>Exportación</td>
											<td>ICBPER</td>
											<td>Descuento</td>
											<td>Sub Total</td>
											<td>IGV</td>
											<td>ISC</td>
											<td>OtrImp.</td>
											<td>TotalDoc.</td>
											<td>Deuda</td>
											<td>Pagado</td>
											<td>Mod.Pago</td>
											
											<th>Código Prod.</th>
											<th>Producto/Servicio</th>
											<th>Cantidad</th>
											<th>U.Medida</th>
											<th>Precio SinIgv</th>
											<th>IGV</th>
											<th>ISC</th>
											<th>ICBPER</th>
											<th>Importe</th>
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
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="info_detalle_abonos">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header modal-header-bg">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="body_info_detalle_abonos">
				<div class="row">
					<div class="col-lg-12">
						<div class="overflow-auto">
							<table id="tbl_detalle_abonos" class="table">
								<thead>
								<tr>
									<td>Documento</td>
									<td>Cliente</td>
									<td>Sucursal</td>
									<td>Moneda</td>
									<td>Gravado</td>
									<td>Inafecto</td>
									<td>Exonerado</td>
									<td>Gratuito</td>
									<td>Exportación</td>
									<td>ICBPER</td>
									<td>Descuento</td>
									<td>Sub Total</td>
									<td>IGV</td>
									<td>ISC</td>
									<td>OtrImp.</td>
									<td>TotalDoc.</td>
									<td>Deuda</td>
									<td>Pagado</td>
									
									<th>Fecha Abono</th>
									<th>Molidad Pago</th>
									<th>Monto Abono</th>
									<th>Nro. Operación</th>
									<th>Banco</th>
									<th>Detalle</th>

								</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>