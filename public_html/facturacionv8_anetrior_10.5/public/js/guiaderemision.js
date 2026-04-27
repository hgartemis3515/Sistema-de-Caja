$(function() {
    var btn_productos_accion = '';
    var ultimo_texto_buscado = '';
    sugerencias_ubigeo($("#ubigeo_partida"));
    sugerencias_ubigeo($("#ubigeo_llegada"));
    sugerencias_ubigeo_new_client($("#ubigeo_client"));
    //get_lista_ubigeos($("#ubigeo_client"));
    sugerencias_clientes($("#idcliente"));
    get_lista_sucursales($("#select_sucursal"), 'si');
    sugerencias_producto($("#select_producto_buscar"));
    get_lista_unidades_medida($("#producto_unidadmedida"));
    //get_lista_empresas_transporte($("#empresa_transporte"));
    inicializar_tabla_detalle();
    $(".btn_agregar_producto").click(function(){
        $("#key_row").val("");
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        $("#vm_agregar_articulo").modal("show");
    });
    $('#modalidad_traslado').on('change', function() {
        var modalidad_traslado = this.value;
        if(modalidad_traslado == '01') {
            $("#transporte_tipo_docidentidad").val('6').trigger("change").trigger("select2:select");
            $("#titulo_transporte").html('Datos del Transporte Público');

            $("#content_tipo_documento").removeClass();
            $("#content_transporte_numerodocumento").removeClass();
            $("#razonsocial_numerodocumento").removeClass();

            $("#content_tipo_documento").attr('class', 'form-group col-md-3');
            $("#content_transporte_numerodocumento").attr('class', 'form-group col-md-4');
            $("#razonsocial_numerodocumento").attr('class', 'form-group col-md-5');
            $("#content_numero_placa").hide();
            $("#content_licencia_conducir").hide();
        } else {
            $("#transporte_tipo_docidentidad").val('1').trigger("change").trigger("select2:select");
            $("#titulo_transporte").html('Datos del Transporte Privado');

            $("#content_tipo_documento").removeClass();
            $("#content_transporte_numerodocumento").removeClass();
            $("#razonsocial_numerodocumento").removeClass();

            $("#content_tipo_documento").attr('class', 'form-group col-md-2');
            $("#content_transporte_numerodocumento").attr('class', 'form-group col-md-4');
            $("#razonsocial_numerodocumento").attr('class', 'form-group col-md-6');
            $("#content_numero_placa").show();
            $("#content_licencia_conducir").show();
        }
    });

    $('#transporte_tipo_docidentidad').on('change', function() {
        var tipo_doc_identidad = this.value;
        if(tipo_doc_identidad == '6') {
            $("#titulo_numerodocumento").html('N° RUC Emp.Transp.');
            $("#titulo_nombrecliente").html('Razón Social');
        } else if(tipo_doc_identidad == '1') {
            $("#titulo_numerodocumento").html('N° DNI Conductor');
            $("#titulo_nombrecliente").html('Nombre Conductor');
        } else {
            $("#titulo_numerodocumento").html('N° Doc Conductor');
            $("#titulo_nombrecliente").html('Nombre Conductor');
        }
    });

    $("#modalidad_traslado").val('02').trigger("select2:select").trigger('change');
    $("#transporte_tipo_docidentidad").trigger("select2:select").trigger('change');

    $('#select_producto_buscar').on('change', get_data_producto);
    $(".btn_agregarproducto_detalle").click(add_to_detalle);
    $(".btn_eliminar_producto").click(eliminar_producto_detalle);

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

    inicializar_controles();

    $("#select_sucursal").on('change', function() {
        rellenar_data_sucursal(this.value);
    });

    $(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
    $('.select').select2({ minimumResultsForSearch: -1 });

    $("#empresa_transporte").on('change', function(){
        get_lista_conductores_autos($("#select_conductor"), $("#select_vehiculo"), $("#empresa_transporte").val());
    });

    $("#motivo_traslado").on('change', function() {
        if(this.value == '13') {
            $("#content_texto_otro_motivo_traslado").show('slide');
        } else {
            $("#content_texto_otro_motivo_traslado").hide('slide');
        }
    });

    $("#idcliente").on('change', function() {
        get_documentos_cliente(this.value);
    });

    $(".btn_generar_codigo").click(function(){
		generar_codigo($("#txt_codigo"), 8);
	});
    $(".btn_save_guia").click(guardar_guia_remision);
    $('.btn_saveclient').click(guardar_cliente);
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
    $(".search_document").click(function() {
        var num_doc = $("#transporte_numerodocumento").val();

        if($("#transporte_numerodocumento-flexdatalist").val() != '') {
            var num_doc = $("#transporte_numerodocumento-flexdatalist").val();
            $("#transporte_numerodocumento").val($("#transporte_numerodocumento-flexdatalist").val());
        } else {
            var num_doc = $("#transporte_numerodocumento").val();
        }

		if(num_doc != '') {
            var tipo_doc = $("#transporte_tipo_docidentidad").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                //consultar_numero_doc_cliente(num_doc, $("#transporte_nombre"), tipo_doc);
                consultar_numero_doc_cliente('', num_doc, $("#transporte_nombre"), '', '', tipo_doc, '', '');
            }
		}
    });

    $(".search_document_newclient").click(function() {
        var num_doc = $("#newclient_numerodocumento").val();

		if(num_doc != '') {
            var tipo_doc = $("#type_document").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                //consultar_numero_doc_cliente(num_doc, $("#transporte_nombre"), tipo_doc);
                consultar_numero_doc_newcliente('', num_doc, $("#razon_social"), $("#direccionfiscal"), $("#ubigeo_client"), tipo_doc, $("#email"));
            }
		}
    });
    
    var tipo_doc_guardado = $("#tipo_doc_guardado").val();
    var numero_doc_guardado = parseInt($("#numero_doc_guardado").val());
    var serie_doc_guardado = $("#serie_doc_guardado").val();

    if(tipo_doc_guardado == '77') {
        if(numero_doc_guardado > 0) {
            var light = $("#content_guia_remision");
            get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'NOTA DE VENTA', light, serie_doc_guardado);
        }
    }

    if(tipo_doc_guardado == '88') {
        if(numero_doc_guardado > 0) {
            var light = $("#content_guia_remision");
            get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'COTIZACIÓN', light, serie_doc_guardado);
        }
    }

    if(tipo_doc_guardado == '01') {
        if(numero_doc_guardado > 0) {
            if(serie_doc_guardado != '') {
                var light = $("#content_guia_remision");
                get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'FACTURA', light, serie_doc_guardado);
            }
        }
    }

    if(tipo_doc_guardado == '03') {
        if(numero_doc_guardado > 0) {
            if(serie_doc_guardado != '') {
                var light = $("#content_guia_remision");
                get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'BOLETA', light, serie_doc_guardado);
            }
        }
    }

    if(tipo_doc_guardado == '09') {
        if(numero_doc_guardado > 0) {
            if(serie_doc_guardado != '') {
                var light = $("#content_guia_remision");
                get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'GUIA DE REMISIÓN', light, serie_doc_guardado);
            }
        }
    }

    generar_codigo($("#txt_codigo"), 8);
    inicializar_autocompletado();

    $("#producto_peso").on('input', calcular_peso_total_producto).on('change', calcular_peso_total_producto);
    $("#producto_cantidad").on('input', calcular_peso_total_producto).on('change', calcular_peso_total_producto);
    $("#producto_peso_total").on('input', calcular_peso_unitario_en_base_peso_total).on('change', calcular_peso_unitario_en_base_peso_total);

    $(".file-styled").uniform({
        fileButtonClass: 'action btn btn-primary'
    });
    
    $(".btn_importar_items").click(function() {
        $("#vm_importar_items").modal('show');
    });
    
    $("#btn_iniciar_importacion_items").click(iniciar_proceso_importacion_item);
});

