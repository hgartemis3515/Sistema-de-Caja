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
				<p>Selecciona el rango de fechas de emisión de los comprobantes electrónico, luego has click en Generar para poder visualizar el reporte.</p>
				<div class="col-md-3 col-xs-6">
					<label class="label-form" for="rangofechas"><i class="icon-calendar2 position-left"></i>Fecha de Emisión:</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="icon-calendar3"></i></span>
						<input type="hidden" id="fechainicio" name="fechainicio" value="">
						<input type="hidden" id="fechafinal" name="fechafinal" value="">
						<input name="rangofechas" id="rangofechas" type="text"
							class="rangofechas form-control daterange-buttons" value="">
					</div>
				</div>
				<div class="col-md-3 col-xs-6">
					<label class="label-form" for="tipo_documento"><i class="icon-calendar position-left"></i>Tipo de Comprobante:</label>
					<div class="form-group has-feedback has-feedback-left">
						<select name="tipo_documento" id="tipo_documento"
							data-placeholder="Selecciona un Criterio..." class="select_criterio_visualizacion tipo_documento">
							<option value="-1">Todos</option>
							<option value="01">Facturas</option>
							<option value="03">Boletas</option>
							<option value="07">Notas de Crédito</option>
							<option value="08">Notas de Débito</option>
						</select>
					</div>
				</div>

				<div class="col-md-3 col-xs-6">
					<div class="form-group has-feedback has-feedback-left">
						<label class="label-form"><i class="icon-profile position-left"></i>Selecciona la Sucursal <span class="text-danger">*</span></label>
						<select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select_criterio_visualizacion select idsucursal" name="idsucursal" id="idsucursal" required>
							<option value="0">Todas</option>
						</select>
					</div>
				</div>
				
				<div class="col-md-3 col-xs-6">
					<label class="label-form" for="select_criterio_visualizacion"><i class="icon-calendar position-left"></i>Régimen:</label>
					<div class="form-group has-feedback has-feedback-left">
						<select name="regimen_empresa" id="regimen_empresa"
							data-placeholder="Selecciona un Criterio..." class="regimen_empresa select_criterio_visualizacion">
							<option value="<?php echo $regimen->idregimen; ?>" selected><?php echo $regimen->nombre; ?></option>
						</select>
					</div>
				</div>
				<div class="col-md-12 text-right btn-options">
					<button type="button" id="btn_generar_reporte" target="_blank"
						class="btn bg-indigo font-weight-bold text-uppercase">
						<i class=" icon-search4"></i> Generar Reporte
						</a>
					</button>
					<div class="btn-group botones_opciones_extra" style="display: none;">
						<button type="button" class="btn bg-teal-400 ml-2 btn-raised dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false">Opciones <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a class="btn_generar_txt" href="/facturacionv8/reportes/generar_txt_libventas/"><i class="icon-menu7"></i> Generar TXT SUNAT</a></li>
							<li><a class="btn_generar_excel" href="/facturacionv8/reportes/generar_excel_libventas/"><img src="/facturacionv8/img/sunat_logo.png"> Formato SUNAT - Excel</a></li>
							<li><a class="btn_generar_excel_ejb" href="/facturacionv8/reportes/generar_excel_ventas_ejb/"><img src="https://arpsystem.com.pe/facturacionv8/herramientas/verimage/imguser-62644df176e63-f520c93802085bea28050d20a573e870.png" style="width: 83px;"> Fomato EJB</a></li>
							<li><a class="btn_asiento_contable" href="/facturacionv8/reportes/get_asiento_contable/"><img src="https://arpsystem.com.pe/facturacionv8/herramientas/verimage/imguser-62644e14548ae-c05fe5b455f573d5e38e675a61ecfd8c.png" style="width: 78px;"> Asiento Contable</a></li>
						</ul>
					</div>

					<a style="display: none;" href="/facturacionv8/reportes/generar_txt_libventas/" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_generar_txt"><b style="padding: 10px !important;"><i class="icon-file-text"></i></b> Generar TXT - SUNAT</a> 
					<a style="display: none;" href="/facturacionv8/reportes/generar_excel_libventas/" class="btn btn-info btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_generar_excel"><b style="padding: 10px !important;"><i class="icon-plus-circle2"></i></b> Generar EXCEL - SUNAT</a>
				</div>
			</div>
		</div>
	</div>
	<!-- /Panel Comisiones Pendientes de Pago -->

	<!-- Detalle de Documentos de compra -->
	<div class="col-md-12" id="content_reportes" style="margin-top: 25px;">
		<div class="panel">
			<div class="panel-heading">
				<h5 class="panel-title">Formato de Libro Electrónico de Ventas <a class="heading-elements-toggle"><i
							class="icon-more"></i></a></h5>
			</div>

			<div class="panel-body">

				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive p-20">
							<table class="table table-striped" id="tbl_detalle_documentos">
								<thead>
									<tr>
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">PERIODO</th>
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">COD.UNIC.</th>
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">REGIMEN</th>

										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="6">DATOS DEL COMPROBANTE</th>
										
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="3">INFORMACIÓN CLIENTE</th>
										
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="15">IMPORTES DE LA OPERACIÓN</th>
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="4">DOCUMENTO MODIFICADO</th>

										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="5">OTROS</th>
										<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">ESTADO COMP.</th>
									</tr>
									<tr>
										<!-- 1 -->
										<!-- 2 -->
										<!-- 3 -->

										<!-- DATOS DEL COMPROBANTE -->
										<!--4--><th class="borde-cells">F.EMISIÓN</th>
										<!--5--><th class="borde-cells">F.VENCIMIENTO</th>
										<!--6--><th class="borde-cells">TIPO DOC</th>
										<!--7--><th class="borde-cells">SERIE</th>
										<!--8--><th class="borde-cells">NUMERO</th>
										<!--9--><th class="borde-cells">NUM.MAQ.REG.</th> <!-- Importe Total de las Operaciones diarias que no otorgen derecho a crédito fiscal en forma consolidada, registrar número final-->
										<!-- /DATOS DEL COMPROBANTE -->

										<!--INFORMACIÓN PROVEEDOR-->
										<!--10--><th class="borde-cells">T.DOC.</th>
										<!--11--><th class="borde-cells">NUMERO</th>
										<!--12--><th class="borde-cells">RAZÓN SOCIAL</th>
										<!--/INFORMACIÓN PROVEEDOR-->

										<!--IMPORTES DE LA OPERACIÓN-->
										<!--13--><th class="borde-cells">OP.EXPORT.</th>
										<!--14--><th class="borde-cells">OP.GRAVADA</th>
										<!--15--><th class="borde-cells">DESCUENT.</th>
										<!--16--><th class="borde-cells">IGV</th>
										<!--17--><th class="borde-cells">DESC.IGV</th>
										<!--18--><th class="borde-cells">OP.EXONERADA</th>
										<!--19--><th class="borde-cells">OP.INAFECTA</th>
										<!--20--><th class="borde-cells">ISC</th>
										<!--21--><th class="borde-cells">OP.ARROZ.P.</th>
										<!--22--><th class="borde-cells">IMP.ARROZ.P.</th>
										<!--22--><th class="borde-cells">ICBPER</th>
										<!--23--><th class="borde-cells">OTRO.TRIBUTOS.</th>
										<!--24--><th class="borde-cells">TOTAL</th>
										
										<!--25--><th class="borde-cells">MONEDA</th>
										<!--26--><th class="borde-cells">T.C.</th>
										<!--/IMPORTES DE LA OPERACIÓN-->


										<!--27--><th class="borde-cells">FEC.COMP.MODIF.</th> <!-- FECHA DE EMISIÓN DEL COMPROBANTE QUE MODIFICA -->
										<!--28--><th class="borde-cells">TIPO.DOC.MODIF.</th>
										<!--29--><th class="borde-cells">SERIE.DOC.MODIF.</th>
										<!--30--><th class="borde-cells">NUM.DOC.MODIF.</th>
										

										<!--31--><th class="borde-cells">ID.CONTR.</th>
										<!--32--><th class="borde-cells">ERR.T.C.</th>
										<!--33--><th class="borde-cells">COMP.M.P</th>
										<!--34--><th class="borde-cells">ESTADO</th>
										<!--35--><th class="borde-cells">CAMP.LIB.</th>
										
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
	<!-- /Detalle de Documentos de compra -->
</div>
</div>