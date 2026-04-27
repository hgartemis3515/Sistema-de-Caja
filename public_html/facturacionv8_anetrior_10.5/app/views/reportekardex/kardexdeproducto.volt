<style>
	.font-weight-bold{
		font-weight: bold!important;
	}

	.tooltip.fade.top.in{
		position: absolute;
		top: -29px;
		left: 1216.12px;
	}
	.tooltip_small {
		width: 150px;
		color: #fff;
		text-align: center;
		background-color: #333;
		border-radius: 3px;
		position: absolute;
		top: -50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}
	.tooltip_small .tooltip-inner {
		padding: 7px 12px;
	}

	.tooltip_small .tooltip-arrow {
		position: absolute;
		width: 0;
		height: 0;
		border-color: transparent;
		border-style: solid;
	}

	.tooltip_small .tooltip-arrow {
		bottom: -4px;
		left: 50%;
		margin-left: -4px;
		border-width: 4px 4px 0;
		border-top-color: #333;
	}
	.tool_tip{
		position: relative;
	}
	.tool_tip:hover{
		cursor: grab;
	}
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reporte Kardex</span></h4>
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
		<div class="col-md-12 col-md-12">
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_panel_kardex">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Reporte Kardex / Kardex Individual</span> 
                        </legend>
                        </fieldset>
						<form name="frm_kardex" id="frm_kardex" action="">
							<input type="hidden" name="idprod_buscar" id="idprod_buscar" value="<?php echo $idproducto; ?>" >
							<input type="hidden" name="nombreprod_buscar" id="nombreprod_buscar" value="<?php echo $nombreproducto; ?>" >
							<div class="row">

								<div class="col-lg-3">
									<div class="form-group">
										<label  class="label-form">
											<i class="icon-profile position-left"></i>
											Selecciona la Sucursal
										</label>
										<select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select select_sucursal" name="select_sucursal" id="select_sucursal" required>
											<?php
											foreach($lista_sucursales as $sucursal) {
												if($sucursal->idsucursal == $sucursal_seleccionada) {
													echo '<option value="'.$sucursal->idsucursal.'" selected>'.$sucursal->nombre.'</option>';
												} else {
													echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>

								<div class="col-lg-6">
									<div class="form-group">
										<label  class="label-form">
											<i class="fa fa-shopping-bag mr-2"></i>
											Producto
										</label>
										<div class="form-group has-feedback has-feedback-left" id="contenedor_select_productos">
											<select name="select_producto_buscar" id="select_producto_buscar" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar">
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label class="label-form">
											<i class="icon-barcode2  mr-2"></i>
											Código
										</label>
										<input type="text" name="codigo" id="codigo" class="form-control" placeholder="Código" disabled="disabled">
									</div>
								</div>



								<div class="col-lg-4">
									<div class="form-group">
										<label  class="label-form">
											<i class="fa fa-calendar mr-2"></i>
											Fecha Inicio
										</label>
										<input type="text" id="control_fecha_inicio" class="form-control control_fecha_inicio" value="<?php echo $fecha_inicio; ?>">
										<input type="hidden" id="fecha_inicio_valor" class="form-control" value="<?php echo $fecha_inicio_2; ?>">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label  class="label-form">
											<i class="fa fa-calendar mr-2"></i>
											Fecha Final
										</label>
										<input type="text" id="control_fecha_fin" class="form-control control_fecha_fin" value="<?php echo $fecha_fin; ?>">
										<input type="hidden" id="fecha_fin_valor" class="form-control" value="<?php echo $fecha_fin_2; ?>">

									</div>
								</div>
								
								<div class="col-lg-4">
									<div class="form-group">
										<label  class="label-form">
											<i class="icon-cash2 position-left"></i>
											Módulo de Valoración
										</label>
										<select name="select_modulo_valoracion" id="select_modulo_valoracion" class="select select_modulo_valoracion">
											<option value="promedio" selected>Promedio Ponderado</option>
										</select>
									</div>
								</div>

								<div class="col-lg-12">
									<div class="float-right">
										<button class="btn bg-indigo legitRipple btn_generar_kardex" type="button">
											<i class="icon-floppy-disk mr-2"></i>
											Generar Kardex
										</button>
									</div>
								</div>
							</div>
						</form>
				</div>
			</div>
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_lista_kardex">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-list mr-2" aria-hidden="true"></i>
							<span class="font-weight-bold text-uppercase">Reporte Kardex</span>
						</legend>
					</fieldset>
					<div class="row content-table">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table datatable-basic" id="tbl_kardex">
									<thead>
										<tr>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">N°</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">Fecha</th>
											<th style="min-width: 200px; border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">Detalle</th>

											<th style="min-width: 200px; border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">Nota</th>

											<th class="bg-success-400" style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="3">ENTRADAS</th>
											<th class="bg-primary-400" style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" colspan="3">SALIDAS</th>

											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">Stock</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">C.Prom.Unit.</th>
											<th style="border-top: 1px solid #e9eaea !important;" align="center" class="borde-cells" rowspan="2">Stock Valorizado</th>
										</tr>
										<tr>
											<th>Cantidad</th>
											<th>C.Unit.</th>
											<th>C.Total</th>
											<th>Cantidad</th>
											<th>C.Unit.</th>
											<th>C.Total</th>
										</tr>
									</thead>
									<tbody id="content_tbl_kardex"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer text-muted">
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>
<!-- Ingreso sin Doc -->
<div id="vm_ingreso_sindoc" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
                <h5 class="modal-title" id="editar_nota_modalLabel"><i class="icon-pencil position-left"></i>Ingresar Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body">
				<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cupiditate sit mollitia odio, nam praesentium, quae impedit facere doloribus quo dolore a nostrum ad asperiores. Ex eius et quis explicabo eaque.</p>
				<table class="table">
					<thead>
						<tr>
							<th>Uni. Medica</th>
							<th>Cantidad</th>
							<th>Precio Com</th>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</thead>
				</table>
				<p><span class="font-weight">Observación: </span> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam neque praesentium dolore quas dolorum magnam accusantium suscipit aliquam rem temporibus odit reiciendis blanditiis iusto, laudantium cupiditate harum similique fugit! Est.</p>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" id="btn_guardar_plantilla" class="btn btn-primary">Aceptar</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ingreso sin Doc modal -->
<!-- Ingreso con Doc -->
<div id="vm_ingreso_doc" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
                <h5 class="modal-title" id="editar_nota_modalLabel"><i class="icon-pencil position-left"></i>Ingresar factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body">
				<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cupiditate sit mollitia odio, nam praesentium, quae impedit facere doloribus quo dolore a nostrum ad asperiores. Ex eius et quis explicabo eaque.</p>
				<!-- Preview de PDF -->
				<div class="pdf_preview"><embed src="https://arpsystem.com.pe/facturacionv8/printpdf/?file=FcFW1Ofd3pq56O6RlQKdl0ohgbJ21wJkvU6ViQ%3D%3D" type="application/pdf" width="100%" height="300px" /></div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" id="btn_guardar_plantilla" class="btn btn-primary">Aceptar</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ingreso con Doc modal -->