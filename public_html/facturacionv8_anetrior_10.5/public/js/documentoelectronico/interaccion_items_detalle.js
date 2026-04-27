function editar_item_tabla(rowid) {
    //var rowid = jQuery("#detalle_documento").jqGrid('getGridParam', 'selrow');
    if(rowid === undefined || rowid == '') {
        return false;
    }

    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }
    
    var factor_igv_sunat = get_igv_sunat();

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
    $("#item_detraccion_codigo").val(data.item_detraccion_codigo);
    $("#item_detraccion_porcentaje").val(data.item_detraccion_porcentaje);
    $("#stock_actual").val(data.stock_actual);

    if(data.idunidadmedida != 20) {
        $("#msg_stock_actual_html").html(' <span class="text-info">(Stock: ' + data.stock_actual + ' ' + data.unidadmedida + ')</span>');
        $("#msg_stock_actual_html").show('slide');
    } else {
        $("#msg_stock_actual_html").hide();
    }

    $("#msg_stock_insuficiente").hide();
    if($("#c_restriccion_stock").val() == 'si' && parseFloat(data.cantidad) > parseFloat(data.stock_actual) && data.idunidadmedida != '20' && data.idunidadmedida != 'UND-20') {
        $("#msg_stock_insuficiente").hide('slide');
    } else {
        $("#msg_stock_insuficiente").hide();
    }

    if(!data.item_detraccion_codigo) {
        $("#content_msg_percepcion").hide();
        $("#item_detraccion_codigo").val('');
        $("#item_detraccion_porcentaje").val('');
    } else {
        //tiene detracción
        $("#content_msg_percepcion").show('slide');
        $("#content_msg_percepcion").html('<i class="icon-info3 text-size-mini position-left"></i> Producto con Detracción: ' + data.item_detraccion_porcentaje + '%');
        $("#item_detraccion_codigo").val(data.item_detraccion_codigo);
        $("#item_detraccion_porcentaje").val(data.item_detraccion_porcentaje);
    }
    
    if(data.html_lista_precios != '') {
        $("#contenido_listaprecios").html(unescapeHtml(data.html_lista_precios));
        $("#btn_listaprecios").show();
    } else {
        $("#contenido_listaprecios").html('');
        $("#btn_listaprecios").hide();
    }
    
    if(data.afecto_icbper == 'si') {
        $('#opcion_afecto_icbper').bootstrapSwitch('state', true);
    } else {
        $('#opcion_afecto_icbper').bootstrapSwitch('state', false);
    }

    $(".ver_stock_varias_sucursales").attr('onclick', 'ver_stock_varias_sucursales(' + "'" + data.codigo + "'" + ','+ $(".select_sucursal").val() +')');
    
    array_select_opcion_unidades = JSON.parse(data.select_unidadmedida);
    $('#producto_unidadmedida').empty();
    $.each(array_select_opcion_unidades, function(key, item) {
        var preciosinigv = parseFloat(item.preciosinigv);
        var precioconigv = round_math(preciosinigv*(1 + factor_igv_sunat), numero_decimales);
        
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
                "data-idcodmoneda"          : item.idcodmoneda,
                "data-preciominimo"         : item.preciominimo
            })
        );
    });

    $("#producto_unidadmedida").val(data.idunidadmedida).trigger("select2:select");
}

function get_total_row_en_lista(rowid) {
    if(rowid === undefined || rowid == '' || rowid <= 0) {
        return 0;
    }
    
    var data = jQuery("#detalle_documento").jqGrid('getRowData', rowid);
    return data.importe;
}

function calcular_porcentaje_detraccion() {
    //ayudará a calcular el porcentaje más bajo de la detracción
    var grid = jQuery("#detalle_documento");
    var ids = grid.jqGrid('getDataIDs');
    var codigo_detraccion_mayor = '0';
    var porcentaje_detraccion_mayor = 0;

    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var item_detraccion_codigo = grid.jqGrid('getCell', id, 'item_detraccion_codigo');
        var item_detraccion_porcentaje = grid.jqGrid('getCell', id, 'item_detraccion_porcentaje');
        if(!item_detraccion_porcentaje) {

        } else {
            if(item_detraccion_porcentaje > porcentaje_detraccion_mayor) {
                porcentaje_detraccion_mayor = item_detraccion_porcentaje;
                codigo_detraccion_mayor = item_detraccion_codigo;
            }
        }
    }

    return codigo_detraccion_mayor;
}

