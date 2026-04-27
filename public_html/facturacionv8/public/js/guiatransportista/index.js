var btn_productos_accion = '';
var ultimo_texto_buscado = '';

$(function() {
    inicializar_tabla_detalle();

    sugerencias_ubigeo($("#ubigeo_partida"));
    sugerencias_ubigeo($("#ubigeo_llegada"));
    sugerencias_producto($("#select_producto_buscar"));
    get_lista_sucursales($("#select_sucursal"), 'si');

    $('#select_producto_buscar').on('change', get_data_producto);
    $(".btn_agregarproducto_detalle").click(add_to_detalle);
    $(".btn_eliminar_producto").click(eliminar_producto_detalle);

    $('.select').select2({ minimumResultsForSearch: -1 });
    $(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});

    $("#select_sucursal").on('change', function() {
        rellenar_data_sucursal(this.value);
    });

    $(".btn_agregar_producto").click(function(){
        $("#key_row").val("");
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        $("#vm_agregar_articulo").modal("show");
    });

    $(".btn_editar_producto").click(function() {
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        var rowid = jQuery("#detalle_guia").jqGrid('getGridParam', 'selrow');
        if(rowid === undefined || rowid == '' || rowid <= 0) {
            swal({   
                title:'Error',   
                text: 'Debes seleccionar un elemento para poder editarlo!!',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                return false
            });
        } else {
            editar_item_tabla(rowid);
            $("#vm_agregar_articulo").modal("show");
        }
    });

    var btn_opciones_avanzadas = document.querySelector('.btn_opciones_avanzadas');

    var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
    });
    btn_opciones_avanzadas.onchange = function() {
        if(btn_opciones_avanzadas.checked) {
            $("#grupo_opciones_avanzadas").show("slide");
        } else {
            $("#grupo_opciones_avanzadas").hide("slide");
        }
    };

    $("#producto_peso").on('input', calcular_peso_total_producto).on('change', calcular_peso_total_producto);
    $("#producto_cantidad").on('input', calcular_peso_total_producto).on('change', calcular_peso_total_producto);
    $("#producto_peso_total").on('input', calcular_peso_unitario_en_base_peso_total).on('change', calcular_peso_unitario_en_base_peso_total);

    inicializar_autompletado_inputs();

    $(".search_document_remitente").click(function() {
        var num_doc = $("#nro_documento_remitente").val();

        if($("#nro_documento_remitente-flexdatalist").val() != '') {
            var num_doc = $("#nro_documento_remitente-flexdatalist").val();
            $("#nro_documento_remitente").val($("#nro_documento_remitente-flexdatalist").val());
        }

		if(num_doc != '') {
            var tipo_doc = $("#tipo_documento_remitente").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                consultar_numero_doc_remitente('', num_doc, $("#nombre_remitente"), '', '', tipo_doc, '', '');
            }
		}
    });

    $(".search_document_destinatario").click(function() {
        var num_doc = $("#nro_documento_destinatario").val();

        if($("#nro_documento_destinatario-flexdatalist").val() != '') {
            var num_doc = $("#nro_documento_destinatario-flexdatalist").val();
            $("#nro_documento_destinatario").val($("#nro_documento_destinatario-flexdatalist").val());
        }

		if(num_doc != '') {
            var tipo_doc = $("#tipo_documento_destinatario").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                consultar_numero_doc_destinatario('', num_doc, $("#nombre_destinatario"), '', '', tipo_doc, '', '');
            }
		}
    });

    $(".search_document_conductor").click(function() {
        var num_doc = $("#nro_documento_conductor").val();

        if($("#nro_documento_conductor-flexdatalist").val() != '') {
            var num_doc = $("#nro_documento_conductor-flexdatalist").val();
            $("#nro_documento_conductor").val($("#nro_documento_conductor-flexdatalist").val());
        }

		if(num_doc != '') {
            var tipo_doc = $("#tipo_documento_conductor").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                consultar_numero_doc_conductor('', num_doc, $("#nombre_conductor"), '', '', tipo_doc, '', '');
            }
		}
    });

    inicializar_controles();

    $("#btn_guardar_guia_transportista").click(guardar_guia_transportista);

    $("#new_document_msg_guardado").click(function() {
        $("#modal_msg_guardado").modal('hide');
        limpiar_guia_transportista();
    });
});

