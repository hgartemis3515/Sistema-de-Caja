<div class="col-sm-6 col-md-6"  id="content_descuento_porcentaje">
	<div class="form-group">
		<div class="checkbox checkbox-switchery switchery-xs" style="margin-top: 1px !important;">
			<label>
				<input checked type="checkbox" name="opcion_tipo_descuento" id="opcion_tipo_descuento" class="switchery opcion_tipo_descuento" checked="checked">
				<span id="txt_titulo_opcion_descuento">Descuento en Porcentaje</span>
			</label>
		</div>

		<div class="input-group" id="content_descuento_total" style="display: none;">
			<span class="input-group-addon font-weight-bold simbolo_descuento">S/.</span>
			<input type="number" inputmode="numeric" pattern="[0-9]*" title="Ingresa el Porcentaje de Descuento" name="txt_descuento_total" id="txt_descuento_total" placeholder="Porcentaje de Descuento Total" class="form-control txt_descuento_total input_modify_totales">
		</div>

		<div class="input-group" id="content_descuento_porcentaje_input">
			<span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
			<input type="text" value="0.0" title="Ingresa el Porcentaje de Descuento" name="txt_descuento_porcentaje" id="txt_descuento_porcentaje" placeholder="Porcentaje de Descuento Total" class="form-control input_modify_totales txt_descuento_porcentaje">
		</div>
	</div>
</div>

<div class="col-sm-6 col-md-6" id="content_condicionpago_comprobante">
	<div class="form-group">
		<div class="has-feedback has-feedback-left">
			<label><i class="icon-cash2 position-left"></i>Condición de Pago: </label>
			<select title="Selecciona una condición de pago" data-placeholder="Selecciona una condición de pago" class="select condicionpago_comprobante" name="condicionpago_comprobante" id="condicionpago_comprobante">
				<?php
				foreach($lista_condiciones_pago as $condicionpago) {
					if($condicionpago->tipo == 'contado') {
						echo "<option data-tipocondicion='".$condicionpago->tipo."' value='".$condicionpago->id_condicionpago."' selected>".$condicionpago->condicionpago."</option>";
					} else {
						echo "<option data-tipocondicion='".$condicionpago->tipo."' value='".$condicionpago->id_condicionpago."'>".$condicionpago->condicionpago."</option>";
					}
				}
				?>
			</select>
		</div>
	</div>
</div>
<div class="col-lg-12 col-sm-12 col-md-12" id="content_numero_operacion">
	<div class="form-group">
		<label><i class="fa fa-hashtag position-left"></i> Número de Operación: </label>
		<input type="text" value="" title="Número de Operación" name="txt_numero_operacion" id="txt_numero_operacion" placeholder="Número de Operación" class="form-control txt_numero_operacion">
	</div>
</div>

<div id="content_fecha_pago">
	<div class="col-lg-6 col-sm-6 col-md-6">
		<label><i class="icon-calendar2 position-left"></i> Fecha de Pago:</label>
		<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_pago_comprobante" id="fecha_pago_comprobante" placeholder="" class="form-control control_fecha">
	</div>
	<div class="col-lg-6 col-sm-6 col-md-6">
		<div class="form-group">
			<label>Monto Adeudado en <span class="simbolo_moneda">S/.</span>: </label>
			<input type="text" value="" title="Monto Adeudado" name="txt_monto_adeudado" id="txt_monto_adeudado" placeholder="Monto Adeudado" class="form-control txt_monto_adeudado">
		</div>
	</div>
</div>


<div class="col-lg-12 col-sm-12 col-md-12" style="display:none;">
	<div class="form-group">
		<label><i class="icon-vcard position-left"></i> Otros Cargos <span class="simbolo_moneda">S/.</span>:</label>
		<input type="text" title="Ingresa otros montos" name="txt_otros_cargos_comprobante_input" id="txt_otros_cargos_comprobante_input" placeholder="Ingresa otros montos" class="form-control input_modify_totales txt_otros_cargos_comprobante_input" value="0.0">
	</div>
</div>
<div class="col-lg-12 col-sm-12 col-md-12">
	<div class="form-group">
		<h6><i class="icon-notebook position-left"></i> Observación:</h6>
		<div class="mb-15 mt-15">
			<textarea rows="3" cols="3" name="observacion_documento" class="custom-textarea" placeholder="Escribe aquí una observación"></textarea>
		</div>
	</div>
</div>