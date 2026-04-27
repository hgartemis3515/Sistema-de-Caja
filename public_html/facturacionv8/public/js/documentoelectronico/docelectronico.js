$(function(){
    var tipo_doc_selected = $("#tipo_doc_selected").val();
    var btn_productos_accion = '';
    var ultimo_texto_buscado = '';


    $('#prueba_fecha').daterangepicker(
        {
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            singleDatePicker: true,
            minDate: '01/01/2020',
            maxDate: '12/12/2021',
            dateLimit: { days: 60 },
            parentEl: '.content-inner',
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        },
        function(start, end) {
            console.log('Date range has been changed');
        }
    );
    
	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
	$('.select').select2({ minimumResultsForSearch: -1 });
    $('.select_codigoubigeo').select2();
    $('.select_with_search').select2();
	inicializar_tabla_detalle();
    //get_lista_ubigeos($("#select_codigoubigeo"));
    inicializar_buscador_ubigeos($("#select_codigoubigeo"));
    get_lista_sucursales_doc($("#select_sucursal"), 'si');
    get_lista_almacenes($("#select_almacen"), 'si');
    //get_lista_monedas($("#codmoneda_comprobante"));
    //get_lista_unidades_medida($("#producto_unidadmedida"));
    get_lista_tipoafectacionigv($("#producto_tipo_afect_igv")); 
    get_tipocambio_by_date($("#new_producto_tipodecambio"));

    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode == 112){ //F1
            event.preventDefault();
            $(".btn_agregarproducto").trigger('click');
        }
    });

    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode == 123){ //F12
            event.preventDefault();
            $("#btn_guardar_doc_electronico").trigger('click');
        }
    });

    $('#cliente_numerodocumento').keyup(function(e){
        if(e.keyCode == 13) {
            $(".search_document").trigger("click");
        }
    });

    $('#producto_total').keyup(function(e){
        if(e.keyCode == 13) {
            $(".btn_agregarproducto_detalle").trigger("click");
        }
    });
    
    if(tipo_doc_selected == '03' || tipo_doc_selected == '77' || tipo_doc_selected == '88') {
        selected_doc_identidad = 1;
    } else {
        selected_doc_identidad = 6;
    }
    get_lista_tipo_doc_identidad($("#cliente_tipo_docidentidad"), selected_doc_identidad);
    get_tiponotadebito($("#id_motivo_nota_debito"));
    get_tiponotacredito($("#id_motivo_nota_credito"));

	$(".search_document").click(function() {
        if($("#cliente_numerodocumento-flexdatalist").val() != '') {
            var num_doc = $("#cliente_numerodocumento-flexdatalist").val();
            $("#cliente_numerodocumento").val($("#cliente_numerodocumento-flexdatalist").val());
        } else {
            var num_doc = $("#cliente_numerodocumento").val();
        }
         
		if(num_doc != '') {
            var tipo_doc = $("#cliente_tipo_docidentidad").val();
            //if(tipo_doc == 1 || tipo_doc == 6 || tipo_doc == 0) { //1: DNI //6: RUC
                consultar_numero_doc_cliente($("#id_cliente_documento"), num_doc, $("#cliente_nombre"), $("#cliente_direccion"), $("#select_codigoubigeo"), tipo_doc, $("#cliente_email"), $("#numero_celular"));
            //}
		}
    });
    
	$("#select_sucursal").on('change', get_info_sucursal_cpe);

    $(".btn_agregarproducto").click(function() {
        $('.opciones_producto a[href="#buscar_producto"]').tab('show');
        $("#key_row").val("");
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        //$("#frm_nuevo_producto")[0].reset();
        reset_form_registro_new_producto();
        btn_productos_accion = 'agregar';
        $(".content_propiedades_producto").hide();
        calcular_totales_documento();
        $("#vm_agregar_articulo").modal("show");
    });

    $(".btn_editarproducto").click(function() {
        $('.opciones_producto a[href="#buscar_producto"]').tab('show');

        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        //$("#frm_nuevo_producto")[0].reset();
        reset_form_registro_new_producto();
        var rowid = jQuery("#detalle_documento").jqGrid('getGridParam', 'selrow');
        if(rowid === undefined || rowid == '') {
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
            $(".content_propiedades_producto").show();
            editar_item_tabla(rowid);
            btn_productos_accion = 'editar';
            $("#vm_agregar_articulo").modal("show");
        }
        
    });
    
    $(".btn_eliminarproducto").click(eliminar_producto_detalle);

    inicializar_buscador();
    $('.select_producto_buscar').on('change', get_data_producto);
    $('#producto_tipo_afect_igv').on('change', calcular_totales_producto);
    $("#codmoneda_comprobante").on('change', recalcular_por_cambio_de_moneda);

    $(".totales_input").on('input', function() {
        calcular_totales_producto();
    }).on('change', function() {
        calcular_totales_producto();
    });

    $("#total_recibido").click(function() {
        $(this).select();
    });
    $("#total_recibido").on('input', function() {
        calcular_vuelto();
    }).on('change', function() {
        calcular_vuelto();
    });

    $(".input_modify_totales").on('input', function() {
        calcular_totales_documento();
    }).on('change', function() {
        calcular_totales_documento();
    });

    $("#producto_total").on('change', function() {
        var total =  $("#producto_total").val();
        var precio = $("#producto_preciounidad").val();
        var cantidad = 0;

        if(precio == '' || precio <= 0 || isNaN(precio)) {
            precio = 0;
        }

        if(total == '' || total <= 0 || isNaN(total)) {
            total = 0;
        }

        if(precio > 0) {
            cantidad = round_math(parseFloat(total)/parseFloat(precio), num_decimales);
        }

        $("#producto_cantidad").val(cantidad);
        calcular_totales_producto();
    });

    $(".btn_agregarproducto_detalle").click(add_to_detalle);

    $(".btn_tipo_documento").click(function() {
        var tipo_doc = $(this).data('tipodocumento');
        $("#select_tipo_doc_electronico").val(tipo_doc).trigger("change").trigger("select2:select");
    });

    $("#select_tipo_doc_electronico").on('change', function(){
        $("#cliente_numerodocumento").val('');
        $("#cliente_nombre").val('');
        $("#cliente_direccion").val('');

        if(this.value == '01') { //factura
            $("#titulo_numerodocumento").html('N° de R.U.C.');
            $("#titulo_nombrecliente").html('Razón Social');
            $("#cliente_tipo_docidentidad").val(6).trigger("change").trigger("select2:select");
            $("#contenido_nota_credito_debito").hide();
            $("#content_modo_envio_a_sunat").show();

            $("#titulo_tipo_documento").html('<img src="' + $("#img_factura").attr('src') +'" style="width: 18px;"/> Creando Factura')
        } else if (this.value == '03') { //boleta
            $("#titulo_numerodocumento").html('N° de Documento de Identidad');
            $("#titulo_nombrecliente").html('Nombre Cliente');
            $("#cliente_tipo_docidentidad").val(1).trigger("change").trigger("select2:select");
            $("#contenido_nota_credito_debito").hide();
            $("#content_modo_envio_a_sunat").show();

            $("#titulo_tipo_documento").html('<img src="' + $("#img_factura").attr('src') +'" style="width: 18px;"/> Creando Boleta')
        } else if (this.value == '07') { //nota de crédito
            $("#contenido_motivo_nota_credito").show();
            $("#contenido_motivo_nota_debito").hide();
            $("#contenido_nota_credito_debito").show();
            $("#content_modo_envio_a_sunat").show();

            $("#titulo_tipo_documento").html('<img src="' + $("#img_factura").attr('src') +'" style="width: 18px;"/> Creando Nota de Crédito')
        } else if (this.value == '08') { //nota de Débito
            $("#contenido_motivo_nota_debito").show();
            $("#contenido_motivo_nota_credito").hide();
            $("#contenido_nota_credito_debito").show();
            $("#content_modo_envio_a_sunat").show();

            $("#titulo_tipo_documento").html('<img src="' + $("#img_factura").attr('src') +'" style="width: 18px;"/> Creando Nota de Débito')
        } else if (this.value == '77') { //Nota de Venta
            $("#titulo_numerodocumento").html('N° de Documento');
            $("#titulo_nombrecliente").html('Nombre/Razón Social');
            $("#cliente_tipo_docidentidad").val(1).trigger("change").trigger("select2:select");
            $("#contenido_nota_credito_debito").hide();
            $("#content_modo_envio_a_sunat").hide();

            $("#titulo_tipo_documento").html('<img src="' + $("#img_factura").attr('src') +'" style="width: 18px;"/> Creando Nota de Venta')
        } else if (this.value == '88') { //Cotización
            $("#titulo_numerodocumento").html('N° de Documento');
            $("#titulo_nombrecliente").html('Nombre/Razón Social');
            $("#cliente_tipo_docidentidad").val(1).trigger("change").trigger("select2:select");
            $("#contenido_nota_credito_debito").hide();
            $("#content_modo_envio_a_sunat").hide();

            $("#titulo_tipo_documento").html('<img src="' + $("#img_factura").attr('src') +'" style="width: 18px;"/> Creando Cotización')
        }
        
        $("#texto_nombre_documento").html($("#select_tipo_doc_electronico option:selected").text());
        document.title = $("#select_tipo_doc_electronico option:selected").text();
    });

    $("#serie_numero_doc_modificar").on('change', get_data_documento);
    $("#select_tipo_doc_electronico").val(tipo_doc_selected).trigger("change").trigger("select2:select");

    var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
    });

    $(".switch").bootstrapSwitch();
    $('#opcion_envio_email').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $(".content_email_cliente").show("slide");
        } else {
            $(".content_email_cliente").hide("slide");
        }
    });

    var doc_guardado_id_tipodoc_electronico = $("#doc_guardado_id_tipodoc_electronico").val();
    var doc_guardado_serie_comprobante = $("#doc_guardado_serie_comprobante").val();
    var doc_guardado_numero_comprobante = parseInt($("#doc_guardado_numero_comprobante").val());

    var doc_modifica_id_tipodoc_electronico = $("#doc_modifica_id_tipodoc_electronico").val();
    var doc_modifica_serie_comprobante = $("#doc_modifica_serie_comprobante").val();
    var doc_modifica_numero_comprobante = parseInt((($("#doc_modifica_numero_comprobante").val() == '')?0:$("#doc_modifica_numero_comprobante").val()));

    var tipo_doc_guardado = $("#tipo_doc_guardado").val();
    var numero_doc_guardado = parseInt($("#numero_doc_guardado").val());
    var serie_doc_guardado = $("#serie_doc_guardado").val();

    if(doc_guardado_serie_comprobante == '' && tipo_doc_guardado == '') {
        get_tipo_cambio($("#tipo_cambio_comprobante"));
        get_tipocambio_by_date_venta($("#tipo_cambio_comprobante"));
    }

    if(doc_guardado_id_tipodoc_electronico != '' && doc_guardado_serie_comprobante != '' && doc_guardado_numero_comprobante > 0) {
        if(doc_modifica_id_tipodoc_electronico != '' && doc_modifica_serie_comprobante != '' && doc_modifica_numero_comprobante > 0) {
            $(".data_no_modificable_notas").hide();
            $("#content_data_doc_modificado").removeClass("col-md-12");
            $("#content_data_doc_modificado").addClass("col-md-7");
        }
        var light = $("#cuerpo_comprobante");
        get_data_documento_electronico(doc_guardado_id_tipodoc_electronico, doc_guardado_serie_comprobante, doc_guardado_numero_comprobante, light, doc_modifica_id_tipodoc_electronico, doc_modifica_serie_comprobante, doc_modifica_numero_comprobante);
    }

    if(tipo_doc_guardado == '77') {
        if(numero_doc_guardado > 0) {
            var light = $("#cuerpo_comprobante");
            get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'NOTA DE VENTA', light, serie_doc_guardado);
        }
    }

    if(tipo_doc_guardado == '88') {
        if(numero_doc_guardado > 0) {
            var light = $("#cuerpo_comprobante");
            get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'COTIZACIÓN', light, serie_doc_guardado);
        }
    }

    if(tipo_doc_guardado == '01') {
        if(numero_doc_guardado > 0) {
            if(serie_doc_guardado != '') {
                var light = $("#cuerpo_comprobante");
                get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'FACTURA', light, serie_doc_guardado);
            }
        }
    }

    if(tipo_doc_guardado == '03') {
        if(numero_doc_guardado > 0) {
            if(serie_doc_guardado != '') {
                var light = $("#cuerpo_comprobante");
                get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'BOLETA', light, serie_doc_guardado);
            }
        }
    }

    if(tipo_doc_guardado == '09') {
        if(numero_doc_guardado > 0) {
            if(serie_doc_guardado != '') {
                var light = $("#cuerpo_comprobante");
                get_data_doc_guardado(tipo_doc_guardado, numero_doc_guardado, 'GRE', light, serie_doc_guardado);
            }
        }
    }
    
    if($("#doc_guardado_serie_comprobante").val() == '' && $("#doc_guardado_numero_comprobante").val() == '') {
        if(doc_modifica_id_tipodoc_electronico != '' && doc_modifica_serie_comprobante != '' && doc_modifica_numero_comprobante > 0) {
            $("#tipo_docelectronico_modificar").val(doc_modifica_id_tipodoc_electronico).trigger("change").trigger("select2:select");
            $('#serie_numero_doc_modificar')
                .append($("<option></option>")
                .attr("value",doc_modifica_serie_comprobante+','+doc_modifica_numero_comprobante)
                .text($("#tipo_docelectronico_modificar option:selected").text() + ': ' + doc_modifica_serie_comprobante+'-'+doc_modifica_numero_comprobante));
            
            $('#serie_numero_doc_modificar').trigger("change").trigger("select2:select");
        }
    }

    $("#producto_preciounidad").on('input', function() {
        calcular_precio_sin_igv();
        calcular_totales_producto();
    }).on('change', function() {
        calcular_precio_sin_igv();
        calcular_totales_producto();
    });
    
    $("#producto_preciounidad_sin_igv").on('input', function() {
        calcular_precio_con_igv();
        calcular_totales_producto();
    }).on('change', function() {
        calcular_precio_con_igv();
        calcular_totales_producto();
    });
    
    inicializar_controles();
    inicializar_autocompletado();

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
    
    $('.totales_input').keyup(function(e){
        if(e.keyCode == 13) {
            $(".btn_agregarproducto_detalle").trigger("click");
        }
    });

    $(".totales_input").click(function() {
        $(this).select();
    });

    $(".monto_total_producto").click(function() {
        $(this).select();
    });

    //$(".switch").bootstrapSwitch();
    $('#opcion_tipo_descuento').on('change', function() {
        var checkbox = document.querySelector('.opcion_tipo_descuento');
        if (checkbox.checked) {
            $("#txt_titulo_opcion_descuento").html('Descuento en %');
            $("#content_descuento_porcentaje_input").show();
            $("#content_descuento_total").hide();
        } else {
            $("#txt_titulo_opcion_descuento").html('Desc. por Monto');
            $("#content_descuento_total").show();
            $("#content_descuento_porcentaje_input").hide();
        }
    });

    $("#btn_agregar_categoria").click(function() {
        $("#contenido_nuevo_producto").hide();
        $("#contenido_nueva_categoria").show('slide');
    });

    $(".btn_cancelar_categoria").click(function() {
        $("#contenido_nueva_categoria").hide();
        $("#contenido_nuevo_producto").show('slide');
    });

    $(".btn_generar_codigo_cate").click(function(){
        generar_codigo($("#txt_codigo_categoria"), 7, $(".btn_generar_codigo_cate > i"));
    });

    $(".btn_guardar_nueva_categoria").click(agregar_categoria);
    $("#historial_costos").popover({
        trigger: "hover",
        placement: "top",
        html: true,
        title: "Historial de Costos:",
        content: ""
    });

    $("#tipo_percepcion").on('change', function(){
        var factor_percepcion = $(this).find(':selected').data('porcentaje');
        var porcentaje_percepcion = parseFloat(factor_percepcion)/100;
        $("#monto_base_percepcion").val($("#txt_total_comprobante").val());
        var monto_base_percepcion = parseFloat($("#monto_base_percepcion").val());
        $("#porcentaje_percepcion").val(factor_percepcion);
        $("#monto_percepcion").val(round_math(porcentaje_percepcion*monto_base_percepcion, 2));
    });

    $("#select_aplica_retencion").on('change', function(){
        var aplica_retencion = this.value;
        if(aplica_retencion == 'si') {
            $("#row_total_a_pagar").show();
            $("#content_info_retencion").show();

            var factor_retencion = parseFloat($("#doc_regimen_retencion").val());
            var monto_base_retencion = parseFloat($("#txt_total_comprobante").val());
            var tipo_moneda = $("#codmoneda_comprobante").val();
            $("#porcentaje_retencion").val(parseFloat($("#doc_regimen_retencion").val())*100);
            $("#base_imponible_retencion").val(monto_base_retencion);

            if(tipo_moneda == 'USD') {
                var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
                var monto_retencion_dolares = round_math(factor_retencion*monto_base_retencion, 2);

                //var monto_retencion_soles = round_math(monto_retencion_dolares*tipo_cambio, 0);
                $("#monto_retencion").val(monto_retencion_dolares); //según validaciones de sunat la retención debe estar en la misma moneda del comprobante

                //var nuevo_monto_retencion_dolares = round_math(monto_retencion_soles/tipo_cambio, 2); //se recalcula para saber cuánto se pagará en dólares
                //$("#titulo_info_retencion").html('Información de la Retención: S/. ' + monto_retencion_soles);
                $("#monto_dolares_retencion").val(monto_retencion_dolares);

                var total_a_pagar = round_math(monto_base_retencion - monto_retencion_dolares, 2);
            } else {
                var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
                var monto_retencion = round_math(factor_retencion*monto_base_retencion, 2);
                var total_a_pagar = round_math(monto_base_retencion - monto_retencion, 2);
                $("#monto_retencion").val(monto_retencion);
            }

            $("#txt_total_a_pagar").val(total_a_pagar);
            $("#total_a_pagar").html(total_a_pagar);

            var monto_deuda = parseFloat($("#txt_monto_adeudado").val());
            if(monto_deuda > total_a_pagar) {
                console.log("si llega!");
                $("#txt_monto_adeudado").val(total_a_pagar);
            }
        } else {
            $("#content_info_retencion").hide();
            var id_tipooperacion = $("#tipo_operacion_docelectronico").val();
            if(id_tipooperacion == '1001' || id_tipooperacion == '1002' || id_tipooperacion == '1003' || id_tipooperacion == '1004') {

            } else {
                $("#row_total_a_pagar").hide();
            }
        }

        $("#txt_monto_adeudado").trigger('change');
        $("#total_recibido").trigger('change');
        //validar_monto_pagado_adeudado();
        //validar_formadepago_tipoventa(true);
        //calcular_vuelto();
    });

    $("#detraccion_codigo_bien").on('change', function() {
        var factor_detraccion = $(this).find(':selected').data('porcentaje');
        var porcentaje_detraccion = parseFloat(factor_detraccion)/100;
        var monto_base_detraccion = parseFloat($("#txt_total_comprobante").val());
        var tipo_moneda = $("#codmoneda_comprobante").val();
        $("#porcentaje_detraccion").val(factor_detraccion);

        if(tipo_moneda == 'USD') {
            var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
            var monto_detraccion_dolares = round_math(porcentaje_detraccion*monto_base_detraccion, 2);
            var monto_detraccion_soles = round_math(monto_detraccion_dolares*tipo_cambio, 2);
            $("#monto_detraccion").val(monto_detraccion_soles);
            var nuevo_monto_detraccion_dolares = round_math(monto_detraccion_soles/tipo_cambio, 2); //se recalcula para saber cuánto se pagará en dólares

            $("#titulo_info_detraccion").html('Información de la Detracción: USD ' + nuevo_monto_detraccion_dolares);
            $("#monto_dolares_detraccion").val(nuevo_monto_detraccion_dolares);
            $("#informacion_equivalencia_dolares").html('<i class="icon-info3 text-size-mini position-left"></i> Según RS N° 183-2004/SUNAT indica que debemos utilizar el tipo de cambio venta (' + tipo_cambio + ') en la fecha de creación del documento, y según R.S. N° 178-2005/SUNAT se debe redondear a un número entero. Por tanto el monto resultante luego de redondear es: S/. ' + monto_detraccion_soles +' y su equivalente en dólares es: USD ' + nuevo_monto_detraccion_dolares);
            $("#informacion_equivalencia_dolares").show();

            var total_a_pagar = round_math(monto_base_detraccion - nuevo_monto_detraccion_dolares, 2);
            $("#txt_total_detraccion").val(nuevo_monto_detraccion_dolares);
            $("#total_detraccion").html(nuevo_monto_detraccion_dolares);
            $("#txt_total_a_pagar").val(total_a_pagar);
            $("#total_a_pagar").html(total_a_pagar);
        } else {
            var monto_detraccion = round_math(porcentaje_detraccion*monto_base_detraccion, 2);
            var total_a_pagar = round_math(monto_base_detraccion - monto_detraccion, 2);
            $("#monto_detraccion").val(monto_detraccion);
            $("#titulo_info_detraccion").html('Información de la Detracción:');
            $("#informacion_equivalencia_dolares").hide();

            $("#txt_total_detraccion").val(monto_detraccion);
            $("#total_detraccion").html(monto_detraccion);
            $("#txt_total_a_pagar").val(total_a_pagar);
            $("#total_a_pagar").html(total_a_pagar);
        }

        $("#total_recibido").trigger('change');
        validar_monto_pagado_adeudado();
        validar_formadepago_tipoventa(true);
        calcular_vuelto();
    });

    $("#tipo_operacion_docelectronico").on('change', function(){
        var id_tipooperacion = this.value;
        if(id_tipooperacion == '0101') {
            //venta interna
            $("#content_info_percepcion").hide();
            $("#content_info_detraccion").hide();
            $("#row_total_detraccion").hide();
            $("#row_total_a_pagar").hide();
        } else if(id_tipooperacion == '1001') {
            //Operación Sujeta a Detracción
            $("#content_info_percepcion").hide();
            $("#content_info_detraccion").show();
            $("#row_total_detraccion").show();
            $("#row_total_a_pagar").show();

            $("#detraccion_codigo_bien").trigger("change");
        } else if(id_tipooperacion == '1002') {
            //Operación Sujeta a Detracción
            $("#content_info_percepcion").hide();
            $("#content_info_detraccion").show();
            $("#row_total_detraccion").hide();
            $("#row_total_a_pagar").hide();

            $("#detraccion_codigo_bien").trigger("change");
        } else if(id_tipooperacion == '1003') {
            //Operación Sujeta a Detracción
            $("#content_info_percepcion").hide();
            $("#content_info_detraccion").show();
            $("#row_total_detraccion").hide();
            $("#row_total_a_pagar").hide();

            $("#detraccion_codigo_bien").trigger("change");
        } else if(id_tipooperacion == '1004') {
            //Operación Sujeta a Detracción
            $("#content_info_percepcion").hide();
            $("#content_info_detraccion").show();
            $("#row_total_detraccion").hide();
            $("#row_total_a_pagar").hide();

            $("#detraccion_codigo_bien").trigger("change");
        } else if(id_tipooperacion == '2001') {
            //percepcion
            $("#content_info_percepcion").show();
            $("#content_info_detraccion").hide();
            $("#row_total_detraccion").hide();
            $("#row_total_a_pagar").hide();
        } else {
            $("#content_info_percepcion").hide();
            $("#content_info_detraccion").hide();
            $("#row_total_detraccion").hide();
            $("#row_total_a_pagar").hide();
        }
    });

    $('.opt_avanzadas').on('change', ver_opciones_avanzadas);
    if (typeof(Storage) !== "undefined") {
        var key_opt_avanzadas = 'opt_avanzadas_' + $("#id_usuario_sistema").val();
        if (localStorage.getItem(key_opt_avanzadas) === null) {

        } else {
            mostrar_ocultar_opciones_avanzadas(JSON.parse(localStorage.getItem(key_opt_avanzadas)), 'storage');
        }
    }

    $('#producto_unidadmedida').on('change', function() {
        var tipo = $(this).find(':selected').data('tipo');
        if(typeof(tipo) != "undefined" && tipo !== null) {

            var tipo_moneda = $("#codmoneda_comprobante").val();
            var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);

            var numero_decimales = 2;
            if (typeof num_decimales !== 'undefined') {
                if(num_decimales > 2) {
                    numero_decimales = num_decimales;
                }
            }

            console.log(tipo);
            
            if(tipo == 'presentacion') {
                var precioconigv = $(this).find(':selected').data('precioconigv');
                var preciosinigv = $(this).find(':selected').data('preciosinigv');
                var codigo = $(this).find(':selected').data('codigo');
                var id_tipoafectacionigv = parseInt($('#producto_tipo_afect_igv').val()) + 0;
                var nombrepresentacion = $(this).find(':selected').data('nombrepresentacion');
                var idcodmoneda = $(this).find(':selected').data('idcodmoneda');
                var precio_minimo = round_math(parseFloat($(this).find(':selected').data('preciominimo')), numero_decimales);
                var simbolo_moneda = idcodmoneda == 'USD'?'$':'S/.';

                console.log(precio_minimo);

                if(tipo_moneda == 'USD') {
                    if(idcodmoneda == 'PEN') {
                        precioconigv = round_math(parseFloat(precioconigv/tipo_cambio), numero_decimales);
                        preciosinigv = round_math(parseFloat(preciosinigv/tipo_cambio), numero_decimales);
                    }
                }

                if(tipo_moneda == 'PEN') {
                    if(idcodmoneda == 'USD') {
                        precioconigv = round_math(parseFloat(precioconigv*tipo_cambio), numero_decimales);
                        preciosinigv = round_math(parseFloat(preciosinigv*tipo_cambio), numero_decimales);
                    }
                }
                
                var precio_normal = 0;
                if(id_tipoafectacionigv == 10) {
                    precio_normal = precioconigv;
                    asignar_precio_de_lista(precioconigv, tipo_moneda);
                } else {
                    precio_normal = preciosinigv;
                    asignar_precio_de_lista(preciosinigv, tipo_moneda);
                }

                var html_precio_normal =`<a href="javascript:void(0);" data-precio="` + precio_normal + `" onclick="asignar_precio_de_lista(` + precio_normal + `,'` + tipo_moneda + `')"><span class="badge badge-success pull-right">` + simbolo_moneda + ` ` + precio_normal + `</span> Precio de Venta</a>`;

                var html_precio_minimo =`<a href="javascript:void(0);" data-precio="` + precio_minimo + `" onclick="asignar_precio_de_lista(` + precio_minimo + `,'` + tipo_moneda + `')"><span class="badge badge-success pull-right">` + simbolo_moneda + ` ` + precio_minimo + `</span> Precio Mínimo</a>`;

                $("#vm_add_product_producto_precio_venta").html(html_precio_normal);
                $("#vm_add_product_producto_precio_venta_minimo").html(html_precio_minimo);

                $("#producto_descripcion").val($("#select_producto_buscar option:selected").text() + ' - ' + nombrepresentacion);
                $("#producto_codigo").val(codigo);
            }

            if(tipo == 'unidad') {
                var precioconigv = $(this).find(':selected').data('precioconigv');
                var preciosinigv = $(this).find(':selected').data('preciosinigv');
                var codigo = $(this).find(':selected').data('codigo');
                var id_tipoafectacionigv = parseInt($('#producto_tipo_afect_igv').val()) + 0;
                var nombrepresentacion = $(this).find(':selected').data('nombrepresentacion');
                var idcodmoneda = $(this).find(':selected').data('idcodmoneda');
                var precio_minimo = round_math(parseFloat($(this).find(':selected').data('preciominimo')), numero_decimales);
                var simbolo_moneda = idcodmoneda == 'USD'?'$':'S/.';

                if(tipo_moneda == 'USD') {
                    if(idcodmoneda == 'PEN') {
                        precioconigv = round_math(parseFloat(precioconigv/tipo_cambio), numero_decimales);
                        preciosinigv = round_math(parseFloat(preciosinigv/tipo_cambio), numero_decimales);
                    }
                }

                if(tipo_moneda == 'PEN') {
                    if(idcodmoneda == 'USD') {
                        precioconigv = round_math(parseFloat(precioconigv*tipo_cambio), numero_decimales);
                        preciosinigv = round_math(parseFloat(preciosinigv*tipo_cambio), numero_decimales);
                    }
                }
                
                var precio_normal = 0;
                if(id_tipoafectacionigv == 10) {
                    precio_normal = precioconigv;
                    asignar_precio_de_lista(precioconigv, tipo_moneda);
                } else {
                    precio_normal = preciosinigv;
                    asignar_precio_de_lista(preciosinigv, tipo_moneda);
                }

                var html_precio_normal =`<a href="javascript:void(0);" data-precio="` + precio_normal + `" onclick="asignar_precio_de_lista(` + precio_normal + `,'` + tipo_moneda + `')"><span class="badge badge-success pull-right">` + simbolo_moneda + ` ` + precio_normal + `</span> Precio de Venta</a>`;

                var html_precio_minimo =`<a href="javascript:void(0);" data-precio="` + precio_minimo + `" onclick="asignar_precio_de_lista(` + precio_minimo + `,'` + tipo_moneda + `')"><span class="badge badge-success pull-right">` + simbolo_moneda + ` ` + precio_minimo + `</span> Precio Mínimo</a>`;

                $("#vm_add_product_producto_precio_venta").html(html_precio_normal);
                $("#vm_add_product_producto_precio_venta_minimo").html(html_precio_minimo);

                $("#producto_descripcion").val($("#select_producto_buscar option:selected").text());
                $("#producto_codigo").val(codigo);
            }
        }
    });

    $(".btn_resetear").click(limpiar_documento_electronico);

    $("#cliente_tipo_docidentidad").on('change', function() {
        if(this.value == '6') {
            $("#cliente_numerodocumento").attr('inputmode', 'numeric');
            $("#cliente_numerodocumento").attr('type', 'number');
            $("#cliente_numerodocumento").attr('pattern', '[0-9]*');

            $("#cliente_numerodocumento-flexdatalist").attr('inputmode', 'numeric');
            $("#cliente_numerodocumento-flexdatalist").attr('type', 'number');
            $("#cliente_numerodocumento-flexdatalist").attr('pattern', '[0-9]*');
        } else if(this.value == '1') {
            $("#cliente_numerodocumento").attr('inputmode', 'numeric');
            $("#cliente_numerodocumento").attr('type', 'number');
            $("#cliente_numerodocumento").attr('pattern', '[0-9]*');

            $("#cliente_numerodocumento-flexdatalist").attr('inputmode', 'numeric');
            $("#cliente_numerodocumento-flexdatalist").attr('type', 'number');
            $("#cliente_numerodocumento-flexdatalist").attr('pattern', '[0-9]*');
        } else {
            $("#cliente_numerodocumento").attr('inputmode', 'text');
            $("#cliente_numerodocumento").attr('type', 'text');
            $("#cliente_numerodocumento").attr('pattern', '[A-Za-z0-9_]{1,15}');

            $("#cliente_numerodocumento-flexdatalist").attr('inputmode', 'text');
            $("#cliente_numerodocumento-flexdatalist").attr('type', 'text');
            $("#cliente_numerodocumento-flexdatalist").attr('pattern', '[A-Za-z0-9_]{1,15}');
        }
    });

    $('.select_etiquetas').multiselect({
        nonSelectedText: 'Seleccionar'
    });

    $("#select_igv_sunat").on('change', cambiar_factor_igv_sunat);
});

