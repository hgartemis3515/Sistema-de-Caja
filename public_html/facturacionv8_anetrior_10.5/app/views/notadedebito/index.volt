<style>
.buttons{
	margin-bottom: 1em;
	text-align: center;
}
.buttons button{
	margin-bottom: .7em;
}
.btn-primary {
	color: #fff;
	background-color: #00BCD4;
	border-color: #00BCD4;
}
.btn-primary:hover {
	color: #fff;
	background-color: rgb(0, 143, 161);
	border-color:rgb(0, 143, 161);
}
.btn {
	position: relative;
	font-weight: 500;
	text-transform: uppercase;
	border-width: 0;
	padding: 5px;
}
.dataTables_filter > label:after {
	display: none;
}
.form-group {
	margin-bottom: 9px;
	position: relative;
}
.form-group div[class*="col-lg-"]:not(.control-label) + div[class*="col-lg-"] {
    margin-top: 0px;
}
.mt-2{
	margin-top: 1.5em;
}
.nav-tabs > li > a,
.panel-heading h6
	{
	text-transform: uppercase;
	font-weight: 700!important;
	font-size: 14px;
}
.nav-tabs {
    margin-bottom: 0px;
}
.nav-tabs.nav-tabs-highlight > li.active > a,
	.nav-tabs.nav-tabs-highlight > li.active > a:hover, .nav-tabs.nav-tabs-highlight > li.active > a:focus {
	border-top-color: #00BCD4;
}
.page-title {
	padding: 14px;
}
.page-title h5{
	font-size: 16px;
}
.page-title h4{
	font-size: 23px;
}
.resumen{
	border-bottom: 1px solid #ddd;
	text-align: right;
	font-weight: 700;
}
.resumen-total{
	font-size: 18px;
	text-align: right;
	font-weight: 700;
}
.text-document-color{
	color: #00BCD4;
}
.title-total{
	font-size: 16px;
	text-transform: uppercase;
}
@media (min-width: 800px){
	.btn {
		position: relative;
		font-weight: 500;
		text-transform: uppercase;
		border-width: 0;
		padding: 8px 17px;
	}
	.dataTables_filter > label:after {
		display: inherit;
	}
}
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Documento eléctronico: Nota de Débito</span></h4>
		</div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
				<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
			</div>
		</div>
	</div>
