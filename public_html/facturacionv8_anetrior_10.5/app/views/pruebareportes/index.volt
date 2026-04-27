<style>
h4, .h4, h5, .h5, h6, .h6 {
    margin-top: 0px;
    margin-bottom: 0px;
}
.mb-1{
    margin-bottom: .5em;
}
.mb-2{
    margin-bottom: 1em;
}
</style>
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Perfil</span></h4>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
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
<div class="content">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <form name="frm_generar_repote" id="frm_generar_repote">
                    <h6 class="text-semibold mb-2">Selecciona un Rango de Fechas</h6>
                        <div class="col-md-3">
                            <label for="rangofechas"><i class="icon-calendar2 position-left"></i>Rango de Fechas:</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                                <input type="hidden" id="fechainicio" name="fechainicio" value="">
                                <input type="hidden" id="fechafinal" name="fechafinal" value="">
                                <input name="rangofechas" id="rangofechas" type="text"
                                    class="rangofechas form-control daterange-buttons" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label><i class="icon-profile position-left"></i>Sucursal:</label>
                            <select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select2 select_sucursal" name="select_sucursal" id="select_sucursal" required>		
                                <option value="todos" selected>Todas las sucursales</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label><i class="icon-user position-left"></i>Vendedores:</label>
                            <select data-placeholder="Selecciona un vendedor" class="select2 select_vendedores" name="select_vendedores" id="select_vendedores" required>		
                                <option value="todos" selected>Todos los vendedores</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label><i class="icon-calendar position-left"></i>Visualizar
                                por:</label>
                            <select name="select_criterio_visualizacion" id="select_criterio_visualizacion"
                                data-placeholder="Selecciona un Criterio..." class="select2 select_producto_buscar">
                                <option value="dia">Día</option>
                                <option value="mes">Mes</option>
                                <option value="anio">Año</option>
                            </select>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" id="btn_generar_reporte" style="margin-top: 25px;" target="_blank"
                                class="btn bg-indigo font-weight-bold text-uppercase">
                                <i class=" icon-search4"></i> Generar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h6 class="font-weight-bold text-uppercase mb-1">Total facturas</h6>
                    <img src="/facturacionv8/img/factura.svg" style="width: 30px;" class="float-left">
                    <h4 class="float-right">S/ 7,972.68</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h6 class="font-weight-bold text-uppercase mb-1">Total facturas</h6>
                    <img src="/facturacionv8/img/factura.svg" style="width: 30px;" class="float-left">
                    <h4 class="float-right">S/ 7,972.68</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h6 class="font-weight-bold text-uppercase mb-1">Total facturas</h6>
                    <img src="/facturacionv8/img/factura.svg" style="width: 30px;" class="float-left">
                    <h4 class="float-right">S/ 7,972.68</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h6 class="font-weight-bold text-uppercase mb-1">Total facturas</h6>
                    <img src="/facturacionv8/img/factura.svg" style="width: 30px;" class="float-left">
                    <h4 class="float-right">S/ 7,972.68</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h6 class="font-weight-bold text-uppercase mb-1">Total facturas</h6>
                    <img src="/facturacionv8/img/factura.svg" style="width: 30px;" class="float-left">
                    <h4 class="float-right">S/ 7,972.68</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h6 class="font-weight-bold text-uppercase mb-1">Total facturas</h6>
                    <img src="/facturacionv8/img/factura.svg" style="width: 30px;" class="float-left">
                    <h4 class="float-right">S/ 7,972.68</h4>
                </div>
            </div>
        </div>
    </div>
</div>
