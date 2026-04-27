<style>

.btn-default{
	color: #333!important;
	background-color: #fff!important;
	background-image: none!important;
	border: 1px solid #ddd!important;
	font-weight: normal!important; 
	padding: 5px 12px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
	right: 0px;
}
.overflow-auto{
	overflow: auto;

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
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
	border-top: 1px solid #ddd;
}

.table>thead>tr>th {
	border: 1px solid #ddd;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
	padding: 12px 20px;
	line-height: 1.5384616;
	vertical-align: middle;
	border: 1px solid #ddd;
	text-align: center;
	text-transform: uppercase;
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Liquidación de Impuesto Mensual</span></h4>
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
		<div class="col-md-12 col-md-12">
			<div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body" id="content_panel_liquidacion_mensual">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
							<span class="text-uppercase">Configuración:</span> 
						</legend>
					</fieldset>
					<form name="frm_reporte_liquidacion_mensual" id="frm_reporte_liquidacion_mensual" action="">
						<input type="hidden" value="<?php echo $contribuyente->mype; ?>" name="contribuyente_mype" id="contribuyente_mype" />
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form"><i class="icon-file-text2 position-left"></i> Liquidación de Impuestos:</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" id="opt_mype_tributario" name="opt_mype_tributario" class="control-success opt_mype_tributario">
											MYPE Tributario < 300 UIT
										</label>
									</div>
								</div>	
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form"><i class="icon-file-text2 position-left"></i> Renta 3ra Categoría (COEFICIENTE):</label>
									<input type="text" name="renta_3ra_coeficiente" id="renta_3ra_coeficiente" value="" class="form-control">
								</div>	
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form"><i class="icon-file-text2 position-left"></i> Renta 3ra Categoría (PORCENTAJE %):</label>
									<input type="text" name="renta_3ra_porcentaje" id="renta_3ra_porcentaje" value="1" class="form-control">
								</div>	
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha inicio
									</label>
									<input type="text" value="<?php echo date('01/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_inicio" id="fecha_inicio">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="label-form">
										<i class="icon-calendar2 position-left"></i>
										Fecha fin
									</label>
									<input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_fin" id="fecha_fin">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<br />
									<button class="btn bg-indigo legitRipple btn_generar_reporte" type="button">Generar Reporte</button>
								</div>
							</div>
							
						</div>
					</form>
				</div>
			</div>
			<div style="margin-top: 25px;"></div>
			<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body"  id="content_list_clientes">
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
							<li class="active">
								<a href="#resumen_impuestos" data-toggle="tab" class="legitRipple" aria-expanded="true">
									<i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Resumen</span> 
								</a>
							</li>
							<li class="">
								<a href="#libro_e_ventas" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-cloud-upload fa-lg position-left"></i> Libro Elec. Ventas</a>
							</li>
							<li class="">
								<a href="#libro_e_compras" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="icon-circle-code position-left"></i> Libro Elec. Compras</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="resumen_impuestos">
								
							</div>

							<div class="tab-pane" id="libro_e_ventas">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive p-20">
											<table class="table table-striped" id="tbl_detalle_documentos">
												<thead>
													<tr>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">N° CORRELATIVO</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">FECHA EMISIÓN</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">FECHA VCTO.</th>
			
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="3">COMPROBANTE DE PAGO</th>
														
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="2">DOC. IDENTIDAD</th>
														
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">NOMBRE O RAZÓN SOCIAL</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">VALOR EXPORT.</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">BASE IMP. OPER.GRAV.</th>

														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">EXONERADO</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">INAFECTO</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">BASE IMP.</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">IGV</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">I.S.C</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">IGV e IPM</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">OTROS TRIBUTOS</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">IMPORTE TOTAL</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">IMP. TOTAL EN M.E.</th>
														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">[T/C]</th>

														<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="4">REFERENCIAS COMPROBANTE DE PAGO</th>

													</tr>
													<tr>
														<!-- 1 -->
														<!-- 2 -->
														<!-- 3 -->
			
														<!-- DATOS DEL COMPROBANTE -->
														<!--4--><th class="borde-cells">TIPO</th>
														<!--5--><th class="borde-cells">SERIE</th>
														<!--6--><th class="borde-cells">NUMERO</th>

														<!-- DOCUMENTO DE IDENTIDAD -->
														<!--7--><th class="borde-cells">TIPO</th>
														<!--8--><th class="borde-cells">NÚMERO</th>

														<!-- REFERENCIAS COMPROBANTE DE PAGO -->
														<th class="borde-cells">FECHA</th>
														<th class="borde-cells">TIPO</th>
														<th class="borde-cells">SERIE</th>
														<th class="borde-cells">NUMERO</th>
														
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="tab-pane" id="libro_e_compras">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="label-form">
												<i class="icon-barcode2 mr-2"></i> Token <span class="text-danger">*</span>
											</label>
											<div class="input-group">
												<input type="text" name="token_contribuyente" id="token_contribuyente" class="form-control" placeholder="token_contribuyente" value="<?php echo $contribuyente->token; ?>">
												<span class="input-group-btn">
													<button class="btn bg-indigo legitRipple btn_generar_token" type="button">
														<i class="icon-rotate-ccw3 mr-2"></i> Generar Token
													</button>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="footer text-muted">
		© 2021. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>
	