<style>
	#tbl_lista_abonos_wrapper > .datatable-header {
		display: none;
	}
</style>
<!-- Lista Abonos -->
<div id="vm_lista_abonos" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="vm_lista_abonos_content">
			<div class="modal-header bg-indigo-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-cash3"></i> &nbsp; Lista de Abonos para: <strong id="numero_serie_doc_abonado"></strong></h6>
			</div>
	
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-primary alert-styled-left alert-bordered" id="mensaje_lista_abonos">
						</div>
					</div>

					
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table datatable-basic" id="tbl_lista_abonos">
								<thead>
									<tr>
										<th>Fecha Registro</th>
										<th>Monto</th>
										<th class="text-center">Acción</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!-- /Lista Abonos -->