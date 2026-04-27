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
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Resumen de documentos emitidos</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="mb-2">
				<div class="btn-group">
					<button type="button" class="btn btn-info dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="true">Emitir comprobante de pago <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li>
							<a href="#">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<span>
									Emitir Factura Electrónica
								</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<span>
									Emitir Boleta Electrónica
								</span>
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<span>
									Emitir Nota Crédito Electrónica
								</span>
							</a>
						</li>
						<li>
							<a href="">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<span>
									Emitir Nota Débito Electrónica
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="breadcrumb-line breadcrumb-line-component mb-2">
				<ul class="breadcrumb">
					<li><i class="icon-checkmark font-weight-bold text-success position-left"></i> Aceptado</li>
					<li><i class="icon-blocked text-danger font-weight-bold position-left"></i>Anulado</li>
					<li><i class="fa fa-refresh text-orange font-weight-bold  position-left"></i> Volver a Enviar</li>
					<li><i class="fa fa-send text-primary font-weight-bold position-left"></i> Por Enviar</li>
					<li><i class="icon-pencil7 text-indigo font-weight-bold mr-2"></i>Editable</li>
				</ul>
			</div>
			<div class="panel">
				<div class="panel-body" id="content_panel">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Lista de Comprobantes de pago</span>
						</legend>
					</fieldset>
					
						<div class="col-md-12">
							<div class="table-responsive p-20">
								<table class="table datatable-basic" id="tabla_user">
									<thead>
										<tr>
											<th>Fecha</th>
											<th>Tipo de comprobante</th>
											<th>Serie-Nro.</th>
											<th style="min-width: 250px;">Cliente</th>
											<th>Total</th>
											<th style="max-width: 10px;">PDF</th>
											<th style="max-width: 10px;">XML</th>
											<th style="max-width: 10px;">CDR</th>
											<th>Sunat</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>04/10/2018</td>
											<td>Nota de Débito Electrónica</td>
											<td>F020-1</td>
											<td >10432738006 VALLES OJEDA MICHAEL RAUL</td>
											<td>30.00 S/</td>
											<td><a href=""><i class="fa fa-file-pdf-o"></i></a></td>
											<td><a href=""><i class="fa fa-file-excel-o"></i></a></td>
											<td><a href=""><i class="fa fa-file-text-o"></i></a></td>
											<td><i class="fa fa-check-circle-o"></i></td>
											<td class="text-center">
												<ul class="icons-list">
													<li class="dropdown">
														<a href="#" class="dropdown-toggle" data-toggle="dropdown">
															<i class="icon-menu9"></i>
														</a>
														<ul class="dropdown-menu dropdown-menu-right">
															<li><a href="#"><i class="icon-pencil4"></i> Editar</a></li>
															<li><a href="#"><i class="icon-user-cancel"></i> Eliminar</a></li>
														</ul>
													</li>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
	$('#home').addClass('sidebar-xs');
});
</script>