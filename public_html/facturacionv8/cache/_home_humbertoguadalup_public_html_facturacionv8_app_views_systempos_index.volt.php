<?= $this->partial('systempos/css') ?>
<?= $this->partial('systempos/css_editar_item_carrito') ?>


<input type="hidden" id="id_usuario" value="<?php echo $idusuario; ?>">
<input type="hidden" id="id_contribuyente" value="<?php echo $id_contribuyente; ?>">
<input type="hidden" id="factor_igv_sunat" value="0.18">
<input type="hidden" id="impuesto_icbper" value="<?php echo $impuesto_icbper; ?>">
<input type="hidden" id="num_decimales" value="<?php echo $num_decimales; ?>">
<input type="hidden" id="data_tipo_cambio" data-venta="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>" data-compra="<?php echo $data_tipo_cambio['tipo_cambio_compra']; ?>" data-fechaconsulta="<?php echo $data_tipo_cambio['fecha_consulta']; ?>" value="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>">

<div class="container-wrapper">
    
    <?= $this->partial('systempos/custom_nav_bar') ?>

    <div class="main-body">
        <div class="content-wrap-pt">
            
            <?= $this->partial('systempos/contenedor_productos') ?>

            <div class="content-payment">
                <div class="header-sidebar">
                    <div class="top-sidebar">
                        <div class="invoice_title">
                            <div class="doc_type_dropdown">
                                <button type="button" class="doc_type_selected" id="doc_type_selector_btn">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span id="doc_type_current">Boleta de Venta</span>
                                    <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>

                                <div class="doc_type_menu" id="doc_type_menu">
                                    <!-- Factura -->
                                    <button type="button" class="doc_type_option" data-type="01" id="doc_type_01">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="doc_type_content">
                                            Factura Electrónica
                                            <span class="doc_type_description">Para empresas con RUC</span>
                                        </div>
                                        <span class="doc_type_badge" id="sucursal_seleccionada_factura_serie">F001</span>
                                    </button>
                                    
                                    <!-- Boleta -->
                                    <button type="button" class="doc_type_option selected" data-type="03" id="doc_type_03">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="doc_type_content">
                                            Boleta de Venta
                                            <span class="doc_type_description">Para personas naturales</span>
                                        </div>
                                        <span class="doc_type_badge" id="sucursal_seleccionada_boleta_serie">B001</span>
                                    </button>
                                    
                                    <!-- Nota de Venta -->
                                    <button type="button" class="doc_type_option" data-type="77" id="doc_type_77">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="doc_type_content">
                                            Nota de Venta
                                            <span class="doc_type_description">Comprobante interno</span>
                                        </div>
                                        <span class="doc_type_badge">NV01</span>
                                    </button>
                                    
                                    <!-- Cotización -->
                                    <button type="button" class="doc_type_option" data-type="88" id="doc_type_88">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="doc_type_content">
                                            Cotización
                                            <span class="doc_type_description">Presupuesto para el cliente</span>
                                        </div>
                                        <span class="doc_type_badge">COT</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="invoice_options d-flex">
                          
                            <div class="op-doc resumen_config_parametros_carrito">
                                <div class="dropwdown-wrap-pt">
                                    <div class="title-drop-main drop-config-carr" id="tipo_cambio_container">
                                        <span id="info_seleccion_tipo_cambio_cpe">
                                            <!-- Aquí se mostrará el tipo de cambio si la moneda es dólares -->
                                        </span>
                                    </div>
                                    <div class="dropdown-menu-pt drop-op-doc">
                                        <label>Tipo de Cambio (Actual: <?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>):</label>
                                        <input name="pos_opt_global_tipocambio" id="pos_opt_global_tipocambio" type="text" class="form-control" value="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="op-doc resumen_config_parametros_carrito">
                              
                                <div class="dropwdown-wrap-pt">
                                    <div class="title-drop-main drop-config-carr">
                                        <span id="info_seleccion_igv_cpe">
                                            <!-- Aquí se mostrará el IGV seleccionado -->
                                        </span>
                                    </div>
                                    <div class="dropdown-menu-pt drop-op-doc">
                                        <label>IGV:</label>
                                        <select name="pos_opt_global_factorigv" id="pos_opt_global_factorigv" class="form-control">
                                            <option value="18">18%</option>
                                            <option value="10">10%</option>
                                            <option value="10.5">10.5%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="op-doc resumen_config_parametros_carrito">
                                <div class="dropwdown-wrap-pt">
                                    <div class="title-drop-main drop-config-carr">
                                        <span id="info_seleccion_moneda_cpe">
                                            <!-- Aquí se mostrará la moneda seleccionada -->
                                        </span>
                                    </div>
                                    <div class="dropdown-menu-pt drop-op-doc">
                                        <label>Moneda:</label>
                                        <select name="pos_opt_global_moneda" id="pos_opt_global_moneda" class="form-control">
                                            <option value="PEN">Soles</option>
                                            <option value="USD">Dólares</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="op-doc">
                                <div class="dropwdown-wrap-pt">
                                    <div class="title-drop-main drop-config-carr">
                                        <a href="javascript:void(0)"><i class="icon-office"></i></a>
                                    </div>
                                    <div class="dropdown-menu-pt drop-op-doc">
                                        <label><i class="icon-office"></i>Selecciona tu surcusal:</label>
                                        <select title="CPE" data-placeholder="" class="form-control" name="select_sucursal_in_carrito" id="select_sucursal_in_carrito">
                                            <?php foreach ($lista_sucursales as $sucursal): ?>
                                                <option 
                                                    data-serie_boleta="<? echo $sucursal->boleta_serie; ?>" 
                                                    data-serie_factura="<? echo $sucursal->factura_serie; ?>" 
                                                    data-totalproductos="<?= htmlspecialchars($sucursal->cantidad_productos, ENT_QUOTES, 'UTF-8'); ?>" 
                                                    value="<?= htmlspecialchars($sucursal->idsucursal, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars($sucursal->idsucursal.'.- '.$sucursal->nombre, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="op-doc" style="display:none;">
                                <div class="dropwdown-wrap-pt">
                                    <div class="title-drop-main drop-config-carr">
                                        <a href="javascript:void(0)"><i class="icon-stack4"></i></a>
                                    </div>
                                    <div class="dropdown-menu-pt drop-op-doc">
                                        <label><i class="icon-file-spreadsheet2"></i>Elije un documento:</label>
                                        <select title="CPE" data-placeholder="Selecciona el Tipo de Doc." class="form-control" name="id_tipodoc_electronico" id="id_tipodoc_electronico">
                                            <option value="03">BOLETA</option>
                                            <option value="01">FACTURA</option>
                                            <option value="77">NOTA DE VENTA</option>
                                            <option value="88">COTIZACION</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="data-cliente">
                        <div class="form-group d-flex d-flex-column w-100">
                            <label class="label-form" id="text_input_tipodoc_cliente">Número de D.N.I.. </label>
                            <div class="input-group">
                                <select style="display:none;" name="select_id_tipodoc_cliente" id="select_id_tipodoc_cliente">
                                    <option value="0" data-nombre="SinDoc.">Sin.Doc.</option>
                                    <option value="1" data-nombre="D.N.I." selected>DNI</option>
                                    <option value="6" data-nombre="R.U.C.">RUC</option>
                                    <option value="7" data-nombre="Pasaporte">Pasaporte</option>
                                    <option value="A" data-nombre="Ced.Diplomática">Ced.Diplomática</option>
                                    <option value="B" data-nombre="Doc.No.Domic.">Doc.No.Domic.</option>
                                    <option value="C" data-nombre="TIN">TIN</option>
                                    <option value="D" data-nombre="IN">IN</option>
                                </select>
                                <span data-idtipodocseleccionado="1" data-textidseleccionado="DNI" id="span_id_tipodoc_cliente" style="padding: 1px 6px; cursor: pointer; width: 50px; " class="input-group-addon font-weight-bold dropdown-toggle" data-toggle="dropdown"><img style="width: 30px;" src="/facturacionv8/public/img/icons_docs_identidad/dni_icon_grande_small.png" /></span>

                                <ul class="dropdown-menu dropdown-menu-left">
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente(1)"><img style="width: 30px;" src="/facturacionv8/public/img/icons_docs_identidad/dni_icon_grande_small.png" /> DNI</a></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente(6)"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/ruc_icon_small.png" /> RUC</a></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente(0)"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/otrodoc_icon_small.png" /> Otr.Doc.</a></li>
                                    <li class="divider"></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente(7)"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/otro_doc_2_grande.png" /> Pasaporte</a></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente('A')"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/pasaporte_icon_small.png" /> Ced.Diplomática</a></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente('B')"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/doc1_otro_small.png" /> Doc.No.Domic.</a></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente('C')"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/doc1_otro_small.png" /> TIN</a></li>
                                    <li><a href="javascript:void(0);" onclick="cambiar_tipodoc_cliente('D')"><img style="width: 18px;" src="/facturacionv8/public/img/icons_docs_identidad/doc1_otro_small.png" /> IN</a></li>
                                </ul>

                                <div class="input-group">
                                    <input style="border-radius: 0px !important;" type="text" value="" name="input_numdoc_cliente" id="input_numdoc_cliente" placeholder="" class="form-control" style="">
                                    <span class="input-group-btn">
                                        <button class="btn bg-indigo btn-icon legitRipple" type="button" id="btn_buscar_cliente">
                                            <i class="icon-search4"></i>
                                            <i class="icon-spinner10 spinner position-left" style="display: none;" ></i>
                                        </button>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="form-group  box-name-client">
                            <label  class="label-form">
                                <i class="icon-user mr-2"></i>
                                <span class="type_id">Nombre</span> 
                            
                            </label>
                            <div class="input-group">
                                <input type="text" title="Nombre del Cliente" name="input_nombre_cliente" id="input_nombre_cliente" placeholder="Nombre/Razón Social" class="form-control" required>
                                <span class="input-group-btn">
                                    <button class="btn bg-indigo btn-icon legitRipple" type="button" id="btn_editar_cliente">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="dropdown-menu" id="lista_sugerencias_cliente" style="width: 100%; display:none;"></div>
    
                    </div>

                    <div class="cuadro_busqueda_movil">
                        <div class="produc-box-movil control_input_facturalaya_container">
                            <input type="text" class="control_input_facturalaya_input" name="txt_busqueda_productos2" id="txt_busqueda_productos2" placeholder=" ">
                            <label class="control_input_facturalaya_label">
                                Busca Tus Productos Aquí
                            </label>
                            <div id="suggestions-box" class="suggestions-box" style="display:none;"></div>
                        </div>
                    </div>
                </div>
                <!-- <div class="carr-list-product" id="lista_productos_para_venta">
                  
                </div>    -->
                 <div class="carr-list-product carr-list-product d-flex d-flex-column" id="lista_productos_para_venta">
                    <div class="no-list-product">
                        <p><i class="icon-cart"></i></p>
                        <p>No hay productos agregados a tu carrito</p>
                    </div>
                </div>  
                
                <div class="footer-sidebar">
                    
                    <div class="control_descuento_total_container">
                        <label class="control_descuento_total_label">Descuento</label>
                        <div class="control_descuento_total_control" id="control_descuento_total">
                            <div class="control_descuento_total_type_selector">
                                <button class="control_descuento_total_type_btn active simbolo_moneda_pos" data-type="monto">S/.</button>
                                <button class="control_descuento_total_type_btn" data-type="porcentaje">%</button>
                            </div>
                            <div class="control_descuento_total_input_wrapper">
                                <span class="control_descuento_total_input_symbol">S/.</span>
                                <input type="number" name="control_descuento_total_input" class="control_descuento_total_input" placeholder="0.00">
                                <div class="control_descuento_total_input_indicator"></div>
                                <div class="control_descuento_total_tooltip">Ingrese el monto a descontar</div>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-payment-total">
                        <button class="btn bg-indigo d-block w-100" id="btn_payment"> 
                            <span>Vender</span>
                            <span id="total_venta"></span>
                        </button>
                    </div>
                    <div class="details-invoice-small" id="pv_total_items_eliminar_carrito">
                        <div id="total_items_seleccionados"></div>
                        <div id="btn_eliminar_carrito">Eliminar Items</div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <?= $this->partial('systempos/footer_carritos') ?>

</div>

<!-- Overlay de sincronización -->
<div id="syncOverlayScreen">
    <div class="sync-container">
        <div class="status-badge">SINCRONIZANDO</div>
        <div class="loader-circle"></div>
        <h3 class="sync-title">Sincronización en progreso</h3>
        <div class="sync-status">Preparando base de datos...</div>
        <div class="progress-container">
            <div class="progress-bar" style="width: 0%"></div>
        </div>
        <div class="progress-text">0%</div>
        <ul class="lista-sucursales">
            <!-- Sucursales sincronizadas aparecerán aquí -->
        </ul>
    </div>
</div>

<?= $this->partial('systempos/sidebar_menu') ?>
<?= $this->partial('systempos/modal_config_pos') ?>
<?= $this->partial('systempos/modal_editar_cliente') ?>
<?= $this->partial('systempos/modal_editar_item_carrito') ?>
<?= $this->partial('systempos/modal_payment') ?>
<?= $this->partial('systempos/modal_success_payment') ?>