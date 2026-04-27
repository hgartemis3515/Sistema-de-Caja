<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    right: 0;
    padding: 10px 10px;
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión Unidades</span></h4>
			<a class="heading-elements-toggle"><i class="icon-more"></i></a>
		</div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/>
					<span>Boleta</span>
				</a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/factura.svg" style="width: 25px;"/>
					<span>Factura</span>
				</a>
				<a href="/facturacionv8/documentoelectronico/index/07/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/nota_credito.svg" style="width: 25px;"/>
						<span>Nota Crédito</span>
				</a>
				<a href="/facturacionv8/documentoelectronico/index/08/nuevo" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/nota_debito.svg" style="width: 25px;"/>
					<span>Nota Débito</span>
				</a>
				<a href="/facturacionv8/reportes" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/svg/analytics.svg" style="width: 25px;"/>
					<span>Reporte de Ventas</span>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="content" style="max-width: 1100px; margin: 0 auto;">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_panel_unidades">
                    <form name="frm_unidades" id="frm_unidades" action="">
                        <fieldset class="content-group"><legend class="text-bold"><i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Agregar Unidades</span> </legend></fieldset>
                        <input type="hidden" class="form-control form-control-sm" name="idunidad" id="idunidad"> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-users mr-2"></i>
                                    Código
                                </label>
                                <input type="text" class="form-control form-control-sm" name="codigo" id="codigo" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-file-text2 position-left"></i>
                                   Nombre
                                </label>
                                <input type="text" class="form-control form-control-sm" name="nombre_unidad" id="nombre_unidad" placeholder="Nombre de Unidad"> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-stairs-up position-left"></i>
                                   Símbolo
                                </label>
                                <input type="text" class="form-control form-control-sm" name="simbolo_unidad" id="simbolo_unidad" placeholder="Símbolo de la Unidad"> 
                            </div>
                        </div>
                      
                        <div class="col-md-12">
                            <div class="text-right mt-5">
                                <button class="btn bg-indigo legitRipple btn_unidades" type="button">
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
            <div class="panel-body" id="content_lista_unidades">
                <fieldset class="content-group">
                    <legend class="text-bold">
                        <i class="icon-list mr-2" aria-hidden="true"></i>
                        <span class="font-weight-bold text-uppercase">Lista de Unidades</span>
                    </legend>
                </fieldset>
                <div class="row content-table">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table datatable-basic" id="tbl_lista_unidades">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Símbolo</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer text-muted">
        © 2018. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank">Alex Castañeda</a>
    </div>
</div>