function recalcular_por_cambio_factor_igv() {
    var grid = jQuery("#detalle_documento");
    var ids = grid.jqGrid('getDataIDs');
    var tipo_moneda = $("#codmoneda_comprobante").val();
    var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
    $(".simbolo_moneda").html($("#codmoneda_comprobante").find(':selected').data('simbolo'));

    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    var factor_igv = get_igv_sunat();

    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];

        var id_tipoafectacionigv = parseInt(grid.jqGrid('getCell', id, 'id_tipoafectacionigv'));
        var cantidad = grid.jqGrid('getCell', id, 'cantidad');

        if(id_tipoafectacionigv == 10 || id_tipoafectacionigv == 7152) {
            //este código modifica el igv, dejando el precio intacto
            var precio = parseFloat(grid.jqGrid('getCell', id, 'precio'));
            var p_unit_sin_igv = precio/(factor_igv + 1);

            var importe = round_math(precio*cantidad, 2);
            var subtotal = round_math(importe/(1 + factor_igv), 2);
            var igv = round_math(importe - subtotal, 2);
            
            /*
            //Este código modifica el precio  y modifica el igv
            var p_unit_sin_igv = parseFloat(grid.jqGrid('getCell', id, 'p_unit_sin_igv')); //precio de venta unitario sin IGV
            var precio = round_math(p_unit_sin_igv*(factor_igv + 1), numero_decimales);

            var importe = round_math(precio*cantidad, 2);
            var subtotal = round_math(importe/(1 + factor_igv), 2);
            var igv = round_math(importe - subtotal, 2);
            */
        } else {
            var precio = parseFloat(grid.jqGrid('getCell', id, 'precio'));
            var p_unit_sin_igv = parseFloat(grid.jqGrid('getCell', id, 'p_unit_sin_igv'));
            var subtotal = parseFloat(grid.jqGrid('getCell', id, 'subtotal'));
            var igv = parseFloat(grid.jqGrid('getCell', id, 'igv'));
            var importe = parseFloat(grid.jqGrid('getCell', id, 'importe'));
        }
        
        var data = {
            row_identificadador: grid.jqGrid('getCell', id, 'row_identificadador'),
            idarticulo: grid.jqGrid('getCell', id, 'idarticulo'),
            afecto_icbper: grid.jqGrid('getCell', id, 'afecto_icbper'),
            subtotal_icbper: grid.jqGrid('getCell', id, 'subtotal_icbper'),
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
            codigo: grid.jqGrid('getCell', id, 'codigo'),
            html_lista_precios: grid.jqGrid('getCell', id, 'html_lista_precios'),
            item_detraccion_codigo: grid.jqGrid('getCell', id, 'item_detraccion_codigo'),
            item_detraccion_porcentaje: grid.jqGrid('getCell', id, 'item_detraccion_porcentaje'),

            select_unidadmedida: grid.jqGrid('getCell', id, 'select_unidadmedida'),
            tipo_unidad: grid.jqGrid('getCell', id, 'tipo_unidad'),
            idpresentacion: grid.jqGrid('getCell', id, 'idpresentacion'),

            p_unit_sin_igv: p_unit_sin_igv,
            factor_igv_sunat: factor_igv,
            stock_actual: grid.jqGrid('getCell', id, 'stock_actual')
        };

        grid.jqGrid('setRowData', id, data);
    }

    calcular_totales_documento();
}

