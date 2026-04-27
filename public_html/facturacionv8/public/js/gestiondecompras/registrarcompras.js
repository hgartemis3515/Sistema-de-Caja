var btn_productos_accion = '';
$(function(){

    var tipo_doc_selected = $("#tipo_doc_selected").val();
    var ultimo_texto_buscado = '';
    
    inicializar_controles();
    $('.control_fecha').on('change.datepicker', function(ev){
        var picker = $(ev.target).data('daterangepicker');
        //console.log(picker.startDate.format('YYYY-MM-DD')); // contains the selected start date
        //console.log(picker.endDate); // contains the selected end date 
        get_tipocambio_by_date($("#tipo_cambio_comprobante"), picker.startDate.format('YYYY-MM-DD'));
    });

    $("#file_xml_comprobante").uniform({
		fileButtonClass: 'action btn btn-primary',
		fileButtonHtml: 'Seleccionar Archivo XML'
	});

    $("#file_xml_comprobante").change(leer_xml_cpe);
	
    get_lista_monedas($("#codmoneda_comprobante"));
	get_lista_ubigeos($("#select_codigoubigeo"));
    get_lista_tipoafectacionigv($("#producto_tipo_afect_igv"));
    get_lista_unidades_medida($("#producto_unidadmedida"));
    get_lista_tipo_doc_identidad($("#proveedor_tipo_docidentidad"), 6);
    
    get_tipo_cambio($("#tipo_cambio_comprobante"));
    get_tipocambio_by_date($("#new_producto_tipodecambio"));
    
    $('.select_codigoubigeo').select2();
    
	$('.select').select2({ minimumResultsForSearch: -1 });
 
	inicializar_tabla_detalle();
	inicializar_buscador();

	$(".btn_agregarproducto").click(function() {
        $('.opciones_producto a[href="#buscar_producto"]').tab('show');
        $("#key_row").val("");
        $("#select_producto_buscar").empty();
        //$("#frm_nuevo_producto")[0].reset();
        reset_form_registro_new_producto();
        $("#frm_producto")[0].reset();
        $(".content_propiedades_producto").hide();
        btn_productos_accion = 'agregar';
        calcular_totales_documento();
        $("#vm_agregar_articulo").modal("show");
    });

    $(".btn_eliminarproducto").click(eliminar_producto_detalle);

    $('#proveedor_numerodocumento').keyup(function(e){
        if(e.keyCode == 13) {
            $(".search_document").trigger("click");
        }
    });

    $(".search_document").click(function() {
        if(typeof $("#proveedor_numerodocumento-flexdatalist").val() != "undefined" && $("#proveedor_numerodocumento-flexdatalist").val() != '') {
            var num_doc = $("#proveedor_numerodocumento-flexdatalist").val();
            $("#proveedor_numerodocumento").val($("#proveedor_numerodocumento-flexdatalist").val());
            console.log('ingres aqui!', $("#proveedor_numerodocumento-flexdatalist").val());
        } else {
            var num_doc = $("#proveedor_numerodocumento").val();
        }
         
		if(num_doc != '') {
            var tipo_doc = $("#proveedor_tipo_docidentidad").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                consultar_numero_doc_proveedor($("#id_proveedor_documento"), num_doc, $("#proveedor_nombre"), $("#proveedor_direccion"), $("#select_codigoubigeo"), tipo_doc, $("#proveedor_email"));
            }
		}
	});
    
	//Funcionalida de venta de buscar producto en modal
	$('.select_producto_buscar').on('change', get_data_producto);
    $('#producto_tipo_afect_igv').on('change', calcular_totales_producto);
    
    $('.select_with_search').select2();

    $(".totales_input").on('input', function() {
        calcular_totales_producto();
    }).on('change', function() {
        calcular_totales_producto();
    });

    $("#ruc_emisor_apicpe").on('input', function() {
        $("#proveedor_numerodocumento").val($("#ruc_emisor_apicpe").val());
    }).on('change', function() {
        $("#proveedor_numerodocumento").val($("#ruc_emisor_apicpe").val());
    });

    $("#serie_comprobante_apicpe").on('input', function() {
        $("#serie_comprobante_apicpe").val(this.value.toUpperCase());
    }).on('change', function() {
        $("#serie_comprobante_apicpe").val(this.value.toUpperCase())
    });

    $("#serie_comprobante_apicpe").on('input', function() {
        $("#serie_comprobante").val($("#serie_comprobante_apicpe").val());
    }).on('change', function() {
        $("#serie_comprobante").val($("#serie_comprobante_apicpe").val());
    });

    $("#correlativo_comprobante_apicpe").on('input', function() {
        $("#numero_comprobante").val($("#correlativo_comprobante_apicpe").val());
    }).on('change', function() {
        $("#numero_comprobante").val($("#correlativo_comprobante_apicpe").val());
    });

    $("#ruc_emisor_apicpe").trigger('input');
    $("#serie_comprobante_apicpe").trigger('input');
    $("#correlativo_comprobante_apicpe").trigger('input');
	
	$("#producto_preciounidad").on('input', function() {
        calcular_precio_sin_igv();
        calcular_totales_producto();
        calcular_nuevos_precios_de_venta();
    }).on('change', function() {
        calcular_precio_sin_igv();
        calcular_totales_producto();
        calcular_nuevos_precios_de_venta();
    });

    $("#serie_comprobante").on('input', function() {
        $("#serie_comprobante").val(this.value.toUpperCase());
    }).on('change', function() {
        $("#serie_comprobante").val(this.value.toUpperCase())
    });

    $("#producto_preciounidad_sin_igv").on('input', function() {
        calcular_precio_con_igv();
        calcular_totales_producto();
    }).on('change', function() {
        calcular_precio_con_igv();
        calcular_totales_producto();
	});
	
	$(".totales_input").click(function() {
        $(this).select();
    });

    $(".new_prod_inputs_selected").click(function() {
        $(this).select();
    });
    
    $(".input_editable").click(function() {
        $(this).select();
	});
	
	$('.totales_input').keyup(function(e){
        if(e.keyCode == 13) {
            $(".btn_agregarproducto_detalle").trigger("click");
        }
    });

    $(".input_modify_totales").on('input', function() {
        calcular_totales_documento();
    }).on('change', function() {
        calcular_totales_documento();
    });

    $(".btn_agregarproducto_detalle").click(add_to_detalle);

    $(".btn_editarproducto").click(function() {
        $('.opciones_producto a[href="#buscar_producto"]').tab('show');
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        //$("#frm_nuevo_producto")[0].reset();
        reset_form_registro_new_producto();
        var rowid = jQuery("#detalle_documento").jqGrid('getGridParam', 'selrow');
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
            btn_productos_accion = 'editar';
            $("#vm_agregar_articulo").modal("show");
            $(".content_propiedades_producto").show('slide');
        }
    });

    $("#codmoneda_comprobante").on('change', recalcular_por_cambio_de_moneda);

    $('#select_producto_buscar').on('select2:open', function() {
        $('.select2-search__field').first().selectionStart = $('.select2-search__field').first().selectionEnd = $('.select2-search__field').first().val().length;
    });

    $('#vm_agregar_articulo').on('shown.bs.modal', function (e) {
        if(btn_productos_accion == 'agregar') {
            $('#select_producto_buscar').select2('open');
        }

        if(btn_productos_accion == 'editar') {
            $("#producto_cantidad").focus().select();
        }
    });

    $("#btn_guardar_compra").click(guardar_documento_electronico);
    $(".tipo_comprobante ").on("change", function () {
        let tipo_documento = $(this).val();
        //console.log('tipo_documento => ',tipo_documento);
        if(tipo_documento == '07') {
            $("#serie_comprobante").prop('disabled', false);
            $("#numero_comprobante").prop('disabled', false);
            $("#serie_comprobante").val("");
            $("#numero_comprobante").val("");

            $("#content_comprobante_modifica").show('slide');
            $("#contenido_motivo_nota_credito").show();
            $("#contenido_motivo_nota_debito").hide();
            $("#texto_datos_comprobante").html('Datos de la Nota de Crédito')
            $("#texto_titulo_general").html('Registro de una Nota de Crédito - Compras');

            $(".data_sunat_cpe").hide();
            $(".data_manual_cpe").show('slide');
            $(".content_opcion_buscar_cpe").hide('slide');
        } else if(tipo_documento == '08') {
            $("#serie_comprobante").prop('disabled', false);
            $("#numero_comprobante").prop('disabled', false);
            $("#serie_comprobante").val("");
            $("#numero_comprobante").val("");

            $("#content_comprobante_modifica").show('slide');
            $("#contenido_motivo_nota_credito").hide();
            $("#contenido_motivo_nota_debito").show();
            $("#texto_datos_comprobante").html('Datos de la Nota de Débito');
            $("#texto_titulo_general").html('Registro de una Nota de Débito - Compras');

            $(".data_sunat_cpe").hide('slide');
            $(".data_manual_cpe").show('slide');
            $(".content_opcion_buscar_cpe").hide('slide');

        } else if(tipo_documento == '01') {
            $("#serie_comprobante").prop('disabled', false);
            $("#numero_comprobante").prop('disabled', false);
            $("#serie_comprobante").val("");
            $("#numero_comprobante").val("");

            $("#content_comprobante_modifica").hide('slide');
            $("#texto_datos_comprobante").html('Datos de la Factura de Compra');
            $("#texto_titulo_general").html('Registro de una Factura - Compras');

            $(".content_opcion_buscar_cpe").show('slide');
            if($("#opcion_buscar_cpe").is(":checked")) {
                $(".data_sunat_cpe").show('slide');
                $(".data_manual_cpe").hide('slide');
            } else {
                $(".data_sunat_cpe").hide('slide');
                $(".data_manual_cpe").show('slide');
            }
            
        } else if(tipo_documento == '03') {
            $("#serie_comprobante").prop('disabled', false);
            $("#numero_comprobante").prop('disabled', false);
            $("#serie_comprobante").val("");
            $("#numero_comprobante").val("");

            $("#content_comprobante_modifica").hide('slide');
            $("#texto_datos_comprobante").html('Datos de la Boleta de Compra');
            $("#texto_titulo_general").html('Registro de una Boleta - Compras');

            $(".data_sunat_cpe").hide('slide');
            $(".data_manual_cpe").show('slide');
            $(".content_opcion_buscar_cpe").hide('slide');
        } else if(tipo_documento == '99') {
            $("#serie_comprobante").prop('disabled', true);
            $("#numero_comprobante").prop('disabled', true);

            get_info_sucursal_cpe();

            $("#content_comprobante_modifica").hide('slide');
            $("#texto_datos_comprobante").html('Datos de la Orden de Compra');
            $("#texto_titulo_general").html('Registro de una Orden de Compra');

            $(".data_sunat_cpe").hide('slide');
            $(".data_manual_cpe").show('slide');
            $(".content_opcion_buscar_cpe").hide('slide');
        } else  {

            $("#serie_comprobante").prop('disabled', false);
            $("#numero_comprobante").prop('disabled', false);
            $("#serie_comprobante").val("");
            $("#numero_comprobante").val("");

            $("#content_comprobante_modifica").hide('slide');
            $("#texto_datos_comprobante").html('Datos del Comprobante de Compra Desconocido');
            $("#texto_titulo_general").html('Registro de una Entrada - Compras');

            $(".data_sunat_cpe").hide('slide');
            $(".data_manual_cpe").show('slide');
            $(".content_opcion_buscar_cpe").hide('slide');
        }
    });

    get_tiponotadebito($("#id_motivo_nota_debito"));
    get_tiponotacredito($("#id_motivo_nota_credito"));
    $('#opcion_tipo_descuento').on('change', function() {
        var checkbox = document.querySelector('.opcion_tipo_descuento');
        if (checkbox.checked) {
            $("#txt_titulo_opcion_descuento").html('Descuento en Porcentaje (%)');
            $("#content_descuento_porcentaje_input").show();
            $("#content_descuento_total").hide();
        } else {
            $("#txt_titulo_opcion_descuento").html('Descuento por Monto');
            $("#content_descuento_total").show();
            $("#content_descuento_porcentaje_input").hide();
        }
    });

    if($("#tipo_doc_seleccionado").val() == '00') {
        $("#tipo_otro").prop( "checked", true ).uniform('refresh');
        $("#tipo_notadebito").prop( "checked", false ).uniform('refresh');
        $("#tipo_notacredito").prop( "checked", false ).uniform('refresh');
        $("#tipo_boleta").prop( "checked", false ).uniform('refresh');
        $("#tipo_factura").prop( "checked", false ).uniform('refresh');
        $("#tipo_ordencompra").prop( "checked", false ).uniform('refresh');
        $(".tipo_comprobante").trigger('change');
    }
    
    $(".btn_get_cpe_sunat").click(get_data_cpe);
    $(".switch").bootstrapSwitch();
    $('#opcion_buscar_cpe').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $(".data_sunat_cpe").show('slide');
            $(".data_manual_cpe").hide('slide');
        } else {
            $(".data_sunat_cpe").hide('slide');
            $(".data_manual_cpe").show('slide');
        }
    });

    $('#opcion_buscar_cpe').trigger('switchChange.bootstrapSwitch', false);

    $("#txt_porcentaje_ganancia").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        var porcentaje_ganancia = 1 + round_math(parseFloat($("#txt_porcentaje_ganancia").val())/100, 2);
        var precio_venta = parseFloat($("#nuevo_producto_precio_compra").val());
        var precio_minimo = round_math(porcentaje_ganancia*precio_venta, numero_decimales);
        $("#nuevo_producto_preciominimo").val(precio_minimo);
    });
    
    $("#txt_porcentaje_ganancia_maxima").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        var porcentaje_ganancia = 1 + round_math(parseFloat($("#txt_porcentaje_ganancia_maxima").val())/100, 2);
        var precio_venta = parseFloat($("#nuevo_producto_precio_compra").val());
        var precio_minimo = round_math(porcentaje_ganancia*precio_venta, numero_decimales);
        $("#nuevo_producto_valor_con_igv").val(precio_minimo);
        $("#nuevo_producto_valor_con_igv").trigger('input');
    });

    $("#producto_total").on('change', function() {
        var total =  $("#producto_total").val();
        var precio = $("#producto_preciounidad").val();
        var cantidad = 0;

        var numero_decimales = 4;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        if(precio == '' || precio <= 0 || isNaN(precio)) {
            precio = 0;
        }

        if(total == '' || total <= 0 || isNaN(total)) {
            total = 0;
        }

        if(precio > 0) {
            cantidad = round_math(parseFloat(total)/parseFloat(precio), numero_decimales);
        }

        $("#producto_cantidad").val(cantidad);
        calcular_totales_producto();
    });

    inicializar_autocompletado();

    
    //INICIO FUNCIONALIDAD DE CAMBIO DE PRECIO DE VENTA

    $("#porcentaje_ganancia_maxima").on('input', function() {
        var precio_compra = parseFloat($("#producto_preciounidad").val());
        var porcentaje_ganancia_maxima = parseFloat($("#porcentaje_ganancia_maxima").val());

        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#producto_nuevo_precio_venta").val(0);
            //$("#producto_nuevo_precio_minimo").val(0);
            return;
        }
        
        if(isNaN(porcentaje_ganancia_maxima) || porcentaje_ganancia_maxima == '' || typeof porcentaje_ganancia_maxima === undefined){
            porcentaje_ganancia_maxima = 0;
            $("#producto_nuevo_precio_venta").val(precio_compra);
            return;
        }

        if(porcentaje_ganancia_maxima <= 0) {
            $("#producto_nuevo_precio_venta").val(precio_compra);
            return;
        }

        if(precio_compra <= 0) {
            $("#producto_nuevo_precio_venta").val(0);
            return;
        }

        var producto_nuevo_precio_venta = round_math(parseFloat(((porcentaje_ganancia_maxima/100)+1)*precio_compra),num_decimales);
        $("#producto_nuevo_precio_venta").val(producto_nuevo_precio_venta);
    });

    $("#producto_nuevo_precio_venta").on('input', function() {
        var precio_compra = parseFloat($("#producto_preciounidad").val());
        var producto_nuevo_precio_venta = parseFloat($("#producto_nuevo_precio_venta").val());

        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#porcentaje_ganancia_maxima").val(0);
            //$("#producto_nuevo_precio_minimo").val(0);
            return;
        }
        
        if(isNaN(producto_nuevo_precio_venta) || producto_nuevo_precio_venta == '' || typeof producto_nuevo_precio_venta === undefined){
            producto_nuevo_precio_venta = 0;
            $("#porcentaje_ganancia_maxima").val(0);
            return;
        }

        if(producto_nuevo_precio_venta <= 0) {
            $("#porcentaje_ganancia_maxima").val(0);
            return;
        }

        if(precio_compra <= 0) {
            $("#porcentaje_ganancia_maxima").val(0);
            return;
        }

        var porcentaje_ganancia_maxima = round_math(parseFloat((producto_nuevo_precio_venta*100)/precio_compra - 100),4);
        if(porcentaje_ganancia_maxima <= 0) {
            $("#porcentaje_ganancia_maxima").val(0);
        } else {
            $("#porcentaje_ganancia_maxima").val(porcentaje_ganancia_maxima);
        }
        
    });

    $("#porcentaje_ganancia_minima").on('input', function() {
        var precio_compra = parseFloat($("#producto_preciounidad").val());
        var porcentaje_ganancia_minima = parseFloat($("#porcentaje_ganancia_minima").val());

        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#producto_nuevo_precio_minimo").val(0);
            //$("#producto_nuevo_precio_minimo").val(0);
            return;
        }
        
        if(isNaN(porcentaje_ganancia_minima) || porcentaje_ganancia_minima == '' || typeof porcentaje_ganancia_minima === undefined){
            porcentaje_ganancia_minima = 0;
            $("#producto_nuevo_precio_minimo").val(precio_compra);
            return;
        }

        if(porcentaje_ganancia_minima <= 0) {
            $("#producto_nuevo_precio_minimo").val(precio_compra);
            return;
        }

        if(precio_compra <= 0) {
            $("#producto_nuevo_precio_minimo").val(0);
            return;
        }

        var producto_nuevo_precio_minimo = round_math(parseFloat(((porcentaje_ganancia_minima/100)+1)*precio_compra),num_decimales);
        $("#producto_nuevo_precio_minimo").val(producto_nuevo_precio_minimo);
    });

    $("#producto_nuevo_precio_minimo").on('input', function() {
        var precio_compra = parseFloat($("#producto_preciounidad").val());
        var producto_nuevo_precio_minimo = parseFloat($("#producto_nuevo_precio_minimo").val());

        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#porcentaje_ganancia_minima").val(0);
            //$("#producto_nuevo_precio_minimo").val(0);
            return;
        }
        
        if(isNaN(producto_nuevo_precio_minimo) || producto_nuevo_precio_minimo == '' || typeof producto_nuevo_precio_minimo === undefined){
            producto_nuevo_precio_minimo = 0;
            $("#porcentaje_ganancia_minima").val(0);
            return;
        }

        if(producto_nuevo_precio_minimo <= 0) {
            $("#porcentaje_ganancia_minima").val(0);
            return;
        }

        if(precio_compra <= 0) {
            $("#porcentaje_ganancia_minima").val(0);
            return;
        }

        var porcentaje_ganancia_minima = round_math(parseFloat((producto_nuevo_precio_minimo*100)/precio_compra - 100),4);
        if(porcentaje_ganancia_minima <= 0) {
            $("#porcentaje_ganancia_minima").val(0);
        } else {
            $("#porcentaje_ganancia_minima").val(porcentaje_ganancia_minima);
        }
    });


    $('#opcion_actualizar_pventa').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $(".content_actualizacion_precios_venta").show("slow");

        } else {
            $(".content_actualizacion_precios_venta").hide("slow");
        }
    });

    $('#producto_unidadmedida').on('change', function() {
        var id_unidad_medida = $('#producto_unidadmedida').val();
        if(id_unidad_medida == 20) {
            $("#content_opcion_actualizar_pventa").hide('slow');
            $('#opcion_actualizar_pventa').bootstrapSwitch('state', false);
        } else {
            $("#content_opcion_actualizar_pventa").show('slow');
        }
    });
    
    $("#select_sucursal").on('change', get_info_sucursal_cpe);

    /*codigo para guardar los datos de sunat*/
    var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
	});
	
	var btn_opciones_accesos_sunat = document.querySelector('.btn_opciones_accesos_sunat');
    btn_opciones_accesos_sunat.onchange = function() {
        if(btn_opciones_accesos_sunat.checked) {
            $(".opc_datos_operaciones_sunat").show("slide");
        } else {
            $(".opc_datos_operaciones_sunat").hide("slide");
        }
	};

    $("#btn_guardar_datos_acceso").click(guardar_datos_acceso_sunat);
    //FIN
});

