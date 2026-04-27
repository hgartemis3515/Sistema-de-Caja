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
.nav-tabs li.active {
    font-weight: 700;
}
.mb-3{
	margin-bottom: 30px;
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
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cuentas por Cobrar</span></h4>
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
<div class="content" id="contenido_reporte_por_cobrar">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-flat">
				<div class="panel-body">
					<div class="col-md-12">
						<form id="frm_filtros">
							<div class="col-md-2">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha inicio
									</label>
									<input type="text" value="<?php echo date('d/m/Y', strtotime('-450 day')); ?>" class="form-control form-control-sm control_fecha_inicio" name="fecha_inicio" id="fecha_inicio">
									<input type="hidden" name="fecha_inicio_valor" value="<?php echo date('Y-m-d', strtotime('-450 day')); ?>" id="fecha_inicio_valor" />
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha fin
									</label>
									<input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha_fin" name="fecha_fin" id="fecha_fin">
									<input type="hidden" name="fecha_fin_valor" value="<?php echo date('Y-m-d'); ?>" id="fecha_fin_valor" />
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-file-text2 position-left"></i>Selecciona un Cliente:
									</label> 
									<select class="js-example-basic-single filtro_select" name="idcliente" id="idcliente">
										<option selected value="">Escribe el Número de RUC o Razón Social</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-profile position-left"></i>
										Tipo Venta
									</label>
									<select name="select_tipoventa" id="select_tipoventa" class="select_tipoventa filtro_select">
										<option value="todos">Todos</option>
										<option value="pagado">Pagado</option>
										<option value="pendiente" selected>Pendientes de Pago</option>
										<option value="vencidas">Pagos Vencidos</option>
									</select>
								</div>
							</div>

							<div class="col-md-2"> 
								<div class="form-group">
									<label class="label-form">
										<i class="icon-profile position-left"></i> 
										Tipo Doc.
									</label>
									<select class="multiselect filtro_select" multiple="multiple" name="select_tipo_comprobante[]" id="select_tipo_comprobante" >
										<option value="0" selected>Todos</option>
										<option value="03">BOLETAS</option>
										<option value="01">FACTURAS</option>
										<option value="77">NOTAS DE VENTAS</option>
									</select>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


		<div class="col-md-3">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/factura.svg" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="monto_deuda_facturas_soles"></h5>
						<div>Deuda en Facturas</div>
						<div class="text-size-small text-muted" id="monto_deuda_facturas_dolares"></div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/boleta.svg" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="monto_deuda_boletas_soles"></h5>
						<div>Deuda en Boletas</div>
						<div class="text-size-small text-muted" id="monto_deuda_boletas_dolares"></div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/nota_venta.svg" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="monto_deuda_nventas_soles"></h5>
						<div>Deuda en N. Ventas</div>
						<div class="text-size-small text-muted" id="monto_deuda_nventas_dolares"></div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/cuentas_por_cobrar_total.png" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="monto_deuda_total_soles"></h5>
						<div>Total Adeudado</div>
						<div class="text-size-small text-muted" id="monto_deuda_total_dolares"></div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>
					

		<div class="col-md-12">
			<div class="panel panel-flat">
				<div class="panel-body">  
					<div class="col-md-12">
						<div class="tabbable" id="tab_resumen_ig">
							<ul class="nav nav-tabs nav-tabs-highlight">
								<li class="active"><a href="#badges-tab1" data-toggle="tab" class="tab_opcion"><i class="icon-stats-growth mr-2"></i> CPE Sunat</a></li>
								<li><a href="#badges-tab2" class="tab_opcion" data-toggle="tab"><i class="icon-transmission mr-2"></i>Notas de Venta</a></li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="badges-tab1">								
									<div class="col-md-12">
										<?= $this->partial('cuentasporcobrar/cpe_sunat') ?>
									</div>
								</div>

								<div class="tab-pane" id="badges-tab2">
									<div class="col-md-12">
										<?= $this->partial('cuentasporcobrar/notas_venta') ?>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>

<?= $this->partial('cuentasporcobrar/lista_abonos') ?>