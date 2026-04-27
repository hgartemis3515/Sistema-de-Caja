$(function() {
	inicializar_grid_presentaciones_producto();
	$("#nuevo_producto_nombre_servicio").on('input', function() {
		var id_cod_moneda = $("#nuevo_producto_moneda").val();
		var simbolo_moneda = 'S/. ';
        if(id_cod_moneda == 'USD') {
			simbolo_moneda = '$ ';	
		}
		var precio_venta = 0;
		var id_tipoafectacionigv = parseInt($('#nuevo_producto_tipo_afect_igv').val()) + 0;
        if(id_tipoafectacionigv != 10 && id_tipoafectacionigv != 7152) {
			precio_venta = $('#nuevo_producto_valor_sin_igv').val();
			$("#texto_presentacion_pventa").html('Precio Venta (sin IGV)');
		} else {
			precio_venta = $('#nuevo_producto_valor_con_igv').val();
			$("#texto_presentacion_pventa").html('Precio Venta (Inc. IGV)');
		}
		var unidad_medida_base_nombre = $("#nuevo_producto_unidad option:selected").text();
		var id_unidad_medida_base = $("#nuevo_producto_unidad").val()
		$(".presentacion_nombre_producto").html($("#nuevo_producto_nombre_servicio").val() + " - P.V. " + simbolo_moneda + " " + precio_venta + " - UNID.MEDIDA: " + unidad_medida_base_nombre);
		$(".presentacion_unidad_base").html(unidad_medida_base_nombre);
	});
	$(".btn_agregar_nueva_presentacion").click(add_to_presentacion_producto);
	$("#select_presentacion_nueva_unidad").select2({
        language: "es",
        dropdownParent: $('#vm_agregar_articulo')
    });
    get_lista_unidades_medida($("#select_presentacion_nueva_unidad"));
    $("#btn_eliminar_presentacion").click(eliminar_presentacion_producto);
});

