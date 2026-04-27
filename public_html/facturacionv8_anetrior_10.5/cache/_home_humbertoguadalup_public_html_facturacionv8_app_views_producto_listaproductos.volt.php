<style>
.navbar-brand {
	float: left;
	padding: 3px 20px;
	font-size: 14px;
	line-height: 20px;
	height: 46px;
}

/* LOADING */
.loading {
	/* position: absolute; */
	top: 50%;
	left: 50%;
}
.loading-bar {
	display: inline-block;
	width: 4px;
	height: 18px;
	border-radius: 4px;
	animation: loading 1s ease-in-out infinite;
}
.loading-bar:nth-child(1) {
	background-color: #3f51b5;
	animation-delay: 0;
}
.loading-bar:nth-child(2) {
	background-color: #2196f3;
	animation-delay: 0.09s;
}
.loading-bar:nth-child(3) {
	background-color: #4caf50;
	animation-delay: .18s;
}
.loading-bar:nth-child(4) {
	background-color: #00bcd4;
	animation-delay: .27s;
}
/* clases para popup */
@media only screen and (max-width: 400px) {
	.modal-dialog {
        position: relative;
    width: auto;
    margin: 10px;
    height: 100%;
    overflow: inherit!important;
    transform: inherit!important;
	
	}
}
@media (min-width: 600px) and (max-width: 1000px) {
	.modal-dialog {
    position: relative;
    width: auto; 
    margin: 10px;
    height: 100%;
    overflow: inherit!important;
	transform: inherit!important;
	}
}
@keyframes loading {
	0% {
	transform: scale(1);
	}
	20% {
	transform: scale(1, 2.2);
	}
	40% {
	transform: scale(1);
	}
}
/* /LOADING */
.btn-options button, .btn-options a {
	position: relative;
	font-weight: 500;
	text-transform: uppercase;
	border-width: 0;
	padding: 8px 17px;
}
.close {
	text-shadow: none;
	opacity: 1;
}
li.li-close {
	position: absolute;
	top: .7em;
	right: 1em
}
.icono-close{
	font-weight: 700;
	color: #FFF;
}
.dataTables_filter > label:after {
	content: "";
}
@media (min-width: 800px){
	.dataTables_filter > label:after {
	content: "\e98e";
	}
}
.btn-labeled.btn-xs > b {
	padding: 10px;
}


.custom-textarea:focus {
	/* outline: 0; */
	/* border-color: transparent; */
	border-bottom-color: #009688;
	-webkit-box-shadow: 0 1px 0 #009688;
	box-shadow: 0 1px 0 #009688;
	border-color: #ddd;
	outline: 0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(221, 221, 221, 0.6);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(221, 221, 221, 0.6);
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
/* MODIFICACIÓN DE CAMPOS */
div.DTE_Field input:focus, div.DTE_Field textarea:focus {
    background-color: #fff;
}
div.DTE_Field_Type_textarea textarea {
    padding: 3px;
    width: 100%;
    height: 80px;
    border-radius: 15px;
    border-color: #ddd;
}
input#DTE_Field_precio_venta, input#DTE_Field_stock, input#DTE_Field_stock_minimo, input#DTE_Field_precio_compra {
    border-radius: 15px;
    border: 1px solid #ddd;
    padding-left: 15px;
	max-width: 100px;
}
input#DTE_Field_codigo, input#DTE_Field_fecha_vencimiento, input#DTE_Field_marca {
    border-radius: 15px;
    border: 1px solid #ddd;
    padding-left: 15px;
}
select#DTE_Field_afecto_icbper {
    border-radius: 15px;
    border: 1px solid #ddd;
    padding: 5px;
}
.dataTable thead .sorting, .dataTable thead .sorting_asc, .dataTable thead .sorting_asc_disabled, .dataTable thead .sorting_desc, .dataTable thead .sorting_desc_disabled {
    padding-right: 70px;
}
.valor_editable {
	border-bottom: 1px dashed #ccc;
	padding: 0.5em;
	margin: 0.5em;
}

