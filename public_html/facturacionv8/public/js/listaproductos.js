var dataTable_lista_productos;
var btn_productos_accion = '';
var editor;
var dataTableFilterTimeout;
var dataTableFilterWait = 900;

$(function() {
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Escribe para Filtrar...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });

    editor = new $.fn.dataTable.Editor( {
        ajax: "/facturacionv8/producto/update_cell",
        table: "#tbl_lista_productos",
        fields: [
            { "label": "codigo",                "name": "codigo", type: 'text'  },
            { "label": "nombreproducto",        "name": "nombreproducto", type: 'textarea' },
            { "label": "precio_venta",          "name": "precio_venta", type: 'text' },
            { "label": "precio_compra",          "name": "precio_compra", type: 'text' },
            { "label": "nota", "name":          "nota", type: 'textarea' },
            { "label": "stock_minimo",          "name": "stock_minimo", type: 'text' },
    
            {
                name: "afecto_icbper",
                type: "select",
                options: [
                      { label: 'SI', value: 'si' },
                      { label: 'NO', value: 'no' }
                ]
            },
            
            { "label": "fecha_vencimiento",     "name": "fecha_vencimiento", type: "date" },
            { "label": "marca",                 "name": "marca", type: 'text' }
    
        ]
    });
    
    $('#tbl_lista_productos').on( 'click', 'tbody td.row_editable', function (e) {
        editor.inline( dataTable_lista_productos.cell( this ).index(), {
            onBlur: 'submit'
        } );
    } );

    editor.on('postSubmit', function ( e, json, data )  {
        if(json.respuesta == 'error') {
            swal({   
                title: json.titulo,
                text: json.mensaje,
                html: true,
                type: "info",   
                confirmButtonColor: "#563d7c",   
                confirmButtonText: "OK"
            }, function(){  
                
            });
        }
    });
    
    get_lista_unidades_medida($("#nuevo_producto_unidad"));
	get_lista_monedas($("#nuevo_producto_moneda"));
	get_lista_tipoafectacionigv($("#nuevo_producto_tipo_afect_igv")); 
	get_lista_categorias($("#nuevo_producto_categoria"));
    $(".btn_guardar_nuevo_producto").click(guardar_nuevo_producto);
    
    $(".new_prod_inputs_selected").click(function() {
        $(this).select();
    });
    
    $("#nuevo_producto_moneda").on("change", function() {
        let id_cod_moneda = $("#nuevo_producto_moneda").val();
        if(id_cod_moneda == 'USD') {
            $(".simbolo_moneda_nuevoproducto").html('USD');
            $("#content_tipo_cambio").show();

            $("#content_precio_compra").attr('class', 'col-md-3 col-xs-6');
            $("#content_ganancia_maxima").attr('class', 'col-md-3 col-xs-6');
            $("#content_ganancia_minima").attr('class', 'col-md-3 col-xs-6');
        } else {
            $(".simbolo_moneda_nuevoproducto").html('S/');
            $("#content_tipo_cambio").hide();

            $("#content_precio_compra").attr('class', 'col-md-4 col-xs-6');
            $("#content_ganancia_maxima").attr('class', 'col-md-4 col-xs-6');
            $("#content_ganancia_minima").attr('class', 'col-md-4 col-xs-6');
        }
        $("#nuevo_producto_nombre_servicio").trigger('input');
	});
	
	$(".btn_agregarproducto").click(function() {
        $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
        $("#txt_porcentaje_ganancia").prop("disabled", true);

        let idsucursal = parseInt($("#select_almacen_mostrar").val());
        if(idsucursal <= 0) {
            swal({   
                title: 'Selecciona un Almacén!',   
                text: 'Actualmente tu está mirando los productos de todos los almacenes, debes seleccionar uno en específico donde deseas registrar el producto.',
                html: true,
                type: "info",   
                confirmButtonColor: "#563d7c",   
                confirmButtonText: "OK"
            }, function(){  
                return false;
            });
        } else {
            $(".campos_no_editables").show();
            limpiar_campos();
            $("#vm_agregar_articulo").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
	});
	
	$('.select').select2({ minimumResultsForSearch: -1 });
    $('.select_with_search').select2();
    $("#nuevo_producto_categoria").select2({
        language: "es",
        dropdownParent: $('#vm_agregar_articulo')
    });

    $("#nuevo_producto_unidad").select2({
        language: "es",
        dropdownParent: $('#vm_agregar_articulo')
    });

    $(".info_sucursal_seleccionada").html('<i class="icon-info3 text-size-mini position-left"></i> Sucursal: ' + $("#select_almacen_mostrar option:selected").text());
    $(".info_texto_almacen").html('El Producto Será Registrado en el almacén para la sucursal: ' + $("#select_almacen_mostrar option:selected").text());
    $(".info_texto_almacen_2").html('Todos los productos que se ingresen en el excel, serán registrados en el almacén para la sucursal: ' + $("#select_almacen_mostrar option:selected").text());
    get_lista_productos($("#select_almacen_mostrar").val());
    get_tipocambio_by_date($("#new_producto_tipodecambio"));

    //get_lista_almacenes($("#select_almacen_mostrar"), 'si', 'opcion_todos');
    //get_lista_almacenes($("#select_almacen"), 'si', 'individual');
    $("#select_almacen_mostrar").on('change', function() {
        $(".info_sucursal_seleccionada").html('<i class="icon-info3 text-size-mini position-left"></i> Sucursal: ' + $("#select_almacen_mostrar option:selected").text());
        $(".info_texto_almacen").html('El Producto Será Registrado en el almacén para la sucursal: ' + $("#select_almacen_mostrar option:selected").text());
        $(".info_texto_almacen_2").html('Todos los productos que se ingresen en el excel, serán registrados en el almacén para la sucursal: ' + $("#select_almacen_mostrar option:selected").text());
        get_lista_productos($("#select_almacen_mostrar").val(), $("#select_tipo_item").val());
    });

    $("#select_tipo_item").on('change', function() {
        get_lista_productos($("#select_almacen_mostrar").val(), $("#select_tipo_item").val());
    });

    $("#nuevo_producto_unidad").on('change', function() {
        if($("#nuevo_producto_unidad").val() == '20') {
            $("#nuevo_producto_stock").prop("disabled", true);
            $("#nuevo_producto_stock_minimo").prop("disabled", true);
            $("#nuevo_producto_precio_compra").prop("disabled", true);
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
            $("#txt_marca_producto").prop("disabled", true);
            $("#txt_marca_producto-flexdatalist").prop("disabled", true);
            $("#txt_fecha_vencimiento").prop("disabled", true);
            $("#content_icbper").hide('slide'); 

            $(".campos_para_producto").hide();
        } else {
            $("#nuevo_producto_stock").prop("disabled", false);
            $("#nuevo_producto_stock_minimo").prop("disabled", false);
            $("#nuevo_producto_precio_compra").prop("disabled", false);
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", false);
            $("#txt_porcentaje_ganancia").prop("disabled", false);
            $("#txt_marca_producto").prop("disabled", false);
            $("#txt_marca_producto-flexdatalist").prop("disabled", false);
            $("#txt_fecha_vencimiento").prop("disabled", false);
            $("#content_icbper").show('slide');

            $(".campos_para_producto").show('slide');
        }
        $("#nuevo_producto_nombre_servicio").trigger('input');
    });

    $(".btn_importarproductos").click(function() {
        let idsucursal = parseInt($("#select_almacen_mostrar").val());
        if(idsucursal <= 0) {
            swal({   
                title: 'Selecciona un Almacén!',   
                text: 'Actualmente tu está mirando los productos de todos los almacenes, debes seleccionar uno en específico donde deseas registrar el producto.',
                html: true,
                type: "info",   
                confirmButtonColor: "#563d7c",   
                confirmButtonText: "OK"
            }, function(){  
                return false;
            });
        } else {
            $("#vm_importar_articulo").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    $(".btn_actulizarenlote").click(function() {
        let idsucursal = parseInt($("#select_almacen_mostrar").val());
        if(idsucursal <= 0) {
            swal({   
                title: 'Selecciona un Almacén!',   
                text: 'Actualmente tu está mirando los productos de todos los almacenes, debes seleccionar uno en específico donde deseas registrar el producto.',
                html: true,
                type: "info",   
                confirmButtonColor: "#563d7c",   
                confirmButtonText: "OK"
            }, function(){  
                return false;
            });
        } else {
            $("#actualizacion_nombre_sucursal").html($("#select_almacen_mostrar option:selected").text());
            $("#enlace_descarga_productos").attr('href', '/facturacionv8/producto/export_to_update/' + idsucursal);
            $("#vm_actualizacion_productos").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    $(".file-styled").uniform({
        fileButtonClass: 'action btn btn-primary'
    }); 
 
    $("#btn_importar_data").click(iniciar_proceso_importacion);
    $("#btn_actualizar_data").click(iniciar_proceso_actualizacion);

    $("#btn_agregar_stock").click(function(){
        $("#contenedor_cuadros_stocks").hide();
        $("#select_producto_buscar").empty();
        $("#text_cambio_stock").html('');
        $("#stock_actual_producto").val('');
        $("#nuevo_stock_producto").val('');
        $("#nota_ingreso").val('');
        let idsucursal = parseInt($("#select_almacen_mostrar").val());
        if(idsucursal <= 0) {
            swal({   
                title: 'Selecciona un Almacén!',   
                text: 'Actualmente tu está mirando los productos de todos los almacenes, debes seleccionar uno en específico donde deseas registrar el producto.',
                html: true,
                type: "info",   
                confirmButtonColor: "#563d7c",   
                confirmButtonText: "OK"
            }, function(){  
                return false;
            });
        } else {
            $("#vm_agregar_stock").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    $("#btn_disminuir_stock").click(function(){
        $("#contenedor_cuadros_stocks_2").hide();
        $("#select_producto_buscar_2").empty();
        $("#text_cambio_stock_2").html('');
        $("#stock_actual_producto_2").val('');
        $("#nuevo_stock_producto_2").val('');
        $("#observacion_disminuirstock").val('');
        let idsucursal = parseInt($("#select_almacen_mostrar").val());
        if(idsucursal <= 0) {
            swal({   
                title: 'Selecciona un Almacén!',   
                text: 'Actualmente tu está mirando los productos de todos los almacenes, debes seleccionar uno en específico donde deseas registrar el producto.',
                html: true,
                type: "info",   
                confirmButtonColor: "#563d7c",   
                confirmButtonText: "OK"
            }, function(){  
                return false;
            });
        } else {
            $("#vm_disminuir_stock").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    inicializar_buscador_producto();
    $('#select_producto_buscar').on('change', get_data_producto);
    $('#select_producto_buscar_2').on('change', get_data_producto_2);
    $('#select_producto_buscar_3').on('change', get_data_producto_3);

    $("#nuevo_stock_producto").on('input', function() {
        interaccion_stocks_aumentar();
    }).on('change', function() {
        interaccion_stocks_aumentar();
    });

    $("#nuevo_stock_producto_2").on('input', function() {
        interaccion_stocks_disminuir();
    }).on('change', function() {
        interaccion_stocks_disminuir();
    });

    $("#btn_registrar_salida").click(registrar_salida_producto);
    $("#btn_registrar_entrada").click(registrar_entrada_producto);

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

    $(".btn_generar_codigo_presentacion").click(function() {
        generar_codigo($("#presentacion_codigo"), 7, $(".btn_generar_codigo_presentacion > i"));
    });

    $(".btn_generar_codigo").click(function(){
		generar_codigo($("#nuevo_producto_codigo"), 7, $(".btn_generar_codigo > i"));
    });

    $(".btn_guardar_nueva_categoria").click(agregar_categoria);

    $('#nuevo_producto_tipo_afect_igv').on('change', function() {
        var id_tipoafectacionigv = parseInt($('#nuevo_producto_tipo_afect_igv').val()) + 0;
        if(id_tipoafectacionigv != 10 && id_tipoafectacionigv != 7152) {
            $("#text_precio_inc_igv").html('Precio Unitario de Venta');
            $("#content_preciounidad_inc_igv").removeClass();
            $("#content_preciounidad_sin_igv").hide();

            $("#content_preciounidad_inc_igv").attr('class', 'col-md-6 col-xs-12');
        } else {
            $("#text_precio_inc_igv").html('PrecioVenta(Inc.IGV)');
            $("#content_preciounidad_inc_igv").removeClass();
            $("#content_preciounidad_sin_igv").show();

            $("#content_preciounidad_inc_igv").attr('class', 'col-md-3 col-xs-6');
        }
        $("#nuevo_producto_nombre_servicio").trigger('input');
    });

    $(".switch").bootstrapSwitch();
    

    //INICIO MODIFICACION DE CALCULOS AUTOMÁTICOS.
    //precio de compra
    $("#nuevo_producto_precio_compra").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        var precio_compra = parseFloat($("#nuevo_producto_precio_compra").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
        }

        if(precio_compra > 0) {
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", false);
            $("#txt_porcentaje_ganancia").prop("disabled", false);
        }

        var porcentaje_ganancia_maxima = parseFloat($("#txt_porcentaje_ganancia_maxima").val());
        if(isNaN(porcentaje_ganancia_maxima) || porcentaje_ganancia_maxima == '' || typeof porcentaje_ganancia_maxima === undefined){
            porcentaje_ganancia_maxima = 0;
        }

        var porcentaje_precio_minimo = parseFloat($("#txt_porcentaje_ganancia").val()); //porcentaje de ganancia mínimo
        if(isNaN(porcentaje_precio_minimo) || porcentaje_precio_minimo == '' || typeof porcentaje_precio_minimo === undefined){
            porcentaje_precio_minimo = 0;
        }

        if(porcentaje_ganancia_maxima == 0) {
            $("#txt_porcentaje_ganancia_maxima").val(0);
            $("#nuevo_producto_valor_con_igv").val(precio_compra);
            $("#nuevo_producto_valor_sin_igv").val(round_math(precio_compra/1.18, numero_decimales));
        } else {
            var precio_venta = round_math(precio_compra*(1 + porcentaje_ganancia_maxima/100), numero_decimales);
            var precio_venta_sin_igv = round_math(precio_venta/1.18, numero_decimales);

            $("#nuevo_producto_valor_con_igv").val(precio_venta);
            $("#nuevo_producto_valor_sin_igv").val(precio_venta_sin_igv);
        }

        if(porcentaje_precio_minimo == 0) {
            $("#txt_porcentaje_ganancia").val(0); //porcentaje de ganancia mínimo
            $("#nuevo_producto_preciominimo").val(precio_compra);
        } else {
            var precio_venta_minimo = round_math(precio_compra*(1 + porcentaje_precio_minimo/100), numero_decimales);
            $("#nuevo_producto_preciominimo").val(precio_venta_minimo);
        }

        //console.log('precio_compra: ',precio_compra,' || porcentaje_ganancia_maxima: ', porcentaje_ganancia_maxima,' || porcentaje_precio_minimo: ', porcentaje_precio_minimo);
    });

    //porcentaje de ganancia máxima
    $("#txt_porcentaje_ganancia_maxima").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        var porcentaje_ganancia_maxima = parseFloat($("#txt_porcentaje_ganancia_maxima").val());
        if(isNaN(porcentaje_ganancia_maxima) || porcentaje_ganancia_maxima == '' || typeof porcentaje_ganancia_maxima === undefined){
            porcentaje_ganancia_maxima = 0;
        }

        var precio_compra = parseFloat($("#nuevo_producto_precio_compra").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
        }

        if(porcentaje_ganancia_maxima == 0) {
            $("#nuevo_producto_valor_con_igv").val(precio_compra);
            $("#nuevo_producto_valor_sin_igv").val(round_math(precio_compra/1.18, numero_decimales));
        } else {
            var precio_venta = round_math(precio_compra*(1 + porcentaje_ganancia_maxima/100), numero_decimales);
            var precio_venta_sin_igv = round_math(precio_venta/1.18, numero_decimales);

            $("#nuevo_producto_valor_con_igv").val(precio_venta);
            $("#nuevo_producto_valor_sin_igv").val(precio_venta_sin_igv);
        }
    });

    //porcentaje de ganancia mínimo
    $("#txt_porcentaje_ganancia").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        var precio_compra = parseFloat($("#nuevo_producto_precio_compra").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
        }

        var porcentaje_precio_minimo = parseFloat($("#txt_porcentaje_ganancia").val()); //porcentaje de ganancia mínimo
        if(isNaN(porcentaje_precio_minimo) || porcentaje_precio_minimo == '' || typeof porcentaje_precio_minimo === undefined){
            porcentaje_precio_minimo = 0;
        }
        
        if(porcentaje_precio_minimo == 0) {
            $("#nuevo_producto_preciominimo").val(precio_compra);
        } else {
            var precio_venta_minimo = round_math(precio_compra*(1 + porcentaje_precio_minimo/100), numero_decimales);
            $("#nuevo_producto_preciominimo").val(precio_venta_minimo);
        }

    });

    //
    $("#nuevo_producto_valor_con_igv").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        var precio_compra = parseFloat($("#nuevo_producto_precio_compra").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
        }

        var precio_venta_con_igv = parseFloat($("#nuevo_producto_valor_con_igv").val());
        if(isNaN(precio_venta_con_igv) || precio_venta_con_igv == '' || typeof precio_venta_con_igv === undefined){
            precio_venta_con_igv = 0;
        }

        if(precio_venta_con_igv == 0) {
            $("#txt_porcentaje_ganancia_maxima").val(0);
            $("#nuevo_producto_valor_sin_igv").val(0);
            return;
        } else {
            var precio_venta_sin_igv = round_math(precio_venta_con_igv/1.18, numero_decimales);
            $("#nuevo_producto_valor_sin_igv").val(precio_venta_sin_igv);

            if(precio_compra == 0) {
                $("#txt_porcentaje_ganancia_maxima").val(0);
                $("#txt_porcentaje_ganancia").val(0);
                $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
                $("#txt_porcentaje_ganancia").prop("disabled", true);
                return;
            } else {
                if(precio_venta_con_igv < precio_compra) {
                    //el precio de venta es menor al de compra: significa que el cliente está perdiento. (deberíamos mostrar en rojo)
                    $("#txt_porcentaje_ganancia_maxima").val(0);
                } else if(precio_venta_con_igv == precio_compra) {
                    $("#txt_porcentaje_ganancia_maxima").val(0); //no está ganando ni perdiendo: (deberíamos mostrar un color )
                } else {
                    var porcentaje_ganancia_maxima = round_math(round_math(((precio_venta_con_igv*100)/precio_compra), 4) - 100, 2);
                    $("#txt_porcentaje_ganancia_maxima").val(porcentaje_ganancia_maxima);
                }
            }
        }
    });

    $("#nuevo_producto_valor_sin_igv").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        var precio_compra = parseFloat($("#nuevo_producto_precio_compra").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
        }

        var precio_venta_sin_igv = parseFloat($("#nuevo_producto_valor_sin_igv").val());
        if(isNaN(precio_venta_sin_igv) || precio_venta_sin_igv == '' || typeof precio_venta_sin_igv === undefined){
            precio_venta_sin_igv = 0;
        }

        if(precio_venta_sin_igv == 0) {
            $("#txt_porcentaje_ganancia_maxima").val(0);
            $("#nuevo_producto_valor_con_igv").val(0);
            return;
        } else {
            var precio_venta_con_igv = round_math(precio_venta_sin_igv*1.18, numero_decimales);
            $("#nuevo_producto_valor_con_igv").val(precio_venta_con_igv);

            if(precio_compra == 0) {
                $("#txt_porcentaje_ganancia_maxima").val(0);
                $("#txt_porcentaje_ganancia").val(0);
                $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
                $("#txt_porcentaje_ganancia").prop("disabled", true);
                return;
            } else {
                if(precio_venta_sin_igv*1.18 < precio_compra) {
                    //el precio de venta es menor al de compra: significa que el cliente está perdiento. (deberíamos mostrar en rojo)
                    $("#txt_porcentaje_ganancia_maxima").val(0);
                } else if(precio_venta_sin_igv*1.18 == precio_compra) {
                    $("#txt_porcentaje_ganancia_maxima").val(0); //no está ganando ni perdiendo: (deberíamos mostrar un color )
                } else {
                    var porcentaje_ganancia_maxima = round_math(round_math(((precio_venta_con_igv*100)/precio_compra), 4) - 100, 2);
                    $("#txt_porcentaje_ganancia_maxima").val(porcentaje_ganancia_maxima);
                }
            }
        }
    });

    $("#nuevo_producto_preciominimo").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }

        var precio_compra = parseFloat($("#nuevo_producto_precio_compra").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_compra = 0;
            $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
            $("#txt_porcentaje_ganancia").prop("disabled", true);
        }

        var precio_venta_minimo = parseFloat($("#nuevo_producto_preciominimo").val());
        if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
            precio_venta_minimo = 0;
        }

        if(precio_venta_minimo == 0) {
            $("#txt_porcentaje_ganancia").val(0);
            return;
        } else {
            if(precio_compra == 0) {
                $("#txt_porcentaje_ganancia_maxima").val(0);
                $("#txt_porcentaje_ganancia").val(0);
                $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
                $("#txt_porcentaje_ganancia").prop("disabled", true);
                return;
            } else {
                if(precio_venta_minimo < precio_compra) {
                    //el precio de venta es menor al de compra: significa que el cliente está perdiento. (deberíamos mostrar en rojo)
                    $("#txt_porcentaje_ganancia").val(0);
                } else if(precio_venta_minimo == precio_compra) {
                    $("#txt_porcentaje_ganancia").val(0); //no está ganando ni perdiendo: (deberíamos mostrar un color )
                } else {
                    var porcentaje_ganancia_minima = round_math(round_math(((precio_venta_minimo*100)/precio_compra), 4) - 100, 4);
                    $("#txt_porcentaje_ganancia").val(porcentaje_ganancia_minima);
                }
            }
        }
    });
    //FIN MODIFICACION CALCULOS AUTOMÁTICOS
    
    $('#opcion_multiprecio').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $("#content_lista_precios").show("slide");
        } else {
            $("#content_lista_precios").hide("slide");
        }
    });

    $('#opcion_tienedetraccion').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $("#content_listacodigo_detraccion").show("slide");
        } else {
            $("#content_listacodigo_detraccion").hide("slide");
        }
    });

    $('.editable').editable();

    $(".add-row").click(function(){
        var markup = '\
        <tr counter-id="1">\
            <td counter-id="1" td_attr="value"><span class="editable">Click para Cambiar</span></td>\
            <td counter-id="1" td_attr="value"><span class="editable">00</span></td>\
            <td counter-id="1" td_attr="value">\
                <ul class="icons-list">\
                    <li class="text-danger-600"><a onclick="removeRow(this)" href="javascript:void(0);"><i class="icon-trash"></i></a></li>\
                </ul>\
            </td>\
        </tr>';
        $("#lista_precios_items").append(markup);
        $('.editable').editable();
    });

    $("#btn_traslados").click(function() {
        $("#vm_traslados").modal({
			backdrop: 'static',
			keyboard: false
		});
        $("#key_row_traslado").val("");
        $("#select_producto_buscar_3").empty();
        $('#descripcion_prod_traslado').val('');
        $('#cantidad_traslado').val('');
        $('#unidad_medida_traslado').val('');
        $('#lista_productos_traslado').jqGrid('clearGridData');
    });

    inicializar_grid();
    $(".btn_agregar_producto_traslado").click(add_to_detalle_traslado);
    $("#btn_gistrar_traslado").click(iniciar_traslado);
    $(".btn_eliminarproducto").click(eliminar_producto_detalle);
    $(".control_fecha").daterangepicker({singleDatePicker: true, drops: 'up', locale: { format: 'DD/MM/YYYY' }});
    inicializar_autocompletado();

    $('#opcion_actualizacion_total').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $(".info_texto_almacen_2").removeClass("alert-info");
            $(".info_texto_almacen_2").addClass("alert-danger");
            $(".info_texto_almacen_2").html("<strong class='text-danger'>!Cuidado!</strong> Se actualizará únicamente el IDCATEGORIA, NOMBRE, ID_UNIDADMEDIDA, AFECTACIÓN IGV, PRECIO VENTA, ESTADO, MARCA, NOTA Y MULTIPRECIO, los cambios no se podrán deshacer. Utilizar con cuidado esta opción!.");
        } else {
            $(".info_texto_almacen_2").removeClass("alert-danger");
            $(".info_texto_almacen_2").addClass("alert-info");
            $(".info_texto_almacen_2").html('Todos los productos que se ingresen en el excel, serán registrados en el almacén para la sucursal: ' + $("#select_almacen_mostrar option:selected").text());
        }
    });
});


