<style>
.img-preview-pag{
	box-shadow: hsl(0, 0%, 80%) 0 0 16px;
	border: 4px solid #fff;
    border-radius: 5px;
}
.top-2{
    margin-top: 2rem;
}
.w-100{
	width: 100%;
}
.h-100{
	height: 100%;
}

</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Gestión de plantillas</span></h4>
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
				<div class="panel-body" id="content_panel_plantilla">
                    <form name="frm_plantilla" id="frm_plantilla" action="">
                        <fieldset class="content-group"><legend class="text-bold"><i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Gestor de plantillas</span> </legend></fieldset>
                        <input type="hidden" class="form-control form-control-sm" name="idplantilla" id="idplantilla"> 
                        <input type="hidden" id="src_img_upload" name="preview_imagen">
                        <div class=" col-sm-6 col-md-6 col-lg-5">
                            <div class="form-group">
                                <img class="w-100 h-100 img-preview-pag" id="img_upload_preview" src="/facturacionv8/img/preview.jpg" alt="preview_template">
                                <div class="text-center">
                                    <button type="button" id="btn_subirimagen" class="btn bg-indigo legitRipple top-2"> <i class="fa fa-picture-o mr-2"></i>
                                        Subir preview</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-7">
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-file-code-o mr-2"></i>
                                    Nombre
                                </label>
                                <input type="text" class="form-control form-control-sm" name="nombre_plantilla" id="nombre_plantilla" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-list-alt mr-2"></i>
                                   Categoría
                                </label>
                                <select name="categoria_plantilla" id="categoria_plantilla" class="select_minimizado form-control">
                                    <?php
                                    foreach($categorias as $categoria) {
                                        echo '<option value="'.$categoria->idcategoria.'">'.$categoria->nombre.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="label-form">
                                    <i class="fa fa-link mr-2"></i>
                                   Ruta Assets
                                </label>
                                <input type="text" class="form-control form-control-sm" name="assets_plantilla" id="assets_plantilla" placeholder="Ruta assets">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label-form">
                                <i class="fa fa-code mr-2"></i>
                                  HTML
                                </label>
                                <textarea name="html_plantilla" class="form-control" id="html_plantilla" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right mt-5">
                                <button class="btn bg-indigo legitRipple btn_plantilla" type="button">
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
        <div class="panel-body" id="content_lista_plantilla">
            <fieldset class="content-group">
                <legend class="text-bold">
                    <i class="icon-list mr-2" aria-hidden="true"></i>
                    <span class="font-weight-bold text-uppercase">Lista de plantillas</span>
                </legend>
            </fieldset>
            <div class="row content-table">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table datatable-basic" id="tbl_lista_plantilla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen preview</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Ruta assets</th>
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
    <div class="footer text-muted">
        © 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
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
				<p>Recuerda que la imágen debe tener las siguientes dimensiones 590x300px, en caso tengas una imágen más grande, puedes subirla sin problemas y nosotros te ayudaremos a recortar la imágen...</p>
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