.valor_editable:hover {
	background: #f3f3f3;
	border: 1px dashed #333;
}
</style>
<div class="page-header">
<div class="page-header-content">
	<div class="page-title">
		<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Lista de Artículos</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a>
	</div>
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
<div class="panel border-top-indigo">
	<div class="panel-body" id="content_lista_productos">

		<div class="row">
			<div class="col-md-12 btn-options text-aling" style="margin-bottom: 20px;">

					<!-- INICIO: Registro Productos -->
					<button style="margin-right: 7px; margin-bottom: 5px;" type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_agregarproducto"><b><i class="icon-pencil3"></i></b> Registrar Producto/Servicio</button>
					<!-- FIN: Registro productos -->

					<!-- INICIO: ingresos -->
					<div class="btn-group" style="margin-right: 7px; margin-bottom: 5px;">
						<button type="button" id="btn_agregar_stock" class="btn btn-success btn-rounded legitRipple"><i class="icon-plus-circle2 position-left"></i> Ingresos</button>
						<button type="button" class="btn btn-success btn-rounded dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="legitRipple-ripple" style="left: 43.782%; top: 45.8882%; transform: translate3d(-50%, -50%, 0px); width: 310.494%; opacity: 0;"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="#" onclick="javascript:void(0);" id="btn_ingresos_reporte"><i class="icon-menu7"></i> Ver Reporte</a></li>
						</ul>
					</div>
					<!-- FIN: Ingresos -->


					<!-- INICIO: Salidas -->
					<div class="btn-group" style="margin-right: 7px; margin-bottom: 5px;">
						<button type="button" id="btn_disminuir_stock" class="btn btn-danger btn-rounded legitRipple"><i class="icon-minus-circle2 position-left"></i> Salidas</button>
						<button type="button" class="btn btn-danger btn-rounded dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="legitRipple-ripple" style="left: 43.782%; top: 45.8882%; transform: translate3d(-50%, -50%, 0px); width: 310.494%; opacity: 0;"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="#" onclick="javascript:void(0);" id="btn_salidas_reporte"><i class="icon-menu7"></i> Ver Reporte</a></li>
						</ul>
					</div>
					<!-- FIN: salidas -->


					<!-- INICIO: traslados -->
					<div class="btn-group" style="margin-right: 7px; margin-bottom: 5px;">
						<button type="button" id="btn_traslados" class="btn btn-info btn-rounded legitRipple"><i class="icon-shuffle position-left"></i> Traslados</button>
						<button type="button" class="btn btn-info btn-rounded dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="legitRipple-ripple" style="left: 43.782%; top: 45.8882%; transform: translate3d(-50%, -50%, 0px); width: 310.494%; opacity: 0;"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="#" onclick="javascript:void(0);" id="btn_traslados_reporte"><i class="icon-menu7"></i> Ver Reporte</a></li>
						</ul>
					</div>
					<!-- FIN: traslados -->

					<!-- INICIO: transformacion -->
					<div class="btn-group" style="margin-right: 7px; margin-bottom: 5px;">
						<button type="button" id="btn_transformacion" class="btn btn-primary btn-rounded legitRipple"><i class="icon-shuffle position-left"></i> Transformación</button>
						<button type="button" class="btn btn-primary btn-rounded dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="legitRipple-ripple" style="left: 43.782%; top: 45.8882%; transform: translate3d(-50%, -50%, 0px); width: 310.494%; opacity: 0;"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="#" onclick="javascript:void(0);" id="btn_transformacion_reporte"><i class="icon-menu7"></i> Ver Reporte</a></li>
						</ul>
					</div>
					<!-- FIN: transformacion -->
					
					<button style="margin-right: 7px; margin-bottom: 5px;" type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_importarproductos"><b><i class="icon-file-excel"></i></b> Importar</button>
					<button style="margin-right: 7px; margin-bottom: 5px;" type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_actulizarenlote"><b><i class="icon-file-excel"></i></b> Actualizar en Lote</button>
			</div>
			
			<div class="col-md-8 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-box-add"></i> Almacén: <span class="text-danger">*</span>
					</label>
					<select class="select select2 select_almacen" name="select_almacen_mostrar" id="select_almacen_mostrar">
						<?php echo $opciones_select; ?>
					</select>
				</div>
			</div>

			<div class="col-md-4 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-box-add"></i> Tipo: <span class="text-danger">*</span>
					</label>
					<select class="select select2" name="select_tipo_item" id="select_tipo_item">
						<option value="0">Mostrar Productos y Servicios</option>
						<option value="1">Mostrar Solo Productos</option>
						<option value="2">Mostrar Solo Servicios</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table datatable-basic" id="tbl_lista_productos">
						<thead>
							<tr>
								<th>Fecha Registro</th> <!-- 0 -->
								<th>ID</th> <!-- 0 -->
								
								<th>CÓDIGO</th> <!-- 1 -->
								<th>ID Almacén</th> <!-- 2 -->
								<th>Sucursal/Almacén</th> <!-- 3 -->
								
								<th>ID Categ.</th> <!-- 4 -->
								<th>Nom. Categ.</th> <!-- 5 -->
								<th width="250px">Producto/Servicio</th> <!-- 6 -->

								<th>ID Unidad</th> <!-- 7 -->
								<th>Unidad</th> <!-- 8 -->
								<th>ID TipoAfec.IGV</th> <!-- 9 -->
								<th>TipoAfec.IGV</th> <!-- 10 -->

								<th>Costo Promedio</th> <!-- 11 -->
								<th>Stock Valorizado</th>

								<th>Costo</th> <!-- 12 -->

								<th>Cod Moneda</th> <!-- 12 -->
								<th>Precio Venta</th> <!-- 13 -->
								<th  width="250px">Nota</th> <!-- 14 -->
								<th>Stock</th> <!-- 15 -->
								
								<th>Stock Mínimo</th> <!-- 16 -->
								<th>ICBPER</th> <!-- 17 -->
								<th>MultiPrecio</th> <!-- 18 -->

								<th>Vencimiento</th> <!-- 19 -->
								<th>Marca</th> <!-- 20 -->
								<th>Peso</th>

								<th class="text-center">Acción</th> <!-- 19 -->
							</tr>
						</thead>
						<tbody></tbody>
						<!--
						<tfoot>
							
						</tfoot>
						-->
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- vm_agregar_articulo -->
<div id="vm_agregar_articulo" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" style="padding: 0px;" id="content_popup_producto">
				<div class="row">
					<div class="tabbable">
						<ul class="nav nav-tabs bg-teal-400 opciones_producto">
							<li class="active"><a href="#registrar_producto" data-toggle="tab"><i class="icon-file-plus position-left"></i> Producto / Servicio</a></li>
							<li ><a href="#presentacion_producto" data-toggle="tab"><i class="icon-stack-plus position-left"></i> Presentaciones <span style="color: #fff !important;" class="label bg-success">Nuevo</span></a></li>
							<li ><a href="#registrar_imagen" data-toggle="tab"><i class="fa fa-picture-o position-left"></i> Imágenes <span style="color: #fff !important;" class="label bg-success">Nuevo</span></a></li>
							<li class="li-close"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true" class="icono-close">&times;</span>
								</button></li>
						</ul>

						<div class="tab-content" style="padding: 0px 15px 10px 15px;">
							<div class="tab-pane active" id="registrar_producto">
								<?= $this->partial('producto/registrar_producto') ?>
							</div>

							<div class="tab-pane" id="presentacion_producto">
								<?= $this->partial('producto/presentacion_producto') ?>
							</div>

							<div class="tab-pane" id="registrar_imagen">
								<?= $this->partial('producto/registrar_imagenes') ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->

