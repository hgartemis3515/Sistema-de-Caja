<style>
.label-form{
	padding: 5px 5px;
}
td > .form-control {
    display: block;
    width: 60px;
}
@media (min-width: 800px){
	td > .form-control {
    width: 100%;
	}
}
@media (min-width: 769px){
	.nav-tabs.nav-tabs-highlight > li.active > a, .nav-tabs.nav-tabs-highlight > li.active > a:hover, .nav-tabs.nav-tabs-highlight > li.active > a:focus {
		font-weight: 700;
	}
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php if($idsucursal <= 0){ echo 'Agregar Nueva Sucursal'; } else { echo 'Editar Sucursal'; } ?></span></h4>
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
<div class="content" id="contenido_sucursal">
	<form name="frm_branchoffice" id="frm_branchoffice" action="">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-flat" style="max-width: 1120px;margin: 0 auto;">
					<div class="panel-body">
						<div class="tabbable" id="tab_resumen_ig">
							<ul class="nav nav-tabs nav-tabs-highlight">
								<li class="active"><a href="#tab_info_sucursal" data-toggle="tab" class="tab_opcion"><i class="icon-home"></i> Info. Sucursal/Almacén</a></li>
								<li><a href="#tab_series_correlativos" class="tab_opcion" data-toggle="tab"><i class="icon-qrcode"></i> Series/Correlativos</a></li>
								<li><a href="#tab_diseno_pdf" class="tab_opcion" data-toggle="tab"><i class="icon-file-pdf"></i> Diseño del PDF</a></li>
								<li><a href="#tab_yape_plin" class="tab_opcion" data-toggle="tab"><i class="icon-file-pdf"></i> Yape - Plin</a></li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="tab_info_sucursal">
									<div class="col-lg-12">
										<?= $this->partial('branchoffice/tab_info_sucursal') ?>
									</div>
								</div>

								<div class="tab-pane" id="tab_series_correlativos">
									<div class="col-lg-12">
										<?= $this->partial('branchoffice/tab_series_correlativos') ?>
									</div>
								</div>

								<div class="tab-pane" id="tab_diseno_pdf">
									<div class="col-lg-12">
										<?= $this->partial('branchoffice/tab_diseno_pdf') ?>
									</div>
								</div>

								<div class="tab-pane" id="tab_yape_plin">
									<div class="col-lg-12">
										<?= $this->partial('branchoffice/tab_yape_plin') ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="footer text-muted">
		© 2018. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>