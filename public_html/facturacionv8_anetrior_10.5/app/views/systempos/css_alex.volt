<style>
    .control_descuento_total_container {
        font-family: system-ui, -apple-system, sans-serif;
        max-width: 100%;
        padding-top: 5px;
        padding-bottom: 15px;
    }

    .control_descuento_total_label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .control_descuento_total_control {
        display: flex;
        height: 42px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .control_descuento_total_type_selector {
        display: flex;
        border-right: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .control_descuento_total_type_btn {
        padding: 0 16px;
        border: none;
        background: transparent;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .control_descuento_total_type_btn:first-child {
        border-right: 1px solid #e5e7eb;
    }

    .control_descuento_total_type_btn:hover {
        background: #f3f4f6;
    }

    .control_descuento_total_type_btn.active {
        background: #6366f1;
        color: white;
    }

    .control_descuento_total_input_wrapper {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
    }

    .control_descuento_total_input {
        width: 100%;
        height: 100%;
        padding: 0 32px;
        border: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .control_descuento_total_input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    .control_descuento_total_input_symbol {
        position: absolute;
        left: 12px;
        color: #6b7280;
        font-weight: 500;
        pointer-events: none;
    }

    .control_descuento_total_input_indicator {
        position: absolute;
        right: 12px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #d1d5db;
        transition: background-color 0.2s ease;
    }

    .control_descuento_total_input_indicator.active {
        background: #22c55e;
    }

    .control_descuento_total_tooltip {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        padding: 6px 10px;
        background: #374151;
        color: white;
        font-size: 12px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }

    .control_descuento_total_tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #374151 transparent transparent transparent;
    }

    .control_descuento_total_input_wrapper:hover .control_descuento_total_tooltip {
        opacity: 1;
        visibility: visible;
    }
</style>

<style>
.control_icbper_en_modal_container {
  padding: 6px 15px;
  margin-bottom: 9px;
  border-radius: 8px;
  background: #f8f9fa;
  font-family: system-ui, -apple-system, sans-serif;
}

.control_icbper_en_modal_wrapper {
  display: flex;
  align-items: center;
  gap: 12px;
}

.control_icbper_en_modal_switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
  min-width: 50px;
}

.control_icbper_en_modal_switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.control_icbper_en_modal_slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .3s;
  border-radius: 24px;
}

.control_icbper_en_modal_slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
}

input:checked + .control_icbper_en_modal_slider {
  background-color: #4F46E5;
}

input:checked + .control_icbper_en_modal_slider:before {
  transform: translateX(26px);
}

.control_icbper_en_modal_labels {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.control_icbper_en_modal_title {
  font-size: 14px;
  color: #374151;
  font-weight: 500;
}

.control_icbper_en_modal_status {
  font-size: 12px;
  color: #6B7280;
}
</style>


<style>
.modal_success_payment_overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1060;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal_success_payment_container {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    width: 90%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.modal_success_payment_close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
    padding: 0.5rem;
}

.modal_success_payment_header {
    text-align: center;
    margin-bottom: 2rem;
}

.modal_success_payment_icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    background: #6366F1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal_success_payment_icon svg {
    width: 40px;
    height: 40px;
    color: white;
}

.modal_success_payment_title {
    color: #1F2937;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.modal_success_payment_subtitle {
    color: #6B7280;
    margin-top: 0.5rem;
}

.modal_success_payment_info {
    background: #F3F4F6;
    border-radius: 12px;
    padding: 1rem;
    margin: 1rem 0;
}

.modal_success_payment_info_row {
    display: flex;
    justify-content: space-between;
    margin: 0.5rem 0;
}

.modal_success_payment_info_label {
    color: #6B7280;
    font-size: 0.875rem;
}

.modal_success_payment_info_value {
    color: #1F2937;
    font-weight: 500;
}