function iniciar_proceso_importacion_item(){
	var light = $("#content_vm_importar_items");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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

    var datastring = $("#frm_importar_items").serializeArray();
    var formData = new FormData();
    formData.append("archivo", $("#file_data_items")[0].files[0]);
    formData.append("idsucursal", $("#select_sucursal").val());
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });
    
    $.ajax({
        url : '/facturacionv8/guiaderemision/iniciar_importacion_items',
		type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
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
				confirmButtonText: "Si, Adelante!",   
				closeOnConfirm: false,
  				showLoaderOnConfirm: true
			}, function(isconfirmed){  
				if(isconfirmed) {
					var confirmado = 'si';
					formData.append('confirmacion', confirmado);
					$.ajax({
                        url: "/facturacionv8/guiaderemision/iniciar_importacion_items",
                        type: "post",
                        dataType: "json",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data){
                        if(data.respuesta == 'ok') {
                            swal({   
                                title:'Ok',   
                                text: data.mensaje,
                                html: true,
                                type: "success", 
                                confirmButtonColor: "#DD6B55",   
                                confirmButtonText: "Ok",
                            }, function() {
                                var sum_peso_total = 0;
                                data.detalle.forEach( function(data_item, indice, array) {
                    				jQuery('#detalle_guia').addRowData(indice + 1, data_item, 'last');
                    				sum_peso_total = sum_peso_total + data_item.peso;
                                });
                            
                                $("#pesobruto").val(sum_peso_total);
                                $("#vm_importar_items").modal('hide');
                                $(light).unblock();
                            });
                        } else {
                            swal({   
                                title:'Error',   
                                text: data.mensaje,
                                html: true,
                                type: "error", 
                                confirmButtonColor: "#DD6B55",   
                                confirmButtonText: "Ok",
                            }, function() {
                                $(light).unblock();
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
                            $(light).unblock();
                        });
                    });
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

function calcular_peso_total() {
    var grid = jQuery("#detalle_guia");
    var ids = grid.jqGrid('getDataIDs');

    var peso_total = 0;

    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var peso_unitario = 0;
        if(grid.jqGrid('getCell', id, 'peso') != '') {
            peso_unitario = parseFloat(grid.jqGrid('getCell', id, 'peso'));
        }
        peso_total = peso_total + peso_unitario;
    }

    $("#pesobruto").val(peso_total);
}

function inicializar_autocompletado() {
    $('#transporte_nombre').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc", "placa", "licencia_conducir"],
        searchContain: true, 
        searchIn: 'razon_social',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiaderemision/sugerencias_transportista'
    }).on("select:flexdatalist", function(event, data) {
        $("#titulo_licencia_conducir").removeClass();
        $("#titulo_licencia_conducir").html('N° Licencia Conducir:');

        $("#transporte_numerodocumento").val(data.num_doc);
        $("#transporte_nombre").val(data.razon_social);
        $("#transporte_num_placa").val(data.placa);
        $("#conductor_num_licencia").val(data.licencia_conducir);
    });

    $('#transporte_numerodocumento').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc", "placa", "licencia_conducir"],
        searchContain: true, 
        searchIn: 'num_doc',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiaderemision/sugerencias_transportista'
    }).on("select:flexdatalist", function(event, data) {
        $("#titulo_licencia_conducir").removeClass();
        $("#titulo_licencia_conducir").html('N° Licencia Conducir:');

        $("#transporte_numerodocumento").val(data.num_doc);
        $("#transporte_nombre").val(data.razon_social);
        $("#transporte_num_placa").val(data.placa);
        $("#conductor_num_licencia").val(data.licencia_conducir);
    });

    $('#transporte_num_placa').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc", "placa", "licencia_conducir"],
        searchContain: true, 
        searchIn: 'placa',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiaderemision/sugerencias_transportista'
    }).on("select:flexdatalist", function(event, data) {
        $("#titulo_licencia_conducir").removeClass();
        $("#titulo_licencia_conducir").html('N° Licencia Conducir:');
        
        $("#transporte_numerodocumento").val(data.num_doc);
        $("#transporte_nombre").val(data.razon_social);
        $("#transporte_num_placa").val(data.placa);
        $("#conductor_num_licencia").val(data.licencia_conducir);
    });

    $('#direccionllegada').flexdatalist({
        minLength: 0,
        searchDisabled: false,
        selectionRequired: false,
        visibleProperties: ["dir_destino", "codigo_ubigeo", "ubicacion"],
        searchContain: false, 
        searchIn: 'dir_destino',
        focusFirstResult: true,
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/guiaderemision/sugerencias_direcciones_llegada',
        params: {id_cliente: get_id_ciente()}
    }).on("select:flexdatalist", function(event, data) {
        $("#direccionllegada").val(data.dir_destino);
        $("#ubigeo_llegada").append('<option value="' + data.codigo_ubigeo + '">' + data.ubicacion + '</option>');
        $("#ubigeo_llegada").val(data.codigo_ubigeo).trigger("select2:select");
    });
}