function guardar_datos_acceso_sunat(){
	var light = $("#configuracion_data_sol");

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
	
	$.ajax({
        url : "/facturacionv8/sire/guardar_data_acceso_sunat",
		method :  'POST',
		data: {
            usuario_sol_principal: $("#usuario_sol_principal").val(),
            password_usuario_sol: $("#password_usuario_sol").val(),
            cliente_id: '',
            client_secret: '',
        },
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

function calcular_nuevos_precios_de_venta() {
    var precio_compra = parseFloat($("#producto_preciounidad").val());
    var porcentaje_ganancia_maxima = parseFloat($("#porcentaje_ganancia_maxima").val());
    var porcentaje_ganancia_minima = parseFloat($("#porcentaje_ganancia_minima").val());

    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
        precio_compra = 0;
    }

    $("#base_porcentaje_ganancia_maxima").html(precio_compra);
    $("#base_porcentaje_ganancia_minima").html(precio_compra);

    if(precio_compra <= 0) {
        //aquí el porcentaje no debería cambiar ya que puede estar cambiando el costo de compra solamente.
        $("#producto_nuevo_precio_venta").val(0);
        $("#producto_nuevo_precio_minimo").val(0);
        return;
    }

    if(isNaN(porcentaje_ganancia_maxima) || porcentaje_ganancia_maxima == '' || typeof porcentaje_ganancia_maxima === undefined){
        porcentaje_ganancia_maxima = 0;
    }

    if(isNaN(porcentaje_ganancia_minima) || porcentaje_ganancia_minima == '' || typeof porcentaje_ganancia_minima === undefined){
        porcentaje_ganancia_minima = 0;
    }

    var producto_nuevo_precio_venta =  round_math(parseFloat((1 + porcentaje_ganancia_maxima/100)*precio_compra), numero_decimales);
    var producto_nuevo_precio_minimo =  round_math(parseFloat((1 + porcentaje_ganancia_minima/100)*precio_compra), numero_decimales);

    $("#producto_nuevo_precio_venta").val(producto_nuevo_precio_venta);
    $("#producto_nuevo_precio_minimo").val(producto_nuevo_precio_minimo);
}

function inicializar_autocompletado() {
    $('#ruc_emisor_apicpe').flexdatalist({
        minLength: 0,
        selectionRequired: false,
        visibleProperties: ["texto"],
        searchContain: false, 
        searchDisabled: false,
        focusFirstResult: true,
        searchIn: 'razon_social',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        data: '/facturacionv8/gestiondecompras/get_top_proveedores'
    }).on("select:flexdatalist", function(event, data) {
        $("#ruc_emisor_apicpe").val(data.num_doc);
    });

    $('#proveedor_nombre').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc"],
        searchContain: true, 
        searchIn: 'razon_social',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/herramientas/get_sugerencias_proveedor'
    }).on("select:flexdatalist", function(event, data) {
        $("#proveedor_numerodocumento").val(data.num_doc);
        $(".search_document").trigger("click");
    });

    $('#proveedor_numerodocumento').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc"],
        searchContain: true,
        searchIn: 'num_doc',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/herramientas/get_sugerencias_proveedor'
    }).on("select:flexdatalist", function(event, data) {
        $(".search_document").trigger("click");
    });

    $('#proveedor_numerodocumento-flexdatalist').prop('type', 'number');
    $('#proveedor_numerodocumento-flexdatalist').prop('inputmode', 'numeric');
    $('#proveedor_numerodocumento-flexdatalist').prop('pattern', '[0-9]*');

    $('#proveedor_numerodocumento-flexdatalist').keyup(function(e){
        if(e.keyCode == 13) {
            $(".search_document").trigger("click");
        }
    });
}

