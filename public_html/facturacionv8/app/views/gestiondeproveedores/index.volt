<style>
.label-form{
    padding: 5px 5px;
}
.dataTables_filter > label:after {
    content: "";
}
@media (min-width: 800px){
    .dataTables_filter > label:after {
    content: "\e98e";
    }
}
</style>
<!-- Page header -->
<div class="page-header">
    <div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de Proveedores</span></h4>
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
        <div class="col-lg-12 col-md-12">
            <div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body" id="contenido_datos_proveedores">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Agregar Proveedores</span> 
                        </legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="" class="frm_addproveedor" id="frm_addproveedor">
                                <input type="hidden" id="idproveedor" name="idproveedor" value="" />
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="label-form ">
                                                <i class="icon-user mr-2"></i>
                                                Código
                                            </label>
                                            <div class="input-group">
                                                <input type="text" name="codigo" id="txt_codigo" class="form-control" placeholder="Código">
                                                <span class="input-group-btn">
                                                    <button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
                                                        <i class="icon-rotate-ccw3 mr-2"></i>
                                                        Generar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-user mr-2"></i> 
                                                Tipo de Documento de Identidad
                                            </label>
                                            <select class="js-example-basic-single" name="type_document" id="type_document">
                                                <option selected value="">Seleccione una opción</option>
                                                <?php                                    
                                                    foreach ($tipo_identidad as $value) {
                                                        echo "<option value='".$value->id_tipodocidentidad."'>".$value->nombre."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-user mr-2"></i>
                                                <span class="type_id">Documento de Identidad</span> 
                                                <span style="display: none;" class="label label-danger lbl_estado_empresa lbl_estado_inactivo">Inactivo</span>
                                                <span style="display: none;" class="label label-success lbl_estado_empresa lbl_estado_activo">Activo</span>
                                            </label>
                                            <div class="form-group" id="contenido_boton_busqueda">
                                                <input type="text" name="doc_id" id="doc_id" class="form-control" placeholder="Número de Documento de Identidad">
                                                <span class="input-group-btn" id="btn_buscar_datos_api" style="display:none;">
                                                    <button class="btn bg-indigo legitRipple search_document" type="button">
                                                        <i class="icon-search4" id="icon_search_document"></i>
                                                        <i class="icon-spinner10 spinner" style="display: none;" id="icon_searching_document"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-users2 mr-2"></i> 
                                                Razón social/Nombre Completo 
                                            </label> <span style="display: none;" class="label label-danger lbl_estado_empresa lbl_estado_inactivo">Inactivo</span>
                                            <span style="display: none;" class="label label-success lbl_estado_empresa lbl_estado_activo">Activo</span>
                                            <input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" placeholder="Nombre Comercial">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-envelop mr-2"></i>
                                                Email
                                            </label>
                                            <input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-iphone mr-2"></i> 
                                                Célular
                                            </label>
                                            <input type="number" class="form-control form-control-sm" name="celular" id="celular" placeholder="Célular">
                                        </div>	
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-address-book mr-2"></i>
                                                Dirección fiscal
                                            </label>
                                            <input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Direccion fiscal">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                                Ubigeo
                                            </label>
                                            <select class="js-example-basic-single" name="ubigeo" id="ubigeo">
                                                <option selected value="">Seleccione una opción</option>
                                                <?php                                    
                                                    foreach ($ubigeo as $value) {
                                                        echo "<option value='".$value->codigo_ubigeo."'>".$value->departamento.' - '.$value->provincia.' - '.$value->distrito."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>	
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label  class="label-form">
                                                <i class="icon-plus mr-2"></i> 
                                                Detalle Adicional
                                            </label>
                                            <textarea name="detalle_adicional" id="detalle_adicional" cols="30" rows="4" maxlength="250"  class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="float-right">
                                        <button class="btn bg-indigo legitRipple btn_saveproveedor" type="button">
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
            <div class="panel border-top-indigo" style="max-width: 1120px;margin: 30px auto;">
                <div class="panel-body"  id="contenido_lista_proveedor">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-list mr-2" aria-hidden="true"></i>
                            <span class="font-weight-bold text-uppercase">Lista de Poveedores</span>
                        </legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive p-20">
                            <table class="table datatable-basic" id="tbl_lista_proveedor">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo de Documento</th>
                                        <th>N° Documento</th>
                                        <th>Código</th>
                                        <th>Razón Social / Nombre Completo</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>							
                                    
                                </tbody>
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