function get_id_ciente() {
    return $("#idcliente").val();
}

function consultar_numero_doc_newcliente(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email, input_telefono = '') {
    $("#icon_search_document_newclient").hide();
    $("#icon_searching_document_newclient").show();
    $(".search_document_newclient").prop('disabled', true);
    $("#cliente_api_foto").val('');
    $("#cliente_api_fecha_nac").val('');
    $("#cliente_api_sexo").val('');

    $.ajax({
        url : '/facturacionv8/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);

                        if (typeof data.data.api !== 'undefined') {
                            if (typeof data.data.api.success !== 'undefined') {
                                if(data.data.api.success === true) {
                                    if (typeof data.data.api.result !== 'undefined') {
                                        if (typeof data.data.api.result.desDireccion !== 'undefined') {
                                            input_direccion.val(data.data.api.result.desDireccion);
                                        }

                                        if (typeof data.data.api.result.feNacimiento !== 'undefined') {
                                            $("#cliente_api_fecha_nac").val(data.data.api.result.feNacimiento);
                                        }

                                        if (typeof data.data.api.result.sexo !== 'undefined') {
                                            $("#cliente_api_sexo").val(data.data.api.result.sexo);
                                        }
                                    }

                                    if (typeof data.data.api.images !== 'undefined') {
                                        if (typeof data.data.api.images.foto !== 'undefined') {
                                            $("#cliente_api_foto").val(data.data.api.images.foto);
                                            $("#cliente_api_foto_src").attr("src", "data:image/png;base64, " + data.data.api.images.foto);
                                            $("#content_cliente_api_foto_src").show();
                                            $("#content_razon_social_cliente").css("display", "table");
                                        } else {
                                            $("#content_cliente_api_foto_src").hide();
                                            $("#content_razon_social_cliente").css("display", "block");
                                        }
                                    }
                                }
                            }
                        }

                        if (typeof data.codigo_ubigeo !== 'undefined' && typeof data.texto_ubigeo !== 'undefined') {
                            input_ubigeo.append('<option value="' + data.codigo_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            input_ubigeo.val(data.codigo_ubigeo).trigger("select2:select");
                        }

                    } else {
                        swal({
                            title: 'Información',
                            text: 'El cliente con DNI N° ' + num_doc + ', ya existe en su base de datos!',
                            html: true,
                            type: "info",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#2196F3"
                        }, function(){
                            $("#idcliente").append('<option value="' + data.data.idcliente + '">' + data.data.razon_social + '</option>');
                            $("#direccionllegada").val(data.data.direccion_fiscal);
                            $("#idcliente").val(data.data.idcliente).trigger("select2:select");
                            $("#ubigeo_llegada").append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            $("#ubigeo_llegada").val(data.data.id_cod_ubigeo).trigger("select2:select");
                            $("#cliente_email").val(data.data.email);
                            $("#new_cliente").modal("hide");
                        });
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            //input_ubigeo.val(data.data.codigo_ubigeo).trigger("change").trigger("select2:select");
                            input_ubigeo.append('<option value="' + data.data.codigo_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            input_ubigeo.val(data.data.codigo_ubigeo).trigger("select2:select");
                        }
                    } else {
                        swal({
                            title: 'Información',
                            text: 'El cliente con DNI N° ' + num_doc + ', ya existe en su base de datos!',
                            html: true,
                            type: "info",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#2196F3"
                        }, function(){
                            $("#idcliente").append('<option value="' + data.data.idcliente + '">' + data.data.razon_social + '</option>');
                            $("#idcliente").val(data.data.idcliente).trigger("select2:select");
                            $("#direccionllegada").val(data.data.direccion_fiscal);
                            $("#ubigeo_llegada").append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            $("#ubigeo_llegada").val(data.data.id_cod_ubigeo).trigger("select2:select");
                            $("#cliente_email").val(data.data.email);
                            $("#new_cliente").modal("hide");
                        });
                    }
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
                }
            }
            $("#lista_guias_cliente").hide('slide');
            if (typeof data.lista_guias !== "undefined") {
                if(data.lista_guias.length > 0) {
                    $("#lista_guias_cliente").show('slide');
                }
                $("#id_guia_remision_electronica").empty();
                $("#id_guia_remision_electronica").append('<option value="">Selecciona un Documento</option>');
                $.each(data.lista_guias, function(key, item) {
                    $("#id_guia_remision_electronica").append('<option value="' + item.id + '">' + item.text + '</option>');
                });
            }

            $("#icon_search_document_newclient").show();
            $("#icon_searching_document_newclient").hide();
            $(".search_document_newclient").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document_newclient").show();
                $("#icon_searching_document_newclient").hide();
                $(".search_document_newclient").prop('disabled', false);
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
            $("#icon_search_document_newclient").show();
            $("#icon_searching_document_newclient").hide();
            $(".search_document_newclient").prop('disabled', false);
        });
    });
}