function reset_form_registro_new_producto() {
    $("#nuevo_producto_codigo").val('');
    $("#nuevo_producto_nombre_servicio").val('');
    $("#nuevo_producto_valor_con_igv").val(0);
    $("#nuevo_producto_valor_sin_igv").val(0);
    $("#nuevo_producto_stock").val(0);
    $("#nuevo_producto_stock_minimo").val(0);
    $("#nuevo_producto_precio_compra").val(0);
}

function inicializar_controles() {

    // Initialize multiple switches
    var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });

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
    
    $(".control_fecha").daterangepicker({
        singleDatePicker: true, 
        locale: { format: 'DD/MM/YYYY' }
    });
}

function calcular_precio_sin_igv() {
    var igv_percent = parseFloat((18/100) + 1);
    if($('#producto_preciounidad').val() == '' || $('#producto_preciounidad').val() <= 0 || isNaN($('#producto_preciounidad').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = round_math(parseFloat($('#producto_preciounidad').val()), 2);
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), 2);
    $("#producto_preciounidad_sin_igv").val(precio_sin_igv);
}

function calcular_precio_con_igv() {
    var igv_percent = 1.18;
    if($('#producto_preciounidad_sin_igv').val() == '' || $('#producto_preciounidad_sin_igv').val() <= 0 || isNaN($('#producto_preciounidad_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = round_math(parseFloat($('#producto_preciounidad_sin_igv').val()), 2);
    }
    
    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), 2);
    $("#producto_preciounidad").val(precio_sin_igv);
}

