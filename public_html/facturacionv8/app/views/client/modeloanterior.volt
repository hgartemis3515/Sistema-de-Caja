<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Clientes</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-header page-header-light" style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
				<div class="page-header-content border-bottom-2 border-bottom-danger">
					<div class="page-title">
						<h5>
							<i class="fa fa-sort mr-2" aria-hidden="true"></i>
							<span class="font-weight-semibold">Agregar Cliente</span>
							<small class="d-block text-muted">Aquí puedes agregar tus clientes, llenando todos los datos vacíos. <span class="font-weight-bold">Atención, todos son requeridos.</span> </small>
						</h5>
					</div>
				</div>
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex cont-pt2">
						<div class="row">
							<div class="col-lg-12">
								<form name="frm_client" id="frm_client" action="">
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3 col-md-6 col-12">
											<i class="icon-user mr-2"></i> 
											Tipo de Documento de Identidad
										</label>
										<div class="col-lg-9 col-md-6 col-12">
											<select class="js-example-basic-single" name="id_cliente" id="id_cliente">
												<option value="">Option1</option>
													...
												<option value="">Option2</option>
											</select>
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-user mr-2"></i>
											Código
										</label>
										<div class="col-lg-9">
											<div class="input-group">
												<input type="text" name="codigo" id="codigo" class="form-control" placeholder="Código">
												<span class="input-group-btn">
													<button class="btn btn-info legitRipple" type="button">
														<i class="icon-rotate-ccw3 mr-2"></i>
														Generar
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-user mr-2"></i>
											D.N.I
										</label>
										<div class="col-lg-9">
											<div class="input-group">
												<input type="text" name="dni" id="dni" class="form-control" placeholder="D.N.I">
												<span class="input-group-btn">
													<button class="btn btn-info legitRipple" type="button">
															<i class="fa fa-search-plus"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-users2 mr-2"></i> 
											Razón social/Nombre Completo
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="razonsocial" id="razonsocial" placeholder="Razón Social">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-briefcase mr-2"></i> 
											Nombre Comercial
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="nombre_comercial" id="nombre_comercial" placeholder="Nombre Comercial">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-address-book mr-2"></i>
											Dirección fiscal
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Direccion fiscal">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
											Ubigeo
										</label>
										<div class="col-lg-9">
											<select class="js-example-basic-single" name="ubigeo" id="ubigeo">
												<option value="AL">Alabama</option>
													...
												<option value="WY">Wyoming</option>
											</select>
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-envelop mr-2"></i>
											Email
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-iphone mr-2"></i> 
											Célular
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="celular" id="celular" placeholder="Célular">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-phone2 mr-2"></i> 
											Teléfono
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="telefono" id="telefono" placeholder="Teléfono">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-office mr-2"></i> 
											N° de cuenta de Detracción
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="cuenta_detraccion" id="cuenta_detraccion"  placeholder="N° de cuenta de Detracción">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-plus mr-2"></i> 
											Detalle Adicional
										</label>
										<div class="col-lg-9">
											<textarea name="detalle_adicional" id="detalle_adicional" cols="30" rows="10" class="form-control"></textarea>
										</div>
									</div>
								</form>
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

