<!-- vm_importar_cpe -->
<div id="vm_importar_cpe" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-plus-circle2"></i> &nbsp; Carga Masiva de CPE (FACTURAS y BOLETAS)</h6>
			</div>
			<div class="modal-body" id="content_vm_importar_cpe">
				<form name="frm_importar_cpe" id="frm_importar_cpe" action="#" method="post" enctype="multipart/form-data"> 
					<div class="panel panel-body">
						<div class="media no-margin stack-media-on-mobile">
							<div class="media-left media-middle">
								<i class="fa fa-cloud-upload fa-2x text-muted no-edge-top"></i>
							</div>

							<div class="media-body">
								<h6 class="media-heading text-semibold">Descarga nuestra plantilla en excel!</h6>
								<span class="text-muted">Para poder importar una lista de Comprobantes Electrónicos (solo Facturas y/o Boletas) debes descargar la plantilla y enviar los datos en el mismo formato.</span>
							</div>

							<div class="media-right media-middle">
								<a href="/facturacionv8/importacioncpe/plantilla_importacion_cpe" target="_blank" class="btn btn-primary legitRipple">Descargar Plantilla</a>
							</div>
						</div>
					</div>
					<div class="panel panel-body">
						<div class="col-md-12">
							<input type="file" class="file-styled" name="file_data_import" id="file_data_import" placeholder="Selecciona un Archivo">
						</div>
					</div>

					<div class="row">
						<div class="col-md-12" style="margin-top: 10px;">
							<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
							<button class="btn btn-primary legitRipple btn_importar_data" id="btn_importar_data" type="button">
								<i class="icon-floppy-disk mr-2"></i> Iniciar Proceso de Importación
							</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->