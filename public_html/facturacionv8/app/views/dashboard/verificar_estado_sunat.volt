<!-- verificar estado en sunat -->
<div id="vm_verificar_estado_sunat" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="content_verificar_estado_sunat">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <img src="/facturacionv8/img/sunat_logo.png" style="max-height: 19px;" /> &nbsp; Verificar Estado en SUNAT de <strong id="estado_num_doc_22"></strong></h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="vestado_id_contribuyente" id="vestado_id_contribuyente" value=""/>
				<input type="hidden" name="vestado_tipo_doc_electronico" id="vestado_tipo_doc_electronico" value=""/>
				<input type="hidden" name="vestado_serie_comprobante" id="vestado_serie_comprobante" value=""/>
				<input type="hidden" name="vestado_numero_comprobante" id="vestado_numero_comprobante" value=""/>
	
				<div class="row">
					<div class="col-md-12" id="contenido_respuesta_sunat">
					</div>
					<div class="col-md-12" id="contenedor_numero_ticket">
						<div class="form-group">
							<label class="label-form "><i class="icon-user mr-2"></i>Número de Ticket:</label>
							<div class="input-group">
								<input type="text" name="codigo" id="txt_numero_ticket" class="form-control" placeholder="Número de Ticket"><span class="input-group-btn"><button class="btn bg-indigo legitRipple btn_guardar_nuevo_ticket" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Ticket</button></span>
							</div>
						</div>
					</div>

					<div class="col-md-12" id="contenedor_logs_documento">
						<div class="alert alert-info alert-styled-left alert-bordered" id="info_logs_documento" style="margin-bottom: 4px;"></div>
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<div class="col-xs-4">
					<div class="btn-group">
						<button type="button" id="btn_recuperar_cdr" class="btn btn-success"> Get CDR</button>
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								Si el documento (Generalmente FACTURAS) se encuentra en Pendiente, pero ya está en SUNAT, entonces simplemente se debe recuperar el CDR utilizando esta opción.
							</li>
						</ul>
					</div>
				</div>
				
				<div class="col-xs-4">
					<div class="btn-group">
						<button type="button" id="btn_resetear_documento" class="btn btn-primary"> Reset. Doc.</button>
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								Utilizar esta opción cuando el documento NO SE ENCUENTRA EN SUNAT, sin embargo cuando se intenta reenviar aparece ERROR. Entonces Resetear el documento con esta opción.
							</li>
						</ul>
					</div>
				</div>
				
				<div class="col-xs-4">
					<div class="btn-group">
						<button type="button" id="btn_aprobar_manualmente" class="btn btn-danger">Apro. Manual</button>
						<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								La Aprobación Manual normalmente se utiliza con BOLETAS que ya fueron enviadas a SUNAT, sin embargo aún no se ha logrado recuperar el CDR, pues lo más probable es que SUNAT no haya retornado el número de ticket. Entonces se debe proceder con una aprobación manual. (CUIDADO: VERIFICAR QUE EL COMPROBANTE EXISTA EN SUNAT PARA HACER CLICK EN ESTE BOTÓN)
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /verificar estado en sunat -->