function get_data_doc_guardado(id_tipodocumento, numero_comprobante, nombre_nuevo_doc, light, serie_comprobante = ''){

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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
	
	//id: f8sjHDGDSAjdh
    $.ajax({
        url : '/facturacionv8/documentoelectronico/get_data_doc',
		method :  'POST',
		data: {id_tipodocumento: id_tipodocumento, numero_comprobante: numero_comprobante, nombre_nuevo_doc: nombre_nuevo_doc, serie_comprobante: serie_comprobante},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $(light).unblock();
            
			limpiar_jq_grid(jQuery('#detalle_guia'));
            data.data_grid.forEach( function(data_item, indice, array) {
				jQuery('#detalle_guia').addRowData(indice + 1, data_item, 'last');
			});

            $("#idcliente").append('<option value="' + data.cliente.idcliente + '">' + data.cliente.razon_social + '</option>');
            $("#direccionllegada").val(data.cliente.direccion_fiscal);
            $("#idcliente").val(data.cliente.idcliente).trigger("select2:select");
            $("#ubigeo_llegada").append('<option value="' + data.cliente.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
            $("#ubigeo_llegada").val(data.cliente.id_cod_ubigeo).trigger("select2:select");
            $("#cliente_email").val(data.cliente.email);
            $("#new_cliente").modal("hide");

            if(data.comprobante.numero_paquetes !== null && data.comprobante.numero_paquetes !== '') {
                $("#numero_bultos").val(data.comprobante.numero_paquetes);
            }

            if(data.comprobante.licencia_conductor !== null && data.comprobante.licencia_conductor !== '') {
                $("#conductor_num_licencia").val(data.comprobante.licencia_conductor);
            }

            if(data.comprobante.nro_documento_transporte !== null && data.comprobante.nro_documento_transporte !== '') {
                $("#transporte_numerodocumento-flexdatalist").val(data.comprobante.nro_documento_transporte);
                $("#transporte_numerodocumento").val(data.comprobante.nro_documento_transporte);
            }

            if(data.comprobante.transporte_nro_placa !== null && data.comprobante.transporte_nro_placa !== '') {
                $("#flexdatalist-transporte_num_placa").val(data.comprobante.transporte_nro_placa);
                $("#transporte_num_placa").val(data.comprobante.transporte_nro_placa);
            }

            if(data.comprobante.razon_social_transporte !== null && data.comprobante.razon_social_transporte !== '') {
                $("#flexdatalist-transporte_nombre").val(data.comprobante.razon_social_transporte);
                $("#transporte_nombre").val(data.comprobante.razon_social_transporte);
            }

            if(data.comprobante.id_tipo_documento_transporte !== null && data.comprobante.id_tipo_documento_transporte !== '') {
                $("#transporte_tipo_docidentidad").val(data.comprobante.id_tipo_documento_transporte).trigger("change").trigger("select2:select");
            }

            if(id_tipodocumento == '01') {
                $("#select_tipo_doc_referencia").val('01').trigger("change");
                $("#serie_doc_referencia").val(serie_comprobante);
                $("#numero_doc_referencia").val(numero_comprobante);
            }

            if(id_tipodocumento == '03') {
                $("#select_tipo_doc_referencia").val('03').trigger("change");
                $("#serie_doc_referencia").val(serie_comprobante);
                $("#numero_doc_referencia").val(numero_comprobante);
            }

            if(id_tipodocumento == '09') {
                $("#select_tipo_doc_referencia").val('03').trigger("change");
                $("#serie_doc_referencia").val(serie_comprobante);
                $("#numero_doc_referencia").val(numero_comprobante);
            }

            calcular_peso_total();

            if(data.comprobante.peso !== null && data.comprobante.peso !== '') {
                var peso_total = parseFloat(data.comprobante.peso);
                $("#pesobruto").val(peso_total);
            }
            
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
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
			$(light).unblock();
		});
    });
}

