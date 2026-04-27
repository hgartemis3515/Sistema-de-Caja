<div class="form-group col-md-4">
	<label><i class="icon-question4 position-left"></i> ¿Aplica Percepción?</label>
	<div class="checkbox checkbox-switch">
		<label>
			<input name="opcion_afecto_percepcion" id="opcion_afecto_percepcion" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
		</label>
	</div>
</div>

<div class="form-group col-md-8 data_no_modificable_notas">
	<div class="has-feedback has-feedback-left">
		<label><i class="icon-user position-left"></i>Tipo de Percepción <span class="text-danger">*</span> :</label>
		<select title="Selecciona el Tipo de Percepcción" data-placeholder="Selecciona el Tipo de Percepcción" class="tipo_percepcion select" name="tipo_percepcion" id="tipo_percepcion">
			<?php
			foreach($tipo_percepcion as $percepcion) {
				echo '<option value="'.$percepcion->codigo.'">'.$percepcion->descripcion.'</option>';
			}
			?>
		</select>
	</div>
</div>