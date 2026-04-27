$(function() {
    $("#btn_transformacion").click(function() {
        $("#vm_transformacion").modal({
			backdrop: 'static',
			keyboard: false
		});
        $("#key_row_transf_inicial").val("");
        $("codigo_producto_transf_inicial").val("");
        $("#select_producto_buscar_4").empty();
        $('#descripcion_prod_transf_inicial').val('');
        $('#cantidad_transf_inicial').val('');
        $('#unidad_medida_transf_inicial').val('');
        $('#lista_productos_transf_inicial').jqGrid('clearGridData');

        $("#key_row_transf_final").val("");
        $("codigo_producto_transf_final").val("");
        $("#select_producto_buscar_5").empty();
        $('#descripcion_prod_transf_final').val('');
        $('#cantidad_transf_final').val('');
        $('#unidad_medida_transf_final').val('');
        $('#lista_productos_transf_final').jqGrid('clearGridData');
    });

    $(".btn_agregar_prod_transf_inicial").click(add_to_detalle_prod_iniciales);
    $(".btn_eliminar_prod_transf_inicial").click(eliminar_producto_detalle_iniciales);

    $(".btn_agregar_prod_transf_final").click(add_to_detalle_prod_finales);
    $(".btn_eliminar_prod_transf_final").click(eliminar_producto_detalle_finales);

    $('#select_producto_buscar_4').on('change', get_data_producto_4);
    $('#select_producto_buscar_5').on('change', get_data_producto_5);
    
    inicializar_buscadores_productos_transformacion();
    inicializar_grid_transf_prod_iniciales();
    inicializar_grid_transf_prod_finales();

    $("#btn_gistrar_transformacion").click(registrar_transformacion);

    $("#btn_transformacion_reporte").on('click', function() {
        $("#vm_reporte_transformacion").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#opt_tab_tranformacion_lista").trigger('click');
		$("#tab_transformacion_content").hide();
    });

    $("#btn_extraer_transformaciones").click(get_lista_transformaciones);
    $(".rep_transformacion_id_sucursal").select2();
    $("#rep_transformacion_id_sucursal").on('change', function() {
        $("#tab_transformacion_content").hide();
    });
});

function get_lista_transformaciones() {
    var light = $('#body_vm_reporte_transformacion');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Espere un momento...</p>',
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

    var id_sucursal = $("#rep_transformacion_id_sucursal").val();
    var fecha_inicio = $("#rep_transformacion_fecha_inicio").val();
    var fecha_fin = $("#rep_transformacion_fecha_fin").val();
    var id_sucursal_destino = '';
	
	dataTable_lista_transformaciones = $('#tbl_lista_transformacion').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/productomovimientos/get_lista_movimientos", // json datasource
			data: {id_sucursal: id_sucursal, id_sucursal_destino: id_sucursal_destino, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, tipo_movimiento: 'lista_transformacion'},
			type: "post",
			error: function(){
				$(".tbl_lista_transformacion-error").html("");
				$("#tbl_lista_transformacion").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_transformacion_processing").css("display","none");
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
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-indigo btn-icon',
                collectionLayout: 'fixed two-column'
            }
        ],
        "columns": [
            { "data": "serie_correlativo", "visible": true},
            { "data": "fecha_movimiento", "visible": true },
            { "data": "usuario", "visible": true },
            { "data": "id_sucursal", "visible": true },
            { "data": "info_sucursal_origen", "visible": true },
            { "data": "id_sucursal_destino", "visible": true },
            { "data": "info_sucursal_destino", "visible": true },
            { "data": "nota", "visible": true },
            { "data": "opciones", "visible": true },
          ],
        stateSave: false,
        initComplete: function(){
            $(light).unblock();
        }
    });

    
	dataTable_ldetalle_transform = $('#tbl_lista_detalle_transformacion').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/productomovimientos/get_lista_movimientos", // json datasource
			data: {id_sucursal: id_sucursal, id_sucursal_destino: id_sucursal_destino, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, tipo_movimiento: 'lista_detalle_transformacion'},
			type: "post",
			error: function(){
				$(".tbl_lista_detalle_transformacion-error").html("");
				$("#tbl_lista_detalle_transformacion").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_detalle_transformacion_processing").css("display","none");
				
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
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-indigo btn-icon',
                collectionLayout: 'fixed two-column'
            }
        ],
        "columns": [
            { "data": "serie_correlativo", "visible": true},
            { "data": "fecha_movimiento", "visible": true },

            { "data": "usuario", "visible": false },
            { "data": "id_sucursal", "visible": false },
            { "data": "info_sucursal_origen", "visible": false },
            { "data": "id_sucursal_destino", "visible": false },
            { "data": "info_sucursal_destino", "visible": false },
            { "data": "nota", "visible": false },
            { "data": "id_codigomoneda", "visible": false },

            { "data": "o_nom_prod", "visible": true },
            { "data": "cantidad", "visible": true },

            { "data": "o_id_u_medida", "visible": false },
            { "data": "o_u_medida", "visible": true },
            { "data": "o_precio", "visible": false },
            { "data": "o_id_afectigv", "visible": false },
            { "data": "o_tipo_unidad", "visible": false },
            { "data": "o_id_presentacion", "visible": false },
            { "data": "o_cod_prod", "visible": false },
            { "data": "o_id_prod", "visible": false },
            

            { "data": "d_nom_prod", "visible": true },
            { "data": "d_id_u_medida", "visible": false },
            { "data": "d_u_medida", "visible": true },
            { "data": "d_precio", "visible": false },
            { "data": "d_id_afectigv", "visible": false },
            { "data": "d_tipo_unidad", "visible": false },
            { "data": "d_id_presentacion", "visible": false },
            { "data": "d_cod_prod", "visible": false },
            { "data": "d_id_prod", "visible": false },
            
            { "data": "opciones", "visible": true },
          ],
        stateSave: false,
        initComplete: function(){
            $(light).unblock();
        }
    });

	$("#tab_transformacion_content").show();
}

