<style>
.bg-gray-50 {
    background: #fff;
    text-align: center;
}
.border-indigo-500 {
    border: 1px solid rgb(127, 17, 137);
    border-radius: 20px;
}
.border-azul-500 {
    border: 1px solid #4c85a5;
    border-radius: 20px;
}
.box-check {
    border: 1px solid #7f1189;
    margin-top: 1em;
    border-radius: 20px;
    background: rgb(127 17 137 / 8%);
    padding: 1em;
}
.box-check-1 {
    border: 1px solid #4c85a5;
    margin-top: 1em;
    border-radius: 20px;
    background: rgb(76 133 165 / 8%);
    padding: 1em;
}
.btn-justify {
    background: #7f1189;
    flex-direction: column;
    padding: 1em;
    border-radius: 20px;
}
.btn-justify-1 {
    background: #4c85a5;
}
.btn-justify .label-form{
    color: #fff;
}
.btn-justify .label-form i{
    background: #fff!important;
    -webkit-background-clip: text!important;
    -webkit-text-fill-color: transparent;
}
.card-box{
    background-color: #fff;
    padding: 10px;
    border-radius: 20px;
}

.flex-col {
    flex-direction: column;
}
.fill-white {
    fill: #fff;
}

.font-medium {
    font-weight: 500;
}
.gap-2 {
    gap: 0.5rem;
}
.items-center {
    align-items: center;
}
.mb-5 {
    margin-bottom: 15px!important;
}
.p-4 {
    padding: 1em 2em;
}

.row-1{
    display: flex;
    justify-content: center;
}

.shadow-md {
    
    box-shadow: 0 0 #0000, 0 0 #0000, 0 0 #0000, 0 0 #0000,  0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}
.stroke-indigo-500 {
    stroke: #7f1189;
}
.stroke-azul-500 {
    stroke: #4c85a5;
}
.text-gray-600 {
    color: rgb(75 85 99 / 1);
}
.w-100{
    width: 100%;
}

.mxy-20{
    margin: 1em 0em 1em 1em!important;
}
.mt-20{
    margin-top: 1em!important;
}
.mb-20{
    margin-bottom: 1em!important;
}

.color-gray{
    color: #90949c;
}
.display-block{
    display: block;
}

@media (min-width: 900px)
{
    #img_upload_preview {
        width: 130px;
        height: 130px;
    }
    .nav-tabs > li {
        display: inline-block;
        font-size: 14px;	
    }
    .nav-tabs.nav-tabs-bottom > li.active > a, .nav-tabs.nav-tabs-bottom > li.active > a:hover, .nav-tabs.nav-tabs-bottom > li.active > a:focus{
        font-weight: 700;
    }
    .info-password, .info-access{
        display: grid;
        grid-template-columns: 200px 1fr 90px;
    }
        .info-access2, .info-password2{
        display: grid;
        grid-template-columns: 200px 1fr;
    }

}
</style>

    <input type="hidden" id="img_qr_yape" value="<?php echo $sucursal->img_qr_yape ?? ''; ?>"> <!-- Elemento Oculto para guardar la imágen qr de yape -->
    <input type="hidden" id="img_qr_plin" value="<?php echo $sucursal->img_qr_yape ?? ''; ?>"> <!-- Elemento Oculto para guardar la imágen qr de plin -->

