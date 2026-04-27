<!-- vm_agregar_articulo -->
<div id="vm_agregar_articulo" class="modal">
	<div class="modal-dialog">
		<div class="modal-content" id="contenido_vm_agregar_articulo">
			
			<div class="modal-body" style="padding: 0px 10px 10px 10px;" id="content_popup_producto">

					<div class="row">
						<div class="tabbable">
							<ul class="nav nav-tabs bg-teal-400 opciones_producto">
								<li class="active"><a href="#buscar_producto" data-toggle="tab"><i class="icon-search4 position-left"></i> Buscar Producto</a></li>
								<li><a href="#registrar_producto" data-toggle="tab"><i class="icon-file-plus position-left"></i> Registrar Nuevo Producto</a></li>
								<li class="li-close"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true" class="icono-close">&times;</span>
								  </button></li>
							</ul>
	
							<div class="tab-content" style="padding: 0px 15px 10px 15px;">
								<div class="tab-pane active" id="buscar_producto">
									<?= $this->partial('documentoelectronico/buscar_producto') ?>
								</div>
	
								<div class="tab-pane" id="registrar_producto">
									<?= $this->partial('documentoelectronico/registrar_producto') ?>
								</div>
							</div>
						</div>
					</div>
					
				
			</div>

		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->