function cambiar_factor_igv_sunat() {
    var text_factor_igv_sunat = "(" + $("#select_igv_sunat option:selected").text() + ")";
    $(".texto_facto_igv_sunat").html(text_factor_igv_sunat);
    recalcular_por_cambio_factor_igv();
}

function get_info_sucursal_cpe() {

    var idsucursal = this.value;
    var tipo_comprobante = $("#select_tipo_doc_electronico").val();
    var input_serie = $("#serie_comprobante");
    var tipo_doc_electronico = '';
    
    if (tipo_comprobante == '01') { 
        tipo_doc_electronico = 'factura';
    } else if (tipo_comprobante == '03') {
        tipo_doc_electronico = 'boleta';
    } else if (tipo_comprobante == '07') {
        if($("#tipo_docelectronico_modificar").val() == '01') {
            tipo_doc_electronico = 'notacredito_factura';
        } else if($("#tipo_docelectronico_modificar").val() == '03') {
            tipo_doc_electronico = 'notacredito_boleta';
        }
    } else if (tipo_comprobante == '08') {
        if($("#tipo_docelectronico_modificar").val() == '01') {
            tipo_doc_electronico = 'notadebito_factura';
        } else if($("#tipo_docelectronico_modificar").val() == '03') {
            tipo_doc_electronico = 'notadebito_boleta';
        }
    }
    
    $.ajax({
        url : '/facturacionv8/herramientas/get_data_sucursal',
        method :  'POST',
        data: {idsucursal: idsucursal},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc_electronico == 'factura') {
                input_serie.val(data.sucursal.factura_serie);
            } else if(tipo_doc_electronico == 'boleta') {
                input_serie.val(data.sucursal.boleta_serie);
            } else if(tipo_doc_electronico == 'notacredito_factura') {
                input_serie.val(data.sucursal.notacredito_factura_serie);
            } else if(tipo_doc_electronico == 'notadebito_factura') {
                input_serie.val(data.sucursal.notadebito_factura_serie);
            } else if(tipo_doc_electronico == 'notacredito_boleta') {
                input_serie.val(data.sucursal.notacredito_boleta_serie);
            } else if(tipo_doc_electronico == 'notadebito_boleta') {
                input_serie.val(data.sucursal.notadebito_boleta_serie);
            } else {

            }

            if(data.total_sucurles > 1) {
                $(".info_sucursal_seleccionada").html('<i class="icon-info3 text-size-mini position-left"></i> ' + 'Almacén de Sucursal: ' + data.sucursal.nombre + ' ('+ data.sucursal.idsucursal +')');
            } else {
                $(".info_sucursal_seleccionada").hide();
            }

            //se agrega este código para que solo se ejecute si es un documento nuevo
            var accion_cpe = $("#documento_tipo_accion").val();
            if(accion_cpe == 'editar' || accion_cpe == 'crear_nota_credito' || accion_cpe == 'crear_nota_debito' || accion_cpe == 'transformar_cotizacion' || accion_cpe == 'transformar_nota_venta' || accion_cpe == 'duplicar_boleta' || accion_cpe == 'duplicar_factura' || accion_cpe == 'editar_cotizacion') {
                
                
            } else {
                $("#select_igv_sunat").val(parseFloat(data.sucursal.factor_igv)).trigger("change");
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

function limpiar_documento_electronico() {
    $('#detalle_documento').jqGrid('clearGridData');
    calcular_totales_documento();
    $("#select_codigoubigeo").empty();
    $("#cliente_numerodocumento").val('');
    $("#flexdatalist-cliente_numerodocumento").val('');
    $("#cliente_nombre").val('');
    $("#id_cliente_documento").val(''); 
    $("#cliente_nombre-flexdatalist").val('');
    $("#cliente_direccion").val('');
    $("#numero_celular").val('');
    $("#cliente_email").val('');
    $("#estado_numerodocumento").removeClass();
    $("#razonsocial_numerodocumento").removeClass();
    $("#estado_numerodocumento").addClass("form-group col-md-4");
    $("#razonsocial_numerodocumento").addClass("form-group col-md-5");

    $("#content_cliente_api_foto_src").hide();
    $("#content_razon_social_cliente").css("display", "block");

    var id_tipodocidentidad = $("#cliente_tipo_docidentidad").val();
    if(id_tipodocidentidad == 1) {
        $("#titulo_numerodocumento").html("N° de D.N.I.");
        $("#titulo_nombrecliente").html("Nombre del Cliente");
    } else if(id_tipodocidentidad == 6) {
        $("#titulo_numerodocumento").html("N° de R.U.C.");
        $("#titulo_nombrecliente").html("Razón Social");
    } else {
        $("#titulo_numerodocumento").html("N° de Documento");
        $("#titulo_nombrecliente").html("Nombre del Cliente");
    }

    $("#condicionpago_comprobante option").each(function(i){
        if($(this).data('tipocondicion') == 'contado') {
            $("#condicionpago_comprobante").val($(this).val()).trigger("change").trigger("select2:select")
            return false;
        }
    });

    $('#opcion_tipo_venta').bootstrapSwitch('state', false);
    $("#total_recibido").val('');
    $("#total_vuelto").val(0);
    $("#observacion_documento").val('');
    $("#txt_numero_operacion").val('');
    $("#select_cuenta_banco_deposito").val('0').trigger("change");
    $("#txt_descuento_porcentaje").val(0);
    $("#txt_descuento_total").val(0);
    $("#nro_orden").val('');
    $("#nro_placa_vehiculo").val('');
    $("#guia_remision_manual").val('');

    $("#tipo_operacion_docelectronico").val('0101').trigger("change");
    $("#select_aplica_retencion").val('no').trigger("change");
    
    $("#doc_guardado_numero_comprobante").val('');
    $("#tipo_doc_guardado").val('');
    $("#numero_doc_guardado").val('');

}

function ver_opciones_avanzadas() {

    //Datos del Cliente
    var opt_avanzadas_direccion = $('#opt_avanzadas_direccion').is(":checked");
    var opt_avanzadas_ubigeo = $('#opt_avanzadas_ubigeo').is(":checked");
    var opt_avanzadas_numcelular = $('#opt_avanzadas_numcelular').is(":checked");

    //Opciones Avanzadas
    var opt_avanzadas_tipooperacion = $('#opt_avanzadas_tipooperacion').is(":checked");
    var opt_avanzadas_tipodocumento = $('#opt_avanzadas_tipodocumento').is(":checked");
    var opt_avanzadas_moneda = $('#opt_avanzadas_moneda').is(":checked");
    var opt_avanzadas_sucursal = $('#opt_avanzadas_sucursal').is(":checked");
    var opt_avanzadas_seriecomprobante = $('#opt_avanzadas_seriecomprobante').is(":checked");
    var opt_avanzadas_numcomprobante = $('#opt_avanzadas_numcomprobante').is(":checked");
    var opt_avanzadas_fechacomprobante = $('#opt_avanzadas_fechacomprobante').is(":checked");
    var opt_avanzadas_fechavenc_comprobante = $('#opt_avanzadas_fechavenc_comprobante').is(":checked");
    var opt_avanzadas_tipocambio = $('#opt_avanzadas_tipocambio').is(":checked");
    var opt_avanzadas_numeroplaca = $('#opt_avanzadas_numeroplaca').is(":checked");
    var opt_avanzadas_numero_orden = $('#opt_avanzadas_numero_orden').is(":checked");
    var opt_avanzadas_gremision_electronica = $('#opt_avanzadas_gremision_electronica').is(":checked");
    var opt_avanzadas_gremision_manual = $('#opt_avanzadas_gremision_manual').is(":checked");
    var opt_avanzadas_lista_usuarios = $('#opt_avanzadas_lista_usuarios').is(":checked");
    var opt_avanzadas_retencion = $('#opt_avanzadas_retencion').is(":checked");
    var opt_avanzadas_etiquetas = $('#opt_avanzadas_etiquetas').is(":checked");
    var opt_avanzadas_igv_sunat = $('#opt_avanzadas_igv_sunat').is(":checked");
    var opt_avanzadas_tiene_anticipos = $('#opt_avanzadas_tiene_anticipos').is(":checked");

    var opt_avanzadas = { 
        'opt_avanzadas_direccion'               : opt_avanzadas_direccion, 
        'opt_avanzadas_ubigeo'                  : opt_avanzadas_ubigeo, 
        'opt_avanzadas_numcelular'              : opt_avanzadas_numcelular, 
        'opt_avanzadas_tipooperacion'           : opt_avanzadas_tipooperacion, 
        'opt_avanzadas_tipodocumento'           : opt_avanzadas_tipodocumento, 
        'opt_avanzadas_moneda'                  : opt_avanzadas_moneda, 
        'opt_avanzadas_sucursal'                : opt_avanzadas_sucursal, 
        'opt_avanzadas_seriecomprobante'        : opt_avanzadas_seriecomprobante, 
        'opt_avanzadas_numcomprobante'          : opt_avanzadas_numcomprobante, 
        'opt_avanzadas_fechacomprobante'        : opt_avanzadas_fechacomprobante, 
        'opt_avanzadas_fechavenc_comprobante'   : opt_avanzadas_fechavenc_comprobante, 
        'opt_avanzadas_tipocambio'              : opt_avanzadas_tipocambio, 
        'opt_avanzadas_numeroplaca'             : opt_avanzadas_numeroplaca, 
        'opt_avanzadas_numero_orden'            : opt_avanzadas_numero_orden, 
        'opt_avanzadas_gremision_electronica'   : opt_avanzadas_gremision_electronica, 
        'opt_avanzadas_gremision_manual'        : opt_avanzadas_gremision_manual, 
        'opt_avanzadas_lista_usuarios'          : opt_avanzadas_lista_usuarios,
        'opt_avanzadas_retencion'               : opt_avanzadas_retencion,
        'opt_avanzadas_etiquetas'               : opt_avanzadas_etiquetas,
        'opt_avanzadas_igv_sunat'               : opt_avanzadas_igv_sunat,
        'opt_avanzadas_tiene_anticipos'         : opt_avanzadas_tiene_anticipos
    };

    if (typeof(Storage) !== "undefined") {
        localStorage.setItem('opt_avanzadas_' + $("#id_usuario_sistema").val(), JSON.stringify(opt_avanzadas));
    }

    mostrar_ocultar_opciones_avanzadas(opt_avanzadas);
}

function mostrar_ocultar_opciones_avanzadas(opt_avanzadas, tipo = 'usuario') {
    //Datos del Cliente
    if(opt_avanzadas.opt_avanzadas_direccion && !opt_avanzadas.opt_avanzadas_ubigeo && !opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-12');
        $("#control_numcelular").addClass('col-md-4');
        $("#control_ubigeo").addClass('col-md-4');

        $("#control_ubigeo").hide();
        $("#control_numcelular").hide();
        $("#control_direccion").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_direccion').prop('checked', true);
            $.uniform.update('#opt_avanzadas_direccion');
        }

    } else if(!opt_avanzadas.opt_avanzadas_direccion && opt_avanzadas.opt_avanzadas_ubigeo && !opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-4');
        $("#control_numcelular").addClass('col-md-4');
        $("#control_ubigeo").addClass('col-md-12');

        $("#control_direccion").hide();
        $("#control_numcelular").hide();
        $("#control_ubigeo").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_ubigeo').prop('checked', true);
            $.uniform.update('#opt_avanzadas_ubigeo');
        }
        
    } else if(!opt_avanzadas.opt_avanzadas_direccion && !opt_avanzadas.opt_avanzadas_ubigeo && opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-4');
        $("#control_numcelular").addClass('col-md-12');
        $("#control_ubigeo").addClass('col-md-4');

        $("#control_direccion").hide();
        $("#control_ubigeo").hide();
        $("#control_numcelular").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_numcelular').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numcelular');
        }

    } else if(opt_avanzadas.opt_avanzadas_direccion && opt_avanzadas.opt_avanzadas_ubigeo && !opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-6');
        $("#control_numcelular").addClass('col-md-4');
        $("#control_ubigeo").addClass('col-md-6');

        $("#control_numcelular").hide();
        $("#control_direccion").show();
        $("#control_ubigeo").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_direccion').prop('checked', true);
            $.uniform.update('#opt_avanzadas_direccion');

            $('#opt_avanzadas_ubigeo').prop('checked', true);
            $.uniform.update('#opt_avanzadas_ubigeo');
        }
        
    } else if(opt_avanzadas.opt_avanzadas_direccion && !opt_avanzadas.opt_avanzadas_ubigeo && opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-6');
        $("#control_numcelular").addClass('col-md-6');
        $("#control_ubigeo").addClass('col-md-4');

        $("#control_ubigeo").hide();
        $("#control_direccion").show();
        $("#control_numcelular").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_direccion').prop('checked', true);
            $.uniform.update('#opt_avanzadas_direccion');

            $('#opt_avanzadas_numcelular').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numcelular');
        }

    } else if(!opt_avanzadas.opt_avanzadas_direccion && opt_avanzadas.opt_avanzadas_ubigeo && opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-4');
        $("#control_numcelular").addClass('col-md-6');
        $("#control_ubigeo").addClass('col-md-6');

        $("#control_direccion").hide();
        $("#control_numcelular").show();
        $("#control_ubigeo").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_ubigeo').prop('checked', true);
            $.uniform.update('#opt_avanzadas_ubigeo');

            $('#opt_avanzadas_numcelular').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numcelular');
        }

    } else if(opt_avanzadas.opt_avanzadas_direccion && opt_avanzadas.opt_avanzadas_ubigeo && opt_avanzadas.opt_avanzadas_numcelular) {
        $("#control_direccion").removeClass();
        $("#control_numcelular").removeClass();
        $("#control_ubigeo").removeClass();

        $("#control_direccion").addClass('col-md-4');
        $("#control_numcelular").addClass('col-md-4');
        $("#control_ubigeo").addClass('col-md-4');

        $("#control_direccion").show();
        $("#control_numcelular").show();
        $("#control_ubigeo").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_direccion').prop('checked', true);
            $.uniform.update('#opt_avanzadas_direccion');

            $('#opt_avanzadas_ubigeo').prop('checked', true);
            $.uniform.update('#opt_avanzadas_ubigeo');

            $('#opt_avanzadas_numcelular').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numcelular');
        }
    } else {
        $("#control_direccion").hide();
        $("#control_numcelular").hide();
        $("#control_ubigeo").hide();
    }

    //opciones avanzadas
    if(opt_avanzadas.opt_avanzadas_tipooperacion) {
        $("#control_tipooperacion").show();

        if(tipo == 'storage') {
            $('#opt_avanzadas_tipooperacion').prop('checked', true);
            $.uniform.update('#opt_avanzadas_tipooperacion');
        }
    } else {
        $("#control_tipooperacion").hide();
    }

    if(opt_avanzadas.opt_avanzadas_tipodocumento) {
        $("#control_tipodocumento").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_tipodocumento').prop('checked', true);
            $.uniform.update('#opt_avanzadas_tipodocumento');
        }
    } else {
        $("#control_tipodocumento").hide();
    }

    if(opt_avanzadas.opt_avanzadas_moneda) {
        $("#control_moneda").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_moneda').prop('checked', true);
            $.uniform.update('#opt_avanzadas_moneda');
        }
    } else {
        $("#control_moneda").hide();
    }

    if(opt_avanzadas.opt_avanzadas_sucursal) {
        $("#control_sucursal").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_sucursal').prop('checked', true);
            $.uniform.update('#opt_avanzadas_sucursal');
        }
    } else {
        $("#control_sucursal").hide();
    }

    if(opt_avanzadas.opt_avanzadas_seriecomprobante) {
        $("#control_seriecomprobante").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_seriecomprobante').prop('checked', true);
            $.uniform.update('#opt_avanzadas_seriecomprobante');
        }
    } else {
        $("#control_seriecomprobante").hide();
    }

    if(opt_avanzadas.opt_avanzadas_numcomprobante) {
        $("#control_numcomprobante").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_numcomprobante').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numcomprobante');
        }
    } else {
        $("#control_numcomprobante").hide();
    }

    if(opt_avanzadas.opt_avanzadas_fechacomprobante || opt_avanzadas.opt_avanzadas_fechavenc_comprobante) {
        $("#control_fechacomprobante").show();
        $("#control_fechavenc_comprobante").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_fechacomprobante').prop('checked', true);
            $.uniform.update('#opt_avanzadas_fechacomprobante');

            $('#opt_avanzadas_fechavenc_comprobante').prop('checked', true);
            $.uniform.update('#opt_avanzadas_fechavenc_comprobante');
        }
    } else {
        $("#control_fechacomprobante").hide();
        $("#control_fechavenc_comprobante").hide();
    }

    if(opt_avanzadas.opt_avanzadas_tipocambio) {
        $("#control_tipocambio").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_tipocambio').prop('checked', true);
            $.uniform.update('#opt_avanzadas_tipocambio');
        }
    } else {
        $("#control_tipocambio").hide();
    }

    if(opt_avanzadas.opt_avanzadas_numeroplaca) {
        $("#control_numeroplaca").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_numeroplaca').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numeroplaca');
        }
    } else {
        $("#control_numeroplaca").hide();
    }

    if(opt_avanzadas.opt_avanzadas_numero_orden) {
        $("#control_numero_orden").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_numero_orden').prop('checked', true);
            $.uniform.update('#opt_avanzadas_numero_orden');
        }
    } else {
        $("#control_numero_orden").hide();
    }

    if(opt_avanzadas.opt_avanzadas_gremision_electronica) {
        $("#control_gremision_electronica").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_gremision_electronica').prop('checked', true);
            $.uniform.update('#opt_avanzadas_gremision_electronica');
        }
    } else {
        $("#control_gremision_electronica").hide();
    }

    if(opt_avanzadas.opt_avanzadas_gremision_manual) {
        $("#control_gremision_manual").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_gremision_manual').prop('checked', true);
            $.uniform.update('#opt_avanzadas_gremision_manual');
        }
    } else {
        $("#control_gremision_manual").hide();
    }

    if(opt_avanzadas.opt_avanzadas_lista_usuarios) {
        $("#control_lista_usuarios").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_lista_usuarios').prop('checked', true);
            $.uniform.update('#opt_avanzadas_lista_usuarios');
        }
    } else {
        $("#control_lista_usuarios").hide();
    }

    if(opt_avanzadas.opt_avanzadas_retencion) {
        $("#control_retencion").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_retencion').prop('checked', true);
            $.uniform.update('#opt_avanzadas_retencion');
        }
    } else {
        $("#control_retencion").hide();
    }

    if(opt_avanzadas.opt_avanzadas_etiquetas) {
        $("#control_etiquetas").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_etiquetas').prop('checked', true);
            $.uniform.update('#opt_avanzadas_etiquetas');
        }
    } else {
        $("#control_etiquetas").hide();
    }

    if(opt_avanzadas.opt_avanzadas_igv_sunat) {
        $("#control_igv_sunat").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_igv_sunat').prop('checked', true);
            $.uniform.update('#opt_avanzadas_igv_sunat');
        }
    } else {
        $("#control_igv_sunat").hide();
    }

    if(opt_avanzadas.opt_avanzadas_tiene_anticipos) {
        $("#control_tiene_anticipos").show();
        if(tipo == 'storage') {
            $('#opt_avanzadas_tiene_anticipos').prop('checked', true);
            $.uniform.update('#opt_avanzadas_tiene_anticipos');
        }
    } else {
        $("#control_tiene_anticipos").hide();
    }
}