<div class="row">
    <div class="col-lg-6 position-relative">
        <div class="btn-justify">
            <div class="w-100 text-center">
                <img src="/facturacionv8/img/logo_yape.png" alt="" width="50px" class="mb-5">
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-box">
                        <img src="<?php echo $sucursal->img_qr_yape ?? ''; ?>" id="show_img_qr_yape">
                        <div class="rounded-md border border-indigo-500 bg-gray-50 p-4 shadow-md w-36" id="btn_subir_qr_yape">
                            <label class="flex flex-col items-center gap-2 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 fill-white stroke-indigo-500" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-gray-600 font-medium">Subir QR</span>
                            </label>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="label-form"><i class="icon-file-text position-left"></i> Nombre del Titular para Yape <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo $sucursal->titular_yape ?? ''; ?>" name="titular_yape" id="titular_yape" placeholder="Nombre Titular Yape">
                    </div>
                
                    <div class="form-group">
                        <label class="label-form"><i class="icon-file-text position-left"></i> Número de Celular Yape <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo $sucursal->celular_yape ?? ''; ?>" name="celular_yape" id="celular_yape" placeholder="Número de Celular Yape">
                    </div>
                    
                    <button style="display:none;" class="btn bg-indigo legitRipple" id="btn_eliminar_qr_yape" type="button"><i class="icon-floppy-disk mr-2"></i>Eliminar Qr</button>
                </div>
            </div>
        </div>
        <div class="box-check">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="factura_mostrar_yape" id="factura_mostrar_yape" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->factura_mostrar_yape) && $sucursal->factura_mostrar_yape == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Facturas?
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="boleta_mostrar_yape" id="boleta_mostrar_yape" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->boleta_mostrar_yape) && $sucursal->boleta_mostrar_yape == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Boletas?
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="notaventa_mostrar_yape" id="notaventa_mostrar_yape" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->notaventa_mostrar_yape) && $sucursal->notaventa_mostrar_yape == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Notas de Venta?
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="cotizacion_mostrar_yape" id="cotizacion_mostrar_yape" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->cotizacion_mostrar_yape) && $sucursal->cotizacion_mostrar_yape == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Cotizaciones?
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>



    <div class="col-lg-6 position-relative">
        <div class="btn-justify btn-justify-1">
            <div class="w-100 text-center">
                <img src="/facturacionv8/img/plin_logo.png" alt="" width="60px" class="mb-5">
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-box">
                        <img src="<?php echo $sucursal->img_qr_plin ?? ''; ?>" id="show_img_qr_plin">
                        <div class="rounded-md border border-azul-500 bg-gray-50 p-4 shadow-md w-36" id="btn_subir_qr_plin">
                            <label class="flex flex-col items-center gap-2 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 fill-white stroke-azul-500" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-gray-600 font-medium">Subir QR</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="label-form"><i class="icon-file-text position-left"></i> Nombre del Titular para Plin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo $sucursal->titular_plin ?? ''; ?>" name="titular_plin" id="titular_plin" placeholder="Nombre Titular Plin">
                    </div>
                
                    <div class="form-group">
                        <label class="label-form"><i class="icon-file-text position-left"></i> Número de Celular Plin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo $sucursal->celular_plin ?? ''; ?>" name="celular_plin" id="celular_plin" placeholder="Número de Celular Plin">
                    </div>
        
                    <button style="display:none;" class="btn bg-indigo legitRipple" id="btn_eliminar_qr_plin" type="button"><i class="icon-floppy-disk mr-2"></i>Eliminar QR</button>
                </div>
            </div>
        </div>

        
        <div class="box-check-1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="factura_mostrar_plin" id="factura_mostrar_plin" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->factura_mostrar_plin) && $sucursal->factura_mostrar_plin == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Facturas?
                            </label>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="boleta_mostrar_plin" id="boleta_mostrar_plin" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->boleta_mostrar_plin) && $sucursal->boleta_mostrar_plin == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Boletas?
                            </label>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="notaventa_mostrar_plin" id="notaventa_mostrar_plin" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->notaventa_mostrar_plin) && $sucursal->notaventa_mostrar_plin == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Notas de Venta?
                            </label>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="checkbox checkbox-switch">
                            <label class="label-form">
                                <input name="cotizacion_mostrar_plin" id="cotizacion_mostrar_plin" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_opt_yape_plin" data-size="mini" <?php if(isset($sucursal->cotizacion_mostrar_plin) && $sucursal->cotizacion_mostrar_plin == 'si') { echo "checked"; } ?>>
                                ¿Deseas Mostrar el Código QR en Cotizaciones?
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-12 text-right mt-5">
        <button class="btn bg-indigo legitRipple btn_branchoffice" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Todos los Datos!</button>
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
                <p>Recuerda que la imágen debe ser cuadrada, y con un ancho máximo de 300x300px, en caso tengas una imágen más grande, puedes subirla sin problemas y nosotros te ayudaremos a recortar la imágen...</p>
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