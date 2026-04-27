<style>
.dataTables_filter > label:after {
    content: "";
}
.dataTables_paginate .paginate_button.current, .dataTables_paginate .paginate_button.current:hover, .dataTables_paginate .paginate_button.current:focus {
    color: #fff;
    background-color: #3F51B5!important;
}
@media (min-width: 800px){
	.dataTables_filter > label:after {
    content: "\e98e";
	}
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Lista de Sucursales</span></h4>
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
		<div class="col-lg-12 col-lg-12" id="contenido_lista_sucursales">
			<div class="mb-2"  style="max-width: 1120px;margin: 0 auto;">
				<a target="_blank" href="/facturacionv8/branchoffice/" class="btn bg-indigo legitRipple" >
					<i class="fa fa-plus mr-2"></i>
					Agregar Sucursal
				</a>
			</div>
			<div style="margin-top: 25px;"></div>
			<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Sucursales</span>
						</legend>
					</fieldset>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
							<table class="table datatable-basic" id="tbl_lista_sucursales">
								<thead>
									<tr>
										<th>ID</th>
										<th>Código</th>
										<th>Sucursal</th>
										<th>Dirección</th>
										<th>Comprobantes y Series</th>
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
</div>