function recalcular_por_cambio_de_moneda() {
    var grid = jQuery("#detalle_documento");
    var ids = grid.jqGrid('getDataIDs');
    var tipo_moneda = $("#codmoneda_comprobante").val();
    var tipo_cambio = round_math(parseFloat($("#tipo_cambio_comprobante").val()), 3);
    $(".simbolo_moneda").html($("#codmoneda_comprobante").find(':selected').data('simbolo'));
    var factor_igv = get_igv_sunat();

    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var id_cod_moneda = grid.jqGrid('getCell', id, 'id_cod_moneda');
        var precio = parseFloat(grid.jqGrid('getCell', id, 'precio'));
        var cantidad = parseFloat(grid.jqGrid('getCell', id, 'cantidad'));

        if(tipo_moneda == 'USD') {
            if(id_cod_moneda == 'PEN') {
                //desea pasar de soles a dólares.
                precio = round_math(parseFloat(precio/tipo_cambio), 4);
                p_unit_sin_igv = round_math(parseFloat(precio/(1 + factor_igv)), 6);
            }
        }

        if(tipo_moneda == 'PEN') {
            if(id_cod_moneda == 'USD') {
                //desea pasar de dólares a soles
                precio = round_math(parseFloat(precio*tipo_cambio), 4);
                p_unit_sin_igv = round_math(parseFloat(precio/(1 + factor_igv)), 6);
            }
        }

        var importe = round_math(cantidad*precio,2);
        var subtotal = round_math(importe/(1 + factor_igv), 2);
        var igv = round_math(importe - subtotal, 2);
        
        var codigo = grid.jqGrid('getCell', id, 'codigo');
        var subtotal_icbper = grid.jqGrid('getCell', id, 'subtotal_icbper');
        var item_detraccion_codigo = grid.jqGrid('getCell', id, 'item_detraccion_codigo');
        var item_detraccion_porcentaje = grid.jqGrid('getCell', id, 'item_detraccion_porcentaje');

        var select_unidadmedida = grid.jqGrid('getCell', id, 'select_unidadmedida');
        var tipo_unidad = grid.jqGrid('getCell', id, 'tipo_unidad');
        var idpresentacion = grid.jqGrid('getCell', id, 'idpresentacion');

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
            cantidad: cantidad,
            subtotal: subtotal,
            igv: igv,
            importe: importe,
            estado: 'V',
            codigo: codigo,
            html_lista_precios: grid.jqGrid('getCell', id, 'html_lista_precios'),
            item_detraccion_codigo: item_detraccion_codigo,
            item_detraccion_porcentaje: item_detraccion_porcentaje,

            select_unidadmedida: select_unidadmedida,
            tipo_unidad: tipo_unidad,
            idpresentacion: idpresentacion,

            p_unit_sin_igv: p_unit_sin_igv,
            factor_igv_sunat: factor_igv,
            stock_actual: grid.jqGrid('getCell', id, 'stock_actual')
        };

        grid.jqGrid('setRowData', id, data);
    }

    calcular_totales_documento();
}

function valida_si_existe_item(row_indentificador_nuevo, data_grid_new) {
    var grid = jQuery("#detalle_documento");
    var ids = grid.jqGrid('getDataIDs');
    for (var i = 0; i < ids.length; i++) {
        var id = ids[i];
        var data_anterior = jQuery("#detalle_documento").jqGrid('getRowData', id);
        if(data_grid_new.descripcion === data_anterior.descripcion) {
            return true;
        }
        /*
        var row_indentificador = grid.jqGrid('getCell', id, 'row_identificadador');
        if(row_indentificador == row_indentificador_nuevo) {
            return true;
        }
        */
    }

    return false;
}

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

function escapeHtml(unsafe) {
    return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}
  
