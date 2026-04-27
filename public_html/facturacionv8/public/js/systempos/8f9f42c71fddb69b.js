let TIMEOUT_MS=3e4,TIPOS_DOCUMENTO={"01":"Factura Electrónica","03":"Boleta de Venta Electrónica",77:"Nota de Venta",88:"Cotización"};function procesarVentaCarrito(a){return a?obtenerDatosCarrito(a).then(function(e){return obtenerItemsCarrito(a).then(function(a){return e.items=a,e})}).then(function(a){return enviarDatosAlBackend({carrito:a,datosAdicionales:obtenerDatosAdicionales()})}).then(function(e){var a=["serie_comprobante","numero_comprobante","id_tipodoc_electronico","id_carrito"].filter(function(a){return!e[a]});if(0<a.length)throw new Error("Faltan campos requeridos en la respuesta: "+a.join(", "));return actualizarEstadoCarrito(e,"venta_generada").then(function(){return e})}).catch(function(a){throw mostrarNotificacion("Error al procesar la venta: "+a.message,!0),a}):Promise.reject(new Error("ID de carrito no válido"))}function obtenerDatosCarrito(r){return new Promise(function(e,o){var a=db.transaction(["carts"],"readonly").objectStore("carts").get(r);a.onsuccess=function(a){a=a.target.result;a?e(a):o(new Error("No se encontró el carrito actual."))},a.onerror=function(){o(new Error("Error al obtener el carrito actual."))}})}function obtenerItemsCarrito(r){return new Promise(function(e,o){var a=db.transaction(["carrito"],"readonly").objectStore("carrito").index("id_carrito").getAll(IDBKeyRange.only(r));a.onsuccess=function(a){a=a.target.result;a&&0<a.length?e(a):o(new Error("El carrito está vacío."))},a.onerror=function(){o(new Error("Error al obtener los ítems del carrito."))}})}function obtenerDatosAdicionales(){return{id_usuario:$("#id_usuario").val(),id_contribuyente:$("#id_contribuyente").val(),factor_igv_sunat:parseFloat($("#factor_igv_sunat").val())||.18,impuesto_icbper:parseFloat($("#impuesto_icbper").val())||.3,num_decimales:parseInt($("#num_decimales").val(),10)||2,id_vendedor_asignado:$("#vm_paymentcar_asignar_vendedor").val(),tipo_cambio:{venta:parseFloat($("#data_tipo_cambio").data("venta"))||0,compra:parseFloat($("#data_tipo_cambio").data("compra"))||0,fecha_consulta:$("#data_tipo_cambio").data("fechaconsulta")||""}}}function enviarDatosAlBackend(a){return a&&a.carrito?new Promise(function(e,t){$.ajax({url:"/facturacionv8/systempos/procesar_venta_carrito",method:"POST",data:JSON.stringify(a),contentType:"application/json",dataType:"json",timeout:TIMEOUT_MS}).done(function(a){hideLoadingFacturalaYa("vm_paymentcar_contenido");try{if(!a)throw new Error("Respuesta vacía del servidor");if("object"!=typeof a)throw new Error("Respuesta del servidor inválida");if("error"===a.respuesta)throw new Error(a.mensaje||"Error en el proceso de venta");if("ok"!==a.respuesta)throw new Error("Respuesta del servidor no reconocida");e(a)}catch(a){t(a)}}).fail(function(a,e,o){hideLoadingFacturalaYa("vm_paymentcar_contenido");var r="Error en la comunicación con el servidor";a.responseJSON?r=a.responseJSON.mensaje||r:o&&(r+=": "+o),t(new Error(r))})}):(hideLoadingFacturalaYa("vm_paymentcar_contenido"),Promise.reject(new Error("Datos de venta inválidos")))}function procesarRespuestaExitosa(r){return new Promise(function(a,e){try{var o=["serie_comprobante","numero_comprobante","id_tipodoc_electronico"].filter(function(a){return!r[a]});if(0<o.length)throw new Error("Faltan campos requeridos en la respuesta: "+o.join(", "));cerrarModalPagoYMostrarExito(r),a({comprobante:{serie:r.serie_comprobante,numero:r.numero_comprobante,tipoDoc:r.id_tipodoc_electronico},documentos:{a4:r.url_relativa_a4||null,ticket:r.url_relativa_ticket||null,xml:r.url_relativa_xml||null,cdr:r.url_relativa_xml_cdr||null},mensajes:{sistema:r.mensaje||"Venta procesada correctamente",sunat:r.mensaje_sunat||""}})}catch(a){e(a)}})}function cerrarModalPagoYMostrarExito(a){hideLoadingFacturalaYa("vm_paymentcar_contenido");var e=$("#vm_paymentcar_modal");e.hasClass("modal")?(e.modal("hide"),e.one("hidden.bs.modal",function(){mostrarModalComprobanteExitoso(a)})):(e.hide(),mostrarModalComprobanteExitoso(a)),$(".modal-backdrop").remove()}function mostrarModalComprobanteExitoso(e){$("#ventana_exito_crear_cpe_modal").remove();var a=`
    <div id="ventana_exito_crear_cpe_modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="" id="ventana_exito_crear_cpe_modal_content">
                    <div class="">
                        <div class="">
                            <!-- Header -->
                            <div class="ventana_exito_crear_cpe_header">
                                <div class="ventana_exito_crear_cpe_title_wrapper">
                                    <div class="ventana_exito_crear_cpe_check_icon">✓</div>
                                    <div class="ventana_exito_crear_cpe_title_container">
                                        <h2>¡Comprobante Generado!</h2>
                                        <p class="ventana_exito_crear_cpe_subtitle">${e.serie_comprobante}-${e.numero_comprobante}</p>
                                    </div>
                                </div>
                                <button class="ventana_exito_crear_cpe_close" data-dismiss="modal">×</button>
                            </div>

                            <!-- Body -->
                            <div class="ventana_exito_crear_cpe_body">
                                <!-- Actions -->
                                <div class="ventana_exito_crear_cpe_actions">
                                    <div class="ventana_exito_crear_cpe_view_buttons">
                                        <button class="ventana_exito_crear_cpe_btn_view active" data-view="a4">Formato A4</button>
                                        <button class="ventana_exito_crear_cpe_btn_view" data-view="ticket">Ticket</button>
                                    </div>
                                    <div class="ventana_exito_crear_cpe_download_buttons">
                                        <a href="${e.url_relativa_a4}" target="_blank" class="ventana_exito_crear_cpe_btn_download">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 0a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 12.293V.5A.5.5 0 0 1 8 0z"/>
                                            </svg>
                                            Descargar A4
                                        </a>
                                        <a href="${e.url_relativa_ticket}" target="_blank" class="ventana_exito_crear_cpe_btn_download">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 0a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 12.293V.5A.5.5 0 0 1 8 0z"/>
                                            </svg>
                                            Descargar Ticket
                                        </a>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="ventana_exito_crear_cpe_preview">
                                    <iframe style="flex: auto; height: 400px;" src="${e.url_relativa_a4}" class="ventana_exito_crear_cpe_iframe"></iframe>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="ventana_exito_crear_cpe_footer">
                                <button class="ventana_exito_crear_cpe_btn secondary" id="ventana_exito_crear_cpe_btn_list">
                                    Ver Listado de Comprobantes
                                </button>
                                <button class="ventana_exito_crear_cpe_btn primary" id="ventana_exito_crear_cpe_btn_new">
                                    Crear Nueva Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;$("body").append(a);let o=$(".ventana_exito_crear_cpe_iframe");$(".ventana_exito_crear_cpe_btn_view").on("click",function(){var a=$(this).data("view");$(".ventana_exito_crear_cpe_btn_view").removeClass("active"),$(this).addClass("active"),o.attr("src","a4"===a?e.url_relativa_a4:e.url_relativa_ticket)}),$(".ventana_exito_crear_cpe_close").on("click",function(){$("#ventana_exito_crear_cpe_modal").modal("hide")}),$("#ventana_exito_crear_cpe_btn_list").on("click",function(){window.location.href="/facturacionv8/dashboard"}),$("#ventana_exito_crear_cpe_btn_new").on("click",function(){$("#ventana_exito_crear_cpe_modal").modal("hide")}),$("#ventana_exito_crear_cpe_modal").modal({backdrop:"static",keyboard:!1})}function actualizarEstadoCarrito(n,a){return new Promise(function(o,r){var t=db.transaction(["carts"],"readwrite").objectStore("carts"),a=n.id_carrito,a=t.get(a);a.onsuccess=function(a){var e=a.target.result;e?(e.estado="comprobante_generado",e.comprobantes||(e.comprobantes=[]),a={serie_comprobante:n.serie_comprobante,numero_comprobante:n.numero_comprobante,id_tipodoc_electronico:n.id_tipodoc_electronico,mensaje:n.mensaje,mensaje_sunat:n.mensaje_sunat,url_relativa_a4:n.url_relativa_a4,url_relativa_ticket:n.url_relativa_ticket,url_relativa_xml:n.url_relativa_xml,url_relativa_xml_cdr:n.url_relativa_xml_cdr,fecha_creacion:(new Date).toISOString()},e.comprobantes.push(a),(a=t.put(e)).onsuccess=function(){$("#tab_nombre_carrito_"+e.id_carrito).html(n.serie_comprobante+"-"+n.numero_comprobante),o(e)},a.onerror=function(){r(new Error("Error al actualizar el estado del carrito."))}):r(new Error("No se encontró el carrito para actualizar."))},a.onerror=function(){r(new Error("Error al acceder al carrito para actualizar."))}})}function obtenerComprobantesCarrito(r){return new Promise(function(e,a){var o=db.transaction(["carts"],"readonly").objectStore("carts").get(r);o.onsuccess=function(a){a=a.target.result;a&&a.comprobantes&&0<a.comprobantes.length?e({tieneComprobantes:!0,comprobantes:a.comprobantes,totalComprobantes:a.comprobantes.length}):e({tieneComprobantes:!1,comprobantes:[],totalComprobantes:0})},o.onerror=function(){a(new Error("Error al buscar comprobantes del carrito."))}})}function mostrarNotificacion(a,e){swal({title:e?"Error":"Éxito",text:a,html:!0,type:e?"error":"success",confirmButtonColor:e?"#EF5350":"#66BB6A",confirmButtonText:"Cerrar"})}function cerrarModalExito(){var a=$(".modal_success_payment_overlay");$(".modal_success_payment_container");a.modal("hide")}function mostrarModalComprobantesExistentes(a){a=`
        <div class="modal_aviso_cpe_generados_overlay" id="modal_aviso_cpe_generados_container">
            <div class="modal_aviso_cpe_generados_container">
                <!-- Header -->
                <div class="modal_aviso_cpe_generados_header">
                    <h2 class="modal_aviso_cpe_generados_title">Comprobantes Existentes</h2>
                    <button type="button" class="modal_aviso_cpe_generados_close" id="modal_aviso_cpe_generados_btn_close">&times;</button>
                </div>

                <div class="modal_aviso_cpe_generados_body">
                    <!-- Mensaje informativo -->
                    <div class="modal_aviso_cpe_generados_message">
                        <i class="fas fa-info-circle"></i>
                        Este carrito ya tiene comprobantes generados. Por favor, revise la lista:
                    </div>

                    <!-- Lista de comprobantes -->
                    <div class="modal_aviso_cpe_generados_list">
                        ${a.map(a=>`
                            <div class="modal_aviso_cpe_generados_item">
                                <div class="modal_aviso_cpe_generados_item_header">
                                    <h3>${TIPOS_DOCUMENTO[a.id_tipodoc_electronico]||"Comprobante"}
                                        ${a.serie_comprobante}-${a.numero_comprobante}</h3>
                                    <p class="modal_aviso_cpe_generados_date">
                                        <i class="ph-clock"></i>
                                        ${new Date(a.fecha_creacion).toLocaleString()}
                                    </p>
                                </div>
                                <div class="modal_aviso_cpe_generados_actions">
                                    ${a.url_relativa_a4?`
                                        <a href="${a.url_relativa_a4}" target="_blank" 
                                           class="modal_aviso_cpe_generados_btn modal_aviso_cpe_generados_btn_a4">
                                            <i class="ph-file-pdf"></i>
                                            A4
                                        </a>
                                    `:""}
                                    ${a.url_relativa_ticket?`
                                        <a href="${a.url_relativa_ticket}" target="_blank"
                                           class="modal_aviso_cpe_generados_btn modal_aviso_cpe_generados_btn_ticket">
                                            <i class="ph-note"></i>
                                            Ticket
                                        </a>
                                    `:""}
                                    ${a.url_relativa_xml?`
                                        <a href="${a.url_relativa_xml}" target="_blank"
                                           class="modal_aviso_cpe_generados_btn modal_aviso_cpe_generados_btn_xml">
                                            <i class="ph-file-code"></i>
                                            XML
                                        </a>
                                    `:""}
                                </div>
                            </div>
                        `).join("")}
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal_aviso_cpe_generados_footer">
                    <button class="modal_aviso_cpe_generados_btn_cancel" id="modal_aviso_cpe_generados_btn_cancelar">
                        Cancelar
                    </button>
                    <button class="modal_aviso_cpe_generados_btn_generate" onclick="generarNuevoComprobante()">
                        Generar Nuevo Comprobante
                    </button>
                </div>
            </div>
        </div>
    `;$("#modal_aviso_cpe_generados_container").remove(),$("body").append(a),$("#modal_aviso_cpe_generados_btn_close, #modal_aviso_cpe_generados_btn_cancelar").on("click",function(){$("#modal_aviso_cpe_generados_container").fadeOut(300,function(){$(this).modal("hide")})}),$("#modal_aviso_cpe_generados_container").modal({backdrop:"static",keyboard:!1})}function generarNuevoComprobante(){$("#modal_aviso_cpe_generados_container").modal("hide"),showLoadingFacturalaYa("vm_paymentcar_contenido"),procesarVentaCarrito(currentCartId).then(function(a){return procesarRespuestaExitosa(a)}).catch(function(a){hideLoadingFacturalaYa("vm_paymentcar_contenido"),mostrarNotificacion(a.message,!0)})}$(function(){$(".btn_procesar_venta").on("click",function(){$(this).prop("disabled",!0),showLoadingFacturalaYa("vm_paymentcar_contenido"),obtenerComprobantesCarrito(currentCartId).then(function(a){if(!a.tieneComprobantes)return procesarVentaCarrito(currentCartId);hideLoadingFacturalaYa("vm_paymentcar_contenido"),mostrarModalComprobantesExistentes(a.comprobantes)}).then(function(a){if(a)return procesarRespuestaExitosa(a)}).catch(function(a){hideLoadingFacturalaYa("vm_paymentcar_contenido"),mostrarNotificacion(a.message,!0)})})});