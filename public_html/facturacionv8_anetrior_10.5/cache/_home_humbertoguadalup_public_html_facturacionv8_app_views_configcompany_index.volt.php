<style>
.justifice-content{
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	-ms-flex-align: center!important;
	align-items: center!important;
}
.media-body, .media-left, .media-right {
	vertical-align: initial;
}
@media only screen and (max-width: 760px) and (min-width: 300px){
	.img_logo350x167{
		max-width: 100px;
	}
}
@media only screen and (max-width: 1170px) and (min-width: 900px){
	.img_logo350x167{
		max-width: 300px;
	}
}
@media(min-width: 200px){
	.thumbnail {
		width: 30%;
		display: block;
		margin: auto;
	}
	.media-right {
	display: initial;}
}
@media(min-width: 900px){
	.thumbnail { 
		width: 100%;
		display: block;
		margin: auto;
	}
	.img_logo350x167{
		width: 350px; 
		height: 167px;
	}
}

.mt-2{
	margin-top: 2em;
}
.mb-2{
	margin-bottom: 2em;
}
.label-form{
	padding: 5px 5px;
}
.section-title-divisor{
	text-transform: uppercase;
	position: relative;
	border-top: 1px solid #e5e5e5;
	padding-top: 10px;
}
.bootstrap-switch {
    margin-top: 1em;
}

.dropdown-menu>.active> a > i, .dropdown-menu>.active>a:focus > i, .dropdown-menu>.active>a:hover > i{
    color: #fff;
	background: transparent;
	-webkit-text-fill-color: #fff;
}

</style>
<!-- Page header -->

<input type="hidden" id="input_tipo_certificado" value="<?php echo $contribuyente->tipo_certificado; ?>" />
<input type="hidden" id="input_tipo_envio_sunat" value="<?php echo $contribuyente->tipo_envio_sunat; ?>" />
<input type="hidden" id="input_tipo_empresa_sunat" value="<?php echo $contribuyente->tipo_empresa_sunat; ?>" />


