<div id="vm_aviso_sincronizacion" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Sincronización de Productos</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-body">
                            <h6 class="fw-semibold" id="nombre_sucursal"></h6>
                            <p class="mb-3">Para poder utilizar el punto de venta con la sucursal seleccioanda debes descargar todos los productos, actualmente la sucursal tiene <strong class="vm_aviso_sincronizacion_total_productos">123</strong> productos y/o servicios activos. Para sincronizar estos productos debes hacer click en el botón <strong>Descargar</strong></p>

                            <button type="button" class="btn btn-primary" id="btn_sincronizar_sucursal">
                                <i class="icon-plus-circle2 mr-2"></i> Descargar productos
                            </button>

                            <div class="progress" id="vm_aviso_sincronizacion_progressbar" style="display:none">
                                <div class="progress-bar bg-teal" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85% complete</div>
                            </div>
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