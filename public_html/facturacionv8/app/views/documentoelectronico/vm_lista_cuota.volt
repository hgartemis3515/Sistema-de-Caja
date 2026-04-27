<style>
.border-cuotas {
    border: 1px solid #7880f0;
    padding: 10px;
    border-radius: 20px;
}
.mt-5 {
    margin-top: 3em!important;
}
.list_cuotas {
    font-weight: 700;
    padding: 5px 5px;
    cursor: pointer;
   
}

.icons-list {
    text-align: center;
}
.modal-header-bg{
    position: relative;
}
.modal-header-bg::after{
    content: ' ';
    position: absolute;
    top: -10em;
    left: 0;
    width: 100%;
    height: 400px;
    background-image: url(/facturacionv8/img/10.png);
    background-repeat: no-repeat;
    background-position: unset;
}
hr {
    margin-top: 0;
    margin-bottom: 20px;
}
.icon-arrow {
    background: -webkit-linear-gradient(#7880f0, #3f51b5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
<!-- vm_agregar_articulo -->
<div id="vm_lista_cuota" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content" id="contenido_vm_lista_cuota">
            <div class="modal-header modal-header-bg">
                <h6 class="modal-title text-uppercase font-weight-bold"><label class="label-form"><i class="icon-file-text"></i></label> Detalle de Cuotas</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body" style="padding: 10px;" id="content_popup_cuotas">
                <hr>
                <div style="margin-bottom: 5px;">
                    <span class="text-right font-weight-bold text-uppercase">
                      Total Adeudado: <strong class="simbolo_moneda">S/.</strong> <strong id="vm_lista_cuotas_total_adeudado"></strong>
                    </span>
                    <span class="float-right">
                        <a href="javascript:void(0);" class="btn btn-success btn-labeled btn legitRipple btn-sm agregar_cuota_item"><b><i class="icon-plus-circle2"></i></b> Agregar Cuota</a>
                    </span>
				</div>

                <table class="table mt-5" id="tbl_lista_cuotas">
                    <thead id="tbl_head_lista_cuotas">
                        <tr class="bg-indigo">
                            <td>Cuota</td>
                            <td>Monto</td>
                            <td>F.Vencimiento</td>
                            <td>Opción</td>
                        </tr>
                    </thead>
                    <tbody id="tbl_body_lista_cuotas" counter-id="1">
                        
                    </tbody>
                    <tfoot id="footer_tabla_cuotas" style="display:none;">
                        <tr>
                            <td colspan="4" class="font-weight-bold text-uppercase"> Total Cuotas: <span class="simbolo_moneda">S/.</span> <span id="monto_total_cuotas">789</span></td>
                           
                        </tr>
                    </tfoot>
                </table>
                
				<div class="text-right">
                    <div class="form-group">
                        <!-- <button class="btn bg-success legitRipple btn_guardar_lista_cuotas" id="btn_guardar_lista_cuotas" type="button">
                            <i class="icon-floppy-disk mr-1"></i>  Agregar Cuotas a la Factura
                        </button> -->
                        <hr>
                        <button type="button" class="btn bg-indigo btn-labeled  mx-1 text-uppercase font-weight-bold legitRipple btn_guardar_lista_cuotas" id="btn_guardar_lista_cuotas" ><b><i class="icon-floppy-disk"></i></b> Agregar Cuotas</button>

                        <button type="button" class="btn bg-info btn-labeled  mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_lista_cuotas" id="btn_eliminar_lista_cuotas" ><b><i class="icon-floppy-disk"></i></b> Eliminar Cuotas</button>

                        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-labeled  mx-1 text-uppercase font-weight-bold legitRipple"><b><i class="icon-cross2"></i></b> Cerrar</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->