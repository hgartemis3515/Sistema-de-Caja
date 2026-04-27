<style>
	.checkbox, .radio {
    margin-top: 0px;
    margin-bottom: 0px;
}
.input-select-categoria .select2-selection--single {
		display: inline-grid;
		width: 100%;
	}
	.padding-bottom-5px{
		padding: 5px; 
	}
</style>
<form name="frm_nuevo_producto" id="frm_nuevo_producto" action="">
	<div class="row" id="contenido_nuevo_producto" style="margin-left: 7px; margin-right: 7px;">
		<div class="row">
			<input type="hidden" class="form-control form-control-sm" name="idproducto_newprod" id="idproducto_newprod">
			<input type="hidden" class="form-control form-control-sm" name="item_detraccion_codigo" id="item_detraccion_codigo">
			<input type="hidden" class="form-control form-control-sm" name="item_detraccion_porcentaje" id="item_detraccion_porcentaje">

			<div class="col-md-6" style="display: none;">
				<div class="form-group">
					<label class="label-form">
						<i class="fa fa-caret-right mr-2"></i>
						Código Producto SUNAT:
					</label>
					<select class="select select_codprodsunat" name="codprodsunat_codigoproducto" id="codprodsunat_codigoproducto">
						<option selected value="">Seleccione una opción</option>
					</select>
				</div>
			</div>

			<div class="col-md-4  col-xs-12">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-barcode2 mr-2"></i> Código <span class="text-danger">*</span>
					</label>
					<div class="input-group">
						<input type="text" name="nuevo_producto_codigo" id="nuevo_producto_codigo" class="form-control" placeholder="Código">
						<span class="input-group-btn">
							<button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
								<i class="icon-rotate-ccw3 mr-2"></i> 
							</button>
						</span>
					</div>
				</div>
			</div>
			
			<div class="col-md-8  col-xs-12">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-file-text position-left"></i> Nombre del Producto o Servicio <span class="text-danger">*</span>
					</label>
					<input type="text" class="form-control form-control-sm" name="nuevo_producto_nombre_servicio" id="nuevo_producto_nombre_servicio" placeholder="Nombre de bien o Servicio"> 
				</div>
			</div>

			<div class="col-md-3 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-percent position-left"></i> Tipo de IGV <span class="text-danger">*</span>
					</label>
					<select class="select" name="nuevo_producto_tipo_afect_igv" id="nuevo_producto_tipo_afect_igv">
					</select>
				</div>
			</div>

			<div class="col-md-3 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-cash2"></i> Moneda <span class="text-danger">*</span>
					</label>
					<select class="select" name="nuevo_producto_moneda" id="nuevo_producto_moneda">
					</select>
				</div>
			</div>

			<div class="col-md-3 col-xs-6" id="content_preciounidad_inc_igv">
				<div class="form-group">
					<label for="nuevo_producto_valor_con_igv" class="padding-bottom-5px">Precio V. (Inc.IGV)</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_valor_con_igv" id="nuevo_producto_valor_con_igv" placeholder="Valor Unitario (Con IGV)">
					</div>
				</div>
			</div>

			<div class="col-md-3 col-xs-6" id="content_preciounidad_sin_igv">
				<div class="form-group">
					<label for="producto_total" class="padding-bottom-5px">Precio V. (Sin IGV)</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_valor_sin_igv" id="nuevo_producto_valor_sin_igv" placeholder="Valor Unitario (Sin IGV)">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" id="content_tipo_cambio" style="display: none;">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-file-text2 position-left"></i> Tipo Cambio <span class="text-danger">*</span>
					</label>
					<input type="text" value="" class="form-control form-control-sm new_producto_tipodecambio new_prod_inputs_selected" name="new_producto_tipodecambio" id="new_producto_tipodecambio" placeholder="Tipo Cambio"> 
				</div>
			</div>

			<div class="col-md-7 col-xs-7" id="content_categoria">
				<div class="form-group input-select-categoria">
					<label class="label-form">
						<i class="icon-users mr-2"></i> Categoría
					</label>
					<div class="input-group input-select2">
						<select name="nuevo_producto_categoria" class="select_with_search" id="nuevo_producto_categoria">
							<option selected value="">Seleccione una categoría</option>
						</select>
						<span class="input-group-btn ">
							<a class="btn bg-indigo legitRipple" id="btn_agregar_categoria" href="javascript:void(0)">
								<i class="icon-plus-circle2 mr-2"></i>
							</a>
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-5 col-xs-5" id="content_unidad_medida">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stairs-up position-left"></i> Unidad de Medida <span class="text-danger">*</span>
					</label>
					<select class="id_unidad_medida" name="nuevo_producto_unidad" id="nuevo_producto_unidad">
					</select>
				</div>
			</div>
			
			
			<div class="col-md-4 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Stock Inicial <span class="text-danger">*</span>
					</label>
					<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_stock" id="nuevo_producto_stock" placeholder="Stock Actual"> 
				</div>
			</div>
			<div class="col-md-4 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Stock Mínimo <span class="text-danger">*</span>
					</label>
					<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_stock_minimo" id="nuevo_producto_stock_minimo" placeholder="Stock Mínimo"> 
				</div>
			</div>

			<div class="col-md-4 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Prec.Compra (Inc.IGV)
					</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_precio_compra" id="nuevo_producto_precio_compra" placeholder="Precio de Compra">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" <?php if($precio_venta_minimo != 'si'){echo "style='display: none;'"; } ?>>
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Precio Mínimo Venta:
					</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_preciominimo" id="nuevo_producto_preciominimo" placeholder="Precio Mínimo de Venta">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" <?php if($precio_venta_minimo != 'si'){echo "style='display: none;'"; } ?>>
				<div class="form-group">
					<label class="label-form">
						<i class="icon-percent position-left"></i> Ganancia Mínima:
					</label>
					<div class="input-group" id="content_descuento_porcentaje_input">
						<span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
						<input type="text" value="0" title="Ingresa el Porcentaje de Ganancia Mínima" name="txt_porcentaje_ganancia" id="txt_porcentaje_ganancia" placeholder="Porcentaje Ganancia Mínima" class="form-control input_modify_totales txt_porcentaje_ganancia">
					</div>
				</div>
			</div>
		
			<div class="col-md-9 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-box-add"></i> Almacén: <span class="text-danger">*</span>
					</label>
					<select class="select select2" name="select_almacen" id="select_almacen">
					</select>
				</div>
			</div>
			<div class="col-md-3 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-bag position-left"></i> ICBPER
					</label>
					<div class="checkbox checkbox-switch">
						<label>
							<input name="nuevo_producto_afecto_icbper" id="nuevo_producto_afecto_icbper" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
						</label>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12 text-right">
				<div class="form-group">
					<button class="btn bg-indigo legitRipple btn_guardar_nuevo_producto" type="button">
						<i class="icon-floppy-disk mr-1"></i>
						Registrar Nuevo Producto
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="contenido_nueva_categoria" style="display: none;">
		<div class="col-md-12">
			<div class="col-md-4">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-barcode2 mr-2"></i> Código
					</label>
					<div class="input-group">
						<input type="text" name="codigo" id="txt_codigo_categoria" class="form-control" placeholder="Código">
						<span class="input-group-btn">
							<button class="btn bg-indigo legitRipple btn_generar_codigo_cate" type="button"> 
								<i class="icon-rotate-ccw3 mr-2 x"></i> 
							</button>
						</span>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="form-group">
					<label for="ruc" class="label-form">
						<i class="fa fa-caret-right mr-2"></i> Nombre de la categoría
					</label>
					<input type="text" class="form-control form-control-sm" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre de la categoría"> 
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="ruc" class="label-form">
						<i class="fa fa-caret-right mr-2"></i>
						Cod. Cuenta Contable
					</label>
					<input type="text" class="form-control form-control-sm" name="codigo_cuenta_contable" id="codigo_cuenta_contable" placeholder="Código de la Cuenta Contable"> 
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="ruc" class="label-form">
						<i class="fa fa-caret-right mr-2"></i>
						Cod. Centro Costo
					</label>
					<input type="text" class="form-control form-control-sm" name="codigo_centro_costo" id="codigo_centro_costo" placeholder="Código del Centro de Costo"> 
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="ruc" class="label-form">
						<i class="fa fa-caret-right mr-2"></i>
						Código Presupuesto
					</label>
					<input type="text" class="form-control form-control-sm" name="codigo_presupuesto" id="codigo_presupuesto" placeholder="Código Presupuesto"> 
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label for="ruc" class="label-form">
						<i class="fa fa-caret-right mr-2"></i> Descripción
					</label>
					<textarea name="descripcion_categoria" id="descripcion_categoria" cols="30" rows="3" maxlength="250" class="form-control"></textarea>
				</div>
			</div>

		</div>
		<div class="col-md-12 text-right">
			<div class="form-group">
				<button style="margin-right: 10px;" type="button" class="btn bg-danger legitRipple btn_cancelar_categoria">Cancelar</button>
				<button class="btn bg-success legitRipple btn_guardar_nueva_categoria" type="button">
					<i class="icon-floppy-disk mr-1"></i>  Guardar Categoría
				</button>
			</div>
		</div>
	</div>
</form>