<style>
.vm_edit_item_carrito_btn {
    padding: 8px 16px;
    border: 1px solid #ccc;
    cursor: pointer;
    transition: all 0.3s ease;
}

.vm_edit_item_carrito_btn-outline {
    background-color: white;
    color: #666;
}

.vm_edit_item_carrito_active {
    background-color: #4A90E2;
    color: white;
    border-color: #4A90E2;
}
</style>

<div id="vm_edit_item_carrito" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
            
                <div class="vm_editar_item_carrito_modal_header">
                    <h2 class="vm_editar_item_carrito_modal_title">Editar Item</h2>
                    <button class="vm_editar_item_carrito_modal_close" data-dismiss="modal">&times;</button>
                </div>

                <div class="vm_editar_item_carrito_modal_top_section">
                    <div class="vm_editar_item_carrito_modal_image_container">
                        <img id="vm_edit_item_carrito_image_producto" src="/facturacionv8/img/product.jpg" alt="Producto" class="vm_editar_item_carrito_modal_image">
                    </div>

                    <div>
                        <div class="control_icbper_en_modal_container" id="vm_edit_item_carrito_icbper_content">
                            <div class="control_icbper_en_modal_wrapper">
                            <label class="control_icbper_en_modal_switch">
                                <input type="checkbox" id="vm_edit_item_carrito_icbper_checkbox">
                                <span class="control_icbper_en_modal_slider"></span>
                            </label>
                            <div class="control_icbper_en_modal_labels">
                                <span class="control_icbper_en_modal_title">Afecto a ICBPER</span>
                                <span class="control_icbper_en_modal_status" id="vm_edit_item_carrito_icbper_status">No</span>
                            </div>
                            </div>
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Afecto a IGV</label>
                            <select id="vm_edit_item_carrito_afectacionIgv" class="vm_editar_item_carrito_modal_select vm_edit_item_carrito_select">
                                <option value="10">10 - Gravado - Operación Onerosa</option>
                                <option value="20">20 - Exonerado</option>
                                <option value="30">30 - Inafecto</option>
                            </select>
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Unidad de Medida</label>
                            <select id="vm_edit_item_carrito_unidadMedida" class="vm_editar_item_carrito_modal_select vm_edit_item_carrito_select">
                                <option value="CAJA">CAJA</option>
                                <option value="UND">UNIDAD</option>
                                <option value="KG">KILOGRAMO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="vm_editar_item_carrito_modal_form_group">
                    <label class="vm_editar_item_carrito_modal_label">Descripción</label>
                    <textarea id="vm_edit_item_carrito_descripcion" class="vm_editar_item_carrito_modal_textarea" placeholder="Ingrese la descripción del producto"></textarea>
                </div>

                <div class="vm_editar_item_carrito_modal_calculations">
                    <div class="vm_editar_item_carrito_modal_calc_grid">
                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Precio Unit. (Con IGV)</label>
                            <div class="input-group">
                                <span style="cursor: pointer;" id="vm_editar_item_carrito_dropdwon_multiprecio_con_igv" class="input-group-addon font-weight-bold dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></span>
                                <ul class="dropdown-menu dropdown-menu-left" id="vm_edit_item_carrito_lista_multiprecio_con_igv">
                                    
                                </ul>

                                <div class="vm_editar_item_carrito_modal_input_group">
                                    <span class="vm_editar_item_carrito_modal_currency vm_editar_item_carrito_simbolo_moneda">S/</span>
                                    <input id="vm_edit_item_carrito_precioConIgv" type="number" class="vm_editar_item_carrito_modal_input" value="10.00" step="0.01">
                                </div>
                            </div>
                            
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Precio Unit. (Sin IGV)</label>
                            <div class="input-group">
                                <span style="cursor: pointer;" id="vm_editar_item_carrito_dropdwon_multiprecio_sin_igv" class="input-group-addon font-weight-bold dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></span>
                                <ul class="dropdown-menu dropdown-menu-left" id="vm_edit_item_carrito_lista_multiprecio_sin_igv">
                                    
                                </ul>
                                <div class="vm_editar_item_carrito_modal_input_group">
                                    <span class="vm_editar_item_carrito_modal_currency vm_editar_item_carrito_simbolo_moneda">S/</span>
                                    <input id="vm_edit_item_carrito_precioSinIgv" type="number" class="vm_editar_item_carrito_modal_input" value="8.47" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Cantidad</label>
                            <div class="vm_editar_item_carrito_modal_input_group">
                                <svg class="vm_editar_item_carrito_modal_quantity_icon" width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <path d="M12 8v8M8 12h8"/>
                                </svg>
                                <input id="vm_edit_item_carrito_cantidad" type="number" class="vm_editar_item_carrito_modal_input" value="1" min="1">
                            </div>
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Subtotal</label>
                            <div class="vm_editar_item_carrito_modal_input_group">
                                <span class="vm_editar_item_carrito_modal_currency vm_editar_item_carrito_simbolo_moneda">S/</span>
                                <input id="vm_edit_item_carrito_subtotal" type="number" class="vm_editar_item_carrito_modal_input" value="8.47" disabled>
                            </div>
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">IGV (<span class="vm_edit_item_carrito_factor_igv"></span>%)</label>
                            <div class="vm_editar_item_carrito_modal_input_group">
                                <span class="vm_editar_item_carrito_modal_currency vm_editar_item_carrito_simbolo_moneda">S/</span>
                                <input id="vm_edit_item_carrito_igv" type="number" class="vm_editar_item_carrito_modal_input" value="1.53" disabled>
                            </div>
                        </div>

                        <div class="vm_editar_item_carrito_modal_form_group">
                            <label class="vm_editar_item_carrito_modal_label">Total</label>
                            <div class="vm_editar_item_carrito_modal_input_group">
                                <span class="vm_editar_item_carrito_modal_currency vm_editar_item_carrito_simbolo_moneda">S/</span>
                                <input id="vm_edit_item_carrito_total" type="number" class="vm_editar_item_carrito_modal_input" value="10.00" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="vm_editar_item_carrito_modal_footer">
                    <button class="vm_editar_item_carrito_modal_button vm_editar_item_carrito_modal_button_cancel" data-dismiss="modal">Cancelar</button>
                    <button class="vm_editar_item_carrito_modal_button vm_editar_item_carrito_modal_button_accept" id="vm_edit_item_carrito_btn_aceptar">Aceptar</button>
                </div>
                
            </div>
        </div>
    </div>
</div>