<!-- vm_importar_articulos -->
<div id="vm_importar_articulo" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-plus-circle2"></i> &nbsp; Carga Masiva de Productos</h6>
			</div>
			<div class="modal-body" id="content_vm_importar_articulo">
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
								<a href="/facturacionv8/producto/descargar_plantilla_importacion" target="_blank" class="btn btn-primary legitRipple">Descargar Plantilla</a>
							</div>
						</div>
					</div>
					<div class="panel panel-body">
						<div class="col-md-12">
							<input type="file" class="file-styled" name="file_data_import" id="file_data_import" placeholder="Selecciona un Archivo">
						</div>
						
						<div class="col-md-12 col-xs-12" style="margin-top: 10px;">
							<div class="alert alert-info alert-styled-left alert-bordered info_texto_almacen_2" style="margin-bottom: 4px;">
								
							</div>
						</div>
						
					</div>

					<div class="row">
						<div class="col-md-12" style="margin-top: 10px;">
							<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
							<button class="btn btn-primary legitRipple btn_importar_data" id="btn_importar_data" type="button">
								<i class="icon-floppy-disk mr-2"></i> Iniciar Proceso de Importación
							</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->


<!-- vm_actualizacion_productos -->
<div id="vm_actualizacion_productos" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-plus-circle2"></i> &nbsp; Actualización Masiva de Productos</h6>
			</div>
			<div class="modal-body" id="content_vm_actualizar_articulo">
				<form name="frm_actualizarproductos" id="frm_actualizarproductos" action="#" method="post" enctype="multipart/form-data"> 
					<div class="panel panel-body">
						<div class="media no-margin stack-media-on-mobile">
							<div class="media-left media-middle">
								<i class="fa fa-cloud-upload fa-2x text-muted no-edge-top"></i>
							</div>

							<div class="media-body">
								<h6 class="media-heading text-semibold">Descargar Productos de: <strong class="text-primary" id="actualizacion_nombre_sucursal"></strong></h6>
								<span class="text-muted">Para poder actualizar en Lote debes descargar todos tus productos y volverlos a subir respetando el formato!</span>
							</div>
 
							<div class="media-right media-middle">
								<a href="/facturacionv8/producto/export_to_excel/" id="enlace_descarga_productos" target="_blank" class="btn btn-primary legitRipple">Descargar Plantilla</a>
							</div>
						</div>
					</div>
					<div class="panel panel-body">
						<div class="col-md-12">
							<input type="file" class="file-styled" name="file_data_actualizacion" id="file_data_actualizacion" placeholder="Selecciona un Archivo">
						</div>
						
						<div class="col-md-12 col-xs-12" style="margin-top: 10px;">
							<div class="alert alert-info alert-styled-left alert-bordered info_texto_almacen_2" style="margin-bottom: 4px;">
								
							</div>
						</div>

						<div class="col-md-12 col-xs-12" style="margin-top: 10px;">
							<div class="form-group">
								<div class="checkbox checkbox-switch">
									<label class="label-form">
										¿Deseas Actualizar Todos los Productos de Todas las Sucursales? <input name="opcion_actualizacion_total" id="opcion_actualizacion_total" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12" style="margin-top: 10px;">
							<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
							<button class="btn btn-primary legitRipple btn_actualizar_data" id="btn_actualizar_data" type="button">
								<i class="icon-floppy-disk mr-2"></i> Iniciar Proceso de Actualización
							</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->

