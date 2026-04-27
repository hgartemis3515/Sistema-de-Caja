<style>
.btn-default {
	background-color: #dfdfdf;
	border-color: #dfdfdf;
}

.custom-textarea {
	display: block;
	width: 100%;
	padding: 8px 16px;
	font-size: 13px;
	line-height: 1.5384616;
	color: #333333;
	background-color: transparent;
	background-image: none;
	border: 1px solid #ddd;
	border-radius: 3px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	-webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

.navbar-brand {
	float: left;
	padding: 0px 20px;
	font-size: 14px;
	line-height: 20px;
	height: 46px;
}

*, ::after, ::before {
	box-sizing: border-box;
}


[class^="icon-"], [class*=" icon-"] {
	font-family: 'icomoon' !important;
	 
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 1;
	min-width: 1em;
	display: inline-block;
	text-align: center;
	font-size: 16px;
	vertical-align: middle;
	position: relative;
	top: -1px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}
a.breadcrumb-elements-item, a.breadcrumb-item {
	display: inline-block;
	color: inherit;
	transition: opacity ease-in-out .15s;
}
.bg-indigo{
	color: #fff;
}
.border-bottom-2 {
	border-bottom: 2px solid;
}
.breadcrumb-item {
	padding: .625rem 0;
	color: inherit;
}
.button-large{
	margin: 1em;
	text-align: center;
}
.breadcrumb-item+.breadcrumb-item {
	padding-left: .625rem;
}
.breadcrumb-item+.breadcrumb-item::before {
	display: inline-block;
	padding-right: .625rem;
	color: inherit;
	content: "/";
}
.breadcrumb-line-light {
	background-color: #fff;
	border-color: #ddd;
	color: #333;
}
.breadcrumb-item.active {
	color: #999;
}
.btn {
	position: relative;
	font-weight: 500;
	text-transform: uppercase;
	border-width: 0;
	padding: 8px 17px;
}
.cont-pt2{
	padding-top: 2em;
	padding-bottom: 2em;
}
.d-block {
	display: block!important;
}
.dataTables_filter input {
	margin-left: 1em;
}
.dataTables_paginate .paginate_button.current, .dataTables_paginate .paginate_button.current:hover, .dataTables_paginate .paginate_button.current:focus {
	color: #fff;
	background-color: #3F51B5!important;
}
.header-highlight .navbar-header:not([class*=bg-]) {
	background-color: #3F51B5;
	-webkit-box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.1) inset, 0 -1px 0 rgba(255, 255, 255, 0.1) inset;
	box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.1) inset, 0 -1px 0 rgba(255, 255, 255, 0.1) inset;
}
.float-right{
	float: right;
}

.table-responsive {
	padding: 1em;
}
.font-weight-bold{
	font-weight: 700;
}
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
	border-color: #3F51B5;
	transition-timing-function: ease;
}
.label-form{
	font-weight: 700;
	padding: 5px 5px;
}
.label-form i{
	color: #3F51B5;
}
.mr-2, .mx-2 {
	margin-right: .625rem!important;
}
.mb-2{
	margin-bottom: 2em;
}

.page-header-content {
	position: relative;
	padding: 0 1.25rem;
}
.page-title small.d-block {
	margin-left: 0;
}
.page-title small {
	display: inline-block;
	margin-left: .625rem;
}
.page-title small:before {
	content: '';
	margin-right: 20px;
}
.small, small {
	font-size: 80%;
	font-weight: 400;
}

.table-responsive {
	padding: 1em;
}

