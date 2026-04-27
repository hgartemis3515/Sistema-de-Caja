<style>
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
</style>
<!-- abonos - cobros -->
<div id="vm_modificar_condicionpago" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="vm_modificar_condicionpago_content">
			<div class="modal-header bg-indigo-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-cash3"></i> &nbsp; Registrar Abono: </h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="vm_condicionpago_idcontribuyente" id="vm_condicionpago_idcontribuyente" value=""/>
				<input type="hidden" name="vm_condicionpago_tipo_doc" id="vm_condicionpago_tipo_doc" value=""/>
				<input type="hidden" name="vm_condicionpago_serie_doc" id="vm_condicionpago_serie_doc" value=""/>
				<input type="hidden" name="vm_condicionpago_numero_comprobante" id="vm_condicionpago_numero_comprobante" value=""/>
				<input type="hidden" name="vm_condicionpago_id_moneda" id="vm_condicionpago_id_moneda" value=""/>

				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-styled-left alert-bordered" id="mensaje_condicionpago">
						</div>
					</div>

					<div class="form-group col-md-4" id="content_condicionpago_abono" <?php if(count($lista_condiciones_pago) <= 0) {echo ' style="display:none;" '; } ?>>
						<label for="condicionpago_abono">
							<span>Condición de Pago: </span>
						</label>
						<select title="Selecciona una condición de pago" data-placeholder="Selecciona una condición de pago" class="select condicionpago_abono" name="condicionpago_abono" id="condicionpago_abono">
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


					<div  <?php if(count($lista_condiciones_pago) <= 0) {echo ' class="form-group col-md-6" '; } else { echo ' class="form-group col-md-4" '; } ?> id="content_preciounidad_sin_igv">
						<label for="monto_a_pagar">
							<span>Monto a Pagar: </span> 
							<span class="simbolo_moneda">S/.</span>
						</label>
						<div class="input-group">
							<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
							<input class="form-control monto_a_pagar" type="text" value="" name="monto_a_pagar" id="monto_a_pagar" />
						</div>
					</div>

					<div <?php if(count($lista_condiciones_pago) <= 0) {echo ' class="form-group col-md-6" '; } else { echo ' class="form-group col-md-4" '; } ?> id="content_preciounidad_sin_igv">
						<label for="monto_adeudado">
							<span>Monto Adeudado: </span> 
							<span class="simbolo_moneda">S/.</span>
						</label>
						<div class="input-group">
							<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
							<input class="form-control monto_adeudado" type="text" value="" name="monto_adeudado" id="monto_adeudado" disabled />
						</div>
					</div>

					<div class="col-lg-12 col-sm-12 col-md-12" id="content_numero_operacion">
						<div class="form-group">
							<label><i class="fa fa-hashtag position-left"></i> Número de Operación: </label>
							<input type="text" value="" title="Número de Operación" name="txt_numero_operacion" id="txt_numero_operacion" placeholder="Número de Operación" class="form-control txt_numero_operacion">
						</div>
					</div>

					<div class="col-lg-4 col-sm-4 col-md-4" id="content_fecha_deposito">
						<div class="form-group">
							<label class="label-form"><i class="fa fa-calendar-check-o position-left"></i> Fecha Depósito: </label>
							<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_deposito" id="fecha_deposito" placeholder="" class="form-control control_fecha">
						</div>
					</div>	

					<div class="col-sm-4 col-md-4" id="content_cuenta_banco_deposito">
						<div class="form-group">
							<div class="has-feedback has-feedback-left">
								<label class="label-form"><i class="fa fa-bank position-left"></i>Banco: </label>
								<select title="Seleccionar Banco" data-placeholder="Seleccionar Banco" class="select_cuenta_banco_deposito select" name="select_cuenta_banco_deposito" id="select_cuenta_banco_deposito">
									<option value="0">Selecc. Banco</option>
									<?php
									foreach($cuentas_banco as $banco) {
										echo '<option value="'.$banco->id_cuentabanco.'">'.$banco->nro_cuenta.' - '.$banco->nombre_banco.'</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group col-md-12" id="fecha_proximo_pago" style="display: none;">
						<label><i class="icon-calendar2 position-left"></i> Fecha del Próximo Pago:</label>
						<input type="text" value="<?php echo date('d/m/Y'); ?>" name="nueva_fecha_pago" id="nueva_fecha_pago" placeholder="" class="form-control control_fecha">
					</div>

					<div class="col-lg-12 col-sm-12 col-md-12">
						<div class="form-group">
							<h6><i class="icon-notebook position-left"></i> Observación:</h6>
							<div class="mb-15 mt-15">
								<textarea rows="3" cols="3" id="txt_observacion_abono" name="txt_observacion_abono" class="custom-textarea" placeholder="Escribe aquí una observación"></textarea>
							</div>
						</div>
					</div>

					<div class="col-md-12" id="content_resultado" style="display: none;">
					</div>
					
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_guardar_condicion" class="btn btn-info">Guardar!</button>
			</div>
		</div>
	</div>
</div>
<!-- /abonos - cobros -->