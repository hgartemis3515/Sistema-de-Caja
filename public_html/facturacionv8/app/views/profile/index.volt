<style>
.mxy-20{
	margin: 1em 0em 1em 1em!important;
}
.mt-20{
	margin-top: 1em!important;
}
.mb-20{
	margin-bottom: 1em!important;
}

.color-gray{
	color: #90949c;
}
.display-block{
	display: block;
}

@media (min-width: 900px)
{
	#img_upload_preview {
		width: 130px;
		height: 130px;
	}
	.nav-tabs > li {
		display: inline-block;
		font-size: 14px;	
	}
	.nav-tabs.nav-tabs-bottom > li.active > a, .nav-tabs.nav-tabs-bottom > li.active > a:hover, .nav-tabs.nav-tabs-bottom > li.active > a:focus{
		font-weight: 700;
	}
	.info-password, .info-access{
		display: grid;
		grid-template-columns: 200px 1fr 90px;
	}
		.info-access2, .info-password2{
		display: grid;
		grid-template-columns: 200px 1fr;
	}

}

</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Perfil</span></h4>
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
		<div class="col-lg-3 col-md-3">
			<div class="thumbnail  border-top-indigo">
				<div class="thumb thumb-rounded">
					<img id="img_upload_preview" style="max-width: 300px;" src="<?php echo $usuario['url_image']; ?>" alt="">
				</div>
				<div class="caption">
					<h6 class="text-semibold no-margin text-center"><?php echo $usuario['nombre'].' '.$usuario['apellido']; ?></h6>
					<div class="text-center mt-20">
						<!-- <button type="button" class="btn bg-indigo"><i class="fa fa-cloud-upload position-left"></i>Subir</button> -->
						<button type="button" id="btn_subirimagen" class="btn bg-indigo legitRipple"><i class="icon-box-remove mr-2"></i>Subir Imágen</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="panel panel-flat  border-top-indigo">
				<div class="panel-body">
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-bottom">
							<li <?php if($tab == 'perfil') { echo 'class="active"'; } ?>><a href="#content_panel_usuario" data-toggle="tab" class="legitRipple"><i class="icon-user-tie mr-2"></i>Perfil</a></li>
							<li <?php if($tab == 'seguridad') { echo 'class="active"'; } ?>><a href="#seguridad" data-toggle="tab" class="legitRipple"><i class="icon-cog4 mr-2"></i>Seguridad y acceso</a></li>
							<?php if($usuario['id_rol'] == '1' || $usuario['id_rol'] == '2' || $usuario['id_rol'] == '3' || $usuario['id_rol'] == '5') { ?>
							<li style="display:none;" <?php if($tab == 'facturacion') { echo 'class="active"'; } ?>><a href="#facturacion" data-toggle="tab" class="legitRipple"><i class="icon-stack4 mr-2"></i>Facturación</a></li>
							<?php } ?>
						</ul>

						<div class="tab-content">
							<div class="tab-pane <?php if($tab == 'perfil') { echo 'active'; } ?>" id="content_panel_usuario">
								<form name="frm_profile" id="frm_profile" action="">
									<input type="hidden" id="src_img_upload" name="imagen_usuario" value="<?php echo $usuario['url_image']; ?>" />
									<input type="hidden" class="form-control" name="idusuario" id="idusuario" value="{{usuario['idusuario']}}">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label  class="label-form ">
													<i class="icon-barcode2 position-left"></i>
													Código
												</label>
												<div class="input-group">
													<input type="text" name="codigo" id="txt_codigo" class="form-control" value="{{usuario['codigo']}}">
													<span class="input-group-btn">
														<button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
															<i class="icon-rotate-ccw3 mr-2"></i>
														</button>
													</span>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="label-form">
													<i class="icon-user mr-2"></i> 
													Nombre
												</label>
												<input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="{{usuario['nombre']}}">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="label-form">
													<i class="icon-user mr-2"></i> 
													Apellido
												</label>
												<input type="text" class="form-control form-control-sm" name="apellido" id="apellido" value="{{usuario['apellido']}}">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="label-form">
													<i class="icon-iphone mr-2"></i> 
													Célular
												</label>
												<input type="text" class="form-control form-control-sm" name="celular" id="celular" value="{{usuario['celular']}}">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="label-form">
													<i class="icon-phone2 mr-2"></i> 
													Teléfono
												</label>
												<input type="text" class="form-control form-control-sm" name="telefono" id="telefono"  value="{{usuario['telefono']}}">
											</div>
										</div>
										<div class="float-right">
											<button class="btn bg-indigo legitRipple btn_saveuser mt-20" type="button">
												<i class="icon-floppy-disk mr-2"></i>
												Guardar
											</button>
										</div>
									</div>
								</form>
							</div>

							<div class="tab-pane <?php if($tab == 'seguridad') { echo 'active'; } ?>" id="seguridad">
								<div class="row">
									<div class="col-lg-12">
										<form name="frm_email" id="frm_email" action="">
											<input type="hidden" class="form-control" name="idusuario"  value="{{usuario['idusuario']}}">
											<div class="form-group info-access">
												<div class="details">
													<label class="label-form"><i class="icon-envelop color-indigo mr-2"></i>Cambiar Email </label>
												</div>
												<div class="info">
													<label class="label-form color-gray label_email"><?php echo $usuario['email']?></label>
													<input type="email" class="form-control form-control-sm input_email" name="email" id="email"  placeholder="Ingresa el nuevo email">
													<button class="btn btn-default legitRipple btn_cerraremail mxy-20 float-right" type="button">
														Cerrar
													</button>
													<button class="btn bg-indigo legitRipple btn_guardaremail mxy-20 float-right" type="button">
														<i class="icon-floppy-disk mr-2"></i>
														Guardar
													</button>
												</div>
												<div class="edit"><a href="javascript:void(0)" class=" edit_email btn btn-default"><i class="icon-pencil mr-2"></i>Editar</a></div>	
											</div>
										</form>
									</div>
									<div class="col-lg-12">
										<form name="frm_password" id="frm_password" action="">
											<div class="form-group info-password">
												<div class="details">
													<label class="label-form"><i class="icon-lock2  mr-2"></i>Cambiar contraseña </label>
												</div>
												<div class="info">
													<label for="pass" class="label-form label_password color-gray"><?php echo md5($usuario['password']); ?></label>
													<input type="hidden" class="form-control" name="idusuario"  value="{{usuario['idusuario']}}">
													<input type="password" name="password" id="password" class="form-control mb-20" placeholder="Ingresa la contraseña actual">
													<div class="input-group input-pass">
														<input type="password" name="new_password" id="new_password" class="form-control" placeholder="Ingresa la nueva contraseña">
														<div class="input-group-btn">
															<span id="show-passwd" action="hide" class="btn bg-indigo legitRipple"><i class="icon-eye-blocked"></i></span>
														</div>
													</div>
													<div id="meter1"></div>
													<button class="btn btn-default legitRipple btn_cerrarpass mxy-20 float-right" type="button">
														Cerrar
													</button>
													<button class="btn bg-indigo legitRipple btn_guardarpass mxy-20 float-right" type="button">
														<i class="icon-floppy-disk mr-2"></i>
														Guardar
													</button>
												</div>
												<div class="edit"><a href="javascript:void(0)" class="edit_password btn-sm btn btn-default"><i class="icon-pencil mr-2"></i>Editar</a></div>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<?php if($usuario['id_rol'] == '1' || $usuario['id_rol'] == '2' || $usuario['id_rol'] == '3' || $usuario['id_rol'] == '5') { ?>
								<div class="tab-pane <?php if($tab == 'facturacion') { echo 'active'; } ?>" id="facturacion">
									<div class="row content-table" <?php if($usuario['id_contribuyente'] == 654) { echo "style='display:none;'"; } ?> >
										<div class="col-md-12">
											<div id="alert_plan"></div>
											<div class="table-responsive">
												<table class="table datatable-basic" id="tbl_lista_usuarios" style="width: 100%;">
													<thead>
														<tr>
															<th>#ID </th>
															<th style="min-width: 250px;">Plan Suscripción</th>
															<th style="min-width: 250px;">Período de Validez</th>
															<th>Límite docs</th>
															<th>Total Pagado</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	