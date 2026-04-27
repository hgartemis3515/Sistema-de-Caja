<style>
.thumb {
    position: relative;
    display: block;
    text-align: center;
    padding: 1px;
    -webkit-box-shadow: 0 4px 20px 0 rgb(0 0 0 / 5%);
    box-shadow: 0 4px 20px 0 rgb(0 0 0 / 5%);
    -webkit-appearance: none;
    background-size: 500%;
    border-radius: 5px;
}
.image-preview{
    -webkit-box-shadow: 0 4px 20px 0 rgb(0 0 0 / 5%);
    box-shadow: 0 4px 20px 0 rgb(0 0 0 / 5%);
    border-radius: 5px;
}

#img_upload_preview {
    -webkit-box-shadow: 0 0 15px 0 rgb(0 0 0 / 5%);
    box-shadow: 0 0 15px 0 rgb(0 0 0 / 5%);
    padding: 1px;
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de Plantillas</span></h4>
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
    <div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
        <div class="panel-body" id="content_panel_plantilla">
            <fieldset class="content-group">
                <legend class="text-bold">
                    <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                    <span class="text-uppercase">Gestión de Plantillas PDF</span> 
                </legend>
            </fieldset>
            <form name="frm_plantilla_pdf" id="frm_plantilla_pdf" action="">
                <input type="hidden" id="id_plantilla_pdf" name="id_plantilla_pdf" value="" />
                <input type="hidden" id="src_img_upload" name="src_img_upload" value="" />
                <div class="row">
                    <div class="col-md-2">
                        <div class="text-center">
                            <div class="thumb">
                                <img src="/facturacionv8/img/svg/preview_pdf.svg"  id="img_upload_preview" alt="" class="thumb-image">
                            </div>
                            <button class="btn bg-indigo legitRipple mt-5 btn_subirimagen" type="button">
                                <i class="fa fa-cloud-upload mr-2"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="fa fa-file-pdf-o mr-2"></i>
                                Nombre
                            </label>
                            <input type="text" class="form-control form-control-sm" name="nombre_plantilla" id="nombre_plantilla" placeholder="Nombre de plantilla PDF">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="fa fa-file-o mr-2"></i>
                                Tamaño
                            </label>
                            <select class="select inputMaterial select_tamanio select2" name="select_tamanio" id="select_tamanio">
                                <option value="ticket">Ticket</option>
                                <option value="a4">A4</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-profile position-left"></i> 
                                Plantilla Válida para:
                            </label>
                            <select class="multiselect" multiple="multiple" name="tipos_documentos[]" id="tipos_documentos">
                                <option value="03">BOLETAS</option>
                                <option value="01">FACTURAS</option>
                                <option value="07">NOTAS DE CRÉDITO</option>
                                <option value="08">NOTAS DE DÉBITO</option>
                                <option value="09">GUÍAS DE REMISIÓN</option>
                                <option value="88">COTIZACIONES</option>
                                <option value="77">NOTAS DE VENTAS</option>
                                <option value="31">GUÍA TRANSPORTISTA</option>
                                <option value="99">ORDEN DE COMPRA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="fa fa-pencil mr-2"></i>
                                Categoría
                            </label>
                            <select class="select inputMaterial select_categoria select2" name="select_categoria" id="select_categoria">
                                <option value="normal">Normal</option>
                                <option value="personalizado">Personalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="fa fa-user mr-2"></i>
                                Id contribuyente
                            </label>
                            <input type="text" class="form-control form-control-sm" name="id_contribuyente" id="id_contribuyente" >
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button class="btn bg-indigo legitRipple btn_save_plantilla" id="btn_save_plantilla" type="button">
                            <i class="icon-floppy-disk mr-2"></i>
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel border-top-indigo"  style="max-width: 1120px;margin: 25px  auto 0 auto;">
        <div class="panel-body"  id="content_lista_plantillapdf">
            <fieldset class="content-group">
                <legend class="text-bold">
                    <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                    <span class="font-weight-bold text-uppercase">Lista de Plantillas PDF</span>
                </legend>
            </fieldset>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table datatable-basic" id="tbl_lista_plantillapdf">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Preview</th>
                                    <th>Tipo Docs</th>
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

<!-- Ventana para Agregar imágen -->
<div id="vm_cargar_imagen" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Importante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p>Recuerda que la imagen debe tener el siguiente tamaño: <strong class="tamanio_texto_img"></strong>, en caso tengas una imágen más grande, puedes subirla sin problemas y nosotros te ayudaremos a recortar la imágen...</p>
				<hr>
				<div class="row">
					<div class="form-group col-lg-9">
						<input id="fileimage" type="file" class="file-input" accept=".jpg,.gif,.png">
					</div>
					<div class="form-group col-lg-3">
						<div class="previewrecorteimg" style="width: 100%;"></div>
					</div>
						<img src="" id="imagenresultado" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary legitRipple" id="btn_guardarimagen"><i class="icon-spinner6 spinner position-left btn_guardarimagen_loading" style="display: none;"></i><i class="icon-floppy-disk position-left btn_guardarimagen_icono"></i> Guardar Imágen</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para Agregar imágen -->