function unescapeHtml(safe) {
    return safe
      .replace(/&amp;/g, "&")
      .replace(/&lt;/g, "<")
      .replace(/&gt;/g, ">")
      .replace(/&quot;/g, '"')
      .replace(/&#039;/g, "'");
}

function add_to_detalle() {
    var numero_decimales = 2;
    if (typeof num_decimales !== 'undefined') {
        if(num_decimales > 2) {
            numero_decimales = num_decimales;
        }
    }

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
    var html_lista_precios= escapeHtml($("#contenido_listaprecios").html());
    var item_detraccion_codigo = $("#item_detraccion_codigo").val();
    var item_detraccion_porcentaje = $("#item_detraccion_porcentaje").val();
    var stock_actual = parseFloat($("#stock_actual").val());

    var factor_igv_sunat = get_igv_sunat();

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

    if(precio === undefined || precio == '' || precio < 0) {
        swal({   
            title:'Error',   
            text: 'Debes agregar un precio válido!',
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

    var p_unit_sin_igv = $('#producto_preciounidad').val(); //producto_preciounidad
    if(id_tipoafectacionigv == 10 || id_tipoafectacionigv == 7152) {
        p_unit_sin_igv = $("#producto_preciounidad_sin_igv").val();
    } else {
        p_unit_sin_igv = $('#producto_preciounidad').val();
    }

    var descripcion_sin_espacios = descripcion_prod.replace(/\s/g, "").toLowerCase();
    var descripcion_sin_espacios = removeSpecialChars(descripcion_sin_espacios);
    var row_identificadador = descripcion_sin_espacios + '|.|.|' + idarticulo; // + '_' + Math.random().toString(36).substr(2, 9);
    var identificador_unico = get_uid();
    row_identificadador = get_uid();

    var afecto_icbper = 'no';
    var monto_impuesto_icbper = 0.2;
    
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
        var preciosinigv = parseFloat($(this).data('preciosinigv'));
        var precioconigv = round_math(preciosinigv*(1 + factor_igv_sunat), numero_decimales);

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
            precioconigv        : precioconigv,
            preciosinigv        : preciosinigv,
            idcodmoneda         : $(this).data('idcodmoneda'),
            preciominimo        : $(this).data('preciominimo')
        };

        opciones_select.push(opcion_select);
    });

    var json_select_unidadmedida = JSON.stringify(opciones_select);
    var tipo_unidad = $("#producto_unidadmedida").find(':selected').data('tipo');
    var idpresentacion = $("#producto_unidadmedida").find(':selected').data('idpresentacion');
    
    var data = {
        row_identificadador: row_identificadador,
        idarticulo: idarticulo,
        afecto_icbper: afecto_icbper,
        subtotal_icbper: subtotal_icbper,
        id_tipoafectacionigv: id_tipoafectacionigv,
        descripcion: $('#producto_descripcion').val(),
        text_tipo_afecigv: text_tipo_afecigv[0],
        idunidadmedida: $('#producto_unidadmedida').val(),
        unidadmedida: $("#producto_unidadmedida option:selected" ).text(),
        precio: precio,
        id_cod_moneda: $("#id_cod_moneda").val(),
        cantidad: cantidad,
        subtotal: subtotal,
        igv: igv_producto,
        importe: importe,
        estado: 'V',
        codigo: codigo,
        html_lista_precios: html_lista_precios,
        item_detraccion_codigo: item_detraccion_codigo,
        item_detraccion_porcentaje: item_detraccion_porcentaje,
        select_unidadmedida: json_select_unidadmedida,
        tipo_unidad: tipo_unidad,
        idpresentacion: idpresentacion,

        p_unit_sin_igv: p_unit_sin_igv,
        factor_igv_sunat: factor_igv_sunat,
        stock_actual: stock_actual
    };

    var key_row = $("#key_row").val();
	if(key_row != '') {
        var su = jQuery('#detalle_documento').jqGrid('setRowData', key_row, data);
        calcular_totales_documento();
        //$("#vm_agregar_articulo").modal("hide");

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

function inicializar_tabla_detalle() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	detalle_documento = $('#detalle_documento');

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

            'stock_actual'
        ],
        colModel: [
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'idarticulo', index: '2', hidden: true},
            { name: 'afecto_icbper', index: '1', hidden: true},
            { name: 'subtotal_icbper', index: '1', hidden: true},
            { name: 'id_tipoafectacionigv', index: '3', hidden: true},
            { name: 'descripcion', index: '4', width: 360, },
            { name: 'text_tipo_afecigv', index: '5', width: 120, },
            { name: 'idunidadmedida', index: '6', hidden: true },
            { name: 'unidadmedida', index: '7', width: 120 },
            { name: 'precio', index: '8', width: 80, align: "right", sorttype: 'float' },
            { name: 'id_cod_moneda', index: '9', hidden: true},
            { name: 'cantidad', index: '10', width: 80, align: "right", sorttype: 'float' },
            { name: 'subtotal', index: '11', width: 85, align: "right", sorttype: 'float' },
            { name: 'igv', index: '12', width: 60, align: "right", sorttype: 'float' },
            { name: 'importe', index: '13', width: 90, align: "right", sorttype: 'float' },
            { name: 'estado', index: '14', hidden: true },
            { name: 'codigo', index: '15', hidden: true},
            { name: 'html_lista_precios', index: '15', hidden: true},
            { name: 'item_detraccion_codigo', index: '15', hidden: true},
            { name: 'item_detraccion_porcentaje', index: '15', hidden: true},
            { name: 'select_unidadmedida', index: '15', hidden: true},
            { name: 'tipo_unidad', index: '15', hidden: true},
            { name: 'idpresentacion', index: '15', hidden: true},
            { name: 'p_unit_sin_igv', index: '15', hidden: true},
            { name: 'factor_igv_sunat', index: '15', hidden: true},
            { name: 'stock_actual', index: '15', hidden: true}
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
            calcular_totales_documento();
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            //SE EJECUTA CUANDO MODIFICAMOS UN REGISTRO
            //                        calculateImporte();
            //                        calculateTotal();
        },
        ondblClickRow: function(rowid) {
            $(".btn_editarproducto").trigger('click');
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
 
function removeSpecialChars(str) {
    return str.replace(/(?!\w|\s)./g, '')
    .replace(/\s+/g, ' ')
    .replace(/^(\s*)([\W\w]*)(\b\s*$)/g, '$2');
}