<style>
.fa-question-circle-o{
	font-size: 17px;
	margin-left: 10px;
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Categorías</span></h4>
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
			<div class="panel border-top-indigo" id="content_panel_category" style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="text-uppercase">Agregar Categoría</span> 
						</legend>
					</fieldset>
					<div class="row">
						<form name="frm_category" id="frm_category" action="">
							<input type="hidden" value="" name="idcategoria" id="idcategoria" />
							<div class="col-lg-4">
								<div class="form-group">
									<label for="codigo" class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
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
							<div class="col-lg-6" style="display:none;">
								<div class="form-group">
									<label for="codigosunat" class="label-form col-lg-3">
										<i class="fa fa-caret-right mr-2"></i>
										Código Sunat
										<i class="fa fa-question-circle-o" data-popup="popover" data-trigger="hover" 
										data-content="Sunat Catálogo. Nro 25: Código del producto Sunat"></i> 
									</label>
									<select class="js-example-basic-single" name="codigosunat" id="codigosunat">
										<?php                                    
											foreach ($codigosunat as $value) {
												echo "<option value='".$value->id_codigoproducto."'>".$value->descripcion." (".$value->id_codigoproducto.")</option>";
											}
											?>
									</select>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label for="nombre_categoria" class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
										Nombre de la categoría
									</label>
									<input type="text" class="form-control form-control-sm" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre de la categoría"> 
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group">
									<label for="codigo_cuenta_contable" class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
										Cod. Cuenta Contable
									</label>
									<input type="text" class="form-control form-control-sm" name="codigo_cuenta_contable" id="codigo_cuenta_contable" placeholder="Código de la Cuenta Contable"> 
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group">
									<label for="codigo_centro_costo" class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
										Cod. Centro Costo
									</label>
									<input type="text" class="form-control form-control-sm" name="codigo_centro_costo" id="codigo_centro_costo" placeholder="Código del Centro de Costo"> 
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group">
									<label for="codigo_presupuesto" class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
										Código Presupuesto
									</label>
									<input type="text" class="form-control form-control-sm" name="codigo_presupuesto" id="codigo_presupuesto" placeholder="Código Presupuesto"> 
								</div>
							</div>

							<div class="col-lg-12">
								<div class="form-group">
									<label for="descripcion_categoria" class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
										Descripción
									</label>
									<textarea name="descripcion_categoria" id="descripcion_categoria" cols="30" rows="3" maxlength="250" class="form-control"></textarea>
								</div>
							</div>
							<div class="float-right">
								<button class="btn bg-indigo legitRipple btn_savecategory" type="button">
									<i class="icon-floppy-disk mr-2"></i>
									Guardar
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div style="margin-top: 20px;"></div>
			<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body" id="content_lista_categorias">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Categorías</span>
						</legend>
					</fieldset>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive p-20">
								<table class="table datatable-basic" id="tbl_lista_categorias">
									<thead>
										<tr>
											<th>Fecha</th>
											<th>ID</th>
											<th>Código</th>
											<th>Nombre</th>
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
			<!-- <div id="content_lista_categorias" class="page-header page-header-light" style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
				<div class="page-header-content border-bottom-2 border-bottom-danger">
					<div class="page-title">
						<h5>
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Categorías</span>
							<small class="d-block text-muted">Listado completo de todos las categorías agregadas a tu empresa. En ellos, puedes ejercer acciones como: .... </small>
						</h5>
					</div>
				</div>
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex cont-pt2">
						<div class="row">
							<div class="col-lg-12">
								<div class="table-responsive p-20">
									<table class="table datatable-basic" id="tbl_lista_categorias">
										<thead>
											<tr>
												<th>Fecha</th>
												<th>Código</th>
												<th>Nombre</th>
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
			</div> -->
		</div>
	</div>
	<div class="footer text-muted">
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>
