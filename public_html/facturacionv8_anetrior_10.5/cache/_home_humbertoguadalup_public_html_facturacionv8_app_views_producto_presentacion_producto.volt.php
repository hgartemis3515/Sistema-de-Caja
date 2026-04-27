<div class="row">
	<input type="hidden" value="" id="key_row_presentacion" />
	<div class="col-md-12">
		<fieldset>
			<legend class="text-semibold label-form"><i class="icon-stack-check position-left"></i> Producto: <strong class="presentacion_nombre_producto">LECHE GLORIA EN TARRO - P.V.: S/. 3.2 - Unid.Medida: UNIDAD</strong></legend>
		</fieldset>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-users mr-2"></i>
						Código
					</label>
					<div class="input-group">
						<input type="text" name="presentacion_codigo" id="presentacion_codigo" class="form-control" placeholder="Código">
						<span class="input-group-btn">
							<button class="btn bg-indigo legitRipple btn_generar_codigo_presentacion" type="button"> 
								<i class="icon-rotate-ccw3 mr-2 x"></i> 
							</button>
						</span>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-file-text position-left"></i> Nombre Presentación <span class="text-danger">*</span>
					</label>
					<input type="text" class="form-control form-control-sm" name="presentacion_nombre" id="presentacion_nombre" placeholder="Nombre Presentación"> 
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-box-add mr-2"></i>Nueva Unidad Medida: <span class="text-danger">*</span>
					</label>
					<select class="select_presentacion_nueva_unidad" name="select_presentacion_nueva_unidad" id="select_presentacion_nueva_unidad">
						
					</select>
				</div>
			</div>
			
			<div class="col-md-3">
				<label for="presentacion_pventa" id="texto_presentacion_pventa">Precio Venta (Inc.IGV): </label>
				<div class="input-group">
					<span class="input-group-addon font-weight-bold presentacion_moneda_producto simbolo_moneda_nuevoproducto">S/.</span>
					<input type="text" class="form-control" value="" name="presentacion_pventa" id="presentacion_pventa">
				</div>
			</div>

			<div class="col-md-3">
				<label for="presentacion_pventa_minimo" id="texto_presentacion_pventa_minimo">Precio Mínimo: </label>
				<div class="input-group">
					<span class="input-group-addon font-weight-bold presentacion_moneda_producto simbolo_moneda_nuevoproducto">S/.</span>
					<input type="text" class="form-control" value="0" name="presentacion_pventa_minimo" id="presentacion_pventa_minimo">
				</div>
			</div>

			<div class="col-md-3">
				<label for="presentacion_cantidad">Contiene: </label>
				<div class="input-group">
					<input type="text" class="form-control" value="" name="presentacion_cantidad" id="presentacion_cantidad">
					<span class="input-group-addon font-weight-bold presentacion_unidad_base">UNIDAD</span>
				</div>
			</div>
			
			<div class="col-md-12 mt-2 text-right">
				<button type="button" class="btn btn-success margin-bottom-10 btn-extra-padding btn-labeled btn-xs mx-1 mr-2 text-uppercase font-weight-bold legitRipple btn_agregar_nueva_presentacion"><b><i class="icon-plus-circle2"></i></b> Agregar</button>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12" style="font-size: 12px;">
				<fieldset>
					<legend class="text-semibold label-form"> <i class="icon-list mr-2" aria-hidden="true"></i>Lista de productos:</legend>
				</fieldset>
			</div>
			<div class="col-md-12 text-right" style="padding-bottom: 10px;">
				<button type="button" id="btn_eliminar_presentacion" class="btn btn-danger margin-bottom-10 btn-extra-padding btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_presentacion"><b><i class="icon-minus-circle2"></i></b> Eliminar</button>
			</div>
			<div class="col-md-12 mb-6">
				<div class="jqGrid content_tabla_presentaciones" style="overflow: auto; width: 100%; height: 200px;">
					<table id='lista_presentaciones_producto' class='scroll'></table>
				</div>
			</div>
		</div>
	</div>	
</div>
<div class="row">
	<div class="modal-footer" style="padding-top: 10px;">
		<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
		<button type="button" id="btn_guardar_nuevo_producto" class="btn btn-primary btn_guardar_nuevo_producto"><i class="icon-floppy-disk mr-2"></i>Guardar</button>
	</div>
</div>