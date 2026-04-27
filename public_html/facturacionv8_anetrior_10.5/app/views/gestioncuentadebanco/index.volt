<div class="page-header">
	<div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión cuenta de banco</span></h4>
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
<div class="content" style="max-width: 1100px; margin: 0 auto;">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="panel border-top-indigo">
				<div class="panel-body" id="content_panel_cuentabanco">
                    <form name="frm_cuentabanco" id="frm_cuentabanco" action="">
                        <fieldset class="content-group"><legend class="text-bold"><i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Agregar/editar cuenta de banco</span> </legend></fieldset>
                        <input type="hidden" class="form-control form-control-sm" name="idcuentabanco" id="idcuentabanco"> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-cash2 position-left"></i>
                                    Moneda
                                </label>
                                <select class="form-control" name="moneda" id="moneda">
                                    <option value="" selected>Selecciona una moneda</option>
                                    <?php                                    
                                        foreach ($moneda as $value) {
                                            echo "<option value='".$value->id_codigomoneda."'>".$value->nombre."</option>";
                                        }
                                    ?>
								</select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-file-text2 position-left"></i>
                                    Tipo de cuenta
                                </label>
                                <select name="tipo_cuenta" id="tipo_cuenta" class="form-control">
                                    <option value="" selected>Selecciona un tipo</option>
                                    <option value="cuenta_corriente">Cuenta Corriente</option>
                                    <option value="cuenta_ahorro">Cuenta Ahorro</option>
                                    <option value="cuenta_detracciones">Cuenta de Detracción</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-cash2 position-left"></i>
                                    Entidad Financiera
                                </label>
                                <select class="form-control" name="entidad_financiera" id="entidad_financiera">
                                    <?php                                    
                                        foreach ($entidad_financiera as $value) {
                                            if($value->id_entidadfinanciera == '99') {
                                                echo "<option selected value='".$value->id_entidadfinanciera."'>".$value->descripcion."</option>";
                                            } else {
                                                echo "<option value='".$value->id_entidadfinanciera."'>".$value->descripcion."</option>";
                                            }
                                        }
                                    ?>
								</select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-university mr-2"></i>
                                    Nombre del banco
                                </label>
                                <input type="text" class="form-control form-control-sm" name="nombre_banco" id="nombre_banco" placeholder="Nombre del banco"> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="icon-profile position-left"></i>
                                    Nombre del titular
                                </label>
                                <input type="text" class="form-control form-control-sm" name="nombre_titular" id="nombre_titular" placeholder="Nombre del titular">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-credit-card-alt mr-2"></i>
                                    Nº de cuenta
                                </label>
                                <input type="number" class="form-control form-control-sm" name="num_cuenta" id="num_cuenta" placeholder="Nº de cuenta"> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-credit-card mr-2"></i>
                                    CCI
                                </label>
                                <input type="number" class="form-control form-control-sm" name="cci" id="cci" placeholder="CCI"> 
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-pencil7 mr-2"></i> Descripción:</label>
                            </div>
                            <textarea rows="5" cols="5" name="descripcion" id="descripcion" class="form-control" placeholder="Escribe aquí una descripción"></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right mt-5">
                                <button class="btn bg-indigo legitRipple btn_cuentabanco" type="button">
                                    <i class="icon-floppy-disk mr-2"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="panel border-top-indigo">
            <div class="panel-body" id="content_lista_cuentabanco">
                <fieldset class="content-group">
                    <legend class="text-bold">
                        <i class="icon-list mr-2" aria-hidden="true"></i>
                        <span class="font-weight-bold text-uppercase">Lista de cuentas de banco registradas</span>
                    </legend>
                </fieldset>
                <div class="row content-table">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table datatable-basic" id="tbl_lista_cuentabanco">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Moneda</th>
                                        <th>Banco</th>
                                        <th>Títular</th>
                                        <th>Nª de cuenta</th>
                                        <th class="text-center">Acción</th>
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
    <div class="footer text-muted">
        © 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
    </div>
</div>