function agregar_categoria(){
    var light = $("#contenido_nueva_categoria");
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

    var codigo = $("#txt_codigo_categoria").val();
    var nombre = $("#nombre_categoria").val();
    var descripcion = $("#descripcion_categoria").val();
    var codigo_cuenta_contable = $("#codigo_cuenta_contable").val();
    var codigo_centro_costo = $("#codigo_centro_costo").val();
    var codigo_presupuesto = $("#codigo_presupuesto").val();

	$.ajax({
        url :  '/facturacionv8/category/insert',
		method :  'POST',
		data: {codigo: codigo, nombre_categoria: nombre, descripcion_categoria: descripcion, codigo_cuenta_contable: codigo_cuenta_contable, codigo_centro_costo: codigo_centro_costo, codigo_presupuesto: codigo_presupuesto },
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title: data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#00BCD4",   
                confirmButtonText: "Ok",
            }, function() {
                //$('#modal_categoria').modal('hide');
                $(light).unblock();
                get_lista_categorias($("#nuevo_producto_categoria"), data.idcategoria);
                $("#contenido_nueva_categoria").hide();
                $("#contenido_nuevo_producto").show('slide');
            });
        } else {
            swal({   
                title: data.titulo,   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#00BCD4",   
                confirmButtonText: "Ok",
            });
            $(light).unblock();
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#00BCD4",   
			confirmButtonText: "Ok"
        });
        $(light).unblock();
    });
}

