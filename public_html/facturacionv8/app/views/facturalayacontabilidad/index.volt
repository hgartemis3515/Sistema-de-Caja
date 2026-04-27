<style>
/* LOADING */
.loading {
	/* position: absolute; */
	top: 50%;
	left: 50%;
}
.loading-bar {
	display: inline-block;
	width: 4px;
	height: 18px;
	border-radius: 4px;
	animation: loading 1s ease-in-out infinite;
}
.loading-bar:nth-child(1) {
	background-color: #3f51b5;
	animation-delay: 0;
}
.loading-bar:nth-child(2) {
	background-color: #2196f3;
	animation-delay: 0.09s;
}
.loading-bar:nth-child(3) {
	background-color: #4caf50;
	animation-delay: .18s;
}
.loading-bar:nth-child(4) {
	background-color: #00bcd4;
	animation-delay: .27s;
}
/* clases para popup */
@media only screen and (max-width: 400px) {
	.modal-dialog {
        position: relative;
    width: auto;
    margin: 10px;
    height: 100%;
    overflow: inherit!important;
    transform: inherit!important;
	
	}
}
@media (min-width: 600px) and (max-width: 1000px) {
	.modal-dialog {
    position: relative;
    width: auto; 
    margin: 10px;
    height: 100%;
    overflow: inherit!important;
	transform: inherit!important;
	}
}
@keyframes loading {
	0% {
	transform: scale(1);
	}
	20% {
	transform: scale(1, 2.2);
	}
	40% {
	transform: scale(1);
	}
}
/* /LOADING */

#ul_opcion_cpe>li>a:hover {
    color: #333;
    background-color: transparent !important;
}
</style>

<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"> Contabilidad FacturalaYa S.R.L.</span></h4>
		</div>
	</div>
</div>