<div class="page-header">
	<?php echo $html_suscripcion; ?>
	<div class="page-header-content"  style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-grid52 position-left"></i> <span class="text-semibold">Configurar mi Empresa</span></h4>
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
		<div class="col-md-12">
			<div class="tabbable">
				<div class="panel" style="max-width: 1120px;margin: 0 auto;border-radius: 0px;">
					<div class="panel-body" >
						<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
							<li class="active">
								<a href="#datos_empresa" data-toggle="tab" class="legitRipple" aria-expanded="true">
									<i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Datos Iniciales</span> 
								</a>
							</li>
							<li class="">
								<a href="#graficos_personalizados" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-cloud-upload fa-lg position-left"></i> Gráficos</a>
							</li>
							<li class="">
								<a href="#integracion_apirest" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="icon-circle-code position-left"></i> Integración APIREST</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		
			<div class="tab-content">
				<!-- Primer tab -->
				<div class="tab-pane active" id="datos_empresa">
					<div class="panel" style="max-width: 1120px;margin: 0 auto;border-top-left-radius: 0px;border-top-right-radius: 0px;">
						<div class="panel-body">
							<form name="frm_configcompany" id="frm_configcompany" action="#" method="post" enctype="multipart/form-data"  autocomplete="off"> 
								<input type="hidden" id="tipo_empresa_sunat" value="<?php echo $contribuyente->tipo_empresa_sunat; ?>" />
								<div class="panel-heading mb-2">
									<h6 class="panel-title">
										<i class="fa fa-sort mr-2" aria-hidden="true"></i>
										<span class="font-weight-semibold">Ingresa y/o edita la información de tu empresa</span>
									</h6>
									<div class="heading-elements mb-2">
										<div class="checkbox checkbox-switch">
											<label>
												<input type="checkbox" name="opcion_tipo_proceso" id="opcion_tipo_proceso" data-on-color="success" data-off-color="primary" data-on-text="Pruebas" data-off-text="Producción" class="switch" checked="checked">
											</label>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-body border-panel">
											<div class="row">
												<div class="col-lg-10">
													<div class="media">
														<div class="media-left">
															<img src=" /facturacionv8/public/img/subetulogo.jpg" class="contribuyente_img_logo img-lg" alt="">
														</div>
				
														<div class="media-body">
															<h6 class="media-heading" id="contribuyente_text_razonsocial">Razón Social Empresa</h6>
															<span class="text-muted" id="contribuyente_text_ruc">RUC: 7984654654</span>
														</div>
													</div>
												</div>
												<div class="col-lg-2">
													<div class="media text-center">
														<div class="media-right media-middle margin-top-20">
															<button type="button" id="btn_subirimagen" class="btn_subirimagen btn bg-indigo legitRipple"><i class="icon-box-remove mr-2"></i>Subir Logo</button>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<input type="hidden" id="ruta_logo" value="" name="ruta_logo" />
										<input type="hidden" name="id_contribuyente" id="id_contribuyente" value="<?php echo $id_contribuyente; ?>">
										<div class="form-group">
											<label  class="label-form ">
												<i class="icon-user mr-2"></i>
												R.U.C.
											</label>
											<div class="input-group">
												<input type="text" title="Número de RUC" name="ruc" id="ruc" placeholder="Número de RUC Aquí!" class="form-control ruc" required>
												<span class="input-group-btn">
													<button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
														<i class="icon-search4" id="icon_search_document"></i>
														<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
														</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-briefcase mr-2"></i> 
												Razón social
											</label>
											<input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" placeholder="Razon Social">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-briefcase mr-2"></i> 
												Nombre Comercial
											</label>
											<input type="text" class="form-control form-control-sm" name="nombre_comercial" id="nombre_comercial" placeholder="Nombre Comercial">
										</div>	
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-phone2 mr-2"></i> 
												Teléfono
											</label>
											<input type="number" class="form-control form-control-sm" name="telefono" id="telefono" placeholder="Teléfono">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form ">
												<i class="icon-user mr-2"></i> 
												Email Empresa
											</label>												
											<input type="email" class="form-control form-control-sm" name="email_empresa" id="email_empresa" placeholder="Escribe el Email de la Empresa">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
												Ubigeo
											</label>
											<select class="select2" name="ubigeo" id="ubigeo">
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-office mr-2"></i> 
												Urbanización
											</label>
											<input type="text" class="form-control form-control-sm" name="urbanizacion" id="urbanizacion" placeholder="Urbanización">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-address-book mr-2"></i>
												Dirección fiscal
											</label>
											<input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Dirección fiscal">
										</div>
									</div>
							
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="flaticon-money mr-2"></i>
												Régimen
											</label>
											<select class="select_opcion" name="sunat_idregimen" id="sunat_idregimen">
												<?php foreach($sunat_regimen as $regimen) {
													$selected = '';
													if($regimen->idregimen == $contribuyente->sunat_idregimen) {
														$selected = 'selected';
													}
													echo "<option value='".$regimen->idregimen."' ".$selected.">".$regimen->nombre."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="flaticon-stock mr-2"></i>
												¿Restricción de Stock?
											</label>
											<select class="select_opcion" name="restriccion_stock" id="restriccion_stock">
												<option value="si" <?php if($contribuyente->restriccion_stock == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if($contribuyente->restriccion_stock == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="flaticon-warehouse mr-2"></i>
												¿Multi-Almacén?
											</label>
											<select class="select_opcion" name="multi_almacen" id="multi_almacen">
												<option value="si" <?php if($contribuyente->multi_almacen == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if($contribuyente->multi_almacen == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-coin-dollar mr-2"></i>
												¿Restricc. Prec. Mínimo?
											</label>
											<select class="select_opcion" name="precio_venta_minimo" id="precio_venta_minimo">
												<option value="si" <?php if($contribuyente->precio_venta_minimo == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if($contribuyente->precio_venta_minimo == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-calendar2 mr-2"></i>
												¿Fecha Venc. en Prod.?
											</label>
											<select class="select_opcion" name="producto_fecha_vencimiento" id="producto_fecha_vencimiento">
												<option value="si" <?php if($contribuyente->ver_fecha_vencimiento == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if($contribuyente->ver_fecha_vencimiento == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-price-tags2 mr-2"></i>
												¿Marca en Prod.?
											</label>
											<select class="select_opcion" name="producto_marca" id="producto_marca">
												<option value="si" <?php if($contribuyente->ver_marca == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if($contribuyente->ver_marca == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="mr-2 flaticon-calculator"></i>
												N° Decimales
											</label>
											<select class="select_opcion" name="num_decimales" id="num_decimales">
												<option value="2" <?php if($contribuyente->num_decimales == 2) { echo 'selected'; } ?> >2 Decimales</option>
												<option value="3" <?php if($contribuyente->num_decimales == 3) { echo 'selected'; } ?> >3 Decimales</option>
												<option value="4" <?php if($contribuyente->num_decimales == 4) { echo 'selected'; } ?> >4 Decimales</option>
												<option value="5" <?php if($contribuyente->num_decimales == 5) { echo 'selected'; } ?> >5 Decimales</option>
												<option value="6" <?php if($contribuyente->num_decimales == 6) { echo 'selected'; } ?> >6 Decimales</option>
												<option value="7" <?php if($contribuyente->num_decimales == 7) { echo 'selected'; } ?> >7 Decimales</option>
												<option value="8" <?php if($contribuyente->num_decimales == 8) { echo 'selected'; } ?> >8 Decimales</option>
												<option value="9" <?php if($contribuyente->num_decimales == 9) { echo 'selected'; } ?> >9 Decimales</option>
												<option value="10" <?php if($contribuyente->num_decimales == 10) { echo 'selected'; } ?> >10 Decimales</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="mr-2 icon-search4"></i>
												Tipo Búsqueda DNI
											</label>
											<select class="select_opcion" name="tipo_busqueda_doc" id="tipo_busqueda_doc">
												<option value="completa" <?php if($contribuyente->tipo_busqueda_doc == 'completa') { echo 'selected'; } ?> >Completa</option>
												<option value="basica" <?php if($contribuyente->tipo_busqueda_doc == 'basica') { echo 'selected'; } ?> >Basica</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<img class="mr-2" src="/facturacionv8/img/svg/calculator.svg" style="width: 16px;">
												¿Mostrar Items con IGV?
											</label>
											<select class="select_opcion" name="cotizacion_con_igv" id="cotizacion_con_igv">
												<option value="si" <?php if($contribuyente->cotizacion_con_igv == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if(empty($contribuyente->cotizacion_con_igv) || $contribuyente->cotizacion_con_igv == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<img class="mr-2" src="/facturacionv8/img/svg/last_price.svg" style="width: 16px;">
												¿Mostrar Último Precio Vendido?
											</label>
											<select class="select_opcion" name="mostrar_uprecio_clieprod" id="mostrar_uprecio_clieprod">
												<option value="si" <?php if($contribuyente->mostrar_uprecio_clieprod == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if(empty($contribuyente->mostrar_uprecio_clieprod) || $contribuyente->mostrar_uprecio_clieprod == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<img class="mr-2" src="/facturacionv8/img/svg/last_price.svg" style="width: 16px;">
												¿Redondear Detracción en PDF?
											</label>
											<select class="select_opcion" name="redondear_detraccion_pdf" id="redondear_detraccion_pdf">
												<option value="si" <?php if(empty($redondear_detraccion_pdf) || $redondear_detraccion_pdf == 'si') { echo 'selected'; } ?> >SI</option>
												<option value="no" <?php if($redondear_detraccion_pdf == 'no') { echo 'selected'; } ?> >NO</option>
											</select>
										</div>
									</div>


									<div class="col-md-12">
										<div class="section-title-divisor"><p class="text-bold">Redes sociales: </p></div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-facebook mr-2"></i>
												Facebook
											</label>
											<input type="text" class="form-control form-control-sm" name="url_facebook" id="url_facebook" placeholder="Facebook">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-twitter mr-2"></i>
												Twitter
											</label>
											<input type="text" class="form-control form-control-sm" name="url_twitter" id="url_twitter" placeholder="Twitter">
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<img src="/facturacionv8/img/icons/tik-tok.png" width="13px" alt="" class="mr-2">
												Tik tock
											</label>
											<input type="text" class="form-control form-control-sm" name="url_tiktok" id="url_tiktok" placeholder="TikTok">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-instagram mr-2"></i>
												Instagram
											</label>
											<input type="text" class="form-control form-control-sm" name="url_instagram" id="url_instagram" placeholder="Instragram">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<img src="/facturacionv8/img/icons/youtube.png" width="13px" alt="" class="mr-2">
												Youtube
											</label>
											<input type="text" class="form-control form-control-sm" name="url_youtube" id="url_youtube" placeholder="Youtube">
										</div>
									</div>

									<div class="col-md-12">
										<div class="section-title-divisor">
											<p class="text-bold">Selecciona el modo de envío por defecto: </p>
										</div>
										<div class="col-md-4">
											<div class="radio">
												<label>
													<input type="radio" value="solo_firma" id="mod_envio_sunat_solo_firma" name="modalidad_envio_sunat" class="control-primary" <?php if($modalidad_envio_sunat=='solo_firma'){echo 'checked="checked"';} ?>>
													Solo Firmar e Imprimir
												</label>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="radio">
												<label>
													<input type="radio" value="inmediato" id="mod_envio_sunat_inmediato" name="modalidad_envio_sunat" class="control-success" <?php if($modalidad_envio_sunat=='inmediato'){echo 'checked="checked"';} ?>>
													Enviar a SUNAT ahora mismo!
												</label>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="radio">
												<label>
													<input type="radio" value="no_enviar" id="mod_envio_sunat_no_enviar" name="modalidad_envio_sunat" class="control-info" <?php if($modalidad_envio_sunat=='no_enviar'){echo 'checked="checked"';} ?>>
													Solo Guardar la Venta
												</label>
											</div>
										</div>
										
									</div>


									<div class="col-md-12 datos_acceso_content">
										<div class="content-divider text-muted form-group mt-2"><span>Datos de Acceso</span></div>
									</div>
									<div class="col-md-6 datos_acceso_content">
										<div class="form-group">
											<label class="label-form ">
												<i class="icon-user mr-2"></i> 
												Email
											</label>												
											<input type="text" autocomplete="off" class="form-control form-control-sm" name="email_login" id="email_login" placeholder="Escribe tu Email">
										</div>
									</div>
									<div class="col-md-6 datos_acceso_content">
										<div class="form-group">
											<label class="label-form  text-center">
												<i class=" icon-lock mr-2"></i> 
												Password
											</label>
											<div class="form-group input-group">
												<input type="password" autocomplete="off" name="password_login" id="password_login" class="form-control" placeholder="Contraseña">
												<span class="input-group-addon bg-indigo btn_show_pass_login" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
											</div>
										</div>
									</div>
									
									<div class="col-md-12 text-right">
										<button class="btn bg-indigo legitRipple btn_savecompany" type="button">
											<i class="icon-floppy-disk mr-2"></i>
											Guardar Información
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class="panel panel-body"  style="max-width: 1120px;margin: 2em auto;">
							<div class="col-md-12">
								<div class="content-divider text-muted form-group mt-2"><span>Forma de Pago</span>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="alert alert-styled-left alert-styled-custom alert-arrow-left alpha-primary alert-bordered">
									<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
									<span>Según decreto de urgencia 013-2020 publicada el 23 de Enero del 2020, y resolución de superintendencia N° 193-2020/SUNAT, publicada el 07 de noviembre del 2020: Indica que debemos indicar cuál es la forma de pago de las FACTURAS o NOTAS DE CRÉDITO, que pueden ser al contado o al crédito.</span>
								</div>

								

								<div class="alert alert-primary border-0 alert-dismissible">
									<div class="col-md-12">
										<div class="checkbox checkbox-switch text-center">
											<label class="label-form">
												<strong>¿Deseas Informar a SUNAT si una FACTURA es al crédito?</strong>
												<br class="mb-40">
												<input type="checkbox" value="si" name="informar_condpago_sunat" id="informar_condpago_sunat" data-on-color="primary" data-off-color="success" data-on-text="SI" data-off-text="NO" class="switch" <?php if($contribuyente->informar_condpago_sunat == 'si'){echo 'checked';} ?>>
											</label>
										</div>
									</div>
									
									Si marcas la opción "NO", entonces todas las facturas se enviarán como pagadas al contado, y la gestión de créditos será interna solo en el sistema. Si tu utilizas el factoring (vendes tus facturas al crédito) entonces deberías marcas SI, caso contrario puedes enviar todas tus facturas como forma de pago al contado.
								</div>
							</div>

							<div class="col-md-12" id="content_modificar_cuotas_sunat">
							
								<div class="col-md-12">
									<div class="checkbox checkbox-switch text-center">
										<label class="label-form">
											<strong>¿Deseas que el Sistema Permita Agregar, Eliminar y/o Modificar las Cuotas de los Comprobantes al Crédito?</strong>
											<br class="mb-40">
											<input type="checkbox" value="si" name="permitir_cambio_cuotas" id="permitir_cambio_cuotas" data-on-color="primary" data-off-color="success" data-on-text="SI" data-off-text="NO" class="switch" <?php if($permitir_cambio_cuotas == 'si'){echo 'checked';} ?>>
										</label>
									</div>
								</div>
								
								<div class="col-md-12 text-right"><button class="btn bg-indigo legitRipple btn_savecompany" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Información</button></div>
							</div>
					</div>
					

					<div class="panel panel-body only_produccion only_certificado_propio"  style="max-width: 1120px;margin: 2em auto; display: none;" id="data_produccion_empresa_normal">
						<div class="row justifice-content">

							<div class="col-md-12">
								<div class="content-divider text-muted form-group mt-2"><span>Usuario Secundario y Password SOL - SUNAT</span>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form ">
										<i class="icon-user mr-2"></i> 
										Usuario Sol Secundario
									</label>												
									<input type="text" autocomplete="off" class="form-control form-control-sm" name="usuario_sol" id="usuario_sol" placeholder="Usuario Sol">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form  text-center">
										<i class=" icon-lock mr-2"></i> 
										Contraseña Sol
									</label>
									<div class="form-group input-group">
										<input autocomplete="off" type="password" name="password_sol" id="password_sol" class="form-control" placeholder="Password SOL">
										<span class="input-group-addon bg-indigo btn_show_pass_sol" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									</div>
								</div>
							</div> 


							<div class="col-md-12"><div class="content-divider text-muted form-group mt-2"><span>Certificado Electrónico y Password</span></div></div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="col-md-12">
										<input type="file" class="file-styled" name="file_certificado" id="file_certificado" placeholder="Selecciona tu Certificado">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="form-group input-group">
										<input autocomplete="off" type="password" name="password_certificado" id="password_certificado" class="form-control" placeholder="Contraseña de tu Certificado">
										<span class="input-group-addon btn_show_pass_certificado" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									</div>
								</div>
							</div> 
							
							<div class="col-md-12 text-right"><button class="btn bg-indigo legitRipple btn_savecompany" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Información</button></div>

						</div>
					</div>

					<div class="panel panel-body only_produccion only_certificado_propio"  style="max-width: 1120px;margin: 2em auto; display: none;" id="data_produccion_empresa_normal">
						<div class="row justifice-content">
						
							<div class="col-md-12">
								<div class="content-divider text-muted form-group mt-2"><span>Datos para Envío de Guía de Remisión (Conexión ApiRest)</span>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form ">
										<i class="icon-user mr-2"></i> 
										Usuario Sol
									</label>												
									<input type="text" autocomplete="off" class="form-control form-control-sm" name="sunat_u_sol_principal" id="sunat_u_sol_principal" placeholder="Usuario Sol Principal">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form  text-center">
										<i class=" icon-lock mr-2"></i> 
										Contraseña Sol
									</label>
									<div class="form-group input-group">
										<input autocomplete="off" type="password" name="sunat_p_sol_principal" id="sunat_p_sol_principal" class="form-control" placeholder="Password SOL Principal">
										<span class="input-group-addon bg-indigo btn_show_pass_sol" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									</div>
								</div>
							</div> 

							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form ">
										<i class="icon-user mr-2"></i> 
										Client_ID
									</label>												
									<input type="text" autocomplete="off" class="form-control form-control-sm" name="sunat_client_id" id="sunat_client_id" placeholder="Client ID">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form  text-center">
										<i class=" icon-lock mr-2"></i> 
										Client_SECRET
									</label>
									<div class="form-group input-group">
										<input autocomplete="off" type="password" name="sunat_client_secret" id="sunat_client_secret" class="form-control" placeholder="Client Secret">
										<span class="input-group-addon bg-indigo btn_show_pass_sol" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									</div>
								</div>
							</div> 
							


							<div class="col-md-12 text-right"><button class="btn bg-indigo legitRipple btn_savecompany" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Información</button></div>

						</div>
					</div>

					<div class="panel panel-body"  style="max-width: 1120px;margin: 2em auto;">
						<div class="row justifice-content">
						
							<div class="col-md-12">
								<div class="content-divider text-muted form-group mt-2"><span>Datos para Buscar Facturas de Compra (Conexión ApiRest)</span>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form ">
										<i class="icon-user mr-2"></i> 
										Usuario Sol Principal
									</label>												
									<input type="text" autocomplete="off" class="form-control form-control-sm" name="user_sol_busq_cpe" id="user_sol_busq_cpe" placeholder="Usuario Sol Principal">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form  text-center">
										<i class=" icon-lock mr-2"></i> 
										Contraseña Sol
									</label>
									<div class="form-group input-group">
										<input autocomplete="off" type="password" name="pass_sol_busq_cpe" id="pass_sol_busq_cpe" class="form-control" placeholder="Password SOL Principal">
										<span class="input-group-addon bg-indigo btn_show_pass_sol_busq_cpe" id="show-passwd" action="hide"><i class="icon-eye-blocked" id="icon_eye_blocked_pass_sol_busq_cpe"></i></span>
									</div>
								</div>
							</div> 
							
							<div class="col-md-12 text-right"><button class="btn bg-indigo legitRipple btn_savecompany" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Información</button></div>

						</div>
					</div>
					
					<div class="panel panel-body only_prico"  style="max-width: 1120px;margin: 2em auto; ">
						<div class="row justifice-content">
							<div class="col-md-12">
								<label class="label-form"><i class="icon-user mr-2"></i>Datos OSE</label>
								<select class="select_opcion" name="nombre_ose" id="nombre_ose">
									<option value="">Otro</option>
									<option value="bizlinks" <?php if($contribuyente->nombre_ose == 'bizlinks'){echo 'selected';} ?>>BIZLINKS</option>
									<option value="nubefact" <?php if($contribuyente->nombre_ose == 'nubefact'){echo 'selected';} ?>>NUBEFACT</option>
								</select>
								
							</div>
							<div class="col-md-12">
								<p class="text-bold text-uppercase font-weight-700" style="margin: 1em 0">AMBIENTE DE PRUEBA - DATOS DE ACCESO: </p>
							</div>
							<div class="col-md-3" >
								<div class="form-group">
									<label class="label-form">
										<i class="icon-user mr-2"></i>
										Usuario (sin num. RUC)
									</label>
									<input type="text" value="<?php echo $contribuyente->u_prueba_ose; ?>" class="form-control form-control-sm" name="u_prueba_ose" id="u_prueba_ose" placeholder="Usuario Prueba OSE">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-lock2  mr-2"></i>
										Contraseña
									</label>
									<input type="text" value="<?php echo $contribuyente->c_prueba_ose; ?>" autocomplete="off" class="form-control form-control-sm" name="c_prueba_ose" id="c_prueba_ose" placeholder="Contraseña">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-sphere3 mr-2"></i>
										URL Prueba
									</label>
									<input type="text" value="<?php echo $contribuyente->url_prueba_ose; ?>" autocomplete="off" class="form-control form-control-sm" name="url_prueba_ose" id="url_prueba_ose" placeholder="URL Prueba">
								</div>
							</div>

							<!-- Produccion -->
							<div class="col-md-12">
								<p class="text-bold text-uppercase font-weight-700" style="margin: 1em 0">AMBIENTE DE PRODUCCIÓN - DATOS DE ACCESO: </p>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-user mr-2"></i>
										Usuario (sin num. RUC)
									</label>
									<input type="text" value="<?php echo $contribuyente->u_produccion_ose; ?>" autocomplete="off" class="form-control form-control-sm" name="u_produccion_ose" id="u_produccion_ose" placeholder="Usuario Producción">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-lock2  mr-2"></i>
										Contraseña
									</label>
									<input type="text" value="<?php echo $contribuyente->c_produccion_ose; ?>" autocomplete="off" class="form-control form-control-sm" name="c_produccion_ose" id="c_produccion_ose" placeholder="Contraseña Producción">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-sphere3 mr-2"></i>
										URL Producción
									</label>
									<input type="text" value="<?php echo $contribuyente->url_produccion_ose; ?>" autocomplete="off" class="form-control form-control-sm" name="url_produccion_ose" id="url_produccion_ose" placeholder="URL Producción">
								</div>
							</div>
							<div class="col-md-12 text-right"><button class="btn bg-indigo legitRipple btn_savecompany" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Información</button></div>
						</div>
					</div>
					
				</div>
				<!-- Segundo  tab -->
				<div class="tab-pane" id="graficos_personalizados">
					<?= $this->partial('configcompany/tab_logos_empresa') ?>
				</div>

				<!-- Tercer  tab -->
				<div class="tab-pane" id="integracion_apirest">
					<?= $this->partial('configcompany/tab_integracion_api_rest') ?>
				</div>
			</div>
		</div>
	</div>

	
	<div class="footer text-muted">
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>


<!-- Ventana para Agregar imágen -->
<div id="vm_cargar_imagen" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Importante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p>Recuerda que la imagen debe tener el siguiente tamaño: <strong class="tamanio_texto_img"></strong>, en caso tengas una imágen más grande, puedes subirla sin problemas y nosotros te ayudaremos a recortar la imágen...</p>
				<hr>
				<div class="row">
					<div class="form-group col-lg-9">
						<input id="fileimage" type="file" class="file-input" accept=".jpg,.gif,.png">
					</div>
					<div class="form-group col-lg-3">
						<div class="previewrecorteimg" style="width: 100%;"></div>
					</div>
						<img src="" id="imagenresultado" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary legitRipple" id="btn_guardarimagen"><i class="icon-spinner6 spinner position-left btn_guardarimagen_loading" style="display: none;"></i><i class="icon-floppy-disk position-left btn_guardarimagen_icono"></i> Guardar Imágen</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para Agregar imágen -->
	
<script>
	var token_contribuyente = '<?php echo $token_contribuyente; ?>';
</script>