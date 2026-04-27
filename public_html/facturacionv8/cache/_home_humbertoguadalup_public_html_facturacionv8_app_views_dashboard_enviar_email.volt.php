<!-- Enviar Email al Cliente -->
<div id="vm_enviar_email" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="content_enviar_email_vm">
			<div class="modal-header bg-success-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Enviar CPE el Correo del Cliente</h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="email_idcontribuyente" id="email_idcontribuyente" value=""/>
				<input type="hidden" name="email_tipo_doc" id="email_tipo_doc" value=""/>
				<input type="hidden" name="email_serie_doc" id="email_serie_doc" value=""/>
				<input type="hidden" name="email_numero_comprobante" id="email_numero_comprobante" value=""/>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="label-form"><i class="icon-envelop mr-2"></i>Ingresa el Email del Cliente:</label>
							<input type="email" class="form-control form-control-sm" name="email_cliente" id="email_cliente" placeholder="Email">
						</div>
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_enviar_email" class="btn btn-info">Enviar Mensaje!</button>
			</div>
		</div>
	</div>
</div>
<!-- /Enviar Email al Cliente -->