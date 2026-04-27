$(function() {
	$(".btn_generar_codigo").click(function(){
		generar_codigo($("#nuevo_producto_codigo"), 7, $(".btn_generar_codigo > i"));
	});
	get_lista_unidades_medida($("#nuevo_producto_unidad"));
	get_lista_monedas($("#nuevo_producto_moneda"));
	get_lista_tipoafectacionigv($("#nuevo_producto_tipo_afect_igv")); 
	get_lista_categorias($("#nuevo_producto_categoria"));
    $(".btn_guardar_nuevo_producto").click(guardar_nuevo_producto);
    
    $(".new_prod_inputs_selected").click(function() {
        $(this).select();
    });

    $("#nuevo_producto_valor_con_igv").on('input', function() {
        calcular_precio_sin_igv_nuevoprod();
        if($("#txt_porcentaje_ganancia").val() != '') {
            $("#txt_porcentaje_ganancia").trigger('input');
        } else if ($("#nuevo_producto_preciominimo").val() != '') {
            $("#nuevo_producto_preciominimo").trigger('input');
        }
    });
    
    $("#nuevo_producto_valor_sin_igv").on('input', function() {
        calcular_precio_con_igv_nuevoprod();
        if($("#txt_porcentaje_ganancia").val() != '') {
            $("#txt_porcentaje_ganancia").trigger('input');
        } else if ($("#nuevo_producto_preciominimo").val() != '') {
            $("#nuevo_producto_preciominimo").trigger('input');
        }
    });

    $("#nuevo_producto_moneda").on("change", function() {
        let id_cod_moneda = $("#nuevo_producto_moneda").val();
        if(id_cod_moneda == 'USD') {
            $(".simbolo_moneda_nuevoproducto").html('USD');
            $("#content_tipo_cambio").show('slide');
            $("#content_categoria").attr('class', 'col-md-4 col-xs-6');
            $("#content_unidad_medida").attr('class', 'col-md-4 col-xs-6');
        } else {
            $(".simbolo_moneda_nuevoproducto").html('S/');
            $("#content_tipo_cambio").hide('slide');
            $("#content_categoria").attr('class', 'col-md-6 col-xs-6');
            $("#content_unidad_medida").attr('class', 'col-md-6 col-xs-6');
        }
    });

    $("#txt_porcentaje_ganancia").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        var porcentaje_ganancia = 1 + round_math(parseFloat($("#txt_porcentaje_ganancia").val())/100, 2);
        var precio_venta = parseFloat($("#nuevo_producto_valor_con_igv").val());
        var precio_minimo = round_math(porcentaje_ganancia*precio_venta, numero_decimales);
        $("#nuevo_producto_preciominimo").val(precio_minimo);
    });

    $("#nuevo_producto_preciominimo").on('input', function() {
        var numero_decimales = 2;
        if (typeof num_decimales !== 'undefined') {
            if(num_decimales > 2) {
                numero_decimales = num_decimales;
            }
        }
        
        var precio_venta = parseFloat($("#nuevo_producto_valor_con_igv").val());
        var precio_minimo = parseFloat($("#nuevo_producto_preciominimo").val()) - precio_venta;
        var porcentaje_ganancia = round_math(precio_minimo*100/precio_venta, 2);
        $("#txt_porcentaje_ganancia").val(porcentaje_ganancia);
    });

    $("#nuevo_producto_unidad").select2({
        language: "es",
        dropdownParent: $('#vm_agregar_articulo')
    });
});

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

    var state = $("#nuevo_producto_afecto_icbper").bootstrapSwitch('state');
    if(state) {
        afecto_icbper = 'si';
    } else {
        afecto_icbper = 'no';
    }
    
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
        nota_producto: '',
        id_unidad_medida: $("#nuevo_producto_unidad").val(),
        id_cod_moneda: $("#nuevo_producto_moneda").val(),
        stock: $("#nuevo_producto_stock").val(),
        stock_minimo:  $("#nuevo_producto_stock_minimo").val(),
        select_sucursal: $("#select_almacen").val(),
        tipo_cambio_producto: $("#new_producto_tipodecambio").val(),
        opcion_afecto_icbper: afecto_icbper,
        precio_venta_minimo: $("#nuevo_producto_preciominimo").val()
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
                $('.opciones_producto a[href="#buscar_producto"]').tab('show');
                
                $("#key_row").val("");
                $("#select_producto_buscar").empty();
                $("#frm_producto")[0].reset();
                btn_productos_accion = 'agregar';

                //$('#select_producto_buscar').append('<option value="'+ data.producto.idproducto +'">'+ data.producto.nombre + ' - ' + $("#nuevo_producto_unidad option:selected").text() + '</option>');
                $('#select_producto_buscar').append('<option value="'+ data.producto.idproducto +'">'+ data.producto.nombre + '</option>');
                $('#select_producto_buscar').val(data.producto.idproducto).select2().trigger('change');
                inicializar_buscar_productos_registrados();

                //$("#frm_nuevo_producto")[0].reset();
                reset_form_registro_new_producto();
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
    var factor_igv = get_igv_sunat();
    var igv_percent = parseFloat(factor_igv + 1);
    if($('#nuevo_producto_valor_con_igv').val() == '' || $('#nuevo_producto_valor_con_igv').val() <= 0 || isNaN($('#nuevo_producto_valor_con_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#nuevo_producto_valor_con_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), 2);
    $("#nuevo_producto_valor_sin_igv").val(precio_sin_igv);
}

function calcular_precio_con_igv_nuevoprod() {
    var factor_igv = get_igv_sunat();
    var igv_percent = parseFloat(1 + factor_igv);
    if($('#nuevo_producto_valor_sin_igv').val() == '' || $('#nuevo_producto_valor_sin_igv').val() <= 0 || isNaN($('#nuevo_producto_valor_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#nuevo_producto_valor_sin_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), 2);
    $("#nuevo_producto_valor_con_igv").val(precio_sin_igv);
}