function consultar_numero_doc_cliente(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email, input_telefono) {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);
    input_idusuario.val('');
    $("#cliente_api_foto").val('');
    $("#cliente_api_fecha_nac").val('');
    $("#cliente_api_sexo").val('');

    $("#content_cliente_api_foto_src").hide();
    $("#content_razon_social_cliente").css("display", "block");

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

                        if(typeof data.data.fotos !== 'undefined') {
                            if(typeof data.data.fotos.foto !== 'undefined') {
                                if(data.data.fotos.foto != '') {
                                    $("#cliente_api_foto").val(data.data.fotos.foto);
                                    $("#cliente_api_foto_src").attr("src", "data:image/png;base64, " + data.data.fotos.foto);
                                    $("#content_cliente_api_foto_src").show();
                                    $("#content_razon_social_cliente").css("display", "table");
                                } else {

                                }
                            }
                        }

                        if(typeof data.data.fecha_nacimiento !== 'undefined') {
                            if(data.data.fecha_nacimiento != '') {
                                $("#cliente_api_fecha_nac").val(data.data.fecha_nacimiento);
                                var parts = data.data.fecha_nacimiento.split('/'); 
                                let edad = calcular_edad(parts[2] + '-' + parts[1] + '-' + parts[0]);
                                if(edad >= 18) {
                                    $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-success'> (Edad: " + edad + " Años)</strong>");
                                } else {
                                    $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-primary'> (Edad: " + edad + " Años)</strong>");
                                }
                            }
                        }

                        if(typeof data.data.sexo !== 'undefined') {
                            if(data.data.sexo != '') {
                                if(data.data.sexo == 'Femenino') {
                                    $("#cliente_api_sexo").val('FEMENINO');
                                } else {
                                    $("#cliente_api_sexo").val('MASCULINO');
                                }
                            }
                        }

                        if (typeof data.codigo_ubigeo !== 'undefined' && typeof data.texto_ubigeo !== 'undefined') {
                            input_ubigeo.append('<option value="' + data.codigo_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            input_ubigeo.val(data.codigo_ubigeo).trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_telefono.val(data.data.celular);
                        //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                        input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");

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

                        if (typeof data.data.fecha_nac !== 'undefined') {
                            if(data.data.fecha_nac !== null && data.data.fecha_nac !== '') {
                                let edad = calcular_edad(data.data.fecha_nac);
                                if(edad >= 18) {
                                    $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-success'> (Edad: " + edad + " Años)</strong>");
                                } else {
                                    $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-primary'> (Edad: " + edad + " Años)</strong>");
                                }
                            } else {
                                $("#titulo_nombrecliente").html("Nombre del Cliente: ");
                            }
                        } else {
                            $("#titulo_nombrecliente").html("Nombre del Cliente: ");
                        }
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
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_telefono.val(data.data.celular);
                        //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                        input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
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
            } else if(tipo_doc == 0) {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
                    input_email.val(data.data.email);
                    input_telefono.val(data.data.celular);
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    input_telefono.val(data.data.celular);
                    //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
                }
            }

            $("#control_gremision_electronica").hide('slide');
            if (typeof data.lista_guias !== "undefined") {
                if(data.lista_guias.length > 0) {
                    $("#control_gremision_electronica").show('slide');
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

function inicializar_autocompletado() {
    $('#cliente_nombre').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc"],
        searchContain: true, 
        searchIn: 'razon_social',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/herramientas/get_sugerencias_clientes_mejorado'
    }).on("select:flexdatalist", function(event, data) {
        $("#cliente_numerodocumento").val(data.num_doc);
        $(".search_document").trigger("click");
    });

    $('#cliente_numerodocumento').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["razon_social", "num_doc"],
        searchContain: true,
        searchIn: 'num_doc',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/herramientas/get_sugerencias_clientes_mejorado'
    }).on("select:flexdatalist", function(event, data) {
        $("#cliente_numerodocumento").val(data.num_doc);
        $(".search_document").trigger("click");
    });

    $('#cliente_numerodocumento-flexdatalist').prop('type', 'number');
    $('#cliente_numerodocumento-flexdatalist').prop('inputmode', 'numeric');
    $('#cliente_numerodocumento-flexdatalist').prop('pattern', '[0-9]*');

    $('#cliente_numerodocumento-flexdatalist').keyup(function(e){
        if(e.keyCode == 13) {
            $(".search_document").trigger("click");
        }
    });
}