function limpiar_jq_grid(jq_grid) {
	var rowIds = jq_grid.jqGrid('getDataIDs');
	for(var i=0,len=rowIds.length;i<len;i++){
		var currRow = rowIds[i];
		jq_grid.jqGrid('delRowData', currRow);
	} 
}

function guardar_guia_remision() {
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
        url : '/facturacionv8/guiaderemision/guardar_guia_remision',
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
				confirmButtonText: "Si, Adelante!",   
				closeOnConfirm: false,
  				showLoaderOnConfirm: true
			}, function(isconfirmed){  
				if(isconfirmed) {
					var confirmado = 'si';
					datastring.push({name: 'confirmacion', value: confirmado});
					//id: f8sjHDGDSAjdh
                    $.ajax({
						url : '/facturacionv8/guiaderemision/guardar_guia_remision',
						method :  'POST',
						data: datastring,
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: 'Excelente',   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",   
									cancelButtonColor: "#4CAF50",
									confirmButtonText: "Ver Lista de Guías >",
									cancelButtonText: "Realizar Otra Guía!",
									showCancelButton: true
								}, function(isconfirmed){  
									if(isconfirmed) {
										window.location.href = "/facturacionv8/dashboard/index/" + data.documento.id_contribuyente + "/" + data.documento.id_tipodoc_electronico + "/" + data.documento.serie_comprobante + "/" + data.documento.numero_comprobante;
									} else {
										window.location.reload();
									}
								});
								$(light).unblock();
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

function get_documentos_cliente(idcliente) {
    //id: f8sjHDGDSAjdh
    $.ajax({
        url : '/facturacionv8/guiaderemision/get_documentos_cliente',
        method :  'POST',
        data: {idcliente: idcliente},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#select_doc_referencia").empty();

            $("#select_doc_referencia").append('<option value="">Selecciona un Documento</option>');
			$.each(data.listadocumentos, function(key, item) {
				$("#select_doc_referencia").append('<option value="' + item.id + '">' + item.text + '</option>');
            });
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
            $("#serie_comprobante").val(data.sucursal.guia_remision_serie);
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

function get_lista_conductores_autos(select_conductores, select_autos, id_etransporte) {
    $.ajax({
        url : '/facturacionv8/guiaderemision/get_lista_conductores_autos',
        data: {id_etransporte: id_etransporte},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            select_conductores.empty();
            select_autos.empty();

			$.each(data.conductores, function(key, item) {
				select_conductores.append('<option value="' + item.idconductor + '">' + item.nombre_completo + ' - ' + item.num_doc + '</option>');
            });
            
            $.each(data.vehiculos, function(key, item) {
				select_autos.append('<option value="' + item.id_vehiculo + '">' + item.marca + ' - ' + item.nro_placa + '</option>');
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
			
		});
    });
}

function get_lista_empresas_transporte(select, seleccion = -1) {
    $.ajax({
        url : '/facturacionv8/guiaderemision/get_empresastransporte',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_etransporte + '">' + item.razon_social + ' - ' + item.num_doc + '</option>');
			});
			if(seleccion >= 0) {
				select.val(seleccion).trigger("change").trigger("select2:select");
            }
            
            select.trigger("change");
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
			
		});
    });
}

function eliminar_producto_detalle() {
    var rowid = jQuery('#detalle_guia').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#detalle_guia");
    if(rowid == null) {
        swal({   
            title:'Error',   
            text: 'Debes seleccionar un elemento para poder eliminarlo!!',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });
    } else {
        $grid.jqGrid('delRowData', rowid);
    }
}