<!-- Aumentar Stock -->
<div id="vm_agregar_stock" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-plus-circle2"></i> &nbsp; Aumentar Stock de Productos</h6>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label class="label-form"><i class="icon-cart-add position-left"></i>Aquí Debes Buscar y Seleccionar un Producto:</label>
						<div class="form-group has-feedback has-feedback-left" id="contenedor_select_productos">
							<select name="select_producto_buscar" id="select_producto_buscar" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar">
								<option></option>
							</select>
							<div class="text-primary text-size-small info_sucursal_seleccionada" style="margin-bottom: 5px; margin-top: 8px;"></div>
						</div>
						<input type="hidden" value="" id="simbolo_unidad" />
					</div>
					<div id="contenedor_cuadros_stocks" style="display: none;">
						<div class="col-md-4 col-xs-6">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-stack position-left"></i> Stock Actual
								</label>
								<div class="input-group">
									<span class="input-group-addon font-weight-bold simbolo_unidad_prod"></span>
									<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="stock_actual_producto" id="stock_actual_producto" placeholder="Cantidad Actual" disabled>
								</div>
							</div>
						</div>

						<div class="col-md-4 col-xs-6">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-stack position-left"></i> Cant. a Ingresar <span class="text-danger">*</span>
								</label>
								<div class="input-group">
									<span class="input-group-addon font-weight-bold simbolo_unidad_prod"></span>
									<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_stock_producto" id="nuevo_stock_producto" placeholder="Cantidad que Ingresará">
								</div>
							</div>
						</div>

						<div class="col-md-4 col-xs-12">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-cash position-left"></i> Precio de Compra <span class="text-danger">*</span>
								</label>
								<div class="input-group">
									<span class="input-group-addon font-weight-bold moneda_precio_compra"></span>
									<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="costo_unitario" id="costo_unitario" placeholder="Precio de Compra">
								</div>
							</div>
						</div>

						<div class="col-lg-12 col-sm-12 col-md-12">
							<div class="form-group">
								<h6><i class="icon-notebook position-left"></i> Observación:</h6>
								<div class="mb-15 mt-15">
									<textarea rows="3" cols="3" name="nota_ingreso" id="nota_ingreso" class="custom-textarea" placeholder="Escribe aquí una observación"></textarea>
								</div>
							</div>
						</div>

						<div class="col-md-12 col-xs-12" id="contenedor_mensaje">
							<div class="alert alert-info alert-styled-left text-blue-800 content-group" id="text_cambio_stock">
							</div>
						</div>
						
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_registrar_entrada" class="btn btn-primary">Si Agregar Stock Ahora!</button>
			</div>
		</div>
	</div>
