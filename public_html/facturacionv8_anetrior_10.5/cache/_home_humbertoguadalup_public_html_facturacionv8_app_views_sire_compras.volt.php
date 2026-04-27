<style>
.chart-widget-legend {
	margin: 10px 0 0 0;
	padding: 0;
	font-size: 12px;
	text-align: center;
}

.chart-widget-legend li {
	margin: 5px 10px 0;
	padding: 7px 8px 5px;
	display: inline-block;
}
.borde-cells {
	border: 1px solid rgba(34,36,38,.1) !important;
	text-align: center !important;
	font-weight: bold !important;
	border-top: 0px solid rgba(34,36,38,.1) !important;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
	right: 0px !important;
	padding: 10px 10px;
}

.row_rigth_text {
	text-align: right;
}

.dropdown-menu {
    min-width: 251px !important;
}

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
.mt-5{
	margin-top: 20px;
}
.multiselect-item label {
	display: block;
	margin: 0;
	height: 100%;
	cursor: pointer;
	padding: 8px 12px;
	padding-left: 42px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
.checkbox, .radio{
	left: 30%;
}
.sin-padding{
	padding: 0!important;
}
.align-self-end {
	-ms-flex-item-align: end!important;
	align-self: flex-end!important;
}
/* Step*/
.step-content, .step-buttons {
    display: flex;
   
    position: relative;
    z-index: 1;
    text-align: center;
}
.step-buttons {
    justify-content: space-between;
}
.step-content{
    justify-content: space-between;
}

 .step-buttons .step-items-button{
	width: 33.33333333%;
}
.step-items.disabled-items, .step-items-button.disabled-items{
	opacity: .3;
}

.step-button {
    width: 40px;
    height: 40px;
    background: #e7e7e7;
    color: #3f51b5;
    border-radius: 50%;
    text-align: center;
    border: 1px solid #e7e7e7;
    display: inline-block;
    font-weight: 700;
    line-height: 3;
	font-size: 14px;
}
.bar-progress-step.active .step-button {
	background: #3f51b5;
    color: #fff;
}
.bar-progress-step.active::before {
	background: #3f51b5;
	color: #fff;
}
.single-step-box {
    border: 1px solid #4657b7;
    margin: 10px;
    width: 300px;
    padding: 10px;
    border-radius: 20px;
    -webkit-box-shadow: 0 0 15px 0 rgb(95 110 193 / 36%);
    box-shadow: 0 0 15px 0 rgb(95 110 193 / 36%);
    background: #fff;
}
.bar-progress-step{
	position: relative;
}
.bar-progress-step::before {
    content: '';
    width: 100%;
    height: 3px;
    background: #e7e7e7;
    position: absolute;
    left: 0;
    top: 50%;
    /* transform: translate(-50%, -50%); */
    z-index: -1;
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
</style>
<div class="page-header" style="position: relative; max-width: 1300px; margin: 0 auto; margin-top: 15px; margin-bottom: 15px;">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">SIRE - Compras</span></h4>
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
<div class="content" id="content_sire_propuesta" style="position: relative; max-width: 1300px; margin: 0 auto; margin-top: 15px; margin-bottom: 15px;">
	<div class="row">

		<?php
		if($usuario->id_rol != 4) {
		?>
		<!-- Paneles Configuración Usuario SOL y Clave SOL, y Keys -->
		<div class="col-md-12" id="configuracion_data_sol">
			<div class="panel">
				<div class="panel-body">

					<div class="row">

						<div class="col-md-12 text-right">
							<div class="content_btn_opc_avanzadas1" style="padding-top: 0px !important;">
								<div class="content_btn_opc_avanzadas2" style="margin-top: 5px;">
									<div class="content_btn_opc_avanzadas3">
										<label class="checkbox-inline checkbox-switchery checkbox-right switchery-xs">
											<input type="checkbox" id="btn_opciones_accesos_sunat" value="<?php echo $sunat_u_sol_principal; ?>" class="switch2 btn_opciones_accesos_sunat">
											Datos de Acceso SUNAT:
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 opc_datos_operaciones_sunat" style="display:none;">
							<h6 class="text-semibold">Ingresa tus Datos de Acceso a Operaciones en Línea</h6>
						</div>


						<div class="form-group col-md-3 opc_datos_operaciones_sunat" style="display:none;">
							<label class="label-form">
								<i class="icon-user mr-2"></i> Usuario Sol Principal
							</label>
							<input type="text" class="form-control form-control-sm" name="usuario_sol_principal" value="<?php echo $sunat_u_sol_principal; ?>" id="usuario_sol_principal" placeholder="Usuario Sol Principal">
						</div>


						<div class="form-group col-md-3 opc_datos_operaciones_sunat" style="display:none;">
							<label class="label-form">
								<i class="icon-lock mr-2"></i> Password Usuario Sol
							</label>
							<input class="form-control form-control-sm" type="text" name="password_usuario_sol" value="<?php echo $sunat_p_sol_principal; ?>" id="password_usuario_sol" placeholder="Password Sol Principal">
						</div>

						<div class="form-group col-md-3 opc_datos_operaciones_sunat" style="display:none;">
							<label class="label-form">
								<i class="icon-lock mr-2"></i> Cliente ID
							</label>
							<input class="form-control form-control-sm" type="text" name="cliente_id" value="<?php echo $sunat_client_id; ?>" id="cliente_id" placeholder="Client Id">
						</div>

						<div class="form-group col-md-3 opc_datos_operaciones_sunat" style="display:none;">
							<label class="label-form">
								<i class="icon-lock mr-2"></i> Sunat Client Secret
							</label>
							<input class="form-control form-control-sm" type="text" name="client_secret" value="<?php echo $sunat_client_secret; ?>" id="client_secret" placeholder="Secret Key">
						</div>

						<div class="col-md-12 text-right opc_datos_operaciones_sunat" style="display:none;">
							<button class="btn bg-indigo legitRipple" id="btn_guardar_datos_acceso" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Datos de Acceso</button>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Paneles Configuración Usuario SOL y Clave SOL, y Keys -->
		<?php
		}
		?>

		<!-- Panel Comisiones Pendientes de Pago -->
		<div class="col-md-12">
			<div class="panel" id="panel_criterios_busqueda">
				<div class="panel-body">
					<div class="step-buttons">
						<div class="step-items-button bar-progress-step step-1 active text-left">
							<div class="step-button">1</div> 
						</div> 
						<div class="step-items-button bar-progress-step step-2 text-center">
							<div class="step-button">2</div> 
						</div> 
						<div class="step-items-button bar-progress-step step-3 text-right">
							<div class="step-button">3</div> 
						</div> 
					</div>
					<div class="step-content">
						<div class="step-items active step-1">
						
							<div class="single-step-box">
								<label class="label-form">
									Paso 01: Selecciona el El Ejercicio y Periodo Tributario
								</label>
								<label class="label-form"><i class="icon-calendar position-left"></i>Num Ejercicio:</label>
								<div class="form-group has-feedback has-feedback-left">
									<select name="num_ejercicio" id="num_ejercicio" data-placeholder="Selecciona un Criterio..." class="select_num_ejercicio select2_minimo">
											<option value="0">Selecciona</option>
									</select>
								</div>
								<div class="form-group has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Periodo Tributario <span class="text-danger">*</span></label>
									<select title="Selecciona un Periodo" data-placeholder="Selecciona un periodo" class="select_periodo_tributario  select2_minimo" name="select_periodo_tributario" id="periodo_tributario" required>
									</select>
								</div>
							</div>
							<div class="btn-next-1">
								<button class="btn bg-indigo font-weight-bold text-uppercase next_step_btn" data-step="1">Siguiente</button>
							</div>
							
						
						</div>
						<div class="step-items step-2 disabled-items">
							<div class="single-step-box">
								<label class="label-form"><i class="icon-calendar position-left"></i>Paso 02: Haz Click en Extraer el Número de Ticket para solicitar a SUNAT, el número de ticket que representará el ticket de atención.</label>
								<div class="form-group has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-calendar position-left"></i>Num Ticket <span class="text-danger">*</span></label>
									<input type="text" class="form-control daterange-single"  value=""name="num_ticket" id="num_ticket" placeholder="Num Ticket" required disabled>
									<div class="form-control-feedback">
										<i class="icon-calendar text-muted"></i>
									</div>
								</div>
								<button type="button" id="btn_extraer_ticket_sire" target="_blank"
								class="btn bg-indigo font-weight-bold text-uppercase" disabled>
									<i class=" icon-search4"></i> Extraer Número de Ticket
								</a>
								</button>
								
							</div>
							<div class="btn-next-2"></div>
						</div>
						<div class="step-items step-3 disabled-items">
							<div class="single-step-box">
								<label class="label-form"><i class="icon-calendar position-left"></i>Paso 03: Haz Click en el Botón Consultar Estado Ticket para descargar la Propuesta, en algunas ocasiones tomará algunas horas para que SUNAT devuelva la propuesta.</label>
								<button type="button" id="btn_consultar_estado_y_descargar_propuesta_sire" target="_blank"
									class="btn bg-indigo font-weight-bold text-uppercase" disabled>
									<i class=" icon-search4"></i> Consultar Estado del Ticket
									</a>
								</button>
							</div>
							<div class="btn-next-3"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Panel Comisiones Pendientes de Pago -->

		<!-- Paneles de Totales -->
		<div class="col-md-12" id="totales_sire" style="display:none;">
			<div class="panel" id="panel_criterios_busqueda">
				<div class="panel-body">


					<div class="row text-center">
						<div class="col-md-2 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/factura.svg"
										style="width: 25px;"> <span id="html_total_facturas_soles"></span></h5>
								<span class="text-muted text-size-small">Total con Facturas</span>
							</div>
						</div>

						<div class="col-md-2 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/boleta.svg"
										style="width: 25px;"> <span id="html_total_boletas_soles"></span></h5>
								<span class="text-muted text-size-small">Total en Boletas</span>
							</div>
						</div>

						<div class="col-md-2 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin text-danger"><img src="/facturacionv8/img/nota_credito.svg"
										style="width: 25px;"> <span id="html_total_notas_credito_soles"></span></h5>
								<span class="text-muted text-size-small">Total en Notas Crédito</span>
							</div>
						</div>

						<div class="col-md-2 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/nota_debito.svg"
										style="width: 25px;"> <span id="html_total_notas_debito_soles"></span></h5>
								<span class="text-muted text-size-small">Total con Notas Débito</span>
							</div> 
						</div>

						<div class="col-md-4 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/money.svg"
										style="width: 25px;"> <span id="html_total_neto_soles"></span></h5>
								<span class="text-muted text-size-small">Total Neto</span>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
		<!-- /paneles de totales -->


		<!-- Detalle de Documentos de compra -->
		<div class="col-md-12" id="content_reportes" style="margin-top: 25px; display:none;">
			<div class="panel">
				<div class="panel-heading">
					<h5 class="panel-title">Formato de Libro Electrónico de Ventas <a class="heading-elements-toggle"><i
								class="icon-more"></i></a></h5>
				</div>

				<div class="panel-body">

					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive p-20">
								<table class="table table-striped" id="tbl_propuesta_sire">
									<thead>
										<tr>
											<th>Preview</th>
											<th>RUC</th>
											<th>Razon Social</th>
											<th>Periodo</th>
											<th>Car SUNAT</th>
											<th>Fecha Emision</th>
											<th>Fecha Vcto Pago</th>
											<th>Tipo CP Doc</th>
											<th>Serie CDP</th>
											<th>Año</th>
											<th>Nro CP Doc Inicial</th>
											<th>Nro Final Rango</th>
											<th>Tipo Doc Identidad</th>
											<th>Nro Doc Identidad</th>
											<th>Apellidos Nombres Razon Social</th>
											<th>BI Gravado DG</th>
											<th>IGV IPM DG</th>
											<th>BI Gravado DGNG</th>
											<th>IGV IPM DGNG</th>
											<th>BI Gravado DNG</th>
											<th>IGV IPM DNG</th>
											<th>Valor Adq NG</th>
											<th>ISC</th>
											<th>ICBPER</th>
											<th>Otros Trib Cargos</th>
											<th>Total CP</th>
											<th>Moneda</th>
											<th>Tipo Cambio</th>
											<th>Fecha Emision Doc Modificado</th>
											<th>Tipo CP Modificado</th>
											<th>Serie CP Modificado</th>
											<th>Cod DAM DSI</th>
											<th>Nro CP Modificado</th>
											<th>Clasif BSS SSS</th>
											<th>ID Proyecto Operadores</th>
											<th>Porcpart</th>
											<th>IMB</th>
											<th>Car Orig Ind E O I</th>
											<th>Detraccion</th>
											<th>Tipo de Nota</th>
											<th>Est Comp</th>
											<th>Clu1</th>
											<th>Clu2</th>
											<th>Clu3</th>
											<th>Clu4</th>
											<th>Clu5</th>
											<th>Clu6</th>
											<th>Clu7</th>
											<th>Clu8</th>
											<th>Clu9</th>
											<th>Clu10</th>
											<th>Clu11</th>
											<th>Clu12</th>
											<th>Clu13</th>
											<th>Clu14</th>
											<th>Clu15</th>
											<th>Clu16</th>
											<th>Clu17</th>
											<th>Clu18</th>
											<th>Clu19</th>
											<th>Clu20</th>
											<th>Clu21</th>
											<th>Clu22</th>
											<th>Clu23</th>
											<th>Clu24</th>
											<th>Clu25</th>
											<th>Clu26</th>
											<th>Clu27</th>
											<th>Clu28</th>
											<th>Clu29</th>
											<th>Clu30</th>
											<th>Clu31</th>
											<th>Clu32</th>
											<th>Clu33</th>
											<th>Clu34</th>
											<th>Clu35</th>
											<th>Clu36</th>
											<th>Clu37</th>
											<th>Clu38</th>
											<th>Clu39</th>
										</tr>
									</thead>
									<tbody>
										<!-- Aquí van las filas de los datos -->
									</tbody>
								</table>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Detalle de Documentos de compra -->
	</div>
</div>