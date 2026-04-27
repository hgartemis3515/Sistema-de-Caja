<div id="vm_config_post" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="">
                    <div class="">
                        <div class="vm_modal_facturalaya_configpv_header">
                            <h2 class="vm_modal_facturalaya_configpv_title">Configuración del Punto de Venta</h2>
                            <button class="vm_modal_facturalaya_configpv_close" data-dismiss="modal">×</button>
                        </div>

                        <div class="vm_modal_facturalaya_configpv_branch_section">
                            <label class="vm_modal_facturalaya_configpv_label">Selecciona el Almacén/Sucursal:</label>
                            <div class="vm_modal_facturalaya_configpv_select_container">
                                <select class="vm_modal_facturalaya_configpv_select" id="pos_opt_global_sucursal">
                                    <?php foreach ($lista_sucursales as $sucursal): ?>
                                        <option 
                                            data-totalproductos="<?= htmlspecialchars($sucursal->cantidad_productos, ENT_QUOTES, 'UTF-8'); ?>" 
                                            value="<?= htmlspecialchars($sucursal->idsucursal, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?= htmlspecialchars($sucursal->idsucursal.'.- '.$sucursal->nombre, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button class="vm_modal_facturalaya_configpv_sync_button" id="btn_forzar_sincronizacion_sucursal">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38"/>
                                </svg>
                                SINCRONIZAR
                            </button>

                            <div class="vm_modal_facturalaya_configpv_progress" style="display:none;">
                                <div class="vm_modal_facturalaya_configpv_progress_status">
                                    <span>Sincronizando productos...</span>
                                    <span>45%</span>
                                </div>
                                <div class="vm_modal_facturalaya_configpv_progress_bar">
                                    <div class="vm_modal_facturalaya_configpv_progress_fill"></div>
                                </div>
                            </div>

                            <div class="vm_modal_facturalaya_configpv_success" style="display:none;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>¡Fueron Sincronizados! <strong>80</strong> productos en la sucursal <strong>462 - Sucursal 02</strong></span>
                            </div>
                        </div>

                        <div class="vm_modal_facturalaya_configpv_options">
                            <div class="vm_modal_facturalaya_configpv_option" id="pos_opt_global_enviar_email">
                                <label class="vm_modal_facturalaya_configpv_switch">
                                    <input type="checkbox">
                                    <span class="vm_modal_facturalaya_configpv_slider"></span>
                                </label>
                                <span>Enviar Email al Cliente</span>
                            </div>

                            <div class="vm_modal_facturalaya_configpv_option" id="pos_opt_global_habilitar_oc">
                                <label class="vm_modal_facturalaya_configpv_switch">
                                    <input type="checkbox">
                                    <span class="vm_modal_facturalaya_configpv_slider"></span>
                                </label>
                                <span>Habilitar Número de Orden en el CPE</span>
                            </div>

                            <div class="vm_modal_facturalaya_configpv_option" id="pos_opt_global_habilitar_numplaca">
                                <label class="vm_modal_facturalaya_configpv_switch">
                                    <input type="checkbox">
                                    <span class="vm_modal_facturalaya_configpv_slider"></span>
                                </label>
                                <span>Habilitar Número de Placa en el CPE</span>
                            </div>

                            <div class="vm_modal_facturalaya_configpv_option" id="pos_opt_global_habilitar_numguia">
                                <label class="vm_modal_facturalaya_configpv_switch">
                                    <input type="checkbox">
                                    <span class="vm_modal_facturalaya_configpv_slider"></span>
                                </label>
                                <span>Habilitar Guía de Remisión en el CPE</span>
                            </div>

                            <div class="vm_modal_facturalaya_configpv_option" id="pos_opt_global_habilitar_asignarventa">
                                <label class="vm_modal_facturalaya_configpv_switch">
                                    <input type="checkbox">
                                    <span class="vm_modal_facturalaya_configpv_slider"></span>
                                </label>
                                <span>Habilitar la opción de Asignar Venta</span>
                            </div>
                        </div>

                        <div class="vm_modal_facturalaya_configpv_footer">
                            <button class="vm_modal_facturalaya_configpv_close_button" data-dismiss="modal">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>