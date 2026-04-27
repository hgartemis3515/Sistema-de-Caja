<!-- Transformación -->
<div id="vm_transformacion" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="content_vm_transformacion">
			<div class="modal-header bg-info">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Transformación de Productos</h6>
			</div>
	
			<div class="modal-body">
				<form id="data_transformacion" >
					<div class="row">
						<div class="col-md-12">
								<div class="col-md-4">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-box-add mr-2"></i>Almacén Origen: <span class="text-danger">*</span>
										</label>
										<select class="select select2 select_aorigen_transformacion" name="select_aorigen_transformacion" id="select_aorigen_transformacion">
											<?php echo $opciones_select; ?>
										</select>
									</div>
								</div>

								<div class="col-md-5">
									<label class="label-form"><i class="icon-cart-add position-left"></i>Producto:</label>
									<div class="form-group has-feedback has-feedback-left" id="contenedor_select_productos_4">
										<select name="select_producto_buscar_4" id="select_producto_buscar_4" data-placeholder="Selecciona un Producto..." class="select_producto_buscar_4">
										</select>
									</div>

									<input type="hidden" value="" id="codigo_producto_transf_inicial" />
									<input type="hidden" value="" id="key_row_transf_inicial" />
									<input type="hidden" value="" id="descripcion_prod_transf_inicial" />
									<input type="hidden" value="" id="unidad_medida_transf_inicial" />
								</div>

								<div class="form-group col-md-3">
									<label class="label-form"><i class="icon-pencil position-left"></i> <span id="txt_cantidad_transf_inicial">Cantidad</span>: </label>
									<div class="input-group">
										<input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cantidad_transf_inicial" id="cantidad_transf_inicial" placeholder="Cantidad" class="form-control cantidad_transf_inicial" required>
										<span class="input-group-btn">
											<button class="btn bg-indigo btn-icon legitRipple search_document btn_agregar_prod_transf_inicial" type="button">
												<i class="icon-plus-circle2" id="icon_search_document"></i>
												<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
											</button>
										</span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px; text-transform: uppercase; font-weight: 700;">
										Lista de Materiales:
									</div>
									<div class="col-md-6" style="text-align: right; padding-bottom: 4px;">
										<button type="button" class="btn btn-danger margin-bottom-10 btn-extra-padding btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_prod_transf_inicial"><b><i class="icon-cross2"></i></b> Eliminar</button>
									</div>
									<div class="col-md-12 mb-6">
										<div class="jqGrid_productos_transf_inicial content_tabla_detalle">
											<table id='lista_productos_transf_inicial' class='scroll'></table>
										</div>
									</div>
								</div>
						</div>	
					</div>



					<div class="row" style="margin-top:30px;">
						<div class="col-md-12">
								<div class="col-md-4">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-box-add mr-2"></i>Almacén Destino: <span class="text-danger">*</span>
										</label>
										<select class="select select2 select_adestino_transformacion" name="select_adestino_transformacion" id="select_adestino_transformacion">
											<?php echo $opciones_select; ?>
										</select>
									</div>
								</div>

								<div class="col-md-5">
									<label class="label-form"><i class="icon-cart-add position-left"></i>Producto:</label>
									<div class="form-group has-feedback has-feedback-left" id="contenedor_select_productos_5">
										<select name="select_producto_buscar_5" id="select_producto_buscar_5" data-placeholder="Selecciona un Producto" class="select_producto_buscar_5">
										</select>
									</div>

									<input type="hidden" value="" id="codigo_producto_transf_final" />
									<input type="hidden" value="" id="key_row_transf_final" />
									<input type="hidden" value="" id="descripcion_prod_transf_final" />
									<input type="hidden" value="" id="unidad_medida_transf_final" />
								</div>

								<div class="form-group col-md-3">
									<label class="label-form"><i class="icon-pencil position-left"></i> <span id="txt_cantidad_transf_final">Cantidad</span>: </label>
									<div class="input-group">
										<input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cantidad_transf_final" id="cantidad_transf_final" placeholder="Cantidad" class="form-control cantidad_transf_final" required>
										<span class="input-group-btn">
											<button class="btn bg-indigo btn-icon legitRipple search_document btn_agregar_prod_transf_final" type="button">
												<i class="icon-plus-circle2" id="icon_search_document"></i>
												<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
											</button>
										</span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px; text-transform: uppercase; font-weight: 700;">
										Producto Final:
									</div>
									<div class="col-md-6" style="text-align: right; padding-bottom: 4px;">
										<button type="button" class="btn btn-danger margin-bottom-10 btn-extra-padding btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_prod_transf_final"><b><i class="icon-cross2"></i></b> Eliminar</button>
									</div>
									<div class="col-md-12 mb-6">
										<div class="jqGrid_productos_transf_final content_tabla_detalle">
											<table id='lista_productos_transf_final' class='scroll'></table>
										</div>
									</div>
								</div>
						</div>	
					</div>

                    <div class="row">
						<div class="form-group col-md-12 mt-15">
							<label class="label-form"><i class="icon-pencil position-left"></i> <span id="txt_nota_traslado">Nota</span>: </label>
                            <input type="text" title="Nota para la Transformación de Productos" name="nota_transformacion" id="nota_transformacion" placeholder="Nota para la Transformación de Productos" class="form-control nota_transformacion" required>
						</div>
					</div>
				</form>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_gistrar_transformacion" class="btn btn-primary">Registrar Transformación!</button>
			</div>
		</div>
	</div>
</div>
<!-- /Transformación -->