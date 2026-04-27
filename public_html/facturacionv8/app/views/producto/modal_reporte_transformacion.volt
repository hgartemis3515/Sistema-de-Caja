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
<!-- Reporte Transformación -->
<div class="modal fade" id="vm_reporte_transformacion">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reporte de Transformación de Productos</h6>
            </div>
            
			<div class="modal-body" id="body_vm_reporte_transformacion">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel border-top-indigo">
                            <div class="panel-body" id="content_panel_transformacion">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-cash2 mr-2"></i>
                                                Sucursal
                                            </label>
                                            <select name="rep_transformacion_id_sucursal" id="rep_transformacion_id_sucursal" data-placeholder="Selecciona una Sucursal..." class="rep_transformacion_id_sucursal">
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
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-calendar2 position-left"></i>
                                                Fecha inicio
                                            </label>
                                            <input type="text" value="<?php echo date('01/m/Y'); ?>" class="form-control form-control-sm control_fecha_transformacion control_transformacion" name="rep_transformacion_fecha_inicio" id="rep_transformacion_fecha_inicio">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-calendar2 position-left"></i>
                                                Fecha fin
                                            </label>
                                            <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha_transformacion control_transformacion" name="rep_transformacion_fecha_fin" id="rep_transformacion_fecha_fin">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <button class="btn bg-indigo legitRipple btn_extraer_transformaciones" id="btn_extraer_transformaciones" type="button">
                                                Extraer Lista de Transformaciones
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="tab_transformacion_content">
                        <div class="tabbable" id="tab_transformacion">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="active"><a id="opt_tab_tranformacion_lista" href="#tab_reporte_transformacion_lista" data-toggle="tab"><i class="icon-clipboard3 position-left text-success"></i> Lista de Transformaciones</a></li>
                                <li><a id="opt_tab_tranformacion_lista_detalle" href="#tab_reporte_transformacion_lista_detalle" data-toggle="tab"><i class="icon-list2 position-left text-danger"></i> Lista Detallada</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_reporte_transformacion_lista">
                                    <div style="overflow: auto; width: 100%;">
                                        <table id="tbl_lista_transformacion" class="table">
                                            <thead>
                                                <tr class="bg-indigo">
                                                    <td>Documento</td>
                                                    <td>Fecha</td>
                                                    <td>Usuario</td>
                                                    <td>IdSucursal</td>
                                                    <td>Sucursal Origen</td>
                                                    <td>IdSucursalDestino</td>
                                                    <td>Sucursal Destino</td>
                                                    <td>Nota</td>
                                                    <td>Opc.</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                

                                <div class="tab-pane" id="tab_reporte_transformacion_lista_detalle">
                                    <div class="overflow-auto">
                                        <table id="tbl_lista_detalle_transformacion" class="table">
                                            <thead>
                                                <tr class="bg-indigo">
                                                    <td>Transferencia</td>
                                                    <td>fecha_movimiento</td>

                                                    <td>Usuario</td>
                                                    <td>IdSucursal</td>
                                                    <td>Sucursal Origen</td>
                                                    <td>IdSucursalDestino</td>
                                                    <td>Sucursal Destino</td>
                                                    <td>nota</td>
                                                    <td>Moneda</td>

                                                    <td>Producto</td>
                                                    <td>Cantidad</td>

                                                    <td>o_id_u_medida</td>
                                                    <td>o_u_medida</td>
                                                    <td>o_precio</td>
                                                    <td>o_id_afectigv</td>
                                                    <td>o_tipo_unidad</td>
                                                    <td>o_id_presentacion</td>
                                                    <td>o_cod_prod</td>
                                                    <td>o_id_prod</td>


                                                    <td>NomProdTransformado</td>
                                                    <td>d_id_u_medida</td>
                                                    <td>U.Medida</td>
                                                    <td>d_precio</td>
                                                    <td>d_id_afectigv</td>
                                                    <td>d_tipo_unidad</td>
                                                    <td>d_id_presentacion</td>
                                                    <td>d_cod_prod</td>
                                                    <td>d_id_prod</td>

                                                    <td>opciones</td>
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
<!-- /reporte transformación -->