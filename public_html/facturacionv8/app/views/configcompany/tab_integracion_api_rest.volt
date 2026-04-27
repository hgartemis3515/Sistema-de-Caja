<div class="panel" style="max-width: 1120px;margin: 0 auto;">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="label-form">
                        <i class="icon-barcode2 mr-2"></i> Token <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text" name="token_contribuyente" id="token_contribuyente" class="form-control" placeholder="token_contribuyente" value="<?php echo $contribuyente->token; ?>">
                        <span class="input-group-btn">
                            <button class="btn bg-indigo legitRipple btn_generar_token" type="button">
                                <i class="icon-rotate-ccw3 mr-2"></i> Generar Token
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <fieldset>
                    <legend class="text-bold">Documentación ApiRest: </legend>
                </fieldset>
                <div class="content-group tab-content-bordered navbar-component">
                    <div class="navbar navbar-inverse bg-teal-400" style="position: relative; z-index: 30;">

                        <div class="navbar-collapse collapse" id="demo1">
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <a href="#tab_doc_factura" data-toggle="tab" class="legitRipple">
                                        <img src="/facturacionv8/img/factura.svg" style="width: 18px; margin-right: 5px;">
                                        Facturas
                                    </a>
                                </li>

                                <li>
                                    <a href="#tab_doc_boleta" data-toggle="tab" class="legitRipple">
                                        <img src="/facturacionv8/img/boleta.svg" style="width: 18px; margin-right: 5px;">
                                        Boletas
                                    </a>
                                </li>

                                <li>
                                    <a href="#tab_doc_nota_credito" data-toggle="tab" class="legitRipple">
                                        <img src="/facturacionv8/img/nota_credito.svg" style="width: 18px; margin-right: 5px;">
                                        Notas de Crédito
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_doc_nota_debito" data-toggle="tab" class="legitRipple">
                                        <img src="/facturacionv8/img/nota_debito.svg" style="width: 18px; margin-right: 5px;">
                                        Notas de Débito
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_doc_guia_remision" data-toggle="tab">
                                    <i class="icon-make-group"></i> Guía de Remisión</a>
                                </li>
                                <li><a href="#tab_doc_cotizacion" data-toggle="tab">
                                    <img src="/facturacionv8/img/cotizacion.svg" style="width: 18px; margin-right: 5px;"> Cotización</a>
                                </li>
                                <li><a href="#tab_doc_nota_venta" data-toggle="tab">
                                    <img src="/facturacionv8/img/nota_venta.svg" style="width: 18px; margin-right: 5px;"> Nota de Venta</a>
                                </li>
                                
                                
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-plus-circle2"></i> Otros <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="#tab_doc_producto" data-toggle="tab">
                                            <i class="icon-clipboard2"></i> Productos</a>
                                        </li>
                                        <li><a href="#tab_doc_comunicacion_baja" data-toggle="tab"> 
                                            <i class="icon-cancel-square2 text-danger font-weight-bold  position-left"></i> Comunicación de Baja</a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                            
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade active in has-padding" id="tab_doc_factura">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Facturas:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_venta" class="form-control form-control-sm" name="url_comprobante_factura" id="url_comprobante_factura" placeholder="URL comprobantes">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo FACTURA</span> (PHP)</p>
                                        <div id="php_editor_factura">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo FACTURA</span> (JSON)</p>
                                        <div id="json_editor_factura">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_boleta">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Boletas:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_venta" class="form-control form-control-sm" name="url_comprobante_boleta" id="url_comprobante_boleta" placeholder="URL comprobantes">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo BOLETA</span> (PHP)</p>
                                        <div id="php_editor_boleta">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo BOLETA</span> (JSON)</p>
                                        <div id="json_editor_boleta">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_nota_credito">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Notas de Crédito:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_notacredito" class="form-control form-control-sm" name="url_comprobante_nota_credito" id="url_comprobante_nota_credito" placeholder="URL comprobantes">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo NOTA CRÉDITO</span> (PHP)</p>
                                        <div id="php_editor_nota_credito">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo NOTA CRÉDITO</span> (JSON)</p>
                                        <div id="json_editor_nota_credito">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_nota_debito">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Notas de Débito:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_notadebito" class="form-control form-control-sm" name="url_comprobante_nota_debito" id="url_comprobante_nota_debito" placeholder="URL comprobantes">
                                    </div>
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo NOTA DÉBITO</span> (PHP)</p>
                                        <div id="php_editor_nota_debito">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo NOTA DÉBITO</span> (JSON)</p>
                                        <div id="json_editor_nota_debito">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_guia_remision">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Guía de Remisión:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_guia_remision" class="form-control form-control-sm" name="url_comprobante_guia_remision" id="url_comprobante_guia_remision" placeholder="URL comprobantes">
                                    </div>
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo GUÍA REMISIÓN</span> (PHP)</p>
                                        <div id="php_editor_guia_remision">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo GUÍA REMISIÓN</span> (JSON)</p>
                                        <div id="json_editor_guia_remision">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_cotizacion">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Cotización:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_cotizacion" class="form-control form-control-sm" name="url_comprobante_cotizacion" id="url_comprobante_cotizacion" placeholder="URL comprobantes">
                                    </div>
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo COTIZACIÓN</span> (PHP)</p>
                                        <div id="php_editor_cotizacion">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo COTIZACIÓN</span> (JSON)</p>
                                        <div id="json_editor_cotizacion">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_nota_venta">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Nota de Venta:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/procesar_nota_venta" class="form-control form-control-sm" name="url_comprobante_nota_venta" id="url_comprobante_nota_venta" placeholder="URL comprobantes">
                                    </div>
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo NOTA VENTA</span> (PHP)</p>
                                        <div id="php_editor_nota_venta">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo NOTA VENTA</span> (JSON)</p>
                                        <div id="json_editor_nota_venta">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_producto">
                            <div class="row">
                                    
                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo: LISTAR PRODUCTOS</span> (PHP)</p>
                                        <div id="php_editor_get_productos">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Response</span> (JSON)</p>
                                        <div id="json_editor_response_get_productos">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo: EXTRAER PRODUCTO</span> (PHP)</p>
                                        <div id="php_editor_get_producto">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Response</span> (JSON)</p>
                                        <div id="json_editor_response_get_producto">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo: GET número total de Productos</span> (PHP)</p>
                                        <div id="php_editor_get_num_productos">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Response</span> (JSON)</p>
                                        <div id="json_editor_response_get_num_productos">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade has-padding" id="tab_doc_cliente">
                            
                        </div>

                        <div class="tab-pane fade has-padding" id="tab_doc_comunicacion_baja">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-briefcase mr-2"></i> 
                                            End Point para Comunicación de Baja:
                                        </label>
                                        <input type="text" value="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/api/comunicacion_baja" class="form-control form-control-sm" name="url_comunicacion_baja" id="url_comunicacion_baja" placeholder="URL comprobantes">
                                    </div>
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo COMUNICACIÓN DE BAJA</span> (PHP)</p>
                                        <div id="php_editor_comunicacion_baja">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="content-group">
                                        <p><span class="text-semibold">Ejemplo COMUMNICACIÓN DE BAJA</span> (JSON)</p>
                                        <div id="json_editor_comunicacion_baja">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>