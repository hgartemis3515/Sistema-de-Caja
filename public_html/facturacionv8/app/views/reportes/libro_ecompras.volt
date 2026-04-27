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
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Libro Electrónico de Compras</span></h4>
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
					<div class="col-md-4 col-xs-6">
						<label class="label-form" for="filtro_tipo_fecha"><i class="icon-calendar position-left"></i>Buscar Por:</label>
						<div class="form-group has-feedback has-feedback-left">
							<select name="filtro_tipo_fecha" id="filtro_tipo_fecha" data-placeholder="Selecciona un Criterio..." class="select_criterio_visualizacion filtro_tipo_fecha">
								<option value="fecha_emision" selected>Fecha de Emisión</option>
								<option value="fecha_registro">Fecha de Registro</option>
							</select>
						</div>
					</div>
					<div class="col-md-4 col-xs-6">
						<label class="label-form" for="rangofechas"><i class="icon-calendar2 position-left"></i>Fecha Documentos:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-calendar3"></i></span>
							<input type="hidden" id="fechainicio" name="fechainicio" value="">
							<input type="hidden" id="fechafinal" name="fechafinal" value="">
							<input name="rangofechas" id="rangofechas" type="text"
								class="rangofechas form-control daterange-buttons" value="">
						</div>
					</div>
					<div class="col-md-4 col-xs-6">
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

					<div class="col-md-4 col-xs-6">
						<div class="form-group has-feedback has-feedback-left">
							<label class="label-form"><i class="icon-profile position-left"></i>Selecciona la Sucursal <span class="text-danger">*</span></label>
							<select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select_criterio_visualizacion select idsucursal" name="idsucursal" id="idsucursal" required>
								<option value="0">Todas</option>
							</select>
						</div>
					</div>

					<div class="form-group col-md-4 col-xs-6">
						<label class="label-form"><i class="icon-calendar2 position-left"></i> Periodo:</label>
						<select class="select form-control select_periodo" name="select_periodo" id="select_periodo">
							<?php
							echo $options_select;
							?>
						</select>
					</div>
					
					<div class="col-md-12 text-right btn-options">
						<button style="margin-right: 25px;" type="button" id="btn_generar_reporte" target="_blank"
							class="btn bg-indigo font-weight-bold text-uppercase">
							<i class=" icon-search4"></i> Generar Reporte
							</a>
						</button>
						<a style="display: none;" href="/facturacionv8/reportes/generar_txt_libcompras/" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_generar_txt"><b style="padding: 10px !important;"><i class="icon-plus-circle2"></i></b> Generar TXT - SUNAT</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /Panel Comisiones Pendientes de Pago -->

		<!-- Detalle de Documentos de compra -->
		<div class="col-md-12" id="content_reportes" style="margin-top: 25px;">
			<div class="panel">
				<div class="panel-heading">
					<h5 class="panel-title">Libro Elect. Compras / Régimen: <?php echo $regimen->nombre; ?></h5>
				</div>

				<div class="panel-body">

					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive p-20">
								<table class="table table-striped" id="tbl_detalle_documentos">
									<thead>
										<tr>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">1</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">2</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">3</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">4</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">5</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">6</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">7</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">8</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">9</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">10</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">11</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">12</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">13</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">14</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">15</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">16</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">17</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">18</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">19</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">20</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">21</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">22</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">23</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">24</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">25</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">26</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">27</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">28</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">29</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">30</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">31</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">32</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">33</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">34</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">35</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">36</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">37</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">38</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">39</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">40</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">41</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">42</th>
											<th style="border: none !important;font-size: 9px;" align="center" class="borde-cells">43</th>
										</tr>
										<tr>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">PERIODO</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">COD.UNIC.</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">REGIMEN</th>

											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="7">DATOS DEL COMPROBANTE</th>
											
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="3">INFORMACIÓN PROVEEDOR</th>
											
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="13">IMPORTES DE LA OPERACIÓN</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="5">DOCUMENTO MODIFICADO</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="4">DETRAC. Y RETENC.</th>

											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="8">OTROS</th>
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
											<!--8--><th class="borde-cells">DUA/DSI</th>
											<!--9--><th class="borde-cells">NUMERO</th>
											<!--10--><th class="borde-cells">O.D.N.C.F</th> <!-- Importe Total de las Operaciones diarias que no otorgen derecho a crédito fiscal en forma consolidada, registrar número final-->
											<!-- /DATOS DEL COMPROBANTE -->

											<!--INFORMACIÓN PROVEEDOR-->
											<!--11--><th class="borde-cells">T.DOC.</th>
											<!--12--><th class="borde-cells">NUMERO</th>
											<!--13--><th class="borde-cells">RAZÓN SOCIAL</th>
											<!--/INFORMACIÓN PROVEEDOR-->

											<!--IMPORTES DE LA OPERACIÓN-->
											<!--14--><th class="borde-cells">OP.GRAVADA</th>
											<!--15--><th class="borde-cells">IGV</th>
											<!--16--><th class="borde-cells">SALD.EXPORT.</th> <!-- Base imponible de las adquisiciones gravadas que no dan derecho a crédito fiscal y/o saldo a favor por exportación, por no estar destinadas a operaciones gravadas y/o de exportación.-->
											<!--17--><th class="borde-cells">IMP.PROM.MUN.</th> <!-- Monto del Impuesto General a las Ventas y/o Impuesto de Promoción Municipal -->
											<!--18--><th class="borde-cells">OP.GRAV.NO.CF.</th>
											<!--19--><th class="borde-cells">MONT.IMP.PROM.MUN.</th><!-- Monto del Impuesto General a las Ventas y/o Impuesto de Promoción Municipal -->
											<!--20--><th class="borde-cells">OP.NO.GRAVADA</th>
											<!--21--><th class="borde-cells">ISC</th>
											<!--21--><th class="borde-cells">ICBPER</th>
											<!--22--><th class="borde-cells">OTRO.TRIBUTOS.</th> <!--OP.GRAVADA + IGV + OTROS-->
											<!--23--><th class="borde-cells">TOTAL</th>
											<!--24--><th class="borde-cells">MONEDA</th>
											<!--25--><th class="borde-cells">T.C.</th>
											<!--/IMPORTES DE LA OPERACIÓN -->


											<!--26--><th class="borde-cells">FEC.COMP.MODIF.</th> <!-- FECHA DE EMISIÓN DEL COMPROBANTE QUE MODIFICA -->
											<!--27--><th class="borde-cells">TIPO.DOC.MODIF.</th>
											<!--28--><th class="borde-cells">SERIE.DOC.MODIF.</th>
											<!--29--><th class="borde-cells">DUA.DOC.MODIF.</th>
											<!--30--><th class="borde-cells">NUM.DOC.MODIF.</th>
											
											<!--31--><th class="borde-cells">FEC.DETRAC.</th>
											<!--32--><th class="borde-cells">NUM.DETRAC.</th>
											<!--33--><th class="borde-cells">MARC.COMP.RETENC.</th>
											<!--34--><th class="borde-cells">CLASIF.BIENES.SERV.</th>
											
											<!--35--><th class="borde-cells">ID.CONTR.</th>
											<!--36--><th class="borde-cells">ERR.T.C.</th>
											<!--37--><th class="borde-cells">ERR.PROV.N.H</th>
											<!--38--><th class="borde-cells">ERR.PROV.EX.</th>
											<!--39--><th class="borde-cells">ERROR.DNI</th>
											<!--40--><th class="borde-cells">COMP.M.P</th>
											<!--41--><th class="borde-cells">ESTADO</th>
											<!--42--><th class="borde-cells">CAMP.LIB.</th>
											
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