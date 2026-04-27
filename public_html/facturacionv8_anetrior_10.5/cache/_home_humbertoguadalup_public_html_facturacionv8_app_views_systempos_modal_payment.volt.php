<div id="vm_paymentcar_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #f5f7ff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="vm_paymentcar_title"></h5>
            </div>

            <div class="modal-body" id="vm_paymentcar_contenido">
                <div class="d-flex body-payment-car">
                    <div class="details-products">
                        <div class="vm_paymentcar_contenedor_info_cliente_opts_globales">
                            <div class="vm_paymentcar_content_cliente_vendedor" style="display: flex; gap: 12px; width: 100%; justify-content: space-between;">
                                <div class="items-input" style="flex: auto;">
                                    <div class="form-group">
                                        <label class="label-form">
                                            <i class="icon-user mr-2"></i>
                                            <span class="type_id">Cliente</span>
                                        </label>
                                        <input type="text" title="Datos del Cliente" name="vm_paymentcar_datos_cliente" id="vm_paymentcar_nombre_cliente" value="" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="items-input" id="vm_paymentcar_content_asignar_venta">
                                    <div class="form-group box-name-client">
                                        <label class="label-form">Asignación de venta</label>
                                        <select id="vm_paymentcar_asignar_vendedor" title="CPE" data-placeholder="Selecciona el Tipo de Doc." class="form-control" name="">
                                        
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="items-input input-separate d-flex d-flex-wrap w-100" id="vm_paymentcar_opt_globales_placa_numorden_guia">
                                <div class="seccion_pagos_divididos_input-row" style="margin-bottom: 15px;">
                                    <div class="seccion_pagos_divididos_floating-input" id="vm_paymentcar_content_num_placa">
                                        <input type="text" title="N° de placa" name="vm_paymentcar_num_placa" id="vm_paymentcar_num_placa" placeholder=" " class="form-control" required>
                                        <label>N° de Placa</label>
                                    </div>
                                    
                                    <div class="seccion_pagos_divididos_floating-input" id="vm_paymentcar_content_num_orden">
                                        <input type="text" title="N° de Orden" name="vm_paymentcar_num_orden" id="vm_paymentcar_num_orden" placeholder=" " class="form-control" required>
                                        <label>N° de Orden</label>
                                    </div>
                                    
                                    <div class="seccion_pagos_divididos_floating-input" id="vm_paymentcar_content_num_guia">
                                        <input type="text" title="N° de Orden" name="vm_paymentcar_num_guia" id="vm_paymentcar_num_guia" placeholder=" " class="form-control" required>
                                        <label>N° de Guía</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- aqui sección de pagos divididos -->
                        <div class="seccion_pagos_divididos_container">
                            <div class="seccion_pagos_divididos_header">
                                <div class="seccion_pagos_divididos_summary">
                                    <div class="seccion_pagos_divididos_summary_item">
                                        <span class="seccion_pagos_divididos_summary_label">Total de pagos</span>
                                        <span class="seccion_pagos_divididos_summary_value" id="vm_paymentcar_total_pagos">4 pagos</span>
                                    </div>
                                    <div class="seccion_pagos_divididos_summary_item">
                                        <span class="seccion_pagos_divididos_summary_label">Monto total</span>
                                        <span class="seccion_pagos_divididos_summary_value" id="vm_paymentcar_monto_total">S/ 0.00</span>
                                    </div>
                                    <div class="seccion_pagos_divididos_summary_item">
                                        <span class="seccion_pagos_divididos_summary_label">Vuelto</span>
                                        <span class="seccion_pagos_divididos_summary_value" id="vm_paymentcar_vuelto">S/ 0.00</span>
                                    </div>
                                </div>
                                <div class="seccion_pagos_divididos_actions">
                                    <!-- al hacer click en este botón se agregará nuevos métodos de pago -->
                                    <button class="seccion_pagos_divididos_button seccion_pagos_divididos_add-button">
                                        <svg class="seccion_pagos_divididos_icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Agregar
                                    </button>

                                    <!-- al hacer click en este botón se eliminará el último método de pago -->
                                    <button class="seccion_pagos_divididos_button seccion_pagos_divididos_remove-button">
                                        <svg class="seccion_pagos_divididos_icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M6 12h12" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="seccion_pagos_divididos_list">
                            
                            </div>
                            <div class="text-center btn_procesar_venta_movil">
                                <div class="order-summary vm_paymentcar_order_summary">
                                    <div class="summary-row vm_paymentcar_sub_total_content">
                                        <span>Subtotal:</span>
                                        <span class="vm_paymentcar_sub_total"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_descuento_content">
                                        <span>Descuento (-):</span>
                                        <span class="vm_paymentcar_total_descuento"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_gravado_content">
                                        <span>Gravado:</span>
                                        <span class="vm_paymentcar_total_gravado"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_exonerado_content">
                                        <span>Exonerado:</span>
                                        <span class="vm_paymentcar_total_exonerado"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_inafecto_content">
                                        <span>Inafecto:</span>
                                        <span class="vm_paymentcar_total_inafecto"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_exportacion_content">
                                        <span>Exportación:</span>
                                        <span class="vm_paymentcar_total_exportacion"> </span>
                                    </div>
                                    
                                    <div class="summary-row vm_paymentcar_total_gratuito_content">
                                        <span>Gratuito</span>
                                        <span class="vm_paymentcar_total_gratuito"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_igv_content">
                                        <span>IGV (18%)</span>
                                        <span class="vm_paymentcar_total_igv"> </span>
                                    </div>
                                    <div class="summary-row vm_paymentcar_total_icbper_content">
                                        <span>Imp. ICBPER</span>
                                        <span class="vm_paymentcar_total_icbper"> </span>
                                    </div>
                                    <div class="total-row vm_paymentcar_total_total_a_pagar_content">
                                        <span>Total a Pagar</span>
                                        <span class="vm_paymentcar_total_total_a_pagar"> </span>
                                    </div>
                                </div>
                                <button class="btn bg-indigo btn_procesar_venta"><i class="ph-money position-left"></i>Procesar Venta</button>

                            </div>
                        </div>
                        <!-- fin sección de pagos divididos -->
                    </div>

                    <div id="vm_paymentcar_details_price" class="details-price order-container d-flex d-flex-column">
                        <div class="order-header">
                            <h2>Lista de productos</h2>
                        </div>
                        <div id="vm_paymentcar_order_list_content" class="order-list-content">
                            <div class="order-items">
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="https://www.flavorrightla.com/wp-content/uploads/2022/02/Torta-Vintage-Foto-400x400.png" alt="Raspberry tart"/>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-info">
                                            <span class="item-name">Producto Torta</span>
                                            <span class="item-price">$6.12</span>
                                        </div>
                                        <span class="item-quantity font-weight-bold">x1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="vm_paymentcar_order_summary" class="order-summary">
                            <div class="summary-row vm_paymentcar_sub_total_content">
                                <span>Subtotal:</span>
                                <span class="vm_paymentcar_sub_total"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_descuento_content">
                                <span>Descuento (-):</span>
                                <span class="vm_paymentcar_total_descuento"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_gravado_content">
                                <span>Gravado:</span>
                                <span class="vm_paymentcar_total_gravado"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_exonerado_content">
                                <span>Exonerado:</span>
                                <span class="vm_paymentcar_total_exonerado"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_inafecto_content">
                                <span>Inafecto:</span>
                                <span class="vm_paymentcar_total_inafecto"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_exportacion_content">
                                <span>Exportación:</span>
                                <span class="vm_paymentcar_total_exportacion"> </span>
                            </div>
                            
                            <div class="summary-row vm_paymentcar_total_gratuito_content">
                                <span>Gratuito</span>
                                <span class="vm_paymentcar_total_gratuito"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_igv_content">
                                <span>IGV (18%)</span>
                                <span class="vm_paymentcar_total_igv"> </span>
                            </div>
                            <div class="summary-row vm_paymentcar_total_icbper_content">
                                <span>Imp. ICBPER</span>
                                <span class="vm_paymentcar_total_icbper"> </span>
                            </div>
                            <div class="total-row vm_paymentcar_total_total_a_pagar_content">
                                <span>Total a Pagar</span>
                                <span class="vm_paymentcar_total_total_a_pagar"> </span>
                            </div>
                        </div>
                        <button class="btn bg-indigo btn_procesar_venta"><i class="icon-cart pos position-left"></i>Procesar Venta</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--