.text-muted {
	color: #999!important;
}
.title-card{
	font-weight: 700;
	color:  #3F51B5;
	text-transform: uppercase;
	font-size: 14px;
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
<div class="page-header">
	<div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Artículos</span></h4>
			<a class="heading-elements-toggle"><i class="icon-more"></i></a>
		</div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/>
					<span>Boleta</span>
				</a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/factura.svg" style="width: 25px;"/>
					<span>Factura</span>
				</a>
				<a href="/facturacionv8/documentoelectronico/index/07/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/nota_credito.svg" style="width: 25px;"/>
						<span>Nota Crédito</span>
				</a>
				<a href="/facturacionv8/documentoelectronico/index/08/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/nota_debito.svg" style="width: 25px;"/>
					<span>Nota Débito</span>
				</a>
				<a href="/facturacionv8/reportes" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/svg/analytics.svg" style="width: 25px;"/>
					<span>Reporte de Ventas</span>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="content" style="max-width: 1100px; margin: 0 auto;">
	<div class="row">
		<div class="col-md-12 col-md-12">
			<div class="panel">
				<div class="panel-body" id="content_panel_productos">
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
							<li class="active">
								<a href="#agregar_productos_individuales" data-toggle="tab" class="legitRipple" aria-expanded="true">
									<i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Agregar Productos/Servicios</span> 
								</a>
							</li>
							<li class=""><a href="#importar_productos_excel" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-cloud-upload fa-lg position-left"></i> Importar Productos/Servicios en Lote</a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="agregar_productos_individuales">
								<form name="frm_product" id="frm_product" action="">
									<input type="hidden" class="form-control form-control-sm" name="idproducto" id="idproducto" value="<?php echo $idproducto; ?>">
									
									<div class="col-md-6" style="display: none;">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Código Producto SUNAT:
											</label>
											<select class="select2 select_codprodsunat" name="codprodsunat_codigoproducto" id="codprodsunat_codigoproducto">
												<option selected value="">Seleccione una opción</option>
											</select>
										</div>
									</div>
			
									<div class="col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-barcode2 mr-2"></i>
												Cód.Barra - QR - Cod.Único <span class="text-danger">*</span>
											</label>
											<div class="input-group">
												<input type="text" name="codigo" id="txt_codigo" class="form-control" placeholder="Código">
												<span class="input-group-btn">
													<button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
														<i class="icon-rotate-ccw3 mr-2"></i> 
													</button>
												</span>
											</div>
										</div>
									</div>
									
									<div class="col-md-8">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Nombre del Producto o Servicio <span class="text-danger">*</span>
											</label>
											<input type="text" class="form-control form-control-sm" name="nombre_servicio" id="nombre_servicio" placeholder="Nombre de bien o Servicio"> 
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Tipo de IGV <span class="text-danger">*</span>
											</label>
											<select class="select2" name="producto_tipo_afect_igv" id="producto_tipo_afect_igv">
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Moneda <span class="text-danger">*</span>
											</label>
											<select class="select2" name="id_cod_moneda" id="id_cod_moneda">
											</select>
										</div>
									</div>
									<div class="col-md-3" id="content_preciounidad_inc_igv">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												<span id="text_precio_inc_igv">Valor Unitario de Venta (Con IGV)</span> <span class="text-danger">*</span>
											</label>
											<input type="number" class="form-control form-control-sm" name="valor_con_igv" id="valor_con_igv" placeholder="Valor Unitario (Con IGV)"> 
										</div>
									</div>
									<div class="col-md-3" id="content_preciounidad_sin_igv">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Valor Unitario de Venta (Sin IGV) <span class="text-danger">*</span>
											</label>
											<input type="number" class="form-control form-control-sm" name="valor_sin_igv" id="valor_sin_igv" placeholder="Valor Unitario (Sin IGV)"> 
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-users mr-2"></i>
												Categoría
											</label>
											<div class="input-group">
												<select name="id_categoria" id="id_categoria">
													<option selected value="">Seleccione una categoría</option>
												</select>
												<span class="input-group-btn">
													<a class="btn bg-indigo legitRipple " data-toggle="modal" data-target="#modal_categoria" href="javascript:void(0)">
														<i class="icon-plus-circle2 mr-2"></i>
														Agregar Gategoría
													</a>
												</span>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Unidad de Medida <span class="text-danger">*</span>
											</label>
											<select class="id_unidad_medida" name="id_unidad_medida" id="id_unidad_medida">
											</select>
										</div>
									</div>	
									
									<div class="col-md-4" style="display: none;">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Código Detracción
											</label>
											<select class="select2" name="id_cod_detraccion" id="id_cod_detraccion">
												<option selected value="">Seleccione una opción</option>
											</select>
										</div>
									</div>
									
									<div class="inputs_solo_productos col-md-4 valores_no_editables">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Stock Inicial <span class="text-danger">*</span>
											</label>
											<input type="number" class="form-control form-control-sm" name="stock" id="stock" placeholder="Stock Actual"> 
										</div>
									</div>
									<div class="inputs_solo_productos col-md-4">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Stock Mínimo <span class="text-danger">*</span>
											</label>
											<input type="number" class="form-control form-control-sm" name="stock_minimo" id="stock_minimo" placeholder="Stock Mínimo"> 
										</div>
									</div>
									<div class="inputs_solo_productos col-md-4 valores_no_editables">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												<span>Valor de Compra (Inc.IGV) </span><span class="text-danger">*</span>
											</label>
											<input type="number" class="form-control form-control-sm" name="valor_de_compra" id="valor_de_compra" placeholder="Valor de Compra (Con IGV)"> 
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label class="label-form">
												<i class="fa fa-caret-right mr-2"></i>
												Selecciona el Almacén donde Será Registrado el Producto: <span class="text-danger">*</span>
											</label>
											<select class="select2" name="select_sucursal" id="select_sucursal">
											</select>
										</div>
									</div>
			
									<div class="col-md-12">
										<div class="content-group">
											<h6><i class="icon-notebook position-left"></i> Detalle (memo):</h6>
											<div class="mb-15 mt-15">
												<textarea rows="5" cols="5" name="nota_producto" class="custom-textarea" placeholder="Escribe aquí el detalle del producto"></textarea>
											</div>
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="text-right">
											<button class="btn bg-indigo legitRipple btn_product" type="button">
												<i class="icon-floppy-disk mr-2"></i>
												Guardar
											</button>
										</div>
									</div>
								</form>
							</div>

							<div class="tab-pane" id="importar_productos_excel">
								<form name="frm_importarproductos" id="frm_importarproductos" action="#" method="post" enctype="multipart/form-data"> 
									<div class="panel panel-body">
										<div class="media no-margin stack-media-on-mobile">
											<div class="media-left media-middle">
												<i class="fa fa-cloud-upload fa-2x text-muted no-edge-top"></i>
											</div>
		
											<div class="media-body">
												<h6 class="media-heading text-semibold">Descarga nuestra plantilla en excel!</h6>
												<span class="text-muted">Para poder importar tus productos en lote debes descargar la plantilla y enviarlo utilizando el mismo formato!</span>
											</div>
		
											<div class="media-right media-middle">
												<a href="/facturacionv8/files_recursos/plantilla_importacion_productos.xlsx" target="_blank" class="btn btn-primary legitRipple">Descargar Plantilla</a>
											</div>
										</div>
									</div>
									<div class="panel panel-body">
										<div class="col-md-12">
											<input type="file" class="file-styled" name="file_data_import" id="file_data_import" placeholder="Selecciona un Archivo">
										</div>
										
										<div class="col-md-12" style="margin-top: 25px;">
											<button class="btn bg-indigo legitRipple btn_importar_data" id="btn_importar_data" type="button">
												<i class="icon-floppy-disk mr-2"></i>
												Guardar Información
											</button>
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
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_categoria" tabindex="-1" role="dialog" aria-labelledby="modal_categoria" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<h5 class="modal-title" id="modal_categoria">Agregar categoría</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_category" id="frm_category" action="">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-users mr-2"></i>
									Código
								</label>
								<div class="input-group">
									<input type="text" name="codigo" id="txt_codigo_categoria" class="form-control" placeholder="Código">
									<span class="input-group-btn">
										<button class="btn bg-indigo legitRipple btn_generar_categoria" type="button">
											<i class="icon-rotate-ccw3 mr-2"></i>
											Generar
										</button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-12" style="display:none;">
							<div class="form-group">
								<label for="ruc" class="label-form">
									<i class="fa fa-caret-right mr-2"></i>
									Código Sunat
									<i class="fa fa-question-circle-o" data-popup="popover" data-trigger="hover" 
									data-content="Sunat Catálogo. Nro 25: Código del producto Sunat"></i> 
								</label>
								<select class="select2" name="codigosunat" id="codigosunat">
									<?php                                    
										foreach ($codigosunat as $value) {
											echo "<option value='".$value->id_codigoproducto."'>".$value->descripcion." (".$value->id_codigoproducto.")</option>";
										}
										?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="ruc" class="label-form">
									<i class="fa fa-caret-right mr-2"></i>
									Nombre de la categoría
								</label>
								<input type="text" class="form-control form-control-sm" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre de la categoría"> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="ruc" class="label-form">
									<i class="fa fa-caret-right mr-2"></i>
									Descripción
								</label>
								<textarea name="descripcion_categoria" id="descripcion_categoria" cols="30" rows="3" maxlength="250" class="form-control"></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn bg-indigo btn_agregar_categoria"><i class="icon-floppy-disk mr-2"></i>Guardar categoría</button>
			</div>
		</div>
	</div>
</div>