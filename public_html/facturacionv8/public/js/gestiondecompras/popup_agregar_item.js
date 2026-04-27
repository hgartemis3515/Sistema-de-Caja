//modificado
function add_to_detalle() {
    var idarticulo = $('#producto_idproducto').val();
    var text_tipo_afecigv = $('#producto_tipo_afect_igv').select2().find(":selected").text().split(' ');
    var id_tipoafectacionigv = parseInt($('#producto_tipo_afect_igv').val()) + 0;
    var precio = $('#producto_preciounidad').val();
    var cantidad = $('#producto_cantidad').val();
    var subtotal = $('#producto_subtotal').val();
    var igv_producto = $('#producto_igv').val();
    var importe = $('#producto_total').val();
    var codigo = $('#producto_codigo').val();
    var descripcion_prod = $('#producto_descripcion').val();

    var n = Math.floor(Math.random() * 11);
    var k = Math.floor(Math.random() * 1000000);
    var m = String.fromCharCode(n) + k;

    //var descripcion_sin_espacios = descripcion_prod.replace(/\s/g, "").toLowerCase();
    //var descripcion_sin_espacios = removeSpecialChars(descripcion_sin_espacios);
    var row_identificadador = m+'|.|.|'+codigo+'|.|.|'+precio+'|.|.|'+idarticulo;

    var afecto_icbper = 'no';
    var monto_impuesto_icbper = 0.3;

    if (typeof impuesto_icbper !== 'undefined') {
        if(impuesto_icbper > 0) {
            monto_impuesto_icbper = impuesto_icbper;
        }
    }

    var state = $("#opcion_afecto_icbper").bootstrapSwitch('state');
    if(state) {
        afecto_icbper = 'si';
        subtotal_icbper = monto_impuesto_icbper*cantidad;
    } else {
        afecto_icbper = 'no';
        subtotal_icbper = 0;
    }

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
            precioconigv        : $(this).data('precioconigv'),
            preciosinigv        : $(this).data('preciosinigv'),
            idcodmoneda        : $(this).data('idcodmoneda')
        };

        opciones_select.push(opcion_select);
    });

    var json_select_unidadmedida = JSON.stringify(opciones_select);
    var tipo_unidad = $("#producto_unidadmedida").find(':selected').data('tipo');
    var idpresentacion = $("#producto_unidadmedida").find(':selected').data('idpresentacion');

    //datos para el cambio de precio de venta en producto
    var pventa_moneda = !($("#pventa_moneda_original").val() == '') ? 'PEN' : ($("#pventa_moneda_original").val() == 'PEN' ? 'PEN' : 'USD');

    var pventa_actual = parseFloat($("#producto_precio_venta_actual").val());
    var porcentaje_pventa = parseFloat($("#porcentaje_ganancia_maxima").val());
    var pventa_nuevo = parseFloat($("#producto_nuevo_precio_venta").val());

    var pventa_minimo_actual = parseFloat($("#producto_precio_venta_minimo").val());
    var porcentaje_pventa_minimo = parseFloat($("#porcentaje_ganancia_minima").val());
    var pventa_minimo_nuevo = parseFloat($("#producto_nuevo_precio_minimo").val());

    var tiene_presentaciones = !($("#prod_tiene_presentaciones").val() == '') ? 'no' : ($("#prod_tiene_presentaciones").val() == 'si' ? 'si' : 'no');
    var tiene_multiprecio = !($("#prod_tiene_multiprecio").val() == '') ? 'no' : ($("#prod_tiene_multiprecio").val() == 'si' ? 'si' : 'no');

    var modificar_precio_venta = ($('#opcion_actualizar_pventa').bootstrapSwitch('state') === true)?'si':'no';
    //fin datos para el cambio de precio de venta

    var data = {
        row_identificadador: row_identificadador,
        idarticulo: idarticulo,
        afecto_icbper: afecto_icbper,
        subtotal_icbper: subtotal_icbper,
        id_tipoafectacionigv: id_tipoafectacionigv,
        descripcion: $('#producto_descripcion').val(),
        text_tipo_afecigv: text_tipo_afecigv[0],
        idunidadmedida: $('#producto_unidadmedida').val(),
        unidadmedida: $("#producto_unidadmedida option:selected" ).text(), //$('#producto_unidadmedida').val(),
        precio: precio,
        id_cod_moneda: $("#id_cod_moneda").val(),
        cantidad: cantidad,
        subtotal: subtotal,
        igv: igv_producto,
        importe: importe,
        estado: 'V',
        codigo: codigo,

        select_unidadmedida: json_select_unidadmedida,
        tipo_unidad: tipo_unidad,
        idpresentacion: idpresentacion,

        //data para cambio de precio de venta
        pventa_moneda: pventa_moneda,
        pventa_actual: pventa_actual,
        porcentaje_pventa: porcentaje_pventa,
        pventa_nuevo: pventa_nuevo,
    
        pventa_minimo_actual: pventa_minimo_actual,
        porcentaje_pventa_minimo: porcentaje_pventa_minimo,
        pventa_minimo_nuevo: pventa_minimo_nuevo,
    
        tiene_presentaciones: tiene_presentaciones,
        tiene_multiprecio: tiene_multiprecio,
        
        modificar_precio_venta: modificar_precio_venta
        //fin data para cambio de precio de venta
    };

    var key_row = $("#key_row").val();
    if(key_row != '') {
        if(idarticulo > 0) {
            var su = jQuery('#detalle_documento').jqGrid('setRowData', key_row, data, { background:'#4CAF50', color: '#fff'});
        } else {
            var su = jQuery('#detalle_documento').jqGrid('setRowData', key_row, data, { background:'#fff', color: '#222222'});
        }
        calcular_totales_documento();
        //$("#vm_agregar_articulo").modal("hide");

        $("#key_row").val("");
        $("#select_producto_buscar").empty();
        $("#frm_producto")[0].reset();
        btn_productos_accion = 'agregar';
        $(".content_propiedades_producto").hide();
        $('#select_producto_buscar').select2('open');
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
            var su = jQuery('#detalle_documento').addRowData(row_identificadador, data, 'last');
            calcular_totales_documento();
           
            $("#key_row").val("");
            $("#select_producto_buscar").empty();
            $("#frm_producto")[0].reset();
            btn_productos_accion = 'agregar';
            $(".content_propiedades_producto").hide();
            $('#select_producto_buscar').select2('open');
            //$("#vm_agregar_articulo").modal("hide");
        }
    }
}

