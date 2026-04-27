<div id="vm_config_parametros_carrito" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="vm_config_parametros_carrito_titulo">Parámetros de la Venta</h5>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Moneda:</label>
                    <div class="col-md-2">
                        <select name="pos_opt_global_moneda" id="pos_opt_global_moneda" class="form-control">
                            <option value="PEN">Soles</option>
                            <option value="USD">Dólares</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Tipo de Cambio (Actual: <?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>):</label>
                    <div class="col-md-4">
                        <input name="pos_opt_global_tipocambio" id="pos_opt_global_tipocambio" type="text" class="form-control" value="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label">IGV:</label>
                    <div class="col-md-2">
                        <select name="pos_opt_global_factorigv" id="pos_opt_global_factorigv" class="form-control">
                            <option value="18">18%</option>
                            <option value="10">10%</option>
                            <option value="10.5">10.5%</option>
                        </select>
                    </div>
                </div>

                
            </div>

            

            <div class="modal-footer">
                <button type="button" id="btn_cerrar_modal_config_pos" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>