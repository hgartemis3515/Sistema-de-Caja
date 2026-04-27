
<div class="table-responsive">
	<table class="table datatable-basic" id="tbl_lista_movimientos">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Descripción</th>
				<th>Tipo</th>
				<th>Monto</th>
				<th class="text-center">Acción</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_movimientos" tabindex="-1" role="dialog" aria-labelledby="modal_movimientosTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<h5 class="modal-title" id="modal_movimientosTitle">Registrar movimiento</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" class="frm_registrar_movimiento" id="frm_registrar_movimiento">
					<input type="hidden" id="id_movimiento" name="id_movimiento" value="" />
					<div class="row">
						<div class="col-md-6" >
							<div class="radio">
								<label>
									<input type="radio" name="tipo_movimiento" class="control-danger" value="egreso"  id="radio_egreso" checked="checked">
									<strong class="text-danger">Egreso (-)</strong>
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="radio">
								<label>
									<input type="radio" value="ingreso" name="tipo_movimiento" class="control-success" id="radio_ingreso">
									<strong class="text-success">Ingreso (+)</strong>
								</label>
							</div>
						</div>
						<?php
						if($usuario->id_rol != 4) {
						?>
						<div class="col-md-4" style="margin-bottom: 10px;">
							<label class="label-form">
								<i class="icon-profile position-left"></i>
								Sucursal 
							</label>
							<select name="select_sucursal" id="select_sucursal" class="select2">
								<?php
								foreach($lista_sucursales as $sucursal) {
									echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
								}
								?>
							</select>
						</div>
						<div class="col-md-4" style="margin-bottom: 10px;">
							<label class="label-form">
								<i class="icon-users position-left"></i>
								Usuario
							</label>
							<select name="select_vendedor" id="select_vendedor" class="select2">
								<?php
								foreach($lista_usuarios as $usuario) {
									echo '<option value="'.$usuario->idusuario.'">'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
								}
								?>
							</select>
						</div>
						<div class="col-md-4">	
							<div class="form-group">
								<label class="label-form">
									<i class="icon-calendar2 position-left"></i>
									Fecha 
								</label>
								<input type="text" value="<?php echo date('d/m/Y H:i:s'); ?>" class="form-control form-control-sm control_fecha_movimiento" name="control_fecha_movimiento" id="control_fecha_movimiento">
								<input type="hidden" name="control_fecha_movimiento_valor" value="<?php echo date('Y-m-d H:i:s'); ?>" id="control_fecha_movimiento_valor" />
							</div>	
						</div>
						<?php
						} else {
						?>
						<div class="col-md-12" style="display: none;">	
							<div class="form-group">
								<label class="label-form">
									<i class="icon-calendar2 position-left"></i>
									Fecha de Registro del Movimiento:
								</label>
								<input type="text" value="<?php echo date('d/m/Y H:i:s'); ?>" class="form-control form-control-sm control_fecha_movimiento" name="control_fecha_movimiento" id="control_fecha_movimiento">
								<input type="hidden" name="control_fecha_movimiento_valor" value="<?php echo date('Y-m-d H:i:s'); ?>" id="control_fecha_movimiento_valor" />
							</div>	
						</div>
						<?php
						}
						?>
						
						<div class="col-md-12">	
							<div class="form-group">
								<label class="label-form">
									<i class="icon-file-text2 position-left"></i>
									Descripción / Concepto:
								</label>
								<input type="text" class="form-control form-control-sm" name="descripcion" id="descripcion" placeholder="Descripción">
							</div>	
						</div>

						<div class="form-group col-md-5" id="content_condicionpago_abono" <?php if(count($lista_condiciones_pago) <= 0) {echo ' style="display:none;" '; } ?>>
							<label for="producto_preciounidad_sin_igv">
								<span>Modalidad | Condición Pago: </span>
							</label>
							<select title="Selecciona una condición de pago" data-placeholder="Selecciona una condición de pago" class="select_minimizado condicionpago_abono" name="condicionpago_abono" id="condicionpago_abono">
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

						<div class="col-md-7">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-cash2 mr-2"></i>
									Monto
								</label>
								<div class="row">
									<div class="col-md-6">
										<select class="select_minimizado"  name="id_cod_moneda" id="id_cod_moneda">
											<option value="PEN">Soles (S/)</option>
											<option value="USD">Dólares Americanos ($)</option>
										</select>
									</div>
									<div class="col-md-6 sin-padding">
										<input type="number" class="form-control form-control-sm" name="monto" id="monto">
									</div>
								</div>
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
									<select title="Seleccionar Banco" data-placeholder="Seleccionar Banco" class="select_cuenta_banco_deposito select_minimizado" name="select_cuenta_banco_deposito" id="select_cuenta_banco_deposito">
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

						<div class="col-md-12">	
							<div class="form-group">
								<label class="label-form">
									<i class="icon-notebook position-left"></i>
									Detalle 
								</label>
								<textarea name="detalle_movimiento" id="detalle_movimiento" cols="30" rows="2" maxlength="250" class="form-control form-control-sm" ></textarea>
							</div>	
						</div>

						<div class="col-md-12">
							<legend class="text-bold">Responsable | Proveedor (Opcional): </legend>
						</div>

						<input type="hidden" name="id_proveedor_documento" id="id_proveedor_documento" class="id_proveedor_documento" value="" />
								
						<div class="form-group col-md-3 col-xs-6">
							<div class="has-feedback has-feedback-left">
								<label><i class="icon-user position-left"></i>Tipo</label>
								<select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="proveedor_tipo_docidentidad select_minimizado" name="proveedor_tipo_docidentidad" id="proveedor_tipo_docidentidad">
									<option value="0">OTRO DOCUMENTO (COD 0)</option>
									<option value="1" selected>D.N.I.</option>
									<option value="6">R.U.C.</option>
								</select>
							</div>
						</div>

						<div class="form-group col-md-4 col-xs-6" id="estado_numerodocumento">
							<label><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° de RUC</span>:</label>
							<div class="input-group">
								<input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="proveedor_numerodocumento" id="proveedor_numerodocumento" placeholder="Número de documento Aquí!" class="form-control proveedor_numerodocumento input_editable" value="" required>
								<span class="input-group-btn">
									<button class="btn bg-indigo btn-icon legitRipple buscar_proveedor" type="button">
										<i class="icon-search4" id="icon_search_document"></i>
										<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
									</button>
								</span>
							</div>
						</div>

						<div class="form-group col-md-5 col-xs-12" id="razonsocial_numerodocumento">
							<label><i class="icon-vcard position-left"></i> <span id="proveedor_titulodocumento">Razón Social</span>:</label>
							<input type="text" title="Ingresa la Razón Social o Nombre" name="proveedor_nombre" id="proveedor_nombre" placeholder="Nombre o Razón Social Aquí" class="form-control proveedor_nombre"  required>
						</div>
						

						<div class="col-md-6">
							<div class="radio">
								<label>
									<input type="radio" name="comprobante" class="control-primary control-comprobante" value="1" checked="checked">
									Sin comprobante
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="radio">
								<label>
									<input type="radio" value="2" name="comprobante" class="control-danger control-comprobante" >
									Con comprobante
								</label>
							</div>
						</div>
					</div>
					<div class="row" id="content_client_movimiento">
						<div class="col-md-12" style="margin-top: 20px;">	
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-profile position-left"></i> 
												Tipo de comprobante
											</label>
											<select class="select_minimizado" name="select_tipo_comprobante" id="select_tipo_comprobante">
												<option value="" selected>Elige un tipo de comprobante</option>
												<option value="03">BOLETA</option>
												<option value="01">FACTURA</option>
												<option value="77">NOTA DE VENTA</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<label class="label-form"><i class="icon-file-text2 position-left"></i> Serie - Número:</label>
										<input type="text" class="form-control form-control-sm" name="num_comprobante" id="num_comprobante">
									</div>
								</div>
							</div>	
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn bg-indigo btn_save_registro"><i class="icon-floppy-disk mr-2"></i> Guardar registro</button>
			</div>
		</div>
	</div>
</div>