function registrar_transformacion() {
    var light = $("#content_vm_transformacion");

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

    var lista_prod_iniciales_raw = jQuery("#lista_productos_transf_inicial").getRowData();
    var lista_prod_iniciales = JSON.stringify(lista_prod_iniciales_raw);

    var lista_prod_finales_raw = jQuery("#lista_productos_transf_final").getRowData();
    var lista_prod_finales = JSON.stringify(lista_prod_finales_raw);

	var datastring = $("#data_transformacion").serializeArray();
	datastring.push({ name: "lista_prod_iniciales", value: lista_prod_iniciales });
    datastring.push({ name: "lista_prod_finales", value: lista_prod_finales });

	$.ajax({
        url : '/facturacionv8/producto/iniciar_proceso_transformacion',
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
						url : '/facturacionv8/producto/iniciar_proceso_transformacion',
						method :  'POST',
						data: datastring,
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {

                                $('#lista_productos_transf_inicial').jqGrid('clearGridData');
                                $('#lista_productos_transf_final').jqGrid('clearGridData');
                                $("#nota_transformacion").val('');
                                
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

//FUNCIONES PARA GRID_LISTA_PRODUCTOS_INICIALES
function inicializar_grid_transf_prod_iniciales() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	detalle_documento = $('#lista_productos_transf_inicial');

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
            'Código',
            'Id Almacén',
            'Almacén (Stock -)'
        ],
        colModel: [
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'idarticulo', index: '2', hidden: true},
            { name: 'descripcion', index: '3', width: 350, hidden: false},
            { name: 'unidadmedida', index: '4', width: 100, hidden: false},
            { name: 'cantidad', index: '5', width: 100, hidden: false, align: "right", sorttype: 'float' },
            { name: 'codigo_producto', index: '1', hidden: true},
            { name: 'id_almacen', index: '1', hidden: true},
            { name: 'txt_almacen', index: '1', hidden: true},
        ],
        height: 200,
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
            responsive_table($(".jqGrid_productos_transf_inicial"));
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
                editar_item_tabla_transf_prod_iniciales(rowid);
            }
        }
    });
}

