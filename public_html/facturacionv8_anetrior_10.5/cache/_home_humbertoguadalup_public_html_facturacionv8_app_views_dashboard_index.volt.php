<style>
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

tr.shown td.details-control {
    background: url(https://cdn.jsdelivr.net/gh/DataTables/DataTables@6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png) no-repeat center center;
}
td.details-control {
    background: url(https://cdn.jsdelivr.net/gh/DataTables/DataTables@6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png) no-repeat center center;
    cursor: pointer;
}

</style>
<div class="content" id="contenido_documentos_totales">
	<?php echo $html_suscripcion; ?>
	<div class="row">
		<div class="col-md-12" style="margin-top: 15px;">
			<!-- Toolbar -->
			<div class="navbar navbar-default navbar-component navbar-xs">
				<ul class="nav navbar-nav visible-xs-block">
					<li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
				</ul>

				<div class="navbar-collapse collapse" id="navbar-filter">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#activity" data-toggle="tab"><i class="icon-menu7 position-left"></i> Dashboard</a></li>
						<li><a href="#schedule"  data-toggle="modal" data-target="#exampleModal"><i class="icon-calendar3 position-left"></i> Pendientes <span class="badge badge-success badge-inline position-right" id="docs_pendientes_envio"></span></a></li>
					</ul>

					<div class="navbar-right">
						<ul class="nav navbar-nav">
							<li><a href="/facturacionv8/documentoelectronico/index/01/nuevo"><img src="/facturacionv8/img/factura.svg" style="width: 18px;"/> Crear Factura</a></li>
							<li><a href="/facturacionv8/documentoelectronico/index/03/nuevo"><img src="/facturacionv8/img/boleta.svg" style="width: 18px;"/> Crear Boleta</a></li>
							<li><a href="/facturacionv8/documentoelectronico/index/77/nuevo"><img src="/facturacionv8/img/nota_venta.svg" style="width: 18px;"/> Nota de Venta</a></li>
							<li><a href="/facturacionv8/documentoelectronico/index/88/nuevo"><img src="/facturacionv8/img/cotizacion.svg" style="width: 18px;"/> Cotización</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-plus-circle2"></i> <span class="visible-xs-inline-block position-right"> Options</span> <span class="caret"></span></a>
								<ul class="dropdown-menu dropdown-menu-right" style="width: 210px;">
									<li><a href="/facturacionv8/documentoelectronico/index/07/nuevo"><img src="/facturacionv8/img/nota_credito.svg" style="width: 18px;"/> Crear Nota de Crédito</a></li>
									<li><a href="/facturacionv8/documentoelectronico/index/08/nuevo"><img src="/facturacionv8/img/nota_debito.svg" style="width: 18px;"/> Crear Nota de Débito</a></li>
									<li class="divider"></li>
									<li><a href="/facturacionv8/guiaderemision"><i class="icon-make-group"></i> Crear Guía de Remisión</a></li>
									<li><a href="/facturacionv8/guiatransportista"><i class="icon-make-group"></i> Crear Guía Transportista</a></li>
									<li class="divider"></li>
									<li><a href="/facturacionv8/documentoelectronico/index/77/nuevo"><img src="/facturacionv8/img/nota_venta.svg" style="width: 18px;"/> Crear Nota de Venta</a></li>
									<li><a href="/facturacionv8/documentoelectronico/index/88/nuevo"><img src="/facturacionv8/img/cotizacion.svg" style="width: 18px;"/> Crear Cotización</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- /toolbar -->
		</div>

		<!-- Panel Comisiones Pendientes de Pago -->
		<div class="col-md-12" <?php if($ocultar_opciones_avanzadas_dashboard == 'si'){echo " style='display:none;'";} ?>>
			<div class="panel" id="panel_rangofechas">
				<div class="panel-body">
					<div class="col-md-12 text-right">
						<div class="content_btn_opc_avanzadas1" style="padding-top: 0px !important;">
							<div class="content_btn_opc_avanzadas2" style="margin-top: 5px;">
								<div class="content_btn_opc_avanzadas3">
									<label class="checkbox-inline checkbox-switchery checkbox-right switchery-xs">
										<input type="checkbox" id="btn_opciones_avanzadas" class="switch2 btn_opciones_avanzadas">
										Ver Opciones Avanzadas:
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12 opc_controles_avanzados">
						<h6 class="text-semibold">Opciones Avanzadas</h6>
						<p>A continuación puedes seleccionar los diferentes criterios para mostrar los gráficos y tablas...</p>
					</div>
					
					<form id="frm_filtros">
					<div class="col-md-2 opc_controles_avanzados">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-calendar2 position-left"></i>
								Fecha inicio
							</label>
							<input type="text" value="<?php echo $rango_fechas_dashboard['fecha_inicio_txt']; ?>" class="form-control form-control-sm control_fecha_inicio" name="fecha_inicio" id="fecha_inicio">
							<input type="hidden" name="fecha_inicio_valor" value="<?php echo $rango_fechas_dashboard['fecha_inicio_valor']; ?>" id="fecha_inicio_valor" />
						</div>
					</div>
					
					<div class="col-md-2 opc_controles_avanzados">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-calendar2 position-left"></i>
								Fecha fin
							</label>
							<input type="text" value="<?php echo $rango_fechas_dashboard['fecha_fin_txt']; ?>" class="form-control form-control-sm control_fecha_fin" name="fecha_fin" id="fecha_fin">
							<input type="hidden" name="fecha_fin_valor" value="<?php echo $rango_fechas_dashboard['fecha_fin_valor']; ?>" id="fecha_fin_valor" />
						</div>
					</div>

					<div class="col-md-3 opc_controles_avanzados">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-profile position-left"></i>
								Sucursal
							</label>
							<select name="select_sucursal_filtro" id="select_sucursal_filtro" class="multiselect select_filtros_avanzados" multiple="multiple">
								<?php
								if($select_sucursal == 0) {
									echo '<option value="0" selected>Todos</option>';
								}
								foreach($lista_sucursales as $sucursal) {
									if($select_sucursal == $sucursal->idsucursal) {
										echo '<option value="'.$sucursal->idsucursal.'" selected>'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
									} else {
										echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					
					<div class="col-md-3 opc_controles_avanzados">
						<div class="form-group">
							<label class="label-form">
								<i class="icon-users mr-2"></i>
								Vendedor
							</label>
							<select name="select_vendedor_filtro" id="select_vendedor_filtro" class="multiselect select_filtros_avanzados" multiple="multiple">
								<?php
								if($select_vendedor == 0) {
									echo '<option value="0" selected>Todos</option>';
								}
								foreach($lista_usuarios as $usuario) {
									if($usuario->idusuario == $select_vendedor) {
										echo '<option value="'.$usuario->idusuario.'" selected>'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
									} else {
										echo '<option value="'.$usuario->idusuario.'">'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2 opc_controles_avanzados">
						<div class="form-group">
							<label for="select_periodo"><i class="icon-calendar position-left"></i> Periodo:</label>
							<div class=" has-feedback has-feedback-left">
								<select name="select_periodo" id="select_periodo"
									data-placeholder="Selecciona un Periodo..." class="select_criterios">
									<option value="dia">Día</option>
									<option value="mes">Mes</option>
									<option value="anio">Año</option>
								</select>
							</div>
						</div>
					</div>

					</form>

				</div>
			</div>
		</div>
		<!-- /Panel Comisiones Pendientes de Pago -->

		<!-- Gráfica de ventas mensuales -->
		<div class="col-md-12" id="content_reportes" style="<?php if(!$mostrar_graficos) {echo "display:none;";} ?> margin-top: 5px;">
			<div class="panel">
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
										style="width: 25px;"> - <span id="html_total_notas_credito_soles"></span></h5>
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
						
						<div class="col-md-2 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/nota_venta.svg"
										style="width: 25px;"> <span id="html_total_notas_venta_soles"></span></h5>
								<span class="text-muted text-size-small">Total con Notas de Venta</span>
							</div>
						</div>

						<div class="col-md-2 col-xs-6">
							<div class="content-group">
								<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/money.svg"
										style="width: 25px;"> <span id="html_total_neto_soles"></span></h5>
								<span class="text-muted text-size-small">Total Neto</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-8">
							<div style="height: 350px; width: 100%;" class="chart" id="grafico_ventas_detalle_soles"></div>
						</div>
						<div class="col-md-4">
							<div style="height: 350px; width: 100%;" class="chart" id="grafico_tipos_venta_soles"></div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<!-- /Gráfica de ventas mensuales -->

		<div class="col-md-12" id="content_lista_docs_general">
			<div class="content-group tab-content-bordered navbar-component">
				<div class="navbar navbar-inverse bg-teal-400" style="position: relative; z-index: 30;">

					<div class="navbar-collapse collapse" id="demo1">
						<ul class="nav navbar-nav">
							<li class="<?php if($id_tipodoc != '77' && $id_tipodoc != '88' && $id_tipodoc != '09') { echo 'active'; } ?>">
								<a href="#tab_docs_sunat" data-toggle="tab">
									<img src="/facturacionv8/img/sunat_logo.png" class="position-left" style="width: 22px;">
									Documentos SUNAT
								</a>
							</li>

							<li class="<?php if($id_tipodoc == '77') { echo 'active'; } ?>">
								<a href="#tab_notas_venta" data-toggle="tab"><i class="icon-file-text position-left"></i>
									Notas de Venta
								</a>
							</li>

							<li class="<?php if($id_tipodoc == '88') { echo 'active'; } ?>">
								<a href="#tab_cotizaciones" data-toggle="tab">
									<i class="icon-file-empty position-left"></i>
									Cotizaciones
								</a>
							</li>

							<li class="<?php if($id_tipodoc == '09') { echo 'active'; } ?>">
								<a href="#tab_guias_remision" data-toggle="tab"><i class="icon-file-check position-left"></i>
									Guías de Remisión
								</a>
							</li>

							<li class="<?php if($id_tipodoc == '31') { echo 'active'; } ?>">
								<a href="#tab_guias_transportista" data-toggle="tab"><i class="icon-file-check position-left"></i>
									Guías Transportista
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
									<img style="width: 30px;" src="/facturacionv8/img/sunat_logo.png" class="position-left"/>
									<span class="text-semibold">Estados SUNAT: </span>
								</li>
								<li id="inicio_lista"><i class="icon-checkmark font-weight-bold text-success position-left"></i> Aceptado</li>
								<li><i class="icon-blocked text-danger font-weight-bold position-left"></i> Rechazado</li>
								<li><i class="fa fa-refresh text-success font-weight-bold  position-left"></i> Pendiente de Envío</li>
								<li><i class="icon-cancel-square2 text-danger font-weight-bold  position-left"></i> Comunicación de Baja (Anulado)</li>
							</ul>
						</div>
						
						<?= $this->partial('reportedocumentos/tab_docs_sunat') ?>
					</div>
					<div class="tab-pane fade <?php if($id_tipodoc == '77') { echo 'active in'; } ?> has-padding" id="tab_notas_venta">
						<?= $this->partial('reportedocumentos/tab_notas_venta') ?>
					</div>
					<div class="tab-pane fade <?php if($id_tipodoc == '88') { echo 'active in'; } ?> has-padding" id="tab_cotizaciones">
						<?= $this->partial('reportedocumentos/tab_cotizaciones') ?>
					</div>
					<div class="tab-pane fade <?php if($id_tipodoc == '09') { echo 'active in'; } ?> has-padding" id="tab_guias_remision">

						<div class="breadcrumb-line breadcrumb-line-component mb-2">
							<ul class="breadcrumb">
								<li>
									<img style="width: 30px;" src="/facturacionv8/img/sunat_logo.png" class="position-left"/>
									<span class="text-semibold">Estados SUNAT: </span>
								</li>
								<li id="inicio_lista"><i class="icon-checkmark font-weight-bold text-success position-left"></i> Aceptado</li>
								<li><i class="icon-blocked text-danger font-weight-bold position-left"></i> Rechazado</li>
								<li><i class="fa fa-refresh text-success font-weight-bold  position-left"></i> Pendiente de Envío</li>
								<li><i class="icon-cancel-square2 text-danger font-weight-bold  position-left"></i> Comunicación de Baja (Anulado)</li>
							</ul>
						</div>
						
						<?= $this->partial('reportedocumentos/tab_guias_remision') ?>
					</div>
					<div class="tab-pane fade <?php if($id_tipodoc == '31') { echo 'active in'; } ?> has-padding" id="tab_guias_transportista">

						<div class="breadcrumb-line breadcrumb-line-component mb-2">
							<ul class="breadcrumb">
								<li>
									<img style="width: 30px;" src="/facturacionv8/img/sunat_logo.png" class="position-left"/>
									<span class="text-semibold">Estados SUNAT: </span>
								</li>
								<li id="inicio_lista"><i class="icon-checkmark font-weight-bold text-success position-left"></i> Aceptado</li>
								<li><i class="icon-blocked text-danger font-weight-bold position-left"></i> Rechazado</li>
								<li><i class="fa fa-refresh text-success font-weight-bold  position-left"></i> Pendiente de Envío</li>
								<li><i class="icon-cancel-square2 text-danger font-weight-bold  position-left"></i> Comunicación de Baja (Anulado)</li>
							</ul>
						</div>
						
						<?= $this->partial('reportedocumentos/tab_guias_transportista') ?>
					</div>
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

	<button type="button" id="btn_modal_importar_cpe" onclick="modal_importar_cpe()" style="margin-right: 10px;" class="btn bg-teal-400 btn-labeled btn-rounded legitRipple"><b><i class="icon-file-upload"></i></b> Crear CPE Masivo</button>

	<div class="btn-group">
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
<!-- Modal de Entrada -->

<?= $this->partial('dashboard/modal_aviso') ?>

<?= $this->partial('dashboard/enviar_email') ?>

<?= $this->partial('cuentasporcobrar/lista_abonos') ?>

<?= $this->partial('dashboard/verificar_estado_sunat') ?>

<?= $this->partial('dashboard/modal_whatsapp') ?>

<?= $this->partial('dashboard/importar_cpe') ?>

<?= $this->partial('dashboard/modal_etiquetas') ?>

<?= $this->partial('dashboard/crear_gre_masivo') ?>