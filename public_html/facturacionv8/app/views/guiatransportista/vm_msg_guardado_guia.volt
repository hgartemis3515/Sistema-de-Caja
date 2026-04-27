<style>
	.pdf_preview { height: 32rem; border: 1rem solid rgba(0,0,0,.1); }
</style>
<!-- Modal opciones avanzadas -->
<div id="modal_msg_guardado" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title" id="titulo_msg_guardado"></h5>
			</div>
            <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success alert-bordered" id="mensaje_msg_guardado">
							
						</div>
					</div>
					<div class="col-md-12 btn-options" style="text-align: right; padding-bottom: 4px;">
						<a href="#" target="_blank" id="enlace_a4_msg_guardado" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 25px;"><span>A4</span></a>
						<a href="#" target="_blank" id="enlace_ticket_msg_guardado" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 25px;"><span>Ticket</span></a>
					</div>
					<div class="col-md-12" id="content_pdf_preview_ticket">
						
					</div>
					<div class="col-md-12" style="display: none;" id="content_pdf_preview_a4">
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="/facturacionv8/dashboard" type="button" class="btn bg-success">Lista de Comprobantes</a>
                <button type="button" id="new_document_msg_guardado" class="btn bg-indigo">Crear Nuevo Documento</button>
            </div>
        </div>
    </div>
</div>
<!-- /Modal opciones avanzadas -->