<style>
.modal-header-bg{
    position: relative;
}
.modal-header-bg::after {
    content: ' ';
    position: absolute;
    top: -13em;
    left: 0;
    width: 100%;
    height: 400px;
    background-image: url(/facturacionv8/img/10.png);
    background-repeat: no-repeat;
}
</style>
<!-- Modal: modificar doc -->
<div class="modal fade" id="vm_modificar_doc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg bg-indigo">
                <h5 class="modal-title">Modificar Documento</h5>
            </div>
            <div class="modal-body" id="vm_modificar_doc_content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label-form"><i class="icon-profile position-left"></i>Tipo de operación</label>
                            <select name="selec_tipo_operacion" id="selec_tipo_operacion" class="select_criterios">
                                <option value="dia">1</option>
                                <option value="mes">2</option>
                                <option value="anio">3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label-form"><i class="icon-file-text2 position-left"></i>N° de placa vehicular</label>
                            <input type="text" class="form-control" id="num_vehicular">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label-form"><i class="icon-file-text2 position-left"></i>N° de orden</label>
                            <input type="text" class="form-control" id="celular_cliente_whatsapp">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label-form"><i class="icon-profile position-left"></i>Colaborador</label>
                            <select name="select_colaborador" id="select_colaborador" class="select_criterios">
                                <option value="dia">1</option>
                                <option value="mes">2</option>
                                <option value="anio">3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btn_guardar_condpago_sunat" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Modal: modificar doc  -->