function add_to_detalle_prod_iniciales() {
    var id_almacen = parseInt($('#select_aorigen_transformacion').val());
    var idarticulo = parseInt($('#select_producto_buscar_4').val());
    var descripcion_prod = $('#descripcion_prod_transf_inicial').val();
    var cantidad = parseFloat($('#cantidad_transf_inicial').val());
    var unidad_medida = $('#unidad_medida_transf_inicial').val();
    var codigo_producto = $("#codigo_producto_transf_inicial").val();
    var txt_almacen = $( "#select_aorigen_transformacion option:selected" ).text();
    
    if(isNaN(id_almacen) || (id_almacen === null) || (id_almacen === undefined) || (id_almacen == '') || (id_almacen <= 0)) {
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
    
    if(descripcion_prod == '') {
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

    var descripcion_sin_espacios = descripcion_prod.replace(/\s/g, "").toLowerCase();
    var descripcion_sin_espacios = removeSpecialChars(descripcion_sin_espacios);
    var row_identificadador = descripcion_sin_espacios + '|.|.|' + idarticulo;

    var data = {
        row_identificadador: row_identificadador,
        idarticulo: idarticulo,
        descripcion: descripcion_prod,
        unidadmedida: unidad_medida, //$('#producto_unidadmedida').val(),
        cantidad: cantidad,
        codigo_producto: codigo_producto,
        id_almacen: id_almacen,
        txt_almacen: txt_almacen
    };

    var key_row = $("#key_row_transf_inicial").val();
	if(key_row != '') {
        var su = jQuery('#lista_productos_transf_inicial').jqGrid('setRowData', key_row, data);
        $("#key_row_transf_inicial").val("");
        $("#select_producto_buscar_4").empty();
        btn_prod_transf_inicial_accion = 'agregar';
        $('#select_producto_buscar_4').select2('open');

        $('#descripcion_prod_transf_inicial').val('');
        $('#cantidad_transf_inicial').val('');
        $('#unidad_medida_transf_inicial').val('');
	} else {
        if(valida_si_existe_item_transformacion(jQuery('#lista_productos_transf_inicial'), row_identificadador)) {
            swal({   
                title:'Error',   
                text: 'No puede agregar items repetidos al detalle!',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                $("#key_row_transf_inicial").val("");
                $("#select_producto_buscar_4").empty();
                btn_prod_transf_inicial_accion = 'agregar';
                $('#select_producto_buscar_4').select2('open');
				return false;
            });
            
        } else {
            var su = jQuery('#lista_productos_transf_inicial').addRowData(row_identificadador, data, 'last');
            $("#key_row_transf_inicial").val("");
            $("#select_producto_buscar_4").empty();
            btn_prod_transf_inicial_accion = 'agregar';
            $('#select_producto_buscar_4').select2('open');

            $('#descripcion_prod_transf_inicial').val('');
            $('#cantidad_transf_inicial').val('');
            $('#unidad_medida_transf_inicial').val('');
        }
    }
}

function editar_item_tabla_transf_prod_iniciales(rowid) {
    //var rowid = jQuery("#lista_productos_traslado").jqGrid('getGridParam', 'selrow');
    if(rowid === undefined || rowid == '') {
        return false;
    }

    $("#key_row_transf_inicial").val(rowid);
    var data = jQuery("#lista_productos_transf_inicial").jqGrid('getRowData', rowid);

    $("#select_producto_buscar_4").empty();
    $("#select_producto_buscar_4").append('<option value="' + data.idarticulo + '">' + data.descripcion + '</option>');
    $("#select_producto_buscar_4").val(data.idarticulo).trigger("select2:select");
    $("#descripcion_prod_transf_inicial").val(data.descripcion);
    $("#unidad_medida_transf_inicial").val(data.unidadmedida).trigger("input");
    $("#cantidad_transf_inicial").val(data.cantidad);
    $("#codigo_producto_transf_inicial").val(data.codigo_producto);
    $("#select_aorigen_transformacion").val(data.id_almacen).trigger("select2:select").trigger('change');

}

function eliminar_producto_detalle_iniciales() {
    var rowid = jQuery('#lista_productos_transf_inicial').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#lista_productos_transf_inicial");
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

function get_data_producto_4() {
    var idproducto = $("#select_producto_buscar_4").val();
    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#unidad_medida_transf_inicial").val(data.unidad_medida.nombre);
            $("#descripcion_prod_transf_inicial").val(data.producto.nombre);
            $("#codigo_producto_transf_inicial").val(data.producto.codigo);
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

//FIN: FUNCIONES PARA GRID_LISTA_PRODUCTOS_INICIALES



//FUNCIONES PARA GRID_LISTA_PRODUCTOS_FINALES
function inicializar_grid_transf_prod_finales() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	detalle_documento = $('#lista_productos_transf_final');

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
            'Código',
            'Id Almacén',
            'Almacén (Stock +)'
        ],
        colModel: [
            { name: 'row_identificadador', index: '1', hidden: true},
            { name: 'idarticulo', index: '2', hidden: true},
            { name: 'descripcion', index: '3', width: 350, hidden: false},
            { name: 'unidadmedida', index: '4', width: 100, hidden: false},
            { name: 'cantidad', index: '5', width: 100, hidden: false, align: "right", sorttype: 'float' },
            { name: 'codigo_producto', index: '1', hidden: true},
            { name: 'id_almacen', index: '1', hidden: true},
            { name: 'txt_almacen', index: '1', hidden: true}
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
            responsive_table($(".jqGrid_productos_transf_final"));
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
                editar_item_tabla_transf_prod_finales(rowid);
            }
        }
    });
}