function limpiar_guia_transportista() {
    $('#detalle_guia').jqGrid('clearGridData');
    
    $("#ubigeo_llegada").empty();
    $("#direccionllegada").val('');



    $("#nro_documento_destinatario").val('');
    $("#flexdatalist-nro_documento_destinatario").val('');
    $("#nombre_destinatario").val('');
    $("#flexdatalist-nombre_destinatario").val('');

    $("#nro_documento_remitente").val('');
    $("#flexdatalist-nro_documento_remitente").val('');
    $("#nombre_remitente").val('');
    $("#flexdatalist-nombre_remitente").val('');

    $("#nro_documento_conductor").val('');
    $("#flexdatalist-nro_documento_conductor").val('');
    $("#nombre_conductor").val('');
    $("#flexdatalist-nombre_conductor").val('');
    $("#conductor_num_licencia").val('');
    $("#flexdatalist-conductor_num_licencia").val('');


    $("#num_registro_mtc").val('');
    $("#tuc_vehiculo_principal").val(''); 
    $("#transporte_nro_placa").val('');
    $("#pesobruto").val('');
}


function guardar_guia_transportista() {
    var light = $('#content_guia_remision');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Generando Guía de Remisión...</p>',
		overlayCSS: {
			backgroundColor: '#fff',
			opacity: 0.8,
			cursor: 'wait'
		},
		css: {
			border: 0,
			padding: 0,
			backgroundColor: 'none'
		}
	});

	var gridData = jQuery("#detalle_guia").getRowData();
	var postData = JSON.stringify(gridData);

	var datastring = $("#frm_guia_remision").serializeArray();
	datastring.push({ name: "detalle_guia", value: postData });

	//id: f8sjHDGDSAjdh
    $.ajax({
        url : '/facturacionv8/guiatransportista/guardar_guia_transportista',
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			swal({   
				title: 'Necesitamos de tu Confirmación',   
				text: data.mensaje,
				html: true,
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
				cancelButtonColor: "#DD6B55",
				confirmButtonText: "Si, Crear Guía Transportista!",   
				closeOnConfirm: false,
  				showLoaderOnConfirm: true
			}, function(isconfirmed){  
				if(isconfirmed) {
					var confirmado = 'si';
					datastring.push({name: 'confirmacion', value: confirmado});
					//id: f8sjHDGDSAjdh
                    $.ajax({
						url : '/facturacionv8/guiatransportista/guardar_guia_transportista',
						method :  'POST',
						data: datastring,
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {

                                swal.close();

                                $("#titulo_msg_guardado").html('<i class="icon-checkmark-circle position-left"></i> ' + data.titulo);
								$("#mensaje_msg_guardado").html(data.mensaje);
								var url_pdf_ticket = "";
								var url_pdf_a4 = "";

								url_pdf_ticket = data.enlaces.url_ticket;
								url_pdf_a4 = data.enlaces.url_a4;
								
								$("#content_pdf_preview_ticket").html('<div class="pdf_preview"><embed src="' + url_pdf_ticket + '" type="application/pdf" width="100%" height="300px" /></div>');
								$("#content_pdf_preview_a4").html('<div class="pdf_preview"><embed src="' + url_pdf_a4 + '" type="application/pdf" width="100%" height="300px" /></div>');
								$("#enlace_ticket_msg_guardado").attr("href", url_pdf_ticket);
								
								$("#enlace_a4_msg_guardado").attr("href", url_pdf_a4);

								$(light).unblock();

								$("#modal_msg_guardado").modal({
									backdrop: 'static',
									keyboard: false
								});

								return;
							} else {
								swal({   
									title: 'Error',     
									text: data.mensaje,
									html: true,
									type: "error",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								});
								$(light).unblock();
								return;
							}
						},
						function(reason){
							swal({   
								title: 'ERROR',   
								text: reason,
								html: true,
								type: "error",   
								confirmButtonColor: "#563d7c",   
								confirmButtonText: "OK"
							});
							$(light).unblock();
							return;
						}
					);
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
					
					$(light).unblock();
					return;
				}
			});
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				$(light).unblock();
			});
		}
    }, function(reason){
		swal({
			title: 'Error',
			text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
			html: true,
			type: "error",
			confirmButtonText: "Ok",
			confirmButtonColor: "#2196F3"
		}, function(){
			$(light).unblock();
		});
    });
}