</div>
<!-- /aumentar Stock -->

<!-- Disminuir Stock -->
<div id="vm_disminuir_stock" class="modal fade" tabindex="-1">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Disminuir Stock de Productos</h6>
		</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<label class="label-form"><i class="icon-cart-add position-left"></i>Aquí Debes Buscar y Seleccionar un Producto:</label>
					<div class="form-group has-feedback has-feedback-left" id="contenedor_select_productos_2">
						<select name="select_producto_buscar_2" id="select_producto_buscar_2" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar_2">
							<option></option>
						</select>
						<div class="text-primary text-size-small info_sucursal_seleccionada" style="margin-bottom: 5px; margin-top: 8px;"></div>
					</div>
					<input type="hidden" value="" id="simbolo_unidad" />
				</div>
				<div id="contenedor_cuadros_stocks_2" style="display: none;">
					<div class="col-md-6 col-xs-6">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-stack position-left"></i> Stock Actual
							</label>
							<div class="input-group">
								<span class="input-group-addon font-weight-bold simbolo_unidad_prod"></span>
								<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="stock_actual_producto_2" id="stock_actual_producto_2" placeholder="Cantidad Actual" disabled>
							</div>
						</div>
					</div>

					<div class="col-md-6 col-xs-6">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-stack position-left"></i> Cant. a Disminuir <span class="text-danger">*</span>
							</label>
							<div class="input-group">
								<span class="input-group-addon font-weight-bold simbolo_unidad_prod"></span>
								<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_stock_producto_2" id="nuevo_stock_producto_2" placeholder="Cantidad que Disminuirá">
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-sm-12 col-md-12">
						<div class="form-group">
							<h6><i class="icon-notebook position-left"></i> Observación:</h6>
							<div class="mb-15 mt-15">
								<textarea rows="3" cols="3" name="observacion_disminuirstock" id="observacion_disminuirstock" class="custom-textarea" placeholder="Escribe aquí una observación"></textarea>
							</div>
						</div>
					</div>
					
					<div class="col-md-12 col-xs-12" id="contenedor_mensaje_2">
						<div class="alert alert-info alert-styled-left text-blue-800 content-group" id="text_cambio_stock_2">

						</div>
					</div>
					
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
			<button type="button" id="btn_registrar_salida" class="btn btn-primary">Sí, Disminuir Stock Ahora!</button>
		</div>
	</div>
</div>
</div>
<!-- /Disminuir Stock -->

