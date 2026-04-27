<style>
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
		
	.daterangepicker {z-index:1151 !important;}
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Resúmen de Boletas</span></h4>
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
		<div class="col-md-12">
			<div class="alert alert-styled-left alert-styled-custom alert-arrow-left alpha-success alert-bordered">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Solo podrá enviar el RESUMEN DIARIO DE BOLETAS generados en los últimos 7 días. Debes tener en cuenta lo siguiente: </span>
				<br /><br />
				<ul class="media-list">

					<li class="media" style="margin-top: 0px !important;">
						<div class="media-left">
							<a href="#" class="btn text-white btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
						</div>
						
						<div class="media-body">
							El Resumen Diario son enviados con la información solicitada por SUNAT.
						</div>
					</li>

					<li class="media" style="margin-top: 0px !important;">
						<div class="media-left">
							<a href="#" class="btn text-white btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
						</div>
						
						<div class="media-body">
							Los Resumenes Diario apartir de la <a target="_blank" href="https://www.sunat.gob.pe/legislacion/superin/2017/117-2017.pdf">R. Superintendencia N° 117-2017</a> se pueden enviar independientes o agrupadas.
						</div>
					</li>

					<li class="media" style="margin-top: 0px !important;">
						<div class="media-left">
							<a href="#" class="btn text-white btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
						</div>
						
						<div class="media-body">
							Existen 3 tipos de estados: Adicionar, Modificar y Anulado
						</div>
					</li>

					<li class="media" style="margin-top: 0px !important;">
						<div class="media-left">
							<a href="#" class="btn text-white btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
						</div>
						
						<div class="media-body">
							Solo puede crear 99,999 resúmenes diario por día. Si supera esta cantidad se recomienda enviar un resumen diario agrupado al final de día.
						</div>
					</li>

				</ul>
			</div>
			
		</div>
		<div class="col-lg-12 col-md-12">
			<div class="btn-group">
				<div class="mb-2">
					<a href="javascript:void(0)" class="btn_listar_boletas btn btn-primary legitRipple " >
						<i class="fa fa-plus mr-2"></i>
						Crear nuevo resumen de boletas
					</a>
				</div>
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
					<li><i class="fa fa-refresh text-orange font-weight-bold  position-left"></i> Pendiente de Envío</li>
				</ul>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_panel">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Comprobantes de pago</span>
						</legend>
					</fieldset>
					
						<div class="col-md-12">
							<div class="table-responsive p-20">
								<table class="table datatable-basic" id="tbl_lista_resumenes">
									<thead>
										<tr>
											<th>Fecha Envío</th>
											<th>Fecha Documento</th>
											<th>Documentos</th>
											<th>Ticket</th>
											<th style="max-width: 10px;">PDF</th>
											<th style="max-width: 10px;">XML</th>
											<th style="max-width: 10px;">CDR</th>
											<th>Sunat</th>
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
</div>


<!-- Ventana para crear resumen de boletas -->
<div id="vm_lista_boletas" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="content_vm_listaboletas">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Listar Comprobantes</h5>
			</div>

			<div class="modal-body">
				<div class="form-group col-md-12">
					<button type="button" style="float: right; margin-top: 27px;" class="btn bg-indigo btn-icon legitRipple btn_extraer_lista_documentos"><i class="icon-search4" id="btn_extraer_lista_documentos"></i></button>
					<div style="overflow: hidden; padding-right: .5em;">
						<label><i class="icon-barcode2 position-left"></i>Selecciona una Fecha:<span class="text-danger">*</span></label>
						<input type="text" name="fecha_registro_boletas" id="fecha_registro_boletas" placeholder="" class="form-control control_fecha">
					</div>​
				</div>

				<div class="col-md-12" style="margin-bottom: 40px;">
					<table class="table datatable-basic" id="tbl_lista_boletas">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Tipo</th>
								<th>Serie</th>
								<th>Número</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody id="tbl_lista_boletas_rows">
						</tbody>
					</table>
				</div>
				<!-- /basic datatable -->
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary btn_crear_resumen">Crear Resúmen de Boletas</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para crear resumen de boletas -->


<!-- Ventana para ver los items de las boletas -->
<div id="vm_items_resumen" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="content_items_resumen">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Resumen: <strong id="identificador_resumen"></strong></h5>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12" style="margin-bottom: 40px;">
						<table class="table datatable-basic" id="tbl_lista_items_resumen" style="width: 100%;">
							<thead>
								<tr>
									<th>Fecha</th>
									<th>Tipo</th>
									<th>Serie - Número</th>
									<th>Cliente</th>
									<th>Total</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody id="tbl_lista_items_body">
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para ver los items de las boletas -->