<style>
@media(min-width: 200px){
	.thumbnail {
    width: 30%;
    display: block;
    margin: auto;
	}
}
@media(min-width: 900px){
	.thumbnail {
    width: 100%;
    display: block;
    margin: auto;
	}
}
.mt-2{
	margin-top: 2em;
}
</style>
<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Configuración de la Empresa</span></h4>
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
							<span class="font-weight-semibold">Información del Emisor Electrónico</span>
							<small class="d-block text-muted">Aquí puedes agregar/editar los datos de tu empresa, llenando todos los datos vacíos. <span class="font-weight-bold">Atención, todos son requeridos.</span> </small>
						</h5>
					</div>
				</div>
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex cont-pt2">
						<div class="row">
							<div class="col-lg-4 col-xl-4 col-md-4 col-sm-5 col-12">
								<div class="thumbnail">
									<div class="thumb">
										<img src="/facturacionv8/public/img/subetulogo.jpg" class="img-fluid" alt="">
										<div class="caption-overflow">
											<span>
												<a href="/facturacionv8/public/img/subetulogo.jpg" target="_blank" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded legitRipple"><i class="icon-plus3"></i></a>
												<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5 legitRipple"><i class="icon-link2"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="text-center mt-2">
									<button type="button" class="btn btn-info legitRipple"><i class="icon-box-remove mr-2"></i>Subir</button>
								</div>
							</div>
							<div class="col-lg-8 col-md-8 col-sm-7 col-xl-8 col-12">
								<form action="">
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-briefcase mr-2"></i> 
											R.U.C
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="ruc" id="ruc" placeholder="R.U.C">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-briefcase mr-2"></i> 
											Razón social
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="razonsocial" id="razonsocial" placeholder="Razon Social">
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
											<i class="icon-envelop mr-2"></i>
											Email
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
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
											<i class="icon-office mr-2"></i> 
											Urbanización
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="urbanizacion" id="urbanizacion" placeholder="Urbanización">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-address-book mr-2"></i>
											Dirección fiscal
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Dirección fiscal">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12">
			<div class="page-header page-header-light" style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
				<div class="page-header-content border-bottom-2 border-bottom-danger">
					<div class="page-title">
						<h5>
							<i class="fa fa-sort mr-2" aria-hidden="true"></i>
							<span class="font-weight-semibold">Datos de acceso</span>
							<small class="d-block text-muted">Completa los campos vacíos para asignar tus datos de ingreso al sistema. <span class="font-weight-bold">Atención, todos son requeridos.</span> </small>
						</h5>
					</div>
				</div>
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex cont-pt2">
						<div class="row">
							<div class="col-lg-12">
								<form action="">
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class="icon-user mr-2"></i> 
											Usuario Sol
										</label>
										<div class="col-lg-9">
											<input type="text" class="form-control form-control-sm" name="user_sol" id="user_sol" placeholder="Usuario Sol">
										</div>
									</div>
									<div class="row form-group">
										<label for="ruc" class="label-form col-lg-3">
											<i class=" icon-lock mr-2"></i> 
											Contraseña Sol
										</label>
										<div class="col-lg-9">
											<input type="password" class="form-control form-control-sm" name="pass_sol" id="pass_sol" placeholder="Contraseña Sol">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12">
			<div class="page-header page-header-light" style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
				<div class="page-header-content border-bottom-2 border-bottom-danger">
					<div class="page-title">
						<h5>
							<i class="fa fa-sort mr-2" aria-hidden="true"></i>
							<span class="font-weight-semibold">Certificado Digital</span>
							<small class="d-block text-muted">Aquí puedes editar los datos de tu empresa, así como blablabla</small>
						</h5>
					</div>
				</div>
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex cont-pt2">
						<div class="row">
							<div class="col-lg-12">
								<div class="button-large">
										<button type="button" class="btn btn-info btn-xlg legitRipple" id="btn_certificado"><i class="icon-file-text position-left"></i> Agregar Certificado</button>
								</div>
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