.modal_success_payment_actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.modal_success_payment_button {
    flex: 1;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.modal_success_payment_button_primary {
    background: #6366F1;
    color: white;
    border: none;
}

.modal_success_payment_button_secondary {
    background: white;
    color: #6366F1;
    border: 1px solid #6366F1;
}

.modal_success_payment_button svg {
    width: 20px;
    height: 20px;
}
</style>

<style>
.doc_type_dropdown {
    position: relative;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.doc_type_selected {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: #1F2937;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.doc_type_selected:hover {
    background: rgba(99, 102, 241, 0.1);
}

.doc_type_selected svg.icon {
    width: 16px;
    height: 16px;
}

.doc_type_selected svg.arrow {
    width: 14px;
    height: 14px;
    margin-left: 4px;
    transition: transform 0.2s ease;
}

.doc_type_selected.active svg.arrow {
    transform: rotate(180deg);
}

.doc_type_menu {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 4px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 6px;
    min-width: 220px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
}

.doc_type_menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.doc_type_option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    color: #4B5563;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.15s ease;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
}

.doc_type_option:hover {
    background: #F3F4F6;
    color: #6366F1;
}

.doc_type_option.selected {
    background: #EEF2FF;
    color: #6366F1;
}

.doc_type_option svg {
    width: 18px;
    height: 18px;
}

.doc_type_option span.doc_type_description {
    display: block;
    font-size: 11px;
    color: #9CA3AF;
    margin-top: 2px;
}

.doc_type_option.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.doc_type_badge {
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: auto;
    background: #E5E7EB;
    color: #6B7280;
}

.doc_type_option:hover .doc_type_badge {
    background: #6366F1;
    color: white;
}
</style>

<style>
.modal_aviso_cpe_generados_overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

/* Asegurar que el contenedor del modal no sea más alto que la pantalla */
.modal_aviso_cpe_generados_container {
    width:auto;
    max-width: 600px;
    max-height: 90vh; /* Altura máxima del 90% de la ventana */
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal_aviso_cpe_generados_header {
    background: #7B7CFF; /* Color más similar a tu interfaz */
    padding: 1.5rem;
    color: white;
    position: relative;
}

.modal_aviso_cpe_generados_title {
    margin: 0;
    font-size: 17px; /* Aumentado */
    font-weight: 500;
}

.modal_aviso_cpe_generados_close {
    position: absolute;
    right: 1.5rem;
    top: 1.5rem;
    background: transparent;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.modal_aviso_cpe_generados_body {
    padding: 1.5rem;
    max-height: 60vh; /* Altura máxima del 60% de la ventana */
    overflow-y: auto; /* Habilitar scroll vertical */
}

.modal_aviso_cpe_generados_message {
    background-color: #F0F0FF; /* Más suave */
    padding: 1rem 1.25rem;
    border-radius: 8px;
    color: #4c4c85;
    font-size: 14px; /* Aumentado */
    margin-bottom: 1.5rem;
}

.modal_aviso_cpe_generados_item {
    background: #F8F9FE;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
    border: 1px solid #E8E9F5;
}

.modal_aviso_cpe_generados_item:hover {
    background: #F3F4FF;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(123, 124, 255, 0.1);
}

.modal_aviso_cpe_generados_item_header h3 {
    color: #7B7CFF;
    margin: 0 0 0.75rem 0;
    font-size: 14px; /* Aumentado */
    font-weight: 500;
}

.modal_aviso_cpe_generados_date {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal_aviso_cpe_generados_actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.25rem;
}

.modal_aviso_cpe_generados_btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-size: 14px; /* Aumentado */
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.modal_aviso_cpe_generados_btn_a4 {
    background-color: #F0F0FF;
    color: #7B7CFF;
}

.modal_aviso_cpe_generados_btn_ticket {
    background-color: #F3F4FF;
    color: #7B7CFF;
}

.modal_aviso_cpe_generados_btn_xml {
    background-color: #F0FAFB;
    color: #0ea5a5;
}

.modal_aviso_cpe_generados_footer {
    padding: 1.5rem;
    background: #F8F9FE;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-top: 1px solid #E8E9F5;
}

.modal_aviso_cpe_generados_btn_cancel {
    padding: 0.75rem 1.5rem;
    background: #F0F0F0;
    color: #4a5568;
    border: none;
    border-radius: 8px;
    font-size: 14px; /* Aumentado */
    font-weight: 500;
    cursor: pointer;
}

.modal_aviso_cpe_generados_btn_generate {
    padding: 0.75rem 1.5rem;
    background: #7B7CFF;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px; /* Aumentado */
    font-weight: 500;
    cursor: pointer;
}

.modal_aviso_cpe_generados_btn:hover {
    transform: translateY(-1px);
    filter: brightness(1.05);
}

/* Estilizar la barra de scroll para hacerla más moderna */
.modal_aviso_cpe_generados_body::-webkit-scrollbar {
    width: 8px;
}

.modal_aviso_cpe_generados_body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.modal_aviso_cpe_generados_body::-webkit-scrollbar-thumb {
    background: #7B7CFF;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 640px) {
    .modal_aviso_cpe_generados_container {
        width: 92%;
        margin: 15px;
    }

    .modal_aviso_cpe_generados_actions {
        flex-direction: row;
    }

    .modal_aviso_cpe_generados_btn {
        width: 100%;
        justify-content: center;
    }

    .modal_aviso_cpe_generados_footer {
        flex-direction: column;
    }

    .modal_aviso_cpe_generados_btn_cancel,
    .modal_aviso_cpe_generados_btn_generate {
        width: 100%;
        text-align: center;
    }
}
</style>


<style>
    /* Contenedor principal de la tarjeta */
    .card_product_new_container {
        position: relative;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        width: 280px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
        overflow: hidden;
        margin: 20px;
    }

    .card_product_new_container:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .card_product_new_container.selected-item {
        border: 2px solid #4f46e5;
    }

    /* Contenedor de la imagen */
    .card_product_new_image_container {
        position: relative;
        width: 100%;
        height: 180px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .card_product_new_image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
        transition: transform 0.3s ease;
    }

    .card_product_new_container:hover .card_product_new_image {
        transform: scale(1.05);
    }

    /* Badge de tipo de producto */
    .card_product_new_type_badge {
        position: absolute;
        bottom: 0.75rem;
        left: 50%;
        transform: translateX(-50%);
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 11px;
        font-weight: 350;
        z-index: 1;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .item_en_carrito_badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 11px;
        font-weight: 350;
        z-index: 1;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .item_en_carrito_badge.Gratuito {
        background-color: #d1fae5;
        color: #065f46;
    }

    .card_product_new_type_badge.Exonerado {
        background-color: #fef3c7;
        color: #92400e;
    }

    .card_product_new_type_badge.Gravado {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .card_product_new_type_badge.Gratuito {
        background-color: #d1fae5;
        color: #065f46;
    }

    .card_product_new_type_badge.Inafecto {
        background-color: #f3e8ff;
        color: #6b21a8;
    }

    .card_product_new_type_badge.Exportacion {
        background-color: #e0e7ff;
        color: #3730a3;
    }

    /* Indicador de selección */
    .card_product_new_selected_indicator {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #4f46e5;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 350;
        z-index: 1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Botón de estrella */
    .card_product_new_star {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .card_product_new_star svg {
        width: 25px;
        height: 25px;
        transition: all 0.2s ease;
    }

    .card_product_new_star.highlighted svg {
        fill: #fbbf24;
        color: #fbbf24;
    }

    .card_product_new_star:not(.highlighted) svg {
        fill: #f3f4f6;
        color: #d1d5db;
    }

    /* Contenido de la tarjeta */
    .card_product_new_content {
        padding: 1rem;
    }

    .card_product_new_name {
        font-size: 15px;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
        line-height: 18px;
    }

    .card_product_new_price {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: baseline;
        margin: 0.75rem 0;
    }

    .card_product_new_price span {
        font-size: 13px;
        font-weight: 400;
        color: #6b7280;
        margin-left: 0.25rem;
    }

    /* Información de stock */
    .card_product_new_stock {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #6b7280;
        padding: 0.5rem 0;
        border-top: 1px solid #e5e7eb;
        margin-top: 0.5rem;
    }

    .card_product_new_stock svg {
        margin-right: 0.5rem;
        flex-shrink: 0;
    }

    .card_product_new_stock.low-stock {
        color: #dc2626;
    }

    /* Selector de presentación */
    .card_product_new_presentation select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 14px;
        margin: 0.5rem 0;
        background-color: white;
        cursor: pointer;
    }

    /* Botón de agregar/quitar del carrito */
    .card_product_new_add_button {
        width: 100%;
        -webkit-appearance: none;
        background: -webkit-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
        background: linear-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
        background-size: 500%;
        color: white;
        padding: 0.625rem 1rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 15px;
        font-weight: 400;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 0.5rem;
    }

    .card_product_new_add_button:hover {
        animation-name: gradient;
        -webkit-animation-name: gradient;
        animation-duration: 2s;
        -webkit-animation-duration: s;
        animation-iteration-count: 1;
        -webkit-animation-iteration-count: 1;
        animation-fill-mode: forwards;
        -webkit-animation-fill-mode: forwards;
        color: #fff;
    }

    .card_product_new_container.selected-item .card_product_new_add_button {
        /*background-color: #dc2626;*/
    }

    .card_product_new_container.selected-item .card_product_new_add_button:hover {
        /*background-color: #b91c1c;*/
    }

    /* Grid para mostrar múltiples productos */
    .card_product_new_grid {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        background-color: #f3f4f6;
    }
</style>

<style>
/* Base y Contenedores */
.seccion_pagos_divididos_container * {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.seccion_pagos_divididos_container {
    max-width: 800px;
    margin: 0 auto;
}

.seccion_pagos_divididos_case {
    position: relative;
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Header y Resumen */
.seccion_pagos_divididos_header {
    background: white;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    gap: 12px;
    justify-content: space-between;
   
}

.seccion_pagos_divididos_header_title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
}

.seccion_pagos_divididos_summary {
    display: flex;
    gap: 24px;
}

.seccion_pagos_divididos_summary_item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.seccion_pagos_divididos_summary_label {
    font-size: 13px;
    color: #6b7280;
}

.seccion_pagos_divididos_summary_value {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}
.seccion_pagos_divididos_list {
    height: 430px;
    overflow-y: scroll;
    overflow-x: hidden;
    padding: 10px 10px 10px 0;
}
/* Select Personalizado */
.seccion_pagos_divididos_select-container {
    position: relative;
    margin-bottom: 16px;
}

.seccion_pagos_divididos_select-header {
    width: 100%;
    padding: 12px 16px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    color: #6b7280;
}

.seccion_pagos_divididos_select-header:hover {
    border-color: #6366f1;
}

.seccion_pagos_divididos_payment-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 4px;
}

.seccion_pagos_divididos_arrow {
    margin-left: auto;
}

.seccion_pagos_divididos_select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    z-index: 10;
    padding: 8px 0;
    display: none;
}

.seccion_pagos_divididos_select-option {
    padding: 12px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
}

.seccion_pagos_divididos_select-option:hover {
    background: #f9fafb;
}

.seccion_pagos_divididos_option-content {
    display: flex;
    flex-direction: column;
}

.seccion_pagos_divididos_option-title {
    font-weight: 500;
    color: #111827;
    margin-bottom: 2px;
}

.seccion_pagos_divididos_option-description {
    font-size: 13px;
    color: #6b7280;
}

/* Fila de Inputs */
.seccion_pagos_divididos_input-row {
    display: flex;
    gap: 12px;
    width: 100%;
}

.seccion_pagos_divididos_input-row > div {
    flex: 1;
    min-width: 0;
}

/* Inputs Flotantes y Labels */
.seccion_pagos_divididos_floating-input {
    position: relative;
}

.seccion_pagos_divididos_floating-input input {
    width: 100%;
    height: 48px;
    padding: 16px 12px 4px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
    color: #111827;
    background: white;
}

/* Corrección para inputs date */
.seccion_pagos_divididos_floating-input input[type="date"] {
    color: #111827;
    background-color: white;
}

.seccion_pagos_divididos_floating-input input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0.5;
}

/* Labels flotantes */
.seccion_pagos_divididos_floating-input label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #6b7280;
    pointer-events: none;
    transition: all 0.2s;
    background: transparent;
}

/* Estados focus y contenido */
.seccion_pagos_divididos_floating-input input:focus,
.seccion_pagos_divididos_floating-input input:not(:placeholder-shown) {
    padding-top: 16px;
}

.seccion_pagos_divididos_floating-input input:focus + label,
.seccion_pagos_divididos_floating-input input:not(:placeholder-shown) + label {
    top: 8px;
    font-size: 11px;
}

.seccion_pagos_divididos_floating-input input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
}

/* Input de Monto */
.seccion_pagos_divididos_amount-input input {
    padding-left: 28px;
}

.seccion_pagos_divididos_amount-input::before {
    content: 'S/';
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 14px;
    z-index: 1;
}

.seccion_pagos_divididos_amount-input label {
    left: 28px;
}

/* Select Flotante */
.seccion_pagos_divididos_floating-select {
    position: relative;
}

.seccion_pagos_divididos_floating-select select {
    width: 100%;
    height: 48px;
    padding: 16px 12px 4px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
    appearance: none;
    background-color: white;
    color: #111827;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
}

.seccion_pagos_divididos_floating-select label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #6b7280;
    pointer-events: none;
    transition: all 0.2s;
    background: transparent;
}

/* Estados del select */
.seccion_pagos_divididos_floating-select select:focus,
.seccion_pagos_divididos_floating-select select:not([value=""]) {
    padding-top: 16px;
}

.seccion_pagos_divididos_floating-select select:focus + label,
.seccion_pagos_divididos_floating-select select:not([value=""]) + label {
    top: 8px;
    font-size: 11px;
}

.seccion_pagos_divididos_floating-select select:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
}

