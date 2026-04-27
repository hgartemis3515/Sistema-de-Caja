<style>
.color-indigo{
    color: #3F51B5;
}
.font-weght-bold{
    font-weight: 700;
}
.label_razonsocial{
    display: block;
}
.margin-top-25{
    margin-top: 25px;
}
.datatable-scroll-wrap {
    overflow-x: visible !important;
}
@media (min-width: 769px){
    .nav-tabs.nav-justified > .active > a, .nav-tabs.nav-justified > .active > a:hover, .nav-tabs.nav-justified > .active > a:focus {
        border-bottom-color: #fff;
        border-radius: 6px;
    }
}
.custom-textarea {
    display: block;
    width: 100%;
    padding: 8px 16px;
    font-size: 13px;
    line-height: 1.5384616;
    color: #333333;
    background-color: transparent;
    background-image: none;
    border: 1px solid #ddd;
    border-radius: 3px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
.dataTable thead .sorting, .dataTable thead .sorting_asc, .dataTable thead .sorting_asc_disabled, .dataTable thead .sorting_desc, .dataTable thead .sorting_desc_disabled {
    padding: 10px 30px;
}
#vm_ver_estadisticas_content .dataTables_wrapper .datatable-header .dt-buttons{
    display: none;
}
.modal-header-bg{
	position: relative;
}
.modal-header-bg::after {
    content: ' ';
    position: absolute;
    top: -7em;
    left: 0;
    width: 100%;
    height: 400px;
    background-image: url(/facturacionv8/img/10.png);
    background-repeat: no-repeat;
}
.card-estadisticas {
    padding: 1rem;
    border-radius: 20px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
}
.mt-0{
    margin-top: 0!important;
}
.mr-3 {
    margin-right: 1em;
}
.card-white {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
    background: #ffff;
    border-radius: 15px;
    padding: 1em;
    margin: 1em 0;
}
.single-docs-item {
    margin: .9em .9em;
}
.single-box-cpe {
    background: #eeeeee;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: auto;
    text-align: center;
    font-size: 30px;
    color: rgba(63,81,181,1);
    position: relative;
}
.icon-number {
    /* width: 90px; */
    /* height: 90px; */
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    font-size: 20px;
    /* padding-top: 5px; */
    /* border: 1px dashed; */
    /* border-radius: 50%; */
    font-weight: 700;
}
.container-cpe p{
    margin: 0;
}
.container-cpe .sub-text{
   color: rgb(163, 163, 163);
}
</style>
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Contribuyentes</span></h4>
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
<div class="content" id="contenido_lista_contribuyentes">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div>
                <div class="mb-2 text-aling button-actions">
                    <a style="margin-right: 10px;" href="/facturacionv8/gestiondecontribuyentes/configuracion" class="btn bg-indigo legitRipple" >
                        <i class="fa fa-plus mr-2"></i>
                        Agregar Contribuyente
                    </a>

                    <a style="margin-right: 10px;" href="/facturacionv8/gestiondecontribuyentes/videotutoriales" class="btn bg-primary legitRipple" >
                        <i class="icon-clapboard-play mr-2"></i>
                        Ver VideoTutoriales
                    </a>
                </div>

                <div class="content-group tab-content-bordered navbar-component">
                    <div class="navbar navbar-inverse bg-teal-400" style="position: relative; z-index: 30;">
                        <div class="navbar-header">
                            <ul class="nav navbar-nav pull-right visible-xs-block">
                                <li><a data-toggle="collapse" data-target="#demo1"><i class="icon-tree5"></i></a></li>
                            </ul>
                        </div>
                        <div class="navbar-collapse collapse" id="demo1">
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <a href="#tab_lista_contribuyentes" data-toggle="tab">
                                        <i class="icon-file-picture2 position-left"></i>
                                        Contribuyentes
                                    </a>
                                </li>
                            </ul>

                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <button type="button" class="btn btn-link daterange-ranges heading-btn text-semibold">
                                        <i class="icon-calendar3 position-left"></i> <span></span> <b class="caret"></b>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade active in has-padding" id="tab_lista_contribuyentes">
                            {{ partial('gestiondecontribuyentes/lista_contribuyentes') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal: Ventana validación de operacion -->
<div class="modal fade" id="vm_validar_operacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="validar_operacion_contenido">
            <div class="modal-header bg-indigo">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title">Validación de la Operación - Suscripción N° <span id="text_id_suscripcion"></span></h5>
            </div>
            <div class="modal-body" id="body_validar_operacion">
                <div class="row">
                    <input type="hidden" value="" name="id_suscripcion" id="id_suscripcion" />
                    <div class="col-md-12">
                        <div class="content-group"><h6><i class="icon-notebook position-left"></i> Nota:</h6>
                            <div class="mb-15 mt-15">
                                <textarea rows="3" cols="3" name="nota_validar_suscripcion" id="nota_validar_suscripcion" class="custom-textarea" placeholder="Escribe alguna nota para validar la suscripción!"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 15px;">Cancelar</button>
                        <button type="button" class="btn  bg-indigo btn_validar_suscripcion">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal: Ventana validación de operacion -->

<!-- vm_cambiar_fecha_expira -->
<div id="vm_cambiar_fecha_expira" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" id="content_vm_fecha_expira">
            <div class="modal-header bg-success-400">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Cambiar Fecha de Expiración:</h6>
            </div>
    
            <div class="modal-body">
                <input type="hidden" name="fechaexpira_idcontribuyente" id="fechaexpira_idcontribuyente" value=""/>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label><i class="icon-calendar2 position-left"></i> Fecha Expiración de Suscripción:</label>
                        <input type="text" name="fecha_expira_suscripcion" id="fecha_expira_suscripcion" placeholder="" class="form-control control_fecha_suscrip_expira">
                    </div>
                </div>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btn_cambiar_fechaexpira" class="btn btn-info">Guardar!</button>
            </div>
        </div>
    </div>
</div>
<!-- /vm_cambiar_fecha_expira -->

<!-- vm_cambiar_tipo_certificado -->
<div id="vm_cambiar_tipo_certificado" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" id="content_vm_tipo_certificado">
			<div class="modal-header bg-success-400">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-minus-circle2"></i> &nbsp; Cambiar Fecha de Expiración del Certificado!</h6>
			</div>
	
			<div class="modal-body">
				<input type="hidden" name="tipo_cert_idcontribuyente" id="tipo_cert_idcontribuyente" value=""/>

				<div class="row">
					<div class="form-group col-md-12">
						<label><i class="icon-calendar2 position-left"></i> Fecha Expiración:</label>
						<input type="text" name="fecha_expira_certificado" id="fecha_expira_certificado" placeholder="" class="form-control control_fecha_expira_cert">
					</div>
				</div>
			</div>
	
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<button type="button" id="btn_guardar_expiracert" class="btn btn-info">Guardar!</button>
			</div>
		</div>
	</div>
</div>
<!-- /vm_cambiar_tipo_certificado -->

<!-- Modal: Ver estadísticas -->
<div class="modal fade" id="vm_ver_estadisticas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Ver estadísticas</h5>
            </div>
            <div class="modal-body" id="vm_ver_estadisticas_content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card card-estadisticas bg-indigo">
                            <div class="card-body">
                                <div class="d-flex text-center">
                                    <h3 class="font-weight-semibold mb-0 mt-0" id="cantidad_clientes"></h3>
                                    <div class="list-icons ml-auto">
                                        <a class="list-icons-item" data-action="reload"></a>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                   Clientes Diferentes
                                    <div class="font-size-sm opacity-75">Clientes activos</div>
                                </div>
                            </div>
                            <div id="today-revenue"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-estadisticas bg-success">
                            <div class="card-body">
                                <div class="d-flex text-center">
                                    <h3 class="font-weight-semibold mb-0 mt-0" id="cantidad_productos"></h3>
                                    <div class="list-icons ml-auto">
                                        <a class="list-icons-item" data-action="reload"></a>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    Productos y/o productos
                                    <div class="font-size-sm opacity-75">Registrados y Activos</div>
                                </div>
                            </div>
                            <div id="today-revenue"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-estadisticas bg-indigo">
                            <div class="card-body">
                                <div class="d-flex text-center">
                                    <h3 class="font-weight-semibold mb-0 mt-0" id="cantidad_sucursales"></h3>
                                    <div class="list-icons ml-auto">
                                        <a class="list-icons-item" data-action="reload"></a>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    Sucursales
                                    <div class="font-size-sm opacity-75">o almacenes</div>
                                </div>
                            </div>
                            <div id="today-revenue"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-estadisticas bg-success">
                            <div class="card-body">
                                <div class="d-flex text-center">
                                    <h3 class="font-weight-semibold mb-0 mt-0" id="cantidad_usuarios"></h3>
                                    <div class="list-icons ml-auto">
                                        <a class="list-icons-item" data-action="reload"></a>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    Usuarios
                                    <div class="font-size-sm opacity-75">Registrados y Activos</div>
                                </div>
                            </div>
                            <div id="today-revenue"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card card-white">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5>Cantidad de Documentos emitidos</h5>
                                    </div>
                                    <div class="col-lg-6 mt-5">
                                        <select name="fechas_rango" id="fechas_rango" class="fechas_rango select2 margin-top-25"></select>
                                    </div>
                                </div>
                               
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="single-docs-item text-center">
                                            <img src="/facturacionv8/img/factura.svg" style="width: 40px;">
                                            <h5 class="font-weight-bold title-cpe" id="total_factura"></h5>
                                            <span>Factura</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="single-docs-item text-center">
                                            <img src="/facturacionv8/img/boleta.svg" style="width: 40px;">
                                            <h5 class="font-weight-bold title-cpe" id="total_boleta"></h5>
                                            <span>Boleta</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="single-docs-item text-center">
                                            <img src="/facturacionv8/img/nota_credito.svg" style="width: 40px;">
                                            <h5 class="font-weight-bold title-cpe" id="total_nota_credito"></h5>
                                            <span>Nota de Crédito</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="single-docs-item text-center">
                                            <img src="/facturacionv8/img/nota_debito.svg" style="width: 40px;">
                                            <h5 class="font-weight-bold title-cpe"  id="total_nota_debito"></h5>
                                            <span>Nota de Débito</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="single-docs-item text-center">
                                            <img src="/facturacionv8/img/nota_venta.svg" style="width: 40px;">
                                            <h5 class="font-weight-bold title-cpe" id="total_nota_venta"></h5>
                                            <span>Nota de Venta</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="single-docs-item text-center">
                                            <img src="/facturacionv8/img/nota_venta.svg" style="width: 40px;">
                                            <h5 class="font-weight-bold title-cpe" id="total_cotizacion"></h5>
                                            <span>Cotización</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="single-docs-item text-center">
                                            <i class="icon-trophy3" style="font-size: 30px !important;"></i>
                                            <h5 class="font-weight-bold title-cpe" id="total_docs_enviados_sunat"></h5>
                                            <span>Total DocsEnviados SUNAT</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="container-cpe text-center">
                                        <div class="single-box-cpe">
                                            <div class="icon-number">
                                                <span id="number_cpe"></span>
                                            </div>
                                        </div>
                                        <p class="font-weight-bold">CPE</p>
                                        <span class="sub-text">Enviados a Sunat</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="container-cpe text-center">
                                        <div class="single-box-cpe">
                                            <div class="icon-number">
                                                <span id="number_otros"></span>
                                            </div>
                                        </div>
                                        <p class="font-weight-bold">Otros documentos</p>
                                        <span class="sub-text">No enviados a SUNAT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" style="display:none;">
                        <div class="alert bg-primary text-white alert-dismissible">
                            <button type="button" class="close mr-3" data-dismiss="alert"><span>×</span></button>
                            El plan de Suscripción elegido es: <span class="font-weight-bold" id="nombre_plan2"></span>, y tiene un límite de <span  class="font-weight-bold" id="limite_docs"></span> documentos por mes. Su suscrpción vencerá el  <span id="fecha_expiracion"  class="font-weight-bold"></span>.
                        </div>
                    </div>

                    <div class="col-lg-12" style="display:none;">
                        <div class="alert bg-success text-white alert-dismissible" id="planes_basexreseller">
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- /Modal: Ver estadísticas  -->

{{ partial('gestiondecontribuyentes/asignacion_modulos_sistema') }}