//modificado
function recalcular_por_cambio_de_moneda() {
    var grid = jQuery("#detalle_documento");
    var ids = grid.jqGrid('getDataIDs');
    var tipo_moneda = $("#codmoneda_comprobante").val();
    var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
	$(".simbolo_moneda").html($("#codmoneda_comprobante").find(':selected').data('simbolo'));
	
	$("#content_serie_comprobante").removeAttr('class');
	$("#content_numero_comprobante").removeAttr('class');
	$("#content_fecha_comprobante").removeAttr('class');
	$("#content_codmoneda_comprobante").removeAttr('class');
	$("#content_tipo_cambio_comprobante").removeAttr('class');
	
	if(tipo_moneda == 'PEN') {
		$("#content_serie_comprobante").attr('class', 'form-group col-md-3');
		$("#content_numero_comprobante").attr('class', 'form-group col-md-3');
		$("#content_fecha_comprobante").attr('class', 'form-group col-md-3');
		$("#content_codmoneda_comprobante").attr('class', 'form-group col-md-3');
		$("#content_tipo_cambio_comprobante").hide('slide');
	}

	if(tipo_moneda == 'USD') {
		$("#content_serie_comprobante").attr('class', 'form-group col-md-2');
		$("#content_numero_comprobante").attr('class', 'form-group col-md-2');
		$("#content_fecha_comprobante").attr('class', 'form-group col-md-3');
		$("#content_codmoneda_comprobante").attr('class', 'form-group col-md-3');
		$("#content_tipo_cambio_comprobante").attr('class', 'form-group col-md-2');
		$("#content_tipo_cambio_comprobante").show('slide');
	}

    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var precio = parseFloat(grid.jqGrid('getCell', id, 'precio'));
        var subtotal = parseFloat(grid.jqGrid('getCell', id, 'subtotal'));
        var igv = parseFloat(grid.jqGrid('getCell', id, 'igv'));
        var importe = parseFloat(grid.jqGrid('getCell', id, 'importe'));
        var id_cod_moneda = grid.jqGrid('getCell', id, 'id_cod_moneda');
        var codigo = grid.jqGrid('getCell', id, 'codigo');
        var subtotal_icbper = grid.jqGrid('getCell', id, 'subtotal_icbper');

        var select_unidadmedida = grid.jqGrid('getCell', id, 'select_unidadmedida');
        var tipo_unidad = grid.jqGrid('getCell', id, 'tipo_unidad');
        var idpresentacion = grid.jqGrid('getCell', id, 'idpresentacion');

        //data para cambio de precio de venta
        var pventa_moneda = grid.jqGrid('getCell', id, 'pventa_moneda');
        var pventa_actual = grid.jqGrid('getCell', id, 'pventa_actual');
        var porcentaje_pventa = grid.jqGrid('getCell', id, 'porcentaje_pventa');
        var pventa_nuevo = grid.jqGrid('getCell', id, 'pventa_nuevo');
    
        var pventa_minimo_actual = grid.jqGrid('getCell', id, 'pventa_minimo_actual');
        var porcentaje_pventa_minimo = grid.jqGrid('getCell', id, 'porcentaje_pventa_minimo');
        var pventa_minimo_nuevo = grid.jqGrid('getCell', id, 'pventa_minimo_nuevo');
    
        var tiene_presentaciones = grid.jqGrid('getCell', id, 'tiene_presentaciones');
        var tiene_multiprecio = grid.jqGrid('getCell', id, 'tiene_multiprecio');

        var modificar_precio_venta = grid.jqGrid('getCell', id, 'modificar_precio_venta');
        //fin data para cambio de precio de venta

        if(tipo_moneda == 'USD') {
            if(id_cod_moneda == 'PEN') {
                precio = round_math(parseFloat(precio/tipo_cambio), 2);
                subtotal = round_math(parseFloat(subtotal/tipo_cambio), 2);
                igv = round_math(parseFloat(igv/tipo_cambio), 2);
                importe = round_math(parseFloat(importe/tipo_cambio), 2);
                subtotal_icbper = round_math(parseFloat(subtotal_icbper/tipo_cambio), 2);
            }
        }

        if(tipo_moneda == 'PEN') {
            if(id_cod_moneda == 'USD') {
                precio = round_math(parseFloat(precio*tipo_cambio), 2);
                subtotal = round_math(parseFloat(subtotal*tipo_cambio), 2);
                igv = round_math(parseFloat(igv*tipo_cambio), 2);
                importe = round_math(parseFloat(importe*tipo_cambio), 2);
                subtotal_icbper = round_math(parseFloat(subtotal_icbper*tipo_cambio), 2);
            }
        }

        var data = {
            row_identificadador: grid.jqGrid('getCell', id, 'row_identificadador'),
            idarticulo: grid.jqGrid('getCell', id, 'idarticulo'),
            afecto_icbper: grid.jqGrid('getCell', id, 'afecto_icbper'),
            subtotal_icbper: subtotal_icbper,
            id_tipoafectacionigv: grid.jqGrid('getCell', id, 'id_tipoafectacionigv'),
            descripcion: grid.jqGrid('getCell', id, 'descripcion'),
            text_tipo_afecigv: grid.jqGrid('getCell', id, 'text_tipo_afecigv'),
            idunidadmedida: grid.jqGrid('getCell', id, 'idunidadmedida'),
            unidadmedida: grid.jqGrid('getCell', id, 'unidadmedida'),
            precio: precio,
            id_cod_moneda: tipo_moneda,
            cantidad: grid.jqGrid('getCell', id, 'cantidad'),
            subtotal: subtotal,
            igv: igv,
            importe: importe,
            estado: 'V',
            codigo: codigo,

            select_unidadmedida: select_unidadmedida,
            tipo_unidad: tipo_unidad,
            idpresentacion: idpresentacion,

            //data para cambio de precio de venta
            pventa_moneda: pventa_moneda,
            pventa_actual: pventa_actual,
            porcentaje_pventa: porcentaje_pventa,
            pventa_nuevo: pventa_nuevo,
        
            pventa_minimo_actual: pventa_minimo_actual,
            porcentaje_pventa_minimo: porcentaje_pventa_minimo,
            pventa_minimo_nuevo: pventa_minimo_nuevo,
        
            tiene_presentaciones: tiene_presentaciones,
            tiene_multiprecio: tiene_multiprecio,

            modificar_precio_venta: modificar_precio_venta
            //fin data para cambio de precio de venta
        };

        grid.jqGrid('setRowData', id, data);
        calcular_totales_documento();
    }
}