function inicializar_controles() {

    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });

    // Danger
    $(".control-danger").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    // Success
    $(".control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

    // Warning
    $(".control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });

    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });

}

function consultar_numero_doc_conductor(input_idusuario = '', num_doc, input_nombre, input_direccion = '', input_ubigeo = '', tipo_doc, input_email = '', input_telefono = '') {
    $("#icon_search_document_conductor").hide();
    $("#icon_searching_document_conductor").show();
    $(".search_document_conductor").prop('disabled', true);

    $.ajax({
        url : '/facturacionv8/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc, incluir_licencias: 'si'},
        method :  'POST',
        dataType : "json"
    }).then(function(data){

        $("#titulo_licencia_conducir").removeClass();
        $("#titulo_licencia_conducir").html('N° Licencia Conducir:');
        $("#conductor_num_licencia").val('');

        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_nombre.val(data.data.razon_social);
                    }
                }

                if(typeof data.data_licencia !== 'undefined') {
                    if(typeof data.data_licencia.respuesta !== 'undefined' && data.data_licencia.respuesta == 'ok') {
                        if(typeof data.data_licencia.num_licencias !== 'undefined' && parseFloat(data.data_licencia.num_licencias) == 1) {
                            $("#conductor_num_licencia").val(data.data_licencia.primer_num_licencia);
                            if(data.data_licencia.estado == 'inactivo') {
                                $("#titulo_licencia_conducir").addClass('text-danger');
                                if(typeof data.data_licencia.categoria_licencia !== 'undefined' && data.data_licencia.categoria_licencia != '') {
                                    $("#titulo_licencia_conducir").html('N° Licencia Conducir ' + data.data_licencia.categoria_licencia + ' : Vencida (' + data.data_licencia.vencimiento_licencia_es + ')');
                                } else {
                                    $("#titulo_licencia_conducir").html('N° Licencia Conducir: Licencia Vencida (' + data.data_licencia.vencimiento_licencia_es + ')');
                                }
                            } else if(data.data_licencia.estado == 'activo') {
                                $("#titulo_licencia_conducir").addClass('text-success');
                                if(typeof data.data_licencia.categoria_licencia !== 'undefined' && data.data_licencia.categoria_licencia != '') {
                                    $("#titulo_licencia_conducir").html('N° Licencia Conducir ' + data.data_licencia.categoria_licencia + ': Activa (' + data.data_licencia.vencimiento_licencia_es + ')');
                                } else {
                                    $("#titulo_licencia_conducir").html('N° Licencia Conducir: Licencia Activa (' + data.data_licencia.vencimiento_licencia_es + ')');
                                }
                            }
                        } else if(typeof data.data_licencia.num_licencias !== 'undefined' && parseFloat(data.data_licencia.num_licencias) > 1) {
                            if(typeof data.data_licencia.licencias !== 'undefined')
                            var lista_licencias = '';
                            var num_lic = 0;
                            $.each(data.data_licencia.licencias, function(key, licencia) {
                                num_lic++;
                                if(licencia.estado == 'Vigente') {
                                    if(num_lic == 1) {
                                        lista_licencias = lista_licencias + '<strong class="text-success" onclick="agregar_licencia_conducir(' + "'" + licencia.nrolicencia + "'"  +')">'+ licencia.nrolicencia +' ('+ licencia.recharevalidacion +')</strong>';
                                    } else {
                                        lista_licencias = lista_licencias + ' || <strong class="text-success onclick="agregar_licencia_conducir(' + "'" + licencia.nrolicencia + "'"  +')">'+ licencia.nrolicencia +' ('+ licencia.recharevalidacion +')</strong>';
                                    }
                                } else {
                                    if(num_lic == 1) {
                                        lista_licencias = lista_licencias + '<strong class="text-danger onclick="agregar_licencia_conducir(' + "'" + licencia.nrolicencia + "'"  +')">'+ licencia.nrolicencia +' ('+ licencia.recharevalidacion +')</strong>';
                                    } else {
                                        lista_licencias = lista_licencias + ' || <strong class="text-danger onclick="agregar_licencia_conducir(' + "'" + licencia.nrolicencia + "'"  +')">'+ licencia.nrolicencia +' ('+ licencia.recharevalidacion +')</strong>';
                                    }
                                }
                            });

                            $("#titulo_licencia_conducir").html('N° Licencia Conducir: ' + lista_licencias);
                        }
                    }
                } else {
                    $("#titulo_licencia_conducir").html('N° Licencia Conducir: <strong class="text-info">No Encontramos Licencia de Conducir</strong>');
                }
                
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            
                        }
                    } else {
                        input_nombre.val(data.data.razon_social);
                    }
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    
                }
            }
            
            $("#icon_search_document_conductor").show();
            $("#icon_searching_document_conductor").hide();
            $(".search_document_conductor").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document_conductor").show();
                $("#icon_searching_document_conductor").hide();
                $(".search_document_conductor").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document_conductor").show();
            $("#icon_searching_document_conductor").hide();
            $(".search_document_conductor").prop('disabled', false);
        });
    });
}

