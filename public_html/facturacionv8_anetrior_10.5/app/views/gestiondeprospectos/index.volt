<style>
p{
    margin: 0;
    padding: 0;
}
#nombre_empresa_nota{
    position: relative;
    margin-bottom: 5px;
}
#nombre_empresa_nota::after{
    left: 2.7em;
    top: .1em;
    content: "";
    position: absolute;
    bottom: -2px;
    width: 59px;
    margin-left: -37px;
    border-bottom: 2px solid #7880f0;
}
.add-item {
    padding: 6px 15px 5px 15px;
    margin: 10px 3px;
    height: 35px;
    min-width: 60px;
    background-color: #fff;
    border: 1px solid #eee;
    color: #6f6f6f!important;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 500;
    line-height: 20px;
    display: inline-block;
}
.add-item i{ font-size: 11px;}
.add-item:hover{
    background-color: #eee;
    border: 1px solid #eee;
    color: #6f6f6f!important;
    transition: .5s;
}
.btn-default {
    border-color: #dfdfdf;
    background-color: #eee;
    color: #333;
}
.color-indigo{
    color: #3F51B5!important;
}

#box-tag .form-control{
    border: 0px solid #ddd;
}
#box-tag  .tokenfield .token {
    border-radius: 20px;
    margin: 6px 0 0 6px;
}
.content-correo p {
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}
.content-tag{
     display: inline-block;
}
#content-tag {
    padding: 10px;
    background: rgba(228, 228, 228, 0.507);
    border: 2px dashed rgb(228, 228, 228);
    margin-bottom: 10px;
}
#content-tag .tagg-item{
    margin-right: 10px;
}
.dataTable thead .sorting, .dataTable thead .sorting_asc, .dataTable thead .sorting_asc_disabled, .dataTable thead .sorting_desc, .dataTable thead .sorting_desc_disabled {
    padding-right: 70px;
}
.display-none {
    display: none!important;
}
.dt-buttons>.btn:first-child {
    border-bottom-left-radius: 15px;
    border-top-left-radius: 15px;
    border-bottom-right-radius: 0px!important;
    border-top-right-radius: 0px!important;
}
.edit-token{
    float: right;
}
.mr-1{
    margin: 5px;
}
.mr-2{
    margin-right: 15px;
}
#nuevo_prospecto .tokenfield .token>.close {
    font-size: 0;
    cursor: pointer;
    position: absolute;
    top: 35%;
    color: inherit;
    right: 8px;
    line-height: 1;
    margin-top: -5.5px;
}
.radio{
    display: inline-block;
}
.p-0{
    padding: 0px!important;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    right: 0px;
    padding: 10px 10px;
}

.tagg-item {
    position: relative;
    display: inline-block;
    padding: 8px 0px;
    cursor: pointer;
}
.tagg-item span {
    border-radius: 20px;
    margin: 0px;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 6px 6px;
    padding-right: 25px;
    font-size: 10.5px;
    line-height: 1.6666667;
}

.tagg-item .close{
    font-size: 18px;
    cursor: pointer;
    position: absolute;
    color: inherit;
    right: 8px;
    line-height: 1;
    color: #000;
}
.tagg-item span:hover .close a{
    color: #fff!important;
}
.border-primary-600 {
    border-color: #7880f0!important;
}
.text-primary-800, .text-primary-800:focus, .text-primary-800:hover {
    color: #7880f0!important;
}
.disabled {
    font-size: 10px;
    color: #949494;
}
.dropdown-box {
    padding: 1em;
    -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    border-bottom: #7880f0!important;

}
</style>
<div class="page-header">
    <div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de Prospectos</span></h4>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/>
                    <span>Boleta</span></a>
                <a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/factura.svg" style="width: 25px;"/>
                    <span>Factura</span></a>
                <a href="/facturacionv8/documentoelectronico/index/07/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/nota_credito.svg" style="width: 25px;"/>
                        <span>Nota Crédito</span></a>
                <a href="/facturacionv8/documentoelectronico/index/08/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/nota_debito.svg" style="width: 25px;"/>
                    <span>Nota Débito</span></a>
                <a href="/facturacionv8/reportes" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/svg/analytics.svg" style="width: 25px;"/>
                    <span>Reporte de Ventas</span></a>
            </div>
        </div>
    </div>