function add_to_presentacion_producto() {
	var numero_decimales = 2;
	if (typeof num_decimales !== 'undefined') {
		if(num_decimales > 2) {
			numero_decimales = num_decimales;
		}
	}

	var row_identificadador = '';
    var id_producto = $("#idproducto_newprod").val();
    var codigo = $("#presentacion_codigo").val();
    var nombre_presentacion = $("#presentacion_nombre").val();
	var id_unidad_presentacion = $("#select_presentacion_nueva_unidad").val();
	var id_unidad_base = $("#nuevo_producto_unidad").val();
	var nombre_unidad_presentacion = $("#select_presentacion_nueva_unidad option:selected").text();
	var cantidad = $("#presentacion_cantidad").val();
	var nombre_unidad_base = $("#nuevo_producto_unidad option:selected").text();
	var precio = $("#presentacion_pventa").val();
    var precio_minimo = $("#presentacion_pventa_minimo").val();
	var precio_con_igv = 0;
	var precio_sin_igv = 0;

	if(isNaN(cantidad) || cantidad === null || cantidad === undefined || cantidad == '' || cantidad <= 0) {
        swal({   
            title:'Error',   
            text: 'Debes Ingresar la Cantidad que Contiene la Nueva Presentación',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        $("#presentacion_cantidad").focus().select();
        return false;
	}
	
	if(isNaN(precio) || precio === null || precio === undefined || precio == '' || precio <= 0) {
        swal({   
            title:'Error',   
            text: 'Debes Ingresar el Precio para la Nueva Presentación',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        $("#presentacion_pventa").focus().select();
        return false;
    }

    if(isNaN(precio_minimo) || precio_minimo === null || precio_minimo === undefined || precio_minimo == '' || precio_minimo <= 0) {
        swal({   
            title:'Error',   
            text: 'Debes Ingresar el Precio Mínimo para la Nueva Presentación',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        $("#presentacion_pventa_minimo").focus().select();
        return false;
    }
    
    if(codigo === null || codigo === undefined || codigo == '') {
        swal({   
            title:'Error',   
            text: 'Debes Ingresar un Código para la Presentación o Generar un Código',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        $("#presentacion_pventa").focus().select();
        return false;
    }
    
    if(nombre_presentacion === null || nombre_presentacion === undefined || nombre_presentacion == '') {
        swal({   
            title:'Error',   
            text: 'Debes Ingresar un Nombre para la presentación.',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        $("#presentacion_pventa").focus().select();
        return false;
	}

	var id_tipoafectacionigv = parseInt($('#nuevo_producto_tipo_afect_igv').val()) + 0;
	if(id_tipoafectacionigv != 10 && id_tipoafectacionigv != 7152) {
		precio_con_igv = round_math(parseFloat(precio)*1.18, numero_decimales);;
		precio_sin_igv = precio;
	} else {
		precio_con_igv = precio;
		precio_sin_igv = round_math(parseFloat(precio)/1.18, numero_decimales);
    }
    
    var descripcion_sin_espacios = nombre_presentacion.replace(/\s/g, "").toLowerCase();
    var descripcion_sin_espacios = removeSpecialChars(descripcion_sin_espacios);
    var row_identificadador = descripcion_sin_espacios + '|.|.|' + id_unidad_presentacion;
    var row_identificadador = codigo; //valida que el código de cada presentación sea único, ayuda a controlar mejor cuando se busca por código en el punto de ventas
	
    var data = {
        row_identificadador: row_identificadador,
        id_producto: id_producto,
        codigo: codigo,
        id_unidad_presentacion: id_unidad_presentacion,
        id_unidad_base: id_unidad_base, //$('#producto_unidadmedida').val(),
        nombre_presentacion: nombre_presentacion,
        nombre_unidad_presentacion: nombre_unidad_presentacion,
		cantidad: cantidad,
		nombre_unidad_base: nombre_unidad_base,
		precio_con_igv: precio_con_igv,
		precio_sin_igv: precio_sin_igv,
        precio_minimo: precio_minimo
    };

    var key_row = $("#key_row_presentacion").val();
	if(key_row != '') {
        var su = jQuery('#lista_presentaciones_producto').jqGrid('setRowData', key_row, data);
        $("#key_row_presentacion").val("");
		$("#presentacion_cantidad").val("");
        $("#presentacion_pventa").val("");
        $("#presentacion_pventa_minimo").val("");
        $("#presentacion_codigo").val("");
        $("#presentacion_nombre").val("");
        btn_productos_accion = 'agregar';
	} else {
        if(valida_si_existe_presentacion(row_identificadador)) {
            swal({   
                title:'Error',   
                text: 'No puede agregar códigos repetidos en las presentaciones!',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				btn_productos_accion = 'agregar';
				return false;
            });
            
        } else {
            var su = jQuery('#lista_presentaciones_producto').addRowData(row_identificadador, data, 'last');
            $("#key_row_presentacion").val("");
			$("#presentacion_cantidad").val("");
            $("#presentacion_pventa").val("");
            $("#presentacion_pventa_minimo").val("");
            $("#presentacion_codigo").val("");
            $("#presentacion_nombre").val("");
			btn_productos_accion = 'agregar';
			return false;
        }
    }
}

function removeSpecialChars(str) {
    return str.replace(/(?!\w|\s)./g, '')
    .replace(/\s+/g, ' ')
    .replace(/^(\s*)([\W\w]*)(\b\s*$)/g, '$2');
}

function valida_si_existe_presentacion(row_indentificador_nuevo) {
    var grid = jQuery("#lista_presentaciones_producto");
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

function inicializar_grid_presentaciones_producto() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	lista_presentaciones_producto = $('#lista_presentaciones_producto');
	
	lista_presentaciones_producto.jqGrid({
        url: '',
        datatype: 'json',
        mtype: 'POST',
        colNames: [
            'row_identificadador',
            'id_producto',
            'Código',
			'id_unidad_presentacion',
            'id_unidad_base',
            'Nombre',
            'Presentación',
			'Cantidad',
			'Unidad Base',
			'Precio',
			'Precio sin IGV',
            'Precio Mínimo'
        ],
        colModel: [
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'id_producto', index: '2', hidden: true},
            { name: 'codigo', index: '2', hidden: false},
			{ name: 'id_unidad_presentacion', index: '3', hidden: true},
            { name: 'id_unidad_base', index: '4', hidden: true},
            { name: 'nombre_presentacion', index: '5', hidden: false},
            { name: 'nombre_unidad_presentacion', index: '6' },
            { name: 'cantidad', index: '7', width: 100, align: "right", sorttype: 'float' },
			{ name: 'nombre_unidad_base', index: '8', hidden: true },
			{ name: 'precio_con_igv', index: '9' },
			{ name: 'precio_sin_igv', index: '10', hidden: true},
            { name: 'precio_minimo', index: '11', hidden: false }
        ],
        height: 150,
        rowNum: 0,
        loadOnce: true,
        viewrecords: true,
        gridview: true,
        autowidth:true, 
        shrinkToFit:false,
        forceFit:true,
        cellEdit: true,
        scroll: true,
        sortname: 'cantidad',
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
                editar_item_tabla_presentaciones(rowid);
            }
        }
    });
}

function editar_item_tabla_presentaciones(rowid) {
    
    if(rowid === undefined || rowid == '') {
        return false;
	}
	
    $("#key_row_presentacion").val(rowid);
	var data = jQuery("#lista_presentaciones_producto").jqGrid('getRowData', rowid);
	
	$("#select_presentacion_nueva_unidad").val(data.id_unidad_presentacion).trigger("select2:select");
	$("#presentacion_cantidad").val(data.cantidad);

	var id_tipoafectacionigv = parseInt($('#nuevo_producto_tipo_afect_igv').val()) + 0;
	if(id_tipoafectacionigv != 10 && id_tipoafectacionigv != 7152) {
		$("#presentacion_pventa").val(data.precio_sin_igv);
	} else {
		$("#presentacion_pventa").val(data.precio_con_igv);
    }

    $("#presentacion_pventa_minimo").val(data.precio_minimo);
    
    $("#presentacion_codigo").val(data.codigo);
    $("#presentacion_nombre").val(data.nombre_presentacion);
}

function eliminar_presentacion_producto() {
    var rowid = jQuery('#lista_presentaciones_producto').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#lista_presentaciones_producto");
    if(rowid == null) {
        swal({   
            title:'Error',   
            text: 'Debes una de las presentaciones para proceder con su eliminación!!',
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