function calcular_totales_producto() {
    var igv_percent = parseFloat((18/100) + 1);
    var precioarticulo = 0;
    var cantidad = 0;

    if($('#producto_preciounidad').val() == '' || $('#producto_preciounidad').val() <= 0 || isNaN($('#producto_preciounidad').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#producto_preciounidad').val());
    }

    if($('#producto_cantidad').val() == '' || $('#producto_cantidad').val() <= 0 || isNaN($('#producto_cantidad').val())) {
        cantidad = 0;
    } else {
        cantidad = parseFloat($('#producto_cantidad').val());
    }

    var total = round_math(parseFloat(precioarticulo) * parseFloat(cantidad), 2);
    var subtotal = round_math(parseFloat(total) / parseFloat(igv_percent), 2);
    var igv = parseFloat(total) - parseFloat(subtotal);

    var id_tipoafectacionigv = parseInt($('#producto_tipo_afect_igv').val()) + 0;
    if(id_tipoafectacionigv != 10) {
        //$("#producto_preciounidad_sin_igv").val($('#producto_preciounidad').val());
        subtotal = total;
        igv = 0;
        $("#text_precio_inc_igv").html('Costo Unitario');
        $("#content_preciounidad_inc_igv").removeClass();
        $("#content_preciounidad_sin_igv").hide();

        $("#content_preciounidad_inc_igv").attr('class', 'col-md-8 col-xs-6 mt-10');
    } else {
        $("#text_precio_inc_igv").html('Costo/Uni(Inc.IGV)');
        $("#content_preciounidad_inc_igv").removeClass();
        $("#content_preciounidad_sin_igv").show();

        $("#content_preciounidad_inc_igv").attr('class', 'col-md-4 col-xs-6 mt-10');
    }
    
    $('#producto_subtotal').val(round_math(subtotal, 2));
    $('#producto_igv').val(round_math(igv, 2));
    $('#producto_total').val(round_math(total, 2));
    
    let total_documento = $("#txt_total_comprobante").val();
    let id_row_selected = parseFloat($("#key_row").val());

    if(id_row_selected > 0) {
        let monto_a_eliminar = get_total_row_en_lista(id_row_selected);
        $("#total_documento_2").html( round_math(parseFloat(total) + parseFloat(total_documento) - parseFloat(monto_a_eliminar), 2));
    } else {
        $("#total_documento_2").html( round_math(parseFloat(total) + parseFloat(total_documento), 2));
    }
}

function calcular_totales_documento() {
    var factor_igv = 0.18;
    var totales_items = get_totales_items(factor_igv);
    actualizar_campos_resumen(totales_items);
}

function get_totales_items(factor_igv) {
    var grid = jQuery("#detalle_documento");
    var ids = grid.jqGrid('getDataIDs');
    
    //totales que si incluye el igv, suma total de importes por tipo
    var sub_total_ventas = 0;
    var total_gravado = 0;
    var total_exonerado = 0;
    var total_inafecto = 0;
    var total_gratuito = 0;
    var total_exportacion = 0;
    var total_icbper = 0;
    
    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        //var importe_item = parseFloat(grid.jqGrid('getCell', id, 'importe'));

        //se utiliza esta forma de calcular nuevamente el importe para no perder decimales en las sumas
        var cantidad_item = parseFloat(grid.jqGrid('getCell', id, 'cantidad'));
        var precio_item = parseFloat(grid.jqGrid('getCell', id, 'precio'));
        var importe_item = cantidad_item*precio_item;
        // fin nuevo cálculo del importe
        
        var subtotal_icbper = parseFloat(grid.jqGrid('getCell', id, 'subtotal_icbper'));
        var id_tipoafectacionigv = parseInt(grid.jqGrid('getCell', id, 'id_tipoafectacionigv')) + 0;
        var afecto_icbper = grid.jqGrid('getCell', id, 'afecto_icbper');

        if(id_tipoafectacionigv == 10) {
            total_gravado = total_gravado + importe_item;
            sub_total_ventas = sub_total_ventas + importe_item;
        } else if(id_tipoafectacionigv == 30) {
            total_inafecto = total_inafecto + importe_item;
            sub_total_ventas = sub_total_ventas + importe_item;
        } else if (id_tipoafectacionigv == 40) {
            total_exportacion = total_exportacion + importe_item;
            sub_total_ventas = sub_total_ventas + importe_item;
        } else if(id_tipoafectacionigv == 20) {
            total_exonerado = total_exonerado + importe_item;
            sub_total_ventas = sub_total_ventas + importe_item;
        } else {
            total_gratuito = total_gratuito + importe_item;
        }

        if(afecto_icbper == 'si') {
            total_icbper = total_icbper + subtotal_icbper;
        }
    }
    
    var descuento = get_descuento(sub_total_ventas);
    var descuento_factor = descuento.descuento_factor;
    var desc_imponible_gravado = 0;
    var desc_imponible_exonerado = 0;
    var desc_imponible_inafecto = 0;
    var desc_imponible_gratuito = 0;
    var desc_imposible_exportacion = 0;
    var desc_total_imponible = 0;
    var total_igv = 0;
    var total_a_pagar = 0;
    
    var nuevo_total_gravado_sin_igv = total_gravado/(factor_igv + 1);
    
    if(descuento_factor > 0) {
        desc_imponible_gravado = nuevo_total_gravado_sin_igv*descuento_factor; //total descuento sin igv
        desc_imponible_exonerado = total_exonerado*descuento_factor;
        desc_imponible_inafecto = total_inafecto*descuento_factor;
        desc_imposible_exportacion = total_exportacion*descuento_factor;
        desc_total_imponible = desc_imponible_gravado + desc_imponible_exonerado + desc_imponible_inafecto + desc_imposible_exportacion;
    }
    
    total_gravado = nuevo_total_gravado_sin_igv - desc_imponible_gravado;
    total_exonerado = total_exonerado - desc_imponible_exonerado;
    total_inafecto = total_inafecto - desc_imponible_inafecto;
    total_exportacion = total_exportacion - desc_imposible_exportacion;

    var otros_cargos = parseFloat($("#txt_otros_cargos_comprobante_input").val()) + 0;

    total_igv = total_gravado*factor_igv;
    total_a_pagar = total_gravado + total_exonerado + total_inafecto + total_exportacion + total_igv + otros_cargos + total_icbper;;
    
    return {
        total_gravado: round_math(total_gravado, 2),
        total_exonerado: round_math(total_exonerado, 2),
        total_inafecto: round_math(total_inafecto, 2),
        total_gratuito: round_math(total_gratuito, 2),
        total_exportacion: round_math(total_exportacion, 2),
        total_icbper: round_math(total_icbper, 2),
        sub_total_ventas: round_math(sub_total_ventas, 2),
        total_igv:  round_math(total_igv, 2),
        total_a_pagar: round_math(total_a_pagar, 2),
        desc_total_imponible: round_math(desc_total_imponible, 2),
        desc_total_monto: round_math(descuento.descuento_monto, 2),
        otros_cargos: round_math(otros_cargos, 2)
    };
}

function get_descuento(sub_total_ventas) {
    var descuento_factor = 0;
    var descuento_monto = 0;

    if($('#opcion_tipo_descuento').is(':checked')) {
        if($('#txt_descuento_porcentaje').val() == '' || $('#txt_descuento_porcentaje').val() <= 0 || isNaN($('#txt_descuento_porcentaje').val())) {
            descuento_factor = 0;
            descuento_total = 0;
        } else {
            descuento_factor = parseFloat($('#txt_descuento_porcentaje').val())/100;
            descuento_monto = round_math(sub_total_ventas*descuento_factor, 2);
            $('#txt_descuento_total').val(descuento_monto);
        }
    } else {
        if($('#txt_descuento_total').val() == '' || $('#txt_descuento_total').val() <= 0 || isNaN($('#txt_descuento_total').val())) {
            descuento_factor = 0;
            descuento_monto = 0;
        } else {
            descuento_monto = parseFloat($('#txt_descuento_total').val());
            descuento_factor = descuento_monto/sub_total_ventas;
            $('#txt_descuento_porcentaje').val(round_math(descuento_factor*100, 2));
        }
    }

    return {
        descuento_factor: descuento_factor,
        descuento_monto: descuento_monto
    }
}

function actualizar_campos_resumen(totales) {
    console.log(totales);
    $("#exportacion_documento").html(round_math(totales.total_exportacion, 2));
    $("#txt_exportacion_comprobante").val(round_math(totales.total_exportacion, 2));

    $("#exonerada_documento").html(round_math(totales.total_exonerado, 2));
    $("#txt_exonerada_comprobante").val(round_math(totales.total_exonerado, 2));

    $("#inafecta_documento").html(round_math(totales.total_inafecto, 2));
    $("#txt_inafecta_comprobante").val(round_math(totales.total_inafecto, 2));

    $("#gravada_documento").html(round_math(totales.total_gravado, 2));
    $("#txt_gravada_comprobante").val(round_math(totales.total_gravado, 2));

    $("#igv_documento").html(round_math(totales.total_igv, 2));
    $("#txt_igv_comprobante").val(round_math(totales.total_igv, 2));

    $("#gratuita_documento").html(round_math(totales.total_gratuito, 2));
    $("#txt_gratuita_comprobante").val(round_math(totales.total_gratuito, 2));

    $("#icbper_documento").html(round_math(totales.total_icbper, 2));
    $("#txt_icbper_comprobante").val(round_math(totales.total_icbper, 2));

    $("#txt_descuento_comprobante").val(totales.desc_total_imponible);
    $("#descuento_documento").html(totales.desc_total_imponible);

    $("#txt_otros_cargos_comprobante").val(totales.otros_cargos);
    $("#otros_cargos_documento").html(totales.otros_cargos);

    $("#total_documento").html(round_math(totales.total_a_pagar, 2));
    $("#txt_monto_adeudado").val(round_math(totales.total_a_pagar, 2));
    $("#total_documento_2").html(round_math(totales.total_a_pagar, 2));
    $("#txt_total_comprobante").val(round_math(totales.total_a_pagar, 2));
    $('#tipo_percepcion').trigger("change");
    $("#detraccion_codigo_bien").trigger("change");

    $("#txt_sub_total_ventas").val(totales.sub_total_ventas);
    $("#sub_total_ventas").html(totales.sub_total_ventas);

    var numletras = NumeroALetras(totales.total_a_pagar);
    $("#txt_total_letras").val(numletras);
    
    if(totales.total_exportacion > 0) {
        $("#row_exportacion_documento").show("slow");
    } else {
        $("#row_exportacion_documento").hide("slow");
    }

    if(totales.total_exonerado > 0) {
        $("#row_exonerada_documento").show("slow");
    } else {
        $("#row_exonerada_documento").hide("slow");
    }
    
    if(totales.total_inafecto > 0) {
        $("#row_inafecta_documento").show("slow");
    } else {
        $("#row_inafecta_documento").hide("slow");
    }

    if(totales.total_gravado > 0) {
        $("#row_gravada_documento").show("slow");
    } else {
        $("#row_gravada_documento").hide("slow");
    }

    if(totales.total_gratuito > 0) {
        $("#row_gratuita_documento").show("slow");
    } else {
        $("#row_gratuita_documento").hide("slow");
    }

    if(totales.total_icbper > 0) {
        $("#row_icbper_documento").show("slow");
    } else {
        $("#row_icbper_documento").hide("slow");
    }

    if(totales.total_a_pagar != totales.sub_total_ventas) {
        $("#row_sub_total_ventas").show('slow');
    } else {
        $("#row_sub_total_ventas").hide('slow');
    }

    if(totales.otros_cargos > 0) {
        $("#row_otros_cargos_documento").show("slow");
        $("#input_otros_cargos").show("slow");
    } else {
        $("#row_otros_cargos_documento").hide("slow");
        $("#input_otros_cargos").hide("slow");
    }
}