function calcular_precio_sin_igv() {
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var factor_igv = get_igv_sunat();

    var igv_percent = parseFloat(factor_igv + 1);
    if($('#producto_preciounidad').val() == '' || $('#producto_preciounidad').val() <= 0 || isNaN($('#producto_preciounidad').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = round_math(parseFloat($('#producto_preciounidad').val()), numero_decimales);
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), numero_decimales);
    $("#producto_preciounidad_sin_igv").val(precio_sin_igv);

}

function calcular_precio_con_igv() {
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var factor_igv = get_igv_sunat();
    
    var igv_percent = parseFloat(factor_igv + 1);
    if($('#producto_preciounidad_sin_igv').val() == '' || $('#producto_preciounidad_sin_igv').val() <= 0 || isNaN($('#producto_preciounidad_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = round_math(parseFloat($('#producto_preciounidad_sin_igv').val()), numero_decimales);
    }
    
    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), numero_decimales);
    $("#producto_preciounidad").val(precio_sin_igv);

}

function inicializar_controles() {

    // Switchery
    // ------------------------------

    // Initialize multiple switches
    var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });

    // Checkboxes/radios (Uniform)
    // ------------------------------

    // Default initialization
    $(".styled").uniform();

    // File input
    $(".file-styled").uniform({
        wrapperClass: 'bg-blue',
        fileButtonHtml: '<i class="icon-file-plus"></i>'
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

}

function get_data_documento() {
    var tipo_doc = $("#tipo_docelectronico_modificar").val();
    var serie_numero = $("#serie_numero_doc_modificar").val();
    var array_serie_numero = serie_numero.split(',');
    var serie = array_serie_numero[0];
    var numero = array_serie_numero[1];
    var light = $("#cuerpo_comprobante");
    get_data_documento_electronico(tipo_doc, serie, numero, light);
}

function calcular_totales_documento() {
    var factor_igv = get_igv_sunat();
    var totales_items = get_totales_items(factor_igv);
    actualizar_campos_resumen(totales_items);

    var codigo_detraccion = calcular_porcentaje_detraccion();
    if(codigo_detraccion != '0') {
        $("#detraccion_codigo_bien").val(codigo_detraccion).trigger('change');
    }

    $("#total_recibido").trigger('change');
    $("#btn_eliminar_lista_cuotas").trigger('click');
    $("#select_aplica_retencion").trigger("change");

    valida_si_tiene_anticipos();
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

        //haciendo el redondeo al importe al parecer calcula correctamente salvo por ciertos comprobantes que calcula por decimales un tanto errado
        //quizás una solución sea redondear según los decimales que trabaje el contribuyente.
        var importe_item = round_math(cantidad_item*precio_item, 2);
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

        //console.log('importe_item (' + (i+1) + ') - ' + id, importe_item);
        
        //tee4a4|.|.|152110  
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
    
    var total_gravado_original = total_gravado;
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
    total_igv = total_gravado*factor_igv; //con esta operación tenemos un pequeño error.
    //total_igv = round_math(total_gravado_original, 2) - round_math(total_gravado, 2);

    /*
    console.log("TOTAL GRAVADO => ", total_gravado);
    console.log("TOTAL GRAVADO REDONDEADO => ", round_math(total_gravado, 2));
    console.log("TOTAL IGV", total_igv);
    console.log("total_gravado_original", total_gravado_original);
    */

    total_a_pagar = total_gravado + total_exonerado + total_inafecto + total_exportacion + total_igv + otros_cargos + total_icbper;
    
    /*
    console.log(
        "total_gravado:", round_math(total_gravado, 2),
        "total_exonerado:", round_math(total_exonerado, 2),
        "total_inafecto:", round_math(total_inafecto, 2),
        "total_gratuito:", round_math(total_gratuito, 2),
        "total_exportacion:", round_math(total_exportacion, 2),
        "total_icbper:", round_math(total_icbper, 2),
        "sub_total_ventas:", round_math(sub_total_ventas, 2),
        "total_igv:",  round_math(total_igv, 2),
        "total_a_pagar:", round_math(total_a_pagar, 2),
        "desc_total_imponible:", round_math(desc_total_imponible, 2),
        "desc_total_monto:", round_math(descuento.descuento_monto, 2),
        "otros_cargos:", round_math(otros_cargos, 2)
    );
    */
   
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
            $('#txt_descuento_porcentaje').val(round_math(descuento_factor*100, 5));
        }
    }

    return {
        descuento_factor: descuento_factor,
        descuento_monto: descuento_monto
    }
}