</div>
<div class="content header-top">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-header page-header-light" style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
				<div class="page-header-content border-bottom-2 border-bottom-danger">
					<div class="page-title text-center">
						<h5 class="font-weight-bold text-uppercase">
							Documento Eléctronico:
						</h5>
						<h4 class="font-weight-bold text-document-color text-uppercase">
							Nota de Débito
						</h4>
					</div>
				</div>
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<form name="frm_notadebito" id="frm_notadebito" action="">
						<div class="d-flex cont-pt2">
							<div class="row">
								<div class="col-lg-3 col-sm-6 col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="fa fa-caret-right mr-2"></i>
											Serie
										</label>
										<select class="select2" name="serie" id="serie">
											<option selected value="">Seleccione una opción</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="fa fa-caret-right mr-2"></i>
											Número
										</label>
										<input name="num_notadebito" id="num_notadebito" type="text" class="form-control" value="01">
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="fa fa-caret-right mr-2"></i>
											Moneda
										</label>
										<select class="select2" name="id_cod_moneda" id="id_cod_moneda">
											<option selected value="">Seleccione una opción</option>
											<?php                                    
												foreach ($list_moneda as $moneda) {
													echo "<option value='".$moneda->id_codigomoneda."'>".$moneda->nombre."</option>";
												}
											?> 
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-md-6">
									<div class="form-group">
										<label class="label-form">
											<i class="fa fa-caret-right mr-2"></i>
											Fecha Emisión
										</label>
										<input name="fecha_emision" id="fecha_emision" type="text" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex">
							<div class="row">
								<div class="col-lg-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h6 class="panel-title"><i class="icon-pencil mr-2"></i> Documento a modificar</h6>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-lg-4 col-sm-6 col-md-6 col-6">
													<div class="form-group">
														<label class="label-form">
															<i class="fa fa-caret-right mr-2"></i>
															Tipo de documento
														</label>
														<select class="select2" name="type_document_edit" id="type_document_edit">
															<option selected value="">Seleccione una opción</option>
															<?php                                    
																foreach ($tipo_identidad as $value) {
																	echo "<option value='".$value->nombre."'>".$value->nombre."</option>";
																}
															?>
														</select>
													</div>
												</div>
												<div class="col-lg-4 col-sm-6 col-md-6 col-6">
													<div class="form-group">
														<label class="label-form">
															<i class="fa fa-caret-right mr-2"></i>
															Serie
														</label>
														<input name="serie_edit" id="serie_edit" type="text" class="form-control">
													</div>
												</div>
												<div class="col-lg-4 col-sm-6 col-md-6 col-6">
													<div class="form-group">
														<label class="label-form">
															<i class="fa fa-caret-right mr-2"></i>
															Número
														</label>
														<input name="num_edit" id="num_edit" type="text" class="form-control">
													</div>
												</div>
												<div class="col-lg-12 col-sm-6 col-md-6 col-6">
													<div class="form-group">
														<label class="label-form">
															<i class="fa fa-caret-right mr-2"></i>
															Motivo
														</label>
														<select class="select2" name="motivo_edit" id="motivo_edit">
															<option selected value="">Seleccione una opción</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h6 class="panel-title"><i class="icon-user mr-2"></i> Clientes</h6>
										</div>
										<div class="panel-body">
											<div class="col-lg-6 col-sm-4 col-md-4 col-4">
												<div class="form-group">
													<label class="label-form">
														<i class="fa fa-caret-right mr-2"></i>
														Tipo de documento
													</label>
													<select class="select2" name="type_document_client" id="type_document_client">
														<option selected value="">Seleccione una opción</option>
														<?php                                    
															foreach ($tipo_identidad as $value) {
																echo "<option value='".$value->nombre."'>".$value->nombre."</option>";
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-lg-6 col-sm-4 col-md-4 col-4">
												<div class="form-group">
													<label class="label-form">
														<i class="fa fa-caret-right mr-2"></i>
														Número de documento
													</label>
													<input name="numdoc_client" id="numdoc_client" type="text" class="form-control">
												</div>
											</div>
											<div class="col-lg-12 col-sm-4 col-md-4 col-4">
												<div class="form-group">
													<label class="label-form">
														<i class="fa fa-caret-right mr-2"></i>
														Nombre
													</label>
													<input name="nombre_client" id="nombre_client" type="text" class="form-control">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex">
							<div class="panel panel-flat">
								<div class="panel-body">
									<div class="tabbable">
										<ul class="nav nav-tabs nav-tabs-highlight">
											<li class="active"><a href="#detalle_notadebito" data-toggle="tab" class="legitRipple">Detalle</a></li>
											<li><a href="#guia_emision" data-toggle="tab" class="legitRipple">Guía de Remisión</a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="detalle_notadebito">
												<div class="table-responsive">
													<div class="buttons float-right">
														<button class="btn btn-warning mr-2"><i class="icon-pencil mr-2"></i>Editar</button>
														<button class="btn btn-primary mr-2"><i class="icon-plus3 mr-2"></i>Agregar</button>
														<button class="btn btn-danger mr-2"><i class="icon-cross2 mr-2"></i>Eliminar</button>
													</div>
													<table class="table datatable-basic" id="tabla_user">
														<thead>
															<tr>
																<th>#</th>
																<th>Producto</th>
																<th>Unidad</th>
																<th>Tipo IGV</th>
																<th>Precio</th>
																<th>Cantidad</th>
																<th>Sub total</th>
																<th>IGV</th>
																<th>Impuesto</th>
															</tr>
														</thead>
														<tbody>		
															<tr>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<div class="tab-pane" id="guia_emision">
												<div class="table-responsive">
													<div class="buttons float-right">
														<button class="btn btn-warning mr-2"><i class="icon-pencil mr-2"></i>Editar</button>
														<button class="btn btn-primary mr-2"><i class="icon-plus3 mr-2"></i>Agregar</button>
														<button class="btn btn-danger mr-2"><i class="icon-cross2 mr-2"></i>Eliminar</button>
													</div>
													<table class="table datatable-basic" id="tabla_user">
														<thead>
															<tr>
																<th>#</th>
																<th>Producto</th>
																<th>Unidad</th>
																<th>Tipo IGV</th>
																<th>Precio</th>
																<th>Cantidad</th>
																<th>Sub total</th>
																<th>IGV</th>
																<th>Impuesto</th>
															</tr>
														</thead>
														<tbody>		
															<tr>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
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
						<div class="d-flex mt-10">
							<div class="row">
								<div class="col-lg-6 col-md-12 col-xl-6 col-12 col-sm-6">
									<div class="form-group">
										<label class="label-form">
											<i class="fa fa-caret-right mr-2"></i>
											Observación
										</label>
										<textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
									</div>
								</div>
								<div class="col-lg-6 col-md-12 col-xl-6 col-12 col-sm-6">
									<label class="label-form">
										<i class="fa fa-caret-right mr-2"></i>
										Resumen:
									</label>
									<div class="content-resumen">
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Descuento:
													</label>
												</div>
												<div class="col-lg-8">
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Total Exportación:
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen">4000.$</p>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Total Exonerada:
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen">4000.$</p>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Total inafecta: 
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen">4000.$</p>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Total gravada: 
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen">4000.$</p>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Total IGV: 
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen">4000.$</p>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Total gratuita:
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen">4000.$</p>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form">
														<i class="icon-circle-small mr-2"></i>
														Otros cargos (+):
													</label>
												</div>
												<div class="col-lg-8">
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-4">
													<label class="label-form title-total">
														<i class="icon-circle-small mr-2"></i>
														Total:
													</label>
												</div>
												<div class="col-lg-8">
													<p class="resumen-total">4000.$</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-lg-12 col-md-12 col-xl-12 col-12 col-sm-12">
									<div class="form-group text-center mt-2">
										<button class="btn btn-info btn-lg legitRipple btn_product" type="button">
											<i class=" icon-paperplane  mr-2"></i>
											Guardar y enviar a Sunat
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>