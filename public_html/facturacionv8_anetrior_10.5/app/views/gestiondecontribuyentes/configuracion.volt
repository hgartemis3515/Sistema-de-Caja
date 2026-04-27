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
	.mb-2{
		margin-bottom: 2em;
	}
	.label-form{
		padding: 5px 5px;
	}
</style>
<!-- Page header -->
<div class="page-header">
	<div class="page-header-content"  style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-grid52 position-left"></i> <span class="text-semibold">Gestión de Empresas Clientes</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/>
					<span>Boleta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/factura.svg" style="width: 25px;"/>
					<span>Factura</span></a>
				<a href="/facturacionv8/documentoelectronico/index/07/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/nota_credito.svg" style="width: 25px;"/>
						<span>Nota Crédito</span></a>
				<a href="/facturacionv8/documentoelectronico/index/08/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/nota_debito.svg" style="width: 25px;"/>
					<span>Nota Débito</span></a>
				<a href="/facturacionv8/reportes" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/svg/analytics.svg" style="width: 25px;"/>
					<span>Reporte de Ventas</span></a>
			</div>
		</div>
	</div>
</div>



<div class="content">
	<div class="row">
		<div class="col-md-12 col-md-12">
			<form name="frm_configcompany" id="frm_configcompany" action="#" method="post" enctype="multipart/form-data"> 
				<div class="panel" style="max-width: 1120px;margin: 0 auto;">
					<div class="panel-heading">
						<h6 class="panel-title">
							<i class="fa fa-sort mr-2" aria-hidden="true"></i>
							<span class="font-weight-semibold">Información del Emisor Electrónico</span>
						</h6>
						<div class="heading-elements mb-2">
							<div class="checkbox checkbox-switch">
								<label>
									<input type="checkbox" name="opcion_tipo_proceso" id="opcion_tipo_proceso" data-on-color="success" data-off-color="primary" data-on-text="Pruebas" data-off-text="Producción" class="switch" checked="checked">
								</label>
							</div>
						</div>
					</div>
					<div class="panel-body" id="content_panel_company">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-body border-panel">
									<div class="media">
										<div class="media-left">
											<img src="/facturacionv8/public/img/subetulogo.jpg" id="contribuyente_img_logo" class="contribuyente_img_logo img-lg" alt="">
										</div>

										<div class="media-body">
											<h6 class="media-heading" id="contribuyente_text_razonsocial">Razón Social Empresa</h6>
											<span class="text-muted" id="contribuyente_text_ruc">RUC: 7984654654</span>
										</div>

										<div class="media-right media-middle">
											<button type="button" id="btn_subirimagen" class="btn bg-indigo legitRipple"><i class="icon-box-remove mr-2"></i>Subir Logo</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<input type="hidden" id="ruta_logo" value="" name="ruta_logo" />
								<input type="hidden" name="id_contribuyente" id="id_contribuyente" value="<?php echo $id_contribuyente; ?>">
								<div class="col-md-6">
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
								<div class="col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-briefcase mr-2"></i> 
											Nombre Comercial
										</label>
										<input type="text" class="form-control form-control-sm" name="nombre_comercial" id="nombre_comercial" placeholder="Nombre Comercial">
									</div>	
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-phone2 mr-2"></i> 
											Teléfono
										</label>
										<input type="number" class="form-control form-control-sm" name="telefono" id="telefono" placeholder="Teléfono">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
											Ubigeo
										</label>
										<select class="select2" name="ubigeo" id="ubigeo">
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-office mr-2"></i> 
											Urbanización
										</label>
										<input type="text" class="form-control form-control-sm" name="urbanizacion" id="urbanizacion" placeholder="Urbanización">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-address-book mr-2"></i>
											Dirección fiscal
										</label>
										<input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Dirección fiscal">
									</div>
								</div>
								<div class="col-lg-6" style="display:none;">
									<div class="form-group">
										<label  class="label-form">
											<i class="icon-user mr-2"></i> 
											Selecciona el Patrocinador
										</label>
										<select class="select2 idpatrocinador" name="idpatrocinador" id="idpatrocinador">
											<option selected value="">Seleccione una opción</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<fieldset class="content-group">
									<legend class="text-bold">Selecciona el modo de envío por defecto: </legend>
	
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
								</fieldset>
							</div>

							<div class="col-md-12 datos_acceso_content"><div class="content-divider text-muted form-group mt-2"><span>Datos de Acceso</span></div></div>
							<div class="col-md-6 datos_acceso_content">
								<div class="form-group">
									<label class="label-form ">
										<i class="icon-user mr-2"></i> 
										Email
									</label>												
									<input type="text" class="form-control form-control-sm" name="email_login" id="email_login" placeholder="Escribe tu Email">
								</div>
							</div>
							<div class="col-md-6 datos_acceso_content">
								<div class="form-group">
									<label class="label-form  text-center">
										<i class=" icon-lock mr-2"></i> 
										Password
									</label>
									<div class="form-group input-group">
										<input type="password" name="password_login" id="password_login" class="form-control" placeholder="Contraseña">
										<span class="input-group-addon bg-indigo btn_show_pass_login" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									</div>
								</div>
							</div>
							<div class="col-md-12 tipo_proceso_content" style="display: none;"><div class="content-divider text-muted form-group mt-2"><span>Usuario y Password SOL - SUNAT</span></div></div>
							<div class="col-md-6 tipo_proceso_content" style="display: none;">
								<div class="form-group">
									<label class="label-form ">
										<i class="icon-user mr-2"></i> 
										Usuario Sol
									</label>												
									<input type="text" class="form-control form-control-sm" name="usuario_sol" id="usuario_sol" placeholder="Usuario Sol">
								</div>
							</div>
							<div class="col-md-6 tipo_proceso_content" style="display: none;">
								<div class="form-group">
									<label class="label-form  text-center">
										<i class=" icon-lock mr-2"></i> 
										Contraseña Sol
									</label>
									<div class="form-group input-group">
										<input type="password" name="password_sol" id="password_sol" class="form-control" placeholder="Password SOL">
										<span class="input-group-addon bg-indigo btn_show_pass_sol" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									</div>
								</div>
							</div> 
							<div class="col-md-12 tipo_proceso_content" style="display: none;"><div class="content-divider text-muted form-group mt-2"><span>Certificado Electrónico y Password</span></div></div>
							<div class="col-md-6 tipo_proceso_content" style="display: none;">
								<div class="form-group">
									<div class="col-md-12">
										<input type="file" class="file-styled" name="file_certificado" id="file_certificado" placeholder="Selecciona tu Certificado">
									</div>
								</div>
							</div>
							<div class="col-md-6 tipo_proceso_content" style="display: none;">
								<div class="form-group">
									<div class="form-group input-group">
										<input type="password" name="password_certificado" id="password_certificado" class="form-control" placeholder="Contraseña de tu Certificado">
										<span class="input-group-addon btn_show_pass_certificado" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
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
					</div>
				</div>
			</form>
		</div>

	</div>
	<div class="footer text-muted">
		© 2018. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank">Alex Castañeda</a>
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
				<p>Recuerda que la imágen debe ser cuadrada, y con un ancho máximo de 300x300px, en caso tengas una imágen más grande, puedes subirla sin problemas y nosotros te ayudaremos a recortar la imágen...</p>
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
	