function agregar_licencia_conducir(txt_licencia) {
    $("#conductor_num_licencia").val(txt_licencia);
}

function consultar_numero_doc_remitente(input_idusuario = '', num_doc, input_nombre, input_direccion = '', input_ubigeo = '', tipo_doc, input_email = '', input_telefono = '') {
    $("#icon_search_document_remitente").hide();
    $("#icon_searching_document_remitente").show();
    $(".search_document_remitente").prop('disabled', true);

    $.ajax({
        url : '/facturacionv8/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc, incluir_licencias: 'no'},
        method :  'POST',
        dataType : "json"
    }).then(function(data){

        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_nombre.val(data.data.razon_social);
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                }
            }

            $("#icon_search_document_remitente").show();
            $("#icon_searching_document_remitente").hide();
            $(".search_document_remitente").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document_remitente").show();
                $("#icon_searching_document_remitente").hide();
                $(".search_document_remitente").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document_remitente").show();
            $("#icon_searching_document_remitente").hide();
            $(".search_document_remitente").prop('disabled', false);
        });
    });
}

function consultar_numero_doc_destinatario(input_idusuario = '', num_doc, input_nombre, input_direccion = '', input_ubigeo = '', tipo_doc, input_email = '', input_telefono = '') {
    $("#icon_search_document_destinatario").hide();
    $("#icon_searching_document_destinatario").show();
    $(".search_document_destinatario").prop('disabled', true);

    $.ajax({
        url : '/facturacionv8/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc, incluir_licencias: 'no'},
        method :  'POST',
        dataType : "json"
    }).then(function(data){

        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_nombre.val(data.data.razon_social);
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                }
            }

            $("#icon_search_document_destinatario").show();
            $("#icon_searching_document_destinatario").hide();
            $(".search_document_destinatario").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document_destinatario").show();
                $("#icon_searching_document_destinatario").hide();
                $(".search_document_destinatario").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document_destinatario").show();
            $("#icon_searching_document_destinatario").hide();
            $(".search_document_destinatario").prop('disabled', false);
        });
    });
}

