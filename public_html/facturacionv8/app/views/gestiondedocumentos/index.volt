<style>
button.btn.btn-success {
	padding: 6px 10px;
}
.color-black{
	color: #000!important;
}
.color-indigo{
	color: #3F51B5!important;
}
.img-lg {
	width: 60px!important;
	height: 60px!important;
}
.mt-1{
	margin-top: 15px;
}
.mt-2{
	margin-top: 25px;
}
.mb-2{
	margin-bottom: 25px;
}
.p-2{
	float: left;
}
.pr-2{
	padding-right: 20px;
}
.panel-access a{
	color: #000;
}
.panel-indigo > .panel-heading {
	color: #fff;
	background-color: #3F51B5;
	border-color: #3F51B5;
}
.panel-teal > .panel-heading {
	color: #fff;
	background-color: #26A69A;
	border-color: #26A69A;
}
@media(min-width: 900px){

	.p-2{
		padding: 10px 0px 10px 0px;
		float: left;
	}
	.padding-h5{
		padding: 5px 10px 5px 50px;
	}
	.pr-2{
		padding-right: 20px;
	}	
}

@media (min-width: 1025px) {
	.items_opciones {
		margin-bottom: 15px;
	}
}

.media_item_opcion {
	padding-right: 10px !important;
}

.datatable-basic {
	width: 100% !important;
}
.mb-2{
	margin-bottom: 2em;
}
.width-1{
	width: 20px!important;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
font-size: 12px;
} 
.breadcrumb > li + li:before {
	content: "-\00a0" !important;
	padding: 0 5px;
	color: #333333;
}
#inicio_lista:before {
	content: "" !important;
	padding: 0 5px;
	color: #333333;
}

