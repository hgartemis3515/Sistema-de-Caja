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