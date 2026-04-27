<div class="page-header">
	<div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión Plan base</span></h4>
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
				<div class="panel-body" id="content_panel_planbase">
                    <form name="frm_planbase" id="frm_planbase" action="">
                        <fieldset class="content-group"><legend class="text-bold"><i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Agregar plan base</span> </legend></fieldset>
                        <input type="hidden" class="form-control form-control-sm" name="idplanbase" id="idplanbase"> 
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-file-text2 position-left"></i>
                                    Nombre del plan
                                </label>
                                <input type="text" class="form-control form-control-sm" name="nombre_plan" id="nombre_plan" placeholder="Nombre del plan"> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-calendar mr-2"></i>
                                    Nº de días
                                </label>
                                <input type="number" class="form-control form-control-sm" name="num_dias" id="num_dias" placeholder="Nº de días"> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-file mr-2"></i>
                                    Límite de documentos
                                </label>
                                <input type="text" class="form-control form-control-sm" name="limite_doc" id="limite_doc" placeholder="Límite de documentos"> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-list mr-2"></i>
                                    Tipo
                                </label>
                                <select name="tipo_plan" id="tipo_plan" class="form-control">
                                    <option value="" selected>Selecciona un tipo</option>
                                    <option value="precio_base">Precio base</option>
                                    <option value="porcentaje">Porcentaje</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-money mr-2"></i>
                                    Precio base
                                </label>
                                <input type="number" class="form-control form-control-sm" name="precio_base" id="precio_base" placeholder="Precio base"> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-percent position-left"></i>
                                    Porcentaje
                                </label>
                                <input type="number" class="form-control form-control-sm" name="porcentaje" id="porcentaje" placeholder="Porcentaje"> 
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-notebook position-left color-indigo"></i> Nota:</label>
                            </div>
                            <textarea rows="5" cols="5" name="nota" id="nota" class="form-control" placeholder="Escribe aquí una nota"></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right mt-5">
                                <button class="btn bg-indigo legitRipple btn_planbase" type="button">
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
            <div class="panel-body" id="content_lista_planbase">
                <fieldset class="content-group">
                    <legend class="text-bold">
                        <i class="icon-list mr-2" aria-hidden="true"></i>
                        <span class="font-weight-bold text-uppercase">Lista de Plan Base</span>
                    </legend>
                </fieldset>
                <div class="row content-table">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table datatable-basic" id="tbl_lista_planbase">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Num_dias</th>
                                        <th>Límite docs</th>
                                        <th>Tipo</th>
                                        <th>Precio base</th>
                                        <th>Porcentaje</th>
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