</div>
<div class="content">
	<div class="row">
		<div class="col-md-12 col-md-12">
			<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body"  id="content_lista_prospectos">
					<fieldset class="content-group">
						<legend class="text-bold">
							<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                            <span class="font-weight-bold text-uppercase">Gestión de Prospectos</span>
                          
						</legend>
					</fieldset>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
                                <table class="table datatable-basic" id="tbl_lista_prospectos">
                                    <thead>
                                        <tr>
                                            <th style="padding-right: 20px;">ID</th>
                                            <th style="padding-right: 20px;">FechaReg.</th>
                                            <th style="padding-right: 40px;">Nombre</th>
                                            <th width="200px">Etiquetas</th>
                                            <th width="150px">Teléfono</th>
                                            <th>E-mail</th>
                                            <th>Empresa</th>
                                            <th style="padding-right: 120px;">Nota</th>
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
    <!-- Modal de agregar prospecto -->
    <div class="modal fade" id="nuevo_prospecto" tabindex="-1" role="dialog" aria-labelledby="nuevo_prospectoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-prospecto">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title" id="nuevo_prospectoLabel">Agregar prospecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="frm_agregar_prospecto">
                        <input type="hidden" id="id_prospecto" name="id_prospecto" value="" />
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-form"><i class="icon-user position-left"></i>Nombre del contacto</label>
                                    <input type="text" class="form-control" name="nombre_contacto" id="nombre_contacto">
                                </div>
                            </div>
                            <div class="col-lg-6 display-none">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-profile position-left"></i>
                                        Sucursal 
                                    </label>
                                    <select name="select_sucursal" id="select_sucursal" class="select2">
                                        <?php
                                        foreach($lista_sucursales as $sucursal) {
                                            echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group" id="box-telefono-prospecto">
                                    <label class="label-form"><i class="fa fa-phone position-left"></i>Telefonos</label>
                                    <input type="text" class="form-control tokenfield"  name="telefonos" id="telefonos">
                                    <label id="text_warning"></label>
                                </div>
                            </div>
                            <div class="col-lg-6"> 
                                <div class="form-group" id="box-correo-prospecto">
                                    <label class="label-form"><i class="icon-envelop2 position-left"></i>Correos</label>
                                    <input type="text" class="form-control tokenfield" name="email" id="email">
                                    <label id="text_warning2"></label>
                                </div>
                            </div>
                            <div class="col-lg-12"> 
                                <div class="form-group">
                                    <label class="label-form"><i class="icon-envelop2 position-left"></i>Fecha Expira Oferta</label>
                                    <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_expira_oferta" id="fecha_expira_oferta">
                                    <label id="text_warning2"></label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group display-none" id="box-etiqueta-prospecto">
                                    <label class="label-form"><i class="fa fa-tag position-left"></i>Etiquetas</label>
                                    <input type="text" class="form-control" name="etiquetas" id="etiquetas" readonly>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="label-form"><i class="icon-droplet2 mr-2"></i>Color</label>
                                        <div class="display-block mr-1"></div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color-etiqueta-modal" class="control-primary" value="bg-primary" id="bg-primary"  checked="checked">
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color-etiqueta-modal" class="control-success" id="bg-success" value="bg-success">
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color-etiqueta-modal" class="control-warning" value="bg-warning" id="bg-warning">
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color-etiqueta-modal" class="control-danger" value="bg-danger" id="bg-danger">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 p-0">
                                    <div class="form-group">
                                        <label class="label-form"><i class="fa fa-tag position-left"></i>Agregar</label>
                                        <div class="input-group">
                                            <input type="text" name="nombre_etiqueta_modal" class="form-control" id="nombre_etiqueta_modal" placeholder="Agregar etiqueta" required>
                                            <span class="input-group-btn">
                                                <button id="addtoken" class="btn bg-indigo  legitRipple" type="button">
                                                    Agregar etiqueta
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 p-0" id="add_modal_token">
                                    <div id="content-tag"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-form"><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° de RUC</span>: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento" required>
                                        <span class="input-group-btn">
                                            <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                                <i class="icon-search4" id="icon_search_document"></i>
                                                <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-form"><i class="icon-vcard position-left"></i>Razón social</label>
                                    <input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" placeholder="Nombre Comercial">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-form"><i class="icon-notebook position-left"></i>Nota</label>
                                    <textarea rows="3" cols="3" name="nota_prospecto" class="form-control placeholder="Escribe aquí una nota"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn bg-indigo btn_save_prospectos"><i class="icon-floppy-disk position-left"></i>Guardar prospecto</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal etiquetas -->
    <div class="modal fade" id="agregar_tagg_modal" tabindex="-1" role="dialog" aria-labelledby="agregar_tagg_modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                <h5 class="modal-title" id="agregar_tagg_modalLabel"><i class="fa fa-tag mr-1"></i>Agregar etiqueta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="" id="add_tag">
                        <input type="hidden" id="id_prospecto_tag" name="id_prospecto" value="" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-form">Nombre de etiqueta</label>
                                    <input type="text" name="nombre_etiqueta" class="form-control" id="nombre_etiqueta" placeholder="Agregar etiqueta">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-form">Color</label>
                                    <div class="display-block"></div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="color_tag" class="control-primary" value="bg-primary" checked="checked">
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="color_tag" class="control-success" value="bg-success">
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="color_tag" class="control-warning" value="bg-warning">
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="color_tag" class="control-danger" value="bg-danger">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-indigo btn_save_tag">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal telefono-->
    <div class="modal fade" id="agregar_telefono_modal" tabindex="-1" role="dialog" aria-labelledby="agregar_tagg_modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                <h5 class="modal-title" id="agregar_tagg_modalLabel"><i class="fa fa-phone position-left"></i>Agregar Teléfono</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="" id="add_phone">
                        <input type="hidden" id="id_prospecto_telefono" name="id_prospecto" value="" />
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-form">Número de teléfono</label>
                                    <input type="number"  class="form-control" name="telefono" id="telefono" placeholder="Agregar telefono">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-indigo btn_guardar_telefono">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal E-mail -->
    <div class="modal fade" id="agregar_correo_modal" tabindex="-1" role="dialog" aria-labelledby="agregar_tagg_modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                <h5 class="modal-title" id="agregar_tagg_modalLabel"><i class="icon-envelop2 position-left"></i>Agregar Correo Electrónico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="" id="add_correo">
                        <input type="hidden" id="id_prospecto_correo" name="id_prospecto" value="" />
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Correo Electrónico</label>
                                    <input type="email"  class="form-control" name="correo" id="correo" placeholder="Agregar E-mail">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-indigo btn_guardar_correo">Guardar</button>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal Notas -->
     <div class="modal fade" id="editar_nota_modal" tabindex="-1" role="dialog" aria-labelledby="editar_nota_modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                <h5 class="modal-title" id="editar_nota_modalLabel"><i class="icon-pencil position-left"></i>Editar Notas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_nota_prospecto">
                        <input type="hidden" id="id_prospecto_nota" name="id_prospecto" value="" />
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <p class="font-weight-700" id="nombre_empresa_nota"></p>
                                    <p  id="nombre_nota"></p>
                                    <p  id="telefono_nota"></p>
                                </div>
                                <div class="form-group">
                                    <label class="label-form" id="tipo_nota">Editar Nota de Prospecto</label>
                                    <textarea name="nota_prospecto_new" id="nota_prospecto_new" cols="30" rows="4" maxlength="250" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-indigo btn_guardar_nota">Guardar</button>
                </div>
            </div>
        </div>
    </div>
	<div class="footer text-muted">
		© 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
	</div>
</div>