function inicializar_autocompletado() {
    $('#txt_marca_producto').flexdatalist({
        minLength: 0,
        selectionRequired: false,
        visibleProperties: ["marca"],
        searchContain: false, 
        searchDisabled: false,
        focusFirstResult: true,
        searchIn: 'marca',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        data: '/facturacionv8/producto/get_sugerencias_marcas'
    }).on("select:flexdatalist", function(event, data) {
        $("#txt_marca_producto").val(data.marca);
    });

    $('#nombre_categoria').flexdatalist({
        minLength: 3,
        selectionRequired: false,
        visibleProperties: ["texto"],
        searchContain: false, 
        searchDisabled: false,
        focusFirstResult: true,
        searchIn: 'nombre_cate',
        searchDelay: 200,
        requestType: 'post',
        noResultsText: 'No se encuentran resultados para: "{keyword}"',
        url: '/facturacionv8/category/get_sugerencias_categorias'
    }).on("select:flexdatalist", function(event, data) {
        $("#nombre_categoria").val(data.nombre_cate);
    });
}

function get_json_listaprecios(counter=1){

    var header = [];
    var data = [];

    /*
    $('#lista_precios_head_1 th').each(function(i, v){
        if($(this).html().trim() == '') {
            header[i] = 'opcion';
        } else {
            header[i] = $(this).html().trim().toLowerCase();    
        }
    });*/

    header[0] = 'nombre';
    header[1] = 'precio';
    header[2] = 'opcion';
    
    //console.log('header: ', header, counter);
    var row_finder = `#lista_precios_items tr[counter-id=1]`;
    $(row_finder).each(function(row_i, row_v){

        var obj = {};
        //console.log('Outer loop id, value: ', row_i, row_v);
        var n = 0;
        $(header).each(function(header_i, header_value){

            n++;
            var cell = $(row_v).children('td').eq(header_i);
            var td_attr = $(cell).attr('td_attr');
            var inner_text = $(cell).children('span').html();
            var inner_table = $(cell).find('table');

            if(n <= 2) {
                if(inner_text == undefined) {
                    return false;
                }
            } else {
                if(inner_text == undefined) {
                    inner_text = '-';
                }
            }

            switch(td_attr){
                case 'value':
                    if(inner_text != "" && inner_text != null )
                        obj[header_value] = inner_text;
                break;

                case 'obj':
                case 'array':
                obj[header_value] = makeJson( $(inner_table).attr('counter-id') );
                break;

                case null:
                case '':
                case undefined:
                
                break;

                default:
                obj[header_value] = "unknown value";
                break;
            }
            console.log('value of td_attr: ', td_attr);
            
        });
        //console.log('data value: ', data);
        data.push(obj);
    });
    
    //return JSON.stringify(data);
    return data;
}

