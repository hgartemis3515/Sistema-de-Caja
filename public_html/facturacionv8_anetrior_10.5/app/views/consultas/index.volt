<style>
.btn-consulta{
	-webkit-box-shadow: 0px 5px 10px 2px rgb(40, 58, 163,0.19) !important;
	box-shadow: 0px 5px 10px 2px rgb(40, 58, 163,0.19) !important;
	border-radius: 60px;
	padding: 1.2rem 3rem;
}
.captcha{
	display: grid;
	justify-content: center;
}

.page-container {
	background-image: url(/facturacionv8/img/38.jpg);
	background-size: cover;
	height: 100%;
	max-width: 100%;
	position:relative;
	color: white;
	z-index:1;
}
@media (min-width: 800px){
	.login-container .page-container .login-form {
	width: 500px;
}
}
</style>
<div class="login-container">
	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content" id="contenido_form_busqueda">
					<div class="text-center">
						<h1 class="title-header">Consulta de Documentos Electrónicos</h1>
					</div>
					<div class="panel panel-body login-form">
						<form class="frm_consulta" id="frm_consulta" action="" method="post">
							<input type="hidden" value="<?php echo $id_contribuyente; ?>" name="id_contribuyente" />
							<div class="text-center">
								<img src="<?php echo $data_empresa['logo_img_461']; ?> width="320px" alt="">
							</div>
							<div class="contect_document">
								<div class="row mt-20">
									<div class="col-md-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-profile position-left"></i>
												Tipo de Documento
											</label>
											<select class="select2 form-control" name="tipo_documento" id="tipo_documento">
												<option selected value="">Seleccione una opción</option>
												<option  value="01">Factura</option>
												<option  value="03">Boleta</option>
												<option  value="07">Nota Débito</option>
												<option  value="08">Nota Crédito</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-barcode2 position-left"></i>
												Serie - Número Documento
											</label>
											<input type="text" name="serie_numero_documento" id="serie_numero_documento" class="form-control input-login"  placeholder="Ej: FXXX-000000">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-calendar2 position-left"></i>
												Fecha de emisión
											</label>
											<input placeholder="Ej: 21-03-2015" class="form-control" type="date"  id="fecha_documento" name="fecha_documento"> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-cash2 position-left"></i>
												Monto total
											</label>
											<input type="number" name="monto_total" id="monto_total" class="form-control input-login" placeholder="Ej: 5678999.01">
										</div>
									</div>
								</div>
								<div class="form-group text-center mt-20">
									<button type="button" id="btn_consulta_documento" class="btn bg-indigo btn-consulta legitRipple">Ver documento <i class="icon-circle-right2 position-right"></i></button>
								</div>
							</div>
							<div class="result mt-20">
								<div class="alert alert-success no-border">
									<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
									<p class="text-semibold" id="msg_respuesta">La presente factura fue enviada  el día 20/12/2018: </p> 
								</div>
							</div>
						</form>
					</div>
					<div class="footer text-muted text-center">
						© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>