function inicializar_autompletado_inputs() {
    $('#num_placa_transportista').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["transporte_nro_placa", "num_registro_mtc", "tuc_vehiculo_principal"],
        searchContain: true, 
        searchIn: 'transporte_nro_placa',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_datos_transportista'
    }).on("select:flexdatalist", function(event, data) {
        $("#num_placa_transportista").val(data.transporte_nro_placa);
        $("#num_registro_mtc").val(data.num_registro_mtc);
        $("#tuc_vehículo_principal").val(data.tuc_vehiculo_principal);
    });

    $('#num_registro_mtc').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["transporte_nro_placa", "num_registro_mtc", "tuc_vehiculo_principal"],
        searchContain: true, 
        searchIn: 'num_registro_mtc',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_datos_transportista'
    }).on("select:flexdatalist", function(event, data) {
        $("#num_placa_transportista").val(data.transporte_nro_placa);
        $("#num_registro_mtc").val(data.num_registro_mtc);
        $("#tuc_vehículo_principal").val(data.tuc_vehiculo_principal);
    });

    $('#tuc_vehículo_principal').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["transporte_nro_placa", "num_registro_mtc", "tuc_vehiculo_principal"],
        searchContain: true, 
        searchIn: 'tuc_vehiculo_principal',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_datos_transportista'
    }).on("select:flexdatalist", function(event, data) {
        $("#num_placa_transportista").val(data.transporte_nro_placa);
        $("#num_registro_mtc").val(data.num_registro_mtc);
        $("#tuc_vehículo_principal").val(data.tuc_vehiculo_principal);
    });

    $('#nro_documento_conductor').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["conductor_nro_documento", "conductor_nombre_completo", "conductor_nro_licencia"],
        searchContain: true, 
        searchIn: 'conductor_nro_documento',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_conductor'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_conductor").val(data.conductor_nro_documento);
        $("#nombre_conductor").val(data.conductor_nombre_completo);
        $("#conductor_num_licencia").val(data.conductor_nro_licencia);
    });

    $('#nombre_conductor').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["conductor_nro_documento", "conductor_nombre_completo", "conductor_nro_licencia"],
        searchContain: true, 
        searchIn: 'conductor_nombre_completo',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_conductor'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_conductor").val(data.conductor_nro_documento);
        $("#nombre_conductor").val(data.conductor_nombre_completo);
        $("#conductor_num_licencia").val(data.conductor_nro_licencia);
    });

    $('#conductor_num_licencia').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["conductor_nro_documento", "conductor_nombre_completo", "conductor_nro_licencia"],
        searchContain: true, 
        searchIn: 'conductor_nro_licencia',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_conductor'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_conductor").val(data.conductor_nro_documento);
        $("#nombre_conductor").val(data.conductor_nombre_completo);
        $("#conductor_num_licencia").val(data.conductor_nro_licencia);
    });

    $('#nro_documento_destinatario').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["dest_numero_documento", "dest_nombre_completo"],
        searchContain: true, 
        searchIn: 'dest_numero_documento',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_destinatario'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_destinatario").val(data.dest_numero_documento);
        $("#nombre_destinatario").val(data.dest_nombre_completo);
    });

    $('#nro_documento_remitente').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["remitente_numero_documento", "remitente_nombre_completo"],
        searchContain: true, 
        searchIn: 'remitente_numero_documento',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_remitente'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_remitente").val(data.remitente_numero_documento);
        $("#nombre_remitente").val(data.remitente_nombre_completo);
    });

    $('#nombre_remitente').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["remitente_numero_documento", "remitente_nombre_completo"],
        searchContain: true, 
        searchIn: 'remitente_nombre_completo',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_remitente'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_remitente").val(data.remitente_numero_documento);
        $("#nombre_remitente").val(data.remitente_nombre_completo);
    });

    $('#nombre_destinatario').flexdatalist({
        minLength: 2,
        selectionRequired: false,
        visibleProperties: ["dest_numero_documento", "dest_nombre_completo"],
        searchContain: true, 
        searchIn: 'dest_nombre_completo',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiatransportista/sugerencias_destinatario'
    }).on("select:flexdatalist", function(event, data) {
        $("#nro_documento_destinatario").val(data.dest_numero_documento);
        $("#nombre_destinatario").val(data.dest_nombre_completo);
    });
}

function rellenar_data_sucursal(idsucursal) {
    $.ajax({
        url : '/facturacionv8/herramientas/get_data_sucursal',
        method :  'POST',
        data: {idsucursal: idsucursal},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#direccionpartida").val(data.sucursal.direccion);
            $('#ubigeo_partida')
                .append($("<option></option>")
                .attr("value",data.sucursal.id_ubigeo)
                .text(data.ubigeo.departamento + ' - ' + data.ubigeo.provincia + ' - ' + data.ubigeo.distrito));        
            $('#ubigeo_partida').val(data.sucursal.id_ubigeo).trigger("change").trigger("select2:select");
            $("#serie_comprobante").val(data.sucursal.guia_transportista_serie);
            $("#codigo_local_partida").val(data.sucursal.codigo);
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                
            });
        }
    }, function(reason){
        swal({   
            title: 'Error',   
            text: reason,
            html: true,
            type: "error",  
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok"
        }, function() {
            
        });
    });
}

