<style>
.label-form{
	padding: 5px 5px;
}
.dataTables_filter > label:after {
    content: "";
}
@media (min-width: 800px){
	.dataTables_filter > label:after {
    content: "\e98e";
	}
}
</style>
<!-- Page header -->
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de Clientes</span></h4>
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
				<div class="panel-body" id="content_panel_usuario">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="text-uppercase">Agregar Clientes</span> 
						</legend>
					</fieldset>
					<div class="row">
						<div class="col-lg-12">
							<form action="" class="frm_addclient" id="frm_addclient">
								<input type="hidden" id="idcliente" name="idcliente" value="" />
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label  class="label-form ">
												<i class="icon-user mr-2"></i>
												Código
											</label>
											<div class="input-group">
												<input type="text" name="codigo" id="txt_codigo" class="form-control" placeholder="Código">
												<span class="input-group-btn">
													<button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
														<i class="icon-rotate-ccw3 mr-2"></i>
														Generar
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-user mr-2"></i> 
												Tipo de Documento de Identidad
											</label>
											<select class="js-example-basic-single" name="type_document" id="type_document">
												<option selected value="">Seleccione una opción</option>
												<?php                                    
													foreach ($tipo_identidad as $value) {
														echo "<option value='".$value->id_tipodocidentidad."'>".$value->nombre."</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-user mr-2"></i>
												<span class="type_id">Documento de Identidad</span> 
												<span style="display: none;" class="label label-danger lbl_estado_empresa lbl_estado_inactivo">Inactivo</span>
												<span style="display: none;" class="label label-success lbl_estado_empresa lbl_estado_activo">Activo</span>
											</label>
											<div class="form-group" id="contenido_boton_busqueda">
												<input type="text" name="doc_id" id="doc_id" class="form-control" placeholder="Número de Documento de Identidad">
												<span class="input-group-btn" id="btn_buscar_datos_api" style="display:none;">
													<button class="btn bg-indigo legitRipple search_document" type="button">
														<i class="icon-search4" id="icon_search_document"></i>
														<i class="icon-spinner10 spinner" style="display: none;" id="icon_searching_document"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-users2 mr-2"></i> 
												Razón social/Nombre Completo 
											</label> <span style="display: none;" class="label label-danger lbl_estado_empresa lbl_estado_inactivo">Inactivo</span>
											<span style="display: none;" class="label label-success lbl_estado_empresa lbl_estado_activo">Activo</span>
											<input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" placeholder="Nombre Comercial">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-address-book mr-2"></i>
												Dirección fiscal
											</label>
											<input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Direccion fiscal">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-envelop mr-2"></i>
												Email
											</label>
											<input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-phone2 mr-2"></i> 
												Teléfono
											</label>
											<input type="number" class="form-control form-control-sm" name="telefono" id="telefono" placeholder="Teléfono">
										</div>	
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-iphone mr-2"></i> 
												Célular
											</label>
											<input type="number" class="form-control form-control-sm" name="celular" id="celular" placeholder="Célular">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
												Ubigeo
											</label>
											<select class="js-example-basic-single" name="ubigeo" id="ubigeo">
												<option selected value="">Seleccione una opción</option>
												<?php                                    
													foreach ($ubigeo as $value) {
														echo "<option value='".$value->codigo_ubigeo."'>".$value->departamento.' - '.$value->provincia.' - '.$value->distrito."</option>";
													}
												?>
											</select>
										</div>	
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-office mr-2"></i> 
												N° de cuenta de Detracción
											</label>
											<input type="text" class="form-control form-control-sm" name="cuenta_detraccion" id="cuenta_detraccion"  placeholder="N° de cuenta de Detracción">
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-plus mr-2"></i> 
												Detalle Adicional
											</label>
											<textarea name="detalle_adicional" id="detalle_adicional" cols="30" rows="4" maxlength="2500"  class="form-control"></textarea>
										</div>
									</div>
									<div class="float-right">
										<button class="btn bg-indigo legitRipple btn_saveclient" type="button">
											<i class="icon-floppy-disk mr-2"></i>
											Guardar
										</button>
									</div>		
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div style="margin-top: 20px;">

			</div>
			<div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body"  id="contenido_lista_clientes">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de clientes</span>
						</legend>
					</fieldset>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive p-20">
							<table class="table datatable-basic" id="tbl_lista_clientes">
								<thead>
									<tr>
										<th>ID</th>
										<th>Tipo Doc.</th>
										<th>Código</th>
										<th>NumDoc.</th>
										<th>Nombre</th>
										<th>Dirección</th>
										<th>Ubigeo</th>
										
										<th>Departamento</th>
										<th>Provincia</th>
										<th>Distrito</th>

										<th>Email</th>
										<th>Celular</th>
										<th>Detalle</th>
										<th>FecReg.</th>
										<th>Estado</th>
										<th>Sexo</th>
										<th>Fecha Nac.</th>
										<th>Acción</th>
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
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>


<!-- vm_importar_clientes -->
<div id="vm_importar_clientes" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-plus-circle2"></i> &nbsp; Importación de Productos</h6>
			</div>
			<div class="modal-body" id="content_vm_importar_clientes">
				<form name="vm_frm_importar_clientes" id="vm_frm_importar_clientes" action="#" method="post" enctype="multipart/form-data"> 
					<div class="panel panel-body">
						<div class="media no-margin stack-media-on-mobile">
							<div class="media-left media-middle">
								<i class="fa fa-cloud-upload fa-2x text-muted no-edge-top"></i>
							</div>

							<div class="media-body">
								<h6 class="media-heading text-semibold">Descarga nuestra plantilla en excel!</h6>
								<span class="text-muted">Para poder importar o actualizar tus clientes debes descargar la plantilla y enviarlo utilizando el mismo formato!</span>
							</div>

							<div class="media-right media-middle">
								<a href="/facturacionv8/client/descargar_plantilla_importacion" target="_blank" class="btn btn-primary legitRipple">Descargar Plantilla</a>
							</div>
						</div>
					</div>
					<div class="panel panel-body">
						<div class="col-md-12">
							<input type="file" class="file-styled" name="file_data_import" id="file_data_import" placeholder="Selecciona un Archivo">
						</div>
					</div>

					<div class="row">
						<div class="col-md-12" style="margin-top: 10px;">
							<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
							<button class="btn btn-primary legitRipple btn_importar_data" id="btn_importar_data" type="button">
								<i class="icon-floppy-disk mr-2"></i> Iniciar Proceso de Importación y/o Actualización
							</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /vm_importar_clientes -->