function get_data_producto() {

    var light = $("#frm_producto");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Buscando Información! ...</p>',
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
            $("#producto_unidadmedida").val(data.producto.id_unidad_medida);
            $("#producto_tipo_afect_igv").val(data.producto.id_tipoafectacionigv).trigger("change").trigger("select2:select");
            $("#producto_descripcion").val(data.producto.nombre);

            var row_identificadador = $("#key_row").val();
            if(row_identificadador.includes('CPE_CONSULTA')) {

            } else {
                
                var precio_compra = parseFloat(data.producto.precio_compra);

                $("#producto_preciounidad").val(precio_compra);

                var tipo_moneda = $("#codmoneda_comprobante").val();
                if(data.producto.id_tipoafectacionigv == "10") {
                    var valor_con_igv = round_math(parseFloat(precio_compra), 2);
                    var valor_sin_igv = round_math(parseFloat(precio_compra/1.18), 2);
                } else {
                    var valor_con_igv = round_math(parseFloat(precio_compra*1.18), 2);
                    var valor_sin_igv = round_math(parseFloat(precio_compra), 2);
                }
                
                var igv = round_math(parseFloat(valor_con_igv - valor_sin_igv), 2);
    
                var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
    
                if(tipo_moneda == 'USD') {
                    if(data.producto.id_cod_moneda == 'PEN') {
                        valor_con_igv = round_math(parseFloat(valor_con_igv/tipo_cambio), 2);
                        valor_sin_igv = round_math(parseFloat(valor_sin_igv/tipo_cambio), 2);
                    }
                }
    
                if(tipo_moneda == 'PEN') {
                    if(data.producto.id_cod_moneda == 'USD') {
                        valor_con_igv = round_math(parseFloat(valor_con_igv*tipo_cambio), 2);
                        valor_sin_igv = round_math(parseFloat(valor_sin_igv*tipo_cambio), 2);
                    }
                }
    
                if(data.producto.id_tipoafectacionigv == "10") {
                    $("#producto_preciounidad").val(valor_con_igv);
                    $("#producto_preciounidad_sin_igv").val(valor_sin_igv);
                } else {
                    $("#producto_preciounidad").val(valor_sin_igv);
                    //$("#producto_preciounidad_sin_igv").val(data.producto.valor_sin_igv);
                }
    
                if(data.producto.afecto_icbper == 'si') {
                    $('#opcion_afecto_icbper').bootstrapSwitch('state', true);
                } else {
                    $('#opcion_afecto_icbper').bootstrapSwitch('state', false);
                }
                
                $("#id_cod_moneda").val(tipo_moneda);
                //$("#producto_preciounidad").val(valor_con_igv);
                //$("#producto_cantidad").focus().val(1).select();
                $("#producto_preciounidad").focus().select();
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

                if(presentacion_seleccionar) {
                    /*
                    var id_tipoafectacionigv = parseInt(data.producto.id_tipoafectacionigv) + 0;
                    if(id_tipoafectacionigv == 10) {
                        asignar_precio_de_lista(presentacion.precio_con_igv, presentacion.id_cod_moneda);
                    } else {
                        asignar_precio_de_lista(presentacion.precio_sin_igv, presentacion.id_cod_moneda);
                    }
                    */
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
                        "data-precioconigv": presentacion.precio_con_igv,
                        "data-preciosinigv": presentacion.precio_sin_igv,
                        "data-idcodmoneda" : presentacion.id_cod_moneda
                    })
                );
            });

            $("#producto_unidadmedida").trigger("change").trigger("select2:select"); //necesario para que agarre el precio del producto seleccionado

            $(light).unblock();
            $(".content_propiedades_producto").show('slide');
            calcular_totales_producto();//$(".totales_input").trigger("input");


            //INICIO CAMBIO DE PRECIO DE VENTA
            if(data.producto.id_unidad_medida == 20) {
                $("#content_opcion_actualizar_pventa").hide('slow');
                $('#opcion_actualizar_pventa').bootstrapSwitch('state', false);
            } else {
                $("#content_opcion_actualizar_pventa").show('slow');
            }



            $("#msg_cambio_precio_venta").html("");
            $("#msg_cambio_precio_venta").hide();
            $("#prod_tiene_presentaciones").val('no');
            $("#prod_tiene_multiprecio").val('no');
            $('#opcion_actualizar_pventa').bootstrapSwitch('state', false);

            /*
            if(data.producto.id_cod_moneda != $("#codmoneda_comprobante").val()) {
                $("#content_opcion_actualizar_pventa").hide();
            } else {
                $("#content_opcion_actualizar_pventa").show('slow');
            }
            */
            
            if (data.lista_precios.length === 0 && data.presentaciones.length === 0) { 
                $("#msg_cambio_precio_venta").html("");
                $("#msg_cambio_precio_venta").hide();
            } else if(data.lista_precios.length > 0 && data.presentaciones.length === 0) {
                $("#msg_cambio_precio_venta").html("Verificamos que el producto tiene una lista de precios registrada... Recomendamos hacer una actualización de precios manual, ya que con esta opción los montos de su lista de precios no se actualizará.");
                
                $("#msg_cambio_precio_venta").show();
                $("#prod_tiene_presentaciones").val('si');
            } else if(data.lista_precios.length === 0 && data.presentaciones.length > 0) {
                $("#msg_cambio_precio_venta").html("Verificamos que el producto tiene presentaciones registrados... Recomendamos hacer una actualización de precios manual, ya que con esta opción los montos de sus presentaciones no se actualizarán.");
                
                $("#msg_cambio_precio_venta").show();
                $("#prod_tiene_multiprecio").val('si');
            } else if(data.lista_precios.length > 0 && data.presentaciones.length > 0) {
                $("#msg_cambio_precio_venta").html("Verificamos que el producto tiene una lista de precios y presentaciones registrados... Recomendamos hacer una actualización de precios manual, ya que con esta opción los montos de su lista de precios y presentaciones no se actualizará.");
                
                $("#msg_cambio_precio_venta").show();
                $("#prod_tiene_presentaciones").val('si');
                $("#prod_tiene_multiprecio").val('si');
            }

            var precio_venta_con_igv = parseFloat(data.producto.valor_con_igv)
            var precio_venta_sin_igv = parseFloat(data.producto.valor_sin_igv)
            var precio_venta = 0;
            var precio_venta_minimo = 0;

            if(data.producto.id_tipoafectacionigv == "10") {
                $("#producto_precio_venta_actual").val(precio_venta_con_igv);
                precio_venta = precio_venta_con_igv;
            } else {
                $("#producto_precio_venta_actual").val(precio_venta_sin_igv);
                precio_venta = precio_venta_sin_igv;
            }

            precio_venta_minimo = parseFloat(data.producto.precio_venta_minimo);

            var porcentaje_ganancia_maximo = parseFloat(data.porcentajes_ganancia.porcentaje_maximo);
            var porcentaje_ganancia_minimo = parseFloat(data.porcentajes_ganancia.porcentaje_minimo);

            $("#producto_precio_venta_minimo").val(precio_venta_minimo);
            $("#porcentaje_ganancia_maxima").val(porcentaje_ganancia_maximo);
            $("#porcentaje_ganancia_minima").val(porcentaje_ganancia_minimo);

            if(porcentaje_ganancia_maximo == 0) {
                $("#producto_nuevo_precio_venta").val(0);
            } else {
                var producto_nuevo_precio_venta = round_math(parseFloat(precio_compra*(1+porcentaje_ganancia_maximo/100)), 4);
                $("#producto_nuevo_precio_venta").val(producto_nuevo_precio_venta);
            }

            if(porcentaje_ganancia_minimo == 0) {
                $("#producto_nuevo_precio_minimo").val(0);
            } else {
                var producto_nuevo_precio_minimo = round_math(parseFloat(precio_compra*(1+porcentaje_ganancia_minimo/100)), 4);
                $("#producto_nuevo_precio_minimo").val(producto_nuevo_precio_minimo);
            }

            $("#base_porcentaje_ganancia_maxima").html(precio_compra);
            $("#base_porcentaje_ganancia_minima").html(precio_compra);

            $("#pventa_moneda_original").val(data.producto.id_cod_moneda);
            //FIN CAMBIO PRECIO VENTA
                
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

