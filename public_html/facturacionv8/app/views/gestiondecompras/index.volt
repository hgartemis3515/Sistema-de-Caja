<style>
.box-compra {
    padding: .5em;
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: 700;
    text-align: center;
    /* -webkit-box-shadow: 0 1px 4px rgba(0,0,0,.2);
    box-shadow: 0 1px 4px rgba(0,0,0,.2); */
	box-shadow: 0 3px 6px 0 rgba(0,0,0,0.15);
	border-left: 2px solid #3F51B5;
    border-right: 2px solid #3F51B5;
}
.color-indigo{
	color: #3F51B5;
}
#content_resumen_doc_electronico {
    
    display: grid;
    justify-items: end;
}
.dataTables_filter > label:after {
	content: "";
}
.dataTables_paginate .paginate_button.current, .dataTables_paginate .paginate_button.current:hover, .dataTables_paginate .paginate_button.current:focus {
	color: #fff;
	background-color: #3F51B5!important;
} 
.display-none{
	display: none;
	transition: all 1s;
}
.head-compra {
    background: #F9FAFD;
    border-bottom: 2px solid #3F51B5;
}
.modal {
    background: #f9fafdb0!important;
}
.modal-body {
    position: relative;
    padding: 40px 20px;
}
.modal-body:before {
    content: "";
    position: absolute;
    top: 0px;
    right: 0px;
    border-width: 0 30px 32px 0;
    border-style: solid;
    border-color: #fff #fff #eaedf7 #eaedf7;
    background: #FFF;
    display: block;
    width: 0;
}
@media (min-width: 769px){
.modal-dialog {
    width: 800px;
    margin: 30px auto;
}
}
@media (min-width: 800px){
	.dataTables_filter > label:after {
	content: "\e98e";
	}
}
.buy-content.collapse {
	display: initial;
}
.buy-navbar >li {
    float: left;
}

/*.datatable-scroll-wrap{
	overflow-x: unset !important;
}

.table-responsive {
	overflow-x: unset !important;
}*/
@media only screen and (max-width: 400px) {
	.modal-dialog {
    position: relative;
    width: auto; 
    margin: 10px;
    height: 100%;
    overflow: inherit!important;
}
}
@media (min-width: 600px) and (max-width: 1000px) {
	.modal-dialog {
    position: relative;
    width: auto; 
    margin: 10px;
    height: 100%;
    overflow: inherit!important;
	}
}

