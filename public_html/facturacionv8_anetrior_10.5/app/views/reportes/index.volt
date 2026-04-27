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
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Libro Electrónico de Ventas</span></h4>
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
<div class="content">
	<div class="row">
		<!-- Panel Comisiones Pendientes de Pago -->
		<div class="col-md-12">
			<div class="panel" id="panel_rangofechas">
				<div class="panel-body">
					<h6 class="text-semibold">Selecciona un Rango de Fechas</h6>
					<p>Selecciona un rango de fechas y luego haz click en el botón GENERAR, se creará el formato del libro electrónico de fechas con todos los documentos creados en el rango de fechas seleccionados.</p>
					<div class="col-md-5">
						<label for="rangofechas"><i class="icon-calendar2 position-left"></i>Rango de Fechas:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-calendar3"></i></span>
							<input type="hidden" id="fechainicio" name="fechainicio" value="">
							<input type="hidden" id="fechafinal" name="fechafinal" value="">
							<input name="rangofechas" id="rangofechas" type="text"
								class="rangofechas form-control daterange-buttons" value="">
						</div>
					</div>
					<div class="col-md-5">
						<label for="select_criterio_visualizacion"><i class="icon-calendar position-left"></i>Visualizar
							por:</label>
						<div class="form-group has-feedback has-feedback-left">
							<select name="select_criterio_visualizacion" id="select_criterio_visualizacion"
								data-placeholder="Selecciona un Criterio..." class="select_producto_buscar">
								<option value="dia">Día</option>
								<option value="mes">Mes</option>
								<option value="anio">Año</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<label></label>
						<button type="button" id="btn_generar_reporte" style="margin-top: 25px;" target="_blank"
							class="btn bg-indigo font-weight-bold text-uppercase">
							<i class=" icon-search4"></i> Generar
							</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /Panel Comisiones Pendientes de Pago -->

		<!-- Gráfica de ventas mensuales -->
		<div class="col-md-12" id="content_reportes" style="display:none; margin-top: 25px;">
			<div class="panel" style="max-width: 1120px; margin: 0 auto;">
				<div class="panel-heading">
					<h5 class="panel-title">Registro de Ventas <a class="heading-elements-toggle"><i
								class="icon-more"></i></a></h5>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="row text-center">
							<div class="col-md-3">
								<div class="content-group">
									<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/factura.svg"
											style="width: 25px;"> <span id="total_facturas"></span></h5>
									<span class="text-muted text-size-small">Total con Facturas</span>
								</div>
							</div>

							<div class="col-md-3">
								<div class="content-group">
									<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/boleta.svg"
											style="width: 25px;"> <span id="total_boletas"></span></h5>
									<span class="text-muted text-size-small">Total en Boletas</span>
								</div>
							</div>

							<div class="col-md-3">
								<div class="content-group">
									<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/nota_credito.svg"
											style="width: 25px;"> <span id="total_notas_credito"></span></h5>
									<span class="text-muted text-size-small">Total en Notas Crédito</span>
								</div>
							</div>

							<div class="col-md-3">
								<div class="content-group">
									<h5 class="text-semibold no-margin"><img src="/facturacionv8/img/nota_debito.svg"
											style="width: 25px;"> <span id="total_notas_debito"></span></h5>
									<span class="text-muted text-size-small">Total con Notas Débito</span>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="height: 300px; width: 100%;" class="chart" id="grafico_ventas"></div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Gráfica de ventas mensuales -->


		<!-- Detalle de Documentos Emitidos -->
		<div class="col-md-12" id="content_reportes" style="margin-top: 25px;">
			<div class="panel">
				<div class="panel-heading">
					<h5 class="panel-title">Libro Electrónico de Ventas <a class="heading-elements-toggle"><i
								class="icon-more"></i></a></h5>
				</div>

				<div class="panel-body">

					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive p-20">
								<table class="table table-striped" id="tbl_detalle_documentos">
									<thead>
										<tr>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">ID</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="5">DATOS DEL COMPROBANTE</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="2">DOCUMENTO AFECTADO</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="3">DATOS DEL CLIENTE</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="9">IMPORTES DE LA OPERACIÓN</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">ESTADO COMPROBANTE</th>
										</tr>
										<tr>
											<!-- 1 -->

											<th class="borde-cells">F.EMISIÓN</th>
											<th class="borde-cells">F.VENCIMIENTO</th>
											<th class="borde-cells">TIPO DOC</th>
											<th class="borde-cells">SERIE</th>
											<th class="borde-cells">NUMERO</th>

											<th class="borde-cells">SERIE</th>
											<th class="borde-cells">NUMERO</th>

											<th class="borde-cells">T.DOC.</th>
											<th class="borde-cells">NUMERO DOC.</th>
											<th class="borde-cells">NOMBRE/RAZÓN</th>

											<th class="borde-cells">MONEDA</th>
											<th class="borde-cells">T.C.</th>
											<th class="borde-cells">OP.GRAVADA</th>
											<th class="borde-cells">OP.INAFECTA</th>
											<th class="borde-cells">OP.EXONERADA</th>
											<th class="borde-cells">OP.EXPORTACIÓN</th>
											<th class="borde-cells">IGV</th>
											<th class="borde-cells">PRECIO VENTA</th>
											<th class="borde-cells">OP.GRATUITAS</th>

											<!-- 19 -->
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
		<!-- /Detalle de Documentos Emitidos -->
	</div>
</div>
