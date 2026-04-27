<style>
	.eliminar_radio_input {
		border-top-left-radius: 0px !important;
		border-bottom-left-radius: 0px !important;
	}
</style>
<form action="#" id="frm_producto" method="get" accept-charset="utf-8">
	<input type="hidden" id="producto_idproducto" name="producto_idproducto" />
	<input type="hidden" id="id_cod_moneda" name="id_cod_moneda" />
	<input type="hidden" id="key_row" name="key_row" value="" />
	<input type="hidden" id="stock_actual" name="stock_actual" value="" />
		<div class="row">

			<div class="col-md-12 col-xs-12" >
				<label><i class="icon-cart-add position-left"></i>Aquí puedes buscar y seleccionar tu producto/Servicio!</label>
				<div class="text-muted text-size-small info_sucursal_seleccionada" style="margin-bottom: 5px;">
					
				</div>
				<div id="contenedor_select_productos" class="mb-20">
					<select name="select_producto_buscar" id="select_producto_buscar" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar">
						<option></option>
					</select>
					<div class="text-primary text-size-small" id="content_msg_percepcion" style="display:none; margin-top: 5px;">
						
					</div>
				</div>
				
			</div>

		</div>

		<div class="row content_propiedades_producto">
			<div class="form-group col-md-4 col-xs-6">
				<label for="producto_tipo_afect_igv"><i class="icon-percent position-left"></i>Afect. IGV</label>
				<select class="form-control" name="producto_tipo_afect_igv" id="producto_tipo_afect_igv">
					
				</select>
			</div>
			<div class="form-group col-md-3 col-xs-6">
				<label for="producto_codigo"><i class="icon-barcode2 position-left"></i>Código</label> 
				<input type="text" class="form-control" value="" name="producto_codigo" id="producto_codigo" disabled>
			</div>
			<div class="form-group col-md-3 col-xs-8">
				<label for="producto_unidadmedida"><i class="icon-stairs-up position-left"></i>Und/Medida</label>
				<select class="form-control" name="producto_unidadmedida" id="producto_unidadmedida">
					
				</select>
			</div>
			<div class="form-group col-md-2 col-xs-4">
				<label class="label-form" style="font-size: 11px;">
					<i class="icon-bag position-left"></i> ICBPER
				</label>
				<div class="checkbox checkbox-switch">
					<label>
						<input name="opcion_afecto_icbper" id="opcion_afecto_icbper" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
					</label>
				</div>
			</div>

			<div class="form-group col-md-12 col-xs-12">
				<label for="producto_descripcion"><i class="icon-file-text position-left"></i> Descripción <span id="msg_stock_actual_html"></span>:</label>
				<textarea rows="2" cols="1" name="producto_descripcion" id="producto_descripcion" class="custom-textarea" placeholder="Descripción del producto/servicio"></textarea>
			</div>

			<div class="form-group col-md-5 col-xs-12" id="content_preciounidad_inc_igv">
				<label for="producto_preciounidad">
						<i id="historial_costos" class="icon-question4 position-left" style="cursor: pointer;"></i>
					<span id="text_precio_inc_igv">Precio/Uni(Inc.IGV)</span>
				</label>
				<div class="input-group"> 
					<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
					<input class="form-control totales_input" type="text" value="" name="producto_preciounidad" id="producto_preciounidad" <?php if(isset($modificar_precio_en_pantalla_venta) && $modificar_precio_en_pantalla_venta == 'no') {echo 'disabled="disabled"';} ?>>
				
					<span class="input-group-btn" id="btn_listaprecios" style="display: none;">
						<button type="button" class="btn btn-primary btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false">
							<i class="icon-menu7"></i> &nbsp;<span class="caret"></span>
						</button>

						<ul class="dropdown-menu dropdown-menu-right" style="min-width: 300px;" id="contenido_listaprecios">
							
						</ul>
					</span>
				</div>
			</div>

			
			<div class="form-group col-md-4 col-xs-12" id="content_preciounidad_sin_igv">
				<label for="producto_preciounidad_sin_igv"><span id="text_precio_sin_igv">Precio/Uni(Sin.IGV)</span></label>
				<div class="input-group">
					<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
					<input class="form-control totales_input" type="text" value="" name="producto_preciounidad_sin_igv" id="producto_preciounidad_sin_igv" <?php if(isset($modificar_precio_en_pantalla_venta) && $modificar_precio_en_pantalla_venta == 'no') {echo 'disabled="disabled"';} ?>>
				</div>
			</div>
			<div class="form-group col-md-3 col-xs-6">
				<label for="producto_cantidad"><a href="javascript:void(0)" onclick="" class="ver_stock_varias_sucursales"> <i class="icon-stack position-left"></i>Cant. <span id="txt_unidad_medida_producto"></span> </a></label>
				<input class="form-control totales_input" type="text" value="1" name="producto_cantidad" id="producto_cantidad">
				<div id="msg_stock_insuficiente" class="text-danger text-size-small" style="margin-top: 7px; display: none;">
				
					<a href="javascript:void(0)" onclick="" class="ver_stock_varias_sucursales"><i class="icon-checkmark3 text-size-mini position-left"></i>Stock Insuficiente.</a>
					
				</div>
			</div>
			<div class="form-group col-md-4 col-xs-6">
				<label for="producto_subtotal">Sub.Total </label>
				<div class="input-group">
					<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
					<input type="text" class="form-control" value="" name="producto_subtotal" id="producto_subtotal" disabled="disabled">
				</div>
			</div>
			<div class="form-group col-md-4 col-xs-6">
				<label for="producto_igv">IGV <strong class="texto_facto_igv_sunat">(18%)</strong> </label>
				<div class="input-group">
					<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
					<input type="text" class="form-control" value="" name="producto_igv" id="producto_igv" disabled="disabled">
				</div>
			</div>
			<div class="form-group col-md-4 col-xs-6">
				<label for="producto_total">Total </label>
				<div class="input-group">
					<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
					<input type="text" class="form-control monto_total_producto" value="" name="producto_total" id="producto_total" <?php if(isset($modificar_precio_en_pantalla_venta) && $modificar_precio_en_pantalla_venta == 'no') {echo 'disabled="disabled"';} ?>>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4 text-left" style="padding: 0px 0px 0px 22px; display: none;">
				<h5 style="font-size: 11px; font-weight: 700; color: #2196F3; letter-spacing: 1px; text-align: left; text-transform: uppercase;  margin: 0px !important; padding: 0px !important;">Total Documento:</h5>
				<div style="font-size: 30px; color: #2196F3; text-align: left;">
					<span class="simbolo_moneda">S/. </span> <span id="total_documento_2">0.00</span>
				</div>
			</div>

			<div class="col-md-12 text-right col-xs-12">
				<button type="button" class="btn bg-indigo mx-1 font-weight-bold text-uppercase btn_agregarproducto_detalle"><i class="icon-floppy-disk mr-1"></i>Agregar a la Lista</button>
				<button type="button" class="btn btn-default  mx-1 font-weight-bold text-uppercase" data-dismiss="modal"><i class="icon-cross2 mr-1"></i>Cerrar</button> 
			</div>
		</div>
		
</form>