function calcular_peso_total_producto() {
    var peso_producto_unit = 0;
    var cantidad = 0;

    if($("#producto_peso").val() != '') {
        peso_producto_unit = parseFloat($("#producto_peso").val());
    }

    if($("#producto_cantidad").val() != '') {
        cantidad = parseFloat($("#producto_cantidad").val());
    }

    var total = round_math(cantidad*peso_producto_unit, 2);

    $("#producto_peso_total").val(total);
}

function calcular_peso_unitario_en_base_peso_total() {
    var producto_peso_total = 0;
    if($("#producto_peso").val() != '') {
        producto_peso_total = parseFloat($("#producto_peso_total").val());
    }

    var cantidad = 0;
    if($("#producto_cantidad").val() != '') {
        cantidad = parseFloat($("#producto_cantidad").val());
    }

    var peso_producto_unit = 0;
    if(cantidad > 0) {
        peso_producto_unit = round_math(producto_peso_total/cantidad, 2);
    }

    $("#producto_peso").val(peso_producto_unit);
}

function sugerencias_ubigeo(select) {
    select.select2({
        language: "es",
        ajax: {
          type: "post",
          url: "/facturacionv8/apisunat/get_lista_sugerencias_ubigeo",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
  
            return {
              results: data.items,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
    });
}

function sugerencias_producto(select) {
    $("#select_producto_buscar").select2({
        language: "es",
        dropdownParent: $('#vm_agregar_articulo'),
        minimumResultsForSearch: Infinity,
        ajax: {
          type: "post",
          url: "/facturacionv8/herramientas/get_sugerencias_producto",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            ultimo_texto_buscado = params.term;
            return {
              q: params.term, // search term
              page: params.page,
              idsucursal: $("#select_sucursal").val()
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
  
            return {
              results: data.items,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });
}

function formatRepo (data) {
    var $container;

    if (data.loading) {
      return data.text;
    }

    if(data.imagen == '') {
        $container = get_html_item_busqueda();
    } else { 
        try {
            var imagenes_producto = jQuery.parseJSON(data.imagen);
            if(imagenes_producto.cuadradas.length > 0) {
                $.each(imagenes_producto.cuadradas, function( index, url_imagen ) {
                    //console.log(url_imagen);
                    $container = get_html_item_busqueda(url_imagen);
                    return false;
                });
            } else if(imagenes_producto.verticales.length > 0) {
                $.each(imagenes_producto.verticales, function( index, url_imagen ) {
                    $container = get_html_item_busqueda(url_imagen);
                    return false;
                });
            } else if(imagenes_producto.horizontales.length > 0) {
                $.each(imagenes_producto.horizontales, function( index, url_imagen ) {
                    $container = get_html_item_busqueda(url_imagen);
                    return false;
                });
            } else {
                $container = get_html_item_busqueda(url_imagen);
            }
            
        } catch (e) {
            $container = get_html_item_busqueda();
        };
    }

    if($("#c_restriccion_stock").val() == 'si' && data.id_unidad_medida != '20' && parseFloat(data.stock) <= 0) {
        $html_stock = '<span>Stock: ' + '<strong class="text-danger">' + data.stock + '</strong>' + ' ' + data.unidad + '</span>';
    } else {
        $html_stock = '<span>Stock: ' + data.stock + ' ' + data.unidad + '</span>';
    }
    
    $container.find(".select2-result-repository__title").text(data.text);
    $container.find(".select2-result-repository__description").text('Código: ' + data.codigo);
    $container.find(".select2-result-repository__forks").append($html_stock);
    $container.find(".select2-result-repository__stargazers").append(data.precio);
    $container.find(".select2-result-repository__watchers").append('Cate: ' + data.categoria);
  
    return $container;
}

  function get_html_item_busqueda(img = '') {
    if(img == '') {
        return $(
            "<div class='select2-result-repository clearfix'>" +
              "<div class='select2-result-repository__meta' style='margin-left: 1px !important;'>" +
                "<div class='select2-result-repository__title text-primary'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                    "<div class='select2-result-repository__forks'><i class='icon-stairs-up'></i> </div>" +
                    "<div class='select2-result-repository__stargazers'><i class='icon-cash2'></i> </div>" +
                    "<div class='select2-result-repository__watchers'><i class='icon-users'></i> </div>" +
                "</div>" +
              "</div>" +
            "</div>"
          );
    } else {
        return $(
            "<div class='select2-result-repository clearfix'>" +
              "<div class='select2-result-repository__avatar'><img src='" + img + "' /></div>" +
              "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title text-primary'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                    "<div class='select2-result-repository__forks'><i class='icon-stairs-up'></i> </div>" +
                    "<div class='select2-result-repository__stargazers'><i class='icon-cash2'></i> </div>" +
                    "<div class='select2-result-repository__watchers'><i class='icon-users'></i> </div>" +
                "</div>" +
              "</div>" +
            "</div>"
        );
    }
}
  
function formatRepoSelection (data) {
    return data.full_name || data.text;
}

function get_data_producto() {
    var idproducto = $("#select_producto_buscar").val();

    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#producto_idproducto").val(data.producto.idproducto);
            $("#producto_codigo").val(data.producto.codigo);
            $("#producto_descripcion").val(data.producto.nombre);
            $("#producto_unidadmedida").val(data.producto.id_unidad_medida);
            $("#producto_cantidad").val(1);
            if(data.producto.peso != '' && data.producto.peso != null) {
                $("#producto_peso").val(data.producto.peso).trigger('input');
            } else {
                $("#producto_peso").val(1).trigger('input');
            }

            //Agregamos la únidad de medidad al SELECT
            $('#producto_unidadmedida').empty();
            $('#producto_unidadmedida').append(
                $('<option />')
                .val('UND-' + data.unidad_medida.idunidad)
                .text(data.unidad_medida.nombre)
                .attr({
                    "data-tipo": 'unidad',
                    "data-idpresentacion": '',
                    "data-codigo": data.producto.codigo,
                    "data-nombrepresentacion": '',
                    "data-idproducto": data.producto.idproducto,
                    "data-idunidadpresentacion": '',
                    "data-idunidadbase": data.unidad_medida.idunidad,
                    "data-nombreunidadpresentacion": '',
                    "data-cantidad": '',
                    "data-nombreunidadbase": data.unidad_medida.nombre,
                    "data-precioconigv": data.producto.valor_con_igv,
                    "data-preciosinigv": data.producto.valor_sin_igv,
                    "data-idcodmoneda" : data.producto.id_cod_moneda
                })
            );

            data.presentaciones.forEach(function(presentacion, indice, array) {
                var presentacion_seleccionar = false;
                if(ultimo_texto_buscado.toLowerCase() == presentacion.codigo.toLowerCase()) {
                    presentacion_seleccionar = true;
                }
                
                $('#producto_unidadmedida').append(
                    $('<option />')
                    .val('PRE-' + presentacion.id_presentacion)
                    .text(presentacion.nombre_presentacion + ' : ' + presentacion.nombre_unidad_presentacion)
                    .prop('selected', presentacion_seleccionar)
                    .attr({
                        "data-tipo": 'presentacion',
                        "data-idpresentacion": presentacion.id_presentacion,
                        "data-codigo": presentacion.codigo, //VERIFICAR QUE NO TENGA COMILLAS
                        "data-nombrepresentacion": presentacion.nombre_presentacion, //VERIFICAR QUE NO TENGA COMILLAS
                        "data-idproducto": presentacion.id_producto,
                        "data-idunidadpresentacion": presentacion.id_unidad_presentacion,
                        "data-idunidadbase": presentacion.id_unidad_base,
                        "data-nombreunidadpresentacion": presentacion.nombre_unidad_presentacion,
                        "data-cantidad": presentacion.cantidad,
                        "data-nombreunidadbase": presentacion.nombre_unidad_base,
                        //"data-precioconigv": presentacion.precio_con_igv,
                        //"data-preciosinigv": presentacion.precio_sin_igv,
                        "data-precioconigv": 0,
                        "data-preciosinigv": 0,
                        "data-idcodmoneda" : presentacion.id_cod_moneda
                    })
                );
            });

            $("#producto_unidadmedida").trigger("change").trigger("select2:select"); //necesario para que agarre el precio del producto seleccionado
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			
		});
    });
}