<!-- Traslados -->
<div id="vm_traslados" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="content_vm_traslado">
			<div class="modal-header bg-info">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Traslados Entre Almacenes</h6>
			</div>
	
			<div class="modal-body">
				<form id="data_traslado" >
					<div class="row">
						<div class="col-md-12">
							<fieldset>
								<legend class="text-semibold label-form"><i class="icon-reading position-left"></i> Almacén de Origen:</legend>
								<div class="col-md-4">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-box-add mr-2"></i>Almacén Origen: <span class="text-danger">*</span>
										</label>
										<select class="select select2 select_almacen_origen" name="select_almacen_origen" id="select_almacen_origen">
											<?php echo $opciones_select; ?>
										</select>
									</div>
								</div>

								<div class="col-md-5">
									<label class="label-form"><i class="icon-cart-add position-left"></i>Producto:</label>
									<div class="form-group has-feedback has-feedback-left" id="contenedor_select_productos_3">
										<select name="select_producto_buscar_3" id="select_producto_buscar_3" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar_3">
										</select>
									</div>

									<input type="hidden" value="" id="codigo_producto_traslado" />
									<input type="hidden" value="" id="key_row_traslado" />
									<input type="hidden" value="" id="descripcion_prod_traslado" />
									<input type="hidden" value="" id="unidad_medida_traslado" />
								</div>

								<div class="form-group col-md-3">
									<label class="label-form"><i class="icon-pencil position-left"></i> <span id="txt_cantidad_traslado">Cantidad</span>: </label>
									<div class="input-group">
										<input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cantidad_traslado" id="cantidad_traslado" placeholder="Cantidad" class="form-control cantidad_traslado" required>
										<span class="input-group-btn">
											<button class="btn bg-indigo btn-icon legitRipple search_document btn_agregar_producto_traslado" type="button">
												<i class="icon-plus-circle2" id="icon_search_document"></i>
												<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
											</button>
										</span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px; text-transform: uppercase; font-weight: 700;">
										Lista de productos:
									</div>
									<div class="col-md-6" style="text-align: right; padding-bottom: 4px;">
										<button type="button" class="btn btn-danger margin-bottom-10 btn-extra-padding btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminarproducto"><b><i class="icon-cross2"></i></b> Eliminar</button>
									</div>
									<div class="col-md-12 mb-6">
										<div class="jqGrid_traslado content_tabla_detalle">
											<table id='lista_productos_traslado' class='scroll'></table>
										</div>
									</div>
								</div>
							</fieldset>
						</div>	
					</div>

					<div class="row">
						<div class="col-md-12 mt-15">
							<fieldset>
								<legend class="text-semibold label-form"><i class="icon-reading position-left"></i> Almacén Destino:</legend>
								<div class="col-md-4">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-box-add mr-2"></i>Almacén Destino: <span class="text-danger">*</span>
										</label>
										<select class="select select2 select_almacen_destino" name="select_almacen_destino" id="select_almacen_destino">
											<?php echo $opciones_select; ?>
										</select>
									</div>
								</div>

								<div class="form-group col-md-8">
									<label class="label-form"><i class="icon-pencil position-left"></i> <span id="txt_nota_traslado">Nota</span>: </label>
									<input type="text" title="Escribe una Nota para el Traslado" name="nota_traslado" id="nota_traslado" placeholder="Escribe una Nota para el Traslado" class="form-control nota_traslado" required>
								</div>
							</fieldset>
						</div>
					</div>
				</form>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_gistrar_traslado" class="btn btn-primary">Iniciar Traslado!</button>
			</div>
		</div>
	</div>
</div>
<!-- /traslados -->


<!-- /Modal Transformacion -->
<?= $this->partial('producto/transformacion_productos') ?>

<!-- Ventana para Agregar imágen -->
<style>
    .kv-file-content {
        max-height: 300px !important;
    }

    .file-thumbnail-footer {
        display: none !important;
    }
</style>

<div id="vm_cargar_imagen" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
                    <div class="col-lg-12">
                        <input id="fileimage" type="file" class="file-input" accept=".jpg,.gif,.png">
                        <div class="text-right mt-10">
                        <button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary legitRipple" id="btn_guardarimagen"><i class="icon-spinner6 spinner position-left btn_guardarimagen_loading" style="display: none;"></i><i class="icon-floppy-disk position-left btn_guardarimagen_icono"></i> Guardar Imágen</button>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para Agregar imágen -->

<?= $this->partial('producto/modal_reporte_in_sa') ?>

<?= $this->partial('producto/modal_reporte_traslados') ?>

<?= $this->partial('producto/modal_reporte_transformacion') ?>

<?= $this->partial('producto/modal_ver_stock_varias_sucursales') ?>

<script>
var num_decimales = <?php echo $numero_decimales; ?>;
</script>