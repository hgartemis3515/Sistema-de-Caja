<!-- Modal de Editar Cliente -->
<div id="vm_editar_cliente" class="modal fade">
    <div class="modal-dialog modal-lg"> <!-- Añadido modal-lg para un modal más ancho -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Editar Cliente</h5>
            </div>

            <div class="modal-body">
                <!-- Añadimos una clase contenedora para posicionamiento relativo -->
                <div class="container-fluid" style="position: relative;">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="vm_editar_cliente_id_tipodocidentidad">Tipo de Documento:</label>
                            <select name="vm_editar_cliente_id_tipodocidentidad" id="vm_editar_cliente_id_tipodocidentidad" class="form-control" disabled>
                                <option value="1">DNI</option>
                                <option value="6">RUC</option>
                                <option value="4">Carnet de Extranjería</option>
                                <option value="0">Otro</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="vm_editar_cliente_num_doc">Número de Documento:</label>
                            <input type="text" name="vm_editar_cliente_num_doc" id="vm_editar_cliente_num_doc" class="form-control" disabled>
                        </div>

                        <div class="col-md-5">
                            <label for="vm_editar_cliente_razon_social">Nombre del Cliente:</label>
                            <input type="text" name="vm_editar_cliente_razon_social" id="vm_editar_cliente_razon_social" class="form-control">
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-6">
                            <label for="vm_editar_cliente_direccion">Dirección:</label>
                            <input type="text" name="vm_editar_cliente_direccion" id="vm_editar_cliente_direccion" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="vm_editar_cliente_telefono">Número de Celular:</label>
                            <input type="text" name="vm_editar_cliente_telefono" id="vm_editar_cliente_telefono" class="form-control">
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px; position: relative;">
                        <div class="col-md-4">
                            <label for="vm_editar_cliente_id_codigoubigeo">Código Ubicación:</label>
                            <input type="text" name="vm_editar_cliente_id_codigoubigeo" id="vm_editar_cliente_id_codigoubigeo" class="form-control">
                        </div>

                        <div class="col-md-8">
                            <label for="vm_editar_cliente_text_ubigeo">Ubicación:</label>
                            <input type="text" name="vm_editar_cliente_text_ubigeo" id="vm_editar_cliente_text_ubigeo" class="form-control">
                        </div>

                        <!-- Contenedor de sugerencias -->
                        <div class="dropdown-menu" id="lista_sugerencias_ubigeo"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn_vm_editar_cliente_guardar">Guardar Información</button>
            </div>
        </div>
    </div>
</div>