//modificado
function editar_item_tabla(rowid) {
    //var rowid = jQuery("#detalle_documento").jqGrid('getGridParam', 'selrow');
    if(rowid === undefined || rowid == '') {
        return false;
    }

    $("#key_row").val(rowid);
    var data = jQuery("#detalle_documento").jqGrid('getRowData', rowid);

    $("#producto_idproducto").val(data.idarticulo);
    $("#id_cod_moneda").val(data.id_cod_moneda);
    $("#key_row").val(rowid);
    $("#select_producto_buscar").append('<option value="' + data.idarticulo + '">' + data.descripcion + '</option>');
    $("#select_producto_buscar").val(data.idarticulo).trigger("select2:select");
    $("#producto_tipo_afect_igv").val(data.id_tipoafectacionigv).trigger("change").trigger("select2:select");
    //$("#producto_unidadmedida").val(data.idunidadmedida).trigger("change").trigger("select2:select");
    $("#producto_descripcion").val(data.descripcion);
    $("#producto_preciounidad").val(data.precio).trigger("input");
    $("#producto_cantidad").val(data.cantidad);
    $("#producto_subtotal").val(data.subtotal);
    $("#producto_igv").val(data.igv);
    $("#producto_total").val(data.importe);
    $("#producto_codigo").val(data.codigo);


    //data para cambio de precio de venta
    $("#pventa_moneda_original").val(data.pventa_moneda);
    $("#prod_tiene_presentaciones").val(data.tiene_presentaciones);
    $("#prod_tiene_multiprecio").val(data.tiene_multiprecio);

    $("#producto_precio_venta_actual").val(data.pventa_actual);
    $("#porcentaje_ganancia_maxima").val(data.porcentaje_pventa);
    $("#producto_nuevo_precio_venta").val(data.pventa_nuevo);

    $("#producto_precio_venta_minimo").val(data.pventa_minimo_actual);
    $("#porcentaje_ganancia_minima").val(data.porcentaje_pventa_minimo);
    $("#producto_nuevo_precio_minimo").val(data.pventa_minimo_nuevo);

    if(data.afecto_icbper == 'si') {
        $('#opcion_afecto_icbper').bootstrapSwitch('state', true);
    } else {
        $('#opcion_afecto_icbper').bootstrapSwitch('state', false);
    }

    if(data.modificar_precio_venta == 'si') {
        $('#opcion_actualizar_pventa').bootstrapSwitch('state', true);
    } else {
        $('#opcion_actualizar_pventa').bootstrapSwitch('state', false);
    }

    array_select_opcion_unidades = JSON.parse(data.select_unidadmedida);
    $('#producto_unidadmedida').empty();
    $.each(array_select_opcion_unidades, function(key, item) {
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
                "data-precioconigv"         : item.precioconigv,
                "data-preciosinigv"         : item.preciosinigv,
                "data-idcodmoneda"          : item.idcodmoneda
            })
        );
    });

    $("#producto_unidadmedida").val(data.idunidadmedida).trigger("select2:select");

    $("#nuevo_producto_nombre_servicio").val(data.descripcion);
    $("#nuevo_producto_precio_compra").val(data.precio);
    $("#nuevo_producto_moneda").val($("#codmoneda_comprobante").val()).trigger("change").trigger("select2:select");
}

//modificado
function valida_si_existe_item(row_indentificador_nuevo) {
    var grid = jQuery("#detalle_documento");
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

//revisado
function eliminar_producto_detalle() {
    var rowid = jQuery('#detalle_documento').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#detalle_documento");
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
        calcular_totales_documento();
    }
}

function get_total_row_en_lista(rowid) {
    if(rowid === undefined || rowid == '' || rowid <= 0) {
        return 0;
    }
    
    var data = jQuery("#detalle_documento").jqGrid('getRowData', rowid);
    return data.importe;
}