function editar_item_tabla(rowid) {
    if(rowid === undefined || rowid == '' || rowid <= 0) {
        return false;
    }

    $("#key_row").val(rowid);
    var data = jQuery("#detalle_guia").jqGrid('getRowData', rowid);

    $("#producto_idproducto").val(data.idarticulo);
    $("#key_row").val(rowid);
    $("#select_producto_buscar").append('<option value="' + data.idarticulo + '">' + data.descripcion + '</option>');
    $("#select_producto_buscar").val(data.idarticulo).trigger("select2:select");
    $("#producto_unidadmedida").val(data.idunidadmedida).trigger("change").trigger("select2:select");
    $("#producto_descripcion").val(data.descripcion);
    $("#producto_cantidad").val(data.cantidad);
    $("#producto_peso").val(data.peso_unitario);
    $("#producto_codigo").val(data.codigo);
    $("#producto_peso_total").val(data.peso);

    console.log(data.select_unidadmedida);

    array_select_opcion_unidades = JSON.parse(data.select_unidadmedida);
    $('#producto_unidadmedida').empty();
    $.each(array_select_opcion_unidades, function(key, item) {
        var preciosinigv = 0;
        var precioconigv = 0;
        
        $('#producto_unidadmedida').append(
            $('<option />')
            .val(item.val)
            .text(item.text)
            .attr({
                "data-tipo"                 : item.tipo,
                "data-idpresentacion"       : item.idpresentacion,
                "data-codigo"               : item.codigo,
                "data-nombrepresentacion"   : item.nombrepresentacion,
                "data-idproducto"           : item.idproducto,
                "data-idunidadpresentacion" : item.idunidadpresentacion,
                "data-idunidadbase"         : item.idunidadbase,
                "data-nombreunidadpresentacion": item.nombreunidadpresentacion,
                "data-cantidad"             : item.cantidad,
                "data-nombreunidadbase"     : item.nombreunidadbase,
                "data-precioconigv"         : precioconigv,
                "data-preciosinigv"         : preciosinigv,
                "data-idcodmoneda"          : item.idcodmoneda
            })
        );
    });

    $("#producto_unidadmedida").val(data.idunidadmedida).trigger("select2:select");
}

function add_to_detalle() {

    var idarticulo = $('#producto_idproducto').val();
    var precio = $('#producto_preciounidad').val();
    var cantidad = $('#producto_cantidad').val();
    var codigo = $('#producto_codigo').val();
    var descripcion_prod = $('#producto_descripcion').val();
    var peso = $('#producto_peso_total').val();
    var peso_unitario = $('#producto_peso').val();

    if(idarticulo === undefined || idarticulo == '' || idarticulo <= 0) {
        swal({   
            title:'Error',   
            text: 'El Producto que Intentas Agregar no se Encuentra registrado en tu Almacén!',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });
        return false;
    }

    if(descripcion_prod === undefined || descripcion_prod == '' || descripcion_prod <= 0) {
        swal({   
            title:'Error',   
            text: 'Debes agregar un nombre al producto, no debes agregar productos con nombres vacíos!',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });
        return false;
    }

    if(cantidad === undefined || cantidad == '' || cantidad < 0) {
        swal({   
            title:'Error',   
            text: 'Debes agregar una cantidad válida!',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });
        return false;
    }
    
    row_identificadador = get_uid();

    var opciones_select = [];

    $("#producto_unidadmedida > option").each(function() {
        var opcion_select = {};

        opcion_select = {
            val                 : $(this).val(),
            text                : $(this).text(),

            tipo                : $(this).data('tipo'),
            idpresentacion      : $(this).data('idpresentacion'),
            codigo              : $(this).data('codigo'),
            nombrepresentacion  : $(this).data('nombrepresentacion'),
            idproducto          : $(this).data('idproducto'),
            idunidadpresentacion: $(this).data('idunidadpresentacion'),
            idunidadbase        : $(this).data('idunidadbase'),
            nombreunidadpresentacion: $(this).data('nombreunidadpresentacion'),
            cantidad            : $(this).data('cantidad'),
            nombreunidadbase    : $(this).data('nombreunidadbase'),
            precioconigv        : 0,
            preciosinigv        : 0,
            idcodmoneda         : $(this).data('idcodmoneda')
        };

        opciones_select.push(opcion_select);
    });

    var json_select_unidadmedida = JSON.stringify(opciones_select);
    var tipo_unidad = $("#producto_unidadmedida").find(':selected').data('tipo');
    var idpresentacion = $("#producto_unidadmedida").find(':selected').data('idpresentacion');
    
    var data = {
        row_identificadador: row_identificadador,
        idarticulo: idarticulo,
        afecto_icbper: '',
        subtotal_icbper: '',
        id_tipoafectacionigv: '',
        descripcion: $('#producto_descripcion').val(),
        text_tipo_afecigv: '',
        idunidadmedida: $('#producto_unidadmedida').val(),
        unidadmedida: $("#producto_unidadmedida option:selected" ).text(),
        precio: precio,
        id_cod_moneda: '',
        cantidad: cantidad,
        subtotal: '',
        igv: '',
        importe: '',
        estado: 'V',
        codigo: codigo,
        html_lista_precios: '',
        item_detraccion_codigo: '',
        item_detraccion_porcentaje: '',

        select_unidadmedida: json_select_unidadmedida,
        tipo_unidad: tipo_unidad,
        idpresentacion: idpresentacion,

        p_unit_sin_igv: '',
        factor_igv_sunat: '',
        stock_actual: '',
        peso: peso,
        peso_unitario: peso_unitario
    };

    var key_row = $("#key_row").val();
	if(key_row != '') {
        var su = jQuery('#detalle_guia').jqGrid('setRowData', key_row, data);
        
        $("#key_row").val("");
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        btn_productos_accion = 'agregar';
        $(".content_propiedades_producto").hide();
        $('#select_producto_buscar').select2('open');
	} else {
        if(valida_si_existe_item(row_identificadador, data)) {
            swal({   
                title:'Error',   
                text: 'No puede agregar items repetidos al detalle!',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                calcular_totales_documento();
                $("#key_row").val("");
                $("#select_producto_buscar").empty();
                $("#frm_producto")[0].reset();
                btn_productos_accion = 'agregar';
                $(".content_propiedades_producto").hide();
                $('#select_producto_buscar').select2('open');
				return false;
            });
            
        } else {
            var su = jQuery('#detalle_guia').addRowData(row_identificadador, data, 'last');
            
            $("#key_row").val("");
            $("#select_producto_buscar").empty();
            $("#frm_producto")[0].reset();
            btn_productos_accion = 'agregar';
            $(".content_propiedades_producto").hide();
            $('#select_producto_buscar').select2('open');
        }
    }

    calcular_peso_total();
}

