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
<!-- Reporte traslados -->
<div class="modal fade" id="vm_reporte_traslados">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reporte de Traslados</h6>
            </div>
            
			<div class="modal-body" id="body_vm_reporte_traslados">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel border-top-indigo">
                            <div class="panel-body" id="content_panel_traslados">
                                <div class="row">                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-calendar2 position-left"></i>
                                                Fecha inicio
                                            </label>
                                            <input type="text" value="<?php echo date('01/m/Y'); ?>" class="form-control form-control-sm control_fecha_traslados control_traslado" name="rep_traslados_fecha_inicio" id="rep_traslados_fecha_inicio">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-calendar2 position-left"></i>
                                                Fecha fin
                                            </label>
                                            <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha_traslados control_traslado" name="rep_traslados_fecha_fin" id="rep_traslados_fecha_fin">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="label-form">
                                                <i class="icon-cash2 mr-2"></i>
                                                Sucursal Origen
                                            </label>
                                            <select name="rep_traslados_id_sucursal_o" id="rep_traslados_id_sucursal_o" data-placeholder="Selecciona una Sucursal..." class="rep_traslados_id_sucursal_o control_traslado">
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
                                                <i class="icon-cash2 mr-2"></i>
                                                Sucursal Destino
                                            </label>
                                            <select name="rep_traslados_id_sucursal_d" id="rep_traslados_id_sucursal_d" data-placeholder="Selecciona una Sucursal..." class="rep_traslados_id_sucursal_d control_traslado">
                                                <option value="0">-</option>
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

                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <button class="btn bg-indigo legitRipple btn_extraer_traslados" id="btn_extraer_traslados" type="button">
                                                Extraer Lista de Traslados
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="tab_traslados_content">
                        <div style="overflow: auto; width: 100%;">
                            <table id="tbl_lista_traslados" class="table">
                                <thead>
                                    <tr>
                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-primary-400" rowspan="2">Documento</th>
                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-primary-400" rowspan="2">F.Registo</th>
                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-primary-400" rowspan="2">Usuario</th>
                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-primary-400" rowspan="2">Nota</th>


                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-danger-400 text-center" colspan="4">ORIGEN</th>

                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-primary-400" rowspan="2">CANTIDAD</th>

                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-success-400 text-center" colspan="4">DESTINO</th>
                                        
                                        <th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells bg-primary-400" rowspan="2">Opc.</th>
                                    </tr>
                                    <tr>

                                        <td>Sucursal</td>
                                        <td>Id.Producto</td>
                                        <td>Cod.Producto</td>
                                        <td>Nombre Prod.</td>
                                        

                                        <td>Sucursal</td>
                                        <td>Id.Producto</td>
                                        <td>Cod.Producto</td>
                                        <td>Nombre Prod.</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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
<!-- /reporte traslados -->