.datatable-scroll-wrap {
    width: 100%;
    min-height: .01%;
    overflow-x: none !important;
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de Compras</span></h4>
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
		<!-- Opciones Avanzadas Búsqueda Compras -->
		<div class="col-md-12">
			<div class="panel" id="panel_rangofechas" style="max-width: 1120px;margin: 0 auto;">
				<form action="post" id="frm_filtros_busquedas">
					<div class="panel-body">
						<fieldset class="content-group">
							<legend class="text-bold">
								<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
								<span class="text-uppercase">Filtrar búsqueda:</span> 
							</legend>
						</fieldset>
						<div class="col-md-4 opc_controles_avanzados">
							<label class="label-form" for="select_tipofecha_busqueda"><i class="icon-file-empty position-left"></i> Mostrar facturas por:</label>
							<div class="form-group has-feedback has-feedback-left">
								<select name="select_tipofecha_busqueda" id="select_tipofecha_busqueda"
									data-placeholder="Selecciona un Periodo..." class="select_criterios">
									<option value="fecha_documento">Fecha del Documento</option>
									<option value="fecha_registro">Fecha de Registro</option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-calendar2 position-left"></i>
									Fecha inicio
								</label>
								<input type="text" value="<?php echo date('01/m/Y'); ?>" class="form-control form-control-sm control_fecha_inicio" name="fecha_inicio" id="fecha_inicio">
								<input type="hidden" name="fecha_inicio_valor" value="<?php echo date('Y-m-01'); ?>" id="fecha_inicio_valor" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-calendar2 position-left"></i>
									Fecha fin
								</label>
								<input type="text" value="<?php echo date('d/m/Y H:i:s'); ?>" class="form-control form-control-sm control_fecha_fin" name="fecha_fin" id="fecha_fin">
								<input type="hidden" name="fecha_fin_valor" value="<?php echo date('Y-m-d'); ?>" id="fecha_fin_valor" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="label-form">
									<i class="icon-profile position-left"></i> 
									Tipo de comprobante
								</label>
								<select class="multiselect" multiple="multiple" name="select_tipo_comprobante" id="select_tipo_comprobante">
									<option value="-" selected>Todos</option>
									<option value="03">BOLETAS</option>
									<option value="01">FACTURAS</option>
									<option value="99">ORDEN DE COMPRA</option>
									<option value="07">NOTAS DE CRÉDITO</option>
									<option value="08">NOTAS DE DÉBITO</option>
									<option value="00">OTROS DOCUMENTOS</option>
								</select>
							</div>
						</div>
						
						<div class="col-md-4 opc_controles_avanzados">
							<label class="label-form" for="select_sucursal"><i class="icon-store2 position-left"></i> Sucursal:</label>
							<div class="form-group has-feedback has-feedback-left">
								<select name="select_sucursal" id="select_sucursal" data-placeholder="Selecciona una Sucursal..." class="select_criterios">
									<option value="0" selected>Todas</option>
								</select>
							</div>
						</div>
						<div class="col-md-4 opc_controles_avanzados">
							<label class="label-form" for="select_vendedores"><i class="icon-users position-left"></i> Usuario:</label>
							<div class="form-group has-feedback has-feedback-left">
								<select name="select_vendedores" id="select_vendedores"
									data-placeholder="Selecciona un Criterio..." class="select_criterios">
									<option value="0">Todos</option>
								</select>
							</div>
						</div>
						<div class="col-md-12 text-right">
							<button class="btn bg-indigo legitRipple btn_busqueda_compra" id="btn_busqueda_compra" type="button">
								<i class="icon-search4 mr-2"></i>
							Buscar Comprobantes
							</button>
						</div>
					</div>
				</form>
			</div>
		</div> 
		<!-- /Opciones Avanzadas Búsqueda Compras -->

		<!-- Gráfica de ventas mensuales -->
		<div class="col-md-12" id="content_reportes" style="margin-top: 5px; display: none;">
			<div class="panel" style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-heading">
					<h5 class="panel-title">Gráfico de Compras Registradas <a class="heading-elements-toggle"><i
								class="icon-more"></i></a></h5>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<div style="height: 300px; width: 100%;" class="chart" id="grafico_compras_detalle"></div>
						</div>
						<div class="col-md-4">
							<div style="height: 300px; width: 100%;" class="chart" id="grafico_totales_compras"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Gráfica de ventas mensuales -->
		
		

		<div class="col-md-12" id="content_lista_docs_general">
			<div style="margin-top: 25px;"></div>

			<div class="content-group tab-content-bordered navbar-component" style="max-width: 1120px;margin: 0 auto;">
				<div class="navbar navbar-inverse bg-teal-400" style="position: relative; z-index: 30;">

					<div class="navbar-collapse collapse buy-content" id="demo1">
						<ul class="nav navbar-nav  buy-navbar">
							<li class="active">
								<a href="#tab_docs_compra" data-toggle="tab">
									<img src="/facturacionv8/img/sunat_logo.png" class="position-left" style="width: 22px;">CPE DE COMPRA - SUNAT
								</a>
							</li>
							<li>
								<a href="#tab_orden_compra" data-toggle="tab">
									<img src="/facturacionv8/public/img/icons/icon_orden_compra_64.png" class="position-left" style="width: 22px;">ORDEN DE COMPRA
								</a>
							</li>
							<li>
								<a href="#tab_otros_docs" data-toggle="tab">
									<i class="icon-file-text position-left"></i> COMPRAS SIN DOCUMENTO
								</a>
							</li>

						</ul>

						<ul class="nav navbar-nav navbar-right">
							
						</ul>
					</div>
				</div> 
				<div class="tab-content">
					<div class="tab-pane fade active in has-padding" id="tab_docs_compra">
						
						{{ partial('gestiondecompras/tab_docs_compra') }}
					</div>
					<div class="tab-pane fade has-padding" id="tab_orden_compra">
							{{ partial('gestiondecompras/tab_orden_compra') }}
					</div>
					<div class="tab-pane fade has-padding" id="tab_otros_docs">
							{{ partial('gestiondecompras/tab_otros_docs') }}
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="detalle_compra" tabindex="-1" role="dialog" aria-labelledby="detalle_compraLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-6 encabezado font-weight-bold">
						<p id="razon_social" class="text-uppercase"> </p>
						<p id="direccion_fiscal"></p>
						<p id="email"></p>
						<p id="fecha_comprobante"></p>
					</div>
					<div class="col-lg-6 encabezado">
						<div class="box-compra">
							<p id="ruc_empresa"></p>
							<p id="factura_empresa"></p>
							<p>
								<span id="compra_serie"> </span>
								<span> - </span>
								<span id="compra_num"> </span>
							</p>
						</div>
					</div>
					<div class="col-lg-12">
							<div class="table-responsive" style="overflow-x: unset !important;">
							<table class="table datatable-basic" id="tbl_lista_detalle_compra">
								<thead class="head-compra">
									<tr>
										<th>Cant.</th>
										<th>Descripción</th>
										<th>Tipo IGV</th>
										<th>Unidad</th>
										<th>Precio</th>
										<th>Subt</th>
										<th>IGV</th>
										<th>Importe</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="content-group" id="content_resumen_doc_electronico">
							<div class="table-responsive no-border">
								<table class="table">
									<tbody>
										<tr id="row_gravada_documento">
											<th>Total Gravada:</th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="gravada_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_exonerada_documento">
											<th>Total Exonerada:</th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="exonerada_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_inafecta_documento">
											<th>Total Inafecta:</th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="inafecta_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_exportacion_documento">
											<th>Total Exportación:</th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="exportacion_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_igv_documento">
											<th>IGV: <span class="text-regular">(18%)</span></th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="igv_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_gratuita_documento">
											<th>Gratuita:</th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="gratuita_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_icbper_documento">
											<th>Imp.ICBPER:</th>
											<td class="text-right">
												<span class="simbolo_moneda">S/.</span> <span id="icbper_documento">0.0</span>
											</td>
										</tr>
										<tr id="row_total_documento">
											<th>Total:</th>
											<td class="text-right text-primary"><h5 class="text-semibold">
												<span class="simbolo_moneda">S/.</span> <span id="total_documento">0.0</span></h5>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn bg-indigo" data-dismiss="modal">Cerrar</button>
				
			</div>
		</div>
	</div>
</div>