function inicializar_buscador_anterior() {

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

function inicializar_buscador() {
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
    
    $container.find(".select2-result-repository__title").text(data.text);
    $container.find(".select2-result-repository__description").text('Código: ' + data.codigo);
    $container.find(".select2-result-repository__forks").append('Stock: ' + data.stock + ' ' + data.unidad);
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

function inicializar_tabla_detalle() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	detalle_documento = $('#detalle_documento');

	detalle_documento = detalle_documento.jqGrid({
        url: '',
        datatype: 'json',
        mtype: 'POST',
        colNames: [
            'M/P',
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

            /*
            'html_lista_precios',
            'item_detraccion_codigo',
            'item_detraccion_porcentaje',
            */
           
            'select_unidadmedida',
            'tipo_unidad', //presentacion, unidad
            'IdPresentacion',

            //data válida para el cambio del previo de venta al momento de guardar la compra.
            'pventa_moneda',

            'pventa_actual',
            'porcentaje_pventa',
            'pventa_nuevo',

            'pventa_minimo_actual',
            'porcentaje_pventa_minimo',
            'pventa_minimo_nuevo',

            'tiene_presentaciones',
            'tiene_multiprecio'
            //fin data cambio precio venta producto
        ],
        colModel: [
            { name: 'modificar_precio_venta', index: '1', width: 40, align: "left"},
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'idarticulo', index: '1', hidden: true},
            { name: 'afecto_icbper', index: '1', hidden: true},
            { name: 'subtotal_icbper', index: '1', hidden: true},
            { name: 'id_tipoafectacionigv', index: '1', hidden: true},
            { name: 'descripcion', index: '2', width: 360, },
            { name: 'text_tipo_afecigv', index: '3', width: 120, },
            { name: 'idunidadmedida', index: '4', hidden: true },
            { name: 'unidadmedida', index: '5', width: 120 },
            { name: 'precio', index: '6', width: 80, align: "right", sorttype: 'float' },
            { name: 'id_cod_moneda', index: '6', hidden: true},
            { name: 'cantidad', index: '6', width: 80, align: "right", sorttype: 'float' },
            { name: 'subtotal', index: '6', width: 85, align: "right", sorttype: 'float' },
            { name: 'igv', index: '6', width: 60, align: "right", sorttype: 'float' },
            { name: 'importe', index: '6', width: 90, align: "right", sorttype: 'float' },
            { name: 'estado', index: '8', hidden: true },
            { name: 'codigo', index: '1', hidden: true},

            /*
            { name: 'html_lista_precios', index: '15', hidden: true},
            { name: 'item_detraccion_codigo', index: '15', hidden: true},
            { name: 'item_detraccion_porcentaje', index: '15', hidden: true},
            */

            { name: 'select_unidadmedida', index: '15', hidden: true},
            { name: 'tipo_unidad', index: '16', hidden: true},
            { name: 'idpresentacion', index: '17', hidden: true},
            

            { name: 'pventa_moneda', index: '18', hidden: true},
            { name: 'pventa_actual', index: '19', hidden: true},
            { name: 'porcentaje_pventa', index: '20', hidden: true},
            { name: 'pventa_nuevo', index: '21', hidden: true},
            { name: 'pventa_minimo_actual', index: '22', hidden: true},
            { name: 'porcentaje_pventa_minimo', index: '23', hidden: true},
            { name: 'pventa_minimo_nuevo', index: '24', hidden: true},
            { name: 'tiene_presentaciones', index: '25', hidden: true},
            { name: 'tiene_multiprecio', index: '26', hidden: true}
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
            
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            //SE EJECUTA CUANDO MODIFICAMOS UN REGISTRO
            //                        calculateImporte();
            //                        calculateTotal();
        },
        rowattr: function (item) {
            if(parseFloat(item.idarticulo) > 0) {
                return { "class": "fila_enparejada" };
            } else {
                return { "class": "fila_sin_enparejar" };
            }
            
        },
        ondblClickRow: function(rowid) {
            console.log("si llega aquí=> ");
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
                btn_productos_accion = 'editar';
                $("#vm_agregar_articulo").modal("show");
                $(".content_propiedades_producto").show('slide');
            }
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

function consultar_numero_doc_proveedor(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email) {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);
    input_idusuario.val('');
    
$.ajax({
        url : '/facturacionv8/herramientas/get_data_proveedor',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            input_ubigeo.val(data.data.codigo_ubigeo).trigger("change").trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }

                    if(typeof data.data.estado !== 'undefined' && data.data.estado != '') {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                        
                        if(data.data.estado == 'ACTIVO' || data.data.estado == 'activo') {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-success");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-success");
     
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        } else {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-danger");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-danger");
    
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        }
                    } else {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#estado_numerodocumento").addClass("form-group col-md-4");
                        $("#razonsocial_numerodocumento").addClass("form-group col-md-5");
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                    }
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                }
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

function guardar_documento_electronico() {
	var light = $('#cuerpo_comprobante');
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

	var gridData = jQuery("#detalle_documento").getRowData();
	var postData = JSON.stringify(gridData);

	var datastring = $("#frm_datacompra").serializeArray();
	datastring.push({ name: "detalle", value: postData });
    
    $.ajax({
        url : '/facturacionv8/gestiondecompras/guardar_compra',
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			var html_confirm = '<div><strong>Se creará el documento electrónico con los siguientes datos!</strong></div>\
			<br>\
			<div class="resumen-sec resumensecdos" style="width: 100% !important; float: none !important;">\
			' + $("#content_resumen_doc_electronico").html() + '   \
			</div>\
			<br>\
			<div><strong><span class="text-success" style="font-size: 18px;">¿Está Usted de Acuerdo?</span></strong></div>';
			
			swal({   
				title: 'Necesitamos de tu Confirmación',   
				text: html_confirm,
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
					
                    $.ajax({
						url : '/facturacionv8/gestiondecompras/guardar_compra',
						method :  'POST',
						data: datastring,
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
                                if($('input[name="tipo_comprobante"]:checked').val() != '99') {
                                    swal({   
                                        title: data.titulo,   
                                        text: data.mensaje,
                                        html: true,
                                        type: "success",   
                                        confirmButtonColor: "#563d7c",   
                                        cancelButtonColor: "#4CAF50",
                                        confirmButtonText: "Ver Compras >>",
                                        cancelButtonText: "Registrar Otra Compra!",
                                        showCancelButton: true
                                    }, function(isconfirmed){  
                                        if(isconfirmed) {
                                            window.location.href = "/facturacionv8/gestiondecompras/";
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                    $(light).unblock();
                                    return;
                                } else {
                                    swal.close();
                                    $("#titulo_msg_guardado").html('<i class="icon-checkmark-circle position-left"></i> ' + data.titulo);
                                    $("#mensaje_msg_guardado").html(data.mensaje);
                                    var url_pdf_ticket = "";
                                    var url_pdf_a4 = "";
                                    
                                    url_pdf_ticket = data.enlaces.ticket;
                                    url_pdf_a4 = data.enlaces.a4;
                                    
                                    $("#content_pdf_preview_ticket").html('<div class="pdf_preview"><embed src="' + url_pdf_ticket + '" type="application/pdf" width="100%" height="300px" /></div>');
                                    $("#content_pdf_preview_a4").html('<div class="pdf_preview"><embed src="' + url_pdf_a4 + '" type="application/pdf" width="100%" height="300px" /></div>');
                                    $("#enlace_ticket_msg_guardado").attr("href", url_pdf_ticket);
                                    
                                    $("#enlace_whatsapp_msg_guardado").hide();
                                    
                                    $("#enlace_a4_msg_guardado").attr("href", url_pdf_a4);
                                    $("#enlace_guiaremision_msg_guardado").hide();
    
                                    $(light).unblock();
                                    $("#modal_msg_guardado").modal({
                                        backdrop: 'static',
                                        keyboard: false
                                    });
                                    return;
                                }

							} else {
								swal({   
									title: data.titulo,   
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

function leer_xml_cpe() {
    var light = $('#cuerpo_comprobante_xml');
	$(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Buscando Comprobante en SUNAT...</p>',
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

    var formData = new FormData();
    formData.append("xmlFile", $(this)[0].files[0]);

    $.ajax({
        url : '/facturacionv8/gestiondecompras/procesar_xml_cpe',
		method :  'POST',
		data: formData,
        processData: false,
        contentType: false,
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {

            let str_correlativo = data.cpe.comprobante.correlativo;
            str_correlativo = str_correlativo.replace(/^0+/, '');

            $("#proveedor_numerodocumento").val(data.cpe.emisor.ruc);
            $("#ruc_emisor_apicpe-flexdatalist").val(data.cpe.emisor.ruc);
            $("#proveedor_numerodocumento").trigger('input');

            $("#serie_comprobante_apicpe").val(data.cpe.comprobante.serie.toUpperCase());
            $("#correlativo_comprobante_apicpe").val(str_correlativo);

            $("#serie_comprobante").val(data.cpe.comprobante.serie.toUpperCase());
            $("#numero_comprobante").val(str_correlativo);
            $(".tipo_comprobante").val(data.cpe.comprobante.tipo);
            $("#proveedor_tipo_docidentidad").val(6).trigger("change").trigger("select2:select");

			procesar_data_cpe(data.cpe, light, data.unidades_medida);
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
			text: 'Error al subir el archivo',
			html: true,
			type: "error",
			confirmButtonText: "Ok",
			confirmButtonColor: "#2196F3"
		}, function(){
			$(light).unblock();
		});
    });
}

function get_data_cpe() {

	var light = $('#cuerpo_comprobante');
	$(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Buscando Comprobante en SUNAT...</p>',
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
    
    var ruc_emisor = $("#proveedor_numerodocumento").val();
    if($("#ruc_emisor_apicpe-flexdatalist").val() != '') {
        var ruc_emisor = $("#ruc_emisor_apicpe-flexdatalist").val();
        $("#proveedor_numerodocumento").val($("#ruc_emisor_apicpe-flexdatalist").val());
        $("#proveedor_numerodocumento").trigger('input');
    } else {
        var ruc_emisor = $("#proveedor_numerodocumento").val();
    }

    var serie_doc = $("#serie_comprobante").val();
    var correlativo_doc = $("#numero_comprobante").val();
    var id_tipo_cpe = $(".tipo_comprobante").val();
    var id_tipodoc_proveedor = $("#proveedor_tipo_docidentidad").val();

	
    $.ajax({
        url : '/facturacionv8/gestiondecompras/get_data_cpe',
		method :  'POST',
		data: {ruc_emisor: ruc_emisor, serie_doc: serie_doc, correlativo_doc: correlativo_doc, id_tipo_cpe: id_tipo_cpe, id_tipodoc_proveedor: id_tipodoc_proveedor},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			procesar_data_cpe(data.cpe, light, data.unidades_medida);
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

function procesar_data_cpe(cpe, light, unidades_medida) {
    jQuery("#detalle_documento").jqGrid("clearGridData");

    var array_fecha_comprobante = cpe.comprobante.fecha_emision.split('-');
    var fecha_comprobante = array_fecha_comprobante[2] + '/' + array_fecha_comprobante[1] + '/' + array_fecha_comprobante[0];
    $("#fecha_comprobante").val(fecha_comprobante);
    get_tipocambio_by_date($("#tipo_cambio_comprobante"), cpe.comprobante.fecha_emision);

    var id_codigo_moneda = 'PEN';

    if(cpe.comprobante.moneda == 'PEN') {
        $("#codmoneda_comprobante").val('PEN');
        $("#codmoneda_comprobante").trigger("change").trigger("select2:select");

        $("#nuevo_producto_moneda").val('PEN');
        $("#nuevo_producto_moneda").trigger("change").trigger("select2:select");
    } else if(cpe.comprobante.moneda == 'DOLARES' || cpe.comprobante.moneda == 'USD') {
        $("#codmoneda_comprobante").val('USD');
        $("#codmoneda_comprobante").trigger("change").trigger("select2:select");

        $("#nuevo_producto_moneda").val('USD');
        $("#nuevo_producto_moneda").trigger("change").trigger("select2:select");
        id_codigo_moneda = 'USD';
    }

    if(cpe.totales.otros_cargos == null || cpe.totales.otros_cargos == '' || parseFloat(cpe.totales.otros_cargos) <= 0 || isNaN(cpe.totales.otros_cargos)) { 
        $("#txt_otros_cargos_comprobante_input").val(0);
    } else {
        $("#txt_otros_cargos_comprobante_input").val(parseFloat(cpe.totales.otros_cargos));
    }
    
    var opciones_select = [];
    
    $.each(unidades_medida, function(key, unidad_medida) {
        var opcion_select = {};
        opcion_select = {
            val                 : 'UND-' + unidad_medida.idunidad,
            text                : unidad_medida.nombre,

            tipo                : 'unidad',
            idpresentacion      : '',
            codigo              : '',
            nombrepresentacion  : '',
            idproducto          : '',
            idunidadpresentacion: '',
            idunidadbase        : unidad_medida.idunidad,
            nombreunidadpresentacion: '',
            cantidad            : '',
            nombreunidadbase    : unidad_medida.nombre,
            precioconigv        : '',
            preciosinigv        : '',
            idcodmoneda         : ''
        };

        opciones_select.push(opcion_select);
    });

    var json_select_unidadmedida = JSON.stringify(opciones_select);
    var tipo_unidad = 'unidad';
    var idpresentacion = '';
    
    var nro_item = 0;
    $.each( cpe.items, function( index, item ){
        nro_item++;
        var idarticulo = 0;
        var igv_percent = parseFloat((18/100) + 1);
        var precioarticulo = parseFloat(item.precio_con_igv);
        var cantidad = parseFloat(item.cantidad);
        var id_tipoafect_validos = [10, 11, 12, 13, 14, 15, 16, 20, 30, 31, 32, 33, 34, 35, 36, 40];
        var id_tipoafectacionigv = 20;
        var text_tipoafectacionigv = 'Exonerado';
        if(checkValue(parseInt(item.id_afectacion), id_tipoafect_validos)) {
            id_tipoafectacionigv = item.id_afectacion;
            
            if(id_tipoafectacionigv == 10) {text_tipoafectacionigv = 'Gravado'}
            if(checkValue(parseInt(item.id_afectacion), [11, 12, 13, 14, 15, 16])) {text_tipoafectacionigv = 'Gratuito'}
            if(checkValue(parseInt(item.id_afectacion), [30, 31, 32, 33, 34, 35, 36])) {text_tipoafectacionigv = 'Inafecto'}
            if(id_tipoafectacionigv == 40) {text_tipoafectacionigv = 'Exportacion'}
        } else {
            id_tipoafectacionigv = 20;
        }

        /* Calculando Totales */
        var total = round_math(parseFloat(precioarticulo) * parseFloat(cantidad), 2);
        var subtotal = round_math(parseFloat(total) / parseFloat(igv_percent), 2);
        var igv = round_math(parseFloat(total) - parseFloat(subtotal), 2);

        if(id_tipoafectacionigv != 10) {
            subtotal = total;
            igv = 0;
        } else {

        }
        
        var codigo = 'CPE_CONSULTA_'+nro_item;

        var n = Math.floor(Math.random() * 11);
        var k = Math.floor(Math.random() * 1000000);
        var m = String.fromCharCode(n) + k;
        
        var row_identificadador = 'CPE_CONSULTA ' + m + '|.|.|'+codigo+'|.|.|'+precioarticulo+'|.|.|'+nro_item;

        var afecto_icbper = 'no';
        var monto_impuesto_icbper = 0.2;

        if (typeof impuesto_icbper !== 'undefined') {
            if(impuesto_icbper > 0) {
                monto_impuesto_icbper = impuesto_icbper;
            }
        }

        var text_unidad_medida = 'UNIDAD';
        var id_unidad_medida = 'UND-' + 7;
        if(item.und_med != 'ZZ') {
            var resp_id_text_und = get_id_unidad_medida(item.und_med);
            text_unidad_medida = resp_id_text_und.text;
            id_unidad_medida = 'UND-' + resp_id_text_und.value;
        } else {
            text_unidad_medida = 'SERVICIO';
            id_unidad_medida = 'UND-' + 20;
        }

        afecto_icbper = 'no';
        subtotal_icbper = 0;

        var data = {
            row_identificadador: row_identificadador,
            idarticulo: idarticulo,
            afecto_icbper: afecto_icbper,
            subtotal_icbper: subtotal_icbper,
            id_tipoafectacionigv: id_tipoafectacionigv,
            descripcion: item.descripcion,
            text_tipo_afecigv: text_tipoafectacionigv,
            idunidadmedida: id_unidad_medida,
            unidadmedida: text_unidad_medida, //$('#producto_unidadmedida').val(),
            precio: precioarticulo,
            id_cod_moneda: id_codigo_moneda, //$("#id_cod_moneda").val(),
            cantidad: parseFloat(cantidad),
            subtotal: parseFloat(subtotal),
            igv: parseFloat(igv),
            importe: parseFloat(total),
            estado: 'V',
            codigo: codigo,

            select_unidadmedida: json_select_unidadmedida,
            tipo_unidad: tipo_unidad,
            idpresentacion: idpresentacion,

            //data para cambio de precio de venta
            pventa_moneda: id_codigo_moneda,
            pventa_actual: 0,
            porcentaje_pventa: 0,
            pventa_nuevo: 0,
        
            pventa_minimo_actual: 0,
            porcentaje_pventa_minimo: 0,
            pventa_minimo_nuevo: 0,
        
            tiene_presentaciones: 'no',
            tiene_multiprecio: 'no'
            //fin data para cambio de precio de venta
        };

        var su = jQuery('#detalle_documento').addRowData(row_identificadador, data, 'last');
        calcular_totales_documento();
    });

    $(".search_document").trigger("click");
    $(".data_sunat_cpe").hide('slide');
    $(".data_manual_cpe").show('slide');
    
    swal({
        title: 'IMPORTANTE',
        text: '\
            <div class="alert alert-info alert-styled-left alert-bordered">\
                La Búsqueda de Comprobante en SUNAT es una funcionalidad en BETA, por favor proceda con cuidado\
            </div>\
            <div class="alert alert-warning alert-styled-left">\
                <span class="text-semibold">Si usted desea que los STOCKS aumenten</span>, debe emparejar cada item, con cada producto de su almacén\
            </div>\
        ',
        html: true,
        type: "success",
        confirmButtonText: "Ok",
        confirmButtonColor: "#2196F3"
    }, function(){
        
        $(light).unblock();
    });

}

function get_id_unidad_medida(item_und_med) {
    item_und_med = item_und_med.toUpperCase();

    if (item_und_med == "4A") {   //  Nombre: BOBINAS
        return {value: 7, text: 'BOBINAS'};
    } else if (item_und_med == "BE") {   //  Nombre: FARDO
        return {value: 22, text: 'FARDO'};
    } else if (item_und_med == "BG") {   //  Nombre: BOLSA
        return {value: 24, text: 'BOLSA'};
    } else if (item_und_med == "BJ") {   //  Nombre: BALDE
        return {value: 39, text: 'BALDE'};
    } else if (item_und_med == "BLL") {   //  Nombre: BARRILES
        return {value: 10, text: 'BARRILES'};
    } else if (item_und_med == "BO") {   //  Nombre: BOTELLAS
        return {value: 46, text: 'BOTELLAS'};
    } else if (item_und_med == "BX") {   //  Nombre: CAJA
        return {value: 12, text: 'CAJA'};
    } else if (item_und_med == "C62") {   //  Nombre: PIEZAS
        return {value: 45, text: 'PIEZAS'};
    } else if (item_und_med == "CA") {   //  Nombre: LATAS
        return {value: 11, text: 'LATAS'};
    } else if (item_und_med == "CEN") {   //  Nombre: CIENTO DE UNIDADES
        return {value: 17, text: 'CIENTO DE UNIDADES'};
    } else if (item_und_med == "CJ") {   //  Nombre: CONOS
        return {value: 7, text: 'CONOS'};
    } else if (item_und_med == "CMK") {   //  Nombre: CENTIMETRO CUADRADO
        return {value: 7, text: 'CENTIMETRO CUADRADO'};
    } else if (item_und_med == "CMQ") {   //  Nombre: CENTIMETRO CUBICO
        return {value: 7, text: 'CENTIMETRO CUBICO'};
    } else if (item_und_med == "CMT") {   //  Nombre: CENTIMETRO LINEAL
        return {value: 7, text: 'CENTIMETRO LINEAL'};
    } else if (item_und_med == "CT") {   //  Nombre: CARTONES
        return {value: 7, text: 'CARTONES'};
    } else if (item_und_med == "CY") {   //  Nombre: CILINDRO
        return {value: 7, text: 'CILINDRO'};
    } else if (item_und_med == "DR") {   //  Nombre: TAMBOR
        return {value: 7, text: 'TAMBOR'};
    } else if (item_und_med == "DZN") {   //  Nombre: DOCENA
        return {value: 16, text: 'DOCENA'};
    } else if (item_und_med == "DZP") {   //  Nombre: DOCENA POR 10**6
        return {value: 7, text: 'DOCENA POR 10**6'};
    } else if (item_und_med == "FOT") {   //  Nombre: PIES
        return {value: 49, text: 'PIES'};
    } else if (item_und_med == "FTK") {   //  Nombre: PIES CUADRADOS
        return {value: 7, text: 'PIES CUADRADOS'};
    } else if (item_und_med == "FTQ") {   //  Nombre: PIES CUBICOS
        return {value: 7, text: 'PIES CUBICOS'};
    } else if (item_und_med == "GLI") {   //  Nombre: GALON INGLES (4,545956L)
        return {value: 9, text: 'GALON INGLES (4,545956L)'};
    } else if (item_und_med == "GLL") {   //  Nombre: US GALON (3,7843 L)
        return {value: 9, text: 'US GALON (3,7843 L)'};
    } else if (item_und_med == "GRM") {   //  Nombre: GRAMO
        return {value: 6, text: 'GRAMO'};
    } else if (item_und_med == "GRO") {   //  Nombre: GRUESA
        return {value: 52, text: 'GRUESA'};
    } else if (item_und_med == "HLT") {   //  Nombre: HECTOLITRO
        return {value: 7, text: 'HECTOLITRO'};
    } else if (item_und_med == "INH") {   //  Nombre: PULGADAS
        return {value: 7, text: 'PULGADAS'};
    } else if (item_und_med == "KGM") {   //  Nombre: KILOGRAMO
        return {value: 1, text: 'KILOGRAMO'};
    } else if (item_und_med == "KG") {   //  Nombre: KILOGRAMO
        return {value: 1, text: 'KILOGRAMO'};
    } else if (item_und_med == "KT") {   //  Nombre: KIT
        return {value: 7, text: 'KIT'};
    } else if (item_und_med == "KTM") {   //  Nombre: KILOMETRO
        return {value: 7, text: 'KILOMETRO'};
    } else if (item_und_med == "KWH") {   //  Nombre: KILOVATIO HORA
        return {value: 7, text: 'KILOVATIO HORA'};
    } else if (item_und_med == "LBR") {   //  Nombre: LIBRAS
        return {value: 2, text: 'LIBRAS'};
    } else if (item_und_med == "LEF") {   //  Nombre: HOJA
        return {value: 7, text: 'HOJA'};
    } else if (item_und_med == "LTN") {   //  Nombre: TONELADA LARGA
        return {value: 3, text: 'TONELADA LARGA'};
    } else if (item_und_med == "LTR") {   //  Nombre: LITRO
        return {value: 8, text: 'LITRO'};
    } else if (item_und_med == "MGM") {   //  Nombre: MILIGRAMOS
        return {value: 7, text: 'MILIGRAMOS'};
    } else if (item_und_med == "MLL") {   //  Nombre: MILLARES
        return {value: 13, text: 'MILLARES'};
    } else if (item_und_med == "MLT") {   //  Nombre: MILILITRO
        return {value: 7, text: 'MILILITRO'};
    } else if (item_und_med == "MMK") {   //  Nombre: MILIMETRO CUADRADO
        return {value: 7, text: 'MILIMETRO CUADRADO'};
    } else if (item_und_med == "MMQ") {   //  Nombre: MILIMETRO CUBICO
        return {value: 7, text: 'MILIMETRO CUBICO'};
    } else if (item_und_med == "MMT") {   //  Nombre: MILIMETRO
        return {value: 7, text: 'MILIMETRO'};
    } else if (item_und_med == "MTK") {   //  Nombre: METRO CUADRADO
        return {value: 47, text: 'METRO CUADRADO'};
    } else if (item_und_med == "MTQ") {   //  Nombre: METRO CUBICO
        return {value: 7, text: 'METRO CUBICO'};
    } else if (item_und_med == "MTR") {   //  Nombre: METRO
        return {value: 7, text: 'METRO'};
    } else if (item_und_med == "MWH") {   //  Nombre: MEGAWATT HORA
        return {value: 7, text: 'MEGAWATT HORA'};
    } else if (item_und_med == "NIU") {   //  Nombre: UNIDAD (BIENES)
        return {value: 7, text: 'UNIDADES'};
    } else if (item_und_med == "ONZ") {   //  Nombre: ONZAS
        return {value: 7, text: 'ONZAS'};
    } else if (item_und_med == "PF") {   //  Nombre: PALETAS
        return {value: 7, text: 'PALETAS'};
    } else if (item_und_med == "PG") {   //  Nombre: PLACAS
        return {value: 7, text: 'PLACAS'};
    } else if (item_und_med == "PK") {   //  Nombre: PAQUETE
        return {value: 18, text: 'PAQUETE'};
    } else if (item_und_med == "PR") {   //  Nombre: PAR
        return {value: 7, text: 'PAR'};
    } else if (item_und_med == "RM") {   //  Nombre: RESMA
        return {value: 59, text: 'RESMA'};
    } else if (item_und_med == "SET") {   //  Nombre: JUEGO
        return {value: 50, text: 'JUEGO'};
    } else if (item_und_med == "ST") {   //  Nombre: PLIEGO
        return {value: 58, text: 'PLIEGO'};
    } else if (item_und_med == "STN") {   //  Nombre: TONELADA CORTA
        return {value: 5, text: 'TONELADA CORTA'};
    } else if (item_und_med == "TNE") {   //  Nombre: TONELADAS
        return {value: 60, text: 'TONELADAS'};
    } else if (item_und_med == "TU") {   //  Nombre: TUBOS
        return {value: 7, text: 'TUBOS'};
    } else if (item_und_med == "UM") {   //  Nombre: MILLON DE UNIDADES
        return {value: 7, text: 'MILLON DE UNIDADES'};
    } else if (item_und_med == "YDK") {   //  Nombre: YARDA CUADRADA
        return {value: 7, text: 'YARDA CUADRADA'};
    } else if (item_und_med == "YRD") {   //  Nombre: YARDA
        return {value: 7, text: 'YARDA'};
    } else if (item_und_med == "ZZ") {   //  Nombre: UNIDAD (SERVICIOS)
        return {value: 20, text: 'SERVICIO'};
    } else {
        return {value: 7, text: 'UNIDADES'};
    }
}

function checkValue(value,arr){
    var status = false;
   
    for(var i=0; i<arr.length; i++){
      var name = arr[i];
      if(name == value){
        status = true;
        break;
      }
    }
    return status;
}

function get_info_sucursal_cpe() {

    var idsucursal = $("#select_sucursal").val();
    var tipo_documento = $('input[name="tipo_comprobante"]:checked').val();
    
    if(tipo_documento == '99') {
        $("#serie_comprobante").prop('disabled', true);
        $("#numero_comprobante").prop('disabled', true);
        $.ajax({
            url : '/facturacionv8/herramientas/get_data_sucursal',
            method :  'POST',
            data: {idsucursal: idsucursal},
            dataType : "json"
        }).then(function(data){
            if(data.respuesta == 'ok') {
                
                $("#serie_comprobante").val(data.sucursal.orden_compra_serie);  

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
    } else {
        $("#serie_comprobante").prop('disabled', false);
        $("#numero_comprobante").prop('disabled', false);
    }
    
    
}