/* Botones */
.seccion_pagos_divididos_actions {
    display: flex;
    gap: 12px;
}

.seccion_pagos_divididos_button {
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.seccion_pagos_divididos_add-button {
    background: #6366f1;
    color: white;
}

.seccion_pagos_divididos_add-button:hover {
    background: #4f46e5;
}

.seccion_pagos_divididos_remove-button {
    background: #fee2e2;
    color: #ef4444;
}

.seccion_pagos_divididos_remove-button:hover {
    background: #fecaca;
}

/* Botón de eliminación */
.seccion_pagos_divididos_delete-button {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ef4444;
    border: 2px solid white;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s;
}

.seccion_pagos_divididos_delete-button:hover {
    background: #dc2626;
    transform: scale(1.1);
}

/* Íconos */
.seccion_pagos_divididos_icon {
    width: 16px;
    height: 16px;
}

/* Media Queries */
@media (max-width: 768px) {
    body {
        padding: 16px;
    }

    .seccion_pagos_divididos_container {
        padding: 0 0px;
    }

    #vm_paymentcar_contenido {
        position: relative;
        padding: 10px;
    }

    .details-products {
        padding-right: 1px;
    }

    .seccion_pagos_divididos_list {
        height: 230px;
    }

    .seccion_pagos_divididos_case {
        padding: 16px;
        margin-bottom: 16px;
    }

    .seccion_pagos_divididos_input-row {
        gap: 8px;
    }
}