function actualizar_campos_resumen(totales) {
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
    //$("#txt_monto_adeudado").val(round_math(totales.total_a_pagar, 2));
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
}

function get_igv_sunat() {
    var select_igv_sunat = parseFloat($("#select_igv_sunat").val());
    return select_igv_sunat / 100;
}

function get_data_producto() {
    var idproducto = $("#select_producto_buscar").val();
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var factor_igv = get_igv_sunat(); //igv (decimal)

    var light = $("#contenido_vm_agregar_articulo");
    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Buscando Producto ...</p>',
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

    var id_cliente_documento = $("#id_cliente_documento").val();

    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto, idcliente: id_cliente_documento},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#producto_idproducto").val(data.producto.idproducto);
            $("#producto_descripcion").val(data.producto.nombre);
            //$("#producto_unidadmedida").val(data.producto.id_unidad_medida);
            $("#producto_tipo_afect_igv").val(data.producto.id_tipoafectacionigv).trigger("change").trigger("select2:select");

            if(!data.producto.id_cod_detraccion) {
                $("#content_msg_percepcion").hide();
                $("#item_detraccion_codigo").val('');
                $("#item_detraccion_porcentaje").val('');
            } else {
                //tiene detracción
                $("#content_msg_percepcion").show('slide');
                $("#content_msg_percepcion").html('<i class="icon-info3 text-size-mini position-left"></i> Producto con Detracción: ' + data.detraccion.porcentaje + '%');
                $("#item_detraccion_codigo").val(data.producto.id_cod_detraccion);
                $("#item_detraccion_porcentaje").val(data.detraccion.porcentaje);
            }
            
            if(data.producto.id_unidad_medida != 20) {
                $("#stock_actual").val(data.producto.stock);
            }
            
            if($("#c_restriccion_stock").val() == 'si') {
                if(data.producto.id_unidad_medida != 20) {
                    $("#msg_stock_actual_html").html(' <span class="text-info">(Stock: ' + data.producto.stock + ' ' + data.unidad_medida.simbolo + ')</span>');
                    $("#msg_stock_actual_html").show('slide');
                }
            } else {
                $("#msg_stock_actual_html").hide();
            }

            $("#txt_unidad_medida_producto").html('(' + data.unidad_medida.simbolo + ')');
            
            var tipo_moneda = $("#codmoneda_comprobante").val();
            var valor_sin_igv = round_math(parseFloat(data.producto.valor_sin_igv), numero_decimales);
            var valor_con_igv = round_math((parseFloat(data.producto.valor_con_igv))*(factor_igv + 1), numero_decimales);
            //var valor_con_igv = round_math(parseFloat(data.producto.valor_con_igv), numero_decimales); (esta línea de código extrae el valor que se encuentra en la BD)
            var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);

            if(tipo_moneda == 'USD') {
                if(data.producto.id_cod_moneda == 'PEN') {
                    valor_con_igv = round_math(parseFloat(valor_con_igv/tipo_cambio), numero_decimales);
                    valor_sin_igv = round_math(parseFloat(valor_sin_igv/tipo_cambio), numero_decimales);
                }
            }

            if(tipo_moneda == 'PEN') {
                if(data.producto.id_cod_moneda == 'USD') {
                    valor_con_igv = round_math(parseFloat(valor_con_igv*tipo_cambio), numero_decimales);
                    valor_sin_igv = round_math(parseFloat(valor_sin_igv*tipo_cambio), numero_decimales);
                }
            }

            if(data.producto.id_tipoafectacionigv == "10" || data.producto.id_tipoafectacionigv == "7152") {
                $("#producto_preciounidad").val(valor_con_igv);
                $("#producto_preciounidad_sin_igv").val(valor_sin_igv);
            } else {
                $("#producto_preciounidad").val(valor_sin_igv);
            }

            if(data.producto.afecto_icbper == 'si') {
                $('#opcion_afecto_icbper').bootstrapSwitch('state', true);
            } else {
                $('#opcion_afecto_icbper').bootstrapSwitch('state', false);
            }

            $(".content_propiedades_producto").show();

            $("#id_cod_moneda").val(tipo_moneda);
            //$("#producto_preciounidad").val(valor_con_igv);
            $("#producto_cantidad").focus().val(1).select();
            calcular_totales_producto();//$(".totales_input").trigger("input");
            $("#historial_costos").data('bs.popover').options.content = data.html_costos;

            $("#contenido_listaprecios").html('');
            var html_precios = '';
            data.lista_precios.forEach(function(precio_item, index) {
                var simbolo_moneda_lp = 'S/ ';
                if(precio_item.moneda == 'USD') {
                    simbolo_moneda_lp = '$ ';
                }
                html_precios = html_precios + '\
                <li><a href="javascript:void(0);" data-precio="'+precio_item.precio+'" onclick="asignar_precio_de_lista('+precio_item.precio+',' + "'" + precio_item.moneda + "'" + ')"><span class="badge badge-success pull-right">' + simbolo_moneda_lp + ' ' + precio_item.precio + '</span> ' + precio_item.nombre + '</a></li>\
                ';
            });
            
            $("#producto_preciounidad").addClass("eliminar_radio_input");
            if(data.producto.multi_precio == 'si') {
                if(html_precios != '') {
                    $("#contenido_listaprecios").html(html_precios);
                    $("#btn_listaprecios").show();
                } else {
                    $("#btn_listaprecios").hide();
                    //$("#producto_preciounidad").removeClass("eliminar_radio_input");
                }
            } else {
                $("#btn_listaprecios").hide();
            }

            if(data.html_ultimo_precio != '') {
                $("#contenido_listaprecios").html($("#contenido_listaprecios").html() + data.html_ultimo_precio);
                $("#btn_listaprecios").show();
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
                    "data-idcodmoneda" : data.producto.id_cod_moneda,
                    "data-preciominimo": data.producto.precio_venta_minimo
                })
            );

            data.presentaciones.forEach(function(presentacion, indice, array) {
                var presentacion_seleccionar = false;
                if(ultimo_texto_buscado.toLowerCase() == presentacion.codigo.toLowerCase()) {
                    presentacion_seleccionar = true;
                }

                if(factor_igv == 0.18) {
                    var presentacion_precio_sin_igv = round_math(parseFloat(presentacion.precio_sin_igv), numero_decimales);
                    var presentacion_precio_con_igv = round_math(parseFloat(presentacion.precio_con_igv), numero_decimales);
                } else {
                    var presentacion_precio_sin_igv = round_math(parseFloat(presentacion.precio_sin_igv), numero_decimales);
                    var presentacion_precio_con_igv = round_math((parseFloat(presentacion.precio_sin_igv))*(1 + factor_igv), numero_decimales);   
                }

                if(presentacion_seleccionar) {
                    var id_tipoafectacionigv = parseInt(data.producto.id_tipoafectacionigv) + 0;
                    if(id_tipoafectacionigv == 10) {
                        //asignar_precio_de_lista(presentacion.precio_con_igv, presentacion.id_cod_moneda);
                        asignar_precio_de_lista(presentacion_precio_con_igv, presentacion.id_cod_moneda);
                    } else {
                        //asignar_precio_de_lista(presentacion.precio_sin_igv, presentacion.id_cod_moneda);
                        asignar_precio_de_lista(presentacion_precio_sin_igv, presentacion.id_cod_moneda);
                    }
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
                        "data-precioconigv": presentacion_precio_con_igv,
                        "data-preciosinigv": presentacion_precio_sin_igv,
                        "data-idcodmoneda" : presentacion.id_cod_moneda,
                        "data-preciominimo": presentacion.precio_minimo
                    })
                );
            });

            $("#producto_unidadmedida").trigger("change").trigger("select2:select"); //necesario para que agarre el precio del producto seleccionado

            $(".ver_stock_varias_sucursales").attr('onclick', 'ver_stock_varias_sucursales(' + "'" + data.producto.codigo + "'" + ','+ $(".select_sucursal").val() +')');

            $(light).unblock();
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

