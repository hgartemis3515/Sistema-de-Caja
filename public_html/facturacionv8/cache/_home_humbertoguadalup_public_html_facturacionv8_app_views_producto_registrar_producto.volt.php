<style>
.editable {
	border-bottom: 1px dotted #03A9F4 !important;
	display: inline-block !important;
	line-height: 1.45 !important;
	text-decoration: none !important;
}
.input-select-categoria .select2-selection--single {
	display: inline-grid;
	width: 100%;
}
</style>
<form name="frm_nuevo_producto" id="frm_nuevo_producto" action="">
	<div class="row" id="contenido_nuevo_producto" style="margin-left: 7px; margin-right: 7px;">
		<div class="row">
			<input type="hidden" class="form-control form-control-sm" name="idproducto_newprod" id="idproducto_newprod">
			
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

			<div class="col-lg-4 col-md-6 col-xs-12">
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
			
			<div class="col-lg-8 col-md-6 col-xs-12">
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

			<div class="col-md-3 col-xs-6" id="content_categoria">
				<div class="form-group input-select-categoria">
					<label class="label-form">
						<i class="icon-users mr-2"></i> Categoría
					</label>
					<div class="input-group input-select2">
						<select name="nuevo_producto_categoria" class="nuevo_producto_categoria" id="nuevo_producto_categoria">
							<option selected value="">Seleccione una categoría</option>
						</select>
						<span class="input-group-btn">
							<a class="btn bg-indigo legitRipple" id="btn_agregar_categoria" href="javascript:void(0)">
								<i class="icon-plus-circle2 mr-2"></i>
							
							</a>
						</span>
					</div>
				</div>
			</div>

			<div class="col-md-3 col-xs-6" id="content_unidad_medida">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stairs-up position-left"></i> Unidad de Medida <span class="text-danger">*</span>
					</label>
					<select class="id_unidad_medida" name="nuevo_producto_unidad" id="nuevo_producto_unidad">
					</select>
				</div>
			</div>

			<div class="col-md-3 col-xs-6" id="content_tipo_cambio" style="display: none;">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-file-text2 position-left"></i> Tipo Cambio <span class="text-danger">*</span>
					</label>
					<input type="text" value="" class="form-control form-control-sm new_producto_tipodecambio new_prod_inputs_selected" name="new_producto_tipodecambio" id="new_producto_tipodecambio" placeholder="Tipo Cambio"> 
				</div>
			</div>

			<div class="col-md-4 col-xs-6" id="content_precio_compra">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Precio Compra: 
					</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_precio_compra" id="nuevo_producto_precio_compra" placeholder="Precio de Compra">
					</div>
				</div>
			</div>	

			<div class="col-md-4 col-xs-6" id="content_ganancia_maxima">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-percent position-left"></i> % Ganancia Máxima:
					</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
						<input type="text" value="0" title="Tomando como base el precio de compra" name="txt_porcentaje_ganancia_maxima" id="txt_porcentaje_ganancia_maxima" placeholder="En Base al Precio de Compra" class="form-control input_modify_totales txt_porcentaje_ganancia_maxima new_prod_inputs_selected">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" id="content_ganancia_minima">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-percent position-left"></i> % Ganancia Mínima:
					</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
						<input type="text" value="0" title="Tomando como base el precio de compra" name="txt_porcentaje_ganancia" id="txt_porcentaje_ganancia" placeholder="En Base al Precio de Compra" class="form-control txt_porcentaje_ganancia new_prod_inputs_selected">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" id="content_preciounidad_inc_igv">
				<div class="form-group">
					<label for="nuevo_producto_valor_con_igv" class="label-form" id="text_precio_inc_igv">Precio Venta (Inc.IGV)</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_valor_con_igv" id="nuevo_producto_valor_con_igv" placeholder="Valor Unitario (Con IGV)">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" id="content_preciounidad_sin_igv">
				<div class="form-group">
					<label for="producto_total" class="label-form">Precio Venta (Sin IGV)</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_valor_sin_igv" id="nuevo_producto_valor_sin_igv" placeholder="Valor Unitario (Sin IGV)">
					</div>
				</div>
			</div>
			
			<div class="col-md-4 col-xs-6">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Precio Venta Mínimo:
					</label>
					<div class="input-group">
						<span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
						<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_preciominimo" id="nuevo_producto_preciominimo" placeholder="Precio Mínimo de Venta">
					</div>
				</div>
			</div>
			

			
			
			<div class="col-md-3 col-xs-6 campos_no_editables campos_para_producto">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Stock Inicial <span class="text-danger">*</span>
					</label>
					<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_stock" id="nuevo_producto_stock" placeholder="Stock Actual"> 
				</div>
			</div>

			<div class="col-md-3 col-xs-6 campos_para_producto">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Stock Mínimo <span class="text-danger">*</span>
					</label>
					<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_stock_minimo" id="nuevo_producto_stock_minimo" placeholder="Stock Mínimo"> 
				</div>
			</div>

			<div class="col-md-3 col-xs-6 campos_para_producto">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-stack position-left"></i> Peso (KGM)
					</label>
					<input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_peso" id="nuevo_producto_peso" placeholder="Peso en KGM"> 
				</div>
			</div>

			<div class="col-md-3 col-xs-6" <?php if($contribuyente->ver_fecha_vencimiento != 'si'){echo "style='display:none;'"; } ?>>
				<div class="form-group">
					<label class="label-form">
						<i class="icon-calendar2 position-left"></i> Fecha Vencimiento:
					</label>
					<input type="text" value="<?php echo date('d/m/Y'); ?>" title="Ingresa la Fecha de Vencimiento" name="txt_fecha_vencimiento" id="txt_fecha_vencimiento" placeholder="Fecha Vencimiento" class="form-control txt_fecha_vencimiento control_fecha">
				</div>
			</div>

			<div class="col-md-3 col-xs-6" <?php if($contribuyente->ver_marca != 'si'){echo "style='display:none;'"; } ?>>
				<div class="form-group">
					<label class="label-form">
						<i class="icon-price-tags2 position-left"></i> Marca del Producto:
					</label>
					<input type="text" value="" title="Ingresa la Marca del Producto" name="txt_marca_producto" id="txt_marca_producto" placeholder="Marca Producto" class="form-control txt_marca_producto new_prod_inputs_selected">
				</div>
			</div>
			
			<div class="col-md-3 col-xs-6" id="content_icbper">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-bag position-left"></i> ¿Es Afecto al ICBPER?
					</label>
					<div class="checkbox checkbox-switch">
						<label>
							<input name="opcion_afecto_icbper" id="opcion_afecto_icbper" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
						</label>
					</div>
				</div>
			</div>

			<div class="col-md-3 col-xs-6" id="content_icbper">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-bag position-left"></i> ¿Tiene Detracción?
					</label>
					<div class="checkbox checkbox-switch">
						<label>
							<input name="opcion_tienedetraccion" id="opcion_tienedetraccion" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
						</label>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-6" id="content_icbper">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-bag position-left"></i> ¿Utilizar MultiPrecio?
					</label>
					<div class="checkbox checkbox-switch">
						<label>
							<input name="opcion_multiprecio" id="opcion_multiprecio" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
						</label>
					</div>
				</div>
			</div>

			<div class="col-md-12 col-xs-12" id="content_listacodigo_detraccion" style="display: none; margin-bottom: 20px;">
				<div class="has-feedback has-feedback-left">
					<label><i class="icon-basket position-left"></i>Código del Bien <span class="text-danger">*</span> :</label>
					<select title="Selecciona el Código del Bien" data-placeholder="Selecciona el Código del Bien" class="detraccion_codigo_bien select" name="detraccion_codigo_bien" id="detraccion_codigo_bien">
						<?php
						foreach($bienes_detracciones as $biendetraccion) {
							echo '<option data-porcentaje="'.($biendetraccion->porcentaje + 0).'" value="'.$biendetraccion->id_cod_detraccion.'">'.$biendetraccion->descripcion.' ('.$biendetraccion->porcentaje.')</option>';
						}
						?>
					</select>
				</div>
			</div>

			<div class="col-md-12 col-xs-12">
				<div class="form-group">
					<label class="label-form">
						<i class="icon-file-text position-left"></i> Detalle/Nota:
					</label>
					<input type="text" class="form-control form-control-sm" name="nota_producto" id="nota_producto" placeholder="Nota o Detalle"> 
				</div>
			</div>


			<div class="col-md-12 col-xs-12" id="content_lista_precios" style="display: none; margin-bottom: 20px;">
				<div class="text-right" style="margin-bottom: 5px;">
					<a href="javascript:void(0);" class="label label-success add-row"><i class="icon-plus-circle2"></i> Agregar</a>
				</div>

				<div>
					<table class="table" id="lista_precios">
						<thead id="lista_precios_head_1">
							<tr class="bg-blue">
								<!-- IMPORTANTE: NO CAMBIAR LOS NOMBRES -->
								<th style="width: 50%;">Nombre</th>
								<th style="width: 30%">Precio</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody id="lista_precios_items" counter-id="1">
							
						</tbody>
					</table>
				</div>	
			</div>

			<div class="col-md-12 col-xs-12" style="display: none;">
				<div class="alert alert-info alert-styled-left alert-bordered info_texto_almacen">
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-right">
				<div class="form-group">
					<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
					<button class="btn bg-indigo legitRipple btn_guardar_nuevo_producto" type="button">
						<i class="icon-floppy-disk mr-1"></i>  Guardar
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
						<i class="icon-users mr-2"></i>
						Código
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

			<div class="col-md-4">
				<div class="form-group">
					<label for="ruc" class="label-form">
						<i class="fa fa-caret-right mr-2"></i>
						Nombre de la categoría
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
						<i class="fa fa-caret-right mr-2"></i>
						Descripción
					</label>
					<textarea name="descripcion_categoria" id="descripcion_categoria" cols="30" rows="3" maxlength="250" class="form-control"></textarea>
				</div>
			</div>

		</div>
		<div class="col-md-12 text-right">
			<div class="form-group">
				<button style="margin-right: 10px;" type="button" class="btn btn-default legitRipple btn_cancelar_categoria">Cancelar</button>
				<button class="btn bg-success legitRipple btn_guardar_nueva_categoria" type="button">
					<i class="icon-floppy-disk mr-1"></i>  Guardar Categoría
				</button>
			</div>
		</div>
	</div>
</form>