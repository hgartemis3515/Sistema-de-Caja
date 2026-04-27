<style>
.btn-labeled {
    padding: 6px 17px;
}
.btn-default {
    color: #333;
    background-color: #fcfcfc;
    border-color: #ddd;
}
.label-form{
    padding: 5px 5px;
}
.nav-tabs.nav-tabs-highlight > li.active > a, .nav-tabs.nav-tabs-highlight > li.active > a:hover, .nav-tabs.nav-tabs-highlight > li.active > a:focus {
    border-top-color: #3F51B5;
}
li.active > a, .nav-tabs.nav-tabs-highlight > li.active > a:hover, .nav-tabs.nav-tabs-highlight > li.active > a:focus {
    border-top-color: #3F51B5;
}
.modal-header {
    padding: 0px; 
}
.mt-1{
    margin-top: 20px!important;
}
.mt-2{
    margin-top: 40px!important;
}
.mt-4{
    margin-top: 4em!important;
}
.mr-2{
    margin-right: 10px;
}
.sweet-alert button.cancel{
    background-color: #adadad;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
}
.sweet-alert button.confirm {
    text-transform: uppercase;
    font-weight: 700;
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
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Empresa de transporte</span></h4>
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
                <div class="panel-body" id="content_panel_empresa">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Agregar Empresas de transporte</span> 
                        </legend>
                    </fieldset>
                    <form action="" class="frm_empresa_transporte" id="frm_empresa_transporte">
                        <input type="hidden" id="id_etransporte" name="id_etransporte" value="" />
                        <input type="hidden" id="id_cliente_documento" name="id_cliente_documento" value="" />
                        <input type="hidden" id="cliente_email" name="cliente_email" value="" />
                        <div class="row">
                            <div class="col-lg-6" id="estado_numerodocumento">
                                <div class="form-group">
                                    <label  class="label-form"> 
                                        <i class="icon-user mr-2"></i>  
                                        <span id="titulo_numerodocumento">N° de RUC</span>: <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="hidden" name="tipo_doc" id="tipo_doc" class="tipo_doc" value="6" />
                                        <input type="text" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento" required>
                                        <span class="input-group-btn">
                                            <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                                <i class="icon-search4 icon_search_document"></i>
                                                <i class="icon-spinner10 spinner position-left icon_searching_document" style="display: none;" id=""></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" id="razonsocial_numerodocumento">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-users2 mr-2"></i> 
                                        Razón social/Nombre Completo 
                                    </label>
                                    <input type="text"  name="cliente_nombre" id="cliente_nombre" placeholder="Nombre o Razón Social" class="form-control cliente_nombre"  required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="has-feedback has-feedback-left">
                                        <label class="label-form"><i class="icon-sphere position-left"></i>Ubigeo: </label>
                                        <select title="Selecciona tu Código de Ubigeo" data-placeholder="Selecciona Tu Código de Ubigeo" class="select_codigoubigeo select2" name="select_codigoubigeo" id="select_codigoubigeo">
                                        </select>
                                    </div>    
                                </div>                    
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-address-book mr-2"></i>
                                        Dirección 
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="cliente_direccion" id="cliente_direccion" placeholder="Direccion fiscal">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-phone2 mr-2"></i> 
                                        Teléfono
                                    </label>
                                    <input type="number" class="form-control form-control-sm" name="cliente_telefono" id="cliente_telefono" placeholder="Teléfono">
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
                            <div class="col-md-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                        <li class="active">
                                            <a href="#tab_conductores" data-toggle="tab" class="legitRipple" aria-expanded="true">
                                                <i class="icon-vcard position-left"></i>Lista Conductores
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab_vehiculos" data-toggle="tab" class="legitRipple" aria-expanded="false">
                                                <i class="fa fa-car position-left"></i>Lista Vehículos
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" style="border-bottom: solid 1px #dfdfdf; margin-bottom: 25px;">
                                        <div class="tab-pane" id="tab_vehiculos">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="col-md-12" style="text-align: right; padding-bottom: 4px;">
                                                        <button type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_editar_vehiculo"><b><i class="icon-pencil3"></i></b> editar</button>
                                                        <button type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_agregar_vehiculo"><b><i class="icon-plus-circle2"></i></b> Agregar</button>
                                                        <button type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_vehiculo"><b><i class="icon-cross2"></i></b> Eliminar</button>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="jqGrid content_tabla_detalle_vehiculos">
                                                            <table id='lista_vehiculos' class='scroll'></table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane active" id="tab_conductores">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="col-md-12" style="text-align: right; padding-bottom: 4px;">
                                                        <button type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_editar_conductor"><b><i class="icon-pencil3"></i></b> editar</button>
                                                        <button type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_agregar_conductor"><b><i class="icon-plus-circle2"></i></b> Agregar</button>
                                                        <button type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_conductor"><b><i class="icon-cross2"></i></b> Eliminar</button>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="jqGrid content_tabla_detalle_conductores">
                                                            <table id='lista_conductores' class='scroll'></table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 mt-1">
                                <div class="float-right">
                                    <button class="btn bg-indigo legitRipple btn_save_empresatransporte" type="button">
                                        <i class="icon-floppy-disk mr-2"></i>
                                        Guardar
                                    </button>
                                </div>	
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel border-top-indigo mt-2" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body"  id="contenido_lista_empresa">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-list mr-2" aria-hidden="true"></i>
                            <span class="font-weight-bold text-uppercase">Lista de empresas de transporte</span>
                        </legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive p-20">
                            <table class="table datatable-basic" id="tbl_lista_empresa">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>N° Documento</th>
                                        <th>Razón Social / Nombre Completo</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
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
<!--  Modal agregar_conductor -->
<div class="modal fade border-top-indigo" id="vm_modal_conductores" tabindex="-1" role="dialog" aria-labelledby="vm_modal_conductores" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading bg-indigo-400">
                <div class="modal-header">
                    <h5 class="modal-title" id="vm_modal_conductores"><i class="icon-vcard position-left"></i>Agregar conductor</h5>
                </div>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control"  name="id_conductor_temporal" id="id_conductor_temporal" value="0">
                <form action="" name="frm_agregar_conductor" id="frm_agregar_conductor">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="hidden" id="idconductor" name="idconductor" value="" />
                                <input type="hidden" id="key_row" name="key_row" value="" />
                                <label class="label-form"><i class="icon-file-text2 position-left"></i>Tipo de documento</label>
                                <select class="select2" name="type_document" id="type_document" required>
                                    <option selected value="">Seleccione una opción</option>
                                    <?php                                    
                                        foreach ($tipo_identidad as $value) {
                                            if($value->id_tipodocidentidad == 1) {
                                                echo "<option selected='selected' value='".$value->id_tipodocidentidad."'>".$value->nombre."</option>";
                                            } else {
                                                echo "<option value='".$value->id_tipodocidentidad."'>".$value->nombre."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-pencil position-left"></i> Nro. de documento</label>
                                <div class="input-group">
                                    <input type="text" title="Número de Documento" name="numero_doc_conductor" id="numero_doc_conductor" placeholder="Número de documento Aquí!" class="form-control numero_doc_conductor" required>
                                    <span class="input-group-btn">
                                        <button class="btn bg-indigo btn-icon legitRipple search_document_dni" type="button">
                                            <i class="icon-search4 icon_search_document"></i>
                                            <i class="icon-spinner10 spinner position-left icon_searching_document" style="display: none;" id=""></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-user position-left"></i> Nombre completo</label>
                                <input type="text" class="form-control"  name="nombre_completo" id="nombre_completo" placeholder="Nombre completo" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-vcard position-left"></i> Licencia de conducir</label>
                                <input type="text" class="form-control"  name="licencia_conducir" id="licencia_conducir" placeholder="Licencia de conducir" required>
                                
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-vcard position-left"></i> Tipo de licencia</label>
                                <select  class="form-control select2"  name="tipo_licencia" id="tipo_licencia">
                                    <option selected value="">Seleccione un tipo de licencia</option>
                                    <option value="A-I">A-I</option>
                                    <option value="A-IIa">A-IIa</option>
                                    <option value="A-IIb">A-IIb</option>
                                    <option value="A-IIIa">A-IIIa</option>
                                    <option value="A-IIIb">A-IIIb</option>
                                    <option value="A-IIIc">A-IIIc</option>
                                    <option value="B-I">B-I</option>
                                    <option value="B-IIa">B-IIa</option>
                                    <option value="B-IIb">B-IIb</option>
                                    <option value="B-IIc">B-IIc</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-phone2 mr-2"></i> Teléfono</label>
                                <input type="number" class="form-control"  name="telefono" id="telefono" placeholder="Teléfono" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-indigo mx-1 font-weight-bold text-uppercase btn_guardar_conductor"><i class="icon-floppy-disk mr-2"></i>Agregar</button>
                <button type="button" class="btn btn-secondary mx-1 font-weight-bold text-uppercase" data-dismiss="modal"><i class="icon-cross2 mr-2"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--  / Modal agregar_conductor -->

<!--  Modal agregar_vehiculo -->
<div class="modal fade border-top-indigo" id="vm_modal_vehiculo" tabindex="-1" role="dialog" aria-labelledby="vm_modal_vehiculo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading bg-indigo-400">
                <div class="modal-header">
                    <h5 class="modal-title" id="vm_modal_vehiculo"><i class="fa fa-car position-left"></i>Agregar vehículo</h5>
                </div>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control"  name="id_vehiculo_temporal" id="id_vehiculo_temporal" value="0">
                <form  name="frm_agregar_vehiculo" id="frm_agregar_vehiculo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="hidden" id="idvehiculo" name="idvehiculo" value="" />
                                <input type="hidden" id="key_row_vehiculo" name="key_row_vehiculo" value="" />
                                <label class="label-form"><i class="icon-file-text2 position-left"></i> Nro. de placa</label>
                                <input type="text" class="form-control"  name="vh_numero_placa" id="vh_numero_placa" placeholder="Número de placa" required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-pencil position-left"></i> Marca</label>
                                <input type="text" class="form-control" name="vh_marca" id="vh_marca" placeholder="Marca" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-file-text2 position-left"></i>Const. Inscripción</label>
                                <input type="text" class="form-control"  name="vh_const_inscripcion" id="vh_const_inscripcion" placeholder="Const. Inscripción" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-stack position-left"></i> Capacidad</label>
                                <input type="number" class="form-control"  name="vh_capacidad" id="vh_capacidad" placeholder="Capacidad" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="label-form"><i class="icon-file-text2 position-left"></i> DGH</label>
                                <input type="text" class="form-control"  name="vh_dgm" id="vh_dgh" placeholder="DGM" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-indigo mx-1 font-weight-bold text-uppercase btn_guardar_vehiculo"><i class="icon-floppy-disk mr-2"></i>Agregar</button>
                <button type="button" class="btn btn-secondary mx-1 font-weight-bold text-uppercase" data-dismiss="modal"><i class="icon-cross2 mr-2"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--  / Modal agregar_conductor -->
