<style>
.ui-grid-ico-sort {
	display:none !important;
}
</style>
<form action="#" id="frm_producto" method="get" accept-charset="utf-8">
	<input type="hidden" id="producto_idproducto" name="producto_idproducto" />
	<input type="hidden" id="id_cod_moneda" name="id_cod_moneda" />
	<input type="hidden" id="key_row" name="key_row" value="" />
	<input type="hidden" id="producto_descripcion2" name="producto_descripcion2" />

	<input type="hidden" id="pventa_moneda_original" name="pventa_moneda_original" />
	<input type="hidden" id="prod_tiene_presentaciones" name="prod_tiene_presentaciones" />
	<input type="hidden" id="prod_tiene_multiprecio" name="prod_tiene_multiprecio" />

	<div class="row">
		<div class="col-md-12">
			<label><i class="icon-cart-add position-left"></i>Aquí puedes buscar y seleccionar tu producto/Servicio!</label>
			<div class="has-feedback has-feedback-left" id="contenedor_select_productos">
				<select name="select_producto_buscar" id="select_producto_buscar" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar">
					<option></option>
				</select>
			</div>
		</div>
	</div>
		
	<div class="row content_propiedades_producto" style="margin-top: 15px;">
		<div class="col-md-12 col-xs-12 mt-5">
			<label for="producto_descripcion"><i class="icon-file-text position-left"></i> Descripción <span id="msg_stock_actual_html"></span>:</label>
			<textarea rows="2" cols="1" name="producto_descripcion" id="producto_descripcion" class="custom-textarea" placeholder="Descripción del producto/servicio"></textarea>
		</div>
		
		<div class="col-md-4 col-xs-6 mt-10">
			<label for="producto_tipo_afect_igv"><i class="icon-percent position-left"></i>Afect. IGV</label>
			<select class="form-control valid" name="producto_tipo_afect_igv" id="producto_tipo_afect_igv">
				
			</select>
		</div>
		<div class="col-md-3 col-xs-6 mt-10">
			<label for="producto_codigo"><i class="icon-barcode2 position-left"></i>Codigo</label> 
			<input type="text" class="form-control" value="" name="producto_codigo" id="producto_codigo" disabled>
		</div>
		<div class="col-md-3 col-xs-6 mt-10">
			<label for="producto_unidadmedida"><i class="icon-stairs-up position-left"></i>Und/Medida</label>
			<select class="form-control valid" name="producto_unidadmedida" id="producto_unidadmedida">
				
			</select>
		</div>
		<div class="col-md-2 col-xs-6 mt-10" style="margin-bottom: 5px;">
			<label class="label-form">
				<i class="icon-bag position-left"></i> ICBPER
			</label>
			<div class="checkbox checkbox-switch">
				<label>
					<input name="opcion_afecto_icbper" id="opcion_afecto_icbper" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
				</label>
			</div>
		</div>

		<div class="col-md-4 col-xs-6 mt-10" id="content_preciounidad_inc_igv">
			<label for="producto_preciounidad"><span id="text_precio_inc_igv">Costo Uni. (Inc.IGV)</span></label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input class="form-control totales_input" type="text" value="" name="producto_preciounidad" id="producto_preciounidad">
			</div>
		</div>
		<div class="col-md-4 col-xs-6 mt-10" id="content_preciounidad_sin_igv">
			<label for="producto_preciounidad_sin_igv"><span id="text_precio_sin_igv">Costo Uni. (Sin.IGV)</span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input class="form-control totales_input" type="text" value="" name="producto_preciounidad_sin_igv" id="producto_preciounidad_sin_igv">
			</div>
		</div>
		<div class="col-md-4 col-xs-6 mt-10">
			<label for="producto_cantidad"><i class="icon-stack position-left"></i>Cantidad</label> 
			<input class="form-control totales_input" type="text" value="1" name="producto_cantidad" id="producto_cantidad">
		</div>
		<div class="col-md-4 col-xs-6 mt-10">
			<label for="producto_subtotal">Sub.Total </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input type="text" class="form-control" value="" name="producto_subtotal" id="producto_subtotal" disabled="disabled">
			</div>
		</div>
		<div class="col-md-4 col-xs-6 mt-10">
			<label for="producto_igv">IGV (18%) </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input type="text" class="form-control" value="" name="producto_igv" id="producto_igv" disabled="disabled">
			</div>
		</div>
		<div class="col-md-4 col-xs-6 mt-10">
			<label for="producto_total">Total </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input type="text" class="form-control" value="" name="producto_total" id="producto_total">
			</div>
		</div>


		<div class="col-md-12 col-xs-12 mt-10" id="content_opcion_actualizar_pventa">
			<div class="form-group" style="margin-bottom: 7px !important;">
				<div class="checkbox checkbox-switch">
					<label class="label-form">
						¿Deseas Actualizar el Precio de Venta para este Producto?<br />
						<input name="opcion_actualizar_pventa" id="opcion_actualizar_pventa" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
					</label>
				</div>
			</div>
		</div>
	</div>

	<!-- INICIO CAMPOS PARA ACTUALIZAR EL PRECIO DE VENTA -->
	<div class="row ml-5 mr-5 content_actualizacion_precios_venta" style="display:none;">
		<div class="col-md-4 col-xs-4 mt-10">
			<label for="producto_precio_venta_actual"><span id="text_producto_precio_venta_actual">P.Venta Actual</span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input class="form-control totales_input" type="text" value="" name="producto_precio_venta_actual" id="producto_precio_venta_actual" disabled>
			</div>
		</div>

		<div class="col-md-4 col-xs-4 mt-10">
			<label for="porcentaje_ganancia_maxima"><span id="text_porcentaje_ganancia_maxima">Precio Compra: <span class="simbolo_moneda">S/.</span> <strong id="base_porcentaje_ganancia_maxima"></strong></span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold">%</span>
				<input class="form-control totales_input" type="text" value="" name="porcentaje_ganancia_maxima" id="porcentaje_ganancia_maxima">
			</div>
		</div>

		<div class="col-md-4 col-xs-4 mt-10">
			<label for="producto_nuevo_precio_venta"><span id="text_producto_nuevo_precio_venta">Nuevo P.Venta</span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input class="form-control totales_input" type="text" value="" name="producto_nuevo_precio_venta" id="producto_nuevo_precio_venta">
			</div>
		</div>



		<div class="col-md-4 col-xs-4 mt-10">
			<label for="producto_precio_venta_minimo"><span id="text_producto_precio_venta_minimo">P.Venta Mínimo</span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input class="form-control totales_input" type="text" value="" name="producto_precio_venta_minimo" id="producto_precio_venta_minimo" disabled>
			</div>
		</div>

		<div class="col-md-4 col-xs-4 mt-10">
			<label for="porcentaje_ganancia_minima"><span id="text_porcentaje_ganancia_minima">Precio Compra: <span class="simbolo_moneda">S/.</span> <strong id="base_porcentaje_ganancia_minima"></strong></span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold">%</span>
				<input class="form-control totales_input" type="text" value="" name="porcentaje_ganancia_minima" id="porcentaje_ganancia_minima">
			</div>
		</div>

		<div class="col-md-4 col-xs-4 mt-10">
			<label for="producto_nuevo_precio_minimo"><span id="text_producto_nuevo_precio_minimo">Nuevo P.V.Mínimo</span> </label>
			<div class="input-group">
				<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
				<input class="form-control totales_input" type="text" value="" name="producto_nuevo_precio_minimo" id="producto_nuevo_precio_minimo">
			</div>
		</div>

		<div class="col-md-12 col-xs-12 mt-10">
			<div class="alert alert-danger alert-styled-left content-group" id="msg_cambio_precio_venta">
			
			</div>
		</div>
	</div>
	<!-- FIN CAMPOS PARA ACTUALIZAR EL PRECIO DE VENTA -->
	
	<div class="row content_propiedades_producto" style="display: none;">
		<div class="col-md-4 text-left" style="padding: 0px 0px 0px 22px;">
			<div style="display: none;">
				<h5 style="font-size: 11px; font-weight: 700; color: #2196F3; letter-spacing: 1px; text-align: left; text-transform: uppercase;  margin: 0px !important; padding: 0px !important;">Total Documento:</h5>
				<div style="font-size: 30px; color: #2196F3; text-align: left;">
					<span class="simbolo_moneda">S/. </span> <span id="total_documento_2">0.00</span>
				</div>
			</div>
		</div>
		<div class="col-md-8 text-right">
			<button type="button" class="btn bg-indigo mx-1 font-weight-bold text-uppercase btn_agregarproducto_detalle"><i class="icon-floppy-disk mr-1"></i>Agregar a la Lista</button>
			<button type="button" class="btn btn-default  mx-1 font-weight-bold text-uppercase" data-dismiss="modal"><i class="icon-cross2 mr-1"></i>Cerrar</button> 
		</div>
	</div>
	
</form>