function asignar_precio_de_lista(precio, moneda = 'PEN') {
    precio = parseFloat(precio);
    var tipo_moneda_documento = $("#codmoneda_comprobante").val();
    var tipo_cambio = $("#tipo_cambio_comprobante").val();

    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }
    
    if(tipo_moneda_documento == 'USD') {
        if(moneda == 'PEN') {
            precio = round_math(parseFloat(precio)/parseFloat(tipo_cambio), num_decimales);
        }
    } else {
        if(moneda == 'USD') {
            precio = round_math(parseFloat(precio)*parseFloat(tipo_cambio), num_decimales);
        }
    }

    $("#producto_preciounidad").val(precio);
    if(precio <= 0) {
        $("#producto_preciounidad").val(0);
    }
    
    calcular_totales_producto();
    $("#producto_preciounidad").trigger('input');
}

function inicializar_buscar_productos_registrados() {
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
  
function inicializar_buscador() {

    inicializar_buscar_productos_registrados();
    
    $("#serie_numero_doc_modificar").select2({
        language: "es",
        ajax: {
          type: "post",
          url: "/facturacionv8/herramientas/get_sugerencias_docelectronico",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page,
              id_tipodoc: $("#tipo_docelectronico_modificar").val()
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
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 3/*,
        tags: true*/
    });
}

function calcular_totales_producto() {

    var factor_igv = get_igv_sunat();

    var igv_percent = parseFloat(factor_igv + 1);
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

    var stock_actual = 0;
    if($('#stock_actual').val() == '' || $('#stock_actual').val() <= 0 || isNaN($('#stock_actual').val())) {
        stock_actual = 0;
    } else {
        stock_actual = parseFloat($('#stock_actual').val());
    }
    
    $("#msg_stock_insuficiente").hide();
    if($("#c_restriccion_stock").val() == 'si' && cantidad > stock_actual) {
        console.log("cantidad => ", cantidad);
        console.log("stock_actual => ", stock_actual);
        if($("#producto_unidadmedida").val() == '20' || $("#producto_unidadmedida").val() == 'UND-20') {
            $("#msg_stock_insuficiente").hide();
        } else {
            $("#msg_stock_insuficiente").show();
        }
    } else {
        $("#msg_stock_insuficiente").hide();
    }

    var total = round_math(parseFloat(precioarticulo) * parseFloat(cantidad), 2);
    var subtotal = parseFloat(total) / parseFloat(igv_percent);
    var igv = parseFloat(total) - parseFloat(subtotal);

    var id_tipoafectacionigv = parseInt($('#producto_tipo_afect_igv').val()) + 0;
    if(id_tipoafectacionigv == 10 || id_tipoafectacionigv == 7152) {
        $("#text_precio_inc_igv").html('Precio/Uni(Inc.IGV)');
        $("#content_preciounidad_inc_igv").removeClass();
        $("#content_preciounidad_sin_igv").show();

        $("#content_preciounidad_inc_igv").attr('class', 'form-group col-md-5 col-xs-12');
    } else {
        //$("#producto_preciounidad_sin_igv").val($('#producto_preciounidad').val());
        subtotal = total;
        igv = 0;
        $("#text_precio_inc_igv").html('Precio Unitario');
        $("#content_preciounidad_inc_igv").removeClass();
        $("#content_preciounidad_sin_igv").hide();

        $("#content_preciounidad_inc_igv").attr('class', 'form-group col-md-9 col-xs-12');
    }
    
    $('#producto_subtotal').val(round_math(subtotal, 2));
    $('#producto_igv').val(round_math(igv, 2));
    $('#producto_total').val(round_math(total, 2));
    let total_documento = $("#txt_total_comprobante").val();
    let id_row_selected = $("#key_row").val();

    if(id_row_selected != '') {
        let monto_a_eliminar = get_total_row_en_lista(id_row_selected);
        $("#total_documento_2").html( round_math(parseFloat(total) + parseFloat(total_documento) - parseFloat(monto_a_eliminar), 2));
    } else {
        $("#total_documento_2").html( round_math(parseFloat(total) + parseFloat(total_documento), 2));
    }
}

function get_lista_almacenes(select, seleccionar = 'no') {
	$.ajax({
        url : '/facturacionv8/herramientas/get_lista_sucursales',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            let n = 0;
			$.each(data.lista, function(key, item) {
                n++;
				select.append('<option value="' + item.idsucursal + '">Almacen para la Sucursal: "' + item.nombre + '", Con ID: ' + item.idsucursal + '</option>');
            });
            if(seleccionar == 'no' || data.idsucursal_usuario == null) {
                select.trigger("change").trigger("select2:select");
            } else {
                select.val(data.idsucursal_usuario).trigger("change").trigger("select2:select");
            }
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
	
	$.ajax({
        url : '/facturacionv8/documentoelectronico/get_data_doc',
		method :  'POST',
		data: {id_tipodocumento: id_tipodocumento, numero_comprobante: numero_comprobante, nombre_nuevo_doc: nombre_nuevo_doc, serie_comprobante: serie_comprobante},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			$(light).unblock();

			$("#cliente_tipo_docidentidad").val(data.cliente.id_tipodocidentidad).trigger("change").trigger("select2:select");
			$("#cliente_numerodocumento").val(data.cliente.num_doc);
			$("#cliente_nombre").val(data.cliente.razon_social);
			$("#cliente_direccion").val(data.cliente.direccion_fiscal);
            
            if (typeof data.cliente.celular !== 'undefined') {
                $("#numero_celular").val(data.cliente.celular);
            }

            if (typeof data.cliente.email !== 'undefined') {
                $("#cliente_email").val(data.cliente.email);
            }

            if (typeof data.cliente.id_cod_ubigeo !== 'undefined' && typeof data.texto_ubigeo !== 'undefined') {
                $("#select_codigoubigeo").append('<option value="' + data.cliente.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                $("#select_codigoubigeo").val(data.cliente.id_cod_ubigeo).trigger("select2:select");
            }

            if (typeof data.comprobante.id_tipo_operacion !== 'undefined') {
                $("#tipo_operacion_docelectronico").val(data.comprobante.id_tipo_operacion).trigger("change").trigger("select2:select");
            }

            if (typeof data.comprobante.porcentaje_descuento_total !== 'undefined') {
                $("#txt_descuento_porcentaje").val(data.comprobante.porcentaje_descuento_total);
            }
            
            if(data.comprobante.nro_otr_comprobante != '' || data.comprobante.transporte_nro_placa != '' || data.comprobante.id_codigomoneda == 'USD') {
                $("#nro_placa_vehiculo").val(data.comprobante.transporte_nro_placa);
                $("#nro_orden").val(data.comprobante.nro_otr_comprobante);

                if(data.comprobante.nro_otr_comprobante != '') {
                    $("#label_nro_orden").addClass('text-info');
                }

                if(data.comprobante.transporte_nro_placa != '') {
                    $("#label_nro_placa_vehiculo").addClass('text-info');
                }
            }

            if(data.comprobante.id_codigomoneda == 'USD') {
                $("#codmoneda_comprobante").val('USD').trigger("change").trigger("select2:select");
                $("#tipo_cambio_comprobante").val(data.comprobante.tipo_cambio_sunat);
            }

			limpiar_jq_grid(jQuery('#detalle_documento'));

            $("#producto_preciounidad").addClass("eliminar_radio_input");
			data.data_grid.forEach( function(data_item, indice, array) {
				jQuery('#detalle_documento').addRowData(indice + 1, data_item, 'last');
			});

            $("#observacion_documento").val(data.comprobante.nota);
            
            $("#select_igv_sunat").val(parseInt(data.comprobante.porcentaje_igv)).trigger("change");  //siempre tiene que ir al final de calcular totales
            calcular_totales_documento();

            //ERROR: AL PARECER EL SISTEMA CARGA LUEGO LA SUCURSAL Y ASIGNA EL PORCENTAJE. NO DEBERÍA SER: IDEA, SI ES UN COMPROBANTE GUARDADO ENTONCES QUE NO CAMBIE PORCENTAJE.
            //TAMBIÉN AL MOMENTO DE HACER OTRO DOCUMENTO O COPIAR UN DOCUMENTO, SE DEBE CAMBIAR EL ID DEL DOC AL DOCUMENTO CORRECTO.
            //SE QUEDA EL ID EN ID_DOC_GUARDADO
            get_lista_sucursales_doc($("#select_sucursal"), 'si', data.comprobante.id_sucursal);
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

function get_lista_sucursales_doc(select, seleccionar = 'no', id_sucursal = '') {
    select.find('option').remove().end();
    //console.log('id_sucursal: ' + id_sucursal, 'seleccionar: ' + seleccionar);
	$.ajax({
        url : '/facturacionv8/herramientas/get_lista_sucursales',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.idsucursal + '">' + item.nombre + ' (ID: ' + item.idsucursal +')' + '</option>');
            });
            if(seleccionar == 'no' || data.idsucursal_usuario == null) {
                select.trigger("change").trigger("select2:select");
            } else {
                if(id_sucursal != '') {
                    select.val(id_sucursal).trigger("change").trigger("select2:select");
                } else {
                    select.val(data.idsucursal_usuario).trigger("change").trigger("select2:select");
                }
            }
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

function calcular_vuelto() {
    let pago_parcial = parseFloat($("#pago_parcial").val());
    let total = parseFloat($("#txt_total_comprobante").val());
    var tipo_operacion = $("#tipo_operacion_docelectronico").val();
    if(tipo_operacion == '1001' || tipo_operacion == '1002' || tipo_operacion == '1003' || tipo_operacion == '1004') {
        var tipo_moneda = $("#codmoneda_comprobante").val();
        if(tipo_moneda == 'USD') {
            total = total - parseFloat($("#monto_dolares_detraccion").val());
        } else {
            total = total - parseFloat($("#monto_detraccion").val());
        }
    }

    if($("#select_aplica_retencion").val() == 'si') {
        if(tipo_moneda == 'USD') {
            var monto_retencion = parseFloat($("#monto_dolares_retencion").val());
            total = total - monto_retencion;
        } else {
            var monto_retencion = parseFloat($("#monto_retencion").val());
            total = total - monto_retencion;
        }
    }
    
    let total_recibido = parseFloat($("#total_recibido").val());
    let total_vuelto = 0;
    var valor_tipo_venta = $("#opcion_tipo_venta").bootstrapSwitch('state');

    if(pago_parcial == '' || pago_parcial <= 0 || isNaN(pago_parcial)) {
        pago_parcial = 0;
    }

    if(total == '' || total <= 0 || isNaN(total)) {
        total = 0;
    }

    if(total_recibido == '' || total_recibido <= 0 || isNaN(total_recibido)) {
        total_recibido = 0;
    }

    if(!valor_tipo_venta) {
        
    } else {
        if(pago_parcial < total) {
            total = pago_parcial;
        }
    }
    
    if(total_recibido == '' || total_recibido <= 0 || isNaN(total_recibido)) {
        total_vuelto = 0;
    } else {
        if(total_recibido >= total) {
            total_vuelto = total_recibido - total;
        } else {
            total_vuelto = 0;
        }
    }

    $("#total_vuelto").val(round_math(total_vuelto, 2));
}

function cambiar_fecha_credito(num_dias) {
    var days = parseInt(num_dias);
    $('#fecha_pago_comprobante').data('daterangepicker').setStartDate(moment().add(days, 'days'));
}