@media (max-width: 480px) {
    body {
        padding: 12px;
    }

    .seccion_pagos_divididos_case {
        padding: 12px;
        margin-bottom: 12px;
    }

   /* .seccion_pagos_divididos_input-row {
        flex-direction: column;
    }*/

    .seccion_pagos_divididos_select-header {
        padding: 10px 12px;
        font-size: 14px;
    }

    .seccion_pagos_divididos_option-description {
        font-size: 12px;
    }

    .seccion_pagos_divididos_floating-input input,
    .seccion_pagos_divididos_floating-select select {
        font-size: 16px;
    }

    .seccion_pagos_divididos_floating-input label,
    .seccion_pagos_divididos_floating-select label {
        font-size: 13px;
    }

    .seccion_pagos_divididos_select-option {
        padding: 14px 12px;
    }
}

@media (max-width: 320px) {
    .seccion_pagos_divididos_case {
        padding: 10px;
    }

    .seccion_pagos_divididos_select-header {
        gap: 8px;
    }

    .seccion_pagos_divididos_payment-icon {
        width: 20px;
        height: 20px;
    }
}
</style>

<style>
    .ventana_exito_crear_cpe_overlay * {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .ventana_exito_crear_cpe_overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .ventana_exito_crear_cpe_container {
        background: white;
        border-radius: 0.75rem;
        width: 100%;
        max-width: 64rem;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .ventana_exito_crear_cpe_header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        background: #eef2ff;
        border-radius: 0.75rem 0.75rem 0 0;
    }

    .ventana_exito_crear_cpe_title_wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ventana_exito_crear_cpe_check_icon {
        -webkit-appearance: none;
        background: -webkit-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
        background: linear-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
        background-size: 500%;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .ventana_exito_crear_cpe_title_container h2 {
        margin: 0;
        font-size: 18px;
        color: #111827;
        font-weight: 600;
    }

    .ventana_exito_crear_cpe_subtitle {
        margin: 0.25rem 0 0;
        font-size: 15px;
        color: #6b7280;
    }

    .ventana_exito_crear_cpe_close {
        background: none;
        border: none;
        color: #6b7280;
        padding: 0.5rem;
        cursor: pointer;
        font-size: 1.5rem;
        border-radius: 50%;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }

    .ventana_exito_crear_cpe_close:hover {
        background: #f3f4f6;
    }

    .ventana_exito_crear_cpe_body {
        flex: 1;
        min-height: 0;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .ventana_exito_crear_cpe_actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .ventana_exito_crear_cpe_view_buttons {
        display: flex;
        gap: 0.5rem;
    }

    .ventana_exito_crear_cpe_btn_view {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        background: #f3f4f6;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .ventana_exito_crear_cpe_btn_view.active, .ventana_exito_crear_cpe_btn.primary {
        -webkit-appearance: none;
        background: -webkit-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
        background: linear-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
        background-size: 500%;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        color: #fff;
    }

    .ventana_exito_crear_cpe_download_buttons {
        display: flex;
        gap: 0.5rem;
        margin-left: auto;
    }

    .ventana_exito_crear_cpe_btn_download {
        padding: 0.5rem 1rem;
        background: -webkit-gradient(to right, rgb(88, 189, 91) 0%, rgba(137, 204, 138, 1) 50%, rgba(76, 175, 79, 1));
        background: linear-gradient(to right, rgb(88, 189, 91) 0%, rgba(137, 204, 138, 1) 50%, rgba(76, 175, 79, 1));
        background-size: 500%;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        outline: none;
        -webkit-tap-highlight-color: transparent;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ventana_exito_crear_cpe_btn_download:hover,   .ventana_exito_crear_cpe_btn_view.active:hover, .ventana_exito_crear_cpe_btn.primary:hover {
        animation-name: gradient;
        -webkit-animation-name: gradient;
        animation-duration: 2s;
        -webkit-animation-duration: s;
        animation-iteration-count: 1;
        -webkit-animation-iteration-count: 1;
        animation-fill-mode: forwards;
        -webkit-animation-fill-mode: forwards;
        color: #fff;
    }

    .ventana_exito_crear_cpe_preview {
        flex: 1;
        min-height: 0;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        color: #6b7280;
    }

    .ventana_exito_crear_cpe_footer {
        padding: 1.25rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        border-radius: 0 0 0.75rem 0.75rem;
    }
  
    .ventana_exito_crear_cpe_btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
        min-width: 150px;
        color: white;
    }

    .ventana_exito_crear_cpe_btn.secondary {
        background: #4b5563;
    }

    .ventana_exito_crear_cpe_btn.secondary:hover {
        background: #374151;
    }

   

    @media (max-width: 640px) {
        .ventana_exito_crear_cpe_actions {
            flex-direction: column;
        }

        .ventana_exito_crear_cpe_view_buttons,
        .ventana_exito_crear_cpe_download_buttons {
            width: 100%;
            justify-content: stretch;
        }

        .ventana_exito_crear_cpe_download_buttons {
            margin-left: 0;
        }

        .ventana_exito_crear_cpe_btn_view,
        .ventana_exito_crear_cpe_btn_download {
            flex: 1;
            text-align: center;
            justify-content: center;
        }

        .ventana_exito_crear_cpe_footer {
            flex-direction: column;
        }
    }
</style>

<style>
    .input_para_busqueda_productos_container {
        width: 100%;
    }

    .input_para_busqueda_productos_wrapper {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .input_para_busqueda_productos_wrapper:focus-within {
        border-color: #818cf899;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .input_para_busqueda_productos_search_icon,
    .input_para_busqueda_productos_barcode_icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: #f3f4f6;
        font-size: 1.2rem;
    }

    .input_para_busqueda_productos_search_icon:hover,
    .input_para_busqueda_productos_barcode_icon:hover {
        background: #e5e7eb;
    }

    .input_para_busqueda_productos_search_icon.active,
    .input_para_busqueda_productos_barcode_icon.active {
        background: #818cf8c2;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }

    .input_para_busqueda_productos_field_container {
        flex: 1;
        position: relative;
        min-width: 0;
        margin-top: 0.5rem;
    }

    .input_para_busqueda_productos_input {
        width: 100%;
        border: none;
        outline: none;
        padding: 0.75rem;
        font-size: 15px;
        background: transparent;
    }

    .input_para_busqueda_productos_label {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        transition: all 0.2s;
        pointer-events: none;
        font-size: 15px;
        padding: 0 0.25rem;
        margin-top: -1px;
    }

    .input_para_busqueda_productos_input:focus + .input_para_busqueda_productos_label,
    .input_para_busqueda_productos_input:not(:placeholder-shown) + .input_para_busqueda_productos_label {
        top: -22px;
        left: 13px;
        font-size: 12px;
        color: #818cf8;
        padding: 0 0.25rem;
    }

    @media (max-width: 640px) {
        .input_para_busqueda_productos_wrapper {
            padding: 0.5rem;
        }

        .input_para_busqueda_productos_search_icon,
        .input_para_busqueda_productos_barcode_icon {
            width: 36px;
            height: 36px;
        }
    }
</style>

<style>
    .control_input_facturalaya_container {
        position: relative;
        width: 100%;
    }

    .control_input_facturalaya_input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        /* line-height: 1.25rem; */
        background: white;
        transition: all 0.2s;
    }

    .control_input_facturalaya_input:focus {
        outline: none;
        border-color: #818cf8;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .control_input_facturalaya_label {
        position: absolute;
        left: 23px;
        top: 40%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #6b7280;
        padding: 0 0.25rem;
        background: transparent;
        transition: all 0.2s;
        pointer-events: none;
    }

    .control_input_facturalaya_input:focus + .control_input_facturalaya_label,
    .control_input_facturalaya_input:not(:placeholder-shown) + .control_input_facturalaya_label {
        top: 0;
        transform: translateY(-50%) scale(0.85);
        background: white;
        color: #818cf8;
    }

    /* Variante con error */
    .control_input_facturalaya_container.error .control_input_facturalaya_input {
        border-color: #ef4444;
    }

    .control_input_facturalaya_container.error .control_input_facturalaya_label {
        color: #ef4444;
    }

    .control_input_facturalaya_error_text {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.25rem;
        display: none;
    }

    .control_input_facturalaya_container.error .control_input_facturalaya_error_text {
        display: block;
    }

    /* Variante deshabilitado */
    .control_input_facturalaya_input:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    .control_input_facturalaya_input:disabled + .control_input_facturalaya_label {
        color: #9ca3af;
    }
</style>

<style>
.vm_modal_facturalaya_configpv_overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
}

.vm_modal_facturalaya_configpv_container {
    background-color: white;
    border-radius: 16px;
    padding: 32px;
    width: 90%;
    max-width: 680px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    transform: translateY(0);
    animation: vm_modal_slide_up 0.3s ease-out;
}

@keyframes vm_modal_slide_up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.vm_modal_facturalaya_configpv_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.vm_modal_facturalaya_configpv_title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    letter-spacing: -0.025em;
}

.vm_modal_facturalaya_configpv_close {
    background: none;
    border: none;
    font-size: 1.5rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.vm_modal_facturalaya_configpv_close:hover {
    background-color: #f1f5f9;
    color: #1e293b;
}

.vm_modal_facturalaya_configpv_branch_section {
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
    padding: 24px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 32px;
}

.vm_modal_facturalaya_configpv_label {
    display: block;
    font-weight: 600;
    color: #334155;
    margin-bottom: 10px;
}

.vm_modal_facturalaya_configpv_select_container {
    position: relative;
    margin-bottom: 20px;
}

.vm_modal_facturalaya_configpv_select_container::after {
    content: '↓';
    font-family: system-ui;
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6366f1;
    pointer-events: none;
    font-weight: bold;
}

.vm_modal_facturalaya_configpv_select {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    color: #1e293b;
    background-color: white;
    appearance: none;
    cursor: pointer;
    transition: all 0.2s;
}

.vm_modal_facturalaya_configpv_select:hover {
    border-color: #cbd5e1;
}

.vm_modal_facturalaya_configpv_select:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.vm_modal_facturalaya_configpv_sync_button {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    border: none;
    padding: 14px 24px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
}

.vm_modal_facturalaya_configpv_sync_button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    transition: 0.5s;
}

.vm_modal_facturalaya_configpv_sync_button:hover::before {
    left: 100%;
}

.vm_modal_facturalaya_configpv_sync_button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.vm_modal_facturalaya_configpv_sync_button:active {
    transform: translateY(1px);
}

.vm_modal_facturalaya_configpv_progress {
    margin-top: 20px;
    display: block;
}

.vm_modal_facturalaya_configpv_progress_status {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    color: #6366f1;
}

.vm_modal_facturalaya_configpv_progress_bar {
    width: 100%;
    height: 6px;
    background-color: #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.vm_modal_facturalaya_configpv_progress_fill {
    height: 100%;
    background: linear-gradient(90deg, #818cf8, #6366f1);
    width: 45%;
    border-radius: 8px;
    position: relative;
    animation: vm_modal_progress_animation 2s ease-in-out infinite;
}

@keyframes vm_modal_progress_animation {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

.vm_modal_facturalaya_configpv_progress_fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    animation: vm_modal_shimmer 1.5s infinite;
}

@keyframes vm_modal_shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

.vm_modal_facturalaya_configpv_success {
    display: block;
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    padding: 16px;
    border-radius: 10px;
    margin-top: 20px;
    align-items: center;
    gap: 12px;
    animation: vm_modal_fade_in 0.3s ease-out;
}

@keyframes vm_modal_fade_in {
    from { opacity: 0; }
    to { opacity: 1; }
}

.vm_modal_facturalaya_configpv_options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    padding: 4px;
}

.vm_modal_facturalaya_configpv_option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 10px;
    transition: all 0.2s;
}

.vm_modal_facturalaya_configpv_option:hover {
    background-color: #f8fafc;
}

.vm_modal_facturalaya_configpv_switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}