function valida_si_existe_item(idarticulo) {
    var grid = jQuery("#detalle_guia");
    var ids = grid.jqGrid('getDataIDs');
    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var idproducto = parseFloat(grid.jqGrid('getCell', id, 'idarticulo'));
        if(idproducto == idarticulo) {
            return true;
        }
    }

    return false;
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

function inicializar_tabla_detalle() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	detalle_documento = $('#detalle_guia');

    detalle_documento = detalle_documento.jqGrid({
        url: '',
        datatype: 'json',
        mtype: 'POST',
        colNames: [
            'row_identificadador',
            'idarticulo',
            'afecto_icbper',
            'subtotal_icbper',
            'id_tipoafectacionigv',
            'Descripcion',
            'Tipo IGV',
            'idunidadmedida',
            'Und/Medida',
            'Precio',
            'id_cod_moneda',
            'Cantidad',
            'Sub.Total',
            'Igv',
            'Importe',
            'Estado',
            'Codigo',
            'html_lista_precios',
            'item_detraccion_codigo',
            'item_detraccion_porcentaje',

            'select_unidadmedida',
            'tipo_unidad', //presentacion, unidad
            'IdPresentacion',

            'p_unit_sin_igv',
            'factor_igv_sunat',

            'stock_actual',
            'Peso Unit.(KGM)',
            'Peso Total (KGM)'
        ],
        
        colModel: [
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'idarticulo', index: '2', hidden: true},
            { name: 'afecto_icbper', index: '1', hidden: true},
            { name: 'subtotal_icbper', index: '1', hidden: true},
            { name: 'id_tipoafectacionigv', index: '3', hidden: true},
            { name: 'descripcion', index: '4', width: 360, },
            { name: 'text_tipo_afecigv', index: '5', width: 120, hidden: true },
            { name: 'idunidadmedida', index: '6', hidden: true },
            { name: 'unidadmedida', index: '7', width: 120 },
            { name: 'precio', index: '8', width: 80, align: "right", sorttype: 'float', hidden: true },
            { name: 'id_cod_moneda', index: '9', hidden: true},
            { name: 'cantidad', index: '10', width: 80, align: "right", sorttype: 'float' },
            { name: 'subtotal', index: '11', width: 85, align: "right", sorttype: 'float', hidden: true },
            { name: 'igv', index: '12', width: 60, align: "right", sorttype: 'float', hidden: true },
            { name: 'importe', index: '13', width: 90, align: "right", sorttype: 'float', hidden: true },
            { name: 'estado', index: '14', hidden: true, hidden: true },
            { name: 'codigo', index: '15', hidden: true},
            { name: 'html_lista_precios', index: '15', hidden: true},
            { name: 'item_detraccion_codigo', index: '15', hidden: true},
            { name: 'item_detraccion_porcentaje', index: '15', hidden: true},
            { name: 'select_unidadmedida', index: '15', hidden: true},
            { name: 'tipo_unidad', index: '15', hidden: true},
            { name: 'idpresentacion', index: '15', hidden: true},
            { name: 'p_unit_sin_igv', index: '15', hidden: true},
            { name: 'factor_igv_sunat', index: '15', hidden: true},
            { name: 'stock_actual', index: '15', hidden: true},
            { name: 'peso_unitario', index: '6', width: 125, align: "right", hidden: false},
            { name: 'peso', index: '6', width: 125, align: "right", hidden: false},
        ],
        height: 250,
        rowNum: 0,
        loadOnce: true,
        viewrecords: true,
        gridview: true,
        autowidth:true, 
        shrinkToFit:false,
        forceFit:true,
        cellEdit: true,
        sortname: 'codigo',
        cellsubmit: 'clientArray',
        editurl: 'clientArray',
        beforeRequest: function () {
            responsive_table($(".jqGrid"));
        },
        jsonReader: {
            repeatitems: false,
            root: 'lstLista'
        },
        gridComplete: function () {
            //SE EJECUTA CUANDO AGREGAS NUEVO REGISTRO A LA GRILLA
            
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            //SE EJECUTA CUANDO MODIFICAMOS UN REGISTRO
            //                        calculateImporte();
            //                        calculateTotal();
        },
        ondblClickRow: function(rowid) {
            $(".btn_editar_producto").trigger('click');
        }
    });
}

