/*
$(function() {
    //get_lista_almacenes($("#select_almacen"), 'si');
	get_lista_unidades_medida($("#nuevo_producto_unidad"));
	get_lista_monedas($("#nuevo_producto_moneda"));
	get_lista_tipoafectacionigv($("#nuevo_producto_tipo_afect_igv"));
	get_lista_categorias($("#nuevo_producto_categoria"));

	$("#nuevo_producto_valor_con_igv").on('input', function() {
        calcular_precio_sin_igv_nuevoprod();
    });
    
    $("#nuevo_producto_valor_sin_igv").on('input', function() {
        calcular_precio_con_igv_nuevoprod();
	});
	
	$(".btn_generar_codigo").click(function(){
	    generar_codigo($("#nuevo_producto_codigo"), 7, $(".btn_generar_codigo > i"));
	});
    
    $(".btn_guardar_nuevo_producto").click(guardar_nuevo_producto);
    
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

    $(".switch").bootstrapSwitch();
    $('#restr_stock').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
          if(state == true){
              var state_restr_stock = 'si';
              $("#restriccion_stock").val(state_restr_stock);
          }
        } else {
            if(state == false){
                var state_restr_stock = 'no';
                $("#restriccion_stock").val(state_restr_stock);
            }
        }
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

    var state = $("#opcion_afecto_icbper").bootstrapSwitch('state');
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
        restriccion_stock: $("#restriccion_stock").val(),
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
                $("#frm_nuevo_producto")[0].reset();
                $("#vm_agregar_articulo").modal("hide");
                get_lista_productos();
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

function calcular_precio_sin_igv_nuevoprod() {
    var igv_percent = parseFloat((18/100) + 1);
    if($('#nuevo_producto_valor_con_igv').val() == '' || $('#nuevo_producto_valor_con_igv').val() <= 0 || isNaN($('#nuevo_producto_valor_con_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#nuevo_producto_valor_con_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), 2);
    $("#nuevo_producto_valor_sin_igv").val(precio_sin_igv);
}

function calcular_precio_con_igv_nuevoprod() {
    var igv_percent = 1.18;
    if($('#nuevo_producto_valor_sin_igv').val() == '' || $('#nuevo_producto_valor_sin_igv').val() <= 0 || isNaN($('#nuevo_producto_valor_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#nuevo_producto_valor_sin_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), 2);
    $("#nuevo_producto_valor_con_igv").val(precio_sin_igv);
}
*/