.dropdown-menu > li > a {
	white-space: normal;
}
.custom-textarea:focus {
	/* outline: 0; */
	/* border-color: transparent; */
	border-bottom-color: #009688;
	-webkit-box-shadow: 0 1px 0 #009688;
	box-shadow: 0 1px 0 #009688;
	border-color: #ddd;
	outline: 0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(221, 221, 221, 0.6);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(221, 221, 221, 0.6);
}
.custom-textarea {
	display: block;
	width: 100%;
	padding: 8px 16px;
	font-size: 13px;
	line-height: 1.5384616;
	color: #333333;
	background-color: transparent;
	background-image: none;
	border: 1px solid #ddd;
	border-radius: 3px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	-webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

.navbar-brand-original {
	float: left;
	padding: 13px 20px;
	font-size: 14px;
	line-height: 20px;
	height: 46px;
}
.datatable-scroll-wrap {
	overflow-x: visible !important;
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

.dataTables_processing {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    width: 100% !important;
    height: 40px !important;
    margin-left: -50% !important;
    margin-top: -25px !important;
    padding-top: 20px !important;
    text-align: center !important;
    background-color: #fff !important;
    background: -webkit-gradient(linear,left top,right top,color-stop(0,rgba(255,255,255,0)),color-stop(25%,rgba(255,255,255,.9)),color-stop(75%,rgba(255,255,255,.9)),color-stop(100%,rgba(255,255,255,0))) !important;
    background: -webkit-linear-gradient(left,rgba(255,255,255,0) 0,rgba(255,255,255,.9) 25%,rgba(255,255,255,.9) 75%,rgba(255,255,255,0) 100%) !important;
    background: -moz-linear-gradient(left,rgba(255,255,255,0) 0,rgba(255,255,255,.9) 25%,rgba(255,255,255,.9) 75%,rgba(255,255,255,0) 100%) !important;
    background: -ms-linear-gradient(left,rgba(255,255,255,0) 0,rgba(255,255,255,.9) 25%,rgba(255,255,255,.9) 75%,rgba(255,255,255,0) 100%) !important;
    background: -o-linear-gradient(left,rgba(255,255,255,0) 0,rgba(255,255,255,.9) 25%,rgba(255,255,255,.9) 75%,rgba(255,255,255,0) 100%) !important;
    /* background: linear-gradient(to right,rgba(255,255,255,0) 0,rgba(255,255,255,.9) 25%,rgba(255,255,255,.9) 75%,rgba(255,255,255,0) 100%); */
}

.select2-selection--multiple .select2-selection__choice {
    background-color: #7981f0 !important;
    color: #fff;
    border-radius: 3px;
    cursor: default;
    float: left;
    margin-right: 2px;
    margin-top: 2px;
    padding: 7px 12px;
}

.tabla_informe_sunat {
	width: 600px;
    font-family: Arial, sans-serif;
    border-collapse: collapse;
    font-size: 14px;
}

.tabla_informe_sunat th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    background-color: #4CAF50;
    color: white;
}

.tabla_informe_sunat td {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

.tabla_informe_sunat tr:nth-child(even) {
    background-color: #f2f2f2;
}


</style>
<div class="content" id="contenido_documentos_totales">
	<div class="row">

		<!-- panel de opciones avanzadas -->
		<div class="col-md-12" style="margin-top: 15px;">
			<div class="panel" id="panel_rangofechas">
				<div class="panel-body">

					<div class="col-md-12 opc_controles_avanzados">
						<h6 class="text-semibold">Opciones Avanzadas</h6>
						<p>A continuación puedes seleccionar los diferentes criterios para mostrar las tablas...</p>
					</div>
					<div class="col-md-4 opc_controles_avanzados">
						<label for="rangofechas"><i class="icon-calendar2 position-left"></i>Rango de Fechas:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-calendar3"></i></span>
							<input type="hidden" id="fechainicio" name="fechainicio" value="">
							<input type="hidden" id="fechafinal" name="fechafinal" value="">
							<input name="rangofechas" id="rangofechas" type="text"
								class="rangofechas form-control daterange-buttons" value="">
						</div>
					</div>
					
					<div class="col-md-4 opc_controles_avanzados">
						<label for="select_vendedores"><i class="icon-users position-left"></i> Estado Comprobantes:</label>
						<div class="form-group has-feedback has-feedback-left">
							<select name="select_estado_comprobante" id="select_estado_comprobante" data-placeholder="Selecciona un Criterio..." class="multiselect" multiple="multiple">
								<option value="aceptado">Aceptado</option>
								<option value="rechazado">Rechazado</option>
								<option value="pendiente" selected>Pendiente</option>
								<option value="ticket">Ticket</option>
								<option value="anulado">Anulado</option>
								<option value="xml">Solamente Sin XML</option>
							</select>
						</div>
					</div>

					<div class="col-md-4 opc_controles_avanzados">
						<label for="select_tipo_comprobante"><i class="icon-calendar position-left"></i> Tipo Comprobante:</label>
						<div class="form-group has-feedback has-feedback-left">
							<select name="select_tipo_comprobante" id="select_tipo_comprobante" data-placeholder="Selecciona un Periodo..." class="multiselect" multiple="multiple">
								<option selected value="01" <?php if($tipodoc_electronico == '01') { echo 'selected'; } ?>>FACTURA</option>
								<option selected value="03" <?php if($tipodoc_electronico == '03') { echo 'selected'; } ?>>BOLETA</option>
								<option selected value="07" <?php if($tipodoc_electronico == '07') { echo 'selected'; } ?>>NOTAS DE CRÉDITO</option>
								<option selected value="08" <?php if($tipodoc_electronico == '08') { echo 'selected'; } ?>>NOTAS DE DÉBITO</option>
								<option value="09" <?php if($tipodoc_electronico == '09') { echo 'selected'; } ?>>Guía Remisión</option>
							</select>
						</div>
					</div>

					<div class="col-md-4 opc_controles_avanzados">
						<label for="select_contribuyente"><i class="icon-store2 position-left"></i> Lista de Contribuyentes:</label>
						<div class="form-group has-feedback has-feedback-left">
							<select name="select_contribuyente" id="select_contribuyente" data-placeholder=" Se muestran todos los contribuyentes en producción" class="multiselect" multiple="multiple">
								<?php
								foreach($contribuyentes as $contribuyente) {
									if($contribuyente->id_contribuyente == $id_contribuyente) {
										echo '<option selected value="'.$contribuyente->id_contribuyente.'">'.$contribuyente->id_contribuyente.'.- '.$contribuyente->ruc.' - '.$contribuyente->razon_social.' - idPAT: '.$contribuyente->id_patrocinador.'</option>';
									} else { 
										echo '<option value="'.$contribuyente->id_contribuyente.'">'.$contribuyente->id_contribuyente.'.- '.$contribuyente->ruc.' - '.$contribuyente->razon_social.' - idPAT: '.$contribuyente->id_patrocinador.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4 opc_controles_avanzados">
						<label for="select_contribuyente_excluir"><i class="icon-store2 position-left"></i> Excluir Contribuyentes:</label>
						<div class="form-group has-feedback has-feedback-left">
							<select name="select_contribuyente_excluir" id="select_contribuyente_excluir" data-placeholder=" Selecciona los Contribuyentes a Excluir" class="multiselect" multiple="multiple">
								<?php
								foreach($contribuyentes as $contribuyente) {
									echo '<option value="'.$contribuyente->id_contribuyente.'">'.$contribuyente->id_contribuyente.'.- '.$contribuyente->ruc.'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<div class="checkbox checkbox-switch">
								<label class="label-form">
									<input name="opcion_recarga_tabla" value="si" id="opcion_recarga_tabla" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini" checked>
									¿Deseas Recargar la tabla después de cada acción?
								</label>
							</div>
						</div>
					</div>

					<div class="col-md-12 text-right">
						<button class="btn bg-indigo legitRipple btn_extraer_cpe" type="button">Extraer Documentos Electrónicos</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /panel de opciones avanzadas -->



		<div class="col-md-12" id="content_lista_docs_general">
			<div class="content-group tab-content-bordered navbar-component">
				<div class="navbar navbar-inverse bg-teal-400" style="position: relative; z-index: 30;">

					<div class="navbar-collapse collapse" id="demo1">
						<ul class="nav navbar-nav">
							<li class="<?php if($id_tipodoc != '77' && $id_tipodoc != '88' && $id_tipodoc != '09') { echo 'active'; } ?>">
								<a href="#tab_docs_sunat" data-toggle="tab">
									<img src="/facturacionv8/img/sunat_logo.png" class="position-left" style="width: 22px;">
									Gestión y Envío Masivo de CPE - SUNAT
								</a>
							</li>
						</ul>

						<ul class="nav navbar-nav navbar-right">
							
						</ul>
					</div>
				</div> 
				<div class="tab-content">
					<div class="tab-pane fade <?php if($id_tipodoc != '77' && $id_tipodoc != '88' && $id_tipodoc != '09') { echo 'active in'; } ?> has-padding" id="tab_docs_sunat">

						<div class="breadcrumb-line breadcrumb-line-component mb-2">
							<ul class="breadcrumb">
								<li>
									<img style="max-width: 30px;" src="/facturacionv8/img/sunat_logo.png" class="position-left"/>
									<span class="text-semibold">Estados SUNAT: </span>
								</li>
								<li id="inicio_lista"><i class="icon-checkmark font-weight-bold text-success position-left"></i> Aceptado</li>
								<li><i class="icon-blocked text-danger font-weight-bold position-left"></i> Rechazado</li>
								<li><i class="fa fa-refresh text-success font-weight-bold  position-left"></i> Pendiente de Envío</li>
								<li><i class="icon-cancel-square2 text-danger font-weight-bold  position-left"></i> Comunicación de Baja (Anulado)</li>
							</ul>
						</div>

						<div class="text-center">
							<h6 class="no-margin text-semibold">Proceso de Envío Automático de CPE en Lote</h6>
							<div class="progress content-group-sm" id="content_barra_progreso">
								<div class="progress-bar progress-bar-primary" data-transitiongoal-backup="75" data-transitiongoal="75" aria-valuemax="150" style="width: 0%">
									<span class="sr-only">0%</span>
								</div>
							</div>

							<div id="content_data_reporte_sunat" style="display:none;">

							</div>
						</div>
						
						{{ partial('reportedocumentos/tab_docs_sunat') }}
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12" id="content_validación_cpe" style="display:none;">
			<div class="panel">
				<div class="panel-body" id="content_tabla_informe_sunat">

				</div>
			</div>
		</div>
	</div>
</div>

<!-- Ventana para ver los items de las boletas -->
<div id="vm_comunicacionbaja" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="vm_content_comunicacionbaja">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title" id="vm_titulo_comunicacionbaja"></h5>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-styled-left alert-styled-custom alert-arrow-left alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Si existen FACTURAS, BOLETAS DE VENTA Y NOTAS DE CREDITO O DEBITO electrónicas que no han sido entregadas a sus clientes, pueden ser dadas de baja a través de una comunicación a la SUNAT, siempre que se cumpla lo siguiente: </span>
							<br /><br />
							<ul class="media-list">
			
								<li class="media" style="margin-top: 0px !important;">
									<div class="media-left">
										<a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
									</div>
									<div class="media-body">
										Que previamente hayan sido informadas a SUNAT y cuente con un CDR – ACEPTADO (Constancia de Recepción – Aceptada)
									</div>
								</li>

								<li class="media" style="margin-top: 0px !important;">
									<div class="media-left">
										<a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
									</div>
									<div class="media-body">
										Plazo para enviar la comunicación de baja: hasta las 7 días, contados desde el día siguiente de la fecha consignada en la Constancia de Recepción.
									</div>
								</li>

								<li class="media" style="margin-top: 0px !important;">
									<div class="media-left">
										<a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
									</div>
									<div class="media-body">
										Las Boletas, Notas Crédito y Débito relaciona a una Boleta son anuladas con un Resumen Diario a partir de la <a href="http://www.sunat.gob.pe/legislacion/superin/2017/117-2017.pdf" target="_blank">R. Superintendencia N° 117-2017.</a>
									</div>
								</li>
								
							</ul>
						</div>
						
					</div>
					<div class="col-md-12">
						<div class="content-group">
							<h6><i class="icon-notebook position-left"></i> Motivo de la Anulación:</h6>
							<div class="mb-15 mt-15">
								<input type="hidden" id="tipodoc" name="tipodoc" value="" />
								<input type="hidden" id="serie" name="serie" value="" />
								<input type="hidden" id="numero" name="numero" value="" />
								<textarea rows="5" cols="5" id="motivo" name="motivo" class="custom-textarea" placeholder="Escribe aquí el motivo para anular el presente documento"></textarea>
							</div>
						</div>
					</div>
					<!-- /basic datatable -->
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary btn_crear_comunicaciondebaja">Crear Comunicación de Baja</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para ver los items de las boletas -->

<div class="col-md-12 mb-20" id="button_actions_crear_comprobantes" style="display:none;">
	<div class="btn-group">
		<button type="button" onclick="refrescar_tabla()" class="btn btn-success btn-raised legitRipple"><i class="icon-reload-alt position-left"></i> </button>
		<button type="button" onclick="procesar_comprobantes()" class="btn btn-danger btn-raised legitRipple"><i class="icon-cog3 position-left"></i> Procesar Comprobantes</button>
		<button type="button" id="btn_emitir_documentos" class="btn bg-indigo dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="true">Emitir comprobante de pago <span class="caret"></span></button>
		<ul class="dropdown-menu dropdown-menu-right">
			<li>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo">
					<i class="fa fa-plus" aria-hidden="true"></i>
					<span>
						Emitir Factura Electrónica
					</span>
				</a>
			</li>
			<li>
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo">
					<i class="fa fa-plus" aria-hidden="true"></i>
					<span>
						Emitir Boleta Electrónica
					</span>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="/facturacionv8/documentoelectronico/index/07/nuevo">
					<i class="fa fa-plus" aria-hidden="true"></i>
					<span>
						Emitir Nota Crédito Electrónica
					</span>
				</a>
			</li>
			<li>
				<a href="/facturacionv8/documentoelectronico/index/08/nuevo">
					<i class="fa fa-plus" aria-hidden="true"></i>
					<span>
						Emitir Nota Débito Electrónica
					</span>
				</a>
			</li>
		</ul>
	</div>
</div>

		
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><img src="/facturacionv8/img/sunat_logo.png" class="position-left" style="width: 22px;"> Documentos Sunat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive" id="content_doc_pendientes">
					<table class="table datatable-basic" id="tbl_lista_doc_pendientes">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Comprobante</th>
								<th>Cliente</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<!-- Enviar Email al Cliente -->
<div id="vm_enviar_email" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="content_enviar_email_vm">
			<div class="modal-header bg-success-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Enviar CPE el Correo del Cliente</h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="email_idcontribuyente" id="email_idcontribuyente" value=""/>
				<input type="hidden" name="email_tipo_doc" id="email_tipo_doc" value=""/>
				<input type="hidden" name="email_serie_doc" id="email_serie_doc" value=""/>
				<input type="hidden" name="email_numero_comprobante" id="email_numero_comprobante" value=""/>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="label-form"><i class="icon-envelop mr-2"></i>Ingresa el Email del Cliente:</label>
							<input type="email" class="form-control form-control-sm" name="email_cliente" id="email_cliente" placeholder="Email">
						</div>
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_enviar_email" class="btn btn-info">Enviar Mensaje!</button>
			</div>
		</div>
	</div>
</div>
<!-- /Enviar Email al Cliente -->

<!-- Enviar Email al Cliente -->
<div id="vm_modificar_condicionpago" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="vm_modificar_condicionpago_content">
			<div class="modal-header bg-indigo-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-cash3"></i> &nbsp; Modificar Condición de Pago: </h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="vm_condicionpago_idcontribuyente" id="vm_condicionpago_idcontribuyente" value=""/>
				<input type="hidden" name="vm_condicionpago_tipo_doc" id="vm_condicionpago_tipo_doc" value=""/>
				<input type="hidden" name="vm_condicionpago_serie_doc" id="vm_condicionpago_serie_doc" value=""/>
				<input type="hidden" name="vm_condicionpago_numero_comprobante" id="vm_condicionpago_numero_comprobante" value=""/>
				<input type="hidden" name="vm_condicionpago_id_moneda" id="vm_condicionpago_id_moneda" value=""/>

				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-styled-left alert-bordered" id="mensaje_condicionpago">
						</div>
					</div>
					<div class="form-group col-md-6" id="content_preciounidad_sin_igv">
						<label for="producto_preciounidad_sin_igv">
							<span>Monto a Pagar: </span> 
							<span class="simbolo_moneda">S/.</span>
						</label>
						<div class="input-group">
							<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
							<input class="form-control monto_a_pagar" type="text" value="" name="monto_a_pagar" id="monto_a_pagar" />
						</div>
					</div>

					<div class="form-group col-md-6" id="content_preciounidad_sin_igv">
						<label for="producto_preciounidad_sin_igv">
							<span>Monto Adeudado: </span> 
							<span class="simbolo_moneda">S/.</span>
						</label>
						<div class="input-group">
							<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
							<input class="form-control monto_adeudado" type="text" value="" name="monto_adeudado" id="monto_adeudado" disabled />
						</div>
					</div>

					<div class="form-group col-md-12" id="fecha_proximo_pago" style="display: none;">
						<label><i class="icon-calendar2 position-left"></i> Fecha del Próximo Pago:</label>
						<input type="text" value="<?php echo date('d/m/Y'); ?>" name="nueva_fecha_pago" id="nueva_fecha_pago" placeholder="" class="form-control control_fecha">
					</div>

					<div class="col-md-12" id="content_resultado" style="display: none;">
					</div>
					
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_guardar_condicion" class="btn btn-info">Guardar!</button>
			</div>
		</div>
	</div>
</div>
<!-- /Enviar Email al Cliente -->

<!-- verificar estado en sunat -->
<div id="vm_verificar_estado_sunat" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="content_verificar_estado_sunat">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <img src="/facturacionv8/img/sunat_logo.png" style="max-height: 19px;" /> &nbsp; Verificar Estado en SUNAT de <strong id="estado_num_doc_22"></strong></h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="vestado_id_contribuyente" id="vestado_id_contribuyente" value=""/>
				<input type="hidden" name="vestado_tipo_doc_electronico" id="vestado_tipo_doc_electronico" value=""/>
				<input type="hidden" name="vestado_serie_comprobante" id="vestado_serie_comprobante" value=""/>
				<input type="hidden" name="vestado_numero_comprobante" id="vestado_numero_comprobante" value=""/>
	
				<div class="row">
					<div class="col-md-12" id="contenido_respuesta_sunat">
					</div>
					<div class="col-md-12" id="contenedor_numero_ticket">
						<div class="form-group">
							<label class="label-form "><i class="icon-user mr-2"></i>Número de Ticket:</label>
							<div class="input-group">
								<input type="text" name="codigo" id="txt_numero_ticket" class="form-control" placeholder="Número de Ticket"><span class="input-group-btn"><button class="btn bg-indigo legitRipple btn_guardar_nuevo_ticket" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Ticket</button></span>
							</div>
						</div>
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<div class="col-xs-3">
					<div class="btn-group">
						<button type="button" id="btn_recuperar_cdr" class="btn btn-success"> Get CDR</button>
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								Si el documento (Generalmente FACTURAS) se encuentra en Pendiente, pero ya está en SUNAT, entonces simplemente se debe recuperar el CDR utilizando esta opción.
							</li>
						</ul>
					</div>
				</div>
				
				<div class="col-xs-3">
					<div class="btn-group">
						<button type="button" id="btn_resetear_documento" class="btn btn-primary"> Reset. Doc.</button>
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								Utilizar esta opción cuando el documento NO SE ENCUENTRA EN SUNAT, sin embargo cuando se intenta reenviar aparece ERROR. Entonces Resetear el documento con esta opción.
							</li>
						</ul>
					</div>
				</div>
				
				<div class="col-xs-3">
					<div class="btn-group">
						<button type="button" id="btn_aprobar_manualmente" class="btn btn-danger">Apro. Manual</button>
						<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								La Aprobación Manual normalmente se utiliza con BOLETAS que ya fueron enviadas a SUNAT, sin embargo aún no se ha logrado recuperar el CDR, pues lo más probable es que SUNAT no haya retornado el número de ticket. Entonces se debe proceder con una aprobación manual.
							</li>
						</ul>
					</div>
				</div>

				<div class="col-xs-3">
					<div class="btn-group">
						<button type="button" id="btn_anular_manualmente" class="btn btn-danger">Anulación Manual</button>
						<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="icon-question4"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li style="padding: 10px;">
								Se Anulará de Forma Manual, únicamente válido para Facturas.
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /verificar estado en sunat -->

<!-- vm_cambiar_fecha_comprobante -->
<div id="vm_cambiar_fecha_comprobante" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="content_vm_tipo_certificado">
			<div class="modal-header bg-success-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Cambiar Fecha del Comprobante!</h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="vm_fcomprobante_id_contribuyente" id="vm_fcomprobante_id_contribuyente" value=""/>
				<input type="hidden" name="vm_fcomprobante_id_tipodoc_electronico" id="vm_fcomprobante_id_tipodoc_electronico" value=""/>
				<input type="hidden" name="vm_fcomprobante_serie_comprobante" id="vm_fcomprobante_serie_comprobante" value=""/>
				<input type="hidden" name="vm_fcomprobante_numero_comprobante" id="vm_fcomprobante_numero_comprobante" value=""/>
				<input type="hidden" name="vm_fcomprobante_modo" id="vm_fcomprobante_modo" value=""/>
				
				<div class="row">
					<div class="form-group col-md-12">
						<label><i class="icon-calendar2 position-left"></i> Fecha Comprobante:</label>
						<input type="text" name="vm_fcomprobante_fecha_comprobante" id="vm_fcomprobante_fecha_comprobante" placeholder="" class="form-control vm_fcomprobante_fecha_comprobante">
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_guardar_fecha_documento" class="btn btn-info">Guardar!</button>
			</div>
		</div>
	</div>
</div>
<!-- /vm_cambiar_fecha_comprobante -->