function responsive_table(table) {
    table.find('.ui-jqgrid').addClass('clear-margin span12').css('width', '');
    table.find('.ui-jqgrid-view').addClass('clear-margin span12').css('width', '');
    table.find('.ui-jqgrid-view > div').eq(1).addClass('clear-margin span12').css('width', '').css('min-height', '0');
    table.find('.ui-jqgrid-view > div').eq(2).addClass('clear-margin span12').css('width', '').css('min-height', '0');
    table.find('.ui-jqgrid-sdiv').addClass('clear-margin span12').css('width', '');
    table.find('.ui-jqgrid-pager').addClass('clear-margin span12').css('width', '');
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

function sugerencias_clientes(select) {
    select.select2({
        language: "es",
        ajax: {
          type: "post",
          url: "/facturacionv8/client/get_lista_sugerencias_clientes",
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

function sugerencias_ubigeo_new_client(select) {
    select.select2({
        language: "es",
        dropdownParent: $('#new_cliente'),
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

function guardar_cliente(){
    var light = $('#content_vm_agregar_cliente');
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
    
	var datastring = $("#frm_addclient").serializeArray();
	//id: f8sjHDGDSAjdh
    $.ajax({
        url : "/facturacionv8/client/insert",
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title:'Ok',   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                console.log(data.cliente);
                $("#idcliente").append('<option value="' + data.cliente.idcliente + '">' + data.cliente.razon_social + '</option>');
                $("#direccionllegada").val(data.cliente.direccion_fiscal);
                $("#idcliente").val(data.cliente.idcliente).trigger("select2:select");
                $("#ubigeo_llegada").append('<option value="' + data.cliente.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                $("#ubigeo_llegada").val(data.cliente.id_cod_ubigeo).trigger("select2:select");
                $("#cliente_email").val(data.cliente.email);
                $("#new_cliente").modal("hide");

				$("#frm_addclient")[0].reset();
                $("#new_cliente").modal("hide");
                $(light).unblock();
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
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
			$(light).unblock();
		});
    });
}

function consultar_numero_doc_cliente(input_idusuario = '', num_doc, input_nombre, input_direccion = '', input_ubigeo = '', tipo_doc, input_email = '', input_telefono = '') {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);

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

                        if (typeof data.data.api !== 'undefined') {
                            if (typeof data.data.api.success !== 'undefined') {
                                if(data.data.api.success === true) {
                                    if (typeof data.data.api.result !== 'undefined') {
                                        if (typeof data.data.api.result.desDireccion !== 'undefined') {
                                            
                                        }

                                        if (typeof data.data.api.result.feNacimiento !== 'undefined') {
                                            $("#cliente_api_fecha_nac").val(data.data.api.result.feNacimiento);
                                        }

                                        if (typeof data.data.api.result.sexo !== 'undefined') {
                                            $("#cliente_api_sexo").val(data.data.api.result.sexo);
                                        }
                                    }

                                    if (typeof data.data.api.images !== 'undefined') {
                                        if (typeof data.data.api.images.foto !== 'undefined') {
                                            $("#cliente_api_foto").val(data.data.api.images.foto);
                                            $("#cliente_api_foto_src").attr("src", "data:image/png;base64, " + data.data.api.images.foto);
                                            $("#content_cliente_api_foto_src").show();
                                            $("#content_razon_social_cliente").css("display", "table");
                                        } else {
                                            $("#content_cliente_api_foto_src").hide();
                                            $("#content_razon_social_cliente").css("display", "block");
                                        }
                                    }
                                }
                            }
                        }

                        if (typeof data.codigo_ubigeo !== 'undefined' && typeof data.texto_ubigeo !== 'undefined') {

                        }

                    } else {
                        input_nombre.val(data.data.razon_social);

                        if (typeof data.data.foto !== 'undefined') {
                            if(data.data.foto !== null && data.data.foto !== '') {
                                $("#cliente_api_foto").val(data.data.foto);
                                $("#cliente_api_foto_src").attr("src", data.data.foto);
                                $("#content_cliente_api_foto_src").show();
                                $("#content_razon_social_cliente").css("display", "table");
                            } else {
                                $("#content_cliente_api_foto_src").hide();
                                $("#content_razon_social_cliente").css("display", "block");
                            }
                        }
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
            $("#lista_guias_cliente").hide('slide');
            if (typeof data.lista_guias !== "undefined") {
                if(data.lista_guias.length > 0) {
                    $("#lista_guias_cliente").show('slide');
                }
                $("#id_guia_remision_electronica").empty();
                $("#id_guia_remision_electronica").append('<option value="">Selecciona un Documento</option>');
                $.each(data.lista_guias, function(key, item) {
                    $("#id_guia_remision_electronica").append('<option value="' + item.id + '">' + item.text + '</option>');
                });
            }

            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document").show();
                $("#icon_searching_document").hide();
                $(".search_document").prop('disabled', false);
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
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        });
    });
}

function agregar_licencia_conducir(txt_licencia) {
    $("#conductor_num_licencia").val(txt_licencia);
}