function add_to_detalle_prod_finales() {
    var id_almacen = parseInt($('#select_adestino_transformacion').val());
    var idarticulo = parseInt($('#select_producto_buscar_5').val());
    var descripcion_prod = $('#descripcion_prod_transf_final').val();
    var cantidad = parseFloat($('#cantidad_transf_final').val());
    var unidad_medida = $('#unidad_medida_transf_final').val();
    var codigo_producto = $("#codigo_producto_transf_final").val();
    var txt_almacen = $( "#select_adestino_transformacion option:selected" ).text();
    
    if(isNaN(id_almacen) || (id_almacen === null) || (id_almacen === undefined) || (id_almacen == '') || (id_almacen <= 0)) {
        swal({   
            title:'Error',   
            text: 'Primero debes Seleccionar el Almacén de Destino!',
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
    
    if(descripcion_prod == '') {
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
            text: 'Debes Ingresar la Cantidad',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });

        return false;
    }

    var descripcion_sin_espacios = descripcion_prod.replace(/\s/g, "").toLowerCase();
    var descripcion_sin_espacios = removeSpecialChars(descripcion_sin_espacios);
    var row_identificadador = descripcion_sin_espacios + '|.|.|' + idarticulo;

    var data = {
        row_identificadador: row_identificadador,
        idarticulo: idarticulo,
        descripcion: descripcion_prod,
        unidadmedida: unidad_medida, //$('#producto_unidadmedida').val(),
        cantidad: cantidad,
        codigo_producto: codigo_producto,
        id_almacen: id_almacen,
        txt_almacen: txt_almacen
    };

    var key_row = $("#key_row_transf_final").val();
	if(key_row != '') {
        var su = jQuery('#lista_productos_transf_final').jqGrid('setRowData', key_row, data);
        $("#key_row_transf_final").val("");
        $("#select_producto_buscar_5").empty();
        btn_prod_transf_final_accion = 'agregar';
        $('#select_producto_buscar_5').select2('open');

        $('#descripcion_prod_transf_final').val('');
        $('#cantidad_transf_final').val('');
        $('#unidad_medida_transf_final').val('');
	} else {
        if(valida_si_existe_item_transformacion(jQuery('#lista_productos_transf_final'), row_identificadador)) {
            swal({   
                title:'Error',   
                text: 'No puede agregar items repetidos al detalle!',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                $("#key_row_transf_final").val("");
                $("#select_producto_buscar_5").empty();
                btn_prod_transf_final_accion = 'agregar';
                $('#select_producto_buscar_5').select2('open');
				return false;
            });
            
        } else {
            var su = jQuery('#lista_productos_transf_final').addRowData(row_identificadador, data, 'last');
            $("#key_row_transf_final").val("");
            $("#select_producto_buscar_5").empty();
            btn_prod_transf_final_accion = 'agregar';
            $('#select_producto_buscar_5').select2('open');

            $('#descripcion_prod_transf_final').val('');
            $('#cantidad_transf_final').val('');
            $('#unidad_medida_transf_final').val('');
        }
    }
}

function editar_item_tabla_transf_prod_finales(rowid) {
    //var rowid = jQuery("#lista_productos_traslado").jqGrid('getGridParam', 'selrow');
    if(rowid === undefined || rowid == '') {
        return false;
    }

    $("#key_row_transf_final").val(rowid);
    var data = jQuery("#lista_productos_transf_final").jqGrid('getRowData', rowid);

    $("#select_producto_buscar_5").append('<option value="' + data.idarticulo + '">' + data.descripcion + '</option>');
    $("#select_producto_buscar_5").val(data.idarticulo).trigger("select2:select");
    $("#descripcion_prod_transf_final").val(data.descripcion);
    $("#unidad_medida_transf_final").val(data.unidadmedida).trigger("input");
    $("#cantidad_transf_final").val(data.cantidad);
    $("#codigo_producto_transf_final").val(data.codigo_producto);
    $("#select_adestino_transformacion").val(data.id_almacen).trigger("select2:select");
}

function eliminar_producto_detalle_finales() {
    var rowid = jQuery('#lista_productos_transf_final').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#lista_productos_transf_final");
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

function get_data_producto_5() {
    var idproducto = $("#select_producto_buscar_5").val();
    $.ajax({
        url : '/facturacionv8/producto/get_data_producto',
        method :  'POST',
        data: {idproducto: idproducto},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#unidad_medida_transf_final").val(data.unidad_medida.nombre);
            $("#descripcion_prod_transf_final").val(data.producto.nombre);
            $("#codigo_producto_transf_final").val(data.producto.codigo);
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

//FIN: FUNCIONES PARA GRID_LISTA_PRODUCTOS_FINALES


























function valida_si_existe_item_transformacion(grid, row_indentificador_nuevo) {
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

function inicializar_buscadores_productos_transformacion() {
    $("#select_producto_buscar_4").select2({
        language: "es",
        dropdownParent: $('#vm_transformacion'),
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
              idsucursal: $("#select_aorigen_transformacion").val()
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
    
    $("#select_producto_buscar_5").select2({
        language: "es",
        dropdownParent: $('#vm_transformacion'),
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
              idsucursal: $("#select_adestino_transformacion").val()
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