function removeRow(oButton) {
    swal({   
        title: 'Cuidado!',   
        text: '¿Realmente Deseas Eliminarlo?',
        html: true,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "Si, Adelante!",   
        closeOnConfirm: true,
        showLoaderOnConfirm: true
    }, function(isconfirmed){  
        if(isconfirmed) {
            oButton.parentNode.parentNode.parentNode.parentNode.remove();
        } else {
            return;
        }
    });
}

function registrar_entrada_producto() {
    var light = $("#vm_agregar_stock");

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
    var cantidad = $("#nuevo_stock_producto").val();
    var nota = $("#nota_ingreso").val();
    var select_sucursal = $("#select_almacen_mostrar").val();
    var costo_unitario = $("#costo_unitario").val();

	$.ajax({
        url : '/facturacionv8/producto/registrar_ingreso_producto',
		method :  'POST',
		data: {idproducto: idproducto, cantidad: cantidad, nota: nota, select_sucursal: select_sucursal, costo_unitario: costo_unitario},
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
					$.ajax({
						url : '/facturacionv8/producto/registrar_ingreso_producto',
						method :  'POST',
						data: {idproducto: idproducto, cantidad: cantidad, nota: nota, select_sucursal: select_sucursal, costo_unitario: costo_unitario, confirmado: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "Ok"
								}, function(){  
                                    $("#nuevo_stock_producto").val(0);
                                    $("#stock_actual_producto").val(0)
                                    $("#vm_agregar_stock").modal('hide');
                                    $("#select_producto_buscar").empty();
                                    $("#contenedor_cuadros_stocks").hide();
                                    $("#text_cambio_stock").hide();
                                    dataTable_lista_productos.ajax.reload(null, false);
								});
								$(light).unblock();
								return;
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

function registrar_salida_producto() {
    var light = $("#vm_disminuir_stock");

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

    var idproducto = $("#select_producto_buscar_2").val();
    var cantidad = $("#nuevo_stock_producto_2").val();
    var nota = $("#observacion_disminuirstock").val();
    var select_sucursal = $("#select_almacen_mostrar").val();

	$.ajax({
        url : '/facturacionv8/producto/registrar_salida_producto',
		method :  'POST',
		data: {idproducto: idproducto, cantidad: cantidad, nota: nota, select_sucursal: select_sucursal},
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
					$.ajax({
						url : '/facturacionv8/producto/registrar_salida_producto',
						method :  'POST',
						data: {idproducto: idproducto, cantidad: cantidad, nota: nota, select_sucursal: select_sucursal, confirmado: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "Ok"
								}, function(){  
                                    $("#nuevo_stock_producto_2").val(0);
                                    $("#stock_actual_producto_2").val(0);
                                    $("#select_producto_buscar_2").empty();
                                    $("#vm_disminuir_stock").modal('hide');
                                    $("#contenedor_cuadros_stocks_2").hide();
                                    $("#text_cambio_stock_2").hide();
                                    dataTable_lista_productos.ajax.reload(null, false);
								});
								$(light).unblock();
								return;
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

function interaccion_stocks_aumentar() {
    let nuevo_stock = 0;
    if($('#nuevo_stock_producto').val() == '' || $('#nuevo_stock_producto').val() <= 0 || isNaN($('#nuevo_stock_producto').val())) {
        nuevo_stock = 0;
    } else {
        nuevo_stock = parseFloat($('#nuevo_stock_producto').val());
    }

    let stock_actual = parseFloat($("#stock_actual_producto").val());
    let stock_total = nuevo_stock + stock_actual;

    if(nuevo_stock > 0) {
        let texto = 'Se agregarán ' + nuevo_stock + ' ' +  $("#simbolo_unidad").val() + ' al stock actual, haciendo un nuevo total de: <strong>' + stock_total + ' ' + $("#simbolo_unidad").val() + '</strong>';
        $("#contenedor_mensaje").show();
        $("#text_cambio_stock").html(texto);
    } else {
        $("#contenedor_mensaje").hide();
    }
}

function interaccion_stocks_disminuir() {
    let nuevo_stock = 0;
    if($('#nuevo_stock_producto_2').val() == '' || $('#nuevo_stock_producto_2').val() <= 0 || isNaN($('#nuevo_stock_producto_2').val())) {
        nuevo_stock = 0;
    } else {
        nuevo_stock = parseFloat($('#nuevo_stock_producto_2').val());
    }

    let stock_actual = parseFloat($("#stock_actual_producto_2").val());
    let stock_total = stock_actual - nuevo_stock;
    if(stock_total < 0) {
        stock_total = 0;
    }

    if(nuevo_stock > 0) {
        let texto = 'Se disminuirá el stock en ' + nuevo_stock + ' ' +  $("#simbolo_unidad").val() + ', haciendo un nuevo total de: <strong>' + stock_total + ' ' + $("#simbolo_unidad").val() + '</strong> en stock.';
        $("#contenedor_mensaje_2").show();
        $("#text_cambio_stock_2").html(texto);
    } else {
        $("#contenedor_mensaje_2").hide();
    }
}

function get_data_producto() {
    var idproducto = $("#select_producto_buscar").val();
    console.log("FC 001");
    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $(".simbolo_unidad_prod").html(data.unidad_medida.simbolo);
            $("#stock_actual_producto").val(data.producto.stock);
            $("#nuevo_stock_producto").val(0);
            $("#simbolo_unidad").val(data.unidad_medida.simbolo);
            $("#costo_unitario").val(data.producto.precio_compra);
            $(".moneda_precio_compra").html(data.producto.id_cod_moneda);

            if(data.producto.afecto_icbper == 'si') {
                $('#opcion_afecto_icbper').bootstrapSwitch('state', true);
            } else {
                $('#opcion_afecto_icbper').bootstrapSwitch('state', false);
            }

            $("#contenedor_mensaje").hide();
            $("#contenedor_cuadros_stocks").show();
            
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

function get_data_producto_2() {
    var idproducto = $("#select_producto_buscar_2").val();
    console.log("FC 002");
    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $(".simbolo_unidad_prod").html(data.unidad_medida.simbolo);
            $("#stock_actual_producto_2").val(data.producto.stock);
            $("#nuevo_stock_producto_2").val(0);
            $("#simbolo_unidad").val(data.unidad_medida.simbolo);

            $("#contenedor_mensaje_2").hide();
            $("#contenedor_cuadros_stocks_2").show();
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

function get_data_producto_3() {
    var idproducto = $("#select_producto_buscar_3").val();
    console.log("FC 003");
    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#unidad_medida_traslado").val(data.unidad_medida.nombre);
            $("#descripcion_prod_traslado").val(data.producto.nombre);
            $("#codigo_producto_traslado").val(data.producto.codigo);
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

function inicializar_buscador_producto() {
    $("#select_producto_buscar").select2({
        language: "es",
        dropdownParent: $('#vm_agregar_stock'),
        minimumResultsForSearch: Infinity,
        ajax: {
          type: "post",
          url: "/facturacionv8/herramientas/get_sugerencias_producto",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
                q: params.term, // search term
                page: params.page,
                idsucursal: $("#select_almacen_mostrar").val()
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

    $("#select_producto_buscar_2").select2({
        language: "es",
        dropdownParent: $('#vm_disminuir_stock'),
        minimumResultsForSearch: Infinity,
        ajax: {
          type: "post",
          url: "/facturacionv8/herramientas/get_sugerencias_producto",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
                q: params.term, // search term
                page: params.page,
                idsucursal: $("#select_almacen_mostrar").val()
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

    $("#select_producto_buscar_3").select2({
        language: "es",
        dropdownParent: $('#vm_traslados'),
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
              idsucursal: $("#select_almacen_origen").val()
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

function iniciar_proceso_actualizacion(){
	var light = $("#content_vm_actualizar_articulo");

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

    var datastring = $("#frm_actualizarproductos").serializeArray();
    var formData = new FormData();
    formData.append("archivo", $("#file_data_actualizacion")[0].files[0]);
    formData.append('select_sucursal', $("#select_almacen_mostrar").val())
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });
    
    $.ajax({
        url : '/facturacionv8/producto/actualizar_productos',
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
                        url: "/facturacionv8/producto/actualizar_productos",
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
                                get_lista_productos();
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

function iniciar_proceso_importacion(){
	var light = $("#content_vm_importar_articulo");

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

    var datastring = $("#frm_importarproductos").serializeArray();
    var formData = new FormData();
    formData.append("archivo", $("#file_data_import")[0].files[0]);
    formData.append('select_sucursal', $("#select_almacen_mostrar").val())
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });
    
    $.ajax({
        url : '/facturacionv8/importacionproductos/importar_productos',
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
                        url: "/facturacionv8/importacionproductos/importar_productos",
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
                                get_lista_productos();
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
 
function limpiar_campos() {
    $("#idproducto_newprod").val('');
    $("#nuevo_producto_codigo").val('');
    $("#nuevo_producto_nombre_servicio").val('');
    $("#nuevo_producto_valor_con_igv").val(0);
    $("#nuevo_producto_valor_sin_igv").val(0);
    $("#nuevo_producto_stock").val(0);
    $("#nuevo_producto_stock_minimo").val(0);
    $("#nuevo_producto_precio_compra").val(0),
    $("#valor_de_compra").val(0);
    $('#contenedor_imagenes_cuadradas').html('');
    $('#array_listaimagenes_cuadradas').val('');
    $('#contenedor_imagenes_horizontales').html('');
    $('#array_listaimagenes_horizontales').val('');
    $('#contenedor_imagenes_verticales').html('');
    $('#array_listaimagenes_verticales').val('');

    $("#txt_porcentaje_ganancia_maxima").val('');
    $("#txt_porcentaje_ganancia").val('');

    limpiar_jq_grid(jQuery('#lista_presentaciones_producto'));
    $("#presentacion_codigo").val('');
    $("#presentacion_nombre").val('');
    $("#presentacion_pventa").val('');
    $("#presentacion_pventa_minimo").val('');
    $("#presentacion_cantidad").val('');
    $('.opciones_producto a[href="#registrar_producto"]').tab('show');
}

function edit_product(idproducto, accion = 'editar'){
    
    var light = $("#content_lista_productos");
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
        url: '/facturacionv8/producto/get_data_producto',
        data: {idproducto: idproducto},
		method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {

            if(accion == 'editar') {
                $("#idproducto_newprod").val(data.producto.idproducto);
            } else {

            }

            $('.opciones_producto a[href="#registrar_producto"]').tab('show');

            $("#nuevo_producto_codigo").val(data.producto.codigo);
            $("#nuevo_producto_nombre_servicio").val(data.producto.nombre);
            $("#nuevo_producto_tipo_afect_igv").val(data.producto.id_tipoafectacionigv).trigger("change").trigger("select2:select");

            if(data.producto.id_tipoafectacionigv == 10) {
                $("#nuevo_producto_valor_con_igv").val(parseFloat(data.producto.valor_con_igv));
                $("#nuevo_producto_valor_sin_igv").val(parseFloat(data.producto.valor_sin_igv));
            } else {
                $("#nuevo_producto_valor_con_igv").val(parseFloat(data.producto.valor_sin_igv));
                $("#nuevo_producto_valor_sin_igv").val(parseFloat(data.producto.valor_sin_igv));
            }
            //$("#valor_de_compra").val(data.producto.precio_compra);
            $("#nuevo_producto_categoria").val(data.producto.id_categoria).trigger("change").trigger("select2:select");
            //$("#id_cod_detraccion").val(data.producto.id_cod_detraccion).trigger("change").trigger("select2:select");
            $("#nuevo_producto_unidad").val(data.producto.id_unidad_medida).trigger("change").trigger("select2:select");
            $("#nuevo_producto_moneda").val(data.producto.id_cod_moneda).trigger("change").trigger("select2:select");
            $("#new_producto_tipodecambio").val(data.producto.tipo_cambio_sunat);

            $("#nuevo_producto_stock").val(parseFloat(data.producto.stock));
            $("#nuevo_producto_stock_minimo").val(parseFloat(data.producto.stock_minimo));
            $("#nuevo_producto_precio_compra").val(parseFloat(data.producto.precio_compra));
            $("#nuevo_producto_preciominimo").val(parseFloat(data.producto.precio_venta_minimo));
            $("#nota_producto").val(data.producto.nota);
            $("#txt_marca_producto").val(data.producto.marca);
            $("#nuevo_producto_peso").val(data.producto.peso);

            if(data.fecha_vencimiento != '') {
                $("#txt_fecha_vencimiento").val(data.fecha_vencimiento);
                $('#txt_fecha_vencimiento').data('daterangepicker').setStartDate(data.fecha_vencimiento);
            } else {
                $("#txt_fecha_vencimiento").val('');
            }

            var porcentaje_ganancia_maxima = 0;
            var porcentaje_ganancia_minima = 0;
            var precio_compra = data.producto.precio_compra;

            if(isNaN(precio_compra) || precio_compra == '' || typeof precio_compra === undefined){
                precio_compra = 0;
            }

            if(precio_compra <= 0) {
                porcentaje_ganancia_maxima = 0;
                porcentaje_ganancia_minima = 0;

                $("#txt_porcentaje_ganancia_maxima").prop("disabled", true);
                $("#txt_porcentaje_ganancia").prop("disabled", true);
            } else {
                $("#txt_porcentaje_ganancia_maxima").prop("disabled", false);
                $("#txt_porcentaje_ganancia").prop("disabled", false);

                if(data.producto.valor_con_igv <= 0) {
                    porcentaje_ganancia_maxima = 0;
                } else {
                    porcentaje_ganancia_maxima = round_math(((parseFloat(data.producto.valor_con_igv))/parseFloat(data.producto.precio_compra) - 1)*100, 4);
                }

                if(data.producto.precio_venta_minimo <= 0) {
                    porcentaje_ganancia_minima = 0;
                } else {
                    porcentaje_ganancia_minima = round_math(((parseFloat(data.producto.precio_venta_minimo))/parseFloat(data.producto.precio_compra) - 1)*100, 4);
                }
            }
            
            $("#txt_porcentaje_ganancia_maxima").val(porcentaje_ganancia_maxima);
            $("#txt_porcentaje_ganancia").val(porcentaje_ganancia_minima);

            if(data.producto.afecto_icbper == 'si') {
                $('#opcion_afecto_icbper').bootstrapSwitch('state', true);
            } else {
                $('#opcion_afecto_icbper').bootstrapSwitch('state', false);
            }

            if(data.producto.id_cod_detraccion == '0' || data.producto.id_cod_detraccion == 0 || data.producto.id_cod_detraccion == '' || !data.producto.id_cod_detraccion ) {
                $('#opcion_tienedetraccion').bootstrapSwitch('state', false);
                $("#content_listacodigo_detraccion").hide();
            } else {
                $('#opcion_tienedetraccion').bootstrapSwitch('state', true);
                $("#detraccion_codigo_bien").val(data.producto.id_cod_detraccion).trigger("change");
                $("#content_listacodigo_detraccion").show('slide');
            }

            if(data.producto.multi_precio == 'si') {
                $('#opcion_multiprecio').bootstrapSwitch('state', true);
                $('#content_lista_precios').show();
            } else {
                $('#opcion_multiprecio').bootstrapSwitch('state', false);
                $('#content_lista_precios').hide();
            }

            $("#lista_precios_items").html('');
            data.lista_precios.forEach(function(precio_item, index) {
                var markup = '\
                <tr counter-id="1">\
                    <td counter-id="1" td_attr="value"><span class="editable">' + precio_item.nombre +'</span></td>\
                    <td counter-id="1" td_attr="value"><span class="editable">' + precio_item.precio + '</span></td>\
                    <td counter-id="1" td_attr="value">\
                        <ul class="icons-list">\
                            <li class="text-danger-600"><a onclick="removeRow(this)" href="javascript:void(0);"><i class="icon-trash"></i></a></li>\
                        </ul>\
                    </td>\
                </tr>';
                $("#lista_precios_items").append(markup);
            });
            $('.editable').editable();
            
            $(".campos_no_editables").hide();
            $("#vm_agregar_articulo").modal({
                backdrop: 'static',
                keyboard: false
            });

            if(data.producto.foto !== null && data.producto.foto !== '') {
                var imagenes_producto = jQuery.parseJSON(data.producto.foto); 
                $('#contenedor_imagenes_cuadradas').html('');
                $('#array_listaimagenes_cuadradas').val('');
                $('#contenedor_imagenes_horizontales').html('');
                $('#array_listaimagenes_horizontales').val('');
                $('#contenedor_imagenes_verticales').html('');
                $('#array_listaimagenes_verticales').val('');
                $.each(imagenes_producto.cuadradas, function( index, url_imagen ) {
                    if(url_imagen !== null && url_imagen !== '') {
                        agregar_imagen_contenedor(url_imagen, $('#contenedor_imagenes_cuadradas'), $("#array_listaimagenes_cuadradas"), 'btn_eliminar_img_cuadrada', 'col-lg-2 col-md-2 col-sm-2 col-xs-6 content-box-1');
                    }
                });

                $.each(imagenes_producto.horizontales, function( index, url_imagen ) {
                    if(url_imagen !== null && url_imagen !== '') {
                        agregar_imagen_contenedor(url_imagen, $('#contenedor_imagenes_horizontales'), $("#array_listaimagenes_horizontales"), 'btn_eliminar_img_horizontal', 'col-lg-3 col-md-3 col-sm-3 col-xs-12  content-box-2');
                    }
                });

                $.each(imagenes_producto.verticales, function( index, url_imagen ) {
                    if(url_imagen !== null && url_imagen !== '') {
                        agregar_imagen_contenedor(url_imagen, $('#contenedor_imagenes_verticales'), $("#array_listaimagenes_verticales"), 'btn_eliminar_img_vertical', 'col-lg-3 col-md-3 col-sm-3 col-xs-6  content-box-3');
                    }
                });
            }

            limpiar_jq_grid(jQuery('#lista_presentaciones_producto'));
            data.presentaciones.forEach(function(presentacion, indice, array) {
                var data = {
                    row_identificadador: presentacion.row_identificadador,
                    id_producto: presentacion.id_producto,
                    nombre_presentacion: presentacion.nombre_presentacion,
                    codigo: presentacion.codigo,
                    id_unidad_presentacion: presentacion.id_unidad_presentacion,
                    id_unidad_base: presentacion.id_unidad_base, //$('#producto_unidadmedida').val(),
                    nombre_unidad_presentacion: presentacion.nombre_unidad_presentacion,
                    cantidad: presentacion.cantidad,
                    nombre_unidad_base: presentacion.nombre_unidad_base,
                    precio_con_igv: presentacion.precio_con_igv,
                    precio_sin_igv: presentacion.precio_sin_igv,
                    precio_minimo: presentacion.precio_minimo
                };
				jQuery('#lista_presentaciones_producto').addRowData(indice + 1, data, 'last');
            });

			$(light).unblock();
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#00BCD4",   
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
			confirmButtonColor: "#00BCD4",   
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

function get_lista_almacenes(select, seleccionar = 'no', opcion = 'individual') {
	$.ajax({
        url : '/facturacionv8/herramientas/get_lista_sucursales',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            select.empty();
            select.select2({ minimumResultsForSearch: -1 });
            if(opcion == 'opcion_todos') {
                select.append('<option value="0">Todos los Almacenes</option>');
            }
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

function guardar_nuevo_producto() {
    var light = $("#content_popup_producto");

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

    var state = $("#opcion_afecto_icbper").bootstrapSwitch('state');
    if(state) {
        afecto_icbper = 'si';
    } else {
        afecto_icbper = 'no';
    }

    var state3 = $("#opcion_tienedetraccion").bootstrapSwitch('state');
    if(state3) {
        opcion_tienedetraccion = 'si';
    } else {
        opcion_tienedetraccion = 'no';
    }

    var state2 = $("#opcion_multiprecio").bootstrapSwitch('state');
    if(state2) {
        opcion_multiprecio = 'si';
    } else {
        opcion_multiprecio = 'no';
    }
    
    var json_listaprecios = get_json_listaprecios();
    var gridData_presentacion = jQuery("#lista_presentaciones_producto").getRowData();
    var presentaciones = JSON.stringify(gridData_presentacion);
    
    var datastring = {
        idproducto: $("#idproducto_newprod").val(),
        codigo: $("#nuevo_producto_codigo").val(),
        nombre_servicio: $("#nuevo_producto_nombre_servicio").val(),
        producto_tipo_afect_igv: $("#nuevo_producto_tipo_afect_igv").val(),
        valor_de_compra: $("#nuevo_producto_precio_compra").val(),
        valor_con_igv: $("#nuevo_producto_valor_con_igv").val(),
        valor_sin_igv: $("#nuevo_producto_valor_sin_igv").val(),
        id_categoria: $("#nuevo_producto_categoria").val(),
        id_cod_detraccion: '',
        nota_producto: $("#nota_producto").val(),
        id_unidad_medida: $("#nuevo_producto_unidad").val(),
        id_cod_moneda: $("#nuevo_producto_moneda").val(),
        stock: $("#nuevo_producto_stock").val(),
        stock_minimo:  $("#nuevo_producto_stock_minimo").val(),
        select_sucursal: $("#select_almacen_mostrar").val(),
        tipo_cambio_producto: $("#new_producto_tipodecambio").val(),
        opcion_afecto_icbper: afecto_icbper,
        precio_venta_minimo: $("#nuevo_producto_preciominimo").val(),
        opcion_lista_precio: opcion_multiprecio,
        json_listaprecios: JSON.stringify(json_listaprecios),
        opcion_tienedetraccion: opcion_tienedetraccion,
        detraccion_codigo_bien: $("#detraccion_codigo_bien").val(),
        txt_fecha_vencimiento: $("#txt_fecha_vencimiento").val(),
        txt_marca_producto: $("#txt_marca_producto").val(),

        imgprod_cuadradas: $("#array_listaimagenes_cuadradas").val(),
        imgprod_verticales: $("#array_listaimagenes_verticales").val(),
        imgprod_horizontales: $("#array_listaimagenes_horizontales").val(),

        peso: $("#nuevo_producto_peso").val(),

        presentaciones: presentaciones
    };
    
	$.ajax({
        url : '/facturacionv8/producto/insert',
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
                confirmButtonColor: "#00BCD4",   
                confirmButtonText: "Ok",
            }, function() {
                dataTable_lista_productos.ajax.reload(null, false);
                limpiar_campos();
                $("#vm_agregar_articulo").modal("hide");
                limpiar_jq_grid(jQuery('#lista_presentaciones_producto'));
				$(light).unblock();
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#00BCD4",   
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
			confirmButtonColor: "#00BCD4",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
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

function calcular_precio_sin_igv_nuevoprod() {
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var igv_percent = parseFloat((18/100) + 1);
    if($('#nuevo_producto_valor_con_igv').val() == '' || $('#nuevo_producto_valor_con_igv').val() <= 0 || isNaN($('#nuevo_producto_valor_con_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#nuevo_producto_valor_con_igv').val());
    }
    
    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), numero_decimales);
    $("#nuevo_producto_valor_sin_igv").val(precio_sin_igv);
}

function calcular_precio_con_igv_nuevoprod() {
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var igv_percent = 1.18;
    if($('#nuevo_producto_valor_sin_igv').val() == '' || $('#nuevo_producto_valor_sin_igv').val() <= 0 || isNaN($('#nuevo_producto_valor_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#nuevo_producto_valor_sin_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), numero_decimales);
    $("#nuevo_producto_valor_con_igv").val(precio_sin_igv);
}

function get_lista_productos(idsucursal = 0, select_tipo_item = 0){
    var light = $("#tbl_lista_productos");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Generando Reporte, Espera un Momento! ...</p>',
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
    
    dataTable_lista_productos = $('#tbl_lista_productos').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/producto/get_lista_productos", // json datasource
			data: {idsucursal: idsucursal, select_tipo_item: select_tipo_item},
			type: "post",
			error: function(){
				$(".tbl_lista_productos-error").html("");
				$("#tbl_lista_productos").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_productos_processing").css("display","none");
				
			}
        },
        "bDestroy": true,
        "lengthMenu": [ [10, 25, 50, 100, 300], [10, 25, 50, 100, 300] ],
		buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '<i class="icon-file-excel position-left"></i> Exp. Vista</span>',
                className: 'btn btn-success'
            },
            {
                text: '<i class="icon-file-excel position-left"></i> Exp. Sucursal</span>',
                className: 'btn btn-info',
                action: function ( e, dt, node, config ) {
                    window.open("/facturacionv8/producto/export_to_excel/" + $("#select_almacen_mostrar").val(),"_self");
                }
            },
            {
                text: '<i class="icon-file-excel position-left"></i> Exp. Todo</span>',
                className: 'btn btn-danger',
                action: function ( e, dt, node, config ) {
                    window.open("/facturacionv8/producto/export_to_excel","_self");
                }
            },
            {
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-indigo btn-icon',
                collectionLayout: 'fixed two-column'
            }
        ],
        "columns": [
            { "data": "fecha_registro", "visible": false}, //0
            { "data": "idproducto", "visible": false}, //0
            { "data": "codigo", "visible": false, "className": 'row_editable', 
                render: function ( data, type, row ) {
                    var array_codigo = row.codigo_array.split('||');
                    return "<div class='valor_editable'><a href='/facturacionv8/producto/imprimir_codigobarras/" + array_codigo[0] + "/?l1=" + array_codigo[1] + "&l2=" + array_codigo[2] + "&l3=" + array_codigo[3] + "' target='_blank'>" + array_codigo[0] + "</a>  <i class='text-primary fa fa-pencil'/></div>";
                }, 
            }, //1
            { "data": "idsucursal", "visible": true }, //2
            { "data": "nombresucursal", "visible": false }, //3
            { "data": "id_categoria", "visible": false }, //4
            { "data": "nombre_categoria", "visible": false }, //5
            { "data": "nombreproducto", "visible": true, "className": 'row_editable', 
                render: function ( data, type, row ) {
                    return "<div class='valor_editable'>" + row.nombreproducto +"</div>";
                }, 
            }, //6
            { "data": "id_unidad_medida", "visible": false }, //7
            { "data": "nombreunidad", "visible": true }, //8
            { "data": "id_tipoafectacionigv", "visible": false }, //9
            { "data": "tipoafectacion", "visible": false }, //10

            { "data": "costopromedio", "visible": false }, //11
            { "data": "stock_valorizado", "visible": false }, //12

            { "data": "precio_compra", "visible": true, "className": 'row_editable',
                render: function ( data, type, row ) {
                    if(row.id_cod_moneda == 'USD') {
                        return "<span class='valor_editable'>" + 'USD ' + row.precio_compra + "</span>";
                    } else {
                        return "<span class='valor_editable'>" + 'S/ ' + row.precio_compra + "</span>";
                    }
                }, 
            
            }, //13
            { "data": "id_cod_moneda", "visible": false, 
                render: function ( data, type, row ) {
                    if(row.id_cod_moneda == 'USD') {
                        return 'DÓLARES';
                    } else {
                        return 'SOLES';
                    }
                }, 
            }, //14
            { "data": "precio_venta", "visible": true , "className": 'row_editable', 
                render: function ( data, type, row ) {
                    if(row.id_cod_moneda == 'USD') {
                        return "<span class='valor_editable'>" + 'USD ' + row.precio_venta + "</span>";
                    } else {
                        return "<span class='valor_editable'>" + 'S/ ' + row.precio_venta + "</span>";
                    }
                },
            }, //15
            { "data": "nota", "visible": false, "className": 'row_editable', 
                render: function ( data, type, row ) {
                    return "<div class='valor_editable'>" + row.nota +"</div>";
                }, 
            }, //16
            { "data": "stock", "visible": true, "className": 'row_editable_NO', 
                render: function ( data, type, row ) {
                    if((row.stock - row.stock_minimo) <= 7) {
                        return '<span title="Las Existencias Actuales Están Cerca del Stock Minimo" class="label bg-danger">' + '<a style="color: #fafafb !important;" onclick="ver_stock_varias_sucursales(' + "'" + row.codigo + "'" + ','+ row.idsucursal +')" href="javascript:void(0)">' + row.stock + '</a></span>';
                    } else {
                        return '<span class="label bg-success">' + '<a style="color: #fafafb !important;" onclick="ver_stock_varias_sucursales(' + "'" + row.codigo + "'" + ','+ row.idsucursal +')" href="javascript:void(0)">' + row.stock + '</a></span>';
                    }
                },
            }, //17
            { "data": "stock_minimo", "visible": false, "className": 'row_editable', 
                render: function ( data, type, row ) {
                    return "<span class='valor_editable'>" + row.stock_minimo +"</span>";
                }, 
            }, //18
            { "data": "afecto_icbper", "visible": false, "className": 'row_editable', 
                render: function ( data, type, row ) {
                    return "<span class='valor_editable'>" + row.afecto_icbper +"</span>";
                }, 
            }, //19
            { "data": "multi_precio", "visible": false }, //20
            { "data": "fecha_vencimiento", "visible": false , "className": 'row_editable', 
                render: function ( data, type, row ) {
                    if((row.fecha_vencimiento === null) || (row.fecha_vencimiento === undefined) || (row.fecha_vencimiento == '') ) {
                        return "<div class='valor_editable'>" + '<span class="label bg-primary">Sin Vencimiento</span>' + "</div>";
                    } else {
                        return "<div class='valor_editable'>" + row.fecha_vencimiento + "</div>";
                    }
                },
            }, //21
            { "data": "marca", "visible": false , "className": 'row_editable', 
                render: function ( data, type, row ) {
                    if((row.marca === null) || (row.marca === undefined) || (row.marca == '') ) {
                        return "<div class='valor_editable'>" + '<span class="label bg-info"> Sin Marca </span>' + '</div>';
                    } else {
                        return "<div class='valor_editable'>" + row.marca + '</div>';
                    }
                },
            }, //22

            { "data": "peso", "visible": false }, //23

            { "data": "opciones", "visible": true }, //24
          ],
        stateSave: true,
        initComplete: function(){
            // Apply the search
            /*
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
            */
            var $searchInput = $('div.dataTables_filter input');
            $searchInput.unbind();
            $searchInput.bind('keyup', function(e) {
                window.clearTimeout(dataTableFilterTimeout);
                var valor_busqueda = this.value;
                //if(valor_busqueda.length > 3) {
                    dataTableFilterTimeout = setTimeout(function(){
                        dataTable_lista_productos.search( valor_busqueda ).draw();
                    }, dataTableFilterWait);
                //}
            });
            
            $(light).unblock();
        }
    } );
}

function eliminar_producto(idproducto){
    var light = $("#content_lista_productos");
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
    swal({
        title: "Confirmación!",
        text: "¿Estás seguro que deseas eliminar este producto?",
        type: "warning",
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",       
        cancelButtonColor: "#aaaaaa",
        confirmButtonText: "Si, Adelante!",
        closeOnConfirm: false
    },function(isconfirmed){
        if(isconfirmed) {
            var confirmado = 'si';
            $.ajax({
                url: '/facturacionv8/producto/eliminar_producto',
                data: {idproducto: idproducto},
                method :  'POST',
                dataType : "json"
            }).then(function(data){
                if(data.respuesta == 'ok') {
                    swal({   
                        title: data.titulo,   
                        text: data.mensaje,
                        html: true,
                        type: "success",   
                        confirmButtonColor: "#00BCD4",   
                        confirmButtonText: "OK"
                    }, function() {
                        $(light).unblock();
                        dataTable_lista_productos.ajax.reload(null, false);
                    });
                } else {
                    swal({   
                        title:'Error',   
                        text: data.mensaje,
                        html: true,
                        type: "error", 
                        confirmButtonColor: "#00BCD4",   
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
                    confirmButtonColor: "#00BCD4",   
                    confirmButtonText: "Ok"
                }, function() {
                    $(light).unblock();
                });
            });
        }else{
            $(light).unblock();
            return;
        }
      
    });
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

function inicializar_grid() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	detalle_documento = $('#lista_productos_traslado');

	detalle_documento = detalle_documento.jqGrid({
        url: '',
        datatype: 'json',
        mtype: 'POST',
        colNames: [
            'row_identificadador',
            'idarticulo',
            'Descripcion',
            'Unidad-Med',
            'Cantidad',
            'Código'
        ],
        colModel: [
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'idarticulo', index: '2', hidden: true},
            { name: 'descripcion', index: '3', width: 350, hidden: false},
            { name: 'unidadmedida', index: '4', width: 100, hidden: false},
            { name: 'cantidad', index: '5', width: 100, hidden: false, align: "right", sorttype: 'float' },
            { name: 'codigo_producto', index: '1', hidden: true}
        ],
        height: 100,
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
            responsive_table($(".jqGrid_traslado"));
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

function add_to_detalle_traslado() {
    var id_almacenorigen = parseInt($('#select_almacen_origen').val());
    var idarticulo = parseInt($('#select_producto_buscar_3').val());
    var descripcion_prod_traslado = $('#descripcion_prod_traslado').val();
    var cantidad = parseFloat($('#cantidad_traslado').val());
    var unidad_medida = $('#unidad_medida_traslado').val();
    var codigo_producto = $("#codigo_producto_traslado").val();
    
    if(isNaN(id_almacenorigen) || (id_almacenorigen === null) || (id_almacenorigen === undefined) || (id_almacenorigen == '') || (id_almacenorigen <= 0)) {
        swal({   
            title:'Error',   
            text: 'Primero debes Seleccionar el Almacén de Origen!',
            html: true,
            type: "error",  
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok", 
        }, function() {
            
        });

        return false;
    }
  
    if(isNaN(idarticulo) || idarticulo === null || idarticulo === undefined || idarticulo == '' || idarticulo <= 0) {
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
    
    if(descripcion_prod_traslado == '') {
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

    if(isNaN(cantidad) || cantidad === null || cantidad === undefined || cantidad == '' || cantidad <= 0) {
        swal({   
            title:'Error',   
            text: 'Debes Ingresar la Cantidad que se trasladará al almacén de destino',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        return false;
    }

    var descripcion_sin_espacios = descripcion_prod_traslado.replace(/\s/g, "").toLowerCase();
    var descripcion_sin_espacios = removeSpecialChars(descripcion_sin_espacios);
    var row_identificadador = descripcion_sin_espacios + '|.|.|' + idarticulo;

    var data = {
        row_identificadador: row_identificadador,
        idarticulo: idarticulo,
        descripcion: descripcion_prod_traslado,
        unidadmedida: unidad_medida, //$('#producto_unidadmedida').val(),
        cantidad: cantidad,
        codigo_producto: codigo_producto
    };

    var key_row = $("#key_row_traslado").val();
	if(key_row != '') {
        var su = jQuery('#lista_productos_traslado').jqGrid('setRowData', key_row, data);
        $("#key_row_traslado").val("");
        $("#select_producto_buscar_3").empty();
        btn_productos_accion = 'agregar';
        $('#select_producto_buscar_3').select2('open');

        $('#descripcion_prod_traslado').val('');
        $('#cantidad_traslado').val('');
        $('#unidad_medida_traslado').val('');
	} else {
        if(valida_si_existe_item(row_identificadador)) {
            swal({   
                title:'Error',   
                text: 'No puede agregar items repetidos al detalle!',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                $("#key_row_traslado").val("");
                $("#select_producto_buscar_3").empty();
                btn_productos_accion = 'agregar';
                $('#select_producto_buscar_3').select2('open');
				return false;
            });
            
        } else {
            var su = jQuery('#lista_productos_traslado').addRowData(row_identificadador, data, 'last');
            $("#key_row_traslado").val("");
            $("#select_producto_buscar_3").empty();
            btn_productos_accion = 'agregar';
            $('#select_producto_buscar_3').select2('open');

            $('#descripcion_prod_traslado').val('');
            $('#cantidad_traslado').val('');
            $('#unidad_medida_traslado').val('');
        }
    }
}

function removeSpecialChars(str) {
    return str.replace(/(?!\w|\s)./g, '')
    .replace(/\s+/g, ' ')
    .replace(/^(\s*)([\W\w]*)(\b\s*$)/g, '$2');
}

function valida_si_existe_item(row_indentificador_nuevo) {
    var grid = jQuery("#lista_productos_traslado");
    var ids = grid.jqGrid('getDataIDs');
    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var row_indentificador = grid.jqGrid('getCell', id, 'row_identificadador');
        if(row_indentificador == row_indentificador_nuevo) {
            return true;
        }
    }

    return false;
}

function editar_item_tabla(rowid) {
    //var rowid = jQuery("#lista_productos_traslado").jqGrid('getGridParam', 'selrow');
    if(rowid === undefined || rowid == '') {
        return false;
    }

    $("#key_row_traslado").val(rowid);
    var data = jQuery("#lista_productos_traslado").jqGrid('getRowData', rowid);

    $("#select_producto_buscar_3").append('<option value="' + data.idarticulo + '">' + data.descripcion + '</option>');
    $("#select_producto_buscar").val(data.idarticulo).trigger("select2:select");
    $("#descripcion_prod_traslado").val(data.descripcion);
    $("#unidad_medida_traslado").val(data.unidadmedida).trigger("input");
    $("#cantidad_traslado").val(data.cantidad);
    $("#codigo_producto_traslado").val(data.codigo_producto);

}

function iniciar_traslado() { 
    var light = $("#content_vm_traslado");

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

    var gridData = jQuery("#lista_productos_traslado").getRowData();
	var postData = JSON.stringify(gridData);

	var datastring = $("#data_traslado").serializeArray();
	datastring.push({ name: "detalle", value: postData });

	$.ajax({
        url : '/facturacionv8/producto/iniciar_traslado',
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
					datastring.push({name: 'confirmado', value: confirmado});
					$.ajax({
						url : '/facturacionv8/producto/iniciar_traslado',
						method :  'POST',
						data: datastring,
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
                                $('#lista_productos_traslado').jqGrid('clearGridData');
                                $("#nota_traslado").val('');
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "Ok"
								}, function(){  
                                    
								});
								$(light).unblock();
								return;
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

function eliminar_producto_detalle() {
    var rowid = jQuery('#lista_productos_traslado').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#lista_productos_traslado");
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