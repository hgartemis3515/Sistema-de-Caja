<style>
/* Modal intro */
.bt-buttons button:first-child{
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
	padding: 10px 15px;
}
.bt-buttons button:last-child{
	border-top-left-radius: 0;
	border-bottom-left-radius: 0;
	padding: 10px 15px;
}
.modal-intro-content {
    position: relative;
    z-index: 1;
}
.modal-intro-content::before {
    content: "\eb49";
    font-family: icomoon!important;
    position: absolute;
    left: 0;
    top: -.7em;
    opacity: .4;
    font-size: 70px;
    color: #ddd;
    z-index: -1;
}
.modal-intro-content::after{
	content: "\eb4a";
	font-family: icomoon!important;
	position: absolute;
	right: 0;
	bottom: -.2em;
	opacity: .4;
	font-size: 70px;
	color: #ddd;
}
.line-intro-modal {
    width: 100px;
    opacity: .5;
}
.heading-elements{
    position: initial;
    margin-top: 1em;
}
</style>
<!-- Large modal -->
<input type="hidden" value="<?php echo $contribuyente->mostrar_aviso; ?>" name="var_mostrar_aviso" id="var_mostrar_aviso">
<input type="hidden" value="<?php echo $rol; ?>" name="var_tipo_usuario" id="var_tipo_usuario">
<div id="vm_modal_aviso" class="modal fade">
	<div class="modal-dialog">
		<form id="form_modal_aviso" action="">
			<div class="modal-content" id="contenido_modal_aviso">
				<div class="modal-header  bg-indigo">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<span class="text-uppercase">Cambios Abril 2021 - Sunat</span> 
				</div>

				<div class="modal-body text-center">
					<div class="row">
						<div class="col-lg-6">
							<img src="/facturacionv8/img/women_dashboard.png" alt="" width="250px">
						</div>
						<div class="col-lg-6">
							<img src="<?php echo $data_empresa['logo_img_461']; ?>" alt="" id="navbar-logo">
							<hr class="line-intro-modal">
							<div class="modal-intro-content">
								<h5>¿Deseas Informar a SUNAT si una FACTURA es al crédito?</h5>
								<div class="heading-elements mb-2">
									<div class="checkbox checkbox-switch">
										<label>
											<input type="checkbox" value="si" name="informar_condpago_sunat" id="informar_condpago_sunat" data-on-color="primary" data-off-color="success" data-on-text="SI" data-off-text="NO" class="switch" <?php if($contribuyente->informar_condpago_sunat == 'si'){echo 'checked';} ?>>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="alert alert-primary alert-styled-left mt-2">
								<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
								<p class="text-left">Según decreto de urgencia 013-2020 publicada el 23 de Enero del 2020, y resolución de superintendencia N° 193-2020/SUNAT, publicada el 07 de noviembre del 2020: Indica que debemos indicar cuál es la forma de pago, lo que favorece el factoring, si tu no utilizas el factoring puedes manejar todos tus créditos de forma interna.</p>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="">
								<label>
									<input type="checkbox" id="opt_mostrar_modal_aviso" name="opt_mostrar_modal_aviso" class="control-primary opt_mostrar_modal_aviso">
									<span style="margin-left: 10px;"> NO Volver a Mostrar Este Aviso </span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" id="btn_guardar_condpago_sunat" class="btn btn-primary">Guardar Configuración</button>
				</div>
			</div>
		</form>
	</div>
</div>