<div id="vm_paymentcar_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="vm_paymentcar_title">Pagar Factura</h5>
            </div>

            <div class="modal-body">
                <div class="d-flex body-payment-car">
                    <div class="details-products">
                        <div class="tabs-container" style="display:none;">
                            <div class="tabs-header">
                                <div id="vm_paymentcar_tab_boleta" class="tab active" onclick="switchTab(0)">Boleta De Venta</div>
                                <div id="vm_paymentcar_tab_factura" class="tab" onclick="switchTab(1)">Factura</div>
                                <div id="vm_paymentcar_tab_nota_venta" class="tab" onclick="switchTab(2)">Nota De Venta</div>
                                <div id="vm_paymentcar_tab_cotizacion" class="tab" onclick="switchTab(3)">Cotización</div>
                            </div>
                        </div>

                        <div class="vm_paymentcar_contenedor_info_cliente_opts_globales">
                            <div class="items-input">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-user mr-2"></i>
                                        <span class="type_id">Cliente</span>
                                    </label>
                                    <input type="text" title="Datos del Cliente" name="vm_paymentcar_datos_cliente" id="vm_paymentcar_nombre_cliente" value="" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="items-input input-separate d-flex d-flex-wrap w-100" id="vm_paymentcar_opt_globales_placa_numorden_guia">
                                <div class="form-group" id="vm_paymentcar_content_num_placa">
                                    <label class="label-form">
                                        <i class="icon-truck"></i>
                                        N° de placa
                                    </label>
                                    <input type="text" title="N° de placa" name="vm_paymentcar_num_placa" id="vm_paymentcar_num_placa" placeholder="Número de placa" class="form-control" required>
                                </div>
                                <div class="form-group" id="vm_paymentcar_content_num_orden">
                                    <label class="label-form">
                                        <i class="icon-file-text2"></i>
                                        N° de Orden
                                    </label>
                                    <input type="text" title="N° de Orden" name="vm_paymentcar_num_orden" id="vm_paymentcar_num_orden" placeholder="Número de orden" class="form-control" required>
                                </div>
                                <div class="form-group" id="vm_paymentcar_content_num_guia">
                                    <label class="label-form">
                                        <i class="icon-file-text2 position-left"></i>
                                        N° de Guía
                                    </label>
                                    <input type="text" title="N° de Guía" name="vm_paymentcar_num_guia" id="vm_paymentcar_num_guia" placeholder="Número de guía" class="form-control" required>
                                </div>
                            </div>
                            <div class="items-input" id="vm_paymentcar_content_asignar_venta">
                                <div class="form-group box-name-client">
                                    <label class="label-form">Asignación de venta</label>
                                    <select id="vm_paymentcar_asignar_vendedor" title="CPE" data-placeholder="Selecciona el Tipo de Doc." class="select2" name="">
                                        <option value="idusuario1">Nombre y Apellido Usuario 1</option>
                                        <option value="idusuario2">Nombre y Apellido Usuario 1</option>
                                        <option value="idusuario3">Nombre y Apellido Usuario 1</option>
                                        <option value="idusuario4">Nombre y Apellido Usuario 1</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-between p-1-t">
                            <p class="font-weight-bold">Elige en cuántos pagos quieres dividir la factura.</p>
                            <div id="vm_paymentcar_btn_add_payment" class="btn-add-payment d-flex align-items">
                                <button class="btn bg-black" id="vm_paymentcar_eliminar_ultimo_detalle_pago_cpe">-</button>
                                <span id="vm_paymentcar_payment_count">1</span>
                                <button class="btn bg-black" id="vm_paymentcar_agregar_detalle_pago_cpe">+</button>
                            </div>
                        </div>
                            
                        <div id="vm_paymentcar_list_slip_payment" class="list-slip-payment">
                            
                            <div class="split-item d-flex d-flex-wrap">
                                <div class="img-split-type">
                                    <i class="icon-wallet"></i>
                                </div>

                                <div class="select-main-split">
                                    <label>Modalidad</label>
                                    <select name="vm_paymentcar_modalidad_transferencia" id="vm_paymentcar_modalidad_transferencia" class="select2">
                                        <option value="01">Transferencia</option>
                                        <option>Credit card</option>
                                    </select>
                                </div>
                                <div class="form-group w-100">
                                    <label>Num. Ope</label>
                                    <input type="text" title="Número de Operación" name="vm_paymentcar_num_ope" id="vm_paymentcar_num_ope" placeholder="N° de Ope." class="form-control" required>
                                </div>
                                <div class="form-group w-100">
                                    <label>Banco</label>
                                    <select name="vm_paymentcar_banco" id="vm_paymentcar_banco" class="select2 select-item-split">
                                        
                                    </select>
                                </div>
                                <div class="form-group w-100">
                                    <label>Fecha Transf.</label>
                                    <div class="input-group">
                                        <span class="input-group-addon font-weight-bold simbolo_moneda"><i class="icon-calendar2 position-left"></i></span>
                                        <input type="date" class="form-control" value="" name="vm_paymentcar_fecha_transf" id="vm_paymentcar_fecha_transf">
                                    </div>
                                </div>
                                <div class="form-group w-100">
                                    <label>Monto</label>
                                    <div class="input-group">
                                        <span class="input-group-addon font-weight-bold simbolo_moneda">S/</span>
                                        <input type="text" class="form-control" value="" name="vm_paymentcar_monto_efectivo" id="vm_paymentcar_monto_efectivo" placeholder="Monto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="vm_paymentcar_details_price" class="details-price order-container d-flex d-flex-column">
                        <div class="order-header">
                            <h2>Lista de productos</h2>
                        </div>
                        <div id="vm_paymentcar_order_list_content" class="order-list-content">
                            <div class="order-items">
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="https://www.flavorrightla.com/wp-content/uploads/2022/02/Torta-Vintage-Foto-400x400.png" alt="Raspberry tart"/>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-info">
                                            <span class="item-name">Producto Torta</span>
                                            <span class="item-price">$6.12</span>
                                        </div>
                                        <span class="item-quantity font-weight-bold">x1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="vm_paymentcar_order_summary" class="order-summary">
                            <div class="summary-row" id="vm_paymentcar_sub_total">
                                <span>Subtotal:</span>
                                <span> </span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_descuento">
                                <span>Descuento (-):</span>
                                <span> </span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_gravado">
                                <span>Gravado:</span>
                                <span>$0.00</span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_exonerado">
                                <span>Exonerado:</span>
                                <span>$0.00</span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_inafecto">
                                <span>Inafecto:</span>
                                <span>$0.00</span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_exportacion">
                                <span>Exportación:</span>
                                <span>$0.00</span>
                            </div>
                            
                            <div class="summary-row" id="vm_paymentcar_total_gratuito">
                                <span>Gratuito</span>
                                <span>$0.00</span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_igv">
                                <span>IGV (18%)</span>
                                <span>$0.00</span>
                            </div>
                            <div class="summary-row" id="vm_paymentcar_total_icbper">
                                <span>Imp. ICBPER</span>
                                <span>$0.00</span>
                            </div>
                            <div class="total-row" id="vm_paymentcar_total_total_a_pagar">
                                <span>Total a Pagar</span>
                                <span>$0.00</span>
                            </div>
                        </div>
                        <button class="btn bg-indigo" id="btn_procesar_venta"><i class="icon-cart pos position-left"></i>Procesar compra</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="vm_paymentcar_btn_close_modal" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
-->