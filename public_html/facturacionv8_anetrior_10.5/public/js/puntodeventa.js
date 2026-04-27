$(function(){
    $('.select_producto_buscar').on('change', get_data_producto);
    $('.id_unidad_medida').select2(); 
    $('.select_producto_buscar').select2();
    $('.select2').select2({
        minimumResultsForSearch: -1
    });  
    $(".switch").bootstrapSwitch();
    
    get_lista_monedas($("#id_cod_moneda"));
    get_lista_unidades_medida($("#id_unidad_medida"));
    $("#type_document").on('change', function() {
		var id_document = this.value; 
		var text_document = $("#type_document option:selected").text();
        $(".type_id").html(text_document);
        if(id_document == '1' || id_document == '6'){
            $("#type_id").addClass("dni");
        } else{
            $("#type_id").removeClass("dni");
        }
		
    });
 
   $(".search_document").click(function() {
        if($("#cliente_numerodocumento-flexdatalist").val() != '') {
            var num_doc = $("#cliente_numerodocumento-flexdatalist").val();
            $("#cliente_numerodocumento").val($("#cliente_numerodocumento-flexdatalist").val());
        } else {
            var num_doc = $("#cliente_numerodocumento").val();
        }
         
		if(num_doc != '') {
            var tipo_doc = $("#cliente_tipo_docidentidad").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                consultar_numero_doc($("#id_cliente_documento"), num_doc, $("#cliente_nombre"), $("#cliente_direccion"), $("#select_codigoubigeo"), tipo_doc, $("#cliente_email"), $("#numero_celular"));
            }
		}
	});
});

function get_data_producto() {
    var idproducto = $("#select_producto_buscar").val();
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var id_cliente_documento = $("#id_cliente_documento").val();

    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto, idcliente: id_cliente_documento},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#producto_idproducto").val(data.producto.idproducto);
            $("#producto_codigo").val(data.producto.codigo);
            $("#producto_descripcion").val(data.producto.nombre);
            $("#producto_unidadmedida").val(data.producto.id_unidad_medida);
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
            
            $("#stock_actual").val(1000000);
            if(data.restriccion_stock == 'si') {
                if(data.producto.id_unidad_medida != 20) {
                    $("#msg_stock_actual_html").html('Stock: ' + data.producto.stock + ' ' + data.unidad_medida.simbolo);
                    $("#stock_actual").val(data.producto.stock);
                }
            } else {
                $("#msg_stock_actual_html").hide();
                $("#stock_actual").val(1000000);
            }
            
            var tipo_moneda = $("#codmoneda_comprobante").val();
            var valor_con_igv = round_math(parseFloat(data.producto.valor_con_igv), numero_decimales);
            var valor_sin_igv = round_math(parseFloat(data.producto.valor_sin_igv), numero_decimales);
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
                html_precios = html_precios + '\
                <li><a href="javascript:void(0);" data-precio="'+precio_item.precio+'" onclick="asignar_precio_de_lista('+precio_item.precio+')"><span class="badge badge-success pull-right">' + precio_item.precio + '</span> ' + precio_item.nombre + '</a></li>\
                ';
            });
            
            if(data.producto.multi_precio == 'si') {
                if(html_precios != '') {
                    $("#contenido_listaprecios").html(html_precios);
                    $("#btn_listaprecios").show();
                } else {
                    $("#btn_listaprecios").hide();
                }
            } else {
                $("#btn_listaprecios").hide();
            }

            if(data.html_ultimo_precio != '') {
                $("#contenido_listaprecios").html($("#contenido_listaprecios").html() + data.html_ultimo_precio);
                $("#btn_listaprecios").show();
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