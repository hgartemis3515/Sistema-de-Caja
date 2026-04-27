<div class="page-header">
    <div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión condición de pago</span></h4>
            <a class="heading-elements-toggle"><i class="icon-more"></i></a>
        </div>
        <div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/><span>Boleta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/factura.svg" style="width: 25px;"/><span>Factura</span></a>
				<a href="/facturacionv8/documentoelectronico/index/77/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/nota_venta.svg" style="width: 25px;"/><span>Nota de Venta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/88/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/cotizacion.svg" style="width: 25px;"/><span>Cotización</span></a>
				<a href="/facturacionv8/dashboard" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/dashboard.svg" style="width: 25px;"/><span>Dashboard</span></a>
			</div>
		</div>
    </div>
</div>
<div class="content" style="max-width: 1100px; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="panel border-top-indigo">
                <div class="panel-body" id="content_panel_condicionpago">
                    <form name="frm_condicionpago" id="frm_condicionpago" action="">
                        <fieldset class="content-group"><legend class="text-bold"><i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Agregar/editar condición de pago</span> </legend></fieldset>
                        <input type="hidden" class="form-control form-control-sm" name="idcondicionpago" id="idcondicionpago"> 
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-file-text2 position-left"></i>
                                    Tipo de Condición de Pago
                                </label>
                                <select name="tipo_pago" id="tipo_pago" class="form-control">
                                    <option value="" selected>Selecciona un tipo</option>
                                    <option value="contado">En Efectivo</option>
                                    <option value="credito">Al Crédito</option>
                                    <option value="tarjeta_credito">Con Tarjeta de crédito</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-credit-card-alt mr-2"></i>
                                    Condición de pago
                                </label>
                                <input type="text" class="form-control form-control-sm" name="condicion_pago" id="condicion_pago" placeholder="Condicion de pago"> 
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right mt-5">
                                <button class="btn bg-indigo legitRipple btn_condicionpago" type="button">
                                    <i class="icon-floppy-disk mr-2"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="panel border-top-indigo">
            <div class="panel-body" id="content_lista_condicionpago">
                <fieldset class="content-group">
                    <legend class="text-bold">
                        <i class="icon-list mr-2" aria-hidden="true"></i>
                        <span class="font-weight-bold text-uppercase">Lista de condición de pago registradas</span>
                    </legend>
                </fieldset>
                <div class="row content-table">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table datatable-basic" id="tbl_lista_condicionpago">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Condición de pago</th>
                                        <th>Tipo</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer text-muted">
        © 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
    </div>
</div>