.vm_modal_facturalaya_configpv_switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.vm_modal_facturalaya_configpv_slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e2e8f0;
    transition: .4s;
    border-radius: 26px;
}

.vm_modal_facturalaya_configpv_slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

input:checked + .vm_modal_facturalaya_configpv_slider {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}

input:checked + .vm_modal_facturalaya_configpv_slider:before {
    transform: translateX(22px);
}

.vm_modal_facturalaya_configpv_option span {
    color: #334155;
    font-weight: 500;
}

.vm_modal_facturalaya_configpv_footer {
    margin-top: 32px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
}

.vm_modal_facturalaya_configpv_close_button {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.vm_modal_facturalaya_configpv_close_button:hover {
    background-color: #e2e8f0;
    color: #1e293b;
}

@media (max-width: 640px) {
    .vm_modal_facturalaya_configpv_container {
        padding: 20px;
        width: 95%;
        margin: 16px;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
    }
    
    .vm_modal_facturalaya_configpv_title {
        font-size: 1.25rem;
    }
    
    .vm_modal_facturalaya_configpv_branch_section {
        padding: 16px;
    }
    
    .vm_modal_facturalaya_configpv_options {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .vm_modal_facturalaya_configpv_option {
        padding: 8px;
    }
    
    .vm_modal_facturalaya_configpv_sync_button {
        padding: 12px 20px;
    }
    
    .vm_modal_facturalaya_configpv_select {
        padding: 12px 14px;
    }
    
    .vm_modal_facturalaya_configpv_footer {
        margin-top: 24px;
        padding-top: 16px;
    }
}
/* Clase nueva Isa
=========================*/
#vm_paymentcar_modal{
    overflow: scroll;
}
</style>