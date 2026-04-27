<style>
/* tab style */
.nav_new_style {
	text-align: center;
}
.nav-tabs.nav_new_style.nav-tabs-solid>.active>a, .nav-tabs.nav_new_style.nav-tabs-solid>.active>a:focus, .nav-tabs.nav_new_style.nav-tabs-solid>.active>a:hover {
	margin: 0 10px;
	border-radius: 5rem!important;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
}
.nav-tabs.nav_new_style.nav-tabs-solid>li >a, .nav-tabs.nav_new_style.nav-tabs-solid>li >a:focus, .nav-tabs.nav_new_style.nav-tabs-solid> li >a:hover {
	background-color: #fff;
	border: 1px solid #ddd!important;
	border-radius: 5rem!important;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
}
.modal-header-bg{
	position: relative;
}
.modal-header-bg::after {
	content: ' ';
	position: absolute;
	top: -7em;
	left: 0;
	width: 100%;
	height: 400px;
	background-image: url(/facturacionv8/img/10.png);
	background-repeat: no-repeat;
}
.nav-tabs.nav-tabs-solid.nav_new_style {
	background-color: transparent;
}
</style>
<!-- Reporte ingresos y salidas -->
<div class="modal fade" id="vm_reporte_in_sa">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reporte de Ingresos y Salidas</h6>
            </div>
            
			<div class="modal-body" id="body_vm_reporte_in_sa">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel border-top-indigo">
                            <div class="panel-body" id="content_panel_in_sal">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-cash2 mr-2"></i>
                                                Sucursal
                                            </label>
                                            <select name="rep_in_sa_id_sucursal" id="rep_in_sa_id_sucursal" data-placeholder="Selecciona una Sucursal..." class="rep_in_sa_id_sucursal">
                                                <?php 
                                                foreach($sucursales as $sucursal) {
                                                ?>
                                                <option value="<?php echo $sucursal->idsucursal; ?>"><?php echo $sucursal->nombre.' (ID: '.$sucursal->idsucursal.')'; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>	
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-calendar2 position-left"></i>
                                                Fecha inicio
                                            </label>
                                            <input type="text" value="<?php echo date('01/m/Y'); ?>" class="form-control form-control-sm control_fecha_in_sa control_in_sa" name="rep_in_sa_fecha_inicio" id="rep_in_sa_fecha_inicio">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-calendar2 position-left"></i>
                                                Fecha fin
                                            </label>
                                            <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha_in_sa control_in_sa" name="rep_in_sa_fecha_fin" id="rep_in_sa_fecha_fin">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-cash2 mr-2"></i>
                                                Producto
                                            </label>
                                            <select name="rep_in_sa_idproducto" id="rep_in_sa_idproducto" data-placeholder="Selecciona un Producto..." class="rep_in_sa_idproducto control_in_sa"> 
                                            </select>
                                        </div>	
                                    </div>

                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <button class="btn bg-indigo legitRipple btn_extraer_salidas_entradas" id="btn_extraer_salidas_entradas" type="button">
                                                Extraer Lista de Movimientos
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="tab_ingresos_salidas_content">
                        <div class="tabbable" id="tab_ingresos_y_salidas">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="active"><a id="opt_tab_ingresos" href="#tab_reporte_ingresos" data-toggle="tab"><i class="icon-plus-circle2 position-left text-success"></i> Ingresos</a></li>
                                <li><a id="opt_tab_salidas" href="#tab_reporte_salidas" data-toggle="tab"><i class="icon-minus-circle2 position-left text-danger"></i> Salidas</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_reporte_ingresos">
                                    <div style="overflow: auto; width: 100%;">
                                        <table id="tbl_lista_ingresos" class="table">
                                            <thead>
                                                <tr class="bg-indigo">
                                                    <td>Doc.Ingreso</td>
                                                    <td>Tipo</td>
                                                    <td>Sucursal</td>
                                                    <td>F.Movimiento</td>
                                                    <td>Nota</td>
                                                    <td>Id Prod.</td>
                                                    <td>Codigo Prod.</td>
                                                    <td>Nombre Prod.</td>
                                                    <td>Cantidad</td>
                                                    <td>Opc.</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_reporte_salidas">
                                    <div class="overflow-auto">
                                        <table id="tbl_lista_salidas" class="table">
                                            <thead>
                                                <tr class="bg-indigo">
                                                    <td>Doc.Ingreso</td>
                                                    <td>Tipo</td>
                                                    <td>Sucursal</td>
                                                    <td>F.Movimiento</td>
                                                    <td>Nota</td>
                                                    <td>Id Prod.</td>
                                                    <td>Codigo Prod.</td>
                                                    <td>Nombre Prod.</td>
                                                    <td>Cantidad</td>
                                                    <td>Menu</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!-- /reporte ingresos -->