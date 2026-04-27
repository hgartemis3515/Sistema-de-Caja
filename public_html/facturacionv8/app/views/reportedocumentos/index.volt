<style>
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
.logo_documentos{
	margin-left: 0;
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
@media (min-width: 600px) and (max-width: 1024px){
	.button_date {
		float: initial!important; 
	}
	.button_date>li {
		float: initial; 
		text-align: center;
	}
}

</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Resumen de documentos emitidos</span></h4>
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
<div class="content" id="content_listado_documentos">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			
			<div class="col-md-12 mb-20" id="button_actions_crear_comprobantes" style="display:none;">
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
			<div class="col-md-12">
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
			</div>
			
			<div class="col-md-12">
				<div class="content-group tab-content-bordered navbar-component">
					<div class="navbar navbar-inverse bg-teal-400" style="position: relative; z-index: 30;">
						<div class="navbar-header logo_documentos">
							<a class="navbar-brand navbar-brand-original" href="javascript:void(0)"><img src="/facturacionv8/img/img_documentos.png" alt=""></a>

							<ul class="nav navbar-nav pull-right visible-xs-block">
								<li><a data-toggle="collapse" data-target="#demo1"><i class="icon-menu7"></i></a></li>
							</ul>
						</div>

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
							</ul>

							<ul class="nav navbar-nav navbar-right button_date margin-top-5">
								<li>
									<button type="button" class="btn btn-link daterange-ranges heading-btn text-semibold">
										<i class="icon-calendar3 position-left"></i> <span></span> <b class="caret"></b>
									</button>
								</li>
							</ul>
						</div>
					</div>
					<div class="tab-content">
						<div class="tab-pane fade <?php if($id_tipodoc != '77' && $id_tipodoc != '88' && $id_tipodoc != '09') { echo 'active in'; } ?> has-padding" id="tab_docs_sunat">
							{{ partial('reportedocumentos/tab_docs_sunat') }}
						</div>
						<div class="tab-pane fade <?php if($id_tipodoc == '77') { echo 'active in'; } ?> has-padding" id="tab_notas_venta">
							{{ partial('reportedocumentos/tab_notas_venta') }}
						</div>
						<div class="tab-pane fade <?php if($id_tipodoc == '88') { echo 'active in'; } ?> has-padding" id="tab_cotizaciones">
							{{ partial('reportedocumentos/tab_cotizaciones') }}
						</div>
						<div class="tab-pane fade <?php if($id_tipodoc == '09') { echo 'active in'; } ?> has-padding" id="tab_guias_remision">
							{{ partial('reportedocumentos/tab_guias_remision') }}
						</div>
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