<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Planes</span></h4>
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
		<div class="col-lg-12 col-md-12">
			<div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body" id="content_planes">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="text-uppercase">Planes</span> 
						</legend>
					</fieldset>
					<form name="frm_planes" id="frm_planes" action="">
						<input type="hidden" id="idusuario" name="idusuario" value="" />
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="label-form">
										<i class=" fa fa-file mr-2"></i> 
										Nombre del plan
									</label>
									<input type="text" class="form-control form-control-sm" name="nombre_plan" id="nombre_plan" placeholder="Nombre de plan">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-money mr-2"></i>
										Precio base
									</label>
									<input type="text" class="form-control form-control-sm" name="precio_base" id="precio_base" placeholder="Precio Base">
								</div>
                            </div>
                            <div class="col-lg-4">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-money mr-2"></i>
										Precio recomendado
									</label>
									<input type="text" class="form-control form-control-sm" name="precio_recomendado" id="precio_recomendado" placeholder="Precio recomendado">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-compress mr-2"></i> 
										Limite doc
									</label>
									<input type="text" class="form-control form-control-sm" name="limite_doc" id="limite_doc" placeholder="Limite documento">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label class="label-form">
										<i class="fa fa-sticky-note mr-2"></i>
										Nota
									</label>
									<textarea name="nota" id="nota" class="form-control"></textarea>
								</div>
							</div>
							
							<div class="float-right">
								<button class="btn bg-indigo legitRipple btn_save_plan" type="button">
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
				<div class="panel-body"  id="content_lista_planes">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="fa fa-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Planes</span>
						</legend>
					</fieldset>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
							<table class="table datatable-basic" id="tbl_lista_planes">
								<thead>
									<tr>
										<th>ID</th>
										<th>Nombre</th>
										<th>Fecha_registro</th>
										<th>Precio base</th>
										<th>Precio recomendado</th>
										<th>Limite</th>
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
		© 2018. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank">Alex Castañeda</a>
	</div>
</div>