<div class="content">

    <div class="panel border-top-indigo">
		<div class="panel-body" id="content_panel_usuario">
			<fieldset class="content-group">
				<legend class="text-bold">
					<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
					<span class="text-uppercase">Filtros avanzados</span> 
				</legend>
			</fieldset>
			<form name="frm_reporte_consolidado" id="frm_reporte_consolidado" action="">
				<div class="row">

					<div class="col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
                        <label class="label-form">
                            <i class="icon-cash2 mr-2"></i>
                            Tipo de Filtro
                        </label>
						<select name="filto_tipo_filtro" id="filto_tipo_filtro" data-placeholder="Selecciona el tipo de filtro..." class="select_minimizado filto_tipo_filtro">
							<option value="fecha_registro">Por Fecha Registro</option>
                            <option value="fecha_validacion">Por Fecha Validación</option>
                            <option value="fecha_deposito">Por Fecha Depósito</option>
                            <option value="perido_contable">Por Periodo Contable</option>
						</select>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-4 col-xs-6 content_fecha_inicio_fin">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-calendar2 position-left"></i>
								Fecha inicio
							</label>
							<input type="text" value="<?php echo date('01/m/Y'); ?>" class="form-control form-control-sm control_fecha_inicio" name="criterio_fecha_inicio" id="criterio_fecha_inicio">
							<input type="hidden" name="criterio_fecha_inicio_valor" value="<?php echo date('Y-m-01 00:00:00'); ?>" id="criterio_fecha_inicio_valor" />
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-xs-6 content_fecha_inicio_fin">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-calendar2 position-left"></i>
								Fecha fin
							</label>
							<input type="text" value="<?php echo date('t/m/Y 23:59:59'); ?>" class="form-control form-control-sm control_fecha_fin" name="criterio_fecha_fin" id="criterio_fecha_fin">
							<input type="hidden" name="criterio_fecha_fin_valor" value="<?php echo date('Y-m-t 23:59:59'); ?>" id="criterio_fecha_fin_valor" />
						</div>
					</div>
                    
                    <div class="col-lg-8 col-md-8 col-xs-12  content_periodo_contabilidad" style="display:none;">
                        <div class="form-group">
                            <div class="has-feedback has-feedback-left">
                                <label class="label-form"><i class="fa fa-bank position-left"></i>Periodo Contabilidad: </label>
                                <select title="Seleccionar Mes y Anio Contabilidad" data-placeholder="Seleccionar Periodo Contable" class="filtro_mes_anio_contabilidad select" name="filtro_mes_anio_contabilidad" id="filtro_mes_anio_contabilidad">
                                    <?php
                                    foreach($mes_anio_contabilidad as $fecha) {
                                        echo '<option value="'.$fecha.'">'.$fecha.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

					<div class="float-right">
						<button class="btn bg-indigo legitRipple btn_generar_reporte" type="button">
							Extraer Lista de Depósitos
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>


    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Nuevos Registros</h6>
                </div>
                
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-primary-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Nuevos Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-checkmark3 text-size-mini position-left"></i> Cuentas Nuevas de Socios Estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_socios_estrategicos_nuevos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Nuevos Clientes Serv.Facturación Directos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Clientes Directos de facturalaya.com</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_clientes_servicio_directos_nuevos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-indigo-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">P</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Nuevas Activaciones PSE Directos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Activaciones PSE directo de facturalaya.com</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_activaciones_pse_directo_nuevos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Nuevos Clientes de Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Clientes del Serv. Fac.Elect. de Socios Estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_clientes_de_socios_estrategicos_nuevos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Nuevas Activaciones PSE by Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE de Clientes de Socios Estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_activaciones_pse_cliente_socio_nuevos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-info-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">A</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Nuevas Activaciones PSE de SisCompleto</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE de Clientes de Sistema Completo</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_activaciones_pse_cliente_sis_completo_nuevos"></h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        
        </div>

        <div class="col-lg-6 col-md-6 col-xs-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Estadísticas Generales</h6>
                </div>
                
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        
                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Clientes Sistema Completo PHP</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Número de Clientes SisCompleto</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_clientes_sis_completo"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-primary-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Total Cuentas Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-checkmark3 text-size-mini position-left"></i> Número de Cuentas pagadas de Socios Estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_socios_estrategicos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-indigo-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">P</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Clientes de Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Clientes del Serv. Facturación</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_clientes_de_socios_estrategicos"></h6>
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Cliente del Servicio Fact.Elect. Directos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Clientes del Serv. Fac.Elect. de facturalaya.com</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_clientes_servicio_directos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Número de Activaciones PSE Directos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE Directos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_activaciones_pse_directo"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-info-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">A</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Número de Activaciones PSE de Socios</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE de Socios</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_activaciones_pse_cliente_socio"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-info-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">A</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Número de Activaciones PSE de SisCompleto</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE de SisCompleto</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="num_activaciones_pse_cliente_sis_completo"></h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        
        </div>

        <div class="col-lg-6 col-md-6 col-xs-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Montos</h6>
                </div>
                
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        
                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto SisCompleto y Actualizaciones</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Sistema Completo y Actualizaciones</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_clientes_sis_completo"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-primary-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto por Plan Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-checkmark3 text-size-mini position-left"></i> Monto por plan de socios estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_socios_estrategicos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-indigo-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">P</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Montos por Clientes de Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Clientes de Socios Estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_clientes_de_socios_estrategicos"></h6>
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto por Clientes directos del Serv. Fact.Elect.</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Clientes directos del Serv. Fact.Elect.</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_clientes_servicio_directos"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto Activaciones PSE Directos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE Directos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_activaciones_pse_directo"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-info-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">A</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto de Activaciones PSE de Socios</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE de Socios</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_activaciones_pse_cliente_socio"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-info-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">A</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto de Activaciones PSE de SisCompleto</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Activaciones PSE de SisCompleto</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_activaciones_pse_cliente_sis_completo"></h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        
        </div>

        <div class="col-lg-6 col-md-6 col-xs-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Totales</h6>
                </div>
                
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        
                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-danger-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">PSE Total</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-spinner11 text-size-mini position-left"></i> Monto Total PSE</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_pse_total"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-primary-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">E</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Total Serv.Fact. Elect.</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-checkmark3 text-size-mini position-left"></i> Total Servicio Facturación</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_servicio_facturacion_total"></h6>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-indigo-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">P</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Total Código Fuente PHP</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Total Código Funete PHP</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_sis_completo_total"></h6>
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Total Socios Estratégicos</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Socios Estratégicos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_socios_estrategicos_total"></h6>
                                </td>
                            </tr>




                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-indigo-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">P</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto Total</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> Total Ingresos</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_total"></h6>
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">S</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">IGV</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-lifebuoy text-size-mini position-left"></i> IGV</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="igv_total"></h6>
                                </td>
                            </tr>




                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <a href="#" class="btn bg-primary-400 btn-rounded btn-icon btn-xs">
                                            <span class="letter-icon">G</span>
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Monto Gasto</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-checkmark3 text-size-mini position-left"></i> Monto Considerado Gasto</div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin" id="monto_gasto"></h6>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        
        </div>

        <div class="col-lg-3 col-md-3 col-xs-6">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/factura.svg" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="dividendos_pse"></h5>
						<div>PSE</div>
						<div class="text-size-small text-muted">Monto Base: <strong id="reparticion_pse"></strong></div>
                        <div class="text-size-small text-muted">Utilidades Asignadas a Diver, Nora y Alex</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-xs-6">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/boleta.svg" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="dividendos_servicio"></h5>
						<div>Servicio Fac.Elect.</div>
						<div class="text-size-small text-muted">Monto Base: <strong id="reparticion_servicio_facturacion"></strong></div>
                        <div class="text-size-small text-muted">Utilidades Asignadas a Diver, Nora y Alex</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-xs-6">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/nota_venta.svg" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="dividendos_sis_completo"></h5>
						<div>Sis. Completo</div>
						<div class="text-size-small text-muted">Monto Base: <strong id="reparticion_sis_completo"></strong></div>
                        <div class="text-size-small text-muted">Utilidades Asignadas a Diver, Nora y Alex</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-xs-6">
			<div class="panel text-center">
				<div class="panel-body" style="padding: 20px;">
					<!-- Progress counter -->
					<div class="content-group-sm svg-center position-relative" style="margin-bottom: 5px!important;">
						<img src="/facturacionv8/img/cuentas_por_cobrar_total.png" style="width: 35px;">
						<h5 class="mt-15 mb-5" id="dividendos_socios_estrategicos"></h5>
						<div>Socios Estratégicos</div>
						<div class="text-size-small text-muted">Monto Base: <strong id="reparticion_socios_estrategicos"></strong></div>
                        <div class="text-size-small text-muted">Utilidades Asignadas a Diver, Nora y Alex</div>
					</div>
					<!-- /progress counter -->
				</div>
			</div>
		</div>

    </div>
            

	<div class="panel border-top-indigo">
		<div class="panel-body" id="content_lista_ingresos">

            <div class="row">
		    	<div class="col-md-12 btn-options text-aling" style="margin-bottom: 20px;">
                    <button id="btn_abrir_vm_ingreso" style="margin-right: 7px; margin-bottom: 5px;" type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple"><b><i class="icon-pencil3"></i></b> Registrar Depósito</button>
                </div>
            </div>

            <div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table datatable-basic" id="tbl_lista_ingresos">
							<thead>
								<tr>
									<th>ID</th>
									<th>F. Depósito</th>
									<th>F. Validación</th>
									<th>U. Registro</th>
                                    <th>U. Validación</th>
                                    <th>Cuenta Banco</th>
                                    <th>Num. Operación</th>
                                    <th>Monto</th>
                                    <th>Servicio</th>
                                    <th>IdTipoDoc</th>
                                    <th>CPE</th>
                                    <th>Serie</th>
                                    <th>Correlativo</th>
                                    <th>Validación</th>
                                    <th>NuevoCliente</th>
                                    <th>Periodo</th>
                                    <th>¿Utilidades?</th>
                                    <th>Detalle</th>
                                    <th>Acción</th>
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

<!-- vm_agregar_ingreso -->
<div id="vm_agregar_ingreso" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="content_vm_agregar_ingreso">
			<div class="modal-header bg-info">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Guardar Depósito</h6>
			</div>
	
			<div class="modal-body">
                <form name="frm_deposito" id="frm_deposito" action="">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-calendar2 position-left"></i>
                                    Fecha Depósito
                                </label>
                                <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm fecha_deposito" name="fecha_deposito" id="fecha_deposito">
                                <input type="hidden" name="fecha_deposito_valor" value="<?php echo date('Y-m-d 00:00:00'); ?>" id="fecha_deposito_valor" />
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="form-group">
                                <div class="has-feedback has-feedback-left">
                                    <label class="label-form"><i class="fa fa-bank position-left"></i>Banco: </label>
                                    <select title="Seleccionar Banco" data-placeholder="Seleccionar Banco" class="id_cuentabanco select" name="id_cuentabanco" id="id_cuentabanco">
                                        <option value="0">Selecc. Banco</option>
                                        <?php
                                        foreach($cuentas_banco as $banco) {
                                            echo '<option value="'.$banco->id_cuentabanco.'">'.$banco->nombre_banco.' - '.$banco->nombre_titular.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="label-form"><i class="fa fa-hashtag position-left"></i> Num.Ope.: </label>
                                <input type="text" value="" title="Número de Operación" name="num_operacion" id="num_operacion" placeholder="Número de Operación" class="form-control num_operacion">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="label-form"><i class="fa fa-hashtag position-left"></i> Monto: </label>
                                <input type="text" value="" title="Monto" name="monto" id="monto" placeholder="Monto" class="form-control monto">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="form-group">
                                <div class="has-feedback has-feedback-left">
                                    <label class="label-form"><i class="fa fa-bank position-left"></i>Periodo Contabilidad: </label>
                                    <select title="Seleccionar Mes y Anio Contabilidad" data-placeholder="Seleccionar Banco" class="mes_anio_contabilidad select" name="mes_anio_contabilidad" id="mes_anio_contabilidad">
                                        <?php
                                        foreach($mes_anio_contabilidad as $fecha) {
                                            echo '<option value="'.$fecha.'">'.$fecha.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-9 col-xs-6">
                            <div class="form-group">
                                <div class="has-feedback has-feedback-left">
                                    <label class="label-form"><i class="fa fa-bank position-left"></i>Servicio: </label>
                                    <select title="Seleccionar Mes y Anio Contabilidad" data-placeholder="Seleccionar Servicio" class="id_servicio select" name="id_servicio" id="id_servicio">
                                        <option value="1">Sistema Completo Código Fuente PHP</option>
                                        <option value="2">Actualización Código Fuente PHP</option>
                                        <option value="3">Servicio Facturación - Cliente Directo FacturalaYa</option>
                                        <option value="4">Plan de Socios Estratégicos</option>
                                        <option value="5">Cliente Socio Estratégico - Servicio Facturación</option>
                                        <option value="6">Activación PSE - Directo FacturalaYa</option>
                                        <option value="7">Activación PSE - Cliente de Socio Estratégico</option>
                                        <option value="8">Activación PSE - Cliente de Sistema Completo</option>
                                        <option value="9">Renovación VPS y Otr. Servicios</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="checkbox checkbox-switch">
                                    <label class="label-form">
                                        <input name="opcion_cpe" id="opcion_cpe" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
                                        ¿Deseas Vincular un Comprobante?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="content_cpe" style="display:none;">

                        <div class="col-lg-12 col-md-12 col-xs-12" id="content_crear_nuevo_cpe">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <div class="has-feedback has-feedback-left">
                                                    <label class="label-form"><i class="fa fa-bank position-left"></i>Tipo CPE: </label>
                                                    <select title="Seleccionar Mes y Anio Contabilidad" data-placeholder="Seleccionar Servicio" class="tipo_doc_nuevo_cpe select" name="tipo_doc_nuevo_cpe" id="tipo_doc_nuevo_cpe">
                                                        <option value="01">Factura</option>
                                                        <option value="03">Boleta</option>
                                                        <option value="77">Nota Venta</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-4" style="display:none;">
                                            <div class="form-group">
                                                <div class="has-feedback has-feedback-left">
                                                    <label class="label-form"><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
                                                    <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
                                                        <option value="6" selected>R.U.C.</option>
                                                        <option value="1">D.N.I.</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-4" id="estado_numerodocumento">
                                            <div class="form-group">
                                                <label class="label-form"><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° de RUC</span>: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento" required>
                                                    <span class="input-group-btn">
                                                        <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                                            <i class="icon-search4" id="icon_search_document"></i>
                                                            <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-4" id="razonsocial_numerodocumento">
                                            <div class="form-group">
                                                <label class="label-form"><i class="icon-vcard position-left"></i> <span id="titulo_nombrecliente">Razón Social</span>: <span class="text-danger">*</span></label>
                                                <div class="input-group" id="content_razon_social_cliente" style="display: block;">
                                                    <input type="text" title="Ingresa la Razón Social o Nombre" name="cliente_nombre" id="cliente_nombre" placeholder="Nombre o Razón Social Aquí" class="form-control cliente_nombre">
                                                </div> 
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="has-feedback has-feedback-left">
                                                    <label class="label-form"><i class="fa fa-bank position-left"></i>Seleccionar Documento: </label>
                                                    <select title="Seleccionar Mes y Anio Contabilidad" data-placeholder="Buscar" class="bucar_cpe_asignacion select" name="bucar_cpe_asignacion" id="bucar_cpe_asignacion">
                                                        <option value="0" selected>Crear Nueva Factura</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="checkbox checkbox-switch">
                                    <label class="label-form">
                                        <input name="es_cliente_nuevo" id="es_cliente_nuevo" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
                                        ¿Es un Cliente Nuevo?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
			</div>

            <div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_guardar_ingreso" class="btn btn-info">Guardar Depósito!</button>
			</div>

		</div>
	</div>
</div>
<!-- /vm_agregar_ingreso -->